<?php

declare(strict_types=1);

namespace Tests\Feature\Membership\Projection;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

use App\Contexts\Membership\Domain\Fee\Events\FeePaid;
use App\Contexts\Membership\Application\Member\Listeners\MemberFeeStateListener;

/**
 * Ensure listener does not crash on missing members.
 *
 * Invariant: system survives missing data without exception
 */
final class MemberFeeProjectionResilienceTest extends TestCase
{
    use RefreshDatabase;

    public function test_listener_handles_missing_member_gracefully(): void
    {
        $tenantId = $this->createTenant();

        $event = new FeePaid(
            $this->feeId(),
            $this->memberIdNonExistent(),
            $tenantId,
            '100.00',
            'bank_transfer',
            new \DateTimeImmutable()
        );

        $listener = app(MemberFeeStateListener::class);

        // Must NOT throw exception
        try {
            Event::dispatch($event);
            $this->assertTrue(true, 'Listener must handle missing member gracefully');
        } catch (\Throwable $e) {
            $this->fail("Listener must not throw on missing member: {$e->getMessage()}");
        }
    }
}
