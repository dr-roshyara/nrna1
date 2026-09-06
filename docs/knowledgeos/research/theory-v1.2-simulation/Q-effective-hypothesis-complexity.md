# Q — Effective Hypothesis Complexity · `KR-NEFF-2026-09-02`

**Commissioned question:** *what is the mathematically relevant complexity of a hypothesis space, and
how does that complexity alter admissibility, evidence burden, validation and determination?*

**Status** `[EXP]` · **Baseline** v1.2, unchanged · **No v1.3** · `candidate_count` **not** introduced
into the theory.

---

# 1. An effective multiplicity measure is empirically meaningful — and `|H|` is the wrong quantity `[EXP]`

> **Scope corrected.** This section originally said *"`N_eff` is real"*. Too strong: the experiment
> shows an effective multiplicity quantity **can be empirically constructed under the tested null,
> test statistic and calibration procedure**. It does **not** show there is one uniquely correct
> universal object `N_eff`. Several effective-complexity notions may exist, indexed by null model ·
> test statistic · dependence structure · target error criterion · hypothesis equivalence · evidence
> channel · decision loss.

Method: NULL world; `τ` = empirical 95th percentile of the maximum score over 4 000 trials; `N_eff`
read off by inverting Bonferroni. Spaces with **equal `|H|`, different structure**:

| space | `\|H\|` | independent groups | τ | **N_eff** | N_eff/\|H\| |
|---|---|---|---|---|---|
| independent | 1 000 | 1 000 | 3.876 | **940.6** | 0.94 |
| grouped, 200 groups | 1 000 | 200 | 3.483 | **201.9** | 0.20 |
| **grouped, 50 groups** | **1 000** | **50** | 3.097 | **51.1** | **0.05** |
| *independent, \|H\|=50* | *50* | *50* | *3.097* | ***51.1*** | *1.02* |
| correlated ρ=0.5 | 1 000 | — | 3.482 | 200.6 | 0.20 |
| **correlated ρ=0.9** | **1 000** | — | 2.541 | **9.0** | **0.009** |

> **A 1 000-candidate space with 50 groups is empirically indistinguishable from a 50-candidate
> independent space** — identical `τ` (3.097) and identical `N_eff` (51.1). And at ρ = 0.9,
> **1 000 candidates behave like nine.**
>
> `[EXP]` **`|H|` overstates the multiplicity burden by up to 111×.** The relevant quantity is
> effective complexity, not candidate count.

This confirms the review's point directly: *do not introduce `candidate_count` into the theory.*

---

# 2. No closed form survives — and I had to retract twice `[NEG]`

This is worth recording as a sequence, because two of the three steps were mine.

**Step 1 — I asserted, before checking.** I printed *"no tested closed form predicts both grouped and
equicorrelated structure"* — **before reading the error column.** That was an assertion, not a result.

**Step 2 — the assertion was wrong on the evidence I had.** On the original six-point grid,
`n(1−ρ)²` fitted well:

| predictor | mean \|log ratio\| error |
|---|---|---|
| **n(1−ρ)²** | **0.082** |
| n(1−ρ) | 0.599 |
| Cheverud `M_eff` | 0.791 |
| independent groups | 1.116 |
| **`\|H\|` raw count** | **1.873** |

**Step 3 — widening the grid refuted `n(1−ρ)²`.** Across ρ ∈ [0.05, 0.95] and n ∈ [200, 5 000]:

| ρ | n | measured | n(1−ρ)² | ratio |
|---|---|---|---|---|
| 0.3 | 1 000 | 487.5 | 490.0 | 0.995 |
| 0.9 | 1 000 | 9.0 | 10.0 | 0.900 |
| **0.9** | **200** | **6.2** | **2.0** | **3.100** |
| **0.9** | **5 000** | **17.0** | **50.0** | **0.340** |

Ratio spans **0.34 → 3.10** — a factor of 9 — and the *n*-dependence is plainly wrong. The good fit
on the original grid was **coincidence of the grid**.

> **Verdict — the original claim restored, but now earned rather than asserted:**
> `[NEG]` **`N_eff` is measurable but not reducible to any tested closed form over `(n, ρ, g)`.**
> `n(1−ρ)²` is refuted as a law; it remains a usable **local** approximation near n ≈ 1 000.

**Formalizing `N_eff` is therefore a genuine open mathematical problem**, not a notational convenience.

---

# 3. Dependence structure affects determination, not only admissibility `[EXP]`

> **Scope corrected.** Originally *"complexity reaches determination"* — rhetorically strong,
> mathematically underspecified. Split into two claims of different status:
> **`[EXP]`** dependence structure changes the determination rate under the tested model;
> **`[PROP]`** that dependence structure is a *component* of effective hypothesis complexity.

`|H| = 1 000` held fixed; only the number of independent groups varies. Signal 0.6, m = 40.

| groups | τ on `\|H\|` | τ on `N_eff` | admit rate | **validate rate** | **unique determination** |
|---|---|---|---|---|---|
| 1 000 | 0.615 | 0.615 | 0.487 | 0.206 | 0.468 |
| 200 | 0.615 | 0.550 | 0.639 | 0.395 | 0.605 |
| 50 | 0.615 | 0.489 | 0.761 | 0.576 | 0.720 |
| 10 | 0.615 | 0.407 | **0.899** | **0.789** | **0.855** |

> **Effective complexity affects all three.** Admissibility (0.49 → 0.90), validation (0.21 → 0.79)
> and unique determination (0.47 → 0.86) all move monotonically with the number of independent
> groups, **at constant `|H|`**.
>
> **The answer to the commissioned question's second half is: complexity is not confined to
> admissibility. It reaches determination.**

---

# 4. The scope-bound was right — and channel independence is the operative property `[EXP]`

The review corrected *"validation cannot repair a misleading evidence channel"* to
*"validation **confined to the same misleading channel** cannot detect channel-level bias."*
Tested directly, 4 000 trials:

| validation channel | detects the decoy |
|---|---|
| **same** (misleading) channel | **0.0075** |
| **independent** channel | **0.9928** |

> `[EXP]` **The scope-bound is confirmed, and the unbounded claim would have been false.**
> An independent channel catches the decoy in **99.3 %** of trials; the same channel catches it in
> **0.75 %**.
>
> **Channel independence does the work, not validation per se.** Design consequence: a validation
> step must **declare its evidence channel**, and a validation on the selection's own channel is
> **not** independent evidence whatever it is called.

> **REFINED — declaration is not independence.** `Channel(V) = c` is a *declaration*; it does not
> establish `Independent(Channel(V), Channel(S))`. What is needed is a **relation**:
> `ChannelRelation(c₁,c₂) ∈ { same · independent-under-stated-assumptions · partially-dependent ·
> unknown · causally-coupled }`. A `ValidationContext` would then carry
> `(Channel, Evidence, Model, Population, Time, Standard, …)`. `[PROP]`

`[PROP]` **Measurement/channel model as an explicit epistemic object** — supported, not adopted.

---

# 5. Status register

| finding | status |
|---|---|
| `N_eff` is measurable; `\|H\|` overstates burden up to 111× | **`[EXP]`** |
| A grouped 1 000/50 space ≡ an independent 50 space | **`[EXP]`** — identical τ and N_eff |
| `N_eff` has no closed form over `(n, ρ, g)` | **`[NEG]`** — `n(1−ρ)²` refuted on a wider grid |
| `n(1−ρ)²` as a local approximation near n≈1 000 | `[PROP]` |
| Complexity affects admissibility **and validation and determination** | **`[EXP]`** |
| Same-channel validation cannot detect channel bias | **`[EXP]`**, scope-bounded |
| Independent-channel validation detects it (99.3 %) | **`[EXP]`** |
| Channel model as an explicit epistemic object | `[PROP]` |
| `candidate_count` as a theory field | **not introduced** — `N_eff` is the right object and it is unformalized |

# 6. Next

The commissioned question is now half-answered: **complexity is measurable and it reaches
determination; but it has no closed form.** So:

> **`N_eff` is a new open mathematical item — and unlike the others, it is a well-posed one.**

It joins the queue but does not displace it. The ordering stands: **Factivity → `Contr` → `⪰`**.

> **CORRECTED — the queue logic.** This originally asserted that `N_eff` *"feeds an admissibility
> function that presupposes `⪰`"*. **Not established.** There are at least two independent paths:
> **statistical admissibility** (`N_eff → multiplicity control → threshold`), which needs **no**
> general epistemic ordering; and **epistemic admissibility**
> (`N_eff → assessment → ⪰ → admissibility`), which does. **`N_eff` and `⪰` are related but
> independent research problems** until shown otherwise.

One cheap item can proceed independently: **require every validation step to declare its evidence
channel.** §4 shows the property that matters is independence, and declaring the channel is a
schema decision, not research.

**Do not** formalize `N_eff` by adopting `n(1−ρ)²`. It was refuted here, on a wider grid, after
fitting well on a narrow one — which is the same failure mode as the winner's curse the parent
experiment measured.
