# 90: Administrator notes: Platform routing (PBDIGIT-345)

**A living page, updated per task.**

## Task 2: the Platform registry (2026-11-13)

| Question | Answer |
|---|---|
| New database migration? | **Yes, one, on the CENTRAL database:** `database/migrations/2026_11_13_170000_create_platform_served_organisations.php` creates the empty table `platform_served_organisations` |
| Data migrated? | **No.** The table starts empty. **Legacy organisations are never copied in** (L-2: legacy cutover is out of scope) |
| New configuration or secret? | **No.** The registry stores a credential *reference* (e.g. `secret-store:…`), never a password. Where those references resolve is task 3 |
| Provider | `App\Contexts\Platform\Infrastructure\Providers\PlatformServiceProvider` is registered in `config/app.php` |
| User-visible change? | **None.** Nothing in HTTP, jobs or the legacy tenant path uses the registry yet |

**Deploying it:**
1. **Development:** the normal `php artisan migrate` creates the empty table. **Never `migrate:fresh` or `migrate:refresh` on a development database** (project rule).
2. **Production: don't run it before PBDIGIT-345 is accepted by the Principal.** The story ships as one routing capability; an empty table alone gives nothing. When it is accepted, run the normal migration and take a backup first.
3. **Rollback:** the migration's `down()` drops the table. It holds no data until organisations are founded (task 5).

**The G-4 production restrictions (Reference Geography) are unrelated to this table, and still apply.**
