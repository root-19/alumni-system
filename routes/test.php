<?php

use Illuminate\Support\Facades\Route;

// Simple test route
Route::get('/test', function () {
    return response()->json([
        'message' => 'Application is running!',
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'php_version' => PHP_VERSION,
        'extensions' => [
            'pdo' => extension_loaded('pdo'),
            'pdo_mysql' => extension_loaded('pdo_mysql'),
            'mysqli' => extension_loaded('mysqli'),
        ],
    ]);
});

// Debug database
Route::get('/debug-db', function () {
    try {
        $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
        return response()->json([
            'database' => 'connected',
            'pdo_drivers' => PDO::getAvailableDrivers(),
        ]);
    } catch (Exception $e) {
        return response()->json([
            'database' => 'failed',
            'error' => $e->getMessage(),
            'pdo_drivers' => extension_loaded('pdo') ? PDO::getAvailableDrivers() : [],
        ]);
    }
});
