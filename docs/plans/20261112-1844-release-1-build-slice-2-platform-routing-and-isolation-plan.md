# Release 1, build slice 2: Platform routing and organisation isolation — plan

| | |
|---|---|
| **Status** | **APPROVED for implementation by the Principal, 2026-11-12 (EP-01):** "approve the plan". Strict TDD; no task starts before its RED |
| **Story** | **PBDIGIT-345** (`docs/publicdigit/backlog/PBDIGIT-345-platform-routing-and-organisation-isolation.md`). *(PBDIGIT-342 is reserved by the 2026-11-10 plan; PBDIGIT-344 is the reminder defect)* |
| **Design** | `docs/publicdigit/architecture/reviews/2026-11-12-platform-organisation-routing-isolation-tactical-design.md` revision 3 (the **delta**), consuming **D-178** and **D-193**; decisions **D-219** |
| **Previous slice** | slice 1 (PBDIGIT-343), **accepted** 2026-11-12 with qualifications |
| **Discipline** | strict TDD: **RED → GREEN → developer guide** per task, one story, commits per task group; hexagonal (Domain and Application free of Laravel) |

## 1. Objective

**Every organisation-side operation runs on exactly one registry organisation's own database, chosen only from the trusted registry, or is refused.** This makes the accepted slice 1 ledger reachable in production for ledger organisations.

## 2. Background

**Facts:**
- today's code resolves the organisation through `TenantContext` (path, session, header) on one shared database;
- the queue is `sync`, with no `jobs` table;
- 13 scheduled entries set no context;
- the relay reads the default connection (F-10);
- `org_memberships` has a foreign key to the central `organisations` table (F-13);
- slice 1's Membership code already takes its connection explicitly.

**Decided:** D-160, D-164, D-166, D-178, D-193, D-219 (derived ledger rule, shared hostname, emergency route deferred, one cluster).

## 3. Scope

**In:**
- the Platform registry (`ServedOrganisation`) and `ResolutionService` (fail closed);
- `OrganisationContext` and the connection factory;
- the entry channels E-1 … E-5;
- the two migration sets and `organisation_identity` (F-13);
- the per-organisation relay;
- Membership adapters M-1 … M-3;
- the founding sequence F-1 with L-1 and L-2;
- the operator channel for technical operations only;
- outage behaviour.

**Out:**
- the emergency route (D-219 (3));
- contacts and case grants beyond refusal;
- legacy cutover (C-1 … C-10);
- Organisation structure, Governance, the Membership lifecycle, Forum, Newsletter;
- Reference Geography (D-147);
- per-organisation hostnames;
- **any change to the legacy path.**

## 4. Design decisions used (no new ones)

| Ref | Decision |
|---|---|
| D-219 (1) | **No stored ledger flag;** L-1 / L-2 / L-3 enforcement (delta §3.7) |
| D-219 (2) | shared hostname; ISO-3 re-checked on every request (E-1) |
| D-219 (3) | technical recovery yes, emergency content access no (§3.8) |
| D-219 (4) | one cluster; locator names cluster + database (§3.9) |
| delta §3.1, C-1 | the default connection stays central; organisation code gets its connection explicitly |
| delta M-5 | `organisation_identity` one-row table; F-13 foreign key re-pointed in the organisation schema only |

## 5. Placement (hexagonal)

| Layer | New |
|---|---|
| `app/Contexts/Platform/Domain` | `ServedOrganisation` (aggregate: status, `schemaState`, locator reference, service grant), `OrganisationId`, `DatabaseLocator`, `PlatformStatus`, `SchemaState`, refusals. Pure PHP |
| `app/Contexts/Platform/Application` | `RegisterOrganisation`, `RecordServiceOperationGrant`, `CompleteProvisioning`, `MarkOperational` (L-1), `ResolveOrganisation`; ports `ServedOrganisationRepository`, `OrganisationDatabaseInspector` (L-1 facts), `LegacyOrganisationDirectory` (L-2), `IdentityBindings` (ISO-3) |
| `app/Contexts/Platform/Infrastructure` | PostgreSQL registry repository, `OrganisationConnectionFactory`, HTTP middleware, job middleware and marker, fan-out command, provisioning runner, operator commands |
| `app/Contexts/Membership/Infrastructure` | adapters M-1 … M-3; organisation-schema migration for F-13 |
| `app/Contexts/Shared/Infrastructure/Outbox` | the processor takes a connection; the per-organisation relay job |

## 6. Task checklist

Every task: **RED test(s) first → minimal GREEN → developer guide** under `developer_guide/platform/` (new area; `00_index.md` first).

- [x] **0. Test harness.** *(Done 2026-11-12, TDD: two cycles plus one default-suite fix; final isolated run 144 tests, slice 1 135 unchanged; architecture failures identical. Evidence `docs/publicdigit/platform/evidence/2026-11-12-pbdigit345-task0/`; guide `developer_guide/testing/07_multi_database_harness.md`. **Awaiting the Principal's review before task 1.**)* Planned: Extend `scripts/testing/membership-pg.sh` (or a sibling `platform-pg.sh`) to create **a central database and organisation databases A, B, C** on the isolated cluster. No production configuration. *Gate: the slice 1 suite still passes (T-12).*
- [x] **1. Platform domain.** *(Done 2026-11-12, TDD; 21 unit + 5 purity tests; guide `developer_guide/platform/01_served_organisation_domain.md`; evidence `docs/publicdigit/platform/evidence/2026-11-12-pbdigit345-task1/`. **Awaiting the Principal's review before task 2.**)* Planned: `ServedOrganisation` lifecycle: transitions, routable only if `operational` and `schemaState = current`, grant before provisioning. Unit tests, no framework.
- [x] **2. Registry persistence + `ResolutionService`** (central migration): unknown, not operational or wrong schema → refused (**T-2**). *(Done 2026-11-13, TDD: the domain rebuild path `fromSnapshot`; `ResolutionService`; the central table with invariant CHECKs and a one-database-per-organisation unique index (D-222); 8 new unit and 8 new PostgreSQL tests; guides `developer_guide/platform/02`, `90` (administrator); evidence `docs/publicdigit/platform/evidence/2026-11-13-pbdigit345-task2/`. Authorised by the Principal's review of 2026-11-13.)*
- [x] **3. `OrganisationContext` + `OrganisationConnectionFactory` (custom, D-222; one connection name per organisation, never a re-pointed shared name; add **T-26**, the held-connection proof):** a named connection from the locator; the default connection untouched; credentials by reference (**T-10** part 1). *(Done 2026-11-13, TDD: the factory (name from the id; cluster from `config/organisation_routing.php`; login by `env:` reference; `current_database()` check; release purges), `OrganisationContext`, T-26 and T-10 part 1 tests; guides `developer_guide/platform/03`, `90` (administrator: clusters and per-organisation logins); evidence `docs/publicdigit/platform/evidence/2026-11-13-pbdigit345-task3/`.)*
- [x] **4. Organisation migration set:** *(Done 2026-11-14, TDD: guide `developer_guide/platform/04_organisation_migration_set.md`; evidence `docs/publicdigit/platform/evidence/2026-11-14-pbdigit345-task4/`; the ledger's organisation migration lives in Membership, as the file table says.)* a separate path; `organisation_identity`; F-13 re-pointed for the organisation schema; architecture test that organisation migrations reference no central table (**T-8**, **T-10** part 2).
- [x] **5. (DONE 2026-11-14: 5a `43c8681ce`, 5b `45522a35a`, 5c; guide 05; T-9, T-13, T-14 part 1 proven; step 4's founding record, person entries and bootstrap claim B-8 are outside this slice, see guide 05)** Founding sequence F-1 with L-1 and L-2,** **including the initial platform contacts from the founding evidence** (F-T1-1; at least one with an active account before `operational`): resumable after a crash at each step; `MarkOperational` refused for each missing precondition; legacy id refused (**T-9**, **T-13**, **T-14** part 1).
- [x] **6. (DONE 2026-11-14; guide 06; evidence `docs/publicdigit/platform/evidence/2026-11-14-pbdigit345-task6/`; T-1, T-15, T-10 part 3 proven against Platform's `IdentityBindings` port; production answers "not bound" until Identity's bindings exist, PBDIGIT-349)** HTTP entry (E-1): selection after login; ISO-3 on every request; revoked binding and forged session refused; legacy and registry paths disjoint (**T-1**, **T-15**, **T-10** part 3).
- [x] **7. (DONE 2026-11-14, except T-24: step 1 T-19 `668469223`; guide 07; evidence `docs/publicdigit/platform/evidence/2026-11-14-pbdigit345-task7/`. **T-24 is carried to task 9:** it needs the organisation outbox and relay, which do not exist yet; task 7 proves the dispatch side of Q-7 with T-25. Awaiting the Principal's acknowledgement of that move.)** Queue (E-2, delta §3.10).
  - **Step 1, characterisation before change:** write **T-19** and run it against the **untouched** queue configuration. It covers inline legacy mail and job execution, and a failing legacy job rethrowing with no `failed_jobs` row. **It must be green before anything below.** Record its output as evidence.
  - **Step 2:** add the separate connection `organisation`:
    - one central migration, `organisation_jobs`;
    - failures in the **standard `failed_jobs`** (`connection = 'organisation'`; delta §3.10.1, verified against Laravel 12.71.0);
    - **the default stays `sync`.**
  - **Step 3:** `OrganisationJob` base class (connection fixed, `OrganisationId` required, no `SerializesModels`); `OrganisationJobDispatcher` (type, organisation, connection override and **open-transaction refusal**, §3.10.2); `EstablishOrganisationContext` middleware (connection check, resolve per attempt, scoped context, purge in `finally`).
  - **Step 4:** re-run **T-19 unchanged**; it must still pass.
  - Tests: **T-3**, **T-4**, **T-19** (twice), **T-20 … T-25**. T-23 uses a real `queue:work organisation --once` worker.
- [ ] **8. Fan-out (E-3):** dispatcher over operational organisations; one failing or non-operational organisation doesn't stop the others (**T-5**).
- [ ] **9. Per-organisation relay (E-5)** (also runs **T-24**, carried from task 7): the processor on a given connection; ISO-9 dead-letter; the central relay kept for central events (**T-6**).
- [ ] **10. Membership adapters M-1 … M-3:** C2 end to end through routing; `SOURCE_NOT_LEDGER` outside a registry context; `CROSS_ORGANISATION` (**T-7**, **T-14** part 2, **T-12**).
- [ ] **11. Operator channel (E-4), technical only:** migration repair without business reads; content read refused (**T-16**).
- [ ] **12. Outage and restore:** cluster or registry down → refused, then resumes; scripted restore drill (**T-11**, **T-17**, **T-18**).
- [ ] **13. Proof obligations and evidence:** list each of D-193's 53 obligations as executed / passed or out of scope (with the reason); slice evidence folder with checksums; baseline comparison done soundly (own `vendor/` and autoloader).
- [ ] **14. Index check; evidence to the Principal; acceptance by the Principal.**

**Commit groups:** 0 · 1–3 · 4–5 · 6 · 7–8 · 9–10 · 11–12 · 13–14, each `(PBDIGIT-345)`.

## 7. Acceptance criteria

- **T-1 … T-26** green on the isolated cluster, with raw output and checksums (**T-19 run before and after the queue change**);
- D-193 obligations listed by number;
- legacy suites no worse than the slice 1 baseline;
- Domain and Application free of Laravel (architecture tests);
- guides for every task;
- **the Principal accepts.**

## 8. Progress

- 2026-11-12: plan drafted after D-219. Nothing implemented.
- 2026-11-12: task 7 rewritten for two separate queue connections (delta revision 3, §3.10); T-19 … T-24 added; Q-S2-5 opened.
- 2026-11-12: **approved**; PBDIGIT-345 created; D-221 ratified.
- 2026-11-12: **D-222:** custom multi-database integration (no Spatie); T-26 added to task 3. Task 1 findings F-T1-1 … F-T1-4 closed in the design.
- 2026-11-12: **task 1 done** (pure domain).
  - **Deviation:** shared `TenantId` reused instead of a new `OrganisationId` (canonical discovery).
  - **Deferred to task 2:** domain events.
  - **Findings for the Principal:**
    - **F-T1-1:** D-193 F-07 requires ≥ 1 active-account contact for `operational`, but this plan puts contacts out of scope. Implemented per F-07; task 5 must supply at least the initial contact.
    - **F-T1-2:** delta §3.6 step 1 says "registry entry `provisioning`", but D-185 puts `pending` first. Implemented per D-185; the delta's wording needs a correction note.
    - **F-T1-3:** delta E-2, Q-5 and T-23 mention `suspended`, a later status with undecided triggers. Not modelled; tests use `migration-failed`.
    - **F-T1-4:** schema state is null before the first migration result (interpretation).
- 2026-11-12: **task 0 done** (test-only).
  - **Deviation 1:** `PlatformPostgres` added as a second suite in `phpunit.membership-pg.xml`, inside the existing script rather than a sibling script; allowed by task 0's text.
  - **Deviation 2:** the harness tests skip outside the isolated cluster, found by running the default suite: without it, `php artisan test` would fail 9 tests.
  - **Deviation 3:** the guide lives in `developer_guide/testing/07`, the test-safety area, with `developer_guide/platform/00_index.md` pointing to it.
- 2026-11-12: reconciliation after the Principal's review:
  - T-19 re-sequenced (before and after the change);
  - `organisation_failed_jobs` withdrawn after verifying Laravel 12.71.0 (standard `failed_jobs`);
  - Q-7 enforced (open-transaction refusal, single dispatch path, T-25);
  - revision references corrected.

## 9. Risks

| # | Risk | Mitigation |
|---|---|---|
| R-1 | a queue change alters when the 55 legacy queued classes run (today inline on `sync`) | **two connections, not two queue names** (delta §3.10): the default stays `sync`; organisation jobs use the separate `organisation` connection, set by the base class and enforced by the dispatcher and middleware. **T-19 pins the legacy behaviour before the change and re-proves it after** |
| R-6 | the failed-job store is global in Laravel (one `queue.failer`), so organisation failures share `failed_jobs` with any future legacy worker | rows are distinguished by `connection`; identifier-only payloads; legacy jobs are `sync` and never reach the failer today (delta §3.10.1). Revisit if legacy ever moves to a worker |
| R-2 | a legacy scheduled task touches a registry organisation | T-10: `TenantContext` cannot resolve a registry id; registry ids are never in the legacy table (L-2) |
| R-3 | multi-database tests are slow or flaky | one cluster, databases created once per suite run; reuse slice 1's isolated-cluster discipline |
| R-4 | the slice 1 relay change regresses Adjudication's outbox use | the processor keeps its default-connection behaviour for central events; existing outbox tests stay green |
| R-5 | the scope grows into contacts and grants | they stay refused (out of scope), each listed in task 13 |

## 10. Open questions

- ~~Q-S2-5~~ **decided: D-221 (ratified 2026-11-12):** the central `organisation_jobs` table, with five conditions. Task 7 is unblocked.

- Do the slice 1 plan §12 rows get the appended pointer to delta §3.7 at approval? (Recommended: yes, append-only.)
- Do the launch obligations of delta §3.8 (backup rehearsal, monitoring, runbook) go into a separate operational backlog item? (Recommended: yes, at the end of this slice.)

## 11. Next actions

1. ~~Principal review~~ **done:** plan approved; D-221 ratified (2026-11-12).
2. ~~Create PBDIGIT-345; append the §12 pointer~~ **done.**
3. **Start task 0** (test harness), RED first.

## Traceability

- D-147, D-160, D-164, D-166, D-178, D-193, D-219;
- delta **revision 3** (§3.7 … §3.10.2);
- slice 1 plan `20261112-1251-…` §12, §13;
- PBDIGIT-343 (accepted);
- PBDIGIT-344.
