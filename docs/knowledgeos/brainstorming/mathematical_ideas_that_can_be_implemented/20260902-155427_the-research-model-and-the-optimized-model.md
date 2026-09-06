# The Research Model and the Optimized Model

### What is still being investigated, what I would build now, and where every artifact lives

**Date** 2026-09-02 · **Baseline** KnowledgeOS Theory **v1.2**, unchanged · **This is not v1.3**
**Status** `[PROP]` throughout Part B — a **proposal**. Nothing here is adopted.
**Evidence base** 26 experiments: `KR-2026-09-01` (kernel reduction) · v1.1 chain · v1.2 chain `-B`…`-V`
· `KR-CLOSURE` · `KR-HISTORY` · `KR-YZ` · `KR-M2O` · `KR-NEFF` · `KR-DIST` (`FR-001`) · `KR-HILBERT` ·
`KR-CONTR`.

> **Supersedes** `20260902-132000_proposed-optimized-model-…md` **as a proposal**, and **incorporates
> the review appended to it.** Four review rulings are honoured here and are marked ⚖️ where they bite:
> the *kernel* terminology correction · the refusal of the four-primary-structures list · version-diff
> discipline · and the `𝒮_t → π_i → R_{i,t}` formulation. The earlier document stands as the record of
> the proposal that was reviewed; **this one is not a re-issue of it.**

---

> ## ⚠️ SUPERSEDED AS A PROPOSAL — 2026-09-02
> `20260902-170000_the-research-decision-and-optimized-model.md` supersedes this one, incorporating
> `KR-CONTR-EVAL`, `KR-CONTR-FDE`, `KR-COMP`, `KR-COMP-SEP`, the `E-FDE` adjudication, and — the
> structural change — **a DECISION LAYER** (`DECISION-01` ratified, `DECISION-02` open).
>
> **NOT withdrawn.** Read it as of its date; its four ⚖️ review rulings still bind.

---

# Part A — The Research Model

*What is under investigation, stated as a research object rather than as an architecture.*

## A.1 The object

⚖️ **Adopted from the review's §22**, which is a better formulation than the one it replaced:

```
                     π_i
        𝒮_t   ─────────────►   R_{i,t}          representations / projections
         │
         │  Θ : 𝒮_t × Input_t → 𝒮_{t+1}         transformation
         ▼
        𝒮_{t+1}   ──────────►   History_t        the transition is recorded
```

with `ZeroLens(𝒮_t, π_i)` an **instrument** that examines *the distinctions `π_i` discards*.

**The separation that matters, and it is four-way:**

```
   Structure   ≠   Projection   ≠   Lens   ≠   History
     𝒮_t             π_i          ZeroLens    History_t
   what is        what is        what is      what was
    the case      reported       examinable    recorded
```

**Nothing in this diagram is adopted.** It is the shape the research is being conducted in.

## A.2 ⚖️ The corrected vocabulary — and why it is not pedantry

The earlier proposal said *"the information the theory needed was in the **kernel** of the projection"*
and *"every projection declares its kernel."* **The review corrected this, and the correction is
load-bearing.** For an arbitrary projection `π : X → Y` there need be **no kernel in the algebraic
sense**. The right object is the **induced equivalence relation**:

```
        x₁ ~_π x₂   ⟺   π(x₁) = π(x₂)              and      Y ≅ X / ~_π
```

so the rule is:

> ### **Every projection declares what DISTINCTIONS IT IDENTIFIES.**
> — not *"every projection declares its kernel."*

**This is not a wording change; it changed what gets measured.** `KR-CONTR` is the first experiment
built on the corrected object and could not have been built on the other one: it separates
contradiction models by comparing **which pairs of cases each model identifies**, because the models
are *functions* and functions are separated on their induced relations, not on their values. A single
case cannot separate them; a **pair** can. *(The code initially named this function `kernel()`; renamed
to `induced_relation()`, results verified bit-identical.)*

**Use, for semantic projections:** *information loss* · *discarded structure* · *identified
distinctions*. **Reserve "kernel" for the algebraic case where one actually exists.**

## A.3 ⚖️ What `𝒮_t` is made of is OPEN — and is not asserted here

The earlier proposal named four primary structures `E_t, 𝓑_t, AF_t, M_t`. **The review declined to
accept that list**, on the ground that the evidence is suggestive but not sufficient — `𝓑_t` may itself
be derivable from a state plus requirements, and likewise `AF_t`.

**That ruling is honoured. This document asserts no decomposition of `𝒮_t`.** What can be said is
weaker and is all that is earned:

| about `𝒮_t` | status |
|---|---|
| some structure richer than the value codomain is required | `[EXP]` — Experiment J: nine conditions indistinguishable in the codomain, distinguishable above it |
| `𝓑` is **a** candidate for part of it | `[PROP]` — **not** established as primary, and `KR-CONTR` §8 adds no support |
| the decomposition is settled | **NO — OPEN.** Not claimed |

> **The honest position: we know a projection is losing what we need. We do not know the structure it
> is a projection of.** Naming four structures would be the same error the programme keeps
> diagnosing — asserting a decomposition because a projection failed.

## A.4 The open questions, and what would close each

| question | state | what would close it |
|---|---|---|
| **`Contr`** — the contradiction relation itself | `OPEN`, **untouched** by `KR-CONTR` | a definition, then a suite that can observe it — **which today it cannot** (§B.2) |
| **composition** — the rule *and* its level | `OPEN`, now **load-bearing** | a choice between token-blind and token-sensitive; carries the fourth value with it |
| **the fourth value** | **no longer independent** | **closes automatically with composition** — `KR-CONTR` §5 |
| **`⪰`** | `OPEN`, **independent of `N_eff`** | a definition; two independent paths exist and must stay independent |
| **`≡_sem`** | `OPEN`, **NOT refuted** | must not be asked a question it was not designed for |
| **`δ`** | `OPEN` | — |
| **retirement / lifecycle** | `OPEN` | — |
| **`N_eff`** | `OPEN` as a *family-level* quantity | **not** by another closed-form search — `FR-001` closed that |
| **class / reason exhaustiveness** | `OPEN` | 5 facet gaps outstanding |

**Two questions have been removed from this table** and should not drift back onto it:
**`Factivity`** (decided — `R1`) and **the `N_eff` formula search** (frozen — `FR-001`).

## A.5 The two items that are decisions, not experiments

The programme has learned to tell these apart, and getting it wrong costs whole experiments.

| item | why no experiment can settle it |
|---|---|
| **adopting `M3 ≅ M4`** | `KR-CONTR` eliminated `MD`. **One survivor is not an adoption.** No further evidence distinguishes a survivor from a choice |
| **adopting `𝓑`** | Experiment J supports it; **support is not adoption**, and no amount of further support becomes one |

> This is the shape `Factivity` had, and it sat in the queue as a research item for far longer than it
> should have because nobody had noticed. **Both of the above should be adjudicated, not investigated.**

---

# Part B — The Optimized Model

*What I would build now, given everything the 26 experiments established.* **`[PROP]` — a proposal.**

## B.0 ⚖️ The version diff, stated formally — not "nothing is deleted"

The earlier proposal said *"nothing is deleted from v1.2; six items are repositioned and one is
retired."* **The review rejected that framing and required formal version-diff discipline**, on the
ground that if the semantics of `K_t` change, that is a deletion however it is described.

**Applying that discipline to `R1`, which has since been decided:**

| symbol | v1.2 | after `R1` | diff class |
|---|---|---|---|
| `K_t` | `K_t = Γ(E_t,Q,C,EC)`, **intended to denote knowledge** (factive via `DEF-1`) | **retired** | **DELETION** |
| `A_t` | — | `A_t = Γ(E_t,Q,C,EC)`, denotes **attribution** (non-factive) | **ADDITION** |
| `DEF-1` | governs the system's own object | governs an **external** predicate the system never computes | **RE-SCOPING** |

> **`K_t → A_t` is NOT a rename.** `K_t` was intended to denote knowledge and `A_t` denotes
> attribution; these are different predicates with different truth conditions. **A symbol was retired
> and another introduced.** Any migration note that calls this a rename will mislead every later
> reader about what was given up.

**What was given up, plainly: KnowledgeOS no longer claims to represent knowledge.** That is the
correct outcome — it never could, and `DEF-1` plus the attribution equation were jointly unsatisfiable
— but it is a deletion and is recorded as one.

## B.1 The design principle — unchanged, and now six times evidenced

> ```
> DEFINE THE STRUCTURE.  DERIVE THE PROJECTION.
> A projection may be named, used and optimized — it may never be primitive.
> ```

| # | the projection | the structure | where |
|---|---|---|---|
| 1 | `Sat_c = value ∘ Eval_c` | the evaluation situation | Phase 3 |
| 2 | `U` | `𝓑` | Phase 5 |
| 3 | `Gap` | `𝓑` | Phase 5 |
| 4 | pairwise distinguishability | the family-level joint object | `FR-001` |
| 5 | spectral functionals of `Σ` | the full joint / tail structure | `KR-HILBERT` |
| 6 | **the value-set of a state** | **the state** | **`KR-CONTR` — NEW** |

**Instance 6 is new and is the most actionable**, because unlike the others it comes with a
constructive fix.

## B.2 The saturation law — the one genuinely new design output

**What `KR-CONTR` found.** The four `Zero` readings are functions of the **set of values present in the
state**:

```
Zero_weak(s)     = ¬∃r. value(r) = F
Zero_kleene(s)   = F if ∃F else U if ∃U else T
```

These factor through the projection `π_set : 𝒮_t → 𝒫({T,F,U})`, which discards **which** requirement,
**multiplicity**, **the reason**, and **every value outside `{T,F,U}`**.

**The consequence, measured:** the three contradiction models are separable in isolation, and **a
single unrelated `U` anywhere in the state masks the difference completely** — in any bucket, at any
multiplicity. The readings are **absorbing**: once a `U` is present, no further information can move
them.

> **And `Sat` has 5 of 8 classes non-executable. Saturation is therefore the NORMAL regime, not an edge
> case. The system is, in practice, permanently blind.**

### The law, stated so it can be checked

> ### A reading must be a function of `𝒮_t`.
> If it is computed from a projection `π` for efficiency, `π` must be **reading-complete** for it:
> ```
>          ρ factors through π      ⟺      ~_π  refines  ~_ρ
>                                   ⟺      π(s₁) = π(s₂)  ⟹  ρ(s₁) = ρ(s₂)
> ```
> **This is decidable and testable.** It is the §A.2 rule applied to readings instead of to values.

### The constructive form — diagnostic readings

Replace `ρ : 𝒮 → Bool` with

```
        ρ : 𝒮_t  ⟶  (verdict, Witness)
        Witness = which requirements are responsible, with their reasons
```

A saturated *verdict* is then harmless, because the **witness still moves**. `Zero_kleene` may sit at
`U` forever; *"`U` because `content` is contradictory"* and *"`U` because `governance` has no
evaluator"* remain distinct — and those are exactly the two states `MD` conflated.

**This is one fix for two problems.** The boundary object exists because the value codomain loses
distinctions; diagnostic readings exist because the reading loses them again downstream. **Adding `𝓑`
while leaving the readings existential would recover the information and then discard it a second
time.**

## B.3 Contradiction handling

**One model, and it is the only survivor — not an adoption.**

```
contradictory input  ─►  a value OUTSIDE {T,F,U}  +  reason CONTRADICTORY_INPUT (agent bucket)
```

- **`M3 ≅ M4`** — isomorphic under relabeling `UNDEFINED ↔ C` (0/8 commutation failures). **Whether
  the token is called `UNDEFINED` or `C` is not a modelling question.**
- **`MD` (delegated) is excluded** `[NEG]` — it renames contradiction as absence, violating the
  corpus's own `ZI-01` (*Unknown ≠ Absent*) and `ZI-09` (*Conflict ≠ Invalidity*). **Scope:** refuted
  *relative to those declared distinctions*; not a general proof that delegation is incoherent.
- **The fourth value is decided by composition, not here.** Token-blind composition ⟹ the fourth value
  does no work. Token-sensitive ⟹ it does. **That is the entire question, reduced to one binary
  choice** — and the corpus currently supplies no token-sensitive rule.

## B.4 Factivity — as decided

```
   ┌── KnowledgeOS ─────────────────────────┐
   │   A_t = Γ(E_t, Q, C, EC)               │      attribution — NOT factive
   │   no component may assert Knows        │
   └────────────────────────────────────────┘
                    │  Verification is SEPARATE
                    ▼
   ┌── outside ─────────────────────────────┐
   │   Knows(a,p,c,t) → True(p,c,t)         │      factive, where truth is available
   └────────────────────────────────────────┘
```

**Two things the optimized model must not quietly assume:**

1. **`ChannelRelation` is not required by `R1`.** `R1` keeps Verification separate without committing
   to a channel-consuming verifier. **If** such a verifier is ever built, it must sit on an
   **independent channel** — same-channel validation detects a misleading source **0.75 %** of the time
   versus **99.3 %** (`KR-M2O`) — and stating independence needs `ChannelRelation(c₁,c₂)`, which is
   unbuilt.
2. **The error rate is untouched. 3 320 attributions, 390 false.** `R1` fixes what may be claimed,
   never how often the system is right.

## B.5 What the optimized model does NOT solve — stated before the benefits, not after

| | |
|---|---|
| **`Contr`** | undefined. The model says what to *do* with a detected contradiction; **not what one is** |
| **`Sat`** | still semantically incoherent; 5 of 8 classes non-executable |
| **closure** | **still broken.** `KR-CONTR` `C6` reproduces `D-0`: a contradictory state still has `Zero_weak = Zero_reasoned = true`. §B.2 is the *design* for the repair, **not the repair** |
| **`𝒮_t`** | decomposition open; `𝓑` a candidate, not primary |
| **`N_eff`** | no family-level measure. Neither pairwise nor spectral — `FR-001` + `KR-HILBERT` |
| **the 390** | unchanged |
| **the kernel** | **NOT SELECTABLE.** Nothing here changes that, and per **`C-1`** no statistical mechanism enters the kernel merely because KnowledgeOS can use it |

## B.6 Build order — cheapest and most-unblocking first

| # | step | why here | kind |
|---|---|---|---|
| **1** | **diagnostic readings** (§B.2) | **restores observability.** Everything downstream is untestable without it | engineering |
| **2** | **composition** — choose token-blind or token-sensitive | closes the fourth value as a corollary; unblocks `Sat_content` | **experiment** |
| **3** | **`Contr`** | now *observable*, so a definition can be evaluated | experiment |
| 4 | `K_t → A_t` propagation across `Δ_t`, `Zero`, adequacy, kernel def, `I1`–`I9` | consequent on `R1` | engineering |
| 5 | adjudicate `M3≅M4` and `𝓑` | decisions, not experiments (§A.5) | **decision** |
| 6 | `⪰`, `δ`, retirement | independent | experiment |

> ### ⚠️ The order changed, and the reason is a finding, not a preference.
> **`Contr` was first. It should now be third.** It was queued ahead of composition on the assumption
> that the fourth value was part of it — `KR-CONTR` §5 shows it is not. And the readings **saturate**,
> so **a `Contr` defined today would be unobservable in the normal regime**: the definition could not
> be evaluated by any experiment. **Fix observability, then compose, then define.**
>
> **This is a recommendation. The lane does not reorder the queue.**

---

# Part C — Where the artifacts are

## C.1 The research record — three lanes

| lane | files | what it holds |
|---|---|---|
| **`docs/knowledgeos/research/theory-v1.2-simulation/`** | **25** | the primary lane, `00-INDEX` + `A`–`V` + `FINAL-VERDICT` |
| `docs/knowledgeos/research/theory-v1.1-simulation/` | 14 | `00-INDEX`, `A`–`L`, `FINAL-VERDICT` — formal model, type system, and the counterexample / no-smuggling / circularity / proof-obligation registers |
| `docs/knowledgeos/research/kernel-reduction/` | 22 | `00-INDEX`, `01`–`19`, `FINAL-kernel-reduction-report` — capability model, ablations, falsification, protocol audit |

**The v1.2 lane, by what it establishes:**

| file | result |
|---|---|
| `A`–`D` | executive result · model & experiments · status classification · gap register |
| `E` | factivity repair — `R1`/`R2` identical, `R3` refuted |
| `F` · `G` | `Sat_c` incoherence · evaluation semantics, **`D-0`** |
| `H` | repair phase, re-ordered · the `Zero` chain derivation |
| `I` · `J` · `K` | **the boundary object `𝓑`** · boundary separation · typed facets |
| `L` · `M` · `N` | closure as event · kernel vs history (0 reads) · Yoni-Zero cycle |
| `O` · `P` · `Q` | standing checks · winner's curse & channels · `N_eff` calibration |
| `R` · **`S`** | **`FR-001`** · **the frozen-results register — authoritative for the freeze** |
| `T` | Hilbert space — cleaner coordinates, same obstructions |
| **`U`** | **factivity adjudication brief — §0 records the `R1` outcome** |
| **`V`** | **`KR-CONTR` — two models not three; `MD` refuted; the saturation finding** |

## C.2 Code

```
research/knowledgeos-sim/
  kos12/   25 modules (v1.2)   experiment sat3 relations repair satc_spec phaseB phaseC
                               evaluators rerun repairs evalc expG zerolens zeroH zeroI
                               closure history yonizero category_check m2o neff distinguish
                               hilbert  contr                        ← KR-CONTR
  kos/     12 modules (v1.1)   world state types transitions scenarios properties inquiry
                               factivity adversarial audits randomized
  runners  run_experiment.py · run_v12.py · run_satc.py · run_contr.py
research/kernel-reduction/
  kr/      12 modules          atoms capabilities operators properties scenarios carriers
                               reach ablate variants audit_variants infotheory
  runner   run_all.py
```

## C.3 Machine-readable results

| directory | n | holds |
|---|---|---|
| `research/knowledgeos-sim/results/zero/` | 7 | the `Zero` chain — 1 620 000 assignments, 0 counterexamples |
| `…/evaluation/` | 9 | evaluator runs, contagion, the `U`-collapse |
| `…/closure/` · `…/history/` | 4 · 2 | closure-as-event · the 0-reads check |
| `…/m2o/` | 5 | winner's curse · multiplicity–evidence coupling · decoy & channels |
| `…/neff/` · `…/dist/` | 4 · 2 | `N_eff` calibration · **`FR-001`** |
| `…/hilbert/` | 3 | spectral functionals |
| **`…/contr/`** | **10** | **`C1`–`C10` — separation, isomorphism, masking, bucket sweep** |
| `…/yz/` · `…/audit/` | 2 · 5 | Yoni-Zero · audits |
| `research/kernel-reduction/results/` | 13 | ablation · pairwise · randomized · variants |

*Two scratch files (`smuggling_probes.json`, `variant_summary.json`) remain in the kernel-reduction
results directory — cleanup was denied by the sandbox; documented in that directory's README.*

## C.4 Reproduction

```bash
cd research/knowledgeos-sim && python run_contr.py    # KR-CONTR — deterministic, no seed
cd research/knowledgeos-sim && python run_v12.py      # the v1.2 chain
cd research/knowledgeos-sim && python run_satc.py     # Sat_c phases
cd research/kernel-reduction && python run_all.py     # kernel reduction
```

Seeds are fixed and recorded in each result JSON. **`KR-CONTR` is exhaustive over its
parameterization and uses no randomness at all.**

## C.5 In this folder

| file | what it is |
|---|---|
| `…122338-what-eleven-experiments-established-a-synthesis.md` | mid-programme synthesis |
| `…132000_proposed-optimized-model-…md` | **the earlier proposal + the review that corrected it** |
| `…142217_the-frozen-model-…md` | the **snapshot** — what is settled now |
| `…150331_the-development-of-the-model-…md` | the **trajectory** — how it got there |
| **this document** | the **research model** and the **optimized model** |

*Commissioning protocols and reviews in this folder are **inputs**; they are timestamped and are not
edited by the lane.*

## C.6 State and session record

`.claude/CONTEXT.md` — active-track block · `.claude/sessions/2026-09-02.md` — appended after every
experiment, including every self-correction.

---

# Closing — the two sentences that carry the most

> **Every projection declares what distinctions it identifies.**
>
> **The strongest statement made must never exceed the strength of the available evidence.**

The first is the model. The second is why anything in it can be believed.
