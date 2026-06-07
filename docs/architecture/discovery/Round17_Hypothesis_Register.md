# Round 17 — Hypothesis Register

**Date:** 2026-06-07

**Status:** Initialized

**Purpose:** Track all hypotheses discovered during Round 16 and Round 17. Prevent hypotheses from becoming accidental truths through repetition.

---

## How This Register Works

| ID | Hypothesis | Evidence For | Evidence Against | Status | Last Updated |
|----|-----------|--------------|------------------|--------|--------------|
| (ID) | (Hypothesis statement) | (Tier-classified evidence) | (Tier-classified evidence) | Open / Strengthening / Weakening / Rejected / Inconclusive | (Date) |

**Status Values:**

- **Open** — Evidence balanced; investigation continuing
- **Strengthening** — Evidence tilts toward hypothesis
- **Weakening** — Evidence tilts against hypothesis
- **Rejected** — Contradictory evidence found; hypothesis discarded
- **Inconclusive** — Insufficient evidence; deferring decision

---

## Hypotheses from Round 16

These hypotheses emerged during Round 16 discovery and are carried forward to Step 2 investigation.

---

### H1: Evidence is a Governance Concern

**Hypothesis:** Evidence is primarily a governance concern, not an operational concern.

**Evidence For (Tier 3):**
- Trust Model Synthesis identifies Evidence as recurring concept alongside Governance, Authority, Legitimacy
- Governance Evidence Extraction documents Evidence mechanisms as part of governance structure

**Evidence Against (Tier 2):**
- Vote table (operational) produces evidence
- Result table (operational) requires evidence
- Audit trail (operational) generates evidence

**Status:** Open

**Rationale:** Evidence appears in both operational and governance contexts. Insufficient evidence to conclude whether it's primarily governance or cross-cutting.

---

### H2: Evidence is a Capability Shared Across Contexts

**Hypothesis:** Evidence is a shared capability used by multiple contexts (Voting, Governance, Audit) rather than owned by a single context.

**Evidence For (Tier 1, 2):**
- Vote table persists evidence of votes (voting context)
- State machine persists evidence of state transitions (governance context)
- Audit trail captures evidence of officer actions (audit context)

**Evidence Against (Tier 3):**
- Trust Requirements Workbook discusses Evidence as unified concept
- Governance Evidence Extraction treats Evidence as singular concern

**Status:** Open

**Rationale:** Evidence appears across multiple concerns. Could be shared capability or unified concern. Investigation needed.

---

### H3: Evidence is a Bounded Context

**Hypothesis:** Evidence represents a bounded context in its own right, with unified vocabulary and ownership.

**Evidence For (Tier 3):**
- Five recurring concepts include Evidence
- Evidence appears across six independent discovery paths
- Trust Model identifies Evidence mechanisms explicitly

**Evidence Against (Tier 1, 2):**
- No unified Evidence vocabulary found in code
- Evidence is produced by different components (voting, governance, audit)
- No Evidence-specific controller or service identified
- No Evidence ownership model identified

**Status:** Open (but lower probability)

**Rationale:** Convergent discovery is signal, but code analysis shows distributed evidence production. Architectural nature remains undetermined.

---

### H4: Constitutional Rules Form Unified Domain

**Hypothesis:** Constitutional rules (governing how governors are governed) form a unified domain separate from operational rules.

**Evidence For (Tier 2, 3):**
- ElectionConstitution.php contains explicit rules
- State Machine Analysis shows constitutional constraints
- Governance Evidence Extraction documents constitutional structure

**Evidence Against (Tier 1, 2):**
- Constitutional rules are embedded in operational code
- No separate Constitutional domain object identified
- No Constitutional-specific enforcement separate from operational enforcement

**Status:** Open

**Rationale:** Constitutional rules are documented but unclear if they form unified domain or distributed across operational concerns. Investigation needed.

---

### H5: Authority is Constrained by Governance

**Hypothesis:** Authority (who can do what) is constrained by Governance (rules about how power can be exercised).

**Evidence For (Tier 2, 3):**
- ConstitutionalTransitionGuard validates authority against constitutional rules
- State Machine Analysis shows authority bounded by preconditions
- Trust Model Synthesis identifies authority relationships as distinct from governance rules

**Evidence Against:** (No evidence against found yet)

**Status:** Strengthening

**Rationale:** Implementation shows clear pattern of authority validation against governance rules. Strengthening with investigation.

---

### H6: Governance is Exercised Through Authority

**Hypothesis:** Governance rules are enforced by limiting what authorities can do.

**Evidence For (Tier 2, 3):**
- Four-check transition guard validates governance rules through authority validation
- Officer roles (chief, deputy) constrained by governance preconditions
- State transitions gated by governance rules

**Evidence Against:** (No evidence against found yet)

**Status:** Strengthening

**Rationale:** Pattern shows governance is operationalized through authority constraints. Strengthening with investigation.

---

### H7: Voting and Tallying are Operationally Coupled

**Hypothesis:** Vote recording and result projection are computationally coupled in real-time, not separate sequential operations.

**Evidence For (Tier 1, 2):**
- Result table updated immediately when votes are recorded (architect confirmed)
- No separate "counting" workflow found in code
- No "counting" state is manually triggered by an officer
- Result table is derived automatically from vote table
- **Stream 3 (Tier 2):** BaseVote.saved() event calls createResultsFromCandidates() synchronously (BaseVote.php:127-131)
- **Stream 3 (Tier 2):** Results are a derived projection — can be deleted and regenerated via syncResults() (Vote.php:260-271)
- **Stream 3 (Tier 2):** No counting service, batch process, or queue found
- **Stream 3 (Tier 2):** Vote counts computed on-demand via SQL GROUP BY on results table (ResultController.php:32-38)

**Evidence Against (Tier 1):**
- State machine contains explicit "counting" state
- Candidate Reassessment lists "Vote Tallying independence" as uncertainty

**Status:** Strengthening

**Rationale:** Targeted investigation confirms coupling at the implementation level. Results are a synchronous derived projection of vote data with no temporal or architectural separation.

---

### H8: Voting and Tallying are Distinct Operational Concerns

**Hypothesis:** Voting and tallying are operationally distinct, even if computationally coupled.

**Evidence For (Tier 2, 3):**
- State Machine Analysis lists voting and counting as separate states
- Trust Requirements distinguishes vote integrity (rank 1) from result correctness (rank 3)
- Candidate Reassessment identifies "Vote Tallying ambiguity"

**Evidence Against (Tier 1, 2):**
- Real-time coupling suggests unified operation
- No separate officer action triggers tallying
- Results are automatically available, not counted
- **Stream 3 (Tier 2):** Results created synchronously as side-effect of vote save (BaseVote.php:127-131)
- **Stream 3 (Tier 2):** No counting process, only publication gating via results_published flag (ResultController.php:23-25)
- **Stream 3 (Tier 2):** Results are derived projection, not independent data

**Status:** Weakening

**Rationale:** Targeted stream investigation finds no evidence of operational distinctness. The "counting" state gates publication, not computation.

---

### H9: Observed Coupling May Relate to Operational or Integrity Concerns

**Hypothesis:** The observed vote/result coupling may relate to one or more operational or integrity concerns (such as preventing vote tampering after submission).

**Evidence For (Tier 3):**
- Trust Requirements emphasize vote recording as first trust priority
- Real-time recording minimizes vote loss window

**Evidence For (Tier 2 — Stream 3):**
- Data checksum (SHA256) provides vote integrity verification (Vote.php:181-203)
- Expected result count derived from JSON enables reconciliation (Vote.php:241-252)
- Results can be regenerated from source of truth via syncResults() (Vote.php:260-271)
- Coupling means results are always consistent with votes — no tally drift possible

**Evidence Against:** (No evidence against found yet)

**Status:** Open

**Rationale:** Stream 3 found evidence that coupling provides integrity benefits (verifiable reconciliation, single source of truth, no tally drift). Whether coupling was designed for integrity or is an implementation byproduct remains unresolved.

---

### H10: Operational Logging and Governance Replay are Unified

**Hypothesis:** Audit is a single unified concern containing both operational logging and governance replay.

**Evidence For (Tier 2):**
- Single audit trail captures both officer actions and state transitions
- Both appear to use same logging mechanism

**Evidence Against (Tier 2):**
- Operational logging tracks voter actions; governance replay tracks state changes
- Different purposes (operational accountability vs governance verification)
- Different access patterns

**Status:** Open

**Rationale:** Some unification in implementation, but distinct purposes suggest potential split. Investigation needed.

---

### H11: Operational Logging and Governance Replay are Distinct Concerns

**Hypothesis:** Operational audit logging and governance replay represent two separate concerns unified in implementation for convenience.

**Evidence For (Tier 2, 3):**
- Governance Evidence Extraction distinguishes "replay" from "audit"
- Different data captured (voter actions vs state transitions)
- Different questions answered (who did what vs what changed)

**Evidence Against (Tier 2):**
- Single audit table in database
- Unified logging mechanism

**Status:** Open

**Rationale:** Conceptual distinction exists, but implementation is unified. Investigation should determine if separation is needed.

---

### H12: Constitutional Rules are Separate from Operational Rules

**Hypothesis:** Constitutional rules (governing how governors are governed) are distinct from operational rules (governing elections).

**Evidence For (Tier 2, 3):**
- ElectionConstitution.php contains explicit constitutional rules separate from operational code
- State Machine distinguishes constitutional constraints from operational workflows

**Evidence Against (Tier 1):**
- Constitutional rules embedded in operational code
- No separate Constitutional enforcement visible in running system

**Status:** Open

**Rationale:** Conceptual and code separation exists, but operational distinction unclear. Investigation needed.

---

### H13: Constitutional Rules Protect Governance from Officer Overreach

**Hypothesis:** Constitutional rules create constraints that prevent officers from exceeding their authority.

**Evidence For (Tier 2, 3):**
- ConstitutionalTransitionGuard enforces rules regardless of officer role
- Chief authority is explicitly limited (not unlimited)
- Preconditions must be satisfied before actions allowed

**Evidence Against:** (No evidence against found yet)

**Status:** Strengthening

**Rationale:** Implementation shows pattern of constraint enforcement. Strengthening with investigation.

---

## Hypotheses from Step 2 Investigation

### H14: Governance and Authority Are Tightly Integrated

**Hypothesis:** Governance and Authority artifacts are tightly integrated rather than cleanly separated into distinct concerns.

**Evidence For (Tier 2):**
- ConstitutionalTransitionGuard validates authority against governance rules
- GovernanceDecision model exists alongside officer authority models
- Multiple constitutional policies enforce both governance constraints and authority limits

**Evidence Against:** (Pending investigation)

**Status:** Open

**Rationale:** Phase 0 located governance and authority artifacts in same repository areas, suggesting possible integration. Investigation will determine if they are unified or separate concerns.

---

### H15: Legitimacy Functions as Capability Gating Mechanism

**Hypothesis:** Legitimacy is enforced as a capability-gating mechanism (controlling what actions are allowed) rather than an abstract organizational concept.

**Evidence For (Tier 2):**
- ConstitutionalLegitimacyPolicy and ConstitutionalLegitimacyDecision classes control decision authorization
- LegitimacyGranted events appear linked to capability decisions
- TrustCapabilityPolicy restricts capabilities based on trust state

**Evidence Against:** (Pending investigation)

**Status:** Open

**Rationale:** Code artifacts suggest legitimacy enforces policy gates. Investigation will clarify whether legitimacy is purely operational enforcement or has broader organizational meaning.

---

### H16: Constitutional Rules Form Distinct Separate System

**Hypothesis:** Constitutional rule artifacts located in multiple repository areas form a distinct, unified system separate from operational rules.

**Evidence For (Tier 2):**
- ConstitutionalArticlesSnapshot, ElectionConstitutionValidator, ElectionConstitutionHasher locate in separate directories
- Constitutional rules appear immutable (ConstitutionalImmutabilityTest)
- ConstitutionalDivergenceType tracks violations

**Evidence Against:** (Pending investigation)

**Status:** Open

**Rationale:** Phase 0 located constitutional artifacts in distinct areas. Investigation will determine if they form unified system or if constitutional rules are distributed across operational concerns.

---

### H17: Challenge/Dispute Handling Is Intentionally Absent or Non-Implemented

**Hypothesis:** Election challenge and dispute mechanisms are either intentionally absent from the design or non-implemented in the current system.

**Evidence For (Tier 2):**
- No ElectionChallenge, DisputeResolution, or AppealProcess models located in repository
- No challenge controllers or endpoints found in route inventory
- Only 3 minimally related files found in keyword search

**Evidence Against:** (Pending investigation)

**Status:** Open

**Rationale:** Phase 0 found minimal challenge-related code. Investigation will determine if challenges are missing by design (governance model does not include appeals) or missing by implementation (planned but not yet built).

---

### H19: Constitutional Rules Are Represented in Code With No Runtime Modification Mechanism

**Hypothesis:** Constitutional rules are encoded in PHP arrays and guard service logic, with no observed runtime configuration mechanism. Changing rules requires code deployment.

**Evidence For (Tier 2):**
- Rules stored in ElectionConstitution.RULES array
- Precondition logic in ConstitutionalTransitionGuard service methods
- No configuration files, rule engine, or runtime rule updates found
- Comments (line 11) reference rules as "SINGLE SOURCE OF TRUTH" but only within code

**Evidence Against (Tier 2):**
- Rules could theoretically be extracted to external configuration
- Design decision to embed rules may be temporary (pending rule engine implementation)

**Status:** Strengthening

**Rationale:** Evidence shows rules are represented in code with enforcement integrated into guard service. Whether this design is intentional (immutable constitutional protection) or temporary (incomplete implementation) remains unresolved (D30).

---

### H20: Rule Origin and Rationale Are Not Documented in Code

**Hypothesis:** Constitutional rules (state transitions, preconditions, roles) are represented in code, but the source and rationale for these rules are not documented in the examined codebase.

**Evidence For (Tier 2):**
- ElectionConstitution.RULES array describes rules but not their origin or rationale
- Comments explain what rules do, not why they exist
- Precondition selection reasons not documented (D23)
- No external policy/governance documents found in examined repository
- Frozen policy source not found (D25)

**Evidence Against:** (Pending investigation)

**Status:** Open

**Rationale:** Absence of documentation in examined codebase is observable. Source documents may exist outside repository (organizational bylaws, governance policies, architectural decisions) not examined by this investigation. Representation in code does not indicate origin.

---

### H21: Some Rules Are Defined But Not Fully Enforced

**Hypothesis:** Some constitutional rules are syntactically present in code but not operationally enforced.

**Evidence For (Tier 2):**
- Payment authorization for paid plan (>40 voters) is stubbed (line 225: `$paymentAuthorized = true; // Stub`)
- Any election >40 voters currently bypasses payment check
- Code comment indicates incomplete implementation

**Evidence Against:** (Pending investigation)

**Status:** Open

**Rationale:** Clear evidence of at least one incomplete rule. Extent of incompleteness unknown (other rules may be similarly incomplete).

---

## Hypothesis Status Summary

| Status | Count | Examples |
|--------|-------|----------|
| Open | 12 | H3, H4, H9, H10, H11, H12, H14, H15, H16, H17, H20, H21 |
| Strengthening | 6 | H1, H2, H5, H6, H7, H13, H19 |
| Weakening | 1 | H3 |
| Rejected | 0 | (none) |
| Inconclusive | 0 | (none) |

---

## Investigation Approach to Hypotheses

### For Hypotheses with Evidence For + Evidence Against

Continue investigation to:
- Strengthen supporting evidence
- Weaken contradictory evidence
- Move toward resolution

### For Hypotheses with Strong Evidence For Only

Investigate to:
- Find disconfirming evidence
- Test hypothesis against edge cases
- Ensure no hidden assumptions

### For Hypotheses Weakening

Investigate to:
- Confirm weakness
- Move toward rejection
- Identify replacement hypothesis

### H18: Operational Audit and Governance Replay Participate in a Larger Verification Concern

**Hypothesis:** Operational audit and governance replay may participate in a larger verification ecosystem while remaining distinct mechanisms.

**Evidence For (Tier 2):**
- ParticipationEligibilityEvidence is built during operational evaluation but has deterministic hash designed for replay
- Both mechanisms interact with shared evidence artifacts
- Replay infrastructure exists and is designed to be compatible with operational evidence

**Evidence Against (Tier 2):**
- No integration point exists in current codebase
- Operational audit and replay have separate code locations
- No cross-references or coupling found

**Status:** Open

**Rationale:** Shared evidence design suggests potential convergence, but no current integration exists. The nature of any larger verification concern remains unresolved.

---

## Next Steps

1. Investigation executes against six investigation streams
2. New hypotheses discovered during investigation added to this register
3. Evidence for/against collected with tier classification
4. Status updated as evidence accumulates
5. Final hypothesis status documented before ARB review

---

**Status: Tracking Active**

**Next Update: After Investigation Phase 1 Evidence Gathering**

