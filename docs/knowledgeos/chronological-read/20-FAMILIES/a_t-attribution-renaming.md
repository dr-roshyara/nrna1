# a_t-attribution-renaming

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `A_t (post-R1; formerly K_t)` · **Aliases:** KnowledgeOS state object renamed under R1
**Candidate group membership (NOT an identity claim):**
- G0602: `a_t-attribution-renaming` · `knowledge-claim-determination-aggregates` — explicit agent-stated uncertainty (batch B0061): the step-292 crosswalk records that KnowledgeOS's central state object, called K_t throughout most of that batch's book-review files, has been renamed A_t following governance decision R1, since its nature is an attribution, not an assertion of Knows; this renaming is asserted as already-settled corpus fact within step-292, not itself a new proposal. Relationship not yet decided (P3).

## Sources (how this label entered the ledger)
- PROPOSAL, batch B0061, scope OBJECT: "The step-292 crosswalk records that KnowledgeOS's central state object, called K_t throughout most of this batch's book-review files, has been renamed A_t following a governance decision R1, since its nature is an attribution, not an assertion of Knows; this renaming is asserted as already-settled corpus fact within step-292, not itself a new proposal of this batch." (`relation_to_existing`: POSSIBLY knowledge-claim-determination-aggregates)

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2550 §"P8 | Reiter's knowledge operator = KnowledgeOS Knowledge | REFUTED | Reiter's Knows is accessibility-relation based and factive by construction. KnowledgeOS decided R1: its object is A_t, an attribution, and no component may assert Knows. They are different predicates by governance decision."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2550 §"P8 | Reiter's knowledge operator = KnowledgeOS Knowledge | REFUTED | Reiter's Knows is accessibility-relation based and factive by construction. KnowledgeOS decided R1: its object is A_t, an attribution, and no component may assert Knows. They are different predicates by governance decision."]

Both the lexical and governance candidate births point to the same S2550 row/anchor.

## Lifecycle
last_seen: S2554. Candidate lifecycle: ACTIVE. Evidence: no `retracted_by`, no `superseded_by`, `contested_by_own_contradiction_type: false`. This is a recency heuristic (S2554 is the later of the two rows) — the renaming is presented in both rows as an already-settled governance fact (R1) rather than a live proposal, but "ACTIVE" here reflects only that the label was recently touched, not a confirmed statement that the topic remains open for discussion.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S2550 |
| dependencies | PRESENT | S2550, S2554 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2554 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE (`rationale_evidence` is empty in the family data). Both rows nonetheless carry an implicit rationale in their statements: the rename from K_t to A_t is grounded in a distinction between an "attribution" and a factive "Knows" assertion — R1 decided KnowledgeOS's object must not assert factive knowledge, unlike Reiter's accessibility-relation-based Knows operator [S2550, S2554]. This is reported here as it appears in the rows' own statement/anchor text, not as a separately-flagged `rationale_evidence` entry.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE (`assumption_register` is empty in the family data).

## All rows (source_id order)
- [S2550] types=[CORRECTION, GOVERNANCE] scope=OBJECT — "P8 refuted by explicit governance decision (R1): KnowledgeOS's central object A_t is an attribution, and no component may assert factive Knows, whereas Reiter's Knows is accessibility-relation-based and factive by construction — these are different predicates by decision, not oversight." Carries dependency "R1 governance decision" and invariant "A_t attribution != Reiter's factive Knows." (anchor: "P8 | Reiter's knowledge operator = KnowledgeOS Knowledge | REFUTED | ...")
- [S2554] types=[GOVERNANCE, RESTATEMENT] scope=CROSS-OBJECT — "Confirms via the definitive crosswalk that KnowledgeOS's state object was renamed A_t (post-R1, formerly K_t) and is a candidate correspondent of Reiter's state projection (not Situation, which maps instead to History_t); Reiter's Knows is formally excluded as a NORMATIVE (governance-decided) rejection since R1 made KnowledgeOS's object an attribution rather than a factive knowledge predicate." Carries dependency "R1 governance decision." (anchor: "Situation (a history) | History_t — not A_t | DERIVED ... State projection | A_t (post-R1; formerly K_t) | DERIVED ... Reiter's Knows | ~~Knows~~ | REFUTED — R1 made KnowledgeOS's object an attribution | NORMATIVE (governance)")

## Notes for P3
- Observation: `family.files_touching` lists only `S2554`, while row 1 is attributed to `S2550` — another apparent gap in the pre-computed `files_touching` list versus the actual rows; flagging, not correcting.
- Observation: this label is explicitly a renaming event, not an independent object — both rows describe A_t as the post-R1 name for what was previously called K_t. Per R5/R12 this file does not assert that `a_t-attribution-renaming` "is" K_t or any other K_t-adjacent label elsewhere in the ledger; that identity question (if K_t-labeled families exist elsewhere) is exactly the kind of candidate-group question this file defers to P3, especially given the G0602 pairing with `knowledge-claim-determination-aggregates`.
- Observation: R1 (the governance decision cited by both rows) is treated here strictly as a dependency named inside the row data, not independently verified against any governance-decision ledger, per the "no re-reading source corpus" constraint.
