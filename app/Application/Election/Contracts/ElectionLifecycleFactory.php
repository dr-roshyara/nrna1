<?php

namespace App\Application\Election\Contracts;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Application\Election\Services\ElectionLifecycleSnapshot;
use App\Models\Election;

final class ElectionLifecycleFactory implements ElectionLifecycleContract
{
    public function of(Election $election): ElectionLifecycle
    {
        return ElectionLifecycle::of($election);
    }

    public function withSnapshot(Election $election, ElectionLifecycleSnapshot $snapshot): ElectionLifecycle
    {
        return ElectionLifecycle::withSnapshot($election, $snapshot);
    }
}
