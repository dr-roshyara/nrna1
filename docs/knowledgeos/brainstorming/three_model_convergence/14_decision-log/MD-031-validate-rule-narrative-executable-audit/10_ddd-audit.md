# DDD Audit

For each named concept, both sources classified independently — a Python carrier is not promoted to
a DDD aggregate merely by capitalization, and a narrative term is not promoted to an entity merely by
appearing in prose.

| Concept | In `06` (narrative) | In `kr/carriers.py` (executable) | DDD reading |
|---|---|---|---|
| `Claim` | a carrier kind, produced only via the `entailment` atom; no identity, no lifecycle stated | identical role — a string constant naming a derivable kind | **Value object** (or, more precisely, a *type label* for a value-object-shaped artifact) — neither source gives it identity or mutable state |
| `Hypothesis` | a carrier kind, alternative producible-only-via-`content-generation` | identical role | **Value object** |
| `Evidence` | a carrier kind, produced from `Observation, Policy` via `evidential-qualification` | identical role | **Value object**, with an explicit upstream *policy dependency* — the closest either source comes to a domain-service-flavored concept (a policy is consulted to admit something as evidence) |
| `Verdict` | a carrier kind, produced from `{Claim,Evidence}`/`{Hypothesis,Evidence}` via `warrant-assessment` | identical role | **Derived value object** — matches MD-029's own P-3 `Confidence` classification (already noted as a structural echo in MD-030 `10`, not re-opened here) |
| `Validate` | named once, only rhetorically (line 53); never a subject of the derivation table itself | an `Op` object holding one atom, no I/O type of its own | **Not evidenced as a DDD command, aggregate, or domain service in either source** — in both, "Validate" names a *step* in a fixpoint composition, addressed by the atom it introduces, not by identity — the same finding MD-029's own `06_ddd-analysis.md` already reached for the narrative-only version of this question |
| `Qualify` | not named in `06`'s own table by operator name (its atom, `evidential-qualification`, appears in the `Evidence` row) | an `Op` object, same pattern as `Validate` | same reasoning as `Validate` |
| `Capability` (e.g. `C10`) | not discussed in `06` at all (that is `03`'s own subject, already admitted) | not discussed in `carriers.py` (that is `capabilities.py`'s subject) | out of this document's scope on both sides |

## Command binding — re-tested, not assumed

**Still not established.** `06`'s own composition mechanism (`Reach(S)`, lines 58–69) is, by its own
explicit design ("independent of operator names," matching `reach.py`'s own docstring exactly)
structurally incompatible with a DDD command-dispatch model, which requires name-addressed commands
routed to an aggregate. Both sources — independently, in their own words — describe a mechanism that
deliberately avoids that shape. This reinforces, rather than revisits, MD-029's and MD-030's own prior
findings on this point.

## Aggregate / bounded-context reading of `06` itself

Not evidenced. `06` is one member of a numbered document series with no stated ownership/authority
metadata (matching every other provenance finding already made about this lane in MD-026/027/030) —
this study adds no new DDD-governance finding beyond what those already established.
