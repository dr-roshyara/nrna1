# Implementation Evidence — the seam's issuance path has no transaction boundary

**Produced by:** engineering, 2026-08-04. **Event D package: implementation evidence that an accepted decision is not realized on a code path.**
**Engineering proposes no remedy and no replacement governance.** The ARB determines whether governance reopens.

---

## 1. The affected decision

`TransactionalAdjudicationService`'s own stated purpose:

> *"Transaction-owning decorator over the (frozen) `CoordinatesAdjudication`. Runs the use case inside **one transaction** so the **Determination write and its outbox enqueue commit atomically**."*

And the port it uses:

> *"Application port for transaction ownership: **the application layer defines the boundary**; infrastructure executes the mechanics… Aggregates and repositories never manage transactions (Constitution §Transaction)."*

**Related:** ADR-T1 (one aggregate per transaction) · the outbox pattern's atomicity guarantee, on which every downstream consumer depends.

## 2. Observed evidence

**Container resolution, executed 2026-08-04:**

```
AdjudicationService::class            → TransactionalAdjudicationService     ← transaction-owning
RequestsDeterminationIssuance::class → CoordinatorIssuanceRequest
  its `coordinator` property         → CoordinatesAdjudication              ← BARE, undecorated
```

**Why:** `AdjudicationServiceProvider` binds `AdjudicationService::class` to a closure that constructs `new TransactionalAdjudicationService(new CoordinatesAdjudication(...), $transactions)` (line 53-68). **`CoordinatesAdjudication` itself is never bound.** `CoordinatorIssuanceRequest::__construct()` type-hints the **concrete** `CoordinatesAdjudication`, so the container **auto-wires a second, undecorated instance** rather than reaching the closure.

**Consequence on that path:** `CoordinatesAdjudication::issueDetermination()` performs two writes — `$this->determinations->save($determination)` and `$this->outbox->enqueue(...)` — **with no enclosing transaction.**

## 3. Observed deficiency

**The two writes the decorator exists to make atomic are not atomic on the seam's path.**

| | |
|---|---|
| **What the decorator guarantees** | determination + outbox enqueue commit together, or neither |
| **What the seam's path does** | two independent writes |
| **Failure it admits** | **determination persisted, outbox event lost** — a determination that exists constitutionally and is never announced |

**This failure mode is not covered by any crash model in scope.** R-83 adopted A (no request), B (request succeeded, marker lost) and C (request failed) — **all of which assume issuance itself is atomic.** *"Issued but never announced"* was not among them because the decorator was assumed to be on the path.

## 4. Current reachability — stated precisely, because it bears on severity

**No production path reaches this today.** The seam is entered from `receiveRulingDecision()` or `redriveIssuance()`, and **nothing in production calls either**: the authority-decision intake is WP-4D, unbuilt and unauthorized. Every existing test supplies a hand-rolled `RequestsDeterminationIssuance` double, so no test traverses the real wiring either.

**So the defect is LATENT, not active.** It becomes reachable the moment WP-4D lands — and would then be a silent, data-losing failure rather than a loud one.

**Engineering does not conclude what that means for priority.** *Latent* is an observation about reachability, not a severity ruling.

## 5. Architectural consequences

1. **The outbox guarantee is bypassed on one path.** Every consumer of `DeterminationIssued` depends on publication being inseparable from the write.
2. **R-84's reconcile could misread the situation.** §12 discriminates on *"a determination already exists"*. A determination persisted without its event would make a redrive's refusal look like crash model B — **the reconcile would ack a determination nobody was ever told about.**
3. **The dependency direction is the mechanism.** The adapter depends on a **concrete final class** rather than the `AdjudicationService` **interface** that carries the transactional binding. **Naming the mechanism is not proposing the fix** — which service the seam should hold is a design decision, and it is not engineering's to take here.

## 6. What engineering did NOT do

- **No production change.** The wiring is untouched.
- **No fix proposed.** §5.3 names the mechanism; it does not recommend a remedy.
- **No test written that asserts the current behaviour.** A passing test documenting a non-atomic path would **entrench** it and make the defect look intended.
- **No severity or priority claimed.**
- **No governance proposed.** Per Event D: evidence and the affected decision only.

## 7. Evidence status

| Claim | Label |
|---|---|
| The container resolves an undecorated `CoordinatesAdjudication` for the seam | **EVIDENCE** — executed, output in §2 |
| `CoordinatesAdjudication` performs two writes with no enclosing transaction | **EVIDENCE** — `CoordinatesAdjudication.php:63-69` |
| The decorator's purpose is to make those two writes atomic | **EVIDENCE** — its own docblock |
| No production path reaches the seam today | **EVIDENCE** — WP-4D unbuilt; no caller exists |
| A determination could persist without its event | **INFERENCE** from the above, not observed in execution |
| R-84's reconcile would misread that state | **INFERENCE** |

## 8. Traceability

`TransactionalAdjudicationService` · `TransactionManager` (Constitution §Transaction) · `CoordinatesAdjudication.php:63-69` · `CoordinatorIssuanceRequest.php` · `AdjudicationServiceProvider.php:49, 53-68` · **ADR-T1** · **R-72** (WP-4B authorized) · **R-83** (crash models A/B/C) · **R-84** (§12 reconcile) · EPIC-004K §11 · §12 · INV-B1.
