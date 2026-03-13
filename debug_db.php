<?php
// Debug database connection and extensions
echo "=== PHP Extensions Debug ===\n";
echo "PHP Version: " . phpversion() . "\n";

// Check PDO extensions
echo "\n--- PDO Extensions ---\n";
echo "PDO: " . (extension_loaded('pdo') ? '✅ LOADED' : '❌ NOT LOADED') . "\n";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? '✅ LOADED' : '❌ NOT LOADED') . "\n";
echo "MySQLi: " . (extension_loaded('mysqli') ? '✅ LOADED' : '❌ NOT LOADED') . "\n";

// List all PDO drivers
if (extension_loaded('pdo')) {
    echo "\n--- Available PDO Drivers ---\n";
    $drivers = PDO::getAvailableDrivers();
    if (empty($drivers)) {
        echo "❌ No PDO drivers available\n";
    } else {
        foreach ($drivers as $driver) {
            echo "✅ $driver\n";
        }
    }
}

// Test database connection
echo "\n=== Database Connection Test ===\n";
try {
    // Try to connect using Laravel's database config
    require_once __DIR__ . '/vendor/autoload.php';
    
    // Load environment
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    
    $host = $_ENV['DB_HOST'] ?? 'localhost';
    $port = $_ENV['DB_PORT'] ?? '3306';
    $database = $_ENV['DB_DATABASE'] ?? 'laravel';
    $username = $_ENV['DB_USERNAME'] ?? 'root';
    $password = $_ENV['DB_PASSWORD'] ?? '';
    
    echo "Connecting to: $host:$port/$database as $username\n";
    
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 10,
    ]);
    
    echo "✅ Database connection successful!\n";
    
    // Test query
    $stmt = $pdo->query("SELECT VERSION() as version");
    $version = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "MySQL Version: " . $version['version'] . "\n";
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "Error Type: " . get_class($e) . "\n";
}

echo "\n=== Environment Variables ===\n";
$envVars = ['DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
foreach ($envVars as $var) {
    $value = $_ENV[$var] ?? 'NOT SET';
    echo "$var: " . (strpos($var, 'PASSWORD') !== false ? '[HIDDEN]' : $value) . "\n";
}

echo "\n=== Complete ===\n";
?>
