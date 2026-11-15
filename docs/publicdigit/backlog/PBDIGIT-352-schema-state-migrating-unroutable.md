# PBDIGIT-352: `SchemaState::Migrating`, unroutable while an organisation is migrated (D-193 B-7, M-8)

**Status:** OPEN (created 2026-11-15) · **prerequisite** for any command that migrates operational organisations · not authorised · **Context:** Platform · **Kind:** missing state (accepted requirement)
**Found by:** PBDIGIT-345 task 13 (`docs/publicdigit/platform/evidence/2026-11-15-pbdigit345-task13/01-d193-proof-obligation-account.md`, B-7).

## Finding

D-193 B-7 and revision 4 M-8 (multi-database design conformance note) require `schemaState = migrating` to be **unroutable** while it lasts. `SchemaState` has only `current` and `migration-failed`.

## Business impact

**None today.** Migrations run only while `provisioning` (unroutable) or as a repair from `migration-failed` (unroutable). **No command yet migrates an operational organisation** (that also waits for the migration-approval control).

**Once one exists, without `migrating`, members could be served by a database half-way through a schema change.**

## Scope (when authorised; test first)

- `SchemaState::Migrating` in the domain (unroutable, PlatformStatus unchanged);
- the registry CHECKs;
- set before and cleared after a rollout migration, in the provisioning process only;
- a crash while `migrating` leaves it unroutable until repair;
- T-2-style routing tests.

**Traceability:** D-193 register B-7 · revision 4 M-8 · D-178 §13.6 · PBDIGIT-345 task 13.
