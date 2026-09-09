# MD-065 §02 — Q1: F4 Semantic Type Reconstruction

Source: M0043/M0047/M0048/M0125/M0126/M0132 (MD-057–064's own established work, reused by reference),
plus the newly-verified (this phase) earlier lineage below.

| Type | Definition | Status |
|---|---|---|
| **Requirement (`r`)** | `r=(id,type,scope,content,standard,priority,validity)` (M0047 `[DEF]`) — a member of `Req(EC_t)` | **EVIDENCED (shape)**; `standard`'s own evaluation rule **UNRESOLVED** (MD-063/064) |
| **`K_t`** | `K_t∈𝕂`, deliberately abstract (M0043 `[DEF-11]`); 12 competing concrete variants exist (MD-060), one (`V7`/`Σ_t`) partially typed | **EVIDENCED (abstract shape)**; concrete typing **PARTIAL** |
| **`EC_t`** | `EC_t=EC(S_t,G_t,Q_t,C_t)`, purpose-relative (M0043 `[DEF-19]`) | **EVIDENCED (shape)**; its own mapping to acceptance conditions **UNWITNESSED** (MD-063/064) |
| **Satisfaction (`Sat`)** | `Sat(K_t,r)∈\{0,1\}`, role in `𝕀(EC_t)`/`Adequate`/`Δ_t` (M0043) | **EVIDENCED (shape)**; body **OPEN** |
| **Gap (`Δ_t`)** | `Δ_t=\{r∈Req(EC_t):¬Sat(K_t,r)\}`, frozen (M0132) | **EVIDENCED (shape)**; computable body **OPEN** except for the narrow `Δ_t^Σ` slice (MD-061) |

## Newly-verified, earlier lineage — disclosed, not adopted as bridge evidence this phase

**`Step-013`** (`20260827-153702_step-013-sufficiency-completeness-and-readiness-for-purpose.md`,
2026-08-27 — genuinely primary, verified this phase): `q=(Target,Condition,MinimumEpistemicState,
Context,Criticality)`, `Satisfies(K,q)≠Satisfied`, `RequirementCondition_ρ(K_t,q)`.

**`Step-023`** (`20260827-162545_step-023-...knowledge-boundary.md`, same date — genuinely primary,
verified this phase): `EC=(Purpose,Requirements,EvidenceRules,UncertaintyLimits,ConflictRules,
TemporalRules,AuthorityRules)`, delivering "the EpistemicContract domain object," `R(P)-
SatisfiedRequirements(K)`.

**This is a real, earlier (predating M0043 by ~5 days), richer requirement/`EC` formulation, genuinely
verified in this phase** — but **not chased into a full reconstruction here**, per the user's own
explicit redirect toward F3↔F4 specifically. Named for a future, separately-authorized phase.
`RequirementCondition_ρ(K_t,q)` in particular is worth flagging: it is closer in shape to a genuine
evaluation rule than anything found in M0043's own later, more abstract apparatus — a lead, not a
finding, this phase.

## Bearing on this phase

**No F3-shaped type (`atom`, `Reach`, `Observe`/`Relate`/etc.) appears anywhere in the F4 source base
searched, including the newly-verified Step-013/023 material** — confirmed by a fresh grep this phase
(`00`).
