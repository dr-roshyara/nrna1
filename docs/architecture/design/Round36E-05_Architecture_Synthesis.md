# Round 36E-05 — Architecture Synthesis

**Program:** NRNA DDD Trustworthiness Research Program  
**Series:** 36E — Architecture Impact Assessment  
**Sub-Round:** 36E-05 of 05  
**Status:** APPROVED WITH REQUIRED REVISIONS — APPROVED  
**Governing Question:** Given the Architecture Option Catalog produced in 36E-04, what coherent option combinations exist, what are their constitutional dependencies, and in what sequence should Round 37 ADRs be authored?

**Predecessors:**
- 36E-01: 30 Architectural Constraints — APPROVED
- 36E-02: Constraint Interaction Analysis — APPROVED
- 36E-03: DDD Impact Assessment — APPROVED
- 36E-04: Architecture Option Evaluation — APPROVED

**Binding Guardrail:** This round synthesizes options into families and sequences. This round does NOT select options. Recommended combination ≠ final architecture. Selection belongs to Round 37 ADRs.

---

## Section 1 — Synthesis Framework

### 1.1 Purpose and Scope

36E-04 produced 20+ architectural options across 7 pressure areas. Each option was evaluated independently. No options were selected or eliminated.

36E-05 transforms that catalog into three outputs:

```
Architecture Option Catalog (36E-04)
                ↓
        36E-05 Synthesis
                ↓
┌───────────────────────────────────┐
│ 1. Architecture Families          │
│    Coherent option combinations   │
│                                   │
│ 2. Dependency Map                 │
│    Which decisions gate others    │
│                                   │
│ 3. ADR Authoring Sequence         │
│    Ordered decision package       │
│    for Round 37                   │
└───────────────────────────────────┘
```

These outputs allow Round 37 ADR authors to evaluate **architecturally coherent positions** rather than isolated option choices.

### 1.2 Three-Level Synthesis Model

Every Architecture Family in Section 3 is assessed at three levels simultaneously:

**Level 1 — Constitutional Compatibility**
Do the combined options satisfy the 30 architectural constraints (AC-01 through AC-30) when evaluated together? Options that individually pass may create new constraint stress when combined.

**Level 2 — DDD Compatibility**
Do the combined options work within or alongside the existing DDD model? What does the combination require of existing aggregates, contexts, value objects, and domain events?

**Level 3 — Operational Feasibility**
Is the combination organizationally realistic? What governance structures, constitutional instruments, and organizational prerequisites does the combination require?

Some combinations are constitutionally valid but operationally unrealistic. Some are operationally simple but constitutionally weak. 36E-05 exposes those tradeoffs — it does not resolve them.

### 1.3 Binding Prohibitions

The following statements do not appear in this document:

- "Family F-N is the recommended architecture"
- "Option X is the correct choice"
- "The program should implement Y"
- Any statement selecting a family as the final architecture
- Any new contexts, aggregates, services, APIs, ADRs, or technology selections

Architecture Families are candidate coherent positions. The decision belongs to Round 37 ADRs.

### 1.4 Input Map — 36E-04 Option Catalog

| Pressure Area | Options |
|---|---|
| EH-01 (Verifier Independence) | A: Verifier Only / B: Verifier + Reference / C: Cryptographic Commitment |
| EC-01 (Challengeability vs Receipt-Freeness) | A: Separated Domains / B: Individual Verifiability Without Receipt / C: Aggregate Verifiability Only |
| CPR-01 (Form of Independence) | A: Role Mandate / B: Committee Holder / C: External Organization / D: Hybrid per Function |
| CPR-02 (Audit Scope Authority) | A: Unified D43-AUDIT / B: Separated Scope + Execution / C: ElectionConstitution Delegation |
| CPR-03 (Authority in DDD Model) | A: Domain Policies / B: Dedicated Aggregates / C: Separate Authority Layer / D: Annotations |
| CPR-04 (Certification) | A: Terminal State / B: Separate Constitutional Act / C: Multi-Party / D: Deferred |
| CPR-05 (Evidence Integrity) | A: Layered Pathways / B: External Repository / C: Cryptographic Accumulation / D: Stratified Architecture |

Cross-option tensions from 36E-04 Section 9:
- CT-1: EC-01 B × CPR-05 C — may be complementary or in tension
- CT-2: CPR-03 C × CPR-03 dual map — reinforcing
- CT-3: CPR-04 D × program timeline — sequencing risk
- CT-4: CPR-01 A × AC-06 — nominal independence risk

---

## Section 2 — Option Compatibility Matrix

Before proposing Architecture Families, the compatibility between options across different pressure areas is assessed. Classification:

- **R — Reinforcing:** options strengthen each other constitutionally
- **C — Compatible:** options can coexist without creating new constraint stress
- **T — Tension:** options create additional constitutional or design pressure when combined
- **I — Incompatible:** options cannot coherently coexist (high bar — requires explicit constitutional evidence)

### 2.1 EH-01 × CPR-05 Compatibility

The verifier independence model (EH-01) and the evidence integrity architecture (CPR-05) address adjacent parts of the same constitutional concern: evidence authenticity. They must be assessed together.

| EH-01 \ CPR-05 | A (Layered Pathways) | B (External Repository) | C (Cryptographic Accumulation) | D (Stratified Architecture) |
|---|---|---|---|---|
| **A (Verifier Only)** | C — both minimal external footprint; reference circularity persists in both | C — repository provides reference standard that Option A lacks; AC-02 stress partially reduced | T — cryptographic commitment is a stronger reference mechanism than Option A assumes; options are not incompatible but their trust assumptions differ | C — stratified architecture exposes the circularity gap that Option A leaves unresolved; honest combination |
| **B (Verifier + Reference)** | T — layered pathways alone do not provide the independent reference standard Option B requires | R — external repository IS the independent reference standard Option B requires; strongest pairing | R — cryptographic commitment externally anchored provides the reference standard; strong pairing | R — stratified architecture with external authenticity stratum provides Option B's reference requirement |
| **C (Cryptographic Commitment)** | T — commitment without external publication degrades to Option A semantics | C — external repository can hold the published commitment; compatible routing | R — both are cryptographic approaches; natural pairing; CT-1 (EC-01 B interaction) must be evaluated | C — stratified architecture accommodates commitment as the authenticity stratum mechanism |

**Finding 2.1-F1:** EH-01 B and CPR-05 B are the strongest reinforcing pairing. EH-01 B and CPR-05 D are also reinforcing. EH-01 A and CPR-05 A are compatible but leave reference circularity unresolved in both.

### 2.2 EC-01 × CPR-05 Compatibility

Individual challengeability (EC-01) and evidence integrity architecture (CPR-05) interact through the CT-1 cross-tension: individual verifiability mechanisms may enable receipt construction.

| EC-01 \ CPR-05 | A (Layered Pathways) | B (External Repository) | C (Cryptographic Accumulation) | D (Stratified Architecture) |
|---|---|---|---|---|
| **A (Separated Domains)** | C | C | C — no CT-1 tension because vote content is not individually verifiable under Option A | C |
| **B (Individual Verifiability)** | C — layered pathways can expose individual vote confirmation without enabling receipts if pathway is carefully designed | C | T (CT-1) — cryptographic commitment may enable voters to construct externally verifiable proofs of vote content; design must prevent this | C — stratified architecture can isolate individual verification from commitment publishing |
| **C (Aggregate Verifiability)** | C | C | C — no individual commitment exposure; CT-1 not triggered | C |

**Finding 2.2-F1:** EC-01 A (Separated Domains) is compatible with all CPR-05 options — it avoids CT-1 entirely by scope separation. EC-01 B creates CT-1 tension specifically with CPR-05 C; pairing EC-01 B with CPR-05 C requires explicit CT-1 resolution in Round 37 design.

### 2.3 CPR-01 × CPR-04 Compatibility

The form of constitutional independence (CPR-01) constrains the viable certification architecture (CPR-04).

| CPR-01 \ CPR-04 | A (Terminal State) | B (Separate Act) | C (Multi-Party) | D (Deferred) |
|---|---|---|---|---|
| **A (Role Mandate)** | C — AC-06 risk stacks: the same organization's role certifies its own election | T — separate act requires the role to be genuinely separate from election execution; AC-06 risk applies | T — multi-party requires external parties; Option A (role mandate within same organization) does not naturally include external parties | C |
| **B (Committee Holder)** | T — committee certifying the election it administratively supports creates circular certification concern | R — committee as a separate certification authority satisfies AC-27 more naturally | C — multi-party can include the committee plus external parties | C |
| **C (External Organization)** | I — external organization constitutionally designated as independent cannot be reduced to a terminal state of the election system | R — external organization as the certifying authority is the canonical form of Option B | R — external organization as one of the certifying parties strengthens multi-party independence | C |
| **D (Hybrid per Function)** | Variable | Variable | C | C |

**Finding 2.3-F1:** CPR-01 A (Role Mandate) × CPR-04 A (Terminal State) is the weakest constitutional pairing — circular certification risk + nominal independence risk stack. CPR-01 C × CPR-04 B or C are the most constitutionally coherent pairings.

### 2.4 CPR-03 × CPR-01 Compatibility

The DDD modeling approach for authority (CPR-03) must be compatible with the form of independence chosen (CPR-01).

| CPR-03 \ CPR-01 | A (Role Mandate) | B (Committee) | C (External Org) | D (Hybrid) |
|---|---|---|---|---|
| **A (Domain Policies)** | C — policies can represent role mandates | C | T — external organization as authority holder needs more than a policy reference; the organizational boundary is not captured in a policy element | T — hybrid requires per-function modeling; policies alone may not differentiate independence levels |
| **B (Dedicated Aggregates)** | C | R — authority aggregates with L-1/L-5 specifications can model committee mandate, lifecycle, and revocation | R — authority aggregate can represent external organization as L-2 holder with organizational boundary modeled as a value object | R — per-function aggregates can model different independence forms per D43 instance |
| **C (Separate Layer)** | C | R | R — separate authority layer naturally accommodates organizational boundary modeling | R — separate layer can model hybrid authority forms per function |
| **D (Annotations)** | C | T — committee structure is not well-modeled by annotations alone; lifecycle and revocation not capturable | I — external organizational relationship cannot be adequately represented as annotations on existing elements | T |

**Finding 2.4-F1:** CPR-03 B (Dedicated Aggregates) and CPR-03 C (Separate Layer) are the most compatible with higher independence forms (CPR-01 B/C/D). CPR-03 D (Annotations) is incompatible with CPR-01 C (External Organization) — organizational relationships require richer modeling than annotations.

### 2.5 CPR-02 × CPR-01 Compatibility

Audit scope structure (CPR-02) must align with the chosen form of independence (CPR-01).

| CPR-02 \ CPR-01 | A (Role Mandate) | B (Committee) | C (External Org) | D (Hybrid) |
|---|---|---|---|---|
| **A (Unified D43-AUDIT)** | C — unified authority with role mandate; AC-16 stress persists | T — committee-level unified authority; AC-16 stress partially reduced | T — external organization holds both scope and execution; more genuine independence but AC-16 tension remains | C |
| **B (Separated Scope + Execution)** | T — two separate role mandates within same org; whether they are constitutionally distinct depends on bylaw enforcement | R — scope committee + audit execution committee as separate bodies within framework | R — external organizations naturally provide the separation Option B requires | R |
| **C (ElectionConstitution Delegation)** | R — constitution provides the scope; role executes the audit | R — constitution provides the scope; committee executes | R — constitution provides the scope; external org executes | R |

**Finding 2.5-F1:** CPR-02 C (ElectionConstitution Delegation) is compatible with ALL CPR-01 options — the constitutional document provides scope regardless of who executes. This makes Option C the most independence-neutral choice for audit scope.

### 2.6 Compatibility Summary

From the pairwise analysis, the following option clusters emerge as naturally coherent:

**Cluster Alpha — Minimal (Role-Level, Policy-Based, Layered)**
```
EH-01 → A    EC-01 → A    CPR-01 → A    CPR-02 → A or C
CPR-03 → A   CPR-04 → A   CPR-05 → A
```
Coherence: internal tensions exist (AC-06 risk stacks, reference circularity, AC-16 stress); weakest constitutional coverage.

**Cluster Beta — Structural (Committee, Aggregates, Repository)**
```
EH-01 → B    EC-01 → A    CPR-01 → B    CPR-02 → C
CPR-03 → B   CPR-04 → B   CPR-05 → B
```
Coherence: strong reinforcing pairings; organizationally feasible for most democratic associations; CT-1 avoided via EC-01 A.

**Cluster Gamma — Maximum (External, Separate Layer, Stratified)**
```
EH-01 → B or C    EC-01 → A    CPR-01 → C    CPR-02 → B
CPR-03 → C        CPR-04 → C   CPR-05 → C or D
```
Coherence: strongest constitutional coverage; requires external organizational relationships; highest operational complexity.

---

## Section 3 — Architecture Families

Based on the compatibility matrix, three Architecture Families are proposed. These represent internally coherent constitutional positions, not an exhaustive enumeration of all possible combinations.

**Important discipline note:** The families below present coherent combinations for Round 37 evaluation. They do not represent a recommendation sequence or preference ordering. No family is presented as preferred.

---

### Family A

*Associational Integrity — designated roles, clear mandates, and documented accountability within the organization.*

**Core constitutional position:** Constitutional trustworthiness is achieved through well-structured internal governance. External entities are not constitutionally required; organizational discipline is the primary trust mechanism.

**Option Profile:**

| Pressure Area | Option | Rationale for coherence |
|---|---|---|
| EH-01 | A — Independent Verifier Only | Designated internal verifier role; reference standard circularity acknowledged but not addressed at this level |
| EC-01 | A — Separated Challenge Domains | Non-content decisions are challengeable; vote content protected by VO-1; receipt-freeness fully preserved |
| CPR-01 | A — Role Mandate | Constitutionally designated role within organization; dependent on bylaw enforcement |
| CPR-02 | C — ElectionConstitution Delegation | Constitutional document defines audit scope; single execution authority; AC-16 stress reduced by documentary separation |
| CPR-03 | A — Domain Policies | Authority relationships as policy extensions of ElectionConstitution; least model disruption |
| CPR-04 | A — Terminal State | Certification as final lifecycle state of results process; simplest organizational requirement |
| CPR-05 | A — Layered Pathways | Distinct pathways for presence and authenticity verification; no external repository required |

#### Level 1 — Constitutional Compatibility (A)

| AC Group | Assessment |
|---|---|
| AC-01/02 (CF-05-08: behavioral ≠ constitutional) | Stressed — F1 relies heavily on organizational roles; the behavioral-vs-constitutional gap is partially unresolved |
| AC-05/06 (independent authority relationship; not nominal) | Stressed — AC-06 is the primary F1 risk: role mandate independence may be nominal if appointment/removal is not itself independent |
| AC-11/12/13 (lifecycle, revocation, succession) | Partially addressed — policy elements do not naturally carry lifecycle mechanisms; succession (L-5) is the most exposed |
| AC-14/16/17 (audit scope from outside; scope ≠ execution) | Partially addressed — ElectionConstitution provides scope; AC-16 tension reduced but not eliminated since execution authority is internal |
| AC-27/28 (certification challenge; terminal legitimacy gap) | Stressed — certification as terminal state within the election system creates AC-27 challenge pathway tension |
| AC-29 (presence ≠ authenticity) | Addressed — layered pathways architecturally separate the two concerns |
| AC-30 (criteria precision) | Depends on ElectionConstitution precision — constitutional document must be sufficiently precise |

**ACs best satisfied:** AC-29, AC-30 (if constitution is precise)
**ACs stressed:** AC-06, AC-12, AC-27
**ACs least satisfied:** AC-05 (independence substantiveness), AC-11/L-5 (succession)

#### Level 2 — DDD Compatibility (A)

Existing model compatibility:
- Vote aggregate: fully compatible — no changes required
- Verification aggregate: compatible — TA-3 (append-only) preserved; no new challenge mechanisms
- GovernanceState: extended — ElectionConstitution reference becomes the authority policy anchor
- Audit Context: compatible — existing event observation model sufficient for layered pathways
- VRC (D42B): compatible — access pathway design is the primary new requirement

New elements implied:
- ElectionConstitution policy catalog (extension of existing reference)
- AC-15 external access pathway for layered authenticity exposure

Model disruption: **Low** — F1 is the most DDD-compatible family.

#### Level 3 — Operational Feasibility (A)

Governance structures required:
- Constitutionally designated verifier role (internal)
- ElectionConstitution authorship and ratification process
- Internal audit execution body with documented mandate

Organizational prerequisites:
- Bylaw revision to designate independent roles with protected mandates
- ElectionConstitution drafting and membership ratification
- Single-organization governance capability

D39/D42B dependency exposure:
- CPR-04 A (certification as terminal state) requires D39 design; **CPR-04 D (Deferred) may be substituted until D39 resolved**
- D42B access pathway design needed for CPR-05 A

**Operational feasibility: High** — F1 is achievable by any organization with bylaws and a ratification process.

#### A Characteristics Summary

Family A achieves trustworthiness through organizational discipline rather than structural separation. Its constitutional coverage is weakest against AC-06 (nominal independence) and AC-27 (certification challenge). Its DDD impact is minimal and its operational requirements are accessible. The primary constitutional risk is that well-structured internal roles may be organizationally revocable in ways that render their independence nominal. Family A is constitutionally coherent as a minimum viable position; whether it is constitutionally sufficient is a Round 37 determination.

---

### Family B

*Delegated Constitutional — constitutionally ratified instruments and designated independent committees.*

**Core constitutional position:** Constitutional trustworthiness is achieved through a combination of constitutionally ratified instruments (ElectionConstitution as scope and criteria authority) and designated independent bodies (committees with protected mandates) operating within the organization. The constitutional document is the primary trust anchor; committee structures provide the independence layer.

**Option Profile:**

| Pressure Area | Option | Rationale for coherence |
|---|---|---|
| EH-01 | B — Verifier + Independent Reference | Independent reference standard is constitutionally designated; reference circularity resolved |
| EC-01 | A — Separated Challenge Domains | Non-content decisions challengeable; VO-1 fully protected; CT-1 avoided |
| CPR-01 | B — Separate Authority Holder (Committee) | Committee with constitutionally protected mandate; different holder from primary authority |
| CPR-02 | C — ElectionConstitution Delegation | Constitution provides scope mandate; audit committee executes; scope and execution constitutionally separated by document vs. committee |
| CPR-03 | B — Dedicated Aggregates | Authority relationships as first-class aggregates; L-1/L-5 as value objects; lifecycle explicitly modeled |
| CPR-04 | B — Separate Constitutional Act | Certification committee performs constitutionally independent act after results; AC-27 satisfied |
| CPR-05 | B — External Evidence Repository | External repository under committee authority provides independent reference standard |

#### Level 1 — Constitutional Compatibility (B)

| AC Group | Assessment |
|---|---|
| AC-01/02 (behavioral ≠ constitutional) | More fully satisfied — dedicated aggregates make constitutional structures first-class; EH-01 B breaks reference circularity |
| AC-05/06 (independent authority; not nominal) | More fully satisfied — separate committee holder reduces AC-06 risk; committee's L-2 mandate must be constitutionally grounded |
| AC-08/09 (challenge pathway; independent reception) | Satisfied for non-content decisions (EC-01 A); vote content challenge not addressed |
| AC-11/12/13 (lifecycle, revocation, succession) | Addressed — dedicated aggregates carry explicit lifecycle; succession (L-5) requires constitutional specification |
| AC-14/16/17 (audit scope) | Satisfied — constitution provides scope (C); committee executes (B-style separation through different constitution vs. committee) |
| AC-27/28 (certification challenge; terminal legitimacy) | More fully satisfied — certification committee holds AC-27 challenge pathway naturally |
| AC-29 (presence ≠ authenticity) | Satisfied — external repository is the independent reference; authenticity verified against repository |

**ACs best satisfied:** AC-02, AC-05, AC-14/16/17, AC-27/28, AC-29
**ACs stressed:** AC-06 (committee independence depends on appointment process), AC-11/L-5 (succession still requires specification), AC-08 (vote content not individually challengeable)
**ACs least satisfied:** AC-08 for vote content decisions

#### Level 2 — DDD Compatibility (B)

Existing model compatibility:
- Vote aggregate: fully compatible
- Verification aggregate: compatible
- GovernanceState: extended — ElectionConstitution reference becomes the primary policy anchor for authority relationships
- Audit Context: compatible with extension — external repository creates a new audit evidence pathway
- VRC (D42B): authority boundary must be designed alongside committee authority structure

New elements implied:
- Authority aggregates (one per D43 function, carrying L-1/L-5 as value objects)
- External evidence repository interaction points
- Committee mandate lifecycle modeling

Model disruption: **Medium** — dedicated aggregates require new modeling work; follows existing aggregate patterns from Round 33.

#### Level 3 — Operational Feasibility (B)

Governance structures required:
- Constitutionally designated independent committee (distinct from election administration)
- ElectionConstitution with audit scope, certification authority, and committee mandate provisions
- External evidence repository with constitutional governance (who manages it, who can write, who can read)
- Membership ratification of ElectionConstitution

Organizational prerequisites:
- Committee establishment and constitutional protection
- External repository arrangement (may be another organization, a trusted third party, or a technically independent system)
- Bylaw revision to protect committee independence from operational election authority

D39/D42B dependency exposure:
- CPR-04 B (certification as separate act) is not dependent on D39 internal architecture, only on results being published
- D42B committee authority boundary must be separated from context boundary

**Operational feasibility: Moderate** — achievable by most established democratic associations; requires committee establishment and an external repository arrangement.

#### B Characteristics Summary

Family B achieves trustworthiness through constitutional instruments (ElectionConstitution) and committee-level structural independence. Its constitutional coverage is substantially stronger than Family A, particularly for AC-02, AC-14/16/17, and AC-27/28. Its primary constitutional exposure is the committee's own L-1/L-5 specification — the committee's independence must itself be constitutionally grounded (who constitutes the committee? Who can dissolve it?). Family B is constitutionally coherent for most democratic associations that can establish committee structures with protected mandates.

---

### Family C

*Structural Independence — organizationally external authority relationships and augmented authority layer.*

**Core constitutional position:** Constitutional trustworthiness is achieved through structural independence — at least some authority relationships are held by entities that are organizationally separate from the election system. The DDD model is augmented with a separate authority layer. Evidence integrity relies on cryptographic or multi-repository mechanisms that resist simultaneous tampering.

**Option Profile:**

| Pressure Area | Option | Rationale for coherence |
|---|---|---|
| EH-01 | B — Verifier + Independent Reference | Full EH-01 satisfaction; reference standard held by external authority |
| EC-01 | A — Separated Challenge Domains | Maintains VO-1 protection; CT-1 avoided; vote content not individually challengeable |
| CPR-01 | C — External Organization | Organizational independence for at least the audit and certification functions; strongest AC-06 protection |
| CPR-02 | B — Separated Scope + Execution | External scope authority defines expected evidence set; external audit authority executes; AC-16 fully satisfied |
| CPR-03 | C — Separate Authority Modeling Layer | Authority map as a distinct modeling artifact; authority partitioning independent of context partitioning |
| CPR-04 | C — Multi-Party Certification | Two or more constitutionally independent parties must concur; TC-4 protection strongest |
| CPR-05 | D — Stratified Architecture | Completeness, presence, and authenticity as distinct strata with separate governance and access requirements |

#### Level 1 — Constitutional Compatibility (C)

| AC Group | Assessment |
|---|---|
| AC-01/02 (behavioral ≠ constitutional) | Well-satisfied — separate authority layer makes constitutional structures independently maintained |
| AC-05/06 (independent authority; not nominal) | Strongest — organizational boundary provides genuine independence; AC-06 risk minimal |
| AC-08/09 (challenge pathway) | Non-content decisions challengeable (EC-01 A); external organizations provide natural challenge reception |
| AC-11/12/13 (lifecycle, revocation, succession) | Better addressed — external organization's mandate has its own constitutional lifecycle |
| AC-14/16/17 (audit scope) | Fully satisfied — separate external scope authority and separate external audit authority |
| AC-18/19/20 (authority map ≠ context map) | Best satisfied — separate authority layer IS the authority map |
| AC-25/26/27/28 (certification) | Best satisfied — multi-party certification with external organizations |
| AC-29 (presence ≠ authenticity) | Fully satisfied — stratified architecture provides three distinct strata |

**ACs best satisfied:** AC-05/06, AC-14/16/17, AC-18/19/20, AC-25/26/27/28, AC-29
**ACs stressed:** AC-08 (vote content still not individually challengeable under EC-01 A), D39 dependency for full certification design
**ACs least satisfied:** AC-08 for vote content

#### Level 2 — DDD Compatibility (C)

Existing model compatibility:
- Vote aggregate: fully compatible
- Verification aggregate: compatible
- GovernanceState: extended with external organization references
- Audit Context: extended with stratified evidence architecture

New elements implied:
- Separate authority modeling layer (distinct from domain model — new modeling vocabulary required)
- External organization authority relationships represented in authority layer
- Multi-party certification protocol structure
- Three evidence strata with distinct access pathways

Model disruption: **High** — separate authority layer requires new modeling vocabulary; two distinct models to maintain.

#### Level 3 — Operational Feasibility (C)

Governance structures required:
- External audit organization with constitutional recognition
- External certification parties (two or more) with constitutional concurrence mechanism
- External scope authority (regulatory body, constitutional drafting body, or ratified standards committee)
- Inter-organizational agreements for all external relationships

Organizational prerequisites:
- Agreements with external audit body
- Multi-party certification protocol establishment
- Constitutional recognition of external authorities within organizational bylaws

D39/D42B dependency exposure:
- CPR-04 C (multi-party) is provisionally designable without full D39 resolution
- D42B boundary design must account for external organization access patterns

**Operational feasibility: Low to Moderate** — achievable by large, established organizations with access to external certification and audit bodies; more demanding for smaller democratic associations.

#### C Characteristics Summary

Family C achieves the strongest constitutional coverage by placing authority relationships with organizationally independent entities. It is the most resistant to the forms of trust concentration that 36C identified as highest-risk (TC-4, TC-5). Its primary constraint is operational — not every organization can establish or maintain relationships with external certification and audit bodies. Family C is constitutionally coherent and may be constitutionally sufficient or required for organizations where the election results have significant legal or governance consequences. Whether structural independence of this form is constitutionally required (as opposed to constitutionally optimal) is a central Round 37 question — not a settled finding.

---

### 3.1 Family Comparison (No Selection)

| Dimension | Family A | Family B | Family C |
|---|---|---|---|
| AC-05/06 satisfaction | Stressed | Moderate | Strongest |
| AC-14/16/17 satisfaction | Partial | Satisfied | Full |
| AC-27/28 satisfaction | Stressed | Satisfied | Strongest |
| AC-29 satisfaction | Satisfied | Satisfied | Fully satisfied |
| DDD model disruption | Low | Medium | High |
| Organizational prerequisites | Low | Moderate | High |
| TC-4 protection | Partial | Moderate | Strongest |
| EC-01 vote content | Not addressed | Not addressed | Not addressed |
| D39 dependency | High (CPR-04 A) | Low (CPR-04 B) | Provisional (CPR-04 C) |
| Constitutional coverage | Lower | Moderate | Higher |

**Families are not ranked.** Family ordering (A/B/C) does not imply preference. No family is recommended. The question for Round 37 is: which constitutional coverage is constitutionally sufficient for this organization and this electoral context?

---

## Section 4 — Constitutional Dependency Map

### 4.1 The Foundation Question

The most foundational question in the entire option catalog is:

**OQ-03-05 / CPR-03: What form do authority relationships take in the DDD model?**

This is the foundation because:
- If authority relationships are Domain Policies (Option A), the vocabulary for expressing L-1/L-5 in Round 37 is the ElectionConstitution policy catalog
- If authority relationships are Dedicated Aggregates (Option B), the vocabulary is aggregate identity and lifecycle modeling
- If authority relationships form a Separate Authority Layer (Option C), a new modeling vocabulary is required before any subsequent design work can proceed
- If authority relationships are Annotations (Option D), subsequent ADRs are constrained to annotation-compatible structures

**ADR-1 (Authority Vocabulary and Authority Source Model) is therefore the dependency root for all subsequent ADRs.**

### 4.2 Dependency Structure

```
ADR-1: Authority Vocabulary + Authority Source Model (CPR-03 / OQ-03-05 / OQ-36E-04-04)
    │
    │   "What is the DDD modeling vocabulary for constitutional authority?"
    │   "Is ElectionConstitution the shared L-1, or does each D43 function
    │    require its own L-1 instrument?"
    │
    ├──► ADR-2: Independence Form (CPR-01)
    │        │
    │        │   "What form of organizational independence is constitutionally sufficient
    │        │    per D43 function?"
    │        │
    │        ├──► ADR-3: Evidence + Verifier Architecture (EH-01 + CPR-05 co-decided)
    │        │        │
    │        │        │   "What verifier independence model and evidence integrity
    │        │        │    architecture are constitutionally sufficient together?"
    │        │        │   (EH-01 and CPR-05 must be co-decided — they address
    │        │        │    adjacent parts of the same constitutional concern)
    │        │        │
    │        │        └──► ADR-6: Certification Architecture (CPR-04 provisional)
    │        │
    │        ├──► ADR-4: Audit Scope Structure (CPR-02 / ET-03)
    │        │        │
    │        │        │   "Is audit scope authority unified with execution, separated,
    │        │        │    or delegated to ElectionConstitution?"
    │        │        │
    │        │        └──► ADR-6: Certification Architecture (CPR-04 provisional)
    │        │
    │        └──► ADR-5: Challenge Architecture (EC-01)
    │                 │
    │                 │   "Is EC-01 a Type D tension (resolvable through Option A/B)
    │                 │    or Type E conflict?"
    │                 │   "What scope of decisions is constitutionally challengeable?"
    │
    └──► ADR-7: D42B Closure (Verification Representation Context)
             │
             │   Depends on: ADR-1 (vocabulary for VRC authority boundary)
             │               ADR-2 (independence form for verification authority)
             │               ADR-3 (evidence architecture for VRC access pathway)
```

**ADR-6 (Certification) is the most dependent ADR** — it requires ADR-1, ADR-2, ADR-3, and ADR-4, and is provisional until D39 is resolved.

### 4.3 Key Dependencies Explained

**EH-01 + CPR-05 Co-Decision (ADR-3):**

EH-01 and CPR-05 cannot be decided independently because:
- EH-01 Option B requires an independent reference standard
- CPR-05 Option B (External Repository) provides that reference standard
- EH-01 Option C requires cryptographic commitment
- CPR-05 Option C (Cryptographic Accumulation) provides that commitment infrastructure
- Choosing EH-01 A while choosing CPR-05 B creates constitutional incoherence — why build an external repository if the verifier only checks the system's own reference?

The verifier independence model and the evidence integrity architecture are two expressions of the same constitutional requirement: the election system cannot be the sole arbiter of its own evidence authenticity.

**CPR-01 gates CPR-04:**

The form of independence for D43 functions (CPR-01) constrains certification architecture (CPR-04) because:
- If CPR-01 A (role mandate) is selected, CPR-04 C (multi-party with external orgs) is incompatible
- If CPR-01 C (external organization) is selected, CPR-04 A (terminal state within election system) is constitutionally incoherent
- The independence form sets the constitutional floor; certification cannot exceed that floor in independence, and should not fall below it

**ElectionConstitution as Shared L-1 (OQ-36E-04-04):**

The question of whether ElectionConstitution serves as a shared L-1 source for multiple D43 instances cuts across ADR-1 and ADR-2. It must be determined early because:
- If ElectionConstitution is the shared L-1: all authority relationships are grounded in a single constitutional instrument; scope of that instrument determines coverage
- If each D43 function requires its own L-1: separate constitutional instruments are needed for enrollment, criteria, audit, governance, and certification
- This choice determines how many constitutional artifacts must be produced and maintained

ADR-1 (Authority Vocabulary) should resolve this question as a prerequisite for subsequent authority modeling.

### 4.4 Shared Constitutional Prerequisites

Some constitutional prerequisites are shared across multiple ADRs and are not specific to any single option choice:

**Prerequisite P-1 — ElectionConstitution Authorship and Ratification:**
Required by F1/F2/F3 at different degrees. Must be constitutionally grounded (AC-23 applies). Cannot be completed by election administration acting alone.

**Prerequisite P-2 — D43 Gap Closure for Enrollment:**
D43-ENROLL has zero owner and zero L-1/L-5 coverage. Before ADR-2 can fully address enrollment independence, the enrollment function's authority holder must be identified. (Note: identification is not design — it is discovery. The Gap-A-3 problem for enrollment legitimacy mirrors the Gap-A-3 problem for audit completeness.)

**Prerequisite P-3 — D39 Resolution:**
CPR-04 (all options except D) requires knowing how results are produced. D39 deferred. Until D39 is resolved, all certification ADRs are provisional.

**Prerequisite P-4 — EC-01 Type Determination (Type D or Type E):**
ADR-5 must first determine whether EC-01 is resolvable (Type D) or irresolvable (Type E) before challenge architecture can be designed. This determination is a constitutional analysis question, not a design question.

---

## Section 5 — ADR Authoring Sequence

Based on the dependency map, the following ADR authoring sequence is proposed for Round 37. Each ADR must reach a decision before the next dependent ADR can be authorized.

**Important:** This is a proposed sequence, not a binding design. The sequence is itself subject to ARB review and adjustment. It represents the dependency order implied by the constitutional constraints — not a claim that the sequence is the only valid one.

### Proposed Sequence

**ADR-1: Authority Vocabulary and Authority Source Model**
- *Questions (two distinct but co-decided):*
  - **Vocabulary:** What is the constitutionally sufficient DDD modeling vocabulary for representing L-1/L-5 authority relationships? (CPR-03)
  - **Source:** What is the constitutional source of authority — does ElectionConstitution serve as shared L-1 for all D43 functions, or does each function require its own L-1 instrument? (OQ-36E-04-04)
- *Why co-decided:* The vocabulary decision and the source decision are related but distinct. A policy-based vocabulary (CPR-03 A) naturalizes a shared constitutional document as the L-1 source. An aggregate-based vocabulary (CPR-03 B) accommodates per-function L-1 differentiation. Deciding vocabulary without deciding source artificially constrains the source question; deciding source without vocabulary produces an unmodelable answer.
- *Addresses:* OQ-03-05 (CPR-03 Options A/B/C/D), OQ-36E-04-04 (ElectionConstitution as shared L-1), OQ-36E-04-06 (vocabulary sufficiency)
- *Unblocks:* All subsequent ADRs — this is both the vocabulary foundation and the constitutional source foundation
- *ADR Output:* Which option from CPR-03 is adopted + authority source model (shared L-1 or per-function L-1)

---

**ADR-2: Independence Form per D43 Function**
- *Question:* What form of organizational independence is constitutionally sufficient for each D43 function?
- *Addresses:* CPR-01 (Options A/B/C/D), OQ-36E-04-01 (cross-option tensions)
- *Depends on:* ADR-1 (vocabulary must exist to express independence model)
- *Unblocks:* ADR-3, ADR-4, ADR-5, ADR-6
- *ADR Output:* Which option from CPR-01 is adopted per D43 function (may vary — Option D structure)

---

**ADR-3: Evidence and Verifier Architecture** *(EH-01 + CPR-05 co-decided)*
- *Question:* What verifier independence model and evidence integrity architecture are constitutionally sufficient together?
- *Addresses:* EH-01 (Options A/B/C), CPR-05 (Options A/B/C/D), OQ-36E-04-02 (eliminable options)
- *Depends on:* ADR-2 (independence form determines what "external reference standard" means organizationally)
- *Unblocks:* ADR-6, ADR-7
- *Note:* EH-01 and CPR-05 must be decided together. They are two expressions of the same constitutional requirement.
- *ADR Output:* Which EH-01 + CPR-05 pairing is adopted; whether EH-01 is promoted to AC-31

---

**ADR-4: Audit Scope Authority Structure**
- *Question:* Is audit scope authority unified with audit execution, separated from it, or delegated to ElectionConstitution?
- *Addresses:* CPR-02 (Options A/B/C), ET-03 (specialization vs separate authority relationship)
- *Depends on:* ADR-2 (form of audit authority holder determines whether Option A/B/C is viable)
- *Unblocks:* ADR-6
- *ADR Output:* Which option from CPR-02 is adopted; ET-03 threshold determination (specialization or separate relationship)

---

**ADR-5: Challenge Architecture**
- *Question:* Is EC-01 a Type D tension (resolvable) or Type E conflict (irresolvable within constitutional constraints)? What scope of decisions is constitutionally challengeable?
- *Addresses:* OQ-36E-04-03 (EC-01 Type D or E), EC-01 (Options A/B/C)
- *Depends on:* ADR-1 (vocabulary for challenge pathway modeling), ADR-2 (independence form determines who receives challenges)
- *Note:* ADR-5 can proceed in parallel with ADR-3 and ADR-4 after ADR-2 is complete. **Conditional dependency on ADR-3:** if EC-01 Option B (individual verifiability) is under evaluation and the chosen evidence architecture (CPR-05) involves cryptographic commitment, ADR-5 must be aware of ADR-3 outputs before finalizing the challenge architecture — CT-1 tension (EC-01 B × CPR-05 C) may require co-analysis. This dependency is conditional, not absolute.
- *ADR Output:* EC-01 type determination; which option from EC-01 is adopted

---

**ADR-6: Certification Architecture** *(provisional pending D39)*
- *Question:* What constitutionally grounded certification architecture is coherent with the adopted independence model, audit structure, and evidence architecture?
- *Addresses:* CPR-04 (Options A/B/C/D), OQ-36E-04-05 (certification before or after D39)
- *Depends on:* ADR-2 (independence form determines viable certification authority form), ADR-3 (evidence architecture determines what is being certified), ADR-4 (audit scope determines relationship to certification scope)
- *Note:* PROVISIONAL until D39 is resolved. ADR-6 may produce a provisional architecture that commits to a family (A/B/C) while deferring D39-dependent details.
- *ADR Output:* Which option from CPR-04 is adopted; D39 dependency explicitly acknowledged

---

**ADR-7: D42B Closure (Verification Representation Context)**
- *Question:* What is the authority boundary of the Verification Representation Context, and how does it interact with the adopted authority model and evidence architecture?
- *Addresses:* ADR-Candidate-01 and ADR-Candidate-02 (previously deferred)
- *Depends on:* ADR-1 (vocabulary for VRC authority boundary), ADR-2 (VRC independence form), ADR-3 (evidence access pathway through VRC)
- *ADR Output:* D42B closure; VRC authority boundary separated from context boundary

---

### Sequence Visualization

```
ADR-1 (Authority Vocabulary + Source Model)
    ↓
ADR-2 (Independence Form)
    ↓                    ↘
ADR-3 (Evidence/Verifier)  ADR-4 (Audit Scope)
    ↓  ╌╌╌ conditional ╌╌╌╌╌╌╌╌╌↘
    ↓                          ADR-5 (Challenge)
    ↓
ADR-6 (Certification — provisional)
    ↓
ADR-7 (D42B Closure)
```

ADR-3, ADR-4, and ADR-5 all depend on ADR-2. ADR-3 and ADR-4 have no dependency on each other and may be authorized in parallel. ADR-5 may proceed in parallel with ADR-3 and ADR-4, **except** where EC-01 Option B evaluation intersects with CPR-05 Option C selection (CT-1 tension) — in that case, ADR-5 requires ADR-3 outputs before finalizing. This conditional dependency is noted but not absolute.

---

## Section 6 — Open Questions Mapped to ADR Sequence

| OQ (from 36E-04) | Addressed by | Nature |
|---|---|---|
| OQ-36E-04-01: Are cross-option tensions correctly identified? | Section 9 tensions resolved across ADR-3, ADR-5, ADR-6 | Architectural |
| OQ-36E-04-02: Are any options eliminable? | ADR-1 through ADR-7 individually | Selection decision |
| OQ-36E-04-03: Is EC-01 Type D or Type E? | ADR-5 | Constitutional determination — primary question |
| OQ-36E-04-04: Is ElectionConstitution a shared L-1? | ADR-1 | Constitutional determination — most consequential for authority model scope |
| OQ-36E-04-05: Certification before or after D39? | ADR-6 | Sequencing decision |
| OQ-36E-04-06: Is DDD vocabulary sufficient for authority? | ADR-1 | Vocabulary decision |

**Most consequential single question for Round 37:**

OQ-36E-04-04 (ElectionConstitution as shared L-1) directly determines:
- Whether one constitutional document grounds all five D43 functions
- Whether the authority model is unified (one instrument) or distributed (one per function)
- The scope and precision requirements for the ElectionConstitution itself
- The operational overhead of maintaining constitutional legitimacy structures

ADR-1 must resolve this question before any subsequent ADR can proceed.

---

## Section 7 — ARB Decision Block

**[APPROVED WITH REQUIRED REVISIONS — APPROVED]**

**ARB Verdict Date:** 2026-06-15

### Summary of Deliverables

**Produced:**
- Option compatibility matrix: EH-01 × CPR-05, EC-01 × CPR-05, CPR-01 × CPR-04, CPR-03 × CPR-01, CPR-02 × CPR-01
- 3 Architecture Families: A (Associational Integrity), B (Delegated Constitutional), C (Structural Independence) — neutral labels; no ranking
- Each family assessed at Level 1 (Constitutional), Level 2 (DDD), Level 3 (Operational)
- Constitutional Dependency Map: ADR-1 as foundation, dependency chain through ADR-7
- ADR Authoring Sequence: 7 ADRs with explicit dependencies, parallel authorization points, and conditional ADR-3 → ADR-5 dependency
- 6 OQs from 36E-04 mapped to ADR sequence
- Most consequential single question identified: OQ-36E-04-04 (ElectionConstitution as shared L-1)

**Not produced (by discipline):** option selections, architectural decisions, family recommendations, winners, new contexts, new aggregates, new services.

### ARB Required Revisions (Applied)

**Revision 1 — Family Names Neutralized:**
Family F1/F2/F3 renamed to Family A/B/C throughout. Descriptive characterizations (Associational Integrity, Delegated Constitutional, Structural Independence) moved into family section subtitles rather than primary headers. Family comparison table last column renamed from "Constitutional floor" to "Constitutional coverage" to avoid ranking implication.

**Revision 2 — F3/C "requires" → "achieved through":**
Core constitutional position of Family C changed from "Constitutional trustworthiness requires structural independence" to "Constitutional trustworthiness is achieved through structural independence." Characteristics summary also clarified: "may be constitutionally sufficient or required" (not "may be constitutionally required" as the original stated) — opening the question rather than pre-answering it.

**Revision 3 — ADR-1 Broadened to Include Authority Source Model:**
ADR-1 expanded from "Authority Vocabulary" to "Authority Vocabulary and Authority Source Model." The two questions (vocabulary and source) are distinct but must be co-decided. The dependency map reference and sequence visualization updated accordingly.

**Revision 4 — OQ-36E-05-03 Removed:**
The question "Should one family be the primary candidate?" was removed from the ARB Decision Block. All three families enter Round 37 on equal footing. No pre-selection occurs.

**Revision 5 — Conditional ADR-3 → ADR-5 Dependency Added:**
ADR-5 note and sequence visualization updated to reflect: "if EC-01 Option B is under evaluation and CPR-05 Option C is selected, ADR-5 requires ADR-3 outputs — CT-1 tension requires co-analysis." Conditional, not absolute.

### ARB Responses to OQs

**OQ-36E-05-01 (Families eliminable?):** No. All three families (A, B, C) are constitutionally coherent positions. No family is eliminable before Round 37. The constitutional bar for elimination (explicit evidence that no design under the family can satisfy the constraints) is not met for any family.

**OQ-36E-05-02 (ADR sequence correct?):** Largely yes. Conditional ADR-3 → ADR-5 dependency added (CT-1 tension). No additional dependency corrections required.

### Authorization

**36E-05: APPROVED — CLOSED**

**Series 36E: COMPLETE**

**Round 37: AUTHORIZED**

Round 37 Governance Instruction (binding):
```
Round 37 is the first place where architectural selection is allowed.

Every ADR in Round 37 must:
  - Identify alternatives
  - Evaluate alternatives against constitutional constraints
  - Select one alternative
  - Record rationale for selection
  - Record rejected alternatives and reasons for rejection

All three Architecture Families (A, B, C) enter Round 37 on equal footing.
No pre-selection has occurred.
```

**First authorized ADR:**

Round 37-01 — ADR-1: Authority Vocabulary and Authority Source Model

---

*Round 36E-05 — Architecture Synthesis — APPROVED WITH REQUIRED REVISIONS — APPROVED*  
*ARB Approval Date: 2026-06-15*  
*Research Program: NRNA DDD Trustworthiness*  
*Document: Round36E-05_Architecture_Synthesis.md*  
*Series 36E: COMPLETE (36E-01 through 36E-05 APPROVED)*  
*Successor: Round 37-01 — ADR-1: Authority Vocabulary and Authority Source Model — AUTHORIZED*
