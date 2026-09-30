# session-assignment-resolver

**Scope(s):** OBJECT · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** AST-016, session-resolve.php · **Aliases:** Session Assignment Resolver
**Candidate group membership (NOT an identity claim):**
- **G0056** [`session-assignment-resolver` · `session-bootstrap-ast017`] — explicit agent-stated uncertainty: 'session-bootstrap-ast017' POSSIBLY relates to 'session-assignment-resolver' (batch B0009). Note: Read-only, ON_DEMAND resolver capability composing AST-015 (fold/identity/authorized) to answer a governed session's lane/role/authorization deterministically at start; six-way output separation (identity/assignment/activation_prerequisites/grant/mutation_owner/gates/continuation); one bounded raw-read exception (v3HandoffRead).


## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch `B0006`, scope `OBJECT`: The read-only session-assignment-resolution asset (AST-016); carries the open V-3 handoff-distinguishability gap and the P-4 write-capable KOS_MECHANISM_PATH substitution path (proc_open).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0234] §"session-resolve.php:90 reads getenv('KOS_MECHANISM_PATH')"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0234. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used (S0234), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0234 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0234 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S0234 |

## Rationale
- [S0234] (ANALYSIS) Where the record lives: P-1 default workflow-state.php:81, P-2 default session-resolve.php:74 (two independent defaults, RA-2), P-3a/b the two --dir overrides. Which mechanism interprets it: P-4 session-resolve.php:90 getenv('KOS_MECHANISM_PATH') -> :103 proc_open -> :156, a write-capable substitution path. The mechanism already supports relocation; only the default contradicts the B' relocation.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0234] types=[CORRECTION, WARNING] scope=OBJECT — "Erratum E-2: 'exactly one writer (workflow-state.php)' is WITHDRAWN as a structural guarantee -- it is only the currently identified primary writer. A second write-capable path exists: session-resolve.php:90 reads getenv('KOS_MECHANISM_PATH') and :103 executes the environment-named program via proc_open, handing it the authority-record directory as an argument (:156) -- a write-capable substitution path through a component whose own bytes contain no write call. Corrects P3 SS8.1/SS9.3." (anchor: "session-resolve.php:90 reads getenv('KOS_MECHANISM_PATH')")
- [S0234] types=[LIMITATION, OPEN-QUESTION] scope=OBJECT — "AST-016 (session-resolve.php) V-3 cannot distinguish a recorded handoff from an absent one; it fails safe. Remedy UNDECIDED. PO/ARB condition (BINDING and OUTSTANDING): V-3 must be resolved before AST-016 is wired into SESSION_START or any automatic startup path. AST-015/016 are advisory overall -- violations are visible/adjudicable, not physically prevented (Increment-2 not authorized)." (anchor: "AST-016 V-3 is an OPEN ARCHITECTURE/SPECIFICATION GAP")
- [S0234] types=[ANALYSIS] scope=OBJECT — "Where the record lives: P-1 default workflow-state.php:81, P-2 default session-resolve.php:74 (two independent defaults, RA-2), P-3a/b the two --dir overrides. Which mechanism interprets it: P-4 session-resolve.php:90 getenv('KOS_MECHANISM_PATH') -> :103 proc_open -> :156, a write-capable substitution path. The mechanism already supports relocation; only the default contradicts the B' relocation." (anchor: "Path sources are THREE, on TWO axes")

## Notes for P3
- No unusual internal tensions or notable evidentiary anomalies observed while compiling this file.
