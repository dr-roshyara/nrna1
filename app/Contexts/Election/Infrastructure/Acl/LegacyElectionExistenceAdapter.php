<?php

declare(strict_types=1);

namespace App\Contexts\Election\Infrastructure\Acl;

use App\Contexts\Election\Application\Port\ElectionExistencePort;
use App\Contexts\Election\Domain\ElectionId;
use App\Services\TenantContext;
use Illuminate\Support\Facades\DB;

/**
 * Anti-Corruption Layer: answers election existence from the legacy voting platform's
 * `elections` table — the CURRENT operational source of truth, until a greenfield
 * Election-lifecycle capability replaces it (Strangler; then this adapter is swapped
 * with no domain change).
 *
 * Constraints (all constitutional/architectural invariants of the IDD):
 *  - READ-ONLY — never writes to legacy.
 *  - TENANT-SCOPED — filters by the ambient organisation (TenantContext); an election in
 *    another organisation resolves to "not found". Tenant scope lives here, not in the
 *    domain or the port.
 *  - SOFT-DELETE aware — a soft-deleted election does not exist.
 *  - IDENTITY-ONLY / ANONYMITY — reads the `elections` table, which carries no vote/voter
 *    data; it never touches `votes`/`results` (ADR-T11). This is the ONLY code that knows
 *    the legacy schema.
 *  - FAILURE — a query/connection error propagates (QueryException); it is NEVER caught
 *    and turned into `false`, so a transient infrastructure fault is not misread as a
 *    permanent business absence.
 */
final class LegacyElectionExistenceAdapter implements ElectionExistencePort
{
    public function exists(ElectionId $id): bool
    {
        return DB::table('elections')
            ->where('id', $id->toString())
            ->where('organisation_id', TenantContext::get())
            ->whereNull('deleted_at')
            ->exists();
    }
}
