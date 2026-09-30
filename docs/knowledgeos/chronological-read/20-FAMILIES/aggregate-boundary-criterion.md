# aggregate-boundary-criterion

**Scope(s):** METHODOLOGICAL · **Row count:** 3 ·
**Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "Same Aggregate iff shared transactional invariant requires atomicity" · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G0728: an UNKNOWN-OBJECT-CANDIDATE row (batch B0067, source S2781) named `aggregate-boundary-criterion`, `aggregate-derivation-criteria`, and `assurance-claim-aggregate-boundary` as alternative candidates for one piece of evidence. The capturing agent's stated uncertainty: this may re-derive an aggregate formalism and candidate-aggregate list independently within an architecture chapter, possibly a restatement/continuation of the DDD aggregate-derivation work from batch B0034 using the same underlying invariant-ownership logic but different symbolic notation (Agg=<Root,Members,Inv,Cmd,Ev> vs Agg=(I,O,B)); the concrete Evidence/Epistemic-Case/Model/Decision aggregate candidates are not explicitly tied back to the earlier ten-candidate derivation. Relationship not yet decided (P3).

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0034, scope METHODOLOGICAL: "Step 203's rigorous same-aggregate DDD test, explicitly rejecting relatedness, shared category, or shared storage as justifications, illustrated by Assessment holding immutable Evidence references rather than owning Evidence."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1422 §"Same Aggregate \iff Shared transactional invariant requires atomicity. Not: 'They are related.' Not: 'They are both knowledge.' Not: 'They are stored in the same database.'"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1477 §"DDD-2 | Aggregate adjudication (189's five vs 205's lists): apply 203 section34's own criterion (Same Aggregate iff shared transactional invariant requires atomicity) to the KI catalogue per candidate"]

## Lifecycle
last_seen: S1718. Candidate lifecycle: CONTESTED. Evidence: `retracted_by` and `superseded_by` are both empty, but `contested_by_own_contradiction_type` is true — the final row [S1718] is itself typed CONTRADICTION/ARGUMENT and reports that the corpus argues both sides of whether K (the object this criterion would be applied to) is a DDD Aggregate at all versus a read-model/projection over an event stream, with a proposed reframing (K as a sufficient statistic of H relative to the operation set) offered as resolution and the aggregate reading blamed for the corpus's "tuple proliferation." This is the source's own reported contradiction, not an assessment made by this file.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1718 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1422 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1477 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
S1718 supplies this label's only classified rationale row, and it is a contradiction report rather than a justification of the criterion itself: it argues that whether K is a DDD Aggregate (one transactional consistency boundary) or a read-model/projection over an event stream is argued both ways elsewhere in the corpus (Step 205 treats K as an aggregate; Step 247's 𝒦=(K,H) and replay machinery treat it as a projection), with opposite consequences for concurrency, merge, and invariant enforcement. It offers a separate document's KG-8 reframing (K as a sufficient statistic of H relative to the operation set) as a possible resolution, and blames the aggregate reading for the corpus's "tuple proliferation" [S1718]. The criterion itself (S1422) is justified on its own terms by explicit rejection of weaker alternatives: two objects are the same aggregate if and only if a shared transactional invariant requires their atomic co-change, explicitly rejecting "they are related," "they are both knowledge," or "same database" as justifications — illustrated by Assessment holding immutable EvidenceRef_i references rather than owning the referenced Evidence objects, letting evidence evolve independently [S1422].

rationale_truncated_count = 0 (all rationale-bearing rows for this label are shown above).

## Assumption register
NOT-EVIDENCED-IN-CAPTURE (family.assumption_register is empty for this label).

## All rows (source_id order)

- [S1422] types=[DEFINITION, CONSTRAINT] scope=METHODOLOGICAL — "Establishes the rigorous DDD aggregate-boundary test: same aggregate iff a shared transactional invariant requires atomic co-change; rejects 'related', 'both knowledge', 'same database' as justifications; illustrated by Assessment holding immutable EvidenceRef_i references rather than owning Evidence." (anchor: "Same Aggregate \iff Shared transactional invariant requires atomicity. ...")
- [S1477] types=[GOVERNANCE] scope=OBJECT — "DDD-2 adjudicates step 189's five candidate aggregates against step 205's aggregate lists by applying step 203 section 34's own same-aggregate criterion to the KI catalogue per candidate, producing a per-candidate verdict, resolving disagreements by the criterion or marking UNRESOLVED." (anchor: "DDD-2 | Aggregate adjudication (189's five vs 205's lists) ...")
- [S1718] types=[CONTRADICTION, ARGUMENT] scope=THEORY-LEVEL — "UL-6: whether K is a DDD Aggregate or a read-model/projection is argued both ways in the corpus (Step 205 vs Step 247's replay machinery), with opposite consequences for concurrency/merge/invariant enforcement; a separate KG-8 reframing (K as a sufficient statistic of H) is offered as resolution; the aggregate reading is blamed for the corpus's tuple proliferation." (anchor: "UL-6 (`DERIVED`, HIGH) — the aggregate boundary is never fixed.")

## Notes for P3
- This label's own history shows the criterion being defined [S1422], then operationally applied to adjudicate a real dispute [S1477], then having its target object's very aggregate-hood called into contradiction elsewhere in the corpus [S1718] — the CONTESTED flag applies to whether the underlying object (K) is an aggregate at all, not to the correctness of the criterion's definition itself. Worth distinguishing these two levels during reconciliation: the criterion (well-defined, unretracted) versus its application to K (disputed).
- G0728 raises a real possibility that a later architecture-chapter document (batch B0067, S2781) independently re-derived an aggregate formalism using different notation without explicitly tying it back to this criterion or its ten-candidate derivation — flagged as a priority candidate for P3 reconciliation, given the risk of duplicated theory under different symbols (Agg=<Root,Members,Inv,Cmd,Ev> vs Agg=(I,O,B)).
- `family.files_touching` lists S1720 in addition to S1422/S1477/S1718 (which do appear in family.rows), but no row from S1720 appears in this label's row list — noted as a data-completeness oddity for P3, consistent with a pattern seen in other labels in this batch group.
