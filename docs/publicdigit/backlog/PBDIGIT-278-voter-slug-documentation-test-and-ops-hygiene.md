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

**Refuted during triage (not a defect):** "`tests/Feature/MiddlewareChainTest.php` targets a non-existent path". `/v/{slug}/demo-code/create` **does** match `slug.demo-code.create` (route-table probe, 2026-10-11). Whether that test's fixtures, a real `VoterSlug` on a demo route, assert what they claim is a separate, unverified question.
