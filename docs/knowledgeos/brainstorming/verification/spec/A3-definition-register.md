# A3 — Definition Register (Candidate KnowledgeOS Theory)

**Status:** ratified layer SEEDED (first-hand reading, 2026-08-29); raw-track layer pending extraction merge.
**Tag vocabulary (mandate V1-C):** `EXPLICIT` (formal definition stated in source) · `INFERRED` (usable definition assembled from source statements) · `RECONSTRUCTED` (definition exists only as a synthesis-layer reconstruction) · `UNDEFINED` (symbol used, no definition anywhere) · `SIGNATURE-ONLY` (typed at arrow level, no construction).
**Nothing here is repaired.** Where the prior GN-46 audit judged completeness, that judgment is recorded as *prior-audit claim* — to be independently re-checked at Level 1, not assumed.

---

## Part R — Ratified layer (v0.1 + v0.2 + FA; L2 formal model)

### R.1 Primitive frame

| # | Symbol / term | Definition as recorded | Tag | Source | Prior-audit note (GN-46) |
|---|---|---|---|---|---|
| D-R01 | `X_t` | actual world state; never fully observed: `X_t ≠ Observed(X_t)` | EXPLICIT (as inequality only; X_t itself unconstructed — world model deliberately outside) | v0.1 §1; Step 66 | F (world dynamics `S_{t+1}=F(S_t,A_t,U_t)`) uninterpreted |
| D-R02 | Knower | owner of problem, purpose, Ideal State, final authority | EXPLICIT (role definition, non-mathematical) | v0.1 §1; 151244, Q11/Q18, 008-A6 | internally unmodeled (deliberate) |
| D-R03 | `G` (Goal/Purpose) | Knower-owned intent inducing knowledge requirements | EXPLICIT (informal) | v0.1 §1; 151244, 025d | internally unmodeled |
| D-R04 | IdealState | the epistemic understanding the Knower considers sufficient; changes Knower-authorized only | EXPLICIT (informal) | v0.1 §1; Q11, Q18 | internally unmodeled |
| D-R05 | `EC = η(G, IdealState)` | goal-derived requirement set: *what must be known or satisfied* | η: **SIGNATURE-ONLY** — no construction; totality ASSUMED (OQ-1) | v0.2 §1 (R-2); 025d/025e | "NO — signature only"; computability NOT ESTABLISHED |
| D-R06 | EC structure | pair `(R_G, Γ_G)` (requirements, sufficiency rules); source-final forms: `(R,Γ,A,V)` 4-tuple, 9-tuple (025e §25E.37), 8-tuple recap (025f) — **source-internal inconsistency, recorded** | PARTIAL-EXPLICIT; Γ's rule language UNDEFINED | 025d §25D.3; 025e; 025f | MV-F-4 (unregistered compression + 8-vs-9 inconsistency) |

### R.2 Observation & evidence

| # | Symbol / term | Definition as recorded | Tag | Source | Prior-audit note |
|---|---|---|---|---|---|
| D-R07 | SourceObservation ≠ SemanticObservation | *what was observed ≠ what was understood* | EXPLICIT (distinction), constructions absent | v0.1 §1; 125403 Option 3 | untested |
| D-R08 | Evidence `e` | design-level tuple `e=(P,π,ρ,κ,τ,δ,…)`; carries dependency structure and roles | EXPLICIT at tuple level; **`~` (epistemic equivalence) UNDEFINED — by the source's own admission** | v0.1 §1; EXP-01 design | PF-3; MV-F-22 (identity calculus missing) |
| D-R09 | Aggregation operators A₁–A₄ | SATURATING `1−∏(1−sᵢ)`, BAYES-like (odds product), MAX, WM — on finite multisets of [0,1] | EXPLICIT formulas | EXP-01 design + CSV | BAYES undefined on {0,1} jointly; WM undefined on ∅ (MV-F-18); operator selection OPEN (OQ-3) |
| D-R10 | Pipeline | `E → E/~ → Q → EA → Conclusion` (dependency resolution precedes aggregation) | EXPLICIT as ordering law; `~` and resolver N UNDEFINED | EXP-01; I-6 | I-5/I-6 hold at pipeline level only |

### R.3 Epistemic state & admission

| # | Symbol / term | Definition as recorded | Tag | Source | Prior-audit note |
|---|---|---|---|---|---|
| D-R11 | `K_t` (KnowledgeState) | state over the 8 primitives `{Entity, State, Event, Observation, Proposition, Relation, Policy, Action}`; `𝒦=(E,S,T,O,P,R,Π,A)` (049 §49.30); practical `K=(V,E,R,Metadata)` (49.32) | EXPLICIT contents; **state equality UNDEFINED** | v0.1 §1; 049/050 | MV-F-22; kernel membership at object level [U] (OQ-2) |
| D-R12 | Epistemic ladder | three statuses `Candidate ⋖ Supported ⋖ Accepted` as a **covering relation** (no skip, I-12); `Committed` = decision-boundary status attached by authority across the A6 boundary (`Accepted ⋖ Committed`) | EXPLICIT (R-3/R-4) | v0.2 §1; 008 | well-defined; witnessed (`ladder_dc_reference.py`); **transition calculus (what moves an item) ABSENT** (AF-F-3) |
| D-R13 | Determination | the `Supported → Accepted` transition under AcceptancePolicy | EXPLICIT | v0.1 §1; 234405 ↦ 008 | hosting single-source (OQ-03 legacy) |
| D-R14 | AcceptancePolicy | determines what may be admitted as accepted knowledge (≠ what evidence supports); **governed, versioned object**; changes routed through DC + BC_Governance (R-1) | EXPLICIT (role); content language UNSPECIFIED | v0.2 §1 | in-force uniqueness under multiple authorities NOT ESTABLISHED (MV-F-7); genesis base case ABSENT (MV-F-8) |
| D-R15 | Layered state model (FA) | admission axis (ladder) + non-admission states REJECTED (terminal, preserved) and CONFLICTED (governed suspension), reachable from any pre-boundary status by governed act; evidence-layer states UNKNOWN ≠ ABSENT ≠ FALSE orthogonal; initial state UNKNOWN | EXPLICIT (D-FA-1) | FA-1 §2; Constitution Arts. 7–9 | adjudication of CONFLICTED: operator UNDEFINED (MV-F-10) |

### R.4 Gap, agency, decision

| # | Symbol / term | Definition as recorded | Tag | Source | Prior-audit note |
|---|---|---|---|---|---|
| D-R16 | `Zero(K, EC) = Z_t` | typed discrepancy over requirements; vector `{(rᵢ, statusᵢ, …)}`; retains *why* unsatisfied; **not a metric** (directed, purpose-relative, structured) | EXPLICIT at vector level; status-set closure NOT ESTABLISHED (source: "initially I propose"); historical signatures `Zero(K,G,EC)`, `Zero(K,K*,EC)`, binary `Zero(K_t,EC_t)` recorded as lineage | v0.2 §1 (R-2); 025d | computable relative to evaluators (witnessed); 𝒮 not closed (MV-F-3) |
| D-R17 | Satisfaction set 𝒮 | nine named values (+ `Missing` added 25D.7; `Failed` used 25D.23, never a member); I-9's "four-way" is the ratified compression | EXPLICIT enumeration, NOT CLOSED | 025d §25D.4 | MV-F-2 (I-9's "four-way" INVALID as source description); PF-1 load-bearing losses {Insufficient, Stale, Prohibited} |
| D-R18 | Proposal (selector) | next epistemic action from `(K_t, Z_t, G)`; **no authority**; source forms `Lord(K,Z,G,C)`, `L(K,Z,G,C,P)` — C and P dropped in ratified form | EXPLICIT signature; selection semantics = policy (deliberate) | v0.1 §1; 025g | loop termination NOT guaranteed without budget/termination policy (source's own 25G.31) |
| D-R19 | Decision selector | source forms `(K,G,D,C,P)→d`, `S(K,G,D,M,C)`, `Sārathi(K,D,DecisionModel)` — **unratified** (PF-8) | EXPLICIT in source, NOT RATIFIED | 025h | MV-F-6: ratified flow edge `PROPOSAL → DECISION under DC(d)` not type-correct — nothing ratified produces d |
| D-R20 | `DC(d)` | six-tuple `(Pre, Inv, Auth, Post, Temporal, Evidence)`; source refinement: 7-tuple `⟨P,I,A,E,Q,T,O⟩` with Q = epistemic sufficiency | EXPLICIT components; **no ratified admissibility conjunction over exactly the 6** (42.9 has 4 conjuncts, 42.41 belongs to the 7-tuple) | v0.1 §1; 042 | MV-F-5; PF-7 (Q loss load-bearing for I-3's first disjunct) |
| D-R21 | Admissibility | conjunction law, no averaging; three-valued handling of Unknown per policy (Unknown→Block semantics) | EXPLICIT for the source's two forms | 042 §§42.9–42.14 | witnessed; sound as stated |
| D-R22 | Authorization | precondition constraint filled via governance, never a processing step | EXPLICIT (COMPOSITION grade) | v0.1 §1; 025h+042+Q18+008-A6 | — |
| D-R23 | Governance algebra | `⪰_C` (context-indexed authority precedence), `Applicable`, `Conflict`, `Resolve → (EffectiveRules, Conflicts, Exceptions, Supersessions, Unresolved)`, escalation law | EXPLICIT in source (025f), **only one row ratified**; ⪰_C's partial-order axioms never stated or argued | 025f | MV-F-12; PF-9 CONFIRMED (I-11 presupposes uniqueness of in-force policy) |
| D-R24 | Stratification loop | `PolicyChangeProposal → DECISION under DC → BC_Governance approval → Policy vN→vN+1`; policy-as-content (∈K_t) vs policy-in-force (versioned governor) split | EXPLICIT (R-1) | v0.2 §3 | genesis base case ABSENT (MV-F-8) |
| D-R25 | `Learn` | `K_{t+1} = Learn(K_t, Obs, Events, Policies, Outcomes)` | **SIGNATURE-ONLY — uninterpreted function symbol** | 049 §49.92 | MV-F-11; no governed OQ flag (unlike η) |
| D-R26 | Action loop | authorized action → new observation | EXPLICIT head / SPECIFIED tail | 025z, 056 | action/execution semantics OPEN (OQ-4) |
| D-R27 | Ω | **historical term only** — decomposed: Ω-a→EC, Ω-b bounded away, Ω-c→X_t (GN-09) | RECONSTRUCTED lineage ruling | v0.1 §1 | vestigial symbol reuses (049 §49.8, 008 Ω_A) are naming hazards only |

### R.5 Ratified invariants (I-1…I-12) — statements as recorded

| # | Statement | Grade as recorded | Prior-audit status |
|---|---|---|---|
| I-1 | Knower owns problem, purpose, Ideal State, final authority | READ, 3-regime chain | hole (unowned AcceptancePolicy) closed by I-11 |
| I-2 | Proposal ≠ decision; selector holds no authority | READ | — |
| I-3 | SufficientKnowledge ≠ ValidDecision ≠ AuthorizedAction | READ (042) | first disjunct has **no carrier** in ratified DC 6-tuple (PF-7/MV-F-5) |
| I-4 | Authority determines commitment, not evidential truth | READ (008-A6) | witnessed |
| I-5 | Duplicate evidence must not increase confidence; independent corroboration must | TESTED (EXP-01) | holds **at pipeline level only**; raw operators fail |
| I-6 | Dependency resolution precedes aggregation | TESTED (EXP-01) | confirmed as ordering law |
| I-7 | `X_t ≠ Observed(X_t)` — model ≠ reality, structurally | READ (Step 66) | — |
| I-8 | No architectural assertion without evidence (method invariant) | READ (109), partially executed | — |
| I-9 | Zero's non-satisfaction typology must not collapse to Boolean | READ (025d) | normative content intact; embedded "four-way" INVALID as source description (MV-F-2) |
| I-10 | Constitution changes require approval | READ (042) — **violated in the real repo per Step 121** (live finding, recorded) | — |
| I-11 | No in-force policy changes without governed, versioned approval decision | REQUIRED-BY-COHERENCE (R-1) | guard evaluable only single-authority (MV-F-7); no genesis (MV-F-8) |
| I-12 | Status transitions follow the covering relation; no skipping | REQUIRED-BY-COHERENCE (R-4) | well-defined; witnessed |

### R.6 Non-collapse distinctions (gate-ratified)

`observation ≠ evidence` · `evidence ≠ determination` · `determination ≠ commitment` · `proposal ≠ decision` · `decision ≠ authorization` · `authorization ≠ execution` · `model ≠ reality` · triple `SufficientKnowledge ≠ ValidDecision ≠ AuthorizedAction`. (v0.1 §2; 042.)

---

## Part W — Raw research track (pending extraction merge)

To be merged from the three extraction sweeps: K_t/S_t tuple families and the ~25 transition forms (Q13/Q15/Q20, step-031, Steps 189/201–205) · the 025-series operator algebras · aggregation axioms (step-004/005) · invariant families (step-048/120, Steps 162/167/197) · computability claims (step-049/069/089) · uncertainty calculus (step-027/082, conditional-evidence, Step 199, SNF).

**Recorded variance carried from V0 (not to be resolved here):** D-01…D-05 (transition forms; K_t/S_t/Z_t/DC arities).
