## 1. What the attached contract establishes

The document now contains both the original correction contract and a semantic consistency audit in §13. It is substantially more mature than the earlier draft: it identifies specific counterexamples, separates ratified rules from proposals, acknowledges common-mode error in the oracle, and defines a bounded C-1 kernel slice.

The audit reports these key findings:

- D-COR-2: Annul or Void of a fact that is already not live needs an explicit rule. The proposed rule is to refuse it.
- D-COR-5: Annul of an End after re-entry can be refused by INV-9 because extending the earlier period would overlap the later period. This exposes a conflict between the ratified no-consequence semantics and older wording about trimming.
- Oracle limitations: the Python oracle and production kernel share specification ancestry, so agreement alone does not establish independent correctness.
- C-1 scope: kernel-level Annul and Void, excluding Correct, persistence, application authority evaluation and migration.
- C-1 gate: contract approval, D-COR-2 decision, confirmation of the D-COR-5 architectural interpretation, and explicit PO authorisation.

These are grounded in the attached document's §§9 and 13. Source: attached contract, particularly §§13.1–13.8.

## 2. My independent principal-reviewer findings

The audit is valuable, but I found several residual inconsistencies that should be corrected before the contract is approved.

P0

Finding 1 — §10a has ambiguous and contradictory target-liveness wording

Annul criterion (1) says the target is not live. It is unclear whether this describes the target before the operation or its state after the operation. Under the proposed D-COR-2 rule, an already non-live target should be refused, while a successful Annul makes its target non-live.

Void criterion (1) similarly says any earlier fact, including Annul or Void, is accepted, but D-COR-2 proposes refusing operations against targets that are already non-live.

Required correction: distinguish precondition from postcondition. State explicitly when an operation is accepted, when `TargetNotLive` is returned, and which restoration cases remain valid.

P0

Finding 2 — C-1 acceptance criteria include work explicitly excluded from C-1

The shared §10a criteria require persistence round-trips, race tests and tenant scoping for all three operations. But §13.7 excludes persistence and the application layer from C-1, and C-1 implements only Annul and Void.

Required correction: separate kernel acceptance criteria from application, concurrency and persistence acceptance criteria. Keep the latter as obligations for the relevant later slice rather than silently expanding C-1.

P0

Finding 3 — The formal properties need explicit domains

§5(b) describes Annul/Void reversibility broadly, while §13.5 proposes P-EQ, equality between Annul and Void resolution. These properties require preconditions: Annul and Void are not both admissible for every fact, and `Annul(Annul)` has appeal semantics rather than ordinary reversal semantics.

Required correction: specify the set of target facts and accepted histories for each property, and state when the property does not apply.

P1

Finding 4 — D-COR-5 needs a clean separation of architecture and business policy

The architectural behaviour—Annul adds no consequences and G\* rejects invalid post-states—can be specified precisely. Whether an appeal after re-entry should require the later period to be separately annulled or voided is also a consequential business-policy question.

Required correction: preserve the mechanically derived result, but make the appropriate PO/Legal decision explicit wherever the business interpretation remains unresolved. Do not let a principal-reviewer confirmation silently substitute for that decision.

P1

Finding 5 — “Independent reversals commute” needs a stronger definition

Disjoint target IDs do not necessarily imply independent effects on Membership periods. Two operations can affect the same invariant even if their direct targets differ.

Required correction: define independence in terms of dependency chains and affected claims/consequences, and require both operation orders to be admissible before claiming commutativity.

A further, smaller point: the C-1 scope permits Annul of Correct, while Correct is not yet implemented in C-1. The contract must say whether such histories are excluded from C-1 testing and deferred to C-2, or whether C-1 must support pre-existing Correct facts for compatibility. It must not leave this implicit.

## 3. Recommended next step

Do not repeat the entire semantic audit. The document already records that work. Commission a focused contract-reconciliation pass that fixes the residual contradictions, preserves the open decisions and produces a clean, reviewable C-1 contract.

## 4. Claude Code prompt — reconcile the contract and prepare C-1 for review

# Membership Mission — Correction Contract Reconciliation and C-1 Readiness

## Role

Act as a principal DDD architect, Membership requirements engineer, formal-methods researcher, and adversarial reviewer.

Work exclusively in the PublicDigit Membership domain. Treat the current ratified Membership corpus as authoritative. Continue from the existing work; do not restart discovery or repeat the complete audit already recorded in §13.

## Objective

Reconcile the remaining semantic and scope inconsistencies in:

`docs/publicdigit/reviews/2026-10-09-membership-correction-slice-semantic-contract-draft.md`

Produce a contract that can be reviewed for C-1 approval without confusing:

- preconditions with postconditions;
- kernel behaviour with application or persistence behaviour;
- architectural constraints with business-policy decisions;
- bounded model evidence with proof of production correctness.

This is documentation-only work. It does not authorise C-1 implementation.

## 1. Preserve the authoritative baseline

Read the current full contract, including §13, and inspect the referenced ratification act, final proof, decision register, Membership kernel and oracle.

Do not modify ratified architecture documents. Do not close an open decision without explicit authority. Retain BD-REV-ER, CQ-3, CQ-9 and D-COR-1 through D-COR-5 as open wherever the corpus requires it.

Use exact source references. Do not claim tests have been run unless that is independently evidenced. No tests are to be run in this mission.

## 2. Correct the liveness semantics

Reconcile §3, §5, §10a and §13 for D-COR-2.

Define separately:

- whether the target fact is live before the operation;
- whether the operation is admissible;
- whether the target becomes non-live after a successful operation;
- the conditions under which Annul or Void of an Annul/Void fact restores an earlier fact;
- the refusal when a target is already non-live, if D-COR-2 is formally decided that way.

Resolve the ambiguous wording in Annul criterion (1) and the broad “any earlier fact” wording in Void criterion (1).

Check the distinction between `live`, `active` and `superseded`. An inert Correct targeting an annulled fact may itself be live; do not equate an inert correction with a non-live fact.

Keep the decision OPEN until the authorised decision is recorded.

## 3. Correct the scope of acceptance criteria

Separate the criteria into:

### C-1 kernel

- Annul and Void semantics;
- target eligibility and refusal reasons;
- G\* and INV-9 behaviour;
- restoration and rejection cases;
- event semantics;
- bounded differential checks and specification-derived properties;
- historical-prefix stability.

### C-2 kernel

- Correct semantics;
- correction chains and value precedence;
- consequences, trims, clipping and revival;
- INV-EP and INV-COV;
- BD-REV-ER and D-COR-3 dependencies.

### C-3 application

- command handlers;
- authority evidence and CQ-3/CQ-9 dependencies;
- application events and projections;
- statistics and read-model behaviour where applicable.

### C-4 persistence

- schema and reference shape;
- same-Member referential integrity;
- encode/decode round-trips;
- persistence race tests and database isolation requirements;
- PBDIGIT-264 dependency and D-COR-4.

Do not require persistence, tenant-scoping or application-level race tests as C-1 completion criteria if those layers are explicitly excluded from C-1. Preserve them as future obligations in the correct slice.

## 4. Formalise the properties with explicit preconditions

Review P-REST, P-EQ, P-COMM and the metamorphic properties in §5.

For every property, state:

- its input domain and preconditions;
- which operation combinations are admissible;
- whether the result concerns resolution, per-period lifetime, statistics or recorded history;
- whether both operation orders must be accepted;
- the exact conditions under which the property does not apply.

In particular:

- Restrict Annul/Void reversibility to operation combinations where the relevant semantics permit it. Do not apply ordinary reversal semantics to `Annul(Annul)`, which has appeal semantics.
- Restrict P-EQ to target classes and histories for which both operations are admissible.
- Define independence for P-COMM by the relevant fact-dependency graph and affected Membership claims, not merely by different target IDs.
- Preserve the recorded-consequences rule for CFC+; do not substitute replay unless the ratified corpus explicitly permits it.

Use counterexamples to reject overbroad properties. Do not invent new domain rules.

## 5. Resolve the C-1 / Correct-target boundary

C-1 implements Annul and Void, while its target rules include Annul of Correct facts.

Determine from the current architecture whether C-1 can only encounter Admit/End/Annul/Void histories, or whether it must support histories containing Correct facts for compatibility or forward correctness.

Document the decision and its evidence. If the required history class is not yet implemented, state which tests are deferred to C-2. Do not silently remove a ratified target category or silently expand the authorised slice.

## 6. Separate D-COR-5 architecture from policy

Preserve the finding that Annul of an earlier End after re-entry can be refused by INV-9 if it would create overlapping Membership periods and Annul has no consequences.

Separate:

- the mechanically derived kernel result;
- the conflict in older corpus wording;
- the business meaning of an appeal after re-entry;
- the authority responsible for resolving the policy question.

Do not let the principal reviewer alone ratify a business/legal interpretation that belongs to the PO and relevant Legal/Governance decision-makers.

Keep D-COR-5 OPEN until the required decisions are recorded. Do not change the ratified no-consequence semantics merely to make an example pass.

## 7. Reassess C-1 readiness

After reconciliation, provide:

- the exact C-1 scope;
- a criterion-to-rule-to-test traceability matrix;
- a state-transition table for the key restoration and refusal cases;
- a list of blockers and their decision owners;
- a clear Definition of Ready and Definition of Done;
- a verdict: NOT READY, READY WITH EXPLICIT OPEN DECISIONS, or READY FOR APPROVAL.

Do not confuse “ready for approval” with implementation authorisation.

C-1 must remain unimplemented until the contract is approved, D-COR-2 and the required D-COR-5 architectural/business boundary are properly resolved, and explicit PO authorisation is recorded.

## 8. Controlled changes

Documentation only:

- edit the existing draft in place;
- do not create a duplicate contract;
- do not modify ratified documents;
- do not modify source code, tests, schema, migrations or configuration;
- do not run tests or database commands;
- do not stage, commit or push;
- do not change shared context or session logs as a side effect.

Report every file changed.

## 9. Final report

Summarise:

1. The inconsistencies corrected.
2. The rules and evidence supporting each correction.
3. The decisions that remain open and who owns them.
4. The C-1 scope and acceptance criteria.
5. What is deferred to C-2, C-3 and C-4.
6. Remaining Membership TODOs, ordered by dependency.
7. Files changed and confirmation that no code, tests, database, staging, commit or push occurred.

Stop after producing the reconciled draft. Await review and explicit implementation authorisation.

## 5. How far are we from the Membership implementation goal?

The attached document reports the following baseline. These figures are from the corpus, not from a new repository or test run.

| Workstream               | Reported status                                     | Remaining gap                                                             |
| ------------------------ | --------------------------------------------------- | ------------------------------------------------------------------------- |
| Membership domain theory | Ratified                                            | Preserve consistency as new operations are implemented                    |
| Slice 0 kernel           | Admit/End implemented; 93 unit tests reported green | Extend beyond 2 of 9 fact families                                        |
| Slice 1a application     | Built and verified                                  | No new gap established by this attachment                                 |
| Slice 1b persistence     | Conditionally accepted                              | Formal acceptance and hardening                                           |
| Correction model         | Bounded model explored                              | Independent validation and explicit semantic decisions                    |
| Correction contract      | Draft with §13 audit                                | Reconcile residual contradictions before approval                         |
| C-1: Annul/Void          | Not authorised for implementation                   | Resolve D-COR-2 and D-COR-5, approve contract and obtain PO authorisation |
| C-2: Correct             | Blocked                                             | BD-REV-ER, D-COR-3 and validated consequence semantics                    |
| C-3: application         | Blocked                                             | Authority contract and CQ-3/CQ-9 decisions                                |
| C-4: persistence         | Blocked                                             | PBDIGIT-264 and D-COR-4                                                   |
| Migration/cutover        | Not ready                                           | Implement and satisfy all cutover gates                                   |
| Production readiness     | Not established                                     | End-to-end verification and release evidence                              |

My current assessment: the next critical milestone is not yet writing correction code. It is completing a coherent, approved contract for C-1 that does not contradict itself or depend on unresolved policy.

## 6. Remaining Membership TODOs

### P0 — Contract correctness and approval

- Reconcile the liveness wording in §3, §10a and §13.
- Separate C-1 kernel criteria from C-2 correction, C-3 application and C-4 persistence criteria.
- Specify the valid domains of P-REST, P-EQ and P-COMM.
- Clarify whether C-1 must handle Correct facts as targets.
- Decide D-COR-2 and formally record its refusal/restoration semantics.
- Resolve D-COR-5 with the architectural and business-policy owners.
- Obtain principal review and PO approval of the corrected contract.
- Obtain explicit C-1 implementation authorisation.

### P1 — Correction kernel and persistence

- Implement Annul and Void using RED-first tests after authorisation.
- Validate restoration, invariant refusal and historical-prefix behaviour.
- Introduce bounded differential tests and specification-derived properties.
- Resolve BD-REV-ER before implementing Correct.
- Resolve D-COR-3 and define the correctable attributes.
- Complete PBDIGIT-264 and the formal acceptance of Slice 1b.
- Resolve D-COR-4 before the persistence correction slice.

### P1 — Application integration

- Resolve CQ-3, including the authority evidence required for each operation.
- Resolve CQ-9, including the appeal process and relevant decision authority.
- Define and approve the Slice 2 contract and authority interface.
- Implement application command handlers and correction lineage.
- Keep Membership facts separate from Governance authority and Election entitlement.

### P2 — Cutover and production readiness

- Complete correction persistence, round-trip tests and race tests.
- Build the migration and reverse-projection machinery.
- Inventory and safely retire legacy Membership readers and writers.
- Conduct migration dry runs, parity checks and rollback rehearsals.
- Satisfy the eleven reported cutover gates with evidence.
- Demonstrate end-to-end Membership correctness before enabling live use.

### Research discipline

- Keep the ratified specification as the source of truth for oracle validation.
- Recover or reproduce the missing historical model artefacts where possible.
- Use bounded model exploration to find counterexamples, explicitly stating bounds.
- Use independent properties and mutation testing to reduce common-mode error.
- Do not introduce machine learning without a justified Membership data use case, suitable data and a defensible validation method.

The architecture already contains enough substance to make the next step precise. The task now is to close the semantic consistency gap, not to broaden discovery or prematurely start implementation. This preserves the existing ratified Membership model and its correction-before-cutover gate.&#x20;