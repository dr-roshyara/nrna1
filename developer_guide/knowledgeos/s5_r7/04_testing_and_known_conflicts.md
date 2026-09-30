# 04: Testing, and the known design conflicts

## Fixtures (test helpers, `scripts/tests/`)

| Helper | Builds |
|---|---|
| `r7_harness_fixture.py` | synthetic Claude Code harness transcripts (subagent records, meta, main transcript with Agent dispatches, orchestrator markers, `queue-operation` notifications), shaped as observed in the Model-D readiness experiment |
| `r7_execution_fixture.py` | a complete synthetic **execution** from a plan: the input reads (slice; unit records for synthesis), reader calls in the R7 header format, writes, handbacks, markers, notifications, and the run input manifests |
| `r7_full_fixture.py` | a **valid R7 batch** through `p3b_s5_verify.verify` |

`r7_full_fixture.py` in detail:
- It starts from the committed historical S4 batch PB04 (read-only material): 1 DECOMPOSED, 3 SINGLE, and 1 label made genuinely EMPTY.
- It applies a documented baseline sanitation.
- It uses synthetic source bytes (a marker sentence per S-id) with a fake resolver, and the synthetic hold-out sets.
- It writes the freeze files as the orchestrator would.
- Hooks let a test break exactly one thing:
  - `hooks` (per run, or `main`);
  - `pre_freeze`, `post` (file tampering);
  - `readlog_mut`, `manifest_mut`;
  - `header_extra`, `entry_extra`;
  - `no_archive`;
  - `base(pre_obj_mut=…, overrides=…)` for positive controls.

## Suites

| Suite | What it proves |
|---|---|
| `test_p3b_transcript_syntax` | decoder syntax, chain, notifications, persisted outputs, S5-agnosticism |
| `test_p3b_s5_r7_universe` | registry loading and sha, F2, discovery vs validation, typing, claims, namespace, binding, I(run), W8 grammar, META subset |
| `test_p3b_s5_r7_witness` | W1 codes, W2, W3, W4, W5, W8, W7a on synthetic executions |
| `test_p3b_s5_r7_evidence` | anchoring (exact, N-WS, multiple occurrences, cross-page, same run), coverage, W6, claims, input ≠ evidence |
| `test_p3b_s5_r7_reconstruction` | precedence (exhaustive strict partial order), summary relations, FD-1′b, lifecycle, S3 canonical matcher |
| `test_p3b_s5_r7_stats` | the domain (REJECT / NOT-ESTIMABLE / VALID) and exact-enumeration unbiasedness |
| `test_p3b_read_source_r7` | reader grammar, default-deny, plan hash, header and invocation id |
| `test_p3b_s5_r7_properties` | exhaustive Kleene composition, exhaustive `program_accepted`, T94 capability closure, randomized anchoring |
| `test_p3b_s5_r7_full` | the acceptance matrix **through the production verifier** |

The statistics cases T30–T37 and T68–T70 run through `estimate_v7`.

Run from `scripts/tests` with `PYTHONPATH=.:.. python3 -B -m unittest <suite>`.

## Former design conflicts, resolved by v2.4 (G-LOG-0089)

| Id | Resolution | Tests |
|---|---|---|
| **NDB-1** | `absences.<dim>.reason`, `stage2_dispositions[*].reason` and `timeline[*].historical_position` are typed **META** (rule `—`). They create no claim, and `historical_position` is never a precedence input | `test_p3b_s5_r7_universe.test_ndb1_…`, `test_p3b_s5_r7_full.test_T98_…` |
| **DC-1** | `escalations[*].field` is META with the self-reference rule (`U.SELF_REF_RULE`, enforced in `meta_violations`): an S-id there must form exactly `timeline[<own S-id>].<order\|date_applies_to_file\|change_vs_previous>` | T103/T104 in the Universe and full suites |
| **DC-2** | The R6 positive control asserts "R6 = PASS ∧ revision 6 superseded" | `test_p3b_s5_r6_verify.PositiveControl` |

- **INV-LEX (addendum §3.0).** A lexical S-id occurrence is support for nothing outside EVIDENTIARY/QUOTE locations. `test_p3b_s5_r7_inv_lex` checks that META-only mentions leave the claims, evidence obligations, reconstruction, precedence, lifecycle, statistics frame and claim ids unchanged. It also checks that, relative to v2.3 (recovered from git `05569322d`), the only change is the removed typing failure.
- **Mentions remain subject to the lexical gates.** G-01, AUDIT, QUARANTINE and S3 still apply, so an unknown S-id, a hold-out id or a range in META text still FAILS.
- **The fixture sanitation of these locations is removed.** The positive control now carries 63 real META S-id mentions and passes.

**Open risk RI-1 (DEFERRED, G-LOG-0089).** A token-capped harness Read of an input file shows a prefix of its lines without an error, and `content_match` accepts it. See `audit-p3b/20260927_R7-READ-INTEGRITY-CHARACTERIZATION.md`.

**Traceability:** addendum §11 (T01–T97) · the implementation record `audit-p3b/20260927_REVISION-7-IMPLEMENTATION-RECORD.md`.
