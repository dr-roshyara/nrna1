---
name: test_database_protocol
description: Critical rules for test database handling — never run migrate commands manually
metadata: 
  node_type: memory
  type: feedback
  originSessionId: c58c462c-c198-465e-89b3-69a6bfe193fb
---

## CRITICAL RULE: Test Database Protection

**NEVER run these commands without explicit permission:**
```bash
php artisan migrate:fresh
php artisan migrate:refresh
php artisan db:seed
```

## Why

1. **Development database is sacred** — user's session, login, test data live there
2. **Test framework handles everything** — phpunit.xml + RefreshDatabase trait manage test database state automatically
3. **Tests run in transactions** — data rolls back after each test; no cleanup needed

## The Correct Way to Run Tests

```bash
php artisan test --no-coverage
```

That's it. No other commands needed.

## Test Database Configuration

Located in `.env.testing`:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nrna_testing      # Separate test database
DB_USERNAME=postgres
DB_PASSWORD=yourpassword
```

Tests automatically use this via `phpunit.xml`:
```xml
<env name="DB_CONNECTION" value="testing"/>
<env name="APP_ENV" value="testing"/>
```

## If Test Fails Due to Database

Do NOT run migrate commands.

Instead:
1. Check the test's `setUp()` method — is it creating required data?
2. Check the test's `RefreshDatabase` trait — is it enabled?
3. Fix the test, not the database
4. Rerun: `php artisan test`

## Never This Flow

❌ Test fails → Run migrate:fresh → Development DB destroyed

## Always This Flow

✅ Test fails → Debug test setup → Fix test → Rerun test → Pass
