# Round 50-03 — Correction Terminus Decision

**Phase III · Built against Release 1.0 · Design only · 2026-06-26**
**Status:** 🎯 DECISION — resolves where the correction loop terminates after `DeterminationIssued`. Blocks Domain Event Design (50-04). Concise by design.

## Question
After a binding ruling is issued, what happens?
```
DeterminationIssued → ?
```

## Options
| Opt | Flow | Trade-off |
|-----|------|-----------|
| A | `DeterminationIssued` → **Election/Lifecycle reacts & transitions its own state** | autonomy preserved; Adjudication doesn't enforce ✓ |
| B | `DeterminationIssued` → `ElectionCorrected` (Adjudication corrects directly) | Adjudication owns election state → coupling + violates SD-5 #4 ✗ |
| C | `DeterminationIssued` → `CorrectionWorkflowStarted` (process manager) | viable for multi-step; adds a coordinator — defer unless needed |

## Decision — **Option A** (request / react)
- **Adjudication issues the ruling; it does NOT enforce.** `DeterminationIssued` is a domain event.
- **The Election/Lifecycle context subscribes** and applies the correction **to its own state** (it owns election state) → emits `ElectionCorrectionApplied` (or `LifecycleTransitioned`).
- **Terminus = the Election/Lifecycle context.** A Correction **coordinator** (application service / process manager) mediates *only if* correction proves multi-step (Option C as a later refinement, not now).
- **Correction is bounded by Anonymity** → may be **Contained-Only** (cannot un-cast votes; restore-forward only).

## Rationale (3 lines)
1. **Aggregate autonomy** — Determination must not mutate the Election aggregate (no cross-aggregate txn; ADQC Q10).
2. **SD-5 #4 — software does not own enforcement** — the ruling is issued; *applying* it is the Election context's own decision (and ultimately consent-based acceptance).
3. **Separation** — Adjudication owns *determinations*, Lifecycle/Election owns *state*; the event is the seam.

## Companion decision — Challenge ⇒ Determination is **REQUEST, not CREATE**
> **Aggregates do not create other aggregates.** `Challenge` **requests** adjudication; an **Application Service (`AdjudicationService` / process manager) coordinates**: reads `Challenge` + `EvidenceEnvelope`, invokes `IssueDetermination`. Standing rule for all aggregates.

## Resulting correction loop (the event contract 50-04 will formalize)
```
RaiseChallenge ─► ChallengeRaised
   ─► [AdjudicationService] reads Challenge + EvidenceEnvelope
   ─► IssueDetermination ─► DeterminationIssued
   ─► [Election/Lifecycle subscribes] applies correction to its OWN state
   ─► ElectionCorrectionApplied  (bounded by Anonymity → possibly Contained-Only)
```
No cross-aggregate transaction; each step atomic, linked by events.

## Consequences
- 50-04 Domain Event Design defines: `ChallengeRaised`, `DeterminationIssued`, **`ElectionCorrectionApplied`** (the terminus event), not `ElectionCorrected`-by-Adjudication.
- Adds a standing tactical rule: **request-not-create** (application-service coordination).
- This is a **software-boundary/event-flow** decision — certified governance concepts unchanged.

## Adopted for the next docs *(per review; applied there, not re-done here)*
- **Command/event taxonomy:** commands = imperative verb (`CastVote`); events = past tense (`VoteAccepted`).
- **Aggregate state machines** (Mandate ACTIVE→REVOKED; Challenge Raised→Admitted→Routed→Resolved; Determination Draft→Issued→Final; Vote Draft→Cast→Verified) → 50-04/state tables.
- **"Who references this aggregate?"** added to the review questions.
- **Repository ownership frozen now:** `VoteRepository` owns Vote only (never Result); `EvidenceRepository` owns Evidence only (never Replay); etc.
- **Policy classification** (Invariant / Decision / Authorization / Calculation / Validation) → **50-05 Policy Catalogue** (new roadmap step).

## Roadmap (revised)
```
50-02 Review ✓ → 50-03 Correction Terminus (this) ✓ → 50-04 Domain Event Design
   → 50-05 Policy Catalogue → 50-06 Repository & Transaction Design → Implementation (greenfield Core)
```

---
*Round 50-03 — Correction Terminus — DECIDED (Option A: Election/Lifecycle reacts; Adjudication issues, doesn't enforce; terminus event = ElectionCorrectionApplied, Anonymity-bounded). Challenge REQUESTS Determination (app service coordinates; aggregates don't create aggregates). Unblocks 50-04. + Policy Catalogue added (50-05). Repo ownership frozen. No code.*
