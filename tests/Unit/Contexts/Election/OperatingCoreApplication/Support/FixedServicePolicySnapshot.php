<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication\Support;

use App\Contexts\Election\Domain\OperatingCore\Port\ServicePolicySnapshot;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PolicyBinding;

/**
 * Test double of the `ServicePolicySnapshot` ACL port (B-5): hands over ONLY
 * `(policyVersion, duration)` per period kind — never a consequence (EM-OPEN-047
 * resolution; EM-GOV-058 safeguard). Records which kinds were requested so a test
 * can pin that the handler snapshots for the correct period (§8a).
 * EM-IMPL-002 Phase 1 (RED).
 */
final class FixedServicePolicySnapshot implements ServicePolicySnapshot
{
    /** @var list<PeriodKind> */
    public array $requestedKinds = [];

    /** @param array<string, PolicyBinding> $bindings keyed by PeriodKind value */
    public function __construct(private readonly array $bindings)
    {
    }

    public function snapshotFor(PeriodKind $kind): PolicyBinding
    {
        $this->requestedKinds[] = $kind;

        return $this->bindings[$kind->value]
            ?? throw new \RuntimeException(sprintf('No policy binding fixture for period kind "%s".', $kind->value));
    }
}
