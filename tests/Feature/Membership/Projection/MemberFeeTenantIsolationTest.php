<?php

declare(strict_types=1);

namespace Tests\Feature\Membership\Projection;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Fee\Events\FeePaid;

/**
 * Ensure no cross-tenant leakage in projection layer.
 *
 * Invariant: tenantId is part of projection routing key
 */
final class MemberFeeTenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_isolation_is_enforced_in_projection(): void
    {
        $tenantA = $this->createTenant();
        $tenantB = $this->createTenant();

        $memberIdA = MemberId::generate();
        $memberIdB = MemberId::generate();

        $memberA = $this->createMember($memberIdA, $tenantA);
        $memberB = $this->createMember($memberIdB, $tenantB);

        $feeId = $this->feeId();
        $now = new \DateTimeImmutable();

        // FeePaid for Tenant A only
        $event = new FeePaid(
            $feeId,
            $memberIdA,
            $tenantA,
            '100.00',
            'bank_transfer',
            $now
        );

        Event::dispatch($event);

        $memberA->refresh();
        $memberB->refresh();

        $this->assertEquals('paid', $memberA->fee_state, 'Tenant A member must be marked as paid');
        $this->assertEquals('unpaid', $memberB->fee_state, 'Tenant B member must NOT be affected');
    }
}
