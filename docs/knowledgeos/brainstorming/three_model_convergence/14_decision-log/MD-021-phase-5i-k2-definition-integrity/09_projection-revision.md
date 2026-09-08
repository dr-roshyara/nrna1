# Phase 5I — Projection Revision (multiple K-2 targets, per the authorization's §10)

Since K-2 is classified `COMPETING OBJECT DEFINITIONS` (`08`), the K-1 → K-2 projection is now
represented as **two projections**, one per competing `Assertion` characterization:

$$\pi_1: S_1 \rightarrow S_{2}^{(D1/D3)} \qquad \pi_2: S_1 \rightarrow S_{2}^{(D2/D4)}$$

where $S_2^{(D1/D3)}$ carries `{Proposition,Entity,Evidence,Context,Time,Provenance}` and
$S_2^{(D2/D4)}$ carries `{Proposition,Entity,Observation,id,c,t,Π}`.

## Per-projection specification

| | $\pi_1$ (D1/D3 target) | $\pi_2$ (D2/D4 target) |
|---|---|---|
| `Entity` | Preserved | Preserved |
| `State` | Undefined target ("the carrier," `06` of Phase 5H) | Undefined target (same) |
| `Event` | Dropped | Dropped |
| `Observation` | **NOT a target field at all — Observation has no home in $S_2^{(D1/D3)}$** | Preserved directly as a named field |
| `Proposition` | Preserved | Preserved |
| `Relation` | Preserved | Preserved |
| `Policy` | Dropped (by `t285_reconcile.py`'s own `VERIF_EXTERNAL`) | Ambiguous — `Π` is glossed as "Policy" in prose but used as provenance-shaped data in code (`06`, discrepancy 5) |
| `Action` | Dropped | Dropped |

## Are $\pi_1$ and $\pi_2$ equivalent, compatible, competing, incomparable, or unresolved?

**Competing.** They disagree on where — or whether — `Observation` lands, and on what `Policy`/`Π`
denotes. **This is not merely a notational difference**: $\pi_1$ has no image at all for `Observation`
(it isn't in the target field set), while $\pi_2$ maps it directly. A projection to $S_2^{(D2/D4)}$
(the reading Phase 5F/5G/5H implicitly used) **cannot be generalized to $S_2^{(D1/D3)}$** — exactly
the caution the authorization's §10 requires.

## Consequence for every prior phase's own K-1↔K-2 findings

Phase 5F/5G/5H's own equivalence work (structural FALSE / semantic TRUE-after-unpacking /
observational FALSE) was performed **against $\pi_2$ specifically** (D285-6's own version, which is
what `t285_equality.py` tests). **This phase does not retract that finding** — it remains correct *for
$\pi_2$*. But it is **not shown to hold for $\pi_1$**, and this reconstruction has, until this phase,
never distinguished the two. This is disclosed as a scope-narrowing of every prior phase's own K-1↔K-2
semantic-equality work, not a reversal of it.
