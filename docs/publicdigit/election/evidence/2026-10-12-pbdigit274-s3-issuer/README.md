# PBDIGIT-274 S3a / S3b: voter-slug issuer on the isolated database, 2026-10-12

**Authority:** Human PO, 2026-10-12. S3a / S3b authorised, real elections only (D1). Not authorised: migrations, `migrate:fresh`, G6, callers, the cast path.

**Code under test (uncommitted at run time):**
- `app/Services/Voting/VoterSlugIssuer.php`, `VoterSlugIssuance.php`;
- `app/Exceptions/Voting/VoterSlugIssuanceConflict.php`;
- tests `tests/Unit/Services/Voting/VoterSlugIssuerTest.php` (database-free), `tests/Isolated/Pbdigit274/Database/VoterSlugIssuerTest.php`;
- base class `tests/Isolated/Pbdigit274/IsolatedCommittedDatabaseTestCase.php`.

| # | File | What | Result |
|---|---|---|---|
| 01 | `01-issuer-database-run.out` | `. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/VoterSlugIssuerTest.php --display-deprecations` | bootstrap identity `confirmed`; **18 tests, 77 assertions, exit 0**, first run. 3 deprecations: `PDO::MYSQL_*` config, not new code |
| 02 | `02-after-run.out` | read-only `psql` afterwards | 0 voter slugs / codes / steps / elections / users left (fixtures cleaned up). The one organisation, `publicdigit`, was created at 21:11 UTC by the S2 run's `migrate:fresh`, not by this run. Migrations: 225, unchanged since the S2 run; **this run did not migrate** |

**Why a committing test base.** `issue()` refuses to run inside a transaction (plan 5), and `IsolatedDatabaseTestCase` wraps every test in one and runs `migrate:fresh` (`RefreshDatabase`). `IsolatedCommittedDatabaseTestCase`:
- applies the same identity guard (unconfined connections removed, all connections checked, live `current_database()`);
- never migrates or refreshes, and requires the S2 migration to be recorded;
- lets each test delete its own fixtures (hard delete on the isolated database; foreign-key cascades).

**Covered:**
- **S3a (plan 5.1):**
  - no link → one issued, `expires_at = now + T`;
  - a valid link reused, nothing written;
  - an expired link replaced: new id and string, the old one soft-deleted / inactive / `expired`, steps kept;
  - a deactivated link replaced;
  - `AlreadyVoted` with nothing written, via the code row, a soft-deleted voted slug, or a current voted slug;
  - the `retrieved` hook writes nothing on a refusal;
  - lost entitlement → the link is retired (committed) and `NotEntitled`;
  - not entitled without a link → nothing written;
  - other voters and elections untouched;
  - invalid lifetime → nothing issued;
  - inside a transaction → `LogicException`.
- **S3b:**
  - a **real** slug-string collision → a new string;
  - a persistent collision → conflict after 3 attempts, no link;
  - **SIMULATION** (injected through a seam): an active-slug conflict → decided again once; repeated → conflict, both attempts rolled back; an unknown constraint → no retry.

**Not covered:**
- **G6:** real concurrency, two processes and two connections. No concurrency safety is claimed.
- **Callers (S4/S5) and the cast path (S9).**
- **The entitlement rules themselves:** a stand-in port is used here; they are tested elsewhere.
