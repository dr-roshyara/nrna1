# CORPUS AUDIT — do case-level KR-ZERO records exist?

**The question, restated as review corrected it:** *locate and inspect the original case-generating
artifacts and case-level outputs underlying `KR-ZERO-ORDER-2026-09`; determine whether a
reconstructible case-level corpus exists.*

**Answered from the files, not by hypothesis.**

---

# 1. What was actually persisted — the audit finding

> ## The 1,395 case-level records were **NEVER PERSISTED**. `property-results.json` is purely aggregate.

| artifact | size | content |
|---|---|---|
| `property-results.json` | 4 677 B | **aggregate only** — counters and distributions (`minimal_k_distribution`, `by_subset_size`, …). **No case rows.** |
| `witnesses/ALL_irreducible_witnesses.json` | 331 KB | **481 case-level records — but only the IRREDUCIBLE ones** |
| `witnesses/O1_minimal_k_witnesses.json` | 5 KB | 10 illustrative records (a display cap, not a sample) |
| `witnesses/O5_k_monotone_witnesses.json` | 2 KB | 4 records |
| `filter-accounting.json` · `paired-reanalysis.json` · `mechanism-2x2.json` | small | aggregate |
| `seeds.json` | 88 B | **the reconstruction key** |
| `code/` | 7 modules | **the generator** |

> **So the review's suspicion was correct, and the distinction it drew is the right one:**
> ```
> aggregate KR-ZERO results  ≠  case-level KR-ZERO dataset
> ```
> **Only the tail (the 481 irreducible) was saved. The other ~1 280 records existed only in memory.**

# 2. But they are EXACTLY reconstructible — and this is now demonstrated, not asserted

The generator is deterministic, the seeds are recorded, and `code/emit_corpus.py` replays the exact
pipeline. **The reconstruction was verified against the published aggregates:**

| | emitted | published | match |
|---|---|---|---|
| size-tests | **1 395** | 1 395 | **✔** |
| `k=1` / `k=2` / `k=3` / irreducible | **1 252 / 25 / 3 / 115** | 1 252 / 25 / 3 / 115 | **✔** |

> **All aggregates reproduce. The corpus is now materialized.**

## The emitted corpus — `corpus/`

| file | records | grain |
|---|---|---|
| **`cases.jsonl`** | **1 395** | the published unit: a **level test** `(D, T, Π, m)` |
| **`cases.csv`** | 1 395 | the same, flat columns |
| **`subsets.jsonl`** | **14 194** | **every individual subset** with its Zero status |

> ### ⚠️ The unit of analysis differs from the schema proposed in review
> Review's sketch had `S = subset tested for elimination` as the row key. **The 1 395 are not
> subsets.** Each is a **level test over ALL subsets of size `m`** in one context — which is why
> `minimal_k` is defined per `(context, m)` and not per `S`. **`subsets.jsonl` supplies the per-`S`
> grain**, 14 194 rows, for anything that needs it.

## Fields that actually exist

```
corpus_index  seed  representation_class  generator_shape  n
D_tokens  D_sources  D_polarities  D_scopes  D_uncertainties
transformation  transformation_class     (relational | elementwise)
contract        contract_class           (cancelling | noncancelling)
record_id  subset_size_m  n_subsets_at_m  zero_count_at_m
minimal_k  irreducible
    + when irreducible:  k_exhausted  colliding_subsets  colliding_zero
```

---

# 3. Can this support `KR-REP-DATASET-2026-09`? — **NO**

The proposed schema wanted `D · Q(D) · R₅ · R₄ · R₃ · R₂ · R₁ · C(Rₙ) · O(Rₙ)`.

**Searched every artifact in the experiment:**

| field | occurrences |
|---|---|
| `Q(D)` · `R5` · `C(Rn)` · `O(Rn)` · `representation_hierarchy` · `reduction_level` | **0 files, all of them** |

> ## `[NEG]` **The KR-ZERO corpus cannot support `KR-REP-DATASET`.**
>
> `R1`–`R4` in KR-ZERO are **representation CLASSES** — a categorical label for how a case was
> generated (token sequence / with metadata / graph / structured claim). They are **not a reduction
> hierarchy** `R₅ → R₁`, and there is no `Q(D)`, no `C(Rₙ)`, no `O(Rₙ)` anywhere.
>
> **The name collision on `R1..R4` is the trap**, and it would have produced a fabricated dataset had
> it gone unchecked.

**Constructing `KR-REP-DATASET` requires a NEW generating experiment.** It cannot be adapted from
this one, and no adapter should be written. **Stopping here is the correct outcome**, per the review's
own instruction.

---

# 4. Two things worth recording

**`[EXP/METHOD]` Persist the population, not only the tail.** This experiment saved the 481 witnesses
because they were the interesting cases — and thereby discarded the ~1 280 uninteresting ones that
define the denominator. **Reconstructibility rescued it here only because the generator is
deterministic and the seed was recorded.** A stochastic or externally-sourced corpus would have been
**unrecoverable**.

**`[EXP/METHOD]` A field name is not a field.** `R1..R4` meant something entirely different in the two
schemas. **Checking cost one grep; assuming would have cost a fabricated dataset.**
