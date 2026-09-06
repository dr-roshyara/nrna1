# 02 — Test Case Specifications

## Corpus: 36 cases, 12 classes × 3

| Class | N | Kind mix | Real? |
|---|---:|---|---|
| ordinary | 3 | 3 pos | **REAL** (EKP docs) |
| evidence-supported | 3 | 3 pos | **REAL** (authority=authoritative) |
| evidence-refuted | 3 | 3 pos | synthetic — *the EKP cannot express refutation* |
| unknown/missing | 3 | 3 neg | 1 real (orphan) + 2 synthetic |
| contradictory | 3 | 3 neg | synthetic |
| supersession | 3 | 3 pos | 1 real + 2 synthetic |
| replay | 3 | 3 pos | synthetic |
| policy-controlled | 3 | 2 pos 1 neg | synthetic |
| authority-controlled | 3 | 1 pos 2 neg | synthetic |
| policy-change | 3 | 1 pos 1 neg 1 bnd | synthetic |
| conditional | 3 | 2 pos 1 neg | synthetic |
| measurement | 3 | 1 pos 2 neg | synthetic |

**Totals — observed:** 36 cases · positive 22 · negative 13 · boundary 1 · **negative+boundary = 39%** ·
real 8 · synthetic 28.

> **Why 28 are synthetic is itself a finding:** the real EKP has no evidence, no refutation, no temporal
> validity, no policy runtime and no authority runtime, so those classes **cannot** be drawn from it.
> Stratification was met; real-world sourcing was not, and could not be.

## Stratification
By **context** (global, prod, staging) · **operation** (assert, relate, retract, replay) ·
**epistemic status** (Supporting, Refuting, Contested, Neutral) · **policy state** (applicable, expired,
missing, conflicting).
