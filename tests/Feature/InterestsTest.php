<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class InterestsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_interests_is_displayed_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $this->withSession([])->actingAs($user);
        $response = $this->get('/interests');

        $response->assertStatus(200);
    }

    public function test_interests_is_not_displayed_for_unauthenticated_users(): void
    {
        $response = $this->get('/interests');

        $response->assertRedirect();
    }

    public function test_sync_interests_updates_database(): void
    {
        $user = User::factory()->create();

        $this->withSession([])->actingAs($user);
        $payload = [
            '_token' => \csrf_token(),
            'interests' => ['Hiking', 'Art', 'Photography'],
        ];

        $response = $this->postJson('/interests/sync', $payload);

        $response->assertStatus(200)->assertJson(['status' => 'ok']);

        $option = DB::table('options')->where('name', 'interests')->first();

        $this->assertNotNull($option);

        $this->assertEqualsCanonicalizing(json_decode($option->value), $payload['interests']);
    }
}
