<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // Show create user form
    public function create()
    {
        return view('admin.users.create');
    }

    // Store new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'suffix' => 'nullable|string|max:50',
            'year_graduated' => 'nullable|digits:4',
            'program' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'status' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('profile-images', 'public');
        }

        User::create($validated);

        return redirect()->route('admin.users.create')
            ->with('success', 'User account created successfully!');
    }

    // Bulk update multiple users (admin only)
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
            'program' => 'nullable|string|max:255',
            'year_graduated' => 'nullable|digits:4',
            'role' => 'nullable|in:user,admin',
            'status' => 'nullable|string|max:255',
        ]);

        $updates = collect($validated)
            ->only(['program','year_graduated','role','status'])
            ->filter(fn($v) => !(is_null($v) || $v === ''))
            ->all();

        if (empty($updates)) {
            return back()->withErrors(['bulk' => 'No fields provided to update.'])->withInput();
        }

        User::whereIn('id', $validated['user_ids'])->update($updates);

        return back()->with('success', 'Selected users updated successfully.');
    }
}
