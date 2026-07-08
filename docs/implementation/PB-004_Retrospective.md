# PB-004 (Election Reaction) — Retrospective

**Status:** evidence capture (not a Completion Review, not an ADR) · **2026-07-08** · closes after ARB acceptance of PB-004.
**Scope:** captures implementation evidence while fresh, across slices 4A.1 · 4A.2 · 4A.3 · 4B · 4C. **No redesign; no new ADR; candidate patterns remain candidates.**

---

## 1. Architectural assumptions validated
- **Strangler over legacy is executable.** Election reacts to a legacy-owned concept (election existence) through a read-only ACL behind a port, with no legacy import in the domain. The migration seam is real code, not a diagram.
- **The Messaging Platform is reusable, not just reusable-in-theory.** A *second* bounded context (after Adjudication) produced and consumed events through the frozen inbox/outbox/registries/hydrator with **zero platform modification** (ARR PASS).
- **One transaction = one aggregate + its outbox row(s) (ADR-T1) composes with the inbox.** The reaction inherits atomicity from `Inbox::consume()`'s transaction — no bespoke transaction manager was needed, and a mid-consume failure rolls back the outbox write and the reaction-state ledger together (proven by test).

## 2. Strategic DDD decisions confirmed
- **Ownership held stable through implementation** — no drift. Election owns the *concept*, the *reaction*, the *correction event*, and the *publication decision*; the platform owns the *publication mechanism* and *transport identity*; legacy is only the *current operational source of truth* for existence.
- **Tenant-free domain (ADR-T16)** survived contact with persistence: organisation scope lives entirely at the repository/ACL boundary; the domain and its ports express only `ElectionId`.
- **Local VOs, not shared identity** — Election's `ElectionId`/`DeterminationId` are its own; the three `ElectionId` VOs across contexts are intentionally distinct (aggregate identity vs. foreign reference).

## 3. Tactical DDD patterns proven
- **Aggregate decides, handler reacts** — the correction rule lives in `Election::applyDetermination()`, not the inbox handler.
- **Forward-only correction (ADR-T8/T11)** — `CorrectionType` closed on `ContainedOnly`; no reverse/rescind operation exists; anonymity preserved end-to-end (event + ACL carry no voter↔vote linkage).
- **Idempotency at two levels** — same-instance (message replay) and semantic (reconstituted-then-reapplied), backed by a tenant-scoped unique index.

## 4. TDD lessons learned
- **The early RED breach (PB-004 Step 2, prior work) stayed learned.** Every slice since led with a genuine RED (confirmed failing for a stated reason) before GREEN — including infrastructure slices, where "class/binding missing" and "unregistered handler" were the RED signals.
- **Behaviour over transport (ER-07)** kept RED matrices meaningful: aggregate decisions and emitted events were the assertions; wire shape was covered separately by adapter/hydrator round-trips.
- **Reviews record, implementations repair (ER-08)** worked in practice: the `appliedAt` timestamp deviation was *recorded* during EP-02 and *fixed* only in the next authorized slice (4A.1).

## 5. Messaging Platform evidence
- Producer + consumer integration reduced to three small artifacts (outbox adapter, inbox-registry wiring, hydrator) — explicit `match(true)` mapping, `TenantContext::require()` FK guard, schema-version rejection. `EventRegistryCompletenessTest` stayed green with the new hydrator. No new messaging abstraction was required or created.

## 6. Aggregate Reconstruction evidence
- `CompositeElectionRepository` reconstructs the aggregate from **two independent persistence sources** (existence via legacy ACL + reaction state via greenfield ledger); neither is individually authoritative. This resolved the "happy-path always rejects" tension (an existing-but-uncorrected election reconstitutes with an empty idempotency set) and kept `Election ≠ legacy row` explicit as an architectural invariant.

## 7. Engineering — what slowed / accelerated
- **Accelerated:** exploring the sibling (Adjudication) pattern before each slice; reusing `ClockInterface`/`BelongsToTenant`; the plan-as-IDD carrying design cleanly into code.
- **Slowed (usefully):** the pgsql test harness has **no per-test rollback** — discovered empirically via a failing setup, then handled by unique-org-per-test + tenant-scoped assertions. A one-time cost that hardened all Election Feature tests.

## 8. Remaining architectural risks (low, documented, non-blocking)
- **PHPUnit "risky" artifact** — 12 Election Feature tests flag "removed error handlers" only in the mixed suite; clean in isolation. Pre-existing cross-suite/pgsql interaction; retrospective backlog, not a defect.
- **`ElectionId` does not enforce uuid** though the outbox `aggregate_id` is uuid — safe because production ids come from legacy (uuid); recorded observation.

## 9. Candidate engineering patterns (CANDIDATES — not standards)
- **Strangler reconstitution:** `Legacy Source → ACL → Aggregate Reconstruction → Domain Behaviour → Integration Event → Shared Messaging`. Implemented across Adjudication (produce) and Election (react). **Not promoted.** PB-005 is an *opportunity to gather independent implementation evidence*; the ARB decides on promotion **after** reviewing PB-005 — precedent alone does not establish a standard.
- **Inbox-inherited atomicity:** a reacting context needs no own transaction manager. Candidate; re-evaluate at PB-005.

## 10. Architectural Confidence Delta
| Area | Before PB-004 | After PB-004 |
|---|---|---|
| Aggregate Reconstruction | Theoretical | Proven (one context) |
| Shared Messaging reuse | One context (Adjudication) | Two contexts |
| Election qualification | Deferred (partial context) | Complete (hexagonal, fitness-qualified) |
| Greenfield architecture | Partially validated | Strongly validated |
| Strangler viability | Design hypothesis | Implementation evidence |
| Correction loop (constitutional) | Designed | Closed end-to-end (react → publish) |

## 11. Recommendations for PB-005 (Contestation Reaction)
- Reuse PB-004 patterns **as conscious, evidence-justified decisions** — for each reused pattern, state *why it fits*, not "PB-004 did it."
- Follow the workflow: DDD Ownership → IDD → ARB review → RED → GREEN → Regression → Qualification → Completion Review → STOP.
- Treat PB-005 as the independent evidence point for deciding whether the candidate patterns become standards. Introduce a new abstraction only if implementation evidence shows the existing architecture is insufficient.
- Watch two inherited assumptions specifically: (a) does Contestation's reaction also compose existence + reaction-state, or is its persistence shape different? (b) does inbox-inherited atomicity still hold when the reaction writes more than one aggregate/outbox row?

---

*Governance note: this retrospective captures evidence only. It creates no ADR and promotes no candidate pattern. Promotion decisions are deferred to the ARB after PB-005.*
