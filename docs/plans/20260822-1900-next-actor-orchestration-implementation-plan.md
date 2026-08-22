# Next-Actor Orchestration / Business-Language Handoff — implementation plan

**Work item:** `KOS-NEXT-ACTOR-ORCHESTRATION-001` (incl. `AMENDMENT-001`) · **Lane:** `5c0e13c1-…` [implementation] = ACTIVE
**Asset:** `AST-018` — `.claude/scripts/next-actor-orchestration.php` (CMP-004's FOURTH implementation asset)

## Objective

Remove the mechanical burden (`REGISTER`/`HANDOFF`/`START`/UUIDs/transition JSON) from the human,
without weakening governance or human authority.

## Design decisions

**D1 — Placement.** `.claude/scripts/next-actor-orchestration.php` + contract test in
`tests/Unit/Platform/WorkflowEngine/`. Canonical discovery (ES-005.4): the KnowledgeOS governance
mechanism family (AST-015/016/017) lives as single-file CLI PHP there, contract-pinned in that test
dir. `app/Contexts/Governance` is PublicDigit **election** governance — a different bounded context;
placing meta-governance there would be a boundary violation. The commission's hexagonal intent is
honoured *inside* the file: pure domain functions · application use cases · port adapters.

**D2 — AST-015 is the single workflow authority.** Every state fact via subprocess
(`fold`/`identity`/`authorized`); every write via `append`. No second fold, no state from raw JSON,
no duplicated mutation-owner or authorization logic.

**D3 — AST-017 untouched.** Remains READ-ONLY / ON_DEMAND.

**D4 — Human authority is never manufactured.** `--human-act` is a required input for the appointment
path. No AI message becomes a `humanAct`; no inference of "Yes"; no auto-start.

**D5 — Candidate identity is never manufactured.** Identities arrive as explicit `--candidate`
inputs (the empirical probe: a subagent inherits the parent `CLAUDE_CODE_SESSION_ID`). 0 → `NO_ELIGIBLE_CANDIDATE`,
1 → proposable, >1 → `AMBIGUOUS`. Never silently choose.

**D6 — Pre-flight before the first append.** The transition log is append-only and cannot be rolled
back, so all preconditions are checked before any write; a mid-sequence failure is reported honestly.

**D7 — Next-actor progression is a declared, named policy**, not silent invention:
`architecture|implementation → verification → governance → human (adoption decision)`. Unknown
progression → `AMBIGUOUS` + human decision required.

## Use cases

| Command | Use case | Writes? |
|---|---|---|
| `next-actor` | UC1 `DetermineNextActorAction` | no |
| `appoint` | UC2 `AppointReviewer` (Option 1) | yes — only with a recorded human act |
| `prepare-prompt` | advisory `PrepareReviewerPrompt` (Option 2) | no |
| `prepare-next-session` | **UC3 `PrepareNextActorSession`** (AMENDMENT-001) | no |
| `stop` | Option 3 | no |

## Task checklist

- [x] RED contract test `NextActorOrchestrationContractTest.php` — 26 tests failing by absence
- [x] GREEN `AST-018` — 27/27 (312 assertions); full WorkflowEngine suite 77/77 (878)
- [x] `N-16` added mid-slice: the appointed actor must be AST-017-attributable and authorized
- [x] Registry entry `AST-018` (`adoption: verify` — adoption NOT claimed)
- [x] Developer guide `developer_guide/ai_platform/04_next_actor_orchestration.md` + index
- [x] FOLLOW-UP observations recorded (FU-1 durability · FU-2 attribution-vs-mention)
- [x] Session Completion Report → STOP

## Progress — COMPLETE (implementation only; NOT adopted)

Implemented all four commissioned use cases plus `stop`. The one substantive discovery during
implementation was **N-16**: an appointment can fold `ACTIVE` while leaving the appointed actor
`UNRESOLVED` at `AST-017`, because the identity label was written before a period and the extractor
captured the period into the id. Fixed producer-side and pinned end-to-end. The `AST-017`-side
generalisation is recorded as `FU-2`, not fixed here.

## Risks

- Partial append on mid-sequence failure (mitigated by D6; reported honestly if it occurs).
- The next-actor progression policy (D7) is the one place judgement enters — it is declared, fail-closed, and advisory.

## Open questions

- Whether the lane's own governance-recording by the appointed actor was legitimate → **for the independent verifier**, not self-decided.
