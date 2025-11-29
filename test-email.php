<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

try {
    echo "Testing email configuration...\n";
    echo "SMTP Host: " . config('mail.mailers.smtp.host') . "\n";
    echo "SMTP Port: " . config('mail.mailers.smtp.port') . "\n";
    echo "SMTP Username: " . config('mail.mailers.smtp.username') . "\n";
    echo "From Address: " . config('mail.from.address') . "\n\n";

    Mail::raw('This is a test email from the voting system', function ($message) {
        $message->to('roshyara@gmail.com')
                ->subject('Test Email - Voting System');
    });

    echo "✅ Email sent successfully!\n";
} catch (\Exception $e) {
    echo "❌ Error sending email:\n";
    echo $e->getMessage() . "\n";
    echo "\nFull error:\n";
    echo $e->getTraceAsString() . "\n";
}
