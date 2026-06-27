<?php

namespace App\Domain\Election\Security;

enum TrustValidityScope: string
{
    case ElectionScoped = 'election_scoped';
    case SessionScoped = 'session_scoped';
    case DeviceScoped = 'device_scoped';
    case OverlayScoped = 'overlay_scoped';
    case ManualClearRequired = 'manual_clear';
}
