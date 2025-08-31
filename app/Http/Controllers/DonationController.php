<?php
namespace App\Http\Controllers;

use App\Models\Donation; // ⬅️ important
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'receipt_image' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $path = $request->file('receipt_image')->store('donations', 'public');

        Donation::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'receipt_image' => $path,
        ]);

        return back()->with('success', 'Donation submitted! Awaiting confirmation.');
    }

    public function updateStatus(Request $request, Donation $donation)
    {
        $request->validate([
            'status' => 'required|in:Pending,Confirmed'
        ]);

        $donation->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Donation status updated successfully',
            'status' => $donation->status
        ]);
    }

    public function destroy(Donation $donation)
    {
        $donation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Donation deleted successfully'
        ]);
    }
}
