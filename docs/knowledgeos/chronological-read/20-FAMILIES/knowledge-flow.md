# knowledge-flow

**Scope(s):** OBJECT · **Row count:** 50 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Evidence → improves KnowledgeOS`, `the flywheel` · **Aliases:** `the evolution loop`, `the platform lifecycle loop`
**Candidate group membership (NOT an identity claim):**
- G0680: [`evidence` · `knowledge-flow`] — an UNKNOWN-OBJECT-CANDIDATE row (batch B0012, source S0446) named these as alternative candidates for one piece of evidence. why_uncertain: Proposes an Observation->Inference->Claim decomposition (observed_fact/source/measurement/context/time under Observation; method/assumptions/alternatives/evidence/uncertainty under Inference) that may overlap with existing Evidence/Observation concepts but is not confirmed identical.
- G1092: [`knowledge-flow` · `pks`] — labels co-occur in the same contribution's labels[] 4 separate times across the corpus
- G1104: [`capability` · `knowledge-flow`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- G1110: [`knowledge-flow` · `provenance`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0001, scope OBJECT): The proposed/discovered lifecycle chain by which operational evidence is meant to flow back into and improve the platform (KnowledgeOS creates PKS -> guides Product -> produces Evidence -> improves KnowledgeOS); repeatedly graded, corrected (0 traversals vs n≈3), and central to many documents.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0002] §"Platform Lifecycle (Idea→Knowledge→PKS→Implementation→Evidence→Evolution) ... B-1 correction: software→evidence OBSERVED · back-edge n≈3 informal / ≈2 formal · KnowledgeOS→PKS n=0 · Genesis has NO route"
- CANDIDATE-CONCEPTUAL-BIRTH: [S0002] §"Knowledge Flow — four kinds of change + one non-progression; "evidence EARNS · governance GRANTS · promotion requires BOTH""
- CANDIDATE-FORMAL-BIRTH: [S0008] §"The corrected canonical chain: MISSION (enacted) → STRATEGY → PRINCIPLE → DESIGN POLICY → CAPABILITY ..."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0449. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type: True.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0002, S0003, S0005, S0008, S0010, S0016, S0017, S0018, S0446 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0008, S0016, S0018, S0021, S0028, S0029, S0038, S0040 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0002, S0007, S0008, S0009, S0016, S0018, S0021, S0040 |
| dependencies | PRESENT | S0002, S0003, S0004, S0005, S0007, S0008, S0009, S0010, S0014, S0015, S0017, S0018, S0021, S0025, S0027, S0028, S0038, S0446 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0002, S0004, S0009, S0010, S0016, S0018, S0025, S0027, S0040 |
| examples | PRESENT | S0015 |
| warnings | PRESENT | S0010, S0027 |
| experiments | PRESENT | S0002, S0005, S0019, S0025, S0027 |
| open_questions | PRESENT | S0004, S0015 |

## Rationale
The platform lifecycle is graded link-by-link: software-to-evidence is observed; the back-edge is n≈3 informal / ≈2 formal; KnowledgeOS-to-PKS generation is n=0; and the Genesis stage has no route at all. Individual grades are confirmed; the loop as a whole remains a hypothesis [S0002]. The context map is sharpened: KnowledgeOS governs retrieval architecture and competes with none of it, deepening the R-2 mapping: governance (PAP) -> DP-n/capability (PDP) -> retrieval boundary (PEP). The industry's 'enforce before ranking' is the PEP placed exactly where R-7 already put it; KnowledgeOS is upstream policy, retrieval engines are downstream enforcement surfaces — a supplier relationship, not a rivalry [S0003]. Each edge of the composite R-6 chain is graded individually: discovers/models/governs are individually exercised and validated (Rounds 16-31 executed; the method survived a foreign domain); generates is n=0 inside and outside; evolves is n≈3 informal (B-1's fact); guides-product-engineering is partial (protocol used, effect unmeasured, no unguided baseline) with a first formal behavioural record OE-KOS-1 (WP-4B delivery planning obeyed the architecture); engineers-software is observed (1,532 files, the WP deliveries); collects-evidence is observed (106 reports, CAP-001 section 9); improves-KnowledgeOS is n≈3 informal, the loop's back-edge, same fact as evolves seen from the platform side [S0005]. The proposed chain is validated edge by edge: 'KnowledgeOS creates PKS' is a hypothesis (n=0, every PKS is authored by a person); 'PKS contains Engineering Knowledge' is WRONG, violating I-7 (a PKS contains product knowledge and bindings; reusable method must not live in it — the current breach is the exception that proves the rule); 'knowledge organized by Ontology' holds with the precision that L-A organizes artifacts/carriers, not knowledge itself (I-1); 'used by Capabilities' holds (capabilities may read across space boundaries, never own — H-1); 'Capabilities executed through Runtime' is imprecise (capabilities are advisory scripts invoked on demand; the runtime executes Execution Assets/ASTs; 41 enforced controls live runtime-side while every capability is advisory — the drawn edge hides this finding); 'Runtime produces Evidence' should attribute production to WORK (P-7/P-8), the runtime only hosts the work; 'Evidence improves KnowledgeOS' is only partly true (n≈3 informal, B-1's fact) [S0008]. OKF does not prove: that the feedback loop works (an article cannot prove this; the repository has the mechanism and zero traversals); that a knowledge layer improves engineering outcomes (no unguided baseline exists); that KnowledgeOS is a product (canon rules otherwise, AIP-14 Supporting Subdomain); that engineering knowledge behaves like enterprise knowledge (the article does not address engineering knowledge at all). The blocker on the feedback-loop pipeline is identified as a gated entry point, not apathy: ES-006.4's harvest question is asked at the retrospective, and the retrospective has not run — a concrete, dated, falsifiable blocker, more actionable than '0 traversals' [S0010]. The Knowledge Layer is distinct from the Tool Layer in CAP-001 (Domain/Application are knowledge-free, Infrastructure reads knowledge), but a real conflation exists elsewhere: two knowledge bodies exist — the governed L-A corpus (in the graph, docs/knowledge/**) and the operational registers CAP-001 actually consumes (outside it, governed-registers.yaml) — no single 'Knowledge Layer' spans both [S0010]. The Knowledge != Artifact edge makes a recurring three-times-repeated error class unstatable: claims like 'there is no strategic-DDD method', 'there is no capability pattern', 'the mission layer is vacant' become inexpressible, since under this model one can only say 'no artifact carries it', a representation claim, not a nature claim. A meta-model earns its place by making a known error class impossible to phrase; this one does. The reviewer's hierarchy is adopted: MISSION -> ENGINEERING CAPABILITY -> ENGINEERING KNOWLEDGE -> ENGINEERING ARTIFACT (the lowest reusable layer) -> PKS -> PRODUCT; documents are the bottom of the model, not its centre [S0016]. Nine ontology elements are each bound to the specific decision they serve, applying Evans' test: KNOWLEDGE!=ARTIFACT (I-1) underwrites the deletion litmus of DetermineArtifactLifecycle (unaskable unless knowledge and file differ); AUTHORITY-SCOPE serves DetermineConcern/DeterminePlacement; GOVERNANCE-STATUS serves DeterminePromotionPath; NATURE:normative-force serves DetermineApplicableStandards; DP-n anchoring serves the approve-capability-change decision; CONTAINER boundary would serve the extraction/adoption decision (the MVK FAIL is this decision made without the model); PROJECTION+attestation (corrected I-4) serves the trust-or-rebuild decision; EVIDENCE-STATUS serves the earns/grants promotion gate; the four partial orders serve the direction-of-change-propagation decision [S0017]. Multiple progression mechanisms exist because engineering knowledge changes in four causally different ways (governance position by acts per decision by named roles; evidential standing by evidence per occurrence, owned by nobody, earned; the work around it by time per session, owned by the executing team; its origin, which never changes) and the repository mostly deliberately refuses to conflate them. A single unified lifecycle would force one engine onto all four, requiring either that evidence be grantable by decree or that decisions wait on evidence that may never come; the repository's two declared orthogonalities and GEP-F1 are three prior refusals of exactly that fusion. No mechanism needs redesign on this evidence; what P-2 needs is separation of its two questions, which is an ARB decision [S0018]. Freedman is framed as improving RAG: the RAG pipeline (retrieve evidence) is extended to Evidence Retrieval -> Evidence Qualification -> Inference Construction -> Assumption Analysis -> Alternative Explanation Analysis -> Validation -> Claim [S0446]. Connects this book to a prior 'Human-AI Interaction' book: that book said AI should not replace human judgment, and Freedman explains why -- judgment determines which question/data/assumptions/mechanism/confounders/alternatives/method/evidence and what a result establishes; the architecture should therefore start with Question -> Research framing -> Evidence -> Method -> Inference -> Challenge -> Validation -> Claim, not AI -> answer [S0446].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
This label has 50 rows — too many to list individually while keeping the file readable. Rows are grouped into content-based themes below; each theme names the rows condensed into it (by position in the ledger-order row list for this label) and its representative source_id(s). Full text for every row is in `03-CONTRIBUTIONS.jsonl`.

**Platform lifecycle link-by-link grading and the Engineering Progression Model** (2 rows condensed into this theme; source(s): S0002)
Rows 0-1. Grades the platform lifecycle link-by-link (software-to-evidence observed; the back-edge only n≈3 informal/≈2 formal; KnowledgeOS-to-PKS generation ungrounded); the Engineering Progression Model identifies four kinds of change plus one non-progression kind, governed by 'evidence EARNS, governance GRANTS'.

**Context-map sharpening and the R-2..R-6 relationship-validation register** (5 rows condensed into this theme; source(s): S0002, S0003, S0005)
Rows 2, 5-8. Sharpens the context map (KnowledgeOS governs retrieval architecture, competing with none of it); validates R-2 (policy defined/decided separately from enforced, cf. XACML PAP/PDP), R-3 (Knowledge represented by Artifact, cf. FRBR's work/expression/manifestation chain), and R-5 (Evidence harvested into Candidate, granted into Promotion, cf. PDSA); grades each edge of the composite R-6 chain individually.

**Open questions and unopened validation streams** (2 rows condensed into this theme; source(s): S0004)
Rows 3-4. Poses PM-6 (who retires KNOWLEDGE, as distinct from artifacts, given existing artifact-retirement machinery) and records a third validation stream (Lifecycle Validation, over-time transitions) as identified but not yet opened.

**External-literature validation of core distinctions** (5 rows condensed into this theme; source(s): S0005, S0007)
Rows 9, 10-13. Further enriches R-2 with retrieval-boundary-enforcement industry practice; supports 'derived, non-authoritative projections' via CQRS/event-sourcing; supports 'evidence earns, governance grants' via NASA TRL, GRADE, and CMMI; supports Knowledge≠Artifact (I-1) via Polanyi/Nonaka and FRBR; finds the four-progression-kind taxonomy only partially supported in the literature.

**Canonical-chain correction and ontology-candidate rejection** (3 rows condensed into this theme; source(s): S0008, S0009)
Rows 14-16. Validates the proposed chain edge by edge, finding 'KnowledgeOS creates PKS' an unevidenced hypothesis (n=0); replaces it with a corrected canonical chain MISSION(enacted)→STRATEGY→PRINCIPLE→DESIGN POLICY→CAPABILITY; rejects and splits the 'Knowledge Assets' ontology candidate because Knowledge≠Artifact.

**OKF limitations and the Knowledge-Layer/Tool-Layer distinction** (3 rows condensed into this theme; source(s): S0010)
Rows 17-19. Notes what an external framework (OKF) does not prove — that the feedback loop works, that a mechanism with zero traversals is functioning; scores the lowest-confidence OKF insight LOW; distinguishes the Knowledge Layer from the Tool Layer within CAP-001.

**Independent double-derivation validation and retiring the '0 traversals' claim** (5 rows condensed into this theme; source(s): S0014, S0015)
Rows 20-24. Records twelve category-A HIGH-confidence conclusions reproduced by mutually blind derivation paths (KnowledgeOS is a governed hypothesis, gate shut, Vision unapproved, among others); retires the repeated, over-generalized '0 traversals, never traversed' claim in favor of a factual n≈3 correction (IR-5).

**Core concepts, the Knowledge≠Artifact error class, and orthogonality findings** (6 rows condensed into this theme; source(s): S0016, S0017, S0018)
Rows 25-30. Defines six core concepts (MISSION and others) by responsibility/lifecycle/owner rather than by folder; shows Knowledge≠Artifact makes a recurring error class ('there is no strategic-DDD method') unstatable; binds nine ontology elements each to a specific decision (Evans' test); reduces ten mechanisms to four kinds of change plus one non-progression kind; records orthogonality findings (status vs. authority, ADR-status vs. maturity, both declared).

**Lifecycle-link grading, the eleven-invariant register, and pre-validation corrections** (3 rows condensed into this theme; source(s): S0019, S0021, S0022)
Rows 31-33. Grades four commissioned lifecycle links (KnowledgeOS→creates→PKS remains hypothesized, n=0); traces eleven invariants to governed statements (I-1 Knowledge is represented by an Artifact and is never the Artifact, I-2, and others); records four pre-validation corrections, notably restating 'Knowledge is never generative' as 'Knowledge does not execute; Capabilities execute'.

**Operational-evidence register OE-KOS-1..4 and the Stream A/Stream B distinction** (7 rows condensed into this theme; source(s): S0025, S0027)
Rows 34-40. Records a bootstrap-experiment FAIL scoped to one 15-file MVK-extraction boundary (not the platform generally); records real operational evidence — a WP-4B delivery-planning exercise, a WP-4B RED-ratchet iteration isolating K2 as the slice's sole behavioural invariant, the Developer-Guide Definition of Done being skipped across every engineering-tooling step, and a WP-4C-2 discovery that explicitly refused to answer the design question it uncovered; distinguishes Stream A (Operational Evidence, append-only) from Stream B (Learning Candidates); checks (rather than assumes) R-6's n=3 count is valid at the activity level.

**Evidence-harvest correction and the deferred-concept register** (2 rows condensed into this theme; source(s): S0028, S0029)
Rows 41-42. Corrects the evidence-harvest pattern from Evidence→Decision→Change to Evidence→HARVEST→Candidate→Promotion→Change; records a register of ~24 deferred architectural concepts, each with a current invariant protecting the need and an explicit activation condition.

**Lifecycle dependency graphs and the inferred multi-stage knowledge/engineering process** (4 rows condensed into this theme; source(s): S0031, S0038, S0040)
Rows 43-46. Shows a dependency graph in which KnowledgeOS→generates→PKS is speculative (no PKS yet generated by it); infers a knowledge lifecycle Discovery→Assessment→Challenge→Saturation Gate→Decision→Governance→Execution→Evidence→Harvest; describes a six/seven-stage engineering-reality process (collectors observe metrics→Observation→Recommendation→...) with four process rules (every stage transition is a recorded, timestamped, provenanced event; projections are derived).

**External-literature framing: RAG extension, judgment, and the Intervention Lens** (3 rows condensed into this theme; source(s): S0446, S0449)
Rows 47-49. Frames Freedman as improving RAG (Evidence Retrieval→Evidence Qualification→Inference Construction); connects to a prior 'Human-AI Interaction' book's claim that AI should not replace human judgment; proposes an Intervention Lens representing claims as do(X) interventions with expected outcome and counterfactual, enabling an OBSERVE→HYPOTHESIZE→INTERVENE→OBSERVE loop.


## Notes for P3
Carries 4 candidate group membership(s); P3 should prioritize resolving whether these reflect the same underlying object.
