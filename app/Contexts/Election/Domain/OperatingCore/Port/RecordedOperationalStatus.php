<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Port;

use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\OperatingCore\Condition\ElectionOperationalStatus;

/**
 * Driven port: answers ONE question about an identified election — *"what is this
 * election's RECORDED operational status?"* (EM-DOM-001 act B; D1).
 *
 * `ElectionOperationalStatus` is semantically complete and frozen — the two
 * orthogonal recorded facts EM-GOV-059(b) requires (the overlay condition, and
 * whether a halt is recorded and at which gate), retained across becoming
 * Inoperative and across restoration (EM-GOV-059(c)). What the domain lacked was
 * not the concept but the ability to OBTAIN one: nothing could state, for an
 * election, what operational status is recorded, so recorded operational truth
 * could not reach the policies that need it (P-7's resumption target;
 * `ExpiryConsequence`'s EM-GOV-063 consequence). This contract states what must
 * be retrievable, and nothing more.
 *
 * The answer is a READ of recorded truth, never a computation or a
 * re-classification (DD-1) — the core's own recorded-versus-derived distinction,
 * which `HaltedAtGate` ("the recorded fact") already draws against
 * `GateIntervalState` ("DERIVED ... never stored as authoritative state").
 * Absence of a halt is a legitimate recorded answer, discriminated by
 * `isHalted()`: Restoration and Resumption are distinct, and a restoration with
 * no prior halt has a known causal origin but no resumption target (D2). This
 * port therefore fabricates no halt, and supplies only the input to *"where does
 * restoration resume?"* — it carries no verdict on *"why is restoration
 * permitted?"*, and that boundary is preserved here by omission, deliberately
 * (ADR-2 §6(a)).
 *
 * The return is total by type: "no operational status is recorded" is not an
 * answer this contract may give, because Operative-not-halted is a POSITIVE
 * recorded state and not an absence (EM-GOV-062). Whether an election that has
 * not been constituted has a recorded operational status at all is a
 * lifecycle-phase question: BND-1 remains OPEN, and this contract neither
 * answers it nor encodes an answer.
 *
 * BOUNDARY, stated so nothing is read into this file:
 *  - BND-3 remains OPEN, and this contract selects nothing within it. It names
 *    only the election's own `ElectionId` as the key and the frozen status type
 *    as the answer, and prescribes no mechanism of any kind; every reading BND-3
 *    leaves open can supply it unchanged, so it distinguishes none of them
 *    (G-2/G-2a; Gate 1 CONFIRMED). It is silent on that question, in both
 *    directions — nothing here may be read as answering it.
 *  - Like every driven port here, it declares a SUPPLY relationship and never a
 *    transfer of ownership: the meaning of the recorded operational status stays
 *    with this operating core.
 *  - Retrieval only. The recording half is a separate authorized act, and the
 *    mechanism by which a future implementor obtains the status is deliberately
 *    NOT prescribed — the discipline `ProtocolAppend` already models.
 *  - NO ADAPTER AND NO CALLER IS AUTHORIZED by this declaration. Its existence
 *    means only that the domain now states what must be retrievable; it does not
 *    make the status persisted, retrievable at runtime, or reachable by anything.
 */
interface RecordedOperationalStatus
{
    public function ofElection(ElectionId $electionId): ElectionOperationalStatus;
}
