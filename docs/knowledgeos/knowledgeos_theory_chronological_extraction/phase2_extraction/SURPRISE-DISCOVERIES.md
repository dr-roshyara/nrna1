# Surprise Report — Iteration 1

> ⛔ **A run reporting zero surprises has probably filled a schema rather than reasoned.**

## Not anticipated by Step 1

| Discovery | Why Step 1 could not see it |
|---|---|
| ⭐ **The authority schema cannot express its own worked example** (`SI-0007`) | Step 1 recorded `T-0014` as F0018 stated it. The cardinality argument (6 states required, 5-valued field supplied) requires *counting across* the definition, not transcribing it |
| ⭐ **The undefined `bar` may explain the empty back-edge** (`SI-0008`) | Step 1 recorded both facts in different registries. Relating them needs degenerate-case analysis of a formula neither file wrote down |
| ⭐ **The rediscovery counter measures detection effort** (`EM-0005`) | F0019 supplies both the acceleration claim and its confounder in the same section. Step 1 recorded the claim |
| ⭐ **PD-3 now outranks D-2** (`SI-0006`) | Requires counting *surviving* grounds after falsification — a subtraction Step 1 had no reason to perform |

## Relationships absent from P3A

P3A carries one date per file. Every ordering constraint used here (`OC-0001`–`OC-0008`) is invisible to it, and **45 % of event pairs cannot be ordered by date at all** — a measurement P3A's representation cannot express.

## Structures not in the protocol's list

⭐ **Two of six were discovered, not carried:** `STR-0003` check-before-admit as a total function, `STR-0004` append-only with supersession edges. **Neither appears in §9.1's `S-1..S-5`.** Phase F.1's search step is what found them.

## Contradictions between the emerging seed and the corpus

| Seed says | Corpus says |
|---|---|
| the acceleration claim is confounded | *"the rate is accelerating"* (F0019) |
| PD-3 outranks D-2 | D-2 is *"THE MODEL'S PRINCIPAL DISCOVERY"* (F0020) |
| the empty arrow may be definitional | it is an evidence gap (7 files) |

## Concepts important only through synthesis

**`check-before-admit`.** Performed five times across four files, named by none. It is arguably the corpus's most disciplined habit — and it was invisible until five instances were placed side by side.

## ⭐ And one surprise about the protocol itself

**`Competition` was adjudicated PREMATURE** on the ground that the corpus never calls these things competitions. **Construction demanded it anyway** (`RG-0002`): `CMP-0001` holds *three* rival readings with a conjunctive discriminator, which no edge can carry.

> **Evidence overrode an architectural judgement made two passes earlier.** That is the READ → CONSTRUCT → IMPLEMENT loop doing exactly what it exists to do.
