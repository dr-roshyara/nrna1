# 12: Outage and restore, PBDIGIT-345 task 12

**Design:** D-178 §13.6 (fail closed: the centre is needed for every new request and job start; health is not a status); routing tactical design failure rows 7 and 10, §3.9 (one cluster, a shared failure domain, D-219 (4)).
**Proofs:**
- **T-11 and T-18: proven (12a).**
- **T-17: the restore drill awaits the Principal's decision** (see the end of this guide).

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

## T-17 (restore drill): pending decision

See the task 12 report.
- **The locator is immutable** (written only by provisioning; D-193 scenario 9; `LocatorAlreadyAssigned`).
- So "a database restored to a **scratch** database routes" needs either an **in-place** restore (the organisation's own locator) or a new **relocation** transition.
- That is a decision for the Principal, not an implementation choice.

**Traceability:** PBDIGIT-345 task 12 · D-178 §13.6 · D-219 (4) · T-11, T-18 (T-17 pending).
