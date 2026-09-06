# 03 — Distinguishability Audit (mandate §4)

## The two required witnesses

| | Required | Result |
|---|---|---|
| **W1** | `∃K_1,K_2:` `K_1 ≡ K_2` **and not** `K_1 ≈ K_2` | 🔴 **UNDECIDABLE FROM CURRENT CORPUS** |
| **W2** | `∃K_1,K_2:` `K_1 ≈ K_2` **and not** `K_1 ≡ K_2` | 🔴 **UNDECIDABLE FROM CURRENT CORPUS** |

**EXECUTED, failing loudly as the mandate requires** (`exec/OUT-t290_n1_audit.txt`):
```
FAIL LOUDLY: required definition ABSENT -> a decision procedure for ==  (semantic)
FAIL LOUDLY: required definition ABSENT -> a closed observation set O_K
```

**Why neither witness can be built from admissible material:**
- **W1** needs a procedure for `≡` that is **independent of** the observational formula. The only two
  definitions offered for `≡_K` **are** the observational formula (`258.8`, `261.21`). Using either
  makes `W1` vacuously empty **by construction, not by fact**.
- **W2** needs a closed `𝒪_K` to evaluate `≈`. `261.21` boxes: *"`𝒪_K` is not yet completely closed."*
- And `012 §35`: `≡` is **not fully decidable** for arbitrary language — so no total procedure can exist.

$$\boxed{\textbf{NO HYPOTHETICAL WAS CONSTRUCTED.}}$$
⚠️ **The mandate's §4 prohibition — *"do not invent examples merely to make the relations differ"* — is
the binding constraint here.** It would have been easy to write a pair differing in provenance and
declare `≡` blind to it; **that would have assumed Decision 3's answer** in order to manufacture a
distinction. **Declined.**

## Therefore: on what does `N-1A` rest?

**Not on distinguishability. On the corpus's explicit design statements.**

| Locus | Statement |
|---|---|
| `246 §A–D` | four relations, **non-interchangeable** |
| `261.1` | six relations; *"The relations should therefore **not** be collapsed"* |
| **`261.20`** | *"KnowledgeOS requires a typed **family** of relations"* · *"they should **not be conflated**"* |
| **`261.25`** | `𝔎 = (K, =_str, ≡_sem, ≈_obs, SameId, ≡_H, ≡_P)` — **distinct tuple positions** |
| `261.19` | **no single equality relation is adequate for all operations** — presupposes plurality |

> ### The evidence strengths are different and must not be conflated
> | claim | strength |
> |---|---|
> | *"the corpus distinguishes `≡` from `≈`"* | ✅ **ESTABLISHED** — explicit prose, 5 loci, 2 notations |
> | *"`≡` and `≈` are semantically distinguishable"* | 🔴 **UNDECIDABLE** — no witness constructible |
>
> **`N-1A` asserts only the first.** A register can distinguish two slots without yet exhibiting a pair
> that separates them — **and that is exactly the corpus's position: a typed family with one slot filled,
> one slot empty, and a candidate proposal to fill the empty one with the filled one's formula.**

## §11 Required negative checks — all recorded as EXECUTED

`≡ = ≈` **not** assumed from both being called *"equivalence"* · `≡ ≠ ≈` **not** assumed from differing
symbols (Q1 rests on prose) · `≈_X` **not** assumed canonical · `≈_X` **not** assumed behavioural
(`258.11`) · `≅_λ` **not** assumed equivalent to either (`261.8` two-branched) · `Σ`'s existence **not**
treated as proof of observational equivalence · **`δ`-congruence dependency NOT treated as proof of
semantic equality** — *a dependency relation is not a semantic identity* · no philosophical source ·
no governance preference.

## STATUS
**ESTABLISHED** the distinction, from design statements · **TECHNICALLY OPEN / UNDECIDABLE** semantic
distinguishability — no witness constructible from corpus material · **DEFERRED** any witness until `𝒪_K`
closes or `≡` gains an independent procedure
