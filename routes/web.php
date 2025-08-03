<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('index');
})->name('home');


// for user route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// for admin route 
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard');

// for assistant rute
Route::get('/assistant/dashboard', function () {
    return view('assistant.dashboard');
})->middleware(['auth', 'verified'])->name('assistant.dashboard');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/room', function () {
    return view('room');
})->name('room');

Route::get('/view', function () {
    return view('view');
})->name('view');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
