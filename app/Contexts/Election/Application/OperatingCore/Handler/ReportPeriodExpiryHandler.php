<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\OperatingCore\Handler;

use App\Contexts\Election\Application\OperatingCore\Command\ReportPeriodExpiryCommand;
use App\Contexts\Election\Domain\OperatingCore\Port\InstantSource;
use App\Contexts\Election\Domain\OperatingCore\Port\ProtocolAppend;
use App\Contexts\Election\Domain\OperatingCore\Repository\AcceptanceGateDecisionRepository;
use App\Contexts\Election\Domain\OperatingCore\Repository\ElectionCommitteeRepository;
use App\Contexts\Election\Domain\OperatingCore\Repository\RecoveryProcessRepository;
use BadMethodCallException;

/**
 * EM-IMPL-002 — UC-4 handler (GREEN-1 surface; behaviour arrives in GREEN-5).
 * A report is not authority (A-4): P-6 alone supplies any consequence; this
 * handler mutates NO aggregate (§4). NO policy-snapshot port. Orchestrates only.
 */
final class ReportPeriodExpiryHandler
{
    public function __construct(
        private readonly ElectionCommitteeRepository $committees,
        private readonly AcceptanceGateDecisionRepository $gates,
        private readonly RecoveryProcessRepository $recoveries,
        private readonly ProtocolAppend $protocol,
        private readonly InstantSource $instants,
    ) {
    }

    public function handle(ReportPeriodExpiryCommand $command): void
    {
        throw new BadMethodCallException('EM-IMPL-002 GREEN-5 pending: UC-4 behaviour is not implemented yet.');
    }
}
