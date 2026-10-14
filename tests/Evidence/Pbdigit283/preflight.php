<?php

declare(strict_types=1);

/*
 * PBDIGIT-283 R6 negative evidence (G-RUN-NEG): the pre-run check's loader.
 *
 * Loads ONLY the environment and the configuration (LoadEnvironmentVariables, LoadConfiguration). No service provider
 * is registered, so no database manager exists and no connection is possible. Prints the effective database
 * configuration as JSON with every password removed. Started only by negative-evidence.php, with its environment.
 * (Allowed boot site: tests/Architecture/LaravelBootIsGuardedTest.php.)
 */

use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;

$root = dirname(__DIR__, 3);
require $root.'/vendor/autoload.php';

$app = require $root.'/bootstrap/app.php';
$app->bootstrapWith([LoadEnvironmentVariables::class, LoadConfiguration::class]);

$withoutPasswords = static function (array $config) use (&$withoutPasswords): array {
    unset($config['password']);
    foreach ($config as $key => $value) {
        if (is_array($value)) {
            $config[$key] = $withoutPasswords($value);
        }
    }

    return $config;
};

$connections = $withoutPasswords((array) $app['config']->get('database.connections', []));
$sqlite = (string) ($connections['sqlite']['database'] ?? '');

echo json_encode([
    'cached' => $app->configurationIsCached(),
    'env' => $app->environment(),
    'default' => $app['config']->get('database.default'),
    'bound_db' => $app->bound('db'),
    'sqlite_target_exists' => $sqlite !== '' && (file_exists($sqlite) || file_exists($root.'/'.$sqlite)),
    'connections' => $connections,
], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
