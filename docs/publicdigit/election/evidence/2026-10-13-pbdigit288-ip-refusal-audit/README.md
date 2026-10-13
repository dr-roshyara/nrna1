# PBDIGIT-288 item 5: every IP refusal is in the election audit, 2026-10-13

**Authority:** Human PO ("continue" = commit step 2 and start step 3; plan step 3).

## Change
- **`IpVoteLimit::recordRefusal()`** (new) writes one `ip_blocked` audit entry, category `voters`. Metadata, exactly:
  - `reason`: `limit_exceeded` or `network_busy`;
  - `step`: `start`, `code_entry`, `verification` or `vote`;
  - `max`: the limit that applied;
  - `limit_source`: `election`, `env` or `default`.
- **`IpVoteLimit::limitSource()`** (new) mirrors `limitFor()`. `globalLimit()` now reads the value through a shared parser; its behaviour is unchanged (`IpVoteLimitTest`, 18 earlier cases still pass).
- **Call sites:**
  - start (`ElectionVotingController`) replaces its old entry, whose `max` was the election setting even when that setting was off;
  - code entry (`CodeController::verifyCode`);
  - verification (`VoteController::vote_post_check`);
  - the vote (`VoteController::store`, after the rollback).
- **The IP** is still passed to the audit service as before (stored in full); retention is item 6. It is not in the metadata.

## Runs
| # | File | Result |
|---|---|---|
| 00 | `00-red-limit-source.out` | the two `limitSource()` unit tests against `IpVoteLimit` without this step's change: **2 of 2 error**. **TDD deviation, recorded:** these two tests were written after `limitSource()`. This run proves their RED retroactively (the file was temporarily replaced by its HEAD version, then restored byte-identical) |
| 01 | `01-red.out` | `IpRefusalAuditTest` before the change: **5 of 6 fail**. Start recorded `max: 4` (the disabled election setting) where the applied limit was 10; code entry, verification, the vote and the busy case wrote no entry. The 6th is a characterisation (no refusal → no entry) |
| 02 | `02-green.out` | after the change: **6 / 38, exit 0** |
| 03 | `03-database-free.out` | `IpVoteLimitTest` 20/64, `IpVoteGroupTest` 10/25, `ElectionIpWhitelistTest` 33/77: all pass |
| 04 | `04-isolated-all.out` | the isolated suite: 118 tests, 116 pass. The **2 failures are the parked PBDIGIT-284 RED proof** (`VoteAnonymityLinkageTest`, fails by design), the same 2 as before (112 tests + 6 new) |

The deprecation notices come from the framework's `config/database.php` on PHP 8.5. The isolated identity guard confirmed `pbdigit274_isolated_test`; no other database was used.

## Not in this item
- **`tests/Feature/Audit/ElectionVotingControllerAuditTest::test_start_logs_ip_blocked_event_when_limit_reached`** (default suite, not run here) builds its earlier vote on `voter_slugs.step_1_ip`. That column has not been counted since PBDIGIT-278 item 8, so the test does not reach the refusal. The guard does not allow that suite to run on the isolated database. To be revisited with the default-suite run already pending for PBDIGIT-283.
- **The constitutional limit's refusals** (trust policy) are a different limit and are not `ip_blocked` entries (`PBDIGIT-286`).
