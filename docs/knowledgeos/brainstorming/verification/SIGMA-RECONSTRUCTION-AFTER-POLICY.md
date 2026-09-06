---
artifact: 7 · SIGMA-RECONSTRUCTION-AFTER-POLICY
mandate: 20260830_1931 §10
date: 2026-08-30
status: **RESOLVED — and the corpus proved my own Σ defect two days before I found it**
---

# Σ — Reconstruction After Policy

## 0. The find that settles it

The corpus's **executable** reference (`ladder_dc_reference.py`, 2026-08-28, contamination-free) reports:

```
Expressibility probe — Omega_A = (Support, Acceptance, Commitment, Contest)
  target: Acceptance=Accepted AND Contest=Active           (008 §11, board case)
  options: stay 'Accepted' (contest invisible) or move to 'CONFLICTED' (acceptance suspended)
  NO single state carries both.   -> PF-6 residue CONFIRMED
```

> **This is EXACTLY the defect I found in the three-state Σ — no image on the conflicted region.**
> The corpus reached it **two days earlier, by a different route, and EXECUTED it.**
> **Two independent derivations, one conclusion: a single epistemic value is insufficient.**

## 1. Σ ⊥ Γ — now proven three independent ways

| Route | Evidence | Independent of the others? |
|---|---|---|
| **1 · Derivation** | four meaningful quadrants constructed | verifier work |
| **2 · Running config** | `authorities.yaml`: *"INDEPENDENT of `status`"* + worked cross-quadrant example, both enums linted | **YES** |
| **3 · Corpus experiment** | **EXECUTED: `10^6 evidence, no authority act → not committed`; one authority act → committed** | **YES** |

> **Route 3 is the strongest form of the claim available: unbounded evidence cannot substitute for a single
> authority act.** The two inputs are not merely different — **they are non-substitutable at any
> magnitude.** That is orthogonality demonstrated, not asserted.

## 2. Where does "contest" belong? — five candidates, tested

| Candidate | Verdict |
|---|---|
| a value of `Σ` | **REFUTED** — PF-6: it destroys the acceptance value it must coexist with |
| a second `Σ` dimension | viable, but contest is **not evidence-derived** — wrong axis |
| a relation in `ℛ` | **VIABLE** |
| a derived predicate over `e` | **REFUTED as the general case** — see the discriminator |
| a governance state in `Γ` | **VIABLE** |

**The discriminator, executed as a construction:**
> *Can a contest exist with NO contradicting evidence?*
> **YES** — a board member contests an accepted claim on **procedural** grounds, offering no counter-
> evidence. Then `conflictsWith(e) = False` while `contested = True`.

> **Therefore contest is NOT evidence-derived. It is a GOVERNANCE ACT, like Committed.**
> This also **separates two things the corpus calls by one name**: *self-conflict* (an assertion's own
> evidence points both ways — derivable from `e`) and *third-party contest* (someone objects — an act).

## 3. The derived Σ

```
Σ : Assertion → (dir, str)                    EPISTEMIC — via Assessment(·, Policy)
      dir ∈ {Refuting, Neutral, Supporting}
      str ∈ ORDINAL {None, Weak, Moderate, Strong, VeryStrong}

Γ : Assertion × GovCtx → GovState              GOVERNANCE — via authority acts
      GovState ⊇ {Uncommitted, Committed, Rejected, Contested, Superseded, Retired}

conflictsWith(a)   derived from e              self-conflict
contests(b,a) ∈ ℛ                              third-party contest — a governance act
```

**PF-6 dissolves:** `Accepted AND Contest:Active` = `( Σ=(Supporting,Strong), Γ=Contested )`.
**Two coordinates, both retained, nothing lost.**

**Coverage of the corpus's own five states — verified by enumeration:**

| Corpus state | Σ | Γ |
|---|---|---|
| Candidate | `(Neutral,None)` / `(Supporting,Weak)` | Uncommitted |
| Supported | `(Supporting,Moderate+)` | Uncommitted |
| Accepted | `(Supporting,Strong+)` | Committed |
| REJECTED | any | Rejected |
| CONFLICTED | `(Supporting,·)` with `conflictsWith=True` | any |
| **PF-6 residue** | `(Supporting,Strong)` | **Contested** |

> **The pair represents all five corpus states AND the residue the five-state model cannot express.
> STRICTLY MORE EXPRESSIVE, and by exactly one case — the one the corpus itself identified as lost.**

## 4. §10's question answered directly

> *Is `EpistemicStrength ≠ GovernanceStatus` one dimension, two orthogonal dimensions, derived values, or
> context-specific assessments?*

**TWO ORTHOGONAL DIMENSIONS, and BOTH ARE DERIVED — but from different sources:**
- `Σ` is derived from **evidence**, via `Assessment(·, Policy)` — **now computable, because Policy is
  reconstructed.**
- `Γ` is derived from **authority acts**, via governance — not from evidence at any magnitude (route 3).

**Neither is context-specific in the sense of varying by observer; both are context-*scoped* through the
assertion's `c`.**

## 5. Why this could not have been derived before Policy

`Σ = codomain of Assessment`, and `Assessment` takes `Policy`. **While `Policy` was undefined, `Σ`'s
codomain was undetermined — not unknown, undetermined.** The executed proof:

```
Assess(A2, policy="default")           = (Supporting, Weak)
Assess(A2, policy="strict-provenance") = (Neutral,    None)
```
> **Same evidence, same assertion, different `Σ`.** `Σ` is not a property of the assertion — **it is a
> property of the assertion UNDER A POLICY.** The mandate's ordering — Policy before Σ — was correct, and
> this is the evidence that it was.

## 6. Classification

| Claim | Class |
|---|---|
| PF-6: no single state carries Accepted+Contested | **CORPUS ESTABLISHES + EMPIRICALLY VERIFIED** — executed, independent |
| `Σ ⊥ Γ` | **PROVEN — three independent routes** |
| contest is a governance act, not evidence-derived | **FORMALLY DERIVED** — constructed discriminator |
| self-conflict ≠ third-party contest | **FORMALLY DERIVED** |
| `Σ = (dir, str)`, `str` ORDINAL | **FORMALLY DERIVED** |
| **no arithmetic on `str`** | **PROVEN** — no interval scale exists; and 42.10's no-averaging law agrees |
| the pair covers all 5 corpus states + the residue | **FORMALLY DERIVED** — enumerated |
| `Σ` is a property of an assertion **under a policy** | **EMPIRICALLY VERIFIED** — executed |
