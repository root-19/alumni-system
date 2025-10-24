<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;
use App\Models\User;
use Livewire\Livewire;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventRegistrationController;
// use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Livewire\Auth\Register;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DocumentRequestController;
use App\Http\Controllers\ReviewController;
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

  Route::post('/alumni_post', [AlumniController::class, 'store'])->name('alumni_post.store');
    Route::post('/admin/news', [NewsController::class, 'store'])->name('news.store');

    Route::get('/news', function () {
        $news = \App\Models\News::latest()->get();
        $alumniPosts = \App\Models\AlumniPost::latest()->get();

        $featuredNews = $news->first();
        $featuredAlumni = $alumniPosts->first();

        return view('news', compact('news', 'alumniPosts', 'featuredNews', 'featuredAlumni'));
    })->name('news');

Route::get('/trainings', [\App\Http\Controllers\TrainingController::class, 'index'])
    ->name('trainings.index');

    Route::get('/trainings/{training}/take', [\App\Http\Controllers\TrainingController::class, 'take'])
        ->name('training.take');

    Route::post('/trainings/{training}/read/{file}', [\App\Http\Controllers\TrainingController::class, 'markAsRead'])
        ->name('training.read');

    Route::post('/trainings/{training}/progress', [\App\Http\Controllers\TrainingController::class, 'updateProgress'])
        ->name('training.progress');

    Route::post('/trainings/{training}/module-progress/{file}', [\App\Http\Controllers\TrainingController::class, 'updateModuleProgress'])
        ->name('training.module.progress');
        
    Route::get('/trainings/{training}/certificate', [\App\Http\Controllers\TrainingController::class, 'downloadCertificate'])
        ->name('training.certificate');

    // Test route to verify progress saving (remove in production)
    Route::get('/test-progress/{training}', function(\App\Models\Training $training) {
        $user = auth()->user();
        $total = $training->files->where('type', 'module')->count();
        $read = $user->reads()->whereIn('training_file_id', $training->files->pluck('id'))->count();
        $calculatedProgress = $total > 0 ? round(($read / $total) * 100) : 0;
        
        // Get user-specific progress
        $userProgress = \App\Models\UserTrainingProgress::where('user_id', $user->id)
            ->where('training_id', $training->id)
            ->first();
        $storedProgress = $userProgress ? $userProgress->progress : 0;
        
        // Get detailed module progress
        $moduleProgresses = \App\Models\UserModuleProgress::where('user_id', $user->id)
            ->whereIn('training_file_id', $training->files->pluck('id'))
            ->get();

        return response()->json([
            'training_id' => $training->id,
            'training_title' => $training->title,
            'user_id' => $user->id,
            'user_name' => $user->name,
            'global_progress' => $training->progress, // Old global progress
            'user_stored_progress' => $storedProgress,
            'user_calculated_progress' => $calculatedProgress,
            'total_modules' => $total,
            'read_modules' => $read,
            'read_records' => $user->reads()->whereIn('training_file_id', $training->files->pluck('id'))->get(),
            'user_progress_record' => $userProgress,
            'detailed_module_progress' => $moduleProgresses,
            'average_completion' => $moduleProgresses->count() > 0 ? round($moduleProgresses->avg('completion_percentage')) : 0
        ]);
    })->name('test.progress');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
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
    // Bulk user update (admin)
    Route::post('users/bulk-update', [AdminUserController::class, 'bulkUpdate'])
        ->middleware(['auth','admin'])
        ->name('users.bulk-update');
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
    $donations = \App\Models\Donation::with('user')->latest()->take(15)->get();
    $totalDonationAmount = \App\Models\Donation::sum('amount');
    $messageCount = \App\Models\Message::count();
    
    // Get events list for dropdown
    $eventsList = \App\Models\AlumniPost::latest()->take(20)->get();
    
    // Get all reviews with event and user data (no approval filter)
    $reviews = \App\Models\Review::with(['alumniPost', 'user'])
        ->latest()
        ->take(50)
        ->get();
    
    // Get top 7 contributors based on activity
    $topContributors = \App\Models\User::where('role', 'user')
        ->withCount([
            'reviews as review_count',
            'eventRegistrations as attendance_count',
            'donations as donation_count'
        ])
        ->get()
        ->map(function($user) {
            // Calculate contribution score based on various activities
            $reviewScore = $user->review_count * 5; // 5 points per review
            $attendanceScore = $user->attendance_count * 3; // 3 points per event attendance
            $donationScore = \App\Models\Donation::where('user_id', $user->id)->sum('amount') * 0.1; // 0.1 points per peso donated
            
            $user->contribution_score = $reviewScore + $attendanceScore + $donationScore;
            return $user;
        })
        ->sortByDesc('contribution_score')
        ->take(7);
    
    // Prepare chart data - last 12 months
    $chartData = [
        'labels' => [],
        'reviews' => [],
        'attendance' => []
    ];
    
    // Generate last 12 months labels
    for ($i = 11; $i >= 0; $i--) {
        $date = now()->subMonths($i);
        $chartData['labels'][] = $date->format('M Y');
        
        // Count reviews for this month (all reviews, no approval filter)
        $reviewsCount = \App\Models\Review::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();
        $chartData['reviews'][] = $reviewsCount;
        
        // Count attendance for this month
        $attendanceCount = \App\Models\EventRegistration::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();
        $chartData['attendance'][] = $attendanceCount;
    }
    
    // Prepare individual event data for dropdown selection
    $eventsData = [];
    foreach ($eventsList as $event) {
        $eventData = [
            'labels' => [],
            'reviews' => [],
            'attendance' => []
        ];
        
        // Generate last 12 months for this specific event
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $eventData['labels'][] = $date->format('M Y');
            
            // Count reviews for this specific event this month (all reviews, no approval filter)
            $reviewsCount = \App\Models\Review::where('alumni_post_id', $event->id)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $eventData['reviews'][] = $reviewsCount;
            
            // Count attendance for this specific event this month
            $attendanceCount = \App\Models\EventRegistration::where('alumni_post_id', $event->id)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $eventData['attendance'][] = $attendanceCount;
        }
        
        $eventsData[$event->id] = $eventData;
    }
    
    return view('admin.dashboard', compact('userCount','donations','totalDonationAmount','messageCount','eventsList','reviews','chartData','eventsData','topContributors'));
    })->name('admin.dashboard');

    // Notification routes
    Route::get('/notifications/feed', [\App\Http\Controllers\NotificationController::class, 'feed'])->name('notifications.feed');
    Route::post('/notifications/mark-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');


    // Events routes - accessible by both admin and users
    Route::get('/events', [AlumniController::class, 'index'])->name('events');
    Route::get('/events/{post}', [AlumniController::class, 'show'])->name('events.show');
    Route::post('/events/{post}/register', [EventRegistrationController::class, 'register'])->name('events.register');
    Route::delete('/events/{post}/register', [EventRegistrationController::class, 'unregister'])->name('events.unregister');
    
    // User attendance routes
    Route::middleware(['auth'])->group(function () {
        Route::post('/events/{post}/attendance', [\App\Http\Controllers\AttendanceController::class, 'store'])->name('events.attendance.store');
        Route::put('/attendance/{attendance}', [\App\Http\Controllers\AttendanceController::class, 'update'])->name('attendance.update');
    });
    
    // Admin-only events management
    Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
        Route::get('/events', [AlumniController::class, 'adminIndex'])->name('admin.events.index');
        Route::post('/events', [AlumniController::class, 'store'])->name('admin.events.store');
        Route::get('/events/{post}', [AlumniController::class, 'adminShow'])->name('admin.events.show');
        Route::delete('/events/{post}/registrants/{registration}', [EventRegistrationController::class, 'destroy'])->name('admin.events.registrants.destroy');
        Route::get('/registrations', [EventRegistrationController::class, 'index'])->name('admin.registrations.index');
        
        // Attendance management routes
        Route::get('/attendance', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('admin.attendance.index');
        Route::post('/attendance/{post}', [\App\Http\Controllers\AttendanceController::class, 'store'])->name('admin.attendance.store');
        Route::put('/attendance/{attendance}', [\App\Http\Controllers\AttendanceController::class, 'update'])->name('admin.attendance.update');
        Route::post('/attendance/{registration}/mark-attended', [\App\Http\Controllers\AttendanceController::class, 'markAttended'])->name('admin.attendance.mark-attended');
        Route::delete('/attendance/{registration}', [\App\Http\Controllers\AttendanceController::class, 'destroy'])->name('admin.attendance.destroy');
        Route::get('/attendance/{post}/stats', [\App\Http\Controllers\AttendanceController::class, 'getEventStats'])->name('admin.attendance.stats');
        Route::get('/attendance/{post}/export', [\App\Http\Controllers\AttendanceController::class, 'export'])->name('admin.attendance.export');
    });
    
    // User interactions with events (likes, comments, reviews)
    Route::middleware(['auth'])->group(function () {
        Route::post('/posts/{post}/like', [AlumniController::class, 'likePost'])->name('posts.like');
        Route::post('/comments/{comment}/like', [AlumniController::class, 'like'])->name('comments.like');
        Route::post('/alumni_posts', [AlumniController::class, 'store'])->name('alumni_posts.store');
        
        // Review routes
        Route::post('/events/{post}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
        Route::get('/events/{post}/reviews', [ReviewController::class, 'getEventReviews'])->name('reviews.index');
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
    })->name('admin.news');

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
        
        // Review management routes
        Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('admin.reviews.approve');
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
        $users = \App\Models\User::where('role', 'user')
            ->with(['eventRegistrations.alumniPost'])
            ->get();
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
    Route::get('/assistant/document-requests', fn() => view('assistant.document-requests'))->name('assistant.document-requests');
    Route::get('/assistant/account-management', fn() => view('assistant.account-management'))->name('assistant.account-management');
    Route::get('/assistant/helpdesk', fn() => view('assistant.helpdesk'))->name('assistant.helpdesk');
    
    // Assistant access to attendance management
    Route::middleware(['auth', 'assistant'])->prefix('assistant')->group(function () {
        Route::get('/attendance', [\App\Http\Controllers\AttendanceController::class, 'index'])->name('assistant.attendance.index');
        Route::post('/attendance/{post}', [\App\Http\Controllers\AttendanceController::class, 'store'])->name('assistant.attendance.store');
        Route::put('/attendance/{attendance}', [\App\Http\Controllers\AttendanceController::class, 'update'])->name('assistant.attendance.update');
        Route::post('/attendance/{registration}/mark-attended', [\App\Http\Controllers\AttendanceController::class, 'markAttended'])->name('assistant.attendance.mark-attended');
        Route::delete('/attendance/{registration}', [\App\Http\Controllers\AttendanceController::class, 'destroy'])->name('assistant.attendance.destroy');
        Route::get('/attendance/{post}/stats', [\App\Http\Controllers\AttendanceController::class, 'getEventStats'])->name('assistant.attendance.stats');
        Route::get('/attendance/{post}/export', [\App\Http\Controllers\AttendanceController::class, 'export'])->name('assistant.attendance.export');
    });
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
    // Documents: user-facing
    Route::get('/documents', [DocumentRequestController::class, 'index'])->name('documents.index');
    Route::post('/documents', [DocumentRequestController::class, 'store'])->name('documents.store');
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

// Admin Documents management
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/document-requests', [DocumentRequestController::class, 'adminIndex'])->name('admin.document-requests.index');
    Route::patch('/document-requests/{documentRequest}', [DocumentRequestController::class, 'updateStatus'])->name('admin.document-requests.update');
});

require __DIR__ . '/auth.php';


