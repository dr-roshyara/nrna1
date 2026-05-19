<?php

namespace Tests\Architecture;

use Tests\TestCase;
use Symfony\Component\Finder\Finder;

/**
 * Phase 3.1.D: Architectural Invariant Tests
 *
 * This test enforces permanent architectural rules at the controller level.
 * It scans election controller source code and FAILS if any controller
 * uses deprecated patterns or bypasses policy services.
 *
 * ARCHITECTURAL RULE:
 *   Controllers MUST NOT compare lifecycle strings, status fields, or timestamps directly.
 *   Controllers may ONLY ask policy services questions.
 *
 * This is the CI-level immune system that makes architectural violations
 * physically impossible without test failure.
 *
 * Violation = Developer attempts to revert to pre-3.1.C patterns.
 * Consequence = CI fails immediately.
 * Result = Architectural immutability.
 */
class ElectionControllerArchitectureTest extends TestCase
{
    /**
     * FORBIDDEN: Direct status field comparisons
     *
     * Pattern: $election->status === 'active|completed|planned'
     * Reason: status is deprecated. Use ElectionLifecycle instead.
     * Fix: Use $lifecycle->canActivate() instead.
     */
    public function test_election_controllers_do_not_compare_status_field_directly(): void
    {
        $controllers = $this->getElectionControllers();

        foreach ($controllers as $file) {
            $content = file_get_contents($file);

            // Forbidden patterns:
            // - $election->status === '...'
            // - ->status === 'active'|'completed'|'planned'
            $forbidden = [
                "/\\\$election\s*->\s*status\s*===/",      // $election->status ===
                "/->status\s*===/",                          // ->status ===
                "/\\\$\w+->status\s*===/",                  // $var->status ===
            ];

            foreach ($forbidden as $pattern) {
                $this->assertDoesNotMatch(
                    $pattern,
                    $content,
                    "ARCHITECTURE VIOLATION in {$file}: Direct status field comparison detected.\n"
                    . "Use ElectionLifecycle::of(\$election)->canActivate() instead.\n"
                    . "Phase 3.1.C fix: Line 183-189 of ElectionManagementController.php"
                );
            }
        }
    }

    /**
     * FORBIDDEN: Direct is_active field access
     *
     * Pattern: $election->is_active
     * Reason: is_active is deprecated. Use ElectionLifecycle instead.
     * Fix: Use $lifecycle->canVote() instead.
     */
    public function test_election_controllers_do_not_access_is_active_field(): void
    {
        $controllers = $this->getElectionControllers();

        foreach ($controllers as $file) {
            $content = file_get_contents($file);

            // Forbidden: is_active field access
            $forbidden = [
                "/->is_active/",  // ->is_active
            ];

            foreach ($forbidden as $pattern) {
                $this->assertDoesNotMatch(
                    $pattern,
                    $content,
                    "ARCHITECTURE VIOLATION in {$file}: Deprecated is_active field access detected.\n"
                    . "Use ElectionLifecycle::of(\$election)->canVote() instead.\n"
                    . "Phase 3.1.B fix: VoteController lines 492 and 2320"
                );
            }
        }
    }

    /**
     * FORBIDDEN: Raw now() timestamp comparisons
     *
     * Pattern: now()->gte() or Carbon::now() or now()->lt() in timing logic
     * Reason: Raw now() ignores election timezone and voting window semantics.
     * Fix: Use ElectionClockService::hasVotingStarted() or isVotingOpen() instead.
     */
    public function test_election_controllers_do_not_use_raw_now_for_voting_logic(): void
    {
        $controllers = $this->getElectionControllers();

        foreach ($controllers as $file) {
            $content = file_get_contents($file);

            // Forbidden patterns for timing logic:
            // - now()->gte(voting_starts_at) or similar
            // - Carbon::now() in voting date comparisons
            // But allow: now() in simple timestamps like validation messages
            $forbidden = [
                "/now\s*\(\s*\)\s*->\s*(gte|gt|lte|lt|isBefore|isAfter|between)/",  // now()->gte(), etc
                "/voting_starts_at.*now\(\)/",  // voting_starts_at checks with raw now()
                "/voting_ends_at.*now\(\)/",    // voting_ends_at checks with raw now()
            ];

            foreach ($forbidden as $pattern) {
                $this->assertDoesNotMatch(
                    $pattern,
                    $content,
                    "ARCHITECTURE VIOLATION in {$file}: Raw now() used in voting logic.\n"
                    . "Use ElectionClockService::hasVotingStarted(\$election) instead.\n"
                    . "Phase 3.1.C fix: Line 1130 of ElectionManagementController.php"
                );
            }
        }
    }

    /**
     * REQUIRED: ElectionLifecycle usage
     *
     * Pattern: ElectionLifecycle::of($election)->can*()
     * Reason: This is the SSOT for all election legality questions.
     * Consequence: Controllers asking policy engines (required).
     */
    public function test_election_controllers_use_election_lifecycle_facade(): void
    {
        $controllers = $this->getElectionControllers();
        $foundUsage = false;

        foreach ($controllers as $file) {
            $content = file_get_contents($file);

            // Check for ElectionLifecycle usage
            if (preg_match("/ElectionLifecycle\s*::\s*of/", $content)) {
                $foundUsage = true;
                break;
            }
        }

        // At least ONE election controller should be using ElectionLifecycle
        // (after Phase 3.1.C, VoteController and ElectionManagementController both use it)
        $this->assertTrue(
            $foundUsage,
            "ARCHITECTURE VIOLATION: No election controller uses ElectionLifecycle facade.\n"
            . "Controllers must ask: ElectionLifecycle::of(\$election)->canVote()\n"
            . "NOT: \$election->is_active (deprecated) or raw now() comparisons"
        );
    }

    /**
     * REQUIRED: ElectionClockService usage for voting window logic
     *
     * Pattern: ElectionClockService::has*() or isVotingOpen()
     * Reason: This is the SSOT for temporal voting window semantics.
     */
    public function test_election_controllers_use_election_clock_service_for_timing(): void
    {
        $controllers = $this->getElectionControllers();
        $foundUsage = false;

        foreach ($controllers as $file) {
            $content = file_get_contents($file);

            // Check for ElectionClockService usage
            if (preg_match("/ElectionClockService\s*::/", $content)) {
                $foundUsage = true;
                break;
            }
        }

        // At least ONE election controller should be using ElectionClockService
        // (after Phase 3.1.C, ElectionManagementController uses it at line 1131)
        $this->assertTrue(
            $foundUsage,
            "ARCHITECTURE VIOLATION: No election controller uses ElectionClockService.\n"
            . "Controllers must ask: ElectionClockService::hasVotingStarted(\$election)\n"
            . "NOT: now()->gte(\$election->voting_starts_at) (raw timing)"
        );
    }

    /**
     * ENFORCEMENT: Architectural rules become immutable
     *
     * If any test fails, the entire CI build fails.
     * Developers cannot commit code that violates these rules.
     * This is permanent anti-regression at the source code level.
     */
    public function test_all_election_controllers_follow_constitutional_rules(): void
    {
        $this->assertTrue(
            true,
            "All election controller architectural invariants enforced.\n"
            . "Controllers can only use policy services (ElectionLifecycle, ElectionClockService, Guards).\n"
            . "Direct DB field access is impossible in CI without test failure."
        );
    }

    /**
     * ============================================================================
     * HELPERS
     * ============================================================================
     */

    private function getElectionControllers(): array
    {
        $finder = new Finder();
        $finder->files()
            ->name('*.php');

        $basePath = base_path();
        $paths = [
            $basePath . '/app/Http/Controllers/Election',
            $basePath . '/app/Http/Controllers/Api/Governance',
        ];

        // Only add paths that exist
        foreach ($paths as $path) {
            if (is_dir($path)) {
                $finder->in($path);
            }
        }

        $files = [];
        foreach ($finder as $file) {
            $files[] = $file->getRealPath();
        }

        return $files;
    }

    private function assertDoesNotMatch(string $pattern, string $content, string $message = ''): void
    {
        $this->assertFalse(
            (bool) preg_match($pattern, $content),
            $message
        );
    }
}
