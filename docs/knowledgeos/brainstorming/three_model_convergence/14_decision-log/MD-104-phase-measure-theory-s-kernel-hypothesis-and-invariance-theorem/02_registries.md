# MD-104 §02 — Duplicate/Provenance Register, Self-Correction Ledger, Negative-History Register,
External-Theory Register

## Duplicate/Provenance Register

- **Position 33 reuse (already known, not re-read as new evidence)**: per MD-101's own complete
  exact-duplicate census (33 groups, `md5sum`-based), position 33
  (`20260825-235913_kernel-problem-minimum-substrate-for-conditional-determination-duplicate.md`) is
  a confirmed byte-identical duplicate of position 31
  (`20260825-235804_kernel-problem-minimum-substrate-for-conditional-determination.md`). The reuse
  event is logged here; the content was NOT re-derived as new evidence, consistent with the standing
  "byte-identical counts once" statistical discipline.
- **Position 35 = position 34 + position 31, concatenated (NEW finding, directly verified)**:
  `md5sum` showed positions 34 and 35 are NOT byte-identical (`a306b9be...` vs. `d66732968...`), so
  the two were NOT assumed to be simple duplicates from their close-looking content alone (per the
  standing "never trust apparent visual sameness — verify directly" discipline). A direct `diff`
  confirms position 35's file is EXACTLY position 34's full 1,669-line content, followed by a bare
  `#` line, followed by position 31's full content verbatim (no alteration) — 2,074 lines total (34's
  1,669 + a separator + 31's 405). **This is a new duplication SHAPE**: a full cross-file
  recombination — position 35's own filename ("v2-expanded") suggests an independently expanded
  document, but its actual content is entirely explained by concatenating two already-separately-
  read files. Net-new content from position 35: ZERO. This is recorded as a reuse/concatenation
  event, not double-counted as independent evidence — consistent with, and extending, the duplication-
  shape taxonomy already built across MD-101 (cross-file exact duplicates), MD-102 (within-file
  partial-tail duplicate), and MD-103 (within-file full self-duplicate): MD-104 adds a fourth shape —
  within-file full CROSS-file concatenation (two distinct prior files' full content, not one file's
  own content, pasted together).
- Position 31, 32, 34 verified NOT exact duplicates of any file in MD-101's own 33-group register
  (direct content inspection against known group members).
- Position 32's filename verified ACCURATE by direct reading (the one instance this segment where the
  filename correctly predicted the file's own central claim) — reported as a data point for the
  ongoing "never trust filenames, always verify" discipline: verification is required in both
  directions (filenames can mislead, as MD-101/102 found repeatedly, and can also be accurate, as
  found here — the discipline is to verify, not to assume either way).

## Self-Correction Ledger

1. **Position 32's own extensive self-critique of positions 29/30's own framework**: pos32 directly
   challenges "the listed common properties are not yet proven invariants" (calling them "a common
   research concern," not a mathematical invariant), calls the "common algebraic structure" hypothesis
   "highly speculative" and "premature," and proposes a five-step ordering (epistemic object →
   determination operation → regime representation → equivalence conditions → what survives →
   THEN invariance) that explicitly REORDERS position 29/30's own research program. Recorded as a
   genuine within-segment methodological correction, both retained.
2. **Position 34's own internal, uncorrected multiplicity**: the same file proposes two structurally
   different 7-tuples for "the abstract epistemic state" (`S=(K,Γ,Π,Ω,Θ,C,t)` then later
   `S=(𝒫,𝒞,𝒜,𝒯,𝒦,𝒢,ℋ)`) without ever explicitly reconciling or acknowledging the shift — recorded as
   a within-document, NOT author-flagged, self-inconsistency (distinct from the segment's other,
   explicitly self-flagged corrections).

## Negative-History Register

- F4 formal family (`Sat`, `Det_r`, `EvalReq`, `EC`, `EC_t`, `Γ`(the F4-tracked symbol specifically —
  note position 34's own bare `Γ` inside its first `S`-tuple is a DIFFERENT, locally-scoped usage,
  not the F4-tracked `Γ` object; recorded as a possible notational homonym-risk, not merged), `Δ_t`,
  `≡_sem`, `⪯_cap`, `MinKer`, `v1.3`): SEARCHED-NOT-FOUND across all four files this phase (verified
  via direct `grep`, zero hits for every tracked token in every file).

## External-Theory Register

- No new independently-citable external source this phase. KST, Bayesian, Dempster-Shafer,
  Non-Monotonic Logic, Argumentation Theory, Epistemic Logic, Temporal Logic, and Measurement Theory
  are all reused/re-surveyed (positions 31, 34) — all classified EXTERNAL THEORY, RELATED/EXTERNAL —
  NOT ADOPTED, consistent with the standing five-way classification. Positions 31/32/34 are
  themselves classified CORPUS-NATIVE (the reconstruction's own first-person "I would..." narrative
  voice, continuous with positions 25-29 — distinct from position 30's classification as
  reconstruction-adjacent AI-authored commentary).
