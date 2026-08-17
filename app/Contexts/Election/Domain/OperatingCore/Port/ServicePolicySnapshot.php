<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Port;

use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PolicyBinding;

/**
 * Driven port (ACL boundary — B-5): the governed period DURATIONS belong to the
 * service-policy context, OUTSIDE Election (EM-GOV-014 Part 2, 059(a);
 * EM-OPEN-050(a) leaves the owning context unsettled). The port hands over ONLY
 * `(policyVersion, duration)` at period start; the service side is never asked
 * for, and can never supply, a CONSEQUENCE (EM-OPEN-047 resolution; EM-GOV-058
 * safeguard: the provider configures duration, never meaning).
 */
interface ServicePolicySnapshot
{
    public function snapshotFor(PeriodKind $kind): PolicyBinding;
}
