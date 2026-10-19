# D1 safety review: packet for the reviewer, and the human test sheet

| Field | Value |
|---|---|
| Authorization | D1 (Principal, 2026-10-17): review of the filter **source code and specification** with **fake fixtures**, followed by a **human-run, signed** fake-fixture test. No restricted material. |
| Prepared by | Session B, 2026-10-17 19:31 (clock). As the author of every script involved, **Session B cannot be the D1 reviewer.** |
| Not covered | **D2 remains paused.** Passing D1 does **not** permit any production run. Production stays disabled in code (`D2_CLEARED = False`) until the rule owner answers `D2-CLARIFICATION-REQUEST-20261017_1929.md`. |

## Part A: what the reviewer receives

None of these files contains sealed data. They contain only code, paths and fake fixtures.

| File | Full sha256 (r4; 19:51:22) | Role |
|---|---|---|
| `filter_v2/SPEC.md` | `e24a8bf204465158067173655634e4953d6c6dae7b7fdabebbfee6349e7dc232` | the required behaviour (review this first, independently of the code) |
| `filter_v2/kos_filter_v2.py` | `4b60a02b0ad8ee03f37c05075632a7f661b4331f411af0b9afa7def6bd372dff` | proposed code |
| `filter_v2/test_kos_filter_v2.py` | `f3a44639b8766a072a61571e51871f98b5d91b90293436f2b7a0ffb78a820aa1` | fake-fixture tests (**49 tests** at r4) |
| `../corpus-access/discovery_filter.py`, `../corpus-access/corpus_filter.py` | as on disk | the current scripts, for the defect list (**read only; never run**: they load the real seal) |
| `../corpus-access/PROPOSED-FILTER-PATCH-20261017.md` | — | defects F-1…F-10 and N-1 |

**Roles (Principal decision record, 2026-10-17).** Each is a different person, and none is the filter's author (Session B):

| Role | Task | Appointed |
|---|---|---|
| **Reviewer** | spec first, code second; add tests; numbered verdict | *to be appointed* |
| **Human tester** | runs Part D as a **non-root** user and signs it | *to be appointed* |

**If the reviewer adds or changes tests,** the hashes and the expected test count in Part D must be re-issued before the human run. The tester runs exactly the reviewer-approved files.

**Internal pre-review history** (fresh-context Claude sub-agents; **same model family; not the D1 review**):

| Round | Reviewer | Verdict | Outcome |
|---|---|---|---|
| r1 | A | FAIL | 9 fail-open: labels emptied by normalization, Unicode and zero-width variants, non-UTF-8 files, IDs in file names, labels in directory names and corpus paths, family files without ROOT or sha checks, case-sensitive sha, integrity checked only against the seal's own hashes |
| r2 | A | PASS WITH CONDITIONS | all 9 fixed. New: combining marks, encoded or spread-out text, silent skip of unreadable directories |
| r3 | **B (fresh)** | **FAIL** | 7 more fail-open: JSON `\uXXXX`, quoted-printable and repeated encodings; hyphenated ID prefixes (e.g. `F-LOG-0308`); invisible Hangul fillers; regex edges; non-canonical seal paths; withheld names in tracebacks; output overwrite and newline injection |
| r4 | — | **not yet reviewed** | realistic r3 findings fixed, each with a test (49 tests). The rest are declared residual risks R-6…R-8 in SPEC §6 |

**What this history means.** Each fresh reviewer found new ways past a content-matching denylist. The D1 reviewer should treat the internal pre-review as **leads, not assurance**, and should judge whether the residual risks are acceptable **for the mechanism D2 permits**. If the seal custodian produces the allowed list (the preferred D2 option), the filter's completeness matters less than the custodian's procedure.

## Part B: reviewer instructions (proposed reviewer: an external model family, e.g. ChatGPT, or a human engineer)

1. **Read SPEC.md first.** Derive your own expected behaviour, and your own test cases, for every rule before you read the code or the tests.
2. **Check the specification itself:**
   - Is it complete? In particular, does it say what happens with symlinks, binary files, non-UTF-8 text, empty families, very large files, IDs in other formats, and labels that are substrings of common words (over-withholding)?
   - Does every rule fail closed?
3. **Check the code against the specification line by line.**
4. **Check the tests:** does each defect F-1…F-9 and N-1 have a test that would **fail on the old code**? Add the missing cases.
5. **Name the residual risks** that remain open. **F-10** (edited-and-moved copies) is known to be open.
6. **The production adapter is deliberately not written.** Specify what it must verify, in particular how I-1 and I-2 must match the real seal's hash conventions, using the resolver's documented interface only.
7. **Verdict:** PASS / PASS WITH CONDITIONS / FAIL, with a numbered list of findings.

## Part C: developer pre-check (Session B; NOT the signed test)

- **Command:** `python3 -I -B -W error::ResourceWarning test_kos_filter_v2.py`
- **Result at 19:51 (r4):** **49 tests, all passed.** Fake fixtures only. The unreadable-directory test is skipped when run as root.
- **Weight:** this shows the code passes its *own* tests. It does not show that the tests are adequate. That is the reviewer's job.

## Part D: human-run fake-fixture test (to be completed and signed by a human)

| Step | Action | Expected | Observed | Initials |
|---|---|---|---|---|
| 1 | Confirm the three file hashes in Part A with `sha256sum` (or the reviewer-approved revised hashes) | match | | |
| 2 | Confirm the test file contains only fake seals, labels and IDs (S9001, S9002, "Fake Hold Label", "zeta_secret_topic") | yes | | |
| 3 | Run `python3 -I -B -W error::ResourceWarning test_kos_filter_v2.py` from `filter_v2/`, as a **non-root** user (`id -u` ≠ 0; otherwise the unreadable-directory test is skipped) | 49 tests OK, 0 skipped (or the reviewer-approved count) | | |
| 4 | Run `python3 -I kos_filter_v2.py` | exits with "REFUSED: production use disabled …" | | |
| 5 | Run the reviewer's added test cases | all OK | | |
| 6 | Confirm no file outside the temporary test directories was created or changed | none | | |

**Signature block**

| Field | Value |
|---|---|
| Name and role | |
| Date and time | |
| Result | PASS / FAIL |
| Conditions or notes | |
| Signature | |

## Part E: the gate after D1

The gate passes only if all four hold:
1. the D1 review is PASS or PASS WITH CONDITIONS, with the conditions met;
2. the signed Part D is PASS;
3. D2 is answered in writing, permitting the chosen mechanism;
4. the Principal records the gate as passed.

Only then may the count-only check run, under the operator D2 names, followed by the five D5 reads.
