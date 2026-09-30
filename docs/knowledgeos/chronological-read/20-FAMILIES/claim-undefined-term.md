# claim-undefined-term

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Claim`
**Aliases:** "CS-1"
**Candidate group membership (NOT an identity claim):**
- G1082: co-occurs with `verdict-undefined-term` — working_label token overlap Jaccard=0.50 (shared tokens: ['term', 'undefined'])

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0041, scope OBJECT: "'Claim' is used throughout the corpus (claim registry, authority claims, claim provenance) and even has a dedicated section (Step 253 §253.14) discussing it, yet is never given a type, identity criterion, or stated relation to Assertion; recommended repair is retiring the word as a synonym for Assertion rather than defining it as a new concept."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1701 §"it is an informal synonym, and the corpus's own §253.14 does not remove it. The natural repair -- Claim = Assertion -- is available and costs nothing."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1718. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S1718), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1701, S1718 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1701 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

Finding CS-1: Step 253 §253.14 has a section titled 'Claim' but, on inspection, discusses claims without giving a type, identity criterion, or relation to Assertion; recommends retiring 'Claim' as a synonym for the existing Assertion term (ES-005.4-style: extend, don't create a second) rather than formally defining it as distinct [S1701]. Additionally, UL-4: Claim has no definition anywhere in the corpus and is a synonym of Assertion never stated as such; the corpus should retire the word rather than define it (per ES-005.4's extend-don't-duplicate principle) [S1718].

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1701] types=[ARGUMENT, LIMITATION] scope=OBJECT — "Finding CS-1: Step 253 §253.14 has a section titled 'Claim' but, on inspection, discusses claims without giving a type, identity criterion, or relation to Assertion; recommends retiring 'Claim' as a synonym for the existing Assertion term (ES-005.4-style: extend, don't create a second) rather than formally defining it as distinct." (anchor: "it is an informal synonym, and the corpus's own §253.14 does not remove it. The natural repair -- Claim = Assertion -- is available and costs nothing.")
- [S1718] types=[DEFINITION, ARGUMENT] scope=THEORY-LEVEL — completeness N/A — "UL-4: Claim has no definition anywhere in the corpus and is a synonym of Assertion never stated as such; the corpus should retire the word rather than define it (per ES-005.4's extend-don't-duplicate principle)." (anchor: "UL-4 (`DERIVED`, MEDIUM). `Claim` and `Assertion`. `Claim` has no definition")

## Notes for P3

- This is my own observation: nothing unusual noticed beyond what is already recorded above; evidence base is internally consistent for what it covers.
