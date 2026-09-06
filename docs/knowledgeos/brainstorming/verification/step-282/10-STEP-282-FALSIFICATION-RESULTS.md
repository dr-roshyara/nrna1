# 10 — Step 282 Falsification Results

| Test | Question | Result | Evidence |
|---|---|---|---|
| **F14** | Can removing a proposed resolution break a mandatory operation? | **1 of 8 can** — only `Q_t` | `OUT-F14-CONTRADICTIONS.txt` |
| **F15** | Can distinct states be observationally equivalent? | **YES — constructed.** And it is **derivable**, not primitive | `OUT-F15.txt` |
| **F16** | Can policy stay external while knowledge about policy stays representable? | **YES — both simultaneously** | `OUT-F16-F19-F20.txt` |
| **F17** | Can `Q_t` be reconstructed from history? | **YES — 7/7** | `OUT-F17-F18.txt` |
| **F18** | Does serialization preserve `Q_t` exactly? | **YES — 3/3** | `OUT-F17-F18.txt` |
| **F19** | Can a true definitional cycle be constructed? | **NO — 26 nodes, 0 cycles** | `OUT-F16-F19-F20.txt` |
| **F20** | Can an empirical failure be miscategorised as formal? | **The framework prevents it** — one operational rule re-derives all four test categorisations | `OUT-F16-F19-F20.txt` |
| **F21** | Does removing probability break any mandatory construct? | **NO — 0 of 13** | `OUT-F21.txt` |

## Attacks that SUCCEEDED
| # | What it found | Classification |
|---|---|---|
| **F21 (incidentally)** | **id collision in the harness** — polarity omitted from the hashed key; two semantically opposite assertions shared an id | **C — implementation, not theory** |
| **F15** | non-identifiability **is** constructible — and my prior "inexpressible" status was **wrong** | **F — corrected, closed** |

## §20 — active contradiction search, ten mandated pairs

`K` vs `Q_t` · `Σ` vs missingness · Policy vs Knowledge · Identity vs observational equivalence ·
History vs state · provenance vs lineage · measurement vs uncertainty · governance vs epistemic status ·
transformation vs policy · authorization vs transformation.

> **NONE FOUND.** This is asserted **only after** all ten were executed, as §20 requires.

## Step 281 re-verified under the corrected id
```
M1-M6 all distinct: True          M7 orthogonal: True -> False
StructuralValid: (True,'ok')      collision case re-run: ids differ
```
**Step 281's results survive the correction.**
