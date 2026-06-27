# Round 50-02 — Aggregate Review

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** III (Strategic→Tactical Transition) · **Built against:** Architecture Release 1.0 (BDR v1.1)
**Status:** 🔬 AGGREGATE REVIEW — validates each candidate aggregate (`Round50-01`) against a fixed **13-item checklist**, makes **Command→Policy→State-change→Event** lifecycles explicit, adds **Aggregate Collaboration**, and resolves modeling questions. **Design only — no code.** Repositories/event-contracts emerge *from* this review, not before.
**Date:** 2026-06-26

> **Review checklist (every aggregate):** Decision · Invariant · Transaction · Lifecycle · Commands · Policies · Domain Events · External Dependencies · Split Test · Merge Test · Repository (boundary only) · Consistency · Testability (→ fitness test).
> **Lifecycle template (made explicit per review):** `Command → Policy → State change → Event`.

---

## Vote
- **Decision:** is this vote valid & anonymous? **Invariant:** no voter↔vote linkage · uniqueness (`VoteHash`) · integrity (`DataChecksum`) · verifiability (`VoteReceipt`) · selection rules per post. **Transaction:** one Vote atomic (Results out of txn). **Lifecycle:** Drafted→Cast(→Verified). **Consistency:** immediate (vote); eventual (Results).
- **Command→Policy→Event:** `CastVote → (SelectionRulesPolicy + AnonymityPolicy) → Vote cast → VoteAccepted`.
- **External deps:** Authorization decision (read), Lifecycle state (read). **Split:** Ballot stays within Vote. **Merge:** no. **Repository:** anonymous `VoteRepository` (no user link). **Testability:** *fitness test — no schema/query reconstructs voter↔vote (Q7).*

## EvidenceEnvelope
- **Decision:** which evidence participated, immutably. **Invariant:** frozen-at-creation · hash integrity · hashed voter id. **Transaction:** write-once. **Lifecycle:** Created→(immutable). **Consistency:** immediate.
- **Command→Policy→Event:** `RecordEvidence → (Integrity/HashPolicy) → Envelope sealed → EvidenceRecorded`.
- **External deps:** evidence inputs (Voting/Trust). **Split:** no. **Merge:** no. **Evidence Items:** **VOs initially — Review verdict: keep as VOs for now; revisit if a type (e.g. Challenge/Device evidence) evolves independently → Entity.** **Repository:** append-only (write-once). **Testability:** *fitness test — envelope immutable; identical evidence → identical hash.*
- **Replay:** *design hypothesis — Application Service `VerifyReplay` over Evidence (not an aggregate); confirm at implementation (BDR-06).*

## Mandate
- **Decision:** who holds delegated authority, active? **Invariant:** valid transitions only (`ACTIVE→REVOKED`; `REVOKED` terminal). **Transaction:** one transition atomic. **Lifecycle:** Granted(ACTIVE)→Revoked. **Consistency:** immediate.
- **Command→Policy→Event:** `GrantMandate → (DelegationPolicy) → Mandate active → MandateGranted`; `RevokeMandate → (DelegationLifecyclePolicy) → Mandate revoked → MandateRevoked`.
- **External deps:** appointment source. **Split:** no. **⚠ Merge Test — OPEN:** does `Mandate` own Committee Membership, or is it `Committee → Mandate` / `Mandate → CommitteeMembership`? **NOT finalized** — resolve before Mandate implementation. **Repository:** TBD post-resolution. **Testability:** *fitness test — no `REVOKED→ACTIVE` transition.*

## Determination  *(greenfield)*
- **Decision:** is a contested outcome constitutionally valid → binding finality. **Invariant:** **final once issued** (S-2) · reasoned · evidence-traced · **issued under valid authority** (correction below). **Transaction:** one Determination atomic. **Lifecycle:** Drafted→**Issued(final)**. **Consistency:** immediate.
- **Command→Policy→Event:** `IssueDetermination → (FinalityPolicy + DecisionalIndependencePolicy + **AuthorityPolicy**) → Determination finalized → DeterminationIssued`.
- **Correction (per review):** a Determination also protects **authority** — add VOs **`IssuedByAuthority` · `DecisionAuthority` · `Jurisdiction`** (without valid authority a Determination cannot exist).
- **External deps:** `Challenge` (Contestation), `EvidenceEnvelope` (Evidence). **Split:** no (atomic; appeal is Contestation's concern). **Merge:** no (distinct from committee `ConstitutionalArbitrationKernel`). **Repository:** immutable-once-final. **Testability:** *fitness test — a final Determination is never mutated; no Determination without authority.*

## Challenge  *(greenfield)*
- **Decision:** does a standing-holder raise a valid challenge & route it? **Invariant:** standing required (S-5) · valid lifecycle · time-bounded (appeal window) · **owns its submitted content** (correction below). **Transaction:** one transition atomic. **Lifecycle:** Raised→Admitted/Dismissed→Routed→Resolved. **Consistency:** immediate.
- **Command→Policy→Event:** `RaiseChallenge → (StandingPolicy + WindowPolicy) → Challenge created → ChallengeRaised`; `RouteChallenge → ChallengeRouted`.
- **Correction (per review):** Challenge **owns submitted arguments / evidence / claims** *before* adjudication (else it's a mere workflow object) — VOs/entities: `SubmittedArgument`, `SubmittedEvidenceRef`, `Claim`.
- **External deps:** standing (Appointment/Authorization), the contested outcome. **Split:** no. **Merge:** no (distinct from Determination — challenge ≠ ruling). **Repository:** `ChallengeRepository`. **Testability:** *fitness test — no Challenge without standing; none accepted outside the window.*

---

## Aggregate Collaboration (sequences — remove ambiguity before implementation)

**Voting → Evidence → Results:**
```
CastVote ─► VoteAccepted ─► RecordEvidence ─► EvidenceRecorded ─► (async) Result projection
```
**Correction loop (greenfield Core):**
```
RaiseChallenge ─► ChallengeRaised ─► RouteChallenge ─► ChallengeRouted
   ─► IssueDetermination (consumes Challenge + EvidenceEnvelope) ─► DeterminationIssued
   ─► (correction terminus?) LifecycleTransition / re-run
```
**No cross-aggregate transaction** — aggregates collaborate via **domain events** (eventual consistency). Satisfies ADQC Q10.

## Resolved vs still-open
- **Resolved:** Vote Results = async projection (consistency confirmed) · EvidenceItems = VOs for now · Determination must carry Authority VOs · Challenge owns submitted content · Command→Policy→Event made explicit.
- **Still OPEN (flag before implementing the relevant aggregate):** (1) **Mandate vs Committee membership** ownership; (2) **Correction terminus** — does "re-run/invalidate" belong to **Adjudication** (Determination triggers it) or **Lifecycle**? (3) **Replay** placement (impl confirmation, BDR-06).

## Deferred (emerge from confirmed aggregates — not before)
**Repository design** and **Domain Event contracts** are designed in the *next* steps, now that aggregates + commands + events are validated.

## Status & next
**Design only — no code.** Aggregates validated (5), with 3 open modeling questions scoped to their aggregates. **Next:** Domain Event Design → Repository & Transaction Design → **Implementation (greenfield Core: Challenge + Determination first)** → Architecture Fitness Tests. *Code/migration awaits authorization.*

---
*Round 50-02 — Aggregate Review — ISSUED (design only).*
*13-item checklist applied to 5 aggregates; Command→Policy→State→Event lifecycles explicit; Aggregate Collaboration sequences (no cross-aggregate txn). Corrections: Determination +Authority VOs; Challenge +submitted-content; Vote conceptual via Ballot; EvidenceItems VOs-for-now. OPEN: Mandate-vs-Committee, Correction-terminus, Replay-placement. Repos/event-contracts = next steps. No code.*
