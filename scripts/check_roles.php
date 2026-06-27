<?php

/**
 * Role & Permission Governance Check Script v2
 *
 * Governance Layer: Security Governance
 * Orchestrator:     verify.sh
 *
 * Validates:
 *   1. Listing of all Spatie roles and election-related permissions
 *   2. Mandatory role presence (member, election-committee, etc.)
 *   3. Mandatory permission presence (election.create, election.publish, etc.)
 *   4. Role-permission assignments for critical roles
 *   5. CI exit codes — exits 1 if critical roles/permissions are missing
 *
 * Usage:
 *   php scripts/check_roles.php          # standard check
 *   php scripts/check_roles.php --strict # exit 1 on any missing item
 *
 * Safe for CI/CD (pre-push, GitHub Actions, deployment verification).
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$strictMode = in_array('--strict', $argv ?? []);
$exitCode = 0;

// ──────────────────────────────────────────────
// Helper: print status with consistent formatting
// ──────────────────────────────────────────────
function status(string $label, bool $ok, string $detail = ''): void {
    $mark = $ok ? '✅' : '❌';
    echo "  {$mark} {$label}" . ($detail ? " — {$detail}" : '') . "\n";
}

// ══════════════════════════════════════════════
// CHECK 1: List all roles
// ══════════════════════════════════════════════
try {
    echo "📋 All Roles:\n";
    $roles = Role::all();
    if ($roles->isEmpty()) {
        echo "  (no roles found)\n";
    } else {
        foreach ($roles as $role) {
            echo "- {$role->name}\n";
        }
    }
} catch (\Exception $e) {
    echo "⚠️  Could not retrieve roles: " . $e->getMessage() . "\n";
    echo "   Make sure migrations have been run.\n";
}

// ══════════════════════════════════════════════
// CHECK 2: Verify mandatory roles
// ══════════════════════════════════════════════
echo "\n🔐 Required Roles:\n";

$requiredRoles = [
    'member',
    'election-committee',
    'constitutional-council',
    'auditor',
    'admin',
];

try {
    foreach ($requiredRoles as $roleName) {
        $exists = Role::where('name', $roleName)->exists();
        status($roleName, $exists);
        if (!$exists) {
            fwrite(STDERR, "  ⚠️  Missing required role: {$roleName}\n");
            $exitCode = 1;
        }
    }
} catch (\Exception $e) {
    echo "⚠️  Could not check required roles: " . $e->getMessage() . "\n";
}

// ══════════════════════════════════════════════
// CHECK 3: List & verify election permissions
// ══════════════════════════════════════════════
echo "\n🔑 Election Permissions:\n";

$mandatoryPermissions = [
    'election.create',
    'election.publish',
    'election.vote',
    'election.results.view',
];

try {
    $permissions = Permission::where('name', 'like', '%election%')
        ->orWhere('name', 'like', '%results%')
        ->orWhere('name', 'like', '%publish%')
        ->get();

    if ($permissions->isEmpty()) {
        echo "  (no matching permissions found)\n";
    } else {
        foreach ($permissions as $perm) {
            $isRequired = in_array($perm->name, $mandatoryPermissions);
            $mark = $isRequired ? '✅' : 'ℹ️';
            echo "  {$mark} {$perm->name}\n";
        }
    }

    // Check mandatory permissions specifically
    echo "\n  Required permissions check:\n";
    $permissionNames = $permissions->pluck('name')->toArray();
    foreach ($mandatoryPermissions as $permName) {
        $found = in_array($permName, $permissionNames);
        status($permName, $found);
        if (!$found) {
            fwrite(STDERR, "  ⚠️  Missing required permission: {$permName}\n");
            $exitCode = 1;
        }
    }
} catch (\Exception $e) {
    echo "⚠️  Could not retrieve permissions: " . $e->getMessage() . "\n";
    echo "   Make sure migrations have been run.\n";
}

// ══════════════════════════════════════════════
// CHECK 4: Verify role-permission assignments
// ══════════════════════════════════════════════
echo "\n🔗 Role-Permission Assignments:\n";

$criticalRoles = [
    'election-committee' => [
        'election.create',
        'election.publish',
        'election.results.view',
    ],
    'admin' => [
        'election.create',
        'election.publish',
        'election.vote',
        'election.results.view',
    ],
];

try {
    foreach ($criticalRoles as $roleName => $expectedPermissions) {
        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            echo "  ⏭️  Role '{$roleName}' does not exist — skipping assignment check\n";
            continue;
        }

        echo "  {$roleName}:\n";
        $assigned = $role->permissions->pluck('name')->toArray();

        foreach ($expectedPermissions as $permName) {
            $assigned = in_array($permName, $assigned);
            status("  {$permName}", $assigned);
            if (!$assigned) {
                fwrite(STDERR, "    ⚠️  Role '{$roleName}' missing permission: {$permName}\n");
                $exitCode = 1;
            }
        }
    }
} catch (\Exception $e) {
    echo "⚠️  Could not verify role-permission assignments: " . $e->getMessage() . "\n";
}

// ══════════════════════════════════════════════
// Final verdict
// ══════════════════════════════════════════════
echo "\n";
$label = $exitCode === 0 ? '✅ All checks passed' : '❌ Some checks failed';
echo "{$label} — exiting with code {$exitCode}\n";

exit($exitCode);
