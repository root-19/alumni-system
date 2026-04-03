<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AssistantAccountController extends Controller
{
    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'last_name'      => 'nullable|string|max:255',
            'email'          => 'required|email|unique:users,email,' . $user->id,
            'program'        => 'nullable|string|max:255',
            'year_graduated' => 'nullable|string|max:10',
            'contact_number' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return response()->json(['success' => true, 'user' => $user->fresh()]);
    }

    public function deactivate(User $user)
    {
        if ($user->status === 'Inactive') {
            $user->update(['status' => 'Active']);
            $label = 'Active';
        } else {
            $user->update(['status' => 'Inactive']);
            $label = 'Inactive';
        }

        return response()->json(['success' => true, 'status' => $label]);
    }
}
