# leonardo-context-completeness-lens

**Scope(s):** OBJECT · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `INCOMPLETE_CONTEXT` · **Aliases:** `Leonardo lens`
**Candidate group membership (NOT an identity claim):**
- **G0322**: [`context-tuple` · `leonardo-context-completeness-lens`] — explicit agent-stated uncertainty: 'context-tuple' POSSIBLY relates to 'leonardo-context-completeness-lens' (batch B0032). Note: A structure described (l.208 of an out-of-batch source) as carrying 'the delimiting conditions... the frame beyond which the claim is incomplete'; offered as a candidate answer to whether v1.1 models context sufficiency, not merely context presence.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0020`, scope `OBJECT`: A named lens asking 'is the relevant context sufficiently represented?' (context completeness, as distinct from Zero's 'do we have a basis?'), used alongside Zero and Statistical Learning; the curse of dimensionality is read as a Leonardo failure (Context completeness ≠ information volume).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0833 §"Zero tells us when learning should not start. ... Leonardo tells us whether the learning problem has been sufficiently contextualized. ... Statistical Learning tells us how to learn once those conditions are satisfied."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0833 §"Zero tells us when learning should not start. ... Leonardo tells us whether the learning problem has been sufficiently contextualized. ... Statistical Learning tells us how to learn once those conditions are satisfied."]
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0833 §"Zero tells us when learning should not start. ... Leonardo tells us whether the learning problem has been sufficiently contextualized. ... Statistical Learning tells us how to learn once those conditions are satisfied."]

## Lifecycle
last_seen: S1308. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S0833, S1308 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0833 |
| dependencies | PRESENT | S0833, S0833, S1308 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1308 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The book's curse-of-dimensionality (nearest-neighbor intuition breaks down as observations cease to be meaningfully close in high dimensions) is read as a 'Leonardo failure': more dimensions can destroy contextual locality, so Context completeness ≠ information volume, and MoreFeatures ⇏ BetterPrediction — 'an important correction to naive RAG architectures'. Reformulates evidence value as EvidenceValue = ExpectedReductionInDecisionLoss, not EvidenceValue = NumberOfDocuments. [S0833] Three genuinely different admission-condition questions are converging across the corpus: standing (was this input allowed to participate, S1-F032), prerequisite presence (what happens when a prerequisite is missing, the Zero lens, answered by ⟨Z-1⟩), and context sufficiency (have we understood the whole relevant context, the Leonardo lens). The reviewer confirms these are genuinely distinct questions and the convergence claim is sound. [S1308]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0833] types=['CONCEPT', 'GOVERNANCE'] scope=THEORY-LEVEL — "Composes three lenses into a pipeline: Zero ('do we have a basis?' -> NO_BASIS if not), Leonardo ('is the relevant context sufficiently represented?' -> INCOMPLETE_CONTEXT if not), Statistical Learning ('what model learns best given the context?'), then Assessment ('does it generalize?') -> Knowledge. A model producing a result does not make that result legitimate: statistical machinery can still run even when X/Y/an important variable/context/measurement validity/population representativeness is missing — Zero says this is not constitutively sufficient for the question, confirming the NO_BASIS state." (anchor: "Zero tells us when learning should not start. ... Leonardo tells us whether the learning problem has been sufficiently contextualized. ... Statistical Learning tells us how to learn once those conditions are satisfied.")
- [S0833] types=['ARGUMENT', 'CORRECTION'] scope=OBJECT — "The book's curse-of-dimensionality (nearest-neighbor intuition breaks down as observations cease to be meaningfully close in high dimensions) is read as a 'Leonardo failure': more dimensions can destroy contextual locality, so Context completeness ≠ information volume, and MoreFeatures ⇏ BetterPrediction — 'an important correction to naive RAG architectures'. Reformulates evidence value as EvidenceValue = ExpectedReductionInDecisionLoss, not EvidenceValue = NumberOfDocuments." (anchor: "Context\ completeness \neq information\ volume")
- [S1308] types=['ANALYSIS', 'DISTINCTION'] scope=CROSS-OBJECT — "Three genuinely different admission-condition questions are converging across the corpus: standing (was this input allowed to participate, S1-F032), prerequisite presence (what happens when a prerequisite is missing, the Zero lens, answered by ⟨Z-1⟩), and context sufficiency (have we understood the whole relevant context, the Leonardo lens). The reviewer confirms these are genuinely distinct questions and the convergence claim is sound." (anchor: "Three converging admission conditions. *Standing* ... *prerequisite presence* ... *context sufficiency*")

## Notes for P3
- This label participates in 1 candidate group(s) (G0322) — per R5/R12 this is not an identity claim; P3 should review whether any group member denotes the same underlying object as this label.
- Thin evidence base (n=3 row(s)) — treat conclusions here as provisional.
