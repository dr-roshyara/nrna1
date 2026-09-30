# identity-object-status-table-state-operation-authority-event

**Scope(s):** CROSS-OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Authority-act identity: open, Event identity: open, Operation identity: open, Provenance identity: defined, State identity: defined · **Aliases:** five identity objects
**Candidate group membership (NOT an identity claim):**
- G0475: labels `humanactref-untyped-referent-governance-question` and `identity-object-status-table-state-operation-authority-event` — explicit agent-stated uncertainty (batch B0051): the layer-separated status table across five identity objects (used to argue grantId identifying a Grant does not establish authority-act identity semantics) is flagged as possibly relating to the HumanActRef untyped-referent governance question.

## Sources (how this label entered the ledger)
- PROPOSAL · batch B0051 · scope CROSS-OBJECT — "A layer-separated status table across five distinct identity objects relevant to the KnowledgeOS kernel programme, distinguishing which already have defined identity semantics (state, provenance) from which remain genuinely open (operation, authority-act, event) -- used to argue grantId identifying a Grant does not thereby establish authority-act identity semantics."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2121 §"| Object | Current status | | State identity | defined | | Provenance identity | defined | | Operation identity | open | | Authority-act identity | open | | Event identity | open | ... `grantId` identifies a **Grant**, but that does not establish identity semantics for the **authority act itself**."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2121. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded. Since no retraction/supersession/contradiction evidence is present, this lifecycle label is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S2121 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2121 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- (DISTINCTION/ANALYSIS) Endorses a layer separation across five distinct identity objects: state identity (defined), provenance identity (defined), operation identity (open), authority-act identity (open), event identity (open); clarifies that grantId identifies a Grant object but does not thereby establish identity semantics for the authority act itself -- these must be tracked as separate open questions, none of which is resolved by the state-identity work. [S2121]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2121] types=[DISTINCTION, ANALYSIS] scope=CROSS-OBJECT — "Endorses a layer separation across five distinct identity objects: state identity (defined), provenance identity (defined), operation identity (open), authority-act identity (open), event identity (open); clarifies that grantId identifies a Grant object but does not thereby establish identity semantics for the authority act itself -- these must be tracked as separate open questions, none of which is resolved by the state-identity work." (anchor: "| Object | Current status | | State identity | defined | | Provenance identity | defined | | Operation identity | open | | Authority-act identity | open | | Event identity | open | ... `grantId` identifies a **Grant**, but that does not establish identity semantics for the **authority act itself**.")

## Notes for P3
(Own observation.) This label functions as a status ledger/tracking table rather than a domain object in its own right — it records the open/defined status of five OTHER identity concepts (state, provenance, operation, authority-act, event) without itself defining any of their semantics. P3 may want to treat this less as a family to reconcile and more as an index pointing at three genuinely open questions (operation identity, authority-act identity, event identity) that presumably get resolved (or remain open) under whichever labels own those concepts elsewhere in the corpus — none of which are named explicitly in this label's own single row.
