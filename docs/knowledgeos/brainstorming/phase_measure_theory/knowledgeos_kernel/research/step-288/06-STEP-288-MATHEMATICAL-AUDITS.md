# 06 — Mathematical Audits and Required Negative Results (mandate §§18, 19)

**Witness:** `exec/t288_audits.py` · **transcript:** `exec/OUT-t288_audits.txt`
**Axis sets `CORPUS`-verified** from Q4A §§2.2–2.6: `|A|=7 |S|=5 |R|=4 |V|=4 |C|=4` → **2240**
(`7×5×4×4×4`, the same figure in **five** corpus files: Q4A ×2, Q5 ×2, Q14).

> ⚠️ **Scope limit, stated first.** Every result below concerns **constructions defined in the witness
> file**. None establishes anything about the corpus's `=`, `≡`, `≈`, `≅_λ`, `≅_I`, `≅_H` or
> `Continuity` — **those have no decision procedures to test.** A test against my own construction
> cannot validate the corpus's relation. *(`259`'s own discipline, and `280`'s.)*

## §19 The ten tests

| | Test | Result | Class |
|---|---|---|---|
| **A** | `≈_X` reflexivity, all 32 `X` | ✅ **PASS** | `EXECUTED` + theorem |
| **B** | `≈_X` symmetry | ✅ **PASS** | `EXECUTED` + theorem |
| **C** | `≈_X` transitivity | ✅ **PASS** — all 32 induce a partition of 2240 | `EXECUTED` + theorem |
| **D** | containment: `X ⊆ Y ⇒ ≈_Y` refines `≈_X` | ✅ **PASS** — 211 pairs, 0 violations *(sampled)*; **the theorem is the evidence, not the sample** | theorem |
| **E** | duplicate/degenerate projections | ⭐ **exactly 32 distinct partitions of 32** — no axis degenerate | `EXECUTED` |
| **F** | structural equality properties | ✅ `=` is `≈_{A,S,R,V,C}`; 2240 blocks = all singletons | `EXECUTED` |
| **G** | is `=` a congruence? | 🔴 **NO** — `k1 =_visible k2` True, `T(k1)=T(k2)` False | `EXECUTED` |
| **H** | hash/fold limitations | 🔴 canonicalization-relative; `id` re-keys on withdrawal | `EXECUTED` |
| **I** | finite-sample equality | 🔴 `f,g` agree on `0..9`, differ at `10` | `EXECUTED` |
| **J** | composition of relations | ⚠️ **`≈_S ∘ ≈_V` is the UNIVERSAL relation** — see below | `EXECUTED` |

### ⭐ E — "at most 32" is now **exactly 32**
Prior artifacts said *"32 candidate projections, inducing **at most** 32 distinct relations, since
different `X` induce the same partition when an axis is degenerate."* **Measured: no axis is
degenerate (every axis has ≥2 values), and all 32 induce distinct partitions.**
$$\boxed{\text{exactly 32 distinct } \approx_X \text{ relations on } \Sigma} \qquad |\text{blocks}|: \ \emptyset \to 1,\quad \{A,S,R,V,C\} \to 2240$$
**A tightening of my own claim, by measurement.** ⚠️ **And still `Σ`-level: not the corpus's `≈`.**

### ⚠️ J — corrected reading of my own output
The witness printed *"0 transitivity violations"* for `≈_S ∘ ≈_V` and I nearly recorded that as
*"composition is safe."* **It is the opposite.** Since `m` can be chosen freely with `m.S = s_1.S` and
`m.V = s_2.V`, **such an `m` always exists — so the composition relates every pair.** It is the
**universal relation**: trivially an equivalence relation, and carrying **zero information**.
$$\boxed{\text{Composing two projections COLLAPSED } \Sigma \text{ to one class. 0 violations because nothing was distinguished.}}$$
**A vacuous PASS read as a substantive one — caught in the transcript, recorded here.** The general
fact stands: **the composition of two equivalence relations is not in general an equivalence relation,
and relations must not be composed casually.**

## §18 The thirteen required negative results

| # | Claim to falsify | Attempted proof | Counterexample / obstruction | Verdict | Evidence |
|---|---|---|---|---|---|
| 1 | there is **one universal** equality relation | try to exhibit one adequate for all operations | `261.19`: 7 ops × 5 relations, all `UNRESOLVED`; boxed *"no single equality relation is adequate"* | 🔴 **FALSIFIED** | `CORPUS` |
| 2 | semantic equality is **already fully specified** | look for a definition of `≡` | `01 §3.3`: the only definition offered for `≡_K` is the **observational** one, which `261.1` says is a *different* relation | 🔴 **FALSIFIED** | `CORPUS` conflict |
| 3 | observational equivalence is **defined at `K` level** | take `∀O∈𝒪_K` as the definition | `261.21` boxed: **`𝒪_K` is not closed** — there is no function to take the kernel of | 🔴 **FALSIFIED** | `CORPUS` |
| 4 | **`≈_X` is the corpus's `≈`** | compare indices | corpus indexes by **`𝒪`** (`258.8`,`261.5`,`261.21`); `25I.35` by **policy**; `≈_X` by **Σ-axis subset** — three rivals, mine not corpus-native | 🔴 **FALSIFIED** | `CORPUS` |
| 5 | `Σ`-order implies `K`-order | attempt the lift | `K` carries content·prov·history·governance·events·retraction — none a `Σ` coordinate; `258.35`: `K` should be **extensional**, not a tuple | 🔴 **FALSIFIED** | `DERIVED` |
| 6 | structural equality is a **congruence** | test on a state with a hidden component | `k1 =_visible k2` but `TraceOrigin` distinguishes | 🔴 **FALSIFIED** | **`EXECUTED` §G** + `258.10` |
| 7 | a hash identity **is** semantic identity | vary serialization | key order alone flips the verdict; `'03/04/2026'` is locale-ambiguous | 🔴 **FALSIFIED** | **`EXECUTED` §H** |
| 8 | same output on a finite sample proves equality | agree on a sample, extend | `f,g` agree on `0..9`, differ at `10` | 🔴 **FALSIFIED** | **`EXECUTED` §I** |
| 9 | provenance relevance is **an executable predicate** | look for `λ` | `261.8` gives two *interpretations*; a relevance **PRINCIPLE**, never a **PREDICATE** | 🔴 **FALSIFIED** | `CORPUS` |
| 10 | **Decision 3 alone** closes equality | assume `Π ∈ ≡` settled, then close | `𝒪_K` still open (cond. 1), `𝒯` still open (cond. 2), `≡`/`≈` conflict remains, congruence unproven | 🔴 **FALSIFIED** | `05 §16.2` |
| 11 | the 32 projections **close** observational equivalence | offer the 32 as `≈` | they are `Σ`-level and exactly 32; the corpus's `≈` needs `𝒪_K`; **`K→Σ` projection unspecified** | 🔴 **FALSIFIED** | `EXECUTED` §E + `CORPUS` |
| 12 | Step 288 can close kernel selection **independently of 261** | audit the six conditions | **0 of 6 resolved** | 🔴 **FALSIFIED** | `05` |
| 13 | equality closure **automatically** yields an implementable kernel | assume equality closed | `260.11` quotient not implementable; `04 §13` 0 of 8 quotient properties; `δ` commit case unspecifiable | 🔴 **FALSIFIED** | `CORPUS` |

$$\boxed{\textbf{13 of 13 falsified. Four by execution, nine from the corpus. Zero survived.}}$$

> ⚠️ **The mandate's rule observed:** *"never convert **not established** into **impossible**."*
> Of the thirteen, only **#8** and **#7** are *impossibility* results (and #7 only relative to an
> unfixed canonicalization); the rest are **not-established**, and several would become true once
> `𝒪`/`𝒯` close. **#2 is a corpus contradiction — repairable by a governance act, not impossible.**
> **Only `012 §35`'s undecidability of `≡` for arbitrary language is a genuine theorem-shaped limit.**

## STATUS
**ESTABLISHED** 10 tests run; `=` not a congruence; hash canonicalization-relative; finite samples
prove nothing; **exactly 32** distinct `≈_X` · **BOUNDED** `≈_X` at exactly 32 · **NORMATIVE** none
decided here · **TECHNICALLY OPEN** every corpus relation remains untestable — **no procedure to test**
· **BLOCKED** `𝒪_K`, `𝒯`, `δ` · **DEFERRED** empirical validation against a running system (`261.29`
row 13 stays 🔴)
