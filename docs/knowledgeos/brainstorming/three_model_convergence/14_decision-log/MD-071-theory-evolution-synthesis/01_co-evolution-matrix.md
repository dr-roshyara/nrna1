# MD-071 §01 — Co-Evolution Matrix

Built entirely from MD-069's own 24 `TheoryState` snapshots (`MD-069-theory-state-time-series/
01_theory-state-time-series.md`) — no new source reading. For every turning point where two or more
objects changed together, this matrix classifies the connection per the mission's own four-way
vocabulary: **EXPLICITLY CONNECTED** (the source states the relationship directly, matching MD-069's
own `YES`), **RECONSTRUCTED CONNECTION** (this reconstruction infers it from adjacent evidence,
matching MD-069's own `RECONSTRUCTED`), **TEMPORALLY CO-OCCURRING ONLY** (born/changed in the same
document/session with no stated or inferable link between the specific pair), **UNRESOLVED** (a link
is plausible but the evidence is too thin even to reconstruct one). Single-object turning points
(T0, T6, T8, T16) are excluded — there is no pair to classify.

| T_n | Object A | Object B | Connection | Basis |
|---|---|---|---|---|
| T1 | `Zero` v1 | `K_t` v2 | **EXPLICITLY CONNECTED** | `Zero(K,G,EC)` written as a joint function over `K_t` directly |
| T1 | `Zero` v1 | `EC_t` v1 | **EXPLICITLY CONNECTED** | same joint function, `EC` is a direct argument |
| T1 | `K_t` v2 | `EC_t` v1 | **TEMPORALLY CO-OCCURRING ONLY** | both born same document; no direct link between them stated independent of `Zero` |
| T2 | `Req` v1/v2 | `Δ_t` v1 | **TEMPORALLY CO-OCCURRING ONLY** | both born same session, both reference `K_t` separately, no stated link between `Req` and `Δ_t` themselves yet |
| T3 | `EC_t` v2 | `Δ_t` (typed extension) | **RECONSTRUCTED CONNECTION** | typed gap classes are motivated by `EC_t`'s rule fields, not formally derived from them |
| T4 | `K_t` v4 | `Knows` v1 | **RECONSTRUCTED CONNECTION** | factivity framed as a property `K_t` must respect, `Knows` never made a formal type argument of `K_t` |
| T5 | `EC_t` v3 · `Req` v3 · `Sat` v3 · `Δ_t` v2 · `Zero` v2 · `K_t` v5 | (all pairs, mutual) | **EXPLICITLY CONNECTED** | the single clearest multi-object co-birth in the corpus — `EC_t→Req(EC_t)→Sat(K_t,r)→Δ_t→Zero` stated as one connected structure, not five independent claims |
| T7 | `r` v1 | `Sat` v4 | **EXPLICITLY CONNECTED** | `Sat` explicitly defined over `r`'s own new internal structure |
| T7 | `Δ_t` v3 | `Sat` v4, `Req` | **EXPLICITLY CONNECTED** | stated dependency, source-explicit |
| T7 | `Zero` v3 | `Δ_t` v3 | **EXPLICITLY CONNECTED** | `Zero=⊥` stated within `Δ_t`'s own lattice extension, same formula |
| T9 | `Sat_c` v1 | `Sat` v4 (T7) | **RECONSTRUCTED CONNECTION** | both operate on `(K_t,r)`; no document states `Sat_c` is a version of `Sat` — treated as a distinct sibling object per MD-068's Theory Object Registry |
| T9 | `App` v1 | `Sat_c` v1 | **EXPLICITLY CONNECTED** | `App` `PRECEDES` `Sat_c`, source-explicit |
| T9 | `Δ_t^sem` | `App` v1 | **RECONSTRUCTED CONNECTION** | "applicability-filtered" language implies dependence on `App`'s gate; no formal dependency statement |
| T10 | `Sat_c` v3 | `Eval_c` v1 | **EXPLICITLY CONNECTED** | `Sat_c v3 := value∘Eval_c`, explicit ("if justified") |
| T11 | `ZeroLens` v1 | `Eval_c` v2 | **TEMPORALLY CO-OCCURRING ONLY** | same document window, no relationship between them stated — `ZeroLens`'s own link is to `Zero` v2 (T5), unrelated to `Eval_c`'s independent parallel re-arrival |
| T12 | `Sat` v5 (retired) | `Eval_content` (new) | **EXPLICITLY CONNECTED** | `Eval_content` is introduced as part of `Sat` v5's own explicit replacement pipeline, same retirement document |
| T13 | `Δ_t` | `Zero` | `Sat` | **EXPLICITLY CONNECTED** (all three) | joint verbatim restatement of T5's own formulas, explicit self-identification as "the fundamental definition" |
| T14 | `ℛ_req` v1 | `Adequate` (this branch) | `ABK-1` | **EXPLICITLY CONNECTED** (within branch) | one kernel architecture, ratified together at `[03-13]` |
| T15 | `Zero_{T,Π}` v1 | `Adequate` (3rd sense) | **EXPLICITLY CONNECTED** | the falsification test (`KR-BRIDGE-01`) directly tests whether `Zero_{T,Π}` predicts this sense of adequacy — the hypothesis itself IS the stated link |
| T15 | `Zero_{T,Π}` v1 | `Realized` | **RECONSTRUCTED CONNECTION** | same branch, same test apparatus; `Realized`'s own specific role in the tested hypothesis is less explicitly spelled out than `Adequate`'s |
| T17 | `Zero_{T,Π}` (redef) | `Adequate` (new sense) | `Realized` (refined) | **EXPLICITLY CONNECTED** (all three) | restated together as one "Theory v1.2 FROZEN" set, `SAME_LINEAGE_AS` T15 |
| T18 | `EC_t/Req/Sat/Δ_t/Zero` (re-derived chain) | (all pairs, mutual) | **EXPLICITLY CONNECTED** | the T5 chain restated as one connected structure, `SAME_LINEAGE_AS` T5 |
| T18 | `Eval` v1 (new) | `Sat` (re-derived) | **TEMPORALLY CO-OCCURRING ONLY** | `Eval` born in the same document as the chain's re-derivation, but no dependency between `Eval` and `Sat` is stated until T21 |
| T19 | `EC_t` v4 · `Req` v4 · `r` v2 · `Sat` v6 · `Δ_t` v4-form · `Zero` | (sequential chain) | **EXPLICITLY CONNECTED** | "the single densest co-evolution event in the corpus after T5 itself" — sequential dependency chain matching the theory's own stated architecture |
| T20 | `Determination` v3 | `Sat` v6, `Req` v4 | **EXPLICITLY CONNECTED** | explicit and proved (`[THM 3.1]`) |
| T20 | `Eval` v4 | `Determination` v3 | **TEMPORALLY CO-OCCURRING ONLY** | both change in the same document; `Determination`'s own T20 definition text has no explicit `Eval` term — the link becomes explicit only at T21 via `EvalReq` |
| T21 | `Δ_t` v4 final | `Sat` v7 final | **EXPLICITLY CONNECTED** | `Δ_t`'s own final formula takes `Sat(K_t,r,Γ_t)` as a direct argument (`χ_EC_t(Sat(...))=0`) |
| T21 | `Sat` v7 | `EvalReq` v1, `EC_t` v4 | **EXPLICITLY CONNECTED** | explicit and proved |
| T21 | `EvalReq` v1 | `App` v1 (T9) | **RECONSTRUCTED CONNECTION** | functional subsumption confirmed by direct source verification of `Req(EC_t,Γ_t)`'s own "Definition 5.1"; the bridge itself is `UNWITNESSED` (MD-068 GAP-003) |
| T21 | `Eval` v5 | `EvalReq` v1 | **RECONSTRUCTED CONNECTION** | `EvalReq` plausibly packages `Eval`'s own `K×E×P×EC×Γ→𝒱` signature per-requirement, given naming/typing; not spelled out as an explicit construction step in the source text this reconstruction has read |
| T21 | `Determination` v4 | `Sat` v7 | **EXPLICITLY CONNECTED** | `[THM 6.1]`/`[THM 6.2]` prove properties of `Determination` directly in terms of `Sat` v7 |
| T22 | `Δ_t`/`Zero`/`Determination`/`Sat` (8 domain specializations) | T21 core chain | **EXPLICITLY CONNECTED** | "all specializations DEPEND_ON the T21 core chain — YES, explicit per-domain" |
| T22 | `Decision` (extended) | `Determination` v4 | **EXPLICITLY CONNECTED** (negative result) | `[THM 16.38]` directly, formally addresses the relationship and proves `Determination ⇏ UniqueDecision` — a proved non-dependency, still an explicit relationship, not an absence of one |
| T23 | late echo `Δ_Q(K_t)` (`[06-22]`–`[06-25]`) | `Adeq`/`Determine` (T5/T21 shape) | **RECONSTRUCTED CONNECTION** | independent re-derivation, `SAME_LINEAGE_AS`, no citation of either `[00-47]` or `[05-40]`/`[05-41]` found |

## Reading this matrix

The pattern is not uniform across the timeline: **T5, T7, T13, T18, T19, T21** — the canonical
co-birth and its two re-derivation events — carry the corpus's own **densest EXPLICITLY CONNECTED**
clusters, exactly where the theory's central chain is actually under active construction. The
**TEMPORALLY CO-OCCURRING ONLY** entries cluster specifically at the *introduction* of a new object
inside an otherwise-connected document (`K_t`/`EC_t` at T1, `Req`/`Δ_t` at T2, `Eval`/`Sat` at T18,
`Eval`/`Determination` at T20) — a genuine, evidenced finding: **new objects are consistently born
adjacent to the chain before being formally wired into it**, never wired in at the moment of their own
birth. This is itself a candidate structural observation about how this corpus does theory
construction, offered here as a pattern description, not asserted as a rule the corpus states about
itself.
