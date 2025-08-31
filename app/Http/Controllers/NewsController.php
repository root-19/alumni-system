<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\AlumniPost;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public');
        }

        News::create([
            'title' => $request->title,
            'content' => $request->content,
            'image_path' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'News posted successfully!');
    }

    public function index()
    {
        // Check user role and redirect accordingly
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role === 'assistant') {
            return redirect()->route('assistant.dashboard');
        }
        
        // For regular users, show the dashboard with news data
        $featuredNews = News::latest()->first();
        $latestAlumniPosts = AlumniPost::latest()->take(5)->get();
        
        // Get upcoming events from alumni_posts (assuming they have dates)
        $upcomingEvents = AlumniPost::where('created_at', '>=', now())
            ->orderBy('created_at', 'asc')
            ->take(3)
            ->get();
        
        // Get top 5 contributors from donations table
        $topContributors = User::select('users.id', 'users.name')
            ->join('donations', 'users.id', '=', 'donations.user_id')
            ->groupBy('users.id', 'users.name')
            ->selectRaw('SUM(donations.amount) as total_donated')
            ->orderByDesc('total_donated')
            ->take(5)
            ->get();
        
        // For debugging - let's also get all donations to see what's in the table
        $allDonations = \App\Models\Donation::with('user')->get();
        
        return view('dashboard', compact('featuredNews', 'latestAlumniPosts', 'upcomingEvents', 'topContributors', 'allDonations'));
    }
}
