<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\OperatingCore\Handler;

use App\Contexts\Election\Application\OperatingCore\Command\ExpressCommitteePositionCommand;
use App\Contexts\Election\Domain\OperatingCore\Port\InstantSource;
use App\Contexts\Election\Domain\OperatingCore\Port\ProtocolAppend;
use App\Contexts\Election\Domain\OperatingCore\Port\ServicePolicySnapshot;
use App\Contexts\Election\Domain\OperatingCore\Repository\AcceptanceGateDecisionRepository;
use App\Contexts\Election\Domain\OperatingCore\Repository\ElectionCommitteeRepository;
use App\Contexts\Election\Domain\OperatingCore\Repository\RecoveryProcessRepository;
use BadMethodCallException;

/**
 * EM-IMPL-002 — UC-1 handler (GREEN-1 surface; behaviour arrives in GREEN-2).
 * Orchestrates only: the domain decides, the protocol records (G-1/G-2).
 * Port wiring pinned by the RED base fixture (§3a — constructor-injected ports
 * only; no new port).
 */
final class ExpressCommitteePositionHandler
{
    public function __construct(
        private readonly ElectionCommitteeRepository $committees,
        private readonly AcceptanceGateDecisionRepository $gates,
        private readonly RecoveryProcessRepository $recoveries,
        private readonly ServicePolicySnapshot $policies,
        private readonly ProtocolAppend $protocol,
        private readonly InstantSource $instants,
    ) {
    }

    public function handle(ExpressCommitteePositionCommand $command): void
    {
        throw new BadMethodCallException('EM-IMPL-002 GREEN-2 pending: UC-1 behaviour is not implemented yet.');
    }
}
