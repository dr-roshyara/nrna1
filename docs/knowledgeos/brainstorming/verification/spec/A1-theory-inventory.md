# A1 — Theory Inventory (Candidate KnowledgeOS Theory)

**Status:** ASSEMBLED 2026-08-29 from the four V0 sweeps + three full-read extraction sweeps + first-hand reading of the ratified layer. Detail lives in A3 (ratified definitions), A3W (state/transition), A3X (algebras/invariants/computability), A6 (statistics), AC (contradictions), A2 (assumptions).
**Maturity scale (mandate 1453 §25):** T0 Mentioned · T1 Defined · T2 Formalized · T3 Derived · T4 Proven · T5 Tested (executed) · T6 Validated. **T-ratings below are verifier assessments of the corpus record (class C), pre-Level-1; "T5" appears ONLY where an executable ran** (the three GN-46 witnesses + EXP-01 CSV). Conceptual PASS ≠ T5.
**Variants column:** number of materially distinct competing formulations on record.

## 1. Primitive / ontological layer

| Construct | Principal sources | Maturity | Variants | Key open issue |
|---|---|---|---|---|
| World state `X_t`/`W_t` | Step 66; 031 §17; 049 §92 | T2 (as inequality `X_t≠Observed(X_t)`) | 2 symbols | F dynamics uninterpreted |
| Observation | v0.1; 031 §31.6-family; 201 §4; Q15 | T1–T2 | ≥3 | Source vs Semantic split untested; 𝒪_t in no K_t tuple (C-048) |
| Observation function `Ω: W→O` | 031 §18 | T2 | 1 | anchor of impossibility/identifiability results |
| Entity / Identity | Q13/Q14 (𝓔); 031 (𝓘); 201 (I); 162 I-19 | T1 | 3 framings | identity criteria = missing identity calculus (MV-F-22) |
| Evidence `e` | EXP-01 tuple; 031 §6; Q14 𝓔_v; 004/005 | T2 | ≥4 tuples | equivalence `~` UNDEFINED; in/out of K_t [UNRESOLVED] (C-043) |
| Proposition / Assertion / Claim | Q14 `P=(E,D,V)`, `A=(P,Σ,…)`; 031 §5; 201 (Proposition+Assessment) | T2 | ≥4; vocabulary schism (C-009) | assertion arity drift; evidence-optional dispute (C-029) |
| Context | Q13 (7-field); Q20 (6-field); 031 𝓑; 204 §25 | T1 | ≥3 | Purpose dropped silently (C-012); "context is not metadata" |
| Provenance / Lineage | 031 §29 graph; 197 conservation; 162 I-18 (Provenance≠Lineage) | T2 | 2–3 | scope of conservation ("domain-required") policy-relative |
| Time / temporal validity | 031 §4; Q13 𝒯_t; 189 temporal invariants; 048 I-3 | T1–T2 | ≥3 | ValidTime≠KnowledgeTime formalization thin |

## 2. Epistemic layer

| Construct | Principal sources | Maturity | Variants | Key open issue |
|---|---|---|---|---|
| Knowledge state `K_t` | 049 (8-prim, ratified); Q13/Q14/Q15/Q20; 031 §9; 025a-1; 025k; 201 | **T2 formalized, 7+ ways** | 7+ | C-001; state equality undefined; minimal coherent form = V1 target |
| Epistemic status | 008 ladder (ratified, 3+boundary); FA layered model; 𝔼₃–𝔼₅ (199); 025-series sets; 120 §13 | T2 (ratified ladder) / T1 elsewhere | ≥6 sets | C-006; adjudication of CONFLICTED undefined (MV-F-10) |
| Ladder + covering relation I-12 + A6 boundary | v0.2 R-3/R-4; 008 | **T5 (witnessed: ladder_dc_reference.py)** | 1 ratified | transition calculus (what moves an item) ABSENT (AF-F-3) |
| Determination | v0.1; 234405; 189 §8–11 | T1 | 2 | exists only in 189 among late steps (C-009) |
| Zero / gap operator | 025d; v0.2 `Zero(K,EC)`; FA-4 | **T5 (witnessed: zero_reference.py, T1–T8 executed)** | 9 signatures (C-003) | status-set closure; no termination/totality claim; η input |
| Epistemic contract EC / η | 025d/025e; v0.2 R-2 | T2 (structure) / η **signature-only** | 4 forms (C-004) | η non-construction = OQ-1, THE load-bearing gap |
| Ideal state / K* | Q11/Q18; 025d K*_EC | T1 | 2 | K* prose-only; Distance undefined then non-metric |
| Unknown / missingness semantics | 027 §41 (6-way); 199; 031 §23; 089 §48; Freedman | T1–T2 | ≥4 operationalizations (C-035) | six null semantics individually undefined |
| Uncertainty | 027 (8-vector); 199 (7-list); 082; 031 §24 `U(H)` typed object | T1–T2 | 5 taxonomies (C-007) | components' domains undefined; no unification |
| Conflict | 031 §25; 189 §19–21 predicate; 025-series; 025e escalation | T2 (predicate forms) | ≥2 concepts under 1 token (C-034) | preserved-state vs blocking-state split unstated |

## 3. Dynamics layer

| Construct | Principal sources | Maturity | Variants | Key open issue |
|---|---|---|---|---|
| Transition function | Q15-revised δ (partial, Pre/Post, Replay); Q20 δ on S_t; 031 Revise/ℛ; 204 τ; 201 τ; ~25 RHS forms | T2 (Q15-revised core) | ~25 (C-001/D-01) | δ vs τ never connected; arity conflict C-026; composition law C-024 |
| Learn / F | 049 §92 | **signature-only (T1)** | 1 | uninterpreted; no governed flag (MV-F-11) |
| History / Replay / Rollback | Q15 T3/T7/T8; 025k Fold/Derive; 197 fold; 031 §14 | T2; replay conditional on determinism+versions | set vs sequence (C-011); rollback ×3 semantics (C-025) | replay determinism vs HumanAuthorization/LLM inputs (C-040) |
| Events / Commands | Q15-revised (8 events / 7 commands); 204 §28 event identity; 205 Command≠Event | T2 | 2–3 | payload slots (C, Context both) unexplained |
| Transition algebra (composition, idempotency, commutativity, reversibility, causal order) | 204; 205 | T2 | composition law stated 2 ways (C-024) | disjoint from Q15 δ development (I.15) |
| State federation | 203 (6 spaces, product REJECTED); 189 (3 machines) | T2 | vs Q20 product (C-027) | governance/operational state sets never enumerated in 189 |
| System state `S_t` | Q13/Q15/Q20; 197 𝒮_t | T2, 4 arities (C-002) | 8 forms | K_t opaque inside revised Q20 |

## 4. Evidence & inference layer

| Construct | Principal sources | Maturity | Variants | Key open issue |
|---|---|---|---|---|
| Aggregation axioms | 004 (16 sections; A₁–A₄ kernel / A₅–A₈ policy; E-K1–10 candidates) | T2 (stated requirements) | — | monotonicity axiom vs non-monotonicity (C-020) |
| Aggregation operators | EXP-01 A₁–A₄ formulas; 025c ⊕; regime instances (Bayes/DS/fuzzy/…) | **T5 for A₁–A₄ formula behavior (exp01_recheck.py)** | ≥5 mechanisms | operator selection OPEN (OQ-3); "no universal algebra" claim |
| Evidence assessment object | 005 AR; 025c ×2; 025c-2 𝓔_H | T2 | 4 vocabularies (C-008) | slot semantics unaligned |
| Dependency/independence treatment | 004 §4/9/10; 025c-2 §5–16; 199 I₆₄; EXP-01 pipeline | T2–T5 (pipeline duplicated/dependent cases executed) | — | resolver N and `~` undefined; Dempster precondition unstated (C-039) |
| Update rule `U_R` (regime-indexed) | conditional-evidence v1/v2 | T1 (instance list only) | — | no axioms/existence/uniqueness; φ_R existence assumed (HA-S10) |
| Information value | 025c-3 IG/VOI; 027 §54; 082 §46 | T2 (formulas) | 2 VOI forms | model-quality dependence stated; estimand undefined |

## 5. Statistical layer

| Construct | Principal sources | Maturity | Variants | Key open issue |
|---|---|---|---|---|
| Probability usage | 027; 082; 199; 031 §22 typed q; ratified refusal (008 §12) | T2 as guarded policy-relative tool; **STATISTICALLY SOUND BY ABSTENTION at ratified level (GN-46, to re-check)** | 3 Bayes forms | no (Ω,𝓕,P) constructed anywhere; likelihood source unaddressed (HA-S02) |
| Uncertainty propagation | 082 (rules + 33 conceptual experiments + 7 invariants) | T2; **T5=none (nothing executed)** | — | delta-method/moment assumptions unstated (HA-S03/04) |
| Calibration | 082 §26–27; EXP-01 design; SNF v0.4 proposal | T1–T2, **never executed** | — | the corpus's own "most important statistical test" |
| Measurement / SNF metrics | SNF file (C_snf, NC_snf, T_snf, H_sem, CR) | T2 claimed / **self-demoted to MATHEMATICALLY UNDERSPECIFIED** | 2 voices (C-049) | scale-type legitimacy unexamined (HA-S09); estimand undefined |
| Identifiability | 031 §19–21 (impossibility + criterion); KST reconstruction; Freedman NOT_IDENTIFIABLE | **T3–T4 candidate (standard results correctly stated — to prove at L1)** | — | KnowledgeOS analogue of structure 𝒦 unconstructed (HA-S20) |
| Decision theory | 027 §55–57; 025h EU/Risk/θ/Pareto; 031 §34 | T2 | — | utility scale legitimacy (HA-S08); θ governance-supplied |

## 6. Governance / agency layer

| Construct | Principal sources | Maturity | Variants | Key open issue |
|---|---|---|---|---|
| Proposal selector (Lord) | 025g; v0.1 (ratified, C/P dropped) | T2 | 5 signatures | loop termination needs unratified budget policy |
| Decision selector (Sārathi) | 025h; **unratified (PF-8)** | T2 | 3 signatures | ratified flow edge type-incorrect without it (MV-F-6) |
| Authority / authorization | 008-A6 (ratified I-4); 025f ⪰_C; 162 I-08..11, I-20; 201 §9–11 | T2 ratified boundary; ⪰_C postulated | — | partial-order axioms never stated (MV-F-12); in-force uniqueness (MV-F-7) |
| Policy | v0.2 R-1 (governed versioned object); Q20 P_t; 202 §26 | T2 | 2 signatures (C-013) | genesis base case ABSENT (MV-F-8); mid-flight change calculus ABSENT (AF-F-4) |
| Governance algebra | 025f (⪰_C, Applicable, Resolve, escalation) | T2, one row ratified | — | PF-9: I-11's guard evaluable only single-authority |
| Stratification loop | v0.2 §3 | T2 | — | genesis (MV-F-8) |
| Termination algebra 𝒯 | 025g §31–32 | T1 | — | Lord-only; composed loop unguarded (C-046) |

## 7. Invariant & meta layer

| Construct | Principal sources | Maturity | Variants | Key open issue |
|---|---|---|---|---|
| Invariant registries | ratified I-1…I-12; 048 1–20; 120 K1–K7; 162 I-01–20; 197 I₅₅–₅₇+7 classes; 089 ten named; 082 seven; 199 I₆₄–₆₈; 201/202/203 I₃₃,I₇₀–₇₅; 025a-5 I1–14; 025k 6+6 | T2 statements; I-5/I-6/I-12 **T5 at pipeline/witness level** | **8+ registries, unmapped (C-042)** | **joint satisfiability never argued — 𝒱 could be empty (A3X §3)**; numbering gaps I₃₄–₅₄, I₅₈–₆₃, I₆₉ absent |
| Conservation laws | 197 (8 conserved quantities) | T2 | — | "conservation" = non-erasure, no conserved quantity (C-032) |
| Verification obligations / lattice | 167; 168 | T1–T2 | verdict vocab inconsistent (C-047) | — |
| Computability claims | 049/069/089 | T2–T3 (standard results correctly imported) | limitation taxonomies ×3 (C-030) | "normal PC" claims unqualified pre-explosion (C-037) |
| Kernel candidates | 049 8-primitives; 162 7 shared-kernel; v0.2 canonical concepts; 201 vocabulary; K1–K8 capacities (kernel track) | T1–T2 | **≥4 disjoint senses (C-010)** | mandate requires independent mathematical kernel = K0 deliverable |
| Constitutional layer | EP01 Constitution v1.0 (eleven laws, ACCEPTED); INV-KOS-* map | prose-normative (not math) | — | no mapping constitutional-invariant ↔ formal counterpart |

## 8. Witnesses (evidence level C — the ONLY executed artifacts corpus-wide)

`zero_reference.py` (Zero total/terminating over finite R relative to evaluators; T1–T8 8/8; Insufficient-via-Γ; scalar-collapse counterexample) · `ladder_dc_reference.py` (I-12 no-skip; A6 evidence-inert; conjunction/no-averaging/Unknown→Block; Ω_A expressibility probe) · `exp01_recheck.py` (RAW vs PIPE semantics; CSV mixed-semantics re-derivation; associativity outcomes; scalar-contradiction impossibility; calibration demos) + `tests/experiments/knowledgeos_evidence_calculus_property_tests.csv`. **Everything else marked PASS in the corpus is conceptual (103 verdicts, X.31).** Known witness gap: invariant I-11 has no reference execution.
