<?php
// Simple PHP test - no Laravel dependencies
echo "=== Simple PHP Test ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Current working directory: " . getcwd() . "\n";
echo "Server time: " . date('Y-m-d H:i:s') . "\n";

echo "\n=== Extensions Check ===\n";
echo "PDO: " . (extension_loaded('pdo') ? 'YES' : 'NO') . "\n";
echo "PDO MySQL: " . (extension_loaded('pdo_mysql') ? 'YES' : 'NO') . "\n";
echo "MySQLi: " . (extension_loaded('mysqli') ? 'YES' : 'NO') . "\n";
echo "GD: " . (extension_loaded('gd') ? 'YES' : 'NO') . "\n";

echo "\n=== File System Check ===\n";
echo "Composer vendor exists: " . (file_exists('vendor/autoload.php') ? 'YES' : 'NO') . "\n";
echo "Bootstrap cache exists: " . (file_exists('bootstrap/cache/config.php') ? 'YES' : 'NO') . "\n";

echo "\n=== Environment Check ===\n";
if (file_exists('.env')) {
    echo ".env file exists: YES\n";
    $env = parse_ini_file('.env');
    echo "APP_ENV: " . ($env['APP_ENV'] ?? 'NOT SET') . "\n";
    echo "DB_CONNECTION: " . ($env['DB_CONNECTION'] ?? 'NOT SET') . "\n";
} else {
    echo ".env file exists: NO\n";
}

echo "\n=== Test Complete ===\n";
?>
