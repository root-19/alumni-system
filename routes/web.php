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

    Route::get('/news', function () {
        $news = \App\Models\News::latest()->get();
        $alumniPosts = \App\Models\AlumniPost::where('is_archived', false)->latest()->get();

        $featuredNews = $news->first();
        $featuredAlumni = $alumniPosts->first();

        return view('news', compact('news', 'alumniPosts', 'featuredNews', 'featuredAlumni'));
    })->name('news');

Route::get('/trainings', [\App\Http\Controllers\TrainingController::class, 'index'])
    ->name('trainings.index');

    Route::get('/trainings/{training}/take', [\App\Http\Controllers\TrainingController::class, 'take'])
        ->name('training.take');
    
    // Quiz Routes (User)
    Route::get('/quizzes', [\App\Http\Controllers\QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/{id}', [\App\Http\Controllers\QuizController::class, 'show'])->name('quizzes.show');
    Route::get('/quizzes/{id}/take', [\App\Http\Controllers\QuizController::class, 'take'])->name('quizzes.take');
    Route::post('/quizzes/{id}/submit', [\App\Http\Controllers\QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quizzes/result/{attemptId}', [\App\Http\Controllers\QuizController::class, 'result'])->name('quizzes.result');
    Route::post('/quizzes/{id}/retake', [\App\Http\Controllers\QuizController::class, 'retake'])->name('quizzes.retake');
    
    // Final Assessment Routes (User)
    Route::get('/final-assessments', [\App\Http\Controllers\FinalAssessmentController::class, 'index'])->name('final-assessments.index');
    Route::get('/final-assessments/{id}', [\App\Http\Controllers\FinalAssessmentController::class, 'show'])->name('final-assessments.show');
    Route::get('/final-assessments/{id}/take', [\App\Http\Controllers\FinalAssessmentController::class, 'take'])->name('final-assessments.take');
    Route::post('/final-assessments/{id}/submit', [\App\Http\Controllers\FinalAssessmentController::class, 'submit'])->name('final-assessments.submit');
    Route::get('/final-assessments/result/{attemptId}', [\App\Http\Controllers\FinalAssessmentController::class, 'result'])->name('final-assessments.result');
    Route::get('/final-assessments/certificate/{attemptId}', [\App\Http\Controllers\FinalAssessmentController::class, 'certificate'])->name('final-assessments.certificate');
    Route::post('/final-assessments/{id}/retake', [\App\Http\Controllers\FinalAssessmentController::class, 'retake'])->name('final-assessments.retake');

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
        $user = auth()->user->id();
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

// Resume Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/resumes/create', [ResumeController::class, 'create'])->name('resumes.create');
    Route::post('/resumes/generate', [ResumeController::class, 'generate'])->name('resumes.generate');
    Route::get('/resumes/{id}/download', [ResumeController::class, 'downloadPdf'])->name('resumes.download');
});

Route::get('/resumes/{resume}', [ResumeController::class, 'show'])->name('resumes.show');
Route::get('/resume-view', [ResumeController::class, 'showResumeViewer'])->name('resume-view');

Route::get('/resume-view/{id}', [ResumeController::class, 'viewResumeInViewer'])->name('resume-view.show');
Route::get('/resumes', [ResumeController::class, 'index'])->name('resumes.index');
Route::post('/resumes', [ResumeController::class, 'store'])->name('resumes.store');
Route::delete('/resumes/{resume}', [ResumeController::class, 'destroy'])->name('resumes.destroy');

Route::get('/resumes/view/{id}', [ResumeController::class, 'viewResume'])->name('resumes.view');

// Route::get('/admin/events/{post}', [AlumniController::class, 'show'])->name('admin.events.show');

Route::get('events/{post}', [AlumniController::class, 'show'])
    ->name('events.show');

Route::get('/donations', function () {
    $news = \App\Models\News::latest()->get();
    $alumniPosts = \App\Models\AlumniPost::where('is_archived', false)->latest()->get();

    $featuredNews = $news->first();
    $featuredAlumni = $alumniPosts->first();

    return view('donations', compact('news', 'alumniPosts', 'featuredNews', 'featuredAlumni'));
})->name('donations');
Route::post('/donations', [DonationController::class, 'store'])->name('donations.store');

Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

/*
|--------------------------------------------------------------------------
| Admin Dashboard & Pages
|--------------------------------------------------------------------------
*/


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('trainings', [\App\Http\Controllers\Admin\TrainingController::class, 'index'])->name('trainings.index');
    Route::get('trainings/create', [\App\Http\Controllers\Admin\TrainingController::class, 'create'])->name('trainings.create');
    Route::post('trainings', [\App\Http\Controllers\Admin\TrainingController::class, 'store'])->name('trainings.store');
    Route::get('trainings/{id}', [\App\Http\Controllers\Admin\TrainingController::class, 'show'])->name('trainings.show');
    Route::delete('trainings/{id}', function ($id) {
        $training = \App\Models\Training::findOrFail($id);
        
        // Delete associated files from storage
        foreach ($training->files as $file) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($file->path);
        }
        
        // Delete certificate if exists
        if ($training->certificate_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($training->certificate_path);
        }
        
        // Delete the training
        $training->delete();
        
        return redirect()->back()->with('success', 'Training deleted successfully!');
    })->name('trainings.destroy');
    
    // Quiz Management Routes
    Route::get('quizzes', [\App\Http\Controllers\Admin\QuizController::class, 'index'])->name('quizzes.index');
    Route::get('quizzes/create', [\App\Http\Controllers\Admin\QuizController::class, 'create'])->name('quizzes.create');
    Route::post('quizzes', [\App\Http\Controllers\Admin\QuizController::class, 'store'])->name('quizzes.store');
    Route::get('quizzes/{id}/participants', [\App\Http\Controllers\Admin\QuizController::class, 'getParticipants'])->name('quizzes.participants');
    Route::get('quizzes/{id}', [\App\Http\Controllers\Admin\QuizController::class, 'show'])->name('quizzes.show');
    Route::get('quizzes/{id}/edit', [\App\Http\Controllers\Admin\QuizController::class, 'edit'])->name('quizzes.edit');
    Route::put('quizzes/{id}', [\App\Http\Controllers\Admin\QuizController::class, 'update'])->name('quizzes.update');
    Route::delete('quizzes/{id}', [\App\Http\Controllers\Admin\QuizController::class, 'destroy'])->name('quizzes.destroy');
    Route::get('quizzes/{id}/add-question', [\App\Http\Controllers\Admin\QuizController::class, 'addQuestion'])->name('quizzes.add-question');
    Route::post('quizzes/{id}/questions', [\App\Http\Controllers\Admin\QuizController::class, 'storeQuestion'])->name('quizzes.store-question');
    Route::get('quizzes/{quizId}/questions/{questionId}/edit', [\App\Http\Controllers\Admin\QuizController::class, 'editQuestion'])->name('quizzes.edit-question');
    Route::put('quizzes/{quizId}/questions/{questionId}', [\App\Http\Controllers\Admin\QuizController::class, 'updateQuestion'])->name('quizzes.update-question');
    Route::delete('quizzes/{quizId}/questions/{questionId}', [\App\Http\Controllers\Admin\QuizController::class, 'deleteQuestion'])->name('quizzes.delete-question');
    
    // Final Assessment Management Routes
    Route::get('final-assessments', [\App\Http\Controllers\Admin\FinalAssessmentController::class, 'index'])->name('final-assessments.index');
    Route::get('final-assessments/create', [\App\Http\Controllers\Admin\FinalAssessmentController::class, 'create'])->name('final-assessments.create');
    Route::post('final-assessments', [\App\Http\Controllers\Admin\FinalAssessmentController::class, 'store'])->name('final-assessments.store');
    Route::get('final-assessments/{id}', [\App\Http\Controllers\Admin\FinalAssessmentController::class, 'show'])->name('final-assessments.show');
    Route::get('final-assessments/{id}/edit', [\App\Http\Controllers\Admin\FinalAssessmentController::class, 'edit'])->name('final-assessments.edit');
    Route::put('final-assessments/{id}', [\App\Http\Controllers\Admin\FinalAssessmentController::class, 'update'])->name('final-assessments.update');
    Route::delete('final-assessments/{id}', [\App\Http\Controllers\Admin\FinalAssessmentController::class, 'destroy'])->name('final-assessments.destroy');
    Route::get('final-assessments/{id}/add-question', [\App\Http\Controllers\Admin\FinalAssessmentController::class, 'addQuestion'])->name('final-assessments.add-question');
    Route::post('final-assessments/{id}/questions', [\App\Http\Controllers\Admin\FinalAssessmentController::class, 'storeQuestion'])->name('final-assessments.store-question');
    Route::get('final-assessments/{assessmentId}/questions/{questionId}/edit', [\App\Http\Controllers\Admin\FinalAssessmentController::class, 'editQuestion'])->name('final-assessments.edit-question');
    Route::put('final-assessments/{assessmentId}/questions/{questionId}', [\App\Http\Controllers\Admin\FinalAssessmentController::class, 'updateQuestion'])->name('final-assessments.update-question');
    Route::delete('final-assessments/{assessmentId}/questions/{questionId}', [\App\Http\Controllers\Admin\FinalAssessmentController::class, 'deleteQuestion'])->name('final-assessments.delete-question');
    
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
    Route::post('/alumni_posts', [AlumniController::class, 'store'])->name('alumni_posts.store');

    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
    $userCount = User::where('role', 'user')->count();
    $donations = \App\Models\Donation::with('user')->latest()->take(15)->get();
    $totalDonationAmount = \App\Models\Donation::sum('amount');
    $messageCount = \App\Models\Message::count();
    
    // Get events list for dropdown
    $eventsList = \App\Models\AlumniPost::where('is_archived', false)->latest()->take(20)->get();
    
    // Get all reviews with event and user data - only show reviews for events that:
    // 1. Have at least one user marked as "attended" (status='attended' in EventRegistration)
    // 2. OR the event is completed (is_completed = true)
    $reviews = \App\Models\Review::with(['alumniPost', 'user'])
        ->whereHas('alumniPost', function($query) {
            $query->where(function($q) {
                // Event has attendees (status = 'attended')
                $q->whereHas('eventRegistrations', function($regQuery) {
                    $regQuery->where('status', 'attended');
                })
                // OR event is completed
                ->orWhere('is_completed', true);
            });
        })
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
        
        // Count reviews for this month - only for events with attendees or completed
        $reviewsCount = \App\Models\Review::whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->whereHas('alumniPost', function($query) {
                $query->where(function($q) {
                    $q->whereHas('eventRegistrations', function($regQuery) {
                        $regQuery->where('status', 'attended');
                    })
                    ->orWhere('is_completed', true);
                });
            })
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
            
            // Count reviews for this specific event this month - only if event has attendees or is completed
            $hasAttendees = \App\Models\EventRegistration::where('alumni_post_id', $event->id)
                ->where('status', 'attended')
                ->exists();
            
            $reviewsCount = 0;
            if ($hasAttendees || $event->is_completed) {
                $reviewsCount = \App\Models\Review::where('alumni_post_id', $event->id)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            }
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
    
    // Training Analytics
    $trainings = \App\Models\Training::with(['quizzes.userAttempts'])->get();
    $trainingAnalytics = [];
    
    foreach ($trainings as $training) {
        $quizIds = $training->quizzes->where('is_active', true)->pluck('id');
        
        // Get all unique users who took quizzes for this training
        $uniqueUsers = \App\Models\UserQuizAttempt::whereIn('quiz_id', $quizIds)
            ->distinct('user_id')
            ->pluck('user_id');
        
        $totalTakers = $uniqueUsers->count();
        
        // Count how many passed (passed at least one quiz)
        $passedUsers = 0;
        foreach ($uniqueUsers as $userId) {
            $userAttempts = \App\Models\UserQuizAttempt::whereIn('quiz_id', $quizIds)
                ->where('user_id', $userId)
                ->get();
            
            // Check if user passed all quizzes
            $allQuizzes = $training->quizzes->where('is_active', true);
            $passedAll = true;
            foreach ($allQuizzes as $quiz) {
                $userQuizAttempt = $userAttempts->where('quiz_id', $quiz->id)->where('passed', true)->first();
                if (!$userQuizAttempt) {
                    $passedAll = false;
                    break;
                }
            }
            
            if ($passedAll && $allQuizzes->count() > 0) {
                $passedUsers++;
            }
        }
        
        $failedUsers = $totalTakers - $passedUsers;
        
        $trainingAnalytics[] = [
            'training_id' => $training->id,
            'training_title' => $training->title,
            'total_takers' => $totalTakers,
            'passed' => $passedUsers,
            'failed' => $failedUsers,
            'total_quizzes' => $training->quizzes->where('is_active', true)->count(),
        ];
    }
    
    return view('admin.dashboard', compact('userCount','donations','totalDonationAmount','messageCount','eventsList','reviews','chartData','eventsData','topContributors','trainingAnalytics'));
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
        Route::get('/events/{post}/edit', [AlumniController::class, 'edit'])->name('admin.events.edit');
        Route::put('/events/{post}', [AlumniController::class, 'update'])->name('admin.events.update');
        Route::delete('/events/{post}', [AlumniController::class, 'destroy'])->name('admin.events.destroy');
        Route::delete('/events/{post}/registrants/{registration}', [EventRegistrationController::class, 'destroy'])->name('admin.events.registrants.destroy');
        Route::get('/registrations', [EventRegistrationController::class, 'index'])->name('admin.registrations.index');
        
        // Event Logs (Archived Events)
        Route::get('/event-logs', [\App\Http\Controllers\EventLogsController::class, 'index'])->name('admin.event-logs.index');
        Route::post('/events/{post}/archive', [\App\Http\Controllers\EventLogsController::class, 'archive'])->name('admin.events.archive');
        Route::post('/events/{post}/unarchive', [\App\Http\Controllers\EventLogsController::class, 'unarchive'])->name('admin.events.unarchive');
        Route::delete('/event-logs/{post}', [\App\Http\Controllers\EventLogsController::class, 'destroy'])->name('admin.event-logs.destroy');
        
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
        $alumniPosts = \App\Models\AlumniPost::where('is_archived', false)->latest()->get();

        $featuredNews = $news->first();
        $featuredAlumni = $alumniPosts->first();

        return view('admin.news', compact('news', 'alumniPosts', 'featuredNews', 'featuredAlumni'));
    })->name('admin.news');

    Route::get('/admin/news-display', function () {
        $news = \App\Models\News::latest()->get();
        return view('admin.newsdisplay', compact('news'));
    })->name('admin.newsdisplay');

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

/*
|--------------------------------------------------------------------------
| Storage Symlink Routes (Remove after use!)
|--------------------------------------------------------------------------
| These routes help diagnose and fix storage symlink issues.
| Access: /create-storage-link or /check-storage-link
| After fixing, REMOVE THESE ROUTES for security.
*/

// Check storage symlink status
Route::get('/check-storage-link', function () {
    $publicStoragePath = public_path('storage');
    $targetPath = storage_path('app/public');
    $testImagePath = 'news_images/9sror8oPVh4cK0tSoiItIWHMLe3t5S5bNl81eom2.png';
    
    $linkTarget = is_link($publicStoragePath) ? readlink($publicStoragePath) : null;
    $isRelative = $linkTarget && (strpos($linkTarget, '../') === 0 || strpos($linkTarget, '..\\') === 0);
    
    $status = [
        'symlink_exists' => file_exists($publicStoragePath),
        'is_symlink' => is_link($publicStoragePath),
        'is_directory' => is_dir($publicStoragePath),
        'symlink_target' => $linkTarget,
        'is_relative_path' => $isRelative,
        'is_absolute_path' => $linkTarget && !$isRelative,
        'target_exists' => is_dir($targetPath),
        'test_image_exists_in_storage' => file_exists($targetPath . '/' . $testImagePath),
        'test_image_exists_via_symlink' => file_exists($publicStoragePath . '/' . $testImagePath),
        'test_image_url' => asset('storage/' . $testImagePath),
        'public_storage_path' => $publicStoragePath,
        'target_path' => $targetPath,
        'issue' => $linkTarget && !$isRelative ? 'Symlink uses absolute path - this causes 404 errors! Use /create-storage-link to fix.' : null,
    ];
    
    return response()->json($status, 200, [], JSON_PRETTY_PRINT);
});

// Create storage symlink
Route::get('/create-storage-link', function (Request $request) {
    // Simple route - no token required for now (remove after use!)
    // TODO: Add authentication or remove this route after symlink is created
    
    $publicStoragePath = public_path('storage');
    $targetPath = storage_path('app/public');
    $messages = [];
    $errors = [];
    
    try {
        // Step 1: Check if public/storage exists
        if (file_exists($publicStoragePath)) {
            if (is_link($publicStoragePath)) {
                // It's a symlink - check if it's valid and uses relative path
                $linkTarget = readlink($publicStoragePath);
                $isRelative = (strpos($linkTarget, '../') === 0 || strpos($linkTarget, '..\\') === 0);
                
                if ($isRelative && ($linkTarget === '../storage/app/public' || realpath($publicStoragePath) === realpath($targetPath))) {
                    $messages[] = "✓ Symlink already exists and is correct: public/storage -> $linkTarget";
                    return response()->json([
                        'success' => true,
                        'message' => 'Storage symlink already exists and is correct.',
                        'details' => $messages,
                        'link_target' => $linkTarget,
                        'warning' => 'Please remove this route after verifying the symlink works!'
                    ]);
                } else {
                    // Absolute path or incorrect symlink - MUST REMOVE AND RECREATE
                    $messages[] = "⚠ Found existing symlink with ABSOLUTE path: $linkTarget";
                    $messages[] = "This causes 404 errors! Removing and recreating with relative path...";
                    unlink($publicStoragePath);
                }
            } elseif (is_dir($publicStoragePath)) {
                // It's a directory, not a symlink
                $messages[] = "⚠ public/storage exists as a directory (should be symlink)";
                $messages[] = "This is why images don't display! Removing directory...";
                if (!rmdir($publicStoragePath)) {
                    // Try to remove recursively if not empty
                    $files = new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($publicStoragePath, \RecursiveDirectoryIterator::SKIP_DOTS),
                        \RecursiveIteratorIterator::CHILD_FIRST
                    );
                    foreach ($files as $file) {
                        $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
                    }
                    rmdir($publicStoragePath);
                }
                if (!isset($errors) || !in_array("❌ Failed to remove directory. May need manual intervention or different permissions.", $errors ?? [])) {
                    $messages[] = "✓ Directory removed successfully";
                }
            } else {
                // It's a file
                $messages[] = "⚠ public/storage exists as a file (should be symlink)";
                $messages[] = "Removing file...";
                unlink($publicStoragePath);
                $messages[] = "✓ File removed";
            }
        }
        
        // Step 2: Ensure target directory exists
        if (!is_dir($targetPath)) {
            $messages[] = "Creating target directory: $targetPath";
            mkdir($targetPath, 0755, true);
            $messages[] = "✓ Target directory created";
        } else {
            $messages[] = "✓ Target directory exists: $targetPath";
        }
        
        // Step 3: Create the symlink (use RELATIVE path for web server compatibility)
        $messages[] = "Creating symlink: public/storage -> storage/app/public";
        
        // Calculate relative path from public/ to storage/app/public
        $relativeTarget = '../storage/app/public';
        
        // Try using artisan command first (creates relative symlink)
        $artisanPath = base_path('artisan');
        if (file_exists($artisanPath)) {
            // Change to public directory to create relative symlink
            $originalDir = getcwd();
            chdir(public_path());
            
            $output = [];
            $returnVar = 0;
            exec("php ../artisan storage:link 2>&1", $output, $returnVar);
            
            chdir($originalDir);
            
            if ($returnVar === 0 && is_link($publicStoragePath)) {
                $linkTarget = readlink($publicStoragePath);
                $messages[] = "✓ Symlink created successfully using artisan";
                $messages[] = "Link: " . $linkTarget;
                
                // Check if it's relative (should be ../storage/app/public)
                if (strpos($linkTarget, '../') === 0 || strpos($linkTarget, '..\\') === 0) {
                    $messages[] = "✓ Using relative path (correct for web servers)";
                } else {
                    $messages[] = "⚠ Warning: Using absolute path, recreating with relative path...";
                    // Remove and recreate with relative path
                    unlink($publicStoragePath);
                    chdir(public_path());
                    if (symlink($relativeTarget, 'storage')) {
                        $messages[] = "✓ Recreated with relative path";
                    }
                    chdir($originalDir);
                }
            } else {
                // Fallback to manual symlink creation with relative path
                $messages[] = "Artisan command failed, trying manual symlink creation with relative path...";
                chdir(public_path());
                if (symlink($relativeTarget, 'storage')) {
                    $messages[] = "✓ Symlink created successfully manually with relative path";
                } else {
                    $errors[] = "Failed to create symlink. Check file permissions.";
                }
                chdir($originalDir);
            }
        } else {
            // Manual symlink creation with relative path
            chdir(public_path());
            if (symlink($relativeTarget, 'storage')) {
                $messages[] = "✓ Symlink created successfully with relative path";
            } else {
                $errors[] = "Failed to create symlink. Check file permissions.";
            }
            chdir($originalDir);
        }
        
        // Step 4: Verify the symlink
        if (is_link($publicStoragePath)) {
            $linkTarget = readlink($publicStoragePath);
            $messages[] = "✓ Verification: Symlink exists";
            $messages[] = "  Link: public/storage -> $linkTarget";
            
            // Test if symlink resolves correctly
            if (file_exists($publicStoragePath)) {
                $messages[] = "✓ Symlink resolves correctly - images should now be accessible!";
            } else {
                $errors[] = "⚠ Symlink exists but does not resolve correctly. Check permissions.";
            }
        } else {
            $errors[] = "❌ Symlink was not created successfully.";
        }
        
        if (count($errors) > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Some errors occurred while creating the symlink.',
                'details' => $messages,
                'errors' => $errors,
                'warning' => 'Please remove this route after use!'
            ], 500);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Storage symlink created successfully!',
            'details' => $messages,
            'link_path' => $publicStoragePath,
            'target_path' => $targetPath,
            'warning' => '⚠ IMPORTANT: Remove this route from routes/web.php after verifying it works!'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error creating symlink: ' . $e->getMessage(),
            'details' => $messages,
            'errors' => array_merge($errors, [$e->getMessage()]),
            'trace' => config('app.debug') ? $e->getTraceAsString() : null
        ], 500);
    }
})->name('create.storage.link');

// Fallback route to serve images directly if symlink doesn't work
// This route serves files from storage/app/public when symlink fails
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    
    // Security: Prevent directory traversal
    $realPath = realpath($filePath);
    $storagePath = realpath(storage_path('app/public'));
    
    if (!$realPath || strpos($realPath, $storagePath) !== 0) {
        abort(404);
    }
    
    if (!file_exists($realPath)) {
        abort(404);
    }
    
    $mimeType = mime_content_type($realPath);
    if (!$mimeType) {
        $mimeType = 'application/octet-stream';
    }
    
    return response()->file($realPath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*')->name('storage.fallback');

require __DIR__ . '/auth.php';


