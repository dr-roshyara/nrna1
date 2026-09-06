# T — Hilbert-Space Representation of KnowledgeOS · `KR-HILBERT-2026-09`

**Protocol** authored externally (`…/20260902-142944_kr-hilbert-…md`, 1 296 lines). Executor: this lane.
**Baseline** v1.2, unchanged · **No v1.3** · **Status** `[EXP]`.

> **Governing principle (§0):** *do not ask Hilbert space to explain KnowledgeOS; ask Hilbert space to
> **survive** KnowledgeOS.* **FR-001 is FROZEN** and is a **test constraint**, not a target to be
> replaced. Protocol §4 forbids collapsing *participation ratio* into *effective hypothesis
> complexity* — that distinction turned out to be the crux.

## Overall result

> **PARTIALLY CONFIRMED — and only in the weakest sense.** Hilbert space supplies a **well-defined
> joint object** (the spectrum) and a **vocabulary for stating what is missing**. On every substantive
> KnowledgeOS question tested, it **reproduces the existing obstruction in new coordinates rather than
> removing it.**

---

# 1. H7 — the crux: does a spectral functional represent effective complexity? `[NEG]`

Tested against the **measured** `N_eff` from `KR-NEFF` (evaluator-side ground truth here):

| space | **measured** | participation ratio | exp(spectral entropy) | trace/λ_max | rank |
|---|---|---|---|---|---|
| independent n=1000 | 940.6 | 1000.0 | 1000.0 | 1000.0 | 1000.0 |
| **grouped g=200** | **201.9** | **200.0** | **200.0** | **200.0** | **200.0** |
| **grouped g=50** | **51.1** | **50.0** | **50.0** | **50.0** | **50.0** |
| equicorr ρ=0.1 | 751.8 | 91.0 | 690.2 | 9.9 | 1000.0 |
| **equicorr ρ=0.5** | **200.6** | **4.0** | 63.0 | 2.0 | 1000.0 |
| **equicorr ρ=0.9** | **9.0** | **1.2** | 2.8 | 1.1 | 1000.0 |
| equicorr ρ=0.95 | 4.5 | 1.1 | 1.7 | 1.1 | 1000.0 |

| functional | mean \|log ratio\| error |
|---|---|
| exp(spectral entropy) | 0.497 |
| participation ratio | 1.359 |
| rank | 1.728 |
| trace/λ_max | 1.797 |

**All four are exact on block structure** (200→200, 50→50) and **all four fail on equicorrelation.**

## 1.1 The finding that matters: the two routes **bracket** the truth from opposite sides `[EXP]`

| route | at ρ=0.5, measured **200.6** | direction | worst case |
|---|---|---|---|
| **pairwise** — δ-packing (`FR-001`) | **1 000** | **over** by 5× | **222×** at ρ=0.95 |
| **spectral** — participation ratio (here) | **4.0** | **under** by 50× | 50× |

> `[EXP]` **The measured family-level burden is captured by neither construction**, and the two err
> in **opposite directions**.

> ### CORRECTED — do not freeze "the answer lies between them"
> An earlier wording said the burden *"lies BETWEEN"* the two constructions. **That is not a
> mathematical result and must not be frozen as one.** "Between" presupposes a formally defined
> ordering on the space of candidate functionals **and** a proof that the sought functional lies in
> that interval. Neither exists. What is supported is the weaker, precise statement:
>
> **`[EXP]` Under the tested correlation-family regimes, the measured effective multiplicity burden
> is not represented by pairwise δ-packing, and is not represented by the tested standard spectral
> functionals (participation ratio, spectral entropy, trace/λ_max, rank).**
>
> The directional observation — pairwise **overestimates**, spectral **underestimates**
> (`1000 > 200.6 > 4.0` at ρ=0.5) — is a **structural boundary worth recording**, not an interval
> claim.

**Why they differ.** The participation ratio measures the effective **dimension of the covariance** —
how many directions carry variance. The multiplicity burden measures the effective number of
**independent extreme-value draws** — a property of the *tail of the maximum*. These are different
functionals of the same spectrum, and protocol §4 was right to forbid their identification.

**This strengthens `FR-001` rather than replacing it.** FR-001 froze the boundary that the *pairwise*
route cannot carry family-level complexity. `KR-HILBERT` now adds: **nor can the natural spectral
route.** The frozen entry is untouched and its residual question is sharper for it.

---

# 2. H8 — Hilbert distance as semantic equivalence `[NEG] REFUTED`

Twelve vectors on a line at spacing `0.9 ε`:

| | |
|---|---|
| adjacent pairs within ε | **true** |
| endpoints beyond ε | **true** |
| **transitive** | **false** |

> **Hilbert geometry does not repair the transitivity failure — it reproduces it.** ε-distance is a
> **tolerance relation**, exactly as `~_Λ` was in `FR-001`. **A metric does not become an equivalence
> relation by being a metric.**

This is the same sorites construction, in a metric space instead of an evidence regime. `≡_sem`
gains nothing from the geometry.

---

# 3. H3 — orthogonality as epistemic independence `[NEG] REFUTED as stated`

Witness: `X ~ Uniform{−1,0,1}`, `Y = 1[X = 0]`.

```
Cov(X,Y) = 0      →  orthogonal
P(Y=1 | X=0) = 1  ≠  P(Y=1) = 1/3      →  NOT independent
```

`Y` is a **deterministic function of** `X`, and they are orthogonal.

> Orthogonality is a **second-moment** property; independence is a property of the **whole joint
> distribution**. They coincide only in the jointly-Gaussian case — and **assuming joint Gaussianity
> is a modelling commitment, not a free choice.**

**Verdict:** REFUTED as stated; **CONDITIONALLY VALID** under an explicit Gaussian restriction.

---

# 4. H1 — can a vector `K_t` carry the required distinctions? `[NEG] REFUTED as stated`

The protocol's own §2 constraint list contains four distinctions that are all **about absence**:

| required distinction | what the geometry does |
|---|---|
| `Unknown ≠ Absent` | the zero vector is the natural *absent*; *unknown* has no distinct canonical vector |
| `Unobserved ≠ Unobservable` | the geometry sees only a missing component; the **reason** is not a geometric property |
| `NotAssessed ≠ Unknown` | same collapse |
| **`Contradiction ≠ Unknown`** | **`v + (−v) = 0`** — a contradiction is the **same vector** as absence |

> **The inner-product structure collapses Absent, Unknown, NotAssessed and Contradiction onto the
> zero vector.** Distinguishing them requires labels carried **alongside** the vector — i.e. outside
> the Hilbert structure.

**This is the same collapse measured in `KR-SIM-…-G`** (nine evaluation situations → the single value
`U`), re-expressed in a new coordinate system. The structures-first diagnosis applies unchanged:
a representation was chosen and then asked to carry what it projects away.

---

# 5. H6, H9, H12

| | result |
|---|---|
| **H6** amplitude ≠ probability ≠ truth | **does not repair factivity.** Two worlds with different truth produce the same epistemic state → the same vector → the same amplitudes. **Truth is not in the domain of the representation**, and a change of representation does not change the domain |
| **H9** basis invariance | **PARTIALLY CONFIRMED.** Spectral functionals are functions of the eigenvalues, hence invariant under orthogonal change of basis — but **not** under per-candidate rescaling, which KnowledgeOS admits as a representation change |
| **H12** genuine reduction, or renaming? | **NOT a reduction on any question tested.** Effective complexity **no** · semantic equivalence **no** · independence **no** · factivity **no**. What it *does* give: a well-defined joint object and a vocabulary for the gap |

---

# 6. What Hilbert space **can** and **cannot** represent

| **can** | **cannot** |
|---|---|
| block/duplicate structure — exactly (200→200, 50→50) | equicorrelation's contribution to multiplicity burden (up to 50× under) |
| a well-defined **joint** object: the spectrum | the tail behaviour that governs the burden |
| rotation-invariant summaries | invariance under per-candidate rescaling |
| a similarity **geometry** | a semantic **equivalence relation** (transitivity fails) |
| second-moment dependence | probabilistic independence |
| a probability measure via `\|ψ\|²` | truth, factivity, or the `Γ` domain problem |
| — | `Unknown` / `Absent` / `NotAssessed` / `Contradiction` as distinct states |

---

# 7. Consequences

## 7.1 `FR-001` compatibility — **strengthened, not reopened**

`FR-001` remains frozen and untouched. This experiment adds a **second** failed route to the same
residual question, from the opposite direction. That makes the residual question better posed:

> **What is the minimal joint structure required to represent family-level epistemic dependence?**
> — now with the added constraint that **it is neither a pairwise construction nor a standard
> spectral functional.**

## 7.2 Kernel consequence

Consistent with standing consequence **C-1**: a Hilbert representation is a **mathematical
mechanism**, and nothing here makes it an irreducible kernel power. **Kernel status unchanged: NOT
SELECTABLE.**

## 7.3 Theory consequence

**None.** No amendment proposed. Theory v1.2 unchanged.

## 7.4 New frozen-result candidate

`[PROP]` — **not proposed for freezing here.** The freezable statement is the **narrow empirical
one**, and only **after independent replication**:

> **`[EXP]` Under the tested correlation-family regimes, the measured effective multiplicity burden
> is not represented by pairwise δ-packing and is not represented by the tested standard spectral
> functionals (participation ratio, spectral entropy, trace/λ_max, rank).**

**Not** the stronger sentence about lying *between* them — see the correction in §1.1.

## 7.5 Next research question

**Sharpened in review — and the sharpening is more fundamental than my phrasing.** I proposed
*"which functional of the spectrum governs the tail of the maximum?"*, which already presumes the
spectrum suffices. The prior question is:

> **What information about the joint dependence structure determines `P(M_n ≤ x)`, where
> `M_n = max_i Z_i`?**

**Decisive first check — it could yield a stronger result than any formula:**

> **Do two covariance structures exist with the SAME spectrum but different extreme-value
> behaviour?** If yes, **the eigenvalue spectrum alone cannot determine the multiplicity burden**,
> and H7's failure is not a failure of the *chosen functionals* but of the *entire spectral level*.

Three levels, to locate where the needed information enters:

```
pairwise geometry  →  second-order spectrum  →  full joint / tail structure
```

**Recorded as `KR-EXTREME-2026-09` — DESIGNED, NOT RUN.** Per review it must **not** be started ahead
of the queue.

**Queue unchanged:** `Factivity → Contr → ⪰` — and **factivity is now an adjudication, not a
simulation.**
#
