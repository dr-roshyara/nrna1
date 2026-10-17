# PublicDigit Membership — Principal review of the correction-slice contract

I will continue exclusively in the Membership domain. My review is based on the correction acceptance criteria and status report you supplied, together with the existing Membership research context. I have not inspected the complete current contents of `membership-correction-slice-semantic-contract-draft.md`, so I will distinguish findings visible in the excerpt from checks Claude must perform against the full document.

## 1. Principal-reviewer verdict

The contract is progressing, but I would not yet approve C-1 for implementation based on this excerpt alone.

Adding operation-specific acceptance criteria was the right improvement. However, a list of tests is not yet a complete executable contract. The most important remaining task is to verify that the operation semantics, correction algebra, historical reconstruction rules, and rejection conditions are internally consistent.

The next mission should therefore be a semantic consistency review of the contract, not another discovery round and not implementation.

Good progress

- Annul, Void and Correct have separate acceptance criteria.
- The draft covers idempotency, race behaviour, persistence and temporal reconstruction.
- Open business-policy decisions are identified rather than silently marked approved.
- The proposed first implementation slice is narrowly scoped to kernel-level Annul and Void.

Review before implementation

- Clarify what it means for an Annul or Void operation to restore an earlier fact.
- Resolve the interaction between an annulled fact and a correction that is currently inert.
- Specify precedence when multiple correction operations target related facts.
- Verify that oracle comparisons are genuinely independent and cover all claimed cases.
- Ensure that open decisions have explicit blocking consequences for C-1.

## 2. Semantic issues I want Claude to investigate

### A. Annul, Void and Correct must not be interchangeable

The excerpt says:

- Annul revokes a decision-backed fact.
- Void can target earlier facts, including Annul and Void.
- Correct changes the effective date and has special behaviour for annulled and voided targets.

These are distinct operations with potentially different legal and historical meanings. Claude must verify that the contract defines the meaning of each operation independently and then defines their interactions.

For every operation, the contract should answer: Which fact is targeted, what new fact is recorded, what is the resulting resolution, and what can be reconstructed at each `knownAt` instant?

### B. “Correct an annulled fact, then make it effective later” needs a precise rule

The stated criterion says that correcting an annulled fact is accepted but inert, and becomes effective after `Annul(Annul)`.

That may be a valid rule, but it is a significant dependency between operations. The contract must specify:

- whether the correction is retained as a fact while inert;
- whether it is automatically applied if the target becomes effective again;
- how multiple corrections are ordered;
- what happens if the target is voided before revival;
- whether the resulting history remains deterministic regardless of evaluation order.

These are questions for formal semantic validation, not assumptions to resolve through implementation.

### C. Restoration rules need algebraic properties

The draft describes restoration for `Annul(Annul)`, `Void(Annul)` and `Void(Void)`, subject to invariant checks.

Claude should express these as explicit state-transition rules and verify which properties are intended:

- Determinism: the same valid history resolves to the same answer.
- Historical preservation: a new operation does not change what was known before its `recordedAt`.
- Invariant preservation: a restoration that would produce an invalid Membership history is refused.
- Lineage preservation: every effective change can be traced to the relevant original and corrective facts.
- Defined precedence: related corrections cannot yield an ambiguous result.

Do not assume all these operations are simple mathematical inverses. The history, provenance and validity constraints may make an apparent inverse invalid.

### D. “Exhaustive differential equality” needs an explicit scope

The excerpt refers to equality with a PHP oracle, while the existing research context describes a Python correction model compared with the PHP kernel. Claude must check the actual implementations and name them accurately.

The contract should identify:

1. the reference implementation;
2. the implementation under test;
3. the generated input histories and bounds;
4. the compared outputs and refusal reasons;
5. the properties that are independently tested;
6. the cases that remain outside the model.

Agreement between two implementations is useful evidence, but it is not proof of correctness if they share the same mistaken interpretation of the rules. The ratified Membership specification must remain the authority.

## 3. Recommended next step: a consistency audit before C-1

The next Claude mission should correct the contract's semantics and traceability where evidence supports a change, without changing the ratified architecture or making policy decisions on your behalf.

# Membership Mission — Independent Semantic Consistency Audit of the Correction Contract

## Role and scope

Act as a principal DDD architect, Membership domain researcher, requirements engineer, formal-methods analyst, and mathematical reviewer.

Work exclusively in the PublicDigit Membership domain and its necessary Membership implementation boundaries.

Review the existing draft:

`docs/publicdigit/reviews/2026-10-09-membership-correction-slice-semantic-contract-draft.md`

The contract has been extended with §10a, acceptance criteria per operation for Annul, Void and Correct.

## Mission objective

Determine whether the correction-slice contract is internally consistent, traceable to ratified Membership rules, sufficiently precise for implementation, and safe to use as the basis for C-1.

This is a documentation and analysis mission. It is not authorisation to implement correction operations.

## 1. Preserve the authoritative corpus

Read the complete current contract, the ratified Membership architecture and fact algebra, the correction oracle, kernel implementation, research findings and decision register.

Do not restart completed discovery. Do not rewrite the architecture merely to make the contract easier to implement.

Distinguish:

- ratified rule;
- observed implementation behaviour;
- model-derived result;
- proposed interpretation;
- unresolved policy decision;
- contradiction or missing specification.

Cite exact repository paths, symbols and relevant sections. Inspect the actual source before making claims about implementation or test results.

## 2. Audit §10a operation by operation

For Annul, Void and Correct, check every acceptance criterion against the operation definition, invariants, fact algebra, temporal model and existing oracle.

Create a traceability table containing:

- criterion ID;
- intended business meaning;
- authoritative rule;
- expected resulting facts;
- expected current resolution;
- expected historical resolution;
- expected refusal reason, if applicable;
- executable test or property;
- status: CONSISTENT, AMBIGUOUS, CONTRADICTORY, MISSING or OPEN.

Do not mark a criterion consistent merely because the same wording appears in two documents.

## 3. Formalise the operation interactions

Specifically investigate:

- Annul(Annul);
- Void(Annul);
- Void(Void);
- Annul(Correct);
- Correct(Correct);
- Correct(Annul);
- Correct(Void);
- corrections whose target is no longer effective;
- multiple corrective facts related to the same original fact;
- restoration that would violate a Membership invariant;
- corrections that affect more than one Membership period.

For each interaction, show the initial facts, appended operation, resulting resolution and relevant `knownAt` prefixes.

Use a state-transition table or bounded state exploration where useful. Keep the model bounds explicit. Do not interpret bounded enumeration as a production guarantee or probability.

Do not invent precedence rules. If the ratified corpus does not determine an outcome, mark it OPEN and explain which authority must decide.

## 4. Examine critical ambiguities

### Restoration and precedence

Determine whether restoration operations are true inverses, conditional inverses or independent domain operations. State the precise rule supported by the corpus.

### Correcting an annulled fact

Verify the proposed rule that a correction to an annulled fact is accepted but inert until the annulment is itself annulled. Establish what happens if the fact is voided or receives additional corrections before revival.

### Period absorption — BD-REV-ER

Retain OPEN status. Evaluate the recommendation to refuse correction that silently absorbs a whole live Membership period. Explain the invariant at risk and the consequences of each plausible option. Do not ratify the decision.

### D-COR-1 through D-COR-4

Read the existing decision register and contract definitions. Explain each unresolved decision, its owner, its impact on C-1 and whether a safe, bounded C-1 can proceed without it. Do not infer their meaning from their IDs alone.

### CQ-3 and CQ-9

Keep correction authority and appeals OPEN. Identify where they affect the contract and implementation. Do not invent authority or appeal rules.

## 5. Verify oracle independence and evidence

Inspect the actual oracle and PHP kernel. Correct any inaccurate terminology in the contract.

Document:

- which implementation is the reference model;
- which implementation is under test;
- whether the implementations are independently derived from the specification;
- generated history bounds and operation combinations;
- equality criteria and refusal-reason comparisons;
- metamorphic and historical-reconstruction properties;
- known blind spots, including authority, concurrency, persistence and unsupported fact types.

Where the implementations share assumptions, identify an independent property or specification-derived test that can reduce correlated-error risk.

Do not run tests or modify production code.

## 6. Assess C-1 readiness

Determine whether kernel-level Annul and Void can be implemented without silently deciding unresolved policy.

Provide:

- a precise C-1 scope;
- explicit inclusions and exclusions;
- dependencies and blockers;
- RED-first acceptance tests;
- invariants that must remain true;
- evidence required for completion;
- decisions that must be resolved before C-1 starts.

If C-1 cannot safely proceed, explain exactly why. Do not expand it into Correct, persistence changes or Slice 2 unless the approved contract requires that dependency.

## 7. Controlled document changes

You may make documentation-only corrections to the existing draft when supported by the corpus.

- Do not modify ratified architecture documents.
- Do not convert a proposal into an approved rule.
- Do not close BD-REV-ER, D-COR-1 through D-COR-4, CQ-3 or CQ-9 without an explicit decision.
- Do not edit production code, tests, schema, migrations or configuration.
- Do not run tests, database commands or migrations.
- Do not stage, commit or push.
- Do not modify shared context/session files as a side effect.

Preserve existing work and report every file changed.

## 8. Final deliverables

Return:

1. Principal-reviewer verdict.
2. §10a criterion-by-criterion consistency table.
3. Operation-interaction state-transition table.
4. Formal invariants and historical reconstruction properties.
5. Oracle independence and coverage assessment.
6. BD-REV-ER and D-COR-1 through D-COR-4 decision impact.
7. CQ-3 and CQ-9 dependency analysis.
8. C-1 Definition of Ready and Definition of Done.
9. Prioritised Membership TODOs.
10. Exact documentation changes and unresolved issues.

End with one recommendation: READY FOR C-1, READY WITH EXPLICIT OPEN DECISIONS, or NOT READY FOR C-1. Explain the evidence behind the classification.

Await explicit implementation authorisation after presenting the review. Do not implement C-1 during this mission.

## 4. What I recommend you decide

The excerpt supports a recommendation, but not an automatic approval.

- BD-REV-ER: retain the recommendation to refuse silent whole-period absorption. This protects the integrity of a different still-valid Membership period. Keep the decision open until formally ratified.
- D-COR-1–D-COR-4: first inspect their definitions and dependencies in the full draft; their IDs alone are insufficient for responsible decisions.
- CQ-3 and CQ-9: keep open pending the appropriate authority and governance input.
- C-1: do not authorise implementation solely because §10a was added. First establish whether Annul and Void have fully specified semantics and whether their unresolved dependencies can be safely bounded.

## 5. How far are we from the Membership implementation goal?

The figures below are those reported in the supplied Membership status and earlier research context, not a fresh repository or test run.

| Area                        | Reported status                  | Principal assessment                              |
| --------------------------- | -------------------------------- | ------------------------------------------------- |
| Ratified Membership theory  | Advanced and ratified            | Foundation established                            |
| Admit/End kernel            | 93 unit tests reported green     | Limited to 2 of 9 fact types                      |
| Application layer, Slice 1a | Built and verified               | Reported complete for its defined scope           |
| Persistence, Slice 1b       | Conditionally accepted           | Acceptance and hardening outstanding              |
| Correction model            | Bounded model evidence available | Useful design evidence; implementation unproven   |
| Correction contract         | Draft with §10a added            | Needs semantic consistency review                 |
| Correction implementation   | Not started                      | Critical path                                     |
| Slice 2 application wiring  | Not started                      | Decisions and authority interface remain blockers |
| Migration and cutover       | 0 of 11 gates reported met       | Not ready                                         |
| Production readiness        | Not established                  | Cannot be claimed                                 |

Overall: Membership is beyond the conceptual-design stage, but it has not yet reached an integrated, production-ready implementation. The most important immediate gap is converting the correction rules into a fully specified, independently reviewable and executable contract.

I would not assign a single percentage: there is no defensible weighted denominator for the complete Membership implementation, and such a number would conceal the distinction between domain completeness and production readiness.

## 6. Remaining Membership TODOs

### P0 — Semantic correctness and decisions

- Complete the independent consistency audit of the correction contract.
- Verify all Annul/Void/Correct interactions against the ratified fact algebra.
- Clarify restoration, precedence and historical reconstruction rules.
- Resolve BD-REV-ER through an explicit decision.
- Inspect and resolve D-COR-1 through D-COR-4 with the appropriate owners.
- Determine the exact C-1 dependencies on CQ-3 and CQ-9.
- Approve a precise C-1 contract and its Definition of Ready.

### P1 — Kernel and persistence

- Obtain explicit authorisation for C-1 before implementation.
- Implement the authorised Annul/Void slice using RED-first tests.
- Verify correction lineage, invariants and `knownAt` reconstruction.
- Validate the oracle comparison and add independent property checks.
- Complete the separately authorised PBDIGIT-264 persistence hardening.
- Complete the required formal acceptance of Slice 1b.

### P1 — Application integration

- Resolve the six Slice 2 decisions.
- Complete the authority interface needed for collective committee decisions.
- Implement Slice 2 only after its contract and authorisation are approved.
- Resolve PBDIGIT-265 with the Election owner without conflating Membership with voting entitlement.
- Establish the Finance-owned mapping for Membership-related financial data where required.

### P2 — Migration and production verification

- Map and retire the legacy Membership readers and writers through controlled cutover.
- Implement the write guard, reverse projection and migration machinery.
- Run migration dry runs, parity checks and rollback rehearsals.
- Complete the required isolated-database security work under its own authorisation.
- Satisfy all cutover gates with recorded evidence.
- Demonstrate end-to-end Membership correctness before enabling live use.

The research discipline remains important throughout: use formal logic and bounded computation to find counterexamples, use independent tests to reduce correlated errors, and use statistical or machine-learning methods only where suitable Membership data and a defensible validation design exist. Neither a model nor ML can replace explicit authority, ratified business rules or implementation evidence.

My recommended next action is the semantic consistency audit in the prompt above. It moves the Membership work forward without reopening the accepted foundation or prematurely authorising implementation.&#x20;