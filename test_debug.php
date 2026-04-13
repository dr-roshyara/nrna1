<?php

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Support\Str;

require 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$status = $kernel->bootstrap();

// Clean database
$app->make(Illuminate\Database\DatabaseManager::class)->connection()->getPdo()->exec('DELETE FROM elections');
$app->make(Illuminate\Database\DatabaseManager::class)->connection()->getPdo()->exec('DELETE FROM organisations');
$app->make(Illuminate\Database\DatabaseManager::class)->connection()->getPdo()->exec('DELETE FROM users');
$app->make(Illuminate\Database\DatabaseManager::class)->connection()->getPdo()->exec('DELETE FROM user_organisation_roles');

// Create test data
$org = Organisation::factory()->create();
$admin = User::factory()->create();
$election = Election::factory()->real()->forOrganisation($org)->create();

UserOrganisationRole::create([
    'id' => (string) Str::uuid(),
    'user_id' => $admin->id,
    'organisation_id' => $org->id,
    'role' => 'admin',
]);

echo "Test data created successfully\n";
echo "Organisation: {$org->id}, Admin: {$admin->id}, Election: {$election->id}\n";
echo "Testing route: /elections/{$election->slug}/settings\n";

// Test the route
try {
    $response = $app->make('auth')->setUser($admin)
        ->guard('web')->setUser($admin);
    echo "Admin logged in successfully\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
