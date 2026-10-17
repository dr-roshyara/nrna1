# Principal Reviewer report: PublicDigit Membership

I have reviewed the attached session record, including the PBDIGIT-264 remediation plan, the Slice 2 ratification pack, the legacy cutover checklist, the Participation Semantic Contract draft, and the new PBDIGIT-265 Election investigation.

My verdict: the research and planning have advanced substantially, but the implementation must remain at its current gate until the required approvals are explicit. Claude reports that the planning documents were committed as `27f57aec5`, with no implementation code changed and nothing pushed.&#x20;

Eingefügter Text.txt



## 1. Principal Reviewer verdict

Research and decision-pack work: accepted as useful working material

The report distinguishes implementation facts, test assumptions, open decisions, and recommendations. The Participation contract is correctly marked as a draft, not approved architecture.

Slice 1b: ACCEPT WITH CONDITIONS recommended

This is my review recommendation, not a formal acceptance act. The date-range defect and persistence-hardening gaps remain. The new persistence layer is not ready for live use.

Implementation gate: STOP pending authorisation

PBDIGIT-264 has not started. Slice 2, legacy cutover, HTTP/UI, and Governance implementation must not begin merely because their plans exist.

The attached report is unusually useful because it corrects its own earlier claims rather than defending them. It identifies the legacy inventory as approximately 36 reader files and 13 writer paths, corrects the earlier estimate of 23 readers, and distinguishes an actual defect from missing test coverage.&#x20;

Eingefügter Text.txt



### The immediate technical issue

The most important known defect is the far-date round-trip failure: the adapter can accept and persist a date such as `10000-01-01`, but a later read cannot parse it. That means a successfully appended record can make its Member aggregate unloadable.

The proposed fix is to reject unsupported dates before any persistence write, with tests proving that no fact, command key, or version change is committed on rejection. The report proposes the adapter's supported representation range as `0001-01-01` through `9999-12-31`.&#x20;

Eingefügter Text.txt



One important DDD qualification: a persistence representation limit is not automatically a domain rule. Keep the technical round-trip limit in the adapter. If the organisation needs a narrower business-valid date range, validate it at the appropriate application/domain boundary only after the business rule is ratified.

## 2. What the new research tells us

### A. Membership's core architecture remains sound

The research does not establish a reason to redesign the `Member` aggregate. It instead reinforces the need to keep several kinds of truth separate:

- Membership owns the recognised organisational relationship and its derived temporal predicates.
- Governance owns participation, positions, tenure, mandates, authority, and duties.
- Finance owns charges, payments, credits, and settlement.
- Each service owns its access policy and any rebuildable access projection.
- Election evaluates voter eligibility according to its own adopted rules and consumes Membership information only in Full Membership mode.

This preserves the adopted meaning of organisational membership: the `Member` aggregate represents the organisation's membership relationship. A committee position or service permission is not a second membership identity.

### B. The bounded computations are useful—but not proofs of real-world frequency

The new research reports:

| Modelled question                      | Result in the bounded model        | What it supports                                                                   |
| -------------------------------------- | ---------------------------------- | ---------------------------------------------------------------------------------- |
| Stored charges after corrections       | 140 of 222 histories were wrong    | The tested legacy-style charging strategy can diverge after corrections.           |
| Credit after correction                | 70 of 222 histories                | Finance must represent credits; refund policy remains a separate decision.         |
| Event-maintained access projection     | Divergence in 180 of 432 histories | The tested access design is not reliably rebuildable from the authoritative facts. |
| Lapse versus suspension after re-entry | Divergence in 110 of 540 histories | The two policies are observably different when re-entry is possible.               |

These counts are from small, deliberately bounded models, not estimates of the probability of an error in production. The report correctly notes that the derived charge matches the model's final target by construction; that result is not independent evidence of correctness.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



My recommendation is to retain the models as counterexample generators and test-oracle scaffolding, then strengthen them with independent specifications and more realistic scenarios: partial payments, closed accounting periods, waivers, pro-rating, multiple amendments, and rules that apply differently before and after an accounting period closes.

### C. Participation research has found important distinctions

The draft correctly separates:

- Authority: what a person is permitted to do.
- Duty: what a person must do or remain accountable for.
- Charge: an amount due under a financial rule.
- Payment: a settlement fact.
- Access: a policy-derived permission to use a service.

For example, delegating signing authority does not necessarily transfer the chair's accountability. Ending a tenure need not automatically end every duty associated with it. These are strong counterexamples against a single generic `role/status/obligation` model.&#x20;

Eingefügter Text.txt



However, these are still research hypotheses, not ratified statutory rules. In particular, whether duties can survive a tenure, how discharge works, whether former members can read historical forum content, and how vacancy and succession operate require explicit policy decisions. The draft itself records these open questions.&#x20;

Eingefügter Text.txt



### D. PBDIGIT-265 must be treated as a potentially serious Election defect

The inventory reports that the bulk Full Membership voter-list path checks expiry and the membership type's voting rights, while the per-person eligibility decision does not check the same predicates. Runtime impact is not yet verified, and the issue may overlap existing Election work.&#x20;

Eingefügter Text.txt



My review position:

- Do not silently fix it as part of Membership work.
- Ask the Election owner to reconcile the two predicates against the adopted Election entitlement decisions.
- Test both the bulk-list and individual-decision paths against the same independently defined examples.
- Treat the issue as a cutover blocker for affected Full Membership organisations until the intended semantics and parity are established.

It need not block isolated Membership command work that cannot affect a live voter decision, but it must block any migration that could change voter eligibility.

## 3. Additional challenges I would raise before approving the plan

These are principal-review observations beyond the issues already identified by Claude.

1\. The proposed minimal foreign key may not guarantee full command/fact consistency. The report's F-4a proposes a composite foreign key from `(organisation_id, membership_id, seq)` to the fact sequence. This prevents a command row from referring to a nonexistent sequence, but, if `fact_id` and `recorded_at` remain duplicated in the command row, it does not necessarily prove that those copied values match the referenced fact.&#x20;

Eingefügter Text.txt

&#x20;Before approving F-4a, require a test for a valid sequence paired with the wrong `fact_id`. Either constrain the complete relationship or use the normalised join-based design, with the schema change explicitly approved.



2\. Do not assume READ COMMITTED merely because it is PostgreSQL's default. The report correctly identifies this assumption. The adapter should enforce its required isolation level or demonstrate the required behaviour at every supported level. The proposed explicit isolation setting must be tested through the real Laravel transaction path, including the concurrent retry scenarios.&#x20;

Eingefügter Text.txt



3\. Do not overstate the legacy inventory's completeness. Approximately 36 reader files and 13 writer paths are valuable findings, but the cutover must use a reproducible inventory and tests, not just counts. The two bulk updates that bypass model events are especially important because an application-level guard alone cannot protect against them.&#x20;

Eingefügter Text.txt



4\. Keep the Participation draft conditional. Its own exit criteria require ratification of RD-1 through RD-4, answers to specified business questions, and Principal Reviewer approval. It must not become an implementation contract merely because it contains formal predicates or computed results.&#x20;

Eingefügter Text.txt



5\. Keep historical facts distinct from current projections. An append-only Membership fact history and a derived current membership view solve different problems. The same principle can inform Finance and service-access design, but each context needs its own accounting, retention, correction, and rebuild semantics. Reusing a pattern is not a reason to copy the entire Membership implementation into another context.

## 4. Copy-ready next-step prompt for Claude

The correct immediate next step is an approval-gated execution step, not an automatic start on Slice 2. The prompt below tells Claude to preserve the work already done, sharpen the remaining technical questions, and wait for explicit authorisation before changing code.

# PublicDigit Membership — Principal Review Gate and Authorised Next Step

## Role and objective

Act as a Senior DDD Architect, Principal Reviewer, Requirements Engineer, formal-methods researcher, mathematician/statistician, and careful implementation engineer.

The objective is to achieve a robust, logically consistent, practically deployable Membership capability without undermining existing PublicDigit decisions, Election-Only mode, tenant isolation, or bounded-context ownership.

The attached report and the current repository are the evidence base. Treat earlier architecture documents as research and decision evidence according to their recorded status; do not treat every proposal as adopted.

## 1. Preserve the current state

Start with a read-only preflight:

1. Inspect Git status, the current branch, staged and unstaged changes, recent commits, and concurrent-session work.
2. Read:
   - `docs/publicdigit/reviews/2026-10-09-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md`
   - `docs/publicdigit/reviews/2026-10-09-participation-semantic-contract-draft.md`
   - `docs/publicdigit/reviews/2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md`
   - `docs/publicdigit/reviews/2026-10-09-membership-slice1b-contract.md`
   - `docs/publicdigit/backlog/PBDIGIT-264-membership-slice1b-acceptance-follow-ups.md`
   - `docs/publicdigit/backlog/PBDIGIT-265-full-membership-eligibility-bulk-and-single-predicates-differ.md`
   - the relevant Slice 0/1a/1b contracts, guides, code, and tests.
3. Verify that the repository still matches the reports. Record discrepancies instead of assuming the reports are current.
4. Preserve uncommitted work from other sessions. Do not reset, clean, overwrite, or stage unrelated changes.

## 2. Respect the current authorisation boundary

The report recommends ACCEPT WITH CONDITIONS for Slice 1b. It does not establish formal Principal Reviewer acceptance.

PBDIGIT-264 is OPEN and has not been authorised. No implementation work may begin until I explicitly authorise it.

Until then:

- Do not edit production code or migrations.
- Do not run migrations against development or production.
- Do not begin Slice 2, migration cutover, HTTP/UI, Governance, or Participation implementation.
- Do not push commits.
- You may perform read-only verification, refine the review, prepare tests and decision material, and report risks.

At the end, state exactly which approvals are still missing. Do not interpret this prompt as approval to implement PBDIGIT-264.

## 3. Finalise the Slice 1b acceptance analysis

Provide a concise classification of:

- Demonstrated correctness;
- Correctness conditional on assumptions;
- Confirmed defects;
- Test coverage gaps;
- Deferred infrastructure risks;
- Business decisions;
- Deployment blockers.

Retain the current finding that the far-date defect is real until a regression test proves otherwise.

Explicitly verify:

- Supported calendar-date range and round-trip behaviour;
- Idempotency-key precedence and identical concurrent retries;
- Tenant-scoped identity and append atomicity;
- Repository contract parity;
- Transaction isolation and autocommit assumptions;
- Fact/command referential integrity;
- Migration and production-database readiness;
- The actual limitations of the regression baseline.

Do not describe a dirty-working-tree comparison as a clean-checkout baseline. Do not claim the full test suite is green.

## 4. Strengthen PBDIGIT-264 before implementation authorisation

Keep the approved remediation scope narrow and follow the recorded order:

1. F-1: reject unsupported dates before persistence.
2. F-5: identify PostgreSQL constraint violations reliably and enforce the required transaction isolation.
3. F-3: align the in-memory adapter with the PostgreSQL repository contract.
4. F-4: resolve command-to-fact referential integrity.
5. F-2: add the missing concurrent-retry coverage and demonstrate its sensitivity.

### Mandatory review question for F-4

The current minimal option adds a foreign key for `(organisation_id, membership_id, seq)` while the command row also stores `fact_id` and `recorded_at`.

A valid sequence does not necessarily prove that the copied `fact_id` and `recorded_at` are correct.

Before recommending F-4a:

- Add a proposed negative test in which the sequence exists but the command row supplies the wrong fact ID.
- Establish whether the database rejects it.
- If it does not, propose either a composite relationship that constrains the complete reference or the normalised join-based alternative.
- Explain the schema and migration consequences.
- Do not silently alter the approved schema. Request the Principal Reviewer's explicit choice.

### Mandatory review question for F-5

Do not merely document READ COMMITTED as an assumption. Determine how the Laravel/PostgreSQL adapter will enforce its required isolation and how the tests demonstrate that enforcement. Verify the exact first statement in the transaction and test the relevant concurrent retry cases.

Do not change `DecideAndAppend`, the domain kernel, handler signatures, or repository port unless the authorisation explicitly permits it.

For test gaps where the implementation is believed correct, do not fabricate a RED-first history. Demonstrate test sensitivity with a deliberate mutation, record the result, and revert the mutation.

## 5. Protect the legacy migration boundary

Use the read-only inventory as a starting point, not as proof that every writer and reader has been found.

Maintain the ten cutover gates C-1 through C-10. Give special attention to:

- Writers that bypass model events;
- Bulk updates and relation-builder deletes;
- Membership-derived versus Finance-owned columns;
- The `fees_status` / `fee_state` discrepancy;
- Import regeneration of membership numbers;
- Reverse-projection rebuild parity;
- A per-organisation source-of-truth flag;
- A rehearsed rollback and explicit write freeze.

Do not expose a live Admit/End entry point before the applicable cutover gates are met.

## 6. Treat PBDIGIT-265 as an Election-owned investigation

The report identifies a potential difference between bulk voter-list eligibility and individual voter eligibility for Full Membership mode.

Do not fix it as part of Membership work.

Ask the Election owner to:

- Check for an existing adopted decision or duplicate backlog item;
- Establish the intended predicate from the existing Election architecture and requirements;
- Compare bulk and individual paths against one independent test matrix;
- Verify expiry, membership-type voting rights, fee standing, and other adopted entitlement predicates;
- Record the runtime impact and cutover implications.

Do not change Election entitlement semantics without the responsible owner's approval. Treat unresolved predicate inconsistency as a blocker for cutover where it could affect voter eligibility.

## 7. Continue Membership theory and Participation research

Preserve the adopted Membership architecture:

- Organisation Membership is represented by the `Member` aggregate.
- Re-entry creates a new Membership Period under the same Member.
- Membership facts are authoritative evidence; current predicates are derived.
- Membership does not itself grant voting entitlement.
- Election-Only remains independent of Membership.
- Governance owns participation, positions, tenures, mandates, authority, and duties.
- Finance owns charges, payments, credits, and settlement.
- Service access is derived through each service's policy and remains a rebuildable projection.

Continue to treat the Participation Semantic Contract as a conditional draft.

For G-1 through G-5, distinguish:

- Demonstrated counterexamples;
- Bounded-model results;
- Hypotheses;
- Ratified rules;
- Unanswered business questions.

Extend the models where useful with independent oracles and realistic cases, especially:

- Multiple corrections and annulments;
- Partial payments and closed accounting periods;
- Refund and credit policies;
- Delegation and discharge of duties;
- Former-member access to historical content;
- Re-entry, lapse, suspension, vacancy, and succession.

Do not describe enumerated counts as production probabilities. Do not treat a model that agrees with its own definition as independent evidence.

Machine learning may assist with candidate role mapping, duplicate detection, or migration triage only. It must not determine membership legitimacy, authority, duties, voting eligibility, or access rights.

## 8. Required output and stop condition

Return:

1. Final Slice 1b acceptance recommendation and evidence;
2. The refined PBDIGIT-264 plan, including the F-4 consistency challenge;
3. The exact approvals needed from the Product Owner, Principal Reviewer, Governance owner, legal adviser, and Election owner;
4. The updated cutover risk list;
5. The Participation research status and remaining falsification questions;
6. A dependency-ordered roadmap and concise TODO list.

Then STOP.

No implementation, migration rollout, or push until I explicitly authorise the next action.

## 5. How far are we from the Membership implementation goal?

My assessment is that we have a functioning technical foundation, a stronger domain theory, and a substantially better migration plan—but not yet a complete, production-ready Membership capability.

I would measure progress by completed capabilities and verified gates rather than inventing a percentage. The next milestone is not another broad research round: it is closing the persistence acceptance gate and obtaining the decisions that permit controlled implementation.

## Current milestone assessment

Domain kernel and application foundation

Slice 0 and Slice 1a are implemented; Slice 1b has a PostgreSQL adapter and test evidence.

Persistence acceptance

Conditional acceptance recommended; PBDIGIT-264 is awaiting authorisation and remediation.

Business rules and application workflows

Admission basis, pending-member semantics, ending kinds, authority, and date policies remain open; Slice 2 is blocked.

Safe production integration

Legacy cutover gates, voter-eligibility reconciliation, migration parity, rollback, and deployment verification remain.

Complete organisational capability

Renewal and correction lifecycle, Governance participation, Finance, Audit, service access, and legacy retirement are still future work.

## 6. Remaining TODOs — in dependency order

### P0 — Acceptance and authorisation

- Principal Reviewer: formally decide Slice 1b acceptance and confirm the recorded authorisation for the minimal `DecideAndAppend` fix.
- Product Owner: explicitly authorise PBDIGIT-264.
- Decide whether F-4 uses the minimal foreign key or a normalised relationship; ensure the design also prevents a valid sequence from being paired with the wrong fact ID.
- Approve the in-memory/PostgreSQL repository contract clarification.
- Confirm the supported date range and the distinction between technical date representation and business-valid dates.

### P1 — Complete PBDIGIT-264

- Fix the far-date defect with pre-persistence validation and regression tests.
- Harden PostgreSQL constraint detection and explicitly enforce/test transaction isolation.
- Align in-memory and PostgreSQL adapter behaviour.
- Enforce complete command-to-fact referential integrity.
- Add concurrent identical-Admit and cross-Member idempotency-key tests.
- Verify rollback, tenant isolation, and all existing contract tests.
- Record mutation-sensitivity evidence and accurately document regression-baseline limitations.
- Obtain a fresh Principal Reviewer decision after the remediation.

### P2 — Ratify the rules needed for Slice 2

- PO and appropriate legal advice: decide the admission-basis and consent rules.
- PO: ratify whether pending applications remain outside the `Member` aggregate.
- PO and legal adviser: ratify the permitted ending acts and distinguish ending acts from grounds and term lapse.
- Governance owner through the PO: decide the interim authority approach.
- Ratify backdating, future-dating, and valid-date-range rules.
- Approve the Slice 2 semantic and implementation contract.
- Keep all unratified decisions visibly marked as proposals.

### P3 — Reconcile Election and prepare cutover

- Election owner: investigate PBDIGIT-265 and reconcile the bulk and individual Full Membership eligibility predicates.
- Confirm that voter-eligibility behaviour matches the adopted Election decisions.
- Produce a reproducible inventory of all legacy `members` readers and writers.
- Map column ownership, especially `fees_status` versus `fee_state`.
- Design the per-organisation source-of-truth switch and rebuildable reverse projection.
- Guard direct writers, bulk updates, expiry jobs, imports, and relation-builder deletes.
- Define migration dry-run, parity, write-freeze, rollback, and environment-readiness evidence.
- Keep Election-Only independent of the Membership ledger.

### P4 — Deliver Membership workflows

- Implement Slice 2 command wiring without a live entry point, after approval.
- Implement controlled per-organisation cutover and prove parity.
- Expose HTTP/API/UI workflows only after the relevant cutover gates pass.
- Implement renewal, type changes, suspension/restoration, membership numbers, and correction/annulment/voiding as separately reviewed slices.
- Complete full lifecycle, temporal, tenant-isolation, authority, and end-to-end regression tests.

### P5 — Complete the wider organisational platform

- Finish Participation research and obtain the required Governance decisions.
- Define duties, authority, tenure, delegation, discharge, vacancy, and succession semantics.
- Define service-specific access policies and rebuildable access projections.
- Establish Finance's charge, payment, credit, and adjustment contracts.
- Establish Audit's evidence-consumption contract.
- Complete migration and retire obsolete membership representations only after verified cutover.
- Resolve PBDIGIT-261, PBDIGIT-262, PBDIGIT-263, and PBDIGIT-265 through their own authorised work.
- Complete production-readiness, operational, security, and full-suite acceptance checks.

Final recommendation: keep the scope narrow now. Obtain the Principal Reviewer's decision, authorise and complete PBDIGIT-264, and ratify the minimum business rules needed for Slice 2. Continue Participation research in parallel without implementing it. That sequence gives us the strongest path from a theoretically coherent Membership model to a safely deployable organisational capability.&#x20;