# ARB Evidence Synthesis (Pre-Decision)

**Date:** 2026-06-03  
**Status:** SYNTHESIS — Input to governance decisions Q0–Q5  
**Authority:** Architecture Review Board  
**Purpose:** Reduce evidence into governance findings before authorizing Round 8

**CRITICAL:** This synthesis does NOT answer Q0–Q5. It prepares the evidence foundation for those answers.

---

## Section 1 — Strong Findings (Survived Testing)

These findings appear repeatedly across all maps, heuristics, and test scenarios.

### Finding 1: Four Core Bounded Contexts Exist

**Contexts:**
1. Membership (6/6 heuristics)
2. Election (6/6 heuristics)
3. Governance (5/6 heuristics)
4. Appeals (6/6 heuristics)

**Evidence:**
- All scored identically across heuristic framework
- Survived boundary stress testing (8 scenarios)
- Appeared in all three alternative maps (A, B, C)
- Unique language documented for each (membership-specific, election-specific, etc.)
- Unique decisions documented for each
- Unique consistency rules documented for each
- Organizational ownership confirmed (real organizations have these committees separately)

**Confidence:** HIGH

**Remaining Uncertainty:**
- Internal structure of contexts (tactics, aggregates) not designed
- Context relationships (contracts, event flows) not finalized
- Context boundaries may shift during Round 8 modeling

**Implication for Round 8:** These four contexts can be assumed stable starting points for Round 8 context relationship modeling.

---

### Finding 2: Multiple Viable Maps Exist

**Maps:**
- Map A: 7 contexts (maximum separation)
- Map B: 5 contexts + infrastructure (simplified topology)
- Map C: 4 contexts + distributed verification (minimal pressure)

**Evidence:**
- All three maps work in both Election-Only and Full Membership modes
- All three hold under 8 boundary stress scenarios
- No map has "clear winner" status (all have trade-offs)
- Maps represent different architectural assumptions about Verification, Evidence, Authority

**Confidence:** HIGH

**Remaining Uncertainty:**
- Whether one map is "better" than others (governance question, not evidence question)
- Which architectural assumptions (about Authority, Evidence, Recognition) should guide selection

**Implication for Round 8:** Round 8 can proceed with one map OR compare multiple approaches; governance must decide strategy.

---

### Finding 3: Verification Requires Architectural Decision

**Status:** Verification is "ambiguous" in heuristic scoring (5/6; organizational ownership is weak)

**Maps Treat Differently:**
- Map A: Verification as bounded context
- Map B: Verification as infrastructure layer
- Map C: Verification as distributed capability

**Evidence:**
- Phase 2 called Verification "constitutional pillar"
- Round 7 found Verification appears in all four core contexts
- No single context owns "what is legitimate?" decision
- Verification logic appears to need consistent treatment across contexts

**Confidence:** MEDIUM (high confidence that Verification is important; medium confidence about architectural placement)

**Remaining Uncertainty:**
- Should Verification be centralized, distributed, or layered?
- Can Verification be a capability without a context?
- If distributed, how is consistency maintained?

**Implication for Round 8:** Verification architectural role MUST be clarified before finalizing context relationships. This is not optional.

---

### Finding 4: Core Concepts Are Mapped to Contexts

**Concepts and Their Status:**

| Concept | Status | Maps Show |
|---------|--------|-----------|
| Evidence | Observed as infrastructure in all maps | Shared by all contexts; not owned by any (contradicts Phase 2 "pillar" designation) |
| Authority | H-B and H-C both viable (unresolved) | Similar pattern in all contexts, but whether uniform (H-C) or varied (H-B) unclear |
| Legitimacy | Emergent Property (observed) | Appears in all contexts; created by combination of Authority + Recognition + Governance + Verification |
| Recognition | Significant Pattern (observed) | Appears in all contexts; enables legitimacy; deep investigation deferred |
| Verification | Ambiguous | Three different architectural roles across maps; governance must choose |

**Evidence:**
- All concepts appear in multiple contexts
- No single concept is owned by one context
- Concepts appear to be orthogonal to context boundaries (cross-cutting or emergent)

**Confidence:** HIGH (concepts mapped); MEDIUM (architectural roles determined)

**Remaining Uncertainty:**
- How do contexts interact through these concepts?
- What are the "contracts" between contexts for shared concepts?

**Implication for Round 8:** Round 8 context relationship mapping must make explicit how contexts interact through these shared concepts.

---

### Finding 5: Boundary Stress Testing Worked

**Scenarios Tested:**
1. Membership independence ✗ breaks
2. Election independence ✗ breaks
3. Verification as standalone context ⚠ works but weak
4. Merging Membership + Election ✗ breaks
5. Merging Governance + Appeals ✗ breaks
6. Fraud Investigation placement ⚠ weak standalone
7. Evidence ownership ✗ must be shared
8. Authority behavior consistency ✓ works across contexts

**Evidence:**
- Core boundaries (Membership, Election, Governance, Appeals) held under pressure
- Weak boundaries (Verification standalone, Fraud Investigation standalone) identified early
- Shared infrastructure (Evidence) necessity confirmed
- Authority consistency pattern confirmed

**Confidence:** HIGH

**Remaining Uncertainty:**
- Whether internal context structure will hold when detailed
- Whether Round 8 will reveal additional stress points

**Implication for Round 8:** Boundaries are strong enough to map relationships; internal structure may need revision based on context contracts.

---

## Section 2 — Findings That Remain Ambiguous

These findings survived investigation but lack definitive answers.

### Ambiguity 1: Verification Architectural Role

**Competing Interpretations:**

**Interpretation A: Verification as Bounded Context (Map A)**
- Pros: Directly addresses "constitutional pillar" designation; clear ownership
- Cons: Context has no independent decisions; weak on heuristics
- Evidence: Phase 2 called it pillar; Map A treats as context

**Interpretation B: Verification as Infrastructure Layer (Map B)**
- Pros: Reduces context count; centralizes verification logic
- Cons: Infrastructure layer becomes complex; blurs ownership
- Evidence: Map B treats as infrastructure; simplifies context count

**Interpretation C: Verification as Distributed Capability (Map C)**
- Pros: Each context owns its own legitimacy determination; minimal pressure; clear contracts
- Cons: Verification logic duplicated; requires consistency discipline
- Evidence: Map C treats as capability; most viable under testing

**Impact on Round 8:** CRITICAL
- Determines how context decision-making is structured
- Affects contracts between contexts
- Affects how "legitimacy" is enforced

**Governance Must Decide:** Which interpretation aligns with architectural intent?

---

### Ambiguity 2: Authority Nature (H-B vs H-C)

**Competing Hypotheses:**

**Hypothesis H-B: Authority Family**
- Different authority types in different contexts
- Membership Authority behaves differently from Election Authority
- Evidence: Phase 2 "Authority Family" identified as viable

**Hypothesis H-C: Authority Cross-Cutting**
- Authority behaves the same everywhere (like Time, Identity)
- Pattern: scoped, temporal, challengeable, recognition-dependent
- Evidence: Round 7 observed consistent patterns consistent with H-C; Map C assumes this

**Status:** H-C remains viable. H-B (Authority Family) also remains viable.

**Impact on Round 8:** HIGH
- H-C implies Authority is primitive; contexts inherit authority pattern
- H-B implies Authority varies; each context defines its own authority structure
- Affects context relationship design

**Governance Must Decide:** Which hypothesis should guide Round 8, or should Round 8 test both?

---

### Ambiguity 3: Evidence Architectural Status

**Phase 2 Classification:** "Constitutional pillar" (co-equal with Verification)

**Round 7 Observation:** Evidence behaves like shared infrastructure within all currently explored maps (not owned by any single context)

**Contradiction:** Phase 2 calls Evidence a pillar; Round 7 maps treat it as infrastructure. These designations may not be mutually exclusive.

**Possible Resolutions:**
- Evidence IS infrastructure; Phase 2 language was aspirational
- Evidence IS pillar; Round 7 architectural treatment was wrong
- Evidence is BOTH (infrastructure status + pillar significance)

**Impact on Round 8:** HIGH
- Affects shared infrastructure design
- Affects how contexts store and access evidence
- Affects evidence governance (who defines schema?)

**Governance Must Decide:** Is Evidence pillar or infrastructure?

---

### Ambiguity 4: Recognition Architectural Significance

**Phase 2 Finding:** "Recurring pattern but not deeply investigated"

**Round 7 Finding:** Recognition appears in every context; appears significant but role unclear

**Competing Interpretations:**
- Recognition is domain-level concept (belongs to a context)
- Recognition is cross-cutting concern (appears in all contexts)
- Recognition is emergent property (created by combination of factors)

**Impact on Round 8:** MEDIUM
- Does NOT block context boundary decisions
- Affects how contexts handle legitimacy of decisions
- Can be deferred to Round 9

**Governance Can Defer:** Recognition can be investigated later without blocking Round 8.

---

### Ambiguity 5: Legitimacy Foundation

**Phase 2 Observation:** Legitimacy appears to be emergent (Authority + Recognition + Governance compliance + Verification)

**Round 7 Testing:** Maps assume legitimacy is emergent; alternative (foundational) not tested

**Competing Interpretations:**
- Legitimacy is EMERGENT (created by combination)
- Legitimacy is FOUNDATIONAL (exists as primitive; enhanced by combination)

**Impact on Round 8:** MEDIUM
- Affects how contexts validate decisions internally
- Does NOT affect context boundaries
- Can be clarified during Round 8 contract design

**Governance Can Defer:** Legitimacy foundation can be clarified during Round 8 without blocking start.

---

## Section 3 — Contradictions

Five contradictions emerged between Phase 2 and Round 7. These are not failures; they are governance questions.

### Contradiction 1: Evidence Pillar vs Infrastructure

**Phase 2 Statement:** "Evidence and Verification are co-equal constitutional pillars."

**Round 7 Finding:** All three maps treat Evidence as infrastructure, not as a domain context.

**Severity:** HIGH (fundamental architectural interpretation)

**Architectural Impact:**
- If pillar: Evidence deserves dedicated context or major infrastructure component
- If infrastructure: Evidence is treated like logging/storage (shared, not domain-owned)

**Blocks Round 8?** POSSIBLY
- If Evidence is truly pillar, Round 8 contracts must reflect that status
- If infrastructure, Round 8 can proceed with current treatment

**Governance Question:** Is Phase 2 "constitutional pillar" language the architectural intent, or was it rhetorical?

---

### Contradiction 2: Verification Pillar vs Capability

**Phase 2 Statement:** "Verification is a constitutional pillar."

**Round 7 Finding:** Three different architectural roles proposed (context, infrastructure, distributed).

**Severity:** HIGH (fundamental architectural interpretation)

**Architectural Impact:**
- If pillar: Verification deserves explicit architectural presence
- If capability: Verification is a service provided by decision-making contexts

**Blocks Round 8?** YES
- Round 8 context relationships depend on knowing Verification's role
- Must be clarified before Round 8 can finalize contracts

**Governance Question:** What did Phase 2 mean by "constitutional pillar"? Is it proven architectural role or aspirational framing?

---

### Contradiction 3: Authority Family vs Cross-Cutting

**Phase 2 Decision:** "H-A significantly weakens; H-B (Family) and H-C (Cross-Cutting) both viable; unresolved."

**Round 7 Choice:** Map C implicitly assumes H-C (cross-cutting); H-B not fully tested.

**Severity:** HIGH (shapes context architecture)

**Architectural Impact:**
- H-B (Family): Each context type has different authority patterns; contexts are heterogeneous
- H-C (Cross-Cutting): All contexts follow same authority pattern; contexts are homogeneous in authority structure

**Blocks Round 8?** NO (but shapes it)
- Round 8 can proceed with provisional assumption
- May need revision if assumption wrong

**Governance Question:** Should Round 8 commit to H-C assumption, or test H-B as alternative?

---

### Contradiction 4: Recognition Role Assigned vs Deferred

**Phase 2 Decision:** "Recognition is recurring pattern but not deeply investigated; significance unresolved."

**Round 7 Treatment:** All maps assign Recognition a role (secondary to Authority but essential to Legitimacy).

**Severity:** MEDIUM (affects legitimacy formula but not boundaries)

**Architectural Impact:**
- If Recognition is architectural: Must be designed explicitly into Round 8
- If Recognition is social/political: Architecture can enable but not enforce it

**Blocks Round 8?** NO
- Does not affect context boundaries
- Can be clarified during Round 8 or deferred to Round 9

**Governance Question:** Is Round 7's assignment of Recognition role acceptable, or should it remain unresolved?

---

### Contradiction 5: Governance Centrality

**Phase 2:** No explicit assessment of Governance centrality

**Round 7 Finding:** All three maps show every other context depending on Governance for policy.

**Severity:** MEDIUM (architectural risk, not boundary issue)

**Architectural Impact:**
- Governance is single point of policy definition
- If Governance fails, system cannot function
- Alternatively: Governance centrality is the intended constitutional structure

**Blocks Round 8?** NO
- Does not affect context boundaries
- Should be evaluated for resilience during Round 8
- May inform governance structure design

**Governance Question:** Is Governance centrality strength (unified policy) or vulnerability (single point of failure)?

---

## Section 4 — Round 8 Blocking Analysis

For each unresolved question: Does this prevent Context Architecture in Round 8?

### Potential Blockers (May Affect Round 8 Design)

**Potential Blocker 1: Verification Architectural Role**
- **Question:** Is Verification a context, infrastructure, or distributed capability?
- **Architectural Impact:** May affect Round 8 context relationship design (depending on role chosen)
- **Can Round 8 proceed without resolution?** POSSIBLY — Round 8 may actually help resolve this through context mapping
- **Options:**
  - **A) Resolve before Round 8:** Governance decides Verification role now; Round 8 designs relationships accordingly
  - **B) Carry forward as assumption:** Round 8 assumes one role (e.g., Map C: distributed); if assumption wrong, findings are revisable
- **ARB Must Decide:** Which approach is preferable (efficiency vs. discovery-driven)?

### Important Non-Blockers (Important But Don't Prevent Round 8)

**Non-Blocker 1: Authority Nature (H-B vs H-C)**
- **Question:** Is Authority family or cross-cutting?
- **Why non-blocking:** Round 8 can proceed with provisional assumption (either H-B or H-C); if assumption wrong, Round 8 artifacts are revisable
- **Can Round 8 proceed?** YES, with explicit acknowledgment of provisional assumption
- **Resolution can be deferred?** YES, Round 8 may help validate which hypothesis is correct
- **Note:** Phase 2 explicitly left both H-B and H-C viable; neither has been proven

**Non-Blocker 2: Evidence Pillar vs Infrastructure**
- **Question:** Is Evidence truly infrastructure, or does it have domain significance?
- **Why non-blocking:** Round 8 can model Evidence as shared infrastructure; if pillar designation matters, it affects Round 9 detailed design
- **Can Round 8 proceed?** YES, with understanding that Evidence status may change
- **Resolution can be deferred?** YES, to Round 8 findings

**Non-Blocker 3: Legitimacy Emergent vs Foundational**
- **Question:** Is Legitimacy created by combination, or does it exist as primitive?
- **Why non-blocking:** Round 8 context contracts can treat it either way; detailed behavior clarified later
- **Can Round 8 proceed?** YES, with observation that Legitimacy is tied to context decisions
- **Resolution can be deferred?** YES, to Round 8/9 design

**Non-Blocker 4: Recognition Role**
- **Question:** Is Recognition architecturally significant?
- **Why non-blocking:** Does not affect context boundaries; affects internal decision-making
- **Can Round 8 proceed?** YES, understanding that Recognition patterns will emerge during mapping
- **Resolution can be deferred?** YES, to Round 8 or later

**Non-Blocker 5: Governance Centrality**
- **Question:** Is Governance central authority a strength or vulnerability?
- **Why non-blocking:** Does not affect context boundaries; affects governance architecture resilience
- **Can Round 8 proceed?** YES, with understanding that centrality may need mitigation strategies
- **Resolution can be deferred?** YES, to governance design phase

---

## Section 5 — Preliminary Governance Position

Without finalizing answers, the evidence suggests a governance position.

### Evidence Summary by Category

**Strong Findings (High Confidence):**
- Four core bounded contexts viable (Membership, Election, Governance, Appeals)
- Multiple architectural maps possible (A, B, C all work)
- Boundaries hold under stress testing
- Core concepts (Evidence, Authority, Legitimacy, Recognition) are mapped to contexts

**Ambiguous Findings (Medium Confidence):**
- Verification role (three alternatives)
- Authority nature (H-B vs H-C)
- Evidence status (pillar vs infrastructure)
- Recognition significance (architectural vs social)
- Legitimacy foundation (emergent vs primitive)

**Blocking Issues:** 1 (Verification role must be resolved)

**Non-Blocking Issues:** 5 (can be deferred or provisionally resolved)

**Contradictions:** 5 (identified and explained; not hidden)

---

### Preliminary Position (Not Yet Final)

Based on evidence reduction:

**Q0 Likely:** YES — Round 7 successfully tested multiple maps

**Q1 Likely:** YES — Four bounded context candidates strongly identified

**Q2 Likely:** PARTIAL — Some concepts clarified; some contradictory (Evidence pillar, Verification role)

**Q3 Likely:** YES — Major contradictions surfaced and documented

**Q4 Likely:** YES (CONDITIONAL) — Uncertainty reduced enough to start Round 8, BUT Verification role must be resolved first

**Q5 Likely:** MINOR — Some discovery areas identified (Recognition, Legitimacy foundation) but non-blocking

---

### Likely Outcome

**Based on current evidence:** Outcome B (Round 8 Conditionally Authorized)

**Likely Conditions:**
1. Verification architectural role clarified before Round 8
2. Governance assumption (H-C) documented as provisional
3. Evidence status clarified (is it pillar or infrastructure?)
4. Round 8 explicitly test provisional assumptions

**Rationale:**
- Evidence is strong enough to proceed
- Critical blocking issue is narrow (Verification role)
- Once Verification resolved, Round 8 can map context relationships
- Non-blocking ambiguities can be clarified during Round 8 or deferred

---

## Next Steps

This synthesis is ready for ARB review.

Once synthesis is reviewed, ARB proceeds to:

```text
Q0–Q5 Assessment
    ↓
Decision Matrix Completion
    ↓
Outcome A/B/C Selection
    ↓
Round 8 Authorization (or further discovery directive)
```

---

**Status:** SYNTHESIS COMPLETE — Ready for Q0–Q5 governance decisions

