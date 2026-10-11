# Voter slug — developer guide index

A voter slug is a voter's 30-minute voting session for one election (`VoterSlug` real, `DemoVoterSlug` demo).

| # | Guide | Story | Status |
|---|---|---|---|
| 01 | [`VerifyVoterSlug`, the server-side voter-slug guard](01_verifyvoterslug_guard.md) | `PBDIGIT-273` | implemented with database-free tests green; request-path tests not run; not committed; deploy only with `PBDIGIT-274` |

**Next:** the lifecycle (fixed lifetime from creation, replacement by a new row, election-specific issuance), `PBDIGIT-274`.
