# 11: The operator channel E-4 (technical recovery, no business content), PBDIGIT-345 task 11

**Design:** routing tactical design §3.8 and E-4; D-166 (1), D-219 (3); ISO-8, ISO-11. **Proof:** T-16.
**Evidence:** `docs/publicdigit/platform/evidence/2026-11-14-pbdigit345-task11/`.

## Business purpose

**When an organisation's database needs technical repair** (in release 1: a failed migration), platform staff can repair it **without ever seeing the organisation's business records.**
- **Every operation is recorded centrally first, and reported to the organisation itself.**
- **The repair never makes an organisation usable on its own.** Its readiness is re-checked, and its status never changes.
- **Looking at business content** (members, posts, decisions) would need a per-case grant from the organisation (D-166 (2)). That is **not offered in release 1** (D-219 (3)), so **any such request is refused.**

## Authority

**The authority is the organisation's joining grant:** the service-operation grant recorded in the registry at founding (D-166 (1); guide 05).
- No grant (a pending entry) or an unknown organisation → **refused**.
- **There is no operator identity in release 1.** D-193's `PlatformOperator` belongs to the deferred case-grant sessions.
- **The command records who ran it** (`--operator`, required); this is a record and grants nothing. The command runs on the server, like provisioning.

## Components

| Layer | Component | Role |
|---|---|---|
| Application | `OperateTechnically::perform(organisation, operation, operator)` | record first, then refuse or execute, then record the outcome and report it |
| Application | `TechnicalOperationRecord`, `TechnicalOperationOutcome` | identifiers and outcomes only |
| Port → Infrastructure | `TechnicalOperationLog` → `PostgresTechnicalOperationLog` | the central table `platform_technical_operations` (`attempted` → `succeeded` / `failed` / `refused`, plus `reported`) |
| Port → Infrastructure | `OrganisationSchemaRepair` → `ProcessOrganisationProvisioner::repair` | starts `platform:prepare-organisation-database <id> --repair` (the **dedicated process**, guide 05) |
| Infrastructure | `OrganisationDatabasePreparer::repair` | in that process only: re-applies the organisation migration set; **verifies** (never writes) `organisation_identity`; reads only `migrations` and `organisation_identity` |
| Port (existing) | `OrganisationReadinessProbe` | the L-1 database facts, re-verified after a repair |
| Port → Infrastructure | `OrganisationOperationReport` → `OutboxOrganisationOperationReport` | writes `TechnicalOperationPerformed` into **the organisation's own outbox** (guide 09); relayed in its context |
| Published | `Platform\Application\Published\TechnicalOperationPerformed` (and its hydrator) | for the consuming context (Organisation, slice 3) that shows it to the organisation |
| Delivery | `php artisan platform:operate <organisation> <operation> --operator=<who>` | exit 0 only on success |

## Flow

```
attempted (central, first)
→ no operator named                                       refused: operator-not-named
→ operation ≠ migration-repair                            refused: case-grant-required   (content access: not offered)
→ unknown or malformed organisation                       refused: unknown-organisation
→ no joining grant                                        refused: no-joining-grant
→ schema not migration-failed                             refused: nothing-to-repair
→ repair in the dedicated process fails                   failed: repair-failed          (schema stays migration-failed)
→ readiness facts (L-1, the organisation database) fail   failed: readiness-not-restored (schema stays migration-failed)
→ otherwise: schema current (status unchanged)            succeeded
succeeded / failed → reported to the organisation; the outcome recorded (with `reported`)
```

**Status is never changed by the channel:**
- a provisioning organisation stays provisioning;
- an operational one becomes routable again **only** because its schema is current **and** its readiness facts hold.

## How "no business reads" is proven (T-16.2)

The test reads PostgreSQL's own per-table counters (`pg_stat_user_tables`: sequential and index scans) for the Membership ledger's member data (`org_memberships`, `org_membership_facts`, `org_membership_commands`), before and after a real repair through the real child process.
- **They do not move.**
- **A positive control** reads one of those tables and shows the counters do move.
- **Technical metadata** is not business content: `migrations`, `organisation_identity`, the ledger clock (via Membership's own readiness function) and `outbox_events`.
- **Statically,** Platform code may not name a ledger table at all (`MembershipLedgerIsolationTest`, R5).

## Testing

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Platform/Application/OperateTechnicallyTest.php
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite PlatformPostgres --filter OperatorChannelTest
```

**`OperatorChannelTest`:** T-16.1 to T-16.8, through the real command and a real repair child.

**Mutation evidence (copy-load):**
- **Child processes always run the real code,** so the repair's own business-read safety rests on the runtime counters and the static guard.
- **A static-scanner mutant is meaningless** (the scanner reads the file on disk), so none was used.

**Not built (deferred, D-219 (3)):**
- case grants and emergency content access;
- backup and restore commands (task 12 rehearses a restore);
- operator identity.

**Traceability:** PBDIGIT-345 task 11 · §3.8, E-4 · D-166, D-219 (3) · ISO-8, ISO-11 · T-16.
