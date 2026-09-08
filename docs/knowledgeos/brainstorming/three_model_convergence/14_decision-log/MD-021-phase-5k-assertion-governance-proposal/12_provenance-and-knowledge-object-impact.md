# Phase 5K — Provenance and Knowledge-Object Impact (per option, per the authorization's §9)

| Field | Option A | Option B | Option C | Option D | Option E |
|---|---|---|---|---|---|
| Proposal ID | `5K-OPT-A` | `5K-OPT-B` | `5K-OPT-C` | `5K-OPT-D` | `5K-OPT-E` |
| Originating evidence | D1/D3 | D2/D4 | seq 0630/0795/Step-272A (3 sources) | Synthesis of A+B+`id` | The full register (`01` of Phase 5J) |
| Affected objects | `Assertion`, K-2's own `π_1` projection | `Assertion`, K-2's own `π_2` projection | `Assertion`, `Qualify` (given an internal role) | `Assertion`, both projections | None — no object is changed |
| Assumptions | D1/D3 is more foundational than D2/D4 (unproven) | D2/D4 is more foundational than D1/D3 (unproven) | `Assertion`'s own field structure should encode a lifecycle (new architectural assumption) | The union is coherent (new assumption); `Π` may be safely dropped (new, disclosed assumption) | None beyond the status quo |
| Contradictions addressed | The D1-vs-D2 conflict, by elimination of D2 | The D1-vs-D2 conflict, by elimination of D1 | The D1-vs-D2 conflict, by union rather than elimination | The D1-vs-D2 conflict, by union; the `Π` conflict, by avoidance | None — all contradictions remain visible, by design |
| Contradictions remaining | `Qualify` arity/algorithm; `State`; `Π` (moot, since Option A never used it) | `Qualify` arity/algorithm; `State`; `Π` (unresolved, since Option B keeps it) | `Qualify` arity/algorithm; `State`; `Π` | `Qualify` arity/algorithm; `State` | All of them, explicitly, for all variants |
| Provenance | Traceable to D1/D3 exactly | Traceable to D2/D4 exactly | Traceable to 3 sources for the relationship, none for the specific field-structure proposal | Traceable to A+B+`id`, with one disclosed novel element | Traceable to the full register, unmodified |
| Mathematical consequences | `05` | `06` | `07` | `08` | `09` |
| Architectural consequences | `11` | `11` | `11` | `11` | `11` |
| Governance consequences | Requires a ratification act selecting A over B (and implicitly over C/D) | Requires a ratification act selecting B over A | Requires ratifying a genuinely new structural proposal | Requires ratifying the most novel proposal of the five | Requires only a lower-stakes acknowledgment act, not a selection |
| Migration implications | Any consumer currently assuming D2/D4's shape would need to change | Any consumer currently assuming D1/D3's shape would need to change | All consumers need to adapt to the new 2-field structure | All consumers need to adapt to the new union structure | No consumer is forced to change; but no consumer gets a single settled schema either |
| Reversibility | Reversible (a later governance act could re-open) | Reversible | Reversible | Reversible | Trivially reversible — nothing was committed |
| Risks | Silently discarding genuine content D2/D4 captured (Observation) | Silently discarding genuine content D1/D3 captured (Evidence/Context/Provenance as named concepts) | Introduces an unevidenced new invariant | Introduces the `Π`-avoidance judgment without independent verification | Indefinite deferral; eventual forced ad hoc choice at first point of actual use |
| Unresolved questions | `Qualify`, `State`, K-1..K-7's own remaining open items | Same | Same, plus whether the new invariant is itself correct | Same, plus whether `Π`'s avoidance is itself correct | All of the above, for both/all variants simultaneously |

## Discipline

**No historical definition is erased in any row above** — every option's own "Provenance" and
"Contradictions remaining" cells explicitly retain the full trace back to D1–D5, even where an option
proposes *selecting* one of them.
