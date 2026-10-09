# Membership Slice 1b: Implementation Review (PostgreSQL adapter)

**Date:** 2026-10-09 · **Story:** `PBDIGIT-260` · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable). Engineering supplies the evidence; acceptance is the Principal Reviewer's.
**Contract:** `2026-10-09-membership-slice1b-contract.md` (approved by the PO 2026-10-09, clarifications 1–6).
**Commits:** `1013555aa` (approval, plan) → `896f91bc8` … `eb29153c7` (10 commits). Branch `knowelegeos-modelling`; **nothing pushed.**

## 1. Approved decisions and sources

| Decision | Source |
|---|---|
| EP-Q6 = T1, calendar dates; decidedAt / recordedAt are instants; no historical dates in 1b | PO, 2026-10-09 (readiness review §J.1, verbatim) |
| DB-1 = PostgreSQL only; fail fast; MySQL residue as technical debt | PO, 2026-10-09 (contract §1) |
| B-1 … B-4 accepted; B-6 not included | instruction `20261009_0922…` + contract §1 |
| migration = a normal deployable migration, applied only by the tests in 1b, not yet on dev / prod | PO approval, clarification 2 (contract §5) |
| **minimal fix of `DecideAndAppend`** (a Slice 1a defect found by the T12 race) | **PO decision, 2026-10-09** ("Allow minimal fix") |

## 2. What was implemented

**Production code:**

| File | Role |
|---|---|
| `database/migrations/2026_10_09_100000_create_org_membership_tables.php` | three tables as in contract §5, PostgreSQL-only, no driver branches |
| `app/Contexts/Membership/Infrastructure/Organisational/PostgresMemberRepository.php` | the adapter |
| `DateOrdinal.php`, `InstantMicros.php`, `ProvenanceJson.php` | exact mappings |
| `UnsupportedMembershipDatabaseDriver.php` | fail-fast exception |
| `app/Contexts/Membership/Application/Organisational/DecideAndAppend.php` | **the authorised fix** (§6) |

**Unchanged** (empty diff since `1013555aa`): `Domain/Organisational/*` (kernel), `Application/Organisational/Ports/*`, `AdmitMemberHandler`, `EndMembershipHandler`.

**Tests:**
- contract trait refactor;
- `PostgresMemberRepositoryTest` (14);
- `PostgresMemberRepositoryInvariantsTest` (9);
- `PostgresReferenceModelPropertyTest` (1);
- `PostgresConcurrencyRaceTest` (3);
- 1 new in-memory interleaving test;
- `MembershipSlice1bPersistenceBoundaryTest` (3).

## 3. Final schema (as migrated on `nrna_test`)

| Table | Columns | Constraints |
|---|---|---|
| `org_memberships` | organisation_id uuid · id text · person_ref text · version int · created_at timestamptz(6) | PK (organisation_id, id) · UNIQUE (organisation_id, person_ref) [INV-M] · FK organisations RESTRICT · CHECK version ≥ 0 |
| `org_membership_facts` | organisation_id · membership_id · seq · fact_id · kind · effective_at **date** · initial_term · initial_term_until **date** · period_fact_id · end_kind · decided_at / recorded_at **timestamptz(6)** · provenance **jsonb** | PK (org, membership, seq) · UNIQUE (org, membership, fact_id) [INV-FID safety net] · FK (org, membership) → org_memberships [tenant] · FK (org, membership, period_fact_id) → facts · CHECK kind columns · CHECK initial-term states · CHECK seq ≥ 1 |
| `org_membership_commands` | organisation_id · idempotency_key · fingerprint · membership_id · fact_id · seq · recorded_at | PK (organisation_id, idempotency_key) · FK (org, membership) → org_memberships |

There is no `based_on_version`, `updated_at` or soft delete, and no Eloquent model.

## 4. Transaction model (`append`)

One transaction (READ COMMITTED), in this order:
1. **Key check:** the key row exists → `AlreadyApplied` / `IdempotencyKeyConflict`.
2. **Version compare-and-set:** `UPDATE … SET version = version + 1 WHERE … AND version = :basedOn RETURNING version`. This takes the row lock and is re-evaluated after any lock wait.
3. **If no row matched:** re-read the key (§6, interleaving A), then `ConcurrencyConflict` or `MemberNotFound`.
4. **recordedAt** = max(clock, last recorded_at).
5. **Insert** the fact at `seq` = the new version.
6. **Insert** the key row. A primary-key violation here (a concurrent use of the key for another Member) rolls back and is answered from the key row.

**Error policy:**
- any other failure rolls back and propagates;
- the adapter never retries;
- `create` is a single statement: `INSERT … ON CONFLICT (organisation_id, person_ref) DO NOTHING RETURNING id`, otherwise `HolderExists`.

## 5. Evidence

| Contract test | Result | Notes |
|---|---|---|
| T1 shared contract (14) on PostgreSQL | ✅ 14 / 14 | same suite as in memory (trait) |
| T2 same MemberId / person in two tenants | ✅ | |
| T3 FK rejects a cross-tenant fact | ✅ | database-level tenant guarantee |
| T4 three initial-term states | ✅ | none ⇒ Lapsed (C-S0-2) |
| T5 exact round trips | ✅ | dates incl. 0001-01-01, 1969-12-31, 2024-02-29, 9999-12-31; µs; provenance |
| T6 monotone recordedAt | ✅ | |
| T7 atomic rollback | ✅ | version, facts and key unchanged after a forced mid-append failure |
| T8 fact-id violation untranslated | ✅ | infrastructure error (B-1) |
| T9 no update / delete | ✅ | reflection |
| T10 fail-fast on non-PostgreSQL | ✅ | sqlite probe connection |
| T11 property vs the independent model | ✅ | 25 seeds × 30 commands on PostgreSQL; in memory 200 × 40. Outcomes covered (PostgreSQL run): appended 247 · overlapping 192 · member_not_found 131 · end_not_after_start 79 · period_already_ended 35 · key_conflict 33 · duplicate_fact_id 17 · period_not_found 16. These are counts of generated commands, not statistics |
| T12 real concurrent processes | ✅ (after §6) | `pcntl_fork`, 4 processes per round released at a barrier, 8 rounds × 3 scenarios, 3 consecutive runs green: creation race → one Member, one admission; different Ends → exactly one End, losers re-decided (`period_already_ended` / `concurrent_modification`); identical retries → one fact, every caller gets the original `Appended` |
| T13 architecture | ✅ 3 tests (partial; corrected 2026-10-09) | plus the existing kernel and 1a fitness tests (9). **The tests do not enforce the contract clause "kernel and application unchanged".** That clause is evidenced by `git diff` (§2), not by T13 |

**Sensitivity:** every mutation listed was detected, then reverted.
- Removing fail-fast → T10 fails.
- Removing the transaction → T7 fails.
- A date off by one → T4 and T5 fail.
- Removing the recordedAt clamp → T6 fails.
- Decoding "none" as unlimited → T11 fails.
- A planted Eloquent model with Election vocabulary → T13 fails.

**TDD record (honest):**
- **RED-first:** T1 (`de0e0b60d`), T12 (`bfe4829d2`) and the in-memory interleaving test (`500ada433`).
- **Green on first run:** T2–T10. The GREEN step for T1 had already built the whole adapter (fail-fast and transaction included). This is a **TDD deviation**; each guarantee was instead evidenced by the mutations above.

## 6. Found defect and its fixes (PO-authorised)

The real-process race (T12) found that **concurrent identical retries** returned `DuplicateFactId` to the losers instead of the original `Appended`. Exactly one fact was always written, so safety held; the retry answer was wrong (IDEM-1 / IDEM-3). There were two interleavings:

| | Interleaving | Where | Fix |
|---|---|---|---|
| A | the key check ran before the row-lock wait; the version update then failed → re-decide → DuplicateFactId | adapter | re-read the key after a failed compare-and-set (`d3aeaaa9f`), within the 1b scope |
| B | the identical command committed between `DecideAndAppend`'s pre-check and its load → the domain refuses DuplicateFactId; append is never reached | **`DecideAndAppend` (Slice 1a code)** | after a domain refusal, consult the key: same fingerprint → original outcome; other → `IdempotencyKeyConflict`; none → the refusal (`362c5ee03`). **The PO authorised this change, an exception to clarification 1.** It is race-free **under READ COMMITTED (the server default) with an autocommitted `create`**, because the key row commits atomically with its fact (wording narrowed 2026-10-09) |

- **B reproduced deterministically in memory** (`ConcurrencyAndRaceTest`, new hook `beforeNextLoad`). The in-memory adapter could not show A, because its calls are atomic.
- **After the B fix, the A fix is redundant for correctness.** It is kept because it makes the adapter itself honour IDEM-3 ("the key check is atomic with the append").
- **The Slice 1a contract's IDEM-1 is amended accordingly** (§9).

## 7. Regression comparison

Baseline: the checkout `1013555aa` (before any 1b code).

**Method note:**
- the baseline worktree shares `vendor/`, so Composer autoloads app classes from the main checkout;
- this is valid for the legacy tests because **no legacy app or database file changed** (the diff since the baseline is only the 1b files and `DecideAndAppend`);
- the baseline's own (pre-trait) Organisational tests were excluded from the baseline run, because they cannot load against the new trait.

| Suite | Now | Baseline | New failures |
|---|---|---|---|
| `--testsuite Architecture` | 174 tests, 4 failures | 171, 4 failures | **none** (the same 4 pre-existing: Election lifecycle / clock ×2, ConstitutionalBoundary, C25) |
| `tests/Unit/Contexts/Membership` | 341, 54 errors + 1 failure | 307 (without 1a Organisational), 55 errors + 1 failure | **none**: the identical set of 13 failing legacy classes. The difference of one error is **not attributed** (corrected 2026-10-09: the earlier attribution to the flaky `CommitteeSlugAvailabilityServiceTest` was not evidenced) |
| `tests/Feature/Contexts/Membership` | 46, 13 errors + 4 failures | 19, 13 errors + 4 failures | **none**: identical legacy failures; the 27 new tests are green |
| `tests/Feature/Membership` | 307, 28 errors + 35 failures | 307, identical | **none** (pre-existing) |

**The full suite is not claimed green.** Pre-existing legacy failures remain.

**Disclosure added 2026-10-09 (independent review, F-7):** both runs also used **uncommitted working-tree changes that are not part of Slice 1b** (their origin is not recorded):
- `database/factories/OrganisationFactory.php` (`faker->unique()`, aimed at the slug flake);
- `tests/Feature/Election/*`;
- `tests/Support/ElectionScenarioFactory.php`.

Because `vendor/` is shared, the baseline also executed the current `app/` code. **The comparison therefore shows "no new failure under the same working tree", not a clean-checkout baseline.**

## 8. Observations (not acted on)

| # | Observation | Recommendation |
|---|---|---|
| O-1 | `Tests\TestCase` claims "each test runs in its own transaction", but on PostgreSQL it **skips** per-test transactions; data persists between tests in one process. The 1b tests isolate themselves via fresh organisations | backlog item (test-infrastructure honesty) |
| O-2 | the MySQL residue | already recorded: `PBDIGIT-261` / `-262` |
| O-3 | the production `.env` engine is unverified by me | an operator prerequisite before any production rollout; the fail-fast guard covers a mismatch at runtime |
| O-4 | the migration is deployable but not yet run on dev or prod | a separate rollout step, outside 1b |

## 9. Deviations from the contract

| # | Finding | Existing contract | Deviation | Reason | Consequence |
|---|---|---|---|---|---|
| DV-1 | `DecideAndAppend` changed | clarification 1: unchanged | a minimal fix (§6) | a demonstrated defect; **PO-authorised** | Slice 1a contract IDEM-1 amended: the pre-check is repeated after a domain refusal |
| DV-2 | T2–T10 were not RED-first | §8 "RED first" | green on first run | the T1 GREEN step built the full adapter | mutation evidence per guarantee (§5) |
| DV-3 | the 1a in-memory property run gained checks | — | it now also checks initial-term states and InForce / Lapsed / Ended / CurrentPeriod | the T11 runner is shared | stronger 1a evidence; no semantic change |

## 10. Final status and next step

**GREEN.** All Slice 1b acceptance tests T1–T13 pass, including the real-process race. The kernel, port and handlers are unchanged. The single change to `DecideAndAppend` was authorised. The regression shows no new failure.

**Remaining** (not 1b blockers, but needed before production use):
- the README index rows for 258–262;
- the O-1 backlog item;
- the operator check of the production engine (O-3);
- the decision to roll out the migration (O-4).

**Recommended next step (for Principal Reviewer approval):** accept Slice 1b. Then choose between:
- **(a) Slice 2:** Admit / End boundary wiring (interim authority adapter labelled technical-only, organisation time zone for "today", HTTP / Inertia entry). Needs BD-CONSENT / LQ-1, BD-REC0, BD-3 or an interim End-kind list;
- **(b) the Governance participation contract track:** needs RD-1 … RD-4.

**STOP.** Neither is started.

## 11. Addendum (2026-10-09): independent acceptance review

An independent reviewer (read-only, fresh context) checked this slice. Results: `2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md` §1–§2.

- **Core claims held:** idempotency, atomic append, tenant scoping, no authoritative persistence state. No interleaving returning a wrong outcome was found.
- **One defect (F-1):** dates beyond 9999-12-31 are written but cannot be read back. **This review's T5 claim "exact round trips" holds only within years 0001–9999.**
- **Residual risks:** F-2 … F-5.
- **Corrections in this document:** the T13 wording, the "race-free" wording, the attribution of the error-count difference and the working-tree disclosure (§5, §6, §7).
- **Follow-up:** `PBDIGIT-264` (not authorised).
- **The "GREEN" classification above is the author's.** The independent recommendation is **ACCEPT WITH CONDITIONS**, and the decision is the Principal Reviewer's.
