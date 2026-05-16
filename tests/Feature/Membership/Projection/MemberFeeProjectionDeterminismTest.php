<?php

declare(strict_types=1);

namespace Tests\Feature\Membership\Projection;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Fee\Events\FeePaid;
use App\Contexts\Membership\Application\Member\Listeners\MemberFeeStateListener;

/**
 * Ensures event replay always results in identical state.
 *
 * Invariant: Projection must be pure function of event stream
 */
final class MemberFeeProjectionDeterminismTest extends TestCase
{
    use RefreshDatabase;

    public function test_projection_is_deterministic_across_replay(): void
    {
        $memberId = MemberId::generate();
        $tenantId = $this->createTenant();

        $member = $this->createMember($memberId, $tenantId);

        $feeId = $this->feeId();
        $now = new \DateTimeImmutable();

        $events = [
            new FeePaid($feeId, $memberId, $tenantId, '150.00', 'bank_transfer', $now),
            new FeePaid($feeId, $memberId, $tenantId, '150.00', 'bank_transfer', $now),
        ];

        $listener = app(MemberFeeStateListener::class);

        // First replay
        foreach ($events as $event) {
            Event::dispatch($event);
        }

        $member->refresh();
        $state1 = $member->fee_state;

        // Reset for second replay
        $member->update(['fee_state' => 'unpaid']);

        // Second replay
        foreach ($events as $event) {
            Event::dispatch($event);
        }

        $member->refresh();
        $state2 = $member->fee_state;

        $this->assertEquals($state1, $state2, 'Projection state must be deterministic across replays');
    }
}
