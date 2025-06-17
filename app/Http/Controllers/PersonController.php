<?php

namespace App\Http\Controllers;

use App\Mail\PersonCreatedMail;
use App\Models\Person;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class PersonController extends Controller
{
    private array $autoloadOptions;

    public function __construct()
    {
        $this->autoloadOptions = app('autoload.options');
    }

    /**
     * Display a listing people.
     */
    public function index()
    {
        $dateFormat = $this->autoloadOptions['l10n']['date_format'];

        $people = Person::all()->map(function ($person) use ($dateFormat) {
            $person->birth_date_formatted = optional($person->birth_date)->format($dateFormat);

            return $person;
        });

        return view('dashboard', ['people' => $people]);
    }

    /**
     * Show the form for creating a new person.
     */
    public function create()
    {
        $interests = \App\Models\Option::where('name', 'interests')->first()->value;

        return view('person.form', [
            'person' => new \App\Models\Person,
            'mode' => 'create',
            'interests' => $interests,
            'autoloadOptions' => $this->autoloadOptions,
        ]);
    }

    /**
     * Store a newly created person in storage.
     */
    public function store(\App\Http\Requests\PersonRequest $request)
    {
        $validated = $request->validated();
        $validated['created_user_id'] = \auth()->id();
        $person = Person::create($validated);

        // Send email
        Mail::to($person->email)->send(new PersonCreatedMail($person));

        return Redirect::route('dashboard')->with('status', 'person-created');
    }

    /**
     * Show the form for editing the specified person.
     */
    public function edit(Person $person)
    {
        $interests = \App\Models\Option::where('name', 'interests')->first()->value;

        $person->birth_date_formatted = optional($person->birth_date)->format($this->autoloadOptions['l10n']['date_format']);

        return view('person.form', [
            'person' => $person,
            'mode' => 'edit',
            'interests' => $interests,
            'autoloadOptions' => $this->autoloadOptions,
        ]);
    }

    /**
     * Update the specified person in storage.
     */
    public function update(\App\Http\Requests\PersonRequest $request, Person $person)
    {
        $validated = $request->validated();
        $validated['updated_user_id'] = \auth()->id();
        $person->update($validated);

        return Redirect::route('dashboard')->with('status', 'person-updated');
    }

    /**
     * Remove the specified person from storage.
     */
    public function destroy(Person $person)
    {
        $person->delete();

        if (request()->expectsJson()) {
            return response()->json(['status' => 'person-deleted']);
        }

        return Redirect::route('dashboard')->with('status', 'person-deleted');
    }
}
