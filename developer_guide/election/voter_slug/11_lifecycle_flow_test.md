# 11: The voting-link lifecycle end to end (PBDIGIT-274 S8)

**Story:** `PBDIGIT-274` S8 · **Test:** `tests/Isolated/Pbdigit274/Database/VoterSlugLifecycleFlowTest.php` · **Production code:** none (a verification slice).

## Purpose
One test that walks the business rule the way a voter lives it, over HTTP, across the work of S3–S7:

| Step | Voter does | Asserted |
|---|---|---|
| 1 | starts voting | one link; redirect to `slug.code.create`; step 1 opens (200) |
| 2 | starts again 10 min later | the same link; rows byte-identical (no extension) |
| 3 | another entitled voter opens it | 404; nothing created for them |
| 4 | opens it after 31 min | redirect to `elections.show` with the expiry message; row unchanged |
| 5 | starts again | a new link; the old one soft-deleted; exactly one current |
| 6 | opens the old link, then the new one | 404, then 200 |
| 7 | starts again after voting | redirect to `elections.show`; nothing issued or changed |

A second test covers a voter who **loses entitlement**: the next start retires the current link (O-3), issues none, and the old link answers 404.

## How it works
- **Time passes through the clock, not by rewriting timestamps:**
  ```php
  $this->travel(10)->minutes();
  ```
  The issuer, the guard and the stored rows all see the same "now". Rewriting `created_at` / `expires_at` in a fixture can hide bugs in how the code itself sets them.
- **The base is `IsolatedCommittedDatabaseTestCase`:** the issuer refuses to run inside a transaction, so the data is committed, then deleted in `tearDown`.
- **Step 7 sets `has_voted`** on the voter's codes row the way a committed cast leaves it. The real cast is tested in guide 12.

## Testing
```bash
. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/VoterSlugLifecycleFlowTest.php
```

## Pitfalls
- **No RED run exists:** S8 adds no behaviour; it guards S3–S7 against regressions.
- **The lost-entitlement assertions were pinned after one probe run** that printed the actual outcome (404, link retired). The evidence README says so.
- **Building a similar journey?** Copy this fixture: `real()->inVotingState()` with `voting_locked`, a voting window and an approved candidacy (inserted directly; the post and candidacy factories create untracked rows).

**Traceability:** `PBDIGIT-274` S8: test `46bcdb9f7`; evidence `0ff538e78`.
