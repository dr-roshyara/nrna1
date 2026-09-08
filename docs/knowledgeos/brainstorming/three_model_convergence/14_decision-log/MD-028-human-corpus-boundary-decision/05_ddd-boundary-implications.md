# DDD Boundary Implications

## The prohibited inference, stated explicitly

**"Admission → Model-B membership → bounded context → authority"** is not a valid chain. Each arrow
requires its own separate evidence:

- Admission (a governance act, if DQ-1 is answered YES) does **not** establish that `kernel-reduction/`
  was historically Model B's own output (that remains `RECONSTRUCTED PROVENANCE`, per `03`).
- Even if historical origin were established, that would **not** automatically make it a DDD bounded
  context relative to Model B (ownership/authority evidence would still be separately required, per
  MD-027 `03`'s own five-boundary distinction).
- Even a genuine bounded-context relationship would **not** automatically confer governance authority
  over anything beyond its own scope.

## What each option means in DDD terms

- **Option A/D**: no boundary changes; `kernel-reduction/` remains outside every DDD boundary this
  reconstruction recognizes.
- **Option B/C**: only the **evidence-admissibility boundary** (per MD-027 `03`'s own six-way
  distinction: filesystem / research-lane / model-membership / bounded-context / governance-authority /
  evidence-admissibility) moves. The other five remain exactly as MD-026/027 found them — in
  particular, **the model-membership and bounded-context boundaries do not move**, even under Option C.

## Why this distinction is worth stating even though it may seem pedantic

The whole MD-023→MD-027 sequence exists because earlier phases (Phase 3, Phase 6) established, at
real cost, that resemblance/naming/co-location must never be silently upgraded into identity or
membership. Admitting evidence under a narrow, explicitly-labeled purpose is the same discipline
applied to a governance act instead of a research finding — the DDD boundaries are kept separate
specifically so a future reader cannot point to "we admitted it" as if that settled "it belongs to
Model B."
