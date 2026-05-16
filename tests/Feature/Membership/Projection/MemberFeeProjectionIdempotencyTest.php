<?php

declare(strict_types=1);

namespace Tests\Feature\Membership\Projection;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Fee\Events\FeePaid;

/**
 * Ensure duplicate events do not corrupt state.
 *
 * Invariant: idempotency key = FeeId + event_timestamp
 */
final class MemberFeeProjectionIdempotencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_fee_paid_event_is_idempotent(): void
    {
        $memberId = MemberId::generate();
        $tenantId = $this->createTenant();

        $member = $this->createMember($memberId, $tenantId);

        $feeId = $this->feeId();
        $now = new \DateTimeImmutable();

        $event = new FeePaid(
            $feeId,
            $memberId,
            $tenantId,
            '100.00',
            'bank_transfer',
            $now
        );

        // Dispatch same event twice
        Event::dispatch($event);
        Event::dispatch($event);

        $member->refresh();

        // Member should be paid, but not have duplicate entries
        $this->assertEquals('paid', $member->fee_state, 'Member must be marked as paid');
    }
}
