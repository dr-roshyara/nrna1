# HPA SUPERVISORY REVIEW: KR-HILBERT-2026-09 — COMPLETE ASSESSMENT

**Reviewer:** Senior Mathematician · Senior Statistician · Senior DDD Architect
**Date:** 2026-09-02
**Status:** ACCEPTED — METHODOLOGICAL BREAKTHROUGH
**Authority:** HPA Supervisory

---

## Executive Summary

**KR-HILBERT is ACCEPTED as a valid and important experimental result.** It is the cleanest possible verdict on the Hilbert space proposal:

> **Hilbert space supplies a well-defined joint object and a vocabulary for the gap — and otherwise reproduces the existing obstructions in new coordinates rather than removing them.**

The experiment is methodologically exemplary: it did not try to make Hilbert space "solve" KnowledgeOS. It tested whether the Hilbert framework survives KnowledgeOS's constraints. It did not.

**The most important finding:**

$$
\boxed{
\text{The two natural routes bracket the truth from opposite sides.}
}
$$

| Route | at ρ=0.5 (measured 200.6) | Direction |
|:---|:---|:---|
| Pairwise δ-packing (FR-001) | 1,000 | Over by 5× |
| Spectral participation ratio | 4.0 | Under by 50× |

**The measured family-level burden lies between them and is captured by neither.**

---

## Part 1: What Is Established

### 1.1 The Seven Refutations

| Hypothesis | Result | Why |
|:---|:---|:---|
| **H1** — K_t as vector | **[NEG] REFUTED** | Unknown, Absent, NotAssessed, Contradiction collapse onto zero vector |
| **H3** — Orthogonality = Independence | **[NEG] REFUTED** | Witness: X~U{-1,0,1}, Y=1[X=0]; orthogonal but dependent |
| **H6** — Amplitude = Probability = Truth | **[NEG] REFUTED** | Truth is not in the domain of the representation |
| **H7** — Spectral functional = N_eff | **[NEG] REFUTED** | All four fail on equicorrelation |
| **H8** — Hilbert distance = Semantic equivalence | **[NEG] REFUTED** | Transitivity fails (sorites construction) |
| **H9** — Basis invariance | **PARTIALLY CONFIRMED** | Rotation-invariant; not rescaling-invariant |
| **H12** — Genuine reduction | **[NEG] REFUTED** | No reduction on any question tested |

### 1.2 The Four Spectral Functionals

| Functional | Block Structure | Equicorrelation | Error |
|:---|:---|:---|:---|
| Participation Ratio | Exact (200→200, 50→50) | Fails (4.0 vs 200.6) | 1.359 |
| Exp(Spectral Entropy) | Exact | Fails (63.0 vs 200.6) | 0.497 |
| Trace/λ_max | Exact | Fails (2.0 vs 200.6) | 1.797 |
| Rank | Exact | Fails (1000 vs 200.6) | 1.728 |

### 1.3 The Strengthening of FR-001

FR-001 froze the boundary that the **pairwise route** cannot carry family-level complexity. KR-HILBERT adds:

> **Nor can the natural spectral route.**

The frozen entry is untouched and its residual question is sharper for it:

$$
\boxed{
\text{What is the minimal joint structure required to represent family-level epistemic dependence?}
}
$$

— now with the added constraint that it is **neither a pairwise construction nor a standard spectral functional.**

---

## Part 2: What Is Corrected

### 2.1 The Collapse Problem

**What was discovered:**

The inner-product structure collapses:
- `Absent`, `Unknown`, `NotAssessed`, `Contradiction`
- All onto the **zero vector**

**The implication:**

$$
\boxed{
v + (-v) = 0
}
$$

A contradiction is the **same vector** as absence. This is the same 9→U collapse from Experiment G, in new coordinates.

### 2.2 The Orthogonality Problem

**What was discovered:**

$$
\text{Cov}(X,Y) = 0 \not\Rightarrow X \perp Y
$$

Witness: `X ~ Uniform{-1, 0, 1}`, `Y = 1[X = 0]`. They are orthogonal but dependent.

**The implication:**

Orthogonality is a **second-moment** property. Independence is a property of the **whole joint distribution**. They coincide only in the jointly-Gaussian case.

### 2.3 The Factivity Problem

**What was discovered:**

Two worlds with different truth produce the same epistemic state → the same vector → the same amplitudes.

**The implication:**

$$
\boxed{
\text{Truth is not in the domain of the representation.}
}
$$

A change of representation does not change the domain.

---

## Part 3: The Deepest Finding

### 3.1 The Two Routes Bracket the Truth

The experiment discovered that the two natural routes bracket the truth from opposite sides:

| Route | Direction | Worst Case |
|:---|:---|:---|
| Pairwise δ-packing | Over by 5× | 222× at ρ=0.95 |
| Spectral participation ratio | Under by 50× | 50× |

**Why they differ:**

- The participation ratio measures the effective **dimension of the covariance** — how many directions carry variance.
- The multiplicity burden measures the effective number of **independent extreme-value draws** — a property of the *tail of the maximum*.

These are different functionals of the same spectrum.

### 3.2 What Hilbert Space Can and Cannot Do

| Can | Cannot |
|:---|:---|
| Block/duplicate structure — exactly | Equicorrelation's contribution to multiplicity burden |
| A well-defined joint object: the spectrum | The tail behaviour that governs the burden |
| Rotation-invariant summaries | Invariance under per-candidate rescaling |
| A similarity geometry | A semantic equivalence relation (transitivity fails) |
| Second-moment dependence | Probabilistic independence |
| A probability measure via ‖ψ‖² | Truth, factivity, or the Γ domain problem |
| — | Unknown / Absent / NotAssessed / Contradiction as distinct states |

---

## Part 4: The Supervisory Verdict

### 4.1 Status

| Element | Status |
|:---|:---|
| KR-HILBERT experiment | **[EXP] COMPLETED** |
| H1 refutation | **[NEG] ESTABLISHED** |
| H3 refutation | **[NEG] ESTABLISHED** |
| H6 refutation | **[NEG] ESTABLISHED** |
| H7 refutation | **[NEG] ESTABLISHED** |
| H8 refutation | **[NEG] ESTABLISHED** |
| H9 partial confirmation | **[PROP] CONDITIONAL** |
| H12 refutation | **[NEG] ESTABLISHED** |
| FR-001 strengthening | **[EXP] CONFIRMED** |
| New frozen-result candidate | **[PROP] — not proposed** |

### 4.2 The Final Statement

The Hilbert space proposal has been tested and found **partially confirmed in the weakest sense**. It supplies:

1. **A well-defined joint object** — the spectrum
2. **A vocabulary for the gap** — what is missing

But on every substantive KnowledgeOS question tested:

$$
\boxed{
\text{It reproduces the existing obstruction in new coordinates rather than removing it.}
}
$$

### 4.3 The New Research Question

The experiment yields a **well-posed next question**:

> **Which functional of the spectrum governs the tail of the maximum?**

This is a well-posed question in extreme-value theory. It is **not** a formula hunt of the kind FR-001 closed — it names the object sought and the property it must have.

**Queue unchanged:**

$$
\boxed{
\text{Factivity} \rightarrow \text{Contr} \rightarrow \succeq
}
$$

---

## Part 5: HPA Ruling

### 5.1 Ruling

```
KR-HILBERT is ACCEPTED.
FR-001 is STRENGTHENED, not reopened.
Theory v1.2 remains UNCHANGED.
Kernel status: NOT SELECTABLE.
```

### 5.2 What Is Adopted

1. **The Hilbert framework is a vocabulary, not a solution** — it supplies a joint object and a vocabulary for the gap
2. **The two routes bracket the truth from opposite sides** — pairwise over, spectral under
3. **The residual question is better posed** — neither construction captures the burden
4. **FR-001 is strengthened** — a second failed route from the opposite direction
5. **The queue is unchanged** — Factivity → Contr → ⪰

---

**HPA Supervisory Ruling**
**Date: 2026-09-02**
**Status: ACCEPTED**
**Next: Factivity → Contr → ⪰**

---

*END OF REVIEW*