<?php

namespace App\Application\Election\Monitoring;

use Illuminate\Support\Facades\Log;

final class ConstitutionalDriftMonitor implements DriftMonitorInterface
{
    public function record(SSOTViolationEvent $event): void
    {
        try {
            Log::channel('constitutional_integrity')
                ->warning('constitutional_violation', $event->jsonSerialize());
        } catch (\Throwable) {
            // Monitoring must never block application execution
        }
    }
}
