# merge-algebra-question

**Scope(s):** `OBJECT` · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `idempotent/commutative/associative`, `merge: K x K -> K` · **Aliases:** `EXP-10, EXP-10b`
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0041, scope OBJECT): Whether KnowledgeOS state-merge forms an algebra (idempotent/commutative/associative) depends on which conflict-resolution rule is chosen; exhaustive search executed for two candidate rules (conflict-marking, latest-wins).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1678] §"the exhaustive result -- not an assertion -- is what stands. Whichever way it comes out, the finding is the same one: 'merge converges' (Step 025l) is a property OF A RULE, and the corpus states the claim without naming the rule."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S1708`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1678 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S1678 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1678, S1684, S1708 |
| assumptions | PRESENT | S1678 |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1678, S1684, S1708 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
EXP-10/10b implement two candidate merge conflict-resolution rules (mark-conflicted-on-value-disagreement; latest-timestamp-wins-with-tie-as-conflict) and run an exhaustive search over a small state space for associativity counterexamples; regardless of outcome, the corpus's claim that 'merge converges' (Step 025l) has no content because it never names which conflict-resolution operator is meant [S1678].

## Assumption register
| Statement | Stated | Source ID | Anchor |
|---|---|---|---|
| a convergence theorem must name its conflict-resolution operator to have content | EXPLICIT | S1678 | "A convergence theorem that does not name its conflict-resolution operator has no content." |

## All rows (source_id order)
- `[S1678]` types=[EXPERIMENTAL-RESULT, ARGUMENT] scope=OBJECT — "EXP-10/10b implement two candidate merge conflict-resolution rules (mark-conflicted-on-value-disagreement; latest-timestamp-wins-with-tie-as-conflict) and run an exhaustive search over a small state space for associativity counterexamples; regardless of outcome, the corpus's claim that 'merge converges' (Step 025l) has no content because it never names which conflict-resolution operator is meant." (anchor: "the exhaustive result -- not an assertion -- is what stands. Whichever way it comes out, the finding is the same one: 'merge converges' (Step 025l) is a property OF A RULE, and the corpus states th...")
- `[S1684]` types=[EXPERIMENTAL-RESULT, LIMITATION] scope=OBJECT — "Executing kaudit.py shows two assertions of the identical proposition from two different sources get distinct ids (because Pi/provenance is inside id), so merge-as-union cannot deduplicate: corroboration becomes indistinguishable from duplication, K grows unboundedly under repeated observation of the same fact, merge's idempotence holds only for byte-identical assertions, and cross-state relations between semantic twins are never created by merge, silently dropping information." (anchor: "Because Pi is inside id, the same fact from two sources is two assertions. ... K grows without bound under re-observation ... R is not maintained by merge.")
- `[S1708]` types=[EXPERIMENTAL-RESULT, CORRECTION] scope=OBJECT — "TEST 5 confirms Step 265 s265.11's merge test passes (assertion-level provenance survives a merge, distinguishable via Pi.source), but shows the merge event itself (when it happened, who authorized it) has no record inside K at all; cross-references EXP-10b's finding that different merge orders can produce different K under a latest-wins rule, meaning the unrecorded merge order is semantically load-bearing information that is silently lost." (anchor: "Assertion provenance survives merge (Step 265 s265.11 'merge test' PASSES). MERGE provenance does not exist: the merge event itself has no record inside K. Two different merge orders producing the ...")

## Notes for P3
- No additional observations beyond what is captured above; nothing about this label's own rows struck this reviewer as unusual relative to its evidentiary base.
