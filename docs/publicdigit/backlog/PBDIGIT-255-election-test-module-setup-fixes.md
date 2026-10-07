# PBDIGIT-255 — Election test module, story 1: test-setup defects

**Type:** Test debt (setup defects) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-07
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable), from the PO commission "fix the failing election tests, TDD first approach"
**Plan:** `docs/plans/20261007-0006-election-test-module-green-plan.md` (stories 1–5)

| | |
|---|---|
| **Status** | 🟢 **RESOLVED** 2026-10-07: 9 failing tests fixed by correcting their setup; no assertion changed |
| **Baseline** | `tests/Feature/Election`: 83 failed / 704 passed; all 83 pre-existing (0 new vs `7ae825d1e`) |
| **Rule** | Setup fixes only. An expectation is never changed to make a test pass. |

## Fixes

| File | Tests fixed | Root cause | Fix |
|---|---|---|---|
| `tests/Feature/Election/ElectionOnlyEntitlementPinTest.php` | 5 of 6 | `setUp` inserted the user's organisation role a second time (`$org->users()->attach`). `UserFactory::afterCreating` already `firstOrCreate`s that `UNIQUE(user_id, organisation_id)` row, so the setup failed with a unique violation. | Idempotent `UserOrganisationRole::updateOrCreate` (same intent: the voter is an org member with role `voter`) |
| `tests/Feature/Election/Security/SecuritySchemaTest.php` | 4 of 4 (file 9/9) | A redundant `use RefreshDatabase` re-imported the trait's `beginDatabaseTransaction()`, overriding `Tests\TestCase`'s PostgreSQL skip. The election stayed uncommitted and the separate `pgsql_audit` connection (`ElectionSecurityEvent`, ADR_20260806_1340) could not see it: an FK violation. | Remove the redeclaration (the convention of `SecurityEventRecorderTest`, `AuditEventTransactionIsolationTest`) |

## Moved to later stories (not setup defects)
- **`ElectionOnlyEntitlementPinTest::…passes the ballot gate`** now reaches its assertion: 302, because `Election::factory()` defaults derive a **Draft** election. Category B, story 2.
- **`TimelineEditing\TimelineCapabilityAuthorizationTest` (8)** relies on things that no longer exist:
  - the permission `manage_elections`;
  - the factory state `inDraftState()`;
  - a `state` column.

  The endpoint authorises `manageSettings` (election Chief / Deputy) + `ElectionLifecycle::canEditTimeline()`. Category C, story 3.

**Traceability:** plan above · `Tests\TestCase::beginDatabaseTransaction` · `UserFactory::afterCreating`.
