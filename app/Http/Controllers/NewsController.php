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
            // DEBUG: Check request data
            \Log::info('News Store Request:', [
                'has_image' => $request->hasFile('image'),
                'filesystem_default' => config('filesystems.default'),
                'title' => $request->title,
            ]);

            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                try {
                    // Always store in local storage (storage/app/public/)
                    \Log::info('Storing image to local storage (public disk)');
                    
                    $imagePath = $request->file('image')->store('news_images', 'public');
                    
                    \Log::info('Image stored successfully:', [
                        'path' => $imagePath,
                        'disk' => 'public',
                        'exists' => Storage::disk('public')->exists($imagePath),
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Error storing image: ' . $e->getMessage());
                    \Log::error('Stack trace: ' . $e->getTraceAsString());
                    return redirect()->back()->with('error', 'Failed to upload image: ' . $e->getMessage())->withInput();
                }
            }

            $news = News::create([
                'title' => $request->title,
                'content' => $request->content,
                'image_path' => $imagePath,
            ]);

            \Log::info('News created successfully:', [
                'id' => $news->id,
                'image_path' => $news->image_path,
            ]);

            return redirect()->back()->with('success', 'News posted successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', $e->errors());
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
