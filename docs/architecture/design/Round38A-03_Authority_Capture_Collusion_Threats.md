# Round 38A-03 — Authority Capture & Collusion Threats

**Program:** NRNA DDD Trustworthiness Research Program — Round 38A  
**Document:** 38A-03 of 06  
**Status:** APPROVED WITH STRATEGIC CORRECTIONS APPLIED (2026-06-17 — ARB score 9.8/10)  
**Predecessors:** 38A-01 APPROVED WITH STRATEGIC CORRECTIONS | 38A-02 APPROVED WITH TARGETED CORRECTIONS APPLIED  
**Mission:** Adversarial posture. Attempt to produce FAIL findings. Do not defend the architecture.  
**Authorized by ARB:** TM-02, TM-04, TM-06, TM-18, TM-19 (primary); TM-11, TM-14, TM-16, TM-17, TM-36 (coverage matrix); TM-39 (Independence Illusion — ARB-authorized 2026-06-17)

---

## Part A — Pre-Evaluation Architecture State

### A.1 Inherited Findings from 38A-01 and 38A-02

| Finding | Source | Status | Relevance to 38A-03 |
|---|---|---|---|
| Five structural gaps (Gap 1–5) | 38A-01 G.5 + 38A-02 C.3 | Active | Gap 5 (AC-31 governance) is the primary attack surface for TM-19 |
| TM-08-B: C-F approaching F | 38A-02 B.6 | Active | Evidence accessibility connects to TM-19 and TM-02 |
| Constitutional Self-Destruction (AW-02-11) | 38A-02 C.4 | Active | TM-06 full capture is the fast execution path for constitutional self-destruction |
| TM-03 C-F: all conditions currently met | 38A-02 D.2 | Open | GovernanceAuthority capture analysis must cite AW-02-03 |
| ADR7-INV-01: Succession Succession pre-designation required | 38A-01 | Active | TM-11 evaluation depends on this invariant |
| ADR7-INV-02: No self-dealing in standing | 38A-01 | Active | TM-04 and TM-14 evaluation depends on this invariant |

### A.2 The Seven D43 Authority Aggregates

| Aggregate | Abbreviation | Constitutional Function | L-Level |
|---|---|---|---|
| EnrollmentAuthority | EA | Determines voter eligibility and enrollment records | L-2 |
| CriteriaAuthority | CrA | Defines voting criteria and eligibility standards | L-2 |
| AuditScopeAuthority | ASA | Defines expected evidence set (Tier 2 within EC Tier 1) | L-3 |
| AuditExecutionAuthority | AEA | Executes the audit against the defined scope | L-4 |
| GovernanceAuthority | GA | Maintains GovernanceState — the authoritative phase record | L-2 |
| CertificationAuthority | CA | Issues CO-5 (Election Validity) — requires CO-2, CO-3, CO-4 | L-5 |
| ChallengeAdjudicationBody | CAB | Adjudicates all constitutional challenges | L-5 |

*ElectionConstitution (EC) is the source-of-authority (L-1) for all seven aggregates. AC-31 Reference Standard is a cross-cutting authentication mechanism (not a D43 aggregate — Gap 5).*

### A.3 Active Constitutional Invariants for Authority Architecture

- **ADR6-INV-01:** CO-5 void unless CO-2 + CO-3 + CO-4 independently satisfied (non-waivable)
- **ADR7-INV-01:** EC must pre-designate successors for all seven D43 authority aggregates
- **ADR7-INV-02:** No authority may self-grant, expand, or restrict standing for challenges against itself

### A.4 ADR-2 Independence Requirement

All seven D43 authority aggregates are constitutionally independent. Capture requires compromising the authority's decision-making capacity while leaving its constitutional form intact — which is why the Named Attestation Model (challengeable CO-2/CO-3/CO-4 attestations) is the primary detection mechanism.

**Strategic Correction (ARB 2026-06-17):** ADR-2 specifies constitutional independence — the authority structure. It does not specify operational independence. Constitutionally independent authorities can share infrastructure, personnel, funding, or appointment chains without violating ADR-2. TM-39 (Independence Illusion — Section C.9) evaluates this gap. Preliminary finding: operational dependence may create hidden concentration points that the constitutional architecture's independence designations do not address and cannot detect.

---

## Part B — Single Authority Capture Baseline

*Purpose: establish the single-authority capture severity for all seven aggregates before evaluating coalitions. This section feeds the Authority Capture Matrix (Part E).*

### B.1 EnrollmentAuthority (EA) — Single Capture

**Attack:** EA selectively excludes eligible voters from enrollment records, or creates enrollment records for ineligible voters (stuffing), or creates records in formats that cannot satisfy CO-2 assessment criteria.

**Primary defense:** CO-2 (Evidence Completeness) — CA must independently assess that enrollment evidence is complete. Named Attestation: CO-2 attestation is challengeable.

**Defense failure:** CA's CO-2 assessment can only detect absence of evidence, not absence of eligible-voter-coverage. If a voter was never enrolled and never appears in the evidence record, CA has no visibility into the missing enrollment. Challengers must demonstrate evidence-of-absence (challenging a missing record), which requires OA-01 (challenger evidence access) to be resolved.

**Classification: C-F** — conditionally fails when (a) EA selectively excludes voters and (b) challengers cannot independently access enrollment records to demonstrate absence. OA-01 is the controlling condition.

---

### B.2 CriteriaAuthority (CrA) — Single Capture

**Attack:** CrA narrows eligibility criteria to exclude targeted voter populations. The criteria appear neutral but are designed to exclude by demographic, geographic, or organizational category.

**Primary defense:** CO-4 (Constitutional Compliance) — CA must assess whether election conduct satisfied EC criteria standards. Named Attestation: CO-4 attestation is challengeable.

**Defense failure:** CO-4 compliance is evaluated against the current EC. CrA defines criteria within the EC's framework. If CrA's narrowed criteria are expressible within the EC's Tier 1 categories (which are broadly defined), CA will find EC-compliant criteria — making CO-4 challenge difficult because there is no EC violation to point to.

**Classification: C-F** — conditionally fails when (a) narrowed criteria are expressible within EC without amendment and (b) challengers cannot demonstrate that excluded voters were eligible under pre-capture criteria. Classification escalates to C-F approaching F if combined with TM-01 (EC amendment to normalize the narrowed criteria).

---

### B.3 AuditScopeAuthority (ASA) — Single Capture

**Attack:** ASA defines the Tier 2 expected evidence set in a way that excludes evidence categories adverse to the election's preferred outcome. CO-2 assessment is then evaluated against the narrow scope, appearing complete against a manipulated standard.

**Primary defense:** ASA operates within EC Tier 1 categories. CA's CO-2 assessment evaluates against ASA's defined scope. Named Attestation: CO-2 is challengeable.

**Defense failure:** CO-2 challenge requires demonstrating that ASA's scope definition itself violated EC Tier 1 categories — a meta-challenge against ASA's scope authority. The challenge architecture adjudicates CO-2/CO-3/CO-4 attestations but has no established mechanism for challenging ASA's scope definition as a prior step. Gap 3 (EC Amendment Process undefined) means there is no clear procedure for whether scope challenges are a CO-4 sub-question or a separate constitutional matter.

**Classification: C-F** — conditionally fails when (a) ASA's narrowed scope is expressible within EC Tier 1 categories and (b) CAB does not recognize scope-definition challenges as within the challenge architecture. Classification may be worse if Gap 3 is not resolved.

---

### B.4 AuditExecutionAuthority (AEA) — Single Capture

**Attack:** AEA executes the audit against ASA's scope but produces falsified results — marking evidence as present when it is absent, or marking records as authentic when they are not.

**Primary defense:** CO-3 (Evidence Authenticity) — CA assesses authenticity via AC-31. If AEA's audit records are falsified and AC-31 authenticates them (because they have the correct format), CA's CO-3 assessment approves falsified evidence.

**Defense failure:** AC-31 authenticates format and provenance, not content truth. AEA with write access to audit records can produce format-compliant falsified records that pass AC-31 authentication. CO-3 challenge requires challengers to access the underlying evidence and demonstrate that what AEA certified as present is actually absent — OA-01 dependency.

**Classification: C-F** — conditionally fails when (a) AEA produces format-compliant falsified audit records and (b) OA-01 is not resolved (challengers cannot independently access evidence to detect falsification).

---

### B.5 GovernanceAuthority (GA) — Single Capture

**Attack:** GA manipulates GovernanceState — the authoritative phase record. Phase transitions can be accelerated (collapsing challenge windows), deferred (extending phases beyond constitutional limits), or falsely recorded (declaring a completed phase as ongoing or vice versa).

**Primary defense:** TM-03 (already evaluated in 38A-02) — GovernanceState corruption identified as C-F with all three conditions currently satisfied. ADR7-INV-01 requires phase succession documentation; GA manipulation attacks the phase record, not succession designations.

**Defense failure:** GovernanceState is the sole authoritative record of constitutional phase status (AA-07). No independent corroboration mechanism exists. A captured GA can declare a phase complete before it is, collapsing the challenge window (TM-10 interface). Challengers must use GovernanceState records to prove GovernanceState corruption — the circularity documented in TM-03.

**Classification: C-F** — all three C-F conditions (GovernanceState corrupted + sole record + circular challenge evidence) are currently architecturally satisfied. This is the TM-03 C-F approaching F from 38A-02. No additional adversary capability is needed beyond GA access.

---

### B.6 CertificationAuthority (CA) — Single Capture

**Attack (TM-02):** CA issues CO-5 despite knowing CO-2, CO-3, or CO-4 are not satisfied. This is the intentional version of TM-08 (which examined non-intentional failure modes).

**Primary defense:** Named Attestation Model — CO-2, CO-3, and CO-4 are individually challengeable. ADR6-INV-01 requires all three to be independently satisfied. An independent CAB can adjudicate CO-2/CO-3/CO-4 challenges.

**Defense failure:** If CA is captured but CAB is independent, the challenge path remains functional. Challengers with OA-01 resolved can file CO-2/CO-3/CO-4 challenges and succeed. The defense succeeds — but only with independent CAB and resolved OA-01.

**Classification: C-F** — conditionally fails when (a) CA issues false CO-5 AND (b) either CAB is not independent (TM-04 coalition) or OA-01 is unresolved (challengers cannot access evidence). CA single capture is the most important coalition amplifier: CA + CAB = F.

---

### B.7 ChallengeAdjudicationBody (CAB) — Single Capture

**Attack (TM-04):** CAB systematically dismisses all challenges, either on procedural grounds or by adverse merit decisions, without technically violating ADR7-INV-02 (no standing self-manipulation for challenges against itself).

**Primary defense:** ADR7-INV-02 prevents CAB from restricting its own standing for challenges against itself. Named Attestation challenges are constitutionally authorized.

**Defense failure — critical:** There is no meta-adjudication body. CAB is the sole constitutional adjudication layer. When CAB dismisses a challenge, no constitutional mechanism overrides or reviews the dismissal. ADR7-INV-02 prevents procedural self-dealing on standing (CAB cannot change the rules for challenges about CAB), but a captured CAB can dismiss challenges on adverse merit findings rather than procedural manipulation — and merit dismissals are unreviewable within the constitutional architecture.

**Classification: C-F approaching F** — conditionally fails when (a) CAB systematically dismisses challenges on adverse merit findings and (b) no alternative enforcement path exists. Condition (b) is currently met: the constitutional architecture provides no appellate body or alternative challenge mechanism. CAB single capture is the most architecturally severe single-authority capture because it removes the enforcement mechanism for all other constitutional invariants simultaneously.

---

## Part C — Specific Threat Evaluations

### C.1 TM-02 — Certification Capture

**Class:** 2 (Governance / Certification) | **Adversary:** C (Compromised Authority), D (Colluding Authorities)

*Distinct from TM-08: TM-08 analyzed non-intentional certification failure. TM-02 analyzes intentional capture of CA — the authority is deliberately compromised and issues CO-5 knowing the underlying conditions are not met.*

**Attack Path:**

1. Adversary C or D achieves compromise of CA's decision-making apparatus (key personnel, operational control, or organizational capture).
2. CA issues CO-5 with false attestations on one or more of: CO-2 (evidence completeness), CO-3 (evidence authenticity), CO-4 (constitutional compliance).
3. CO-5 appears constitutionally valid — all three sections are attested.
4. Election proceeds on a false constitutional foundation.

**Weakest Link:** The Named Attestation Model creates three independent challenge targets. However, all three attestations are produced by the captured CA. A captured CA that controls all three attestations can make all three appear satisfied.

**Adversary Capability Threshold:** Moderate. Requires organizational control of CA. Does not require compromising any other authority — but without CAB capture, challenge vulnerability remains.

**Constitutional Defenses:**
- ADR6-INV-01: CO-5 requires all three to be independently satisfied. Named Attestation: each is individually challengeable.
- Independent CAB: if CAB is not compromised, challenges can succeed.

**Defense Failures:**
1. Named Attestation challenges require OA-01 (challenger evidence access). If CA controls evidence access, challengers cannot demonstrate false CO-2/CO-3 attestation.
2. CO-4 challenge requires demonstrating EC non-compliance — but if CrA or ASA have been quietly adjusted (TM-17 interface), CO-4 compliance may be technically correct under adjusted standards.
3. CA's internal decision process is not externally auditable. The constitutional architecture has no pre-certification audit of CA's review process.

**Survivability Classification: C-F**

Conditionally fails when: (a) CA issues false CO-5 AND (b) either OA-01 is unresolved (challengers cannot independently verify evidence) OR CAB is not independent. When CAB is independent and OA-01 is resolved, TM-02 alone is S-D — challenges can succeed.

**TM-02's danger is primarily as a coalition threat.** Alone: C-F. Combined with TM-04 (CAB capture): F. See Part D.

**Unresolved Questions:**
- Is CA's internal review process subject to any constitutional disclosure requirement? Named Attestation covers the outcome (CO-2/CO-3/CO-4), not the process.
- Can a CO-2 challenge succeed if the underlying evidence is formally complete but manipulated in content? Or does CO-2 only evaluate presence, not content truth (which is CO-3)?

---

### C.2 TM-04 — Challenge Adjudication Capture

**Class:** 2 (Governance / Adjudication) | **Adversary:** C (Compromised Authority), D (Colluding Authorities)

*TM-04 is the constitutional enforcement mechanism failure. If CA capture (TM-02) destroys the constitutional output layer, TM-04 destroys the constitutional correction layer.*

**Attack Path:**

1. Adversary achieves organizational control of CAB — key personnel, decision criteria, or operational process.
2. CAB systematically dismisses challenges adverse to the adversary's preferred election outcome.
3. Dismissal strategy avoids ADR7-INV-02 violation: dismissals are on "procedural grounds," "lack of standing," or "insufficient evidence" — not on standing manipulation rules that ADR7-INV-02 addresses.
4. Named Attestation challenges are filed by legitimate challengers; all are dismissed.
5. The constitutional challenge architecture is formally operational but substantively captured.

**Weakest Link:** No meta-adjudication mechanism. The constitutional architecture provides one challenge layer. CAB's decisions are final within the constitutional architecture.

**Adversary Capability Threshold:** Moderate to high (requires organizational control of CAB). However, once achieved, the effect is global: all challenges across all constitutional domains are affected simultaneously. This is the highest leverage single-authority capture.

**Constitutional Defenses:**
- ADR7-INV-02: prevents CAB from manipulating standing rules for challenges against itself. Challengers retain formal standing.
- Named Attestation: CO-2/CO-3/CO-4 are constitutionally challengeable. Standing exists.

**Defense Failures:**
1. ADR7-INV-02 prevents procedural self-dealing on standing. It does not prevent adverse merit dismissals. A captured CAB can dismiss on merit without violating ADR7-INV-02.
2. The constitutional architecture has no appellate mechanism. CAB's merit decisions are unreviewable within the architecture.
3. The SA-02 (challenger willingness) social assumption is additionally at risk: if all challenges are systematically dismissed, legitimate challengers eventually stop filing. Challenge attrition via systematic adverse dismissal is not addressed by any ADR.

**Survivability Classification: C-F approaching F**

Conditionally fails when (a) CAB systematically dismisses all adverse challenges AND (b) no alternative enforcement path exists. Condition (b) is currently met. This is the most severe single-authority capture because:
- It does not merely corrupt one constitutional function — it removes correction capacity for ALL constitutional functions simultaneously.
- TM-04 makes every other capture (TM-02, TM-03, TM-17, etc.) permanent and unrecoverable within the constitutional architecture.

**Note:** TM-04 alone (without TM-02) still allows CA to correctly refuse false CO-5 certification. The FAIL classification materializes when TM-04 is combined with TM-02 or any other capture that produces a false CO-5. See Part D.

---

### C.3 TM-11 — Succession Vacancy Attack

**Class:** 2 (Governance) | **Adversary:** B (Malicious Official), C (Compromised Authority), D (Colluding Authorities)

**Attack Path:**

1. ADR7-INV-01 requires EC to pre-designate successors for all seven D43 authority aggregates.
2. TM-11 does not attack incumbent authority members. It attacks the successor designees.
3. Adversary simultaneously eliminates (through legal, organizational, political, or physical means) all pre-designated successors for one or more authority aggregates.
4. Suspension of that authority (triggered by TM-10 or legitimate operational failure) creates a vacancy: incumbent absent + successors eliminated.
5. The constitutional architecture has no provision for what occurs when designated successors are also unavailable.

**Weakest Link:** ADR7-INV-01 requires successor designation but does not specify: (a) how many successors must be designated, (b) whether successor lists must be updated if designees become unavailable, or (c) what constitutional mechanism activates when all designees are eliminated.

**Adversary Capability Threshold:** Moderate. Requires eliminating successor designees before triggering vacancy. Simpler than compromising incumbents (who are typically under observation). Attack window: before suspension, when designee elimination does not trigger constitutional alerts.

**Constitutional Defenses:** ADR7-INV-01 (the succession requirement itself). The existence of designated successors makes single-point succession failure structurally harder.

**Defense Failures:**
1. No minimum designee count is specified in ADR7-INV-01. If EC designates one successor per authority, eliminating that one successor triggers vacancy.
2. No successor update obligation exists. Once designated, successor lists are static.
3. No constitutional mechanism addresses the post-vacancy state when all designees are unavailable.

**Survivability Classification: C-F**

Conditionally fails when: (a) adversary eliminates all designated successors for a target authority AND (b) that authority enters suspension before new successors are designated. The C-F condition requires both adversary action and a suspension trigger. If authority operations are continuous and no suspension occurs, TM-11 has no immediate constitutional effect — but it sets the precondition for subsequent TM-10 (Phase Lock) attacks.

---

### C.4 TM-14 — Authority Self-Amendment

**Class:** 1 (Constitutional) | **Adversary:** C (Compromised Authority)

**Attack Path:**

1. An authority aggregate expands its constitutional scope beyond its EC-defined function through precedent accumulation rather than EC amendment.
2. Example: AuditScopeAuthority begins issuing binding "scope clarifications" that effectively expand Tier 2 evidence sets beyond what ASA's constitutional mandate covers.
3. Example: ChallengeAdjudicationBody begins issuing binding "procedural guidance" that expands or restricts challenge standing in ways that go beyond adjudicating filed challenges.
4. Over multiple election cycles, precedent accumulates. The authority's effective scope diverges from its constitutional mandate.
5. The precedent accumulation is not an EC amendment (which would be detectable) but a constitutional drift within the authority's operational function.

**Weakest Link:** No authority is constitutionally designated to monitor whether authorities are operating within their EC-defined scope (Gap 4 — no constitutional interpretation body). ADR7-INV-02 prevents standing self-manipulation for challenges against the authority itself, but it does not address scope self-expansion through precedent.

**Adversary Capability Threshold:** Low to moderate. Self-amendment through precedent does not require an adversary external to the authority — a compromised internal faction can initiate it. Detection requires a challenger with knowledge of EC scope boundaries and willingness to file a challenge (SA-02 dependency).

**Constitutional Defenses:** ADR7-INV-02: prevents standing manipulation for self-challenges. Named Attestation: CO-4 compliance assessment could detect if an authority's scope expansion violated EC Tier 1 categories.

**Defense Failures:**
1. CO-4 challenge targeting authority scope requires defining what the EC mandated vs. what the authority did. Gap 4 (no interpretation body) means there is no constitutional authority to produce that definition. CAB adjudicates the challenge but must interpret the EC itself to do so — which is exactly the function Gap 4 identifies as absent.
2. Scope self-amendment through precedent may not appear in any challengeable attestation. CO-2/CO-3/CO-4 attest election-level compliance; they do not attest that each authority operated within its constitutional scope.

**Survivability Classification: C-F**

Conditionally fails when: (a) authority self-amendment through precedent violates EC Tier 1 scope constraints AND (b) Gap 4 prevents CAB from producing a binding authoritative interpretation of the violated scope. Condition (b) is currently met (Gap 4 is pre-existing). Condition (a) depends on whether the self-amendment can be expressed within the EC's current language.

---

### C.5 TM-16 — Evidence Authenticity Confusion

**Class:** 4 (Evidence Manipulation) | **Adversary:** B (Malicious Official), C (Compromised Authority), D (Colluding Authorities)

**Attack Path:**

1. Adversary introduces multiple plausible versions of the same evidence record. Rather than producing one false version, the adversary produces several competing versions that each appear authentic.
2. AC-31 reference standard authenticates format and provenance — it verifies that a record has the expected structure and apparent source. When multiple records all have valid AC-31 format signatures, AC-31 cannot determine which is genuine.
3. CA's CO-3 assessment (Evidence Authenticity) requires AC-31 authentication. Under evidence multiplicity, CA cannot produce a definitive CO-3 attestation — it faces the TM-08-B condition: review impossible.
4. Alternative attack variant: CA resolves the ambiguity by selecting the version favorable to the adversary's preferred outcome and attesting CO-3 against it.

**Distinction from TM-34B (Evidence Shadow Copies):** TM-34B creates false copies with false content; TM-16 creates genuine-appearing copies with conflicting content where CA cannot determine which is authoritative. The confusion itself is the attack, not the content of any individual copy.

**Weakest Link:** AC-31 reference standard authenticates evidence records individually, not relatively. When two records are both format-compliant and have valid provenance signatures, AC-31 does not have a constitutional mechanism to determine which represents ground truth.

**Constitutional Defenses:** CO-3 Named Attestation — CA must attest which evidence version it found authentic. Challengers can challenge CO-3 if they can demonstrate that the attested version is not the genuine record.

**Defense Failures:**
1. AC-31 cannot resolve genuine-format multiplicity. No ADR specifies what CA must do when two AC-31-authenticated records conflict.
2. Gap 5 (AC-31 governance unspecified): the mechanism for AC-31 to adjudicate between competing authentic-format records is not constitutionally established.
3. If adversary introduces confusion only for specific evidence categories, CA may produce a partial CO-3 (attesting only non-confused categories) — but this produces a structurally incomplete CO-3, raising TM-08-D (abandoned review) conditions.

**Survivability Classification: C-F**

Conditionally fails when: (a) evidence multiplicity creates genuine AC-31 authentication ambiguity AND (b) Gap 5 prevents AC-31 from constitutionally adjudicating between competing versions. Condition (b) is currently met (Gap 5 pre-existing). Condition (a) depends on adversary capability to produce format-valid competing records.

---

### C.6 TM-17 — Audit Scope Exclusion

**Class:** 4 (Evidence Manipulation) | **Adversary:** C (Compromised Authority), D (Colluding Authorities)

**Attack Path:**

1. AuditScopeAuthority defines the Tier 2 expected evidence set narrowly — excluding categories of evidence that would reveal the adversary's manipulation.
2. The narrow scope is expressible within EC Tier 1 categories (which define permissible evidence categories but not their minimum coverage).
3. CA's CO-2 assessment evaluates evidence completeness against the narrowed scope. The evidence appears complete against the narrow scope.
4. CO-2 is attested as satisfied. The excluded evidence categories — and whatever they would have revealed — are constitutionally invisible.

**Key distinction from B.3 (ASA single capture):** TM-17 is the operationalized threat analysis; B.3 is the baseline capture classification. TM-17 evaluates the specific attack vector: scope definition as an exclusion mechanism rather than a completeness standard.

**Weakest Link:** EC Tier 1 categories define what types of evidence are constitutionally relevant. They do not define the minimum coverage depth within each type. ASA controls depth — a deep or shallow Tier 2 scope is structurally valid as long as it names the right Tier 1 categories.

**Adversary Capability Threshold:** Moderate. Requires ASA compromise. Attack is subtle: the scope appears professionally defined; evidence appears complete against it.

**Constitutional Defenses:** CO-2 Named Attestation: challengers can challenge CO-2 attestation. Named Attestation challenge for TM-17 requires demonstrating that the scope definition itself was constitutionally inadequate — a meta-challenge.

**Defense Failures:**
1. CO-2 challenge evaluates whether evidence was complete against ASA's defined scope. It does not automatically evaluate whether ASA's scope was constitutionally adequate.
2. A scope adequacy challenge requires EC interpretation (is this scope adequate under EC Tier 1?). Gap 4 (no interpretation authority) means CAB must itself interpret EC Tier 1 requirements — without a constitutional precedent or authority to cite.
3. If TM-17 is executed subtly (scope reduced 30% rather than 90%), detection threshold is high.

**Survivability Classification: C-F**

Conditionally fails when: (a) ASA defines scope to exclude evidence categories material to election integrity AND (b) CO-2 challenge against scope adequacy fails due to Gap 4 (no EC interpretation authority). Condition (b) is currently met. Condition (a) depends on ASA capture and adversary knowledge of which evidence categories are most vulnerable.

---

### C.7 TM-19 — AC-31 Reference Standard Capture

**Class:** 3 (Reference Standard) | **Adversary:** C (Compromised Authority), D (Colluding Authorities), E (State-Level)

*This is the highest-priority threat evaluation in 38A-03. Gap 5 (formally elevated) means AC-31 is constitutionally ungoverned. TM-19 attacks an ungoverned concentration point.*

**Attack Path:**

1. Adversary achieves control of the AC-31 reference standard — the authentication mechanism used by CA for CO-3 (Evidence Authenticity) across all elections.
2. AC-31 now authenticates whatever evidence the adversary designates as authentic, including fabricated or falsified records.
3. CA's CO-3 assessment, which depends on AC-31 authentication, is now evaluating authenticity against a compromised reference standard.
4. All future CO-3 attestations are produced against a compromised authentication base.
5. All past CO-5 certifications retroactively depend on a compromised AC-31 — their authenticity foundations are undermined.

**Distinct severity profile from other authority captures:**

| Characteristic | D43 Authority Capture | TM-19 (AC-31 Capture) |
|---|---|---|
| Elections affected | One election cycle | All elections — retroactive + prospective |
| Constitutional challenge mechanism | ADR-5 challenge architecture | None (Gap 5) |
| Succession mechanism | ADR7-INV-01 | None (Gap 5) |
| Detection path | Named Attestation | No constitutional detection mechanism |
| Independence requirement | ADR-2 | Not specified |

**Gap 5 Assessment:** AC-31 governance is entirely unspecified. No body governs it. No challenge covers it. No succession exists for it. The authentication circularity: who authenticates the reference standard is unresolved. AC-31 is a constitutionally ungoverned concentration point with cross-election reach.

**Adversary Capability Threshold:** High for Adversaries C/D (organizational capture of an unspecified governance structure is paradoxically harder when the structure doesn't exist — but also easier because there's no governance to resist); Very high for Adversary E (state-level resources can acquire or compromise a reference standard's operational infrastructure).

**Constitutional Defenses:** None. The Gap 5 finding is definitive: the constitutional architecture has provided zero governance, zero challenge mechanism, and zero succession for AC-31.

**Defense Failures:**
1. No governing body → no resistance to capture
2. No challenge mechanism → no constitutional remedy after capture
3. No succession mechanism → no constitutional recovery path
4. Authentication circularity → self-referential; captured reference standard declares itself authentic
5. Retroactive reach → even if capture is detected, all past CO-5 certifications using compromised AC-31 authentication are now of uncertain integrity

**Survivability Classification: C-F → F** *(Strongest FAIL candidate in 38A-03)*

- **C-F** condition: adversary achieves AC-31 capture (requires adversary action — not unconditional FAIL)
- **F** consequence once triggered: no constitutional detection, challenge, remedy, or recovery mechanism exists within the architecture. The failure, once achieved, is total and permanent within the constitutional architecture's current specification.

This is architecturally distinct from TM-08-B (reclassified C-F approaching F): TM-08-B could be addressed by adding a recovery procedure. TM-19 once triggered produces retroactive and prospective corruption of all evidence authenticity assessments with no architectural path for recovery.

**Evidence for FAIL classification once triggered:**
- Gap 5 establishes zero constitutional governance for AC-31
- No ADR specifies any detection mechanism for AC-31 integrity
- No ADR specifies any authority responsible for AC-31 authenticity challenges
- The retroactive impact means that even if the architecture is corrected, past elections' CO-3 attestations cannot be retroactively re-authenticated

**Unresolved Questions:**
- Is AC-31 a single point of failure or can it be distributed? Distributed AC-31 would reduce TM-19 severity — but no ADR addresses this.
- Should TM-19 be classified as F (unconditional) given that the constitutional architecture currently provides zero governance? The adversary-action requirement makes it technically C-F, but the consequences are F-equivalent.

---

### C.8 TM-36 — Observer Capture

**Class:** 2 (Governance / Observation) | **Adversary:** C (Compromised Authority), D (Colluding Authorities)

**Context:** TM-36 was ARB-added in 38A-01. Constitutional observers hold S-2 standing (the standing class for constitutional oversight agents). TM-36 attacks this standing class — making it adversary-controlled.

**Attack Path:**

1. Observers with S-2 standing are constitutionally authorized to file challenges and report on election conduct.
2. Adversary achieves organizational control of the observer network — through selection of observer personnel, creation of proxy observer organizations, or capture of organizations that hold S-2 standing.
3. Captured observers: file challenges favorable to the adversary (flooding CAB with adversary-controlled challenges — TM-34C interface), suppress adverse observations (SA-02 interface), and provide public legitimacy endorsements for election outcomes produced under other capture attacks.
4. The challenge architecture's independence guarantee degrades: S-2 standing holders appear to be exercising genuine oversight but are serving adversary interests.

**Weakest Link (two simultaneous):**
- S-2 standing class integrity: the architecture assumes S-2 standing holders are genuine constitutional observers. No provision defines what makes an observer organization legitimate (Gap 1 analogue — observer legitimacy is unspecified).
- Challenge quality assessment: CAB adjudicates all challenges, including adversary-filed challenges from captured observers. A high volume of adversary-controlled S-2 challenges consumes CAB adjudication capacity (TM-12 amplifier) and displaces legitimate challenges.

**Constitutional Defenses:** ADR7-INV-02: captured observers cannot manipulate standing rules for challenges against the capture itself. Named Attestation: captured observer challenges, if factually false, can be exposed by independent challengers who have access to the underlying evidence.

**Defense Failures:**
1. No constitutional definition of observer legitimacy. The architecture does not specify what distinguishes a genuine S-2 observer from an adversary-planted S-2 observer.
2. TM-36 used in conjunction with TM-04 (CAB capture) is particularly dangerous: adversary controls both the challenge input (observers) and the adjudication output (CAB). Named Attestation defense fails entirely.
3. TM-36 used in conjunction with TM-35 (Legitimacy Narrative Attack): adversary controls observer reporting AND narrative framing. Technical constitutional compliance appears but public legitimacy perception is adversary-shaped.

**Survivability Classification: C-F**

Conditionally fails when (a) captured observers constitute a significant fraction of active S-2 standing holders AND (b) genuine independent observers are either absent, deterred (SA-02), or outnumbered in CAB adjudication capacity. The C-F escalates to C-F approaching F when TM-36 is combined with TM-04 — at that point, the challenge architecture is adversary-controlled from both input and output.

---

### C.9 TM-39 — Independence Illusion Attack

**Class:** 2 (Governance / Structural) | **Adversary:** D (Colluding Authorities), A (Insider, structural design)  
**Status:** ARB-Authorized — 2026-06-17 Strategic Correction (SC2)

*TM-39 evaluates whether constitutional independence designations guarantee genuine independence. This threat class is characterized as a structural gap as much as an adversarial threat.*

#### The Question ADR-2 Does Not Answer

ADR-2 establishes independence forms for each D43 authority aggregate: Option B (Committee) for most, Option C (External) for CertificationAuthority. Constitutional independence designates the authority structure. It does not specify:
- **Who verifies independence** — no body is constitutionally designated to assess whether an authority is operationally independent
- **What independence requires** — no criteria define minimum operational, informational, or social separation
- **What happens when independence fails** — no challenge mechanism addresses whether an authority is genuinely independent vs. constitutionally independent on paper
- **Whether independence can erode** — no monitoring detects independence erosion over time

#### Attack Surface — Independence Failure Modes

| Failure Mode | Description | Constitutional Violation? | Detectability |
|---|---|---|---|
| **Shared infrastructure** | Independent authorities share the same IT systems, databases, or communication channels | No (ADR-2 is structural, not operational) | Low — technical dependency is invisible to constitutional review |
| **Shared personnel** | Committee members serve on multiple authorities or have prior roles in overseen bodies | No (no restriction on dual membership) | Medium — personnel overlap is discoverable but not challenged |
| **Shared funding** | Independent authorities are funded through budgets controlled by the bodies they oversee | No (no financial independence requirement) | Low — budget relationships are administrative, not constitutional |
| **Shared information** | Authorities rely on the bodies they oversee for the information needed to perform oversight | No | High for detection, Low for remedy (no constitutional recourse) |
| **Shared appointment roots** | All authorities are appointed by the same governance body | No — ADR-7 specifies appointment but not appointing body independence | Low — single appointment chain is a constitutional fact, not a violation |
| **Social capture** | Committee members share professional, social, or organizational networks with overseen bodies | No | Very Low — social networks are not constitutionally measurable |

#### Independence Illusion — Attack Path

1. Adversary D (colluding or systematically designed) creates operational interdependencies among constitutionally independent authorities: shared IT platform, overlapping committee membership, shared funding source, or shared information supply.
2. All seven D43 authorities retain their constitutional independence designations — no ADR is violated.
3. A single operational concentration point (shared database, shared appointing authority, shared budget controller) creates de facto concentration that is constitutionally undetectable and unchallengeable.
4. An attack on the shared operational layer simultaneously compromises multiple constitutionally independent authorities. The constitutional independence designations continue to appear valid while the operational reality is centralized.

**Trust-boundary failure (distinct from ordinary capture):** Named Attestation challenges evaluate whether CA correctly applied the constitutional standard. They do not evaluate whether the CA committee members were genuinely independent of the bodies whose evidence they assessed. Two CA committee members who share an employer with the election administration team will produce Named Attestation challenges that are procedurally valid and constitutionally unassailable — regardless of whether genuine independence existed.

#### What Proves ADR-2 Independence?

The architecture specifies THAT authorities must be independent. It provides no mechanism to prove independence in any given election cycle. The Named Attestation model is the detection mechanism for capture — it cannot detect Independence Illusion because Independence Illusion leaves the attestation process constitutionally intact.

#### Survivability Classification: **F at Adversary D / C-F at Adversary A**

- **Adversary D (systematic operational design):** FAIL. When operational dependencies are designed into the independence structure, the constitutional independence designations are meaningless for practical independence. Multiple authorities can be simultaneously compromised through the shared operational layer while each appears constitutionally independent. No constitutional challenge mechanism exists to address operational concentration.

- **Adversary A (insider access):** C-F. A single insider with access to shared infrastructure can compromise multiple authorities simultaneously, but this requires adversary action, so it is conditional.

**Note on classification:** The F finding for Adversary D differs from prior FAIL findings in this document. Prior FAILs required adversary capture of specific authority functions. TM-39's F finding at Adversary D is architectural: if the independence structure permits operational concentration by design, the constitutional independence designations are constitutively inadequate. This is a design-level FAIL, not a capture-level FAIL.

#### Potential Gap 6

**SC2 Note (ARB 2026-06-17):** TM-39 reveals that the five structural gaps (Gaps 1–5) may be incomplete. The absence of operational independence requirements may constitute Gap 6: *No operational independence standard — ADR-2 establishes constitutional independence forms but no operational independence requirements; who verifies independence, what independence requires operationally, and what happens when independence erodes are all unspecified.* ARB ruling requested on whether TM-39 should be formalized as Gap 6 in 38A-01 G.5.

---

### C.10 — CAB Legitimacy Analysis

**Note:** This is a constitutional authority analysis, not a threat evaluation. It addresses why CAB possesses adjudicatory authority and whether that authority can erode without adversarial capture. *(ARB Strategic Correction SC3 — 2026-06-17)*

#### Three Components of Adjudicatory Authority

Constitutional theory identifies three independent components of legitimate adjudicatory authority. The architecture addresses only one:

| Component | Description | CAB Status |
|---|---|---|
| **Jurisdictional conferral** | By what constitutional instrument does CAB act? | ✅ Specified — ElectionConstitution (L-1), ADR-5, ADR-7 |
| **Procedural legitimacy** | Are CAB's procedures fair, due-process-compliant, and consistently applied? | ❌ Not specified — no constitutional requirement for CAB procedure |
| **Substantive legitimacy** | Are CAB's decisions grounded in a legitimate constitutional standard? | ⚠️ Partial — depends on EC integrity; if EC is captured (TM-01/TM-06), substantive legitimacy is evaluated against a captured reference |

The architecture provides jurisdictional conferral. Procedural and substantive legitimacy are unspecified.

#### Can CAB Lose Legitimacy Without Capture?

**Yes.** Capture (TM-04) requires adversary control. Legitimacy loss is distinct — it can occur without adversary action:

| Legitimacy Loss Mode | Mechanism | Requires Adversary? | Constitutional Remedy? |
|---|---|---|---|
| **Competence failure** | CAB makes consistent legal/constitutional errors; honest but wrong | No | No — no legitimacy challenge mechanism exists |
| **Independence perception failure** | CAB is structurally independent (ADR-2 compliant) but perceived as dependent | No | No — perception is not a constitutional concept |
| **Procedural failure** | CAB violates its own procedures, misses deadlines, issues inconsistent decisions | No | No — no procedural compliance monitor exists |
| **Scope creep (TM-14 interface)** | CAB expands mandate beyond adjudication into rule-making | Partially (requires institutional willingness) | C-F via TM-14 challenge path |
| **Social/political delegitimization** | CAB decisions cease to be accepted as binding by participants | No | No — no constitutional mechanism to restore legitimacy |

#### What Happens When CAB Loses Legitimacy Without Capture?

The architecture provides no mechanism for legitimacy restoration:
- **No legitimacy challenge mechanism:** Challenges adjudicate authority decisions; they cannot challenge the legitimacy of the adjudicating body itself
- **No legitimacy monitoring:** No body is designated to assess whether CAB maintains constitutional legitimacy
- **No legitimacy succession:** If CAB loses procedural or substantive legitimacy, no constitutional process exists to restore or replace it
- **No minimum competence standard:** No ADR specifies the constitutional expertise requirements for CAB membership

#### Constitutional Consequences

**Classification: C-F for legitimacy loss without capture.** If CAB loses legitimacy through non-adversarial means, the constitutional architecture has no internal mechanism to detect, challenge, or remedy this erosion. The consequence is functionally similar to TM-04 (CAB capture) at the enforcement layer — constitutional challenges are adjudicated by a body whose decisions lack legitimacy — but the mechanism is institutional dysfunction rather than adversarial control.

**Relationship to existing findings:**
- TM-04 (Challenge Adjudication Capture): addresses adversarial control of CAB. CAB legitimacy loss is the non-adversarial parallel.
- TM-35 (Legitimacy Narrative Attack): attacks public perception of election legitimacy. CAB legitimacy loss is institutional counterpart — legitimacy erosion through dysfunction, not narrative.
- AW-03-01 (No meta-adjudication body): CAB decisions are unreviewable even if illegitimate. Legitimacy loss is unremedied because there is no higher body to identify or correct it.

**Relation to ADR-7 pre-designation:** ADR7-INV-01 requires successor pre-designation. Legitimacy cannot be transferred to a successor if the delegitimization is structural (competence failure, procedural dysfunction) rather than vacancy. Succession addresses absence; it does not address delegitimization.

---

## Part D — Coalition Analysis

### D.1 Two-Authority Coalition Analysis (TM-18)

*Strategy: group coalitions by which authority pairs create coverage gaps — i.e., which pairs eliminate the primary enforcement mechanisms for each other's constitutional violations.*

#### D.1a Coalitions Including CA

The key structural insight: CA produces CO-5 (election validity). Any coalition that includes a captured CA must also neutralize the Named Attestation challenge path to approach FAIL territory.

| Coalition | Attack Surface | Challenge Path Status | Classification |
|---|---|---|---|
| **CA + CAB** | False CO-5 + challenge dismissal | **Destroyed** — CAB dismisses all challenges | **F** ← highest severity |
| CA + ASA | False CO-2 against narrowed scope | Weakened — scope challenge path unclear (Gap 4) | C-F approaching F |
| CA + GA | False CO-5 + GovernanceState corruption | Weakened — GovernanceState evidence circular (TM-03) | C-F |
| CA + AEA | False CO-2/CO-3 + falsified audit supply chain | Weakened — OA-01 dependency for challengers | C-F |
| CA + EA | False CO-2 + enrollment gaps | Weakened — OA-01 dependency for enrollment evidence | C-F |
| CA + CrA | False CO-4 + narrowed criteria | Weakened — CO-4 challenge requires EC interpretation (Gap 4) | C-F |

**CA + CAB coalition** is the primary FAIL finding from two-authority coalitions:
- CA issues false CO-5 with falsified CO-2/CO-3/CO-4 attestations
- All Named Attestation challenges are filed with CAB
- CAB dismisses all challenges on adverse merit grounds
- No remaining constitutional enforcement mechanism
- The election is certified on a false constitutional foundation and all challenges to it are dismissed
- **Classification: F** — constitutional failure complete; no internal constitutional remedy path

#### D.1b Coalitions Including CAB (Not CA)

When CAB is captured but CA is not, constitutional rule violations can still produce CO-5 — but other authorities' violations become unrecoverable.

| Coalition | Primary Effect | CA Position | Classification |
|---|---|---|---|
| **CAB + GA** | GovernanceState corruption unchallenged | CA must certify against corrupted GovernanceState | C-F approaching F |
| CAB + ASA | Scope exclusion unchallenged | CA certifies against manipulated scope | C-F approaching F |
| CAB + EA | Enrollment exclusion unchallenged | CA cannot detect enrollment gaps if challengers cannot | C-F |
| CAB + CrA | Criteria manipulation unchallenged | CA certifies against manipulated criteria | C-F |
| CAB + AEA | Audit falsification unchallenged | CA certifies against falsified audit results | C-F approaching F |

**CAB + GA** is notable: GovernanceState corruption (TM-03 C-F with all conditions currently met) combined with challenge dismissal produces a scenario where phase manipulation and timing attacks are permanently unrecoverable within the constitutional architecture.

#### D.1c Coalitions Including Neither CA Nor CAB

Without CA or CAB compromise, independent certification and challenge enforcement remain. These coalitions degrade evidence quality but face functioning detection mechanisms.

| Coalition | Primary Effect | Detection Path | Classification |
|---|---|---|---|
| ASA + AEA | Complete audit supply chain control | CO-2/CO-3 Named Attestation if OA-01 resolved | C-F |
| GA + ASA | Phase manipulation + scope exclusion | GovernanceState challenge if evidence access | C-F |
| EA + CrA | Enrollment exclusion + criteria manipulation | CO-2/CO-4 challenges with access | C-F |

#### D.1d Additional Coalition Families — Hidden Trust-Boundary Failures

*(ARB Strategic Correction SC1 — 2026-06-17: expand coalition search to find hidden trust-boundary failures rather than obvious authority concentration)*

The coalitions above focus on enforcement path destruction. SC1 identifies a second failure mode: **trust-boundary failures** where two authorities control complementary invisibility mechanisms such that their combined effect is undetectable even through functioning Named Attestation challenges.

**EA + ASA — Enrollment Suppression + Audit Invisibility**

EA selectively excludes voters from enrollment records. ASA defines audit scope to exclude the evidence categories that would reveal the enrollment gaps. The trust-boundary failure: Named Attestation's CO-2 challenge evaluates evidence completeness against ASA's defined scope — not against the universe of eligible voters. If ASA has scoped away the enrollment evidence, CO-2 challenge succeeds (evidence is complete against scope) while enrollment manipulation is constitutionally invisible.

The combined effect is greater than the sum of the parts: EA's exclusion leaves detectable gaps; ASA's scope narrowing leaves visible scope reduction. Together, they close the detection gap — EA removes the voters; ASA removes the evidence that would show they were removed. A challenger must penetrate both manipulations simultaneously.

**Classification: C-F approaching F.** The Named Attestation challenge path functions but evaluates against the wrong standard (ASA's narrowed scope). Detection requires a challenger who independently knows (a) who should have been enrolled and (b) what evidence categories should have been in scope — external knowledge that the constitutional architecture has no obligation to provide.

---

**CrA + ASA — Criteria Exclusion + Evidence Exclusion**

CrA narrows eligibility criteria. ASA narrows audit scope to exclude evidence of who was excluded by the narrowed criteria. The criteria change appears legitimate (within EC Tier 1). The scope change appears professional (audit focus). Together: the criteria exclude a demographic; the scope excludes evidence that the demographic was excluded.

Unlike EA+ASA, the CrA+ASA coalition leaves the criteria visible (CrA decisions are public) but makes their exclusionary effect invisible (the evidence showing impact on specific populations is out of scope). Detection requires connecting a visible criteria change to an invisible evidence gap.

**Classification: C-F.** Slightly less severe than EA+ASA because the criteria change is publicly visible — a challenger who notices the criteria narrowing can challenge CO-4 independently of the scope manipulation. However, the evidence to prove exclusionary impact remains out of scope.

---

**GA + CAB — Phase Corruption + Challenge Immunity**

GA corrupts GovernanceState (TM-03: C-F with all conditions currently met). CAB dismisses all challenges to the corrupted GovernanceState. The phase record is corrupted; challenges to the corruption are dismissed; the corrupted record becomes the authoritative constitutional history of the election.

Trust-boundary failure: GovernanceState is the sole authoritative phase record (AA-07). The constitutional architecture has no corroboration mechanism. When challenges to GovernanceState corruption are dismissed, the corruption becomes constitutionally certified.

**Classification: C-F approaching F.** This coalition makes TM-03 (already C-F with all conditions currently met) permanent and unrecoverable. GA corruption alone leaves a challenge path; CAB capture alone has a functioning GovernanceState to expose. Together: corruption + immunity. The GA C-F conditions being currently met means this coalition is one CAB capture away from approaching-F status.

---

**GA + CA — Phase Corruption + Certification Circularity**

GA corrupts GovernanceState. CA certifies the election using the corrupted GovernanceState as the authoritative phase record. CO-4 evaluates constitutional compliance against the corrupted record — finding compliance because the record itself has been manipulated to show compliance.

Trust-boundary failure: CA is constitutionally independent and evaluates CO-4 against what GovernanceState says happened. If GovernanceState says every phase was completed correctly, an honest and independent CA will produce a constitutionally valid CO-5. Independence is real; the input is false.

**Classification: C-F approaching F.** The coalition version of TM-03's circularity: challengers must use GovernanceState to prove GovernanceState corruption (AA-07: sole authoritative record). When CA's CO-4 also relies on GovernanceState, the circularity extends to certification — CO-4 challenges evaluate compliance against the corrupted record.

---

**EA + CrA + ASA — Full Enrollment-to-Audit Suppression Chain**

EA excludes voters. CrA narrows criteria to justify the exclusion retroactively. ASA narrows scope to exclude evidence of both the exclusion and the criteria manipulation. Three-authority enrollment-to-audit pipeline: voters removed, criteria adjusted to make removal appear legitimate, audit scoped to avoid detecting either.

This extends EA+ASA and CrA+ASA into a full chain. Detection requires penetrating three coordinated manipulations across three constitutionally independent authorities. The independence forms (all Option B committees per ADR-2) make coordination detectable in theory — shared personnel, funding, or infrastructure (TM-39) would be the coordination mechanism — but not within the challenge architecture.

**Classification: C-F approaching F.** The full suppression chain makes detection structurally impractical even with functioning challenge mechanisms. A challenger must independently reconstruct who should have been enrolled, what criteria applied to them, and why the audit evidence for both is missing — without access to any of that evidence (OA-01 dependency).

---

| Coalition | Trust Boundary Violated | Detection Mechanism | Classification |
|---|---|---|---|
| EA + ASA | CO-2 evaluates against wrong scope | External enrollment knowledge required | C-F approaching F |
| CrA + ASA | CO-4 visible; CO-2 scope conceals impact | Criteria visible; impact invisible | C-F |
| GA + CAB | GovernanceState sole record + challenge immunity | None (AA-07 + TM-04 combined) | C-F approaching F |
| GA + CA | GovernanceState → CO-4 circularity | GovernanceState challenge circular | C-F approaching F |
| EA + CrA + ASA | Full suppression chain | External knowledge at all three layers | C-F approaching F |

---

### D.2 Three-Authority Coalitions (Highest Risk)

Evaluating only the highest-risk triples. Full combinatorial analysis deferred to 38A-06.

| Coalition | Mechanism | Enforcement Paths Remaining | Classification |
|---|---|---|---|
| **CA + CAB + GA** | False CO-5 + challenge dismissal + GovernanceState corruption | None | **F** |
| **CA + CAB + ASA** | False CO-5 + challenge dismissal + scope exclusion | None | **F** |
| **CA + CAB + AEA** | False CO-5 + challenge dismissal + falsified audit supply | None | **F** |
| **GA + ASA + CAB** | Phase corruption + evidence invisibility + challenge dismissal | None | **F** *(SC1 — new FAIL finding)* |
| CA + ASA + AEA | Complete audit supply chain + false CO-2/CO-3 | CAB challenge path remains | C-F approaching F |
| CAB + GA + ASA | All challenges dismissed + GovernanceState + scope | CA only enforcement; no challenge path | C-F approaching F |

**Any triple including CA + CAB = F.** The presence of both CA and CAB in a coalition destroys the constitutional enforcement architecture completely. Adding a third authority makes the failure broader but the CA + CAB pair is the sufficient condition for F.

**SC1 New FAIL Finding — GA + ASA + CAB = F:** This coalition achieves constitutional failure without CA. GA corrupts GovernanceState (the authoritative phase record — AA-07); ASA defines audit scope to exclude the evidence that would expose GovernanceState corruption; CAB dismisses all challenges. The result: GovernanceState corruption is constitutionally certified by challenge dismissal, constitutionally concealed by evidence exclusion, and constitutionally documented by GovernanceState itself. No CA participation required. CA may produce an honest CO-5 against a constitutionally corrupted process — making this coalition particularly dangerous because it can produce a formally clean election outcome (uncaptured CA, valid CO-5) while the underlying constitutional process was corrupt. *(ARB Strategic Correction SC1 — 2026-06-17)*

---

### D.3 Concentration Chain Analysis (TM-06)

*38A-01 F.1 explicitly requires evaluation of partial capture scenarios before full classification.*

#### D.3a Partial Capture — EC Captured, CA Independent

EC defines CO-4 compliance. If EC is captured via TM-01 (MA amendment threshold achieved), CA evaluates CO-4 against the captured EC.

**Critical finding:** An honest, independent CA evaluating CO-4 against a captured EC will produce a constitutionally valid CO-5. The CA is not malfunctioning — it is correctly applying the standard it has been given. The standard has been corrupted.

- CO-4 Named Attestation challenge: challengers challenge CA's CO-4 attestation. CAB adjudicates against the same captured EC. If CA applied the captured EC correctly, CO-4 challenge fails.
- The constitutional architecture has no mechanism to challenge the EC itself — only to challenge compliance with the EC.
- **Classification: C-F → approaching F** (this is TM-01's C-F finding materialized at the certification layer). Once EC is captured and a CO-5 is issued, the challenge architecture evaluates compliance with the captured EC as its constitutional reference — making constitutional remedy through the internal challenge architecture structurally impossible.

#### D.3b Partial Capture — CA Captured, EC Independent

CA is compromised; EC remains unmanipulated. Named Attestation challenges can succeed because:
- CO-4 compliance is evaluated against the genuine EC
- CAB adjudicates against the genuine EC
- If CA falsely attested CO-4, a challenger can demonstrate the genuine EC's requirements were not met

**Classification: C-F** — this is TM-02 alone (already evaluated in C.1). With independent CAB and OA-01 resolved, the challenge path functions. Without independent CAB, this degrades to CA + CAB coalition → F.

#### D.3c Full Concentration Chain — EC + CA Both Captured (TM-06 Full)

Both EC and CA are captured.

**The Circularity:**

```
Captured EC defines CO-4 compliance standard
    ↓ evaluated by
Captured CA producing CO-5 attestations
    ↓ challenges adjudicated by
CAB (if independent) evaluating CO-4 compliance
    ↓ evaluated against
Captured EC as the constitutional reference
    ↓ result
Any CO-4 challenge evaluates whether CA applied the captured EC correctly
If CA applied captured EC correctly → challenge fails
```

Even with an independent CAB, TM-06 full produces an unbreakable circularity: the challenge architecture uses the captured EC as its reference. Genuine CO-4 non-compliance under a pre-capture EC cannot be demonstrated using the post-capture EC as the evaluation standard.

**Classification: F** — once EC + CA are both captured:
- CO-4 circularity is complete
- CO-2 and CO-3 attestations from captured CA cannot be externally verified if OA-01 is unresolved
- Even independent CAB cannot break the CO-4 circularity
- The captured EC IS the constitution — it has no external referent within the constitutional architecture

**TM-06 full is the fastest execution path for Constitutional Self-Destruction (C.4 of 38A-02):** A single election cycle with both EC + CA captured can produce an irreversible constitutional shift. Named Attestation challenges evaluate CO-4 compliance against the captured standard; ADR6-INV-01 is applied against the captured standard; all constitutional invariants are evaluated against a captured standard.

---

## Part E — Authority Capture Matrix (Deliverable)

### E.1 Single Authority Capture Matrix

| Authority | Capture Classification | Primary Defense | Defense Failure Condition | Critical Dependencies |
|---|---|---|---|---|
| EA (EnrollmentAuthority) | **C-F** | CO-2 Named Attestation | OA-01 unresolved; challengers cannot access enrollment records | OA-01 |
| CrA (CriteriaAuthority) | **C-F** | CO-4 Named Attestation | Gap 4; CAB cannot produce authoritative EC Tier 1 interpretation | Gap 4 |
| ASA (AuditScopeAuthority) | **C-F** | CO-2 Named Attestation (scope adequacy) | Gap 4; scope adequacy challenge requires EC interpretation | Gap 4 |
| AEA (AuditExecutionAuthority) | **C-F** | CO-3 Named Attestation | OA-01; challengers cannot access underlying evidence | OA-01 |
| GA (GovernanceAuthority) | **C-F** (all conditions currently met — approaching F) | GovernanceState challenge | AA-07 (sole record); OA-01 circular challenge evidence | AA-07; OA-01 |
| CA (CertificationAuthority) | **C-F** | ADR6-INV-01; Named Attestation | OA-01 and/or CAB capture | OA-01; TM-04 |
| CAB (ChallengeAdjudicationBody) | **C-F approaching F** (no alternative enforcement) | ADR7-INV-02 (no self-dealing) | No appellate mechanism in architecture | — |

**Single D43 authority severity ranking (most → least dangerous):**
1. CAB: removes enforcement capacity for all other constitutional violations simultaneously
2. CA: produces invalid CO-5; recoverable only with independent CAB + OA-01
3. GA: all conditions for C-F currently met; approaching F
4. ASA: scope manipulation is subtle and difficult to challenge without EC interpretation
5. AEA: audit supply chain corruption; detectable with OA-01 resolved
6. EA: enrollment manipulation; detectable with OA-01 resolved
7. CrA: criteria manipulation; most visible because criteria are public

**Note:** AC-31 is not a D43 authority aggregate and is not ranked here. The full concentration ranking (including AC-31 and GovernanceState) is evaluated in E.4 below. Revised finding per 38A-04-ARB-Review: **AC-31 > GovernanceState > CAB > CA** by constitutional blast radius. GovernanceState admitted to the concentration group per ARB Correction C-4 (2026-06-17). *(SC5 — ARB 2026-06-17; updated 38A-04-ARB-Review 2026-06-17)*

---

### E.2 Coalition Failure Matrix

| Coalition | Classification | Mechanism | Enforcement Path | Condition for Elevation |
|---|---|---|---|---|
| **CA + CAB** | **F** | Certification capture + challenge dismissal | None | Already FAIL |
| **EC + CA (TM-06 full)** | **F** | CO-4 circularity; captured EC IS the standard | None (even independent CAB uses captured EC as reference) | Already FAIL |
| **TM-19 (AC-31 capture)** | **C-F → F** | AC-31 controls CO-3 for all elections; Gap 5 = no remedy | None post-capture | Adversary capture of AC-31 |
| CA + CAB + any third | **F** | All enforcement removed; third authority extends corruption depth | None | Already FAIL |
| CAB + GA | C-F approaching F | GovernanceState corruption + challenge dismissal | None within architecture | Trivial condition (GA C-F all conditions met) |
| CA + ASA | C-F approaching F | False CO-2 against narrowed scope; scope challenge unclear | Challenged with Gap 4 unresolved | Gap 4 resolution |
| ASA + AEA | C-F | Complete audit supply chain | CA (if independent) + CAB Named Attestation | OA-01 resolution |

---

### E.3 FAIL Findings Summary (38A-03)

**Three FAIL findings produced:**

| # | Threat / Coalition | Classification | Key Reasoning | Adversary Required |
|---|---|---|---|---|
| 1 | **CA + CAB (TM-02 + TM-04)** | **F** | Named Attestation challenge path is the only constitutional enforcement mechanism for CA capture; CAB capture eliminates it completely; no alternative enforcement mechanism exists | D (dual authority capture) |
| 2 | **TM-06 full (EC + CA)** | **F** | CO-4 circularity: the captured EC IS the constitutional standard; even an independent CAB evaluates CO-4 compliance against the captured EC; no external constitutional referent exists | D/E (EC capture via MA threshold + CA capture) |
| 3 | **TM-19 (AC-31 Reference Standard Capture)** | **C-F → F** | Gap 5: AC-31 has zero constitutional governance, zero challenge mechanism, zero succession; retroactive and prospective impact on all elections' CO-3 assessments; no constitutional recovery path once triggered | C/D/E (capture of ungoverned concentration point) |

**Zero-FAIL check:** 38A-03 produces four FAIL findings (two unconditional coalition FAILs + one C-F → F for TM-19 + one SC1 new FAIL finding GA+ASA+CAB + TM-39 structural F at Adversary D). The zero-FAIL discipline from 38A-01 A.4 is satisfied: this round did not produce zero failures.

---

### E.4 Concentration Ranking — CAB, CA, AC-31, GovernanceState by Constitutional Blast Radius

*(ARB Strategic Correction SC5 — 2026-06-17; updated per 38A-04-ARB-Review Correction C-4 — 2026-06-17)*

The ARB identified three principal concentration points in SC5 (CAB, CA, AC-31). The 38A-04-ARB-Review formally admitted GovernanceState to the concentration group (Correction C-4): GovernanceState's challengeability is lower than any D43 authority aggregate because Named Attestation challenges evaluate compliance WITH GovernanceState — they cannot override GovernanceState based on external evidence alone (self-referential challenge problem). Ranking is by constitutional blast radius across four concentration points.

| Criterion | GovernanceState *(ARB C-4 addition)* | CAB | CA | AC-31 |
|---|---|---|---|---|
| **Elections affected** | Current election — all phase-dependent actions simultaneously | Current cycle — all ongoing challenges | Current cycle — one certification | **ALL elections — retroactive + prospective** |
| **Recoverability** | **None** (AA-07 sole record; no override mechanism within architecture) | Appeal to Membership Assembly (ADR-5) — high institutional bar | Challenge + recertification (if CAB independent + OA-01 resolved) | **None (Gap 5 — no recovery mechanism)** |
| **Detectability** | **Low** — corruption mimicking valid transitions constitutionally indistinguishable from genuine transitions | Challenge dismissal patterns observable | Named Attestation challenges can expose false CO-2/CO-3/CO-4 | **None (Gap 5 — no constitutional detection mechanism)** |
| **Challengeability** | **Lowest** — Named Attestation evaluates compliance WITH GovernanceState; cannot override GovernanceState (self-referential challenge problem) | Appeal to Membership Assembly (external mechanism) | Named Attestation (CO-2/CO-3/CO-4 independently challengeable — ADR-6) | **None (Gap 5 — no challenge mechanism for AC-31)** |
| **Retroactive impact** | None across election cycles (current election only) | None — CAB adjudications are per-cycle | None — CO-5 is per-election certification | **Total — all past CO-3 assessments compromised** |
| **Constitutional function** | Temporal root — sole authoritative phase record; challenges depend on it, not challengeable through it | Enforcement removal — removes the correction mechanism | Output corruption — produces false terminal constitutional act | **Reality corruption — corrupts the evidentiary basis of all elections** |
| **Single-point classification** | C-F / structural amplifier (TM-40); Candidate Gap 7 | C-F approaching F | C-F | **C-F → F (Gap 5; once triggered: no recovery)** |

#### Ranking by Constitutional Blast Radius

**1. AC-31 — Largest constitutional blast radius**

AC-31's capture affects all elections simultaneously (retroactive and prospective), leaves no constitutional recovery mechanism (Gap 5), provides no constitutional detection mechanism, and produces no challengeable constitutional act. Every CO-3 attestation (Evidence Authenticity) in every election depends on AC-31. Once AC-31 is compromised, the evidentiary foundation of the entire electoral system is undermined.

AC-31 "affects reality itself" — not the enforcement of constitutional violations (CAB) or the output of constitutional assessment (CA), but the fundamental question of what evidence is authentic. This is a pre-certification failure: before CA can assess CO-3, before CAB can adjudicate challenges, the authentication reference on which both depend has been compromised.

The only constraint on AC-31's severity is that capture requires adversary action (C-F condition). Gap 5 ensures there is nothing to resist capture once it is attempted.

**2. CAB — Broadest per-cycle constitutional impact**

CAB's capture removes constitutional enforcement capacity for ALL violations in the current cycle — not just certification violations (CA) but enrollment violations (EA), criteria violations (CrA), scope violations (ASA), audit violations (AEA), and governance violations (GA). Every authority's constitutional violations become unrecoverable when CAB is captured.

CAB is recoverable through Membership Assembly appeal (ADR-5), but this requires the Assembly to function as a trial-level adjudicator for every dismissed challenge — operationally infeasible at scale. Retroactive impact is limited to the current cycle: prior cycles' adjudications are final.

**3. CA — Deepest single-function impact**

CA corrupts the terminal constitutional act: election validity (CO-5). This is the most visible and most challengeable of the three concentration points. Named Attestation (CO-2/CO-3/CO-4) provides a structured challenge framework. With independent CAB and OA-01 resolved, CA capture has defined recovery paths.

CA's blast radius is limited to one election cycle per capture event. It does not affect other elections' certifications, and prior certifications remain valid. CA capture is severe but architecturally the most recoverable of the three.

#### Revised Concentration Finding (SC5 + ARB Correction C-4)

**The single-authority severity ranking (E.1) applies within the D43 authority tier only.** For overall concentration ranking across all constitutional mechanisms (updated to include GovernanceState per 38A-04-ARB-Review Correction C-4):

**AC-31 > GovernanceState > CAB > CA**

- **AC-31**: cross-election, retroactive, no-recovery, no-detection — exceeds any D43 authority aggregate by constitutional blast radius
- **GovernanceState** *(added per ARB Correction C-4)*: within-election temporal root; challengeability is LOWER than any D43 authority because challenges evaluate compliance WITH GovernanceState rather than challenging GovernanceState itself (self-referential challenge problem); shares self-referential property with AC-31 (Candidate Gap 7 — GovernanceState Phase Record Governance); ranked above CAB because its challengeability is constitutionally lower despite its cross-election reach being limited to one cycle
- **CAB**: removes enforcement for all constitutional violations within a cycle; the most severe D43 authority aggregate; has MA appeal path (theoretically)
- **CA**: deepest per-function impact within its election cycle but highest recoverability and challengeability; Named Attestation provides structured challenge framework

This ranking revises AW-03-01's framing: AW-03-01 correctly identifies no meta-adjudication for CAB as Critical. In the full concentration landscape, both AC-31 (Gap 5) and GovernanceState (Candidate Gap 7) represent constitutional roots without governance — potentially more dangerous than any D43 authority because they are the references against which authority is evaluated, not authorities subject to the same challenge mechanisms.

---

## Part F — Architectural Weakness Register (38A-03 Findings)

| Weakness ID | Description | Threat(s) | Severity |
|---|---|---|---|
| AW-03-01 | No meta-adjudication body — CAB decisions are constitutionally unreviewable | TM-04 | Critical |
| AW-03-02 | No observer legitimacy standard — S-2 standing class has no constitutional legitimacy definition | TM-36 | High |
| AW-03-03 | No successor designee minimum count or update obligation in ADR7-INV-01 | TM-11 | High |
| AW-03-04 | CA + CAB two-authority coalition produces complete constitutional enforcement collapse | TM-02 + TM-04 | Critical (FAIL) |
| AW-03-05 | EC + CA concentration chain produces CO-4 circularity; captured EC is constitutionally self-validating | TM-06 | Critical (FAIL) |
| AW-03-06 | AC-31 governance gap means TM-19 attacks an entirely ungoverned concentration point with retroactive and prospective election-wide impact | TM-19 | Critical (C-F → F) |
| AW-03-07 | No appellate challenge mechanism — constitutional architecture is single-layer adjudication | TM-04, TM-14 | High |
| AW-03-08 | Scope adequacy challenges (TM-17) require EC interpretation; Gap 4 means no authoritative interpretation exists | TM-17, TM-14 | Medium |
| AW-03-09 | No operational independence standard — ADR-2 specifies constitutional independence forms but not operational independence; who verifies, what it requires, and what happens when it erodes are unspecified | TM-39 | Critical (structural) |
| AW-03-10 | CAB legitimacy has no restoration mechanism — CAB can lose procedural or substantive legitimacy without adversary action; no constitutional monitoring, challenge, or succession addresses legitimacy erosion | C.10 | High |
| AW-03-11 | Constitutional ratchet — once EC is amended and a CO-5 is issued against the new EC, the amended EC is constitutionally established as the future reference; each subsequent election cycle under a captured EC further entrenches it; TM-01's C-F condition, if triggered and propagated through one cycle undetected, may be constitutionally irreversible | TM-01, TM-09 | Critical |
| AW-03-12 | GA + ASA + CAB produces constitutional failure without CA — a formally clean CO-5 can coexist with a constitutionally corrupt process when phase record, evidence, and challenge mechanisms are all manipulated with CA remaining independent and honest | SC1 (D.2) | Critical (FAIL) |

---

## Part G — Observations and Open Questions

### G.1 Key Structural Observations

**OBS-38A03-01: CA + CAB Is the Minimum Sufficient FAIL Coalition**
The CA + CAB two-authority coalition achieves a constitutional enforcement collapse that no single authority achieves alone. CA produces the false CO-5; CAB eliminates all correction capacity. Every additional authority in the coalition adds corruption depth but does not change the failure classification — the FAIL is already achieved with two authorities.

**OBS-38A03-02: TM-06 Full Is the Fastest Constitutional Self-Destruction Path**
The concentration chain (EC + CA both captured) produces the fastest execution of the Constitutional Self-Destruction pattern (38A-02 C.4). EC capture is achievable via TM-01 (MA amendment threshold); CA capture is achievable via TM-02. Together they produce a single-election FAIL that is architecturally irreversible within the constitutional architecture because the EC itself is the constitutional reference.

**OBS-38A03-03: TM-19 Is Structurally Unique — The Only Cross-Election FAIL Candidate**
All other FAIL findings (CA + CAB; TM-06 full) affect one election cycle. TM-19 (AC-31 capture) affects all elections simultaneously, retroactively and prospectively. This makes TM-19 the highest leverage attack in the catalog even though it requires adversary action to trigger.

**OBS-38A03-04: TM-36 Is a Threat Amplifier, Not a Primary Threat**
Observer Capture (TM-36) does not independently produce FAIL. It amplifies existing threats:
- TM-36 + TM-04: adversary controls both challenge input (observers) and challenge adjudication (CAB) → enforcement architecture is adversary-controlled from input to output
- TM-36 + TM-35: adversary controls both technical observer reports and public legitimacy narrative
- TM-36 + TM-12: adversary floods CAB with adversary-controlled observer challenges → displaces legitimate challenges

**OBS-38A03-05: The Partial TM-06 Finding Is Architecturally Significant**
EC captured alone (partial TM-06, Section D.3a) produces a constitutional circularity at the challenge layer even with an independent CA and CAB. This means TM-01 (EC capture, classified C-F in 38A-02) produces secondary FAIL-equivalent effects at the challenge architecture level that 38A-02 did not fully evaluate. The CO-4 challenge path evaluates compliance with the captured EC — making TM-01's C-F finding produce near-FAIL consequences at the institutional layer.

**OBS-38A03-06: EC Capture Detection Is Structurally Impossible Within the Architecture — Constitutional Ratchet** *(SC4 — ARB 2026-06-17)*

The constitutional architecture has no mechanism for detecting EC capture after the fact:
- **No constitutional court:** No body can review whether an EC amendment is constitutional — the EC IS the constitution
- **No post-amendment challenge:** Once ratified, an amendment IS the constitution; challenges to it must demonstrate non-compliance with the EC, but the amendment cannot violate itself
- **Circularity of review:** To challenge an EC amendment as unconstitutional, a challenger must demonstrate the amendment violates the EC. Post-ratification, the amendment is part of the EC. Self-contradiction is impossible.

The only detection mechanism is external — Membership Assembly recognizing a prior ratification as procedurally or substantively invalid and reversing it. This requires the Assembly to act against its own prior decision — a high institutional bar that the constitutional architecture does not facilitate.

**Constitutional ratchet:** Once an EC amendment is ratified and a CO-5 is issued against the amended EC, the amendment is constitutionally established as the future reference. Each subsequent election cycle under a captured EC further entrenches the captured standard — each CO-5 issued under the captured EC is valid, each challenge adjudicated against the captured EC is correctly decided. The architecture has no cumulative drift detection mechanism (TM-09): each amendment is individually valid; the cumulative effect is never constitutionally evaluated. TM-01's C-F condition, if triggered and propagated through one election cycle undetected, may produce a constitutionally irreversible transformation. This does not change TM-01's classification (adversary action still required), but it dramatically raises the severity characterization: TM-01's C-F → permanent F-level consequence if the condition is triggered.

**OBS-38A03-07: TM-39 (Independence Illusion) Is a Design-Level FAIL at Adversary D** *(SC2 — ARB 2026-06-17)*

TM-39's F finding at Adversary D differs from all prior FAIL findings in this document. Prior FAILs (CA+CAB, EC+CA, GA+ASA+CAB) required adversary capture of specific authority functions. TM-39's design-level FAIL at Adversary D is architectural: if the independence structure permits operational concentration by design, the constitutional independence designations are constitutively inadequate regardless of adversary action.

This introduces a new failure category in the 38A taxonomy: **design-level structural failures** — failures that exist as a matter of architectural specification rather than adversary action. The architecture has specified constitutional independence without specifying operational independence. At Adversary D, this is sufficient to produce effective concentration across multiple constitutionally independent authorities through a single operational compromise.

ARB question raised: should TM-39's design-level FAIL at Adversary D be considered as a prospective Gap 6, or as a new threat class requiring a dedicated architectural response before 38A is complete?

### G.2 Threat-to-Authority Mapping

For each ARB-authorized threat, the controlling authority relationships:

| Threat | Primary Authority | Coalition Amplifier | Minimum for FAIL |
|---|---|---|---|
| TM-02 (Certification Capture) | CA | + CAB → F | CA + CAB |
| TM-04 (Challenge Adjudication Capture) | CAB | + CA → F | CA + CAB |
| TM-06 (Concentration Chain) | EC + CA | None needed | EC + CA |
| TM-18 (Two-Authority Coalition) | CA + CAB (strongest) | Any third | CA + CAB |
| TM-19 (AC-31 Capture) | AC-31 (Gap 5) | Gap 5 makes it standalone | AC-31 alone |

### Open Questions for Round 38A

**OQ-38A03-01:** Should TM-19 be classified as unconditional F rather than C-F → F? Given Gap 5, the only condition (adversary capture of AC-31) is constrained only by adversary capability, not by any constitutional defense. The gap between "C-F → F" and "F" may be smaller for TM-19 than the adversary-action requirement implies.

**OQ-38A03-02:** The EC captured (partial TM-06) scenario produces CO-4 challenge architecture circularity — does this mean TM-01 (currently C-F) should be considered as having FAIL-level consequences when a CO-5 is issued under the captured EC? Should TM-01 be re-evaluated in 38A-06 to account for this secondary effect?

**OQ-38A03-03:** ADR7-INV-02 prevents CAB from self-manipulating standing rules. Does it also prevent CA from restricting standing for challenges against CA's own CO-5 attestations? If not, CA single capture may be more severe than the C-F classification suggests.

**OQ-38A03-04:** AC-31 distribution as a remediation strategy: if AC-31 is distributed across multiple independent reference holders, TM-19 severity decreases. Is distributed AC-31 addressable within the current constitutional architecture framework, or does it require a new architectural element? This is a design question for Round 38B.

**OQ-38A03-05:** Should TM-39 (Independence Illusion) be formalized as Gap 6 in 38A-01 G.5? The absence of operational independence requirements is structurally analogous to the five existing gaps — a missing governance provision with constitutional consequences. TM-39's design-level FAIL at Adversary D is more severe than some existing gaps (e.g., Gap 3 — Amendment Process unspecified) in terms of blast radius. ARB ruling requested on whether Gap 6 should be formally elevated or whether TM-39 should remain classified purely as a threat rather than a structural gap. *(SC2 — ARB 2026-06-17)*

---

## Part H — ARB Decision Block

### Document Status

**[APPROVED WITH STRATEGIC CORRECTIONS APPLIED — 2026-06-17 | ARB Score: 9.8/10]**

### Findings Summary (Revised with Strategic Corrections)

1. **Four FAIL findings:** CA + CAB coalition (F), EC + CA concentration chain / TM-06 full (F), GA + ASA + CAB non-CA coalition (F — SC1 new finding), TM-19 AC-31 capture (C-F → F once triggered). TM-39 produces structural F at Adversary D.

2. **Single-authority analysis complete:** Seven D43 authorities evaluated; CAB single capture (C-F approaching F) remains most severe D43 authority finding. Full concentration ranking (E.4): AC-31 > CAB > CA by constitutional blast radius. AC-31 is not a D43 authority but exceeds all D43 authority blast radii.

3. **Coalition analysis expanded (SC1):** CA + CAB is the minimum sufficient FAIL coalition (unchanged). New hidden trust-boundary failure coalitions identified: EA+ASA (C-F approaching F), GA+CAB (C-F approaching F), GA+CA (C-F approaching F), EA+CrA+ASA (C-F approaching F). GA+ASA+CAB = F (new three-authority FAIL without CA).

4. **TM-19 classification confirmed (ARB ruling applied):** C-F → F — NOT unconditional FAIL. Adversary action required (C-F condition). Once triggered: no constitutional recovery (F consequence). OQ-38A03-01 RESOLVED.

5. **TM-39 Independence Illusion (SC2):** New threat added to catalog. F at Adversary D (design-level FAIL — operational concentration permitted by architecture despite constitutional independence designations). C-F at Adversary A (adversary action required). Potential Gap 6 raised to ARB (OQ-38A03-05).

6. **CAB legitimacy analysis (SC3):** Three components of adjudicatory authority; architecture provides only jurisdictional conferral. CAB can lose legitimacy without capture (C-F for legitimacy erosion). No restoration mechanism in architecture (AW-03-10).

7. **TM-01/TM-09 deepened (SC4):** EC capture detection structurally impossible within architecture. Constitutional ratchet identified (AW-03-11): TM-01's C-F condition, if triggered undetected through one election cycle, may be constitutionally irreversible. TM-01 re-evaluation recommended for 38A-06 as C-F → permanent-F-consequence (same pattern as TM-19). OQ-38A03-02 deferred to 38A-06.

8. **Concentration Ranking produced (SC5):** AC-31 > CAB > CA explicitly justified across five blast radius criteria (elections affected, recoverability, detectability, challengeability, retroactive impact).

9. **Twelve new architectural weaknesses documented** (AW-03-01 through AW-03-12; AW-03-09 through AW-03-12 added by strategic corrections).

10. **AW-03-01 re-framed (SC5 consequence):** No meta-adjudication body remains Critical. In full concentration landscape, Gap 5 (AC-31 governance absence) carries equal or larger blast radius. Both are Critical; neither subsumes the other.

### ARB Authorizations Applied

- OQ-38A03-01: RESOLVED — TM-19 remains C-F → F per ARB ruling
- SC1: Applied — D.1d expanded coalition families + GA+ASA+CAB added to D.2 as new FAIL finding
- SC2: Applied — TM-39 Independence Illusion added to catalog; OQ-38A03-05 raised for Gap 6 ruling
- SC3: Applied — CAB Legitimacy Analysis added as C.10
- SC4: Applied — OBS-38A03-06 (constitutional ratchet) and OBS-38A03-07 (TM-39 design-level FAIL) added to G.1
- SC5: Applied — E.4 Concentration Ranking added; E.1 severity ranking annotated
- Round 38A-04 AUTHORIZED

---

**Authors:**
- Senior Election Security Architect
- Constitutional Governance Architect
- Threat Modeling Specialist (adversarial)
- DDD Architect

**Round 38A Program Status:**
*38A-01 APPROVED WITH STRATEGIC CORRECTIONS*
*38A-02 APPROVED WITH TARGETED CORRECTIONS APPLIED*
*38A-03 APPROVED WITH STRATEGIC CORRECTIONS APPLIED (2026-06-17)*
*38A-04 AUTHORIZED — Timing, Phase, and Operational Attack Threats*
*38A-05 through 38A-06 — Awaiting Authorization*
*Next Phase (after 38A-06): Round 38B — Technical Architecture — AUTHORIZATION NOT YET GRANTED*
