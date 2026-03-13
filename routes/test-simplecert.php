<?php

use Illuminate\Support\Facades\Route;
use App\Services\SimpleCertService;

// Test route for SimpleCert integration (remove in production)
Route::get('/test-simplecert', function () {
    $simpleCertService = new SimpleCertService();
    
    // Test API key validation
    $validation = $simpleCertService->validateApiKey();
    
    if ($validation['success']) {
        // Test certificate generation
        $testData = [
            'studentName' => 'Test User',
            'trainingTitle' => 'Test Training',
            'completionDate' => now()->format('F d, Y'),
            'schoolName' => 'Test School',
            'certificateId' => 'TEST-' . time(),
        ];
        
        $result = $simpleCertService->generateCertificate($testData);
        
        return response()->json([
            'api_validation' => $validation,
            'certificate_generation' => $result,
            'status' => 'SimpleCert integration test completed'
        ]);
    } else {
        return response()->json([
            'api_validation' => $validation,
            'status' => 'SimpleCert API key validation failed'
        ]);
    }
})->name('test.simplecert');
