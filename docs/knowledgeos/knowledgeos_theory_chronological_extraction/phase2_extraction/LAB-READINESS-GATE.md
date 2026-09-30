# LAB-READINESS GATE

A candidate is `LAB_READY` **only if all nine hold**. ⛔ If any one is missing: **NOT LAB_READY**, and what is missing must be named.

| # | Condition |
|---|---|
| 1 | all required terms **defined** |
| 2 | all relevant **types / domains** known |
| 3 | required **assumptions explicit** |
| 4 | **derivation** complete enough to execute |
| 5 | **input** can be constructed |
| 6 | **procedure** unambiguous |
| 7 | **expected output** defined |
| 8 | **falsification criterion** defined |
| 9 | ⭐ **an independent implementer could reproduce the experiment without inventing theory** |

## ⛔ The governing rule — NO SILENT COMPLETION

> If the corpus does not define something necessary for implementation, **do not invent it.**

Do not choose `threshold = 0.8`, or `1`, or any value. Record `OPEN_TERM(x)` and continue searching.

⭐ **Step 3 demonstrated why.** The open term `bar` was not missing — a numeric threshold is **forbidden** by ES-003.2. Had it been invented, the laboratory would have implemented a quantitative promotion rule that the governance explicitly rules out, and every downstream result would have been an artefact of that invention.

## Gate results — Iteration 1

| Structure | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | Verdict |
|---|---|---|---|---|---|---|---|---|---|---|
| `STR-0001` promotion | ⛔ | ⛔ | ⚠️ | ⛔ | ⛔ | ⛔ | ⛔ | ✅ | ⛔ | **NOT LAB_READY** |
| `STR-0002` authority P×S | ⚠️ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ⭐ **LAB_READY** *(narrow claim only)* |
| `STR-0003` check-before-admit | ⛔ | ⛔ | ⛔ | ⛔ | ⚠️ | ⛔ | ⛔ | ✅ | ⛔ | **NOT LAB_READY** |
| `STR-0004` append-only | ✅ | ✅ | ⚠️ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ⭐ **LAB_READY** *(as a historical property)* |
| `STR-0005` counter clock | ✅ | ✅ | ⚠️ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ⭐ **LAB_READY** |
| `STR-0006` nesting | ⛔ | ⚠️ | ⛔ | ⛔ | ⛔ | ⛔ | ⛔ | ⚠️ | ⛔ | **NOT LAB_READY** |

> ⭐ **3 of 6 pass — but none of the three is the theory.** Two test *properties of the corpus* and one tests *a schema defect*. **Not one tests the promotion mechanism**, which is the candidate theory's core.
