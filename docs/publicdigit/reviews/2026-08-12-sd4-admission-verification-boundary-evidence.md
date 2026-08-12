# SD-4 evidence — do the admission-enforcement tests belong to the Election capability boundary?

**Commission:** Product Owner · Session 1 · **evidence only; SD-4 NOT decided, denominator NOT changed, baseline NOT re-run**
**Date:** 2026-08-12 · **Checkpoint:** `9ce272ac` · **Status:** **`SD-1` unchanged · 1,376 baseline frozen · no production, schema, migration, test or fixture change**

> **The question is not "are these tests good?" It is "would a programme that excludes them be entitled to say it verified the Election test estate?"**

---

## 1 · Executive answer

> 🔴 **The adopted universe verifies the DEFERRED mode and excludes the mode being implemented FIRST.**
>
> **In-universe:** `VoterEligibilityTest` — **27 rows, exclusively Full Membership** (Phase 2, deferred).
> **Out-of-universe:** the only rows that verify **Election-Only** eligibility — **Phase 1, being built now.**

**Measured, `--list-tests` (never a scan):**

| File | Rows | Universe |
|---|---:|---|
| `tests/Feature/Election/VoterEligibilityTest` | **27** | **IN** |
| `tests/Feature/Contexts/Elections/EloquentVoterEligibilityQueryServiceTest` | **8** | 🔴 OUT |
| `tests/Unit/Contexts/Elections/BulkAssignVotersHandlerTest` | **7** | 🔴 OUT |
| `tests/Unit/Contexts/Elections/AssignVoterHandlerTest` | **4** | 🔴 OUT |
| `tests/Unit/Contracts/VoterEligibilityPolicyContractTest` | **3** | 🔴 OUT |
| | **22 out-of-universe** | |

**Mode-token measurement on the 27 in-universe rows:** `election_only`/`ImportedVoterRegistry` → **0** · `full_membership`/`grants_voting_rights`/`fees_status` → **6**. **All 27 test names are Full-Membership concepts** (paid · exempt · unpaid · partial fees · membership type grants voting · expiry · member record · associate type).

## 2 · What each out-of-universe file verifies — read, not inferred

### `EloquentVoterEligibilityQueryServiceTest` — 8 rows · **capability boundary, both modes**

**Real database, real rules.** 4 Election-Only rows (qualifies · inactive rejected · **not in organisation rejected** · soft-deleted rejected) · 3 Full-Membership rows (valid accepted · no member record rejected · unpaid fees rejected) · **1 tenancy row.**

```php
// test_election_only_user_not_in_organisation
$user = User::factory()->create();                       // no organisation_users row
$this->assertFalse($this->service->isEligible($user->id, $org->id, ImportedVoterRegistry));
```

> **These 4 rows are the ONLY verification anywhere that Election-Only admission requires organisation enrolment.** *(They also constitute the implemented answer to `Q-B1` — enrolment is `organisation_users`, not `members`. **Evidence, not authority.**)*

```php
// test_tenant_isolation_respected — eligible in orgA, NOT eligible in orgB
$this->assertTrue($resultOrgA);  $this->assertFalse($resultOrgB);
```

**That row verifies a cross-cutting tenancy invariant** — the project's own RULE 9 (*"Unit tests MUST test tenant isolation logic… always test with multiple TenantId values"*). **A universe adopted to verify Election correctness excludes it.**

### `AssignVoterHandlerTest` (4) · `BulkAssignVotersHandlerTest` (7) — **capability boundary, enforcement wiring**

**Mocked collaborators — they verify ORCHESTRATION, which is precisely the obligation Phase C.1 transferred to the application:**

```php
// filters_ineligible_users_before_any_writes
$this->policyMock->expects($this->once())->method('qualifyingSubset')->willReturn(['user-1','user-2']);
$this->repositoryMock->expects($this->once())->method('bulkInsert');
$this->assertEquals(2, $result['success']);  $this->assertEquals(1, $result['invalid']);
```

> **This is the test that verifies `CF-3`'s guarantee — that the policy filters BEFORE any write.** It sits outside the universe. **The 11 rows also cover soft-delete restore-instead-of-duplicate, duplicate rejection, chunking at 500, dead-letter rows and continue-after-chunk-failure** — bulk-import robustness for Phase 1's primary admission path.

### `VoterEligibilityPolicyContractTest` — 3 rows · **NOT capability boundary**

`test_interface_exists` · `test_interface_has_is_eligible_method` · `test_interface_is_contract_only`. **Structural fitness assertions about an interface — SD-4A class C.** **The boundary report's own §6 excludes architecture tests from a business-verification universe, and that exclusion applies here.**

## 3 · Duplicate, supporting, or boundary? — the question answered directly

| | Verdict |
|---|---|
| **Duplicates of the in-universe 27?** | 🔴 **No.** They share the *concern* (eligibility) but differ in **mode** and **level**: the 27 are HTTP-surface (`store` · `bulk_store` · `bulk_assign` · `import_preview`) and **Full-Membership only**; the 8 are service-level and **cover both modes**. **Complementary, not redundant** |
| **Merely supporting?** | **No for 19 rows.** They verify *who may be admitted* and *that ineligible people are excluded before writes* — the substance of **C6**, not scaffolding |
| **Capability boundary?** | ✅ **19 of 22** — `EloquentVoterEligibilityQueryServiceTest` (8) + the two handler tests (11) |
| **Excludable on the boundary report's own rules** | **3** — `VoterEligibilityPolicyContractTest` (structural) |

## 4 · Would excluding them make *"Election test estate verification"* misleading?

**The programme's stated purpose** (boundary report §2): *"establish that an organisation can run an election whose outcome is legitimate — **the right people voted**, once, anonymously…"*

> ✅ **YES — misleading, and specifically misleading for PHASE 1.**
>
> **"The right people" IS C6 voter admission.** With these 22 excluded, the universe contains **27 rows verifying admission under the DEFERRED mode** and **ZERO verifying admission under the mode being implemented now.** A reader of *"the Election test estate is verified"* would reasonably conclude Election-Only admission was covered. **It is not covered anywhere inside the universe.**

**Stated precisely, and no further:** the phrase is misleading **as to Election-Only admission and tenancy isolation of eligibility**. **It is NOT misleading as to Full-Membership admission**, which the 27 in-universe rows do cover.

## 5 · Classification discipline — the categories kept apart

| Category | This finding |
|---|---|
| **OBSERVED IMPLEMENTATION** | Election-Only enrolment = `organisation_users`; Full Membership = `members` + fees + voting rights |
| **VERIFIED BUSINESS RULE** | Election-Only requires active organisation enrolment; ineligible users are excluded **before** any write — **both verified only OUTSIDE the universe** |
| **TEST VERIFICATION** | 19 rows of genuine business verification · 3 structural |
| **VERIFICATION GAP** | **Inside the universe: Election-Only admission — NIL** |
| **BUSINESS RULE NOT SPECIFIED** | whether a bulk import must **report** whom it skipped (`O-1`) |
| **ARCHITECTURAL QUESTION** | `SD-4` — **the Product Owner's, not engineering's** |
| **IMPLEMENTATION DEFECT** | 🔴 **NONE. Nothing here is a defect.** The mechanism works and is tested; the **programme's boundary** is what is in question |

## 6 · Blast radius — measured, and deliberately small

| | Rows |
|---|---:|
| Rows whose **classification** changes if SD-4 excludes these tests | **0** — they are not in the matrix |
| Rows whose classification changes if SD-4 includes them | **0 existing** · **+19 new rows** (and a new denominator) |
| **Existing matrix rows blocked by SD-4** | 🔴 **0** |

> **`SD-4` blocks the programme's CLAIM, not its CLASSIFICATION.** Master Matrix work continues at full speed on authority-settled batches. **"Important" is not "blocking."**

**`BR-1.12` blast radius (Session 2's open decision — admission → `active` vs → `invited` → approval → `active`):** **bounded entirely within `VoterEligibilityTest` (27 rows)** in-universe, and **at most the ~7 rows that assert a created membership's resulting state** (`store_assigns…` · `bulk_store_*` · `bulk_assign_all_eligible…` · `bulk_assign_skips_already_assigned`). **Rejection rows do not depend on it.** **Exact count NOT ESTABLISHED** — that class is the deliberately-deferred batch. **Marked `BUSINESS RULE NOT YET AUTHORIZED`; not classified against Option A or B.**

## 7 · Repository hygiene — a third stale reference to the dropped FK

`VoterEligibilityTest:110` — *"Also seeds the `UserOrganisationRole` row **required by the election_memberships FK**."*

**That FK was dropped on 2026-05-19.** With the CREATE migration and `ElectionShowControllerTest:54`, this is the **third** stale artifact describing it — **which is why I wrongly reported `CF-1`/`CF-2`.** **Repository classification, not a defect:** the comments are misleading, and the fixture rows they justify may now be unnecessary. **Not changed — Session 1 does not edit tests.**

## 8 · What is NOT concluded

**Not** that these tests should be added — **`SD-4` is the Product Owner's.** · **Not** a new denominator; **1,376 stands, frozen.** · **Not** a re-run of the baseline. · **Not** that `SD-1` was wrong to adopt — only that **its claim scope is narrower than its name suggests.** · **Not** that Election-Only admission is unverified — **it is verified, outside the universe.** · **Not** an implementation defect anywhere. · **Not** `Q-B1`, and **not** `BR-1.12`.

## 9 · Self-audit

| Check | ✓ |
|---|---|
| Counts from `--list-tests`, never a scan | ✅ 27 · 8 · 7 · 4 · 3 |
| Test **intent read**, not inferred from names | ✅ §2 — three bodies quoted |
| Name match treated as candidate only | ✅ the 3 contract rows were **excluded** despite an eligibility-shaped name |
| Duplicate-vs-boundary answered with evidence | ✅ §3 — mode **and** level differ |
| SD-4 **not** decided · denominator unchanged · baseline not re-run | ✅ |
| Blast radius measured, and stated as **0 blocked rows** | ✅ §6 |
| `BR-1.12` left unauthorised, bounded, not inferred from production | ✅ §6 |
| Implementation evidence **not** promoted to business authority | ✅ `Q-B1` §2 |
| Categories never collapsed | ✅ §5 — **defect row explicitly NONE** |
| My own earlier error's root cause traced | ✅ §7 — third stale FK reference |
| Full Membership not imported into Election-Only scope | ✅ the 27 rows marked **DEFERRED — FULL MEMBERSHIP** |
| No production/schema/migration/test/fixture change | ✅ |

---

**SD-4 EVIDENCE COMPLETE — DECISION REMAINS WITH THE PRODUCT OWNER / ARB**
**`SD-1` UNCHANGED · 1,376 BASELINE FROZEN · 0 EXISTING MATRIX ROWS BLOCKED**

**Traceability:** `vendor/bin/phpunit --list-tests` (all five files) · `EloquentVoterEligibilityQueryService` (per-mode queries) · `AssignVoterHandler:57` · `BulkAssignVotersHandler:56` · `VoterEligibilityPolicy` · Phase C.1 migration `2026_05_19_000001` · CF-3 investigation (`9ce272ac`) · SD-4A (155 files; class B *voter source strategy*, class C architecture) · capability boundary report §2 · §6 · CLAUDE.md RULE 9 · `BR-1.12` (Session 2) · `Q-B1`
