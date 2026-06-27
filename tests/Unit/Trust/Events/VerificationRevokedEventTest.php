<?php

namespace Tests\Unit\Trust\Events;

use App\Contexts\Trust\Domain\Events\VerificationRevokedEvent;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class VerificationRevokedEventTest extends TestCase
{
    public function test_event_can_be_created_with_all_fields(): void
    {
        $verificationId = 'verification-123';
        $userId = 'user-456';
        $revokedBy = 'officer-789';
        $revokedAt = new DateTimeImmutable('2026-05-30 14:00:00');

        $event = new VerificationRevokedEvent(
            verificationId: $verificationId,
            userId: $userId,
            revokedBy: $revokedBy,
            revokedAt: $revokedAt,
        );

        $this->assertEquals($verificationId, $event->verificationId());
        $this->assertEquals($userId, $event->userId());
        $this->assertEquals($revokedBy, $event->revokedBy());
        $this->assertEquals($revokedAt, $event->revokedAt());
    }

    public function test_event_payload_contains_only_trust_concepts(): void
    {
        $event = new VerificationRevokedEvent(
            verificationId: 'verification-123',
            userId: 'user-456',
            revokedBy: 'officer-789',
            revokedAt: new DateTimeImmutable('2026-05-30 14:00:00'),
        );

        // Event should not contain election context
        $this->assertFalse(method_exists($event, 'electionId'));
        $this->assertFalse(method_exists($event, 'reason'));
    }
}
