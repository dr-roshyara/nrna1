# PBDIGIT-297: About 1,800 tests are marked "risky" for changing PHP's error handlers

**Type:** Test-suite health · **Epic:** none · **Created:** 2026-10-14 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from the PBDIGIT-283 R6 positive runs (the first full default-suite runs on `nrna_test` since the 2026-10-13 incident). **Index row:** added 2026-10-14.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · creation approved by the Principal 2026-10-14 |
| **Classification** | **observed in test output**; cause **not analysed**; whether it predates PBDIGIT-283 **isn't established** (no earlier full-suite baseline) |
| **Scope** | outside PBDIGIT-283's acceptance; nothing fixed |

## Finding
- **1,819 risky notices** across the Unit and Feature runs: "Test code or tested code removed error handlers other than its own" (most) and "did not remove its own error handlers". Evidence: `docs/publicdigit/election/evidence/2026-10-14-pbdigit283-r6-positive/` (`stage-2-unit.txt`, `stage-3-feature-rerun.txt`, `stage-3-architecture.txt`).
- **This is PHPUnit 11 reporting** that a test or the code under test changed PHP's global error or exception handlers. Laravel's `HandleExceptions` bootstrapper installs handlers on every application boot.
- **Plain PHPUnit tests that boot Laravel themselves** (e.g. the database-free tests using `CreatesApplication`) may contribute. Not analysed.

## Impact
- The noise hides real risky tests; the counts make suite output hard to read.

## Proposed direction (nothing decided)
1. **Reproduce on one test,** and compare a Laravel `TestCase` with a plain-PHPUnit Laravel boot.
2. **Apply the Laravel/PHPUnit 11 compatible teardown** (`HandleExceptions::flushState()` where Laravel's lifecycle isn't used), or configure PHPUnit deliberately, documenting why.

## Acceptance criteria
1. The cause is identified on a minimal reproduction.
2. The risky count from error handlers drops to near 0 in a full run, without hiding other risky categories.
3. No PBDIGIT-283 guard is weakened.
