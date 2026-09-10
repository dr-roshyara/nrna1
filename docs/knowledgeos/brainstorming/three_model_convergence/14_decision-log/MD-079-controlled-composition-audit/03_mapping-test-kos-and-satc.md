# MD-079 §03 — Demonstrated-Mapping-vs-Related-Construction Test (Step 9)

Test applied to both `kos/inquiry.py`'s own `Sat(K,r,E)` and `kos12`'s own `Sat_c(K,r;Γ)`/
`Eval_c(K_t,r,Γ_t)`: **does any source, anywhere, explicitly state that this construction instantiates,
implements, or is intended to complete T21's own `Sat(K,r,Γ)=Det_r(EvalReq(K,r,EC,Γ),EC)`?** If yes:
`DEMONSTRATED MAPPING`. If the resemblance is structural/functional only, with no source-level
assertion of intended identity: `RELATED CONSTRUCTION`.

## `kos/inquiry.py`

| Interface point | T21's own requirement | `kos/inquiry.py`'s own offering | Gap |
|---|---|---|---|
| `r`'s own type | `r_B∈Req`, abstract, 11 possible concern-topics | `Requirement(id,prop,kind,note)`, `kind` a 5-value enum | no source states `kind`'s 5 values instantiate `r_B`'s 11 topics; **3 of `kind`'s 5 values (`unique`,`corroborated`,`causal`) have no obviously corresponding topic in `r_B`'s own list, and `r_B`'s own `authority`/`temporal validity`/`representation`/`transformation`/`decision readiness` topics have no corresponding `kind` value** |
| `EC`'s own type | `EC_B=⟨Req,Rules,Scope,ER,TR,AR⟩`, 6-field | `EpistemicContract(id,standard,requirements,attribution_policy)`, 4-field | no field-level correspondence stated anywhere; `EC_B.Rules` (the exact missing slot) has no counterpart offered — `EpistemicContract.standard` is a *different* dataclass (`EpistemicStandard`) with its own unrelated fields (`min_weight`,`require_independent_sources`,...) |
| `Γ`'s own role | `EvalReq(K,r,EC,Γ)` — `Γ` is a required 4th argument | `Sat(K,r,E=None)` — **no `Γ` argument at all**; `E` denotes evidence, not context | structural mismatch, not merely a naming difference — there is no slot in `kos/inquiry.py`'s own signature for `EvalReq`'s own `Γ` to occupy |
| `EC`'s own role in `Sat` itself | `Det_r`'s own signature takes `EC` directly (`𝒱×EC→𝕊_sat`) | `kos/inquiry.py`'s own `Sat(K,r,E)` takes **no `EC` argument at all** | same structural mismatch as `Γ` |

**Explicit citation search**: `kos/inquiry.py`'s own docstrings cite `§2.3-2.7`, `DEF-20/21/22` — this
reconstruction's own prior work (MD-067) already established these are `T5`'s own citation IDs, not
`T21`'s. **No comment, docstring, or sibling file anywhere in `research/knowledgeos-sim/kos/` names
`Det_r`, `EvalReq`, `Sat(K,r,Γ)`, or the T21 rewrite.** No source-level assertion of intended identity
exists.

**Verdict: `RELATED CONSTRUCTION`, not `DEMONSTRATED MAPPING`.** `kos/inquiry.py` is best read as an
independent, self-contained implementation of the `T5` lineage's own conceptual shape (contract-indexed
requirement satisfaction, `Gap`/`Zero`/`Adequate` citing `T5`'s own `DEF` IDs) — not an attempt to
complete `T21`'s own, structurally different apparatus. Using it to fill `T21`'s gap would require
inventing all four correspondences above.

## `kos12/satc_spec.py` + `evalc.py`

| Interface point | T21's own requirement | `Sat_c`/`Eval_c`'s own offering | Gap |
|---|---|---|---|
| `r`'s own type | `r_B∈Req` | `r` consumed by `P_c(r)`, the sub-predicate family `{P_C,P_E,P_P,P_S,P_Con,P_G,P_T,P_O}` — 8 named dimensions (Content/Evidence/Provenance/Status/Consistency/Governance/Temporal/Operational) | closer in *count* to `r_B`'s own 11 topics than `kos/inquiry.py`'s 5-value `kind`, but still no source-stated correspondence — 8≠11, and the two lists' own names only partially overlap (Provenance↔provenance; Temporal↔temporal validity; Governance has no clear `r_B` counterpart) |
| `EC`'s own role | required, both in `EvalReq`'s own argument list and `Det_r`'s own domain | **`Sat_c(K,r;Γ)`'s own top-level signature takes no `EC` argument at all** — `EC`'s role, if any, is implicit inside the `P_c(r)` predicate family, never made explicit | same structural absence found in `kos/inquiry.py` |
| `Γ`'s own role | 4th argument to `EvalReq`, absent from `Det_r` | **present**, as the semicolon-separated 3rd argument to `Sat_c` and the 3rd argument to `Eval_c` | closer structural match than `kos/inquiry.py` — but the semicolon notation (`Sat_c(K,r;Γ)` vs. `Sat(K,r,Γ)`) is itself a disclosed, deliberate notational distinction, not a typo |
| codomain | `𝕊_sat` (5-value, per `Sat_{PartV}`'s own likely referent) | `{⊤,⊥,U}` (3-value) | no stated correspondence between the two enumerations |

**Explicit citation search**: the agent's own direct read of `kos12/evalc.py` found the explicit
self-description **"`Eval_c` is the primary object; `Sat_c := value∘Eval_c` is a CANDIDATE PROJECTION,
tested not assumed"** — the source's own words already disclose non-canonical, candidate status. No
citation of `Det_r`, `EvalReq`, or the T21 rewrite anywhere in the module.

**Verdict: `RELATED CONSTRUCTION`, not `DEMONSTRATED MAPPING`** — closer in shape to `T21`'s own
apparatus than `kos/inquiry.py` (it does take a `Γ`-like third argument), but the source's own explicit
self-labeling ("candidate," "tested not assumed") is itself evidence against reading it as a completion
of `T21`'s own specific equation, and the missing `EC` argument remains a genuine structural gap on
either construction.

## Consolidated Step 9 result

**Neither executable alternative supplies a demonstrated mapping.** Both are genuine, valuable,
independently-tested pieces of evidence that computable satisfaction predicates *can* be built — which
is itself informative (it shows the abstract goal is achievable, just not via `T21`'s own specific
route as written) — but adopting either to fill `T21`'s own gap, rather than as a self-contained
alternative system in its own right, would require inventing correspondences this phase declines to
invent, per the mission's own explicit prohibition.
