<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication\Support;

use App\Contexts\Election\Domain\OperatingCore\Port\InstantSource;
use App\Contexts\Election\Domain\OperatingCore\Time\RecordedInstant;

/**
 * Test double of the `InstantSource` driven port: recording instants only, set by
 * the test (D-8 / EM-OPEN-024: commands carry no caller-supplied timestamps; the
 * handler obtains the recording instant from this port). No schedule semantics.
 * EM-IMPL-002 Phase 1 (RED).
 */
final class FixedInstantSource implements InstantSource
{
    private RecordedInstant $now;

    public function __construct(int $epochSeconds)
    {
        $this->now = RecordedInstant::fromEpochSeconds($epochSeconds);
    }

    public function now(): RecordedInstant
    {
        return $this->now;
    }

    public function setNowEpoch(int $epochSeconds): void
    {
        $this->now = RecordedInstant::fromEpochSeconds($epochSeconds);
    }
}
