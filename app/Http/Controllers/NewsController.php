<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\AlumniPost;
use App\Models\Donation;
use App\Models\User;
use App\Helpers\ImageHelper;
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
            // Log::info('News Store Request:', [
            //     'has_image' => $request->hasFile('image'),
            //     'filesystem_default' => config('filesystems.default'),
            //     'title' => $request->title,
            // ]);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
                try {
                    $imagePath = $request->file('image')->store('news_images', 'public');
                        // Log::info('Image stored successfully:', [
                        //     'path' => $imagePath,
                        // ]);
                } catch (\Exception $e) {
                    // Log::error('Error storing image: ' . $e->getMessage());
                    // Log::error('Stack trace: ' . $e->getTraceAsString());
                    return redirect()->back()->with('error', 'Failed to upload image: ' . $e->getMessage())->withInput();
                }
        }

            $news = News::create([
            'title' => $request->title,
            'content' => $request->content,
            'image_path' => $imagePath,
        ]);

            // Log::info('News created successfully:', [
            //     'id' => $news->id,
            //     'image_path' => $news->image_path,
            // ]);

        return redirect()->back()->with('success', 'News posted successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log::error('Validation error:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Log::error('Error storing news: ' . $e->getMessage());
            // Log::error('Stack trace: ' . $e->getTraceAsString());
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
            // Log::error('Error in admin news page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading the news page.');
        }
    }

    /**
     * Update a news article
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content,
        ];

        // Handle image upload if new image is provided
        if ($request->hasFile('image')) {
            try {
                // Delete old image if exists
                if ($news->image_path) {
                        Storage::disk('public')->delete($news->image_path);
                }
                
                // Store new image
                $imagePath = $request->file('image')->store('news_images', 'public');
                $data['image_path'] = $imagePath;
                
                // Log::info('News image updated successfully:', [
                //     'news_id' => $news->id,
                //     'new_image_path' => $imagePath,
                // ]);
            } catch (\Exception $e) {
                // Log::error('Error updating news image: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to upload image: ' . $e->getMessage())->withInput();
            }
        }

        $news->update($data);

        // \Log::info('News updated successfully:', [
        //     'id' => $news->id,
        //     'title' => $news->title,
        // ]);

        return redirect()->route('admin.news')->with('success', 'News article updated successfully!');
    }

    /**
     * Delete a news article (soft delete)
     */
    public function destroy(News $news)
    {
        try {
            // Delete image if exists
            if ($news->image_path) {
                Storage::disk('public')->delete($news->image_path);
            }
            
            $news->delete();

            // Log::info('News deleted successfully:', [
            //     'id' => $news->id,
            //     'title' => $news->title,
            // ]);

            return redirect()->route('admin.news')->with('success', 'News article deleted successfully!');
        } catch (\Exception $e) {
            // Log::error('Error deleting news: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the news article.');
        }
    }
}
