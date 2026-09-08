# Phase 5G — K-2 Mathematical Reconstruction (independent re-verification)

$$K_2 = (S_2, \mathcal{O}_2, I_2, C_2)$$

| Component | Value | Evidence |
|---|---|---|
| $S_2$ (carrier, surface) | $(\mathcal{A}, \mathcal{R})$ — Assertion, Relation | D285-1 §1, D285-6 §1 |
| $S_2$ (carrier, expanded — **two non-identical unpackings found**) | **D285-1's own unpacking**: `Assertion → {Proposition, Entity, Evidence, Context, Time, Provenance}` (6 fields). **D285-6's own unpacking**: `Assertion → {Proposition, Entity, Observation} + {id, c, t, Π}` (3 named + 4 further fields, *including `Observation`*, which D285-1's own list omits) | D285-1 §2 line 29; D285-6 §3 line 63 — **re-confirmed as genuinely different lists this phase**, not a transcription artifact (both re-read verbatim) |
| $\mathcal{O}_2$ (operations) | $\mathcal{O}_{sem}$: 19 candidates, 5 families (Step 272A) — **explicitly not minimal** | D285-7; Step 272A file located, full content not re-read this phase |
| $I_2$ (invariants) | `StructuralValid` + 3 others, "derived, unratified" | D285-7 |
| $C_2$ (constraints) | **NOT EVIDENCED** beyond the invariant reference | — |

## Adversarial finding: treating "an expansion of a symbol" as "the whole source model" (per the authorization's §6 warning)

The authorization explicitly warns: *"Do not treat an expansion of a symbol as automatically
equivalent to the whole source model."* **This is directly relevant to D285-1/D285-6's own "semantic
equality" claim** (audited fully in `06`): unpacking `Assertion` into 6 (or 4+3) fields is an
**expansion of one symbol** ($\mathcal{A}$), not a demonstration that the *entire* $K_2$ model
($\mathcal{A}$ **and** $\mathcal{R}$, **and** the declared-external primitives, **and** the
operation/invariant sets) is equivalent to $K_1$. **Phase 5F's own artifacts did not make this
over-extension explicitly** — but the risk is worth flagging precisely: the "semantic equality"
result only concerns the *carrier* ($S_1$ vs. $S_2$, after unpacking), never the operation sets
($\mathcal{O}_1$ is `NOT EVIDENCED`, so no operation-level comparison was ever possible in the first
place) or the invariant sets. This is stated explicitly in `06`.

## Verdict

**K-2's carrier reconstruction is confirmed, but this audit surfaces one component previously
under-emphasized in Phase 5F**: the two source documents' own `Assertion`-unpacking lists genuinely
differ (this was noted in Phase 5F `10` as "an internal tension" but not carried through into the
mathematical-reconstruction file itself — corrected here, disclosed at the point where it actually
matters, the carrier reconstruction).
