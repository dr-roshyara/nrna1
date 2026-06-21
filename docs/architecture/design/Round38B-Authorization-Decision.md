# Round 38B — Authorization Decision

**Status:** APPROVED — FINAL (ARB Review issued 2026-06-17; all revisions applied; document closed)  
**Authorization Date:** 2026-06-17  
**Authorization Authority:** ARB Chair, Senior DDD Architect, Constitutional Governance Architect, Online Voting Security Architect  
**Predecessor:** Round 38A FORMALLY CLOSED (2026-06-17; Round38A-06-ARB-Review.md)  
**Method:** Authorization only — no specifications, no architectural decisions, no protocol selections, no cryptographic decisions, no implementation work  
**Binding Constraint:** This document opens Round 38B and defines its charter. It does not perform any specification work. All gaps, OQs, observations, and ADR interactions named in this document are inputs to 38B work, not 38B outputs.  
**Applied Revisions:** R1 (ADR-4 dependency softened to preferred prerequisite); R2 (Section 6 split into Required Deliverables / Success Criteria); R3 (Gap 3 sequencing rationale added); Section 4.7 (OBS-38A06-01 permanent checklist); Section 9 (38B-01 pre-authorization, program state consistency check, scope constraints, deliverables A–J, governing discipline)

---

## Section 1 — Authorization Verdict

```text
Round 38B

AUTHORIZED

Charter-bounded.
Protective constraints binding.
Effective: 2026-06-17
```

**Basis for authorization:**

Round 38A is formally closed per Round38A-06-ARB-Review.md Section 10. All required closure conditions were verified (8/8 satisfied). The gap register is definitive. The FAIL catalog is confirmed at 7 distinct findings. The program is at the Authorization Gate between Threat Validation and Constitutional Gap Specification. The confirmed gaps are governance gaps, not technical architecture gaps. The program is ready to specify, not to design.

**What this authorization does:**
- Opens Round 38B as an authorized program phase
- Establishes the 38B charter (Section 4)
- Names binding constraints (Section 5)
- Defines success criteria (Section 6)

**What this authorization does not do:**
- Resolve any open constitutional question
- Specify any gap
- Make any architectural decision
- Authorize any implementation work

---

## Section 2 — 38A Closure Prerequisite Verification

The following prerequisites (derived from Round38A-06-ARB-Review.md Section 11) are verified as satisfied before authorizing 38B:

| Prerequisite | Required Condition | Status |
|---|---|---|
| P-1 | Round 38A formally closed with documented closure basis | ✅ SATISFIED — Section 10 of ARB-Review; 8/8 conditions verified |
| P-2 | Gap register definitive with status for each gap | ✅ SATISFIED — 5 confirmed (3/4/5/6/7); 1 deferred candidate (8) |
| P-3 | FAIL catalog confirmed at 7 distinct findings | ✅ SATISFIED — F-1 through F-7; derived entries (F-8, F-9) correctly excluded |
| P-4 | OQ-38A05-02 explicitly deferred with documented binding constraints | ✅ SATISFIED — ARB-Review Section 3.2; constraints documented |
| P-5 | OQ-38A05-01 ruled | ✅ SATISFIED — RULED: unconditional F; ARB-Review Section 3.1 |
| P-6 | OQ-38A05-03 resolved | ✅ SATISFIED — SUBSUMED by Gap 5; ARB-Review Section 3.3 |
| P-7 | OQ-38A05-06 bifurcated with independent resolution paths assigned | ✅ SATISFIED — Sub-6a → Gap 4 spec; Sub-6b → Gap 5+7 spec; ARB-Review Section 3.4 |
| P-8 | OA-01 confirmed as highest-priority assumption for 38B | ✅ SATISFIED — ARB-Review Section 3.5 |
| P-9 | OBS-38A06-SD1 registered in constitutional record | ✅ SATISFIED — ARB-Review Section 7 |
| P-10 | Cluster B synthesis validated and strengthened | ✅ SATISFIED — ARB-Review Section 6 |

**All 10 prerequisites satisfied. Authorization gate is clear.**

---

## Section 3 — Confirmed Gap Register (38B Baseline)

This is the authoritative gap register as 38B opens. It constitutes the constitutional baseline inherited from 38A.

| Gap | Status | Confirmed By | Constitutional Significance |
|---|---|---|---|
| **Gap 3** — EC Amendment Process Governance | **Confirmed** | 38A-01 | Legitimacy Root governance; EC capture path; OBS-38A06-SD1 enabled |
| **Gap 4** — Constitutional Interpretation Authority | **Confirmed** | 38A-01 | Amplifier of all other gaps; highest priority for resolution sequencing |
| **Gap 5** — AC-31 Governance | **Confirmed** | 38A-01/02 | Highest failure yield; retroactive cross-election FAIL; no recoverability |
| **Gap 6** — Operational Independence Standard | **Confirmed** | 38A-03 (TM-39 F-4); ARB-Review | D43 independence assumption constitutionally unspecified; ADR-2 revision candidate |
| **Gap 7** — GovernanceState Phase Record Governance | **Confirmed** | 38A-04-ARB-Review; ARB-Review | Temporal Root; self-referential; no record governance; ADR-7 extension required |
| **Gap 8** — Post-Finality Constitutional Review | **Candidate — deferred** | OQ-38A05-05; ARB-Review | Cannot be confirmed before OQ-38A05-02 resolution; 38B may not prejudge |

**Open constitutional questions carried from 38A:**

| OQ | Status | 38B Constraint |
|---|---|---|
| OQ-38A05-02 (Finality vs Validity) | Active — highest priority | PROTECTED: 38B may not implicitly resolve in either direction |
| Gap 8 candidate | Deferred | Triggered immediately upon OQ-38A05-02 formal ruling |

---

## Section 4 — 38B Charter

### 4.1 Program Type

**Round 38B is a Constitutional Governance Specification program.**

It is not:
- Security Architecture (no protocol, no cryptographic mechanism design)
- Technical Architecture (no implementation, no system component design)
- Implementation Planning (no code, no deployment)

The confirmed gaps (3/4/5/6/7) are governance gaps. Every one of them identifies a constitutional role, rule, or process that exists or is assumed to exist without specifying how it is governed, challenged, succeeded, or validated. Round 38B produces constitutional governance specifications for those gaps — nothing more.

**One-sentence charter:** Round 38B produces constitutional governance specifications for confirmed gaps 3, 4, 5, 6, and 7, in the order required by the Constitutional Resolution Ranking, subject to OQ-38A05-02 protection and the charter constraints in Section 5.

### 4.2 Constitutional Resolution Order

Per the Constitutional Resolution Ranking established in Round38A-06 (Part C.4), the following order governs 38B specification work:

```text
FIRST:  Gap 4 — Constitutional Interpretation Authority
        Prerequisite for all other gap resolutions to be constitutionally authoritative.
        One constitutional act under current EC to designate.
        Gap 3/4 ordering tension: resolved (no circularity; see 38A-06 C.4).

SECOND: Gap 5 — AC-31 Governance
        Independently resolvable after Gap 4.
        Highest failure yield; retroactive cross-election threat.
        Delay is constitutionally expensive.
        ADR-4 preferred prerequisite: ADR-4 approval should precede Gap 5 finalization.
        Gap 5 work begun before ADR-4 approval remains provisional until ADR-4 is finalized.
        See Section 4.3.

THIRD:  Gap 7 — GovernanceState Phase Record Governance
        Structurally parallel to Gap 5; resolvable after Gap 4.
        Extends ADR-7 (see Section 4.3).

FOURTH: Gap 6 — Operational Independence Standard
        Constitutionally independent; can be addressed in parallel with Gap 7.
        May produce ADR-2 revision (see Section 4.3).

FIFTH:  Gap 3 — EC Amendment Process Governance
        Governs ongoing amendment management.
        Resolvable after Gap 4 designates interpretation authority.
        OBS-38A06-SD1 must be addressed within Gap 3 specification scope.

DEFERRED: Gap 8 — Post-Finality Review
        Not in 38B scope until OQ-38A05-02 is formally ruled by the
        Constitutional Interpretation Authority designated under Gap 4.
        38B must not treat Gap 8 as in scope.
```

**Gap 3 sequencing rationale:** Gap 3 is placed fifth — after Gaps 4, 5, 7, and 6 — because amendment governance disputes require a constitutional interpretation authority to adjudicate. Specifying how the EC may be amended before designating who interprets constitutional questions means any dispute arising from the amendment process specification itself is irresolvable. Gap 3 remains constitutionally important: it governs ongoing EC management and is the primary enabler of OBS-38A06-SD1 (the constitutional self-destruction path). But Gap 3 is not a prerequisite for resolving the other confirmed gaps — those gaps concern constitutional roles and reference standards, not amendment procedures. Once Gap 4 designates the Interpretation Authority, that authority will be available to adjudicate any constitutionally contested questions that arise during Gap 3 specification work.

### 4.3 ADR Interaction Requirements

The following ADR interactions are required as a result of 38A confirmed findings. These are inputs to 38B work — not authorized changes.

**ADR-4 (Audit Scope Authority Structure — SUBMITTED FOR ARB REVIEW):**
ADR-4 defines AuditScopeAuthority and AuditExecutionAuthority — the bodies that evaluate AC-31 in CO-3. Gap 5 specification governs AC-31 itself, which AuditExecutionAuthority's design depends on. **Preferred 38B first-action:** ADR-4 ARB Review should be resolved before Gap 5 specification is finalized. If Gap 5 work begins before ADR-4 approval, all resulting specifications remain provisional until ADR-4 is finalized. This is a preferred prerequisite, not a hard program blocker: the program should not stall on Gap 5 if Gap 4 specification work is complete and ADR-4 remains pending for unrelated procedural reasons.

**ADR-2 (Independence Form per D43 Function — APPROVED WITH REVISIONS):**
ADR-2 selected independence forms for each D43 function (ENROLL/CRITERIA/AUDIT/GOV-AUTH/CERT). These selections assumed operational independence was constitutionally real. Gap 6 confirmation establishes that the constitutional specification governing whether independence is actually maintained was never specified. Gap 6 specification may produce constitutional backing for ADR-2's selections as-is, or may reveal that ADR-2 requires revision. **38B output trigger:** If Gap 6 specification identifies any independence form incompatible with the confirmed constitutional requirement, ADR-2 revision is required.

**ADR-7 (GovernanceState Boundary Architecture — APPROVED WITH MINOR OBSERVATIONS):**
ADR-7 defines what GovernanceState does (records constitutional authority as a state machine aggregate). Gap 7 confirmation establishes that record governance — who may write, what constitutes a valid phase transition, how records are challenged — was not specified. **38B output trigger:** Gap 7 specification extends ADR-7 with record governance provisions. This is an ADR-7 extension requirement, not a full revision.

**ADR-6 (Certification Architecture — APPROVED):**
ADR6-INV-01 (CO-5 validity requirement) and ADR-6 finality principle (TS-1) are in direct constitutional conflict when CO-3's predicate is later proven false. This is OQ-38A05-02. ADR-6 is not to be revised in 38B to resolve this conflict. OQ-38A05-02 is a constitutional interpretation question for the Interpretation Authority designated under Gap 4. **38B constraint:** Any 38B specification touching CO-5 or TS-1 states must flag OQ-38A05-02 as unresolved.

### 4.4 Priority Constitutional Ruling Request

When Gap 4 specification designates a Constitutional Interpretation Authority, OQ-38A05-02 must be formally placed before that authority as its first constitutional ruling request. This is the most consequential unresolved constitutional question in the program and is the highest-priority ruling once an interpretation authority exists.

### 4.5 OBS-38A06-SD1 Specification Requirement

OBS-38A06-SD1 (Constitutional Self-Destruction): the constitutional architecture may remove its own protections through valid EC amendment paths. This observation must be addressed as a **named specification requirement** within both Gap 3 and Gap 4 specifications:

- Gap 3 specification must address whether the EC amendment process requires a minimum constitutional floor that cannot be removed by amendment.
- Gap 4 specification must address whether the Constitutional Interpretation Authority has jurisdiction to prevent constitutionally valid removal of constitutionally necessary provisions.

This is not a general carry-forward note. It is a named deliverable of the Gap 3 and Gap 4 specifications.

### 4.6 OA-01 Integration

OA-01 (Challenger Evidence Access) is the highest-priority assumption for 38B. It must be integrated with Gap 5 specification work: AC-31 governance specification must address challenger access to AC-31 records as part of the governance framework. Resolution of OA-01 without Gap 5 governance context is incomplete; resolution of Gap 5 without OA-01 leaves Named Attestation constitutionally hollow.

### 4.7 Permanent Specification Review Checklist (OBS-38A06-01)

OBS-38A06-01 (Candidate Observation, registered in Round38A-06): *Root-layer objects lacking governance tend to create self-referential validation structures, which subsequently create concentration chains.*

This observation is promoted to a **permanent review checklist item** for all 38B specification work. Every governance specification produced in 38B must answer the following question before it is considered complete:

> **Does this governance model create a new self-referential validation chain?**

Specifically:
- Does the governing body validate its own records?
- Does the governing body determine its own succession without external input?
- Does the governing body adjudicate challenges to its own legitimacy?

If the answer to any of these is yes, the specification must either (a) name the self-referential property explicitly and justify why it is constitutionally acceptable, or (b) introduce an external reference point that breaks the self-referential loop. Governance specifications that silently produce self-referential validation chains repeat the structural pattern that generated Gaps 5 and 7 in the first place.

---

## Section 5 — 38B Constraints

### 5.1 Binding Constitutional Protections

The following are non-negotiable constraints on all 38B work:

**C-1 — OQ-38A05-02 Protection:**
38B must not implicitly resolve OQ-38A05-02 in either direction. No 38B specification may assume that retroactive TS-1 invalidity is either constitutionally possible or constitutionally prohibited. Any specification touching post-finality states, CO-5 validity, or retroactive election review must be explicitly flagged as OQ-38A05-02-dependent and deferred until that question receives a formal constitutional ruling. Violation of this constraint would constitute unauthorized constitutional resolution — the most serious discipline failure in the program.

**C-2 — Gap 8 Exclusion:**
Gap 8 (Post-Finality Constitutional Review) is not in 38B scope. Treating Gap 8 as confirmed, specifying any post-finality review procedure, or designing any mechanism that presupposes Gap 8's confirmation constitutes implicit OQ-38A05-02 resolution. Gap 8 specification is triggered only when OQ-38A05-02 receives a formal ruling.

**C-3 — Pre-Constitutional Boundary:**
TM-07 (Membership Assembly Capture), TM-35 (Legitimacy Narrative Attack), and AA-01 (MA Legitimacy Foundation) are explicitly excluded from 38B technical scope. These are pre-constitutional boundary items requiring governance-level responses outside the DDD Trustworthiness Research Program's mandate. If 38B work encounters these boundaries, it must name the boundary explicitly rather than design across it.

**C-4 — Scope Discipline:**
38B is Constitutional Governance Specification. It is not and may not become:
- Cryptographic mechanism design
- Protocol specification
- System component design
- Implementation planning
- Technical architecture

If a specification requirement appears to demand cryptographic or technical architecture decisions, that requirement must be suspended until the appropriate research program (38C or later) is authorized.

**C-5 — Constitutional Resolution Ordering:**
Gap 4 specification must precede Gap 5 specification. Specifying Gap 5 governance before Gap 4 designates an authoritative interpretation mechanism means any AC-31 governance disputes arising from Gap 5 specification remain irresolvable. The ordering constraint is binding.

**C-6 — ADR-4 Preferred Prerequisite:**
ADR-4 approval should be treated as a preferred prerequisite for finalizing Gap 5 specification. ADR-4's AuditExecutionAuthority design is the primary consumer of AC-31 in CO-3 evaluation, so Gap 5 governance specification without ADR-4's final form creates provisional risk. If Gap 5 specification work begins before ADR-4 is approved, all Gap 5 outputs are explicitly marked provisional and must be reviewed for compatibility once ADR-4 is finalized. This is not a hard program blocker — it is a specification quality constraint.

---

## Section 6 — 38B Required Deliverables and Success Criteria

### 6.1 Required Deliverables

These are the tangible specification outputs 38B must produce. Each deliverable is a named governance specification artifact, not an assessment or a recommendation.

| Deliverable | Content | Gap Target |
|---|---|---|
| D-1 | Constitutional Interpretation Authority: designated with explicit jurisdiction scope, succession mechanism, and challenge mechanism | Gap 4 |
| D-2 | OQ-38A05-02 formally placed before the designated Interpretation Authority as first ruling request | Gap 4 act |
| D-3 | OBS-38A06-SD1 addressed within Gap 4 specification: whether the Interpretation Authority has jurisdiction to prevent constitutionally valid removal of constitutionally necessary provisions | Gap 4 |
| D-4 | AC-31 governance framework: governing body, succession, challenge mechanism, singleton enforcement, OA-01 challenger evidence integration | Gap 5 |
| D-5 | OBS-38A06-SD1 addressed within Gap 3 specification: whether the EC amendment process requires a minimum constitutional floor | Gap 3 |
| D-6 | EC amendment process governance: procedure, authority, minimum constitutional floor determination | Gap 3 |
| D-7 | GovernanceState record governance: write authority, valid phase transition definition, record challenge mechanism; ADR-7 extension produced | Gap 7 |
| D-8 | Operational independence constitutional floor: minimum standard, enforcement mechanism, verification; ADR-2 revision determination recorded | Gap 6 |

### 6.2 Success Criteria

38B is complete when the following conditions are all satisfied:

**SC-A — All deliverables produced:** D-1 through D-8 are all produced and approved.

**SC-B — Ordering constraints satisfied:** D-1 (Gap 4) is produced before D-4 (Gap 5) is finalized. This is binding regardless of ADR-4 status.

**SC-C — ADR-4 provisional resolution:** D-4 is either (a) finalized after ADR-4 approval, or (b) explicitly marked provisional with a documented compatibility review requirement for when ADR-4 is finalized. D-4 must not be presented as final if ADR-4 remains unapproved at the time of D-4 production.

**SC-D — Gap 8 trigger honored:** If OQ-38A05-02 receives a formal constitutional ruling at any point during 38B, Gap 8 status must be evaluated immediately. This is not a deliverable 38B controls — it is a commitment to respond without delay when the trigger condition is met.

**SC-E — OQ-38A05-02 protection maintained throughout:** No deliverable implicitly resolves OQ-38A05-02 in either direction. This criterion is evaluated retrospectively across all eight deliverables before 38B closure.

---

## Section 7 — What 38B Is Not Authorized to Decide

The following questions are explicitly outside 38B's authorized scope:

| Question | Why Out of Scope | Where It Belongs |
|---|---|---|
| OQ-38A05-02 resolution (Finality vs Validity) | Constitutional interpretation question requiring Gap 4 authority | First ruling for Gap 4-designated Interpretation Authority |
| Gap 8 specification | Dependent on OQ-38A05-02 | Triggered post-OQ-38A05-02 ruling |
| AC-31 cryptographic design | Technical architecture; not governance specification | Round 38C or later |
| Voting protocol selection | Technical architecture | Round 38C or later |
| ElectionGuard / Helios or equivalent protocol evaluation | Technical architecture / literature research | Deferred research program |
| MA governance structures | Pre-constitutional boundary | Governance workstream outside DDD program |
| Any cryptographic mechanism | Technical architecture | Post-38B program |

---

## Section 8 — Program State After This Authorization

```text
Round 36A-36E   CLOSED
Round 37        CLOSED (ADR-1/2/3/5/6/7 APPROVED; ADR-4 SUBMITTED — resolves in 38B)
Round 38A       CLOSED (2026-06-17)
Round 38B       AUTHORIZED (2026-06-17) ← this document

38B Type:       Constitutional Governance Specification
38B Priority:   Gap 4 first, Gap 5 second (ADR-4 first-action prerequisite)
OQ-38A05-02:    PROTECTED — highest-priority ruling request for Gap 4 output
Gap 8:          Deferred — triggered on OQ-38A05-02 ruling
OBS-38A06-SD1:  Named requirement in Gap 3 and Gap 4 specifications
ADR-4:          Preferred prerequisite for Gap 5 finalization — provisional risk if Gap 5 begins before ADR-4 approved
ADR-2:          Revision candidate contingent on Gap 6 specification output
ADR-7:          Extension required contingent on Gap 7 specification output
```

---

## Section 9 — 38B-01 Pre-Authorization and First Specification Scope

This section defines the first specification document to be produced under Round 38B: **38B-01 — Constitutional Interpretation Authority Specification.** 38B-01 begins with Gap 4.

### 9.1 Pre-38B-01 Program State Consistency Check (Required)

Before 38B-01 is opened, perform a program-state consistency review and document the findings. The review must verify:

| Check | Required Verification |
|---|---|
| ADR-4 status | Is ADR-4 Approved / Approved with revisions / Still submitted? Document current status. If still submitted, Gap 5 deliverable (D-4) must be marked provisional when produced. |
| Gap register | Gaps 3/4/5/6/7 confirmed; Gap 8 deferred — verify all five statuses are intact in authoritative closure documents |
| OQ-38A05-02 protection | Confirm it remains formally unresolved. Confirm no 38A or 38B-authorization document has implicitly resolved it in either direction. |

This consistency check is not a gate that can halt 38B-01 indefinitely. It is a documentation requirement: the outcomes must be recorded before 38B-01 begins so that later reviewers can verify the program state at the time 38B-01 was opened.

**Program State Reconciliation Rule:** If any discrepancy exists between memory records, ADR records, and authorization documents, the authoritative source is the latest approved ADR or ARB decision document. Memory artifacts (MEMORY.md, ddd_program_state.md) are informational records — they are not authoritative. When in conflict, the ADR or ARB document governs. This rule applies throughout Round 38B and all future rounds.

### 9.2 38B-01 Scope Constraints

38B-01 produces **constitutional governance specification only**. The following are explicitly out of scope:

- DDD design (no bounded contexts, no aggregates, no domain events)
- System components or services
- APIs or interfaces
- Cryptographic mechanisms
- Implementation choices of any kind
- Technical architecture

### 9.3 38B-01 Required Deliverables

| Deliverable | Content |
|---|---|
| A | Constitutional purpose of the Interpretation Authority |
| B | Jurisdiction scope — what constitutional questions fall within its authority |
| C | Authority source — where the Authority's mandate derives constitutionally |
| D | Challengeability requirements — how the Authority's rulings may be challenged |
| E | Succession requirements — how the Authority continues if members are unavailable |
| F | Independence requirements — what structural independence is constitutionally necessary |
| G | Relationship to existing ADRs — specifically ADR-1 through ADR-7 |
| H | OQ-38A05-02 handling process — the formal mechanism by which OQ-38A05-02 is placed before the Authority as its first ruling request |
| I | Alternatives evaluated — per Round 37 ADR discipline: identify alternatives → evaluate → select → record rejected alternatives and rationale |
| J | Selected constitutional model and rationale |

### 9.4 38B-01 Governing Discipline

38B-01 follows Round 37 ADR authoring discipline: evaluate alternatives, select one, record rejected alternatives, record rationale. The document must not present a single option without evaluating alternatives — this is the discipline that produced the authority of ADR-1 through ADR-7.

OBS-38A06-01 checklist (Section 4.7) applies to 38B-01. Before 38B-01 is considered complete, it must answer: does the constitutional model proposed for the Interpretation Authority create a new self-referential validation chain?

---

*Round 38B opened. 38B-01 begins with Gap 4 — because without a designated constitutional interpretation authority, every other specification in 38B produces findings that no one is constitutionally authorized to adjudicate.*
