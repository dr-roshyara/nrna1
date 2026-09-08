# Provenance and Temporal Analysis

## Cross-citation check (both directions)

- Does `06-composition-rules.md` cite the executable code or the `nrna1/research/` path? **No** —
  grep-confirmed across the full file text; no `.py`, no `nrna1/research`, no `run_all`, no `kr/`.
- Does `kr/carriers.py` (or `kr/reach.py`) cite `06` or "composition-rules" by name? **No** —
  grep-confirmed; no match in either module.
- Does `04-operator-contracts.md` (admitted) cite `06`? **Yes, explicitly**: *"The reach engine
  (§06) consults only the atom set"* (line 6) — a same-series, internal cross-reference (both are
  parts of one numbered document set, `01`–`19`), not a reference to the executable code.

**Finding: no explicit citation link exists between the narrative `06` and the executable
`carriers.py`, in either direction.** Their agreement is not explained by either one visibly quoting
the other.

## Git provenance — does one predate the other in version control?

Both are absent from any commit predating `70fee73c8bcce04b18606adb007fa45ab5297787` (2026-09-06),
which is the *first* commit for both — the entire `docs/knowledgeos/research/` tree and the
`nrna1/research/` tree were checked in (or, for `research/`, remain entirely uncommitted — MD-030
`02`) at materially different points: `06` was committed 2026-09-06; the executable lane has **never**
been committed at all. **Git history cannot establish which was authored first** — it only
establishes that `06` has a commit and the executable lane does not. This is a provenance-*strength*
finding, not a temporal-*order* finding.

## Filesystem mtime — the tightest signal available, still not proof

`04-operator-contracts.md`: mtime `1788295210.9229610470` (2026-09-01 22:40:10.9229...).
`06-composition-rules.md`: mtime `1788295210.9279609300` (2026-09-01 22:40:10.9279...).
**Gap: ~5 milliseconds.** For comparison, the typical gap between adjacent files in this same
directory's own write sequence is tens of seconds to several minutes (e.g. `03`→`04` is ~45 seconds
per the full listing in `02`). A 5-millisecond gap between two files is consistent with both being
written by a single script/generation pass in immediate succession — not with two files independently
authored at different times that happen to land close together by chance.

The executable lane's own `kr/carriers.py` mtime (MD-030 `02`): 2026-09-01 22:36:13 — **earlier** than
either `04` or `06`'s mtimes by roughly 4 minutes. If mtimes are read as an authoring-order signal
(a caveat this study repeats, per MD-030's own explicit caution that mtimes are not authorship proof),
the order suggested is: `carriers.py` (22:36:13) → ... → `04-operator-contracts.md` (22:40:10.9229)
→ `06-composition-rules.md` (22:40:10.9279). This would be *consistent with* the narrative document
having been written as a description of an already-existing code artifact, in the same session — but
this study does **not** treat this as established fact, per the instruction not to equate mtime with
authoring order.

**Formal answer, per this study's own required vocabulary: `TEMPORAL ORDER UNRESOLVED`** — a
directional mtime signal exists and is reported, but is explicitly not strong enough to be called a
determination.

## Common-source test

Both `04` (admitted) and `06` are members of the same numbered document series, produced in the same
narrow mtime window (2026-09-01, 22:35–23:10 for the bulk of the series), explicitly cross-referenced
internally (§06 cited from `04`), and — per this study's own `05` — state the identical rule as the
executable code, without citing it. The most parsimonious explanation consistent with every piece of
evidence gathered (no cross-citation to the code from either narrative document; near-simultaneous
narrative-internal mtimes; identical rule content down to the explanatory prose) is: **`06` and
`carriers.py` derive from a common design/authoring process, not from one independently confirming
the other.**

## Classification, per this study's own required vocabulary

**`CONVERGENCE WITH COMMON-CAUSE PROVENANCE`** — not `INDEPENDENT CONVERGENCE`. The agreement between
`06` and `kr/carriers.py` is real and precise, but is not usable as two-source independent
confirmation of the rule; it is one design decision, expressed in two media (prose table + code
table), most likely by the same authoring process on the same day.
