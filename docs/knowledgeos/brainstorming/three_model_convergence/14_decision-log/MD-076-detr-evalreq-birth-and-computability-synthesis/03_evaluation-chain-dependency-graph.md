# MD-076 §03 — Evaluation-Chain Dependency Graph

The mission's own candidate pipeline, tested against the corpus rather than assumed:

```
EC_t
  ↓
Req(EC_t)
  ↓
r
  ↓
Eval / Eval_c / EvalReq
  ↓
standard
  ↓
Det_r
  ↓
Sat
  ↓
Δ_t
```

## Per-arrow classification

| Arrow | Classification | Evidence |
|---|---|---|
| `EC_t → Req(EC_t)` | **source-stated** | `[00-47]` `[DEF-19]`/`[DEF-21]`, T5 — `Req(EC_t)` is directly a function of `EC_t` in the canonical source |
| `Req(EC_t) → r` | **source-stated** | `r∈Req(EC_t)`, T5/T7/T19 — `r` is an element of the set `Req` produces, consistently across every version |
| `r → Eval` | **contradicted (as drawn)** | `[05-41]`'s own signature is `Eval:K×E×P×EC×Γ→𝒱` — `Eval` takes evidence `E` and a proposition `P`, not `r` directly. `MD-074`'s own finding: **no corpus statement composes `Eval` into `EvalReq` at all** — the mission's own drawn arrow `r→Eval→EvalReq` is not source-stated in either segment |
| `r → EvalReq` | **source-stated (as an argument), but EvalReq's own codomain is unstated** | `EvalReq(K,r,EC,Γ)` — `r` is directly the second argument (`[05-41]` §6.17) |
| `EvalReq → standard` | **unwitnessed** | No document composes `EvalReq`'s output with `standard`. `standard` was relocated into `EC.Rules` at T19 and is consulted (per `MD-068`'s own GAP-001 closure) only through the abstract, undefined `Det_r` — not through `EvalReq` |
| `standard → Det_r` | **reconstructed** | `MD-068`'s own GAP-001 closure: `standard`'s successor role is "consulted via an abstract `Det_r`" — a reconstructed relation, not a source-stated one; the source's own words describe `Det_r` as consuming `EC.Rules` generally, not `standard` specifically by name |
| `Det_r → Sat` | **source-stated** | `Sat(K,r,Γ)=Det_r(EvalReq(K,r,EC,Γ),EC)`, `[Def 6.18]` — direct, explicit composition |
| `Sat → Δ_t` | **source-stated, but only for the 2-argument `Sat`** | `Δ_t={r∈Req(EC_t):¬Sat(K_t,r)}` (`[00-47]` `[DEF-21]`; `[PII]` §2.30) is defined over `Sat(K,r)`, the **2-argument** form. **No definition of `Δ_t`/`Zero` over the 3-argument `Sat(K,r,Γ)` exists anywhere** (`MD-074`, Stage E) — the decisive typed chain's own output has no downstream consumer |

## The graph the corpus actually supports (not the mission's own drawn diagram)

```
EC_t ──source-stated──▶ Req(EC_t) ──source-stated──▶ r
                                                       │
                                    ┌──────────────────┼───────────────────┐
                            source-stated (arg)   unwitnessed          source-stated (arg)
                                    │                  │                   │
                                    ▼                  ▼                   ▼
                               EvalReq(K,r,EC,Γ)   [standard, relocated]  Det_r(·,EC)
                               — no codomain ──unwitnessed──▶ consulted via Det_r (reconstructed)
                                    │
                          (never composed — MD-074)
                                    │
                                    ▼
                               [no downstream use]

Det_r(EvalReq(...),EC) ──source-stated──▶ Sat(K,r,Γ)  [3-arg, decisive, ZERO consumers]

Sat(K,r) [2-arg, the ONLY form ever stipulated] ──source-stated──▶ Δ_t ──source-stated──▶ Zero
```

**The graph is disconnected at two points**: (1) `r→Eval` is never composed into `EvalReq` despite
the mission's own diagram assuming it; (2) the decisive 3-argument `Sat(K,r,Γ)=Det_r(EvalReq(...),EC)`
output is never wired into `Δ_t`/`Zero` — only the older, simpler 2-argument `Sat(K,r)` is, and that
form is never derived from `Det_r`/`EvalReq` either (it is always stipulated directly). **The typed
chain the mission asks about is not merely under-specified in its middle — it is operationally
disconnected from the rest of the theory at both its input and its output ends** (`MD-074`'s own
phrase, confirmed here as a structural graph property, not merely a narrative observation).
