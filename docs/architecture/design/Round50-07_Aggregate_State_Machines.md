# Round 50-07 — Aggregate State Machines

**Phase III (Tactical Realization) · Built against Release 1.0 · Design only · 2026-06-26**
**Status:** 🔁 STATE MACHINES — per-aggregate states, **allowed/forbidden transitions, terminal states, recovery, timeouts**. Precedes repositories (state machines *define* state; repos *store* it). Concise. Each transition emits the 50-05 event in brackets.

## Conventions
- **▣ terminal** (no exit). **↦ allowed**. **⊘ forbidden** (fitness-tested). Timeout = system-driven transition. Recovery = how a crash mid-transition heals (outbox + idempotency, 50-04 §5).
- Transitions are guarded by 50-06 policies; the event is emitted **after** the state commit, via the outbox (same txn).

## Vote
```
Draft ↦ Cast [VoteAccepted] ↦ Verified ▣
Draft ↦ Abandoned ▣        (timeout: voting window closes)
```
- ⊘ Verified→Cast, Cast→Draft, Abandoned→Cast (no re-open, no re-vote).
- **Timeout:** window close → un-Cast Draft → **Abandoned** (no event consumed downstream).
- **Recovery:** crash after Cast-commit before publish → outbox redelivers `VoteAccepted`; idempotent consumers dedupe.

## EvidenceEnvelope
```
Open ↦ Frozen ▣ [EvidenceRecorded]
```
- ⊘ Frozen→Open, any mutation of Frozen (immutability invariant; Q-evidence).
- **No timeout** (freeze is explicit). **Recovery:** freeze is idempotent on `envelopeHash`; redelivery safe.

## Mandate
```
Active ↦ Revoked ▣ [MandateRevoked]
Active ↦ Expired ▣        (timeout: term end)
(grant: ∅ ↦ Active [MandateGranted])
```
- ⊘ Revoked→Active, Expired→Active (no resurrection — `DelegationLifecyclePolicy`, exists).
- **Timeout:** term end → **Expired** (distinct from Revoked: cause differs).
- **Recovery:** version-checked (optimistic concurrency); redelivery idempotent on `mandateId`.

## Challenge  *(greenfield Core)*
```
Raised ↦ Admitted [ChallengeAdmitted] ↦ Routed [ChallengeRouted] ↦ Resolved ▣ [ChallengeResolved]
Raised ↦ Dismissed ▣ [ChallengeDismissed]
Raised ↦ Lapsed ▣         (timeout: raise/decision window expires)
```
- ⊘ Resolved→*, Dismissed→*, skip Admitted→Resolved (must route first), Routed→Raised.
- **Timeout:** undecided past window → **Lapsed** (terminal; logged; not the same as Dismissed-on-merits).
- **Recovery:** `Resolved` waits on `DeterminationIssued` (causal); if Determination not yet delivered, Challenge **parks in Routed** (not failed) until the event arrives.

## Determination  *(greenfield Core)*
```
Draft ↦ Issued [DeterminationIssued] ↦ Final ▣
```
- ⊘ Final→Issued, Issued→Draft, re-issue for same `challengeId` (issued once — finality invariant).
- **No timeout** (issuance is an authority act, not time-driven). **Recovery:** outbox redelivers `DeterminationIssued`; Election consumer idempotent on `determinationId`.

## Election / Lifecycle  *(states per existing `ElectionLifecycleEngine` — not redefined here)*
```
…(existing lifecycle)… ↦ CorrectionApplied [ElectionCorrectionApplied]
```
- Correction is a **reaction** to `DeterminationIssued` (50-03 Option A), applied to Election's own state; bounded by anonymity → **ContainedOnly** when votes cannot be un-cast (restore-forward).
- ⊘ Adjudication mutating Election directly (SD-5 #4). **Recovery:** if Election down when `DeterminationIssued` arrives → outbox holds → applied on recovery (no saga).
- **Existing lifecycle states are authoritative**; this round adds only the correction transition. Confirm exact state names against the engine at implementation.

## Cross-aggregate transition flow (correction loop)
```
Challenge:Routed ──[DeterminationIssued]──► Determination:Final
        ▲                                          │
        └────[ChallengeResolved]◄── Election:CorrectionApplied ◄──[reacts]
```
Each hop atomic; linked by causal events; no cross-aggregate transaction (ADQC Q10).

## Fitness tests
- Every aggregate exposes its allowed transition set; forbidden transitions throw (not silently no-op).
- Terminal states reject all transitions.
- Each timeout has an explicit terminal target (no aggregate stuck indefinitely).
- Parked (awaiting-causal-event) states are recoverable, never dead-locked.

## Open
- Exact Election/Lifecycle state names ← existing engine (confirm at implementation).
- Challenge timeout windows (raise window, decision window) = governance config, not hardcoded.

## Next
```
50-07 (this) ✓ → 50-08 Repository & Transaction Design → 50-09 Verification checklist → Implementation
```

---
*Round 50-07 — Aggregate State Machines — ISSUED (design).*
*6 aggregates: states, allowed/forbidden transitions, terminal states, timeouts (Vote→Abandoned, Mandate→Expired, Challenge→Lapsed), recovery (outbox+idempotency; parked-not-failed for causal waits). Correction loop transition flow. Election states deferred to existing engine. Fitness tests derived. Next: 50-08 Repository & Transaction. No code.*
