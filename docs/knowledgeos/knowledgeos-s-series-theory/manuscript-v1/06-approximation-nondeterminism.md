# Chapter 6. Approximation and nondeterminism

*Status: INTERNALLY REVIEWED (same model family; refute-first review 2026-10-18; all corrections applied). Not externally reviewed.*

**Research question (RQ4).** What survives if the state may be **approximate** (fewer resources, some errors), or if the system is **nondeterministic** (several outcomes possible for the same history)?

**Plain meaning.**
- **No free approximation.** A state that can be updated step by step and answers correctly on **every** history is already exact. So "approximate" only means something once one declares **which** errors are tolerated: a time horizon, a probability weighting, or graded errors.
- **Good approximations are often not simplifications of the exact state.** They track different information. So approximations belong in separate, labelled views, not in place of the authoritative state. (Whether to adopt that design is a governance decision.)
- **Under nondeterminism the theory still works** for "what outcomes are possible", via a sets-of-states view. Compact nondeterministic models are not unique. **Recording each choice outcome as an input** turns the system back into a deterministic one, under stated conditions.

## 6.1 Approximation

**DEF 6.1.**
- A **recursive approximation** is a realization R = (S, s₀, u, f), not necessarily sufficient.
- For each d, let ρ_d be a metric on V_d ∪ {⊥}. For a set H ⊆ Σ\* of histories, the **worst-case error** on H is sup_{h∈H} max_d ρ_d(f_d(s(h)), d̂(h)).
- For a process μ on histories, with time weights w_t, the **expected loss** is ℓ_μ(R) := Σ_t w_t E_μ Σ_d ρ_d(f_d(s(H_t)), d̂(H_t)).

**Theorem 6.2 (no free approximation). THM.** Let ρ be a metric on V_d ∪ {⊥}, so ρ(a, b) = 0 ⇔ a = b. If the worst-case error on **all of Σ\*** is 0, then R is sufficient. Hence ker s ⊆ ∼ (Thm 3.4).

*Proof.* Zero error on Σ\* means f_d ∘ s = d̂ on Σ\* for every d, which is DEF 2.7's sufficiency. ∎

*Remark (ANA).* Measuring errors only on L is weaker. Two legal histories with the same state could differ in which continuations are legal, and an error on illegal continuations would never be counted. For L = Σ\* the two notions coincide.

**Proposition 6.3 (support). THM.** Let each ρ_d be a metric. Suppose:
- Σ is countable;
- μ gives positive probability to every legal history of length ≤ T;
- w_t > 0 for t ≤ T and w_t = 0 for t > T (a horizon T).

Then ℓ_μ(R) = 0 iff R is exact on every legal history of length ≤ T.
- This does **not** imply ker s ⊆ ∼, which concerns all lengths.
- Without full support, ℓ_μ = 0 allows arbitrary errors outside the support. A shift of μ onto those histories can make the loss maximal.

*Proof.* A sum of non-negative terms with positive weights is 0 iff every term is 0. ∎

**CE 6.4 (equal loss, different information).**
- *Setting:* Σ = {x0, x1, y0, y1}; each input sets bit x or bit y; start (0, 0); inputs i.i.d. uniform. Decisions d_x = x and d_y = y, equally weighted, with 0/1 error. Time weights w_t > 0 for some t ≥ 2. Budget: 2 states.
- *Result:* "track x" and "track y" are the only optimal 2-state realizations (each in two state labellings). They have equal loss, and their kernels are incomparable.
- At time t, the error equals P(untracked bit = 1) = ½(1 − 2^{−t}).
- *Evidence:* checked exhaustively over all 2-state realizations (both initial states, every update and decoder), by two internal reviewers independently. At t = 1, all realizations tie, hence the requirement on the weights.
- *Consequence:* an audit hard constraint on y admits only "track y".
- *Penalties (ANA):* in this equal-weight example, **any** positive penalty on violating y also selects track-y. With **unequal** decision weights, a fixed penalty chosen without knowledge of the weights can be outweighed. A hard constraint is the robust formulation, not the only valid one.

**Observation 6.5 (approximations need not coarsen the exact state). FIN, pre-declared, synthetic.**
- *Population:* 60 random minimal 4-state machines, each having a non-trivial congruence, with a budget N equal to the smallest non-trivial congruence size.
- *Result:* the best N-state approximation strictly beats every coarsening of the exact kernel in **35 / 60** machines at horizon 6, and **30 / 60** at horizon 10.
- *Hand-checkable instance (exact fractions; re-verified independently by the Ch. 6 reviewer):*
  - *Machine:* δ(0,·) = (2,1), δ(1,·) = (3,3), δ(2,·) = (3,1), δ(3,·) = (3,2), over inputs {0,1}, with outputs [1,1,1,0] and start state 0.
  - *Loss:* L = (1/7) Σ_{t=0..6} P[error at t], inputs i.i.d. uniform.
  - *Trivial coarsening:* the 1-block coarsening has loss 195/448.
  - Best coarsening (the only non-trivial one, {0}|{1,2,3}): loss **27/64**.
  - Best 2-state approximation ("remember whether the last input was 0", not a function of the exact state): loss **67/448**.
- *Limits:* synthetic population, n = 4, one distribution family. **Not a theorem.**

**Proposition 6.6 (decision sets and approximations). THM / CE.**
- Exact kernels refine monotonically: ∼_{𝔇∪𝔇′} = ∼_𝔇 ∩ ∼_{𝔇′}.
- Approximate optima do not. Reweighting CE 6.4 switches the optimum between incomparable kernels.

**Consequence (ARCH; the policy is STAKE).** Approximate representations are best placed in **labelled projections**, kept apart from an exact authoritative state for the designated critical decisions. This is the D9 principle; Observation 6.5 **supports** it but does not prove it.

## 6.2 Nondeterminism

**DEF 6.7.**
- A **nondeterministic system** is (Q, Q₀, T ⊆ Q × Σ × Q, out), where out : Q → V is the decision value on states.
- Its **belief set** after h is B(h) := the set of states reachable from Q₀ along h.
- The **set-valued decision** is D(h) := {out(q) : q ∈ B(h)}.
- *Blocking convention:* if B(h) = ∅, D(h) := ∅ is an observable value.

**Theorem 6.8 (canonical quotient of belief sets). THM.** Take L = Σ\* and 𝔇 = {λ ≡ 1, D}; λ adds nothing.
- (a) h ≈ h′ :⇔ ∀w : D(hw) = D(h′w) equals ∼_𝔇 (DEF 3.1).
- (b) So ≈ is a right congruence, and Σ\*/≈ is the coarsest sufficient kernel. It is unique up to isomorphism among trim realizations with that kernel, and its state count is a lower bound when Σ\*/≈ is finite (Thm 3.8).
- (c) "May-v" (v ∈ D) and "must-v" are functions of D, and are covered. *Note:* with the blocking convention, D = ∅ makes "D ⊆ {v}" vacuously true. Define must-v as D = {v} if vacuous truth is unwanted.

*Proof.*
- The map h ↦ B(h) is the run of a deterministic realization: the subset construction, with update B ↦ {q′ : (q, a, q′) ∈ T for some q ∈ B}. It is sufficient, because D is a function of B, but generally not minimal.
- Apply Ch. 3 to 𝔇. ∎

**Scope of the equivalence.**
- ≈ is a **linear-time** equivalence: it concerns what outcomes are possible along a history.
- **Branching-time** distinctions (bisimulation) are finer, and are **not** captured. *Example:* a.(b.c + b.d) and a.b.c + a.b.d have the same D-values but are not bisimilar.
- With acceptance-only decisions, ≈ also merges a.(b + c) and a.b + a.c. It separates them only if readiness or refusal (per branch) is a decision value. Total blocking is already observable here (D = ∅), but it does not separate them, since neither blocks along ab or ac.
- *Which observer power applies is a modelling choice (ASM).*

**CE 6.9 (compact nondeterministic models are not unique). CE + FIN.**
- *Setting:* L = 0\*(ε | 1) over {0,1}.
- Two non-isomorphic 2-state NFAs accept it, each with one initial state 0 and finals {0, 1}: δ(0,0) = {0}, δ(0,1) = {1} versus δ(0,0) = {0,1}, δ(0,1) = {1}.
- No 1-state NFA accepts L. *THM:* a 1-state NFA accepts ∅, {ε}, or A\* for some A ⊆ Σ, and L is none of these.

**Fact 6.10 (LIT, classical).** "The n-th symbol from the end is 0" has an (n+1)-state NFA but a minimal deterministic state with 2ⁿ states (checked for n ≤ 6). **Canonical is not the same as compact.**

**CE 6.11 (merging states).**
- **Merging two NFA states with equal future languages preserves the language (THM).** *Proof sketch:* show by induction on |w| that every state's future language is unchanged after the merge. Base case: finality agrees because ε ∈ L(p) ⇔ ε ∈ L(q). Step: a path through the merged state can be rerouted through p or q, since L(p) = L(q).
- Merging states with different futures **may or may not** enlarge the language. *Counterexample to "must enlarge":* s –a→ p and s –a→ q, with p, q and f all final, and q –b→ f. Merging p and q keeps the language {a, ab}.

**Proposition 6.12 (recording choice outcomes). ANA.** If every nondeterministic choice is resolved by an outcome that is **recorded as an input letter**, then the extended system is deterministic over the extended alphabet, and Ch. 3 applies. This needs:
- (a) the outcomes are observable when decisions are taken;
- (b) the decisions are restated over the extended alphabet, so "for all continuations" now ranges over choices too;
- (c) the order and timing of choice events is fixed, including for concurrent choices;
- (d) probabilistic outcomes keep a separate distributional model;
- (e) the resulting kernel describes the **extended** interface, not the original one.

**Probabilistic systems are a different topic.**
- Belief distributions replace belief sets.
- *LIT, unverified:* the minimal **linear** dimension of a weighted representation is unique up to change of basis (Schützenberger; Carlyle–Paz). The minimal **state count** is not unique.
- Full treatment: **OPEN** (Ch. 14).

## 6.3 Limitations and open problems

- **OPEN:** approximation under nondeterminism, which needs a declared choice between worst-case (demonic) and expected loss.
- **OPEN:** probabilistic records.
- **OPEN:** branching-time decisions. Whether such decisions are required at all is a **STAKE** question: the D10 principle requires an explicit justification for "paths not taken".

## 6.4 Traceability (ARCH)

| Result | Engineering consequence |
|---|---|
| Obs. 6.5 | approximate views are labelled projections; the authoritative state covers the critical decisions exactly (D9, provisional). *Supported by synthetic evidence, not proved* |
| CE 6.4 | prefer hard constraints for audit requirements; they are robust when weights are unknown (ANA) |
| Prop. 6.12 | record actor choices, oracle results and timing as events (D10, provisional), under conditions (a)–(e) |
| Thm 6.8 | where hidden nondeterminism remains, the authoritative object is the quotient Σ\*/≈ of belief-set behaviour; compact nondeterministic encodings are a design choice |
