# Round 27D-ARB — Governance Consistency Boundary Review

**Date:** 2026-06-08

**Phase:** Tactical DDD — Consistency Boundary Review (Pre-Acceptance)

**Status:** Complete — Ready for ARB Decision

---

## 1. Purpose

Determine whether Constitutional Governance's unique decisions must live inside a single aggregate boundary or can safely be separated. This is the final review before ARB acceptance of the Governance aggregate inventory.

---

## 2. Consistency Boundary Questions

### Q1: Can Lifecycle State Change Without Changing Governance Rules?

**Scenario:** An election transitions from `draft` to `submitted_for_approval`.

| What changes? | Transactional requirement |
|-------------|--------------------------|
| Lifecycle state: draft → submitted_for_approval | Must be atomic with transition validation |
| Governance rules: No change | Rules are static deploy-time definitions |
| Precondition state: timezone must be set | Must be verified at transition time |

**Conclusion:** Lifecycle state changes independently of governance rules. Rules are static (D30 resolved — rules-in-code is intentional). State is dynamic. **They do not require the same consistency boundary.**

**Implication:** GovernanceAggregate does NOT need to contain both state and rules. The ConservativeTransitionGuard (Domain Service) reads rules (Specification/Policy) and validates state transitions — the rules are inputs, not state to be protected.

---

### Q2: Can Governance Rules Change Without Changing Lifecycle State?

**Scenario:** A rule change (deployment) modifies allowed_roles for `publish_results` from `[chief]` to `[chief, deputy]`.

| What changes? | Transactional requirement |
|-------------|--------------------------|
| Governance rules: allowed_roles updated | Deploy-time change — no transaction |
| Lifecycle state: No change | Remains in current state |
| Existing transitions: No change | Only future transitions affected |

**Conclusion:** Governance rules change independently of lifecycle state. Rules are deployed, not transacted with state. **They do not require the same consistency boundary.**

**Implication:** Governance rules (Specifications/Policies) are safe outside any aggregate boundary. They are evaluated at runtime but do not participate in transactional state changes.

---

### Q3: Must Suspension State and Lifecycle State Remain Transactionally Consistent?

**Scenario:** An election is suspended.

| What changes? | Transactional requirement |
|-------------|--------------------------|
| Lifecycle state: voting_active → suspended | Must be atomic |
| Suspension flag: false → true | Must be set atomically with state change |
| Capability freeze: applied | Must be applied atomically with suspension |
| Pre-suspension state: recorded | Must be recorded before state change |

**Conclusion:** Suspension state and lifecycle state MUST remain transactionally consistent. If suspension flag is set without state change, the election would be in an inconsistent state (suspended flag set but lifecycle still showing voting_active). **They require the same consistency boundary.**

**Implication:** Suspension is an entity within whatever aggregate owns lifecycle state. Whether that aggregate is GovernanceAggregate or ElectionAggregate, suspension and lifecycle must be in the same aggregate boundary.

---

### Q4: What Business Inconsistency Becomes Possible If GovernanceAggregate Is Split?

**Scenario A:** Lifecycle state in Aggregate A, Suspension state in Aggregate B.

| Invariant | Breaks? | Why |
|-----------|---------|-----|
| Suspended election shows suspension state | ✅ Yes — eventual consistency could desynchronize lifecycle and suspension states |
| Resume returns to correct pre-suspension state | ✅ Yes — if pre-suspension state is in Aggregate B and lifecycle is in Aggregate A, a partial update could lose the pre-suspension state |
| Capability freeze matches suspension status | ⚠️ Possible — capability freeze could be applied without suspension state (or vice versa) |

**Inconsistency risk: HIGH.** Lifecycle state and suspension state must be transactionally consistent. Splitting them creates a window where the election appears partially suspended.

**Scenario B:** Governance rules (Specifications) and Governance state (lifecycle + suspension) are separated.

| Invariant | Breaks? | Why |
|-----------|---------|-----|
| Transition rules applied correctly | ❌ No — Domain Service reads rules at runtime; rules are static inputs |
| Transitions follow defined sequence | ❌ No — Domain Service enforces sequence; rules are specifications evaluated not transacted |
| Preconditions satisfied | ❌ No — precondition evaluation is runtime, rules are inputs not state |

**Inconsistency risk: LOW.** Governance rules are static specifications. They are inputs to the Domain Service, not state that must be transactionally consistent with lifecycle changes.

---

## 3. Aggregate Boundary Assessment

### What Must Be In The Same Aggregate

| Concept | Reason |
|---------|--------|
| **Lifecycle state** | Core governance state — changes through transitions |
| **Suspension flag** | Must be transactionally consistent with lifecycle state |
| **Pre-suspension state** | Must be recorded atomically with suspension |
| **Governance decisions** | May be separate — decisions have independent lifecycle (ADG-2) |

### What Can Be Outside The Aggregate

| Concept | Reason |
|---------|--------|
| **Governance rules** | Static deploy-time specifications — inputs to Domain Service |
| **StateTransition** | Domain Service — stateless, reads rules and state |
| **Precondition specifications** | Static rules — evaluated at runtime |
| **Role definitions** | Static rules — owned by Governance, evaluated by Authorization |

### Candidate Names

The aggregate boundary that emerges is:

**GovernanceState** — owning lifecycle state, suspension flag, and pre-suspension state.

Not GovernanceAggregate (which implied rules + state).
Not Election aggregate (which implied voting ownership).

---

## 4. Revised Aggregate Inventory (Final)

| Concept | Type | Confidence | Rationale |
|---------|------|------------|-----------|
| **GovernanceState** (lifecycle + suspension) | **Aggregate** | **MEDIUM-HIGH** | Lifecycle and suspension must be transactionally consistent. Rules are outside the boundary (static specifications). This is a narrower boundary than the original GovernanceAggregate proposal. |
| **GovernanceDecision** | **Entity (within GovernanceState) or Persistent Record** | **MEDIUM** | May be an entity recording transitions on GovernanceState, or a standalone record consumed by Audit/Replay. ADG-2 remains open. |
| StateTransition (ConstitutionalTransitionGuard) | Domain Service | HIGH | Stateless — reads GovernanceState and Governance rules |
| GovernanceRule (ElectionConstitution.RULES) | Specification/Policy | HIGH | Static deploy-time definitions |
| Suspension | Entity (within GovernanceState) | MEDIUM | Overlay state — must be transactionally consistent with lifecycle |
| LifecycleState | Entity (within GovernanceState) | HIGH | Core state tracked by the aggregate |

### Key Shift from Round 27D

The original proposal was **GovernanceAggregate** (rules + state + transitions + suspension).

The evidence supports a narrower boundary: **GovernanceState** (lifecycle + suspension only). Rules, transitions, and preconditions are specifications/policies outside the aggregate boundary. This is consistent with the pattern across 27B/27C — bounded contexts often contain fewer aggregates than initially proposed.

---

## 5. Summary

| Question | Answer | Confidence |
|----------|--------|------------|
| Q1: Can lifecycle change without rules changing? | **Yes** — rules are static, state is dynamic | HIGH |
| Q2: Can rules change without lifecycle changing? | **Yes** — rules are deployed, not transacted | HIGH |
| Q3: Must suspension and lifecycle be consistent? | **Yes** — splitting breaks suspension semantics | HIGH |
| Q4: What inconsistency risk from splitting? | **HIGH for lifecycle+suspension; LOW for rules** | MEDIUM-HIGH |

**Final recommendation:** GovernanceState aggregate (lifecycle state + suspension overlay, narrower than originally proposed). GovernanceDecision remains unresolved (4 possible classifications). Governance rules remain Specifications/Policies outside the aggregate boundary.

---

**Round 27D-ARB Consistency Boundary Review — READY FOR ARB DECISION**

**GovernanceState aggregate confirmed (MEDIUM-HIGH confidence) — lifecycle state + suspension only. Governance rules confirmed as Specifications/Policies outside aggregate boundary. GovernanceDecision remains unresolved (MEDIUM confidence, 4 possible classifications). Consistency boundary narrowed from original GovernanceAggregate proposal.**
