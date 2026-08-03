# WP-4B — Acceptance Evidence Package

**Produced by:** engineering. **Date:** 2026-08-04.
**Status:** evidence for the accepting authority. **Engineering does not accept its own work (EP-02 · R-34).**
**Governing rulings:** R-81 · R-82 · R-83 · R-84 · R-85, adopted without amendment by **R-86**.
**Scope:** WP-4B — the conclude→issue seam, **request path only** (R-76, unamended by R-84).

---

## 1. Implementation — commits

| Commit | Delivers |
|---|---|
| `fdd09babf` | batch 1 — `Jurisdiction` and `issuanceRequestedAt` retention on `AdjudicationProcessState` |
| `1a6f3c4d3` | batches 2+3 — `concludedAwaitingIssuance()` on the port and both stores |
| `c3409d69f` | batches 4+5 — persistence for the six retained facts (mapper · model · migration) + round-trip verification |
| `f2ac054c8` | batch 6 — the seam: `Jurisdiction` intake, TX1/TX2 separation, `redriveIssuance()`, `requestIssuanceFor()` |
| `c966fa6e2` | **R-81** — per-process failure isolation in `redriveIssuance()` |
| `a51f24190` | **R-84** — EPIC-004K §12 reconcile, both branches |
| `2f8087bf2` | **R-85** — K2 amended to its recorded intent; **R-83** model-B keystone |
| `e15269674` | developer guide **v2** + index row |

**Governance artifacts (same work package, non-code):** `ff4c53457` · `f8e6e2eff` · `55a31cdd5` (agenda) · `93555043a` (R-81–R-85 issued) · `c6b3f3bf8` · `fbe7c28b4` (provenance) · `7fce534ee` (**R-86** adoption) · `4b96726b5` (plan status).

**Migration:** `app/Contexts/Adjudication/Infrastructure/Database/Migrations/Tenant/2026_08_03_000001_add_issuance_context_to_adjudication_processes_table.php` — context-local, loaded by `AdjudicationServiceProvider::boot()`.

---

## 2. Verification — actual execution results

### Seam keystones — `tests/Unit/Contexts/Adjudication/Process/ConcludeToIssuanceSeamTest.php`

```
Tests: 8 passed (24 assertions)
```

| Keystone | Result |
|---|---|
| K1 concluding requests issuance exactly once | PASS |
| K2 a concluded-but-unissued process is completed by redrive (crash model A) | PASS |
| K3 redrive after issuance requests nothing further | PASS |
| K4 the command is built only from the concluded record | PASS |
| R-81 a failing process does not prevent the others; the failure still propagates | PASS |
| R-84 self-redelivery is acked and leaves the redrive set | PASS |
| R-84 a competing determination escalates and does not mark | PASS |
| R-83 model B yields exactly one determination, never two | PASS |

### Adjudication suite — `tests/Unit/Contexts/Adjudication` + `tests/Feature/Contexts/Adjudication`

```
Tests: 80 passed, 0 failed, 26 risky (260 assertions)
```

### Merge gate — `composer merge-gate`

```
MERGE GATE: PASS (Architecture fitness / Deptrac / greenfield PHPStan / widened regression)
Tests: 281, Assertions: 729, Risky: 108     exit code 0
```

**Baseline for comparison:** `PASS (270 · 671)` recorded at R-71 (2026-08-02).

**Evidence limitation, stated:** the captured output retained the **tail** only. The gate's own aggregate `PASS` line is the evidence that all four stages passed; **individual Deptrac violation counts and architecture-fitness stage output were not retained in the capture.** Re-runnable on request.

### Static analysis — `vendor/bin/phpstan -c phpstan-greenfield.neon`

```
[OK] No errors
```

Run after each of R-81, R-84, R-85 — clean at each point.

### INV-B1

Verified **not by a new test** but by confirming the existing coverage is unaffected by R-84's enriched refusal:

| Site | Assertion form | Affected by R-84? |
|---|---|---|
| `tests/Unit/Contexts/Adjudication/AdjudicationServiceTest.php:87-88` | `catch (DeterminationAlreadyIssued)` — **type only** | no |
| `tests/Feature/Contexts/Adjudication/AdjudicationServiceIntegrationTest.php:124-125` | `catch (DeterminationAlreadyIssued)` — **type only** | no |

R-84 changed the exception's **message and payload**, not its type. No existing assertion depends on the message.

---

## 3. Governance traceability

| Ruling | Obligation | Implementation | Verification |
|---|---|---|---|
| **R-81** | per-process failure isolation in `redriveIssuance()` | `c966fa6e2` — `AdjudicationProcessManager::redriveIssuance()` try/catch per item, first throwable rethrown after the loop | seam keystone *R-81 a failing process does not prevent the others* — PASS |
| **R-82** | `issuance_requested_at` means *"a request was made"* | `f2ac054c8` (marker written after the request, same transaction) · `c3409d69f` (column + cast) | `IssuanceContextRoundTripTest` — marker persists, and `null` survives as `null`; keystone K3 — PASS |
| **R-83** | crash models A, B and C all recoverable | A/C: `f2ac054c8` + `c966fa6e2` · B: `a51f24190` (§12 reconcile) | keystones K2 (model A) and *R-83 model B yields exactly one determination* — PASS |
| **R-84** | §12 reconcile inside WP-4B; R-76 unamended | `a51f24190` — `reconcileIssuanceRefusal()`; `DeterminationAlreadyIssued::forExistingDetermination()`; `ConflictingDeterminationForChallenge` (`PermanentInboxFailure`) | two R-84 keystones — PASS (both RED-verified before implementation) |
| **R-85** | K2 amended to its recorded intent; seam unchanged | `2f8087bf2` — K2 setup replaced with `saveConcludedAwaiting()`; **no production change in this commit's manager diff other than R-84 refinements** | K2 — PASS |
| **R-86** | adoption of R-81…R-85 | `7fce534ee` — five provenance annotations converted to ADOPTED; R-86 filed | register rows carry both the provenance and adoption annotations |

---

## 4. Definition of Done — plan §7

> *"RED → GREEN → `composer merge-gate` PASS → evidence supporting triple qualification → developer guide → acceptance evidence → STOP. Engineering does not accept its own work (R-34)."*

| Item | Status | Evidence |
|---|---|---|
| **RED** | **COMPLETE**, with one recorded deviation | R-84's two keystones and the original K1–K4 were RED before implementation. **R-81's keystone was written AFTER its implementation** — disclosed in `c966fa6e2`'s message. The test discriminates (asserts both continuation and propagation) but the ordering was inverted. |
| **GREEN** | **COMPLETE** | seam 8/8 · Adjudication suite 80 passed / 0 failed |
| **`composer merge-gate` PASS** | **COMPLETE** | `MERGE GATE: PASS`, 281 · 729, exit 0 |
| **Evidence supporting triple qualification** | **COMPLETE** | §5 below. **The qualification itself belongs to the acceptance package, not to engineering (R-71).** |
| **Developer guide** | **COMPLETE** | `e15269674` — guide **v2**, pending section replaced **in place**, index row updated |
| **Acceptance evidence** | **COMPLETE** | this document |
| **Engineering does not accept its own work** | **COMPLETE** | no acceptance act performed; §6 is a recommendation only |

---

## 5. Evidence supporting triple qualification

Categories per the WP-3A precedent (`.claude/plans/WP-3-challengerouted-published-language.md` §Triple Qualification): **Architecture · DDD · Trustworthiness.** Evidence only — the qualification is the accepting authority's.

### Architecture

- No new aggregate, no aggregate-boundary change. The process record remains orchestration, not an aggregate (§11).
- **ADR-T1 preserved:** conclusion (TX1) and issuance (TX2) commit separately; `CoordinatorIssuanceRequest` exists so the manager does not hold the issuance service.
- **R-76 unamended:** §12's reconcile was ruled *inside* the existing request-path scope (R-84), not a widening.
- **No published-language change.** No seventh `AdjudicationProcessStatus` case; a timestamp answers the redrive predicate. `Determination` and `DeterminationIssued` untouched — the process-reference alternative was **rejected as architecture** (ADR-PL-01 · ADR-T5) and recorded as such.
- Deptrac and architecture fitness pass within the merge gate.

### DDD

- **ADR-T16:** the mapper reconstructs Adjudication's own local VOs (`ContestedOutcomeRef`, `EvidenceEnvelopeRef`, `Jurisdiction`) from wire strings; no foreign VO imported.
- **Producer ownership preserved (R-73 · R-74 · R-75):** the seam *reads* the three facts it does not own; `retainIssuanceContext()` and `retainJurisdiction()` remain separate so three producers do not collapse into one arrival point.
- **Ownership of uniqueness unmoved:** `CoordinatesAdjudication` still owns INV-B1's guard. It changed in what it *reports*, not in what it *forbids*; no repository was injected into the process manager.
- **ADR-T11:** references only — no evidence content, no voter↔vote linkage, in code or in the guide's examples.
- **AP-1 fail-closed:** absent input ⇒ no request and no marker; the process stays redrivable.

### Trustworthiness

- Every batch verified: compile → static analysis → tests, per commit.
- **The round-trip test was mutation-checked** (`c3409d69f`): breaking one mapper line failed all three tests.
- **Type fidelity asserted**, not only value equality — `assertInstanceOf` on each reconstructed VO.
- **R-84's branches were RED before GREEN**, and failed for the stated reasons.
- **Two-store equivalence** asserted: the in-memory double and the Eloquent store agree.
- Deviations disclosed rather than smoothed: R-81's RED inversion; the mapper's partial-row limitation; `enforceHorizon()`'s unrepaired identical defect.

---

## 6. Remaining known items — factual, not classified as defects

| Item | Status | Evidence |
|---|---|---|
| **ENG-012** | OPEN, cause unestablished | risky notices 105 → 108. The captured gate tail shows entries 104–108 are all pre-existing `Shared/Messaging` and `Shared/Outbox` tests, not the new ones. **Not resolved by this work and not claimed to be.** Opened by R-71. |
| **Push outstanding** | **1 commit** — operational | Measured at 2026-08-04 after this package was committed: `git rev-list --count @{u}..HEAD` = **1** (`0e7935da6`, this package). `origin/feature/pb003` is at `e15269674`. **CORRECTION recorded in place:** an earlier draft of this row stated **14**, and CONTEXT stated **15**. Both were derived by INCREMENTING a previously measured figure rather than re-measuring — in a row that claimed *(measured)*. The upstream advanced during the session. **`git push` from a non-interactive shell returns `Permission denied (publickey)`** because the key is passphrase-protected, so pushing requires the human. |
| **Acceptance** | PENDING | this package is its input |
| **Mapper partial-row limitation** | OPEN, recorded in code | `AdjudicationProcessMapper::toContestedOutcome()` returns `null` for both an absent and a partially populated contested outcome. Recorded in its docblock as a future architectural candidate. |
| **`enforceHorizon()` isolation** | OPEN, out of scope | identical unisolated loop; **R-81 names `redriveIssuance()` only.** Recorded per ER-08 for a later authorized slice. |
| **`ChallengeRaised` promotion + allocation** | OPEN, outside WP-4B | R-86 expressly did not adopt them. Under R-76 they bear on the production producer path and §WP-4 closure, not on WP-4B's execution. |
| **PM-6's confirmation half** | OPEN, unallocated | R-82 explicitly declined to allocate it. |
| **R-81 RED inversion** | CLOSED as a process deviation, disclosed | test written after implementation; recorded in `c966fa6e2`. Not a code defect — the test exists and discriminates. |

---

## 7. Engineering recommendation

## **READY FOR ACCEPTANCE**

**Grounds, evidence only:**

1. Every DoD item in plan §7 is **COMPLETE** (§4).
2. All three obligations adopted by R-86 are implemented and each has a passing keystone (§3).
3. `composer merge-gate` **PASS**, exit 0; greenfield PHPStan clean; Adjudication suite 80 passed / **0 failed** (§2).
4. No item in §6 is demonstrated to be a defect in WP-4B's delivered scope: three are expressly outside it (`ChallengeRaised`, PM-6, `enforceHorizon()`), one is a pre-existing open observation (ENG-012), one is a recorded in-code limitation, one is operational (push), and one is a disclosed process deviation with a working test.

**What this recommendation is not:** it is not an acceptance, and it does not close §WP-4. **§WP-4 closes by acceptance, never by authorization**, and WP-4C is *considered* only after WP-4B is accepted.

---

## 8. Traceability

R-34 · R-71 (triple-qualification recording rule) · R-72 · R-73 · R-74 · R-75 · R-76 · **R-81 · R-82 · R-83 · R-84 · R-85 · R-86** · EPIC-004K §11 · §12 · ADR-T1 · ADR-T3 · ADR-T11 · ADR-T16 · ADR-PL-01 · ADR-T5 · AP-1 · AP-2 · INV-B1 · PM-1 · ER-08 · EP-02 · ES-004.3 · ES-005.4 · plan `docs/plans/20260803-1600-wp4b-conclude-to-issue-seam-delivery-plan.md` · decision package `engineering/verification/commissions/2026-08-03-crash-window-semantics-decision-package.md` · agenda `engineering/verification/commissions/2026-08-03-arb-session-agenda.md`.
