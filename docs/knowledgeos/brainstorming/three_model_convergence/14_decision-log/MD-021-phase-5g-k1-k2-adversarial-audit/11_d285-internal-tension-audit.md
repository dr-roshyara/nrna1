# Phase 5G — D285-1/D285-6 Internal Tension Audit (re-audited)

## The tension, re-confirmed

D285-1 §2: `Assertion` → `{Proposition, Entity, Evidence, Context, Time, Provenance}` (6 fields, no
`Observation`).
D285-6 §3: `Assertion` → `{Proposition, Entity, Observation}` + `{id, c, t, Π}` (7 fields total,
**including `Observation`**, naming `id/c/t/Π` rather than `Evidence/Context/Time/Provenance`).

## Testing the required questions (per the authorization's §16)

1. **Exact conflicting formulations**: confirmed distinct, re-verified verbatim this phase (`04`).
2. **Can they coexist?** Plausibly, under a reading where D285-1's list names *conceptual* fields and
   D285-6's list names a more *technical* field-encoding (`id`=identity, `c`=?, `t`=time, `Π`=policy) —
   but this reading is **this phase's own conjecture**, not something either document states.
3. **Does one supersede the other?** No supersession language was found in either document.
4. **Is one a scope-specific formulation?** Plausible (D285-6 is doing a formal equality test and may
   need a more technical encoding than D285-1's conceptual list) — again, conjecture, not evidenced.
5. **Are they contradictory?** **At face value, yes** — D285-1's list omits `Observation`, which
   D285-6 explicitly includes; if both lists are read as literal, complete enumerations of the same
   `Assertion` composite, they cannot both be correct as stated.
6. **Does the contradiction affect the K-1/K-2 equivalence claim?** **Yes, materially** — this is
   precisely why `06` downgrades "semantic equality TRUE" to "partial correspondence, unverified
   declaration": if the very definition of what `Assertion` unpacks to is unsettled *within the
   source package itself*, no downstream equality claim built on "after unpacking Assertion" can be
   asserted with full confidence.

## Verdict

**AUDIT FINDING — FROZEN SOURCE.** This is a genuine, disclosed internal inconsistency in the D285
package (specifically between D285-1 and D285-6), **not repaired** — recorded here as an
evidence-preserving finding. **This tension is not a minor curiosity**: it directly undermines the
strength of the "semantic equality" claim this whole K-1↔K-2 question turns on, which is exactly why
`06` treats that claim with more caution than Phase 5F did.
