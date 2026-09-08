# File Identity and Provenance

## Identity

`docs/knowledgeos/research/kernel-reduction/12-randomized-results.md` — "12 — Randomized,
Statistical and Robustness Results (Parts XI, XII, XXIII)," 206 lines.

## Git provenance

```
$ git log --diff-filter=A -- docs/knowledgeos/research/kernel-reduction/12-randomized-results.md
70fee73c8bcce04b18606adb007fa45ab5297787 2026-09-06 08:02:08 +0200
docs(knowledgeos): check in the brainstorming corpus and research lanes as untracked-until-now
research material
```

**Identical commit** to `03`, `04`, and `06` (all four files were first tracked together, in the
same bulk check-in). Same provenance tier as the currently-admitted files — not weaker, not
stronger.

## Filesystem mtime

2026-09-01 23:54:41 — later than `04`/`06` (~22:40:10) by roughly 74 minutes, and later than `03`
(~22:39:25) by a similar margin. Consistent with its own stated role (a *results* file, naturally
produced after the *contract*-defining files `03`/`04`/`06` in the numbered series' own internal
order) — not treated as proof of authorship order beyond that plausibility, per this programme's
own standing mtime-caution discipline.

## Citations — one direction confirmed, the other not found

`03-capability-model.md` (line 89): *"Property P2 (§12) tests exactly this."* (line 95): *"tested
instead as robustness variant **V1** (§12)."* `04-operator-contracts.md` (line 76): *"attacked
directly by robustness variant **V4** (§12)."* — three explicit citations **from** the already-
admitted files **into** `12`.

Grep for "§03"/"§04"/"§06"/the three filenames, run against `12-randomized-results.md` itself: **zero
matches.** `12` does not cite `03`/`04`/`06` back by their own section numbers or filenames anywhere
in its own text — citation is one-directional (into `12`, not out of it), at least in the explicit
"§NN" convention this document series uses elsewhere.

## Cross-citation to the executable lane

Grep for `.py`/`nrna1/research`/`kr/`/`run_all` in `12-randomized-results.md`: **zero matches.**
Grep for `12-randomized`/`randomized-results` in `kr/variants.py` and `kr/properties.py`: **zero
matches.** No cross-citation either direction between this narrative file and the executable code —
the same pattern MD-031 already found for `06` specifically.

## Net provenance classification

Same tier as `03`/`04`/`06` — `RECONSTRUCTED PROVENANCE`, same bulk commit, no upgrade or downgrade
implied by anything found in this study.
