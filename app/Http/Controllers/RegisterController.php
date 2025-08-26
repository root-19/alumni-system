<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('admin.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Save user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Send email with credentials
        Mail::raw("Welcome {$user->name}!\n\nYour account has been created.\n\nEmail: {$user->email}\nPassword: {$request->password}", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Your Account Credentials');
        });

        return redirect()->route('admin.register.form')->with('success', 'User registered successfully! Email notification sent.');
    }
}

