<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\Committee\CommitteeConstitution;
use App\Contexts\Membership\Domain\Committee\Events\CommitteeCreated;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use PHPUnit\Framework\TestCase;

/**
 * CommitteeConstitutionTest — Mandate definition tests.
 *
 * CommitteeConstitution now holds pure mandate/identity fields.
 * Lifecycle behavior moved to GovernanceState (tested separately).
 */
final class CommitteeConstitutionLifecycleTest extends TestCase
{
    public function test_it_creates_committee_with_identity(): void
    {
        $committee = CommitteeConstitution::create(
            CommitteeId::generate(),
            'National Committee',
            1,
        );

        $this->assertEquals('National Committee', $committee->name());
        $this->assertEquals(1, $committee->levelIndex());
    }

    public function test_it_emits_created_event(): void
    {
        $committee = CommitteeConstitution::create(
            CommitteeId::generate(),
            'Test Committee',
            2,
        );

        $events = $committee->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(CommitteeCreated::class, $events[0]);
    }
}
