# EG-3 REVIEW-B-v2: re-review of SPEC-A-v2 (delta)

**Verdict: SPEC-ACCEPTABLE.** REVIEW-B's MATERIAL finding is closed. No new MATERIAL finding.

## 1. Probe fix confirmed by direct re-execution (not by reading the diff)

Ran `probes/eg3_probe_required_coverage.py` unmodified, as shipped:

```
small : REQUIRED lines 209 -> reads 11   E2 PASS
median: REQUIRED lines 353 -> reads 16   E2 PASS
large : REQUIRED lines 2157 -> reads 88  E2 PASS
E2 negative control: first-leaf variant REJECTED (hit leaf ['search_records',0,'raw_hits','S8000',0,0] required)
```

Exact match to SPEC-A-v2 D1's table (209/353/2157 lines, 11/16/88 reads, PASS ×3). `required()`'s `seen_first` branch is gone; the `elif ... in ("ledger_hits","raw_hits"): pass` now excludes every hit-map leaf, including the E-kind line of an empty map (verified against `slice_view`'s own leaf-emission rule for empty containers — consistent). E2 is a genuinely independent re-derivation (different dispatch logic: `hit`/`meta_out` flags vs. `required()`'s key-walk), not circular, and its negative control fires exactly where claimed.

T(L) fix confirmed: `run()` now computes `T` by `re.findall(r'\bS\d{4}\b', canon(d))` over the slice with `search_records`, `stage2_files`, `source_meta` popped, and asserts `T == sorted(own)` — this assertion passed (no error) on all three synthetic sizes, so T(L) is no longer empty and matches spec §4's definition exactly. Earlier probe conclusions (COVERED/NOT-COVERED pattern across full/targeted/skip-scenarios) are unchanged in kind; only the required-line counts shrank by 0–2 per scenario as documented.

## 2. Production figures re-checked directly against `hub_required_counts.json`

The file now carries five labeled series (previously it silently held only the first-leaf variant, unlabeled). Read directly:

- `citable_rule3_exclude_all_hits`: median 41 / p90 88 / max 127 / sum 3349 — **exact match** to SPEC-A-v2 D2's "recommended option" row.
- `narrow_rule3_exclude_all_hits`: max 123 / sum 3252 — matches D3's "3,349 vs 3,252" comparison.
- `citable_first_leaf_variant` / `narrow_first_leaf_variant`: sum 3354/3257, max 127/123 — matches D2's "superseded" row, and **confirms** the open question I raised in REVIEW-B v1 (whether the original counts used the non-conforming variant): the file's own `_note` says so explicitly — *"first computation (earlier today) used the first-leaf variant copied from the A3 probe."* That ambiguity is now closed by direct evidence, not inference.

## 3. HD-2 load-bearing checks — all six locations exist and do what D3 claims

Re-read each cited line directly:

| Claim | Line | Confirmed |
|---|---|---|
| slice `hub_record` = hub-list line | `:375` | exact: `(sl.get("hub_record") or None) != (hub_list.get(lab) if is_hub else None)` |
| `set(dims) - set(ab)` | `:756-757` | exact |
| `set(ab) - set(dims)` | `:758-759` | exact |
| `negative_label` equals DIMENSION record's | `:777` | exact (condition line) |
| hit-bearing dim must be ESCALATED+LOAD, `hub_record` equality | `:781-790` | exact, including the `hub_rec_eq(...)` call at `:789-790` |
| `hub_rec_eq` definition | `:1123-1130` | exact |

**Correction confirmed as accurate.** `hub_rec_eq(v, rec)`: if `v` is a dict, `v == rec` (Python structural dict equality); if `v` is a str, `json.loads(v) == rec` (parse then structural equality). Neither branch compares serialized bytes/strings directly — my v1 review's "byte-exact" language was imprecise, and SPEC-A-v2's "exact structural equality of parsed JSON, not byte-exact" is the correct description. This does not weaken the safety argument: dict `==` is still exact over every key and value, recursively, order-independent — an agent still cannot pass with a wrong or partial `hub_record`/dimension set.

## 4. Anything MATERIAL left? No.

- Checked whether `e2()`'s independent re-derivation is truly independent of `required()`'s bug class: yes, different code path (flag-based vs. dispatch-based), and it caught the negative control on live execution, not just by construction.
- One cosmetic-only nit, not material: `required()`'s docstring still says "M-c = source_meta[S] for S in run files ∪ source_tracks," but the call site now passes citable `T(L)` positionally as `run_files` with `tracks=[]`. Functionally correct (the union is computed correctly either way), just a stale comment — worth a one-line fix, not a review blocker.
- D3's HD-3/HD-2 sharpenings directly and correctly incorporate REVIEW-B v1's §5/§6 notes (minimum-display-set framing; byte→structural correction); no regression introduced.
- Materiality/separability/placement conclusions from REVIEW-B v1 (§4) are untouched by this delta and were not reopened by A3.

## Traceability

`scratchpad/eg3/EG3-SPEC-A-v2.md` · `scratchpad/eg3/probes/eg3_probe_required_coverage.py` (re-run, unmodified) · `hub_required_counts.json` (re-read, 5-series version) · `scripts/p3b_s5_verify.py:373-377,754-791,1123-1130` · REVIEW-B v1 (`EG3-REVIEW-B.md`) for the finding this closes.
