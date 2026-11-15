# 12: Outage and restore, PBDIGIT-345 task 12

**Design:** D-178 §13.6 (fail closed: the centre is needed for every new request and job start; health is not a status); routing tactical design failure rows 7 and 10, §3.9 (one cluster, a shared failure domain, D-219 (4)).
**Proofs:** T-11 and T-18 (12a); T-17 (12b, D-234).

**Evidence:** `docs/publicdigit/platform/evidence/2026-11-15-pbdigit345-task12/`.

## Business purpose

**When PublicDigit cannot prove an organisation can be served safely, it refuses.** It never guesses, never falls back to another database, and never uses a connection from before the problem. **When the problem is fixed, work resumes through normal routing.**

## What happens

| Outage | Request (E-1) | Job (E-2) |
|---|---|---|
| **Registry unreachable** | **503**, selection kept (D-178 §13.6) | not run; the attempt throws, so the job is **released for a retry** (never failed for an outage while it has tries left) |
| **One organisation database unreachable** | **503** for that organisation only | released; other organisations' jobs run normally |
| **Cluster unreachable** | **503** for every organisation; selecting an organisation is **503** too (it needs the registry) | every job waits |
| **After recovery** | normal routing; a **fresh** resolution and a **fresh** connection | the retried attempt resolves again and runs on a new connection |

**Nothing survives an outage:**
- the per-request and per-attempt context is always released;
- **a failed connection attempt leaves no registered connection behind** (fixed in this task in `OrganisationConnectionFactory::establish`).

## Fixed in 12a (found by the tests)

1. **`OrganisationConnectionFactory::establish`:** a database that cannot be reached left its `org_<id>` connection registered. Now it is forgotten before the error propagates.
2. **E-1 (`OrganisationEntry`, `SelectOrganisationController`):** a refused connection can surface as a **raw `PDOException`** (Laravel's reconnect path), which gave a **500**. Both now answer **503**, and the selection is kept.

## How the outages are made real in tests (`OutageAndRecoveryTest`)

| Outage | Mechanism (no mocks) |
|---|---|
| registry down | the registry served over a connection to **port 1** (nothing listens: a TCP refusal) |
| one database down | `ALTER DATABASE … WITH ALLOW_CONNECTIONS false` plus `pg_terminate_backend` (PostgreSQL itself refuses) |
| cluster unreachable | the registry **and** the cluster address on port 1 |

**One long-running worker** (this test process; `queue:work --once` repeatedly) processes A, survives the outage, and then processes A and B with **new** PostgreSQL backends.

```
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite PlatformPostgres --filter OutageAndRecoveryTest
```

**Job authors:** give organisation jobs `$tries` greater than 1 (and a backoff), so an outage is retried rather than failed. The relay (guide 09) and fan-outs (guide 08) are re-dispatched on every run anyway.

## T-17: the restore drill and in-place recovery (12b; D-234 Option A)

**The decision (D-234):**
- the locator stays **immutable** (D-193 scenario 9; `LocatorAlreadyAssigned`), so there is no relocation;
- a restored copy is **never routable**;
- an organisation routes again after a restore **only** through `migration-repair`'s identity and L-1 checks.

### Two new technical operations (operator channel E-4, joining grant; guide 11)

| Operation | Effect | Registry |
|---|---|---|
| `restore-drill` | back up → restore into `scratch_restore_<random>` → L-1 facts **on the copy** → drop the copy and delete the backup | **untouched** (`row_version` unchanged) |
| `take-out-for-restore` | `SchemaState` → `migration-failed`: unroutable; `PlatformStatus` kept | SchemaState only |

**Refusals:** both need the joining grant (`no-joining-grant`) and a database (`no-database`). Anything else is still `case-grant-required`.

### Code
- **Port** `Application/Ports/OrganisationRestoreDrill::rehearse(TenantId): OrganisationDatabaseFacts`. It throws `ProvisioningFailed` when the dump or restore itself fails.
- **`OperateTechnically`:** `restore-drill` gives `succeeded`, `failed: restore-not-verified` (facts not all true) or `failed: restore-failed`, each recorded and reported. `take-out-for-restore` calls `markMigrationFailed()`.
- **Adapter `Infrastructure/PgDumpRestoreDrill`:**
  - uses PostgreSQL's `pg_dump -Fc` and `pg_restore --no-owner --exit-on-error`;
  - the backup file is owner-only;
  - the scratch database is created and **always** dropped (`DROP DATABASE … WITH (FORCE)` in `finally`);
  - the password reaches the tools only through `PGPASSWORD` and is redacted from error messages.
- **`PostgresOrganisationReadinessProbe::factsOn(Connection, TenantId)`:** the same L-1 facts as `inspect`, read on any connection. The drill reads them on the copy.
- **`OrganisationConnectionFactory::parameters(DatabaseLocator)`:** connection parameters for Platform's own external tools. It is never logged and never given to another context.
- **Config `organisation_routing.restore_drill`:** `directory`, `tool_prefix`, `tool_host`, `tool_port`.
  - The client tools **must match the server's major version**. A newer `pg_dump` emits `SET transaction_timeout`, which PostgreSQL 15 rejects (found in this task).
  - The harness exports `MEMBERSHIP_PG_CONTAINER` so the tests use the cluster's own tools.

### Proofs (`RestoreDrillTest`, real PostgreSQL 15 and its own tools)

| T-17 criterion | Test |
|---|---|
| a reproducible backup is restored into an isolated scratch database; identity and L-1 verified on it; it is never routable; it and the backup are removed; registry and locator unchanged | `the_drill_verifies_a_restored_copy_that_is_never_routable_and_touches_nothing` |
| a backup that does not verify fails the drill and changes nothing | `a_backup_that_does_not_verify_fails_the_drill_and_changes_nothing` |
| the drill judges **the copy**, not the live database (a copy corrupted in transit fails while A is healthy) | `the_drill_judges_the_restored_copy_not_the_live_database` |
| in-place recovery: take out (unroutable, status kept) → restore into A's own database (**still unroutable**) → `migration-repair` → routable; locator unchanged; A's data back; B routable and untouched throughout | `the_in_place_recovery_procedure_returns_a_only_through_repair_and_leaves_b_untouched` |
| a wrong backup (B's) restored into A: the repair refuses; A stays unroutable | `a_wrong_backup_restored_in_place_is_refused_and_a_stays_unroutable` |

### Limitations
- **No backup schedule, retention or off-site storage:** operational decisions for the Principal, before production.
- **A killed drill can leave a `scratch_restore_*` database behind.** It is never routable, but it holds a copy of the data: the administrator drops it (admin notes).
- **The drill restores on the same cluster as the organisation:** it proves the backup, not a different host.

**Traceability:** PBDIGIT-345 task 12 · D-178 §13.6 · D-219 (4) · D-234 · T-11, T-17, T-18.
