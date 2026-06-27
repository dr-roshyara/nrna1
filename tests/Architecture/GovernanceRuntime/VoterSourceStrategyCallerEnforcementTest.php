<?php

namespace Tests\Architecture\GovernanceRuntime;

use Tests\TestCase;

/**
 * VoterSourceStrategyCallerEnforcementTest — Heuristic Enforcement
 *
 * LEVEL: HEURISTIC
 *
 * Responsibility: Detect if VoterSourceStrategy::fromOrganisation() is called
 * outside the 2 approved callers.
 *
 * Approved callers (ONLY these 2):
 * 1. App\Console\Commands\BackfillVoterSourceStrategy (backfill operation)
 * 2. App\Http\Controllers\Election\ElectionManagementController::store() (creation)
 *
 * Why this restriction exists:
 * - fromOrganisation() derives strategy from org's MUTABLE boolean flag
 * - Should only be called at election CREATION time (snapshot established)
 * - Runtime code must use election's IMMUTABLE snapshot instead
 * - Prevents runtime volatility where org changes affect election governance
 *
 * Consequence of breach:
 * - If other code calls fromOrganisation() at runtime, elections lose sovereignty
 * - Org mutations could retroactively change which voters are eligible
 * - This breaks the constitutional constraint that elections own their authority
 *
 * Note: This is a HEURISTIC test — grep-based pattern detection.
 * It catches obvious violations but cannot detect:
 * - Dynamic method calls via reflection
 * - Calls via variable indirection
 * - Calls through intermediary functions
 *
 * If this test fails: Either add the call to approved callers list OR
 * refactor the calling code to use VoterSourceStrategy::fromElection() instead.
 *
 * @see VoterSourceStrategy::fromOrganisation() for approved caller list
 * @see ElectionManagementController::store() for creation flow
 * @see BackfillVoterSourceStrategy for backfill flow
 */
class VoterSourceStrategyCallerEnforcementTest extends TestCase
{
    /**
     * Test H.1.1: fromOrganisation() only called from approved locations
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_from_organisation_only_called_from_approved_callers(): void
    {
        // Search app/ for calls to VoterSourceStrategy::fromOrganisation() (excluding comments and definitions)
        $process = proc_open(
            "grep -r \"VoterSourceStrategy::fromOrganisation\" app/ --include=\"*.php\" | grep -v \"//\" | grep -v \"public static function\"",
            [1 => ['pipe', 'w']],
            $pipes
        );

        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        proc_close($process);

        $lines = array_filter(explode("\n", trim($output)));

        // Approved caller locations
        $approvedFiles = [
            'app/Console/Commands/BackfillVoterSourceStrategy.php',
            'app/Http/Controllers/Election/ElectionManagementController.php',
        ];

        foreach ($lines as $line) {
            // Parse file path from grep output
            [$filePath] = explode(':', $line, 2);
            $filePath = str_replace(['\\', '/'], '/', $filePath);

            // Check if call is from an approved location
            $isApproved = false;
            foreach ($approvedFiles as $approved) {
                $approvedPath = str_replace(['\\', '/'], '/', $approved);
                if (strpos($filePath, $approvedPath) !== false) {
                    $isApproved = true;
                    break;
                }
            }

            $this->assertTrue(
                $isApproved,
                "VoterSourceStrategy::fromOrganisation() called from unapproved location: {$filePath}\n\n" .
                "Approved callers (ONLY these 2):\n" .
                "  1. {$approvedFiles[0]} (backfill)\n" .
                "  2. {$approvedFiles[1]} (creation)\n\n" .
                "Violation: This call derives governance from mutable org flag at runtime.\n" .
                "Election sovereignty requires immutable snapshot established at creation.\n" .
                "Fix: Either add to approved list OR use VoterSourceStrategy::fromElection() instead."
            );
        }
    }
}
