<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election;

use App\Contexts\Election\Domain\CorrectionType;
use App\Contexts\Election\Domain\Events\ElectionCorrectionApplied;
use App\Contexts\Election\Infrastructure\Outbox\ElectionCorrectionAppliedHydrator;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * PB-004 Step 4B (RED) — the hydrator reconstructs Election's DOMAIN EVENT
 * `ElectionCorrectionApplied` from the published wire payload (the cross-context
 * contract). It is the inverse of the outbox adapter's writer; the two define the wire
 * contract together (ER-07). Unsupported schema versions are rejected, never silently
 * hydrated.
 */
final class ElectionCorrectionAppliedHydratorTest extends TestCase
{
    private function payloadV1(): array
    {
        return [
            'schema_version' => 1,
            'electionId' => 'election-77',
            'determinationId' => 'det-9',
            'correctionType' => 'contained_only',
            'appliedAt' => '2026-07-08T10:04:00+00:00',
        ];
    }

    public function test_declares_the_canonical_event_type(): void
    {
        $this->assertSame('ElectionCorrectionApplied', (new ElectionCorrectionAppliedHydrator())->eventType());
    }

    public function test_hydrates_the_domain_event_preserving_all_business_data(): void
    {
        $event = (new ElectionCorrectionAppliedHydrator())->hydrate($this->payloadV1());

        $this->assertInstanceOf(ElectionCorrectionApplied::class, $event);
        $this->assertSame('election-77', $event->electionId->toString());
        $this->assertSame('det-9', $event->determinationId->toString());
        $this->assertSame(CorrectionType::ContainedOnly, $event->correctionType);
        $this->assertSame('2026-07-08T10:04:00+00:00', $event->appliedAt->format(DATE_ATOM));
    }

    public function test_unsupported_schema_version_is_rejected_never_silently_hydrated(): void
    {
        $payload = $this->payloadV1();
        $payload['schema_version'] = 99;

        $this->expectException(InvalidArgumentException::class);
        (new ElectionCorrectionAppliedHydrator())->hydrate($payload);
    }
}
