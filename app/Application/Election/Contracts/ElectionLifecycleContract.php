<?php

namespace App\Application\Election\Contracts;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Application\Election\Services\ElectionLifecycleSnapshot;
use App\Models\Election;

interface ElectionLifecycleContract
{
    public function of(Election $election): ElectionLifecycle;

    public function withSnapshot(Election $election, ElectionLifecycleSnapshot $snapshot): ElectionLifecycle;
}
