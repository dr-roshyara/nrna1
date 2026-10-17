# PublicDigit Membership — Principal Reviewer assessment

I reviewed the new Claude session transcript and the updated Membership: PBDIGIT-264 Remediation Plan, Slice 2 Ratification Pack, Cutover Gate, Roadmap. My central conclusion is that the work is progressing in the right architectural direction, but the next step must close the persistence-contract decisions and preserve the authorisation boundary before implementation continues.

The latest session added meaningful evidence: the command-to-fact consistency probe, the transaction-isolation probe, and a stronger Finance model with an independent interval-set oracle. Claude reports that the work is committed as `02a6674d9`, with no production code, migration, or test files changed and nothing pushed. The report also says the repository has other in-progress changes, which Claude left untouched.

## 1. Principal Reviewer verdict

Research and architecture preparation: accept

The work distinguishes tested behaviour, bounded-model evidence, hypotheses, business decisions, and unresolved questions. Retaining the research models as counterexample generators and test-oracle scaffolding is appropriate.

Slice 1b PostgreSQL adapter: accept with conditions recommended

This is not an unconditional acceptance or production-readiness approval. The far-date defect remains real until a regression test proves the fix. Test coverage, adapter parity, command-to-fact integrity, transaction semantics, and clean-checkout regression evidence remain relevant.

Production cutover: not approved

The production database engine remains unverified, migrations have not been rolled out, legacy writers still exist, and Full-membership election eligibility has an unresolved predicate discrepancy.

The key distinction is that a sound domain model, a working persistence adapter, and a safe migration to production are three different achievements. We must not treat success in one as proof of the others.

## 2. Decisions I recommend as Principal Reviewer

These are my architectural review decisions and recommendations, not a substitute for the Product Owner, Legal, Governance, Election, or Finance decisions assigned in the documents.

| Topic                          | Principal Reviewer disposition                              | Reason                                                                                                                                    |
| ------------------------------ | ----------------------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------- |
| Slice 1b                       | Accept with conditions recommended                          | The adapter has useful demonstrated properties, but the outstanding defect and contract gaps must be closed.                              |
| F-4: command-to-fact integrity | Choose F-4b, subject to a migration preflight               | Remove duplicated `fact_id` and `recorded_at` from the command/idempotency table and read them through the referenced fact.               |
| F-5: transaction isolation     | Choose N1                                                   | Reject nested adapter calls before writing anything; enforce `READ COMMITTED` at the start of a top-level append transaction.             |
| F-3: in-memory adapter parity  | Approve the proposed contract clarification                 | A Member-ID collision for another holder is an infrastructure failure with no state change; idempotency-key lookup precedes other checks. |
| DV-1: `DecideAndAppend`        | Require traceable confirmation of the earlier authorisation | The latest report says the authorisation was recorded in session text, but still requests confirmation. Do not infer a missing approval.  |
| PBDIGIT-264                    | Not authorised by this review alone                         | The report assigns implementation authorisation to the Product Owner.                                                                     |
| Slice 2 business rules         | Keep open pending named ratifiers                           | Admission basis, pending-member semantics, End kinds, authority, and valid-date policies are not all adopted.                             |

### Why F-4b is the stronger design

The probe demonstrates that the current schema accepts inconsistent command rows. F-4a, which links only the organisation, membership and sequence, still accepts a row with a wrong `fact_id` or `recorded_at`. F-4c can enforce the full relationship but introduces a wider key and additional index.

F-4b is preferable because it removes the duplicated data that creates the inconsistency class. The fact is authoritative; the idempotency record should not independently maintain copies of its identity and timestamp.

However, Claude must first verify the actual data in every relevant database before applying a migration. The assertion that rows are consistent by construction is not a substitute for a migration preflight. The migration must also have a tested rollback path.

### Why N1 is preferable

The report's probe shows that setting transaction isolation fails inside an outer transaction, and a nested transaction also weakens the meaning of `Appended`: the outer transaction can still roll back.

N1 preserves a clearer contract:

- The adapter accepts only top-level append calls.
- The isolation level is enforced rather than assumed.
- A successful `Appended` outcome means the adapter's transaction committed.
- A nested call fails before any write.
- The interaction with `PBDIGIT-263` is documented, so a later test-isolation change does not accidentally break adapter tests.

These choices should be recorded as explicit review dispositions. They must not silently settle the separate business decisions.

## 3. Research review: what the models teach us

The new research is useful because it tests whether apparently reasonable implementation strategies survive corrections, re-entry, and historical changes. The figures below are counts of enumerated model histories, not real-world probabilities or production incident rates.

### A. Membership is a resolved history, not a mutable status

Research implication

A correction to an earlier fact can change the resolved membership periods. Re-entry must create a new Membership Period under the same Member aggregate. The current state should therefore be derived from authoritative facts, rather than trusted as an independently maintained status field.

Architecture direction: retained facts → deterministic resolution → derived predicates.

### B. Finance must reconcile changes without rewriting closed history

# 42,075

enumerated histories

The revised model reports that an append-only ledger with adjusting postings matched an independently implemented interval-set oracle per service month in every generated history and did not mutate closed postings. The legacy stored-charge approach disagreed in 38,106 histories; naive in-place recomputation would have altered closed months in 14,433.

Interpretation: this is promising evidence for the reconciliation mechanism, not proof that a complete Finance domain is correct. Refund settlement, reinstated charges, non-linear fee rules and other omitted behaviours still require testing.

### C. Access and membership are different concepts

The event-maintained access list diverged from derived access in 180 of 432 generated histories. This supports treating access as a rebuildable projection, rather than as authoritative membership or governance evidence.

A member's organisational belonging does not, by itself, settle access to every committee, forum, document or meeting. Those decisions require the relevant service policy and, where applicable, Governance authority.

### D. Lapse and suspension are not interchangeable

The model found differences between lapse and suspension in 110 of 540 histories with re-entry, versus none in the 432 histories without re-entry.

This makes re-entry semantics a real design discriminator. The model does not decide the business meaning of suspension; that still requires an explicit rule and ratification.

### The most important research improvement still needed

The README explicitly warns that the generators include histories the PHP domain kernel would refuse. That weakens the interpretation of aggregate results: some apparent counterexamples may describe impossible domain histories.

The next research step should be to filter generated histories through the actual PHP kernel's admissibility rules, without changing those rules to make the model pass.

The research process should:

1. Generate candidate histories independently.
2. Ask the existing kernel whether each history is admissible.
3. Keep rejected histories separate, with reasons where possible.
4. Compare the candidate designs against an independently implemented oracle on admissible histories.
5. Retain counterexamples, assumptions, and model boundaries.
6. Reconcile every discovered semantic disagreement with an adopted rule before updating the oracle.

The earlier disagreement involving a correction of an annulled End illustrates why this matters: a model disagreement is useful evidence of an unresolved semantic issue, not permission to silently alter the domain rules.

Machine learning may later help classify migration exceptions, identify duplicate candidates, or prioritise anomalous histories for human review. It must not determine membership legitimacy, governance authority, or voter eligibility. Deterministic domain rules remain authoritative.

## 4. Copy-ready instructions for Claude: next step

I recommend a review-and-research step while the Product Owner decision remains open. It advances the theory and closes documentation gaps without accidentally authorising persistence changes or production implementation.

# Claude Commission — PublicDigit Membership: Principal Review Closure and Admissibility Research

## Role

Act as Senior DDD Architect, Principal Reviewer, Requirements Engineer, formal-methods researcher, mathematician, statistician, and software verification engineer.

Treat the existing PublicDigit corpus as research evidence and brainstorming material. Your job is to derive a robust, implementable Membership theory while preserving the distinction between adopted decisions, demonstrated behaviour, bounded-model results, hypotheses, and unresolved business questions.

The Principal Reviewer is the architecture decision reviewer. Product Owner, Legal, Governance, Election, Finance, and Operations retain their assigned decision rights.

## 1. Establish repository state before doing anything

1. Inspect the current branch, HEAD, working-tree status, staged changes, and relevant session/context documents.
2. The latest reviewed report identifies HEAD `02a6674d9`; verify the actual current HEAD instead of assuming it has not changed.
3. Inspect the latest versions of:
   - `docs/publicdigit/reviews/2026-10-09-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md`
   - `docs/publicdigit/reviews/2026-10-09-participation-semantic-contract-draft.md`
   - `docs/publicdigit/backlog/PBDIGIT-264-membership-slice1b-acceptance-follow-ups.md`
   - `docs/publicdigit/membership/architecture/models/README.md`
   - The Slice 0, Slice 1a, and Slice 1b contracts, implementation reviews, and developer guides.
4. Reconcile the documents against the actual repository. Do not trust session recaps over files or executable evidence.
5. Preserve all work belonging to other sessions. Do not reset, clean, stage, amend, or commit unrelated changes.

## 2. Record the Principal Reviewer's disposition

Update the existing decision pack without creating a second competing source of truth.

Record these dispositions explicitly:

- Slice 1b: **ACCEPT WITH CONDITIONS recommended**, not production-ready.
- F-4: select **F-4b**, subject to migration preflight and rollback verification. Remove duplicated `fact_id` and `recorded_at` from the command/idempotency table and obtain them through the referenced fact.
- F-5: select **N1**. Reject nested adapter calls before writing; enforce `READ COMMITTED` at the beginning of a top-level append transaction.
- F-3: accept the contract clarification that a Member-ID collision belonging to another holder is an infrastructure failure with no state change, and that idempotency-key lookup precedes other checks.
- DV-1 (`DecideAndAppend`): verify the authoritative approval record. If the earlier Product Owner approval cannot be established from the corpus, mark it as requiring explicit confirmation. Do not infer approval from a Claude recap.
- Confirm the research-model location as `docs/publicdigit/membership/architecture/models/`.

For F-4b, require a read-only preflight that checks for existing command rows, orphaned references, and inconsistent copied values before any migration. Do not assume the database is empty or consistent. Verify that the migration's `up()` and `down()` preserve data and restore the intended schema.

For F-5, document and test the interaction with PBDIGIT-263. Do not implement a test-isolation fix as part of this commission.

Keep the Product Owner's authorisation for PBDIGIT-264 separate from the Principal Reviewer's technical dispositions. This commission does **not** authorise PBDIGIT-264 implementation.

## 3. Conduct the next formal research step: kernel-admissible histories

The existing research models include histories the Membership kernel would refuse. This is a priority limitation.

Inspect the actual PHP kernel API and the research scripts before choosing an approach. Do not invent an API or modify domain semantics to fit a generator.

Within the research-only area `docs/publicdigit/membership/architecture/models/`:

1. Define how candidate histories are translated into the kernel's existing input types.
2. Use the actual kernel as an admissibility filter, if this can be done without modifying production code or changing adopted contracts.
3. Separate accepted and rejected histories. Record the rejection reason when it is available without changing the kernel.
4. Re-run the relevant Membership, Finance, access, vacancy, lapse/suspension, and re-entry models on admissible histories.
5. Preserve an independent oracle. Do not implement the oracle by calling the same resolver whose correctness is being evaluated.
6. Check whether the earlier findings survive the filter. Distinguish:
   - counterexamples on admissible histories;
   - counterexamples only on rejected histories;
   - bounded agreement within the tested domain;
   - unresolved semantic disagreements;
   - cases not represented by the model.
7. Retain deterministic reproduction commands, assumptions, finite-domain limits, and model results in the README.
8. Do not report enumerated counts as probabilities or as production defect rates.
9. Do not silently modify a ratified rule when the model and oracle disagree. Document the minimal counterexample, identify the semantic question, and stop that branch until the governing rule is resolved.

If an admissibility filter cannot be built without a production change, do not change production code. Document the missing interface and propose the smallest separately authorised research solution.

## 4. Falsify the Finance model further

The existing model reports agreement across 42,075 generated histories for the append-only adjustment-ledger design. Continue testing, not merely confirming, that result.

Investigate at least:

- refund settlement followed by a correction that reinstates a charge;
- partial payments and credits across corrections;
- pro-rata and non-linear fee rules;
- accounting-period close boundaries;
- duplicate, reordered, or retried adjustment commands;
- the effect of annulment and correction under BD-REV;
- histories accepted by the kernel versus histories it rejects.

Use an independently implemented oracle and retain minimal counterexamples. If the assumptions cannot model a case, label it unmodelled rather than claiming success.

Do not implement the Finance ledger in production.

## 5. Preserve the bounded-context boundaries

Do not change the adopted Membership architecture.

Keep these distinctions explicit:

`Person ≠ OrganisationAffiliation ≠ Member ≠ MembershipPeriod ≠ ElectionRegistration ≠ Admission ≠ VotingEntitlement ≠ Exercisability`.

- Member represents the recognised organisational relationship.
- Re-entry creates a new Membership Period under the same Member aggregate.
- Membership does not itself grant voting entitlement.
- Election-Only mode must remain independent of the Membership ledger.
- Governance owns positions, tenures, mandates, authority, duties, and committee participation.
- Finance owns charges, payments, credits, settlement, and accounting.
- Service contexts own access policies and rebuildable access projections.
- Audit consumes authoritative evidence and must not become an alternative authority source.
- Do not introduce Event Sourcing or a shared cross-context persistence/kernel.
- Do not let machine learning determine membership legitimacy, authority, access rights, or voter eligibility.

Keep the Participation Semantic Contract explicitly conditional on its required decisions and approvals.

## 6. Maintain the legacy cutover gates

Do not implement cutover machinery in this commission. Preserve and refine the existing C-0 through C-10 gates.

In particular, make sure future implementation plans include:

- a reproducible architecture fitness test that detects unapproved writers to legacy `members` data;
- explicit ownership of membership-derived columns versus Finance-owned columns;
- a database-level write guard for mass updates and relation-builder deletes;
- a single, rebuildable reverse projection from the Membership ledger;
- reader-to-predicate mapping and resolution of PBDIGIT-265;
- migration dry runs, attested history, exception reports, parity criteria, write freeze, and rehearsed rollback;
- an explicit test that Election-Only mode never consults the Membership ledger;
- verified deployment database engine and staged migration rollout.

Treat the Full-membership eligibility mismatch as a cutover blocker for any organisation whose voting eligibility could change. It does not prevent isolated research or separately authorised Membership work.

## 7. Evidence and reporting requirements

Classify every conclusion as one of:

- ADOPTED / RATIFIED
- DEMONSTRATED BY TEST OR PROBE
- BOUNDED-MODEL RESULT
- COUNTEREXAMPLE
- HYPOTHESIS / RECOMMENDATION
- BUSINESS DECISION REQUIRED
- NOT VERIFIED

For every material claim, identify the source file, test, command, or model and the assumptions under which it holds.

Do not call a dirty-working-tree regression run a clean-checkout regression. Do not claim the full suite is green unless a clean, reproducible run establishes that.

Update the relevant review document, model README, and session/context record. Avoid duplicated or drifting decision records.

## 8. Stop conditions

This commission authorises **review documentation and research-only artefacts** within the stated research directory.

It does not authorise:

- PBDIGIT-264 production adapter changes;
- application or handler changes;
- migrations or migration rollout;
- PBDIGIT-261, PBDIGIT-262, or PBDIGIT-263;
- Slice 2 command wiring or live entry points;
- Governance, Finance, Audit, or Participation production implementation;
- changes to adopted domain rules;
- pushing changes.

At completion, report:

1. repository state and files changed;
2. Principal Reviewer dispositions recorded;
3. research results before and after admissibility filtering;
4. counterexamples and unresolved semantics;
5. test commands and exact outcomes;
6. remaining decisions by accountable role;
7. the smallest next implementation slice and its prerequisites.

Then STOP and await explicit authorisation for the next implementation step.

## 5. Where we are against the Membership implementation goal

I would not assign a single percentage. A percentage would obscure the difference between completed domain foundations and the substantial integration, migration, and governance work that remains.

## Implementation status

Domain foundation — implemented

Slice 0 (`PBDIGIT-258`) provides the pure-PHP semantic kernel and resolution logic. Its recorded verification includes unit, architecture, exhaustive bounded-oracle, property, metamorphic, and mutation-sensitivity checks.

Application/repository contract — implemented

Slice 1a (`PBDIGIT-259`) establishes the repository port, Admit/End handlers, authority port, idempotency outcomes, and concurrency contract.

PostgreSQL persistence — implemented, acceptance pending

Slice 1b (`PBDIGIT-260`) has a PostgreSQL adapter and migration definition. The acceptance recommendation is conditional; F-1 remains a confirmed defect pending its regression-proven fix, and other contract gaps remain.

Command wiring, cutover and live application integration — not complete

Slice 2 and subsequent cutover work remain blocked by decisions and implementation gates. There is no basis in these reports to call Membership production-ready.

The right overall assessment is: the theoretical and technical foundation is substantially established; a safe, integrated, production Membership capability is not yet achieved. The next milestone is not simply “finish Membership”; it is to close the persistence contract, authorise and complete the remediation, ratify the necessary domain rules, and then implement and prove the cutover path.

## 6. Remaining TODOs, in dependency order

### P0 — Close the current review and authorisation boundary

- Record the Principal Reviewer disposition for Slice 1b: accept with conditions, with explicit outstanding conditions.
- Confirm whether DV-1 (`DecideAndAppend`) has an authoritative Product Owner approval.
- Record F-4b and N1 as the selected technical approaches, subject to the required review/document updates.
- Record the F-3 contract clarification and add shared contract tests for both adapters.
- Run the research-only admissibility-filter investigation; preserve the independent oracle.
- Obtain explicit Product Owner authorisation before implementing PBDIGIT-264.

### P1 — Complete PBDIGIT-264 after authorisation

- F-1: reject unsupported date ordinals before any write; test upper and lower bounds and verify the Member remains loadable.
- F-5: implement robust PostgreSQL unique-constraint classification and enforce the chosen isolation contract.
- F-3: correct in-memory adapter behaviour and verify parity with PostgreSQL.
- F-4b: normalise command/idempotency data to reference authoritative facts; verify existing data, migration, and rollback.
- F-2: add concurrent Admit retry and cross-Member idempotency-key tests, including mutation-sensitivity evidence.
- Run the relevant tests repeatedly, document exact results, and obtain a fresh Principal Reviewer decision.
- Establish a clean-checkout regression baseline; keep known legacy failures separate from new failures.

### P2 — Ratify Slice 2 domain semantics

- D-1: agree admission basis with the Product Owner and Legal adviser.
- D-2: ratify whether a pending Member exists; the current recommendation is that an application remains outside the Member aggregate.
- D-3: ratify End kinds and required procedures with the Product Owner and Legal adviser.
- D-4: obtain Governance approval for any interim authority adapter.
- D-5: ratify backdating, future dating, and any narrower business date range. Keep the technical date-representation limit distinct from a business rule.
- Approve the Slice 2 contract before implementing Admit/End command wiring.

### P3 — Resolve election and legacy cutover risks

- Resolve `PBDIGIT-265`: establish the intended Full-membership eligibility predicate, one shared test matrix for single and bulk paths, and runtime impact.
- Obtain Finance's signed column-ownership map for `fees_status`, `fee_state`, and other columns.
- Build the reproducible writer fitness test (C-0).
- Define the per-organisation source-of-truth flag and cutover policy.
- Route, disable, or skip every legacy membership writer for cutover organisations.
- Implement and test the database-level write guard for bulk updates and relation deletes.
- Implement the single reverse projection and prove rebuild = incremental.
- Map each legacy reader to its required domain predicate.
- Perform organisation-by-organisation dry runs, report exceptions, prove parity, freeze writes, and rehearse rollback.
- Keep Election-Only organisations outside the Membership-ledger cutover and test that independence explicitly.
- Verify the actual production database engine and rollout status before deployment.

### P4 — Complete Membership lifecycle and application integration

- Implement remaining lifecycle slices: renewal, type changes, suspension/restoration, membership numbering, correction, annulment, and voiding—each under its relevant ratified contract.
- Wire application approval to Admit and the approved ending workflow to End.
- Implement HTTP/UI entry points only after the relevant organisation passes cutover gates.
- Test organisation isolation, idempotency, concurrency, history reconstruction, and authorisation end to end.
- Retire legacy paths only after parity, rollback, and operational gates are satisfied.

### P5 — Complete adjacent contexts and technical debt

- Ratify RD-1–RD-4 and resolve the relevant Governance questions before implementing Participation.
- Continue Finance-ledger falsification for refunds, credits, non-linear fees, and closed periods before implementation.
- Define Audit consumption of authoritative facts without giving Audit ownership of membership or governance decisions.
- Resolve `PBDIGIT-261` (database engine consistency), `PBDIGIT-262` (legacy MySQL branches), and `PBDIGIT-263` (test isolation) through their own authorised work.
- Resolve remaining participation questions BQ-P1–P11, with accountable business owners and legal/statutory evidence where required.
- Complete operational, security, migration, rollback, and full-regression verification before declaring the Membership capability production-ready.

My recommended next action: use the Claude commission above to close the review record and improve the research model while preserving the implementation gate. Once the Product Owner explicitly authorises PBDIGIT-264, Claude can execute the ordered remediation without prematurely wiring Membership into the live application.&#x20;