# Round 27D-ARB — Constitutional Governance Challenge Review

**Date:** 2026-06-08

**Phase:** Tactical DDD — Aggregate Challenge Review (Pre-Acceptance)

**Status:** Complete — Awaiting ARB Decision

---

## 1. Purpose

Challenge all aggregate conclusions from Round 27D before acceptance. Use only existing evidence. No new repository discovery.

---

## 2. Question 1: Is GovernanceAggregate Genuinely an Aggregate?

### Invariant Evidence Matrix

| Invariant | Evidenced? | Enforced? | Transactional? | Classification |
|-----------|-----------|-----------|----------------|----------------|
| State must be one of 12 defined values | ✅ ADR-003: 12 constitutional states | ✅ Database column `lifecycle_state` is string; application uses enums | ❌ State changed through transitions, not checked at write time | ⚠️ Evidenced but not transactionally enforced |
| Transition must proceed through valid state sequence | ✅ ConstitutionalTransitionGuard.assertAllowed() checks 1-4 | ✅ Guard throws InvalidTransitionException | ✅ Guard enforces at transition time | ✅ Confirmed |
| Preconditions must be satisfied | ✅ isPreconditionMet() for each precondition | ✅ Guard check 4 | ✅ Guard enforces at transition time | ✅ Confirmed |
| User must have required role | ✅ Guard check 3, ElectionConstitution allowed_roles | ✅ Guard throws if role missing | ✅ Guard enforces at transition time | ✅ Confirmed |
| Suspension freezes capabilities without lifecycle change | ✅ ADR-003, code comments | ✅ `projectionAvailable=false` flag | ⚠️ Enforcement through capability resolver, not at write time | ⚠️ Partially evidenced |
| Pre-suspension state preserved for resume | ❌ Not observed | ❌ Not observed | ❌ Not observed | ❌ Unknown |

### Assessment

Of 6 claimed invariants:
- **3 confirmed**: transition sequence, precondition validation, role requirement — all enforced by ConstitutionalTransitionGuard
- **1 partially evidenced**: state must be one of 12 values — evidenced in ADR but not transactionally enforced at write time (state is set through transitions, not validated independently)
- **1 partially evidenced**: suspension freeze — capability freeze is architectural, not invariants-based
- **1 unknown**: pre-suspension state preservation

### ConstitutionalTransitionGuard Role

The guard is not an aggregate. It is a **Domain Service** that enforces policies (Specifications) against current state. The state it validates is the lifecycle state, which could be owned by a GovernanceAggregate or by an Election aggregate.

### Hypotheses for Lifecycle State Ownership

**H1: GovernanceAggregate owns lifecycle state.**
- Evidence: State is the core Governance concept; all transitions require Governance validation
- Weakness: Lifecycle state is stored on the `elections` table — it is part of the Election record. Nothing forces it to be a separate aggregate.

**H2: Election (Voting/Governance root) owns lifecycle state; Governance owns transition rules.**
- Evidence: Lifecycle state is a column on `elections` table; it is inherently an attribute of the election, not a separate concept
- Weakness: This would merge lifecycle state into whatever aggregate owns the Election root, blurring the Governance/Voting boundary

### Verdict

GovernanceAggregate is **MODERATELY SUPPORTED** but not proven. The invariants that require transactional consistency (transition order, preconditions, role requirements) are enforced by the ConstitutionalTransitionGuard Domain Service against the lifecycle state. Whether that lifecycle state is part of a GovernanceAggregate or part of an Election aggregate is unresolved.

---

## 3. Question 2: Who Owns Lifecycle State?

### Hypothesis A: Governance owns lifecycle state

| Evidence | Source |
|----------|--------|
| Lifecycle state is the central concept of constitutional governance | ADR-003, Stream 5 |
| Transitions are defined in ElectionConstitution.RULES (Governance) | ElectionConstitution.php |
| TransitionGuard enforces all state changes (Governance enforcement) | ConstitutionalTransitionGuard.php |
| Suspension is a governance overlay | ADR-003 |

### Hypothesis B: Election owns lifecycle state; Governance owns transition rules

| Evidence | Source |
|----------|--------|
| Lifecycle state is stored as `elections.lifecycle_state` — a column on the elections table | Database migration, Election model |
| The election is the entity that progresses through states, not Governance | Stream 5 |
| Governance defines *rules* but Elections own *state* | ADR-003 (Governance defines allowed transitions; Elections transition through them) |
| TransitionGuard checks are applied at the Election level (ConstitutionalTransitionGuard takes Election as parameter) | ConstitutionalTransitionGuard.php:40-44 |

### Assessment

Hypothesis B is **more consistent with current evidence**. The lifecycle state is stored on the `elections` table, making it part of whatever aggregate owns the Election record. Governance owns the *rules* about how that state can change, but the *state itself* is an attribute of the Election.

This would mean: GovernanceAggregate as proposed in Round 27D may not be a separate aggregate. Instead, the lifecycle state specification/entity belongs with the Election aggregate, and Governance owns the transition policies (StateTransition Domain Service, GovernanceRule Specification, Suspension policy).

**Impact on aggregate inventory:** GovernanceAggregate may be REDUNDANT if lifecycle state belongs to the Election. The ConstitutionalTransitionGuard would remain a Domain Service. Governance would own rules, not state.

---

## 4. Question 3: What Is GovernanceDecision?

### Hypothesis A: Governance Aggregate

| Evidence for | Evidence against |
|-------------|-----------------|
| GovernanceDecision model records decisions per capabilityType — a governance concern | Could merely be a record in Audit, not a governance aggregate |
| Decision capability scope ties it to governance actions | |

### Hypothesis B: Audit Aggregate

| Evidence for | Evidence against |
|-------------|-----------------|
| GovernanceDecision records decisions about actions — similar to audit logging | Audit is fire-and-forget; GovernanceDecision is created as part of governance process |
| Decisions are immutable after recording — similar to audit records | GovernanceDecision has integrity hashing (tamper detection), which Audit does not |
| GovernanceDecision is consumed by Replay for verification — similar to audit evidence | |

### Hypothesis C: Replay Aggregate

| Evidence for | Evidence against |
|-------------|-----------------|
| GovernanceDecisionSnapshot includes integrity hashing for replay | Replay consumes decisions but does not create them |
| GovernanceReplayService persists snapshots | Replay infrastructure is deferred (Phase 6) |
| Decisions are used for divergence detection by Replay | |

### Hypothesis D: Persistent Record (no aggregate status)

| Evidence for | Evidence against |
|-------------|-----------------|
| GovernanceDecision may simply be a history of governance actions | Has invariants (attribution, capability scope) that require transactional consistency |
| No observed enforcement or consumption within governance workflow | Referenced by Replay — it is independently meaningful |

### Assessment

GovernanceDecision is **most consistent with Hypothesis D or A**. It records governance decisions (who decided what about which capability). This is a governance provenance record — it is created by Governance actions and consumed by Audit and Replay contexts for verification.

The strongest argument **against aggregate status**: GovernanceDecision may be a **side-effect record** of governance transitions rather than an independent decision-making concept. If governance decisions are recorded *after* a governance action (e.g., "election approved"), the decision is not an aggregate root — it is a record emitted by the Governance aggregate during a state transition.

**Impact on aggregate inventory:** GovernanceDecision is WEAKER than originally assessed. It may be an entity within GovernanceAggregate or Audit, not a standalone aggregate. This depends on whether governance decisions are created as part of governance transitions (entity) or as independent governance actions (aggregate).

---

## 5. Question 4: Boundary Stress Test

### Scenario A: Remove GovernanceAggregate

| Invariant | Breaks? | Why |
|-----------|---------|-----|
| Transition sequence valid | ❌ No | ConstitutionalTransitionGuard Domain Service still enforces sequence |
| Preconditions satisfied | ❌ No | isPreconditionMet() Domain Service still checks preconditions |
| User has required role | ❌ No | Guard still checks roles |
| Suspension freezes capabilities | ❌ No | GovernanceRule specification still defines suspension policy |
| **Lifecycle state ownership** | **⚠️ Unclear** | State lives on `elections` table — would need to belong to an Election aggregate |

**Result:** Removing GovernanceAggregate breaks **no invariant**. The ConstitutionalTransitionGuard Domain Service continues to enforce all transition constraints. The lifecycle state remains on the `elections` table. This suggests GovernanceAggregate may not be a genuine aggregate — the invariants are protected by Domain Services and Specifications, not by an aggregate boundary.

### Scenario B: Remove GovernanceDecision

| Invariant | Breaks? | Why |
|-----------|---------|-----|
| Governance decisions recorded | ✅ Yes — no record of who decided what about which capability | The decision record would be lost |
| Decision attribution | ✅ Yes — no link between decision and decision-maker | Attribution requires the record |
| Decision capability scope | ✅ Yes — no link between decision and capability | Scope requires the record |

**Result:** Removing GovernanceDecision breaks **three invariants** — decision recording, attribution, and capability scope. However, these invariants could be satisfied by an entity within another aggregate (GovernanceAggregate, Audit) rather than requiring a standalone GovernanceDecision aggregate. The invariant protection does not prove independent aggregate status — it only proves the record must exist somewhere.

---

## 6. Revised Aggregate Inventory

| Concept | Type | Confidence (updated) | Rationale |
|---------|------|---------------------|-----------|
| **GovernanceAggregate** | **Reassessing** — may be REDUNDANT | **LOW** | Invariants are protected by Domain Services, not by aggregate boundary. Lifecycle state is an attribute of the Election, not a separate aggregate. Governance owns rules (policies, specifications), not state. |
| **GovernanceDecision** | **Entity (within another aggregate) or Persistent Record** | **LOW-MEDIUM** | Invariants require the record to exist but do not require a standalone aggregate. May be an entity within GovernanceAggregate (if it exists), Audit, or a standalone persistent record. |
| StateTransition | Domain Service | HIGH | No change — confirmed |
| GovernanceRule | Specification/Policy | HIGH | No change — confirmed |
| LifecycleState | **May be Entity within Election aggregate** | MEDIUM | State lives on `elections` table — may belong to Election, not Governance |
| Suspension | Policy (not entity/aggregate) | MEDIUM | Suspension is a governance overlay rule, not a state-owning concept |

---

## 7. Confidence Reassessment

| Concept | Previous Confidence | Revised Confidence | Reason |
|---------|-------------------|-------------------|--------|
| GovernanceAggregate | MEDIUM | **LOW** | Boundary stress test shows no invariant breaks. Lifecycle state is on Election table, not separate. Domain Services protect all invariants. |
| GovernanceDecision | MEDIUM | **LOW-MEDIUM** | Invariants require record to exist but do not require independent aggregate. Record could be entity within another aggregate. |
| StateTransition (DS) | HIGH | HIGH | Unchanged |
| GovernanceRule (Spec) | HIGH | HIGH | Unchanged |
| LifecycleState (entity) | HIGH | **Reassigned** | May be part of Election aggregate, not Governance |
| Suspension (entity) | MEDIUM | **Reassigned** | Policy/rule, not entity |

---

## 8. Summary

**Challenge review outcomes:**

1. **GovernanceAggregate is WEAKLY SUPPORTED.** The boundary stress test shows no invariants break if removed. ConstitutionalTransitionGuard (Domain Service) protects all transition invariants. Lifecycle state is stored on the `elections` table — it may be an Entity within an Election aggregate, not a separate GovernanceAggregate.

2. **GovernanceDecision is WEAKLY SUPPORTED as a standalone aggregate.** Evidence supports four possible interpretations (Governance aggregate, Audit aggregate, Replay aggregate, Persistent Record). Invariants require the record to exist but do not prove independent aggregate status.

3. **Constitutional Governance may own no aggregates.** It owns rules, policies, specifications, and domain services — but the state those rules govern (lifecycle state, governance decisions) may belong to other aggregates (Election, Audit).

4. **This is consistent with the pattern from 27B/27C:** Not every accepted bounded context contains a primary aggregate.

---

**Round 27D-ARB Constitutional Governance Challenge Review — READY FOR ARB DECISION**

**0-2 aggregate candidates. 0 confirmed after stress test. GovernanceAggregate LOW confidence (no invariant breaks). GovernanceDecision LOW-MEDIUM confidence (4 possible interpretations). Governance may be a rule-defining context with no primary aggregate — consistent with 27B/27C patterns.**
