# c10-establishment-criteria

**Scope(s):** OBJECT · **Row count:** 6 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** D5 criteria, E1..E10 · **Aliases:** establishment criteria
**Candidate group membership (NOT an identity claim):**
- G0028: shares an explicit agent-stated uncertainty with `capability-c10-knowledge-distribution` (batch B0003) — the same note is recorded for this label's own PROPOSAL source entry (below), which itself flags a possible relation to that capability — relationship not yet decided (P3).
- G0853: shares the notation 'E1..E10' with `c10-d5-establishment-criteria` — relationship not yet decided (P3).
- G0925: has a working_label token-overlap Jaccard of 0.75 with `c10-d5-establishment-criteria` (shared tokens: c10, criteria, establishment) — relationship not yet decided (P3).

## Sources (how this label entered the ledger)
- PROPOSAL, batch B0003, scope OBJECT, relation_to_existing "POSSIBLY:capability-c10-knowledge-distribution": "Ten named criteria (semantic fidelity, receipt completeness, claim authority model, receipt state integrity, lifecycle realization, repeatability, boundary discrimination, provenance reconstruction, negative claim discipline, operational repeatability) for moving C-10 from NOT YET ESTABLISHED to ESTABLISHED, with a three-level (L1/L2/L3) assurance model."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0117 §"SUBJECT Realization | Boundary | Future-Excluded ... EPISTEMIC Observed | Inferred | Proposed | Decided | Open ... PROVENANCE Governed | Ungoverned | External-corroborative ... REPRODUCIBILITY Reproducible | Non-reproducible"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0117 §"SUBJECT Realization | Boundary | Future-Excluded ... EPISTEMIC Observed | Inferred | Proposed | Decided | Open ... PROVENANCE Governed | Ungoverned | External-corroborative ... REPRODUCIBILITY Reproducible | Non-reproducible"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0120. Candidate lifecycle: DORMANT. Evidence: retracted_by empty, superseded_by empty, contested_by_own_contradiction_type false. DORMANT is a heuristic based on how recently (by source_id) this label was last used (S0120, batch B0003, early in the corpus), not a confirmed close-out — the label's own last row (S0120) explicitly records the underlying commission as still open pending a future Architecture artifact, which is a stronger signal of "awaiting continuation" than of retirement.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0117, S0117, S0120 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0117, S0117, S0117 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0120 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0120 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0117 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The rationale evidence documents why the object exists and what alternative it displaced. S0117 proves that adopting the full conjunctive criteria set E1 through E10 as the establishment test (Option A) is not merely unjustified but unsatisfiable, because E10 (operational repeatability, requiring a sustained history) structurally cannot be satisfied at a single point in time — this disposes of Option A on evidence rather than preference and is the direct reason a three-level model was needed [S0117]. In its place, S0117 recommends a three-level assurance model (Option C: L1 Prototype / L2 Operational / L3 Authoritative), with the D2 (existence) decision recommended to flip only at L2 rather than L1, because a single instance is still compatible with an incidental (not owned) outcome, and E6 (repeatability across ≥2 acts) is what actually demonstrates ownership [S0117]. S0120 supplies a second, corrective layer of rationale: it documents that D5 was registered as DECIDED (adopting the establishment-criteria model in principle) while Governance simultaneously discovered that eight of the nine criterion names (E1-E9) appeared nowhere as defined content in the estate, because the commissioned Architecture analysis had not yet been delivered — "a name is not a criterion" is stated as a governing discipline, and the record is corrected to distinguish framework-adoption from provenance/artifact-delivery status, keeping the commission open rather than closed [S0120]. rationale_truncated_count is 0 — no further rationale rows exist beyond what's shown.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S0117] types=[FORMALIZATION] scope=THEORY-LEVEL — "Four orthogonal evidence dimensions adopted for C-10 establishment: SUBJECT, EPISTEMIC, PROVENANCE, REPRODUCIBILITY; notes a DECIDED claim and a Reproducible claim are not the same thing." (anchor: "SUBJECT Realization | Boundary | Future-Excluded ... EPISTEMIC Observed | Inferred | Proposed | Decided | Open ... PROVENANCE Governed | Ungoverned | External-corroborative ... REPRODUCIBILITY Reproducible | Non-reproducible")
- [S0117] types=[ANALYSIS, EXPERIMENTAL-RESULT] scope=OBJECT — "Impossibility proof: the full conjunction E1-E10 (Option A) would make C-10 permanently unestablishable because E10 requires a history that cannot exist at establishment time." (anchor: "Option A is not merely unjustified -- it is unsatisfiable. the full conjunction E1 and ... and E10 includes E10, and E10 requires a history that cannot exist at establishment time. Adopting Option A would make C-10 PERMANENTLY UNESTABLISHABLE")
- [S0117] types=[FORMALIZATION, ANALYSIS] scope=OBJECT — "Recommends a three-level assurance-level model (Option C): L1 Prototype, L2 Operational, L3 Authoritative; D2 should flip only at L2 because a single instance is compatible with an incidental outcome." (anchor: "L1 PROTOTYPE ESTABLISHMENT ... L2 OPERATIONAL ESTABLISHMENT ... L3 AUTHORITATIVE ESTABLISHMENT ... Which level flips D2? recommended: L2, not L1. one instance satisfying E1/E2/E7/E8 is still compatible with an incidental outcome")
- [S0117] types=[FORMALIZATION, VALIDATION] scope=OBJECT — "Criterion E7 (Boundary discrimination) given a counterfactual satisfaction test: remove the candidate capability and ask whether the outcome still appears; if yes, E7 fails." (anchor: "does the acting context OWN the outcome, or does it FALL OUT of another context's mechanism? Counterfactual form: remove C-10 -- does the receipt still appear? If yes, E7 fails.")
- [S0120] types=[LIMITATION, ANALYSIS] scope=OBJECT — "D5 registered as DECIDED while Governance discovers eight of nine criterion names (E1-E9) appear nowhere as defined content in the estate; 'a name is not a criterion' stated as governing discipline." (anchor: "eight of nine names appear NOWHERE in the estate ... The adopted criteria have no artifact in the governed record. They arrive as names only ... this is the THIRD occurrence of the same pattern in this estate")
- [S0120] types=[CORRECTION, DISTINCTION] scope=OBJECT — "D5-AMD1 corrects D5's status to two parts (framework decision adopted; provenance/Architecture-artifact status separately pending); Option A must not be assumed the minimum sufficient combination; commission kept open." (anchor: "D5 STATUS: DECIDED -- framework adopted. PROVENANCE STATUS: Architecture artifact pending. ... A good model is not the same as an authorized architectural deliverable.")

## Notes for P3
- Own observation: this label's own evidence documents a self-correcting governance sequence within 2 source documents (S0117 proposes and proves; S0120 discovers the proposal's criteria E1-E9 were never actually defined as content, only named) — this is a strong, load-bearing internal tension worth priority attention: the object as understood after S0120 is materially thinner (framework adopted, but 8/9 criteria still undefined content) than as understood after S0117 alone.
- Own observation: three separate group_ids (G0028, G0853, G0925) all point at what look like closely related or possibly-identical siblings (`capability-c10-knowledge-distribution`, `c10-d5-establishment-criteria` twice via different signals) — this label may be a strong candidate for priority P3 reconciliation given the convergence of three independent mechanical signals, though per R5/R12 this file makes no identity claim.
- Own observation: S0120 states "this is the THIRD occurrence of the same pattern in this estate" — that pattern (criteria adopted as names before content) recurs elsewhere in the corpus per the source's own claim, but the other two occurrences are not in this label's own rows and so are not documented here.
