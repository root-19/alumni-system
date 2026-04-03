<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Initialize the application
$app->boot();

echo "=== Assistant Role Users Check ===\n\n";

try {
    // Get all users with assistant role
    $assistantUsers = \App\Models\User::where('role', 'assistant')->get();
    
    if ($assistantUsers->count() === 0) {
        echo "No users found with 'assistant' role.\n";
    } else {
        echo "Found {$assistantUsers->count()} assistant user(s):\n";
        echo str_repeat("=", 50) . "\n";
        
        foreach ($assistantUsers as $user) {
            echo "ID: {$user->id}\n";
            echo "Name: {$user->name}\n";
            echo "Email: {$user->email}\n";
            echo "Role: {$user->role}\n";
            echo "Email Verified: " . ($user->email_verified_at ? 'Yes (' . $user->email_verified_at . ')' : 'No') . "\n";
            echo "Created: " . $user->created_at . "\n";
            echo "Updated: " . $user->updated_at . "\n";
            
            // Note: We cannot show the password hash for security reasons
            // But we can show if it's set
            echo "Password Set: " . ($user->password ? 'Yes' : 'No') . "\n";
            echo str_repeat("-", 50) . "\n";
        }
    }
    
    // Also check all users and their roles for reference
    echo "\n=== All Users and Their Roles ===\n";
    $allUsers = \App\Models\User::all();
    
    foreach ($allUsers as $user) {
        echo "ID: {$user->id} | Name: {$user->name} | Email: {$user->email} | Role: {$user->role}\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Please make sure your database connection is working.\n";
}

echo "\n=== End Check ===\n";
