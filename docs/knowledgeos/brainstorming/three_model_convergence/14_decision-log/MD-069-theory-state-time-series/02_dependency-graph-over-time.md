# MD-069 §02 — Dependency Graph Over Time

Per the mission's own instruction (§12): the dependency graph is not assumed permanent. Three
genuinely distinct graph states are reconstructed below, each dated, each evidenced from the
`TheoryState` snapshots in `01_...md`. A graph state is recorded only where the corpus itself changes
which object depends on which — not merely where new domain instances are added without changing the
graph's own shape (T22's 8 domain specializations, for example, do not change the graph shape, only
populate it repeatedly — not re-recorded as a fourth state).

## Graph state 1 — as of T2/T3 (2026-09-01, ~21:16–23:59), pre-canonical

```
K_t ──uses──> Zero
EC_t ──(monolithic, no stated internal dependency)
Req ──(free-standing, not yet tied to EC_t by any explicit statement)
Δ_t ──uses──> K_t   (undifferentiated distance, not yet requirement-indexed)
```

Status: fragmentary — `Req` and `EC_t` are not yet connected; `Δ_t` does not yet depend on `Sat` (which
does not exist yet at this point).

## Graph state 2 — as of T5 (2026-09-02, 00:46), the canonical formalization

```
EC_t
  │ DEPENDS_ON (constructs)
  ▼
Req(EC_t)
  │
  ▼
r  (an element of Req(EC_t), body unspecified — first structured at T7)
  │
  ▼
Sat(K_t, r)  [also: Sat(K_t, EC_t), contract-wide — a second, unreconciled arity, same document]
  │
  ▼
Δ_t = {r∈Req(EC_t) : ¬Sat(K_t,r)}
  │
  ▼
Zero(K_t,EC_t) ⟺ Δ_t=∅ ⟺ K_t⊨EC_t
```

This shape is stable from T5 through T13 (2026-09-02 00:46 → 17:53) — refined in detail (T7's
structured `r`, T9/T10's `Sat_c`/`Eval_c` attempt to fill the `r→Sat` edge, T12's rejected/retired
FOL-entailment attempt at the same edge) but **not restructured** during this whole window. The one
edge that remains genuinely open throughout Graph state 2 is `r → Sat` — every attempt to fill it
(`Sat_c`/`Eval_c`, FOL entailment) is proposed and then falsified, retired, or left as an unrepaired
defect (per the T6/T9/T10/T12 record).

## Graph state 3 — as of T18–T21 (2026-09-06, 00:16–00:40), the Theory-00-21 re-derivation

```
EC_t  (v4, 6-field — a DIFFERENT structure than Graph-state-2's EC_t, GAP-002 unresolved)
  │
  ▼
Req(EC_t,Γ_t)   ← now explicitly context-parameterized, and — per MD-068's GAP-003 finding —
  │               its own generation function is defined to return ONLY already-applicable
  │               requirements by construction (functionally absorbing what Graph-state-2's
  │               separate App gate, T9, would have done as a discrete step)
  ▼
r  (v2, opaque — no longer separately structured; GAP-001 finding: the "standard"/acceptance-
  │  criterion concept relocates from r's own fields to EC's own Rules field)
  ▼
Eval(K,p,Γ,EC) → EvalReq(K,r,EC,Γ)   ← a NEW two-stage edge that Graph-state-2 never had; Eval is
  │                                     independently typed before being specialized to one r
  ▼
Sat(K,r,Γ) = Det_r(EvalReq(K,r,EC,Γ), EC)   ← THE decisive new edge: Sat now has a genuine
  │                                            computed body, expressed as a function of EvalReq
  │                                            and EC directly (Det_r's own internal computation
  │                                            remains a designed-open parameter, not a missing edge)
  ▼
Δ_t = {r∈I_t | χ_EC_t(Sat(K,r,Γ))=0}
  │
  ▼
Zero(K_t,EC_t,Γ_t) ⟺ Δ_t=∅
  │
  ▼
Determination(K,p,EC,Γ) ⟺ ∀r∈Req_p(EC,Γ), Sat(K,r)=Satisfied
  │
  ▼
Decision   ← PROVED NOT to be determined by Determination alone (THM 16.38); requires an
             additional, independently-supplied decision rule/contract — an edge the corpus
             explicitly proves does NOT exist as a direct dependency
```

## What genuinely changed between Graph state 2 and Graph state 3

1. **A new node pair (`Eval`, `EvalReq`) was inserted between `r` and `Sat`** — Graph state 2 had no
   such intermediate stage; `Sat` was a direct function of `(K_t,r)` (or `(K_t,EC_t)`).
2. **`r`'s own internal structure was removed** (its 7 fields, including `standard`) and the
   acceptance-criterion role was relocated one level up, into `EC`'s own `Rules` field, consulted by
   `Det_r`.
3. **`Req`'s own generation function absorbed the applicability-filtering role** that Graph state 2 had
   assigned to a separate, explicit `App` gate.
4. **The `EC_t → Decision` edge was explicitly proven NOT to be a direct dependency** (`[THM 16.38]`)
   — Graph state 2 never tested this; Graph state 3 actively disproves the naive assumption that
   satisfying every requirement automatically determines a decision.

**No corpus document states these four changes as deliberate architectural revisions of Graph state
2** — Graph state 3 is a fresh re-derivation (T18's own framing, "responding to a rescinded closure
record") that happens to arrive at a structurally different, more elaborated graph, not an explicit
edit of Graph state 2's own text. This is recorded, per the mission's own discipline, as
`RECONSTRUCTED`/`SAME_LINEAGE_AS`, never as `YES`/explicit revision.
