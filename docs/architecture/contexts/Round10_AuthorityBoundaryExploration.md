# Round 10 — Authority Boundary Exploration

**Strategic DDD — Boundary Analysis**

**Date:** 2026-06-04  
**Status:** Boundary Exploration Phase  
**Working Model:** H-C (Cross-Cutting Authority)  
**Purpose:** Determine whether H-C produces coherent strategic boundaries

---

## ARB Decision Context

**Selected Model:** H-C (Cross-Cutting Authority)

**Decision Type:** Working architectural hypothesis (not final, not implementation authorization, reversible)

**Revision Triggers:** Will reconsider H-C if boundary exploration reveals ownership contradictions, candidate invariants cannot be maintained, or new evidence materially weakens assumptions.

---

## Section 1: Candidate Authority Boundary

**Hypothesis:** Authority is a cross-cutting concern that spans all contexts.

### Boundary Definition

**Candidate Responsibility: Authority Could Own:**
- Authority origin (where did this authority come from?)
- Authority delegation (who can transfer authority?)
- Authority exercise decision (who decides to execute?)
- Authority challenge mechanism (can authority be questioned?)
- Authority revocation (who can cancel authority?)

**Candidate Responsibility: Authority Would NOT Own:**
- Domain decisions (Membership could own membership decisions, Election could own election decisions)
- Domain evidence (each domain could own its evidence)
- Domain verification (each domain could own verification of its facts)
- Legitimacy determination (each domain could determine legitimacy of its decisions)

### Boundary Character

**Type:** Cross-cutting responsibility layer

**Relationship to Other Concerns:**
- Overlays domain contexts (Membership, Election, Governance, Appeals)
- Orthogonal to domain structure
- Present in all contexts simultaneously

**Boundary Interactions:**
- Receives requests from all contexts (any context can exercise authority)
- Provides authority validation to all contexts
- Constrains but does not override domain decisions

---

## Section 2: Authority vs Governance

**Question:** How does Authority boundary interact with Governance context?

### Candidate Responsibility Boundary

**Governance Could Own:**
- Rule definition (what are the rules?)
- Authority framework definition (what authority is valid?)
- Constitution/foundational policy (what is the source of all authority?)

**Authority Could Own (in H-C model):**
- Authority origin tracing (where did this authority come from?)
- Authority delegation (who can transfer authority?)
- Authority lifecycle management (claim, exercise, challenge, revocation)

### Tension Point 1: Rule Authority vs Authority Authority

**Observation:** Governance defines rules that Authority must respect.

**Question:** Does this make Authority subordinate to Governance, or are they orthogonal?

**H-C Interpretation A:** Authority is orthogonal; Governance defines scope, Authority manages execution within that scope.

**H-C Interpretation B:** Governance is foundational authority source; Authority is the execution layer of Governance's authority decisions.

**Supporting Evidence:** Round9A shows Governance defines rules for all contexts; Authority traces to Governance rules.

**Contradicting Evidence:** Authority also traces to "Fairness principle" (independent of Governance), suggesting partial independence.

**Confidence:** MEDIUM (orthogonality vs subordination remains ambiguous)

---

### Tension Point 2: Governance Authority vs Cross-Cutting Authority

**Observation:** Governance authority affects all other contexts (constrains them).

**Question:** Can Governance be truly peer to other contexts if Authority is cross-cutting?

**H-C Interpretation:** Governance owns "authority origin" responsibility; this is a cross-cutting role (Governance is special context within H-C model).

**Alternative:** Governance is foundational (non-peer) in H-C model.

**Supporting Evidence:** All authority traces to Governance rules.

**Contradicting Evidence:** Appeals authority traces to "Fairness principle," not just Governance rules.

**Confidence:** MEDIUM (Governance's role in H-C remains ambiguous)

---

## Section 3: Authority vs Evidence

**Question:** How does Authority boundary interact with Evidence concern?

### Candidate Responsibility Boundary

**Evidence Could Own:**
- Evidence collection (gathering facts)
- Evidence storage (persisting facts)
- Evidence integrity (ensuring facts are not corrupted)
- Evidence access (who can read evidence)

**Authority Could Own (in H-C):**
- Authority origin (does this evidence support valid authority origin?)
- Authority delegation (does evidence verify delegation was authorized?)
- Authority verification (does evidence support that authority was exercised legitimately?)

### Boundary Coherence

**Observation:** Authority depends on Evidence (every authority claim must have documented origin).

**Implication:** Authority is downstream of Evidence (Evidence must exist before Authority can reference it).

**Boundary Clarity:** CLEAR (Evidence provides input; Authority consumes and validates).

**Overlap Risk:** LOW (distinct responsibilities, clear dependency direction).

**Confidence:** HIGH

---

## Section 4: Authority vs Verification

**Question:** How does Authority boundary interact with Verification concern?

### Candidate Responsibility Boundary

**Verification Could Own:**
- Legitimacy checking (is this decision made legitimately?)
- Verification process (how to verify?)
- Verification evidence (what evidence supports legitimacy?)

**Authority Could Own (in H-C):**
- Authority origin (what is the source of authority to decide?)
- Authority exercise (is the authority holder actually executing?)
- Authority challenge (can authority be questioned?)

### Boundary Coherence

**Observation:** Authority and Verification are separate concerns (Round9B analysis).

**Implication:** Both can be cross-cutting independently.

**Boundary Clarity:** MEDIUM (both involve legitimacy, but from different angles)

**Overlap Risk:** MEDIUM (Authority provides "who has right to decide"; Verification provides "was decision made correctly")

**Potential Confusion:** 
- Authority answers "Is this person authorized to make this decision?"
- Verification answers "Did they follow proper process?"
- Overlap: Both involve checking "legitimacy"

**Confidence:** MEDIUM (requires careful separation in Round 11 when Verification is explored)

---

## Section 5: Authority vs Election

**Question:** How does Authority boundary interact with Election context?

### Candidate Responsibility Boundary

**Election Could Own:**
- Election creation (setting up election)
- Voting process (collecting votes)
- Result certification (confirming count)
- Result publication (announcing results)

**Authority Could Own (in H-C):**
- Election authority origin (where does authority to create election come from?)
- Election authority exercise (who has authority to certify results?)
- Election authority challenge (can certification be questioned?)

### Boundary Coherence

**Observation:** Election owns domain decisions; Authority owns authority lifecycle for those decisions.

**Supporting Evidence:** Round9A shows Election makes decisions within Governance constraints; Round9B shows Authority lifecycle applies to election decisions.

**Boundary Plausibility:** Strong Candidate (Election decides "what"; Authority could decide "who can decide").

**Overlap Risk:** LOW (distinct responsibility sets under this model).

**Confidence:** MEDIUM (plausible under explored scenarios, requires further testing)

---

## Section 6: Boundary Tensions

### Tension 1: Governance Centrality

**Description:** Governance defines rules that all other contexts follow. In H-C model, is Governance truly a peer context, or foundational?

**H-C Challenge:** H-C assumes Authority is orthogonal (peer to all contexts). But Governance appears foundational, not orthogonal.

**Implication:** Either Governance is special (not truly peer), OR Authority is not truly orthogonal.

**Unresolved:** Whether H-C model can accommodate Governance's special role.

**Stability Assessment:** UNSTABLE (tension remains).

---

### Tension 2: Authority vs Verification Overlap

**Description:** Both Authority and Verification involve "legitimacy" concept. Authority: "is this person authorized?" Verification: "was process followed correctly?"

**H-C Challenge:** If both are cross-cutting and both involve legitimacy, what prevents them from merging into one concern?

**Implication:** Boundary between Authority and Verification may be unclear.

**Unresolved:** Whether Authority and Verification can remain separate in H-C model.

**Stability Assessment:** UNSTABLE (overlap risk unresolved).

---

### Tension 3: Appeals as Context or Responsibility

**Description:** Appeals crosses all boundaries (can reverse any context's decisions). In H-C, where does Appeals belong?

**H-C Challenge:** If Authority is cross-cutting, is Appeals part of Authority? Or separate? Or in Appeals context?

**Implication:** Appeals boundary remains ambiguous in H-C model.

**Unresolved:** Whether Appeals is Authority responsibility, separate responsibility, or context-owned.

**Stability Assessment:** UNSTABLE (Appeals placement unresolved).

---

## Section 7: Boundary Confidence Assessment

### H-C Authority Boundary: Coherence Assessment

**Strongly Coherent Elements:**
- Authority vs Evidence (clear dependency)
- Authority vs Election (clear separation)
- Authority vs Domain contexts (H-C assumption holds)

**Coherent with Tensions:**
- Authority vs Governance (Governance appears special, not peer)
- Authority vs Verification (overlap risk on legitimacy)
- Authority vs Appeals (placement ambiguous)

**Overall Assessment:** COHERENT WITH TENSIONS

**Meaning:** H-C produces mostly coherent boundaries, but three significant tensions remain unresolved.

**Confidence:** MEDIUM (boundaries hold, but with caveats)

---

## Decision Gate

**Question:** Does H-C produce a coherent strategic boundary?

**Answer:** COHERENT WITH TENSIONS

**Implication:** H-C boundaries work, but:
1. Governance's special role requires explanation in H-C (not truly orthogonal peer)
2. Authority and Verification boundary must be clarified (Round 11)
3. Appeals placement must be resolved (may belong to Authority, separate responsibility, or Appeals context)

**Remaining Viable:** YES — H-C remains viable working model despite tensions.

**Next Investigation:** Round 11 (Context Relationships and Verification placement) may provide additional evidence regarding these tensions.

---

## Summary for Exploration Results

### Boundaries Survived Testing

H-C produced plausible candidate boundaries under explored scenarios, despite three unresolved tensions.

**Not Incoherent:** Candidate boundaries remained stable under explored comparisons.

**Not Final:** Three tensions remain unresolved and require further investigation.

### Candidate Boundaries That Held

- Authority as cross-cutting responsibility remained plausible
- Candidate separation from domain contexts appeared workable
- Candidate separation from Evidence remained clear
- Candidate separation from Election remained plausible

### Tensions Requiring Further Exploration

- Governance's role (appears foundational, not peer—tension with H-C orthogonality assumption)
- Verification's relationship to Authority (overlap risk on legitimacy—requires clarification)
- Appeals' architectural location (placement ambiguous—context, responsibility, or other?)

### ARB Review Required

The explored boundaries hold enough plausibility to continue testing H-C as working model.

**H-C Working Model Status:** SURVIVED BOUNDARY EXPLORATION WITH DOCUMENTED TENSIONS

---

**STATUS: Round 10 Boundary Exploration Complete**

**RESULT: H-C produced plausible candidate boundaries under explored scenarios**

**AWAITING:** ARB Review

**STABILITY:** H-C remains viable working model; tensions are inputs for future exploration, not blockers

