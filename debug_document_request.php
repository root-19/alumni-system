<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();

$response = $kernel->handle($request);

// Debug information
echo "=== Document Request Debug ===\n";

// Check if user is authenticated
if (auth()->check()) {
    echo "User is authenticated\n";
    echo "User ID: " . auth()->id() . "\n";
    echo "User Name: " . auth()->user()->name . "\n";
    echo "User Role: " . auth()->user()->role . "\n";
} else {
    echo "User is NOT authenticated\n";
}

// Check if assistant middleware would pass
if (auth()->check() && auth()->user()->role === 'assistant') {
    echo "Assistant middleware would PASS\n";
} else {
    echo "Assistant middleware would FAIL\n";
}

// Check document request data
try {
    $documentRequest = \App\Models\DocumentRequest::find(4);
    if ($documentRequest) {
        echo "Document Request #4 found\n";
        echo "Status: " . $documentRequest->status . "\n";
        echo "User ID: " . $documentRequest->user_id . "\n";
    } else {
        echo "Document Request #4 NOT found\n";
    }
} catch (Exception $e) {
    echo "Error finding Document Request: " . $e->getMessage() . "\n";
}

// Check routes
$routes = app('router')->getRoutes();
foreach ($routes as $route) {
    if (str_contains($route->getName(), 'document-request')) {
        echo "Route found: " . $route->getName() . " - " . $route->uri() . "\n";
        echo "Methods: " . implode(', ', $route->methods()) . "\n";
        echo "Middleware: " . implode(', ', $route->middleware()) . "\n";
        echo "---\n";
    }
}

echo "=== End Debug ===\n";

$kernel->terminate($request, $response);
