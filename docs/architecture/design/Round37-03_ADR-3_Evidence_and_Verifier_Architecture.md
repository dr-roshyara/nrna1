# Round 37-03 — ADR-3: Evidence and Verifier Architecture

**Program:** NRNA DDD Trustworthiness Research Program  
**Round:** 37 — ADR Authoring  
**Document:** ADR-3 of 7  
**Status:** APPROVED (2026-06-15)  
**Governing Question:** What constitutes constitutionally sufficient evidence, and who may verify it?

**Predecessors:**
- ADR-1 — Authority Vocabulary and Authority Source Model — APPROVED
- ADR-2 — Independence Form per D43 Function — APPROVED
- 36E-04 — Architecture Option Evaluation (EH-01, CPR-05) — APPROVED
- 36E-05 — Architecture Synthesis — APPROVED

**Binding Inputs from ADR-2:**
- AUDIT: Option D (Hybrid) selected — constitutional scope via ElectionConstitution + independent execution body with IR-H-grade independence
- AuditScopeAuthority and AuditExecutionAuthority: CANDIDATE aggregates pending ADR-4 determination
- AuditExecutionAuthority inherits evidence access pathway requirement (AC-15)
- ElectionConstitution as audit scope source: ADR-2 established this as the constitutional scope mandate (link 2 of the Completeness Stratum dependency chain — see Part E)

**Scope:** Conceptual architecture only. No evidence storage technology specification. No cryptographic protocol selection. No database schema. No deployment model. Evidence architecture = what constitutional requirements evidence must satisfy and what constitutional properties verifiers must hold — not how those properties are technically realized.

---

## Part A — Problem Statement

### A.1 The Core Question

ADR-2 established that AUDIT requires two constitutionally distinct sub-functions: constitutional scope definition (what must be present) and independent execution (verifying what is present against the defined scope). ADR-3 answers the evidence layer of that separation: what must election evidence be, and what qualifies as constitutionally legitimate verification of it?

These are constitutional questions with direct domain model consequences. The domain model currently contains evidence-producing elements (VoteRecorded events, DataChecksum VO-2, ReceiptHash VO-3, Audit Context observation). ADR-3 determines what constitutional requirements those elements must satisfy, and what new constitutional specifications are required that the existing elements do not yet satisfy.

### A.2 The Governing Warning

The most important discipline for ADR-3:

```
Evidence presence ≠ evidence legitimacy.
```

CF-05-08 (CANDIDATE PROGRAM-LEVEL finding from 36D-05): Behavioral Integrity ≠ Constitutional Legitimacy. This extends directly to evidence: the behavioral fact that evidence exists and has not been visibly altered does not constitute constitutional legitimacy for that evidence. A DataChecksum that matches the evidence it was generated from demonstrates behavioral consistency — not constitutional authenticity. The constitutional question is whether the reference standard used for authenticity verification is itself constitutionally independent of the system that produced the evidence.

### A.3 What ADR-3 Produces

For the evidence and verifier domain:

1. An EH-01 decision: is verifier independence a Supported Hypothesis or an Architectural Constraint? (AC-31 elevation decision)
2. A CPR-05 selection: what evidence integrity architecture satisfies the constitutional requirements?
3. A three-stratum evidence model: the constitutional decomposition of evidence into Completeness, Presence, and Authenticity strata
4. A self-authentication prohibition: the combined constitutional constraint from AC-02 + AC-31
5. VO-2 (DataChecksum) and VO-3 (ReceiptHash) constitutional specification — what roles these existing value objects must satisfy
6. Completeness Stratum dependency chain: what ADR-3, ADR-2, and ADR-4 each establish
7. Cross-concern analysis: CT-1 tension (EC-01 × CPR-05) and VO-1 compatibility
8. Consequences for ADR-4 through ADR-7

### A.4 Governing Constraints

From 36E-01:

| ID | Constraint | ADR-3 Relevance |
|---|---|---|
| AC-02 | No authority function may be architecturally self-verifying | Evidence authenticity cannot be verified using only system-produced reference standards |
| AC-14 | Audit scope must originate outside the election system's architectural boundary | Completeness Stratum: what records must exist must be defined externally |
| AC-15 | External access pathway to election evidence required | AuditExecutionAuthority must have constitutionally grounded evidence access |
| AC-17 | Mechanism for externally defining the expected evidence set is constitutionally required | Completeness Stratum closure mechanism |
| AC-24 | Audit must include external scope definition, external access pathway, IR-H structure | All three strata have constitutional grounding requirements |
| AC-29 | Presence verification ≠ authenticity verification — these are architecturally distinct concerns | Foundational for the three-stratum model |

Additional finding constraints:

| Source | Finding | ADR-3 Relevance |
|---|---|---|
| TC1-DI-01 | Evidence absence ambiguity: absence of evidence ≠ evidence of absence | Completeness Stratum: absence of expected evidence may indicate suppression or may have never existed |
| TC2-DI-01 | Presence ≠ truth: presence of evidence is not evidence of truth | Authenticity Stratum: present evidence may be fabricated or altered |
| CF-05-08 | Behavioral Integrity ≠ Constitutional Legitimacy | Evidence presence cannot substitute for constitutional grounding of the reference standard |
| Gap A-3 | Expected evidence set is undefined | Completeness Stratum requires closure through external constitutional source |
| EH-01 | Supported Hypothesis: verifier independence may require independent reference standard | ADR-3 determines whether to elevate to AC-31 |

### A.5 The EH-01 Decision Threshold

EH-01 was promoted to Supported Hypothesis in 36E-03 based on the DataChecksum circularity case: the current model's reference standard for evidence authenticity is held by the same system that produces the evidence. EH-01's two claims:

- **Derived claim** (established): an independent verifier is required — an external party constitutionally designated to verify election evidence
- **Hypothesized claim** (pending): an independent reference standard is required — the reference against which verification is performed must itself be constitutionally independent of the system that produced the evidence

ADR-3 determines whether the hypothesized claim is elevated to an architectural constraint.

**Elevation threshold:** Elevating a Supported Hypothesis to an Architectural Constraint requires constitutional evidence that the constraint's absence creates a structural constitutional defect — not merely a constitutional weakness. The test: can the absence of an independent reference standard produce a constitutional outcome equivalent to self-verification, in a way that structural design cannot prevent?

### A.6 Options Under Evaluation

**EH-01 options (from 36E-04, Section 2):**
- Option A — Independent Verifier Only: external party verifies against system-produced reference standard
- Option B — Independent Verifier + Independent Reference Standard: both the verifying party and the reference are constitutionally independent of the election system
- Option C — Cryptographic Commitment Mechanism: the system commits to evidence through a cryptographic scheme published externally; any party can verify against the published commitment

**CPR-05 options (from 36E-04, Section 8):**
- Option A — Layered Evidence Pathways: two distinct pathways separate presence and authenticity concerns
- Option B — External Evidence Repository: evidence written simultaneously to internal system and constitutionally independent external repository
- Option C — Cryptographic Evidence Accumulation: evidence accumulated in a cryptographic structure with externally published commitment
- Option D — Stratified Evidence Architecture: three distinct strata (Completeness, Presence, Authenticity) with different access requirements and constitutional grounding

---

## Part B — Constitutional Evidence Inherited

### B.1 Evidence Finding Inventory

**From 36C-02 (Evidence Suppression Threat Model):**
- TC1-DI-01 (CANDIDATE PROGRAM-LEVEL): Evidence Absence Ambiguity — "absence of evidence ≠ evidence of absence." States 1 (Never Existed) and 2 (Disappeared) are constitutionally indistinguishable without an expected evidence set. This makes Gap A-3 closure constitutionally necessary, not merely useful.
- TC1-NCQ-01: Expected evidence set undefined — who is responsible for defining it?
- TC1-NCQ-02: Completeness responsibility unassigned.

**From 36C-03 (Evidence Fabrication Threat Model):**
- TC2-DI-01 (CANDIDATE PROGRAM-LEVEL): Presence ≠ Truth — "presence of evidence is not evidence of truth." A present DataChecksum demonstrating internal consistency does not prove the evidence records the correct ballot content.
- TF-36C-COMB-01 (PROGRAM-LEVEL): The evidence record may be smaller than reality (TC-1, suppression) AND larger or wrong (TC-2, fabrication) simultaneously. Passive observation of an evidence record resolves neither.
- OBS-36C-03-G: Evidence Authenticity ≠ Evidence Completeness. These are constitutionally independent dimensions. Must not be merged.

**From 36D-04/05:**
- CF-05-08: Behavioral Integrity ≠ Constitutional Legitimacy. Evidence behavioral properties (it exists, it matches its own checksums) cannot substitute for constitutional grounding of the verification reference.

**From 36E-02/03:**
- AC-29: Presence verification ≠ authenticity verification — architecturally distinct concerns.
- Gap A-3: Completeness gap — the expected evidence set is undefined. This is both an auditability gap and a legitimacy gap (36D-04-CF-04-03).
- EH-01: DataChecksum circularity — reference standard is system-produced and system-held.

### B.2 Existing Model Elements

**VO-2 (DataChecksum):**
- Generated by the Vote aggregate at vote recording time
- Represents a hash/integrity value for the vote record
- Current constitutional status: system-produced AND system-held. Both the evidence and the reference standard are within the same system boundary.
- AC-02 implication: the Vote aggregate cannot constitutionally verify its own DataChecksum without an external anchor — this IS the DataChecksum circularity that drove EH-01.

**VO-3 (ReceiptHash):**
- Generated by the Vote aggregate at vote recording time
- Delivered to the voter as a receipt
- Critical constitutional distinction from VO-2: the voter holds VO-3 independently of the election system. Once delivered, the voter's copy cannot be unilaterally modified by the election system.
- Constitutional significance: VO-3 delivered to the voter is the strongest existing candidate for an independent reference anchor — it represents a reference standard held outside the election system's control.

**Audit Context:**
- Observes VoteRecorded events (fire-and-forget pattern)
- Models evidence presence (what events have been observed)
- Does NOT model evidence completeness (what events MUST exist — Gap A-3) or evidence authenticity (whether observed events are unmodified)

**AuditExecutionAuthority (from ADR-2 — CANDIDATE):**
- Holds mandate to execute audit of election evidence
- IR-H-grade independence from the audited subject required (AC-24)
- Requires evidence access pathway (AC-15) — not yet specified

### B.3 ADR-2 AUDIT Inheritance

ADR-2's AUDIT Option D selection established:
1. **Constitutional scope mandate:** ElectionConstitution defines what must be present in a complete audit (Gap A-3 closure candidate — constitutional level)
2. **Execution independence:** AuditExecutionAuthority holds mandate to verify actual evidence against the constitutional scope definition
3. **IR-H requirement:** audit execution body must be constitutionally independent of the election system as a whole, not only of the operators

These three inherited decisions directly constrain ADR-3:
- The Completeness Stratum's source is pre-determined: ElectionConstitution (ADR-2 established this)
- The Presence Stratum's access pathway must be designed for AuditExecutionAuthority (AC-15)
- The Authenticity Stratum must satisfy IR-H — an authentication mechanism that routes through the election system cannot satisfy IR-H

---

## Part C — EH-01: Verifier Independence Decision

### C.1 The DataChecksum Circularity — Constitutional Analysis

The DataChecksum (VO-2) circularity is not a software design problem — it is a constitutional structure problem. The circularity has the following constitutional structure:

```
Election System produces evidence record (VoteRecorded event)
Election System generates DataChecksum (VO-2) for that record
Election System stores both record and DataChecksum

External verifier accesses record and DataChecksum
External verifier verifies record integrity against DataChecksum

Result: verification is constitutionally independent (external verifier)
but the REFERENCE STANDARD (DataChecksum) is system-produced and system-held
```

The question for AC-02 compliance: does this structure constitute self-verification?

**AC-02 analysis of the circularity:**

If an adversary controls the election system, they can tamper with both the evidence record AND the DataChecksum simultaneously. The external verifier — checking the tampered record against the tampered DataChecksum — would observe internal consistency and report no anomaly. The verification would be structurally complete, constitutionally worthless.

This is the structural form of self-verification that AC-02 prohibits: the system being verified controls the reference against which its own output is verified. The external verifier provides procedural independence but not constitutional independence — the reference standard is still within the system's authority boundary.

**TC2-DI-01 extension:**

TC2-DI-01 establishes that evidence presence ≠ truth. The DataChecksum circularity extends this: even with external verification, if the reference is system-controlled, evidence authenticity ≠ truth. The external verifier confirms behavioral consistency of a potentially fabricated reference against fabricated evidence.

**Conclusion:** The absence of an independent reference standard creates a constitutional outcome equivalent to self-verification. The structural design — external verifier, access pathway, DataChecksum generation — cannot prevent this outcome if the reference standard is system-controlled. The structural defect cannot be engineered away within the system boundary; it requires that the reference standard originate outside the system.

### C.2 Option Analysis — EH-01

#### Option A (Independent Verifier Only)

**Constitutional assessment:**

Option A provides procedural independence (external party performs verification) but not constitutional reference independence (reference standard is still system-produced). Against the AC-02 structural analysis in C.1: Option A does not eliminate the self-verification defect. A system-wide adversary can compromise both evidence and reference; the external verifier will report compliance.

TC2-DI-01 implication: Option A provides a trusted verifier who confirms behavioral consistency of potentially false evidence against a potentially false reference. This is constitutionally insufficient for audit-grade verification.

EH-01 derived claim: satisfied (external verifier exists).
EH-01 hypothesized claim: not satisfied (reference standard remains system-controlled).

**Verdict:** Does not meet the AC-02 constitutional standard for audit-grade evidence authenticity verification.

#### Option B (Independent Verifier + Independent Reference Standard)

**Constitutional assessment:**

Option B breaks the AC-02 circularity structurally: the reference standard is constitutionally independent of the system that produced the evidence. Simultaneous tampering with evidence AND independent reference requires compromising two constitutionally separate systems — a higher constitutional bar that is definitionally beyond the election system's unilateral control.

TC2-DI-01 implication: even if evidence is fabricated, the fabrication cannot extend to a reference standard that the system does not control.

EH-01 derived claim: satisfied.
EH-01 hypothesized claim: satisfied.

**Verdict:** Satisfies AC-02 for audit-grade evidence authenticity verification.

#### Option C (Cryptographic Commitment)

**Constitutional assessment:**

Option C is a specific implementation approach for achieving Option B's constitutional property: by publishing a commitment externally before the election closes, the commitment becomes the independent reference standard. The commitment is externally anchored; the election system cannot retroactively modify it.

EH-01 hypothesized claim: conditionally satisfied — if the commitment is published to a constitutionally independent external holder, the reference standard is independent.

Key qualification: if the commitment is published by the election system to its own infrastructure, Option C degenerates to Option A. Constitutional independence of the commitment publication holder is the load-bearing requirement — the same constitutional grounding challenge as Option B's external repository.

EC-01 × CPR-05 tension (CT-1): cryptographic commitment schemes may enable individual voters to verify their vote against the commitment — which may enable receipt construction. This tension belongs primarily to ADR-5 (Challenge Architecture) when individual voter verification is the subject. For audit-grade verification (the scope of AC-31), the CT-1 tension is not triggered — auditors are not voters; receipt-freeness is a voter-facing concern.

**Verdict:** Constitutionally valid if commitment publication holder is independently constitutionally grounded. Constitutionally equivalent to Option B in structure. Does not address Gap A-3 (completeness) independently. VO-1 compatibility requires assessment at implementation level (Round 38+).

### C.3 Selection — EH-01 Elevated to AC-31

**EH-01 is elevated to Architectural Constraint AC-31:**

> **AC-31: Constitutional (audit-grade) evidence authenticity verification requires an independent reference standard. The reference standard used to verify election evidence authenticity must be constitutionally independent of the election system that produced the evidence. This constraint applies to audit-grade verification. Individual voter-facing verification (EC-01 domain) inherits its reference standard requirements from ADR-5.**

**Constitutional basis:**
1. AC-02 prohibits architecturally self-verifying authority functions
2. The DataChecksum circularity demonstrates that a system-held reference standard creates structural self-verification even when the verifier is external
3. TC2-DI-01 establishes that presence of evidence against a system-controlled reference cannot constitute constitutional authenticity
4. CF-05-08 establishes that behavioral consistency (evidence matches its own reference) ≠ constitutional legitimacy

**Scope discipline:** AC-31 is scoped to audit-grade evidence authenticity verification. It does not determine whether individual voters require access to an independent reference for personal verification — that is the EC-01 question, which belongs to ADR-5. The boundary: if the question concerns auditors verifying constitutional compliance of the election evidence record, AC-31 applies. If the question concerns individual voters confirming their own vote was counted, EC-01 and ADR-5 apply.

**Realization discipline (AC-31):**

AC-31 requires constitutional independence of the reference standard. AC-31 does NOT require:
- an external organization
- a separate bounded context
- a third-party company or external service
- a separate deployment or technical infrastructure

The constitutional requirement is reference independence — the reference must not be producible by, revocable by, or modifiable by the election system unilaterally. How that independence is architecturally realized (external body, voter custody, cryptographic commitment, or other mechanism) is a Round 38+ implementation decision. ADR-3 establishes the constitutional requirement; it does not select the realization form.

**Selected option for EH-01:** Option B (Independent Verifier + Independent Reference Standard) with Option C recognized as a viable implementation approach for achieving Option B's constitutional property through cryptographic commitment.

### C.4 What AC-31 Constrains

AC-31 places the following constitutional requirements on the domain model:

1. The Authenticity Stratum (see Part D) must specify a reference standard that is constitutionally independent of the election system
2. VO-2 (DataChecksum) as currently modeled does NOT satisfy AC-31 — it is system-produced and system-held (see Part F.3)
3. VO-3 (ReceiptHash) may satisfy AC-31 as a per-vote independent reference anchor, subject to the delivery independence analysis (see Part F.4)
4. AuditExecutionAuthority must have constitutional access to the independent reference standard in addition to the evidence itself (AC-15 for both pathways)
5. The reference standard's constitutional independence must be assessable from the authority map (AC-20)

### C.5 Rejected Alternative

**Option A (Independent Verifier Only) — REJECTED:** Does not satisfy AC-02. System-controlled reference standard creates structural self-verification regardless of verifier independence. The constitutional defect cannot be engineered away within the system boundary. Behavioral verification against a system-controlled reference is procedurally independent but constitutionally insufficient for audit-grade verification.

---

## Part D — CPR-05: Evidence Integrity Architecture

### D.1 Constitutional Requirements for Evidence Architecture

From the inherited evidence findings and AC-31:

| Requirement | Source | Architectural Implication |
|---|---|---|
| Evidence completeness requires an externally defined expected evidence set | Gap A-3, AC-14, AC-17 | Completeness is a constitutional scope question, not a system observation question |
| Evidence presence can be internally observed but requires external access | TC1-DI-01, AC-15 | Presence Stratum uses existing Audit Context model; must be externally accessible |
| Evidence authenticity requires an independent reference standard | AC-31 (from EH-01) | Authenticity Stratum must reference an independent reference; VO-2 alone insufficient |
| Presence ≠ authenticity — these are architecturally distinct concerns | AC-29, OBS-36C-03-G | Two strata (Presence + Authenticity) cannot be merged |
| Completeness ≠ Presence ≠ Authenticity — three independent dimensions | TC1-DI-01 + TC2-DI-01 + AC-29 | Three strata required, not two |

OBS-36C-03-G (binding): Evidence Authenticity ≠ Evidence Completeness. These are constitutionally independent dimensions and must not be merged in any evidence architecture. ADR-3 carries this forward as an architectural constraint on the stratum model.

### D.2 Option Analysis — CPR-05

#### Option A (Layered Evidence Pathways)

**Assessment:**
- AC-29: satisfied — two distinct pathways (presence + authenticity) are architecturally separated
- AC-31: NOT satisfied — the authenticity pathway uses DataChecksum (system-produced reference); reference standard circularity is not resolved by routing it through a separate pathway
- Gap A-3: NOT addressed — completeness remains unmodeled
- Verdict: constitutional incompleteness on both the critical constraints (AC-31 and Gap A-3)

#### Option B (External Evidence Repository)

**Assessment:**
- AC-29: satisfied — external repository holds reference; authenticity verified against repository, not originating system
- AC-31: satisfied — repository is the independent reference standard
- Gap A-3: partially addressed — repository mandate can define the expected evidence set; but the expected evidence set must still be constitutionally defined (AC-14, AC-17)
- Governance complexity: high — repository requires its own constitutional grounding (governance authority for the repository)
- Does not structurally separate Completeness from Presence + Authenticity — the repository handles presence AND authenticity reference; completeness is a scope definition question that requires additional constitutional specification

#### Option C (Cryptographic Evidence Accumulation)

**Assessment:**
- AC-29: well-addressed — commitment schemes can separate inclusion proof (presence) from content proof (authenticity)
- AC-31: satisfied if commitment is externally anchored
- Gap A-3: partially addressable — universal commitment can include full expected evidence set, but the scope definition (what must be in the commitment) still requires external constitutional definition
- VO-1 compatibility: requires implementation-level assessment (Round 38+); some commitment schemes reveal vote content; this is an implementation concern, not a constitutional architecture constraint
- CT-1 cross-tension: CPR-05 Option C × EC-01 tension — if commitment enables individual voter verification that enables receipt construction, CT-1 applies. At the constitutional architecture level (ADR-3 scope), this tension is conditionally deferred to ADR-5: if the implementation selects cryptographic commitment for the Authenticity Stratum, ADR-5 must assess CT-1 for the voter-facing verification pathway

#### Option D (Stratified Evidence Architecture)

**Assessment:**
- AC-29: strongest separation — three strata with different access requirements and constitutional grounding
- AC-31: Authenticity Stratum can be designed with independent reference standard requirement; does NOT specify which independent reference mechanism is used (defers to Round 38+)
- Gap A-3: Completeness Stratum makes the gap explicit and creates the constitutional architecture for closure; does NOT close it (ADR-4 closes the Completeness Stratum design)
- OBS-36C-03-G: directly satisfied — Authenticity and Completeness are in separate strata by definition
- CT-1 tension: NOT triggered at the constitutional architecture level by Option D alone. Option D establishes constitutional stratum requirements; it does not select the Authenticity Stratum implementation. If implementation selects cryptographic commitment, CT-1 assessment applies at implementation level (ADR-5 / Round 38+)
- VO-1 risk: Low — Option D's Presence Stratum observes VoteRecorded events (existing model, no voter linkage); Authenticity Stratum references a standard, not ballot content; Completeness Stratum deals with evidence record counts, not content

### D.3 Selection — CPR-05: **Option D (Stratified Evidence Architecture)**

**Rationale:**

Options A fails AC-31. Option B satisfies AC-31 but conflates Presence and Authenticity — the external repository holds both the evidence and the reference standard; the constitutional distinction between OBS-36C-03-G (Authenticity ≠ Completeness) and AC-29 (Presence ≠ Authenticity) is maintained by governance structure rather than architectural structure. Option C is an implementation approach for the Authenticity Stratum, not a complete evidence architecture — it does not address Completeness separately.

Option D is the only architecture that:
1. Structurally separates all three constitutional concerns (Completeness, Presence, Authenticity) as first-class strata
2. Assigns each stratum its own constitutional grounding requirement
3. Satisfies AC-29 and OBS-36C-03-G architecturally rather than by governance discipline
4. Does not trigger CT-1 at the constitutional level — Authenticity Stratum implementation is deferred to Round 38+
5. Has lowest VO-1 risk of the options that satisfy AC-31

**Discipline note:** Option D's selection does not specify the implementation of any stratum. The Authenticity Stratum's independent reference standard may be realized through Option B (external repository), Option C (cryptographic commitment), VO-3 (voter-held receipt), or a combination. Those are Round 38+ design decisions. ADR-3 establishes that each stratum must satisfy its constitutional requirement — it does not specify the technical realization.

---

## Part E — The Three-Stratum Evidence Model

### E.1 Stratum Definitions

The constitutional evidence architecture for this election system consists of three strata with distinct constitutional requirements:

```
Constitutional Evidence Architecture (Three Strata):

┌──────────────────────────────────────────────────────────┐
│  COMPLETENESS STRATUM                                      │
│  Constitutional question: What records MUST exist?         │
│  Source: ElectionConstitution (ADR-2 AUDIT Option D)      │
│  Closure: AuditScopeAuthority design (ADR-4)              │
│  Current status: Gap A-3 — UNDEFINED; dependency chain    │
│  established in this ADR (see E.3)                        │
├──────────────────────────────────────────────────────────┤
│  PRESENCE STRATUM                                          │
│  Constitutional question: What records DO exist?           │
│  Source: Audit Context (existing model — event observation)│
│  External access: AuditExecutionAuthority AC-15 pathway   │
│  Current status: PARTIALLY MODELED (events observed;      │
│  completeness comparison against Stratum 1 absent)        │
├──────────────────────────────────────────────────────────┤
│  AUTHENTICITY STRATUM                                      │
│  Constitutional question: Are present records unmodified? │
│  Source: Independent reference standard (AC-31)           │
│  Current status: STRUCTURALLY ABSENT (VO-2 insufficient   │
│  under AC-31; VO-3 is strongest candidate — see Part F)   │
└──────────────────────────────────────────────────────────┘
```

### E.2 Stratum Constitutional Requirements

**Completeness Stratum:**
- Constitutional source: ElectionConstitution (mandate from ADR-2 AUDIT Option D decision)
- AC-14: scope definition must originate outside the election system
- AC-17: mechanism for externally defining expected evidence set is constitutionally required
- Relationship to Gap A-3: Completeness Stratum IS the constitutional architecture for Gap A-3 closure — it names the stratum; it does not close it
- Completeness gap closure condition: ElectionConstitution must specify the expected evidence set with AC-30 precision (sufficient precision requirement)

**Presence Stratum:**
- Constitutional source: Audit Context (existing event observation model)
- The Audit Context already observes VoteRecorded events — this is the existing Presence Stratum
- External access pathway required: AuditExecutionAuthority must be able to access the Presence Stratum's evidence record (AC-15)
- Completeness comparison: Presence Stratum alone cannot determine whether all required evidence is present — it requires comparison against the Completeness Stratum's expected evidence set. This comparison is an AuditExecutionAuthority function, not a Presence Stratum function.

**Authenticity Stratum:**
- Constitutional requirement: independent reference standard required (AC-31)
- The reference standard must be constitutionally independent of the election system — not produced by, held by, or revocable by the election system unilaterally
- AuditExecutionAuthority must have constitutional access to both the evidence (Presence Stratum) AND the independent reference standard (Authenticity Stratum) to perform constitutionally valid authenticity verification
- Current model status: VO-2 (DataChecksum) is constitutionally insufficient; VO-3 (ReceiptHash) is the strongest existing candidate (see Part F)

**Stratum Non-Collapse Invariant:**

> **ADR3-INV-01: No future architecture may satisfy Completeness by Presence, Presence by Authenticity, or Authenticity by Completeness. Each stratum requires independent evaluation.**

This invariant is a binding architectural constraint on all subsequent ADRs and on all domain model designs in Round 38+. The three strata are constitutionally distinct — that the Presence Stratum shows all expected records are present does not establish authenticity; that the Authenticity Stratum confirms records are unmodified does not establish completeness; that the Completeness Stratum defines what must exist does not confirm what does exist or whether it is unmodified.

| Collapse Form | Why Prohibited |
|---|---|
| Completeness ← Presence | Presence of records does not prove all required records exist (TC1-DI-01: absence ≠ never existed) |
| Presence ← Authenticity | Confirmation that present records are unmodified does not prove all required records are present |
| Authenticity ← Completeness | Definition of what must exist does not confirm what does exist or whether it is unmodified |

### E.3 Completeness Stratum Dependency Chain

The Completeness Stratum's closure has a three-link constitutional dependency chain:

```
Link 1 (ADR-3 — this document):
    Completeness Stratum EXISTS as a constitutional requirement.
    Gap A-3 requires architectural resolution, not just procedural resolution.
    The stratum is named; its content is not yet defined.

Link 2 (ADR-2 — ESTABLISHED):
    ElectionConstitution is the constitutional source for the Completeness Stratum.
    The expected evidence set must be defined in ElectionConstitution (AUDIT Option D decision).
    This link is established — the source is determined.

Link 3 (ADR-4 — pending):
    AuditScopeAuthority (or the AuditAuthority aggregate if unified) must be designed
    to represent the ElectionConstitution's audit scope mandate.
    ADR-4 determines whether a dedicated AuditScopeAuthority aggregate is warranted
    or whether the scope mandate is represented within a unified AuditAuthority aggregate.
    ADR-4 closes the Completeness Stratum's governance design.
```

**What ADR-3 establishes about the Completeness Stratum:** existence, constitutional necessity, Link 1 of the dependency chain. ADR-3 does NOT determine the content of the expected evidence set, the aggregate design for scope authority, or the operational process for Completeness Stratum verification.

**What closes Gap A-3:** ElectionConstitution must enumerate the expected evidence set (Link 2 established in ADR-2). AuditScopeAuthority or equivalent must be designed to represent and enforce this enumeration (Link 3 — ADR-4). The gap is constitutionally addressable through this chain; it is not closed by ADR-3 alone.

---

## Part F — The Self-Authentication Prohibition

### F.1 AC-02 + AC-31 Combined: No Election Evidence is Self-Authenticating

The combination of AC-02 (no authority function may be architecturally self-verifying) and AC-31 (audit-grade evidence authenticity requires independent reference standard) produces a binding architectural prohibition:

> **No election evidence record is constitutionally self-authenticating.**

Self-authentication in evidence means: the evidence record, when checked against a reference standard controlled by the same system that produced the evidence, is reported as authentic. The prohibition applies to any evidence authenticity claim that is traceable back to a system-controlled reference.

**Implications for the authority model:** AuditExecutionAuthority cannot issue an authenticity certificate for election evidence if its entire reference chain is system-internal. The authority's mandate requires an independent reference (AC-31) as a constitutional prerequisite for its execution.

### F.2 Self-Authentication Forms Prohibited

The following patterns constitute self-authentication and are constitutionally impermissible for audit-grade evidence verification:

| Pattern | Why Prohibited |
|---|---|
| Election system generates DataChecksum → external verifier checks evidence against DataChecksum held by election system | Reference standard is system-controlled; simultaneous compromise of evidence + reference is undetectable |
| External audit body accesses Audit Context events + election system's checksum database | Same reference chain as above — reference is system-held regardless of who accesses it |
| Election system certifies its own evidence completeness (compares Presence Stratum against a system-defined expected set) | Completeness Stratum source must be external (AC-14, AC-17); system-defined expected set is self-certification |
| Audit execution by a body whose IR-H independence exists but whose reference standard is system-provided | IR-H satisfied for the verifier; AC-31 violated for the reference standard |

### F.3 VO-2 (DataChecksum) — Constitutional Status Under AC-31

**Current status:** CONSTITUTIONALLY INSUFFICIENT for audit-grade evidence authenticity verification.

**Reason:** DataChecksum (VO-2) is generated by the Vote aggregate at recording time, stored within the election system, and retrievable from the election system. Both the evidence record and the reference standard are within the election system's authority boundary. Under AC-31, this is the canonical self-authentication case.

**What VO-2 can legitimately do:**
- Serve as an internal consistency check — a behavioral integrity measure confirming that the evidence record has not been corrupted in transit or storage within the system
- Function as the EVIDENCE component that an independent reference standard would validate against (i.e., the DataChecksum itself could be the value that an external reference independently anchors)

**What VO-2 cannot do under AC-31:**
- Constitute the independent reference standard for audit-grade authenticity verification
- Be the sole basis for an AuditExecutionAuthority authenticity certificate

**Architectural consequence for VO-2:** VO-2 remains a valid value object in the Vote aggregate for its behavioral integrity purpose. Its role in the Authenticity Stratum is as the value to be verified — not as the reference standard.

### F.4 VO-3 (ReceiptHash) — Constitutional Analysis Under AC-31

VO-3 (ReceiptHash) is generated by the Vote aggregate at recording time and delivered to the voter. The voter holds the receipt independently of the election system. This creates a constitutionally significant distinction from VO-2:

**The independence argument for VO-3:**
Once delivered to the voter, the voter's receipt copy is outside the election system's authority boundary. The election system cannot unilaterally modify what the voter holds. If the voter's VO-3 differs from what the election system claims, the voter can present their receipt as evidence of discrepancy. The voter-held receipt is constitutionally independent in the sense that it is not revocable or modifiable by the election system.

**VO-3 as AC-31 candidate — delivery independence analysis:**

| Question | Assessment |
|---|---|
| Is VO-3 generated by the election system? | Yes |
| Is VO-3 held by the election system? | Partially — a copy exists in the system; the voter's copy is external |
| Is the voter's copy of VO-3 modifiable by the election system? | No — once delivered, the voter's copy is outside system control |
| Does the voter's copy satisfy AC-31's "constitutionally independent reference"? | CANDIDATE — the voter's copy is independent of the election system after delivery |
| Can voter-held VO-3 serve as an aggregate reference for auditors? | Limited — individual receipt + individual record; aggregate evidence integrity requires aggregate reference |

**Assessment:** VO-3 is the strongest existing candidate for a per-vote independent reference anchor. Voter-held receipts function as a distributed independent reference — each voter holds a reference for their own vote that the system cannot retroactively modify. This is a constitutional property that VO-2 does not have.

**Limitations for Authenticity Stratum purposes:**
1. VO-3 is per-vote: it provides per-vote authenticity verification capability; aggregate evidence integrity may require an aggregate reference standard (hash of all receipts, or commitment over the full ballot set)
2. VO-3 delivery independence requires constitutional protection of the delivery process: if the system delivers a false VO-3 to the voter AND stores a matching false evidence record, the voter's receipt matches the false record — the delivery process must be independently protected
3. VO-3's scope covers the voter's ballot record; the scope of audit-grade verification (per AC-31) covers the full evidence record — VO-3 addresses the authenticity of individual vote records, not necessarily the completeness or authenticity of governance evidence, tally evidence, or enrollment evidence
4. **VO-3 satisfies custody independence (outside system control after delivery) but not origin independence (generated and initially delivered by the election system).** The system generates VO-3, the system delivers VO-3, and the system stores the originating record. Whether custody independence — the voter's post-delivery exclusive hold — is constitutionally sufficient for AC-31 audit-grade purposes is not established by ADR-3. This remains a constitutional design question for Round 38+.

**ADR-3 determination for VO-3:**

> **VO-3 (voter-held ReceiptHash) is a CONFIRMED CANDIDATE for the per-vote authenticity reference in the Authenticity Stratum. VO-3 satisfies custody independence after delivery. Whether custody independence is sufficient for AC-31 remains a Round 38+ design question.** Its role in aggregate evidence authentication and non-vote evidence authentication requires design specification (Round 38+). ADR-5 must assess whether voter-held VO-3 is compatible with receipt-freeness requirements under EC-01 before voter-verification use cases are designed.

### F.5 OBS-ADR3-01 — Independent Reference Standard Legitimacy

The introduction of an independent reference standard creates a new constitutional dependency beyond the election system's own authority map:

> **OBS-ADR3-01: Any independently governed reference standard requires its own legitimacy chain. The existence of an independent reference standard does not itself establish constitutional legitimacy for that reference standard.**

The constitutional obligation does not end with requiring independence — it extends to requiring that the independent reference be itself constitutionally legitimate. A reference standard held by an unconstrained external party, or by a body without traceable L1Source, is constitutionally independent of the election system but not constitutionally grounded.

**Implications:**
- The body holding or producing the independent reference standard must have traceable constitutional authority for that role
- This is NOT a new D43 instance — it does not describe a gap in the election system's own authority map
- This IS a governance dependency that ADR-6 (Certification Architecture) and ADR-7 (GovernanceState Boundary) must inherit: when designing CertificationAuthority and ElectionConstitution's scope, the legitimacy of the reference standard holder must be explicitly addressed

**ADR-7 inheritance:** The concentration analysis from ADR-2 H.3.1 must be extended. If ElectionConstitution designates the independent reference standard holder, the concentration dependency deepens — one additional authority dimension tracing to ElectionConstitution. If the reference standard holder has a constitutionally independent legitimacy chain, that independence must be established and its own L1Source identified. ADR-7 must assess whether this additional concentration dimension changes the total constitutional collapse implications from ADR-2 H.3.1.

---

## Part G — Cross-Concern Analysis

### G.1 Evidence/Authenticity/Completeness/Challengeability Relationships

The four constitutional concerns are related but constitutionally independent:

```
Evidence (what records exist — Presence Stratum)
    ↓ depends on
Completeness (what records must exist — Completeness Stratum)
    Constitutional source: ElectionConstitution (ADR-2)
    Design: AuditScopeAuthority (ADR-4)

Evidence (what records exist — Presence Stratum)
    ↓ verified against
Authenticity (are records unmodified — Authenticity Stratum)
    Constitutional requirement: independent reference standard (AC-31)

Evidence decisions (which records are certified as authentic/complete)
    ↓ may be challenged through
Challengeability (who may challenge evidence decisions)
    Constitutional design: ADR-5 (Challenge Architecture)
    Note: Challengeability is NOT an evidence stratum — it is a dimension of
    the authority aggregates that hold evidence-related mandates
```

**The non-implication:** More evidence ≠ more authenticity. A larger evidence record that is more completely present does not become more authentic by its size. Completeness and Authenticity are independently necessary; neither is sufficient for the other.

**The ADR-5 boundary:** Challengeability of evidence decisions is NOT part of the three-stratum model. Who may challenge an AuditExecutionAuthority finding, or who may challenge a certification decision based on evidence, belongs to the challenge architecture (ADR-5). The stratum model designs what evidence must be — challenge architecture designs who can contest the evidence assessment.

### G.2 CT-1 Cross-Tension Assessment

**CT-1 tension:** EC-01 Option B (individual verifiability without receipt construction) × CPR-05 Option C (cryptographic commitment scheme)

**ADR-3's impact on CT-1:**

ADR-3 selects CPR-05 Option D (Stratified Architecture), not Option C. CT-1 as defined in 36E-04 Section 9 is therefore NOT directly triggered by ADR-3's architecture selection.

**Conditional CT-1 exposure:** If the Authenticity Stratum's implementation (Round 38+) uses a cryptographic commitment scheme, CT-1 may be triggered at the implementation level. This conditional exposure is carried forward:

> **CT-1 Conditional Dependency (ACTIVE):** If the Authenticity Stratum implementation selects cryptographic commitment (CPR-05 Option C mechanism), ADR-5 must assess CT-1 — specifically whether the commitment scheme enables individual voters to construct receipts from their ballot commitments. This conditional dependency from 36E-05 remains active.

ADR-5 must be aware of this conditional when designing the challenge architecture for vote-content decisions.

### G.3 EH-01/AC-31 × CPR-05 Option D Compatibility

The EH-01/AC-31 selection (independent reference standard required) and the CPR-05 Option D selection (stratified architecture) are constitutionally compatible:

- Option D's Authenticity Stratum IS the constitutional architecture in which AC-31 is satisfied
- AC-31 constrains what the Authenticity Stratum must hold (an independent reference standard) without prescribing how
- The three strata together create the constitutional space for AC-31 compliance without triggering CT-1 at the architecture level

### G.4 VO-1 Compatibility Analysis

VO-1 (vote anonymity invariant — no voter identity may appear in any Vote aggregate event) must be preserved throughout the evidence architecture.

| Stratum | VO-1 Impact | Assessment |
|---|---|---|
| Completeness Stratum | Defines what records must exist — expected evidence set is counts and types, not voter-linked content | Compatible |
| Presence Stratum | Observes VoteRecorded events — these are already anonymous (no voter identity, VO-1 enforced at command layer) | Compatible |
| Authenticity Stratum | References a standard for evidence integrity — the reference standard holds hashes/commitments, not ballot content | Compatible — depends on implementation not breaking VO-1 |

**VO-1 risk in CPR-05 Option D:** Low at the constitutional architecture level. The risk exists at implementation level: if the Authenticity Stratum's implementation (Round 38+) uses a commitment scheme that requires revealing ballot content as part of commitment verification, VO-1 is at risk. This is an implementation constraint that ADR-3 flags for Round 38+ design:

> **VO-1 Implementation Constraint:** Any Authenticity Stratum implementation must be evaluated for VO-1 compatibility before adoption. Commitment schemes or reference standard mechanisms that require revealing ballot content or voter identity during verification are constitutionally impermissible regardless of other properties.

---

## Part H — AuditExecutionAuthority Evidence Requirements

### H.1 ADR-2 AUDIT Inheritance for Evidence

ADR-2 established that AuditExecutionAuthority holds the mandate to execute audit verification. This mandate has three constitutional evidence requirements:

1. **Completeness assessment mandate:** AuditExecutionAuthority must be able to compare the Presence Stratum against the Completeness Stratum's expected evidence set. This comparison requires access to both strata and constitutional authority to determine whether the comparison result constitutes audit compliance.

2. **Authenticity verification mandate:** AuditExecutionAuthority must be able to verify Presence Stratum evidence against the Authenticity Stratum's independent reference standard. This requires access to both the evidence (Presence Stratum) and the reference (Authenticity Stratum).

3. **IR-H independence:** AuditExecutionAuthority must be constitutionally independent of the audited subject. Its access to the Presence and Authenticity strata must be constitutionally grounded — the access right must not be revocable by the election system (AC-12 applied to access).

### H.2 AC-15 Access Pathway Specification Requirements

AC-15 requires that election evidence is accessible to constitutionally authorized external parties. For AuditExecutionAuthority, the AC-15 access pathway must cover:

| Stratum | Access Requirement | Constitutional Source |
|---|---|---|
| Completeness Stratum | Read access to the expected evidence set definition | ElectionConstitution (publicly specified) |
| Presence Stratum | Read access to the full Audit Context evidence record | AC-15 — external access pathway to Audit Context |
| Authenticity Stratum | Read access to the independent reference standard | AC-15 extension — access to the reference, not only the evidence |

**New specification required:** The AC-15 access pathway must be extended to include the Authenticity Stratum's independent reference standard. If the reference standard is voter-held (VO-3), the access pathway design is different from a centrally held external repository. This design belongs to ADR-4 (for scope-related access) and Round 38+ (for the Authenticity Stratum implementation).

### H.3 Authority Map Update After ADR-3

The Authenticity Stratum's independent reference standard creates a new authority map element:

```
Authority Map (after ADR-3, conceptual):
┌────────────────────────────────────────────────────────────────────────────┐
│                     ElectionConstitution (shared L1Source)                  │
└──────────┬──────────┬──────────┬──────────┬──────────────────────────────┘
           │          │          │          │                    │
    EnrollmentA  CriteriaA   [AuditScope] GovernanceA    CertificationA
    [L2=Cmte]   [L2=Cmte    [CANDIDATE   [L2=Cmte]     [L2=External]
                 L4=Cmte]    — ADR-4]

                             AuditExecA
                             [L2=Indep body]
                                  │
                         ┌────────┴─────────────────┐
                         │  Evidence Strata Access   │
                         │  (AC-15 — to be designed) │
                         │  Completeness ← Constitution│
                         │  Presence ← Audit Context  │
                         │  Authenticity ← Indep Ref  │
                         └──────────────────────────┘
```

---

## Part I — Consequences for ADR-4 through ADR-7

### I.1 Consequences for ADR-4 (Audit Scope Authority)

ADR-3 establishes the Completeness Stratum as a constitutional requirement and Link 1 of the dependency chain. ADR-4 must:

- Determine whether AuditScopeAuthority and AuditExecutionAuthority are two distinct aggregates (ADR-2 E.4 candidate refinement) or one unified AuditAuthority aggregate with scope and execution functions
- Design the aggregate(s) that represent the Completeness Stratum's constitutional mandate from ElectionConstitution
- Specify how the expected evidence set is represented in the domain model (as an AuditScopeAuthority aggregate carrying the ElectionConstitution's audit scope mandate — or as a specification-level element)
- Address AC-16: scope authority and execution authority cannot share an architectural authority boundary — ADR-4 must determine how the Completeness Stratum (scope) and Presence Stratum assessment (execution) are constitutionally separated in the aggregate design
- Design the AuditExecutionAuthority's access to the Completeness Stratum (how does the executing authority know what the scope mandate requires?)

**ADR-4 inherits from ADR-3:** Three-stratum model, Completeness Stratum as Gap A-3 architectural resolution, Link 1 + Link 2 of the dependency chain (Link 3 is ADR-4's to establish).

### I.2 Consequences for ADR-5 (Challenge Architecture)

- **CT-1 conditional dependency (ACTIVE):** If Authenticity Stratum implementation uses cryptographic commitment, ADR-5 must assess CT-1 cross-tension
- **GOV-AUTH challengeability question (carried from ADR-2):** Does GovernanceAuthorizationCommittee require independent challengeability? ADR-5 must address this
- **AuditExecutionAuthority challenge pathway:** ADR-5 must determine how AuditExecutionAuthority's findings (completeness and authenticity assessments) are challengeable — through what body and process
- **VO-3 and EC-01 interaction:** ADR-5 must assess whether voter-held VO-3 is compatible with receipt-freeness requirements before voter-verification use cases are designed

### I.3 Consequences for ADR-6 (Certification Architecture)

- CertificationAuthority (Option C, external body) must receive a constitutionally sufficient evidence package before issuing certification
- What constitutes a constitutionally sufficient evidence package for certification? All three strata must be satisfied: the Completeness Stratum comparison must show expected evidence is present; the Presence Stratum must be accessible; the Authenticity Stratum verification must confirm evidence is unmodified against the independent reference
- **Explicit certification prohibition (AC-02 + AC-31):** CertificationAuthority may not certify Authenticity Stratum compliance without access to the same independent reference standard required by AC-31. Certification cannot accept the election system's report of authenticity verification as constitutionally sufficient — if CertificationAuthority relies solely on AuditExecutionAuthority's finding without independently accessing the reference standard, certification recreates the trust chain collapse that AC-31 prohibits. The reference chain would terminate in the audited system's own verification report, reversing the constitutional advance made by the AC-31 elevation.
- The external certification body's access to the Authenticity Stratum's independent reference standard is a constitutional access question — the body must be able to independently verify, not rely on the election system's report of verification
- D39 (Results/Tallying) dependency remains: certification of election results cannot be fully designed until D39 is resolved

### I.4 Consequences for ADR-7 (GovernanceState Boundary)

- The Authenticity Stratum's independent reference standard, if realized through the ElectionConstitution or a constitutionally anchored process, creates an additional dimension of ElectionConstitution concentration (all five authority aggregates + the evidence reference standard all tracing to ElectionConstitution)
- ADR-7 must assess whether this additional concentration dimension changes the chokepoint failure analysis from ADR-2 H.3.1
- VO-3 delivery independence: if voter-held receipts function as the distributed Authenticity Stratum reference, the governance of VO-3 delivery (who delivers, what constitutional protections govern delivery) is a GovernanceState boundary question

---

## Part J — Comparative Option Matrix

### J.1 EH-01 Option Selections

| Option | AC-02 | AC-31 satisfied | EH-01 Derived | EH-01 Hypothesized | Selected |
|---|---|---|---|---|---|
| A (Verifier Only) | Stressed | No | Yes | No | REJECTED |
| B (Verifier + Reference) | Satisfied | Yes | Yes | Yes | **SELECTED (→ AC-31)** |
| C (Cryptographic Commitment) | Conditional | Conditional | Yes | Conditional | Viable implementation of B |

### J.2 CPR-05 Option Selections

| Option | AC-29 | AC-31 | Gap A-3 | OBS-36C-03-G | CT-1 risk | VO-1 risk | Selected |
|---|---|---|---|---|---|---|---|
| A (Layered Pathways) | Satisfied | Not satisfied | Not addressed | Partial | Low | Low | REJECTED |
| B (External Reference Holder) | Satisfied | Satisfied | Partial | Partial (conflates Presence+Auth) | Low | Low | Not selected |
| C (Cryptographic) | Well-satisfied | Conditional | Partial | Well-satisfied | ACTIVE | Requires assessment | Not selected |
| D (Stratified) | Strongest | Architecture for satisfaction | Named; ADR-4 closes | Satisfied structurally | Conditional (deferred) | Low | **SELECTED** |

### J.3 Value Object Constitutional Status

| Value Object | Role | AC-31 Status | ADR-3 Determination |
|---|---|---|---|
| VO-2 (DataChecksum) | Internal consistency reference | INSUFFICIENT for Authenticity Stratum | Evidence component to be verified; not the reference standard |
| VO-3 (ReceiptHash) | Per-vote voter-held receipt | CANDIDATE for per-vote reference | Confirmed candidate; aggregate reference design deferred to Round 38+; ADR-5 must assess EC-01 compatibility |

---

## Section — ARB Decision Block

**[APPROVED — Required Revisions Applied (2026-06-15)]**

**ARB Decision:** APPROVED WITH REQUIRED REVISIONS → APPROVED (2026-06-15)

**Required Revisions Applied:**
- R1 (C.3): AC-31 realization discipline added — AC-31 requires reference independence, not external organization; realization form deferred to Round 38+
- R2 (F.4): VO-3 qualification strengthened — custody independence ≠ AC-31 sufficiency; origin independence question carried to Round 38+
- R3 (F.5): OBS-ADR3-01 elevated to formal observation — independent reference standard requires its own legitimacy chain; ADR-6 and ADR-7 must inherit
- R4 (I.3): CertificationAuthority explicit prohibition added — may not certify Authenticity Stratum compliance without independently accessing the AC-31 reference standard
- R5 (E.2): ADR3-INV-01 three-stratum non-collapse invariant added — no stratum may be satisfied by another; binding on all subsequent ADRs and Round 38+

### Decisions Made in This ADR

1. **EH-01 → AC-31 (Architectural Constraint):** Constitutional (audit-grade) evidence authenticity verification requires an independent reference standard. Scoped to audit-grade verification; individual voter-facing verification deferred to ADR-5 / EC-01.

2. **CPR-05 → Option D (Stratified Evidence Architecture):** Three strata — Completeness (Gap A-3 / constitutional scope), Presence (Audit Context existing model), Authenticity (AC-31 independent reference). Strata are constitutionally distinct; none may substitute for another.

3. **Self-Authentication Prohibited:** AC-02 + AC-31 combined. No election evidence is constitutionally self-authenticating. VO-2 (DataChecksum) as currently modeled is constitutionally insufficient for Authenticity Stratum purposes.

4. **VO-2 (DataChecksum) constitutional role:** Behavioral integrity reference (internal consistency); evidence component to be verified against an independent reference. NOT the Authenticity Stratum reference standard.

5. **VO-3 (ReceiptHash) constitutional role:** Confirmed CANDIDATE for per-vote authenticity reference in the Authenticity Stratum. Delivery creates constitutional independence from election system. Aggregate reference role and EC-01 compatibility deferred to ADR-5 / Round 38+.

6. **Completeness Stratum dependency chain:** Three links — Link 1 (this ADR: stratum existence and constitutional necessity), Link 2 (ADR-2 established: ElectionConstitution as source), Link 3 (ADR-4 to establish: AuditScopeAuthority design). Gap A-3 is constitutionally addressable through this chain.

7. **CT-1 Cross-Tension:** NOT triggered by ADR-3 selection (CPR-05 Option D avoids it at constitutional level). Conditional CT-1 dependency remains ACTIVE: if Authenticity Stratum implementation uses cryptographic commitment, ADR-5 must assess CT-1.

8. **VO-1 Compatibility:** CPR-05 Option D is constitutionally VO-1 compatible. Implementation-level VO-1 risk flagged as design constraint for Round 38+.

9. **AC-15 Extension:** AuditExecutionAuthority requires constitutional access to both evidence (Presence Stratum) AND the independent reference standard (Authenticity Stratum). AC-15 pathway design covers both; implementation belongs to ADR-4 and Round 38+.

### Open Questions for ARB

**OQ-37-03-01: Is VO-3 (voter-held receipt) constitutionally sufficient as the primary per-vote Authenticity Stratum reference anchor?**

The delivery independence argument is constitutionally significant: voter-held receipts are outside election system control after delivery. However, the delivery process itself must be constitutionally protected, and the aggregate evidence integrity question (can per-vote receipts constitute an aggregate reference?) is unresolved. ADR-3 confirms VO-3 as a candidate. Does the ARB see a constitutional objection to this classification that ADR-3 has not addressed?

**OQ-37-03-02: Should the expected evidence set (Completeness Stratum content) be partially enumerable in ADR-3, or is full deferral to ADR-4 correct?**

ADR-3 establishes the Completeness Stratum exists and that ElectionConstitution is its source. The content of the expected evidence set — what records must exist — is deferred to ADR-4 (AuditScopeAuthority design). Is any preliminary enumeration warranted at ADR-3 level, or is full deferral correct given that the enumeration is an AuditScopeAuthority function?

**OQ-37-03-03: Does AC-31 require that the independent reference standard be designated by ElectionConstitution, or is it sufficient for ElectionConstitution to authorize an independent body to hold the reference?**

Two constitutional forms are possible: (a) ElectionConstitution defines what the reference standard must be (constitutional specification of the reference), or (b) ElectionConstitution designates an independent body that holds or produces the reference standard (constitutional delegation of reference authority). These have different implications for the Authenticity Stratum's relationship to ElectionConstitution and the concentration analysis from ADR-2 H.3.1.

### Decisions Deferred to Subsequent ADRs

- Completeness Stratum content (expected evidence set enumeration): ADR-4
- AuditScopeAuthority / AuditExecutionAuthority unified vs. split aggregate decision: ADR-4
- Authenticity Stratum implementation (external repository, cryptographic commitment, voter-held receipts, or combination): Round 38+
- VO-3 EC-01 compatibility (voter verification without receipt construction): ADR-5
- CT-1 assessment if cryptographic commitment implementation is selected: ADR-5
- AuditExecutionAuthority challenge pathway design: ADR-5
- GOV-AUTH committee challengeability question (carried from ADR-2): ADR-5
- CertificationAuthority evidence package specification: ADR-6
- Additional ElectionConstitution concentration from Authenticity Stratum reference: ADR-7

### Authorization Requested

ADR-4: Audit Scope Authority Structure (CPR-02, ET-03)

ADR-4 is the direct successor of ADR-3. The Completeness Stratum dependency chain Link 3 is ADR-4's primary output. ADR-3's Completeness Stratum existence decision is not actionable in the domain model until ADR-4 designs the AuditScopeAuthority (or unified AuditAuthority) aggregate structure.

ADR-5: Challenge Architecture (EC-01, AC-09/10) — depends on ADR-3 completion (CT-1 conditional established, VO-3/EC-01 interaction identified). The conditional ADR-3 → ADR-5 dependency from 36E-05 has now been concretized: ADR-5 must address VO-3/EC-01 and the CT-1 conditional.

---

*Round 37-03 — ADR-3: Evidence and Verifier Architecture — SUBMITTED FOR ARB REVIEW*  
*Research Program: NRNA DDD Trustworthiness*  
*Document: Round37-03_ADR-3_Evidence_and_Verifier_Architecture.md*  
*Predecessors: ADR-1, ADR-2 — APPROVED*  
*Successors pending ARB: ADR-4, ADR-5*
