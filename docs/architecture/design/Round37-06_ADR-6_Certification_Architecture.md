# Round 37-06 — ADR-6: Certification Architecture

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 37 — ADR Authoring
**Document:** ADR-6 of 7
**Status:** APPROVED — Required Revisions Applied (2026-06-16)
**Governing Question:** What is CertificationAuthority certifying, must it independently evaluate the three evidence strata, can it certify under active challenge, and what is the terminal certification state for an election cycle?

**Predecessors:**
- ADR-1 — Authority Vocabulary and Authority Source Model — APPROVED
- ADR-2 — Independence Form per D43 Function — APPROVED
  Key inheritance: CERT = Option C (External Organization); AC-27 external challenge reception; terminal risk
- ADR-3 — Evidence and Verifier Architecture — APPROVED
  Key inheritance: Three-stratum model; AC-31; CertificationAuthority prohibition (may not certify Authenticity Stratum compliance without independently accessing AC-31 reference standard)
- ADR-4 — Audit Scope Authority Structure — SUBMITTED FOR ARB REVIEW
  Key inheritance: AuditScopeAuthority Tier 2 specification must be in certification evidence package; CertificationAuthority must independently access scope specification
- ADR-5 — Challenge Architecture — APPROVED (Required Revisions Applied)
  Key inheritance: CertificationAuthority L-3 challenge profile; R-7 (Recertify) remedy conditions; CertificationAuthority has S-3 standing to challenge AuditScopeAuthority and AuditExecutionAuthority; OQ-37-04-01 partially answered (Tier 2 publication must be evidentially recoverable)

**Scope:** Conceptual architecture only. No certification workflow implementation. No API design. No technical process specification. Certification architecture = what constitutional requirements the CertificationAuthority function must satisfy, what it is certifying, how it relates to the three evidence strata and the challenge architecture, and where constitutional finality exists — not how those requirements are technically executed.

**Required Discipline:** Independence ≠ externality. Certification ≠ audit. Certification ≠ challenge adjudication. Certification ≠ governance authorization. ADR3-INV-01 applies. AC-31 applies.

---

## Part A — Problem Statement

### A.1 The Nine Governing Questions

ADR-6 must answer nine constitutional questions about certification:

1. **What is CertificationAuthority certifying?** (Process compliance? Evidence completeness? Evidence authenticity? Constitutional compliance? Election validity? These are constitutionally distinct — collapsing them is one of the most common election governance failures.)
2. **Are these certifications separate or unified?** Can a single certification statement cover all, or must they be constitutionally distinct statements?
3. **Must CertificationAuthority independently evaluate completeness, presence, and authenticity — or may it rely on AuditExecutionAuthority's findings?**
4. **Does ADR-3 AC-31 require CertificationAuthority access to the same independent reference standard used for authenticity verification?**
5. **Can CertificationAuthority certify an election that is still under active challenge?**
6. **What challenge outcomes invalidate a prior certification?**
7. **What challenge outcomes require recertification?**
8. **What constitutional conditions justify refusing certification?**
9. **What is the terminal certification state for an election cycle?**

### A.2 The Certification Collapse Risk

ADR-5 identified that many election systems collapse audit, challenge, appeal, and certification into one governance function. ADR-6 faces an analogous risk: certification may collapse multiple distinct constitutional assessments into one statement. The ARB has identified the following as constitutionally distinct and NOT equivalent:

```
Process Compliance     ≠  Evidence Completeness
Evidence Completeness  ≠  Evidence Authenticity
Evidence Authenticity  ≠  Constitutional Compliance
Constitutional Compliance ≠ Election Validity
```

If CertificationAuthority produces one undifferentiated certification statement covering all five, the constitutional meaning of "certified" becomes indeterminate. ADR-6 must determine whether certification must be decomposed.

### A.3 Binding Inputs

From ADR-2 (CERT = Option C, External Organization):
- CertificationAuthority is an external organization, not an internal committee (highest constitutional independence)
- AC-27: external challenge reception required
- Terminal risk: certification is the last constitutional gate before an election result is formally accepted; error at certification is hardest to recover from

From ADR-3 (CertificationAuthority prohibition):
- CertificationAuthority may NOT certify Authenticity Stratum compliance without independently accessing the same AC-31 independent reference standard used for authenticity verification
- It cannot accept AuditExecutionAuthority's report of authenticity verification as constitutionally sufficient (R4 from ADR-3 revision cycle)
- This prohibition is binding on ADR-6

From ADR-4 (scope specification in certification):
- CertificationAuthority must verify that AuditScopeAuthority produced a valid Tier 2 specification for the election being certified
- CertificationAuthority must independently access AuditScopeAuthority's Tier 2 specification; it cannot rely solely on AuditExecutionAuthority's report of the scope used
- Certification without scope verification creates a Completeness Stratum gap

From ADR-5 (challenge interaction):
- CertificationAuthority has S-3 standing to challenge AuditScopeAuthority (if scope is constitutionally insufficient to support certification) and AuditExecutionAuthority (if audit findings are constitutionally insufficient)
- R-7 (Recertify) conditions: a prior certification issued on constitutionally invalid grounds must be followed by correction (R-3/R-4/R-6) before recertification is valid
- OQ-37-04-01: Tier 2 scope publication must be evidentially recoverable for certification purposes

### A.4 ADR3-INV-01 Certification Implication

ADR3-INV-01: no stratum may be satisfied by another. Applied to certification:
- CertificationAuthority certifying Completeness by confirming records are present (Presence → Completeness) violates the invariant
- CertificationAuthority certifying Authenticity by confirming records are complete (Completeness → Authenticity) violates the invariant
- Each stratum requires independent constitutional evaluation for certification

This means: if CertificationAuthority is to certify all three strata, it must evaluate each independently — not derive one from another.

---

## Part B — What Is CertificationAuthority Certifying?

### B.1 The Five Certification Object Candidates

Five possible objects of certification have been identified:

| Candidate | Definition | Constitutional Basis |
|---|---|---|
| **CO-1: Process Compliance** | The election process followed constitutionally mandated procedures | TF-36C-04-07: perfect evidence + correct procedures = constitutional electoral conduct |
| **CO-2: Evidence Completeness** | All constitutionally required evidence exists (Completeness Stratum) | ADR-3 Link 1/2/3; Gap A-3; ADR-4 AuditScopeAuthority Tier 2 |
| **CO-3: Evidence Authenticity** | Present records have not been modified (Authenticity Stratum) | ADR-3 AC-31; independent reference standard |
| **CO-4: Constitutional Compliance** | The election was conducted in accordance with ElectionConstitution | AC-30; TF-36C-04-07 |
| **CO-5: Election Validity** | The election outcome is constitutionally valid and should be given legal/constitutional effect | The most consequential certification; depends on CO-1 through CO-4 |

### B.2 Are These the Same or Different?

**CO-1 ≠ CO-2:** Process compliance concerns whether the right steps were taken; evidence completeness concerns whether all required records exist. An election can have complete records of a non-compliant process. An election can have compliant processes with incomplete records (records destroyed or never created).

**CO-2 ≠ CO-3:** Completeness concerns whether all required records exist; authenticity concerns whether the existing records are unmodified. An election can have all required records present but some modified (completeness satisfied, authenticity violated). An election can have authentic records of an incomplete set (authenticity satisfied for what exists, completeness violated).

**CO-3 ≠ CO-4:** Authenticity concerns the integrity of evidence records; constitutional compliance concerns whether the election followed constitutional rules. A constitutionally non-compliant election can produce authentic records — every step of a constitutionally invalid process can be faithfully recorded.

**CO-4 ≠ CO-5:** Constitutional compliance concerns whether the election followed ElectionConstitution; election validity concerns the constitutional effect of the outcome. An election may be constitutionally compliant in procedure but the outcome contested for other constitutional reasons.

**Implication for certification architecture:** These five objects are constitutionally distinct. Certification may address one, several, or all — but the certification statement must identify which objects are being certified. An undifferentiated "the election is certified" statement with no specification of what is being certified is constitutionally indeterminate.

### B.3 Minimum Certification Set

What is the constitutional minimum for CertificationAuthority to produce a valid certification?

From the program's constitutional findings:
- TF-36C-04-07 (CANDIDATE PROGRAM-LEVEL from 36C-04): perfect evidence + manipulated governance rules = constitutional fraud. This finding implies that CO-4 (constitutional compliance) is a minimum certification object — certifying CO-2/CO-3 without CO-4 allows certifying a constitutionally invalid election with complete and authentic records of its conduct
- ADR-3: CO-3 (authenticity) requires independent evaluation (AC-31); CertificationAuthority cannot omit this
- ADR-4: CO-2 (completeness) requires scope verification; CertificationAuthority cannot omit this without creating a Gap A-3 recurrence at the certification level

**Minimum certification set: CO-2 + CO-3 + CO-4** (Completeness + Authenticity + Constitutional Compliance). CO-1 and CO-5 may follow from these, but CO-2/CO-3/CO-4 are independently necessary.

**CO-5 (Election Validity):** This is the terminal certification object. It depends on CO-1 through CO-4. CertificationAuthority may certify CO-5 only when CO-2, CO-3, and CO-4 have been individually satisfied. CO-5 is a DERIVED certification object — derived from the others, not a substitute for them.

### B.4 CO-4 Scope Boundary (OBS-ADR6-01)

**OBS-ADR6-01:** CO-4 is not a substitute for CO-2 or CO-3 and must not be used as one.

CO-4 evaluates compliance with constitutional rules — whether the election was conducted in accordance with ElectionConstitution. It does NOT re-evaluate evidence completeness (CO-2) or evidence authenticity (CO-3).

This distinction is constitutional, not merely organizational:
- CO-2 answers: does the required evidence exist?
- CO-3 answers: is the existing evidence unmodified?
- CO-4 answers: were the constitutional rules for the election followed?

Finding that the process was constitutionally compliant (CO-4) does not confirm that all required records exist (CO-2). Finding that all required records are present does not confirm that the constitutional rules were followed (CO-4). These evaluations are independent and non-substitutable. Future architecture must not treat CO-4 as a "catch-all" certification object covering the absence of CO-2 or CO-3 satisfaction.

### B.5 CO-5 Invariant (ADR6-INV-01)

**ADR6-INV-01 (CO-5 Dependency Invariant):**

CO-5 (Election Validity) may never be granted unless:
- CO-2 (Evidence Completeness) = independently satisfied
- CO-3 (Evidence Authenticity) = independently satisfied
- CO-4 (Constitutional Compliance) = independently satisfied

Failure of any one of CO-2, CO-3, or CO-4 automatically invalidates CO-5.

CO-5 certification issued while any of CO-2, CO-3, or CO-4 is unsatisfied is constitutionally void regardless of other conditions. This invariant cannot be waived by CertificationAuthority, by ChallengeAdjudicationBody, or by any authority; it can only be modified by constitutional amendment through ElectionConstitution.

---

## Part C — Independent vs. Delegated Evaluation

### C.1 The Core Question

Must CertificationAuthority independently evaluate the three evidence strata (Completeness, Presence, Authenticity), or may it rely on AuditExecutionAuthority's findings and certify based on those findings?

This question determines whether CertificationAuthority is:
- **Option A (Reliance):** A constitutional acceptance body that receives audit findings and certifies based on them — relying on AuditExecutionAuthority's evaluation of the three strata
- **Option B (Independent Evaluation):** A constitutional verification body that independently evaluates the three strata — performing its own assessment separate from AuditExecutionAuthority
- **Option C (Hybrid):** CertificationAuthority relies on audit findings for some strata and independently evaluates others — stratified reliance based on constitutional risk

### C.2 The ADR-3 Prohibition as a Binding Constraint

ADR-3 R4 established an explicit prohibition: CertificationAuthority may NOT certify Authenticity Stratum compliance without independently accessing the AC-31 reference standard. This prohibition directly answers the question for the Authenticity Stratum: **pure reliance on AuditExecutionAuthority is constitutionally prohibited for CO-3 (Authenticity).**

The reasoning: if CertificationAuthority accepts AuditExecutionAuthority's authenticity finding without independently accessing the reference standard, the constitutional advance made by AC-31 is reversed — the trust chain terminates in AuditExecutionAuthority's own report, recreating the self-verification risk that AC-31 was designed to eliminate.

### C.3 Extended Prohibition Analysis

Does the ADR-3 prohibition extend beyond the Authenticity Stratum to Completeness (CO-2) and Process Compliance (CO-1)?

**For CO-2 (Completeness):** ADR-4 established that CertificationAuthority must independently access AuditScopeAuthority's Tier 2 specification — it cannot rely solely on AuditExecutionAuthority's report of the scope used. This is the same prohibition pattern applied to the Completeness Stratum: CertificationAuthority must independently access the scope definition used for completeness assessment.

**For CO-1 (Process Compliance):** No explicit prohibition established yet. CertificationAuthority may accept AuditExecutionAuthority's process compliance findings with appropriate constitutional safeguards (challenge resolution, documented audit trail). Pure reliance on CO-1 is not yet constitutionally prohibited.

**For CO-4 (Constitutional Compliance):** CertificationAuthority assessing constitutional compliance requires access to ElectionConstitution and the election record. This is an inherently independent assessment — it cannot be delegated to AuditExecutionAuthority, whose mandate is evidence integrity (ADR-3), not constitutional interpretation.

### C.4 Selection — Option C (Hybrid): Stratum-Differentiated Reliance

**Selected:** Option C (Hybrid) — the appropriate reliance model is differentiated by stratum and certification object.

| Certification Object | CertificationAuthority Access Requirement | Reliance Permitted? |
|---|---|---|
| CO-2 (Evidence Completeness) | Must independently access AuditScopeAuthority's Tier 2 specification (ADR-4); must verify scope specification was validly published (OQ-37-04-01) | NO for scope source; audit execution findings usable as input but not dispositive |
| CO-3 (Evidence Authenticity) | Must independently access AC-31 independent reference standard (ADR-3 prohibition) | NO — reliance on AuditExecutionAuthority's authenticity finding is constitutionally prohibited |
| CO-4 (Constitutional Compliance) | Must access ElectionConstitution; must evaluate compliance with Tier 1 evidence categories and constitutional governance requirements | NO — constitutional compliance is inherently an independent assessment |
| CO-1 (Process Compliance) | May receive AuditExecutionAuthority's process findings as primary input | YES — with constitutional safeguards; no ADR-established prohibition |
| CO-5 (Election Validity — derived) | Derived from CO-2 + CO-3 + CO-4 being satisfied | DERIVED — follows from independent evaluation of CO-2/CO-3/CO-4 |

**Constitutional basis for C.4 selection:**
1. ADR-3 prohibition is binding (CO-3 — no reliance)
2. ADR-4 scope specification independence requirement is binding (CO-2 — no reliance on scope)
3. CO-4 is structurally inherent to CertificationAuthority's constitutional mandate — if CertificationAuthority cannot independently assess constitutional compliance, it cannot be the terminal constitutional gate
4. CO-1 has no established prohibition; pure reliance would be constitutionally permissible with appropriate transparency

---

## Part D — Certification Under Active Challenge

### D.1 The Certification-Challenge Interaction

ADR-5 designed a challenge architecture with remedies up to R-7 (Recertify) and R-8 (Rerun). ADR-6 must answer: can CertificationAuthority certify an election that is still under active challenge?

This is not a procedural question — it is a constitutional question about whether certification has constitutional meaning while challenges are pending.

### D.2 Options

**Option A — No Certification Under Challenge:** CertificationAuthority may not certify while any challenge is pending. Certification only when all challenges are resolved.

**Option B — Certification Notwithstanding Challenge:** CertificationAuthority may certify at any time; pending challenges do not prevent certification; challenges pursue their own resolution channel.

**Option C — Challenge-Conditional Certification:** CertificationAuthority may certify but must disclose pending challenges in the certification statement; the certification is provisional until challenge resolution.

**Option D — Tiered Challenge Response:** CertificationAuthority may certify when challenges that could invalidate the certification have been resolved; challenges that affect lower-tier objects (CO-1) but not CO-5 do not prevent certification.

### D.3 Option Analysis

**Option A (No Certification Under Challenge):** Strong constitutional protection but creates a constitutional vulnerability: any party can delay certification indefinitely by filing challenges. This is a Denial of Certification threat (identified in the post-ADR-7 Round 38A threat list). Constitutional governance cannot be held hostage to challenge timing.

**Option B (Certification Notwithstanding Challenge):** Weakest protection. Certification proceeds regardless of challenge status. If a challenge later succeeds and finds a substantial defect, the certification has already been issued — uncertification requires additional process. The terminal risk for CertificationAuthority (ADR-2) is highest here.

**Option C (Provisional Certification):** A middle position but constitutionally murky. What does "provisional certification" mean? If the election outcome can be acted upon, provisional certification is effectively real certification. If it cannot be acted upon, it is effectively no certification. "Provisional" may obscure more than it reveals.

**Option D (Tiered Challenge Response):** Distinguishes between challenges that, if successful, would require R-7 (Recertify) or R-8 (Rerun), and challenges that, if successful, would require only R-3/R-4 (Correct). CertificationAuthority may certify when no material challenge is pending — where "material" means a challenge whose successful outcome would affect CO-2, CO-3, CO-4, or CO-5.

### D.4 Selection — Option D (Tiered Challenge Response) with Materiality Threshold

**Selected:** Option D — certification may proceed when no material challenge is pending; materiality is determined by whether the challenge's potential remedy would affect CO-2, CO-3, CO-4, or CO-5.

**Materiality assessment:**

| Challenge Subject | Potential Remedy | Material to Certification? |
|---|---|---|
| EnrollmentAuthority decision (one voter) | R-3 (Correct) | Not material to CO-5 unless the enrollment error affects the outcome |
| CriteriaAuthority criteria interpretation | R-3/R-4 (Correct/Override) | Material if criteria affect candidate eligibility |
| AuditScopeAuthority scope specification | R-3/R-4 (Correct) or R-5 (Suspend) | MATERIAL — CO-2 depends on scope; scope challenge directly affects completeness assessment |
| AuditExecutionAuthority audit findings | R-3/R-4 or R-6 (Re-audit) | MATERIAL — CO-2/CO-3 depend on audit findings |
| GovernanceAuthority authorization | R-3/R-4/R-5 | Potentially material if authorization affects election conduct |
| CertificationAuthority prior decision | R-7 (Recertify) | MATERIAL — directly affects certification |

**Constitutional basis:**
1. Prevents Denial of Certification attacks (Option A vulnerability)
2. Preserves constitutional protection for CO-2/CO-3/CO-4 (Option B weakness avoided)
3. Clearer than provisional certification (Option C ambiguity avoided)
4. Preserves ChallengeAdjudicationBody's authority to resolve material challenges before certification is final

**Certification with disclosure:** When CertificationAuthority certifies, it must disclose all pending non-material challenges in the certification record. This preserves constitutional transparency without allowing non-material challenges to block certification.

---

## Part E — Challenge Outcomes and Certification Effects

### E.1 Challenge Outcomes That Invalidate Prior Certification

A prior certification is constitutionally invalidated when ChallengeAdjudicationBody finds:
- CO-2 (Completeness) was not satisfied when certification was issued: the audit scope was constitutionally invalid and CertificationAuthority's CO-2 assessment was therefore wrong
- CO-3 (Authenticity) was not satisfied: records that CertificationAuthority accepted as authentic were subsequently shown to be modified
- CO-4 (Constitutional Compliance) was not satisfied: a governance decision that CertificationAuthority treated as constitutionally valid is found to be unconstitutional

**Effect of invalidation:** Prior certification loses constitutional force. Correction under R-3/R-4/R-6 must be completed before R-7 (Recertify) can be issued. Certification does not self-reinstate — a new certification is required.

### E.2 Challenge Outcomes That Require Recertification

Recertification (R-7) is required when:
1. A material challenge succeeds (invalidating CO-2, CO-3, or CO-4)
2. The underlying defect is corrected (R-3, R-4, or R-6 executed)
3. CertificationAuthority performs a new independent evaluation under the corrected conditions

**R-7 sequencing (from ADR-5 ADR5-INV-01 pattern applied to certification):** Recertification without correction is constitutionally void — it recertifies the same defect. The sequence must be: challenge succeeds → correction ordered → correction executed → new certification performed.

### E.3 Constitutional Conditions for Refusing Certification

CertificationAuthority must refuse certification when:
1. **CO-2 not satisfied:** AuditScopeAuthority Tier 2 specification was not validly produced, or the Completeness assessment by AuditExecutionAuthority found missing required evidence
2. **CO-3 not satisfied:** Authenticity Stratum assessment, using the AC-31 independent reference standard, found that required records are modified or unverifiable
3. **CO-4 not satisfied:** CertificationAuthority's independent constitutional compliance assessment finds that the election was conducted in violation of ElectionConstitution
4. **Material challenge pending:** A challenge is pending whose potential remedy (if successful) would affect CO-2, CO-3, CO-4, or CO-5
5. **AuditScopeAuthority Tier 2 specification not evidentially recoverable:** CertificationAuthority cannot access or verify the scope specification used for the audit (OQ-37-04-01 — certification requires verifiable scope record)

**CertificationAuthority must document refusal reasons.** A refusal without documented constitutional basis is not a constitutionally grounded refusal — it is an exercise of ungrounded authority that could itself be challenged (S-3 standing by any party affected by the uncertified election outcome).

---

## Part F — Terminal Certification State

### F.1 The Terminal Certification Question

What is the terminal certification state for an election cycle? When is certification constitutionally final?

Three candidate terminal states:
- **TS-1: Certification issued, no pending material challenges:** Terminal for the election cycle; the certified outcome takes constitutional effect
- **TS-2: ChallengeAdjudicationBody confirms certification:** An additional constitutional confirmation by the adjudication body that the certification was valid; terminal only after this confirmation
- **TS-3: Constitutional review confirms certification:** The terminal state is ElectionConstitution's own review confirming the certification; the most final but the most process-heavy

### F.2 Option Analysis

**TS-1 (Certification Issued, No Pending Material Challenges):** CertificationAuthority issues certification; after the challenge window closes without a material successful challenge, the certification is terminal. This is the most operationally practical terminal state.

**TS-2 (ChallengeAdjudicationBody Confirmation):** Adds a second constitutional confirmation layer. However, this requires ChallengeAdjudicationBody to affirmatively confirm each certification — converting it from an adjudicative body to an approval body. This conflicts with ChallengeAdjudicationBody's mandate (adjudication of challenges, not approval of certifications).

**TS-3 (Constitutional Review):** Constitutional review is the terminal point for challenges to adjudication decisions (ADR-5 Terminal Authority Principle). Applying it to certification would require constitutional review of every election certification. Operationally impractical as a routine terminal state; appropriate only for fundamental constitutional disputes.

### F.3 Selection — TS-1 with Challenge Window

**Selected:** TS-1 — certification is terminal for the election cycle when: (a) CertificationAuthority issues the certification, (b) no material challenge is pending at the time of issuance, and (c) the constitutional challenge window for certification challenges has closed without a successful material challenge.

**Challenge window:** The constitutional challenge window for challenging a certification decision is a constitutionally defined period after certification issuance. ADR-5 established that CertificationAuthority decisions may be challenged (S-1/S-2/S-3 standing). After the challenge window closes, the certification cannot be challenged within the election cycle. Post-cycle constitutional review remains available (ElectionConstitution review process).

**ADR6-CONSTRAINT-01 (Challenge Window Constitutional Requirements):**

The challenge window must satisfy four constitutional properties:
1. **Non-zero:** The window must allow meaningful time for challenge preparation and filing. A zero-length or near-zero window negates challenge rights.
2. **Finite:** The window must terminate. An indefinite window prevents electoral finality and creates a constitutional denial of validity.
3. **Constitutionally published:** The window duration must be established in ElectionConstitution or a constitutionally designated instrument — not set by CertificationAuthority's discretion at the time of each election.
4. **Known before election start:** The window duration must be knowable to all stakeholders before the election begins. A post-election window definition is constitutionally suspect (it allows the window to be set after the outcome is known).

These four properties together prevent challenge window duration from becoming a governance lever. The specific duration is a Round 38+ governance design question; these constitutional bounds are established here.

**Constitutional basis:**
1. Prevents indefinite certification uncertainty (an election must be able to produce a final constitutional result)
2. Preserves challenge rights within the window (constitutional protection during the window period)
3. Does not require ChallengeAdjudicationBody to approve certifications (preserving its adjudicative mandate)
4. Post-cycle constitutional review remains available for fundamental disputes (consistent with Terminal Authority Principle from ADR-5)

---

## Part G — CertificationAuthority Constitutional Profile (Updated from ADR-2)

### G.1 Updated Mandate

**Function (from ADR-2 CERT Option C):** Holds the constitutional mandate to assess and certify that an election satisfies CO-2 (Completeness), CO-3 (Authenticity), CO-4 (Constitutional Compliance), and CO-1 (Process Compliance) — and on that basis to certify CO-5 (Election Validity).

**ADR-6 additions to ADR-2 profile:**
- Stratified evaluation mandate: CertificationAuthority evaluates CO-2/CO-3/CO-4 independently; CO-1 by reliance with safeguards; CO-5 derivatively
- Independent access mandates: AC-31 independent reference standard (CO-3), AuditScopeAuthority Tier 2 specification (CO-2), ElectionConstitution (CO-4)
- Materiality assessment: determines whether pending challenges are material before certifying
- Refusal mandate: must refuse when constitutional conditions for refusal exist (Part E.3)
- Challenge window: maintains a constitutionally defined challenge window post-issuance

### G.2 Independence Form (R2 Discipline — Inherited from ADR-5)

ADR-2 selected Option C (External Organization) for CertificationAuthority. ADR-5 established that independence ≠ externality for ChallengeAdjudicationBody. ADR-6 does NOT revisit ADR-2's Option C selection for CertificationAuthority — that decision was made on the basis of "terminal risk" (ADR-2) and "highest constitutional independence requirement for certification." The R2 discipline applies to NEW authority selections; it does not retroactively weaken ADR-2's established CertificationAuthority independence form.

**CertificationAuthority remains Option C (External Organization).** ADR-5's externality-deference applies to ChallengeAdjudicationBody only.

### G.3 OBS-ADR3-01 Compliance

CertificationAuthority's legitimacy chain (L-1/L-5 from ADR-2 Option C) was established. OBS-ADR3-01 applies: the external organization form does not itself establish constitutional legitimacy — the designated L-1/L-5 chain (ElectionConstitution-grounded) does. No change to ADR-2 legitimacy profile.

### G.4 CertificationAuthority as Constitutional Concentration Point (OBS-ADR6-02)

**OBS-ADR6-02:** CertificationAuthority is a constitutional concentration point.

ADR-6 established that CertificationAuthority independently assesses CO-2, CO-3, and CO-4, and derives CO-5. This means:
- Compromise of CertificationAuthority's independence (e.g., capture by a partisan actor) can simultaneously invalidate CO-2 certification, CO-3 certification, CO-4 certification, and CO-5 certification
- Structural failure of CertificationAuthority (dissolution, loss of ElectionConstitution designation) eliminates the terminal constitutional gate for election validity
- CertificationAuthority's access to AC-31 and AuditScopeAuthority's Tier 2 specification creates dependencies that, if compromised, undermine the constitutional value of its certification

This concentration risk parallels the ElectionConstitution concentration risk identified in OBS-ADR5-02. ADR-7 must inherit OBS-ADR6-02 and assess it alongside ElectionConstitution's four-role concentration. The constitutional architecture now contains two confirmed concentration points — ElectionConstitution (constitutional source) and CertificationAuthority (constitutional terminal gate) — and their mutual dependency (CertificationAuthority reads ElectionConstitution for CO-4) creates a concentration chain.

---

## Part H — Cross-Concern Analysis

### H.1 ADR3-INV-01 Compliance

| Invariant | Certification Architecture Assessment |
|---|---|
| Completeness ← Presence | CertificationAuthority evaluates CO-2 (Completeness) by independently accessing AuditScopeAuthority's Tier 2 specification and verifying that AuditExecutionAuthority's completeness assessment used that specification. It does not derive "what must exist" from "what does exist." Compliant. |
| Presence ← Authenticity | CertificationAuthority evaluates CO-3 (Authenticity) by independently accessing the AC-31 reference standard. Confirming records are authentic (CO-3) is a separate evaluation from confirming records are present (CO-2). Compliant. |
| Authenticity ← Completeness | CO-4 (Constitutional Compliance) does not certify records as authentic; it certifies the election process as constitutional. Compliant. |

**ADR3-INV-01: SATISFIED in certification architecture.**

### H.2 The CertificationAuthority-AuditExecutionAuthority Relationship

The C.4 selection (hybrid reliance) raises a design question: if CertificationAuthority must independently access AC-31 and AuditScopeAuthority's specification, what role does AuditExecutionAuthority's report serve?

AuditExecutionAuthority's report serves as:
1. **Primary input for CO-1 (Process Compliance):** Reliance is constitutionally permitted here; the audit report is the evidence of process compliance
2. **Corroborating input for CO-2/CO-3:** CertificationAuthority's independent evaluations should be consistent with AuditExecutionAuthority's findings; material discrepancy is a constitutional red flag requiring resolution — not ignored, but not dispositive

**If CertificationAuthority's independent CO-3 evaluation contradicts AuditExecutionAuthority's CO-3 finding:** CertificationAuthority must withhold CO-3 certification and has S-3 standing (ADR-5) to challenge AuditExecutionAuthority's finding. The discrepancy must be resolved through the challenge architecture before CO-3 can be certified.

### H.3 OBS-ADR5-02 Interaction and Tally-Level Carry-Forward (OBS-ADR6-03)

OBS-ADR5-02 identified that ElectionConstitution now serves as L-1 source, revocation terminus, and challenge terminus. ADR-6 adds: CertificationAuthority's CO-4 assessment (Constitutional Compliance) requires reading ElectionConstitution directly. ElectionConstitution now also serves as the constitutional standard against which CO-4 is measured.

**OBS-ADR6-03:** CO-5 (Election Validity) certification as currently specified covers authority-level and vote-level concerns (consistent with ADR-5 Two-Tier architecture). However, CO-5 may in the future depend on tally-level verification capabilities that are outside the scope of ADR-6.

The following tally-level capabilities remain constitutionally undiscovered and outside ADR-6 scope:
- Aggregation verification (are votes correctly aggregated from individual vote records?)
- Decryption verification (are ballots correctly decrypted from encrypted form?)
- Mixing verification (are shuffled ballots correctly linked to original ballots?)
- Proof validation (are published cryptographic proofs of tally correctness valid?)

ADR-5 OBS-ADR5-04 identified tally-level challenges as a third constitutionally distinct category deferred to Round 38+. OBS-ADR6-03 records the corollary for certification: a complete CO-5 certification may require CertificationAuthority access to tally-level verification artifacts (proofs, mixing transcripts, decryption evidence) that do not yet have a constitutional home in the current architecture. Round 38+ cryptographic and tally-level design must assess whether CO-5 as currently specified is constitutionally complete or whether it requires a tally-level CO-6 certification object.

ADR-7 must assess four ElectionConstitution roles:
1. L-1 source for all authority aggregates
2. L-4 revocation terminus
3. Challenge terminus (Terminal Authority Principle — ADR-5)
4. Constitutional compliance standard (CO-4 — ADR-6)

### H.4 OQ-37-04-01 Resolution

OQ-37-04-01 asked: does AuditScopeAuthority's Tier 2 publication require its own evidence integrity? ADR-5 partially answered: a publication record is constitutionally necessary for challenge purposes. ADR-6 provides the fuller answer for certification purposes:

CertificationAuthority must be able to verify that the Tier 2 specification used in the audit was the valid, unmodified specification that AuditScopeAuthority published. This requires that AuditScopeAuthority's Tier 2 publication be:
1. Evidentially recoverable (ADR-5 partial answer — challenge needs it)
2. Authenticity-verifiable for certification purposes — CertificationAuthority must be able to confirm the specification has not been modified between publication and certification

**OQ-37-04-01 RESOLUTION:** AuditScopeAuthority's Tier 2 specification requires at minimum: (a) a recoverable publication record, and (b) some form of integrity verification enabling CertificationAuthority to confirm the specification's authenticity. Whether the full three-stratum model applies to the scope publication itself, or whether a simpler integrity mechanism suffices, is a Round 38+ design question. The constitutional requirement (recoverability + authenticity-verifiability) is established by ADR-6.

---

## Part I — Comparative Option Matrix

### I.1 Certification Object Options

| Object | Constitutionally Distinct from Others? | Independent Evaluation Required? | Minimum Certification Set? |
|---|---|---|---|
| CO-1 (Process Compliance) | Yes | No (reliance with safeguards) | Not minimum; informative |
| CO-2 (Evidence Completeness) | Yes | Yes (scope access, ADR-4) | **YES — minimum** |
| CO-3 (Evidence Authenticity) | Yes | Yes (AC-31 access, ADR-3 prohibition) | **YES — minimum** |
| CO-4 (Constitutional Compliance) | Yes | Yes (inherent; ElectionConstitution) | **YES — minimum** |
| CO-5 (Election Validity) | Derived | Derived from CO-2/CO-3/CO-4 | Terminal — derived |

### I.2 CertificationAuthority Evaluation Model Options

| Option | CO-2 | CO-3 | CO-4 | ADR-3 Prohibition | ADR-4 Requirement | Selected |
|---|---|---|---|---|---|---|
| A (Pure Reliance) | Relies on audit | Relies on audit | Relies on audit | VIOLATED | VIOLATED | REJECTED |
| B (Full Independence) | Independent | Independent | Independent | Satisfied | Satisfied | Not selected (over-broad; CO-1 reliance is constitutionally permissible) |
| C (Hybrid — stratum-differentiated) | Independent (scope) | Independent (AC-31) | Independent (EC) | Satisfied | Satisfied | **SELECTED** |

### I.3 Certification Under Challenge Options

| Option | Denial of Certification Risk | Material Challenge Protection | Constitutional Clarity | Selected |
|---|---|---|---|---|
| A (No certification under any challenge) | HIGH — any challenge blocks | Strongest | Clear but vulnerable | Not selected |
| B (Certification notwithstanding challenge) | Low | Weakest | Clear but insufficient | REJECTED |
| C (Provisional certification) | Low | Medium | Ambiguous — "provisional" undefined | Not selected |
| D (Tiered — materiality threshold) | Low | Strong for CO-2/CO-3/CO-4/CO-5 | Clear materiality definition | **SELECTED** |

### I.4 Terminal Certification State Options

| Terminal State | Operational Practicality | Constitutional Protection | Challenge Rights | Selected |
|---|---|---|---|---|
| TS-1 (Issued + challenge window closed) | Highest | Good (window protects) | Preserved within window | **SELECTED** |
| TS-2 (ChallengeAdjudicationBody confirmation) | Medium | Higher | Converts adjudicator to approver | Not selected |
| TS-3 (Constitutional review) | Lowest | Highest | Strongest | Not selected (routine cases) |

---

## Section — ARB Decision Block

**[APPROVED — Required Revisions Applied (2026-06-16)]**

### Revisions Applied

| Revision | Content | Location |
|---|---|---|
| R1 (OBS-ADR6-01) | CO-4 is not a substitute for CO-2 or CO-3; evaluates constitutional rules only; must not be used as catch-all | B.4 |
| R2 (ADR6-INV-01) | CO-5 may never be granted unless CO-2 + CO-3 + CO-4 all independently satisfied; any failure invalidates CO-5; non-waivable | B.5 |
| R3 (OBS-ADR6-02) | CertificationAuthority is a constitutional concentration point; compromise invalidates CO-2/CO-3/CO-4/CO-5 simultaneously; ADR-7 must inherit | G.4 |
| R4 (ADR6-CONSTRAINT-01) | Challenge window must be non-zero, finite, constitutionally published, and known before election start | F.3 |
| R5 (OBS-ADR6-03) | CO-5 may depend on future tally-level verification capabilities (aggregation/decryption/mixing/proof validation) not yet in scope | H.3 |

### Decisions Made in This ADR

1. **Five Certification Objects Distinguished:** CO-1 (Process Compliance), CO-2 (Evidence Completeness), CO-3 (Evidence Authenticity), CO-4 (Constitutional Compliance), CO-5 (Election Validity — derived). These are constitutionally distinct; certification must specify which objects are covered.

2. **Minimum Certification Set: CO-2 + CO-3 + CO-4.** CO-5 is the terminal derived certification. CO-1 is informative. The minimum set prevents certifying a constitutional fraud (TF-36C-04-07 pattern) by requiring constitutional compliance alongside evidence integrity.

3. **Evaluation Model → Option C (Hybrid, Stratum-Differentiated):** CO-3: independent AC-31 access (ADR-3 prohibition binding). CO-2: independent scope specification access (ADR-4 requirement binding). CO-4: independent ElectionConstitution assessment (inherent to constitutional compliance). CO-1: reliance with safeguards permitted.

4. **Certification Under Challenge → Option D (Tiered Materiality Threshold):** Material challenges (affecting CO-2/CO-3/CO-4/CO-5) block certification. Non-material challenges do not. Pending non-material challenges must be disclosed in certification record. Prevents Denial of Certification attacks while protecting constitutional substance.

5. **Refusal Conditions Established:** Five constitutional conditions requiring certification refusal (Part E.3). Refusal must be documented. Undocumented refusal is itself challengeable.

6. **Challenge Outcomes and Certification:** Challenges invalidating CO-2/CO-3/CO-4 invalidate prior certification. R-7 (Recertify) requires prior correction. Recertification without correction is constitutionally void.

7. **Terminal Certification State → TS-1:** Certification is terminal when issued without pending material challenge and the constitutional challenge window closes without a successful material challenge. Post-cycle constitutional review remains available.

8. **CertificationAuthority Independence Form Confirmed:** ADR-2 Option C (External Organization) is confirmed; ADR-5 R2 discipline (independence ≠ externality) applies to NEW authority decisions; it does not retroactively weaken ADR-2's established CERT selection.

9. **OQ-37-04-01 RESOLVED:** AuditScopeAuthority's Tier 2 specification requires (a) evidential recoverability and (b) authenticity-verifiability for certification purposes. Whether the full three-stratum model applies to scope publication itself = Round 38+ design question.

10. **ADR3-INV-01 Satisfied:** Each certification object evaluated independently; no stratum derived from another.

11. **OBS-ADR5-02 Extended:** ElectionConstitution now serves four roles: (1) L-1 source, (2) revocation terminus, (3) challenge terminus, (4) CO-4 constitutional compliance standard. ADR-7 must evaluate four-role concentration.

12. **OBS-ADR6-01 (R1):** CO-4 evaluates constitutional rule compliance only; it does not re-evaluate evidence completeness (CO-2) or evidence authenticity (CO-3); CO-4 is not a catch-all certification object.

13. **ADR6-INV-01 (R2):** CO-5 may never be granted unless CO-2 + CO-3 + CO-4 are all independently satisfied; failure of any one invalidates CO-5; this invariant is non-waivable.

14. **OBS-ADR6-02 (R3):** CertificationAuthority is a constitutional concentration point; its compromise can simultaneously invalidate CO-2/CO-3/CO-4/CO-5; the architecture contains two confirmed concentration points (ElectionConstitution + CertificationAuthority) with a mutual dependency chain; ADR-7 must evaluate.

15. **ADR6-CONSTRAINT-01 (R4):** The challenge window must be non-zero, finite, constitutionally published, and known before election start; window duration cannot be governance discretion.

16. **OBS-ADR6-03 (R5):** CO-5 may depend on future tally-level verification capabilities (aggregation, decryption, mixing, proof validation) that are outside ADR-6 scope; Round 38+ must assess whether a tally-level CO-6 certification object is constitutionally required.

### Open Questions Carried to ARB

**OQ-37-06-01: Must CO-2, CO-3, and CO-4 be certified separately (three certification statements) or may CertificationAuthority issue a unified certification that covers all three with individual attestations?**

The constitutional objects are distinct, but a unified certification statement with separate attestations for each object may be constitutionally sufficient. A single undifferentiated statement is constitutionally insufficient (it is indeterminate). Three separate statements are constitutionally clear but operationally complex. A unified statement with named attestations per object may satisfy both constitutional clarity and operational practicality.

**OQ-37-06-02: What is the constitutional challenge window duration?**

ADR-6 establishes that a challenge window exists post-certification, after which certification is terminal for the cycle. The duration of this window is constitutionally significant: too short fails to protect challenge rights; too long prevents electoral finality. The window duration is a governance design question (Round 38+) but the constitutional bounds (must be non-zero; must not be indefinite within the election cycle) are established here.

**OQ-37-06-03: Certification scope publication integrity — does CO-2 certification require CertificationAuthority to maintain its own record of the Tier 2 scope specification used?**

OQ-37-04-01 resolution establishes that Tier 2 must be recoverably published and authenticity-verifiable. Does CertificationAuthority's CO-2 certification create a constitutional obligation to record the Tier 2 specification it verified? This would create an independent record of the scope specification — useful for post-cycle review but not yet established as constitutionally required.

### Decisions Deferred to Subsequent ADRs

| Decision | Deferred to |
|---|---|
| Unified vs. separate CO-2/CO-3/CO-4 statements (OQ-37-06-01) | ADR-7 (governance design) or Round 38+ |
| Challenge window duration (OQ-37-06-02) | Round 38+ governance design |
| CO-2 certification scope record obligation (OQ-37-06-03) | Round 38+ |
| AuditScopeAuthority Tier 2 publication three-stratum treatment | Round 38+ |
| CertificationAuthority operational access pathway to AC-31 reference standard | Round 38+ |
| CertificationAuthority operational access pathway to AuditScopeAuthority Tier 2 | Round 38+ |
| ElectionConstitution four-role concentration impact | ADR-7 |
| ChallengeAdjudicationBody independence form (Option B vs C) | ADR-7 |
| OQ-37-05-01 (ChallengeReceptionFunction authority status) | ADR-7 |
| OQ-37-05-02 (R-5 Suspension succession) | ADR-7 |
| CO-4 constitutional compliance assessment methodology | Round 38+ |

### Authorization Requested

**ADR-7: GovernanceState Boundary Architecture — AUTHORIZED**

**ADR-7 Review Charter (from ARB, 2026-06-16):**

**Primary Question:** Is ElectionConstitution an unconstitutional or architecturally dangerous concentration point? Has the architecture created a "Constitution God Aggregate" — the exact concentration pattern DDD exists to prevent?

**Required Analysis:**

**Step 1 — ElectionConstitution Responsibility Inventory:**
Identify every responsibility currently assigned to ElectionConstitution. Classify each responsibility:
- Source (L-1 authority grant)
- Governance (constitutional rules + transition conditions)
- Compliance (CO-4 standard — ADR-6)
- Challenge (L-3 terminal point — ADR-5)
- Revocation (L-4 terminus — ADR-1/2)
- Certification (Tier 1 evidence categories — ADR-4)

**Step 2 — Delegation Assessment:**
Determine whether any responsibility currently concentrated in ElectionConstitution should be delegated to a constitutionally designated authority relationship. The criterion is not DDD elegance — it is constitutional risk: does concentration of this responsibility in ElectionConstitution create a failure mode that cannot be recovered from within an election cycle?

**Step 3 — GovernanceState Constitutional Profile:**
Determine whether GovernanceState:
- Carries constitutional authority (GovernanceState makes constitutional decisions)
- Records constitutional authority (GovernanceState stores what ElectionConstitution established)
- Derives constitutional authority (GovernanceState's authority derives from ElectionConstitution grants)

This distinction determines whether GovernanceState is an authority aggregate, a state machine aggregate, or a projection of constitutional grants. The answer has major implications for the authority map (OBS-36D-02-1).

**Step 4 — ChallengeAdjudicationBody Independence Form:**
Reevaluate whether Option B (internal independent body) or Option C (external organization) is constitutionally required for ChallengeAdjudicationBody. ADR-5 deferred this. ADR-7 must resolve it using the same independence ≠ externality discipline, but must also evaluate whether the Terminal Authority Principle (adjudication final within cycle) creates a constitutional requirement for externality beyond what ADR-2's CERT analysis established.

**Step 5 — Constitutional Dependency Graph (required output):**
Produce an explicit constitutional dependency graph:
```
ElectionConstitution
    ↓ L-1 grant
D43 Authorities (EnrollmentAuthority / CriteriaAuthority / AuditScopeAuthority / AuditExecutionAuthority / GovernanceAuthority / CertificationAuthority / ChallengeAdjudicationBody)
    ↓ challenge pathway
Challenge Architecture (ChallengeReceptionFunction → ChallengeAdjudicationBody)
    ↓ terminal certification
Certification Architecture (CO-2 + CO-3 + CO-4 → CO-5)
```

Identify every place a failure in ElectionConstitution propagates upward. Identify every place a failure in GovernanceState propagates.

**Step 6 — Constitutional Concentration Failure Analysis (required output):**
Produce a concentration failure analysis:
- Failure of ElectionConstitution → what collapses? (list every authority aggregate, every certification object, every challenge pathway affected)
- Failure of GovernanceState → what collapses?
- Failure of CertificationAuthority (OBS-ADR6-02) → what collapses?
- Can the architecture survive any single-point failure? Which ones? Which cannot be survived?

**Additional Required Questions:**
- ADR-5 OQ-37-05-01: ChallengeReceptionFunction authority status (separate aggregate or ChallengeAdjudicationBody sub-function)
- ADR-5 OQ-37-05-02: R-5 Suspension succession mechanism
- ADR-6 OQ-37-06-01: Unified vs. separate CO-2/CO-3/CO-4 certification statements
- OBS-ADR5-03: Standing class grant mechanism must not derive from challenged-authority discretion — how is this constitutionally enforced?

**Required Discipline:**
- Constitutional concentration risk is the primary question; GovernanceState implementation is secondary
- No new bounded contexts, services, APIs, or cryptographic selections
- OBS-36D-02-1 governs: authority map ≠ context map
- ADR8 Discipline: ADRs may only build on what ADR-1 through ADR-6 established; ADR-7 may not rewrite earlier decisions

ADR-7 is authorized. All predecessor ADRs: ADR-1/2/3 APPROVED; ADR-4 SUBMITTED; ADR-5 APPROVED (revisions applied); ADR-6 APPROVED (revisions applied).

---

*Round 37-06 — ADR-6: Certification Architecture — APPROVED — Required Revisions Applied (2026-06-16)*
*Research Program: NRNA DDD Trustworthiness*
*Document: Round37-06_ADR-6_Certification_Architecture.md*
*Predecessors: ADR-1, ADR-2, ADR-3 — APPROVED; ADR-4 — SUBMITTED; ADR-5 — APPROVED (Required Revisions Applied)*
*Successors authorized: ADR-7 — GovernanceState Boundary Architecture (Constitutional Concentration Risk)*
