# The Development of the Model

### How the KnowledgeOS model reached its current state — what was added, what was removed, what was retracted, and where every artifact lives

**Date** 2026-09-02 · **Baseline** KnowledgeOS Theory **v1.2**, unchanged throughout
**Status** `[EXP]`/`[NEG]`/`[OPEN]` per claim — **nothing here amends the theory**

> **Relation to the other document in this folder.** `20260902-142217_the-frozen-model-…md` is a
> **snapshot**: what is settled *now*. **This document is the trajectory**: how it got there, what
> each step changed, and — more often — what each step took away. They are complementary; where they
> disagree on a figure, the frozen-model register and `S-frozen-results-register.md` win.

---

> # ⚖️ UPDATE — a governance decision was taken AFTER this trajectory was captured
>
> **`Factivity` is DECIDED: `R1` selected.** `K_t → A_t` (**AttributedState**);
> `Knows(a,p,c,t) → True(p,c,t)` **retained as an external, factive assertion**; **Verification kept
> separate.**
>
> **The historical sections below are NOT rewritten, and must not be.** They correctly record that
> `R1` and `R2` were **experimentally behaviourally identical** and that the experimental lane
> **therefore could not adjudicate between them.** That fact is permanent. The decision belongs
> **after** the experiment, never inside it:
>
> ```
> experiment  →  R1/R2 behavioural equivalence  →  experimental lane cannot adjudicate
>             →  human governance decision      →  R1 selected
> ```
>
> This ordering **is** the programme's `evidence ≠ decision` rule, in the one case where it was
> load-bearing. **Only the current-state sections are updated** — Part 4's post-decision note,
> Part 6, and Part 8. **Where a historical section reads "awaiting a decision", read it as of the
> date of that phase.**

---

# Part 0 — The one-sentence history

> **The model developed by subtraction.**

Almost every experiment in this programme **removed** something from the theory or **demoted** it.
Exactly **one** structural object was added (`𝓑`, the boundary object). The kernel gained **nothing**.
The single frozen result is **negative**. *(This is the ledger of the **experimental programme**. A
**governance** decision has since changed the model vocabulary — see the three registers in Part 4.1;
"nothing was added" must not be read as "nothing subsequently changed".)* This is not a disappointing outcome misdescribed as a good
one — a research programme that removes unsupported structure is doing its job, and the removals are
what make the remaining claims worth anything. But it should be stated plainly, because a reader
skimming twenty-four experiment documents could easily form the opposite impression.

---

# Part 1 — The baselines, and why v1.2 is *weaker* than v1.1

The developmental arc does not begin at an experiment. It begins at a **deliberate weakening**.

## 1.1 What v1.2 did to v1.1

| | v1.1 | **v1.2** | direction |
|---|---|---|---|
| layer structure | philosophy and architecture free to interact | **constitutional 4-layer separation**: Philosophy → Epistemic mathematics → DDD → Architecture | **restriction** |
| derivation `Gītā concept → primitive → DDD → architecture` | permitted | **banned** | **restriction** |
| `Observation` | settled | **reopened as `OPEN`** | **demotion** |
| `Zero` | a theory element | **demoted to candidate** | **demotion** |
| `E_t ≠ K_t` | a claim | **demoted to research distinction** | **demotion** |
| `Sat` | two-valued | **three-valued, class-indexed**: `Sat : 𝒦 × ℛ → {⊤,⊥,U}` over **8 requirement classes** | refinement |

**Read the column.** v1.2 is v1.1 with four things taken away and one thing made more careful. The
programme's most important single move happened **before** the first simulation ran, and it was a
retreat. Everything after it inherits that discipline: **the burden of proof sits on the person
adding structure, never on the person declining to.**

## 1.2 The ban that shaped everything

The banned derivation chain is worth isolating, because it is the rule that most often fired:

```
Gītā concept  →  epistemic primitive  →  DDD element  →  architecture     ❌ BANNED
```

A philosophical reading may **motivate** a question, supply a **lens**, or name a **candidate**. It
may never **licence** a primitive, and a primitive may never licence a DDD element by resemblance.
Each arrow has to be earned separately, on its own evidence. Where the Gītā material genuinely
contributed — and it did — it contributed **questions and lenses**, never entities. The most
productive instance was the Zero/Śūnya lens, which produced a *derivation* (§2.5), not a concept.

---

# Part 2 — The developmental arc, phase by phase

Each phase records **what was asked**, **what was found**, and — the column that matters —
**what changed in the model**.

## 2.1 Phase 0 — The corpus and the kernel-reduction lane

**Asked:** which operators are irreducible? Can the kernel be reduced?

**Found:** a capability model, operator contracts, an ablation design, pairwise and randomized
results, alternative kernels, and a falsification pass. Headline: **14 irreducible powers** —
immediately qualified on review to **"relative to the tested capability model and semantic algebra."**

**Changed in the model:** nothing yet, but the qualification set the tone. It is the first instance of
the rule that governs the whole programme: **a result is indexed to the instrument that produced it.**

**Also established here — and later corrected:** the corpus count. An initial `1 155` vs `1 452`
discrepancy traced to `brainstorming/three_model_convergence/` — **325 files, all created
2026-09-01**, a *derived* lane writing into the primary corpus and absent from the exclusion list.
Corrected to **`N_primary = 1 153 @ 2026-09-01 23:02`**. **`MR-1` and all 13 role counts were
unchanged — delta 0.** A count moved; no conclusion did.

## 2.2 Phase 1 — The v1.1 simulation (`A`–`L`)

**Asked:** is v1.1 executable? Does it smuggle? Is it circular?

**Found:** a formal model, type system, scenario catalogue, property catalogue, and four registers —
counterexample, **no-smuggling**, **circularity**, proof obligations.

**Changed in the model:** the *vacuity* problem surfaced. Properties can pass **because nothing
instantiates them**. From this point on every property carries a **witness requirement**: a passing
property must exhibit a case that could have failed. This is a methodological addition that survived
into every later phase and caught several would-be results.

## 2.3 Phase 2 — v1.2 and the factivity obstruction (`B`, `C`, `D`)

**Asked:** is v1.2 executable?

**Found — the programme's first hard obstruction:**

```
(1)  Knows(a,p,c,t) → True(p,c,t)                DEF-1, factivity
(2)  K_{a,c,t} = Γ(E_{a,c,t}, Q_t, C_t, EC_t)    the attribution equation
```

**Jointly unsatisfiable for any total `Γ` that attributes anything.** The witness: two worlds
differing in the truth of `p`, a policy-trusted source reporting identically in both, **bit-identical**
epistemic states by fingerprint over observations, evidence, interpretations, assessments,
determinations, hypotheses and rejections. `Γ` is a function of `E`; it returns the same `K`; the
attribution is true in one world and false in the other.

**The diagnosis is structural, not numerical: truth is not in `Γ`'s domain.** That is a property of
the **domain**, not of the function, which is why no choice of `Γ` escapes it. The policy sweep
confirms: `justified-unique` violates, `justified-any` violates, `none` does not — **by attributing
nothing.**

**At scale:** 420 violations in 10 000 paired worlds; conditional rate **0.1237**; and the
localization is total — **414 of 665** violations occur where a policy-trusted source reported a
falsehood, **0 of 2 607** otherwise.

**Changed in the model:** `Theory v1.2` classified **B — PARTIALLY EXECUTABLE**. `Factivity` becomes
`OPEN` and stays open for the rest of the programme.

## 2.4 Phase 3 — `Sat_c` semantic closure, and the loss at `U` (`F`, `G`)

**Asked:** are the eight requirement classes executable? Is `Sat_c := value ∘ Eval_c` coherent?

**Found:**

- Only **3 of 8** `Sat_c` classes are executable. Three had **no supplied evaluator at all**
  (`Sat_gov`, temporal, operational).
- **`Sat_c := value ∘ Eval_c` is a lossy projection, and the loss is concentrated exactly at `U`** —
  **nine distinct evaluation situations collapse into the single value `U`**.
- **A knowledge state can satisfy all eight classes and still be false.** The satisfaction family has
  **nowhere to attach truth** — visible from the specification alone, before execution. This is an
  independent confirmation of §2.3 from a completely different direction.
- **8 of 10** required negative tests were falsified.

**Changed in the model:** `Sat` classified **SEMANTICALLY INCOHERENT under the model the theory
proposes** — reported as a *specification* finding, not an implementation bug.

## 2.5 Phase 4 — Repairs, and the ordering lesson (`E`, `H`)

**Asked:** can factivity be repaired? Can the `U`-collapse be repaired?

**Found on factivity — three arms:**

| repair | violation | coverage | verdict |
|---|---|---|---|
| baseline | 0.1175 | 0.3320 | the obstruction |
| **R1** rename `K_t` → `A_t` | n/a — 0 knowledge claims | 0.3320 | works |
| **R2** externalize factivity to a verifier | n/a — 0 knowledge claims | 0.3320 | works |
| **R3** partial `Γ` *(as specified)* | **0.1181** | 0.1143 | **REFUTED** — no better than baseline, −66 % coverage |
| R3′ channel consulted | 0.0000 | 0.3497 | **not a repair** — **71.2 %** of attributions bypass the pipeline |

**R1 and R2 are behaviourally identical.** This is the fact that eventually converts factivity from an
experiment into a decision (§2.11).

**Found on the `U`-collapse:** candidate repairs `R_a`…`R_d` tested against three criteria — total,
three-valued, preserving the distinctions. **`R_b` is the only candidate satisfying all three.**
**`R_c` is the trap**: total, three-valued, superficially clean, and it maps a *contradiction* onto the
same value as an *absence*. It would have passed a less careful test.

**The ordering lesson — a self-correction with teeth.** I ran the evaluators **before** the
specification repairs, violating the programme's own ordering. Re-running in the correct order changed
the arity-3 contagion figure from **0.643 → 0.821** — the violation had *understated* the problem.
Zero conclusions were affected, but the ordering rule became explicit and binding from that point:
**specification repairs precede evaluation, always.**

## 2.6 Phase 5 — The boundary object: **the one structural addition** (`I`, `J`, `K`)

This is the only phase that **added** structure, so it deserves the most space.

**Asked:** the `U`-collapse loses nine situations. Where did they go?

**The move.** Instead of enriching the codomain — adding a fourth value, or a fifth — the repair
introduces a **boundary object `𝓑`** and recognizes the existing quantities as **projections of it**:

```
Gap = π_Q(𝓑)          U = π_V(𝓑)
```

**Found:**

- **Nine epistemic conditions indistinguishable in the evaluation codomain remain fully
  distinguishable in `𝓑`.** The information was never destroyed; it was **projected away**.
- **Boundary closure repairs `D-0` in 6 of 6 contradiction-bearing cases — under all three
  contradiction models.** This **corrected Experiment `G`'s** earlier "not separable" verdict.
- **You do not need to choose among three-valued / four-valued / delegated** to stop a contradictory
  state from closing. The choice that looked forced was an artefact of asking a projection to do the
  work.

**The rigging guard, and what it cost me.** With enough types, *any* set of states separates — so the
typing had to be tested for necessity, not just sufficiency:

- **9 of 11 facets separate on membership alone.** Only `G_assessment` and `G_model` require typing.
- **37 of 41 declared distinctions are unnecessary on this suite** — 4 are load-bearing.
- An **exhaustive `2^11` search** found **two minimal typing sets, both of size 3.** My first write-up
  said *"the minimal repair"*; it is **"a minimal repair."**
- My **first parsimony test measured the wrong thing** — merging one type cannot collide states that
  differ in facet *membership*, so "0/35 load-bearing" was an artefact of the test design, not a
  finding. Replaced with facet-untyping plus pairwise-merge tests.

**Changed in the model:** `𝓑` enters as a `[PROP]` structural object with experimental support. It is
**not** adopted into v1.2. It is the strongest candidate the programme has produced.

## 2.7 Phase 6 — Closure is an event, not a state (`L`, `M`, `N`)

**Asked:** is epistemic closure a state of a proposition, or an event in a history?

**Found:** **closure-as-state is refuted by temporal revision; closure-as-event is not.** And the
decisive structural check: **no operation, rule or policy in the corpus reads whether a proposition
was previously closed** — **0 reads, statically verified.** The kernel **writes** history and **never
reads it.**

The commissioned decisive test was exact: `K_t, K_{t+1}` identical **∧** `History_1 ≠ History_2`. It
passes. Two systems can be in the same epistemic state with different histories, and nothing in the
kernel can tell.

**Also found (`N`, the Yoni-Zero cycle test):** **the cycle does not terminate — and the
specification's stated reason for termination is false.** **Completeness cannot be claimed while the
criterion for completeness is undefined.**

**Changed in the model:** nothing adopted. `EpistemicClosureEvent` is a `[PROP]`; per the layer ban,
the metaphor that suggested it is **not** placed in the domain model.

**And the principle that came out of it, which is the most portable thing in this programme:**

> **Epistemic change does not imply epistemic progress.**

## 2.8 Phase 7 — Multiplicity, selection, and the invisible failure (`O`, `P`)

**Asked:** what happens when many candidates compete for one determination?

**Found — three results that belong together:**

1. **The winner's curse.** Apparent quality rises monotonically **0.00 → 0.62**. **Real quality never
   moves from zero.** Optimism scales as `σ√(2 ln n)/√m`. **More candidates does not merely fail to
   improve determination — it actively degrades it while appearing to improve it.**
2. **The multiplicity–evidence coupling.** Truth is *excluded* unless `m > (z_{1-α/n} σ / signal)²`.
   At `n = 1000`, `m = 40`, `signal = 0.6`, the true candidate **cannot** be selected — not
   "is unlikely to be"; **cannot**. Since `z² ~ 2 ln n`, evidence must grow logarithmically in the
   candidate count merely to stand still.
3. **Selection and validation can agree and both be wrong.** A decoy passed validation at **0.9849**.
   An **independent channel** detects it **99.3 %** of the time; the **same channel** detects it
   **0.75 %**.

**Changed in the model:** nothing structural — but three standing constraints, and one wording
correction I had to accept. I wrote *"the fix is evidence, not thresholds"*; the accurate statement is
**"multiplicity control cannot be obtained for free"** — seven remedies exist and only one was tested.
Similarly *"validation cannot repair a misleading channel"* is true only for **same-channel**
validation.

**The concept that emerged:** `ChannelRelation(c₁,c₂)`. **Declaring a channel is not establishing
channel independence** — and KnowledgeOS currently cannot state the difference.

## 2.9 Phase 8 — `N_eff`, and the first frozen result (`Q`, `R`, `S`)

**Asked:** if 1 000 candidates are not 1 000 independent chances, how many are they?

**Found (`Q`) — effective hypothesis complexity is measurable:**

| structure | measured `N_eff` |
|---|---|
| 1 000 independent | 940.6 |
| 1 000 in 50 groups | **51.1** — *identical `τ = 3.097` to a 50-candidate independent space* |
| 1 000 in 200 groups | 201.9 |
| equicorrelated ρ=0.5 | 200.6 |
| equicorrelated ρ=0.9 | **9.0** — *1 000 candidates behave like nine* |

**Found (`R`) — and then frozen as `FR-001`:**

> **Pairwise semantic/evidential distinguishability cannot carry family-level epistemic complexity by
> itself.**

Two **independent** mechanisms, which is why it was strong enough to freeze:

1. **The relation is not an equivalence relation.** A sorites/chain construction shows tolerance
   relations are **non-transitive**; `a ~ b` and `b ~ c` with `a ≁ c`. Quotienting is unavailable.
2. **The transitivity-free repair fails on coupling.** δ-packing overestimates by up to **222×**.

**And a self-correction that made the result better.** I asserted *"no closed form fits"* **before
reading the error column**. `n(1−ρ)²` then fitted at error 0.082. Widening the sweep to `ρ ∈ [0.05,
0.95]`, `n ∈ [200, 5000]` **refuted it** — ratio 0.34–3.10. The original claim was restored, but this
time **earned**. Asserting before checking produced the right answer for the wrong reason, which is
indistinguishable from luck.

**Changed in the model — and here the governance discipline had to be corrected too.** I proposed the
freeze; the review sharpened it: **`FR-001` is a research-result freeze, NOT a theory or architecture
decision.** Freezing means *"stop re-litigating this"*, not *"promote this."* Theory v1.2 is
unchanged; the finding stays `[NEG]`/`[OPEN]`. Five standing consequences `C-1…C-5` carry forward, of
which the load-bearing one is:

> **C-1 — do not put statistical mechanisms into the kernel merely because KnowledgeOS can use them.**

## 2.10 Phase 9 — Hilbert space: cleaner coordinates, same obstructions (`T`)

**Asked:** does a Hilbert/spectral representation supply the joint object `FR-001` found missing?

**Found: no — and instructively no.**

| route | value at ρ=0.5 (measured 200.6) | error |
|---|---|---|
| pairwise δ-packing | ~1 000 | **222× over** |
| spectral participation ratio | **4.0** | **50× under** |

All four tested spectral functionals (participation ratio, spectral entropy, trace/λ_max, rank) are
**exact on block structure** and **fail on equicorrelation**. The two routes err in **opposite
directions**.

Other hypotheses: **H8 refuted** — ε-distance reproduces the `FR-001` transitivity failure. **H3
refuted** — `X ~ U{−1,0,1}`, `Y = 1[X=0]` has `Cov = 0` and deterministic dependence, so inner-product
geometry cannot see the dependence that matters. **H1 refuted** — `v + (−v) = 0` collapses
Contradiction onto Absent, **the same 9→`U` collapse from Phase 3, in new coordinates.** **H9
partial** — invariant under rotation, **not** under per-candidate rescaling, which KnowledgeOS admits.

**The adjudication:** **Hilbert space did not solve the KnowledgeOS obstructions; it reproduced
several of them in a mathematically cleaner representation.**

**Changed in the model:** nothing. Kernel **NOT SELECTABLE**, consistent with `C-1`. `FR-001`
**strengthened, not reopened.**

**And a claim withdrawn.** I wrote that the true burden *"lies BETWEEN"* the two constructions. That
is **not a mathematical result**: "between" presupposes a formally defined ordering on candidate
functionals **and** a proof that the sought functional lies in that interval, and neither exists. It
had already propagated into the **frozen register** — the worst possible destination for an unearned
claim. Withdrawn from both sites. What stands is narrower and is **`[EXP]`, not frozen**:

> Under the tested correlation-family regimes, the measured effective multiplicity burden is not
> represented by pairwise δ-packing and is not represented by the tested standard spectral functionals.

## 2.11 Phase 10 — Factivity leaves the experimental track (`U`)

**Asked:** what next on factivity?

**Found: that this is no longer a question experiments can answer.** `R1` and `R2` are
**behaviourally identical** — 3 320 attributions, 390 false, 0 knowledge claims in both. They differ
in **what the system claims**, not in **what it does**. No simulation can separate them, because the
difference is not in the behaviour.

**Changed in the model:** factivity is reclassified from *open research question* to **open
decision**, with a brief supplying options `R0`/`R1`/`R2` (`R3` refuted), their costs, and the five
things a decision must state. **The lane does not adjudicate its own work.**

Two constraints the brief carries that were not visible from inside the factivity work alone:

- **From Phase 7:** if `R2` is chosen the verifier must be on an **independent channel**, or it adds
  nothing (0.75 % vs 99.3 %). So `R2` silently commits KnowledgeOS to representing `ChannelRelation`,
  which is unbuilt. **This is the programme's clearest instance of one experiment constraining an
  unrelated decision.**
- **No option removes the 390 false attributions.** A system wrong 11.75 % of the time is **not
  improved by relabelling its output; it is described more honestly.** The decision fixes *what may be
  claimed*, never *how often the system is right*.

---

# Part 3 — The recurring diagnosis

One diagnosis fired **five** times across otherwise unrelated experiments:

> **The programme repeatedly defined a *projection* and then asked it to do the work of the
> *structure* it projects from.**

| # | the projection | the structure it projects from | where |
|---|---|---|---|
| 1 | `Sat_c = value ∘ Eval_c` | the evaluation situation | Phase 3 |
| 2 | `U` | `𝓑` — the boundary object | Phase 5 |
| 3 | `Gap` | `𝓑` | Phase 5 |
| 4 | pairwise distinguishability | the family-level joint object | Phase 8 |
| 5 | spectral functionals of `Σ` | the full joint/tail structure | Phase 9 |

**Why this matters more than any individual result.** A projection is *cheaper* than the structure it
comes from — that is what makes it attractive, and what makes the substitution so easy to miss. Each
time, the projection was **adequate on the easy cases** and failed on exactly the cases that mattered:
blocks fine / coupling wrong; absence fine / contradiction wrong.

**One correction on this point.** The claim "`Gap = π_Q(𝓑)` loses information" is true, but I first
described the loss as *cardinality* loss. It is **attribute loss** — the projection discards `kind`
and `remediability`. The distinction matters because a cardinality-preserving projection can still be
lossy, which is precisely the case that fooled Phase 3.

---

# Part 4 — Development by subtraction: the ledger

**Added to the model — the complete list:**

| object | status |
|---|---|
| **`𝓑`**, the boundary object, with `Gap = π_Q(𝓑)` and `U = π_V(𝓑)` | **`[PROP]`** — supported, **not adopted** |

**That is the entire addition column — for the experimental programme.**

## 4.1 Three registers, which must not be collapsed

Added **after** the trajectory above, so that "nothing was added" is not misread as "nothing
subsequently changed":

| register | what changed | by what authority |
|---|---|---|
| **experimental structural additions** | **`𝓑`** — and nothing else | evidence |
| **governance decisions affecting model vocabulary** | **`R1`: `K_t` → `A_t` (AttributedState)** | **decision** |
| **kernel** | **unchanged · NOT SELECTED** | neither |

**Why the separation is load-bearing.** `𝓑` is supported by experiment and **still not adopted** —
evidence does not adopt. `R1` is adopted and was **never** supported by experiment over `R2` —
the two were behaviourally identical, so no evidence could favour either. **The two rows were produced
by different faculties and neither can substitute for the other.** A reader who merges them will
conclude either that experiments adopt things or that decisions are evidence-backed; both are false.

**Derived (earned, not assumed):**

| result | evidence |
|---|---|
| `Zero_strict ⇒ Zero_reasoned ⇒ Zero_weak` | **DERIVED** — 1 620 000 assignments, **0** counterexamples, **partition-independent** |
| `Zero_kleene` as three-valued companion | consistent |
| `custody(I, 𝒦) = { o ∈ 𝒦 : I fails in 𝒦 \ {o} }` | invariant custody, well-defined |
| `Truth ⟂ Closure` | **ESTABLISHED** — a state closes while its attribution is false |
| kernel writes history, never reads it | **0 reads, statically verified** |

**Removed, demoted, or refuted:**

`Observation` (reopened) · `Zero` as theory element (→ candidate) · `E_t ≠ K_t` (→ research
distinction) · the `Gītā → primitive → DDD` chain (banned) · closure-as-state (refuted) · `R3` partial
`Γ` (refuted) · `R_c` (trap) · `n(1−ρ)²` (refuted on widening) · H1, H3, H7, H8 (refuted) · 5 of 8
`Sat_c` classes (not executable) · 37 of 41 typed distinctions (unnecessary on suite) · "the answer
lies between them" (withdrawn) · "the minimal repair" (→ *a* minimal repair) · "`N_eff` is real"
(→ scope-bound) · "14 irreducible powers" (→ instrument-relative).

**Net change to Theory v1.2 across all twenty-four experiments: none.**

---

# Part 5 — What actually developed: the method

The model barely moved. **The method matured substantially**, and that is the honest headline.

| discipline | forced by |
|---|---|
| **witness requirement** — a passing property must exhibit a case that could have failed | Phase 1 vacuity audit |
| **specification repairs precede evaluation** | my ordering violation, Phase 4 |
| **anti-rigging** — necessity tests, not just sufficiency; exhaustive search where feasible | Phase 5 typed facets |
| **instrument-relativity** — every result indexed to the model that produced it | Phase 0 "14 powers" |
| **freezing ≠ promotion** | `FR-001` review |
| **evidence ≠ decision** — the lane supplies, never adjudicates | `FR-001`, then factivity |
| **supported ≠ established** | H1–H12 review |
| **check before asserting** | the `n(1−ρ)²` episode |
| **status vocabulary** `[CORPUS] [EXT] [INF] [PROP] [EXP] [NEG] [OPEN] [DEF] [MODEL] [HYPOTHESIS]` | throughout |

> **The governing rule, and the one that did the most work:**
> **The strongest statement made must never exceed the strength of the available evidence.**

## 5.1 The self-correction register

Ten corrections were caught and applied at source. They are listed here because a development history
that omits them is not a development history:

1. Two evaluators (`Eval_Gov` authority table, `Eval_Time` interval coverage) **invented semantics** —
   retracted as corpus-unsupported; `A4` forbids equating timestamps with temporal validity.
2. The `Zero_reasoned`/`Zero_weak` separation was **an artefact of those evaluators**; once retracted
   the readings agree on all 7 suite cases.
3. Evaluators run **before** specification repairs — understated contagion, `0.643 → 0.821`.
4. A confidence interval **on the wrong quantity** — the correct analysis is McNemar `b = 5944,
   c = 0`, conditional rate **1.0000 deterministically**; only the generator's guard-activation rate
   has sampling error.
5. The parsimony test **measured the wrong thing** — "0/35 load-bearing" was a test-design artefact.
6. **"The" minimal repair → "a"** minimal repair — two minimal sets exist.
7. **Asserted before checking** — the `n(1−ρ)²` episode.
8. **Cardinality loss → attribute loss** for `π_Q`.
9. **Progress-claim false positives** — first run reported "84 % of 2 254"; **461 hits were the word
   "convergence" in another lane's directory name.** Corrected to **83.2 % of 1 793**.
10. **"Lies between" withdrawn** from `T` **and from the frozen register**.

Plus a formatting defect: `T-hilbert-space-representation.md` was written with a spurious uniform
4-space indent on **129 of 187 lines**, which renders every table as a code block. Fixed.

---

# Part 6 — Where the model stands

| | |
|---|---|
| **Theory** | **v1.2 — unchanged.** No v1.3. |
| **Executability** | **B — PARTIALLY EXECUTABLE** |
| **Kernel** | **NOT TESTED**, and under v1.2 not testable. **Gained nothing** across the programme. |
| **`Sat`** | **SEMANTICALLY INCOHERENT** under the model the theory proposes |
| **Frozen** | **1** — `FR-001` · **1 candidate `[EXP]`, not frozen** (Phase 9) |
| **Strongest unadopted candidate** | **`𝓑`**, the boundary object |
| **`Factivity`** | **DECIDED — `R1`.** `K_t → A_t`; `Knows → True` retained as an **external factive** assertion; **Verification separate** |
| **Blocked on** | **nothing** — the decision gate is cleared |
| **Queue** | **`Contr` + evaluation domain** (next experiment) → `⪰` |
| **Designed, not run** | `KR-EXTREME-2026-09` — **must not precede the queue** |

## 6.1 The current research position

```text
Theory v1.2
    │
    ├── Factivity
    │      └── DECIDED: R1
    │             K_t → A_t (AttributedState)
    │             Knows → Truth remains externally factive
    │             Verification remains separate
    │
    ├── Contr + evaluation domain
    │      └── NEXT RESEARCH EXPERIMENT
    │
    ├── ⪰
    │      └── OPEN, independent
    │
    ├── ≡sem / identity
    │      └── OPEN
    │
    ├── δ
    │      └── OPEN
    │
    ├── lifecycle / retirement
    │      └── OPEN
    │
    ├── composition / reduction
    │      └── OPEN
    │
    └── Kernel
           └── NOT SELECTABLE
```

## 6.2 What `R1` does and does not do

> **`R1` removes one specific unsatisfiable formulation as a blocker. It does not solve the downstream
> theory.**

**It does:** dissolve the joint unsatisfiability of `DEF-1` and the attribution equation, by making
the kernel's object an **attribution** rather than a knowledge claim. Factivity survives intact —
**externally**, where truth is actually available — instead of being demanded of a function whose
domain excludes it.

**It does not:** make the remaining semantic questions any easier. **`Contr` and the meaning and
domain of evaluation still have to be earned**, on evidence, exactly as before. `Sat` remains
**semantically incoherent** under the model the theory proposes; 5 of 8 classes remain non-executable;
the `U`-collapse is repaired only by a `[PROP]` object that is **still not adopted**.

**And it does not touch the error rate.** Under `R1` the system makes **3 320 attributions of which
390 are false**, and **0 knowledge claims**. Those 390 do not go away — the decision fixes **what may
be claimed**, never **how often the system is right**. Anyone reading `R1` as an improvement in
accuracy has read it exactly backwards: it is an improvement in **honesty**.

**Open items:** `Contr` + fourth value · `⪰` · `≡_sem` · `δ` · the retirement relation · the
composition rule/level · 5 facet gaps · `N_eff` · class/reason exhaustiveness. **`Factivity` is no
longer among them.**

**Two things to keep separate, on review instruction:** `N_eff` and `⪰` are **independent** research
problems — the claim that `N_eff` presupposes `⪰` is **not established**, there are two independent
paths, and they must stay independent. And **`≡_sem` is not refuted**; it must not be asked a question
it was not designed for.

---

# Part 7 — Where the artifacts are

## 7.1 Written record — three lanes

**`docs/knowledgeos/research/theory-v1.2-simulation/` — 24 files, the primary lane**

| file | what it holds |
|---|---|
| `00-INDEX.md` | lane index |
| `A`–`D` | executive result · model & experiments · status classification · gap register |
| `E` | factivity repair (R1/R2/R3) |
| `F` | `Sat_c` semantic closure |
| `G-evaluation-semantics-experiment.md` · `G-rerun-with-evaluators.md` | **ID collision — reported, not resolved** |
| `H` | repair phase, re-ordered rerun · Zero chain derivation |
| `I` · `J` · `K` | **the boundary object** · boundary separation · typed facet vocabulary |
| `L` · `M` · `N` | closure as event · kernel vs history · Yoni-Zero cycle test |
| `O` · `P` · `Q` · `R` | standing checks · multiplicity & selection · `N_eff` · **`FR-001`** |
| **`S-frozen-results-register.md`** | **authoritative for the freeze itself** |
| `T` | Hilbert-space representation |
| **`U-factivity-adjudication-brief.md`** | **the open decision** |
| `FINAL-VERDICT.md` | lane verdict |

**`docs/knowledgeos/research/theory-v1.1-simulation/` — 14 files:** `00-INDEX`, `A`–`L`,
`FINAL-VERDICT.md`. Formal model, type system, scenario/property catalogues, and the counterexample,
**no-smuggling**, **circularity** and proof-obligation registers.

**`docs/knowledgeos/research/kernel-reduction/` — 22 files:** `00-INDEX`, `01`–`19`,
`FINAL-kernel-reduction-report.md`. Research question, **evidence matrix** (corpus counts), capability
model, operator contracts, ablation/pairwise/randomized results, DDD analysis, alternative kernels,
falsification, negative results, open questions, protocol audit.
*One foreign file — `Yes. I read the full attached document,` — was written there by a concurrent lane
at 22:56 and is flagged in that index; it is not this lane's output.*

## 7.2 Code

**`research/knowledgeos-sim/kos12/` — 24 modules (v1.2), by experiment:**

```
experiment.py · sat3.py · relations.py     →  -B      repair.py                    →  -C
satc_spec.py · phaseB.py · phaseC.py       →  -D      evaluators.py · rerun.py     →  -E
repairs.py                                 →  -F      evalc.py · expG.py           →  -G
zerolens.py · zeroH.py · zeroI.py          →  Zero    closure.py · history.py      →  L/M
yonizero.py                                →  N       category_check.py            →  checks
m2o.py · neff.py · distinguish.py          →  P/Q/R   hilbert.py                   →  T
```

**`research/knowledgeos-sim/kos/` — 12 modules (v1.1):** `world · state · types · transitions ·
scenarios · properties · inquiry · factivity · adversarial · audits · randomized`.

**Runners:** `run_experiment.py` · `run_v12.py` · `run_satc.py`.

**`research/kernel-reduction/kr/` — 12 modules:** `atoms · capabilities · operators · properties ·
scenarios · carriers · reach · ablate · variants · audit_variants · infotheory`. Runner: `run_all.py`.

## 7.3 Machine-readable results

| directory | files | holds |
|---|---|---|
| `research/knowledgeos-sim/results/zero/` | 7 | Zero-lens chain, 1 620 000 assignments |
| `…/evaluation/` | 9 | evaluator runs, contagion, `U`-collapse |
| `…/closure/` | 4 | closure-as-event |
| `…/history/` | 2 | kernel-vs-history, the 0-reads check |
| `…/m2o/` | 5 | winner's curse, multiplicity–evidence coupling, decoy/channel |
| `…/neff/` | 4 | `N_eff` calibration |
| `…/dist/` | 2 | `FR-001` |
| `…/hilbert/` | 3 | `h7_spectral` · `h3_h8_fr001` · `h1_h6_h9_h12` |
| `…/yz/` | 2 | Yoni-Zero cycle |
| `…/audit/` | 5 | audits |
| `research/kernel-reduction/results/` | 13 | ablation, pairwise, randomized, variants |

*Two scratch files — `smuggling_probes.json`, `variant_summary.json` — remain in the
kernel-reduction results directory; their cleanup was denied by the sandbox and they are documented in
that directory's README.*

## 7.4 Syntheses in this folder

| file | what it is |
|---|---|
| `20260902-122338-what-eleven-experiments-established-a-synthesis.md` | the mid-programme synthesis |
| `20260902-132000_proposed-optimized-model-structures-first-projections-derived.md` | the structure-first proposal |
| `20260902-142217_the-frozen-model-what-is-settled-and-where-the-artifacts-are.md` | **the snapshot** |
| **this document** | **the trajectory** |

*Commissioning protocols and reviews also live in this folder, timestamped. They are **inputs**, not
this lane's output, and are not edited by it.*

## 7.5 Reproduction

```bash
cd research/knowledgeos-sim && python run_v12.py      # v1.2 chain
cd research/knowledgeos-sim && python run_satc.py     # Sat_c phases
cd research/kernel-reduction && python run_all.py     # kernel reduction
```

Seeds are fixed and recorded in each result JSON.

## 7.6 Session and state record

`.claude/sessions/2026-09-02.md` — appended after every experiment, in order, including the
self-corrections · `.claude/CONTEXT.md` — active-track block, carrying the **stop on further
simulation ahead of the factivity decision**.

---

# Part 8 — What development is still owed

**Item 1 was the factivity decision. It is DECIDED — `R1`** (see Part 6). The list below is what
remains, renumbered.

1. **`Contr` and the evaluation domain — the next research experiment**, with the four `Zero` readings
   extended in the same step: five open items, one decision. The next experiment **must supply a case
   that separates the three contradiction models**; the current deterministic suite cannot. This is
   now the head of the queue, and it is a **research** question, not a decision.
2. **The `R1` rename, carried through.** `K_t → A_t` must propagate to `Δ_t`, `Zero`, adequacy, the
   kernel definition and `I1`–`I9`, and **no KnowledgeOS component may assert `Knows`** — the factive
   assertion lives outside. Until the propagation is done, those artifacts still carry the old
   vocabulary. **This is engineering work consequent on a decision, not research.**
3. **`⪰`** — independently, not as a corollary of `N_eff`.
4. **`𝓑`'s status** — the strongest candidate the programme produced, still `[PROP]`. **Adoption is a
   decision, not an experiment** — the same shape as factivity, and it should be adjudicated the same
   way rather than waiting for evidence that cannot arrive.
5. **`ChannelRelation(c₁,c₂)`** — surfaced by Phase 7, unbuilt. **It is not a precondition of `R1`**,
   which keeps Verification separate without committing to a channel-consuming verifier. The
   constraint stands **against any future verification component that reads evidence channels**:
   same-channel validation catches a misleading source **0.75 %** of the time versus **99.3 %**
   independent, so such a component is worth building only if its independence can be stated.
6. **`KR-EXTREME-2026-09`** — designed, not run. Its decisive check is sharper than a formula hunt:
   **do two covariance structures exist with the same spectrum but different extreme-value
   behaviour?** If yes, Phase 9's failure is the **entire spectral level** failing, not the four
   functionals chosen.

**Do not**, per standing constraints: implement `Sat_c`, run another randomized layer, invent `Contr`
or `⪰` as test fixtures, start `KR-EXTREME` ahead of the queue, or promote `𝓑` without a decision.
**The blanket stop on further simulation is lifted** — it was conditional on the factivity decision,
which has been taken. `Contr` may proceed.

---

> **The governing principle is unchanged, and survives the decision intact:**
>
> ## **The strongest statement made must never exceed the strength of the available evidence.**
>
> `R1` is not a counterexample to it. `R1` was **not** made on evidence — no evidence could favour it
> over `R2` — and it does not claim to have been. It was made on **authority**, openly, and recorded
> in a different register for exactly that reason.
