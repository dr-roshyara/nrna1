# PBDIGIT-158 — The adjudication issuance marker comes back two hours shifted after persistence

**Type:** Defect (data correctness, legal time) · **Epic:** `PBDIGIT-EPIC-06` Results & Audit · **Created:** 2026-10-01
**Found by:** Push B / Greenfield Core status verification (`docs/implementation/2026-10-01-push-b-status-verification.md` §2)
**Raised by:** `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared, not attestable)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🔴 **OPEN — NOT AUTHORISED FOR IMPLEMENTATION**. Root cause not yet established |
| **Business problem** | When adjudication asks for a determination to be issued, the moment of that request is recorded as a legal fact. **After it is saved and loaded again, the recorded moment is two hours earlier than the moment that was saved.** Any audit, dispute or recovery that relies on "when was issuance requested?" reads a wrong time. |
| **Severity** | 🔴 High as **risk**: it corrupts a governance timestamp silently. No customer-visible symptom has been reported yet. |

## Verified finding

`tests/Feature/Contexts/Adjudication/IssuanceContextRoundTripTest.php::test_the_issuance_marker_persists_and_closes_the_crash_window` fails (re-run 2026-10-01: 3 tests, 1 failure):

```
-'2026-08-02T12:00:05+00:00'      (saved)
+'2026-08-02T10:00:05+00:00'      (loaded)
```

**Facts that narrow it down:**
- The column is timezone-aware: `$table->timestampTz('issuance_requested_at')` (`…/Migrations/Tenant/2026_08_03_000001_add_issuance_context_to_adjudication_processes_table.php:49`).
- The **other** timestamps of the same process round-trip correctly in the same test file (`openedAt`, `concludedAt`, asserted at `:175-180`).
- The persistence path is symmetric for `concluded_at` and `issuance_requested_at`: the same `immutable_datetime` cast (`AdjudicationProcessModel.php:71-72`), and the same mapper treatment (`AdjudicationProcessMapper.php:50/58` write, `:80/86` read).
- The 2-hour offset equals the Europe/Berlin summer offset (UTC+2) on the test date. That suggests the instant is being interpreted as local time somewhere on the issuance path.
- The test and the field came with WP-4B (`c3409d69f`, 2026-08-03), after EPIC-001 closed. The failure is inside the merge gate's `GreenfieldCore` suite.

**Root cause: UNKNOWN.** Because the mapper and the cast are symmetric, the shift is introduced elsewhere on the issuance path: where the request instant is produced, a clock, a direct update, or how the value reaches the state. Whether the result depends on the machine's or the database session's timezone is also UNKNOWN.

## Scope (when authorised)

1. **Characterize:** reproduce with the machine and database session timezones fixed to UTC and to Europe/Berlin, and record which component shifts the instant.
2. **RED:** the existing test, unchanged, is the failing test.
3. **GREEN:** the smallest change on the issuance path so that the instant saved equals the instant loaded in every timezone.

## Out of scope

No change to the test's assertion. No change to other Adjudication timestamps (they are correct). No timezone policy change for the application. Not related to the election schedule timezone defect `PBDIGIT-67`, which is a different path (browser input), although it is the same class of problem.

## Acceptance

`IssuanceContextRoundTripTest` passes with the assertion unchanged, under both UTC and Europe/Berlin settings. A regression test pins the timezone independence.

## Sequence

Independent of the constitutional-runtime tracks. In the Push B verification sequence it is item 2, after `ENG-015` / `PBDIGIT-159` (status truth) and before `ENG-013` / `PBDIGIT-160` and `ENG-014` / `PBDIGIT-161`. **Related:** `PBDIGIT-178` (the same issuance path, R-91 transaction boundary); R-82 fixes the marker's meaning as "a request was made".

**Traceability:**
- `IssuanceContextRoundTripTest.php:191-205`
- `AdjudicationProcessModel.php:66-74`
- `AdjudicationProcessMapper.php:50-86`
- migration `2026_08_03_000001` `:49`
- commit `c3409d69f`
- `docs/implementation/2026-10-01-push-b-status-verification.md`
