<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election;

use App\Contexts\Election\Domain\CorrectionType;
use App\Contexts\Election\Domain\DeterminationId;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\Events\ElectionCorrectionApplied;
use App\Contexts\Election\Infrastructure\Outbox\ReactionOutboxAdapter;
use App\Services\TenantContext;
use DateTimeImmutable;
use App\Contexts\Shared\Application\Messaging\EventProvenance;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * PB-004 Step 4B (RED) — the outbox adapter must never stamp an empty tenant onto an
 * outbox row (the S7/FK lesson): it resolves the ambient organisation with
 * `TenantContext::require()`, which throws when unset. Mirrors Adjudication's guard.
 */
final class ReactionOutboxAdapterTest extends TestCase
{
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

    public function test_enqueue_requires_an_ambient_tenant(): void
    {
        $event = new ElectionCorrectionApplied(
            ElectionId::fromString('election-77'),
            DeterminationId::fromString('det-9'),
            CorrectionType::ContainedOnly,
            new DateTimeImmutable('2026-07-08T10:04:00+00:00'),
        );

        // No ambient tenant → require() must throw before any row is written.
        $this->expectException(RuntimeException::class);
        (new ReactionOutboxAdapter())->enqueue(EventProvenance::start('corr-1'), $event);
    }
}
