# PBDIGIT-307: A voter who logs in through the invitation link has no IP to capture

**Type:** Bug · **Epic:** none · **Created:** 2026-10-26 · **Raised by:** the PO, on `/organisations/overseas-nepali-forum-east-asia/elections/onf-east-asia-real-election-a3d254a0/voters/manage`.

| | |
|---|---|
| **Status** | `IMPLEMENTED` · plan `docs/plans/20261026-0805-voter-invitation-login-ip-plan.md` approved 2026-10-26; RED → GREEN; awaiting PO acceptance and deploy |
| **Classification** | **observed in production:** 109 voters · 51 logged in · only 5 with `users.current_ip` · 46 logged in with no IP |
| **Scope** | every login path; the fix applies to future logins only |

## Finding
- "Voter's IP Verification" captures `users.current_ip`, the voter's last-login IP.
- Only the email+password form writes it (`Auth/LoginController.php:71`).
- Invitation-link login (`VoterInvitationController::setPassword()`), Google, social and register logins never write it.
- Example: Siddharth Shrestha. Invitation used, email verified, `last_login_at` 2026-10-26 02:42:14, `current_ip` NULL.

## Change
- Record `current_ip` in the existing `Login` listener `RecordLastLoginTimestamp`, which already covers every login path for `last_login_at`.
- Remove the duplicate write in `LoginController`.

## Evidence
- RED: `test_voter_invitation_login_records_current_ip` failed with "null is identical to '203.0.113.7'" (validation passed, the user was logged in).
- GREEN: all 6 tests in `RecordLastLoginTimestampTest` pass.
- Related suites, with and without the change: the only difference is the new test (fail → pass). There are 27 failures in the related suites and 93 in `tests/Feature/Auth`. They exist without this change, so they are not regressions from it. They are tracked as **PBDIGIT-308**.
- Guide: `developer_guide/election/voter_admission/10_login_ip_recorded_on_every_login.md`.

## Operational
- Voters already affected must log out and log in again before their IP can be captured.
- No backfill: their past IP was never recorded.
