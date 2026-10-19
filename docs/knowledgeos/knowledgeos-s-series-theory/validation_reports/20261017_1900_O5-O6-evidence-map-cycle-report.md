# Research cycle report: O-5 (approximate sufficiency), O-6 (nondeterminism), D4 proposal, evidence map

| Field | Value |
|---|---|
| Author | Session B (Professor), as worker and integrator |
| Clock | Started 18:55. File created 19:00. Review integration appended later, with clock times. |
| Authorization | D1 conditional · D2 paused · D3 v0.4 unapproved draft · D4 proposal only · D5 conditional · D6 fresh-context sub-agent · D7 synthetic only · D8 candidates only |
| Read | Validation reports 1752–1849; manuscript v0.4 §15 (read only); my own filter scripts (read only, for the patch proposal) |
| Not read | Any corpus, family, seal, hold-out or S5 material; the governance log |
| Computed | Synthetic only: `synthetic-checks/o5/`, `synthetic-checks/o6/` |
| Labels | PROVED (worker) → after review: INTERNALLY REVIEWED — FRESH-CONTEXT SUB-AGENT · COMPUTATIONALLY CHECKED (synthetic) · COUNTEREXAMPLE · UNVERIFIED-LITERATURE · PROPOSED · PRINCIPAL-DECISION-REQUIRED |

## 1. Entry assessment and dependency map

```
O-5 (loss) ──► the approximation policy for projections ──► H-14, CR-7
   ▲ needs O-6's choice of loss model (worst-case vs expected) for nondeterministic parts
O-6 (equivalence) ──► H-13 (record choice outcomes as events) ──► input completeness (C-6(ii))
D4 proposal ◄── 1752…1849 corrections (+ P-9: Refinement 15.5 was wrong for hereditary decoders)
Evidence map ◄── all of the above; CORPUS EVIDENCE: still none (Gate D blocked by D2)
```

**Binding constraint.** D2 blocks the safety gate, and with it every primary-source read. This cycle is entirely analytical and synthetic.

## 2. O-5: approximate sufficiency and a declared loss

### 2.1 Setting

**Histories and decisions.**
- Histories h ∈ Σ\*; deterministic required decisions d : Σ\* → V_d.
- A recursive realization (S, s₀, u, f) answers f_d(s(h)).
- **Exact** means f_d ∘ s = d on all histories. Equivalently, ker s ⊆ ∼ with suitable decoders.

**Four notions kept apart.**

| Notion | Meaning |
|---|---|
| exact behavioural equivalence | ∼ |
| approximate equivalence | errors tolerated on some histories or values |
| statistical prediction | a distribution over outcomes |
| decision sufficiency | the right action under a declared cost |

### 2.2 Candidate losses (each needs ρ or c on V_d, and a set of histories)

| Loss | Definition | Measures | Assumes | Permits | Guarantees provable |
|---|---|---|---|---|---|
| L_wc | sup_h ρ(f(s(h)), d(h)) | worst-case error | metric ρ on V_d | nothing beyond ρ-tolerance | yes (distribution-free) |
| L_T | sup over \|h\| ≤ T | bounded-horizon worst case | horizon T | errors after T | yes |
| L_μ | Σ_t w_t E_μ ρ(f(s(H_t)), d(H_t)) | expected error | process μ, time weights | rare-history errors | in expectation only |
| L_c,μ | as L_μ, with an **asymmetric cost** c(true, output) | downstream decision cost (e.g. a false Zero ≫ a missed Zero) | μ, c | costed errors | in expectation |
| L_rob | sup_{μ ∈ M} L_μ | robustness to shift | shift set M | errors outside M | over M only |
| Hard constraints | exact for 𝔇_crit, plus admissibility 𝒜 (privacy, audit, separation, caps) | non-negotiable requirements | a declared 𝔇_crit and policies | — | lexicographic feasibility |

**Regret.** For deterministic decisions, the exact kernel has zero loss, so regret = loss. For stochastic targets (Φ: belief → commitment), regret is measured against the Bayes decision under the exact belief state.

### 2.3 Results

| # | Result | Status |
|---|---|---|
| **O5-1** | **No free approximation.** If s has a deterministic update and f_d(s(h)) = d(h) for *all* histories (L_wc = 0 under 0/1 ρ), then ker s ⊆ ∼, so s is exact. *Proof:* ker s is a right congruence inside ⋂ker d, hence inside ∼. **Meaning:** a recursive kernel cannot be "approximately exact on all histories". Approximation needs a declared set of tolerated histories (horizon, distribution) or a graded ρ. | PROVED |
| **O5-2** | **Support.** If μ gives positive probability to every history of length ≤ T, and w_t > 0 for t ≤ T, then L_μ = 0 ⇔ L_T = 0. Without full support, L_μ = 0 allows arbitrary errors off support, so a shift μ′ that puts mass there can make the loss maximal. | PROVED |
| **O5-3** | **Equal loss, different partitions.** Overwrite dynamics Σ = {x0, x1, y0, y1}, uniform; decisions d_x = x, d_y = y with equal weight; budget 2 states. "Track x" and "track y" have **equal** L_μ: each errs on the untracked bit with probability ½(1 − 2^{−t}) at time t. Their partitions are incomparable. With an audit hard constraint on y, only "track y" is admissible. | COUNTEREXAMPLE |
| **O5-4** | **Weighted-sum hazard.** Replace the hard constraint in O5-3 with a penalty λ for an audit violation. If the weight on d_x exceeds the weight on d_y + λ, the optimizer chooses "track x" and violates the audit. A finite λ can always be outbid. **Hard constraints must be lexicographic, not weighted.** | COUNTEREXAMPLE |
| **O5-5** | **Approximate optima need not coarsen the exact kernel** (synthetic search; hand verification requested). Machine M: transitions [(1,1), (1,2), (0,2)], outputs [0,1,0], i.i.d. uniform inputs, T = 6. Best 2-state realization: "remember whether the last input was 0", loss **0.2455**. Best 2-state realization that is a function of M's state: **0.4241**. In 27 of 40 random machines, *no* optimum was a function of the exact state (post-hoc measure). **Meaning:** an approximate kernel may use information the exact kernel discards. The exact theory does **not** constrain approximate schemas. | COMPUTATIONALLY CHECKED (one implementation); the 27/40 count is POST HOC |
| **O5-6** | **Non-nested budgets** (pre-declared Q4). In 12 of 15 machines, no 3-state optimum refines any 2-state optimum. More budget can require a schema **migration**, not a refinement. | COMPUTATIONALLY CHECKED, synthetic population only |
| **O5-7** | **Shift** (pre-declared Q3). In 6 of 40 machines, the optimal partitions under p = 0.5 and p = 0.8 are disjoint. | COMPUTATIONALLY CHECKED |
| **O5-8** | **Non-unique optima** (pre-declared Q1). 8 of 40 machines have ≥ 2 distinct optimal 2-state partitions. | COMPUTATIONALLY CHECKED |
| **O5-9** | **Growing 𝔇.** Exact kernels refine monotonically (∼_{𝔇∪𝔇′} = ∼_𝔇 ∩ ∼_{𝔇′}). Approximate optima do not: reweighting O5-3 switches the optimum between incomparable partitions. | PROVED; COUNTEREXAMPLE |
| **O5-10** | **Information bottleneck (the corpus's T-5).** max I(Z; D) s.t. I(Z; X) ≤ C optimizes a *snapshot* encoder, with no update constraint. By C-0′, its optimum need not be recursively maintainable. Recursive or predictive variants exist in the literature. | ANALYTICAL; literature UNVERIFIED |
| **O5-11** | **Budget vs loss** is two-objective. The Pareto frontier is the honest output; scalarizing needs a declared exchange rate. | PROVED (elementary) |

**Pre-declared protocol** (in the script header, fixed before the run):
- 40 random minimal Moore machines with 3–4 states over {0,1}, seed 20261017;
- loss L_μ with uniform time weights, T = 6, p = 0.5 (shift p = 0.8);
- all realizations with 2 and 3 states searched exhaustively;
- Q1–Q4 declared in advance.

**Limits.** Tiny machines, one distribution family, a bounded horizon. The results describe this synthetic population only. They give no estimate of any KnowledgeOS quantity.

### 2.4 Recommended formulation [PROPOSED; normative inputs need a decision]

A **lexicographic program**:
1. **Feasibility:** admissible under the declared policies. Privacy is counted on histories, actors or outputs (CR-1); audit and separation are decisions (CR-2); caps are flagged (CR-5).
2. **Hard exactness:** L_wc = 0 for 𝔇_crit. Candidates: legality, Zero/FalseZero, authorization, identity and audit decisions.
3. **Soft objective:** L_c,μ for 𝔇_soft, under a declared μ, cost matrix c and horizon. Report L_wc and L_rob alongside.
4. **Resources** as constraints. Report the Pareto frontier.

**Key recommendation (D9):** **the authoritative kernel K is exact.** O5-1 shows a recursive kernel exact on all histories is forced anyway. Approximate representations are permitted only as **labelled projections** (Π_t, ML assistants, forecasts). This is consistent with the corpus's AX-1, "Probability ≠ Knowledge" (SOURCE-REPORTED).

**Requires a decision:**
- whether to adopt this;
- the membership of 𝔇_crit;
- μ, c and M for any approximate projection.

## 3. O-6: behavioural equivalence under nondeterminism

### 3.1 Models kept apart

| Model | Definition |
|---|---|
| N1 relational | transitions T ⊆ S × Σ × S, outputs on states; histories are input words; the observer sees inputs and decision values |
| N2 | multiple permitted outputs for an input |
| N3 linear-time (trace) | observations are sequences |
| N4 may / must | may: some resolution yields v; must: all resolutions yield v |
| N5 branching (bisimulation) | the observer can test branching structure |
| P probabilistic | distributions over successors; **not** nondeterminism |
| ⊥ partiality | observable failure vs "don't care" |

### 3.2 Candidate equivalences

| Equivalence | Distinguishes | Preserves | Congruence and quotient | Unique canonical kernel? |
|---|---|---|---|---|
| **E-set:** h ≈ h′ iff for all w, the *set* of possible decision values after hw is equal | possible outcomes, and with them may and must (both are functions of the set) | all set-valued decisions | yes: Nerode on the determinized (belief-set) system | **yes**, the minimal deterministic quotient over belief sets (C-6 transfers via the subset construction) |
| E-trace: may-acceptance only | only which sequences are possible | may-decisions | yes | yes, but it **merges** histories that differ in must-behaviour |
| E-bisim: coarsest bisimulation on states | branching structure | branching-time decisions | yes, on states | unique coarsest bisimulation (literature: Paige–Tarjan, UNVERIFIED); a state notion, not a history notion |
| E-prob: equal future observation distributions | probabilities | expected-value decisions | yes, on belief distributions | the minimal *linear* dimension is unique up to change of basis (Schützenberger / Carlyle–Paz, UNVERIFIED); the minimal *state count* is not |

**Trace vs may/must.**
- Under linear-time trace semantics, a·(b + c) and a·b + a·c are equivalent.
- They differ in must-behaviour: after a, "b is surely accepted" holds only in the first.
- E-set separates them; E-trace merges them.
- **Which equivalence is right is a modelling choice:** what can the observer see? It is not a theorem.

### 3.3 Counterexamples (smallest found)

- **N-CE1: the minimal NFA is not unique** [COMPUTATIONALLY CHECKED, bounded; hand-checked].
  - L = 0\*(ε | 1) has two non-isomorphic 2-state NFAs, both with finals {0,1}:
    - δ(0,0) = {0}, δ(0,1) = {1};
    - δ(0,0) = {0,1}, δ(0,1) = {1}.
  - In a bounded search (words up to length 7), 27 of 208 languages with minimal NFA size 2 have ≥ 2 non-isomorphic minimal NFAs.
  - The minimal DFA (canonical) is unique.
- **N-CE2: size blow-up** [classical; COMPUTATIONALLY CHECKED for n ≤ 6].
  - "The n-th symbol from the end is 0" has an NFA with n + 1 states but a minimal deterministic kernel with **2ⁿ** states.
  - So canonical ≠ compact.
- **N-CE3: merging states is not generally safe** [ANALYTICAL].
  - In an NFA, merging two states with different future languages adds accepted words.
  - Merging is safe for states with equal future languages, or bisimilar states.
  - The deterministic "every right congruence inside ∼ is safe" has no direct NFA analogue on states.
- **N-CE4: semantics choice changes the kernel** [ANALYTICAL; classical example]. As in §3.2: E-trace and E-set give different quotients.

### 3.4 Theorem-transfer map

| Result | Under E-set (belief-set determinization) | For nondeterministic *representations* (NFA states) |
|---|---|---|
| C-0′ (fold ⇒ sufficient) | **transfers** (belief-set update is deterministic) | fails as stated; needs set-valued states |
| C-1 / C-1⁺ | transfers (undecidable in general); for finite NFAs equivalence is decidable (PSPACE-complete, literature UNVERIFIED) | same |
| C-6 canonical quotient | **transfers** | **fails**: N-CE1 |
| Minimal state count unique | transfers for the deterministic kernel | **fails** (N-CE1); exponential gap (N-CE2) |
| R2, R4 (order theory) | transfer unchanged (pure lattice facts) | transfer for any family of equivalences |
| A2, B2 | transfer on the determinized automaton | open |
| Kernel information vs schema | transfers, and the gap widens: canonical (DFA) vs compact (NFA) | — |
| Composition (𝔇₁ ∪ 𝔇₂ ⇒ product) | transfers | transfers for products |

**Open problems:**
- O-6a: approximate sufficiency under nondeterminism;
- O-6b: whether 𝔇 may contain branching-time (counterfactual) decisions;
- O-6c: partial observability with probabilities, where the PSR route applies.

### 3.5 The link to O-5

- Pure (adversarial) nondeterminism calls for a **demonic worst-case** loss over resolutions.
- Probabilistic choice calls for an **expected** loss.
- Mixed systems need both, with the adversary declared.
- So O-5's loss model depends on O-6's classification of every choice point.

### 3.6 Recommendation [PROPOSED; H-13]

**Record the outcome of every choice as an event:** actor choices, oracle results, timing.
- Then the history is complete (C-6(ii)), the kernel is deterministic over the event history, and the whole deterministic theory applies.
- Residual nondeterminism, from genuinely hidden information, is handled by belief-set or probabilistic **projections**, never by the authoritative kernel.

**Decision required (D10):** whether 𝔇 may include branching-time decisions. Recommendation: no, unless a stakeholder requires it.

## 4. D4 proposal

Written as `../D4-CORRECTION-PROPOSAL-20261017_1859.md`. It contains 11 proposed changes (P-1…P-11) and 1 unresolved item (U-1). **None is applied.**

It found one **new** error beyond the earlier checklist (P-9). Refinement 15.5 of the manuscript says the coarsest 𝔉-sufficient state is "in general finer" than the Nerode quotient, and that it hinges on "closure under composition". B2 shows that for hereditary decoder classes it **is** the Nerode quotient, and that the relevant criterion is ⋁𝒜 ∈ 𝒜.

## 5. Theory-to-architecture evidence map (every row is a hypothesis; nothing is adopted)

| H / CR | Domain question | Formal basis | Assumptions / known failures | Evidence status | Before adoption |
|---|---|---|---|---|---|
| H-1 history authoritative; K = fold | where truth lives | C-0′a, C-6 | determinism, input completeness; fails for snapshots | INTERNALLY REVIEWED math; **no corpus evidence** | Gate D (S2763 transition model); stakeholder |
| H-2 projections are pure folds | projection design | C-0′c | — | INTERNALLY REVIEWED | architecture decision |
| H-3 events carry time, actor, authorization, oracle outcomes | event schema | C-6(ii) | — | INTERNALLY REVIEWED | stakeholder list of inputs |
| H-4 𝔇 first-class and versioned | kernel evolution | T1; O5-9 (exact monotone, approximate not) | — | INTERNALLY REVIEWED | versioning policy (PDR) |
| H-5 / CR-2 audit = decisions | audit design | R4(b) | needs the audit list | INTERNALLY REVIEWED | stakeholder audit list |
| H-6 / CR-1 privacy on histories, actors, outputs | privacy policy | E3′ (trim; the finite case is open) | padding defeats state counts | INTERNALLY REVIEWED | privacy policy (PDR) |
| H-7 gap and Zero are derived, recomputable | assessment | C-5 (+ attainability) | contract-relative | INTERNALLY REVIEWED; T-2 SOURCE-REPORTED | Gate D (gap-theory primary) |
| H-8 append-only provenance | retention | C-3: only if the **joint** 𝔇 forces K = H | otherwise a stakeholder requirement | conditional | stakeholder |
| H-9 category non-collapse; AI grants no authority | governance | none mathematical | — | governance requirement (AX-1 SOURCE-REPORTED) | governance decision |
| H-10 the five core operations as commands | command set | none yet (O-3) | completeness and independence unproved | SOURCE-REPORTED (S2763 unverified) | Gate D |
| H-11 / CR-3 vet update restrictions | update policy | A2–A5 | infinite-state injective failures | INTERNALLY REVIEWED | architecture track |
| H-12 / CR-4 schema = infrastructure | layering | B2, O7, P-3 | encoding-level update restrictions open | INTERNALLY REVIEWED | architecture track |
| CR-5 caps flagged | aggregation policy | E2 | — | COMPUTATIONALLY CHECKED | stakeholder |
| **H-13 (new)** choice outcomes recorded as events | nondeterminism | O-6 §3.4–3.6 | hidden information remains | ANALYTICAL; literature UNVERIFIED | D10 |
| **H-14 (new)** approximation only in labelled projections; K exact | ML and forecasting placement | O5-1, O5-4, O5-5 | — | PROVED / COUNTEREXAMPLE / synthetic | D9 |
| **CR-6 (new)** 𝔇's specification class has decidable equivalence | tooling, comparison | C-1⁺ | — | INTERNALLY REVIEWED | architecture track |
| **CR-7 (new)** hard requirements are lexicographic constraints, never penalties | optimization or ML policy | O5-4 | — | COUNTEREXAMPLE | D9 |

**A mathematical result does not establish a bounded context or aggregate.** Every row needs domain evidence (Gate D) or a stakeholder decision.

## 6. Prioritized TODO list

| ID | Objective | Depends on | Status | Next action |
|---|---|---|---|---|
| T-1 | Safety gate | D2 (rule owner), D1 reviewer | **BLOCKED** | rule-owner clarification |
| T-2 | Five primary reads | T-1, D5 | BLOCKED | — |
| T-3 | Internal review of O-5, O-6 and the D4 proposal | — | IN PROGRESS (sub-agents launched) | integrate verdicts |
| T-4 | External review (other model family or human) | Principal approval | PROPOSED | approve reviewer |
| T-5 | Literature verification (Pfleeger, Paull–Unger, Skowron–Rauszer, Sénizergues, Larsen–Skou, Eilenberg, Paige–Tarjan, Schützenberger, Carlyle–Paz, information-bottleneck variants) | external access or a human | PROPOSED | assign |
| T-6 | Hand-verify O5-5; second implementation of the O-5 experiment | — | IN PROGRESS | reviewer |
| T-7 | E2 for ≥ 5 classes (symmetry-reduced search) | D7 | PROPOSED | low priority |
| T-8 | O-1′, encoding-level update restrictions, E3′ finite case, O-6a/b/c | — | OPEN | analytical |
| T-9 | Apply D4 → v0.5 | review + Principal approval | AWAITING | after T-3 |
| T-10 | O-3: does 𝒪_core generate Σ? | T-2 | BLOCKED | — |
| T-11 | Reconcile the model with sources (determinism, input completeness, definedness) | T-2 | BLOCKED | — |
| T-12 | Architecture decision map | D9, D10, T-11 | PROPOSED | — |
| T-13 | README rules 1/4, revision table, read-log and source-register fixes | edit authorization | AWAITING | — |
| T-14 | F-Series integration | Principal | DEFERRED | — |

## 7. Principal decision sheet (only decisions that genuinely need you)

| # | Decision | Why it matters | Recommendation | Consequence if deferred |
|---|---|---|---|---|
| D2 | Clarify the sealed-list rule with its owner | blocks the safety gate and all source verification | ask the owner whether in-script use, never displayed, is allowed | theory stays unconnected to evidence |
| D1 | Confirm the safety reviewer | the gate cannot start without one | ChatGPT reviewer on the script source; a human runs the fake-seal fixtures | — |
| D6-ext | Approve external proof review | internal review is not independent | ChatGPT reviewer using the two-stage packet | proofs stay "internally reviewed" |
| **D9 (new)** | Is approximation allowed in the authoritative kernel? | decides where ML belongs | **no**: K is exact; approximations live only in labelled projections, with hard requirements as constraints | ML work has no admissible target |
| **D10 (new)** | Record all choice outcomes as events; may 𝔇 include branching-time decisions? | decides whether the deterministic theory applies | **record outcomes; no branching decisions** unless a stakeholder needs them | O-6 stays a family of models |
| D11 (deferrable) | If approximate projections are used: 𝔇_crit, μ, cost matrix c, shift set M | defines what "good approximation" means | defer until D9 | — |

## 8. Validation and provenance record

- **Inputs:** listed in the header. No restricted material was opened, no corpus filter was run, and no sealed list was used.
- **Computation:** `synthetic-checks/o5/o5_experiment.py` (protocol declared in the header before running), `o5_posthoc.py` (labelled post hoc), `synthetic-checks/o6/o6_nfa.py` (one bug fixed before any result was read: tuple vs list).
- **Files created:** this report; `../D4-CORRECTION-PROPOSAL-20261017_1859.md`. No governed file was edited. No git operation.
- **Status:** worker results until §9, where the internal review is integrated.

## 9. Internal review integration (appended 19:05 clock; §§2–8 above are left as written)

**Process.**
- Three fresh-context reviewer sub-agents worked in parallel, on O-5, O-6, and D4 + evidence map.
- The O-5 and O-6 reviewers attempted falsification first and wrote their own synthetic code.
- **Label: INTERNALLY REVIEWED — FRESH-CONTEXT SUB-AGENT (same model family).** This is not independent validation.
- **Where this section conflicts with §§2–7, this section governs.**

### 9.1 O-5

| Item | Verdict | Integrated correction |
|---|---|---|
| O5-1 | CONFIRMED | elementary. It does **not** make exactness "forced"; it holds only once L_wc = 0 is demanded |
| O5-2 | CONFIRMED WITH CORRECTIONS | needs ρ(a,b) = 0 ⇔ a = b. L_T = 0 gives exactness only up to length T, **not** ker s ⊆ ∼ |
| O5-3 | CONFIRMED | brute force over every 2-state realization: "track x" and "track y" are the only optima |
| O5-4 | CONFIRMED WITH CORRECTIONS | **overstated.** With fixed weights, a finite candidate set and violations bounded away from 0, a large enough λ (an exact penalty) reproduces the lexicographic optimum. The real hazards are a λ chosen without knowing the weights, and violations that can be made arbitrarily rare. Lexicographic is the *robust* choice, not the only valid one. **CR-7 is restated accordingly** |
| O5-5 | **CONFIRMED NUMERICALLY; FRAMING WITHDRAWN** | the loss values are exact (55/224 ≈ 0.2455). But 0.4241 is the **constant predictor**: M has **no** 2-block congruence, so the only recursive coarsening within budget is trivial. The snapshot partition {0,2}\|{1} is exact but not recursive, a concrete instance of C-0′. **Withdrawn:** "approximation uses information the exact kernel discards". **Replacement:** "under a budget below the smallest non-trivial congruence, approximate optima cannot be coarsenings of the exact kernel, because none exist" |
| 27/40 (post hoc) | confounded | 29/40 machines have no non-trivial 2-block congruence. Only 8/40 have a non-trivial quotient optimum. So the 27/40 is **not evidence** for the withdrawn claim |
| O5-6…O5-8 | reproduced | horizon-bounded, synthetic population only |
| O5-9 | CONFIRMED | near-trivial under a fixed budget |
| O5-10 | CONFIRMED WITH CORRECTIONS | analytical; the literature remains UNVERIFIED |
| Protocol | CONFIRMED (code matches the declaration) | "fixed before the run" **cannot be verified** from the file. There is no timestamp or commit evidence, and I disclose this |
| **D9** | **OVERSTATED** | it is a **normative proposal**, not a consequence of O5-1. It depends on 𝔇_crit. **Scope resolved:** K is defined over 𝔇_crit; every 𝔇_soft decision lives in labelled projections |

**New open question O5-12:** behaviour for budgets at or above the smallest non-trivial congruence, the regime where "coarsening" is a non-vacuous comparison.

### 9.2 O-6

| Item | Verdict | Integrated correction |
|---|---|---|
| N-a (E-set) | CONFIRMED WITH CORRECTIONS | needs a **blocking convention** (empty set observable, or a ⊥ sink). "Must" is state-valued, not branching-time. E-set is strictly coarser than bisimulation (a.(b.c + b.d) vs a.b.c + a.b.d) |
| N-b | **CORRECTED** | E-set separates a.(b+c) from a.b + a.c **only if** readiness or refusals are decision values, or blocking is observable as ⊥. With acceptance-only decisions, E-set merges them, as trace equivalence does |
| N-CE1 | CONFIRMED | exact (product of determinized automata). The bounded search is in fact exact for ≤ 2-state NFAs (distinguishing words of length ≤ 6 < 7) |
| N-CE2 | CONFIRMED for n ≤ 6 | **script defect:** the docstring says "subset construction", but the code minimizes the window DFA. The reviewer ran a true subset construction and got the same counts. The docstring is to be corrected (synthetic script, not governed) |
| N-CE3 | **PARTLY REFUTED** | "Merging states with different futures adds words" is false as a universal claim. *Counterexample:* s –a→ p, s –a→ q, both final, q –b→ f; merging p and q keeps {a, ab}. **Correct form:** merging states with equal future languages is *sufficient* for safety, not necessary |
| Transfer map | CONFIRMED WITH CORRECTIONS | "minimal state count unique … fails" is mis-worded: the count is a number. What fails is **uniqueness of the minimal automaton up to isomorphism**. "No canonical form" is too strong: restricted canonical forms exist (bisimulation quotient, residual automata; UNVERIFIED-LITERATURE). Lattice transfer is formal; whether a *greatest language-preserving merge* exists for NFAs is **OPEN (O-6d)**. PSPACE-completeness is UNVERIFIED-LITERATURE |
| H-13 | CONFIRMED WITH CORRECTIONS | recording choices *reformulates* the system. Valid only if: (a) recorded outcomes are observable when the decision is taken; (b) decisions are restated over the extended alphabet; (c) order and timing of choice events are fixed, including concurrent choices; (d) probabilistic outcomes keep a separate distribution model; (e) the kernel is for the extended interface |

### 9.3 D4 proposal and evidence map

- **Quotes and hash:** all quotations and the hash match the manuscript.
- **Corrections:** recorded as **amendments r2** inside the proposal file (§ "r2 amendments"). They are not applied to the manuscript.
- **The most important D4 corrections:**
  - **P-9 contained an error.** Separation policies leave the Nerode quotient of the **augmented** decision set (∼′) as the coarsest state, not ∼ itself.
  - "A finer minimum arises under non-hereditary decoders" has **no witness**, so it becomes "may arise".
  - **P-7's direction was wrong:** C-0′a gives a sufficient condition, not an "only if".
  - **Three items were missing:** M1, the Consequence 1 sufficiency wording (l.1438); M2, the Limits must list input completeness and observable definedness; M3, EA "formal content" is conditional.
- **Evidence map:**
  - H-1, H-2, H-7 and H-14 each carried a **design choice inside the hypothesis text**. They are split into "formal fact" and "design choice (PDR)".
  - The CR-1, CR-2 and CR-4 duplicates are merged into H-6, H-5 and H-12.
  - CR-5 is weakened to "can destroy (one checked instance)".
  - H-12 is weakened: O7c shows the update alphabet constrains viable schemas, so a schema is **not purely** infrastructure.
  - **Missing rows added:**
    - legality and definedness must be declared decisions (C-6/C-6′);
    - "predicts" ≠ "maintainable" (C-0′b);
    - anonymity-vs-audit infeasibility is detectable as ∼′ ∉ U (R4(c));
    - encodings are non-unique and may need redundancy (E4/E5);
    - determinism under concurrent writers (OPEN).

### 9.4 Status after review

| Workstream | Status |
|---|---|
| O-5 | complete as a **framework**: a loss comparison and a proposed lexicographic formulation. D9 is reframed as a normative decision. One headline was withdrawn (O5-5), and O5-12 is opened |
| O-6 | complete as a **theorem-transfer analysis**, with corrected counterexamples. N-CE3 was corrected. O-6a…d are open |
| D4 | proposal with r2 amendments; **not applied** |
| Evidence map | corrected per §9.3; **no row adopted**; corpus evidence **none** |
