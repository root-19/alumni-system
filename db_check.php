<?php

// Read .env file directly
$envFile = __DIR__ . '/.env';
$envVars = [];

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue; // Skip comments
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $envVars[trim($key)] = trim($value);
        }
    }
} else {
    echo ".env file not found!\n";
    exit(1);
}

echo "=== Database User Check ===\n\n";

try {
    // Database connection
    $host = $envVars['DB_HOST'] ?? 'localhost';
    $database = $envVars['DB_DATABASE'] ?? 'alumni_system';
    $username = $envVars['DB_USERNAME'] ?? 'root';
    $password = $envVars['DB_PASSWORD'] ?? '';
    
    echo "Connecting to database: $host/$database as $username\n";
    
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected successfully\n\n";
    
    // Check assistant users
    echo "=== ASSISTANT USERS ===\n";
    $stmt = $pdo->prepare("SELECT id, name, email, role, email_verified_at, created_at FROM users WHERE role = 'assistant'");
    $stmt->execute();
    $assistants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($assistants)) {
        echo "No assistant users found.\n";
    } else {
        foreach ($assistants as $user) {
            echo "ID: {$user['id']}\n";
            echo "Name: {$user['name']}\n";
            echo "Email: {$user['email']}\n";
            echo "Role: {$user['role']}\n";
            echo "Email Verified: " . ($user['email_verified_at'] ?? 'No') . "\n";
            echo "Created: {$user['created_at']}\n";
            echo "-------------------\n";
        }
    }
    
    echo "\n=== ALL USERS AND ROLES ===\n";
    $stmt = $pdo->prepare("SELECT id, name, email, role FROM users ORDER BY role, id");
    $stmt->execute();
    $allUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($allUsers as $user) {
        echo "ID: {$user['id']} | Name: {$user['name']} | Email: {$user['email']} | Role: {$user['role']}\n";
    }
    
    echo "\n=== CREATE ASSISTANT USER (if needed) ===\n";
    echo "If you need to create an assistant user, you can use this SQL:\n";
    echo "INSERT INTO users (name, email, password, role, email_verified_at, created_at, updated_at) VALUES ";
    echo "('Assistant Name', 'assistant@example.com', '" . password_hash('password123', PASSWORD_DEFAULT) . "', 'assistant', NOW(), NOW(), NOW());\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Please check your database connection settings in .env file:\n";
    echo "DB_HOST: " . ($envVars['DB_HOST'] ?? 'not set') . "\n";
    echo "DB_DATABASE: " . ($envVars['DB_DATABASE'] ?? 'not set') . "\n";
    echo "DB_USERNAME: " . ($envVars['DB_USERNAME'] ?? 'not set') . "\n";
    echo "DB_PASSWORD: " . ($envVars['DB_PASSWORD'] ?? 'not set') . "\n";
}

echo "\n=== End Check ===\n";
