<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Port;

use App\Contexts\Adjudication\Application\Command\IssueDeterminationCommand;

/**
 * Application port for the second half of the conclude→issue seam (WP-4B · R-72).
 *
 * EPIC-004K §11 seats the PM's conclusion and the aggregate's issuance in **two
 * transactions**: *"the conclusion + the fixed considered-set + the authority reference
 * commit as one write to the PM record… the permanent fixation is the aggregate's."*
 * **ADR-T1 forbids one transaction writing two roots**, so the seam cannot be a single
 * atomic call — and the process manager must therefore REQUEST issuance rather than
 * perform it.
 *
 * This port is that request. It exists so the process manager depends on an
 * **application contract** and not on `CoordinatesAdjudication`, which lets the crash
 * seam be exercised without the aggregate, the repository or a database transaction
 * (roadmap §WP-4's keystone: *concluded-but-unissued → redrive → exactly one
 * determination*).
 *
 * **IDEMPOTENCE IS NOT THIS PORT'S RESPONSIBILITY.** INV-B1 is a **boundary** invariant
 * (EPIC-004E: *"deliberately NOT the aggregate's"*), so a duplicate request is refused
 * at the issuance boundary and reconciled per EPIC-004K §12 — *if this process concluded
 * and requested earlier, ack; if another writer issued, dead-letter and escalate.*
 * **A caller that pre-checked would move the guard and duplicate the rule.**
 *
 * Traceability: R-72 (authorization) · R-76 (scope: the request path only, never the
 * confirmation half) · EPIC-004K §11 · §12 · ADR-T1 · INV-B1.
 */
interface RequestsDeterminationIssuance
{
    /** Request issuance of the determination the concluded process decided. */
    public function request(IssueDeterminationCommand $command): void;
}
