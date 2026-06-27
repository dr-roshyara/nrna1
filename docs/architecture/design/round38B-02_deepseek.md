## Round 38B-02 — AC-31 Reference Standard Governance Specification

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38B-02 — Governance Specification
**Status:** IN PROGRESS
**Gap Addressed:** Gap 5 — AC-31 Reference Standard Governance
**Governing Question:** How is AC-31 governed, challenged, replaced, validated, and succeeded?

**Predecessors:**
- 38B-01 — Constitutional Interpretation Authority (Gap 4) — APPROVED
- 38A — Threat Model Validation — COMPLETE
- ADR-3 — Evidence & Verifier Architecture — APPROVED
- ADR-6 — Certification Architecture — APPROVED

**Binding Inputs:**
- Gap 5 is the highest-risk unresolved governance gap (38A-06 ranking)
- TM-19 (AC-31 Capture): C-F → F — ungoverned concentration point
- TM-47 (Chain Self-Reference): C-F → F — compromised AC-31 validates itself
- TM-46 (Succession Failure): C-F → F — no successor, single-point failure
- TM-56 (Retroactive Invalidation): F — post-TS-1 discovery has no remedy
- OBS-38A06-01: Root-layer objects lacking governance create self-referential validation chains
- OBS-38A06-SD1: The architecture specified end states without specifying governance for those end states
- AC-31 currently has: no governing body, no challenge mechanism, no succession, no detection, no minimum clarity standard

**Scope:** Constitutional governance specification only. No cryptographic design. No protocol design. No implementation. No technical architecture. No technology selection.

---

## Part A — Constitutional Purpose of AC-31

### A.1 What AC-31 Is

AC-31 is the constitutional constraint (ADR-3) requiring that audit-grade evidence authenticity verification use an independent reference standard. It is the Authenticity Root of the constitutional architecture — the foundation on which all evidence authenticity assessment rests.

**AC-31 provides the answer to: "Is this evidence authentic?"** Without AC-31, CO-3 (Evidence Authenticity) cannot be evaluated. Without CO-3, CO-5 (Election Validity) cannot be derived. Without CO-5, the election cannot be constitutionally certified.

### A.2 What AC-31 Is Not

AC-31 is not:
- A specific technology (not a hash function, digital signature scheme, or blockchain)
- A specific implementation (not a database, service, or API)
- A specific organization (not a company, agency, or department)
- A D43 authority aggregate (it is a constitutional mechanism, not an authority function)

AC-31 is the **constitutional requirement that an independent reference standard exist**. The reference standard itself is the operational artifact that satisfies AC-31. This specification governs the reference standard — its establishment, modification, challenge, succession, and legitimacy.

### A.3 Constitutional Function

AC-31 serves three constitutional functions:

1. **Authenticity Verification:** Provides the reference against which evidence authenticity is evaluated for CO-3
2. **Self-Authentication Prohibition:** Breaks the circularity where the election system authenticates its own evidence (AC-02 + AC-31 combined)
3. **Cross-Election Continuity:** Provides a stable authenticity foundation across election cycles, enabling historical audit and post-hoc verification

---

## Part B — Jurisdiction Scope

### B.1 What AC-31 Governs

The AC-31 reference standard governs authenticity verification for:
- VoteRecorded events (ballot evidence)
- Audit events (audit findings and observations)
- Enrollment records (voter eligibility evidence)
- Phase transition records (GovernanceState evidence)
- Any evidence category required by the Completeness Stratum (Tier 1/Tier 2)

### B.2 Temporal Scope

- **Prospective:** All evidence generated after AC-31 establishment
- **Retroactive:** Past evidence re-verified when AC-31 is updated or replaced
- **Cross-election:** AC-31 persists across election cycles; continuity of the reference standard is a constitutional requirement

### B.3 What AC-31 Does Not Govern

- Evidence completeness (CO-2 — governed by AuditScopeAuthority)
- Constitutional compliance (CO-4 — governed by ElectionConstitution)
- Phase validity (governed by GovernanceState)
- Certification decisions (governed by CertificationAuthority)

---

## Part C — Authority Source

### C.1 Constitutional Grounding

AC-31 derives its constitutional authority from:
- **ADR-3:** Establishes AC-31 as an architectural constraint requiring an independent reference standard
- **ElectionConstitution:** Must specify AC-31's establishment, governance, and mandate
- **Membership Assembly:** Ratifies the ElectionConstitution provision establishing AC-31

### C.2 Legitimacy Chain

```
Membership Assembly (source-of-source candidate — AA-01)
    ↓ ratifies
ElectionConstitution
    ↓ establishes
AC-31 Governance Body (to be specified in this round)
    ↓ governs
AC-31 Reference Standard (operational artifact)
    ↓ provides reference for
CO-3 (Evidence Authenticity)
    ↓ component of
CO-5 (Election Validity)
```

**OBS-38B01-SA1 applied:** AC-31's legitimacy is traceable through ElectionConstitution to the Membership Assembly. If AA-01 is unresolved, AC-31's ultimate legitimacy source is unresolved. This is documented, not hidden.

### C.3 The Governance Gap

Before this specification, the chain was:

```
AC-31 Reference Standard
    ↓ provides reference for
CO-3 (Evidence Authenticity)
```

With no governance layer. The reference standard existed as a constitutional assertion — required to exist, but with no specification of who establishes it, governs it, challenges it, or replaces it. This specification inserts the governance layer.

---

## Part D — Governance Model

### D.1 Specification Question

Who governs AC-31? What body holds authority over the reference standard's establishment, modification, validation, and protection?

### D.2 Options

**Option A — ElectionConstitution Direct Governance:** AC-31 is governed directly by ElectionConstitution provisions. The constitution specifies the reference standard's properties, establishment process, and modification procedure. No separate governance body.

**Option B — Designated Governance Body:** A constitutionally designated body holds governance authority over AC-31. This body establishes, modifies, validates, and protects the reference standard under constitutional mandate.

**Option C — Distributed Governance:** Multiple independent bodies share governance authority over AC-31. No single body can unilaterally modify or compromise the reference standard.

**Option D — CertificationAuthority as AC-31 Governor:** CA governs AC-31 as part of its certification mandate. This would concentrate authenticity governance in the certification function.

### D.3 Evaluation

**Option A (EC Direct):** Would embed operational reference standard specifications in the constitution — making routine updates constitutional amendments. This violates the distinction between constitutional specification and operational implementation. Rejected.

**Option B (Designated Body):** Creates a single governance body with clear accountability. Risk: the governance body becomes a new concentration point. Mitigation: challengeability, succession, and revocation specifications.

**Option C (Distributed):** Strongest protection against single-point capture (TM-19). Multiple bodies must concur to modify the reference standard. Risk: coordination complexity, potential deadlock (TM-42 pattern), unclear accountability.

**Option D (CA as Governor):** Would concentrate authenticity governance and certification evaluation in the same entity — enabling TM-52 (Certification Self-Validation) at the governance level. CA would govern the standard it evaluates compliance with. Rejected.

### D.4 Selection — Option B with Option C Properties

**Selected: AC-31 Governance Body (A31GB) — a constitutionally designated body with distributed internal governance.**

**Structure:**
- A31GB is a single constitutional body with authority over AC-31
- Internal governance requires concurrence of multiple members (distributed property)
- No single individual can unilaterally modify the reference standard
- Modifications require documented justification and constitutional compliance certification

**Constitutional mandate:**
- Establish the initial AC-31 reference standard
- Maintain and protect the reference standard's integrity
- Modify the reference standard when constitutionally justified
- Certify the reference standard's validity for each election cycle
- Publish the reference standard for access by CertificationAuthority, CAB, and challengers
- Respond to CIC interpretive questions about AC-31's constitutional compliance

**Independence:**
- Option B (Committee) with constitutional safeguards
- Members appointed through a process independent of any authority whose evidence AC-31 authenticates
- Cannot include members from CertificationAuthority, AuditExecutionAuthority, or election administration
- If constitutional safeguards cannot be structurally guaranteed, Option C (External Organization)

**L-1/L-5 dimensions:**

| Dimension | Specification |
|-----------|---------------|
| **L-1 (Source)** | ElectionConstitution provision establishing A31GB |
| **L-2 (Holder)** | Constitutionally appointed governance committee |
| **L-3 (Challenge)** | A31GB decisions challengeable through CAB; CIC may interpret whether A31GB actions are constitutionally compliant |
| **L-4 (Revocation)** | Through constitutional governance process; not through any body whose evidence AC-31 authenticates |
| **L-5 (Succession)** | Pre-designated successors per ADR7-INV-01; minimum three successors |

---

## Part E — Challengeability Model

### E.1 The Core Problem

Before this specification, AC-31 had no challenge mechanism (Gap 5). Named Attestation challenges CO-3 compliance WITH AC-31 — not AC-31's own validity. A compromised AC-31 could not be challenged because no challenge pathway existed.

### E.2 Specification

**Who may challenge AC-31?**
- CertificationAuthority (S-3 standing — authority peer)
- AuditExecutionAuthority (S-3 standing)
- Constitutional observers (S-2 standing)
- Affected candidates (S-1 standing — if election outcome affected)

**Grounds for challenge:**
- AC-31 reference standard has been compromised (TM-19)
- AC-31 reference standard is constitutionally non-compliant
- A31GB has failed in its governance duties
- AC-31 modification was procedurally invalid
- AC-31 reference standard is ambiguous (TM-45)

**Challenge pathway:**
1. Challenge filed with CAB through Named Attestation or direct constitutional challenge
2. CAB evaluates whether challenge has standing and prima facie validity
3. If challenge concerns constitutional interpretation of AC-31 requirements, CAB refers to CIC
4. CIC interprets whether AC-31 or A31GB action is constitutionally compliant
5. CAB adjudicates based on CIC interpretation
6. Appeal to Membership Assembly

**Challenge effect:**
- Pending challenge does NOT automatically suspend AC-31 (prevents denial-of-authenticity attacks)
- If challenge succeeds and AC-31 is found compromised, A31GB must execute succession (Part F)
- CO-3 evaluations performed under compromised AC-31 are retroactively suspect (TM-56 interface — OQ-38A05-02)

### E.3 TM-19 and TM-47 Mitigation

**TM-19 (AC-31 Capture):** A31GB is the governance body. Capture requires compromising A31GB's distributed internal governance — multiple members must be compromised. Challenge pathway enables detection: compromised A31GB decisions can be challenged through CAB.

**TM-47 (Chain Self-Reference Exploitation):** Before this specification, compromised AC-31 authenticated itself — CO-3 used AC-31 as reference, and challenges to CO-3 also used AC-31. Now: challenges to AC-31 itself go through CAB with CIC interpretation. The challenge pathway uses constitutional interpretation (CIC), not the compromised reference standard (AC-31), to evaluate whether AC-31 is valid. **The self-reference is broken.**

---

## Part F — Succession Model

### F.1 The Core Problem

Before this specification, AC-31 had no succession mechanism. If the reference standard became unavailable (TM-46 — organizational dissolution, technical failure, compromise), no successor existed. CO-3 would be impossible. CO-5 could not be derived. The election cycle would be permanently deadlocked.

### F.2 Specification

**Succession triggers:**
- A31GB determines AC-31 is compromised and cannot be restored
- CIC rules that AC-31 is constitutionally non-compliant and must be replaced
- AC-31 becomes technically or operationally unavailable (TM-46)
- Scheduled succession (cryptographic depreciation, standard evolution)

**Succession process:**
1. Succession trigger activated
2. A31GB designates successor reference standard
3. CIC certifies successor is constitutionally compliant
4. Successor reference standard published
5. Transition period: both current and successor standards operational
6. Current standard retired; successor becomes authoritative
7. Past evidence re-verifiable against successor standard

**Succession safeguards:**
- Successor must be constitutionally compliant (CIC certification)
- Successor must be published before current standard is retired
- Transition period enables CO-3 continuity
- Past evidence authenticity must survive succession — re-verification capability required
- Pre-designated successor standards (where technically feasible)

**ADR7-INV-01 application:** A31GB must pre-designate successor reference standards. Minimum three candidate successors. Successor list reviewed and updated each election cycle.

### F.3 TM-46 Mitigation

TM-46 (Succession Failure) is mitigated: succession triggers, process, and safeguards are specified. A31GB is accountable for succession readiness. If A31GB fails to maintain succession readiness, this is challengeable through CAB.

---

## Part G — Revocation Model

### G.1 Specification

**Revocation triggers:**
- AC-31 found constitutionally non-compliant by CIC and cannot be remedied through modification
- A31GB found systematically compromised and cannot be restored through succession
- Membership Assembly votes to revoke AC-31 governance mandate

**Revocation process:**
1. Revocation trigger activated
2. CIC certifies revocation is constitutionally justified
3. A31GB mandate terminated
4. Succession activated — successor A31GB assumes governance
5. New A31GB establishes replacement reference standard per succession process

**Revocation safeguards:**
- Revocation requires CIC constitutional compliance certification
- Revocation automatically triggers succession — no governance gap
- Revocation cannot be executed by any body whose evidence AC-31 authenticates

---

## Part H — Independence Requirements

### H.1 Specification

**AC-31 Independence Discipline (38B-01 R3 applied):**

Independence is a constitutional requirement. The realization form is not specified as "external organization" — it is specified as structural independence from the functions AC-31 serves.

**A31GB must be structurally independent of:**
- CertificationAuthority (whose CO-3 evaluation depends on AC-31)
- AuditExecutionAuthority (whose audit findings are authenticated by AC-31)
- Election administration (whose evidence is authenticated by AC-31)
- Any D43 authority whose decisions depend on evidence authenticity

**Independence verification:**
- CIC may be asked to interpret whether A31GB's structure satisfies constitutional independence requirements
- A31GB's independence can be challenged through CAB
- Independence is assessed at establishment and at each election cycle

**Realization neutrality:**
- Committee form (Option B) is sufficient if constitutional safeguards are met
- External organization (Option C) is not constitutionally required
- The constitutional requirement is independence, not a specific organizational form

---

## Part I — Relationship to Certification Authority

### I.1 Specification

**CA's dependence on AC-31:** CA must independently access AC-31 for CO-3 evaluation (ADR-3 prohibition). CA cannot rely on A31GB's assertion that AC-31 is valid — CA must verify.

**A31GB's obligations to CA:**
- Publish AC-31 reference standard before each election cycle
- Certify AC-31's validity for the election cycle
- Notify CA immediately if AC-31 integrity is questioned during the certification window
- Provide CA with access to AC-31's establishment and modification history

**CA's obligations regarding AC-31:**
- Independently access AC-31 for CO-3 evaluation (not rely on A31GB's certification)
- Challenge AC-31 through CAB if CA has grounds to believe AC-31 is compromised
- Include AC-31 version and validity status in CO-3 attestation
- Suspend CO-3 evaluation if AC-31 validity is in question and not yet resolved

**Dispute resolution:** If CA and A31GB disagree about AC-31's validity, CIC interprets constitutional requirements, CAB adjudicates the dispute.

---

## Part J — Relationship to Constitutional Interpretation Body

### J.1 Specification

**CIC's role regarding AC-31:**
- Interpret whether AC-31 reference standard satisfies constitutional requirements
- Interpret whether A31GB actions are constitutionally compliant
- Resolve constitutional ambiguity about AC-31's scope, independence, or mandate
- Certify successor reference standards as constitutionally compliant

**A31GB's obligations to CIC:**
- Refer constitutional questions about AC-31 to CIC
- Comply with CIC interpretations of AC-31 constitutional requirements
- Request CIC certification for significant AC-31 modifications

**CIC's limitations:**
- CIC interprets constitutional requirements for AC-31; it does not govern AC-31 operationally
- CIC does not select the reference standard technology or implementation
- CIC's interpretations are binding on A31GB

---

## Part K — Relationship to GovernanceState

### K.1 Specification

**Temporal coordination:** AC-31 modifications must be coordinated with election phases recorded in GovernanceState. A reference standard modification during an active CO-3 evaluation window would create authenticity ambiguity.

**Coordination requirements:**
- AC-31 modifications take effect at phase boundaries, not during active evaluation windows
- A31GB must publish modification schedule aligned with election cycle phases
- GovernanceState must record which AC-31 version is authoritative for each phase
- CO-3 evaluations reference the AC-31 version recorded in GovernanceState for that phase

**Dispute resolution:** If AC-31 version ambiguity arises (which version was authoritative for which phase), GovernanceState is the temporal record. CIC interprets if the temporal record is constitutionally sufficient.

---

## Part L — Alternative Models Evaluated

| Model | Advantages | Disadvantages | Verdict |
|-------|------------|---------------|---------|
| **A — EC Direct Governance** | No new body; constitutional clarity | Operational details in constitution; amendment required for updates | Rejected — confuses constitutional and operational |
| **B — Single Designated Body (selected)** | Clear accountability; challengeable; successible | New concentration point (mitigated) | **Selected** with distributed internal governance |
| **C — Distributed Governance** | Strongest against single-point capture | Coordination complexity; deadlock risk; unclear accountability | Partially adopted — internal distribution within A31GB |
| **D — CA as Governor** | No new body; leverages existing authority | Self-referential (CA governs standard it evaluates); enables TM-52 | Rejected — violates OBS-38A06-01 |

---

## Part M — Selected Governance Model

### M.1 Summary Specification

**AC-31 Governance Body (A31GB):**
- Constitutionally designated committee with distributed internal governance
- Establishes, maintains, modifies, and protects the AC-31 reference standard
- Challengeable through CAB with CIC interpretation
- Succession specified (pre-designated successors, transition process)
- Revocation specified (CIC-certified, triggers automatic succession)
- Structurally independent of all authorities whose evidence AC-31 authenticates

### M.2 Threat Mitigation Summary

| Threat | Pre-Specification Status | Post-Specification Status |
|--------|-------------------------|--------------------------|
| **TM-19 (AC-31 Capture)** | C-F → F — ungoverned; no challenge | C-F — governed; challengeable through CAB; distributed internal governance |
| **TM-47 (Chain Self-Reference)** | C-F → F — compromised AC-31 authenticates itself | Self-reference broken — challenges use CIC interpretation, not AC-31 |
| **TM-46 (Succession Failure)** | C-F → F — no successor | Succession specified — triggers, process, safeguards |
| **TM-56 (Retroactive Invalidation)** | F — no post-TS-1 remedy | Partial — succession enables re-verification; OQ-38A05-02 remains |
| **TM-45 (Ambiguity)** | C-F — no interpretation | CIC interprets AC-31 constitutional requirements |

### M.3 OBS-38A06-01 Review

**Question:** Does this governance model create a new self-referential validation chain?

**Analysis:** A31GB governs AC-31. A31GB is challengeable through CAB. Challenges to A31GB are adjudicated using CIC interpretation of constitutional requirements — not using AC-31 itself. The chain is:

```
A31GB action challenged
    → CAB adjudicates
    → CIC interprets constitutional requirements
    → CIC interpretation is the reference, not AC-31
```

**The self-reference is broken.** AC-31 does not authenticate challenges to its own governance. CIC interpretation provides an external reference point.

### M.4 Concentration Chain Review

**New concentration point:** A31GB is a new governance body. It concentrates AC-31 governance authority.

**Mitigation:**
- Distributed internal governance prevents single-person capture
- Challenge pathway enables external accountability
- Succession prevents single-point failure
- Revocation enables removal of compromised governance
- CIC oversight provides constitutional compliance verification

**Residual risk:** A31GB is a new authority aggregate. It adds governance burden. OBS-38B01-AI1 applies — more authorities ≠ better governance. The justification is that Gap 5's severity (highest-ranked gap, multiple FAIL findings) warrants a dedicated governance body.

---

## Part N — Open Questions

**OQ-38B02-01:** Should the AC-31 reference standard itself be distributed (multiple independent reference holders) rather than governed by a single body? Distributed AC-31 would further reduce TM-19 risk but introduce TM-48 (Reference Disagreement) risk. This is a technical architecture question for 38C.

**OQ-38B02-02:** How does A31GB's establishment affect the ElectionConstitution concentration analysis (ADR-7, 38A-06)? A31GB is a new body whose mandate derives from ElectionConstitution — adding to the EC's concentration as L-1 source.

**OQ-38B02-03:** Does A31GB require D43 classification as a new constitutional authority function? A31GB governs a constitutional mechanism (AC-31), not an operational function (enrollment, audit, certification). Is this a new D43 instance or a new category of constitutional body?

**OQ-38B02-04:** The succession specification requires past evidence to be re-verifiable against successor standards. Is this technically achievable for all evidence types, or does it impose constraints on AC-31 implementation? This is a technical architecture question for 38C.

**OQ-38B02-05:** OQ-38A05-02 (finality vs. validity) remains unresolved. A31GB succession and challengeability improve AC-31 governance, but do not resolve the underlying constitutional contradiction between TS-1 finality and ADR6-INV-01 validity when AC-31 is later proven to have been compromised.

---

## Section — ARB Decision Block

**[SUBMITTED FOR ARB REVIEW]**

### Decision

**AC-31 Governance Body (A31GB) established — Option B with distributed internal governance.** AC-31 now has governance, challengeability, succession, and revocation specifications.

### Gap 5 Status

**Gap 5 is CLOSED by this specification.** AC-31 governance is now constitutionally specified. A31GB is the governing body. Challenge, succession, and revocation pathways exist. Self-reference is broken.

### Deliverables

1. AC-31 constitutional purpose specification
2. A31GB governance model (structure, mandate, L-1/L-5)
3. Challengeability model (breaks TM-47 self-reference)
4. Succession model (mitigates TM-46)
5. Revocation model
6. Independence requirements
7. Relationship specifications (CA, CIC, GovernanceState)
8. Alternative models evaluated
9. OBS-38A06-01 and concentration chain reviews
10. Open questions

### Authorization Requested

**38B-03: Gap 7 Specification — Temporal Root Governance**

---

*Round 38B-02 — Gap 5 AC-31 Governance Specification — COMPLETE*
*Research Program: NRNA DDD Trustworthiness*

review: 
Let me apply the five required revisions.

---

## Revisions Applied — 38B-02

### R1 — OBS-38B02-01 Added (Concentration Transformation)

Inserted after the Governance Model section:

**OBS-38B02-01: A31GB transforms the AC-31 concentration problem rather than eliminating it.** Before 38B-02, AC-31 was the concentration point — an ungoverned reference standard whose compromise affected all elections retroactively and prospectively. After 38B-02, A31GB governs AC-31 — concentrating governance authority over the reference standard in a single body. The concentration has moved upward from the reference standard to its governance. Whether this transformation sufficiently reduces constitutional risk, or merely relocates it, requires evaluation in future rounds. A31GB is challengeable, successible, and revocable — properties AC-31 alone lacked — but A31GB itself is now a concentration point for authenticity governance.

---

### R2 — Gap 5 Status Changed (CLOSED → PROVISIONALLY RESOLVED)

**Gap 5 is PROVISIONALLY RESOLVED by this specification.** The specification addresses the four original Gap 5 absences: governance (A31GB established), challengeability (CAB + CIC pathway specified), succession (triggers, process, safeguards specified), and legitimacy chain (EC → MA traceable, AA-01 documented). However, resolution is provisional subject to:

- **OQ-38A05-02 (Finality vs. Validity):** The underlying constitutional contradiction remains. When AC-31 is later proven to have been compromised after TS-1, the architecture still lacks a resolution mechanism for whether finality or validity governs. A31GB improves AC-31's present governance but does not resolve retroactive disputes.

- **AA-01 (Membership Assembly Legitimacy):** A31GB's legitimacy terminates in the Membership Assembly through ElectionConstitution. If AA-01 is unresolved, A31GB's ultimate legitimacy source is unresolved.

- **Round 38B review:** Subsequent governance specifications (Gap 7, Gap 6, Gap 3) may reveal dependencies on Gap 5 that require reopening.

---

### R3 — TM-47 Language Changed ("broken" → "materially reduced")

**TM-47 self-reference is materially reduced by this specification.** Before 38B-02, compromised AC-31 authenticated itself — CO-3 used AC-31 as reference, and challenges to CO-3 also used AC-31. The self-reference was complete.

After 38B-02, challenges to AC-31 route through CAB with CIC interpretation. CIC interprets constitutional requirements for AC-31 — not using AC-31 itself, but using ElectionConstitution as the interpretive reference. This breaks the direct self-reference: AC-31 no longer validates challenges to its own validity.

**However, TM-47 is not eliminated.** CIC, CAB, and A31GB all derive constitutional legitimacy through ElectionConstitution. The constitutional chain — MA → EC → A31GB → AC-31 — remains a single legitimacy lineage. Whether self-reference persists at the constitutional level (EC governing the bodies that interpret EC) remains a question for future evaluation. The direct operational self-reference (AC-31 authenticating itself) is broken. The indirect constitutional self-reference (EC legitimizing the bodies that govern EC's artifacts) is a deeper question that Gap 3 (Amendment Governance) must address.

---

### R4 — OQ-38B02-03 Elevated to Primary Open Question

**OQ-38B02-03: PRIMARY OPEN QUESTION — Does A31GB constitute a new D43 authority function?**

A31GB exercises functions characteristic of constitutional authorities: it establishes, modifies, maintains, certifies, and protects the AC-31 reference standard. It has L-1/L-5 dimensions. It is subject to challenge, succession, and revocation. These are authority functions.

The question is whether A31GB is a new D43 instance (a new constitutional authority function alongside Enrollment, Criteria, Audit, Governance, Certification, and Challenge Adjudication) or a new category of constitutional body — a governance body for a constitutional mechanism rather than an operational authority function.

**The distinction matters for the authority inventory.** ADR-7 identified 6-7 D43 authority aggregates. If A31GB is a D43 instance, the inventory grows. If A31GB is a new category, the D43 taxonomy may need to recognize "governance bodies" as distinct from "operational authorities."

**This question should be resolved before 38B concludes**, as subsequent governance specifications (Gap 7, Gap 6) may create additional governance bodies with the same classification ambiguity.

---

### R5 — OBS-38B02-02 Added (Gap 4 → Gap 5 Dependency)

Inserted in the Relationship to CIC section:

**OBS-38B02-02: Gap 5 governance depends on Gap 4 resolution.** The A31GB governance model relies on CIC for multiple constitutional functions: certifying successor reference standards as constitutionally compliant, interpreting whether A31GB actions satisfy constitutional requirements, resolving constitutional ambiguity about AC-31's scope, and certifying revocation as constitutionally justified.

This creates a dependency chain: Gap 5 (AC-31 governance) → Gap 4 (CIC). If CIC is not operational, A31GB's succession, revocation, and constitutional compliance verification pathways are impaired. The governance of the Authenticity Root depends on the governance of constitutional interpretation.

**Future analysis must evaluate** whether this Gap 4 → Gap 5 dependency creates a new concentration chain. If CIC is the sole body capable of certifying AC-31 constitutional compliance, CIC becomes a chokepoint for authenticity governance — a single body whose unavailability or compromise affects AC-31 governance across all elections.

---

## Revision Summary

| Revision | Change | Status |
|----------|--------|--------|
| **R1** | OBS-38B02-01: Concentration transformation observation added | Applied |
| **R2** | Gap 5: CLOSED → PROVISIONALLY RESOLVED (subject to OQ-38A05-02, AA-01, 38B review) | Applied |
| **R3** | TM-47: "broken" → "materially reduced" (direct self-reference broken; constitutional self-reference remains) | Applied |
| **R4** | OQ-38B02-03 elevated to PRIMARY OPEN QUESTION (affects D43 authority inventory) | Applied |
| **R5** | OBS-38B02-02: Gap 4 → Gap 5 dependency chain observation added | Applied |

---

**All five revisions applied. 38B-02 now accurately characterizes what the governance specification achieves — and what it does not. Shall I proceed with 38B-03: Gap 7 — Temporal Root Governance Specification?**