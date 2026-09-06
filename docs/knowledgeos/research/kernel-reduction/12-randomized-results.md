# 12 — Randomized, Statistical and Robustness Results (Parts XI, XII, XXIII)

## Design

Random epistemic worlds vary **which ambient carriers the situation supplies**
(`Policy`, `Rule`, `IdealState`, `Objective`, `ActionSet`, each present with p = 0.6) plus
1–20 entities / observations / claims, 1–10 dimensions / evidence items, 1–5 competing hypotheses,
and Bernoulli flags for missing evidence, conflict, temporal change, differing provenance, differing
context, and indistinguishability.

**Seeds** `1, 7, 13, 101, 2718` × **2 000 trials** = **10 000 worlds per arm**, 15 arms
(baseline + 14 leave-one-out).

> ### CORRECTED — the design is PAIRED, not 150 000 independent observations (directive §23; verified)
>
> `make_world` consumes a fixed 17 random draws regardless of the operator set, so **re-seeding each
> arm with the same seed hands every arm the identical world sequence.** Verified directly: two arms
> at seed 1 produce bit-identical world streams, and one arm's 10 000 trials contain 10 000 distinct
> worlds.
>
> Therefore the experiment is **10 000 distinct worlds × 15 perfectly paired arms**, not 150 000
> independent observations. Failure indicators are correlated across arms by construction.
>
> **This is the right design for comparing arms** — pairing removes world-level variance entirely —
> **but it means the correct statistic is the paired difference** `D_i = Failure_{i,A} − Failure_{i,B}`,
> not two independent binomials. No between-arm test was run, so no published conclusion is
> invalidated; but the Wilson intervals below are *within-arm Monte-Carlo error only* and must never
> be read as licensing an arm-to-arm comparison. See §"The one result that survives" for the paired
> analysis of the only comparison that matters.

Randomization is **not** used to produce large numbers. Each property has a specific structural
target, and properties can fail by **overreach** as well as by incapacity.

## Property failure rates (pooled, n = 10 000 per arm)

| Ablated | Failing properties (rate, 95 % CI) |
|---|---|
| *(baseline)* | **none** |
| `Observe` | P1 .202 [.194,.210] · P2 .242 [.234,.250] · P5 .496 [.486,.505] · P8 .800 [.792,.808] · P9 .496 [.486,.505] |
| `Interpret` | P2 .242 [.234,.250] · P5 .496 [.486,.505] · P8 .800 [.792,.808] · P9 .496 [.486,.505] |
| `Represent` | none |
| `Relate` | P5 .496 [.486,.505] |
| `Discriminate` | none |
| `Hypothesize` | P2 .242 [.234,.250] · P8 .800 [.792,.808] |
| `Infer` | none |
| `DetectGap` | none |
| `Challenge` | **P10 .594 [.585,.604]** |
| `Validate` | none |
| `Revise` | P1 .507 [.498,.517] · P4 .500 [.490,.510] |
| `Determine` | none |
| `Select` | none |
| `Qualify` | none |

## The vacuity audit — and why most of that table is not what it looks like `[NEG]`

A property whose antecedent is never active passes **vacuously**. Guard-activation rates:

| Ablated | P1 | P2 | P3 | P4 | P5 | P6 | P7 | P8 | P9 | P10 |
|---|---|---|---|---|---|---|---|---|---|---|
| baseline | .51 | .24 | **.00** | .50 | .50 | .60 | 1.00 | .80 | .50 | .59 |
| Observe | .51 | .24 | .00 | .50 | .50 | .60 | **.00** | .80 | .50 | **.00** |
| Interpret | .51 | .24 | .00 | .50 | .50 | .60 | 1.00 | .80 | .50 | **.00** |
| Hypothesize | .51 | .24 | .00 | .50 | .50 | .60 | 1.00 | .80 | .50 | .36 |
| Validate | .51 | .24 | .00 | .50 | .50 | .60 | 1.00 | .80 | .50 | **.00** |
| Determine | .51 | **.00** | .00 | .50 | .50 | **.00** | 1.00 | .80 | .50 | .59 |
| Qualify | .51 | .24 | .00 | .50 | .50 | .60 | 1.00 | .80 | .50 | **.00** |
| *(all others)* | .51 | .24 | .00 | .50 | .50 | .60 | 1.00 | .80 | .50 | .59 |

**Three consequences, all negative results:**

1. **P3 (provenance) has activation 0.00 in every arm.** It is structurally guaranteed by the
   simulator and therefore untestable here. Its universal pass is worthless. `[NEG]`
2. **`Validate`, `Qualify`, `Determine`, `Select`, `Infer`, `Represent`, `DetectGap` and
   `Discriminate` show "no property failures" — but for `Validate`, `Qualify` and `Determine` this is
   vacuous**: removing them drives the guard to zero. A system that *cannot* validate never validates
   *wrongly*. `[NEG]`
3. **The randomized property suite is non-discriminating for capability loss.** It detects
   *misbehaviour*, not *incapacity*. Ablation degrades a system into "cannot do it", which the
   properties cannot see. **Capability ablation (§10) and scenario ablation are the load-bearing
   instruments; the randomized suite is a robustness check on top of them, nothing more.** `[NEG]`

### The one result that survives — and its correct (paired) analysis

The single arm carrying genuine signal is **`Challenge`**. Under the correct paired analysis over the
10 000 shared worlds:

| | count |
|---|---|
| ablated arm fails, baseline does not (`b`) | **5 944** |
| baseline fails, ablated does not (`c`) | **0** |
| both fail | 0 |
| neither fails | 4 056 |
| guard active (baseline) | 5 944 |

> **P(P10 fails │ guard active) = 5944 / 5944 = 1.0000 — exactly, and *deterministically*.**
> Given the world and the operator set, the outcome is not sampled at all. `c = 0` and `b` equals
> *every* guard-active world, so no test statistic is required and none is reported.

> ### CORRECTED — the confidence interval was on the wrong quantity (directive §23)
>
> An earlier version reported *"P10 fails at .5944, 95 % CI [.5847, .6040]"*. That put a sampling
> interval on a quantity that has **no sampling error**: the conditional failure rate is 1 by logic.
> The only quantity with genuine sampling error is the **guard-activation rate**, and it is a
> property of the chosen generator `G`, not of KnowledgeOS:
>
> **guard activation = 0.5944, 95 % CI [0.5847, 0.6040]** — i.e. *"in ~59 % of worlds drawn from `G`,
> the situation arises at all"*, and *"whenever it arises, the ablated system fails, always."*
> The second clause is the finding; the first is a fact about the generator.

Removing `Challenge` produces a system that issues verdicts it was never able to attack — a true
overreach failure, not an incapacity artifact, and the only property result in this experiment that
survives the vacuity audit at full strength.

Similarly `Observe`, `Interpret` and `Hypothesize` produce genuine **P2 overreach** at .242: the
system still emits a `Determination` while unable to represent the alternatives it is determining
between — it fabricates uniqueness. That is the sharpest empirical signature of a real primitive.

## Statistical interpretation — what these numbers do and do not establish

* The rates are **properties of the generator**, not estimates of any population. `P8 = .800` is
  ≈ P(≥2 hypotheses) under the chosen generator, nothing more. The CIs quantify Monte-Carlo error
  only; they carry **no** information about whether the underlying model is right.
* No significance test is reported. There is no hypothesis here for which a p-value is the right
  instrument. **The central question is structural, not statistical.**
* **Seed reproducibility, not statistical robustness** (directive §24): no arm's failure set changed
  across the five seeds and per-seed rates agreed within Monte-Carlo error. Mathematically this shows
  the generator and algorithm are deterministic given a seed and that these five seeds exposed no
  variation. It establishes neither `∀ seed` nor robustness to *generator specification* — the label
  is therefore **seed reproducibility**.
* **No single-number kernel score is produced**, and none should be. §14 keeps the criteria separate.

## Robustness across alternative models (Part XXIII)

Seven alternative reasonable models. `A` = irreducible, **`B`** = derivable.

| Variant | What it changes | Operators that become derivable |
|---|---|---|
| **V0** | baseline as declared | `Discriminate`, `DetectGap` |
| **V1** | `meaning-assignment` and `symbolic-encoding` are ONE atom | `Interpret`, `Represent`, `Discriminate`, `DetectGap` |
| **V2** | `Infer` may act on `SemanticContent`, not only `Representation` | `Discriminate`, `DetectGap` |
| **V3** | closure-judgment not primitive: adequacy = difference-decision over the norm delta | `Discriminate`, `DetectGap`, **`Determine`** |
| **V4** | `DetectGap`'s difference-decision is **scoped** to `NormDelta` | **`DetectGap` only** |
| **V5** | action choice = difference-decision + `Objective` | `Discriminate`, `DetectGap`, **`Select`** |
| **V6** | a `Verdict` requires a surviving-defeater step | `Discriminate`, `DetectGap` |
| **V7** | raw `C0`, no `Qualify` (baseline lost: C10 C16 C17 C25) | `Discriminate`, `DetectGap`, **`Validate`** |

### Reading the robustness table

| Conclusion | Survives | Verdict |
|---|---|---|
| **`DetectGap` is derivable** | **8 / 8 variants**, including V4 which was built to protect it | `[EXP]` **robust** |
| `Observe`, `Relate`, `Hypothesize`, `Infer`, `Challenge`, `Revise`, `Qualify` irreducible | 8 / 8 | `[EXP]` robust |
| `Discriminate` is derivable | 7 / 8 — **fails under V4** | `[EXP]` **artifact of `DetectGap`'s unscoped atom**, not a property of `Discriminate` |
| `Interpret`, `Represent` both irreducible | 7 / 8 — fails under V1, where each derives *the other* | model-dependent: the pair is jointly necessary, the split is a modelling choice |
| `Determine` irreducible | 7 / 8 — fails under V3 | **`[OPEN]`**: turns entirely on whether closure-judgment is primitive |
| `Select` irreducible | 7 / 8 — fails under V5 | **`[OPEN]`**: turns on whether preference-over-actions reduces to difference-decision |
| `Validate` irreducible | 7 / 8 — "derivable" under V7 | **degenerate**: in V7 nothing can produce a `Verdict` at all, so `Validate`'s atom is inert. This is a *vacuous* derivability and is rejected as evidence. `[NEG]` |

## Information-theoretic check (Part XIII)

`X = (port, context)`, `H(X) = 2.000` bits. The signal is an ambiguous token half the time.

| Pipeline | Mutual information with `X` |
|---|---|
| with `meaning-assignment` (context-indexed) | `I(X; S) = 1.500` bits |
| without it (raw encoding) | `I(X; S′) = 0.500` bits |
| **loss** | **1.000 bit — exactly the whole context index** |

Data-processing inequality holds (`I(X;S′) ≤ I(X;S)`): `S′` is a function of `S`, so **no downstream
transformation of `S′` alone can increase its mutual information with `X`.**

> **Correction (2026-09-01, external audit §17 — accepted).** An earlier wording read *"no downstream
> operator can recover what the meaning step did not carry"*. That is too strong. It holds only for
> processing that receives **no additional information**; a downstream step given `S′` **plus**
> `Context` may recover the index. The DPI constrains functions of `S′` alone, not every possible
> downstream system.

The residual 0.5-bit ambiguity is a property of the signal, not a failure of any operator — the two
must not be conflated.

The three distinctions the protocol demands are kept separate: this is **information loss**
(measured, 1 bit); the `Hypothesize+Infer` pair result is **semantic inadequacy** (no proposition
bearer, nothing lost quantitatively); the `Challenge` P10 result is **epistemic invalidity** (a
verdict without possible defeat).

## Causal / model-criticism check (Part XV)

Synthetic confounding `Z → X`, `Z → Y`, **no** `X → Y` edge, n = 4 000, seed 11:

```
OLS beta = 1.852     R^2 = 0.899     true causal effect = 0.0
```

Strong association, strong fit, **zero causal effect**. Applied to the kernel:

| Model | Is a `Verdict` reachable without `Challenge`? |
|---|---|
| **V0** (baseline) | **yes** — a model that fits can be "validated" with no defeater ever considered |
| **V6** (verdict requires surviving a defeater) | **no** |

`[EXP]` The baseline model **permits** the failure mode `fit ⇒ validation`. Only V6 blocks it
structurally. This is a defect of the baseline *capability model*, not of any operator, and it is why
`Challenge` is the one operator whose necessity is visible in every instrument simultaneously —
capability ablation, scenario ablation, randomized properties (P10 at .594 with an active guard), and
the causal experiment.
