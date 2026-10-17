# PublicDigit Membership — Principal Architecture Review

I have analysed the attached session record, including the independent Slice 1b review, the Slice 2 decision register, the participation research contract, and the proposed follow-up backlog item.

My main conclusion is that the project has reached an important architecture and implementation gate. We have a working initial Membership kernel and PostgreSQL persistence adapter, but the latest evidence has exposed one genuine defect and several smaller gaps that should be addressed before we rely on the new persistence layer in a live workflow.&#x20;

Eingefügter Text.txt



## 1. My principal-review verdict

## Slice 1b: Accept with conditions — recommended

This is a recommendation for the Principal Reviewer, not a claim that formal acceptance has already occurred. The independent review supports the core design but identifies a data-integrity defect and follow-up work.

The most important defect

`DateOrdinal::toDate` can write a date beyond `9999-12-31` that the corresponding read path cannot parse. A membership record can therefore be persisted but become permanently unloadable.&#x20;

Eingefügter Text.txt



The recommended action

Prioritise PBDIGIT-264, beginning with a regression test for the date-range defect. Then close the remaining adapter-contract and persistence-hardening gaps before moving to live application workflows.

### What the attachment establishes

- Slice 0: the semantic domain kernel has been implemented.

- Slice 1a: the application commands, handlers, and repository contract have been implemented.

- Slice 1b: the PostgreSQL adapter, tenant-scoped persistence, transactional append, and idempotency handling have been implemented and tested.

- Independent review: the recommended verdict is Accept with conditions, not unconditional acceptance.

- Regression status: the full suite is not green. The report says no new failures were identified in its comparison, but both runs used uncommitted changes outside Slice 1b; this is not a clean-checkout baseline.&#x20;

  Eingefügter Text.txt

- Next implementation: not yet authorised in the report. Slice 2, migration cutover, and Governance implementation remain stopped pending the required decisions and approvals.&#x20;

  Eingefügter Text.txt



## 2. What I would change in the next-step strategy

The attached report is valuable because it does not simply declare the implementation finished. It distinguishes engineering evidence, open business decisions, and the next implementation gates.

I would preserve that discipline and make four improvements.

First: fix data-integrity risks before expanding the feature set. The date-range defect is low-frequency but potentially irreversible for an affected Member. The other PBDIGIT-264 items concern race-test coverage, parity between the in-memory and PostgreSQL adapters, a missing foreign-key guarantee, and safer database-constraint detection.&#x20;

Eingefügter Text.txt



Second: prevent two competing membership truths. The report identifies 23 application files that read the legacy `members` table. Wiring a live Admit/End endpoint to the new tables before a controlled cutover could cause the new and legacy models to disagree. The next steps must therefore distinguish command wiring without a live entry point, cutover, and HTTP/UI exposure.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



Third: separate technical choices from business decisions. The organisation's local time zone is principally a boundary concern when commands already receive explicit calendar dates. Whether dates may be backdated or future-dated is the substantive policy question. Consent, recognition, ending semantics, and authority also require the appropriate ratification; a researcher must not silently turn a recommendation into a business rule.

Fourth: continue theory development without mixing bounded contexts. Organisational Participation research should reuse the existing committee, position, tenure, appointment, and authority findings. It should focus on the remaining gaps: responsibilities versus permissions, obligations versus payments, service access, succession, and context ownership. The attachment explicitly keeps this research outside Membership Slice 2.&#x20;

Eingefügter Text.txt



## 3. Copy-ready prompt instructions for Claude

This prompt is designed to continue from the attached report rather than repeat completed research. It prioritises the known defect, protects the existing architecture, and advances the membership theory and practical implementation in parallel without allowing unratified decisions to leak into code.

# PublicDigit Membership — Principal Review, PBDIGIT-264, Decision Closure and Theory Development

## Your role

Work as a Senior DDD Architect, Senior Requirements Engineer, Principal-Level Technical Researcher, Formal Methods Researcher, Mathematician/Statistician, and Implementation Engineer.

Your responsibility is to help establish a robust, logically consistent, evidence-based Membership domain theory that can be implemented safely in PublicDigit.

You are the engineering and research agent. You may investigate, challenge assumptions, construct formal models, run bounded computational checks, write tests, and propose architecture. You must not silently ratify business rules or accept your own implementation.

The Product Owner and Principal Reviewer retain their decision responsibilities.

## 1. Establish the authoritative starting point

Read and reconcile these documents before taking action:

- `docs/publicdigit/reviews/2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md`
- `docs/publicdigit/reviews/2026-10-09-membership-slice1b-implementation-review.md`
- `docs/publicdigit/reviews/2026-10-09-membership-slice1b-contract.md`
- `docs/publicdigit/backlog/PBDIGIT-264-membership-slice1b-acceptance-follow-ups.md`
- `docs/publicdigit/backlog/PBDIGIT-260-membership-slice1b-postgres-adapter.md`
- `docs/publicdigit/membership/architecture/brainstorming/2026109_2043_membership_implementation.md`
- The Slice 0 and Slice 1a contracts, implementation guides, final decision register, implementation blueprint, and existing organisational participation research.
- Relevant source code, tests, migration, Git status, and recent commits.

Use the actual repository paths if a path has moved. Do not invent file locations or assume the report perfectly reflects the current working tree.

Before editing anything:

1. Inspect `git status`, staged changes, untracked files, and relevant recent commits.
2. Identify concurrent-session changes and preserve them.
3. Establish which claims are documented, which are independently tested, and which remain assumptions.
4. Report material discrepancies between the documents, code, tests, and backlog.

Do not repeat expensive tests when existing trustworthy evidence is sufficient. Do independently reproduce critical defects where safe.

## 2. Principal acceptance review of Slice 1b

Provide a concise, evidence-based assessment of:

- Date round-trip safety and supported date range.
- Atomic append, idempotency, and concurrent retries.
- Tenant scoping and composite constraints.
- Version checks, transaction boundaries, and isolation-level assumptions.
- Persistence facts as evidence versus domain resolution as membership truth.
- In-memory/PostgreSQL adapter contract parity.
- Test isolation and regression-baseline limitations.
- Architecture boundaries and authorised changes to Slice 1a.
- Deployment and migration readiness.

Recommend one of:

- ACCEPT
- ACCEPT WITH CONDITIONS
- REJECT

This is a recommendation only. Do not describe your recommendation as the Principal Reviewer's formal acceptance.

Distinguish:

- Demonstrated properties;
- Properties supported only under stated assumptions;
- Test gaps;
- Defects;
- Deferred risks;
- Business decisions.

Do not claim that the full test suite is green. Do not claim a clean-checkout baseline when the evidence does not establish one.

## 3. PBDIGIT-264: prepare the smallest safe remediation

PBDIGIT-264 is currently recorded as OPEN and not authorised. Do not modify implementation code until the Product Owner explicitly authorises this work.

Prepare the exact TDD-first plan for these items:

- F-1: reject unsupported calendar dates before any persistence operation. Supported round-trip range must be explicit and consistent with the chosen date representation.
- F-2: add real-process concurrent identical-Admit retry coverage and committed coverage for the cross-Member idempotency-key path.
- F-3: define the repository port's behaviour for an existing MemberId associated with a different person; align the in-memory adapter with PostgreSQL.
- F-4: assess and, if consistent with the approved contract, add the composite foreign key ensuring a command-idempotency row references the fact it names.
- F-5: use reliable PostgreSQL SQLSTATE and constraint identification instead of fragile message-substring matching; document the supported isolation-level assumptions.

For each item provide:

- The failing scenario;
- A minimal regression test;
- The expected failure before the fix;
- The smallest permitted implementation change;
- Shared contract and invariant tests;
- Rollback or migration implications;
- Acceptance evidence.

Do not broaden PBDIGIT-264 into Slice 2 or a general refactoring exercise. Do not change the domain kernel, repository port, or handlers unless the approved contract explicitly permits the change and the Principal Reviewer authorises it.

After preparing this plan, stop and request explicit authorisation to implement PBDIGIT-264. Do not treat the presence of this prompt as retrospective approval.

## 4. Prepare the Slice 2 business-decision ratification pack

Use the evidence in the existing decision-closure report. Do not repeat discovery without a specific evidence gap.

For each decision, provide the question, source evidence, conflicting evidence, alternatives, consequences, recommendation, ratifier, and whether it blocks the next slice.

### D-1 — Admission basis: BD-CONSENT / LQ-1

Evaluate whether every new admission must record a recognised basis, such as a declaration reference, and how historical imports differ from new admissions. Do not conflate evidence of a declaration with storing the declaration itself.

### D-2 — Pending Member: BD-REC0

Evaluate the proposal that pending applications remain outside the Member aggregate and that a Member is created only through the agreed admission act. Identify contradictory legacy representations and avoid adding a new recognition state without justification.

### D-3 — Ending kinds: BD-3 / LQ-3

Evaluate a controlled vocabulary for explicit ending acts. Distinguish an act, a reason or ground, a term lapse, and organisation dissolution. Do not treat non-payment as automatically equivalent to a membership-ending act.

### D-4 — Authority: DS-4

Authority belongs to Governance, not Membership. Present the proposed interim adapter that mirrors the existing authorisation policy only as a conditional option. Identify the Governance ratifier and the evidence that the adapter must record. Do not invent a Membership-local authority policy.

### D-5 — Effective-date range and calendar boundary

Keep the adopted calendar-date model: Membership transitions use dates; `decidedAt` and `recordedAt` remain exact instants.

Distinguish:

- Date conversion for user-interface defaults, using the organisation's configured time zone;
- Whether backdating is allowed;
- Whether future-dated admission or ending is allowed;
- Any restrictions needed for existing-period invariants.

Do not turn an implementation default into an unratified business rule.

### D-6 — Legacy coexistence and R10

Treat coexistence with the legacy `members` table as a critical integration and cutover concern. Identify the authoritative store per organisation and the prerequisites for preventing two competing membership truths.

Produce a decision table and a short recommendation pack for the Product Owner and Principal Reviewer. Do not claim a decision is adopted until the authorised person or body ratifies it.

## 5. Preserve the canonical domain model

Every proposal must respect the following distinctions:

`Person ≠ OrganisationAffiliation ≠ Member ≠ MembershipPeriod ≠ ElectionRegistration ≠ Admission ≠ VotingEntitlement ≠ Exercisability`

The adopted model is:

- Organisation Membership is represented by the `Member` aggregate.
- Re-entry creates a new Membership Period under the same Member.
- Membership facts are authoritative evidence; current membership predicates are derived by domain resolution.
- Membership does not itself grant voting entitlement.
- Election-Only must work without the Membership context.
- Full Membership uses the organisation's Membership registry as its population source.
- Governance owns organisational authority, roles, mandates, and participation rules.
- Finance owns fee/payment and settlement responsibilities.
- Audit consumes authoritative evidence without becoming a second owner of Membership truth.

Do not introduce a new aggregate, change identity semantics, adopt Event Sourcing, or replace the approved model without an explicit decision and documented rationale.

## 6. Protect the legacy integration boundary

The existing report identifies multiple direct readers of the legacy `members` table. Do not expose a live Admit/End endpoint until the coexistence and cutover plan prevents two competing sources of truth.

Preserve the planned sequence:

1. Command wiring without a live application entry point.
2. Controlled per-organisation cutover, reverse projection where required, legacy write guards, writer inventory, migration freeze, parity verification, and rollback.
3. HTTP/API/UI exposure after the cutover gate is satisfied.

Verify all legacy writers, including bulk and expiry paths. An organisation cutover must have an explicit source-of-truth rule.

Election-Only must never consult the new Membership ledger for voter-source eligibility. Add or retain an explicit regression test for that rule.

## 7. Organisational Participation research — no implementation yet

Reuse the existing `2026-10-09-organisational-participation-domain-research.md` and its RD-1…RD-5 and BQ-P1…P7 questions. Do not repeat settled research.

Investigate only the remaining gaps:

- G-1: Responsibility versus permission.
- G-2: Financial obligation versus payment/settlement.
- G-3: Deriving forum, document, meeting, task, and notification access.
- G-4: Succession, vacancy, and the evidence needed when assignments end.
- G-5: Bounded-context ownership and Published Language implications.

The conceptual distinctions are:

`Membership → Participation Assignment → Position / Role / Mandate → Responsibilities and Obligations → Policy Evaluation → Service Access`

This is a hypothesis to test, not an approved implementation design.

Use concrete organisation scenarios and counterexamples. Distinguish a duty from an authority, a financial obligation from a payment, and authoritative organisational facts from access-control projections.

For each gap:

1. Formulate precise requirements and domain questions.
2. Build positive, negative, temporal, and exceptional scenarios.
3. Falsify the hypothesis against the existing corpus and legacy readers.
4. Identify ownership, source-of-truth, and integration contracts.
5. Use bounded enumeration or property-based testing when rules are sufficiently specified.
6. Report assumptions and the limits of computational verification.

Use machine learning only as an advisory analysis technique—for example, to cluster legacy role vocabularies or identify candidate mappings and duplicate records. Never use a model's prediction to decide legitimate authority, membership, or access rights.

Deliver a draft Participation Semantic Contract. Do not implement Governance or Participation.

## 8. Formal methods and statistical discipline

Where useful, express rules as predicates, invariants, transition relations, and temporal properties. Define the finite domain and assumptions for every bounded computation.

Use independent oracles where possible. Add metamorphic properties, counterexample-led tests, and property-based tests. Do not treat repeated random tests as a proof of correctness.

For each reported test or simulation, distinguish:

- The number of generated cases;
- The number of failures found;
- The properties actually checked;
- The range of dates, events, states, or commands explored;
- The assumptions not represented by the model.

Use statistical or machine-learning methods only when they add evidence beyond deterministic rules and explicit domain semantics.

## 9. Deliverables and stop conditions

Produce:

1. Principal acceptance recommendation for Slice 1b.
2. Prioritised PBDIGIT-264 remediation plan.
3. Slice 2 decision ratification pack.
4. Legacy coexistence and cutover gate checklist.
5. Participation Semantic Contract draft for the identified gaps.
6. Updated membership implementation roadmap with dependencies and explicit blockers.
7. A concise evidence ledger separating adopted decisions, recommendations, assumptions, implementation facts, and open questions.

Do not implement Slice 2, migration cutover, HTTP/UI workflows, or Governance/Participation.

Do not run migrations against development or production without explicit authorisation.

Do not push commits.

Preserve other sessions' staged and uncommitted work. Make no broad documentation rewrites. If a documentation update is needed, edit only the relevant sections and inspect the diff.

**Stop and report** when the review and research deliverables are ready, stating exactly which Product Owner or Principal Reviewer decisions are required before the next implementation action.

## 4. How far are we from the Membership implementation goal?

My assessment is that the technical foundation is substantially built, but the complete Membership capability is not yet implemented.

## Membership delivery status

Domain foundation — implemented

Initial temporal model, resolution logic, domain invariants, and Admit/End semantics.

Application and persistence foundation — implemented

Initial handlers, repository contract, PostgreSQL adapter, and concurrency/idempotency safeguards.

Technical acceptance — conditional

Date-range defect and adapter hardening remain in PBDIGIT-264; formal Principal Reviewer acceptance is outstanding.

Usable Membership workflows — incomplete

Business decisions, application wiring, safe legacy cutover, and HTTP/UI workflows remain.

Complete organisational membership capability — not yet achieved

Lifecycle completion, Governance participation, integration, migration, and end-to-end acceptance remain.

I would not assign a percentage without a defined and measurable completion baseline. The meaningful milestone is that we have moved beyond purely conceptual research into an implemented domain kernel and persistence layer. We now need to prove that the design can safely support real organisational workflows without conflicting with legacy behaviour.

## 5. Remaining TODOs — prioritised

### Immediate: acceptance and integrity

- Principal Reviewer: decide the formal acceptance status of Slice 1b.
- Product Owner: explicitly authorise PBDIGIT-264 before implementation begins.
- Fix the unsupported far-date persistence defect using a regression test first.
- Add concurrent identical-Admit retry tests and cross-Member idempotency-key coverage.
- Align in-memory and PostgreSQL repository behaviour.
- Close the command-to-fact foreign-key and constraint-detection hardening gaps.
- Confirm the recorded authorisation for the `DecideAndAppend` idempotency fix.
- Resolve PBDIGIT-263, the PostgreSQL test-isolation problem, and avoid overstating regression evidence.
- Add the missing database developer guide and keep backlog, implementation guides, and project-state records consistent.

### Next: business decisions and Slice 2

- Ratify the admission-basis rule: consent/declaration versus other legitimate admission grounds.
- Decide whether pending applications remain outside the `Member` aggregate.
- Ratify the allowed ending kinds and distinguish ending acts from reasons and term lapse.
- Obtain Governance/PO approval for any interim authority adapter.
- Decide the permitted backdating and future-dating rules for membership transitions.
- Approve the Slice 2 semantic and implementation contract.
- Wire Admit/End commands to the PostgreSQL repository without exposing a live entry point prematurely.
- Verify that Election-Only remains independent of Membership.

### Then: application integration and lifecycle

- Implement the per-organisation legacy cutover strategy before enabling live HTTP/UI workflows.
- Inventory and guard all legacy writers, including bulk updates and expiry processes.
- Implement the approved Membership application/API/UI workflows.
- Complete renewal, type changes, suspension/restoration, membership-number handling, and correction/annulment/voiding in separately reviewed slices.
- Establish end-to-end tests for temporal semantics, tenant isolation, authority, idempotency, and lifecycle invariants.

### Parallel research and subsequent integration

- Complete the Participation Semantic Contract research for responsibilities versus permissions.
- Formalise obligations versus payments and their context ownership.
- Define how committee and role assignments produce service-access decisions.
- Resolve succession, vacancies, and the boundaries between Membership and Governance.
- Integrate Election through the established contract without making Membership the source of voting entitlement.
- Complete Finance ownership of fees and settlement.
- Implement Audit consumption of authoritative Membership evidence.
- Execute migration dry runs, parity checks, per-organisation cutover, rollback preparation, and legacy retirement.
- Verify PostgreSQL configuration and close the MySQL legacy-residue items.
- Complete security, operational-readiness, and full end-to-end acceptance.

My final recommendation: the next controlled milestone is Slice 1b acceptance and PBDIGIT-264 remediation, followed by explicit business-decision ratification. Continue the Participation research in parallel, but do not allow it to expand Membership's responsibilities or bypass the existing Election architecture.&#x20;