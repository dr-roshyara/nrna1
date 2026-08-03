# WP-4B — The conclude→issue seam, and the six facts it carries

**Step:** WP-4B batches 1–6 · **Status:** 🔶 **v1 — documents the STABLE implementation only.** Crash-window recovery semantics are under ARB review; see [Pending ARB decision](#pending-arb-decision) before relying on redrive behaviour.

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
| `tests/Unit/…/Process/ConcludeToIssuanceSeamTest.php` | K1 concluding requests issuance once · K3 redrive after issuance is inert · K4 the command is built only from the record (**K2 — see below**) |
| `tests/Feature/…/Adjudication/IssuanceContextRoundTripTest.php` | all six facts survive a real PostgreSQL round trip · the marker persists · the in-memory double and the database agree |

**Two harness lessons worth inheriting.**

**Construct the manager directly in unit tests.** Resolving it from the container drags in the outbox adapter, which calls `TenantContext::require()` and fails with *"Tenant context not set"* — a harness failure that masks every behavioural assertion.

**Assert VO types, not just values.** An in-memory store round-trips *objects*; a database round-trips *columns*. A mapper handing back raw strings would satisfy every value-equality check and still break every consumer, so the round-trip test uses `assertInstanceOf` alongside value assertions. It was mutation-checked: breaking one mapper line (`'jurisdiction' => null`) fails all three tests.

## Known limitation, recorded rather than hidden

`AdjudicationProcessMapper::toContestedOutcome()` returns `null` both when the contested outcome is **absent** and when it is **partially populated**. The three columns are only ever written together, so a partial row violates the persistence invariant this slice establishes — but distinguishing it would mean introducing a failure mode and an exception type, which was outside the slice's authorized scope. **A future architectural candidate, not an oversight.**

---

## Pending ARB decision

**The crash-window recovery semantics of this seam are under ARB review (Q1–Q5).** Package: [`engineering/verification/commissions/2026-08-03-crash-window-semantics-decision-package.md`](../../engineering/verification/commissions/2026-08-03-crash-window-semantics-decision-package.md) — 🔒 READY FOR ARB.

**This guide intentionally does not specify replay or reconciliation behaviour until the Board adopts the governing model.** Specifically undocumented here, and not to be inferred from the code:

- which crash models `redriveIssuance()` is required to recover;
- what happens when an issuance request meets an **INV-B1 refusal** — EPIC-004K §12 specifies *reconcile* (ack on self-redelivery, else dead-letter + escalate), and **the currently implemented request path does not realize it**: `DeterminationAlreadyIssued` escapes unhandled;
- whether `redriveIssuance()` must isolate failures per process — today one throwing process aborts the whole pass, and the query orders by `concluded_at`, so the oldest stuck process starves every newer one;
- the canonical meaning of `issuanceRequestedAt` beyond *"a request was made"* (R-76 excluded PM-6's confirmation half from this slice, so nothing here learns whether a determination was written).

**Keystone K2 currently fails, and it is not a defect in this seam.** K2 and K3 cannot both pass: K3 requires the durable marker set by the conclude path, K2 requires its absence, and the store state after concluding is identical in both — K2's only mutation is on its spy, which erases the *record* of a request but never its *durable effect*. **Do not "fix" the seam to make K2 pass.** Its disposition is Q5.

Until the Board rules, treat redrive as *"exists, and is exercised by K3 for the already-requested case"* — nothing stronger.

### How this guide evolves — **update this file, do not add another**

**This is the one authoritative developer guide for the conclude→issue seam.** It is a **Reference artifact** and therefore living (ES-004.3): revisions replace content **in place**.

| Version | Content |
|---|---|
| **v1** (this) | the implemented seam + this explicit list of pending decisions |
| **v2** | **this section is replaced** by the adopted crash-window semantics and reconciliation behaviour, and the status banner drops the 🔶 |
| v3+ | ordinary maintenance as the implementation evolves |

**Do not create `07_conclude_to_issue_seam_v2.md`.** Two files on one subject fragment the knowledge and guarantee that one of them goes stale unread — and the stale one is always the one the next engineer opens first.

**Note the contrast with the ARB package**, which is *frozen* rather than living: that is a **Decision** artifact serving a single ruling, so it closes on the ruling and is never rewritten (corrections are recorded in place). **Different artifact role, different lifecycle** — do not apply this guide's living-document rule to it, or that one's freeze to this guide.

---

## Traceability

EPIC-004K §11 (persistence · conclude-time atomicity) · §12 (failure handling — **specified, not yet realized on this path**) · R-72 (WP-4B authorized) · R-73 · R-74 · R-75 (the three producers) · R-76 (**request path only**) · ADR-T1 · ADR-T11 · ADR-T16 · AP-1 · AP-2 · INV-B1 · commits `fdd09babf` · `1a6f3c4d3` · `c3409d69f` · `f2ac054c8` · plan `docs/plans/20260803-1600-wp4b-conclude-to-issue-seam-delivery-plan.md`.
