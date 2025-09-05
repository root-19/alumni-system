<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Models\User;
use Livewire\Livewire;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
// use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Livewire\Auth\Register;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonationController;
// use App\Http\Controllers\TrainingController;
use App\Http\Controllers\Admin\TrainingController;
// use App\Http\Controllers\TrainingController;
// use App\Http\Controllers\Admin\TrainingController;


// use Illuminate\Support\Facades\Route;
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
Route::get('/trainings', [\App\Http\Controllers\TrainingController::class, 'index'])
    ->name('trainings.index');

    Route::get('/trainings/{training}/take', [TrainingController::class, 'take'])
    ->name('training.take');

Route::get('/dashboard', [NewsController::class, 'index'])->name('dashboard');

Route::get('/resumes/{resume}', [ResumeController::class, 'show'])->name('resumes.show');
Route::get('/resume-view', [ResumeController::class, 'showResumeViewer'])->name('resume-view');

Route::get('/resume-view/{id}', [ResumeController::class, 'viewResumeInViewer'])->name('resume-view.show');
Route::get('/resumes', [ResumeController::class, 'index'])->name('resumes.index');
Route::post('/resumes', [ResumeController::class, 'store'])->name('resumes.store');
Route::get('/resumes/{resume}', [ResumeController::class, 'show'])->name('resumes.show'); // <--
Route::delete('/resumes/{resume}', [ResumeController::class, 'destroy'])->name('resumes.destroy');

Route::get('/resumes/view/{id}', [ResumeController::class, 'viewResume'])->name('resumes.view');

// Route::get('/admin/events/{post}', [AlumniController::class, 'show'])->name('admin.events.show');

Route::get('events/{post}', [AlumniController::class, 'show'])
    ->name('events.show');

Route::get('/donations', function () {
    $news = \App\Models\News::latest()->get();
    $alumniPosts = \App\Models\AlumniPost::latest()->get();

    $featuredNews = $news->first();
    $featuredAlumni = $alumniPosts->first();

    return view('donations', compact('news', 'alumniPosts', 'featuredNews', 'featuredAlumni'));
})->name('donations');
Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');

Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
Route::middleware(['auth'])->group(function () {
    Route::post('/alumni_posts', [AlumniController::class, 'store'])->name('alumni_posts.store');
 


});

/*
|--------------------------------------------------------------------------
| Admin Dashboard & Pages
|--------------------------------------------------------------------------
*/


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('trainings', [TrainingController::class, 'index'])->name('trainings.index');
    Route::get('trainings/create', [TrainingController::class, 'create'])->name('trainings.create');
    Route::post('trainings', [TrainingController::class, 'store'])->name('trainings.store');
});

// Route::middleware(['auth', 'can:isAdmin'])->group(function () {
//     Route::get('/admin/users/create', App\Livewire\Admin\Register::class)->name('admin.users.create');
// });

Route::middleware(['auth', 'verified'])->group(function () {

Route::get('/admin/register', [RegisterController::class, 'showForm'])->name('admin.register.form');
Route::post('/admin/register', [RegisterController::class, 'store'])->name('admin.register.store');
Route::post('/events/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::post('/comments/{comment}/replies', [CommentController::class, 'reply'])->name('comments.reply');
Route::post('/comments/{comment}/like', [CommentController::class, 'like'])->name('comments.like');

    Route::get('/alumni_posts', [AlumniController::class, 'index'])->name('alumni_posts.index');

// Store a new post
Route::post('/alumni_posts', [AlumniController::class, 'store'])->name('alumni_posts.store');

    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
        $userCount = User::where('role', 'user')->count();
        return view('admin.dashboard', compact('userCount'));
    })->name('admin.dashboard');


    // Events routes - accessible by both admin and users
    Route::get('/events', [AlumniController::class, 'index'])->name('events');
    Route::get('/events/{post}', [AlumniController::class, 'show'])->name('events.show');
    
    // Admin-only events management
    Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
        Route::get('/events', [AlumniController::class, 'adminIndex'])->name('admin.events.index');
        Route::post('/events', [AlumniController::class, 'store'])->name('admin.events.store');
        Route::get('/events/{post}', [AlumniController::class, 'adminShow'])->name('admin.events.show');
    });
    
    // User interactions with events (likes, comments)
    Route::middleware(['auth'])->group(function () {
        Route::post('/posts/{post}/like', [AlumniController::class, 'likePost'])->name('posts.like');
        Route::post('/comments/{comment}/like', [AlumniController::class, 'like'])->name('comments.like');
        Route::post('/alumni_posts', [AlumniController::class, 'store'])->name('alumni_posts.store');
    });



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
        $alumniPosts = \App\Models\AlumniPost::latest()->get();

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

    // Admin Donations Management
    Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
        Route::get('/contributor', function () {
            $donations = \App\Models\Donation::with('user')->latest()->get();
            $totalDonations = $donations->sum('amount');
            $confirmedDonations = $donations->where('status', 'Confirmed')->sum('amount');
            $pendingDonations = $donations->where('status', 'Pending')->sum('amount');
            $donationCount = $donations->count();
            $confirmedCount = $donations->where('status', 'Confirmed')->count();
            $pendingCount = $donations->where('status', 'Pending')->count();
            
            return view('admin.contributor', compact('donations', 'totalDonations', 'confirmedDonations', 'pendingDonations', 'donationCount', 'confirmedCount', 'pendingCount'));
        })->name('admin.contributor');
        
        // Donation management routes
        Route::patch('/donations/{donation}/status', [DonationController::class, 'updateStatus'])->name('admin.donations.status');
        Route::delete('/donations/{donation}', [DonationController::class, 'destroy'])->name('admin.donations.destroy');
    });




    
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


