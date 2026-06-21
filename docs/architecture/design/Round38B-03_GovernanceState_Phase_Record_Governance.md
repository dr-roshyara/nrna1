# Round 38B-03 — Gap 7: GovernanceState Phase Record Governance Specification

**Status:** APPROVED WITH INTEGRATIONS (ARB Review 2026-06-17; DeepSeek cross-review integrated: TM-44 detectability, AA-07 binding input, G.4 deadlock-breaking, OBS-38B03-02/03 liveness observations, OQ-38B03-07)
**Round:** 38B-03 (Third specification under Round 38B)
**Gap Target:** Gap 7 — GovernanceState Phase Record Governance (Temporal Root)
**Predecessor:** Round38B-02_AC31_Governance_Specification.md (APPROVED WITH MINOR OBSERVATIONS APPLIED, 2026-06-17)
**Authorization Basis:** Round38B-Authorization-Decision.md Section 4.2; 38B-02 ARB approval; 38A-06-ARB-Review Gap 7 confirmation
**Date:** 2026-06-17
**Discipline:** Constitutional Governance Specification only. No DDD design. No bounded contexts. No aggregates. No services. No APIs. No cryptographic mechanisms. No technology selection. No implementation choices.
**Provisional Note (per SC-C):** All references to AuditScopeAuthority and AuditExecutionAuthority in this document are provisional. ADR-4 remains SUBMITTED FOR ARB REVIEW.
**Framing Rule (per ARB guidance before 38B-03):** GovernanceState is not a database. GovernanceState is a constitutional authority record. The governing question is not "How do we store phases?" The governing question is: **"Who has authority to establish, challenge, correct, certify, and succeed phase records?"**

---

## Part A — Program State Consistency Check

Per Round38B-Authorization-Decision.md Section 9.1 and the Program State Reconciliation Rule.

| Check | Finding | Implication |
|---|---|---|
| Gap 7 confirmation status | CONFIRMED by 38A-06-ARB-Review | THIS DOCUMENT targets Gap 7 |
| Gap 7 basis | TM-40 (self-certifying); self-referential challenge problem | Core architectural problem identified |
| ADR-7 GovernanceState baseline | GovernanceState = RECORDS constitutional authority; NOT authority aggregate; D43 does not apply | Records vs. governs distinction binding |
| OBS-ADR7-03 | GovernanceState corruption → constitutional evidence ambiguity (phase active? window open?) | Corruption has constitutional, not merely operational, consequences |
| TM-42 (unconditional F) | Complete Deadlock = unconditional FAIL; succession connection | Gap 7 succession specification is constitutional survival requirement |
| TM-44 (Rollback Attack) | Retroactive phase manipulation; no append-only requirement in current architecture | Constitutional detectability of retroactive alteration is a Gap 7 specification requirement |
| AA-07 (sole authoritative record) | GovernanceState is currently the sole authoritative phase record | This is the structural gap that multi-party corroboration resolves; AA-07 remains a hidden assumption until Gap 7 is governed |
| OQ-38A05-02 | Formally unresolved; protected | Not touched here; GovernanceState record corruption is a contributing factor to OQ-38A05-02 but does not resolve it |
| OBS-38B02-01 | MA dependency increasing with each 38B specification | This specification must track whether GovernanceState governance adds to MA dependency |
| 38B-01 binding invariant (38B01-INV-01) | CIC interprets; CAB adjudicates; CIC does not operationally govern | Routing of GovernanceState constitutional challenges must respect this |

**Consistency check result:** All items verified. 38B-03 may proceed.

---

## Part B — Constitutional Purpose (Deliverable A)

### B.1 What GovernanceState Is

GovernanceState is the Temporal Root in the Three Trust Roots framework established in Round 38A-05:
- Legitimacy Root = ElectionConstitution
- Authenticity Root = AC-31
- Temporal Root = **GovernanceState**

As established in ADR-7: GovernanceState records constitutional authority — it is a state machine aggregate that records when constitutional phases are entered, active, and exited. It does not authorize those phases. It does not govern those phases. It records them.

This distinction is not administrative. It is constitutional. GovernanceState records are the temporal evidence base for:
- Whether a challenge was filed within the constitutionally specified window
- Whether CertificationAuthority was constitutionally authorized to certify
- Whether AuditExecutionAuthority was constitutionally authorized to evaluate CO-3
- Whether any constitutional act occurred during the correct phase

CO-5 (Election Validity) is the only constitutional act requiring all three trust roots simultaneously. GovernanceState phase records are a required input to CO-5: an election cannot be certified as valid unless the certification phase was constitutionally authorized, which requires a constitutionally valid GovernanceState record.

### B.2 What Gap 7 Names

Gap 7 names the absence of constitutional governance for GovernanceState phase records. ADR-7 established what GovernanceState is and what it records. It did not establish:

- Who is constitutionally authorized to create a GovernanceState phase record
- What constitutional act must precede a valid phase record (what authorizes the phase)
- Who may challenge a GovernanceState phase record that appears incorrect
- What independent evidence a challenger may use (the self-referential problem)
- Who adjudicates a phase record challenge
- Who may correct an incorrect GovernanceState record
- How GovernanceState is succeeded if it fails or is corrupted
- How GovernanceState phase records are corroborated independently

This is the governance gap. GovernanceState's recording function is specified. The constitutional governance of that function is absent.

### B.3 Why the Self-Referential Challenge Problem Is the Structural Core of Gap 7

From 38A-06-ARB-Review: Gap 7 was confirmed because "the self-referential challenge problem [is] confirmed." This problem is what makes Gap 7 constitutionally distinct from a simple record management question:

- To challenge a GovernanceState phase record, a challenger needs independent evidence of the correct phase state
- But GovernanceState IS the authoritative record of phase state
- If GovernanceState is the only source of phase evidence, a challenger challenging a GovernanceState record must use GovernanceState to prove GovernanceState is wrong
- That is self-referential: the record validates itself against itself

This is more severe than AC-31's OBS-38A06-01 analysis. AC-31 governance distributes across actors but the reference standard itself is external to the governance bodies. GovernanceState governance must distribute across actors where the record is the object being governed — it cannot be external to itself.

The resolution: **decouple the evidence of a phase transition from the record of that transition.** The constitutional act that authorizes a phase transition must be recorded independently of GovernanceState. Challenge evidence comes from the authorization record, not from GovernanceState itself.

### B.4 Constitutional Purpose of Gap 7 Governance

GovernanceState Phase Record Governance exists to:
1. Specify who is constitutionally authorized to create a GovernanceState phase record (establishment authority)
2. Specify what constitutional act must precede a valid phase record (authorization grounding)
3. Provide a challenge mechanism that does not depend on GovernanceState to challenge GovernanceState (self-referential problem resolution)
4. Specify who adjudicates phase record challenges and on what evidence basis
5. Specify how GovernanceState is corrected when a record is found constitutionally invalid
6. Specify GovernanceState succession in the event of failure (TM-42 connection)
7. Specify multi-party corroboration to prevent GovernanceState from remaining constitutionally self-certifying (AA-07 resolution)
8. Specify that retroactive alteration of phase records is constitutionally detectable (TM-44 protection)
9. Specify a deadlock-breaking mechanism for when the phase clock freezes without GovernanceState failing (TM-42 non-adversarial path)

---

## Part C — Jurisdiction Scope (Deliverable B)

### C.1 What GovernanceState Phase Record Governance Covers

| Governed by this specification | Not governed by this specification |
|---|---|
| Who may create GovernanceState phase records | How GovernanceState is technically implemented |
| What constitutional act grounds a valid phase record | GovernanceState data schema or storage |
| How GovernanceState records are challenged | GovernanceState API or access protocol |
| What independent evidence is admissible in phase record challenges | GovernanceState replication or backup mechanisms |
| Who adjudicates phase record challenges | Log format or audit trail structure |
| How incorrect records are corrected | Any specific technology selection |
| GovernanceState succession model | Implementation of phase boundary enforcement |
| Multi-party corroboration obligations | Append-only vs. event-stream vs. journal implementation choice |
| Constitutional detectability requirement for retroactive alteration | Whether detectability is implemented as append-only ledger, signed chain, or other mechanism |
| Deadlock-breaking authority and procedure | Technical mechanism for deadlock detection |

### C.2 Phase Records vs. Phase Enforcement

GovernanceState phase records are distinct from phase enforcement. Phase enforcement — preventing an authority aggregate from acting outside its authorized phase — is an operational mechanism. Phase record governance — who establishes, challenges, and corrects the record that reflects constitutional phase state — is a constitutional governance question. This specification governs the record, not the enforcement mechanism.

---

## Part D — Authority Source (Deliverable C)

### D.1 The Root Problem: Who Authorizes What GovernanceState Records

A GovernanceState phase record is constitutionally valid only if it records a phase transition that was constitutionally authorized. Constitutional authorization of a phase transition requires:
1. The ElectionConstitution must specify the conditions under which each phase may begin and end
2. A constitutionally authorized actor must determine that those conditions have been met
3. GovernanceState records the determination

If these three steps are not all present, the GovernanceState record is constitutionally ungrounded — it records something without any constitutional authority behind the recording act.

**OBS-38B03-SA1 (Source-of-Authority Chain — required, following OBS-38B01-SA1 / OBS-38B02-SA1 pattern):**

```text
GovernanceState phase record (specific record entry)
    ↑
GovernanceAuthority determination (operational act: conditions are met)
    ↑
ElectionConstitution phase conditions (constitutional specification: what conditions must be met)
    ↑
MA ratification of EC
    ↑
AA-01 (unresolved — same terminal open question as 38B-01 and 38B-02)
```

Every GovernanceState phase record must be traceable to: EC conditions → GovernanceAuthority determination → record creation. The absence of any link in this chain means the record is constitutionally ungrounded.

### D.2 MA Dependency Note (OBS-38B02-01 Carry-Forward)

The source-of-authority chain for GovernanceState phase records terminates at AA-01 via MA ratification of EC, the same terminal as 38B-01 (CIC) and 38B-02 (AC-31). GovernanceState governance does not introduce a new MA dependency beyond what already exists for all authority aggregates. However, it does confirm that the Temporal Root's governance, like the Authenticity Root's, traces to the same MA-rooted chain. OBS-38B02-01 applies: the tradeoff of operational distribution against MA dependency increase is not evaluated here; it is tracked and carried forward.

---

## Part E — Governance Model (Deliverable D)

### E.1 The Three-Function Separation

Following the architect's framing: who has authority to establish, challenge, correct, certify, and succeed phase records? These functions are separated:

| Function | Constitutional Role | Basis |
|---|---|---|
| **Establish** (create a phase record) | GovernanceAuthority, upon EC-specified conditions being met | ADR-7; D43-GOV-AUTH authority relationship |
| **Authorize** (determine that EC conditions are met) | GovernanceAuthority (primary); specific aggregates for specific phases (see E.3) | Phase-specific authority relationships |
| **Corroborate** (independently verify the record matches the authorization) | Multi-party corroboration obligation (see E.4) | TM-40 prevention |
| **Challenge** (dispute a phase record's constitutional validity) | Any standing party — same standing classes as ADR-5 (S-1/S-2/S-3) | OQ-38A05-02 must not be implicitly resolved |
| **Adjudicate** (rule on a phase record challenge) | CIC for constitutional interpretation; CAB for standing-based adjudication | 38B01-INV-01 |
| **Correct** (modify a GovernanceState record found constitutionally invalid) | GovernanceAuthority (under CIC or CAB instruction) | Same authority that created the record |
| **Succeed** (continue GovernanceState function when the primary record system fails) | Pre-designated in EC; independent of GovernanceAuthority | ADR7-INV-01; TM-42 connection |

### E.2 Why Not Route All Phase Authority Through GovernanceAuthority Alone

GovernanceAuthority holds constitutional authority for governance transitions (ADR-2: Option B — Committee Independence). However, routing all phase transition authority through GovernanceAuthority alone recreates a single-actor concentration for the Temporal Root, mirroring the AC-31 governance problem that Alternative 2 (dedicated governance body) presented in 38B-02.

Furthermore: GovernanceAuthority's own operations depend on GovernanceState phase records (GovernanceAuthority may only act in constitutionally authorized phases). If GovernanceAuthority is both the creator of phase records AND the sole validator of its own authority to act, a self-referential loop exists: GovernanceAuthority determines that its authority phase is active (GovernanceState says so), but GovernanceAuthority created the GovernanceState record that says so.

This is the specific self-referential risk for GovernanceState: an authority aggregate that creates phase records cannot rely solely on those records to justify its own authority to act. External corroboration is required.

### E.3 Phase-Specific Authorization Authority

Not all phases require GovernanceAuthority authorization. The constitutional architecture already establishes phase-specific authority relationships:

| Phase Transition | Primary Authorization Authority | Constitutional Basis |
|---|---|---|
| Election creation → Setup phase | GovernanceAuthority | ADR-2; D43-GOV-AUTH |
| Setup → Enrollment open | GovernanceAuthority; AuditScopeAuthority scope publication prerequisite (provisional) | Completeness Stratum Link 3; ADR-4 |
| Enrollment → Voting open | GovernanceAuthority; EC-specified conditions | ADR-2 |
| Voting open → Voting closed | GovernanceAuthority; EC-specified close conditions | ADR-2; ADR6-CONSTRAINT-01 (challenge window begins) |
| Voting closed → Certification authorized | GovernanceAuthority; CO-2/CO-3 evaluation completion (AuditExecutionAuthority, provisional) | ADR-6; CO-5 prerequisite chain |
| Certification phase → Challenge window | Automatic per ADR6-CONSTRAINT-01; window pre-specified in EC | ADR-6 |
| Challenge window close → Election concluded | GovernanceAuthority; TS-1 (no outstanding challenge resolution pending) | ADR-6 TS-1 |

**Provisional note:** All rows referencing AuditScopeAuthority and AuditExecutionAuthority are provisional pending ADR-4 finalization.

### E.4 Multi-Party Corroboration Obligation

Following the AC-31 precedent: GovernanceState phase records must be independently corroborated. No single actor may be the sole source of evidence that a GovernanceState record is correct.

Corroboration structure:
- GovernanceAuthority creates the phase record (record establishment act)
- At least two additional authority aggregates whose operations depend on the phase (the phase-dependent authorities) hold an obligation to independently verify: does the GovernanceState record match the authorization conditions they observed in their own constitutional mandate?
- If GovernanceAuthority created a phase record that none of the phase-dependent authorities can corroborate, the corroboration obligation is unmet and an automatic challenge review is triggered

This structure decouples GovernanceState record validity from GovernanceState itself: the corroborating evidence is the independent observation of the phase transition by other authority aggregates.

**OA-01 Parallel — GovernanceRecord Access:** Challenging parties must have constitutional access to:
- The GovernanceState phase record being challenged
- The EC conditions that govern the challenged phase transition
- The corroboration records held by phase-dependent authorities

Denial of access to these records constitutes a constitutional violation, not a technical policy decision.

---

## Part F — Challengeability Model (Deliverable E)

### F.1 The Self-Referential Problem — Resolved

The self-referential challenge problem (Part B.3) is resolved by the decoupling established in Part E.4:

- **Challenge evidence is NOT GovernanceState.** Challenge evidence is: (a) the EC-specified conditions for the phase transition; (b) the corroboration records of phase-dependent authorities; (c) the authorization act of GovernanceAuthority (independent of the GovernanceState record it created)
- **GovernanceState is the object being challenged, not the evidence for the challenge.** A challenger presents evidence from the EC conditions and corroboration records to argue that the GovernanceState entry is constitutionally wrong.

This is analogous to how a document's validity is challenged by showing the document was not properly executed — the document itself is not the evidence of its own invalidity.

### F.2 Standing to Challenge a GovernanceState Phase Record

Standing classes follow ADR-5:
- S-1 (Directly Affected): any constitutional actor whose own authority to act depends on the phase being challenged (e.g., if GovernanceState records certification phase as closed but CertificationAuthority believes it is open)
- S-2 (Constitutional Observer): any authority aggregate or CIC acting under constitutional observation mandate
- S-3 (Authority Peer): any authority aggregate whose mandated operations were affected by the phase record in question

### F.3 Challenge Routing

| Challenge Type | Route | Adjudicator |
|---|---|---|
| Does GovernanceState record match EC-specified conditions? | Constitutional interpretation question → CIC | CIC issues ruling |
| Did GovernanceAuthority have authority to create this record at this time? | Constitutional interpretation question → CIC | CIC issues ruling |
| Was a corroboration obligation unmet, making the record invalid? | Adjudication with evidentiary foundation → CAB | CAB adjudicates with CIC consultation if constitutional question embedded |
| Is this challenge filed within the constitutionally valid challenge period? | Procedural adjudication → CAB | CAB adjudicates; GovernanceState records the period — this is where the circularity re-emerges (see F.4) |

### F.4 The Remaining Circularity — Temporal Challenge Bootstrapping

There is a residual circularity that this specification cannot fully eliminate: challenging the GovernanceState record of a challenge window requires knowing when the challenge window is open — which is recorded in GovernanceState. A corrupted challenge window record could prevent all phase challenges from being filed in time.

This residual circularity is addressed not by elimination but by:
1. **EC-specified challenge windows** (ADR6-CONSTRAINT-01): challenge windows are constitutionally pre-specified in the EC and published before the election begins. A challenger does not need GovernanceState to know when the challenge window opens — they know from the EC.
2. **CIC emergency jurisdiction:** CIC may receive an ISR (Interpretation Supersession Request) at any point, including during a potentially-corrupted window. The ISR path is independent of GovernanceState phase records.

This does not fully resolve the bootstrapping problem in all corruption scenarios. OQ-38B03-01 documents this as the primary remaining open question.

---

## Part G — Succession Model (Deliverable F)

### G.1 GovernanceState Failure and TM-42 Connection

TM-42 (Operational Deadlock) was classified as unconditional F for Complete Deadlock — the first unconditional non-adversarial constitutional FAIL in the program. From 38A-04: "Complete Deadlock = F (UNCONDITIONAL); no constitutional minimum capacity; no reconstitution mechanism; non-adversarial trigger possible."

GovernanceState failure is a primary path to Complete Deadlock: if the phase record is inaccessible or corrupted beyond correction, no authority aggregate can determine what phase the election is in, cannot determine what actions are constitutionally authorized, and cannot proceed. The entire constitutional architecture stalls.

GovernanceState succession is therefore a constitutional survival requirement of the same order as AC-31 succession.

### G.2 Succession Requirements

Following the same pattern as 38B-01 Part F and 38B-02 Part G:

1. **Pre-designated succession in EC:** Successor GovernanceState record-keeping function is designated before the primary GovernanceState becomes active. This follows ADR7-INV-01 (Suspension Succession).
2. **Succession chain, not single successor:** At least two succession levels.
3. **Succession trigger specification:** Constitutionally specified triggers:
   - GovernanceState record inaccessibility exceeding EC-specified duration
   - GovernanceState record corruption confirmed by multi-party corroboration failure
   - GovernanceState record disagreement between primary and corroboration sources that CIC cannot resolve within the constitutionally specified time
4. **Successor independence:** The successor GovernanceState record-keeping function must be independent of the primary function. Sharing infrastructure defeats succession.
5. **Corroboration carries through succession:** The multi-party corroboration obligation applies equally to the successor GovernanceState function. A successor that is also self-certifying does not solve the problem.

### G.3 Succession and the Phase Bootstrapping Problem

If GovernanceState succession is triggered during an active election, the successor must reconstruct the current phase state. The constitutional basis for that reconstruction: the EC-specified conditions and the corroboration records held by phase-dependent authorities (established in Part E.4). The successor does not inherit the potentially-corrupted primary GovernanceState records as authoritative — it reconstructs from independent evidence.

### G.4 Deadlock-Breaking (Distinct from Succession)

Succession handles GovernanceState unavailability — the record-keeping function cannot proceed. Deadlock-breaking handles a different failure mode: GovernanceState is operational and recording correctly, but GovernanceAuthority is constitutionally unable to authorize a phase transition (quorum failure, constitutional incapacity, coordination failure). The phase clock stops without GovernanceState failing. This is TM-42's non-adversarial path: no adversary required, no GovernanceState corruption, just an authority aggregate that cannot act.

The constitutional requirement: a mechanism must exist for phase clock advancement when normal authorization pathways are blocked.

**Candidate mechanism:** any verifying authority (Part E.4) may petition for phase advancement when phase stall exceeds an EC-specified duration. CIC interprets whether constitutional conditions for advancement are constitutionally met. A constitutionally designated deadlock recovery authority may order phase advancement based on CIC ruling. GovernanceState records the recovery-authority-ordered transition as a distinct recovery act.

**Deadlock Recovery Authority — Provisional:** The specific constitutional actor designated as deadlock recovery authority is not yet determined. CAB is the strongest candidate: it adjudicates phase challenges and can assess whether phase advancement conditions are met. However, designating CAB as temporal recovery authority creates a potential constitutional concentration: adjudicator + temporal recovery authority in the same body — the same kind of dual-function concentration that ADR-6 flagged for CertificationAuthority. The specific recovery actor remains provisional pending evaluation of concentration effects. OQ-38B03-07 documents this as an open question.

**Deadlock-breaking mechanism requirements (regardless of which actor is designated):**
1. The deadlock recovery authority must have independent operational capacity — it must not itself depend on the frozen phase record to determine its own authority to act
2. The recovery mechanism must not be subject to the same deadlock it is meant to break
3. Recovery transitions must be distinguishable in GovernanceState records from normal GovernanceAuthority-authorized transitions
4. EC must pre-specify the conditions that trigger deadlock recovery (duration threshold, corroboration confirmation)

---

## Part H — Correction Model (Deliverable G)

### H.1 Correction vs. Revision

A GovernanceState phase record that is found constitutionally invalid must be corrected, not merely flagged. Correction changes the record to reflect the constitutionally accurate phase state.

Correction authority follows from establishment authority: GovernanceAuthority, which created the record, is the authority that corrects it. Correction must be:
1. Authorized by CIC ruling (constitutional interpretation question) or CAB order (adjudication order)
2. Documented with the basis for correction (what was wrong and what the correct state is)
3. Corroborated by the same multi-party obligation as original record creation

### H.2 Constitutional Detectability of Retroactive Alteration (TM-44)

Prior phase records must remain constitutionally reviewable. Retroactive alteration must be detectable: any modification to a previously recorded phase state must be visible as a modification, not a silent overwrite.

Phase corrections must be recorded as distinct constitutional acts, preserving the original record and the correction as separate entries in the phase history. The original record of what was previously believed to be the constitutional phase must survive the correction — both for constitutional transparency and for challengeability. A correction that erases the original record is constitutionally invalid: it makes the prior error unverifiable and renders a fraudulent correction indistinguishable from a legitimate one.

Whether this constitutional requirement is realized through an append-only ledger, an event stream, a signed journal, a cryptographic audit chain, or another mechanism belongs to technical architecture (38C+). The constitutional requirement is detectability of retroactive alteration, not a specific implementation pattern.

### H.4 Retroactive Effect of Corrections on Prior Constitutional Acts

When a GovernanceState phase record is corrected, constitutional acts that depended on the incorrect record must be evaluated for their validity. This is OQ-38A05-02 territory: if CO-5 was issued under a phase record that is subsequently corrected to show the certification phase was not constitutionally authorized, is CO-5 void?

This document does not and may not resolve OQ-38A05-02. The correction model must specify that retroactive constitutional effect of corrections is a CIC question, not a GovernanceAuthority correction authority question.

---

## Part I — Independence Requirements (Deliverable H)

### I.1 GovernanceState Independence Form

Following the independence discipline established in ADR-2 and the form discipline of 38B-01 Part G.2 and 38B-02 Part I.1: the constitutional requirement is that GovernanceState phase records are independently verifiable, not self-validating.

This does not specify a technology, organization, or bounded context. The independence requirement has two aspects:

1. **Record creation independence from record validation:** GovernanceAuthority creates records; multi-party corroboration validates them; these are distinct constitutional functions performed by different actors
2. **Phase record independence from GovernanceAuthority authority:** GovernanceAuthority's authority to act in a given phase must be corroborated by evidence independent of the GovernanceState records GovernanceAuthority itself created

### I.2 The GovernanceAuthority Operational Independence Constraint

**OBS-38B03-INV-01 (Required Constraint):** GovernanceAuthority may not use GovernanceState phase records created by GovernanceAuthority as the sole constitutional basis for GovernanceAuthority's own authority to act. At least one corroborating source independent of GovernanceState must confirm GovernanceAuthority's operational phase.

This invariant is required to break the self-referential loop identified in Part E.2. It is a binding constitutional constraint that must appear in the EC designation provision for GovernanceAuthority.

---

## Part J — Relationship to CIC (Deliverable I)

CIC intersects with GovernanceState Phase Record Governance in four ways:

| Intersection | CIC Role |
|---|---|
| Does GovernanceState record match EC-specified phase conditions? | Constitutional interpretation → CIC rules |
| Did GovernanceAuthority act within its constitutionally authorized phase? | Constitutional interpretation → CIC rules |
| Temporal challenge bootstrapping emergency (F.4) | ISR path available to CIC regardless of GovernanceState phase record |
| Retroactive effect of GovernanceState correction on CO-5 | OQ-38A05-02 — CIC receives referral; does not resolve here |

38B01-INV-01 applies: CIC interprets; CIC does not correct GovernanceState records. CIC rules on constitutional validity; GovernanceAuthority acts on that ruling.

---

## Part K — Relationship to CertificationAuthority (Deliverable J — supplementary)

CertificationAuthority (CA) requires GovernanceState confirmation that the certification phase is constitutionally authorized before issuing CO-4 or CO-5. This creates a dependency:

```text
CertificationAuthority (issues CO-5)
    ↓ requires
Certification phase = constitutionally authorized (per GovernanceState)
    ↓ requires
GovernanceState record = constitutionally valid (not self-certified)
    ↓ requires
Multi-party corroboration of GovernanceState (Part E.4)
```

The corroboration obligation therefore transitively protects CO-5: if GovernanceState records cannot be corroborated, CO-5 cannot be constitutionally grounded in the Temporal Root. This is the constitutional mechanism by which Gap 7 resolution strengthens ADR-6's CO-5 framework.

---

## Part L — Relationship to ChallengeAdjudicationBody (Deliverable J — supplementary)

CAB's challenge adjudication authority depends entirely on GovernanceState: CAB adjudicates within the constitutionally specified challenge window, which GovernanceState records. If GovernanceState records are incorrect about the challenge window, CAB may adjudicate challenges it has no constitutional authority to adjudicate (window already closed) or refuse to adjudicate challenges it is constitutionally required to receive (window falsely recorded as closed).

Gap 7 governance directly strengthens CAB's constitutional legitimacy by ensuring the phase records on which CAB depends are themselves constitutionally grounded.

ADR7-INV-02 (Anti-Capture Invariant): no authority may self-grant or restrict standing. GovernanceState records must not be the sole mechanism by which CAB validates its own challenge jurisdiction. Corroboration (Part E.4) provides the independent jurisdictional grounding.

---

## Part M — Alternative Models Evaluated (Deliverable L)

### M.1 Alternative 1 — GovernanceAuthority as Sole Phase Record Authority

**Description:** GovernanceAuthority holds exclusive authority over all GovernanceState records — creates them, manages them, adjudicates challenges to them.

**Assessment:**
- Simple; single authority; no coordination complexity
- Recreates the self-referential problem: GovernanceAuthority depends on GovernanceState to determine its own operating phase; GovernanceAuthority creates GovernanceState records; no independent corroboration
- OBS-38A06-01 Q1: GovernanceAuthority validates its own records — FAILS
- OBS-38A06-01 Q3: GovernanceAuthority adjudicates challenges to GovernanceState records that justify GovernanceAuthority's own authority — FAILS
- TM-40 (self-certifying) directly maps to this alternative — it is the current ungoverned state

**Verdict: REJECTED.** This is the structure that Gap 7 exists to replace.

---

### M.2 Alternative 2 — Dedicated GovernanceState Governance Body (New Aggregate)

**Description:** A new authority aggregate governs GovernanceState records exclusively.

**Assessment:**
- OBS-38B01-AI1 triggered: 9th authority aggregate
- OBS-38B02-AI1 pattern: concentration moves, does not disappear
- The new governance body must still rely on something independent of GovernanceState to adjudicate GovernanceState challenges — a second-order record problem
- If the governance body creates its own records of GovernanceState decisions, those records need governance too (infinite regress risk)
- No structural advantage over Alternative 4 that justifies the governance burden increase

**Verdict: REJECTED.** Same reasons as AC-31's Alternative 2 (38B-02 Part M.2). Governance burden not justified when Alternative 4 achieves the same objective without a new aggregate.

---

### M.3 Alternative 3 — EC Anchor Only (No Operational Governance)

**Description:** EC specifies all phase conditions; GovernanceState records them automatically. No operational governance body governs phase records. Constitutional review after the fact only.

**Assessment:**
- No self-referential problem if GovernanceState simply records EC conditions mechanically
- But: EC cannot specify every operational condition for every phase transition in advance with sufficient precision — operational determination is still required
- Mechanical recording without operational authority determination recreates Gap 7 as an operational ambiguity problem rather than a governance problem
- TM-41 (Phase Boundary Ambiguity) — phase completion "not specified" — is the direct evidence that EC-only specification is insufficient
- TM-42 (Operational Deadlock) — no constitutional minimum capacity — is directly linked to the absence of an operational governance mechanism

**Verdict: REJECTED.** EC-only specification is necessary but not sufficient. Operational phase transition authority is required.

---

### M.4 Alternative 4 — EC-Anchored Phase Specification + GovernanceAuthority Record + Multi-Party Corroboration + CIC Constitutional Challenge Resolution

**Description:** EC specifies conditions for each phase transition (constitutional floor). GovernanceAuthority determines when conditions are met and creates GovernanceState records. Multiple phase-dependent authority aggregates hold corroboration obligations. CIC adjudicates constitutional challenges to phase records. CAB adjudicates procedural standing challenges. GovernanceState succession pre-designated in EC.

**Assessment:**
- No new authority aggregate (OBS-38B01-AI1 respected; OBS-38B02-AI1 continued)
- Self-referential problem resolved: challenge evidence is EC conditions + corroboration records (not GovernanceState)
- GovernanceAuthority's self-referential dependency broken by OBS-38B03-INV-01 (corroboration required for GovernanceAuthority's own phase authorization)
- OA-01 parallel: GovernanceRecord Access rights built into governance structure
- Succession pre-designated per ADR7-INV-01 and TM-42 survival requirement
- CIC handles constitutional disputes; CAB handles procedural disputes (38B01-INV-01 compliant)

**OBS-38A06-01 check (preliminary — full check in Part O):**
- Q1: Does the model validate its own records? → EC specifies conditions; GovernanceAuthority records; corroboration verifies. No single body validates its own records using the records it created. MANAGEABLE.
- Q2: Does the model determine its own succession without external input? → EC pre-designates. SAFE.
- Q3: Does the model adjudicate challenges to its own legitimacy? → CIC adjudicates; GovernanceAuthority does not adjudicate challenges to its own phase records. MANAGEABLE.

**Verdict: SELECTED.** See Part N.

---

## Part N — Selected Governance Model (Deliverable M)

### N.1 Selection

**Alternative 4 — EC-Anchored Phase Specification + GovernanceAuthority Record + Multi-Party Corroboration + CIC Constitutional Challenge Resolution** is selected.

### N.2 Complete GovernanceState Phase Record Governance Specification

**Constitutional Architecture:**

| Layer | Function | Constitutional Actor | Basis |
|---|---|---|---|
| Constitutional conditions | What makes a phase transition constitutionally valid | ElectionConstitution (per phase) | Fixed until Gap 3 amendment |
| Phase determination | Whether EC conditions are met for a specific transition | GovernanceAuthority | ADR-2; D43-GOV-AUTH |
| Record creation | Creating the GovernanceState entry recording the transition | GovernanceAuthority | ADR-7 |
| Corroboration | Independently verifying the record matches the observed transition | Phase-dependent aggregates (multi-party) | TM-40 prevention |
| Constitutional challenge adjudication | Does the record match EC conditions? | CIC | 38B01-INV-01 |
| Procedural challenge adjudication | Was the challenge filed properly? Is standing established? | CAB | ADR-5 |
| Correction | Modifying a constitutionally invalid record | GovernanceAuthority (under CIC/CAB instruction) | Established authority |
| Succession | Continuing record function when primary fails | Pre-designated in EC | ADR7-INV-01; TM-42 |

**Required EC Provision Elements for GovernanceState Phase Record Governance:**

1. Phase-specific conditions for each constitutional phase transition (operational determinability required — not purely abstract)
2. GovernanceAuthority designation as primary phase record establishment authority
3. Multi-party corroboration parties per phase type (which aggregates corroborate which transitions)
4. GovernanceRecord Access rights for challenging parties (OA-01 parallel)
5. Challenge window specification for phase record challenges (must be EC-published before election)
6. CIC jurisdiction over constitutional phase record questions
7. OBS-38B03-INV-01: GovernanceAuthority may not rely solely on GovernanceState records of its own creation to justify its own operating phase
8. Succession chain for GovernanceState (at least two levels; independence requirement)
9. Succession trigger conditions
10. Retroactive correction effect referral to CIC (OQ-38A05-02 interaction)
11. Constitutional detectability requirement for retroactive phase record alteration: any modification to a prior entry must be recorded as a distinct correction act; the original entry must survive (TM-44 protection; realization-neutral)
12. Deadlock recovery authority designation, trigger conditions (EC-specified stall duration), and procedure (Part G.4)

---

## Part O — OBS-38A06-01 Self-Referential Validation Check

Per Round38B-Authorization-Decision.md Section 4.7 and 9.4.

---

**Q1: Does the governance model validate its own records?**

Analysis: EC specifies conditions (EC does not validate its own phase records — it specifies requirements). GovernanceAuthority creates records (GovernanceAuthority does not validate the records it creates against GovernanceState — corroboration verifies). Phase-dependent aggregates corroborate (they do not use GovernanceState to corroborate GovernanceState — they use their own independent operational evidence). CIC adjudicates challenges (CIC does not use GovernanceState records to rule on GovernanceState records — it uses EC conditions and corroboration evidence).

**Verdict: No self-referential chain identified within the current model.** The critical break: corroboration uses evidence independent of GovernanceState. Future threat analysis may identify chains not visible at this specification stage.

---

**Q2: Does the governance model determine its own succession without external input?**

Analysis: EC pre-designates GovernanceState succession (Tier 1 constitutional provision). GovernanceAuthority does not self-designate a GovernanceState successor. Phase-dependent aggregates do not select GovernanceState successors. Succession triggers are EC-specified.

**Verdict: No self-referential chain identified within the current model.** EC pre-specification provides external grounding for succession determination. Future threat analysis may identify chains not visible at this specification stage.

---

**Q3: Does the governance model adjudicate challenges to its own legitimacy?**

Analysis: GovernanceAuthority creates records but does not adjudicate challenges to them (CIC adjudicates constitutional questions; CAB adjudicates procedural questions). Phase-dependent aggregates corroborate but do not adjudicate their own corroboration disputes. CIC adjudicates challenges to GovernanceState records; CIC itself is challengeable via ISR (38B-01 Part E).

**Verdict: No self-referential chain identified within the current model.** OBS-38B03-INV-01 breaks GovernanceAuthority's potential self-adjudication loop. Future threat analysis may identify chains not visible at this specification stage.

**Overall OBS-38A06-01 result:** No self-referential validation chain identified in the selected governance model at the constitutional governance specification level. The corroboration mechanism (Part E.4) is the structural break that prevents self-referential collapse. The residual bootstrapping circularity (Part F.4 — challenge window bootstrapping) is documented as a known structural limitation, not eliminated.

**OBS-38B02-AI1 pattern continued:** No new authority aggregate is created. GovernanceState governance uses existing actors (EC, MA, GovernanceAuthority, phase-dependent aggregates, CIC, CAB).

**OBS-38B03-01 (Trust Root Governance Completion Observation):**

With 38B-03, the constitutional architecture now has governance specifications for all three trust roots:

| Trust Root | Gap | Specification | Governance Model |
|---|---|---|---|
| Legitimacy Root (ElectionConstitution) | Gap 4 | 38B-01 (CIC) | Independent interpretive authority; no self-validation |
| Authenticity Root (AC-31) | Gap 5 | 38B-02 (Multi-Party Tiered) | Distributed designation + verification |
| Temporal Root (GovernanceState) | Gap 7 | 38B-03 (EC-Anchored + Corroboration) | Phase-specific conditions + multi-party corroboration |

The remaining confirmed gaps (Gap 6 — Operational Independence; Gap 3 — EC Amendment Process) govern the PROCESSES that change the constitutional architecture, not the trust roots themselves. Gap 8 (deferred) concerns post-finality review — a constitutional act dependent on all three trust roots but not itself a root. This distinction marks a structural progression: 38B-01 through 38B-03 have specified governance of what the constitution is built on; 38B-04 and 38B-05 will specify governance of how the constitution changes and defends itself.

**OBS-38B03-02 (Temporal Liveness Requirement — integrated from cross-review):** Temporal governance differs fundamentally from legitimacy governance and authenticity governance in its liveness requirements. Legitimacy disputes may remain unresolved — the election proceeds under the current best interpretation while constitutional ambiguity persists. Authenticity disputes may remain unresolved — evidence authenticity questions can be preserved for post-election review. Temporal disputes cannot remain unresolved: if the constitutional clock stops (TM-42), if the phase record is contested without resolution (TM-03), or if the current phase is constitutionally ambiguous (TM-10), constitutional operation cannot proceed. Correctness alone is insufficient for temporal governance; the system must also guarantee progress. This liveness requirement is why Part G.4 (deadlock-breaking) is a constitutional necessity and not merely an operational concern.

**OBS-38B03-03 (Continuous vs. On-Demand Governance — integrated from cross-review):** Legitimacy governance (CIC) and authenticity governance (AC-31) operate on demand: a constitutional question triggers CIC interpretation; a verification need triggers Tier 3 verification. Temporal governance must operate continuously: the phase clock must advance regardless of whether any dispute exists. This continuous liveness property — the requirement that the system make progress even in the absence of challenges — distinguishes temporal governance from the other two trust roots. It imposes a distinct structural requirement on the deadlock-breaking mechanism (Part G.4): that mechanism must be available even when no standing party has filed a challenge, and even when all parties believe the current phase is correct. This distinction will carry forward to technical architecture (38C+) where distributed systems liveness constraints apply directly.

**OBS-38B02-01 carry-forward confirmed:** The governance model for the Temporal Root traces to the same MA-rooted source-of-authority chain as the Legitimacy Root and Authenticity Root. The MA is now the constitutional anchor for all three trust roots at the source-of-source level. Whether this tri-root MA dependency is constitutionally favorable or constitutionally concerning is explicitly deferred for future threat analysis. It is tracked; it is not evaluated here.

---

## Part P — Open Questions Generated (Deliverable N)

### P.1 Primary Open Question

**OQ-38B03-01 (Primary) — Temporal Challenge Bootstrapping**

When a GovernanceState record of a challenge window is itself challenged, the challenge must be filed within a constitutionally specified period — which is recorded in GovernanceState. This creates a potential circularity: a challenger cannot prove the challenge window is open if the challenge window record is what is being challenged.

The EC pre-specification mechanism (ADR6-CONSTRAINT-01) and CIC ISR path provide partial mitigation but do not fully eliminate the bootstrapping problem in all corruption scenarios. The primary open question: is there a constitutionally sufficient challenge mechanism for GovernanceState records of challenge windows that is guaranteed to be non-circular in all scenarios?

This question requires further analysis in threat validation (Round 38C or later) and potentially in Gap 3 (EC Amendment Process) — the amendment process is the most powerful correction mechanism for EC-level governance failures including challenge window definitions.

### P.2 Remaining Open Questions

| OQ | Question | Where It Belongs |
|---|---|---|
| OQ-38B03-02 | What is the constitutional minimum corroboration threshold? (How many phase-dependent aggregates must independently corroborate before a GovernanceState record is constitutionally valid?) | EC designation provision; informed by Gap 6 operational independence spec |
| OQ-38B03-03 | When GovernanceState succession is triggered during an active election, how is the reconstruction of current phase state constitutionally authorized? (Who certifies that the reconstructed state is accurate?) | EC designation provision; succession procedure |
| OQ-38B03-04 | Does GovernanceState correction have retroactive constitutional effect on CO-5? | OQ-38A05-02 — deferred to CIC; explicitly flagged |
| OQ-38B03-05 | How is OBS-38B03-INV-01 verified? If GovernanceAuthority cannot rely solely on GovernanceState records of its own creation, what independent evidence must GovernanceAuthority maintain, and who verifies that independent evidence? | EC designation provision; operational independence (Gap 6) |
| OQ-38B03-06 | Does the three-trust-root MA dependency (OBS-38B03-01 observation) constitute a constitutional concentration risk requiring its own threat analysis? | Future threat validation; Round 38C or later |
| OQ-38B03-07 | If CAB is designated as deadlock recovery authority (Part G.4), does this create a new constitutional concentration: adjudicator + temporal recovery authority in the same body? What alternative deadlock recovery authority models avoid this concentration? | 38B synthesis or Gap 6 concentration analysis; concentration impact assessment required before finalizing deadlock recovery authority |

---

## Part Q — ARB Submission

This document is submitted for ARB review. The ARB is asked to:

1. **Confirm the self-referential resolution** (Part E.4 and Part F.1) — that decoupling challenge evidence from GovernanceState records constitutionally resolves the primary self-referential challenge problem identified in 38A-06-ARB-Review
2. **Confirm OBS-38B03-INV-01** (GovernanceAuthority may not rely solely on its own GovernanceState records for its own authority) as a binding required constraint in the EC designation provision
3. **Confirm OBS-38A06-01 analysis** — no self-referential chains identified at this specification level; bootstrapping circularity correctly classified as a known structural limitation rather than a design failure
4. **Confirm OBS-38B03-01** (Trust Root Governance Completion Observation) — that the structural progression from trust root governance (38B-01 through 38B-03) to process governance (38B-04/38B-05) is correct
5. **Rule on OQ-38B03-01** (Temporal Challenge Bootstrapping) as the primary open question — whether it is resolvable within 38B or requires 38C/Gap 3 treatment
6. **Confirm the provisional phase-specific authority table (Part E.3)** — whether the phase-specific authorization authorities are consistent with the constitutional architecture as it stands
7. **Authorize 38B-04** — Gap 6: Operational Independence Standard Specification

---

*GovernanceState records what the constitution says has happened. Without governance of the record, the temporal evidence base for every constitutional act is constitutionally ungrounded. You can have correct legitimacy and correct authenticity and still have a constitutionally unverifiable election — because no one can prove, independently of GovernanceState, that the phases that produced the election were constitutionally authorized. Gap 7 closes that vulnerability in the Temporal Root.*
