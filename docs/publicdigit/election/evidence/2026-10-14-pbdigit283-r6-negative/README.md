# PBDIGIT-283 R6 negative evidence (G-RUN-NEG), 2026-10-14

Command (repository root): `php tests/Evidence/Pbdigit283/negative-evidence.php`

| Run | File | Code | Outcome |
|---|---|---|---|
| 1 | `run-1-aborted-before-canary.txt` | `eb18ccfa9` | **ABORTED before the canary** (exit 1): the loaded connection set included `mariadb`, merged by Laravel 11 from its framework defaults. The canary never ran; no connection was attempted. Not evidence of refusal. Corrected test-first; `DB_URL` gap → backlog `PBDIGIT-293`. |
| 2 | `run-2.txt` | `4ce631f39` | **Harness exit 0 (EVIDENCE).** Pre-run check OK: 8 loaded connections, every environment-controlled one at the dead socket and port 1; `testing` = the exact Option A entry; default `pgsql` → `publicdigit`; not cached; db manager not bound. Canary (`--no-configuration`) exit 2: `RuntimeException: PBDIGIT-283: refusing to boot Laravel … nothing was connected`, naming `pgsql`, `pgsql_audit`, `pgsql_geo` → `publicdigit`. The trace shows the refusal from the `bootstrapped: LoadConfiguration` event (`Application.php:344` → `CreatesApplication.php:34`), before `RegisterProviders`. No `SQLSTATE`, no connection error; the canary body wasn't reached. **Accepted by the Principal 2026-10-14.** |

Harness, rules and design: `developer_guide/testing/06_negative_evidence.md`.
