# assumption-ledger

**Scope(s):** OBJECT · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `AssumptionLedger` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G1390**: candidate group with `assumption-object` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus (mechanical signal only; relationship not yet decided, P3).



## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0012, scope OBJECT): Proposed construct tracking each Model's assumptions with evidence and status, chained from a Research Question through a Model to its assumptions.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0446] §"Freedman repeatedly attacks hidden assumptions. ... assumptions are frequently not stated or tested, and when assumptions fail, the mathematical guarantees of the method no longer apply."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0446] §"Freedman repeatedly attacks hidden assumptions. ... assumptions are frequently not stated or tested, and when assumptions fail, the mathematical guarantees of the method no longer apply."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0982. Candidate lifecycle: DORMANT. Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S0982 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S0446 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | PRESENT | S0446 |
| Semantics | PRESENT | S0982 |
| Examples | PRESENT | S0446 |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
States that because a change in an Assumption can propagate through Model→Prediction→Decision, assumptions require explicit dependency lineage tracking [S0982].

## Assumption register
| Statement | Stated | Source id | Anchor |
|---|---|---|---|
| Assumptions are frequently not stated or tested, and when assumptions fail, the mathematical guarantees of the method no longer apply. | EXPLICIT | S0446 | section 5, attributed to the book |

## All rows (source_id order)
- [S0446] types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "Proposes Assumption as a first-class object under Model (statement/type/justification/evidence/testability/status/consequence_if_false/scope) with states SUPPORTED/PARTIALLY_SUPPORTED/UNSUPPORTED/UNTESTED/UNTESTABLE/CONTRADICTED, illustrated with the example assumption 'The observations are independent' (status: unverified, testability: limited, consequence_if_false: confidence_intervals_invalid, inference_weakened); introduces an AssumptionLedger chaining Research Question -> Model -> per-assumption evidence/status." (anchor: "Freedman repeatedly attacks hidden assumptions. ... assumptions are frequently not stated or tested, and when assumptions fail, the mathematical guarantees of the method no longer apply.")
- [S0982] types=[PRINCIPLE, ARGUMENT] scope=OBJECT — "States that because a change in an Assumption can propagate through Model→Prediction→Decision, assumptions require explicit dependency lineage tracking." (anchor: "82.54 — This is a major KnowledgeOS property ... A change in Assumption can propagate through Model→Prediction→Decision. Therefore assumptions need dependency lineage.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
