<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/test-email', function () {
    try {
        // Test 1: Check mail config
        $config = [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'username' => config('mail.mailers.smtp.username'),
            'from_address' => config('mail.from.address'),
        ];

        \Log::info('📧 Test Email Config', $config);

        // Test 2: Send a test email
        Mail::raw('This is a test email from Public Digit', function ($message) {
            $message->to('roshyara@gmail.com')
                ->subject('Public Digit Test Email');
        });

        return response()->json([
            'success' => true,
            'message' => 'Test email sent successfully!',
            'config' => $config
        ]);
    } catch (\Exception $e) {
        \Log::error('❌ Email test failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'type' => get_class($e)
        ], 500);
    }
});
