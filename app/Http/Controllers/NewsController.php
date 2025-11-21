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
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                try {
                    $imagePath = $request->file('image')->store('news_images', 'public');
                } catch (\Exception $e) {
                    \Log::error('Error storing image: ' . $e->getMessage());
                    return redirect()->back()->with('error', 'Failed to upload image: ' . $e->getMessage())->withInput();
                }
            }

            News::create([
                'title' => $request->title,
                'content' => $request->content,
                'image_path' => $imagePath,
            ]);

            return redirect()->back()->with('success', 'News posted successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Error storing news: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'An error occurred while posting news. Please try again.')->withInput();
        }
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

    public function adminIndex()
    {
        try {
            $news = News::latest()->get();
            $alumniPosts = AlumniPost::where('is_archived', false)->latest()->get();

            $featuredNews = $news->first();
            $featuredAlumni = $alumniPosts->first();

            return view('admin.news', compact('news', 'alumniPosts', 'featuredNews', 'featuredAlumni'));
        } catch (\Exception $e) {
            \Log::error('Error in admin news page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the news page.');
        }
    }
}
