# 07 — Resolution: Step 275 (graded strength) vs Step 272B (binary polarity)

**Question:** is `S = {None, Weak, Moderate, Strong, VeryStrong}` (a) part of Σ, (b) orthogonal to Σ,
(c) derivable, (d) policy-dependent, or (e) obsolete?

Executed: `exec/resolve_275_vs_272b.py` · transcript `exec/OUT-resolve_275_vs_272b.txt`.

---

## Answer

$$\boxed{\;\Sigma = \mathcal P(\{\mathrm{Support},\mathrm{Refute}\})\quad\text{policy-INVARIANT}\;}$$
$$\boxed{\;\mathrm{Strength} = f(\mathrm{evidence},\ \mathrm{policy})\quad\text{policy-RELATIVE, an ASSESSMENT output}\;}$$

**They are not competing definitions of Σ, and they are not orthogonal dimensions.** Σ is the coarsest
**policy-invariant** abstraction of the evidence set; Strength is a **policy-relative** refinement of
the *same* evidence set. The contradiction dissolves once Assessment is separated from Σ — which
§271.10–11 and 272A.7 already require.

| Option | Verdict |
|---|---|
| (a) part of Σ | **NO** — it is policy-relative; Σ is not |
| (b) orthogonal to Σ | **NO — refuted.** It *nests*: 5 of 10 `(bit, grade)` pairs are impossible |
| (c) derivable | **YES** — from the same evidence set Σ is derived from |
| (d) policy-dependent | **YES** — and that is exactly what excludes it from Σ |
| (e) obsolete | **NO** — three operations need it: `Qualify`, `Assess`, `Compare` |

---

## 0. The scale is real, and it is the survivor of a 2240 → 4 reduction

Step 275's premise checks out. The scale is in the corpus, not invented:

| Source | Date |
|---|---|
| Q14 — "the KnowledgeOS type system, complete formal definition" | 2026-08-26 |
| Q16 — epistemic logic | 2026-08-26 |
| "KnowledgeOS — the complete mathematical model" | 2026-08-26 |
| closure-03 — `overall_support: SupportLevel` | 2026-08-27 |

And its **original home** (`spec/STEP-TRACE-B2`, tracing Q14):

$$\Sigma = (Acquisition, Support, Resolution, Validity, Conflict) = 7\times5\times4\times4\times4 = \mathbf{2240}\ \text{states}$$

The reduction sequence:

| | `|Σ|` | Shape |
|---|---:|---|
| **Q14** (08-26) | **2240** | five dimensions |
| **Step 275** (08-30 21:46) | **15** | `D × S = 3 × 5` |
| **Step 272B** (08-30 22:50) | **4** | `{0,1}²` |

**272B's exclusions map exactly onto Q14's dimensions** — `Acquisition` → provenance,
`Resolution` → §272A.9, `Validity` → §272A.10, `Conflict` → derived as `(1,1)`, and `Support`
kept but collapsed 5 → 2.

**This is a fourth reduction in the sequence `canonical-construction` diagnosed as transcription
loss** (`ℛ` 8→3, status 10→2, `DC` 7→6). The question this document answers is whether *this* one
discarded something. **It did not — but only because what it discarded belongs elsewhere.**

---

## 1. TEST — orthogonal or nested?

The reviewer proposed `Σ ⊥ Strength`, both coexisting. **Refuted.**

```
Support=None        -> S bit = 0
Support=Weak        -> S bit = 1
Support=Moderate    -> S bit = 1
Support=Strong      -> S bit = 1
Support=VeryStrong  -> S bit = 1

(S bit, grade) combinations: 10 constructible, 5 possible
impossible: (0,Weak) (0,Moderate) (0,Strong) (0,VeryStrong) (1,None)
```

`S = (Support > None)` is total and deterministic, so **the binary is a quotient of the graded
scale**. Orthogonality requires the coordinates to vary freely; here **half the combinations are
impossible**. The relation is **nesting**, not independence — Σ₀ is the *coarsening*, not a peer.

*(This also means the two are not in contradiction in the way "graded vs binary" suggests: one is a
refinement of the other. The real question is which refinement level Σ needs.)*

---

## 2. TEST — does any mandatory operation need the grades?

Over 272B's own `O_sem` (17 operations):

| Needs grades | Why |
|---|---|
| **`Qualify`** | decides whether an observation *counts* as evidence — reads a bar |
| **`Assess`** | produces a verdict against a policy bar — reads quantity/quality |
| **`Compare`** | *ranks* two propositions — needs an order the binary cannot give |

The other 14 do not. `Merge` in particular does not: 272B's own law is OR over **polarity**, and
grades play no part in it.

---

## 3. TEST — must the grade live *in* Σ? **No, and this is decisive**

Apply the argument that settled D-4: Σ is **derived** from the evidence set. Strength is a function of
the **same** set:

```
S(A)        = ∃x ∈ e : polarity(x) = support
R(A)        = ∃x ∈ e : polarity(x) = refute
strength(A) = f(e, policy)
```

`Qualify`, `Assess` and `Compare` all read `e` **and a policy**. None needs a grade pre-stored on Σ.

**The decisive asymmetry:**

> **Strength is policy-relative. `(S,R)` is policy-invariant.**
>
> Two supporting items: `bar=2` → *Moderate*; `bar=3` → *Weak*. Same evidence, different policy,
> different grade.
> The **polarity** of that evidence does not move when the bar moves.

A component whose value changes with policy cannot sit inside a policy-independent Σ without making Σ
policy-relative — and **§271.3 already boxes `Policy ∉ Identity(K)`**.

This is the same structural argument that produced the D-4 answer, applied one level down. It is
consistent, and it is the strongest ground in this document.

---

## 4. TEST — is the five-level scale even well-typed?

Exhaustive search over portfolios of size 1–3 and thresholds 1–7, across three **order-preserving**
(hence admissible) encodings:

```
flipping (portfolio, threshold) pairs found: 176

  portfolio ['Weak'], rule mean >= 2:
      A  1,2,3,4    mean=1.000  PROCEED=False
      B  1,2,3,10   mean=1.000  PROCEED=False
      C  5,6,7,8    mean=5.000  PROCEED=True
```

**176 decision rules over this scale flip under an admissible re-encoding.** The scale is at most
**ordinal**, so any average/threshold/percentage rule over it is meaningless in Roberts' sense
(first-order MT-3, here established exhaustively rather than by a hand-picked case).

**Stated honestly:** not every rule flips. `min`, `max` and `median` are invariant, and many
(portfolio, threshold) pairs agree across encodings. The finding is that **meaningfulness must be
checked per rule** — not that every numeric use is wrong.

**And MT-5 still stands:** no empirical relational structure was ever given for this scale, so even
its **ordinality is assumed rather than established**.

---

## 5. What this does *not* settle

Two things, and both are real:

1. **The cardinality is unjustified.** Nothing in the corpus fixes *five* levels.
   `Qualify`/`Assess`/`Compare` need an **order**, not five names. The five levels are an
   **inheritance from Q14** that has survived four reductions without ever being derived.
   *Recommended status: `INHERITED, NOT DERIVED`.*
2. **`Compare` may not be mandatory at all.** It appears in `O_sem` **with no definition** — and it is
   the only operation whose need for grades rests on ranking. If `Compare` is dropped or defined
   without ranking, the case for grades narrows to `Qualify`/`Assess`, both of which read the bar
   directly and need no grade at all.

**So the residual open question is sharper than "graded or binary":**

> **Does any mandatory operation need an ORDER over evidence strength that it cannot compute from the
> evidence set and the policy at the point of use?**

If no — strength need not be a stored quantity anywhere, only a computed one.

---

## 6. Consequence for the register

| Item | Status |
|---|---|
| **Step 275 vs Step 272B** | **RESOLVED — not a contradiction.** Different questions; the disagreement dissolves under the Σ/Assessment separation both documents already require |
| `Σ = P({Support, Refute})` | **stands** as the policy-invariant epistemic core |
| Five-level strength | **moves to Assessment** — policy-relative, derived, `INHERITED NOT DERIVED` cardinality |
| `Σ ⊥ Strength` (reviewer's hypothesis) | **REFUTED** — nesting, not orthogonality |
| **NEW G-61** | the cardinality of the strength scale has never been derived — it has survived four reductions unexamined |
| **NEW G-62** | `Compare` is in `O_sem` with no definition, and it is the sole operation whose grade-requirement rests on ranking |

**Neither 275 nor 272B needs to be withdrawn.** Step 275 was right that support magnitude is
epistemically real and must be tested; Step 272B was right that it is not part of the minimal Σ.
**Both were answering the question they posed, and neither posed the other's.**

---

## 7. A note on how this was resolved

Worth recording because it bears on the estate's method: **this contradiction was resolvable from
material already in the corpus** — §271.3 (`Policy ∉ Identity(K)`), §271.10–11
(`Assessment ≠ Truth`), 272A.7 (`Σ ≠ governance state`), and the first-order measurement work.

No new theory was required. **The two documents contradicted each other because neither cited the
other**, not because the corpus lacked the means to separate them. That is the transcription pattern
again — the sixth instance — and it is the argument for the carry-forward register recommended in
`04` §6.
