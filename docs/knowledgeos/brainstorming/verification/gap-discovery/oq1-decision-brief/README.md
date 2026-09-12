# `OQ-1` — Carrier Decision Brief

**2026-09-07 · prepared for Governance · evidence packet: `V1` (`carrier-options/`) + `V3` (`glyph-register/`)**

> ⛔ **This brief decides nothing.** It answers the four questions Governance specified, with evidence
> and options, and leaves every choice blank. **`OQ-1` is a governance decision and no experiment can
> decide it.** Theory v1.2 FROZEN · kernel NOT SELECTED · `𝒪_core` NOT FROZEN.

---

## 0. 🔴 Correction to `V1` — there are **THREE** implemented carriers, not two

`V1` reported two code estates and stated that the ratified eight primitives have **"0 lines of
code."** **Both are wrong.** Measured by class definitions:

| | estate | `.py` | carrier types defined |
|---|---|---:|---|
| **A** | `verification/zero-algebra/` | **59** | `Case`, `Rec(v,s,t,rank)`, `Rep(cls,items)`, `Item(token,source,uncertainty,scope,polarity,node,edge_to)` |
| **B** | `research/knowledgeos-sim/` | **55** | `EVal`, `Arg`, `State`, `Boundary`, `World`, `StructuredModel` |
| **C** | **`verification/**/exec/`** | **54** · **5 723 LOC** | **`class K`**, **`Assertion(id, P, e, c, t, σ)`**, **`Proposition(entity, dimension, value)`**, `Observation`, `Evidence`, `Provenance`, `Op` |

$$\boxed{\begin{array}{c}\textbf{Carrier C implements BOTH } K=(\mathcal A,\mathcal R) \textbf{ AND ratified-primitive types.}\\ \textbf{The ratified vocabulary is IMPLEMENTED. } V1\text{'s "0 lines of code" is withdrawn.}\end{array}}$$

⚠️ **Three estates of near-identical size — 59 / 55 / 54 `.py`.** `[INF]` This is not an estate with
one carrier and some sketches. **It is three parallel implementations of comparable maturity**, and
`OQ-1`'s real content is which of them the theory speaks about.

---

## Q1 — What carrier arrangement is being ratified?

| option | inherits | costs |
|---|---|---|
| **A alone** | the `Zero` / reduction / bridge / zoom family — the three separations, `FR-001`, `Zero`-is-not-an-element-property, **and `V2`'s two `OQ-4` witnesses** | orphans the v1.2 evaluation family; **loses the `reason` channel** |
| **B alone** | the 26 v1.2 experiments — `Sat`, `Contr`/FDE, closure, history, multiplicity, `N_eff`, Hilbert | orphans the reduction ladder; `R2`'s proven `E_S ≠ ∖` has nowhere to live |
| **C alone** | the verification-lane kernel work; **the only estate speaking the ratified vocabulary** | orphans **both** experimental families |
| **A + B + C, with declared seams** | **everything currently executed** | **two seam contracts become obligations** — see Q3 |
| **`KS = (𝒳,𝒜)`** *(conceptual)* | a clean measure-theoretic frame; `Γ(K_t) ∈ 𝒜`; filtration | **6 files, 0 code** — orphans all three estates unless declared as an *envelope* over them |

`[REC]` **No recommendation is offered on Q1.** The evidence supports any of the five; the choice is
about what the theory is *for*, which is not a measurable property.

## Q2 — What does `K` / `𝒦` mean in the ratified vocabulary?

**Seven definitions, ranked by load-bearing evidence** — `.md` files defining it, of those how many
co-occur with an executed result, and whether code implements it:

| definition | arity | files | with a result | in code | candidate status |
|---|:--:|---:|---:|:--:|---|
| **`K = (𝒜, ℛ)`** | 2 | **181** | **71** | ✅ **Carrier C** | **RETAINED** — dominant, implemented, `=_semantic π_K(K_t)` |
| **ratified `K_t`, 8 primitives** | 8 | **48** | **17** | ✅ **Carrier C** | **RETAINED** — the ratified surface, `C-022`, 50 attack classes |
| **`𝒦 = (K, H)`** | 2 | 25 | 12 | 🔴 | **LOCAL?** — state + history; bears on `KR-HISTORY` |
| **`𝒦 = (E,S,T,O,P,R,Π,A)`** | 8 | 16 | 9 | 🔴 | **HISTORICAL?** — an early 8-tuple, ≠ the ratified 8 |
| **`𝒦 = (K,C,T,E,A)`** | 5 | 12 | 6 | 🔴 | **HISTORICAL?** |
| **`K_t = (𝒜_t,ℰ_t,ℛ_t,𝒞_t,ℋ_t,Γ_t)`** | 6 | 7 | 5 | 🔴 | **HISTORICAL?** — *"the corpus's FIRST `K_t` tuple"* |
| **`𝕂 = {(G,σ,θ,λ,π)}`** | set of 5-tuples | 5 | 4 | 🔴 | **LOCAL?** |
| **`𝕂 = {admissible knowledge states}`** | set, members undefined | — | — | 🔴 | **LOCAL?** — a schema, not a definition |
| **`𝕂 = (Σ,M,B,D,O,δ)`** | 6 | **2** | **0** | 🔴 | ⭐ **REJECTED?** — **the only one with no result anywhere** |

$$\boxed{\begin{array}{c}\textbf{Two definitions carry } \mathbf{119} \textbf{ of the } 165 \textbf{ result-bearing files and are the only two in code.}\\ \textbf{One} (\mathbb K = (\Sigma,M,B,D,O,\delta)) \textbf{ carries ZERO results and is a clean rejection candidate.}\end{array}}$$

⚠️ **The `𝒦` vs `𝕂` vs `K` script distinction is load-bearing and undeclared** — three of the nine are
`𝒦`, three are `𝕂`, three are `K`/`K_t`. **A ruling that names "`K`" without naming the script leaves
the referent open.**

⚠️ **All statuses above carry a `?` and are CANDIDATE classifications from citation evidence only.**
Assigning retained / historical / local / rejected is the governance act.

## Q3 — Conceptual vs implemented carriers, so executed experiments stay citable

```
CONCEPTUAL          KS = (𝒳,𝒜) + filtration        6 files · 0 code
     ▲                        │  envelope?
     │                        ▼
RATIFIED            K_t over the 8 primitives       48 files · Carrier C
     ▲                        │
     │                        ▼
IMPLEMENTED   ┌───────────────┼───────────────┐
              ▼               ▼               ▼
         Carrier A       Carrier B       Carrier C
      Rep/Rec/Item    EVal/Arg/State   K/Assertion/Prop
        59 py            55 py           54 py
              │               │               │
EXECUTED   Zero, reduction,  Sat, Contr,   kernel, Σ,
           bridge, zoom,     closure,      congruence,
           OQ-4 witnesses    history       Closure(𝒦₉)
```

**Two seams, and only two:**

| seam | question | evidence |
|---|---|---|
| **A ↔ B** | what does an evaluator receive from a representation? | ⭐ **the `reason` channel** — A says *what* was removed, only B says *why*. Demanded independently by `KR-CONTR-FDE`'s two boundary collapses and `KR-CONTR-EVAL`'s *"no flat domain of any cardinality is adequate; the minimum is a pair with an indispensable reason component"* |
| **C ↔ (A,B)** | is `K = (𝒜,ℛ)` the state that A's representations represent and B's evaluations evaluate? | `(𝒜,ℛ) =_semantic π_K(K_t)` — **lossy, definable-not-computable**; `replay`, `policy-eval`, `authorize` unanswerable in `(𝒜,ℛ)` |

`[INF]` **A ratification that names a carrier and leaves these two seams undeclared produces a
formally ratified carrier whose relationship to every executed result is unstated.** That is the
failure mode Governance identified, and it is what Q3 exists to prevent.

## Q4 — What is deliberately NOT being decided

**Stated so the decision cannot silently widen:**

| not decided | why it must not ride along |
|---|---|
| **kernel selection** | `𝒪_core` is present in **51 %** of 66 worlds — any minimality claim inherits a coin flip. Both registers put selection **last** |
| **`𝒪_core`** | NOT FROZEN; membership rule absent |
| **Theory v1.3** | governance order is `EXPERIMENT → AUDIT → ADJUDICATION → v1.3`; adjudication has not happened |
| **`OQ-2` / `DECISION-02`** | a separate decision; it gates composition and `δ`, not the carrier |
| **the five conflict records** | `Contr`, `⪰`, `δ`, `≡_sem`, `Qualify` — each its own decision |
| **any new algebra** | the algebra results were negative; `Zero` is **not an element property** |
| **Gītā candidacy** | closed, `Theory 13 §6` |
| **every glyph collision** | ⭐ **explicitly out of scope per Governance.** Only the **carrier vocabulary** and the citations needed to identify the ratified object unambiguously. The remaining ~45 definitions across 15 glyphs are a **subsequent normalization task** (`H1`) |

## 5. What the decision needs to state, minimally

1. **the carrier arrangement** (Q1) — one of the five, or another the evidence supports;
2. **one canonical referent for `K`**, with the script named (`𝒦` / `𝕂` / `K`), and each of the other
   eight mapped to **retained · historical · local · rejected** (Q2);
3. **the two seam contracts**, or an explicit statement that they remain open and that results are
   cited as scope-bound until they are declared (Q3);
4. **the non-decisions** (Q4), so `OQ-1` cannot become a kernel-selection exercise.

## 6. What this brief does not do

- **recommends no option on Q1** — the evidence does not decide it, and saying otherwise would be
  preference dressed as measurement;
- **assigns no status on Q2** — the nine candidate classifications all carry `?`;
- **declares no seam** — Q3 states the two seams and their evidence, nothing more;
- **does not treat `V2` as carrier evidence.** `V2` is frozen. It ran on Carrier A at `R5` and is
  **scope-bound to it**; it is listed under Q1's inheritance for **Carrier A only**, which is a fact
  about where it ran, not an argument for ratifying A.

## 7. Reproduce

```bash
cd docs/knowledgeos
# load-bearing ranking per K-definition
for p in '\(𝒜, *ℛ\)' '\{ *Entity, *State, *Event' '𝒦 *= *\(K, *H\)' '𝒦=\(E,S,T,O,P,R,Π,A\)' \
         '𝒦 *= *\(K,C,T,E,A\)' '𝒜_t,ℰ_t,ℛ_t' '𝕂 *= *\{\(G,σ,θ,λ,π\)' '𝕂=\(Σ,M,B,D,O,δ\)'; do
  n=$(grep -rlE -- "$p" --include='*.md' . | wc -l)
  r=$(grep -rlE -- "$p" --include='*.md' . | xargs -r grep -lE '\[EXP\]|EXECUTED|witness' | wc -l)
  echo "$n  $r  $p"
done
# the three estates
for d in ../../verification/zero-algebra ../../research/knowledgeos-sim brainstorming/verification; do
  echo "$d $(find $d -name '*.py'|wc -l)"; done
```
