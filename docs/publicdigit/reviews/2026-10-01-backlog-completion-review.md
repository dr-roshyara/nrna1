# PublicDigit backlog completion review (Backlog Completion Mode)

| | |
|---|---|
| **Date** | 2026-10-01 |
| **Kind** | ⚠ authority: generated. **Backlog completion**: every candidate obligation is classified A–E. **It authorises nothing, implements nothing, and applies no correction to a frozen story** (corrections are *proposed*, §2). |
| **Objective (PO, verbatim)** | *"Close the gap between what PublicDigit must correctly do and what the backlog explicitly owns."* |
| **Frozen baseline** | `PBDIGIT-00`…`208`, plus new IDs only for a delivery obligation shown not to be covered. **This review adds four: `PBDIGIT-209`** (§3), **`PBDIGIT-210`, `PBDIGIT-211`** (§8) and **`PBDIGIT-212`** (§9). |
| **Supersedes in part** | `2026-10-01-next-phase-status-and-todo.md` §5/§7. **The draft `PBDIGIT-209` (merge/release to `main`) is WITHDRAWN** on the PO's instruction (*"Do NOT create or prioritize PBDIGIT-209 merely because no explicit merge story exists"*). It was never committed, and it is now class **E** (§5). |
| **Prepared by** | `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared) |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/` |

> **FINAL STATE (after the controlled closure pass, §9). This block supersedes the current-state statements of §1–§8, which record earlier passes.**
> - **Classified 1,965 / 1,965 · directly verified 381 · explained by failure/signature evidence 1,265 · explained by code-reading agents 319 · resolved 0.**
> - **Limitation:** the 1,584 failures in the last two groups were **not individually re-run**. Classification is not resolution.
> - **New stories in this series: `PBDIGIT-209`, `210`, `211`, `212`.** The last UNDETERMINED item was resolved as `PBDIGIT-212`.
> - **Discovery is CLOSED.** No implementation is authorised.

## 1. What this pass examined

The delivery audits had already classified the code markers, documents, registers and test estate (`…-pbdigit-story-integrity-final.md`, `…-strategic-knowledge-traceability-audit.md`). **This pass covers the evidence produced since the freeze and not yet turned into backlog decisions:**
1. **The full test run's failures, by where the exception originated.** Of 1,965 failures:
   - 1,278 were thrown in test code;
   - 358 are assertion failures;
   - 11 come from database factories or seeders;
   - **318 were thrown inside `app/`, at 46 distinct sites. Each of the 46 was read and classified** (§3.1).
2. The Track 2 outcomes needing backlog action (corrections, candidates, decisions).
3. The proposals left open by the integrity review (merges, re-links).
4. The runtime-audit and repository facts from the next-phase review.

**Rule applied throughout:** *search results are candidates, not conclusions.* Each candidate was checked in its real context (reachability, callers, routes, schema) before it was classified.

## 2. Class B: existing stories that are stale or incomplete (proposed corrections, NOT applied)

| Story | What is stale | Proposed precise correction | Evidence |
|---|---|---|---|
| **`PBDIGIT-41`** (demo provisioning) | It names **two** provisioning paths. **A third, automatic path is also broken:** `DemoElectionResolver.php:60` auto-creates an organisation's demo through `DemoElectionCreationService::createOrganisationDemoElection()`, which writes `'user_id' => "demo-…-N"` into a **`uuid`** column (`DemoElectionCreationService.php:161`; migration `2026_03_05_000013:41`). It fails, and **the resolver catches and only logs it**, so it is silent. | Add to 41's findings: *"Third path, automatic organisation demo (`DemoElectionResolver` → `DemoElectionCreationService:161`): `demo_candidacies.user_id` receives a non-UUID string → insert fails, swallowed at `DemoElectionResolver.php:60-70`. `demo:setup` (the working path) writes `user_id => null`."* | `DemoElectionCreationServiceTest` 3 F (`invalid input syntax for type uuid: "demo-…-1"`); `DemoElectionAutoCreationTest` 3 / 3 F; `SetupDemoElection.php:344` |
| **`PBDIGIT-177`** (WP-4D intake) | Its scope lists the *"Jurisdiction-carrying widening of `receiveRulingDecision()` (R-73)"* as work to do. **It is already implemented.** | Strike that item from the scope, with a note: *"R-73 already realised: `AdjudicationProcessManager.php:142` (`Jurisdiction $jurisdiction`)."* | Track 2 §7 |
| **`PBDIGIT-134`** (the `PBDIGIT-69` collision) | Its problem says both files came from `50d1ff1b1`. | *"The cache-key file was added in `d930cc9e4` (2026-08-12) and carries implementation history under 69. The operating-core file was added in `50d1ff1b1` (2026-08-17) and is the later collision."* (134's text is generated, so the correction goes into the backlog source data.) | closure audit §10 |
| **`PBDIGIT-167`** (legacy test estate) | `F-7C-4` (`ValidateVotingIpTest`) is bound to the middleware that `PBDIGIT-120` deletes | Re-link F-7C-4 to `PBDIGIT-120` as EXISTING, so 167 keeps a single owner | integrity review §3.3 |
| **`PBDIGIT-188`** (pin docblock) | It concerns the same pin as `PBDIGIT-186` | Merge into 186 (one problem: the state of the ADR-1 pin) | integrity review §3.2 |

**Further B corrections found by the test-estate pass (§8):**
- **`PBDIGIT-158`:** the root cause is located (`forStorage()` asymmetry).
- **`PBDIGIT-63`:** extend F-3, because validation errors are swallowed too.
- **`PBDIGIT-195`:** add the cross-tenant governance matrix.
- **`PBDIGIT-96`:** add the single-assign eligibility asymmetry.
- **`PBDIGIT-29`:** its listener fact is false.
- **`PBDIGIT-37`:** extend it to per-test isolation on PostgreSQL.
- **`PBDIGIT-74`:** the `'platform'` slug item is now **verified**; its repair is owned by `PBDIGIT-211`.

**None of these is applied.** The baseline is frozen, and **the PO decides whether these additive corrections may be written into the stories.**

## 3. Class C: a genuine delivery obligation not covered by any story

### 3.1 How the 46 `app/` throw sites were classified

| Classification | Sites | Example | Backlog |
|---|---|---|---|
| **Test drift** (wrong constructor arguments or types, `Log::spy` nulls, fixtures without an actor or tenant) | 33 | `VoteController.php:60` (test does `new VoteController()`); `Fee.php:51` (Membership `TenantId`); `ConstitutionalMetrics.php:209` (`Log::spy`) | **A:** `PBDIGIT-77` / `165` / `167` / `208` |
| **Deliberate RED pins** | 6 | `ReportPeriodExpiryHandler.php:33`, `GateIntervalStateQuery.php:29`, … | **A:** `183` / `184` / `185` |
| **Code that cannot run in production** (unreachable) | 3 | `UuidGovernanceDecisionIdGenerator` (references a class not in its namespace; **0 references**) · `App\Http\Responses\LoginResponse` (implements `Laravel\Fortify\Contracts\LoginResponse`; **Fortify is not installed**, so the binding is never resolved) · `ElectionReadModel` (undefined `DeprecationPolicy::MODE`; **never instantiated**) | **E** (dead code, §5) |
| **A production path with a defect** | 2 | `MembershipPaymentService.php:52` → **`PBDIGIT-209` (NEW)** · `DemoElectionCreationService.php:160` → **B: `PBDIGIT-41`** | §3.2 · §2 |
| **Production-shaped, but a test artefact** | 2 | `NewsletterAuditLog` NOT NULL actor (the only production caller is the authenticated `OrganisationNewsletterController.php:301`) · `ElectionSecurityEvent` foreign-key violation (the test uses a non-existent election) | **A:** `208` |

### 3.2 `PBDIGIT-209`: a membership fee payment cannot be recorded (NEW)

Full story: [`../backlog/PBDIGIT-209-membership-fee-payment-cannot-be-recorded.md`](../backlog/PBDIGIT-209-membership-fee-payment-cannot-be-recorded.md). In summary:

| Template field | Content |
|---|---|
| **Problem** | `MembershipPaymentService::recordPayment()` inserts `currency` and `payment_reference`; `membership_payments` has neither column |
| **Consequence** | **An organisation cannot record a member's fee payment.** The routed `fees/{fee}/pay` fails; the fee status and the standing that feeds on it never update |
| **Evidence** | `MembershipFeeControllerPayTest` 5 / 5 F and `MembershipPaymentServiceTest` 9 / 9 F, all on `column "currency" … does not exist`. The service and model date from 2026-04-17; the table was re-created 2026-05-03 with a different column set |
| **Existing-story search** | `git grep -i` over all story files, the 76–157 backlog and the 159–208 specs for `membership_payments`, `recordPayment`, `record payment`, `fees/{fee}/pay`, `MembershipPaymentService`, `currency` → no match |
| **Why not covered** | 165 is a test-only fixture. 167/208 own the failing *tests*, and their scope requires production defects to get their own story. EPIC-02's 04–08 are verification stories with no defect |
| **Scope / out of scope / acceptance / dependencies / verification** | in the story file; acceptance verifies the **business state** (payment row, fee paid, `FeePaid` emitted), not the redirect |

## 4. Class D: decision dependencies (no implementation story until decided)

| Decision | Decider | What it unblocks |
|---|---|---|
| **D-1:** engineering and documentation items as PBDIGIT stories? | PO | the classification of 159, 161 (residue), 163, 164, 168, 169, 170, 171, 188, 194; AD-007/009/010; manifesto drift; B-5 |
| `PBDIGIT-209` column shape (per-payment currency? `payment_reference` vs `transaction_reference`) | PO | 209's GREEN |
| The Evidence-side bounded contexts: build, supersede or defer? | ARB / Decision Authority | Track 2 SK-078 (and integrity protection of audit evidence) |
| Which bounded-context set governs (BDR v1.1 vs the EPIC-002 map) | ARB | Track 2 SK-215; `PBDIGIT-189` |
| The Q-2 evidence-preservation anchor (interim resolver bound) | Q-2 owner / ARB | Track 2 SK-177 |
| COL-5a home | governance | Track 2 SK-135 |
| Q1–Q4 | Contestation Domain Owner | `PBDIGIT-176` |
| ADR-003: the consequences of a revoked verification | PO / ARB | Track 2 SK-152 |
| Committee dissolution and terms; membership re-application: are they capabilities? | PO | the closure audit §5.1 UNKNOWN groups |
| Code-only rules Q-a…Q-e (billing, wing criteria, geography coverage, welcome email, geography validation) | PO | whether those become stories |
| Widen C-1 (`76`–`78`) to the 690 voting/demo failures that 208 owns? | PO | 76–78 / 208 |
| `PBDIGIT-00`'s "no story VERIFIED" rule; a Level-0 *challenge* segment | PO | product definition |
| The other recorded decision gates | as recorded | 160, 179, 181, 183, 184, 189, 197 |

## 5. Class E: future or candidate concerns (NOT in the current delivery backlog)

| Item | Why E now | When it becomes current |
|---|---|---|
| **Merge / release to `main`** (the withdrawn draft 209) | PO: merge/release is a later delivery concern. The facts are kept in `2026-10-01-next-phase-status-and-todo.md` §5: 1,599 commits ahead; 244 code files vs 16,242 KnowledgeOS files; the gate red | after backlog completion and implementation execution |
| **A unified runtime audit review capability** | Audit exists in four stores. `election_audit_logs` is write-only. There is no review path and no integrity protection. **No stated business purpose yet** (next-phase review §4) | after the PO states what an auditor must be able to prove |
| Unreachable code: `UuidGovernanceDecisionIdGenerator`, the Fortify `LoginResponse` binding, `ElectionReadModel` | cannot fail in production; dead-code hygiene (the D-1 class) | dead-code disposition (`PBDIGIT-122` covers voting dead code only) |
| Two dead routes (`profile/edit`, `admin/ip-stats`), 30 unrouted controllers, 68 `.bak` test files, the stray-file-writing `DebugCandidacyTest` | not linked from any page; hygiene | as above |
| `MyCommitteesQueryService` null fields | not consumed by any page | if a page needs them |

## 6. Class A: already correctly covered (summary)

- **Track 2's 35 B outcomes**, each mapped to its existing story (Track 2 §4).
- **Every failing test file** has one owner (closure audit §7.1).
- **The live defects:** 195, 201, 202, 203.
- **The correction-loop remainder:** 174–182, 196–200, 204–206.
- **The OperatingCore slices:** 183–187.
- **Audit-adjacent items:** 22, 23, 42, 112, 114, 143, 203.
- **Done and not re-ticketed:** WP-4C-1 D1/D2 (R-93); PB-006 (R-94); everything marked IMPLEMENTED in Track 2 (43 outcomes).

## 7. Is backlog completion finished?

> **⚠️ Wording corrected (ARB review, 2026-10-01).** The original sentence (*"As to ownership: yes. Every candidate found has exactly one place"*) was true only for the scope of this pass, and is replaced by:

**All obligations identified in the completed review passes have been classified or assigned:** a story (A, or C = `PBDIGIT-209`), a proposed correction (B), a decision (D) or a deferral (E). **Backlog completion is not yet globally complete**, because the remaining test-estate classification can still reveal further production obligations.

**As to discovery inside the test estate: not yet.** It is owned, not finished:
- *(⚠️ Superseded by §8–§9: both populations were classified in the following passes.)* **At the time of this pass:** 1,278 test-origin errors and 358 assertion failures were not individually classified. By their stories' own scope, classifying them (and raising each production defect found as its own story) **is the work of `PBDIGIT-167` and `PBDIGIT-208`.** This pass classified every failure thrown from `app/`. An assertion failure can also hide a production defect, which is why 167/208 start with classification.

**The honest status:** the backlog owns everything known. The test-estate classification inside 167/208 is the one owned activity that can still raise new defect stories, and each would follow the rule used here for 209.


## 8. Test-estate classification: the remaining 1,636 failures (continued pass, after ARB review)

**Method.**
- **Test-origin errors (1,278):** clustered by normalised error signature (114 signatures); each cluster checked against production code.
- **Assertion failures (358):** classified per test by four read-only agents (voting · election · membership · organisations/auth/other). The agents **ran no tests**, because the suite shares one test database. **Every suspected production defect and every UNDETERMINED item was then re-verified by this process**, one at a time (code reads and scratch probes against `nrna_test`).

### 8.1 Results

| Population | Classified as | Count |
|---|---|---|
| **Test-origin errors (1,278)** | fixture, schema, environment or test drift; obsolete tests | 1,265 (cluster table §8.4) |
| | already-owned production defects reached from test setup | 13 (`PBDIGIT-209` ×9, `PBDIGIT-202` ×4) |
| **Assertion failures (358)** | TEST_DRIFT 175 · FIXTURE_DRIFT 102 · OBSOLETE 23 · ORDER_DEPENDENT 19 | 319 |
| | suspected production defects | 35, verified below |
| | UNDETERMINED | 4 (two resolved, one owned, one remaining at the time of §8 → **the last UNDETERMINED item was resolved as `PBDIGIT-212`**, §9.1) |

### 8.2 Every suspected production defect, verified and placed

| Finding (verified) | Tests | Class | Owner / action |
|---|---|---|---|
| **The membership fee payment writes non-existent columns** (`currency`, `payment_reference`; the listener writes `income_id`) | 15 | A | **`PBDIGIT-209`** (updated: `income_id` listener added) |
| **Participant import: the template's roles violate the DB CHECK constraint** (500 on the routed import) | 1 | **C** | **`PBDIGIT-210` (NEW)** |
| **Scheduled jobs see no tenant data:** with no tenant, the `BelongsToTenant` fallback looks for slug `'platform'` (the platform organisation is `'publicdigit'`) → `organisation_id IS NULL`. Annual fees, overdue marking, renewal reminders and the voter-cache flush do nothing. **Runtime probe: 0 of 1 active members visible** | 2 (+ the jobs' production behaviour) | **C** | **`PBDIGIT-211` (NEW)** |
| Newsletter election audiences filter on `users.organisation_id` | 5 | A | `PBDIGIT-74` |
| `election_security_events.overlay_influence_chain` NOT NULL, never supplied | 3 | A | `PBDIGIT-42` (already records "every audit INSERT fails" and the velocity overlay) |
| Issuance marker 2 h shift | 1 | **B** | **`PBDIGIT-158`: root cause found.** `EloquentAdjudicationProcessStore::forStorage()` (`:118-133`) formats `opened_at` and `concluded_at` as `DATE_ATOM` (with offset) but passes `issuance_requested_at` through raw, so the timestamptz column reads it in session time. *(158's "mapper symmetric" statement was right about the mapper. The asymmetry is in the store.)* To be confirmed by 158's planned UTC/Europe-Berlin characterisation. |
| The ADR-1 absent-aggregate pin | 1 | A | `PBDIGIT-186` |
| **Organisation creation swallows validation errors:** `validate()` sits inside `catch (\Exception)` (`OrganisationController.php:295-392`), so `errors.name` (`Create.vue:73`) is never filled, and a bad name shows only a generic error | 3 | **B** | **`PBDIGIT-63`:** extend F-3. The same catch also converts `ValidationException`, not only raw exception text. |
| **The canonical committee handler builds the governance matrix from every tenant's `governance_level_definitions`** (`AppServiceProvider.php:61-65`; the table is per-tenant, `unique(tenant_id, level)`) | 1 | **B** | **`PBDIGIT-195`:** add the cross-tenant matrix. It is the same endpoint and handler, the API-only validation path of the dual-path ADR. |
| **Voter eligibility, single-assign and import-preview path, ignores `grants_voting_rights` and membership expiry** (`FullMembershipPolicy::decideForContext`, `:55-62`); the bulk path enforces both (`EloquentVoterEligibilityQueryService.php:113-118`). Not used at vote time | 3 | **B + D** | **`PBDIGIT-96` (H-7):** add this concrete asymmetry and the assignment path as evidence. **The repair waits for H-7's decision on the canonical membership predicate.** It then needs an owner (a candidate story *after* the decision). |
| Member import job parses membership number, status, fees status and dates, but passes only email and name (`ProcessMemberImportJob.php:190-196`); the template contract contradicts the docblock | 1 | **D** | PO: which columns does the member import honour? |

### 8.3 UNDETERMINED items and order dependence

| Item | Result |
|---|---|
| Registration tests (`RegistrationTest`, `LoginOrganisationAccessTest`, `RegistrationMultiTenancyTest`) | **Resolved: a test-only artefact, with a real but harmless production fault.** The `Registered` listener's raw `DB::table` insert (`CreateUserOrganisationRole.php:52`) has no `id`; there is no DB default and the model's `HasUuids` is bypassed. It always fails and logs an ERROR. **In production** the controller then creates the pivot row through the model, so registration works. **In tests** an outer transaction exists (`DB::transactionLevel()` = 1, probed), so the failure poisons it and causes the 500. → **B:** correct `PBDIGIT-29` §2 ("the listener creates the membership row" is false; the controller does) · **E:** remove the broken listener (it also makes the design fragile: if it ever succeeded, the controller's insert would hit `unique(user_id, organisation_id)`). |
| `ElectionShowControllerTest::start_for_already_voted…` | A: `PBDIGIT-78` (voting-active fixtures) and `201` (`start()` ambient scope) |
| `ElectionGracePeriodUITest` (403 for an active chief) | *(⚠️ Superseded by §9.1: **resolved as `PBDIGIT-212`**, a production-code defect: the union-typed `string\|Election` parameter is not bound, so the `can:` middleware receives the slug.)* **At the time of §8: UNDETERMINED, narrowed.** The policy evaluated directly returns `true` for the same user and election, with the same organisation. Through the route (`web, auth, verified, tenant, can:manageSettings`) it is denied, so the difference lies in route binding or the tenant middleware. **Decisive next step:** trace the bound `election` instance under the `tenant` middleware. Owned by `PBDIGIT-208`. |
| **Order dependence (19 assertion failures + part of the 249 duplicate-key errors)** | **Root cause verified:** `tests/TestCase.php:24-35` skips the transaction on PostgreSQL, so classes that inherit `RefreshDatabase` from `TestCase` get **no isolation**. Probe: a row created in test A is visible in test B, at transaction level 0. **Classes that re-declare `use RefreshDatabase` get a transaction** (the trait method overrides `TestCase`'s skip; probed level 1). **Isolation is inconsistent per class.** → **B: `PBDIGIT-37`.** It already owns the false safety comment and a database-target guard; extend it to *"restore consistent per-test isolation on PostgreSQL (or document its absence), and remove the per-class inconsistency"*. The affected failures stay owned by `PBDIGIT-208`. |

### 8.4 Test-origin error clusters (1,278)

| Cluster | Tests | Owner |
|---|---|---|
| FIXTURE / ORDER: duplicate inserts (UserFactory::configure + attach; static slugs/emails) | 249 | 77 · 208/167 |
| SCHEMA DRIFT: fixture writes retired global voter flags | 213 | 76 (named files) · 208/167 (rest) |
| FIXTURE DRIFT: organisation_id NULL (old demo model) against NOT NULL | 139 | 77 · 208 |
| FIXTURE DRIFT: integer or string ids written to UUID columns | 109 | 208/167 |
| FIXTURE DRIFT: attach() on a has-many relation (pivot misuse in test setup) | 102 | 77 · 208 |
| ENVIRONMENT DRIFT: MySQL-only statements on PostgreSQL | 101 | 208/167 |
| FIXTURE DRIFT: expected record absent (setup did not create it under current rules) | 62 | 208/167 |
| FIXTURE DRIFT: required column not supplied by the test (production supplies it, e.g. save_vote sets vote_hash) | 45 | 208/167 |
| FIXTURE DRIFT: values outside current check constraints | 45 | 208/167 |
| OBSOLETE MODULE: Geo Kernel referenced by nothing in production (class E) | 43 | 208/167 (tests) · E (code) |
| TEST DRIFT: test calls an API that no longer exists (or a test helper that never existed) | 40 | 208/167 |
| SCHEMA DRIFT: fixture writes a column the schema no longer has | 34 | 76 · 208/167 |
| OTHER (individually fixture or test drift; see list) | 23 | 208/167 |
| OBSOLETE: tests of removed security classes | 14 | 122 |
| TEST DRIFT: renamed or never-routed route | 14 | 157 (elections.*) · 208 |
| COVERAGE GAP: demo ballot route returns 404 for the test's fixture slug (probe); the runtime demo journey is verified (PBDIGIT-38) | 10 | 208 |
| PRODUCTION DEFECT (reached from test setup) | 9 | 209 |
| OBSOLETE: permission neither seeded nor used | 8 | 208 |
| OBSOLETE: removed model | 6 | 208 |
| TEST DRIFT / UNDETERMINED: expected exception not thrown | 4 | 208/167 |
| PRODUCTION DEFECT | 4 | 202 |
| FIXTURE DRIFT: referenced row not created | 4 | 208/167 |


### 8.5 Corrections to this process's own earlier statements

- **Probes and transactions.** The final closure audit's header says the scratch probes ran *"in a transaction (`RefreshDatabase`)"*. **That is false for probe classes inheriting `TestCase`:** they ran without isolation, and their rows **remained in the test database `nrna_test`**. The development database was never targeted (`phpunit.xml:70`). Annotated additively in that document.
- **Membership "Phase 3C" TODOs.** The closure audit (§6.1) filed `SendRenewalReminder` / `MarkOverdueMembers` / `CleanupExpiredInvitations` markers as *engineering refactor*. **One of them, *"Iterate through TenantContext::allTenants() for multi-tenant"*, describes the live defect now owned by `PBDIGIT-211`.**

### 8.6 Status after this pass

- **Every failure of the full run now has a classification and an owner:** a story, a proposed correction, a decision, or the classify-and-repair scope of `PBDIGIT-167` / `208`. *(⚠️ Qualified by §9.3: **classified ≠ explained ≠ resolved.** 381 failures were verified directly, 1,584 were explained without an individual re-run, and 0 are resolved.)*
- **New stories: `PBDIGIT-210`, `PBDIGIT-211`.**
- *(⚠️ Superseded by §9.1.)* At the time of §8, one UNDETERMINED item (`ElectionGracePeriodUITest`) remained. **The last UNDETERMINED item was resolved as `PBDIGIT-212`.**
- **The remaining discovery risk is bounded and named:**
  - the 319 drift-class assertion failures and the 1,265 drift-class errors were classified by cluster or by agent from the failure text and code. **None of them was re-run individually by this process.** A misclassified one could still hide a defect. Their repair work under 167/208 re-runs each test, and that is where any such defect would surface;
  - queued, non-scheduled jobs may face the same tenant gap as `PBDIGIT-211`, depending on the production queue driver, which was not inspected.


## 9. Controlled closure pass: discovery STOPS here (ARB instruction, 2026-10-01)

**Scope of this pass, as instructed:**
1. resolve the remaining UNDETERMINED item;
2. reconcile the totals;
3. separate *classified* / *explained* / *resolved*;
4. produce the final lists;
5. identify what is ready for PO authorisation;
6. stop discovery.

### 9.1 The last UNDETERMINED item is resolved → `PBDIGIT-212` (NEW)

`ElectionGracePeriodUITest` (403 for an active chief) is **a production defect, not a test artefact.**
- **Cause:** `elections.timeline` and `elections.timeline-view` carry `->can('manageSettings', 'election')`, but their actions declare `string|Election $election`. Implicit binding binds only single-model-typed parameters, so the Gate receives the **slug string** and denies **every** user.
- **Probe:** the policy called directly → `true`; through the route → `Gate::after` shows `args=['<slug>'] result=NULL`.
- **Reach:** a reflection scan of every route with a `can:` middleware found **exactly these two**.
- **History:** on `main` since 2026-04-22 / 2026-05-20 (`git blame`).
- **Search:** no story owns it (search in the story file).

### 9.2 Reconciled totals: all 1,965 failures of the full run

| Origin | Count | Placement |
|---|---|---|
| **Thrown in `app/`** (46 sites, each read) | **318** | test drift 253 · tests of unreachable code 28 (code: E) · deliberate RED pins 15 (`183`–`185`) · test artefacts 12 · **`PBDIGIT-209` 7** · **`PBDIGIT-41` (B) 3** |
| **Thrown in factories or seeders** | **11** | `DemoElectionSeeder` `post_id` 7 → **`PBDIGIT-41`** (A, already documented) · `CommitteeModelFactory` foreign-key drift 4 → 167/208 |
| **Thrown in test code** (114 signatures) | **1,278** | fixture, schema, environment or test drift and obsolete tests 1,265 → `76`/`77`/`78`/`122`/`157`/`167`/`208` · **`PBDIGIT-209` 9 · `PBDIGIT-202` 4** |
| **Assertion failures** | **358** | TEST_DRIFT 175 · FIXTURE_DRIFT 102 · OBSOLETE 23 · ORDER_DEPENDENT 19 (= 319) · **verified defects 35:** `209` 14 · `74` 5 · `42` 3 · `186` 1 · `158` 1 · **`211` 2** · **`210` 1** · `63` (B) 3 · `195` (B) 1 · `96` (B+D) 3 · member-import (D) 1 · **UNDETERMINED 4 → resolved:** registration (test artefact) 2 · `78` 1 · **`212` 1** |
| **Total** | **1,965** | |

### 9.3 Classified ≠ explained ≠ resolved

| Level | Failures | Basis |
|---|---|---|
| **Classified** (category and owner known) | **1,965 / 1,965** | §9.2 |
| **Explained and verified by this process** (throw site read, or re-run, or probed) | **381** | the 318 `app/` throws, the 11 factory/seeder throws, the 39 individually verified assertion items, and the 13 test-origin errors at owned defects |
| **Explained by error signature** (the cause is stated in the error itself; not individually re-run) | **1,265** | test-origin clusters (§8.4) |
| **Explained by code-reading agents** (not re-run) | **319** | drift-class assertion failures |
| **Resolved** (the obligation implemented and verified) | **0** | **no implementation has been authorised.** Resolution happens in the stories |

**Residual risk, named once:** a misclassified item among the 1,584 drift-class failures explained without a run could still hide a defect. **It will surface when 76–78 / 139 / 157 / 165–167 / 208 re-run each test during repair. That is where it is owned, so no further discovery pass is needed to manage it.**

### 9.4 Final lists

**A. New stories created in this backlog-completion series (frozen-baseline rule applied; the existing-story search is in each file)**

| ID | Defect | Ready for PO authorisation? |
|---|---|---|
| `PBDIGIT-209` | Membership fee payment cannot be recorded (`currency`, `payment_reference`, `income_id` missing from `membership_payments`) | **After a PO decision** on the column shape |
| `PBDIGIT-210` | Participant import fails on the template's own roles | **After a PO decision** on the role vocabulary |
| `PBDIGIT-211` | Scheduled jobs see no tenant data (fees, overdue, reminders, cache flush) | **After an ARB decision** on the tenant-context model for non-request execution |
| `PBDIGIT-212` | Election timeline pages return 403 to every user | **Yes:** fully specified, no decision needed |

**B. Proposed corrections to existing stories (NOT applied; the PO decides)**

| Story | Correction |
|---|---|
| 41 | third demo-provisioning path broken (non-UUID `demo_candidacies.user_id`, swallowed) |
| 158 | root cause located: `forStorage()` writes `issuance_requested_at` without an offset |
| 177 | R-73 `Jurisdiction` widening already implemented; strike it from the scope |
| 134 | wrong commit for one `PBDIGIT-69` file |
| 167 | re-link F-7C-4 → `PBDIGIT-120` |
| 188 | merge into 186 |
| 63 | F-3 extends to swallowed `ValidationException` (field errors never shown) |
| 195 | the canonical handler's governance matrix reads every tenant's levels |
| 96 | single-assign and import-preview eligibility ignores voting rights and expiry (evidence for H-7) |
| 29 | "the `Registered` listener creates the membership row" is false; the controller does |
| 37 | extend to per-test isolation on PostgreSQL (absent, and inconsistent per class: probed) |
| 74 | the `'platform'` slug item is verified; its repair is owned by 211 |

**C. Decisions required (no implementation story until decided)**

| Decision | Owner | Blocks |
|---|---|---|
| D-1: engineering and documentation items as PBDIGIT stories? | PO | classification of 10 stories, AD-007/009/010, the drift-repair stories |
| 209 payment column shape | PO | 209 |
| 210 participant role vocabulary | PO | 210 |
| **The tenant-context model for scheduled, console and queued execution** | **ARB** | 211 (and any queued-job equivalent) |
| Member-import column contract | PO | the member-import repair (no story yet, by rule) |
| H-7 canonical membership predicate | PO/ARB | 96 → the assignment-path repair (a candidate story after the decision) |
| Committee routes without `tenant` middleware: intended? | ARB | none found yet |
| Evidence-side bounded contexts · bounded-context set · Q-2 · COL-5a · Q1–Q4 · ADR-003 · committee/lineage capabilities · Q-a…Q-e · C-1 widening · `PBDIGIT-00` rule · Level-0 *challenge* | as recorded (Track 2; §4) | their stories |

**D. Engineering-only (not product backlog, unless D-1 says otherwise)**
- test-infrastructure isolation (B to 37);
- unreachable code (`UuidGovernanceDecisionIdGenerator`, the Fortify `LoginResponse`, `ElectionReadModel`);
- dead routes and 30 unrouted controllers;
- 68 `.bak` files;
- `DebugCandidacyTest`'s stray file;
- the broken redundant registration listener;
- stale stub docblocks.

**E. Deferred / candidate**
- merge/release to `main`;
- a unified runtime audit review capability;
- queued (non-scheduled) jobs and tenant context (the production queue driver was not inspected).

### 9.5 Ready for PO authorisation now (sufficiently specified, no open decision)

`PBDIGIT-212` · `PBDIGIT-201` · `PBDIGIT-202` · `PBDIGIT-158` (root cause now located) · and the test-repair stories `165`, `166`, `76`/`77`/`78`, `139` part A, `157`, which are fixture-only and already specified.

**Each needs PO authorisation, one story at a time, then RED → GREEN → business-state verification → acceptance.**

> **Discovery is closed at this point.** No further repository sweep is planned. New stories arise only from the repair work above, by the same rule.

---

**Traceability:**
- **Inputs:** the full-run JUnit report (2026-10-01; exception-origin parse, all 46 `app/` sites read) · Track 2 audit · the integrity review · the closure audit.
- **Re-runs:** `MembershipFeeControllerPayTest` 5 F · `MembershipPaymentServiceTest` 9 F · `FeePaymentTest` 8 F (fixture) · `DemoElectionAutoCreationTest` 3 F.
- **Code reads:** cited inline.
