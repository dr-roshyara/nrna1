# R-S3 review: EG-3 hub required lines / coverage (S3 parts only)
Verdict: IMPL-ACCEPTABLE. No MATERIAL findings. Read-only review (a scratch copy of HEAD's orchestrator was made in the scratchpad only; a transient dotfile briefly created in scripts/ was moved out at once, git status clean of it).

Suite: test_p3b_s5_r7_hub_required + orchestrate + assemble + slice_view -> 111 tests OK (incl. orchestrate replay of I(run)/manifests; prepare's I(run)/manifest code is untouched, only `slice_text=` is added to the render_prompt call).

## Conformity to addendum v2.8 section 5.9a (checked against orchestrate.py)
- (i)-(iv): hub_required_lines L145-162 matches the text and the v2 probe. Header line 1 always. Rule (iii) excludes every leaf under search_records[0].ledger_hits/raw_hits including the E line of an empty map (test_rule_iii_*).
- T(L): hub_citable_sids L137-142 = SID regex over U.canon(slice minus search_records/stage2_files/source_meta). U.canon (sort_keys, ensure_ascii=False, compact) is the same canonical form the verifier enforces: slice_view_violations requires canon(parse(view)) == slice, so parsing the view yields a dict whose canon equals the frozen bytes; T(L) is well defined and a function of the view alone. Keys are included in the regex scan (as the probe/spec do).
- Edge paths: empty search_records (path ["search_records"], E) -> excluded (no clause matches; consistent with the text). Empty record 0 ({} at ["search_records",0]) -> included (p[2] vacuously not a hit key). Empty source_meta E line -> excluded. All defensible readings; none tested (minor, see 2).
- Hub detection: required_lines (view-parsed "hub" is True) and _hub_view_lines (slice text "hub" is True) use the same flag; SINGLE/UNIT/SYNTHESIS share the label's view, so R is role-independent (test_E9).
- Line numbering: view line n = i+2 for leaf i (header = 1), identical to W._content_match (split("\n"), 1-based n). displayed_range = [min,max] of the harness line numbers; run_view_coverage uses range(a, b+1). Aligned.
- Coverage filter: kind == "read-input", run == run, path == the run's own view path, content_match is True, range a 2-list. Raw SLICE reads, other runs, mismatches, and "read" kind are excluded (test_E7). Non-hub R = 1..len(split)-1 = whole view.
- stdout: coverage_lines prints run ids and counts only; no label, path, S-id (test_E11 asserts). uncovered_paths stay in the returned dict. NOT-COVERED never raises; view_coverage writes nothing.

## Non-hub prompt identity (your question)
test_E8_non_hub_prompt_is_byte_identical compares render_prompt(no slice) vs render_prompt(slice_text=plain): both are the CURRENT function, so it only proves that passing a non-hub slice is a no-op. It would not catch a change to the default paging text (partly mitigated by asserting OLD_PAGING lines in `base`). I verified the stronger property manually: the HEAD render_prompt output for the UNIT run is byte-identical to the current output, both without a slice and with a non-hub slice. SINGLE/SYNTHESIS outputs differ from HEAD only by S1's 3-line record-ids block (not S3).
Stronger test (optional): pin the rendered UNIT prompt sha256 (or full literal text with fake ids/COMMIT/READER_ABS) captured from HEAD, and assert equality for no-slice and non-hub-slice calls.

## Minor (non-blocking, no fix required for acceptance)
1. test_E8 as above: replace/add a golden (HEAD-captured) comparison.
2. Untested edges: empty search_records, empty record 0, empty source_meta (add three one-line tests to pin the reading chosen). No test feeds a real W.witness read-input event through coverage; the view-path string f"{slice_root}/{batch}/{lab}.view.txt" (orchestrate.py L707) must equal the witness `_rel` path (which comes from the manifest entry). Equal for a normal slice_root; safer to take the path from the run's SLICE-VIEW manifest entry.
3. Efficiency: for the largest hub views (~136k lines) slice_view_parse runs 3x per run (required_lines, hub_citable_sids, run_view_coverage's hub flag); parse once and pass the object. Not correctness.
4. run_view_coverage assumes int ranges (TypeError on malformed input); the witness always emits ints, so unreachable today.
