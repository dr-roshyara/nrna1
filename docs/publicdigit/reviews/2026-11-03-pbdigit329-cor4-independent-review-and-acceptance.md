# PBDIGIT-329 (COR-4): independent review and PO verdict

**Status:**
- §2 is the review report, recorded **verbatim** as returned (2026-11-03).
- §3 is the commissioning session's spot-check.
- §4 is the PO's verdict: **PENDING**.

**Reviewer:** a fresh general-purpose Claude sub-agent, commissioned on 2026-11-03 by session `93000efc-ad03-4c1d-9b3f-40966981efc3`, on the PO's instruction "Commission the COR-4 review". Brief: `2026-11-03-pbdigit329-cor4-independent-review-brief.md` (`42a8bbf04`).
- It was read-only on the repository.
- It worked in its own worktree at `acdedf1cd`, removed afterwards.
- It used PostgreSQL only through the pinned throwaway container: 13 runs, system identifiers listed in §2.

**Reviewed:** `660fc03a9`, `6da2594b3`, `5b134f033`, `5572ce595`, `8dfc363b6`, `144f1e947`, `5304aaab7`, `96d71183e`, `acdedf1cd`.

## 1. Commission (summary)

- AC-1…AC-11;
- correctness against the contract and Q-1…Q-7: the load-validation boundary, lossless encode / decode, schema vs kernel shapes, `down()`, Q-2 under concurrency, the Q-6 exception set, Q-7;
- evidence quality: the race-test placeholder act, the reload-differential sample, the adapter-mutation gap, survivor classification, the archive;
- the four changed tests;
- scope, regression and documentation.

Report sections A–G.

## 2. Report (verbatim)

# PBDIGIT-329 (COR-4): independent review report

Reviewer: a fresh general-purpose sub-agent. I worked read-only on the repository, from my own detached worktree at `acdedf1cd`. I used PostgreSQL only through `scripts/testing/membership-pg.sh` with the pinned image `postgres:15@sha256:7da5d534…a822` (server 15.19).

Line references are to `acdedf1cd`. Labels: **VERIFIED** (I ran it, or read it at the cited line), **INFERRED** (reasoning only), **NOT CHECKED**.

## A. Verdict recommendation

**ACCEPT WITH CONDITIONS.**

The core is sound and I could not break it:
- K-2 is closed.
- Every fact kind round-trips on PostgreSQL.
- Strictly increasing `recordedAt` (Q-2) holds under concurrency.
- The race tests catch both a kernel lost-update mutant and an adapter compare-and-set (CAS) mutant.
- The schema matches the kernel's shapes.
- Regression is green, and the archive hashes reproduce.

The defects are in error labelling, documentation, and one survivor classification. None of them lets a damaged history resolve: loading still fails closed.

Conditions:

| # | Condition | Needs a code change? |
|---|---|---|
| C-1 | **Q-6 wrapping (F-1).** Damaged provenance must surface as `StoredMemberLogInvalid`. Today it escapes as `ErrorException` or `TypeError`. Fix: `ProvenanceJson::decode` checks the shape and throws `UnexpectedValueException` / `InvalidArgumentException`; add a PostgreSQL decode test for it | **Yes** (small; Infrastructure only) |
| C-2 | **Exception message (F-2).** Either stop appending the cause's message (keep it as `previous`), or correct the docblock and guide claim that the message never carries stored payload. PO's choice | Yes for the first option; docs only for the second |
| C-3 | **Release gate (F-6).** Restore the clause "code and migration deployed together" in the migration docblock (`:22-24`), the Slice 1b amendment and guide 14 | No (docs) |
| C-4 | **Survivor classification (F-4).** Reclassify the `Member.php:390` separator mutant as killable (not equivalent) in plan §10, and either add a killing test or record it as accepted risk. It is COR-2 code, not COR-4's new code | Optional test |

## B. AC-1 … AC-11

| AC | Status | Evidence |
|---|---|---|
| AC-1 | **Met in substance** | VERIFIED: `test_every_fact_kind_round_trips_identically` passes on PostgreSQL (my run1 and run2), including the two-period Correct C8 (`[A0:[1,2)], [A2:[2,6)]`, re-derived database-free). `recordedAt` is pinned by the separate strict test. Gap: no committed test stores an **open-ended (∞) trim bound** on PostgreSQL. My probe (Correct with a trim ending at ∞) round-trips exactly (sysid `…5073`). |
| AC-2 | **Met in substance; Q-6 wrapping partial** | VERIFIED: all 18 `MemberLogValidationTest` cases pass. 6 adapter cases in `PostgresCorrectionDecodeTest` pass. The §4 rules share one `fromLog` path and one catch (`PostgresMemberRepository.php:91`). But damaged provenance is not wrapped (F-1), and three structural rules are absent (F-3). |
| AC-3 | **Met** | VERIFIED: `StorableValidTime.php:117-125`; `StorableValidTimeTest` 8/8; the contract unstorable-value test on PostgreSQL. The property test's limits are in F-9. |
| AC-4 | **Met** | VERIFIED: `encode()` branches on `instanceof` before any date accessor (`:268-290`). The CHECK forces a NULL date for annul / void (migration `:43`). The archived step-4 RED reproduced K-2. |
| AC-5 | **Met in substance** | VERIFIED: 19 schema tests pass. `down()` refuses (`:55-58`). The restored `kind_check` text is identical to the 1b migration (`2026_10_09…:64-67` vs `:72-75`). The test pins `down()` only by column names (F-10). |
| AC-6 | **Met** | VERIFIED: contract test 500/501/502 and T6 pass. My concurrent probe (frozen clock, 6 processes × 4 commands × 10 rounds): **0 of 120** adjacent pairs non-increasing (sysid `…5426`). |
| AC-7 | **Met** | VERIFIED: race tests green twice. ER-off mutant reproduced, failing as the archive records. My adapter **CAS-off** mutant is **killed by all three** race tests, including the "stored consequences = g2 on the stored prefix" check (sysid `…7218`). |
| AC-8 | **Met within bounds; sample weak on trims** | VERIFIED: the stride-10 run passes. My extension (offset 5, i.e. 747 other histories, plus **all 30** trim-bearing histories): 0 mismatches, 15,092 assertions (sysid `…5073`). F-7: the bounded set contains no two-period and no open-ended Correct. |
| AC-9 | **Met, with one misclassified survivor** | VERIFIED: regression and archive reproduce (section E). F-4: one survivor classified "equivalent" is not. I did not re-run Infection. |
| AC-10 | **Met** (gate wording: F-6) | VERIFIED: `git diff --stat 660fc03a9^ acdedf1cd -- app database routes config` shows exactly 6 files, all named in the plan (StorableValidTime, Member, ConsequencesJson, PostgresMemberRepository, StoredMemberLogInvalid, the migration). No routes, config, handlers, `MembershipAct` changes or event storage. |
| AC-11 | **Met so far** (PO verdict pending by design) | VERIFIED: guide 14 matches the code (spot-checked snippets against `:91`, `:284-287`, the `ConsequencesJson` format and the test counts 18/8/20/7). The amendment matches the migration column for column. |

## C. Findings

### F-1 (minor; VERIFIED): damaged provenance escapes Q-6 wrapping

`load()` catches only five exception types (`PostgresMemberRepository.php:91`). `ProvenanceJson::decode` (`ProvenanceJson.php:39-59`) indexes the array without checking it.

Reproduction: a PostgreSQL probe inserts an admit row with damaged provenance, then calls `load()`:

| Stored provenance | Exception raised |
|---|---|
| `{"kind":"decision"}` | `ErrorException` |
| `{"kind":"decision","actorOrSource":5,…}` | `TypeError` |
| `[1,2]` | `ErrorException` |
| `{"kind":"bogus"}` | `StoredMemberLogInvalid` (correct) |

(sysid `7703830335265595426`)

Effect: the load still fails closed, but the failure is mislabelled. Plan §4 promises "every decode failure becomes `StoredMemberLogInvalid`".

### F-2 (minor; VERIFIED): the exception message carries stored payload

The docblock (`StoredMemberLogInvalid.php`, the "never contains provenance or other stored payload" line) is contradicted at `:82`, which appends `$cause->getMessage()`.

Reproduction:
- a provenance kind `"PAYLOAD-in-provenance"` produces `…is invalid: "PAYLOAD-in-provenance" is not a valid backing value for enum …ProvenanceKind`;
- a trim date `"PAYLOAD-in-trim"` produces `…Not a calendar date: 'PAYLOAD-in-trim'.`

Fact ids, `end_kind` values and dates are echoed the same way. Guide 14 repeats the claim.

### F-3 (minor; VERIFIED): structural rules missing from load validation

`requireStructurallyValid` (`Member.php:47-85`) accepts logs that no `decide*` call can produce, even though these properties do not depend on time or authority. Reproduction: `fromLog` + `resolve()` both succeed for:
- an Annul with `decidedAt = null` (`decideAnnul` refuses with `DecidedAtRequired`, `:215`);
- an Annul of a rule-provenance target (`TargetHasNoDecisionProvenance`, `:225`);
- a Correct that trims its own period (`trimConsequences` skips its own period, `:314`).

The database is equally loose: no CHECK requires `decided_at` for annul.

These logs also load, but rightly so under the approved R-12 boundary, because the checks are guards or G\*:
- `InvalidInitialTerm`, `AdmissionBasisMissing`;
- a legacy End without attested provenance, a second End of an ended period;
- `ValueUnchanged`;
- a Correct whose stored consequences differ from g2.

### F-4 (minor; VERIFIED): one survivor classified "equivalent" is killable

The revival key `cause . "\0" . period` (`Member.php:390`). Removing the separator is **not** equivalent. My reasoning-only check agrees that dropping the period part or swapping the order is equivalent: in-force status is per cause (`Resolution::trimsInForce` `:72-83`).

Reproduction, all accepted by `decide*`:
1. Admit A at 0, End EA at 10; Admit Y at 10, End EY at 20; Admit Z at 30, End EZ at 40; Admit 1A at 40.
2. C corrects EZ to 42, trimming 1A.
3. C1 corrects Y to 8, trimming A.
4. X annuls C1; V voids X.

On V, the real key gives `revivedTrims = [[C1, A]]`; the separator-free key gives `[]` ("C"+"1A" collides with "C1"+"A").

Caveat: I did not re-run Infection, so I cannot confirm exactly which operators it produced at `:390`. The plan explicitly names "separator" among the equivalents.

### F-5 (minor; VERIFIED): most of the Q-6 catch list is untested

Full PostgreSQL suite against each adapter mutant:

| Mutant | Result | sysid |
|---|---|---|
| catch `\Throwable` | survives | `…8514` |
| drop `\JsonException` | survives | `…8290` |
| drop `\UnhandledMatchError` | survives | `…2882` |
| drop `\ValueError` | survives | `…0754` |

Only the `InvalidArgumentException` and `UnexpectedValueException` paths are pinned. Guide 14's pitfall "Do not catch `\Throwable`" is therefore unenforced. `JsonException` and `UnhandledMatchError` are probably unreachable behind jsonb and the CHECK constraints (INFERRED).

### F-6 (minor; VERIFIED): release-gate wording is inconsistent

- The migration docblock (`:22-24`), the 1b amendment and guide 14 list: OQ-01, the §10.2 preflight, `lc_messages`, ARB-MEM-01.
- They omit "code and migration deployed together", which appears in plan §2 (line 30) and the PBDIGIT-264 acceptance (`:125`).
- That clause is the load-bearing one here: after the rename, pre-COR-4 code that reads `period_fact_id` breaks (INFERRED).

### F-7 (observation; VERIFIED): the differential barely exercises consequences

Profile of `C2Histories::accepted(5, 4)` (7,470 histories):
- 30 contain a trimming Correct, 0 a two-period Correct, 0 an open-ended trim;
- the stride-10 PostgreSQL sample contains 3 trimming histories.

Two-period consequences are covered on PostgreSQL only by the single every-kind test. Open-ended trims are covered only database-free (`ConsequencesJsonTest`) and by my probe. Whether `decide*` can reach an ∞ trim at all: NOT CHECKED.

### F-8 (observation): Q-2 is correct under concurrency (VERIFIED by reading, test and probe; plus reasoning)

- The CAS `UPDATE … RETURNING` (`:195-200`) holds the member row lock until commit.
- Under the enforced READ COMMITTED (`:169`), the `max(recorded_at)` read (`:216-218`) is a new snapshot that sees every earlier commit.
- So no tie or decrease is reachable.

Probe: 0 of 120 non-increasing pairs. A mutant that moves the max read **before** the CAS also survives the suite and the probe (sysid `…5538`). For callers whose version came from a load, the order is not load-bearing, because a stale caller fails the CAS (INFERRED). The race tests' `verify()` does not assert strict `recordedAt`.

### F-9 (observation; VERIFIED by reading): limits of the property test

The architecture test counts only `TimePoint`-typed constructor parameters, and checks that the text `instanceof X` appears anywhere in the file. It would not catch:
- a future fact whose dates sit only inside value objects or arrays;
- a partial check. Trim bounds are pinned by `StorableValidTimeTest` instead.

It is sound for its stated scope.

### F-10 (observation)

- The `down()` test checks only column names. Constraint and NOT NULL restoration I VERIFIED by reading.
- `down()` counts, then ALTERs, without a lock. It still fails safe inside the migrator's transaction: a concurrent annul breaks `SET NOT NULL`, a correct breaks the re-added 1b CHECK (INFERRED).

### F-11 (observation; INFERRED)

The self-target schema case accepts SQLSTATE 23503 or 23514. A self-referencing FK is satisfied by the row itself, so only the CHECK can refuse. The assertion is permissive, but removing the CHECK would still fail the test.

### F-12 (observation; VERIFIED)

- `s6-mutant-er-off` ran at `0e02433af`: a docs-only KnowledgeOS commit between steps 5 and 6, not in the commit table. It is code-equivalent and disclosed via `run.txt`.
- ER-off is a kernel mutant that would also fail sequentially, so the CAS-off result above is the concurrency-specific evidence.
- The evidence README's s6 row does not mention the placeholder act. The test docblock (`:33-34`, `:98`), plan §10 and guide 14 do.

### F-13 (observation)

The double and PostgreSQL differ observably only for hand-built invalid `Decision`s: the double raises a raw `InvalidArgumentException` on load, PostgreSQL raises `StoredMemberLogInvalid` or a CHECK `QueryException`.

## D. Decisions for the PO (recommendations only)

1. **F-3:** should Annul `decidedAt` present, Annul-target decision provenance, and "a Correct does not trim its own period" join load validation as structural rules? I recommend yes, under COR-4 or a follow-up. The rest stay excluded, per R-12.
2. **F-2:** fix the message, or narrow the claim.
3. **F-4:** a killing test for `:390`, or record it as accepted risk (COR-2 scope).
4. **F-7:** a PostgreSQL test with an ∞ trim bound, or show that it is unreachable.
5. **The placeholder `MembershipAct::End` in the race tests:** acceptable, since authorisation is orthogonal and it is disclosed. COR-3 should re-run the races with the real acts.

## E. Evidence consistency

- **Archive hashes:** `sha256sum -c` passes for all 12 folders. VERIFIED.
- **Archive counts all reproduce from `suite.txt`:**
  - s4-red: 19 tests (17 failures, 2 errors);
  - s4-green2: 81 tests (3 errors);
  - s5-red: 7 errors;
  - s5-green: 88 tests (1 failure); s5-green2: 88 OK;
  - s6-a: 3 tests / 340 assertions;
  - s6-mutant-er-off: 1 failure;
  - s7-a: 14,827 assertions;
  - s7-mutant-drop-trims: 1 failure;
  - final-run1..3: 92 tests each, 15,674 / 15,674 / 15,678 assertions, sysids `…638818`, `…160738`, `…672034`.
- **Commits:** the final runs are at `96d71183e`. Step 8 adds only database-free tests and docs, and my runs at `acdedf1cd` give 92 tests.
- **Database-free counts reproduce:** Domain 189, Application 76, Infrastructure 42, Architecture 98. `tests/Unit/Testing`: 168 OK. Per-class counts match guide 14: `MemberLogValidationTest` 18, `ConsequencesJsonTest` 20, `StorableValidTimeTest` 8.
- **No credential-shaped token** in the archive or in my evidence.
- **Mismatches:** the release-gate wording (F-6) and the `:390` classification (F-4).

## F. Residual risks

- The fail-closed load can be mislabelled (F-1). Operators may see a `TypeError` or `ErrorException` instead of the infrastructure failure.
- Structural gaps (F-3): a corrupted row of those three shapes would load and resolve.
- ∞ trims and multi-period consequences have thin PostgreSQL coverage (F-7).
- The catch-list and read-ordering choices are unpinned by tests (F-5, F-8).
- All PostgreSQL evidence is on 15.19 only (W2-2 provisional; OQ-01 open). The migration has never run outside the throwaway cluster, and the gate wording lacks the deploy-together clause (F-6).
- Race coverage is 8 rounds × 4 processes with real (non-COR-3) acts substituted.

## G. Out of scope (candidate backlog items, not fixes)

- `ProvenanceJson::decode` predates COR-4 and has no shape validation. That is the root of F-1, and it also affects pre-COR-4 Admit / End loads.
- The `Member.php:390` revival key (COR-2): a collision-proof key or a test.
- `end_kind` CHECK (V-11, Slice 2b), already noted in the plan.

## Commands and container runs

**Setup and database-free suites:**
- `git worktree add --detach <scratch>/rev329/wt acdedf1cd`, then `composer install --no-scripts`.
- `phpunit --no-configuration --bootstrap vendor/autoload.php` on Membership Domain, Application and Infrastructure `Organisational`, and on `tests/Architecture/Membership*Test.php`.
- `vendor/bin/phpunit tests/Unit/Testing` with the default configuration.
- `git diff --stat …`, archive `sha256sum -c`, and database-free probes (`loadprobe.php`, `revivalkey3.php`, `c2stats.php`, `everykind.php`, a payload-message check).

**Container runs**, all via `membership-pg.sh` with the pinned image, all `podman`-removed:

| Run | Scope | Result | system_identifier |
|---|---|---|---|
| run1 | full suite | 92 tests / 15,672 assertions, exit 0 | 7703829757335474209 |
| run2 | full suite | 92 tests / 15,676 assertions, exit 0 | 7703833371582631970 |
| probe1 | reviewer probes only | — | 7703830335265595426 |
| M1 (`+1` removed) | full suite | killed: 3 failures (T6, the strict contract test, my probe) | 7703830687677014050 |
| M2 (max read before CAS) | full suite | survives | 7703831008282005538 |
| M3 (catch `\Throwable`) | full suite | survives | 7703831347795988514 |
| M4 (no `JsonException`) | full suite | survives | 7703831678076768290 |
| M5 (no `UnhandledMatchError`) | full suite | survives | 7703831993236942882 |
| M6 (Correct `decidedAt` dropped on decode) | full suite | killed | 7703832325726744610 |
| M7 (no `ValueError`) | full suite | survives | 7703832504940830754 |
| ER-off | race tests only | killed: lost-update test, round 0 stored C-0 and E-3 | 7703832844285153314 |
| M8 (CAS-off) | race tests and Q-2 probe | killed: all 3 race tests | 7703833036235997218 |
| diff-offset5-plus-trims | differential variant and ∞-trim probe | OK | 7703833138853335073 |

Runs M1–M7 were on the full suite plus my probes (97 tests).

## Cleanup

- **Worktree:** mutants were restored from pristine copies after each run (`git status` clean apart from my probe files, which I moved out before run2). The worktree was then removed with `git worktree remove --force` (confirmed: `worktree list` shows none, and the path is gone).
- **Containers:** `podman ps -a` shows no `pbd-membership` container.
- **Main tree:** untouched. Its HEAD has since moved to `f2920dd90` through others' KnowledgeOS commits, not mine.
- **Left in the scratchpad** (not deleted): `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/93000efc-ad03-4c1d-9b3f-40966981efc3/scratchpad/rev329/ev/` (13 run folders), `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/93000efc-ad03-4c1d-9b3f-40966981efc3/scratchpad/rev329/tools/` (probe scripts and probe tests) and `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/93000efc-ad03-4c1d-9b3f-40966981efc3/scratchpad/rev329/mut/` (mutant definitions). They contain no credentials.

## 3. Spot-check by the commissioning session (2026-11-03)

All database-free scratch scripts at `f2920dd90`; no tree file was edited.

- **F-1, reproduced.** `ProvenanceJson::decode` on the reviewer's inputs gives:

  | Input | Exception |
  |---|---|
  | `{"kind":"decision"}` | `ErrorException` (under an error-to-exception handler, as Laravel installs) |
  | `actorOrSource: 5` | `TypeError` |
  | `[1,2]` | `ErrorException` |
  | `{"kind":"bogus"}` | `ValueError` (wrapped by `load()` → `StoredMemberLogInvalid`) |

  Neither `ErrorException` nor `TypeError` is in the catch list at `PostgresMemberRepository.php:91`.
- **F-4, reproduced.**
  - In the reviewer's 11-fact history, the Void is accepted with `revivedTrims = [[C1, A]]`.
  - Before it, the trims in force have the separator-free key `"C1A"`: C trimming period "1A".
  - That equals C1 + "A", so the separator-free mutant would filter the revival out.
  - **My plan §10 classification of `Member.php:390` (the separator variant) as equivalent was wrong.** I had reasoned only about whole-cause in-force status, not about string-key collisions between different (cause, period) pairs.
- **F-2, confirmed by reading:** the docblock (`StoredMemberLogInvalid.php:11`) says the message never contains stored payload; `:17` appends `$cause->getMessage()`.
- **Worktree cleanup:** `git worktree list` shows no reviewer worktree.
- **Not re-checked by me:** F-3, F-5…F-13 and the container runs. They are taken from the report with its own evidence labels.

## 4. PO verdict

**PO decision (2026-11-03): option (A), fix the conditions first, then accept**, with C-2 as recommended (PO words: "A, with C-2 as recommended"). That covers:

| Item | Decided handling |
|---|---|
| C-1 | `ProvenanceJson::decode` validates the shape; damaged provenance → `StoredMemberLogInvalid`; a PostgreSQL decode test |
| C-2 | **the message no longer includes the cause's message**; it names the Member, the organisation and the cause's class only; the cause stays as `previous` |
| C-3 | the release-gate wording gains "code and migration deployed together" (migration docblock, Slice 1b amendment, guide 14) |
| C-4 | a killing test for the `Member.php:390` separator collision; plan §10 classification corrected |
| F-3 | three structural load rules added: an Annul has `decidedAt`; an Annul's target has decision provenance; a Correct does not trim its own chain period |
| F-7 | a PostgreSQL test storing and reloading an open-ended (∞) trim |

**Conditions closed (2026-11-03; plan §10 "Review conditions closed"; evidence `cond-red`, `cond-green`):** C-1 (shape-checked `ProvenanceJson`; RED on PostgreSQL reproduced the review exactly), C-2 (message without payload, exact message pinned), C-3 (deploy-together clause in all three places), C-4 (killing test; the separator mutant fails it), F-3 (three structural rules), F-7 (∞ trim round trip). Full PostgreSQL suite 96 OK; regression green.

**Acceptance: PENDING.** It follows on the PO's word, after the conditions are committed. *(The original list of decisions needed is kept below.)*

The decisions needed (as listed before the PO's decision):
1. the verdict (the reviewer recommends ACCEPT WITH CONDITIONS C-1…C-4);
2. C-2: fix the message, or narrow the claim;
3. C-4: a killing test, or accepted risk;
4. F-3: whether the three structural rules join load validation;
5. F-7: an ∞-trim PostgreSQL test, or reachability shown.
