<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Models\User;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
// use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('index');
})->name('home');

/*
|--------------------------------------------------------------------------
| User Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif (auth()->user()->role === 'assistant') {
        return redirect()->route('assistant.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::post('/alumni_posts', [AlumniController::class, 'store'])->name('alumni_posts.store');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard & Pages
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
Route::get('/events/{post}', [PostController::class, 'show'])->name('events.show');
Route::post('/events/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/comments/{comment}/replies', [CommentController::class, 'reply'])->name('comments.reply');
Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');
Route::post('/events/{post}/comments', [CommentController::class, 'store'])
    ->name('comments.store');

    Route::get('/alumni_posts', [AlumniController::class, 'index'])->name('alumni_posts.index');

// Store a new post
Route::post('/alumni_posts', [AlumniController::class, 'store'])->name('alumni_posts.store');

    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
        $userCount = User::where('role', 'user')->count();
        return view('admin.dashboard', compact('userCount'));
    })->name('admin.dashboard');


    //for events
 // Admin routes
Route::get('/admin/eventsAdmin', [AlumniController::class, 'index'])->name('alumni.index');
Route::post('/admin/eventsAdmin', [AlumniController::class, 'store'])->name('alumni.store');
Route::get('/admin/eventsAdmin', fn() => view('admin.eventsAdmin'))->name('events');
// Route::get('/events', [AlumniController::class, 'index'])->name('eventsAdmin');
Route::get('/admin/events', [AlumniController::class, 'index'])->name('eventsAdmin');
// Route::get('/admin/events/{post}', [AlumniController::class, 'show'])->name('admin.events.show');

Route::get('/admin/events/{post}', [AlumniController::class, 'show'])
    ->name('admin.events.show');

// Events list page
Route::get('/events', [AlumniController::class, 'index'])->name('events');

Route::middleware(['auth'])->group(function () {
    Route::post('/posts/{post}/like', [AlumniController::class, 'likePost'])->name('posts.like');
    Route::post('/comments/{comment}/like', [AlumniController::class, 'like'])->name('comments.like');
});


// Show a single event (for adding/viewing comments)
Route::get('/events/{post}', [AlumniController::class, 'show'])->name('admin.events.show');

// Store new event/news post
Route::post('/alumni_posts', [AlumniController::class, 'store'])->name('alumni_posts.store');
// User-facing route
Route::get('/events', [AlumniController::class, 'index'])->name('events.index');



    // Resume Management
    Route::get('/admin/resume', [ResumeController::class, 'index'])->name('resume.index');
    Route::post('/admin/resume', [ResumeController::class, 'store'])->name('resume.store');
    Route::delete('/admin/resume/{resume}', [ResumeController::class, 'destroy'])->name('resume.destroy');
    Route::get('/admin/resume', [ResumeController::class, 'index'])->name('resume');

    // News & Alumni
    Route::post('/admin/alumni_post', [AlumniController::class, 'store'])->name('alumni_post.store');
    Route::post('/admin/news', [NewsController::class, 'store'])->name('news.store');

    Route::get('/admin/news', function () {
        $news = \App\Models\News::latest()->get();
        $alumniPosts = \App\Models\Alumni::latest()->get();

        $featuredNews = $news->first();
        $featuredAlumni = $alumniPosts->first();

        return view('admin.news', compact('news', 'alumniPosts', 'featuredNews', 'featuredAlumni'));
    })->name('news');

    // Admin Static Pages
//     Route::get('/admin/giving-back', fn() => view('admin.givingBack'))->name('givingBack');
//    Route::get('/admin/giving-back', function () {
//     $users = User::where('id', '!=', auth()->id())->get();
//     return view('admin.givingBack', compact('users'));
// })->name('givingBack');




    
 Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/giving-back', function () {
        return view('admin.givingBack');
    })->name('givingBack');
});


    
 Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/report', function () {
        return view('admin.report');
    })->name('report');
});

Route::middleware(['auth'])->group(function () {
    // User Chat Page
    Route::get('/message', function () {
        return view('message'); // Blade file for user chat
    })->name('message');
    
    // Test route for chat functionality
    Route::get('/test-chat', function () {
        $adminCount = \App\Models\User::getAdmins()->count();
        $userCount = \App\Models\User::getRegularUsers()->count();
        $messageCount = \App\Models\Message::count();
        
        return response()->json([
            'admin_count' => $adminCount,
            'user_count' => $userCount,
            'message_count' => $messageCount,
            'status' => 'Chat system is working correctly'
        ]);
    })->name('test.chat');
});
















//     Route::get('/admin/reports', fn() => view('admin.reports'))->name('reports');
//     Route::get('/admin/reports', function () {
//     $users = User::where('id', '!=', auth()->id())->get();
//     return view('admin.reports', compact('users'));
// })->name('reports');
        // Route::get('/admin/reports', fn() => view('reports'))->name('reports');

    Route::get('/accounts', function () {
        $users = \App\Models\User::where('role', 'user')->get();
        return view('admin.accounts', compact('users'));
    })->name('accounts');
});

/*
|--------------------------------------------------------------------------
| Assistant Dashboard
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/assistant/dashboard', fn() => view('assistant.dashboard'))->name('assistant.dashboard');
});

/*
|--------------------------------------------------------------------------
| Common Authenticated Pages
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/events', fn() => view('events'))->name('events');
    Route::get('/room', fn() => view('room'))->name('room');
    Route::get('/view', fn() => view('view'))->name('view');
    // Route::get('/reports', fn() => view('reports'))->name('reports');
});

/*
|--------------------------------------------------------------------------
| Settings Routes (Livewire Volt)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
