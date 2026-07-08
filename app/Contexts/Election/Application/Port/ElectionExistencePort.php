<?php

declare(strict_types=1);

namespace App\Contexts\Election\Application\Port;

use App\Contexts\Election\Domain\ElectionId;

/**
 * The business dependency: "does this Election exist (within the ambient organisation)?"
 *
 * This is a Strangler seam. Today it is satisfied from the legacy voting platform (the
 * current *operational source of truth* for election existence); when a greenfield
 * Election-lifecycle capability lands, the adapter is swapped with no change here or in
 * the domain. Tenant scoping is an infrastructure concern — the port expresses only
 * Election *identity*, never an organisation id.
 *
 * Contract: `exists()` returns false ONLY for a successfully-answered "no such election
 * in this organisation". If existence cannot be determined (infrastructure failure), the
 * implementation MUST throw — never return false — so a transient fault is not
 * misclassified as a permanent business absence.
 */
interface ElectionExistencePort
{
    public function exists(ElectionId $id): bool;
}
