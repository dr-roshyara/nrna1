# assurance-claim-aggregate-boundary

**Scope(s):** OBJECT · **Row count:** 5 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G0728: an UNKNOWN-OBJECT-CANDIDATE row (batch B0067, source S2781) named `aggregate-boundary-criterion` and `aggregate-derivation-criteria` as alternative candidates possibly related to this label. The normalization note describes this as a re-derivation of an aggregate formalism/candidate-aggregate list within the architecture chapter, possibly a restatement or continuation of DDD aggregate-derivation work using the same underlying invariant-ownership logic but a different symbolic notation (Agg=<Root,Members,Inv,Cmd,Ev> vs Agg=(I,O,B)); the concrete Evidence/Epistemic-Case/Model/Decision aggregate candidates are not explicitly tied back to this label's earlier derivation. Relationship not yet decided (P3).

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0002, scope OBJECT: "The {Assurance Claim, its Assessments} aggregate boundary and its uniqueness-rule justification."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0049 §"F-1 (MAJOR): the boundary is RIGHT; its justification is WRONG as stated. Repair: restate the boundary as {Claim · Assessments} + serialized establishment, justified by the outcome-definiteness INV-ATTR-2/3 presuppose"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0050 §"I interpret the approved Assurance Model as requiring at most one established assessment per Assurance Claim at a time."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0050 §"I interpret the approved Assurance Model as requiring at most one established assessment per Assurance Claim at a time."]

## Lifecycle
last_seen: S0050. Candidate lifecycle: DORMANT. Evidence: no retraction, no superseding row, no self-contradiction flag; DORMANT here is a heuristic based on how long ago (by source_id ordering) this label was last touched, not a confirmed retirement of the boundary decision.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0050 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0049, S0050 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE (family.rationale_evidence is empty for this label; the substantive content is carried as CORRECTION/DEFINITION/GOVERNANCE/DISTINCTION rows rather than rows classified into the rationale_evidence bucket).

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S0049] types=[CORRECTION] scope=OBJECT — "Finding F-1: the aggregate boundary {Assurance Claim, its Assessments} is correct but its stated justification (INV-ATTR-2 alone) is unsound, because a total derivation with an ELSE branch can never observe an undefined outcome regardless of transactionality; the real justification is an unstated uniqueness rule — at most one established assessment per claim at a time — which the approved model presupposes but never states." (anchor: "F-1 (MAJOR): the boundary is RIGHT; its justification is WRONG as stated. Repair: restate the boundary as {Claim · Assessments} + serialized establishment, justified by the outcome-definiteness INV-ATTR-2/3 presuppose")
- [S0049] types=[DISTINCTION] scope=OBJECT — "F-3: the claim-uniqueness rule is precised to 'one CURRENT claim per (act, dimension)'; status disputes about a claim's epistemic standing are Assessments, while content disputes about what actually happened are resolved via Evidence plus claim supersession, never by admitting a second simultaneous claim or moving the claimant onto the Assessment." (anchor: "one claim per (Governed Act, dimension) should read one current claim per (Governed Act, dimension). Claimant stays on the Claim")
- [S0050] types=[DEFINITION] scope=OBJECT — "R-1 restates the aggregate boundary as {Assurance Claim, its Assessments} with establishment serialized per claim, making 'current outcome' a guaranteed derived read rather than a stored member; Evidence References stay outside the boundary, referenced only." (anchor: "The minimal aggregate is { Assurance Claim · its Assessments }, with establishment serialized per claim. \"Current outcome\" is a guaranteed read over the boundary")
  - Lineage claim: SOURCE-CLAIMED-REPLACEMENT → "rev 3 §1.1's boundary conclusion, §1.2's supersession-row reasoning, §2's derivation note" (quote: "Supersedes: rev 3 §1.1's boundary conclusion, §1.2's supersession-row reasoning, and §2's derivation note.").
- [S0050] types=[GOVERNANCE, AXIOM] scope=OBJECT — "The Human/ARB records, verbatim in their own words, the interpretive uniqueness rule the F-1 repair required: at most one established assessment per Assurance Claim at a time, framed as an interpretation of the already-approved model, not a new invariant." (anchor: "I interpret the approved Assurance Model as requiring at most one established assessment per Assurance Claim at a time.")
- [S0050] types=[DISTINCTION] scope=OBJECT — "R-3 precises the claim-uniqueness rule to one CURRENT claim per (act, dimension) with superseded claims retained in history, and formalizes that status-disputes route to Assessment while content-disputes route to Evidence plus claim-supersession." (anchor: "One current claim per (Governed Act, dimension). Superseded claims stand in history.")

## Notes for P3
Five rows across two documents, both dated 2026-08-16: S0049 (`2026-08-16-KOS-ATTR-ARCH-001-rev3-independent-architecture-review.md`, 2 rows) and S0050 (`2026-08-16-KOS-ATTR-ARCH-001-f1-f8-repair.md`, 3 rows), which S0050 itself states supersedes parts of S0049 ("Supersedes: rev 3 §1.1's boundary conclusion, §1.2's supersession-row reasoning, and §2's derivation note"). This is a tight, internally consistent review→repair sequence: S0049 finds the aggregate boundary itself correct but its stated justification unsound (F-1), and S0050 restates the boundary with the corrected justification (an unstated uniqueness rule made explicit) and has the Human/ARB record that interpretation verbatim as governance. Despite this being explicitly a supersession, `lifecycle_evidence.superseded_by` is empty and `contested_by_own_contradiction_type` is false — my own observation: the supersession here is *internal* to this same label's row set (S0050 supersedes parts of S0049, both under this one working_label), which is different from the label-level supersession the lifecycle heuristic seems designed to catch; this is not a flaw to flag as data-quality, just a reminder to P3 that DORMANT/ACTIVE and the retracted_by/superseded_by fields track cross-label lifecycle signals, not the internal review-then-repair pattern visible within a single label's own rows.
