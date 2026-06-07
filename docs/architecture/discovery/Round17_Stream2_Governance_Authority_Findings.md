# Round 17 — Stream 2: Governance & Authority Relationship Analysis — Evidence Findings

**Date:** 2026-06-07

**Phase:** Phase 1 — Evidence Gathering (Stream 2)

**Status:** Complete

**Investigation Scope:** Governance and Authority relationship and interaction

---

## Investigation Questions

**Primary Questions:**

1. How is authority granted?
2. How is authority constrained?
3. How is authority revoked?
4. What governance mechanisms affect authority?
5. What authority mechanisms affect governance decisions?
6. Where does Evidence influence Governance?
7. Where does Evidence influence Authority?
8. Are Governance and Authority observed together or separately?

---

## Governance & Authority Interaction Matrix

| Governance Artifact | Authority Artifact | Observed Interaction | Source | Tier |
|---|---|---|---|---|
| **ConstitutionalTransitionGuard** (4-check enforcement) | **ElectionConstitution.getAllowedRolesForAction()** | Guard checks if user has required roles for action. Role requirement defined in Constitution RULES. | ConstitutionalTransitionGuard.php:40-81 | Tier 2 |
| **ElectionConstitution.RULES** (defines all transitions) | **allowed_roles** field in each rule | Each rule specifies allowed_roles (chief, deputy, system, platform_admin). Guard validates against this. | ElectionConstitution.php:23-100 | Tier 2 |
| **ConstitutionalTransitionGuard.userHasAnyRole()** | **ElectionOfficer model** (represents officers with roles) | Guard queries user's roles/permissions to check authority. ElectionOfficer stores role field. | ConstitutionalTransitionGuard.php:94-120 | Tier 2 |
| **CapabilityPolicyLayer enum** (5-layer evaluation) | **Authorization layer** (5th layer) | Authorization layer evaluates role-based capability decisions. Sits last in policy priority chain. | CapabilityPolicyLayer.php:1-28 | Tier 2 |
| **TrustCapabilityPolicy** (evidence interpretation) | **ConstitutionalReviewPending** denial reason | Trust policy can deny capability with ConstitutionalReviewPending, escalating to governance review. | TrustCapabilityPolicy.php:66-71 | Tier 2 |
| **EvidenceCapabilityPolicy** (evidence gating) | **CapabilityDecision.prohibited()** | Evidence policy produces capability decisions that gate authority exercise. | EvidenceCapabilityPolicy.php:40-149 | Tier 2 |
| **ElectionCapabilityResolver** (layered evaluation) | **First denial = stop** | Resolver evaluates policies in priority order. Any denial (governance or authority) stops evaluation. | ElectionCapabilityResolver.php:28-76 | Tier 2 |
| **GovernanceDecision** (records decisions) | **capabilityType** field | Governance decisions are recorded per capability type. Links governance decisions to specific capabilities that authority exercises. | GovernanceDecision.php:15-80 | Tier 2 |
| **ConstitutionalTransitionGuard.validatePreconditions()** | **Preconditions layer** | Guard validates preconditions before allowing action. Preconditions enforce governance rules before authority can act. | ConstitutionalTransitionGuard.php:76-80 | Tier 2 |
| **Evidence** (from Stream 1 chain) | **TrustCapabilityPolicy** gates authority | Evidence flows through Trust evaluation → Capability decision → blocks/allows authority. | TrustCapabilityPolicy.php:44-100 | Tier 2 |

---

## Governance–Authority Relationship Inventory

### Relationship 1: Authority Is Constrained By Governance Rules

**Observed Relationship:**

ConstitutionalTransitionGuard enforces governance rules as preconditions to authority exercise. Authority (user roles) alone is insufficient; governance rules must also allow the action.

**Supporting Evidence:**

- ConstitutionalTransitionGuard.assertAllowed() (line 40-81) performs 4 sequential checks:
  1. Action defined in constitution (governance rule exists)
  2. State allows action (governance state constraint)
  3. User has required role (authority check)
  4. Preconditions met (governance rules verified)
- Guard throws InvalidTransitionException if ANY check fails (line 50-51, 59-62, 70-73, 79)
- Check 1-2, 4 are governance constraints; Check 3 is authority constraint
- All checks must pass sequentially (not optional)

**Observed Consequence:**

Authority is gated by governance rules. A user with the required role cannot act unless governance rules also permit the action in the current state with satisfied preconditions.

**Confidence:** Tier 2 (Implementation Evidence) — Code structure shows sequential enforcement

**Files:** app/Application/Election/Services/ConstitutionalTransitionGuard.php

---

### Relationship 2: Governance Rules Define What Authority Is Allowed

**Observed Relationship:**

ElectionConstitution.RULES registry defines, for each action, what roles are allowed to perform it and what preconditions must be met.

**Supporting Evidence:**

- ElectionConstitution RULES structure (line 23-120) has:
  - 'allowed_roles': array of roles (chief, deputy, system, platform_admin) 
  - 'allowed_states': array of states
  - 'preconditions': array of precondition names
  - 'target_state': the state transition result
- Examples (lines 25-100):
  - submit_for_approval: allowed_roles = [chief, deputy]
  - approve: allowed_roles = [platform_admin]
  - open_voting: allowed_roles = [chief]
- ConstitutionalTransitionGuard calls ElectionConstitution::getAllowedRolesForAction() (line 66) to get governance-defined roles
- No role-checking logic exists outside this governance registry

**Observed Consequence:**

Authority structure (who can do what) is defined centrally in governance rules, not distributed across code. Guard uses these definitions, not local logic.

**Confidence:** Tier 2 (Implementation Evidence) — Code shows centralized rule registry

**Files:** app/Domain/Election/Constitution/ElectionConstitution.php, app/Application/Election/Services/ConstitutionalTransitionGuard.php

---

### Relationship 3: Capability Decision Framework Gates Both Governance And Authority

**Observed Relationship:**

CapabilityPolicyLayer with 5 evaluation layers allows both governance policies and authority policies to gate capability decisions. Policies run in priority order; first denial stops evaluation.

**Supporting Evidence:**

- CapabilityPolicyLayer enum (line 5-28) defines 5 layers:
  1. Overlay (Operational Overlay)
  2. Trust (Constitutional Trust)
  3. Lifecycle (Lifecycle State)
  4. Preconditions (Constitutional Requirements)
  5. Authorization (Role Authorization)
- ElectionCapabilityResolver (line 28-76) evaluates policies in priority order
- Line 45-64: If any policy denies, evaluation stops (short-circuit)
- Line 66-70: If any policy grants, return decision
- Line 74: If no denials, default to authorized
- Trust, Lifecycle, Preconditions are governance layers
- Authorization is authority layer
- Authority layer runs LAST (priority 5), after all governance checks

**Observed Consequence:**

Governance policies (Trust, Lifecycle, Preconditions) can block capability before Authority policy ever evaluates. Authority cannot override governance denials.

**Confidence:** Tier 2 (Implementation Evidence) — Code shows layered policy evaluation

**Files:** app/Application/Election/Capabilities/CapabilityPolicyLayer.php, app/Application/Election/Services/ElectionCapabilityResolver.php

---

### Relationship 4: Evidence Influences Both Governance And Authority Decisions

**Observed Relationship:**

Evidence flows through governance evaluation (Trust layer) which can escalate to governance review, constraining what authority is allowed to exercise.

**Supporting Evidence:**

- TrustCapabilityPolicy (line 44-100) interprets evidence state:
  - Line 57: If no trust evidence (pre-authentication), abstain
  - Line 66-71: If overlay signals DEFER_TO_GOVERNANCE, deny with ConstitutionalReviewPending
  - Line 83-99: Match on evidence evaluation state:
    - SUFFICIENT_EVIDENCE → allow
    - INSUFFICIENT_EVIDENCE → deny(TrustDenied)
    - REVIEW_REQUIRED → deny(ConstitutionalReviewPending)
    - INCONCLUSIVE → deny(TrustEvaluationInconclusive)
- TrustCapabilityPolicy sits in Trust layer (layer 2), before Authorization layer (layer 5)
- EvidenceCapabilityPolicy (EvidenceCapabilityPolicy.php:40-149) similarly interprets evidence

**Observed Consequence:**

Within the examined implementation, evidence-based governance checks execute before authorization checks and may prevent authorization evaluation from occurring.

**Confidence:** Tier 2 (Implementation Evidence) — Code shows evidence → governance → capability decision chain

**Files:** app/Application/Election/Capabilities/Policies/TrustCapabilityPolicy.php, app/Application/Election/Capabilities/Policies/EvidenceCapabilityPolicy.php

---

### Relationship 5: Governance Decisions Reference Authority Capabilities

**Observed Relationship:**

GovernanceDecision entity records decisions per capabilityType, linking governance decisions to specific authority capabilities.

**Supporting Evidence:**

- GovernanceDecision (line 15-66) has:
  - Line 20: capabilityType field (string)
  - Line 34: record() static method takes capabilityType parameter
  - Line 78: capabilityType() accessor returns the capability type
- GovernanceDecision represents a formal governance decision about a capability
- Governance decisions are persisted and traceable to specific capabilities

**Observed Consequence:**

Governance decisions track which specific authority capabilities are subject to governance review. Governance decisions are capability-scoped.

**Confidence:** Tier 2 (Implementation Evidence) — Code shows governance decision structure

**Files:** app/Contexts/Governance/Domain/GovernanceDecision.php

---

### Relationship 6: Preconditions Layer Enforces Governance Rules Before Authority Acts

**Observed Relationship:**

ConstitutionalTransitionGuard validates preconditions defined in governance rules before allowing authority to act.

**Supporting Evidence:**

- ElectionConstitution RULES include 'preconditions' field (line 28, 35, 72, 97)
- Examples:
  - submit_for_approval preconditions: ['timezone_set']
  - approve preconditions: ['capacity_eligibility']
  - complete_administration preconditions: ['has_posts', 'has_voters', 'has_chief']
- ConstitutionalTransitionGuard.validatePreconditions() (line 76-80) is called for every action
- If preconditions fail, InvalidTransitionException is thrown (never reaches authority evaluation)

**Observed Consequence:**

Governance preconditions are enforced at the application boundary, before authority policy evaluation. Preconditions enforce governance rules about state and context, not roles.

**Confidence:** Tier 2 (Implementation Evidence) — Code shows precondition enforcement

**Files:** app/Domain/Election/Constitution/ElectionConstitution.php, app/Application/Election/Services/ConstitutionalTransitionGuard.php

---

### Relationship 7: Governance And Authority Are Observed Separately

**Observed Relationship:**

Governance-related artifacts and authority-related artifacts were observed in different implementation locations and participate in shared enforcement flows.

**Supporting Evidence:**

- Governance artifacts:
  - ElectionConstitution (Domain layer) — defines rules
  - ConstitutionalTransitionGuard (Application layer) — enforces rules
  - CapabilityPolicyLayer.Trust, Lifecycle, Preconditions — governance policies
  - GovernanceDecision (Governance context) — records decisions
  
- Authority artifacts:
  - ElectionOfficer (Model) — represents officers
  - Role strings in RULES (chief, deputy, system)
  - CapabilityPolicyLayer.Authorization — authority policy
  - userHasAnyRole() method — authority checking

- Code paths:
  - Governance-related: Rule checking, precondition validation, state constraints
  - Authority-related: Role checking, user permission checking

- Shared integration point:
  - CapabilityPolicyLayer and ElectionCapabilityResolver
  - ConstitutionalTransitionGuard chains checks

**Observed Consequence:**

Governance and Authority are logically distinct concerns with separate implementations but integrated enforcement. Governance is "what can happen"; Authority is "who can make it happen".

**Confidence:** Tier 2 (Implementation Evidence) — Code structure shows separation and integration

**Files:** app/Domain/Election/Constitution/, app/Application/Election/, app/Models/

---

## Observed Relationship Chain

The Stream 1 chain is now extended:

```
Evidence
    ↓
Trust Evaluation (TrustPolicyEvaluator)
    ↓
Trust Capability Policy (Governance layer 2)
    ↓
Other Governance Policies (Lifecycle, Preconditions)
    ↓
Authorization Policy (Authority layer 5)
    ↓
Capability Decision (Permit/Deny)
    ↓
Authority Exercise Allowed/Blocked
```

**Observation:** Evidence → Governance → Authority flow. Evidence influences governance decisions. Governance decisions constrain authority exercise.

---

## Hypothesis Updates

### H4: Constitutional Rules Form Unified Domain

**Previous Status:** Open

**Evidence Found (Tier 2):**
- ElectionConstitution.RULES is a centralized registry (not distributed)
- All transitions defined in single location
- All preconditions defined in single location
- All allowed roles defined in single location

**Updated Status:** **Strengthening**

**Rationale:** Evidence supporting centralized management of constitutional rules has strengthened. The relationship between centralization and domain structure remains unresolved.

---

### H5: Authority is Constrained by Governance

**Previous Status:** Strengthening

**Evidence Found (Tier 2):**
- ConstitutionalTransitionGuard enforces 4-check sequence
- Governance checks (1-2, 4) happen before authority check (3)
- Any governance denial prevents authority check execution
- Authority alone is insufficient; governance rules must also allow

**Updated Status:** **Strengthening**

**Rationale:** Code structure shows governance-related checks precede and gate authority checks within the examined implementation.

---

### H6: Governance is Exercised Through Authority

**Previous Status:** Strengthening

**Evidence Found (Tier 2):**
- ConstitutionalTransitionGuard executes governance rules but also checks authority
- Authority (role) is necessary but not sufficient
- Governance rules define what authority is allowed
- Authority is the mechanism through which governance rules are enforced

**Updated Status:** **Strengthening**

**Rationale:** Code structure shows governance rules are enforced through authority checking within the examined implementation.

---

### H14: Governance and Authority Are Tightly Integrated

**Previous Status:** Open

**Evidence Found (Tier 2):**
- ConstitutionalTransitionGuard chains governance and authority checks
- CapabilityPolicyLayer integrates governance policies and authority policy
- ElectionConstitution defines both governance rules and authority requirements
- No separation between governance enforcement and authority enforcement

**Updated Status:** **Strengthening**

**Rationale:** Governance-related checks and authority-related checks are observed in shared enforcement mechanisms. Observed interactions occur within enforcement mechanisms. Additional investigation is required to understand relationships among definition mechanisms.

---

## New Discovery Debt

### D14: What Exactly Are "Preconditions" and How Are They Enforced?

**Question:** ElectionConstitution defines preconditions for actions (has_posts, has_voters, timezone_set, etc.) but the enforcement code is not yet examined. How are preconditions verified?

**Why Unresolved:**
- ConstitutionalTransitionGuard calls validatePreconditions() but logic not examined
- Preconditions appear to be governance rules but enforcement mechanism unclear

**Priority:** Medium

**Recommended Discovery:** Investigate validatePreconditions() logic in ConstitutionalTransitionGuard or find where precondition validators are defined

**Source:** Stream 2 (ConstitutionalTransitionGuard.php:76-80)

---

### D15: How Is Officer Authority Managed (Appointment, Revocation, Term Limits)?

**Question:** ElectionOfficer model has appointed_by, term_ends_at, status fields but the appointment/revocation workflow is not examined. Who appoints officers? How is authority revoked?

**Why Unresolved:**
- Officer model exists but lifecycle not examined
- Governance/Authority relationship for officer management unclear

**Priority:** Medium

**Recommended Discovery:** Examine officer appointment/revocation workflows, ApprovalProcessState class, ElectionVoterController for context

**Source:** Stream 2 (ElectionOfficer.php, grep results)

---

### D16: What Role Does "Legitimacy" Play in Governance Decisions?

**Question:** GovernanceDecision has a Legitimacy field but role of legitimacy in decision-making not examined.

**Why Unresolved:**
- GovernanceDecision structure shows Legitimacy is tracked
- Relationship between Governance and Legitimacy unclear

**Priority:** Medium

**Recommended Discovery:** Investigate during Stream 2 deepening or later stream

**Source:** Stream 2 (GovernanceDecision.php:21)

---

### D17: How Do Governance Policies Integrate With The Capability Framework?

**Question:** CapabilityPolicyLayer includes Trust, Lifecycle, Preconditions as governance-related layers, but specific policy implementations not examined.

**Why Unresolved:**
- Layer structure identified but policy implementations not read
- How policies map to CapabilityDecision not examined

**Priority:** Medium

**Recommended Discovery:** Investigate LifecycleCapabilityBaselinePolicy and PreconditionsCapabilityPolicy (if they exist)

**Source:** Stream 2 (CapabilityPolicyLayer.php, ElectionCapabilityResolver.php)

---

## Stream 2 Completion Status

**✅ Complete**

Deliverables Produced:

✅ Governance & Authority Interaction Matrix (10 interactions documented)

✅ Governance–Authority Relationship Inventory (7 relationships with evidence)

✅ Observed Relationship Chain (Evidence → Trust → Governance → Authority)

✅ Hypothesis Updates (H4-H6, H14 updated)

✅ Discovery Debt (4 new items logged: D14-D17)

---

## Summary of Stream 2 Findings

**Observed Characteristics:**

1. **Implemented Separately** — Distinct code locations and definition mechanisms
2. **Participate in Shared Flows** — Governance checks and authority checks execute within the same evaluation frameworks
3. **Sequenced in Execution** — Within the examined implementation, governance-related checks (state, preconditions, evidence) execute before authorization checks
4. **Mutually Constraining** — Authority (roles) alone is insufficient; governance rules must also permit
5. **Evidence-Participated** — Evidence flows through governance layer before authorization is evaluated

**Observed Execution Flows:**

Stream 1 discovered: Evidence → Trust → Capability Decision

Stream 2 documents: Evidence → Trust (Governance) → Other Governance Policies → Authorization (Authority) → Capability Decision

**Unresolved Relationships:**

The ownership and structural relationship between Governance and Authority concepts remains unresolved. Both participate in shared implementation flows but the architectural meaning of this coupling is not yet determined.

---

**Stream 2 Status: READY FOR ARB REVIEW**

