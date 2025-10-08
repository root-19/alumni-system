<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    // Show the profile page
    public function show()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    // Show the edit form
    public function edit()
    {
        $user = Auth::user(); // kukunin ang naka-login user
        return view('profile.edit', compact('user'));
    }

    // Update user info
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'year_graduated' => 'nullable|digits:4',
            'program' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:20',
            'status' => 'nullable|string|max:50',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image_path = $path; // ✅ use correct database column
        }
        
        // Update other fields (except password!)
        $user->update($request->except(['password', 'profile_image']));

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
