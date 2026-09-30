# discrepancy-resolved-as-design-difference

**Scope(s):** METHODOLOGICAL · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0067, scope METHODOLOGICAL: "Methodological resolution and lesson: an apparent numeric mismatch between two independent computations was a design/scope difference (edge-variant treatment), not a contradiction."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2803 §"My first pass reported 16...23 over 32 worlds against their published 15...22, and I did not report that as a mismatch. The designs differ ... The structural variant is what reaches 15. Both are correct for their own design ... a number that disagrees is a difference of design or scope until proven "]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2803. Candidate lifecycle: ACTIVE. Evidence: no retraction, no superseding row, no self-contradiction flag; ACTIVE here is a heuristic based on recency of the single captured sighting (S2803), not a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2803 |
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
This label captures a methodological finding: an earlier independent 32-world verification pass (`verify_k9_closure.py`, S2799) had reported a range of 16..23 against a published 14-world range of 15..22, and this was not a contradiction but a genuine design/scope difference — the original review-lane design crosses mapping perturbations with the structural-only InvariantReg edge variant (which reaches the minimum of 15), while the independent pass held the edge at "epistemic" throughout; both are correct for their own design, and the union across both is captured by a wider 66-world 15..23 range. The row draws the general methodological lesson that "a number that disagrees is a difference of design or scope until proven otherwise," and that the correct first response to an apparent mismatch is to read the other design rather than report a contradiction [S2803]. The row records a SOURCE-CLAIMED-REFINEMENT lineage relation to `OUT-verify_k9_closure.txt (S2799)`.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S2803] types=[CORRECTION, EXPLANATION] scope=OBJECT — "Explains that an earlier 32-world pass (verify_k9_closure.py, S2799) reported a range of 16..23 against the published 14-world 15..22, but this reflects a genuine design difference rather than an error — the original review-lane design crosses the mapping perturbations with the structural-only InvariantReg edge variant (which is what reaches the minimum of 15), while the first independent pass held the edge at epistemic throughout; both are correct for their own design, and the union of both is captured in the 66-world 15..23 range; states the general methodological lesson that 'a number that disagrees is a difference of design or scope until proven otherwise' and the first response to a mismatch should be to read the other design, not report a contradiction." (anchor: "My first pass reported 16...23 over 32 worlds against their published 15...22, and I did not report that as a mismatch. The designs differ ... The structural variant is what reaches 15. Both are correct for their own design ... a number that disagrees is a difference of design or scope until proven ")

## Notes for P3
Single-row label (S2803), from `docs/knowledgeos/brainstorming/verification/gap-discovery/gap-update-2026-09-02/11-INDEPENDENT-VERIFICATION-K9-CLOSURE.md`. This is a methodological/procedural finding rather than an OBJECT-level theory construct — its scope in the OBJECT-INDEX note is METHODOLOGICAL, though the row itself is tagged scope=OBJECT (my own observation: the node_metadata scope and the row-level scope disagree slightly — METHODOLOGICAL at the label level, OBJECT at the row level — worth a quick check by P3 on which is authoritative for this kind of "methodological lesson" label). References an external source S2799 (`OUT-verify_k9_closure.txt`) that is not itself among this label's rows — it is cited only as a lineage target, not directly evidenced in this capture.
