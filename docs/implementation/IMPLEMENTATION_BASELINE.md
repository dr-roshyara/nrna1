# Implementation Baseline — Official History Register

**Status:** append-only · ONE line per ticket reaching **Verified** · plus baseline/freeze events.
**Rule:** entries are immutable facts (date · item · gates · commit). Corrections append, never edit.

## Baseline events

| Date | Event | Evidence |
|------|-------|----------|
| 2026-07-06 | **IMPLEMENTATION BASELINE 1.1 FROZEN** — all pre-PB-003 work committed; tree clean; Blueprint v1.0 + Process v1.0 frozen; tag `baseline-release-1.1` | commits `e61e111d0` (AD-001..005) · `67d4602ca` (PB-001+PB-002) · `0be566370` (docs/governance) · `afae5e9d5` (.claude project OS) · gates: Architecture suite 133/133 · greenfield PHPStan PASS |

## Verified tickets

| Date | Ticket | Verdict | Gates | Commit |
|------|--------|---------|-------|--------|
| 2026-07-06 | PB-001 Event Registry | Verified | Arch PASS · PHPStan PASS · Tests 12/12 · Mutation PENDING-F2 | `67d4602ca` |
| 2026-07-06 | PB-002 Relay Registry | Verified | Arch PASS (133/133) · PHPStan PASS · Tests 14/14 outbox · Mutation PENDING-F2 | `67d4602ca` |
| 2026-07-07 | PB-003 Inbox / Deduplication | **Verified / Certified (ARR)** | Arch Fitness 143/1-skip · greenfield PHPStan PASS · Inbox 34 + verification 11/11 · ARR CERTIFIED (F-1/F-2/F-3 remediated; R-1/R-2/R-3 residual) · Mutation PENDING-F2 | C1 `a421c2ef7` · C2 `cec36ee07` · C3 `8930f47fd` · C4 `3bc0b35dc` · C5 `ce7f2b99d` · C6A `305cfb9ff` · C6B `528619ba6` |

*(next line: PB-004 upon Verified)*
