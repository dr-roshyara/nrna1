# Round 27C — Authorization Aggregate Discovery

**Date:** 2026-06-08

**Phase:** Tactical DDD — Aggregate Discovery (Context 3 of 9)

**Status:** Complete — Awaiting ARB Review

---

## 1. Investigation Scope

**Candidate Context:** Authorization

**Acceptance Status:** ACCEPTED (Round 25)
**Boundary Stability:** STABLE

**Evidence Sources:** ADR-001 (Constitutional Capability Sovereignty), ADR-004 (Deterministic Capability Resolver), Stream 2 (Governance & Authority), ElectionConstitution.php, ConstitutionalTransitionGuard.php, ElectionOfficer model, ConstitutionalArbitrationKernel (limited overlap), CapabilityPolicyLayer, Round 22 Relationship Analysis (R3/R5).

---

## 2. Context Legitimacy

Authorization owns the decision: **"Is this user allowed to perform this action in this context?"** This is a unique business decision that no other context owns. Unlike Eligibility (computational decision model), Authorization has persistent state — role assignments, permission definitions, and capability snapshots. It is a state-owning context, likely to contain one or more aggregates.

---

## 3. Concept Inventory

### Concept 1: Capability

**Source:** ADR-001, ADR-004, ElectionConstitution.php, ConstitutionalTransitionGuard.php

**Description:** The allowed/denied status of a specific action for a specific user in a specific election state. Capabilities are computed deterministically — same inputs always produce same outputs.

**Discovered evidence:**
- CapabilityPolicyLayer defines 5 layers: Overlay, Trust, Lifecycle, Preconditions, Authorization — the Authorization layer evaluates role-based decisions
- ElectionCapabilityResolver evaluates in priority order — first denial stops evaluation
- Capabilities are "immutable snapshots" returned to frontend — never inferred or re-derived
- CapabilitySnapshot concept exists in frontend as read-only derived state

**Core attributes:** Action ID, allowed/denied status, denial reason, evaluation timestamp

---

### Concept 2: Role

**Source:** ElectionConstitution.RULES (`allowed_roles`), ElectionOfficer model, ADR-001

**Description:** Named authority levels (chief, deputy, platform_admin, system, voter, member) that define what actions a user can perform. Roles are defined centrally in ElectionConstitution.RULES and checked by ConstitutionalTransitionGuard.userHasAnyRole().

**Discovered evidence:**
- Each constitutional rule specifies allowed_roles
- Roles are checked via Spatie Permission (global) and ElectionOfficer queries (election-specific)
- Special case: 'system' role for automatic transitions (auto_submit)
- Role assignment tracks appointed_by, term_ends_at, status

**Core attributes:** Role name, scope (global vs. election-specific), status (active/expired), assignment authority, term limits

---

### Concept 3: Permission

**Source:** Spatie Permission library, ElectionConstitution.RULES

**Description:** The association between a role and an allowed action. Permissions are defined in the constitution and enacted through role assignments.

**Discovered evidence:**
- Permissions are not stored as separate records — they are defined by ElectionConstitution.RULES
- Each rule entry implicitly defines a permission (role X can perform action Y in state Z)
- Spatie Permission provides global role checking
- ElectionOfficer provides election-specific role checking

---

### Concept 4: Precondition

**Source:** ElectionConstitution.RULES (`preconditions` field), ConstitutionalTransitionGuard.isPreconditionMet()

**Description:** Conditions that must be satisfied before an action is allowed. Preconditions are governance rules that reference operational data.

**Discovered evidence:**
- Preconditions include: has_posts, has_voters, has_chief, has_approved_candidates, voting_window_defined, timezone_set, capacity_eligibility
- Preconditions are checked by ConstitutionalTransitionGuard after role checks
- Capacity_eligibility has a stub implementation (D21)

**Core attributes:** Precondition name, evaluation result (met/unmet)

---

### Concept 5: ElectionOfficer

**Source:** ElectionOfficer model, ConstitutionalTransitionGuard.userHasAnyRole()

**Description:** The assignment of a role to a specific user for a specific election. Officers can be chief, deputy, platform_admin, or other roles.

**Discovered evidence:**
- ElectionOfficer tracks: role, status ('active'), appointed_by, term_ends_at
- Officers are checked for role authorization in ConstitutionalTransitionGuard
- Officer status must be 'active' for role check to pass
- Term limits are tracked but enforcement not observed

**Core attributes:** Officer ID, election ID, role, status, appointed_by, term_ends_at

---

### Concept 6: SovereignOverlayIdentifier

**Source:** OverlayIdentifier enum (from governance/replay infrastructure)

**Description:** Identifies overlay signals that can influence capability decisions. Overlays can escalate to governance review.

---

## 4. Aggregate Candidate Evaluation

### Candidate A: RoleDefinition

| Test | Assessment |
|------|------------|
| **Business decision owned** | "What roles exist and what actions are they allowed to perform?" |
| **Invariant protected** | Role definitions must be consistent (one definition per role per action) |
| **Transactional consistency required** | Medium — role definitions change rarely and through governance process |
| **If split** | Inconsistent role definitions across different code paths |
| **Alternative classification** | **Specification / Policy** — Role definitions are rules, not state. They are evaluated at runtime, not modified transactionally. ElectionConstitution.RULES is a static definition, not an aggregate with lifecycle. |

**Evidence for aggregate status:**
- Roles are the central concept of authorization
- Change requires governance process (constitutional rules)

**Evidence against aggregate status:**
- Role definitions are static code arrays (ElectionConstitution.RULES), not runtime-created records
- No lifecycle — roles are defined at deployment time, not created/modified through application operations
- Role definitions do not require transactional consistency (they are re-deployed, not transactionally updated)

**Result: RECLASSIFY — Specification/Policy.** Role definitions are rules evaluated at runtime, not aggregates with persistent state and lifecycle.

---

### Candidate B: RoleAssignment (ElectionOfficer)

| Test | Assessment |
|------|------------|
| **Business decision owned** | "Which user has which role for which election?" |
| **Invariant protected** | One active chief per election; one user cannot have conflicting roles; role assignment requires governance authority |
| **Transactional consistency required** | HIGH — role assignment must be atomic with officer creation and status |
| **If split** | Role assignment could become inconsistent with officer authorization — active role without active officer status |
| **Alternative classification** | **AGGREGATE** — RoleAssignment has persistent state (ElectionOfficer records), clear lifecycle (created → active → expired/revoked), and invariants (one active chief per election, status must be active for authorization) |

**Evidence for aggregate status:**
- ElectionOfficer model stores persistent assignment records
- Clear lifecycle: appointment → active → term end or revocation
- Invariants: one active chief per election, status must be active
- RoleAssignment changes are distinct business actions (appoint, revoke, term expiration)
- Transactional consistency: role assignment + officer status + authorization must be consistent

**Evidence against aggregate status:**
- Officer appointment/revocation workflow not fully examined (D28/D29)
- Term expiration enforcement not observed

**Result: AGGREGATE (MEDIUM confidence)** — RoleAssignment is the primary state-owning concept within Authorization.

---

### Candidate C: CapabilityResolution

| Test | Assessment |
|------|------------|
| **Business decision owned** | "Is this user allowed to perform this action in this context?" |
| **Invariant protected** | Same inputs → same outputs (deterministic); no frontend inference |
| **Transactional consistency required** | N/A — CapabilityResolution is stateless computation |
| **If split** | N/A — stateless |
| **Alternative classification** | **Domain Service** — CapabilityResolution is a pure function that takes role definitions, role assignments, lifecycle state, and user identity, and produces a capability decision. It owns no persistent state and requires no transactional consistency boundary. |

**Evidence for Domain Service:**
- ADR-004 explicitly documents resolver as deterministic pure function
- "Same inputs always produce same outputs"
- "No side effects, no database calls, no global state"
- Resolver does not own state — it evaluates state owned by other concepts

**Result: Domain Service — not an aggregate.**

---

### Candidate D: CapabilitySnapshot

| Test | Assessment |
|------|------------|
| **Business decision owned** | None — snapshots are output of capability resolution, not input |
| **Invariant protected** | Snapshot must be point-in-time accurate |
| **Transactional consistency required** | N/A — snapshot is created after resolution, not during |
| **If split** | N/A — no state to protect |
| **Alternative classification** | **Value Object** — CapabilitySnapshot is an immutable representation of a resolution result. It is created, passed to the frontend, and not modified. |

**Result: Value Object — not an aggregate.**

---

### Candidate E: PreconditionEvaluation

| Test | Assessment |
|------|------------|
| **Business decision owned** | "Are all preconditions satisfied for this action?" |
| **Invariant protected** | Precondition evaluation must be consistent with current election state |
| **Transactional consistency required** | N/A — preconditions are evaluated at runtime against current state |
| **If split** | N/A — stateless evaluation |
| **Alternative classification** | **Specification/Policy** — Preconditions are rules evaluated at runtime. They do not own state and do not require their own consistency boundary. |

**Result: Specification/Policy — not an aggregate.**

---

## 5. Aggregate Boundary Validation Matrix

| Candidate | Owns Decision? | Owns State? | Invariant Protection | Aggregate? | Reclassification |
|-----------|---------------|-------------|---------------------|------------|-----------------|
| RoleDefinition | ✅ But policy rules | ❌ Static code | ⚠️ Deployed, not transacted | **NO** | Specification/Policy |
| **RoleAssignment** | **✅ Assign roles** | **✅ Yes** | **✅ Officer status, uniqueness** | **YES** | **Aggregate** (MEDIUM) |
| CapabilityResolution | ✅ Core auth decision | ❌ Stateless | ✅ Determinism | **NO** | Domain Service |
| CapabilitySnapshot | ❌ None | ❌ Immutable | ❌ Output only | **NO** | Value Object |
| PreconditionEvaluation | ❌ Policy rules | ❌ Stateless | ❌ Evaluated at runtime | **NO** | Specification/Policy |

---

## 6. Decision Ownership Review

| Decision | Owned By | Classification |
|----------|----------|---------------|
| What roles exist? | **RoleDefinition** | Specification/Policy (ElectionConstitution.RULES) |
| What actions are allowed per role? | **RoleDefinition** | Specification/Policy |
| Which user has which role for which election? | **RoleAssignment** | **Aggregate** |
| Is this user allowed to perform this action? | **CapabilityResolution** | Domain Service |
| Is this capability snapshot accurate? | **CapabilitySnapshot** | Value Object |
| Are preconditions satisfied? | **PreconditionEvaluation** | Specification/Policy |

---

## 7. Invariant Analysis

| Invariant | Owned By | Type |
|-----------|----------|------|
| One active chief per election | RoleAssignment | Uniqueness |
| Role assignment requires governance authority | RoleAssignment | Auditing |
| Officer status must be active for authorization | RoleAssignment | Policy |
| Same inputs → same capability outputs | CapabilityResolution | Determinism |
| Frontend never re-derives capabilities | CapabilityResolution | Architectural |
| Preconditions must be satisfied before action allowed | PreconditionEvaluation | Policy |

---

## 8. Consistency Boundary

```
RoleAssignment Aggregate (root)
    │
    ├── AssignmentId (VO)
    ├── UserId (VO)
    ├── ElectionId (VO)
    ├── Role (VO) — chief / deputy / platform_admin / system / voter / member
    ├── Status (VO) — active / expired / revoked
    ├── AppointedBy (VO)
    ├── TermEndsAt (VO, optional)
    └── CreatedAt (VO)
```

**Transactional boundary:** RoleAssignment must be consistent within a single transaction. Assignment creation, status change, and role assignment must be atomic. The same user cannot be simultaneously assigned conflicting roles.

**Note:** CapabilityResolution (Domain Service) reads from RoleAssignment but does not modify it. The Domain Service and Aggregate are separate — the Domain Service has no transactional boundary because it owns no state.

---

## 9. Revised Aggregate Inventory

| Concept | Type | Confidence | Rationale |
|---------|------|------------|-----------|
| **RoleAssignment** (ElectionOfficer) | **Aggregate** | **MEDIUM** | Owns persistent state (officer assignments), clear lifecycle, invariants (uniqueness, active status), transactional boundary |
| CapabilityResolution | Domain Service | HIGH | Stateless deterministic resolver — pure function |
| RoleDefinition | Specification/Policy | HIGH | Static rule definitions in ElectionConstitution.RULES |
| PreconditionEvaluation | Specification/Policy | HIGH | Runtime evaluation of governance rules |
| CapabilitySnapshot | Value Object | HIGH | Immutable output of resolution |

---

## 10. Aggregate Discovery Debt

| Debt | Question | Priority |
|------|----------|----------|
| ADC-1 | What is the complete lifecycle of RoleAssignment — appointment, activation, term tracking, revocation? Current evidence shows ElectionOfficer fields (appointed_by, term_ends_at, status) but the appointment/revocation workflow is partially understood (D28/D29). | HIGH |
| ADC-2 | Does RoleAssignment have any aggregate-level invariants beyond "one active chief per election" and "status must be active"? Are there constraints on who can appoint (e.g., only existing chiefs can appoint deputies)? | MEDIUM |
| ADC-3 | Should RoleDefinition be formalized as a Specification/Policy concept, or is its current representation as a static code array sufficient for the domain model? | LOW |

---

## 11. Confidence Assessment

| Concept | Evidence Strength | Decision Ownership | Boundary Clarity | Overall |
|---------|-----------------|-------------------|-----------------|---------|
| **RoleAssignment** (aggregate) | MEDIUM | MEDIUM | MEDIUM | **MEDIUM** |
| CapabilityResolution (DS) | HIGH | HIGH | HIGH | HIGH |
| RoleDefinition (Spec/Policy) | HIGH | N/A | HIGH | HIGH |
| PreconditionEvaluation (Spec/Policy) | HIGH | N/A | HIGH | HIGH |
| CapabilitySnapshot (VO) | HIGH | N/A | HIGH | HIGH |

---

## 12. Summary

| Concept | Type | Confidence |
|---------|------|------------|
| **RoleAssignment** | **Aggregate** | MEDIUM |
| CapabilityResolution | Domain Service | HIGH |
| RoleDefinition | Specification/Policy | HIGH |
| PreconditionEvaluation | Specification/Policy | HIGH |
| CapabilitySnapshot | Value Object | HIGH |

**Key finding:** Authorization has one clear aggregate candidate — RoleAssignment (ElectionOfficer). Its core business decision (capability resolution) is a stateless Domain Service reading from RoleAssignment state and RoleDefinition policies. The deterministic resolver (ADR-004) confirms that authorization is fundamentally a computational decision, but unlike Eligibility, Authorization does own persistent state through role assignments.

**Lesson from Eligibility applied:** Not every concept is an aggregate. RoleDefinition, PreconditionEvaluation, and CapabilityResolution are all policies, specifications, or services — not aggregates. RoleAssignment is the only concept with persistent state, lifecycle, and transactional consistency requirements.

---

**Round 27C Authorization Aggregate Discovery — READY FOR ARB REVIEW**

**1 aggregate (RoleAssignment — MEDIUM confidence). 1 Domain Service, 2 Specifications/Policies, 1 Value Object. 3 aggregate discovery debt items identified (ADC-1 through ADC-3).**
