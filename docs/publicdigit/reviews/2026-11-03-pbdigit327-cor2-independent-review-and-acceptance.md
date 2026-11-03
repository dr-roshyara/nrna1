# PBDIGIT-327 (COR-2): independent review and PO verdict

**Status:**
- §2 is the review report, recorded **verbatim** as returned (2026-11-03).
- §3 is the commissioning session's spot-check.
- §4 is the PO's verdict: **ACCEPTED** (2026-11-03, after the conditions were closed; §4.1).

**Reviewer:** a fresh general-purpose Claude sub-agent, commissioned on 2026-11-03 by session `93000efc-ad03-4c1d-9b3f-40966981efc3`, on the PO's instruction "commission the COR-2 review". Brief: `2026-11-03-pbdigit327-cor2-independent-review-brief.md` (`09e89f3df`).
- It was read-only on the repository.
- It worked in its own clean worktree at `763a47cf3`, removed afterwards.
- It used no database.

**Reviewed:** commits `abb81b1c5`, `58b89ab44`, `739875e1c`, `e5836a816`, `01fcbc9e3`, `763a47cf3`.

## 1. Commission (summary)

- AC-1…AC-9;
- semantic correctness against the contract (guard order, value precedence, stage 2, LiveCut, g2, CFC+, ER, D-COR-5 (A));
- evidence independence, including a check beyond the L = 5 bound;
- the four PO verdict points (P-2 reading, ER scope, the ≤ 1-consequence argument, guide 12);
- deviations, regression, code quality, documentation.

Report sections A–G.

## 2. Report (verbatim)

## PBDIGIT-327 (COR-2): independent review report

**Reviewer:** a fresh sub-agent with no context from the implementing session. I worked in my own detached worktree at `763a47cf3`, with no database, artisan or `.env`, and I never wrote to the main tree.

**Headline:**
- I found **no production defect**. The kernel matches the PHP port, and the port matches the Python oracle, everywhere I tested beyond the committed bounds.
- **The ≤ 1-consequence argument is false** (R-4 point 3). The two survivors classified as "equivalent" (`Member.php:269` ArrayOneItem and `Resolution.php:302` Continue_) are killable at 6 facts. A test gap therefore remains in a real code path: consequences on several periods.

---

### A. Verdict recommendation: ACCEPT WITH CONDITIONS

| # | Condition | Code change? |
|---|---|---|
| C-1 | Add killing tests for `Member.php:269` and `Resolution.php:302`. Use the 6-fact refused case and the 9-fact accepted case in F-1. Withdraw the "equivalent under ≤ 1 consequence" classification in plan §10 step 7, and record the correction. | **Tests only.** No production change. |
| C-2 | Record that ER (`PeriodAbsorbed`) also refuses **reversals**, not only Correct and End (F-5). Have the PO confirm the ER scope (D-2). | Documentation only |
| C-3 | Record the COR-4 obligations where COR-4 will see them (F-6, F-7, F-8): R-1a-6 for the corrected value; INV-TGT at decode; the "≤ 1 consequence per period" rule enforced when a `Correct` is rebuilt. | Documentation / backlog only |

Nothing I found needs a change to `app/`.

---

### B. Acceptance criteria AC-1 … AC-9

| AC | Status | Evidence |
|---|---|---|
| AC-1 | **Met in substance** | Guard order read at `Member.php:196-221`, matching plan §10 step 3 (VERIFIED). §10a Correct (6) `HolderChangeNotAllowed` is not applicable under D-COR-3; the command cannot carry a holder (INFERRED, sound). The multi-period consequence path has no unit test (F-1). |
| AC-2 | **Met** | I regenerated the fixture with `G6_C2_L=5 python3 -I …/g6_c2_golden.py`. It is byte-identical to the committed `Fixtures/g6_c2_golden.json`: sha256 `7f44bd9b…fe3d`, stream sha `498b2451…b737`, oracle sha `1e05fb98…568c`, the same as the oracle file (VERIFIED). |
| AC-3 | **Met as stated** | The test passes. My independent DFS harness reproduces 277,835 decisions / 139,722 accepted / 0 mismatches at H = 5, L = 5 (VERIFIED). The committed differential does **not** compare `revivedTrims`, and no revival is reachable at L = 5 (F-2). |
| AC-4 | **Met within L = 4** | `C2PropertyTest` is green (VERIFIED). P-CFC compares the kernel with the kernel on F ∖ D(c). That is specification-derived, but it is not independent of `Resolution`. Multi-consequence histories cannot be reached at L = 4 (INFERRED from F-1, which needs at least 6 facts). |
| AC-5 | **Met** for "refuse" | 616 `period_absorbed` at L = 5 (35 at depth 4 + 581 at depth 5) in the fixture (VERIFIED). "Changes nothing else": NOT CHECKED. |
| AC-6 | **Partially met** | Re-run reproduces the figures exactly (VERIFIED): 408 mutants, 368 killed, 2 errored, 26 escaped, 12 not covered; MSI 90 %, mutation code coverage 97 %, covered-code MSI 93 %. **Two survivors are misclassified** (F-1). |
| AC-7 | **Met** | All VERIFIED, in my worktree: Domain 169 OK (542,368 assertions, 36 s); Application 62 OK; Infrastructure 22 OK; the 7 `tests/Architecture/Membership*Test.php` files 97 OK; `tests/Unit/Testing` 168 OK (1 deprecation, 1 PHPUnit deprecation). |
| AC-8 | **Met** | `git diff --stat abb81b1c5^ 763a47cf3`: the only `app/` changes are the 8 kernel files in `Domain/Organisational`. Everything else is tests and fixture, guide 13 and its index, the plan, story and session log, and unrelated KnowledgeOS docs from interleaved commits. No persistence, application or migration files (VERIFIED). |
| AC-9 | **Partially met (in progress)** | Guide 13 exists. Every API it names exists in the code (VERIFIED by grep). This review is still open, then the PO verdict. |

---

### C. Findings

**F-1 · major (evidence / test gap) · VERIFIED.** The "≤ 1-consequence" equivalence is false, and both survivors are killable. Infection confirmed the two mutants are exactly:
- `Member.php:269`: `return $consequences` → `count(...) > 1 ? array_slice(...,0,1,true) : ...` (ArrayOneItem);
- `Resolution.php:302`: `continue` → `break` (Continue_).

Reproductions (tokens as in the golden line format):
- **6 facts, H = 5, refused decision:** `A0@1 E0@3 A1@3 E1@4 A2@4 C4@2`.
  - Original kernel, port and Python: `period_absorbed`. g2 computes trims on **two** periods: A1 absorbed, A0 partially.
  - Both mutants: `overlapping_membership_periods`.
  - So an exhaustive differential at L = 6 would kill both. The equivalence argument only considered **accepted** Corrects, but these mutants also change the reason on refused ones.
- **9 facts, H = 10, accepted, two live periods trimmed:** `A0@0 E0@2 A1@9 C2@4 E1@6 A2@8 N3 C5@2 C2@1`.
  - The kernel accepts with `trim A0[1]` and `trim A2[2,3,4,5]`, matching the port.
  - Both mutants refuse with `overlapping_membership_periods`.
  - The step `N3` (annul of the Admit correction) leaves A1 with an **empty BaseClaim**, value 9 > End 6. The later correction is then a two-sided extension. This breaks the plan's argument ("a contiguous extension cannot partially trim two periods").
- **8 facts:** `A0@0 E0@3 A1@3 E1@6 A2@6 C(A2→4) Annul(A1) C(C→2)` produces consequences on A0 and on the **dead** period A1, through inheritance.
  - With `C(A1→4)` and `Annul(Annul A1)` appended, the original refuses `period_absorbed` and both mutants refuse `overlapping_membership_periods`.

FP §13.6 case B ("one cause → several trims") already anticipates several trims per cause. Kernel behaviour equals the oracle in every case, so this is not a code defect.

**F-2 · minor · VERIFIED.** `TrimRevived` data is not part of the committed differential (`C2ExhaustiveDifferentialTest.php:57-58` compares lifetimes and consequences only), and no revival can be reached at L ≤ 5 (0 revival decisions at H = 5, L = 5).
- The unit tests cover it, and the claimed kill reproduced.
- My harness compared `revivedTrims` with an independent port-side computation ("active ∧ not superseded" after, minus before, minus own). It found 0 mismatches over 81 (H4/L6) + 291 (H5/L6) + about 1,250 random decisions that carried revivals.

**F-3 · observation (contract) · VERIFIED.** P-4 is a decision-time guard only. An Annul of a Correct can still leave a live period whose End is before its start, i.e. an empty BaseClaim: `A0@0 E0@2 C1@4 C0@3 N2` gives `A0` claim `[]`, accepted by kernel and port. This is the state that enables F-1's live two-period trim. It is consistent with the oracle (`base_claim` uses `max(s, e)`). Contract §3.3 wanted to avoid exactly this degeneracy for Correct. PO / contract question.

**F-4 · observation (P-1 scope) · VERIFIED.** P-1 covers only a voided direct target or a voided root (`Member.php:210`). Two cases are accepted and stay inert, matching the oracle:
- a Correct of a chain whose **intermediate** link is voided: `A0@1 C0@2 C1@3 V1 C2@0`;
- a Correct(End) whose period's **Admit** is voided: `A0@0 E0@2 V0 C1@3`.

P-1's rationale ("never happened") arguably covers both. PO question; not a defect against P-1 as worded.

**F-5 · minor (documentation) · VERIFIED.** ER sits at `InvariantSet.php:376`, checked last. It applies to every decision, **including reversals**. Example: the appeal `Annul(Annul A1)` in the 10-fact history of F-1 is refused `period_absorbed`. Plan §10 step 6 names only End as the effect "beyond corrections".

**F-6 · minor (COR-4) · VERIFIED.** `Member::fromLog` accepts a `Correct` that targets itself. On that log, `decideCorrect` with the self-targeting fact as target did not terminate; it was killed by a 20 s timeout, presumably because `Resolution::rootOf`, `Resolution.php:125-130`, recurses without end. `resolve()` itself still returns. This cannot be reached through `decide*`. COR-4 decode must enforce INV-TGT.

**F-7 · minor · VERIFIED.** The R-1a-6 deferral (Correct is not an `EffectiveDatedFact`) is recorded only in plan §10 step 3 and guide 13 `:18`, `:49`. There is no COR-4 backlog item, and contract §14.5 has no C-4 criterion for it.

**F-8 · observation · VERIFIED (read).** `Correct::consequences()` documents "at most one per trimmed period" (`Correct.php:36`). The constructor (`:18-34`) does not enforce it. This matters only for COR-4 decode.

**F-9 · observation · INFERRED.** `revivedTrims` also reports trims that come into force for the **first** time, e.g. when an inert Correct's target is revived. "Revived" is then a naming stretch. Its definition is the same in the kernel (`Member.php:324-333`) and in my independent port-side computation of the same "in force" definition. It is consistent with plan §4.

**F-10 · observation · VERIFIED.** Plan §10 calls `Resolution.php:217` "not covered". My Infection run reports it **escaped**. Run directly against the full kernel suite (a `break` mutant in scratch), it fails 3 tests, so the claim that it is killed holds. It also changes resolution visibly: `A0@1 N0 C0@0 A1@3` makes A1 vanish.

**F-11 · observation (COR-3 / COR-4 performance) · INFERRED.** One `decideCorrect` runs about 6 full `Resolution::of` / `trimsInForce` passes: `trimConsequences` `Member.php:241-249`, `accept` `:310`, `revivedTrims` `:327-330`. `keepsPeriodOrdered` calls `isLive` per fact. Record for COR-3 / COR-4 only.

**R-2 semantic checks, all VERIFIED by reading at `763a47cf3` unless stated:**
- **`TargetNotLive`:** never returned by `decideCorrect` (`Member.php:194-226`).
- **Annulled target accepted and inert, then revived:**
  - a Correct of an annulled Admit is accepted inert (`A0@1 N0 C0@0`, F-10's history);
  - Annul(Annul) and Void(Annul) revival follow from `live()` / `value()` over live facts.
  - Checked against the port over 6.2 M decisions.
- **Value precedence:** `value()` (`Resolution.php:149-166`) uses the latest **live** Correct by seq, recursively. That matches oracle `value`. Live and active coincide for a live root, as argued in the docblock (INFERRED; I found no counterexample).
- **Stage 2:** `Resolution.php:211-224` ("active iff target active").
- **LiveCut:** the cause is in force when active and not superseded by a live Correct (`:252-265`). Each cut is clipped to the claim of the cause's chain period (`:296-312`).
- **g2:** `Member.php:238-270` matches oracle `g2`, with a per-period merge.
- **D-COR-5 (A):** Annul and Void create no consequences.
- **Guard order:** matches plan §10 step 3.

**R-3 independence. VERIFIED by reading.**
- The kernel is structurally different from the port: a backward liveness pass, a forward activity pass, a claims map, and `IntervalSet`, against the port's recursive point sets. I saw no shared helper decomposition.
- **Shared-premise risk:** the C-2 restrictions P-1, P-3 and P-4 are written by the same session in both `g6_c2_golden.py:60-73` and the port `:349-372`, and the kernel matches them. This is a correlated-specification risk; the Python oracle core is the independent part.
- **Port fidelity beyond the golden:** VERIFIED. The PHP port and the Python generator give identical stream sha256 at **H = 5, L = 6**: 6,208,931 decisions, sha `62e3793b…88ef`.

---

### D. R-4 recommendations (for the PO to decide)

**1. P-2 reading.**
- **Recommendation:** record the representation honestly as "the removed interval in absolute valid-time dates, as stated by the decision, clipped at resolution". Treat the relative / absolute distinction as **nominal** in this implementation.
- **Reasons:**
  - The stored content equals the oracle's absolute set: consequences compared equal over 6.2 M + 1.75 M + 2.4 M decisions (VERIFIED).
  - FP §12.4 / §12.1 cite `multicause*.py` "relative" vs "absolute". Those scripts are not in the repository, so what FP meant by "relative" (e.g. cause-relative offsets) is NOT CHECKED.
  - FP states that both representations pass under G\*.
- If the PO intends a cause-relative meaning, it is not implemented and should be specified for COR-4.

**2. ER scope.**
- **Recommendation:** keep ER as a G\* invariant on **every** decision, and record that this explicitly extends the PO's §2 wording ("a correction … another period").
- **Reasons:**
  - Record §3 places ER in the enforcement set I. An invariant scoped to one decision type would let absorbed states arise through End or Annul/Void, and would break the "every reachable state satisfies I" safety form of FP §12.2.
  - It matches the oracle and golden.
- **Record also:** End self-absorption (21 at L = 5), and reversal refusals such as an appeal refused `period_absorbed` (F-5).
- The alternative, ER for Correct only, needs an amendment of the decision record and diverges from the oracle.

**3. The ≤ 1-consequence argument: falsified.**
- Smallest refused counterexample: 6 facts at H = 5.
- Accepted two-live-period counterexample: 9 facts at H = 10. Search details are in E.
- **Recommendation:** condition C-1. Optionally add an L = 6 differential as a `membership-slow` group: about 10 min in my harness, 6.2 M decisions.

**4. Guide 12.**
- **Recommendation:** a separate documentation backlog item under PBDIGIT-264, not part of COR-2. It is unrelated to COR-2's scope, and the "discovered work becomes backlog" rule applies.

---

### E. Evidence consistency

| Claim | Status |
|---|---|
| 277,835 decisions / 139,722 accepted / 0 mismatches at L = 5; 616 `period_absorbed` | reproduces |
| L = 4: 14,230 decisions and 35 absorbed at depth 4 | reproduces |
| 57 `period_lifetime_not_contiguous` at L = 5; INV-COV never fires | reproduces |
| Fixture sha `7f44bd9b…`; oracle sha `1e05fb98…` | reproduces |
| Infection: 408 / 368 / 2 / 26 / 12; MSI 90 / 97 / 93 | reproduces exactly |
| Stage-2 kill (`Resolution.php:216`): a mutant with stage 2 removed fails `test_the_trims_of_a_correction_whose_target_is_annulled_stop_applying`, yet the L = 5 differential still passes (139,725 assertions) | reproduces |
| `revivedTrims` exactness kill: dropping `!isset($before…)` at `Member.php:331` fails `test_only_the_restored_trim_is_reported_as_revived` | reproduces |
| Regression counts 169 / 62 / 22 / 97; `tests/Unit/Testing` 168 | reproduce |
| Commits, story status and session log (`.claude/sessions/2026-11-03.md:89-178`) | consistent |
| `Resolution.php:217` "not covered" | does not reproduce: escaped in my run, though killed directly (F-10) |
| "Equivalent under ≤ 1 consequence" (`Member.php:269`, `Resolution.php:302`) | **does not hold** (F-1) |

**Beyond-bounds searches.** All runs compared the kernel with the port on verdict, reason, lifetimes, merged consequences and `revivedTrims`. All found 0 mismatches.

| Run | Seed | Decisions | Accepted | Corrects with consequences on more than one period (two or more of them live) | Time |
|---|---|---|---|---|---|
| Exhaustive H = 4, L = 6 | — | 1,753,870 | 851,737 | — | 95 s |
| Exhaustive H = 5, L = 6 | — | 6,208,931 | 3,204,004 | 0 accepted | 620 s |
| Random walk H = 8, L ≤ 10, 2,000 walks | 11 | 1,199,258 | — | — | — |
| Random walk H = 8, L ≤ 10, 2,000 walks | 12 | 1,196,976 | — | — | — |
| Random walk H = 10, L ≤ 10, 1,500 walks, 6-step build-up of Admit / End first | 21 | 1,206,582 | — | 37 (6) | — |
| Same as above | 22 | 1,209,489 | — | 22 (2) | — |

Spot check: at seed 21 (H = 10, 300 walks), the same random harness detected all three mutants (`:216`, `:269`, `:302`).

---

### F. Residual risks (for the acceptance record)

1. Kernel, port, test expectations and the P-1 / P-3 / P-4 restriction layer come from one AI session. Only the Python oracle core is independent, and it is itself AI-authored (contract §7).
2. Exhaustive evidence stops at L = 6 (H ≤ 5). Behaviour with 7–10 facts rests on random sampling only.
3. Degenerate states can be reached through reversals: live periods with an empty BaseClaim (F-3) and inert Corrects with a voided intermediate link or Admit (F-4). The contract is silent on both.
4. COR-4 must add guards the kernel does not have: INV-TGT (F-6), consequence-shape validation (F-8), R-1a-6 (F-7).
5. Performance of repeated resolution (F-11).

---

### G. Out of scope (candidate backlog items, not fixes)

- Guide `12_pbdigit264_part2.md`, never written.
- Contract question on F-3 (P-4 / C-S0-1 bypass through Annul) and on the F-4 P-1 extension.
- `tests/Architecture` with `--filter Membership` and `--no-configuration` also matches `GovernanceRuntime\VoterStrategyConvergenceTest::test_unassigned_eligible_query_defaults_to_full_membership_mode`. That test errors (PBDIGIT-283 boot guard) when run without `phpunit.xml`. It is not a COR-2 regression.
- Observation: during the session the main branch HEAD moved to `136404415` (another session's commit, not mine).

---

### Commands run (paths abbreviated; scratch = the `rev327` folder in the session scratchpad)

- `git worktree add --detach <scratch>/wt 763a47cf3`; `composer install --no-scripts --no-interaction -q`
- `php vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php` on:
  - `tests/Unit/Contexts/Membership/{Domain,Application,Infrastructure}/Organisational`;
  - each `tests/Architecture/Membership*Test.php`.
- `php vendor/bin/phpunit tests/Unit/Testing`, worktree only, no `.env` present.
- `git diff --stat abb81b1c5^ 763a47cf3`; `git log --oneline abb81b1c5^..763a47cf3`
- `G6_C2_L=5 python3 -I …/g6_c2_golden.py` and `cmp` with the fixture; `sha256sum`.
- `G6_C2_L=6 python3 -I …/g6_c2_golden.py` against a port stream-hash script (`G6C2CorrectionOracle::enumerate(5,6)`).
- Scratch probes: `twocons.php`, `replay.php`, `random_diff.php`, and `exhaustive_diff.php` with arguments (0 4 6), (0 5 6) and (0 5 5).
- Mutants: scratch copies of `Organisational/` (`m216`, `m217`, `m269`, `m302`, `m331`), changed with `sed` in scratch only and loaded first by a prepended `spl_autoload_register` (`preload.php`).
- Infection 0.29.10 + pcov: scratch `infection.json5` / `phpunit.xml`, `tmpDir` in scratch, timeout 400, `--threads=10`, `-d pcov.enabled=1`, the plan's `--filter`.

### Cleanup

- **Worktree:** removed with `git worktree remove --force <scratch>/wt`. `git worktree list` no longer shows it. The main tree's status matches the start of the session; I changed nothing in it, and `bootstrap/cache/config.php` was untouched.
- **Scratch artefacts were NOT deleted.** The permission system refused `rm -r` on them. They remain only in the session scratchpad, outside the repository:
  - `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/93000efc-ad03-4c1d-9b3f-40966981efc3/scratchpad/rev327/probe` (probe scripts, mutated kernel copies, outputs, the regenerated golden file);
  - `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/93000efc-ad03-4c1d-9b3f-40966981efc3/scratchpad/rev327/inf` (Infection config, logs, tmp).

  They contain no credentials.

## 3. Spot-check by the commissioning session (2026-11-03)

- **F-1, reproduced exactly.** I ran a scratch script with mutated kernel copies loaded ahead of the real ones; no tree file was edited. The reviewer's two histories gave:

  | History | Real kernel | Mutant `Member.php:269` | Mutant `Resolution.php:302` |
  |---|---|---|---|
  | 6 facts `A0@1 E0@3 A1@3 E1@4 A2@4 C(A2→2)` | refused `period_absorbed` | refused `overlapping_membership_periods` | refused `overlapping_membership_periods` |
  | 9 facts `A0@0 E0@2 A1@9 C(A1→4) E1@6 A2@8 N(C) C(A2→2) C(A1→1)` | **accepted**, consequences `A0 [1]` and `A2 [2–5]` (two live periods) | refused `overlapping_membership_periods` | refused `overlapping_membership_periods` |

  **My "≤ 1-consequence" argument in plan §10 step 7 was wrong.** It reasoned only about accepted decisions with a non-degenerate BaseClaim. Its evidence was "0 counterexamples at L = 5", and the smallest counterexample needs 6 facts. The two mutants were misclassified as equivalent; they are a test gap.
- **Worktree cleanup:** `git worktree list` shows no reviewer worktree.
- **Not re-checked by me:** F-2 to F-11 and the beyond-bounds runs. They are taken from the report with its own evidence labels.
- **Scratch:** the reviewer's leftover scratch folders (`rev327/`) are outside the repository and contain no credentials. Deleting them is the PO's call (permissions refuse `rm`).

## 4. PO verdict

**PO decision (2026-11-03): option (A), fix the conditions first, then accept** (PO words: "I agree with your recommendation", answering the options A / B / C).

**Conditions, closed by tests and records only (no `app/` change):**

| Condition | How it was closed |
|---|---|
| C-1 | Killing tests `CorrectConsequencesTest::test_one_correction_can_trim_two_live_periods` (9 facts) and `CorrectAbsorptionTest::test_a_correction_that_trims_two_periods_and_absorbs_one_is_refused_as_absorption` (6 facts). Expected values from the oracle port. Both fail on each mutant (`Member.php:269`, `Resolution.php:302`). The "equivalent" classification is withdrawn in plan §10 step 7 |
| C-2 | ER also refuses reversals: recorded in plan §10 step 6 and guide 13. The ER scope is confirmed by the PO with the acceptance below |
| C-3 | COR-4 entry criteria V-13 (R-1a-6), V-14 (INV-TGT at decode), V-15 (decoded-consequence validation) in the roadmap verification table §3.2; guide 13 points to them |

The conditions were committed in `9173c841f`.

### 4.1 Acceptance (PO, 2026-11-03)

**PO words:** "accept PBDIGIT-327; 1–3 as recommended; F-3/F-4 as contract questions".

**PBDIGIT-327 (COR-2) is ACCEPTED, kernel only.** The decisions:
1. **P-2 reading, as recommended.**
   - The representation is recorded as *the removed interval in absolute valid-time dates, as stated by the decision, clipped at resolution to the cause's current BaseClaim*.
   - The relative / absolute distinction is **nominal** in this implementation.
   - A cause-relative meaning, if ever wanted, would be a new decision for COR-4. The FP `multicause*.py` scripts are not in the repository.
2. **ER scope, as recommended.**
   - ER stays a G\* invariant on **every** decision: Correct, End and reversals.
   - This explicitly extends the PO's 2026-11-02 wording ("a correction …"). Recorded in the BD-REV-ER decision record.
3. **Guide 12, as recommended.** A separate backlog item: **PBDIGIT-328**.
4. **F-3 and F-4 become contract questions** (contract §9): **OQ-C2-1** (F-3) and **OQ-C2-2** (F-4), both OPEN, owner PO. They do not block this acceptance.

**Residual risks accepted with it** (review §F):
- single-session authorship of the kernel, the port and the P-1 / P-3 / P-4 restriction layer;
- exhaustive evidence only to L = 6 (H ≤ 5), random sampling beyond;
- reachable degenerate states (OQ-C2-1, OQ-C2-2);
- the COR-4 guards V-13…V-15 not yet built;
- repeated resolution (performance; COR-3 / COR-4).

**What this acceptance does not do:**
- It authorises no persistence (COR-4), application command (COR-3) or event emission.
- The ARB-MEM-01 gate stays: correction COR-1…COR-4 must be accepted before any cutover, migration or live write.
- The release gate for the ledger migrations is unchanged.

*(Superseded wording: "Acceptance: PENDING. It follows on the PO's word, after the conditions are committed." The list of decisions needed at acceptance is kept below as history.)*
1. the verdict (the reviewer recommends ACCEPT WITH CONDITIONS C-1…C-3);
2. P-2 reading;
3. ER scope (it now also covers reversals, F-5);
4. guide 12;
5. whether F-3 / F-4 become contract questions.
