<?php
echo "Testing SMTP connection to smtp.hostinger.com...\n\n";

$host = 'smtp.hostinger.com';
$ports = [25, 465, 587, 2525];
$username = 'info@publicdigit.com';
$password = 'Rathaus#4!';

foreach ($ports as $port) {
    echo "Testing port $port...\n";
    
    $connection = @fsockopen($host, $port, $errno, $errstr, 10);
    
    if ($connection) {
        echo "  ✅ Port $port is OPEN\n";
        fclose($connection);
    } else {
        echo "  ❌ Port $port is CLOSED: $errstr\n";
    }
}

echo "\nTesting SMTP authentication...\n";

require 'vendor/autoload.php';

use Swift_SmtpTransport;
use Swift_Mailer;
use Swift_Message;

foreach ([
    ['port' => 587, 'encryption' => 'tls'],
    ['port' => 465, 'encryption' => 'ssl'],
    ['port' => 587, 'encryption' => null],
] as $config) {
    
    echo "\nTrying {$config['encryption']} on port {$config['port']}...\n";
    
    try {
        $transport = (new Swift_SmtpTransport($host, $config['port'], $config['encryption']))
            ->setUsername($username)
            ->setPassword($password)
            ->setStreamOptions(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]);
        
        $transport->start();
        echo "  ✅ AUTHENTICATION SUCCESSFUL!\n";
        
    } catch (Exception $e) {
        echo "  ❌ Failed: " . $e->getMessage() . "\n";
    }
}
