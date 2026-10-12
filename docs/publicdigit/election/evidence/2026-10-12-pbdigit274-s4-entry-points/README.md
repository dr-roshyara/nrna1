# PBDIGIT-274 S4 (first cut): "start voting" issues links through the issuer, 2026-10-12

**Authority:** Human PO, 2026-10-12 ("start S4").

**Scope:** `pbdigit274_isolated_test` only, through the identity guard. The committing test base was used, so there was no `migrate:fresh`. No other database was touched; no password appears in any file here.

**Change:** `ElectionVotingController::start` (`POST /elections/{slug}/start`). The block that rewrote the existing row in place (new slug, `expires_at` = now + 30, **old `created_at` kept**, so the voter was locked out after 30 minutes) is replaced by `VoterSlugIssuer::fresh()->issue()`:
- REUSED / ISSUED → into the voting flow;
- ALREADY_VOTED → the election page with "You have already voted.";
- NOT_ENTITLED → the election page with the existing messages, plus one for "election not votable";
- a conflict → the election page with a neutral message.

The checks before it (standing, membership voted, lifecycle, IP) are unchanged.

| # | File | Result |
|---|---|---|
| 01 | `01-red.out` | failed for the **wrong reason**: the fixture election was not open for voting |
| 02 | `02-red.out` | failed for the wrong reason again: the lifecycle needs `voting_locked`, an open window and an approved candidacy. A fixture guard test was added |
| 03 | `03-red.out` | **real RED** for `start()`: reuse rewrote the slug; after expiry only 1 row (the lockout); a code row with `has_voted` still got a link. One failure was a test bug: the tenant-scoped membership update changed nothing; fixed with a direct table update. The `/vote` tests crashed |
| 04 | `04-green.out` | `start()` green; `/vote` answered 200 from **another** route |
| 05 | `05-green.out` | `route('vote.direct')` → "Route [vote.direct] not defined" (see the finding below) |
| 06 | `06-green-final.out` | **entry points 6 tests / 27 assertions, exit 0**; issuer 18 / 77, exit 0 |

**Covered (`tests/Isolated/Pbdigit274/Database/VoterSlugIssuanceEntryPointsTest.php`):**
- the fixture is open for voting and the voter is entitled (guards the refusal tests);
- the first start issues one link;
- starting again while valid → the same link, rows unchanged;
- **starting again after expiry → a new link, the old one kept as history, a fresh `created_at` (no lockout)**;
- a code row with `has_voted` → no link;
- an inactive voter → refused as before.

**Finding (not changed in S4):** `routes/web.php:494-496` registers `GET /vote` (`vote.dashboard`) **after** `routes/election/electionRoutes.php` (included at `web.php:443`). It replaces `GET /vote` (`vote.direct`) entirely:
- `vote.direct` is unreachable, so its `ArgumentCountError` can never happen;
- the legacy redirects to `route('vote.direct')` (`electionRoutes.php:153-165`) would throw "route not defined".

The S4 edit of that closure was reverted (the route file is unchanged), and its tests were removed. This should become a separate backlog item.

**Not run:** the existing default-suite tests that call `elections.start` (ElectionShowControllerTest, ElectionVotingControllerAuditTest, ElectionIpRestrictionTest, VotingEntitlementUnificationTest, ElectionSettingsTest, LegacyVotingFlagsBoundaryTest, IdentityVerificationAdmissionTest, SecurityPolicySurfaceCharacterizationTest). They run on `nrna_test` (forbidden). Read-only check: none asserts the old in-place rewrite by name. One behaviour change they could see: a code row with `has_voted` now refuses a link even if the membership is not marked voted.

**Still open in S4:**
- the recovery paths (`VoterSlugController::start/restart`, `GenerateRecoverySlug`, `Admin/VotingSecurityController`);
- the backlog item for the `vote.direct` shadowing.
