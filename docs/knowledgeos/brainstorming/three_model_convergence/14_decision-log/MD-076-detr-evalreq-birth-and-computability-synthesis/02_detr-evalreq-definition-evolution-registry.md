# MD-076 §02 — `Det_r`/`EvalReq` Definition Evolution Registry

Each row is a distinct historical version; none overwrites another. Both `Det_r` and `EvalReq` have
exactly **one** version each in the entire traversed corpus — recorded once, at birth, never revised.

## `EvalReq`

| Field | v1 (the only version) |
|---|---|
| Source | `[05-41]` (`20260906-003947_theory-part-06-evidence-evaluation-determination-calculus.md`, `[PVI]` §6.17) |
| Date/time | 2026-09-06 ~00:40 (T21) |
| Exact formulation | "For a requirement `r`, define `EvalReq(K,r,EC,Γ)`" — prose only; **no type signature, no codomain stated** |
| Provenance | Part of the Theory-00-21 21-part rewrite's own re-derivation of the F4 chain; no direct citation of `[00-47]` (the canonical T5 source) found anywhere in the rewrite |
| Role | Intended bridge between per-evidence evaluation (`Eval`) and per-requirement satisfaction determination (`Det_r`) |
| Status | `CORPUS-SUPPORTED, DEFINED (prose only)` — never invoked with concrete arguments anywhere in the corpus (`MD-073`/`074`, corpus-wide grep) |
| Change from previous | N/A — no predecessor exists. `App(r,Q_t,C_t,S_t,EC_t)` (T9, `[00-55]`) is a *candidate* predecessor by function (applicability gating), `RECONSTRUCTED` relation only, bridge `UNWITNESSED` (`MD-068` GAP-003) |
| Reason for change, if stated | N/A |
| Relationship to earlier versions | `RECONSTRUCTED` functional subsumption of `App`, never source-stated |
| **Semantic content actually supplied** | One worked *illustration*, for a structurally different requirement shape ("identity established by two independent authoritative sources": `SourceAuthority(e_1)`, `SourceAuthority(e_2)`, `Independent(e_1,e_2)`, `Supports(e_1,r)∧Supports(e_2,r)`) — does not generalize to single-evidence-object requirements (`MD-073`, confirmed for `r_1=PaymentConfirmed(S)`) |

## `Det_r`

| Field | v1 (the only version) |
|---|---|
| Source | `[05-41]`, `[PVI]` §6.18, `[Def 6.18]` |
| Date/time | 2026-09-06 ~00:40 (T21), same document as `EvalReq` v1 |
| Exact formulation | Type signature only: `Det_r : 𝒱 × EC → 𝕊_sat`. `𝕊_sat` never defined as a set — only member symbols (`Satisfied`, `Satisfied_strong`, `Satisfied_weak`) appear in use elsewhere in the corpus |
| Provenance | Same document/session as `EvalReq` v1; the pair is introduced together as the decisive closure of `Sat` |
| Role | Consumes `EvalReq`'s own (unstated-type) output plus `EC`, produces a satisfaction-status symbol |
| Status | `CORPUS-SUPPORTED, DEFINED (signature only)` — **no body, rule, or instance for any `r` exists anywhere in the corpus** (`MD-073`/`074`, corpus-wide grep, exactly one hit — the definitional statement itself) |
| Change from previous | N/A — no predecessor. `[05-41]` itself designs `Det_r` as an intentionally externally-supplied, per-contract parameter — disclosed, not an oversight (`[05-40]`'s own text, cited in `MD-068`'s GAP-001 closure) |
| Reason for change, if stated | N/A (no change; the disclosed design choice is stated at birth, not as a later revision) |
| Relationship to earlier versions | None — `Det_r` has no lineage; it is a genuinely new symbol at T21 |
| **Semantic content actually supplied** | None. Zero instances of `Det_r` being computed for any `r`, anywhere, including the theory's own flagship worked example (`MD-070` Finding 5; `MD-073`/`074`, confirmed independently twice, same case, same result) |

## Why only one version each

Unlike `K_t` (5+ versions), `EC_t` (4 versions), `Sat` (7 versions), `r` (2 versions), both `EvalReq`
and `Det_r` are born once, fully formed in their *typed* aspect, and never revisited — no later
document in the traversed corpus (queue lines 5123–5998, the 876-file F4-lineage population) proposes
a refinement, an alternative, or a computation for either. This is itself a finding: the corpus
treats the `EvalReq`/`Det_r` pair as *settled at the type level* the moment it is introduced, with no
subsequent research attention directed at supplying their missing bodies — in sharp contrast to `Sat`
itself, which received five distinct repair attempts across five days before `[05-41]`'s own
re-derivation (`T6`, `T9`, `T10`, `T12`, `T16`).
