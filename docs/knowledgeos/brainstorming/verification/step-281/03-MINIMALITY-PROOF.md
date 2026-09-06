# 03 — Minimality Proof
**`exec/test_minimality.py` → `OUT-MINIMALITY.txt`**

`ΔR = R* − R₀ = {Q_t}`

## M1 — Necessity **PASS**
Remove `Q_t` and re-run the discriminating pair:
```
NotAsked      -> ('?','Absent-or-NotAsked')
Asked+Absent  -> ('?','Absent-or-NotAsked')     identical = True
```
> Removing `ΔR` **reproduces exactly the Step 280 E4 failure**. Every component of `ΔR` is required to
> distinguish at least one mandatory state pair.

## M2 — Irreducibility **PASS**
`ΔR` is a single structure. Its only proper subset is `∅`, which is the M1 case and fails.
**No proper subset is sufficient.**

## M3 — No redundant distinction **PASS**
Five reachable states, **each produced by a declared operation** — `Ask(p)` or
`Assess(p,e,Policy)`. `Q_t` carries **one bit per proposition**: no ordering, no strength, no structure
beyond membership. No distinction is introduced that the mandatory operation set cannot make.

## Comparison of the three ΔR
| Candidate | ΔR | Verdict |
|---|---|---|
| A | one BOTTOM assertion per query, **inside `𝒜`**, plus `BOTTOM ∈ V_D` | **larger, and destructive** |
| C2 | `I ≅ Q_t` **plus** a coupling of `I` into `Σ` | **equal content, fails M3** |
| **B** | one set `Q_t ⊆ P`, **outside `K`** | **minimal** |

> **`ΔR(B)` is the smallest extension satisfying M1 ∧ M2 ∧ M3.**
