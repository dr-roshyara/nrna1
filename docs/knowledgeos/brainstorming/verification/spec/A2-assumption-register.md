# A2 — Assumption Register (Candidate KnowledgeOS Theory)

**Status:** ratified layer SEEDED; raw-track layer pending extraction merge.
**Classes:** `STATED` (explicitly labeled in source) · `RULED` (assumed by governance act) · `REQUIRED-UNSTATED` (needed for a derivation/definition to work, not stated — each such entry is a **candidate hidden-assumption finding** for the checkpoint §E) · `ORACLE` (assumption that an external capability exists).
**Nothing is repaired here.** Entries marked REQUIRED-UNSTATED are my identification of a dependency, not corpus content — clearly separated.

## Part R — Ratified layer (v0.1/v0.2 + FA + witnesses)

| # | Assumption | Class | Where it operates | Source / basis |
|---|---|---|---|---|
| AS-R01 | **η is total**: `EC = η(G, IdealState)` is defined for every (G, IdealState) | RULED (synthesis decision, revisitable) | Zero's input; the whole reference layer | v0.2 §5–7 non-claims; OQ-1 |
| AS-R02 | **Requirement set R is finite** | STATED | Zero's totality/termination; O(|R|·cost(eval)) | 025d; GN-46 §6 |
| AS-R03 | **Per-requirement evaluators exist** (rules/statistics/authority as oracles; HumanAuthorization is not a computation) | ORACLE | Zero computability "relative to evaluators" | 025d §25D.12 |
| AS-R04 | **K_t is finite at any t** | STATED | representability; state-space arguments | 049 §49.33 |
| AS-R05 | **Aggregation inputs are strengths sᵢ ∈ [0,1]** on finite multisets | STATED | A₁–A₄ well-definedness | EXP-01 design |
| AS-R06 | **Normalization (dedup/dependency collapse) precedes aggregation** — I-5's claimed properties hold only under this pipeline assumption | STATED (as I-6) but its **carrier `~` is UNDEFINED** | I-5, duplicate-invariance | EXP-01; PF-3 |
| AS-R07 | **Conditional independence** where BAYES-like combination is applied | REQUIRED-UNSTATED at operator level (design doc flags it narratively; operator itself carries no test) | evidence combination | EXP-01; GN-46 §7 numeric demo (0.80→0.985 inflation) |
| AS-R08 | **Three-valued logic handling of Unknown is policy-supplied** (Unknown→Block default in witness) | STATED | DC admissibility | 042 §42.12 |
| AS-R09 | **"The in-force policy" is unique at any time** | REQUIRED-UNSTATED — presupposed by I-11's guard; NOT ESTABLISHED under multiple applicable authorities | I-11, stratification loop | MV-F-7 (recorded prior finding) |
| AS-R10 | **A first in-force policy exists** (genesis) | REQUIRED-UNSTATED — the stratification induction has no ratified base case | I-11 protection claim | MV-F-8 |
| AS-R11 | **⪰_C is a partial order** (reflexive/transitive/antisymmetric) | REQUIRED-UNSTATED — postulated, axioms never stated or argued | governance conflict resolution | 025f §25F.14; MV-F-12 |
| AS-R12 | **Governance semantics are explicitly modeled** (applicability/precedence machine-readable) | STATED (as proviso) | GovernanceResolve computability | 025f §25F.22 |
| AS-R13 | **Bounded action space 𝒜(K_t)**; selector-loop termination additionally needs a budget/deadline/termination policy that is **not part of the ratified model** | STATED (need named by source itself: "termination algebra", 25G.31) | Proposal loop | 025g |
| AS-R14 | **State equality / evidence equivalence / frame equivalence / requirement identity exist** — an identity calculus is presupposed by I-5, replay/determinism (25D.27), and any Γ↔ρ_A identification | REQUIRED-UNSTATED (4 instances of one missing foundation) | evidence pipeline, Zero determinism, r↔P map | MV-F-22, MV-F-9 |
| AS-R15 | **Epistemic status is not probability**; no probability space is claimed; `Pr(P|E) ≥ 0.95` is a policy example only | STATED (refusal) | statistical layer | 008 §12; 049 §49.10 |
| AS-R16 | **The world is never fully observed** (`X_t ≠ Observed(X_t)`) — structural, not empirical | STATED (I-7) | epistemic boundary | Step 66 |
| AS-R17 | **A6**: authority determines commitment, not evidential truth — normative postulate, not derived | STATED (I-4/A6) | boundary crossing | 008-A6 |
| AS-R18 | **Learn and F are assumed to exist** as functions (uninterpreted symbols) — every dynamic claim about K_{t+1} presupposes them | REQUIRED-UNSTATED (openness of η is governed via OQ-1; Learn/F carry **no** analogous flag) | state dynamics | 049 §49.92; MV-F-11 |
| AS-R19 | **The 8-primitive set is a candidate reduction, not proven minimal/complete/unique** — any use of "the kernel" as if minimal imports an unstated minimality assumption | STATED (as caveat) | K_t structure; kernel claims | 049/050; OQ-2 |
| AS-R20 | **Zero's status set 𝒮 is closed** (fixed membership) | REQUIRED-UNSTATED — source says "initially I propose"; membership drifts (Missing, Failed) | I-9; Zero vector typing | 025d; MV-F-3 |

## Part W — Raw research track (pending extraction merge)

To be merged: assumptions stated in step-031 (universe construction, probability layer), step-004 aggregation axioms, uncertainty calculus assumptions (step-027/082: independence, distributional, calibration), Q-series transition assumptions, Steps 201–205 (freeze assumptions), SNF measurement assumptions, Doignon–Falmagne adoption assumptions, Freedman constraints.

---

*Checkpoint feed: every REQUIRED-UNSTATED row above is a candidate for report section E (hidden assumptions); AS-R09/R10/R11/R14/R18/R20 are simultaneously candidates for section C (load-bearing) risk ranking.*
