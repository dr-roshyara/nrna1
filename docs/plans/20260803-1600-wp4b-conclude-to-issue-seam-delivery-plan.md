# WP-4B — Delivery Plan: the conclude→issue seam

**Created:** 2026-08-03 16:00 · **Author:** Chief Software Architect / Technical Delivery Lead
**Authorized by:** **R-72** · **Scope fixed by R-76** (request path only) · **Governing model: R-73 · R-74 · R-75**
**Type:** delivery artefact. **Not a governance document · no architecture proposed · no ADR reinterpreted · no Board decision improved.**

---

## 1. Implementation Readiness Review

### 1a. What the slice must prove — one keystone, from the roadmap

> **`concluded-but-unissued → redrive → EXACTLY ONE determination`**

**R-76 fixes the boundary: the request path and its crash-safe redrive. PM-6's issuance-confirmation half is outside this slice.**

### 1b. Inputs `IssueDeterminationCommand` requires — and where each stands

| Input | Source under the adopted model | Available? |
|---|---|---|
| `challengeRef` · `outcome` · `legitimacy` · `reason` · `issuedByAuthority` · `evidenceSet` · `occurredAt` | the concluded `AdjudicationProcessState` | ✅ **all seven, via existing accessors** |
| **`jurisdiction`** | **the deciding authority (R-73)** — arrives on the decision; `receiveRulingDecision()` accepts it, the state retains it | ⛔ **producer absent.** The signature change is in-slice; **the production caller is not** |
| **`evidenceEnvelopeRef`** | **the Evidence context, by integration event (R-74)** | ⛔ **producer absent.** R-74's owner has no code home. **R-74's implication rejects deriving it from `EvidenceSet`** (H2/H3), so the state must *retain* a distinct ref |
| **`contestedOutcome`** | **Contestation, via a promoted `ChallengeRaised` (R-75)** | ⛔ **producer absent.** Promotion is a Board act; publication, hydration and consumption are unallocated |

### 1c. Readiness verdict

> **PLANNABLE NOW. THE SEAM IS IMPLEMENTABLE NOW. THE SLICE IS NOT PRODUCTION-COMPLETE NOW.**
>
> **⚠️ CORRECTED 2026-08-03, after RED ran.** The earlier verdict said *“GREEN cannot complete.”* **That conflated two things.** **The seam's own tests CAN reach GREEN — the three values are supplied to the record by doubles, and the seam only reads the record.** **What cannot complete is the slice EXERCISED END TO END IN PRODUCTION, because the three producers are absent.**
>
> **This is the same distinction WP-4A recorded as *“registration ≠ delivery”*: a seam can be built and proven while nothing yet feeds it.** **All three prerequisites remain previously identified, and this is still not a stop condition.**

**What is in this slice:** the seam, the redrive, the store query, the retention fields, and the signature widening.
**What is upstream of it:** the three producers.

## 2. Implementation Scope

| # | Change | Layer | In scope |
|---|---|---|---|
| **S1** | `receiveRulingDecision()` accepts `Jurisdiction`; `AdjudicationProcessState` retains it and exposes `jurisdiction()` | Adjudication Application | ✅ |
| **S2** | `AdjudicationProcessState` retains an `EvidenceEnvelopeRef` and exposes `evidenceEnvelopeRef()` | Adjudication Application | ✅ |
| **S3** | `AdjudicationProcessState` retains a `ContestedOutcomeRef` (set at open or on consumption) and exposes it | Adjudication Application | ✅ |
| **S4** | **`AdjudicationProcessStore::concludedAwaitingIssuance(): array`** — the redrive query. The port has five methods and **none can find a concluded-but-unissued process** | Adjudication Application (port) + Infrastructure (impl) | ✅ |
| **S5** | A **seventh state** — *IssuanceRequested* or an `issuedAt` marker — so *concluded* and *issued* are distinguishable | Adjudication Application | ⚠️ **see §2a** |
| **S6** | The seam itself: on conclusion, request issuance in a **second transaction**; on redrive, re-request | Adjudication Application | ✅ |
| **S7** | Migration: the new retention columns + the redrive index | Adjudication Infrastructure | ✅ |
| **Out** | PM-6 confirmation · `AdjudicationFailureDeclared` · the intake port · the three producers | — | ⛔ |

### 2a. ⚠️ S5 is a stop-condition candidate, flagged not decided

**`AdjudicationProcessStatus`'s docblock states: *"The set is CLOSED… Adding a seventh case is therefore an ARCHITECTURAL ACT, not a coding one."***

**The redrive query must distinguish *concluded and issuance not yet confirmed* from *concluded and done*. Two shapes satisfy that:**

- **an `issuedAt`/`issuanceRequestedAt` timestamp on the record** — **no new state, so no architectural act**; or
- **a seventh status** — **explicitly an architectural act by the enum's own docblock.**

> **Engineering INTENDS to implement the timestamp form as the INITIAL IMPLEMENTATION STRATEGY**, because it satisfies the current governing model without introducing a new architectural state. **If RED demonstrates that this representation cannot satisfy the invariant, implementation stops and the issue returns to governance as materially new evidence.**
>
> **Phrased as an intention rather than a decision on purpose: the timestamp form has NOT been validated — RED has not run.** Saying *“engineering will implement”* would claim a validation that does not exist yet. *(Decision discipline step 3: safely deferrable — so deferred, not escalated.)*

## 3. RED Test Plan — four keystones

**File:** `tests/Feature/Contexts/Adjudication/ConcludeToIssuanceSeamTest.php` *(`tests/Feature/Contexts/*` is inside `GreenfieldCore`, so the merge gate covers it — unlike `tests/Feature/Audit/`.)*

| K | Assertion | Fails today because |
|---|---|---|
| **K1** | A concluded process **requests issuance**: exactly one determination exists for the challenge afterwards | nothing connects conclusion to issuance |
| **K2** | **Crash between the transactions** — conclusion committed, issuance not — then **redrive** ⇒ **exactly one** determination, never two | no redrive query, no marker |
| **K3** | **Redrive of an already-issued process is inert** — INV-B1's boundary guard refuses, the process is reconciled, **no second determination and no escalation** | the reconciliation path does not exist |
| **K4** | The command is built **only** from the concluded record — **no field is defaulted, invented or clamped in the seam** | the seam does not exist |

**Deliberately absent:** any assertion about *which* producer supplies the three inputs. **Test doubles supply them; the seam must not care.** **A test that pinned a producer would silently re-decide R-73/R-74/R-75.**

**RED is genuine when all four fail for the stated reasons and none passes by accident.**

### 3a. RED WRITTEN AND RUN (2026-08-03) — STOP at the RED boundary

**File:** `tests/Feature/Contexts/Adjudication/ConcludeToIssuanceSeamTest.php` · **Result: `4 failed (0 assertions)`.**

**All four fail at the same first absence:** *Interface `App\Contexts\Adjudication\Application\Port\RequestsDeterminationIssuance` not found.*

> ### ⚠️ Reported honestly: the failures are NOT YET DISCRIMINATING
>
> **The plan's own genuineness criterion is *“all four fail FOR THEIR STATED REASONS”*. They do not yet — the missing collaborator masks the rest.** **What is established is that the seam is absent, which is one reason, not four.**
>
> **Each keystone becomes discriminating only as the piece above it is supplied:** K1 once the port and the seam exist · **K2 once the redrive query and the marker exist — this is the keystone, and it is currently the least proven** · K3 once reconciliation exists · K4 once the three retention fields exist.
>
> **This is normal for a first RED against a wholly absent seam, and it would be wrong to report it as four independent failures.**

**Surface the tests name — the minimum the governing model implies, with producers deliberately unnamed:**

| Named | Purpose |
|---|---|
| `RequestsDeterminationIssuance` port + `request(IssueDeterminationCommand)` | the seam's collaborator, so the PM does not depend on the issuance service directly |
| `admitIssuanceContext(ChallengeRef, ContestedOutcomeRef, EvidenceEnvelopeRef)` | **the arrival point for the two non-authority values. It names WHERE they land, not WHO calls it** — R-74's and R-75's producers stay outside this slice |
| `receiveRulingDecision(…, Jurisdiction)` | R-73's value arrives with the decision |
| `redriveIssuance()` | the crash-recovery entry point |

**No production code has been written. GREEN is not attempted, because three producers are absent (§1b).**

### 3b. RED ITERATIONS (2026-08-03) — three runs, each revealing the next absence

**The Board directed the ratchet: introduce the shape, re-run, capture the NEXT discriminating failure, repeat.** Result:

| Run | Failure | What it was |
|---|---|---|
| **1** | *Interface `RequestsDeterminationIssuance` not found* | **the collaborator's absence**, masking everything |
| **2** | *“Tenant context not set”* — `TenantContext::require()` | ⚠️ **MY HARNESS, NOT THE SEAM.** Resolving the manager from the container dragged in the outbox adapter. **It would have masked every behavioural assertion.** Corrected by constructing the manager directly with in-memory doubles — **the WP-2 precedent for this class** — and relocating the file to `tests/Unit/Contexts/Adjudication/Process/` |
| **3** | *Call to undefined method `AdjudicationProcessManager::admitIssuanceContext()`* | ✅ **a genuine seam absence.** The arrival point for the two non-authority values does not exist |

**Run 2 is the one worth recording.** A container-resolved manager made the suite fail for an infrastructural reason that had nothing to do with the seam — **a RED that looks red for the wrong cause is worse than no RED, because it cannot be distinguished from progress.**

**Still not discriminating:** all four keystones share run 3's failure. **The ratchet continues: `admitIssuanceContext` → retention fields → the marker → `concludedAwaitingIssuance()` → the seam → reconciliation.** **K2 remains the least proven and is the roadmap's keystone.**

**One consequence of run 3 to carry into GREEN:** the manager's constructor still takes five parameters, so **the issuance spy passed as a sixth is silently ignored by PHP.** GREEN's first step must add the constructor parameter, or K1 could appear to pass while requesting nothing.

### 3c. Two implementation principles, recorded before GREEN

#### K2 is not one keystone among four — it is the behavioural invariant of the slice

> **`concluded-but-unissued → redrive → EXACTLY ONE determination`**
>
> **K1, K3 and K4 exist to support it.** K1 proves the request happens · K3 proves the redrive is safe to repeat · K4 proves the request carries the record's facts. **None of them is the invariant; K2 is.**

**Consequence for GREEN's order — strictly this, and not "all four keystones in parallel":**

| # | Step | Serves |
|---|---|---|
| 1 | `admitIssuanceContext()` | the record can hold what issuance needs |
| 2 | the three retention fields + accessors | same |
| 3 | the `issuanceRequestedAt` marker | **K2's precondition becomes expressible** |
| 4 | `concludedAwaitingIssuance()` | **K2 becomes askable** |
| 5 | redrive behaviour | **K2** |
| 6 | INV-B1 reconciliation | **K2's exactly-once half** |

#### ⛔ No producer logic in the seam

**The application service consumes domain facts that have already crossed bounded-context boundaries through approved integration contracts. It neither establishes those facts nor derives them.**

**Concretely: the seam must never ask where `Jurisdiction`, `EvidenceEnvelopeRef` or `ContestedOutcomeRef` came from, and must never derive, reconstruct, default or look them up.**

**Those ownership decisions belong to other bounded contexts and are already settled (R-73 · R-74 · R-75).** **If the seam begins reconstructing them it violates the separation the RED suite was written to preserve — and it would do so invisibly, because the tests supply the values and would still pass.**

> **That is the specific danger: this is a violation the current tests CANNOT catch.** **It is prevented by discipline, not by the suite** — which is why it is recorded here rather than left implicit.

### 3d. Cost finding from reading the code — recorded before paying it

> **⛔ WITHDRAWN — the cost was measured and it is much lower than I claimed.** I wrote *“around eight call sites”* from counting **transition methods**. **`grep -n "new self("` returns THREE**: `open()`, `reconstitute()`, and a **private `with(...)` funnel** every transition routes through.
>
> **Two fields therefore touch four places — the constructor, `open()`, `reconstitute()` and `with()` — not eight.** **The class is better designed than I credited: the funnel is exactly what makes field addition cheap, and I inferred a cost instead of measuring one.**

**No refactor of that pattern is proposed, and none is needed.**

### 3e. GREEN STEP 1 IMPLEMENTED — BEHAVIOURAL GREEN REMAINS INCOMPLETE (2026-08-03)

**Precise wording, corrected: *“step 1 done”* would read as progress toward passing. K1–K4 all still fail.** **What is done is the step; what is not done is GREEN.**

**Written:** the constructor parameter · `AdjudicationProcessManager::admitIssuanceContext()` · `AdjudicationProcessState::retainIssuanceContext()` with two retention fields and accessors · **`CoordinatorIssuanceRequest`** (the port's adapter) · its binding in `AdjudicationServiceProvider`.

**⚠️ The adapter arrived earlier than the plan sequenced it, and the container forced it:** adding a required parameter broke boot, because **WP-4A's registration resolves the manager in `AdjudicationServiceProvider::boot()`**. *(A consequence of R-69's own delivery — worth noting, not a defect.)*

**⚠️ One pre-existing test file was modified:** `AdjudicationProcessManagerTest` now supplies an inert issuance stand-in, in the same form WP-6's horizon collaborators took. **Disclosed here because acceptance must see it — a required constructor parameter cannot be added without it.**

#### The failures are now discriminating — and all four still fail

| K | Failure | Kind |
|---|---|---|
| **K1** | *“Failed asserting that actual size 0 matches expected size 1”* | ✅ **behavioural** — concluding does not yet request issuance |
| **K2** | `redriveIssuance()` undefined | ✅ the redrive entry point is absent |
| **K3** | `redriveIssuance()` undefined | ✅ **same cause as K2, correctly** — both need that one entry point |
| **K4** | consequential on K1 — nothing was requested, so there is no command to inspect | ✅ |

**Three distinct causes, and one assertion now executes (0 → 1).** **That is the milestone: the suite is testing the seam rather than reporting missing symbols.**

**Adjudication suites after the change: 72 passed · 4 failed (only the new keystones) · 23 risky (pre-existing).**

#### Next, unchanged in order

**The `issuanceRequestedAt` marker → `concludedAwaitingIssuance()` → redrive → INV-B1 reconciliation.** **K2 remains the next architectural CAPABILITY required to satisfy the issuance invariant — it is not itself the invariant.**

> **⛔ Corrected: I had written *“K2 is the invariant.”* It is not.** **The invariants are *exactly one determination per challenge* (INV-B1) and *redrive is idempotent*.** **`redriveIssuance()` is a mechanism that serves them.** **Conflating the test with the rule it protects is the same category error as conflating a ruling with the evidence for it** — and it would let a passing K2 be mistaken for a proven invariant.

#### The structural safeguard, sequenced deliberately

**An architecture test asserting the seam has no producer dependency is the right complement to the behavioural keystones. Deptrac already forbids the CROSS-CONTEXT half** (`AdjudicationApplication: [AdjudicationDomain, Shared]`), **so what an ADV would add is the INTRA-context constraint: no repository lookup, no aggregate load, no derivation inside the seam.** **It becomes assertable once the seam's body exists — writing it now would constrain code that has not been written.**

## 4. GREEN Implementation Sequence

**Application first, then Infrastructure — the house order (WP-2 precedent).**

1. **S1–S3** retention fields + accessors, `receiveRulingDecision()` widened → **K4 progresses**
2. **S5** the `issuanceRequestedAt` marker → K2's precondition becomes expressible
3. **S4** `concludedAwaitingIssuance()` on the port, then the Eloquent implementation → **K2 progresses**
4. **S6** the seam: `TransactionManager::transactional()` for the issuance transaction, **separate from the conclusion write** → **K1 green**
5. **Reconciliation**: catch INV-B1's refusal → **if this process requested earlier, ack; if another writer issued, dead-letter + escalate** (EPIC-004K §12, unchanged) → **K3 green**
6. **S7** migration + index
7. Full-suite run, then the gate

**Constraint: `EvidencePreservationWindow`-style purity is not at stake here, but AP-2 is — the seam defines, defaults and clamps nothing.**

## 5. Verification Checklist

- [ ] RED: 4 failures, each for its stated reason
- [ ] GREEN: 4/4, and **no pre-existing test modified**
- [ ] `composer merge-gate` **PASS** — Architecture fitness · **Deptrac 0** · greenfield PHPStan **no errors** · widened regression
- [ ] **`CorrelationIdMintingTest` green with the allowlist unchanged** — the seam mints nothing
- [ ] **`DurationPolicyOwnershipTest` green** — AP-2 untouched
- [ ] Risky-notice delta recorded, whatever it is (**ENG-012**)
- [ ] Test counts before/after stated explicitly

## 6. Acceptance Evidence Checklist

- [ ] Changes made · **changes deliberately not made**
- [ ] RED genuineness argued per test
- [ ] Gate output quoted, not summarised
- [ ] Invariants: **INV-B1 · AP-1 · AP-2 · TP-1 · ADR-T16 · ADR-T1 (one aggregate per transaction — the seam writes the PM record and the Determination in *separate* transactions)**
- [ ] **Governing-model conformance: `jurisdiction` from the authority · `evidenceEnvelopeRef` retained, not derived · `contestedOutcome` from Contestation** — each read from the record, none synthesised
- [ ] Evidence supporting triple qualification *(engineering produces it; the qualification is the acceptance package's — R-71)*
- [ ] Developer guide + index row
- [ ] **STOP for ARB slice acceptance**

## 7. Definition of Done

**RED → GREEN → `composer merge-gate` PASS → evidence supporting triple qualification → developer guide → acceptance evidence → STOP.** **Engineering does not accept its own work (R-34).**

## 8. WP-4C and WP-4D — deliberately no plan produced

**R-72 declined to authorize them and stated each is to be authorized *"from implementation evidence rather than roadmap intent, after its predecessor is accepted."***

> **Producing implementation plans for them now would be planning from roadmap intent before predecessor acceptance — precisely what R-72 declined.** **The commission asks for artefacts for all three; R-72 permits them for one. R-72 governs.**

**What is already recorded and needs no new artefact:** both wait on the **authority-decision intake**; WP-4C additionally needs `AdjudicationFailureDeclared` with its counterpart, hydrator and catalog entry. **Their plans become producible when WP-4B is accepted.**

---

**Traceability:** **R-72** (authorization) · **R-73 · R-74 · R-75** (governing model) · **R-76** (scope) · **R-79** (WP-8 deferred) · **EPIC-004K §11 · §12** · **ADR-T1 · ADR-T16 · INV-B1 · AP-1 · AP-2 · TP-1** · roadmap §WP-4 keystones · `engineering/verification/reports/2026-08-03-wp4b-delivery-commission.md` · `…-wp4b-implementation-dependency-verification.md`. **Delivery artefact — no governance produced, no architecture proposed, no code written yet.**
