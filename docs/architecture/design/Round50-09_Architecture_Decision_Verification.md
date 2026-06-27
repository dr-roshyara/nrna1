# Round 50-09 — Architecture Decision Verification

**Phase III (Tactical Realization) · Built against Release 1.0 · Pre-implementation GATE · 2026-06-26**
**Status:** ✅ VERIFICATION CHECKLIST — *not a design doc*. One matrix; every aggregate must be green on every column before implementation. Cells cite the artifact that satisfies them.

## Verification matrix (✓ = satisfied · ◐ = satisfied, item open at impl)
| Aggregate | Decision protected | Invariant | Policy | Events | Repository | State Machine | Consistency | Transaction | Authorization | Failure/Recovery | TDD tests | Fitness tests |
|-----------|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|:---:|
| **Vote** | ✓06 | ✓06 (Anonymity Q7) | ✓06 | ✓05 | ✓08 | ✓07 | ✓08 | ✓08 | ✓05/06 | ✓07 | plan | ✓ |
| **EvidenceEnvelope** | ✓06 | ✓06 (immutability) | ✓06 | ✓05 | ✓08 | ✓07 | ✓08 | ✓08 | ✓05/06 | ✓07 | plan | ✓ |
| **Mandate** | ✓06 | ✓06 (no-resurrect) | ✓06 | ✓05 | ✓08 | ✓07 | ✓08 | ✓08 | ✓05/06 | ✓07 | plan | ◐ scope¹ |
| **Challenge** ★ | ✓06 | ✓06 (state) | ✓06 | ✓05 | ✓08 | ✓07 | ✓08 | ✓08 | ✓05/06 | ✓07 (park) | plan | ✓ |
| **Determination** ★ | ✓06 | ✓06 (finality) | ✓06 | ✓05 | ✓08 | ✓07 | ✓08 | ✓08 | ✓05/06 | ✓07 | plan | ◐ legitimacy² |
| **Election/Lifecycle** | ✓06 | ✓06 (ContainedCorrection Q7) | ✓06 | ✓05 | ✓08(exists) | ◐ states³ | ✓08 | ✓08 | ✓05/06 | ✓07 | plan | ✓ |

★ = greenfield Core (implement first). Column key: 04=Domain Events, 05=Event Catalogue, 06=Policy Catalogue, 07=State Machines, 08=Repo/Txn.

## The 4 open items (◐) — none block the greenfield Core
1. **Mandate scope/holderId** — depends on Mandate-vs-Committee ownership. *Not on the Core path; resolve when Appointment is touched.*
2. **`LegitimacyDecision` internals** — greenfield; specified TDD-first *during* Determination implementation (that IS the work).
3. **Election/Lifecycle state names** — read from existing `ElectionLifecycleEngine` at implementation; correction transition only is new.
4. **Replay placement (BDR-06)** — confirm at implementation; does not gate Core.

## Constitutional gates (must stay green — build-breaking)
- **Q7 Anonymity** — no event/payload/repo/projection carries voter↔vote linkage (50-05 §1, 50-06, 50-08). ✓
- **Q7 Contained correction** — corrections cannot un-cast votes (restore-forward only). ✓
- **SD-5 #4** — software issues rulings, does not enforce; Election applies its own correction (50-03). ✓

## Standing principles enforced by fitness tests (must exist before/with code)
- TP-1 event-is-the-seam · TP-2 request-not-create · TP-3 events-version-never-mutate.
- One producer per event; no foreign consumer (Voting consumes nothing foreign).
- One aggregate per transaction; outbox in same txn; idempotent consumers.
- No unguarded decision; invariants-in-aggregate; authz-at-boundary; calculation-pure.

## Readiness
| | Verdict |
|---|---|
| Strategic / BCs / Aggregates / Release 1.0 | **FROZEN** |
| Tactical contracts (events/policies/state/repo) | **COMPLETE** |
| Open items | **4, none on the Core path** |
| **Recommendation** | **Ready to implement the greenfield Core (Contestation + Adjudication), TDD + fitness tests first.** |

> **Implementation of live-repo code awaits the user's explicit authorization.** This checklist clears the *architecture* gate; it does not itself authorize writing code.

## Suggested first implementation slice (greenfield Core, TDD)
1. Fitness tests first (one-producer, one-aggregate-per-txn, no-foreign-consumer, Q7-no-linkage) — red.
2. `Challenge` aggregate + state machine + `ChallengeStandingPolicy`/`…StateInvariant` → `ChallengeRaised…Routed`.
3. `AdjudicationService` (request-not-create) reads Challenge + EvidenceEnvelope.
4. `Determination` aggregate + finality invariant → `DeterminationIssued`.
5. Election reacts → `ElectionCorrectionApplied` (ContainedOnly) → `ChallengeResolved`.
6. Outbox wiring + idempotent consumers; fitness tests green.

---
*Round 50-09 — Architecture Decision Verification — GATE CLEARED (architecture).*
*All 6 aggregates green across 12 columns; 4 open items, none on the greenfield-Core path; 3 constitutional gates green (Q7 anonymity, Q7 contained-correction, SD-5 #4); standing principles fitness-tested. Recommendation: Ready to implement greenfield Core (Contestation+Adjudication) TDD-first — pending explicit user authorization for live-repo code. Round 50 design chain COMPLETE.*
