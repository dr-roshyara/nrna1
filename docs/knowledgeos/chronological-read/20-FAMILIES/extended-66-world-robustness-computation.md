# extended-66-world-robustness-computation

**Scope(s):** OBJECT · **Row count:** 2 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `32 = 2^5 C-row combinations`, `66 worlds` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope OBJECT): A widened 66-world robustness computation (full product over 5 perturbable C-rows x 2 edge variants) extending a prior 14-world design.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2801] §"worlds: 66 (review lane's design: 14) closure size ranges over 15 .. 23 of 29 review lane published: 15 .. 22 -> the published range is CONFIRMED and WIDENS to 15..23. ... present in EVERY world (8): Assertion, Identity, InvariantReg, K, Proposition, Provenance, Relation, RelationType ... review lan"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S2802] §"Extension of the review lane's robustness design, using the SOURCE program's graph. Their design: 2 InvariantReg-edge variants x (K_4 + 6 one-at-a-time mappings) = 14 worlds. This: 2 edge variants x the FULL product over the 5 perturbable C-rows = 64 worlds, plus K_4 in both = 66. Everything else id"
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2802. Candidate lifecycle: ACTIVE.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This ACTIVE classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2801, S2802 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2801] types=[EXPERIMENTAL-RESULT, VALIDATION] scope=OBJECT — "Reports that across the wider 66-world enumeration, closure size ranges 15..23 of 29 constructs, confirming and widening the previously published 15..22 range (note: this differs from the separate verify_k9_closure.py's 32-world 16..23 mismatch finding, since this run additionally includes K4 in both edge variants, lowering the minimum to 15); the set of constructs present in every one of the 66 worlds is {Assertion, Identity, InvariantReg, K, Proposition, Provenance, Relation, RelationType} (8 constructs), of which 5 are blocked (Identity, InvariantReg, K, Proposition, Relation) -- identical to the previously reported triply-robust blocked set even under this much wider perturbation space; three constructs (Lineage, Measurement, Orphan) are present in no world at all." (anchor: "worlds: 66 (review lane's design: 14) closure size ranges over 15 .. 23 of 29 review lane published: 15 .. 22 -> the published range is CONFIRMED and WIDENS to 15..23. ... present in EVERY world (8): Assertion, Identity, InvariantReg, K, Proposition, Provenance, Relation, RelationType ... review lan")
- [S2802] types=[EXPERIMENT] scope=OBJECT — "Header and world-enumeration logic: widens a prior 14-world robustness design (crossing 2 InvariantReg edge-treatment variants with K4 plus 6 one-at-a-time C-row mappings) to 66 worlds by instead crossing the 2 edge variants with the FULL product over all 5 perturbable C-rows (2^5=32 combinations) plus K4 in both edge variants; computes the transitive closure in every world using the directly-imported original graph, then reports the size range across all worlds, the constructs present in every world (further split into blocked vs unblocked), constructs present in no world, and how many of the 66 worlds contain O_core." (anchor: "Extension of the review lane's robustness design, using the SOURCE program's graph. Their design: 2 InvariantReg-edge variants x (K_4 + 6 one-at-a-time mappings) = 14 worlds. This: 2 edge variants x the FULL product over the 5 perturbable C-rows = 64 worlds, plus K_4 in both = 66. Everything else id")

## Notes for P3
Very thin evidentiary base (1-2 rows) — classification here is provisional and should be revisited if more contributions surface.
