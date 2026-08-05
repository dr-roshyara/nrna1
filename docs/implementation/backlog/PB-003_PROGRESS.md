# PB-003 Inbox — Progress Tracker

**Ticket:** PB-003 · **IDD:** `PB-003_Inbox_Implementation_Design.md` · **Branch:** feature/pb003
**Lifecycle:** In Development · **Last updated:** 2026-07-06 · **Started:** 2026-07-06 · **Completed:** — · **Elapsed:** —
**Process:** `../Implementation_Process_v1.0.md` — ALL percentages in this file are **PB-003 TICKET-level** (ticked WBS / 18), DERIVED, never hand-written. Epic-level % lives in `EPIC-001_Greenfield_Core.md`; program-level in `../IMPLEMENTATION_PROGRESS.md` / `../PROGRAM_STATUS.md`. Never report an unscoped percentage.

### Capability-group progress (PRIMARY view)
```text
Port Layer        ██████████ 100%  (C1 ✔)
Infrastructure    ██████████ 100%  (migration + model ✔ C2 · wrapper ✔ C3)
Registry          ██████████ 100%  (registry + wiring ✔ C4)
Commands          ██████████ 100%  (inbox:redrive + schedule ✔ C5)
Testing           ██████████ 100%  (5/5 groups: unit ✔ · persist ✔ · consume ✔ · registry ✔ · redrive ✔)
Documentation     ██░░░░░░░░   partial
```
Secondary (derived backing count): **18 / 18 WBS** — PB-003 **VERIFIED / CERTIFIED** (ARR, 2026-07-07).

### C1 Implementation Review (gate result)
PASS · 0 Critical · 0 Major · 2 Minor (accepted, no action): (m1) IDD called InboxOutcome a "VO" — implemented as enum (correct DDD form for a closed set); (m2) InboxMessage.payload typed `array` per D-08. **Accepted for C2: YES.**

## Executable process state (hooks/sessions must respect this)

```text
Current commit: PB-003 COMPLETE — C6B ✔ 528619ba6 (ARR: CERTIFIED); C6A ✔ 305cfb9ff
Allowed next:  PB-004 (Election Reaction) — write its IDD FIRST (Architecture Review Gate), then implement C1.. ; carry residual risks R-1/R-2/R-3
Forbidden:     implementing PB-004 before its IDD is approved; editing FROZEN Implementation_Process_v1.0 (fold-in needs v1.1)
```

## Review checkpoints (ticket-boundary reviews — NOT per commit)

| Review | Status |
|--------|--------|
| Architecture Review (IDD) | ✔ 2026-07-06 |
| Platform Capability Certification / ARR (C6B — D-11) | ✔ 2026-07-07 CERTIFIED (`PB-003_Architecture_Readiness_Report.md`) |
| Implementation Review (subsumed by ARR) | ✔ 2026-07-07 |
| Merge Review (Architecture Review Checklist on PR) | ☐ |

## Commit plan (code commits C1–C6 · docs in separate PB-003-DOC commits)

| Commit | Scope (code only) | Estimate | Actual | Rollback |
|--------|-------------------|----------|--------|----------|
| PB-003-C1 | Port package (6 pure-PHP classes) + unit tests | 1h | 25m ✔ a421c2ef7 | `git revert <hash>` → prior suite green (no consumers yet) |
| PB-003-C2 | `inbox_events` migration + `InboxEvent` model | 45m | ~30m ✔ cec36ee07 | revert → table dropped, nothing references it |
| PB-003-C3 | `Inbox` wrapper + consume tests (7 scenarios) | 2h | ~40m ✔ 8930f47fd | revert → port remains, no runtime path uses wrapper yet |
| PB-003-C4 | `InboxHandlerRegistry` + container wiring | 45m | ~25m ✔ 3bc0b35dc | revert → wrapper testable via direct construction |
| PB-003-C5 | `inbox:redrive` + scheduling + config + tests | 1.5h | ~50m ✔ ce7f2b99d | revert → parked rows wait (no data loss; redrive is additive) |
| PB-003-C6A | Messaging Architecture Verification (10 property-based tests) + strict clock | 45m | ~55m ✔ 305cfb9ff | revert → guards + 1 signature tighten only |
| PB-003-C6B | Architecture Readiness Review (ARB) + RED-first guard hardening (F-1/F-2/F-3) | 45m | ~60m ✔ 528619ba6 | revert → tests only |
| PB-003-DOC | progress/dev-log/session/epic-board records (per session end) | 15m | — | revert → docs only |

**Per-commit DoD:** ☐ RED evidence captured (output, not claim) ☐ GREEN evidence captured ☐ PHPStan (greenfield gate) ☐ purity/rule checks for that commit ☐ tracker WBS ticked (edit now, commit in next PB-003-DOC)

**IDD clarification (recorded):** IDD §12-1 listed `InboxHandlerRegistryTest` under step 1; the registry is Infrastructure → its test belongs to PB-003-C4. C1 = port classes only. Clarification, not deviation.

## PB-003 Exit Criteria (ticket → Verified ONLY when ALL ✔)

```text
✔ Inbox port package exists (6 classes, pure PHP)                       C1
✔ inbox_events infrastructure exists (migration + model, UNIQUE)        C2
✔ Idempotent wrapper exists (dedupe/park/classify in ONE txn)           C3
✔ Handler registry exists (duplicate registration rejected)            C4
✔ Redrive exists (parked re-drive + deadline → dead)                    C5
✔ All PB-003 tests green (unit + feature + architecture)                verification 11/11 · Inbox 34
✔ Architecture suite green, zero regressions                            143 passed / 1 skip
✔ PHPStan greenfield gate green
✔ Traceability Matrix row → Verified
✔ Decision Log updated                                                  D-10, D-11
✔ Progress tracker at 18/18 (derived)
✔ Implementation Review passed (ticket boundary)                        subsumed by ARR (C6B)
✔ IMPLEMENTATION_BASELINE.md line appended
✔ Ready-for-PB-004 statement (inbox consumable by Election Reaction)    ARR §5 — CERTIFIED
```

**Ready-for-PB-004:** the Inbox is CERTIFIED as a reusable platform capability (see `PB-003_Architecture_Readiness_Report.md`). PB-004 (Election Reaction) may consume it by implementing an `InboxHandler` + registering it — no change to Shared. PB-004 still requires its own IDD before implementation. Residual risks R-1/R-2/R-3 carry forward (non-blocking).

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
- 2.3 `Inbox` wrapper + `InboxExecutionEngine` (shared run+classify) ✔ (C3/C5)

**3. Registry** *(C4)*
- 3.1 `InboxHandlerRegistry` ✔ (C4)
- 3.2 container singleton (AppServiceProvider) ✔ (C4)

**4. Commands** *(C5)*
- 4.1 `inbox:redrive` + scheduling + `config/inbox.php` ✔ (C5 · config in C3)

**5. Testing (RED first, every block)**
- 5.1 unit: message + classification *(C1)* ✔ · registry *(C4)* ✔
- 5.2 feature: consume semantics (7 scenarios per IDD §11-3) *(C3)* ✔ · persistence *(C2)* ✔
- 5.3 feature: re-drive (7 scenarios) *(C5)* ✔
- 5.4 architecture: Messaging Architecture Verification — 10 property-based invariants *(C6A)* ✔ 305cfb9ff
- 5.5 regression gates (Arch suite 142✔/1 skip · greenfield PHPStan clean · full Inbox 44✔) *(C6A)* ✔

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
