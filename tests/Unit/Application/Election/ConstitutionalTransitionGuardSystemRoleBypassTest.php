<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Services\ConstitutionalTransitionGuard;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot;
use App\Exceptions\InvalidTransitionException;
use App\Models\Election;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\TestCase;

/**
 * S5 — ConstitutionalTransitionGuard: system-role bypass (S5 regression guard).
 *
 * Root cause (ConstitutionalTransitionGuard:99-103):
 *   userHasAnyRole() returns true unconditionally when 'system' is the sole required
 *   role — Auth::user() is never consulted. Any authenticated human making an HTTP
 *   request can call auto_submit (draft → approved) without officer knowledge.
 *
 * Fix: replace `return true;` with `return Auth::user() === null;`.
 *   - System jobs (queue, scheduler) run with no authenticated user → null → allowed.
 *   - Human HTTP requests have a non-null Auth::user()              → non-null → blocked.
 *
 * These tests are DB-free (no factory calls) — they exercise only in-memory auth state.
 * The complementary "no-user passes" case is already covered by the DB-backed test
 * ConstitutionalTransitionGuardTest::system_actions_allowed_without_authenticated_user().
 */
class ConstitutionalTransitionGuardSystemRoleBypassTest extends TestCase
{
    private static Application $app;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // tests/Unit/Application/Election/ → 4 levels up → tests/ → 1 more → project root
        $basePath = dirname(__DIR__, 4);
        /** @var Application $app */
        $app = require $basePath . '/bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        static::$app = $app;
    }

    protected function tearDown(): void
    {
        // Discard guard instances so auth state does not leak between tests.
        Auth::forgetGuards();
        parent::tearDown();
    }

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    private function draftSnapshot(): ElectionLifecycleSnapshot
    {
        return new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::Draft,
            canEdit: true,
            canVote: false,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: true,
            isLocked: false,
            blockedReason: null,
            allowedActions: ['auto_submit'],
        );
    }

    private function setupNominationSnapshot(): ElectionLifecycleSnapshot
    {
        return new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::SetupNomination,
            canEdit: true,
            canVote: false,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: true,
            isLocked: false,
            blockedReason: null,
            allowedActions: ['open_voting'],
        );
    }

    /** Minimal election stub — no DB, no factory, attributes set in memory. */
    private function stubElection(): Election
    {
        $election = new Election();
        $election->id = 999;
        $election->expected_voter_count = 10; // free plan ≤ 40 → capacity_eligibility passes
        return $election;
    }

    private function guard(): ConstitutionalTransitionGuard
    {
        return new ConstitutionalTransitionGuard(); // null metrics — no side effects
    }

    // -----------------------------------------------------------------------
    // S5-a: RED — authenticated human bypasses system-only gate (the bug)
    // -----------------------------------------------------------------------

    /**
     * An authenticated user MUST NOT be able to trigger a system-only action.
     *
     * auto_submit (allowed_roles: ['system']) is designed for queue jobs.
     * A human making an HTTP request with an active session must be rejected.
     *
     * Currently FAILS: userHasAnyRole() short-circuits to `return true` before
     * consulting Auth::user(), so no exception is thrown.
     *
     * After fix (`return Auth::user() === null`): Auth::user() is non-null,
     * returns false, guard throws InvalidTransitionException. ✓
     */
    public function test_system_only_action_is_blocked_when_human_user_is_authenticated(): void
    {
        // Arrange: an authenticated human principal (simulates an HTTP request context).
        // Use a lightweight stub — new User() triggers HasAuditFields which queries DB.
        $user = new class implements Authenticatable {
            public function getAuthIdentifierName(): string { return 'id'; }
            public function getAuthIdentifier(): mixed { return 42; }
            public function getAuthPasswordName(): string { return 'password'; }
            public function getAuthPassword(): string { return ''; }
            public function getRememberToken(): ?string { return null; }
            public function setRememberToken($value): void {}
            public function getRememberTokenName(): string { return 'remember_token'; }
        };
        Auth::setUser($user);

        $this->expectException(InvalidTransitionException::class);

        $this->guard()->assertAllowed($this->stubElection(), 'auto_submit', $this->draftSnapshot());
    }

    // -----------------------------------------------------------------------
    // S5-b: Contract boundary — non-system action still blocks unauthenticated calls
    // -----------------------------------------------------------------------

    /**
     * Null-user pass-through is ONLY valid when 'system' is the sole required role.
     *
     * open_voting requires ['chief']. When no user is authenticated, the guard must
     * still throw — the system principal shortcut must not bleed into human-role actions.
     */
    public function test_non_system_action_still_blocks_unauthenticated_request(): void
    {
        // Arrange: no user (tearDown cleared guards; this test runs with null Auth::user())

        $this->expectException(InvalidTransitionException::class);

        $this->guard()->assertAllowed($this->stubElection(), 'open_voting', $this->setupNominationSnapshot());
    }

    // -----------------------------------------------------------------------
    // S5-c: After fix, 'system' sole-role + null user → allowed (DB-free variant)
    // -----------------------------------------------------------------------

    /**
     * Queue jobs call auto_submit with no HTTP session (Auth::user() === null).
     * After the fix, this must still be allowed.
     *
     * This is a DB-free complement to the existing DB-backed test
     * ConstitutionalTransitionGuardTest::system_actions_allowed_without_authenticated_user().
     */
    public function test_system_only_action_is_allowed_when_no_user_is_authenticated(): void
    {
        // Arrange: Auth state is clean (tearDown cleared guards; no user set)

        // Act — must not throw for the role check
        // (precondition capacity_eligibility will also pass: expected_voter_count = 10 ≤ 40)
        try {
            $this->guard()->assertAllowed($this->stubElection(), 'auto_submit', $this->draftSnapshot());
        } catch (InvalidTransitionException $e) {
            $this->fail(
                'System-only action must be allowed when Auth::user() is null (queue/scheduler context). ' .
                "Exception: {$e->getMessage()}"
            );
        }

        $this->addToAssertionCount(1);
    }
}
