<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\Port;

use App\Contexts\Election\Domain\DeterminationId;
use App\Contexts\Election\Domain\ElectionId;

/**
 * The greenfield-owned reaction state, modelled as a business **ledger**: an append-only
 * record of which determinations have already corrected which election. It is the SECOND
 * persistence source of the Aggregate Reconstruction Invariant (existence being the
 * other, sourced from legacy) — the Election aggregate is reconstructed from both, and
 * neither is individually authoritative for the aggregate as a whole.
 *
 * This is an implementation-composition seam (an Application port), NOT part of the
 * Election domain's ubiquitous language; when the Strangler migration completes it may
 * change or disappear without affecting the domain model.
 */
interface AppliedDeterminationLedger
{
    /**
     * The determinations already applied to this election (within the ambient organisation).
     *
     * @return list<DeterminationId>
     */
    public function appliedDeterminations(ElectionId $id): array;

    /**
     * Record that these determinations have been applied to this election. Idempotent:
     * recording the same determination twice keeps a single entry.
     */
    public function remember(ElectionId $id, DeterminationId ...$determinations): void;
}
