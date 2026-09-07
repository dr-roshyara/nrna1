# `KOS-T-0004` — `Qualify`

**`[EXP]` extraction record · stress case: *an operation/process*.**
**Adjudicates nothing.**

| | |
|---|---|
| **Category** | 🔴 **NO FITTING CATEGORY** — an **operation**. Provisionally `Concepts` **under protest** |
| **Status** | `[UN]` · `status_chain: candidate` · Grounding **architecturally-grounded** (the code is exercised) |

## Definitions — four **signatures with four different codomains**

| ID | signature | codomain | implementation |
|---|---|---|---|
| **`D-01`** | `Qualify : Observation × Policy ⇀ Evidence` | **`Evidence`** | ⭐ **`result-produced-on-it`** ×4 |
| **`D-02`** | `Qualify(x) → {Qualified, Unqualified, Undetermined, **Terminus**}` | a **4-valued verdict** | `none` |
| **`D-03`** | `Evidence × Proposition × Context × Provenance × Discrimination → EpistemicStatus` | **`EpistemicStatus`** | `none` |
| **`D-04`** | `Φ : K̂_t → {Knowledge, Not-Knowledge}` — the *Knowledge Qualification Problem* | a **partition** | `none` |

⚠️ **These are not four readings of one function.** `D-01` is a **constructor**, `D-02`/`D-04` are
**classifiers**, `D-03` is an **evaluator**. **Four different arrows.**

## 🔴🔴 The headline stress result — `G1` says *"no body"*; the code has four bodies

Three lanes independently record **`G1`: `Qualify` — named, typed, NO BODY**. Measured:

```
step-280/exec/kosmodel.py:40   def Qualify(obs, policy_params)
step-281/exec/kosmodel.py:40   def Qualify(obs, policy_params)
step-282/exec/kosmodel.py:40   def Qualify(obs, policy_params)
research/knowledgeos-sim/kos/transitions.py:26   def Qualify(E, oid, policy)
```

**And the body is substantive** — resolves `source_of`, checks `trusted_sources`, returns
`(None,"UnqualifiedSource")` or `Evidence(...)`.

$$\boxed{\begin{array}{c}\textbf{Both claims are true, and the code marks WHY: } \texttt{\# Qualification (279/278: policy-parametric)}\\ \textbf{Its behaviour is supplied by } policy\_params \textbf{ — an INPUT, not a theory.}\\ \boxed{\textbf{There is an IMPLEMENTED body and no DERIVED body.}}\end{array}}$$

⚠️ **`G1` must never be cited as *"no code exists"*, and the code must never be cited as *"`G1` is
closed"*.** The hard rule *implementation is not architectural truth* runs **both ways**: code does
not ratify a theory, **and code does not refute a theory gap.**

## `Latest` · dependencies · open questions

**mention** 2026-09-06 · **implementation** 2026-08-31 · **refinement** `D-02`'s `Terminus` codomain
(Ch 16 lens) · **governed decision** 🔴 **`never`** *(applicable · searched `governance/` · no instance found)*.
**Depends on:** `Observation` · `Policy` (**the only ratified construct**, `GN-19`) · `Evidence`
(`TG-08`: no identity) · `Σ`.
**Open:** ⭐ **which codomain — because the choice is PRIOR to the derivation.** *"Derive `Qualify`"* is
**not yet a well-posed task.** · Is `D-04`'s `Φ` this element or a different one? *(its glyph collides
with `Φ : Π_t → K_t` — routed to `H1`.)*

---

# Schema v2 revalidation — **appended 2026-09-07, v1 text above unchanged**

`kind:` **`operation`** · `Category` (v1) `Concepts` **under protest — protest preserved**.

## Dispositions (Defect C) — **4 → 3**

| disposition | occurrence |
|---|---|
| **1 · definitions** | `D-01`, `D-02`, `D-03` |
| **3 · ambiguous identity** | **`D-04` `Φ : K̂_t → {Knowledge, Not-Knowledge}`** — **a different glyph**, and `Φ` collides with `Φ : Π_t → K_t`; routed to `H1` *(reclassified `R-5`)* |

## Implementation (Defect A) — ⭐ **the case that motivated the defect**

`D-01` — `result-produced-on-it` × **`stipulated`**, on the code's own marking:
`# ---- Qualification (279/278: policy-parametric) ----` — **behaviour supplied by `policy_params`, an
input, not a theory.** `selection: stipulated` (one of four codomains, no act).

$$\boxed{\textbf{IMPLEMENTED body · no DERIVED body. } G1 \textbf{ stands.}}$$

## ⭐ Axis-independence proof (Defect B)

`kind = operation` · `status = [UN]` · `implementation = result-produced-on-it / stipulated` ·
`grounding = architecturally-grounded`. **A single collapsed field would have read *"grounded and
implemented, therefore settled"* — which is false.**
