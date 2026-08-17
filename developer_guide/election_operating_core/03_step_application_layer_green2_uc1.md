# Step 03 — Application Layer, GREEN-2: UC-1 `ExpressCommitteePosition` behaviour

> **Status:** GREEN-2 **APPROVED WITH FIVE REGISTERED CONDITIONS** (PO/ARB, 2026-08-17) · **awaiting independent verification and commit 2** — the lane does not commit its own GREEN (§3b sequencing).
> **Scope:** ONE use case. UC-2…UC-5 and all four queries still throw; that is the causal proof this increment is built on — *one use case → its tests green → frozen suite green → next use case.*

## Purpose

Gives the UC-1 handler its behaviour: a Committee seat expresses a position at an acceptance gate, the fact is recorded, and — **where the domain's own classification changed** — the gate outcome and its F-2 consequences are recorded. One file changed (+153/−3, body only); the frozen domain core stayed byte-identical.

## Where it fits

`app/Contexts/Election/Application/OperatingCore/Handler/ExpressCommitteePositionHandler.php` — the only file GREEN-2 touched. Its command DTO was already complete at GREEN-1. Domain unchanged at `…/Domain/OperatingCore/`.

## How it works — the authorized flow, in code

```
Command → load AG-1/AG-2 → domain verdict BEFORE → ONE domain act
        → append the returned fact → save → domain verdict AFTER
        → record the outcome the domain decided (only if it changed)
```

The act itself never constructs a primary fact — the aggregate returns it (I-8, G-4):

```php
$stateBefore = $decision->intervalState($committee);        // asked, never computed (P-2, I-11)

try {
    $fact = $decision->expressPosition($command->seatId, $command->position, $recordedAt);
} catch (SeatAlreadyExpressedPosition $refused) {
    $this->protocol->append(ProtocolEntry::refusal(
        HistoryKind::ProgressionDecision,
        new RefusalRecord(self::ACT, $refused->getMessage(), $recordedAt),
        $recordedAt,
    ));

    throw $refused;                                         // Q-REF: record + rethrow
}

$this->protocol->append(ProtocolEntry::event(HistoryKind::ProgressionDecision, $fact, $recordedAt));
$this->gates->save($decision);

$this->recordOutcomeOfDecision($command, $stateBefore, $decision->intervalState($committee), $recordedAt);
```

The outcome step branches on the **domain's verdict**, never on arithmetic:

```php
if ($stateAfter === $stateBefore) {
    return;                                                 // verdict unchanged: no new fact (Q-2/A-2)
}

if ($stateAfter === GateIntervalState::DecidedPass) {
    // … append GateSatisfied …
    return;                                                 // B-1: NOTHING ELSE (W-6)
}

if ($stateAfter === GateIntervalState::DecidedFailure) {
    // … append GateFailedByDecision, then startHaltedRecoveryPeriod() …
}
// Open / Unachievable: no outcome fact — Unachievable is never a failure (W-8)
```

F-2's third step binds the policy at start (`EM-GOV-050`(b), I-13) and anchors the period to the **halt instant**, so late sequencing distorts no clock (DD-1):

```php
$binding = $this->policies->snapshotFor(PeriodKind::HaltedElectionRecovery);
$process  = RecoveryProcess::start($command->electionId, PeriodKind::HaltedElectionRecovery, $binding, $haltInstant);
```

## Design decisions — the five conditions registered with the approval

| # | Ruling (binding on GREEN-3 onward) | Consequence for the next handler |
|---|---|---|
| **1** | **`EM-IMPL-002-GREEN2-001`:** where the frozen domain API exposes only the primary operation result and no secondary protocol fact, the Application layer **may append the authorized protocol facts derived from the returned domain classification** — and **must not calculate those classifications.** *Allowed:* `DecidedPass → GateSatisfied`. *Forbidden:* `votes >= threshold → GateSatisfied` | UC-2 may append `ElectionBecameInoperative` **only** from P-4's returned onset — never from its own vacancy count |
| **2** | **Idempotence by verdict comparison is permitted:** the Application may detect whether a domain invocation produced a **protocol-relevant state change**; it must not determine whether the transition is legally valid, nor derive its consequence | UC-2/UC-3 reuse the same before/after enum comparison; never a stored "already emitted" flag (W-4) |
| **3** | ⚠️ **Unknown-aggregate lookup shape stays OPEN.** UC-1 uses `InvalidArgumentException` **by analogy only** — pending the final refusal taxonomy. **Do NOT propagate it as a pattern** | GREEN-3 raises the question again rather than copying UC-1 |
| **4** | **`HistoryKind` assignment is Application protocol mapping** — the handler applies the RED-pinned table using the domain enum; it defines no business meaning | UC-2's four facts are all `Lifecycle`, per the pinned table |
| **5** | The **W-2 guard's lexical scan** is stronger than the architectural rule (it forbids the tokens in *comments* too). GREEN obeys the guard as written; normalization is a **later** hardening item (`PBDIGIT-71`) | never weaken a guard to make production code pass |

Plus the standing invariant, unchanged: the application **may** receive · load · translate · delegate · append; it **may not** interpret · calculate · decide · govern.

### Q-REF, now fixed: record + rethrow

A domain refusal is appended **first** (Q-3 — a refusal record, never a fact), then rethrown. No caller can mistake a refusal for success while return shapes stay open, the handler stays `void`, W-9 holds (never absorbed, never silently retried), and repo Rule 8 keeps the domain message user-visible.

**The catch is narrow on purpose:** only `SeatAlreadyExpressedPosition` (well-formed but rule-refused) is caught. A request referencing nothing in the record is a **caller error** that propagates unrecorded — the protocol holds no entry about a subject that never existed (`EM-GOV-005`, negative half; A-5).

### Why the handler constructs the consequence facts at all (A-9)

The frozen domain returns a **classification** (`GateIntervalState`), not `GateSatisfied`/`GateFailedByDecision`/`RecoveryPeriodStarted` — no factory for them exists, and A-9 pre-registered exactly this: F-2 has no P-4-analogous onset policy, so Increment 2 realizes it as handler orchestration traceable to the approved flow. **The meaning is entirely the domain's; the handler records the verdict it was given.** Whether a domain-side onset policy should exist is a future authorized **domain** slice's decision — never this increment's act.

## Testing

```bash
php artisan test tests/Unit/Contexts/Election/OperatingCoreApplication   # 28 failed · 24 passed
php artisan test tests/Unit/Contexts/Election/OperatingCore              # 42 passed · 2434 assertions
```

**Exactly eight tests moved red → green** — the six UC-1 pins (RED-2 expression under the overlay · F-2 sequence with policy binding and halt-instant anchoring · RED-3 pass-and-nothing-more · I-12 dissent after pass · consequence-not-reissued · Q-2 duplicate) plus the two refusal-taxonomy tests that exercise only UC-1. The 16 structural guards stayed green throughout; nothing outside UC-1 moved.

**Domain byte-identity, verified three ways:** checksum-of-checksums `e2e89e266cf4dd8c75d24d067f768316` before and after · empty `git diff HEAD -- app/Contexts/Election/Domain/OperatingCore/` · `git status` showing one modified file.

**Baseline note for the rest of this increment: 42 tests / 2434 assertions.** The growth over the `EM-IMPL-001` figure of 2420 is the frozen suite's own D-1 scan now covering GREEN-1's 14 files (1606 → 1620 files under `app/`); GREEN-2 added no file.

## Pitfalls

- ⛔ **Never modify an accepted RED test to make production code pass.** GREEN-2's own W-2 guard tripped on a *comment*; the fix was to rephrase the source, not to soften the guard.
- ⛔ **Do not copy the `InvalidArgumentException` lookup shape** into another handler (ruling 3).
- ⛔ **Do not treat `Unachievable` as failure** — no outcome fact; restoration returns it to OPEN (`EM-GOV-059`(c), W-8).
- ⛔ **Do not consult the operational overlay on the expression path** — Meaning-1 makes decision facts recordable regardless of it; a guard there would be Reading B, which the PO did not choose. The overlay's type names are deliberately absent from the file.
- ⚠️ **The `ACT` constant string is a protocol vocabulary surface** (`'express-committee-position'`), matching the frozen `ProtocolAppendContractTest` example — it inherits `EM-OPEN-045` placeholder standing.

## Traceability

GREEN-2 authorization (PO/ARB, 2026-08-17, five registered conditions) · RED commit `1f4b4c5f` · GREEN-1 surface `d2a0fe7c` · guides 01–02 · A-9 · A-5 (provisionally accepted) · Q-REF (fixed here) · `EM-GOV-050`(b) · `EM-GOV-059`(c) · `EM-GOV-070`/`071` · G-1…G-5 · W-1…W-10 · `PBDIGIT-71`.
