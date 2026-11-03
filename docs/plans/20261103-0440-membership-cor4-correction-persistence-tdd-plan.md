# Membership COR-4 (PBDIGIT-329): persisting Annul, Void and Correct, TDD plan

**Status:** **APPROVED by the PO, 2026-11-03 (G-PLAN: "approve the COR-4 plan with Q-1…Q-7 as recommended").** Steps 1–8 are authorised within the limits below. Nothing is implemented yet. *(Was: PROPOSED, awaiting G-PLAN and Q-1…Q-7.)*
- Approval would authorise steps 1–8 (§5) only: kernel load validation, the port helper, the test double, **one new migration exercised only in the pinned throwaway container**, the adapter, the tests and the docs.
- It authorises **no** migration on any persistent database, no application command (COR-3) and no event emission.

**Story:** `docs/publicdigit/backlog/PBDIGIT-329-membership-correction-c4-persistence.md` · **Placement:** ES-004.2 (`docs/plans/`).

## 1. Objective

Make every kernel fact kind storable and reloadable exactly as decided:
- Admit, End, Annul, Void, Correct, with a Correct's frozen consequences;
- on PostgreSQL and on the in-memory double, with identical observable behaviour.

Also:
- refuse structurally invalid stored histories on load (V-1, V-14, V-15);
- close the ACC-260 condition K-2.

## 2. Background and inputs (decided or ratified)

| Input | Status | Source |
|---|---|---|
| Fact shape: `kind` extended to annul / void / correct; a target reference; the Correct value in `effective_at`; `consequences jsonb` + `g2_version` on the fact row; a same-Member composite FK; kind-specific CHECKs; **an additive migration (the 1b migration is not edited)** | contract obligations (RAT direction; schema details PROP) | contract §8 |
| g2 lives in the kernel; the adapter stores and never recomputes it. The `DecideAndAppend` re-decide loop recomputes g2 on conflict | contract §8 [PROP] + FP §12.4 option C | contract §8; COR-2 guide 13 |
| P-2: consequences are absolute valid-time intervals, clipped at resolution | PO, 2026-11-03 | COR-2 acceptance §4.1 |
| Idempotency IDEM-1…4 unchanged; fingerprints are COR-3 | contract §8 | — |
| Entry criteria | V-1, V-4, V-5, V-13, V-14, V-15 (roadmap §3.2); K-2 (ACC-260); D-COR-4 | roadmap `…20261016_2219_…_completion_plan.md`; dispositions §4 |
| **Current state, VERIFIED** | `org_membership_facts`: `kind` CHECK admits only admit / end; `effective_at date NOT NULL`; `period_fact_id` with a same-Member FK (`org_membership_facts_period_fk`) (`2026_10_09_100000_…:38-72`). `encode()` reads `effectiveAt()` before branching on the kind (`PostgresMemberRepository.php:247`, K-2) and throws for other kinds (`:273`). Adapter and double record `recordedAt = max(clock, last)` (`:209`; double `:127`), so ties are possible (V-5). `Member::fromLog` checks only seq and recordedAt order (V-1) | code |
| Release gate (unchanged) | No ledger migration on any persistent database before: OQ-01; the §10.2 operator preflight (0 / 0 / 0); `SHOW lc_messages` English; code and migration deployed together | PBDIGIT-264 acceptance; ACC-260 |
| W3 (the separate kernel-hardening story for V-1) | **never started**; no story exists | roadmap W3; backlog |

## 3. Design points for the PO (decide with the approval)

| # | Question | Options | Recommendation |
|---|---|---|---|
| **Q-1** (D-COR-4) | The target reference column | (a) **rename** `period_fact_id` → `target_fact_id` in the additive migration (End, Annul, Void and Correct all use it); (b) keep `period_fact_id` and widen its meaning; (c) add a second column `target_fact_id` next to `period_fact_id` | **(a) rename**, as the contract proposes (`:238`). One reference with one meaning, "the fact this fact refers to". The table is unreleased (release gate), so the rename costs nothing outside the code. The FK constraint is renamed with it |
| **Q-2** (V-5) | Ties in `recordedAt` (KnownAt cannot observe the state between tied facts) | (a) **strictly increasing** `recordedAt` per Member: `max(clock, last + 1 µs)`, in the adapter and the double; (b) define KnownAt over seq (a domain change) | **(a)**. Adapter-local, with no change to the domain or to INV-14's meaning. The existing "never decreases" contract test still holds |
| **Q-3** (V-1, V-14, V-15) | Where load validation lives, and whether COR-4 takes over W3's V-1 | (a) **in the kernel, `Member::fromLog`**: one rule for every load path (G-A3 / AO-1), and COR-4 absorbs V-1; (b) in the adapter decode only; (c) DB constraints only; (d) a separate W3 story first | **(a)**. Corrections must not be stored before invalid histories are refused, and W3 never started. DB constraints (step 4) are a second line, not the rule |
| **Q-4** | `effective_at` for Annul and Void (they have no valid time) | (a) **nullable**, with a kind CHECK (NULL iff annul / void); (b) a sentinel date | **(a)**. No invented valid time (C-1 P-1) |
| **Q-5** | Storage of a Correct's consequences | (a) **`consequences jsonb` on the fact row** + `g2_version smallint` (contract §8, EP-01 R4); (b) a child table `org_membership_trims` | **(a)**. A trim has no life apart from its cause (FP §12.4 C). Format: `[{"period":"<admit fact id>","removed":[["YYYY-MM-DD","YYYY-MM-DD"\|null], …]}, …]`, where null = ∞ and dates go through `DateOrdinal`. `g2_version = 1`; decode refuses an unknown version |
| **Q-6** | What a load failure is | (a) **an infrastructure failure**: a dedicated `StoredMemberLogInvalid` (a `RuntimeException`, rule 8: not a user refusal), raised on decode or by `fromLog` validation; (b) skip the bad fact | **(a)**. Fail closed; never resolve a damaged history (V-1's risk) |
| **Q-7** (V-13) | The extent of the R-1a-6 storable range | (a) **the corrected value and every finite trim bound** checked by `StorableValidTime` before append; (b) the corrected value only | **(a)**. Every date written must be storable, or the Member becomes unloadable (PBDIGIT-264 F-1's lesson) |

## 4. Design

### Kernel, step 1

`Member::fromLog` additionally refuses (`\InvalidArgumentException`, wrapped by the adapter in `StoredMemberLogInvalid`):

| Rule | Covers |
|---|---|
| every `target` (Annul, Void, Correct) and every End's period names an **earlier** fact of the same log; an End's period names an Admit | V-1, V-14: rules out self-targets and so the `rootOf` loop |
| an Annul does not target a Void (N-3), and carries decision provenance (R-7 / C-AMEND-2) | V-1 |
| a Correct targets an Admit, an End or a Correct (N-3) | — |
| a Correct's consequences: one per period, each naming an Admit earlier in the log, each removing a non-empty set | V-15 |
| duplicate fact ids | today only `decide*` checks them |

Not re-checked on load, as a deliberate choice: the **G\* invariants and the guards that depend on time or authority**. A stored history is the record (R-12). Re-running guards on load would rewrite the past, contrary to the CFC "no re-execution" principle.

### Port, step 2

`StorableValidTime::admits()` covers:
- the Correct value;
- every finite bound of every consequence (Q-7).

The architecture test `MembershipStorableValidTimeCoverageTest` is extended so that a Correct cannot escape the check.

### Double, step 3

`InMemoryMemberRepository` stores every kind (it holds objects already), applies Q-2, and passes the extended `MemberRepositoryContract`.

### Schema, step 4

New migration `2026_11_03_xxxxxx_extend_org_membership_facts_for_corrections.php`, additive, with a `down()`:
- rename the column (Q-1) and its FK;
- `effective_at` nullable;
- `consequences jsonb NULL`, `g2_version smallint NULL`;
- replace `org_membership_facts_kind_check` with five kind-specific branches:

  | Kind | Date | Target | Other |
  |---|---|---|---|
  | admit | yes | no | initial term set |
  | end | yes | yes | end kind set |
  | annul / void | no | yes | no consequences |
  | correct | yes | yes | consequences and `g2_version` set |

### Adapter, step 5

- `encode()` branches per kind **before** touching any date (K-2).
- `decode()` handles all kinds.
- Consequences go through `DateOrdinal`; `g2_version` is checked.
- Every decode or `fromLog` failure becomes `StoredMemberLogInvalid`.
- Q-2: `recorded_at = max(clock, last + 1 µs)`.
- `appliedCommand` (F-4b join) is unchanged for the new kinds; a test pins it.

### Races, step 6

PostgreSQL race tests through `DecideAndAppend::run` with explicit decide closures. **No COR-3 handler is written or bound** (roadmap V-4 sequencing rule). Cases:
- Annul vs Correct on the same target;
- two Corrects of the same fact;
- a Correct vs an End on the adjacent period (FP §16.10 lost-update case).

Each must end in exactly one contract outcome. The loser re-decides, with g2 recomputed.

## 5. Steps (strict TDD: RED recorded before production code; PostgreSQL only in the pinned container from a clean worktree)

| # | Step | RED | GREEN in |
|---|---|---|---|
| 1 | **Load validation** (Q-3): the rules in §4, each with a test that builds the invalid log through `Member::fromLog`. Existing generators must still build only valid logs | kernel unit tests | `Member.php` |
| 2 | **R-1a-6 for Correct** (Q-7): unstorable corrected value or trim bound → `AppendOutcome` "unstorable valid time" on both repositories; boundary values accepted; the architecture test extended | contract + architecture tests | `StorableValidTime.php` |
| 3 | **Double parity + Q-2:** contract tests for round trips of Annul, Void and Correct (including a two-period Correct, the COR-2 review F-1 history), strictly increasing `recordedAt`, `appliedCommand` for new kinds | `MemberRepositoryContract` (double run) | `InMemoryMemberRepository.php` |
| 4 | **Migration** (Q-1, Q-4, Q-5): schema tests in the container. Each CHECK branch refuses malformed rows (direct inserts); the target FK refuses a cross-Member target; `down()` restores the 1b shape | `PostgresMemberRepositoryInvariantsTest` additions | new migration |
| 5 | **Adapter encode / decode** (K-2, Q-2, Q-6): the same contract tests on PostgreSQL; decode-failure tests (unknown `g2_version`, malformed `consequences`, a stored self-target, an Annul of a Void) → `StoredMemberLogInvalid` | `PostgresMemberRepositoryTest` (contract run) | `PostgresMemberRepository.php` (+ `ConsequencesJson` helper) |
| 6 | **Race tests** (§4) | `PostgresConcurrencyRaceTest` additions | none expected; any fix is reported first |
| 7 | **Differential reload:** for a bounded set of C-2 histories (`C2Histories`, L ≤ 4), append every fact through each repository, reload, and compare `resolve()` and every fact with the in-memory original (double: always; PostgreSQL: a sampled subset, budget recorded) | new test | none expected |
| 8 | **Verify and document:** full regression (database-free suites + `tests/Unit/Testing` in a clean worktree); the PostgreSQL suite × 3 on fresh clusters, evidence archived with hashes; Infection scoped to the changed files; developer guide `14_cor4_correction_persistence.md`; Slice 1b contract amended for the schema; the release gate's migration list updated | — | docs |

**After every step:** the database-free Membership regression, with no regression in Slices 0, 1a, 1b, 2a, C-1 or C-2.

## 6. Acceptance criteria

| AC | Criterion |
|---|---|
| AC-1 | every fact kind round-trips identically on both repositories (facts, consequences, `recordedAt`, `resolve()`), including a two-period Correct |
| AC-2 | V-1, V-14, V-15: every rule in §4 refuses on load, through `fromLog` and through the adapter (`StoredMemberLogInvalid`) |
| AC-3 | V-13: no unstorable date is appended (corrected value, trim bounds); boundaries are accepted |
| AC-4 | V-4 / K-2: `encode()` never calls a date accessor on a fact without a valid time; Annul / Void rows have a NULL date |
| AC-5 | the schema: every CHECK branch and the same-Member target FK refuse malformed rows; the migration is additive, the 1b migration untouched, `down()` reversible |
| AC-6 | V-5 (Q-2): `recordedAt` strictly increases per Member on both repositories |
| AC-7 | the race cases end in contract outcomes; g2 is recomputed by the re-decide loop, never by the adapter |
| AC-8 | the differential reload (step 7) shows 0 mismatches within the stated bounds |
| AC-9 | regression green; the PostgreSQL suite green × 3 in fresh pinned clusters, evidence archived; mutation reported with scope and classified survivors |
| AC-10 | no COR-3 handler, no event storage, no migration on a persistent database; the release gate updated to include the new migration |
| AC-11 | developer guide 14; Slice 1b contract amendment; an independent review, then the PO's verdict |

## 7. Risks

| Risk | Mitigation |
|---|---|
| Load validation rejects histories that existing tests or generators build | step 1 runs every existing suite; a generator producing invalid logs is a finding, reported, not patched silently |
| The column rename (Q-1) breaks F-4b or other queries | contract and PostgreSQL suites run after step 4; `appliedCommand` pinned for every kind |
| The jsonb shape drifts from the domain | one helper (`ConsequencesJson`) used by encode and decode; round-trip tests plus decode-failure tests; `g2_version` checked |
| PostgreSQL runs depend on the provisional image (W2-2: 15.19) and OQ-01 is open | all evidence states the pinned image; no claim beyond it |
| `+1 µs` (Q-2) moves `recordedAt` ahead of the clock under bursts | bounded by appends per Member; documented; the clock-going-back test kept |
| Main tree's cached config | every database-backed or boot-guarded run uses a fresh clean worktree (PBDIGIT-324 guide 11) |

## 8. Gates

| Gate | Owner |
|---|---|
| G-PLAN: approve this plan + Q-1…Q-7 | PO |
| the PostgreSQL runs use only the pinned container (already authorised infrastructure, PBDIGIT-324) | — |
| the independent review, then acceptance | reviewer → PO |
| any migration on a persistent database: **not part of COR-4**; release gate + ARB-MEM-01 | PO / Ops / ARB |

## 9. Traceability

- contract §8, §10, §14.5 (C-4 row, C-AMEND-1 F-1);
- roadmap W4 and V-1, V-4, V-5, V-13…V-15, G-A2, G-A3;
- ACC-260 K-2 (dispositions §4);
- COR-2 acceptance §4.1 (P-2);
- FP §12.4, §16.10;
- PBDIGIT-264 / 324 acceptance records (release gate; container).

## 10. Progress

- 2026-11-03: plan prepared; awaiting G-PLAN and Q-1…Q-7.
- 2026-11-03: **G-PLAN approved; Q-1…Q-7 decided as recommended:**
  - Q-1: rename to `target_fact_id`;
  - Q-2: strictly increasing `recordedAt`;
  - Q-3: load validation in `Member::fromLog`; COR-4 absorbs V-1;
  - Q-4: nullable `effective_at` for Annul / Void;
  - Q-5: `consequences jsonb` + `g2_version`;
  - Q-6: `StoredMemberLogInvalid`;
  - Q-7: the corrected value and every finite trim bound.
  - Committed `660fc03a9`.
- 2026-11-03: **Step 1 done (load validation in `Member::fromLog`; V-1, V-14, V-15).**
  - **RED:** `MemberLogValidationTest`:
    - 17 invalid stored logs (a data provider) were all accepted: **17 failures**;
    - one valid log of every kind already loaded. It is a characterisation test: a log that G\* would refuse today still loads, because guards are not re-run.
  - **GREEN:** `Member::requireStructurallyValid()`, called from the constructor, so it runs on **every** load path (`fromLog`, the adapter, the double). It refuses with `\InvalidArgumentException`:
    - a duplicate fact id;
    - an End whose period is not an **earlier Admit**;
    - an Annul, Void or Correct whose target is not an **earlier** fact; this rules out self-targets and so the `rootOf` loop of V-14;
    - an Annul of a Void (N-3);
    - an Annul without decision provenance (R-7);
    - a Correct of an Annul or Void (N-3);
    - a Correct trimming one period twice, or trimming something that is not an earlier Admit (V-15).
  - **Not re-checked, as approved:** G\*, and the guards that depend on time or authority.
  - **The wrapping into `StoredMemberLogInvalid` (Q-6) is in the adapter (step 5).** The kernel throws a plain `\InvalidArgumentException`, keeping the Domain free of infrastructure types.
  - **Regression:** Domain 189, Application 62 (the shared repository contract on the double runs through the new validation), Infrastructure 22, Architecture 97.
  - Committed `6da2594b3`.
- 2026-11-03: **Step 2 done (R-1a-6 for Correct; Q-7, V-13, review F-7).**
  - **RED:**
    - `MembershipStorableValidTimeCoverageTest::test_every_fact_carrying_a_time_point_is_handled_by_storable_valid_time`: a new **property** test (every kernel fact whose constructor takes a `TimePoint` must be handled by name in `StorableValidTime`, unless `effectiveAt()` is its only one). 1 failure, naming `Correct`;
    - `StorableValidTimeTest` (8 cases): 4 failures (value or trim bound out of range); the 4 in-range cases already passed;
    - `MemberRepositoryContract::test_a_correction_with_an_unstorable_value_is_not_appended` (2 cases): 2 failures on the double.
  - **Test correction during RED:** the first version of the property test also flagged `End`, a false positive, because `End` is covered generically through `EffectiveDatedFact`. The rule was narrowed before any production code: name-handling is required unless `effectiveAt()` is the only `TimePoint`. `Correct` stays flagged.
  - **GREEN:** `StorableValidTime::admits()` checks a Correct's value and both bounds of every interval of every consequence (∞ passes).
  - **Note:** through `decide*`, a trim bound is always an earlier stored date or ∞. An out-of-range bound therefore implies an out-of-range value; the bound check is defence in depth.
  - **The PostgreSQL run of the new contract test is due in step 5:** it answers before encoding, so it should already pass there.
  - **Regression:** Domain 189, Application 72, Infrastructure 22, Architecture 98.
  - Committed `5b134f033`.
- 2026-11-03: **Step 3 done (double parity; Q-2, V-5).** Three new `MemberRepositoryContract` tests, run on the double now and on PostgreSQL in step 5:
  - `test_recorded_at_strictly_increases_even_with_a_frozen_or_backward_clock` (clock 500, 500, 300 → recordedAt 500, 501, 502): **RED, 1 failure** (a tie at 500);
  - `test_every_fact_kind_round_trips_identically`: a 10-step history built through `decide*` with Admit, End, Correct (including the COR-2 review F-1 **two-period** Correct), Annul of a Correct and Void of an Annul. Facts, seq and `resolve()` must be equal after reload;
  - `test_applied_command_answers_correction_facts_with_their_own_fact`: Correct, Annul, Void.
  - **The two round-trip tests passed on the double at once**, as expected: it stores fact objects. **They are the RED tests for the PostgreSQL adapter (step 5).**
  - **GREEN (double):** `recordedAt = max(clock, last + 1 µs)`.
  - **Regression:** Domain 189, Application 75, Infrastructure 22, Architecture 98.
  - Committed `5572ce595`.
- 2026-11-03: **Step 4 done (the migration; Q-1, Q-4, Q-5).** Every PostgreSQL run used the pinned image (15.19, W2-2 provisional) via `scripts/testing/membership-pg.sh`, from a fresh clean worktree with the uncommitted files copied in. Each worktree was removed afterwards. Evidence lives in the scratchpad `cor4-ev/`; it is archived in step 8.
  - **RED** (`cor4-ev/s4-red`, commit `5572ce595`): `PostgresCorrectionSchemaTest`, 19 tests: **17 failures + 2 errors**, all SQLSTATE `42703` (no `target_fact_id` column) or the missing migration.
  - **GREEN:**
    - migration `2026_11_03_120000_extend_org_membership_facts_for_corrections.php`:
      - renames `period_fact_id` → `target_fact_id`, with its FK (Q-1);
      - makes `effective_at` nullable (Q-4);
      - adds `consequences jsonb` + `g2_version smallint` (Q-5);
      - adds one five-branch kind CHECK, a `g2_version >= 1` CHECK and a no-self-target CHECK;
      - `down()` refuses while any annul / void / correct row exists, and otherwise restores the 1b shape;
    - **the column rename is carried into the adapter** (4 references in `PostgresMemberRepository.php`, edited by hand), so the existing PostgreSQL suite stays green between steps. The new kinds remain step 5.
  - **Test defect found and fixed (my test, not the migration):** the first `down()` test assumed an empty table. The suite does not wrap tests in transactions (F-5), so the "well-formed rows" test leaves 4 committed correction rows, and `down()` correctly refused. This showed only in the full run; in isolation the test passed.
    - The restore half now runs in one outer transaction (delete correction rows and their command rows → `down()` → `up()` → rollback; PostgreSQL DDL is transactional).
    - Diagnosed by printing the masked exception in a scratch worktree copy only. The debug runs (`s4-debug`, `s4-debug2`) are excluded from the evidence.
  - **Full PostgreSQL suite after the fix** (`cor4-ev/s4-green2`): 81 tests; **the only 3 errors are the step 5 RED**. The new contract tests (strict `recordedAt`, every-kind round trip, `appliedCommand`) hit `Call to undefined method Correct::effectiveAt()` at `PostgresMemberRepository.php:247`: **K-2 itself, reproduced.** Every pre-existing PostgreSQL test and all 19 schema tests pass. 1 deprecation, pre-existing (`config/database.php` on PHP 8.5).
  - **Architecture:** `MembershipLedgerIsolationTest` flagged the new migration as touching the ledger from outside Membership. It is added to the exact-path allowlist, as F-4b's migration was.
  - **Regression (database-free):** Domain 189, Application 75, Infrastructure 22, Architecture 98.
  - Committed `8dfc363b6`.
- 2026-11-03: **Step 5 done (adapter encode / decode for every kind; K-2, Q-2, Q-6).**
  - **RED:**
    - the three contract tests from step 3 on PostgreSQL (`cor4-ev/s4-green2`): `Correct::effectiveAt()` undefined, which is K-2 reproduced;
    - `PostgresCorrectionDecodeTest` (`cor4-ev/s5-red`), 7 tests: **7 errors**. Six invalid stored histories have no `StoredMemberLogInvalid` yet; one valid history with every new kind cannot be decoded.
  - **GREEN:**
    - `encode()`: one branch per kind; no date accessor is called before the kind is known (**K-2 closed**). Every row sets every kind-specific column, so the CHECK decides. Annul / Void have a NULL date. A Correct stores its value, its consequences and `g2_version = 1`;
    - `decode()`: all five kinds;
    - `ConsequencesJson`: the Q-5 format. Its strict decoder compares keys as a set, because `jsonb` does not keep key order; it refuses an unknown `g2_version`;
    - `StoredMemberLogInvalid` (a `RuntimeException`): `load()` wraps decode and `Member::fromLog` failures (`InvalidArgumentException`, `UnexpectedValueException`, `JsonException`, `UnhandledMatchError`, `ValueError` only; any other `\Error` propagates);
    - Q-2: `recorded_at = max(clock, last + 1 µs)`.
  - **Existing test updated because of an approved behaviour change:** `test_t6_recorded_at_never_decreases_under_a_backward_clock` pinned the old tie value (`last`); it now expects `last + 1`, annotated with Q-2. This was its only failure in the first GREEN run (`cor4-ev/s5-green`, 1 failure of 88).
  - **Full PostgreSQL suite** (`cor4-ev/s5-green2`): **88 tests, 515 assertions, exit 0** (1 pre-existing deprecation).
  - **Regression (database-free):** Domain 189, Application 75, Infrastructure 22, Architecture 98.
  - Committed `144f1e947`.
- 2026-11-03: **Step 6 done (PostgreSQL race tests; AC-7).** `PostgresCorrectionRaceTest`: 3 cases × 8 rounds × 4 forked processes, through `DecideAndAppend::run` with explicit decide closures.
  - **No COR-3 handler is written or bound.** The `MembershipAct` passed is a **placeholder** (`End`): `MembershipAct` has no correction acts yet (COR-3: "`MembershipAct` extension"), and `FakeAuthority` grants every act. Authorisation is not under test.
  - **Properties checked every round:**
    - no child errors; every refusal is a contract reason;
    - the stored history reloads (step 1 validation), with contiguous seq;
    - **every stored Correct's consequences equal a fresh `decideCorrect` on its stored prefix** (g2 recomputed by re-decide, never by the adapter);
    - every appended outcome names a stored fact.
  - **The cases:**
    - Annul vs Correct of the same correction: exactly one Annul stored, at most one Correct;
    - two Corrects of the same fact with different values: at least one stored, each with recomputed consequences;
    - **Correct vs End on the adjacent period (FP §16.10 lost update): exactly one of the four stored.**
  - **Result** (`cor4-ev/s6-a`): **3 tests, 340 assertions, exit 0 on the first run**, as the plan expected ("none expected"). These are verification tests.
  - **Sensitivity** (`cor4-ev/s6-mutant-er-off`): with ER disabled in a scratch worktree copy only, the lost-update test **fails**. Round 0 stored both `C-1` and `E-3`. Its refusals (`value_unchanged`, `period_already_ended`) show the processes genuinely interleaved. The worktree was removed.
  - **No production change in this step.**
  - Committed `5304aaab7`.
- 2026-11-03: **Step 7 done (reload differential; AC-8).** `MemberRepositoryContract::test_every_bounded_c2_history_survives_storage_and_reload_unchanged`:
  - **Method:** every C-2 history the kernel accepts (H = 5, L ≤ 4; `C2Histories`) is replayed through the repository, one decision at a time on the freshly loaded Member, then reloaded. Facts with seq, and `resolve()`, must equal the in-memory original.
  - **Double:** all 7,470 non-empty histories, 81,051 assertions, 5 s, **0 mismatches**. Near-trivial, because the double stores objects.
  - **PostgreSQL:** every 10th history (`reloadDifferentialStride()` = 10, the runtime budget recorded in the test), about 750 histories, **14,827 assertions, 44 s, 0 mismatches** (`cor4-ev/s7-a`).
  - **Sensitivity on PostgreSQL** (scratch worktree copy only, then removed):
    - a mutant in which `ConsequencesJson::decode()` drops every stored trim **fails** (history D541: facts differ after reload; `cor4-ev/s7-mutant-drop-trims`);
    - **an earlier "mutant" run was invalid, my mistake:** `return [] ?: array_map(…)` still returns the real result, so it changed nothing and passed (`cor4-ev/s7-mutant-noop`, excluded from the evidence).
  - **No production change in this step.**
  - **Regression (database-free):** Domain 189, Application 76, Infrastructure 22, Architecture 98.

## 11. Open questions (outside this plan's decisions)

- OQ-01 and W2-2 (Ops): unchanged, and not needed for container runs.
- OQ-C2-1 / OQ-C2-2 (PO): kernel semantics, not persistence; untouched.
- V-11 (`end_kind` CHECK): Slice 2b.
