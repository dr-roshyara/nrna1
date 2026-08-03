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

> **PLANNABLE NOW. NOT GREEN-ABLE NOW.**
>
> **RED can be written and will fail honestly. GREEN cannot complete, because three of ten command inputs have no production producer.** **All three prerequisites were previously identified — no new governance question arises here, and this is not a stop condition.**

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
