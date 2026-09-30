# causal-claim-immutable-versioning

**Scope(s):** OBJECT · **Row count:** 4 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** CausalClaim=(Proposition,Evidence,Model,Assumptions,Population,Method,Assessment,Time), Claim_v1 -> Claim_v2=Refuted · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope OBJECT): Step 194's requirement that causal claims be immutable, versioned assertions carrying full model/assumption/population/method provenance, so that model changes and refutations are traceable rather than silently overwritten; grounds a reproducibility invariant.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1397 §"Claim_1 \xrightarrow{superseded/refuted} Claim_2."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1397 §"CausalClaim = (Proposition,Evidence,Model,Assumptions,Population,Method,Assessment,Time). This is essentially the statistical equivalent of software provenance."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1397. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by/superseded_by empty, contested flag false). The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the ledger, not a confirmed retirement and not a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1397 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1397 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1397 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1397 |
| examples | PRESENT | S1397 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The evidence base attributes the following rationale to this object (as recorded, cited to its source):

- **[ARGUMENT]** [S1397]: When AI models evolve (Model_1 generating Claim_1, later Model_2 generating Claim_2), the architecture must preserve Model_1 != Model_2 as distinct provenance, or the reason for a changed epistemic assessment becomes unrecoverable.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1397] types=[PRINCIPLE, EXAMPLE] scope=OBJECT — "Causal claims should be immutable, versioned assertions (Claim_v1, Claim_v2=Refuted) rather than mutable fields silently overwritten (e.g. cause=deployment updated in place); a later refutation creates a new claim version linked by supersession/refutation to the original." (anchor: "Claim_1 \xrightarrow{superseded/refuted} Claim_2.")
- [S1397] types=[ARGUMENT] scope=OBJECT — "When AI models evolve (Model_1 generating Claim_1, later Model_2 generating Claim_2), the architecture must preserve Model_1 != Model_2 as distinct provenance, or the reason for a changed epistemic assessment becomes unrecoverable." (anchor: "Model_1\neq Model_2. Otherwise we cannot understand why the epistemic assessment changed.")
- [S1397] types=[DEFINITION, FORMALIZATION] scope=OBJECT — "Defines a CausalClaim schema with eight fields (Proposition, Evidence, Model, Assumptions, Population, Method, Assessment, Time), framed as the statistical equivalent of software provenance." (anchor: "CausalClaim = (Proposition,Evidence,Model,Assumptions,Population,Method,Assessment,Time). This is essentially the statistical equivalent of software provenance.")
- [S1397] types=[PRINCIPLE, INVARIANT] scope=THEORY-LEVEL — "A serious causal assertion should answer whether another qualified analyst could reproduce the assessment, requiring Data+Method+Model+Assumptions+Version to be retained; reproducibility is proposed as a knowledge invariant for quantitative claims." (anchor: "Reproducibility is a knowledge invariant where quantitative claims matter.")

## Notes for P3
(Own observation) Nothing unusual noticed while drafting this file beyond what is already recorded above.
