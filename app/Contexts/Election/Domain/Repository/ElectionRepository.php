<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\Repository;

use App\Contexts\Election\Domain\Election;
use App\Contexts\Election\Domain\ElectionId;

/**
 * Persistence port for the Election aggregate.
 *
 * The contract asks a DOMAIN question only — "give me the election with this identity"
 * — and returns null when it does not exist (ARB refinement, PB-004 round 3). Tenant /
 * organisation scope is NOT part of this contract: it is an application/infrastructure
 * concern applied by the concrete, organisation-scoped implementation (e.g. an Eloquent
 * repository operating under the ambient tenant), keeping the domain tenant-free
 * (ADR-T16). A determination therefore can never resolve an election outside its own
 * organisation — a cross-organisation lookup simply finds nothing.
 */
interface ElectionRepository
{
    public function find(ElectionId $id): ?Election;

    public function save(Election $election): void;
}
