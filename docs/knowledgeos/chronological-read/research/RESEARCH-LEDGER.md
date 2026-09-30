# KnowledgeOS research ledger (Track B)

⚠ authority: generated · a research instrument, not a governance record · reproduce with `research/structures_v0.py`.

**Researcher's rule.** The corpus is brainstorming material and a historical trace. It is never a specification, a truth or a proof. Our responsibility is to observe it and derive a *robust* theory: one that is formalized, attacked, tested, and kept only if it survives. **"No coherent theory found" is a valid outcome.**

**Material, pass 0.** The 20 committed rev3 S4 reconstruction objects (PB02–PB05), plus the 02-FILES dates.
- They are **unaudited agent reconstructions, not S5 evidence**, so every status below is at most HYPOTHESIS.
- Every "∀" below is a check over this finite set, not a proof.
- No ML is used: at n = 20, rule-based checks are the right baseline.

| # | Candidate structure (predicate) | Evidence (pass 0) | Counterexample | Threat to validity | Status |
|---|---|---|---|---|---|
| H1 | The timeline is a transition system with a unique initial FIRST | 20/20 hold; 73 transitions, EXTENDS→EXTENDS 34 (47%), FIRST→EXTENDS 12; revisions (CHANGES-*, NARROWS, CONTRADICTS) rare (≈ 12%) | none | **Method artefact:** the rev3 contract forces FIRST first, and EXTENDS may be an agent default class. The "development is mostly monotone accretion" reading is **confounded** by the reconstruction method | HYPOTHESIS (confounded) |
| H2 | Contradiction is acyclic and runs forward in A.10 time | 0 contradiction relations in 20 objects | — | no data | OPEN (untestable at pass 0) |
| H3 | The label dependency graph is a DAG | 19 nodes, 13 edges, acyclic | none | **vacuous:** 0 edges end at a label in the set, so no chains exist to form a cycle | OPEN (vacuous) |
| H4 | Birth kinds form a time chain: lexical ≤ conceptual ≤ formal ≤ operational ≤ governance | **CORRECTED by the pass-0b instrument:** all 13 "consistent" pairs are **ties** (same file date); Kendall S = 0 | — | the pass-0 "13/13 consistent" claim counted ties as support. **There is no evidence** | OPEN (no evidence) |
| H5 | FOUND absence dimensions form a closure system (implications A ⇒ B) | 4 exact implications with support ≥ 3, **all ⇒ `informal_meaning`** (support 7, 5, 4, 4) | — | **Multiple testing:** 132 ordered pairs. Under independence (P = 0.65), an implication with support s holds by chance with probability 0.65^s (s = 7: 0.05; s = 4: 0.18). None survives a correction. A better reading: `informal_meaning` is a near-universal "floor" dimension, not a lattice structure | HYPOTHESIS (not significant) |

**What pass 0 teaches (method, not theory).**
1. **Separate method artefacts from structure.** H1 shows that a regularity the protocol imposes is invisible as such unless the protocol is modelled explicitly. Every candidate needs a "could the reconstruction method alone produce this?" test.
2. **Vacuity checks are mandatory.** H3's "acyclic" was vacuous.
3. **Small n with multiple hypotheses gives false structure** (H5). The same analysis must be re-run on the S5 population (1,975 labels), with a pre-registered hypothesis list and a multiple-testing correction.

**Pre-registered for S5 (the next pass).** Frozen before any S5 data is seen:
- H1–H5 re-tested on the full population;
- H1 against a **null model** of the agent's class choice;
- H4 with ties reported separately;
- H5 with a Benjamini–Hochberg correction and a permutation baseline.

**Pass 0b: operational Model 0** (`research/prereg_v1.py`, B = 999, seed 20260927; null generators property-tested in `scripts/tests/test_p3b_research_prereg.py`).

| H | Model 0 (what it preserves) | T | p (Holm) | Reading |
|---|---|---|---|---|
| H1 | within-object permutation (FIRST, class multisets) | 36 runs | 0.62 (1.0) | **no sequential structure beyond frequencies** |
| H3 | degree-preserving swaps | — | UNTESTABLE (0 internal edges) | vacuous |
| H4 | within-object exchange of dates | S = 0 | 1.0 | only ties; **no evidence** |
| H5 | curveball (row and column sums) | 4 implications | 0.84 (1.0) | **fully explained by label and dimension frequencies** |
| H2 | random orientation | — | UNTESTABLE (0 dated relations) | no data |

**Conclusion of pass 0/0b:** nothing in the pilot material survives Model 0. This is a valid and useful result: the instrument works, and it will catch artefacts on the S5 population.

**Theory-candidate registry fields** (a row reaches HYPOTHESIS only with all of them filled):
- ID and statement;
- formal object;
- Model 0;
- reconstruction-artifact models (batch, packing, label size, hub, chronology);
- competing models;
- falsifier;
- statistic and effect size;
- R2 replicate stability;
- counterexamples;
- H-19 result;
- status.

**Status path:** OBSERVED → EXPLORATORY → HYPOTHESIS → FORMALIZED → EMPIRICALLY-SUPPORTED → INDEPENDENTLY-CORROBORATED → CANONICAL. **Exits:** COUNTEREXAMPLE-FOUND, REJECTED, OPEN.

**ML is candidate-generation only,** after adjudication. It covers embedding-based clustering of labels and near-duplicate detection, compared with a lexical (TF-IDF) baseline and a graph baseline. It never decides a status.
