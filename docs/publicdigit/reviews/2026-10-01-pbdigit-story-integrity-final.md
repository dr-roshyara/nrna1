# PBDIGIT inventory: final closure and convergence audit

| | |
|---|---|
| **Date** | 2026-10-01 |
| **Kind** | ⚠ authority: generated. **A convergence audit, not a discovery exercise.** It authorises nothing, implements nothing, and decides no product or governance question. |
| **Builds on** | [`2026-10-01-pbdigit-completeness-audit.md`](2026-10-01-pbdigit-completeness-audit.md) (`PBDIGIT-159`…`194`, later `195`…`207` in §5.8–§5.9) · [`2026-10-01-pbdigit-story-integrity-review.md`](2026-10-01-pbdigit-story-integrity-review.md) |
| **Prepared by** | `claude-code-session:42e0287e-e0e2-4638-8165-cd5d5862c578` (self-declared). **Independence caveat:** the same process wrote the two documents under audit. Every claim here was re-derived from the repository, and §16 and the corrections F-1…F-3 list what this process corrected in its own earlier work. **A fresh process would be stronger evidence.** |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/` |
| **Runtime probes** | Scratch probe tests were kept **outside the repository** (in the session scratch directory). They were run with `php artisan test --env=testing` against `nrna_test`, in a transaction (`RefreshDatabase`). **No file under `app/`, `tests/`, `routes/`, `config/` or `database/` was modified.** No dependency was installed, and `composer install` was not run. |
| ⚠️ **Correction (2026-10-01, `2026-10-01-backlog-completion-review.md` §8.5)** | The row above says the probes ran *"in a transaction (`RefreshDatabase`)"*. **That is false for probe classes inheriting `tests/TestCase.php`, which skips the transaction on PostgreSQL:** their rows remained in the **test** database `nrna_test`. The development database was never targeted. |

## 0. Human decision recorded (2026-10-01)

> **Delivery baseline frozen at PBDIGIT-208 (PO, 2026-10-01). New IDs only for a delivery obligation shown not to be covered by PBDIGIT-00…208.**

Recorded verbatim from the Product Owner's instruction. Track 2 (the Strategic Knowledge Traceability Audit) was authorised in the same instruction, as an audit and not an implementation. It creates no PBDIGIT ID.

## 1. Executive verdict

# **CLOSED WITH KNOWN GOVERNANCE ITEMS**

**Every PublicDigit delivery obligation discovered inside the defined boundary (§2) now has exactly one `PBDIGIT` story.** That covers 14 new stories since the integrity review's first set: `195`…`208`. Of these, `205`…`208` came from this audit, and `208` came from the full test run: **213 failing test files with no owner**, the largest omission found.

The baseline closes **with known items that are not engineering's to resolve**:
- decision **D-1** (are engineering and documentation items PBDIGIT stories at all?);
- the further decisions in §12;
- 3 groups classified **UNKNOWN**, which are not delivery obligations until a requirement exists (§5.1);
- one heuristic class of 99 tests whose concealment status is **owned** by `PBDIGIT-207`, not resolved (§6.3).

**The verdict covers the delivery inventory, not the knowledge history.** It does **not** establish that every outcome of the brainstorming phase and the Strategic DDD discovery corpus has an explicit disposition (realised · story · architecture decision · governance decision · deferred · rejected · exploratory hypothesis · unknown). That corpus was outside the boundary by design (§2). **The Brainstorming → Strategic DDD → Decision → Story traceability audit is recorded as the one open knowledge-to-delivery gap (§17).** Exploratory DDD hypotheses must not be converted into stories by default.

**The claim is bounded.** It is a fact about the sources in §2, as of this tree. It is not a claim that the software has no undiscovered defect. Two of two probed status-only tests hid a defect (§6.3); that is why `PBDIGIT-207` exists, and the claim holds only together with it.

## 2. Audit boundary

> **AUDIT BOUNDARY CLOSED.** The source classes below were each inspected systematically. Documents outside them were **not** followed recursively, even when they mentioned a TODO.

| # | Source class | Exactly what was searched | Method |
|---|---|---|---|
| S1 | Product backlog | `docs/publicdigit/backlog/README.md`, all 47 `PBDIGIT-*.md` files, the 6 `PBDIGIT-EPIC-*.md` files (`PBDIGIT-01`…`24`), the 76–157 backlog specs (82), the completeness audit specs (`159`…`207`) | script (IDs, links, homes, sources, acceptance) + reading |
| S2 | Product reviews | `docs/publicdigit/{reviews,implementation,election,election_management,architecture,adr}/` and PublicDigit files in `docs/plans/`: **485 documents** | scripted marker × ID scan (1,598 lines → 903 pairs → 158 hand-checked) |
| S3 | Engineering backlog | `docs/implementation/backlog/BACKLOG.md` (epics, ENG-001…015, Debt, Parked, NEXT ACTION) · `docs/implementation/Architecture_Debt_Backlog.md` (AD-001…010) · PB-007 IDD (F-7C-1…6) · WP records via the register · `PROGRAM_STATUS.md` | reading |
| S4 | Architecture and governance | ARB register `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (R-30…R-100) · **all 32 ADR files** in `docs/adr/`, `docs/publicdigit/adr/`, `engineering/architecture/adr/` (status of each, plus the accepted ones bearing on open stories) · `ADR-T-LOG` · `docs/implementation/PushB_Architecture_Blueprint.md` · EPIC-004 roadmap · WP-5/WP-6 plans · WP-6 closure report · `ELECTION_MANIFESTO.md` §9 · **BDRs: none exist in the repository** | reading + targeted greps |
| S5 | Code markers | `app/`, `routes/`, `config/`, `database/`, `resources/js`, `resources/views` (Blade, 0 hits), `lang/` (0 hits): `TODO`/`FIXME`/`HACK`/`TBD`/`@todo` (**60**) and `stub`/`placeholder`/`not implemented`/`temporarily` (34 lines) | `git grep`, every hit classified |
| S6 | Code structure | **every route** (474: 52 closures, 2 broken actions) · **every controller** (30 unrouted) · **every migration** (222: `up()` bodies) · **public operations of every event-recording aggregate** under `app/Contexts/*/Domain` (callers searched in `app/` and `routes/`) | a booted-app script; a brace-matched parser |
| S7 | Test estate | **the full `Unit` + `Feature` run: 6,762 tests, every failing file mapped (§7)** · `markTestSkipped`/`markTestIncomplete` (47) · test `TODO`s (11) · class/file-name match over **964** test files · `.bak` files (68) · **write-path tests asserting only a success status** (851 scanned, 101 flagged, 2 probed) · the merge-gate suites (from the Push B verification) | scripts + runs |

**Not in the boundary (stated, not hidden):** KnowledgeOS, `docs/knowledge*`, research and brainstorming folders · vendor code · built assets (`public/build`) · the 52 route closures, individually (only the voter-path legacy closures, P-5, were read) · documents reached only by following references out of S1–S7 · **the brainstorming corpus and the Strategic DDD discovery documents** (`docs/publicdigit/brainstorming/`, the EPIC-002/003 discovery set, the DDD programme archive in `.claude/memory/`), which are the subject of the separate traceability audit (§17).

## 3. Inventory statistics

```text
Existing PBDIGIT IDs before the audits (00–158, gaps 52–57):   153 IDs (154 story files/specs: PBDIGIT-69 is used twice)
New PBDIGIT stories (completeness audit → closure audit):        50  (159–208)
Total PBDIGIT IDs:                                              203  (highest: PBDIGIT-208)

Source items classified across S1–S7 (exact; the counting unit is per row of §8):  1,040
  A. DONE                         55
  B. EXISTING PBDIGIT            341   (includes the 153 existing IDs themselves and 122 failing test files)
  C. NEW PBDIGIT                 365   (→ 50 stories; includes 213 failing files → 208 and 99 tests → 207)
  D. ENGINEERING ONLY             72
  E. GOVERNANCE OWNED             78   (the EM-OPEN register counted once)
  F. KNOWLEDGEOS                  11
  G. OBSOLETE                    116   (68 .bak files, 30 unrouted controllers, 2 dead routes, …)
  H. UNKNOWN                       2   groups (§5.1)
Duplicates (two stories, one obligation):     0
Missing stories (obligation with no story):   0 inside the boundary (after 205–208)
Unclassified items:                           0
```

**The counting unit is the row of the §8 matrix:** an individual line, test or file where the sweep enumerated them, or a named group where the source names one. Each row's count is stated, so the totals add up exactly. **No item is unclassified.**

## 4. Story verification: `PBDIGIT-159`…`208`

**159…194:** the integrity review §3.2 verdicts stand, unchanged by this audit except where noted below. Each was re-checked for a source and an acceptance by script; all pass.

**195…208: each verified independently by this audit**, not taken from the agent sweep:

| ID | Source | Real? | Open? | PublicDigit delivery? | Dup? | Acceptance valid? | Verdict |
|---|---|---|---|---|---|---|---|
| 195 | `CommitteeRepository.php:16` + **`ADR_COMMITTEE_CREATION_DUAL_PATH.md` (ACCEPTED)** | ✅ **runtime-probed:** `201` + id, **0 rows** in all 10 committee tables (including `constitutional_committees`, `committee_governance_projections`) and in 7 event/outbox/audit tables. No `CommitteeEstablished` listener. The existing test asserts only the status. | ✅ | P (defect) | no | ✅ | **KEEP — re-framed (§4.1)** |
| 196 | roadmap §1 finding 1 | ✅ `git grep -i challenge routes/` → 0 | ✅ | P | no | ✅ | **KEEP** |
| 197 | roadmap §0(f); WP-6 closure §2 | ✅ `finalize()` 0 callers (aggregate-operation scan) | ✅ | P | no | ✅ | **KEEP** (blocked) |
| 198 | WP-6 plan `:84`, `:164` | ✅ `enforceHorizon()` 0 callers; no command or schedule | ✅ | P | no | ✅ | **KEEP** |
| 199 | WP-6 closure §2, Decision C | ✅ 0 `AdjudicationExpired` references in Contestation | ✅ | P | no | ✅ | **KEEP** (deferred) |
| 200 | roadmap §0(e); WP-6 closure §2 | ✅ no `Demand` concept | ✅ | P (gap tracked; modelling first) | no | ✅ the acceptance is the modelling output | **KEEP** (blocked) |
| 201 | session-3 record B-2; runtime reproduction `16e4a51a1` (2026-08-14) | ✅ `ElectionVotingController.php:113-115` still lacks the `:40-44` repair; the only later commit to the file (`606c362aa`) did not touch it | ✅ | P (defect) | no (distinct from 65's two sites) | ✅ | **KEEP** |
| 202 | session-3 record §4.3 | ✅ **runtime-probed:** `BadMethodCallException: Call to undefined method App\Models\ElectionMembership::bulkAssignVoters()` | ✅ | P (defect) | no | ✅ | **KEEP** |
| 203 | session-3 record B-6 | ✅ **runtime-probed:** an election-only import of two voters → redirect, flash *"0 users created, 0 already existing"*, **0 memberships**, no row error surfaced | ✅ | P | no | ✅ | **KEEP** (+ new evidence, §4.2) |
| 204 | ADR-2 Rule-8 gate Q1–Q5 | ✅ `ElectionRestored` constructed only at `FillCommitteeSeatHandler.php:156` from `$decision->gate()` (`:140`) | ✅ | P | no; 186 is ADR-1, 72 is the consumer | ✅ | **KEEP** (blocked) |
| 205 | ADR-T20; WP-5 plan | ✅ `dismiss`, `beginInvestigation` 0 callers; `CoordinatesContestation` has `raise`/`admit`/`route` only | ✅ | P | no | ✅ | **NEW — KEEP** |
| 206 | `Challenge::lapse()` docblock (Round 50-07 v1.2) | ✅ `lapse` 0 callers | ✅ | P | no; same class as 198, a different aggregate and window | ✅ | **NEW — KEEP** |
| 207 | §6.3 heuristic; 2 of 2 probes concealed defects | ✅ | ✅ | V (verification) | no; each concealment it finds gets its own story | ✅ | **NEW — KEEP** |
| 208 | §7 full run: 213 failing files with no owner | ✅ 9/10 sampled fail in isolation | ✅ | V (verification) | no; the file sets are disjoint from 76–78, 139, 157 and 167 by construction (§7.1) | ✅ | **NEW — KEEP** |

### 4.1 `PBDIGIT-195`: does the story describe the actual defect?

**Mostly, with one framing correction.** `ADR_COMMITTEE_CREATION_DUAL_PATH.md` (ACCEPTED, 2026-05-13) makes the canonical path a **parallel-validation path, "testable but not exposed to users"**. The live path is the legacy `CommitteeManagementController::store()`, which persists. So:
- **The defect is real and verified:** a registered endpoint answers 201 with an identifier for a committee that exists nowhere. That also defeats the ADR's own purpose, *"running new handler in parallel proves it works"*, because a path that persists nothing proves nothing.
- **The impact is narrower than "committees are lost":** no UI calls the endpoint, and real committees go through the legacy path.
- **The PO question becomes sharper:** is the ADR's migration (Phase 2 → 4) still intended? If yes, 195 is the prerequisite for Phase 2. If no, the endpoint should be retired. **Recorded in 195 (correction F-1, §16). No decision taken.**

### 4.2 The CsvVoterImport probe: one test, two findings

`CsvVoterImportTest::admin_can_import_voters_via_csv` passes on `assertRedirect()`.
- **Fixture drift:** the test's election resolves to the imported-registry (election-only) strategy, which expects `firstname;lastname;email`, but the test sends a bare `email` column.
- **The product defect it conceals is `203`'s:** every row fails, yet the officer sees only *"0 users created, 0 already existing"*. No row error is shown, the session carries no errors, and no membership is created.
- **Mapping:** `203` (evidence added, F-2) + `207` (the test class). Not a new story.

### 4.3 Specific verifications the closure prompt required

| Item | Result |
|---|---|
| **P-2 → 19 / 190** | `PBDIGIT-19` verifies that the count is correct; it does not create a counting model (its text: *"review this first (P-2)"*). The missing tally model belongs to the Voting epic **`PBDIGIT-190`**, whose recorded scope is *"ballot casting, anonymous storage, tally — migration + verifiability"*. **No duplication:** 19 is verification of today's behaviour, 190 is the future model. ✅ |
| **165 / AD-006** | Both `FeePaidProjectionTest` (5/5 fail) and `OutboxIntegrationTest` (3/3 fail) are in 165 after correction C-2. The cause is the same factory, so one story. ✅ |
| **167 / AD-008 / F-7C-4 / F-7C-5** | AD-008 (`CommitteeAssociationRepositoryTest` 11 fail, reproduced; `CommitteeMembershipApplicationTest`) and F-7C-5 → 167. F-7C-4 (`ValidateVotingIpTest`) → 167, but its fate is bound to `PBDIGIT-120`. **The re-link to 120 is proposed, still unapplied** (a PO scope act). **Re-measured by the full run (§7): 81 files / 362 failing tests in 167's named contexts** (correction F-3). The rest went to 208. |
| **201…204** | §4 table: each confirmed by this audit's own reading or probe. **No agent finding was accepted without re-check.** |

## 5. New in this audit: obligations found by the closure sweep

| New | Source class | What | Evidence |
|---|---|---|---|
| `PBDIGIT-205` | S6 aggregate-operation scan + S4 ADR-T20 | A raised challenge can never be dismissed or investigated | `Challenge::dismiss`, `::beginInvestigation`: 0 callers. `CoordinatesContestation` `:69`/`:86`/`:95` = raise/admit/route only |
| `PBDIGIT-206` | S6 + the `lapse()` ruling docblock | A challenge never lapses | `Challenge::lapse`: 0 callers; no command or schedule |
| `PBDIGIT-207` | S7 status-only scan | 99 write-path tests assert only a success status | 851 scanned, 101 flagged, **2 of 2 probed concealed a defect** (195, 203) |
| `PBDIGIT-208` | S7 full run | 1,345 failing tests in 213 files owned by no story | §7 |

### 5.1 UNKNOWN: classified, but not delivery obligations (yet)

| Item | Why UNKNOWN | What would resolve it |
|---|---|---|
| `Committee` governance-state operations: `dissolve`, `attachToParent`, `startTerm`, `extendTerm`, `updateMemberRole` (`Committee.php:1070-1117`) | **No caller, no route, and no recorded requirement found.** Not on the Level-0 journey. It may be legacy-path capability, which the dual-path ADR deprecates. | The PO states whether committee dissolution and terms are product capabilities. If yes, a story under `PBDIGIT-191`. |
| `MembershipLineage::reapplyInitialEpisode`, `::addEpisode` | No caller. May relate to `PBDIGIT-68`'s reinstatement rules (BR-1.x), **but the link is not established**, and a keyword match is not coverage. | `PBDIGIT-68`'s decisions say whether re-application is a capability. |
| *(heuristic class)* the 99 status-only tests | Each may or may not hide a defect | **Owned by `PBDIGIT-207`**, so not left unowned |

**An unused method is not an obligation.** Only `finalize` (roadmap §0(f)), `dismiss`/`beginInvestigation` (ADR-T20) and `lapse` (Round 50-07) became stories, because only they have an approved architecture that requires them to be reachable.

## 6. The sweeps, item by item

### 6.1 Inline markers (60): every one classified

**Correction to the integrity review:** it reported 61. The true count is **60**: 56 `TODO`/`FIXME`/`HACK`/`TBD` + 4 `@todo`. The 61st was `XXX` in a phone-number placeholder (`resources/js/Pages/Code/VoteDenied.vue:98`), and `GeographyAntiCorruptionLayer` has 10 markers, not 11.

**Totals:** C (NEW) 1 · E (GOVERNANCE) 23 · D (ENGINEERING) 25 · G (OBSOLETE) 11 · **unclassified 0**.

| File:line | Marker | Class | Existing PBDIGIT | New PBDIGIT | Reason |
|---|---|---|---|---|---|
| `app/Application/Election/Services/ConstitutionalTransitionGuard.php:260` | // TODO: Replace with actual payment authorization check when billing  | E. GOVERNANCE | EPIC-03 (`PBDIGIT-09` verification) | — | billing not built; threshold is the open `EM-OPEN-019`; PO question Q-a |
| `…/Geography/Application/Services/GeographyAntiCorruptionLayer.php:75` | // TODO: Phase 2 - Implement using DDD repository | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Geography/Application/Services/GeographyAntiCorruptionLayer.php:92` | // TODO: Phase 2 - Implement using DDD repository | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Geography/Application/Services/GeographyAntiCorruptionLayer.php:104` | // TODO: Phase 2 - Implement using DDD repository | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Geography/Application/Services/GeographyAntiCorruptionLayer.php:121` | // TODO: Phase 2 - Implement using DDD repository | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Geography/Application/Services/GeographyAntiCorruptionLayer.php:133` | // TODO: Phase 2 - Implement using DDD repository | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Geography/Application/Services/GeographyAntiCorruptionLayer.php:145` | // TODO: Phase 2 - Implement using DDD repository | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Geography/Application/Services/GeographyAntiCorruptionLayer.php:157` | // TODO: Phase 2 - Implement using DDD repository | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Geography/Application/Services/GeographyAntiCorruptionLayer.php:269` | // TODO: Phase 1 - Implement using DDD with detailed error messages | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Geography/Application/Services/GeographyAntiCorruptionLayer.php:287` | // TODO: Phase 3 - Implement using DDD caching strategy | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Geography/Application/Services/GeographyAntiCorruptionLayer.php:300` | // TODO: Phase 2 - Implement using DDD repository | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Geography/Infrastructure/Database/Seeders/NepalGeographySeeder.php:201` | // TODO: Add remaining 69 districts for other provinces | E. GOVERNANCE | — | — | reference-data coverage target undefined (Q-c) |
| `…/Geography/Infrastructure/Database/Seeders/NepalGeographySeeder.php:275` | // TODO: Add remaining ~748 local levels | E. GOVERNANCE | — | — | reference-data coverage target undefined (Q-c) |
| `…/Geography/Infrastructure/Database/Seeders/NepalGeographySeeder.php:353` | // TODO: Add remaining ~6,691 wards for other local levels | E. GOVERNANCE | — | — | reference-data coverage target undefined (Q-c) |
| `…/Geography/Infrastructure/Database/Seeders/NepalGeographySeeder.php:439` | // TODO: Add remaining villages/toles for all wards | E. GOVERNANCE | — | — | reference-data coverage target undefined (Q-c) |
| `…/Geography/Infrastructure/Repositories/EloquentGeoUnitRepository.php:82` | // TODO: Load from Country model or configuration | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Governance/Application/Services/GovernanceGeoUnitQueryService.php:24` | * 4. TODO: Integrate TenantGeographyProfile for tenant-type level rang | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Membership/Application/Membership/Query/MyCommitteesQueryService.php:52` | // TODO: Query application status to distinguish pending/accepted/reje | D. ENGINEERING | — | — | fields not consumed by any page (`git grep joinedDate resources/js` → 0) |
| `…/Membership/Application/Membership/Query/MyCommitteesQueryService.php:64` | joinedDate: null, // TODO: Query from lineage | D. ENGINEERING | — | — | fields not consumed by any page (`git grep joinedDate resources/js` → 0) |
| `…/Membership/Application/Membership/Query/MyCommitteesQueryService.php:65` | roleInCommittee: null, // TODO: Query from lineage | D. ENGINEERING | — | — | fields not consumed by any page (`git grep joinedDate resources/js` → 0) |
| `…/Membership/Application/Requests/CreateMemberRequest.php:45` | // TODO: Implement proper authorization logic | G. OBSOLETE | — | — | dead code (no references), or already done (`ElectionPolicy` exists) |
| `…/Membership/Application/Services/MobileMemberRegistrationService.php:82` | // TODO: Fix PostgreSQL JSON query syntax issue - temporarily skipped  | G. OBSOLETE | — | — | unreachable: route loading commented out, `MembershipServiceProvider.php:586` |
| `…/Membership/Application/Services/TenantUserProvisioningService.php:203` | * @todo Implement email sending (Week 2) | E. GOVERNANCE | — | — | welcome email not built; requirement unconfirmed (Q-d) |
| `…/Membership/Application/Services/TenantUserProvisioningService.php:207` | // @todo Week 2: Integrate with email service | E. GOVERNANCE | — | — | welcome email not built; requirement unconfirmed (Q-d) |
| `…/Membership/Domain/Committee/Policies/GovernanceMatrix.php:40` | geoLevel: $gov, // diagonal seed — awaits geo_level column migration @ | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Membership/Domain/Committee/Strategies/CentralCommitteeStructure.php:70` | // TODO: Add membership duration check when Member entity supports it | E. GOVERNANCE | — | — | committee-wing criteria are "MVP: accept all"; binding only if the PO says so (Q-b) |
| `…/Membership/Domain/Committee/Strategies/StudentWingStructure.php:52` | // TODO: Implement student status validation when Member entity suppor | E. GOVERNANCE | — | — | committee-wing criteria are "MVP: accept all"; binding only if the PO says so (Q-b) |
| `…/Membership/Domain/Committee/Strategies/StudentWingStructure.php:58` | // TODO: Implement age validation when Member entity supports it | E. GOVERNANCE | — | — | committee-wing criteria are "MVP: accept all"; binding only if the PO says so (Q-b) |
| `…/Membership/Domain/Committee/Strategies/WomenWingStructure.php:47` | // TODO: Implement gender validation when Member entity supports it | E. GOVERNANCE | — | — | committee-wing criteria are "MVP: accept all"; binding only if the PO says so (Q-b) |
| `…/Membership/Domain/Committee/Strategies/WomenWingStructure.php:54` | // TODO: Implement age validation when Member entity supports it | E. GOVERNANCE | — | — | committee-wing criteria are "MVP: accept all"; binding only if the PO says so (Q-b) |
| `…/Membership/Domain/Committee/Strategies/YouthWingStructure.php:51` | // TODO: Implement age validation when Member entity supports it | E. GOVERNANCE | — | — | committee-wing criteria are "MVP: accept all"; binding only if the PO says so (Q-b) |
| `…/Membership/Domain/Models/Member.php:533` | * MVP placeholder - TODO: Calculate from date_of_birth field | E. GOVERNANCE | — | — | placeholder attributes feeding Q-b |
| `…/Membership/Domain/Models/Member.php:538` | // TODO: Calculate age from date_of_birth field | E. GOVERNANCE | — | — | placeholder attributes feeding Q-b |
| `…/Membership/Domain/Models/Member.php:545` | * MVP placeholder - TODO: Return gender from personal_info | E. GOVERNANCE | — | — | placeholder attributes feeding Q-b |
| `…/Membership/Domain/Models/Member.php:550` | // TODO: Return gender from personal_info | E. GOVERNANCE | — | — | placeholder attributes feeding Q-b |
| `…/Membership/Domain/Models/Member.php:557` | * MVP placeholder - TODO: Calculate from joined_date | E. GOVERNANCE | — | — | placeholder attributes feeding Q-b |
| `…/Membership/Domain/Models/Member.php:562` | // TODO: Calculate membership duration from joined_date | E. GOVERNANCE | — | — | placeholder attributes feeding Q-b |
| `…/Membership/Domain/Models/Member.php:569` | * MVP placeholder - TODO: Check student status | E. GOVERNANCE | — | — | placeholder attributes feeding Q-b |
| `…/Membership/Domain/Models/Member.php:574` | // TODO: Check student status from personal_info or dedicated field | E. GOVERNANCE | — | — | placeholder attributes feeding Q-b |
| `…/Membership/Infrastructure/Http/Controllers/Mobile/MemberController.php:108` | // TODO: Implement in future iteration | G. OBSOLETE | — | — | unreachable: route loading commented out, `MembershipServiceProvider.php:586` |
| `…/Membership/Infrastructure/Http/Requests/Desktop/RegisterMemberRequest.php:89` | // TODO: Fix PostgreSQL column detection issue | G. OBSOLETE | — | — | unreachable: route loading commented out, `MembershipServiceProvider.php:586` |
| `…/Membership/Infrastructure/Http/Requests/Mobile/RegisterMemberRequest.php:63` | // TODO: Custom validation causes PostgreSQL error - needs investigati | G. OBSOLETE | — | — | unreachable: route loading commented out, `MembershipServiceProvider.php:586` |
| `…/Membership/Infrastructure/Http/Requests/Mobile/RegisterMemberRequest.php:74` | // TODO: Custom validation causes PostgreSQL error - needs investigati | G. OBSOLETE | — | — | unreachable: route loading commented out, `MembershipServiceProvider.php:586` |
| `…/Membership/Infrastructure/Http/Resources/Desktop/DesktopMemberResource.php:135` | 'can_edit' => true, // TODO: Replace with policy check | G. OBSOLETE | — | — | unreachable: route loading commented out, `MembershipServiceProvider.php:586` |
| `…/Membership/Infrastructure/Jobs/CleanupExpiredInvitations.php:30` | // TODO Phase 3C: Replace with InvitationRepositoryInterface::findExpi | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Membership/Infrastructure/Jobs/CleanupExpiredInvitations.php:31` | // TODO Phase 3C: Use InvitationAggregate for state management | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Membership/Infrastructure/Jobs/MarkOverdueMembers.php:31` | // TODO Phase 3C: Replace with FeeRepositoryInterface::findOverdueForT | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Membership/Infrastructure/Jobs/MarkOverdueMembers.php:32` | // TODO Phase 3C: Use FeeAggregate::markOverdue() method | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Membership/Infrastructure/Jobs/SendRenewalReminder.php:31` | // TODO Phase 3C: Replace with FeeRepositoryInterface::findExpiringFor | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Membership/Infrastructure/Jobs/SendRenewalReminder.php:32` | // TODO Phase 3C: Iterate through TenantContext::allTenants() for mult | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Membership/Infrastructure/Jobs/SendRenewalReminder.php:48` | // TODO Phase 3C: Use MailService instead of Mail facade directly | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `…/Membership/Infrastructure/Repositories/CommitteeRepository.php:16` | // TODO: Implement persistence to committees table | C. NEW | — | 195 | no-op save on the bound port of a routed endpoint (runtime-verified) |
| `…/Membership/Infrastructure/Repositories/MemberDirectoryRepository.php:13` | // TODO: Implement persistence to member_directory table | G. OBSOLETE | — | — | dead code (no references), or already done (`ElectionPolicy` exists) |
| `…/Shared/Infrastructure/Outbox/OutboxRepository.php:11` | // TODO: Query outbox for unprocessed events of given type | G. OBSOLETE | — | — | dead code (no references), or already done (`ElectionPolicy` exists) |
| `…/Shared/Infrastructure/Outbox/OutboxRepository.php:17` | // TODO: Update outbox record to mark as processed | G. OBSOLETE | — | — | dead code (no references), or already done (`ElectionPolicy` exists) |
| `app/Domain/Election/Enum/VoterSourceStrategy.php:34` | * @see Phase 4 Exploration: final names (ImportedVoterAuthority, Membe | E. GOVERNANCE | — | — | authority names "TBD via governance review" |
| `app/Domain/Election/Enum/VoterSourceStrategy.php:141` | * Phase 4 will return stable constitutional-authority strings (TBD via | E. GOVERNANCE | — | — | authority names "TBD via governance review" |
| `app/Http/Controllers/Committee/CreateCommitteeController.php:28` | // @todo: When geo_level column is added to geo_administrative_units, | D. ENGINEERING | — | — | refactor or interim seam over working code; no product outcome |
| `app/Models/ElectionMembership.php:25` | * TODO: Create ElectionPolicy for policy-based authorization (separate | G. OBSOLETE | — | — | dead code (no references), or already done (`ElectionPolicy` exists) |
| `app/Services/GaneshStandardFormula.php:113` | * TODO: Validate thresholds empirically with user surveys and project  | D. ENGINEERING | — | — | product research (threshold calibration), not delivery |


### 6.2 Stub and placeholder lines (48 lines in 31 files; 14 overlap the markers above)

The 34 lines not already counted as markers are classified in the matrix (§8), with S5 rows. The notable ones:
- **`GeographyContextAdapter`** is bound for `GeoContextPort` and is **permissive** ("allow all references"), so the **live** legacy committee-creation path never validates geography. That is a deliberate interim ("Phase C"), not a defect without a requirement → **PO question Q-e**.
- **`TenantAuthProvisioningAdapter`** calls itself a "stub", but `createForCsvImport` creates real `User` rows. Its docblock is stale (D).
- `StubCommitteeGeoPathProvider` and `StubMembershipApplicationRepository` are **bound nowhere** (G).

### 6.3 Status-only write tests: the "HTTP success ≠ business success" class

The heuristic found tests that POST, PUT, PATCH or DELETE, assert only a success status, and contain no assertion on persisted state, events or counts. It flagged **101 of 851**. It deliberately over-includes previews and negative cases, which may legitimately persist nothing. **Two were probed at runtime, and both hid a failure:**

| Test | HTTP | Business outcome | Owner |
|---|---|---|---|
| `Committee/CreateCommitteeApiTest::test_creates_committee_successfully` | 201 + id | **0 rows in 17 related tables** | `PBDIGIT-195` |
| `Election/CsvVoterImportTest::admin_can_import_voters_via_csv` | 302 redirect | **0 memberships; no row error surfaced** | `PBDIGIT-203` (fixture drift + concealed defect) |

The other 99 are listed in **appendix A** and owned by **`PBDIGIT-207`**. The highest-risk among them, by business consequence: `VoteSubmissionWorkflowTest::test_user_can_submit_complete_vote` (already characterised by A2 `PBDIGIT-80`), `ElectionDashboardAccessTest::test_chief_can_publish_results` (`PBDIGIT-60`), `MembershipRenewalTest::member_can_self_renew_within_window`, `MembershipFeeCreateTest::test_store_redirects_to_fees_index_on_success`, `OrganisationRoleControllerTest::test_add_member_returns_success_redirect` and `CommitteeManagementTest::test_admin_can_update_committee_name_via_http`.

### 6.4 Code structure

| Check | Result | Class |
|---|---|---|
| Routes (474) whose action class or method is missing | **2:** `GET profile/edit → UserController@editProfile` and `GET admin/ip-stats → CodeController@getIPStatistics`. Both are registered, **neither is linked from any page or component**, and a hit returns 500. | G (dead routes) |
| Controllers with no route | **30** (legacy `Deligate*`, `ElectionController`, `VoterSlugController`, Finance `Income`/`Outcome`, the Membership `Desktop`/`Mobile` HTTP layer, and others) | G |
| Migrations (222) with an empty `up()` | **2**, both documented no-ops (`2026_04_13_000000`, `2026_05_20_085627`) | A |
| Committed `.bak` files under `tests/` | **68** | G |
| A test that writes outside the test sandbox | **`tests/Feature/DebugCandidacyTest.php:42`** writes the response HTML to the hard-coded Windows path `C:\Users\nabra\…\debug_response.txt`. On Linux that creates a stray file at the **repository root**. The file appeared during this audit's full run and **was removed** (untracked, never staged). | G (debug test) |
| Test class ≠ file name (964 files) | **1** genuine (`OverlayInfluenceTest`, `PBDIGIT-166`) + 2 false positives (a helper class declared first) | B / A |

## 7. Full `Unit` + `Feature` run: known broken tests

**Command:** `vendor/bin/phpunit --testsuite=Unit,Feature --log-junit <scratch>` against `nrna_test` (`phpunit.xml:70`), 2026-10-01, one process, about 70 minutes.

```text
Tests: 6,762 · Assertions: 18,485 · Errors: 1,589 · Failures: 376 · Skipped: 31 · Incomplete: 17 · Risky: 3,921
→ 1,965 failing tests in 335 files
```

**Is this order contamination?** No, overwhelmingly. Ten failing files were re-run **one at a time**: **9 fail identically** (`VoteSubmissionWorkflowTest` 43/43 · `TenantIsolationTest` 33/33 · `VoterControllerAccessibilityTest` 25/25 · `RealVoteEnforcementTest` 23/23 · `VotingSecurityServiceTest` 16/16 · `EmergencyDashboardControllerTest` 14/14 · `ElectionVoterManagementTest` 11/11 · `VotingMembershipIntegrationTest` 6/6 · `OrganisationGeographicScopeTest` 6/6). **One is order-dependent:** `PublicApplicationApprovalTest` fails 6 combined and passes alone.

**Dominant causes (database errors, from the messages):** 263 *column does not exist* · 256 *duplicate key* · 190 *NOT NULL violation* · 112 *invalid input syntax for type uuid* · 99 *unrecognized configuration parameter* · 45 *check constraint* · 15 *foreign key*. These are **schema and fixture drift**: tests written against columns, integer IDs and session settings the schema no longer has. Non-database errors: 144 `Error` · 135 `BadMethodCallException` · 103 `TypeError` · 95 `ArgumentCountError`.

### 7.1 Every failing file has exactly one owner

| Owner | Files | Failing tests | Basis |
|---|---|---|---|
| `PBDIGIT-76` | 10 | 110 | files named in its spec |
| `PBDIGIT-77` | 11 | 89 | files named in its spec |
| `PBDIGIT-78` | 5 | 19 | files named in its spec |
| `PBDIGIT-139` | 3 | 7 | files named in its spec (its own baseline: 7, exactly matched) |
| `PBDIGIT-157` | 2 | 7 | files named in its spec (its own baseline: 4 F + 3 E, exactly matched) |
| `PBDIGIT-158` | 1 | 1 | `IssuanceContextRoundTripTest` |
| `PBDIGIT-160` / `183`–`186` | 6 | 16 | the EM-IMPL-002 RED suite |
| `PBDIGIT-165` | 2 | 8 | AD-006 files |
| `PBDIGIT-167` | 1 + **81** | 1 + **362** | F-7C-4; then files in its named contexts (**re-measured**: July counted 74) |
| **`PBDIGIT-208` (NEW)** | **213** | **1,345** | **owned by no story until now** |
| **Total** | **335** | **1,965** | |

**The 76/77/78, 139 and 157 baselines are confirmed by an independent run.** C-1's recorded baseline of **218** failing tests is matched exactly (110 + 89 + 19), and so are 139's 7 and 157's 7. The full run found **no failing file outside these owners and 208**.

**Without `PBDIGIT-208`, C3 would have failed.** 213 failing files had no owner. That is the largest single omission the convergence audit found, and it is why the full run was required before the verdict.

## 8. Complete source → PBDIGIT matrix

Every classified source item, grouped where identical. **Counts are exact** (they sum to the totals in §3). S1…S7 are the boundary classes of §2.

| Class | Source | Item | Count | PBDIGIT | Note |
|---|---|---|---|---|---|
| A. DONE | S2 | Resolved later (CF-3, Q-TEN-2, N-1/N-2, G-2a, G-7, BND-2, V-7, EM-BRQ, CG-1/2) | 9 | — |  |
| A. DONE | S3 | F-1 | 1 | — |  |
| A. DONE | S3 | F-2 | 1 | — |  |
| A. DONE | S3 | F-7C-2 | 1 | — |  |
| A. DONE | S3 | Root CLAUDE.md tech table | 1 | — |  |
| A. DONE | S3 | NEXT ACTION literature review | 1 | — |  |
| A. DONE | S3 | AD-001…AD-005 | 1 | — |  |
| A. DONE | S4 | EPIC-001 | 1 | — |  |
| A. DONE | S4 | EPIC-002 (widened) | 1 | — |  |
| A. DONE | S4 | EPIC-003 (as used) | 1 | — |  |
| A. DONE | S4 | WP-1 | 1 | — |  |
| A. DONE | S4 | WP-2 | 1 | — |  |
| A. DONE | S4 | WP-3A | 1 | — |  |
| A. DONE | S4 | WP-4A | 1 | — |  |
| A. DONE | S4 | WP-4B | 1 | — |  |
| A. DONE | S4 | WP-5 | 1 | — |  |
| A. DONE | S4 | WP-6 (scoped items) | 1 | — |  |
| A. DONE | S4 | WP-7 | 1 | — |  |
| A. DONE | S4 | R-99 closure | 1 | — |  |
| A. DONE | S4 | EM-IMPL-001, GREEN-1…4, H-2, EM-DOM-001 Act B | 4 | — |  |
| A. DONE | S4 | ADRs ACCEPTED/APPROVED/DECIDED, no open delivery consequence found | 19 | — |  |
| A. DONE | S6 | Migrations with empty up() (both documented no-ops) | 2 | — |  |
| A. DONE | S7 | Skipped: whitespace canonicalization gap (now passes) | 1 | — |  |
| A. DONE | S7 | Class/file mismatch false positives (helper class first) | 2 | — |  |
| B. EXISTING | S1 | PBDIGIT-00…158 (existing IDs) | 153 | themselves |  |
| B. EXISTING | S1 | P-1 · P-3 | 2 | 00/36 · 60 |  |
| B. EXISTING | S1 | P-2 | 1 | 19 + 190 |  |
| B. EXISTING | S1 | P-5 (route precedence; closure disposal unowned until 17/135 report) | 1 | 17 + 135 |  |
| B. EXISTING | S2 | Owned elsewhere (UQ-1…4, M-1, BND-1…, V-8, BR-1.x, AD-2, counting, Q-UC5, N-3…6, EM-OPEN-018) | 10 | 185·183·186·72·68·49·19·184·69·50 |  |
| B. EXISTING | S3 | F-7C-3 | 1 | 70 |  |
| B. EXISTING | S4 | GREEN-5 label · EM-GOV-063 · N-3…N-6 | 3 | 73 · 72 · 69 |  |
| B. EXISTING | S4 | ADR_COMMITTEE_CREATION_DUAL_PATH (ACCEPTED, Phase 2–4 open) | 1 | 195 | re-frames 195 |
| B. EXISTING | S5 | Terminal-state placeholder (TerminalStatePlaceholder, RecoveryPeriodExpired ×2, ExpiryConsequence ×2, ElectionLevelCancellation) | 6 | 72 |  |
| B. EXISTING | S5 | Evidence classification 'always Initial (placeholder)' ×2 | 2 | 105 · 108 |  |
| B. EXISTING | S5 | CoordinatesAdjudication:73 chain-start comment | 1 | 174 |  |
| B. EXISTING | S5 | ElectionConstitution suspend placeholder target | 1 | 146 |  |
| B. EXISTING | S5 | D02AggregateConstitutionalDivergence 'return empty (simulation)' | 1 | 137 |  |
| B. EXISTING | S6 | AuthorityAssignment never delegated or revoked (whole aggregate unwired) | 1 | 191 | discovery input for the Appointment epic |
| B. EXISTING | S7 | Skipped: VoterVerification ×5 · topology ×10 · device ×3 | 18 | 106/108 · 105–108/114 · 91/106 |  |
| B. EXISTING | S7 | Skipped: CommitteeAssociation audit pins ×2 | 2 | 167 |  |
| B. EXISTING | S7 | Test TODOs: VoterVerification ×5, CommitteeAssociation ×2 (same tests as above) | 7 | 106/108 · 167 |  |
| B. EXISTING | S7 | Test TODO: OverlaySemantics dual-axis | 1 | 150 |  |
| B. EXISTING | S7 | Class/file mismatch: OverlayInfluenceTest | 1 | 166 |  |
| B. EXISTING | S7 | Status-only write tests probed: CreateCommitteeApiTest · CsvVoterImportTest | 2 | 195 · 203 |  |
| B. EXISTING | S7 | Risky notices (112) | 1 | 162 |  |
| B. EXISTING | S7 | Merge-gate failures: Architecture ×3 · RED suite ×16 · issuance marker ×1 | 3 | 123/156 · 160/183–186 · 158 |  |
| B. EXISTING | S7 | Full run, failing files owned by named stories (76 ×10 · 77 ×11 · 78 ×5 · 139 ×3 · 157 ×2 · 158 ×1 · RED ×6 · 165 ×2 · F-7C-4 ×1) | 41 | 76·77·78·139·157·158·160/183–186·165·167 |  |
| B. EXISTING | S7 | Full run, failing files in 167's named contexts (362 tests) | 81 | 167 | re-measured |
| C. NEW | S1 | P-4 · P-6 | 2 | 172 · 173 |  |
| C. NEW | S2 | B-2 · bulkAssignVoters · B-6 | 3 | 201 · 202 · 203 |  |
| C. NEW | S3 | ENG-002 | 1 | 163 | pending D-1 where class E |
| C. NEW | S3 | ENG-003 | 1 | 194 | pending D-1 where class E |
| C. NEW | S3 | ENG-004 | 1 | 164 | pending D-1 where class E |
| C. NEW | S3 | ENG-005 | 1 | 170 | pending D-1 where class E |
| C. NEW | S3 | ENG-006 | 1 | 168 | pending D-1 where class E |
| C. NEW | S3 | ENG-010 | 1 | 169 | pending D-1 where class E |
| C. NEW | S3 | ENG-012 + F-7C-6 + F-WP6R-1 | 1 | 162 |  |
| C. NEW | S3 | ENG-013 | 1 | 160 |  |
| C. NEW | S3 | ENG-014 | 1 | 161 | pending D-1 where class E |
| C. NEW | S3 | ENG-015 | 1 | 159 | pending D-1 where class E |
| C. NEW | S3 | AD-006 (2 test files) | 1 | 165 |  |
| C. NEW | S3 | F-7C-1 | 1 | 166 |  |
| C. NEW | S3 | F-7C-4 + F-7C-5 + AD-008 | 1 | 167 |  |
| C. NEW | S3 | Parked: Domain Model Catalogue | 1 | 171 | pending D-1 where class E |
| C. NEW | S3 | Health row: CI run / deployment doc | 1 | 159 · 173 |  |
| C. NEW | S4 | WP-3B | 1 | 174 |  |
| C. NEW | S4 | WP-4C-1 D3/D4 | 1 | 175 |  |
| C. NEW | S4 | WP-4C-2 Q1–Q4 | 1 | 176 |  |
| C. NEW | S4 | WP-4D | 1 | 177 |  |
| C. NEW | S4 | R-91 transaction boundary | 1 | 178 |  |
| C. NEW | S4 | PM-6 confirmation | 1 | 179 |  |
| C. NEW | S4 | ER-08 | 1 | 180 |  |
| C. NEW | S4 | R-75 ChallengeRaised | 1 | 181 |  |
| C. NEW | S4 | WP-8 | 1 | 182 |  |
| C. NEW | S4 | Epic numbering | 1 | 189 |  |
| C. NEW | S4 | Epics Voting/Appointment/ReadModels/Migration | 4 | 190–193 |  |
| C. NEW | S4 | Roadmap: challenge entry point | 1 | 196 |  |
| C. NEW | S4 | Roadmap §0(f) finality | 1 | 197 |  |
| C. NEW | S4 | WP-6 horizon trigger | 1 | 198 |  |
| C. NEW | S4 | WP-6B | 1 | 199 |  |
| C. NEW | S4 | Roadmap §0(e) demand deadlines | 1 | 200 |  |
| C. NEW | S4 | ADR-2 restoration provenance | 1 | 204 |  |
| C. NEW | S4 | ADR-T20 dismiss/investigate | 1 | 205 |  |
| C. NEW | S4 | Challenge lapse (Round 50-07) | 1 | 206 |  |
| C. NEW | S4 | EM-IMPL-002 GREEN-5/6/7, ADR-1 normalization, EM-DOM-001 C/D, pin docblock | 6 | 183–188 |  |
| C. NEW | S5 | CommitteeRepository no-op save | 1 | 195 |  |
| C. NEW | S6 | Uncalled aggregate operations: finalize · dismiss/beginInvestigation · lapse | 4 | 197 · 205 · 206 |  |
| C. NEW | S7 | Status-only write tests not yet probed | 99 | 207 |  |
| C. NEW | S7 | Full run, failing files owned by no story (1,345 tests) | 213 | 208 |  |
| D. ENGINEERING ONLY | S2 | Manifesto drift (EM-OPEN-001/002/004 resolved but shown open) | 1 | — | pending D-1 |
| D. ENGINEERING ONLY | S2 | B-5 entitlement pins never executed | 1 | — | blocked on Manifesto IDs; pending D-1 |
| D. ENGINEERING ONLY | S3 | ENG-001 | 1 | — | frozen methodology |
| D. ENGINEERING ONLY | S3 | ENG-007 | 1 | — | assistant tooling |
| D. ENGINEERING ONLY | S3 | AD-007 · AD-009 · AD-010 | 3 | — | V-class verification drift; story only if D-1 = yes |
| D. ENGINEERING ONLY | S5 | Geography ACL ×10 · GeoUnit repo · GovernanceGeoUnit · jobs ×7 · geo_level ×2 · MyCommittees ×3 · Ganesh formula | 25 | — |  |
| D. ENGINEERING ONLY | S5 | Elections policy interface stubs ×5 · SetupTestElectionCommand ×2 · TenantAuthProvisioningAdapter stale docblock ×2 · CommitteeAssociation F3.3 reserved | 10 | — |  |
| D. ENGINEERING ONLY | S6 | Uncalled accessors on aggregates (tenantId, scope, validity, …) | 1 | — |  |
| D. ENGINEERING ONLY | S7 | Skipped: environment/driver/missing-directory/mail/rate-limit | 26 | — |  |
| D. ENGINEERING ONLY | S7 | Test TODOs: ContextBoundary after P12 ×2 · Vitest gap ×1 | 3 | — |  |
| E. GOVERNANCE | S1 | Method candidates C-1…C-17, OQ-M1, OQ-M2, OQ-2 | 20 | — |  |
| E. GOVERNANCE | S2 | BND-3 · start-of-voting decisions · lifecycle-window decision · SCB items · EM-OPEN-006/007/008 · Q-2 | 6 | — |  |
| E. GOVERNANCE | S2 | EM-OPEN register (36–41 open) | 1 | — | stories should cite them by number |
| E. GOVERNANCE | S3 | 38D-02 | 1 | — | ARB governance artifact |
| E. GOVERNANCE | S4 | COL-5a (R-96) | 1 | — | owner vacated; returned to governance |
| E. GOVERNANCE | S4 | R-99 v1.x spec gap | 1 | — |  |
| E. GOVERNANCE | S4 | WP-6 closure §3 register (AT-EVT-001, DC-1, H-1…3, A-1/2/4, B/C items) | 9 | — |  |
| E. GOVERNANCE | S4 | ADRs PROPOSED (repo separation, rule-ownership migration, architecture authority, PKS ADR-001 + companions) | 6 | — | decisions pending; PKS items are KnowledgeOS-adjacent |
| E. GOVERNANCE | S4 | ADR-T13 crypto E2E verifiability (DEFERRED) | 1 | 190 (future home) |  |
| E. GOVERNANCE | S4 | Blueprint §Integrity deferral | 1 | 190 |  |
| E. GOVERNANCE | S5 | Payment stub · wing criteria ×6 · Member placeholders ×8 · Nepal seeder ×4 · welcome email ×2 · VoterSourceStrategy TBD ×2 | 23 | — | PO questions Q-a…Q-d; governance naming |
| E. GOVERNANCE | S5 | Placeholder names D-7/EM-OPEN-045 (OperatingCore commands ×5, NonCanonicalEventName) | 6 | — |  |
| E. GOVERNANCE | S5 | OrganisationalAppointmentAuthority port: no adapter by design | 1 | — |  |
| E. GOVERNANCE | S5 | GeographyContextAdapter permissive (geography never validated on committee creation) | 1 | — | PO question Q-e |
| F. KNOWLEDGEOS | S2 | KnowledgeOS / session-tooling registers | 8 | — |  |
| F. KNOWLEDGEOS | S3 | ENG-008 | 1 | — | KnowledgeOS/knowledge-platform tooling |
| F. KNOWLEDGEOS | S3 | ENG-009 | 1 | — | KnowledgeOS/knowledge-platform tooling |
| F. KNOWLEDGEOS | S3 | ENG-011 | 1 | — | KnowledgeOS/knowledge-platform tooling |
| G. OBSOLETE | S5 | Unreachable Membership HTTP layer ×6 · dead classes ×5 | 11 | — |  |
| G. OBSOLETE | S5 | Unbound stubs StubCommitteeGeoPathProvider ×2 · StubMembershipApplicationRepository ×2 · Mobile 501 ×1 | 5 | — |  |
| G. OBSOLETE | S6 | Routes with missing controller method (profile/edit, admin/ip-stats; not linked from the UI) | 2 | — |  |
| G. OBSOLETE | S6 | Unrouted controllers | 30 | — |  |
| G. OBSOLETE | S7 | .bak test files committed | 68 | — |  |
| H. UNKNOWN | S6 | Committee governance-state operations (dissolve, attachToParent, startTerm, extendTerm, updateMemberRole) uncalled, unrouted | 1 | — | no recorded requirement found |
| H. UNKNOWN | S6 | MembershipLineage reapplyInitialEpisode/addEpisode uncalled | 1 | — | may relate to 68's reinstatement rules; not established |


## 9. Complete PBDIGIT → source matrix

**`PBDIGIT-00`…`158`:** every ID has a home (an individual file, an epic heading, or a spec in the 76–157 backlog), and every home names its origin. This was checked by script:
- all 47 story files carry a source, finding, traceability or evidence line;
- all 82 specs in `2026-09-30-constitutional-runtime-pbdigit-backlog.md` carry a *Problem* (or *Question*) and an *Acceptance* (or *Decision*);
- `PBDIGIT-01`…`24` cite route → code evidence in their epic files (product review of 2026-08-05).

**`PBDIGIT-159`…`208`:** each row is generated from the story's own *Linkage* cell.

| PBDIGIT | Title | Source (linkage) | Status | Acceptance |
|---|---|---|---|---|
| `PBDIGIT-159` | Programme status integrity: the health table, the epic table and the NEXT ACTION | `ENG-015` (`docs/implementation/backlog/BACKLOG.md`) · evidence `docs/implementation/2026-10-01-push-b-status-verification.md` | ✅ | ✅ |
| `PBDIGIT-160` | The merge gate cannot pass while an authorised RED-first increment is open (deci | `ENG-013` · EM-IMPL-002 RED suite `1f4b4c5f3` · `docs/publicdigit/reviews/2026-09-30-UC-4-GREEN-5-decision-record.md` | ✅ | ✅ |
| `PBDIGIT-161` | Greenfield PHPStan regression: 22 errors in Election OperatingCore | `ENG-014` · `phpstan-greenfield.neon` · ADR-1 Rule-8 gate `docs/publicdigit/implementation/2026-08-18-EM-IMPL-002-rule8-gate-adr1.md` | ✅ | ✅ |
| `PBDIGIT-162` | Risky notices in `GreenfieldCore` have grown 57 → 112 with an unestablished caus | `ENG-012` (101 → 105 during WP-7B-R1) **and** `F-7C-6` (57 risky in PB-007 7C). They are merged because they concern **the same signal**: two IDs, one problem. | ✅ | ✅ |
| `PBDIGIT-163` | Shared Infrastructure (Outbox/Inbox) is outside static analysis and fails PHPSta | `ENG-002` (opened from the PB-003-C2 finding; ER-03) | ✅ | ✅ |
| `PBDIGIT-164` | First mutation-score ratchet on the correction loop (MSI 50 %) | `ENG-004` · A-3 baseline-then-ratchet policy · F-7D-2 (IDD §2e) | ✅ | ✅ |
| `PBDIGIT-165` | Finance tests crash: Membership and Shared each define their own `TenantId` | `AD-006` (BACKLOG.md Debt — "FeeTestFactory TenantId mismatch", owner Membership, Open) | ✅ | ✅ |
| `PBDIGIT-166` | A test class name does not match its file, so its tests never run | `F-7C-1` (PB-007 IDD §2c-ii) | ✅ | ✅ |
| `PBDIGIT-167` | Broken unit and feature tests in the Membership, Elections, Geography and Govern | `F-7C-5` (PB-007 IDD §2c-ii: 997 tests, 69 errors, 5 failures in one broad run) · `F-7C-4` (`ValidateVotingIpTest::it_blocks_request_when_ip_does_not_match`: **re-veri… | ✅ | ✅ |
| `PBDIGIT-168` | The engineering platform (gate scripts, pre-push, CI role gate) has no developer | `ENG-006` (documentation debt; EG-001…EG-003, 2026-07-26) | ✅ | ✅ |
| `PBDIGIT-169` | Documentation indexes promise 30 documents that were never written | `ENG-010` (ARB classification 2026-08-01: an integrity defect, with debt as its consequence) | ✅ | ✅ |
| `PBDIGIT-170` | Programme documentation consolidation (Stream B), trigger-based | `ENG-005` (ARB 2026-07-11: the rule was adopted as an Engineering Standard; the sweep is decoupled, Low priority, and **trigger-based**) | ✅ | ✅ |
| `PBDIGIT-171` | Domain Model Catalogue: the July catalogue is stale and owned by no one | BACKLOG.md "Parked — docs candidates" · `.claude/memory/ddd_program_state.md:1237` ("recommended by audit doc — NOT yet created") | ✅ | ✅ |
| `PBDIGIT-172` | The voter journey and the election lifecycle use disjoint vocabularies | README "Product gaps" **P-4** (`EPIC-05`/`06`) · source review `docs/publicdigit/reviews/2026-08-05-election-process-review-phase1.md` | ✅ | ✅ |
| `PBDIGIT-173` | Deployment documentation: the P-6 claim "none exists" is no longer literally tru | README "Product gaps" **P-6** · BACKLOG Program Health "Production readiness 🟡 — deployment doc still missing" | ✅ | ✅ |
| `PBDIGIT-174` | WP-3B: the determination issuance still mints a fresh provenance chain instead o | Roadmap WP-3 → **WP-3B** · R-67 ("WP-3B remains DEFERRED") · `2e75fc69e` ("WP-3B closure verification FAILS") · R-71 (critical path: "dispose of WP-3B") · `86e2b9fe2` … | ✅ | ✅ |
| `PBDIGIT-175` | WP-4C-1 D3/D4: `AdjudicationFailureDeclared` is not in the Event Catalog and is  | WP-4C-1 (R-88, R-89, R-93: D1 + D2 accepted) · R-97 (D3 and D4 HELD) · PROGRAM_STATUS gate **D** | ✅ | ✅ |
| `PBDIGIT-176` | WP-4C-2: the Contestation Domain Owner has not answered Q1–Q4 | R-88, R-90, R-97, R-98, R-100 · PROGRAM_STATUS gates **A** and **B** | ✅ | ✅ |
| `PBDIGIT-177` | WP-4D: no authority can deliver an adjudication decision (intake port + interim  | Roadmap WP-4D · R-68 · R-72 ("expressly NOT AUTHORIZED") · R-73 (`receiveRulingDecision()` must widen to carry Jurisdiction) · R-87, R-89, R-91 | ✅ | ✅ |
| `PBDIGIT-178` | The issuance path writes the determination and the outbox row in two separate tr | R-91 (Event D review: decision sound, realisation fails on one path) · R-92 (review adopted; **repair not authorised**) · PROGRAM_STATUS "Transaction-boundary repair —… | ✅ | ✅ |
| `PBDIGIT-179` | PM-6's issuance-confirmation half is allocated to no work package | R-76 (excluded from WP-4B) · R-82 ("does not allocate PM-6's confirmation half — that remains OPEN") · R-83/R-84 | ✅ | ✅ |
| `PBDIGIT-180` | `enforceHorizon()` is not isolated per process (ER-08) | ER-08 · R-95 (**planning authorised, implementation NOT**) · PROGRAM_STATUS gate **C** | ✅ | ✅ |
| `PBDIGIT-181` | `ChallengeRaised` publication: the adopted path (R-75) is annotated as prohibite | R-75 (adoption + ⛔ annotation 2026-08-03) · R-86, R-87 · ADR-UL-01, ADR-T16 | ✅ | ✅ |
| `PBDIGIT-182` | WP-8: the correction loop has never been validated end to end over the real path | Roadmap **WP-8** · R-79 (DEFERRED pending closure of §WP-4; "no implementation work shall commence") · R-97 (reconfirmed) | ✅ | ✅ |
| `PBDIGIT-183` | GREEN-5: report-period expiry (UC-4) is pinned RED and unimplemented | EM-IMPL-002 · `ReportPeriodExpiryHandler.php:33` (pending guard) · `ReportPeriodExpiryHandlerRedTest` ×4 · `PBDIGIT-73` | ✅ | ✅ |
| `PBDIGIT-184` | GREEN-6: recording a committee's constitution (UC-5) is pinned RED and unimpleme | EM-IMPL-002 · `RecordCommitteeConstitutionHandlerRedTest` ×3 · guide 02 `Q-UC5` STOP ("at GREEN-6") | ✅ | ✅ |
| `PBDIGIT-185` | GREEN-7: the four operating-core query services (UQ-1…UQ-4) are pinned RED and u | EM-IMPL-002 · `QueryServicesRedTest` ×5 · stubs `GateIntervalStateQuery`, `ProgressionEligibilityQuery`, `ClockReadingQuery`, `RecoveryPeriodStatusQuery` (also 6 of `P… | ✅ | ✅ |
| `PBDIGIT-186` | ADR-1 absent-aggregate normalization: the required domain slice does not exist,  | ADR-1 (DECIDED 2026-08-18) §6(b)/(c) · Rule-8 gate `docs/publicdigit/implementation/2026-08-18-EM-IMPL-002-rule8-gate-adr1.md` (BLOCKED) · `BND-1` (OPEN) · pin `Absent… | ✅ | ✅ |
| `PBDIGIT-187` | EM-DOM-001 Acts C and D (operational-status adapter and caller) are not authoris | EM-DOM-001 (Phase 1 accepted as analysis; **Act B COMPLETE** `216552aa7`, `b54074900`, 2026-08-19) · roadmap steer "operational-status B → C → D as a separate track" (… | ✅ | ✅ |
| `PBDIGIT-188` | A binding RED pin's docblock says the ADR "decision block is blank"; it has been | `AbsentAggregateReferenceRedTest` docblock · CONTEXT-publicdigit 2026-09-30 ("recommended as a separate one-line doc fix … the pin is binding and out of scope here") | ✅ | ✅ |
| `PBDIGIT-189` | Reconcile the programme epic numbering: EPIC-003/004 were reused, EPIC-005/006 a | BACKLOG.md epic table + renumbering note (`e51178f73`, 2026-07-10) · `PublicDigit_Engineering_Protocol_Proposal.md:6` (PROPOSED, implies EPIC-005 = Evidence …) · `.cla… | ✅ | ✅ |
| `PBDIGIT-190` | Roadmap epic: Voting migration and verifiability (formerly EPIC-003) | BACKLOG.md epic row "Voting (ballot casting, anonymous storage, tally — migration + verifiability)" · `PBDIGIT-EPIC-05` | ✅ | ✅ |
| `PBDIGIT-191` | Roadmap epic: Appointment / Governance — delegates, mandates, authority chains ( | BACKLOG.md epic row · the `Committee` and `Governance` contexts exist in `app/Contexts/` | ✅ | ✅ |
| `PBDIGIT-192` | Roadmap epic: Read Models and Public Transparency (formerly EPIC-005) | BACKLOG.md epic row · product gaps P-2/P-3 (count and publication: `PBDIGIT-19`, `PBDIGIT-60`) | ✅ | ✅ |
| `PBDIGIT-193` | Roadmap epic: Election/Lifecycle migration of the legacy operational contexts (E | BACKLOG.md epic row ("sequenced at the retrospective") · `PBDIGIT-58` (legacy election-state migration) · Track A1 (`PBDIGIT-139`…`157`) | ✅ | ✅ |
| `PBDIGIT-194` | Reference value objects (`*Ref`) were never reviewed for consistent naming and s | `ENG-003` (opened from the PB-004 step-1 ARB review, 2026-07-08; ER-03) · ADR-UL-01 (`TargetRef` → `ContestedOutcomeRef`) | ✅ | ✅ |
| `PBDIGIT-195` | Creating a committee through the canonical endpoint returns 201 and persists not | Inline sweep: `app/Contexts/Membership/Infrastructure/Repositories/CommitteeRepository.php:16` ("TODO: Implement persistence") | ✅ | ✅ |
| `PBDIGIT-196` | No one can file a challenge: the correction loop has no product entry point | `docs/implementation/EPIC-004_Architecture_to_Implementation_Roadmap.md` §1 finding 1: *"The entry point that lets a real actor file a challenge (UI/API) is product wo… | ✅ | ✅ |
| `PBDIGIT-197` | No determination ever becomes final: the finality evaluator was never built | Roadmap §0 scope item **(f)** "the finality evaluator (temporal policy; `finalize()` gains its caller)" and WP-6 row · WP-6 closure `engineering/verification/reports/2… | ✅ | ✅ |
| `PBDIGIT-198` | The adjudication horizon is never enforced in production: nothing calls `enforce | WP-6 plan `.claude/plans/WP-6-temporal-machinery.md:84` ("Scheduled command(s) to drive the above", roadmap "timer execution") and `:164` (*"Inert today (nothing calls… | ✅ | ✅ |
| `PBDIGIT-199` | WP-6B: Contestation never reacts to `AdjudicationExpired` | WP-6 closure §2: *"Contestation consumer of `AdjudicationExpired` — Deferred by authority — Decision C ⇒ WP-6B"* · R-89 annotation ("announcing does not require the co… | ✅ | ✅ |
| `PBDIGIT-200` | Evidence-demand deadlines: the roadmap requires them, and the domain has no `Dem | Roadmap §0 scope item **(e)** "demand deadlines" and WP-6 row · WP-6 closure §2: *"Evidence-demand deadlines (§80/PM-3) — Outside WP-6 scope — Domain Modelling Gap; no… | ✅ | ✅ |
| `PBDIGIT-201` | Starting the vote still hides a valid voter's membership behind the ambient tena | Finding **B-2**, `docs/publicdigit/reviews/2026-08-15-session3-implementation-record-and-outstanding-work.md` §4.1 (*"the third ambient-scoped lookup … runtime-reprodu… | ✅ | ✅ |
| `PBDIGIT-202` | Full Membership voter import always fails: `ElectionMembership::bulkAssignVoters | Session-3 record §4.3 (*"the broken `ElectionMembership::bulkAssignVoters()` import path, which is a guaranteed exception"*, deliberately out of scope then) | ✅ | ✅ |
| `PBDIGIT-203` | Voter import integrity: swallowed row errors, non-atomic writes, no assignment a | Finding **B-6**, session-3 record §4.1: boundary-plan **Slices 2–4** (*"import integrity (row errors are silently swallowed; writes are non-atomic; no `assigned_by`/`a… | ✅ | ✅ |
| `PBDIGIT-204` | ADR-2 restoration provenance: the domain cannot say why an election was restored | ADR-2 Rule-8 gate `docs/publicdigit/reviews/2026-08-18-EM-IMPL-002-rule8-gate-adr2.md` (Q1–Q5, all 🔴) · Act-B design map `…-act-b-domain-design-map.md:268` (DEP-6…DEP-… | ✅ | ✅ |
| `PBDIGIT-205` | A raised challenge can be admitted but never dismissed or put under investigatio | ADR-T20 (ARB domain decision: `Raised→Admitted→Investigating→Routed→Adjudicated→Resolved`) · WP-5 plan `.claude/plans/WP-5-raise-path.md:39,75` (wired `raise`/`admit`/… | ✅ | ✅ |
| `PBDIGIT-206` | A challenge never lapses: the ruled timeout has no trigger | `Challenge::lapse()` docblock (*"System-initiated timeout. Allowed only before a decision. Per Round 50-07 v1.2 the Lapsed timeout emits NO domain event … Audit observ… | ✅ | ✅ |
| `PBDIGIT-207` | 99 write-path feature tests assert only a success status, so they cannot detect  | Closure audit §6.3 (heuristic over `tests/Feature`: 851 tests that POST/PUT/PATCH/DELETE, 101 assert only a success status). **Two were probed, and both conceal a real… | ✅ | ✅ |
| `PBDIGIT-208` | 1,345 failing tests in 213 files belong to no story: the legacy test estate outs | Closure audit §7: the full `Unit` + `Feature` run of 2026-10-01 (`vendor/bin/phpunit --testsuite=Unit,Feature` against `nrna_test`): **6,762 tests · 1,589 errors · 376… | ✅ | ✅ |


**Stories without a source: 0. Stories without status or acceptance: 0. Two stories for one obligation: 0.**

## 10. Duplicate and numbering problems

| Check | Result |
|---|---|
| Duplicate IDs | **`PBDIGIT-69` only.** `…-voter-eligibility-cache-is-tenant-unaware.md` was added **2026-08-12** (`d930cc9e4`), has the README row and carries implementation history under that ID (`5d46498e7`, "(PBDIGIT-65)…cache identity restored"). `…-operating-core-post-verification-items.md` was added **2026-08-17** (`50d1ff1b1`). **By precedence and use, the cache-key story is the authoritative holder; the operating-core file is the later collision.** Resolution (renumbering the later file and updating its citations; `PBDIGIT-69` appears in 38 files) is **`PBDIGIT-134`'s scope**. Nothing is renamed or deleted here. |
| Duplicate file ownership | none other |
| README rows → files | **0 orphans.** Every `](PBDIGIT-…md)` link resolves. |
| Files → README rows | **0 missing** (after the audit's 8 rows and the review's `69` row) |
| Number gaps | `52`…`57`, deliberate; not filled |
| Highest ID | **`PBDIGIT-208`** after this audit |
| Factual error found in `PBDIGIT-134` | Its problem text says both `PBDIGIT-69` files came from `50d1ff1b1`. **Git shows the cache-key file was added in `d930cc9e4` (2026-08-12) and the operating-core file in `50d1ff1b1` (2026-08-17).** It is recorded here and not edited: 134's text is generated from the backlog's ticket data, and the correction belongs in that source at the next regeneration. |

## 11. Epic integrity

Unchanged since the integrity review §10, and still **UNKNOWN / DECISION REQUIRED** (`PBDIGIT-189`):
- **Authoritative source:** `BACKLOG.md:30` (renumbering `e51178f73`, 2026-07-10): 002 Evidence · 003 Voting · 004 Appointment · 005 Read Models · 006 legacy migration.
- **Contradicting practice:** EPIC-003 and EPIC-004 were reused (the tactical assessment, then Adjudication) without a ruling. `PublicDigit_Engineering_Protocol_Proposal.md:6` (PROPOSED) implies another scheme.
- **Depending on the decision:** `190`…`193` (opening and numbering), `159` (the epic table and NEXT ACTION), and the `.claude/CLAUDE.md` focus line.
- **This audit uses no programme epic number as settled.** Stories reference epics by title, and product epics by `PBDIGIT-EPIC-0n`.

## 12. Governance decisions and the stories they block

| Decision | Owner | Is there a delivery outcome once decided? | Stories |
|---|---|---|---|
| **D-1** product-backlog scope (README:6 vs the 123–133 precedent) | PO | n/a: classification | 159, 161 (residue), 163, 164, 168, 169, 170, 171, 188, 194 · whether AD-007/009/010, manifesto drift and B-5 get stories |
| Gate vs RED-first | ARB | yes | 160 → 161, 164 |
| Q1–Q4 | Contestation Domain Owner | yes (after a new commission, R-100) | 176 |
| PM-6 allocation | ARB | yes | 179 |
| R-75 contradiction | ARB | yes | 181 |
| UC-4 A/B/C | PO/ARB | yes | 183 |
| `Q-UC5` | PO | yes | 184 |
| Epic numbering | ARB | n/a: structure | 189 → 190…193 |
| Finality integration contract | ARB | yes | 197 |
| Horizon trigger vs R-91 | ARB | yes | 198 (and 206, which is the same class) |
| Challenge filing and admissibility contract | PO | yes | 196, 205 |
| WP allocation for the Contestation lifecycle remainder | ARB | yes | 205, 206 |
| Canonical committee path still intended (dual-path ADR Phase 2–4)? | PO | yes: repair or retire | 195 |
| Import Slices 2–4 grant | PO | yes | 203 |
| Re-link F-7C-4 → 120 · merge 188 → 186 | PO | scope only | 167, 188 |
| Widen C-1 (`76`–`78`) to the 690 voting & demo failures that 208 owns? | PO | scope only | 76–78, 208 |
| **Brainstorming → Strategic DDD → Decision → Story traceability audit: commission it?** | PO | knowledge-to-delivery closure | (§17) |
| Q-a billing · Q-b wing criteria · Q-c geography coverage · Q-d welcome email · Q-e geography validation | PO | **only if the PO makes them requirements**; until then, no story (governance-owned) | — |
| `PBDIGIT-00` "no story VERIFIED" rule still in force? · Level-0 journey gains *challenge*? | PO | product definition | — |
| Proposed ADRs (repository separation, rule-ownership migration, architecture authority) · ADR-T13 crypto verifiability · BND-3 · start-of-voting decisions 1–6 · lifecycle window · SCB items · EM-OPEN register | ARB / PO | **no delivery story until decided** (the governance boundary, §14 of the prompt) | — |

## 13. Stories ready for future authorisation (none authorised)

| Readiness | Stories |
|---|---|
| **Specified, unblocked, single-site, reproducible: the strongest candidates** | **201** (`start()` membership scope; runtime-reproduced) · **202** (undefined `bulkAssignVoters`; runtime-probed) · **158** (issuance marker shift) |
| Specified, unblocked | 162 (plan authorised, R-95) · 165 · 166 · 167 · 172 · 173 · 207 · 208 (classification first) · and, **subject to D-1**, 159 · 163 · 164 (after 160) · 168 · 169 · 171 · 194 |
| Specified; one PO answer first | 195 · 196 · 203 · 205 |
| Decision required | 160 · 176 · 179 · 181 · 183 · 184 · 189 · 197 · D-1 |
| Blocked or held by recorded governance | 174 · 175 · 177 (after 178) · 178 · 180 · 182 · 185 · 186 · 187 · 198 · 199 · 200 · 204 · 206 (allocation) |
| Not yet specified (by design: opened by discovery) | 190 · 191 · 192 · 193 · 170 (dormant) · 161 (residue) |

## 14. Final convergence test

| | Criterion | Result | Basis |
|---|---|---|---|
| **C1** | Source coverage | ✅ **PASS** | S1–S7 searched (§2). The exclusions are stated. |
| **C2** | Classification completeness | ✅ **PASS** | 0 unclassified (§3, §8). Exactly one class per row. |
| **C3** | Story ownership | ✅ **PASS, bounded** | Every obligation discovered inside S1–S7 has exactly one story. **All 335 failing test files have exactly one owner** (§7.1, after 208). The 99-test heuristic class is owned by 207. The 2 UNKNOWN groups are not established obligations (§5.1). |
| **C4** | Story legitimacy | ⚠️ **PASS WITH A KNOWN ITEM** | Every story traces to a real source (§9), but **10 stories' legitimacy as *product* stories depends on D-1**. Recorded, not resolved. |
| **C5** | No unexplained duplicates | ✅ **PASS** | 0 obligations with two stories. Proposed (not applied): F-7C-4 → 120, 188 → 186. |
| **C6** | Traceability | ✅ **PASS** | 50/50 new stories have linkage, status and acceptance. 82/82 specs and 47/47 files have sources. |
| **C7** | Number and index integrity | ✅ **PASS with a recorded historical issue** | `PBDIGIT-69` collision (owned by 134); 0 orphans; 0 missing rows |
| **C8** | Governance separation | ✅ **PASS** | No decision became a software story without a delivery outcome. Q-a…Q-e, the proposed ADRs and the EM-OPEN register are kept governance-owned. |
| **C9** | No implementation | ✅ **PASS** | No file under `app/`, `tests/`, `routes/`, `config/` or `database/` changed (`git status`, §16). Probes ran from scratch, outside the repository. One stray file written by a test during the full run (§6.4) was removed. |

> ## **PBDIGIT INVENTORY BASELINE = CLOSED WITH KNOWN GOVERNANCE ITEMS**
>
> The known items are **D-1** (it decides whether 10 stories are product stories at all) and the decisions in §12. **They do not reopen discovery.** They change how existing, traced stories are classified, not whether an obligation is owned.

## 15. Explicit limitations

1. **Bounded claim.** "Every discovered obligation has one story" holds inside S1–S7. **It is not a claim that no undiscovered defect exists.** `PBDIGIT-207` exists precisely because 2 of 2 probed status-only tests hid one.
2. **Not individually inspected:** 50 of the 52 route closures (only the voter-path legacy closures were read) · 99 status-only tests (owned by 207) · the domain-operation scan covered **event-recording aggregates only**, not every service or entity.
3. **Documentation sweep (S2):** the scripted scan keyed on a finding ID next to an unresolved marker. **A finding written as prose with no ID and no marker would be missed.** 158 rows were hand-checked; the other ~745 pairs were filtered as noise by rule (the OperatingCore "OPEN" gate states).
4. **Counts on dated evidence, not re-run:** `PBDIGIT-164` (MSI baseline), `169` (2026-08-01 link scan).
5. **Same-process audit.** See the independence caveat in the header.
6. **KnowledgeOS, research and brainstorming material** were excluded by instruction. Ambiguous items were classified F, not ticketed.
7. **The full run is one process.** One order-dependent file was found among 10 sampled. Others may exist among the 325 not re-run individually. Classifying order-dependence is part of `PBDIGIT-208`'s and `167`'s scope.
8. **The brainstorming corpus and the Strategic DDD discovery documents were not in the boundary.** This audit closes the *delivery* inventory, not the *knowledge-to-delivery* chain (§17).

## 16. Master records updated (documentation only)

| Record | Change |
|---|---|
| `2026-10-01-pbdigit-completeness-audit.md` | §5.9 with `205`…`208` · F-1 (195 re-framed by the dual-path ADR; runtime probe) · F-2 (203 runtime evidence) · F-3 (167 re-measured) · the "superseded in part" note extended |
| `2026-10-01-pbdigit-story-integrity-review.md` | additive correction: 60 inline markers, not 61 |
| `docs/publicdigit/backlog/README.md` | rows `205`…`208`; the section range is now `159`…`208`; a pointer to this audit and its verdict |
| `.claude/sessions/2026-10-01.md` · `.claude/CONTEXT.md` | session entry; current state |

**Not changed:** any file under `app/`, `tests/`, `routes/`, `config/`, `database/`; any ADR or ruling; the generated 76–157 backlog (the `PBDIGIT-134` git-hash error is recorded in §10, not edited); `.claude/CLAUDE.md`. **Nothing committed.**

## 17. Exact next step

**The delivery inventory is frozen as the baseline: `PBDIGIT-00`…`208`, CLOSED WITH KNOWN GOVERNANCE ITEMS.** From here the question is no longer *"which TODOs are missing?"* but *"which registered story is authorised next?"* Two human acts come before any implementation:

1. **D-1 (PO):** do engineering and documentation items get PBDIGIT stories? This settles the classification of 10 stories, and whether AD-007/009/010, the manifesto drift and B-5 get stories.
2. **Decide whether to commission the Brainstorming → Strategic DDD → Decision → Story traceability audit.** Its unit is a *substantive outcome* of the brainstorming phase or the Strategic DDD discovery. Each outcome gets exactly one disposition:
   - A. realised;
   - B. an existing PBDIGIT story;
   - C. a new PBDIGIT story;
   - D. an architecture or DDD decision, no story;
   - E. a governance decision, no story;
   - F. deferred or held;
   - G. rejected or superseded;
   - H. an exploratory hypothesis, intentionally not a requirement;
   - I. unknown, a human decision.

   **Exploratory hypotheses must not become stories by default.** Until that audit runs, the honest statement is: *the delivery inventory is closed; the knowledge-to-delivery chain is not yet shown closed.*

**After those, the strongest first implementation candidates** (none authorised) are `PBDIGIT-201` and `PBDIGIT-202`: live, single-site, runtime-confirmed defects that can be repaired RED-first. Their acceptance must verify the **business state** (memberships admitted, voters imported), not only the HTTP response. That is the lesson of 195, 203 and 207.

**Proposed engineering rule (recorded, not adopted; adoption is a governance act):** *A state-changing endpoint is not verified by HTTP success. Acceptance must verify the intended business state transition, and, where applicable, persistence, emitted events, audit evidence and invariant preservation.*

---

**Traceability:**
- **Probes** (scratch, not in the repository):
  - `Probe195CommitteePersistenceTest`;
  - `Probe202BulkAssignTest`;
  - `ProbeCsvImportOutcomeTest`;
  - `route_check.php`;
  - the full-run JUnit report.
- **Sources:** §2.
- **Prior documents:** the completeness audit and the integrity review.

## Appendix A — the 99 status-only write-path tests owned by `PBDIGIT-207`

The heuristic deliberately over-includes previews and negative cases. Triaging them is 207's scope.

1. `Admin/ElectionApprovalTest.php::unauthenticated_user_cannot_access_admin_routes`
2. `Admin/ElectionApprovalTest.php::cannot_approve_election_not_in_pending_state`
3. `Admin/GovernanceLevelControllerTest.php::store_allows_same_level_number_for_different_tenants`
4. `Auth/EmergencyDashboardControllerTest.php::test_logout_from_emergency_dashboard`
5. `Auth/LogoutTest.php::test_logout_route_accepts_post_request`
6. `Auth/LogoutTest.php::test_logout_redirects_after_success`
7. `Auth/LogoutTest.php::test_unauthenticated_user_cannot_logout`
8. `Auth/LogoutTest.php::test_multiple_logout_requests_are_safe`
9. `Auth/LogoutTest.php::test_logout_requires_csrf_protection`
10. `Auth/VoterInvitationSetPasswordTest.php::redirect_fails_if_election_slug_is_missing`
11. `AuthenticationTest.php::test_users_can_authenticate_using_the_login_screen`
12. `CandidacyApplicationTest.php::test_guest_cannot_submit_application`
13. `CandidacyApplicationTest.php::test_non_member_cannot_submit_application`
14. `CodeControllerSlugRedirectTest.php::code_submission_redirects_to_slug_based_agreement_url`
15. `CodeControllerSlugRedirectTest.php::code_submission_without_slug_redirects_to_regular_agreement_url`
16. `CodeControllerSlugRedirectTest.php::invalid_code_redirects_to_slug_based_create_url`
17. `CodeControllerSlugRedirectTest.php::user_with_existing_voting_session_redirects_to_slug_based_agreement`
18. `CodeControllerTest.php::test_has_voted_blocks_submit_agreement`
19. `Contexts/Membership/CommitteeMembershipApplicationTest.php::test_apply_for_committee_membership_residence_success`
20. `Contexts/Membership/CommitteeMembershipApplicationTest.php::test_apply_for_committee_membership_exception_with_justification`
21. `Contexts/Membership/CommitteeMembershipApplicationTest.php::test_apply_for_committee_membership_manual`
22. `Contexts/Membership/CommitteeMembershipApplicationTest.php::test_review_application_approve`
23. `Contexts/Membership/CommitteeMembershipApplicationTest.php::test_review_application_reject`
24. `DeleteAccountTest.php::test_user_accounts_can_be_deleted`
25. `Demo/AgreementSubmissionTest.php::test_agreement_submission_redirects_to_vote_page`
26. `Demo/FormSubmissionEndpointTest.php::test_demo_election_form_submits_to_demo_endpoint`
27. `Demo/FormSubmissionEndpointTest.php::test_demo_election_slug_form_submits_to_slug_demo_endpoint`
28. `DemoSetupApiTest.php::test_organisation_member_can_trigger_demo_setup`
29. `DemoSetupApiTest.php::test_demo_setup_returns_stats_after_success`
30. `DemoSetupApiTest.php::test_organization_voter_can_trigger_demo_setup`
31. `Election/CandidacyPhotoManagementTest.php::test_deputy_can_update_candidate_photo_before_voting_starts`
32. `Election/ConstitutionalVotingProtectionTest.php::test_direct_post_to_vote_store_blocked_when_not_voting_active`
33. `Election/CsvVoterImportTest.php::csv_import_validates_email_format`
34. `Election/CsvVoterImportTest.php::csv_import_skips_already_assigned_voters`
35. `Election/CsvVoterImportTest.php::csv_import_respects_membership_mode`
36. `Election/CsvVoterImportTest.php::csv_import_handles_large_files_gracefully`
37. `Election/ElectionDashboardAccessTest.php::test_chief_can_publish_results`
38. `Election/ElectionSettingsControllerTest.php::active_election_with_votes_requires_confirmation`
39. `Election/ElectionSettingsControllerTest.php::active_election_with_confirmation_allows_update`
40. `Election/ElectionSettingsTest.php::test_whitelisted_ip_bypasses_restriction`
41. `Election/ElectionShowControllerTest.php::start_for_non_member_redirects_with_error`
42. `Election/ElectionShowControllerTest.php::start_for_already_voted_redirects_with_info`
43. `Election/ElectionVoterSuspensionTest.php::test_cannot_propose_suspension_when_already_proposed`
44. `Election/TimelineEditing/TimelineCapabilityAuthorizationTest.php::can_edit_timeline_in_draft_state`
45. `Election/TimelineEditing/TimelineCapabilityAuthorizationTest.php::can_edit_timeline_in_approved_state`
46. `Election/TimelineEditing/TimelineCapabilityAuthorizationTest.php::can_edit_timeline_in_setup_state`
47. `Election/TimelineEditing/TimelineCapabilityAuthorizationTest.php::authorization_uses_lifecycle_snapshot_not_old_state_machine`
48. `Election/VoterEligibilityTest.php::test_import_preview_marks_eligible_member_as_valid`
49. `Election/VoterEligibilityTest.php::test_import_preview_marks_unpaid_member_as_invalid`
50. `Election/VoterEligibilityTest.php::test_import_preview_marks_user_without_member_record_as_invalid`
51. `Election/VoterEligibilityTest.php::test_import_preview_marks_expired_member_as_invalid`
52. `Governance/Api/CommitteeMemberApiTest.php::test_remove_member_triggers_domain_command`
53. `Governance/Api/CommitteeMemberApiTest.php::test_remove_nonexistent_member_is_safe`
54. `Governance/Ui/CommitteeMemberManagerTest.php::test_component_can_remove_member_via_api`
55. `Import/OrganisationUserImportTest.php::preview_shows_valid_rows`
56. `Import/OrganisationUserImportTest.php::import_validates_required_fields`
57. `Membership/CommitteeManagementTest.php::test_admin_can_remove_member_from_committee_via_http`
58. `Membership/CommitteeManagementTest.php::test_guest_cannot_remove_member`
59. `Membership/CommitteeManagementTest.php::test_admin_can_update_committee_name_via_http`
60. `Membership/CommitteeManagementTest.php::test_guest_cannot_update_committee`
61. `Membership/FeePaymentTest.php::guest_cannot_record_payment`
62. `Membership/FeeWaiverTest.php::guest_cannot_waive`
63. `Membership/MembershipFeeCreateTest.php::test_store_redirects_to_fees_index_on_success`
64. `Membership/MembershipRenewalTest.php::member_can_self_renew_within_window`
65. `Membership/OrganisationRoleControllerTest.php::test_add_member_returns_success_redirect`
66. `Membership/ParticipantImportErrorMessagesTest.php::test_error_message_for_nonexistent_user_includes_invite_instruction`
67. `Membership/ParticipantImportErrorMessagesTest.php::test_error_message_for_invalid_participant_type_shows_valid_options`
68. `Membership/ParticipantImportErrorMessagesTest.php::test_error_message_for_invalid_date_format_shows_expected_format`
69. `Membership/ParticipantImportErrorMessagesTest.php::test_error_message_for_past_expiry_date_includes_todays_date`
70. `Membership/ParticipantImportErrorMessagesTest.php::test_error_message_for_invalid_json_shows_example`
71. `Membership/ParticipantImportErrorMessagesTest.php::test_multiple_errors_on_same_row_are_all_returned`
72. `Membership/ParticipantImportTest.php::test_preview_returns_valid_rows_and_stats`
73. `Membership/ParticipantImportTest.php::test_preview_flags_invalid_participant_type`
74. `Membership/ParticipantImportTest.php::test_preview_flags_nonexistent_email`
75. `Membership/ParticipantImportTest.php::test_preview_flags_past_expires_at`
76. `Membership/ParticipantImportTest.php::test_preview_accepts_future_expires_at`
77. `OrganisationCreationCsrfTest.php::test_organization_creation_requires_csrf_token`
78. `OrganisationCreationErrorTest.php::test_timeout_handling`
79. `Organizations/VoterControllerTest.php::it_allows_approval_of_voters_regardless_of_voting_status`
80. `RegistrationTest.php::test_new_users_can_register`
81. `Security/VoterControllerPenetrationTest.php::it_requires_valid_csrf_token_on_approval`
82. `Security/VoterControllerPenetrationTest.php::it_prevents_open_redirects`
83. `Security/VoterControllerPenetrationTest.php::it_should_rate_limit_sensitive_operations`
84. `Security/VoterControllerPenetrationTest.php::it_limits_bulk_operation_size`
85. `VoteBugFixIntegrationTest.php::it_requires_authentication_for_vote_submission`
86. `VoteControllerDownloadPdfTest.php::it_returns_pdf_with_correct_headers`
87. `VoteControllerDownloadPdfTest.php::it_generates_pdf_with_voter_information`
88. `VoteControllerDownloadPdfTest.php::it_generates_pdf_with_election_information`
89. `VoteControllerDownloadPdfTest.php::it_generates_pdf_with_vote_selections`
90. `VoteControllerDownloadPdfTest.php::it_generates_pdf_with_vote_statistics`
91. `VoteControllerDownloadPdfTest.php::pdf_filename_contains_timestamp`
92. `VoteControllerTest.php::verify_first_submission_routes_to_real_verify_page`
93. `VoteSubmissionWorkflowTest.php::test_user_can_access_vote_create_page_after_code1`
94. `VoteSubmissionWorkflowTest.php::test_user_can_review_selections_before_submission`
95. `VoteSubmissionWorkflowTest.php::test_user_can_submit_complete_vote`
96. `VoteSubmissionWorkflowTest.php::test_user_receives_verification_code_after_submission`
97. `VoteSubmissionWorkflowTest.php::test_user_can_enter_correct_verification_code`
98. `Voter/VoterImportElectionOnlyTest.php::test_preview_shows_new_for_unknown_email_in_election_only_mode`
99. `Voter/VoterImportElectionOnlyTest.php::test_preview_shows_existing_for_known_email`

## Appendix B — failing files by owner (full run, 2026-10-01)


**`PBDIGIT-76`** (10 files, 110 failing tests)

- `Feature/DoubleVoteDebugTest.php` — 2
- `Feature/ExistingVotingControllersIntegrationTest.php` — 7
- `Feature/MultipleVotePreventionTest.php` — 5
- `Feature/Phase2VoteValidationTest.php` — 9
- `Feature/Phase5ErrorHandlingTest.php` — 8
- `Feature/Phase6EndToEndIntegrationTest.php` — 8
- `Feature/RealElection/EndpointRoutingTest.php` — 15
- `Feature/VoteSubmissionWorkflowTest.php` — 43
- `Feature/VoterStepWorkflowTest.php` — 7
- `Feature/VoterWorkflowIntegrationTest.php` — 6

**`PBDIGIT-77`** (11 files, 89 failing tests)

- `Feature/Audit/VoteControllerAuditTest.php` — 2
- `Feature/Bootstrap/BootstrapAppConfigurationTest.php` — 1
- `Feature/Election/ElectionOnlyEntitlementPinTest.php` — 6
- `Feature/Integration/VotingMembershipIntegrationTest.php` — 6
- `Feature/IpRestriction/ElectionIpRestrictionTest.php` — 8
- `Feature/RealElection/RoutingTest.php` — 20
- `Feature/RealVoteVerificationTest.php` — 7
- `Feature/VoteControllerTest.php` — 13
- `Feature/VoteSubmissionRedirectTest.php` — 3
- `Feature/VoteVerificationTest.php` — 8
- `Feature/VoteVerifyTest.php` — 15

**`PBDIGIT-78`** (5 files, 19 failing tests)

- `Feature/CodeControllerTest.php` — 6
- `Feature/Election/ElectionShowControllerTest.php` — 5
- `Feature/Election/VotingEntitlementAmbientInvarianceTest.php` — 2
- `Feature/ExpiredCodeAutoRegenerationTest.php` — 4
- `Feature/Vote/VoteControllerConstitutionalTest.php` — 2

**`PBDIGIT-139`** (3 files, 7 failing tests)

- `Feature/Election/ElectionSuspensionTest.php` — 1
- `Unit/Application/Election/Capabilities/CanEditTimelineCapabilityTest.php` — 3
- `Unit/Application/Election/CapabilityPolicyLayerTest.php` — 3

**`PBDIGIT-157`** (2 files, 7 failing tests)

- `Feature/Election/ElectionActivationTest.php` — 4
- `Feature/Election/ElectionManagementConstitutionalTest.php` — 3

**`PBDIGIT-158`** (1 files, 1 failing tests)

- `Feature/Contexts/Adjudication/IssuanceContextRoundTripTest.php` — 1

**`PBDIGIT-160` / `183`–`186`** (6 files, 16 failing tests)

- `Unit/Contexts/Election/OperatingCoreApplication/AbsentAggregateReferenceRedTest.php` — 1
- `Unit/Contexts/Election/OperatingCoreApplication/HistoryKindAssignmentRedTest.php` — 1
- `Unit/Contexts/Election/OperatingCoreApplication/QueryServicesRedTest.php` — 5
- `Unit/Contexts/Election/OperatingCoreApplication/RecordCommitteeConstitutionHandlerRedTest.php` — 3
- `Unit/Contexts/Election/OperatingCoreApplication/RefusalTaxonomyRedTest.php` — 2
- `Unit/Contexts/Election/OperatingCoreApplication/ReportPeriodExpiryHandlerRedTest.php` — 4

**`PBDIGIT-165`** (2 files, 8 failing tests)

- `Feature/Finance/FeePaidProjectionTest.php` — 5
- `Feature/Membership/OutboxIntegrationTest.php` — 3

**`PBDIGIT-167 (→120 proposed)`** (1 files, 1 failing tests)

- `Unit/Middleware/ValidateVotingIpTest.php` — 1

**`PBDIGIT-167` (named contexts, re-measured)** (81 files, 362 failing tests)

- `Feature/Committee/CommitteeCreationWithGeoSelectionsTest.php` — 4
- `Feature/Committee/CommitteeDashboardApiTest.php` — 5
- `Feature/Committee/CommitteeDashboardHttpTest.php` — 1
- `Feature/Committee/CreateCommitteeApiTest.php` — 1
- `Feature/Committee/CreateCommitteeWithGovernanceTest.php` — 2
- `Feature/CommitteeStructure/ActivateCommitteeStructureStressTest.php` — 3
- `Feature/CommitteeStructurePersistenceTest.php` — 8
- `Feature/Contexts/Membership/CommitteeAssociationRepositoryTest.php` — 11
- `Feature/Contexts/Membership/CommitteeMembershipApplicationTest.php` — 6
- `Feature/Finance/MembershipPaymentMigrationTest.php` — 2
- `Feature/Finance/MembershipPaymentServiceTest.php` — 9
- `Feature/Geography/GeographicLevelControllerTest.php` — 5
- `Feature/Geography/RegionApiTest.php` — 6
- `Feature/Governance/Api/CommitteeMemberApiTest.php` — 8
- `Feature/Governance/ParticipationFreezeTest.php` — 1
- `Feature/Governance/Ui/CommitteeMemberManagerTest.php` — 5
- `Feature/Membership/CommitteeManagementDomainTest.php` — 2
- `Feature/Membership/CommitteeManagementHttpTest.php` — 6
- `Feature/Membership/CommitteeManagementTest.php` — 6
- `Feature/Membership/Domain/OrganisationParticipantTest.php` — 2
- `Feature/Membership/FeePaymentProjectionConsistencyTest.php` — 5
- `Feature/Membership/FeePaymentTest.php` — 8
- `Feature/Membership/FeeStatusRecalculationTest.php` — 6
- `Feature/Membership/FeeWaiverTest.php` — 5
- `Feature/Membership/MemberApprovalCreatesCorrectMemberTest.php` — 2
- `Feature/Membership/MemberControllerIndexTest.php` — 5
- `Feature/Membership/MemberControllerMarkPaidTest.php` — 4
- `Feature/Membership/MemberImportTest.php` — 3
- `Feature/Membership/MembershipApplicationTest.php` — 1
- `Feature/Membership/MembershipFeeControllerPayTest.php` — 5
- `Feature/Membership/MembershipFeeTest.php` — 3
- `Feature/Membership/MembershipTypeTest.php` — 1
- `Feature/Membership/OrganisationRoleControllerTest.php` — 1
- `Feature/Membership/ParticipantImportErrorMessagesTest.php` — 5
- `Feature/Membership/PublicApplicationApprovalTest.php` — 6
- `Feature/Membership/PublicApplicationDuplicateGuardTest.php` — 4
- `Feature/Membership/PublicApplicationEmailTest.php` — 5
- `Feature/Membership/PublicApplicationRateLimitTest.php` — 2
- `Feature/Membership/PublicApplicationSubmissionTest.php` — 9
- `Feature/Organisation/OrganisationGeographicScopeTest.php` — 6
- `Feature/OrganisationCreationMembershipTest.php` — 1
- `Unit/Application/Committee/CommitteeGeoBoundaryTest.php` — 1
- `Unit/Application/Committee/CommitteeTransactionSafetyTest.php` — 3
- `Unit/Application/Committee/CreateCommitteeUseCaseTest.php` — 3
- `Unit/Contexts/Membership/Application/Committee/AssignMemberToCommitteeHandlerTest.php` — 6
- `Unit/Contexts/Membership/Application/Committee/CommitteeSlugAvailabilityServiceTest.php` — 4
- `Unit/Contexts/Membership/Application/Committee/GetCommitteeDashboardTest.php` — 4
- `Unit/Contexts/Membership/Application/Membership/ApplyForCommitteeMembershipHandlerTest.php` — 5
- `Unit/Contexts/Membership/Application/Membership/RestoreMembershipHandlerTest.php` — 1
- `Unit/Contexts/Membership/Application/Membership/ReviewMembershipApplicationHandlerTest.php` — 4
- `Unit/Contexts/Membership/Application/Membership/SuspendMembershipHandlerTest.php` — 1
- `Unit/Contexts/Membership/Application/Membership/TerminateMembershipHandlerTest.php` — 2
- `Unit/Contexts/Membership/Domain/Committee/CommitteeAssignmentTest.php` — 1
- `Unit/Contexts/Membership/Domain/Fee/FeeTest.php` — 12
- `Unit/Contexts/Membership/Domain/Fee/PaymentPolicyTest.php` — 6
- `Unit/Contexts/Membership/Domain/Member/MemberTest.php` — 8
- `Unit/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeRepositoryTest.php` — 5
- `Unit/Domain/Committee/Constitutional/ConstitutionalArbitrationKernelTest.php` — 4
- `Unit/Domain/Committee/Constitutional/ConstitutionalGovernanceDecisionTest.php` — 3
- `Unit/Domain/Committee/Constitutional/GovernanceArchaeologyRecordTest.php` — 4
- `Unit/Domain/Committee/Constitutional/GovernanceDecisionSnapshotTest.php` — 5
- `Unit/Domain/Committee/Constitutional/GovernanceReplayServiceTest.php` — 4
- `Unit/Domain/Committee/Constitutional/ProjectionIsolationTest.php` — 2
- `Unit/Domain/Committee/Constitutional/ReplayCompatibilityTest.php` — 6
- `Unit/Domain/Committee/Constitutional/Snapshot/CanonicalConstitutionalSerializerTest.php` — 2
- `Unit/Domain/Committee/Constitutional/Snapshot/ConstitutionalReplayFingerprintTest.php` — 4
- `Unit/Domain/Committee/Constitutional/Snapshot/SnapshotDecompositionTest.php` — 4
- `Unit/Domain/Committee/Constitutional/Snapshot/SnapshotIntegrityHashTest.php` — 3
- `Unit/Domain/Committee/Constitutional/Timeline/GovernanceTimelineProjectorTest.php` — 3
- `Unit/Domain/Committee/Geo/GovernanceDecisionKernelTest.php` — 10
- `Unit/Domain/Committee/Geo/Kernel/GovernanceDecisionIdTest.php` — 3
- `Unit/Domain/CommitteeCreationPolicyTest.php` — 5
- `Unit/Domain/CommitteeLevelTest.php` — 6
- `Unit/Domain/CommitteeStructureInvariantsTest.php` — 9
- `Unit/Domain/CommitteeStructureStateMachineTest.php` — 7
- `Unit/Domain/CommitteeStructureTest.php` — 10
- `Unit/Domain/GeographyDomainServiceTest.php` — 3
- `Unit/Models/ElectionMembershipSuspensionTest.php` — 7
- `Unit/Models/ElectionMembershipTest.php` — 8
- `Unit/Models/MembershipApplicationTest.php` — 1
- `Unit/Models/MembershipTypeTest.php` — 3

**`PBDIGIT-208`** (213 files, 1345 failing tests)

- `Feature/Accessibility/VoterControllerAccessibilityTest.php` — 25
- `Feature/Admin/ElectionApprovalTest.php` — 4
- `Feature/AdminRecoverySlugTest.php` — 3
- `Feature/Audit/ElectionSettingsControllerAuditTest.php` — 2
- `Feature/Audit/ElectionVotingControllerAuditTest.php` — 2
- `Feature/Audit/VoterVerificationControllerAuditTest.php` — 2
- `Feature/Auth/CsrfProtectionTest.php` — 2
- `Feature/Auth/DashboardResolverPriorityTest.php` — 13
- `Feature/Auth/DashboardResolverTest.php` — 12
- `Feature/Auth/EmergencyDashboardControllerTest.php` — 14
- `Feature/Auth/HasActiveElectionTest.php` — 6
- `Feature/Auth/LoginFallbackChainTest.php` — 9
- `Feature/Auth/LoginFlowDebugTest.php` — 1
- `Feature/Auth/LoginOrganisationAccessTest.php` — 4
- `Feature/Auth/LoginResponseTest.php` — 9
- `Feature/Auth/RegistrationAndLoginTest.php` — 4
- `Feature/Auth/RegistrationMultiTenancyTest.php` — 6
- `Feature/Auth/VerifiedMiddlewareTest.php` — 12
- `Feature/Auth/VoterInvitationSetPasswordTest.php` — 1
- `Feature/AuthenticationFlowTest.php` — 11
- `Feature/CodeControllerSlugRedirectTest.php` — 4
- `Feature/Commands/BackfillVoterSourceStrategyTest.php` — 3
- `Feature/CommitteeStructure/DatabaseConstraintTest.php` — 1
- `Feature/Consistency/CoreTenantConsistencyTest.php` — 10
- `Feature/Consistency/TenantConsistencyTest.php` — 12
- `Feature/Consistency/TenantIsolationTest.php` — 8
- `Feature/Console/SetupDemoElectionTest.php` — 16
- `Feature/DatabaseIntegrityTest.php` — 1
- `Feature/DebugUserFactoryTest.php` — 1
- `Feature/Demo/AgreementSubmissionTest.php` — 4
- `Feature/Demo/CircularRedirectFixTest.php` — 4
- `Feature/Demo/CodeCreatePageTest.php` — 3
- `Feature/Demo/DemoVoteControllerCreateTest.php` — 9
- `Feature/Demo/DeviceDuplicateDetectionTest.php` — 1
- `Feature/Demo/EndpointRoutingTest.php` — 8
- `Feature/Demo/ExpiredCodeRestartTest.php` — 4
- `Feature/Demo/FormSubmissionEndpointTest.php` — 7
- `Feature/Demo/MarkUserAsVotedTest.php` — 2
- `Feature/Demo/PublicDemoFlowTest.php` — 5
- `Feature/Demo/VoteAnonymityTest.php` — 5
- `Feature/Demo/VotePreCheckTest.php` — 12
- `Feature/Demo/VotingTimeConfigTest.php` — 1
- `Feature/Demo/VotingWorkflowIntegrationTest.php` — 13
- `Feature/DemoAndRealElectionVotingTest.php` — 17
- `Feature/DemoCandidateCreationTest.php` — 1
- `Feature/DemoElectionSetupTest.php` — 8
- `Feature/DemoMirrorSystemTest.php` — 17
- `Feature/DemoModeTest.php` — 5
- `Feature/DemoResultPageTest.php` — 11
- `Feature/DemoSetupApiTest.php` — 6
- `Feature/DemoSetupCommandTest.php` — 5
- `Feature/DemoVoteCompleteFlowTest.php` — 2
- `Feature/DemoVoteIsolationTest.php` — 1
- `Feature/DemoVoteMode1Test.php` — 1
- `Feature/DemoVoteMode2Test.php` — 1
- `Feature/DemoVoteOrganisationTest.php` — 7
- `Feature/DemoVoteVerificationCodeTest.php` — 6
- `Feature/Election/CompleteNominationCharacterizationTest.php` — 11
- `Feature/Election/ConstitutionalParityIntegrationTest.php` — 5
- `Feature/Election/CsvVoterImportTest.php` — 1
- `Feature/Election/ElectionDashboardAccessTest.php` — 1
- `Feature/Election/ElectionGracePeriodUITest.php` — 1
- `Feature/Election/ElectionModelLockAndAuditTest.php` — 1
- `Feature/Election/ElectionPolicyStateAwareTest.php` — 1
- `Feature/Election/ElectionSettingsTest.php` — 1
- `Feature/Election/ElectionStateMachineCapabilitiesTest.php` — 2
- `Feature/Election/ElectionTransitionToMethodTest.php` — 1
- `Feature/Election/EmVot002ApprovedCandidateBeforeVotingTest.php` — 1
- `Feature/Election/Security/SecuritySchemaTest.php` — 4
- `Feature/Election/StateMachine/CurrentBehaviorTest.php` — 4
- `Feature/Election/StateMachineTransitionAuditTest.php` — 3
- `Feature/Election/TimelineEditing/TimelineCapabilityAuthorizationTest.php` — 8
- `Feature/Election/VoterEligibilityTest.php` — 7
- `Feature/Election/VoterStrategyMigrationIntegrityTest.php` — 3
- `Feature/Election/VoterStrategySnapshotTest.php` — 3
- `Feature/Election/VoterVerificationTest.php` — 4
- `Feature/Election/VotingButtonsStateMachineIntegrationTest.php` — 1
- `Feature/Election/VotingButtonsStateMachineTest.php` — 3
- `Feature/Election/VotingClosureValidationTest.php` — 1
- `Feature/ElectionCandidacyRelationshipTest.php` — 9
- `Feature/ElectionIdPositionOrderTest.php` — 15
- `Feature/ElectionOfficerInvitationTest.php` — 7
- `Feature/ElectionOfficerManagementTest.php` — 11
- `Feature/ElectionPostRelationshipTest.php` — 1
- `Feature/ElectionStateMachineTest.php` — 16
- `Feature/ElectionTimelineSettingsTest.php` — 8
- `Feature/ElectionVoterManagementTest.php` — 11
- `Feature/EmailVerificationTest.php` — 2
- `Feature/ExceptionHandlingTest.php` — 8
- `Feature/Finance/IncomeOrganisationMigrationTest.php` — 1
- `Feature/Finance/MemberPaymentIntegrationTest.php` — 7
- `Feature/Jobs/ScheduledJobsTest.php` — 1
- `Feature/Middleware/EnsureElectionStateTest.php` — 1
- `Feature/Middleware/EnsureElectionVoterTest.php` — 7
- `Feature/Middleware/TenantContextMiddlewareTest.php` — 2
- `Feature/MiddlewareChainTest.php` — 9
- `Feature/Newsletter/NewsletterAudienceSegmentationTest.php` — 15
- `Feature/Organisation/InvitationAcceptanceTest.php` — 1
- `Feature/Organisation/OrganisationCreationPageTest.php` — 6
- `Feature/Organisation/OrganisationSettingsTest.php` — 1
- `Feature/OrganisationCreationCsrfTest.php` — 3
- `Feature/OrganisationCreationDatabaseTest.php` — 10
- `Feature/OrganisationCreationEmailTest.php` — 7
- `Feature/OrganisationCreationErrorTest.php` — 12
- `Feature/OrganisationCreationIntegrationTest.php` — 10
- `Feature/OrganisationCreationRepresentativeTest.php` — 11
- `Feature/OrganisationCreationSecurityTest.php` — 10
- `Feature/OrganisationCreationStringOperatorTest.php` — 8
- `Feature/OrganisationCreationTest.php` — 6
- `Feature/OrganisationCreationValidationTest.php` — 13
- `Feature/OrganisationIsolationTest.php` — 1
- `Feature/OrganisationVoterHubTest.php` — 2
- `Feature/Organizations/VoterControllerIntegrationTest.php` — 11
- `Feature/Organizations/VoterControllerSecurityTest.php` — 17
- `Feature/Organizations/VoterControllerTest.php` — 27
- `Feature/Phase3VoteStorageVerificationTest.php` — 8
- `Feature/Phase4StateManagementTest.php` — 8
- `Feature/PreventMultipleVotingTest.php` — 9
- `Feature/ProfileInformationTest.php` — 1
- `Feature/RealVoteEnforcementTest.php` — 23
- `Feature/RealWorldVotingFlowTest.php` — 2
- `Feature/RegionalCandidatesDisplayTest.php` — 1
- `Feature/RegistrationTest.php` — 1
- `Feature/ResultCalculationTest.php` — 11
- `Feature/RouteBindingTest.php` — 6
- `Feature/Routes/VoterSlugRouteBindingTest.php` — 2
- `Feature/Security/VoterControllerPenetrationTest.php` — 23
- `Feature/Seeders/DemoCandidacySeederTest.php` — 4
- `Feature/Seeders/DemoElectionSeederTest.php` — 3
- `Feature/Seeders/OrganisationSeederTest.php` — 1
- `Feature/Seeders/PlatformAdminSeederTest.php` — 1
- `Feature/Services/DashboardResolverElectionPriorityTest.php` — 3
- `Feature/Services/DemoElectionAutoCreationTest.php` — 3
- `Feature/Services/VoterSlugServiceTest.php` — 29
- `Feature/SingleElectionLoginFlowTest.php` — 8
- `Feature/TenantIsolationComprehensiveTest.php` — 11
- `Feature/TenantIsolationTest.php` — 33
- `Feature/UserHierarchyTest.php` — 17
- `Feature/UuidMultiTenancyTest.php` — 9
- `Feature/VoteAnonymityTest.php` — 8
- `Feature/VoteBugFixIntegrationTest.php` — 9
- `Feature/VoteCountingAndNoVotesFeatureTest.php` — 5
- `Feature/VoteResultsCompatibilityTest.php` — 1
- `Feature/VoteStorageTest.php` — 3
- `Feature/Voter/VoterImportElectionOnlyTest.php` — 1
- `Feature/VoterDashboardIntegrationTest.php` — 4
- `Feature/VoterRecoveryTest.php` — 4
- `Feature/VoterSlugControllerTest.php` — 10
- `Feature/VoterSlugFeatureTest.php` — 7
- `Feature/Voting/VoterAuditTrailTest.php` — 1
- `Feature/Voting/VotingEngineIntegrationTest.php` — 2
- `Unit/Application/Election/ConstitutionalDriftMonitorTest.php` — 3
- `Unit/Application/Election/ConstitutionalTransitionGuardTest.php` — 1
- `Unit/Application/Election/DeprecationAccessGuardTest.php` — 4
- `Unit/Application/Election/ElectionCapabilityResolverTrustIntegrationTest.php` — 7
- `Unit/Application/Election/ElectionLifecycleFacadeTest.php` — 4
- `Unit/Application/Election/ElectionReadModelTest.php` — 5
- `Unit/Application/Election/QueryPolicyGuardTest.php` — 5
- `Unit/Application/Election/Security/Overlays/IpVelocityOverlayTest.php` — 3
- `Unit/Application/Election/Security/SecurityEventRecorderTest.php` — 3
- `Unit/Application/TransactionalBoundaryTest.php` — 3
- `Unit/Audit/ReplayDeterminismAuditTest.php` — 3
- `Unit/Audit/TemporalDeterminismAuditTest.php` — 2
- `Unit/Controllers/DemoResultControllerTest.php` — 8
- `Unit/Controllers/VoteControllerValidationTest.php` — 10
- `Unit/Demo/DemoCandidateMappingTest.php` — 3
- `Unit/DemoVoteVerificationFlowTest.php` — 4
- `Unit/Domain/Election/ElectionConstitutionTargetStateTest.php` — 1
- `Unit/Domain/Election/ElectionLifecycleEngineTest.php` — 1
- `Unit/Domain/Election/Security/D5ResolverIntegrationTest.php` — 8
- `Unit/Domain/Election/Security/OverlaySignalTest.php` — 5
- `Unit/Election/BusinessConditionsTest.php` — 3
- `Unit/Election/DerivedStateTest.php` — 12
- `Unit/Election/StateTransitionValidationTest.php` — 12
- `Unit/EmptyCandidatesNoVoteCorrectionTest.php` — 3
- `Unit/Enums/VotingStepTest.php` — 1
- `Unit/Http/LoginResponseUnitTest.php` — 9
- `Unit/Infrastructure/RepositoryAPIRefactorTest.php` — 4
- `Unit/Jobs/ProcessMemberImportJobTest.php` — 10
- `Unit/Middleware/EnsureRealVoteOrganisationTest.php` — 8
- `Unit/Middleware/TenantContextTest.php` — 6
- `Unit/Models/CandidacyTest.php` — 2
- `Unit/Models/CodeTest.php` — 9
- `Unit/Models/Demo/DemoCodeTest.php` — 1
- `Unit/Models/Demo/DemoVoteTest.php` — 4
- `Unit/Models/Demo/DemoVoterSlugTest.php` — 7
- `Unit/Models/DemoVoteModelTest.php` — 1
- `Unit/Models/ElectionTest.php` — 3
- `Unit/Models/OrganisationTest.php` — 3
- `Unit/Models/ResultTest.php` — 3
- `Unit/Models/Traits/ScopedByOrganisationTest.php` — 5
- `Unit/Models/UserOrganisationRoleTest.php` — 4
- `Unit/Models/UserTest.php` — 6
- `Unit/Models/VoteTest.php` — 15
- `Unit/Models/VoteValidationTest.php` — 13
- `Unit/Models/VoterSlugTest.php` — 4
- `Unit/NoVoteOptionDatabaseStorageTest.php` — 3
- `Unit/Observers/UserOrganisationObserverTest.php` — 1
- `Unit/OrganisationStandardizationTest.php` — 9
- `Unit/ResultControllerVoteCountingTest.php` — 5
- `Unit/Services/DashboardResolverRoleTest.php` — 4
- `Unit/Services/DashboardResolverTest.php` — 13
- `Unit/Services/DashboardResolverUnitTest.php` — 4
- `Unit/Services/DemoElectionCreationServiceTest.php` — 3
- `Unit/Services/DemoElectionResolverTest.php` — 14
- `Unit/Services/ElectionSettingsServiceTest.php` — 1
- `Unit/Services/VotingSecurityServiceTest.php` — 16
- `Unit/SimpleVoteCountingTest.php` — 1
- `Unit/VoteDataSanitizationTest.php` — 11
- `Unit/VoteDataValidationTest.php` — 11
- `Unit/VoteFrontendValidationTest.php` — 8
- `Unit/VoterProgressServiceTest.php` — 8
- `Unit/VoterSlugServiceTest.php` — 12
