# 10 — The last-login IP is recorded on every login (PBDIGIT-307)

**Story:** PBDIGIT-307 · **Commit:** this step's commit · **Plan:** `docs/plans/20261026-0805-voter-invitation-login-ip-plan.md`

## Purpose

"Capture IP Address" (guide 06) stores the voter's last-login IP, which the server reads from `users.current_ip`. Until PBDIGIT-307 only the email+password form wrote that column. A voter who signed in through the **invitation link** (or Google, social login or registration) had `current_ip = NULL`, so the officer had nothing to capture. In production, 46 of 51 voters who had logged in had no IP.

## Where it fits

Infrastructure: a Laravel event listener on `Illuminate\Auth\Events\Login`. No domain or application code changes, and no migration (`current_ip` already exists and is in `User::$fillable`).

## Key files

| File | Role |
|---|---|
| `app/Listeners/RecordLastLoginTimestamp.php` | the **only** writer of `users.current_ip` (and of `last_login_at`) |
| `app/Providers/EventServiceProvider.php` | `Login::class => [RecordLastLoginTimestamp::class]` (unchanged) |
| `app/Http/Controllers/Auth/LoginController.php` | no longer writes `current_ip` itself |

## How it works

Laravel's `SessionGuard` fires `Login` for every successful login: `Auth::attempt()`, `Auth::login()` (invitation link, Google, social, register) and a "remember me" cookie sign-in. The listener records both values in one query:

```php
public function handle(Login $event): void
{
    $event->user->updateQuietly(['last_login_at' => now(), 'current_ip' => request()->ip()]);
}
```

- The listener is synchronous (not `ShouldQueue`), so `request()->ip()` is the IP of the login request. It goes through TrustProxies like every `$request->ip()` (see `developer_guide/election/ip_voting/05_trusted_proxy.md`).
- A failed login fires no `Login` event, so nothing is written.
- `updateQuietly()` fires no model events. There are no `User` observers.

## Design decisions

- **One writer, on the framework event.** This is the same rule `last_login_at` already follows. A per-controller write is what caused the bug: each new login path had to remember to add it.
- **Extend the existing listener, no new `RecordLoginIp` class.** Both values describe the same moment. No new class means no event-cache rebuild (`bootstrap/cache/events.php`) on deploy.
- **"Remember me" refreshes the IP.** That keeps `current_ip` the most recent login IP, which is what the capture means.
- **Anonymity (ADR-T11):** the IP lives on `users` only. It is never written to `votes`, `results` or anything that can be linked to a vote.

## Operations

- The fix applies to **future** logins only. Voters who logged in without an IP must log out and log in again before their IP can be captured.
- No backfill is possible, because the past IP was never stored.
- Run `php artisan optimize` on deploy as usual. No listener-cache change is needed.

## Extending

A new login path needs nothing extra, as long as it logs in through the guard (`Auth::login()` / `Auth::attempt()`). **Do not** add `current_ip` writes in controllers again.

## Testing

`tests/Feature/Auth/RecordLastLoginTimestampTest.php`:

| Test | Covers |
|---|---|
| `test_voter_invitation_login_records_current_ip` | the bug: `POST invitation.store-password` from `203.0.113.7` records it (RED before the fix) |
| `test_password_login_records_current_ip` | the password path still records it after the controller write was removed |
| `test_failed_login_does_not_record_current_ip` | a wrong password leaves the old IP |
| `test_latest_login_ip_replaces_the_previous_one` | login from A, then B → B |

Set the request IP with `withServerVariables(['REMOTE_ADDR' => '…'])`.

## Pitfalls

- `actingAs()` in tests does **not** fire `Login`, so it never sets `current_ip`. Use `forceFill(['current_ip' => …])` as `VoterIpCaptureTest` does.
- A login fired outside an HTTP request (console) records `127.0.0.1`.

**Traceability:** PBDIGIT-307 · PBDIGIT-222 (capture reads `current_ip`, guide 06) · PBDIGIT-247 (IP binding, unchanged) · ADR-T11.
