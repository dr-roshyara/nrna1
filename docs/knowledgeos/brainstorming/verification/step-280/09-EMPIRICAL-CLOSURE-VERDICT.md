---
artifact: 09 · EMPIRICAL-CLOSURE-VERDICT
step: 280
date: 2026-08-30T23:24:02+02:00
env: Python 3.13.2 · repo 57d93b0e · PHP 8.x
status: **EC = NOT ACHIEVED**
---

# Empirical Closure Verdict

## The verdict

> # EC = NOT ACHIEVED
> **Two independent reasons, both observed, neither inferred.**

## Reason 1 — Critical Failure Rule #7 is TRIGGERED

§40 lists ten conditions that **automatically prevent** empirical closure. **One fired:**

> **#7 — missingness is silently converted into a substantive value.**

**Observed (E4):**
```
unknown (no evidence)  -> Sigma = (Neutral, None)        distinguishable
absent                 -> a not in A                      distinguishable
not-asked              -> INDISTINGUISHABLE from absent    *** FAILURE ***
orphan (EKP)           -> representable in EKP, NOT in K   *** no K representation ***
```
The theory cannot distinguish *"nobody ever asked"* from *"we asked and it is not there."* Both are `a ∉ 𝒜`.
**Error category: `T` — theory defect.** Not implementation, not data.

**And a second-order finding:** the real EKP has `orphan_document` — *asserted but unconnected* — a fourth
kind of missingness for which **the theory has no representation at all.** The implementation is here
richer than the theory.

## Reason 2 — 15 of 24 tests have NO real-environment observation

The evidence hierarchy states only **Levels 4–6** count as substantive empirical evidence, and **Level 5 =
real KnowledgeOS validation.**

| Real-environment executability | Count | Tests |
|---|---|---|
| **YES — Level 5** | **5** | E1, E2, E10, E22, E23 |
| **PARTIAL — Level 5** | **4** | E4, E8, E12, E14 |
| **NO — NOT OBSERVABLE** | **15** | E3, E5, E6, E7, E9, E11, E13, E15, E16, E17, E18, E19, E20, E21, E24 |

> **Only 8 of 24 tests carry Level-5 evidence.** The other 16 PASSes are **Level 4 — controlled tests
> against the reference implementation I wrote from the Step 279 specification.** That is real evidence of
> **computational** closure and **not** evidence of **empirical** closure.
>
> **A test that passes against my own implementation of the specification cannot validate the
> specification against the world.** Reporting those 16 as empirical evidence would be exactly the
> "inferred status" this execution was commissioned to eliminate.

## What WAS observed in the real system

```
knowledge-lint.php    exit=0    "All documents pass."      37 governed documents
knowledge-graph.php   exit=0    39 nodes, 70 edges
                      run twice -> byte-identical           REPRODUCIBLE
```
Real Level-5 confirmations: **K representation (6/6 real assertions), state equality, typed lineage edges,
supersession with retention, and per-rule explanation.**

## The three closure dimensions, kept separate

| Closure | Status | Basis |
|---|---|---|
| **Formal** | **CONFIRMED** | Steps 272–278; 30/30 symbols resolved |
| **Computational** | **ACHIEVED** | 22/24 E-tests + 14/14 F-tests execute; reference implementation runs |
| **Empirical** | **NOT ACHIEVED** | critical failure #7; 15/24 not observable |
| **Governance** | **NOT CLAIMED** | out of scope for Step 280 |

$$CC \ne EC$$ — **and this execution is the demonstration.** Computational closure was achieved and
empirical closure was not, in the same run.

## Statistical summary

```
Corpus: 36 cases, 12 classes x 3, 39% negative/boundary (minimum 20%), 8 real + 28 synthetic

TP=22  TN=14  FP=0  FN=1   N=37
Accuracy = 0.973    Precision = 1.000    Recall = 0.957
```
> **FP = 0 is the load-bearing number: no negative control was ever wrongly Permitted.**
> Every unauthorized action, expired policy, missing authority and policy conflict was refused.
> **Accuracy of 0.973 must NOT be read as near-closure** — §37 warns precisely against this, and the single
> FN is a foundational construct, not a rare edge case.

## Nine of ten critical conditions clear

| # | Condition | Fired? | Evidence |
|---|---|---|---|
| 1 | K cannot represent a required real state | NO | E1: 6/6 |
| 2 | equality semantic error | NO | E2 |
| 3 | replay cannot reconstruct | NO | E8, F10 |
| 4 | policy history lost | NO | E15, F9, F10 — overwrite structurally impossible |
| 5 | unauthorized action permitted | NO | F2, F4, F7, F8; FP=0 |
| 6 | contradictory evidence collapsed | NO | E6, E21 — both retained |
| **7** | **missingness converted to a value** | **YES** | **E4** |
| 8 | policy contradicts formal semantics | NO | F1, F3, F5, F6, F11 |
| 9 | essential transformation irreproducible | NO | E7, E8; graph byte-identical |
| 10 | real behaviour needs an undefined primitive | NO | 30/30 symbols |

## Theory revision required: YES

Per §36's taxonomy the mismatch is category **`T`**. Per the revision rule
`Contradiction → Reproduce → Classify → Locate → Correct → Retest`:

- **Reproduced:** yes, deterministically, in E4.
- **Classified:** `T` — theory defect.
- **Located:** `𝒜 = Set(Assertion)` has no representation for *"a proposition that was never formed."*
  Absence-from-a-set is a single value doing two jobs.
- **Correction (NOT applied — Step 280 must not repair):** an explicit `⊥`-assertion, or a separate
  *questions-asked* register. **Both are theory changes and belong to Step 281.**
- **Retest:** pending.

## Next

`STEP 281 — GAP CLOSURE VERIFICATION` · `STEP 282 — THEORY CLOSURE DECISION`.
**Theory completeness is NOT claimed.**
