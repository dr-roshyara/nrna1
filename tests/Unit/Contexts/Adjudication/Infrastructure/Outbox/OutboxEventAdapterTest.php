<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Adjudication\Infrastructure\Outbox;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Determination\DeterminationOutcome;
use App\Contexts\Adjudication\Domain\Determination\EvidenceEnvelopeRef;
use App\Contexts\Adjudication\Domain\Determination\IssuedByAuthority;
use App\Contexts\Adjudication\Domain\Determination\Jurisdiction;
use App\Contexts\Adjudication\Domain\Determination\Legitimacy;
use App\Contexts\Adjudication\Domain\Determination\Reason;
use App\Contexts\Adjudication\Domain\Events\DeterminationIssued;
use App\Contexts\Adjudication\Infrastructure\Outbox\OutboxEventAdapter;
use App\Services\TenantContext;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;

/**
 * S7 — OutboxEventAdapter: null tenant cast silently produces empty string.
 *
 * Root cause (OutboxEventAdapter.php:38):
 *   'organisation_id' => (string) TenantContext::get()
 *
 *   TenantContext::get() returns ?string. When it returns null, (string) null = ''.
 *   The OutboxEvent is built with organisation_id = '', then save() tries to INSERT
 *   that empty string into a NOT NULL UUID FK column → PDOException → the
 *   DeterminationIssued event is never persisted → silent data loss.
 *
 * Fix: replace TenantContext::get() with TenantContext::require(), which already
 * throws RuntimeException('Tenant context not set') when null.
 *
 * These tests are DB-free for the null-tenant path: with the fix, require() throws
 * BEFORE the OutboxEvent is constructed and before save() is ever called.
 */
class OutboxEventAdapterTest extends TestCase
{
    private static Application $app;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // tests/Unit/Contexts/Adjudication/Infrastructure/Outbox/ → 6 levels → project root
        $basePath = dirname(__DIR__, 6);
        /** @var Application $app */
        $app = require $basePath . '/bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        static::$app = $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        TenantContext::clear();
    }

    protected function tearDown(): void
    {
        TenantContext::clear();
        parent::tearDown();
    }

    // -----------------------------------------------------------------------
    // Helper
    // -----------------------------------------------------------------------

    private function makeDeterminationIssued(): DeterminationIssued
    {
        return new DeterminationIssued(
            determinationId: DeterminationId::fromString('550e8400-e29b-41d4-a716-446655440001'),
            challengeRef: ChallengeRef::fromString('550e8400-e29b-41d4-a716-446655440002'),
            outcome: DeterminationOutcome::Upheld,
            legitimacy: Legitimacy::Legitimate,
            reason: Reason::fromString('Test reason'),
            evidenceEnvelopeRef: EvidenceEnvelopeRef::fromString('550e8400-e29b-41d4-a716-446655440003'),
            issuedByAuthority: IssuedByAuthority::fromString('550e8400-e29b-41d4-a716-446655440004'),
            jurisdiction: Jurisdiction::fromString('test-jurisdiction'),
            contestedOutcome: null,   // S7 tenant test — contested outcome not exercised here
            occurredAt: new \DateTimeImmutable('2026-01-01T00:00:00Z'),
        );
    }

    // -----------------------------------------------------------------------
    // S7-a: RED — null tenant must throw RuntimeException, not silent empty string
    // -----------------------------------------------------------------------

    /**
     * When TenantContext is not set, enqueue() must fail explicitly.
     *
     * Currently FAILS: (string) null → '' → code proceeds, then save() throws a
     * QueryException/PDOException about invalid UUID format — NOT the explicit
     * RuntimeException callers need to catch.
     *
     * After fix (TenantContext::require()): RuntimeException('Tenant context not set')
     * is thrown before any Eloquent code runs — deterministic, catchable, correct. ✓
     */
    public function test_enqueue_throws_explicit_exception_when_tenant_context_is_null(): void
    {
        // Arrange: tenant context is clear (set in setUp)
        $this->assertNull(TenantContext::get(), 'Pre-condition: tenant must be null for this test');

        $adapter = new OutboxEventAdapter();

        // Assert: must get the explicit exception from TenantContext::require()
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Tenant context not set');

        // Act
        $adapter->enqueue($this->makeDeterminationIssued());
    }

    // -----------------------------------------------------------------------
    // S7-b: TenantContext::require() contract — correct method for this use case
    // -----------------------------------------------------------------------

    /**
     * TenantContext::require() is the right tool here:
     * it returns the tenant ID if set, or throws — no silent fallback.
     * This test documents that contract independent of the adapter.
     */
    public function test_tenant_context_require_throws_when_null(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Tenant context not set');

        TenantContext::require();
    }

    public function test_tenant_context_require_returns_value_when_set(): void
    {
        TenantContext::set('org-abc-123');

        $this->assertSame('org-abc-123', TenantContext::require());
    }

    // -----------------------------------------------------------------------
    // S7-c: Regression guard — setting a valid tenant must not throw
    // -----------------------------------------------------------------------

    /**
     * Confirm that with a valid tenant ID, the adapter does NOT throw the
     * "Tenant context not set" RuntimeException. Any subsequent error
     * (DB constraint, connection) is a different failure mode, not the S7 bug.
     */
    public function test_enqueue_does_not_throw_tenant_exception_when_context_is_set(): void
    {
        // Arrange: tenant is set
        TenantContext::set('org-live-999');

        $adapter = new OutboxEventAdapter();

        try {
            $adapter->enqueue($this->makeDeterminationIssued());
        } catch (\RuntimeException $e) {
            // The S7 bug would surface as "Tenant context not set"
            $this->assertStringNotContainsString(
                'Tenant context not set',
                $e->getMessage(),
                'Tenant is set — the "not set" exception must not be thrown'
            );
            // Any other RuntimeException (DB connectivity, etc.) is acceptable in unit context
        } catch (\Throwable) {
            // Non-RuntimeException throwable (PDOException, etc.) is fine in unit context
        }

        // If we reach here without the S7-specific exception, the fix is holding
        $this->addToAssertionCount(1);
    }
}
