# Round 27D-ARB — Governance Decision Ownership Review

**Date:** 2026-06-08

**Phase:** Tactical DDD — Decision Ownership Review (Pre-Acceptance)

**Status:** Complete — Awaiting ARB Decision

---

## 1. Purpose

Resolve whether Constitutional Governance owns an aggregate by examining decision ownership rather than implementation structure. This review explicitly ignores database tables, class locations, and persistence structures — it evaluates only domain-level decision ownership.

---

## 2. Q1: Who Owns Lifecycle Decisions?

### Decision Matrix

| Decision | Current Evidence | Possible Owners | Most Likely Owner | Confidence |
|----------|----------------|----------------|-------------------|------------|
| **Start/Submit Election** | ElectionConstitution.RULES defines submit_for_approval transition; allowed_roles: [chief, deputy]; Governance owns transition definition | Governance (rule defines it); Election (state changes) | **Governance** — the rule defines what actions are legal; the Election executes the action | MEDIUM |
| **Approve Election** | ElectionConstitution.RULES defines approve transition; allowed_roles: [platform_admin]; preconditions: [capacity_eligibility] | Governance (rule defines approval criteria); Platform Admin (the decision-maker exercising authority defined by Governance) | **Governance** — Governance defines who can approve and under what conditions. The approval decision itself is made by a Platform Admin, but the *legitimacy* of that decision comes from Governance rules. | MEDIUM |
| **Suspend Election** | ElectionConstitution.RULES defines suspend transition; allowed from all lifecycle states; allowed_roles: [chief, platform_admin] | Governance (rule defines suspension authority); Chief/Platform Admin (exercises authority) | **Governance** — Suspension is explicitly defined as an "operational governance overlay" (ADR-003). The overlay concept is a governance decision. | MEDIUM-HIGH |
| **Resume Election** | ElectionConstitution.RULES defines resume transition; allowed_roles: [chief, platform_admin]; engine re-derives state from constitutional facts | Governance (rule defines resume conditions) | **Governance** — Resume authority and pre-suspension state restoration are governance concerns. | MEDIUM |
| **Close Election** | ElectionConstitution.RULES defines close_voting transition; allowed_roles: [chief, deputy] | Governance (rule defines closing authority); Chief/Deputy (executes) | **Governance** — When and by whom voting can be closed is a governance rule. | MEDIUM |
| **Archive Election** | ElectionConstitution.RULES defines archive transition; allowed_roles: [chief, deputy] | Governance (rule defines archiving authority) | **Governance** — Archiving is the final lifecycle transition, defined by Governance. | MEDIUM |

### Assessment

All lifecycle decisions are owned by **Governance** — not by the Election itself nor by Voting. Governance defines:
- What states exist (12 constitutional states)
- Which transitions are legal (13 defined transitions)
- Who can perform each transition (role requirements)
- Under what conditions (preconditions)

The Election executes transitions. Voting respects lifecycle state. Authorization gates based on lifecycle state. But **Governance owns the rules that make transitions legitimate**.

---

## 3. Q2: Who Owns Transition Legitimacy?

| Decision | Current Evidence | Possible Owners | Most Likely Owner | Confidence |
|----------|----------------|----------------|-------------------|------------|
| Is this transition legal? | ConstitutionalTransitionGuard checks action defined, state allows, user has role, preconditions met | Governance (ElectionConstitution.RULES defines legality); ConstitutionalTransitionGuard (enforces); Election (state is current) | **Governance** — The Constitution defines what legal means. The Guard enforces. The Election is the subject. | HIGH |
| Is this precondition satisfied? | isPreconditionMet() checks operational facts against constitutional requirements | Governance (precondition definitions); Election (data is current) | **Governance** — What counts as a "satisfied" precondition is defined by Governance rules. | HIGH |
| Does this user have the required role? | ElectionConstitution.RULES defines allowed_roles; ConstitutionalTransitionGuard checks | Governance (role definitions); Authorization (role assignments) | **Governance** — Role definitions are Governance-owned (confirmed 27C-ARB). Authorization owns assignments. | HIGH |

### Assessment

Transition legitimacy is **definitively owned by Governance**. The rules about what makes a transition legal are defined in ElectionConstitution.RULES, which is the central Governance artifact. ConstitutionalTransitionGuard enforces those rules, but the rules themselves are Governance's domain.

---

## 4. Q3: Can Constitutional Governance Make a Business Decision That No Other Context Can Make?

### Unique Decision Inventory

| Decision | Owned By | Also Claimed By | Can Any Other Context Make This? |
|----------|----------|----------------|----------------------------------|
| **What states exist in the election lifecycle?** | **Governance** | — | No — this is the core Governance definition |
| **Which transitions are allowed between states?** | **Governance** | — | No — the Constitution is Governance's domain |
| **What preconditions are required for each transition?** | **Governance** | Authorization (may evaluate) | Authorization evaluates preconditions but does not define them |
| **What roles are allowed to perform each action?** | **Governance** | Authorization (may evaluate) | Authorization evaluates roles but does not define them |
| **Should this election be suspended?** | **Governance** (overlay) | — | Suspension is a governance overlay — no other context claims this |
| **What is the legitimate lifecycle path?** | **Governance** | — | Governance defines the progression; other contexts follow it |
| **Is this governance decision valid?** | **Governance** | Arbitration (evaluates) | Arbitration evaluates constitutional validity but Governance defines what valid means |

### Unique Decisions Summary

| Decision | Uniqueness | Confidence |
|----------|-----------|------------|
| State definition | ✅ Governance only | HIGH |
| Transition rules | ✅ Governance only | HIGH |
| Precondition definition | ✅ Governance only | HIGH |
| Role definitions | ✅ Governance only (confirmed 27C-ARB) | HIGH |
| Suspension authority | ✅ Governance only | MEDIUM-HIGH |
| Governance decision validity | ⚠️ Shared with Arbitration | MEDIUM |

Constitutional Governance owns at least **5 unique business decisions** that no other accepted context can make. These decisions are not trivial policies — they are the constitutional backbone of the election lifecycle.

---

## 5. Governance Aggregate Necessity Assessment

### Does Governance Own Unique Decisions?

**Yes.** 5 unique decisions confirmed. No other context claims state definition, transition rules, precondition definitions, role definitions, or suspension authority.

### Do Those Decisions Require State?

**Yes.** The following state must exist and be consistent:
- Current lifecycle state (one of 12 values)
- Suspension status (active/inactive)
- Pre-suspension state (for resume)
- Governance decisions made per capability

### Do Those Decisions Require Transactional Consistency?

**Yes.** Every state transition requires atomic:
- Validation against current state
- Precondition satisfaction check
- Role authorization check
- State update

Suspension additionally requires:
- Atomic freeze of capabilities
- Pre-suspension state recording

### Would Any Invariant Break Without an Aggregate?

**Yes.** If lifecycle state were not consistently managed with transition rules:
- A transition could update state without precondition validation (if service boundary broken)
- Suspension could set state without freezing capabilities (if overlay boundary broken)
- Resume could return to an invalid state (if pre-suspension state not preserved)

These are currently protected by ConstitutionalTransitionGuard (Domain Service), but the Domain Service validates against state that must be owned somewhere. The question is whether that state is owned by a Governance aggregate or by an Election aggregate.

### Revised Assessment from Challenge Review

The previous challenge review concluded "no invariants break if removed" because the ConstitutionalTransitionGuard protects them regardless of where state lives. However, this is a **false negative** in the stress test — the invariants don't break because the Domain Service enforces them, but the **state and rules that the Domain Service reads must be consistently owned**.

The correct question is not "does the invariant break?" but "who owns the state and rules that make the invariant meaningful?" That question points to Governance.

---

## 6. Revised Aggregate Conclusion

| Concept | Type | Confidence | Rationale |
|---------|------|------------|-----------|
| **GovernanceAggregate** (lifecycle state + transition authority + suspension) | **Aggregate** | **MEDIUM-HIGH** | Governance owns 5 unique decisions requiring persistent state and transactional consistency. Previous challenge review incorrectly focused on implementation stress test rather than decision ownership. Lifecycle state is Governance's concept — the fact that it's stored on an `elections` table column is implementation detail, not domain ownership. |
| **GovernanceDecision** | **Entity (within GovernanceAggregate) or Persistent Record** | **MEDIUM** | Unique decision ownership is weaker than GovernanceAggregate. May be an entity or record rather than standalone aggregate. Four possible classifications remain open (ADG-2). |
| StateTransition | Domain Service | HIGH | Enforces Governance rules — no change |
| GovernanceRule | Specification/Policy | HIGH | ElectionConstitution.RULES is Governance's core artifact |
| Suspension | Entity (within GovernanceAggregate) | MEDIUM | Overlay state modifies GovernanceAggregate |
| LifecycleState | Entity (within GovernanceAggregate) | HIGH | Core state tracked by GovernanceAggregate |

---

## 7. Summary

**Decision ownership analysis confirms: Constitutional Governance owns at least 5 unique business decisions that no other context can make.** These decisions require persistent state (lifecycle, suspension, governance records) and transactional consistency (state+transition must be atomic). Implementation storage location (elections table) does not determine domain ownership.

| Aggregate | Previous Confidence | Revised Confidence | Primary Evidence |
|-----------|-------------------|-------------------|-----------------|
| GovernanceAggregate | MEDIUM → LOW (challenge) | **MEDIUM-HIGH** | 5 unique decisions; state consistency requirement; decision ownership analysis |
| GovernanceDecision | MEDIUM → LOW-MEDIUM (challenge) | **MEDIUM** | Four possible classifications remain; decision ownership weaker |

---

**Round 27D-ARB Governance Decision Ownership Review — READY FOR ARB DECISION**

**GovernanceAggregate restored to MEDIUM-HIGH confidence based on decision ownership. GovernanceDecision remains MEDIUM with four open classifications (ADG-2). Decision ownership analysis provides stronger evidence than implementation stress test. Previous challenge review corrected.**
