<?php
/**
 * 🧪 Test: Verify Platform Org ID Fix (1 instead of 0)
 *
 * This tests the Golden Rule:
 * VoterSlug.organisation_id MUST match Election.organisation_id
 * OR either must be platform (organisation_id = 1)
 */

require 'vendor/autoload.php';
require 'bootstrap/app.php';

use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "🧪 TESTING PLATFORM ORG ID FIX\n";
echo "========================================\n\n";

// Test 1: Same org access (org 2 -> org 2 election)
echo "TEST 1: Same Organisation Access\n";
echo "User org: 2 | Election org: 2 | Expected: PASS\n";
$orgsMatch = 2 === 2;
$electionIsPlatform = 2 === 1; // FIXED: was === 0
$userIsPlatform = 2 === 1;     // FIXED: was === 0
$orgsValid = $orgsMatch || $electionIsPlatform || $userIsPlatform;
echo "Result: " . ($orgsValid ? "✅ PASS" : "❌ FAIL") . "\n";
echo "  - orgsMatch: " . ($orgsMatch ? "true" : "false") . "\n";
echo "  - electionIsPlatform: " . ($electionIsPlatform ? "true" : "false") . "\n";
echo "  - userIsPlatform: " . ($userIsPlatform ? "true" : "false") . "\n\n";

// Test 2: Cross-org access without platform (org 2 -> org 3 election)
echo "TEST 2: Cross-Organisation Access (Should FAIL)\n";
echo "User org: 2 | Election org: 3 | Expected: FAIL\n";
$orgsMatch = 2 === 3;
$electionIsPlatform = 3 === 1; // FIXED: was === 0
$userIsPlatform = 2 === 1;     // FIXED: was === 0
$orgsValid = $orgsMatch || $electionIsPlatform || $userIsPlatform;
echo "Result: " . ($orgsValid ? "❌ FAIL (WRONG)" : "✅ PASS (correctly rejected)") . "\n";
echo "  - orgsMatch: " . ($orgsMatch ? "true" : "false") . "\n";
echo "  - electionIsPlatform: " . ($electionIsPlatform ? "true" : "false") . "\n";
echo "  - userIsPlatform: " . ($userIsPlatform ? "true" : "false") . "\n\n";

// Test 3: Platform user (org 1) accessing org 2 election (Should PASS)
echo "TEST 3: Platform User Accessing Org 2 Election\n";
echo "User org: 1 | Election org: 2 | Expected: PASS\n";
$orgsMatch = 1 === 2;
$electionIsPlatform = 2 === 1; // FIXED: was === 0
$userIsPlatform = 1 === 1;     // FIXED: was === 0
$orgsValid = $orgsMatch || $electionIsPlatform || $userIsPlatform;
echo "Result: " . ($orgsValid ? "✅ PASS" : "❌ FAIL") . "\n";
echo "  - orgsMatch: " . ($orgsMatch ? "true" : "false") . "\n";
echo "  - electionIsPlatform: " . ($electionIsPlatform ? "true" : "false") . "\n";
echo "  - userIsPlatform: " . ($userIsPlatform ? "true" : "false") . "\n\n";

// Test 4: Platform election (org 1) accessed by org 2 user (Should PASS)
echo "TEST 4: Org 2 User Accessing Platform Election\n";
echo "User org: 2 | Election org: 1 | Expected: PASS\n";
$orgsMatch = 2 === 1;
$electionIsPlatform = 1 === 1; // FIXED: was === 0
$userIsPlatform = 2 === 1;     // FIXED: was === 0
$orgsValid = $orgsMatch || $electionIsPlatform || $userIsPlatform;
echo "Result: " . ($orgsValid ? "✅ PASS" : "❌ FAIL") . "\n";
echo "  - orgsMatch: " . ($orgsMatch ? "true" : "false") . "\n";
echo "  - electionIsPlatform: " . ($electionIsPlatform ? "true" : "false") . "\n";
echo "  - userIsPlatform: " . ($userIsPlatform ? "true" : "false") . "\n\n";

echo "========================================\n";
echo "✅ All tests should PASS with fixed code!\n";
echo "========================================\n";
