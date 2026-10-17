# Principal review: Election adjudication — next architectural step

The attached Claude investigation has completed an important discovery phase. My recommendation is to move from discovery to decision-ready domain design, not directly into coding and not back into general Membership implementation.

The central finding is that the proposed “Election Review Committee” is not yet a canonical domain concept in the repository. The existing architecture already separates the challenge case, the adjudication decision, the authority to decide, and the election's reaction to that decision. The concrete body that exercises adjudication authority remains unresolved.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



## 1. What the investigation established

Reuse the existing domain model

A review case is represented by `Challenge` in Contestation; the formal ruling is a `Determination` in Adjudication. Election handles the consequences. The report recommends against inventing another case aggregate or bounded context.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



Keep authority in Governance

The body or person issuing a ruling must hold a valid constitutional delegation. Adjudication must validate or request that authority rather than maintain its own competing authority model.&#x20;

Eingefügter Text.txt



Several production capabilities are incomplete

The report identifies no production caller for `receiveRulingDecision()`, no product filing entry point, and no implemented jurisdiction for voter-eligibility objections. It also identifies gaps in two-person approval, audit provenance and cross-context tenant-isolation testing. These findings must be rechecked against the current repository before implementation.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



The deciding body's constitution is unresolved

The choices include extending the existing Election Committee's authority, establishing a separate `ChallengeAdjudicationBody`, or using a properly delegated authority. Appointment, quorum, recusal, jurisdiction, filing rules and finality also require decisions by the appropriate owner.&#x20;

Eingefügter Text.txt



## 2. The next step I recommend

Ask Claude to prepare a decision-ready Election Adjudication Blueprint based on the existing investigation and repository evidence.

The goal is to make the process implementable without allowing an AI coding agent to invent constitutional authority.

The blueprint should establish:

1. Process: how a challenge is filed, admitted, investigated, adjudicated, resolved and, where authorised, appealed.
2. Authority: who may issue a ruling, how the delegation is validated and how conflicts of interest are handled.
3. Jurisdiction: which cases can be challenged today and whether eligibility or voter-list disputes should be added through an explicitly approved domain-language change.
4. Decision procedure: individual or collective decision-making, quorum, voting threshold, abstention and recusal—with unresolved rules clearly marked.
5. Technical execution: the existing `Challenge` and `Determination` lifecycle, authority-decision intake, idempotency, atomic persistence, audit evidence and tenant isolation.
6. Delivery: existing stories and dependencies, a recommended implementation sequence, acceptance criteria and tests.

The distinction is important: the discovery report recommends S0, hardening the two-person approval rule, if authorised; S1, authority-decision intake; and later committee procedure and filing capabilities. It explicitly says that the committee implementation cannot yet be authorised because its authority and procedure remain undecided.&#x20;

Eingefügter Text.txt



## 3. Copy-ready prompt for Claude Code

# Next Mission: Election Adjudication Blueprint and Implementation Readiness

## Role

Act as a principal Domain-Driven Design architect, senior requirements engineer, election-process researcher, formal-methods practitioner, mathematician, statistician and software assurance architect.

We are developing PublicDigit, a digitalisation platform for organisations and political parties, with online voting as a core capability.

Your objective is to derive a robust, auditable and practically implementable election challenge and adjudication process from the existing repository corpus. Treat the corpus as evidence and brainstorming material, not as permission to invent missing constitutional rules.

## 1. Mission boundary

The previous investigation produced:

`docs/publicdigit/reviews/2026-10-09-election-review-committee-discovery.md`

Read this report and its referenced source documents before starting.

This mission continues that discovery. It is NOT a general Membership implementation or verification task.

Keep these concepts distinct:

- General Membership: organisational membership facts and lifecycle.
- Election Membership: election-specific participation and eligibility.
- Contestation: challenge-case lifecycle.
- Adjudication: formal determination of a challenge.
- Governance: constitutional authority, delegation and appointments.
- Election: election operation and the application of authorised consequences.

Membership may be considered only where a demonstrated election-eligibility dependency requires it.

Do not create a new bounded context or aggregate merely because the phrase "Election Review Committee" was used. The phrase is not yet canonical.

## 2. Establish the evidence baseline

Inspect the actual current repository state. Verify the discovery report's important claims against the implementation, tests, ADRs, stories, architecture documentation and developer guides.

For every important claim, classify it as:

- FACT: directly supported by current source or approved artefact.
- INFERENCE: logically derived from identified facts.
- RECOMMENDATION: a proposed design choice.
- DECISION REQUIRED: only the Product Owner, Architecture Review Board, Domain Owner or authorised governance body may decide.

Reference exact paths, symbols, line ranges and story/ADR identifiers wherever possible.

Do not assume that an earlier agent's findings remain current. Identify changed files, concurrent work and the current status of relevant stories without modifying another session's work.

## 3. Produce the end-to-end election challenge process

Model the process from the triggering event through final consequence:

1. A person or authorised actor identifies a potentially challengeable election decision.
2. A challenge is submitted with a defined target, standing, evidence and filing time.
3. The system validates basic submission conditions.
4. An authorised actor or process determines admissibility.
5. The case is dismissed, admitted, investigated or routed as permitted by the approved model.
6. The delegated adjudication authority considers the case.
7. A formal Determination is issued and, where permitted, finalised.
8. The Election context processes the authorised consequence.
9. The challenge reaches a terminal state, with finality, expiry and any permitted appeal handled explicitly.
10. The system preserves the audit evidence needed to reconstruct what happened and why.

Do not assume every step is currently implemented. Show existing, incomplete, proposed and undecided capabilities separately.

Use the existing Challenge and Determination models where their contracts support the process. Identify any missing ports, adapters, handlers, persistence, UI/HTTP entry points and cross-context interactions.

Provide a Mermaid state diagram and a sequence diagram. Ensure that the diagrams represent verified or explicitly proposed behaviour rather than silently presenting future behaviour as existing.

## 4. Resolve the authority model as a decision brief

Evaluate the three options already identified in the discovery report:

A. The existing Manifesto Election Committee receives adjudicative authority.

B. A separate ChallengeAdjudicationBody is established, with its internal-versus-external form explicitly considered.

C. A properly delegated authority issues the ruling, without presuming that the authority must be a committee.

For each option, document:

- Constitutional and governance implications
- Independence from the election operators whose decisions may be challenged
- Appointment and delegation requirements
- Accountability and auditability
- Decision procedure and operational complexity
- Required domain and technical changes
- Existing ADRs, stories and unresolved questions
- Risks, trade-offs and assumptions

Do not select an option on behalf of the authorised decision-makers. State which evidence is available and which decisions remain theirs to make.

Do not introduce a canonical display name until the relevant owner confirms it.

## 5. Formalise the domain rules

Express critical invariants as precise predicates or decision tables where appropriate.

At minimum, investigate:

- Only a valid, in-scope constitutional delegation can issue a Determination.
- An actor must not adjudicate a matter where the approved independence or recusal rules prohibit it.
- A challenge cannot receive multiple conflicting final rulings.
- A challenge can target only an authorised target type.
- All linked case, determination, election and audit records respect organisation/tenant boundaries.
- Applying a ruling must be idempotent and must not create unauthorised retroactive changes to the election.
- Audit records must accurately represent the relevant state and decision history.
- No case, determination or audit record may expose a voter-to-ballot link.

Treat quorum, majority, recusal, abstention, filing windows, standing, appeal rights and jurisdiction as unresolved until authoritative evidence establishes them.

Do not assume the existing two-thirds acceptance threshold also governs adjudication. Analyse it as a candidate rule and mark the decision required.

Use truth tables, transition tables, invariants and boundary-value analysis when they make requirements more precise. Consider concurrency, duplicate delivery, stale state, clock boundaries, retries and failure recovery.

Machine learning may help organise the corpus, identify duplicated concepts or classify requirements for human review. It must not decide constitutional authority, voter eligibility, challenge outcomes or legal validity. Final decisions must follow explicit, deterministic and auditable rules.

## 6. Respect bounded-context ownership

Preserve the existing architectural direction:

- Governance owns authority and constitutional delegation.
- Contestation owns the Challenge lifecycle.
- Adjudication owns the Determination and adjudication process.
- Election owns election-specific operation and the consequences of an authorised determination.

Verify these ownership statements against the ratified artefacts before proposing changes.

Do not create duplicate authority models or parallel challenge lifecycles. Prefer an adapter or port where the existing architecture requires an integration seam.

Keep aggregate transaction boundaries, idempotency, outbox/inbox semantics and failure handling explicit. Do not claim end-to-end atomicity across bounded contexts unless the actual design guarantees it.

## 7. Security and audit readiness

Review and map:

- Separation-of-duty gap G3 and the two-person approval rule.
- Actor identity comparison by stable identifiers rather than display names.
- Authority validation at the point of issuing a Determination.
- Atomicity between issuance and required outbox/audit writes.
- Provenance continuity from challenge through determination to election consequence.
- Accurate state-transition evidence.
- Organisation-scoped authorisation and cross-tenant isolation.
- Confidentiality of evidence, reasons and voter information.
- Duplicate, replayed and out-of-order messages.

Distinguish existing defects from risks that depend on future design choices.

## 8. Audit the existing lifecycle discrepancy

Use the companion investigation:

`docs/publicdigit/reviews/2026-10-09-election-lifecycle-and-audit-from-state-investigation.md`

Verify the reported mismatch between derived election lifecycle state and persisted `from_state` audit data.

Explain whether and how this affects adjudication evidence. Keep the correction in its existing authorised scope. Do not modify production lifecycle or audit behaviour without the required approval.

## 9. Backlog and delivery planning

Reuse existing stories and ADRs where appropriate, particularly:

- PBDIGIT-196: challenge filing entry point and contract.
- PBDIGIT-205: admissibility and dismissal.
- PBDIGIT-177 / PBDIGIT-178: authority-decision intake and atomic issuance.
- PBDIGIT-176: outstanding domain-owner decisions.
- PBDIGIT-184: committee constitution decision.
- PBDIGIT-197 / 198 / 199 / 206: finality, horizon and lapse.
- PBDIGIT-191 and its stated dependency.

Verify each item's current status and dependencies. Do not create duplicate stories just to introduce the name "Election Review Committee".

Assess S0 (two-person approval hardening), S1 (authority-decision intake), S2 (collective committee procedure), S3 (challenge filing entry point) and S4 (eligibility objections) as separate slices.

For each slice, state:

- Preconditions and authorisation required
- Existing code and contracts to reuse
- RED tests and acceptance criteria
- Implementation scope
- Regression and security tests
- Developer-guide and project-state updates
- Rollback or recovery considerations

Do not start a slice whose constitutional prerequisites are unresolved.

## 10. Required deliverables

Create or update a decision-ready review document under the existing PublicDigit review-document convention. Do not overwrite the previous discovery report.

Include:

1. Executive findings.
2. Evidence register with source references and confidence.
3. Current-versus-target capability matrix.
4. End-to-end process and Mermaid diagrams.
5. Actor, authority and responsibility matrix.
6. Domain model and context ownership.
7. Formal invariants and decision tables.
8. Security, audit, privacy and tenant-isolation analysis.
9. Option analysis for the deciding body and its procedure.
10. Outstanding decisions, named decision owners and consequences.
11. Existing story/ADR dependency graph.
12. Proposed implementation order with explicit gates.
13. Acceptance criteria and test strategy.
14. Risks, unresolved contradictions and next actions.

Keep the document practical. Each recommendation must trace back to evidence, an explicit assumption or a clearly identified design trade-off.

## 11. Stop conditions

This is an architecture and implementation-readiness step, not automatic permission to implement.

Do not change production code, database schema, ADR decisions, Membership artefacts or story status during this mission. Do not run destructive migrations or interfere with shared test databases.

Stop and report if:

- The authority or mandate remains undecided.
- Required filing, admissibility, quorum, recusal or finality rules are missing.
- The actual repository contradicts the discovery report materially.
- A proposed change crosses a frozen architectural boundary.
- Implementation requires an approval that has not been granted.

Finish with a concise next-action recommendation, the exact decisions required from the appropriate owners, and a list of what is ready for implementation versus blocked.

The outcome must be a robust, evidence-based election adjudication design and an implementable roadmap—not an invented constitution and not an unapproved code change.

## 4. Current status and TODOs

Election adjudication

- Initial discovery of terminology, current context ownership and unresolved authority.
- Identified the existing `Challenge` / `Determination` model and likely integration gaps.
- Identified open decisions about the deciding body, appointment, procedure, jurisdiction and appeals.
- Verify all findings against the current repository state.
- Produce the decision-ready end-to-end blueprint and formal invariants.
- Obtain the required PO / ARB / Domain Owner decisions.
- Authorise and implement the first appropriate vertical slice.
- Validate end-to-end auditability, idempotency and tenant isolation.

Membership boundary

- General Membership remains outside this mission.
- Election Membership is relevant only where the approved election-challenge jurisdiction or eligibility rules require it.
- No separate Membership verification task should be started merely because the committee work is in progress.

The immediate next action is to give Claude the prompt above and have it produce the decision-ready blueprint. That advances the election process without prematurely deciding who has constitutional authority to rule.&#x20;