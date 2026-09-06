# `KR-ZOOM-03` — the cross-dimension counterfactual

**Status:** RUN · audited · **non-adjudicated** · Theory v1.2 FROZEN · kernel NOT SELECTED

Tests whether removing a dimension from the knowledge state changes what an inquiry can
**determine** — with a real `E^-` intervention, not a reachability lookup.

**Contract, floor and estimand were frozen before execution:**
`../KR-ZOOM-03-PREREGISTRATION-2026-09.md` (ratified 2026-09-05, revised to Option 3 before any run).

```bash
python3 code/run03.py     # -> data/results03.json
```

Seeds `20260904` / `88020260904` · n = 2000/split · budget 5 · base `Determine` ≈ 0.34 (gate 0.30–0.80).

## Outcome in one line

> **BORDERLINE (Δ = 0.058 / 0.060, floor 0.10) — not claimed.** Decoy removal changed determination
> almost as often as root-cause removal, so the undirected estimand measured perturbation rather
> than relevance. **Read `RESULTS.md` §3 and §5 before citing anything.**
