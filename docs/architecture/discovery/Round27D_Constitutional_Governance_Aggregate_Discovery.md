# Round 27D — Constitutional Governance Aggregate Discovery

**Date:** 2026-06-08

**Phase:** Tactical DDD — Aggregate Discovery (Context 4 of 9)

**Status:** Complete — Awaiting ARB Challenge Review

---

## 1. Investigation Scope

**Candidate Context:** Constitutional Governance / Lifecycle

**Acceptance Status:** ACCEPTED (Round 25)
**Boundary Stability:** STABLE

**Evidence Sources:** ADR-003 (Lifecycle vs Phase Projection), Stream 5 (Constitutional Rule Discovery), ElectionConstitution.php, ConstitutionalTransitionGuard.php, GovernanceDecision.php, ADR-001 (Constitutional Capability Sovereignty), Round 18 Governance Review, Round 20 Boundary Review, Round 22 Relationship Analysis (R3/R4/R8/R10).

---

## 2. Context Legitimacy

Constitutional Governance owns decisions about what states exist, which transitions are allowed, and what preconditions are required. It is a rule-defining context — it owns the policies that Authorization, Voting, and Eligibility evaluate at runtime, and it owns governance state (lifecycle, suspension, governance decisions).

---

## 3. Concept Inventory

### Concept 1: LifecycleState

**Source:** ADR-003, ElectionConstitution.RULES, Stream 5

**Description:** The 12 constitutional lifecycle states. Single source of truth in `elections.lifecycle_state` database column. Constitutional runtime truth — not a UI projection.

---

### Concept 2: StateTransition

**Source:** ElectionConstitution.RULES, ConstitutionalTransitionGuard

**Description:** Allowed movement between lifecycle states with preconditions and role requirements. 13 transitions defined. 4 checks enforced by ConstitutionalTransitionGuard.

---

### Concept 3: Precondition (Governance-owned)

**Source:** ElectionConstitution.RULES, ConstitutionalTransitionGuard.isPreconditionMet()

**Description:** Rules that must be satisfied before a transition is allowed. Confirmed as Governance-owned in 27C-ARB.

---

### Concept 4: RoleDefinition (Governance-owned)

**Source:** ElectionConstitution.RULES, ElectionOfficer model

**Description:** Definition of which roles exist. Confirmed as Governance-owned in 27C-ARB.

---

### Concept 5: GovernanceDecision

**Source:** GovernanceDecision.php, GovernanceDecisionSnapshot

**Description:** Record of formal governance decisions per capability type.

---

### Concept 6: Suspension

**Source:** ElectionConstitution.RULES (suspend/resume), ADR-003

**Description:** Operational governance overlay that freezes capabilities without mutating business facts.

---

## 4. Aggregate Candidate Evaluation

### Candidate A: LifecycleState

| Test | Assessment |
|------|------------|
| Business decision owned | None — state is tracked, not decided. State changes through transitions. |
| Invariant protected | State must be one of 12 defined values |
| Transactional consistency | State change must be atomic with transition validation |
| If split | State could change without transition validation |
| Alternative classification | **Entity (within Governance aggregate)** — tracked attribute |

**Result: Entity**

---

### Candidate B: StateTransition

| Test | Assessment |
|------|------------|
| Business decision owned | "Is this transition from current state to target state allowed?" |
| Invariant protected | Allowed_states match; preconditions met; user has role |
| Transactional consistency | HIGH — validation + state change must be atomic |
| If split | State could transition without precondition validation |
| Alternative classification | **Domain Service** — evaluates rules, does not own state |

**Result: Domain Service**

---

### Candidate C: GovernanceRule (ElectionConstitution.RULES)

| Test | Assessment |
|------|------------|
| Business decision owned | None — static deploy-time definitions |
| Invariant protected | Rule consistency (assumed, not transactionally enforced) |
| Transactional consistency | N/A — deployed as code |
| Alternative classification | **Specification/Policy** |

**Result: Specification/Policy**

---

### Candidate D: GovernanceDecision

| Test | Assessment |
|------|------------|
| Business decision owned | "What governance decision was made about this capability?" |
| Invariant protected | Decision attributed; capability-scoped; timestamped |
| Transactional consistency | HIGH — decision + attribution + timestamp must be atomic |
| If split | Decision could be recorded without attribution |
| Alternative classification | **AGGREGATE (MEDIUM)** — persistent state, independent lifecycle, invariants |

**Result: Aggregate (MEDIUM confidence)**

---

### Candidate E: Suspension

| Test | Assessment |
|------|------------|
| Business decision owned | "Should this election be suspended or resumed?" |
| Invariant protected | Suspension freezes capabilities; does not advance lifecycle |
| Transactional consistency | HIGH — suspension must be atomic with capability freeze |
| If split | Election could be partially suspended |
| Alternative classification | **Entity (within Governance aggregate)** — modifies lifecycle state |

**Result: Entity**

---

### Candidate F: GovernanceAggregate (combined lifecycle + suspension + transitions)

| Test | Assessment |
|------|------------|
| Business decision owned | "What is the current state and what transitions are allowed?" |
| Invariant protected | State validity; transition legitimacy; suspension consistency |
| Transactional consistency | HIGH — all three must be consistent |
| If split | Lifecycle, transition rules, suspension could become inconsistent |
| Alternative classification | **AGGREGATE (MEDIUM)** — single consistency boundary for election governance state |

**Result: Aggregate (MEDIUM confidence)**

---

## 5. Aggregate Boundary Validation Matrix

| Candidate | Owns Decision? | Owns State? | Invariant Protection | Aggregate? |
|-----------|---------------|-------------|---------------------|------------|
| LifecycleState | ❌ Tracked | ✅ Changes | ✅ Valid state | Entity |
| StateTransition | ✅ Validation | ❌ Stateless | ✅ 4 checks | Domain Service |
| GovernanceRule | ❌ Static | ❌ Deployed | ⚠️ Assumed | Specification |
| **GovernanceDecision** | **✅ Record** | **✅ Yes** | **✅ Attribution** | **Aggregate** |
| Suspension | ✅ Overlay | ✅ Flag | ✅ Freezes | Entity |
| **GovernanceAggregate** | **✅ Lifecycle** | **✅ Yes** | **✅ State+transition** | **Aggregate** |

---

## 6. Revised Aggregate Inventory

| Concept | Type | Confidence |
|---------|------|------------|
| **GovernanceAggregate** (lifecycle + suspension + transition enforcement) | **Aggregate** | MEDIUM |
| **GovernanceDecision** | **Aggregate** | MEDIUM |
| StateTransition | Domain Service | HIGH |
| GovernanceRule | Specification/Policy | HIGH |
| Suspension | Entity | MEDIUM |
| LifecycleState | Entity | HIGH |

---

## 7. Aggregate Discovery Debt

| Debt | Question | Priority |
|------|----------|----------|
| ADG-1 | Should GovernanceDecision be a separate aggregate or part of GovernanceAggregate? Decisions have independent lifecycle but may be transactionally related. | MEDIUM |
| ADG-2 | Is GovernanceDecision a Governance concept, an Audit concept, or a Replay concept? The decision record is created by Governance but consumed by Audit and Replay. | HIGH |
| ADG-3 | What is the GovernanceAppointment boundary? Appointment policies (who appoints whom) are Governance-owned rules, but the resulting RoleAssignment aggregate belongs to Authorization. Boundary needs confirmation. | MEDIUM |

---

**Round 27D Constitutional Governance Aggregate Discovery — READY FOR ARB CHALLENGE REVIEW**

**2 aggregate candidates identified. 1 Domain Service. 1 Specification/Policy. 2 Entities. 3 aggregate discovery debt items. Awaiting challenge review before acceptance.**
