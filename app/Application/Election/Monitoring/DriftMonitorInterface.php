<?php

namespace App\Application\Election\Monitoring;

interface DriftMonitorInterface
{
    public function record(SSOTViolationEvent $event): void;
}
