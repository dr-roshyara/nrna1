# Round 50-06 — Policy Catalogue

**Phase III (Tactical Realization) · Built against Release 1.0 · Design only · 2026-06-26**
**Status:** 🛡️ POLICY CATALOGUE — classifies every policy and asserts **every aggregate decision is protected by a policy**. Policies encode business decisions (review: more important than repositories). Concise.

## Policy taxonomy (5 kinds)
| Kind | Answers | Returns | Lives in |
|------|---------|---------|----------|
| **Invariant** | "is this state *always* legal?" | bool / throw | Aggregate (enforced on every mutation) |
| **Decision** | "given context, what is the *outcome*?" | a chosen result / event | Domain Service or Aggregate method |
| **Authorization** | "*may this actor* do this?" | allow/deny | Application boundary (pre-command) |
| **Validation** | "is this *input* well-formed/admissible?" | valid/errors | Command / VO construction |
| **Calculation** | "*derive* a value (pure)" | value | Domain Service / VO (pure, replay-safe) |

> Order at runtime: **Authorization → Validation → Decision → Invariant**. Calculation feeds Decision. Invariant is the last gate before persist.

## Catalogue (by aggregate — every decision has a policy)
| Aggregate | Decision (protected) | Policy | Kind |
|-----------|----------------------|--------|------|
| **Vote** | one cast per eligible code; ballot well-formed | `BallotAdmissibilityPolicy` | Validation |
| | vote is anonymous (no user link) | `AnonymityInvariant` (Q7) | **Invariant** |
| | may this code cast now? | `VotingEligibilityPolicy` | Authorization |
| | accept → `VoteAccepted` | `VoteAcceptanceDecision` | Decision |
| **EvidenceEnvelope** | envelope frozen = immutable | `EvidenceImmutabilityInvariant` | **Invariant** |
| | hash = deterministic over canonical content | `EnvelopeHashCalculation` | Calculation |
| | recordable now? (election state) | `EvidenceRecordingPolicy` | Authorization |
| **Mandate** | ACTIVE→REVOKED only (no resurrect) | `DelegationLifecyclePolicy` *(exists)* | **Invariant** |
| | who may grant/revoke | `MandateAuthorityPolicy` | Authorization |
| | grant valid for scope+term | `MandateScopeValidation` | Validation |
| **Challenge** | standing to raise; within window | `ChallengeStandingPolicy` | Authorization |
| | submitted content admissible | `ChallengeContentValidation` | Validation |
| | admit vs dismiss | `ChallengeAdmissibilityDecision` | Decision |
| | route to jurisdiction | `ChallengeRoutingDecision` | Decision |
| | transition legality | `ChallengeStateInvariant` | **Invariant** |
| **Determination** | issuing authority has jurisdiction | `DeterminationAuthorityPolicy` | Authorization |
| | legitimacy verdict over evidence | `LegitimacyDecision` | Decision |
| | issued once, then final (Draft→Issued→Final) | `DeterminationFinalityInvariant` | **Invariant** |
| **Election/Lifecycle** | which correctionType applies | `CorrectionTypeDecision` | Decision |
| | correction bounded by anonymity (no un-cast) | `ContainedCorrectionInvariant` (Q7) | **Invariant** |
| | lifecycle transition legality | `ElectionLifecyclePolicy` *(engine exists)* | **Invariant** |

## Rules
- **Every aggregate decision row maps to ≥1 policy** — gap = a fitness-test failure (no unguarded decision).
- **Invariants live in the aggregate** (never in a service); **Authorization at the application boundary** (pre-command, never inside the aggregate); **Calculation is pure** (replay-safe, no I/O — required for determinism/Replay).
- **Decision policies may emit events** (50-05) but **do not enforce** across boundaries (SD-5 #4; request-not-create, TP-2).
- **Two Q7 invariants are constitutional** (`AnonymityInvariant`, `ContainedCorrectionInvariant`) — gating; violation = build-breaking.

## Fitness tests
- Each catalogued decision resolves to a policy class (no unprotected decision).
- No Authorization logic inside an aggregate; no Invariant outside its aggregate.
- Calculation policies are pure (no facade/Eloquent/clock) — replay determinism.
- The two Q7 invariants exist and are exercised by tests.

## Open
- `LegitimacyDecision` internals (greenfield Core) — specified at implementation, TDD-first.
- Mandate policies depend on **Mandate-vs-Committee** ownership (still open).

## Next
```
50-06 (this) ✓ → 50-07 Aggregate State Machines → 50-08 Repository & Transaction → 50-09 Verification checklist → Implementation
```

---
*Round 50-06 — Policy Catalogue — ISSUED (design).*
*5 kinds (Invariant/Decision/Authorization/Validation/Calculation); runtime order Authz→Valid→Decision→Invariant; every aggregate decision mapped to a protecting policy (no unguarded decision); invariants-in-aggregate / authz-at-boundary / calculation-pure; 2 constitutional Q7 invariants (Anonymity, ContainedCorrection) gating. Fitness tests derived. Next: 50-07 State Machines. No code.*
