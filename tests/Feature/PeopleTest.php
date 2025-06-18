<?php

namespace Tests\Feature;

use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PeopleTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // $this->artisan('migrate:fresh');
        $this->user = User::factory()->create();
        $this->withSession([])->actingAs($this->user);
    }

    public function test_dashboard_is_displayed_for_authenticated_user(): void
    {
        $response = $this->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_email_is_queued_when_person_is_created(): void
    {
        Mail::fake();

        $person = Person::factory()->make();

        $response = $this->post('/person', [
            '_token' => \csrf_token(),
            '_date_format' => 'Y-m-d',
            'name' => $person->name,
            'surname' => $person->surname,
            'sa_id_number' => $person->sa_id_number,
            'mobile_number' => $person->mobile_number,
            'email' => $person->email,
            'birth_date' => $person->birth_date->format('Y-m-d'),
            'language_code' => $person->language_code,
            'interests' => $person->interests,
        ]);

        $response->assertRedirect('/dashboard');

        $dbPerson = DB::table('people')
            ->where('sa_id_number', $person->sa_id_number)
            ->whereNull('deleted_at')
            ->first();

        $this->assertNotNull($dbPerson);

        $this->assertSame($person->name, $dbPerson->name);
        $this->assertSame($person->surname, $dbPerson->surname);
        $this->assertSame($person->sa_id_number, $dbPerson->sa_id_number);
        $this->assertSame($person->mobile_number, $dbPerson->mobile_number);
        $this->assertSame($person->email, $dbPerson->email);
        $this->assertSame($person->birth_date->format('Y-m-d'), \Carbon\Carbon::parse($dbPerson->birth_date)->format('Y-m-d'));
        $this->assertSame($person->language_code, $dbPerson->language_code);
        $this->assertEqualsCanonicalizing($person->interests, json_decode($dbPerson->interests, true));

        // Doing a assertDatabaseHas fails on GitHub because the content of interests does not match the json_decoded string

        Mail::assertQueued(\App\Mail\PersonCreatedMail::class, function ($mail) use ($person) {
            return $mail->hasTo($person->email);
        });

    }

    public function test_user_can_view_the_edit_person_form()
    {
        $person = Person::factory()->create();

        $response = $this->get(route('person.edit', $person));

        $response->assertStatus(200);
        $response->assertViewIs('person.form');
        $response->assertViewHas('person', $person);
        $response->assertSee($person->name); // basic content check
    }

    public function test_user_can_update_a_person()
    {
        $person = Person::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $dateFormat = 'Y-m-d';

        $updatedData = [
            '_token' => \csrf_token(),
            '_date_format' => $dateFormat,
            'name' => 'Updated Name',
            'surname' => $person->surname,
            'email' => 'updated@example.com',
            'sa_id_number' => $person->sa_id_number,
            'birth_date' => $person->birth_date->format($dateFormat),
            'mobile_number' => $person->mobile_number,
            'language_code' => $person->language_code,
            'interests' => $person->interests, // may be JSON or array
        ];

        $response = $this->put(route('person.update', $person), $updatedData);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('status', 'person-updated');

        $this->assertDatabaseHas('people', [
            'id' => $person->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'updated_user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_soft_delete_a_person()
    {
        $person = Person::factory()->create();

        $response = $this->delete(route('person.destroy', $person), [
            '_token' => \csrf_token(),
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('status', 'person-deleted');

        $this->assertSoftDeleted('people', [
            'id' => $person->id,
        ]);
    }
}
