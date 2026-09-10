# MD-085 §01 — Mathematical Closure Matrix

Every row is grounded in a specific, already-verified prior finding, cited inline. No new construction,
mapping, or invented equation appears anywhere in this file.

## `EC` / `EC_t`

| | |
|---|---|
| Definition | at least seven distinct, individually complete tuple formulations (step-023 7-field; step-025 series 2/4/9/8-field; `T5` `[00-47]` 4-field `EC_t=EC(S_t,G_t,Q_t,C_t)`; T21 Part I/II 6-field `⟨Req,Rules,Scope,ER,TR,AR⟩`; `kos/inquiry.py` 4-field dataclass) — full ledger in MD-078 §01 / MD-081 §03 |
| Type complete | yes, *per individual formulation* — each is a fully specified tuple; no single canonical type exists across them |
| Dependencies complete | `Req(EC)` is a well-defined, stable dependency shape (`A`, MD-078 §04); `EC.Rules`'s own internal content is not (`D`, MD-081/082/084) |
| Computable | trivially yes as a data container; not "computable" in any deeper sense since `Rules` carries no content |
| Validated | only `kos/inquiry.py`'s own 4-field form is empirically exercised (executable code); every narrative form is unexecuted |
| Identity closed | **No** |

**Three-way**: A — `C, COMPETING COMPLETE DEFINITIONS` (unchanged). B — `SAME` (every formulation
answers "what must be known, at what strength, for what purpose" — MD-084's own `step-023` quote). C —
`PARTIALLY DEFINED` overall (each individual tuple `DEFINED`; the load-bearing `Rules` field `GENUINE
CORPUS GAP`, now `TERMINAL D` per MD-084 — open by design, not absence).

## `r` (requirement instance)

| | |
|---|---|
| Definition | requirement-sense family: `T5` `[00-51]` 7-tuple; T21 Part II Def 2.18 abstract `r∈Req`; Part 13 §13.58 refinement (`g(r)` 5-value); `kos/inquiry.py`'s `Requirement(id,prop,kind,note)` — MD-079 §01. Confirmed unrelated homonyms: relation-type (Part IV), two incompatible relation-instance tuples (Part IX), two incompatible inference-rule tuples (Part X) — same source |
| Type complete | yes for the requirement-sense family, taken together as one coherent (if unreconciled) lineage; homonym senses are typed for their own unrelated purposes |
| Dependencies complete | `r`'s own `standard` field is the same undefined content as `EC.Rules` (MD-078/081, `D`) |
| Computable | `r`'s own existence/identity is usable as a bound variable throughout; its `standard` field is not |
| Validated | `kos/inquiry.py`'s own `Requirement` dataclass is exercised in executable code |
| Identity closed | **No**, at the raw symbol level; **yes**, once the confirmed homonyms are excluded (MD-081 §02) |

**Three-way**: A — `E` at the raw-symbol level (six structurally incompatible senses, two pairs
contradicting within the same file, MD-078); narrows to a coherent `SAME CONCEPT, REFINED` lineage for
the requirement-sense sub-family specifically once homonyms are excluded (MD-079/081). B — `SAME`
(object identity and responsibility relation coincide cleanly here, MD-081 §02 — the one case in this
whole family where they do). C — `PARTIALLY DEFINED` (the shape is defined and used; `standard`'s own
content is the shared `EC.Rules` gap).

## `Γ`

| | |
|---|---|
| Definition | four mutually inconsistent structured forms (Part II Def 2.15 7-tuple `⟨D,P,T,U,C,V,A⟩`; Part VIII §8.5 4-field+ellipsis; Part X §10.31 `Γ_I` 6-field; Part 21 §21.3 `Γ_R` 8-field) plus `EvalReq`'s own bare, unstructured usage (Part VI) — MD-078 §01 |
| Type complete | yes for each individual structured form; **no** for the one usage that matters most (`EvalReq`'s own `Γ`, which commits to none of them) |
| Dependencies complete | unresolved — no source states which structured `Γ`, if any, `EvalReq` intends |
| Computable | n/a (an input parameter, not a computed function) |
| Validated | none of the four structured forms is empirically exercised anywhere |
| Identity closed | **No** |

**Three-way**: A — `E` (four incompatible structured definitions, `EvalReq`'s own usage `UNRESOLVED`
against all four, MD-078 §01). B — **`SUBDIVIDED`**, not pure competition — MD-081 §02's own finding:
`Γ_C`/`Γ_D`/`Γ_E` are domain-specific narrowings of `Γ_B`'s own general "interpretation and
admissibility of reasoning" job (identity-scoped, inference-scoped), not independent redefinitions.
This is the one object in the family where B is materially *more resolved* than A. C — `DEFINED`
(each individual form), `NOT COMPUTABLE` (n/a — parameter, not function); no validation anywhere.

## `Eval` / `Eval_c` / `EvalReq` / `Det_r`

| Object | Definition | Computable | Validated | Identity |
|---|---|---|---|---|
| `Eval` (Part VI §6.15) | `K×E×P×EC×Γ→𝒱`, worked 7-field `𝒱` example | zero invocations anywhere (MD-076/078) | no | — |
| `Eval(p)` (Part VI §6.42, *same Part*) | 6-field, single-argument, different fields from §6.15's own | not invoked | no | `UNRELATED_HOMONYM` to §6.15's `Eval` (MD-082 §01 — a third internal `Eval` inconsistency, alongside the already-known Part 17 homonym) |
| `Eval(M,D)→E_model` (Part 17 §17.24) | Model×Dataset→Evidence | narrower, unexercised in this reconstruction's own reading | no | `UNRELATED_HOMONYM` (MD-078 §01) |
| `EvalReq(K,r,EC,Γ)` (Part VI §6.17) | prose only, no stated codomain; codomain `𝒱` `RECONSTRUCTED` by composition-forcing (MD-078 §01); confirmed never invokes `Eval`'s own formula (worked example uses independent ad hoc predicates, MD-082 §02) | not computable | no | anchor for this cluster |
| `Det_r(EvalReq(...),EC)→𝕊_sat` (Part VI `[Def 6.18]`) | signature only, "may be defined," never supplied | not computable | no | `UNRELATED_HOMONYM` to `Det(K,p,EC,Γ)` (MD-082, new finding — shares a name-root only, different argument structure and role) |
| `Eval_c(K_t,r,Γ_t)→EVal_c` (`kos12`, "the primary object") | fully specified | computable, adversarially tested | yes | `RELATED OBJECT, CONSTRUCTED CANDIDATE` (MD-078/079) |
| `Standing(p)=(S⁺,S⁻,R,P,Ctx,Cond)` (`M0127`) | fully specified | computable, 12/14 scenarios | yes | `RELATED OBJECT` to `Eval`'s own output vector — performs a *neighboring* (per-proposition, not per-requirement) job, never carried into T21 (MD-080/082) |

**Three-way, cluster summary**: A — mixed, dominated by `UNRELATED_HOMONYM` relationships even *within*
Part VI itself. B — `Det_r`/`EvalReq`: `SAME, WIRED but not COMPUTED` within Part VI (MD-082, the
central correction of this whole family); `REDISCOVERED` at the family level (`Standing`/`Eval_c`
discharge the same general responsibility, never adopted, MD-080). C — `DEFINED`, `NOT COMPUTABLE`,
`NOT VALIDATED` for every T21-native instance; `DEFINED, COMPUTABLE, EMPIRICALLY VALIDATED` only for
the two independent, non-adopted executable alternatives.

## `Sat` — the full arity/scope family, as requested

| Form | Source | Arity/scope | Codomain | Status |
|---|---|---|---|---|
| `Sat(K,r_i)` | `step-023` (birth) | per-requirement, 2-arg | gloss only | `DEFINED` (name), fiat-consumed |
| `Sat(K_t,r)` | `T5` `[00-51]` | per-requirement, 2-arg | `{0,1}` | `DEFINED`, fiat-consumed |
| `Sat(K,EC_t)` | `T5` `[DEF-19]`/`[DEF-20]` | **contract-level**, 2-arg (`EC` not `r`) | undefined body | `DEFINED` (primitive), aliased by `Adequate` (MD-084) |
| `Sat:𝕂×Req→𝒮_sat` | Part III §3.14 | per-requirement, 2-arg | 4-value | `DEFINED` (codomain only) |
| `Sat(K,r,Γ)` | Part V §5.6–5.7 | per-requirement, **3-arg** | 5-value `𝕊_sat` + `χ_EC` projection | `DEFINED`, claims to "formalize" Part III, actually changes arity/value-count (undisclosed drift, MD-078) |
| `Sat(K,r,Γ)=Det_r(EvalReq(...),EC)` | Part VI `[Def 6.18]` | per-requirement, 3-arg | `𝕊_sat` via `Det_r` | `DEFINED` (full composition), `NOT COMPUTABLE`; **wired** to `Det(K,p,EC,Γ)`→`Δ_p`→`Zero_p` within the same Part (MD-082, `RECONSTRUCTED` same-symbol link, strongest same-object case in the family) |
| `Sat(K,r_i)=Satisfied` | T22 worked example | per-requirement, 2-arg | fiat | reverts to birth pattern, same day as `[Def 6.18]` |
| `Sat(K,r)=Satisfied` | Part 21 Def 21.4 | per-requirement, 2-arg | fiat | reverts again, **despite postdating** Part V/VI's own 3-arg innovation |
| `Sat(P,r)` | Part 19 | per-requirement, 2-arg, **`K`→`P` argument-type change** | Boolean | a genuine type-level variant, unique to the persistence context |
| `Sat_c(K,r;Γ)` | `kos12` (semicolon notation) | per-requirement, 3-arg (no `EC`) | `{⊤,⊥,U}` | `DEFINED, COMPUTABLE, EMPIRICALLY VALIDATED` — `RELATED CONSTRUCTION` |
| `Sat(K,r,E)` | `kos/inquiry.py` | per-requirement, 3-arg (`E` not `Γ`, no `EC`) | `{True,False}`, kind-dispatched | `DEFINED, COMPUTABLE, EMPIRICALLY VALIDATED` — `RELATED CONSTRUCTION` |

**Is `Sat` one evolving object?** No, and not a clean answer of "multiple objects" either. **Object
identity: `SAME CONCEPT, REPEATEDLY REFINED, NEVER RECONCILED`** — every instance shares the same core
intent ("does `K` satisfy some requirement/contract"), which is why none of the homonym-confirmation
tests this reconstruction has applied elsewhere (unrelated bounded context, incompatible role) apply
here — but at least three genuinely incompatible arities/scopes (contract-level 2-arg; requirement-
level 2-arg; requirement-level 3-arg) are used interchangeably, non-monotonically (T22 and Part 21 both
*revert* to the 2-arg form *after* the 3-arg form was introduced), and never reconciled by any source.
**Semantic responsibility: `SAME`** throughout, without exception. **Computational completeness:
`DEFINED, NOT COMPUTABLE`** for every T21/T5/birth-native instance, without exception — the only
`COMPUTABLE, EMPIRICALLY VALIDATED` instances are the two independently-constructed, never-adopted
executable alternatives.

## `Determination` / `Decision`

| | |
|---|---|
| Definition | `Det(K,p,EC,Γ)⟺∀r∈Req_p(EC,Γ):Sat(K,r)=Satisfied`, independently re-derived at least twice within T21 itself (Part III Def 3.5/Thm 3.1; Part VI Def 6.2/Thm 6.1, MD-082); `Decision(d,K,Q,P,A,Γ)` with proven separation, independently proven ≥4 times (Part III Thm 3.2; Part 13; Part 16; Part 18; Part 21) |
| Type complete | yes |
| Dependencies complete | yes, **given** `Sat`'s own value by any route |
| Computable | yes, given `Sat`'s own value |
| Validated | proven (multiple independent proofs), not empirically tested against real data — a proof-level, not an execution-level, validation |
| Identity closed | yes, internally consistent; `Det_r` confirmed `UNRELATED_HOMONYM` to this family (MD-082) |

**Three-way**: A — `A, SAME OBJECT` (internally, across its own re-derivations). B — `SAME`. C —
`DEFINED, COMPUTABLE (given Sat), proof-validated` — the most fully closed object in this entire family.
