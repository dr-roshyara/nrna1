# MD-078 §01 — Theory Object Registry and Definition Evolution Registry

Extends, never replaces, MD-068's own Definition Evolution Registry and Theory Object Registry.
Every historical formulation is preserved; none is overwritten. Evidence-status tags used:
`SOURCE-STATED` / `DIRECTLY EVIDENCED` / `RECONSTRUCTED` / `INFERRED` / `HYPOTHESIS` / `UNRESOLVED`.

## `Det_r`

| Time | Source | Formulation | Completeness | Evidence status |
|---|---|---|---|---|
| T21, 2026-09-06 00:39:47 | Part VI §6.18 (`mathematical_ideas/…theory-part-06…`) | `Det_r: 𝒱×EC → 𝕊_sat`, "a contract-specific determination function **may be defined**" | PARTIAL (signature only, no body) | SOURCE-STATED |

**No second occurrence anywhere in the corpus** — confirmed independently twice: this phase's own
21-part full-text census (zero hits outside Part VI), and the concurrent `01-BIRTH-CENSUS-DET-R-FAMILY.md`
census (1 file outside `three_model_convergence/`, the same Part VI file). Terminal status: **NOT YET
SEARCHED, by the concurrent census's own honest self-assessment, now RESOLVED by this phase's broader
access** — see §04. Classification: `SOURCE-STATED` (signature) / `GENUINE CORPUS GAP` (body).

## `EvalReq`

| Time | Source | Formulation | Completeness | Evidence status |
|---|---|---|---|---|
| T21, 2026-09-06 00:39:47 | Part VI §6.17 | `EvalReq(K,r,EC,Γ)`, prose-only: "should identify whether the evidence and reasoning satisfy the semantic conditions imposed by the requirement" | PARTIAL (no codomain stated) | SOURCE-STATED |
| MD-078 (this phase) | derived from `[Def 6.18]`'s own composition `Sat(K,r,Γ)=Det_r(EvalReq(...),EC)` | codomain is **forced to be `𝒱`** (or a subtype) by `Det_r`'s own stated domain `𝒱×EC→𝕊_sat` | mathematically necessary consequence, not source-stated | RECONSTRUCTED |

**Correction to the concurrent census's own finding**: its claimed 2026-08-27 birth date for `EvalReq`
(sourced to `step-025e-formal-epistemic-contract-algebra.md`) is a **substring false positive** —
verified directly: line 1007 of that file reads `EvalRequirement(K,r,C)`, a different, 3-argument
symbol (no `EC`, no `Γ`), already independently flagged as a homonym by a third document
(`verification/spec/STEP-VERIFY-025a-025g.md:240`, "this is a homonym, not the tracked `EvalReq`").
Recorded here forward; the concurrent census's own file is not edited. `EvalReq`'s true earliest and
only occurrence remains Part VI §6.17.

## `Sat` (all arities and subscripts)

| Time | Source | Formulation | Completeness | Evidence status |
|---|---|---|---|---|
| **Birth**, 2026-08-27 16:25:45 | `phase_measure_theory/step-023` §10 | `Sat(K,r_i)` = "the degree/status to which knowledge K satisfies requirement r_i"; immediately used as `Sat(K,r_i)=Satisfied`, a **stipulated** boolean input, "may be mandatory" | PARTIAL — gloss only, no computation rule, from birth | SOURCE-STATED |
| T5, 2026-09-01/02, `[00-51]` | main math lane | `Sat(K_t,r)∈{0,1}` (2-arg, Boolean codomain) | PARTIAL | SOURCE-STATED |
| Part 3 §3.13–3.14, T21 | `theory-part-03` | `Sat(K,r)=Partial` example; `𝒮_sat={S,U,P,C}` (4-value), `Sat:𝕂×Req→𝒮_sat` (2-arg), projection `π_EC:𝒮_sat→{0,1}` | COMPLETE as a codomain+projection scheme (2-arg) | SOURCE-STATED |
| Part 5 §5.6–5.7, T21 | `theory-part-05` | "Part III introduced generalized satisfaction. We now formalize it." `𝕊_sat={Satisfied,Partial,Unsatisfied,Unknown,Conflicted}` (**5**-value, Unknown≠Unsatisfied), `Sat(K,r,Γ)` (**3-arg**), projection `χ_EC:𝕊_sat→{0,1}` | COMPLETE as a codomain+projection scheme (3-arg) | SOURCE-STATED, but **self-labeled a refinement of a 2-arg/4-value predecessor while actually changing arity, value-count, and projection name** — flagged as `SAME CONCEPT, REFINED` with undisclosed drift, not a clean extension |
| Part 6 §6.18, T21 | `theory-part-06` | `Sat(K,r,Γ)=Det_r(EvalReq(K,r,EC,Γ),EC)` | the 3-arg *composition*, not a new codomain | SOURCE-STATED |
| Part 21a/21a-rev2 (T22), same day | worked example | `Sat(K,r_i)=Satisfied`/`Unsatisfied`, 2-arg, fiat | stipulated, matches the T5/birth pattern exactly | SOURCE-STATED |
| Parts 12/13/14/15/16/17/18/19/20/21 | dedicated per-domain `Δ_X` formulas | each reuses **2-arg** `Sat(K,r)` (or `Sat(P,r)` in Part 19 — argument-**type** change, K→P) inside a `Δ_X(K,X)={r∈Req_X(X):¬Sat(K,r)}`-shaped definition | each individually complete as an aggregation rule | SOURCE-STATED |
| `research/knowledgeos-sim/kos/inquiry.py` | executable code | `Sat(K,r,E=None)`, kind-dispatched (`determined`/`unique`/`corroborated`/`causal`/`provenanced`) against a dict-valued `K` — **a genuine, running computation rule** | COMPLETE and executable | DIRECTLY EVIDENCED (code, not narrative) |
| `research/knowledgeos-sim/kos12/satc_spec.py` | executable code | `Sat_c(K,r;Γ)` (semicolon separator — distinct notation), three-valued `⊤/⊥/U` over 8 sub-predicates, adversarially tested | COMPLETE and executable, self-labeled "not canonical" | DIRECTLY EVIDENCED |

**Object-identity finding**: the 2-arg `Sat(K,r)` (birth/T5/Parts 3,12–21/T22) and the 3-arg
`Sat(K,r,Γ)` (Part 5's own reformulation, Part 6's `[Def 6.18]`) are `SAME CONCEPT, REFINED` by the
source's own self-description, but the refinement is never actually completed — every concrete
instance in the corpus after Part 5's own introduction, **including inside Part 21 itself** (Definition
21.4: `Sat(K,r)=Satisfied`, 2-arg) and including T22's own worked example, reverts to the 2-arg form.
The `Sat_c`/`kos/inquiry.py` constructions are `RELATED OBJECT` — same role (requirement satisfaction),
different signature, explicitly non-canonical.

## `𝕊_sat` / `𝒮_sat`

Two source-stated, structurally different enumerations exist within the same rewrite (Part 3: 4-value
`{S,U,P,C}`, symbol `𝒮_sat`; Part 5: 5-value `{Satisfied,Partial,Unsatisfied,Unknown,Conflicted}`,
symbol `𝕊_sat`, with `Unknown≠Unsatisfied` explicitly distinguished). `Det_r`'s own Part 6 codomain
`𝕊_sat` most plausibly refers to Part 5's 5-value set (same symbol, same Part-numbering proximity), but
**this is `RECONSTRUCTED`, not `SOURCE-STATED`** — Part 6 never explicitly cites Part 5 or restates the
five-value enumeration.

## `Γ`

| Time | Source | Formulation | Fields |
|---|---|---|---|
| Part II Def 2.15, T21 | `theory-part-02` | `Γ=⟨D,P,T,U,C,V,A⟩∈Ctx` | 7: Domain, Participant, Temporal, Purpose, Constraints, Vocabulary, Authority |
| Part VIII §8.5, T21 | `theory-part-08` | `Γ=(Domain,Time,Purpose,Vocabulary,…)` | 4 + ellipsis |
| Part X §10.31, T21 | `theory-part-10` | **differently-named** `Γ_I=⟨Context,Time,Contract,Model,Rules,Assumptions⟩` | 6 |
| Part 21 §21.3, T21 | `theory-part-21` | **differently-named** `Γ_R=⟨Context,Time,Contract,Model,Rules,Assumptions,Authority,Resources⟩` | 8 |
| Part VI §6.3/§6.17 | `theory-part-06` | bare, unstructured — glossed only as "context" | none stated |

**Terminal classification: E — OBJECT IDENTITY UNRESOLVED.** Four mutually incompatible structured
definitions exist within the same 21-part rewrite, none cross-referencing any other; Part VI's own
`EvalReq(K,r,EC,Γ)` usage — the specific usage this whole investigation concerns — commits to none of
them. This corrects an earlier, over-hasty working note from this same phase's own initial pass
(that Part II's Def 2.15 alone "resolves" MD-076's "no definition at any level" finding) — the correct,
now fully-evidenced statement is: Γ is not undefined, but its definition is **unresolved among at least
four competing formalizations**, which is a different and in some ways more severe finding than
absence. MD-076's own text is not edited; this is recorded forward.

## `EC_t` / `EC`

| Time | Source | Fields | Notes |
|---|---|---|---|
| **Birth**, 2026-08-27 16:25:45 | `phase_measure_theory/step-023` §14 | `EC=(Purpose,Requirements,EvidenceRules,UncertaintyLimits,ConflictRules,TemporalRules,AuthorityRules)` | 7-field; "I recommend introducing EpistemicContract as a first-class domain concept" |
| `step-025d` (per verification-lane's own prior "EC SIGNATURE DRIFT" audit, `STEP-VERIFY-025a-025g.md:238`) | `phase_measure_theory` | `EC_G=(R_G,Γ_G)` | 2-field |
| `step-025e` | `phase_measure_theory` | `EC=(R,Γ,A,V)` | 4-field |
| same lineage, later same-day | `phase_measure_theory` | `EC=(Requirements,…,Version)` | 9-field |
| `step-025f` | `phase_measure_theory` | `EC=(R,Γ,A,S,T,D,X,V)` | 8-field, Provenance dropped |
| `verification/DEFINITION-VERIFICATION-REGISTER.md` DV-27 | verification lane, self-dated audit | `EC=(Purpose,Requirements,EvidenceRules,UncertaintyLimits,ConflictRules,TemporalRules,AuthorityRules)` | 7-field, matches step-023's own birth formulation exactly; verdict "CLEAR as a structure, INCOMPLETE as a derivable object" |
| T5, `[00-47]` (DEF-19), 2026-09-01/02 | math lane | `EC_t=EC(S_t,G_t,Q_t,C_t)` | 4-field, time-subscripted |
| T21 Part I/II, 2026-09-06 | `theory-part-01`/`02` | `EC=⟨Req,Rules,Scope,ER,TR,AR⟩` | 6-field, "exact structure will be refined later" (never refined in any of the remaining 19 parts) |
| `research/knowledgeos-sim/kos/inquiry.py` | executable code | `EpistemicContract(id,standard,requirements,attribution_policy)` | 4-field dataclass, cites `DEF-20`/`21`/`22` — the exact T5 citation IDs |

**Terminal classification: C — COMPETING COMPLETE DEFINITIONS.** At least seven mutually distinct,
individually-complete formulations exist across a corpus history spanning 2026-08-27 through 2026-09-06,
none reconciled. This substantially sharpens MD-068's own GAP-002 ("competing 4-field vs 6-field EC_t...
UNRESOLVED, non-blocking, each lineage self-sufficient") — the competing-definitions count is far larger
than GAP-002's own two-way framing, and the phenomenon is documented as a **known, named finding**
("EC SIGNATURE DRIFT") inside the verification lane's own prior audit, predating this reconstruction's
own discovery of it. GAP-002's own "non-blocking" verdict is not overturned — no single evaluation case
requires reconciling all seven — but its scope was understated.

## `Req` / `Req(EC)`

Stable across the corpus as a *shape*: a finite set of requirement-members indexed by a contract,
`Req(EC)={r_1,...,r_n}` (Part II line 1104) or `Req(EC,Γ)` (Part 21 Def. 21.4, 2-arg, subscripted by
proposition `q`: `Req_q(EC,Γ)`). Every domain-specialized Part reuses the same shape with a
domain-specific contract argument (`Req(RC)`, `Req(DC)`, `Req_{Arch}(AC)`, `Req_P(PC)`, `Req(RCog)`,
`Req(CC)`, `Req(MC)`, `Req(FC)`). **Terminal classification: A — COMPLETE**, for the function's own
shape (a contract-indexed set) — the one object in this whole family with genuine, unforced,
cross-Part consistency. What populates that set — `r`'s own internal structure — is a separate, far
less stable question (see below).

## `r` (requirement instance)

**Terminal classification: E — OBJECT IDENTITY UNRESOLVED, the single most severe finding of this
phase.** At least six structurally incompatible internal formulations exist, several **within the same
file**:

| Source | Formulation | Object sense |
|---|---|---|
| T5, `[00-51]` | `r=(id,type,scope,content,standard,priority,validity)` (7-field, positional) | requirement |
| T21 Part II Def 2.18 | `r∈Req` — abstract, no tuple; eleven possible concern-topics listed, not decomposed | requirement |
| T21 Part IV §4.11 | `r` = a relation **type** used in `LINK(x,y,r)→r(x,y)` | relation-type, unrelated |
| T21 Part IX §9.2 | `r=⟨type,source,target,context,time,provenance,status⟩` (7-field) | relation-**instance** |
| T21 Part IX §9.47, *same file* | `r=(x,R,y)` (3-field) | relation-instance, contradicts §9.2 |
| T21 Part X §10.6 | `r=⟨Premises,Conclusion,Conditions,Exceptions,Logic,Authority,Version,Provenance⟩` (8-field) | inference-rule |
| T21 Part X §10.27, *same file* | `r=⟨Premises,Conclusion,Exceptions⟩` (3-field) | inference-rule, contradicts §10.6 |
| T21 Part 13 §13.58–59 | `r∈Req(EC)`, refined with `g(r)∈{Satisfied,Unknown,Partial,Conflicted,Uncertain}` (5-value gap classification layered on top) | requirement, consistent with Def 2.18's own sense |

`Det_r`/`EvalReq`'s own `r` (Part VI) is, by contextual continuity with Part II's own immediately-
preceding formal ontology (both installments of the same rewrite, Part II explicitly the "type system"
Part), best read as `SAME OBJECT` as Def 2.18's abstract `r∈Req` — **`RECONSTRUCTED`, not
`SOURCE-STATED`**, since Part VI never explicitly cross-references Part II. It is definitively
`UNRELATED_HOMONYM` to the relation-instance and inference-rule senses found in Parts IV/IX/X.

## `standard`

GAP-001 (MD-068) already found `standard` relocated from `r`'s own field (T5-lineage `[00-51]`) into
`EC.Rules` (T21). This phase confirms, across the **complete** 21-part rewrite (not just Parts I/II as
MD-068 checked): `EC.Rules`/`Rules` never receives internal structure anywhere in any of the 21 parts —
every occurrence is either the bare field name or an unrelated `Rules` field on a different object
(`Γ_I`'s own `Rules` field, Part X §10.31; a `Rule` tuple, Part 18 §18.18, structurally unrelated to
`EC.Rules`). **GAP-001's own "CLOSED WITH QUALIFICATION" verdict is reaffirmed, now exhaustively, not
overturned** — `standard`/`Rules`' own openness is a disclosed, deliberate, unclosed design parameter
throughout the entire rewrite, not merely its opening installments.

## `Acceptance` / `AcceptanceCondition`

**Zero occurrences of `AcceptanceCondition` anywhere** — confirmed across all 23 Theory-00-21 files and
all six cross-lane directories, by five independent agent censuses in this phase alone. Bare
`Acceptance`/`Accept(a)` appears only as unrelated, narrower predicates (e.g. Part 16 §16.23:
`Accept(a)⟺R(a)≤τ`, a 1-ary risk-threshold predicate, not the tracked `AcceptanceCondition` object).
**Terminal classification: D — GENUINE CORPUS GAP**, now the most exhaustively-confirmed absence in
this entire investigation. Part 18 (the dedicated DDD/architecture Part) does not name
`AcceptanceCondition` as a candidate bounded context, aggregate, or kernel concept at all.

## `Eval`

| Time | Source | Formulation |
|---|---|---|
| T21 Part VI §6.15 | `theory-part-06` | `Eval:K×E×P×EC×Γ→𝒱`, 𝒱 glossed only as "an evaluation space"; **zero invocations anywhere** (MD-076's own finding, unchanged) |
| T21 Part 17 §17.24 | `theory-part-17` | `Eval(M,D)→E_model` — Model×Dataset→Evidence, a **structurally distinct, narrower** function |

**Terminal classification: E (object identity unresolved)** for the bare symbol — two incompatible
objects share the name within the same rewrite. **D (genuine gap)** stands, unchanged, for Part VI's
own `Eval:K×E×P×EC×Γ→𝒱` specifically.

## `Eval_c` / `Sat_c`

See the `Sat` entry above. `research/knowledgeos-sim/kos12/satc_spec.py` + `evalc.py`: complete,
executable, adversarially-tested three-valued implementation, `Eval_c` primary, `Sat_c:=value∘Eval_c`
a disclosed candidate projection. **Terminal classification: B — COMPLETE THROUGH MULTIPLE SOURCES**,
for this *related*, differently-notated (`Sat_c(K,r;Γ)`, semicolon) construction — not identity with
T21's `Sat(K,r,Γ)`/`EvalReq`.

## `Δ_t` / `Zero` / `Determination` / `Decision`

Treated together: their **aggregation logic**, given `Sat` values, is proven complete and consistent
across at least three independent Parts of the same rewrite (Part 3 Def. 3.5 + Theorem 3.1; Part 5
Def. 5.2, using the 3-arg `Sat` via `χ_EC`; Part 21 Def. 21.4) plus a working code implementation
(`kos/inquiry.py`'s `Gap`/`Zero`/`Adequate`, citing the same `DEF-20/21/22` IDs as T5). The separation
`Determination⇏Decision` is independently proven at least four times (Part 3 Theorem 3.2; Part 13
§13.20/13.57/13.65; Part 16 Theorem/§16.38-style; Part 18 XVIII-C8/C9; Part 21 Theorem 21.6/XXI-C3) —
the most robustly, consistently, multiply-corroborated result in the entire tracked family.
**Terminal classification: A — COMPLETE**, for the aggregation/separation logic specifically. `Decision`
itself receives one rich, never-reused, never-tested 11-field tuple (Part 16 §16.4,
`D=⟨Q,A,K,EC,U,C,Π,Γ,t,Auth,Status⟩`) — classified `B` at best (one complete proposal, uncorroborated).
Every `Δ_X`/`Zero_X` instantiation is domain-locally complete but mutually non-uniform in arity and
second-argument type (`EC`/`CC`/`AC`/`PC`/`RC`/`RCog`/`FC`, 0–3 explicit arguments) — a `RELATED
OBJECT` family, not one function, consistent with `Req`'s own domain-specialization pattern.
