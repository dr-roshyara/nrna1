# transition-log

**Scope(s):** OBJECT · **Row count:** 8 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0002, scope OBJECT): The append-only transition log that is BC-7's lifecycle part and sole source of machine truth.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0203 §"Temporal knowledge is the biggest weakness in the current architecture ... EKS/AIP: only 2/218 transitions carry any date. ... It does not conclude that KnowledgeOS must implement bitemporal storage. It concludes: UNKNOWN whether a richer temporal model is needed."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0234. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S0234) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0203, S0206, S0234 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0206 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0206, S0234 |
| experiments | PRESENT | S0234 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
DeepSeek identifies temporal knowledge as the biggest architectural gap found: EKS/AIP transitions carry a date field in only 2/218 cases, and PKS has supersession semantics but lacks a timestamp dimension, so the architecture often cannot answer 'what was authoritative at time T?', only 'what is recorded now?'. Explicitly does NOT conclude bitemporal storage is required; concludes UNKNOWN whether a richer temporal model is needed -- 'that distinction is architecturally excellent.' [S0203] The persistence inversion: ADRs, reviews, and observation streams are durable/git-tracked/history-preserving/time-reconstructable, while grants+transitions -- the record of who was permitted to do what -- are NOT durable, NOT historied, and NOT temporally reconstructable, because .claude/runtime/ is gitignored (the only excluded subdirectory of .claude/, excluded twice, at lines 25 and 32, deliberately per registry.yaml AST-015 notes). Consequences: no git history for a grant/transition insertion; single-host authoritative state (any other clone folds to UNRESOLVABLE); no backup/replication/integrity check. [S0206] Temporal reconstructability is class E, MISSING: only 2/218 transitions (0.9%) carry any time-like field (day granularity only); ordering is by per-record seq alone, with no relation across records; combined with no git history, questions such as 'when was grant G-X registered?', 'which grants were in force when act Y occurred?', or 'did the handoff precede the human act?' are unanswerable by any mechanism -- 'authority in EKS is durably stated and completely un-timed.' [S0206] One file per work item under .claude/runtime/workflow/<work-item>.json holding schema/workItem/workflow/roles/transitions/grants; no derived state is persisted (mutationOwner, sessions, workItemState are folded at read time). Measured 2026-08-21 22:59: only 2 of 218 transitions carry any date; order is seq alone, with no systematic time dimension. [S0234]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S0203]` types=[LIMITATION, ANALYSIS] scope=CROSS-OBJECT — "DeepSeek identifies temporal knowledge as the biggest architectural gap found: EKS/AIP transitions carry a date field in only 2/218 cases, and PKS has supersession semantics but lacks a timestamp dimension, so the architecture often cannot answer 'what was authoritative at time T?', only 'what is recorded now?'. Explicitly does NOT conclude bitemporal storage is required; concludes UNKNOWN whether a richer temporal model is needed -- 'that distinction is architecturally excellent.'" (anchor: "Temporal knowledge is the biggest weakness in the current architecture ... EKS/AIP: only 2/218 transitions carry any date. ... It does not conclude that KnowledgeOS must implement bitemporal storage. It concludes: UNKNOWN whether a richer temporal model is needed.")
- `[S0206]` types=[ANALYSIS, WARNING] scope=OBJECT — "The persistence inversion: ADRs, reviews, and observation streams are durable/git-tracked/history-preserving/time-reconstructable, while grants+transitions -- the record of who was permitted to do what -- are NOT durable, NOT historied, and NOT temporally reconstructable, because .claude/runtime/ is gitignored (the only excluded subdirectory of .claude/, excluded twice, at lines 25 and 32, deliberately per registry.yaml AST-015 notes). Consequences: no git history for a grant/transition insertion; single-host authoritative state (any other clone folds to UNRESOLVABLE); no backup/replication/integrity check." (anchor: "The persistence inversion ... GRANTS + TRANSITIONS 🔴 NO 🔴 NO 🔴 NO (authority · ownership · lineage — the evidence of who was permitted to do what) ... .claude/runtime/ is the ONLY excluded subdirectory of .claude/")
- `[S0206]` types=[LIMITATION, ANALYSIS] scope=OBJECT — "Temporal reconstructability is class E, MISSING: only 2/218 transitions (0.9%) carry any time-like field (day granularity only); ordering is by per-record seq alone, with no relation across records; combined with no git history, questions such as 'when was grant G-X registered?', 'which grants were in force when act Y occurred?', or 'did the handoff precede the human act?' are unanswerable by any mechanism -- 'authority in EKS is durably stated and completely un-timed.'" (anchor: "Measured this session: 2 of 218 transitions (0.9%) carry any time-like field ... Authority in EKS is durably stated and completely un-timed. ... 'Reports are ephemeral: re-run before acting, never cache (staleness is uncomputable — the record carries no transition timestamps, D-1).'")
- `[S0206]` types=[LIMITATION, DISTINCTION] scope=OBJECT — "No event architecture exists in EKS (class E, verified by exhaustive absence of event type/bus/broker/publisher/subscriber/store/outbox/transport/schema). What exists instead: Triggers are synchronous adapters producing a ChangeSet DTO with no message/queue/delivery-guarantee; a Transition is a persisted fact, not a published event -- nothing subscribes, and governance discovers other processes' transitions 'by re-folding, not by notification' (first-hand: six transitions recorded by other windows during a single Governance session were discovered only by re-fold). Integration style stated plainly: 'a shared-file integration architecture with synchronous in-process calls.'" (anchor: "There is no event architecture in EKS. Class E — MISSING. ... 'Transition' | a persisted fact, not a published event. Nothing subscribes. Governance discovers other processes' transitions by re-folding, not by notification")
- `[S0234]` types=[CORRECTION, WARNING, EXPERIMENTAL-RESULT] scope=OBJECT — "Erratum E-1: the prior claim that dense/monotonic seq numbers make omission mechanically detectable is WITHDRAWN. MIGRATION-PLAN SS1.3 AMD3 CL-1 reproduced concurrent-append trials: 23/30 silently lost a transition (the losing process exited 0 reporting {ok:true,seq:2}) while every survivor stayed dense and monotonic (the clobbering writer reuses the lost writer's sequence number); the write is atomic per write (tmp+rename) but the read-modify-write is NOT atomic (no lock, lease, CAS or version check). Corrects P3 SS8.1." (anchor: "23 / 30 concurrent-append trials silently lost a transition")
- `[S0234]` types=[CORRECTION, WARNING] scope=OBJECT — "Erratum E-2: 'exactly one writer (workflow-state.php)' is WITHDRAWN as a structural guarantee -- it is only the currently identified primary writer. A second write-capable path exists: session-resolve.php:90 reads getenv('KOS_MECHANISM_PATH') and :103 executes the environment-named program via proc_open, handing it the authority-record directory as an argument (:156) -- a write-capable substitution path through a component whose own bytes contain no write call. Corrects P3 SS8.1/SS9.3." (anchor: "session-resolve.php:90 reads getenv('KOS_MECHANISM_PATH')")
- `[S0234]` types=[ANALYSIS, WARNING] scope=OBJECT — "One file per work item under .claude/runtime/workflow/<work-item>.json holding schema/workItem/workflow/roles/transitions/grants; no derived state is persisted (mutationOwner, sessions, workItemState are folded at read time). Measured 2026-08-21 22:59: only 2 of 218 transitions carry any date; order is seq alone, with no systematic time dimension." (anchor: "Only 2 of 218 transitions carry any date")
- `[S0234]` types=[EXPERIMENTAL-RESULT] scope=OBJECT — "Live fold of .claude/runtime/workflow/ measured 2026-08-21 22:59 (this session): 18 records, 218 transitions, 126 grants (125 unique grantIds). Documented prior measurement points: ADR written 16/210/99; IMPLEMENTATION-DESIGN START 17/213/102; AMD5 verified 18/216/110; AMD6 verified 18/216/114 (113 unique). The estate grows while the migration is unimplemented." (anchor: "records: 18 transitions: 218 grants: 126 (125 unique grantIds)")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
