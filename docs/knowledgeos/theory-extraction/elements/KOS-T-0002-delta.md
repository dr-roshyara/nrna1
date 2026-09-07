# `KOS-T-0002` — `δ`

**`[EXP]` extraction record · stress case: *function vs measured constant / operational meaning*.**
**Adjudicates nothing. No canonical `δ`.**

| | |
|---|---|
| **Category** | 🔴 **NO FITTING CATEGORY.** `δ` is an **operation**; the 19-category hypothesis has no `Operations`. Provisionally `Concepts` **under protest** — see [`../03-STRESS-TEST-REPORT.md`](../03-STRESS-TEST-REPORT.md) §2 |
| **Status** | `[UN]` · `status_chain: candidate` · Grounding **mixed** |

## Definitions

| ID | definition | source | implementation |
|---|---|---|---|
| **`D-01`** | `K_{t+1} = δ(K_t, e_t)` — **event**-indexed | corpus, widespread | `none` as such |
| **`D-02`** | `δ(K, o) = K'` — **operation**-indexed | corpus | ⭐ **`result-produced-on-it`** |
| **`D-03`** | `δ(K, o) = Reject(r)` — **partial, with a rejection branch** | step 285 ×2 | ⭐ **implemented** — every branch returns `REJECTED(structure): …` |
| **`D-04`** | `δ` = successor-state axioms (Reiter) | step-292 | `none` |

**Implementation, measured:** `def delta(k,op,arg)` in `step-280/281/282/exec/kosmodel.py:107`, plus
`def T(k,o)` in `gap-discovery/exec/kos_kernel.py:136` and `def delta(K,ev)` in
`witnesses/reverify_construct.py:105`. **Three ops: `assert`, `relate`, `retract`; `Replay(H)` folds
it over a history.**

## 🔴 The finding that matters — the "no commit case" claim needs a qualifier

The estate records: *"`δ` has no commit case — `Γ` is derived and not a component of `K`, so `δ` has
nowhere to write. Executed: `K₁ is K₀`."* **And yet `delta` visibly commits:**
`return K(k.A|{arg}, k.R), "ok"`.

$$\boxed{\begin{array}{c}\textbf{Both are true. } \delta \textbf{ commits STRUCTURALLY } (\mathcal A,\mathcal R \text{ change})\\ \textbf{and has nowhere to write an EPISTEMIC commitment (}\Gamma\textbf{).}\\ \textbf{The blocker is about } \Gamma\textbf{, not about } \delta \textbf{ being a no-op.}\end{array}}$$

⚠️ **My own earlier reporting said *"`δ` has no commit case (executed: `K₁ is K₀`)"* without that
qualifier, in `NG-2` and in the forward plan.** The claim is real; **my phrasing let it read as
"`δ` does nothing", which the code refutes.** Corrected here; the underlying `Γ` blocker stands.

## `δ = 0.3099` is **not** a definition of this element

`FR-001`'s resolution constant shares the glyph and is **a different object**.

$$\boxed{\textbf{A homonym is not a definition. } D\text{-}nn \textbf{ enumerates definitions of ONE element.}}$$

**Routed to `H1`** (glyph registry, archaeology). **New schema rule, from this case** — `03` §3.

## `Latest` · dependencies · open questions

**mention** 2026-09-06 · **implementation** 2026-08-31 · **refinement** step-292 (Reiter vocabulary,
2026-09-02) · **governed decision** 🔴 **`never`** *(applicable · searched `governance/` · no instance found)*.
**Depends on:** `K` (`KOS-T-0001`, 10 definitions) · `Γ` (4 meanings) · `Op`/`𝒪` (registry OPEN) ·
`ℐ` (0 of 7 established).
**Open:** event- or operation-indexed? · total or partial? · `δ(K,o₁)=δ(K,o₂) ⇒ o₁=o₂?` marked **TBD**.

---

# Schema v2 revalidation — **appended 2026-09-07, v1 text above unchanged**

`kind:` **`operation`** · `Category` (v1) `Concepts` **under protest — protest preserved**.

## Dispositions (Defect C) — **4 → 3**

| disposition | occurrence |
|---|---|
| **1 · definitions** | `D-01` `δ(K_t,e_t)` · `D-02` `δ(K,o)` · `D-03` `δ(K,o)=Reject(r)` |
| **2 · different candidate** | ⭐ **`δ = 0.3099`** (`FR-001` resolution constant) → **`KOS-T-0005`**, `kind: constant`. ⛔ **never a `D-nn` here** |
| **4 · symbolic occurrence** | `D-04` *successor-state axioms* · *situation calculus* — **names a framework, does not define `δ`** *(reclassified `R-4`)* |
| **residue** | `δ = 0` (4 occurrences) — 🔴 **unclassified**, `[OPEN]` *(defect `H`)* |

## Implementation (Defect A)

`D-02`/`D-03` — `result-produced-on-it` · **`stipulated`** *(the op-set `{assert, relate, retract}` is
chosen; **`𝒪` membership is OPEN**)* · `selection: stipulated`.

**The `Γ` blocker is untouched by this code.** *Stipulated code does not refute a theory gap.*
