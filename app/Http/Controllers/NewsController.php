<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\AlumniPost;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        // Get news data
        $news = News::latest()->get();
        $alumniPosts = AlumniPost::latest()->get();
        $featuredNews = $news->first();
        $featuredAlumni = $alumniPosts->first();
        
        return view('news', compact('news', 'alumniPosts', 'featuredNews', 'featuredAlumni'));
    }
    }
