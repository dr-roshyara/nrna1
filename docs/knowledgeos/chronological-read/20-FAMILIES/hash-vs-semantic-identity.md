# hash-vs-semantic-identity

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `hash identity != semantic identity`; `integrityHash`
**Aliases:** "IE-6"; "Step 260 §260.17"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0041, scope OBJECT: "Step 260 §260.17's correctly-established distinction that a collision-resistant hash is a tamper-detection device, not an identity criterion (two representations may hash differently while denoting the same class); explains empirically why GovernanceLineageNode's integrityHash (Step 267 §267.10) is marked IMPLEMENTED but theory slot unresolved -- hashing and identity are different jobs and the corpus is right not to conflate them."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1710 §"Hash identity != semantic identity ... Two representations may hash differently while denoting the same class; a collision-resistant hash is not a proof of equality. ... an integrity hash is a TAMPER-DETECTION device, not an identity criterion. Those are different jobs, and the corpus is right not to conflate them."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1710. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a heuristic based on how recently (by source_id) this label was last used (last_seen: S1710), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1710 |
| dependencies | PRESENT | S1710 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1710 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE). `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1710] types=[VALIDATION, DISTINCTION] scope=OBJECT — "Finding IE-6 (positive): endorses Step 260 §260.17's correct establishment that hash identity and semantic identity are different concepts (two representations may hash differently while denoting the same equivalence class, so a collision-resistant hash is not proof of equality), and shows this explains empirically why Step 267 §267.10 marks GovernanceLineageNode's integrityHash field as IMPLEMENTED but theory slot unresolved -- an integrity hash is a tamper-detection device, not an identity criterion, and the corpus correctly declines to conflate the two." (anchor: "Hash identity != semantic identity ... Two representations may hash differently while denoting the same class; a collision-resistant hash is not a proof of equality. ... an integrity hash is a TAMPER-DETECTION device, not an identity criterion. Those are different jobs, and the corpus is right not to conflate them.")

## Notes for P3

- No internal tension, unknown-candidate marker, or contested-lifecycle discrepancy was observed in this label's own rows; evidentiary base is straightforward for its row count.
