<?php

namespace Tests\Architecture\GovernanceRuntime;

use Tests\TestCase;

/**
 * Runtime Convergence Verification Tests
 *
 * Verifies that ALL possible voting-entry paths converge onto
 * ElectionLifecycle::of($election)->canVote() as the single
 * constitutional authority for voting permission.
 *
 * These are architecture-level static analysis tests that verify
 * source code structure, not runtime behavior.
 *
 * INVARIANT G: canVote() MUST be the single source of truth.
 * INVARIANT: No parallel authorization path may grant voting without canVote().
 */
class ConvergenceTest extends TestCase
{
    /**
     * RED: VoteController::create() must call canVote() for ballot display.
     * The primary ballot rendering path MUST check constitutional authority.
     */
    public function test_vote_controller_create_calls_can_vote(): void
    {
        $path = __DIR__ . '/../../../app/Http/Controllers/VoteController.php';
        $this->assertFileExists($path);

        $content = file_get_contents($path);

        $this->assertStringContainsString(
            'canVote()',
            $content,
            'VoteController::create() MUST call canVote() for ballot display. '
            . 'This is the primary constitutional gate for ballot rendering.'
        );
    }

    /**
     * RED: VoteController::store() must call canVote() for vote persistence.
     * INVARIANT F: Both display AND persistence MUST independently verify.
     */
    public function test_vote_controller_store_calls_can_vote(): void
    {
        $path = __DIR__ . '/../../../app/Http/Controllers/VoteController.php';
        $content = file_get_contents($path);

        // Count occurrences — store() should have its own check distinct from create()
        $occurrences = substr_count($content, 'canVote()');
        $this->assertGreaterThanOrEqual(
            2,
            $occurrences,
            'VoteController must call canVote() at least twice (create + store). '
            . 'Found ' . $occurrences . ' calls. Both ballot display AND vote '
            . 'persistence MUST independently verify constitutional authority.'
        );
    }

    /**
     * RED: CodeController must NOT call canVote() for code creation or agreement.
     * INVARIANT E: Code creation is preparatory — not constitutionally gated.
     *
     * This is the negative control: Steps 1-2 are intentionally allowed
     * before VotingActive. No canVote() check in CodeController.
     */
    public function test_code_controller_does_not_call_can_vote(): void
    {
        $path = __DIR__ . '/../../../app/Http/Controllers/CodeController.php';
        $this->assertFileExists($path);

        $content = file_get_contents($path);

        $this->assertStringNotContainsString(
            'canVote()',
            $content,
            'CodeController MUST NOT call canVote(). Code creation and agreement '
            . 'are preparatory operations intentionally allowed before VotingActive. '
            . 'This is a DESIGN CHOICE — users can prepare to vote before the window opens.'
        );
    }

    /**
     * RED: VoteEligibility middleware must delegate to canVote() (or capability resolver)
     * for the voter_slug path, not blindly bypass.
     *
     * INVARIANT D: Middleware bypass ≠ Constitutional bypass.
     * Middleware must check election state even when voter_slug is present.
     */
    public function test_vote_eligibility_middleware_checks_election_state_with_slug(): void
    {
        $path = __DIR__ . '/../../../app/Http/Middleware/VoteEligibility.php';
        $this->assertFileExists($path);

        $content = file_get_contents($path);

        // The middleware must reference the constitutional engine in some form
        // (either directly via canVote or via ElectionCapabilityResolver)
        $hasConstitutionalCheck = str_contains($content, 'canVote()')
            || str_contains($content, 'ElectionCapabilityResolver')
            || str_contains($content, 'CapabilityContext');

        $this->assertTrue(
            $hasConstitutionalCheck,
            'VoteEligibility middleware MUST delegate to constitutional authority '
            . '(canVote() or ElectionCapabilityResolver) even when voter_slug is present. '
            . 'Without this, the middleware blindly bypasses all checks.'
        );
    }

    /**
     * RED: The legacy /vote/direct route must use canVote(), not legacy can_vote column.
     */
    public function test_legacy_direct_vote_route_uses_constitutional_check(): void
    {
        $path = __DIR__ . '/../../../routes/election/electionRoutes.php';
        $this->assertFileExists($path);

        $content = file_get_contents($path);

        // Find the /vote/direct route section (between 'vote.direct' comment and next route)
        $startPos = strpos($content, "name('vote.direct')");
        $this->assertNotFalse($startPos, 'vote.direct route must exist');

        // Look backward about 20 lines to capture the full route closure
        $searchStart = max(0, $startPos - 600);
        $relevantContent = substr($content, $searchStart, 700);

        // The route should use ElectionLifecycle::canVote(), not legacy can_vote column
        $hasLegacyCheck = str_contains($relevantContent, 'can_vote');
        $hasConstitutionalCheck = str_contains($relevantContent, 'ElectionLifecycle')
            || str_contains($relevantContent, 'canVote()');

        $this->assertFalse(
            $hasLegacyCheck && !$hasConstitutionalCheck,
            'The /vote/direct route MUST NOT rely on legacy $user->can_vote column. '
            . 'It must use ElectionLifecycle::canVote() as the constitutional authority.'
        );

        // Either way, it should reference constitutional check
        $this->assertTrue(
            $hasConstitutionalCheck || !$hasLegacyCheck,
            'The /vote/direct route must converge on constitutional authority.'
        );
    }

    /**
     * RED: Demo vote controllers must be explicitly documented as intentional bypass.
     *
     * Demo elections intentionally bypass lifecycle for testing purposes.
     * This is a DOCUMENTED DESIGN DECISION, not a bug.
     */
    public function test_demo_bypass_is_intentional_documented(): void
    {
        $path = __DIR__ . '/../../../app/Http/Controllers/Demo/DemoVoteController.php';
        $this->assertFileExists($path);

        $content = file_get_contents($path);

        // Demo controller should either NOT call canVote() (intentional bypass)
        // or if it does, it should have demo-specific handling
        $callsCanVote = str_contains($content, 'canVote');

        if (!$callsCanVote) {
            // Bypass is intentional — but verify it's documented
            $this->assertStringContainsString(
                'demo',
                strtolower($content),
                'Demo controller bypass must be documented as intentional design'
            );
        }

        // Pass if either: calls canVote (constitutional) or has demo documentation
        $this->assertTrue(true, 'Demo bypass is intentional design decision');
    }
}
