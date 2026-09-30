# R7 (v2.4) — focused independent re-check of F-01, I1, I7

| | |
|---|---|
| **Re-checker** | independent agent (fresh session, not the F-01 auditor and not the implementer), model **Claude Sonnet 5** (`claude-sonnet-5`) |
| **Date** | 2026-09-27 |
| **Contract sha256 verified** | `fa837177dc40fe48f15247b2f00e25c456e3dcd93b9ad182fafc5fa34b979674` — matches `prompts/20260926_2400_p3b-agent-contract-r7-addendum.md` exactly (checked first) |
| **Code commit at start of this recheck** | `git rev-parse HEAD` → `47b3d3b5b59bc2b797210d3c41d665574faa2785` |
| **Note on HEAD movement** | during this session HEAD advanced to `fe333abc8e9bb211439325eafaed196c09ddf49f` via **concurrent, unrelated** commits (F-Series/KnowledgeOS governance work, out of this recheck's scope). Verified: `git diff --stat 47b3d3b5b fe333abc8 -- docs/knowledgeos/chronological-read/scripts/ docs/knowledgeos/chronological-read/prompts/20260926_2400_p3b-agent-contract-r7-addendum.md` is **empty** — none of the S-Series scripts or the addendum changed. All findings below are against the unmodified `p3b_s5_r7_witness.py` / `p3b_transcript_syntax.py` / `p3b_s5_r7_universe.py` / `p3b_s5_r7_verify.py` as committed at `47b3d3b5b` |
| **Scope** | `docs/knowledgeos/chronological-read` only (S-Series), per hard rule. Not opened: F-Series, application code, corpus, ledger, P3B-STATE, seal/hold-out contents |
| **Prior record read (not relied on for conclusions)** | `audit-p3b/20260927_R7-INDEPENDENT-AUDIT.md` (found F-01 MATERIAL) and its authorization package |

## Method

Read the repair diff directly (`_persisted_identity`, `_classify`'s PERSISTED branch, `p3b_s5_r7_witness.py`). Ran the shipped `F01PersistedOutputIdentity` test class and the full `Universe`+`Witness` test classes plus `test_p3b_s5_r7_universe.py` (48 tests, production fixtures, all GREEN — this recheck *did* manage the full-pipeline run that the prior audit could not, by running from `scripts/tests` with `PYTHONPATH=.:..`). Wrote additional adversarial reproducers not present in the shipped suite (full-pipeline, via `r7_full_fixture`/`r7_harness_fixture`, plus isolated `_classify()` calls to avoid an unrelated Evidence-layer fixture artifact). Ran a seeded random counterexample search (`random`, stdlib, 5 seeds, 16,000 trials total) against the real, frozen registry for I1 (Universe closure — typing/ambiguity). For I7, reviewed the hash-binding chain in `p3b_s5_r7_verify.py`/`p3b_s5_r7_universe.py` and confirmed via the existing green test suite (T09–T13, T89, T95, T97) that the standard substitution attacks are caught; found and reasoned through one code-level gap (below), assessed against the declared trust boundary (ADR-R7-01).

## Area 1 — F-01 (persisted-output Read identity)

**Executed:** 6 shipped tests (`F01PersistedOutputIdentity`) + 6 new reproducers of my own:
1. Read *before* its own announcement (order reversal) — full pipeline, `r7-recheck/test_f01_extra.py`.
2. Duplicate identical announcement then a legitimate read — full-pipeline attempt hit an unrelated Evidence-layer (`E`) fixture artifact (a duplicated *reader* Bash call needs a second synthetic READ-LOG row the fixture doesn't generate — a test-construction limitation, not a Witness bug); re-verified at the `_classify()` level directly (`test_f01_classify_isolated.py`).
3. Cross-agent: agent A announces a path, agent B (different run/transcript, no announcement of its own) reads the identical literal path — isolated `_classify()` test (per-agent `announced` set scoping) and full-pipeline test.
4. Legitimate partial display (offset/limit-style partial `cat -n` view) of a genuinely persisted output — full pipeline.
5. Unicode look-alike path (Cyrillic `р` vs Latin `p` in the basename) — full pipeline.
6. Empty display of a genuinely empty persisted file — isolated `_classify()` (robustness only).
7. Archived file physically missing at verify time — full pipeline.

**Result:**

| case | observed | expected | match |
|---|---|---|---|
| Read before announcement | `BATCH-FAIL`, `R7-W … read outside I(run)` | reject | ✅ |
| Duplicate announcement (isolated) | single correct authorized read, no bypass | no bypass | ✅ |
| Cross-agent (isolated + pipeline) | `read outside I(run)`, per-agent scoping confirmed by code (`announced` is a local of `_classify`) | reject | ✅ |
| Partial/offset display of legitimate persisted output | `BATCH-PASS` | accept (no regression) | ✅ |
| Unicode look-alike path | `read outside I(run)` | reject | ✅ |
| Empty persisted file, empty display | `content_match false` (a **false-FAIL**, not false-PASS) | — | MINOR robustness observation only |
| Archived file missing | `content_match false` | reject | ✅ |

No MATERIAL counterexample found. The repair (`_persisted_identity`: exact, lexically-normalized absolute path; `announced` built only from `c.persisted_path`, which is harness-authored per the ADR-R7-01 trust boundary and therefore not agent-forgeable; content compared against the archived artifact) closes the original basename-collision gap. The one new observation (empty-file display) is a **MINOR false-FAIL robustness gap**, not a soundness gap — it can only make a legitimate empty read wrongly fail, never authorize an illegitimate one.

**F-01: CLOSED.**

## Area 2 — I1 (Universe closure)

**Executed:** a seeded fuzzer (`i1_fuzz.py`) generating random nested object shapes (mixing normal and "weird" keys: literal dots, literal middle-dots, empty strings, bracket-looking strings) with embedded S-ids, run against the **real, frozen** registry (`U.load_frozen_contract`), independently re-deriving — for every location `discover()` finds — whether `type_of` returns exactly one row, none (must be flagged untyped), or more than one (must be flagged ambiguous), and cross-checking against `typing_violations()`'s own output. 5 seeds × 3,000–4,000 trials = **16,000 trials, 0 findings** (no unflagged-untyped, no unflagged-ambiguous location).

Also traced by hand the specific concern the task calls out — a literal `.` inside a key (e.g. `births.formal`) vs. a literal middle-dot key colliding after `norm()`'s `.`→`·` substitution — and confirmed it is **not exploitable**: every registry path segment that could receive such a key (`<field>`, `<dim>`, `<text>`) is a **generic single-segment wildcard** in `_path_rx`, not a hard-coded literal dotted name, so the two key spellings normalize to the same (harmless) wildcard match either way; no type confusion results.

Existing suite (`test_p3b_s5_r7_universe.py`, `test_p3b_s5_r7_full.py::Universe`, 34 tests total) re-run: **all GREEN**.

**I1: no MATERIAL counterexample found within this search.** Not exhaustively fuzzed beyond the shapes generated (bounded budget); this remains, as the prior audit said, an area where a further-resourced pass could look longer, but this pass found nothing.

## Area 3 — I7 (binding)

**Executed:** re-ran the existing binding-relevant tests (`T09`–`T13` namespace/legacy/revision, `T89` frozen-manifest-hash-mismatch, `T95` prohibited path, `T97` misdeclared-synthesis-manifest, `T10` legacy-R2 read) — **all GREEN**, confirming the standard substitution classes (contract sha, plan sha, manifest sha, legacy digest, per-entry file sha) are caught.

**One finding from tracing the per-entry integrity check** (`p3b_s5_r7_verify.py` line ~100):
```python
if os.path.isfile(p) and U.fsha(p) != e.get("sha256"):
    FU.append(f"R7-U inputs {run}: {e.get('path')} bytes ≠ the frozen manifest sha256 (frozen-integrity failure)")
```
This check is **skipped entirely** (not merely undetermined — silently absent) when the file named by a manifest entry does not exist on disk at verify time — `os.path.isfile(p)` guards the whole condition, so a manifest entry with a fabricated `sha256` for a now-missing file raises no `R7-U` failure **from this line alone**.

**Assessed against the declared trust boundary (ADR-R7-01: harness/orchestrator/OS account trusted; agent-authored artifacts not):**
- `CONTRACT`/`ADDENDUM` entries point at fixed, governed repository files an S5 agent has no tool-level capability to delete or alter (Write is restricted to `permitted_writes`; Bash to the canonical reader). A missing addendum file would in any case make `U.load_frozen_contract` (called independently, unconditionally, earlier in `verify_r7`) raise `FileNotFoundError` rather than silently pass.
- `PLAN`/`SLICE` entries are independently cross-checked: `declared = _js(...)` returns `None` if the file is missing, and `plan_violations(declared, derived, ...)` is expected to flag a mismatch against the freshly re-derived plan (existing coverage: T97-style structural mismatch tests pass).
- `UNIT-RECORDS` entries are the one category actually *written* by an in-scope peer agent, and are independently guarded by Witness's own W5 check (`p3b_s5_r7_witness.py`, `_classify`'s `last_write` reconciliation): `if not os.path.isfile(p) or open(p,"rb").read() != content...: F.append("... differs from its last witnessed Write")` — a missing or altered unit-records file fails there regardless of the Universe-layer gap.

Given this, the gap is real as a **standalone** code path but is not, on the evidence read, independently reachable by an S5 agent acting within its declared tool capability — every category it could affect is closed by an orthogonal check. Recorded as **MINOR/OBSERVATION**, not MATERIAL: a defense-in-depth thinning, worth tightening (e.g. treat a missing file as its own failure line) so the Universe layer does not rely on Witness/plan-derivation to catch this class, but not a demonstrated false-`BATCH-PASS` path within the current design and trust boundary.

**I7: no MATERIAL counterexample found within this search**, matching the prior audit's own (code-reading-only) conclusion, now with an actual green re-run of the T09–T13/T89/T95/T97 executable tests plus the one traced-and-assessed gap above.

## Findings table

| id | area | severity | reproducer | observed vs expected |
|---|---|---|---|---|
| RC-01 | F-01 (confirmation) | — (repair holds) | `scratchpad/r7-recheck/test_f01_extra.py`, `test_f01_classify_isolated.py` | 6/6 new adversarial cases behave correctly (5 reject/pass as expected, 1 unrelated fixture limitation resolved via isolated test) |
| RC-02 | F-01 | MINOR | `test_f01_classify_isolated.py::test_empty_persisted_file_empty_display` | A genuinely empty persisted file, displayed as empty, yields `content_match=False` (false-FAIL). Never a false-PASS |
| RC-03 | I7 | MINOR / OBSERVATION | code reading, `p3b_s5_r7_verify.py` L100 | The per-entry frozen-integrity check is skipped (not evaluated) when the entry's file is absent from `root` at verify time. Not independently exploitable within the declared trust boundary (see above); recommend an explicit "entry file absent at verify" failure line as a hygiene tightening, not urgent |

No MATERIAL finding in this pass.

## Limits of this recheck (stated honestly)

- One re-checker, one pass, time-boxed. I did not attempt suite-wide mutation testing (I reused/extended the existing test suite and added targeted reproducers instead).
- I1's fuzzer is a generic random-shape generator; it is not a structured grammar-aware fuzzer of the registry's own `_path_rx` patterns, so it may miss a very specific, narrowly-targeted registry-path shape that a hand-crafted adversarial input would find.
- I7's RC-03 assessment (not independently exploitable) rests on my own reading of `permitted_writes`/W5 and the ADR-R7-01 trust boundary, not on a constructed end-to-end reproducer that actually deletes a file mid-run and confirms no other check fires; I did not build that reproducer (would require faking a mid-pipeline deletion in the fixture, which was judged lower value than the F-01/I1 work given the time-box).
- Same general independence caveat as the prior audit: a different model generation, not a different vendor or a human reviewer.

## Overall

- **F-01: CLOSED.** The repair (exact-path identity + real content match) withstood order-reversal, cross-agent, duplicate-announcement, Unicode look-alike, partial-display-regression and missing-archive-file attacks; only a MINOR false-FAIL robustness edge (RC-02) was found.
- **I1: no MATERIAL counterexample found** within a 16,000-trial seeded search plus the existing 34-test suite (green) and a specific trace of the key-normalization concern named in the recheck brief.
- **I7: no MATERIAL counterexample found** within this search; the existing 12+ binding-relevant tests are green, and the one code-level gap found (RC-03) is assessed as not reachable by an in-scope agent given the current trust boundary — recorded as a hygiene observation for governance, not a soundness finding.

**Recommendation: ACCEPTABLE** (with RC-02 and RC-03 recorded as non-blocking, MINOR/OBSERVATION follow-ups for a human/ARB decision — no repair is authorized by this recheck).
