# MD-083 §01 — `document_08.md` Cross-Check: Duplicate vs. New

## Duplicate content (already logged; not re-logged here)

| Claim in `document_08.md` | Already established in |
|---|---|
| `EC_t→Req(EC_t)→r→Eval→EvalReq→Sat→Δ_t→Zero→Det→Decision→Authorization→Action` chain | MD-069 (T5 canonical source), MD-078 §01/§04 |
| `r=(id,type,scope,content,standard,priority,validity)`, "CORPUS-SUPPORTED, DEFINED" | MD-078 §01 (`[00-51]`) |
| `EC_t` two versions (4-field `[00-47]` vs. 6-field T21), "same lineage but non-identical" | MD-078 §01, MD-081 §03 (`EC` evolution ledger) |
| `standard` genuinely missing; `Policy_Det`'s own "the exact policy belongs to the epistemic contract" | MD-081 §01, MD-082 §01 (this reconstruction's own original finding — `document_08.md` is quoting it back) |
| `Eval:K×E×P×EC×Γ→𝒱`, `v=⟨Support,CounterSupport,...⟩` | MD-078 §01 |
| `Sat(K,r,Γ)=Det_r(EvalReq(K,r,EC,Γ),EC)` from `[Def 6.18]` | MD-078 §01, MD-082 §02 |
| `Δ_t={r∈Req(EC_t):¬Sat(K_t,r)}` | MD-069, MD-078 §01 |
| `Zero(K_t,EC_t)⟺Δ_t=∅`; `ZeroLens` as a distinct branch | MD-069, MD-078 §01 |
| `Det(K,p,EC,Γ)⟺∀r∈Req_p(EC,Γ):Sat(K,r)=Satisfied`, `Det⇒Δ_p=∅` theorem | MD-078 §01 (Part III Def 3.5/Thm 3.1); the *specific* Part VI internal restatement (Def 6.2/Thm 6.1) this document does not distinguish from Part III's own version is MD-082's own finding |
| `Det_r` = "where the real semantic responsibility problem moves"; `Policy_Det` "closest same-document candidate... illustrative template" | MD-081 §01, MD-082 §01 (near-verbatim restatement of this reconstruction's own language) |
| `Standing(p)` a real predecessor, "governance correctly did not adopt... as a substitute" | MD-080 (this reconstruction's own disclaimer, restated) |
| `Decision=f(Determination,DecisionRule/Policy)`, `Determination⇏Decision` | MD-078 §01/§04 |
| `Γ` proliferation "better classified as SUBDIVIDED, not simply four competing meanings" | MD-081 §02 (this reconstruction's own finding, near-verbatim) |
| Recommendation: adversarially review the existing `Sat` construction rather than invent `Sat_new`; GAP-004 | MD-070 (original finding), MD-076 (restated), cited by every subsequent phase |

**Assessment**: the substantial majority of `document_08.md`'s own content is a restatement — accurate
in shape, though frequently imprecise in a specific, consistent way (see below) — of material this
reconstruction had already established and published in its own governed decision-log before this
external session's own 2026-09-10 date. Per the user's own instruction, none of this is re-logged as
new evidence.

## A systematic imprecision, not adopted

`document_08.md` repeatedly restates "`X` is defined" as "`X` is derived" / "`X≠missing`" / "`X` is a
genuine derived mathematical object" — for `Eval` (zero invocations anywhere, MD-076/078) and for the
`Sat=Det_r∘EvalReq∘Eval` composition (a stated *formula*, MD-078/082, not a *computed value* — `Det_r`'s
own body remains, in the source's own words, "may be defined," never supplied). This is exactly the
conflation MD-082's own three-way discipline (object identity / semantic responsibility / computational
completeness) exists to prevent. **Not adopted** — this reconstruction's own three-way classifications
(MD-082 §03) stand unmodified.

## Genuinely new claim, checked and not corroborated

`document_08.md` §13 claims `Standing(p)`'s own branch established "`Σ={Unknown,Supported,Refuted}` as
a minimal candidate epistemic state... with Conflicted derivable rather than primitive" — this does not
match this reconstruction's own direct, already-verified reading of `M0127`
(`Standing(p)=(S⁺(p),S⁻(p),R(p),P(p),Ctx(p),Cond(p))`, MD-080).

**Verified directly this phase**: a corpus-wide search for a named `Σ={Unknown,Supported,Refuted}`
object found **zero matches anywhere**. The only genuine primary-source co-occurrence of "Supported" and
"Refuted" is `20260902-124810_simulation-one-proposer-two-evaluation-fields.md`, lines 206–211 — and
there they are two *cell values in a 2×2 worked-example matrix* (`𝓔=(Supported,Refuted)` across two
named contexts), not a formally named three-value epistemic-state space, and "Unknown" does not appear
alongside them in that file at all.

**Conclusion**: this specific claim is **not corroborated by any file in the corpus** and is not
adopted. It is recorded here, once, as a checked-and-refuted claim — not because it constitutes new
evidence, but because its refutation is itself a small, genuine, disclosable finding (a claim from an
external session traced to its purported source and found unsupported), consistent with this
reconstruction's own standing discipline of never accepting a claim about the corpus without direct
verification, regardless of the claim's own source or confidence.
