# PBDIGIT-283 R6 negative evidence (G-RUN-NEG), 2026-10-14

Command (repository root): `php tests/Evidence/Pbdigit283/negative-evidence.php`

| Run | File | Code | Outcome |
|---|---|---|---|
| 1 | `run-1-aborted-before-canary.txt` | `eb18ccfa9` | **ABORTED before the canary** (exit 1): the loaded connection set included `mariadb`, merged by Laravel 11 from its framework defaults. The canary never ran; no connection was attempted. Not evidence of refusal. Corrected test-first; `DB_URL` gap → backlog `PBDIGIT-293`. |
| 2 | `run-2.txt` | correction commit | recorded after the run |

Harness, rules and design: `developer_guide/testing/06_negative_evidence.md`.
