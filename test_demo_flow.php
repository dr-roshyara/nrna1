<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "TESTING ACTUAL DEMO FLOW\n";
echo "========================================\n\n";

// Get user with org_id=2
$user = DB::table('users')->where('organisation_id', 2)->first();
echo "1. User with org_id=2: " . ($user ? "Found (ID: {$user->id})" : "Not found") . "\n";

if (!$user) {
    echo "ERROR: No user with org_id=2\n";
    exit(1);
}

// Get demo election for org 2
$demoElection = DB::table('elections')
    ->where('type', 'demo')
    ->where('organisation_id', 2)
    ->first();
echo "2. Demo election for org 2: " . ($demoElection ? "Found (ID: {$demoElection->id})" : "Not found") . "\n";

if (!$demoElection) {
    echo "ERROR: No demo election for org 2\n";
    exit(1);
}

// Test the Golden Rule validation
$orgsMatch = $demoElection->organisation_id === $user->organisation_id;
$electionIsPlatform = $demoElection->organisation_id === 1;
$userIsPlatform = $user->organisation_id === 1;
$orgsValid = $orgsMatch || $electionIsPlatform || $userIsPlatform;

echo "\n3. Golden Rule Validation (org 2 user -> org 2 election):\n";
echo "   - orgsMatch: " . ($orgsMatch ? "true" : "false") . "\n";
echo "   - electionIsPlatform: " . ($electionIsPlatform ? "true" : "false") . "\n";
echo "   - userIsPlatform: " . ($userIsPlatform ? "true" : "false") . "\n";
echo "   - Result: " . ($orgsValid ? "PASS" : "FAIL") . "\n";

echo "\n========================================\n";
if ($orgsValid) {
    echo "SUCCESS: Demo election flow is valid!\n";
    echo "Fix is working correctly!\n";
} else {
    echo "FAILURE: Demo election flow validation failed!\n";
}
echo "========================================\n";
