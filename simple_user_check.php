<?php

// Simple database connection check
echo "=== Database User Check ===\n\n";

try {
    // Load environment
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    
    // Database connection
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $database = $_ENV['DB_DATABASE'] ?? 'alumni_system';
    $username = $_ENV['DB_USERNAME'] ?? 'root';
    $password = $_ENV['DB_PASSWORD'] ?? '';
    
    echo "Connecting to database: $host/$database as $username\n";
    
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected successfully\n\n";
    
    // Check assistant users
    echo "=== ASSISTANT USERS ===\n";
    $stmt = $pdo->prepare("SELECT id, name, email, role, created_at FROM users WHERE role = 'assistant'");
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
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== End Check ===\n";
