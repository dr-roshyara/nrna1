# MD-079 §01 — Object-Level Comparison Matrices

Every row sourced from MD-078 §01's own direct primary-source verification (this phase performed no
new reading). `Relationship` column uses this reconstruction's own standing six-way taxonomy
(`SAME OBJECT` / `SAME CONCEPT, REFINED` / `RELATED OBJECT` / `UNRELATED_HOMONYM` / `UNRESOLVED`),
stated relative to the row this phase treats as the **anchor** (marked ⭐) for that object family — the
formulation most directly load-bearing for T21's own `Sat(K,r,Γ)=Det_r(EvalReq(K,r,EC,Γ),EC)`.

## `r` (requirement instance) — the most severe case

| ID | Source | Type | Meaning | Fields/Inputs | Outputs | Context | Relationship to anchor (r_B) |
|---|---|---|---|---|---|---|---|
| `r_A` | T5 `[00-51]`, math lane | positional 7-tuple | a requirement instance | `id,type,scope,content,standard,priority,validity` | n/a (a value, not a function) | pre-T21, different lineage | `RELATED OBJECT` — plausibly one possible instantiation of `r_B`'s abstract sort, never source-stated as such (`RECONSTRUCTED`) |
| ⭐ `r_B` | T21 Part II Def 2.18 | abstract set-member | "a condition a knowledge state must satisfy under an epistemic contract" | none (no fields given); 11 possible concern-topics listed, not decomposed | n/a | same rewrite, "formal ontology" installment — the installment Part VI's own `EvalReq(K,r,EC,Γ)` most plausibly draws from | anchor |
| `r_C` | T21 Part IV §4.11 | a relation **type** | used in `LINK(x,y,r)→r(x,y)` | none stated as a tuple | a binary relation | same rewrite, "transitions" installment | `UNRELATED_HOMONYM` — not a requirement at all |
| `r_D` | T21 Part IX §9.2 | 7-tuple, relation **instance** | a semantic relation occurrence | `type,source,target,context,time,provenance,status` | n/a | same rewrite, "relations/graph" installment | `UNRELATED_HOMONYM` |
| `r_E` | T21 Part IX §9.47, *same file as `r_D`* | 3-tuple, relation instance | `Prov(r)` argument | `(x,R,y)` | n/a | same file as `r_D`, contradicts it | `UNRELATED_HOMONYM`; also internally `UNRESOLVED` against `r_D` |
| `r_F` | T21 Part X §10.6 | 8-tuple, inference **rule** | a KnowledgeOS inference rule | `Premises,Conclusion,Conditions,Exceptions,Logic,Authority,Version,Provenance` | n/a | same rewrite, "inference" installment | `UNRELATED_HOMONYM` |
| `r_G` | T21 Part X §10.27, *same file as `r_F`* | 3-tuple, inference rule | a defeasible rule w/ exceptions | `Premises,Conclusion,Exceptions` | n/a | same file as `r_F`, contradicts it | `UNRELATED_HOMONYM`; also internally `UNRESOLVED` against `r_F` |
| `r_H` | T21 Part 13 §13.58–59 | `r_B`'s own sense, refined | requirement, with a richer gap-status | `r∈Req(EC)`; `g(r)∈{Satisfied,Unknown,Partial,Conflicted,Uncertain}` | `g(r)`, a 5-value status | same rewrite, "uncertainty/decision" installment | `SAME CONCEPT, REFINED` relative to `r_B` — a genuine, disclosed extension, not a contradiction |
| `r_I` | `kos/inquiry.py`, executable | 4-field dataclass | a requirement, with an executable satisfaction test | `id,prop,kind,note`; `kind∈{determined,unique,corroborated,causal,provenanced}` | consumed by a working `Sat(K,r,E)` | independent research code, self-dated within the T5–T21 window | `RELATED OBJECT` — `kind`'s 5-value taxonomy *partially* overlaps `r_B`'s own 11-topic list (provenance↔provenanced, evidence/consistency↔corroborated) but is never source-stated as an instantiation of it; `RECONSTRUCTED` at best |

**Verdict for `r`**: `r_B` (anchor) and `r_H` form one coherent, `SAME CONCEPT, REFINED` lineage —
genuinely usable together. `r_A` and `r_I` are plausible but *unproven* instantiations of that same
abstract sort. `r_C`/`r_D`/`r_E`/`r_F`/`r_G` are confirmed unrelated homonyms, safely excluded from any
composition attempt. The genuine open question is narrower than "six incompatible definitions" made it
sound: it is specifically **whether `r_A` or `r_I` may be adopted as `r_B`'s own concrete
instantiation** — and no source anywhere states that they may.

## `Γ` (context)

| ID | Source | Type | Meaning | Fields | Relationship to anchor (Γ_B) |
|---|---|---|---|---|---|
| ⭐ `Γ_B` | T21 Part II Def 2.15 | 7-tuple, `Γ∈Ctx` | "parameters that determine interpretation and admissibility of reasoning" | `D,P,T,U,C,V,A` (domain, participant, temporal, purpose, constraints, vocabulary, authority) | anchor — same rewrite, same "type system" installment as `r_B` |
| `Γ_C` | T21 Part VIII §8.5 | 4-field + ellipsis | proposition-identity dependency | `Domain,Time,Purpose,Vocabulary,…` | `RELATED OBJECT` — a strict subset of `Γ_B`'s own 7 fields plus an unspecified remainder; plausibly a partial restatement, never confirmed |
| `Γ_D` | T21 Part X §10.31 | differently-**named** 6-tuple `Γ_I` | inference-preserving context | `Context,Time,Contract,Model,Rules,Assumptions` | `RELATED OBJECT` — different name, partial field overlap (`Time`), introduces fields (`Contract`,`Model`,`Rules`) absent from `Γ_B` |
| `Γ_E` | T21 Part 21 §21.3 | differently-named 8-tuple `Γ_R` | reasoning context | `Context,Time,Contract,Model,Rules,Assumptions,Authority,Resources` | `RELATED OBJECT` — a superset-shaped extension of `Γ_D`, not of `Γ_B` |
| `Γ_F` | T21 Part VI §6.3/§6.17 (`EvalReq`'s own usage) | bare, unstructured | "context" | none stated | `UNRESOLVED` — the specific usage this whole audit concerns commits to none of `Γ_B`/`Γ_C`/`Γ_D`/`Γ_E` explicitly |

**Verdict for `Γ`**: unlike `r`, there is no confirmed homonym here — every variant is plausibly "the
same general idea of context," and `Γ_D`/`Γ_E` even share a visible lineage with each other (`Γ_E`
literally extends `Γ_D`'s own field list). But **`EvalReq`'s own usage (`Γ_F`) — the one that actually
matters for this audit — never commits to any of them.** Composing `EvalReq(K,r,EC,Γ)` with any
structured `Γ` requires picking one of four candidates with no source-stated basis for the choice.

## `EC`/`EC_t` (Epistemic Contract)

| ID | Source | Fields | Relationship to anchor (EC_B) |
|---|---|---|---|
| `EC_step023` | `phase_measure_theory/step-023`, 2026-08-27 | `Purpose,Requirements,EvidenceRules,UncertaintyLimits,ConflictRules,TemporalRules,AuthorityRules` (7) | genuine birth; `SAME_LINEAGE_AS` everything below, conceptually — `UNWITNESSED` documentarily |
| `EC_{025d..f}` | `phase_measure_theory/step-025` series | 2→4→9→8 fields, already self-documented as drifting by the verification lane | `SAME_LINEAGE_AS` `EC_step023`, source-stated as one evolving object, unreconciled |
| `EC_{T5}` | `[00-47]`, math lane | `EC_t=EC(S_t,G_t,Q_t,C_t)` (4) | `RELATED OBJECT` relative to the `step-023`/`025` lineage — `RECONSTRUCTED`, not source-stated, independence already confirmed by MD-067's own citation check |
| ⭐ `EC_B` | T21 Part I/II | `⟨Req,Rules,Scope,ER,TR,AR⟩` (6) | anchor — the formulation `Det_r`/`EvalReq` (Part VI, same rewrite) actually operates over |
| `EC_verif` | `verification/DEFINITION-VERIFICATION-REGISTER.md` DV-27 | matches `EC_step023` exactly (7) | `SAME OBJECT` as `EC_step023` — a direct verbatim quote/audit, not an independent formulation |
| `EC_kos` | `kos/inquiry.py`, executable | `id,standard,requirements,attribution_policy` (4) | `RELATED OBJECT` — different field set entirely, cites `DEF-20/21/22` (the `T5` lineage's own citation IDs), suggesting deliberate `T5`-lineage implementation, not `EC_B` |

**Verdict for `EC`**: seven mutually distinct, individually complete formulations, none reconciled. The
anchor `EC_B`'s own critical field, `Rules`, is *never* given internal structure by `EC_B` itself or by
any of the 21 parts (confirmed exhaustively, MD-078 §01). None of the other six candidates' own richer
content (`EvidenceRules`/`ConflictRules`/`AuthorityRules` in `EC_step023`; `standard` in `EC_kos`) is
ever source-stated as filling `EC_B`'s own empty `Rules` slot.

## `Sat` (all forms)

| ID | Source | Arity | Codomain | Computation rule | Relationship to anchor |
|---|---|---|---|---|---|
| `Sat_birth` | `step-023` | 2 (`K,r_i`) | gloss only ("degree/status") | **none** — immediately used as a stipulated fiat value | anchor's own direct ancestor |
| `Sat_{T5}` | `[00-51]` | 2 | `{0,1}` | none — fiat | `SAME CONCEPT, REFINED` (codomain fixed to Boolean) |
| `Sat_{PartIII}` | Part III §3.14 | 2 | `𝒮_sat` (4-value) | none | `SAME CONCEPT, REFINED` |
| `Sat_{PartV}` | Part V §5.6 | **3** (`K,r,Γ`) | `𝕊_sat` (5-value) | none — claims to "formalize" Part III, changes arity/value-count | `SAME CONCEPT, REFINED`, undisclosed drift |
| ⭐ `Sat_{PartVI}` | Part VI `[Def 6.18]` | 3 | `𝕊_sat` (via `Det_r`) | **`Det_r(EvalReq(K,r,EC,Γ),EC)`** — a genuine attempt at a computation rule | anchor |
| `Sat_{T22}` | worked example, same day | 2 | `{Satisfied,Unsatisfied}` | none — fiat, reverts to `Sat_birth`'s own pattern | `SAME CONCEPT, REFINED` in name, but operationally identical to the birth pattern |
| `Sat_{kos}` | `kos/inquiry.py` | 3 (`K,r,E`) | `{True,False}` | **kind-dispatched, 5 branches** — a genuine, executable computation rule | `RELATED OBJECT` — different 3rd argument (`E` not `Γ`), no `EC` argument at all |
| `Sat_c` | `kos12/satc_spec.py` | 3 (`K,r;Γ`, semicolon) | `{⊤,⊥,U}` | **8 typed sub-predicates, `P_C..P_O`** — genuine, executable, adversarially tested | `RELATED OBJECT` — takes `Γ`, but no `EC` argument; notation itself (semicolon) marks it as deliberately distinct |

**Verdict for `Sat`**: `Sat_{PartVI}` is the *only* formulation in the entire corpus that attempts a
genuine computation rule composed from more primitive pieces rather than a fiat stipulation or a direct
kind-dispatch. `Sat_{kos}` and `Sat_c` both achieve computability, but neither takes `EC` as an argument
at all — a structural mismatch with `Sat_{PartVI}`'s own signature, not merely a naming difference.

## `Zero`

Already established (MD-078 §01/§04) as `RELATED OBJECT` family, domain-locally complete, arity/
second-argument-type varying by Part (`EC`/`CC`/`AC`/`PC`/`RC`/`RCog`/`FC`). No further matrix needed —
every `Zero_X` is a direct, mechanical instantiation of the *same* shape (`Zero_X(K,X)⟺Δ_X(K,X)=∅`)
over a domain-local contract type; this is the one part of the family where "relationship" is
uncontroversial: all `SAME CONCEPT, REFINED` (contract-type substitution), never `UNRELATED_HOMONYM`.

## `Det`/`Determination`

| ID | Source | Arity | Formulation | Relationship to anchor |
|---|---|---|---|---|
| ⭐ `Det_{PartIII}` | Part III Def 3.5, proven Thm 3.1 | 4 (`K,p,EC,Γ`) | `Det(K,p,EC,Γ)⟺∀r∈Req_p(EC,Γ):Sat(K,r)=Satisfied` | anchor |
| `Det_{Part21}` | Part 21 Def 21.4 | 4, same shape | near-identical restatement, `Req_q(EC,Γ)` | `SAME OBJECT` (independently re-derived, same rewrite, consistent) |
| `Det_{PartVII-a}` | Part VII §7.25 | 2, conditional (`p\|A`) | `Det(p\|A_1)≠Det(p)` | `RELATED OBJECT` — a conditioning-notation variant, not contradicting `Det_{PartIII}`, merely narrower |
| `Det_{PartVII-b}` | Part VII §7.38, same file as above | 4 | `Det(K_t,p,EC_t,Γ)` | `SAME OBJECT` as `Det_{PartIII}` |
| `Det_{PartXIII-a}` | Part 13 §13.52 | 3 (`K,p,EC`, drops Γ) | `Det(K,p,EC_1)=1` | `RELATED OBJECT` — arity drift within the same file as below |
| `Det_{PartXIII-b}` | Part 13 §13.57, same file | 2 (`K,p`) | `Det(K,p)=1` | `RELATED OBJECT` — a third arity in the same file |
| `Det_r` (tracked object) | Part VI §6.18 | different role entirely — a per-requirement determination **function**, not a proposition-level predicate | `𝒱×EC→𝕊_sat` | `UNRELATED_HOMONYM` relative to `Det_{PartIII}` — shares the root "Det" only |

**Verdict for `Det`**: `Det_{PartIII}`/`Det_{Part21}` form a genuinely robust, twice-independently-
derived, proven core. **`Det_r` is not a variant of this object at all** — it is a different object
sharing a name-root, confirmed by its different argument structure (per-requirement, not per-
proposition) and different role (a policy function, not a universally-quantified predicate). This is a
finding this phase adds to MD-078: `Det_r`'s own naming invites conflation with the much more stable
`Det(K,p,EC,Γ)` family, but the two must never be merged.

## `Decision`

Already established (MD-078 §04) as `B — complete through multiple sources, weakly`: one rich,
never-reused 11-field tuple (Part 16 §16.4) plus the separation principle (`A`, independently proven
≥4 times). No composition role in this audit — `Decision` sits downstream of `Determination`, outside
the `Sat`/`EvalReq`/`Det_r` composition chain itself.
