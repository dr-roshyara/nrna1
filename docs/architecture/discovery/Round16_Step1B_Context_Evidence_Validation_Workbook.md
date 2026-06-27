# Round 16 Step 1B — Context Evidence Validation Workbook

**Purpose:** Validate whether current candidate contexts are supported by observable domain evidence.

**Status:** Evidence Validation

**Date:** 2026-06-06

**Critical Note:** This workbook does NOT create new contexts, redesign candidates, or make architecture decisions. It evaluates whether evidence supports, weakens, merges, splits, or eliminates current candidates.

---

## Validation Framework

For each finding:

1. **Current Analysis** — What does our architectural analysis suggest?
2. **Interpretation** — What does this mean?
3. **Alternative Interpretation** — What else could this mean?

---

## Validation Area 1: Language Conflict Matrix

**Question:** Do terms have different meanings across candidate contexts?

### Terms Appearing in Multiple Contexts

| Term | Voting Context | Voter Registration | Vote Tallying | Election Admin | Governance | Audit | Same Meaning? | Conflict Strength |
|------|---|---|---|---|---|---|---|---|
| **Verification** | Voter verifies vote recorded correctly | Verify voter eligibility | Verify all votes counted | N/A | Verify authority valid | Verify election integrity | NO | STRONG CONFLICT |
| **Authority** | N/A | N/A | N/A | Permission to delegate | Right to decide | Authority to challenge | NO | STRONG CONFLICT |
| **Election** | The election context | The election context | The election context | Core entity | Rules context | Context for audit | YES | No conflict |
| **Eligibility** | Not used | Core concept | Not used | Not used | Not used | Challenge eligibility | DIFFERENT ROLE | WEAK CONFLICT |
| **Certification** | N/A | N/A | Declare results official | N/A | May approve rules | Audit certification | Different meaning | MODERATE CONFLICT |
| **Challenge** | N/A | N/A | N/A | N/A | Challenge decision | Challenge result | Different meaning | MODERATE CONFLICT |
| **Result** | N/A | N/A | Vote counts per candidate | N/A | N/A | Result accuracy | Different meaning | WEAK CONFLICT |
| **Governance** | Governance rules apply | Eligibility rules apply | N/A | Core concept | Core concept | Rules being audited | Different meaning | MODERATE CONFLICT |

---

### Key Findings from Language Conflict Matrix

**CANDIDATE LANGUAGE CONFLICTS:**

1. **Verification** has fundamentally different meanings:
   - In Voting: "Voter confirms their selections recorded"
   - In Voter Registration: "System confirms voter eligibility"
   - In Vote Tallying: "System confirms all votes counted"
   - In Audit: "Auditor confirms election integrity"
   
   **Interpretation:** These are four different verification models. Suggests at least three separate contexts (Voting, Voter Registration, Vote Tallying may share verification concept; Audit is different).

   **Alternative Interpretation:** "Verification" is a cross-cutting concern shared across contexts.

**CANDIDATE LANGUAGE CONFLICTS:**

2. **Authority** has fundamentally different meanings:
   - In Governance & Authority: "Permission to make decisions"
   - In Election Admin: "Scope of delegated responsibility"
   - In Audit: "Authority to challenge decisions"
   
   **Interpretation:** Authority is used differently across proposed contexts. Suggests Authority may not be a coherent bounded context.

   **Alternative Interpretation:** Authority is a unifying concept across contexts (though Round 15 left this unresolved).

**MODERATE LANGUAGE CONFLICTS:**

3. **Certification** appears in:
   - Vote Tallying: "Results are officially declared"
   - Governance: "Rules are approved/certified"
   - Audit: "Integrity is certified"
   
   **Interpretation:** Certification has different business meanings in different contexts.

   **Alternative Interpretation:** Certification is a pattern that repeats across contexts.

**MODERATE LANGUAGE CONFLICTS:**

4. **Governance** and **Governance Rules** appear in multiple candidates:
   - As rules that apply to voting
   - As rules for eligibility
   - As rules for tallying
   - As core concept in Election Administration
   - As rules being audited
   
   **Interpretation:** "Governance" may be a cross-cutting concern, not a bounded context.

   **Alternative Interpretation:** Governance is a coherent context with sub-domains.

---

### Language Conflict Assessment

**RESULT: MODERATE-to-STRONG language conflicts exist across candidates.**

**Implication:** Proposed candidates may not have clean language boundaries. Further investigation needed.

---

## Validation Area 2: Consistency Boundary Matrix

**Question:** Are invariants unique to candidates or shared across them?

### Invariants by Candidate

| Invariant | Voting | Voter Reg | Tallying | Election Admin | Governance | Audit | Unique? | Shared? |
|---|---|---|---|---|---|---|---|---|
| **Vote immutability** | ✓ Core | - | ✓ (same vote) | - | - | ✓ (verifies) | VOTING specific | Shared with Tallying |
| **Voter-vote unlinkability** | ✓ Core | - | - | - | - | ✓ (verifies) | VOTING specific | Observed by Audit |
| **Eligibility lock-in** | - | ✓ Core | - | - | - | ✓ (verifies) | REG specific | Observed by others |
| **All votes counted** | - | - | ✓ Core | - | - | ✓ (verifies) | TALLYING specific | Verified by Audit |
| **No double-counting** | - | - | ✓ Core | - | - | ✓ (verifies) | TALLYING specific | Verified by Audit |
| **Result immutability** | - | - | ✓ Core | - | ✓ (rules govern) | ✓ (verifies) | TALLYING primary | Shared across contexts |
| **Authority delegation consistency** | - | - | - | ✓ Applies rules | ✓ Core | ✓ (can be challenged) | GOVERNANCE/ADMIN | Observed by others |
| **Governance rule immutability during voting** | ✓ (applies rules) | ✓ (applies rules) | ✓ (applies rules) | ✓ Core | ✓ Core | - | Shared across contexts | Not Audit-specific |

---

### Key Findings from Consistency Boundary Matrix

**STRONG CONSISTENCY BOUNDARIES:**

1. **Voting has unique anonymity invariant:**
   - Voter-vote unlinkability is enforced ONLY in Voting
   - Other contexts observe/verify it but don't enforce it
   - **Interpretation:** Voting has a core invariant no other context has. This is evidence for separate boundary.
   - **Confidence:** Current (based on architectural analysis)

2. **Voter Registration has unique eligibility lock-in:**
   - Eligibility freeze is enforced in Voter Registration
   - Other contexts depend on this invariant
   - **Interpretation:** Voter Registration owns its core invariant. Evidence for separate context.
   - **Confidence:** Current (based on architectural analysis)

3. **Vote Tallying has unique counting invariants:**
   - No double-counting and all-votes-counted are core to Tallying
   - Other contexts don't enforce these
   - **Interpretation:** Tallying has independent invariants. Evidence for separate context.
   - **Confidence:** Current (based on architectural analysis)

**SHARED CONSISTENCY BOUNDARIES:**

4. **Governance rule immutability is shared:**
   - Rules immutable during voting affects MULTIPLE contexts
   - Not unique to any single candidate
   - **Interpretation:** This may be cross-cutting policy, not a context boundary.
   - **Implication:** Election Administration + Governance may not be separate.

---

### Consistency Boundary Assessment

**RESULT: STRONG consistency boundaries for Voting, Voter Registration, Vote Tallying. WEAK boundaries for Election Administration and Governance.**

**Implication:** Three candidates (Voting, Voter Registration, Vote Tallying) have defensible consistency boundaries. Others need investigation.

---

## Validation Area 3: Decision Ownership Matrix

**Question:** Who makes decisions and can they be made independently?

### Decisions by Candidate

| Decision | Made By | Decision Owner | Can Override? | Evidence |
|---|---|---|---|---|
| **Is voter eligible to vote now?** | Voter Registration | Voter Registration Context (independent) | Yes, by Audit challenge | Voting depends on this decision |
| **Are selections valid per rules?** | Voting | Voting Context (independent) | Unclear | Core voting invariant |
| **Is vote recorded?** | Voting | Voting Context (independent) | Never (immutable) | Core voting responsibility |
| **What is vote count per candidate?** | Vote Tallying | Vote Tallying Context (independent) | Yes, by recount/Audit | Computational decision |
| **Are all votes counted?** | Vote Tallying | Vote Tallying Context (independent) | Verified by Audit | Quality assurance |
| **Can results be published?** | Election Admin | Election Admin (shared with authority) | Yes, by authority | Depends on authority decision |
| **Who has authority to X?** | Governance/Authority | Unclear (unresolved) | Can be challenged | Round 15 left open |
| **Is challenge valid?** | Audit/Governance | Unclear (shared) | Unclear | Round 15 left open |

---

### Key Findings from Decision Ownership Matrix

**INDEPENDENT DECISION OWNERSHIP:**

1. **Voting makes independent decisions about selection validity:**
   - Voting enforces rules independently
   - Other contexts don't override voting rules
   - **Interpretation:** Voting owns its decision space.
   - **Confidence:** Current (based on architectural analysis)

2. **Voter Registration makes independent eligibility decisions:**
   - Eligibility determination is localized
   - Other contexts depend on this decision
   - **Interpretation:** Voter Registration owns eligibility decision.
   - **Confidence:** Current (based on architectural analysis)

3. **Vote Tallying makes independent counting decisions:**
   - Counting is deterministic/independent
   - Other contexts accept the count
   - **Interpretation:** Vote Tallying owns its computational domain.
   - **Confidence:** Current (based on architectural analysis)

**SHARED/UNCLEAR DECISION OWNERSHIP:**

4. **Election Administration and Governance share decision space:**
   - Who decides what governance rules apply? (Admin or Governance?)
   - Who decides who has authority? (Admin or Governance?)
   - **Interpretation:** Election Administration and Governance may not be separate contexts. Or they may have unclear boundaries.
   - **Implication:** These two candidates need further investigation.

5. **Audit and Governance both handle challenges:**
   - Can Audit challenge independently or only report?
   - Who decides if challenge is valid?
   - **Interpretation:** Audit and Governance decision space is unclear.
   - **Round 15 Constraint:** Authority Classification unresolved. This explains the confusion.

---

### Decision Ownership Assessment

**RESULT: Voting, Voter Registration, and Vote Tallying have defensible independent decision space. Election Administration, Governance, and Audit have shared/unclear decision ownership.**

**Implication:** Weak candidates need clarification before boundaries can be approved.

---

## Validation Area 4: Actor Boundary Matrix

**Question:** Which actors interact primarily with which candidates?

### Actors by Candidate

| Actor | Voting | Voter Reg | Tallying | Election Admin | Governance | Audit | Primary Context |
|---|---|---|---|---|---|---|---|
| **Voter** | Primary | Subject | None | None | None | None | VOTING |
| **Election Officer** | None | Primary | Secondary | Primary | Secondary | Secondary | VOTER REG, ELECTION ADMIN |
| **System** | Primary (enforces) | Secondary (validates) | Primary (counts) | Secondary | None | None | VOTING, VOTE TALLYING |
| **Auditor** | None | None | None | None | Secondary | Primary | AUDIT |
| **Governance Authority** | None | None | None | Secondary | Primary | Secondary | GOVERNANCE |
| **Organization Admin** | None | None | Secondary | Primary | Primary | Secondary | ELECTION ADMIN |

---

### Key Findings from Actor Boundary Matrix

**DISTINCT ACTOR ECOSYSTEMS:**

1. **Voting has unique actor set:**
   - Voter is primary (only context where Voter makes decisions)
   - System enforces rules
   - Other actors absent
   - **Interpretation:** Voting has a distinct actor ecosystem.
   - **Confidence:** Current (based on architectural analysis)

2. **Voter Registration has distinct primary:**
   - Officer is primary decision-maker
   - Voter is subject of decisions
   - **Interpretation:** Different power relationship from Voting.
   - **Confidence:** Current (based on architectural analysis)

3. **Vote Tallying is system-primary:**
   - System performs work
   - Officer observes/publishes
   - **Interpretation:** Distinct from other contexts.
   - **Confidence:** Current (based on architectural analysis)

**SHARED ACTOR ECOSYSTEMS:**

4. **Election Administration and Governance overlap:**
   - Both involve Officer, Admin, Governance Authority
   - Unclear who is primary
   - **Interpretation:** May be same context or unclear boundary.
   - **Implication:** These may need to be merged.

5. **Audit overlaps with Governance:**
   - Both involve Auditor, Governance Authority
   - Unclear who decides what
   - **Interpretation:** Audit may be sub-capability of Governance, not separate context.
   - **Round 15 Constraint:** Authority unresolved. This explains confusion.

---

### Actor Boundary Assessment

**RESULT: Voting, Voter Registration, Vote Tallying have distinct actor ecosystems. Election Administration, Governance, and Audit have overlapping actors with unclear primary ownership.**

**Implication:** Overlap suggests possible merges or unclear boundaries.

---

## Validation Area 5: Lifecycle Independence Matrix

**Question:** Can each candidate evolve and be versioned independently?

### Lifecycle Independence

| Candidate | Can Evolve Independently? | Can Be Versioned Separately? | Evidence | Independence |
|---|---|---|---|---|
| **Voting** | Yes | Yes | Voting rules change independently of eligibility rules | STRONG |
| **Voter Registration** | Yes | Yes | Eligibility rules can change without affecting voting process | STRONG |
| **Vote Tallying** | Yes | Yes | Counting algorithms can change independently | STRONG |
| **Election Administration** | Unclear | Unclear | Depends on whether governance rules are separate | WEAK |
| **Governance & Authority** | Unclear | Unclear | Authority classification unresolved (Round 15) | WEAK |
| **Audit** | Unclear | Unclear | May depend on what other contexts audit | WEAK |

---

### Key Findings from Lifecycle Independence

**INDEPENDENT LIFECYCLES:**

1. **Voting lifecycle is independent:**
   - Ballot design can change independently
   - Selection rules can evolve
   - Doesn't require governance rule changes
   - **Interpretation:** Voting can be versioned independently.
   - **Confidence:** Current (based on architectural analysis)

2. **Voter Registration lifecycle is independent:**
   - Eligibility rules can change without affecting voting
   - Voter attributes can expand independently
   - **Interpretation:** Can be versioned separately.
   - **Confidence:** Current (based on architectural analysis)

3. **Vote Tallying lifecycle is independent:**
   - Counting algorithms can improve
   - Result publication rules can change
   - Doesn't require voting changes
   - **Interpretation:** Can evolve independently.
   - **Confidence:** Current (based on architectural analysis)

**INTERDEPENDENT LIFECYCLES:**

4. **Election Administration and Governance are entangled:**
   - Changes to governance rules affect election setup
   - Changes to election setup affect governance application
   - **Interpretation:** May not have independent lifecycles.
   - **Implication:** May be same context or tightly coupled.

5. **Audit depends on other contexts:**
   - Audit rules depend on what other contexts do
   - Audit lifecycle follows election lifecycle
   - **Interpretation:** Audit may not be independent.
   - **Implication:** Audit may be policy layer over other contexts.

---

### Lifecycle Independence Assessment

**RESULT: Voting, Voter Registration, Vote Tallying have independent lifecycles. Election Administration, Governance, and Audit are entangled or dependent.**

**Implication:** Entanglement suggests possible merge or policy/cross-cutting classification.

---

## Validation Area 6: Candidate Context Reassessment

Based on evidence validation, reassess each candidate.

### Candidate 1: Voting

**Current Status:** STRONG CANDIDATE

**Evidence Summary:**
- Language: UNIQUE vocabulary (Vote, Ballot, Anonymity)
- Consistency: UNIQUE invariant (voter-vote unlinkability)
- Decision: INDEPENDENT decision space (selection validity)
- Actors: DISTINCT ecosystem (Voter primary)
- Lifecycle: INDEPENDENT (can evolve separately)

**All five validation areas support this candidate.**

**Validation Result:** ✅ Currently Supported by Analysis

**Confidence:** HIGH

---

### Candidate 2: Voter Registration

**Current Status:** STRONG CANDIDATE

**Evidence Summary:**
- Language: DISTINCT vocabulary (Voter, Eligibility, Region)
- Consistency: UNIQUE invariant (eligibility lock-in)
- Decision: INDEPENDENT decision space (eligibility determination)
- Actors: DISTINCT ecosystem (Officer primary, Voter as subject)
- Lifecycle: INDEPENDENT (eligibility rules change independently)

**All five validation areas support this candidate.**

**Validation Result:** ✅ Currently Supported by Analysis

**Confidence:** HIGH

---

### Candidate 3: Vote Tallying

**Current Status:** STRONG CANDIDATE

**Evidence Summary:**
- Language: SPECIALIZED vocabulary (Result, Tally, Certification)
- Consistency: UNIQUE invariants (no double-counting, all votes counted)
- Decision: INDEPENDENT decision space (counting is deterministic)
- Actors: DISTINCT ecosystem (System primary)
- Lifecycle: INDEPENDENT (counting algorithms can evolve)

**All five validation areas support this candidate.**

**Validation Result:** ✅ Currently Supported by Analysis

**Confidence:** MEDIUM-HIGH

---

### Candidate 4: Election Administration

**Current Status:** WEAK-MEDIUM CANDIDATE

**Evidence Summary:**
- Language: SHARED governance language with Governance context
- Consistency: SHARED rule immutability with other contexts (not unique)
- Decision: SHARED ownership with Governance (unclear primary)
- Actors: OVERLAPPING with Governance (Officer, Admin, Authority)
- Lifecycle: ENTANGLED with Governance (interdependent evolution)

**Validation Areas 2, 3, 4, 5 show WEAK or SHARED evidence.**

**Alternative Interpretation A:** Election Administration should be merged with Governance & Authority into single context.

**Alternative Interpretation B:** Election Administration is infrastructure/coordination layer, not a domain context.

**Alternative Interpretation C:** Election Administration and Governance are separate but boundaries need clarification (governance rules vs. election instance).

**Validation Result:** ⚠️ WEAKENED — Requires Split or Merge Investigation

**Confidence:** MEDIUM (conflicting evidence)

**Next Step:** Determine if governance rules and election instance are different models or same.

---

### Candidate 5: Governance & Authority

**Current Status:** WEAK CANDIDATE (blocked by Round 15)

**Evidence Summary:**
- Language: CONFLICTING (Authority means different things in different contexts)
- Consistency: SHARED constraints (rule immutability affects all contexts)
- Decision: UNCLEAR ownership (shared with other contexts)
- Actors: OVERLAPPING with Election Administration and Audit
- Lifecycle: ENTANGLED (depends on other contexts)

**Round 15 Constraint:** Authority Classification remains UNRESOLVED. This candidate assumes Authority is coherent.

**Validation Areas 1, 3, 4 show WEAK or SHARED evidence.**

**Alternative Interpretation A:** Authority is a cross-cutting concern, not a bounded context.

**Alternative Interpretation B:** Authority is a coherent concept (once classified) that forms a context.

**Alternative Interpretation C:** Authority should be split: "Permission Management" (clear) vs. "Authority Exercise" (unclear).

**Validation Result:** ⚠️ INCONCLUSIVE — Authority Classification remains unresolved (Round 15 constraint)

**Confidence:** Current (based on limited analysis due to unresolved classification)

**Next Step:** Authority Classification remains an active question. Tactical DDD may proceed with Authority as an open question per Round 15 ARB authorization.

---

### Candidate 6: Audit

**Current Status:** WEAK CANDIDATE

**Evidence Summary:**
- Language: CONFLICTING with other contexts (Verification, Challenge, Certification mean different things)
- Consistency: NO UNIQUE invariants (only observes/verifies others' invariants)
- Decision: SHARED with Governance (unclear who decides on challenges)
- Actors: OVERLAPPING with Governance (Auditor + Authority share decision space)
- Lifecycle: DEPENDENT on other contexts (audit follows elections, not independent)

**Validation Areas 1, 2, 3, 4, 5 show WEAK or SHARED evidence.**

**Alternative Interpretation A:** Audit is a policy/governance layer over other contexts, not a domain context.

**Alternative Interpretation B:** Audit is an infrastructure concern (logging, verification) not a domain boundary.

**Alternative Interpretation C:** Audit should be merged with Governance & Authority (both about decision validation).

**Validation Result:** ⚠️ UNRESOLVED — Current evidence insufficient to determine whether Audit is domain context, policy layer, or cross-cutting concern

**Confidence:** Current (based on limited analysis)

**Next Step:** Further investigation required. Audit classification remains an open question.

---

## Summary of Validation Results

| Candidate | Initial Assessment | Validation Result | Confidence | Status |
|---|---|---|---|---|
| **Voting** | STRONG | ✅ Currently Supported by Analysis | Current | Exhibits strongest boundary signals |
| **Voter Registration** | STRONG | ✅ Currently Supported by Analysis | Current | Exhibits strongest boundary signals |
| **Vote Tallying** | STRONG | ✅ Currently Supported by Analysis | Current | Exhibits strong boundary signals |
| **Election Administration** | WEAK-MEDIUM | ⚠️ WEAKENED | Current | Requires Split/Merge Investigation |
| **Governance & Authority** | WEAK | ⚠️ INCONCLUSIVE | Current | Unresolved pending Authority Classification (active question) |
| **Audit** | WEAK | ⚠️ UNRESOLVED | Current | Classification unresolved; Further investigation required |

---

## Key Findings

### Three Candidates Currently Exhibit Strongest Boundary Signals

```
Voting               ✅ Currently Supported by Analysis
Voter Registration   ✅ Currently Supported by Analysis
Vote Tallying        ✅ Currently Supported by Analysis
```

Based on architectural analysis, these three exhibit the strongest boundary signals across all five validation areas.

**Important:** This represents current analysis confidence, not proven bounded contexts. Additional evidence may strengthen, weaken, merge, or eliminate these candidates.

### Three Candidates Exhibit Weaker or Unresolved Signals

```
Election Administration  ⚠️ Shared language, shared consistency, unclear ownership
Governance & Authority   ⚠️ Inconclusive pending Authority Classification (active question per Round 15)
Audit                    ⚠️ Unresolved; insufficient evidence to determine classification
```

### No Aggregates Proposed

This workbook contains no aggregate ownership claims. Aggregate discovery is later.

### Authority Classification Remains Open

This workbook preserves the Round 15 ARB decision: Authority Classification remains an active question, not a gate.

---

## Next Steps

**Do NOT proceed to Step 2 (Context Mapping) yet.**

Instead:

1. **Investigate Election Administration:**
   - Should it be merged with Governance?
   - Should it be split (governance rules vs. election instance)?
   - Or is it infrastructure?

2. **Investigate Audit:**
   - Is it a domain context or infrastructure/policy layer?
   - Should it be merged with Governance?
   - Can it remain open pending Authority Classification resolution?

3. **Defer Governance & Authority decision:**
   - This candidate is blocked by Round 15's unresolved Authority Classification
   - Tactical DDD proceeds with Authority as an open question
   - This context may emerge or be rejected depending on Authority classification

---

**STATUS: Architectural Analysis Complete**

**Candidates with Strongest Signals (Based on Current Analysis):** 3 (Voting, Voter Registration, Vote Tallying)

**Candidates Requiring Further Investigation:** 3 (Election Administration, Governance & Authority, Audit)

**Ready for Step 1C:** Candidate Context Reassessment (addressing investigation questions and alternative interpretations)

**Then Ready for Step 2:** Context Mapping (once confident candidates are identified)

---

**This workbook presents architectural analysis of six candidate contexts. Three currently exhibit stronger boundary signals based on this analysis. Three others require further investigation before any conclusions can be drawn. No bounded contexts are approved based on this analysis alone.**
