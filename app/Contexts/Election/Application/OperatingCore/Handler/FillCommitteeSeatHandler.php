<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\OperatingCore\Handler;

use App\Contexts\Election\Application\OperatingCore\Command\FillCommitteeSeatCommand;
use App\Contexts\Election\Domain\OperatingCore\Port\InstantSource;
use App\Contexts\Election\Domain\OperatingCore\Port\ProtocolAppend;
use App\Contexts\Election\Domain\OperatingCore\Repository\AcceptanceGateDecisionRepository;
use App\Contexts\Election\Domain\OperatingCore\Repository\ElectionCommitteeRepository;
use App\Contexts\Election\Domain\OperatingCore\Repository\RecoveryProcessRepository;
use BadMethodCallException;

/**
 * EM-IMPL-002 — UC-3 handler (GREEN-1 surface; behaviour arrives in GREEN-4).
 * NO policy-snapshot port: restoration RESUMES remaining portions, it never
 * starts a period (EM-GOV-061(a); §8c). Orchestrates only (G-1/G-2).
 */
final class FillCommitteeSeatHandler
{
    public function __construct(
        private readonly ElectionCommitteeRepository $committees,
        private readonly AcceptanceGateDecisionRepository $gates,
        private readonly RecoveryProcessRepository $recoveries,
        private readonly ProtocolAppend $protocol,
        private readonly InstantSource $instants,
    ) {
    }

    public function handle(FillCommitteeSeatCommand $command): void
    {
        throw new BadMethodCallException('EM-IMPL-002 GREEN-4 pending: UC-3 behaviour is not implemented yet.');
    }
}
