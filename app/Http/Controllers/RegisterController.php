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
            'role'     => 'required|in:user,assistant',
        ]);

        // Save user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Send email with credentials (with error handling)
        try {
            Mail::raw("Welcome {$user->name}!\n\nYour account has been created.\n\nEmail: {$user->email}\nPassword: {$request->password}", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Your Account Credentials');
            });
            
            return redirect()->route('admin.register.form')->with('success', 'User registered successfully! Email notification sent.');
        } catch (\Exception $e) {
            // Log the error but don't fail the registration
            \Log::error('Email sending failed: ' . $e->getMessage());
            \Log::error('Email config: ' . json_encode([
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'username' => config('mail.mailers.smtp.username'),
                'encryption' => config('mail.mailers.smtp.encryption'),
            ]));
            
            return redirect()->route('admin.register.form')->with('success', 'User registered successfully! (Email notification failed - please check SMTP configuration)');
        }
    }
}

