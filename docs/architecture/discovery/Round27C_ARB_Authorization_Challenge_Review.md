# Round 27C-ARB — Authorization Aggregate Challenge Review

**Date:** 2026-06-08

**Phase:** Tactical DDD — Aggregate Challenge Review (Pre-Acceptance)

**Status:** Complete — Awaiting ARB Decision

---

## 1. Purpose

Challenge the Round 27C Authorization aggregate conclusions before acceptance. Use existing evidence only — no new repository discovery.

---

## 2. Question 1: Is RoleAssignment Genuinely an Aggregate?

### Invariant Evidence Matrix

| Invariant | Evidenced? | Enforcement | Classification |
|-----------|-----------|-------------|----------------|
| One active chief per election | ElectionOfficer model has `role` and `status` fields; role='chief', status='active' observed. **No database unique constraint** on (election_id, role, status) in examined migrations. | **Unproven** — may be application-level convention or governance process, not aggregate invariant | ⚠️ **Assumed** — requires verification |
| Officer status must be active for authorization | `userHasAnyRole()` checks `where('status', 'active')` at query time (ConstitutionalTransitionGuard.php:129-137) | **Evidenced** — enforced at runtime | ✅ **Confirmed aggregate invariant** |
| Role assignment requires governance authority | ElectionOfficer has `appointed_by` field but appointment workflow logic not examined (D28) | **Partially evidenced** — field exists but enforcement not observed | ⚠️ **Partially evidenced** |
| One user cannot hold conflicting roles simultaneously | No evidence found in examined code | **Not evidenced** — may not exist | ❌ **Unknown** |

### Assessment

Of 4 claimed invariants:
- **1 confirmed**: officer status must be active for authorization
- **1 partially evidenced**: role assignment requires authority (`appointed_by` exists, enforcement not observed)
- **1 assumed**: one active chief per election (no constraint found)
- **1 unknown**: no conflicting roles

**Conclusion:** RoleAssignment has moderate aggregate evidence. The strongest invariant (active status check) is confirmed. The uniqueness invariant (one active chief per election) may be a governance convention rather than an aggregate invariant — there is no observed database or application constraint enforcing it.

**Verdict:** Likely aggregate, but with weaker invariant evidence than the Round 27C document implies. Should remain provisional until uniqueness enforcement is confirmed.

---

## 3. Question 2: Who Owns Role Definitions?

### Governance Ownership

| Evidence | Source | Classification |
|----------|--------|---------------|
| Roles are defined in ElectionConstitution.RULES array | ElectionConstitution.php:23-147 | Governance (rule definition) |
| Role definitions are part of constitutional rules — deploy-time, not runtime | Stream 5, D30 (resolved) | Governance |
| Preconditions and allowed_states are also defined in RULES — same governance mechanism | ElectionConstitution.php | Governance |
| Role definitions change through code deployment — constitutional amendment process | Stream 5 | Governance |

### Authorization Ownership

| Evidence | Source | Classification |
|----------|--------|---------------|
| Authorization reads role definitions to make capability decisions | ConstitutionalTransitionGuard, ADR-001 | Authorization (usage) |
| Roles are evaluated at runtime for permission checking | ADR-004, ConstitutionalTransitionGuard | Authorization (enforcement) |

### Boundary Decision

**Role definitions belong to Constitutional Governance**, not Authorization. Authorization *evaluates* role definitions but does not *own* them.

- Constitutional Governance owns: "What roles exist? What actions are they allowed to perform?"
- Authorization owns: "Given these role definitions and assignments, is this action allowed for this user?"

This is consistent with the broader pattern: **Governance defines rules; Authorization enforces rules.**

**Impact on aggregate inventory:** No change. RoleDefinition was already classified as Specification/Policy (Section 4, Candidate A). This review confirms that classification and clarifies the ownership boundary.

---

## 4. Question 3: Who Owns Appointment Authority?

### Evidence

| Evidence | Source |
|----------|--------|
| ElectionOfficer has `appointed_by` field | ElectionOfficer model, Stream 2 |
| Appointment workflow not fully examined | D28, D29 |
| ConstitutionalTransitionGuard checks role authorization but not appointment authority | ConstitutionalTransitionGuard.php:94-141 |
| No explicit "who appoints whom" logic found in examined code | All streams |

### Boundary Decision

**Appointment authority likely belongs to Constitutional Governance**, not Authorization. Governance defines who can appoint whom (e.g., only the organization admin can appoint chiefs; chiefs can appoint deputies). Authorization enforces the resulting role assignments.

However, since appointment workflow is partially understood (D28/D29), this boundary is provisional. The RoleAssignment aggregate should track `appointed_by` as a reference but should not assume ownership of appointment policies.

**Impact on aggregate inventory:** None. RoleAssignment remains an aggregate but appointment policies belong to Governance. RoleAssignment owns the *assignment record* — Governance owns the *appointment rules*.

---

## 5. Question 4: Aggregate Boundary Stress Test

### Scenario A: RoleAssignment is NOT an Aggregate

| Breach | Consequence | Evidence |
|--------|-------------|----------|
| Two chiefs created for same election | No enforcement observed — ElectionOfficer table has no unique constraint on (election_id, role, status) | Uniqueness invariant weak |
| Officer status changed without authorization | `userHasAnyRole()` checks `status='active'` at query time but status could be changed by any code path | Status invariant partially protected |
| Expired term still allows authorization | No term expiration enforcement observed — `term_ends_at` field exists but is not checked in `userHasAnyRole()` | Term invariant absent |

### Scenario B: RoleAssignment IS an Aggregate

| Protection | Implementation | Evidence |
|------------|---------------|----------|
| Active status enforced on read | `userHasAnyRole()` filters by `status='active'` | Confirmed |
| One user cannot be simultaneously chief and deputy (if enforced) | Would prevent inconsistent assignments | Unproven — no enforcement observed |
| Term-limited assignments could be enforced | Aggregate would check term when evaluating authorization | Not currently implemented |

### Stress Test Conclusion

The RoleAssignment concept sits at the boundary between aggregate and query-based authorization. Its invariants are partially protected through query-time filtering (status active) but not through transactional enforcement (uniqueness, no conflicting roles). This is consistent with a system where authorization is primarily controller-driven rather than aggregate-driven.

**Verdict:** RoleAssignment is a valid aggregate candidate, but its invariant protection is weaker than ideal. The primary protection (status active) happens at query time, not at write time. This is acceptable for the current system but should be noted as a risk.

---

## 6. Revised Aggregate Inventory

| Concept | Type | Confidence (updated) | Rationale |
|---------|------|---------------------|-----------|
| **RoleAssignment** | **Aggregate (provisional)** | **MEDIUM-LOW** | Invariants partially evidenced. Status active confirmed. Uniqueness unproven. Term enforcement absent. Appointment policies likely belong to Governance. |
| CapabilityResolution | Domain Service | HIGH | Confirmed — stateless, deterministic, pure function |
| RoleDefinition | Specification/Policy | HIGH | Belongs to Constitutional Governance, not Authorization |
| PreconditionEvaluation | Specification/Policy | HIGH | Governance rules evaluated at runtime |
| CapabilitySnapshot | Value Object | HIGH | Immutable output of resolution |

### Key Change

RoleAssignment downgraded from MEDIUM to MEDIUM-LOW confidence. The aggregate status is valid based on decision ownership and state ownership, but invariant evidence is weaker than initially assessed. The document should note which invariants are confirmed, which are assumed, and which are absent.

---

## 7. Confidence Reassessment

| Concept | Previous Confidence | Revised Confidence | Reason |
|---------|-------------------|-------------------|--------|
| RoleAssignment (aggregate) | MEDIUM | **MEDIUM-LOW** | Invariant evidence weaker than claimed; uniqueness unproven; term enforcement absent |
| CapabilityResolution (DS) | HIGH | HIGH | No change |
| RoleDefinition (Spec) | HIGH | HIGH | No change — confirmed as Governance-owned |
| PreconditionEvaluation (Spec) | HIGH | HIGH | No change |
| CapabilitySnapshot (VO) | HIGH | HIGH | No change |

---

## 8. Summary

**Challenge review outcomes:**

1. **RoleAssignment is a provisional aggregate.** Valid based on decision ownership and state ownership. Weaker on invariant enforcement — uniqueness unproven, term enforcement absent. The strongest invariant (status active) is enforced at query time, not write time.

2. **Role definitions belong to Constitutional Governance**, not Authorization. Authorization evaluates but does not own them.

3. **Appointment authority likely belongs to Governance.** RoleAssignment owns the assignment record; Governance owns the appointment rules.

4. **Aggregate inventory stands** but RoleAssignment confidence should reflect the weaker invariant evidence.

---

**Round 27C-ARB Authorization Challenge Review — READY FOR ARB DECISION**

**RoleAssignment confirmed as provisional aggregate (MEDIUM-LOW confidence — weaker invariants than initially assessed). All other classifications unchanged. Role definitions and appointment authority belong to Constitutional Governance, not Authorization.**
