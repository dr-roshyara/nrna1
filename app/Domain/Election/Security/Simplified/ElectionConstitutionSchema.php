<?php

namespace App\Domain\Election\Security\Simplified;

final class ElectionConstitutionSchema
{
    public const NETWORK_STRATEGIES = ['none', 'ip_count', 'ip_strict'];
    public const DEVICE_STRATEGIES = ['none', 'fingerprint_required'];
    public const BALLOT_PROTOCOLS = ['single_code', 'dual_code'];
}
