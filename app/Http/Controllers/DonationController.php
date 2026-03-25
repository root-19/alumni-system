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

        return back()->with('success', 'Donation status updated successfully!');
    }

    public function resetYearly(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        // Store backup of old data before reset
        $donationsToReset = Donation::whereYear('created_at', $year)->get();
        
        if ($donationsToReset->isEmpty()) {
            return redirect()->back()->with('error', "No donations found for year {$year}.");
        }
        
        $totalAmount = $donationsToReset->sum('amount');
        $count = $donationsToReset->count();
        
        // Create backup data in session for potential restore
        $backupData = $donationsToReset->map(function($donation) {
            return [
                'id' => $donation->id,
                'user_id' => $donation->user_id,
                'user_name' => $donation->user->name,
                'user_email' => $donation->user->email,
                'original_amount' => $donation->amount,
                'receipt_image' => $donation->receipt_image,
                'status' => $donation->status,
                'created_at' => $donation->created_at,
                'reset_at' => now()
            ];
        })->toArray();
        
        // Store backup in session for potential restore functionality
        session(["donation_backup_{$year}" => $backupData]);
        
        // Log the backup operation
        \Log::info("Donation reset backup created for year {$year}", [
            'year' => $year,
            'total_amount_reset' => $totalAmount,
            'count_reset' => $count,
            'backup_data_count' => count($backupData)
        ]);
        
        // Reset donations for the specified year to 0 amount
        Donation::whereYear('created_at', $year)->update(['amount' => 0]);
        
        return redirect()->back()->with('success', "Donations for year {$year} have been reset to zero. {$count} donations totaling ₱" . number_format($totalAmount, 2) . " have been backed up and can be restored if needed.");
    }

    public function restoreYearly(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $backupKey = "donation_backup_{$year}";
        
        if (!session()->has($backupKey)) {
            return redirect()->back()->with('error', "No backup data found for year {$year}.");
        }
        
        $backupData = session($backupKey);
        $restoredCount = 0;
        $totalAmount = 0;
        
        foreach ($backupData as $backup) {
            $donation = Donation::find($backup['id']);
            if ($donation) {
                $donation->update(['amount' => $backup['original_amount']]);
                $restoredCount++;
                $totalAmount += $backup['original_amount'];
            }
        }
        
        // Clear the backup after restoration
        session()->forget($backupKey);
        
        \Log::info("Donation restore completed for year {$year}", [
            'year' => $year,
            'restored_count' => $restoredCount,
            'total_amount_restored' => $totalAmount
        ]);
        
        return redirect()->back()->with('success', "Successfully restored {$restoredCount} donations totaling ₱" . number_format($totalAmount, 2) . " for year {$year}.");
    }

    public function destroy(Donation $donation)
    {
        $donation->delete();

        return redirect()->back()->with('success', 'Donation deleted successfully!');
    }
}
