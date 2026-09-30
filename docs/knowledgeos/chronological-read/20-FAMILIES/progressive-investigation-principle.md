# progressive-investigation-principle

**Scope(s):** THEORY-LEVEL · **Row count:** 5 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Depth_{t+1}=f(D_new,R_new,Uncertainty_t,DecisionContext)`, `Q_t -> O_t -> D_new -> Q_{t+1}` · **Aliases:** `Investigation Levels 0-5`, `investigation loop`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0019`, scope `THEORY-LEVEL`: Principle that investigation depth is not fixed by the initial question but emerges dynamically from dimensions/relationships discovered during observation; proposes six investigation levels (initial observation through decision investigation) and an architectural investigation loop (Question->Observation->Dimension Discovery->Zero/Lord Lens->more investigation?->Guidance).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0766 §"Arjuna did not initially ask a complex question. The complexity emerged from the dimensions revealed by the first observation."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0766 §"Level 0 — Initial observation ... Level 1 — Dimension discovery ... Level 2 — Relationship investigation ... Level 3 — Consequence investigation ... Level 4 — Ideal-state investigation ... Level 5 — Decision investigation"]
- CANDIDATE-FORMAL-BIRTH: [S0766 §"Depth_{t+1} = f(D_{new},R_{new},Uncertainty_t,DecisionContext) ... Investigation depth emerges from discovered knowledge."]
- CANDIDATE-OPERATIONAL-BIRTH: [S0766 §"KnowledgeOS should probably not have a single Observation Engine that produces one final answer. It needs an investigation loop"]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0766. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S0766 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0766 |
| type_signature | PRESENT | S0766, S0766 |
| invariants | PRESENT | S0766 |
| dependencies | PRESENT | S0766, S0766, S0766, S0766, S0766 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0766 |
| examples | PRESENT | S0766 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Reinterprets the Gita chapter-1 narrative arc as evidence for the investigation loop rather than a simple question-answer exchange, since Arjuna's deepening questions to Krishna only arise after progressive dimension discovery increases his uncertainty. [S0766]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0766] types=['PRINCIPLE'] scope=THEORY-LEVEL — "States the Progressive Investigation Principle: an observation may reveal dimensions not part of the original inquiry; newly revealed dimensions can create new questions, alter interpretations, increase uncertainty, and require deeper investigation levels -- formally Q_t -> O_t -> D_new -> Q_{t+1} with Q_{t+1} != Q_t in general." (anchor: "Arjuna did not initially ask a complex question. The complexity emerged from the dimensions revealed by the first observation.")
- [S0766] types=['CONCEPT'] scope=THEORY-LEVEL — "Defines six provisional investigation levels (0 initial observation, 1 dimension discovery, 2 relationship investigation, 3 consequence investigation, 4 ideal-state investigation, 5 decision investigation), each illustrated with the Arjuna case moving from 'who are they' to 'what should I do'." (anchor: "Level 0 — Initial observation ... Level 1 — Dimension discovery ... Level 2 — Relationship investigation ... Level 3 — Consequence investigation ... Level 4 — Ideal-state investigation ... Level 5 — Decision investigation")
- [S0766] types=['FORMALIZATION'] scope=THEORY-LEVEL — "Formalizes that investigation depth at t+1 is a function of newly discovered dimensions, new relationships, current uncertainty and decision context, rather than being predetermined at the outset." (anchor: "Depth_{t+1} = f(D_{new},R_{new},Uncertainty_t,DecisionContext) ... Investigation depth emerges from discovered knowledge.")
- [S0766] types=['ARGUMENT', 'EXAMPLE'] scope=CROSS-OBJECT — "Reinterprets the Gita chapter-1 narrative arc as evidence for the investigation loop rather than a simple question-answer exchange, since Arjuna's deepening questions to Krishna only arise after progressive dimension discovery increases his uncertainty." (anchor: "The sequence in Chapter 1 is not: Arjuna asks -> Krishna answers. It becomes: Arjuna observes -> new dimensions appear -> his knowledge changes -> his uncertainty increases -> his conceptual model becomes inadequate -> he asks deeper questions -> Krishna begins guidance.")
- [S0766] types=['CONCEPT', 'IMPLEMENTATION'] scope=THEORY-LEVEL — "Proposes an architectural consequence: rather than a single one-shot observation engine, KnowledgeOS needs an investigation loop cycling Question -> Observation -> Dimension Discovery -> Zero/Lord Lens -> (more investigation? yes: new question / no: guidance to Knower)." (anchor: "KnowledgeOS should probably not have a single Observation Engine that produces one final answer. It needs an investigation loop")

## Notes for P3
(none beyond what is noted above)
