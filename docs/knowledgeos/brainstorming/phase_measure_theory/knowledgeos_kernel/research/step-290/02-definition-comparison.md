# 02 — Definition-by-Definition Comparison (mandate §§2, 3)

## The five definitions the corpus actually supplies

| # | locus | symbol | definition | self-label |
|---|---|---|---|---|
| D1 | `246 A` | `=` | field / representation identity | definition ⚠️ canonicalization unbound |
| D2 | `261.5` | `≈` | `∀O ∈ 𝒪: O(H_1)=O(H_2)` — **histories** | **definition** |
| D3 | `261.5` | `≈` | `∀O ∈ 𝒪_K: O(K_1)=O(K_2)` — **states** | **definition** |
| D4 | `261.21` | `≡_K` | `∀O ∈ 𝒪_K: O(K_1)=O(K_2)` | ⭐ **CANDIDATE** |
| D5 | `258.8` | `≡_K` | `∀O ∈ 𝒪: O(K_1)=O(K_2)` | ⭐ **PROPOSAL** |

**D3 and D4 are character-for-character the same predicate.** That is the fact Steps 288–289 found.

## What Steps 288–289 inferred, and why it does not follow

> **Inference made:** D3 = D4 ⟹ either `≈` collapses into `≡`, or `≡` is undefined ⟹ **corpus contradiction.**

**The inference treats D4 as an assertion. It is not.** `261.21` in full:

> *"Instead, define **a candidate semantic equality**: `K_1 ≡_K K_2 ⟺ ∀O ∈ 𝒪_K: O(K_1)=O(K_2)` … However:
> `𝒪_K` is not yet completely closed. Therefore this is **a candidate formal definition, not a completed
> theorem**."*

And `258.8` is a proposal — *"a stronger definition **is** observational"* — with `258.37` confirming:
*"We still do not have the final `=_X` or `≡_K`."*

$$\boxed{\begin{array}{c}\text{D4 and D5 are PROPOSALS TO FILL the } \equiv_{sem} \text{ slot.}\\ \text{Proposing } \equiv := \approx\text{'s formula is not ASSERTING } \equiv = \approx.\end{array}}$$

**A register that says *"`≡` and `≈` are distinct"* and elsewhere says *"here is a candidate for `≡`, and
it happens to be `≈`'s formula, unratified"* is not contradictory. It is a register with one slot
filled, one slot empty, and an open proposal.**

## The two speech acts, distinguished

| | act | consequence if true |
|---|---|---|
| **assertion** | *"`≡_K` **is** `∀O ∈ 𝒪_K`"* | `≡` and `≈` are the same relation → the register contradicts itself |
| **candidate** | *"`≡_K` **could be** `∀O ∈ 𝒪_K`; not a theorem"* | the register stands; a **ratification question** is created |

**Both loci perform the second act.** ⚠️ **This is the whole of the correction** — the formulas were read
correctly; the modality was not.

## Which relation has content?

| relation | content |
|---|---|
| `=` | ✅ a definition ⚠️ conditional on an unbound canonicalization |
| **`≈`** | ✅ **a definition** (`261.5`) ⚠️ conditional on an unclosed `𝒪_K` |
| **`≡`** | 🔴 **NO independent definition. Two unratified candidates, both borrowing `≈`'s formula** |
| `≅_I` | 🟡 `id(x)=id(y)`, transitive within a context (`I_48`) |
| `≅_P`/`≅_λ` | 🔴 two interpretations (`261.8`), no predicate |
| `≅_H`/`∼_H` | 🟡 `258.11`'s strong form; `𝒯` open |

> ⭐ **The asymmetry is the real structure:** **`≈` is the relation with a definition; `≡` is the relation
> with a name.** The corpus's candidate move is therefore the natural one — *define the unfilled slot
> using the filled one* — and its own reason for not ratifying it is that **the filled one is itself
> conditional on an unclosed `𝒪_K`.** **The blocker is `𝒪_K`, not a contradiction.**

## STATUS
**ESTABLISHED** 5 definitions; D3 ≡ D4 as predicates; both `≡_K` loci are candidates; `≈` has content and
`≡` has only a name · **WITHDRAWN** the contradiction reading · **TECHNICALLY OPEN** `≡`'s content ·
**BLOCKED** on `𝒪_K`
