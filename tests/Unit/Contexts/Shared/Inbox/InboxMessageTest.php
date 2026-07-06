<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Shared\Inbox;

use App\Contexts\Shared\Application\Inbox\InboxMessage;
use PHPUnit\Framework\TestCase;

/**
 * PB-003-C1 — Inbox port package: the message DTO crossing the port.
 *
 * Owner: Shared Application · Layer: Application port (pure PHP)
 * Traceability: Blueprint §6/§14 · ADR-T4 · D-06 · Matrix: Inbox
 */
final class InboxMessageTest extends TestCase
{
    private function message(): InboxMessage
    {
        return new InboxMessage(
            eventId: '9f1b6a2e-3c4d-4e5f-8a9b-0c1d2e3f4a5b',
            eventType: 'DeterminationIssued',
            payload: ['determinationId' => 'd-1', 'outcome' => 'upheld'],
            organisationId: 'org-42',
            correlationId: 'corr-x',
            causationId: 'caus-y',
        );
    }

    public function test_exposes_all_identifiers_and_payload(): void
    {
        $m = $this->message();

        $this->assertSame('9f1b6a2e-3c4d-4e5f-8a9b-0c1d2e3f4a5b', $m->eventId);
        $this->assertSame('DeterminationIssued', $m->eventType);
        $this->assertSame(['determinationId' => 'd-1', 'outcome' => 'upheld'], $m->payload);
        $this->assertSame('org-42', $m->organisationId);
        $this->assertSame('corr-x', $m->correlationId);
        $this->assertSame('caus-y', $m->causationId);
    }

    public function test_correlation_and_causation_are_optional(): void
    {
        $m = new InboxMessage(
            eventId: 'e-1',
            eventType: 'FeePaid',
            payload: [],
            organisationId: 'org-1',
        );

        $this->assertNull($m->correlationId);
        $this->assertNull($m->causationId);
    }

    public function test_is_immutable_readonly(): void
    {
        $m = $this->message();

        $this->expectException(\Error::class);
        /** @phpstan-ignore-next-line deliberate violation */
        $m->eventId = 'tampered';
    }
}
