# DEFINITION VERIFICATION REGISTER — D1–D10 (Phase 2C, mandate 20260829_1956 §5)

**VERIFY SESSION · 2026-08-29.** Every entry below was read **verbatim at source by the verifier** (not taken from any prior register). Scope of this instalment: the load-bearing definitions of **Steps 001–025**, the corpus's foundation + framework layer. Definitions from Steps 026–205 are added as their verification bands land.

**Test key (§5):** D1 existence · D2 type · D3 domain · D4 codomain · D5 preconditions · D6 postconditions · D7 uniqueness/determinism · D8 constructibility · D9 computability · D10 independent verifiability.
**Verdict vocabulary (§3):** CLEAR · PARTIALLY_CLEAR · AMBIGUOUS · ILL-TYPED · INCOMPLETE · CONTRADICTORY · NOT_DEFINED.
**Standing rule (§13):** SOURCE RESULT and VERIFIER OBSERVATION are kept apart; no repair is applied to the corpus here.

**Oracle convention used throughout.** Many definitions are *deliberately* parameterised by a policy ρ or an interpretation function the corpus refuses to fix. Where the source states this explicitly, the definition is scored **CLEAR-relative-to-oracle** and D9 stops at `CONSTRUCTIBLE (oracle-gated)` — this is an honest design choice, not a defect. Where the parameterisation is *silent*, it is scored INCOMPLETE.

---

## Group A — Evidence relations (Steps 001–002)

### DV-01 `Ind_ρ(e_i, e_j, G, C) → {Independent, Dependent, Unknown}` (step-001 §1.1, §9)
D1 ✔ defined. D2 four-place predicate into a 3-valued set. D3 evidence pair × provenance graph G × context C, parameter ρ. D4 𝕀={I,D,U} — **explicitly 3-valued, `Unknown` irreducible** (§10). D5 G available; ρ declared. D6 result carries a basis (§8 auditable form). D7 deterministic *given* (G,C,ρ) — the piecewise rule §9 is ordered, so no ambiguity between branches. D8 ✔ for finite G. D9 **COMPUTABLE** for the deterministic clauses (graph reachability, decidable); the "all required independence conditions hold" clause is ρ-gated → CONSTRUCTIBLE-only there. D10 ✔ reproducible from (G,C,ρ).
**Verdict: CLEAR (oracle-gated in one branch).** Note: G's *completeness* is not observable; the corpus handles this honestly by returning Unknown rather than Independent.

### DV-02 `≺` dependency relation (step-002 §5) — **DEFECTIVE**
D1 ✔ defined ("`e₁ ≺ e₂`: e₂ was produced using e₁"). D2 binary relation on ℰ. D3/D4 ✔. D7 **FAILS**: §6 Case B writes the reverse orientation with the author's own caveat *"depending on how we orient the provenance relation"*, and no later step in 001–025 fixes it.
**Verdict: AMBIGUOUS (orientation unfixed).** → finding TV-F-020 / AC C-062. **Consequence:** every downstream statement using ≺ (E-K4, dependency sensitivity, K0's P5 frame) is orientation-relative. **VERIFIER OBSERVATION:** both orientations yield isomorphic theories; the repair is a notation act, not a mathematical one — but it is a *decision the corpus never took*, so it may not be silently assumed.

### DV-03 `=_I` (identity), `≈_P` / `=_P` (propositional equivalence), `=_O` (observation identity) (step-002 §5)
D1 ✔ all three. D2 equivalence-relation candidates on ℰ. D5 `=_O` presupposes `Root(e)`/`Underlying(e)` well-defined (§13). D7 `=_I` deterministic via hash/stable ID; **`≈_P` is oracle-relative (semantic interpretation) and §19 concedes it is not universally decidable — stated honestly**; `=_O` partial (returns Unknown when Root unknown). D9 `=_I` COMPUTABLE; `≈_P` stops at DEFINED (oracle); `=_O` CONSTRUCTIBLE.
**Verdict: `=_I` CLEAR · `=_O` PARTIALLY_CLEAR · `≈_P` CLEAR-as-oracle.** **Notation defect:** the same relation is written `≈_P` and `=_P` inside one file.

---

## Group B — Assessment and aggregation (Steps 003–005)

### DV-04 `EA = (S⁺, S⁻, U, Q, V, D, X, Π)` (step-003 §1)
D1 ✔. D2 8-tuple. D3/D4 **component sorts are NOT given** — S± have no fixed codomain by deliberate decision (§13: the scalar is a projection, not the object). D5–D6 n/a. D7 determinism required by step-004 §1 axiom. D8 partially: S±, D, Π constructible; U, Q, V are forward references never closed in this range.
**Verdict: PARTIALLY_CLEAR (structure CLEAR, three components INCOMPLETE).** **Load-bearing:** the (S⁺,S⁻) separation survives into every later model and into K0's P5.

### DV-05 `Conflict(P) = True iff S⁺>0 ∧ S⁻>0` (step-003 §3) — **TYPE ERROR, REPAIRED IN-CORPUS**
D2 **FAILS**: `>0` presupposes an ordered numeric codomain that the same document (§13–14) explicitly refuses to fix.
**Verdict: ILL-TYPED as written.** **SOURCE RESULT:** step-004 §7–8 restates conflict set-theoretically (`E⁺≠∅ ∧ E⁻≠∅`) — a valid in-corpus repair satisfying the four-condition resolution test (same question, new result, actually answers, not reopened). **Recorded as the cleanest genuine RESOLUTION found in the corpus so far.**

### DV-06 `N_ρ: ℰ → ℰ′` normalization, and the ordering invariant "aggregation only after normalization" (step-003 §4)
D1 ✔. D2 endofunction on evidence sets. D5 requires identity/provenance/relevance/temporal/dependency/polarity resolution to be available. D9 stops at **CONSTRUCTIBLE** — each sub-step is itself oracle- or policy-gated.
**Verdict: PARTIALLY_CLEAR.** The *ordering constraint* (normalize before aggregate) is CLEAR and is an architectural invariant of high value; the *function* is a pipeline stub.

### DV-07 `EA_ρ: (ℰ, G_E, P, C) → 𝒜` with projection `π_s: 𝒜 → [0,1]` (step-003 §13–14)
D1 ✔. D4 **𝒜 is never constructed** — named as "the space of structured assessments". D9 stops at DEFINED.
**Verdict: INCOMPLETE (codomain unconstructed).** **VERIFIER OBSERVATION:** the *doctrine* (scalar is a derived view, not the object) is the single most reused principle in the corpus and is independently defensible on measurement-theoretic grounds; the *object* 𝒜 is a placeholder.

### DV-08 Aggregation axioms `E-K1…E-K10` and admissibility `𝒞_admissible = {ρ | Agg_ρ ⊨ 𝒜_kernel}` (step-004 §19–20, §23)
D1 ✔ all ten stated. D2 axiom schema over aggregation operators. D7 n/a (axioms). D9 admissibility is **decidable only relative to a fixed operator and fixed codomain**; A₆ (supporting monotonicity, `S⁺(E∪{e}) ≥ S⁺(E)`) again presupposes an order on S⁺ that is not fixed here.
**Verdict: CLEAR as an axiom set; A₆ PARTIALLY_CLEAR (order presupposed).** **Strongest downstream fact:** the impossibility fragment of §21's pluralism conjecture is later **PROVEN** as T-K6a (no multiset-of-strengths aggregator satisfies duplicate-invariance ∧ corroboration-increase). The positive half ("a pluggable family suffices") remains a DESIGN CHOICE.
**Numbering defect:** the same content carries three ID systems in one file (16 unnamed properties / A₁–A₈ / E-K1–E-K10). E-K is the only set reused downstream and is therefore the canonical key.

### DV-09 `c_ρ: ℰ × 𝒫 × 𝒞 → 𝒬` (step-005 §5)
D1 ✔. D4 **𝒬 deliberately unfixed** — explicitly *not* required to be [0,1]; may be an ordered scale, vector, belief function, likelihood ratio, argument structure, or distribution. D9 stops at DEFINED.
**Verdict: CLEAR-as-schema / INCOMPLETE-as-object.** **VERIFIER OBSERVATION:** this is the corpus's measurement-theoretic high point — "0.8 has no epistemic meaning until its semantics are specified" is exactly the right constraint, and the refusal to fix 𝒬 is principled, not evasive.

---

## Group C — Value of information (Step 006)

### DV-10 `IG(e;P) = H(P) − H(P|e)` (step-006 §3)
D1 ✔. D2 standard Shannon information gain. **MATHEMATICALLY VALID as an import**; the corpus correctly scopes it to probabilistic representations only (§4) and declines to make it a universal law.
**Verdict: CLEAR (correct standard-theory import, correctly scoped).**

### DV-11 `EIG(e) = Σ_{o∈O_e} Pr(o|e,K_t)·[D(K_t,I_t,P) − D(K^o_{t+1},I_t,P)]` and `EDR` (step-006 §6–7)
D1 ✔. D2 expectation over acquisition outcomes of discrepancy reduction. D3 requires **two oracles**: (i) the discrepancy function D — codomain and order unspecified; (ii) the predictive distribution `Pr(o|e,K_t)` — no model supplied anywhere. D9 **stops at INPUTS_KNOWN → NOT CONSTRUCTIBLE as specified**; the file itself concedes this (§27: the kernel supplies only an `EvidenceValueEvaluator` abstraction).
**Verdict: INCOMPLETE (two unfurnished oracles) — but honestly declared.** **Later evolution:** Q19 REFRAMES D from a metric to a non-symmetric partial order Δ₁⪯Δ₂; step-015 §8 supplies the decision-theoretic sibling EVSI, which *is* computable once a decision model exists (see DV-19).

### DV-12 `q* = argmax_{q∈Q_t} U_ρ(q)` (step-006 §13, §25)
D1 ✔. D5 finite Q_t (given). D7 **tie-breaking unspecified** — argmax may be set-valued; the corpus writes it as if unique.
**Verdict: PARTIALLY_CLEAR (uniqueness not established).** Minor; a tie-break policy is a one-line governance act. Recorded, not repaired.

---

## Group D — Knowledge state and transition (Steps 007–009)

### DV-13 `K_t = (𝒜_t, ℰ_t, ℛ_t, 𝒞_t, ℋ_t, Γ_t)` (step-007 §2) — **tuple variant #1**
D1 ✔ (the corpus's first K_t). D2 6-tuple. D3/D4 component sorts named, not typed. D7 n/a.
**Verdict: PARTIALLY_CLEAR.** **Critical status:** no later step supersedes it; it is variant #1 of at least seven (27 §71 8-field; 31.9 7-field; 25K 11-field; 49 8-primitive carrier; 51.2 11-component; 19's 5-partition). **TV-F-016 stands: no canonical K_t is established by the corpus.**
Internal tension: ℋ_t is *inside* the tuple while §3 asserts `K_t ≠ H_t` and §15 folds over an *external* H_t — consistent (containment ≠ identity) but it seeds the later History-in/out oscillation.

### DV-14 `δ: K × 𝒪_K ⇀ K` with `𝒪_K = {Introduce, Corroborate, Qualify, Contradict, Supersede, Resolve, Retract, Invalidate}` (step-007 §11)
D1 ✔. D2 **partial** function — the partiality is deliberate and correct (§11: `Resolve(C)` invalid if `C ∉ 𝒞_t`). D5 per-operation preconditions **named but never specified**; §14 says explicitly "the exact algebra of `+1` is policy-specific". D9 stops at **DEFINED**; the operation semantics are absent.
**Verdict: PARTIALLY_CLEAR (signature CLEAR, eight operation semantics NOT_DEFINED).** **Collision:** step-009 §22 mints a second, overlapping alphabet ℛ_K (9 ops) over the same carrier with no reconciliation → TV-F-021 / AC C-063; step-051 later adds a third (event alphabet Σ).

### DV-15 `K_t = F(K₀, H_t, ρ, ω)` and the verification invariant `Incremental(K₀,H_t) = Reconstruct(K₀,H_t)` (step-007 §16, §27)
D1 ✔. D7 **determinism is correctly stated as a REQUIREMENT on δ given (ρ,ω), not as a theorem** — an important honesty marker. D9 **COMPUTABLE** for finite histories and total δ-instances; **executed by this verifier on a toy δ (computation C3: incremental ≡ fold, prefix replay, Superseded ≠ deleted — all pass).**
**Verdict: CLEAR.** This is the ancestor of the replay theorem T-K9 and of the corpus's whole reconstructability doctrine. Among the strongest definitions in the range.

### DV-16 `α_ρ: EA × P × C → 𝒮_A`, superseded in-file by `Ω_A = (SupportStatus, AcceptanceStatus, CommitmentStatus, ContestStatus)` with `ω: Ω_A × Event × Policy ⇀ Ω_A` (step-008 §9–10, §27)
D1 ✔ both. **In-file supersession is VALID** (same question, new result, actually answers, not reopened) — a rare legitimate SUPERSEDES in the corpus. D2 product state over four status axes (correctly *not* a linear ladder; §26 explicitly warns against ordering them). D9 ω stops at DEFINED (transitions unspecified).
**Verdict: Ω_A CLEAR as a state space; ω INCOMPLETE.** **Open governance item:** whether Ω_A may be projected to a single status is pending decision #8; the corpus does not decide it.

### DV-17 `Conflict_ρ(A_i,A_j) = Rule_ρ(P_i,P_j,C_i,C_j)` and `𝔹 = {00,10,01,11}` (step-009 §4, §8)
D1 ✔. D2 conflict is **relational and rule-parameterised** — correct, and the reason temporal/contextual conflicts are not false positives. 𝔹 is a four-valued **epistemic-status** space, explicitly *not* four-valued truth (§9) — a distinction the corpus gets right and later files (068) re-derive.
D9: conflict detection COMPUTABLE once Rule_ρ, identity and temporal scope are fixed; **𝔹 has no consequence relation** — the paraconsistent *logic* is deliberately not constructed; what is defined is the no-explosion *invariant*.
**Verdict: Conflict_ρ CLEAR-relative-to-rule · 𝔹 CLEAR as a status space, NOT_DEFINED as a logic (deliberate).**

---

## Group E — Inference and propagation (Steps 010–011)

### DV-18 `Infer_ρ(K,P) → InferenceResult`, derivation object `D = (P,Γ,R,M,Π,Σ,τ)`, and `LLM(Γ)→P ⇏ Γ⊢P` (step-010 §3, §26, I2)
D1 ✔. D2 typed multi-method family: `⊢` (deductive), `⇒` (defeasible), `Pr(·|·)` (probabilistic), `⤳` (abductive), `LLM(·)` (generative) — **deliberately NOT unified into one operator, which is mathematically the right call** (they have different semantics; a common algebra would be false). D5 `⊢_ρ` presupposes a logic that is never instantiated; soundness (§30) is correctly stated as an **obligation**, not a claim. D9 COMPUTABLE per method once the method is fixed.
**Verdict: CLEAR as a typed family; each member's instantiation INCOMPLETE (declared).** I1–I10 are constitution-grade; **I2 is the single most load-bearing safety invariant in the corpus** and is formalised later as EpistemicTypeSafety (031 §41–44).

### DV-19 `Propagate_ρ(Σ_premises, Rule, Method, Dependency, Context)` and `D*(A) = ⋃_{x∈D(A)}({x} ∪ D*(x))` (step-011 §1, §28)
D1 ✔ both. `Propagate_ρ` D9 stops at DEFINED (policy oracle, explicitly). `D*` D9 **COMPUTABLE** for finite acyclic derivation graphs — **executed by this verifier (computation C2): closure correct; and the "retract a premise ⇒ reassess, do not delete" rule verified to preserve an independently-supported conclusion.** Cycles are flagged by the source (§29) as requiring an explicit fixpoint or cycle policy — correct.
**Verdict: `D*` CLEAR and COMPUTABLE · `Propagate_ρ` CLEAR-as-schema.** The accompanying result — *provenance propagation is universal; numerical uncertainty propagation is calculus-dependent* (§23) — is DERIVED and sound, and is the informal ancestor of K0's oracle discipline.

---

## Group F — Sufficiency, decision, time (Steps 013–016)

### DV-20 `Satisfies(K,q) ∈ 𝕋₃ = {Satisfied, Unsatisfied, Unknown}` and `Ready_ρ = DecisionPolicy_ρ(S, RiskAcceptances, Authority, Constraints)` (step-013 §8, §34)
D1 ✔. D2 three-valued per-requirement evaluation lifted to a readiness predicate. D9 **COMPUTABLE for finite requirement sets** once per-requirement evaluation is available — **executed (computation C6): 90 % coverage with one *critical* Unknown correctly yields NotReady, and Unknown stays distinct from Unsatisfied.**
**Verdict: CLEAR.** Widened in step-023 to five values (adds Conflicted, NotApplicable) — a compatible refinement, though delivered by uncited re-derivation (TV-F-024).

### DV-21 `EVSI = E_r[max_a EU(a|K_{t+1}(r))] − max_a EU(a|K_t)` (step-015 §8)
D1 ✔. D2 the standard preposterior expected value of sample information. D3 requires a decision model (actions, utilities, prior, likelihood) — when supplied, **fully COMPUTABLE and TESTABLE**. D10 ✔.
**Verdict: CLEAR (correct standard import).** **Verifier computation C7:** implemented and run on 20 000 random binary decision problems — the formula is well-defined and **EVSI ≥ 0 held on every instance** (min −3.6 × 10⁻¹⁵, float noise), with a worked example returning 3.5 where information can flip the decision.
**VERIFIER OBSERVATION (PROPOSED REFINEMENT, not corpus theory):** EVSI ≥ 0 is a genuine theorem (immediate from Jensen / the fact that the pre-posterior maximum dominates the prior maximum). The corpus never states it. Adding it would be a *new* result, so it stays a recommendation.
**Lineage:** re-derived without citation at step-034 as `I* = argmax_I[EVSI(I) − Cost(I)]`.

### DV-22 Five-time model `T_e ≠ T_o ≠ T_k ≠ T_d`, interval `T_v = [t_start, t_end)`, and `X_t ≠ K_t` with `o_t = Obs(X_t, S_t)` partial/noisy/delayed (step-016 §2, §5, §9, §49)
D1 ✔. D2 five distinguished time coordinates plus a validity interval; two coupled transition systems `X_{t+1} = δ_X(X_t,e_t)`, `K_{t+1} = δ_K(K_t,o_t,ρ_t,Ω_t)`. D9 interval algebra (Allen relations) **COMPUTABLE — executed (computation C8): the overlap of two contradictory validity intervals is correctly identified as the conflict window.** Unknown boundaries are representable (§6) — good.
**Verdict: CLEAR.** **The temporal core of the final theory.** The corpus's later four-competing-temporal-models problem is *divergence from this file*, not a defect in it.

---

## Group G — Rules, authority, uncertainty (Steps 018–020)

### DV-23 `Eval(P,K) ∈ {T,F,U}` with the §25 connective tables (step-018)
D1 ✔. D2 three-valued propositional evaluation. **VERIFIER RE-DERIVATION (computation C1): the tables given are exactly strong Kleene, and were verified by exhaustion to be commutative, associative, and monotone in the knowledge order (U ⊑ T, U ⊑ F).** No error found.
**Verdict: CLEAR and MATHEMATICALLY VALID.** Direct source of K0's P7 frame and of the conjunction law T-K5. Among the very few definitions in the corpus that are both fully specified and independently checkable.

### DV-24 `Cl_R(K)` least fixpoint via `K_{i+1} = K_i ∪ Infer(K_i,R)` (step-018 §30)
D1 ✔. D5 **requires monotonicity of the rule system** — correctly stated. D7 unique least fixpoint for finite monotone systems (standard). D9 COMPUTABLE (terminates on finite K, monotone R). The source immediately notes (§31) that KnowledgeOS is *fundamentally non-monotonic*, so this closure applies only to a fragment — an honest scoping.
**Verdict: CLEAR, correctly scoped.**

### DV-25 `Authority(S,A,Ctx)`, `Rel(S,A,Ctx,t)`, `W(e,A,C)` 7-dimensional evidence profile with Pareto order and first-class `Incomparable` (step-019 §6, §8, §19–22)
D1 ✔. D2 Authority and Reliability are **separately typed and both context-scoped** — the four-way separation Authority ≠ Reliability ≠ Support ≠ Commitment is the file's central contribution. D9: `Rel` is empirically estimable — **the Beta-Bernoulli update `p|D ~ Beta(α+k, β+n−k)` was verified (computation C4)**; `Authority` is a governance datum, not computable from evidence (correctly so). The evidence order is explicitly **partial**, with `Incomparable` a legitimate output (§22) — measurement-theoretically the right move.
**Verdict: CLEAR.** Statistical hygiene in this file is high: no estimator without an estimand, likelihood products gated on conditional independence (§38–39), thresholds explicitly labelled governance (§35).

### DV-26 Uncertainty taxonomy `𝒰 = {Unknown, Incomplete, Ambiguous, Imprecise, Probabilistic, Conflicting, Indeterminate, ModelUncertain}` and the typed uncertainty object (step-020 §3, §26)
D1 ✔. D2 a **type taxonomy**, each with its own propagation semantics (§34). D9 varies by type: interval arithmetic COMPUTABLE (verified, C5); probabilistic requires a model; ambiguity is a candidate set; conflict is a relational state.
**Verdict: CLEAR.** **U7 (`LLMConfidence ≠ CalibratedProbability`) and U8 (`no numerical confidence without a defined basis`) are constitution-grade and independently correct**; calibration is defined empirically and correctly (§16: `P(Y=1|p̂=p) ≈ p`).
**Open fork:** step-027 later defines an 8-**component** uncertainty *vector* whose axes are sources, not types — same glyph `U`, different decomposition, never reconciled.

---

## Group H — Contract, composition, falsification (Steps 023–025)

### DV-27 `EpistemicContract EC = (Purpose, Requirements, EvidenceRules, UncertaintyLimits, ConflictRules, TemporalRules, AuthorityRules)`; `Gap(K,P) = R(P) ∖ Satisfied(K)`; `MSK(P)` (step-023 §14, §39, §41)
D1 ✔. D2 EC is a first-class governance object; Gap is set difference over a finite requirement set. D9 **COMPUTABLE given EC and per-requirement evaluation**. D8 EC itself must be *authored* — and the corpus never says by whom or how, which is exactly the gap that later becomes `DeriveContract(G,S)`/η and is **REFUTED as a total function** by finding TV-F-011 (input insufficiency).
**Verdict: EC CLEAR as a structure, INCOMPLETE as a derivable object · Gap CLEAR · MSK PARTIALLY_CLEAR (minimality never characterised).**
**Historical note:** EC is born here, in a file that re-derives step-013 without citing it (TV-F-024). The entire contract thread of the corpus descends from this uncited re-derivation.

### DV-28 Core invariants `C1–C20` (step-024 §73)
D1 ✔ all twenty stated in one place — the best consolidated invariant list before step-048. D2 mixed: some are typed constraints (C1 type closure), most are natural-language architectural rules. D9 a minority are machine-checkable as written (C1, C10, C14); the rest stop at DEFINED.
**Verdict: PARTIALLY_CLEAR as a set (individually CLEAR as *statements*, INCOMPLETE as *checkable predicates*).**
**On the file's boxed verdict "THE KNOWLEDGEOS CORE MODEL IS COMPOSABLE":** the support is a signature-schema walk over sorts that were never formally typed; §75 itself concedes implementation-level computability is undemonstrated. **Classification: AUTHOR_ASSERTION with DERIVED support at schema level only.** Credit where due — the walk *found and repaired four operator arity defects mid-file* (§59–62), making it the corpus's first self-correcting verification pass.

### DV-29 Failure taxonomy `F1–F10` and pass criterion `Representable ∧ Computable ∧ Traceable ∧ Governable` (step-025 §47–48)
D1 ✔. D2 a ten-class taxonomy of *model* failures (undefined concept, undefined operator, semantic ambiguity, information loss, governance hole, temporal leakage, provenance break, causal overclaim, statistical invalidity, nontermination).
**Verdict: CLEAR.** **Status: LOST/UNACCOUNTED — no later file in the corpus references F1–F10 again.** This is a genuine lineage loss of a well-formed artifact, recorded here for the gap register.

---

## Instalment summary

```text
Definitions D1–D10 verified this instalment:   29 (Steps 001–025)
CLEAR:                                          13   (DV-01, 03a, 08*, 10, 15, 17*, 18*, 20, 21, 22, 23, 24, 25, 26, 27b, 28*, 29 — see per-entry qualifiers)
PARTIALLY_CLEAR:                                10
INCOMPLETE:                                      4   (DV-07 𝒜 · DV-11 EIG oracles · DV-16 ω · DV-27 EC-derivation)
AMBIGUOUS:                                       1   (DV-02 ≺ orientation)
ILL-TYPED:                                       1   (DV-05 — repaired in-corpus by step-004)
NOT_DEFINED (deliberate):                        2   (𝔹's consequence relation; ⊢'s logic)
Definitions independently COMPUTED by verifier:  8   (C1–C8; all pass)
Standard-theory imports checked for correctness: 5   (Kleene tables · Shannon IG · Bayes/Beta · EVSI · delta method) — all correct
Mathematical errors found:                       1   (DV-05, already repaired by the corpus itself)
```

**Instalment verdict.** In the foundation and framework layers, the corpus's definitional hygiene is **high**: where a definition is left open, the source almost always says so; where standard mathematics is imported, it is imported correctly. The defects are concentrated in *canonicalisation* (orientation, alphabets, tuple identity, ID namespaces) rather than in mathematics. **No definition in Steps 001–025 was found mathematically wrong except DV-05, which the corpus repaired itself within one hour.**

**Cross-links:** `spec/STEP-VERIFY-001-010.md` · `spec/STEP-VERIFY-011-025.md` · findings TV-F-020…024 · AC C-062…066 · computations recorded in `STEP-VERIFY-011-025.md §Computations`.
