# expressiveness-tractability-tradeoff-principle

**Scope(s):** THEORY-LEVEL · **Row count:** 6 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Expressiveness <-> Tractability` · **Aliases:** `representation-reasoning tradeoff`
**Candidate group membership (NOT an identity claim):**
- **G0572**: [`dl-complexity-results-table` · `expressiveness-tractability-tradeoff-principle`] — explicit agent-stated uncertainty: 'dl-complexity-results-table' POSSIBLY relates to 'expressiveness-tractability-tradeoff-principle' (batch B0061). Note: Table of satisfiability/subsumption complexity per DL language (FL0, AL, ALE, ALU, ALC, ALCN, ALC+TBox), offered as justification for a KnowledgeOS language-selection criterion.
- **G1791**: [`consistency-operator-vs-contradiction-distinction` · `expressiveness-tractability-tradeoff-principle`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1796**: [`expressiveness-tractability-tradeoff-principle` · `requirement-subsumption-preceq-r-candidate`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1797**: [`expressiveness-tractability-tradeoff-principle` · `successor-state-semantics-candidate`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1798**: [`expressiveness-tractability-tradeoff-principle` · `terminological-assertional-knowledge-split`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1799**: [`expressiveness-tractability-tradeoff-principle` · `zero-vs-closed-world-assumption-comparator`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0061, scope THEORY-LEVEL: "Candidate theory-level methodological principle that a KnowledgeOS representation is not justified by semantic expressiveness alone; its reasoning/computational tractability consequences must be considered, borrowed from the classical KR expressiveness/tractability tradeoff."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2520 §"Expressiveness <-> Tractability ... reasoning by cases can become computationally explosive"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2523 §"KR.1 Representation Layers ... KR.6 Open-World Constraint ... KR.9 Transition ... KR.10 Expressiveness and Tractability"]

## Lifecycle
last_seen: S2524. Candidate lifecycle: ACTIVE. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the ACTIVE classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S2522 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S2523 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2520, S2521, S2523, S2524 |
| examples | PRESENT | S2522 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Expressiveness/tractability tradeoff restated with a concrete 'computational cliff' example: adding role restriction to FL- makes subsumption coNP-hard, versus polynomial without it — offered as underlying principle for [PROP]-status constructs in KnowledgeOS. [S2522]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2520] types=[PRINCIPLE] scope=THEORY-LEVEL — "Candidate Theory-level methodological principle: a KnowledgeOS representation is not justified merely because it is semantically expressive; its reasoning consequences and computational tractability must be explicitly considered — an independent formal reason to resist building one universal semantic structure." (anchor: "Expressiveness <-> Tractability ... reasoning by cases can become computationally explosive")
- [S2521] types=[PRINCIPLE] scope=THEORY-LEVEL — "Methodological principle: expressiveness does not justify computational complexity by itself; every proposed KnowledgeOS semantic mechanism must separately answer a semantic question (what distinctions can it express) and a computational question (what reasoning can be performed, with what guarantees/cost) — neither substitutes for the other, grounded in KR&R's expressiveness/tractability tradeoff and reasoning-by-cases explosion." (anchor: "Expressiveness does not justify computational complexity by itself ... Semantic question: What distinctions can the representation express? Computational question: What reasoning can be performed over…")
- [S2522] types=[EXPLANATION, EXAMPLE] scope=THEORY-LEVEL — "Expressiveness/tractability tradeoff restated with a concrete 'computational cliff' example: adding role restriction to FL- makes subsumption coNP-hard, versus polynomial without it — offered as underlying principle for [PROP]-status constructs in KnowledgeOS." (anchor: "There is a tradeoff between the expressiveness of a representation language and the difficulty of reasoning ... A slight increase in the expressiveness of a Description Logic may result in a drastic c…")
- [S2523] types=[PRINCIPLE, EXTENSION] scope=THEORY-LEVEL — "Derives a kernel-selection criterion from the expressiveness/tractability tradeoff: a candidate kernel operation must satisfy Expressiveness + Soundness + Completeness_(declared domain) + ComplexityBound where appropriate; explicit warning that tableau soundness/completeness for a specified DL semantics does not imply KnowledgeOS as a whole can or should be complete. Kernel remains NOT SELECTABLE; this is a selection criterion, not a kernel definition." (anchor: "Semantic expressiveness and computational tractability must be evaluated separately ... A richer representation is not automatically a better KnowledgeOS representation ... a candidate kernel operatio…")
- [S2523] types=[GOVERNANCE, RESTATEMENT] scope=THEORY-LEVEL — "Proposes inserting a 10-section 'KNOWLEDGE REPRESENTATION AND STRUCTURED REASONING' theory addition (KR.1 Representation Layers, KR.2 Reasoning Cn_S, KR.3 Subsumption, KR.4 Classification, KR.5 Consistency, KR.6 Open-World Constraint, KR.7 Evaluation via reasoning-service family, KR.8 Explanation/Missing Assumptions, KR.9 Transition/successor-state with Boundary!=FrameAxiom retained, KR.10 Expressiveness/Tractability), consolidating all the file's individually-derived candidates into one proposed section." (anchor: "KR.1 Representation Layers ... KR.6 Open-World Constraint ... KR.9 Transition ... KR.10 Expressiveness and Tractability")
- [S2524] types=[RESTATEMENT, GOVERNANCE] scope=THEORY-LEVEL — "Restates the ten-section KR.1-KR.10 theory insertion verbatim from S2523 with an explicit status tag assigned to each section: KR.1 Representation [PROP], KR.2 Reasoning [PROP], KR.3 Subsumption [PROP], KR.4 Classification [PROP], KR.5 Consistency [PROP], KR.6 Open-World Constraint promoted to [ESTABLISHED], KR.7 Evaluation [PROP], KR.8 Explanation/Gap [PROP], KR.9 Transition [PROP] (Boundary != FrameAxiom retained), KR.10 Expressiveness/Tractability promoted to [ESTABLISHED] as a KnowledgeOS methodological principle -- the only two sections elevated above [PROP]." (anchor: "KR.1 Representation Layers [PROP] ... KR.6 Open-World Constraint [ESTABLISHED] — Supported by DL's open-world semantics ... KR.10 Expressiveness and Tractability [ESTABLISHED] — Now a KnowledgeOS meth…")

## Notes for P3
- family.files_touching lists source_id(s) ['S2526'] that do not appear among this label's own family.rows — a data-completeness oddity for P3 to check against 03-CONTRIBUTIONS.jsonl.
