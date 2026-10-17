# Principal review: Membership theory and the next step

My assessment is based on the attached Claude session transcript, including its independent challenge findings, route analysis, proposed security-test plan, and repository-status report. I distinguish what the transcript reports as verified, what remains a hypothesis, and what requires a business or governance decision. I have not independently inspected the live repository or rerun its tests.

## 1. Principal Reviewer verdict

Conditional

Membership theory: accept with conditions

The theory and the Admit/End kernel provide a meaningful foundation. However, the overall Membership implementation is not yet ready for production cutover. Correction and annulment, authority semantics, legacy parity, and integration remain important gaps.

The attached report identifies two major findings that I agree must be resolved explicitly before the architecture proceeds.

### Major finding 1 — an ambiguous decision identifier

`D-1` refers to two different decisions:

- In the Slice 1a contract, it identifies the adopted FactId uniqueness invariant (`INV-FID`).
- In the Slice 2 decision pack, it identifies an unresolved admission-basis question.

This is a requirements-governance defect: a decision or approval referencing `D-1` could be interpreted in two incompatible ways.

Recommendation: preserve the existing Slice 1a identifier and ask the Product Owner (PO) to rename the Slice 2 decisions, for example to `S2-D1` through `S2-D6`. Update all references and verify there are no ambiguous references remaining. Do not silently rename ratified decisions.

### Major finding 2 — correction capability comes too late

The proposed roadmap schedules migration and live Admit/End operations before correction and annulment are executable.

In an append-only Membership ledger, an incorrect fact cannot simply be edited away. According to the transcript, the current kernel refuses a second `End`, while changing an end date is reserved for a future `Correct(End)` capability.

That creates a material risk: an erroneous live or migrated fact could become operationally permanent before the architecture provides a ratified remedy.

Recommendation: make the PO explicitly decide whether correction and annulment are mandatory before cutover and before live writes. My architectural recommendation is to require a tested, auditable correction path before either event. If the PO chooses otherwise, document the risk and the compensating controls as a formal decision—not an implicit roadmap assumption.

## 2. What the security investigation changes

The new PBDIGIT-266 analysis is useful because it follows authorization beyond the route declaration and controller, into middleware, domain guards, tenant context, and persistence paths.

| Area                            | Reviewer assessment                                                                                                                                                          |
| ------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Election approve/reject         | A domain-level `platform_admin` guard was found. The earlier suspicion is likely refuted, pending tests.                                                                     |
| Pending-election list           | A probable information-disclosure gap: the transcript reports no guard on the list route.                                                                                    |
| Committee application review    | A strong authorization concern; self-approval may be possible, and the route may fail before reaching its intended logic.                                                    |
| Governance committee-member API | Organisation membership appears checked, but a specific role guard was not found. Some routes reportedly fail with 400/500 errors.                                           |
| Legacy finance                  | No clear role guard was found in the examined paths; tenant scoping and route failures complicate the assessment.                                                            |
| Tenant header trust             | The transcript reports that `X-Tenant-Id` can set session tenant context without a membership check, with a live debug route also registered. This needs focused validation. |

These are not six confirmed exploitable vulnerabilities. Some are static findings, some are likely defects, and some depend on unresolved policy or a route being reachable. A 500 error is a route defect, not proof that authorization is secure.

The most important architectural concern is the possible connection between tenant-context injection and finance writes. If a caller can influence the tenant context and a later finance route trusts that context, the isolation boundary may be compromised. The proposed tests must therefore assert both the HTTP result and the actual persisted state, tenant context, and side effects.

The transcript also reports an active concurrent session working around the shared test database. Consequently, neither the test suite nor database setup should be run until database isolation and coordination are established.

## 3. The next step I recommend

Do not jump straight into implementation. The next step should close the gap between research findings and an authorised, executable work package.

I recommend one bounded, research-only Claude task that:

1. Re-establishes repository state and preserves all concurrent work.
2. Resolves the decision-ID ambiguity in the documentation proposal, without renaming adopted decisions unilaterally.
3. Prepares the PO decision packet for PBDIGIT-266, including the five policy questions.
4. Reorders the Membership roadmap so the correction-before-cutover decision is explicit.
5. Separates security requirements grounded in existing rules from policy choices that only the PO or Governance owner can settle.
6. Produces a test-execution plan with safe database preconditions—but does not run tests, send requests, change runtime code, or assume authorisation.

This keeps the work moving without accidentally authorising a security test or committing the project to an unresolved governance rule.

## 4. Copy-ready Claude prompt

# Next Step — Membership Principal Review, PBDIGIT-266 Decision Readiness, and Roadmap Correction

## Role

Act as a senior Domain-Driven Design (DDD) architect, principal reviewer, requirements engineer, formal-methods researcher, computer-logic specialist, statistician, and applied machine-learning researcher.

Treat the existing PublicDigit corpus as research evidence and brainstorming material—not as unquestionable truth. Your task is to derive a robust Membership theory that can be implemented safely and practically, while preserving the distinction between ratified rules, proposals, implementation facts, model results, and unresolved business decisions.

## Current evidence to inspect

Start with the current repository state. Locate and review these documents, verifying their actual current paths and contents:

- `docs/publicdigit/reviews/2026-10-09-membership-theory-synthesis-and-implementation-readiness-audit.md`
- `docs/publicdigit/reviews/2026-10-09-membership-audit-challenge-and-pbdigit266-test-plan.md`
- `docs/publicdigit/backlog/PBDIGIT-266-live-routes-without-a-found-authorisation-guard.md`
- The Slice 1a contract, Slice 1b persistence decision records, Slice 2 decision pack, Membership implementation plan, and relevant open-question/decision records.
- The current project context and session logs, including concurrent work and database restrictions.

Do not assume the transcript's reported HEAD, working-tree state, commit contents, or test status are still current. Verify them.

## Hard execution boundary

This commission is **research, review, and decision-readiness work only**.

- Do not run tests or database migrations.
- Do not send HTTP requests.
- Do not create or reset databases.
- Do not modify application code, tests, schema, routes, middleware, or configuration.
- Do not commit, push, or overwrite other sessions' work.
- Do not treat this prompt as authorisation to execute PBDIGIT-266, PBDIGIT-264, or any other implementation/security work.
- Documentation edits are permitted only if necessary, clearly scoped, and safe alongside concurrent work. Prefer a review proposal or a new decision-readiness document over rewriting ratified records.
- If repository state is ambiguous, a concurrent session owns the relevant files, or a proposed action could affect shared state, stop and report the conflict.

## Work package A — Independent principal review

Challenge the audit and test plan, not merely restate them.

For each material claim, classify the evidence as one of:

- **ADOPTED** — formally ratified requirement or decision.
- **OBSERVED** — directly supported by current source or repository evidence.
- **CD** — confirmed defect, with reproducible or decisive evidence already available.
- **HYPOTHESIS** — plausible interpretation requiring further verification.
- **BOUNDED RESULT** — result of a finite model or enumerated state space.
- **P-ARG** — informal proof argument, not mechanically verified.
- **P-PROOF** — proof supported by an explicit derivation or verified artifact.
- **BIZ** — unresolved business or governance decision.
- **UNKNOWN** — evidence is insufficient.

Do not upgrade static suspicion into a confirmed vulnerability. Do not describe a bounded-model count as a probability or production incident rate.

Report any new contradiction, missing evidence, incorrect dependency, overgeneralisation, or misleading status label.

## Work package B — Correct the decision-governance problem

The reported `D-1` collision must be handled explicitly:

- Slice 1a uses `D-1` for the adopted FactId uniqueness invariant (`INV-FID`).
- Slice 2 uses `D-1` for the unresolved admission-basis decision.

Preserve the ratified Slice 1a identifier. Propose a distinct namespace for Slice 2 decisions, such as `S2-D1` through `S2-D6`, but do not silently rename decisions or claim PO approval.

Produce a reference-migration checklist covering decision registers, contracts, plans, ADRs, backlog items, review reports, and session context. Distinguish a proposed naming convention from an approved one.

## Work package C — Re-evaluate correction-before-cutover

Trace the dependencies among:

- append-only Membership facts;
- Admit and End;
- correction and annulment;
- erroneous live facts;
- migrated legacy facts;
- auditability and `knownAt` reconstruction;
- HTTP/UI enablement;
- cutover and rollback.

Answer these questions:

1. Which classes of error can the current kernel remedy?
2. Which errors are currently refused or have no executable remedy?
3. What happens if a bad fact is admitted or migrated before correction is available?
4. Which corrections must preserve historical knowledge, provenance, authority evidence, and temporal meaning?
5. What minimum correction/annulment capability and verification evidence are needed before live writes or migration?
6. What compensating controls would be necessary if the PO rejects correction-before-cutover?

Recommend an ordering, but label the ordering as an architectural recommendation until ratified.

Trace the prerequisites already reported: `BD-REV-ER`, `CQ-3`, `CQ-9`, and the relevant act/N-4 reference. Verify exact identifiers and their current status before relying on them.

## Work package D — Prepare the PBDIGIT-266 PO decision packet

Review the five reported policy questions:

- **P1:** Who may view the platform-wide pending-election list?
- **P2:** Who may review committee-membership applications? Is self-approval forbidden?
- **P3:** Which role or organisational organ may add/remove committee members?
- **P4:** Who may record finance income/outcomes, and what tenant scope is required?
- **P5:** Is `X-Tenant-Id` a permitted non-production/test mechanism only, and should `election.debug-tenant` be registered in production?

For each question, provide:

- the owning bounded context and accountable decision-maker;
- the observed current behaviour, if supported by source;
- the relevant existing rule or precedent;
- the business/security consequence;
- feasible options and their trade-offs;
- a recommended default, clearly marked as a recommendation;
- the exact approval required;
- what cannot be tested definitively until the decision is made.

Do not infer authority from committee names alone. Distinguish Membership, Governance, Election, Finance, Audit, and service-access concerns. Enforce tenant isolation as a cross-cutting platform constraint without inventing a role matrix.

## Work package E — Make the PBDIGIT-266 test plan execution-ready, but do not execute it

Review the five families in the existing plan:

1. Election approval and pending-list visibility.
2. Committee application review and separation of duties.
3. Governance committee-member API.
4. Legacy finance routes and persistence side effects.
5. Tenant-header trust and the debug route.

For every test case, specify:

- route and complete authorization path;
- caller identity, verification state, organisation membership, role, and tenant;
- grounded invariant or unresolved policy question;
- expected HTTP result;
- expected persisted state and absence of unintended writes;
- expected session/tenant-context state;
- expected mail or other side effects;
- whether a 400/401/403/404/500 is a security result, a policy result, or a route-health defect;
- what evidence would confirm, refute, or leave the hypothesis inconclusive.

Pay particular attention to the reported tenant-header-to-session path and the possibility that a tenant-context flaw could affect later Finance writes. Use a controlled, non-destructive test design that proves or disproves the relationship; do not claim exploitability based on static code alone.

Separate:

- tests grounded in already adopted requirements;
- policy-dependent characterisation tests;
- route-health tests;
- tests that cannot run until database isolation is available.

State the exact prerequisites for later execution: explicit PO authorisation, confirmation of the active database, isolation from the development database, coordination with concurrent sessions, and use of in-process tests with fake external side effects. Do not execute any of these steps now.

## Work package F — Rebuild the Membership implementation roadmap

Create a dependency-ordered roadmap, not merely a priority list.

At minimum, assess:

- formal Slice 1b acceptance act and DV-1 countersignature;
- PBDIGIT-264 persistence remediation, separately authorised;
- PBDIGIT-265 Full Membership election-eligibility parity and cutover;
- PBDIGIT-266 authorization/tenant-isolation investigation;
- kernel test gaps TG-1 through TG-5;
- the authority-port gap G-AUTH-1;
- unresolved CQ/D/EM-OPEN decisions and vocabulary conflicts;
- executable correction/annulment and its position relative to migration and live writes;
- Membership/legacy `members` parity and cutover controls;
- Governance and Finance contracts;
- eventual HTTP/UI integration;
- model verification and regression evidence.

For each item state: owner, prerequisites, deliverable, acceptance evidence, blockers, and whether it is research, decision, test-only, implementation, migration, or production-readiness work.

Keep independent workstreams independent. In particular, do not portray PBDIGIT-264 and PBDIGIT-266 as a hard dependency unless the current evidence establishes one.

## Work package G — Formal methods, statistics, and ML discipline

Identify where formal models, property-based tests, mutation tests, temporal logic, or state-space exploration provide useful evidence. Distinguish model assumptions from implementation guarantees.

For every reported finite-model result, state the model boundary, state-space scope, and what it does not prove.

Do not invent a statistical dataset or estimate production risk from synthetic cases. No Membership dataset with a documented sampling frame has been established in the available evidence.

Keep machine learning out of authoritative Membership, identity-equivalence, appointment, authority, and voting-entitlement decisions. If duplicate legacy-row triage is proposed, treat ML as advisory only, require human confirmation, measure precision and recall on a labelled and appropriately stratified sample, and explicitly account for the higher cost of false merges.

## Required deliverables

Produce:

1. A principal-review verdict: `ACCEPT`, `ACCEPT WITH CONDITIONS`, `REVISION REQUIRED`, or `CONFLICT`, with evidence.
2. A decision register for the identifier collision and correction-before-cutover.
3. A PO decision packet for P1–P5.
4. A route-by-route PBDIGIT-266 test specification, ready for later authorisation.
5. A dependency-ordered Membership roadmap with explicit gates.
6. A concise architecture status statement separating theory, kernel, persistence, integration, migration, and production readiness.
7. A bullet-point TODO list with priority, owner, dependency, and acceptance condition.
8. A list of source files inspected and any documentation files changed.

End with a clear statement that no tests, requests, migrations, code changes, commits, or authorisations occurred under this commission. Stop after reporting; wait for explicit direction before execution.

## 5. How far are we from the Membership implementation goal?

The goal is not merely to store members in a database. It is to build a reliable Membership domain that can reconstruct what was true, what was known at a given time, which decisions were authorised, and how Membership integrates with Governance, Elections, Finance, Audit, and organisation-specific operations.

Based on the attached report and the previous Membership research record, this is my current assessment.

## Membership maturity assessment

Qualitative principal-review estimate—not measured delivery percentages.

Domain concepts and core theory

### Advanced

The main concepts, boundaries, temporal semantics, and Admit/End model are established. Some authority and policy questions remain open.

Core kernel and verification

### Substantial

Admit/End has reported passing tests, but correction/annulment and five identified test gaps still need attention.

Persistence and adapter

### Conditional

Slice 1b has a conditional-acceptance recommendation, not a formal acceptance act. PBDIGIT-264 remains open.

Governance, Election and Finance integration

### Incomplete

Authority contracts, election eligibility parity, and legacy behaviour still need to be resolved and verified.

Migration, cutover and production readiness

### Not ready

Parity, correction capability, authorisation rules, migration controls, and acceptance gates remain unresolved.

I would describe the overall programme as past the conceptual-design stage but still in the transition from verified kernel to a complete, operational Membership capability. I would not present one overall percentage as an objective measurement: theory, code coverage, integration, and production readiness are different dimensions.

## 6. Remaining TODOs

The following list is ordered by dependency and risk, not simply by ease of implementation.

### Priority 1 — Close the immediate review and authorisation gates

- PO — Decide PBDIGIT-266 execution authorisation. Authorise a test-only investigation explicitly, or defer it. No tests should run before this decision.
- PO — Resolve P1–P5. Decide pending-election visibility, committee application review and self-approval, committee-member management authority, Finance permissions and tenant scope, and permitted tenant-header/debug behaviour.
- PO — Resolve the `D-1` collision. Approve a distinct Slice 2 decision namespace and reconcile the references without changing adopted decisions silently.
- Principal Reviewer — Formalise Slice 1b acceptance. Record the actual disposition and conditions in the authoritative repository record.
- PO — Countersign DV-1. Reconcile the recorded decision about the minimal `DecideAndAppend` fix with the repository's formal decision record.
- Engineering — Coordinate test-database ownership. Establish an isolated test database and confirm no concurrent session can reset or interfere with it.

### Priority 2 — Establish correctness before irreversible operations

- PO + Principal Reviewer — Decide correction-before-cutover. Make the order of correction/annulment, migration, and live writes explicit.
- DDD architect + Governance owner — Resolve correction authority. Confirm who can correct or annul facts, what evidence is required, and whether a separate appeal body is needed (`CQ-3`, `CQ-9`, and `BD-REV-ER` must be checked against their current authoritative records).
- Membership engineering — Design and verify correction/annulment. Preserve append-only history, provenance, authority evidence, valid-time semantics, and `knownAt` reconstruction.
- PO + Membership engineering — Authorise and execute PBDIGIT-264 separately. Complete the approved persistence remediation sequence, database-isolation checks, migration/rollback verification, and acceptance evidence.
- Membership engineering — Address TG-1 through TG-5. Close the identified test gaps in sequence/recorded-time validation, authority evidence, whitespace identifiers, refusal handling, and `IntervalSet.equals`.

### Priority 3 — Validate the security and organisational boundaries

- Security + Engineering — Execute PBDIGIT-266 after authorisation. Test the five route families and distinguish confirmed defects from policy-dependent outcomes.
- Security + Engineering — Verify tenant isolation end to end. Prove that a supplied tenant identifier cannot create unauthorised session context or affect later reads/writes, including Finance.
- PO + Governance owner — Resolve Membership vocabulary and authority conflicts. Address K-1 through K-8, including the meaning of “Election Committee,” the Organisation Chief wording, and the location of committee write authority.
- DDD architect — Close the authority-port gap G-AUTH-1. Finalise the authority contract only after the relevant governance decisions are made.
- Election owner + Membership owner — Resolve PBDIGIT-265. Establish one authoritative Full Membership eligibility rule, compare it with legacy predicates, and produce a shared test matrix. Election-Only mode must remain independent of the Membership ledger.
- Finance owner — Resolve the Finance contract. Confirm fee-state semantics, credits/refunds, identity keys, and the ownership of authoritative financial facts.

### Priority 4 — Complete integration and migration readiness

- Membership + platform engineering — Reconcile the legacy `members` model with the Membership ledger. Define authoritative ownership, migration mapping, duplicate handling, and cutover parity.
- Engineering — Complete the migration/cutover gates. Verify dry-run results, legacy-versus-new parity, rollback behaviour, and operational observability before rollout.
- Governance owner + DDD architect — Ratify the Governance contract. Specify appointments, tenures, mandates, delegated authority, recusal, quorum, and separation of duties without placing their authority rules inside Membership.
- Engineering — Implement HTTP/UI integration only after domain and authorization gates pass. Ensure that application flows invoke the authoritative domain behaviour rather than recreating Membership eligibility rules in controllers or projections.
- Researcher — Re-run formal and mutation verification after approved changes. Clearly separate kernel guarantees, model assumptions, finite-state results, and production behaviour.
- Principal Reviewer — Issue a final readiness decision. Require explicit evidence for correctness, security, migration, operational controls, and unresolved-risk acceptance before production approval.

### Priority 5 — Continue research without confusing models with business truth

- Researcher — Continue bounded-state exploration for re-entry, retroactive tenure changes, committee quorum, recusal, delegation, and financial correction semantics.
- Requirements engineer — Convert unresolved questions into owned decisions. Every business rule should have an owner, a decision record, a testable acceptance condition, and traceability to its implementation.
- ML researcher — Keep ML advisory-only. Consider duplicate legacy-row triage only when a labelled, representative dataset is available. Measure precision and recall, use human confirmation, and make false-merge costs explicit. ML must not decide identity equivalence, Membership, authority, or voting entitlement.

## Final recommendation

The next immediate deliverable should be the decision-ready PBDIGIT-266 package and corrected Membership dependency roadmap described in the Claude prompt above. After the PO authorises the test-only investigation and test-database isolation is confirmed, execute the grounded security tests and route-health checks. In parallel, the PO should settle the decision-ID collision and correction-before-cutover policy.

The central architectural principle remains unchanged: do not make an append-only Membership ledger operationally authoritative until the project has an acceptable remedy for erroneous facts, an unambiguous authority model, verified tenant isolation, and a controlled migration path.