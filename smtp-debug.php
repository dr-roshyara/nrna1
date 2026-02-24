<?php
require 'vendor/autoload.php';

use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

$host = 'smtp.hostinger.com';
$username = 'info@publicdigit.com';
$password = 'Rathaus#4!';

$configs = [
    ['port' => 465, 'encryption' => 'ssl'],
    ['port' => 587, 'encryption' => 'tls'],
];

foreach ($configs as $config) {
    echo "\n🔍 Testing {$config['encryption']} on port {$config['port']}...\n";
    
    try {
        // Create transport correctly
        $transport = new EsmtpTransport($host, $config['port'], $config['encryption'] === 'ssl');
        $transport->setUsername($username);
        $transport->setPassword($password);
        
        // Set stream options
        $streamOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ]
        ];
        
        if (method_exists($transport, 'setStreamOptions')) {
            $transport->setStreamOptions($streamOptions);
        }
        
        // Test connection
        $transport->start();
        echo "✅ CONNECTION SUCCESSFUL!\n";
        
        // Try to send email
        $mailer = new Mailer($transport);
        $email = (new Email())
            ->from($username)
            ->to('roshyara@gmail.com')
            ->subject('Test from debug script')
            ->text('This is a test email');
        
        $mailer->send($email);
        echo "✅ EMAIL SENT SUCCESSFULLY!\n";
        break;
        
    } catch (Exception $e) {
        echo "❌ Failed: " . $e->getMessage() . "\n";
    }
}
