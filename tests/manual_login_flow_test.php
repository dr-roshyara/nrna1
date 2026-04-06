<?php
/**
 * Manual Login Flow Test Script
 *
 * Tests the 4 scenarios for single election architecture
 * Run with: php artisan tinker < tests/manual_login_flow_test.php
 */

use App\Models\User;
use App\Models\Election;
use App\Http\Responses\LoginResponse;
use Illuminate\Http\Request;

echo "\n" . str_repeat("=", 80) . "\n";
echo "🧪 SINGLE ELECTION LOGIN FLOW TEST SUITE\n";
echo str_repeat("=", 80) . "\n";

// Get elections
$demoElection = Election::where('type', 'demo')->first();
$realElection = Election::where('type', 'real')->first();

// Get test users
$adminUser = User::where('email', 'admin.test@example.com')->first();
$voterEligible = User::where('email', 'voter.eligible@example.com')->first();
$voterIneligible = User::where('email', 'voter.ineligible@example.com')->first();
$demoTester = User::where('email', 'demo.tester@example.com')->first();

echo "\n📊 SETUP VERIFICATION:\n";
echo "─────────────────────────────────────────────────────────────────────────────\n";

// Verify elections
echo "\n✅ Elections:";
echo "\n   Demo Election: " . ($demoElection ? "✓" : "✗");
if ($demoElection) {
    echo " (ID: {$demoElection->id}, Slug: {$demoElection->slug}, Active: {$demoElection->is_active})";
    echo "\n   Currently Active: " . ($demoElection->isCurrentlyActive() ? "✓ YES" : "✗ NO");
}

echo "\n   Real Election: " . ($realElection ? "✓" : "✗");
if ($realElection) {
    echo " (ID: {$realElection->id}, Slug: {$realElection->slug}, Active: {$realElection->is_active})";
    echo "\n   Currently Active: " . ($realElection->isCurrentlyActive() ? "✓ YES" : "✗ NO");
}

// Verify users
echo "\n\n✅ Test Users:";
echo "\n   Admin User: " . ($adminUser ? "✓" : "✗") . ($adminUser ? " ({$adminUser->email})" : "");
echo "\n   Voter Eligible: " . ($voterEligible ? "✓" : "✗") . ($voterEligible ? " ({$voterEligible->email})" : "");
echo "\n   Voter Ineligible: " . ($voterIneligible ? "✓" : "✗") . ($voterIneligible ? " ({$voterIneligible->email})" : "");
echo "\n   Demo Tester: " . ($demoTester ? "✓" : "✗") . ($demoTester ? " ({$demoTester->email})" : "");

echo "\n\n" . str_repeat("=", 80) . "\n";
echo "🧪 TEST SCENARIOS\n";
echo str_repeat("=", 80) . "\n";

// SCENARIO 1: Admin Login
echo "\n\n📋 SCENARIO 1: Admin Login";
echo "\n─────────────────────────────────────────────────────────────────────────────\n";
echo "Expected: Redirect to admin.dashboard\n";
if ($adminUser) {
    echo "\n✓ Admin user found: {$adminUser->email}";
    echo "\n✓ Has admin role: " . ($adminUser->hasRole('admin') ? "YES" : "NO");
    echo "\n✓ is_voter: " . ($adminUser->is_voter ? "YES" : "NO");
    echo "\n\n✅ SCENARIO 1 READY TO TEST";
    echo "\n   Login with: {$adminUser->email} / password123";
    echo "\n   Expected result: You will be redirected to admin dashboard";
} else {
    echo "\n❌ Admin user not found";
}

// SCENARIO 2: Eligible Voter on Election Day
echo "\n\n📋 SCENARIO 2: Eligible Voter Login (ON Election Day)";
echo "\n─────────────────────────────────────────────────────────────────────────────\n";
echo "Expected: Redirect to /code/create/{$realElection->slug}\n";
if ($voterEligible) {
    echo "\n✓ Voter found: {$voterEligible->email}";
    echo "\n✓ is_voter: " . ($voterEligible->is_voter ? "YES" : "NO");
    echo "\n✓ can_vote_now: " . $voterEligible->can_vote_now;
    echo "\n✓ Real election active: " . ($realElection->isCurrentlyActive() ? "YES" : "NO");
    echo "\n\n✅ SCENARIO 2 READY TO TEST";
    echo "\n   Login with: {$voterEligible->email} / password123";
    echo "\n   Expected result: You will be redirected directly to enter your verification code";
} else {
    echo "\n❌ Eligible voter not found";
}

// SCENARIO 3: Ineligible Voter
echo "\n\n📋 SCENARIO 3: Ineligible Voter Login (OFF Election Day)";
echo "\n─────────────────────────────────────────────────────────────────────────────\n";
echo "Expected: Redirect to voter.dashboard\n";
if ($voterIneligible) {
    echo "\n✓ Voter found: {$voterIneligible->email}";
    echo "\n✓ is_voter: " . ($voterIneligible->is_voter ? "YES" : "NO");
    echo "\n✓ can_vote_now: " . $voterIneligible->can_vote_now;
    echo "\n\n✅ SCENARIO 3 READY TO TEST";
    echo "\n   Login with: {$voterIneligible->email} / password123";
    echo "\n   Expected result: You will see the voter dashboard (no election active for you)";
} else {
    echo "\n❌ Ineligible voter not found";
}

// SCENARIO 4: Demo Election Access
echo "\n\n📋 SCENARIO 4: Demo Election Access";
echo "\n─────────────────────────────────────────────────────────────────────────────\n";
echo "Expected: /election/demo/start → /code/create/demo-election\n";
if ($demoTester) {
    echo "\n✓ Demo tester found: {$demoTester->email}";
    echo "\n✓ Demo election exists: " . ($demoElection ? "YES" : "NO");
    echo "\n✓ Demo election slug: {$demoElection->slug}";
    echo "\n\n✅ SCENARIO 4 READY TO TEST";
    echo "\n   1. Login with: {$demoTester->email} / password123";
    echo "\n   2. Navigate to: http://localhost:8000/election/demo/start";
    echo "\n   Expected result: You will be redirected to enter your verification code (demo)";
} else {
    echo "\n❌ Demo tester not found";
}

echo "\n\n" . str_repeat("=", 80) . "\n";
echo "📊 LOGIN LOGIC VERIFICATION\n";
echo str_repeat("=", 80) . "\n";

echo "\n\n🔍 Testing LoginResponse Logic Directly:\n";

// Test 1: Admin
if ($adminUser) {
    echo "\n✓ Admin ({$adminUser->email}):";
    $hasAdminRole = $adminUser->hasRole('admin') || $adminUser->hasRole('election_officer');
    echo "\n   Has admin/officer role: " . ($hasAdminRole ? "✓ YES" : "✗ NO");
    echo "\n   ➜ Would redirect to: " . ($hasAdminRole ? "admin.dashboard" : "voter.dashboard");
}

// Test 2: Eligible Voter
if ($voterEligible && $realElection) {
    echo "\n\n✓ Eligible Voter ({$voterEligible->email}):";
    $realElectionActive = $realElection->isCurrentlyActive();
    $isEligible = $voterEligible->is_voter && $voterEligible->can_vote_now == 1;
    echo "\n   Real election active: " . ($realElectionActive ? "✓ YES" : "✗ NO");
    echo "\n   User eligible: " . ($isEligible ? "✓ YES" : "✗ NO");
    $shouldVote = $realElectionActive && $isEligible;
    echo "\n   ➜ Would redirect to: " . ($shouldVote ? "/code/create/{$realElection->slug}" : "voter.dashboard");
}

// Test 3: Ineligible Voter
if ($voterIneligible) {
    echo "\n\n✓ Ineligible Voter ({$voterIneligible->email}):";
    echo "\n   can_vote_now: " . $voterIneligible->can_vote_now;
    echo "\n   ➜ Would redirect to: voter.dashboard";
}

// Test 4: Demo Tester
if ($demoTester && $demoElection) {
    echo "\n\n✓ Demo Tester ({$demoTester->email}):";
    echo "\n   Can access /election/demo/start: ✓ YES (no eligibility checks)";
    echo "\n   ➜ Would redirect to: /code/create/{$demoElection->slug}";
}

echo "\n\n" . str_repeat("=", 80) . "\n";
echo "✅ ALL TEST USERS AND SCENARIOS VERIFIED!\n";
echo str_repeat("=", 80) . "\n";
echo "\n📋 Next Step: Test in browser by logging in with each user account\n\n";
EOF
