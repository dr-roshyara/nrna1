# decision-validity-vector-three-dimensional

**Scope(s):** THEORY-LEVEL · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `V_D=(E_D,G_D,P_D)`
**Aliases:** "Epistemic x Governance x Policy validity"
**Candidate group membership (NOT an identity claim):**
- G0969: links this to `two-dimensional-decision-validity` — working_label token overlap Jaccard=0.50 (shared tokens: ['decision', 'dimensional', 'validity'])

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 202's extension of the two-dimensional decision-validity vector (from Step 200) to three dimensions by adding Policy conformity, e.g. distinguishing a well-supported, authorized, but policy-violating decision."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1421 §"Authority\not\Rightarrow EpistemicValidity. And: Assessment\not\Rightarrow Authority. This gives us two independent dimensions."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1421 §"V_D=(E_D,G_D) ... | 1 | 1 | supported + authorized | ... | 0 | 0 | unsupported + unauthorized |"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1421. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a heuristic based on how recently (by source_id) this label was last used (last_seen: S1421), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1421 (×2) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1421 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1421 |
| examples | PRESENT | S1421 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE). `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1421] types=[INVARIANT, DISTINCTION] scope=OBJECT — "Authority does not imply epistemic validity (an authorized actor may still lack adequate evidence) and assessment does not imply authority -- confirming two independent dimensions underlying decision validity." (anchor: "Authority\not\Rightarrow EpistemicValidity. And: Assessment\not\Rightarrow Authority. This gives us two independent dimensions.")
- [S1421] types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "Formalizes an initial two-dimensional decision validity vector V_D=(EpistemicValidity,GovernanceValidity) with a worked four-row truth table, far more expressive than a single Decision.valid=true boolean." (anchor: "V_D=(E_D,G_D) ... | 1 | 1 | supported + authorized | ... | 0 | 0 | unsupported + unauthorized |")
- [S1421] types=[FORMALIZATION, EXTENSION] scope=OBJECT — "Extends decision validity to three dimensions V_D=(Epistemic,Governance,Policy), e.g. (1,1,0) meaning well-supported and authorized but policy-violating -- an extremely useful distinction not captured by the earlier two-dimensional model." (anchor: "V_D=(E_D,G_D,P_D) ... (1,1,0) meaning: well-supported and authorized, but violates policy.")

## Notes for P3

- No internal tension, unknown-candidate marker, or contested-lifecycle discrepancy was observed in this label's own rows; evidentiary base is straightforward for its row count.
