<?php

use Illuminate\Support\Facades\Route;

Route::get('/api/health', function () {
    try {
        // Basic health check without database first
        $response = [
            'status' => 'healthy',
            'timestamp' => now()->toISOString(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'extensions' => [
                'pdo' => extension_loaded('pdo'),
                'pdo_mysql' => extension_loaded('pdo_mysql'),
                'mysqli' => extension_loaded('mysqli'),
            ],
        ];

        // Try database connection if extensions are available
        if (extension_loaded('pdo_mysql')) {
            try {
                \Illuminate\Support\Facades\DB::connection()->getPdo();
                $response['database'] = 'connected';
            } catch (\Exception $e) {
                $response['database'] = 'disconnected';
                $response['db_error'] = $e->getMessage();
            }
        } else {
            $response['database'] = 'pdo_mysql_not_loaded';
        }

        return response()->json($response);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'unhealthy',
            'error' => $e->getMessage(),
            'timestamp' => now()->toISOString(),
        ], 500);
    }
});

// Simple root health check - moved to /health
Route::get('/health', function () {
    return response()->json([
        'status' => 'running',
        'message' => 'Alumni Training Portal API',
        'timestamp' => now()->toISOString(),
    ]);
});
