# [VOID — OUT OF SEQUENCE] Round 38C-14A — Constitutional Trust Anchor Evidence Synthesis

> ⚠️ **STATUS: VOID — SUPERSEDED BEFORE ISSUANCE (2026-07-05).**
> This document was drafted from a stale program state that predated the actual pre-ruling sequence. The authoritative record already contains: 38C-14A External Validation Summary, 38C-14B Architect Preliminary Hypothesis (SEALED), 38C-14C Leadership Decision, and **38C-15 ARB Ruling (ISSUED, judicial format): Option B — Functional Independence + permanent safeguards S-1..S-5**. Per the Program State Reconciliation Rule (38B authorization), ARB documents are authoritative over memory artifacts. Per 38C-15 §6, Options A/B/C and the ruling itself may not be revisited. The Model B★ recommendation below is therefore VOID as a recommendation. It is retained solely as an analytical record; its keystone concept substantially overlaps with adopted safeguard S-4 (distinct review mechanism for oversight-body independence challenges). Do not cite this document as program guidance.

**Program:** NRNA DDD Trustworthiness Research Program
**Document:** Evidence Synthesis with Candidate Models, Decision Matrix, and Recommendation
**Authority:** Senior Architect direction (2026-07-05) — synthesis phase inserted between Pre-Ruling Brief (38C-14) and ARB Ruling (38C-15)
**Evidence Base:** 38C-07 through 38C-14, plus the full 38A/38B record where cited
**Status of this document:** This is the FIRST document in the 38C chain authorized to carry a RECOMMENDATION. The recommendation is not a ruling. The ARB accepts or rejects it in 38C-15.
**Governing Constraints:**
- OQ-38B05-05 remains NOT RESOLVED (this document recommends; only the ARB rules)
- OQ-38A05-02 remains PROTECTED
- No new evidence; no new concepts; synthesis of existing record only

---

## Part 1 — The Question, Restated

The question is NOT:

- "Should trust roots be separated?" *(original 38B-05 formulation — a mechanism question)*
- "Is Meta-CVI acceptable?" *(38C-14 formulation — a failure-mode verdict)*
- "Distributed risk or centralized risk?" *(risk-model formulation — closer, but still about consequences)*

The question IS:

> **Where is the final constitutional trust anchor allowed to reside?**

Every constitutional system terminates at a trust anchor — a point at which authority is accepted as legitimate without further justification. Germany anchors in the constitutional order enforced by its Constitutional Court. The United States anchors in the Constitution interpreted by its Supreme Court and sustained by political legitimacy. The United Kingdom anchors in parliamentary sovereignty. The EU anchors in layered, treaty-based sovereignty.

NRNA must choose what kind of trust anchor is constitutionally legitimate for a transnational volunteer membership organization with no state backing, no institutional history, and no external legal enforcement. The candidate models in Part 3 are candidate answers to this question. Everything else — trust root separation, CIC design, amendment tiers — follows from the answer.

---

## Part 2 — Evidence Source Summary

Eight evidence sources feed this synthesis. Each is summarized with its direction of pull.

### 2.1 Governance Evidence (38B-01 through 38B-08)

All five governance gaps (3/4/5/6/7) were specified without creating new authority aggregates beyond CIC. The specifications consistently converged on MA as source-of-source: MA now holds 15 constitutional functions including appointment of 6 of 7 authority bodies and amendment ratification. Governance-layer separation between the three trust roots is real (distinct actors, distinct mandates, multi-party corroboration). Source-layer convergence is total.

**Direction of pull:** Neutral on the anchor question, but establishes the factual baseline — the current design IS a single-anchor design, and the anchor is MA.

### 2.2 Threat Evidence (38A-01 through 38A-06)

Seven FAIL-class findings. The dominant patterns: self-sealing validation chains (Cluster B), coalition capture (CA+CAB, GA+ASA+CAB), and the two ratchets (EC ratchet AW-03-11, AC-31 ratchet AW-05-07 — the latter operating automatically). F-4/TM-39 (Independence Illusion) is the permanent program-level risk: nominal independence that is not structurally real. TM-42 (Operational Deadlock) is the only unconditional non-capture FAIL — a warning that adding governance structure without adding operational capacity creates availability failure.

**Direction of pull:** Toward stronger-than-nominal independence (F-4 is the enemy) but AGAINST designs that multiply coordination points (TM-42 punishes structural complexity in a volunteer organization).

### 2.3 Constitutional Principles (38C-06 Classification)

Seven confirmed Principles: Non-Substitution, Challenge Terminality, CO-5 Derivation, Succession Pre-Designation, Anti-Capture (ADR7-INV-02), Interpretation/Adjudication Separation, Appointment Independence. Anti-Capture is the most load-bearing: no authority may self-grant, self-expand, or self-restrict challenge standing. Meta-CVI is, in essence, a slow-motion Anti-Capture violation that no existing provision detects.

**Direction of pull:** Toward closing the Meta-CVI loophole specifically — the confirmed Principles already prohibit its fast form; they are silent on its slow form.

### 2.4 Diaspora Constraints (organizational reality)

NRNA is a volunteer organization: rotating leadership, limited institutional memory, geographically dispersed members, no professional judiciary, no legal tradition, no external courts, media, or civil-society checks. Every governance structure must be operable by volunteers across time zones. Every new sovereign body requires its own recruitment, legitimacy, succession, and funding.

**Direction of pull:** Strongly toward structural parsimony. Three independent sovereigns (Strategy A) triples the recruitment, legitimacy, and succession burden — and each new sovereign is a new TM-42 deadlock surface and a new AA-01-class bootstrap question.

### 2.5 Software Constraints (ADR-1 through ADR-7)

The seven ADRs assume 7 authority aggregates + CIC = 8, all grounded in a single ElectionConstitution. Strategy A would require partitioning ElectionConstitution's grounding function across three sovereign sources — a foundational rework of ADR-2 through ADR-7, including the certification chain (ADR-6), the challenge architecture (ADR-5), and the dependency graph (ADR-7). Strategy B requires extension, not rework: new Tier 3 provisions, a CIC charter amendment, and appointment-procedure specification.

**Direction of pull:** Toward Strategy B family. Strategy A invalidates substantial approved architecture; Strategy B extends it.

### 2.6 Operational Constraints (38A-04 temporal findings)

TM-42 Complete Deadlock is an unconditional FAIL with a non-adversarial trigger. TM-43 (Successor Exhaustion) and TM-44 (Temporal Concentration) show that the architecture already has zero disruption tolerance in places. Adding sovereigns adds coordination dependencies; every inter-sovereign constitutional act (which Strategy A requires for cross-root verification) is a new liveness dependency.

**Direction of pull:** Against Strategy A's coordination burden; toward designs whose failure mode is degraded oversight rather than organizational paralysis.

### 2.7 Research Literature (38C-07 comparative register)

Eight mechanisms; unanimous direction: every studied constitutional system achieves independent constitutional evaluation through **functional independence** (operationally protected bodies within a shared constitutional order), typically combined with **structural protection of content** (eternity clauses, basic structure doctrine). No studied system requires structurally independent sources.

**Limitation (transferability):** All eight mechanisms operate inside mature systems with institutional history, professional judiciaries, and external checks. The evidence proves functional independence CAN suffice; it does not prove it suffices for a young organization with none of the stabilizing extra-constitutional forces.

**Direction of pull:** Toward functional independence as the pattern — with an explicit caveat that the pattern's preconditions are absent in NRNA.

### 2.8 Independent Reasoning (38C-10-SA through 38C-13B)

The analytical chain produced the decisive facts:

1. Multi-root CERD is present now (38C-11)
2. Severity ranking: multi-root CERD > Full Meta-CVI > per-sovereign CERD (38C-13B) — Strategy A's residual risk is the LEAST severe of the three
3. Meta-CVI is ineliminable under pure Functional Independence — it is inherent wherever the independence evaluator interprets its own independence mandate (38C-13B Part F.1)
4. Meta-CVI's danger concentrates at exactly ONE point: CIC's interpretation of its own independence mandate (38C-13A FI-B-03; 38C-13B Stage 2-3 progression)
5. B+C has common mode failure through CIC — Strategy C adds detection but not CIC-independent response (38C-13B Part G)
6. The five minimum conditions for FI sufficiency are identified and none is currently satisfied (38C-13A Part F)

**Direction of pull:** The single most actionable finding is #4: the Meta-CVI circuit closes at ONE identifiable point. A design that structurally externalizes that one point breaks the circuit without requiring three sovereigns.

---

## Part 3 — Candidate Constitutional Models

Four candidate models answer the trust anchor question. Each is stated as a trust anchor position first, then profiled.

### Model A — Distributed Anchor (Structural Separation / Level 2b)

**Trust anchor position:** The final trust anchor is distributed across three constitutionally independent sovereign sources, one per trust root. No single body is the terminal anchor.

| Dimension | Assessment |
|-----------|------------|
| **Advantages** | Eliminates multi-root CERD completely. Eliminates Meta-CVI (evaluator grounded externally). Cross-root verification structural. Unconditional — does not depend on CIC integrity over time. Strongest possible answer to F-4. |
| **Risks** | Three AA-01-class bootstrap questions (who legitimizes each sovereign?). Triples recruitment/succession/legitimacy burden on a volunteer organization. New inter-sovereign deadlock surfaces (TM-42 amplified). Per-sovereign CERD remains (bounded, cross-verifiable — least severe residual). |
| **Constitutional implications** | Foundational: EC must be re-grounded; the Founding Assembly ceases to be sole source; requires a constitutional convention-scale event. |
| **Trust implications** | Members must trust three separate bodies plus their coordination — trust demand is higher, not lower, at the membership level. |
| **Implementation implications** | ADR-2 through ADR-7 require rework. Certification chain, challenge architecture, dependency graph all re-derived. Longest path to working elections. |

### Model B — Single Anchor with Procedural Armor (Functional Independence / Level 2a, five conditions)

**Trust anchor position:** The final trust anchor is MA — the sovereign membership assembly — with CIC as a functionally independent verifier protected by the five minimum conditions (Tier 3 entrenchment, non-subordination, PAN resistance, jurisdictional explicitness, Meta-CVI partial management).

| Dimension | Assessment |
|-----------|------------|
| **Advantages** | Matches the universal comparative pattern. Single AA-01 question (unchanged). Minimal new organizational burden. Extends rather than reworks approved architecture. Preserves Family-B coherence. |
| **Risks** | Meta-CVI ineliminable — the independence verifier still interprets its own independence mandate. PAN over multiple appointment cycles can reach Stage 3-4 with no constitutional tripwire. Transferability of the comparative pattern to a zero-history organization is unproven. FI-B-03 (meta-review) is listed as a condition but not structurally specified — the condition most likely to be satisfied only nominally (recreating F-4). |
| **Constitutional implications** | New Tier 3 provisions; CIC charter amendment; appointment procedure specification. Amendment-scale, not convention-scale. |
| **Trust implications** | Members trust one sovereign plus one protected verifier. Trust demand is low, but trust is conditional on CIC's durational integrity. |
| **Implementation implications** | Shortest path. All ADRs stand; extensions only. |

### Model B+C — Single Anchor with Armor and Alarm (Model B + Constitutional Verification Checkpoint)

**Trust anchor position:** Same as Model B, plus a standing checkpoint that monitors CERD-proximity indicators and escalates to CIC.

| Dimension | Assessment |
|-----------|------------|
| **Advantages** | All of Model B, plus detection during Meta-CVI Stages 1-2 while CIC is still uncompromised. Creates a constitutional record that later actors can invoke. |
| **Risks** | All of Model B's risks, unreduced at Stage 3-4: the checkpoint escalates TO CIC, so once CIC's interpretation has narrowed, detection produces no constitutionally effective response (confirmed common mode failure, 38C-13B Part G). The alarm rings in the room of the person it warns about. |
| **Constitutional implications** | Model B's plus checkpoint charter. |
| **Trust implications** | Marginally better than B early; identical to B late. |
| **Implementation implications** | Model B's plus checkpoint tooling (low). |

### Model B★ — Single Anchor with One Structural Keystone (Functional Independence + Structurally Externalized Meta-Review)

**Trust anchor position:** The final trust anchor is MA — but the verification of the anchor's verifier is structurally externalized at exactly one point. CIC holds interpretive finality over ALL constitutional provisions EXCEPT ONE CLASS: provisions defining CIC's own independence mandate. That single class is assigned to a meta-review panel whose members are NOT appointed by MA and NOT removable by MA — drawn instead through a constitutionally pre-specified mechanism outside the MA appointment chain (e.g., random selection from a qualified member pool, external professional appointment, or cross-organizational reciprocity — the specific mechanism is an EC design question, not decided here).

This is 38C-13A's condition FI-B-03, promoted from "partial management" to a structural element, combined with the other four FI conditions and the Model C checkpoint (whose escalation target for independence-mandate questions becomes the meta-review panel, not CIC — breaking the B+C common mode).

| Dimension | Assessment |
|-----------|------------|
| **Advantages** | Breaks the Meta-CVI circuit at its single closure point: CIC can no longer narrow its own independence mandate, because that interpretation is not CIC's to make. PAN against CIC still shifts CIC's composition, but the Stage 2→3 transition (interpretive constriction of the independence mandate itself) is structurally blocked. The checkpoint's escalation path for independence questions bypasses CIC — common mode failure resolved for the class that matters. One AA-01-class question is added (who legitimizes the meta-review panel?) but it is far smaller than a sovereign's: the panel holds interpretive authority over ONE provision class, exercised rarely, with no operational role — its capture value and its deadlock surface are both minimal. Preserves Family-B, preserves all ADRs, preserves the comparative pattern (this IS the eternity-clause pattern: structural protection of one critical content class + functional enforcement everywhere else). |
| **Risks** | The meta-review panel is a new body — small, but real recruitment/succession burden. Its selection mechanism must itself resist MA influence (the design question is displaced, not dissolved — but displaced to a body with minimal capture value). Boundary disputes ("is this question about CIC's independence mandate or ordinary interpretation?") need a routing rule; the default must be pre-specified (proposal: any party to a dispute may invoke meta-review routing; frivolous invocation limited by standing rules). Residual Meta-CVI at the panel level exists in principle but the panel interprets nothing about itself — its mandate is fixed constitutional text at Tier 3, giving it minimal self-interpretation surface. |
| **Constitutional implications** | Model B's provisions plus: one Tier 3 provision carving the independence-mandate class out of CIC's interpretive finality; meta-review panel charter; routing rule. Amendment-scale. |
| **Trust implications** | Members trust one sovereign, one protected verifier, and one narrow keystone body. The keystone is the constitutional answer to "who watches the watchman" — and it watches only the watching, nothing else. |
| **Implementation implications** | Model B's path plus one small body and one routing rule. All ADRs stand. |

---

## Part 4 — Decision Matrix

Scale: ++ (strong) / + (adequate) / ○ (neutral or conditional) / − (weak) / −− (failing)

| Criterion | A (Distributed) | B (Procedural) | B+C (Armor+Alarm) | B★ (Keystone) |
|-----------|:---:|:---:|:---:|:---:|
| **Independence (genuine external reference)** | ++ | − | − | + |
| **Transparency (drift is constitutionally visible)** | + | −− | ○ (early only) | + |
| **Manipulation resistance (PAN / ratchets / coalitions)** | ++ | − | − | + |
| **Practicality (volunteer diaspora organization)** | −− | ++ | ++ | + |
| **Software complexity (impact on approved ADRs)** | −− | ++ | ++ | + |
| **Constitutional compatibility (Family-B, AA-01, comparative pattern)** | − | + | + | ++ |
| **Operational resilience (TM-42 class liveness)** | −− | ++ | ++ | + |

**Reading the matrix:**

- **Model A** wins on independence and manipulation resistance and loses everywhere practicality matters. Its failing scores are not marginal: three sovereigns in a volunteer organization is an organizational design the threat record (TM-42) says NRNA cannot operate safely.
- **Model B** is A's mirror: wins practicality, fails the reason the question exists. Its independence score is − not −− only because the five conditions provide real friction — but FI-B-03 unspecified means the Meta-CVI circuit remains closed, and F-4 (Independence Illusion) is precisely the risk of adopting B and declaring the problem solved.
- **Model B+C** improves nothing that matters at the decisive stage. It is dominated by B★.
- **Model B★** has no failing scores. It concedes a small amount of A's independence (the meta-review panel is narrower than a sovereign) and a small amount of B's simplicity (one new small body) to remove both fatal weaknesses.

---

## Part 5 — Recommendation

**Recommended model: B★ — Single Anchor with One Structural Keystone.**

**Trust anchor answer:** The final constitutional trust anchor resides in the Membership Assembly — the only body with sovereign democratic legitimacy in a membership organization — BUT the anchor is not permitted to be self-verifying at the one point where self-verification is constitutionally fatal. The interpretation of the independence verifier's own independence mandate is structurally externalized to a narrow meta-review keystone outside the MA appointment chain.

**Grounding, stated as the ARB will need it:**

1. **The evidence eliminates pure positions.** Model A is eliminated by the operational and diaspora evidence (2.4, 2.6): NRNA cannot operate three sovereigns, and TM-42 makes the attempt an unconditional availability FAIL surface. Model B is eliminated by the independence evidence (2.8): Meta-CVI is ineliminable under pure FI, the comparative pattern's preconditions (institutional history, external checks) are absent, and adopting B as-is would instantiate F-4 — the Independence Illusion — as constitutional design.

2. **The Meta-CVI finding is constructive, not just diagnostic.** 38C-13B located the circuit's single closure point: CIC's interpretation of its own independence mandate. A circuit that closes at one point can be broken at one point. B★ is that break.

3. **B★ follows the comparative pattern more faithfully than B does.** The dominant pattern in all eight studied mechanisms is NOT pure functional independence — it is functional enforcement PLUS structural protection of a critical content core (Art. 79(3), basic structure). B★ applies exactly this pattern: everything functional except the one class that must be structural.

4. **The residual risks are the acceptable ones.** B★'s new AA-01-class question (panel legitimacy) attaches to a body with no operational role, rare exercise, and minimal capture value. Its per-body CERD analogue is the smallest in any candidate. The severity ranking (38C-13B) established that bounded, narrow self-reference residuals are the least severe class — B★ deliberately chooses its residual from that class.

5. **The DDD consequence is decisive for the software project.** Under B★, all seven ADRs and the 8-aggregate authority model stand. Strategic DDD resumes with one addition (the meta-review panel as a 9th, narrow constitutional actor) rather than a re-derivation.

**What the ARB must still decide (this recommendation does not resolve):**
- Accept/reject B★ as the answer to OQ-38B05-05
- If accepted: the meta-review panel's selection mechanism (EC design question — three candidate mechanisms listed in Part 3)
- The routing rule's exact form
- CD-10's final classification (Trust Root Separation as Principle at governance layer, with B★ as its Form — proposed disposition, ARB confirms)

---

## Part 6 — External Review Package (Optional, Pre-Ruling)

Per senior architect direction: external experts should validate the SYNTHESIS, not the raw evidence. If external review is exercised before 38C-15, send:

1. This document's Parts 1, 3, and 4 (question, candidate models, matrix) — with internal citations stripped
2. The Constitutional Case Study (docs/external-validation/02) — already prepared, vocabulary-neutral
3. Three focused questions:
   - Does the four-model space miss a recognized constitutional design?
   - Is the "keystone" pattern (structural externalization of exactly the self-reference point, functional independence elsewhere) a recognized pattern in constitutional design? Under what name?
   - For a young organization without institutional history, is the keystone sufficient, or does the absence of extra-constitutional stabilizers demand more?

If external review returns findings that alter the matrix, this synthesis is revised before the ARB convenes. If not exercised or returns confirmation, 38C-15 proceeds on this synthesis as issued.

---

## Part 7 — Governance Verification

| Constraint | Status |
|-----------|--------|
| OQ-38B05-05 ruled | NO — recommendation issued; ruling reserved to ARB (38C-15) |
| OQ-38A05-02 | PROTECTED — post-finality review not touched; B★ meta-review covers independence-mandate interpretation only |
| New evidence collected | NO — synthesis of 38A/38B/38C record only |
| New concepts introduced | NO — B★ is 38C-13A's FI-B-03 condition promoted to structural status + 38C-13 composite logic; the keystone pattern is 38C-07's protected-core pattern applied |
| Recommendation authorized | YES — senior architect direction 2026-07-05 (synthesis phase explicitly includes recommendation) |
| DDD Gate | REMAINS ACTIVE until 38C-15 issued |
| OBS-38B06-05 | PERMANENT — synthesis completeness ≠ synthesis correctness; the matrix encodes judgments the ARB must independently confirm |

---

*Round 38C-14A — Constitutional Trust Anchor Evidence Synthesis — ISSUED*
*Recommendation: Model B★ (Single Anchor with One Structural Keystone)*
*OQ-38B05-05: NOT RESOLVED — awaits ARB Ruling (38C-15)*
*OQ-38A05-02: PROTECTED*
*Next: (optional) External Review of synthesis → 38C-15 ARB Ruling → Architecture Freeze → Strategic DDD resumes*
*Date: 2026-07-05*
