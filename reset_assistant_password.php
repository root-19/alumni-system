<?php

// Reset assistant password script
echo "=== Reset Assistant Password ===\n\n";

try {
    // Read .env file
    $envFile = __DIR__ . '/.env';
    $envVars = [];
    
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) continue;
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $envVars[trim($key)] = trim($value);
            }
        }
    }
    
    // Database connection
    $host = $envVars['DB_HOST'] ?? 'localhost';
    $database = $envVars['DB_DATABASE'] ?? 'alumni_system';
    $username = $envVars['DB_USERNAME'] ?? 'root';
    $password = $envVars['DB_PASSWORD'] ?? '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database\n\n";
    
    // New password
    $newPassword = 'assistant123';
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    echo "Setting new password for both assistant users...\n";
    echo "New password: $newPassword\n";
    echo "Hashed password: $hashedPassword\n\n";
    
    // Update first assistant (ID: 10)
    $stmt = $pdo->prepare("UPDATE users SET password = ?, email_verified_at = NOW(), updated_at = NOW() WHERE id = 10 AND role = 'assistant'");
    $stmt->execute([$hashedPassword]);
    echo "✓ Updated password for user ID 10 (rensacuna11@gmail.com)\n";
    
    // Update second assistant (ID: 14)
    $stmt = $pdo->prepare("UPDATE users SET password = ?, email_verified_at = NOW(), updated_at = NOW() WHERE id = 14 AND role = 'assistant'");
    $stmt->execute([$hashedPassword]);
    echo "✓ Updated password for user ID 14 (wasieacuna@gmail.com)\n";
    
    echo "\n=== LOGIN DETAILS ===\n";
    echo "Email: rensacuna11@gmail.com\n";
    echo "Password: $newPassword\n";
    echo "Role: assistant\n\n";
    
    echo "Email: wasieacuna@gmail.com\n";
    echo "Password: $newPassword\n";
    echo "Role: assistant\n\n";
    
    echo "✓ Both assistant accounts now have the same password\n";
    echo "✓ Email verification has been marked as complete\n";
    echo "✓ You can now login with either account\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== Done ===\n";
