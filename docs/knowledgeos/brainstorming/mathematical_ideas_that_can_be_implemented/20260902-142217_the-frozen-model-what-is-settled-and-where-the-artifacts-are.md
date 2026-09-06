# The Frozen Model

### What is settled, what is proposed, what is open — and where every artifact lives

**Date** 2026-09-02 · **Baseline** KnowledgeOS Theory **v1.2**, unchanged · **No v1.3.**
Written by the executing research lane as a **reference**, not a proposal. Nothing here promotes
anything.

---

# Part 0 — What "frozen" means, precisely

Three different words are in play and they are not synonyms.

| term | meaning | example |
|---|---|---|
| **BASELINE** | the theory the experiments are run *against*. Changed only by adjudicated theoretical change. | Theory v1.2 |
| **FROZEN** | supervisory review has adjudicated a result and **closed further work on it in that form**. | `FR-001` |
| **PROPOSED** | a candidate, tested or untested, that has **not** been adopted. | the structures-first model |

> **Freezing is not promotion.** A frozen `[NEG]` stays `[NEG]`. Freezing records that *the question
> has been answered well enough to stop asking it in that form* — not that the answer is now canon.

**The freeze discipline.** A frozen entry may be **cited**, **superseded by an explicit act**, or
**reopened by naming the new evidence**. It may **not** be silently revised, and it may **not** be
re-litigated by rerunning the same experiment.

---

# Part 1 — The frozen baseline: Theory v1.2

v1.2 is itself a **weakening** of v1.1, and that is its principal virtue. It made four moves:

1. **A constitutional four-layer separation** — Philosophy → Epistemic mathematics → DDD → Architecture
   — and an explicit **ban** on the inference `Gītā concept → mathematical concept → primitive → DDD
   object → architecture`.
2. **An epistemic status vocabulary** — `ESTABLISHED · DERIVED · BOUNDED · HYPOTHESIS ·
   INTERPRETATION · CORROBORATION · NORMATIVE · TECHNICALLY OPEN · DEFERRED · REFUTED · UNKNOWN` —
   applied to every claim.
3. **Reopening what v1.1 had closed.** `Observation` became an OPEN concept; `Zero` was demoted from a
   construct to a *candidate epistemic-boundary construct*; `E_t ≠ K_t` was demoted from a correction
   to a *research distinction*; `Buddhi` and `Sārathi` became **lenses**, not domain objects.
4. **Three-valued, class-indexed satisfaction** `Sat : 𝒦 × ℛ → {⊤, ⊥, U}` over eight requirement
   classes.

**The v1.2 status after thirteen experiments:**

| | |
|---|---|
| Theory | **B — PARTIALLY EXECUTABLE** |
| `Sat` | **SEMANTICALLY INCOHERENT** under the model the theory proposes |
| Kernel | **NOT SELECTABLE** — not "unfinished" |
| Factivity | **DECIDED — `R1`** *(governance, after this snapshot)*: `K_t → A_t`; `Knows → True` external/factive; Verification separate. **No longer open.** |

---

# Part 2 — The one frozen result: `FR-001`

**`KR-DIST-2026-09-02`. Frozen 2026-09-02.**

## 2.1 The finding

> **Pairwise semantic/evidential distinguishability cannot carry family-level epistemic complexity by
> itself.**

## 2.2 Why it is a boundary and not a failed fit

It was established by **two independent failure mechanisms**. One failure is a missing formula; two
independent failures at different levels of the construction is a **boundary**.

### Mechanism 1 — the relation is not an equivalence relation

Define, for an evidence-and-observation regime `Λ = (m, σ, α)`:

```
H_i ~_Λ H_j     iff     the regime cannot resolve them
```

With `m = 40`, `σ = 1.0`, `α = 0.05`: standard error `0.1581`, resolution on a score difference
`δ = 0.3099`. Place twelve hypotheses with means spaced at `0.9 δ`:

```
H₁ ~ H₂ ~ H₃ ~ … ~ H₁₂        every adjacent pair indistinguishable
H₁ ≁ H₁₂                      endpoints distinguishable, span 3.068
```

`~_Λ` is reflexive and symmetric but **not transitive** — a **tolerance relation**. Therefore the
quotient `H/~_Λ` **does not exist**, and

```
N_eff = |H / ~_Λ|
```

is not a definition. **The commissioned construction fails at its first step.**

> **A methodology catch worth keeping.** A direct search for a transitivity witness *inside the
> equicorrelated space* found **none** — there every pair has the same separation, so the relation is
> vacuously transitive. **Non-transitivity appears only where separations vary.** A homogeneous test
> space conceals the defect.

### Mechanism 2 — the transitivity-free repair fails on coupling

The textbook replacement for a quotient under a tolerance relation is a **δ-packing number** — the
largest pairwise-distinguishable subset. It requires no transitivity and always exists.

| space | `sd(Sᵢ−Sⱼ)` | pairwise distinguishable | **packing** | **measured `N_eff`** | ratio |
|---|---|---|---|---|---|
| grouped, 50 groups | 1.414 | yes | 50 | 51.1 | **0.98** |
| grouped, 200 groups | 1.414 | yes | 200 | 201.9 | **0.99** |
| independent, n=1000 | 1.414 | yes | 1 000 | 940.6 | **1.06** |
| correlated ρ=0.5 | 1.000 | yes | 1 000 | 200.6 | 4.99 |
| **correlated ρ=0.9** | 0.447 | **yes** | **1 000** | **9.0** | **111** |
| **correlated ρ=0.95** | 0.316 | **yes** | **1 000** | **4.5** | **222** |

**Packing matches redundancy to within 6 % and fails on coupling by up to 222×.**

**The mechanism of the failure is the substantive part.** At ρ = 0.9 every pair remains *pairwise
distinguishable* (`sd 0.447 > δ 0.310`), yet the *maximum* of 1 000 such scores behaves like about
nine independent draws — because a shared component moves them together.

> **The multiplicity burden is driven by the joint extreme-value behaviour of the family, not by
> pairwise separability.** Pairwise distinguishability is a property of *pairs*; the burden is a
> property of the *family*.

## 2.3 The three-level distinction this clarifies

| level | question | relevant to |
|---|---|---|
| **1 · Identity** `H_i ≡_sem H_j` | are two hypotheses the same under a semantic contract? | **redundancy** |
| **2 · Pairwise distinguishability** `H_i ≁_Λ H_j` | can the evidence regime tell them apart? | a **measurement** property |
| **3 · Family structure** | how does the whole collection behave *jointly*? | **coupling** — where levels 1–2 are insufficient |

**`≡_sem` is not refuted by this.** It answers *"are these two representations semantically
interchangeable?"*. It does not answer *"how does this family behave as a joint statistical object?"*
`≡_sem` and family-level complexity **remain separate concepts** unless a formal bridge is
established — which is a good outcome, because it prevents forcing everything into one equivalence
relation.

## 2.4 Register

| finding | status |
|---|---|
| `~_Λ` is not generally transitive | **`[NEG]`** |
| `H/~_Λ` cannot generally define complexity | **`[NEG]`** |
| Homogeneous spaces can conceal non-transitivity | `[EXP]` |
| δ-packing avoids the quotient/transitivity problem | `[EXP]` |
| Packing captures tested redundancy/grouping | `[EXP]` |
| Packing fails dramatically under strong correlation | **`[NEG]`** |
| Pairwise distinguishability cannot generally measure family-level burden | **`[EXP]` / `[NEG]`** |
| Family-level burden depends on joint behaviour | `[EXP]`, scope-bounded |
| Redundancy and coupling are distinct **candidate** mechanisms — *at least two* | `[PROP]` |
| `≡_sem` captures the whole `N_eff` problem | **`[NEG]`** |
| `≡_sem` may capture the redundancy component | `[PROP]` |
| Explicit dependence structure is required in KnowledgeOS | **`[OPEN]`** |
| `N_eff` is a KnowledgeOS primitive | **`[OPEN]`** |
| `N_eff` belongs in the kernel | **`[OPEN]`, unsupported** |

## 2.5 What the freeze closes, and what it leaves open

**Closes.** No third `N_eff` formula hunt — two have failed for the same reason: `n(1−ρ)²` (fitted on
a narrow grid, refuted on a wider one, ratio spanning 0.34–3.10) and δ-packing (structural, but
pairwise). `N_eff` does **not** become the next central theory problem: it is not established that
effective complexity is a KnowledgeOS primitive at all — it may belong to an *evidential regime*,
outside the kernel.

**Leaves open, in a specific form.** Not *"what formula gives `N_eff`?"* but:

> **What is the minimal joint structure required to represent family-level epistemic dependence?**

`[PROP]` A candidate representation — **not** a proposed v1.3 — is `𝔥_Λ = (𝓗, ℛ_sem, Λ, Σ_Λ)`.

---

# Part 3 — Five standing consequences carried out of `FR-001`

These outlive the frozen entry and apply programme-wide.

| # | consequence | status |
|---|---|---|
| **C-1** | **Do not put statistical mechanisms into the kernel merely because KnowledgeOS can use them.** Candidate generation · selection · multiplicity control · correlation modelling · validation · Bayesian updating may all be **capabilities** without being **irreducible kernel operators**. | `[EXP]` |
| **C-2** | **Identity → Distinguishability → Joint behaviour.** Levels 1–2 are insufficient for level 3. | `[EXP]` |
| **C-3** | **`≡_sem` and family-level complexity remain separate concepts.** `≡_sem` is not refuted; it must not be asked a question it was not designed for. | `[PROP]` |
| **C-4** | **A homogeneous test space can conceal a relation-level defect.** | `[EXP]` methodology |
| **C-5** | **Good fit on a narrow design space ⇏ structural law.** | `[EXP]` methodology |

---

# Part 4 — What else is settled (established, but not frozen)

Frozen ≠ established. These are **established results** from the thirteen experiments; none has been
adjudicated and closed, so all remain open to further work.

## 4.1 The recurring diagnosis

> **The programme repeatedly defined a projection and then asked it to do the work of the structure
> it projects from.**

| the structure | the projection defined instead | where it broke |
|---|---|---|
| `Eval_c(K,r,Γ) → EVal` | `Sat_c := value ∘ Eval_c` | **9 evaluation situations collapse into `U`** |
| the boundary `𝓑` | `Zero ⟺ Δ = ∅` | three non-equivalent readings; a contradictory state closes |
| `𝓑` | `Gap` | `Gap = π_Q(𝓑)`, attribute-lossy in *kind* and *remediability* |
| `ZeroLens` | `DetectGap` | derivable **12/12** representations |
| `E_t` | `K_t = Γ(E_t,…)` | **`Γ` cannot be factive** — truth is not in its domain |
| `AF_t` | a signed scalar | **6 conditions identical at sum 0** |

## 4.2 The load-bearing established results

| result | evidence |
|---|---|
| **`DEF-1` factivity and `K = Γ(E,Q,C,EC)` are jointly unsatisfiable** for any attributing `Γ` | witness: two worlds, bit-identical `E`; **420 violations / 10 000** |
| **No `Sat_c` class requires factivity** — the family has nowhere to attach truth | Phase A specification alone |
| **Minimality is representation-relative** — `\|K_min\| = 13` and `8` under two algebras | exhaustive over 16 384 subsets |
| **Only 3 of 8 `Sat_c` classes are executable**; five have named blockers | `⪰` · `Contr` · no evaluator · no temporal semantics · `δ` |
| **Every composite of arity ≥ 4 is permanently `U`** with five classes blocked | Kleene conjunction over the class set |
| **The `Zero` chain is derived**: `strict ⇒ reasoned ⇒ weak` | 1 620 000 assignments, 0 counterexamples |
| **The kernel writes history and never reads it** | 0 reads, 0 behavioural differences, static |
| **Closure is an event, not a state** | the state model is refuted by temporal revision |
| **Winner's curse**: apparent quality 0.00 → 0.61 while real quality stays 0 | 200 trials/arm, null world |
| **Multiplicity and evidence are coupled** — at fixed evidence a real signal becomes inadmissible | `m > (z σ / signal)²`, `z² ~ 2 ln n` |
| **Channel independence, not validation, does the work** | same channel detects a decoy **0.75 %**; independent **99.3 %** |
| **83 % of progress claims in the corpus specify no ordering** | 1 491 of 1 793 |
| **11 live lens/domain category violations** | one pattern accounts for 6 |

## 4.3 What is proposed and not adopted

* **The structures-first model** — six layers, four enforceable laws
  (`…132000_proposed-optimized-model-…`). `[PROP]`.
* **`Zero_reasoned`** as a fourth closure reading. `[PROP]`
* **Typed facet conditions** — only 2 of 11 facets need typing; **37 of 41 distinctions unsupported**.
* **The `𝔥_Λ` representation.** `[PROP]`

## 4.4 What is open

`Contr` and the fourth value · `⪰` · `≡_sem` · `δ` · the retirement/lifecycle relation ·
the composition rule and its level · five facet gaps (`ZI-07`, `ZI-10`, missing-counterargument,
unsupported-reconciliation, closure-under-weak-standards) · `N_eff` · class and reason exhaustiveness.

**Queue:** `Contr → ⪰` — **`Factivity` is decided (`R1`)**. `N_eff` and `⪰` are **related but independent**.

---

# Part 5 — Where the artifacts are

## 5.1 Written record

| path | contents |
|---|---|
| `docs/knowledgeos/research/kernel-reduction/` | **22 files** — `00-INDEX` · `01`–`19` · `FINAL-kernel-reduction-report.md`. The 2026-09-01 kernel lane |
| `docs/knowledgeos/research/theory-v1.1-simulation/` | **14 files** — `00-INDEX` · `A`–`L` · `FINAL-VERDICT.md`. The v1.1 simulation |
| `docs/knowledgeos/research/theory-v1.2-simulation/` | **22 files** — `00-INDEX` · `A`–`S` · `FINAL-VERDICT.md`. The v1.2 chain |
| **`…/theory-v1.2-simulation/S-frozen-results-register.md`** | **the freeze register — `FR-001` and the five standing consequences** |
| `…/theory-v1.2-simulation/R-distinguishability-…md` | **the frozen experiment itself** |
| `docs/knowledgeos/brainstorming/20260902-122338-what-eleven-experiments-established-a-synthesis.md` | the cross-experiment synthesis |
| `…/mathematical_ideas_that_can_be_implemented/20260902-132000_proposed-optimized-model-…md` | the structures-first proposal `[PROP]` |
| **this file** | the frozen-model reference |

**Where to start reading:** `S-frozen-results-register.md` → `R-…` → the synthesis → the proposal.

## 5.2 Code and machine-readable results

| path | contents |
|---|---|
| `research/kernel-reduction/` | `kr/` **12 modules** · `run_all.py` · `results/` **13 JSON** |
| `research/knowledgeos-sim/` | `kos/` **12 modules** (v1.1) · `kos12/` **23 modules** (v1.2 →) |
| | runners: `run_experiment.py` · `run_v12.py` · `run_satc.py` |
| | `results/` — **61 JSON** across 9 subdirectories |

**`kos12/` modules by experiment:**

| module | experiment |
|---|---|
| `experiment.py`, `sat3.py`, `relations.py` | v1.2 (`-B`) — Zero readings, equality relations |
| `repair.py` | factivity repair (`-C`) |
| `satc_spec.py`, `phaseB.py`, `phaseC.py` | `Sat_c` closure (`-D`) |
| `evaluators.py`, `rerun.py` | evaluators supplied (`-E`) |
| `repairs.py` | repair phase (`-F`) |
| `evalc.py`, `expG.py` | evaluation semantics (`-G`) |
| `zerolens.py`, `zeroH.py`, `zeroI.py` | Zero Lens · boundary separation · typed vocabulary |
| `closure.py`, `history.py` | closure event · kernel-vs-history |
| `yonizero.py` | Yoni-Zero cycle |
| `category_check.py` | the lens/domain standing check |
| `m2o.py`, `neff.py`, **`distinguish.py`** | multiplicity · `N_eff` · **the frozen experiment** |

**`results/` subdirectories:** `audit` (5) · `closure` (4) · **`dist` (2 — `FR-001`)** ·
`evaluation` (9) · `history` (2) · `m2o` (5) · `neff` (4) · `yz` (2) · `zero` (7), plus **21** at the
top level.

**`FR-001`'s own data:** `research/knowledgeos-sim/results/dist/transitivity.json` (the sorites
witness) and `.../packing_vs_measured.json` (the 222× table).

## 5.3 Reproduction

```bash
cd research/kernel-reduction   && python3 run_all.py          # ~30 s, no dependencies
cd research/knowledgeos-sim    && python3 run_experiment.py   # v1.1, ~20 s
                                  python3 run_v12.py          # v1.2
                                  python3 run_satc.py         # Sat_c phases A/B/C
```

Every module is dependency-free standard-library Python. Seeds are fixed and recorded in each
result's `_meta`.

## 5.4 Session record

`.claude/sessions/2026-09-01.md` and `.claude/sessions/2026-09-02.md` — the full chronological
record, including **every self-correction**. `.claude/CONTEXT.md` carries the current state and the
first frozen result.

---

# Part 5b — Companion: the development history

This document is a **snapshot** — what is settled *now*. Its companion,
`20260902-150331_the-development-of-the-model-how-it-was-built-what-changed.md`, is the
**trajectory**: baseline-by-baseline and phase-by-phase, what each experiment changed, what was
removed or demoted, the five instances of the projection-vs-structure diagnosis, and the
self-correction register. Read the snapshot for *state*; read the trajectory for *how and why*.

---

# Part 6 — How to use this document

* **To cite the frozen result:** cite `FR-001` and the register, not this file.
* **To reopen it:** name the new evidence. Rerunning `KR-DIST` is not new evidence.
* **To extend the programme:** the queue is `Contr → ⪰` (`Factivity` decided — `R1`), and the first is
  **decisions**, not experiments.
* **Before proposing any equation:** check it against **C-1** (statistical mechanism ≠ kernel
  operator) and the lens/domain rule — a domain object on the left and a lens on the right is a
  category error by construction.

> **The programme's most valuable output so far is not a model. It is a set of boundaries — places
> where a natural formulation was shown not to work, and why.** `FR-001` is the first of them to be
> formally frozen.
