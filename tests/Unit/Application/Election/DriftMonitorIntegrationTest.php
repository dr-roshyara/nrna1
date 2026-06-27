<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Deprecation\DeprecationAccessGuard;
use App\Application\Election\Deprecation\QueryPolicyGuard;
use App\Application\Election\Monitoring\DriftMonitorInterface;
use App\Application\Election\Monitoring\SSOTViolationEvent;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class DriftMonitorIntegrationTest extends TestCase
{
    /**
     * @test
     * STREAM 4: RED — DeprecationAccessGuard fires to DriftMonitor on violation
     */
    public function deprecation_access_guard_fires_to_drift_monitor_on_violation()
    {
        $monitorMock = $this->createMock(DriftMonitorInterface::class);
        $monitorMock->expects($this->once())
            ->method('record')
            ->with($this->isInstanceOf(SSOTViolationEvent::class));

        $guard = new DeprecationAccessGuard($monitorMock);
        $guard->checkFieldAccess('status', 'TestContext', 'warning');
    }

    /**
     * @test
     * STREAM 4: RED — QueryPolicyGuard fires to DriftMonitor on violation
     */
    public function query_policy_guard_fires_to_drift_monitor_on_violation()
    {
        $monitorMock = $this->createMock(DriftMonitorInterface::class);
        $monitorMock->expects($this->once())
            ->method('record')
            ->with($this->isInstanceOf(SSOTViolationEvent::class));

        $guard = new QueryPolicyGuard($monitorMock);

        try {
            $guard->assertAllowedQuery(['status' => 'active'], 'TestContext');
        } catch (\Exception) {
            // Expected to throw DeprecatedQueryException
        }
    }

    /**
     * @test
     * STREAM 4: RED — DriftMonitor receives correct layer from DeprecationAccessGuard
     */
    public function drift_monitor_receives_correct_layer_from_access_guard()
    {
        $monitorMock = $this->createMock(DriftMonitorInterface::class);
        $monitorMock->expects($this->once())
            ->method('record')
            ->with($this->callback(function (SSOTViolationEvent $event) {
                return $event->violationType === 'deprecated_field_access' &&
                       $event->layer === 'deprecation_access' &&
                       $event->context === 'ElectionVotingController';
            }));

        $guard = new DeprecationAccessGuard($monitorMock);
        $guard->checkFieldAccess('status', 'ElectionVotingController', 'warning');
    }

    /**
     * @test
     * STREAM 4: RED — Guards with no monitor still work (regression guard)
     */
    public function guards_with_no_monitor_still_pass()
    {
        $accessGuard = new DeprecationAccessGuard();
        $queryGuard = new QueryPolicyGuard();

        // Should not throw even with no monitor
        $accessGuard->checkFieldAccess('status', 'TestContext', 'warning');

        try {
            $queryGuard->assertAllowedQuery(['status' => 'active'], 'TestContext');
        } catch (\Exception) {
            // Expected to throw DeprecatedQueryException, but not due to null monitor
        }
    }
}
