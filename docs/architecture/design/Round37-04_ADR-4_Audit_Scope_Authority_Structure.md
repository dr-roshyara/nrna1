# Round 37-04 — ADR-4: Audit Scope Authority Structure

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 37 — ADR Authoring
**Document:** ADR-4 of 7
**Status:** SUBMITTED FOR ARB REVIEW
**Governing Question:** Who constitutionally defines the expected evidence set, and how is scope authority separated from execution authority?

**Predecessors:**
- ADR-1 — Authority Vocabulary and Authority Source Model — APPROVED
- ADR-2 — Independence Form per D43 Function — APPROVED
  Key inheritance: AUDIT = Option D (Hybrid); AuditScopeAuthority/AuditExecutionAuthority split = CANDIDATE pending ADR-4
- ADR-3 — Evidence and Verifier Architecture — APPROVED
  Key inheritance: Three-stratum model (Completeness/Presence/Authenticity); Completeness Stratum Links 1+2 established; Link 3 = ADR-4's mandate; ADR3-INV-01; OBS-ADR3-01
- 36E-04 — Architecture Option Evaluation (CPR-02, ET-03) — APPROVED

**Scope:** Conceptual architecture only. No aggregate implementation specification. No database schema. No service design. Audit scope authority = what constitutional requirements the scope-defining function must satisfy and how it relates to the execution function — not how those requirements are technically implemented.

---

## Part A — Problem Statement

### A.1 The Core Questions

ADR-2 established that AUDIT requires two constitutionally distinct sub-functions: constitutional scope definition (what must be present) and independent execution (verifying what is present against the defined scope). ADR-2 left the aggregate design as CANDIDATE pending ADR-4. ADR-3 established that the Completeness Stratum (what records MUST exist) is constitutionally necessary and that its source is ElectionConstitution — but left the design of the authority aggregate(s) that represent the scope mandate as Link 3 of the dependency chain.

ADR-4 answers four governing questions:

1. **The aggregate question (CPR-02):** Should AuditScopeAuthority and AuditExecutionAuthority be separate aggregates, or should a single unified AuditAuthority hold both functions? (AC-16 compliance)
2. **The ET-03 recursion question:** Does AuditScopeAuthority itself trigger a new D43 gap (undefined authority), or can its legitimacy be constitutionally grounded without generating a new instance?
3. **The specification form question (OQ-ADR4-01):** Is the expected evidence set fully constitutional (defined entirely in ElectionConstitution), constitutionally delegated (ElectionConstitution authorizes AuditScopeAuthority to specify it), or a versioned audit profile (most flexible, most risk)?
4. **The access pathway question:** How does AuditExecutionAuthority access the expected evidence set to execute completeness comparisons?

### A.2 The Governing Constitutional Tension

```
AC-16 requires:
  Scope authority and execution authority
  must have distinct architectural authority boundaries.

But ET-03 warns:
  A separate AuditScopeAuthority aggregate requires
  its own L-1 through L-5.
  Does this create a new D43 gap?
```

ADR-4 must resolve this tension: structural separation is constitutionally required (AC-16); that separation must not create new undefined authority (ET-03). The resolution is not a compromise between AC-16 and ET-03 — both must be satisfied simultaneously.

### A.3 Binding Constraints

From 36E-01 (binding constraints):

| ID | Constraint | ADR-4 Relevance |
|---|---|---|
| AC-02 | No authority function may be architecturally self-verifying | Scope authority must not verify compliance with its own scope definition |
| AC-14 | Audit scope must originate outside the election system's architectural boundary | AuditScopeAuthority must be constitutionally outside the election system |
| AC-16 | Scope authority and execution authority must have distinct architectural authority boundaries | Direct CPR-02 constraint — structural separation required |
| AC-17 | External mechanism for defining expected evidence set is constitutionally required | AuditScopeAuthority IS the mechanism required by AC-17 |
| AC-24 | Audit must include external scope definition, external access pathway, IR-H structure | AuditScopeAuthority = external scope definition element of AC-24 |
| AC-30 | ElectionConstitution must be precise enough to govern electoral conduct | Scope mandate in ElectionConstitution must achieve AC-30 precision |

From ADR-1: authority relationships are modeled as dedicated aggregates with L-1/L-5. Splitting "AuditAuthority" (ADR-1 candidate) into two named aggregates requires ADR-4 authorization.

From ADR-2 (AUDIT Option D): constitutional scope via ElectionConstitution + independent execution body satisfying IR-H independence. AuditScopeAuthority/AuditExecutionAuthority split = CANDIDATE; ADR-4 confirms or rejects.

From ADR-3: Three-stratum model; Completeness Stratum Links 1+2 established; ADR3-INV-01 (no stratum may be satisfied by another); OBS-ADR3-01 (any independently governed reference standard requires its own legitimacy chain).

### A.4 The ET-03 Recursion Risk

From 36E-02/03: If AuditScopeAuthority is a separate aggregate, it requires its own L-1 through L-5. Does this create a new D43 gap — an undefined authority ownership?

The D43 threshold test: is there a constitutional function where authority ownership is undefined, unchallenged, or self-referential?

For AuditScopeAuthority:
- If L-1 is ElectionConstitution and L-2/L-5 are derived from constitutional designation, no gap exists
- If L-1 is undefined or self-declared, ET-03 IS a new D43 instance requiring resolution before any ADR-4 selection is possible

ADR-4 must determine: is AuditScopeAuthority constitutionally groundable without creating a new D43 gap? This determination precedes the CPR-02 selection.

### A.5 Options Under Evaluation

**CPR-02 options (from 36E-04, Section 4):**
- Option A — Unified AuditAuthority: one aggregate holds scope definition mandate AND execution mandate; internal separation via domain policy objects
- Option B — Separated: AuditScopeAuthority (scope definition) and AuditExecutionAuthority (audit execution) as two distinct authority aggregates
- Option C — Constitutional Scope Specification: scope is embedded in ElectionConstitution as a constitutional specification (not a separate aggregate); execution authority reads specification directly

**OQ-ADR4-01 specification forms (carried from ARB final approval of ADR-3):**
- Form A — Fully Constitutional: ElectionConstitution directly and completely enumerates the expected evidence set; no delegation
- Form B — Constitutionally Delegated: ElectionConstitution mandates evidence categories and authorizes AuditScopeAuthority to specify the concrete expected evidence set
- Form C — Versioned Audit Profile: ElectionConstitution sets minimum requirements; AuditScopeAuthority maintains a versioned, updateable audit profile within constitutional bounds

---

## Part B — Constitutional Evidence Inherited

### B.1 D43-AUDIT Constitutional State

From 36D-04 (Authority Legitimacy Analysis):
- D43-AUDIT: Gap A-3 (Completeness) = PRIMARY AUDITABILITY RISK — the expected evidence set was undefined at discovery
- Audit has IR-H requirement (independence from the audited subject — the election system as a whole, not only its operators)
- Sub-function distinction confirmed by ADR-2: scope definition (what must be present) is constitutionally distinct from execution (verifying what is present)

From 36E-03 (DDD Impact Assessment):
- D43-AUDIT: zero mandate for audit scope authority discovered; no grounded authority holder identified
- ET-03: sub-function of D43-AUDIT; specialization vs. separate authority relationship open; not yet a D43 instance — threshold evaluation deferred to 36E-04 and now ADR-4

### B.2 AC-16 Scope/Execution Boundary

AC-16: "Scope authority and execution authority cannot share an architectural authority boundary."

The constitutional basis for AC-16:
- If the same authority both defines what must be present AND verifies whether it is present, the scope definition is vulnerable to self-referential collapse: an executor can adjust scope to match what it finds (Completeness-by-Presence collapse — ADR3-INV-01 form)
- AC-02 prohibits self-verifying authority functions
- AC-16 extends AC-02 to the scope dimension: the scope must not be within the execution authority's unilateral control, even informally

**AC-16 implication for CPR-02:**
- Option A (Unified): AC-16 requires structural separation within one aggregate — the scope function and execution function must have architecturally distinct boundaries even inside one aggregate. This is enforceable only through governance discipline, not architectural structure; the aggregate's consistency boundary encompasses both functions, and any command handler has theoretical access to both
- Option B (Separated): AC-16 is structurally satisfied — separate aggregates have separate constitutional authority; the execution aggregate cannot modify the scope aggregate's mandate
- Option C (Constitutional Specification): AC-16 is satisfied if the specification is immutable outside the constitutional amendment process — the execution authority cannot alter what ElectionConstitution specifies

### B.3 ADR-3 Completeness Stratum Inheritance

**What ADR-3 established (Links 1 and 2):**
- Link 1: Completeness Stratum EXISTS as a constitutional requirement; Gap A-3 requires architectural resolution
- Link 2: ElectionConstitution is the constitutional source for what must be present

**What ADR-4 must establish (Link 3):**
- The aggregate design that holds the constitutional authority to define and publish the expected evidence set
- How AuditExecutionAuthority accesses the expected evidence set for completeness comparison
- How the expected evidence set is constitutionally grounded (OQ-ADR4-01)

ADR3-INV-01 constraint: ADR-4's design must not create a structure where Completeness is satisfied by Presence. The scope authority must not accept "what is present" as its definition of "what must be present."

### B.4 OBS-ADR3-01 Application to This ADR

OBS-ADR3-01 (from ADR-3 F.5): "Any independently governed reference standard requires its own legitimacy chain. The existence of an independent reference standard does not itself establish constitutional legitimacy for that reference standard."

ADR-3 established OBS-ADR3-01 as an observation that ADR-6 and ADR-7 must inherit. ADR-4 applies OBS-ADR3-01 concretely for the first time: AuditScopeAuthority, if it is a separately governed scope authority, is an independently governed reference standard (for the Completeness Stratum). Its existence as a separate authority does not itself establish its constitutional legitimacy. OBS-ADR3-01 requires that ADR-4 either:

a) Explicitly establish AuditScopeAuthority's legitimacy chain (L-1/L-5), or
b) Establish that AuditScopeAuthority is NOT independently governed (Option C — embedded in ElectionConstitution) and therefore OBS-ADR3-01 applies differently

ADR-4 is the first ADR where OBS-ADR3-01 becomes a design constraint, not merely an observation. The resolution of ET-03 IS the resolution of OBS-ADR3-01 for AuditScopeAuthority.

### B.5 ElectionConstitution Chokepoint

From ADR-2 H.3.1: ElectionConstitution is the shared L-1 for all five authority aggregates. Constitutional collapse follows from ratification failure.

ADR-4 adds to this: if AuditScopeAuthority is a new sixth aggregate with ElectionConstitution as L-1, the chokepoint deepens. Additionally, under OQ-ADR4-01 Form B, the Tier 1 evidence categories (constitutional mandate content) also reside in ElectionConstitution — creating both an authority dependency (AuditScopeAuthority L-1) and a content dependency (the categories themselves). ADR-7 must assess.

---

## Part C — CPR-02: Audit Scope Authority Structure

### C.1 Option Analysis

#### Option A — Unified AuditAuthority

One aggregate holds scope definition mandate AND execution mandate. AC-16 compliance depends on internal architectural separation — distinct domain services, policy objects, or command partitioning within the aggregate's consistency boundary.

**AC-16 assessment:** The single aggregate's consistency boundary encompasses both scope definition commands and execution commands. AC-16 requires distinct authority boundaries; a consistency-boundary distinction within one aggregate is not an authority boundary in the constitutional sense. An execution actor (a member of AuditExecutionAuthority's authorized command set) can, in principle, issue scope modification commands through the same aggregate interface. The boundary is enforced by authorization rules, not by constitutional architecture. This converts AC-16 from a structural guarantee into a governance discipline requirement.

**AC-02 assessment:** If the unified AuditAuthority processes both "define scope" and "verify against scope" commands, the same aggregate holds the reference (scope definition) and the evaluation against that reference (execution). This is structurally analogous to the DataChecksum circularity identified in ADR-3 (VO-2 cannot serve as AC-31 reference standard because it is within the election system boundary being audited). Here, the scope definition is within the same aggregate boundary as the execution — a self-reference risk.

**ET-03 advantage:** No second aggregate means no ET-03 recursion. A unified AuditAuthority needs only one L-1/L-5 profile.

**Verdict:** AC-16 not structurally satisfied. AC-02 scope self-reference risk not eliminated. ET-03 avoidance is insufficient justification when AC-16 and AC-02 compliance are both compromised. Rejected.

#### Option B — Separated AuditScopeAuthority + AuditExecutionAuthority

Two distinct aggregates with independent constitutional authority boundaries.

**AC-16 assessment:** Structurally satisfied. AuditScopeAuthority's consistency boundary is separate from AuditExecutionAuthority's consistency boundary. No command issued to AuditExecutionAuthority can cross the aggregate boundary to modify AuditScopeAuthority's mandate. The authority boundary is architectural, not merely governed.

**AC-02 assessment:** The scope definition function (AuditScopeAuthority) and the verification function (AuditExecutionAuthority) are in separate authority domains. The reference used for execution verification (the expected evidence set) is outside the execution authority's control. This is the correct structural analogue to ADR-3's AC-31 requirement for an independent reference standard — the scope is the constitutionally independent reference for completeness verification.

**ET-03 assessment:** Two aggregates require two L-1/L-5 profiles. AuditScopeAuthority needs its own L-1/L-5. This is the ET-03 risk. Resolution is analyzed in C.2.

**Verdict:** Structurally satisfies AC-16 and AC-02. ET-03 risk is resolvable (see C.2). Subject to ET-03 resolution, this is the constitutionally sound option.

#### Option C — Constitutional Scope Specification

Scope is embedded in ElectionConstitution as a constitutional specification — a value expression, not a separate aggregate. AuditExecutionAuthority reads the constitutional specification directly. AuditScopeAuthority as a named aggregate does not exist.

**AC-16 assessment:** Satisfied. The scope resides in ElectionConstitution; AuditExecutionAuthority cannot modify ElectionConstitution. The boundary between scope and execution is as strong as the constitutional amendment process.

**AC-02 assessment:** Satisfied. ElectionConstitution is fully outside AuditExecutionAuthority's control.

**AC-30 precision problem:** ElectionConstitution is a governance instrument. It must specify the expected evidence set with sufficient precision to be operationally auditable (specific record types, counts, formats, per-election characteristics). A constitutional document may specify evidence categories ("all votes must be auditably recorded") but cannot practically enumerate election-specific expected evidence sets with AC-30 precision for each election variation. Constitutional documents are not data schemas.

**Operational rigidity problem:** The expected evidence set may differ across elections (ballot structure, candidate count, regional distribution). If the expected evidence set is embedded in ElectionConstitution, any election-specific change requires constitutional amendment. Constitutional amendments have high procedural barriers by design. This creates operational brittleness for what may be election-specific configuration, not constitutional revision.

**ET-03 avoidance:** Option C avoids ET-03 entirely. But ET-03 avoidance does not justify AC-30 precision gaps and operational brittleness.

**Verdict:** Constitutional grounding is strongest, but AC-30 precision gap and operational rigidity are disqualifying for the full expected evidence set. Option C may inform Tier 1 in the OQ-ADR4-01 analysis (Part D) — constitutional categories — but is insufficient as the complete architecture. Not selected as primary architecture.

### C.2 ET-03 Resolution — AuditScopeAuthority Legitimacy Analysis

ADR-4 proceeds with Option B. This requires demonstrating that AuditScopeAuthority's L-1/L-5 can be constitutionally grounded without creating a new D43 gap.

**D43 threshold test for AuditScopeAuthority:**

Is there an authority dimension that is undefined, unchallenged, or self-referential?

**AuditScopeAuthority legitimacy profile:**

| Dimension | Candidate Source | Assessment |
|---|---|---|
| L-1 (Legitimacy Source) | ElectionConstitution — AUDIT Option D provision | Established by ADR-2; same L-1 as AuditExecutionAuthority; L-1 = DEFINED |
| L-2 (Authority Holder) | Constitutionally designated scope authority body; named in ElectionConstitution | Must not be self-declared; ElectionConstitution must name the body — CONDITIONALLY DEFINED |
| L-3 (Challenge Mechanism) | Constitutional challenge pathway | Scope specifications challengeable through constitutional process (AC-09 applies to all authority functions); challenge reception must be independent of AuditScopeAuthority — CONDITIONALLY DEFINED |
| L-4 (Revocation Mechanism) | Constitutional process only | Scope mandate revocable only through constitutional process, not by election system or AuditExecutionAuthority (AC-11/AC-12) — CONDITIONALLY DEFINED |
| L-5 (Succession Mechanism) | Constitutional succession provision | Explicit succession required when scope authority body ceases to exist (AC-13) — CONDITIONALLY DEFINED |

**ET-03 resolution:**

AuditScopeAuthority does NOT trigger a new D43 gap IF:
1. ElectionConstitution names the scope authority body (L-2 — not self-declared)
2. ElectionConstitution provides challenge, revocation, and succession mechanisms for the scope authority (L-3/L-4/L-5)

Both conditions are constitutionally achievable. The same constitutional ratification that creates AuditScopeAuthority's mandate can define all five dimensions. The scope authority is constitutionally constituted, not self-constituting.

**ET-03 finding:** ET-03 is RESOLVED as a separate authority relationship within the AUDIT D43 instance — not as a specialization of AuditExecutionAuthority, and not as a new D43 instance. AuditScopeAuthority requires its own L-1/L-5, but all dimensions are groundable in ElectionConstitution without creating new undefined authority. ET-03 does not become a new D43 gap.

**OBS-ADR3-01 resolution for AuditScopeAuthority:** The ET-03 resolution IS the OBS-ADR3-01 resolution for AuditScopeAuthority. AuditScopeAuthority is an independently governed reference standard for the Completeness Stratum. Its legitimacy chain is established by the L-1/L-5 profile above. OBS-ADR3-01 is satisfied: the existence of AuditScopeAuthority as an independent standard does not itself grant constitutional legitimacy — the L-1/L-5 chain does.

**Condition binding on ADR-7:** ElectionConstitution MUST provide explicit L-2 designation AND L-3/L-4/L-5 mechanisms for AuditScopeAuthority. If ElectionConstitution cannot constitutionally carry this designation, ET-03 becomes an active D43 gap and this ADR-4 selection requires revision. ADR-7 (GovernanceState boundary) must verify this condition is satisfiable.

### C.3 Selection — CPR-02: Option B (Separated)

**Selected:** Option B — AuditScopeAuthority and AuditExecutionAuthority as two distinct authority aggregates.

**Constitutional basis:**
1. AC-16 requires distinct constitutional authority boundaries between scope definition and execution; only Option B satisfies this structurally — not through governance discipline but through aggregate boundary
2. AC-02 scope self-reference risk is eliminated: AuditExecutionAuthority cannot modify its own scope reference (separate consistency boundary)
3. ADR-3's Completeness Stratum maps cleanly: AuditScopeAuthority holds the constitutional authority for the Completeness Stratum definition; AuditExecutionAuthority holds the authority for Presence Stratum assessment against that definition
4. ET-03 is resolved (C.2): AuditScopeAuthority legitimacy is constitutionally groundable; OBS-ADR3-01 is satisfied by the L-1/L-5 chain
5. This decision closes the ADR-2 E.4 CANDIDATE: the split was carried forward from ADR-2 as pending; ADR-4 confirms it. AuditScopeAuthority and AuditExecutionAuthority are confirmed as two distinct aggregates. There is no unified AuditAuthority.

**ADR-1 consequence:** The ADR-1 candidate aggregate "AuditAuthority" is refined into two named aggregates: AuditScopeAuthority and AuditExecutionAuthority. The authority map gains one additional aggregate (six total, up from five). OBS-36D-02-1 governs: authority distribution ≠ bounded context distribution. These two aggregates may exist within the same bounded context — their separation is an authority map distinction, not necessarily a context boundary.

---

## Part D — OQ-ADR4-01: Expected Evidence Set Specification Form

### D.1 Three Forms Analysis

#### Form A — Fully Constitutional

ElectionConstitution directly and completely enumerates the expected evidence set. AuditScopeAuthority, if it exists, reads from ElectionConstitution and produces no additional specification. The expected evidence set IS ElectionConstitution's enumeration.

**Constitutional grounding:** Strongest. AC-14, AC-17, and AC-14 precision are all satisfied directly by the constitutional instrument.

**AC-30 precision assessment:** Adequate for fundamental, election-type-invariant evidence requirements ("ballot records must exist," "governance transition records must exist"). Inadequate for election-specific evidence requirements that vary by ballot structure, candidate count, or regional distribution. ElectionConstitution is not a data schema; it specifies mandates, not operational enumeration.

**Change management:** Any revision to the expected evidence set requires constitutional amendment. This is constitutionally appropriate for fundamental evidence requirements. It creates amendment overhead for election-specific operational variation that may not warrant constitutional revision.

**Verdict:** Necessary but insufficient as the complete architecture. Appropriate for constitutional categories. Insufficient for election-specific precision. Partial — informs Tier 1 in Form B.

#### Form B — Constitutionally Delegated Specification

ElectionConstitution mandates constitutional evidence categories (what types of evidence must exist for any election governed by this constitution) and designates AuditScopeAuthority as the authority to produce the concrete expected evidence set for each election.

AuditScopeAuthority holds the delegated authority to specify: "For Election X of type Y with ballot structure Z, the expected evidence set is [specific, operationally precise enumeration]."

**Constitutional grounding:**
- AC-14: AuditScopeAuthority is constitutionally outside the election system (Option B confirmed; ET-03 resolved)
- AC-17: AuditScopeAuthority IS the mechanism required by AC-17 for externally defining the expected evidence set
- The delegation is constitutionally grounded: ElectionConstitution authorizes the delegation; AuditScopeAuthority cannot exceed the constitutional mandate (Tier 1 categories bound Tier 2 specifications)

**AC-30 precision:** The Tier 2 specification (produced by AuditScopeAuthority) can achieve operational precision — specific record types, counts, formats, election-specific characteristics. Constitutional categories are precise at the mandate level; delegated specification achieves precision at the operational level.

**OBS-ADR3-01 compliance:** AuditScopeAuthority's legitimacy chain (L-1/L-5) is the legitimacy chain for the Tier 2 specification. OBS-ADR3-01 is satisfied by ET-03 resolution.

**ADR3-INV-01 risk:** Low. AuditScopeAuthority produces Tier 2 from Tier 1 (constitutional categories), not from observing what is present. The derivation direction is ElectionConstitution → AuditScopeAuthority → expected evidence set. Completeness-by-Presence collapse is not structurally available — AuditScopeAuthority does not read from the Presence Stratum.

**Change management:** Fundamental evidence categories require constitutional amendment. Election-specific scope specifications are produced by AuditScopeAuthority under its delegated authority — no amendment required for election-specific adaptation, subject to L-3 challenge.

**Verdict:** Best-fit with the constitutional structure. Achieves AC-30 precision at the operational level. Enables election-specific adaptation within constitutional bounds. Requires AuditScopeAuthority's ET-03-resolved L-1/L-5. Recommended.

#### Form C — Versioned Audit Profile

AuditScopeAuthority maintains a versioned, updateable audit profile. ElectionConstitution sets minimum evidence requirements; the audit profile can evolve beyond minimums as audit practice develops. Profile versions may be updated between elections.

**Constitutional grounding:** Weakest. The profile update authority is ambiguous. If AuditScopeAuthority can update its own profile without external authorization, this is a form of scope self-definition — the same authority that defines what must be present also decides when and how that definition changes. If a separate body authorizes profile updates, that body requires its own L-1/L-5, extending the governance chain.

**CF-05-08 implication:** Behavioral integrity (profile updates follow a defined process) is not constitutional legitimacy (the update authority has grounded L-1/L-5). A well-managed versioned profile is behaviorally robust but constitutionally indeterminate without resolving the update authorization chain.

**ADR3-INV-01 risk:** Present. If the versioned profile can include or exclude evidence types based on operational observations, there is a structural risk that "what the profile says must exist" tracks "what is observable" rather than "what constitutionally must exist" — a Completeness-by-Presence collapse in the profile maintenance process, not in a single assessment.

**Verdict:** Constitutional grounding of profile updates is insufficiently resolved. ADR3-INV-01 risk present in profile maintenance. Not selected.

### D.2 Selection — OQ-ADR4-01: Form B (Constitutionally Delegated Specification)

**Selected:** Form B — Constitution + Delegated Specification (Two-Tier Architecture)

**Constitutional basis:**
1. Form A is constitutionally strongest but insufficient for election-specific precision (AC-30 gap for operational requirements)
2. Form C has insufficient constitutional grounding for scope updates and ADR3-INV-01 maintenance risk
3. Form B achieves constitutional grounding via ElectionConstitution Tier 1 + operational precision via AuditScopeAuthority Tier 2; ET-03 resolution satisfies OBS-ADR3-01

**Two-Tier Architecture:**

```
Tier 1 — Constitutional Categories (in ElectionConstitution):

  "For all elections governed by this constitution, the following
  evidence categories must exist for a constitutionally complete audit:
  [constitutional enumeration of evidence types — mandate level]"

  This tier cannot change without constitutional amendment.
  Establishes the constitutional minimum.
  Constitutional source: ElectionConstitution — L-1 for AuditScopeAuthority.

Tier 2 — Delegated Specification (produced by AuditScopeAuthority):

  "For Election X with ballot structure Y and candidate set Z,
  the specific expected evidence set is: [operational enumeration —
  specific record types, counts, formats, distribution structure]"

  Produced by AuditScopeAuthority under delegated constitutional authority.
  Must satisfy Tier 1 constitutional categories — Tier 2 cannot exclude
  any Tier 1 category without constitutional amendment.
  Election-specific adaptation without constitutional amendment.
  Subject to L-3 challenge (AC-09).
```

---

## Part E — Aggregate Constitutional Profiles

### E.1 AuditScopeAuthority — Constitutional Profile

**Function:** Holds the constitutional mandate to define the expected evidence set for each election. Produces Tier 2 specifications derived from ElectionConstitution's Tier 1 categories. Publishes the expected evidence set as the constitutional reference for the Completeness Stratum (ADR-3 three-stratum model, Link 3).

**OBS-ADR3-01 compliance:** AuditScopeAuthority is an independently governed reference standard for the Completeness Stratum. Its constitutional legitimacy is established by the L-1/L-5 profile below — the existence of independence does not itself establish legitimacy; the legitimacy chain does. This is the first concrete instantiation of OBS-ADR3-01 in the architecture.

**Legitimacy profile (ET-03 resolved):**

| Dimension | Resolution | Condition |
|---|---|---|
| L-1 (Legitimacy Source) | ElectionConstitution — AUDIT Option D provision, Tier 1 evidence categories | DEFINED — established by ADR-2 AUDIT selection |
| L-2 (Authority Holder) | Constitutionally designated scope authority body; named in ElectionConstitution | CONDITIONALLY DEFINED — ElectionConstitution must name the body; self-declaration is unconstitutional |
| L-3 (Challenge Mechanism) | Constitutional challenge pathway for scope specifications; challenge reception independent of AuditScopeAuthority | CONDITIONALLY DEFINED — ADR-5 must design the challenge pathway |
| L-4 (Revocation Mechanism) | Constitutional process only; not revocable by election system or AuditExecutionAuthority | CONDITIONALLY DEFINED — constitutional instrument must provide |
| L-5 (Succession Mechanism) | Constitutional succession provision for when scope authority body ceases to exist | CONDITIONALLY DEFINED — constitutional instrument must provide |

**Authority boundary:**
- AuditScopeAuthority MAY: define, publish, and revise the Tier 2 expected evidence set within Tier 1 constitutional categories
- AuditScopeAuthority MUST NOT: evaluate whether the expected evidence set is actually present (Presence Stratum — AuditExecutionAuthority function; AC-16)
- AuditScopeAuthority MUST NOT: define scope that excludes evidence categories required by Tier 1 (AC-30)
- AuditScopeAuthority MUST NOT: accept instructions from AuditExecutionAuthority regarding scope definition (AC-16)
- AuditScopeAuthority MUST NOT: read from the Audit Context (Presence Stratum) to derive what must be present (ADR3-INV-01)

**Domain events (candidates for Round 38+):**
- EvidenceScopePublished: AuditScopeAuthority produces a new Tier 2 expected evidence set for a specific election
- EvidenceScopeRevised: AuditScopeAuthority revises an existing specification within delegated Tier 2 authority
- EvidenceScopeChallenged: A constitutional challenge to a scope specification has been received (L-3 input)
- EvidenceScopeRevoked: The current scope specification is revoked through constitutional process (L-4)

### E.2 AuditExecutionAuthority — Constitutional Profile (Updated from ADR-2)

**Function (inherited from ADR-2 AUDIT Option D):** Holds the mandate to execute audit verification — comparing the Presence Stratum against the Completeness Stratum's expected evidence set (from AuditScopeAuthority) and, independently, verifying the Presence Stratum against the Authenticity Stratum's independent reference standard (from ADR-3 AC-31).

**ADR-4 additions to ADR-2 profile:**
- AuditExecutionAuthority must have constitutional access to AuditScopeAuthority's published Tier 2 specification; this access is read-only — AuditExecutionAuthority cannot produce, modify, or challenge the specification through the execution authority channel (AC-16)
- Completeness comparison is a distinct execution function: AuditExecutionAuthority receives the expected evidence set from AuditScopeAuthority and compares against the Audit Context (Presence Stratum); it does not produce or modify the expected evidence set

**ADR-3 inheritance (retained):** AuditExecutionAuthority requires constitutional access to the Authenticity Stratum's independent reference standard (AC-31 / VO-3 candidate — Round 38+). This function is separate from the completeness comparison function (ADR3-INV-01: Presence ≠ Authenticity).

**AC-16 enforcement:** AuditExecutionAuthority holds no scope definition authority. A command to "update the expected evidence set" issued to AuditExecutionAuthority must be rejected at the aggregate boundary — it belongs exclusively to AuditScopeAuthority's authority domain.

### E.3 Authority Interaction Model

```
Authority Interaction — Completeness Stratum:

ElectionConstitution
  │
  │  Tier 1: constitutional evidence categories
  │  (L-1 for AuditScopeAuthority)
  ▼
AuditScopeAuthority
  │  produces (within Tier 1 bounds)
  │  Tier 2: election-specific expected evidence set
  │
  │  publish / read-only access ──────────────────────────┐
  ▼                                                       │
AuditExecutionAuthority ◄──────────────────────────────────┘
  │  reads expected evidence set (Tier 2)
  │  compares against Audit Context (Presence Stratum)
  │
  ▼
Completeness Assessment Result
(Gap A-3 operationally closed for a given election)

AC-16 boundary:
  AuditExecutionAuthority CANNOT instruct AuditScopeAuthority
  to modify the expected evidence set.
  Interaction is ONE-WAY (scope → execution; never execution → scope).
```

---

## Part F — Completeness Stratum Closure

### F.1 Link 3 Established

With CPR-02 Option B and OQ-ADR4-01 Form B selected, the Completeness Stratum dependency chain is complete:

```
Link 1 (ADR-3 — ESTABLISHED):
    Completeness Stratum EXISTS as a constitutional requirement.
    Gap A-3 identified; architectural resolution required.

Link 2 (ADR-2 — ESTABLISHED):
    ElectionConstitution is the constitutional source (L-1)
    for the Completeness Stratum scope mandate.
    AUDIT Option D: constitutional scope via ElectionConstitution.

Link 3 (ADR-4 — THIS DOCUMENT):
    AuditScopeAuthority is the aggregate that holds the
    delegated constitutional authority to define the
    election-specific expected evidence set (Tier 2).
    AuditScopeAuthority's L-1: ElectionConstitution.
    AuditScopeAuthority's L-2/L-5: constitutionally grounded
    (ET-03 resolved; OBS-ADR3-01 satisfied).
    AuditExecutionAuthority receives the expected evidence set
    for completeness comparison.

Completeness Stratum dependency chain: COMPLETE
```

### F.2 Gap A-3 Constitutional Resolution

Gap A-3: "Expected evidence set is undefined."

**Constitutional resolution with Link 3 established:**
- Tier 1 (ElectionConstitution): constitutional evidence categories defined at the mandate level; Gap A-3 closed at the constitutional level for any election type governed by this constitution
- Tier 2 (AuditScopeAuthority): election-specific expected evidence set produced under delegated constitutional authority; Gap A-3 closed at the operational level for each specific election
- AuditExecutionAuthority: receives Tier 2 for completeness comparison; Gap A-3 operational use closed

**Gap A-3 status after ADR-4:** The constitutional architecture for Gap A-3 closure is complete across all three links. Gap A-3 is constitutionally resolvable.

**What remains (Round 38+ governance):**
- The actual content of Tier 1 (ElectionConstitution's evidence categories — governance practice, not architecture)
- The actual content of Tier 2 (AuditScopeAuthority's election-specific specifications — operational design)
- AuditScopeAuthority's L-2 designation in ElectionConstitution — governance design
- The access pathway mechanism between AuditScopeAuthority and AuditExecutionAuthority — implementation

### F.3 What ADR-4 Closes / What Remains Open

**ADR-4 closes:**
- Completeness Stratum dependency chain Link 3
- CPR-02: Option B (separated aggregates) confirmed; ADR-2 E.4 CANDIDATE resolved
- ET-03: NOT a D43 instance; AuditScopeAuthority is constitutionally groundable
- OBS-ADR3-01: instantiated and satisfied for AuditScopeAuthority's legitimacy chain
- AC-16 compliance model: structural separation via distinct aggregates
- OQ-ADR4-01: Form B (constitutionally delegated, two-tier architecture)

**Remains open (carried to subsequent ADRs):**
- AuditScopeAuthority L-2 designation specificity: how specific must ElectionConstitution be? (ADR-7)
- AuditScopeAuthority L-3 challenge pathway: who may challenge and through what body? (ADR-5)
- AuditScopeAuthority L-4/L-5 design: (ADR-7)
- Tier 1 content (constitutional evidence categories): governance practice — Round 38+
- Tier 2 specification design and publication mechanism: Round 38+
- AuditExecutionAuthority access pathway to AuditScopeAuthority publication: Round 38+
- ElectionConstitution concentration update (6 aggregates): ADR-7
- CertificationAuthority handling of scope specification in certification evidence: ADR-6

---

## Part G — Cross-Concern Analysis

### G.1 ADR3-INV-01 Compliance

The selected design (Option B + Form B) must satisfy ADR3-INV-01: no stratum may be satisfied by another.

| Invariant Collapse Form | Design Assessment |
|---|---|
| Completeness ← Presence | AuditScopeAuthority derives Tier 2 from ElectionConstitution Tier 1 (downward from constitution), not from observing the Audit Context (Presence). The derivation direction is constitutionally defined, not presence-driven. **SATISFIED** |
| Presence ← Authenticity | AuditExecutionAuthority compares Presence Stratum against Completeness specification separately from authenticity verification. ADR3-INV-01 structurally prohibits treating confirmed-authentic records as a sufficient proxy for complete records. **SATISFIED** |
| Authenticity ← Completeness | AuditScopeAuthority's scope definition does not assert anything about record authenticity; it asserts what must exist. The Authenticity Stratum requires its own independent reference standard (ADR-3 AC-31). **SATISFIED** |

**ADR3-INV-01: SATISFIED by design.**

### G.2 ElectionConstitution Concentration Impact

ADR-4 extends the ElectionConstitution concentration established in ADR-2 H.3.1.

| Prior state (ADR-2) | Five authority aggregates (EnrollmentAuthority, CriteriaAuthority, AuditExecutionAuthority, GovernanceAuthority, CertificationAuthority) with ElectionConstitution as shared L-1 |
|---|---|
| ADR-4 addition | AuditScopeAuthority with ElectionConstitution as L-1 → sixth aggregate |
| Additional content dependency | Tier 1 evidence categories reside in ElectionConstitution — not only an authority dependency but a content dependency |

**Total constitutional collapse scenario (updated):** If ElectionConstitution ratification fails:
- All six authority aggregates lose L-1 simultaneously (five in ADR-2 → six in ADR-4)
- Tier 1 constitutional evidence categories become undefined — Gap A-3 re-emerges at the constitutional level
- AuditScopeAuthority cannot produce Tier 2 specifications without Tier 1 categories
- Completeness Stratum becomes structurally unusable

**ADR-7 inheritance (critical):** Must update the total constitutional collapse analysis from ADR-2 H.3.1 to include AuditScopeAuthority and Tier 1 content dependency. Must assess whether ElectionConstitution can constitutionally carry six authority designations plus Tier 1 content without creating governance concentration that itself becomes an unconstitutional chokepoint.

### G.3 AC-16 Compliance Structural Verification

| Requirement | Verification |
|---|---|
| Scope authority has distinct constitutional boundary | AuditScopeAuthority aggregate: independent consistency boundary, independent command set, independent L-1/L-5 profile |
| Execution authority has distinct constitutional boundary | AuditExecutionAuthority aggregate: independent consistency boundary, independent command set |
| No write authority from execution to scope | AuditExecutionAuthority publishes no commands to AuditScopeAuthority; access is read-only to published Tier 2 specification |
| No scope modification from execution channel | AuditScopeAuthority's consistency boundary rejects commands from AuditExecutionAuthority's authority domain |

**AC-16: STRUCTURALLY SATISFIED.**

### G.4 Relationship to ADR-3 Three-Stratum Model

| Stratum | Constitutional Authority Owner | Source Chain |
|---|---|---|
| Completeness (what must exist) | AuditScopeAuthority | ElectionConstitution Tier 1 → AuditScopeAuthority Tier 2 |
| Presence (what does exist) | Audit Context (existing domain model) | VoteRecorded events accessible to AuditExecutionAuthority |
| Authenticity (are records unmodified) | Independent reference standard (AC-31) | VO-3 candidate — Round 38+; independent of election system |

Each stratum has a constitutionally distinct owner. ADR3-INV-01 satisfied by design.

---

## Part H — Consequences for Subsequent ADRs

### H.1 ADR-5: Challenge Architecture

New requirements from ADR-4:

- **AuditScopeAuthority L-3 (scope challenge pathway):** Who may challenge a scope specification? What constitutional process governs? Challenge reception must be independent of AuditScopeAuthority (AC-09). This is a new ADR-5 design requirement not present in the ADR-3 → ADR-5 inheritance.
  - Specific challenge questions: Can voters challenge a scope specification as too narrow (Gap A-3 risk)? Can the election body challenge a specification as too broad? Who adjudicates?
  - ADR-5 must answer without reopening CPR-02 or OQ-ADR4-01

- **AuditExecutionAuthority L-3 (execution finding challenge pathway):** Carried from ADR-3; completeness assessment findings must be challengeable; authenticity findings must be challengeable

- **GOV-AUTH challengeability question (carried from ADR-2):** Still active; ADR-5 addresses

- **CT-1 conditional (carried from ADR-3):** Active if Authenticity Stratum implementation uses cryptographic commitment; ADR-5 must assess

### H.2 ADR-6: Certification Architecture

New requirements from ADR-4:

- CertificationAuthority must verify that AuditScopeAuthority produced a valid Tier 2 specification for the election being certified — certification without scope verification creates a Completeness Stratum gap (Gap A-3 would re-emerge at certification level)
- The scope specification (AuditScopeAuthority's Tier 2 publication) must be part of the certification evidence package
- CertificationAuthority must independently access AuditScopeAuthority's published specification — it cannot rely solely on AuditExecutionAuthority's report of the scope used (R4 prohibition pattern from ADR-3 applied to the scope chain: CertificationAuthority may not accept AuditExecutionAuthority's report of what scope was used as constitutionally sufficient verification of scope validity)
- ADR-6 must determine whether CertificationAuthority accesses AuditScopeAuthority directly or through a constitutional publication mechanism

### H.3 ADR-7: GovernanceState Boundary

New requirements from ADR-4 (critical):

- **Six-aggregate concentration update:** ADR-2 H.3.1 total collapse analysis must include AuditScopeAuthority as the sixth aggregate with ElectionConstitution as L-1
- **Tier 1 content dependency:** ElectionConstitution must carry both authority designations (L-2 for six aggregates) and Tier 1 evidence categories — assess whether this is constitutionally viable or creates a precision-governance conflict
- **ET-03 verification condition:** ElectionConstitution must provide AuditScopeAuthority with L-3/L-4/L-5 mechanisms; ADR-7 must verify this is achievable within the GovernanceState model
- **L-2 designation specificity (OQ-37-04-03 below):** How specifically must ElectionConstitution name the scope authority body?
- **AC-30 and Tier 1:** ADR-7 must assess whether the GovernanceState model can represent Tier 1 constitutional evidence categories with sufficient AC-30 precision while remaining a governance instrument rather than a data schema

---

## Part I — Comparative Option Matrices

### I.1 CPR-02 Option Matrix

| Option | AC-16 Structural | AC-02 Scope Self-Ref | ET-03 | AC-30 Precision | Selected |
|---|---|---|---|---|---|
| A (Unified) | Not structural (governance discipline) | Self-reference risk present | Avoided (at AC-16 cost) | Depends on internal design | REJECTED |
| B (Separated) | Structural (distinct aggregate boundaries) | Eliminated by separation | Resolved — see C.2 | Achieved via Form B Tier 2 | **SELECTED** |
| C (Constitutional Specification) | Structural (ElectionConstitution immutable) | Eliminated | Avoided | Gap for election-specific requirements | Not selected (partial only) |

### I.2 OQ-ADR4-01 Form Matrix

| Form | Constitutional Grounding | AC-30 Precision | Operational Flexibility | ADR3-INV-01 Risk | OBS-ADR3-01 | Selected |
|---|---|---|---|---|---|---|
| A (Fully Constitutional) | Strongest | Insufficient for election-specific | Lowest (amendment required for any change) | Low | N/A (no separate body) | Not selected (insufficient) |
| B (Constitutionally Delegated) | Strong (ElectionConstitution Tier 1 + AuditScopeAuthority L-1/L-5) | Highest (Tier 2 operational precision) | Balanced (Tier 1 = amendment; Tier 2 = AuditScopeAuthority authority) | Low | Satisfied by ET-03 resolution | **SELECTED** |
| C (Versioned Profile) | Weakest (update authority governance gap) | Variable | Highest | Present (maintenance-level collapse risk) | Insufficient (update authority lacks grounding) | Not selected |

### I.3 Authority Aggregate Map Update

| ADR-1 Candidate | ADR-2 Resolution | ADR-4 Refinement |
|---|---|---|
| EnrollmentAuthority | Option B — Committee | Unchanged |
| CriteriaAuthority | Option B — Committee | Unchanged |
| AuditAuthority (unified) | CANDIDATE: split pending ADR-4 | **Split confirmed: AuditScopeAuthority + AuditExecutionAuthority** |
| → AuditScopeAuthority | — | NEW: constitutionally delegated scope authority; Form B Tier 2; ET-03 resolved |
| → AuditExecutionAuthority | Option D — Independent execution | CONFIRMED: distinct aggregate; AC-16 structurally satisfied |
| GovernanceAuthority | Option B — Committee | Unchanged |
| CertificationAuthority | Option C — External Organization | Unchanged (ADR-6 addresses) |

**Total:** Six authority aggregates (five in ADR-1 → six confirmed in ADR-4). OBS-36D-02-1: authority map ≠ bounded context map.

---

## Section — ARB Decision Block

**[SUBMITTED FOR ARB REVIEW]**

### Decisions Made in This ADR

1. **CPR-02 → Option B (Separated) CONFIRMED:** AuditScopeAuthority and AuditExecutionAuthority are two distinct authority aggregates. The ADR-2 E.4 CANDIDATE is closed. AC-16 is structurally satisfied via distinct aggregate boundaries. AC-02 scope self-reference risk is eliminated.

2. **ET-03 RESOLVED — Not a D43 instance:** AuditScopeAuthority requires its own L-1/L-5 (confirmed), but all five dimensions are constitutionally groundable in ElectionConstitution without creating new undefined authority. ET-03 becomes a separate authority relationship within the AUDIT D43 instance, not a new D43 gap. Condition: ElectionConstitution must name the scope authority body (L-2) and provide L-3/L-4/L-5 mechanisms. ADR-7 must verify.

3. **OBS-ADR3-01 FIRST INSTANTIATION:** AuditScopeAuthority is the first concrete application of OBS-ADR3-01 in the architecture. AuditScopeAuthority as an independently governed reference standard for the Completeness Stratum has its constitutional legitimacy established by the ET-03-resolved L-1/L-5 profile — existence of independence ≠ constitutional legitimacy; the L-1/L-5 chain establishes it.

4. **OQ-ADR4-01 → Form B (Constitutionally Delegated) SELECTED:** Two-Tier Architecture — Tier 1 (constitutional evidence categories in ElectionConstitution; mandate level; constitutional amendment required for change) + Tier 2 (election-specific expected evidence set produced by AuditScopeAuthority under delegated authority; operationally precise; subject to L-3 challenge).

5. **Completeness Stratum Dependency Chain — Link 3 ESTABLISHED:** AuditScopeAuthority holds Tier 2 specification authority; AuditExecutionAuthority receives the expected evidence set for completeness comparison. Gap A-3 is constitutionally resolvable through the three-link dependency chain.

6. **ADR-1 Authority Inventory Updated:** AuditAuthority (ADR-1 candidate) refined into AuditScopeAuthority + AuditExecutionAuthority. Authority map: six aggregates. OBS-36D-02-1: authority map ≠ bounded context map.

7. **AC-16 Compliance Model — Structural:** Distinct aggregate boundaries enforce AC-16 structurally. Interaction is one-way: AuditExecutionAuthority reads AuditScopeAuthority's published Tier 2 specification; no command or authority crosses from execution to scope definition.

8. **ADR3-INV-01 SATISFIED:** Each stratum has a constitutionally distinct owner. AuditScopeAuthority (Completeness), Audit Context (Presence), AC-31 independent reference (Authenticity). No stratum is satisfied by another by design.

9. **ElectionConstitution Concentration Extended:** Six authority aggregates (five in ADR-2 → six in ADR-4) share ElectionConstitution as L-1. Tier 1 content dependency is an additional concentration factor. ADR-7 must update the total constitutional collapse analysis.

### Open Questions Carried to ARB

**OQ-37-04-01: Does AuditScopeAuthority's Tier 2 specification publication require constitutional evidence integrity?**

If AuditScopeAuthority publishes a Tier 2 specification for Election X, and that specification is later challenged, what constitutional evidence exists that the specification was published as stated and was not modified between publication and audit execution? Does AuditScopeAuthority's publication require the same evidence integrity considerations that ADR-3 established for election evidence — a Completeness/Presence/Authenticity structure for the scope specification itself? This is a potential meta-level application of the three-stratum model.

**OQ-37-04-02: Is the Tier 1 / Tier 2 boundary itself constitutionally adjudicable?**

ElectionConstitution provides Tier 1 categories; AuditScopeAuthority produces Tier 2 specifications. Who determines whether a specific Tier 2 item satisfies Tier 1 constitutional mandate or exceeds delegated authority? Is this a constitutional interpretation question (L-3 challenge mechanism — ADR-5 to design) or an architectural design question requiring a separate adjudication body? This question may require ADR-5 and ADR-7 to coordinate.

**OQ-37-04-03: AuditScopeAuthority L-2 designation specificity**

ET-03 is resolved only if ElectionConstitution names the scope authority body. How specific must this designation be? Can ElectionConstitution designate a role ("the designated audit scope committee established by the governance authority") rather than a specific organization? Or does constitutional legitimacy for L-2 require naming a specific body independent of GovernanceAuthority? This question intersects with the concentration analysis: if AuditScopeAuthority's L-2 is designated by GovernanceAuthority, is AuditScopeAuthority constitutionally independent of the governance structure it is meant to be outside of?

### Decisions Deferred to Subsequent ADRs

| Decision | Deferred to |
|---|---|
| AuditScopeAuthority L-2 designation specificity | ADR-7 (GovernanceState boundary) |
| AuditScopeAuthority L-3 challenge pathway | ADR-5 (Challenge Architecture) |
| AuditScopeAuthority L-4/L-5 design | ADR-7 |
| Tier 1 content (constitutional evidence categories) | Governance practice — Round 38+ |
| Tier 2 specification and publication mechanism | Round 38+ |
| AuditExecutionAuthority access pathway to Tier 2 | Round 38+ |
| Total constitutional collapse analysis (6 aggregates) | ADR-7 |
| CertificationAuthority + scope specification in certification evidence | ADR-6 |
| OQ-37-04-01: Tier 2 publication evidence integrity | ADR-5 or ADR-6 (TBD) |
| OQ-37-04-02: Tier 1/Tier 2 adjudication | ADR-5 + ADR-7 coordination |
| OQ-37-04-03: L-2 designation specificity | ADR-7 |

### Authorization Requested

**ADR-5: Challenge Architecture (EC-01, AC-09/10)**

ADR-5 now has three challenge pathway design requirements:
1. AuditScopeAuthority L-3 (scope specification challenge — new from ADR-4)
2. AuditExecutionAuthority L-3 (execution finding challenge — carried from ADR-3)
3. GOV-AUTH challengeability (carried from ADR-2)

CT-1 conditional (ADR-3), VO-3/EC-01 interaction (ADR-3), and OQ-37-04-01/02 coordination questions are also ADR-5 inputs.

ADR-5 is authorized pending ADR-3 (APPROVED) and ADR-4 (this document, submitted for ARB review).

---

*Round 37-04 — ADR-4: Audit Scope Authority Structure — SUBMITTED FOR ARB REVIEW*
*Research Program: NRNA DDD Trustworthiness*
*Document: Round37-04_ADR-4_Audit_Scope_Authority_Structure.md*
*Predecessors: ADR-1, ADR-2, ADR-3 — APPROVED*
*Successors pending ARB: ADR-5*
