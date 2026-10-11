# PBDIGIT-277: Voter-slug correctness defects (missing column, missing method, type checks)

**Type:** Defect story · **Epic:** none (voting) · **Created:** 2026-10-11 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), split from `PBDIGIT-276` on the Principal Reviewer's triage instruction. **Index row:** added 2026-10-11.

| | |
|---|---|
| **Status** | `OPEN` · not authorised |

| # | Defect | Evidence (2026-10-11) | Reproducible? |
|---|---|---|---|
| 1 | `vote_completed` is read but no such column exists (the column is `vote_completed_at`), so it is always null. The "already voted → verify_to_show" redirect in `VerifyVoterSlugConsistency` and three `VoterSlugController` branches never fire | reads at `VerifyVoterSlugConsistency.php:105`, `VoterSlugController.php:67,186,283`; `grep` over `database/migrations` finds no `vote_completed` column | **statically confirmed**; runtime impact to show with a test |
| 2 | `VoterRecoveryService` calls `isExpired()` on slugs (`:51, :97, :164`). `VoterSlug` defines no `isExpired()`; only `DemoVoterSlug` does (`:110`) | `grep` | **statically confirmed**: a `BadMethodCallException` on a `VoterSlug` if reached; reachability to verify |
| 3 | demo type is checked inconsistently: `Election::isDemo()` is strict (`type === 'demo'`), `VoterSlugService::getOrCreateSlug:275` uses `trim(type) === 'demo'` | code | latent, data-dependent; verify whether any stored `type` has whitespace |
| 4 | recovery slugs: `VotingSecurityController:216` → `generateSlugForUser` without an election id can create a `VoterSlug` for a demo election. Since `PBDIGIT-273` such a slug is rejected (V-6) | independent review | **unverified** |

## Acceptance
Each item gets a RED test first, on an isolated test database where the item needs one, and then a minimal fix. Items that turn out not reproducible are closed with their evidence.
