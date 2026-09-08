# `K_t` Variant Analysis (GA-038)

## §1. Variant matrix — Model B (from `03_model-b_mathematical/02` §G, §A, `03` UE-1/UE-2; register-level, no reconstruction)

| Variant | Components | Purpose (as stated) | Representation | Evidence | Status |
|---|---|---|---|---|---|
| M0001 | 7-component | earliest formulation | tuple | `[HP]` | superseded |
| M0006 | 6-argument function | — | function | `[HP]` | superseded |
| M0009 | 6-component "atomic claim" | — | tuple | `[HP]` | superseded |
| M0043 | 33-definition apparatus (K_t embedded within) | axiomatic proof base | formal system | `[TH]`-adjacent, self-audited | DEVELOPING, "CONDITIONALLY VERIFIED" |
| M0048 | 5th variant | independent convergence via inconsistency-based reasoning | tuple | `[DF]` | DEVELOPING |
| M0076 | 6-component candidate | — | tuple | `[CG]` | superseded within its own arc |
| M0125/M0126 | 11-component, `K_t=(A_t,R_t,E_t,Σ_t,H_t,Z_t,L_t,T_t,G_t,C_t,M_t)` + 20 named "core invariants" | session-handoff continuity | tuple | `[CG]` | DEVELOPING, does not match any other formulation |
| 2 further variants (M0283–M0338 tail) | not individually enumerated in the register | — | — | `[CG]` | DEVELOPING |

**Full field-by-field content for M0001/M0006/M0009/M0076 and the M0283–338 tail is `NOT IN REGISTER`**
— the concept register summarizes rather than fully enumerates. This is named as missing structural
information, not reconstructed. The raw corpus almost certainly contains it (each variant is cited by
a specific `M00xx` seq number), but extracting and cross-tabulating every field of 9+ documents is a
separate, dedicated piece of work this study does not perform, per its own binding constraint against
reconstructing missing specification to enable a test.

**The one component that IS fully specified and frozen**: `Δ_t = {r ∈ R_t : Sat(K_t,r) = 0}` (M0132,
ratifying M0043/M0047) — see §3.

## §2. Variant matrix — Model C1 (from `04_model-c_kernel-ddd/02` §F)

| Variant | Components | Notes |
|---|---|---|
| seq 0446 (earliest) | not individually enumerated in register | first of the arc |
| seq 0461 | not enumerated | — |
| seq 0464 | not enumerated | — |
| seq 0469 | not enumerated | — |
| seq 0479/0480/0481/0486 | not enumerated | each self-caught-circularity-repair points |
| Δ_t (renamed from "Distance" after a non-symmetry proof, seq 0485) | — | "Discrepancy," not frozen within C1's own evidence |

Same limitation as Model B: **full component lists are `NOT IN REGISTER`**; not reconstructed here.

## §3. Model A — Knowledge Vector family (from `02_model-a_gita/02` §G, `03` UE-2)

9+ variants (0247, 0253–0262), component sets given at register level: `K=(R,M,I,P,E,B)` (0255),
`KS=(P,E,M,T,U,D,R,Z,τ)` (0259, 9-component), plus later additions of `WorldState`/`W` (0260/0261),
final form `KnowledgeState=(Method,Time,Logic,Epistemology,WorldModel,TerminalState)` (0262, 6-component).
**Fuller field lists for the intermediate variants are `NOT IN REGISTER`.**

## §4. Common intersection — description-level, not structural

Across all three families, the recurring *described* roles are: a knowledge-content slot (A's `I`/`M`;
B's various `A_t`/atomic-claim fields; C1's own content field), a temporal/index slot (A's `T`/`τ`; B's
implicit time-indexing `_t` subscript itself; C1's same `_t` subscript), and a gap/discrepancy slot
(A has none explicitly named; B's `Δ_t`; C1's own `Δ_t`, independently named). **This is an intersection
of descriptions, not of formally equivalent structures** — per the authorization's own required
distinction (§6.2). No two of these slots across models are shown to obey the same formal properties;
"a temporal index exists" is true of nearly any time-indexed formal system and carries essentially no
discriminating evidentiary weight.

**The one genuinely structural intersection**: B's and C1's own `Δ_t` both independently arise from a
correction to an earlier, symmetric-seeming "distance" framing (B: `Δ_t` replaces a rejected `I_t−K_t`
formulation, M0038; C1: `Δ_t` is explicitly renamed from "Distance" after a proven non-symmetry, seq
0485). **Both corrections are for the same underlying mathematical reason — a difference-like quantity
between "where the system is" and "where it should be" is not symmetric, so a subtraction/distance
framing is inappropriate.** This is the strongest structural (not merely notational) finding this study
produces: two independent lineages independently discovered, and independently corrected, the same
mathematical error.

## §5. Is a single canonical structure justified from this evidence?

**No.** Beyond the `Δ_t` non-symmetry correction (§4), no formal property is shown common to a
majority of the variants; several are explicitly built for different sub-purposes (M0043's for
axiomatic proof; M0125/126's for session-handoff continuity — B's own OQ-10 already names this).
**Full continuation of the canonicalization question is in `04_canonicalization-criteria-analysis.md`.**
