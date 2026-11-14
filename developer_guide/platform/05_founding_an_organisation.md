# 05: Founding an organisation (F-1), PBDIGIT-345 task 5

**Design:** platform routing tactical design §3.6 (F-1), §3.7 (L-1, L-2); T-9, T-13, T-14 part 1; plan task 5 and finding F-T1-1. **Evidence:** `docs/publicdigit/platform/evidence/2026-11-14-pbdigit345-task5a/`.

**Task 5 is built in three test-first sub-steps:**
- **5a: steps 1–2 (central). Done.**
- **5b: step 3**, the organisation database prepared in its own process. **Done.**
- **5c: step 5**, `operational`, with the L-1 facts gathered in the organisation database and the contacts' active accounts checked through Identity. **Done.**

**Task 5 is complete (2026-11-14).**

## Business purpose

An organisation must never appear operational before:
- its registry entry, founding evidence and initial contacts are recorded;
- its counterparty is verified and the service grant recorded;
- its own database is prepared and bound to it;
- at least one platform contact has an active account.

**Founding invents no authority:** each step records evidence or a recorded act. A platform contact gets no membership, workspace access or authority (D-193 R-B).

## 5a: steps 1 and 2 (`FoundOrganisation`, Platform Application)

| Step | Method | What it records |
|---|---|---|
| 1 | `register(TenantId, FoundingApplication)` | a **`pending`** registry entry with its **founding application**: the evidence reference, the registrar and the initial platform contacts (Identity account ids, at least one). **L-2: an id in the legacy `organisations` table is refused** (`FoundingRefused`). An id already registered with a different application is refused |
| 2a | `verifyCounterparty(id, CounterpartyVerification)` | the counterparty verified (D-175) |
| 2b | `recordServiceOperationGrant(id, ServiceOperationGrant)` | the grant (D-166), immutable |
| 2c | `startProvisioning(id)` | `provisioning`, only after 2a and 2b (D-185) |

**Crash recovery (T-9 for these steps):**
- the registry entry **is** the progress record;
- every step is **idempotent**: repeating it with the same input is a no-op, so after a crash (a lost save) the founding resumes by repeating the step;
- repeating a step with **different** input is refused, and the first record stands.

**Domain:** `FoundingApplication`, a value object. `ServedOrganisation::register(id, application)` now **requires** it, and `fromSnapshot` refuses a stored entry without one.

**Persistence:**
- central migration `2026_11_14_310000_add_founding_application_to_platform_served_organisations` adds the founding evidence, registrar and initial contacts as **NOT NULL**, with at least one contact (`platform_served_organisations_contacts_present`);
- the application is written once, by `add()`; `save()` never rewrites it;
- **the migration stops if entries already exist:** founding evidence is never invented.

**Port and adapter:** `LegacyOrganisationRegister` → `PostgresLegacyOrganisationRegister`, which reads the legacy `organisations` table on the central database.

## 5b: step 3, `provision(id, DatabaseLocator)`, in its own process

```
FoundOrganisation::provision(id, locator)              (parent process: request or console)
  1. assignLocator + save          (recorded BEFORE anything is prepared; another locator is refused)
  2. OrganisationProvisioner::prepare(id)              (port)
       ProcessOrganisationProvisioner                  (adapter: starts a CHILD process and waits)
         php artisan platform:prepare-organisation-database <id>
           DedicatedProvisioningProcess::enter(id)     (this process now serves ONE organisation)
           OrganisationDatabasePreparer::prepare(id)   (refuses unless inside that process)
             - locator read from the REGISTRY, status must be provisioning
             - OrganisationMigrations::apply           (task 4: the organisation's own database)
             - organisation_identity row = the registry id  (a database bound to another id is refused)
             - Membership's trigger creates the ledger clock row
  3. markSchemaCurrent + save      (or markMigrationFailed + save, and ProvisioningFailed is rethrown)
```

**Why a child process:** task 4's known limit. While it runs, the migrator switches the process's default connection. A request process must therefore never migrate.

**Three guards make that structural:**
- the preparer refuses to run unless `DedicatedProvisioningProcess` was entered for that organisation;
- only the command enters it;
- `OrganisationProvisioningProcessTest` (architecture) checks:
  - only the command enters the dedicated process and uses the preparer;
  - only the preparer (and the wiring) uses `OrganisationMigrations`;
  - the preparer's first line is the process check.

**The parent never opens the organisation connection.** The feature test asserts there is no `org_…` connection in the parent afterwards, and that its default connection is unchanged.

**Idempotent:**
- migrations already applied are skipped;
- the identity insert is `ON CONFLICT … DO NOTHING`, then compared with the registry id;
- the trigger's clock insert is `ON CONFLICT DO NOTHING`.

So repeating step 3 changes nothing (tested). A crash at any point is resumed by repeating it.

**The ledger clock row:** a **Membership** organisation migration, `2026_11_14_300200_create_ledger_clock_on_organisation_identity`, adds an `AFTER INSERT` trigger on `organisation_identity`. Membership owns the behaviour "my clock exists when the organisation is bound". Platform imports no Membership code: it does not call `LedgerClockRow`.

**Failures:**
- the child's last error line becomes `ProvisioningFailed`, with every password or secret value from the environment redacted (tested with a wrong password);
- the registry records `migration-failed`;
- the organisation stays unrouted: `provisioning` is never routed (T-9, tested).

**Child environment:** the child inherits the parent's environment. That is how production passes `ORGANISATION_DB_CLUSTERS` and the `env:` logins. Tests pass an explicit environment that points **every** connection at the isolated cluster, because PHPUnit's `<server>` values are not inherited by a child process.

**Pitfall:** never call `OrganisationMigrations` or the preparer from a controller, job or listener. The architecture test will fail, by design.

## 5c: step 5, `markOperational(id)`

The facts are read **at the transition**, never assumed from earlier steps:

| Fact (L-1, D-193 F-07) | Read by | Named in a refusal as |
|---|---|---|
| every migration of the organisation set recorded, and the ledger tables present | `PostgresOrganisationReadinessProbe` (`migrations` history compared with the set's files) plus **Membership's** `org_membership_ledger_readiness(uuid)` | `ledger schema` |
| the one `organisation_identity` row equals the registry id | the probe | `organisation identity` |
| Membership's ledger clock row for the id | Membership's `org_membership_ledger_readiness(uuid)` | `ledger clock row` |
| at least one initial contact has a known, active account | `ContactAccounts` → `IdentityContactAccounts` → Identity's `AccountDirectory` | `a platform contact with an active account` |

**Order of checks:**
1. An earlier step missing is refused by the aggregate **before** any database is inspected:
   - `NotProvisioning`;
   - `NoLocator`;
   - `SchemaNotCurrent`, including after a failed preparation.
2. Then the facts are gathered. If any is missing, `FoundingRefused` names **each** missing one (`OperationalReadiness::missing()`), and nothing is recorded (T-13).
3. The aggregate checks everything again, then the registry saves `operational`. From that moment `ResolutionService` routes it.
4. Repeating step 5 on an operational organisation is a no-op and inspects nothing. After a lost save, repeating it completes it (T-9).

**Why Membership answers the ledger question.** R5 (`MembershipLedgerIsolationTest`, the Election-Only boundary) forbids any code outside Membership to name a ledger table. The first version of the probe did, and the guard refused it.
- The fix is not an exception to the guard. Membership's organisation migration `2026_11_14_300300_create_ledger_readiness_function` publishes `org_membership_ledger_readiness(p_organisation uuid, OUT schema_present, OUT clock_row_present)`.
- Two booleans and no ledger data. Platform calls it by name.

**Why Identity has one adapter.** This is the baseline §9 pattern (consumer-owned port):
- Platform owns `ContactAccounts` (and `ContactAccountsUnavailable`); `IdentityContactAccounts` is the **only** Platform file that touches Identity (`PlatformIdentityBoundaryTest`).
- Its 3 imports are recorded one by one in the architecture-ratchet baseline. The ceilings went from 197 to **200** entries and from 264 to **267** occurrences, visibly, with history comments.
- If Identity unavailable, or an id it cannot read: `ContactAccountsUnavailable`, and the organisation is not made operational (fail closed).
- A contact gets nothing from being counted (D-193 R-B).

**The probe:**
- never migrates;
- releases the organisation connection afterwards (tested);
- is listed in `OrganisationRoutingDefaultConnectionTest`, together with the 5b preparer, as organisation code that never uses the default connection.

## Not in task 5 (approved plan scope, recorded for task 13's obligation list)

| Design §3.6 step 4 item | Why not now |
|---|---|
| the founding decision recorded from evidence (D-148) | Organisation and Governance are out of slice 2 ("Organisation structure, Governance" in the plan's **Out** list) |
| the registrar's and contacts' local person entries and `PersonRef`s | Organisation context (slice 4) |
| the registrar's bootstrap claim before `operational` (D-193 F-03; obligation **B-8**) | needs Identity's claim mechanism (PBDIGIT-341: open, not authorised, design first) |

The **contacts themselves** are in scope (F-T1-1): recorded at step 1, and checked for an active account at step 5 (5c).

## Testing

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Platform
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite PlatformPostgres
```

- **`FoundingApplicationTest`:** validation, and the register/restore rules.
- **`FoundOrganisationTest`:**
  - T-14 part 1 (legacy refused);
  - steps 1–2;
  - **T-9: a crash at each step**, then resume and repeat;
  - different input refused; unknown organisation refused.
- **`OrganisationProvisioningTest` (5b, isolated cluster):**
  - step 3 in a child process, touching only that database;
  - repeating it changes nothing;
  - the preparer refused in-process;
  - another organisation's database refused;
  - a failing child fails closed with no password.
- **5c, in `OrganisationProvisioningTest`:**
  - the whole founding ends operational and routed;
  - T-13 at the database: a set migration not recorded, a ledger table missing, the identity of another organisation (which, through the clock's foreign key, also removes the clock row, so both are named), the clock row missing;
  - no active contact;
  - production wiring.
- **5c, `FoundOrganisationTest`:** T-13 for each fact **alone**, and each earlier-step refusal; Identity unavailable; T-9 at step 5. **`OperationalReadinessTest`:** `missing()`. **`IdentityContactAccountsTest`:** only known, active accounts counted; fail closed.
- **`OrganisationProvisioningProcessTest` and `PlatformIdentityBoundaryTest` (architecture):** the process boundary; Platform reaches Identity through one adapter.
- **`PostgresServedOrganisationRepositoryTest`:** the application stored once and never rewritten; NOT NULL and the contacts check; the migration stop; the legacy register.

**Administrator:**
- **5b:** the command `platform:prepare-organisation-database` is started by founding itself. Do not run it by hand.
- The provisioning process needs `ORGANISATION_DB_CLUSTERS` and the cluster's `<NAME>_USERNAME`/`<NAME>_PASSWORD` in its **real** environment (task 3).
- **5a:** a new **central** migration (`2026_11_14_310000_…`). Not for production before the migration-approval control. If it stops with "holds N entries without a founding application", do not fill those columns by hand: report the entries.

**There is no entry point yet.** `FoundOrganisation` is an application service. Its delivery (HTTP E-1) is **task 6**. Until then nothing in production calls it, and no organisation is founded in production.

**Traceability:** PBDIGIT-345 task 5 (5a `43c8681ce`, 5b `45522a35a`, 5c) · §3.6, §3.7 · D-148, D-166, D-174, D-175, D-185, D-193 · L-1, L-2 · T-9, T-13, T-14 part 1 · R5.
