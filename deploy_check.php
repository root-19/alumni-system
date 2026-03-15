<?php
// Deployment environment check script
echo "=== Deployment Environment Check ===\n";

// Check if .env is being loaded
echo "\n--- Environment Variables Check ---\n";
$required_vars = [
    'DB_CONNECTION',
    'DB_HOST', 
    'DB_PORT',
    'DB_DATABASE',
    'DB_USERNAME',
    'DB_PASSWORD',
    'APP_ENV',
    'APP_DEBUG'
];

foreach ($required_vars as $var) {
    $value = getenv($var) ?: $_ENV[$var] ?? 'NOT SET';
    $display_value = (strpos($var, 'PASSWORD') !== false) ? '[HIDDEN]' : $value;
    echo "$var: $display_value\n";
}

// Check Laravel config
echo "\n--- Laravel Config Check ---\n";
try {
    require_once __DIR__ . '/vendor/autoload.php';
    
    // Load Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    $dbConfig = config('database.connections.mysql');
    echo "DB Host from config: " . ($dbConfig['host'] ?? 'NOT SET') . "\n";
    echo "DB Database from config: " . ($dbConfig['database'] ?? 'NOT SET') . "\n";
    echo "DB Username from config: " . ($dbConfig['username'] ?? 'NOT SET') . "\n";
    
} catch (Exception $e) {
    echo "Laravel bootstrap failed: " . $e->getMessage() . "\n";
}

echo "\n=== Complete ===\n";
?>
