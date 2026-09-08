# Phase 5H — Projection Reconstruction (required table, per the authorization's §11)

$$\pi_K: S_1 \rightarrow S_2, \quad S_1 = \{Entity, State, Event, Observation, Proposition, Relation,
Policy, Action\}$$

**K-2's own target is itself under-determined** (`07`) — this table therefore reports the mapping
against **whichever unpacking scheme each source cites**, rather than silently picking one.

| K-1 | K-2 target | Mapping | Total? | Computable? | Information loss | Evidence |
|---|---|---|---|---|---|---|
| Entity | Nested field inside `Assertion`'s `P` component (all 4 sources agree Entity is present, nested) | $Entity \mapsto E \text{ inside } P$ | Yes (for this component alone) | `NOT EVIDENCED` (not the named blocker; also not confirmed) | Structural depth lost, content preserved | D285-1/D285-6/both scripts |
| State | "the carrier" — **undefined target** (`06`) | `NOT EVIDENCED` | `NOT EVIDENCED` | `NOT EVIDENCED` | `NOT EVIDENCED` — cannot classify loss for an undefined target | D285-6 only; no code implements this mapping |
| Event | — | Explicitly dropped, declared external | n/a (excluded from domain of the effective map) | n/a | Deliberate exclusion (D1, `09`) | D285-1, both scripts (`VERIF_EXTERNAL` includes `Event`) |
| Observation | `e`, plausibly = Evidence-field of `Assertion` (`05`) | $Observation \mapsto e$, gated by `Qualify` | **No** — conditional on `Qualify` | **No** — `Qualify` has no computable body (`04`) | Computationally inaccessible (D4, `09`) | D285-6, seq 0630 §49.76, seq 0795 §170.4, `e_equality.py` |
| Proposition | Direct field of `Assertion` (all 4 sources agree) | $Proposition \mapsto P$ | Yes | `NOT EVIDENCED` | None claimed | D285-1/D285-6/both scripts |
| Relation | Direct top-level component of $K_2$ itself | $Relation \mapsto \mathcal{R}$ | Yes | `NOT EVIDENCED` | Role-weight asymmetry (`08` of Phase 5G) | D285-1 §1, `t285_reconcile.py` |
| Policy | — | Explicitly dropped, declared external | n/a | n/a | Deliberate exclusion (D1) | D285-1, `t285_reconcile.py`'s `VERIF_EXTERNAL` |
| Action | — | Explicitly dropped, declared external | n/a | n/a | Deliberate exclusion (D1) | D285-1, `t285_reconcile.py`'s `VERIF_EXTERNAL` |

## What changed from Phase 5G's own version of this table

- `State`'s row is now marked `NOT EVIDENCED` throughout, rather than carrying the under-specified
  label "the carrier" as if it were a real (if vague) mapping target — `06`'s own source-research-gap
  finding is applied consistently here.
- `Observation`'s row now cites 4 independent sources (up from 1 in Phase 5F/5G), strengthening the
  evidence for the mapping's *existence* while leaving its *computability* verdict unchanged
  (still non-computable).
- No cell is filled with an assumption; every "not evidenced" is stated as such, per the authorization's
  own explicit instruction (§11: "Do not fill unknown cells with assumptions").
