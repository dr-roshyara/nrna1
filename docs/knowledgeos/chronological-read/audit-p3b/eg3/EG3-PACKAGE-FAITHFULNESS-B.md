# EG3-PACKAGE-FAITHFULNESS-B: §16 (part (f)) and §13's (f) lines vs. the EG-3 working papers

**Scope:** `audit-p3b/eg5/EG5-V2.8-DECISION-PACKAGE.md` §16 in full, and the (f)-bullets in §13, checked against `audit-p3b/eg3/{EG3-SPEC-A.md, EG3-SPEC-A-v2.md, EG3-REVIEW-B.md, EG3-REVIEW-B-v2.md, hub_view_profile.json, hub_required_counts.json}`.

## Verdict: **NEEDS-EDIT** (one contradicted claim, two unbacked claims; everything else in §16 and the (f) lines of §13 is backed and numerically exact)

## Exact edits required

**1. FALSE — contradicts both cited SPEC-A versions.** §16: *"The hub labels split into 64 SINGLE, 1 DECOMPOSED and 1 EMPTY."*
- `EG3-SPEC-A.md:61`: *"Freeze-2 reports 1 hub-EMPTY and 1 hub-DECOMPOSED-MULTIROW... **PENDING:** the exact SINGLE/DECOMPOSED split of the other 64."*
- `EG3-SPEC-A-v2.md:52`: *"The hub path split (SINGLE/DECOMPOSED counts and files per run, spec §1.4) **remains PENDING**."*
- Only 2 of the 66 are settled (1 EMPTY, 1 DECOMPOSED, both via Freeze-2). The package states the other 64 are SINGLE as settled fact; the working papers say this is explicitly unresolved, twice, including in the most recent revision.
- **Edit:** replace with *"1 EMPTY and 1 DECOMPOSED are confirmed (Freeze-2); the SINGLE/DECOMPOSED split of the remaining 64 is PENDING (SPEC-A §1.4, HD-5)."*

**2. UNBACKED — not in any cited working paper.** §16: *"HUB is 33 of the 231 sampled labels."*
- Neither "231" nor "33" appears anywhere in `EG3-SPEC-A.md`, `EG3-SPEC-A-v2.md`, either review, or either counts JSON (both JSONs give `hub_labels: 66`, not 33; "sampled" isn't a concept either working paper defines).
- **Edit:** cite the actual source of "33 of 231" (outside EG-3 scope) explicitly, or drop the clause. As written it reads as EG-3-reviewed when it wasn't.

**3. UNBACKED / not reproducible from the cited JSON.** §16: *"Hub slices are dominated by `search_records` (66.9%) and `source_meta` (28.5%)."*
- Not stated verbatim anywhere in the working papers. I tried to reproduce it from `hub_view_profile.json`'s `lines_by_key` three ways (sum of medians, sum of p90s, sum of maxes across all 22 keys) — none reproduces 66.9%/28.5% exactly; the closest (p90-based) gives ≈68.9%/26.6%, off by ~2 points each, and the other two bases are off by much more. This is plausibly a genuine per-label computation the orchestrator did, but it is not one I can check from the marginal statistics in the JSON I was given.
- **Edit:** either cite the exact source/method (a per-label fraction, not a fraction-of-marginals), or replace with a figure that *is* directly readable from the JSON, e.g. *"dominated by `search_records` and `source_meta`: together ≈93% of p90 view lines (29,720 + 11,456 of 43,116 total p90 lines across all keys, `hub_view_profile.json`)."*

## Everything else checked and confirmed exact/backed

- Full-view burden (median 318 / p90 1,735 / max 5,950 Reads; ≈8.8M characters at max) — exact match, `hub_view_profile.json` + `EG3-SPEC-A.md` §3.
- All six "contract facts" bullets — each traces to a specific SPEC-A finding I independently re-verified in `EG3-REVIEW-B.md` (stage-2 disabled, mechanical ESCALATED/LOAD, per-label `negative_label`, no whole-`search_records`/`source_meta` requirement, hit entries consumed only by verifier + hub-list builder, no contract anchor for "whole file" display on **any** run — this last point I confirmed for non-hub runs too, and the package states it that generally, correctly).
- Rule §5.9a as stated is the **v2 (corrected)** rule — "without any leaf under `ledger_hits`/`raw_hits`," not the superseded first-leaf variant. Correct version selected.
- **Exact production counts**: median 41 / p90 88 / max 127; 49,934 → 3,349 (a true −93.3%, correctly rounded to −93%) — exact match to `hub_required_counts.json`'s `citable_rule3_exclude_all_hits` (the corrected series, not the superseded `citable_first_leaf_variant`). Correct series selected.
- "Why report-only is safe" — the six `p3b_s5_verify.py` line citations and the "structural, not byte-exact" characterization of `hub_rec_eq` match `EG3-REVIEW-B-v2.md` §3 exactly.
- "Probe. Rule-3-conforming; E2 passes; negative control rejects the superseded first-leaf variant" — matches `EG3-REVIEW-B-v2.md` §1 exactly.
- Materiality paragraph (contract-material, not verdict-/science-material; θ_D as rule-determinism, parallel to EMPTY) — matches `EG3-SPEC-A.md` §5.3 verbatim in substance.
- "+97 reads... over the narrow variant" — exact: 3,349 − 3,252 = 97, both from `hub_required_counts.json`'s rule-3 series. "R(run) is a minimum, so this affects only false NOT-COVERED noise" matches `EG3-SPEC-A-v2.md` D3's corrected HD-3 rationale (itself responsive to `EG3-REVIEW-B.md` §5).
- "Residual (recorded)" paragraph — accurately and appropriately hedges the one point both reviews flagged as unresolved-by-design (coverage is completeness evidence, never correctness enforcement); not overstated, not softened away.
- §13's two (f) lines: *"needed only before the 14 hub batches; the canary has no hub"* — "14 hub batches" is exact, `EG3-SPEC-A.md:215`; canary-has-no-hub matches both `EG3-SPEC-A.md` and `EG3-REVIEW-B.md` §4. *"part (f): §5.9a text + prompt rendering of R(run) + RI-1b coverage reporting, tests first"* — matches SPEC-A's §5.1/§5.2 recommendation and RED list. Both backed.
- Nothing found dropped or softened: the residual/open-question material from both reviews (T(L)-scope rationale, report-only-not-a-gate, the "agent could copy without reading" caveat) all survive into §16, undiluted.

## Traceability

`audit-p3b/eg5/EG5-V2.8-DECISION-PACKAGE.md:218-231,342-373` · `audit-p3b/eg3/EG3-SPEC-A.md:55,61,111,205-220` · `audit-p3b/eg3/EG3-SPEC-A-v2.md:9-56` · `audit-p3b/eg3/EG3-REVIEW-B.md` · `audit-p3b/eg3/EG3-REVIEW-B-v2.md` · `audit-p3b/eg3/hub_view_profile.json`, `hub_required_counts.json` (re-read directly for this check).
