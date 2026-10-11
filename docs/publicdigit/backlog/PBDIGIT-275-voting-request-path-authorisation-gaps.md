# PBDIGIT-275: Voting request path — authorisation gaps around the slug guard

**Type:** Security story · **Epic:** none (voting) · **Created:** 2026-10-11 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from the `PBDIGIT-273` investigation; out of scope there by Human PO decision. **Index row:** added 2026-10-11.

| | |
|---|---|
| **Status** | `OPEN` · not authorised |

## Findings (exploration of 2026-10-11, file:line)
- **No `auth` middleware on the real `{vslug}` groups** (`routes/election/electionRoutes.php:497, 514`). Since `PBDIGIT-273`, `VerifyVoterSlug` rejects guests. But the `{vslug}` binding runs earlier and answers 404 or 410 to guests, which lets them probe whether a slug exists. The custom middleware priority (`bootstrap/app.php:128-132`) no longer forces `Authenticate` before `SubstituteBindings`.
- **Demo-session bypass:** `VoteEligibility.php:145-148` skips `canVote()` for **any** route once `session('selected_election_type') === 'demo'`, a value written by `EnsureDemoElection.php:41`. On real step 1 that is the only `canVote()` gate (V-8).
- **`VerifyVoterSlugConsistency`:**
  - the type check is one-way (lines 200-218);
  - the dead `organisation_id === 1` shortcut (lines 174-179);
  - mismatches are reported as **HTTP 500** (`ElectionMismatchException`, `OrganisationMismatchException`), which is a client-side condition labelled as a server error.
- **Demo steps are missing from `config/election_steps.php`,** so `EnsureVoterStepOrder` doesn't order demo steps (V-7).
- **`EnsureRealVoteOrganisation`** claims to check the organisation but only checks that a slug is present; `handleOrganisationMismatch()` is never called.
- **The legacy `POST /votes` route** (`vote.store`, `electionRoutes.php:169`). Verified 2026-10-11, read-only, from the registered route table and the code:
  - **It is reachable.** It is registered as `POST /votes` with `[web, auth:sanctum, verified, vote.eligibility]`, and **no slug middleware**, so `VerifyVoterSlug`, the slug window and the step-order checks don't run.
  - **It dispatches to the same `VoteController::store` as the slug flow.** Without a `voter_slug` attribute, `getElection()` (`VoteController.php:97-123`) falls back to the session election, then to `Election::where('type','real')->first()`, then to any demo election.
  - **A cast is still gated** inside `store`:
    - `VotingAuthorization::forCast` re-checks entitlement, uncached;
    - the voter's code must exist, match the submitted `voting_code` and have its code step completed;
    - a ballot must be in the session;
    - `ElectionLifecycle::canVote()` and the security assessment apply.
  - **The final cast checks no slug or code expiry** (`CastAttempt` has no time field).
  - **Hypothesis (UNVERIFIED):** a voter who completed the code step and holds a session ballot could cast through `/votes` after their slug expired, bypassing the 30-minute rule. That would be a lifetime bypass, not an entitlement bypass.
  - **Severity is undecided until** a request-path test on an isolated test database confirms or refutes this. It is not classified as an exploitable bypass.
