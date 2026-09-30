# spec-amend-2026-v1-demotion-and-ea-definition

**Scope(s):** METHODOLOGICAL · **Row count:** 4 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "EA(K,O,Q,Gamma) iff Adequacy(K,Q,Gamma) and forall op in O: Preserved(delta(K,op,Gamma),Gamma) supseteq R_req(Q,Gamma)", "SPEC-AMEND-2026-v1.0" · **Aliases:** "Final Structural Audit and Re-Specification Protocol"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0062, scope METHODOLOGICAL: "A governance document that formally demotes all previously [RATIFIED] specs (SPEC-R-REQ through SPEC-CANON) to [PROPOSED -- VALIDATED IN TESTED SCOPE], restates the corrections from the preceding critical review as accepted, gives the first fully-quantified formal definition of Executable Adequacy EA(K,O,Q,Gamma) iff Adequacy(K,Q,Gamma) and for every operation op in O, Preserved(delta(K,op,Gamma),Gamma) supseteq R_req(Q,Gamma), and issues a 12-item open register I-01 through I-12 (CLOSED/CANDIDATE/OPEN/BLOCKED statuses) plus a four-package (C1-C4) execution protocol with named falsification tests for each."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2579 §"Your critique is correct. The previous document suffered from premature operational closure ... All previously issued specifications (SPEC-R-REQ through SPEC-CANON) are hereby demoted from [RATIFIED] to [PROPOSED -- VALIDATED IN TESTED SCOPE]."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2579 §"D. The Missing Bridge: Executable Adequacy (EA) ... EA(K,O,Q,Gamma) iff Adequacy(K,Q,Gamma) and forall op in O, Preserved(delta(K,op,Gamma),Gamma) supseteq R_req(Q,Gamma). An adequate representation K is Executably Adequate only if the operational set O and transition function delta preserve all required distinctions R_req across state transformations without introducing silent collapse."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2579 §"Your critique is correct. The previous document suffered from premature operational closure ... All previously issued specifications (SPEC-R-REQ through SPEC-CANON) are hereby demoted from [RATIFIED] to [PROPOSED -- VALIDATED IN TESTED SCOPE]."]

## Lifecycle
last_seen: S2596. Candidate lifecycle: ACTIVE. Evidence: retracted_by and superseded_by are both empty and no row is self-typed as a contradiction; this is a heuristic based on how recently (by source_id order) this label was last used (S2596), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2579 |
| type_signature | PRESENT | S2579 |
| invariants | PRESENT | S2579 |
| dependencies | PRESENT | S2579, S2579, S2579, S2596 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2579, S2596 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2596 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2579] types=[GOVERNANCE, RETRACTION] scope=METHODOLOGICAL — "Formally accepts a prior critique's finding of premature operational closure and demotes every specification issued so far in this batch's cascade (SPEC-R-REQ through a named SPEC-CANON, implying at least one further un-seen canon document) from [RATIFIED] to [PROPOSED -- VALIDATED IN TESTED SCOPE] -- the first explicit, wholesale retraction of ratification status in this batch." (anchor: "Your critique is correct. The previous document suffered from premature operational closure ... All previously issued specifications (SPEC-R-REQ through SPEC-CANON) are hereby demoted from [RATIFIED] to [PROPOSED -- VALIDATED IN TESTED SCOPE].")
- [S2579] types=[FORMALIZATION] scope=THEORY-LEVEL — "Gives the first fully quantified formal definition of Executable Adequacy: EA(K,O,Q,Gamma) holds iff K is representation-Adequate for (Q,Gamma) AND for every operation op in O, applying delta(K,op,Gamma) preserves at least R_req(Q,Gamma) -- i.e. no operation may silently collapse a required distinction across a state transformation." (anchor: "D. The Missing Bridge: Executable Adequacy (EA) ... EA(K,O,Q,Gamma) iff Adequacy(K,Q,Gamma) and forall op in O, Preserved(delta(K,op,Gamma),Gamma) supseteq R_req(Q,Gamma). An adequate representation K is Executably Adequate only if the operational set O and transition function delta preserve all required distinctions R_req across state transformations without introducing silent collapse.")
- [S2579] types=[RESTATEMENT] scope=THEORY-LEVEL — "Restates the batch's consolidated open-item register as a numbered I-01 through I-12 falsification matrix (matching, in substance, the C1-C4/CLOSED-CANDIDATE-OPEN-BLOCKED register seen in the immediately preceding file S2578, here with explicit falsification criteria attached to each item and a specific pruned O_core reduction target naming Query/Explain/Authorize as items to remove)." (anchor: "Consolidated Open-Item Register & Falsification Matrix: I-01 R_req Definition CLOSED ... I-03 Contr Semantics CANDIDATE ... I-04 EVal Boundaries CANDIDATE ... I-05 through I-10 OPEN (Det, equiv_sem, O_core Reduction, delta, Frame Composition, Executable Adequacy) ... I-11 Kernel Reduction BLOCKED ... I-12 Kernel Selection BLOCKED.")
- [S2596] types=[RESTATEMENT, EXPERIMENTAL-RESULT] scope=CROSS-OBJECT — "Tabulates seven specific claims audited against the governance register: SPEC-DET-2026-v1 (self-declared [RATIFIED] under 'Authority: HPA Supervisory / KnowledgeOS Architecture Board'), SPEC-RREQ-2026-V1-RATIFIED ([PROPOSED RATIFICATION]), 'GAP CLOSURE STRATEGY' (contains [RATIFIED]), the 'Final Structural Audit & Re-Specification' document (contains [RATIFIED]), DECISION-01 ([DECIDED], explicitly self-attributed to governance rather than the reporting lane), and factivity claim R1 (DECIDED, governance, 2026-09-02) -- none of these six are found in governance/; the seventh, DECISION-02, is correctly still marked [DECISION REQUIRED] and open." (anchor: "The claims, and where each is recorded: SPEC-DET-2026-v1 [RATIFIED], Authority: HPA Supervisory/KnowledgeOS Architecture Board -- In governance/? NO. SPEC-RREQ-2026-V1-RATIFIED [PROPOSED RATIFICATION] -- NO. GAP CLOSURE STRATEGY [RATIFIED] -- NO. Final Structural Audit & Re-Specification [RATIFIED] -- NO. DECISION-01 [DECIDED], taken by governance, not by this lane -- NO. factivity R1 DECIDED (governance, 2026-09-02) -- NO. DECISION-02 [DECISION REQUIRED] -- n/a, correctly open.")

## Notes for P3
- This label is ungrouped in P2a — no mechanical signal (token overlap, co-occurrence, or explicit cross-reference) connected it to any other label in this batch's normalization pass.
- family.files_touching lists ['S2580'] in addition to the source_ids that appear in family.rows — no row from ['S2580'] appears in this label's row list. Noted as a data-completeness oddity for P3, consistent with a pattern seen in other labels processed in this batch.
- Rows for this label were captured under more than one scope tag (['CROSS-OBJECT', 'METHODOLOGICAL', 'THEORY-LEVEL']) — this may reflect genuine cross-scope relevance (e.g. an OBJECT used at THEORY-LEVEL) rather than a labeling error, but P3 may want to confirm.
