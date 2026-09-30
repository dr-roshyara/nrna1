# step184-task-specific-evidence-use-and-knowledge-not-one-aggregate

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Historical reconstruction / Investigation / Prediction / Recommendation as distinct bounded contexts over the same evidence`, `HistoricalKnowledge/CurrentKnowledge/DerivedKnowledge/HypotheticalKnowledge/NormativeKnowledge as distinct kinds` · **Aliases:** `evidence used differently by task type; knowledge is overloaded`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0033`, scope `THEORY-LEVEL`: Step 184 distinguishes four bounded-context-specific uses of the same evidence E: historical reconstruction (E->EstablishedHistoricalClaim only when justified), investigation (E->Hypotheses), prediction (E->P(FutureEvent)), recommendation (E->SuggestedAction) -- 'these are different bounded contexts. This is pure DDD thinking.' Argues 'Knowledge' as a single aggregate is itself an overloaded word requiring distinct kinds: HistoricalKnowledge ('Architecture Board approved migration'), CurrentKnowledge ('migration is currently approved'), DerivedKnowledge/inference ('migration probably happened'), HypotheticalKnowledge, NormativeKnowledge/recommendation ('migration should happen') -- these must not collapse into one generic Knowledge object, judged 'probably more important than adding another technical component', requiring a richer ubiquitous language around Observation/Evidence/Claim/Determination/Decision/Rule/Policy/Hypothesis/Recommendation/Unknown.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1378 §"Historical reconstruction: E→EstablishedHistoricalClaim only when justified. Investigation: E→Hypotheses. Prediction: E→P(FutureEvent). Recommendation: E→SuggestedAction. These are different bounded contexts. ... HistoricalKnowledge CurrentKnowledge DerivedKnowledge HypotheticalKnowledge NormativeKnowledge. ... These must not collapse into one generic Knowledge object."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1378. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1378 |
| dependencies | PRESENT | S1378 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1378 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1378] types=['DISTINCTION', 'EXTENSION'] scope=THEORY-LEVEL — "Distinguishes four bounded-context-specific uses of the same evidence E -- historical reconstruction (only established claims), investigation (hypotheses), prediction (probabilistic future events), recommendation (suggested actions) -- 'this is pure DDD thinking.' Splits 'Knowledge' into five distinct kinds (HistoricalKnowledge, CurrentKnowledge, DerivedKnowledge, HypotheticalKnowledge, NormativeKnowledge, illustrated with concrete example sentences for each) that must not collapse into one generic Knowledge object, judged 'probably more important than adding another technical component' and requiring a richer ubiquitous language." (anchor: "Historical reconstruction: E→EstablishedHistoricalClaim only when justified. Investigation: E→Hypotheses. Prediction: E→P(FutureEvent). Recommendation: E→SuggestedAction. These are different bounded contexts. ... HistoricalKnowledge CurrentKnowledge DerivedKnowledge HypotheticalKnowledge NormativeKnowledge. ... These must not collapse into one generic Knowledge object.")

## Notes for P3
(none beyond what is noted above)
