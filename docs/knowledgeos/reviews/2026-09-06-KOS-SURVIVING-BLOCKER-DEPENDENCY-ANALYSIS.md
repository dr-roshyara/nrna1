# KnowledgeOS — **Dependency Structure of the Nine Surviving Blockers**

**Date:** 2026-09-06 · **Analysis only. No new corpus search for definitions. No code. No decision taken.**
**Tests:** `2026-09-06-KOS-IMPLEMENTATION-READINESS-REVERIFICATION.md` §14.

> **Instruction honoured:** *"do not search for more definitions now; prove the dependency structure

> ### ⚠️ **§4 ORDERING CORRECTED 2026-09-06** — `ℐ` is UPSTREAM of `𝒪_core`, not downstream
> Step 277's operation-necessity criterion is stated over `R_mandatory` = `ℐ`, and *"cannot be run
> yet"* because `ℐ` is unestablished — *"a dependency **neither lane has recorded**"*.
> Composing that with `𝒪 → 𝒯 → δ` yields a **cycle on the critical path**.
> See [`…-FIVE-BLOCKER-TRIAGE.md`](2026-09-06-KOS-FIVE-BLOCKER-TRIAGE.md).


> ### ⚠️ **FURTHER AMENDED 2026-09-06** — the closure this rests on is MODEL-RELATIVE
> The `K_min` = {Hold, Transition, Reject, Replay} basis is **stipulated**, not derived
> (`07-MINIMUM-IMPLEMENTABLE` Method paragraph: *"A reference kernel **must be able to**…"*, no
> citation). Its guarantee *"excluded by a dependency fact, not by judgement"* covers **the closure
> of those four, not the four**. And a **rival basis with better standing exists** — `GN-77`:
> *"**9 capabilities are canonically REQUIRED**"*.
> **Consequence: §4's exclusion of `Qualify` from the kernel path holds under `K_min` and is
> UNTESTED under `GN-77`.** See [`…-GAP-KMIN-CAPABILITY-BASIS-UNVALIDATED.md`](2026-09-06-KOS-GAP-KMIN-CAPABILITY-BASIS-UNVALIDATED.md).

> of the nine surviving items."* **No definition search was performed.** Only two already-computed
> dependency artifacts were consulted.

---

## 1. Evidence used — both already executed, neither produced here

| artifact | what it computes |
|---|---|
| `readiness/07-MINIMUM-IMPLEMENTABLE-KNOWLEDGEOS.md` + `exec/minimum_implementable.py` | the **transitive dependency closure** of the four kernel capabilities — *hold state · transition legally · reject illegally · replay*. *"Everything outside the closure is excluded by a **dependency fact**, not by judgement."* |
| `step-289/01-dependency-graph.md` + `exec/t289_bootstrap.py` | **27 nodes · 42 edges**, each definitional or operational. *"Co-mention is **not** an edge — edges were admitted only where a cited passage states that B requires something from A."* |

---

## 2. ⭐ The finding that changes the answer again

### The `kernel` node's complete in-edge set (step-289)

$$\texttt{sufficiency} \cdot \equiv \cdot \texttt{identity} \cdot \mathcal O_K \cdot \mathcal O \cdot \Pi \cdot \texttt{assertion} \cdot \texttt{temporal} \;\longrightarrow\; \texttt{kernel}$$

### `Qualify` has exactly **one** out-edge in the whole graph

$$\texttt{Qualify} \longrightarrow \texttt{projection} \qquad \text{(``}\pi_K\text{ computability blocked by Qualify'', CORPUS 285)}$$

> ### `[EXP]` **There is NO edge `Qualify → kernel`.**

And the closure computation says the same thing independently:

| construct | required by the kernel? | dependency reason |
|---|---|---|
| **Assessment / Qualification / Measurement** | **NO** | *"reachable only downstream of `Evidence`/`Σ`"* |
| Evidence · Σ · Policy | **conditional** | in **iff** `ℐ` carries epistemic invariants |

> ### `[NEG]` **`Qualify` — the corpus's "single irreducible formal blocker" — does NOT block the minimum canonical kernel.**
>
> It blocks **`π_K` computability** (the projection onto `(𝒜,ℛ)`, which is a **view**, not the
> canonical state `K_t`) and the **epistemic pipeline** (Assessment/Qualification, computed *out* of
> the kernel closure). **Both are real. Neither is the kernel.**

**This is not a demotion of `Qualify`.** It is irreducible **for what it gates**. The correction is
to *what* it gates — and my re-verification, having just moved the break onto `Qualify`, put it on
the wrong link.

---

## 3. The dependency graph of the nine survivors

```
                        ┌──────────────────────────────┐
                        │   𝒪_core  — NOT FROZEN       │  ◀── ROOT. no in-edges.
                        └──────────────┬───────────────┘
             ┌─────────────┬───────────┼───────────┬──────────────┐
             ▼             ▼           ▼           ▼              ▼
           𝒯 ──▶ δ       𝒪_K ──▶ ≈    Π         bindings       kernel
                  │        │       ▲   │(258.31: decided by whether a
                  │        └───────┘   │ mandatory op observes provenance)
                  │                    ▼
                  │            ┌───────────────┐
                  │            │  Π ∈ ≡ ?      │ ◀── Decision 3
                  │            └───────┬───────┘
                  ▼                    ▼
            ┌──────────────────────────────────────┐
            │  ≡  ⇄  ≈  ⇄  congruence   (SCC)      │ ⚠️ cycle, flagged N-1
            └──────────────┬───────────────────────┘
                           ▼
                    invariants ℐ ──▶ sufficiency ──▶ kernel
                           │
                           ▼
                    δ  (commit case · 0 postconditions)
                           ▲
                    policy ─┘  (admissibility gates transitions)

  INDEPENDENT OF THE ABOVE:
     Reject ↔ I-12 ↔ Article 8   ──▶ kernel capability "reject illegally"
     K-CANONICAL-DECISION        ──▶ ratifies the (𝒜,ℛ) VIEW relationship
     Qualify                     ──▶ projection π_K · epistemic pipeline
     DECISION-02 (φ)             ──▶ composition rule · Z
     Sat_consistency evaluator   ──▶ downstream of Contr/Σ (conditional-in)
     Acknowledgment              ──▶ no edges: [H], held, not opened
```

---

## 4. Classification of the nine

| # | item | on the kernel's critical path? | classification | evidence |
|---|---|---|---|---|
| **1** | **`𝒪_core` not frozen** | **YES — the ROOT** | 🔴 **BLOCKING** | no in-edges. `𝒪 → 𝒯 → δ` · `𝒪 → 𝒪_K → ≈` · `𝒪 → Π` · `𝒪 → kernel`. `G-01`: *"then everything below it becomes derivable"* |
| **2** | **`Π ∈ ≡ ?`** | **YES** — `Π → ≡ → kernel` | 🟠 **BLOCKING, but see §5** | `CORPUS` 254 · 261.8 |
| **3** | **`ℐ` invariants (0 of 7)** | **YES** — `invariants → sufficiency → kernel` | 🔴 **BLOCKING**, downstream of 1 & 2 | `CORPUS` 273·277·258.30 |
| **4** | **`δ` commit case · 0 postconditions** | **YES** — kernel capability *transition legally* | 🔴 **BLOCKING**, downstream of 1, 2, 3 | `EXECUTED` (`K₁ is K₀`) |
| **5** | **`Reject ↔ I-12 ↔ Article 8`** | **YES** — kernel capability *reject illegally*; `Rejection` is in the required 15 | 🟡 **BLOCKING but INDEPENDENT — actionable today** | handoff: *"independent of act 1 and of each other"* |
| **6** | **`Qualify` body** | **NO** | 🟢 **PARALLEL** — gates `π_K` + the epistemic pipeline | only edge is `Qualify → projection`; closure says Qualification **NO** |
| **7** | **`DECISION-02` / `φ`** | **NO edge to `kernel`** in the 289 graph | 🟢 **PARALLEL** — gates the composition rule and `Z` | `NG-5`; *"well-posed, cheap"* |
| **8** | **`Sat_consistency` evaluator** | **NO** — downstream of `Contr`/`Σ`, both conditional-in | 🟢 **PARALLEL / conditional** | closure: Σ conditional |
| **9** | **`Acknowledgment`** | **NO edges at all** | ⚪ **FUTURE SCOPE, not a blocker** | `[H]`, *held, not opened* |

### Summary

$$\boxed{\textbf{5 blocking · 3 parallel · 1 future scope}}$$

$$\boxed{\textbf{And of the 5, FOUR are downstream of ONE root: } \mathcal O_{core}}$$

---

## 5. ⚠️ Two corpus statements about `Π ∈ ≡?` are in tension — reported, not resolved

| source | says |
|---|---|
| `10-GOVERNANCE-HANDOFF` §2 | act 3 (identity + equality, Decision 3) is *"**independent** of act 1 and of each other. **They can be taken now**"* — i.e. a **free governance act** |
| `step-289` edge `𝒪 → Π` | *"`258.31`: **decided by whether a mandatory op observes provenance**"* — i.e. **derivable once `𝒪` is fixed** |

> `[OPEN]` **If `258.31` holds, `Π ∈ ≡?` is not an independent governance decision at all — it is a
> consequence of freezing `𝒪`.** That would reduce the blocking set from five to four and remove one
> of the two governance decisions in the corpus's own headline.
>
> **I do not resolve this. Both statements are corpus. The tension is the finding.**

---

## 6. A cycle sits on the critical path

$$\equiv \;\rightleftarrows\; \approx \;\rightleftarrows\; \texttt{congruence}$$

`≡ → ≈` (258.8) and `≈ → ≡` (261.5·261.21, *"same formula ⇒ definitional entanglement"*), plus
`congruence → ≡` (*"if it fails, `≡_K` is too coarse"*) and `≡ → congruence` (258.9). Flagged in the
corpus as the **`N-1` conflict**.

`[EXP]` **An SCC on the critical path cannot be resolved by ordering the work.** It is broken either
by `𝒪` fixing the quantifier (`≈` is `∀O ∈ 𝒪_K`) or by a stipulation. **This is a further reason the
root is `𝒪`.**

---

## 7. What this does to the readiness picture

```
Theory ────────────────────────────────── ✅
canonical concepts (K_t) ─────────────── ✅ RATIFIED
formal definitions ───────────────────── ⚠️ Qualify OPEN — but NOT on the kernel path
   ↓
𝒪_core ───────────────────────────────── 🔴 THE ROOT — no in-edges, everything below derivable from it
   ↓
Π ∈ ≡ ?  ·  ≡/≈/congruence SCC ───────── 🟠 possibly derivable from 𝒪 (§5)
   ↓
ℐ invariants ─────────────────────────── 🔴 downstream
   ↓
δ  (commit case, postconditions) ─────── 🔴 downstream
   ↓
contracts · architecture · implementation ⛔

INDEPENDENT, ACTIONABLE NOW:  Reject ↔ I-12 ↔ Article 8
PARALLEL, NOT KERNEL-BLOCKING: Qualify · DECISION-02 · Sat_consistency
FUTURE SCOPE:                  Acknowledgment
```

---

## 8. Answers to the three questions asked

### Which genuinely prevent implementing the canonical kernel?

**Five** — `𝒪_core`, `Π ∈ ≡?`, `ℐ`, `δ`, `Reject`. **Four of the five are downstream of `𝒪_core`;
the fifth (`Reject`) is independent and actionable today.**

### Which are independent parallel work?

**`Qualify`** (gates `π_K` and the epistemic pipeline — real, and not the kernel) ·
**`DECISION-02`** (composition rule, `Z`) · **`Sat_consistency`** (conditional on `Σ` being in `ℐ`).
**All three can proceed in parallel without blocking a kernel build.**

### Which are specification / engineering debt?

**`δ`'s 0 postconditions** is specification debt *once* `𝒪`, `≡` and `ℐ` are fixed — the commit-case
defect is a real defect, but writing pre/postconditions is specification work, not derivation.
**`Acknowledgment`** is future scope. The five EKP items (`G-10`, `G-32`, `G-33`, `G-34`, `G-35`)
remain pure engineering.

---

## 9. Verdict on the verdict

$$\boxed{\textbf{NOT READY stands} \;-\; \textbf{and the blocking set is ONE ROOT plus ONE INDEPENDENT ACT}}$$

| | |
|---|---|
| **The root** | **`𝒪_core` is not frozen.** `G-01` stated the consequence in 2026-08-30: *"**HUMAN DECISION D-1** — then everything below it becomes derivable."* The dependency graph, computed independently in step-289, **agrees**: `𝒪` has no in-edges and reaches `𝒯`, `δ`, `𝒪_K`, `≈`, `Π`, `bindings` and `kernel`. |
| **The independent act** | **`Reject ↔ I-12 ↔ Article 8`** — a kernel capability, and *actionable today*. |
| **Not blocking the kernel** | `Qualify` · `DECISION-02` · `Sat_consistency` · `Acknowledgment` |

### ⚠️ AMENDED 2026-09-06 — recommendation ① is replaced

> **`[NEG]` *"Freeze `𝒪_core`. One act."* is WITHDRAWN.** It conflated **no graph predecessor** with
> **no possible derivation**. The step-289 graph records *already-admitted* dependencies; it proves
> the corpus does not currently derive `𝒪_core`, **not** that `𝒪_core` is intrinsically primitive.
>
> **Replaced by `D-1A` → `D-1B` → `D-1C`:** necessity/minimality proof **first**, canonicalization
> **second**, freeze **third**. See [`2026-09-06-KOS-GAP-OCORE-NECESSITY-PROOF.md`](2026-09-06-KOS-GAP-OCORE-NECESSITY-PROOF.md).
> **Decisive corpus fact:** the existing "six rival minimal registries" result is over
> **reachability**, and *"`Reachability ≠ Epistemic adequacy` ⇒ it is not the `G-67` test."*
> **$\operatorname{Adeq}_K$ against $\mathcal K_{\min}$ has never been computed.**

### `[REC]` The minimum remaining work before kernel implementation is legitimate

1. **Freeze `𝒪_core`.** One act. Four of five blockers become derivable.
2. **Resolve `Reject ↔ I-12 ↔ Article 8`.** Independent; can proceed in parallel with 1.
3. Then, in order: `Π ∈ ≡?` *(possibly derivable — §5)* → `ℐ` → `δ` pre/postconditions.

> **Everything else on the surviving list is real work that does not block a kernel build.**

**Theory v1.2 FROZEN · kernel NOT SELECTED · nothing ratified · no code written · no definition search performed.**
