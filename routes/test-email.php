<?php

// Test email route - add this to your web.php temporarily
Route::get('/test-email', function () {
    try {
        Mail::raw('Test email from Alumni System', function ($message) {
            $message->to('wasieacuna@gmail.com')
                    ->subject('Test Email');
        });
        
        return 'Email sent successfully!';
    } catch (\Exception $e) {
        return 'Email failed: ' . $e->getMessage();
    }
});
