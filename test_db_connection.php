<?php
// Database Connection Test Script
// Upload this to your server to test database connection

try {
    // Test basic connection
    $host = 'localhost'; // Change to your DB host
    $user = 'root';      // Change to your DB username
    $pass = '';          // Change to your DB password
    $db = 'laravel';     // Change to your DB name
    
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection successful!\n";
    
    // Test query
    $stmt = $conn->query("SELECT COUNT(*) as count FROM information_schema.tables WHERE table_schema = '$db'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "📊 Found {$result['count']} tables in database\n";
    
    // Test sessions table
    $stmt = $conn->query("SHOW TABLES LIKE 'sessions'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Sessions table exists\n";
    } else {
        echo "❌ Sessions table missing - run: php artisan migrate\n";
    }
    
} catch(PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    
    // Provide specific troubleshooting tips
    if (strpos($e->getMessage(), 'Connection timed out') !== false) {
        echo "\n🔧 Troubleshooting tips:\n";
        echo "1. Check if MySQL server is running\n";
        echo "2. Verify database host, username, password\n";
        echo "3. Check firewall settings (port 3306)\n";
        echo "4. Contact your hosting provider\n";
    }
}

echo "\n📋 Current PHP settings:\n";
echo "PHP Version: " . phpversion() . "\n";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? '✅ Enabled' : '❌ Disabled') . "\n";
echo "MySQL Client: " . (extension_loaded('mysqlnd') ? '✅ Enabled' : '❌ Disabled') . "\n";
?>
