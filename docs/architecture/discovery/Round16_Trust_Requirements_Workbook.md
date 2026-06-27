# Round 16 — Trust Requirements Workbook

**Purpose:** Consolidate all trust-related discoveries gathered during Round 16 investigation phases.

**Date:** 2026-06-07

**Status:** Synthesis of Existing Discoveries (Questions not yet asked)

---

## Section 1: Trust Priorities (Observed)

From architect ranking in Session 1:

| Rank | Trust Guarantee | Statement |
|------|-----------------|-----------|
| 1 | Vote Integrity | "Your vote was recorded as you cast it" |
| 2 | Inclusion in Count | "Your vote is in the final count" |
| 3 | Correctness | "The result is correct" |
| 4 | Secrecy | "Nobody can see how you voted" |
| 5 | Proof of Participation | "You can prove you voted" |

**Observation:** The ranking appears to prioritize individual vote confidence (ranks 1-2: vote recorded, vote included) before overall result correctness (rank 3). This ordering reflects organizational confidence priorities, though it does not explicitly compare personal verification against universal verifiability.

---

## Section 2: Threat Model (Observed)

From Session 1, all of the following are high-priority threats:

| Threat | Actor |
|--------|-------|
| Losing candidate falsely claims election was rigged | Candidate |
| Election officer manipulates the voter list | Officer |
| Organization leader wants a specific outcome | Leadership |
| Hacker from outside the organization | External |
| Voter tries to vote twice | Voter |
| Someone pressures voters to vote a certain way | Coercion |
| Coordinated group trying to manipulate the result | Internal group |

**Observation:** Threats come from multiple actors (candidates, officers, leaders, external, voters, coercive parties, groups). No single threat dominates. Internal threats (officers, leaders, coordinated groups) are equal priority to external threats (hackers). Coercion is recognized as a threat category.

---

## Section 3: Trust-Relevant Architectural Facts

### A. Vote Table as Source of Truth

**Fact:** Vote table is the authoritative record of votes cast.

**Evidence Source:** Session 1 architect description of data flow.

**Observation:** Votes are canonical. Other tables (result table) are derived from this source.

**Trust Implication:** Vote integrity (Rank 1) depends on vote table integrity and accessibility for verification.

---

### B. Result Table as Derived Projection

**Fact:** Result table is updated in real-time when votes are saved, not in a separate tallying phase.

**Evidence Source:** State Machine Analysis Section 6c and Session 1 architect statement.

**Observation:** There is no separate "counting" workflow. Results are computed continuously as votes arrive.

**Trust Implication:** Results are not delayed or withheld. Availability of results for verification may be time-dependent (hidden during voting, available after).

---

### C. Recalculation Capability

**Fact:** Results can be recalculated from the vote table if needed.

**Evidence Source:** Session 1 architect statement: "Result can be recalculated from the vote table."

**Observation:** Vote table is replayable. Independent verification is possible.

**Trust Implication:** Correctness (Rank 3) can be independently verified through replay.

---

### D. Code Table as Anonymity Bridge

**Fact:** Code table stores mapping between voter identity and votes, but vote table stores only the votes (JSON) with no voter identification.

**Evidence Source:** Session 1 architect description: "Voter table ↔ Code table ↔ Vote table (anonymous)."

**Observation:** Voter identity is NOT in vote table. Identity lives only in code table. This structural separation enforces anonymity.

**Trust Implication:** Secrecy (Rank 4) is enforced at database design level. Voter cannot be linked to their vote through vote table alone.

---

### E. Partial Results Visibility Control

**Fact:** Partial results are hidden during voting.

**Evidence Source:** Session 1 architect statement: "Partial results are hidden during voting."

**Observation:** Results are not publicly visible while election is active.

**Trust Implication:** Voters cannot be influenced by partial results. Election integrity is protected during the voting window.

---

### F. Committee-Only Result Access

**Fact:** Only the election committee sees results before publication.

**Evidence Source:** Session 1 architect statement: "Only election committee sees results after close."

**Observation:** Results are restricted access before Chief publishes.

**Trust Implication:** Publication is a deliberate governance act (by Chief), not automatic visibility.

---

### G. State Machine Governance of Officer Actions

**Fact:** All officer transitions are gated by constitutional rules (4-check enforcement).

**Evidence Source:** State Machine Analysis Section 2.

**Observation:** Officer actions are not arbitrary. They are constrained by preconditions, role requirements, and state validation.

**Trust Implication:** Officer actions (Threat: officer manipulation) are auditable and constrained. Officer cannot unilaterally change state.

---

### H. Chief-Only Authority on Critical Gates

**Fact:** Two actions are restricted to chief only: open_voting and publish_results.

**Evidence Source:** State Machine Analysis Section 2.

**Observation:** Chief has exclusive authority over election opening and outcome visibility. Deputy cannot perform these acts.

**Trust Implication:** Critical trust boundaries (opening votes, publishing results) are concentrated in one role.

---

### I. Suspension as Governance Overlay

**Fact:** Suspension can be applied to any non-terminal state and requires documented reason.

**Evidence Source:** State Machine Analysis Section 5.

**Observation:** Elections can be frozen mid-process. Suspension requires justification (reason, category).

**Trust Implication:** Mechanism exists to halt illegitimate elections. Suspension creates an audit trail.

---

### J. Real-Time Vote Recording

**Fact:** Votes are persisted immediately as cast (not batched, not delayed).

**Evidence Source:** State Machine Analysis Section 6c.

**Observation:** No vote collection phase. Votes are recorded atomically.

**Trust Implication:** Vote loss (inclusion threat, Rank 2) is mitigated by immediate persistence.

---

## Section 4: Candidate Trust Requirements (Inferred)

Based on current evidence and architect ranking, these candidate trust requirements appear supported by architecture. Note: These are inferred from evidence, not yet validated through actual dispute discovery:

### TR1: Vote Integrity (Rank 1)

**Statement:** A voter must be able to verify their vote was recorded as cast.

**Current Evidence:**
- Vote table is authoritative record
- Votes are persisted immediately
- Vote table is replayable
- Code table provides identity-to-vote bridge

**Confidence:** Medium-High. Architecture supports verification, but mechanism for voter access to verification is not yet documented.

**Missing Evidence:**
- How does a voter access the vote table to verify?
- What format is verification provided in?
- Can voter verify without exposing their identity?

---

### TR2: Vote Inclusion (Rank 2)

**Statement:** A voter must be confident their vote is included in the final count.

**Current Evidence:**
- Result table is derived from vote table in real-time
- Recalculation is possible
- Vote table persists immediately

**Confidence:** Medium. Architecture supports this, but evidence expectations not yet explored.

**Missing Evidence:**
- What evidence convinces a voter their vote is in the count?
- Who provides this evidence?
- How is it presented to voters?

---

### TR3: Result Correctness (Rank 3)

**Statement:** The published result is mathematically correct.

**Current Evidence:**
- Result is derived from vote table
- Recalculation from vote table is possible
- Officer actions are auditable

**Confidence:** Medium. Architecture supports verification, but who has authority to verify is unclear.

**Missing Evidence:**
- Who is expected to verify correctness?
- What audit evidence is available?
- Who can challenge a published result?

---

### TR4: Vote Secrecy (Rank 4)

**Problem-Space Requirement:** No one can determine how a voter voted.

**Solution-Space Support (Current Architecture):**
- Vote table has no voter identification column (structural separation)
- Code table is architecturally separate from vote table (implementation isolation)

**Confidence:** High. Architecture enforces separation at database design level.

**Missing Evidence:**
- Can code table be linked to vote table by authorized officers (access control)?
- Is there any scenario where voter identity + vote becomes visible (threat scenarios)?
- What operational controls prevent this linkage (governance)?

---

### TR5: Proof of Participation (Rank 5)

**Statement:** A voter can prove they participated (without revealing how they voted).

**Current Evidence:**
- Code table bridges voter identity and voting
- Votes are recorded with codes

**Confidence:** Low. Mechanism for proof is not yet explored.

**Missing Evidence:**
- How does a voter prove they voted without revealing identity?
- What format is proof provided in?
- Is this even a requirement or just a candidate?

---

## Section 5: Open Trust Questions

### Critical Questions (Affect Legitimacy, Authority, Dispute Resolution)

1. **Challenge Authority:** When a losing candidate claims the result is wrong, who is authorized to investigate? (Affects: Authority, Legitimacy)

2. **Evidence Standards:** What evidence is considered sufficient to overturn a published result? (Affects: Legitimacy, Evidence)

3. **Dispute Resolution:** Who makes the final decision if result is challenged? (Affects: Authority, Legitimacy)

4. **Verification Access:** Can any voter independently verify their vote was recorded? Or only the election committee? (Affects: Trust, Evidence)

5. **Suspension Challenge:** If an election is suspended, who can challenge the suspension decision? (Affects: Authority, Governance)

6. **Constitutional Interpretation:** If election rules are ambiguous, who interprets them during a dispute? (Affects: Authority, Legitimacy)

7. **Officer Accountability:** If an officer acts outside authority, what is the remedy? (Affects: Authority, Legitimacy)

8. **Coercion Detection:** How would the system detect if voters were coerced? (Affects: Trust, Evidence)

---

### Secondary Questions (Operational)

- How is the code table protected from unauthorized access?
- Can results be accessed during voting for transparency purposes?
- What audit trail exists for all result table updates?
- How are result recalculations initiated and verified?

---

## Section 6: Evidence Traceability

### Trust Finding 1: Vote Table as Source of Truth

| Aspect | Details |
|--------|---------|
| **Source** | Session 1 Architect Interview |
| **Evidence** | Direct architect statement: "Result table is updated during vote recording; vote table is replayable" |
| **Interpretation** | Votes are canonical; results are derived projections |
| **Confidence** | High (architect directly confirmed) |
| **Trust Implication** | Vote integrity depends on vote table integrity |

---

### Trust Finding 2: Chief-Only Authority on Critical Gates

| Aspect | Details |
|--------|---------|
| **Source** | State Machine Analysis Section 2 |
| **Evidence** | ElectionConstitution.php RULES explicitly restrict open_voting and publish_results to chief role only |
| **Interpretation** | Critical trust boundaries are concentrated in chief authority |
| **Confidence** | High (explicit in source code) |
| **Trust Implication** | Chief authority concentration creates single point of trust or single point of failure |

---

### Trust Finding 3: Real-Time Vote Recording

| Aspect | Details |
|--------|---------|
| **Source** | State Machine Analysis Section 6c |
| **Evidence** | Architect confirmed: "Result table updated during vote recording. No separate tallying workflow found." |
| **Interpretation** | Votes persist immediately; no collection phase |
| **Confidence** | High (architect directly confirmed; state machine analysis validated) |
| **Trust Implication** | Vote loss is mitigated by immediate persistence |

---

### Trust Finding 4: Anonymity Enforcement

| Aspect | Details |
|--------|---------|
| **Source** | Session 1 Architect Interview |
| **Evidence** | Architect described data flow: "Voter table ↔ Code table ↔ Vote table (anonymous votes, JSON)" |
| **Interpretation** | Voter identity is structurally separated from vote record |
| **Confidence** | High (architect directly described architecture) |
| **Trust Implication** | Vote secrecy is enforced at database design level |

---

### Trust Finding 5: Governance Constraints on Officer Actions

| Aspect | Details |
|--------|---------|
| **Source** | State Machine Analysis Section 2 |
| **Evidence** | ConstitutionalTransitionGuard enforces 4-check validation on all officer actions |
| **Interpretation** | Officer actions are constrained and auditable |
| **Confidence** | High (explicit in implementation) |
| **Trust Implication** | Officer manipulation threats are partially mitigated by architectural constraints |

---

## Section 7: Known Limitations and Gaps

### Architecture Gaps

- **Challenge Process:** System models state transitions but does not document formal dispute/challenge workflow.
- **Arbitration Authority:** System does not model who decides challenges or how disputes are resolved.
- **Verification Interface:** System records votes and results, but how voters/committees access verification data is not documented.

### Evidence Gaps

- **Coercion Prevention:** Threat model includes coercion, but no mechanism for detection is documented.
- **Proof of Participation:** Trust ranking includes proof, but mechanism not explored.
- **Officer Override Scenarios:** State machine constraints officer actions, but recovery from officer action outside authority is not documented.

### Governance Gaps

- **Constitutional Interpretation:** Who resolves disputes about election rules is not documented.
- **Suspension Authority Chain:** Who can challenge suspension, and how is that resolved, is not documented.

---

## Section 8: Critical Missing Discovery

The workbook documents:
- How elections RUN (state machine, officer actions, vote recording)
- What architectural controls exist (constraints, auditability, anonymity)

The workbook does NOT yet document:
- How elections are CONTESTED (actual disputes)
- What evidence RESTORES LEGITIMACY (dispute evidence)
- Who RESOLVES DISPUTES (actual authority)

This is the critical gap for Step 1C. Before reassessing candidates, Round 16 must discover:

```
Actual Disputes
    ↓
Evidence Expectations
    ↓
Resolution Authority
    ↓
Legitimacy Restoration
```

---

## Closing Statement

```
Trust Findings Consolidated:
✓ 5 trust priorities recorded
✓ 7 high-priority threats identified
✓ 10 trust-relevant architectural facts documented
✓ 5 candidate trust requirements identified (inferred)
✓ 8 critical open questions listed
✓ Evidence traceability maintained

Domain Concepts Observed:
✓ Authority (Chief-only gates, governance controls)
✓ Trust (priorities ranking, threat model)
✓ Evidence (architecture supports verification)
✓ Legitimacy (result correctness, but resolution unknown)
✓ Governance (suspension, officer constraints)

Status:
Existing discoveries consolidated.
Architecture mapped to candidate requirements.
Missing information identified.

NEXT: Discover actual disputes and legitimacy restoration paths.
```

This workbook consolidates trust-related findings gathered through state machine analysis and architect interviews. It maps architectural facts to candidate trust requirements. It identifies critical gaps in dispute resolution and legitimacy restoration that must be addressed before Step 1C Candidate Reassessment.

The next discovery phase should focus on: What disputes actually occur in diaspora elections? What evidence restores legitimacy? Who has authority to decide?
