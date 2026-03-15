<?php
// Minimal Laravel test
require_once __DIR__ . '/vendor/autoload.php';

try {
    echo "=== Laravel Bootstrap Test ===\n";
    
    // Test if we can create the app
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "✅ Bootstrap successful\n";
    
    // Test basic app functionality
    echo "✅ App version: " . $app->version() . "\n";
    echo "✅ Environment: " . $app->environment() . "\n";
    
    // Test if routes are working
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    echo "✅ Kernel created\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "❌ File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "❌ Trace: " . $e->getTraceAsString() . "\n";
} catch (Error $e) {
    echo "❌ Fatal Error: " . $e->getMessage() . "\n";
    echo "❌ File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== Test Complete ===\n";
?>
