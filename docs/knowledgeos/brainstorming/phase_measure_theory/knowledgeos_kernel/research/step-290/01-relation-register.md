# 01 — Corpus Relation Register (mandate §3)

**20 primary entries. 5 carry a definition; 15 only NAME a relation.** Primary text only — no snippets.

| source | section | symbol | natural-language name | operands | definition (faithful) | procedure? | parameter | dependency | explicit distinction | explicit synonymy | status |
|---|---|---|---|---|---|---|---|---|---|---|---|
| `246` | A–D | `=` | structural equality | states | field/representation identity | ✅ | canonicalization | — | **YES** (non-interchangeable) | no | corpus |
| `246` | A–D | `≡` | semantic equality | states | — | 🔴 | — | — | **YES** | no | corpus, **named only** |
| `246` | A–D | `≈` | observational equivalence | states | — | 🔴 | — | — | **YES** | no | corpus, named only |
| `246` | A–D | `≅_λ` | provenance-sensitive | states | — | 🔴 | `λ` | — | **YES** | no | corpus, named only |
| `261` | 1 | `=` | representation/structural | states | — | — | — | — | **YES** (*"should not be collapsed"*) | no | corpus |
| `261` | 1 | `≡` | semantic equality | states | — | 🔴 | — | — | **YES** | no | corpus, named only |
| `261` | 1 | `≈` | observational equivalence | states | — | — | `𝒪_K` | `𝒪_K` | **YES** | no | corpus |
| `261` | 1 | `≅_I` | identity equivalence | references | — | 🟡 | id context | `I_48` | **YES** | no | corpus, named only |
| `261` | 1 | `≅_P` | provenance-sensitive | states | — | 🔴 | `λ` | Decision 3 | **YES** | no | corpus, named only |
| `261` | 1 | `≅_H` | historical equivalence | histories | — | 🔴 | `𝒯` | `𝒯` | **YES** | no | corpus, named only |
| **`261`** | **5** | **`≈`** | observational (histories) | **histories** | **`∀O ∈ 𝒪: O(H_1)=O(H_2)`** | 🔴 (`𝒪` open) | `𝒪` | `𝒪` | — | no | **corpus, DEFINED** |
| **`261`** | **5** | **`≈`** | observational (states) | states | **`∀O ∈ 𝒪_K: O(K_1)=O(K_2)`** | 🔴 (`𝒪_K` open) | `𝒪_K` | `𝒪_K` | — | no | **corpus, DEFINED** |
| **`261`** | **21** | **`≡_K`** | Knowledge-State equality | states | **`∀O ∈ 𝒪_K: O(K_1)=O(K_2)`** | 🔴 | `𝒪_K` | `𝒪_K` | — | ⚠️ **same formula as `≈`** | ⭐ **CANDIDATE** — *"a candidate semantic equality"*; *"a candidate formal definition, not a completed theorem"* |
| **`258`** | **8** | **`≡_K`** | Knowledge-State equivalence | states | **`∀O ∈ 𝒪: O(K_1)=O(K_2)`** | 🔴 | `𝒪` | `𝒪` | — | ⚠️ same shape | ⭐ **PROPOSAL** — *"a stronger definition is observational"*; `258.37`: *"we still do not have the final `≡_K`"* |
| `261` | 20·25 | `=_str` | structural | states | — | — | — | — | **YES** (*"not conflated"*) | ⚠️ **= `261.1`'s `=`** | corpus, 2nd notation |
| `261` | 20·25 | `≡_sem` | semantic | states | — | 🔴 | — | — | **YES** | ⚠️ **= `261.1`'s `≡`** | corpus, 2nd notation |
| `261` | 20·25 | `≈_obs` | observational | states | — | — | `𝒪_K` | `𝒪_K` | **YES** | ⚠️ **= `261.1`'s `≈`** | corpus, 2nd notation |
| `261` | 20·25 | `SameId` | identity | references | — | — | — | — | **YES** | ⚠️ **= `≅_I`** | corpus, 2nd notation |
| `261` | 20·25 | `≡_H` | historical | histories | — | 🔴 | `𝒯` | `𝒯` | **YES** | ⚠️ **= `≅_H`** | corpus, 2nd notation |
| `261` | 20·25 | `≡_P` | provenance | states | — | 🔴 | `λ` | Decision 3 | **YES** | ⚠️ **= `≅_P`** | corpus, 2nd notation |

## ⭐ Step 261 carries ONE register in TWO notations

$$\mathfrak K = (K,\ =_{str},\ \equiv_{sem},\ \approx_{obs},\ SameId,\ \equiv_H,\ \equiv_P) \qquad (\texttt{261.25})$$

**Six-for-six against `261.1`.** `≅_I ↔ SameId` · `≅_P ↔ ≡_P` · `≅_H ↔ ≡_H`.

⚠️ **Notation drift inside one document — not a second register.** The nine-register count from Step 288
**stands**. What is added: `261` is internally inconsistent in *glyphs* while perfectly consistent in
*content* — **and that drift is a plausible cause of the confusion Step 290 is resolving.**
*(`≡_H`/`≡_P` in the second notation reuse the `≡` glyph that the first notation reserves for semantic
equality. A reader tracking glyphs rather than sections would see `≡` doing four jobs.)*

## STATUS
**ESTABLISHED** 20 entries; 5 defined, 15 named; one register in two notations; the two `≡_K` loci are
self-labelled candidates · **TECHNICALLY OPEN** 15 relations have no definition · **NORMATIVE** none
decided here
