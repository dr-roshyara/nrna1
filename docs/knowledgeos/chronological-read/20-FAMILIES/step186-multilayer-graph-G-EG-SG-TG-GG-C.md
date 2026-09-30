# step186-multilayer-graph-G-EG-SG-TG-GG-C

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `G = (G_E,G_S,G_T,G_G,G_C); different relation semantics require different graph interpretations`, `G_E (evidence) / G_S (semantic) / G_T (temporal) / G_G (governance) / G_C (causal) as distinct graphs`, `e->p in G_E does not imply p->d in G_G; a->d in governance does not imply a->truth(p)` · **Aliases:** `the multilayer knowledge graph`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0033, scope THEORY-LEVEL: "Discovers there is no single knowledge graph but at least four/five distinct graphs with different edge semantics: Evidence graph G_E (what supports what), Semantic graph G_S (what means/refines/contradicts what), Governance graph G_G (who is authorized to decide what), and potentially a Causal graph G_C (what caused what), plus a Temporal graph G_T -- consolidated into a multilayer graph object bold-G=(G_E,G_S,G_T,G_G,G_C), where layers may reference the same entities but edges must never be collapsed (an edge in G_E does not imply a corresponding edge in G_G; a governance authority edge a->d does not imply a->truth(p)). States the general principle 'different relation semantics require different graph interpretations', judged 'much more faithful to DDD than a single knowledge graph' and 'a major architectural result.' Confirms four separate relations again: Permission!=Authority, Authority!=Evidence, Evidence!=Decision."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1381 §"G_E: evidence; G_S: semantic; G_T: temporal; G_G: governance; G_C: causal. ... e→p in G_E does not mean: p→d in G_G. ... a→d in governance does not imply: a→truth(p). ... Different relation semantics require different graph interpretations. ... G = (G_E,G_S,G_T,G_G,G_C). ... much more faithful to DD…"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1381 §"G_E: evidence; G_S: semantic; G_T: temporal; G_G: governance; G_C: causal. ... e→p in G_E does not mean: p→d in G_G. ... a→d in governance does not imply: a→truth(p). ... Different relation semantics require different graph interpretations. ... G = (G_E,G_S,G_T,G_G,G_C). ... much more faithful to DD…"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1381. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1381 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1381 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1381 |
| dependencies | PRESENT | S1381 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Discovers there is no single knowledge graph but at least five distinct graphs with irreducibly different edge semantics -- Evidence (G_E: what supports what), Semantic (G_S: what means/refines/contradicts what), Temporal (G_T), Governance (G_G: who is authorized to decide what), Causal (G_C: what caused what) -- consolidated into a multilayer graph bold-G=(G_E,G_S,G_T,G_G,G_C), with the principle that different relation semantics require different graph interpretations (an evidence edge does not imply a governance edge; a governance-authority edge does not imply an edge to the truth of a proposition), called 'much more faithful to DDD than a single knowledge graph' and 'a major architectural result.' [S1381]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1381] types=[ANALYSIS, FORMALIZATION] scope=THEORY-LEVEL — "Discovers there is no single knowledge graph but at least five distinct graphs with irreducibly different edge semantics -- Evidence (G_E: what supports what), Semantic (G_S: what means/refines/contradicts what), Temporal (G_T), Governance (G_G: who is authorized to decide what), Causal (G_C: what caused what) -- consolidated into a multilayer graph bold-G=(G_E,G_S,G_T,G_G,G_C), with the principle that different relation semantics require different graph interpretations (an evidence edge does not imply a governance edge; a governance-authority edge does not imply an edge to the truth of a proposition), called 'much more faithful to DDD than a single knowledge graph' and 'a major architectural result.'" (anchor: "G_E: evidence; G_S: semantic; G_T: temporal; G_G: governance; G_C: causal. ... e→p in G_E does not mean: p→d in G_G. ... a→d in governance does not imply: a→truth(p). ... Different relation semantics …")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
