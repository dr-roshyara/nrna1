<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\TrustPolicyEvaluator;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Models\Election;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * S8 — TrustPolicyEvaluator: null-coalescing operator precedence bug.
 *
 * Root cause (TrustPolicyEvaluator.php:139):
 *   $restrictionEnabled = $election?->network_binding_strategy !== 'none' ?? true;
 *
 *   PHP parses this as: ($election?->network_binding_strategy !== 'none') ?? true
 *   The ?? null-coalescing fires ONLY when its left operand is null.
 *   But !== always produces bool (true or false), NEVER null.
 *   → The `?? true` is permanently dead code — it never executes.
 *
 * Observed runtime consequence:
 *   When $election is null (demo/detached context):
 *     $election?->network_binding_strategy  → null
 *     null !== 'none'                       → true  (null is not equal to 'none')
 *     → restrictionEnabled = true           ← wrongly restricts all voters
 *
 *   The developer's intent: "if we can't determine the strategy, fall back to restricted".
 *   The ?? was meant to apply to the strategy STRING, not to the comparison boolean result.
 *
 * Fix: restructure so ?? applies to the strategy value before the comparison:
 *   $restrictionEnabled = $election !== null && $election->network_binding_strategy !== 'none';
 *
 *   This gives:
 *   - null election  → false (not restricted — no policy configured)   ← the correct fallback
 *   - strategy 'none' → false (explicitly disabled)
 *   - strategy 'ip_count' or other → true (restricted)
 *
 * These tests call the private buildNetworkEvidence() via Reflection —
 * the method is pure logic with no external dependencies.
 */
class TrustPolicyEvaluatorNetworkRestrictionTest extends TestCase
{
    private static Application $app;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // tests/Unit/Application/Election/Security/ → 5 levels → project root
        $basePath = dirname(__DIR__, 5);
        /** @var Application $app */
        $app = require $basePath . '/bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        static::$app = $app;
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    private function evaluator(): TrustPolicyEvaluator
    {
        return static::$app->make(TrustPolicyEvaluator::class);
    }

    private function buildNetworkEvidence(
        TrustPolicyEvaluator $evaluator,
        ?Election $election,
        string $ipHash = 'test-ip-hash',
        ?string $registeredIpHash = null,
        ?int $votesFromThisIp = null,
    ): NetworkTrustEvidence {
        $ref = new ReflectionClass($evaluator);
        $method = $ref->getMethod('buildNetworkEvidence');
        $method->setAccessible(true);

        return $method->invoke($evaluator, $election, $ipHash, $registeredIpHash, $votesFromThisIp);
    }

    private function electionWithStrategy(string $strategy): Election
    {
        $election = new Election();
        $election->network_binding_strategy = $strategy;
        $election->max_votes_per_ip = 6;
        return $election;
    }

    // -----------------------------------------------------------------------
    // S8-a: RED — null election must NOT enable network restriction
    // -----------------------------------------------------------------------

    /**
     * When no election exists (demo/detached context), there is no configured
     * network policy. Network restriction must be OFF.
     *
     * Currently FAILS: null !== 'none' = true → restriction wrongly enabled.
     * After fix ($election !== null && ...): null election → false → correct. ✓
     */
    public function test_null_election_disables_network_restriction(): void
    {
        $network = $this->buildNetworkEvidence($this->evaluator(), null);

        $this->assertFalse(
            $network->restrictionEnabled,
            'null election has no configured network policy — restriction must be disabled. ' .
            'Bug: $election?->network_binding_strategy !== \'none\' evaluates \'null !== none\' = true, ' .
            'making the dead ?? true irrelevant and wrongly enabling restriction.'
        );
    }

    // -----------------------------------------------------------------------
    // S8-b: strategy 'none' → restriction disabled (correct before and after fix)
    // -----------------------------------------------------------------------

    /**
     * Explicit 'none' strategy must always disable restriction.
     * This path works correctly both before and after the fix.
     */
    public function test_none_strategy_disables_restriction(): void
    {
        $network = $this->buildNetworkEvidence($this->evaluator(), $this->electionWithStrategy('none'));

        $this->assertFalse($network->restrictionEnabled);
    }

    // -----------------------------------------------------------------------
    // S8-c: active strategy → restriction enabled (correct before and after fix)
    // -----------------------------------------------------------------------

    /**
     * Any non-'none' strategy must enable restriction.
     * This path works correctly both before and after the fix.
     */
    public function test_ip_count_strategy_enables_restriction(): void
    {
        $network = $this->buildNetworkEvidence($this->evaluator(), $this->electionWithStrategy('ip_count'));

        $this->assertTrue($network->restrictionEnabled);
    }

    public function test_strict_binding_strategy_enables_restriction(): void
    {
        $network = $this->buildNetworkEvidence($this->evaluator(), $this->electionWithStrategy('strict'));

        $this->assertTrue($network->restrictionEnabled);
    }

    // -----------------------------------------------------------------------
    // S8-d: dead code guard — the original ?? true is provably unreachable
    // -----------------------------------------------------------------------

    /**
     * Document the precedence trap: (null !== 'none') is true, so ?? true
     * never fires. Any future reader who sees '?? true' must know it is dead.
     * After the fix, this expression no longer exists.
     */
    public function test_bool_comparison_result_is_never_null_so_null_coalescing_is_dead(): void
    {
        // PHP: null !== 'none' → true (bool), not null → ?? never fires
        $result = null !== 'none';
        $this->assertTrue($result);
        $this->assertIsBool($result);

        // Proof: coalescing a bool value NEVER reaches the fallback
        $coalesced = $result ?? 'FALLBACK_NEVER_REACHED';
        $this->assertSame(true, $coalesced, '?? on a bool value never returns the fallback');
        $this->assertNotSame('FALLBACK_NEVER_REACHED', $coalesced);
    }
}
