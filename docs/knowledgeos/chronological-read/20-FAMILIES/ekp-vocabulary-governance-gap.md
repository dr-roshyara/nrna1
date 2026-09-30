# ekp-vocabulary-governance-gap

**Scope(s):** CROSS-OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** S3 declared-vocabulary check 132/132 INCONCLUSIVE, authorities.yaml, statuses.yaml, etc. 0 knowledge cards each · **Aliases:** GR-5, GR-6
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0041 · scope CROSS-OBJECT — "All ten schema vocabulary files in the EKP (authorities.yaml, statuses.yaml, bounded-contexts.yaml, etc.) carry zero knowledge cards -- no knowledge_id, status, authority, owner, or review date -- and are not validated by knowledge-lint as documents, meaning an edit to any of them silently changes the meaning of every governed document with no ADR, review, owner, or lint gate; the one designed defense (knowledge-lint's S3 declared-vocabulary check) is 132/132 INCONCLUSIVE because its required config file schema/vocabulary-integrity.yaml does not exist, though its fail-closed design (INCONCLUSIVE rather than false PASS) is praised while the overall structural-profile mechanism is noted as warn-only, wired into no gate, and exits 0."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1707 §"All ten schema vocabulary files carry zero knowledge cards. ... statuses.yaml determines what frozen MEANS. authorities.yaml determines what authoritative MEANS. An edit to either changes the meaning of every governed document in the system, and is subject to no ADR, no review, no owner and no lint gate. ... The system governs its documents. It governs its constitution. It does not govern the vocabulary that its constitution and its documents are written in."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1707. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded. Since no retraction/supersession/contradiction evidence is present, this lifecycle label is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1707 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1707 |
| dependencies | PRESENT | S1707 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1707 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- (EXPERIMENTAL-RESULT/ANALYSIS) Finding GR-6: running knowledge-lint --profile=structural against docs/knowledge finds the declared-vocabulary check (S3) reports 0 PASS / 0 FAIL / 132 INCONCLUSIVE because its required config file docs/knowledge/schema/vocabulary-integrity.yaml does not exist, meaning this check -- the one designed to police vocabulary drift -- has never once run successfully; the design is judged exemplary (fail-closed by construction, per the code's own comment 'absence of evidence is not PASS', unlike most systems which would return green), but the overall mechanism is inert (warn-only, not wired into any gate/hook, exits 0). A separate finding (GR-7, low severity) notes the 20 S2 intra-doc-reference failures are all confined to docs/knowledge/archive/, not the live working set. [S1707]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1707] types=[EXPERIMENTAL-RESULT, LIMITATION] scope=CROSS-OBJECT — "Finding GR-5: measuring all ten docs/knowledge/schema/*.yaml vocabulary files (authorities.yaml, statuses.yaml, bounded-contexts.yaml, knowledge-relationships.yaml, knowledge-types.yaml, knowledge-schema.yaml, knowledge-audiences.yaml, documentation-placement.yaml, governed-registers.yaml, repository-migrations.yaml) finds zero knowledge cards in every one -- no knowledge_id, status, authority, owner, or review date, and knowledge-lint does not validate them as documents; concludes this is the reflexivity gap made concrete: the system governs its documents and its own constitution, but not the vocabulary its constitution and documents are written in, and this is structurally the same move as the theoretical exogeneity stipulation (§2) -- both terminate a regress by exiting the system, both leave the exit unprotected." (anchor: "All ten schema vocabulary files carry zero knowledge cards. ... statuses.yaml determines what frozen MEANS. authorities.yaml determines what authoritative MEANS. An edit to either changes the meaning of every governed document in the system, and is subject to no ADR, no review, no owner and no lint gate. ... The system governs its documents. It governs its constitution. It does not govern the vocabulary that its constitution and its documents are written in.")
- [S1707] types=[EXPERIMENTAL-RESULT, ANALYSIS] scope=OBJECT — "Finding GR-6: running knowledge-lint --profile=structural against docs/knowledge finds the declared-vocabulary check (S3) reports 0 PASS / 0 FAIL / 132 INCONCLUSIVE because its required config file docs/knowledge/schema/vocabulary-integrity.yaml does not exist, meaning this check -- the one designed to police vocabulary drift -- has never once run successfully; the design is judged exemplary (fail-closed by construction, per the code's own comment 'absence of evidence is not PASS', unlike most systems which would return green), but the overall mechanism is inert (warn-only, not wired into any gate/hook, exits 0). A separate finding (GR-7, low severity) notes the 20 S2 intra-doc-reference failures are all confined to docs/knowledge/archive/, not the live working set." (anchor: "S3 -- the declared vocabulary check, i.e. the very check that would police vocabulary drift -- is 132/132 INCONCLUSIVE ... The file does not exist. The check has never run successfully. ... The design is EXEMPLARY: it is fail-closed by construction ... The mechanism is nonetheless INERT: warn-only, wired into no gate or hook, exit 0, and its central slice unconfigured.")

## Notes for P3
(Own observation.) This label is an operational/engineering audit finding about the actual repository's `docs/knowledge/schema/*.yaml` state and the `knowledge-lint` tool, not a theory object — it is out of scope for this pass to re-verify current repo state, but P3/a later maintenance pass may want to check whether `docs/knowledge/schema/vocabulary-integrity.yaml` has since been created, since S1707 explicitly reports the designed defense (S3 check) has "never run successfully" as of this finding. The label's own framing draws a structural analogy to a "theoretical exogeneity stipulation" elsewhere in the corpus (row 1: "structurally the same move ... both terminate a regress by exiting the system, both leave the exit unprotected") — this cross-reference is stated by the source itself, not invented here, but the referenced theoretical object is not itself part of this label's rows and was not independently checked.
