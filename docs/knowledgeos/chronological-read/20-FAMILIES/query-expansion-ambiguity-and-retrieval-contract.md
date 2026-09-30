# query-expansion-ambiguity-and-retrieval-contract

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `RC=<Question,Scope,Sources,TimeWindow,...>` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0067`, scope `THEORY-LEVEL`: Query-expansion semantic drift risk, ambiguous-query preservation of ambiguity, and the formal Retrieval Contract tuple.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2783 §"Q' != Q necessarily [after query expansion]. ... Interpret(Q) = {I_1,...,I_n} ... Ambiguous != Resolved. ... RC = <Question,Scope,Sources,TimeWindow,...>"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2783 §"Q' != Q necessarily [after query expansion]. ... Interpret(Q) = {I_1,...,I_n} ... Ambiguous != Resolved. ... RC = <Question,Scope,Sources,TimeWindow,...>"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2783. Candidate lifecycle: **ACTIVE**. Evidence: no retraction/supersession/contradiction evidence recorded; the classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2783 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2783] types=['CONSTRAINT', 'FORMALIZATION'] scope=THEORY-LEVEL — "20.37-20.40: query expansion Q->Q' (synonyms, related concepts, entity resolution, inferred terms) need not preserve Q's intended semantics (Q'≠Q necessarily), risking retrieval drift; some query interpretation is itself inferential (e.g. 'why did sales increase?' admits descriptive/causal/attribution/comparative readings) so Interpret(Q) may need to return a set {I1..In} rather than silently picking one as though true, following Unknown≠False and Ambiguous≠Resolved; formalizes a Retrieval Contract RC=<Question,Scope,Sources,TimeWindow,RetrievalMethods,RankingMethod,Filters,MinimumRecall,MinimumPrecision,Freshness,ProvenanceRequirements> defining what constitutes adequate retrieval, analogous to earlier statistical/causal contracts." (anchor: "Q' != Q necessarily [after query expansion]. ... Interpret(Q) = {I_1,...,I_n} ... Ambiguous != Resolved. ... RC = <Question,Scope,Sources,TimeWindow,...>")

## Notes for P3
- Thin evidence base (n=1 row(s)) — treat conclusions here as provisional.
