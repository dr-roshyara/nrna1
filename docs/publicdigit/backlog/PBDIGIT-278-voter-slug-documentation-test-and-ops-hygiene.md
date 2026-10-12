# PBDIGIT-278: Voter-slug documentation, test and operations hygiene

**Type:** Hygiene story (no production behaviour change expected) · **Epic:** none (voting) · **Created:** 2026-10-11 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), split from `PBDIGIT-276` on the Principal Reviewer's triage instruction. **Index row:** added 2026-10-11.

| | |
|---|---|
| **Status** | `OPEN` · not authorised |

| # | Item | Kind | Evidence |
|---|---|---|---|
| 1 | stale route comment at `routes/election/electionRoutes.php:485-496` ("expired slugs allowed so CodeController can extend them") contradicts the Human PO decision of 2026-10-11 | documentation | code |
| 2 | test route `GET v/{vslug}/test` (`test.voter.page`, `electionRoutes.php:400`) has `voter.slug.window` but no `voter.slug.verify`, so it always fails. Remove it or guard it. A `PBDIGIT-273` test pins the exception | dead code | route table (verified 2026-10-11) |
| 3 | `VoterSlugFactory` sets no `election_id` / `organisation_id`, and `expired()` also sets `is_active = false`, mixing two conditions | test infrastructure | factory code |
| 4 | `voting:clean-expired-slugs` (`CleanupExpiredVoterSlugs`) is not scheduled: no reference in `routes/console.php` | operations | grep |
| 5 | `tests/Unit/Services/Voting/VoterSlugRouteLookupTest.php` (S1) calls the real `{vslug}` binding with the clock frozen at 2026-10-11 12:00. The binding's rate limiter writes to the **configured** cache store, and against the frozen clock its 60 s window never expires, so hits pile up across runs. After about 15 the two binding tests fail with 429 "Too many attempts". Fix: pin an array cache store (or clear the limiter key) in that test. Added 2026-10-13 during PBDIGIT-274 S7 | test infrastructure | `docs/publicdigit/election/evidence/2026-10-13-pbdigit274-s7-expiry-routing/04a-*` (fails), `04b-*` (green with `CACHE_STORE=array`) |
| 6 | Legacy slug creation `VoterSlugService::generateSlugForUser` has two callers, both inert today: `GET test/generate-slug` (`test.generate-slug`, `routes/election/electionRoutes.php:385-389`, any authenticated user) builds `new VoterSlugService()` without its required `DemoElectionResolver`, so it always fails with a 500; `VoterRecoveryService` has no caller. **If either were repaired** it would issue a link for the session's `selected_election_id`, possibly a real election, bypassing `VoterSlugIssuer` (entitlement, history). Remove both. Added 2026-10-13 during PBDIGIT-274 S6 | dead code / security | route table, grep |
| 7 | Dead duration readers (found in S6): `CodeVerificationTrait::hasVotingWindowExpired` is overridden by `DemoVoteController`'s own private method; `VoteController` / `DemoVoteController` `second_submission` (unrouted), and their `validateVotingEligibility`, `prepareSessionData`, `send_second_voting_code` (calls commented out) have no live caller. The `column ?? config` fallback in `CheckVotingWindow` and the code readers never runs for stored codes: `codes` / `demo_codes.voting_time_in_minutes` is NOT NULL, default 30 | dead code | grep, schema |

**Refuted during triage (not a defect):** "`tests/Feature/MiddlewareChainTest.php` targets a non-existent path". `/v/{slug}/demo-code/create` **does** match `slug.demo-code.create` (route-table probe, 2026-10-11). Whether that test's fixtures, a real `VoterSlug` on a demo route, assert what they claim is a separate, unverified question.
