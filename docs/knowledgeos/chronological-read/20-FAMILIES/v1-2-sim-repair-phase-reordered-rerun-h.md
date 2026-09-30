# v1-2-sim-repair-phase-reordered-rerun-h

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `KR-SIM-2026-09-02-F`, `R_a..R_d`, "stratify"
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0058, scope OBJECT: "Artifact H: enumerates and tests (without choosing) candidate repairs for PB-2 (four contradiction-codomain candidates; only R_b -- delegate content to consistency -- is total, three-valued and preserves absence!=negation, but it creates a dependency on the still-undefined consistency class), PB-3 (only a skeleton-derived three-structural-source reason taxonomy could be provably exhaustive; enumeration cannot be), and PB-5 (stratifying Sat_op into levels is the only candidate that terminates, keeps expressiveness, and makes well-foundedness failure visible in the value). Also upgrades the Zero_strict=>Zero_reasoned=>Zero_weak chain from observed to machine-checked derived (1,620,000 assignments, 0 counterexamples), and shows the earlier -E evaluator run understated blocked-class contagion because it ran before the PB-2 repair (arity-2 contagion corrected from 0.464 to 0.643)."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2409 §"R_a four-valued codomain ... R_b delegate to Sat_consistency; content returns U on contradiction ... R_c ... maps a contradictory state to top: the system would report a requirement satisfied ... R_b is the only candidate satisfying all three criteria"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2409. Candidate lifecycle: ACTIVE. Evidence: no retraction, supersession, or self-contradiction recorded — heuristic based on recency of last use, not a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2409, S2409 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2409 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Both rows are themselves rationale-bearing (ALTERNATIVE/EXPERIMENTAL-RESULT). This artifact ("H", version_ref v1.2) exists to enumerate and test — without yet choosing — candidate repairs for two open problems in the theory-v1.2 simulation: PB-2 (what codomain a contradiction should map to) and PB-3 (whether the reason vocabulary can be made exhaustive) [S2409]. For PB-2, four candidate repairs (R_a four-valued codomain, R_b delegate-to-consistency, R_c, and an implicit fourth) are tested against three criteria — total, three-valued, preserves `absence≠negation`; only R_b meets all three, at the cost of a new, still-undefined dependency on a "consistency" class, while R_c is explicitly flagged as a "trap" because it maps a contradictory state to top and would make the system report a requirement as satisfied [S2409]. For PB-3, the row argues only a Sat-skeleton-derived, three-structural-source taxonomy could in principle be proven exhaustive; a simple one-word patch and a "global free-for-all" vocabulary are both rejected as unprovable or vacuous, leaving reason-vocabulary exhaustiveness explicitly OPEN [S2409].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S2409] types=[ALTERNATIVE, EXPERIMENTAL-RESULT] scope=OBJECT — "Tests four candidate PB-2 (contradiction) repairs against three criteria (total, three-valued, preserves absence≠negation); only R_b (delegate to consistency) meets all three, but this creates a new dependency on the still-undefined consistency class — R_c is flagged as a 'trap' that silently loses the contradiction." (anchor: "R_a four-valued codomain ... R_b delegate to Sat_consistency; content returns U on contradiction ... R_c ... maps a contradictory state to top: the system would report a requirement satisfied ... R_b is the only candidate satisfying all three criteria")
- [S2409] types=[ALTERNATIVE] scope=OBJECT — "For PB-3 (reason vocabulary), only a skeleton-derived three-structural-source taxonomy could in principle be exhaustive; the simple one-word patch and a global free-for-all vocabulary are both rejected as unprovable or vacuous." (anchor: "R_c derive the vocabulary from the Sat skeleton: U has exactly three structural sources ... the only candidate that could be exhaustive ... Reason-vocabulary exhaustiveness remains OPEN.")

Both rows come from the same file: `docs/knowledgeos/research/theory-v1.2-simulation/H-repair-phase-and-reordered-rerun.md`.

## Notes for P3
Both rows are from a single source file (S2409) and both are explicitly framed as testing/enumerating candidates rather than deciding — "enumerates and tests (without choosing)" per the batch note. This label is an artifact-of-analysis (a repair-options document, PB-2/PB-3/PB-5), not itself a settled theory object; PB-2's chosen-but-costly repair (R_b, dependent on an undefined "consistency" class) and PB-3's still-OPEN exhaustiveness question look like they should route to whatever labels track "consistency class" and "reason vocabulary" as first-class objects, if such labels exist elsewhere in the ledger — not decided here.
