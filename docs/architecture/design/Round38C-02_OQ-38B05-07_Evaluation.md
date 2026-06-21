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

**No resolution. No selection. No authorization. No architectural design. No bounded contexts. No aggregates. No technical architecture. Evaluation only.**

**Note:** This document supersedes Round38C-02_OQ-38B05-07_ADR_EC_Relationship_Resolution.md, which was produced before governance discipline was confirmed. That document should not be treated as an approved evaluation.

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

The gap between what EC protects and what ADRs provide:

| Constitutional Layer (EC) | Architectural Layer (ADR) | Gap |
|---------------------------|---------------------------|-----|
| Challenge rights (Tier 3) | Challenge mechanism design (ADR-5) | Challenge existence is constitutional; mechanism design is not |
| AC-31 minimum (Tier 3) | Reference standard architecture (ADR-3) | AC-31 existence is constitutional; how it is implemented is not |
| CAB independence principle (Tier 3) | CAB appointment mechanics (ADR-2) | Independence requirement is constitutional; implementation is not |
| CIC existence (Tier 3) | CIC jurisdiction scope (38B-01) | Existence is constitutional; jurisdiction scope is not |
| MA ratification requirement (Tier 3) | MA functional appointment scope (38B-04) | Requirement is constitutional; scope is not |

**Structural observation (not finding):** This gap appears wide. Independence forms, certification logic, and succession mechanisms are entirely below the constitutional layer under Option A.

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
- Future generations of architects may not recognise the constitutional gap and treat ADR decisions as constitutionally binding without EC protection

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
| All ADR decisions at Tier 2 | Potential future ADRs not yet written — do they require EC enactment to exist? |
| Independence forms constitutional | Organisational realization of independence (outside program scope) still external |
| Certification logic constitutional | AC-31 realization (technical infrastructure) still outside scope |

**Structural observation:** The constitutional gap narrows significantly. Almost all current architectural decisions gain constitutional protection. The remaining gap is at implementation layer (outside program scope).

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
- If a future technical architecture ADR (in 38C or beyond) contains an error, correcting it requires Tier 2 constitutional process — slowing implementation

---

## Part D — Option C Analysis: Hybrid Principle/Form Split

### D.1 — Authority Source

EC holds constitutional principles at assigned tiers (Tier 1/2/3). ADRs hold implementation forms at the architectural level. Each has distinct standing. EC-held principles carry constitutional authority; ADR-held forms carry architectural authority only.

### D.2 — Amendment Requirements

Changing a constitutional principle requires EC amendment at its assigned tier (Tier 1, 2, or 3 depending on classification). Changing an implementation form requires ARB architectural review only. The boundary between principle and form is the central interpretive question under Option C.

### D.3 — Challengeability

Constitutional challenge procedures apply to principle violations. Form disputes go to architectural governance. The critical discipline: distinguishing a principle violation from a form dispute. CIC adjudicates boundary disputes.

### D.4 — CIC Jurisdiction

CIC interprets EC principles AND adjudicates principle/form boundary disputes when contested. This is a meaningful expansion beyond Option A (EC text only) but narrower than Option B (all ADRs). The boundary-determination role is new to CIC under Option C.

### D.5 — Principle/Form Classification — Candidate Observations

**Governance discipline note:** The following observations identify elements that *appear* principle-like or form-like based on their constitutional grounding. These are observations for ARB consideration — not definitive classifications. ARB may accept, reject, or recategorise any entry.

| ADR Element | Appears Principle-Like | Appears Form-Like | Ambiguous |
|-------------|------------------------|-------------------|-----------|
| ADR3-INV-01 evidence strata independence | EC grounding: AC-31 minimum (Tier 3) + Gap A-3 constitutional concern; appears principle-like | Specific stratum architecture (Three-Stratum) appears form-like | **Ambiguous**: whether "strata must be distinct" is principle or "three specific strata" is the principle |
| ADR5-INV-01 R-8 terminal | Challenge rights in Tier 3 protect challenge existence; "constitutional challenge is terminal within cycle" appears principle-like | "R-8" label and specific remedy taxonomy appear form-like | — |
| ADR6-INV-01 CO-5 void without CO-2/CO-3/CO-4 | "Election validity requires independent satisfaction of completeness, authenticity, constitutional compliance" appears principle-like | CO-5 label, CO-2/CO-3/CO-4 labels, specific object definitions appear form-like | — |
| ADR7-INV-01 suspension succession | "Critical function succession must be pre-designated" appears principle-like (derived from constitutional continuity mandate) | Specific succession chains appear form-like | — |
| ADR7-INV-02 anti-capture invariant | "No authority may self-grant expanded standing" appears principle-like; derived from Tier 3 challenge rights + CIC existence | Specific implementation of anti-capture checking appears form-like | **Ambiguous**: is the invariant itself a principle or is it a form that realises a deeper principle? |
| ADR-2 per-function independence forms | "Independence required per constitutional function" appears principle-like | Committee structure, external organisation, hybrid design per function appears form-like | — |
| 38B01-INV-01 CIC interprets; CAB adjudicates | Appears principle-like — derived from constitutional separation of interpretation and adjudication | Specific procedure for routing disputes appears form-like | — |
| 38B04-INV-01 no self-appointment | Appears principle-like — constitutional anti-corruption | Specific appointment procedures appear form-like | — |

### D.6 — Advantages

- Preserves constitutional protection for principles while allowing form-level architectural corrections without Tier 2 process
- CIC jurisdiction expansion is targeted (boundary determinations) rather than total (all ADRs)
- Independence requirement principle can be constitutionally protected while allowing implementation forms to adapt to technical realization
- Balances constitutional resilience with architectural agility

### D.7 — Disadvantages

- Principle/form boundary is contestable — adversaries may exploit boundary ambiguity to reclassify protections
- CIC acquires a new meta-role (boundary determination) that is itself a potential concentration point
- EC must be extended to explicitly contain principle-level provisions — this requires a new EC amendment process before Option C is fully operative
- The classification exercise (which ADR elements are principles vs. forms) is itself a significant governance task that requires ARB authorization (a separate future round)

### D.8 — Open Risks

- Boundary ambiguity between principle and form may become a strategic governance target
- Principle/form classification may itself require Tier 2 amendment — creating a bootstrapping problem
- CIC's new boundary-determination role may be challenged (CIC adjudicates the scope of its own jurisdiction — self-referential risk)
- If EC extension to include principle provisions is not completed before 38C moves to technical architecture, a governance gap persists during that period

---

## Part E — Threat-Model Correlation

The following evaluates each option's impact on the 38A threat findings that motivated the original OQ-38B05-07 concern. Per 38C-01 Required Output 7 Step 4b instruction: analysis of every existing invariant whose constitutional force changes per option.

### E.1 — TM-19: AC-31 Reference Standard Capture

**Threat:** Compromise of the AC-31 reference standard, enabling false authentication claims to appear valid.

| Option | Impact |
|--------|--------|
| A | AC-31 minimum is Tier 3 protected (38B-05); AC-31 reference standard architecture (ADR-3) is non-constitutional. Capture of the reference standard implementation remains architecturally revocable without constitutional visibility. Partial protection only. |
| B | ADR-3 becomes Tier 2 constitutional. Reference standard architecture is constitutionally protected. Changes to how AC-31 is implemented require Tier 2 amendment. Strong mitigation. |
| C | AC-31 existence principle is constitutional; reference standard architecture is a form. Implementation capture is architecturally revocable; however, principle-level intervention by CIC is available if a capture attempt violates the independence principle. Partial-to-moderate mitigation depending on principle classification. |

### E.2 — TM-39: Independence Illusion (F-4)

**Threat:** Structural independence of authorities exists in design but not in practice — authorities are nominally independent but effectively captured through informal mechanisms.

| Option | Impact |
|--------|--------|
| A | Independence forms (ADR-2 per-function) are architecturally revocable. Independence degradation can occur through architectural revision without constitutional visibility. TM-39 scenario is structurally enabled under Option A. No mitigation beyond EC-protected independence existence (Tier 3 for CAB independence principle). |
| B | Independence forms become Tier 2 constitutional. Changing independence form (e.g., replacing committee independence with internal appointment) requires Tier 2 amendment + deliberation. Strong structural mitigation. Independence degradation has constitutional visibility. |
| C | Independence requirement principle is constitutional; independence form is architectural. The principle protects against elimination of independence; it does not protect against degradation of form. A nominally independent committee that becomes practically captured does not necessarily violate the principle. Partial mitigation — stronger than Option A, weaker than Option B for form-level attacks. |

### E.3 — TM-42: Operational Deadlock (Complete Deadlock = FAIL)

**Threat:** Simultaneous vacancy or incapacity of sufficient constitutional authorities to reconstitute normal operation.

| Option | Impact |
|--------|--------|
| A | ADR7-INV-01 (suspension succession) is non-constitutional. Succession chains are architectural and revocable. Reconstitution mechanisms could be weakened without constitutional process. TM-42 mitigation is architectural only. |
| B | ADR7-INV-01 becomes Tier 2 constitutional. Succession architecture is constitutionally protected. Changes to succession chains require Tier 2 amendment. Strengthens TM-42 mitigation. |
| C | Succession principle ("critical function succession must be pre-designated") appears constitutional; specific succession chains appear formal. Principle protects against complete elimination of succession; form-level degradation (weak succession chains) may not trigger constitutional protection. Partial mitigation. |

### E.4 — TM-44: Temporal Concentration / GovernanceState Rollback

**Threat:** Temporal governance phase records manipulated, rolled back, or contested to create constitutional ambiguity about the current election phase.

| Option | Impact |
|--------|--------|
| A | ADR-7 GovernanceState boundary architecture is non-constitutional. Phase record governance mitigations are architectural and revocable. TM-44 mitigation is architectural only. |
| B | ADR-7 becomes Tier 2 constitutional. GovernanceState architecture is constitutionally protected. Rollback mitigations cannot be removed without Tier 2 amendment. Strengthens TM-44 mitigation. |
| C | GovernanceState independence principle ("phase record must be externally corroborated") appears constitutional; specific corroboration architecture appears formal. Partial mitigation — principle protects against elimination; implementation attacks against corroboration mechanisms may not trigger constitutional protection. |

### E.5 — TM-47: Certification Chain Self-Reference Exploitation

**Threat:** Certification chain becomes self-referential, enabling compromised components to certify their own compliance — particularly the AC-31 authenticity ratchet.

| Option | Impact |
|--------|--------|
| A | ADR6-INV-01 (CO-5 void without CO-2/CO-3/CO-4) is non-constitutional. CO-5 derivation rule is architectural. A compromised CertificationAuthority could architecturally weaken the CO-5 derivation rule without constitutional process. |
| B | ADR6-INV-01 becomes Tier 2 constitutional. CO-5 derivation rule is constitutionally protected. Weakening it requires Tier 2 amendment. Strong mitigation. |
| C | "Election validity requires independent satisfaction of completeness, authenticity, constitutional compliance" appears principle-like; specific CO object structure appears form-like. Principle is constitutional; derivation rule implementation is architectural. Partial mitigation — self-reference attacks against the CO structure may not trigger constitutional protection if framed as form-level changes. |

### E.6 — Threat Correlation Summary

| Threat | Option A | Option B | Option C |
|--------|----------|----------|----------|
| TM-19 (AC-31 capture) | Partial | Strong | Partial-to-Moderate |
| TM-39 (Independence Illusion) | NONE beyond Tier 3 minimum | Strong | Partial (principle-level) |
| TM-42 (Operational Deadlock) | Architectural only | Constitutional | Partial (principle-level) |
| TM-44 (GovernanceState rollback) | Architectural only | Constitutional | Partial (principle-level) |
| TM-47 (Certification self-reference) | Architectural only | Constitutional | Partial (principle-level) |

---

## Part F — AA-01 Dependency Analysis

AA-01 is the pre-constitutional assumption that the Membership Assembly (MA) is a legitimately constituted sovereign body. As of 38B-04, MA holds 15 constitutional functions. The following evaluates how each option changes AA-01 dependency.

### F.1 — Current AA-01 Exposure Baseline (38B state)

MA currently controls: CIC appointment (38B-04), CriteriaAuthority appointment, AuditScopeAuthority appointment, GovernanceAuthority appointment, CertificationAuthority appointment, CAB appointment, EnrollmentAuthority appointment, AC-31 designation (38B-02 Tier 2), EC ratification (38B-05), and amendment process ratification. Total: 15 functions.

All of these trace to AA-01. If AA-01 is invalid (MA is not legitimately constituted), all 15 functions are simultaneously compromised.

### F.2 — Option A: Change in AA-01 Exposure

Under Option A, ADRs are not EC instruments. ADR amendments do not require EC Tier 2 processes. MA's role does not expand into ADR governance.

**Change:** Minimal. AA-01 exposure remains at the 38B baseline. MA does not gain new ADR-related functions.

**Concentration risk:** MA concentration does not increase under Option A. The 15 current functions are unchanged.

**Governance survivability impact:** Under Option A, the constitutional layer (governed by MA) is separated from the architectural layer (governed by ARB). This creates a separation that could provide governance survivability if MA is compromised — the architectural layer (ADRs) could potentially continue operating independently.

### F.3 — Option B: Change in AA-01 Exposure

Under Option B, all ADRs become Tier 2 EC instruments. All ADR amendments require Tier 2 EC amendment process, which traces to MA ratification.

**Change:** MA's effective scope expands to include ADR governance. Every ADR correction, extension, or revision requires constitutional amendment traceable to MA. MA now effectively controls:
- All 15 existing constitutional functions (38B baseline)
- Plus: authority over ADR amendment processes (7 existing ADRs, plus all future 38C ADRs)

**Concentration risk:** AA-01 dependency increases significantly under Option B. An AA-01 failure simultaneously compromises constitutional governance AND all architectural governance.

**Governance survivability impact:** Option B creates a single point of AA-01 failure that extends from constitutional governance through architectural governance. If MA (AA-01) is captured or illegitimate, both layers are simultaneously affected. This may represent the highest AA-01 exposure of the three options.

### F.4 — Option C: Change in AA-01 Exposure

Under Option C, EC holds principles (constitutional, traces to MA) and ADRs hold forms (architectural, traces to ARB). Principle amendments trace to MA; form amendments do not.

**Change:** MA's scope expands modestly. Principle-level provisions in EC require Tier 1/2/3 amendment depending on classification — these trace to MA. Form-level ADR changes do not require MA involvement.

**Concentration risk:** AA-01 dependency increases moderately under Option C — more than Option A, less than Option B. The magnitude depends on how many ADR elements are classified as principles vs. forms. If most ADR invariants are classified as principles (Tier 2), AA-01 exposure approaches Option B levels.

**Governance survivability impact:** Option C provides partial separation — form-level corrections can occur without MA involvement. This provides some resilience compared to Option B if MA is temporarily unavailable or compromised.

### F.5 — AA-01 Dependency Summary

| Option | Change in AA-01 Exposure | Governance Survivability |
|--------|--------------------------|--------------------------|
| A | None (baseline maintained) | Highest — ADR layer independent of MA |
| B | Significant increase — MA controls ADR amendment processes | Lowest — single AA-01 failure covers both layers |
| C | Moderate increase — depends on principle/form classification ratio | Intermediate — form layer retains MA independence |

---

## Part G — ADR Invariant Analysis

Per 38C-01 Required Output 7, each existing ADR invariant is assessed for whether it appears principle-like, form-like, or ambiguous. **These are observations for ARB consideration — not definitive classifications.**

| Invariant | What It Protects | Appears Principle-Like | Appears Form-Like | Assessment |
|-----------|-----------------|------------------------|-------------------|------------|
| ADR3-INV-01 | Evidence strata independence (Completeness ≠ Presence ≠ Authenticity) | "Authentication and completeness are constitutionally distinct concerns" (grounded in AC-31 minimum + Gap A-3) | Three-Stratum specific architecture | Ambiguous: the distinction requirement appears principle-like; the specific three-stratum realisation appears form-like |
| ADR5-INV-01 | R-8 is constitutionally terminal | "Constitutional challenge is terminal within election cycle" (grounded in Tier 3 challenge rights) | R-8 label; remedy taxonomy (R-1 through R-8) | Appears separable: terminality principle vs. taxonomy form |
| ADR6-INV-01 | CO-5 void unless CO-2+CO-3+CO-4 independently satisfied | "Election validity requires independent satisfaction of completeness, authenticity, constitutional compliance" (grounded in certification mandate) | CO-5, CO-2, CO-3, CO-4 labels; specific derivation rule | Appears separable: validity principle vs. CO structure form |
| ADR7-INV-01 | Suspension succession pre-designated in EC | "Critical function succession must be constitutionally pre-designated" (grounded in constitutional continuity requirement) | Specific succession chains; succession trigger rules | Appears separable: pre-designation requirement vs. chain specification |
| ADR7-INV-02 | No authority may self-grant expanded standing | "No authority may self-dealing in challenge standing" (grounded in Tier 3 challenge rights + CIC existence) | Specific anti-capture mechanism implementation | **Ambiguous**: the invariant itself may be the form realising a deeper principle (challenge independence), not a principle itself |
| 38B01-INV-01 | CIC interprets; CAB adjudicates | "Interpretation and adjudication are constitutionally distinct functions" (grounded in 38B-01 CIC establishment) | Specific routing procedure for disputes | Appears separable: function separation principle vs. routing form |
| 38B04-INV-01 | No self-appointment; no circular appointment; no downward capture | "Authority appointment must be constitutionally independent" (grounded in appointment governance 38B-04) | Specific appointment constraint rules | Appears separable: independence requirement vs. specific constraints |
| 38B05-INV-01 | Three amendment tiers; reclassification = Tier 3 | "Constitutional protection levels must be preserved against reclassification attacks" (grounded in 38B-05 amendment architecture) | Three-tier structure; specific thresholds | **Ambiguous**: the three-tier structure may itself be the principle, not merely a form |

**Summary observation (not finding):** Most existing invariants appear to contain a separable principle component and a separable form component. Two (ADR7-INV-02 and 38B05-INV-01) are genuinely ambiguous — the invariant itself may be the principle, not a form realising a deeper principle. ARB classification of these two should be treated with particular care.

---

## Part H — Comparative Findings

### H.1 — Advantages by Option

| Option | Primary Advantages |
|--------|-------------------|
| A | Maximum architectural agility; minimum AA-01 exposure increase; cleanest separation of concerns; fastest error-correction path |
| B | Maximum constitutional protection of existing ADR invariants; strongest TM-39/42/44/47 mitigation; clearest authority model (all decisions are constitutional) |
| C | Balances constitutional protection with architectural agility; targeted CIC expansion; allows form-level corrections without constitutional amendment; most appropriate if principle/form classification is reliable |

### H.2 — Disadvantages by Option

| Option | Primary Disadvantages |
|--------|----------------------|
| A | Widest constitutional gap; independence forms revocable without constitutional visibility; TM-39 scenario structurally enabled; certification logic non-constitutional |
| B | Governance ossification; highest AA-01 exposure increase; CIC overload; architectural corrections in 38C/38D become constitutionally expensive; EC becomes very large |
| C | Principle/form boundary contestable; boundary ambiguity exploitable; CIC acquires meta-role (new concentration point); EC extension required before Option C is fully operative; bootstrapping problem |

### H.3 — Open Risks by Option

| Risk | Option A | Option B | Option C |
|------|----------|----------|----------|
| Independence degradation without constitutional visibility | HIGH | LOW | MEDIUM |
| Governance ossification (error correction cost) | LOW | HIGH | MEDIUM |
| AA-01 single-point-of-failure expansion | LOW | HIGH | MEDIUM |
| Boundary ambiguity exploitation | N/A | N/A | MEDIUM-HIGH |
| CIC concentration increase | LOW | HIGH | MEDIUM |
| TM-39 structural enablement | HIGH | LOW | MEDIUM |

---

## Part I — Questions Requiring ARB Ruling

The following questions emerged from this evaluation and cannot be resolved within the evaluation scope:

**OQ-38C-01:** Under Option C, which body has authority to classify a new ADR provision as principle vs. form? CIC (by interpretive mandate) is the natural candidate, but CIC appointment is controlled by MA — does this create a meta-governance concentration risk at the classification layer?

**OQ-38C-02:** If EC is extended to include principle-level provisions (required for Option C viability), does that extension require Tier 2 or Tier 1 amendment? Who proposes the extension?

**OQ-38C-03:** Under Option C, are the five existing ADR invariants (ADR3-INV-01, ADR5-INV-01, ADR6-INV-01, ADR7-INV-01, ADR7-INV-02) already constitutional principles by derivation from existing EC Tier 3 provisions — or do they require explicit EC designation?

**OQ-38C-04:** The anti-capture invariant (ADR7-INV-02) appears ambiguous in Part G — is it a form realising a deeper challenge-independence principle, or is it itself a constitutional principle? This classification has significant TM-39 implications under Option C.

**OQ-38C-05:** Under Option B, does the retroactive Tier 2 designation of all seven existing ADRs require a Tier 2 amendment process to enact — and if so, is that process bootstrapped by the selection of Option B itself?

---

## Part J — Recommendation Matrix

The following presents each option without selecting one. ARB selects.

| Dimension | Option A | Option B | Option C |
|-----------|----------|----------|----------|
| Constitutional protection of independence forms | LOW | HIGH | MEDIUM |
| Constitutional protection of ADR invariants | LOW (architectural only) | HIGH (Tier 2 constitutional) | MEDIUM (principle portion only) |
| Architectural correction agility | HIGH | LOW | MEDIUM |
| AA-01 dependency change | None | Significant increase | Moderate increase |
| CIC jurisdiction scope | Narrow | Wide | Intermediate |
| TM-39 mitigation | None beyond Tier 3 minimum | Strong | Partial |
| TM-42/44/47 mitigation | Architectural | Constitutional | Partial |
| Bootstrapping complexity | None | High (retroactive Tier 2 designation) | Medium (EC extension required) |
| Governance survivability (MA compromise) | Highest (ADR layer independent) | Lowest (both layers coupled) | Intermediate |
| Overall risk profile | HIGH constitutional gap; LOW ossification | LOW constitutional gap; HIGH ossification | MEDIUM both |

**No option is recommended by this evaluation. ARB selects based on the full analysis above.**

---

*Round 38C-02 — OQ-38B05-07 ADR ↔ EC Relationship Evaluation — SUBMITTED FOR ARB REVIEW*
*Research Program: NRNA DDD Trustworthiness*
*Date: 2026-06-18*
*Input: Round38C-01 Strategic Discovery Charter (APPROVED WITH REVISIONS R1–R5)*
*38C01-INV-01: All outputs are hypotheses until ARB acceptance*
*OQ-38A05-02 PROTECTED: No implicit resolution in this document*
*Output: Evaluation only. ARB selects option.*
