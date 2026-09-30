# eks-current-architecture-baseline

**Scope(s):** OBJECT · **Row count:** 16 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `EKS Current Architecture Baseline` · **Aliases:** `KOS-ARCH-BASELINE-001 candidate`
**Candidate group membership (NOT an identity claim):**
- **G0041**: [`architecture-baseline-001` · `eks-current-architecture-baseline`] — explicit agent-stated uncertainty: 'eks-current-architecture-baseline' POSSIBLY relates to 'architecture-baseline-001' (batch B0005). Note: Multiple competing/successive attempts (2026-08-01, and three more on 2026-08-21) at reconstructing EKS's current-state architecture from evidence; later attempts explicitly compare themselves against and recommend preferring earlier drafts. Possibly the same reconstruction effort tracked by the existing architecture-baseline-001 object (KOS-ARCH-BASELINE-001), which concerns the same subject matter from a later batch's perspective.
- **G0887**: [`architecture-baseline-001` · `eks-current-architecture-baseline`] — working_label token overlap Jaccard=0.50 (shared tokens: ['architecture', 'baseline'])
- **G0889**: [`eks-current-architecture-baseline` · `pks-current-architecture-baseline-stage2`] — working_label token overlap Jaccard=0.50 (shared tokens: ['architecture', 'baseline', 'current'])
- **G1148**: [`eks-current-architecture-baseline` · `knowledgeos-epistemic-architecture-investigation`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0005, scope OBJECT) (relation_to_existing: POSSIBLY:architecture-baseline-001): Multiple competing/successive attempts (2026-08-01, and three more on 2026-08-21) at reconstructing EKS's current-state architecture from evidence; later attempts explicitly compare themselves against and recommend preferring earlier drafts. Possibly the same reconstruction effort tracked by the existing architecture-baseline-001 object (KOS-ARCH-BASELINE-001), which concerns the same subject matter from a later batch's perspective.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0186] §"EKS is presently a governance-oriented engineering knowledge system whose primary durable knowledge representation is repository-based YAML/Markdown ... Its conceptual model is more mature than its executable software implementation."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0189] §"This document establishes a CURRENT ARCHITECTURE BASELINE. It does not authorize: redesign; refactoring; bounded-context creation; service extraction; event-bus introduction; platform extraction; adaptive learning; technology migration."

## Lifecycle
last_seen: S0201. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type=True

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S0186, S0186, S0201 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S0201 |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S0186, S0186, S0188, S0188, S0189 |
| Examples | PRESENT | S0189 |
| Warnings | PRESENT | S0186, S0188, S0201 |
| Experiments | PRESENT | S0188, S0188, S0189, S0201 |
| Open questions | PRESENT | S0196 |

## Rationale
States this document's strongest current-state summary sentence for EKS, explicitly refusing to describe it as a mature microservice platform, event-driven knowledge graph, or PostgreSQL-centered product. [S0186] Traces four successive historical architecture 'generations' of PKS/EKS/KnowledgeOS naming and positioning, showing a reversal from a certified repository-as-system-of-record position through a PostgreSQL-centric proposal and back to a repository-centric reconciliation. [S0186] Tests CAPPI's orthogonality/double-dampening warning against the estate and finds the real analog is not a reliability-vs-uncertainty conflation but a triple redundant implementation of one cohesion measurement (three independent LCOM4 collectors) with no canonical winner. [S0201]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0186] types=[RESTATEMENT, ANALYSIS] scope=OBJECT — "States this document's strongest current-state summary sentence for EKS, explicitly refusing to describe it as a mature microservice platform, event-driven knowledge graph, or PostgreSQL-centered product." (anchor: "EKS is presently a governance-oriented engineering knowledge system whose primary durable knowledge representation is repository-based YAML/Markdown ... Its conceptual model is more mature than its executable software implementation.")
- [S0186] types=[CONTRADICTION] scope=OBJECT — "Records an unresolved contradiction between the certified 'PKS is not software' position and later PHP/Laravel/PostgreSQL descriptions, deliberately preserved rather than reconciled." (anchor: "the certified PKS documents explicitly state that the PKS is knowledge specifications in YAML + Markdown and does not require PHP, Python, or another programming language, while other documents describe PHP/Laravel/PostgreSQL software ... this baseline does not resolve that contradiction artificially.")
- [S0186] types=[ANALYSIS, EXTENSION] scope=THEORY-LEVEL — "Traces four successive historical architecture 'generations' of PKS/EKS/KnowledgeOS naming and positioning, showing a reversal from a certified repository-as-system-of-record position through a PostgreSQL-centric proposal and back to a repository-centric reconciliation." (anchor: "G0 PKS 'documentation/instruction system' → self-correction → G1 PKS knowledge system YAML+Markdown+Git → G2 KnowledgeOS proposal 6 bounded contexts → G3 EKS proposal 7 contexts→4 contexts+planes PostgreSQL-centric → G3/v3 reconciliation returns to: repository system of record.")
- [S0186] types=[LIMITATION] scope=OBJECT — "Concludes there is no authoritative current C4 container model for EKS; several competing-generation C4 diagrams exist and must not be read as evidence of current runtime boundaries." (anchor: "The current C4 CONTESTED / Target C4 diagrams PROPOSED ... Do not use them as evidence for current runtime boundaries.")
- [S0186] types=[WARNING, PRINCIPLE] scope=METHODOLOGICAL — "Warns against mapping existing scripts directly to services/bounded contexts, treating them instead as a capability inventory (Workflow engine → governance/execution mechanism, Session resolver → workflow infrastructure, etc.)." (anchor: "scripts should first be understood as mechanisms and capabilities, not directly mapped into services or bounded contexts.")
- [S0188] types=[PRINCIPLE, EXPERIMENTAL-RESULT] scope=OBJECT — "States the highest-confidence current-state finding of this EKS baseline: measured directly from 9 work items and 20 grants, every grant references a human act and is registered by governance, evidencing that the mechanism records rather than creates authority." (anchor: "The mechanism records authority; it does not grant authority ... All 20/20 grants carry humanActRef, and registeredBy has exactly one observed value — governance.")
- [S0188] types=[LIMITATION, EXPERIMENTAL-RESULT] scope=OBJECT — "States the principal measured weakness: authority is durably recorded but the record contains no validity/delegation/ownership fields, so historical authority cannot be reliably reconstructed at any past point in time." (anchor: "Grants with validity information 0/20 ... Grants with delegation information 0/20 ... Grants with ownership information 0/20 ... Was this authority valid at a particular historical point in time? ... a current-state limitation, not a future-architecture opinion.")
- [S0188] types=[PRINCIPLE] scope=OBJECT — "States that AI-process participation is current infrastructure, but permitted execution must not be confused with holding authority, following directly from the observed authority model." (anchor: "An AI agent must not be interpreted as an authority holder merely because it executes a permitted activity.")
- [S0188] types=[CORRECTION] scope=OBJECT — "A second-session self-critique downgrades an earlier classification: observed progressive grant-narrowing behaviour does not establish that the business concept 'Delegation' exists in EKS, so the classification must be weakened to avoid treating an interpretation as an architectural fact." (anchor: "I would not yet call 'Delegation modelling' IMPLEMENTED-BUT-IMPLICIT merely from the progressive narrowing of grants ... change it to: OBSERVED BEHAVIOUR — SEMANTIC INTERPRETATION UNKNOWN.")
- [S0188] types=[CORRECTION, WARNING] scope=OBJECT — "Warns that classifying 'Rule governance' as PARTIAL risks importing the exploratory Track-2 Rule Model's semantics into a current-state EKS claim, and proposes the safer statement that Rule-like mechanisms exist while the Track-2 model itself is not established as current architecture." (anchor: "I would also weaken: Rule governance — PARTIAL ... because that potentially mixes exploratory Track 2 Rule semantics with the current EKS implementation.")
- [S0189] types=[RESTATEMENT] scope=THEORY-LEVEL — "States the single strongest coherent description of the current EKS architecture that this document's evidence supports, offered as the closing summary of a 60-section baseline." (anchor: "EKS turns engineering activity into evidence, evidence into bounded recommendations and decisions, and decisions into durable engineering knowledge — while keeping architectural authority with humans and keeping future architectural evolution evidence-gated.")
- [S0189] types=[EXPERIMENTAL-RESULT, EXAMPLE] scope=OBJECT — "Cites a demonstrated case where governance discipline changed contributor behavior even without a mechanical enforcement gate, distinguishing tool-enforced impossibility from behavioral-governance restraint." (anchor: "Behavioral governance demonstrated (tier 5 record): the governance changed real contributor behavior (a new artifact written instead of amending a frozen one) even where the automated gate was absent — 'tool enforcement says you COULDN'T; behavioral governance says you COULD have, and you didn't.'")
- [S0189] types=[GOVERNANCE, CONSTRAINT] scope=METHODOLOGICAL — "States the baseline's own authority boundary and the seven-value classification vocabulary (CURRENT/IMPLEMENTED-BUT-IMPLICIT/PARTIAL/DOCUMENTED-ONLY/HISTORICAL/PROPOSED/UNKNOWN) that any future proposal evaluated against it must use." (anchor: "This document establishes a CURRENT ARCHITECTURE BASELINE. It does not authorize: redesign; refactoring; bounded-context creation; service extraction; event-bus introduction; platform extraction; adaptive learning; technology migration.")
- [S0196] types=[OPEN-QUESTION] scope=CROSS-OBJECT — "Leaves the PKS/EKS identity relationship explicitly UNRESOLVED from PKS evidence alone, refusing to import EKS semantics to close the ambiguity per the study's amendment 4." (anchor: "whether EKS and PKS are the same system, different systems, or overlapping systems is UNRESOLVED by PKS evidence (§24 U-02).")
- [S0201] types=[CONSTRAINT, WARNING] scope=METHODOLOGICAL — "Applies a binding evidence-completeness discipline given a discovered defect in a prior-phase deliverable (the EKS Stage-1 baseline is missing its final five sections and registers): an absence in an incomplete corpus must be recorded as UNKNOWN, never conflated with a positive absence finding." (anchor: "the EKS baseline is BROKEN / INCOMPLETE (ends at §20; §21–§25 absent; U-/X- registers missing). Therefore 'EKS does not evidence X' is recorded as UNKNOWN (incomplete corpus), never as NOT-PRESENT.")
- [S0201] types=[ANALYSIS, EXPERIMENTAL-RESULT] scope=OBJECT — "Tests CAPPI's orthogonality/double-dampening warning against the estate and finds the real analog is not a reliability-vs-uncertainty conflation but a triple redundant implementation of one cohesion measurement (three independent LCOM4 collectors) with no canonical winner." (anchor: "the real double-evaluation defect in current systems is EKS's three LCOM4 implementations — one semantic concern evaluated by three independent mechanisms with no canonical choice.")

## Notes for P3
Carries 4 candidate group memberships (G0041, G0887, G0889, G1148); P3 should prioritize resolving whether these reflect the same underlying object — multiple memberships here only means more surface signal touched this label, not that it is more likely to be a duplicate. Lifecycle is mechanically CONTESTED because this label's own rows include a CONTRADICTION-typed row — P3 should read the conflicting rows directly (see 'All rows' above) to determine which claim (if either) should stand, rather than treating CONTESTED as itself a resolution.
