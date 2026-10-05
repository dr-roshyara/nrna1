# PBDIGIT-234 — Voter IP binding reads `users.voting_ip`, a column that does not exist

**Type:** Defect (dead security branch, schema / code mismatch) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** Phase 4 E1 RED (`docs/plans/20261005-1105-phase4-final-cast-hardening-plan.md`, rev 3) — the R1 IP-binding contract failed in its own setup
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Existing-story search (freeze rule):** `PBDIGIT-222` (IP verification has no backend of its own) mentions that the vote-time trust check uses `users.voting_ip`, but not that the column is missing; `PBDIGIT-45` (IP-density consolidation) concerns density, not binding. No existing story covers this obligation.

| | |
|---|---|
| **Status** | 🟡 **OPEN — not authorised.** Deferred to the later security / IP-binding phase (roadmap Phase 6/7; Phase 7 IP binding is blocked pending the anonymity-boundary decision). **Not part of Phase 4** (P4-D4 retired, PO 2026-10-05). |
| **Defect (FACT)** | No migration creates `users.voting_ip`. Writing it fails: `SQLSTATE[42703]: column "voting_ip" of relation "users" does not exist`. Reading it on a `User` model always yields `null`. |
| **Consequence (FACT for the cast path)** | `validateVotingIpWithResponse()` (`app/Helpers/helpers.php:200`) always returns "skip — no_voting_ip_set", so its mismatch branch (the `Vote/VoteDenied` "IP Address Mismatch" page, `VoteController::store` `:1670-1673`) is unreachable. `VoteController::store` `:1475-1479` never computes a `registeredIpHash`, so the trust chain's network-continuity input is always `null`. |
| **Wider surface (REPORTED, not individually verified)** | `voting_ip` is referenced 57 times in 10 files under `app/` (e.g. `ValidateVotingIp` middleware, `VotingSecurityService`, `BulkApproveVoters` / `BulkDisapproveVoters`, `VoterlistController`, `ElectionProcessController`, `ElectionSettingsService`, `LogDivergenceObservations`). Writers would fail; readers see `null`. `IpValidationTest` is skipped as legacy. |
| **Affected boundary** | voter IP binding (legacy) — not the Phase 4 final-cast authorization |
| **Constitutional impact** | an IP-binding protection that appears to exist does not operate; no ballot-secrecy impact (nothing is stored) |
| **Out of scope until decided** | no `voting_ip` migration, no change to `users`, no revival of legacy IP binding, no new security authority (ADR_20261005_0950 I3; Phase 7 blocked) |
| **Decision needed** | in the security / IP-binding phase: remove the dead readers, or define IP binding anew inside the single security authority — after the anonymity-boundary assessment (ADR §7) |

**Traceability:** Phase 4 decision record §2a (`docs/publicdigit/reviews/2026-10-05-phase4-final-cast-hardening-po-decision-record.md`) · ADR_20261005_0950 §7, §8 · PBDIGIT-222.
