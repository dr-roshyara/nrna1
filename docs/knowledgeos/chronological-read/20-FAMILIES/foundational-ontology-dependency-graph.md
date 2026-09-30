# foundational-ontology-dependency-graph

**Scope(s):** THEORY-LEVEL · **Row count:** 6 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `X -> Y definitional dependency` · **Aliases:** `EXP-16..EXP-18`, `Step 241 Dependency Graph`
**Candidate group membership (NOT an identity claim):**
- **G1704**: [`foundational-ontology-dependency-graph` · `step266-class-c-noncomputable-objects`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0041`, scope `THEORY-LEVEL`: An executed, per-edge-sourced definitional dependency graph over ~26 KnowledgeOS terms (Observation, Entity, Proposition, Evidence, Assertion, K, Policy, Authority, Governance, etc.), tested for cycles via Tarjan SCC and for transitive dependence on non-computable (class-C) objects.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1687 §"Policy: (['Authority','Assertion'], ...) ... Authority: (['Assertion','Policy'], ...) ... strongly-connected components with >1 node"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1691. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S1687, S1687 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S1687, S1687, S1687 |
| invariants | PRESENT | S1691 |
| dependencies | PRESENT | S1687, S1687, S1687, S1691, S1691, S1691 |
| assumptions | PRESENT | S1687, S1691 |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1687, S1687, S1687, S1691, S1691, S1691 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
EXP-17 computes, for every node in the dependency graph, whether its definition transitively reaches one of Step 266's eight class-C (no-decision-procedure) objects (Relevance, Truth, Adequacy, Authority, Assessment, EpistemicStatus, Minimality, Policy), reporting the count of blocked nodes and the specific dependency path for each, plus the set of nodes NOT blocked. [S1687]

EXP-18 implements a removal test: for each of eleven candidate primitives (Observation, Proposition, Entity, Dimension, Value, Time, Source, Context, RelationType, Assertion, Relation), it computes the fixpoint of which nodes remain definable after deleting that candidate, reporting the surviving node count and the list of lost (no-longer-definable) nodes, operationalizing necessity as 'removal destroys K' versus non-load-bearing. [S1687]

## Assumption register
| Statement | Stated | Source | Anchor |
|---|---|---|---|
| the definitional dependency of each term as stated in its cited corpus passage is accurately captured by the listed edges | USED-UNSTATED | S1687 | DEPS table with per-node source column |
| where a definition is ambiguous, the reading chosen makes the graph more acyclic (bias against finding a problem) | EXPLICIT | S1691 | the test is biased AGAINST finding a problem |

## All rows (source_id order)
- [S1687] types=['EXPERIMENTAL-RESULT'] scope=OBJECT — "EXP-16 encodes 26 corpus-sourced definitional edges (e.g. Policy depends on Authority and Assertion; Authority depends on Assertion and Policy; Evidence depends on Observation/Proposition/Context/Rule; K depends on Assertion/Relation) and runs Tarjan's SCC algorithm to detect cycles, reporting any strongly-connected components of size >1 as circular definitions, with a witness path printed for each." (anchor: "Policy: (['Authority','Assertion'], ...) ... Authority: (['Assertion','Policy'], ...) ... strongly-connected components with >1 node")
- [S1687] types=['EXPERIMENTAL-RESULT', 'ANALYSIS'] scope=OBJECT — "EXP-17 computes, for every node in the dependency graph, whether its definition transitively reaches one of Step 266's eight class-C (no-decision-procedure) objects (Relevance, Truth, Adequacy, Authority, Assessment, EpistemicStatus, Minimality, Policy), reporting the count of blocked nodes and the specific dependency path for each, plus the set of nodes NOT blocked." (anchor: "nodes whose definition transitively reaches a class-C object: {n}/{len(g)} ... nodes NOT blocked ({len(clean)}): {clean}")
- [S1687] types=['EXPERIMENTAL-RESULT', 'EXPLANATION'] scope=OBJECT — "EXP-18 implements a removal test: for each of eleven candidate primitives (Observation, Proposition, Entity, Dimension, Value, Time, Source, Context, RelationType, Assertion, Relation), it computes the fixpoint of which nodes remain definable after deleting that candidate, reporting the surviving node count and the list of lost (no-longer-definable) nodes, operationalizing necessity as 'removal destroys K' versus non-load-bearing." (anchor: "a candidate whose removal destroys K is NECESSARY for K as defined. A candidate whose removal leaves K standing is not load-bearing for K -- it may still be load-bearing for something else, which the table shows explicitly.")
- [S1691] types=['EXPERIMENTAL-RESULT'] scope=THEORY-LEVEL — "Running Tarjan SCC on the sourced 26-node/42-edge definitional dependency graph finds exactly one strongly-connected component of size >1: a 7-node cycle {Assertion, Assessment, Authority, EpistemicStatus, Evidence, Policy, Rule}, with a concrete witness path Assertion->Evidence->Rule->Policy->Authority->Assertion, each edge individually sourced to a corpus passage (Step 267 §267.6, Closure-04, Step 253 §253.10, Step 270 §270.2/Step 155, Step 187 §187.23); the graph construction was deliberately biased toward acyclicity wherever a definition was ambiguous." (anchor: "CYCLE: {Assertion, Assessment, Authority, EpistemicStatus, Evidence, Policy, Rule} witness path: Assertion -> Evidence -> Rule -> Policy -> Authority -> Assertion ... this is DERIVED, and it is the mandate's §8 question 2 answered in the affirmative: yes, there is circularity.")
- [S1691] types=['EXPERIMENTAL-RESULT', 'CORRECTION'] scope=THEORY-LEVEL — "Finding ON-2: propagating Step 266's class-C (no-decision-procedure) set through the dependency graph via its own stated principle Computability(T) => Computability(all semantic dependencies of T) -- which Step 266 states but never applies -- blocks 11 of 26 nodes including K, T (transformation), the congruence relation, K*, and History; the ten unblocked (computable) nodes are Context, Dimension, Entity, Observation, Proposition, Provenance, RelationType, Source, Time, Value. This sharpens Step 266 §266.34's 'K* is still not proven' into: K as currently defined cannot be computable, because Assertion carries an evidence field whose relation depends on a relevance predicate with no decision procedure." (anchor: "nodes whose definition transitively reaches a class-C object: 11/26 ... the central object K is itself in the blocked set, and so are T, ==, K* and History. Step 266's own candidate principle ... is stated but never applied to the graph.")
- [S1691] types=['EXPERIMENTAL-RESULT'] scope=OBJECT — "Finding ON-3: executing Step 254's specified-but-never-run removal test over 11 candidate primitives shows all eleven are load-bearing for K (removal always destroys it, ranging from 6 to 18 of 26 surviving nodes), a negative minimality result meaning the candidate set contains no redundant member relative to K as currently defined; this says nothing about sufficiency and must not be read as validating the primitive set as correct. Entity is most load-bearing (Dimension->Entity, Value->Dimension chain into Proposition); Relation/RelationType are least load-bearing, mattering only from K upward." (anchor: "Every one of the eleven candidates is load-bearing for K: removing any of them destroys it. ... Entity is the most load-bearing (removal leaves 6 of 26 nodes) ... Relation/RelationType are the least (18/17 survive) -- they matter only from K upward.")

## Notes for P3
(none beyond what is noted above)
