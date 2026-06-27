<?php

namespace App\Domain\Election\StateMachine;

enum TransitionTrigger: string
{
    case MANUAL = 'manual';
    case TIME = 'time';
    case GRACE_PERIOD = 'grace_period';
    case SYSTEM = 'system';
}
