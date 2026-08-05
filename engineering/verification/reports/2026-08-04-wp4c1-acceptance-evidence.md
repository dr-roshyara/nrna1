# WP-4C-1 — Acceptance Evidence Package

**Produced by:** engineering, 2026-08-04. **Engineering does not accept its own work (EP-02 · R-34).**
**Authorized by:** **R-89** · **Subdivided by R-88** · Owner: **Adjudication**
**Scope delivered:** D1 (the event) · D2 (hydrator · outbox writer · registration). **D3 and D4 are HELD and are NOT claimed.**

---

## 1. What is offered for acceptance

| Deliverable | File |
|---|---|
| **D1** the domain event | `app/Contexts/Adjudication/Domain/Events/AdjudicationFailureDeclared.php` |
| **D2a** the hydrator | `app/Contexts/Adjudication/Infrastructure/Outbox/AdjudicationFailureDeclaredHydrator.php` |
| **D2b** the outbox writer + dispatch arm | `app/Contexts/Adjudication/Infrastructure/Outbox/OutboxEventAdapter.php` |
| **D2c** registry registration | `app/Contexts/Adjudication/Infrastructure/Providers/AdjudicationServiceProvider.php` |
| developer guide | `developer_guide/adjudication/07_adjudication_failure_declared.md` + index row |

**Commits:** **`df3464c6b`** approved-scope specification · **`e4724a444`** B1 (RED) · **`777bbbb37`** O-2 stop-and-record · **`939e35dcb`** B2+B3 (implementation) · this commit (guide + package).

## 2. Verification — actual execution results

```
K1–K4 (unit + wiring)            14 passed · 37 assertions
writer→hydrator round trip        2 passed · 10 assertions
greenfield PHPStan                [OK] No errors
composer merge-gate               PASS — 295 tests · 773 assertions · exit 0
                                  (baseline before this slice: 281 · 729)
```

| Keystone | Result |
|---|---|
| K1 the event carries the record's four facts unchanged | PASS |
| K1 the event exposes value objects, not strings | PASS |
| K1 the event cannot be constructed with a missing fact *(reflection: every parameter typed and non-nullable)* | PASS |
| K2 the event type is the canonical name | PASS |
| K2 a v1 payload reconstructs an equal event | PASS |
| K2 the hydrator returns value objects, not strings | PASS |
| K3 an unsupported schema version is rejected | PASS |
| K3 a payload missing a required field is rejected *(×4, one per field)* | PASS |
| K4 the provider registers the hydrator | PASS |
| round trip: the written payload hydrates back to an equal event | PASS |
| round trip: the writer stamps the version the hydrator accepts | PASS |

**RED was genuine and specific.** Before implementation: K1 ×3 and K2/K3 ×8 failed with *class not found* — the intended absent artifacts; **K4 failed as an assertion, not an error** (*"publication without registration is not published language (WP-3A)"*), proving it discriminates a missing registration rather than a missing file. The two pre-existing wiring assertions passed throughout — **no false positives introduced.**

**No RED-ordering deviation in this slice: B1 preceded all production code.** Stated because WP-4B carried one — R-81's test was written after its implementation and disclosed as such — and the absence of a repeat is itself evidence worth recording rather than assuming.

## 3. Definition of Done — plan §9

| Item | Status | Evidence |
|---|---|---|
| RED | **COMPLETE** | B1: 12 tests, all failing for their stated reasons |
| GREEN | **COMPLETE** | 14 + 2 passing; §2 |
| `composer merge-gate` PASS | **COMPLETE** | 295 · 773, exit 0 |
| Evidence supporting triple qualification | **COMPLETE** | §4 — **the qualification itself is this package's, not engineering's (R-71)** |
| Developer guide | **COMPLETE** | `07_adjudication_failure_declared.md` |
| Acceptance evidence | **COMPLETE** | this document |
| Engineering does not accept its own work | **COMPLETE** | §7 is a recommendation only |

## 4. Evidence supporting triple qualification

**Architecture** — no aggregate, no aggregate-boundary change, no new abstraction. The adapter's **existing** pattern was followed rather than improved (ARB interpretation of O-2). No published-language artifact was edited: **the catalog is untouched.** Deptrac and architecture fitness pass inside the gate.

**DDD** — the event **carries** a fact the aggregate established; it does not derive or reconstruct one (AP-2). **The invariant stays with the aggregate:** non-nullable event parameters make the absent case unrepresentable rather than defended, and the construction site fails closed (AP-1). `consideredEvidence` is **deliberately absent** with its reason recorded (ASP — absence as a recorded decision). ADR-T11 honoured: `Reason` is the authority's stated ground; no evidence content, no voter↔vote linkage. The event names an existing concept and mints no vocabulary.

**Trustworthiness** — per-batch compile → static analysis → tests. **The round trip caught a real defect on its first run** (`aggregate_id` is a UUID; `ChallengeRef` does not enforce it). K3 is split into two distinct failures. **A deprecation this slice introduced was found by comparing against baseline and removed** — the gate had said PASS with it present.

## 5. Boundaries — what this slice does NOT deliver

> **A registered, hydratable, writable event that no production path yet enqueues.**

| Held | Why |
|---|---|
| **D3 — catalog** | the catalog is FROZEN (*version, never mutate*); **`AdjudicationExpired` is absent from it despite WP-6 shipping hydrated and accepted.** Whether a **new catalog version** is required is open — plan §6/E1 |
| **D4 — publication call site** | provenance: an insufficiency decision *continues* the challenge's conversation, so a new mint would breach the one-mint invariant; `fromConsumed()` needs WP-4D's intake — plan §6/E2 |
| **K5** | follows D4 |
| **WP-4C-2** | EPIC-004K §15.3, Contestation-side design |

## 6. Observations — recorded, none classified as a defect

| Observation | Status |
|---|---|
| **`ChallengeRef` accepts values the persistence model rejects** | recorded in the round-trip test's docblock. A value-object question **outside this slice** |
| **`OutboxEventAdapter` combines dispatch · mapping · provenance · persistence** | recorded as an implementation characteristic and a **candidate future refactoring**; if decomposed, **all events migrate together** |
| **WP-6's `AdjudicationExpiredHydrator` has no unit test and no wiring assertion** | recorded. **An observation about coverage, NOT a defect claim against accepted work** — and the reason this slice's evidence should not be described as parity with WP-6 |
| **`EventHydrator::eventType()` is documented as *"exactly as in the Event Catalog v1.0"*; neither this event nor `AdjudicationExpired` is in it** | E1 evidence. A docblock, not an enforced rule |
| **ENG-012** — risky 108 → 111 | open, cause unestablished, **not resolved here** |
| Unpushed commits | operational; **measured, not estimated**, at report time |

## 7. Engineering recommendation

> **Based on the available implementation and verification evidence, Engineering concludes that WP-4C-1 satisfies its authorized scope — D1 and D2 — and recommends that scope for acceptance. D3 and D4 are held and are not offered. The acceptance decision remains exclusively with the accepting authority.**

**Verdict token: READY FOR ACCEPTANCE (authorized scope only).**

**No observation in §6 is demonstrated to be a defect within the delivered scope**, and no item in §5 is claimed as delivered. **Accepting this does not close WP-4C**, which needs D3, D4 and WP-4C-2; nor does it close §WP-4, which additionally needs WP-4D.

## 8. Traceability

**R-88 · R-89** · R-34 · R-71 · R-79 · R-80 · EPIC-004K **§10 · §15.3** · PM-7 · ADR-T3 · ADR-T5 · ADR-T11 · AP-1 · AP-2 · Constitutional Policy 4 · Constitutional Audit Invariant · WP-3A rule · WP-6 precedent · EP-01 · EP-02 · ES-004.3 · ES-005.4 · plan `docs/plans/20260804-1900-wp4c1-adjudicationfailuredeclared-plan.md` (§6/E1, §6/E2, §11–§17) · EP-03 `2026-08-04-wp4c-engineering-readiness-review.md`.
