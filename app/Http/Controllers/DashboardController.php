<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\AlumniPost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'assistant') {
                return redirect()->route('assistant.dashboard');
            }
        }

        // Reset and mark events as completed based on event_date
        AlumniPost::where('is_archived', false)
            ->where('is_completed', true)
            ->whereNotNull('event_date')
            ->where('event_date', '>=', now()->startOfDay())
            ->update(['is_completed' => false]);
        
        AlumniPost::where('is_archived', false)
            ->where('is_completed', false)
            ->whereNotNull('event_date')
            ->where('event_date', '<', now()->startOfDay())
            ->update(['is_completed' => true]);

        // Get the data for the user dashboard
        $featuredNews = News::latest()->first();
        $latestAlumniPosts = AlumniPost::where('is_archived', false)->latest()->take(5)->get();
        
        // Get upcoming events - events where event_date is today or in the future
        $upcomingEvents = AlumniPost::where('is_archived', false)
            ->where('is_completed', false)
            ->whereNotNull('event_date')
            ->where('event_date', '>=', now()->startOfDay())
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get();
        
        // Get completed events for the events section - only show events completed within the last 3 days
        $events = AlumniPost::where('is_archived', false)
            ->where('is_completed', true)
            ->whereNotNull('event_date')
            ->where('event_date', '>=', now()->subDays(3)->startOfDay())
            ->where('event_date', '<', now()->startOfDay())
            ->latest('event_date')
            ->take(5)
            ->get();

        return view('dashboard', compact('featuredNews', 'latestAlumniPosts', 'upcomingEvents', 'events'));
    }
}
