---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-11-BOUNDED-KERNEL-READINESS, KSME-11-TRANSITION-SEMANTICS-AUDIT, KSME-11-OBSERVATION-CATALOG, KSME-11-TERM-DISCOVERY-REGISTRY]
derived_from: [all KSME-11 fork reports]
cross_track_dependency: none
---

# KSME-11 — Surviving Mathematical Path

$$
\text{source semantics}\rightarrow E_B\rightarrow \mathcal T_B\rightarrow \mathcal O_B\rightarrow
\text{transition semantics}\rightarrow \sim_B\rightarrow K_B
$$

| Arrow | Status | Evidence |
|---|---|---|
| source semantics → `E_B` | **Source-established (vocabulary), not derived (carrier)** | `K_t` = 8 ratified primitives, RATIFIED (`FA-4`), stable 2 full days. No concrete data structure over all 8 exists; only a 2-element toy projection (`(𝒜,ℛ)`) is executable. |
| `E_B` → `𝒯_B` | **Derived (schema), blocked (membership)** | Step 277's 6-op candidate + 22-op broader registry both real (typed signatures, classification schema closed); `step-291/07` proves mandatory membership has no derivation route — a governance decision, not a discoverable fact. |
| `𝒯_B` → `𝒪_B` | **Derived (partial), blocked (invention wall)** | 10 candidate observations, 0 permitted, root-caused to Step 261.21 (day-one self-aware gap). 3 of 8 primitives (Entity, Observation, Action) have zero candidates — closing requires invention, forbidden by this investigation's discipline. |
| `𝒪_B` → transition semantics | **Open, with a named directional lead** | Deterministic default (`257.10`) refuted on contradiction (`step-292/04`). Corpus's own repeated (3×, 3 weeks) repair proposal: partial function + `Contr` precondition gate (shape 2) — `SOURCE-CLAIMED`, not proven. Relational/nondeterministic shapes `NOT-ESTABLISHED`. |
| transition semantics → `∼_B` | **Definition stable; application blocked** | `∼_B`'s form is unaffected by boundedness itself. Undefined for a nondeterministic/relational `Obs(w(x))` — genuine, corpus-silent, open definitional question, not invented here. |
| `∼_B` → `K_B` | **Not yet computable** | Every upstream link carries an open or partial status; the one executed result (`t285_reconcile.py`) computes a projection, not a quotient. |

## What survives as genuinely solid, carried forward without qualification

- The 8 ratified primitives (`K_t`) — the strongest, most independently-reconfirmed fact in the entire
  KSME-04→11 arc; stable across 4+ independent checkpoints and 2 calendar days.
- The congruence criterion in functional form (`258.9`, `258.30`) — real, reusable, exact machinery,
  blocked only by missing inputs (`𝒯`, `δ`), not by any defect in the criterion itself.
- The `(𝒜,ℛ)=π_K(K_t)` projection result — executed, independently re-verified byte-identical.
- The methodology of constructing congruence counterexamples via a hidden-dimension-differing pair
  (`step-288/06`'s "`=` is not a congruence" result) — directly reusable for any future bounded test.

## What is a genuine architectural decision, not a discovery, and must be disclosed as such if adopted

- Adopting Step 277's `{Assert,Retract,Supersede,Merge,Split,LinkEvidence}` as `𝒯_B` — a choice, since the
  corpus itself proves no derivation closes membership.
- Adopting the `Contr`-gated partial-function shape for δ — the best-evidenced direction, but still
  unproven; adopting it for a bounded construction is a decision informed by evidence, not a recovered fact.
- Treating `Qualify` as an external input boundary — genuinely corpus-suggested (`G-97`/`Terminus`) but
  never adopted by the corpus itself, and shown not to resolve computability on its own (the `Φ`/`G-109`
  recurrence).

## Abandoned / out-of-scope branches (retained only to justify negative results)

- Relational/nondeterministic δ (shapes 3/4) — tested directly against the user's own hypothesis,
  not found, disfavored by the corpus's own stated repair direction.
- `KR-STATE-02`'s probabilistic transition system — a distinct, later, differently-motivated thread about
  epistemic uncertainty over `K_t` in general, not a δ-repair proposal; excluded from this chain to avoid
  conflating two different questions.
- The Σ-product-order construction (already excluded in KSME-10) — remains excluded.

## What this path does not do

No Kernel is selected, named, or ranked. `K_B` is not computed in this pass (computation was deferred
pending knowledge of what the corpus actually grounds — see `KSME-11-REPORT.md` for why). Firewall held
throughout, with two minor, fully-disclosed grep-preview near-misses (`three_model_convergence/M0275.yaml`,
`M0276.yaml`, `M0279.yaml` — filenames/lines only, never opened, no content used).
