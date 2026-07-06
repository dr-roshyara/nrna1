# PB-003 Inbox — Progress Tracker

**Ticket:** PB-003 · **IDD:** `PB-003_Inbox_Implementation_Design.md` · **Branch:** feature/pb003
**Lifecycle:** In Development · **Last updated:** 2026-07-06 · **Started:** 2026-07-06 · **Completed:** — · **Elapsed:** —
**Process:** `../Implementation_Process_v1.0.md` — ALL percentages in this file are **PB-003 TICKET-level** (ticked WBS / 18), DERIVED, never hand-written. Epic-level % lives in `EPIC-001_Greenfield_Core.md`; program-level in `../IMPLEMENTATION_PROGRESS.md` / `../PROGRAM_STATUS.md`. Never report an unscoped percentage.

### Capability-group progress (PRIMARY view)
```text
Port Layer        ██████████ 100%  (C1 ✔)
Infrastructure    ██████████ 100%  (migration + model ✔ C2 · wrapper ✔ C3)
Registry          ██████████ 100%  (registry + wiring ✔ C4)
Commands          ░░░░░░░░░░   0%  (C5)
Testing           ████████░░  80%  (4/5 groups: unit ✔ · persist ✔ · consume ✔ · registry ✔)
Documentation     ██░░░░░░░░   partial
```
Secondary (derived backing count): **13 / 18 WBS**.

### C1 Implementation Review (gate result)
PASS · 0 Critical · 0 Major · 2 Minor (accepted, no action): (m1) IDD called InboxOutcome a "VO" — implemented as enum (correct DDD form for a closed set); (m2) InboxMessage.payload typed `array` per D-08. **Accepted for C2: YES.**

## Executable process state (hooks/sessions must respect this)

```text
Current commit: PB-003-C5 · Current step: 4 (RED) — next session
Allowed next:  C5 RED (inbox:redrive command + scheduling + re-drive tests)
Forbidden:     starting C6+, skipping RED, doc commits before code commit
```

## Review checkpoints (ticket-boundary reviews — NOT per commit)

| Review | Status |
|--------|--------|
| Architecture Review (IDD) | ✔ 2026-07-06 |
| Implementation Review (after C6, before merge request) | ☐ |
| Merge Review (Architecture Review Checklist on PR) | ☐ |

## Commit plan (code commits C1–C6 · docs in separate PB-003-DOC commits)

| Commit | Scope (code only) | Estimate | Actual | Rollback |
|--------|-------------------|----------|--------|----------|
| PB-003-C1 | Port package (6 pure-PHP classes) + unit tests | 1h | 25m ✔ a421c2ef7 | `git revert <hash>` → prior suite green (no consumers yet) |
| PB-003-C2 | `inbox_events` migration + `InboxEvent` model | 45m | ~30m ✔ cec36ee07 | revert → table dropped, nothing references it |
| PB-003-C3 | `Inbox` wrapper + consume tests (7 scenarios) | 2h | ~40m ✔ 8930f47fd | revert → port remains, no runtime path uses wrapper yet |
| PB-003-C4 | `InboxHandlerRegistry` + container wiring | 45m | ~25m ✔ 3bc0b35dc | revert → wrapper testable via direct construction |
| PB-003-C5 | `inbox:redrive` + scheduling + config + tests | 1.5h | — | revert → parked rows wait (no data loss; redrive is additive) |
| PB-003-C6 | Architecture tests (port purity, single-writer) | 45m | — | revert → guards only |
| PB-003-DOC | progress/dev-log/session/epic-board records (per session end) | 15m | — | revert → docs only |

**Per-commit DoD:** ☐ RED evidence captured (output, not claim) ☐ GREEN evidence captured ☐ PHPStan (greenfield gate) ☐ purity/rule checks for that commit ☐ tracker WBS ticked (edit now, commit in next PB-003-DOC)

**IDD clarification (recorded):** IDD §12-1 listed `InboxHandlerRegistryTest` under step 1; the registry is Infrastructure → its test belongs to PB-003-C4. C1 = port classes only. Clarification, not deviation.

## PB-003 Exit Criteria (ticket → Verified ONLY when ALL ✔)

```text
☐ Inbox port package exists (6 classes, pure PHP)
☐ inbox_events infrastructure exists (migration + model, UNIQUE(event_id, consumer_context))
☐ Idempotent wrapper exists (dedupe/park/classify in ONE txn)
☐ Handler registry exists (duplicate registration rejected)
☐ Redrive exists (parked re-drive + deadline → dead)
☐ All PB-003 tests green (unit + feature + architecture)
☐ Architecture suite green, zero regressions
☐ PHPStan greenfield gate green
☐ Traceability Matrix row → Verified
☐ Decision Log updated (or explicit "no new decisions")
☐ Progress tracker at 18/18 (derived)
☐ Implementation Review passed (ticket boundary)
☐ IMPLEMENTATION_BASELINE.md line appended
☐ Ready-for-PB-004 statement (inbox consumable by Election Reaction)
```

## Work Breakdown Structure (tick per commit; % derives from here)

**1. Port Layer (Shared\Application\Inbox — pure PHP)** *(PB-003-C1)*
- 1.1 `InboxMessage` ✔ (C1)
- 1.2 `InboxHandler` ✔ (C1)
- 1.3 `InboxOutcome` ✔ (C1)
- 1.4 `CausalPreconditionMissing` ✔ (C1)
- 1.5 markers `IdempotentReplay` / `PermanentInboxFailure` ✔ (C1)

**2. Infrastructure (Shared\Infrastructure\Inbox)** *(C2, C3)*
- 2.1 `inbox_events` migration (UNIQUE event_id+consumer_context, D-03) ✔ (C2)
- 2.2 `InboxEvent` model ✔ (C2)
- 2.3 `Inbox` wrapper (txn: dedupe-insert → handle → classify → mark) ✔ (C3)

**3. Registry** *(C4)*
- 3.1 `InboxHandlerRegistry` ✔ (C4)
- 3.2 container singleton (AppServiceProvider) ✔ (C4)

**4. Commands** *(C5)*
- 4.1 `inbox:redrive` + scheduling + `config/inbox.php` ✘

**5. Testing (RED first, every block)**
- 5.1 unit: message + classification *(C1)* ✔ · registry *(C4)* ✔
- 5.2 feature: consume semantics (7 scenarios per IDD §11-3) *(C3)* ✔ · persistence *(C2)* ✔
- 5.3 feature: re-drive (4 scenarios) *(C5)* ✘
- 5.4 architecture: port purity + single-writer scan *(C6)* ✘
- 5.5 regression gates (Arch suite · Adjudication · outbox) *(every commit)* ✘

**6. Documentation** *(PB-003-DOC commits + ticket close)*
- 6.1 Traceability Matrix row → Verified ✘
- 6.2 Decision Log entry (or explicit "none") ✘
- 6.3 BACKLOG/EPIC + this tracker + DEVELOPMENT_LOG ✘

## Quality Gate (ticket-level definition of Done)

| Gate | Result |
|------|--------|
| Unit + feature + architecture tests | — |
| Regression suites | — |
| PHPStan (greenfield gate + ad-hoc max on new Shared classes) | — |
| Mutation | PENDING (F-2, program-wide → PB-007) |
| Implementation Review | — |
| Merge Review (Checklist on PR) | — |
