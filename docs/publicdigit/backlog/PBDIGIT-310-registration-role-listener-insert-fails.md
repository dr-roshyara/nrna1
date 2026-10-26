# PBDIGIT-310: The registration role listener inserts a row without its `id`

**Type:** Bug · **Epic:** none · **Created:** 2026-10-26 · **Raised by:** found while analysing PBDIGIT-308.

| | |
|---|---|
| **Status** | `OPEN` · production log check pending |
| **Classification** | **observed in code;** production effect **inferred, not verified** |
| **Scope** | `app/Listeners/CreateUserOrganisationRole.php` (on `Registered`) |

## Finding
- `CreateUserOrganisationRole.php:52` inserts into `user_organisation_roles` with `DB::table()->insert([...])` and no `id`.
- That column is a UUID primary key without a default (`2026_03_05_000003_create_user_organisation_roles_table.php:12`), so the insert can never succeed.
- The `try/catch` logs the error and continues.
- `RegisterController.php:60` then creates the same row correctly through `UserOrganisationRole::create()` (the model generates the UUID). The listener therefore does nothing useful.

## Effect
- **Tests:** the failed insert aborts the PostgreSQL transaction that `RefreshDatabase` wraps each test in. Every later query fails ("current transaction is aborted"), and registration returns 500 (`RegistrationMultiTenancyTest`, `RegistrationAndLoginTest`).
- **Production (inferred):** there is no outer transaction, so registration probably succeeds, but each registration should log `CreateUserOrganisationRole: Error creating pivot entry`.
- **Risk:** if registration is ever wrapped in a transaction, this would break registration in production too.

## Verify
```bash
grep -c "CreateUserOrganisationRole: Error creating pivot entry" storage/logs/*.log   # on the production server
```

## Likely change (after verification and approval)
- Remove the listener (`RegisterController` already creates the row), or make it create the row through the model.
- Do not keep two writers.
