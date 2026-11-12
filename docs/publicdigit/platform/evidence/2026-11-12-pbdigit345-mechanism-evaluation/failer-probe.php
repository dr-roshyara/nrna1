<?php
require $argv[1].'/vendor/autoload.php';
$app = require $argv[1].'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "laravel ", $app->version(), "\n";
echo "default queue connection: ", config('queue.default'), "\n";
echo "queue.failed: ", json_encode(config('queue.failed')), "\n";
echo "failer: ", get_class($app['queue.failer']), "\n";
echo "failer is one singleton for all connections: ", ($app['queue.failer'] === $app['queue.failer'] ? 'yes' : 'no'), "\n";
