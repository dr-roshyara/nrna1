# TV-F-018 · TV-F-019 — Wave 3: E1 codomain closure sweep · E2 epistemic ladder dynamics

Date 2026-08-29 · Verifier analyses over source quotations obtained this wave (step-008 full sections, 025f full sections, Step 189 complete transition sweep) plus the extraction registers.

---

## TV-F-018 (E1) — Codomain closure sweep

| Operator | Declared signature | Verdict | Defect class |
|---|---|---|---|
| `Zero` | `𝒦×EC → vectors over 𝒮_gap` (9 declared) | **VIOLATED** — outputs use `Missing` (25D.6/7) and `Failed` (25D.23); 𝒮 never restated | missing definition (set closure) + ill-typed usage — C-014 formalized |
| `EvalContract` | `→ ContractStatus = {Ready, Blocked, Invalid, Indeterminate}` | **VIOLATED** — fifth value `Conflicted` used on the differently-named `ECStatus` (25E.31), never folded in | ill-typed formulation + naming drift |
| `α_ρ` | `EA×P×C → 𝒮_A` (6 values, **without** Committed) | **VIOLATED in-source and acknowledged** — output "Supported + Contested" ∉ 𝒮_A; the file itself warns "careful about a single enum" and supersedes with `Ω_A` (§10) | ill-typed, in-file superseded; ratified layer and book import neither (MV-F-13 now corroborated from source, not just from the prior audit) |
| `ω` | `Ω_A×Event×Policy ⇀ Ω_A` | honest partial signature, **no construction** | missing definition — the 4-dimensional transition calculus placeholder |
| `Lord` | `L(K,Z,G,C,P) → DecisionState` (7-field) | **AMBIGUOUS** — the procedure's terminal outcomes (Blocked/Escalated ∈ 𝒯) are not typed against DecisionState; relation 𝒯 ↔ DecisionState undefined | semantic ambiguity / missing typing |
| `Sārathi` | `S(K,G,D,M,C) → DecisionResult` (5 values incl. its negative outcomes) | **CLEAN** — negative outcomes are first-class codomain members | — (positive result worth recording) |
| Aggregators A₁–A₄ | `Multiset([0,1]) → [0,1]` | **PARTIAL, undeclared** — BAYES undefined on {0,1} jointly; WM on ∅; MAX needs ∅-convention | missing assumption (domain holes) — MV-F-18 confirmed at source level |
| Evaluator `ev` | `𝒦×R×EC → 𝒮_gap` | inherits 𝒮_gap closure defect; `HumanAuthorization` return type unspecified | missing typing |
| Ratified ladder step | covering pairs + typed boundary | **CLEAN** (T-K4) | — |
| Adjudication of CONFLICTED | consumed by FA layered model | **ABSENT** — no signature at all | missing definition (MV-F-10, re-confirmed) |
| Policy application | `{Must,May,MustNot,Conditional}` (202) vs `{Allowed,Forbidden,Conditional}` (Q20 review) | two codomains, no mapping | semantic ambiguity (C-013 formalized) |
| `Resolve` vs `GovernanceResolve` | 6-outcome set (25F.7: s₁ wins/s₂ wins/coexist/exception/superseded/unresolved) vs GR 5-tuple + 3-state vocabulary (Resolved/Unresolved/Invalid, 25F.20) | **NEW: two outcome vocabularies in one file, mapping undefined** (also `UnresolvedItems` vs `Unresolved` token drift) | semantic ambiguity — new micro-finding |
| `Replay` | `𝒦×H → 𝒦` on valid histories | clean under KA4 (partiality declared via validity) | — |
| `Accept_ρ` | `(P,EA) → AcceptedAssertion`, guarded `EA ⊨ Policy` | clean as far as stated; policy language uninterpreted | missing assumption (policy semantics) |

**Net:** 5 codomain violations (2 acknowledged in-source), 3 ambiguities, 3 absences, 2 clean positives. Every defect has a minimal repair *shape* (close the set; add the missing value; type the terminal outcomes; declare domain conventions) — all PROPOSED REFINEMENTS, none applied.

---

## TV-F-019 (E2) — Epistemic ladder dynamics: the minimal corpus-required transition system

**Method:** extraction only — every state, transition, and guard below is quoted from source; nothing invented. Machines kept separate (epistemic / governance / operational).

### Epistemic machine: corpus-required transitions and guards

| # | Transition | Guard (as stated) | Source |
|---|---|---|---|
| 1 | (creation) → Candidate | `AssertionCreated(P,Σ,E,τ,Π)` event | Q15-revised §4/§6.1 |
| 2 | Candidate → Supported | support is relational: `Supported(P; E,R,C,t)` — evidence + evaluation rule | 008 §4; 189 §7 |
| 3 | Supported → Accepted | `EA ⊨ ρ_A` (acceptance policy: example conditions incl. N_independent≥2, authority min, age max, `ActiveConflict(P)=False`, human approval, `Pr(P|E)≥0.95` — **all explicitly policy examples, not laws**); emits **`AssertionAccepted` with 9-field payload** (AssertionId/AssessmentId/PolicyId/PolicyVersion/Authority/Context/EffectiveFrom/DecisionBasis/Timestamp) — "an auditable transition" | 008 §5/§12/§15 |
| 4 | Accepted → Committed | authority act + purpose/context; `Accepted ⇏ Committed` (A2); A6 | 008 §6/§13/§28 |
| 5 | Supported → Contested · Supported → Rejected · Candidate → Unresolved | branches; `NotAccepted ≠ Rejected`, `Unresolved ≠ Rejected` | 008 §2/§16/§29 |
| 6 | Supported → Conflicted | conflict predicate `I ∧ C ∧ T ∧ ¬Compatible` ("candidate formal rule") | 189 §21 |
| 7 | Supported →^{E₂} Refuted | evidence establishing ¬P; historical `Supported(P,t₁)` preserved | 189 §22 |
| 8 | P →^{Correction} P′ · P_t →^{Supersession} P_{t+1} | **cross-proposition replacement events, not status moves** — a distinct sort | 189 §23–24 |
| 9 | Supported + DeterminationRule + Authority → Determined | the determination gate | 189 §8 |

**Corpus-supported bridge (RECONSTRUCTED, with provenance):** v0.1's ratified definition *"Determination = the Supported→Accepted transition under AcceptancePolicy"* identifies 189's `Determined` with the ratified `Accepted` — the only cross-registry status mapping the corpus itself licenses. (SOURCE-CLAIM v0.1 §1 + 189 §8; the identification is INFERRED, marked as such.)

**Structural finding:** step-008 §26 itself rejects the linear ladder — *"AcceptanceStatus should be modeled as a state space, not a scalar hierarchy"* — and supplies the 4-dimensional `Ω_A=(Support, Acceptance, Commitment, Contest)` with partial dynamics `ω: Ω_A×Event×Policy ⇀ Ω_A`. **The ratified 3-chain (R-3) is the AcceptanceStatus projection of Ω_A** — the compression PF-6 recorded, now visible at source level: `Accepted ∧ Contest:Active` is expressible in Ω_A and inexpressible in the chain (matches the executed witness probe). The ratified chain and the Ω_A state space BOTH stand; their relation is projection, not supersession (**no ruling maps them — gap**).

### Well-definedness verdict
As a **guarded relation schema**: well-defined and now fully catalogued. As an **executable LTS**: NOT well-defined — missing: (i) interpretations of every guard (policy language, DeterminationRule, evaluation rule R); (ii) any exit from `Conflicted` (adjudication operator ABSENT — MV-F-10, third confirmation); (iii) the Contested/Ω_A dimensions' dynamics (ω unconstructed); (iv) retraction as a status transition (absent from 008 — deferred to its Step 9; exists only as evidence-level `Status(E)=Invalid` in 025c-2 §19). **Verdict: UNDER-SPECIFIED as a transition system; the specification of what is missing is now exact.**

### Governance & operational machines
Still **never enumerated in Step 189** (named only); the only state sets on record are Step 203's examples (`Proposed→Reviewed→Authorized/Rejected`; `NotStarted→Running→Completed/Failed`) explicitly marked "depending on the domain". Governance resolution semantics live in 025f: outcomes {Resolved/Unresolved/Invalid}, escalation law (`UnresolvedGovernanceConflict → HumanGovernance` — "the correct computational result"), supersession (`HistoricalConflict ≠ CurrentConflict`), exceptions (`SatisfiedByException` preserving rule + deviation), AI non-bindingness. ⪰_C's partial-order axioms: **confirmed absent at source** (the file argues *against linearity* and *for context-dependence* — it never states reflexivity/transitivity/antisymmetry). Equal-authority tie → `Unresolved` (the tie-breaker IS escalation — 025f answers TV-F-012's device question for the tie case; the *general* uniqueness device remains open).

**Non-consequences:** no lifecycle is proposed; no guard is interpreted; the Ω_A-projection reading licenses no change to the ratified chain — it is a relation *between* two standing corpus layers.
