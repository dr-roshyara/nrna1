# Resolution Status

## Final verdict

**E — PARTIAL CONVERGENCE.**

*"Some contract components converge, but material components remain unresolved."*

## Why E, and not the other six

- **Not A (narrative closes the gap, sufficiently for a future controlled test)** — too strong. The
  single most consequential field (input carriers) genuinely is `CLOSED BY SOURCE` (`08`), but a
  controlled composition test would additionally need: (i) a resolution of which of the two tested
  derivation rules (baseline vs. `V6`) is authoritative — `06` is silent on this entirely (`07`); and
  (ii) preconditions/postconditions/failure semantics, none of which `06` states (`08`). "Sufficiently
  for a future test" overstates what was actually closed.
- **Not B (executable only)** — false; `03`/`08` establish the narrative source does independently
  state the rule, via a chain built entirely from narrative-lane text.
- **Not C (common-cause convergence) as the sole/primary verdict** — this is true and important as a
  *relationship* finding (`06`), but it answers a narrower question (why do the two sources agree)
  than the study's own central question (is the gap closed). Recorded as a qualifier to E, not chosen
  as the primary verdict, because "the relationship is common-cause" does not by itself say whether
  the specification content itself is now sufficient for anything — E covers both.
- **Not D (material disagreement)** — no disagreement was found between `06` and the executable
  baseline (`05`/`09`/`11`); the only tension found is `06`'s *silence* on `V6`, not a contradiction.
- **Not F (specification still insufficient)** — too strong in the other direction; the input-carrier
  field genuinely moved from `NOT SPECIFIED BY SOURCE` to `CLOSED BY SOURCE` (`08`) — calling the
  specification "still insufficient" without qualification would erase that real result.
- **Not G (provenance/admissibility block, as the primary verdict)** — this remains true for the
  *executable* lane specifically (unchanged from MD-030, `12` claim B), but `06` itself carries the
  *same* provenance tier as the two already-admitted files (`02`/`06`) — a provenance block is not the
  right primary characterization of `06`'s own relationship to the gap; it is the right
  characterization of the executable lane, already established and not revisited here as the main
  finding.

## What this verdict means, stated precisely

`06-composition-rules.md`, once chained through the already-admitted `04-operator-contracts.md`,
independently converges with the executable lane's own baseline rule on `Validate`'s input carriers
— that convergence is genuine at the content level but common-cause, not independent, at the
provenance level (`06`/`11`). Several other contract components (preconditions, postconditions,
failure semantics, and — critically — which of two tested derivation rules is authoritative) remain
unresolved by either source. This is a real, bounded, partial result — not a closure of the whole
`Validate` specification question, and not a basis for a composition test on its own.

## Required Final Statement — the smallest scientifically justified next action after MD-031

**Human admissibility decision for `06-composition-rules.md`** (a decision distinct from, and not
implied by, MD-028-DQ-1's own narrower admission of `03`/`04` — a new SESSION-LEVEL HUMAN
RESEARCH-GOVERNANCE DECISION, following that same precedent, would be required before `06` could be
used as evidence in any future study). This is the smallest action that would change the evidentiary
picture: it neither requires resolving the `V6` question first (that could be a named condition of
the admission, or a separate follow-on study), nor requires touching the executable lane at all
(unaffected by this finding, per `12`). A controlled composition retest remains a separate, further
action, appropriately deferred past any admission decision — consistent with the user's own stated
view that the chain should not skip from this study directly to a composition test even given a
strong textual match.
