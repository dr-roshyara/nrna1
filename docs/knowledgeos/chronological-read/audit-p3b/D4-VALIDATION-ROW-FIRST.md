# D4 validation: row-first / boundary-aware partition (addendum to the S5 Decision Package)

**Kind:** deterministic metadata analysis only.
- **Nothing changed:** no experiment; no S5 decision; G-LOG-0080, G-LOG-0081, the OB0018 frozen result (UNDETERMINED), the S5 Decision Package, the 396-batch / 1,975-label floor and Decision A are all unchanged.
- **Data used:** git object sizes, slice-derived required sets, row sources, `02-FILES` best historical dates and P3a pair records. **No corpus content was read.**

**Reproducibility:**
- `audit-p3b/d4-validation/rowfirst_validate.py` (sha256 `5a6ec3be…c5e2f`) produces `rowfirst_validate.output.json` (`d5338713…9b81`).
- `audit-p3b/d4-validation/pairaware.py` (`6aa591f5…5bfa`).

**Permitted claim (and no stronger):** row-first reduces the measured exposure to cross-unit row-source adjacencies under the tested metadata model. **Fidelity remains an empirical question.**

## 1. Reproduction and one erratum

| Quantity | Package value | Re-run | Status |
|---|---|---|---|
| row adjacencies FFD / row-first, 600 KB | 253 / 7 | 253 / 7 | ✓ reproduced |
| row adjacencies FFD / row-first, 300 KB | 529 / 42 | 529 / 42 | ✓ reproduced |
| units FFD = row-first (600 / 300 KB) | 2,333 / 2,938 | 2,333 / 2,938 | ✓ reproduced |
| **unit boundaries (600 / 300 KB)** | **358 / 963** | **366 / 971** | **ERRATUM** |

**Cause of the erratum:**
- The readiness computation took Σ(units − 1) over all 1,975 labels. The 8 labels with no sized required file contributed −1 each.
- The correct count, over labels with ≥ 1 unit, is **366 (600 KB) and 971 (300 KB)**.
- Only this derived count is affected. The published values are left in place, and this erratum supersedes them.

## 2. Strategies compared (deterministic; unranked)

| | **A. FFD** (the only unit-partition rule in the codebase: `p3b_s5_pilot.partition`) | **B. pure row-first** (row sources next-fit in S-id order; the remainder in its own units) | **C. row-first + FFD fill** (the package's measurement) | **D. sequential next-fit over all files in S-id order** (a reference; not in the codebase) |
|---|---|---|---|---|
| **600 KB** units | 2,333 | 3,213 | 2,333 | 2,362 |
| unit boundaries | 366 | 1,246 | 366 | 395 |
| labels multi-unit | 177 | 911 | 177 | 177 |
| **row adjacencies** (labels) | 253 (82) | 7 (5) | **7 (5)** | 40 (31) |
| pair-evidence sets split across units | 101 | 153 | 72 | 43 |
| of which **new vs FFD** | 0 | 101 | **28** | 10 |
| max unit bytes | 1,516,994* | 1,516,994* | 1,516,994* | 1,516,994* |
| **300 KB** units | 2,938 | 3,752 | 2,938 | 3,065 |
| unit boundaries | 971 | 1,785 | 971 | 1,098 |
| **row adjacencies** (labels) | 529 (161) | 42 (28) | **42 (28)** | 124 (83) |
| pair-evidence split / new vs FFD | 237 / 0 | 167 / 78 | 109 / 28 | 89 / 9 |
| implementation complexity | exists | low | low | trivial |

\* In every strategy the maximum unit size is set by **one binary file** (S2276, 1.52 MB), which exceeds any budget. The largest text file is 241 KB. The binary policy (package D3) governs it; no partition rule can.

**Refinement tested:** a pair-aware remainder keeps C's row placement and places each remaining file in the unit that already holds most of its pair-evidence partners. It gives **identical** results (7 / 72 / 28 at 600 KB; 42 / 109 / 28 at 300 KB). **The 28 new pair splits are forced by the row placement itself**, not by the remainder fill.

## 3. Constraints checked

| Constraint | Result |
|---|---|
| **Evidence order** (does partition reorder source evidence?) | **No, for any strategy.** A partition assigns files to reading contexts; the evidence order (the timeline) is a synthesis attribute formed from each file's historical position, not from unit membership. **"Same unit" ≠ "same chronological position".** |
| **Unit order along row-source S-id order** | monotone (0 violations) for B, C and D. FFD is non-monotone in 69 labels (600 KB) and 130 (300 KB). This is placement only, since units are read in parallel, not an evidence-order change |
| **Transitions removed by co-location?** | **No.** `change_vs_previous` is computed at synthesis over the timeline. Co-locating two files in one unit lets one reader compare them, and can remove no transition. |
| **Label membership** | preserved: each label's units contain exactly its required set R(L) (stage-2 ∪ row sources), with no cross-label mixing |
| **Source integrity** | files are never split in any strategy |
| **Byte budget** | met by every unit **except** the binary file above (all strategies alike) |
| **Chronological proxy: S-id order vs recorded dates** | **Violation found in the proxy, not in the partition.** Of 5,683 dated row-source pairs, **634 (11.2%)** have S-id order opposite to `best_historical_date`, in 85 labels; **965 row sources lack an ISO date**. The adjacency metric (and OB0018's frozen adjacency definition) uses S-id order as the proxy. **Consequence:** for labels whose row sources all fit one unit (1,939 of 1,975 at 600 KB) the proxy is irrelevant, because no adjacency exists in any order. For the multi-row-unit labels, the adjacency count under historical order can differ from the S-id-order count |

## 4. Shared-source analysis

- **Scale:** **928 files are row sources of ≥ 2 labels**, affecting **1,736 labels**. The maximum is 35 labels per file.
- **Coupling:** under the S5 decomposition design (as in both pilots), **partition is label-local**. Each label's units read their own copy of a shared file. So a placement for one label **cannot force** a placement for another. The problem decomposes into 1,975 independent sub-problems, **globally feasible by construction**.
- **Cost of label-locality:** repeated reading of shared files across labels. This is **identical for all four strategies**, because required sets are unchanged.
- **The alternative:** cross-label shared units would cut repeated reading but couple labels' partitions and synthesis provenance. That is an architecture choice, not measured here.

## 5. Dependency-boundary analysis ("does the partition create a new boundary where FFD did not?")

| Relationship | Measurable from metadata? | Finding |
|---|---|---|
| **P3a pair evidence** (the S-ids cited in the pair's `what_says_this`/`basis`, within R(L)) | yes | C removes net 29 pair splits vs FFD (101 → 72 at 600 KB) but **creates 28 new ones**. These are forced by the row placement (§2). Safeguard **S1** (package D8: pair context at synthesis) addresses exactly this exposure |
| **dependency edges** | **no**: edges are produced by agents, and population-wide edge metadata does not exist before S5 | not measurable; the co-label proxy is the shared-row-source count (§4) |
| **calibration relationships** (near-identical passages across units) | **no**: requires content | not measurable without reading; OB0018 found 0 on one label |
| **cross-label relationships** | via shared row sources | label-local partition creates no cross-label boundary (§4) |
| **evidence files supporting multiple labels** | yes (§4) | unaffected by strategy choice |

## 6. Capacity analysis (row sources only; never combined across labels)

| | 300 KB | 600 KB |
|---|---|---|
| labels whose row sources fit one unit | 1,916 | 1,939 |
| labels needing multiple row-source units | 28 | 5 |
| row adjacencies remaining under C | 42 (28 labels) | **7 (5 labels)** |
| **lower bound** Σ max(0, ⌈row bytes / B⌉ − 1) | 38 | **7** |

**Row-source bytes per label:** median 25,385 · p90 91,078 · p99 369,202 · max 1,593,012.

**The residual labels at 600 KB:**

| Label | Row sources | Row bytes |
|---|---|---|
| `step-verify-programme` | 25 | 1,593,012 |
| `kt-tuple-spine-wars-steps-26-40` | 16 | 1,268,320 |
| `capability-c10-knowledge-distribution` | 19 | 715,734 |
| `capability-c5-separation-attestation` | 15 | 644,236 |
| `mandatory-operation-set-gap` | 40 | 634,152 |

At 300 KB, 28 labels remain (listed in the output JSON).

**Optimality:**
- **At 600 KB, C attains the lower bound (7 = 7): it is optimal for the row-adjacency objective.**
- At 300 KB, C gives 42 against a bound of 38. Contiguous next-fit is optimal among *contiguous* segmentations. The bin-packing bound may not be attainable contiguously, and any non-contiguous row placement adds adjacencies.

## 7. Deterministic optimization formulation

**No weights (λ) are justified by the frozen methodology, so the formulation is lexicographic.**

For each label L independently (the problem is separable, §4): choose a partition π of R(L) into units that satisfies, and optimizes in order:

1. **Hard constraints:**
   - Σ bytes(u) ≤ B for every unit u (binary files excepted, governed by package D3);
   - files are never split;
   - membership = R(L) exactly;
   - no cross-label mixing.
2. **Minimize** the chronological row-source boundaries: #{i : π(r_i) ≠ π(r_{i+1})} over the row sources in **chronological order**.
3. **Minimize** the pair-evidence boundaries: #{pairs p : the evidence of p lies in > 1 unit}.
4. **Minimize** the shared-source conflicts. **Zero by construction** under label-local partition.
5. **Minimize** |π| (the number of units).

**Properties:**
- **Objective 2** is solved exactly by contiguous next-fit over the ordered row sources, whenever the ordered sequence is the constraint. Under a lexicographic order no later objective may trade it away.
- **Objective 3 conflicts with objective 2 for 28 pair-evidence sets** (§2). The lexicographic order resolves this in favour of 2, and S1 covers the residue.
- **Objective 5:** C matches FFD's unit count (2,333 / 2,938). No trade was needed on this population.

**Open specification point for the human, not decided here:** the **order used in objective 2**. The options are:
- S-id order (the proxy, 11.2% inverted against recorded dates);
- `best_historical_date` then S-id (965 row sources undated);
- the production timeline order.

This affects only the 5 (600 KB) or 28 (300 KB) multi-row-unit labels.

## 8. Implementation implications

- **C** is a small deterministic change to the partition function (row sources first, contiguous; FFD fill). It can be tested with the existing plan-reproduction pattern (OB0018 `test_plan_reproduces_partition`).
- **The chronological order key** (§7) must be fixed in the contract before implementation.
- **The binary file** exceeding any budget is not a partition issue; it needs the package D3 decision.
- **Label-local partition** must be stated explicitly in the S5 contract.
- **ML:** none. Partition membership is deterministic. Later ML may prioritize **investigation** (for example, predicted review cost of the 5 residual labels), never define the partition.

## 9. What remains empirically unvalidated

- **Fidelity:** whether fewer row adjacencies improve reconstruction fidelity. Not measured; OB0018 is UNDETERMINED and n = 1.
- **The dependency-edge and calibration effects** of any partition. Not measurable from metadata.
- **The adjacency count under historical rather than S-id order** for the multi-row-unit labels.
- **The effect of the 28 forced pair splits** with S1 in place.
- **The heavy-tail labels** (5 at 600 KB), which remain multi-unit under every strategy.

**ROW-FIRST PARTITION VALIDATION COMPLETE — NO S5 DECISION MADE**
