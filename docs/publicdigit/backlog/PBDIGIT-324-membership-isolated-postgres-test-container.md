# PBDIGIT-324: Isolated PostgreSQL test container for the Membership PostgreSQL suite (roadmap W2)

**Type:** Story (test infrastructure, data safety) · **Epic:** `PBDIGIT-EPIC-02` · **Created:** 2026-11-02 · **Raised by:** the W2 brief of 2026-11-02, on the PO's decision "W2-1: C, prepare the W2-3 story and plan".

| | |
|---|---|
| **Status** | `APPROVED`: plan approved by the PO, 2026-11-02 (G-PLAN). **Steps 1–5 (database-free) authorised**; step 6 (container run) needs G-RUN and W2-2. Nothing implemented yet |
| **Plan** | `docs/plans/20261102-1719-membership-isolated-postgres-container-plan.md` |
| **Decision** | **W2-1 = C** (PO, 2026-11-02): P-DB is a throwaway PostgreSQL container per run (rootless podman, bound to 127.0.0.1), guarded with the existing PBDIGIT-274 / PBDIGIT-283 rules. **No second guard.** |
| **Depends on** | PBDIGIT-283 (DONE: reset guard); the PBDIGIT-274 isolated-database pattern (`phpunit.pbdigit274.xml`, `tests/bootstrap-isolated-database.php`, `DatabaseIdentityGuard`) |
| **Unblocks** | PBDIGIT-264 Part 2 (Slice 1b PostgreSQL evidence; F-5 → F-4b → F-2) → ACC-260; Slice 2b (W5); COR-4; the PBDIGIT-266 gate (ARB-SEC-01), each separately authorised |

## Business purpose

The Membership history must be proven on a real PostgreSQL database before it can be wired into the application or replace the legacy code.

On 2026-10-13 a test run wiped the development database (PBDIGIT-283). Running tests on the shared server is therefore a real risk, not a theoretical one. A throwaway database that exists only for one run, and is created fresh each time, removes the path to the shared server completely. It also makes every run reproducible.

## Scope

1. Generalise the PBDIGIT-274 identity guard so that a second suite can use it with its **own** environment-variable names and approved database name. PBDIGIT-274's behaviour must stay unchanged and stay pinned by its existing tests.
2. Add a **server-identity check**: the cluster's `system_identifier`, captured when the container starts, must match what the test connection sees. This proves the run reached the throwaway cluster, even if another server had a database with the same name.
3. Add the approved name `membership_pg_isolated` to the PBDIGIT-283 reset allow-list. It must be an exact match.
4. Add `phpunit.membership-pg.xml` and a bootstrap for `tests/Feature/Contexts/Membership/Organisational/`.
5. Add a start/run/stop script for the container: 127.0.0.1 only; a random host port; a random password per run, never printed; the image pinned by digest; removed on exit, including after a failure.
6. A fail-closed proof and the first evidence run. **Step 6 needs a separate run authorisation (G-RUN)**: pulling the image and starting the container.

## Not in scope

- Any application, schema, migration or route change.
- Changing `phpunit.xml` or the default suite.
- Fixing any failure the PostgreSQL suite reveals. Failures are reported and handled under PBDIGIT-264 Part 2 or a new item.
- PBDIGIT-266 security tests; CI integration; OQ-01 (the production migration status); reading `.env`.

## Acceptance criteria

Authoritative in the plan §6 (AC-1…AC-9).

## Open inputs

- **W2-2:** the PostgreSQL major version must match production. Ops is to confirm it, read-only.
- **G-RUN:** the PO's authorisation to pull the pinned image and start the container.
