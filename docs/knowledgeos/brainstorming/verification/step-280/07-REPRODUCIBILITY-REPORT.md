# 07 — Reproducibility Report

**Environment:** Python 3.13.2 · PHP 8.x · Linux · repo `57d93b0e` · 2026-08-30T23:24:02+02:00

| Check | Result |
|---|---|
| `knowledge-graph.php` twice → byte-identical | **PASS** |
| `Replay(H) == Replay(H)` | **PASS** |
| E-suite re-executed → identical output | **PASS** |
| F-suite re-executed → identical output | **PASS** |
| No wall-clock, no RNG in the harness | **PASS** — every timestamp is a fixture literal |
| Content-addressed ids stable across runs | **PASS** — e.g. `c81184e084` |

**Non-reproducible elements: NONE.**

**Caveat, stated rather than buried:** reproducibility here means *the same harness gives the same answer*.
It does **not** establish that an independent implementer would obtain the same result — that is Level 6,
and **no Level-6 evidence exists.**
