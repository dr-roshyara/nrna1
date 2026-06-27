# Round 27G-ARB — Governance Evidence Replay Challenge Review

**Date:** 2026-06-08

**Phase:** Tactical DDD — Aggregate Challenge Review (Pre-Acceptance)

**Status:** Complete — Ready for ARB Decision

---

## 1. Purpose

Challenge the conclusion that ReplaySession is an aggregate. Apply the same adversarial review discipline used in previous challenge reviews.

---

## 2. Challenge Q7: Aggregate or Process Manager?

### Argument for Aggregate

**Evidence:**
- ReplaySession has a state machine lifecycle (sealed → replayed → certified/diverged)
- State transitions are enforced (guards prevent illegal transitions in ReplaySession.php line 64-68)
- Session owns the decision: "Did replay outcome match expected outcome?"
- Session state + assertion + certification must be consistent within the same lifecycle
- Code comments describe it as "Root entity of the ReplayAggregate"

### Argument for Process Manager / Domain Service

**Evidence:**
- ReplaySession primarily coordinates existing components: EvidenceEnvelope + ReplayAssertion + ReplayCertification
- The core "verification" work (comparing expected vs actual outcomes) could be a stateless computation
- Session state (sealed, replayed, certified, diverged) is process tracking, not business truth
- The session does not own the evidence or the certification — it owns the *process* of comparing them
- No observed business consequence follows from session state (D35 — legitimacy consequences unknown)

### Assessment

**Process Manager classification is WEAKER than aggregate classification** because:

1. ReplaySession enforces *invariants*, not just *process steps*:
   - "A session can be used for exactly one certification cycle" (ReplaySession.php line 15-16)
   - "Cannot record assertion in state 'sealed'. Expected 'replayed'." (line 66-67)
   - These are business invariants (a session cannot be reused), not process coordination logic.

2. The session state is *business truth*, not just orchestration state:
   - A certified session means "evidence was verified and matched expected outcome"
   - A diverged session means "constitutional integrity violation detected"
   - These outcomes have business meaning beyond process completion.

3. A Process Manager would not enforce state-based invariants — it would delegate to stateless services and track workflow progress. ReplaySession's state guards are invariant enforcement, not workflow tracking.

**Verdict: Aggregate is stronger than Process Manager.** MEDIUM confidence. The state machine enforces invariants, not just process coordination. However, the lack of observed business consequences (D35) weakens the argument — if certification produces no downstream effect, the session may be a verification record rather than a decision-making aggregate.

---

## 3. Challenge Q8: Replay Projection Test

### Question

Can the certification outcome be reconstructed entirely from EvidenceEnvelope + ExpectedOutcome + PolicySequenceHash without ReplaySession state?

### Analysis

| Data | Source | Can Reconstruct? |
|------|--------|----------------|
| Evidence that was sealed | EvidenceEnvelope (contains frozen evidence + hash) | **Yes** — envelope is independent of session |
| Expected outcome | ReplayAssertion.expectedOutcome (stored during session) | **Yes** — but assertion is created during session lifecycle |
| Policy sequence hash | ReplayAssertion.policySequenceHash | **Yes** — but hash is recorded during session |
| Session identity | Session ID derived deterministically from envelope hash | **Yes** — deterministic from envelope data |
| Certification result | ReplayCertification (produced at end of session) | ❌ **Cannot reconstruct** — certification is the session's own outcome |
| Session state progression | sealed → replayed → certified | ❌ **Cannot reconstruct** — session state is tracked by the session itself |

### Projection Test Conclusion

**Partial reconstruction possible, but certification outcome and state progression are owned by the session.** The certification result depends on the session's own evaluation, not on external data. This is different from Results/Tallying where all data was reconstructable from Votes.

**Implication:** ReplaySession owns non-reconstructable state (certification outcome, state progression). This strengthens the aggregate argument — the session is not merely a projection of other data.

---

## 4. Challenge Q9: Bounded Context vs Domain Capability Reassessment

### Question

Does the discovered model strengthen the case for Bounded Context (A) or Domain Capability (B)?

### Analysis

| Criterion | Assessment | Supports |
|-----------|------------|----------|
| Unique decision ownership | ✅ ReplaySession owns replay verification decision | Bounded Context |
| Distinct language | ✅ Replay, evidence, envelope, seal, certification, divergence | Bounded Context |
| Independent evolution | ✅ Evidence verification is independent of operational audit | Bounded Context |
| Consistency boundary | ✅ Session state + assertion + certification require transactional consistency | Bounded Context |
| Operational activation | ❌ D36 — no observed runtime callers | Domain Capability |
| Downstream consumers | ❌ No observed consumers of certification/divergence output | Domain Capability |

### Assessment

The discovered model strengthens the **Bounded Context** classification for decision ownership, language, and consistency boundary. The **Domain Capability** classification is weaker because the session has a transactional consistency boundary and enforces invariants — a Domain Capability would not require its own consistency boundary.

However, the lack of operational activation (D36) and downstream consumers means Governance Evidence Replay remains a **dormant Bounded Context** — its boundaries are correct, but its runtime integration is not yet operational.

### Replay Classification Test Outcome (Updated from Round 25)

**Primary classification: A — Bounded Context** (strengthened by aggregate analysis)

**Alternative: B — Domain Capability** (weakened — consistency boundary argues against capability-only classification)

---

## 5. Challenge Q10: D36 Invocation Challenge

### Question

If nothing invokes ReplaySession, is ReplaySession a business model or a dormant verification library?

### Arguments for "Verification Library"

| Evidence | Source |
|----------|--------|
| No operational invocation observed | D36, Stream 6B |
| GovernanceDecisionStore is wired but GovernanceReplayService.persist() not observed in operational code | Round 18 |
| ReplaySession creation only observed in tests | Stream 4, Stream 6B |
| Phase 6 deferral means operational activation is not prioritized | Round 18 |

### Arguments for "Business Model (Dormant)"

| Evidence | Source |
|----------|--------|
| ReplaySession enforces business invariants (one certification cycle, state sequencing) | ReplaySession.php |
| Decision ownership (replay verification) exists independent of operational invocation | Stream 4 |
| GovernanceDecisionStore IS operationally wired — persistence infrastructure exists | Round 18 |
| ReplaySession aggregate boundaries are well-defined regardless of activation | ReplaySession.php, Round 27G |

### Assessment

ReplaySession is a **dormant business model**, not a verification library. Its invariants, decision ownership, and consistency boundary are defined regardless of whether it is currently invoked. The key distinction: a verification library would not enforce state machine invariants — it would provide stateless verification functions. ReplaySession's state enforcement proves it is a genuine domain model.

**Impact on aggregate status:** None. D36 affects operational priority, not aggregate legitimacy. This conclusion stands as originally stated in Round 27G.

---

## 6. Revised Aggregate Inventory

| Concept | Type | Confidence | Rationale |
|---------|------|------------|-----------|
| **ReplaySession** | **Aggregate** | **MEDIUM** (unchanged) | Survived challenge: stronger than Process Manager (invariants, not coordination), partial projection test (certification not reconstructable), Bounded Context classification strengthened. D36 does not affect aggregate validity. |
| EvidenceEnvelope | Value Object | HIGH | Unchanged |
| GovernanceDecisionSnapshot | Entity/VO (Governance) | HIGH | Unchanged |
| GovernanceArchaeologyRecord | Read Model | HIGH | Unchanged |
| ReplayDivergence | Domain Event | HIGH | Unchanged |
| GovernanceReplayService | Domain Service | MEDIUM | Unchanged |

---

## 7. Confidence Reassessment

| Concept | Previous Confidence | Revised Confidence | Reason |
|---------|-------------------|-------------------|--------|
| ReplaySession (aggregate) | MEDIUM | MEDIUM | Survived challenge review. Aggregate > Process Manager confirmed. D36 does not affect. |

---

## 8. Summary

**Challenge review outcomes:**

| Challenge | Finding | Impact on Aggregate Status |
|-----------|---------|---------------------------|
| Q7: Aggregate or Process Manager? | **Aggregate** stronger — invariants enforced, business truth owned, not process coordination | ✅ Strengthened |
| Q8: Replay Projection Test | **Partial reconstruction** — certification and state progression not reconstructable | ✅ Strengthened (different from Results/Tallying) |
| Q9: Bounded Context vs Capability | **Bounded Context strengthened** — consistency boundary argues against capability-only | ✅ Strengthened |
| Q10: D36 Invocation Challenge | **Dormant business model** — D36 affects priority, not validity | ✅ Confirmed |

**ReplaySession aggregate status confirmed at MEDIUM confidence.** No downgrade warranted. The challenge review found that aggregate classification is stronger than Process Manager alternative, that certification outcome is not reconstructable from external data, that Bounded Context classification is supported, and that D36 does not affect boundary validity.

---

**Round 27G-ARB Governance Evidence Replay Challenge Review — READY FOR ARB DECISION**

**ReplaySession aggregate confirmed (MEDIUM). Survived 4 challenge questions. Not a Process Manager. Not reconstructable. D36 does not affect aggregate validity. Ready for ARB acceptance alongside original Round 27G aggregate discovery.**
