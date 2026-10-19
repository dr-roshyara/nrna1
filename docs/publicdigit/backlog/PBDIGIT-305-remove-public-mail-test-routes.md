# PBDIGIT-305: Remove the public mail test routes `/test/email` and `/test-email`

**Type:** Security · **Epic:** none · **Created:** 2026-10-19 · **Raised by:** Claude Code session (self-declared, not attestable), from the local Amazon SES investigation (2026-10-15). **Index row:** added 2026-10-19.

| | |
|---|---|
| **Status** | `IMPLEMENTED` · requested by the PO 2026-10-19; awaiting PO acceptance and deployment |
| **Classification** | **observed:** both routes answered HTTP 200 with no authentication (RED test, 2026-10-19) |
| **Scope** | `routes/web.php`, dead `routes/test-email.php`, generated `resources/js/ziggy.js`; regression test |

## Finding
- **`GET /test/email`** (`routes/web.php`, named `test.email`, `web` middleware only):
  - sent a mail to a fixed address;
  - logged the mail configuration;
  - returned it as JSON, including the **SMTP username**.
- **`GET /test-email`** (`web` middleware only): sent the **`SendFirstVerificationCode` notification to the first user in the database** (code `TEST123`).
- Both were reachable by anyone. With production mail moving to Amazon SES, they would let anyone send mail from `info@publicdigit.com` and consume quota.
- **`routes/test-email.php`** defined a copy of `/test-email` but was never loaded (dead file).

## Change
- Both routes are removed from `routes/web.php`; a comment points to this item.
- `routes/test-email.php` is deleted.
- The `test.email` entry is removed from `resources/js/ziggy.js`; no frontend code used it.
- The regression test `tests/Feature/Security/PublicMailTestRoutesRemovedTest.php`:
  - checks that both paths return 404 and send no mail or notification;
  - checks that the `test.email` route name is not registered.
  - It was RED (200, 200, route present) before the change and is GREEN after.

## Not changed (checked)
The other diagnostic routes `elections/{election}/debug-tenant`, `elections/{election}/auth-test` and `test/generate-slug` require authentication. `v/{vslug}/test` is limited by the voter-slug middleware. None of them send mail.

## Deployment
- Takes effect on production with the next deployment of this commit.
- If routes are cached there, run `php artisan route:cache` again.
