# Round 38B-02 — Gap 5: AC-31 Governance Specification

**Status:** APPROVED WITH MINOR OBSERVATIONS APPLIED (ARB Review 2026-06-17; R1/R2/R3 applied)
**Round:** 38B-02 (Second specification under Round 38B)
**Gap Target:** Gap 5 — AC-31 Governance (Authenticity Root)
**Predecessor:** Round38B-01_Constitutional_Interpretation_Authority.md (APPROVED WITH REVISIONS APPLIED, 2026-06-17)
**Authorization Basis:** Round38B-Authorization-Decision.md Sections 4.2 and 9; 38B-01 ARB approval
**Date:** 2026-06-17
**Discipline:** Constitutional Governance Specification only. No DDD design. No bounded contexts. No aggregates. No services. No APIs. No cryptographic mechanisms. No technology selection. No implementation choices.
**Provisional Note (per SC-C):** All references to AuditScopeAuthority and AuditExecutionAuthority in this document are provisional. ADR-4 remains SUBMITTED FOR ARB REVIEW. When ADR-4 is finalized, this specification must be reviewed for compatibility.

---

## Part A — Program State Consistency Check

Per Round38B-Authorization-Decision.md Section 9.1 and the Program State Reconciliation Rule.

| Check | Finding | Implication |
|---|---|---|
| ADR-4 status | SUBMITTED FOR ARB REVIEW | Gap 5 outputs marked provisional; AuditScopeAuthority/AuditExecutionAuthority references provisional throughout |
| Gap 5 | Confirmed — AC-31 Governance | THIS DOCUMENT targets Gap 5 |
| 38B-01 | APPROVED WITH REVISIONS APPLIED | CIC established; 38B01-INV-01 binding (CAB may not interpret; CIC interprets; CIC does not operationally govern) |
| OQ-38A05-02 | Formally unresolved; protected | This document must not resolve it; explicitly flagged where it intersects |
| OQ-38A05-03 | SUBSUMED by Gap 5 (ARB-Review Section 3.3) | Singleton enforcement is a required deliverable of this specification |
| OA-01 (Challenger Evidence Access) | Highest-priority assumption for 38B; must be integrated with Gap 5 | AC-31 governance must address challenger access to AC-31 records |
| OBS-38B01-AI1 | Each new aggregate increases governance burden | This specification must justify any new authority aggregate or avoid it |

**Consistency check result:** All items verified. ADR-4 provisional risk scope confirmed. OQ-38A05-02 protection active. OA-01 integration required. 38B-02 may proceed.

---

## Part B — Constitutional Purpose (Deliverable A)

### B.1 What AC-31 Is

AC-31 is not a technology, system, or database. AC-31 is a **constitutional role**: the designation of an independent reference standard for election evidence authenticity, required by ADR-3 as a precondition for audit-grade evidence evaluation.

ADR-3 established:
- Constitutional (audit-grade) evidence authenticity requires an independent reference standard
- No election evidence is constitutionally self-authenticating (AC-02 + AC-31 combined)
- Self-Authentication is Prohibited: the reference standard must be independent of the evidence it authenticates

AC-31 is the Authenticity Root in the Three Trust Roots framework established in Round 38A-05:
- Legitimacy Root = ElectionConstitution
- Authenticity Root = **AC-31**
- Temporal Root = GovernanceState

CO-5 (Election Validity) is the only constitutional act requiring all three trust roots simultaneously. AC-31 compromise therefore produces CO-5 constitutional failure.

### B.2 What Gap 5 Names

Gap 5 names the absence of constitutional governance for the AC-31 role. ADR-3 established what AC-31 must be constitutionally. It did not establish:

- Who designates a specific reference standard as fulfilling the AC-31 constitutional role
- Who may modify or revoke the AC-31 designation
- How AC-31 integrity is verified over time
- Who may challenge AC-31 legitimacy or integrity
- How AC-31 is succeeded if compromised or unavailable
- How AC-31 singleton enforcement is constitutionally maintained (OQ-38A05-03, now subsumed)

This is the governance gap. The architectural requirement (independent reference standard) is specified. The constitutional governance of that requirement is absent.

### B.3 Why Gap 5 Has the Highest Failure Yield

From Round38A-06 Failure Production Ranking: Gap 5 is ranked #1 because AC-31 compromise produces:

| Consequence | Scope |
|---|---|
| CO-3 (Evidence Authenticity) failure | Every election using the compromised AC-31 |
| CO-5 (Election Validity) void | Every election certified under compromised CO-3 |
| Retroactive cross-election impact | All past elections, not just the current one |
| No recoverability path | No constitutional mechanism currently exists to repair |
| AW-05-07 (Authenticity Ratchet) | Ratchet operates automatically; no deliberate act required once AC-31 is compromised |

The authenticity ratchet (AW-05-07) is more constitutionally dangerous than the EC ratchet (AW-03-11) because it requires no further action by any adversary — honest actors certifying elections in good faith perpetuate the compromise.

### B.4 Constitutional Purpose of AC-31 Governance

AC-31 governance exists to:
1. Designate which specific reference standard currently fulfills the AC-31 constitutional role
2. Specify how that designation is governed, challenged, and maintained
3. Specify how AC-31 is succeeded or replaced if compromised or unavailable
4. Prevent AC-31 from becoming an unaccountable constitutional concentration point
5. Enforce AC-31 singleton status constitutionally (OQ-38A05-03 subsumed requirement)
6. Integrate OA-01 (Challenger Evidence Access) so that AC-31 records are accessible to verification parties

---

## Part C — Jurisdiction Scope (Deliverable B)

### C.1 What AC-31 Governance Covers

AC-31 governance specifies the constitutional framework governing the Authenticity Root role. It does NOT govern the technical realization of the reference standard.

| Governed by this specification | Not governed by this specification |
|---|---|
| Who designates the AC-31 role holder | What technology implements the reference standard |
| Constitutional properties required for AC-31 qualification | How the reference standard cryptographically functions |
| How AC-31 designation is challenged | Protocol or algorithm selection |
| How AC-31 succession is triggered | Implementation architecture |
| Multi-party verification obligations | Service or component design |
| Singleton enforcement | Any bounded context or API |
| OA-01 challenger evidence access | Technical access mechanisms |

### C.2 Singleton Enforcement Scope (OQ-38A05-03 Subsumed)

The constitutional enforcement of AC-31 singleton status is within scope. At any time, only one reference standard may constitutionally fulfill the AC-31 role. The governance specification must make dual-designation constitutionally impossible and define what happens if a singleton challenge arises (TM-48 — Independent Reference Disagreement).

---

## Part D — Authority Source (Deliverable C)

### D.1 Constitutional Properties of AC-31 (EC Specification)

The ElectionConstitution must contain a provision specifying the constitutional properties that any reference standard must possess to qualify for the AC-31 designation. These properties are constitutional requirements, not technical specifications:

| Constitutional Property | Basis |
|---|---|
| Independence from all election evidence it authenticates | AC-02; ADR3-INV-01 (no self-authentication) |
| Independence from all authority aggregates whose mandates it serves | IR-A; TM-19 capture prevention |
| Verifiability of its own integrity by external parties | OA-01; TM-47 ratchet prevention |
| Pre-specified succession capability | TM-46 (permanent loss = unconditional F) |
| Single-instance constitutional status | OQ-38A05-03 (subsumed by Gap 5) |

The EC does not designate a specific technology as AC-31. The EC specifies what any AC-31-qualifying reference standard must constitutionally be. This follows the same pattern as ADR-2's independence form discipline: the constitutional requirement is specified; the realization remains open.

### D.2 Operational Designation (MA Authority)

**OBS-38B02-SA1 (Source-of-Authority Chain — required, following OBS-38B01-SA1 pattern):**

The specific reference standard that currently fulfills the AC-31 constitutional role is designated by the Membership Assembly. The chain:

```text
AC-31 operational designation
    ↑
MA designation decision (operational act under EC authority)
    ↑
ElectionConstitution (constitutional properties; L-1 source)
    ↑
MA ratification of EC (source-of-source candidate)
    ↑
AA-01 (unresolved — same terminal open question as 38B-01)
```

MA designation authority for AC-31 provides the highest available constitutional legitimacy. The MA may revoke and re-designate. Revocation and re-designation are constitutional acts that follow the process specified in Part H.

---

## Part E — Governance Model (Deliverable D)

### E.1 Governing Problem

Any body that governs AC-31 becomes the new AC-31 concentration point. This is the structural property the architect identified: designating governance for Gap 5 moves the concentration, it does not eliminate it. The governance model must therefore distribute AC-31 governance responsibility across multiple constitutional actors such that no single actor's compromise can capture AC-31 governance.

### E.2 Selected Governance Structure: Multi-Party Tiered Governance

AC-31 governance is distributed across three constitutional tiers, each with distinct responsibility:

```text
Tier 1 — Constitutional Properties (EC)
    The ElectionConstitution specifies what qualifies as AC-31.
    No operational actor governs this tier — it is constitutionally fixed
    until amended via Gap 3 process.

Tier 2 — Designation Authority (MA)
    The Membership Assembly designates, revokes, and re-designates
    the specific AC-31 reference standard.
    Maximum constitutional legitimacy; external to operational architecture.

Tier 3 — Ongoing Integrity Verification (Multi-Party Obligation)
    Multiple existing authority aggregates hold an ongoing constitutional
    obligation to verify AC-31 integrity as part of their own mandates.
    No single party may certify AC-31 integrity alone.
    Any party may trigger a challenge.
```

### E.3 Tier 3 Verification Parties and Obligations

The following existing authority aggregates hold AC-31 verification obligations:

| Verification Party | Constitutional Basis for Obligation | Verification Scope |
|---|---|---|
| AuditScopeAuthority (provisional — ADR-4) | Completeness Stratum Link 3; uses AC-31 as basis for Tier 2 evidence specifications | Verify AC-31 integrity before issuing Tier 2 evidence scope decisions |
| AuditExecutionAuthority (provisional — ADR-4) | CO-3 (Evidence Authenticity) evaluator; directly reads AC-31 for authenticity determinations | Verify AC-31 is constitutionally qualifying before each CO-3 evaluation |
| CertificationAuthority | CO-3 required for CO-5; prohibited from certifying Authenticity Stratum compliance without accessing AC-31 independently (ADR-3) | Verify AC-31 integrity before each CO-4 and CO-5 issuance |

**Multi-party verification requirement (binding):** No authority aggregate may certify AC-31 integrity solely on the basis of another aggregate's prior certification. Each verification party must independently verify. This is the direct structural protection against TM-47 (the authenticity ratchet — which propagates precisely when consumers accept prior certifications without independent verification).

**OA-01 Integration:** Each verification party listed above must have constitutional access to AC-31 integrity records as part of their verification mandate. Denial of access constitutes a constitutional trigger for AC-31 challenge (Part F). This is the required OA-01 integration: governance creates the structural obligation for AC-31 record accessibility.

### E.4 Governance Failure Threshold

If two or more Tier 3 verification parties independently flag an AC-31 integrity concern, the AC-31 challenge process (Part F) is constitutionally triggered without requiring any additional precondition. A single-party flag initiates a formal review process without immediately triggering the challenge process. This multi-party threshold prevents both false positives (single actor error) and false negatives (single actor capture).

---

## Part F — Challengeability Model (Deliverable E)

### F.1 Who May Challenge AC-31

The following actors have constitutional standing to challenge AC-31:

1. Any Tier 3 verification party (AuditScopeAuthority, AuditExecutionAuthority, CertificationAuthority) — upon detecting AC-31 integrity failure through their verification obligation
2. MembershipAssembly — upon receiving evidence that AC-31 no longer satisfies the EC constitutional properties
3. ChallengeAdjudicationBody — when an adjudication before it depends on an AC-31 integrity question
4. Constitutional Interpretation Chamber (CIC) — when requested to issue a constitutional interpretation ruling on whether AC-31 still qualifies under EC Tier 1 properties

### F.2 Challenge Routing

AC-31 challenges route as follows:

| Challenge Type | Route | Adjudicator |
|---|---|---|
| Does AC-31 satisfy EC constitutional properties? | Constitutional interpretation question → CIC | CIC issues ruling under 38B01-INV-01 (CIC interprets; CAB adjudicates) |
| Is this specific AC-31 designation valid under the constitutional properties? | MA review request | MA reviews and re-designates if necessary |
| Is AC-31 integrity currently intact? | Multi-party verification report | Two-party flag triggers MA emergency review |
| Singleton violation: two entities claim AC-31 status | Constitutional interpretation question → CIC | CIC determines which (if any) satisfies EC Tier 1 properties |

### F.3 Challenge Evidence Access (OA-01 Constitutional Right)

Any actor with standing to challenge AC-31 must have constitutional access to:
- The AC-31 designation record (what the MA designated and when)
- The EC Tier 1 constitutional properties specification (what AC-31 must be)
- The most recent Tier 3 verification records (what each verification party found)

This is not an operational access question — it is a constitutional governance requirement. Withholding these records from a challenging party with standing constitutes a constitutional violation, not a technical policy decision. The EC designation provision for AC-31 must specify access rights explicitly.

### F.4 OQ-38A05-02 Intersection

When an AC-31 challenge succeeds — meaning AC-31 is found constitutionally compromised after elections have been certified — OQ-38A05-02 (Finality vs Validity) is directly triggered:

- Under ADR-6 TS-1 (finality principle): CO-5 for prior elections is constitutionally final
- Under ADR6-INV-01 (validity requirement): CO-5 is void if CO-3 was not independently satisfied

This document does not and may not resolve OQ-38A05-02. The intersection must be flagged and the resolution deferred to the CIC ruling on OQ-38A05-02 (Part I of 38B-01). Any AC-31 challenge that results in AC-31 compromise being confirmed must include a formal referral to CIC for OQ-38A05-02 ruling.

---

## Part G — Succession Model (Deliverable F)

### G.1 Why AC-31 Succession is Existential

OQ-38A05-01 was ruled in Round 38A-05: permanent AC-31 loss = unconditional FAIL. This means AC-31 succession is not a governance preference — it is a constitutional survival requirement. If AC-31 is permanently unavailable and no succession exists, the constitutional architecture has no Authenticity Root and cannot produce constitutionally valid CO-3, CO-5, or election validity.

### G.2 Succession Requirements

Following the TM-46 threat analysis and ADR7-INV-01 pattern:

1. **Pre-designated succession in EC:** AC-31 succession candidates are named before the primary AC-31 is constitutionally active. Following the same discipline as CIC succession (38B-01 Part F).
2. **Succession chain, not single successor:** At least two succession levels to prevent TM-43 (Successor Exhaustion) applied to the Authenticity Root.
3. **Succession trigger specification:** Constitutionally specified triggers prevent ambiguity exploitation (TM-41 pattern applied to AC-31 succession):
   - Confirmed AC-31 compromise (multi-party verification threshold met + MA ruling)
   - Permanent operational unavailability of AC-31 (timeline to be specified in EC provision)
   - MA voluntary revocation (explicit constitutional decision)
4. **Succession independence:** The process of verifying successor qualification must be independent of the current (compromised or unavailable) AC-31. Successor qualification uses EC Tier 1 constitutional properties as the verification standard, applied by Tier 3 verification parties.

### G.3 Succession and the Authenticity Ratchet (TM-47/AW-05-07)

When AC-31 succession is triggered due to compromise, elections certified under the compromised AC-31 (before the compromise was detected) carry retroactive constitutional ambiguity. The succession event does not automatically resolve that ambiguity.

The succession model must therefore specify:
- At what point succession is constitutionally effective (prospective only, or also affecting prior certifications — this is OQ-38A05-02)
- Whether CO-5 issued under the compromised AC-31 is suspended pending CIC ruling on OQ-38A05-02
- Whether successor AC-31 re-certification of prior elections is constitutionally possible

None of these questions can be resolved by this specification without resolving OQ-38A05-02. Each must be flagged as OQ-38A05-02-dependent in the EC designation provision.

---

## Part H — Revocation Model (Deliverable G)

### H.1 Revocation vs. Succession

Revocation is the constitutional act of terminating the current AC-31 designation. Succession is the constitutional act of designating a replacement. They are distinct:

| Revocation | Succession |
|---|---|
| Terminates current AC-31 | Designates replacement AC-31 |
| Performed by MA | Designates pre-specified candidate |
| May be immediate (emergency) or deliberate | Follows pre-designated succession chain |
| Does not guarantee a replacement exists | Replacement must satisfy EC Tier 1 properties |

### H.2 Revocation Triggers

MA may revoke AC-31 designation when:
1. Multi-party verification threshold is met (two or more Tier 3 parties flag integrity failure)
2. CIC issues a constitutional interpretation ruling that the current AC-31 no longer satisfies EC Tier 1 properties
3. MA independently determines that the current AC-31 no longer satisfies the constitutional designation conditions

Revocation without succession triggers OQ-38A05-01 (permanent loss = unconditional FAIL). Therefore, revocation must be accompanied by succession unless the succession is itself impossible — in which case the architecture is in a constitutionally terminal state that requires emergency MA intervention under Gap 3 amendment authority.

### H.3 Emergency Revocation Procedure

If the standard succession chain fails (TM-43 scenario) and AC-31 must be revoked without a pre-designated successor available, the constitutional procedure must be specified. Two options evaluated:

| Option | Assessment |
|---|---|
| MA emergency designation (outside normal process) | Highest legitimacy but requires MA assembly; timing constraint risk during active election |
| Temporary AC-31 suspension (elections paused) | Preserves constitutional integrity; operationally disruptive; no election certified under absent AC-31 |

Both options must be specified in the EC designation provision. Temporary AC-31 suspension (election pause) may be the constitutionally correct default to avoid certifying elections under an absent Authenticity Root.

---

## Part I — Independence Requirements (Deliverable H)

### I.1 Required Independence Properties

Following the ADR-2 framework and the independence discipline note from 38B-01 Part G.2 (independence requirement ≠ specific realization):

**The constitutional requirement is authenticity independence — the independence of the AC-31 reference standard from the evidence it authenticates and the bodies that consume it.**

This does not imply a specific technology, external organization type, or system component. The realization belongs to technical architecture (38C or later).

Minimum independence requirements (binding regardless of realization):

| Requirement | Basis |
|---|---|
| AC-31 must not be operated or controlled by any entity whose evidence it authenticates | ADR3-INV-01 (no self-authentication) |
| AC-31 must not be operated or controlled by any of the Tier 3 verification parties | Independence from verifiers prevents self-validation chain |
| AC-31 must not be subject to modification by any single authority aggregate unilaterally | Multi-party governance (Part E) |
| AC-31 integrity records must be accessible to all parties with verification or challenge standing | OA-01 integration requirement |

### I.2 Independence Verification

"Designed distribution ≠ actual distribution" (AIC-36C-06-05). Independence must be verifiable:
- Tier 3 verification parties have an ongoing obligation to verify AC-31 independence as part of their integrity verification mandate
- MA receives periodic reports from Tier 3 parties on AC-31 independence status
- Any standing party may submit an AC-31 challenge based on observed independence failure

---

## Part J — Relationship to CertificationAuthority (Deliverable I)

### J.1 CO-3 Dependency

CertificationAuthority (CA) requires CO-3 (Evidence Authenticity) as a prerequisite for CO-5 (Election Validity). CO-3 is evaluated by AuditExecutionAuthority against the AC-31 reference standard. This creates a dependency chain:

```text
CertificationAuthority (issues CO-5)
    ↓ requires
CO-3 (Evidence Authenticity — evaluated against AC-31)
    ↓ requires
AC-31 (constitutionally valid, currently designated, integrity verified)
```

### J.2 CA's AC-31 Prohibition

ADR-3 established: "CertificationAuthority prohibition: may NOT certify Authenticity Stratum compliance without independently accessing the AC-31 reference standard (AC-02 + AC-31 combined)."

This prohibition interacts with Gap 5 governance: CA cannot certify AC-31 compliance through a third party. CA must access AC-31 directly. This is both an independence requirement (CA verifies independently) and an OA-01 integration point (CA must have constitutional access to AC-31 records).

### J.3 Concentration Warning

ADR-6 OBS-ADR6-02 established: "CertificationAuthority = constitutional concentration point; compromise invalidates CO-2/CO-3/CO-4/CO-5 simultaneously."

The AC-31 governance model must ensure that CA is a CONSUMER of AC-31 governance, not a GOVERNOR. If CA governed AC-31, CA's compromise would produce:
- CA directly manipulates AC-31 → CO-3 evaluations compromised
- CA certifies CO-3 against compromised AC-31 → CO-5 compromised
- All four CO objects simultaneously invalid

This is the double-failure scenario that Alternative 3 (CertificationAuthority Governs AC-31) in Part M was rejected for. The separation of CA (consumer) from AC-31 governance (distributed tiered model) must be maintained.

---

## Part K — Relationship to CIC (Deliverable J)

### K.1 CIC Role in AC-31 Governance

The Constitutional Interpretation Chamber (CIC, established in 38B-01) intersects with AC-31 governance in three specific ways:

| Intersection | CIC Role |
|---|---|
| Does the current AC-31 satisfy EC Tier 1 constitutional properties? | Constitutional interpretation question → CIC interprets |
| Singleton dispute: which of two claimed AC-31 designations is constitutionally valid? | CIC determines which (if any) satisfies EC Tier 1 properties |
| OQ-38A05-02 referral upon confirmed AC-31 compromise | CIC receives formal referral (38B-01 Part I procedure) |

### K.2 38B01-INV-01 Application

Per 38B01-INV-01: CIC interprets; CIC does not govern. The CIC's role in AC-31 governance is strictly interpretive:
- CIC determines whether something IS constitutionally AC-31
- CIC does NOT designate, manage, or succeed the AC-31 reference standard

When a challenge arises about whether the current AC-31 satisfies constitutional properties, the routing is:
1. Challenge raised by standing party
2. CIC issues interpretation ruling (does this reference standard satisfy EC Tier 1?)
3. Based on CIC ruling, MA takes designation action (maintain, revoke, or re-designate)

CIC interpretation → MA governance action. The two functions remain separated.

---

## Part L — Relationship to GovernanceState (Deliverable K)

### L.1 GovernanceState's Role

GovernanceState records constitutional authority state. It does not govern AC-31. However, GovernanceState records are relevant to AC-31 governance in two ways:

1. **Phase boundary dependency:** AC-31 integrity verification must occur within constitutionally specified phases. GovernanceState records phase boundaries. But TM-40 (GovernanceState self-certifying) means GovernanceState records cannot be the sole evidence of phase timing for AC-31 verification purposes.

2. **Phase transition and AC-31 succession coordination:** If AC-31 succession is triggered during an active election phase, GovernanceState phase records determine what elections are in-progress and affected. But the same TM-40 caution applies.

### L.2 GovernanceState Caution

AC-31 governance must not rely solely on GovernanceState records for:
- Determining which elections are covered by an AC-31 compromise
- Establishing the timeline of AC-31 integrity failure
- Triggering AC-31 succession based on GovernanceState-reported phase data alone

Each of these requires corroborating evidence independent of GovernanceState (Gap 7 — GovernanceState Record Governance is a separate specification target in 38B). Until Gap 7 is specified, AC-31 governance decisions depending on GovernanceState phase data must be treated as provisionally-corroborated, not constitutionally settled.

---

## Part M — Alternative Models Evaluated (Deliverable L)

### M.1 Alternative 1 — Singleton EC Designation (No Operational Governance Body)

**Description:** The EC designates a specific reference standard as AC-31 directly. Changing AC-31 requires an EC amendment via the Gap 3 process. No operational governance body is created.

**Assessment:**
- Maximum constitutional rigidity — AC-31 change requires the highest constitutional action
- No operational governance mechanism — TM-46 (succession) has no constitutional path except EC amendment, which is too slow for emergency AC-31 compromise
- TM-19 capture is structurally unaddressed — compromising AC-31 directly compromises the EC-designated entity without a constitutional response mechanism
- AW-05-07 (authenticity ratchet) has no brake — once AC-31 is compromised, no operational mechanism exists to stop the ratchet without EC amendment
- OQ-38A05-03 (singleton enforcement) addressed by EC designation — but enforcement requires a challenge mechanism that doesn't exist here

**OBS-38A06-01:** Not self-referential (no governance body). But TM-46 unconditional FAIL exposure remains unaddressed.

**Verdict: REJECTED.** Insufficient for a constitutional role of AC-31's threat profile. No operational governance mechanism leaves the Authenticity Root constitutionally unprotected against its highest-yield threat scenarios (TM-19, TM-46, AW-05-07).

---

### M.2 Alternative 2 — Dedicated AC-31 Governance Authority (New Aggregate)

**Description:** A new dedicated authority aggregate governs AC-31 exclusively: designates it, monitors integrity, adjudicates AC-31 challenges, manages succession.

**Assessment:**
- Creates a 9th authority aggregate (OBS-38B01-AI1 triggered — governance burden increase)
- Concentration moves from AC-31 to the governance authority — the governance body is the new concentration point
- Self-referential risk: if the governance authority validates the reference standard it governs, and the reference standard is used to validate the governance authority's rulings, self-referential loop is created
- OBS-38A06-01 Q3: does the governance authority adjudicate challenges to its own legitimacy? Would require careful design to prevent.
- TM-19 now targets the governance authority instead of AC-31 directly — capture risk shifts but does not decrease

**OBS-38A06-01:** HIGH RISK — governance body's self-referential validation structure is difficult to prevent without additional external reference.

**Verdict: REJECTED** as a standalone model. The governance burden (9th aggregate) is not justified when Alternative 4 (Multi-Party Tiered) achieves the same constitutional objective without a new aggregate. Remains revisitable if constitutional experience shows that distributed governance is insufficient.

---

### M.3 Alternative 3 — CertificationAuthority Governs AC-31

**Description:** CA (established in ADR-2 as External Organization) governs AC-31 as part of its certification mandate.

**Assessment:**
- CA already evaluates CO-3 — natural operational relationship
- ADR-6 OBS-ADR6-02: CA compromise already invalidates CO-2/CO-3/CO-4/CO-5 simultaneously
- If CA also governs AC-31: CA compromise eliminates BOTH certification AND the reference standard → catastrophic double-failure; single actor capture produces the largest possible constitutional blast radius
- AW-05-07 amplified: CA governs AC-31 → CA uses AC-31 to evaluate CO-3 → CA certifies CO-3 → CA issues CO-5. All four steps are under CA control. The authenticity ratchet now operates within a single authority aggregate.

**OBS-38A06-01:** FAILS Q1 — CA validates AC-31 integrity and uses AC-31 in its own certifications = self-referential validation chain.

**Verdict: REJECTED.** Categorically. Creates the maximum constitutional blast radius by combining CA concentration (ADR-6) with AC-31 concentration (Gap 5). This is architecturally prohibited by ADR-6 OBS-ADR6-02 read in conjunction with AC-02 + ADR3-INV-01.

---

### M.4 Alternative 4 — Multi-Party Tiered Governance (MA + Existing Aggregates + CIC)

**Description:** AC-31 governance distributed across three constitutional tiers: EC specifies constitutional properties (Tier 1); MA designates/revokes (Tier 2); existing authority aggregates hold multi-party verification obligations with any-party challenge trigger (Tier 3). CIC adjudicates constitutional interpretation questions about AC-31. No new authority aggregate created.

**Assessment:**
- No new authority aggregate (OBS-38B01-AI1 respected — no additional governance burden)
- Multi-party verification threshold prevents single-actor capture (TM-19 protection)
- MA designation provides maximum L-1 constitutional legitimacy
- CIC handles constitutional interpretation (38B01-INV-01 compliant)
- OA-01 integration is structural (Tier 3 parties have constitutional access obligation)
- Succession pre-specified in EC (TM-46 protection)
- AW-05-07 brake: multi-party verification obligation means ratchet is detectable and stoppable when Tier 3 parties independently flag compromise

**OBS-38A06-01 check:**
- Q1: Does the model validate its own records? → No single body holds AC-31 governance records and validates its own record. MA designates; EC specifies; Tier 3 verifies independently. Distribution breaks self-validation chain. MANAGEABLE.
- Q2: Does the model determine its own succession without external input? → EC pre-designates succession. No operational governance body self-determines succession. SAFE.
- Q3: Does the model adjudicate challenges to its own legitimacy? → CIC adjudicates constitutional questions; MA may revoke; no single operational body adjudicates its own legitimacy. MANAGEABLE.

Residual risk: a sufficiently large coalition compromising MA + two Tier 3 parties simultaneously could capture AC-31 governance. This is the GA+ASA+CAB coalition pattern (F-3 from 38A-03) applied to AC-31 governance. It is documented as a residual risk, not a design failure.

**Verdict: SELECTED.** See Part N.

---

### M.5 Alternative 5 — CIC Governs AC-31

**Description:** The Constitutional Interpretation Chamber governs AC-31 as part of its constitutional authority.

**Assessment:**
- Violates 38B01-INV-01: CIC interprets constitutional provisions; CIC does not operationally govern constitutional role holders
- CIC jurisdiction is interpretation only; AC-31 governance is operational management (designation, verification, succession, revocation)
- Conflating interpretation with operational governance creates the same self-referential concentration structure as Alternative J.1 in 38B-01 (unified CIC+CAB): the body that determines whether AC-31 satisfies constitutional properties would also manage AC-31 operationally

**Verdict: REJECTED.** Violates 38B01-INV-01 structurally. CIC is an interpreter; AC-31 requires an operational governance framework, not constitutional interpretation.

---

## Part N — Selected Governance Model (Deliverable M)

### N.1 Selection

**Alternative 4 — Multi-Party Tiered Governance** is selected.

### N.2 Complete AC-31 Governance Specification

**Constitutional Architecture:**

| Tier | Function | Constitutional Actor | Basis |
|---|---|---|---|
| Tier 1 | Constitutional properties specification (what qualifies as AC-31) | ElectionConstitution | Fixed until Gap 3 amendment |
| Tier 2 | Designation, revocation, re-designation | Membership Assembly | Maximum L-1 legitimacy; external to operational architecture |
| Tier 3 | Ongoing integrity verification; challenge trigger | AuditScopeAuthority (provisional), AuditExecutionAuthority (provisional), CertificationAuthority | Existing aggregates; multi-party independence |
| Interpretation | Constitutional qualification questions; singleton disputes; OQ-38A05-02 referral | Constitutional Interpretation Chamber | 38B01-INV-01 compliance |
| Adjudication | Challenges to AC-31-related authority decisions (not AC-31 constitutional questions) | ChallengeAdjudicationBody | ADR-5 jurisdiction |

**AC-31 Singleton Enforcement (OQ-38A05-03 subsumed):** At any constitutional moment, only the MA-designated reference standard holds AC-31 status. Dual-designation is constitutionally impossible — MA may only designate one AC-31 at a time. Singleton disputes route to CIC for constitutional interpretation of which (if any) claimant satisfies EC Tier 1 properties.

**OA-01 Integration:** Tier 3 verification parties hold constitutional access rights to AC-31 integrity records. Denial of access constitutes a constitutional violation triggering an immediate AC-31 challenge. Any standing challenging party also has constitutional access rights to the designation record, EC Tier 1 properties, and Tier 3 verification reports.

**Required EC Provision Elements for AC-31 Governance:**

1. EC Tier 1 constitutional properties (what qualifies as AC-31)
2. MA designation authority and procedure
3. MA revocation authority and procedure (including emergency revocation)
4. Tier 3 verification parties and their independence obligations
5. Multi-party challenge threshold (two-party flag for automatic challenge trigger)
6. CIC constitutional qualification jurisdiction
7. OA-01 access rights for verification and challenge parties
8. Succession chain (pre-designated; at least two levels)
9. Succession trigger conditions
10. OQ-38A05-02 intersection handling (explicit referral to CIC upon confirmed compromise)

---

## Part O — OBS-38A06-01 Self-Referential Validation Check

Per Round38B-Authorization-Decision.md Section 4.7 and 9.4, the following three questions are answered for the Multi-Party Tiered AC-31 Governance Model:

---

**Q1: Does the governance model validate its own records?**

Risk: If the governance model holds AC-31 records and validates those records using AC-31, it self-validates.

Analysis of selected model:
- EC holds Tier 1 constitutional properties (constitutional document, not self-validating)
- MA holds the designation record (MA designates; EC archive records this; MA does not validate the record it holds against AC-31)
- Tier 3 parties verify AC-31 integrity (they verify AC-31; they do not use AC-31 to verify themselves)
- CIC interprets constitutional qualification questions (CIC does not use AC-31 in its own constitutional interpretations)

**Verdict: No self-referential chain identified within the current model.** The governance model does not use AC-31 to validate its own records. The distribution across EC, MA, Tier 3, and CIC ensures no single body validates its own governance records using the reference standard it governs. Future threat analysis may identify chains not visible at this specification stage.

---

**Q2: Does the governance model determine its own succession without external input?**

Risk: If governance actors self-designate successors, the governance succession is self-determined.

Analysis: EC pre-designates AC-31 succession candidates (Tier 1 constitutional provision). MA executes succession decisions (Tier 2). No Tier 3 party self-designates its own AC-31 governance successor. EC amendment (Gap 3) governs changes to Tier 1 succession provisions.

**Verdict: No self-referential chain identified within the current model.** EC pre-specification breaks self-determination at every level. Future threat analysis may identify chains not visible at this specification stage.

---

**Q3: Does the governance model adjudicate challenges to its own legitimacy?**

Risk: If any governance tier can rule on the legitimacy of its own role, it adjudicates its own legitimacy.

Analysis:
- EC Tier 1 legitimacy: challenged via EC amendment (Gap 3 process) — not self-adjudicated
- MA designation authority: MA legitimacy challenged via AA-01/OBS-ADR7-SS1 path — not self-adjudicated (pre-constitutional boundary)
- Tier 3 verification obligation: if a Tier 3 party's independence is compromised, challenge routes to CIC for constitutional interpretation — not self-adjudicated
- CIC's AC-31 rulings: challengeable via ISR (38B-01 Part E) — not self-adjudicated

**Verdict: No self-referential chain identified within the current model.** Each tier's legitimacy is governed by a different tier or external process. No single actor adjudicates its own legitimacy within the AC-31 governance framework. Future threat analysis may identify chains not visible at this specification stage.

**Overall OBS-38A06-01 result:** No self-referential validation chain has been identified in the Multi-Party Tiered AC-31 Governance Model. All three OBS-38A06-01 checks find no chain at the constitutional governance specification level. The residual coalition risk (MA + two Tier 3 parties simultaneously compromised) is documented as a known residual risk, not a design failure. Threat validation in Round 38C or later may identify chains not visible at this stage.

**OBS-38B02-AI1 (Authority Inventory Note, following OBS-38B01-AI1 pattern):** This specification adds NO new authority aggregates. The Multi-Party Tiered model reuses existing constitutional actors (EC, MA, AuditScopeAuthority, AuditExecutionAuthority, CertificationAuthority, CIC). This is the correct application of OBS-38B01-AI1 discipline: the governance objective (distributed AC-31 governance) is achieved without increasing the authority aggregate count.

**OBS-38B02-01 (MA Dependency Increase — required carry-forward):** The Multi-Party Tiered Governance model reduces AC-31 governance concentration by removing any single operational actor from governing the reference standard alone. However, it achieves this by elevating the Membership Assembly to the Tier 2 designation authority for AC-31. The architectural consequence is that MA dependency increases: MA now grounds not only the ElectionConstitution and all 8 authority aggregates via AA-01, but also the specific designation of the Authenticity Root. Whether this tradeoff — reduced AC-31 concentration, increased MA dependency — is constitutionally favorable remains for future evaluation. It is not evaluated in this specification. This observation carries forward to all remaining 38B specifications and to future threat analysis of the MA as a structural concentration point.

---

## Part P — Open Questions Generated (Deliverable N)

### P.1 Primary Open Question

**OQ-38B02-01 (Primary) — AC-31 Governance Constitutional Floor**

Following the OBS-38A06-SD1 analysis and OQ-38B01-06 (elevated primary question for 38B-01): Should the EC Tier 1 constitutional properties provision for AC-31 include an unamendable constitutional floor — a provision that cannot be removed by ordinary EC amendment?

If the Tier 1 constitutional properties of AC-31 can be removed by EC amendment, then the Authenticity Root constitutional requirement can be deleted. A constitutional architecture without a required independent reference standard for evidence authenticity would permit self-authentication — directly contradicting AC-02. This is the OBS-38A06-SD1 pattern applied to AC-31 specifically.

This question requires Gap 3 specification to address as a named deliverable.

### P.2 Remaining Open Questions

| OQ | Question | Where It Belongs |
|---|---|---|
| OQ-38B02-02 | What is the constitutional form of MA designation procedure for AC-31 — vote, unanimous decision, supermajority? | EC designation provision; informed by Gap 3 |
| OQ-38B02-03 | What timeline is constitutionally specified for emergency AC-31 revocation and succession? | EC designation provision; operational constraint |
| OQ-38B02-04 | When AC-31 compromise is confirmed, are elections certified under the compromised AC-31 suspended pending OQ-38A05-02 CIC ruling? | OQ-38A05-02 — deferred to CIC; explicitly flagged |
| OQ-38B02-05 | How are Tier 3 verification obligations specified when AuditScopeAuthority and AuditExecutionAuthority designs remain provisional (ADR-4 SUBMITTED)? | ADR-4 finalization required; Tier 3 obligations remain provisional |
| OQ-38B02-06 | Does the MA have constitutional authority to designate a temporary AC-31 during a succession gap? | EC designation provision; Gap 3 amendment governance |

---

## Part Q — ARB Decision Record

**ARB Decision: APPROVED WITH MINOR OBSERVATIONS (2026-06-17)**

**Revisions applied (R1/R2/R3):**
- R1: Weakened closing "no single actor" claim — replaced with language about probability reduction; residual coalition risks explicitly stated
- R2: Weakened three "NOT SELF-REFERENTIAL" verdicts — replaced with "No self-referential chain identified within the current model" with forward-looking caveat; overall result language updated
- R3: Added OBS-38B02-01 (MA Dependency Increase) — AC-31 concentration reduced at the cost of increased MA dependency; tradeoff favorability deferred to future evaluation

**ARB confirmations (per review):**
1. Alternative 4 (Multi-Party Tiered Governance) CONFIRMED correct — superior to A31GB model (DeepSeek alternative); no new aggregate; proper separation of governance powers
2. OA-01 integration CONFIRMED — constitutional access rights stronger than record publication; governance vs implementation distinction correct
3. OQ-38A05-02 handling CONFIRMED — CIC referral routing upon confirmed compromise is correct; no implicit resolution
4. OBS-38B01-AI1 compliance CONFIRMED — no new aggregate added; OBS-38B02-AI1 correctly records this
5. Alternative 3 rejection CONFIRMED as absolute — CA+AC-31 double concentration is the worst available architecture

**38B-03 AUTHORIZED: Gap 7 — GovernanceState Phase Record Governance Specification**

---

*AC-31 is the Authenticity Root. Governing it does not eliminate its concentration risk — it distributes that risk across constitutional actors who each hold a piece of governance responsibility. The architecture reduces the probability that a single actor's compromise captures AC-31 governance. Residual coalition risks remain. That distribution is the constitutional protection. It is not elimination of risk; it is constitutionally managed distribution of it.*
