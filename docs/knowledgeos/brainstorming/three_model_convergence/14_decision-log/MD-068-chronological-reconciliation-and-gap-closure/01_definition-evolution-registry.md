# MD-068 §01 — Definition Evolution Registry

Built from the 876-record MD-067 chronological ledgers (scratchpad), consumed as first-order evidence
per the user's own explicit instruction — not from re-reading source files, except where noted.
Ledger-ID format: `[batch-position]`, e.g. `[00-47]`. No version is overwritten; a later formulation is
always a new row.

## `K_t` (Knowledge State)

| Ver | First witness | Type/Arity | Domain→Codomain | Definition | Change from prev. | Status |
|---|---|---|---|---|---|---|
| v1 | `[00-01]` | probability distribution | `L → [0,1]` | Bayesian credence `K_t=Pr(·)`, `K_{t+1}=K_t(·\|E_t)` | NEW | UNRESOLVED (never reconciled with v2+) |
| v2 | `[00-09]`/`[00-10]` | atomic-unit tuple | `(O,S,E,C,G,t) → 𝕂` | `KnowledgeState(O,S,E,C,G,t)`, atomic-unit knowledge about observation `o` at `t` | NEW, unrelated to v1 | SUPERSEDED (by v3) |
| v3 | `[00-42]`/`[00-44]` | measurable-space element | `𝒳,𝒜` measurable space | `K_t` as element of a measurable Knowledge Space, built from Epistemic Observables `Y_j:Ω→𝒱_j` | EXTENSION of v2's substrate | SUPERSEDED (by v4) |
| v4 | `[00-45]` | many-sorted relational element | `𝒦=(D,P,T,C,I,E,Rel,H,Θ)` | `K_t` as attribution within a relational structure, factive `Knows(a,p,c,t)⇒True(p,c,t)` | RE-DEFINITION (new substrate, new factivity constraint) | SUPERSEDED (by v5) |
| v5 | `[00-46]`/`[00-47]` | abstract, deliberately untyped | `K_t ∈ 𝕂` | `[DEF-11]`: kept deliberately abstract, type left open | GENERALIZATION (retreats from v3/v4's concrete substrates) | CORPUS-SUPPORTED, canonical for the target chain |
| v6 | `[01-56]` (governance decision) | renamed object | — | `K_t → A_t` (AttributedState); factive `Knows` moved external, non-factive attribution kept internal | RECLASSIFICATION (governance act, "made on authority, not evidence" — R1 vs R2 behaviourally identical) | GOVERNANCE-DECIDED, propagation into v5's own chain (`[00-47]` DEF-19–22) **never shown executed anywhere in the 876-record traversal — UNWITNESSED** |
| v7 | `[05-36]`ff (Theory-00-21) | abstract, `K` bare symbol | `𝕂` (implicit) | Reuses `K`/`K_t` without re-deriving v1–v6; no explicit statement of which prior `K_t` it inherits | USES (silently), no explicit lineage statement found | UNRESOLVED lineage — see GAP register |

## `EC_t` (Epistemic Contract)

| Ver | First witness | Type/Arity | Domain→Codomain | Definition | Change from prev. | Status |
|---|---|---|---|---|---|---|
| v1 | `[00-09]`/`[00-10]` | informal | — | "Epistemic Contract" named for the first time, `EC(G,C,S)`, monolithic | NEW | SUPERSEDED |
| v2 | `[00-37]` | 6-field record | `EC=(EvidenceRules,InferenceRules,AcceptanceRules,UncertaintyRules,ValidationRules,ContextRules)` | first named rule-decomposition; ruling `EC≠Governance` | REFINEMENT (decomposes v1) | SUPERSEDED |
| v3 | `[00-47]` (canonical) | 4-field tuple | `EC_t=EC(S_t,G_t,Q_t,C_t)` | `[DEF-19]`, source/goal/question/context tuple | RE-DEFINITION (different fields than v2, no stated relation to v2) | CORPUS-SUPPORTED, canonical for `[DEF-20]`–`[THM-4]` |
| v4 | `[05-36]` (Theory-00-21, Part I) | 6-field record, different fields than v2 | `EC=⟨Req,Rules,Scope,EvidenceRequirements,TemporalRequirements,AuthorityRequirements⟩` | explicit `Req` as first-class field | RE-DEFINITION (6 fields again, but different from v2's 6 fields, and no explicit reconciliation with v3) | CORPUS-SUPPORTED, used throughout Theory-00-21; relation to v3 **UNWITNESSED — GAP-002** |

## `Req(EC_t)` / requirement set

| Ver | First witness | Type/Arity | Domain→Codomain | Definition | Change from prev. | Status |
|---|---|---|---|---|---|---|
| v1 | `[00-20]` | named set | `ℛ_I={r_1,...,r_n}` | "Let the Ideal State contain requirements" | NEW (first requirement set in the corpus) | SUPERSEDED |
| v2 | `[00-23]` | field of Inquiry object | `Q=(T,P,C,R,Γ)`, `R`=requirements | requirement set as one component of an Inquiry tuple | EXTENSION | SUPERSEDED (subsumed) |
| v3 | `[00-47]` (canonical) | function of `EC_t` | `Req(EC_t)` | `[DEF-21]` uses `Req(EC_t)` directly, body not separately defined beyond "the set of requirements" | RE-DEFINITION, canonical name adopted | CORPUS-SUPPORTED |
| v4 | `[05-36]` | field of `EC` v4 | `I_t := Req(EC_t,Γ_t)` | context-parameterized | EXTENSION (adds `Γ_t` parameter) | CORPUS-SUPPORTED, feeds directly into `[05-41]`'s `Sat` |

## `r` (a single requirement)

| Ver | First witness | Type/Arity | Definition | Change from prev. | Status |
|---|---|---|---|---|---|
| v1 | `[00-51]` | 7-field record | `r=(id,type,scope,content,standard,priority,validity)` | NEW, first structured `r` | SUPERSEDED — never re-cited by v2 |
| v2 | `[05-37]` (Theory-00-21, Part II) | opaque element | `r∈Req` (§2.26), no field structure given | RE-DEFINITION (drops v1's 7-field structure entirely; no citation of v1 found) | CORPUS-SUPPORTED, used by `[05-41]`'s `Sat`; **relation to v1's `standard`/other fields UNWITNESSED — GAP-001** |

## `standard` (a field of `r`)

| Ver | First witness | Definition | Status |
|---|---|---|---|
| v1 | `[00-51]` | one field of `r`'s 7-tuple, named "acceptance criterion," **body never given anywhere in the 876-record traversal** | OPEN — no version 2 exists; `[05-37]`'s `r` (v2 of `r`) does not carry a `standard` field at all, raising the question of whether v2 dropped it or subsumed it elsewhere (see GAP-001) |

## `App` (Applicability)

| Ver | First witness | Type/Arity | Definition | Change from prev. | Status |
|---|---|---|---|---|---|
| v1 | `[00-55]` | gate function | `App(K_t,r)` / `App(r,Q_t,C_t,S_t,EC_t)` | NEW — inserted between `Req` and `Sat`, "Applicability ≠ Satisfaction" | ABANDONED — no version 2 exists anywhere in the traversal, including `[05-41]`'s own `Eval`/`EvalReq`/`Sat` pipeline, which never reintroduces an applicability gate under any name (see GAP-003) |

## `Sat` — kept as separate objects per the user's own explicit instruction (`Sat`, `Sat_c`, `Sat*` never merged by spelling)

### `Sat` (the base predicate, `Sat(K,·)` family)

| Ver | First witness | Type/Arity | Codomain | Definition | Change from prev. | Status |
|---|---|---|---|---|---|---|
| v1 | `[00-10]` | informal | — | `K_t ⊨ EC(G,C)` | NEW | SUPERSEDED |
| v2 | `[00-44]` | binary, over whole `EC_t` | `Zero(K_t,I_t,EC_t)⟺K_t⊨EC_t` | `[Def 13]`, `Sat`-as-`⊨` over the WHOLE contract, not yet per-requirement | RE-DEFINITION (scope: contract-wide) | SUPERSEDED |
| v3 | `[00-47]` (canonical) | binary, per-requirement | `Sat: 𝕂×Req(EC_t) → {applies/not}` | `[DEF-20]` `Adequate(K_t,EC_t)⟺Sat(K_t,EC_t)` (still contract-wide in DEF-20) AND `[DEF-21]` uses `Sat(K_t,r)` per-requirement — **the canonical source itself uses `Sat` in two arities within the same document, never reconciled internally** | SPECIALIZATION (introduces per-`r` arity alongside the contract-wide one) | CORPUS-SUPPORTED, canonical; internal arity ambiguity itself unflagged in-corpus |
| v4 | `[00-51]` | binary, per-requirement, explicit codomain | `Sat(K_t,r)∈{0,1}` | fixes v3's per-`r` arity as Boolean | SPECIALIZATION | SUPERSEDED |
| v5 | `[02-22]` (M0136-equiv.) | entailment-based | `Sat(K_t,r) ⟺ K_t⊨Content(r)` | RE-DEFINITION (FOL entailment) | **REJECTED** by `[02-24]`, **RETIRED** by `[02-26]` same day | REJECTED |
| v6 | `[05-37]` (Theory-00-21, Part II) | non-Boolean, structured | `Sat:𝕂×Req→𝒮`, `Sat(K,r)=⟨status,degree,evidence,reason⟩`, `status∈{Satisfied,Unsatisfied,Unknown,Partial,Conflicted}` | RE-DEFINITION (arity 2→2 but codomain generalized from Boolean/3-valued to a structured 4-field record) | CORPUS-SUPPORTED |
| v7 | `[05-41]` (Theory-00-21, Part VI) | 3-arg, computed | **`Sat(K,r,Γ) = Det_r(EvalReq(K,r,EC,Γ), EC)`** `[Def 6.18]` | EXTENSION (adds `Γ` argument to v6; supplies a computed body for the first time in the whole corpus) | CORPUS-SUPPORTED, DEFINED, PROVED (`[THM 6.1]`/`[THM 6.2]`) — **no adversarial review found (GAP-004)** |

### `Sat_c` (the class-indexed, three-valued predicate — a DIFFERENT object)

| Ver | First witness | Codomain | Definition | Status |
|---|---|---|---|---|
| v1 | `[00-55]` | 3-valued | `Sat(K_t,r)∈{⊤,⊥,𝖴}`, split by 8 requirement classes `𝓡_c`, Kleene composition | NEW | Obstruction found same-document (CE-1 factivity) |
| v2 | `[00-56]` | 3-valued, finer `𝖴` taxonomy | epistemic/theory/world `𝖴` split; `PB-2` (`Sat_content` not total), `PB-4` (Kleene collapse) | REFINEMENT | Defects found, not repaired |
| v3 | `[00-57]`/`[00-59]` | derived projection | `Sat_c := value ∘ Eval_c` ("if justified") | RECLASSIFICATION — demoted from primitive to derived | SUPERSEDED (demoted); no later file in the traversal re-engages `Sat_c` by this name |

**`Sat_c` is never cited, extended, or reconciled by the Theory-00-21 rewrite (`Sat` v6/v7 above).** No
corpus-native bridge `Sat_c → Sat_v6/v7` was found — recorded `UNWITNESSED`.

### `Sat*` (the MD-061-constructed candidate, from prior MDs — a THIRD, distinct object)

Not re-encountered in the 876-file traversal (it originates in `three_model_convergence/14_decision-
log/MD-061-*`, this reconstruction's own artifact, not a primary corpus source). Retained here only to
record explicitly that it is **UNRELATED_HOMONYM** to both `Sat` and `Sat_c` above — a construction by
this reconstruction itself (MD-061), not a corpus-native object, and MD-062 already found it never
consumes `EC_t`. No relation to `[05-41]`'s `Sat` v7 is established or claimed.

## `Eval_c` / `Eval` — kept separate per instruction (though the corpus itself increasingly conflates them)

| Ver | First witness | Type/Arity | Codomain | Definition | Change from prev. | Status |
|---|---|---|---|---|---|---|
| v1 | `[00-57]` | `Eval_c(K_t,r,Γ_t)` | undetermined, deliberately | `→ EVal_c`, codomain not presupposed | NEW | SUPERSEDED |
| v2 | `[01-01]` | `Eval_c(K_t,r,Γ_t)` | 3-tuple | `→ EVal_c=(v,ρ,π)` (value, reason, provenance) | RE-DEFINITION, `SAME_LINEAGE_AS` v1 but no citation found — independently arrived at ~40 min later | SUPERSEDED |
| v3 | `[02-06]`→`[02-38]` | successive factor-additions | 4- to 7-tuple | `Standing×Boundary`, then `+Context+Provenance+Reliability+Accessibility+Assurance` (never all adopted at once) | EXTENSION, none formally adopted | ABANDONED (superseded by v4) |
| v4 | `[05-38]` (Theory-00-21, Part III) | `Eval(K,p,Γ,EC)` | 5-tuple | `=⟨E_p,J_p,U_p,C_p,S_p⟩` | RE-DEFINITION (drops v1–v3's names, introduces its own field set) | SUPERSEDED (by v5) |
| v5 | `[05-41]` (Theory-00-21, Part VI) | `Eval:K×E×P×EC×Γ→𝒱` | structured 7-field vector | `v=⟨Support,CounterSupport,Uncertainty,Conflict,Dependencies,Assumptions,Justification⟩` | EXTENSION of v4 (adds explicit type signature, renames/restructures fields) | CORPUS-SUPPORTED, feeds `Sat` v7 directly via `EvalReq` |

**No corpus-native bridge `Eval_c` v1–v3 → `Eval` v4/v5 was found** — same general shape (a structured,
multi-field evaluation output), never explicitly identified as the same object. Recorded
`SAME_LINEAGE_AS` (shared conceptual role) but the specific field-mapping is `UNWITNESSED`.

## `EvalReq`

| Ver | First witness | Definition | Status |
|---|---|---|---|
| v1 | `[05-41]` | `EvalReq(K,r,EC,Γ)` — evaluation of `Eval` output specialized to one requirement `r` | NEW, sole version found; feeds `Sat` v7 directly | CORPUS-SUPPORTED, DEFINED; own internal computation left as a black box (type given, no algorithm) |

## `Δ_t` — two senses, kept separate

### Sense 1 — Sat-gap (the tracked sense)

| Ver | First witness | Definition | Change | Status |
|---|---|---|---|---|
| v1 | `[00-44]` | `Δ_t=D(K_t,I_t;Q_t,C_t,EC_t)` | NEW, undifferentiated distance | SUPERSEDED |
| v2 | `[00-47]` (canonical) | `[DEF-21]` `Δ_t=Gap(K_t,EC_t)={r∈Req(EC_t):¬Sat(K_t,r)}` | RE-DEFINITION (set-valued, per-requirement) | CORPUS-SUPPORTED, canonical |
| v3 | `[00-51]` | 10-class typed union `Δ_t=Δ^cov∪...∪Δ^repr` | EXTENSION (typed decomposition) | CORPUS-SUPPORTED |
| v4 | `[05-40]` (Theory-00-21, Part V) | `Δ_t={r∈I_t\|χ_EC_t(Sat(K_t,r,Γ_t))=0}` | EXTENSION (Boolean projection `χ_EC` of a richer `Sat` codomain) | CORPUS-SUPPORTED, PROVED (`[THM 5.1]`/`[THM 5.2]`) |

### Sense 2 — transition-residue (a genuine, unreconciled homonym)

| Ver | First witness | Definition | Status |
|---|---|---|---|
| v1 | `[04-27]` | `Δ_t=Diff(K_t,K_{t+1})` | UNRELATED_HOMONYM to Sense 1 |
| v2 | `[04-28]` | `Δ_t=(Δ_t^-,Δ_t^○,Δ_t^+)` (removed/qualified/acquired) | EXTENSION of Sense-2 v1; never touches Sense 1 |

**No corpus-native bridge between Sense 1 and Sense 2 exists anywhere in the traversal — permanent
`UNRELATED_HOMONYM`, not merely unresolved (GAP-005).**

## `Zero`

| Ver | First witness | Definition | Change | Status |
|---|---|---|---|---|
| v1 | `[00-09]` | `Zero(K,G,EC)`, informal | NEW | SUPERSEDED |
| v2 | `[00-47]` (canonical) | `[DEF-22]` `Zero(K_t,EC_t)⟺Δ_t=∅⟺K_t⊨EC_t`; `[THM-4]` proved | RE-DEFINITION | CORPUS-SUPPORTED, canonical |
| v3 | `[00-51]` | `Zero`=bottom element `⊥` of the Gap lattice `(𝒫(ℛ),⊆)` | EXTENSION (lattice-theoretic characterization) | CORPUS-SUPPORTED |
| v4 | `[05-40]` | restated over the refined Δ_t v4 | EXTENSION | CORPUS-SUPPORTED, PROVED |
| — (branch, distinct object) | `[01-01]` "ZeroLens" | `ZL(K_t,Γ_t,L_t)→Boundary_t` | RE-DEFINITION within the parallel Zero-Lens branch only | UNRELATED_HOMONYM to the main `Zero`(K_t,EC_t) line for practical purposes (never reconciled) |
| — (branch, distinct object) | `[03-16]`ff "`Zero_{T,Π}`" | `Zero_{T,Π}(S;D)⟺Π(T(D))=Π(T(D∖S))` | RE-DEFINITION within the Zero-Algebra branch | UNRELATED_HOMONYM — extensively empirically tested WITHIN its own branch (KR-BRIDGE-01: no causal link to "Adequate"), never engages `EC_t`/`Sat(K_t,r)` |

## `Determination` / `Det`

| Ver | First witness | Definition | Change | Status |
|---|---|---|---|---|
| v1 | `[00-24]`ff | informal, `Determine(K_t,Q_t,C_t)` | NEW | SUPERSEDED |
| v2 | `[00-52]` | set-valued | `Det(E_t,Q_t,C_t,S_t)=𝒜_t={h∈ℋ_Q:Warrant(h\|...)}` | RE-DEFINITION | SUPERSEDED |
| v3 | `[05-38]` (Theory-00-21, Part III) | boolean, defined via `Sat` v6 | `[Def 3.5]` `Det(K,p,EC,Γ)⟺∀r∈Req_p(EC,Γ),Sat(K,r)=Satisfied`; `[THM 3.1]` `Det⇒Δ_p=∅` (PROVED) | RE-DEFINITION | CORPUS-SUPPORTED, PROVED |
| v4 | `[05-41]` (Part VI) | restated via `Sat` v7 | `[Def 6.2]`; `[THM 6.1]` Determination-Gap Equivalence (PROVED); `[THM 6.2]` (PROVED) | EXTENSION | CORPUS-SUPPORTED, PROVED |
| v5 | `[05-56]` (Part XXI) | proof-relative refinement | `[THM 21.8]` Determination Separation (PROVED): valid proof ⇏ Det | EXTENSION | CORPUS-SUPPORTED, PROVED |

## `Decision`

Never independently formalized as a computable function anywhere in the traversal. Consistently kept
`≠ Determination` (`Truth≠Evaluation≠Determination≠Decision`, restated `[00-46]` onward). Most rigorous
treatment: `[05-51]` `[THM 16.38]` Decision-Theoretic Separation Theorem (PROVED) — Determination does
not uniquely determine a Decision absent an explicit decision rule/contract. **Status: CORPUS-SUPPORTED
as a distinguished node, UNRESOLVED as an object with its own internal structure.**

## `Evidence`, `Context` (`Γ_t`), `Reason`, `Provenance`, `Condition`

None of these five receive an independent, standalone formal definition anywhere in the 876-file
traversal — each is consistently used as a **field or ambient parameter** of whatever `Eval`/`Sat`/
`Det`/`δ` function is current at that point in the corpus (e.g. `Reason`=`ρ` in `[01-01]`'s `EVal_c`,
`J_p` in `[05-38]`'s `Eval`; `Provenance`=`π` in `[01-01]`, absent by name from `[05-41]`'s own field
list — its closest analogue there is `Dependencies`/`Justification`). **Status: USED throughout, never
independently DEFINED — this is recorded as a standing, structural fact about the corpus, not treated
as five separate open gaps** (per the user's own instruction not to create speculative gaps).

## `ℛ` vs `ℛ_req` vs `Req` — three distinct objects, never merge by spelling

| Symbol | Object | First witness | Definition | Relation to the other two |
|---|---|---|---|---|
| `Req(EC_t)` | the tracked requirement set | `[00-20]`→`[00-47]` | see `Req(EC_t)` table above | — |
| `ℛ_req(Q,Γ)` | "Required Distinction Universe" | `[02-45]` | a set of preserved equivalence-relations/distinctions, `Adequate(R,Q)⟺D_Q⊆Preserved(R)` | **UNRELATED_HOMONYM to `Req(EC_t)`** — already tracked as `EKS-41` |
| `ℛ` (bare) | representation functions | `phase_measure_theory/` step_276 (found by Lane T, recorded in `EKS-41` Appendix A) | `{Serialize,Deserialize,Save,Load}` | **UNRELATED_HOMONYM to both of the above** — a third sense of the same base glyph, oldest of the three (2026-08-30) |

## `A_t` (AttributedState)

**Birth**: `[01-56]`, the governance rename of `K_t` (see `K_t` v6 above). No independent definition of
`A_t`'s own internal structure was found anywhere in the traversal beyond "non-factive attribution,
factive `Knows` moved external." **Status: GOVERNANCE-DECIDED as a name, UNRESOLVED as a structure;
its relation to `[05-36]`ff's own bare `K`/`K_t` usage (Theory-00-21 never uses `A_t`) is itself
UNWITNESSED.**
