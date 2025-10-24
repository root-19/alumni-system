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

        // Get the data for the user dashboard
        $featuredNews = News::latest()->first();
        $latestAlumniPosts = AlumniPost::latest()->take(5)->get();
        $upcomingEvents = AlumniPost::where('created_at', '>=', now())
            ->orderBy('created_at', 'asc')
            ->take(3)
            ->get();
        
        // Get recent events for the events section
        $events = AlumniPost::latest()->take(5)->get();

        return view('dashboard', compact('featuredNews', 'latestAlumniPosts', 'upcomingEvents', 'events'));
    }
}
