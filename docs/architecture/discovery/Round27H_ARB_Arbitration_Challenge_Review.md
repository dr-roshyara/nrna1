# Round 27H-ARB — Arbitration/Legitimacy Challenge Review

**Date:** 2026-06-08

**Phase:** Tactical DDD — Aggregate Challenge Review (Pre-Acceptance)

**Status:** Complete — Ready for ARB Decision

---

## 1. Purpose

Challenge the conclusion that ConstitutionalDecision is an aggregate. Apply the same adversarial review discipline used for ReplaySession and other contexts.

---

## 2. Challenge Q11: Aggregate or Projection?

### Question

Can ConstitutionalDecision be reconstructed entirely from GovernanceDecision + AuthorityClassification + ArbitrationPolicy + LegitimacyPolicy?

### Analysis

| Data | Source | Can Reconstruct? |
|------|--------|----------------|
| Winner (JurisdictionNode) | Determined by ArbitrationPolicy resolving AuthorityClassification | **Partially** — policy defines *how* to resolve, but the *act* of applying that policy to a specific classification produces the winner |
| Legitimacy status | Determined by LegitimacyEvaluator from TemporalAuthorityWindow | **Yes** — LegitimacyEvaluator is a stateless computation: given window + time, output is deterministic |
| Reason (ConstitutionalReason) | Produced by ArbitrationPolicy based on resolution result | **Yes** — reason is determined algorithmically (resolved code if winner, no_authority if none) |
| Trace (evaluatedNodes, rejectionReasons) | Collected during arbitration execution | **Yes** — trace is a record of what happened, not a decision |
| **Combined result** | ConstitutionalGovernanceDecision wraps Governance + Constitutional decisions | ✅ **Reconstructable** — each component can be recomputed from inputs |

### Projection Test Conclusion

**ConstitutionalDecision is partially reconstructable.** The legitimacy status and reason can be recomputed from inputs. The winner selection depends on applying ArbitrationPolicy to the specific AuthorityClassification — this is a computation, not stored truth. The decision is the *act of applying policy to a specific case at a specific time*, not a persistent truth that cannot be recomputed.

**Implication:** Weaker than ReplaySession (where certification was not reconstructable). ConstitutionalDecision may be an **evaluation record** rather than a decision-making aggregate. The truth it contains is derived from stateless evaluations of external inputs.

---

## 3. Challenge Q12: Decision Owner or Decision Record?

### Question

Does ConstitutionalDecision create constitutional truth or record constitutional truth?

### Analysis

| Aspect | Creates Truth | Records Truth |
|--------|--------------|---------------|
| Winner selection | The policy *decides* who wins based on authority rules | The policy is predetermined — Arbitration *applies* it |
| Legitimacy status | LegitimacyEvaluator *determines* status from temporal window | The window is external — Arbitration *reads* it |
| Constitutional validity | ConstitutionalGovernanceDecision.isConstitutionallyValid() *declares* validity | Validity is derived from legitimacy — Arbitration *reports* it |

### Assessment

**ConstitutionalDecision primarily RECORDS truth that already exists in its inputs.**

- The conflict resolution policy predetermines who should win given an authority classification
- The legitimacy evaluator predetermines what status applies given a temporal window
- Arbitration applies these predetermined rules to a specific case and records the result

This is different from an aggregate like Vote, which CREATES truth (the vote did not exist before the voter cast it). ConstitutionalDecision is an evaluation record — it documents the application of predetermined rules to a specific input set.

**Impact on aggregate status:** Significantly weakened. A decision record is not automatically an aggregate. The truth it contains is derivable from its inputs.

---

## 4. Challenge Q13: Legitimacy Origin Test

### Question

Does legitimacy originate from Arbitration or from Governance rules and temporal windows?

### Analysis

| Evidence | Source |
|----------|--------|
| Legitimacy is determined by LegitimacyEvaluator.evaluate(TemporalAuthorityWindow, DateTimeImmutable) | LegitimacyEvaluator.php |
| LegitimacyEvaluator is a pure stateless computation: PENDING → PENDING, ACTIVE → LEGITIMATE, EXPIRED → EXPIRED | LegitimacyEvaluator.php:13-17 |
| TemporalAuthorityWindow represents governance rules about authority validity periods | Governance context |
| LegitimacyEvaluator is consumed by DefaultTemporalLegitimacyPolicy, which is delegated to by ArbitrationPolicy | DefaultTemporalLegitimacyPolicy.php, DefaultConstitutionalArbitrationPolicy.php |
| D35 asks "What happens when legitimacy = EXPIRED?" — no consequence observed | Stream 6B |

### Assessment

**Legitimacy originates from Governance rules (temporal windows), not from Arbitration.**

The LegitimacyEvaluator is a stateless computation that deterministically maps a governance-defined window to a legitimacy status. Arbitration invokes this evaluation but does not create the legitimacy status — the status was determined by the temporal window state before Arbitration was involved.

This is analogous to: Arbitration asks "is this valid?" but the answer is predetermined by Governance rules. Arbitration merely looks up the answer.

**Impact on aggregate status:** Further weakened. If ConstitutionalDecision does not own the truth it records, its aggregate legitimacy is questionable.

---

## 5. Challenge Q14: Aggregate Root Test

### Question

Would any invariant be violated if ConstitutionalDecision did not exist as an aggregate root?

### Invariant Analysis

| Invariant | Would Violate Without Aggregate? | Why |
|-----------|--------------------------------|-----|
| Decision must be linked to its trace | **No** — trace could be a method return value, not an entity within the aggregate |
| Decision must have a legitimacy status | **No** — legitimacy is a value computed and returned alongside the decision |
| Decision must reference evaluated authority | **No** — evaluation inputs are passed to the policy; output can reference inputs |
| Decision must be timestamped | **No** — timestamp is metadata, not invariant requiring aggregate |
| Decision must be immutable | **No** — immutability is a property of records, not specific to aggregates |

### Assessment

**No invariant requires ConstitutionalDecision to be an aggregate root.** All invariants can be satisfied by a stateless evaluation that returns a result value object. The decision documentation (trace, legitimacy, reason) is the output of a stateless computation — not state that requires transactional consistency.

**Impact on aggregate status: Severe.** The aggregate root test fails. ConstitutionalDecision does not own invariants that require an aggregate boundary.

---

## 6. Challenge Q15: D35/D37 Consequence Test

### Question

If legitimacy has no observed consequence and no observed enforcement, what is ConstitutionalDecision?

### Alternatives

| Classification | Evidence | Verdict |
|---------------|----------|---------|
| **Operational Aggregate** | Owns decisions, produces consequences, triggers downstream actions | ❌ No consequences observed (D35). No enforcement observed (D37). |
| **Informational Aggregate** | Records evaluation results for audit/replay consumption | ⚠️ Possible — ConstitutionalDecision is persisted in GovernanceDecisionSnapshot for replay |
| **Decision Record (Value Object)** | Immutable output of a stateless evaluation process | ✅ Strong — evaluation is stateless, output is immutable, no ongoing consistency requirements |
| **Projection** | Derived from evaluation inputs + policies | ✅ Strong — all output can be recomputed from inputs |

### Assessment

ConstitutionalDecision is best classified as a **Decision Record (Value Object) or Projection** rather than an aggregate. It is the immutable output of a stateless evaluation process. Its invariants (referential integrity, determinism) do not require transactional consistency — they are enforced by the evaluation function, not by an aggregate boundary.

D35 and D37 support this: if the decision has no consequences and no enforcement, it is an informational record, not an operational decision-making concept.

---

## 7. Revised Aggregate Inventory

| Concept | Original Classification | Revised Classification | Confidence | Rationale |
|---------|----------------------|----------------------|------------|-----------|
| **ConstitutionalDecision** | **Aggregate** (MEDIUM) | **Decision Record** (not Value Object — has identity, timestamp, traceability) | **MEDIUM** | Fails aggregate root test (Q14). Stateless evaluation (Q12). Legitimacy from Governance (Q13). Partially reconstructable (Q11). No consequences (Q15). Classification sits between Aggregate and Value Object — it has identity and audit significance but no transactional invariant requirements. |
| GovernanceLegitimacy | Value Object | Value Object | HIGH | Unchanged |
| ConstitutionalArbitrationTrace | Entity | **Value Object collection** | MEDIUM | Trace is output of evaluation, not state to be protected |
| ArbitrationPolicy | Specification/Policy | Specification/Policy | HIGH | Unchanged |
| ConstitutionalGovernanceDecision | Entity/VO | Value Object | HIGH | Combined result |
| LegitimacyEvaluator | Domain Service | Domain Service | HIGH | Unchanged |

### Key Revision

ConstitutionalDecision downgraded from **Aggregate (MEDIUM)** to **Decision Record (MEDIUM)** — not a Value Object because it has identity, timestamp, and audit significance, but not an Aggregate because no invariant requires an aggregate boundary. The challenge review found that:
- The evaluation is stateless — all outputs reconstructable from inputs
- ConstitutionalDecision records truth that already exists in Governance rules and temporal windows
- Legitimacy originates from Governance, not Arbitration
- No invariant requires an aggregate boundary (Q14)
- No consequences or enforcement observed (Q15)

---

## 8. Confidence Reassessment

| Concept | Previous Confidence | Revised Confidence | Reason |
|---------|-------------------|-------------------|--------|
| ConstitutionalDecision | MEDIUM (aggregate) | **MEDIUM (VO/Record)** | Aggregate root test failed. Stateless evaluation. |
| ArbitrationTrace | MEDIUM (entity) | MEDIUM (VO) | Trace is output of evaluation |

---

## 9. Summary

**Challenge outcomes:**

| Challenge | Finding | Impact |
|-----------|---------|--------|
| Q11: Aggregate or Projection? | Partially reconstructable — weaker than ReplaySession | Weakened |
| Q12: Decision Owner or Decision Record? | Primarily records truth that already exists in inputs | Weakened |
| Q13: Legitimacy Origin | Legitimacy originates from Governance temporal windows, not Arbitration | Weakened |
| Q14: Aggregate Root Test | No invariant requires aggregate boundary | **Severely weakened** |
| Q15: D35/D37 Consequence | No consequences or enforcement — informational record | Weakened |

**Arbitration context aggregate inventory:** 0 aggregates confirmed. ConstitutionalDecision is a Decision Record / Value Object — the immutable output of a stateless evaluation process. Arbitration is primarily a Domain Service (LegitimacyEvaluator) backed by Specifications/Policies (ArbitrationPolicy, ConflictResolutionPolicy).

This is consistent with the pattern from Eligibility: a bounded context with a clear business decision that is implemented through stateless evaluation rather than aggregate state management. Arbitration evaluates constitutional validity — it does not create constitutional truth.

---

**Round 27H-ARB Arbitration/Legitimacy Challenge Review — READY FOR ARB DECISION**

**0 aggregates in Arbitration context after challenge review. ConstitutionalDecision downgraded from Aggregate (MEDIUM) to Decision Record / Value Object (MEDIUM). Arbitration context follows the Eligibility pattern — clear decision ownership implemented through stateless evaluation, not aggregate state. 0 aggregates matches the pattern from Results/Tallying and now Arbitration.**
