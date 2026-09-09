# EKS-45 — Two independently-reconstructed `Δ_t` freeze events cite the same source but were never
directly compared

## Problem, in business language

Two entirely separate reconstruction efforts inside this same project — Phase 2 of the
`three_model_convergence` programme (Model B, the mathematics/statistics lane, built in 2026-09-07)
and MD-057–070's own F4 chronological `TheoryState` reconstruction (built 2026-09-08–09) — each
independently found what looks like the same "the knowledge gap is frozen to one formula" event in
the corpus, cite what appear to be the same underlying source documents, and describe it with two
different-looking formulas. Nobody has actually opened both formulas side by side to check whether
they are the same claim written two ways, or two different claims that happen to look similar.

## What the two findings actually say

1. **Phase 2 / Model B** (`03_model-b_mathematical/02_concept-register.md` §A): "`Δ_t` is eventually
   **frozen** (M0132, ratifying M0043/M0047) as `Δ_t = {r ∈ R_t : Sat(K_t,r) = 0}`."
2. **MD-069's own T5** (`MD-069-theory-state-time-series/01_theory-state-time-series.md`,
   `[00-47]`, `[DEF-21]`): `Δ_t = {r ∈ Req(EC_t) : ¬Sat(K_t,r)}`.

Both cite `M0043`/`M0047` (or the equivalent `[00-47]`-numbered document in this reconstruction's own
ledger notation — the two numbering schemes have not been cross-mapped either). Both define `Δ_t` as
the set of requirements a knowledge state fails to satisfy. The visible differences — `R_t` vs.
`Req(EC_t)`, `Sat(K_t,r)=0` vs. `¬Sat(K_t,r)` — could be nothing (two paraphrases of a Boolean
predicate) or could hide a real difference (does `R_t` carry `EC_t`'s own contextual scoping, or is
it a broader, unscoped requirement set?).

## Why this matters

- If the two are the same event, this reconstruction's own T5 gains a second, independently-built
  corroborating witness — a genuinely useful strengthening this reconstruction has not yet claimed.
- If the two are different, treating them as "the same freeze" (which a future reader skimming both
  documents might reasonably do, given the shared source citation) would silently merge two distinct
  formal objects — exactly the class of error `EKS-41`/`EKS-36` already catalogue for this corpus.
- Neither Phase 2 nor MD-057–070 was built with the other in view (Phase 2 predates MD-057 by roughly
  a day and used a different methodology; MD-057–070 reused Phase 2's own artifacts only for the
  `MD-071` Cross-Lane Transfer Register, and only at the level of quoting Phase 2's own summary, not
  re-deriving it from source).

## Recommended resolution (not performed by this ticket)

Open `M0043`/`M0047` (or `[00-47]`) directly and check whether `R_t` (Phase 2's own notation) and
`Req(EC_t)` (this reconstruction's own notation) denote the same set in the source text, and whether
`Sat(K_t,r)=0` and `¬Sat(K_t,r)` are the identical predicate under the source's own type for `Sat`
at that point (`Sat(K_t,r)∈{0,1}`, per T7 — if so, they are formally identical, `=0` being simply
`¬` written arithmetically). This is a bounded, single-document verification task, not a new reading
programme.

## Discovery context

Found during MD-071 (KnowledgeOS Theory Evolution Reconstruction: 9-Artifact Synthesis Pass,
`three_model_convergence/14_decision-log/MD-071-theory-evolution-synthesis/`), while building the
Cross-Lane Transfer Register (`04_cross-lane-transfer-register.md`, Finding 2) by cross-referencing
Phase 6's own C1↔B correspondence-matrix row against MD-069's own T5.

## Evidence

- `03_model-b_mathematical/02_concept-register.md` §A (Model B's own citation of `M0132`
  ratifying `M0043`/`M0047`).
- `14_decision-log/MD-069-theory-state-time-series/01_theory-state-time-series.md` T5 (this
  reconstruction's own citation of `[00-47]`, `[DEF-21]`).
- `14_decision-log/MD-021-phase-6-cross-model-c1c2-extension/02_extended-correspondence-matrix.md`
  lines 118–167 (the C1↔B "State" row that surfaced the adjacent, but distinct, `K_t`/`Δ_t`
  cross-lane finding this ticket builds on).
