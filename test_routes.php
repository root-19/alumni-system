<?php

echo "=== Route Test ===\n\n";

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Initialize the application
$app->boot();

echo "Checking Assistant Document Request Routes:\n\n";

try {
    $routes = app('router')->getRoutes();
    
    $assistantRoutes = [];
    $adminRoutes = [];
    
    foreach ($routes as $route) {
        $name = $route->getName();
        if ($name && str_contains($name, 'document-request')) {
            if (str_contains($name, 'assistant')) {
                $assistantRoutes[] = [
                    'name' => $name,
                    'uri' => $route->uri(),
                    'methods' => implode(', ', $route->methods()),
                    'middleware' => implode(', ', $route->middleware())
                ];
            } elseif (str_contains($name, 'admin')) {
                $adminRoutes[] = [
                    'name' => $name,
                    'uri' => $route->uri(),
                    'methods' => implode(', ', $route->methods()),
                    'middleware' => implode(', ', $route->middleware())
                ];
            }
        }
    }
    
    echo "=== ASSISTANT ROUTES ===\n";
    foreach ($assistantRoutes as $route) {
        echo "Route: {$route['name']}\n";
        echo "URL: {$route['uri']}\n";
        echo "Methods: {$route['methods']}\n";
        echo "Middleware: {$route['middleware']}\n";
        echo "---\n";
    }
    
    echo "\n=== ADMIN ROUTES ===\n";
    foreach ($adminRoutes as $route) {
        echo "Route: {$route['name']}\n";
        echo "URL: {$route['uri']}\n";
        echo "Methods: {$route['methods']}\n";
        echo "Middleware: {$route['middleware']}\n";
        echo "---\n";
    }
    
    echo "\n=== URL GENERATION TEST ===\n";
    
    // Test URL generation
    try {
        $testId = 4;
        echo "Assistant update URL: " . route('assistant.document-requests.update', $testId) . "\n";
        echo "Assistant show URL: " . route('assistant.document-requests.show', $testId) . "\n";
        echo "Assistant index URL: " . route('assistant.document-requests.index') . "\n";
    } catch (Exception $e) {
        echo "URL generation error: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== End Test ===\n";
