# Round 9A — Authority Candidate Responsibilities

**Strategic Design Exploration (NOT Tactical DDD)**

**Date:** 2026-06-04  
**Status:** Design Exploration Phase  
**Purpose:** Explore whether H-B or H-C could produce plausible responsibility boundaries  
**Methodology:** Evidence-based responsibility analysis; no tactical design

---

## Phase Context

**ARB Decision:** Option B — Proceed to Authority Design Exploration

**Inputs:**
- Round8_Synthesis.md (what was learned)
- Round8_AuthorityFlowAnalysis.md (observed patterns)
- Round8_AuthorityHypothesisEvidence.md (balanced evidence for H-B and H-C)
- Round8_RiskAssessment.md (unresolved questions)

**Constraints:**
- ❌ Do NOT design aggregates, repositories, services
- ❌ Do NOT design implementation details
- ❌ Do NOT choose H-B or H-C (keep both viable)
- ✅ Determine only whether either model produces coherent responsibilities
- ✅ Keep all conclusions provisional
- ✅ Identify contradictions without resolving them

---

## Section 1: Authority Candidate Responsibilities — H-B Model

**Hypothesis:** Authority is a context-family. Each context owns authority over its decisions.

**Question:** Could Authority own coherent responsibilities in H-B model?

### H-B Candidate Responsibility Set

**Candidate: Authority Could Own**
- Governance: Authority to define membership rules, election rules, appeal rules
- Membership: Authority to approve/deny membership, suspend, reinstate
- Election: Authority to create elections, open voting, close voting, certify results
- Appeals: Authority to reverse decisions from other contexts

**Candidate: Authority Would NOT Own**
- Evidence storage (would remain with domain contexts)
- Legitimacy determination (would remain context-specific)
- Recognition mechanism (orthogonal to authority itself)

**Verification Status:** Deferred to future exploration (not part of Authority responsibility model in this phase)

### H-B Responsibility Boundaries

| Context | Authority Responsibility | Boundaries | Overlap Risk |
|---------|------------------------|-----------|--------------|
| Governance | Define all rules; grant authority to others | Governance defines scope; others execute within scope | LOW (clear separation) |
| Membership | Approve/deny membership within Governance rules | Membership authority ends at Governance boundaries | MEDIUM (Governance can override) |
| Election | Create/manage elections within Governance rules | Election authority ends at verification boundary | MEDIUM (verification unclear) |
| Appeals | Reverse other contexts' decisions | Appeals authority crosses all boundaries (problematic) | HIGH (crosses all) |

### H-B Plausibility Assessment

**Supporting Observations:**
- Governance authority is clearly defined
- Membership, Election authorities are domain-specific
- Each context can own an authority variant

**Contradicting Observations:**
- Appeals authority crosses all boundaries (problematic if authority is context-family)
- Governance authority affects all others (questions whether Governance is truly a peer context)
- Authority acceptance criteria differ by context (may undermine uniformity assumption)

**Provisional Assessment:** H-B could produce responsibility boundaries, BUT **Appeals presents a model tension**. In H-B terms, Appeals would need to be context-local authority that somehow crosses all boundaries—a possible but complex explanation.

**Confidence:** MEDIUM (H-B plausible but with unresolved tensions; further testing required)

---

## Section 2: Authority Candidate Responsibilities — H-C Model

**Hypothesis:** Authority is cross-cutting. Present in all contexts; orthogonal to domain boundaries.

**Question:** Could Authority own coherent responsibilities in H-C model?

### H-C Candidate Responsibility Set

**Candidate: Authority Could Own**
- Authority origin (where did this authority come from?)
- Authority delegation (who can transfer authority?)
- Authority exercise (who executes the authority decision?)
- Authority challenge (can authority be questioned?)
- Authority revocation (who can cancel authority?)

**Candidate: Authority Would NOT Own**
- Domain decisions (would remain with Membership, Election, Appeals)
- Evidence storage (would remain with domain contexts)

**Verification Status:** Deferred to future exploration (not part of Authority responsibility model in this phase)

### H-C Responsibility Boundaries

| Responsibility | Owner | Applies To | Boundary |
|---|---|---|---|
| Authority Origin | Context or Governance | All authority claims | Traceable source required |
| Authority Exercise | Domain Context | Domain-specific decisions | Context owns execution |
| Authority Verification | Verification concern | All authority exercises | Verification cross-cutting |
| Authority Challenge | Appeals Context | All contexts | Appeals can challenge any |
| Authority Revocation | Higher authority or Governance | All authority claims | Governance typically holds revocation |

### H-C Plausibility Assessment

**Supporting Observations:**
- Appeals authority could be explained as "challenge responsibility" (orthogonal to domain)
- Governance centrality could be explained as origin/revocation responsibility owner
- Uniform lifecycle pattern supports cross-cutting nature
- All contexts could participate in orthogonal authority system

**Contradicting Observations:**
- Authority and domain responsibility would need to be kept separate (complex model)
- Context-specific authority variations observed in Step 4 (suggests non-uniform behavior)
- Governance appears central even as cross-cutting (questions orthogonality assumption)
- Separation into two parallel systems (domain + authority) increases model complexity

**Provisional Assessment:** H-C could produce responsibility boundaries, BUT **requires managing two parallel responsibility systems** (domain responsibilities + authority responsibilities). This creates potential for inconsistency.

**Confidence:** MEDIUM (H-C plausible but with unresolved complexity; further testing required)

---

## Section 3: Responsibility Ownership Comparison

**For each responsibility, could it be owned by a different context?**

### Governance: Define Rules

**H-B View:** Governance owns this; unique to Governance
**H-C View:** Governance owns this; other contexts don't define system rules
**Alternative Owner:** Could Election own election rules? (No - would be inconsistent)
**Overlap:** None (clear single owner)

### Membership: Approve/Deny

**H-B View:** Membership owns this; unique authority type
**H-C View:** Membership owns execution; Governance owns origin
**Alternative Owner:** Could Governance approve membership? (Possible but would violate separation)
**Overlap:** MEDIUM (who owns final authority: Membership or Governance?)

### Election: Certify Results

**H-B View:** Election owns this; authority type
**H-C View:** Election owns execution; Verification owns legitimacy check
**Alternative Owner:** Could Appeals certify results? (Could, through challenge authority)
**Overlap:** HIGH (certification vs verification distinction unclear)

### Appeals: Reverse Decisions

**H-B View:** Appeals owns this; unique authority type (problematic)
**H-C View:** Appeals owns challenge responsibility; reversal is consequence
**Alternative Owner:** Could Governance override other decisions? (Yes, but different mechanism)
**Overlap:** HIGH (Appeals vs Governance reversal authority)

### Verification: Check Legitimacy

**H-B View:** Each context owns verification of its domain
**H-C View:** Cross-cutting concern; all contexts participate
**Alternative Owner:** Could single verification context own all? (Possible but challenging)
**Overlap:** VERY HIGH (verification appears everywhere)

---

## Section 4: Contradiction Analysis

**Where do H-B and H-C models contradict each other?**

### Contradiction 1: Appeals Authority Nature

**H-B Says:** Appeals is another authority type (context-family)

**H-C Says:** Appeals owns "challenge responsibility" (not authority type)

**Evidence:** Appeals can reverse Membership and Election decisions. This appears to be:
- **H-B interpretation:** Appeals has authority over other contexts' authorities
- **H-C interpretation:** Appeals exercises challenge responsibility that all contexts accept

**Architectural Significance:** This is the core distinction between models.

---

### Contradiction 2: Governance Centrality

**H-B Says:** Governance is context that happens to define rules for others (family peer)

**H-C Says:** Governance is central authority source for cross-cutting system (not peer)

**Evidence:** Governance defines rules that all contexts must follow. This could be:
- **H-B interpretation:** Constitutional role (peer with special responsibility)
- **H-C interpretation:** System origin point (non-peer, foundational)

**Architectural Significance:** Determines whether Governance is special or just has special responsibility.

---

### Contradiction 3: Authority Uniformity

**H-B Says:** Authority varies by context (different rules, different acceptance)

**H-C Says:** Authority follows uniform lifecycle (Claim-Origin-Exercise-Challenge-Revoke)

**Evidence:** Both patterns observed in Step 4. Could be:
- **H-B interpretation:** Uniform lifecycle is coincidence or artifact of documentation
- **H-C interpretation:** Uniform lifecycle proves cross-cutting nature

**Architectural Significance:** Determines whether context-variation or uniformity is real.

---

## Section 5: Verification of Responsibility Clarity

**For each model, are responsibilities clear enough for Round 9A to proceed?**

### H-B Responsibility Clarity

**Clear:**
- Governance owns rule-setting authority
- Membership owns membership decisions
- Election owns election decisions

**Unclear:**
- Does Appeals own "reversal authority" or just execute challenges?
- How do context-specific authority types coexist?
- What makes authority legitimate in each context?

**Clarity Score:** MEDIUM (domain authorities clear; Appeals problematic)

### H-C Responsibility Clarity

**Clear:**
- Authority responsibilities are systematic (Origin, Exercise, Challenge, Revocation)
- All contexts follow same lifecycle

**Unclear:**
- How do domain responsibilities and authority responsibilities interact?
- Does Verification own legitimacy checking or is that authority's job?
- How does "orthogonal to domains" work when authority shapes decisions?

**Clarity Score:** MEDIUM (system clear; integration with domains unclear)

---

## Section 6: Next Exploration Questions

**These questions remain for Round 9B (Invariants) or deeper exploration:**

### Q6.1: Can Authority Be Separated from Decision-Making?

**H-B Implication:** Authority cannot be fully separated; it belongs to decision-making context.

**H-C Implication:** Authority can be separated; it's orthogonal to domain.

**Test:** In H-C model, could a context make a decision without authority? (Unlikely - suggests inseparability)

**Deferred To:** Round 9B Invariants exploration.

---

### Q6.2: Is Appeals a Context or a Responsibility?

**H-B View:** Appeals is a context (another authority type)

**H-C View:** Appeals owns a responsibility (challenge); not necessarily a context

**Evidence:** Appeals can reverse all other contexts' decisions, but doesn't make domain decisions itself.

**Deferred To:** Round 9B design exploration (may clarify through boundary analysis).

---

### Q6.3: Does Governance Owning Rule-Authority Make It Non-Peer?

**H-B View:** Governance is peer context with special responsibility

**H-C View:** Governance is foundational (non-peer) because it defines others' authorities

**Evidence:** All other authority traces to Governance rules.

**Deferred To:** Round 9B (may clarify through responsibility overlap analysis).

---

## Section 7: Sensitivity Analysis — Appeals Dependency

**Question:** How much do our conclusions depend on Appeals behavior?

### If Appeals Is Reinterpreted

**Scenario A (Current):** Appeals reverses other contexts' decisions (crosses boundaries)

**Scenario B (Alternative):** Appeals owns special "fairness determination" responsibility (not reversal)

**Which H-B Conclusions Change?**
- **Current:** Appeals is problematic because it's context-local authority that crosses boundaries
- **Alternative:** Appeals could be context-local if reinterpreted as "fairness context"
- **Significance:** Removes primary evidence against H-B

**Which H-C Conclusions Change?**
- **Current:** Appeals explains cross-cutting authority naturally
- **Alternative:** Appeals becomes just another domain context with special responsibility
- **Significance:** Removes primary evidence for H-C

**Provisional Finding:** Appeals is the discriminator between H-B and H-C. Both models are equally sensitive to how Appeals is interpreted.

**Confidence:** LOW (Appeals interpretation is unresolved; both models hinge on it)

---

## Section 8: Remaining Questions

### What We Observed

**Both H-B and H-C could produce plausible responsibility boundaries from evidence reviewed.**

This suggests either model might be architecturally viable, but neither has been fully tested.

### What Remains Unresolved

**Three major questions persist:**
1. **Appeals authority nature:** Is it context-local or cross-cutting? How is it explained in each model?
2. **Governance centrality:** Is Governance a peer context or a foundational authority source?
3. **Authority uniformity:** Does uniform lifecycle prove cross-cutting nature, or is it documentation artifact?

### What Cannot Be Determined Yet

**Invariant constraints:** Whether H-B or H-C could maintain required invariants (immutable rules).

**Boundary coherence:** Whether proposed boundaries would actually separate concerns effectively.

**These require Round 9B testing.** Responsibility exploration alone is insufficient.

---

**STATUS: Round 9A Exploration Complete**

**FINDINGS: Both H-B and H-C remain plausible from responsibility perspective**

**BLOCKERS:** Appeals interpretation is unresolved; both models depend on it

**ARB DECISION REQUIRED:** Whether to proceed to Round 9B (Invariants), or explore Appeals interpretation further before proceeding

