# PBDIGIT-291: An outbox adapter test passes on any database failure, so it proves little

**Type:** Test quality (Adjudication outbox) · **Epic:** none · **Created:** 2026-10-14 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from PBDIGIT-283 R4. **Index row:** added 2026-10-14.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · creation approved by the Principal 2026-10-14 |
| **Classification** | **verified in source** (read-only); the test passes under the PBDIGIT-283 marker (dead socket) |
| **Scope** | **outside PBDIGIT-283.** R4 changed only how the test boots Laravel (through `Tests\CreatesApplication` as a `DatabaseFreeTest`); this item is about what the test asserts |

## Finding (verified in source)
`tests/Unit/Contexts/Adjudication/Infrastructure/Outbox/OutboxEventAdapterTest.php::test_enqueue_does_not_throw_tenant_exception_when_context_is_set`:
- sets a tenant (`'org-live-999'`, not a UUID) and calls `OutboxEventAdapter::enqueue()`, which attempts a real `save()` (an INSERT into the outbox table);
- catches **any** `RuntimeException`, asserting only that its message isn't "Tenant context not set", and **any other `Throwable` silently**;
- then `addToAssertionCount(1)`.

**So it passes whether the INSERT succeeds, fails on the invalid UUID, fails on a missing table, or fails to connect.** It only proves that one specific message doesn't appear.

**History:**
- **Before PBDIGIT-283 R4** the class booted Laravel itself (`require bootstrap/app.php`), bypassing the PBDIGIT-283 protections. The INSERT went to whatever database was configured: `nrna_test` under `phpunit.xml`, or the `.env` database under `--no-configuration`.
- **Since R4** it boots as a database-free test, so the INSERT fails at a non-existent socket, and the test still passes.

## Intended behaviour vs. tolerated behaviour
- **Intended (what the case is named for):** with a tenant set, `enqueue()` does **not** fail with "Tenant context not set". That is the S7 regression guard for `TenantContext::require()`, and the case does check it.
- **Tolerated (what it silently accepts):** **every** other outcome of the persistence step, success or any failure (invalid UUID, missing table or column, constraint violation, no connection).
- **So the case gives no evidence that an outbox row is persisted, or persisted with the right `organisation_id`.** It must not be cited as persistence evidence.

## Impact
- **Weak regression protection on the set-tenant path:** a defect after the tenant check (e.g. a wrong or empty `organisation_id`, a broken payload) would go unnoticed.
- **No database impact now** (R4: database-free boot).

## Proposed resolution (test-side only; nothing decided)
**Scope:** the outbox **test's** assertion quality and persistence evidence. No change to `OutboxEventAdapter` or the outbox design.

Options:
1. **Split the case.**
   - **The database-free unit case** keeps only the intended assertion, stated honestly: no "Tenant context not set". It no longer suggests anything about persistence.
   - **A database-backed case** (`Tests\TestCase`, run only under an authorised test-database run) asserts the persisted outbox row.
2. **If option 1 isn't approved:** narrow the unit case's `catch` so only database-connectivity failures are tolerated, and rename the case to say what it proves.

## Acceptance criteria
1. **No test claims persistence it doesn't verify:** the database-free case's name and docblock state only the tenant-check guarantee.
2. **Persistence evidence, if kept:** a database-backed test, with a valid tenant UUID, asserts that exactly one outbox row exists for the enqueued event, with `organisation_id` equal to the set tenant and the expected event type.
3. **That test fails if** `organisation_id` is empty or differs from the tenant, which is shown RED first.
4. **No `catch (\Throwable)` that can turn an unrelated failure into a pass** remains in the set-tenant case.
5. **The database-backed case runs only under an authorised test-database run** (PBDIGIT-283 gates), never under the database-free command.
6. **No production change** to `OutboxEventAdapter` within this item.

## Related
- `PBDIGIT-283` R4: `tests/Architecture/LaravelBootIsGuardedTest.php`; guide `developer_guide/testing/04_laravel_boot_coverage.md`.
