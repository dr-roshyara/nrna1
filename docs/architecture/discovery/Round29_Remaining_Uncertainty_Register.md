# Round 29 — Remaining Uncertainty Register

**Date:** 2026-06-08

**Phase:** Strategic-to-Tactical Synthesis

**Status:** Final Synthesis Artifact

**Purpose:** Consolidate all remaining unknowns discovered through Rounds 17–28A. This register categorizes what remains unresolved by type and priority, providing the ARB with a complete picture of what has been discovered vs what remains for future phases.

---

## Debt Classification Framework

All remaining uncertainties are classified by type:

| Type | Definition | Resolution Phase |
|------|-----------|------------------|
| **Governance Debt** | Authority, delegation, or governance rule questions | Governance Review / Organizational |
| **Discovery Debt** | Repository investigation questions | Future Discovery Streams |
| **Model Refinement Debt** | Aggregate/context boundary refinements | Design / Tactical Discovery |
| **Design Debt** | Architecture/implementation questions | Design Phase (Round 30+) |

---

## Governance Debt (High Priority)

### D35: What Happens When Legitimacy = EXPIRED?

**Classification:** GOVERNANCE DEBT

**Question:** When ConstitutionalDecision determines legitimacy = EXPIRED, what are the consequences? Does this:
- Reverse the original governance decision? (Corrective)
- Block future actions? (Preventative)
- Provide advisory information only? (Observational)

**Evidence Sources:**
- ConstitutionalDecision records EXPIRED status (observed)
- No enforcement mechanism observed (gap)
- Consequences unresolved (D35)

**Priority:** HIGH STRATEGIC

**Why Unresolved:** Legitimacy consequences determine whether arbitration is corrective (challenge-supporting) or merely preventative/advisory.

**Recommended Resolution:** Governance review; may require stakeholder interviews.

**Impacts:** Arbitration context boundary, Challenge handling capability, Governance enforcement model.

---

### D36: Who Is Permitted to Invoke ConstitutionalArbitrationKernel?

**Classification:** GOVERNANCE DEBT

**Question:** ConstitutionalArbitrationKernel.decide() is a public method. Who/what is authorized to invoke it?
- Anyone? (Distributed challenge capability)
- Restricted authorities? (Organizational governance)
- Automatic invocation only? (No submission required)

**Evidence Sources:**
- ConstitutionalArbitrationKernel exists (observed)
- Public decide() method exists (observed)
- No invocation authorization observed (gap)
- No challenge submission mechanisms found (gap)

**Priority:** HIGH STRATEGIC

**Why Unresolved:** Invocation authority shapes whether system supports independent challenge mechanisms (distributed) or only official review (organizational).

**Recommended Resolution:** Code analysis of kernel call sites; governance review of authorization.

**Impacts:** Challenge handling, Arbitration invocation authority, Dispute resolution model.

---

### D37: How Is Legitimacy Determination Enforced?

**Classification:** GOVERNANCE DEBT

**Question:** When legitimacy is determined, what is the enforcement mechanism?
- Automatic blocking? (System-enforced)
- Officer-triggered? (Governance-enforced)
- Informational only? (Advisory)

**Evidence Sources:**
- ConstitutionalGovernanceDecision.isConstitutionallyValid() exists (observed)
- No enforcement side-effects observed (gap)
- Consequences of legitimacy status unclear (D35, D37)

**Priority:** HIGH STRATEGIC

**Why Unresolved:** Determines whether legitimacy determinations have operational teeth or are informational.

**Recommended Resolution:** Code tracing of decision enforcement; governance review.

**Impacts:** Arbitration consequences, Decision enforcement model, Challenge handling.

---

### ADG-2: Delegated Authority Governance Gaps

**Classification:** GOVERNANCE DEBT

**Question:** How is authority delegated within Constitutional Governance? Specifically:
- Can chief delegate transition authority to deputy?
- What are delegation scopes and limits?
- Are delegations reversible?

**Evidence Sources:**
- Chief and deputy roles observed (Stream 2)
- ADH-1 references authority hierarchy (gap)
- Delegation rules not explicitly documented

**Priority:** MEDIUM

**Why Unresolved:** Chief vs deputy authority distinction is operational but governance scope is unclear.

**Recommended Resolution:** Governance review; ADR archaeology.

**Impacts:** Authorization scope, Governance decision authority, Chief/deputy role boundaries.

---

### ADH-1: Authority Hierarchy Governance Gaps

**Classification:** GOVERNANCE DEBT

**Question:** What is the complete authority hierarchy and delegation model?
- Chief vs deputy authority split?
- What decisions are chief-only? What can be delegated?
- Override and escalation paths?
- Emergency authority protocols?

**Evidence Sources:**
- Chief and deputy roles observed (Stream 2)
- ADR-001, ADR-004 reference authority
- Complete hierarchy not documented (gap)
- GEO-3.2+ references emergency declarations (future)

**Priority:** MEDIUM

**Why Unresolved:** Authority structure exists but governance scope is incomplete.

**Recommended Resolution:** Governance review; organizational hierarchy documentation; stakeholder interviews.

**Affects:** GovernanceState (CG-1, CG-2), Authorization (AU-2), Arbitration (AR-1), all contexts requiring authorization.

---

## Discovery Debt (Repository Investigation)

### D39: Counting State Meaning Unresolved

**Classification:** DISCOVERY DEBT

**Question:** What is the business meaning of the "counting_complete" state in the election lifecycle?
- Is it a governance policy (publication gate) or operational state?
- Does it have independent decision ownership or just gate Results/Tallying publication?

**Evidence Sources:**
- Stream 3 confirmed counting is gating (observed)
- No counting process found (votes create results synchronously)
- Results are derived projection (Projection Test passed)
- State serves publication gate function (observed)

**Priority:** MEDIUM

**Why Unresolved:** Results/Tallying boundary depends on whether counting state creates independent decision ownership.

**Recommended Resolution:** Focused investigation of counting state semantics; governance review.

**Impacts:** Results/Tallying context boundary (C7 provisional), CG-2 invariant (state transitions).

---

### D42B: Verifiability Guarantee Ownership Unresolved

**Classification:** DISCOVERY DEBT

**Question:** Who owns the guarantee that votes are verifiable to voters?
- Verification context (identity trust extends to vote verification)?
- Voting context (vote recording enables verification)?
- Separate context needed?

**Evidence Sources:**
- Receipt hash exists (Vote aggregate owns this; VO-3 proven)
- Verifiability guarantee not explicitly owned (gap)
- D42B blocking VO-1/VO-3 boundary clarity

**Priority:** MEDIUM

**Why Unresolved:** Receipt hash existence (Vote) vs verifiability guarantee ownership (Unresolved).

**Recommended Resolution:** Focused investigation of verifiability semantics; boundary testing.

**Impacts:** Voting aggregate boundary (C6 provisional), VO-3 invariant (receipt hash), Trust/Voting relationship.

---

## Model Refinement Debt (Aggregate/Context Boundaries)

### ADC-1: Role Uniqueness and Exclusivity Rules

**Classification:** MODEL REFINEMENT DEBT

**Question:** What are the complete role assignment uniqueness and exclusivity rules?
- Can a user have multiple roles per election?
- Are some roles mutually exclusive?
- What is the enforcement mechanism?

**Evidence Sources:**
- RoleAssignment candidate aggregate identified (Round 27C)
- Current implementation suggests uniqueness (observed)
- Exclusivity rules not documented (gap)
- Affects AU-3 invariant (Role Assignments Per-Election)

**Priority:** MEDIUM

**Why Unresolved:** RoleAssignment boundary depends on whether role exclusivity creates consistency constraints.

**Recommended Resolution:** Code analysis of role constraints; business rule review.

**Impacts:** RoleAssignment aggregate definition (AU-3 provisional), Authorization invariants.

---

### ADC-2: Temporal Validity and Revocation Authority

**Classification:** MODEL REFINEMENT DEBT

**Question:** Do roles have temporal validity (start/end dates) and who can revoke them?
- Are roles time-bounded?
- Can they be revoked mid-election?
- What is the revocation authority?

**Evidence Sources:**
- RoleAssignment candidate aggregate identified (Round 27C)
- No temporal fields observed in current implementation
- Revocation authority not documented (gap)
- Affects AU-3 invariant (Role Assignments Per-Election)

**Priority:** MEDIUM

**Why Unresolved:** Temporal validity may require independent state management in RoleAssignment.

**Recommended Resolution:** Code analysis of role lifecycle; business rule review.

**Impacts:** RoleAssignment aggregate definition (AU-3 provisional), Authorization invariants.

---

### ADGR-1: Replay Session Governance and Operational Authority

**Classification:** MODEL REFINEMENT DEBT

**Question:** What are the governance scope and operational authority for ReplaySession?
- Who can invoke replay sessions?
- What are the invocation conditions?
- What happens after divergence detection?
- Is divergence actionable or advisory?

**Evidence Sources:**
- ReplaySession candidate aggregate identified (Round 27G)
- GovernanceReplayService exists but invocation mechanism unclear (gap)
- Governance consequences unresolved (relates to D35, D36)
- Affects GR-1, GR-2 invariants (Replay session provisional)

**Priority:** MEDIUM

**Why Unresolved:** ReplaySession boundary and decision ownership depend on governance invocation model.

**Recommended Resolution:** Code analysis of replay invocation; governance review; integration with D35, D36, D37.

**Impacts:** ReplaySession aggregate definition (GR-1, GR-2 provisional), Governance Evidence Replay context.

---

## Remaining Open Questions by Category

### Verification / Challenge Handling (3 Questions)

```
D35: Legitimacy consequences (corrective vs preventative)
D36: Arbitration invocation authority (distributed vs organizational)
D37: Legitimacy enforcement mechanism (automatic vs officer-triggered)
```

**Strategic Insight:** These three questions form a cluster. Resolution sequence:
1. D36 (Who can invoke?)
2. D35 (What are consequences?)
3. D37 (How are consequences enforced?)

---

### Governance Authority (2 Questions)

```
ADG-2: Delegated authority scoping (chief/deputy model)
ADH-1: Complete authority hierarchy (delegations, overrides, escalation)
```

**Strategic Insight:** ADH-1 is broader; ADG-2 is subset. Resolve ADH-1 first; ADG-2 follows.

---

### Results / Tallying Boundaries (1 Question)

```
D39: Counting state meaning (governance vs derived projection)
```

**Strategic Insight:** Results/Tallying context boundary (C7 provisional) depends on D39 resolution.

---

### Vote Verifiability (1 Question)

```
D42B: Verifiability guarantee ownership (Verification vs Voting vs new context)
```

**Strategic Insight:** Affects Voting aggregate boundary (C6 provisional) but lower priority than governance debts.

---

### Role Assignment Boundaries (2 Questions)

```
ADC-1: Role uniqueness and exclusivity rules
ADC-2: Temporal validity and revocation authority
```

**Strategic Insight:** Both affect RoleAssignment aggregate definition (AU-3 provisional). Resolve together.

---

### Replay Session Governance (1 Question)

```
ADGR-1: Replay session governance and operational authority
```

**Strategic Insight:** Depends on D35, D36, D37 resolution. Replay integration with governance is deferred.

---

## Uncertainty Prioritization Matrix

| Debt | Type | Priority | Blocks | Recommended Phase |
|------|------|----------|--------|-------------------|
| D35 | Governance | HIGH | Arbitration, Challenge | Governance Review |
| D36 | Governance | HIGH | Arbitration, Challenge | Code Analysis + Governance |
| D37 | Governance | HIGH | Arbitration, Challenge | Code Analysis + Governance |
| D39 | Discovery | MEDIUM | Results/Tallying boundary | Focused Discovery |
| D42B | Discovery | MEDIUM | Voting boundary | Focused Discovery |
| ADG-2 | Governance | MEDIUM | Delegation model | Governance Review |
| ADH-1 | Governance | MEDIUM | Authority hierarchy | Governance Review + ADR Archaeology |
| ADC-1 | Model Refinement | MEDIUM | RoleAssignment aggregate | Code + Business Review |
| ADC-2 | Model Refinement | MEDIUM | RoleAssignment aggregate | Code + Business Review |
| ADGR-1 | Model Refinement | MEDIUM | ReplaySession aggregate | Code Analysis + Governance |

---

## What Has Been Fully Resolved

**17 Core Domain Invariants** (TA-1 through AR-1) are discovered and stable.

**9 Bounded Contexts** are accepted (5 stable, 4 provisional).

**5 Aggregates** are identified (3 stable, 2 provisional).

**8 Core Decisions** (D1–D8) have clear ownership.

**Cross-context invariants** verified with no violations.

---

## What Remains for Future Phases

### Phase 30: Design Phase (Architecture & Tactical)
- Resolve governance debts (D35, D36, D37, ADG-2, ADH-1)
- Refine aggregate boundaries (ADC-1, ADC-2, ADGR-1)
- Design domain events, commands, query handlers
- Design technical architecture

### Phase 31+: Implementation Phase
- Resolve remaining discovery debts (D39, D42B)
- Build domain services and application layer
- Implement event handlers
- Deploy and verify

---

## Summary

**Discovered:** 
- 9 bounded contexts (strategic map)
- 5 aggregates (consistency boundaries)
- 17 domain invariants (business rules)
- 8 core decisions (ownership model)
- Complete cross-context dependency analysis

**Remaining:**
- 10 open questions (mostly governance)
- 2 provisional context boundaries (Results/Tallying, Voting)
- 2 provisional aggregates (RoleAssignment, ReplaySession)
- 6 provisional invariants (pending debt resolution)

**Status:** Tactical DDD Discovery Complete. Ready for Design Phase.

---

**Round 29 — Synthesis Complete**

**Four Approved Catalogs:**
- ✅ Bounded Context Catalog
- ✅ Aggregate Catalog  
- ✅ Decision Ownership Catalog
- ✅ Invariant Catalog

**One Final Register:**
- ✅ Remaining Uncertainty Register

**Total Discovery Artifacts:** 5

**ARB Status:** Ready for final synthesis review and closure of Round 29.

