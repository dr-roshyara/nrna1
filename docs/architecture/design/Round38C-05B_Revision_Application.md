# Round 38C-05B — Revision Application

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38C-05B — Revision Application
**Status:** COMPLETE — READY FOR 38C-06
**Purpose:** Apply ARB-accepted revisions R1–R5 from the 38C-05 ARB Review (OUTCOME B, 2026-06-19). Establish the CIC/ARB/EC-Design Routing Protocol. Verify gate conditions for 38C-06. This document does NOT issue the Classification Ruling. It does NOT resolve OQ-38B05-05. It does NOT assign tiers. It does NOT create new constitutional principles.
**Date:** 2026-06-19

**Inputs:**
- Round38C-05_Principle_Form_Classification_Exercise.md (OUTCOME B basis)
- Round38C-05-ARB-Review.md (OUTCOME B; R1–R5 required; CF-38C05-01 through CF-38C05-06)

**Governing constraints:**
- 38C01-INV-01: All classification outputs remain provisional hypotheses until 38C-06 ARB ruling
- 38C03-INV-01: No existing ADR invariant loses protection pending classification
- OQ-38A05-02: PROTECTED — no resolution in this document
- OQ-38B05-05: NOT RESOLVED — remains mandatory primary requirement for 38C
- OBS-38B06-05 (PERMANENT): Revision application ≠ classification correctness

---

## Part A — Revision Mandate

The following revisions were ordered by the 38C-05 ARB Review:

| Code | Nature | Subject |
|------|--------|---------|
| R1 | Minor | Part A.3: Add line-item inventory of deferred candidates |
| R2 | Moderate | CD-09 Principle Anchor: Add Option C consequence statement and EC designation mechanism |
| R3 | Required | CD-08 CIC Question 2: Restructure to separate interpretation authority from EC design gap |
| R4 | Required | CD-10 CIC Question 4: Remove from CIC escalation; replace with ARB conformity assessment placeholder |
| R5 | Required | CD-06-F: Add as CIC boundary determination candidate under 38C03-CON-03 |

Additionally, the ARB Review identified CF-38C05-02 as requiring a formal governance output before 38C-06:

**Mandatory Additional Output:** CIC/ARB/EC-Design Routing Protocol — defining the constitutional authority boundary for every escalated question in the exercise.

---

## Part B — R1: Deferred Candidate Inventory (Part A.3 Update)

### B.1 What Changed

Part A.3 previously categorized deferred candidates by class without enumerating individual items. The ARB Review (B-F-02) found this creates a completeness ambiguity: future exercises cannot confirm coverage without reconstructing the list from scratch.

### B.2 Updated Deferred Candidate Register

The following candidates are deferred to 38C-05b (second classification exercise) or to 38C-06 ruling disposition, as noted. These candidates were identified by the senior architect review of 38C-04 (DeepSeek assessment) as within the full scope of the classification program but outside the authorized 10-candidate primary scope of this exercise.

**Category 1: Trust Root Governance Architecture**

| Code | Candidate | Source | Deferral Reason |
|------|-----------|--------|-----------------|
| DC-E01 | EC governance authority over Legitimacy Root — constitutional character of EC's foundational role | 38B-01, 38A-05 | Classification follows OQ-38B05-05 ARB ruling (CD-10 dependent) |
| DC-E02 | AC-31 three-tier governance model (EC specifies properties / MA designates / Tier 3 multi-party verification) | 38B-02 | Classification tied to CD-05/CD-07 principle rulings in 38C-06 |
| DC-E03 | GovernanceState multi-party corroboration model (EC-anchored phase specification) | 38B-03 | Classification tied to CD-04/CD-07 principle rulings in 38C-06 |

**Category 2: Governance Authority Institutional Architecture**

| Code | Candidate | Source | Deferral Reason |
|------|-----------|--------|-----------------|
| DC-E04 | CertificationAuthority external independence form (CERT Option C — External Organization) | ADR-2 | Form classification tied to CD-09 governing Principle confirmation (CF-05-19 EC designation) |
| DC-E05 | ChallengeAdjudicationBody independence form (Options B/C deferred in ADR-7) | ADR-7 | ADR-7 explicitly deferred to Round 38A threat validation; classification follows form selection |
| DC-E06 | CIC independence form (interpretive independence — organizational vs. structural realization) | 38B-01 | Classification tied to CD-06-F boundary determination (R5 finding) |

**Category 3: Specific Tier Thresholds**

| Code | Candidate | Source | Deferral Reason |
|------|-----------|--------|-----------------|
| DC-E07 | Tier 3 threshold values (≥90% near-unanimity + 180-day deliberation + 90-day challenge window + 2-year cooling period) | 38B-05 | Threshold values are Form components of CD-08 principle; classification follows CD-08 ruling |
| DC-E08 | Tier 2 threshold values (2/3 supermajority + deliberation window) | 38B-05 | Same as DC-E07 |
| DC-E09 | Tier 1 amendment procedure (simple majority + deliberation minimum) | 38B-05 | Same as DC-E07 |

**Category 4: ADR-4 and ADR-6 Architecture Specifics**

| Code | Candidate | Source | Deferral Reason |
|------|-----------|--------|-----------------|
| DC-E10 | AuditScopeAuthority + AuditExecutionAuthority two-aggregate structure (ADR-4 approved) | ADR-4 | Form classification tied to CD-01-P/CD-03-P principles; audit scope architecture follows 38C-06 ruling |
| DC-E11 | Named Attestation Model for CO-3 evaluation (unified statement with individually challengeable sections) | ADR-6 | Form component of CD-03-P (CO-5 Derivation Requirement); classification follows 38C-06 ruling |
| DC-E12 | Tiered Materiality Model for certification under challenge (Option D from ADR-6) | ADR-6 | Form component of CD-03-P; same as DC-E11 |

**Total Deferred Candidates:** 12 (DC-E01 through DC-E12)
**Primary Scope Candidates (38C-05):** 10 (CD-01 through CD-10)
**Total Program Classification Scope:** 22 candidates minimum

**Note:** DC-E04 is related to but distinct from CD-09 (ADR-2 Per-Function Independence Forms). CD-09 covers all five D43 functions collectively as a class. DC-E04 specifically covers the CERT external organization architecture as an individual architectural element requiring its own classification once its governing Principle (CF-05-19) is EC-designated.

---

## Part C — R2: CD-09 Principle Anchor Update

### C.1 What Changed

The ARB Review (D-F-02) found that while CD-09's PROVISIONAL anchor status was correctly flagged, the document did not state: (a) the constitutional consequence of CF-05-19 non-designation under Option C; (b) the mechanism by which EC designation could occur; (c) the relationship between CD-09's standing and CD-05's classification.

### C.2 Revised Principle Anchor Analysis for CD-09

**Governing Principle for CD-09:** Constitutional Independence Requirement per D43 Function — the requirement that each of the five D43 authority functions (ENROLL, CRITERIA, AUDIT, GOV-AUTH, CERT) maintains at least one constitutionally independent authority relationship.

**Current anchor components:**

| Anchor Component | Status | Constitutional Level |
|----------------|--------|---------------------|
| CF-05-19 (confirmed discovery finding) | NOT YET EC-DESIGNATED | Discovery-level evidence only |
| AC-04 through AC-07 (architectural constraints from 36E-01) | NOT YET EC-DESIGNATED | Architectural constraint level only |
| ADR7-INV-02 (classified PRINCIPLE in CD-05, this exercise) | PRINCIPLE CANDIDATE (provisional) | Provisional Principle-level anchor |

**Principle Anchor Status: PROVISIONAL — Primary anchor satisfied by CD-05 (provisional); secondary anchors not yet EC-designated**

**Option C Consequence of CF-05-19 Non-Designation:**

If CF-05-19 is never EC-designated as a constitutional principle, the following consequences follow under Option C:

1. CD-09's Form classification loses its Option C provisional constitutional protection
2. The independence forms revert to ADR-level protection only — changeable through ADR amendment process without constitutional constraint and without Tier 1/2/3 threshold requirements
3. TM-39's Independence Illusion attack vector for D43 independence loses constitutional visibility: an authority could modify its independence form below constitutional adequacy without triggering constitutional review
4. CD-05 (ADR7-INV-02, Anti-Capture Invariant) remains a Principle, but it cannot anchor the D43 independence forms without the intermediate CF-05-19 constitutional requirement connecting the general anti-capture prohibition to the specific per-function independence standard
5. Practically: the formal independence of each D43 authority aggregate would become architecturally rather than constitutionally protected — the constitutional floor for independence disappears

**Mechanism for EC Designation:**

CF-05-19 can be EC-designated through the following sequence under 38C03-CON-02 (EC extension authorized):

1. **38C-06 Classification Ruling** classifies the underlying Principle (Constitutional Independence Requirement per D43 Function) as a Principle-level provision at its candidate tier
2. **38C-05b** classifies extended candidates (including DC-E04 through DC-E06 — individual authority independence forms) under the newly classified Principle
3. **EC Extension Document** (authorized by 38C03-CON-02): ARB authorizes drafting an EC provision that captures the Constitutional Independence Requirement per D43 Function with constitutional precision
4. **EC Amendment Process** (38B05-INV-01 tier requirements apply): The new EC provision is adopted through the appropriate tier process (Tier 2 candidate, given governance rule character)

**Until EC designation:** CD-09's constitutional protection under Option C is provisional and dependent on CD-05 (ADR7-INV-02) as its primary anchor. CD-09 has stronger protection than a Form with no anchor, but weaker protection than a Form whose governing Principle is EC-designated.

**CD-09/CD-05 Constitutional Dependency:** If CD-05 (Anti-Capture Invariant) were downgraded in 38C-06, CD-09's primary anchor loses its Principle classification. These two classifications are constitutionally coupled. 38C-06 must note this coupling explicitly.

---

## Part D — R3: CD-08 CIC Question 2 Rework

### D.1 What Changed

The ARB Review (G-F-01) found that CD-08 CIC Question 2 — "What amendment procedure applies when reclassifying the tier structure itself?" — is an EC design question, not a CIC interpretation question: CIC can only interpret existing provisions. If the constitution is silent on this procedure, CIC cannot rule on it.

### D.2 Revised CD-08 CIC Escalation (Question 2)

**Original Question 2 (REMOVED):**
"What amendment procedure applies when reclassifying the tier structure itself (e.g., from three tiers to two tiers)?"

**Revised Question 2 (REPLACED WITH):**

**CIC Interpretation Sub-Question:** "Does any existing constitutional provision — within 38B05-INV-01, any other ADR invariant, or any ARB-established ruling — specify the procedure for reclassifying the tier structure itself (e.g., from three tiers to two tiers, or from two tiers to four tiers)?"

- **If YES:** CIC interprets the relevant provision and determines what procedure it mandates.
- **If NO:** CIC returns a finding of constitutional silence. The gap is then flagged as a constitutional design requirement for the EC Extension process.

**EC Design Gap Flag (conditional):** If CIC finds constitutional silence on the reclassification procedure, the following gap is registered:

> Constitutional Design Gap CD-08-GAP-01: No constitutional provision currently specifies the amendment procedure for reclassifying the tier structure itself. Without specification, the anti-circumvention intent of 38B05-INV-01 (blocking Tier 3 reclassification via Tier 2) may be procedurally incomplete. EC must specify this procedure explicitly before the Protected Core can be considered constitutionally complete.

This gap cannot be resolved by CIC ruling — it requires EC constitutional design and adoption through the appropriate tier process.

### D.3 Updated CD-08 CIC Escalation Questions (Complete Set)

**EscRule-01 TRIGGERED** (Ambiguous designation; Legitimacy Root impact)

CIC must adjudicate:

1. **CIC Interpretation:** Is the three-tier structure itself constitutionally required (the principle), or is it one architectural form of a higher principle of differential protection? (If three-tier is the principle → constitutional. If differential protection is the principle → three-tier is form.)

2. **CIC Interpretation (revised):** Does an existing constitutional provision specify the procedure for tier structure reclassification? If yes, what procedure does it mandate? If no, return constitutional silence finding → EC Design Gap CD-08-GAP-01 registered.

3. **CIC Interpretation:** Does the three-tier structure require Tier 3 constitutional protection (as a meta-constitutional element — the amendment process protecting the amendment process), or is Tier 2 sufficient?

**Routing:** All three questions are CIC interpretation questions about existing provisions. None require CIC to create new constitutional provisions.

---

## Part E — R4: CD-10 CIC Question 4 Removal

### E.1 What Changed

The ARB Review (G-F-02) found that CD-10 CIC Question 4 — "Does the current architecture's three-root differentiation (OBS-38B06-02) satisfy a constitutional separation requirement?" — is an architecture-governance question, not a constitutional interpretation question. CIC interprets what constitutional provisions mean; ARB assesses whether architectural specifications satisfy those provisions.

### E.2 Removed CIC Question

**Original CD-10 CIC Question 4 (REMOVED FROM CIC ESCALATION):**
"Does the current architecture's three-root differentiation (OBS-38B06-02) satisfy a constitutional separation requirement, or is additional constitutional specification required?"

**Reason for removal:** This question asks CIC to evaluate an architectural specification (the 38B-01 through 38B-05 governance model as described in OBS-38B06-02) against a constitutional requirement. Evaluating whether an architecture satisfies a constitutional requirement is an ARB function, not a CIC function. CIC cannot assess architecture — CIC interprets constitutional meaning.

### E.3 ARB Conformity Assessment Placeholder (Replacement)

**Post-38C-06 ARB Assessment Item — CD-10-CONFORM-01:**

> After the constitutional requirement for trust root structural separation is established through:
> (a) CIC ruling on CD-10 Questions 1–3 (what separation constitutionally requires, at what tier, single provision or emergent), AND
> (b) ARB constitutional architecture ruling on OQ-38B05-05 (whether separation should be constitutionally required),
>
> the ARB evaluates architectural conformity:
>
> "Does the current governance architecture (as specified in 38B-01 through 38B-05 and described in OBS-38B06-02 — three roots structurally differentiated but not constitutionally protected as distinct) satisfy the constitutionally-defined separation requirement?"
>
> This assessment cannot begin until both (a) and (b) are complete. It is a post-38C-06 activity. The outcome may require architectural revision, EC extension, or confirmation that the current architecture is constitutionally sufficient.

### E.4 Updated CD-10 CIC Escalation Questions (Complete Set)

**EscRule-04 MANDATORY** (OQ-38B05-05 candidate; TR-05 reflexive)

CIC must adjudicate (Questions 1–3 only):

1. **CIC Interpretation:** Is trust root structural separation constitutionally required — i.e., does any existing EC provision or established invariant mandate it — or is it an architectural property that governance may achieve through non-constitutional means?

2. **CIC Interpretation (conditional on Q1 answer):** If constitutionally required, at what EC tier should trust root structural separation be protected? (Tier 3 — near-unanimity; or Tier 2 — qualified majority?)

3. **CIC Interpretation:** Is trust root separation a single constitutional provision (requiring explicit EC text), or is it an emergent property of the individual trust root governance provisions (38B-01/02/03 each separately protecting their respective root's governance, with separation arising from their combined constitutional architecture)?

**NOT a CIC question (CD-10-CONFORM-01):** Whether the current architecture satisfies these requirements — this assessment belongs to ARB after CIC ruling.

**ARB Constitutional Architecture Question (OQ-38B05-05):** Whether trust root separation SHOULD become a constitutional principle — this is an ARB-level governance decision, not a CIC interpretation question. OQ-38B05-05 routes to ARB.

---

## Part F — R5: CD-06-F CIC Boundary Determination

### F.1 What Changed

The ARB Review (G-F-03) found that CD-06-F's tight coupling (CIC/CAB institutional assignment as Form of the Interpretation/Adjudication Separation Principle) was flagged as an "ARB assessment item" rather than as a CIC boundary determination candidate. Under Option C (38C03-CON-03), CIC adjudicates principle/form boundary disputes — which is exactly what CD-06-F represents.

### F.2 CD-06-F CIC Boundary Determination Candidate

**CD-06-F Status:** FORM (MEDIUM confidence; tight coupling to governing Principle noted)

**Governing Principle:** CD-06-P (Interpretation/Adjudication Separation Principle)

**Updated Escalation Check for CD-06-F:**

Under 38C03-CON-03, CIC's jurisdiction includes adjudicating principle/form boundary disputes under Option C. The following boundary determination question is submitted to CIC as a candidate:

**CIC Boundary Determination Question CD-06-F-BDQ-01:**

> After the 38B specification cycle — in which CIC and CAB were fully established, appointed, given constitutional mandates, and made load-bearing for all subsequent constitutional architecture — has the CIC/CAB institutional assignment crossed from Form-level into constitutional Principle-level?
>
> Specifically: if an alternative set of bodies were designated to hold interpretation authority and adjudication authority respectively, would that designation:
> (a) Be a constitutional amendment (requiring Tier 2 or Tier 3 process), or
> (b) Be an architectural change (requiring ADR amendment process)?
>
> If (a) → the CIC/CAB institutional assignment has crossed from Form into constitutional principle through accumulation of load-bearing constitutional specification. The Form-to-Principle crossing must be adjudicated by CIC under its Option C boundary determination mandate.
>
> If (b) → the current FORM (MEDIUM) classification of CD-06-F is confirmed as correct.

**Routing:** CIC boundary determination under 38C03-CON-03.

**Priority:** MEDIUM. This question does not gate 38C-06 Classification Ruling — but it should be resolved before the EC Extension process drafts CD-06-P's EC provision, to ensure the provision's scope correctly captures whether CIC/CAB assignment carries constitutional weight.

---

## Part G — CIC/ARB/EC-Design Routing Protocol

### G.1 Governing Rationale

The 38C-05 ARB Review identified (CF-38C05-02) that the program lacks a formal boundary definition between three constitutional authorities. The classification exercise surfaced this gap when CD-10 Question 4 (architecture-governance) was routed to CIC, and when CD-08 Question 2 (potentially a constitutional design gap) was routed to CIC as an interpretation question.

This Routing Protocol formalizes the three-authority boundary. It applies to all escalation decisions in 38C-06 and all subsequent rounds.

### G.2 Authority Definitions and Boundaries

---

#### Authority 1: CIC (Constitutional Interpretation)

**What CIC can answer:**
- What does an existing constitutional provision mean?
- What is the scope of an existing constitutional requirement?
- Does an existing provision X imply or prohibit Y?
- What constitutional protection level does an existing provision carry?
- Does an existing Form implementation satisfy its governing Principle?
- Where does the principle/form boundary lie for an existing provision pair (under 38C03-CON-03)?

**What CIC CANNOT do:**
- Create new constitutional provisions or requirements
- Decide whether a new constitutional principle should exist
- Assess whether an architectural specification satisfies a constitutional requirement
- Fill constitutional gaps (absence of provision ≠ implicit prohibition; CIC can only return "constitutional silence")
- Make architectural governance decisions about program direction
- Substitute for ARB on constitutional architecture questions

**CIC question test:** Replace the proposed question with: "What does [existing provision X] mean regarding [issue Y]?" If the question cannot be reformulated this way — if there is no "existing provision X" — it is not a CIC question.

---

#### Authority 2: ARB (Architectural Governance)

**What ARB can answer:**
- Should a new constitutional principle exist? (Constitutional architecture decision)
- Does a proposed specification satisfy an established constitutional requirement?
- Is a proposed classification exercise constitutionally disciplined?
- Should OQ-38B05-05 be resolved in favor of making trust root separation a constitutional principle?
- What is the constitutional architecture for an underspecified area?
- Is an escalation correctly routed?

**What ARB CANNOT do:**
- Issue constitutional interpretations (that is CIC's mandate)
- Interpret the meaning of constitutional text provisions
- Substitute for CIC on meaning questions
- Directly draft constitutional provisions (that is EC Design)

**ARB question test:** Replace the proposed question with: "Should [constitutional architecture choice] be made?" or "Does [specification] satisfy [established requirement]?" If the question is about meaning rather than governance decision or conformity, it is not an ARB question.

---

#### Authority 3: EC Design (Constitutional Gap Creation)

**What EC Design addresses:**
- How should the constitution be written to address a constitutional gap?
- What threshold value should a Tier 2 or Tier 3 amendment requirement specify?
- What procedural mechanism should EC specify for a governance gap?
- What constitutional provision text would capture a classified Principle?

**What EC Design CANNOT do:**
- Occur without prior ARB authorization (38C03-CON-02 requires classification exercise → ARB ruling → EC extension)
- Resolve constitutional meaning questions (CIC does this)
- Bypass the tier amendment process (each tier's threshold applies to EC provisions at that tier)
- Be triggered by CIC silence finding alone — requires ARB authorization for EC extension

**EC Design trigger sequence:**
```
Classification Exercise identifies Principle
    ↓
38C-06 ARB Classification Ruling confirms Principle
    ↓
ARB authorizes EC extension under 38C03-CON-02
    ↓
EC Extension document drafted
    ↓
EC Amendment process (appropriate tier threshold)
```

---

### G.3 Routing Protocol Application to All Escalated Items

#### CD-08 (Three Amendment Tiers — AMBIGUOUS; EscRule-01)

| Question | Authority | Routing |
|----------|-----------|---------|
| Q1: Is three-tier structure itself constitutionally required? | CIC — interpretation of 38B05-INV-01 scope | → CIC |
| Q2 (revised): Does existing provision specify reclassification procedure? | CIC — interpretation; if no provision: return constitutional silence | → CIC; if silence: → EC Design Gap CD-08-GAP-01 |
| Q3: Tier 3 or Tier 2 protection for three-tier structure? | CIC — interpretation of Protected Core scope | → CIC |
| Tier assignment for CD-08 (if Principle confirmed) | ARB — after 38C-06 Classification Ruling | → ARB (post-38C-06) |

#### CD-10 (Trust Root Structural Separation — AMBIGUOUS; EscRule-04 MANDATORY)

| Question | Authority | Routing |
|----------|-----------|---------|
| Q1: Is structural separation constitutionally required? (Does existing provision mandate it?) | CIC — interpretation; if no provision: return constitutional silence | → CIC; ALSO → ARB for OQ-38B05-05 constitutional architecture decision |
| OQ-38B05-05: Should separation BECOME a constitutional principle? | ARB — constitutional architecture decision | → ARB |
| Q2: At what EC tier (if constitutionally required)? | CIC — interpretation of tier criteria (conditional on Q1) | → CIC (post-Q1) |
| Q3: Single provision or emergent property? | CIC — constitutional structure interpretation | → CIC |
| Q4 [REMOVED]: Does current architecture satisfy requirement? | ARB — architectural conformity assessment | → ARB (post-38C-06; CD-10-CONFORM-01 placeholder) |
| EC specification of separation requirement (if principle confirmed) | EC Design — draft constitutional text | → EC Design (post-ARB ruling) |

#### CD-06-F (CIC/CAB Institutional Assignment — FORM, MEDIUM; tight coupling)

| Question | Authority | Routing |
|----------|-----------|---------|
| CD-06-F-BDQ-01: Has CIC/CAB assignment crossed from Form to Principle? | CIC — boundary determination under 38C03-CON-03 | → CIC (MEDIUM priority) |
| If Form confirmed: CD-06-F at what tier? | ARB — tier assignment | → ARB (post-38C-06) |
| If Principle confirmed: EC provision scope for CIC/CAB | EC Design — draft EC text for body-level assignment | → EC Design (post-CIC ruling) |

#### CD-09 (ADR-2 Independence Forms — FORM, PROVISIONAL)

| Question | Authority | Routing |
|----------|-----------|---------|
| Does CF-05-19 already have constitutional force through implicit principle? | CIC — interpretation | → CIC (MEDIUM priority) |
| Should CF-05-19 be established as explicit constitutional principle? | ARB — constitutional architecture decision | → ARB (coordinate with 38C-06) |
| EC provision text for Constitutional Independence Requirement per D43 function | EC Design — draft EC text | → EC Design (post-ARB/CIC ruling, via 38C03-CON-02) |

#### Constitutional Design Gaps (Identified in this Exercise)

| Gap | Authority | Status |
|----|-----------|--------|
| CD-08-GAP-01: No existing provision specifies tier reclassification procedure | EC Design (requires CIC silence finding confirmation, then ARB authorization, then EC amendment) | CONDITIONAL — pending CIC Q2 response |
| CF-05-19 non-designation: Constitutional Independence Requirement per D43 unspecified | EC Design (requires ARB ruling + 38C03-CON-02 authorization) | PENDING |
| Trust Root Separation: No EC provision if OQ-38B05-05 ruled affirmative | EC Design (requires ARB ruling) | PENDING on OQ-38B05-05 |

---

## Part H — Updated Escalation Register

This register replaces the Output G (Mandatory CIC Escalations) from 38C-05 with the revised routing reflecting R3, R4, R5, and the Routing Protocol.

| Code | Candidate | Rule | Questions | Authority | Priority |
|------|-----------|------|-----------|-----------|---------|
| CD-08 | 38B05-INV-01 Three Tiers | EscRule-01 | Q1 (three-tier vs. differential), Q2 (revised: existing procedure provision?), Q3 (Tier 3 or 2?) | CIC (Q1/Q2/Q3) | HIGH |
| CD-10 | Trust Root Structural Separation | EscRule-04 MANDATORY | Q1 (existing mandate?), Q2 (tier, conditional), Q3 (single provision or emergent?) | CIC (Q1-Q3); ARB (OQ-38B05-05 constitutional architecture decision) | CRITICAL |
| CD-10-CONFORM-01 | Trust Root architectural conformity | Post-38C-06 placeholder | Does 38B architecture satisfy constitutional requirement? | ARB (post-38C-06) | DEFERRED |
| CD-06-F-BDQ-01 | CIC/CAB tight coupling boundary | 38C03-CON-03 | Has Form crossed into Principle? | CIC boundary determination | MEDIUM |
| CD-09 CF-05-19 | Constitutional Independence Requirement (implicit) | Option C anchor | Does CF-05-19 have implicit constitutional force? | CIC (interpretation); ARB (should principle be created?) | MEDIUM |
| CD-08-GAP-01 | Tier reclassification procedure gap | EC Design | How should EC specify this procedure? | EC Design (conditional on CIC Q2 finding) | CONDITIONAL |

**Items removed from escalation register:**
- ~~CD-10 CIC Question 4~~ — removed per R4; replaced by CD-10-CONFORM-01 (ARB, post-38C-06)
- ~~CD-08 CIC Question 2 (original)~~ — replaced by revised Q2 per R3

**Items added to escalation register:**
- CD-06-F-BDQ-01 (R5)
- CD-09 CF-05-19 routing (from R2 implication)
- CD-08-GAP-01 constitutional design gap (conditional, from R3)
- CD-10-CONFORM-01 ARB placeholder (R4)

---

## Part I — Updated Deferred Inventory (Complete)

### I.1 Primary Scope Status (CD-01 through CD-10)

All 10 primary candidates evaluated in 38C-05. No changes to classification findings from R1–R5 revisions. Revisions affect escalation routing, anchor analysis, and implication statements only.

| Code | Candidate | Classification | Post-Revision Status |
|------|-----------|---------------|---------------------|
| CD-01-P | Non-Substitution Principle | PRINCIPLE | UNCHANGED |
| CD-01-F | Three-Stratum Evidence Model | FORM | UNCHANGED |
| CD-02-P | Challenge Terminality Principle | PRINCIPLE | UNCHANGED |
| CD-02-F | R-8 Terminal Remedy | FORM | UNCHANGED |
| CD-03-P | CO-5 Derivation Requirement | PRINCIPLE | UNCHANGED |
| CD-03-F | CO-N Enumeration | FORM | UNCHANGED |
| CD-04-P | Succession Pre-Designation Principle | PRINCIPLE | UNCHANGED |
| CD-04-F | Specific Succession Chain Design | FORM | UNCHANGED |
| CD-05 | Anti-Capture Invariant | PRINCIPLE | UNCHANGED |
| CD-06-P | Interpretation/Adjudication Separation | PRINCIPLE | UNCHANGED |
| CD-06-F | CIC/CAB Institutional Assignment | FORM (MEDIUM) | R5: CIC boundary determination candidate added |
| CD-07-P | Appointment Independence Principle | PRINCIPLE | UNCHANGED |
| CD-07-F | Specific Appointment Assignments | FORM | UNCHANGED |
| CD-08 | Three Amendment Tiers | AMBIGUOUS → C-11 toward Principle | R3: CIC Q2 revised; CD-08-GAP-01 conditional |
| CD-09 | ADR-2 Independence Forms | FORM (PROVISIONAL) | R2: Option C consequence and mechanism added |
| CD-10 | Trust Root Structural Separation | AMBIGUOUS → C-11 toward Principle | R4: CIC Q4 removed; CD-10-CONFORM-01 placeholder added |

### I.2 Extended Scope (Deferred — 38C-05b)

DC-E01 through DC-E12 as enumerated in Part B (12 candidates).

### I.3 Total Classification Program Inventory

| Category | Count | Status |
|----------|-------|--------|
| Primary scope candidates (38C-05) | 10 | Evaluated |
| Components from separability findings | 6 additional named components | Evaluated |
| Extended scope candidates (38C-05b) | 12 | Deferred |
| Constitutional design gaps identified | 3 (CD-08-GAP-01, CF-05-19, Trust Root) | Conditional/Pending |
| **Total tracked items** | **28+** | |

---

## Part J — Verification

### J.1 OQ-38A05-02 Protection

OQ-38A05-02 (Finality vs. Validity) is PROTECTED throughout this document. No revision applied in 38C-05B touches OQ-38A05-02 directly or indirectly. The proximity documentation for CD-02-P and CD-03-P from 38C-05 is unchanged and carries forward.

**Verification: OQ-38A05-02 PROTECTED.**

### J.2 OQ-38B05-05 Status

OQ-38B05-05 (Trust Root Structural Separation) is NOT RESOLVED by this document. The revisions applied here (R4: CD-10 CIC Q4 removal, Routing Protocol OQ-38B05-05 routed to ARB) clarify the escalation pathway without resolving the underlying constitutional architecture question.

Post-revision state: CD-10 remains AMBIGUOUS with EscRule-04 mandatory. C-11 direction toward Principle is provisional. CIC Questions 1-3 remain pending. The ARB constitutional architecture ruling on OQ-38B05-05 is the MANDATORY PRIMARY REQUIREMENT that gates trust root separation's final designation.

**Verification: OQ-38B05-05 NOT RESOLVED. MANDATORY PRIMARY REQUIREMENT for 38C remains.**

### J.3 No New Principles Created

The revisions applied in this document do not create new constitutional principles. Specifically:

- R2 documents the consequence of CF-05-19 non-designation; it does not designate CF-05-19 as a Principle
- R3 identifies CD-08-GAP-01 as a constitutional design gap; it does not fill the gap
- R4 creates a post-38C-06 ARB placeholder; it does not rule on trust root separation
- R5 routes CD-06-F to CIC boundary determination; it does not re-classify CD-06-F

**Verification: No new constitutional principles created.**

### J.4 OBS-38B06-05 Status

OBS-38B06-05 (specification completeness ≠ correctness) remains PERMANENT. The revision application completes the ARB-ordered changes to the classification exercise. Completion of revisions does not imply the underlying classifications are constitutionally correct — they remain provisional hypotheses until 38C-06 ARB Classification Ruling.

**Verification: OBS-38B06-05 RESPECTED.**

### J.5 Discovery Evidence Note (From ARB Review K-F-02)

The ARB Review noted that Part K-F-01's phrase "constitutional source evidence" should be read as "discovery evidence" — threat findings, ARB observations, and architectural constraints that support the Principle classification candidacy. These are not enacted EC provisions. All Principles classified in 38C-05 have discovery evidence supporting their Principle candidacy. None have enacted EC provisions (which is expected at this stage — EC extension follows 38C-06).

**Clarification for 38C-06:** All Principles produced by 38C-05 carry discovery-level evidence. Constitutional enactment through EC extension (38C03-CON-02) is the post-38C-06 activity that converts discovery evidence into enacted constitutional provisions.

---

## Part K — Gate Status for 38C-06

### K.1 Gate Conditions

The 38C-05 ARB Review (Part P) specified three required conditions before 38C-06 may begin:

| Condition | Status |
|-----------|--------|
| (a) R3, R4, and R5 applied to 38C-05 | **COMPLETE** — Applied in Parts D, E, F of this document |
| (b) CIC/ARB/EC-Design Routing Protocol established | **COMPLETE** — Part G of this document |
| (c) OQ-38B05-05 remains NOT RESOLVED entering 38C-06 | **CONFIRMED** — J.2 above |

### K.2 Additional Minor Revisions

| Condition | Status |
|-----------|--------|
| R1 — Deferred candidate inventory enumerated | **COMPLETE** — Part B of this document |
| R2 — CD-09 Option C consequence documented | **COMPLETE** — Part C of this document |

### K.3 Authorization Confirmation

All gate conditions from the 38C-05 ARB Review are met.

```
38C-06 — ARB Classification Ruling — AUTHORIZED TO COMMENCE

Governing inputs for 38C-06:
  38C-05 classification findings (R1-R5 revisions applied)
  38C-05B Routing Protocol (Part G)
  Updated Escalation Register (Part H)
  Deferred Inventory (Part I)

38C-06 shall:
  Issue final classification determinations for CD-01 through CD-10
  Address CD-08 and CD-10 Ambiguous designations
  Note CD-05 Tier 3 candidacy
  Note CD-09/CD-05 constitutional coupling
  NOT assign tiers (separate post-38C-06 activity)
  NOT resolve OQ-38B05-05 (MANDATORY PRIMARY REQUIREMENT
    requiring separate ARB constitutional architecture ruling)
  NOT resolve OQ-38A05-02 (PROTECTED)
  NOT perform EC extension (38C03-CON-02 governs; post-38C-06)

OQ-38B05-05 status entering 38C-06:
  AMBIGUOUS (CD-10)
  EscRule-04 mandatory
  C-11 directs toward Principle (provisional)
  ARB constitutional architecture ruling: PENDING
  This is the most consequential open question in the program.
  Everything after 38C-06 will be shaped by how OQ-38B05-05 is ruled.
```

---

*Round 38C-05B — Revision Application — COMPLETE*
*Research Program: NRNA DDD Trustworthiness*
*Date: 2026-06-19*
*Revisions Applied: R1–R5 (complete)*
*Routing Protocol: Established (Part G)*
*Gate Status: ALL CONDITIONS MET*
*Next: 38C-06 — ARB Classification Ruling*
*OQ-38B05-05: MANDATORY PRIMARY REQUIREMENT — NOT RESOLVED*
*OQ-38A05-02: PROTECTED THROUGHOUT*
