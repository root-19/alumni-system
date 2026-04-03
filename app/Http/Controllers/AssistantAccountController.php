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
        try {
            $validated = $request->validate([
                'name'           => 'required|string|max:255',
                'last_name'      => 'nullable|string|max:255',
                'program'        => 'nullable|string|max:255',
                'year_graduated' => 'nullable|integer|min:1900|max:2100',
                'contact_number' => 'nullable|string|max:20',
            ]);

            $user->update($validated);

            return response()->json(['success' => true, 'user' => $user->fresh()]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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
