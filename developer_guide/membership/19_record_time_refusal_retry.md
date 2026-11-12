# 19: Ledger slice 1, step 5 — a record-time refusal is a fresh attempt

**Story:** `PBDIGIT-343` · **Plan:** `docs/plans/20261112-1251-release-1-build-slice-1-membership-ledger-clock-and-c1-c2-plan.md` (task 5, D-3; blueprint G-1d) · **Decisions:** D-209, R-1a-2 · **Commit:** `ad4d5275b`

## Purpose

When the repository answers `RecordTimeRefused` (guide 18), **the command is not failed and the decision is not reused.** The application loop decides again against the latest state, and appends with a fresh clock reading.

## Where it fits

```text
Application  DecideAndAppend::run()          the bounded loop (MAX_ATTEMPTS = 3), pure PHP
             ApplicationRefusal::RecordTimeUnavailable   the fail-closed refusal
```

## How it works

```text
answer from key (IDEM-1)
repeat ≤ 3:  authorise → load → decide → append
  Appended / AlreadyApplied   → done
  ConcurrencyConflict         → again (R-1a-2)
  RecordTimeRefused           → again: fresh load, fresh decision, fresh reading (D-209)
  other outcomes              → refused at once
exhausted → refused with the LAST attempt's reason:
  RecordTimeRefused   → RecordTimeUnavailable
  ConcurrencyConflict → ConcurrentModification (unchanged)
```

The `match` stays **exhaustive with no default**, so a future outcome fails loudly (`UnhandledMatchError`) instead of being retried silently.

## Use

Every command handler that already uses `DecideAndAppend` (`AdmitMemberHandler`, `EndMembershipHandler`) gets this behaviour unchanged. **A caller seeing `RecordTimeUnavailable`** may send the same command again later (same key: idempotent).

## Testing

| Test | What it proves |
|---|---|
| `AppendOutcomeMappingTest` (the existing guard) | every outcome has a result; only conflict and clock refusal are retried |
| `DecideAndAppendRecordTimeRetryTest` (4) | <ul><li>re-load and re-decide against the latest state (a version-1 decision on the second attempt, a different fact);</li><li>exhaustion → `RecordTimeUnavailable`;</li><li>the last attempt's reason;</li><li>with the in-memory double: readings [500 existing, 500 refused, 600] → one new fact at exactly 600</li></ul> |
| `PostgresRecordTimeRetryTest` (3) | readings behind, equal, fresh → one fact and **one C1 row** at the fresh reading; exhaustion writes nothing; a replay writes nothing |

## Pitfalls

- **Do not add a retry around `append()` elsewhere.** The only retry is this loop, because it re-decides.
- A long clock regression exhausts the attempts: that is **fail closed by design** (D-209 §4); monitoring is a pre-launch item.

**Traceability:** D-209, R-1a-2, IDEM-1 · plan task 5, D-3 · G1d-1 · PBDIGIT-343.
