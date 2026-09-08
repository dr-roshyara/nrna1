# Provenance and Temporal Chain

## Repository identity (resolved before anything else)

`git -C .../d0f38614.../nrna1 rev-parse --show-toplevel` and
`git -C .../nab-raj.roshyara@.../nrna1 rev-parse --show-toplevel` both return the **same** path
(`/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1`), and `stat -c '%i %d'` on
`research/kernel-reduction` from both paths returns the **same inode** (`2321190`) on the **same
device** (`41`). **These are two filesystem-path aliases for one location, not two repositories.**
This resolves a question left open at the start of this session: the "additional working
directory" path is not a separate checkout — it is the same tree, reached via a different
username/path prefix. All findings below apply identically regardless of which alias is used.

## Git tracking status — DIRECT EVIDENCE, machine-observable

```
$ git status --porcelain research/ verification/
 M verification/zero-algebra/KR-REP-REDUCTION-2026-09/results/metrics.json
?? research/
```

- `research/` (the entire tree, including `kernel-reduction/`) is reported as `??` — **untracked**.
- `git ls-files research/kernel-reduction` returns **zero** rows.
- `git log --diff-filter=A --follow -- research/kernel-reduction` returns **empty**.
- `git log --all --diff-filter=A -- research/` (searching every ref, not just the current branch)
  also returns **empty**.
- `git check-ignore -v research/kernel-reduction/README.md` and `research/` both return nothing —
  **not gitignored either**; it is simply material that was never `git add`ed.

**Finding: `research/kernel-reduction/` has never been committed to this repository, on any
branch, at any point in its history.** This is a *weaker* provenance status than
`docs/knowledgeos/research/kernel-reduction/` (the narrative write-up), which MD-026 established
was committed on 2026-09-06 (commit `70fee73c8bcce04b18606adb007fa45ab5297787`). The executable
lane exists only as live, uncommitted working-tree state.

## Contrast: `verification/` IS tracked

```
$ git ls-files verification/ | wc -l
174
$ git log --diff-filter=A -- verification/ | tail -1
6779b3e0b60b66a57854efe9f579beb85d1f06c0 2026-09-06 08:00:49 +0200 docs(knowledgeos): zero-algebra experiment lane — bridge, zoom and state experiments with full corpora
```

Recorded for completeness and to sharpen the contrast (both directories were surfaced together in
the prior turn; they do not share a provenance status). `verification/` itself remains out of
scope for this study — this fact is noted, not investigated further.

## Filesystem timestamps — DIRECT EVIDENCE, weaker than Git, self-consistent

All 27 files carry an mtime of **2026-09-01**, in a plausible dependency-respecting write order:
`kr/__init__.py` (22:35:48) → `atoms.py` (22:35:48) → `carriers.py` (22:36:13) → `operators.py`
(22:37:05) → `reach.py`/`capabilities.py` (22:37:35, same second) → `ablate.py` (22:38:44) →
`scenarios.py` (22:39:08) → `properties.py` (22:40:14) → `variants.py` (22:41:41) → 6 `results/*`
files (22:41:41–22:43:26) → `infotheory.py` (22:52:42) → `smuggling_probes.json` (22:56:19) →
`run_all.py` (22:56:48) → `README.md` (23:09:26) → 6 more `results/*` files (23:09:26–23:10:06,
matching `run_all.py`'s 7 experiment groups) → `audit_variants.py` (23:35:14, later) →
`audit_extended_search.json` (23:40:26) → `paired_analysis.json` (23:57:03, latest file).

**Caveat, stated per this study's own discipline (not to be silently smoothed over): filesystem
mtimes are not cryptographically or version-control-anchored evidence.** They can be altered by a
copy, checkout, or restore operation and do not, by themselves, prove authorship date. They are
recorded here as the best available *ordering* signal (internally self-consistent with a plausible
single authoring/execution session) and as a **content-internal corroboration**: `2026-09-01` is
identical to the `experiment_id: "KR-2026-09-01"` self-dating found inside every `results/*.json`
`_meta` block (direct evidence, read from the files themselves) and to MD-026's own prior finding
that the *narrative* lane's `kernel-reduction/` content is content-dated the same day. This is
convergent, not independent, evidence — both signals plausibly derive from the same single
authoring session, not two separate confirmations (per this study's own required statistical
discipline — see `09_adversarial-falsification.md`).

## Provenance classification (this study's own vocabulary, consistent with MD-025–029)

| Question | Answer |
|---|---|
| Filesystem existence | DIRECT EVIDENCE — confirmed present, read in full |
| Git tracking | **NONE** — zero commits, any branch, all history |
| Authorship | NOT EVIDENCED — no commit metadata exists to attribute it |
| Execution | NOT DIRECTLY EVIDENCED — `results/*.json` exist and are internally consistent with having been produced by `run_all.py`, but this study did not execute the code and found no independent log/CI record of an actual run; classified `MACHINE-PLAUSIBLE, EXECUTION NOT DIRECTLY EVIDENCED` |
| Relationship to narrative lane | EXPLICITLY STATED by the README and by `variants.py`'s own `V0` docstring (see `06`) — a documentation-level link, not a provenance chain |
| Governance/organizational authority | NOT ESTABLISHED — same finding as every prior MD-025–028 provenance question in this programme |

No provenance status here is upgraded from what the evidence directly shows.
