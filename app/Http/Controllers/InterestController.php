<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InterestController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function index()
    {
        $interests = \App\Models\Option::where('name', 'interests')->first()->value;

        return view('interests', [
            'interests' => $interests,
        ]);
    }

    public function syncInterests(Request $request)
    {
        $validated = $request->validate([
            'interests' => 'array',
            'interests.*' => 'string|max:255',
        ]);

        \App\Models\Option::where('name', 'interests')->update(['value' => $validated['interests']]);

        return response()->json(['status' => 'ok']);
    }
}
