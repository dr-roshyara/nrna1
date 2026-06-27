<?php

/**
 * Bootstrap Test Database Configuration
 *
 * CRITICAL: This file MUST run before Laravel loads the config.
 * It ensures tests ALWAYS use the test database (nrna_test).
 *
 * This prevents accidental data corruption in development/production databases.
 */

// Load composer autoloader FIRST
require __DIR__ . '/../vendor/autoload.php';

// Force testing environment
$_SERVER['APP_ENV'] = 'testing';
$_ENV['APP_ENV'] = 'testing';
putenv('APP_ENV=testing');

// Force test database configuration
// Use exact credentials from .env.testing (literal password, not URL-encoded)
$dbUsername = 'publicdigit_user';
$dbPassword = 'Rudolfvogt%27%';  // Literal password as-is

$_SERVER['DB_CONNECTION'] = 'pgsql';
$_SERVER['DB_HOST'] = '127.0.0.1';
$_SERVER['DB_PORT'] = '5432';
$_SERVER['DB_DATABASE'] = 'nrna_test';
$_SERVER['DB_USERNAME'] = $dbUsername;
$_SERVER['DB_PASSWORD'] = $dbPassword;

// Also set $_ENV
$_ENV['DB_CONNECTION'] = 'pgsql';
$_ENV['DB_HOST'] = '127.0.0.1';
$_ENV['DB_PORT'] = '5432';
$_ENV['DB_DATABASE'] = 'nrna_test';
$_ENV['DB_USERNAME'] = $dbUsername;
$_ENV['DB_PASSWORD'] = $dbPassword;

// And putenv
putenv('DB_CONNECTION=pgsql');
putenv('DB_HOST=127.0.0.1');
putenv('DB_PORT=5432');
putenv('DB_DATABASE=nrna_test');
putenv('DB_USERNAME=' . $dbUsername);
putenv('DB_PASSWORD=' . $dbPassword);
