# grid-adequacy-amended-to-operational-with-anti-permissiveness

**Scope(s):** METHODOLOGICAL · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "exists theta: Y(theta) in [L,U]" · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0067, scope METHODOLOGICAL: "The formally recorded amendment replacing the strict span-width grid-adequacy rule with an operational gate-reachability criterion plus anti-permissiveness safeguards."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2812 §"The strict rule span(Y) >= width(gate) is not a generally meaningful adequacy requirement for a calibration grid. ... Requiring it to traverse the entire 0.50 interval confuses range coverage with gate reachability. ... AMENDMENT: §6.2 is replaced by the operational gate-reachability criterion. ... "]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2812 §"The strict rule span(Y) >= width(gate) is not a generally meaningful adequacy requirement for a calibration grid. ... Requiring it to traverse the entire 0.50 interval confuses range coverage with gate reachability. ... AMENDMENT: §6.2 is replaced by the operational gate-reachability criterion. ... "]

## Lifecycle
last_seen: S2812. Candidate lifecycle: ACTIVE. Evidence: retracted_by and superseded_by are both empty and no row is self-typed as a contradiction; this is a heuristic based on how recently (by source_id order) this label was last used (S2812), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2812] types=[CORRECTION, GOVERNANCE] scope=METHODOLOGICAL — "Resolves the self-flagged discrepancy by formally amending (not silently reinterpreting) the frozen rule: the strict span>=gate-width criterion is withdrawn as conceptually wrong (it tests interval traversal, not gate reachability -- a quantity bounded in [0,1] can be well-behaved and never span 0.50 of a 0.50-wide gate); replaced with an operational criterion (exists theta in the grid such that Y(theta) is in [L,U], with multiple genuinely admissible points required, not one accidental boundary hit); adds an anti-permissiveness clause so the relaxed criterion cannot become too loose -- the number of in-band points is reported as a diagnostic rather than thresholded again, full gate-width span is not required, post-hoc grid expansion is prohibited, and grid redesign after calibration is prohibited unless the experiment formally returns to design status; the amendment is recorded with the old text struck through, not deleted, for audit-trail purposes, and it is explicitly noted the amendment would not have rescued KR-ZOOM-OUT-02 (which still would have been stopped under the amended rule)." (anchor: "The strict rule span(Y) >= width(gate) is not a generally meaningful adequacy requirement for a calibration grid. ... Requiring it to traverse the entire 0.50 interval confuses range coverage with gate reachability. ... AMENDMENT: §6.2 is replaced by the operational gate-reachability criterion. ...")

## Notes for P3
- This label is ungrouped in P2a — no mechanical signal (token overlap, co-occurrence, or explicit cross-reference) connected it to any other label in this batch's normalization pass.
- This label rests on a single captured row — the evidentiary base is thin by construction; P3 should treat any characterization here as provisional pending further corpus evidence, not as a settled account.
