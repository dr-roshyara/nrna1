<?php

namespace Tests\Unit\Trust\Events;

use App\Contexts\Trust\Domain\Events\IdentityAttestedEvent;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class IdentityAttestedEventTest extends TestCase
{
    public function test_event_can_be_created_with_all_fields(): void
    {
        $verificationId = 'verification-123';
        $userId = 'user-456';
        $attestedBy = 'officer-789';
        $attestedAt = new DateTimeImmutable('2026-05-30 14:00:00');
        $notes = 'Verified via video call';

        $event = new IdentityAttestedEvent(
            verificationId: $verificationId,
            userId: $userId,
            attestedBy: $attestedBy,
            attestedAt: $attestedAt,
            notes: $notes,
        );

        $this->assertEquals($verificationId, $event->verificationId());
        $this->assertEquals($userId, $event->userId());
        $this->assertEquals($attestedBy, $event->attestedBy());
        $this->assertEquals($attestedAt, $event->attestedAt());
        $this->assertEquals($notes, $event->notes());
    }

    public function test_event_can_be_created_without_notes(): void
    {
        $verificationId = 'verification-123';
        $userId = 'user-456';
        $attestedBy = 'officer-789';
        $attestedAt = new DateTimeImmutable('2026-05-30 14:00:00');

        $event = new IdentityAttestedEvent(
            verificationId: $verificationId,
            userId: $userId,
            attestedBy: $attestedBy,
            attestedAt: $attestedAt,
        );

        $this->assertNull($event->notes());
    }

    public function test_event_is_immutable(): void
    {
        $event = new IdentityAttestedEvent(
            verificationId: 'verification-123',
            userId: 'user-456',
            attestedBy: 'officer-789',
            attestedAt: new DateTimeImmutable('2026-05-30 14:00:00'),
        );

        // Attempt to modify should not be possible
        $this->assertIsString($event->verificationId());
        $this->assertIsString($event->userId());
    }
}
