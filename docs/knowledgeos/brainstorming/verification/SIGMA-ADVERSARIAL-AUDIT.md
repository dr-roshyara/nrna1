---
artifact: B · SIGMA-ADVERSARIAL-AUDIT
mandate: 20260830_1852 §6, §7
date: 2026-08-30
status: **MY OWN THREE-STATE RESULT IS PARTIALLY REFUTED**
evidence: executed ten-case audit · Q14 source read
---

# Σ — Adversarial Audit

## 0. Headline — I am refuting my own result

> **`Σ = {Unknown, Supported, Refuted}` is REFUTED as complete.** It cannot express **degree of support**,
> which the corpus's own type system carries as an ordinal five-level scale.
>
> **And the corpus's `Σ` is equally refuted in the other direction:** Q14's `Σ = (A,S,R,V,C)` has **no
> negative pole** and therefore **cannot express refutation at all**.
>
> **Neither model subsumes the other. Both are incomplete, on different axes.**

## 1. The challenger: Q14's Σ (2026-08-26, pre-Step-001, non-step file)

```
Σ = (A, S, R, V, C)                                       |𝒮| = 7×5×4×4×4 = 2240
  Acquisition A : Observed, Reported, Inferred, Calculated, Assumed, Hypothesized, Unknown
  Support     S : None, Weak, Moderate, Strong, Very Strong
  Resolution  R : Open, In Progress, Resolved, Unresolvable
  Validity    V : Current, Stale, Expired, Unknown
  Conflict    C : None, Potential, Active, Resolved
Σ₁ ⪯ Σ₂  ⟺  Support(Σ₁) ≤ Support(Σ₂) ∧ Resolution(Σ₁) ≤ Resolution(Σ₂)
```

**I did not know this existed when I derived the three-state result.** My `DECISION-SIGMA` paper enumerated
24 *status terms* scattered across the corpus; it never found the **structured five-dimensional vector**
sitting in Q14. **That is a coverage failure of my own scan, caused by the same non-step blind spot I have
been criticising.**

## 2. Decomposition of the challenger — executed

| Dim | What it actually is | Correct home | In Σ? |
|---|---|---|---|
| **A** Acquisition | *how* the claim was obtained | `Π` / evidence-type | **NO** |
| **S** Support | strength of evidential support | **epistemic** | **YES** |
| **R** Resolution | workflow position | lifecycle / `Γ` | **NO** |
| **V** Validity | Current/Stale/Expired | derivable from `t` + now() | **NO** |
| **C** Conflict | None/Potential/Active/Resolved | derivable from `ℛ` + `e` | **NO** |

> **Q14 refutes four of its own five dimensions.** Its §6.4 lattice orders by **Support and Resolution
> only** — `A`, `V` and `C` never enter the partial order. **A component that plays no part in the
> ordering is not carrying epistemic content.**

**My earlier classification is confirmed by an independent source:** `DECISION-SIGMA` §2 ruled that
`Observed` and `Inferred` "differ by EVIDENCE TYPE, not epistemic state." **Q14 places exactly those terms
in `A` — and then excludes `A` from its own lattice.** Two independent derivations, same conclusion.

## 3. The decisive asymmetry

```
Q14's S : None → Weak → Moderate → Strong → Very Strong        NO NEGATIVE POLE
```

> **"Strong evidence that Nexus is NOT 3.69" has no value in `S`.** Refutation is inexpressible.
> **My Σ expresses it. Q14's cannot.**
>
> Conversely, **"weakly supported" vs "very strongly supported" is inexpressible in mine.**

**Both models fail. Neither is a coarsening of the other.**

## 4. The ten mandated cases — executed

| # | Case | 3-state | Q14 | 3-state verdict | Q14 verdict |
|---|---|---|---|---|---|
| 1 | no evidence | Unknown | S=None | OK | OK |
| 2 | supporting evidence | Supported | S=Weak..VStrong | OK | OK |
| 3 | **contradicting evidence** | Refuted | **no value** | OK | **FAILS** |
| 4 | **support AND contradiction** | ambiguous | C=Active | **LOSSY** | OK |
| 5 | evidence withdrawn | →Unknown | S=None | OK | OK |
| 6 | evidence invalidated | →Unknown | S=None | OK | OK |
| 7 | proposition superseded | **not a Σ value** | not in Σ | relation `ℛ` | relation `ℛ` |
| 8 | proposition deprecated | **not a Σ value** | not in Σ | lifecycle | lifecycle |
| 9 | governance rejection | **not a Σ value** | not in Σ | `Γ` | `Γ` |
| 10 | unresolved uncertainty | Unknown (coarse) | R=Unresolvable | **LOSSY** | OK |

**Cases 7–9 agree across both models and across my prior work: supersession, deprecation and governance
rejection are NOT epistemic states.** Three independent derivations, one conclusion. **This is the most
robust result in the whole Σ analysis.**

## 5. §6's key question, answered by execution

> *Can `Σ={Unknown,Supported,Refuted}` represent simultaneously valid support and valid contradiction
> without loss of information?*

```
e = {E1: supports, E2: supports, E3: contradicts}
Σ₃ projection -> AMBIGUOUS: the projection has no image on this region
Conflicted(a,K) := supporting(e) ≠ ∅ ∧ contradicting(e) ≠ ∅  ->  True   [DERIVABLE]
```

**ANSWER — and it splits in two:**

- **As a STORED value: NO.** Information is lost.
- **As a DERIVED PROJECTION over `e`: nothing is lost from `K`,** because `e` remains the source of truth and
  the projection is recomputable. **What fails is TOTALITY** — `Σ₃` has no image on the conflicted region.

> **The defect is not the number of values. It is that a total projection must be defined on the conflicted
> region, and `Σ₃` leaves it undefined.**

**Per the mandate's instruction, I did NOT add `Conflicted` to Σ.** Conflict is correctly carried by `e`
(evidence polarity) and `ℛ`, and `Conflicted(a,K)` is **derivable** — executed. **My earlier finding that
`Conflicted` is derived survives this audit intact.**

## 6. The minimal structure that survives both attacks

Requirements: **(i)** a negative pole · **(ii)** degree · **(iii)** a total image on the conflicted region.

```
Σ = (dir, str)      dir ∈ {Refuting, Neutral, Supporting}
                    str ∈ ORDINAL {None, Weak, Moderate, Strong, VeryStrong}
```
equivalently a **signed ordinal scale**. Conflict remains **derived**, never stored.

**MEASUREMENT-THEORETIC CONSTRAINT (binding):** `str` is **ORDINAL**. No interval structure is established
anywhere in 1468 files. **Therefore no arithmetic on support strength is admissible — no averaging, no
summing, no weighted evidence combination, no "confidence scores".** Only `≤` and order statistics.
**Q14 §6.4 is correct to use only `⪯`. Any corpus passage that averages or sums confidence is inadmissible.**

**Classification: `FORMALLY DERIVED`, not proven.** It survives the cases constructed; it is not shown that
no seventeenth case breaks it.

## 7. Status transition algebra — re-audited adversarially (§7)

Tested `Σ × Event → Σ` on the signed-ordinal model.

| Property | Verdict |
|---|---|
| **total** | **NO** — `withdraw(E)` on `Σ=(Neutral,None)` has no defined predecessor to return to |
| **deterministic** | **YES**, given `e` and Policy |
| **information-preserving** | **NO** — the map to `(dir,str)` is many-to-one by construction |
| **monotonic** | **NO** — withdrawal and invalidation both decrease support |
| **reversible** | **NO** — see below |

**Six transitions, all constructible:** `Unknown→Supported` (add supporting) · `Unknown→Refuted` (add
contradicting) · `Supported→Refuted` (add stronger contradicting) · `Refuted→Supported` (add stronger
supporting) · `Supported→Unknown` (withdraw) · `Refuted→Unknown` (invalidate).

**The executed finding that matters:**

> **`withdraw` and `invalidate` produce the SAME `Σ`, and they are NOT the same event.**
> Withdrawn evidence *may return*; invalidated evidence *may not*. **`Σ` alone cannot distinguish them.**

**What is missing, exactly:** the distinction lives in **`e`'s per-reference state** (`active` /
`withdrawn` / `invalidated`), not in `Σ`. **`e` must therefore be a set of *qualified* references, not a set
of bare strings** — which is precisely step 253's `Evidence = QualifiedObservation`.

> **CONSEQUENCE FOR THE ASSERTION — this changes my derived model:**
> **`e` is not `Set(ref)`. It is `Set(ref × polarity × state)`.**
> Polarity is required by §5 (`supports` vs `contradicts`); state is required by §7 (withdrawn vs
> invalidated). **This is the first correction to `Assertion = (id,P,e,c,t,Π)` produced by adversarial
> audit — the tuple's shape is unchanged, but `e`'s type is refined.**

## 8. Classification

| Claim | Class |
|---|---|
| Q14's `Σ = (A,S,R,V,C)` exists, 2240 states | **CORPUS ESTABLISHES** |
| Four of Q14's five dimensions are not epistemic | **FORMALLY DERIVED** (+ Q14's own lattice) |
| Q14's `S` cannot express refutation | **FORMALLY PROVEN** (no negative pole) |
| `Σ₃` cannot express degree | **FORMALLY PROVEN** |
| **`Σ₃` is REFUTED as complete** | **REFUTED** — my own prior result |
| Cases 7–9 are not epistemic states | **FORMALLY DERIVED** — three independent routes |
| `Conflicted` is derivable, not primitive | **EXECUTED** — survives |
| `Σ = (dir, str)` signed ordinal | **FORMALLY DERIVED** — not proven |
| No arithmetic on support strength | **FORMALLY PROVEN** (no interval scale established) |
| `Σ × Event → Σ` is **not** total, **not** monotonic, **not** reversible | **EXECUTED** — corrects my prior claim |
| `e` must be `Set(ref × polarity × state)` | **FORMALLY DERIVED** |
| `Σ ⊥ Γ` | **EMPIRICALLY SUPPORTED** — `authorities.yaml` states it and a linter enforces it |

**`Σ ⊥ Γ` is the one Σ-result that survives every attack, and it is now the best-evidenced claim in the
programme** — derived, corpus-supported, and enforced by running code.
