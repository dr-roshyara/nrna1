# B-03 — Engineering C4 views and reference documents: extracted basis concepts

| Field | Value |
|---|---|
| Read (full) | `engineering/architecture/c4/`: `2026-07-08-arb-diagram-draft-superseded.md`, `AI_Engineering_Platform_Views.md`, `Engineering_Knowledge_System_Reference_Model.md` (the "EKS-RM") · `engineering/architecture/reference/`: `Engineering_Decision_Model.md`, `Engineering_Platform_Knowledge_Metamodel.md`, `Engineering_Platform_Reference_Architecture.md` |
| Dates | Views 2026-07-08 (+§12 addendum 07-12) · Decision Model and Reference Architecture 2026-07-11 · EKS-RM and Metamodel 2026-07-27 (git add 07-28) |
| Standing | engineering background, **not** KnowledgeOS corpus. **Exception:** the EKS-RM is the first document read that **names KnowledgeOS and places it** (§3, §4, §8). Those statements are first-hand evidence *about* KnowledgeOS from the engineering side |
| Labels | [SRC] stated · [I] interpretation · [?] open |

## 1. First-hand statements about KnowledgeOS (EKS-RM, 2026-07-27)

| # | Statement [SRC] | Anchor |
|---|---|---|
| K-1 | KnowledgeOS is a **"potential product"**, governed by a **charter → binding → execution plan** stack | EKS-RM §3 |
| K-2 | It is **"built on, never into"** the Engineering Platform: "the product **binds** the platform; never forks it — and the platform never absorbs product concerns" | §4 L2; §8 |
| K-3 | Its state on 2026-07-27: **vision FROZEN for discovery** · **problem hypothesis** (charter-governed) · Stage-1 scope · charter **PROPOSED** ("should we begin?") · constitution **INERT** · execution plan **PREPARED**; gates G-1 (charter approval, "three asks"), G-2 (adoption act); stages S-1…S-5, "STOP legal at every gate" | §4 L2–L4; §5 |
| K-4 | Customer: **organizations** (vs the platform's customer, the engineering team) | §8 |
| K-5 | Domain classification: **"engineering governance/knowledge would be its Core Domain"**. The inversion means platform rules (e.g. AIP-14) **do not transfer unchanged** | §8 |
| K-6 | Evolution driver: **customer evidence and stage gates** (vs operational evidence for the platform) | §8 |
| K-7 | A separate **"Engineering Theory"** layer is **EMERGING**: "founding text: Foundations Reference, queued"; it "justifies principles" of the platform | §3; §5 |

**[I]** On this evidence, KnowledgeOS began as a **product idea**: taking the engineering platform's knowledge and governance discipline and offering it to organizations. Its own **problem hypothesis** was still to be tested.

**[?] Not yet read:** where the charter, the vision and the problem hypothesis live, and what they say.
- They are probably in `docs/knowledgeos/` (corpus, gated).
- Two engineering reports also look relevant: `engineering/verification/reports/2026-08-01-knowledgeos-product-status-finding.md` and `…-knowledgeos-maturity-structure-assessment.md`. Both are outside the corpus and unread.

## 2. Knowledge concepts already formalized on the engineering side

| ID | Concept [SRC] | Anchor |
|---|---|---|
| M-1 | **Three levels:** Knowledge Object (identified unit of meaning) → Artifact (governed carrier with status and authority) → Document (a file at a path; "paths carry no identity") | Metamodel §2 |
| M-2 | **Five knowledge types** with a conflict rank: **Normative** (what must hold; rank 1) · **Evidence** (what was measured; "produced-never-asserted", "never becomes authoritative"; rank 3) · **Descriptive** (what exists; loses every conflict with Normative; rank 4) · **Runtime** (rank 5) · **Learning** (might become knowledge; quarantined; unranked) | §3 |
| M-3 | **Fourteen typed relationships**, each with cardinality and cycle rules: records · hosts · registers · justifies (downward only) · realizes · implements · traces-to · binds · points-to · describes · verifies · **evidences** (every verdict has ≥1 record) · **promotes** (one Human Decision Event per stage) · **supersedes** (1→1, acyclic) | §5 |
| M-4 | **Four orthogonal lifecycle axes:** authority ⊥ status ⊥ maturity ⊥ adoption. "Conflation is the policed failure mode"; illegal combinations are rejected | §6 |
| M-5 | **Invariant-class caveat:** every constraint is a Domain invariant, an Architectural constraint, an Implementation constraint or a Current observation, and must be classified, not assumed | §5 |
| M-6 | **Global dependency direction:** nothing normative depends on Descriptive or Evidence, **except** through a `promotes` edge at a Human Decision Event | §5 |
| M-7 | **Explicitly not modelled:** Theory ("candidate only") · Policy · generic Requirement | §4 |
| D-1 | **Decision pattern:** Question → Decision → Authority → Procedure → Evidence; "a decision is a specification, never an executor" | Decision Model |
| D-2 | **Future Knowledge Decisions** (pilot-gated candidates): DetermineKnowledgeNeed · DetermineKnowledgeSource · DetermineKnowledgeRepresentation · DetermineKnowledgeQualification · DetermineKnowledgePromotion | Decision Model, Decision Catalogs |
| D-3 | **Harvest question:** "did this work **reveal** reusable knowledge?"; "No" is the common, valid answer; never "can we create a new rule?" | Decision Model, DetermineReusePotential |
| R-1 | **"Governance precedes automation. Automation may implement governance. Automation never defines governance."** | Reference Architecture §1 |
| R-2 | **Five capabilities:** Knowledge (learns) · Governance (defines) · Execution (executes) · Qualification (verifies) · Evidence (records) | §2 |
| R-3 | Evolution only via Operational Evidence → Qualification → Retrospective → Decision Authority → Update; "never idea → ADR → implementation" | §7 |
| V-1 | *"Evidence has many sources and one destination: a human decision."* "Evidence is architecture; metrics are implementation" | Views §10b |
| P-1 | EKS-RM guiding principles: verification before prescription · canonical ownership before creation (CANDIDATE) · decision authority external to the assistant · evidence-based promotion · *"governance artifacts govern the things they govern; they never become them"* | EKS-RM §2 |
| P-2 | **Two lifecycles, never conflated:** the governance artifact (PROPOSED → adopted → historical) vs the governed work (stages, STOP legal at every gate) | EKS-RM §6 |

## 3. Interpretation

**[I] Correspondences with reported KnowledgeOS constructs** (still second-hand):

| Engineering concept | Reported KnowledgeOS construct | Note |
|---|---|---|
| M-2 Normative / Evidence / Descriptive / Learning | AX-1 *Evidence ≠ Knowledge*; *Claim ≠ Truth* | |
| M-4 orthogonal axes | Conceptual Foundation dimension model | |
| M-3 `supersedes`, `evidences` | provenance (AX-5) and the REVISE/RETRACT operations | |
| D-2 Knowledge Decisions | the "required decisions" of v1 | **v1's mathematical core is in effect a theory of D-2-type decisions.** This is an observation about my own derived work, not about the original theory |

**[?] Two different things are called "theory":**
- the **Engineering Theory** (K-7): software-engineering laws justifying the platform's principles;
- the **KnowledgeOS theory**: what the corpus develops.

Whether the second grew out of the first is part of H-B1, and is untested.
