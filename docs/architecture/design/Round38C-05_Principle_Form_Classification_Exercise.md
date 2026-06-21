# Round 38C-05 — Principle/Form Classification Exercise

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38C-05 — Classification Exercise
**Status:** SUBMITTED FOR ARB REVIEW
**Purpose:** Perform the first constitutional classification exercise under the approved 38C-04 framework (R1–R4 applied). Produce provisional classifications only. No final rulings.
**Date:** 2026-06-19

**Authority:** 38C-04 Classification Framework (Approved With Revisions R1–R4; ARB Review Outcome B 2026-06-19)

**Inputs:**
- 38C-03 ARB Ruling (Option C selected; 38C03-CON-01 through CON-04; 38C03-INV-01 through INV-02)
- 38C-04 Classification Framework (R1–R4 applied: C-11, Principle Anchor Requirement, Tier Assignment Criteria, 50/50 Rule)
- ADR-1 through ADR-7 (all APPROVED)
- 38B-01 through 38B-05 (all APPROVED)
- 38C-02 Evaluation (Part G — preliminary observations per candidate)
- round38C-03_deepseek.md (Part F — directional classification for ARB consideration)

**Governing invariants:**
- 38C01-INV-01: All outputs remain hypotheses until ARB acceptance
- 38C03-INV-01: No existing ADR invariant loses protection pending classification
- OBS-38B06-05 (PERMANENT): Classification completeness ≠ classification correctness

**CRITICAL PROHIBITION: This document performs no DDD design. No bounded contexts. No aggregates. No events. No architecture. No final rulings. Classifications are provisional candidates for ARB review and ruling.**

---

## Part A — Classification Target Register

### A.1 — Purpose of the Register

Per the ARB review of 38C-04, a formal Classification Target Register is required before the exercise begins. The register establishes what is being classified, what is deferred, and what is out of scope. Without the register, the exercise has no defined boundary and no completeness criterion.

### A.2 — Primary Candidates (38C-04 Appendix, Authorized for This Exercise)

The following 10 candidates are authorized by the 38C-04 Appendix and the 38C-03 CON-01 authorization. These are evaluated in full in Parts C through L.

| Code | Candidate | Source | Appendix Classification |
|------|-----------|--------|------------------------|
| CD-01 | ADR3-INV-01 (Evidence Strata Independence) | ADR-3 | Ambiguous in 38C-02 |
| CD-02 | ADR5-INV-01 (R-8 Terminality) | ADR-5 | Appears separable |
| CD-03 | ADR6-INV-01 (CO-5 Void Rule) | ADR-6 | Appears separable |
| CD-04 | ADR7-INV-01 (Succession Pre-Designation) | ADR-7 | Appears separable; C-9 mandatory |
| CD-05 | ADR7-INV-02 (Anti-Capture Invariant) | ADR-7 | **Conditional default PRINCIPLE-LEVEL** |
| CD-06 | 38B01-INV-01 (CIC interprets; CAB adjudicates) | 38B-01 | Appears separable |
| CD-07 | 38B04-INV-01 (Appointment Independence) | 38B-04 | Appears separable |
| CD-08 | 38B05-INV-01 (Three Amendment Tiers) | 38B-05 | **Genuinely ambiguous** |
| CD-09 | ADR-2 Per-Function Independence Forms | ADR-2 | C-7 primary candidate |
| CD-10 | Trust Root Structural Separation | OQ-38B05-05 | **Mandatory candidate; EscRule-04** |

### A.3 — Extended Candidate Set (Deferred to Future Exercises)

The ARB review of 38C-04 (DeepSeek variant, senior architect assessment) identified a broader candidate inventory including trust root elements, governance authority elements, and amendment tier elements that exceed the 38C-04 Appendix scope. These are registered here for completeness but are NOT evaluated in this exercise.

| Category | Elements | Deferral Reason |
|----------|----------|-----------------|
| Trust Root governance bodies | CIC (Legitimacy Root), Multi-Party Tiered (Authenticity), Multi-Party Corroboration (Temporal) | Classification of governance body architecture deferred pending OQ-38B05-05 ruling |
| Governance authorities | CA, CAB, GovernanceState record governance | Classification follows 38C-06 ruling on invariants that govern these |
| AC-31 governance model | Three-tier AC-31 governance (38B-02) | Classification follows Authenticity Root governance ruling |
| Specific tier thresholds | 90%/near-unanimity (Tier 3), 2/3 supermajority (Tier 2), deliberation windows | Classification tied to 38B05-INV-01 ruling (CD-08) |
| ADR-4/ADR-6 architecture specifics | Two-audit-aggregate structure, CO-N enumeration details | Classification follows CD-01/CD-03 principles |

These extended candidates will be addressed in 38C-05b (second classification exercise) or in the 38C-06 classification ruling, depending on ARB decision.

### A.4 — Out of Scope for This Exercise

The following are explicitly out of scope and will not be classified:
- Any technical architecture decision (cryptographic protocols, database schemas, API contracts)
- Any DDD design decision (bounded context definitions, aggregate boundaries, event schemas)
- OQ-38A05-02 (Finality vs. Validity) — PROTECTED; routes to CIC when case arises
- Any decision not present in the 38C-04 Appendix candidate register

---

## Part B — Framework Reference (R1–R4 Applied)

### B.1 — Revisions Applied to This Exercise

The following revisions from the 38C-04 ARB Review are applied throughout:

**R1 — C-11 (Misclassification Impact Test):** Applied to every candidate as a tiebreaker for Ambiguous designations. Evaluates asymmetric constitutional cost of False Form vs. False Principle. Applied AFTER C-1 through C-10.

**R2 — Principle Anchor Requirement:** Every Form designation identifies its governing Principle. The governing Principle must be either (a) classified Principle-level in this exercise, (b) grounded in existing EC provisions, or (c) designated a pending Principle candidate with explicit provisionality note.

**R3 — Tier Assignment Criteria:** Applied to every Principle designation. Three tiers evaluated:
- Tier 3: Foundational — removal enables Constitutional Self-Destruction (OBS-38A06-SD1); protects challenge rights, trust root existence, CIC existence, amendment process itself, MA ratification requirement
- Tier 2: Governance — qualified majority; authority appointment rules, governance procedures with constitutional significance
- Tier 1: Operational — simple majority; parameters within a governance structure requiring constitutional anchoring but not supermajority

**R4 — 50/50 Decision Rule:** Equal split of determinate criteria → defaults to Ambiguous. Classifier discretion may not resolve constitutional tie.

### B.2 — Separability Finding Protocol

Several candidates contain both a constitutional principle component and an architectural form component. When the exercise identifies separability, it produces:
- A named Principle-component with its own classification
- A named Form-component with its governing Principle identified
- Both components carry their own confidence levels and evidence

Separability findings are not the same as Ambiguous designations. A separable element is one that has been successfully classified at the level of its components.

---

## Part C — Candidate Analysis: CD-01 (ADR3-INV-01)

### C.1 — Candidate Description

ADR3-INV-01: "No future architecture may satisfy Completeness by Presence, Presence by Authenticity, or Authenticity by Completeness." Established in ADR-3 (Evidence and Verifier Architecture). The three strata — Completeness, Presence, Authenticity — are each constitutionally independent; no stratum may be satisfied by demonstrating another.

### C.2 — Constitutional Purpose

ADR3-INV-01 ensures that election certification (CO-5) requires independently satisfied evidence across three dimensions. Without it, a CertificationAuthority could issue valid CO-5 by satisfying only the easiest stratum (e.g., Presence) and treating that as sufficient for all three. This directly enables the TM-47 (Certification Chain Self-Reference) and TM-19 (AC-31 Capture) attack paths.

### C.3 — Criteria Evaluation

| Criterion | Answer | Direction | Basis |
|-----------|--------|-----------|-------|
| C.1 Constitutional Change | YES — removing non-substitution changes what certification requires | Principle | CO-5 can be issued without authentic evidence |
| C.2 Realization Plurality | SEPARABLE — non-substitution rule = no alternative; three-stratum model = alternatives exist | Separable | Different stratum names/boundaries possible; rule itself is irreducible |
| C.3 Challenge Rights | YES — stratum collapse merges CO-2/CO-3/CO-4; independent challenge impossible | Principle | S-1/S-2/S-3 challenge granularity lost |
| C.4 Constitutional Failure | YES — CO-5 issued on unauthenticated evidence → ADR6-INV-01 chain violated | Principle | Constitutional fraud in certification |
| C.5 Trust Root Integrity | YES — Authenticity Root (AC-31) bypassed if Authenticity satisfied by Presence | Principle | AC-31 loses constitutional function |
| C.6 Anti-Capture | YES — captured CA can certify via easiest stratum without satisfying harder ones | Principle | TM-47 pathway enabled |
| C.7 Independence Existence vs. Form (MANDATORY) | SEPARABLE — non-substitution EXISTENCE is constitutional; specific stratum model is form | Separable | Multiple valid three-dimensional evidence models exist |
| C.8 Constitutional Source | YES — 36B-CFI-01, AC-02, ADR6-INV-01, TM-47, TM-19 | HIGH confidence | Multiple direct constitutional sources |
| C.9 Governance Survivability (MANDATORY) | MEDIUM — not succession/deadlock directly; affects certification governance integrity | Neutral/Principle | Certification governance depends on stratum independence |
| C.10 OQ-38A05-02 Non-Interference (MANDATORY) | NO — stratum independence defines valid CO-5; does NOT resolve whether TS-1 can be reopened if CO-3 was false post-issuance | OQ-38A05-02 PROXIMITY documented | C-10 does NOT block |
| C.11 Misclassification Impact | False Form cost DOMINANT — CO-5 issuable without authentication; TM-47 ratchet constitutionally invisible; certification self-reference enabled | Toward Principle | False Principle: manageable ossification of stratum model |

**Decision Rule:** Strong majority → Principle (for non-substitution core). Separability identified.

### C.4 — Separability Finding

**CD-01-P: Non-Substitution Principle** — The constitutional requirement that evidence for each of the three certification dimensions (completeness, presence, authenticity) must be independently obtained and cannot substitute for evidence of another dimension.

**CD-01-F: Three-Stratum Evidence Model** — The specific architectural realization of the Non-Substitution Principle as three named strata (Completeness/Presence/Authenticity) with defined evidence boundaries.

### C.5 — Classification Result

| Component | Classification | Confidence |
|-----------|---------------|------------|
| CD-01-P: Non-Substitution Principle | **PRINCIPLE** | HIGH |
| CD-01-F: Three-Stratum Evidence Model | **FORM** | HIGH |

Governing Principle for CD-01-F: CD-01-P (Non-Substitution Principle). Principle Anchor satisfied — CD-01-P is classified Principle-level in this exercise.

### C.6 — Tier Evaluation (CD-01-P: Principle)

| Tier Criterion | Assessment |
|----------------|-----------|
| Tier 3 (OBS-38A06-SD1 threshold) | Not met — removing non-substitution does not directly enable Constitutional Self-Destruction; it enables certification fraud but EC itself survives |
| Tier 2 (Governance; qualified majority) | **MET** — non-substitution governs how certification authority operates; constitutional change to certification rules requires deliberation and supermajority |
| Tier 1 (Operational) | Not appropriate — this is not a parameter; it is a constitutional commitment |

**Candidate Tier: Tier 2**

### C.7 — Trust Root Impact

| Trust Root | Impact | Nature |
|-----------|--------|--------|
| TR-01 Legitimacy | LOW | Non-substitution is not about EC interpretation or amendment |
| TR-02 Authenticity (AC-31) | HIGH | Authenticity stratum is the constitutional expression of AC-31; if Authenticity can be satisfied by Presence, AC-31 is bypassed entirely |
| TR-03 Temporal | LOW | Not directly related to phase record governance |
| TR-04 Simultaneous | MEDIUM | AC-31 bypass can occur simultaneously with GovernanceState corruption if both roots are weakened |
| TR-05 Separation | YES — contribution to separation | Non-substitution maintains Authenticity Root's distinct function; its removal weakens separation |

### C.8 — TM Impact

| Threat | Impact |
|--------|--------|
| TM-19 (AC-31 Capture) | DIRECT — if Authenticity satisfied by Presence, TM-19 capture is constitutionally invisible |
| TM-39 (Independence Illusion) | YES — stratum collapse creates exactly the independence illusion TM-39 attacks |
| TM-42 (Operational Deadlock) | NONE |
| TM-44 (Temporal Concentration) | NONE |
| TM-47 (Certification Self-Reference) | DIRECT — enabling mechanism; stratum collapse allows self-referential certification chain |

### C.9 — OQ Impact

| OQ | Impact |
|----|--------|
| OQ-38B05-05 | POSITIVE CONTRIBUTION — non-substitution maintains distinct Authenticity Root function; contributes to but does not resolve trust root separation |
| OQ-38A05-02 | PROXIMITY — non-substitution defines valid CO-5; does NOT resolve whether TS-1 survives post-issuance discovery of CO-3 invalidity. OQ-38A05-02 REMAINS PROTECTED. |

### C.10 — Escalation Check

| EscRule | Triggered? | Basis |
|---------|-----------|-------|
| EscRule-01 | NO | Not Ambiguous; Authenticity Root impact documented but not contested |
| EscRule-02 | NO | Not Ambiguous; anti-capture impact documented |
| EscRule-03 | NO | C-10 does not block; OQ-38A05-02 proximity documented |
| EscRule-04 | OBSERVE | CD-01-P contributes to trust root separation (TR-05); flagged as OQ-38B05-05 contributing element but not a primary candidate |
| EscRule-05 | NO | Principle/Form boundary is clear: non-substitution rule vs. stratum model |

---

## Part D — Candidate Analysis: CD-02 (ADR5-INV-01)

### D.1 — Candidate Description

ADR5-INV-01: "R-8 (Rerun) is constitutionally terminal; terminal remedies cannot be further challenged within the challenge process." Established in ADR-5 (Challenge Architecture). R-8 is the highest-consequence remedy in the eight-tier taxonomy; it terminates the challenge process for the current election cycle.

### D.2 — Constitutional Purpose

ADR5-INV-01 ensures the challenge process terminates at a constitutional boundary. Without terminality, infinite recursive challenges would produce TM-42 (Operational Deadlock — unconditional F). The terminal remedy is what makes the challenge architecture constitutional rather than a process that can be used to permanently block governance.

### D.3 — Criteria Evaluation

| Criterion | Answer | Direction | Basis |
|-----------|--------|-----------|-------|
| C.1 Constitutional Change | YES — removing terminality changes what challenge rights mean; infinite challenge = no governance | Principle | TM-42 Complete Deadlock (unconditional F) |
| C.2 Realization Plurality | SEPARABLE — terminality existence = no alternative; R-8 as specific terminal remedy = alternatives exist (e.g., R-7 "null result" as terminal) | Separable | Other terminal remedies constitutionally adequate |
| C.3 Challenge Rights | YES — terminality defines the constitutional outer boundary of challenge rights | Principle | Without termination, challenge rights become infinite loop |
| C.4 Constitutional Failure | YES — no terminality → TM-42 Complete Deadlock (unconditional FAIL) | Principle | Direct FAIL-class consequence |
| C.5 Trust Root Integrity | LOW | Neutral | Not directly trust root dependent |
| C.6 Anti-Capture | LOW | Neutral | Terminality is not primarily an anti-capture mechanism |
| C.7 Independence Existence vs. Form (MANDATORY) | SEPARABLE — challenge terminality EXISTENCE = constitutional; R-8 as specific form = architectural | Separable | Multiple terminal remedy forms constitutionally possible |
| C.8 Constitutional Source | YES — TM-42 (unconditional F), 38B-05 Protected Core (challenge rights — Tier 3), ADR5-INV-01 | HIGH confidence | Direct threat finding and Tier 3 protection |
| C.9 Governance Survivability (MANDATORY) | HIGH — without terminality, governance cannot achieve final states; constitutional continuity requires finality | Principle | Governance survivability depends on ability to reach terminal states |
| C.10 OQ-38A05-02 Non-Interference (MANDATORY) | **PROXIMITY ALERT** — terminality of challenge process is adjacent to finality of TS-1 (OQ-38A05-02). ASSESSMENT: terminality defines when the challenge PROCESS ends; OQ-38A05-02 asks what happens when TS-1 is discovered invalid after the process ends. These are distinct questions. C-10 does NOT block. LANGUAGE REQUIRED: challenge process terminality ≠ post-finality constitutional review. OQ-38A05-02 REMAINS PROTECTED. | Does NOT block | Distinct questions; explicit language required |
| C.11 Misclassification Impact | False Form (terminality architectural) cost DOMINANT → TM-42 unconditional FAIL; infinite challenge enables governance deadlock. False Principle (R-8 form constitutional): manageable — specific remedy form ossified; alternative terminal remedies require EC amendment | Toward Principle | False Form cost catastrophic |

### D.4 — Separability Finding

**CD-02-P: Challenge Terminality Principle** — The constitutional requirement that the challenge process must have a terminal state beyond which further challenges within the same election cycle cannot be filed.

**CD-02-F: R-8 (Rerun) as Terminal Remedy** — The specific architectural selection of Rerun as the highest-consequence, challenge-terminating remedy in the eight-tier taxonomy.

### D.5 — Classification Result

| Component | Classification | Confidence |
|-----------|---------------|------------|
| CD-02-P: Challenge Terminality Principle | **PRINCIPLE** | HIGH |
| CD-02-F: R-8 as Terminal Remedy | **FORM** | MEDIUM |

Governing Principle for CD-02-F: CD-02-P (Challenge Terminality Principle). Principle Anchor satisfied.

**OQ-38A05-02 Explicit Language:** The Challenge Terminality Principle governs when the challenge process ends within the election cycle. It does not address what happens when a basis for challenging CO-5 validity emerges after TS-1 issuance. OQ-38A05-02 (Finality vs. Validity) REMAINS PROTECTED and must be adjudicated by CIC when a case arises.

### D.6 — Tier Evaluation (CD-02-P: Principle)

| Tier Criterion | Assessment |
|----------------|-----------|
| Tier 3 | CANDIDATE — challenge rights (Tier 3 item 2 in Protected Core). Challenge terminality is part of challenge rights architecture. Whether terminality itself is Tier 3 or Tier 2 requires CIC interpretation of whether removing terminality "removes constitutional challenge rights" (Tier 3) or merely "changes challenge procedures" (Tier 2) |
| Tier 2 | **PRIMARY CANDIDATE** — governance rule governing challenge process; qualified majority appropriate for most cases |
| Tier 1 | Not appropriate |

**Candidate Tier: Tier 2 (Tier 3 candidate; refer to CIC if contested)**

### D.7 — Trust Root Impact

| Trust Root | Impact |
|-----------|--------|
| TR-01 Legitimacy | LOW |
| TR-02 Authenticity | LOW |
| TR-03 Temporal | MEDIUM — challenge windows are temporal elements; temporal concentration (TM-44) is related |
| TR-04 Simultaneous | LOW |
| TR-05 Separation | LOW |

### D.8 — TM Impact

| Threat | Impact |
|--------|--------|
| TM-19 | LOW |
| TM-39 | LOW |
| TM-42 (Operational Deadlock) | DIRECT — terminality absence = TM-42 Complete Deadlock (unconditional F) |
| TM-44 (Temporal Concentration) | MEDIUM — challenge window duration related |
| TM-47 | LOW |

### D.9 — OQ Impact

| OQ | Impact |
|----|--------|
| OQ-38B05-05 | LOW |
| OQ-38A05-02 | PROXIMITY — does not resolve; OQ-38A05-02 REMAINS PROTECTED |

### D.10 — Escalation Check

| EscRule | Triggered? |
|---------|-----------|
| EscRule-01 | NO |
| EscRule-02 | NO |
| EscRule-03 | NO — C-10 does not block; proximity documented |
| EscRule-04 | NO |
| EscRule-05 | NO |

---

## Part E — Candidate Analysis: CD-03 (ADR6-INV-01)

### E.1 — Candidate Description

ADR6-INV-01: "CO-5 (Election Validity) is void unless CO-2 (Evidence Completeness) + CO-3 (Evidence Authenticity) + CO-4 (Constitutional Compliance) are all independently satisfied; non-waivable." Established in ADR-6. CO-5 is the constitutionally terminal certification that produces TS-1 (constitutionally final election result).

### E.2 — Constitutional Purpose

ADR6-INV-01 prevents CertificationAuthority from issuing a constitutionally valid election result without independently satisfying each certification dimension. Without the derivation rule, CA could issue CO-5 by satisfying only CO-1 (Process Compliance — reliance permitted) while skipping CO-2/CO-3/CO-4. This directly enables F-1 (CA+CAB capture) and TM-47 (Certification Chain Self-Reference).

### E.3 — Criteria Evaluation

| Criterion | Answer | Direction | Basis |
|-----------|--------|-----------|-------|
| C.1 Constitutional Change | YES — removing derivation rule changes what a valid election result means | Principle | CO-5 valid without authentic evidence |
| C.2 Realization Plurality | SEPARABLE — derivation requirement = irreducible; CO-N enumeration = alternatives exist (different certification object taxonomy) | Separable | Different CO structure could satisfy same derivation principle |
| C.3 Challenge Rights | YES — without derivation rule, CO-5 cannot be challenged on CO-N grounds independently | Principle | Challenge granularity lost; S-2 standing weakened |
| C.4 Constitutional Failure | YES — CO-5 without CO-3 = certification without authenticity → AC-02 violation → constitutional fraud | Principle | Direct constitutional failure |
| C.5 Trust Root Integrity | YES — CO-3 is the Authenticity Root's formal engagement point; ADR6-INV-01 requires this engagement | Principle | Authenticity Root bypassed without CO-3 requirement |
| C.6 Anti-Capture | YES — CA could self-certify election validity without independent authenticity check | Principle | F-1 (CA+CAB capture path) enabled |
| C.7 Independence Existence vs. Form (MANDATORY) | SEPARABLE — derivation requirement EXISTENCE = constitutional; specific CO-N enumeration = form | Separable | CO taxonomy is architectural; derivation principle is constitutional |
| C.8 Constitutional Source | YES — AC-02, TM-47, F-1 (CA+CAB FAIL), ADR3-INV-01, OBS-ADR6-02 | HIGH confidence | Multiple direct sources |
| C.9 Governance Survivability (MANDATORY) | MEDIUM — certification is terminal; governance survivability depends on valid certification, not specifically on this invariant's survival | Neutral | Valid certification matters; CO-N structure is one realization |
| C.10 OQ-38A05-02 Non-Interference (MANDATORY) | **OQ-38A05-02 PROXIMITY ALERT** — CO-5 is what becomes TS-1. ADR6-INV-01 defines what valid CO-5 requires. ASSESSMENT: the derivation rule defines validity CONDITIONS for CO-5 issuance; OQ-38A05-02 asks whether TS-1 can be reopened when those conditions appeared met but were not (e.g., CO-3 certified but AC-31 was captured). DISTINCT questions. C-10 does NOT block. LANGUAGE REQUIRED: ADR6-INV-01 classification does not resolve OQ-38A05-02 in either direction. | Does NOT block | Distinct: issuance validity vs. post-finality discovery |
| C.11 Misclassification Impact | False Form (derivation requirement architectural) cost DOMINANT → CO-5 valid without authentication; F-1 enabled; TM-47 ratchet active; TS-1 meaningless. False Principle (CO-N enumeration constitutional): manageable ossification of specific CO taxonomy | Toward Principle | False Form cost catastrophic |

### E.4 — Separability Finding

**CD-03-P: CO-5 Derivation Requirement** — The constitutional requirement that election validity certification (CO-5) is derived from and requires independent satisfaction of evidence completeness (CO-2), evidence authenticity (CO-3), and constitutional compliance (CO-4).

**CD-03-F: CO-N Certification Object Enumeration** — The specific architectural taxonomy of certification objects (CO-1 through CO-5 with defined scope and evaluation requirements).

### E.5 — Classification Result

| Component | Classification | Confidence |
|-----------|---------------|------------|
| CD-03-P: CO-5 Derivation Requirement | **PRINCIPLE** | HIGH |
| CD-03-F: CO-N Enumeration | **FORM** | HIGH |

Governing Principle for CD-03-F: CD-03-P (CO-5 Derivation Requirement). Principle Anchor satisfied.

**OQ-38A05-02 Explicit Language:** The CO-5 Derivation Requirement establishes what constitutes a validly issued CO-5. It does not address whether TS-1 can be constitutionally reopened after CO-5 was issued meeting all derivation requirements but later discovered to have been based on a false AC-31 reference. OQ-38A05-02 REMAINS PROTECTED.

### E.6 — Tier Evaluation (CD-03-P: Principle)

| Tier Criterion | Assessment |
|----------------|-----------|
| Tier 3 | CANDIDATE — removes the non-waivable core of certification → enables Constitutional Self-Destruction via fraudulent election results; AC-31 minimum (Tier 3) is partially protected through CO-3 requirement |
| Tier 2 | **PRIMARY CANDIDATE** — governance rule for certification process; qualified majority appropriate; Tier 3 candidacy to be assessed by CIC |

**Candidate Tier: Tier 2 (Tier 3 candidacy for CIC assessment given constitutional significance of CO-3/Authenticity Root linkage)**

### E.7 — Trust Root Impact

| Trust Root | Impact |
|-----------|--------|
| TR-01 Legitimacy | MEDIUM — constitutional compliance (CO-4) requires EC authority; CO-4 removal weakens EC standing |
| TR-02 Authenticity | HIGH — CO-3 (Evidence Authenticity) is the Authenticity Root's formal certification requirement |
| TR-03 Temporal | LOW |
| TR-04 Simultaneous | HIGH — CO-5 = only certification act requiring all three roots simultaneously (38A-05); ADR6-INV-01 is what makes this tri-root requirement binding |
| TR-05 Separation | POSITIVE CONTRIBUTION — CO-5 Derivation Requirement maintains each root's distinct certification role |

### E.8 — TM Impact

| Threat | Impact |
|--------|--------|
| TM-19 (AC-31 Capture) | DIRECT — without CO-3 requirement, AC-31 capture yields valid CO-5 |
| TM-39 (Independence Illusion) | YES — independence of CO-N evaluators is undermined if CO-5 derivation is architectural |
| TM-42 | LOW |
| TM-44 | LOW |
| TM-47 (Certification Self-Reference) | DIRECT — enabling mechanism; derivation rule is what prevents self-referential CO-5 issuance |

### E.9 — OQ Impact

| OQ | Impact |
|----|--------|
| OQ-38B05-05 | POSITIVE CONTRIBUTION — tri-root requirement in CO-5 derivation supports structural separation |
| OQ-38A05-02 | PROXIMITY — does not resolve; OQ-38A05-02 REMAINS PROTECTED |

### E.10 — Escalation Check

| EscRule | Triggered? |
|---------|-----------|
| EscRule-01 | NO |
| EscRule-02 | NO |
| EscRule-03 | NO |
| EscRule-04 | OBSERVE — CO-5 Derivation Requirement (CD-03-P) contributes to trust root separation via tri-root certification requirement; flagged as OQ-38B05-05 contributing element |
| EscRule-05 | NO |

---

## Part F — Candidate Analysis: CD-04 (ADR7-INV-01)

### F.1 — Candidate Description

ADR7-INV-01: "EC pre-designates successors for critical constitutional functions; succession chains must be pre-designated in EC; GovernanceAuthority is prohibited from holding succession authority over itself." Established in ADR-7. The pre-designation requirement ensures that critical authority functions can continue even when primary holders are unavailable.

### F.2 — Constitutional Purpose

ADR7-INV-01 prevents TM-43 (Successor Exhaustion → TM-42 Complete Deadlock). Without pre-designation, authority vacancies cannot be filled constitutionally — TM-43 → TM-42 Complete Deadlock (unconditional FAIL). The GovernanceAuthority prohibition prevents succession chain capture (a body controlling its own succession violates 38B04-INV-01).

### F.3 — Criteria Evaluation

| Criterion | Answer | Direction | Basis |
|-----------|--------|-----------|-------|
| C.1 Constitutional Change | YES — without pre-designation, governance cannot constitutionally succeed itself | Principle | TM-43 + TM-42 chain consequence |
| C.2 Realization Plurality | SEPARABLE — pre-designation EXISTENCE = constitutional; specific chain design = form | Separable | Different successor designee structures constitutionally adequate |
| C.3 Challenge Rights | YES — without pre-designation, challenge of succession decisions has no constitutional standard | Principle | Challenge rights require defined succession rules |
| C.4 Constitutional Failure | YES — TM-43 → TM-42 Complete Deadlock (unconditional F) | Principle | Direct FAIL-class consequence |
| C.5 Trust Root Integrity | MEDIUM — succession affects who holds Legitimacy Root authority when primary holders unavailable | Principle/Neutral | Legitimacy Root continuity depends on succession |
| C.6 Anti-Capture | MEDIUM — GovernanceAuthority prohibition prevents succession self-dealing | Principle | Specific anti-capture application in succession domain |
| C.7 Independence Existence vs. Form (MANDATORY) | SEPARABLE — succession continuity requirement EXISTENCE = constitutional; specific succession chain = form | Separable | Succession continuity is the constitutional requirement |
| C.8 Constitutional Source | YES — TM-43, TM-42 (unconditional F), ADR7-INV-01, 38B-03 (Temporal Root governance) | HIGH confidence | |
| C.9 Governance Survivability (MANDATORY) | **HIGH (DIRECT)** — succession IS the governance survivability mechanism | Principle | MANDATORY criterion → YES; succession is the load-bearing survivability element |
| C.10 OQ-38A05-02 Non-Interference (MANDATORY) | LOW — succession is not directly related to TS-1 finality/validity | Does NOT block | |
| C.11 Misclassification Impact | False Form cost DOMINANT → TM-43+TM-42 chain; governance deadlock after authority vacancies; constitutional continuity fails. False Principle: manageable ossification of specific chain | Toward Principle | False Form cost catastrophic |

### F.4 — Separability Finding

**CD-04-P: Succession Pre-Designation Principle** — The constitutional requirement that EC pre-designates constitutionally adequate successors for critical authority functions, and that GovernanceAuthority may not hold succession authority over itself.

**CD-04-F: Specific Succession Chain Design** — The architectural specification of which bodies serve as designees in which order for each authority function.

### F.5 — Classification Result

| Component | Classification | Confidence |
|-----------|---------------|------------|
| CD-04-P: Succession Pre-Designation Principle | **PRINCIPLE** | HIGH |
| CD-04-F: Specific Succession Chain Design | **FORM** | HIGH |

Governing Principle for CD-04-F: CD-04-P (Succession Pre-Designation Principle). Principle Anchor satisfied.

### F.6 — Tier Evaluation (CD-04-P: Principle)

| Tier Criterion | Assessment |
|----------------|-----------|
| Tier 3 | CANDIDATE — succession protects constitutional continuity; without pre-designation, TM-43 enables complete governance collapse; related to challenge rights (Tier 3) via C.9 |
| Tier 2 | **PRIMARY CANDIDATE** — governance rule about succession process |

**Candidate Tier: Tier 2 (Tier 3 candidacy given TM-42 Complete Deadlock consequence)**

### F.7–F.10 — Trust Root, TM, OQ, Escalation Summary

TR-01 Legitimacy: HIGH — succession governs who holds authority over EC.
TM-42/TM-43: DIRECT.
OQ-38B05-05: Contributes to Temporal Root continuity.
EscRule-09 (C-9 mandatory): YES — mandatory criterion confirms Principle.
No CIC escalation required.

---

## Part G — Candidate Analysis: CD-05 (ADR7-INV-02)

### G.1 — Candidate Description

ADR7-INV-02: "No authority may self-grant expanded standing, self-restrict standing of others, or expand/restrict the scope of challenges against itself." Established in ADR-7. The anti-capture invariant prevents any authority from manipulating the challenge process to immunize itself from constitutional challenge. Conditional default designation: **PRINCIPLE-LEVEL** (38C-03 ruling; C.6 primary criterion).

### G.2 — Constitutional Purpose

ADR7-INV-02 is the constitutional response to TM-39 (Independence Illusion / F-4 — dominant program FAIL-class risk). TM-39 attacks the FORM of independence, not the EXISTENCE of an independence commitment. An authority that can manipulate its own challenge standing can appear constitutionally independent while functionally eliminating its accountability — exactly what F-4 threatens. ADR7-INV-02 makes this attack constitutionally visible.

### G.3 — Criteria Evaluation

| Criterion | Answer | Direction | Basis |
|-----------|--------|-----------|-------|
| C.1 Constitutional Change | YES — removing this rule means authorities can self-immunize; constitutional challenge architecture collapses | Principle | F-3 (GA+ASA+CAB structural FAIL) enabled |
| C.2 Realization Plurality | NO — the prohibition against self-dealing IS the constitutional requirement; no alternative form achieves the same constitutional purpose | Principle | Only prohibiting self-dealing prevents self-dealing |
| C.3 Challenge Rights | YES — challenge rights become meaningless if challenged authority controls challenge standing | Principle | S-1/S-2/S-3 undermined |
| C.4 Constitutional Failure | YES — self-dealing → F-3 (GA+ASA+CAB structural FAIL); challenge architecture constitutionally inert | Principle | Direct FAIL-class consequence |
| C.5 Trust Root Integrity | YES — all three trust root governing bodies could self-expand standing against challenges | Principle | Multi-root impact |
| C.6 Anti-Capture (PRIMARY) | **YES — UNAMBIGUOUS** — prohibition against self-dealing in challenge standing IS the paradigmatic anti-capture constitutional commitment | Principle | C.6 = YES; conditional default confirmed |
| C.7 Independence Existence vs. Form (MANDATORY) | The prohibition IS the existence of constitutional independence for challenge processes; it is not a form of a higher independence requirement — it IS the requirement | Principle | This element is its own existence requirement |
| C.8 Constitutional Source | YES — TM-39/F-4 (dominant FAIL-class), F-3 (GA+ASA+CAB structural FAIL), 38B-05 Protected Core (challenge rights — Tier 3 item 2), ADR7-INV-02 | HIGH confidence | |
| C.9 Governance Survivability (MANDATORY) | HIGH — anti-capture is load-bearing for governance survivability under adversarial conditions | Principle | |
| C.10 OQ-38A05-02 Non-Interference (MANDATORY) | LOW — not related to TS-1 finality/validity | Does NOT block | |
| C.11 Misclassification Impact | False Form cost CATASTROPHIC — TM-39/F-4 unmitigated; dominant program FAIL-class risk operational; F-4 Independence Illusion structurally enabled at constitutional level. False Principle: negligible — ossifying the anti-capture rule is constitutionally appropriate; no architectural flexibility is lost | Strongly toward Principle | False Form = program-level constitutional catastrophe |

**All determinate criteria indicate Principle. No split. C.6 primary criterion = YES unambiguous. Conditional default CONFIRMED.**

### G.4 — Classification Result

| Candidate | Classification | Confidence |
|-----------|---------------|------------|
| CD-05: ADR7-INV-02 Anti-Capture Invariant | **PRINCIPLE** | HIGH |

No separability. The prohibition against self-dealing IS the constitutional principle. The conditional default from 38C-03 is confirmed.

### G.5 — Tier Evaluation

| Tier Criterion | Assessment |
|----------------|-----------|
| Tier 3 | **STRONG CANDIDATE** — challenge rights are Tier 3 Protected Core (item 2 in 38B-05 catalog); anti-capture is the constitutional protection of challenge rights; removing ADR7-INV-02 = removing practical effectiveness of Tier 3 challenge right protection; TM-39/F-4 is the dominant program-level FAIL-class risk; OBS-38A06-SD1 enabled if authorities self-immunize |
| Tier 2 | Alternative — governance rule about challenge administration |

**Candidate Tier: Tier 3 (Challenge Rights protection — Tier 3 Protected Core item 2). Strong case. CIC assessment recommended given stakes.**

### G.6 — Trust Root Impact

TR-01/02/03: HIGH (all three roots' governing authorities could self-expand standing).
TR-04 Simultaneous: HIGH — ADR7-INV-02 applies simultaneously across all authorities.
TR-05: POSITIVE CONTRIBUTION — anti-capture protects independence of each root.

### G.7 — TM Impact

TM-19: INDIRECT — CA self-immunized from challenge → TM-19 capture easier.
TM-39/F-4: DIRECT — ADR7-INV-02 is the primary mitigation for TM-39/F-4.
TM-42: INDIRECT.
TM-44: INDIRECT.
TM-47: INDIRECT.

### G.8 — OQ and Escalation

OQ-38B05-05: POSITIVE CONTRIBUTION — anti-capture applies to all trust root governing bodies; protects structural separation from self-dealing collapse.
OQ-38A05-02: NONE.
EscRule-02: NOT triggered (not Ambiguous; classification confirmed).
No CIC escalation required.

---

## Part H — Candidate Analysis: CD-06 (38B01-INV-01)

### H.1 — Candidate Description

38B01-INV-01: "CIC holds constitutional interpretation authority; CAB holds adjudication authority. These functions must not be combined in a single body." Established in 38B-01. This is the foundational separation that prevents the constitutional interpreter from also being the constitutional enforcer.

### H.2 — Constitutional Purpose

The separation prevents CIC from becoming self-referential: if CIC both interprets the constitution and adjudicates challenges based on those interpretations, CIC can shape interpretations to achieve desired adjudicatory outcomes. This is the constitutional-governance analog of the judicial self-reference problem (TM-03 / AW-02 pattern).

### H.3 — Criteria Evaluation

| Criterion | Answer | Direction | Basis |
|-----------|--------|-----------|-------|
| C.1 Constitutional Change | YES — combining interpretation and adjudication changes what constitutional governance can achieve | Principle | Self-referential constitutional governance enabled |
| C.2 Realization Plurality | SEPARABLE — separation EXISTENCE = irreducible; CIC/CAB as specific role-holders = alternative bodies possible in principle | Separable | Separation principle is irreducible; body assignments are form |
| C.3 Challenge Rights | YES — if CIC adjudicates its own interpretations, challenge rights against CIC decisions collapse | Principle | Self-referential challenge process |
| C.4 Constitutional Failure | YES — combined body → circular constitutional authority → TM-09 (Constitutional Drift) enabled | Principle | Constitutional governance integrity fails |
| C.5 Trust Root Integrity | YES — CIC governs Legitimacy Root; CIC/CAB separation protects Legitimacy Root governance integrity | Principle | Legitimacy Root governance |
| C.6 Anti-Capture | YES — if CIC adjudicates, CIC can expand its own interpretive authority through adjudicatory rulings → self-dealing | Principle | Anti-capture application in governance domain |
| C.7 Independence Existence vs. Form (MANDATORY) | SEPARABLE — separation EXISTENCE = constitutional; CIC/CAB assignment = tightly coupled form | Separable | Separation is constitutional; specific bodies are architectural |
| C.8 Constitutional Source | YES — 38B-01, OBS-38B04-01 (CIC interpretive dependence chain), TM-01 (Constitution Capture), TM-12 | HIGH confidence | |
| C.9 Governance Survivability (MANDATORY) | HIGH — constitutional interpretation and adjudication are both load-bearing; their separation is governance-survivability critical | Principle | |
| C.10 OQ-38A05-02 Non-Interference (MANDATORY) | MEDIUM proximity — CIC interprets, CAB adjudicates OQ-38A05-02 when it arises; the separation ITSELF is not OQ-38A05-02. C-10 does NOT block. | Does NOT block | |
| C.11 Misclassification Impact | False Form cost DOMINANT — combined interpretation/adjudication → TM-09 constitutional drift; CIC self-expands via adjudicatory rulings; Legitimacy Root capture enabled. False Principle (CIC/CAB bodies constitutional): moderate ossification | Toward Principle | |

**TIGHT COUPLING NOTE:** CIC and CAB, as currently the only bodies in the constitutional architecture with these roles, represent a case where the Form is tightly coupled to the Principle. There is no alternative body currently defined. Classifying the Form means: if alternative bodies were designated, they would need to satisfy the Separation Principle. The current body assignment is the Form.

### H.4 — Separability Finding

**CD-06-P: Interpretation/Adjudication Separation Principle** — The constitutional requirement that constitutional interpretation authority and constitutional adjudication authority must be held by constitutionally distinct bodies with non-overlapping mandates.

**CD-06-F: CIC/CAB Institutional Assignment** — The specific allocation of interpretation to CIC and adjudication to CAB as the two designated constitutional bodies in the current architecture.

### H.5 — Classification Result

| Component | Classification | Confidence |
|-----------|---------------|------------|
| CD-06-P: Interpretation/Adjudication Separation | **PRINCIPLE** | HIGH |
| CD-06-F: CIC/CAB Institutional Assignment | **FORM** | MEDIUM (tight coupling noted) |

Governing Principle for CD-06-F: CD-06-P (Interpretation/Adjudication Separation Principle). Principle Anchor satisfied.

**Tight Coupling Note:** CIC and CAB are the only currently designated bodies. Any change to the form assignment effectively changes which bodies hold constitutional interpretation authority — which approaches constitutional significance. ARB should assess whether CD-06-F should be elevated to Tier 2 protection given this tight coupling.

### H.6 — Tier Evaluation (CD-06-P: Principle)

**Candidate Tier: Tier 2** (constitutional governance rule; qualified majority appropriate). CIC existence itself is Tier 3 (Protected Core item 3); the separation requirement governing CIC's role scope may also reach Tier 3.

### H.7–H.10 — Summary

TR-01 Legitimacy: HIGH.
TM-01/09/12: DIRECT.
OQ-38B05-05: CONTRIBUTING (Legitimacy Root governance separation protected by CD-06-P).
EscRule-01: OBSERVE — if CD-06 were Ambiguous, Legitimacy Root impact triggers EscRule-01. But CD-06-P is HIGH confidence Principle. EscRule-01 does NOT apply.

---

## Part I — Candidate Analysis: CD-07 (38B04-INV-01)

### I.1 — Candidate Description

38B04-INV-01: "No self-appointment; no circular appointment (an authority may not appoint its own adjudicator or CIC); no downward capture (higher authority may not be appointed by those it governs); constitutional independence of appointer." Established in 38B-04.

### I.2 — Constitutional Purpose

Appointment independence prevents concentration of constitutional authority through appointment chains. An authority that controls its own appointment perpetuates itself; an authority that appoints its adjudicator eliminates accountability. These patterns are the appointment-layer analog of TM-39 (Independence Illusion) and TM-06 (Concentration Chain Capture).

### I.3 — Criteria Evaluation

| Criterion | Answer | Direction | Basis |
|-----------|--------|-----------|-------|
| C.1 Constitutional Change | YES — self-appointment enables captured authorities to perpetuate; constitutional obligations change | Principle | Authority perpetuation without constitutional constraint |
| C.2 Realization Plurality | SEPARABLE — appointment independence requirement = irreducible; specific appointment mechanics = form | Separable | Multiple valid appointment processes exist |
| C.3 Challenge Rights | YES — authorities that appoint their adjudicators can de facto eliminate challenge standing | Principle | SCF-38B04-01 (CAB appointment tension) |
| C.4 Constitutional Failure | YES — self-appointment → TM-06 Concentration Chain Capture; F-1/F-2 via appointment chains | Principle | |
| C.5 Trust Root Integrity | MEDIUM — all three root governing bodies are appointed; appointment independence affects root governance | Principle | |
| C.6 Anti-Capture | YES — no-circular-appointment IS anti-capture at the appointment layer | Principle | |
| C.7 Independence Existence vs. Form (MANDATORY) | SEPARABLE — appointment independence requirement EXISTENCE = constitutional; specific process = form | Separable | |
| C.8 Constitutional Source | YES — 38B-04, OBS-38B04-02 (MA concentration), TM-06, OBS-38B04-04 (AA-01 accumulation) | HIGH confidence | |
| C.9 Governance Survivability (MANDATORY) | HIGH — appointment independence determines who holds authority after transitions; load-bearing for continuity | Principle | |
| C.10 OQ-38A05-02 Non-Interference (MANDATORY) | LOW | Does NOT block | |
| C.11 Misclassification Impact | False Form cost DOMINANT → self-appointment enabled for all 7 authority aggregates; F-4 Independence Illusion fully operational at appointment layer; program-level constitutional risk. False Principle: manageable ossification of specific processes | Toward Principle | |

### I.4 — Separability Finding

**CD-07-P: Appointment Independence Principle** — The constitutional requirement that authority appointments must satisfy independence constraints: no self-appointment, no circular appointment, no downward capture, constitutional independence of the appointing body.

**CD-07-F: Specific Appointment Assignments** — The specific allocation of appointment authority across authorities (e.g., MA appoints CriteriaAuthority; CriteriaAuthority appoints EnrollmentAuthority).

### I.5 — Classification Result

| Component | Classification | Confidence |
|-----------|---------------|------------|
| CD-07-P: Appointment Independence Principle | **PRINCIPLE** | HIGH |
| CD-07-F: Specific Appointment Assignments | **FORM** | HIGH |

Governing Principle for CD-07-F: CD-07-P (Appointment Independence Principle). Principle Anchor satisfied.

### I.6 — Tier Evaluation

**CD-07-P Candidate Tier: Tier 2.** Authority appointment rules are governance procedures requiring deliberation and qualified majority.

---

## Part J — Candidate Analysis: CD-08 (38B05-INV-01)

### J.1 — Candidate Description

38B05-INV-01: "Three amendment tiers with differential thresholds; no provision may be amended via a procedure less demanding than its assigned tier; Tier 3 reclassification attack blocked (reclassifying a Tier 3 provision to Tier 2 requires Tier 3 procedure)." Established in 38B-05. This is the constitutional meta-protection for the amendment process itself.

### J.2 — Constitutional Purpose

38B05-INV-01 closes the Constitutional Self-Destruction path (OBS-38A06-SD1). Without differential protection, all constitutional provisions — including challenge rights, CIC existence, AC-31 minimum — could be removed through simple majority. The three-tier structure creates graduated protection proportional to the constitutional significance of the provision being changed.

### J.3 — Criteria Evaluation

| Criterion | Answer | Direction | Basis |
|-----------|--------|-----------|-------|
| C.1 Constitutional Change | YES — removing differential protection changes the constitutional amendment regime itself | Principle | OBS-38A06-SD1 enabled without Tier 3 protection |
| C.2 Realization Plurality | CONDITIONAL — differential protection EXISTENCE = irreducible; three-tier as specific realization = SPLIT. Could be two tiers or four tiers. Whether the THREE-tier structure itself is the constitutional principle is the contested question (38C-04 Appendix: "genuinely ambiguous") | Split/Ambiguous | |
| C.3 Challenge Rights | YES — 38B-05 Protected Core includes challenge rights (Tier 3 item 2); without the tier structure, challenge rights lose constitutional entrenchment | Principle | |
| C.4 Constitutional Failure | YES — uniform amendment threshold → Protected Core provisions (challenge rights, CIC existence, AC-31 minimum) amendable by simple majority → OBS-38A06-SD1 | Principle | Direct self-destruction path |
| C.5 Trust Root Integrity | YES — Legitimacy Root (EC) governance integrity depends on the amendment tier structure | Principle | |
| C.6 Anti-Capture | YES — Tier 3 protection of challenge rights and CIC existence is the ultimate anti-capture layer | Principle | |
| C.7 Independence Existence vs. Form (MANDATORY) | AMBIGUOUS — differential protection EXISTENCE = constitutional; but THREE-TIER as the specific form of differential protection is contested: is the number of tiers architectural, or is it itself constitutional? | Ambiguous | 38C-04 Appendix: "three-tier structure may itself be the principle" |
| C.8 Constitutional Source | YES — OBS-38A06-SD1, 38B-05, 38B-06 closure analysis | HIGH confidence | |
| C.9 Governance Survivability (MANDATORY) | HIGH — the amendment tier structure is load-bearing for all constitutional protections; without it, MA can unilaterally dismantle the governance model | Principle | |
| C.10 OQ-38A05-02 Non-Interference (MANDATORY) | LOW | Does NOT block | |
| C.11 Misclassification Impact | **ASYMMETRIC** — False Form for differential protection existence: catastrophic (OBS-38A06-SD1 enabled; self-destruction path open). False Principle for three-tier specific structure: manageable (threshold values and tier count ossified; could constrain reasonable constitutional evolution). C-11 directs toward Principle for differential protection existence; directionally neutral for specific three-tier structure | Toward Principle for existence; Neutral for form | |

**50/50 RULE (R4):** C.7 yields Ambiguous (MANDATORY criterion). Decision Rule: mandatory criterion indeterminate → Ambiguous. C-11 provides directional resolution: toward Principle.

### J.4 — Classification Result

| Candidate | Classification | Confidence |
|-----------|---------------|------------|
| CD-08: 38B05-INV-01 Three Amendment Tiers | **AMBIGUOUS** → C-11 directs toward Principle | AMBIGUOUS |

**C-11 Resolution Direction:** False Form cost is DOMINANT for the differential protection core. Pending CIC ruling, this candidate defaults toward Principle-level protection under R1 (C-11 tiebreaker).

**Provisional Interim Status (38C03-INV-01):** 38B05-INV-01 retains its current constitutional protection until classification is formally ruled.

### J.5 — CIC Escalation Questions

**EscRule-01 TRIGGERED** (Ambiguous designation; affects Legitimacy Root).

CIC must adjudicate:
1. Is the three-tier structure itself constitutionally required, or is it one form of differential protection? If three-tier is the principle, it is constitutional. If differential protection is the principle, the three-tier structure is form.
2. What amendment tier applies when reclassifying the tier structure itself? (The current specification blocks Tier 3 reclassification at Tier 3 — but what tier governs the three-tier structure?)
3. Does the three-tier structure require Tier 3 protection (as a meta-constitutional element) or Tier 2?

### J.6 — Tier Evaluation (Provisional)

If Principle (pending CIC ruling): **Tier 3 candidate** (OBS-38A06-SD1 directly enabled without this protection; it is the amendment process itself — Protected Core item 5).

---

## Part K — Candidate Analysis: CD-09 (ADR-2 Independence Forms)

### K.1 — Candidate Description

ADR-2 independence forms: The specific per-function independence realizations selected in ADR-2 for the five D43 authority functions:
- ENROLL: Option B — Enrollment Review Committee (organizational separation, fixed term, CR/CAB independence)
- CRITERIA: Option B — Criteria Review Committee (same structure; self-referential L-4 broken)
- AUDIT: Option D — Hybrid (AuditScopeAuthority: constitutional scope; AuditExecutionAuthority: independent execution body)
- GOV-AUTH: Option B — Governance Authorization Committee (operational independence with challengeability)
- CERT: Option C — External Organization (organizational externality; highest constitutional risk for CERT)

### K.2 — Constitutional Purpose

These forms realize the constitutional requirement that each D43 authority function has at least one constitutionally independent authority relationship (CF-05-19, AC-04 through AC-07). The constitutional requirement is the Principle; the specific organizational structure chosen for each function is the Form.

### K.3 — Criteria Evaluation

| Criterion | Answer | Direction | Basis |
|-----------|--------|-----------|-------|
| C.1 Constitutional Change | NO — changing ENROLL from Committee to External Organization does not change the constitutional obligation (independence must exist); it changes how that obligation is met | Form | Constitutional obligation (independence) survives form changes |
| C.2 Realization Plurality | YES — the 36E-04 Option Catalog identified A/B/C/D/E options for most D43 functions; HIGH realization plurality | Form | Multiple constitutionally adequate forms exist |
| C.3 Challenge Rights | NO — challenge rights are not affected by whether independence is implemented via Committee or External Organization | Form | |
| C.4 Constitutional Failure | ARCHITECTURAL FAILURE — the underlying constitutional requirement (CF-05-19) survives; only the form fails if a specific implementation no longer achieves independence | Form | |
| C.5 Trust Root Integrity | MEDIUM — independence forms affect how trust roots maintain structural independence, but changing forms while maintaining independence does not affect root integrity | Form | |
| C.6 Anti-Capture | FORM-LEVEL — the anti-capture principle is in ADR7-INV-02 (CD-05, classified PRINCIPLE); the independence forms implement it | Form | Constitutional anti-capture principle is the Principle |
| C.7 Independence Existence vs. Form (MANDATORY) | **FORM — CLEAR** — these explicitly ARE the implementation forms of the constitutional independence requirement; C-7 was designed to identify these as Forms | Form | Primary C-7 candidate — confirmed Form |
| C.8 Constitutional Source | FORM — traces to CF-05-19, AC-04 through AC-07, but these are the governing Principles, not constitutional mandates for these specific forms | Form traces to Principle | |
| C.9 Governance Survivability (MANDATORY) | LOW — changing independence forms while maintaining independence does not reduce governance survivability | Neutral | |
| C.10 OQ-38A05-02 Non-Interference (MANDATORY) | LOW | Does NOT block | |
| C.11 Misclassification Impact | False Principle (forms constitutional): moderate ossification — changing from Committee to External Organization requires EC amendment. False Form: constitutional obligation (independence) protected by its governing Principle (CF-05-19); only form is architectural. False Form cost is LOW because the governing Principle provides constitutional protection. False Principle cost is moderate (architectural rigidity). Roughly symmetric → standard Form designation confirmed | FORM | |

### K.4 — Principle Anchor Analysis

**Governing Principle for CD-09:** Constitutional Independence Requirement per D43 Function (CF-05-19 + AC-04/05/06/07 + ADR7-INV-02 as constitutional sources).

**Principle Anchor Status:** The governing Principle is not yet a named EC provision. It exists as:
(a) CF-05-19 (confirmed discovery finding — CANDIDATE, not yet EC-designated)
(b) AC-04/05/06/07 (confirmed architectural constraints from 36E-01)
(c) ADR7-INV-02 (classified PRINCIPLE in CD-05 in this exercise)

Per R2 (Principle Anchor Requirement): CD-09's constitutional standing is **provisional** until the governing Principle is formally classified and EC-designated. The form classification is architecturally sound but constitutionally unanchored at the EC level at this stage.

### K.5 — Classification Result

| Candidate | Classification | Confidence |
|-----------|---------------|------------|
| CD-09: ADR-2 Per-Function Independence Forms | **FORM** | HIGH |

Governing Principle: Constitutional Independence Requirement per D43 Function (CF-05-19; ADR7-INV-02 as classified in CD-05). Standing: PROVISIONAL pending EC designation of governing Principle.

### K.6 — Trust Root, TM, OQ Impact

TR-02 (Authenticity): MEDIUM — CERT external organization affects Authenticity Root certification independence.
TM-39: DIRECT — independence forms are the primary TM-39 attack surface; form degradation without principle violation IS the TM-39 attack. ADR7-INV-02 (CD-05 Principle) provides constitutional visibility of such degradation.
OQ-38B05-05: CONTRIBUTING — independence forms contribute to structural independence of each root; their form classification means they are revocable without constitutional amendment.

---

## Part L — Candidate Analysis: CD-10 (Trust Root Structural Separation)

### L.1 — Candidate Description

Trust Root Structural Separation (OQ-38B05-05 candidate): "The three trust roots (Legitimacy, Authenticity, Temporal) must be constitutionally protected as structurally distinct — such that no single governance action (Tier 2 coalition, single authority compromise, or adversarial coordination) can simultaneously invalidate two or more roots without triggering the near-unanimity threshold (Tier 3)."

Per 38C-03 ruling, OQ-38B05-05 is a **mandatory primary requirement** and a **mandatory candidate** in this exercise. EscRule-04 is mandatory.

### L.2 — Constitutional Purpose

OQ-38B05-05 asks whether the architectural differentiation of the three trust roots (documented in OBS-38B06-02: "structurally differentiated, not constitutionally protected as distinct") must be elevated to constitutional status. If the three roots can be simultaneously compromised through a Tier 2 coalition without triggering Tier 3, F-4 (Independence Illusion) is constitutionally unmitigated even under Option C.

### L.3 — Criteria Evaluation

| Criterion | Answer | Direction | Basis |
|-----------|--------|-----------|-------|
| C.1 Constitutional Change | CONDITIONAL — if separation IS constitutionally required, removing it changes constitutional obligations. If it is merely architectural, removing it is an architectural change. | Conditional on OQ-38B05-05 resolution | Contested question |
| C.2 Realization Plurality | CONDITIONAL — structural separation could be realized differently (e.g., different tier thresholds for cross-root attacks), but the separation requirement itself cannot be "differently realized" without losing its character | Split | |
| C.3 Challenge Rights | YES (conditional) — if roots can be simultaneously compromised without Tier 3, challenge rights lack constitutional protection against multi-root attacks | Indicates Principle (conditional) | |
| C.4 Constitutional Failure | CONDITIONAL — multi-root simultaneous compromise → CO-5 constitutionally undetectable false result (38A-05). Whether this IS a constitutional failure or a constitutional risk that architecture must manage is contested | Split | |
| C.5 Trust Root Integrity | **REFLEXIVE (TR-05)** — this candidate IS about trust root separation; TR-05 applied to this candidate asks whether this element contributes to trust root separation, which is circular | Reflexive | EscRule-04 handles this |
| C.6 Anti-Capture | YES — simultaneous compromise of multiple roots enables undetectable constitutional capture | Indicates Principle | TM-39/F-4 |
| C.7 Independence Existence vs. Form (MANDATORY) | **INDETERMINATE** — is structural separation an EXISTENCE requirement (constitutional) or is it a FORM (one architectural approach to protecting roots)? This is the contested constitutional question that C-7 cannot itself resolve | Ambiguous | Core of OQ-38B05-05 |
| C.8 Constitutional Source | YES — OBS-38B06-02 (differentiated but not protected), 38B-05 OBS-38B05-05 (HIGH significance candidate omission), TM-39/F-4, 38A-05 (three-root simultaneous failure) | HIGH constitutional significance | Does not confirm constitutional status |
| C.9 Governance Survivability (MANDATORY) | HIGH — trust root structural collapse → total constitutional governance failure (no legitimacy, no authenticity, no temporal reference) | Indicates Principle | |
| C.10 OQ-38A05-02 Non-Interference (MANDATORY) | MEDIUM — trust root structural separation is not TS-1 finality. C-10 does NOT block. | Does NOT block | |
| C.11 Misclassification Impact | **False Form cost DOMINANT** — if trust root separation is not constitutionally required, a Tier 2 coalition can simultaneously compromise multiple roots without triggering Tier 3; F-4 remains structurally unmitigated; TM-39 attack is constitutionally invisible. False Principle: potential over-constitutional ossification of trust root architecture at a specific structural form | Toward Principle | But C-7 indeterminate → Ambiguous |

### L.4 — Classification Result

| Candidate | Classification | Confidence |
|-----------|---------------|------------|
| CD-10: Trust Root Structural Separation | **AMBIGUOUS** (EscRule-04 mandatory; TR-05 reflexive; C.7 indeterminate) | AMBIGUOUS |

**C-11 Resolution Direction:** False Form cost DOMINANT → C-11 directs toward Principle pending CIC ruling. This candidate defaults toward Principle-level protection as interim status under 38C03-INV-01.

**CRITICAL: This classification exercise does NOT resolve OQ-38B05-05.** OQ-38B05-05 remains the mandatory primary requirement for 38C. The exercise produces the Ambiguous designation with C-11 directional guidance. CIC adjudication is mandatory before any final designation.

### L.5 — CIC Escalation Questions (EscRule-04 MANDATORY)

CIC must adjudicate:
1. Is the structural separation of the three trust roots constitutionally required, or is it an architectural property that may be achieved through non-constitutional means?
2. If constitutionally required: at what EC tier should trust root separation be protected? (Tier 3 given near-unanimity required for multi-root impact? Tier 2?)
3. Is trust root separation a single constitutional provision, or is it an emergent property of the individual trust root governance provisions (each root's governance separately protected)?
4. Does the current architecture's three-root differentiation (OBS-38B06-02) satisfy a constitutional separation requirement, or is additional constitutional specification required?

**EscRule-04 is mandatory. No final designation without CIC ruling.**

### L.6 — Trust Root Impact (TR-01 through TR-05)

TR-01/02/03: This candidate covers all three roots simultaneously.
TR-04 Simultaneous: DIRECT — structural separation is specifically about preventing simultaneous compromise.
TR-05: REFLEXIVE — this candidate IS the trust root separation property; TR-05 cannot be applied without circularity. Acknowledged. EscRule-04 handles the reflexivity by routing to CIC.

### L.7 — TM Impact

TM-39/F-4: DIRECT — structural separation is the constitutional response to TM-39 (Independence Illusion). Without constitutional protection, multi-root simultaneous attack is constitutionally invisible.
TM-19/TM-47: INDIRECT — Authenticity Root capture becomes less visible without separation protection.

### L.8 — OQ Impact

OQ-38B05-05: **THIS IS THE CANDIDATE** — the exercise does not resolve it; it produces the evidence for CIC adjudication.
OQ-38A05-02: Does NOT resolve.

---

## Part M — Required Outputs

### Output A — Classification Matrix

| Code | Candidate | Classification | Confidence | CIC Required? |
|------|-----------|---------------|------------|---------------|
| CD-01-P | Non-Substitution Principle (from ADR3-INV-01) | PRINCIPLE | HIGH | No |
| CD-01-F | Three-Stratum Evidence Model (from ADR3-INV-01) | FORM | HIGH | No |
| CD-02-P | Challenge Terminality Principle (from ADR5-INV-01) | PRINCIPLE | HIGH | No |
| CD-02-F | R-8 as Terminal Remedy (from ADR5-INV-01) | FORM | MEDIUM | No |
| CD-03-P | CO-5 Derivation Requirement (from ADR6-INV-01) | PRINCIPLE | HIGH | No |
| CD-03-F | CO-N Enumeration (from ADR6-INV-01) | FORM | HIGH | No |
| CD-04-P | Succession Pre-Designation Principle (from ADR7-INV-01) | PRINCIPLE | HIGH | No |
| CD-04-F | Specific Succession Chain Design (from ADR7-INV-01) | FORM | HIGH | No |
| CD-05 | ADR7-INV-02 Anti-Capture Invariant | PRINCIPLE | HIGH | No (default confirmed) |
| CD-06-P | Interpretation/Adjudication Separation (from 38B01-INV-01) | PRINCIPLE | HIGH | No |
| CD-06-F | CIC/CAB Institutional Assignment (from 38B01-INV-01) | FORM | MEDIUM | No |
| CD-07-P | Appointment Independence Principle (from 38B04-INV-01) | PRINCIPLE | HIGH | No |
| CD-07-F | Specific Appointment Assignments (from 38B04-INV-01) | FORM | HIGH | No |
| CD-08 | 38B05-INV-01 Three Amendment Tiers | AMBIGUOUS → C-11 toward Principle | AMBIGUOUS | YES (EscRule-01) |
| CD-09 | ADR-2 Per-Function Independence Forms | FORM | HIGH | No |
| CD-10 | Trust Root Structural Separation | AMBIGUOUS → C-11 toward Principle | AMBIGUOUS | YES (EscRule-04 MANDATORY) |

### Output B — Ambiguous Items Register

| Code | Candidate | CIC Questions | EscRule | C-11 Direction |
|------|-----------|--------------|---------|----------------|
| CD-08 | 38B05-INV-01 | Is three-tier structure itself constitutional? What tier governs? Reclassification procedure? | EscRule-01 (Legitimacy Root) | Toward Principle |
| CD-10 | Trust Root Structural Separation | Is separation constitutionally required? At what tier? Is it a single provision or emergent? Does current architecture satisfy it? | EscRule-04 MANDATORY | Toward Principle |

### Output C — Candidate Principle Inventory

| Principle | Source | Tier Candidate |
|-----------|--------|----------------|
| Non-Substitution Principle | CD-01-P | Tier 2 |
| Challenge Terminality Principle | CD-02-P | Tier 2 (Tier 3 candidate under review) |
| CO-5 Derivation Requirement | CD-03-P | Tier 2 (Tier 3 candidacy for CIC assessment) |
| Succession Pre-Designation Principle | CD-04-P | Tier 2 (Tier 3 candidate) |
| Anti-Capture Invariant | CD-05 | **Tier 3** (challenge rights protection; strong case) |
| Interpretation/Adjudication Separation | CD-06-P | Tier 2 (Tier 3 candidacy given CIC existence is Tier 3) |
| Appointment Independence Principle | CD-07-P | Tier 2 |
| 38B05-INV-01 (pending CIC) | CD-08 | Tier 3 (provisional pending CIC) |
| Trust Root Structural Separation (pending CIC) | CD-10 | Tier 3 candidate (pending CIC ruling) |

### Output D — Candidate Form Inventory

| Form | Governing Principle | Principle Anchor Status |
|------|--------------------|-----------------------|
| Three-Stratum Evidence Model | Non-Substitution Principle (CD-01-P) | SATISFIED |
| R-8 as Terminal Remedy | Challenge Terminality Principle (CD-02-P) | SATISFIED |
| CO-N Enumeration | CO-5 Derivation Requirement (CD-03-P) | SATISFIED |
| Specific Succession Chain Design | Succession Pre-Designation Principle (CD-04-P) | SATISFIED |
| CIC/CAB Institutional Assignment | Interpretation/Adjudication Separation (CD-06-P) | SATISFIED (tight coupling noted) |
| Specific Appointment Assignments | Appointment Independence Principle (CD-07-P) | SATISFIED |
| ADR-2 Per-Function Independence Forms | CF-05-19 + AC-04-07 + ADR7-INV-02 | PROVISIONAL (governing Principle not yet EC-designated) |

### Output E — Trust Root Separation Candidates

| Element | OQ-38B05-05 Relevance | Status |
|---------|----------------------|--------|
| CD-10 Trust Root Structural Separation | **PRIMARY CANDIDATE** — mandatory; this is the candidate | Ambiguous; EscRule-04 mandatory |
| CD-01-P Non-Substitution | Contributing — Authenticity Root distinctness | Contributing element |
| CD-03-P CO-5 Derivation | Contributing — tri-root certification requirement | Contributing element |
| CD-05 Anti-Capture | Contributing — protects independence of each root | Contributing element |
| CD-09 ADR-2 Forms | Contributing — independence forms maintain structural independence | Contributing element (Form) |

### Output F — Tier Assignment Candidates

| Principle | Tier 3 Case | Tier 2 Case | Contested? |
|-----------|------------|------------|-----------|
| Anti-Capture (CD-05) | STRONG — challenge rights are Tier 3; anti-capture protects them | Governance rule | YES — refer CIC |
| Three Amendment Tiers (CD-08) | STRONG — amendment process itself is Protected Core item 5 | N/A (ambiguous primary) | YES — CIC required |
| Trust Root Separation (CD-10) | STRONG — multi-root attack without Tier 3 is unmitigated F-4 | Governance rule | YES — CIC required |
| Challenge Terminality (CD-02-P) | CANDIDATE — challenge rights are Tier 3 | Governance rule | Moderate |
| CO-5 Derivation (CD-03-P) | CANDIDATE — CO-3/Authenticity Root linkage | Governance certification rule | Moderate |
| Non-Substitution (CD-01-P) | LOW | Tier 2 appropriate | No |
| Succession Pre-Designation (CD-04-P) | CANDIDATE — TM-42 unconditional FAIL consequence | Tier 2 governance rule | Moderate |
| Interpretation/Adjudication Separation (CD-06-P) | CANDIDATE — CIC existence is Tier 3 | Tier 2 governance rule | Moderate |
| Appointment Independence (CD-07-P) | LOW | Tier 2 appropriate | No |

### Output G — Mandatory CIC Escalations

| Code | Escalation Rule | Constitutional Questions |
|------|----------------|-------------------------|
| CD-08 | EscRule-01 (Ambiguous; Legitimacy Root) | Three-tier structure as constitutional principle vs. form; tier assignment; reclassification procedure |
| CD-10 | EscRule-04 (Mandatory; OQ-38B05-05) | Is structural separation constitutionally required? Tier? Single provision or emergent? Current architecture sufficient? |
| CD-05 tier | (Tier dispute) | Is Anti-Capture Invariant Tier 3 or Tier 2? |
| CD-02-P tier | (Tier dispute) | Is Challenge Terminality part of Tier 3 challenge rights protection? |

### Output H — Misclassification Risk Register

| Code | False Form Risk | False Principle Risk | Risk Assessment |
|------|----------------|---------------------|----------------|
| CD-05 | CATASTROPHIC — TM-39/F-4 unmitigated; F-3 enabled | Negligible | FALSE FORM CRITICAL |
| CD-10 | CATASTROPHIC — F-4 unmitigated; multi-root collapse without Tier 3 | Moderate — trust root architecture ossified | FALSE FORM CRITICAL |
| CD-01-P | HIGH — TM-47/TM-19 operational; CO-5 fraud possible | Manageable ossification | FALSE FORM HIGH |
| CD-03-P | HIGH — CO-5 without authentication valid; TS-1 fraudulent | Manageable ossification | FALSE FORM HIGH |
| CD-04-P | HIGH — TM-43+TM-42 chain; governance deadlock | Manageable ossification | FALSE FORM HIGH |
| CD-08 | HIGH — OBS-38A06-SD1 enabled; self-destruction path open | Moderate — threshold ossification | FALSE FORM HIGH |
| CD-09 | LOW — governing Principle protects obligation | Moderate — form ossification | MANAGEABLE |
| CD-06-F | LOW — governing Principle protects separation obligation | Tight coupling concern | TIGHT COUPLING |

### Output I — Items Requiring ARB Ruling

| Item | Nature | Priority |
|------|--------|---------|
| CD-08 classification | Ambiguous → CIC → formal designation | HIGH |
| CD-10 classification | Ambiguous → CIC → OQ-38B05-05 ruling | CRITICAL (mandatory primary) |
| CD-05 tier assignment | Tier 3 vs. Tier 2 for Anti-Capture Invariant | HIGH |
| CD-02-P tier assessment | Tier 3 candidacy for Challenge Terminality | MEDIUM |
| CD-03-P tier assessment | Tier 3 candidacy for CO-5 Derivation | MEDIUM |
| CD-09 Principle Anchor | Governing Principle must be EC-designated before CD-09 constitutional standing is confirmed | MEDIUM |
| CD-06-F tight coupling | ARB to assess whether CIC/CAB form should be elevated to Tier 2 protection | MEDIUM |
| Extended candidate set | Deferred candidates (trust root governance bodies, specific tier thresholds, authority specifics) | DEFERRED to 38C-05b |

---

## Part N — OBS-38B06-05 Acknowledgment

```
OBS-38B06-05 ACKNOWLEDGMENT:
Classification completeness does not constitute classification
correctness. These classifications represent the exercise's best
constitutional assessment at the time of designation, grounded
in program evidence through Round 38B and ADR-1 through ADR-7.

Technical realization (38D+) and adversarial review may surface
cases where a Form-classified element should have been designated
Principle, or where a Principle boundary was drawn too broadly
or too narrowly. Such findings are expected, not failures.

Separability findings (CD-01, CD-02, CD-03, CD-04, CD-06, CD-07)
are particularly subject to challenge: the boundary between a
principle-component and a form-component is constitutionally
significant and may need refinement during technical realization.

All classifications in this document are provisional candidates.
Final designation requires 38C-06 ARB ruling.
OQ-38B05-05 is NOT resolved by this exercise.
OQ-38A05-02 is PROTECTED throughout.
```

---

## Part O — ARB Submission Statement

```
Round 38C-05 — Principle/Form Classification Exercise

SUBMITTED FOR ARB REVIEW

This exercise has:
  (1) Established a Classification Target Register (Part A)
  (2) Evaluated all 10 candidates from the 38C-04 Appendix
      using C-1 through C-11 under the approved framework
  (3) Produced separability findings for 6 candidates
      (CD-01, CD-02, CD-03, CD-04, CD-06, CD-07)
  (4) Confirmed conditional default for CD-05 (PRINCIPLE; Tier 3 candidate)
  (5) Produced Ambiguous designations with C-11 direction for
      CD-08 (EscRule-01) and CD-10 (EscRule-04 MANDATORY)
  (6) Produced all required outputs A through I

OQ-38B05-05 STATUS: NOT RESOLVED
  Trust Root Structural Separation (CD-10) is Ambiguous.
  C-11 directs toward Principle. EscRule-04 is mandatory.
  CIC must adjudicate before 38C-06 can issue final designation.

OQ-38A05-02 STATUS: PROTECTED THROUGHOUT
  OQ-38A05-02 proximity documented for CD-02-P and CD-03-P.
  Classification does not resolve OQ-38A05-02 in either direction.

38C01-INV-01: All outputs are hypotheses until ARB acceptance.
38C03-INV-01: No existing ADR invariant loses protection pending classification.
OBS-38B06-05: Classification completeness ≠ correctness.

Next: ARB review of this exercise
       → 38C-06 Classification Ruling
       → CIC adjudication of CD-08 and CD-10
       → OQ-38B05-05 Evaluation (mandatory primary requirement)
```

---

*Round 38C-05 — Principle/Form Classification Exercise — SUBMITTED FOR ARB REVIEW*
*Research Program: NRNA DDD Trustworthiness*
*Date: 2026-06-19*
*Framework: Round38C-04 (R1–R4 applied)*
*10 candidates evaluated; 9 principles or separable; 1 direct Principle; 2 Ambiguous*
*OQ-38B05-05 MANDATORY PRIMARY REQUIREMENT — NOT RESOLVED — CIC required*
*OQ-38A05-02 PROTECTED THROUGHOUT*
