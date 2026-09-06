# What Eleven Experiments Established

### A synthesis for the KnowledgeOS research programme — mathematics, statistics, and domain design

**Date** 2026-09-02 · **Status** `[EXP]` `[INF]` — a research synthesis. **Nothing here is canonical
KnowledgeOS architecture**, and nothing below promotes any prior result.
**Placement note:** written to `brainstorming/` as directed, rather than resolved through
`scripts/doc-placement.php`.

**Scope.** Eleven experiments run 2026-09-01/02: the kernel-reduction lane (`KR-2026-09-01`), the
Theory v1.1 simulation, and the nine-step v1.2 chain (`-B` … `-I`). Full artifacts:
`docs/knowledgeos/research/{kernel-reduction, theory-v1.1-simulation, theory-v1.2-simulation}/`;
code and machine-readable results under `research/{kernel-reduction, knowledgeos-sim}/`.

---

# Part I — The one thing worth taking away

Read as a sequence, the eleven experiments look like eleven separate problems: factivity, `Zero`'s
ambiguity, `Sat_c`'s incoherence, contradiction, composition, closure. They are not. **They are one
mistake, appearing five times.**

> **The programme has repeatedly defined a projection and then asked it to do the work of the
> structure it projects from.**

| the structure | the projection defined instead | where it broke |
|---|---|---|
| `Eval_c(K,r,Γ) → EVal` | `Sat_c := value ∘ Eval_c` | `-G`: nine evaluation situations collapse into `U` |
| the boundary `𝓑` | `Zero ⟺ Δ = ∅` | `-B`: three non-equivalent readings; `-G` D-0: a contradictory state closes |
| `𝓑` again | `Gap` | `I`: `Gap = π_Q(𝓑)`, attribute-lossy in *kind* and *remediability* |
| `ZeroLens` | `DetectGap` | `-A`: derivable 12/12; `I`: recoverable as `π_Q(ZeroLens(…))` |
| the epistemic state `E_t` | `K_t = Γ(E_t,…)` | v1.1: **`Γ` cannot be factive**, because its domain excludes truth |

Each time, the projection was treated as the primary object; each time, the information the theory
needed was in the kernel of the projection. The **factivity impossibility is the same shape**: `Γ`
projects `E` onto `K`, and truth is not in `E`.

This is a **structural diagnosis, not a metaphor**, and it is what the eleven experiments jointly
establish. It also tells us what to do, which Part V takes up.

---

# Part II — Mathematical assessment

## 1. What is established

**M-1. `DEF-1` factivity and `K = Γ(E,Q,C,EC)` are jointly unsatisfiable.** `[EXP]`
Witness: two worlds differing in truth produce a **bit-identical** epistemic state; `Γ`, being a
function of `E`, returns identical `K`, true in one and false in the other. Policy sweep: the only
escaping `Γ` attributes nothing. Reproduced at scale — 420 violations / 10 000 paired worlds, and
`-D` later showed **no `Sat_c` class requires factivity**, so the family has nowhere to attach truth.
*Not established: that KnowledgeOS should be non-factive.*

**M-2. Minimality is representation-relative.** `[EXP]`
`|K_min| = 13` under one algebra, `8` under another. These are not conflicting measurements — they
are solutions to different optimization problems. **A minimality claim without a fixed admissible
algebra is not a claim.**

**M-3. `value ∘ Eval_c` is lossy, concentrated at `U`.** `[EXP]`
`T`, `F`, `C`, `UNDEFINED` each receive one distinct evaluation situation; **`U` receives nine**,
spanning `[OPEN]`, `[PROP]` and `[NEG]` statuses. The distinction the theory most needs — epistemic
insufficiency vs theory incompleteness vs context absence vs world unobservability — is exactly what
the projection destroys.

**M-4. The `Zero` chain is derived.** `[DEF]`
`Zero_strict ⇒ Zero_reasoned ⇒ Zero_weak`, proved in two lines and machine-checked over 1 620 000
assignments including **every** choice of which reason-buckets close: **0 counterexamples**. It is
independent of the reason partition — which matters, since the partition has already been revised
twice.

**M-5. Blocked-class contagion.** `[EXP]`
Under Kleene conjunction with five of eight evaluator classes undefined, **every composite
requirement of arity ≥ 4 is permanently `U`**. Supplying three candidate evaluators halved low-arity
contagion and changed nothing at arity ≥ 7. The residual blockage is attributable to exactly two
classes: `status` (`⪰` undefined) and `consistency` (`Contr` undefined).

**M-6. The boundary preserves what the codomain destroys.** `[EXP]`
Nine adversarial epistemic conditions are pairwise distinct at the boundary and **all project to the
same `U`**. Closure over `𝓑` blocks a contradictory state where all four value-based `Zero` readings
close, and does so **identically under all three contradiction models** — so the contradiction-model
choice is *unnecessary for closure*.

## 2. What is not established, and should stop being asserted

| claim | status |
|---|---|
| any operator is a KnowledgeOS primitive | **not established** — `DetectGap` is the only one settled, and settled *negatively* |
| the eight requirement classes are exhaustive | **untestable** — no enumeration procedure exists |
| the reason vocabulary is exhaustive | **`[OPEN]`** — enumeration cannot be proved complete; only derivation from the `U`-skeleton could be |
| `Zero ∈ 𝒦` | **NOT TESTED** |
| the boundary is formally complete | **`[OPEN]`** — 37 of 41 typed distinctions rest on provenance alone |

## 3. The two blockers, and that they are one

`⪰` (the epistemic-status ordering) and `Contr` (the contradiction predicate) are the entire residual
blockage of `Sat`. They are **not independent**: the only contradiction repair that is total,
three-valued and preserves *absence ≠ negation* routes `Sat_content` through `Sat_consistency`, and
`Contr` carries the fourth-value question. **Four open items are one decision.**

---

# Part III — Statistical assessment

## 1. The governing fact

> **Model uncertainty dominates sampling uncertainty by a wide margin, and the programme's
> instruments were calibrated the wrong way round.**

Across 150 000 randomized trials the suite produced **exactly one** non-vacuous result. The central
theorem of the whole chain was established by **two worlds**. Seven of the eleven experiments used no
randomization at all and produced more.

## 2. Four methodological corrections, each earned by an error

**S-1. Vacuity is mandatory.** A property whose antecedent never activates passes meaninglessly.
Removing `Validate` drove `P10`'s guard to 0.00, so "no failures" meant "cannot fail". Every
reported rate must carry its **guard-activation rate**.

**S-2. Put the interval on the quantity that has sampling error.** I published
*"P10 fails at .5944, 95 % CI [.5847,.6040]"*. Wrong: given the world and the operator set the
outcome is **deterministic** — `b = 5944`, `c = 0`, conditional rate exactly `1.0000`. The only
sampled quantity was the **generator's** guard-activation rate. The corrected statement is *stronger*
than the published one.

**S-3. Declare the design.** The fifteen arms shared one world stream — an exactly **paired** design,
10 000 worlds × 15 arms, not 150 000 independent observations. Paired is the *right* design for arm
comparison; reporting independent-binomial intervals over it was the error.

**S-4. Seed reproducibility is not robustness.** Identical failure sets across five seeds shows
determinism given a seed. It establishes neither `∀ seed` nor robustness to **generator
specification** — and the latter is where all the uncertainty actually lives.

## 3. Consequence for experimental design

Randomized testing is the wrong instrument for **semantic discrimination**. It measures how often a
chosen generator produces a situation; it cannot tell you whether two epistemic conditions are the
same condition. Use **adversarial deterministic cases, exhaustive enumeration over small structured
spaces, and witnesses** — the three that actually produced results here:

* 2 worlds → the factivity theorem;
* 1 620 000 exhaustive assignments → the `Zero` chain;
* 2¹¹ exhaustive facet subsets → the two minimal typing sets.

---

# Part IV — Domain-design assessment

## 1. What is working

**D-1. The constitutional four-layer separation.** v1.2's ban on
`philosophy → mathematics → primitive → DDD object → architecture` held under pressure. `Buddhi`,
`Sārathi` and `Zero` are lenses; the Gītā tiering placed **zero** new primitives, and the one item
that reached the kernel question (`Zero`-as-non-collapse) was tested rather than promoted.
**`corroboration ≠ derivation` is doing real work.**

**D-2. The distinctions the theory declares are operationally real.** Under adversarial test:
`Evidence ≠ Knowledge` · `Determination ≠ Knowledge` · `Reject ≠ Accept` · the four-way unknown
taxonomy · and the full decision lane `Determination → Proposal → Decision → Authorization → Action`,
each adjacent pair separated by an explicit counterexample. Zero fabrications in 10 000 trials.

**D-3. Three notions of necessity, kept apart.** Formal (no composition reproduces it), functional
(the capability fails without it), and domain (a distinct responsibility) **disagree** — for
`Discriminate`, `Select` and `Revise`. Collapsing them would have produced a confident and wrong
kernel.

**D-4. Invariant custody.** A reduction can shrink `|𝒦|` while *increasing* the number of operators
whose removal breaks an invariant: eliminating `Challenge` moved custody of "a verdict must be
challengeable" from **one** operator to **two**. Cardinality minimization is blind to this.

## 2. What should be improved

**I-1. Symbol discipline.** Ten notation collisions. The worst: **`E` denotes both Evidence (DEF-7)
and EpistemicState (v1.1)** — inside the very correction whose purpose is to separate them. Also
`P` ×3, `K` ×3, `H` ×3, `I` ×3. This is cheap to fix and expensive to leave.

**I-2. Specification defects hide until adversarially attacked.** `Sat_content` is not a function on
contradictory states; the reason vocabulary was incomplete in 2 of 32 cells; operational evaluation
is not well-founded. **All three were invisible until a case was built to break them, and all three
would have been concealed by implementation.** Specification-before-implementation is not a
preference here; it is the only reason these were found.

**I-3. Over-specification is as dangerous as under-specification.** The typed facet vocabulary has
**37 of 41 distinctions doing no work** on the suite that motivated it. Each has a provenance; none
has evidence. A vocabulary large enough to separate anything separates nothing.

**I-4. Corpus hygiene.** A derived analysis lane writes its output into the primary corpus
(325 files, all one day), and the exclusion rule is a hard-coded folder list that cannot see a lane
created after it was written. Provenance should be a **property of the artifact**, not its directory.
Every corpus count must carry a timestamp: the primary corpus grew ~1.7 % during a single session.

**I-5. Sequencing.** Running a measurement before repairing the specification it measures biases the
measurement toward the unrepaired specification's optimism — demonstrated: contagion at arity 3 was
understated `0.643` vs `0.821`.

**I-6. The status vocabulary needs one more term.** It cannot express *"refuted as reading A,
surviving as reading B"*, which is exactly `Zero`'s situation: refuted as a *state* and as a
*missingness representation*, surviving as a *derived view*.

## 3. What the experiments say about the kernel question

> **The kernel cannot be selected, and the reason is not that the work is unfinished.**

Kernel membership is a question about *operations*; operation semantics (`δ`), equality (`≡, ≈, ≅_λ`)
and the closure predicate are all open, and `-B` showed even the closure predicate is three-way
ambiguous. `THM-10` says cardinality is representation-relative. **A kernel selected now would be a
selection of a representation, disguised as a discovery about knowledge.**

---

# Part V — Where the research should go

## 1. The reframing the evidence supports

```
   ABANDON :  define the projection, then try to recover the structure from it
   ADOPT   :  define the structure, then derive the projection
```

Concretely, and in dependency order:

| define the structure | and the projection becomes derived |
|---|---|
| `Eval_c(K,r,Γ) → EVal` | `Sat_c := value ∘ Eval_c` |
| the boundary `𝓑(K,I,Γ,L)` | `Gap`, `Zero`, `U` |
| the epistemic state's **type** | `K_t = Γ(E_t,…)` |

Every one of the five failures in Part I is an instance of the abandoned direction.

## 2. The next four experiments, in order

**N-1 — decide factivity. It is a decision, not an experiment.**
Three repairs were tested; **R1 (rename `K_t`) and R2 (externalize factivity) are behaviourally
identical**, and R3 as specified does not repair anything. R2 additionally makes an otherwise
invisible number measurable (88.25 % of the kernel's claims survived external verification). The
remaining choice is architectural: *may any KnowledgeOS component assert `Knows`?* **Nothing
downstream can be settled until this is answered**, because `K_t` appears in `Δ_t`, `Zero`, adequacy,
the kernel and `I1–I9`.

**N-2 — define `Contr`, and the fourth value with it.**
It unblocks `consistency`, unblocks `content` (whose only clean repair routes through it), carries
the codomain question, and — per D-0 — **any codomain extension must extend the `Zero` readings in
the same step**, or closure silently swallows the new value. Four open items, one decision.

**N-3 — define `⪰`.**
With `Contr`, it is the entire residual contagion. Both are currently *used* by the specification and
*defined* nowhere.

**N-4 — test the 37 unsupported typed distinctions, or drop them.**
Each needs a state that would collapse without it. Where no such state can be built, record the
distinction as **unsupported** rather than carrying it silently.

## 3. What to stop doing

* **Stop running large randomized simulations.** 150 000 trials produced one non-vacuous result.
* **Stop implementing before the specification survives adversarial attack.** Three specification
  defects would have become code.
* **Stop supplying missing semantics as test fixtures.** Two evaluators had to be retracted as
  corpus-unsupported; they had manufactured a `Zero` separation that vanished when they did.
* **Stop treating cross-lane agreement as derivation.** Two lanes agree `DetectGap` is not primitive.
  That is corroboration.

## 4. The question worth reopening

> **What mathematical structure must an epistemic state preserve under all admissible representations
> and transitions?**

The programme began by asking *which operators belong to the kernel*. Eleven experiments have shown
that question to be downstream of at least four others: the type of `K_t`, the definition of semantic
equivalence, the contradiction predicate, and the status ordering. The kernel question is not hard
because the answer is elusive. **It is hard because it was asked first.**

---

## Appendix — the eleven experiments

| id | experiment | the result that survived |
|---|---|---|
| `KR-2026-09-01` | kernel reduction | `DetectGap` derivable **12/12** representations; `C0` had no evidence-admission power; minimality is representation-relative; invariant custody |
| `KR-SIM-…` | Theory v1.1 | **factivity impossibility witness**; 420/10 000; full lifecycle executes |
| `-B` | Theory v1.2 | `Zero ⟺ Δ=∅` names **three** disagreeing predicates; `Zero` coarser than the missingness taxonomy |
| `-C` | factivity repair | **R3 does not repair**; R1 ≡ R2; R3′ works only by ceasing to be epistemic |
| `-D` | `Sat_c` closure | **3 of 8** classes executable; **no class requires factivity**; arity ≥ 4 permanently `U` |
| `-E` | evaluators supplied | contagion halves; `Zero_strict` still unreachable; two new reason buckets required |
| `-F` | repair phase | **`Zero` chain derived**; the ordering violation's cost measured |
| `-G` | evaluation semantics | `value ∘ Eval` **lossy 9→1**; 8 of 10 negative tests falsified; **D-0** |
| `I` | Zero Lens + Gītā | boundary closure repairs D-0 **6/6**; `Gap = π_Q(𝓑)`; Gītā tiering placed **0 new primitives** |
| `-H` | boundary separation | 9 conditions distinct at `𝓑`, identical under `π`; **facet names alone fail** |
| `-I` | typed vocabulary | only **2 of 11** facets need typing; **4 of 41** distinctions load-bearing; two minimal typing sets |

**Self-corrections recorded across the chain: six.** Two evaluators retracted as corpus-unsupported;
a `Zero` separation that was an artifact of them; a sequencing violation that biased a measurement; a
confidence interval on the wrong quantity; a parsimony test that measured the wrong thing; and *a*
minimal repair presented as *the* minimal repair. **Each was found by a later experiment attacking an
earlier one, which is the only reason to run them in a chain.**
