# MD-079 — Controlled Composition Audit

## Purpose

User's mission, following review of MD-078: **do not authorize `SAT-OPERATIONAL-CLOSURE-v1` yet.**
MD-078 showed the `Det_r`/`EvalReq` chain sits alongside genuinely complete pieces (`Req`, the
aggregation logic, `Determination⇏Decision`), competing-but-individually-complete pieces (`EC`, `Sat`'s
own codomain), and two independently executable, adversarially-tested alternative constructions
(`kos/inquiry.py`'s `Sat`, `kos12`'s `Sat_c`/`Eval_c`). The corrected next scientific question is not
"shall we invent `Det_r`?" but:

> **Can the existing corpus-native definitions be composed into the T21-intended computation without
> introducing a new semantic object or an unjustified mapping?**

Ten-step method, supplied by the user and followed directly: (1–6) select the strongest corpus-native
candidate(s) for `Sat`, `Req`, `EC`, `r`, `standard`/acceptance, `Eval`/`Determination`; (7) attempt
composition without inventing mappings; (8) identify exactly where composition fails and why; (9) test
whether `kos/inquiry.py` or `Sat_c`/`Eval_c` supply a *demonstrated* mapping to T21's own objects or
merely a related construction; (10) only then determine whether `Det_r`/`EvalReq` is genuinely
necessary.

## Method

No new source reading performed — MD-078's own §§01–04 already establish the full evidentiary base
(every candidate object, its source, its exact formulation) via direct primary-source verification.
This phase is pure adjudication and composition-testing over that already-verified evidence, performed
by the main process directly (per the user's own instruction that final adjudication must remain with
the main researcher, agents being evidence-workers only — no agents dispatched this phase, since no new
evidence-gathering is required). Object-comparison matrices use the user's own requested column
schema: ID · Source · Type · Meaning · Inputs · Outputs · Context · Relationship.

## Central result, disclosed first

**Composition fails, and fails at a precise, principled location: the three interface objects `r`,
`EC`, and `Γ` are themselves under-determined or multiply-defined at exactly the points where
composition would need to pass through them.** Neither `kos/inquiry.py` nor `Sat_c`/`Eval_c` supplies a
source-stated mapping to T21's own `r`/`EC`/`Γ` — using either to fill T21's gap would require
*inventing* at least three separate correspondences, which this phase declines to do (per the mission's
own explicit prohibition). Both remain correctly classified `RELATED OBJECT, CONSTRUCTED CANDIDATE`,
not a demonstrated completion. See `02_composition-attempt-and-failure-points.md` and
`03_mapping-test-kos-and-satc.md` for the full attempt and its exact failure points.

**Consequence for necessity**: `Det_r`/`EvalReq` (or an equivalent, corpus-grounded replacement) is
genuinely necessary *if* the goal is to complete T21's own specific `Sat(K,r,Γ)` apparatus using T21's
own `r`/`EC`/`Γ` objects — no existing corpus-native or executable-research alternative closes that
specific gap without an invented mapping. It is *not* necessary if the goal is instead to adopt one of
the two working alternative systems wholesale, as a self-contained substitute rather than a patch — a
different, separately-authorizable choice. See `04_necessity-verdict-and-closure.md`.

## Artifact map

- `00_index.md` — this file.
- `01_object-comparison-matrices.md` — full ID/Source/Type/Meaning/Inputs/Outputs/Context/Relationship
  matrices for `r`, `Γ`, `EC`, `Sat`, `Zero`, `Det`, `Decision`.
- `02_composition-attempt-and-failure-points.md` — the actual attempted composition (steps 1–8),
  using the strongest candidate for each object, with the exact failure points named.
- `03_mapping-test-kos-and-satc.md` — step 9, the rigorous demonstrated-mapping-vs-related-construction
  test for both executable alternatives.
- `04_necessity-verdict-and-closure.md` — step 10, the necessity verdict, decision gate, verification,
  closure.

## What this phase does NOT do

Does not invent any mapping between competing `r`/`Γ`/`EC` formulations. Does not construct a new
`Det_r`/`EvalReq`/`Sat` body. Does not canonicalize any candidate. Does not authorize
`SAT-OPERATIONAL-CLOSURE-v1`. Does not perform F3↔F4 bridging. Does not modify any frozen artifact
(MD-024–078). Does not access `theory-extraction/`.
