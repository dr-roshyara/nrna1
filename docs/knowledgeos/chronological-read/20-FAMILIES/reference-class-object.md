# reference-class-object

**Scope(s):** OBJECT · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `PriorAssessment`, `ReferenceClass` · **Aliases:** `outside view / base rate`
**Candidate group membership (NOT an identity claim):**
- **G1026** [`reference-class-object` · `semantic-reference-object`] — working_label token overlap Jaccard=0.50 (shared tokens: ['object', 'reference'])

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0012`, scope `OBJECT`: Superforecaster-derived capability: establish a reference class and base rate (outside view) before updating with case-specific evidence (inside view); PriorAssessment object (proposition, reference class, historical evidence, assumptions, prior distribution, justification, source, date) makes explicit that a prior is not arbitrary opinion but can be checked/crowdsourced/tested.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0453 §"Superforecasters don't simply look at the immediate case. They first ask: What is the base rate among comparable cases? Then they update using the specifics of the current situation. ... OUTSIDE VIEW -> Reference Class -> Base Rate -> INSIDE VIEW -> Case-specific evidence -> Posterior."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0453 §"Superforecasters don't simply look at the immediate case. They first ask: What is the base rate among comparable cases? Then they update using the specifics of the current situation. ... OUTSIDE VIEW -> Reference Class -> Base Rate -> INSIDE VIEW -> Case-specific evidence -> Posterior."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0456. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0453, S0453, S0456 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0453 |
| dependencies | PRESENT | S0453, S0453, S0456 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S0453, S0456 |
| warnings | PRESENT | S0456 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0453] types=['FORMALIZATION', 'EXAMPLE'] scope=OBJECT — "ReferenceClass capability formalized (e.g. 'will project X fail?' answered via 500 similar-technology/size/complexity/delivery-model projects' base failure rate updated by current-project deviations), described as much stronger than an LLM saying 'based on my experience, this project looks risky.'" (anchor: "Superforecasters don't simply look at the immediate case. They first ask: What is the base rate among comparable cases? Then they update using the specifics of the current situation. ... OUTSIDE VIEW -> Reference Class -> Base Rate -> INSIDE VIEW -> Case-speci…")
- [S0453] types=['INVARIANT', 'FORMALIZATION'] scope=OBJECT — "PriorAssessment object formalized (proposition/reference class/historical evidence/assumptions/prior distribution/justification/source/date) with the invariant that priors are not arbitrary opinion but checkable/crowdsourceable/testable for robustness; identical evidence can produce very different posteriors depending on the prior (medical-testing examples)." (anchor: "priors cannot simply be pulled from thin air; reasonable priors can be checked, crowdsourced and tested for robustness. ... Prior ≠ arbitrary opinion.")
- [S0456] types=['FORMALIZATION', 'EXAMPLE', 'WARNING'] scope=OBJECT — "ReferenceComparison object (Subject -> ComparisonClass -> Internal distribution -> Relative position -> Assessment) allows internal-only assessment (e.g. a service's 3% error rate vs peers' 0.8% median/2.1% 95th-percentile), but the analysis warns internal comparison is a 'two-edged sword' and must not replace an external standard (example: peers averaging 3 critical vulnerabilities does not make 3 acceptable against a policy requiring <1)." (anchor: "eliminating an external standard can remove relevance, making intercomparison a two-edged sword. ... Internal comparison must not replace External reality. ... The internal comparison does not make 3 acceptable.")

## Notes for P3
- Thin evidence base (n=3 rows) — treat conclusions here as provisional.
