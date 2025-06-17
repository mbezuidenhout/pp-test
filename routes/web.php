<?php

use App\Http\Controllers\InterestController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.register');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/interests', [InterestController::class, 'index'])->middleware(['auth'])->name('interests');
Route::post('/interests/sync', [InterestController::class, 'syncInterests'])->name('interests.sync');

require __DIR__.'/people.php';
require __DIR__.'/auth.php';
