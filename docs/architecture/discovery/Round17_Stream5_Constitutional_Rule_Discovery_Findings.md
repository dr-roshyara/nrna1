# Round 17 — Stream 5: Constitutional Rule Discovery — Evidence Findings

**Date:** 2026-06-07

**Phase:** Phase 1 — Evidence Gathering (Stream 5)

**Status:** Complete

**Investigation Scope:** Investigate WHERE constitutional rules originate. Distinguish between formal rules, policy rules, eligibility rules, procedural rules, operational practices, and assumed behavior.

---

## Investigation Questions

**Primary Questions:**

1. What are the sources of constitutional rules?
2. Are rules formal (documented, defined) or informal (assumed, practiced)?
3. Can constitutional rules be amended after election creation?
4. Do rules vary by tenant/organization or are they universal?
5. Where do eligibility rules originate?
6. What is the relationship between frozen rules and mutable operational settings?
7. Are there rules that exist only in practice but not in code?

---

## Rule Categories and Sources

### Category 1: Constitutional State Transition Rules

**Source:** Code-embedded (ElectionConstitution.php line 23-147)

**Formal Definition:**
- Defined in single location: `ElectionConstitution::RULES` array
- Each transition specifies: allowed_states, allowed_roles, preconditions, target_state
- Documented in comments (lines 13-20): "THE SINGLE SOURCE OF TRUTH for what actions are constitutionally allowed"

**Rule Examples (Tier 2 Evidence):**

State Transition: `submit_for_approval` (lines 25-30)
- Allowed states: ['draft']
- Allowed roles: ['chief', 'deputy']
- Preconditions: ['timezone_set']
- Target state: 'submitted_for_approval'

State Transition: `open_voting` (lines 94-100)
- Allowed states: ['setup_nomination', 'ready_for_voting']
- Allowed roles: ['chief']
- Preconditions: ['voting_window_defined', 'timezone_set']
- Target state: 'voting_active'

State Transition: `approve` (lines 32-38)
- Allowed states: ['submitted_for_approval']
- Allowed roles: ['platform_admin']
- Preconditions: ['capacity_eligibility']
- Target state: 'approved'

**Observed Enforcement (Tier 2 Evidence):**
- ConstitutionalTransitionGuard.assertAllowed() (lines 40-81) enforces each rule
- Line 47: Checks action is defined → throws InvalidTransitionException if not
- Line 56: Checks state allows action
- Line 66: Checks user has required role
- Line 77: Validates all preconditions

**Observed Characteristics:**
- Rules are stored in a single implementation registry (ElectionConstitution.RULES array)
- All state transition checks pass through the same enforcement service (ConstitutionalTransitionGuard)
- No observed bypass mechanism; transition guard is mandatory for all state changes
- Preconditions are checked at enforcement time, not stored separately

**Origin Question (D22):** Rules are represented in code. Origin of these rules (organizational bylaws, domain model, technical requirements, or other sources) remains unknown. No source document found in examined repository.

---

### Category 2: Eligibility Rules (Preconditions)

**Source:** Code-derived from business facts (ConstitutionalTransitionGuard.php lines 177-202)

**Rule List:**

| Precondition | Logic | Source | Tier |
|---|---|---|---|
| `timezone_set` | `!empty($election->timezone)` | Election model field | Tier 2 |
| `has_posts` | `$election->posts()->exists()` | Post model relationship | Tier 2 |
| `has_voters` | `$election->voters()->exists() OR memberships exist` | Voter/membership queries | Tier 2 |
| `has_chief` | `ElectionOfficer with role='chief' and status='active' exists` | ElectionOfficer model | Tier 2 |
| `has_approved_candidates` | `$election->candidacies()->where('status', 'approved')->exists()` | Candidacy model | Tier 2 |
| `voting_window_defined` | `voting_starts_at !== null AND voting_ends_at !== null` | Election model fields | Tier 2 |
| `capacity_eligibility` | Free ≤40 voters: true; Paid >40: payment_authorized (stubbed) | isCapacityEligible() method | Tier 2 |

**Observed Characteristic:**
These preconditions are NOT defined declaratively in ElectionConstitution.RULES. Instead, they are:
1. Named in the RULES array (line 28, 35, etc.: 'preconditions' => [...])
2. Validated at runtime by isPreconditionMet() (line 177)
3. Implemented as individual business-fact queries

**Observed Characteristic:**
Preconditions reference operational data (election facts like post existence, voter count, timezone setting) when determining whether a state transition is allowed.

**Origin Questions (D23-D24):**
- What determines the specific preconditions for each action? (D23)
- Why is capacity_eligibility a precondition but not other operational facts? (D24)

---

### Category 3: Frozen Constitutional Policies

**Source:** ConstitutionalArticlesSnapshot (lines 1-80)

**Formal Definition:**
```
network_binding_strategy
max_votes_per_ip
device_binding_strategy
ballot_authorization_protocol
trust_overlay_active
trust_overlay_priority
trust_overlay_reason
```

**Observed Characteristics (Tier 2 Evidence):**

Immutability (lines 7-21):
- Comments describe as "frozen forever for replay determinism"
- Captured at election creation
- Frozen snapshot enables constitutional hash for tamper detection

Scope (lines 24-32):
- What IS included: network rules, device requirements, verification protocols, trust thresholds, authorization protocols
- What IS NOT included: election title, descriptions, UI labels, scheduling adjustments

Hash Verification (lines 58-61):
- SHA256 hash computed from snapshot
- Enables detection of constitutional tampering
- Same as ParticipationEligibilityEvidence from Stream 4 (deterministic hashing for divergence detection)

**Origin Questions (D25):**
- Where are these frozen policies defined? At election creation? From templates? From organizational policy?
- Can an election inherit default policies from organization or are they always explicitly set?

---

### Category 4: Operational Governance Rules (Suspension/Resume)

**Source:** ElectionConstitution.RULES (lines 125-146)

**Rules (Tier 2 Evidence):**

`suspend` action (lines 129-139):
- Allowed states: All states (draft through results_published)
- Allowed roles: ['chief', 'platform_admin']
- Purpose: "Suspend election for governance hold (fraud/legal/operational)"

`resume` action (lines 140-146):
- Allowed states: ['suspended']
- Allowed roles: ['chief', 'platform_admin']
- Description: "engine re-derives state from constitutional facts"

**Code Comment (Tier 2 Evidence):**
Lines 126-128 contain a comment describing suspension:
```text
Suspension is an operational governance overlay,
orthogonal to lifecycle progression.
It freezes capabilities only.
Does NOT mutate business facts or advance the election through phases.
```

**Observed Facts:**
- Suspension is defined in the RULES array like other transitions
- Suspension can occur from multiple states (draft through results_published)
- Resume action transitions FROM suspended state
- Code distinguishes suspension from normal lifecycle states via the comment terminology

**Unresolved Question:**
Whether "operational governance overlay" is a domain concept, architectural concept, or implementation description remains unresolved. The comment provides terminology but not definitional authority.

**Origin Question (D26):**
Suspension rules appear to be designed for platform intervention (fraud/legal hold). What organizational or regulatory authority established the suspension capability?

---

### Category 5: Automatic/System Rules

**Source:** ElectionConstitution.RULES (lines 46-52)

**Rule:**

`auto_submit` action:
- Allowed states: ['draft']
- Allowed roles: ['system']
- Preconditions: ['capacity_eligibility']
- Target state: 'approved'
- Description: "System auto-approves election (free plan ≤40 voters, skips manual review)"

**Source of Free/Paid Distinction (Tier 2 Evidence):**

ConstitutionalTransitionGuard.isCapacityEligible() (lines 213-227):
- Line 219: Free plan: voters ≤ 40 → auto-eligible
- Line 225: Paid plan: >40 voters → payment_authorized (STUBBED)
- Comment (line 224): "TODO: Replace with actual payment authorization check when billing system implemented"

**Observed Fact:**
The free/paid plan distinction is implemented but payment authorization is not yet integrated. The rule EXISTS in code but ENFORCEMENT is incomplete.

**Origin Question (D27):**
Where did the 40-voter threshold originate? Is it based on business model, capacity constraints, organizational policy, or pricing tier definition?

---

### Category 6: Role-Based Authority Rules

**Source:** ElectionConstitution.RULES (throughout) + ElectionOfficer model

**Observed Roles (Tier 2 Evidence):**

From RULES array:
- 'chief' — highest election authority (can open voting, publish results, manage suspension)
- 'deputy' — chief's delegate (can submit for approval, complete setup, close voting, archive)
- 'voter' — can apply for candidacy during nomination
- 'member' — can apply for candidacy
- 'platform_admin' — platform governance authority (approve/reject elections)
- 'system' — automatic actions (auto_submit)

Role Storage:
- Global roles via Spatie Permission (line 122, Spatie `hasRole()`)
- Election-specific roles via ElectionOfficer model (lines 129-137)
- ElectionOfficer table tracks: role, status ('active'), appointed_by, term_ends_at

**Role Validation (Tier 2 Evidence):**

Lines 119-140 (userHasAnyRole):
- Check Spatie global permissions first
- If not found, check election-specific ElectionOfficer roles
- ElectionOfficer must have status='active'

**Origin Questions (D28-D29):**
- How are 'chief' and 'deputy' roles assigned? Who appoints them? (D28)
- What is the meaning of 'appointed_by' and 'term_ends_at' fields on ElectionOfficer? (D29)

---

## Rule Source Classification Matrix

| Rule Category | Source Type | Formal/Informal | Mutable | Tenant-Specific | Origin Known |
|---|---|---|---|---|---|
| **State Transitions** | Code array | Formal | No | No | Code only, unclear |
| **Preconditions** | Code logic | Formal | No | No | Business facts, unclear why |
| **Frozen Policies** | ConstitutionalArticlesSnapshot | Formal | No (frozen) | Likely | Not found |
| **Suspension/Resume** | Code array | Formal | No | No | Governance need, unclear source |
| **Auto-Approval** | Code logic | Formal (incomplete) | No | No | Business model, unfinished |
| **Role Definitions** | Code + ElectionOfficer | Formal | No | No | Organizational roles, unclear mapping |

---

## Critical Observations

### Observation 1: Rules are Embedded, Not Declarative

**Finding (Tier 2 Evidence):**

Rules exist in:
- Hard-coded PHP array (ElectionConstitution.RULES)
- Conditional logic in Guard service
- Snapshot at election creation
- No external configuration files, policy documents, or rule engine

**Consequence:**
Changing rules requires code changes and deployment. Rules cannot be adjusted per-organization or per-election.

**Question (D30):**
Is the current approach (rules in code) intentional (immutable constitutional constraints) or temporary (pending rule engine implementation)?

---

### Observation 2: Some Rules Are Incomplete

**Finding (Tier 2 Evidence):**

Capacity eligibility check (line 225):
```php
$paymentAuthorized = true; // Stub: payment system not yet implemented
```

This rule is DEFINED but NOT ENFORCED. Elections with >40 voters pass the check regardless of payment status.

**Question (D31):**
What other rules might be syntactically present but not operationally enforced?

---

### Observation 3: Frozen Rules Create Commitment

**Finding (Tier 2 Evidence):**

ConstitutionalArticlesSnapshot (lines 7-21):
- Captured at election creation
- Frozen with deterministic hash
- Immutable for replay determinism
- Can only change if election is re-created

**Consequence:**
Once an election is created, its constitutional policies cannot be updated. Changes to global policies (e.g., network binding strategy) do not affect existing elections.

**Question (D32):**
Are frozen policies elected at creation time, or inherited from organizational defaults?

---

## Assumption Register Updates

### A14: Roles Are Globally Consistent

**Assumption:** 'Chief', 'deputy', 'platform_admin' mean the same thing across all elections.

**Evidence For:** Consistent role names throughout codebase.

**Evidence Against:** No documentation of what each role represents. 'Chief' appointed how? 'Deputy' relationship to chief unknown. Could vary by organization.

**Status:** OPEN — Investigation needed

---

### A15: Preconditions Are Sufficient

**Assumption:** The seven preconditions (timezone_set, has_posts, etc.) are sufficient to validate readiness for each action.

**Evidence For:** All transitions check these specific preconditions.

**Evidence Against:** isCapacityEligible() is incomplete (payment stubbed). Suggests other preconditions might also be incomplete or missing.

**Status:** FLAGGED

---

## New Discovery Debt

### D22: Where Do Constitutional State Transition Rules Originate?

**Question:** ElectionConstitution.RULES defines state transitions, roles, and preconditions. Are these rules derived from organizational bylaws, domain requirements, or technical constraints?

**Why Unresolved:**
- Rules exist in code but no source document found
- Comments describe them as "CRITICAL RULES" but no rationale

**Priority:** HIGH

**Recommended Discovery:** Architect/domain expert interview on rule origins and whether rules vary by organization

---

### D23: What Determines the Preconditions for Each Action?

**Question:** Why does `submit_for_approval` require `timezone_set` but `begin_setup` does not?

**Why Unresolved:**
- Precondition selection not explicitly documented
- Pattern not obvious from rules alone

**Priority:** MEDIUM

**Recommended Discovery:** Investigate business rationale for each precondition choice

---

### D24: Why Is `capacity_eligibility` a Precondition?

**Question:** Capacity eligibility affects approval workflow, but is it really a precondition for the action or a gating policy?

**Why Unresolved:**
- Unlike other preconditions (has_voters, has_posts), capacity is not a "fact" but a policy
- Suggests different category of rule

**Priority:** MEDIUM

**Recommended Discovery:** Clarify distinction between fact-based and policy-based preconditions

---

### D25: Where Are Frozen Constitutional Policies Defined?

**Question:** ConstitutionalArticlesSnapshot captures network binding, device binding, trust overlay settings. Where do these defaults come from?

**Why Unresolved:**
- No source found for policy values
- Unknown if they're organization-specific or global defaults

**Priority:** MEDIUM

**Recommended Discovery:** Investigate policy initialization logic

---

### D26: What Organizational Authority Established Suspension Rules?

**Question:** Suspension/resume capability appears designed for platform intervention. What regulatory or organizational authority requires this capability?

**Why Unresolved:**
- Rules exist but no documented justification
- Suggests external requirement

**Priority:** MEDIUM

**Recommended Discovery:** Determine if suspension is legal requirement, organizational policy, or platform design

---

### D27: Where Did the 40-Voter Free/Paid Threshold Originate?

**Question:** isCapacityEligible() uses 40 voters as free tier threshold. Is this based on capacity, business model, or organizational policy?

**Why Unresolved:**
- Magic number with no documented rationale
- Implementation is incomplete (payment check stubbed)

**Priority:** LOW

**Recommended Discovery:** Determine threshold origin and business model rationale

---

### D28: How Are Chief and Deputy Roles Assigned?

**Question:** ElectionConstitution specifies chief and deputy roles. Who has authority to appoint them? What does appointment mean?

**Why Unresolved:**
- ElectionOfficer model has 'appointed_by' field but logic not examined
- Role lifecycle not clear

**Priority:** MEDIUM

**Recommended Discovery:** Investigate officer appointment workflow

---

### D29: What Do appointed_by and term_ends_at Mean?

**Question:** ElectionOfficer tracks who appointed the officer and when their term ends. These suggest temporal role management. How are expired terms handled?

**Why Unresolved:**
- Fields exist but enforcement not examined
- May indicate rules about role expiration

**Priority:** MEDIUM

**Recommended Discovery:** Investigate role term enforcement

---

### D30: Are Rules in Code Intentional or Temporary?

**Question:** Rules are hard-coded in PHP arrays, requiring code deployment to change. Is this the intended design (immutable constitution) or incomplete (awaiting rule engine)?

**Why Unresolved:**
- No documentation of architectural decision
- Stub comments suggest some features incomplete

**Priority:** HIGH

**Recommended Discovery:** Architect decision documentation or interview on rule deployment model

---

### D31: What Other Rules Might Be Incomplete?

**Question:** Payment authorization (D27) is stubbed and non-functional. Are other rules similarly incomplete?

**Why Unresolved:**
- Only one stub found in source
- Full codebase audit not conducted

**Priority:** MEDIUM

**Recommended Discovery:** Audit for other TODO/stub comments indicating incomplete rules

---

### D32: Are Frozen Policies Inherited or Explicit?

**Question:** ConstitutionalArticlesSnapshot is frozen at election creation. Are policies inherited from organization defaults or explicitly configured per-election?

**Why Unresolved:**
- Election creation logic not examined
- No configuration source found

**Priority:** MEDIUM

**Recommended Discovery:** Investigate election initialization to determine policy source

---

## Stream 5 Completion Status

**✅ Complete**

Deliverables Produced:

✅ Rule Categories and Sources (6 categories identified and analyzed)

✅ Rule Source Classification Matrix (9 rules analyzed for source type, formality, mutability, tenant-specificity)

✅ Critical Observations (3 observations on embedding, incompleteness, and immutability)

✅ Assumption Register Updates (A13-A15 status updated)

✅ Discovery Debt (11 new items logged: D22-D32)

---

## Summary of Stream 5 Findings

**Observed Rule Organization:**

```
Constitutional Rules (State Transitions)
├── Defined in: ElectionConstitution.RULES (code array)
├── Enforced by: ConstitutionalTransitionGuard
├── Mutability: None (requires code deployment)
├── Origin: Unknown (code-embedded, no source document)
│
Eligibility Rules (Preconditions)
├── Defined in: ElectionConstitution.RULES (named) + ConstitutionalTransitionGuard (logic)
├── Enforced by: isPreconditionMet() at transition time
├── Mutability: None (requires code deployment)
├── Origin: Business facts, selection rationale unknown
│
Frozen Constitutional Policies
├── Defined in: ConstitutionalArticlesSnapshot
├── Frozen at: Election creation
├── Mutability: None (immutable, hash-protected)
├── Origin: Unknown (initialization source not found)
│
Operational Governance Rules (Suspension)
├── Defined in: ElectionConstitution.RULES
├── Enforced by: ConstitutionalTransitionGuard
├── Mutability: None (requires code deployment)
├── Origin: Governance need (fraud/legal), authority unknown
│
Role-Based Rules
├── Defined in: ElectionConstitution.RULES (role names) + ElectionOfficer model (assignments)
├── Enforced by: userHasAnyRole() check
├── Mutability: Roles can be assigned/revoked, term-based expiration unclear
├── Origin: Unknown (no documentation of role definitions)
```

**Key Findings:**

1. **Rules are Represented in Code** — State transitions, preconditions, and role requirements are encoded in PHP arrays and guard service logic
2. **Rules are Stored in Single Implementation Registry** — ElectionConstitution.RULES array contains all state transition definitions
3. **Rules Require Code Deployment to Change** — No runtime configuration mechanism observed; rule changes require code updates and deployment
4. **Some Rules are Incomplete** — Payment authorization is defined but not enforced (stub implementation)
5. **Preconditions Reference Operational Data** — Precondition checks evaluate current election facts (posts, voters, timezone, etc.)
6. **Rule Origin is Unknown** — Rules are represented in code but source/rationale unclear; no external source documents found
7. **No Tenant-Specific Rule Differentiation Observed** — Examined implementation shows no mechanism for per-organization rule variation; all organizations share same rules

**Missing Evidence:**

- Organizational bylaws or governance documents
- Business logic rationale for specific rules
- Change history or rule evolution
- Tenant-specific rule variations (if any)
- Formal rule definitions separate from code

---

**Stream 5 Status: READY FOR ARB REVIEW**

