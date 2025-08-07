<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Models\User;

Route::get('/', function () {
    return view('index');
})->name('home');


// for user route
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif (auth()->user()->role === 'assistant') {
        return redirect()->route('assistant.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// for admin route 
Route::get('/admin/dashboard', function () {
    $userCount = User::where('role', 'user')->count();
    return view('admin.dashboard', compact('userCount'));
})->middleware(['auth', 'verified'])->name('admin.dashboard');

// for news and alumni stored data
Route::post('/admin/alumnipost', [App\Http\Controllers\AlumniController::class, 'store'])->name('alumnipost.store');
Route::post('/admin/news', [App\Http\Controllers\NewsController::class, 'store'])->name('news.store');

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

// Common routes for assistant and admin
Route::get('/admin/giving-back', function () {
    return view('admin.givingBack');
})->middleware(['auth', 'verified'])->name('givingBack');

Route::get('/accounts', function () {
    $users = App\Models\User::where('role', 'user')->get();
    return view('admin.accounts', compact('users'));
})->middleware(['auth', 'verified'])->name('accounts');

Route::get('/admin/news', function () {
    $news = App\Models\News::latest()->get();
    $alumniPosts = App\Models\Alumni::latest()->get();

    $featuredNews = $news->first();
    $featuredAlumni = $alumniPosts->first();

    return view('admin.news', compact('news', 'alumniPosts', 'featuredNews', 'featuredAlumni'));
})->middleware(['auth', 'verified'])->name('news');

Route::get('/events', function () {
    return view('events');
})->middleware(['auth', 'verified'])->name('events');

Route::get('/resume', function () {
    return view('resume');
})->middleware(['auth', 'verified'])->name('resume');

Route::get('/reports', function () {
    return view('reports');
})->middleware(['auth', 'verified'])->name('reports');

// Route::get('/accounts', function () {
//     return view('accounts');
// })->middleware(['auth', 'verified'])->name('accounts');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
