# `KR-REP-REDUCTION-2026-09` — RESULTS

> ## Review verdict: **ACCEPTED as a successful first representation-reduction experiment.**
> **Contribution:** *a specified representation chain can be experimentally tested for preservation,
> and a preservation boundary can be located without assuming in advance where it lies.*
> **Wording corrections from review are applied in §2, §3, §4, §5 and §8.**

**Implemented to `KR-REP-REDUCTION-DESIGN-2026-09.md`.** 40 000 cases per split, independent seeds,
**full population persisted**. **`FT-1..FT-7` ran BEFORE any result and all passed.**
**Positive control PASSES** — `N_viol > 0` at three levels, so the experiment was not blind.
**Theory v1.2 unchanged · no v1.3 · kernel NOT SELECTED · nothing adopted.**

---

# 1. The boundary — `H-RR1` / `H-A` CONFIRMED

| level | `A_n` | `F_n` | `Ĥ(Q\|R)` | `N_viol` | adequate |
|---|---|---|---|---|---|
| **`R5`** drop timestamps | 1.00 | **1.0000** | **0.0000** | **0** | **YES** |
| **`R4`** round 3 s.f. | 1.00 | 0.7611 | 0.4299 | 35 532 | no |
| **`R3`** round 2 s.f. | 1.00 | 0.5806 | 0.8546 | 246 235 | no |
| **`R2`** rank | 1.00 | 0.0254 | 3.3937 | 5 530 777 | no |

> ## Last adequate stage: `R5`. First inadequate: `R4`. Crossing transformation: **`T4` — rounding to 3 significant digits.**

**`TEST` reproduces `TRAIN` closely** (`R4`: `Ĥ` 0.4408 vs 0.4299; `N_viol` 36 445 vs 35 532), so the
`Ĥ(Q|R5) = 0` observation is **not** an artefact of one sample. **It is still `Ĥ`, not `H`** — §0.4.

---

# 2. ⚠️ `H-C` is STRUCTURALLY INAPPLICABLE to a sequential chain

The design hypothesized **non-monotone adequacy** (§2 `H-C`), and §12 instructed that monotonicity not
be assumed. **Empirically, `Ĥ(Q|R)` is non-decreasing along the chain:**

```
R5 → R4 → R3 → R2      0.0000  →  0.4299  →  0.8546  →  3.3937      non-decreasing
```

**But this is not an empirical finding. It is forced.**

> ### Because `Rⁿ⁻¹ = Tₙ(Rⁿ)`, the data-processing inequality gives
> ```
>                H( Q | Rⁿ⁻¹ )  ≥  H( Q | Rⁿ )        always
> ```
> **Adequacy cannot be non-monotone in a SEQUENTIAL chain.** Anything recoverable from `Rⁿ⁻¹` is
> recoverable from `Rⁿ` by composing with `Tₙ`.

**`H-C` was therefore unfalsifiable-in-the-negative-direction from the start**, and the design's
warning "do not assume monotonicity" — correct in general — **does not apply to a chain of this
shape.**

> **What the design got wrong, precisely:** it reasoned that rank could break ties that rounding
> created, restoring `argmax`. **But `R2` is computed FROM `R3`.** It cannot see what `R3` destroyed.
> **The non-monotonicity argument implicitly assumed a PARALLEL family of representations, and the
> design specifies a sequential chain.**
>
> `[NEG]` **`H-C` was identified as STRUCTURALLY INAPPLICABLE to the specified deterministic
> sequential chain.** By the data-processing inequality, the conditional entropy of `Q` cannot
> decrease under the successive transformations. **The empirical monotonicity therefore serves as a
> CHAIN-CONSISTENCY CHECK, not as an independent test of `H-C`.**
>
> ⚠️ **`a priori impossible` ≠ `empirically falsified`.** The earlier wording *"refuted a priori"*
> conflated the two and is withdrawn. Nothing was measured that could have gone the other way.
>
> **Non-monotone adequacy is observable only across a PARALLEL family, never along a sequential
> chain** — a constraint on any future reduction experiment.

**The empirical check is still worth having:** it confirms the implementation obeys the DPI, which is
a correctness test on the chain (`FT-2` covers construction; this covers semantics).

---

# 3. ⚠️ `H-B` — the DESIGN failed to operationalize the separation

> `[NEG]` **The design failed to operationalize the intended separation of representation, contract
> and operator boundaries. Representation failure was observable; contract-only and operator-only
> failure were NOT GENERATED.**
>
> **This is a failure of the experimental design, not of the underlying theoretical distinction.**
> Adequacy ≠ realization remains a sound distinction — §5 demonstrates it directly. What this chain
> lacked was a transformation able to exhibit it.

The design claimed to make representation, contract and operator boundaries separately observable.
**It did not.**

| failure mode | observed? |
|---|---|
| **representation** (`N_viol > 0`) | **yes** — `R4`, `R3`, `R2` |
| **contract** (`A_n < 1`) | **NEVER — `A_n = 1.000` at every level** |
| **operator** (`Ĥ(Q\|R)=0` but `F<1`) | **NEVER — the only adequate level, `R5`, has `F = 1.0000`** |

**Why:** the chain's transformations never drop a record or a source, so `C` cannot fail. And `O`
fails only where the information is already gone. **There is no level at which the information is
present and the committed operator misses it.**

> `[NEG]` **The design's §7 claim — that `O`'s naivety makes the operator boundary observable — is
> not borne out.** The claim needed a transformation that *preserves* `Q` while *defeating* `O`;
> **this chain contains none.**

---

# 4. `H-D` CONFIRMED — "reduction" is genuinely multi-dimensional

| level | encoded bytes | fields/record | **cardinality** | `Ĥ(R)` |
|---|---|---|---|---|
| `R5` | 37.0 | 2 | **26 826** | 15.042 |
| `R4` | 37.0 | 2 | **5 696** | 12.307 |
| `R3` | 37.0 | 2 | **3 288** | 11.097 |
| `R2` | 25.0 | 2 | **162** | 7.170 |

> **Bytes are FLAT across `R5→R3` while cardinality falls 8×.** Fields never change. `Ĥ(R)` falls
> smoothly throughout.
>
> `[EXP]` **Reduction is MULTIDIMENSIONAL: the measured dimensions do not collapse into a single
> common reduction trajectory.** A single "amount of reduction" number would have reported
> `R5 = R4 = R3` on bytes while cardinality fell 8×. The design's refusal to combine them (§9) is
> vindicated.
>
> ⚠️ **Not claimed: that the dimensions are statistically INDEPENDENT.** Four observations along one
> chain cannot establish independence, and the earlier wording *"move independently"* is withdrawn.

**`H-E`:** `Ĥ(Q) = 5.0098` bits over 39 distinct answers. `Ĥ(R) ≥ Ĥ(Q)` at **every** level — 15.04,
12.31, 11.10, 7.17. **No audit triggered.**

> **Epistemic status: EMPIRICAL CONSISTENCY with the theoretical bound — not a proof of it.**
> Theorem 3 concerns `H` under stated assumptions; these are bias-corrected finite-sample `Ĥ`. *(Per §2, a violation would have triggered an audit, not a
falsification of Theorem 3.)*

---

# 5. The strongest result — an INFORMATION-EQUIVALENT RECODING separates adequacy from realization

**`T2`'s rank direction carries no information about `D`.** It is a pure encoding convention. Both
were run as a declared factor.

| convention | `F_argmax` | `F_decile` | `Ĥ(Q\|R2)` | `N_viol` |
|---|---|---|---|---|
| **ascending** (rank 3 = largest) | **0.8667** | 0.0491 | 3.3937 | 5 530 777 |
| **descending** (rank 1 = largest) | **0.3230** | 0.0491 | 3.3965 | 5 577 078 |

> ## `[EXP]` The ascending and descending rank conventions are **INFORMATION-EQUIVALENT RECODINGS** of the same rank representation — yet they produce substantially different performance for the fixed decoder `O`: **argmax accuracy swings 54 points**, while adequacy is essentially unmoved (`Ĥ` 3.3937 vs 3.3965; `N_viol` within 0.8 %).
>
> **`ADEQUACY` is invariant under an information-preserving recoding. `REALIZATION` is not.**

**Why the formal phrasing matters.** An earlier draft said the convention *"carries no information
about `D`"*. That is informal and slightly wrong. **What is theoretically load-bearing is that the map
between the two conventions is INVERTIBLE — information-preserving.** The two `R2` representations are
therefore **`Q`-equivalent to each other** (§0.2), and the entire 54-point swing is attributable to the
decoder, not to the representation.

**This is `DECISION-01` observed in a new setting** — with the sharper statement that the invariance is under an **invertible recoding**, not merely under something that "feels" non-evidential — — and it locates the sensitivity precisely: the
principle constrains **what a representation makes recoverable**, not **what a committed operator
happens to recover.** The design separated adequacy from realization on conceptual grounds (§0.3);
**this measures the separation.**

---

# 6. `H-RR2` — no Zero/boundary association observed

Zero measured with the **typed per-level operator `E_S^{(n)}`** (never `D∖S`; at `R2` ranks are
recomputed):

| level | next | elimination tests | zero rate |
|---|---|---|---|
| `R5` | `R4` | 12 000 | 0.0377 |
| `R4` | `R3` | 12 000 | 0.0426 |
| `R3` | `R2` | 12 000 | **0.0000** |

**The boundary crossing is `R5 → R4`. The Zero rate there (0.0377) is not distinguishable from the
non-crossing `R4 → R3` (0.0426), and Zero vanishes entirely at `R3`.**

> `[NEG]` **No association between `Zero` at level `n` and adequacy at `n−1` was observed.**
> **`H-RR2` was correctly stated as an association hypothesis and not an implication** — had it been
> written `Zero ⇒ Adequacy`, this result would have been a refutation of the design rather than of a
> hypothesis. **The weakening requested in revision R2 is what made this outcome reportable.**

---

# 7. Falsification scorecard (design §17)

| | claim | outcome |
|---|---|---|
| **A** | adequacy | **survives** — `R5` adequate, `N_viol(R5)=0` on both splits |
| **B** | realization separable from adequacy | `[NEG]` **the DESIGN failed to operationalize it** — contract-only and operator-only failure were never generated (§3). The theoretical distinction stands; §5 demonstrates it |
| **C** | a boundary exists | **survives** — `R5→R4`, `T4` |
| **D** | information-theoretic optimality | `Ĥ(R) ≥ Ĥ(Q)` everywhere; **no audit triggered** |
| **E** | monotone reduction (`H-D`) | **the dimensions diverge — `H-D` confirmed, so "reduction" is not one-dimensional** |
| — | **`H-C` non-monotone adequacy** | **STRUCTURALLY INAPPLICABLE** to a sequential chain (DPI). **Not an empirical falsification** — the monotonicity observed is a chain-consistency check (§2) |

# 8. What this does NOT establish

- **Not** that `T4` is *the* boundary in general — it is the boundary **for this `Q`, this `Π`, this
  chain, and `V=12`.** §18's honest threat stands: this is a **calibrated toy**.
- **Not** `H(Q|R5) = 0`. Only `Ĥ(Q|R5) = 0` on two independent samples.
- **Not** anything about the carrier question, which remains `[OPEN]`.
- **Not** any KR-ZERO law — §20.4b's separation held: **no KR-ZERO aggregate was used as evidence
  here, and nothing here confirms a KR-ZERO law.**
- **Not** a kernel implication. **None sought, none found.**

# 9. Sequential vs parallel — a distinction the experiment forces into the theory

**Adopted from review as a permanent distinction.**

| regime | structure | adequacy behaviour |
|---|---|---|
| **SEQUENTIAL family** | `Rⁿ⁻¹ = Tₙ(Rⁿ)` — each level a function of the previous | **monotone by the DPI. Non-monotonicity is IMPOSSIBLE, not merely unobserved** |
| **PARALLEL family** | `Rᵃ = Tᵃ(D)`, `Rᵇ = Tᵇ(D)` — each derived independently from `D` | **no ordering forced. `Rᵃ` may preserve what `Rᵇ` destroys and vice versa** |

> **These are different mathematical regimes and must never be analysed with the same expectations.**
> The old KR-ZERO `R1..R4` are a **parallel** family (independent random draws, proven unordered);
> `R5..R2` here are a **sequential** chain. **The `CORPUS-THEORY-AUDIT` conclusion that they cannot be
> identified now has a second, structural reason: they belong to different regimes.**

**Consequence for design:** a hypothesis about non-monotone adequacy can only be posed in a parallel
family. **Posing it of a sequential chain — as `H-C` did — is a category error**, and it is the error
this experiment caught.

# 10. What this is NOT — a knowledge-extraction theory

> **We do not have a general knowledge-extraction calculus, and nothing here should be read as one.**

What exists:

```
preservation / adequacy mathematics   (§0, theoretical)
        +  KR-ZERO empirical constraints
        +  KR-REP-REDUCTION empirical demonstration   ← this experiment
```

**What is tested is a deliberately small numerical carrier** — `r = (value, source, timestamp)`,
`Q = (argmax_source, decile(total))`, **one chain, `V = 12`.**

**Transfer to documents · claims · entities · relations · provenance · contradictions · temporal
states · evidence is an OPEN research question.** The method is demonstrated; the domain is not.

# 11. Reproduction

```bash
cd code && python3 run.py        # deterministic; seeds 20260903 / 77020260903
```

`corpus/cases.jsonl` — 80 000 case rows, both splits, with `D` and `Q(D)`.
`corpus/levels.jsonl` — 160 000 level rows (`cid, split, level, R, A, O, Q`).
`results/metrics.json` — every metric, both conventions, both splits, plus the fidelity report.

