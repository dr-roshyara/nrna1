# B0046 Extraction Summary

**40 files processed** (S1878-S1919, excluding gaps S1891/S1894 per the batch script).
82 contribution rows, 5 new object-index proposals. All six mandatory self-checks: **PASS** (0/0/82 valid/0/0/0).

## What this batch covers

Two entangled threads, both dated 2026-08-30/31:

1. **Step 281 primary artifacts** (`verification/step-281/`, S1878-S1883, S1913): the executed
   missingness repair. Candidate B (inquiry register `Q_t ⊆ P`) is selected over Candidate A
   (refuted by an executed counterexample — a BOTTOM assertion pollutes the assertion set and
   corrupts `WellFormed(P)` decidability) and Candidate C2 (isomorphic delta but violates
   minimality M3). Distinguishability (6/6 + 1 orthogonal), minimality, and 8/8 invariant
   preservation are all executed and PASS. Internal closure IC281 = ACHIEVED, with an explicit,
   repeated caveat that this is Level-4 (reference-implementation) evidence only, certified by the
   same session that proposed the repair, and does not touch the larger empirical gap (15/24
   constructs unobserved in the real EKP).

2. **Step 282 — three competing/evolving drafts plus a corrected execution** (`phase_measure_theory/`,
   S1884-S1888; `verification/step-282/`, S1892-S1913+S1916-S1919; independent re-verification in
   `gap-discovery/step-272/`, S1889-S1890, S1910-S1912, S1918). The first draft (S1884) declares
   "FORMALLY SUBSTANTIALLY CLOSED"; a self-review (S1885) adds nine required corrections (T-3, T-4,
   I-2, Q_t formalization as `Q_t=π_Q(Replay(K_0,H_t))`, a G0-G4 theory-implementation gap taxonomy,
   etc.) and downgrades the verdict to "PROVISIONALLY CLOSED — EMPIRICAL CERTIFICATION PENDING"; a
   supervisory review (S1886, duplicated as S1887) reframes the whole question from "is the theory
   complete?" to "is there any remaining theory-critical construct?" and issues a corrected execution
   mandate (S1888, in full: TC-1..TC-8 criteria, F14-F21 falsification suite, FC/CC/EC/GC closure
   dimensions, verdicts A/B/C).

   The **executed** Step 282 (`verification/step-282/`) then actually runs F14 (removal test: only
   `Q_t` is load-bearing among 8 candidates), F15 (non-identifiability — constructed, shown DERIVED
   not primitive, and an explicit self-correction of a prior "inexpressible" claim), F16 (policy/
   knowledge separation, PASS), F19 (dependency-cycle test, 0 cycles, I-2 CLOSED), F20 (closure-
   category test), F21 (probability necessity — 0/13 mandatory constructs break; T-3 not
   theory-critical), and F17/F18 (Q_t replay/serialization, 10/10 PASS, classified as event-derived
   projection of History, same shape as Sigma). **During F21 execution a real computational defect
   was found**: the reference-implementation harness hashed only an Evidence's `ref` field, causing
   an id collision between same-reference, opposite-polarity assertions (gap "C-NEW"); a fix module
   `kosfix.py` is provided and Step 281's results are re-verified under the corrected id and survive.

3. **Independent adversarial re-verification** (step-272/10, by a different session than the one
   that wrote Step 281/282): re-runs all six Step 281 test files independently (all pass), corrects
   its own earlier stated preference for Candidate A, closes two master-gap-register entries
   (A6/G-60, G-64) on executed evidence for the first time in the whole investigation, and pushes
   back on Step 282 in two respects: (a) proposes reclassifying E20 from BLOCKED to NOT APPLICABLE
   now that T-3 rules probability unnecessary (step-272/11), and (b) argues Repair B silently
   reopens the sufficiency question one level up to `(K, Q_t)`, motivating a newly adopted
   `Sufficient(F,O,I) := Congruent(F,O) ∧ Expressive(F,I)` criterion (step-272/12) that is shown, by
   executed independence proof, to **retrodict Repair B a priori** — and opens a new gap G-67 (the
   invariant set `I` has never been enumerated).

## Object-index proposals (5)

- `step282-theory-criticality-and-closure-decision` — the whole Step 282 gate (TC criteria, FC/CC/EC/GC, F14-F21, verdict B)
- `sufficient-f-o-i-criterion-adoption` — Congruent+Expressive criterion closing G-56, opening G-67
- `e20-calibration-reclassification-not-applicable` — E20 BLOCKED -> NOT APPLICABLE/DEFERRED
- `step280-281-harness-id-collision-defect` — the C-NEW evidence-hashing bug and its kosfix.py repair
- `gap-discovery-corpus-file-index-00A` — the machine-generated 576-file corpus index

Reused existing labels: `step281-missingness-repair-inquiry-register-selection`,
`orphan-state-structural-not-epistemic`, `step280-empirical-closure-test-suite-ec-not-achieved`,
`master-gap-register-53-gaps` (all first seen B0042/B0045).

## Notable methodological findings (recorded, not resolved, by this extraction)

- Two files (S1886, S1887) are near-verbatim duplicates of each other's trailing "HPA SUPERVISORY
  RULING" section — recorded as an in-file-overlap claim on both, not merged.
- Two executed-looking files (`f14_and_contradictions.py` S1908, `f21_probability_necessity.py`
  S1893) contain hard-coded `True`/`False` literals for several of their reported "test" rows
  (e.g. Lineage/Policy-Apply/Authorize in F21; 7 of 8 F14 rows; all 10 F20 contradiction pairs)
  rather than independently computed checks — flagged with `review_flag: MATH-QUESTION` on the
  relevant contribution rows, per instruction to record rather than resolve such findings.
- Three `.pyc` bytecode-cache files (S1895, S1899, S1909) were treated as `FIREWALL-LIMITED`
  (no extractable textual content); two of them (kos279, repairs) cache source files that are
  **outside** this batch's 40-file list, so `provenance: PROVENANCE-UNRESOLVED` was used for those.
- `00A-CORPUS-FILE-INDEX.md` (S1890) is a ~592-line machine-generated table; a representative
  read (full header/methodology + first ~250 and final ~30 of ~576 rows) was performed and
  disclosed explicitly rather than claiming a full transcription of every row, since row-level
  content duplicates file-level metadata already covered by other batches' per-file extraction.

## Self-check results

```
TOTAL INVALID ROWS: 0            (types closed-list)
TOTAL UNREGISTERED LABELS: 0     (labels vs object-index + proposals)
valid lines: 82                  (JSON validity)
TOTAL INCONSISTENT ROWS: 0       (unknown_candidate/labels consistency)
TOTAL FIELD-SHAPE ERRORS: 0      (files.jsonl field shapes)
TOTAL SCOPE ERRORS: 0            (scope enum)
```
