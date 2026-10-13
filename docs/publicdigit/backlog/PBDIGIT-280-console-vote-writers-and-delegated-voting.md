# PBDIGIT-280: Console commands that write votes have no environment guard; delegated-voting route undecided

**Type:** Security / voting integrity · **Epic:** none (voting) · **Created:** 2026-10-13 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from `PBDIGIT-274` (plan section 8; O-6 = (B) left these paths outside 274, "a new backlog item at plan closure"; to-do item D1). **Index row:** added 2026-10-13.

| | |
|---|---|
| **Status** | `OPEN` · not authorised |
| **Classification** | from the code reading recorded in the 274 plan (section 8, 2026-10-11). **P4 re-checked 2026-10-13:** all seven commands exist in `app/Console/Commands/`, and none calls `environment(`. P3 not re-checked |

## Findings (plan section 8, PBDIGIT-274)
- **P4: seven console commands write votes** with `Vote::create`, with **no environment guard and no confirmation**:
  - `test:create-votes`, `test:random-votes`, `test:full-election`;
  - `election:simple-populate`, `election:populate-all`;
  - `test:vote-counting`, `test:with-himani`.

  They change the vote count V without participation or slug records. Run against production, they would corrupt results.
- **P3: `POST /deligatevotes`** (`deligatevote.store`, `DeligateVoteController@store`) is registered but **statically non-functional**:
  - it calls the undefined methods `vote_post_check()`, `generateAndStoreVerificationKey()` and `markUserAsVoted()`;
  - `Vote`, `Hash` and `Log` are not imported.

  Whether delegated voting is a live product feature is unknown.
- **Not here:** P2 (`POST /votes`, reachable without the slug chain) is tracked in `PBDIGIT-275`.

## Proposed scope (from plan section 8, to be confirmed)
- **P4 guard contract:**
  - each command runs **only** when `app()->environment()` is exactly `local` or `testing`;
  - in any other environment it prints a refusal and exits non-zero **before touching the database**;
  - **no override flag.**
  - Test: `ConsoleVoteWriterGuardTest::test_{command}_refuses_outside_local_testing` (`production` and `staging`; non-zero exit; vote count unchanged).
- **P3:**
  1. first establish whether delegated voting is live: product documentation, UI links (`resources/js`), and production access logs (PO / ops);
  2. then remove the route (simplest, since it can't work) or integrate it properly (consume participation and mark a slug in the same transaction).

## Why it matters
`PBDIGIT-274`'s count invariant (ΔV = ΔC = ΔS) holds for slug-flow casts only (O-6 = B). These paths can change V without C or S, so production aggregates cannot be used as evidence until they are closed.

## Related
- `PBDIGIT-274` (plan sections 8 and 10.2; `CountInvariantTest`).
- `PBDIGIT-275` (P2).
