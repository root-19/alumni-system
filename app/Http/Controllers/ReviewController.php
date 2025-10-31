<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\AlumniPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a new review for an event.
     */
    public function store(Request $request, AlumniPost $post)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user has already reviewed this event
        $existingReview = Review::where('user_id', Auth::id())
            ->where('alumni_post_id', $post->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this event.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'alumni_post_id' => $post->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false, // Reviews need approval by default
        ]);

        return back()->with('success', 'Your review has been submitted and is pending approval.');
    }

    /**
     * Update an existing review.
     */
    public function update(Request $request, Review $review)
    {
        // Check if the user owns this review
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'You can only edit your own reviews.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false, // Reset approval status when updated
        ]);

        return back()->with('success', 'Your review has been updated and is pending approval.');
    }

    /**
     * Delete a review.
     */
    public function destroy(Review $review)
    {
        // Check if the user owns this review or is admin
        if ($review->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return back()->with('error', 'You can only delete your own reviews.');
        }

        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }

    /**
     * Approve a review (admin only).
     */
    public function approve(Review $review)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Only admins can approve reviews.');
        }

        $review->update(['is_approved' => true]);

        return back()->with('success', 'Review approved successfully.');
    }

    /**
     * Get reviews for a specific event.
     * Only show reviews if event has attendees or is completed.
     */
    public function getEventReviews(AlumniPost $post)
    {
        // Check if event has attendees or is completed
        $hasAttendees = $post->eventRegistrations()->where('status', 'attended')->exists();
        
        if (!$hasAttendees && !$post->is_completed) {
            return response()->json([
                'reviews' => [],
                'average_rating' => 0,
                'total_reviews' => 0,
            ]);
        }

        $reviews = $post->approvedReviews()
            ->with('user')
            ->latest()
            ->paginate(10);

        $averageRating = $post->approvedReviews()->avg('rating');
        $totalReviews = $post->approvedReviews()->count();

        return response()->json([
            'reviews' => $reviews,
            'average_rating' => round($averageRating, 1),
            'total_reviews' => $totalReviews,
        ]);
    }
}