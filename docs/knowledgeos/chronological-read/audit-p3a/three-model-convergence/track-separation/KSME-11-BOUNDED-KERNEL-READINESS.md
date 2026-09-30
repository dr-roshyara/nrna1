---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-10-GATE-LEDGER, KSME-10-SURVIVING-PATH, KSME-10-REPORT]
derived_from: [D285-1, D285-2, D285-6, D285-7, D288-SCOPE-DECISION-PROCEDURE-CLOSURE, 09/24/36/38-gap-update series, 10-GOVERNANCE-HANDOFF-STEP-285-RATIFICATION, 30-THE-EIGHT-PRIMITIVES, Step 257/258/260/261/277/278, step-288..292, reviewer-prompt lane 20260831/20260901, mathematical_ideas_that_can_be_implemented KR-STATE-02]
cross_track_dependency: none
---

# KSME-11 — Bounded Kernel Readiness Matrix

**Reframed research question (per the user's explicit pivot)**: not "can K*=H/≡_𝒯 be proven," but "what is
the smallest source-grounded executable bounded regime B=(E_B,𝒯_B,𝒪_B,C_B) that preserves behaviorally
relevant distinctions?" This matrix answers that question component by component.

**Firewall disclosure**: one broad grep (Fork C, "KR-STATE-02") surfaced two filename/line matches inside
the permanently-firewalled `three_model_convergence/` (`M0275.yaml`, `M0276.yaml`). Neither file was
opened; only the grep preview was visible. Disclosed per standing discipline; not treated as a violation.

| Component | Source evidence | Executable | Verified | Status | Missing link |
|---|---|---|---|---|---|
| **E_B (state)** | `K_t={Entity,State,Event,Observation,Proposition,Relation,Policy,Action}` — RATIFIED (`step-049`/C-022/FA-4, "50 attack classes, no counterexample"), independently reconfirmed 2026-09-01 and again 2026-09-01 22:19 ("still ZERO new primitives") | `t285_reconcile.py` (re-run, byte-identical) computes a *projection* `(𝒜,ℛ)=π_K(K_t)`, not `K_t` itself | Model C holds; A/B/F refuted | **PARTIAL** — vocabulary ratified, no carrier | No file anywhere constructs an actual data structure instantiating all 8 primitives together; only a toy 2-element projection exists |
| **𝒯_B (operations)** | 22 named operations (`step-291/03`), 8 typed; Step 277's `𝒯_candidate={Assert,Retract,Supersede,Merge,Split,LinkEvidence}` explicitly labeled non-minimal, forward-traced through Steps 278–280 without reversal | None have a semantic body | 0 of 22 executable | **PARTIAL (schema only)** | `step-291/07`: mandatory membership has **no derivation route** from ratified material (7 candidate routes tested, all circular/blocked/under-specified) — this is a **governance decision, not a discoverable fact** |
| **𝒪_B (observations)** | 10 candidates (`step-291/02`), 0 declared permitted; root cause traced to Step 261.21 (2026-08-30, same day as Steps 255/260) which asserts `𝒪_K` "closed" without ever enumerating it | 2 of 10 (`orphan`, `circular_dependency`) computable/total; `TraceOrigin`/`ExplainRevision` load-bearing but unregistered | `TraceOrigin`/`ExplainRevision` already used in an executed congruence counterexample | **PARTIAL/BLOCKED** | **3 of 8 primitives (Entity, Observation, Action) have zero candidate observations anywhere in the corpus** — closing this requires invention, which this investigation's discipline forbids |
| **C_B (command/context)** | No unified type found anywhere; every signature threads context as a per-operation-family ad-hoc parameter (`Merge_K(K1,K2,Ω,C,T,M)`, `Authorize:Actor×Action×Policy→Decision`) | n/a | n/a | **NOT GROUNDED** | C_B would have to be constructed from scratch across the 22-entry registry, not recovered |
| **Transition semantics** | Deterministic `E×C→E` is the corpus's own stated default (`257.10`, "pending contrary evidence") but explicitly REFUTED as universally sufficient (`step-292/04`, contradictory-effect inputs). The determinism question was posed explicitly twice in the reviewer-prompt lane (2026-08-31, 2026-09-01) and never answered until step-292's test 3 days later. The corpus's OWN proposed repair (`step-292/04`'s boxed `[PROP]`) is a **partial function gated by a separate pre-transition contradiction predicate `Contr`** — not relational, not nondeterministic | `t292_reiter_audit.py` (re-verified) | SSA candidate correct on non-contradictory inputs (12/12 regression≡progression), fails on contradictory ones | **OPEN, with a named directional lead** | The partial-function+`Contr`-gate shape is `SOURCE-CLAIMED`/`DERIVED`, not proven; relational (`E×C×E`) and nondeterministic (`E×C→𝒫(E)`) shapes are `NOT-ESTABLISHED` — a genuine, gated, unexecuted probabilistic proposal (`KR-STATE-02`, 2026-09-04) exists in a *different*, later thread about epistemic-state uncertainty generally, not about δ specifically |
| **Observation semantics** | Same as 𝒪_B row | — | — | **PARTIAL/BLOCKED** | Same |
| **Behavioral equivalence (∼_B)** | Definition form `∀w∈𝒯*:Obs(w(x))=Obs(w(y))` is unaffected by boundedness itself — no corpus material flags a finiteness-dependent problem | n/a | n/a | **DEFINITION STABLE, APPLICATION BLOCKED** | Undefined for a nondeterministic/relational `Obs(w(x))` — a real, corpus-silent, OPEN DEFINITIONAL QUESTION, not invented here |
| **Congruence** | Real, source-established machinery exists for functional transitions: `258.9` (`K1≡_K K2 ⟹ T(K1,c)≡_K T(K2,c)`), `258.30` (commuting diagram `F∘T_H=T̄∘F`); `step-290/05`'s own status line: "BLOCKED on 𝒯, δ" | n/a | n/a | **MACHINERY READY, INPUTS MISSING** | No relational-transition congruence analogue exists anywhere in the corpus — genuinely new work if the partial-function+`Contr` shape is adopted |
| **Quotient (K_B)** | Blocked upstream by every row above; the one executed result (`t285_reconcile.py`) computes a projection, not a quotient | Not computable | n/a | **NOT YET COMPUTABLE** | Depends on 𝒪_B closure (or an explicit disclosed partial version), a chosen 𝒯_B (governance decision), and a resolved transition semantics |
| **Minimality** | `D285-7`: blocked specifically because `𝒪` was never enumerated against the 8 primitives | n/a | n/a | **NEGATIVE-RESULT, sharpened** | Same 3-primitive gap as 𝒪_B |
| **Qualify (computability blocker)** | `G1`-irreducible (`D285-6`, triple-corroborated across the full Aug 31→Sep 1 thread). Boundary-reframing ("treat as external input") is genuinely corpus-supported (`G-97`, Cavell terminus lens) but **explicitly never adopted**, and does not resolve computability — it relocates the seam. A second, structurally identical irreducible gap (`Φ:Π_t→K_t`, `G-109`) was independently found downstream in the same thread, suggesting a **recurring pattern**, not a one-off | n/a | n/a | **OPEN, reframing available but unproven to help** | Whether moving `Qualify` outside the Kernel boundary actually closes the Kernel's remaining surface is `NOT-ESTABLISHED` |

## Overall readiness read

Two components are genuinely close to usable (E_B's vocabulary, the congruence machinery's *form*).
Two are governance decisions masquerading as research questions (T_B membership, the `Qualify`-boundary
adoption). One is a hard invention wall (𝒪_B for 3 of 8 primitives). One has a real but unproven
directional lead for the first time in the whole investigation (transition semantics → partial function +
`Contr` gate). None can be composed into an executable K_B today without either inventing content the
corpus doesn't have, or making explicit, disclosed governance decisions the corpus itself says only
governance — not derivation — can make.
