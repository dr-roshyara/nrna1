# five-architectural-maturity-states

**Scope(s):** `OBJECT` · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `ASSURED`, `DESIGNED`, `ENFORCED`, `IMPLEMENTED`, `OBSERVED` · **Aliases:** `Observed->Designed->Implemented->Enforced->Assured maturity path`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope OBJECT): Step 159's five-state architectural maturity model (OBSERVED/DESIGNED/IMPLEMENTED/ENFORCED/ASSURED), refining the three-state CURRENT/TARGET/DELTA baseline; each transition is non-automatic (a design can be implemented without being enforced; an enforcement mechanism can exist without being assured), worked through the example 'Agents must not directly modify authoritative knowledge.' Companion baseline principle: Designed!=Implemented, Implemented!=Enforced, Enforced!=Proven, Proven!=Universally True.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1351] §"No architectural concept becomes CURRENT merely because we designed it. A concept becomes CURRENT only when there is sufficient evidence. Therefore: Designed ≠ Implemented, Implemented ≠ Enforced, Enforced ≠ Proven, and Proven ≠ Universally True."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1351] §"OBSERVED Directly evidenced | DESIGNED Intentionally specified | IMPLEMENTED Present in software/artifacts | ENFORCED Runtime/tooling actively prevents violation | ASSURED Evidence demonstrates the required property ... A design can be implemented without being enforced. An enforcement mechanism can exist without having been assured."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S1351`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1351 |
| type_signature | PRESENT | S1351 |
| invariants | PRESENT | S1351 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1351 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S1351]` types=[PRINCIPLE] scope=THEORY-LEVEL — "Baseline principle: no architectural concept becomes CURRENT merely because it was designed -- it becomes CURRENT only with sufficient evidence. Chained non-identities: Designed != Implemented, Implemented != Enforced, Enforced != Proven, Proven != Universally True; recommended as a foundational rule for both the book and KnowledgeOS itself." (anchor: "No architectural concept becomes CURRENT merely because we designed it. A concept becomes CURRENT only when there is sufficient evidence. Therefore: Designed ≠ Implemented, Implemented ≠ Enforced, Enf…")
- `[S1351]` types=[FORMALIZATION] scope=OBJECT — "Defines a five-state architectural maturity model OBSERVED (directly evidenced) -> DESIGNED (intentionally specified) -> IMPLEMENTED (present in software/artifacts) -> ENFORCED (runtime/tooling actively prevents violation) -> ASSURED (evidence demonstrates the required property), stressing that these transitions are not automatic: a design can be implemented without being enforced, and an enforcement mechanism can exist without having been assured. Worked example: 'Agents must not directly modif…" (anchor: "OBSERVED Directly evidenced | DESIGNED Intentionally specified | IMPLEMENTED Present in software/artifacts | ENFORCED Runtime/tooling actively prevents violation | ASSURED Evidence demonstrates the re…")

## Notes for P3
- Thin evidentiary base (2 rows) — any relationship claims beyond what is listed here would be unsupported.
- Ungrouped: no mechanical cross-link signal connected this label to any other label in P2a.
- No rationale-bearing (EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE) rows were found for this label in the capture; the object's motivation is not evidenced here.
