# Voter slug — developer guide index

A voter slug is a voter's 30-minute voting session for one election (`VoterSlug` real, `DemoVoterSlug` demo).

| # | Guide | Story | Status |
|---|---|---|---|
| 01 | [`VerifyVoterSlug`, the server-side voter-slug guard](01_verifyvoterslug_guard.md) | `PBDIGIT-273` | implemented with database-free tests green; request-path tests not run; committed `6d3b9e1e0`, not pushed; merge and deploy only with `PBDIGIT-274` |
| 02 | [The lifetime rule and the route lookup](02_lifetime_rule_and_route_lookup.md) | `PBDIGIT-274` S1 | implemented with database-free tests passing; in HEAD via `4d83dffc0` (original `a78d7c2f2`); not pushed; later slices gated |
| 03 | [The isolated test database guard](03_isolated_database_guard.md) | `PBDIGIT-274` S2 steps 1, 1b | step 1 committed `2e9017487`; step 1b (review corrections) committed 2026-10-12, database-free tests passing; nothing approved before G4; never run against PostgreSQL |

**Next:** the lifecycle (fixed lifetime from creation, replacement by a new row, election-specific issuance), `PBDIGIT-274`.
