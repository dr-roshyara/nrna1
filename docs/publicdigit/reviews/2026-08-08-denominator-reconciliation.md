# Denominator reconciliation — why the record says 826 and PHPUnit says 1,376

**Commission:** Principal Architect · Election Verification, Slice 1 Step 2 — **reconciliation only, no row classified**
**Date:** 2026-08-08 · **Checkpoint:** `9533bcfe` · **Status:** no production code, test, fixture or PHPUnit configuration changed

---

## A · Current authoritative test universe

**Definition — reproducible, not descriptive:**

> `vendor/bin/phpunit --list-tests` over `tests/Unit/Domain/Election` · `tests/Unit/Application/Election` · `tests/Architecture/Election` · `tests/Feature/Election` · `tests/Architecture/ElectionStateMachineConsistencyTest.php`, at checkpoint **`9533bcfe`**.

| Path | Tests |
|---|---:|
| `tests/Unit/Application/Election` | 485 |
| `tests/Feature/Election` | 446 |
| `tests/Unit/Domain/Election` | 428 |
| `tests/Architecture/ElectionStateMachineConsistencyTest.php` | 12 |
| `tests/Architecture/Election` | 5 |
| **Total (unique identifiers)** | **1,376** |

**OBSERVED FACT.** 1,376 canonical PHPUnit test identifiers were captured. **These are the Master Matrix's rows.**

## B · Historical test universe — **unrecoverable from the record**

The failure-estate assessment (2026-08-07) states:

> **Universe:** the five paths above · **Measured:** **814 completed — 668 passed, 146 failed** · plus 12 incomplete and 501 risky

**OBSERVED FACT.** **No command, configuration, filter, suite selection or test list was recorded** anywhere — not in the assessment, the plan, or the session logs. Searched for `vendor/bin/phpunit …` and `artisan test …` invocations; **none recorded.**

**CONCLUSION.** An exact set comparison is **impossible**. `HISTORICAL_EXECUTION_UNIVERSE` was never captured, so `CURRENT − HISTORICAL` cannot be computed. **This is a limitation of the record, not a gap to be filled by inference.**

## C · What the 550 difference is — and what it is not

**Three hypotheses were eliminated by measurement, not by argument.**

### 🔴 Eliminated: (b) discovered-but-not-executed · (e) data-provider expansion · (f) classes erroring before execution

**Experiment 1 — a Unit path.** `tests/Unit/Domain/Election`:

```
--list-tests   →  428
actual run     →  Tests: 428, Assertions: 957, Errors: 13, Failures: 1, Incomplete: 2
```

**Experiment 2 — a Feature path** *(added because the first experiment covered only one family, and Feature tests carry the heavier `migrate:fresh` setup where a silent drop was most plausible)*. `tests/Feature/Election/StateMachine`:

```
--list-tests   →  24
actual run     →  Tests: 24, Assertions: 24
```

**Listing and execution agree exactly in both families.** Every listed test ran and reported — including the 13 that errored, which PHPUnit still counts. **So PHPUnit does not silently drop listed tests, provider expansion does not inflate the list, and class-level errors do not remove tests from the count.**

**Still generalised from two paths of five, and stated as such** — the two cover the Unit and Feature families, which between them hold 874 of the 1,376.

### 🔴 Eliminated: (c) tests added since the historical run

```
git log --since=2026-08-07 --diff-filter=A -- <the five paths>   →  0 test files added
```

**The universe has not grown since the assessment.**

### ✅ Established: (a) the historical run covered less than the stated universe

**Arithmetic proof, needing no further measurement:**

```
tests/Unit/Domain/Election      428
tests/Feature/Election          446
                                ———
two of the five paths            874     >    historical total 814
```

**Neither path has grown since the assessment.** So the historical run **cannot have fully covered even two of the five paths it named**, let alone all five.

> **CONCLUSION: the stated universe and the measured universe were never the same. The assessment presented a measurement over an unidentified subset under a heading naming the full five paths.**

**Which subset — MECHANISM NOT ESTABLISHED.** Without the command it cannot be recovered, and it will not be guessed.

## D · The 550, stated exactly

| | |
|---|---|
| **1,376** | tests that exist at `9533bcfe` — **measured** |
| **814** | tests that reported in one historical run — **over an unrecorded scope** |
| **562** | the arithmetic difference. **It is NOT a population of tests that failed to run.** It is the gap between a full-universe count and a subset execution |

**The number 550 used earlier in this programme was itself imprecise** (1,376 − 826 = 550 uses the *reported total including incomplete*; 1,376 − 814 = 562 uses *completed*). **Both are differences between incomparable quantities**, which is the finding rather than a correction.

## E · Was 826 a scope number or an execution number?

**An execution number, over a scope that was never recorded.** It is not, and never was, a count of the Election test estate.

## F · Can the historical 668 / 146 be retained?

**Not as a statement about the Election estate.** They may be retained **only** as: *"one run's result over an unrecorded subset, 2026-08-07."*

**Every downstream claim derived from them must be re-qualified**, including the `146 → 105` trajectory this programme has carried.

## G · The correct denominator, and the scope contract it requires

**Denominator: 1,376.**

**And a bare number is not enough** — it must be reproducible, or six months from now *"we verified 1,376 Election tests"* will repeat this exact defect:

> **MASTER MATRIX UNIVERSE = the exact PHPUnit-discovered test identifiers, under an explicitly recorded command and configuration, at a recorded repository checkpoint.**

| Field | Value |
|---|---|
| Command | `vendor/bin/phpunit --list-tests <path>` |
| Paths | the five listed in §A |
| Configuration | repository `phpunit.xml`, unmodified |
| Checkpoint | **`9533bcfe`** |
| Identifier form | PHPUnit's own `Class::method` identifiers |
| Count | **1,376** |

**Every future coverage or completeness statement cites this contract or states its own.**

## H · Remaining uncertainty

* **Which subset the historical run covered — MECHANISM NOT ESTABLISHED**, and unrecoverable without the command.
* **Whether the remaining three paths behave like the two tested** (listed = ran). **Two of five were tested** — `Unit/Domain/Election` (428) and `Feature/Election/StateMachine` (24), covering the Unit and Feature families. `Unit/Application/Election` (485), the rest of `Feature/Election` (422) and `Architecture` (17) were **not** run.
* **Whether any of the 1,376 fail to report under a full-universe run.** **Not measured** — no full run was performed.
* `SD-1` remains formally unanswered; this reconciliation assumed the five-path universe **because the assessment named it**, not because a decision was recorded.

---

**TEST-UNIVERSE RECONCILIATION COMPLETE — EXECUTION BASELINE NOT ESTABLISHED — AWAITING PRODUCT OWNER REVIEW**

> ⚠️ **Deliberately narrower than the earlier wording.** An earlier version of this line read *"DENOMINATOR RECONCILIATION COMPLETE"* without qualification. **Two different things were being conflated:** the **test universe** is reconciled (1,376, under a recorded contract); the **execution baseline** is not — **919 of 1,376 rows have no measured result.** *"Denominator complete"* would have implied both.

**No test row classified. No production code, test, fixture or PHPUnit configuration changed.**

**Traceability:** `docs/publicdigit/reviews/2026-08-07-election-failure-estate-assessment.md` (the 814/668/146 figures and the five-path universe) · plan §"the matrix row count is 1,376, not 826" · `phpunit.xml` · checkpoint `9533bcfe`
