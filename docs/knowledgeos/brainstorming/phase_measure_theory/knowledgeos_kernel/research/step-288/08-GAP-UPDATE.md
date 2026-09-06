# 08 — Gap Update (mandate §§22, 23) · change control on every repaired claim

## §23 CHANGE CONTROL — every correction, source-first

| | BEFORE | AFTER | REASON | EVIDENCE | PROPAGATION IMPACT |
|---|---|---|---|---|---|
| **C-1** | *"the four-relation register originates at Step 246"* | **nine registers; earliest is `25I.11` (08-28), three relations** | 246 ranks **10th** by equality density; four earlier registers exist | `01 §3.1` · corpus sweep | repaired in **`REFINED-STEP-287` §2**, `04`, `E1-E7`, `exec/e_equality.py` header, `288`; **source-first done** |
| **C-2** | *"behavioural equivalence is not addressed in the corpus"* | **`258.11`, `260`, `261.1` all carry it** | phrase-search instead of concept-search | `CORPUS` | repaired at source in **`REFINED-STEP-287` §3**; `00-INDEX` bisimulation note |
| **C-3** | *"`Fold` supplies the quotient map"* | **`∼_F` is corpus-named and REJECTED as insufficient (`258.11`)** | conflated naive with strong history equivalence | `258.11`, `258.14` | **retracted in `14` §10**; `288 v2` §5; not propagated further |
| **C-4** | *"five relations"* | **nine** (v2 said 8; `Continuity` was missed) | `261.1` has six; `195.21` adds `Continuity` | `01 §3.1` | `288`, `00-INDEX` |
| **C-5** | *"`=` reaches Level 5"* | **Level 5 CONDITIONAL on an unfixed canonicalization** | `38.85`/`38.53`/`012 §27`; **EXECUTED** `06 §H` | `06 §H` | `03`, `04 §14`, `288`; **zero unconditional Level-5 state relations** |
| **C-6** | *"which relation is THE equality"* is a live decision | **the question is MALFORMED** | `261.19` boxed | `04 §11` | `07 N-11` reframed as *bindings* |
| **C-7** | *"`Qualify` is the single irreducible blocker"* | **one of FOUR partial epistemic functions** | `012 §46` `Resolve`/`Normalize`/`Compare` all `⇀`, all bodiless | `15 §4` | `288`; **`G-62` narrowed** |
| **C-8** | Decision 3 *purely normative* | **decomposes: technical half decidable by `258-A`** | `258.31` *"we do NOT ask philosophically"* | `07 §6` | `288 §9`, `07 N-2` |
| **C-9** | *"at most 32 distinct relations"* | **exactly 32** | measured: no axis degenerate | **EXECUTED** `06 §E` | `03`, `288`, `REFINED-STEP-287` §3 wording |
| **C-10** | *"✅ CLOSED — `I_48`"* (v2.1 heading) | **BOUNDED** — parameter *"identity context"* unbound | **mandate §24: *"do not use CLOSED globally"***; and it is honest | `02 §5.4` | `288` STATUS block restructured |
| **C-11** | witness note *"composition is safe, 0 violations"* | **`≈_S ∘ ≈_V` is the UNIVERSAL relation — a vacuous PASS** | 0 violations because nothing was distinguished | **EXECUTED** `06 §J` | `06 §J`; caught before publication |
| **C-12** | `Σ` axis cardinalities assumed | **`|A|=7 |S|=5 |R|=4 |V|=4 |C|=4` → 2240, `CORPUS`-verified in 5 files** | refused to reuse an unverified figure | Q4A §§2.2–2.6 | `06` header; ⚠️ `A`'s 7 values are **modes**, so `A` cannot order |

## Register movements

### 🟢 NARROWED (not closed)
| Gap | Was | Now |
|---|---|---|
| **`G-62` `Compare` undefined** | OPEN — undefined | **signature given** `Compare: 𝒫×𝒫×C×Ω → 𝒬` (`012 §46`); **body absent** — Level 3 |
| **`G-03` identity/equality** | unresolved | **`≅_I` transitive within a context** (`I_48`); 5 of 11 identity kinds still absent |
| **transitivity of the quotients** | unestablished → quotients in doubt | **resolved for `≅_I` per-context**; open for `≡` |

### 🆕 NEW
| ID | Gap | Class |
|---|---|---|
| **G-67** | 🔴 **`≡` and `≈` share one definition while listed as distinct** (`258.8`/`261.21` vs `261.1`/`246`) | ① **corpus contradiction — CRITICAL** |
| **G-68** | `=` and `≡_exact` are decidable **only relative to a canonicalization the corpus forbids fixing a priori** (`38.85`) | ② formal |
| **G-69** | **`Continuity`** is in no register; `195.24` identity ⊥ lineage | ① discovery |
| **G-70** | **four rival decision codomains**; `012 §36`'s `Relation ⊥ Status` principle unapplied | ② formal |
| **G-71** | 6 of the mandate's 11 operations have **no** equality analysis | ⑥ evidence |
| **G-72** | `G_I` is a separate graph (`38.88`, `195.43`) but the kernel has one 3-field `ℛ` | ③ architectural |
| **G-73** | equality's **decision-theoretic layer** (asymmetric loss, abstention) at Level 4, all parameters unbound; inherits `G-22` | ② formal |
| **G-74** | **`Closure(Identity)`** / `AffectedKnowledge(x)` absent from the `𝒪` discussion | ④ implementation |
| **G-75** | `≡` is a **`(C,t)`-indexed family**, treated everywhere as one relation | ② formal |

### ⬛ STANDING, unmoved
`G1`/`Qualify` *(reframed, not closed)* · `𝒪`/`𝒯` unratified · `G-22`/`MT-1` no `(Ω,𝓕,P)` ·
`G-55`/`D-5` `ℛ` 3-field · `G-57` `AuthorityAct` untyped · `G-15` `Context` untyped ·
`δ` commit case · `G-64` orphan · `G-65` 15 of 24 not observable · `G-66` `authorities.yaml` is an enum

### 🔴 CLOSED
**None.** Per mandate §24, *"CLOSED"* is not used globally in this step.

## Net position

| | |
|---|---|
| Gaps **narrowed** | 3 |
| Gaps **added** | **9** (G-67…G-75) |
| Gaps **closed** | **0** |
| Corrections applied source-first | **12** |
| `261.23` conditions resolved | **0 of 6** |
| Negative results attempted / falsified | **13 / 13** |
| Executable witnesses | 1 script, 10 tests, transcript retained |

> **This step added more gaps than it narrowed, and that is the correct outcome.** A register that was
> thought to hold four relations holds nine; two of them share a definition; the one relation everybody
> treated as trivially decidable turns out to carry an unbound parameter. **None of that is progress
> toward closure — it is progress toward knowing what closure would require.**

## Still not read *(recorded so this is not mistaken for coverage)*
`025k` · `025l` · `025m` · `025o` · `025t` · **`025v`** (semantics · ontology · meaning alignment) ·
`025w` · `step-017` (ontology) · `step-022` (identity · lineage · provenance) · **`step-039`**
(bounded-context translation — commissioned by `38.89`) · `step-073` · `step_203`.
**`025v`, `step-017` and `step-039` are the semantic-translation thread, and `Compare`'s missing body
would have to come from there.**
