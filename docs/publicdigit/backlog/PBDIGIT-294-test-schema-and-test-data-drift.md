# PBDIGIT-294: Tests use database columns and settings that the current schema doesn't have

**Type:** Test-suite health · **Epic:** none · **Created:** 2026-10-14 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from the PBDIGIT-283 R6 positive runs (the first full default-suite runs on `nrna_test` since the 2026-10-13 incident). **Index row:** added 2026-10-14.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · creation approved by the Principal 2026-10-14 |
| **Classification** | **observed in test output** (counts from the error messages); root causes **not analysed** |
| **Scope** | unrelated to PBDIGIT-283 (no failing class among its files); nothing fixed |

## Finding (from the logs)
Counts are across the Unit, Feature and Architecture runs: `docs/publicdigit/election/evidence/2026-10-14-pbdigit283-r6-positive/` (`stage-2-unit.txt`, `stage-3-feature-rerun.txt`, `stage-3-architecture.txt`).
- **Undefined columns (270):**
  - `users.is_voter` (196), `users.can_vote` (14);
  - `membership_payments.currency` (16);
  - `demo_candidacies.post_name` (13);
  - `posts.post_id` / `demo_posts.post_id` (12), plus `post_id` unqualified (7);
  - `user_organisation_roles.assigned_at` (5), `organisations.is_platform` (2), `voting_code` (2).
- **MySQL-only setting on PostgreSQL (99):** `unrecognized configuration parameter "foreign_key_checks"`. Tests or helpers issue MySQL's `SET foreign_key_checks`.
- **NOT NULL violations (211):**
  - `organisation_id` on `elections` (70), `posts` (23), `users` (12), `demo_posts` (12), `voter_slugs` (10), `demo_votes` (9);
  - `user_organisation_roles.id` (33);
  - `votes.vote_hash` (13).
- **Invalid literals (113):** e.g. `invalid input syntax for type uuid: "1"`, as integer ids are used where UUIDs are required.
- **Foreign-key (12) and check-constraint (44) violations.**

## Likely meaning (to be confirmed per group)
Tests and factories written for an older schema (MySQL era, integer ids, removed voter columns), or for the time before tenant columns became mandatory.

## Proposed direction (nothing decided)
1. **Per column or setting:** decide whether the **test** is stale (update the test or factory) or the **schema** lost something intended (a separate decision, never a silent migration).
2. **Replace `SET foreign_key_checks`** with database-neutral handling.
3. **Factories set `organisation_id`** (tenant isolation is a constitutional invariant; don't relax the constraint).
4. **`votes.vote_hash`:** coordinate with `PBDIGIT-284` (vote-hash anonymity); test fixtures must not invent a linkable hash.

## Acceptance criteria
1. Each listed column or setting is classified (stale test vs. intended schema), with a decision record where the schema is concerned.
2. The affected tests pass on `nrna_test`, or are removed or retired with a reason.
3. No NOT NULL or tenant constraint is weakened to make a test pass.
4. Runs only under an authorised test-database run (the PBDIGIT-283 gates); the standing rule on `nrna_test` applies.
