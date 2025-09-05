<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;

class DonationController extends Controller
{
    // ...existing code...

    public function updateStatus(Request $request, Donation $donation)
    {
        $request->validate([
            'status' => 'required|in:Pending,Confirmed'
        ]);

        $donation->status = $request->input('status');
        $donation->save();

        // If request came from AJAX return JSON, otherwise redirect back so the blade page reloads and shows the updated status
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Donation status updated successfully',
                'status' => $donation->status,
            ]);
        }

        return redirect()->back()->with('success', 'Donation status updated successfully');
    }

    // ...existing code...
}