# MD-067 §03 — Dependency Graph and Contradiction/Supersession Graph

## Output 4: Dependency graph (which semantic objects depend on which others)

Reading `[05-36]`–`[05-41]` (the decisive, most fully-typed source) as the authoritative dependency
structure, cross-checked against `[00-47]`'s own earlier, looser version:

```
EC_t  (independent — no dependency)
  │
  ▼
Req(EC_t)  DEPENDS_ON EC_t                          [00-47 DEF-19→21; 05-36 §19-20]
  │
  ▼
r  (an element of Req(EC_t))                        [00-51; 05-37 §2.26]
  │
  ├──────────────────────────────┐
  ▼                               ▼
Evidence, Context (Γ_t)       standard (a field of r, body never defined)
  │                               │
  ▼                               │
Eval(K,p,Γ,EC)  DEPENDS_ON        │
  K_t, Evidence, Context, EC_t    │
  [05-38 §3.33; 05-41 §6.15]      │
  │                               │
  ▼                               │
EvalReq(K,r,EC,Γ)  DEPENDS_ON     │
  Eval, r                         │
  [05-41 §6.17]                   │
  │                               │
  ▼ (standard, if defined, would gate this step — currently absent)
Sat(K,r,Γ) = Det_r(EvalReq(K,r,EC,Γ), EC)  DEPENDS_ON
  EvalReq, EC_t                             [05-41 Def 6.18 — THE decisive dependency]
  │
  ▼
Δ_t = {r∈Req(EC_t) : ¬Sat(K_t,r)}  (or χ_EC-projected form)  DEPENDS_ON
  Req(EC_t), Sat                            [00-47 DEF-21; 05-40 Def 5.2]
  │
  ▼
Zero(K_t,EC_t) ⟺ Δ_t=∅ ⟺ K_t⊨EC_t  DEPENDS_ON Δ_t         [00-47 DEF-22; 05-36 §23]
  │
  ▼
Det(K,p,EC,Γ) ⟺ ∀r∈Req_p(EC,Γ), Sat(K,r)=Satisfied  DEPENDS_ON
  Req(EC_t), Sat  (a per-proposition-p specialization, not identical to Zero which is
  contract-wide)                              [05-38 Def 3.5; 05-41 Def 6.2]
  │
  ▼
Decision  DEPENDS_ON Determination + an explicit Decision Rule/Policy (NOT determined by
  Determination alone — Theorem 16.38 PROVED)  [05-51 §16.38]
  │
  ▼
Authorization  DEPENDS_ON Decision + Policy Context  [05-57/05-58, worked example only —
  no independent formal definition found]
  │
  ▼
Action  DEPENDS_ON Authorization                     [05-57/05-58]
  │
  ▼
Outcome/new Observation  →  feeds K_{t+1} = δ(K_t, o_t, Γ_t)  [05-39 §4]
```

**App and Reason/Provenance/Condition are NOT part of this dependency chain as reconstructed from
the decisive `[05-41]` source** — they appear as fields/gates in earlier, non-superseding branches
(`[00-55]`'s `App`; the Contr/FDE branch's `Boundary=(Facet,Condition,Context,Provenance)`) but the
Theory-00-21 rewrite's own `Eval`/`EvalReq`/`Sat` pipeline does not reintroduce them by name. This is
recorded as a genuine gap in cross-branch integration, not as evidence they were rejected.

## Output 5: Contradiction/supersession graph

| # | Claim | Source | Contradicted/superseded by | Nature |
|---|---|---|---|---|
| 1 | `Zero ⟺ Δ_t=∅` (as the base Zero definition) | `[00-47]` DEF-22 | `[01-01]` retires it in favor of `ZeroLens(K_t,Γ_t,L_t)→Boundary_t`, **within that branch only** | Branch-local RETIRES — the `[05-36]`/`[05-40]` lineage never adopts the ZeroLens retirement and continues using `Δ_t=∅` directly |
| 2 | `Sat(K_t,r) ⟺ K_t⊨Content(r)` (FOL entailment) | `[02-22]` | `[02-24]` REJECTS ("we should NOT conclude Sat=Entailment"); `[02-26]` RETIRES formally, replaced by unspecified pipeline | Direct, explicit, same-day |
| 3 | `ASK ⟺ Sat(K_t,r)`, `TELL ⟺` adding to `ℛ_t` | `[02-37]` | `[02-39]`/`[02-40]` REJECTS both explicitly | Direct, explicit, same-day |
| 4 | `InstanceChecking = Sat` (DL Handbook) | `[02-27]` | `[02-28]`/`[02-29]` REJECTS, narrows to a weaker candidate | Direct, explicit, same-day |
| 5 | `Sat = truth-in-situation` (Reiter/situation-calculus) | (antecedent, not in traversal) | `[02-31]` REJECTS explicitly, "Sat_c ≠ Truth" | Direct, explicit |
| 6 | `Zero requires all Tier-1 ℛ_req distinctions` (unrelated `ℛ_req` sense) | `[02-53]`/`[02-54]` | `[02-52]`/`[02-55]` REJECTS, downgrades ratification back to candidate | Direct, explicit, same-day, WITHIN the unrelated-homonym branch |
| 7 | `Kernel = Yoni` / `Kernel = Linga` | (antecedent) | `[01-30]`/`[01-36]`/`[01-37]` REJECTS as "too strong," retained only as unratified research hypothesis | Direct, explicit |
| 8 | `Offspring = Knowledge`, `Offspring = Truth`, `Reconciliation = Orgasm` | `[01-17]` region | `[01-30]`/`[01-36]`/`[01-37]` REJECTS all three | Direct, explicit |
| 9 | `K_1≡_sem K_2 ⟺ ‖K_1−K_2‖<ε` (Hilbert-space semantic equivalence) | `[01-53]` | `[01-60]` REFUTES with concrete witness (sorites/transitivity failure), same failure mode as FR-001 | Direct, explicit, empirically tested |
| 10 | `H_i~_Λ H_j` is an equivalence relation (candidate `N_eff` definition) | `[01-45]` | `[01-48]` REFUTES — not transitive, concrete 12-hypothesis counterexample | Direct, explicit, empirically tested |
| 11 | `Zero_{T,Π}` predicts representation-Adequacy boundary | `[03-43]`/`[03-45]` (hypothesis) | `[03-48]` finds no association; `[03-60]` (KR-BRIDGE-01) definitively REJECTS both directions of implication via a 4-cell contingency table | Direct, empirically tested, definitive within its own (unrelated-homonym) branch |
| 12 | Theory-00-21's central `Sat(K,r,Γ)=Det_r(EvalReq(K,r,EC,Γ),EC)` definition | `[05-41]` | **No contradiction, rejection, or supersession found anywhere in the remaining ~114 traversed positions.** Also no ratification/adversarial-review event found. | **Absence of both** — the single most consequential gap this graph identifies |
| 13 | `Δ_t` as transition-residue (state-difference sense) | `[04-27]`/`[04-28]` | Never reconciled with, nor explicitly contradicted by, `[00-47]`'s Sat-gap sense — both senses persist unreconciled through the end of the traversal | UNRELATED_HOMONYM, not a contradiction (the two `Δ_t` are simply different objects sharing a symbol) |
| 14 | `ℛ_req` (Required-Distinction-Universe) vs. `Req(EC_t)` (requirement set) | Multiple, throughout batches 02–03 | Never confused within any single document (each branch is internally consistent), but the bare symbol collision is never flagged or resolved corpus-wide | UNRELATED_HOMONYM — already tracked as `EKS-41` (filed in MD-066) |

## What the contradiction graph shows, in one sentence

Every other major closure/ratification claim encountered in this 876-file traversal was contradicted,
refuted, or downgraded within the same research session or the next — **except** the Theory-00-21
rewrite's own central `Sat` definition, which is neither contradicted nor ratified; it simply stops
being engaged with. This absence is itself the graph's most load-bearing finding and is treated as
such in `04_current-semantic-state-and-final-determination.md`.
