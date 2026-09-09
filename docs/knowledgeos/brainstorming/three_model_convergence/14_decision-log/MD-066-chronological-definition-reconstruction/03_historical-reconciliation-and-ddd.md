# MD-066 §03 — Historical Reconciliation and DDD Relationship Map

## Output 8: Historical lineage / reconciliation

| Object | Earliest source | Later treatment | Relationship |
|---|---|---|---|
| `EC_t=(S_t,G_t,Q_t,C_t)`, `Req(EC_t)`, `Sat(K_t,r)`, `Δ_t=Gap(K_t,EC_t)` | M0043 (2026-09-02 00:46), frozen `[DEF-21]` per M0132 | M0051 (`Sat_c`/`App`, 09:35) — **extends**, adds an applicability layer, never conflicts | `Sat_c`/`App`: **completes** in part (adds structure `EC_t`'s own `[DEF]`s never specified), still `[PROP]` |
| `Sat(K_t,r)` (as a single function) | M0043 | M0136 (17:53, FOL-entailment candidate) → M0138/M0140 (18:00, explicit retirement) | **supersedes**: the single-`Sat` framing is explicitly abandoned in favor of a typed pipeline. This is not a competing definition of the same object — it is a decision to stop using that object shape at all. |
| `ℛ_req` / `Req(EC_t)` | M0043/M0047 | M0165/M0187 (18:20, "Required Distinction Universe") | **unrelated homonym**, not a lineage relationship — confirmed by content: different arity, different domain, no shared field, no cross-citation. Recorded as a naming collision (see §04), not reconciled as the same evolving object. |
| `δ(K_t,e_t)→K_{t+1}` | M0043 (unspecified) | M0140 (18:00, "research framework," situation-calculus candidate, explicitly not adopted) vs. M0187 (18:20, frozen `δ(K_t,o,Γ)`, `O_core` five-operator set, History-Preserving axiom) | **two independent, non-cross-citing candidate resolutions** for the same open M0043 term, arriving 20 minutes apart, neither citing the other. M0187's is frozen (Category A); M0140's is explicitly a research framework only. Given the timestamps and total absence of cross-reference, this reads as parallel, uncoordinated closure attempts within the same research session, not a single evolving thread. |
| Step-013/023 `q=(Target,Condition,MinimumEpistemicState,Context,Criticality)`, `EC=(Purpose,
  Requirements,EvidenceRules,UncertaintyLimits,ConflictRules,TemporalRules,AuthorityRules)`
  (2026-08-27, verified in MD-065) | earliest known `EC`/requirement lineage | Not cited by M0043, M0049, M0051, M0136–M0187 in anything read this phase | **Still an isolated, earlier precursor** — no file read in this phase or any prior MD closes the gap between the Step-013/023 seven-field `EC` and M0043's four-field `EC_t`. Unchanged from MD-065's own finding. |

**No canonicalization performed.** Where two candidate resolutions exist for the same M0043 term
(`δ`, most clearly), both are recorded, neither is declared correct.

## Output 9: DDD relationship map (corpus-evidenced only)

| Bounded context (as evidenced) | Owns | Never crosses into |
|---|---|---|
| **M0043/M0047's `EC_t`/`Req`/`Sat` context** | `EC_t`, `Req(EC_t)`, `Sat(K_t,r)` (until retired), `Δ_t=Gap(K_t,EC_t)` | The `ℛ_req(Q,Γ)`/`Distinctions(K)`/`Adequate(K,Q,Γ)` context (M0165/M0187) — zero shared vocabulary beyond the bare symbol `ℛ_req`, which denotes different objects in each. |
| **M0051's `Sat_c`/`App` context** | Eight requirement classes, three-valued `Sat_c`, `App(r,Q_t,C_t,S_t,EC_t)`, `Γ_t` | Does consume `EC_t` by name (unlike M0140's own pipeline) — the single instance found this phase where a `Sat`-adjacent apparatus actually takes `EC_t` as an argument. Not cited by M0136–M0187. |
| **M0136→M0140's typed-pipeline context** (`K_t^E`/`K_t^{I,S}`/`Cn_S`/`Eval_c`/Determination/
  Decision/`δ`) | The explicit/implicit knowledge split, the retirement of single-function `Sat`, `TELL`/`ASK`, `Explain`/abduction | `EC_t` is never named. `Sat_c`/`App` (M0051) never cited. This context reuses the bare symbol `Eval_c` from M0051 without citing it — either continuous unstated reuse or independent convergent naming; not adjudicated. |
| **M0165/M0187's ABK-1/`Contr` context** | `ℛ_req(Q,Γ)`, `Distinctions(K)`, `Adequate(K,Q,Γ)`, `EVal(K,p,Γ)`, `Det`, frozen `δ(K_t,o,Γ)`, `O_core` | Fully disjoint from M0043's context — no shared object beyond the homonymous bare symbol `ℛ_req`. This is the same "genuinely separate bounded contexts within the same broader research programme" finding MD-065 already made for F3↔F4, now found a second time, one level down, INSIDE F4 itself, between two of its own sub-threads. |

**No context mapping was found or constructed between the `EC_t`/`Sat` context and either the
`Sat_c`/`App` context's successor (the typed pipeline) or the `ℛ_req`/ABK-1 context.** The typed
pipeline (M0140) is the closest thing to a successor context for `EC_t`/`Sat`, and even it does not
consume `EC_t`.
