<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "Checking roles:\n";
$roles = Role::all();
foreach ($roles as $role) {
    echo "- {$role->name}\n";
}

echo "\nChecking election permissions:\n";
$permissions = Permission::where('name', 'like', '%election%')
    ->orWhere('name', 'like', '%results%')
    ->orWhere('name', 'like', '%publish%')
    ->get();

foreach ($permissions as $perm) {
    echo "- {$perm->name}\n";
}

echo "\nRole 'election-committee' exists: " . (Role::where('name', 'election-committee')->exists() ? 'YES' : 'NO') . "\n";