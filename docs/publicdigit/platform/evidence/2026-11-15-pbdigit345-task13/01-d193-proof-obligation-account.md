# The 53 D-193 proof obligations: an account at the end of PBDIGIT-345 (slice 2), 2026-11-15

| | |
|---|---|
| **Register** | `docs/publicdigit/architecture/reviews/2026-11-11-platform-proof-obligation-register.md`, SHA-256 `a3fe00f28f8fe70c388891cbf36042f428bc69a70d1efdf9d24f3cc8e129fb18`. **This is the same hash D-193 (4) cites:** the register is unchanged since its acceptance |
| **Code under account** | `8b6fcd569` (task 12b). Task 13 changes no code |
| **Slice scope** | delta revision 3 §T: "slice 2 executes those for **routing, the locator, provisioning resume and isolation**. **The contact, case-grant and emergency-route obligations stay NOT EXECUTED** until those features are scheduled." The emergency route is deferred by D-219 (3); contacts are out of the plan (F-T1-1) |
| **Author** | Claude, the implementer. **Not independent.** The Principal decides acceptance |

## The statuses used

- **PROVEN:** the obligation as worded is demonstrated by a named test passing on the real target (PostgreSQL 15 isolated cluster, or the database-free suite), with evidence.
- **OUT OF SCOPE:** the obligation's subject is not built in slice 2, for the stated reason. **It stays a build gate** for the slice that builds that subject.
- **NOT PROVEN:** within the slice's subject, but not fully demonstrated. The proven and unproven parts are listed.
- **BLOCKED:** cannot be proven until an external prerequisite is accepted (the register's own marking).
- **FAILED:** demonstrated to be violated. **None.**

**An existing class or feature is never counted as proof.** Only a passing test that exercises the property counts.

## Summary

| Status | Count | Obligations |
|---|---|---|
| PROVEN (whole obligation) | **0** | — |
| NOT PROVEN (in the slice's subject, partly proven) | **3** | B-7, B-9, B-10 |
| BLOCKED | **3** | E-7 (X-2), E-8 (X-1, X-3), F-1 (X-1) |
| OUT OF SCOPE | **47** | all of A, B-1 … B-6, B-8, C, D, E-1 … E-6, F-2 … F-6 |
| FAILED | **0** | — |
| **Total** | **53** | |

**Plain meaning.** D-193's register was written for the **whole** Platform design: contacts, case grants, emergency access, the recovery copy and the D-188 report. Slice 2 built **routing and isolation**. So most of the 53 are about features that do not exist yet, and they stay open as gates for those slices.

Slice 2's own acceptance matrix, **T-1 … T-26**, is passed (see `05-t-matrix.txt`). It covers properties (ISO-1 … ISO-11 at this boundary) that the register does not list separately. **No register obligation is wholly proven:** the three in the slice's subject are each partly proven, and the open parts are below.

## Section A: concurrency matrix (14)

| Row | Obligation (register wording, shortened) | Protects | Status | Reason |
|---|---|---|---|---|
| 1 | approver removal vs any approval, both orders, first and second approval | a removed contact cannot complete an approval | OUT OF SCOPE | contacts and case-grant approvals not built (plan F-T1-1; delta §T) |
| 2 | second approval vs withdrawal, both orders, with a concurrent removal | a withdrawn grant never takes effect | OUT OF SCOPE | case grants not built |
| 2a | 3-way race: second approval, approver removal, withdrawal; all 6 orders | the same, under a three-way race | OUT OF SCOPE | case grants not built |
| 3 | platform withdrawal vs use; no operation starts after the exclusive lock | withdrawal stops access at once | OUT OF SCOPE | case-grant use not built |
| 3a | organisation-side withdrawal vs `live-database` use, both orders | the organisation can stop staff access itself | OUT OF SCOPE | case grants not built |
| 3b | organisation-side withdrawal vs `backup-copy` admission (all cases) | a `backup-copy` *case grant* stops on withdrawal | OUT OF SCOPE | case grants not built. **Not the task 12 restore drill** (a technical operation; see `02-d166-backup-review.md`) |
| 3c | expiry: admission before, at, after; no result after expiry | grants end on time | OUT OF SCOPE | case grants not built |
| 3d | handover: operation between outbox record and copy arrival refused | no access during the hand-over to the organisation's copy | OUT OF SCOPE | the D-170 pending-grant copy not built |
| 4 | already-open session: withdrawal, then next operation refused | an open session does not outlive withdrawal | OUT OF SCOPE | case-grant sessions not built |
| 5 | duplicate grant command creates one; same key, different scope rejected | grant idempotency | OUT OF SCOPE | case grants not built |
| 6 | contact updates, permutations incl. D-184 same-date cases | contact list ordering | OUT OF SCOPE | contacts not built |
| 7 | recovery copy: crash between copy and flag; exactly one copy | the pending-grant copy is exactly once | OUT OF SCOPE | the D-170 copy not built |
| 8 | evidence reconciliation: missing, mismatch, not found, `NotAuthorised`, `Undetermined` | contact designations are checked against Governance | OUT OF SCOPE | contacts not built; X-2, X-3 open |
| 9 | promotion on recovery: `Authorised` promotes; others removed and flagged | a contact is promoted only on evidence | OUT OF SCOPE | contacts not built |

## Section B: other obligations (10)

| # | Obligation | Protects | Status | Evidence / reason |
|---|---|---|---|---|
| B-1 | `attempted`-without-outcome window: reconciliation reports "access may have occurred" | honesty about possible access | OUT OF SCOPE | case-grant access not built. *Related, not the same:* every technical operation is recorded `attempted` **before** it runs (T-16.7, `OperatorChannelTest::test_t16_7_…`) |
| B-2 | `backup-copy`: the isolated copy expires with the grant and is destroyed; destruction logged and verified | a staff copy of content does not linger | OUT OF SCOPE | the `backup-copy` case grant not built. **The task 12 drill's temporary copies are reviewed separately** against D-166 and M-6 in `02-d166-backup-review.md`, with gaps |
| B-3 | session: revocation on withdrawal, expiry, close, Account disablement; no resume | staff sessions end | OUT OF SCOPE | case-grant sessions not built |
| B-4 | notification lifecycle per recipient; `delivered` only with evidence; retry; escalation | contacts learn of access | OUT OF SCOPE | contact notifications not built |
| B-5 | capture scenario: X removes Y and Z, adds A; no grant possible before recovery | resistance to contact-list capture | OUT OF SCOPE | contacts not built |
| B-6 | single contact / fewer than two `organisation-record` contacts: emergency grant refused | no unilateral emergency access | OUT OF SCOPE | emergency route deferred (D-219 (3)) |
| **B-7** | lifecycle: no technical operation in `pending`; `provisioning` only after verification and grant; **`migrating` unroutable** | nothing is done to an organisation before it has joined; never served mid-migration | **NOT PROVEN** | see B-7 below |
| B-8 | bootstrap claim issued once, only to the founding registrar, before `operational` | the first officer is the registrar | OUT OF SCOPE | founding step 4 is outside slice 2 (plan task 5; guide 05) |
| **B-9** | failure scenarios 1–13 as amended | each listed failure ends safely | **NOT PROVEN** | 5 scenarios proven, 1 partly, 7 out of scope; see B-9 below |
| **B-10** | INV-ACCESS-1 release-1 criteria (multi-database design M-6) | staff never see content without the organisation's case grant | **NOT PROVEN** | a go-live gate: 2 of 9 criteria proven in slice 2; see B-10 below and `02-d166-backup-review.md` |

### B-7, clause by clause

| Clause | Status | Evidence |
|---|---|---|
| no technical operation in `pending` | **PROVEN** | `ServedOrganisationTest::test_no_technical_operation_while_pending`; `OperateTechnicallyTest::test_t17_the_restore_operations_need_the_joining_grant_and_a_database`; `OperatorChannelTest::test_t16_3_without_the_joining_grant_and_for_an_unknown_organisation_it_is_refused_and_recorded` |
| `provisioning` only after verification and grant | **PROVEN** | `ServedOrganisationTest::test_provisioning_needs_both_a_verified_counterparty_and_a_recorded_grant`; `…::test_the_grant_and_the_counterparty_verification_are_recorded_once_and_only_before_provisioning` |
| **`migrating` unroutable** | **NOT PROVEN: not implemented** | `SchemaState` has only `current` and `migration-failed` (`app/Contexts/Platform/Domain/SchemaState.php`). **Present risk: none in slice 2.** Migrations run only while `provisioning` (unroutable) or under repair from `migration-failed` (unroutable). **No command migrates an operational organisation yet.** It must exist before one does → **PBDIGIT-352** |

### B-9, scenario by scenario (design R-F; scenario 13 from revision 4)

| # | Scenario → required outcome | Status | Evidence |
|---|---|---|---|
| 1 | counterparty not verified → not routable (`pending` precedes `provisioning`, revision 4) | **PROVEN** | `ServedOrganisationTest::test_a_registered_organisation_is_pending_and_not_routable`, `…::test_provisioning_needs_both_a_verified_counterparty_and_a_recorded_grant` |
| 2 | no service-operation grant → provisioning refused | **PROVEN** | the same tests; `OrganisationProvisioningTest` (task 5b) |
| 3 | contact list updated twice out of order | OUT OF SCOPE | contacts |
| 4 | contact revoked; database fails before the copy updates | OUT OF SCOPE | contacts |
| 5 | contact's Account disabled | OUT OF SCOPE | contacts |
| 6 | case grant given twice | OUT OF SCOPE | case grants |
| 7 | use outside scope or window | OUT OF SCOPE | case grants |
| 8 | database recovers → copy and report into the organisation | OUT OF SCOPE | the D-170 copy |
| **9** | **locator altered outside provisioning → refused; alert (ISO-2); audit log** | **NOT PROVEN (partly)** | **Proven, application paths:** `ServedOrganisationTest::test_the_locator_is_assigned_once_during_provisioning` (`LocatorAlreadyAssigned`); the only caller is `FoundOrganisation` (step 3); the locator is never taken from a request (`OrganisationRoutingDefaultConnectionTest`, `OrganisationEntryPathsTest`); two organisations can never share a database (`PostgresServedOrganisationRepositoryTest::test_two_organisations_can_never_share_one_database`); a database bound to another organisation is refused (`OrganisationProvisioningTest::test_a_database_bound_to_another_organisation_is_refused_and_recorded_as_failed`, `RestoreDrillTest::test_t17_a_wrong_backup_restored_in_place_is_refused_and_a_stays_unroutable`). **Not proven:** (a) the **database** does not refuse an `UPDATE` of `locator_*` on an existing row: an out-of-band SQL edit would succeed; (b) **no alert** and no audit entry exist for such an edit; (c) per-request routing checks `current_database()` against the locator but not the `organisation_identity` row, so a locator re-pointed to an *unregistered* database is not detected until the next L-1 check → **PBDIGIT-353** |
| 10 | migration fails → `schemaState = migration-failed`; unroutable | **PROVEN** | `ServedOrganisationTest::test_a_failed_migration_blocks_routing_without_changing_status_and_a_repair_restores_it`; `PostgresServedOrganisationRepositoryTest::test_t2_only_an_operational_organisation_with_a_current_schema_resolves`; `OperatorChannelTest::test_t16_8_…`, `…::test_t16_6_the_schema_stays_failed_when_readiness_is_not_restored` |
| 11 | central or Identity down → everything waits | **PROVEN** | `OutageAndRecoveryTest::test_t11_with_the_registry_down_requests_and_jobs_are_refused_then_both_resume`, `…::test_t18_with_the_cluster_unreachable_every_organisation_is_refused_then_all_resume_freshly`, `…::test_t11_a_raw_connection_refusal_during_entry_answers_service_unavailable_and_keeps_the_selection` (Identity bindings unavailable → 503). Identity shares the central cluster in release 1 (D-219 (4)) |
| 12 | request for A reaches B → impossible by ISO-1/2/5; attempt refused | **PROVEN** | T-1 `OrganisationEntryHttpTest::test_t1_…`; T-3/T-22 `OrganisationQueueTest::test_t3_t22_…`; T-26 `OrganisationConnectionFactoryTest::test_t26_…`; T-8 `OrganisationMigrationsTest::test_t8_…`; T-6 `OrganisationOutboxRelayTest::test_t6_iso9_…`; T-7 `MembershipThroughRoutingTest::test_t7_cross_organisation_…` |
| 13 | list capture attempt → removed contacts notified; no grant for 24 hours | OUT OF SCOPE | contacts |

### B-10, criterion by criterion (M-6, "to be proved before go-live")

| Criterion | Status | Evidence / gap |
|---|---|---|
| no human standing credentials on organisation business tables | NOT PROVEN | not built. The application's organisation login owns the tables, and anyone holding it (environment) can read them |
| operator roles non-interactive | NOT PROVEN | not built; operators run `platform:operate` in a shell that also holds the login |
| per-organisation backup encryption with key-use log | NOT PROVEN | no backup regime exists (guide 12 limitations) |
| restore only by the automated process | NOT PROVEN | the drill is automated, but **the documented in-place recovery uses a manual `pg_restore`** (admin notes, task 12) |
| migrations only by the runner | **PROVEN** | `OrganisationProvisioningProcessTest` (only the dedicated process and the preparer apply the organisation set); `OrganisationMigrationSetTest` |
| content-free logs (ISO-10) | NOT PROVEN (partly) | routing refusals are content-free by construction, and the technical record holds ids and outcomes only. No test asserts content-freedom across all logs |
| temporary copies encrypted and expiring | NOT PROVEN | the drill's dump and scratch copy are **not encrypted**; cleanup is in-process, with no expiry if the process is killed |
| case grants recorded and reported | OUT OF SCOPE | case grants not built; until then content access is refused (`OperatorChannelTest::test_t16_5_…`) |
| the service-operation grant: given at joining, in the registry, revocable only with termination | **PROVEN** (given, recorded) / OUT OF SCOPE (revocation) | `ServedOrganisationTest::test_a_service_operation_grant_keeps_the_evidence_of_section_3_3`; T-16.3. Service termination is not built |

→ **PBDIGIT-351** carries the M-6 controls the restore drill and backups need.

## Section C: revision 8 additions (9)

All **OUT OF SCOPE.** Every row concerns case-grant admission, handover, contact designation or the D-188 path, none of which is built:

| # | Subject | Status |
|---|---|---|
| C-1 | recovery transition vs admission | OUT OF SCOPE (case grants) |
| C-2 | platform withdrawal during handover; delayed older copy | OUT OF SCOPE (D-170 copy) |
| C-3 | per-designation binding | OUT OF SCOPE (contacts; X-2, X-3) |
| C-4 | operation idempotency (same id, different binding refused) | OUT OF SCOPE (case-grant operations) |
| C-5 | failure injection around admission and `started` | OUT OF SCOPE (case-grant operations) |
| C-6 | deadline: no result after min(expiry, limit); backup-copy job terminated | OUT OF SCOPE (case grants) |
| C-7 | missing-copy insert race | OUT OF SCOPE (D-170 copy) |
| C-8 | D-188 suspension on a failed designation | OUT OF SCOPE (contacts) |
| C-9 | terminal states never reopen | OUT OF SCOPE (case grants) |

## Section D: revision 9 additions (6)

| # | Subject | Status |
|---|---|---|
| D-1 | AuthorityCheck acceptance predicate | OUT OF SCOPE (contacts, case grants) |
| D-2 | `started` claim exactly once | OUT OF SCOPE (case-grant operations) |
| D-3 | operation registry binding | OUT OF SCOPE (case-grant operations) |
| D-4 | deadline clock, timeouts, termination | OUT OF SCOPE (case grants) |
| D-5 | handover version | OUT OF SCOPE (D-170 copy) |
| D-6 | D-188 report content | OUT OF SCOPE (contacts) |

## Section E: revision 10 additions (8)

| # | Subject | Status |
|---|---|---|
| E-1 | concurrent inbox deliveries of the copy | OUT OF SCOPE (D-170 copy) |
| E-2 | registry-only crash and abandonment | OUT OF SCOPE (case-grant operations) |
| E-3 | restart durability of `started` | OUT OF SCOPE (case-grant operations) |
| E-4 | timer-start gap; effective end ≤ D | OUT OF SCOPE (case grants) |
| E-5 | live termination unconfirmed; containment | OUT OF SCOPE (case-grant live access) |
| E-6 | D-188 report record | OUT OF SCOPE (contacts) |
| E-7 | `PersonRef` → bound Account only through Identity; fails closed until X-2 | **BLOCKED on X-2** (Identity I-TDD-02; **PBDIGIT-349**). *Related:* slice 2's E-1 entry fails closed today (`NoIdentityBindingsYet`) |
| E-8 | D-190 act kinds; per-part `ruleRef` / `policyVersion` | **BLOCKED on X-1 and X-3** |

## Section F: revision 11 additions (6)

| # | Subject | Status |
|---|---|---|
| F-1 | Governance answers carry the right actor and capacity | **BLOCKED on X-1** |
| F-2 | failover formula; effective end ≤ D | OUT OF SCOPE (case grants) |
| F-3 | backend ignoring cancel: containment, forced restart, co-hosted reports | OUT OF SCOPE (case-grant live access) |
| F-4 | report record fields | OUT OF SCOPE (contacts) |
| F-5 | admission vs abandonment | OUT OF SCOPE (case-grant operations) |
| F-6 | window parameters | OUT OF SCOPE (case grants) |

## Residual risks (in slice 2's subject)

1. **B-9 scenario 9 (PBDIGIT-353):** an out-of-band edit of a registry locator is not refused or alerted by the database.
2. **B-10 / D-166 (PBDIGIT-351):** the restore drill's copies are unencrypted and do not expire if the process is killed; the in-place recovery is manual. **The drill and the recovery stay development and test only until then** (admin notes).
3. **B-7 (PBDIGIT-352):** no `migrating` state. Harmless until a command migrates operational organisations; required before one exists.
