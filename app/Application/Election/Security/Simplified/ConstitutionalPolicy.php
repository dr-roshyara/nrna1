<?php

namespace App\Application\Election\Security\Simplified;

use App\Domain\Election\Security\Simplified\ConstitutionalEvidenceSnapshot;

/**
 * ConstitutionalPolicy Interface
 *
 * Policies interpret constitutional articles against evidence.
 * They NEVER derive authority — only evaluate evidence and return PolicyFinding.
 * Authority derivation belongs exclusively in the Resolver.
 */
interface ConstitutionalPolicy
{
    public function evaluate(ConstitutionalEvidenceSnapshot $evidence): PolicyFinding;
}
