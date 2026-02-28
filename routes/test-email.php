<?php
Route::get('/test-email', function () {
    $user = \App\Models\User::first();
    if (!$user) {
        return 'No user found';
    }
    
    try {
        $result = $user->notify(new \App\Notifications\SendFirstVerificationCode($user, 'TEST123'));
        return "Email sent! Result: " . ($result ? 'true' : 'false');
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
