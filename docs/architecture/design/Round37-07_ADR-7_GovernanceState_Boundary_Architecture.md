# Round 37-07 — ADR-7: GovernanceState Boundary Architecture

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 37 — ADR Authoring
**Document:** ADR-7 of 7 (Final ADR in Round 37 sequence)
**Status:** APPROVED WITH MINOR OBSERVATIONS (2026-06-16)
**Governing Question:** Has the architecture created a constitutional concentration point in ElectionConstitution and CertificationAuthority that constitutes a "Constitution God Aggregate" — and if so, what is the architectural response?

**Predecessors:**
- ADR-1 — Authority Vocabulary and Authority Source Model — APPROVED
  Key inheritance: ElectionConstitution as shared L-1; reversal clause if AC-30 precision fails
- ADR-2 — Independence Form per D43 Function — APPROVED
  Key inheritance: 7 D43 authority forms selected; independence ≠ externality discipline inherited
- ADR-3 — Evidence and Verifier Architecture — APPROVED
  Key inheritance: ADR3-INV-01; OBS-ADR3-01; AC-31
- ADR-4 — Audit Scope Authority Structure — SUBMITTED FOR ARB REVIEW
  Key inheritance: ET-03 condition (ElectionConstitution must name AuditScopeAuthority L-2 body); Tier 1 categories in ElectionConstitution
- ADR-5 — Challenge Architecture — APPROVED (Required Revisions Applied)
  Key inheritance: ChallengeAdjudicationBody (provisional 7th aggregate); Terminal Authority Principle; OBS-ADR5-01/02/03; OQ-37-05-01/02/03
- ADR-6 — Certification Architecture — APPROVED (Required Revisions Applied)
  Key inheritance: CO-2/CO-3/CO-4/CO-5 model; ADR6-INV-01; OBS-ADR6-02 (CertificationAuthority concentration point); ADR6-CONSTRAINT-01; OBS-ADR6-03; OQ-37-06-01/02/03

**Scope:** Conceptual architecture only. ADR-7 addresses constitutional concentration risk, GovernanceState's constitutional profile, and final authority map stabilization. No API design, no service design, no cryptographic selections, no deployment topology.

**Primary Question:** Is ElectionConstitution a constitutionally dangerous concentration point? Has the architecture created a Constitution God Aggregate?

**Required Discipline:** Constitutional concentration risk is the primary question; GovernanceState implementation is secondary. OBS-36D-02-1 governs throughout: authority map ≠ context map. No new bounded contexts, services, APIs, or cryptographic selections. ADR-7 may not rewrite earlier ADR decisions; conflicts must be raised as ADR conflicts.

---

## Part A — Problem Statement

### A.1 The Constitutional Concentration Chain

ADR-6 identified the program's first explicit constitutional dependency graph:

```
ElectionConstitution (CP-1)
        ↓ CO-4 compliance standard
CertificationAuthority (CP-2)
        ↓ CO-5 derivation
Election Validity
```

Two confirmed concentration points (CP-1 and CP-2) are now coupled. CertificationAuthority cannot evaluate CO-4 (Constitutional Compliance) without ElectionConstitution. Therefore:

```
ElectionConstitution failure
    → CO-4 assessment impossible
    → CO-5 (Election Validity) void
    → ADR6-INV-01 violated for every election in the system
```

This is the dominant architectural risk entering ADR-7. It is not a D43 gap (which is a legitimacy gap). It is a constitutional concentration gap (which is a resilience and single-point-of-failure risk).

### A.2 The Architectural Shift

| Phase | Dominant Risk |
|---|---|
| Rounds 36B–36D | Authority legitimacy gap (D43 — five functions with undefined authority) |
| ADR-1 through ADR-5 | Authority legitimacy establishment (L-1 through L-5 per function) |
| ADR-6 and ADR-7 | Constitutional concentration risk (CP-1 and CP-2) |

This shift does not invalidate earlier work. The D43 legitimacy gaps were real and required resolution. But the resolution created concentration — seven authority aggregates each pointing to ElectionConstitution as L-1 source, with ElectionConstitution additionally serving as revocation terminus, challenge terminus, and constitutional compliance standard. Solving D43 has produced CP-1. ADR-7 must determine whether CP-1 is a constitutional pathology (a God Aggregate to be mitigated) or a constitutional necessity (an unavoidable property of any constitutional governance architecture).

### A.3 The Seven Governing Questions for ADR-7

1. Is ElectionConstitution a God Aggregate — or is its concentration intrinsic to constitutional architecture?
2. Which of ElectionConstitution's responsibilities are intrinsic (cannot be delegated without constitutional incoherence) vs. delegatable (can be assigned to a constitutional sub-instrument)?
3. What is GovernanceState's constitutional profile — does it carry, record, or derive constitutional authority?
4. Does ChallengeAdjudicationBody require externality (Option C) or is internal independence (Option B) constitutionally sufficient?
5. Is ChallengeReceptionFunction a separate authority aggregate or a sub-function of ChallengeAdjudicationBody? (OQ-37-05-01)
6. What is the constitutional succession mechanism for R-5 Suspension? (OQ-37-05-02)
7. What is the constitutional response to the concentration chain? Mitigation through distribution, or mitigation through resilience?

---

## Part B — ElectionConstitution Responsibility Inventory

### B.1 Complete Responsibility Map

ElectionConstitution currently carries the following responsibilities, derived from ADR-1 through ADR-6:

| Responsibility | Classification | ADR Source | Intrinsic or Delegatable? |
|---|---|---|---|
| L-1 source for EnrollmentAuthority | Source | ADR-1, ADR-2 | **Intrinsic** — L-1 is definitionally the source document |
| L-1 source for CriteriaAuthority | Source | ADR-1, ADR-2 | **Intrinsic** |
| L-1 source for AuditScopeAuthority | Source | ADR-1, ADR-4 | **Intrinsic** |
| L-1 source for AuditExecutionAuthority | Source | ADR-1, ADR-4 | **Intrinsic** |
| L-1 source for GovernanceAuthority | Source | ADR-1, ADR-2 | **Intrinsic** |
| L-1 source for CertificationAuthority | Source | ADR-1, ADR-2 | **Intrinsic** |
| L-1 source for ChallengeAdjudicationBody | Source | ADR-1, ADR-5 | **Intrinsic** |
| L-4 revocation terminus for all 7 functions | Revocation | ADR-2, ADR-5 Terminal Authority Principle | **Intrinsic** — revocation terminus = who can revoke L-1; if L-1 is EC, revocation authority flows from EC |
| Challenge terminus (post-cycle constitutional review) | Challenge | ADR-5 Terminal Authority Principle | **Intrinsic** — the terminal point of recursion must rest in the L-1 source to avoid infinite regress |
| CO-4 constitutional compliance standard | Compliance | ADR-6 B.3, C.3 | **Intrinsic** — CO-4 is compliance with EC's own rules; must reference EC |
| AuditScopeAuthority L-2 body designation (ET-03 condition) | Governance | ADR-4 ET-03 resolution | **Delegatable** — EC may name the L-2 body by reference, but the designating authority could be a GovernanceConstitution instrument |
| Tier 1 evidence categories (ADR-4 two-tier) | Governance | ADR-4 OQ-ADR4-01 Form B | **Delegatable** — Tier 1 categories are constitutional in nature but could be delegated to a constitutional subsidiary instrument (ElectionConstitutionSchedule or similar) |
| Challenge window duration (ADR6-CONSTRAINT-01) | Governance | ADR-6 F.3 | **Delegatable** — the window must be constitutionally published; but EC could designate a constitutional instrument to set the window rather than encoding it directly |
| Standing class grant framework (OBS-ADR5-03) | Source | ADR-5 C.3 | **Intrinsic** — standing class grants must derive from constitutional authority, not challenged-authority discretion; the framework must rest in EC |
| R-5 Suspension succession pre-designation (OQ-37-05-02) | Governance | ADR-5 E.2, OQ-37-05-02 | **Delegatable** — the succession requirement is constitutional (ADR7-INV-01); the specific successor designations could be in a constitutional subsidiary instrument |
| Challenge window for certification (ADR6-CONSTRAINT-01: known before election) | Governance | ADR-6 F.3 | **Delegatable** — same as challenge window above |

### B.2 Intrinsic vs. Delegatable Assessment

**Intrinsic responsibilities (cannot be delegated without constitutional incoherence):**
1. L-1 source for all 7 authority functions — constitutionally definitional
2. L-4 revocation terminus — flows from being L-1 source
3. Challenge terminus (post-cycle) — terminates the recursion that L-1 creates
4. CO-4 compliance standard — CO-4 is defined as compliance with EC; cannot reference a different document
5. Standing class grant framework — must not derive from challenged-authority discretion (OBS-ADR5-03); must originate in EC

**Delegatable responsibilities (can be assigned to constitutional subsidiary instruments):**
1. AuditScopeAuthority L-2 body designation
2. Tier 1 evidence categories
3. Challenge window duration
4. R-5 Suspension successor designations

### B.3 ElectionConstitution Concentration Assessment

**OBS-ADR7-02:** ElectionConstitution exhibits God Aggregate-like constitutional concentration properties. Whether the DDD term "God Aggregate" is the correct classification remains interpretive — ElectionConstitution is not a mutable consistency boundary in the DDD sense; it is a constitutional root artifact. The concentration discovery is correct; the label is less certain.

The five intrinsic responsibilities are constitutionally necessary concentrations. A constitutional governance architecture cannot function without a single authoritative L-1 source — a distributed L-1 creates competing constitutional claims with no resolution mechanism. The revocation terminus, challenge terminus, CO-4 standard, and standing grant framework all logically flow from being the L-1 source. These cannot be distributed without destroying constitutional coherence.

**However:** The four delegatable responsibilities (L-2 designation, Tier 1 categories, window duration, suspension successors) are governance decisions that happen to be stored in ElectionConstitution, not responsibilities that are constitutionally required to be there. Moving them to constitutionally designated subsidiary instruments reduces ElectionConstitution's operational surface without reducing its constitutional authority.

**OBS-ADR7-SS1 (Source-of-Source — not yet fully established):** Constitutional governance requires a source that grounds ElectionConstitution — an entity that is NOT defined by ElectionConstitution and that is the constitutional precondition for ElectionConstitution's existence. The Membership Assembly (the body that ratifies ElectionConstitution) is the strongest currently identified candidate for this source-of-source role. However, the constitutional legitimacy of the Membership Assembly itself has not been fully established by the program — the question "why is the Membership Assembly itself legitimate?" has not been answered. ADR-7 records the candidate; it does not declare final closure. Round 38+ constitutional governance design must assess whether the source-of-source chain terminates at the Membership Assembly or requires further constitutional grounding.

**Constitutional Subsidiary Instrument Pattern (introduced in ADR-7):**

ElectionConstitution may designate constitutional subsidiary instruments — constitutional instruments with defined scope, limited to governance-execution decisions, and subject to ElectionConstitution's L-4 revocation. Subsidiary instruments are not independent L-1 sources; they execute within the scope granted by ElectionConstitution. This is a constitutional delegation, not a constitutional distribution.

```
ElectionConstitution (CP-1)
    ↓ constitutional delegation
ElectionConstitutionSchedule(s)
    (L-2 designations, Tier 1 categories, window durations, succession designations)
    ↓ governs
Authority Aggregates
```

Subsidiary instruments do not reduce the concentration risk at CP-1 (they are still revocable by and dependent on ElectionConstitution). But they reduce ElectionConstitution's operational complexity — the number of governance decisions that require ElectionConstitution-level amendment to modify. This is a maintainability and governance-agility benefit with no constitutional cost.

---

## Part C — GovernanceState Constitutional Profile

### C.1 The Three-Form Question

GovernanceState may relate to constitutional authority in three forms:
- **Carries:** GovernanceState makes constitutional decisions (it is itself an authority aggregate with a D43 legitimacy chain)
- **Records:** GovernanceState stores what ElectionConstitution and other authority aggregates have established (it is a state machine whose valid states are constitutionally authorized)
- **Derives:** GovernanceState derives its own constitutional authority from ElectionConstitution grants (it holds authority delegated by EC)

### C.2 Evidence from the Program Record

From ADR-2: GovernanceAuthority = Option B (Committee Independence). GovernanceAuthority is a D43 authority aggregate. GovernanceState is distinct from GovernanceAuthority — GovernanceState is the aggregate that holds the current governance state of an election (open, suspended, closed, etc.), while GovernanceAuthority holds the mandate to authorize governance transitions.

From ADR-4: GovernanceState's Tier 1 content dependency on ElectionConstitution (what evidence categories are required). GovernanceState does not generate these categories; it enforces them.

From Round 35D (Context Choreography): GovernanceState publishes authority (publishing current state). GovernanceState → Eligibility: voting window gate. GovernanceState → Vote: CONTRACT UNDISCOVERED (OBS-34C-1). The core pattern: "Publish Authority. Consume Authority. Evaluate On Demand."

From ADR-1 (reversal clause): If ElectionConstitution fails AC-30 precision, per-function L-1 reopens. GovernanceState does not appear in this clause — it is not an L-1 alternative.

From ACQ-13 (36E-01): Governance = state machine layer (valid transitions) + constitutional layer (authorized transitions). This distinction maps directly to GovernanceState (state machine) vs. GovernanceAuthority (constitutional authorization).

### C.3 Selection — GovernanceState Records Constitutional Authority

**Selected: GovernanceState RECORDS constitutional authority.**

GovernanceState is a state machine aggregate whose:
- Valid states are defined by ElectionConstitution (constitutional layer)
- Valid transitions are authorized by GovernanceAuthority (D43 authority aggregate)
- Current state is published for consumption by Eligibility, Vote, Audit, and other aggregates

GovernanceState does NOT make constitutional decisions. GovernanceState does NOT hold a D43 legitimacy chain. GovernanceState does NOT carry independent constitutional authority. Its constitutional character is derived from what ElectionConstitution and GovernanceAuthority have established — it is a faithful recorder and publisher of constitutionally authorized state.

**Critical distinction for the authority map:**
- GovernanceAuthority = authority aggregate (D43 instance, L-1/L-5 chain, Option B independence)
- GovernanceState = state machine aggregate (records and publishes authorized state)

These are constitutionally distinct aggregates with different roles. OBS-34C-1 (GovernanceState → Vote contract undiscovered) remains open but does not change this classification — the contract governs interaction behavior, not constitutional profile.

**D43 applicability to GovernanceState:** The D43 pattern (authority with undefined constitutional grounding) does NOT apply to GovernanceState because GovernanceState does not hold authority. It holds state. Rule 3 (Authority Inflation Prohibited) is not violated by GovernanceState because GovernanceState is not an authority aggregate.

### C.4 GovernanceState and OBS-ADR5-02

OBS-ADR5-02 (ElectionConstitution triple-role, now extended to four roles by ADR-6) raises the question: if EC is challenged, what happens to GovernanceState? Answer: GovernanceState's valid state space is constitutionally defined by EC. If EC is suspended or under constitutional review (post-cycle), GovernanceState's published state retains its last authorized value — it does not collapse, but no new constitutional state transitions can be authorized until the EC review completes. This is a resilience property of the state machine model: GovernanceState holds the last-known-constitutional-state, preventing administrative vacuum during EC review.

**OBS-ADR7-03 (GovernanceState Evidentiary Ambiguity):** GovernanceState does not carry constitutional authority — this classification is confirmed. However, corruption of GovernanceState may create evidentiary ambiguity about the exercise of constitutional authority that is more serious than a pure data integrity problem.

If GovernanceState is corrupted, the following constitutional questions become evidentially ambiguous:
- Which election phase was active at a given time? (Affects whether challenge windows were open)
- Was the certification phase constitutionally authorized at the time certification was issued?
- Was a challenge filed during an active challenge window?
- Was a governance suspension in effect when votes were cast?

These are not merely operational questions. They are constitutional evidence questions — the answers determine whether constitutional acts (challenges, certifications, votes) were constitutionally valid at the time they were performed. GovernanceState's records are the primary evidence for these determinations. Their corruption creates constitutional evidence ambiguity (an evidence problem of the kind identified in 36C TC-1/TC-2), not merely an operational inconvenience.

This observation does not change GovernanceState's classification (it records authority, it does not carry authority). It does establish that GovernanceState's integrity is constitutionally significant for evidentiary purposes, and must be treated as a Presence Stratum consideration (ADR-3) for election integrity assessment.

---

## Part D — ChallengeAdjudicationBody Independence Form

### D.1 The Externality Question

ADR-5 deferred whether ChallengeAdjudicationBody requires Option B (Internal Independent Body) or Option C (External Organization). The R2 discipline established: independence ≠ externality. ADR-7 must resolve this.

### D.2 Comparison with CertificationAuthority

ADR-2 selected Option C (External Organization) for CertificationAuthority based on:
1. Terminal risk: certification is the last gate before election result is accepted
2. AC-27: external challenge reception required
3. TF-36C-05-11: certification abuse amplifies all prior threat classes into a public trustworthiness claim

Does ChallengeAdjudicationBody share these characteristics?

| Factor | CertificationAuthority | ChallengeAdjudicationBody |
|---|---|---|
| Terminal risk | YES — final constitutional gate for election validity | PARTIAL — adjudication is final within cycle, but certification follows adjudication; CA is more terminal |
| Challenge reception externality | YES — AC-27 requires external challenge reception to prevent capture | NO — challenge reception is already separated (ChallengeReceptionFunction); adjudication body itself need not be external for reception independence |
| Amplification risk | YES — certification failure amplifies all prior threat classes | LOWER — adjudication failure can be challenged via constitutional review post-cycle |
| Independence basis | Must be independent of election system AND of audit system | Must be independent of challenged party but need not be independent of the election constitutional framework |

### D.3 The Terminal Authority Principle as a Constraint

ADR-5's Terminal Authority Principle: ChallengeAdjudicationBody decisions are final within the election cycle; post-cycle constitutional review is available. This principle terminates the recursion problem ("who governs the governors?") at ElectionConstitution, not at an external organizational structure.

The termination is constitutional (EC review), not organizational (external body review). Therefore externality is not required to terminate the recursion — the constitutional review process serves this function regardless of whether ChallengeAdjudicationBody is internal or external.

### D.4 D43 Revisit for ChallengeAdjudicationBody

ADR-5 justified ChallengeAdjudicationBody as a distinct authority aggregate using D43 analysis:
- Unique mandate: adjudication across all 6 authority functions
- Unique legitimacy: ElectionConstitution designation
- Unique revocation: constitutional process
- Unique challenge pathway: constitutional review

Does this D43 justification require externality? No — D43 establishes the need for a distinct authority aggregate with its own legitimacy chain. The form of independence (internal or external) is determined by the constitutional risk analysis, not by the D43 threshold.

### D.5 Independence Requirements Established — Form Deferred to Round 38A

**ADR-7 establishes: constitutional independence is required for ChallengeAdjudicationBody.** The program has demonstrated what independence requires; it has NOT yet demonstrated which specific realization form (internal committee vs. external organization) is constitutionally necessary.

Independence conditions that must be satisfied (regardless of Option B or C):
1. Members appointed through a process that does not involve any of the six challenged authority aggregates
2. Legitimacy chain grounded in ElectionConstitution (L-1 source), not in the challenged parties
3. Revocation possible only through constitutional process (L-4 from ElectionConstitution)
4. ChallengeReceptionFunction structured to ensure challenge registration is independent of adjudication outcome

**Option B (Internal Independent Body) and Option C (External Organization) both remain constitutionally viable.** Neither is selected in ADR-7. The selection is deferred to Round 38A Election Threat Model Validation, which must assess whether the governance capture threats (TM-01, TM-04 — see Section L) create a constitutional requirement for externality beyond what internal independence can provide.

**Distinction from CertificationAuthority:** ADR-2 selected Option C for CertificationAuthority on the basis of terminal risk (failure at certification cannot be recovered within the election cycle without R-7/R-8) and TF-36C-05-11 (certification abuse amplifies all prior threat classes). ChallengeAdjudicationBody has a different risk profile — adjudication failure has post-cycle constitutional review recovery. This distinction makes externality constitutionally necessary for CERT but not yet constitutionally demonstrated for ChallengeAdjudicationBody. Round 38A threat analysis will determine whether the difference is constitutionally material.

---

## Part E — ChallengeReceptionFunction Authority Status (OQ-37-05-01 Resolved)

### E.1 The Options

OBS-ADR5-01 established ChallengeReceptionFunction as a CANDIDATE authority. Three constitutional forms:
- **Form A:** Separate authority aggregate with its own D43 legitimacy chain
- **Form B:** Sub-function of ChallengeAdjudicationBody — reception is a constitutional capability of the adjudication body
- **Form C:** Constitutional capability not held by any specific aggregate — a capability pattern enforced across all 6 authority aggregates' reception processes

### E.2 D43 Analysis for Form A (Separate Authority)

Applying Rule 3 (Authority Inflation Prohibited):
- Unique mandate: reception of challenges. But reception is prerequisite to adjudication — it is not constitutionally distinct from adjudication; it is the intake function of adjudication.
- Unique legitimacy chain: would require its own L-1/L-5 chain separate from ChallengeAdjudicationBody's. This doubles the constitutional legitimacy burden for a function that exists solely to serve ChallengeAdjudicationBody.
- Unique revocation: separate revocation creates the risk of a scenario where ChallengeAdjudicationBody is revoked but ChallengeReceptionFunction is not, or vice versa — constitutionally incoherent
- Unique challenge pathway: who challenges the reception function? If challenged through ChallengeAdjudicationBody, it is not independent of it; if challenged through a different pathway, infinite regress is reintroduced

**D43 threshold not met for Form A.** Separate authority aggregate not justified.

### E.3 Selection — Form B: ChallengeReceptionFunction as ChallengeAdjudicationBody Constitutional Capability

**Selected: Form B — ChallengeReceptionFunction is a constitutional capability of ChallengeAdjudicationBody, not a separate authority aggregate.**

ChallengeReceptionFunction's constitutional independence requirements (AC-09 from ADR-5: challenge structurally independent of challenged body) are satisfied by ensuring ChallengeReceptionFunction is operationally separated from ChallengeAdjudicationBody's adjudication process — not by making ChallengeReceptionFunction a separate authority aggregate. Operational separation (intake separated from adjudication) is a constitutional design requirement; authority separation is not required and would introduce constitutional incoherence.

**OQ-37-05-01 RESOLVED:** ChallengeReceptionFunction = constitutional capability of ChallengeAdjudicationBody. Authority aggregate count: 7 (not 8). The provisional 7th aggregate (ChallengeAdjudicationBody) is confirmed.

**ADR7-INV-02 (Anti-Capture Invariant):**

No authority aggregate may:
1. Grant itself standing to challenge its own decisions
2. Expand its own standing class designation
3. Restrict the standing class of parties challenging it
4. Participate in the adjudication of a challenge to its own decisions

This invariant closes a governance-capture pathway: without it, a challenged authority could make itself harder to challenge by restricting standing, or could influence adjudication by expanding its own involvement. Standing classes are specified in ElectionConstitution (OBS-ADR5-03) and assessed by ChallengeReceptionFunction without input from the challenged authority. ADR7-INV-02 is not a new constraint — it is the structural enforcement of OBS-ADR5-03. It is named here to make the anti-capture requirement explicit and binding on all subsequent design.

---

## Part F — R-5 Suspension Succession (OQ-37-05-02 Resolved)

### F.1 The Constitutional Problem

R-5 (Suspend) was established in ADR-5 as a remedy: suspend an authority aggregate's operations pending correction. The succession question: if an authority aggregate is suspended, what constitutional arrangement governs the functions it normally performs during the suspension period?

This is not a procedural question — a suspended authority aggregate with no succession creates a constitutional vacuum: required constitutional functions (e.g., audit scope definition, evidence authenticity verification) have no constitutional home for the suspension duration.

### F.2 Succession Options

**Option A (Vacancy — no succession):** The suspended function is simply unavailable during suspension. All actions depending on it are stayed.

**Option B (Pre-designated constitutional successor):** ElectionConstitution pre-designates a successor authority for each function that may be suspended. The successor operates until the suspended authority is reinstated or revoked.

**Option C (GovernanceAuthority holds the function):** GovernanceAuthority temporarily holds the suspended function's responsibilities.

**Option D (ChallengeAdjudicationBody oversight with functional stay):** ChallengeAdjudicationBody oversees the suspension; the suspended function's decisions are stayed but the function itself is not transferred.

### F.3 Option Analysis

**Option A (Vacancy):** For minor functions (e.g., GovernanceAuthority's authorization of routine administrative transitions), vacancy may be constitutionally acceptable — the election continues under previously authorized state. But for critical functions (AuditScopeAuthority, AuditExecutionAuthority, CertificationAuthority), vacancy creates a constitutional gap that cannot complete the election cycle. Unacceptable for critical functions.

**Option B (Pre-designated successor):** Constitutionally sound. Pre-designation is a constitutional commitment made before suspension occurs — it cannot be manipulated at the time of suspension. The successor derives authority from the same ElectionConstitution grant. Requires ElectionConstitution to name successors in advance.

**Option C (GovernanceAuthority holds):** Constitutionally dangerous. Creates a scenario where GovernanceAuthority can suspend AuditScopeAuthority and then hold audit scope authority itself — a direct self-referential concentration risk. Prohibited by CF-05-08 (Behavioral Integrity ≠ Constitutional Legitimacy) and by AC-01 (no self-verification).

**Option D (Functional stay):** Appropriate for cases where the suspended function's decisions are not immediately needed to continue the election. But does not solve the constitutional gap for critical functions where a decision is actively needed.

### F.4 Selection — Option B with Function-Criticality Tiers

**Selected: Option B (Pre-designated constitutional successor) for critical functions; Option D (Functional stay) for non-critical functions.**

**ADR7-INV-01 (Suspension Succession Invariant):**

ElectionConstitution must pre-designate succession arrangements for each of the seven authority aggregates before any election cycle begins. Suspension succession is a constitutional pre-commitment, not a discretionary decision at the time of suspension.

Function-criticality tiers:
- **Critical (Option B required):** AuditScopeAuthority, AuditExecutionAuthority, CertificationAuthority — election cycle cannot constitutionally complete without these functions
- **Semi-critical (Option B or D):** ChallengeAdjudicationBody, EnrollmentAuthority, CriteriaAuthority — election may proceed with functional stay for a defined period; Option B provides stronger protection
- **Non-critical for immediate completion (Option D acceptable):** GovernanceAuthority — governance authorization can be stayed for a limited period if succession is unavailable

**Option C (GovernanceAuthority holds) is PROHIBITED** for any function by ADR7-INV-01.

**OQ-37-05-02 RESOLVED.**

---

## Part G — OQ-37-06-01: Unified vs. Separate Certification Statements

### G.1 The Question

Must CertificationAuthority issue three separate certification statements (CO-2 attestation, CO-3 attestation, CO-4 attestation) or may it issue a unified certification statement?

### G.2 Analysis

**Three separate statements (fully decomposed):** Maximum constitutional clarity — each certification object has an independent legal/constitutional instrument. Maximum operational complexity — three instruments must be issued, recorded, challenged, and terminated in the challenge window. For every election.

**One undifferentiated statement (fully collapsed):** Constitutionally insufficient — established in ADR-6 B.2. "The election is certified" with no specification of what is being certified is indeterminate.

**Unified statement with named attestations (intermediate model):** One certification instrument containing three separately named and separately evaluated sections:
- Section 1: CO-2 Attestation (Evidence Completeness — independently assessed)
- Section 2: CO-3 Attestation (Evidence Authenticity — independently assessed via AC-31)
- Section 3: CO-4 Attestation (Constitutional Compliance — independently assessed via ElectionConstitution)
- Section 4: CO-5 Derivation (Election Validity — derived from Sections 1/2/3; ADR6-INV-01 satisfied)

### G.3 Selection — Named Attestation Model

**Selected: Unified certification statement with named attestations per certification object.**

A unified certification statement with named, separately-evaluated attestations satisfies ADR6-INV-01 (CO-5 requires CO-2 + CO-3 + CO-4 all satisfied) while avoiding the operational complexity of three independent certification instruments. Each named attestation is individually challengeable (S-1/S-2/S-3 standing applies per attestation, not only per the unified statement).

Constitutional condition: the challenge window (ADR6-CONSTRAINT-01) applies per attestation — a challenge may target CO-2 attestation without necessarily contesting CO-3 or CO-4 attestation. Partial challenge success may invalidate only the challenged attestation and the derived CO-5, leaving non-challenged attestations intact.

**OQ-37-06-01 RESOLVED.**

---

## Part H — Constitutional Concentration Analysis

### H.1 Constitutional Dependency Graph (Required Output)

```
ElectionConstitution (CP-1)
│
├── L-1 Grant → EnrollmentAuthority (Option B)
│
├── L-1 Grant → CriteriaAuthority (Option B)
│
├── L-1 Grant → AuditScopeAuthority (Hybrid — EC Tier 1 + delegated Tier 2)
│       └── Tier 2 specification → AuditExecutionAuthority
│
├── L-1 Grant → AuditExecutionAuthority (Hybrid — independent execution body)
│       └── Completeness + Presence evidence → CertificationAuthority (CO-2 input)
│       └── Process records → CertificationAuthority (CO-1 input)
│
├── L-1 Grant → GovernanceAuthority (Option B)
│       └── Constitutional authorization → GovernanceState (state machine)
│               └── Published state → Eligibility / Vote / Audit
│
├── L-1 Grant → CertificationAuthority (CP-2) (Option C)
│       ├── CO-2 independent: AuditScopeAuthority Tier 2 (direct access)
│       ├── CO-3 independent: AC-31 reference standard (direct access)
│       ├── CO-4 independent: ElectionConstitution (direct access) ←─────────────────┐
│       └── CO-5 derived: ADR6-INV-01 (CO-2 + CO-3 + CO-4) → Election Validity     │
│                                                                                     │
├── L-1 Grant → ChallengeAdjudicationBody (Option B minimum)                        │
│       └── ChallengeReceptionFunction (constitutional capability, not separate)     │
│       └── Adjudication → Remedies R-1 through R-8                                  │
│               └── R-7 (Recertify) → CertificationAuthority                         │
│               └── R-8 (Rerun) → Constitutional consequence                         │
│                                                                                     │
├── L-4 Revocation terminus (all 7 aggregates) ◄──────────────────────────────────── │
├── L-3 Challenge terminus (post-cycle constitutional review) ◄─────────────────────  │
└── CO-4 compliance standard ────────────────────────────────────────────────────────┘

Note: EC reads by CertificationAuthority (CO-4) creates the CP-1 → CP-2 → CO-5 chain
```

### H.2 Constitutional Concentration Failure Analysis (Required Output)

**Scenario 1: ElectionConstitution (CP-1) fails — dissolution, corruption, or constitutional invalidity**

| Affected Component | Effect | Recoverable Within Cycle? |
|---|---|---|
| All 7 authority aggregates | L-1 grants become constitutionally ungrounded; all authority is void | NO |
| GovernanceState | Valid state space loses constitutional definition; state machine loses its constitutional basis | NO |
| CO-4 assessment | CertificationAuthority cannot assess compliance with a dissolved EC | NO |
| CO-5 | ADR6-INV-01: CO-4 void → CO-5 void | NO |
| ChallengeAdjudicationBody | L-1 grant void; adjudication loses constitutional standing | NO |
| Terminal Authority Principle | No constitutional review mechanism survives EC dissolution | NO |

**Result: CP-1 failure = total constitutional collapse.** No recovery within election cycle. Post-cycle recovery requires constitutional reconstitution (a new ElectionConstitution).

**This is not a design error.** Any constitutional governance architecture has this property — there must be a source document from which authority flows, and that document's failure creates total constitutional collapse. The architectural question is not "can we prevent this?" but "can we make CP-1 failure detectable and minimize cascade speed?"

**Scenario 2: CertificationAuthority (CP-2) fails — capture, dissolution, or withdrawal**

| Affected Component | Effect | Recoverable Within Cycle? |
|---|---|---|
| CO-5 (Election Validity) | No valid certification can be issued | NOT DIRECTLY — but see below |
| CO-2/CO-3/CO-4 assessments | These can be performed and documented independently even if no CO-5 certification is issued | YES |
| ChallengeAdjudicationBody | Challenge pathway still functional; CertificationAuthority may be challenged (S-1/S-2/S-3) | YES |
| ADR7-INV-01 (succession) | If EC pre-designated a successor CertificationAuthority, CO-5 certification may be recovered | YES — with succession |

**Result: CP-2 failure is partially recoverable within the election cycle IF ElectionConstitution has pre-designated a successor CertificationAuthority (ADR7-INV-01 — Option B for critical functions, which includes CertificationAuthority).** CO-2, CO-3, CO-4 assessments are not lost; only the CO-5 terminal act is blocked. This asymmetry makes CP-2 less catastrophic than CP-1, though still severe.

**Scenario 3: GovernanceState fails — dissolution or data corruption**

| Affected Component | Effect | Recoverable Within Cycle? |
|---|---|---|
| Eligibility gate | Voting window unknown; eligibility assessment may be incorrect | YES — last-known state recoverable from constitutional authority records |
| Vote aggregate | Voting window gate unavailable | PARTIAL — constitutional records may allow reconstruction |
| Audit | Governance events unavailable for audit; E-4 (procedural records) category affected | PARTIAL |
| Constitutional authority | GovernanceAuthority's authorization is not lost — only GovernanceState's record of it | YES |

**Result: GovernanceState failure is less severe than CP-1 or CP-2 because GovernanceState records authority, not creates it.** GovernanceAuthority's authorization records remain constitutionally valid; GovernanceState's failure is a data integrity problem, not a constitutional authority problem. Recovery requires restoring GovernanceState from constitutional authority records.

### H.3 Concentration Chain Assessment

The CP-1 → CP-2 → CO-5 concentration chain is **a structural property of constitutional governance architecture, not a design pathology.**

Constitutional governance requires:
- A source document (L-1 = CP-1 = ElectionConstitution): unavoidable
- A terminal certification gate (CO-5 = CP-2 = CertificationAuthority): unavoidable — without a terminal gate, election validity is never formally established
- The terminal gate must reference the source document for constitutional compliance (CO-4): unavoidable — the source document defines what compliance means

The chain cannot be eliminated. The architectural response is therefore:
1. **Subsidiarity:** Reduce ElectionConstitution's operational surface through constitutional subsidiary instruments (Part B.3) without reducing its constitutional authority
2. **Pre-commitment:** Require succession pre-designation (ADR7-INV-01) for all critical functions, especially CertificationAuthority
3. **Detectability:** Design the constitutional architecture so that CP-1 and CP-2 failures are immediately visible — not silent or gradual (Rule 7: Auditability First applies to the governance layer itself, not only to election evidence)
4. **Independence preservation:** Maintain CP-2 (CertificationAuthority) as Option C (External Organization) — the strongest independence form available — to reduce the probability of simultaneous CP-1 and CP-2 failure (two concentration points that are both independent are harder to simultaneously compromise)

---

## Part I — Authority Map Stabilization

### I.1 Final Authority Aggregate Inventory

| Aggregate | ADR | Independence Form | D43 Status | L-1 Source |
|---|---|---|---|---|
| EnrollmentAuthority | ADR-2 | Option B (Committee) | Resolved (ADR-2) | ElectionConstitution |
| CriteriaAuthority | ADR-2 | Option B (Committee) | Resolved (ADR-2) | ElectionConstitution |
| AuditScopeAuthority | ADR-4 | Hybrid — EC Tier 1 + delegated Tier 2 | Resolved — ET-03 closed (ADR-4) | ElectionConstitution |
| AuditExecutionAuthority | ADR-4 | Hybrid — independent execution body | Resolved (ADR-4) | ElectionConstitution |
| GovernanceAuthority | ADR-2 | Option B (Committee) | Resolved (ADR-2) | ElectionConstitution |
| CertificationAuthority | ADR-2, ADR-6 | Option C (External Organization) | Resolved (ADR-2, ADR-6) | ElectionConstitution |
| ChallengeAdjudicationBody | ADR-5, ADR-7 | Option B minimum (Option C permissible) | Resolved (ADR-5, ADR-7) | ElectionConstitution |

**Final authority aggregate count: 7** (confirmed in ADR-7; OQ-37-05-01 resolved — ChallengeReceptionFunction is a capability of ChallengeAdjudicationBody, not a separate aggregate).

**GovernanceState** is NOT an authority aggregate. GovernanceState is a state machine aggregate that records constitutionally authorized governance state. It does not appear in the authority map. It appears in the bounded context map.

### I.2 ADR-1 Reversal Clause Assessment

ADR-1 established the reversal clause: if ElectionConstitution fails AC-30 (constitutional precision), per-function L-1 reopens.

ADR-7 assessment of AC-30 risk:
- ElectionConstitution carries the standing class grant framework (OBS-ADR5-03), CO-4 compliance standard, and constitutional subsidiary instrument designations
- These require constitutional precision (AC-30) — especially the standing class grant framework (who can challenge what)
- The reversal clause remains standing: if a future constitutional design phase finds that a single ElectionConstitution cannot express all required L-1 grants with AC-30 precision across all 7 authority functions, per-function L-1 instruments are the fallback
- ADR-7 does not activate the reversal clause; it notes that the Constitutional Subsidiary Instrument Pattern (Part B.3) provides a practical alternative to reversal before reversal becomes necessary

### I.3 OBS-36D-02-1 Compliance

OBS-36D-02-1 (governing): Authority Distribution ≠ Bounded Context Distribution.

Confirmed in ADR-7: the authority map (7 aggregates + ElectionConstitution + GovernanceState) does NOT map 1:1 to bounded contexts. The authority map defines where constitutional authority lives. The bounded context map defines where models, languages, and transaction boundaries live. These are separate architectural artifacts. ADR-7 produces the authority map; bounded context map design belongs to Round 38+.

---

## Part J — Threat-Model Carry-Forward Package (Round 38A Input)

### J.1 Purpose

Round 37 has discovered threats that are architectural and constitutional, not merely technical. Without carrying them forward explicitly, Round 38A may focus only on software and cryptographic attacks, missing the governance-capture and constitutional-concentration threats that the ADR authoring process surfaced. This section creates the formal carry-forward package for Round 38A.

### J.2 Constitutional and Governance Threats (TM-01 through TM-06)

**TM-01 — Constitution Capture**
An actor achieves control of ElectionConstitution's amendment process — through procedurally valid but substantively captured ratification — and amends EC to narrow challenge pathways, restrict standing classes, expand certification authority's mandate, or otherwise entrench the actor's position.

Risk: CP-1 failure without technical compromise. EC is constitutionally valid in form but constitutionally captured in substance.
Mitigation target: Source-of-source independence (OBS-ADR7-SS1); amendment process protection (outside EC's self-definition).

**TM-02 — Certification Capture**
CertificationAuthority (CP-2) is captured — through appointment process manipulation, financial dependency, or external pressure — and issues certifications for elections that fail CO-2, CO-3, or CO-4 requirements.

Risk: ADR6-INV-01 cannot be enforced if the certifying body is captured. CO-5 (Election Validity) becomes structurally false.
Mitigation target: CertificationAuthority independence (Option C — External Organization); L-4 revocation from EC; challenge pathway (S-1/S-2/S-3 standing to challenge CertificationAuthority decisions).

**TM-03 — GovernanceState Corruption**
GovernanceState records are corrupted — through software defect, administrative manipulation, or targeted attack — creating evidentiary ambiguity about which election phase was active, whether challenge windows were open, and whether certification was authorized.

Risk: OBS-ADR7-03 — corruption is not merely operational; it creates constitutional evidence ambiguity affecting challenge validity, certification timing, and voting window authorization.
Mitigation target: GovernanceState integrity as a Presence Stratum consideration (ADR-3); independent audit of GovernanceState transitions (IR-H principle applied to state machine records).

**TM-04 — Challenge Adjudication Capture**
ChallengeAdjudicationBody is captured — through appointment manipulation, proximity pressure, or procedural manipulation — and systematically dismisses valid challenges (R-1 Dismiss) or imposes disproportionate remedies to suppress legitimate challengers.

Risk: Challenge architecture (ADR-5) becomes constitutionally void without independent adjudication. All authority aggregates become effectively unchallenged.
Mitigation target: ChallengeAdjudicationBody independence form (Option B vs. Option C — deferred to Round 38A). ADR7-INV-02 anti-capture invariant.

**TM-05 — Standing Manipulation**
A challenged authority restricts standing classes for its own challengers — either through ElectionConstitution amendment (TM-01 combined) or through ChallengeReceptionFunction manipulation — preventing valid challenges from being registered.

Risk: Challenge architecture fails at the reception layer before adjudication is reached. AC-09 (structurally independent challenge reception) is circumvented.
Mitigation target: ADR7-INV-02 (no authority may restrict its own challengers' standing); ChallengeReceptionFunction operational independence from challenged authority.

**TM-06 — Concentration Chain Capture**
The constitutional concentration chain (ElectionConstitution → CertificationAuthority → CO-5) is captured as a coordinated attack: ElectionConstitution is amended to weaken certification requirements (TM-01) AND CertificationAuthority appointment is captured (TM-02) simultaneously, making the terminal constitutional gate validate fraudulent elections.

Risk: The most severe threat in the program. A single coordinated actor compromising both CP-1 and CP-2 simultaneously eliminates both the constitutional standard AND the constitutional enforcement. No challenge pathway can succeed because EC defines the constitutional compliance standard and the certifier applies it.
Mitigation target: Independence of CP-1 and CP-2 amendment/appointment processes from each other; source-of-source protection (OBS-ADR7-SS1); post-cycle constitutional review accessibility.

### J.3 Threat Scope for Round 38A

Round 38A threat model validation must address four threat categories, not only technical threats:

| Category | Examples |
|---|---|
| Technical threats | Malicious client, compromised device, compromised administrator, nation-state adversary, denial of service |
| Constitutional threats | TM-01 (Constitution Capture), TM-06 (Concentration Chain Capture) |
| Governance capture threats | TM-02 (Certification Capture), TM-04 (Challenge Adjudication Capture) |
| Evidence manipulation threats | TM-03 (GovernanceState Corruption), TM-05 (Standing Manipulation) |

---

## Part K — Open Questions for Round 38A

### K.1 New Open Questions from ADR-7

**OQ-37-07-01: Constitutional Subsidiary Instrument Design**
ADR-7 introduces the Constitutional Subsidiary Instrument Pattern as a mitigation for ElectionConstitution operational complexity. Round 38+ must design: what are the constitutional constraints on subsidiary instruments? Who designates them? What is their legitimacy chain? Can they be independently challenged?

**OQ-37-07-02: GovernanceState → Vote Contract (OBS-34C-1)**
ADR-7 confirms GovernanceState as a state machine aggregate. The contract between GovernanceState and the Vote aggregate (OBS-34C-1) remains undiscovered. Round 38+ must specify what GovernanceState must publish and what Vote must consume for the interaction to be constitutionally grounded.

**OQ-37-07-03: Detectability Requirements for CP-1 and CP-2 Failure**
The concentration analysis (H.3) calls for designing the constitutional architecture so that CP-1 and CP-2 failures are immediately visible. What does "immediately visible" mean constitutionally? Who observes? Through what mechanism? This is a Round 38A (Election Threat Model) question.

**OQ-37-07-04: Tally-Level CO-6 Certification Object (OBS-ADR6-03)**
OBS-ADR6-03 deferred whether a CO-6 tally-level certification object is constitutionally required. Round 38+ cryptographic and tally-level design must assess this once aggregation/decryption/mixing/proof contexts are constitutionally defined.

### K.2 Resolved Open Questions

| OQ | Resolution | ADR |
|---|---|---|
| OQ-37-05-01 (ChallengeReceptionFunction status) | Form B: constitutional capability of ChallengeAdjudicationBody | ADR-7 E.3 |
| OQ-37-05-02 (R-5 Suspension succession) | Option B pre-designated for critical functions; ADR7-INV-01 | ADR-7 F.4 |
| OQ-37-06-01 (Unified vs. separate CO-2/CO-3/CO-4 statements) | Named attestation model: unified statement with separately-evaluated named sections | ADR-7 G.3 |
| OQ-37-04-01 (Tier 2 scope publication integrity) | Recoverability + authenticity-verifiability; three-stratum = Round 38+ | ADR-6 H.4 |
| ET-03 (AuditScopeAuthority D43 status) | Not a D43 instance; L-1/L-5 groundable in ElectionConstitution | ADR-4 |

---

## Section — ARB Decision Block

**[APPROVED — Required Revisions Applied (2026-06-16)]**

**Combined verdict:** Claude ADR-7 used as base document. 5 critical findings imported from DeepSeek ADR-7 review. Claude ADR-7 is the stronger architectural document; DeepSeek review provided stronger risk discovery. Combined version is the final ADR-7.

### Revisions Applied

| Revision | Content | Location |
|---|---|---|
| R1 (OBS-ADR7-02) | God Aggregate label softened — ElectionConstitution exhibits concentration PROPERTIES; DDD label interpretive | B.3 |
| R2 (OBS-ADR7-SS1) | Source-of-source weakened — Membership Assembly = strongest CANDIDATE; not final closure | B.3 |
| R3 (OBS-ADR7-03) | GovernanceState evidentiary ambiguity — corruption creates constitutional evidence ambiguity, not only operational problem | C.4 |
| R4 | ChallengeAdjudicationBody form deferred — independence required, Option B and C both viable; Round 38A selects | D.5 |
| R5 (ADR7-INV-02) | Anti-capture invariant — no authority may self-grant standing, expand own standing, or restrict challengers' standing | E.3 |
| R6 (TM-01 through TM-06) | Threat-model carry-forward package — 6 constitutional/governance threats explicitly named for Round 38A | Part J |

### Decisions Made in This ADR

1. **OBS-ADR7-02 (ElectionConstitution Concentration Properties):** ElectionConstitution exhibits God Aggregate-like constitutional concentration properties. The concentration discovery is correct and architecturally significant. Whether the DDD "God Aggregate" label is precisely applicable to a constitutional root artifact remains interpretive. The five intrinsic responsibilities (L-1 source, L-4 revocation, challenge terminus, CO-4 standard, standing class grants) are constitutionally necessary concentrations; the four delegatable responsibilities can move to constitutional subsidiary instruments.

2. **Constitutional Subsidiary Instrument Pattern INTRODUCED:** ElectionConstitution may designate constitutional subsidiary instruments — instruments with limited scope, subject to ElectionConstitution revocation, not independently grounded L-1 sources. Carries governance-execution decisions without reducing constitutional authority.

3. **OBS-ADR7-SS1 (Source-of-Source CANDIDATE — not settled):** The Membership Assembly is the strongest currently identified candidate for source-of-source (the constitutional grounding for ElectionConstitution itself). The constitutional legitimacy of the Membership Assembly is not yet fully established. Round 38+ must assess whether the source-of-source chain terminates at the Membership Assembly or requires further constitutional grounding.

4. **GovernanceState → Records constitutional authority.** State machine aggregate, NOT authority aggregate. D43 does not apply. Appears in context map, not authority map.

5. **OBS-ADR7-03 (GovernanceState Evidentiary Ambiguity):** GovernanceState does not carry constitutional authority. However, corruption of GovernanceState creates constitutional evidence ambiguity about the exercise of constitutional authority — which phase was active, whether challenge windows were open, whether certification was authorized. GovernanceState integrity is constitutionally significant for evidentiary purposes (Presence Stratum consideration).

6. **GovernanceState ≠ GovernanceAuthority (confirmed).** Separate aggregates with separate constitutional profiles.

7. **ChallengeAdjudicationBody — independence required; form deferred.** Constitutional independence conditions established. Option B (Internal Independent Body) and Option C (External Organization) both remain constitutionally viable. Form selection deferred to Round 38A based on TM-04/TM-06 threat validation. Distinction from CertificationAuthority documented.

8. **ChallengeReceptionFunction → constitutional capability of ChallengeAdjudicationBody (OQ-37-05-01 RESOLVED).** D43 threshold not met for separate aggregate.

7. **ADR7-INV-01 (Suspension Succession Invariant):** ElectionConstitution must pre-designate succession for each authority aggregate before election cycle begins. Critical functions (AuditScopeAuthority, AuditExecutionAuthority, CertificationAuthority) require Option B successor designation. Option C (GovernanceAuthority holds) is PROHIBITED for all functions. OQ-37-05-02 RESOLVED.

8. **Named Attestation Model selected for CO-2/CO-3/CO-4 certification (OQ-37-06-01 RESOLVED).** Unified certification statement with separately-named, separately-evaluated, and individually-challengeable attestations per CO object.

9. **Constitutional Concentration Chain confirmed as structural property, not pathology.** CP-1 → CP-2 → CO-5 chain is unavoidable. Architectural response: subsidiarity (reduce EC operational surface), pre-commitment (ADR7-INV-01), detectability (Round 38A), independence preservation (CP-2 remains Option C).

10. **CP-1 failure = total constitutional collapse (no within-cycle recovery).** CP-2 failure = partial recovery possible if ADR7-INV-01 succession is in place. GovernanceState failure = data integrity problem, not constitutional authority problem; recoverable.

11. **Final authority aggregate count: 7 (confirmed).** GovernanceState is not in the authority count.

12. **ADR-1 Reversal Clause remains standing.** Constitutional Subsidiary Instrument Pattern is available as a mitigation before reversal becomes necessary.

13. **OBS-36D-02-1 compliance confirmed.** Authority map (7 aggregates + ElectionConstitution + GovernanceState structural notes) does NOT map to bounded contexts. Bounded context map = Round 38+.

### Open Questions Carried to Round 38A

| OQ | Nature | Round |
|---|---|---|
| OQ-37-07-01 (Subsidiary instrument design) | Constitutional governance design | Round 38+ |
| OQ-37-07-02 (GovernanceState → Vote contract) | OBS-34C-1 unresolved interaction | Round 38+ |
| OQ-37-07-03 (CP-1/CP-2 failure detectability) | Election Threat Model | Round 38A |
| OQ-37-07-04 (Tally-level CO-6) | Round 38+ cryptographic design | Round 38+ |
| OQ-37-06-02 (Challenge window duration) | Governance design | Round 38+ |
| OQ-37-06-03 (CO-2 scope record obligation) | Certification design | Round 38+ |
| OBS-34C-1 (GovernanceState → Vote contract) | Domain interaction contract | Round 38+ |

### Round 37 Closure Statement

With ADR-7 approved, Round 37 (ADR Authoring) is complete. The following constitutional architecture is established:

**Seven Authority Aggregates:**
EnrollmentAuthority / CriteriaAuthority / AuditScopeAuthority / AuditExecutionAuthority / GovernanceAuthority / CertificationAuthority / ChallengeAdjudicationBody

**One State Machine Aggregate:**
GovernanceState (records and publishes constitutionally authorized state)

**One Constitutional Source Document:**
ElectionConstitution (CP-1 — intrinsic L-1 for all 7 authority aggregates)

**Two Confirmed Concentration Points:**
CP-1 (ElectionConstitution) — total collapse if failed
CP-2 (CertificationAuthority) — partial recovery possible with ADR7-INV-01 succession

**Architectural Center of Gravity:**
Constitutional concentration risk (not D43 legitimacy gap) — the primary risk for Round 38A validation

**Required Before Implementation Begins:**
Round 38A — Election Threat Model Validation — mandatory per ADR Architecture Discipline Rules (adr_architecture_discipline_rules.md). The constitutional architecture is strong in authority, legitimacy, and governance. It has NOT yet been stress-tested against voting-system-specific threats: coercion, vote buying, receipt construction, ballot stuffing, malicious client, compromised device, compromised administrator, nation-state adversary, denial of audit, denial of certification. These must be validated before Round 38+ design begins.

### Deferred to Round 38+

| Item | Round |
|---|---|
| Bounded context map | Round 38+ |
| Context mapping and strategic DDD | Round 38+ |
| Event Storming | Round 38+ |
| Aggregate design detail (beyond constitutional profiles) | Round 38+ |
| API design | Round 38+ |
| Database design | Round 38+ |
| Cryptographic architecture (tally, mixing, decryption) | Round 38+ |
| Microservices and deployment topology | Round 38+ |
| Constitutional Subsidiary Instrument design | Round 38+ |
| Tally-level CO-6 certification object | Round 38+ |

---

*Round 37-07 — ADR-7: GovernanceState Boundary Architecture — APPROVED WITH MINOR OBSERVATIONS (2026-06-16)*
*Research Program: NRNA DDD Trustworthiness*
*Document: Round37-07_ADR-7_GovernanceState_Boundary_Architecture.md*
*Base: Claude ADR-7 + 5 critical findings from DeepSeek ADR-7 review*
*Predecessors: ADR-1/2/3 APPROVED; ADR-4 SUBMITTED; ADR-5/6 APPROVED (Required Revisions Applied)*
*Round 37 Status: COMPLETE*
*Next Phase: Round 38A — Election Threat Model Validation (mandatory; TM-01 through TM-06 pre-loaded)*
