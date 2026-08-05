# WP-4B — The conclude→issue seam, and the six facts it carries

**Step:** WP-4B · **Status:** **v2 — COMPLETE.** The crash-window semantics v1 deferred are **adopted and implemented** (R-81 · R-82 · R-83 · R-84 · R-85, adopted by R-86). Nothing in this guide awaits a governance decision.

WP-2 built the process manager and carried it to a conclusion. It stopped there: `receiveRulingDecision()` ended at `store->save($process->concludeRulingRequested(...))` and **nothing followed it** — no production caller connected a concluded process to issuance. This step builds that seam.

---

## The rule that governs everything here

> **The conclusion and the issuance are two transactions, never one.** (ADR-T1 — one aggregate per transaction)

The process record and the `Determination` aggregate are different roots. One transaction may not write both. So the conclusion commits **alone**, and the issuance request follows in its own transaction — which is precisely what makes a gap between them possible, and why a recovery path has to exist at all.

## Where it fits

```
receiveRulingDecision(challenge, outcome, legitimacy, reason, authority, evidenceSet, jurisdiction)
        │
        ├─ TRANSACTION 1   store->save( concluded->retainJurisdiction($j) )     ← alone (ADR-T1)
        │
        └─ TRANSACTION 2   requestIssuanceFor($concluded)
                              ├─ issuance->request($command)   → RequestsDeterminationIssuance
                              │                                  → CoordinatesAdjudication
                              └─ store->save( markIssuanceRequested(now) )

redriveIssuance()
        └─ foreach store->concludedAwaitingIssuance()  →  requestIssuanceFor($process)
```

## Key files

| File | Role |
|---|---|
| `Application/Port/RequestsDeterminationIssuance.php` | The seam's port — one method, `request(IssueDeterminationCommand)` |
| `Infrastructure/Issuance/CoordinatorIssuanceRequest.php` | The adapter. **Adds nothing** — no retry, no pre-check, no translation |
| `Application/Process/AdjudicationProcessManager.php` | `receiveRulingDecision()` (now `Jurisdiction`-aware) · `redriveIssuance()` · the private `requestIssuanceFor()` seam |
| `Application/Process/AdjudicationProcessState.php` | `retainIssuanceContext()` · `retainJurisdiction()` · `markIssuanceRequested()` + accessors |
| `Application/Port/AdjudicationProcessStore.php` | `concludedAwaitingIssuance()` |
| `Infrastructure/Persistence/AdjudicationProcessMapper.php` | Writes the six columns; reconstructs the three VOs locally (ADR-T16) |
| `…/Database/Migrations/Tenant/2026_08_03_000001_add_issuance_context_…php` | The six nullable columns |

**Note the migration's home.** Adjudication keeps migrations **inside the context**, loaded by `AdjudicationServiceProvider::boot()` — not in `database/migrations/`. Put a new one in the global folder and it will still run, while splitting one context's schema across two roots.

---

## The six retained facts, and why each is *retained* rather than *derived*

The process record holds six pieces of issuance context. **Each arrives from a different producer, and the process stores it exactly as delivered.**

| Fact | Producer | Ruling |
|---|---|---|
| `contestedOutcome` (3 columns) | **Contestation** | R-75 |
| `evidenceEnvelopeRef` | **the Evidence context** | R-74 |
| `jurisdiction` | **the deciding authority** — arrives *with* the decision | R-73 |
| `issuanceRequestedAt` | **Adjudication's own** — when *it* requested issuance | WP-4B |

**This is the part to internalize:** the seam is a **reader** of the first three. It does not fetch a contested outcome, invent a jurisdiction, or substitute a fallback envelope. If you are ever tempted to add a lookup here, that is a producer's job and the lookup would relocate an ownership decision the Board has already taken.

**Two retention methods, deliberately not one:**

```php
$process->retainIssuanceContext($contestedOutcome, $evidenceEnvelopeRef);  // other contexts' facts
$process->retainJurisdiction($jurisdiction);                               // the authority's fact
```

Merging them into a generic "context setter" would collapse three distinct producers into one arrival point and erase from the code the distinction the rulings drew.

## Why a timestamp instead of a seventh status

`issuanceRequestedAt === null` means **concluded but not yet requested**. It is tempting to model that as `AdjudicationProcessStatus::IssuanceRequested`, and you should not: that enum declares its set closed, and **adding a seventh case is an architectural act, not a coding one.** The timestamp answers the same question without touching the Published Language.

## Fail closed (AP-1)

If any required fact is absent, `requestIssuanceFor()` **makes no request and writes no marker**:

```php
if ($contestedOutcome === null || $evidenceEnvelopeRef === null || $jurisdiction === null
    || $authority === null || $outcome === null || $legitimacy === null
    || $reason === null || $consideredEvidence === null) {
    return;
}
```

The process therefore stays in the redrive set and becomes issuable the moment its missing input arrives. **Silence, not a guess** — a determination issued on an invented input is a constitutional defect far worse than a delayed one.

## One seam, two entry points

`receiveRulingDecision()` (live) and `redriveIssuance()` (recovery) both go through `requestIssuanceFor()`. **Keep it that way.** Two code paths building the same command would drift, and the recovery path is the one nobody exercises by hand.

## Marker ordering matters

The marker is written **after** the request, in the same transaction:

```php
$this->issuance->request(new IssueDeterminationCommand(...));
$this->store->save($process->markIssuanceRequested($this->clock->now()));
```

Its meaning is *"issuance was requested"*. Writing it first would let a failure between the two silently drop the process out of the redrive set — the exact failure redrive exists to catch.

## Why the adapter adds nothing

`CoordinatorIssuanceRequest` delegates and stops. `TransactionalAdjudicationService` already owns the transaction, and INV-B1's guard already owns uniqueness at the issuance boundary (EPIC-004E — *"deliberately NOT the aggregate's"*). **An adapter that checked first would move that guard and duplicate the rule.**

The manager does not hold `CoordinatesAdjudication` directly for the same reason: that service opens the issuance transaction and writes the aggregate, so holding it would put both writes in one collaborator's reach and make ADR-T1's separation depend on care rather than structure.

---

## Testing

| Test | Asserts |
|---|---|
| `tests/Unit/…/Process/ConcludeToIssuanceSeamTest.php` | **K1** concluding requests issuance once · **K2** a concluded-but-unissued process is completed by redrive (**crash model A**) · **K3** redrive after issuance is inert · **K4** the command is built only from the record · **R-81** a failing process does not starve the others, and the failure still propagates · **R-84** self-redelivery is acked and leaves the redrive set · **R-84** a competing determination escalates and does not mark · **R-83** model B yields exactly one determination, never two |
| `tests/Feature/…/Adjudication/IssuanceContextRoundTripTest.php` | all six facts survive a real PostgreSQL round trip · the marker persists · the in-memory double and the database agree |

**A test-authoring lesson, kept because it cost a governance cycle.** K2 originally simulated the crash by clearing its spy's `requests` array. That mutated the **spy**, not the **store** — erasing the *record* of a request while leaving its *durable effect*, the marker, in place. The state produced was *marked-but-never-requested*: **no crash model at all, and one the design cannot produce.** K2 and K3 were consequently unsatisfiable together, which surfaced only during GREEN. **A double that records is not a double that persists** — when a test simulates a partial failure, check which half it actually undid. Amended under R-85; the seam was never changed to make it pass.

**Two harness lessons worth inheriting.**

**Construct the manager directly in unit tests.** Resolving it from the container drags in the outbox adapter, which calls `TenantContext::require()` and fails with *"Tenant context not set"* — a harness failure that masks every behavioural assertion.

**Assert VO types, not just values.** An in-memory store round-trips *objects*; a database round-trips *columns*. A mapper handing back raw strings would satisfy every value-equality check and still break every consumer, so the round-trip test uses `assertInstanceOf` alongside value assertions. It was mutation-checked: breaking one mapper line (`'jurisdiction' => null`) fails all three tests.

## Known limitation, recorded rather than hidden

`AdjudicationProcessMapper::toContestedOutcome()` returns `null` both when the contested outcome is **absent** and when it is **partially populated**. The three columns are only ever written together, so a partial row violates the persistence invariant this slice establishes — but distinguishing it would mean introducing a failure mode and an exception type, which was outside the slice's authorized scope. **A future architectural candidate, not an oversight.**

---

## Crash-window recovery — the adopted semantics

**Three crash windows exist because §11 seats the conclusion and the issuance in separate transactions.** All three must be recovered (**R-83**):

| | Crash | Persisted state | Determination? | Recovery |
|---|---|---|---|---|
| **A** | TX1 committed, TX2 never started | concluded · marker `NULL` | no | redrive requests it, then marks |
| **B** | request **succeeded**, marker not written | concluded · marker `NULL` | **yes** | redrive requests → INV-B1 refuses → **§12 reconcile → ack** → marks |
| **C** | request **failed**, marker not written | concluded · marker `NULL` | no | as A |

**A and C are indistinguishable in persistence** — same row, no determination — so any redrive recovering A recovers C. **B is the one that needs §12.**

### What `issuance_requested_at` means (R-82)

**"An issuance request was made." NOT "issuance was confirmed."** This is *forced*, not chosen: R-76 excluded PM-6's confirmation half, so nothing in this slice observes whether a `Determination` was written.

**Consequence, adopted rather than treated as a defect:** the marker **cannot** distinguish A from B — in both a request was attempted and in neither was the marker written. **That is why §12's reconcile exists.** Do not try to make the marker carry confirmation semantics; that belongs to PM-6, which is unallocated.

### §12's reconcile, in two branches (R-84)

```
issuance->request(command)
    └─ DeterminationAlreadyIssued (INV-B1 refused)
         ├─ same deciding authority   → SELF-REDELIVERY → ack: write the marker, no throw
         └─ different / absent        → CONFLICT → ConflictingDeterminationForChallenge
                                          (PermanentInboxFailure: dead-letter + escalate;
                                           the marker is NOT written)
```

**The guard is unchanged in what it FORBIDS — only in what it REPORTS.** `CoordinatesAdjudication` still owns uniqueness; it now raises `DeterminationAlreadyIssued::forExistingDetermination(...)` carrying the existing determination's **minimal identity** (id + authority) so the requester can reconcile. **No repository was injected into the process manager**, and the information travels the existing dependency direction:

```
CoordinatesAdjudication → DeterminationAlreadyIssued → requestIssuanceFor()
```

⚠️ **`IssuedByAuthority` IS THE CURRENT DISCRIMINATOR, NOT THE BUSINESS INVARIANT.** §12's real question is *"did THIS PROCESS previously request issuance?"* — a question about **process identity**. The `Determination` carries no process reference, and adding one would change the constitutional record and its published payload (**ADR-PL-01 · ADR-T5**): architecture, not engineering. **The two questions coincide only under today's model. If a process reference or issuance correlation is ever adopted, THAT becomes the discriminator and this one retires.**

**A refusal carrying no identity is escalated, not acked** — `forChallenge()` is retained for callers with no determination in hand, and such a refusal cannot be reconciled. §12 asks for reconciliation, not for a guess that keeps the queue moving.

**Why the ack must write the marker:** without it the process returns to the redrive set and refuses on every pass — a self-poisoning loop.

### Failure isolation (R-81)

`redriveIssuance()` isolates failures **per process**: each is attempted inside its own `try`, throwables are collected, and **the first is rethrown unchanged after the loop.**

**Why isolation matters here specifically:** `concludedAwaitingIssuance()` orders by `concluded_at`, so without it **the oldest stuck process starved every newer one indefinitely.**

**Why it is not a silent catch:** that would convert a starvation defect into an invisible one. A starved queue is at least observable as a backlog. Rethrowing is safe against a retry, because a process whose issuance was requested has left the redrive set.

**Related, and deliberately NOT fixed here:** `enforceHorizon()` has the same unisolated loop. **R-81 names `redriveIssuance()` only**; repairing the other is a later authorized slice (ER-08).

### How this guide evolves — **update this file, do not add another**

**This is the one authoritative developer guide for the conclude→issue seam.** It is a **Reference artifact** and therefore living (ES-004.3): revisions replace content **in place**.

| Version | Content |
|---|---|
| v1 | the implemented seam + an explicit list of pending governance decisions |
| **v2** (this) | the pending section **replaced in place** by the adopted crash-window semantics (R-81–R-86); banner marker dropped |
| v3+ | ordinary maintenance as the implementation evolves |

**v1 → v2 is what this lifecycle prescribed, performed as prescribed:** the *Pending ARB decision* section was **replaced**, not appended to, and no second file was created.

**Do not create `07_conclude_to_issue_seam_v2.md`.** Two files on one subject fragment the knowledge and guarantee that one of them goes stale unread — and the stale one is always the one the next engineer opens first.

**Note the contrast with the ARB package**, which is *frozen* rather than living: that is a **Decision** artifact serving a single ruling, so it closes on the ruling and is never rewritten (corrections are recorded in place). **Different artifact role, different lifecycle** — do not apply this guide's living-document rule to it, or that one's freeze to this guide.

---

## Traceability

EPIC-004K §11 (persistence · conclude-time atomicity) · **§12 (failure handling — IMPLEMENTED, R-84)** · R-72 (WP-4B authorized) · **R-81** (isolation) · **R-82** (marker semantics) · **R-83** (crash models A/B/C) · **R-84** (§12 inside WP-4B; R-76 unamended) · **R-85** (K2 amended) · **R-86** (adoption) · R-73 · R-74 · R-75 (the three producers) · R-76 (**request path only**) · ADR-T1 · ADR-T11 · ADR-T16 · AP-1 · AP-2 · INV-B1 · commits `fdd09babf` · `1a6f3c4d3` · `c3409d69f` · `f2ac054c8` · `c966fa6e2` · `a51f24190` · `2f8087bf2` · plan `docs/plans/20260803-1600-wp4b-conclude-to-issue-seam-delivery-plan.md`.
