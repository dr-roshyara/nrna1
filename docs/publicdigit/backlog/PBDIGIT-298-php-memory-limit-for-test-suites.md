# PBDIGIT-298: The Feature suite runs out of memory under PHP's default 128 MB limit

**Type:** Test infrastructure · **Epic:** none · **Created:** 2026-10-14 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from the PBDIGIT-283 R6 positive runs (the first full default-suite runs on `nrna_test` since the 2026-10-13 incident). **Index row:** added 2026-10-14.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · creation approved by the Principal 2026-10-14 |
| **Classification** | **observed:** a fatal error at 128M; peak 144.5 MB with 1G |
| **Scope** | PBDIGIT-283's R6 positive used `php -d memory_limit=1G` on the command line as an authorised one-off; nothing in configuration changed |

## Finding
- **`vendor/bin/phpunit --configuration phpunit.xml --testsuite Feature`** aborted at 2,928 / 3,482 (84%) with "Allowed memory size of 134217728 bytes exhausted" (exit 255), while booting the application for `tests/Feature/Trust/VoterVerificationEmitsEventsTest.php`. Evidence: `…/stage-3-feature.txt`.
- **With `-d memory_limit=1G`** the suite completed, with a **peak of 144.5 MB** (`…/stage-3-feature-rerun.txt`). The Unit suite peaked at 106 MB.
- **`phpunit.xml` and `phpunit-pgsql.xml` set no `memory_limit`;** the PHP CLI default is 128M.

## Proposed direction (nothing decided)
1. **Set a deliberate limit** for test runs, e.g. `<ini name="memory_limit" value="512M"/>` in `phpunit.xml` (and `phpunit-pgsql.xml`), or document the command-line flag.
2. **Optionally investigate the growth** over ~3,000 application boots (a leak vs. normal growth).

## Acceptance criteria
1. A full Feature run completes without a memory fatal using the documented configuration or command.
2. The chosen limit and its reason are recorded. `DefaultSuitePinningTest` (PBDIGIT-283 R2) still passes if the XML files change.
3. Runs only under an authorised test-database run.
