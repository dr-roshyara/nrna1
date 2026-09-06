# AUDIT — denominator reconciliation, irreducible witnesses, and **two defects in my own design**

**Requested before freezing.** Two things were asked for; both are delivered, and the audit found
**two design defects that the published numbers did not reveal.** Neither overturns the central
result; one materially weakens the evidence I gave for it, and the replacement evidence is stronger.

---

# 1. Denominator reconciliation

> **The primary reason the denominators differ is not filtering. `O1`, `O2` and `O6` use DIFFERENT
> SEED OFFSETS and are therefore DIFFERENT SAMPLES.**

| sample | generated | size-filter | contexts | size-tests | irreducible |
|---|---|---|---|---|---|
| **O1** (all contracts, off=1) | 1 500 | −449 → 1 051 | −17 vacuous → **1 034** | −1 034 → **1 395** | **115 (8.24 %)** |
| **O2-A** (all contracts, off=2) | 1 500 | −403 → 1 097 | −12 vacuous → **1 085** | −1 085 → **1 475** | **117 (7.93 %)** |
| **O2-B** (non-cancelling, off=2) | 1 500 | −403 → 1 097 | −0 vacuous → **1 097** | −1 097 → **1 496** | **127 (8.49 %)** |
| **O6** (non-cancelling, off=6) | 1 500 | −404 → 1 096 | −0 vacuous → **1 096** | −1 096 → **1 546** | **122 (7.89 %)** |

**Every published figure reproduces exactly.** `filter-accounting.json` holds the full record.

**The four filter stages:**

1. **size** — `2 ≤ |D| ≤ 6`. Drops empties, singletons and `n > 6`. ~27–30 % of every sample.
2. **vacuity** — the `T7_meta_destroying × P9_balance` guard. **0 in the non-cancelling samples**,
   because vacuity only ever arose from that one pairing.
3. **incomparable** — `#{S : |S| = m} < 2`, so nothing to compare against.
4. → size-tests.

> **Why `skipped_incomparable` exactly equals `contexts` in all four rows:** the top level `m = n`
> always has exactly one subset, so **every context contributes exactly one skip and no more.** The
> equality is structural, not coincidental.

---

# 2. All 481 irreducible witnesses — `witnesses/ALL_irreducible_witnesses.json`

Each carries: sample · case id · `n` · representation class · transformation · contract · subset size
`m` · `k` exhausted · `D` tokens/polarities/sources · the colliding subsets · **their Zero statuses** ·
**the full singleton-Zero map**.

**Pooled breakdown:**

| by transformation | | by contract | | by size |
|---|---|---|---|---|
| `T2_dedup` | **228** | `P3_required` | 98 | `m=2` | **340** |
| `T8_interacting` | **173** | `P2_len` | 73 | `m=3` | 125 |
| `T4_context` | **73** | `P1_result` | 61 | `m=4` | 16 |
| `T5_reference` | 3 | `P4_provenance` | 53 | | |
| `T3_normalize` | 2 | `P7`/`P6` | 49 / 49 | | |
| `T6` / `T1` | 1 / 1 | `P5` / `P8` / `P9` | 44 / 38 / 16 | | |

**Representation class is flat** — R1 129 · R2 111 · R3 131 · R4 110. **Not a driver.**

**A fully specified witness:**

```
sample      O1        case 11      n=5      class R3
T = T2_dedup          Π = P2_len        m = 2      k exhausted = 1
D           [ x , c , x , c , x ]
singletons  {0:False, 1:True, 2:True, 3:True, 4:True}
colliding   {0,1} Zero=True   {0,2} Zero=False   {0,3} Zero=False
```

`{0,1}`, `{0,2}`, `{0,3}` share an identical `k=1` signature — element 0 non-Zero, the partner Zero —
**and differ in group Zero.** No singleton data separates them.

---

# 3. ⚠️ DEFECT 1 — my `O2` comparison was **not paired**

`O2-A` drew contracts from `PSET` (9) and `O2-B` from `NONCANCEL` (8). **Different pool sizes
desynchronize the RNG stream, so the two samples differ in their TRANSFORMATION mix as well as their
contract pool.**

**Given that irreducibility turns out to be overwhelmingly transformation-driven (§4), the unpaired
comparison cannot separate a contract effect from a transformation-mix effect.** The 7.93 % → 8.49 %
difference I published was therefore weaker evidence than I presented it as.

**Replacement: a fully paired re-analysis** — identical `(D, T)` contexts, every contract evaluated
against each. 1 061 contexts, `paired-reanalysis.json`.

| | irreducible | rate |
|---|---|---|
| `P9_balance` (cancelling) | 73 / 1 252 | **5.83 %** |
| all non-cancelling | 885 / 11 112 | **7.96 %** |

> **The conclusion HOLDS and is now properly evidenced.** Under a paired design the cancelling
> contract has a **lower** irreducibility rate than the non-cancelling ones. **Higher-order
> interaction is not produced by cancellation** — confirmed, this time by a design that can support
> the claim.

---

# 4. The dominant factor is the TRANSFORMATION — and the mechanism is visible

**Paired, so the contract mix is held constant:**

| transformation | irreducible | rate |
|---|---|---|
| **`T2_dedup`** | 378 / 1 341 | **28.19 %** |
| **`T8_interacting`** | 360 / 1 575 | **22.86 %** |
| **`T4_context`** | 182 / 1 620 | **11.23 %** |
| `T6_meta_preserving` | 14 / 1 746 | 0.80 % |
| `T5_reference` | 12 / 1 575 | 0.76 % |
| `T3_normalize` | 9 / 1 773 | 0.51 % |
| `T1_stopword` | 3 / 1 638 | 0.18 % |
| **`T7_meta_destroying`** | 0 / 1 096 | **0.00 %** |

**Range across transformations: 0 % → 28.2 %. Range across contracts: 5.0 % → 9.7 %.**

> ## The mechanism, and it falls straight out of the classification
>
> | high-irreducibility | what the transform reads |
> |---|---|
> | `T2_dedup` | **equality between elements** — whether an earlier element matches |
> | `T4_context` | **adjacency** — whether the predecessor matches |
> | `T8_interacting` | **both** (dedup ∘ stopword) |
>
> | near-zero | what the transform reads |
> |---|---|
> | `T1` `T3` `T5` `T6` `T7` | **each element alone** — a per-element predicate, function or projection |
>
> `[EXP]` ~~**Transformations that are element-wise maps yield `k = 1`.**~~ **FALSIFIED — see
> [`MECHANISM.md`](MECHANISM.md).** 7 irreducible witnesses occur under element-wise transformations,
> all of them under the **cancelling contract**. The corrected statement:
>
> **Irreducibility requires relational structure in `T` OR in `Π`. With both element-wise it never
> occurs — 0 / 33 840.**
>
> **This anticipates the proposed `KR-ZERO-MECHANISM` experiment and largely answers it:** the
> mechanism is not several unrelated special cases. It is one structural property — **whether `T` is
> element-wise or relational** — and the ordering `T2 > T8 > T4 ≫ rest` tracks how much relational
> information each reads.
>
> **What would falsify it:** an element-wise transformation with a high irreducibility rate, or a
> relational one near zero. **Neither appears in the tested set**, and the set is small — **8
> transformations, of which 3 are relational. `[PROP]` pending a wider transformation family.**

---

# 5. ⚠️ DEFECT 2 — three of my nine contracts are **extensionally identical**

`P1_result`, `P2_len`, `P3_required` scored **exactly** 134/1 389 each. That is not coincidence.

```
P2_len      = ( P1_result , len(items) )
P3_required = ( P1_result , required-token subset )
```

**Both added components are FUNCTIONS of `P1_result`**, so equality of the pair holds iff equality of
`P1_result` holds. **Verified: 0 disagreements in 81 440 element tests across all 8 transformations.**

> `[DEFECT]` **The contract factor has 7 distinct levels, not 9.**

**Consequence for the earlier experiment, stated because it is a correction:** `KR-ZERO-ALGEBRA`'s
`H8` counted contract *pairs*, and the pairs `P1|P2`, `P1|P3`, `P2|P3` **agree by construction** —
they inflated the "same" count. **`H8` reported 3 554 same / 495 different; the true
contract-sensitivity rate is therefore HIGHER than published.** The direction of the correction
**strengthens** `H8` (Zero *is* contract-relative) and does not threaten it.

---

# 6. What this audit does and does not change

| | |
|---|---|
| the ladder — `k=1` ~90 %, `k≥2` ~2 %, irreducible ~8 % | **unchanged**, denominators reconciled |
| the 481 witnesses | **now fully specified** |
| *"higher-order survives removal of the cancelling contract"* | **conclusion holds; evidence REPLACED with a paired design** |
| *"the phenomenon is general"* | **NARROWED — it is TRANSFORMATION-SPECIFIC.** Three of eight transformations produce essentially all of it |
| contract factor | **7 levels, not 9** |
| `H8` in `KR-ZERO-ALGEBRA` | **strengthened**, denominator was inflated |

> ### The single most important correction
>
> **Do not say "Zero is higher-order".** Say:
>
> ```
> Higher-order eliminability arises for RELATIONAL transformations.
> For element-wise transformations, singleton information suffices.
> ```
>
> **The order belongs to `(R, T, Π, D, S)` — and within that tuple, `T` dominates.**

**Nothing frozen. Theory v1.2 unchanged · no v1.3 · no kernel · no algebra.**
