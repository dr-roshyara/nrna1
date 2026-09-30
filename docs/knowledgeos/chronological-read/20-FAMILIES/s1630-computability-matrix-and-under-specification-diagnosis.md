# s1630-computability-matrix-and-under-specification-diagnosis

**Scope(s):** THEORY-LEVEL · **Row count:** 5 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `14/16 computable`; `under-specified vs incomputable`
**Aliases:** "COMPUTABILITY-MATRIX"
**Candidate group membership (NOT an identity claim):**
- G0400: co-occurs with `s1612-k-minimality-proof-and-valid-k-computability` — explicit agent-stated uncertainty: 's1630-computability-matrix-and-under-specification-diagnosis' POSSIBLY relates to 's1612-k-minimality-proof-and-valid-k-computability' (batch B0039). Note: A 16-row executed computability matrix (14/16 have running decision procedures); introduces the under-specified-vs-incomputable distinction for Assessment (well-typed but Policy's semantics undefined); documents a concrete O(n^2)->O(n+sum bi^2) contradiction-detection speedup enabled directly by CB-2's resolution.
- G1650: co-occurs with `s1626-four-valid-predicates-and-twelve-operation-algebra` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)

- PROPOSAL, batch B0039, scope THEORY-LEVEL: "A 16-row executed computability matrix (14/16 have running decision procedures); introduces the under-specified-vs-incomputable distinction for Assessment (well-typed but Policy's semantics undefined); documents a concrete O(n^2)->O(n+sum bi^2) contradiction-detection speedup enabled directly by CB-2's resolution." (relation_to_existing: POSSIBLY:s1612-k-minimality-proof-and-valid-k-computability)

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1630 §"14 of 16 computable, 2 blocked. [full 16-row matrix: membership O(1), structural/semantic equality O(n+m), StructuralValid O(n+m) with acyclicity added this phase, SemanticallyValid O(n) not yet executed, EpistemicallyValid partly, GovernanceValid requires History, contradiction O(n+sum bi^2), confl"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1630 §"Uncertainty: NOT DEFINED -- no representation, no probability space in 1468 files (CB-3). Assurance: CONTRADICTORY -- six incompatible types, one self-referential (CB-1). GovernanceValid(K) from K alone: not a property of K -- requires History. Not a defect; a boundary."]

## Lifecycle

last_seen: S1630. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S1630), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1630 (×2) |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1630 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1630 (×2) |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

Gives a precise diagnostic distinction for why Assessment cannot yet be executed: it is well-typed and would compute in O(|e|), but its Policy parameter's semantics are entirely unspecified anywhere in the corpus (no rule for how many independent sources yield 'Strong' support, whether source quality is weighted, how contradicting evidence offsets supporting evidence) -- the function itself is defined, only its parameter's meaning is missing, correctly classified as UNDER-SPECIFIED rather than incomputable [S1630]. Additionally, Diagnoses status derivation as conditionally blocked: the directional component (dir) already computes, but the strength component (str) needs an evidence-to-ordinal mapping rule that must itself preserve order only (never arithmetic), consistent with the no-interval-scale constraint established in S1625's Sigma re-audit [S1630].

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1630] types=[EXPERIMENTAL-RESULT] scope=OBJECT — also labeled `s1612-k-minimality-proof-and-valid-k-computability`, `s1626-four-valid-predicates-and-twelve-operation-algebra`, completeness N/A — "Executes a complete 16-row computability matrix across the theory's core objects/predicates/operations, finding 14 of 16 have executing decision procedures with explicit complexity bounds (mostly O(1) to O(n+m) where n=|A|, m=|R|), and identifying exactly 2 blocked entries (assessment, status derivation's strength component)." (anchor: "14 of 16 computable, 2 blocked. [full 16-row matrix: membership O(1), structural/semantic equality O(n+m), StructuralValid O(n+m) with acyclicity added this phase, SemanticallyValid O(n) not yet executed, EpistemicallyValid partly, GovernanceValid requires History, contradiction O(n+sum bi^2), confl")
- [S1630] types=[ANALYSIS, DISTINCTION] scope=OBJECT — completeness N/A — "Gives a precise diagnostic distinction for why Assessment cannot yet be executed: it is well-typed and would compute in O(|e|), but its Policy parameter's semantics are entirely unspecified anywhere in the corpus (no rule for how many independent sources yield 'Strong' support, whether source quality is weighted, how contradicting evidence offsets supporting evidence) -- the function itself is defined, only its parameter's meaning is missing, correctly classified as UNDER-SPECIFIED rather than incomputable." (anchor: "assessment -- BLOCKED, and not for want of an algorithm. Assessment: P x Evidence x Context x Policy -> Sigma is well-typed and would compute in O(|e|). What is missing is the semantics of Policy. No corpus passage specifies how a policy maps an evidence set to a support level ... The function is de")
- [S1630] types=[ANALYSIS] scope=OBJECT — also labeled `s1625-sigma-signed-ordinal-refutation-and-evidence-refinement`, completeness N/A — "Diagnoses status derivation as conditionally blocked: the directional component (dir) already computes, but the strength component (str) needs an evidence-to-ordinal mapping rule that must itself preserve order only (never arithmetic), consistent with the no-interval-scale constraint established in S1625's Sigma re-audit." (anchor: "status derivation -- CONDITIONALLY BLOCKED. dir computes now. str requires a rule mapping evidence to an ordinal level, and that rule must be order-preserving only -- no arithmetic is admissible, because no interval scale is established.")
- [S1630] types=[EXPERIMENTAL-RESULT, VALIDATION] scope=OBJECT — also labeled `s1622-p-reconstruction-entity-dimension-value`, `s1624-relation-algebra-decomposition`, completeness N/A — "Documents a concrete computational payoff directly attributable to CB-2's resolution (S1622): contradiction detection improves from naive O(n^2) pairwise scanning to O(n + sum bi^2) via bucketing by the (Entity,Dimension) key, a bucketing key that only became available once Proposition's internal structure P=(E,D,V) was known -- an unavailable optimization while P remained opaque." (anchor: "The naive pairwise scan is O(n^2). Bucketing by (Entity, Dimension) reduces it to O(n + sum bi^2) where bi is bucket size -- and P=(E,D,V) makes this bucketing possible, since (E,D) is a natural key. This is a concrete engineering payoff from closing CB-2 that was unavailable while P was opaque.")
- [S1630] types=[LIMITATION, GOVERNANCE] scope=THEORY-LEVEL — label_confidence UNCERTAIN, also labeled `s1617-closure-report-ekp-discovery-and-assurance-blocker`, `s1626-four-valid-predicates-and-twelve-operation-algebra`, completeness N/A — "Lists 3 items that are not computable for principled, already-diagnosed reasons rather than oversight: Uncertainty (CB-3, no representation or probability space anywhere in the corpus), Assurance (CB-1, 6 incompatible types), and GovernanceValid(K) evaluated from K alone (explicitly not a defect but an architectural boundary, since it legitimately requires History)." (anchor: "Uncertainty: NOT DEFINED -- no representation, no probability space in 1468 files (CB-3). Assurance: CONTRADICTORY -- six incompatible types, one self-referential (CB-1). GovernanceValid(K) from K alone: not a property of K -- requires History. Not a defect; a boundary.")

## Notes for P3

- This is my own observation: nothing unusual noticed beyond what is already recorded above; evidence base is internally consistent for what it covers.
