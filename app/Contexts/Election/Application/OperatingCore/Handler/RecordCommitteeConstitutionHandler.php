<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\OperatingCore\Handler;

use App\Contexts\Election\Application\OperatingCore\Command\RecordCommitteeConstitutionCommand;
use App\Contexts\Election\Domain\OperatingCore\Port\InstantSource;
use App\Contexts\Election\Domain\OperatingCore\Port\ProtocolAppend;
use App\Contexts\Election\Domain\OperatingCore\Repository\ElectionCommitteeRepository;
use BadMethodCallException;

/**
 * EM-IMPL-002 — UC-5 handler (GREEN-1 surface; behaviour arrives in GREEN-6).
 * The constitution fact's TYPE is a NAMED OPEN point (Q-UC5/EM-OPEN-045) —
 * GREEN-6 resolves it via review, never silently. Orchestrates only (G-1/G-2).
 */
final class RecordCommitteeConstitutionHandler
{
    public function __construct(
        private readonly ElectionCommitteeRepository $committees,
        private readonly ProtocolAppend $protocol,
        private readonly InstantSource $instants,
    ) {
    }

    public function handle(RecordCommitteeConstitutionCommand $command): void
    {
        throw new BadMethodCallException('EM-IMPL-002 GREEN-6 pending: UC-5 behaviour is not implemented yet.');
    }
}
