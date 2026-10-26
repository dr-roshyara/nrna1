# PBDIGIT-307 — Voter IP not recorded on invitation-link login — plan

**Status:** IMPLEMENTED — approved by the Human 2026-10-26; RED → GREEN; awaiting PO acceptance (EP-02).
**Story:** `docs/publicdigit/backlog/PBDIGIT-307-voter-invitation-login-ip-not-recorded.md` (307 confirmed free; the highest existing story was 306).

## Objective
Every successful login records the voter's IP in `users.current_ip`, so the "Voter's IP Verification" capture on `/organisations/{org}/elections/{election}/voters/manage` has an IP for voters who signed in through their invitation link.

## EP-03 readiness (derived from the repository)
| Question | Answer |
|---|---|
| Business | An officer can capture a voter's last-login IP for every voter who has logged in, whichever login path the voter used. |
| Owner (DDD) | Identity/Auth: a fact about the login, stored on `users`. Election (IP Verification) only reads it; ownership does not change; the context map does not change. |
| Invariant | ADR-T11 anonymity: the IP lives on `users` only and is never written to `votes`, `results` or anything that can be linked to a vote. Unchanged. |
| Existing mechanism | `Illuminate\Auth\Events\Login` → `RecordLastLoginTimestamp` (`EventServiceProvider.php:40-42`), which already covers every login path for `last_login_at`. Reused rather than adding a second mechanism. |
| Migration | None. `users.current_ip` exists and is in `User::$fillable`. |

## Root cause (verified 2026-10-26)
- The only writer of `current_ip` in `app/` is `Auth/LoginController.php:70-71` (email+password form).
- `VoterInvitationController::setPassword()` (`:54-62`) calls `Auth::login($user)` but never writes it. The same gap exists for Google, social and register logins.
- Production: 46 of the 51 voters who logged in have no IP (e.g. Siddharth Shrestha, `last_login_at` 2026-10-26 02:42:14, `current_ip` NULL).

## Design decision
Extend `RecordLastLoginTimestamp` to write `current_ip` too. **No** separate `RecordLoginIp` listener:
- Both values describe the same moment (the login), so one query writes both.
- No new listener class means `bootstrap/cache/events.php` does not have to be rebuilt.
- The class name becomes narrower than what the class does. The docblock will say it records both values; renaming is out of scope.

The listener is synchronous (not `ShouldQueue`), so `request()->ip()` is the login request's IP, the same value `LoginController` reads today. There are no `User` observers, so switching that write from `update()` to `updateQuietly()` does not affect anything.

## Scope
1. `app/Listeners/RecordLastLoginTimestamp.php`: `updateQuietly(['last_login_at' => now(), 'current_ip' => request()->ip()])`, plus the docblock update.
2. `app/Http/Controllers/Auth/LoginController.php:70-71`: remove the duplicate write so there is one writer. Nothing else in that file changes.

**Do not touch:** `VoterIpCaptureController`, `ElectionVoterController`, `Voters/Index.vue`, `voter_ip_verifications`, IP Binding (PBDIGIT-247), the Security Verdict Authority.

## TDD — extend `tests/Feature/Auth/RecordLastLoginTimestampTest.php` (match its style)
1. **RED, invitation path:** user + valid `VoterInvitation` (election, organisation); `POST route('invitation.store-password', token)` with `REMOTE_ADDR=203.0.113.7` and a password `setPassword()` accepts (`password` + `password_confirmation` = `Secret123`: min 8, mixed case, numbers) → assert no validation errors, then `current_ip === '203.0.113.7'`. Must fail before the fix, on the missing IP and not on validation.
2. **Password login still works:** `POST /login` from a set IP → `current_ip` equals that IP.
3. **Failed login does not write:** wrong password → `current_ip` unchanged.
4. **Latest login wins:** log in from IP A, then from IP B → `current_ip === B`.

GREEN, then: `php artisan test --filter='RecordLastLoginTimestamp|VoterInvitationSetPassword|VoterIpCapture|SingleElectionLoginFlow|ElectionVoter'` (output pasted into the result). `php artisan test` only, never `migrate:fresh`/`refresh` or seeders.

## Deployment note
No new listener class, so the listener cache needs no rebuild, but run `php artisan optimize` on deploy anyway.

## Operational note (for the election officer)
The fix applies to **future** logins only. The 46 voters who already logged in without an IP must log out and log in again before their IP can be captured. No backfill is possible: their past IP was never recorded.
Side effect (intended): a "remember me" automatic sign-in also fires `Login`, so it refreshes `current_ip` to the most recent IP, which is what the "last-login IP" capture expects.

## Task checklist
- [x] EP-03 readiness · root cause verified · story number checked
- [x] Backlog item PBDIGIT-307 created
- [x] **Human approval of this plan** (2026-10-26)
- [x] RED (tests 1–4; test 1 fails on the missing IP)
- [x] GREEN (listener + controller line removed)
- [x] Related suites: no new failure. **Deviation:** they do not "pass". 27 failures (related suites) and 93 (`tests/Feature/Auth`) exist without this change and are the same with and without it, so they are not regressions from it → PBDIGIT-308
- [x] Developer guide step. **Deviation:** placed in `developer_guide/election/voter_admission/10_…` (the area that already documents the IP capture, guide 06) instead of `ip_voting/`
- [x] Session log `.claude/sessions/2026-10-26.md` · `.claude/CONTEXT-publicdigit.md`
- [x] One commit, subject contains `(PBDIGIT-307)`

## Risks
- Behind a proxy, `request()->ip()` depends on TrustProxies, exactly as today. No change.
- A login fired from console code or in tests records `127.0.0.1`. Harmless.

## Progress
- 2026-10-26: readiness and root cause done; plan and story written; waiting for approval.
- 2026-10-26 (review): test 1 uses a password that passes validation; `RecordLoginIp` (never created) removed from the filter and replaced by `VoterInvitationSetPassword`, the existing suite for the invitation route; plan renamed to its real creation time (0805). The story file exists (written 08:05).
- 2026-10-26: approved; RED → GREEN; guide, PBDIGIT-308, session log and CONTEXT written; committed. The test run required moving the untracked dev config cache `bootstrap/cache/config.php` aside (the PBDIGIT-283 guard refuses a cached config); it was restored afterwards.
