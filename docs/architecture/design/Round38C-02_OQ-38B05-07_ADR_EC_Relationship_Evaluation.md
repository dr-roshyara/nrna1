# Round 38C-02 — OQ-38B05-07: ADR ↔ EC Relationship Evaluation

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38C-02 — Foundational Governance Prerequisite (Evaluation)
**Status:** SUBMITTED FOR ARB REVIEW
**Purpose:** Evaluate OQ-38B05-07. Do NOT resolve it. Do NOT select an option. ARB performs selection.
**Date:** 2026-06-18

**Input:**
- Round38C-01 Strategic Discovery Charter (APPROVED WITH REVISIONS R1–R5)
- ADR-1 through ADR-7 (all approved)
- 38B-01 through 38B-05 (all approved)
- 38C Authorization Decision (APPROVED WITH REVISIONS R1–R5)

**Governing invariant:** 38C01-INV-01 — all outputs remain hypotheses until ARB acceptance.

**No resolution. No selection. No authorization. No architectural design. Evaluation only.**

**Note:** This document supersedes Round38C-02_OQ-38B05-07_ADR_EC_Relationship_Resolution.md and Round38C-02_OQ-38B05-07_Evaluation.md. Neither prior document should be treated as an approved evaluation.

---

## Part A — Question Definition

**OQ-38B05-07:** What is the constitutional relationship between ADR architectural decisions and Election Constitution (EC) tier provisions?

**Origin:** Elevated in 38B-05 ARB Review as a potentially program-critical question. The concern: ADR decisions (especially ADR-2 independence forms) and EC tier provisions may conflict, or the relationship between them may be undefined. Until specified, CA independence form and non-CIC/CAB D43 independence requirements have ambiguous constitutional protection.

**Why this is a foundational governance prerequisite:** All seven existing ADRs contain architectural decisions, selected options, and binding invariants. Those decisions were made without specifying their constitutional standing relative to EC tiers. This ambiguity affects:

- Which body has authority to change an ADR decision (ARB vs. EC amendment process)
- Whether constitutional challenge procedures (S-1/S-2/S-3 standing) apply to ADR decisions
- Whether CIC interpretation authority extends to ADR language disputes
- Whether future 38C technical architecture ADRs carry constitutional weight or are advisory
- Whether ADR independence forms are constitutionally protected or architecturally revocable

**What this evaluation must produce:** A structured analysis of three candidate options (A, B, C), their impact on threats, AA-01 dependency, and existing ADR invariants — presented without selecting an option. ARB selects.

**Three candidate options:**

| Option | Description |
|--------|-------------|
| A | ADRs are architecturally independent of EC — separate governance systems |
| B | ADRs are EC Tier 2 instruments — architectural decisions carry constitutional weight |
| C | Hybrid Principle/Form split — EC holds principles, ADRs hold implementation forms |

---

## Part B — Option A Analysis: ADRs Architecturally Independent of EC

### B.1 — Authority Source

ADR decisions are authoritative within the architecture governance process. They have no constitutional standing. EC governs the constitutional layer; ADRs govern the architectural layer. These are separate governance systems.

### B.2 — Amendment Requirements

Changing an ADR requires ARB architectural review. No EC amendment required, regardless of what the ADR governs. ADR-2 independence forms could be changed by ARB decision without EC Tier 2 amendment.

### B.3 — Challengeability

Constitutional challenge procedures (S-1/S-2/S-3 standing) do not apply to ADR decisions under Option A. A constitutional observer cannot challenge an ADR decision as a constitutional violation. Challenge routes only through EC provisions that independently protect the relevant interest.

### B.4 — CIC Jurisdiction

CIC interprets EC text only. CIC does not interpret ADRs. ADR disputes go to architectural governance (ARB), not CIC. This is consistent with 38B01-INV-01 (CIC interprets; CAB adjudicates) but limits CIC strictly to EC-text interpretation.

### B.5 — Constitutional Gap Profile

| Constitutional Layer (EC) | Architectural Layer (ADR) | Gap |
|---------------------------|---------------------------|-----|
| Challenge rights (Tier 3) | Challenge mechanism design (ADR-5) | Challenge existence is constitutional; mechanism design is not |
| AC-31 minimum (Tier 3) | Reference standard architecture (ADR-3) | AC-31 existence is constitutional; how it is implemented is not |
| CAB independence principle (Tier 3) | CAB appointment mechanics (ADR-2) | Independence requirement is constitutional; implementation is not |
| CIC existence (Tier 3) | CIC jurisdiction scope (38B-01) | Existence is constitutional; jurisdiction scope is not |
| MA ratification requirement (Tier 3) | MA functional appointment scope (38B-04) | Requirement is constitutional; scope is not |

**Structural observation:** This gap is wide. Independence forms, certification logic, and succession mechanisms are entirely below the constitutional layer under Option A.

### B.6 — Advantages

- Architectural agility: corrections to ADR decisions require ARB consensus, not constitutional amendment
- Clear separation of concerns: EC governs constitutional requirements; ADRs govern technical realization
- Easiest to implement given current program state (no EC amendment process required)
- Allows 38C to correct ADR-level errors discovered during technical realization without triggering Tier 2 processes

### B.7 — Disadvantages

- Independence forms (ADR-2 per-function options) are architecturally revocable without constitutional visibility
- CO-5 certification logic (ADR6-INV-01) is non-constitutional; could be weakened without EC amendment
- Anti-capture invariant (ADR7-INV-02) has no constitutional protection — architectural revision could remove it
- Succession mechanisms (ADR7-INV-01) are non-constitutional; deadlock response architecture could change without constitutional process
- Constitutional challenge procedures cannot reach ADR-layer governance failures

### B.8 — Open Risks

- Independence degradation without constitutional visibility (structural path to TM-39 scenario)
- Certification weakening without constitutional visibility (CO-5 derivation rule is architectural only)
- Future architects may not recognise the constitutional gap and treat ADR decisions as constitutionally binding without EC protection

---

## Part C — Option B Analysis: ADRs as EC Tier 2 Instruments

### C.1 — Authority Source

ADR decisions carry constitutional authority from ElectionConstitution via Tier 2 designation. All seven existing ADRs become retroactively part of the EC constitutional system. Creating, amending, or revoking any ADR requires Tier 2 EC amendment.

### C.2 — Amendment Requirements

ADR revision requires Tier 2 EC amendment: qualified majority + deliberation period. This applies to every existing ADR retroactively. Correcting a technical architectural error in ADR-3 requires the same constitutional process as amending governance rules.

### C.3 — Challengeability

S-1/S-2/S-3 standing applies to ADR decisions as constitutional instruments. A constitutional observer could challenge any ADR architectural decision as a constitutional violation, routing to CAB/CIC for adjudication.

### C.4 — CIC Jurisdiction

CIC interprets all ADRs as Tier 2 EC instruments. CIC jurisdiction expands to all ADR language. This significantly increases CIC's interpretive scope. All ADR disputes become constitutional disputes before they become architectural disputes.

### C.5 — Constitutional Gap Profile

| Constitutional Layer (EC + ADRs) | Remaining Gap |
|-----------------------------------|---------------|
| All ADR decisions at Tier 2 | Potential future ADRs — do they require EC enactment to exist? |
| Independence forms constitutional | Organisational realization of independence (outside program scope) still external |
| Certification logic constitutional | AC-31 realization (technical infrastructure) still outside scope |

**Structural observation:** The constitutional gap narrows significantly. Almost all current architectural decisions gain constitutional protection. The remaining gap is at implementation layer.

### C.6 — Advantages

- ADR invariants gain full constitutional protection — ADR3-INV-01, ADR5-INV-01, ADR6-INV-01, ADR7-INV-01, ADR7-INV-02 all become Tier 2 constitutional
- Independence forms cannot be degraded without qualified majority + deliberation (strong TM-39 mitigation)
- CIC and CAB can adjudicate ADR-level governance failures through constitutional challenge procedures
- Trust root architectural realizations gain Tier 2 constitutional protection

### C.7 — Disadvantages

- Governance ossification: correcting architectural mistakes requires Tier 2 constitutional process
- 38C is still in Strategic DDD Discovery — technical realization will discover errors in the current ADR model; Option B makes early-round corrections constitutionally expensive
- CIC jurisdiction expands significantly — CIC becomes the primary interpreter of both constitutional and architectural governance; CIC concentration increases
- MA ratification scope expands (Tier 2 amendments trace to MA) — AA-01 dependency increases
- EC becomes very large (seven ADRs as Tier 2 content) — constitutional interpretation becomes more complex

### C.8 — Open Risks

- Architectural errors discovered in later rounds (38D+) become constitutionally expensive to fix
- CIC overload: CIC must interpret both constitutional principles and architectural implementation details
- If a future technical architecture ADR contains an error, correcting it requires Tier 2 constitutional process

---

## Part D — Option C Analysis: Hybrid Principle/Form Split

### D.1 — Authority Source

EC holds constitutional principles at assigned tiers (Tier 1/2/3). ADRs hold implementation forms at the architectural level. Each has distinct standing. EC-held principles carry constitutional authority; ADR-held forms carry architectural authority only.

### D.2 — Amendment Requirements

Changing a constitutional principle requires EC amendment at its assigned tier. Changing an implementation form requires ARB architectural review only. The boundary between principle and form is the central interpretive question under Option C.

### D.3 — Challengeability

Constitutional challenge procedures apply to principle violations. Form disputes go to architectural governance. The critical discipline: distinguishing a principle violation from a form dispute. CIC adjudicates boundary disputes.

### D.4 — CIC Jurisdiction

CIC interprets EC principles AND adjudicates principle/form boundary disputes when contested. This is a meaningful expansion beyond Option A (EC text only) but narrower than Option B (all ADRs). The boundary-determination role is new to CIC under Option C.

### D.5 — Principle/Form Classification — Candidate Observations

**Governance discipline note:** The following observations identify elements that *appear* principle-like or form-like based on their constitutional grounding. These are observations for ARB consideration — not definitive classifications. ARB may accept, reject, or recategorise any entry.

| ADR Element | Appears Principle-Like | Appears Form-Like | Assessment |
|-------------|------------------------|-------------------|------------|
| ADR3-INV-01 evidence strata independence | "Authentication and completeness are constitutionally distinct concerns" (grounded in AC-31 minimum + Gap A-3) | Specific three-stratum architecture | Ambiguous: distinction requirement vs. three-stratum realisation |
| ADR5-INV-01 R-8 terminal | "Constitutional challenge is terminal within election cycle" (Tier 3 challenge rights) | R-8 label; remedy taxonomy | Appears separable: terminality principle vs. taxonomy form |
| ADR6-INV-01 CO-5 void | "Election validity requires independent satisfaction of completeness, authenticity, constitutional compliance" | CO-5/CO-2/CO-3/CO-4 labels; derivation rule | Appears separable: validity principle vs. CO structure form |
| ADR7-INV-01 suspension succession | "Critical function succession must be constitutionally pre-designated" | Specific succession chains; trigger rules | Appears separable: pre-designation requirement vs. chain specification |
| ADR7-INV-02 anti-capture invariant | "No authority may self-dealing in challenge standing" (Tier 3 challenge rights + CIC existence) | Specific anti-capture mechanism | Ambiguous: the invariant itself may be a form realising a deeper principle, not a principle itself |
| 38B01-INV-01 CIC interprets; CAB adjudicates | "Interpretation and adjudication are constitutionally distinct functions" | Specific dispute routing procedure | Appears separable: function separation principle vs. routing form |
| 38B04-INV-01 no self-appointment | "Authority appointment must be constitutionally independent" | Specific appointment constraint rules | Appears separable: independence requirement vs. specific constraints |
| 38B05-INV-01 three amendment tiers | "Constitutional protection levels must be preserved against reclassification attacks" | Three-tier structure; specific thresholds | Ambiguous: the three-tier structure may itself be the principle |

### D.6 — Advantages

- Preserves constitutional protection for principles while allowing form-level architectural corrections without Tier 2 process
- CIC jurisdiction expansion is targeted (boundary determinations) rather than total (all ADRs)
- Independence requirement principle can be constitutionally protected while allowing implementation forms to adapt
- Balances constitutional resilience with architectural agility

### D.7 — Disadvantages

- Principle/form boundary is contestable — adversaries may exploit boundary ambiguity to reclassify protections
- CIC acquires a new meta-role (boundary determination) that is itself a potential concentration point
- EC must be extended to explicitly contain principle-level provisions — this requires a new EC amendment process
- Classification exercise (which ADR elements are principles vs. forms) is a significant governance task requiring ARB authorization

### D.8 — Open Risks

- Boundary ambiguity between principle and form may become a strategic governance target
- Principle/form classification may itself require Tier 2 amendment — bootstrapping problem
- CIC's boundary-determination role may be challenged (CIC adjudicates the scope of its own jurisdiction — self-referential risk)
- If EC extension is not completed before 38C moves to technical architecture, a governance gap persists

---

## Part E — Threat-Model Correlation

The following evaluates each option's impact on the 38A threat findings that motivated OQ-38B05-07. Per 38C-01 Required Output 7 Step 4b: analysis of every existing invariant whose constitutional force changes per option. Per 38C-01 Required Output 8 Step 2b: TM-06, TM-19, TM-39, TM-42, TM-44, TM-47 are the minimum required.

### E.1 — TM-06: Concentration Chain Capture

**Threat:** Coordinated capture of multiple constitutional authority nodes through sequential or simultaneous governance attacks, exploiting appointment concentration chains.

| Option | Impact |
|--------|--------|
| A | EC and ADRs are separate governance systems. An adversary must capture both independently. EC capture requires Tier 3 threshold for near-unanimity provisions; ADR capture requires ARB consensus. Two separate attack surfaces provide structural resistance to simultaneous capture. However, separation is not mutual enforcement — EC and ADRs do not check each other. |
| B | ADRs become EC instruments. EC and ADR governance merge into a single constitutional system. A successful Tier 2 EC amendment coalition (qualified majority) could simultaneously affect both constitutional principles and ADR-level independence forms. Attack surface concentration increases. Tier 3 provisions remain near-unanimity protected. |
| C | EC principles and ADR forms are separate governance systems with principled connection. Capture of EC at principle level requires assigned tier threshold; capture of ADR forms requires ARB consensus. Structural separation is maintained but the boundary-determination role (CIC under Option C) becomes a concentration node — capturing CIC's boundary interpretation could reclassify protections without constitutional amendment. |

### E.2 — TM-19: AC-31 Reference Standard Capture

**Threat:** Compromise of the AC-31 reference standard, enabling false authentication claims to appear valid.

| Option | Impact |
|--------|--------|
| A | AC-31 minimum is Tier 3 protected (38B-05); AC-31 reference standard architecture (ADR-3) is non-constitutional. Capture of the reference standard implementation remains architecturally revocable without constitutional visibility. Partial protection only. |
| B | ADR-3 becomes Tier 2 constitutional. Reference standard architecture is constitutionally protected. Changes require Tier 2 amendment. Strong mitigation. |
| C | AC-31 existence principle is constitutional; reference standard architecture is a form. Implementation capture is architecturally revocable; CIC intervention available if capture violates the independence principle. Partial-to-moderate mitigation depending on principle classification. |

### E.3 — TM-39: Independence Illusion (F-4)

**Threat:** Structural independence of authorities exists in design but not in practice — authorities are nominally independent but effectively captured through informal mechanisms.

| Option | Impact |
|--------|--------|
| A | Independence forms (ADR-2 per-function) are architecturally revocable. Independence degradation can occur through architectural revision without constitutional visibility. TM-39 scenario is structurally enabled under Option A. No mitigation beyond EC-protected independence existence (Tier 3 minimum only). |
| B | Independence forms become Tier 2 constitutional. Changing independence form requires Tier 2 amendment + deliberation. Strong structural mitigation. Independence degradation has constitutional visibility. |
| C | Independence requirement principle is constitutional; independence form is architectural. The principle protects against elimination of independence; it does not protect against degradation of form. A nominally independent committee that becomes practically captured does not necessarily violate the principle. Partial mitigation — stronger than Option A, weaker than Option B for form-level attacks. |

### E.4 — TM-42: Operational Deadlock (Complete Deadlock = FAIL)

**Threat:** Simultaneous vacancy or incapacity of sufficient constitutional authorities to reconstitute normal operation.

| Option | Impact |
|--------|--------|
| A | ADR7-INV-01 (suspension succession) is non-constitutional. Succession chains are architectural and revocable. Reconstitution mechanisms could be weakened without constitutional process. TM-42 mitigation is architectural only. |
| B | ADR7-INV-01 becomes Tier 2 constitutional. Succession architecture is constitutionally protected. Changes require Tier 2 amendment. Strengthens TM-42 mitigation. |
| C | Succession principle ("critical function succession must be pre-designated") appears constitutional; specific succession chains appear formal. Principle protects against complete elimination of succession; form-level degradation (weak succession chains) may not trigger constitutional protection. Partial mitigation. |

### E.5 — TM-44: Temporal Concentration / GovernanceState Rollback

**Threat:** Temporal governance phase records manipulated, rolled back, or contested to create constitutional ambiguity about the current election phase.

| Option | Impact |
|--------|--------|
| A | ADR-7 GovernanceState boundary architecture is non-constitutional. Phase record governance mitigations are architectural and revocable. TM-44 mitigation is architectural only. |
| B | ADR-7 becomes Tier 2 constitutional. GovernanceState architecture is constitutionally protected. Rollback mitigations cannot be removed without Tier 2 amendment. Strengthens TM-44 mitigation. |
| C | GovernanceState independence principle ("phase record must be externally corroborated") appears constitutional; specific corroboration architecture appears formal. Partial mitigation — principle protects against elimination; implementation attacks against corroboration mechanisms may not trigger constitutional protection. |

### E.6 — TM-47: Certification Chain Self-Reference Exploitation

**Threat:** Certification chain becomes self-referential, enabling compromised components to certify their own compliance — particularly the AC-31 authenticity ratchet.

| Option | Impact |
|--------|--------|
| A | ADR6-INV-01 (CO-5 void without CO-2/CO-3/CO-4) is non-constitutional. CO-5 derivation rule is architectural. A compromised CertificationAuthority could architecturally weaken the CO-5 derivation rule without constitutional process. |
| B | ADR6-INV-01 becomes Tier 2 constitutional. CO-5 derivation rule is constitutionally protected. Weakening it requires Tier 2 amendment. Strong mitigation. |
| C | "Election validity requires independent satisfaction of completeness, authenticity, constitutional compliance" appears principle-like; specific CO object structure appears form-like. Self-reference attacks against the CO structure may not trigger constitutional protection if framed as form-level changes. Partial mitigation. |

### E.7 — Threat Correlation Summary

| Threat | Option A | Option B | Option C |
|--------|----------|----------|----------|
| TM-06 (Concentration chain) | Structural separation (two independent systems) | Single constitutional system (concentration increases) | Partial separation (CIC as new concentration node) |
| TM-19 (AC-31 capture) | Partial | Strong | Partial-to-Moderate |
| TM-39 (Independence Illusion) | NONE beyond Tier 3 minimum | Strong | Partial (principle-level only) |
| TM-42 (Operational Deadlock) | Architectural only | Constitutional | Partial (principle-level) |
| TM-44 (GovernanceState rollback) | Architectural only | Constitutional | Partial (principle-level) |
| TM-47 (Certification self-reference) | Architectural only | Constitutional | Partial (principle-level) |

---

## Part F — AA-01 Dependency Analysis

AA-01 is the pre-constitutional assumption that the Membership Assembly (MA) is a legitimately constituted sovereign body. As of 38B-04, MA holds 15 constitutional functions. The following evaluates how each option changes AA-01 dependency.

**Current AA-01 Exposure Baseline (38B state):** MA controls CIC appointment (38B-04), CriteriaAuthority appointment, AuditScopeAuthority appointment, GovernanceAuthority appointment, CertificationAuthority appointment, CAB appointment, EnrollmentAuthority appointment, AC-31 designation (38B-02 Tier 2), EC ratification (38B-05), and amendment process ratification. Total: 15 functions. If AA-01 is invalid (MA is not legitimately constituted), all 15 functions are simultaneously compromised.

| Option | AA-01 Dependency Change | MA Dependency Change | Concentration Effect | Governance Survivability |
|--------|------------------------|----------------------|---------------------|--------------------------|
| A | None — baseline maintained | No new MA functions via ADR governance | MA concentration does not increase; ADR layer is ARB-governed | Highest — ADR layer operates independently of MA; constitutional layer compromise does not automatically compromise architectural layer |
| B | Significant increase — MA controls ADR amendment processes via EC Tier 2 traceability | MA acquires control over ADR revision processes (7 existing ADRs + all future 38C ADRs) | MA now governs both constitutional and architectural layers; single AA-01 failure covers both | Lowest — constitutional layer compromise (AA-01 failure) simultaneously compromises architectural governance; no independent ADR governance layer |
| C | Moderate increase — principle amendments trace to MA; form amendments do not | MA acquires control over principle-level EC extensions required by Option C; form layer retains ARB governance | Intermediate — MA controls principle layer, ARB controls form layer; but CIC boundary-determination role (CIC appointed by MA under 38B-04) creates indirect MA influence over form classification | Intermediate — form-level corrections can occur without MA involvement; principle layer remains MA-dependent |

**AA-01 Dependency Structural Note (observation only):** Under Option B, an AA-01 failure creates a single-layer collapse of both constitutional governance and architectural governance simultaneously. This is a structural property independent of the specific probability of AA-01 failure. The program acknowledges AA-01 as a pre-constitutional assumption. The question of which option creates the least AA-01 amplification is relevant to this selection. ARB determines whether this consideration is dispositive.

---

## Part G — ADR Invariant Analysis

Per 38C-01 Required Output 7 Step 4b, each existing ADR invariant is assessed for whether it appears principle-like, form-like, or ambiguous. **These are observations for ARB consideration — not definitive classifications.**

| Invariant | What It Protects | Appears Principle-Like | Appears Form-Like | Assessment |
|-----------|-----------------|------------------------|-------------------|------------|
| ADR3-INV-01 | Evidence strata independence | "Authentication and completeness are constitutionally distinct concerns" (AC-31 minimum + Gap A-3) | Three-Stratum specific architecture | Ambiguous — distinction requirement appears principle-like; three-stratum realisation appears form-like |
| ADR5-INV-01 | R-8 is constitutionally terminal | "Constitutional challenge is terminal within election cycle" (Tier 3 challenge rights) | R-8 label; remedy taxonomy (R-1 through R-8) | Appears separable: terminality principle vs. taxonomy form |
| ADR6-INV-01 | CO-5 void unless CO-2+CO-3+CO-4 independently satisfied | "Election validity requires independent satisfaction of completeness, authenticity, constitutional compliance" | CO-5, CO-2, CO-3, CO-4 labels; derivation rule | Appears separable: validity principle vs. CO structure form |
| ADR7-INV-01 | Suspension succession pre-designated in EC | "Critical function succession must be constitutionally pre-designated" (constitutional continuity requirement) | Specific succession chains; succession trigger rules | Appears separable: pre-designation requirement vs. chain specification |
| ADR7-INV-02 | No authority may self-grant expanded standing | "No authority may self-dealing in challenge standing" (Tier 3 challenge rights + CIC existence) | Specific anti-capture mechanism implementation | Ambiguous — the invariant itself may be the form realising a deeper principle (challenge independence), not a principle itself |
| 38B01-INV-01 | CIC interprets; CAB adjudicates | "Interpretation and adjudication are constitutionally distinct functions" (38B-01 CIC establishment) | Specific dispute routing procedure | Appears separable: function separation principle vs. routing form |
| 38B04-INV-01 | No self-appointment; no circular appointment; no downward capture | "Authority appointment must be constitutionally independent" (appointment governance 38B-04) | Specific appointment constraint rules | Appears separable: independence requirement vs. specific constraints |
| 38B05-INV-01 | Three amendment tiers; reclassification = Tier 3 | "Constitutional protection levels must be preserved against reclassification attacks" (38B-05 amendment architecture) | Three-tier structure; specific thresholds | Ambiguous — the three-tier structure may itself be the principle, not merely a form realising it |

**Summary observation:** Most existing invariants appear to contain a separable principle component and a separable form component. Four (ADR3-INV-01, ADR7-INV-02, 38B05-INV-01, and partially 38B05-INV-01) are genuinely ambiguous. ADR7-INV-02 and 38B05-INV-01 deserve particular ARB attention — if either is classified as a form rather than a principle under Option C, its constitutional protection changes significantly.

---

## Part H — Comparative Findings

### H.1 — Strengths by Option

| Option | Primary Strengths |
|--------|------------------|
| A | Maximum architectural agility; minimum AA-01 exposure increase; cleanest separation of concerns; fastest error-correction path; preserves independent governance layers (two attack surfaces for TM-06) |
| B | Maximum constitutional protection of existing ADR invariants; strongest TM-39/42/44/47 mitigation; clearest authority model (all decisions constitutional); full CIC and CAB challenge jurisdiction |
| C | Balances constitutional protection with architectural agility; targeted CIC expansion; allows form-level corrections without constitutional amendment; principled basis for future ADR governance |

### H.2 — Weaknesses by Option

| Option | Primary Weaknesses |
|--------|-------------------|
| A | Widest constitutional gap; independence forms revocable without constitutional visibility; TM-39 scenario structurally enabled; certification logic non-constitutional |
| B | Governance ossification; highest AA-01 dependency increase; CIC overload (interprets both constitutional and architectural); architectural corrections in 38C/38D become constitutionally expensive |
| C | Principle/form boundary contestable; boundary ambiguity exploitable; CIC acquires meta-role (new concentration point); EC extension required; bootstrapping problem |

### H.3 — Risk Profile by Option

| Risk | Option A | Option B | Option C |
|------|----------|----------|----------|
| Independence degradation without constitutional visibility | HIGH | LOW | MEDIUM |
| Governance ossification (error correction cost) | LOW | HIGH | MEDIUM |
| AA-01 single-point-of-failure expansion | LOW | HIGH | MEDIUM |
| Boundary ambiguity exploitation | N/A | N/A | MEDIUM-HIGH |
| CIC concentration increase | LOW | HIGH | MEDIUM |
| TM-39 structural enablement | HIGH | LOW | MEDIUM |
| TM-06 attack surface concentration | LOW (two systems) | MEDIUM-HIGH (one system) | LOW-MEDIUM (CIC node) |

---

## Part I — Questions Requiring ARB Ruling

The following questions emerged from this evaluation and cannot be resolved within the evaluation scope. Each question is classified as Closure-Blocking, Primary, Secondary, or Informational.

---

**OQ-38C-01 — Principle/Form Classifier Authority Under Option C**
Classification: **Primary**

Under Option C, which body has authority to classify a new ADR provision as principle vs. form? CIC (by interpretive mandate) is the natural candidate, but CIC appointment is controlled by MA — does this create a meta-governance concentration risk at the classification layer?

Rationale: If Option C is selected, this question is immediately load-bearing — every subsequent 38C artifact requires knowing who classifies new provisions. It does not block this evaluation but must be resolved before Option C can be operationalised. Primary rather than Closure-Blocking because it is option-conditional.

---

**OQ-38C-02 — EC Extension Amendment Level Under Option C**
Classification: **Secondary**

If EC is extended to include principle-level provisions (required for Option C viability), does that extension require Tier 2 or Tier 1 amendment? Who proposes the extension?

Rationale: Downstream from Option C selection. Only relevant if Option C is chosen. Does not affect this evaluation's comparative analysis. Secondary — implementation detail conditional on Option C selection.

---

**OQ-38C-03 — Existing ADR Invariants as Derived Constitutional Principles**
Classification: **Primary**

Under Option C, are existing ADR invariants (ADR3-INV-01, ADR5-INV-01, ADR6-INV-01, ADR7-INV-01, ADR7-INV-02) already constitutional principles by derivation from existing EC Tier 3 provisions — or do they require explicit EC designation to gain constitutional standing?

Rationale: This question affects the comparative analysis in Part G. If derived constitutional status is sufficient, the principle/form classification exercise is less demanding. If explicit EC designation is required, Option C carries a higher implementation burden than Part D suggests. Primary — affects evaluation accuracy across all options, not just post-selection implementation.

---

**OQ-38C-04 — ADR7-INV-02 Principle vs. Form Classification**
Classification: **Primary**

The anti-capture invariant (ADR7-INV-02) is assessed as ambiguous in Part G — it may be a form realising a deeper challenge-independence principle rather than a constitutional principle itself. This classification has significant TM-39 implications under Option C: if ADR7-INV-02 is a form, it has no constitutional protection under Option C and the independence-illusion risk remains structurally unmitigated.

Rationale: TM-39 is the F-4 dominant residual risk for the entire program. The constitutional status of the anti-capture invariant under Option C directly determines whether Option C meaningfully mitigates F-4. Primary — TM-39 implications are program-level.

---

**OQ-38C-05 — Option B Bootstrapping**
Classification: **Primary**

Under Option B, does the retroactive Tier 2 designation of all seven existing ADRs require a Tier 2 amendment process to enact — and if so, is that process bootstrapped by the selection of Option B itself? If the retroactive designation is itself an EC amendment, which body initiates it, and under what authority, before Option B is operative?

Rationale: A bootstrapping failure would make Option B structurally inviable regardless of its governance merits. Primary — affects Option B viability assessment. ARB should resolve this before selecting Option B.

---

## Part J — Recommendation Matrix

The following presents each option without selecting one. ARB selects.

| Dimension | Option A | Option B | Option C |
|-----------|----------|----------|----------|
| Constitutional protection of independence forms | LOW | HIGH | MEDIUM |
| Constitutional protection of ADR invariants | LOW (architectural only) | HIGH (Tier 2 constitutional) | MEDIUM (principle portion only) |
| Architectural correction agility | HIGH | LOW | MEDIUM |
| AA-01 dependency change | None | Significant increase | Moderate increase |
| CIC jurisdiction scope | Narrow (EC text) | Wide (all ADRs) | Intermediate (principles + boundary) |
| TM-06 structural impact | Two independent attack surfaces | Single constitutional system | Partial separation (CIC node) |
| TM-39 mitigation | None beyond Tier 3 minimum | Strong | Partial |
| TM-42/44/47 mitigation | Architectural | Constitutional | Partial |
| Bootstrapping complexity | None | High (retroactive Tier 2 designation — OQ-38C-05) | Medium (EC extension required) |
| Governance survivability (MA compromise) | Highest (ADR layer independent) | Lowest (both layers coupled) | Intermediate |
| Overall risk profile | HIGH constitutional gap; LOW ossification | LOW constitutional gap; HIGH ossification; HIGH AA-01 | MEDIUM both; boundary ambiguity risk |

**No option is recommended by this evaluation. ARB selects based on the full analysis above.**

**OQ-38B05-07 Status: EVALUATED — not resolved. ARB ruling required.**

---

*Round 38C-02 — OQ-38B05-07 ADR ↔ EC Relationship Evaluation — SUBMITTED FOR ARB REVIEW*
*Research Program: NRNA DDD Trustworthiness*
*Date: 2026-06-18*
*Input: Round38C-01 Strategic Discovery Charter (APPROVED WITH REVISIONS R1–R5)*
*38C01-INV-01: All outputs are hypotheses until ARB acceptance*
*OQ-38A05-02 PROTECTED: No implicit resolution in this document*
*Output: Evaluation only. ARB selects option. OQ-38B05-07 EVALUATED — not resolved.*
