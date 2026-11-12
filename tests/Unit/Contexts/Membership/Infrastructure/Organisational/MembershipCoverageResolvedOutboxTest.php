<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Infrastructure\Organisational;

use App\Contexts\Membership\Domain\Organisational\ResolvedMember;
use App\Contexts\Membership\Infrastructure\Organisational\MembershipCoverageResolvedOutbox;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Contexts\Membership\Domain\Organisational\Support\MemberHistory;

/**
 * PBDIGIT-343 task 4 (plan D-5; C1/C2 spec §5, §11 CR-02, K-6): the C1 state, its payload encoding and the
 * "only when it changes" rule. Pure: no database.
 */
final class MembershipCoverageResolvedOutboxTest extends TestCase
{
    public function test_the_state_changes_with_coverage_and_not_otherwise(): void
    {
        $h = MemberHistory::start();
        $empty = new ResolvedMember([]);
        $h->commit($h->admit('A1', 20_000, null, true));
        $admitted = $h->member->resolve();
        $h->commit($h->end('E1', 'A1', 20_100));
        $ended = $h->member->resolve();

        $this->assertTrue(MembershipCoverageResolvedOutbox::stateChanged($empty, $admitted));
        $this->assertTrue(MembershipCoverageResolvedOutbox::stateChanged($admitted, $ended));
        $this->assertFalse(MembershipCoverageResolvedOutbox::stateChanged($ended, $ended));
        $this->assertFalse(MembershipCoverageResolvedOutbox::stateChanged($empty, new ResolvedMember([])));
    }

    public function test_in_force_alone_changing_is_a_change(): void
    {
        $lapsing = MemberHistory::start();
        $lapsing->commit($lapsing->admit('A1', 20_000, 20_050));
        $unlimited = MemberHistory::start();
        $unlimited->commit($unlimited->admit('A1', 20_000, null, true));

        $this->assertTrue($lapsing->member->resolve()->coverage()->equals($unlimited->member->resolve()->coverage()));
        $this->assertTrue(MembershipCoverageResolvedOutbox::stateChanged($lapsing->member->resolve(), $unlimited->member->resolve()));
    }

    public function test_intervals_are_calendar_dates_with_an_open_end_as_null(): void
    {
        $h = MemberHistory::start();
        $h->commit($h->admit('A1', 20_000, 20_050))->commit($h->end('E1', 'A1', 20_100))->commit($h->admit('A2', 20_200, null, true));

        $state = MembershipCoverageResolvedOutbox::state($h->member->resolve());

        $this->assertSame([
            'coverage' => [['from' => '2024-10-04', 'to' => '2025-01-12'], ['from' => '2025-04-22', 'to' => null]],
            'inForce' => [['from' => '2024-10-04', 'to' => '2024-11-23'], ['from' => '2025-04-22', 'to' => null]],
            'suspended' => [],
        ], $state);
    }

    public function test_the_aggregate_id_is_a_deterministic_uuid_per_organisation_and_membership(): void
    {
        $org = '6f1b0c3e-8d2a-4b7e-9f10-2a3b4c5d6e7f';

        $this->assertSame(MembershipCoverageResolvedOutbox::aggregateId($org, 'M1'), MembershipCoverageResolvedOutbox::aggregateId($org, 'M1'));
        $this->assertNotSame(MembershipCoverageResolvedOutbox::aggregateId($org, 'M1'), MembershipCoverageResolvedOutbox::aggregateId($org, 'M2'));
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-5[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', MembershipCoverageResolvedOutbox::aggregateId($org, 'not a uuid at all'));
    }
}
