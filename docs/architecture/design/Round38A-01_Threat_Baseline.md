# Round 38A-01 — Threat Modeling Baseline

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38A — Threat Validation
**Document:** 38A-01 of 6
**Status:** APPROVED WITH STRATEGIC CORRECTIONS
**Date:** 2026-06-16

**Predecessor Program Output:**
- ADR-1 — Authority Vocabulary and Authority Source Model — APPROVED
- ADR-2 — Independence Form per D43 Function — APPROVED
- ADR-3 — Evidence and Verifier Architecture — APPROVED
- ADR-4 — Audit Scope Authority Structure — SUBMITTED FOR ARB REVIEW
- ADR-5 — Challenge Architecture — APPROVED (Required Revisions Applied)
- ADR-6 — Certification Architecture — APPROVED (Required Revisions Applied)
- ADR-7 — GovernanceState Boundary Architecture — APPROVED WITH MINOR OBSERVATIONS
  Key inheritance: TM-01 through TM-06 carry-forward package; concentration chain (CP-1 → CP-2 → CO-5); OBS-ADR7-SS1 (Membership Assembly legitimacy unresolved); ADR7-INV-01 (suspension succession); ADR7-INV-02 (anti-capture)

**Governing Discipline:**
This document does NOT evaluate the architecture. It establishes the methodology, taxonomy, adversary capability model, failure definitions, survivability classifications, Master Threat Catalog, and Hidden Assumption Register that 38A-02 through 38A-06 will apply.

**Scope:** Constitutional and governance architecture threats only. Out of scope for the entire 38A series: APIs, databases, microservices, cryptographic algorithms, technology selections, deployment architecture.

---

## Part A — Mission Discipline

### A.1 Purpose

Round 38A is not a validation exercise. It is an adversarial review. The governing question is not:

> *"Does the architecture survive this threat?"*

The governing question is:

> *"Under what conditions does the architecture fail — and are those conditions reachable by a credible adversary?"*

38A-01 does not evaluate the architecture against threats. 38A-01 establishes the framework within which 38A-02 through 38A-05 will attempt to produce FAIL assessments — and 38A-06 will synthesize the findings honestly, including any FAIL findings.

### A.2 The Adversarial Mandate

The burden of proof is reversed for Round 38A relative to all preceding rounds.

| Prior rounds | "We believe the architecture satisfies constraint AC-N because..." |
|---|---|
| **Round 38A** | **"We believe the architecture FAILS under condition X because..."** |

This reversal is not rhetorical. It changes which direction evidence is required. To assert **Survives**, the analyst must demonstrate survival with positive evidence. To assert **Fails**, the analyst must demonstrate a credible failure path. The default classification — when neither can be established — is **Assessment Deferred**, not **Survives**.

Roles for the entire 38A series:
- Senior Election Security Architect
- Constitutional Governance Architect
- Threat Modeling Specialist
- DDD Architect
- **Adversarial Reviewer (primary role — attempt to break the architecture)**

### A.3 Scope Boundaries

**In scope:** Constitutional threats, governance capture threats, authority-level threats, evidence manipulation threats, election-integrity threats, technical/infrastructure threats — evaluated at the constitutional architecture level only.

**Out of scope:** API design, database schema, microservices decomposition, cryptographic algorithm selection, technology stack, deployment topology.

**Scope guard:** If a threat requires specific technical implementation to evaluate, the finding is recorded as **Requires Technical Architecture** and deferred to Round 38B. It is not classified as Survives.

### A.4 The Zero-FAIL Hypothesis

If Round 38A produces zero FAIL findings, this result must be treated as a hypothesis to challenge, not a conclusion to accept. The null hypothesis for Round 38A is:

> *"The architecture has at least one failure mode under some credible adversary and condition combination."*

A zero-FAIL result should trigger the following diagnostic questions before acceptance:

1. **Threat model completeness:** Are there threat classes that were not modeled?
2. **Assumption coverage:** Are there hidden assumptions whose violation was not evaluated?
3. **Adversary capability conservatism:** Were Adversary E (State-Level) scenarios excluded or softened?
4. **Classification weakness:** Is "Survives with Mitigation" being used to reclassify genuine failures?
5. **Scope exclusion:** Are failures at the boundary of constitutional scope being treated as out-of-scope rather than as scope failures?

38A-06 must address the Zero-FAIL hypothesis explicitly if no FAIL findings emerge from 38A-02 through 38A-05.

---

## Part B — Threat Taxonomy

### B.1 Threat Classification Framework

All threats in Round 38A are classified into seven threat classes. This classification determines which 38A sub-document is the primary evaluator.

| Class | Name | Primary Evaluator |
|---|---|---|
| **Class 1** | Constitutional Threats | 38A-02 |
| **Class 2** | Governance Capture Threats | 38A-02 |
| **Class 3** | Authority Capture Threats | 38A-03 |
| **Class 4** | Evidence Manipulation Threats | 38A-02, 38A-03 |
| **Class 5** | Election Security Threats | 38A-04 |
| **Class 6** | Technical/Infrastructure Threats | 38A-05 |
| **Class 7** | Composite/Multi-Vector Threats | 38A-06 (synthesis) |

### B.2 Class Definitions

**Class 1 — Constitutional Threats:**
Attacks that target the ElectionConstitution itself, its amendment process, its legitimacy source (Membership Assembly), or its role as CP-1 in the concentration chain. Success corrupts the constitutional standard that all other authority aggregates depend on.

**Class 2 — Governance Capture Threats:**
Attacks that capture a governance body — CertificationAuthority, ChallengeAdjudicationBody, GovernanceAuthority — without necessarily corrupting the constitutional document. The EC may remain intact while the bodies that enforce it are compromised.

**Class 3 — Authority Capture Threats:**
Attacks that compromise individual or coalition D43 authority aggregates. Includes single-authority, dual-authority, and concentration-chain coalition attacks.

**Class 4 — Evidence Manipulation Threats:**
Attacks on the three-stratum evidence model (Presence, Completeness, Authenticity). Distinguished from Class 3: evidence threats attack the audit record, not the authorities themselves.

**Class 5 — Election Security Threats:**
Classical election attacks: vote buying, coercion, ballot stuffing, selective disenfranchisement, result manipulation. Evaluated at the constitutional architecture level — does the constitutional architecture detect, prevent, or survive these attacks?

**Class 6 — Technical/Infrastructure Threats:**
Insider manipulation, infrastructure compromise, evidence deletion, availability attacks, supply-chain attacks, state-level infrastructure attacks.

**Class 7 — Composite/Multi-Vector Threats:**
Coordinated attacks that combine multiple threat classes simultaneously. These are the most dangerous scenarios because constitutional architecture may survive each component individually but fail under combination.

---

## Part C — Adversary Capability Model

### C.1 Five Adversaries

**Adversary A — Malicious Voter**
- Motivation: Manipulate election outcome to favor preferred candidate; commit electoral fraud for personal benefit; test system for external adversary
- Capabilities: Access to own voting credentials; limited knowledge of constitutional architecture; potential coordination with small group of other voters
- Access level: Voter-tier only; no authority aggregate access
- Constraints: Cannot modify GovernanceState, evidence records, or certification; cannot prevent other voters from voting
- Primary threat classes: Class 5 (election security — vote buying from within, chain voting, credential sharing)

**Adversary B — Malicious Election Official**
- Motivation: Influence specific election outcome; protect incumbents; suppress opposition voter registration; manipulate candidate selection
- Capabilities: Administrative access to election management functions; knowledge of constitutional architecture's operational procedures; ability to influence evidence creation and record-keeping
- Access level: Operational tier; limited authority aggregate influence; may have AuditExecutionAuthority access
- Constraints: Cannot unilaterally amend ElectionConstitution; requires collusion for multi-authority attacks; actions may be detectable through audit trails
- Primary threat classes: Class 4 (evidence manipulation), Class 5 (election security from within)

**Adversary C — Compromised Authority**
- Motivation: One of the seven D43 authority aggregates has been captured through appointment manipulation, financial dependency, external pressure, or long-term infiltration; adversary now controls the authority's function
- Capabilities: Full constitutional authority of the captured aggregate; ability to issue decisions that appear constitutionally legitimate; knowledge of constitutional constraints and procedures
- Access level: Authority tier; full control of captured aggregate's D43 function
- Constraints: Cannot unilaterally capture other authorities; L-4 revocation from EC still applies if capture is detected; other authorities may challenge decisions
- Primary threat classes: Class 2 (governance capture), Class 3 (single-authority capture), Class 4 (evidence manipulation if captured authority is audit-related)

**Adversary D — Colluding Authorities**
- Motivation: Multiple captured or colluding authority holders coordinate to entrench position, suppress electoral opposition, or manufacture electoral outcomes; may include coordinated capture of ElectionConstitution amendment process
- Capabilities: Combined capabilities of multiple authority aggregates; ability to create constitutional deadlock or coverage gaps; coordination capability allows simultaneous action across multiple constitutional layers
- Access level: Multi-authority tier; potentially including concentration chain (CP-1 + CP-2)
- Constraints: Requires coordinated compromise of multiple appointment/election processes; increased detection probability as coalition size grows; MA revocation authority (if MA is not captured) may apply
- Primary threat classes: Class 1 (constitutional), Class 3 (coalition authority capture), Class 6 (concentration chain capture), Class 7 (composite)

**Adversary E — State-Level Adversary**
- Motivation: Undermine electoral legitimacy of a rival political organization; install preferred leadership; demonstrate capability for strategic purposes; systematic subversion of democratic process
- Capabilities: Nation-state or equivalent resources; ability to target multiple constitutional layers simultaneously; technical capabilities for infrastructure compromise; long-term infiltration and social engineering capabilities; legal and regulatory pressure on ExternalOrganization authorities (CertificationAuthority Option C)
- Access level: Systemic; can apply simultaneous pressure across all constitutional layers including infrastructure, appointments, and social legitimacy
- Constraints: High visibility as campaign extends; international implications may deter; constitutional architecture's legitimacy from democratic source (MA) creates reputational cost for overt attack
- Primary threat classes: Class 1, Class 3, Class 6, Class 7; may operate all classes simultaneously

### C.2 Adversary × Threat Class Access Matrix

| Threat Class | Adversary A | Adversary B | Adversary C | Adversary D | Adversary E |
|---|---|---|---|---|---|
| Class 1 — Constitutional | ✗ | Limited (amendment pressure) | Limited (single authority) | ✓ (coalition) | ✓ (full) |
| Class 2 — Governance Capture | ✗ | Partial (operational) | ✓ (own authority) | ✓ (multi-authority) | ✓ (full) |
| Class 3 — Authority Capture | ✗ | Partial | ✓ (own authority) | ✓ (coalition) | ✓ (full) |
| Class 4 — Evidence Manipulation | ✗ | ✓ (operational access) | Partial (authority-dependent) | ✓ | ✓ |
| Class 5 — Election Security | ✓ | ✓ | Partial | ✓ | ✓ |
| Class 6 — Technical | ✗ | ✓ (insider) | Partial | ✓ | ✓ |
| Class 7 — Composite | ✗ | ✗ | ✗ | ✓ (requires D+ capability) | ✓ |

---

## Part D — Failure Definitions

### D.1 Constitutional Failure

**Definition:** The architecture fails to enforce a guarantee that ElectionConstitution declares binding. The constitutional promise is not kept, regardless of whether the failure is detected.

**Indicators:**
- A challenge is filed by a standing holder but cannot reach adjudication through constitutional mechanisms
- CO-5 (Election Validity) is issued despite CO-2, CO-3, or CO-4 not being independently satisfied (ADR6-INV-01 violated)
- An authority aggregate operates outside its constitutional mandate without L-4 revocation capability being triggered
- Standing classes defined in EC cannot exercise the standing granted to them

**Distinguishing feature:** Constitutional failure is about the rules not being enforced — not about the rules being violated.

### D.2 Governance Failure

**Definition:** A governance body (authority aggregate) fails to operate within constitutional constraints AND the architecture has no detection or remediation mechanism with a probability of succeeding before terminal harm occurs.

**Indicators:**
- CertificationAuthority issues a certificate that does not satisfy ADR6-INV-01 and no challenge pathway exists or succeeds before the certificate becomes terminal (TS-1 — challenge window closed)
- ChallengeAdjudicationBody systematically dismisses valid challenges and the dismissal pattern is not detectable within the challenge window
- GovernanceState records are corrupted and the corruption is constitutionally indistinguishable from authentic state (OBS-ADR7-03)

**Distinguishing feature:** Governance failure requires both the governance body's failure AND the absence of a working remediation mechanism. A failure that is detected and remedied within the constitutional window is a near-failure, not a governance failure.

### D.3 Election Failure

**Definition:** The architecture fails to protect the integrity of the election outcome — votes are miscounted, suppressed, manufactured, or linked to voters in ways that violate anonymity — AND this failure either cannot be detected through the constitutional audit architecture or cannot be remedied within the constitutional window.

**Indicators:**
- Vote-buying is enabled through verification mechanisms that allow voters to prove their selections to third parties
- Selective disenfranchisement cannot be detected through enrollment audit trails
- Ballot stuffing produces audit evidence indistinguishable from authentic vote records
- Vote anonymity is breached by linking voting codes to voter identities through constitutional architecture components

**Distinguishing feature:** Election failure must affect the outcome or the outcome's legitimacy — individual voter misbehavior that does not affect results is a threat but not a failure.

### D.4 Technical Failure

**Definition:** An infrastructure compromise succeeds in corrupting architecture guarantees in ways that cannot be reversed or authenticated within the constitutional framework.

**Indicators:**
- GovernanceState records are altered and the constitutional architecture cannot distinguish authentic from corrupted records (OBS-ADR7-03)
- Audit evidence records are deleted before the certification scope is evaluated — CO-2 cannot be satisfied because evidence does not exist
- Evidence authenticity reference standard (AC-31) is itself compromised, invalidating all authenticity assessments

**Distinguishing feature:** Technical failure is about the substrate being compromised, not the authority being captured. A compromised database that destroys evidence is a technical failure; a compromised authority that selects which evidence to submit is a governance failure.

### D.5 Composite Failure

**Definition:** Multiple failure types occurring simultaneously or in sequence, where each individual failure creates conditions that enable or prevent remediation of the others.

**Critical composite pattern:** Constitutional Failure + Governance Failure = no constitutional mechanism exists AND the governance body that would enforce it is captured. This combination is designed to be irrecoverable.

### D.6 Non-Failure Boundary

The following conditions are **not** classified as architectural failures in Round 38A:

- An adversary succeeds in a specific election but the architecture provides constitutional mechanisms for detecting and challenging the result (Detection exists → not a failure of the constitutional architecture, even if the specific election is compromised)
- An operational error occurs that does not exploit an architectural flaw
- A threat requires capabilities exceeding Adversary E (State-Level) with unlimited resources and no detection risk
- A failure that requires the architecture to have been incorrectly implemented — implementation failures are not constitutional architecture failures

---

## Part E — Survivability Classifications

### E.1 Classification Framework

Every threat evaluated in 38A-02 through 38A-05 must be assigned one of five survivability classifications:

| Classification | Code | Definition |
|---|---|---|
| **Survives** | S | Architecture maintains constitutional guarantees under this threat without additional conditions. Evidence of survival must be positive, not merely the absence of a demonstrated failure path. |
| **Survives with Detection** | S-D | Architecture maintains guarantees AND generates constitutionally recoverable signals when the threat is active. Detection enables challengers or remediation bodies to act. The detection mechanism is part of the constitutional architecture, not a separate operational control. |
| **Survives with Mitigation** | S-M | Architecture maintains guarantees ONLY IF specific pre-deployed mitigations are in place. The mitigation must be a constitutional requirement (not an optional enhancement). Must name the specific mitigation and the constitutional instrument that requires it. |
| **Conditional Fail** | C-F | Architecture fails under specific adversary capability thresholds or specific condition combinations. Must name the threshold and the conditions. This is distinct from "Fails" because it bounds the failure domain. |
| **Fails** | F | Architecture cannot maintain constitutional guarantees under this threat. Evidence of failure is required. The failure must be reachable by the named adversary under credible conditions. |

**Assessment deferred:** When evidence for either survival or failure cannot be established from the constitutional architecture alone, the finding is **Assessment Deferred (A-D)** — not classified as Survives. A-D findings require either (a) resolution in technical architecture (Round 38B) or (b) explicit ARB decision to reclassify.

**Note on Conditional Fail:** The ARB's governing instructions specified four survivability classifications (Survives / Survives with Detection / Survives with Mitigation / Fails). Conditional Fail is a fifth classification proposed in this document. Its purpose is to distinguish "fails only when the adversary reaches a specific capability threshold" from "always fails regardless of adversary capability" — a distinction that is analytically important for authorization decisions (some C-F conditions may be acceptable risk tolerances; all F conditions require architectural response). The ARB is asked to accept or reject Conditional Fail as part of the 38A-01 authorization decision. If rejected, C-F findings will be reclassified as F.

### E.2 Determination Criteria

For **Survives:** Analyst must identify the specific constitutional mechanism that defeats the threat and demonstrate that the mechanism cannot be circumvented by the named adversary within their capability profile.

For **Survives with Detection:** Analyst must identify both (a) the survival mechanism and (b) the detection mechanism. The detection mechanism must be part of the constitutional architecture and must produce recoverable evidence within the challenge window.

For **Survives with Mitigation:** Analyst must name (a) the specific mitigation required, (b) the constitutional instrument that mandates it, and (c) what happens if the mitigation is not deployed.

For **Conditional Fail:** Analyst must specify (a) the threshold at which failure occurs, (b) the adversary capability level required, and (c) the conditions under which the threshold is reached.

For **Fails:** Analyst must demonstrate a credible failure path with positive evidence. Speculation is not sufficient. The failure path must be reachable by a named adversary at a named capability level.

### E.3 Composite Survivability

When a composite threat (Class 7) is evaluated, the composite survivability classification is at most as strong as the weakest component. If TM-A is S and TM-B is C-F, the composite (TM-A + TM-B) is at most C-F.

Exception: if the survival mechanism for TM-A directly prevents TM-B, the composite may be classified independently.

---

## Part F — Master Threat Catalog

### F.1 Threats Carried from ADR-7 (TM-01 through TM-06)

| ID | Name | Class | Adversary | Primary 38A Document |
|---|---|---|---|---|
| TM-01 | Constitution Capture | Class 1 | D, E | 38A-02 |
| TM-02 | Certification Capture | Class 2 | C, D, E | 38A-02, 38A-03 |
| TM-03 | GovernanceState Corruption | Class 4 | B, C, E | 38A-02 |
| TM-04 | Challenge Adjudication Capture | Class 2 | C, D, E | 38A-02, 38A-03 |
| TM-05 | Standing Manipulation | Class 2 | C, D | 38A-02 |
| TM-06 | Concentration Chain Capture | Class 7 | D, E | 38A-03, 38A-06 |

**TM-01 Baseline Refinement:** TM-01 must evaluate both overt capture (adversary obtains constitutional amendment authority) and procedural capture (adversary conducts procedurally valid amendments that are substantively captured — valid in form, corrupted in substance). The harder case is the procedurally valid capture, because no constitutional mechanism detects it.

**TM-06 Baseline Refinement:** TM-06 is a Class 7 composite of TM-01 + TM-02 simultaneously. 38A-03 must evaluate the partial capture scenario (adversary captures one of the two concentration points but not both) to determine whether partial capture also constitutes a failure condition.

### F.2 ARB-Authorized New Threats (TM-07 through TM-09)

| ID | Name | Class | Adversary | Primary 38A Document |
|---|---|---|---|---|
| TM-07 | Membership Capture | Class 1 | D, E | 38A-02 |
| TM-08 | Silent Certification Failure | Class 2 | C, D, E | 38A-02 |
| TM-09 | Constitutional Drift | Class 1 | D, E (long-horizon) | 38A-02, 38A-06 |

**TM-07 Description:** Adversary infiltrates Membership Assembly through procedurally valid participation — astroturfing, representative capture, or vote manipulation within the MA — and uses MA authority to establish an ElectionConstitution that serves adversary interests. Risk: if MA legitimacy is the source-of-source (OBS-ADR7-SS1 — unresolved), MA capture corrupts the entire legitimacy chain without any architectural mechanism detecting it.

**TM-08 Description:** CertificationAuthority issues certification that appears to satisfy CO-2 + CO-3 + CO-4 but actually does not — through honest error, negligent evaluation, or deliberate misrepresentation — in a way that challengers cannot detect within the challenge window. Critical distinction from TM-02: TM-02 is intentional capture; TM-08 is constitutional failure that produces a structurally false CO-5 without overt adversary action. This is a structural failure mode, not a capture mode.

**TM-08 Failure Mode Expansion (ARB Correction):** Silent Certification Failure is not a single failure mode — it is a family of structurally distinct failure modes, each with different detection paths and adversary requirements:

| Mode | Description | Adversary Required | Detection Path |
|---|---|---|---|
| TM-08-A | CA issues CO-5 without reviewing underlying evidence (original description) | None/C | Named Attestation → CO-2 challenge if evidence exists |
| TM-08-B | Certification review impossible — evidence not accessible or in unusable format | None | Structural impossibility; no constitutional evidence access mechanism |
| TM-08-C | Review suppressed — CA under pressure not to review adverse evidence | C/D | Detectable if suppression observable; SA-02 dependency |
| TM-08-D | Review abandoned — review begins but fails to complete within window | None/B | Detectable if review audit trail exists; OA-02 dependency |

**Critical distinction:** TM-08-B (Review Impossible) requires no adversary. If the constitutional architecture does not specify evidence access mechanisms for CA, TM-08-B is a baseline structural failure mode not conditioned on adversary capability. This connects to OA-01 (Challenger Evidence Access — unspecified) and OA-02 (Challenge Window Practical Sufficiency — under-specified).

**TM-09 Description:** ElectionConstitution evolves through legitimate, non-captured amendment processes over time in ways that gradually erode constitutional protections. No single amendment is objectionable; the cumulative drift removes safeguards. A long-horizon Class 7 composite where each individual change is legitimate.

### F.3 Baseline-Discovered Threats (TM-10 through TM-19)

The following threats were identified during baseline analysis as gaps not covered by TM-01 through TM-09.

| ID | Name | Class | Adversary | Primary 38A Document |
|---|---|---|---|---|
| TM-10 | Phase Lock Attack | Class 2 | C, D | 38A-02 |
| TM-11 | Succession Vacancy Attack | Class 3 | B, C, D | 38A-03 |
| TM-12 | Challenge Window Attrition | Class 2 | B, C, D | 38A-02 |
| TM-13 | Constitutional Ambiguity Exploitation | Class 1 | C, D | 38A-02 |
| TM-14 | Authority Self-Amendment | Class 3 | C | 38A-03 |
| TM-15 | Evidence Completeness Attack | Class 4 | B, C | 38A-02 |
| TM-16 | Evidence Authenticity Confusion | Class 4 | C, E | 38A-03 |
| TM-17 | Audit Scope Exclusion | Class 4 | C (AuditScopeAuthority) | 38A-03 |
| TM-18 | Two-Authority Coalition | Class 3 | D | 38A-03 |
| TM-19 | AC-31 Reference Standard Capture | Class 4 | D, E | 38A-03 |

**TM-10 — Phase Lock Attack:** Adversary prevents GovernanceState from recording a valid transition from one constitutional phase to the next — keeping the election in the voting phase past its authorized window, preventing certification phase from opening, or preventing challenge window from closing. The architecture's GovernanceState corruption detection (OBS-ADR7-03) is the primary mitigation target. Phase lock is distinct from TM-03 (GovernanceState Corruption) because TM-10 does not corrupt records — it prevents valid transitions from occurring.

**TM-11 — Succession Vacancy Attack:** Adversary systematically eliminates, incapacitates, or compromises pre-designated succession designees from ADR7-INV-01, creating an authority vacuum when a suspension is triggered. ADR7-INV-01 requires pre-designation — but does not protect the designees themselves from compromise.

**TM-12 — Challenge Window Attrition:** Adversary uses procedural mechanisms — challenge filing delays, evidence access delays, standing verification challenges — to prevent material challenges from being completed before the window closes. Distinct from TM-05 (Standing Manipulation) which restricts who has standing; TM-12 allows standing but prevents exercise of standing within the window.

**TM-13 — Constitutional Ambiguity Exploitation:** Adversary exploits ambiguous, silent, or conflicting provisions in ElectionConstitution to claim authorities or override constraints not explicitly prohibited. A constitutional architecture with ambiguous language is vulnerable to adversarial interpretation without requiring amendment. The absence of a constitutional interpretation authority creates this gap.

**TM-14 — Authority Self-Amendment:** Authority aggregate illegitimately expands its own constitutional scope through operational precedent — defining terms broadly, issuing decisions that implicitly extend authority — without formal constitutional amendment. ADR7-INV-02 (anti-capture invariant) governs self-granted standing, but not scope self-extension.

**TM-15 — Evidence Completeness Attack:** Adversary engineers absence of evidence rather than forging evidence. CO-2 (Evidence Completeness) cannot be satisfied because required evidence was never created, was deleted before the audit scope activation, or was created in formats that cannot satisfy scope requirements. Distinct from TM-03 (GovernanceState Corruption) because the attack targets the evidence itself, not the state machine.

**TM-16 — Evidence Authenticity Confusion:** Adversary creates multiple plausible versions of audit evidence with contradictory content. The AC-31 reference standard comparison produces ambiguous results — the standard cannot authenticate one version as the original — rather than a clean authentication failure. Ambiguity is worse than failure because it prevents a definitive finding.

**TM-17 — Audit Scope Exclusion:** Adversary with influence over AuditScopeAuthority engineers Tier 1/Tier 2 scope definitions that systematically exclude the specific evidence most relevant to detecting the adversary's manipulation. The scope appears valid; the exclusion is deliberate and invisible.

**TM-18 — Two-Authority Coalition:** Adversary captures exactly two D43 authority aggregates. 38A-03 must evaluate which two-authority combinations create constitutional coverage gaps — where the two captured authorities together can produce an unconstitutionally valid election result without a remaining authority being able to detect or challenge it.

**TM-19 — AC-31 Reference Standard Capture:** The independently governed reference standard required by AC-31 for evidence authenticity verification (OBS-ADR3-01 — requires own legitimacy chain) is itself captured, corrupted, or made inaccessible. If AC-31 fails, the Authenticity Stratum of every election's evidence package cannot be independently verified. Note: OBS-ADR3-01 flagged that the AC-31 reference standard's own legitimacy chain is unresolved — this is a concentration point that has not been analyzed.

### F.3a ARB-Directed Additions (TM-34 through TM-36 + TM-39)

*Added per ARB Strategic Corrections — TM-34/35/36 APPROVED 2026-06-16; TM-39 APPROVED 2026-06-17 (38A-03 SC2)*

| ID | Name | Class | Adversary | Primary 38A Document |
|---|---|---|---|---|
| TM-34 | Information Environment Capture | Class 7 | D, E | 38A-02, 38A-06 |
| TM-35 | Legitimacy Narrative Attack | Class 1 | D, E | 38A-02, 38A-06 |
| TM-36 | Observer Capture | Class 2 | C, D | 38A-03, 38A-06 |
| TM-39 | Independence Illusion Attack | Class 2 (Structural) | D, A | 38A-03 (C.9), 38A-06 |

**TM-34 — Information Environment Capture:** Adversary controls or saturates the information environment surrounding the election — through fake evidence publication, evidence flooding, disinformation campaigns, observer confusion campaigns, selective publication, or manufactured distrust — so that constitutional actors (CA, ChallengeAdjudicationBody, standing challengers, and members of the Membership Assembly) cannot reliably evaluate the genuine constitutional record. The constitutional architecture may be structurally sound while the information environment makes its outputs unverifiable in practice. Particularly relevant for diaspora elections where information asymmetry is structurally high. Attack surface: connects to TM-08-B (review impossible when evidence cannot be separated from flood) and SA-02 (challenger willingness under coordinated confusion).

**TM-35 — Legitimacy Narrative Attack:** Adversary targets the perceived legitimacy of constitutional actors — Membership Assembly, ElectionConstitution, CertificationAuthority — through sustained narrative campaigns, without requiring any structural compromise of those actors. The attacks are directed at AA-01 (MA legitimacy is the unresolved source-of-source), AA-02 (EC uniqueness and authority), and CO-5 acceptance (certification legitimacy). Distinct from information warfare (TM-34) because TM-35 attacks legitimacy of the institution rather than the intelligibility of the evidence. Constitutional architecture can produce a structurally valid CO-5 that no relevant constituency accepts as legitimate. This is a failure mode the architecture cannot address at the constitutional level.

**TM-36 — Observer Capture:** Constitutional observers — entities with standing to audit the election process, file challenges, or produce independent reports — are captured, compromised, coordinated, or deterred. The architecture relies on observer independence (SA-02) and standing diversity (GA-04), but does not specify how observer capture is detected or remediated. Attack forms: direct compromise (observer is adversary agent), coordinated observer reports (observers independently reach adversary-preferred conclusions through information manipulation), chilling effects (observers self-censor under threat). If observer capture is undetectable, the entire standing-based challenge architecture loses its independence guarantee. Note: if observers are the only challengers with standing, observer capture collapses the challenge architecture entirely.

**TM-39 — Independence Illusion Attack:** ADR-2 specifies constitutional independence forms for all seven D43 authority aggregates but does not specify operational independence requirements. Constitutionally independent authorities may share infrastructure, personnel, funding, or appointment chains without violating ADR-2. TM-39 attacks the gap between constitutional independence (what ADR-2 specifies) and operational independence (what genuine independence requires). Attack forms: shared IT infrastructure creating a single operational compromise point that simultaneously corrupts multiple constitutionally independent authorities; overlapping committee personnel providing de facto coordination between independent authorities; shared appointment chains creating indirect concentration; shared funding creating financial leverage across independent authorities. Constitutional consequence: independence designations are present on paper; effective concentration exists in practice. No constitutional challenge mechanism exists to address operational concentration because Named Attestation evaluates whether authorities applied constitutional standards correctly — not whether they were genuinely operationally independent. Classification: F at Adversary D (design-level — if operational concentration is designed into the structure, the independence designations are constitutively inadequate); C-F at Adversary A (single insider exploiting shared infrastructure requires adversary action). Potential Gap 6: absence of operational independence requirements may constitute a sixth structural gap alongside the five established in G.5 — ARB ruling requested (OQ-38A03-05). *(ARB-Authorized: 38A-03 SC2, 2026-06-17)*

### F.4 Election Security Threats (TM-20 through TM-27)

| ID | Name | Class | Adversary | Primary 38A Document |
|---|---|---|---|---|
| TM-20 | Vote Buying | Class 5 | A, B | 38A-04 |
| TM-21 | Voter Coercion | Class 5 | A, B, D | 38A-04 |
| TM-22 | Chain Voting / Carousel Attack | Class 5 | A, B | 38A-04 |
| TM-23 | Selective Disenfranchisement | Class 5 | B, C | 38A-04 |
| TM-24 | Ballot Stuffing | Class 5 | B, C | 38A-04 |
| TM-25 | Result Manipulation | Class 5 | B, C | 38A-04 |
| TM-26 | Verification Abuse | Class 5 | A, B | 38A-04 |
| TM-27 | Voter Credential Theft | Class 5 | A, B, E | 38A-04 |

### F.5 Technical and Infrastructure Threats (TM-28 through TM-33)

| ID | Name | Class | Adversary | Primary 38A Document |
|---|---|---|---|---|
| TM-28 | Insider Manipulation | Class 6 | B | 38A-05 |
| TM-29 | Infrastructure Compromise | Class 6 | E | 38A-05 |
| TM-30 | Evidence Deletion | Class 6 | B, E | 38A-05 |
| TM-31 | Availability Attack | Class 6 | D, E | 38A-05 |
| TM-32 | Supply Chain Attack | Class 6 | E | 38A-05 |
| TM-33 | State-Level Coordinated Attack | Class 7 | E | 38A-05, 38A-06 |

### F.5a — 38A-04 Authorized Additions (TM-40 through TM-44)

*Added per 38A-04 deep evaluation — SUBMITTED FOR ARB REVIEW 2026-06-17. These threats are newly-named structural and operational failures identified during the deep evaluation of TM-10, TM-11, and operational capacity assumptions.*

| ID | Name | Class | Adversary | Primary 38A Document |
|---|---|---|---|---|
| TM-40 | GovernanceState Corroboration Absence | Class 2 (Structural) | None required | 38A-04 B.2, 38A-06 |
| TM-41 | Phase Boundary Ambiguity | Class 2 | C, D, None | 38A-04 B.3, 38A-06 |
| TM-42 | Operational Deadlock | Class 5 (Structural) | None required / D, E | 38A-04 C.2, 38A-06 |
| TM-43 | Successor Exhaustion Attack | Class 5 | B, C, D | 38A-04 C.3, 38A-06 |
| TM-44 | Temporal Concentration | Class 5 (Structural) | None required / any | 38A-04 D.1, 38A-06 |

**TM-40 — GovernanceState Corroboration Absence:** The explicit naming of AA-07's failure mode. The architecture designates GovernanceState as the sole authoritative phase record but specifies no corroboration mechanism. GovernanceState is constitutionally self-certifying — a corrupted, locked, or fabricated GovernanceState record is constitutionally identical to an accurate one. Named Attestation challenges evaluate compliance with what GovernanceState says; they cannot override GovernanceState based on external evidence alone. TM-40 is a structural amplifier for all GovernanceState-dependent threats: TM-03 (Corruption), TM-10 (Phase Lock), TM-41 (Phase Boundary Ambiguity). Not a standalone FAIL but raises all GovernanceState threat classifications. *(38A-04 B.2)*

**TM-41 — Phase Boundary Ambiguity:** Constitutional phases have completion requirements, but ElectionConstitution does not specify with sufficient precision what constitutes a complete and sufficient phase transition. Adversary or dispute can claim different constitutional phase status interpretations. Gap 4 (no Constitutional Interpretation Authority) prevents authoritative resolution of phase boundary disputes. GovernanceAuthority has effective discretion in determining phase completion — a TM-14 analogue at the phase level. Classification: C-F when (a) phase boundaries ambiguous AND (b) dispute arises AND (c) Gap 4 prevents resolution. Conditions (b) and (c) are structurally created. *(38A-04 B.3)*

**TM-42 — Operational Deadlock:** The architecture specifies no minimum operational capacity for any D43 authority aggregate. No reconstitution mechanism exists when an authority is suspended and its succession chain is exhausted. When multiple authorities are simultaneously suspended/exhausted, the election cannot proceed constitutionally. Unique features: (1) no adversary required for structural failure path — natural disasters, organizational failures, technical disruptions can trigger Complete Deadlock; (2) no constitutional floor — the architecture has no minimum that must be preserved for the election process to be constitutional; (3) Complete Deadlock prevents constitutional actors who would authorize recovery from operating. **Classification (ARB corrected, 38A-04-ARB-Review 2026-06-17): Complete Deadlock (all authorities + successors exhausted) = F (UNCONDITIONAL) — first unconditional non-capture FAIL in the program. Partial Deadlock (one or more authorities) = C-F → F.** Relationship to TM-08-B: TM-08-B could be addressed by a void→rerun procedure because CA and CAB remain functional to invoke it; TM-42 Complete Deadlock cannot be addressed by any rerun procedure because the constitutional actors required to invoke recovery are themselves unavailable. OQ-38A04-01 RESOLVED. *(38A-04 C.2; 38A-04-ARB-Review Part B)*

**TM-43 — Successor Exhaustion Attack:** Adversary systematically identifies and incapacitates designated successors before triggering a primary authority suspension. Succession architecture protects primary authorities (TM-04, TM-02, etc.) but provides no constitutional protection for designated successors. Successor pre-designations may be discoverable; successors are private individuals or organizations. When the primary authority is suspended after successor exhaustion, TM-42 (Operational Deadlock) immediately results. TM-43 is the adversarial path to TM-42. Classification: C-F → F (via TM-42). *(38A-04 C.3)*

**TM-44 — Temporal Concentration:** The constitutional architecture's sequential phase structure creates mandatory time-windows where any disruption produces permanent constitutional consequence. Constitutional windows have no minimum duration, no extension mechanism, no disruption tolerance, and no force majeure provisions. Unlike geographic or authority concentration (distributable across multiple paths), temporal concentration is irreversible — a missed window cannot be retrospectively filled. Applies to: voting window, evidence submission window, challenge window, certification window, succession activation window. Classification: C-F approaching F — zero constitutional disruption tolerance; non-adversarial triggers (technical failures, natural events) can produce constitutional failure. Interaction with TM-12: absence of minimum window duration makes TM-12 (Challenge Window Attrition) feasible with dramatically lower adversary capability than originally assessed. ARB Correction C-3 (38A-04-ARB-Review): ADR6-CONSTRAINT-01 provides partial mitigation for TM-44 on the challenge window only (C-F for that window); all other windows remain C-F approaching F. *(38A-04 D.1; 38A-04-ARB-Review C-3)*

### F.5b — 38A-05 Authorized Additions (TM-45 through TM-49)

*Added per 38A-05 evidence and authenticity chain evaluation — APPROVED WITH CORRECTIONS 2026-06-17. These threats address the Authenticity Root (AC-31), the certification dependency chain (AC-31 → CO-3 → CO-4 → CO-5), and evidence forking/disagreement scenarios. ARB Corrections applied: TM-46 reclassified from C-F → F to C-F (Availability Catastrophe); TM-49 downgraded from C-F to Candidate Threat.*

| ID | Name | Class | Adversary | Primary 38A Document |
|---|---|---|---|---|
| TM-45 | Authenticity Root Ambiguity | Class 2 (Structural) | None required / C, D | 38A-05 C.2, 38A-06 |
| TM-46 | Authenticity Root Succession Failure [C-F — Availability Catastrophe; ARB Correction] | Class 2 (Structural) | None required / B, C, D | 38A-05 C.3, 38A-06 |
| TM-47 | Certification Chain Self-Reference Exploitation | Class 3 (Evidence) | C, D (requires TM-19) | 38A-05 D.2, 38A-06 |
| TM-48 | Independent Reference Disagreement | Class 2 (Structural) | D, E | 38A-05 E.2, 38A-06 |
| TM-49 | Evidence Set Incompatibility [Candidate Threat; ARB Correction] | Class 3 (Evidence) | B, C, D | 38A-05 E.1, 38A-06 |

**TM-45 — Authenticity Root Ambiguity:** AC-31 (the reference standard for CO-3 evaluation) is ambiguous — imprecisely specified in a way that different evaluators reach incompatible CO-3 conclusions for the same evidence. Not capture (TM-19), not unavailability (TM-46), but definitional ambiguity. Structural conditions currently met: Gap 5 (no governance body to clarify), Gap 4 (no interpretation authority). Critical asymmetry: ADR6-CONSTRAINT-01 specifies a minimum precision requirement for the challenge window; no equivalent minimum clarity standard exists for AC-31. TM-45 is more dangerous than TM-41 (Phase Boundary Ambiguity) because ambiguity here corrupts the reference standard against which challenges themselves are evaluated. Classification: C-F. *(38A-05 C.2)*

**TM-46 — Authenticity Root Succession Failure:** AC-31 becomes constitutionally unavailable through organizational dissolution, infrastructure failure, or adversarial incapacitation. No succession mechanism exists (Gap 5). Without AC-31, CO-3 cannot be evaluated; CO-5 cannot be issued (ADR6-INV-01); all concurrent and future elections are constitutionally suspended (not void, not invalid) until AC-31 is restored or a successor mechanism established. Single-point failure: unlike TM-42 (which requires multiple D43 authorities to fail simultaneously), TM-46 requires only AC-31 to become unavailable. **ARB Correction 2026-06-17 — Classification: C-F (Availability Catastrophe).** Availability catastrophe ≠ constitutional FAIL: constitutional actors remain fully functional throughout; the specific resource required for CO-3 evaluation is unavailable but no constitutional invalidity is produced and no constitutional void results. TM-42 Complete Deadlock (F) differs because the actors who would authorize recovery are themselves unavailable; in TM-46, those actors remain present. OQ-38A05-01 remains open: does permanent loss (organizational dissolution) elevate toward unconditional F? *(38A-05 C.3)*

**TM-47 — Certification Chain Self-Reference Exploitation:** An adversary who has already compromised AC-31 (TM-19 precondition) exploits the certification chain's self-referential property to permanently launder that corruption through CO-3 → CO-5 → TS-1. The mechanism: compromised AC-31 trivially satisfies CO-3 evaluation; CO-5 is issued; TS-1 (terminal state) achieved; the false authenticity baseline is constitutionally locked. The AC-31 authenticity ratchet (AW-05-07) then entrenches the false baseline across subsequent elections without requiring further adversary action. Prohibited from unconditional F: TM-47 requires TM-19 precondition (AC-31 must first be compromised); it is not a standalone structural FAIL. Classification: C-F → F (conditioned on TM-19). *(38A-05 D.2)*

**TM-48 — Independent Reference Disagreement (ARB-named):** Two independent reference standard instances, or two competing reference standards, produce different authenticity conclusions for the same evidence. Currently AC-31 is treated as singleton, but no constitutional provision enforces singleton status (Gap 5 removes this governance). Scenarios: AC-31 version divergence after update; challenger introduces alternative reference standard claiming AC-31 compromise; successor reference after TM-46. CAB cannot adjudicate without a meta-reference above AC-31 — creating a reference-level deadlock the challenge architecture cannot resolve. OQ-38A05-03 raised: enforcing singleton status may require Gap 5 resolution as prerequisite. Classification: C-F. *(38A-05 E.2)*

**TM-49 — Evidence Set Incompatibility [CANDIDATE THREAT]:** Two or more constitutional actors present mutually incompatible evidence packages, each satisfying CO-3 independently (each authenticated by AC-31). ADR3-INV-01 prevents CO-2 satisfying CO-3, but does not require evidence uniqueness. No constitutional mechanism selects between two internally CO-3-valid but mutually incompatible packages. CAB adjudicates challenges, not evidence selection — it cannot invalidate a CO-3-valid package without a meta-reference. Amplified by TM-45 (AC-31 Ambiguity): an ambiguous AC-31 increases the probability that adversary-constructed incompatible packages can each claim CO-3 satisfaction. **ARB Correction 2026-06-17 — Classification: Candidate Threat (downgraded from C-F).** Threat acceptance requires formal prior modeling of: evidence identity, evidence uniqueness, evidence provenance, and evidence lineage. The threat analysis assumes these concepts without formally defining them — which means the preconditions cannot be verified. Until evidence identity and uniqueness are constitutionally modeled (a Round 38B task), TM-49 is a candidate identifying a structural vulnerability that would materialize if those conditions can be formally demonstrated and exploited. *(38A-05 E.1)*

---

## Part G — TA-01: Hidden Assumption Register

The architecture's unconditional guarantees depend on assumptions that have not been explicitly stated or challenged. This register surfaces those assumptions for explicit evaluation. Each assumption is classified as:
- **Architectural (AA)** — assumption about how the constitutional architecture functions
- **Governance (GA)** — assumption about how governance bodies behave
- **Operational (OA)** — assumption about how constitutional procedures are executed in practice
- **Social (SA)** — assumption about human and organizational behavior that the architecture depends on

### G.1 Architectural Assumptions

**AA-01 — Membership Assembly Legitimacy**
*Assumption:* The Membership Assembly has constitutionally legitimate authority to establish ElectionConstitution and is not itself subject to capture or capture attempts.
*Status:* **UNRESOLVED** — OBS-ADR7-SS1 explicitly flagged this as the "strongest candidate" for source-of-source, but its own legitimacy chain has not been established.
*Threat connection:* TM-07 (Membership Capture) attacks this assumption directly. If MA legitimacy is unresolved, the entire legitimacy chain from EC downward is conditionally valid — valid only to the extent MA's legitimacy is valid.
*Priority:* **CRITICAL** — highest priority assumption in the register.

**AA-02 — ElectionConstitution Uniqueness**
*Assumption:* ElectionConstitution is a single, uniquely identifiable document at any given point in time. When there is a dispute about which version is authoritative, the architecture provides a resolution mechanism.
*Status:* **NOT VALIDATED** — the ADRs assume EC is uniquely identifiable for AC-31, CO-4, L-1 source, and revocation purposes, but no resolution mechanism for competing EC versions is specified.
*Threat connection:* TM-13 (Constitutional Ambiguity Exploitation), TM-09 (Constitutional Drift).

**AA-03 — EC Amendment Process Independence**
*Assumption:* The process for amending ElectionConstitution is controlled by constitutional actors who are independent of the seven D43 authority aggregates — no single authority can unilaterally amend EC.
*Status:* **ASSUMED, NOT VERIFIED** — the ADRs treat EC amendment as a constitutional fact but have not specified who controls the amendment process or what prevents authority aggregates from controlling it.
*Threat connection:* TM-01 (Constitution Capture), TM-06 (Concentration Chain Capture).

**AA-04 — CertificationAuthority Non-Circular CO-4 Evaluation**
*Assumption:* CO-4 compliance evaluation by CertificationAuthority is non-circular: CA evaluates EC's constitutional compliance requirements without EC defining those requirements in ways that guarantee CA approval.
*Status:* **STRUCTURAL RISK** — EC defines the CO-4 compliance standard (OBS-ADR6-01 clarifies CO-4 evaluates EC's own rules). If EC is captured, it can define CO-4 to pass any election. CA's CO-4 evaluation is only as strong as EC's constitutional integrity.
*Threat connection:* TM-01 + TM-02 (if combined, EC captured + CA captured = CO-4 becomes structurally meaningless).

**AA-05 — Cascade-Free Authority Suspension**
*Assumption:* The seven authority aggregates can be independently suspended without cascading constitutional failure. Suspension of AuditScopeAuthority does not prevent CertificationAuthority from functioning; suspension of CertificationAuthority does not prevent challenge adjudication.
*Status:* **PARTIALLY VALIDATED** — ADR7-INV-01 governs succession. But: (a) simultaneous suspensions may not be covered, (b) functional dependencies between authorities (e.g., CertificationAuthority depends on AuditScopeAuthority scope definition for CO-2) may create cascades even with succession in place.
*Threat connection:* TM-11 (Succession Vacancy Attack).

**AA-06 — AC-31 Independent Reference Standard Stability**
*Assumption:* The AC-31 independent reference standard for evidence authenticity is itself not a concentration point and maintains constitutional legitimacy independently of the seven D43 authority aggregates.
*Status:* **UNRESOLVED** — OBS-ADR3-01 flags that the AC-31 reference standard requires its own legitimacy chain. That chain has not been established in the ADR sequence.
*Threat connection:* TM-19 (AC-31 Reference Standard Capture). If AC-31 fails, the Authenticity Stratum of every election evidence package becomes unverifiable.

**AA-07 — GovernanceState as Authoritative Phase Record**
*Assumption:* GovernanceState records are the sole authoritative source of constitutional phase history and there is no independent corroborating record of phase transitions.
*Status:* **IDENTIFIED RISK** — OBS-ADR7-03 explicitly flags corruption risk. But the assumption that GovernanceState is the single authority creates a single point of truth. If there is no independent corroboration, GovernanceState corruption may be undetectable.
*Threat connection:* TM-03 (GovernanceState Corruption), TM-10 (Phase Lock Attack).

### G.2 Governance Assumptions

**GA-01 — Authority Self-Reporting**
*Assumption:* Authority aggregates that violate constitutional constraints will either self-report the violation or produce detectable evidence of the violation that other authorities or challengers can discover.
*Status:* **OPTIMISTIC ASSUMPTION** — the challenge architecture (ADR-5) provides mechanisms for other authorities to challenge, but only if they can detect the violation. If a violation produces no detectable evidence, the challenge architecture cannot operate.
*Threat connection:* TM-02 (Certification Capture), TM-08 (Silent Certification Failure).

**GA-02 — Concentration Chain Replaceability**
*Assumption:* If ElectionConstitution is amended or replaced (CP-1), CertificationAuthority continues to evaluate CO-4 against the new EC without architectural disruption. The concentration chain is component-replaceable.
*Status:* **UNVALIDATED** — the ADRs have not evaluated what happens when EC is amended mid-election-cycle. CO-4 compliance requires a stable EC reference point. If EC changes during an active certification process, the compliance standard is ambiguous.
*Threat connection:* TM-09 (Constitutional Drift), TM-06 (Concentration Chain Capture).

**GA-03 — No Manufactured Suspension**
*Assumption:* No authority aggregate will manufacture the conditions that trigger its own suspension to capture the successor designation.
*Status:* **ASSUMED, NOT PROTECTED** — ADR7-INV-01 requires succession pre-designation, but does not address the incentive for authorities to trigger their own suspension if the successor is favorable.
*Threat connection:* TM-11 (Succession Vacancy Attack variant).

**GA-04 — Standing Diversity Sufficiency**
*Assumption:* The standing distribution is sufficient that no single adversary can prevent all material challenges from reaching adjudication by capturing, deterring, or eliminating standing holders.
*Status:* **NOT SPECIFIED** — the ADRs distribute standing but do not specify minimum standing diversity requirements or what happens when standing holders are systematically reduced below a functional threshold.
*Threat connection:* TM-05 (Standing Manipulation), TM-12 (Challenge Window Attrition).

### G.3 Operational Assumptions

**OA-01 — Challenger Evidence Access**
*Assumption:* Challengers with standing can access the underlying audit evidence package — not merely the Named Attestation statement (OQ-37-06-01) — in sufficient detail to evaluate CO-2, CO-3, and CO-4 independently.
*Status:* **UNSPECIFIED** — the Named Attestation Model provides separate attestation sections that are individually challengeable. But the model does not specify what underlying evidence challengers can access or in what form.
*Threat connection:* TM-12 (Challenge Window Attrition), TM-16 (Evidence Authenticity Confusion).

**OA-02 — Challenge Window Practical Sufficiency**
*Assumption:* The challenge window is not only non-zero and finite (ADR6-CONSTRAINT-01) but also practically sufficient for challengers to discover, evaluate, access evidence for, and file material challenges.
*Status:* **UNDER-SPECIFIED** — ADR6-CONSTRAINT-01 establishes constitutional minimum conditions (non-zero, finite, published, known before election) but does not define practical sufficiency. A 24-hour window satisfies the constitutional constraint but may be practically insufficient.
*Threat connection:* TM-12 (Challenge Window Attrition), TM-08 (Silent Certification Failure).

**OA-03 — ChallengeReceptionFunction Unforgeable Filing Evidence**
*Assumption:* ChallengeReceptionFunction provides unforgeable evidence of challenge filing — challengers can prove they filed a valid challenge within the window, and no entity can suppress challenge registrations without this being detectable.
*Status:* **OPERATIONALLY CRITICAL** — if CRF is operationally captured (not captured as an authority, but operationally manipulated), challenge registrations may be "lost" without this being constitutionally detectable. The ADRs establish CRF as a constitutional capability of ChallengeAdjudicationBody but do not specify the evidential integrity requirements for the filing record itself.
*Threat connection:* TM-05 (Standing Manipulation at reception layer).

**OA-04 — EC Publication Practical Accessibility**
*Assumption:* The constitutional publication of challenge window duration, EC text, and standing class framework is practically accessible to all standing holders — not only technically available, but findable and readable by those with standing.
*Status:* **ASSUMED** — ADR6-CONSTRAINT-01 requires constitutionally published challenge window. But "published" may mean different things in different operational contexts. If publication requires technical access that standing holders do not have, the constitutional requirement is formally satisfied but practically defeated.
*Threat connection:* TM-12 (Challenge Window Attrition).

### G.4 Social Assumptions

**SA-01 — Membership Assembly Representation**
*Assumption:* The Membership Assembly's authority to establish EC is legitimate because it authentically represents the membership's interests — the MA is not astroturfed, captured, or performing an election of membership representatives that is itself fraudulent.
*Status:* **SOCIAL PRECONDITION** — the architecture treats MA legitimacy as a precondition, not a product of the architecture. TM-07 attacks this precondition. If MA legitimacy is not itself a product of the constitutional architecture, it is a constitutional assumption that must be made explicit.
*Threat connection:* TM-07 (Membership Capture).

**SA-02 — Challenger Willingness Under Adversarial Pressure**
*Assumption:* Standing holders will file material challenges even when facing adversarial pressure — retaliation threats, social consequences, organizational pressure, or legal risk created by challenged authorities or their allies.
*Status:* **BEHAVIORAL PRECONDITION** — the architecture grants standing but cannot compel its exercise. If challengers are deterred through extralegal means, the challenge architecture is formally present but functionally inoperative.
*Threat connection:* TM-05 (Standing Manipulation — chilling effect variant), TM-12 (Challenge Window Attrition).

**SA-03 — Initial Good Faith**
*Assumption:* Authorities appointed through constitutional process will operate in good faith initially — they will not begin operating adversarially from their first day of appointment.
*Status:* **REASONABLE BUT NOT GUARANTEED** — the architecture's protections assume that capture happens after appointment. An authority that is adversarially intentioned from appointment day one begins circumventing constitutional constraints from the start, before detection mechanisms have time to operate.
*Threat connection:* TM-02 (Certification Capture), TM-04 (Challenge Adjudication Capture).

### G.5 Assumption Coverage Challenge

The assumption register reveals four structural gaps that are not covered by any existing threat:

**Gap 1 — MA Legitimacy Foundation:**
TM-07 addresses Membership Assembly Capture by an adversary. But AA-01 reveals a deeper gap: MA legitimacy has not been architecturally established at all. TM-07 requires an already-legitimate MA to be captured. The prior question — what makes MA legitimate in the first place — is an architectural gap, not a threat scenario. This is neither a threat nor an assumption: it is a constitutional architecture incompleteness.

**Gap 2 — AC-31 Legitimacy Chain:**
TM-19 addresses AC-31 reference standard capture. But AA-06 reveals that the AC-31 reference standard's legitimacy chain has not been established. If AC-31 has no legitimacy chain, TM-19 may already be in a permanent Conditional Fail state — the reference standard is already without constitutional grounding.

**Gap 3 — EC Amendment Process:**
The amendment process for ElectionConstitution has no constitutional specification in ADR-1 through ADR-7. EC amendment is treated as a constitutional fact. No authority is designated to control it; no procedure is specified; no procedural safeguards are defined. This is a constitutional architecture gap that precedes TM-01 (Constitution Capture) — TM-01 assumes an amendment process exists to capture.

**Gap 4 — Constitutional Interpretation:**
No authority exists to resolve constitutional ambiguities in ElectionConstitution. TM-13 (Constitutional Ambiguity Exploitation) attacks this gap, but the gap itself is pre-threat: the architecture has no constitutional interpretation function. When EC language is ambiguous, the architecture has no mechanism to produce a binding authoritative interpretation short of amendment.

**Gap 5 — AC-31 Reference Standard Governance:** *(Formally elevated by ARB, 2026-06-17 — see 38A-02 C.3)*
AC-31 is the reference standard for authenticating evidence across all elections simultaneously. Its governance is entirely unspecified: no body is designated to govern it, no succession mechanism exists, no challenge mechanism in the ADR-5 architecture covers it, and who authenticates the authenticator produces an unresolved circularity. Unlike the seven D43 authority aggregates — each with constitutional governance provisions, independence requirements, succession provisions (ADR-7), and challenge mechanisms (ADR-5) — AC-31 has none of these. Gap 5 carries equal weight to Gaps 1–4 in severity: a captured AC-31 threatens every election's evidence authenticity retroactively and prospectively, not one election cycle.

**Candidate Gap 6 — Operational Independence Standard:** *(ARB 2026-06-17 — see 38A-03 C.9, OQ-38A03-05)*
ADR-2 specifies constitutional independence forms for each D43 authority function. It does not specify operational independence requirements. Constitutionally independent authorities may share infrastructure, personnel, funding, or appointment chains without violating ADR-2's constitutional independence designations. TM-39 (Independence Illusion — 38A-03 C.9) identifies this as a design-level structural FAIL at Adversary D: if constitutionally independent authorities are operationally dependent, independence designations are constitutively inadequate. **Status: CANDIDATE** — pending ARB confirmation in 38A-06. ARB ruling requested via OQ-38A03-05.

**Candidate Gap 7 — GovernanceState Phase Record Governance:** *(ARB 2026-06-17 — see 38A-04-ARB-Review Part C)*
The architecture designates GovernanceState as the sole authoritative constitutional phase record (AA-07) with no corroboration mechanism, no external verification mechanism, and no challenge infrastructure that does not depend on GovernanceState itself. Named Attestation challenges evaluate compliance with what GovernanceState says; they cannot override GovernanceState using external evidence. This is structurally analogous to Gap 5 (AC-31 as authentication root without governance) — GovernanceState is the temporal root without governance. Structural parallel: both are constitutional roots in their domains; both are self-referential; neither has corroboration, challenge mechanisms that don't depend on themselves, or governance specification. **Status: CANDIDATE** — pending ARB confirmation in 38A-06 alongside Gap 6 ruling. ARB ruling requested via OQ-38A-Review-01.

---

## Part G.6 — Legitimacy Root Discovery

*ARB Strategic Finding — APPROVED 2026-06-16*

### G.6.1 The Discovery

The Hidden Assumption Register (G.1) reveals a structural convergence that was not the intended purpose of the register analysis:

```
AA-01 — Membership Assembly Legitimacy            (unresolved)
AA-02 — ElectionConstitution Uniqueness           (not validated)
AA-03 — EC Amendment Process Independence         (assumed, not verified)
AA-06 — AC-31 Reference Standard Stability        (unresolved)
```

These four assumptions do not point to four independent weaknesses. They point to the same architectural phenomenon: **the architecture has a Source-of-Authority Layer that predates its own constitutional instruments**.

### G.6.2 What the Source-of-Authority Layer Means

```
Source-of-Authority Layer (pre-constitutional)
    ↓ establishes
Membership Assembly  ←— AA-01 (unresolved legitimacy)
    ↓ produces
ElectionConstitution  ←— AA-02 (uniqueness assumed), AA-03 (amendment process unspecified)
    ↓ governs
CertificationAuthority / GovernanceAuthority / ChallengeAuthority
    ↓ evaluates
CO-5 Election Validity
    ↓ relies on
AC-31 Reference Standard  ←— AA-06 (own legitimacy chain unresolved)
```

The architecture's constitutional governance begins at ElectionConstitution. But ElectionConstitution's authority depends on the Membership Assembly. And the Membership Assembly's legitimacy depends on a layer the architecture has not constitutionally specified.

### G.6.3 This Is a Discovery, Not a Flaw

**The ARB's ruling (2026-06-16):** The architecture has reached its legitimacy root. This is not an architectural failure — it is the natural boundary of a constitutional governance system. Every constitutional architecture eventually encounters this boundary. The question for Round 38A is not "why didn't the ADRs resolve this?" but "what does this boundary mean for the threat evaluations?"

**Implications for Round 38A:**
1. Threats that target the Source-of-Authority Layer (TM-07, TM-35) are attacking something the constitutional architecture has not established — they are pre-constitutional threats.
2. Pre-constitutional threats cannot be evaluated purely within the constitutional architecture's survivability framework.
3. 38A-02 must explicitly recognize when a threat's success condition depends on the Source-of-Authority Layer and classify it accordingly (C-F with condition: legitimacy root resolution).

### G.6.4 Legitimacy Root as an Evaluation Anchor

For each threat in 38A-02 and 38A-03 that touches the Source-of-Authority Layer, the analyst must determine:

- Does this threat succeed because of a constitutional architecture gap, or because it attacks something the architecture was never designed to protect?
- If the latter: the finding is not a constitutional FAIL — it is a **pre-constitutional exposure** that must be reported separately in 38A-06.

---

## Part H — Threat Coverage Matrix

This matrix confirms that every threat in the Master Threat Catalog is covered by at least one 38A sub-document evaluation.

| Threat | Name | 38A-02 | 38A-03 | 38A-04 | 38A-05 | 38A-06 |
|---|---|---|---|---|---|---|
| TM-01 | Constitution Capture | ✓ | | | | ✓ |
| TM-02 | Certification Capture | ✓ | ✓ | | | ✓ |
| TM-03 | GovernanceState Corruption | ✓ | | | | |
| TM-04 | Challenge Adjudication Capture | ✓ | ✓ | | | |
| TM-05 | Standing Manipulation | ✓ | | | | |
| TM-06 | Concentration Chain Capture | | ✓ | | | ✓ |
| TM-07 | Membership Capture | ✓ | | | | ✓ |
| TM-08 | Silent Certification Failure | ✓ | | | | ✓ |
| TM-09 | Constitutional Drift | ✓ | | | | ✓ |
| TM-10 | Phase Lock Attack | ✓ | | | | |
| TM-11 | Succession Vacancy Attack | | ✓ | | | |
| TM-12 | Challenge Window Attrition | ✓ | | | | |
| TM-13 | Constitutional Ambiguity Exploitation | ✓ | | | | |
| TM-14 | Authority Self-Amendment | | ✓ | | | |
| TM-15 | Evidence Completeness Attack | ✓ | | | | |
| TM-16 | Evidence Authenticity Confusion | | ✓ | | | |
| TM-17 | Audit Scope Exclusion | | ✓ | | | |
| TM-18 | Two-Authority Coalition | | ✓ | | | |
| TM-19 | AC-31 Reference Standard Capture | | ✓ | | | ✓ |
| TM-20 | Vote Buying | | | ✓ | | |
| TM-21 | Voter Coercion | | | ✓ | | |
| TM-22 | Chain Voting / Carousel Attack | | | ✓ | | |
| TM-23 | Selective Disenfranchisement | | | ✓ | | |
| TM-24 | Ballot Stuffing | | | ✓ | | |
| TM-25 | Result Manipulation | | | ✓ | | |
| TM-26 | Verification Abuse | | | ✓ | | |
| TM-27 | Voter Credential Theft | | | ✓ | | |
| TM-28 | Insider Manipulation | | | | ✓ | |
| TM-29 | Infrastructure Compromise | | | | ✓ | |
| TM-30 | Evidence Deletion | | | | ✓ | |
| TM-31 | Availability Attack | | | | ✓ | |
| TM-32 | Supply Chain Attack | | | | ✓ | |
| TM-33 | State-Level Coordinated Attack | | | | ✓ | ✓ |
| TM-34 | Information Environment Capture | ✓ | | | | ✓ |
| TM-35 | Legitimacy Narrative Attack | ✓ | | | | ✓ |
| TM-36 | Observer Capture | | ✓ | | | ✓ |

**Coverage confirmation:** 36 threats (33 original + 3 ARB-directed additions), all covered. No threat falls through without a designated evaluation document.

---

## Part I — ARB Decision Block

### Document Status

**[APPROVED WITH STRATEGIC CORRECTIONS — 2026-06-16]**

**ARB Score:** 9.5/10 overall (Constitutional Architecture: 10/10, Methodological Rigor: 10/10, Hidden Assumption Register: Outstanding)

### ARB Corrections Applied

**Correction 1 — OQ-38A-01 Resolved:** The four structural architecture gaps (MA Legitimacy Foundation, AC-31 Legitimacy Chain, EC Amendment Process, Constitutional Interpretation Authority) are **unresolved architecture questions**, not automatic FAIL findings. They must not be classified as FAIL without adversarial analysis. 38A-02 and 38A-03 must treat each gap as a pre-threat exposure and evaluate whether adversarial analysis produces a FAIL, C-F, or S-D classification. Pre-constitutional threats (those targeting the Source-of-Authority Layer) are reported separately in 38A-06 as pre-constitutional exposures.

**Correction 2 — TM-08 Expanded:** Silent Certification Failure is a family of four structurally distinct failure modes (TM-08-A through TM-08-D). TM-08-B (Review Impossible) requires no adversary and is a baseline structural failure mode. Full expansion applied to F.2 TM-08 Description.

**Correction 3 — TM-34 Added:** Information Environment Capture — information warfare threats including misinformation, disinformation, evidence flooding, observer confusion, selective publication, and manufactured distrust. Highly relevant for diaspora elections. Added to F.3a.

**Correction 4 — TM-35 Added:** Legitimacy Narrative Attack — attacks targeting perceived legitimacy of MA, EC, and CA without structural compromise. Distinct from information warfare (TM-34). Targets the unresolved Source-of-Authority Layer. Added to F.3a.

**Correction 5 — TM-36 Added:** Observer Capture — capture, coordination, or deterrence of constitutional observers who hold challenge standing. If observer capture is undetectable, the challenge architecture's independence guarantee fails. Added to F.3a.

**Strategic Finding — Legitimacy Root Acknowledged:** The assumption register reveals a Source-of-Authority Layer predating the constitutional architecture. This is a discovery, not a flaw. Documented in Part G.6. Pre-constitutional threats are evaluated separately from constitutional architecture failures in 38A-06.

### ARB Rulings on Open Questions

**OQ-38A-01 — RESOLVED:** Four architecture gaps = unresolved architecture questions. 38A-02 and 38A-03 conduct adversarial analysis. 38A-06 reports pre-constitutional exposures separately from constitutional failures.

**OQ-38A-02 — RESOLVED:** TM-09 (Constitutional Drift) is evaluated within 38A-02. No standalone sub-document.

**OQ-38A-03 — RESOLVED:** TM-07 (Membership Capture) is evaluable in 38A-02 with AA-01 treated as an unresolved question. The unresolved status of AA-01 is a factor in TM-07's severity classification, not a reason to defer evaluation. 38A-02 must address AA-01's unresolved status directly in the TM-07 assessment.

### Decisions Made in This Document

1. **Mission discipline established.** Burden of proof reversed for Round 38A. Default classification is Assessment Deferred, not Survives.

2. **Seven threat classes defined.** Class 1 (Constitutional), Class 2 (Governance Capture), Class 3 (Authority Capture), Class 4 (Evidence Manipulation), Class 5 (Election Security), Class 6 (Technical/Infrastructure), Class 7 (Composite).

3. **Five adversaries profiled.** Adversaries A through E with capability profiles and access levels. Adversary × Threat Class matrix established.

4. **Four failure types defined.** Constitutional Failure, Governance Failure, Election Failure, Technical Failure — each with distinct indicators and distinguishing features.

5. **Five survivability classifications defined (one proposed addition).** Survives, Survives with Detection, Survives with Mitigation, Fails — per ARB mandate. Conditional Fail (C-F) is proposed as a fifth classification to bound the failure domain. Assessment Deferred defined as the default when neither survival nor failure can be demonstrated. ARB must accept or reject C-F.

6. **Master Threat Catalog established.** TM-01 through TM-33. Carried threats: TM-01 through TM-06. ARB-authorized new threats: TM-07 through TM-09. Baseline-discovered threats: TM-10 through TM-19. Election security threats: TM-20 through TM-27. Technical threats: TM-28 through TM-33.

7. **TA-01 Hidden Assumption Register established.** 10 architectural assumptions (AA-01 through AA-07 critical), 4 governance assumptions (GA-01 through GA-04), 4 operational assumptions (OA-01 through OA-04), 3 social assumptions (SA-01 through SA-03).

8. **Four structural architecture gaps identified.** (a) MA Legitimacy Foundation — unresolved by any ADR; (b) AC-31 Legitimacy Chain — unresolved by any ADR; (c) EC Amendment Process — no constitutional specification; (d) Constitutional Interpretation Authority — no authority designated.

9. **Zero-FAIL hypothesis identified.** If 38A-02 through 38A-05 produce zero FAIL findings, 38A-06 must explicitly address this result against the five diagnostic questions established in Part A.4.

10. **Threat Coverage Matrix confirmed.** 33 threats, all assigned to at least one 38A sub-document.

### Observations

**OBS-38A01-01: Four Architecture Gaps Precede Threat Analysis**
The four structural gaps identified in G.5 (MA Legitimacy, AC-31 Legitimacy Chain, EC Amendment Process, Constitutional Interpretation) are not threats — they are architectural incompleteness items that exist before any adversary acts. These gaps mean that some threats (TM-07, TM-19, TM-01, TM-13) are attacking vulnerabilities that exist in the baseline architecture, not vulnerabilities that adversaries create. 38A-02 and 38A-03 must address whether these gaps constitute baseline failures or baseline risk exposures.

**OBS-38A01-02: AA-01 Is the Single Highest-Risk Assumption**
AA-01 (Membership Assembly Legitimacy) is the only assumption whose failure invalidates the entire legitimacy chain from EC downward. All other assumptions are partial failures — they undermine specific functions. AA-01 failure is total. 38A-02 must treat TM-07 not only as a threat scenario but as a structural legitimacy challenge to the entire architecture.

**OBS-38A01-03: TM-08 Is Distinct from All Other Threats**
Silent Certification Failure (TM-08) is the only threat in the catalog that does not require adversary action. It is a structural failure mode — CO-5 can be constitutionally false through honest negligence. This means the architecture must be evaluated not only against intentional adversaries but against honest errors that the constitutional architecture cannot detect. TM-08 is therefore both a governance threat and an architectural completeness test.

### Open Questions for Round 38A

**OQ-38A-01 [BLOCKING GATE — Guidance required before 38A-02 proceeds]:** The four structural architecture gaps (G.5) exist before any adversary acts. They are not threats — they are baseline architectural incompleteness. If the gaps constitute baseline failures, then TM-07, TM-13, TM-19, and TM-01 are attacking vulnerabilities that already exist in the unadversaried architecture, and their 38A-02/38A-03 threat classifications may be irrelevant. If the gaps are prerequisites (unresolved architecture to be addressed in future rounds), the threat evaluations proceed against a baseline assumed to be complete. This distinction changes the entire framing of 38A-02 and 38A-03. The ARB must provide a ruling before 38A-02 begins. Proposed ruling options: (a) gaps are 38A-06 findings reported as pre-threat architectural incompleteness; (b) gaps are Round 38B prerequisites and 38A evaluates as if the gaps will be resolved; (c) gaps generate C-F or F findings immediately in 38A-02 without requiring further adversary analysis.

**OQ-38A-02:** Should TM-09 (Constitutional Drift) receive a standalone 38A sub-document given its multi-round nature (no single election cycle; accumulates across amendment cycles)?

**OQ-38A-03 [GATE CONDITION FOR 38A-02 AUTHORIZATION]:** TM-07 (Membership Capture) depends on AA-01 (MA Legitimacy) being a resolved architectural fact. Since AA-01 is declared unresolved (OBS-ADR7-SS1), TM-07 as written attacks something the architecture has not established. If TM-07 is evaluated as a capture threat, the evaluation rests on an unresolved foundation. If TM-07 is reframed as a legitimacy chain gap analysis, it becomes an architectural incompleteness finding, not a threat evaluation. The ARB must resolve this reframing question before 38A-02 is authorized. Authorization of 38A-02 is conditional on the ARB providing one of: (a) ruling that AA-01 is assumed resolved for 38A evaluation purposes; (b) ruling that TM-07 is reframed as a Gap analysis, removed from the capture threat catalog and added to 38A-06 structural findings; or (c) ruling that TM-07 is evaluable as a conditional threat (C-F) with the condition being AA-01 resolution.

### Authorization Request

ARB is requested to:

1. Accept or reject the seven threat class taxonomy
2. Accept or reject the Master Threat Catalog as the working catalog for Round 38A
3. Confirm or reject the TA-01 Hidden Assumption Register as the assumption set for evaluation
4. Accept or reject the five survivability classifications and determination criteria — including the Conditional Fail (C-F) classification proposed as an addition to the ARB's four original classifications
5. **[BLOCKING — must precede 38A-02 authorization]** Rule on OQ-38A-01: are the four structural architecture gaps (a) 38A-06 findings, (b) Round 38B prerequisites, or (c) immediate C-F/F findings requiring no further adversary analysis?
6. **[BLOCKING — gate condition for 38A-02 authorization]** Rule on OQ-38A-03: is TM-07 (a) evaluated as a capture threat with AA-01 assumed resolved, (b) reframed as a Gap analysis and moved to 38A-06 structural findings, or (c) evaluated as a Conditional Fail contingent on AA-01 resolution?
7. Authorize Round 38A-02 — Constitutional & Governance Capture Threats — conditional on ARB rulings for items 5 and 6 above
8. Provide guidance on OQ-38A-02 (TM-09 Constitutional Drift standalone sub-document)

---

**Authors:**
- Senior Election Security Architect
- Constitutional Governance Architect
- Threat Modeling Specialist (adversarial)
- DDD Architect

**Round 38A Program Status:**
*38A-01 SUBMITTED*
*38A-02 through 38A-06 — Awaiting 38A-01 Authorization*
*38A predecessor: Round 37 — COMPLETE*
*Next Phase (after 38A-06): Round 38B — Technical Architecture — AUTHORIZATION NOT YET GRANTED*
