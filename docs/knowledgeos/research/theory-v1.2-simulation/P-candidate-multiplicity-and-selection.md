# P — Candidate Multiplicity, Selection, Validation and Determination · `KR-M2O-2026-09-02`

**Protocol** authored externally (`…/20260902-133231_kr-m2o-…md`, 1 351 lines). Executor: this lane.
**Baseline** KnowledgeOS Theory v1.2, **unmodified**. **No v1.3.** **Status** `[EXP]`.

> The "Linga's Millions" source is `[EXT]` — a philosophical/interpretive source. Its biological
> mappings are **not** adopted. This experiment is **adversarial**: it does not try to confirm the
> metaphor, and it does not make the model work by construction. Where a result was inconvenient it
> was **diagnosed, not tuned** (see §2.1).

---

# 1. The statistical centrepiece — selection bias `[EXP]`

**NULL world**: no candidate has any real predictive advantage. Select the best training fit; evaluate
the *selected* candidate on an independent draw. 200 trials per arm.

| n | max training | **held-out (selected)** | held-out (all) | **selection optimism** | √(2 ln n)/√m |
|---|---|---|---|---|---|
| 1 | 0.0027 | 0.0030 | 0.0030 | −0.0003 | 0.000 |
| 10 | 0.2367 | 0.0082 | −0.0002 | **0.2285** | 0.339 |
| 100 | 0.3915 | −0.0031 | −0.0017 | **0.3945** | 0.480 |
| 1 000 | 0.5116 | 0.0152 | 0.0006 | **0.4964** | 0.588 |
| 10 000 | 0.6101 | −0.0120 | −0.0001 | **0.6222** | 0.679 |

> **Apparent quality rises monotonically 0.00 → 0.61. Real quality never moves from zero.**
> The held-out performance of the *selected* candidate is statistically indistinguishable from the
> population mean at every n. **The entire gap is selection optimism**, tracking the extreme-value
> bound `σ√(2 ln n)/√m`.

**H1 confirmed. H9 confirmed.** `|𝓗| ↑ ⇏ KnowledgeQuality ↑`, and `|𝓗| ↑ ⇒ SelectionBias ↑`.

---

# 2. The strongest result — multiplicity and evidence are coupled `[EXP]` NEW

## 2.1 An inconvenient result, diagnosed rather than tuned

§12 CASE 1 (100 candidates → unique determination) returned **|A| = 2**, not 1. Rather than adjust the
threshold, I computed why:

| τ | P(a null candidate crosses) | P(≥1 false admit among 99) |
|---|---|---|
| 0.4 | 0.0057 | **0.432** |
| 0.5 | 0.00078 | 0.075 |
| 0.6 | 0.00007 | 0.007 |

**|A| = 2 was the expected outcome**, not a defect: it is the winner's curse reappearing in the
determination arm. Tuning τ to obtain the "expected" answer would have been exactly the
*make-the-model-work-by-construction* the protocol forbids.

## 2.2 The correct statistical response, and what it costs

Applying a multiplicity-corrected (Bonferroni) admissibility threshold, `signal = 0.6`, `m = 40`:

| n | z(1−α/n) | τ at m=40 | **truth admissible?** | m required |
|---|---|---|---|---|
| 1 | 1.645 | 0.260 | ✔ | 8 |
| 10 | 2.576 | 0.407 | ✔ | 19 |
| 100 | 3.291 | 0.520 | ✔ | 31 |
| **1 000** | 3.891 | **0.615** | **✘ — the truth is EXCLUDED** | 43 |
| 10 000 | 4.417 | 0.698 | ✘ | 55 |
| 100 000 | 4.892 | 0.773 | ✘ | 67 |

```
                signal  >  z(1−α/n) · σ/√m        ⟺        m  >  ( z σ / signal )² ,   z² ~ 2 ln n
```

> `[EXP]` **Candidate count and evidence quantity are coupled.** At fixed evidence, a real signal of
> 0.6 becomes **inadmissible** somewhere between n = 100 and n = 1 000. Restoring admissibility
> requires more evidence per candidate, growing as `2 ln n`.
>
> **This sharpens H1 into something worse: more candidates does not merely fail to improve
> knowledge — at fixed evidence it destroys the ability to detect a real signal.** There is no
> threshold that is simultaneously sound against multiplicity and sensitive to a fixed signal as n
> grows.

> ### CORRECTED — scope of the remedy
> This section originally concluded *"the fix is evidence, not thresholds."* Directionally right,
> **too absolute**. What the experiment establishes is:
> **multiplicity control cannot be obtained for free.** The admissible remedies include more
> evidence, **stronger prior or structural constraints**, **multiplicity-aware inference**,
> **reduced effective hypothesis complexity**, **hierarchical modelling**, **replication**, and
> **independent evidence** — this experiment tested only the first. "Evidence, not thresholds" is an
> experimental conclusion under one remedy, **not a KnowledgeOS law**.

**Consequence for the theory:** an admissibility criterion that does not read `n` is **unsound at
scale**. v1.2's `Sat` and the standard `S^epi` have no candidate-count parameter. `[OPEN]`

---

# 3. Generators — "most hypotheses are false" is a property of the generator `[NEG]` (H12)

n = 1 000 per generator; false proportion **measured**, never assumed:

| generator | false proportion | diversity | candidates / independent groups |
|---|---|---|---|
| G1 random · G2 diverse · G5 adversarial · G6 mostly-false | 1.000 | 1.000 | 1000 / 1000 |
| **G3 correlated** | 1.000 | **0.200** | **1000 / 200** |
| **G4 near-duplicate** | 1.000 | **0.050** | **1000 / 50** |
| **G7 mostly-true** | **0.200** | 1.000 | 1000 / 1000 |

> **H12 confirmed.** The false proportion ranges **0.20 → 1.00 across generators**. The
> million-to-one ratio has **no epistemological status**; it is a property of a generator, not of
> hypothesis spaces.
>
> **And candidate count ≠ candidate diversity:** G4 offers 1 000 candidates and **50** independent
> alternatives. Any argument from "we considered a thousand hypotheses" must report **diversity**.

*Honest note:* four generators produced false proportion exactly 1.000 because they never emit the
truth. That is not a defect of the measurement — **a generator that cannot produce the truth is a
real epistemic failure mode**, and it makes `|A_t| = 0` the *correct* outcome (H7).

---

# 4. Ordering — scalar ranking manufactures uniqueness `[EXP]` (H8)

Candidates constructed with genuine incomparability (`H_true` better on evidence + explanatory;
`H_twin` better on consistency):

| regime | result |
|---|---|
| **Pareto / admissibility** | `\|A\| = 9` — **incomparability preserved**, both survivors retained |
| **scalar argmax** | `\|A\| = 1` — **unique by construction** |
| threshold τ = 0.5 | `\|A\| = 1` |

> **"Best candidate" is a consequence of the imposed scoring function, not a property of the
> candidate set.** A total scalar ranking must not be assumed; it *creates* the uniqueness it appears
> to discover.

---

# 5. The four-way separation

| hypothesis | result |
|---|---|
| **H5** unique survivor ⇏ Knowledge | **confirmed** — §6 below |
| **H6** multiple survivors can be correct | **confirmed** — `\|A\|=5` in the observationally-equivalent world |
| **H7** zero survivors can be correct | **confirmed** — `\|A\|=0` in the none-admissible world |
| **H2** candidate reduction ≠ epistemic progress | **confirmed both directions** — ρ = 0.999 with *no* quality gain in the null world; ρ = 0.1 that removed the one decisively refuted candidate |

`ρ = 1 − |H_{t+1}|/|H_t|` is **CandidateReduction**. It is not KnowledgeGain and not EpistemicProgress.

---

# 6. H3 — and a result stronger than H3 `[NEG]`

World `W4`: decoy signal 0.9, truth signal 0.3, truth = `H_true`.

```
selected (best apparent fit)                      : H_decoy
independent held-out validation of the selected   : 0.9849  →  PASSES
ground truth                                       : H_true   [evaluator only]
```

> **Selection and validation AGREED — and both were wrong.**
>
> This is stronger than "Selection ≠ Validation". The decoy has genuinely higher evidential signal
> than the truth, so **held-out evaluation on the same evidence channel confirms it.** No amount of
> validation on that channel can detect a misleading channel.

`[NEG]` **Validation confined to the same misleading evidence channel cannot detect channel-level
bias.** *(Scope corrected: the original wording — "validation cannot repair a misleading evidence
channel" — was unbounded. With a genuinely independent channel `E₁ ⊥ E₂`, or an intervention
`do(X=x)`, the conclusion may change; §6.1 tests exactly that.)* Recorded as **factivity
unresolved**; **not repaired here**, per §13.

---

# 7. Kernel test (H11) — outcome **A: redundancy**

Reconstructing set-valued `Sel` using only existing v1.2 capabilities —
**Validate** (warrant threshold) + **Discriminate** (pairwise dominance) + **Determine**
(set-valued admissibility):

```
agreement with Sel on 200/200 random configurations
```

> **Outcome A — REDUNDANCY.** `Selection = f(Discriminate, Validate, Determine)`, reproduced exactly.
> **The candidate-multiplicity model introduces no new irreducible kernel capability.**

**Three caveats, all binding:**

1. This establishes derivability of the **set-valued operation** only. It does **not** refute outcome
   **B** — Selection may still be a distinct DDD responsibility while being computationally derivable.
2. Per §19, **no kernel minimality claim is admissible** while `≡_sem` is undefined.
3. `Select` was **not** assumed primitive merely because the source model uses the word.

---

# 8. Verdict against the twelve hypotheses

| | hypothesis | result |
|---|---|---|
| H1 | more candidates ⇏ better epistemic quality | **CONFIRMED** — and sharpened: at fixed evidence it *degrades* detection |
| H2 | candidate reduction ≠ epistemic progress | **CONFIRMED** both directions |
| H3 | selection ≠ validation | **CONFIRMED**, and superseded by §6 — they can agree and both be wrong |
| H4 | selection ≠ determination | **CONFIRMED** — determination is set-valued; selection need not be |
| H5 | unique survivor ⇏ Knowledge | **CONFIRMED** (§6) |
| H6 | multiple survivors can be correct | **CONFIRMED** |
| H7 | zero survivors can be correct | **CONFIRMED** |
| H8 | "best" needs an ordering; no total scalar | **CONFIRMED** — scalar manufactures uniqueness |
| H9 | more candidates ⇒ more selection bias | **CONFIRMED** — optimism 0.00 → 0.62 |
| H10 | the six functions may be separate responsibilities | **supported**; DDD-distinctness untested |
| H11 | if reproducible, no new primitive | **CONFIRMED — outcome A** |
| H12 | the biological ratio has no epistemic status | **CONFIRMED** — 0.20–1.00 across generators |

**Twelve of twelve SUPPORTED UNDER THE TESTED SCENARIOS. None refuted.**
*(Status corrected: `supported`, not `established` — a confirmation under one experimental model is
not a theory-level establishment.)* That is a *weak* outcome by this programme's standards:
the hypotheses were well chosen, and the experiment did not surprise them. **The two genuinely new
results are §2 (multiplicity–evidence coupling) and §6 (validation agreeing with a wrong selection)**,
and neither was among the twelve.

---

# 9. Theory amendments — **none proposed**

| candidate amendment | why not |
|---|---|
| a `Selection` kernel primitive | **outcome A**: derivable from three existing capabilities |
| a candidate-count parameter in `S^epi` | **justified by §2**, but it is an admissibility-criterion change that presupposes `⪰`; recorded `[OPEN]`, not proposed |
| Linga/Yoni structure | `[EXT]`; nothing in the experiment establishes it |

> **No v1.3.** Baseline remains v1.2. The one finding with amendment potential (§2) is **blocked
> behind `⪰`**, like everything else.

# 10. Next

Unchanged: **Factivity → `Contr` → `⪰`.** This experiment does not reorder it, and adds one item to
the `⪰` queue:

> `[PROP]` **Admissibility must be a function of candidate count.** §2 shows a criterion that ignores
> `n` is unsound at scale, and that the repair is *evidence*, not *thresholds*. Formulating that
> requires the ordering — the same blocker as `L5` and the stagnation predicate.

**Do not** conclude from outcome A that `Select` should be removed from the operator vocabulary.
Derivable ≠ mergeable, and the DDD question (outcome B) is untested.
