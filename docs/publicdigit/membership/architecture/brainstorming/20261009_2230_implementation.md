# Principal review: Membership theory and next engineering step

I have read the attached Claude session transcript and reviewed its findings from the perspective of a principal reviewer, senior DDD architect, requirements engineer, formal-methods researcher, and implementation architect.

My main conclusion: We have made meaningful progress in defining Membership as a domain, but the next step should not be another isolated committee analysis or an immediate implementation. We should consolidate the existing research corpus into a testable Membership theory with explicit business rules, authoritative evidence, counterexamples, and a dependency-ordered implementation roadmap.

The latest work gives us important new evidence about governance authority, committee mandates, and the boundary between Membership and other domains. It also identifies a possible security investigation that must be treated separately from the Membership research.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



## 1. Principal-review findings

1\. The domain boundaries are becoming clearer

The core distinction remains:

`Person ≠ Member ≠ MembershipPeriod ≠ CommitteeAppointment ≠ ElectionRegistration ≠ VotingEntitlement ≠ Exercisability`

Membership represents the organisation–person relationship. It does not, by itself, establish election eligibility, committee authority, or access rights. These distinctions must survive the eventual implementation, not merely appear in documentation.

2\. Committee responsibility is not yet a complete authority model

The research finds that current live authority relies on organisation roles and election officers, while the intended Financial and Organisational Committee models are not fully represented in code. It also finds that the Membership authority port cannot yet express a collective committee decision with a subject, decision reference, and relevant time.&#x20;

Eingefügter Text.txt



The next research must establish the contract between Governance authority and Membership decisions without assuming that a committee has powers simply because its name suggests them.

3\. Formal modelling has exposed meaningful counterexamples

The bounded models report:

- 35 of 375 cases where checking a delegation only at creation permits an out-of-mandate action.
- 522 of 3,645 decision cases where counting appointed rather than effective seats changes the quorum outcome.
- 12 of 108 cases where a decision passes only if an interested member is counted.
- 7 of 16 role configurations that permit one person to approve and execute a payment.

These are counts in finite models—not real-world probabilities. The models identify decisions and risks that require explicit business rules. The report correctly warns that the zero-divergence result for use-time authority checking is weak evidence because that strategy closely restates the specification.&#x20;

Eingefügter Text.txt



4\. The possible authorisation gaps need a separate, controlled investigation

`PBDIGIT-266` records four routes where a role or authority guard could not be found during static inspection. These are possible security defects, not confirmed vulnerabilities. Feature tests must establish whether the suspected guards are actually missing before any corrective implementation is proposed. The election approval route has the highest stated priority if the gap is confirmed.&#x20;

Eingefügter Text.txt



5\. Verify repository state before trusting the session summary

The transcript reports research documentation committed as `177caf687`, with no migrations or tests changed. However, its stop-hook output also says database implementation code changed and a `developer_guide/database/` update is needed. These statements may refer to different work or sessions, but the transcript does not establish that conclusively.

Claude must inspect the actual `HEAD`, working tree, staged changes, and commit diff. It must not infer repository cleanliness or implementation status from the narrative summary alone.

## 2. What the next step should achieve

The next step should be a Membership Theory Synthesis and Implementation Readiness Audit.

It should connect five layers:

A. Business meaning

What Membership is, what it is not, and which business outcomes it governs

B. Formal domain rules

States, temporal semantics, invariants, authority, corrections, annulments, and counterexamples

C. Context ownership

Membership, Governance, Election, Finance, Audit, and derived access policies

D. Executable specification

Kernel coverage, property-based tests, independent oracle, adversarial histories, and persistence contracts

E. Implementation readiness

Traceable requirements, approved decisions, safe migration gates, test evidence, and ordered backlog

The central research question is:

Can we derive one coherent, internally consistent Membership theory from the current corpus, show exactly which parts are adopted versus hypothesised, and demonstrate how each approved rule will be enforced and tested in the real implementation?

## 3. Copy-ready Claude prompt for the next step

This prompt deliberately commissions research and implementation-readiness analysis, not production changes. That lets Claude use the new committee research without accidentally turning open business questions into approved rules.

# Claude Code Commission: Membership Theory Synthesis and Implementation Readiness Audit

## 1. Role and standard of work

Act as a principal DDD architect, senior requirements engineer, formal-methods researcher, mathematician, statistician, security-aware software architect, and implementation-readiness reviewer.

Your mission is to observe the existing PublicDigit corpus as research and brainstorming material, derive the strongest defensible Membership theory from the available evidence, and connect that theory to a practical, safely sequenced implementation.

Do not optimise for producing more documents or more backlog items. Optimise for **semantic correctness, evidence quality, domain ownership, falsifiability, implementation feasibility, and preservation of approved decisions**.

Work as a critical researcher. Challenge contradictions, search for counterexamples, distinguish assumptions from facts, and state when evidence is insufficient.

## 2. Commission and scope

Perform a **Membership Theory Synthesis and Implementation Readiness Audit** in the existing repository.

Research and documentation only. Do not implement production code, modify migrations, change tests, change database schemas, change runtime configuration, alter application behaviour, push commits, or authorise backlog items.

Do not execute `PBDIGIT-264`, `PBDIGIT-266`, `PBDIGIT-265`, Slice 2, Governance integration, HTTP/UI work, or cutover work under this commission.

Stop at the end of the research deliverables and report the exact gates that require explicit authorisation.

## 3. First: establish the real repository state

Before substantive work:

1. Inspect the actual repository path, branch, `HEAD`, staged changes, unstaged changes, untracked files, and recent relevant commits.
2. Verify the reported commit `177caf687` and identify the actual current `HEAD`. Do not assume the transcript's repository state is current.
3. Reconcile the session transcript's statement that no implementation code changed with the stop-hook warning about database implementation changes and the missing `developer_guide/database/` update.
4. Determine which changes belong to this session, prior sessions, or concurrent agents. Preserve all work you cannot safely attribute.
5. Do not reset, stash, revert, clean, overwrite, or reformat other people's changes. Do not stage or commit unrelated work.
6. Update the session/context state only if the repository's existing workflow requires it and the change is safe. Record uncertainty rather than inventing a clean state.

If repository state is ambiguous or concurrent work could be overwritten, stop and report the blocker before editing anything.

## 4. Read the corpus before deriving conclusions

Inspect the relevant existing documents and executable models, including at least:

- The ratified Membership architecture and its 2026-10-08 ratification decisions.
- `docs/publicdigit/membership/architecture/brainstorming/` and its relevant research artefacts.
- `2026-10-09-organisational-participation-domain-research.md`.
- `2026-10-09-participation-semantic-contract-draft.md`.
- `docs/publicdigit/reviews/2026-10-09-committee-responsibility-authority-and-membership-boundary-discovery.md`.
- `docs/publicdigit/reviews/2026-10-09-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md`.
- `docs/publicdigit/membership/architecture/models/README.md` and the relevant Python models, including `g5_committee_authority.py`, `g2_ledger_falsify.py`, and the kernel admissibility bridge.
- The Membership domain implementation, ports/adapters, persistence contracts, tests, backlog index, and relevant project context files.
- The records concerning `PBDIGIT-264`, `PBDIGIT-265`, `PBDIGIT-266`, and Slice 2 decisions D-1 through D-6.

Use the actual paths present in the repository. If a named file is absent, record that fact and find its canonical successor; do not silently substitute an unrelated document.

Preserve the corpus's classification discipline. At minimum distinguish:

- `[ADOPTED]` — formally adopted or ratified.
- `[DEMO]` — demonstrated by inspected code or executed test.
- `[MODEL]` — result within an explicitly bounded model.
- `[CX]` — concrete counterexample.
- `[HYP]` — hypothesis or proposed rule.
- `[BIZ]` — business decision still required.
- `[NV]` — not verified.
- `[BLOCKED]` — cannot be concluded or implemented because a dependency is unresolved.

A research document, transcript, test, model, or backlog entry is not automatically an authoritative business decision. Trace conclusions to their actual decision authority.

## 5. Derive the coherent Membership theory

Synthesize rather than merely concatenate the corpus.

Explicitly analyse the distinctions:

`Person ≠ OrganisationAffiliation ≠ Member ≠ MembershipPeriod ≠ CommitteeAppointment ≠ Tenure ≠ Mandate ≠ Authority ≠ Duty ≠ ElectionRegistration ≠ VotingEntitlement ≠ Exercisability`

For each concept, specify:

1. Its business meaning and purpose.
2. Its identity and lifecycle.
3. The bounded context that owns its authoritative facts.
4. Its temporal semantics.
5. Its permitted transitions and evidence requirements.
6. The invariants that must always hold.
7. The contexts allowed to query it and the contexts prohibited from writing it.
8. The implementation location and tests that should enforce it.
9. Its adoption status and unresolved dependencies.

Do not assume that all these concepts must become separate database tables, aggregates, or services. Derive implementation boundaries from consistency requirements and business invariants.

Examine whether the theory consistently supports:

- Admission, ending Membership, re-entry, correction, annulment, and historical reconstruction.
- The distinction between valid time and recorded/knowledge time.
- The meaning of Membership when an event is corrected or annulled.
- Authority evidence for admitting, ending, correcting, or annulling Membership.
- The difference between a person's own act, a registrar's attestation, an individual authorised act, and a collective organ decision.
- Election-Only mode, including its independence from the organisation Membership ledger.
- Full Membership mode and the unresolved `PBDIGIT-265` eligibility-predicate mismatch.
- Derived access and projections without granting those projections authority over Membership.
- The relationship between Membership facts, financial obligations, payments, committee appointments, and audit evidence.

Do not change adopted semantics to make the implementation easier. Where two adopted rules appear incompatible, report the contradiction and the authority needed to resolve it.

## 6. Treat Governance and committees as a boundary problem

Use the committee responsibility/authority discovery as evidence, not as blanket authorisation.

Keep these questions separate:

- Which organ has responsibility for a function?
- Which organ or person has authority to make a decision?
- Who is authorised to execute that decision?
- Who remains accountable or has a duty to act?
- What procedure makes a collective decision valid?
- What evidence must the owning context retain?

Investigate the port gap identified as `G-AUTH-1`: the current Membership authority check cannot express the subject, decision reference, or as-of date, and its evidence cannot adequately represent a committee decision.

Describe the minimal future contract that would support this requirement, but do not implement or ratify it. Identify compatibility implications for Slice 2/D-4.

Preserve the unresolved questions, including:

- `CQ-1`: the meaning of “Election Committee” versus the independent acceptance body.
- `CQ-2`: whether the Organisational Committee is the authorised organisational governance body.
- `CQ-3`: which organ decides each Membership act.
- `CQ-4`: the Financial Committee's mandate and separation of duties.
- `CQ-5`: whether each type of seat requires current Membership and what exceptions exist.
- `CQ-6`: validity of actions after a tenure end is recorded retroactively.
- `CQ-7`: conflict-of-interest and recusal rules.
- `CQ-8`: delegation rules and revalidation at time of use.
- `CQ-9`: appeal and general assembly powers.
- `CQ-10`: which rules are fixed platform rules versus organisation-specific statutory choices.

Do not answer these by inference. Show their dependencies and decision owners.

## 7. Formal methods and mathematical quality

Use mathematics and executable models to test the theory, not to create an illusion of certainty.

For every important invariant:

1. State the invariant in precise natural language and, where useful, formal notation.
2. Define the state variables, preconditions, postconditions, and temporal assumptions.
3. Construct a minimal counterexample where the invariant can fail.
4. Identify whether the counterexample is already covered by an existing model or needs a future model.
5. State the bounded domain, state-space coverage, and known omissions.
6. Distinguish proof, exhaustive enumeration over a finite domain, random/property-based testing, and ordinary example tests.
7. Compare model results against an independent oracle where feasible.
8. Avoid circular validation in which the implementation and oracle use the same flawed rule.
9. Explain whether each result changes a business decision, an architecture contract, a test, or only a hypothesis.

Explicitly retain the existing caveats:

- A count of enumerated cases is not a probability of real-world failure.
- Agreement with a strategy that restates the specification is weak evidence.
- Membership histories involving Annul/Correct cannot be described as kernel-verified while those operations are not implemented in the kernel.
- Python-only filters must remain labelled as hypotheses rather than real-kernel validation.
- Any unchecked histories or assumptions must remain visible in the conclusions.

Investigate high-value counterexamples involving re-entry, overlapping Membership periods, retroactive corrections, annulled facts, late-recorded tenure changes, delegation after mandate amendment, quorum, recusal, and cross-context writes.

Do not broaden the model indiscriminately. Prioritise scenarios that could change Membership legitimacy, authority, election eligibility, financial outcomes, or access.

## 8. Statistics and machine learning: use only where justified

First determine whether the available corpus contains suitable empirical data. Do not claim statistical confidence without an appropriate sampling frame or data.

Use bounded enumeration for finite state spaces, property-based testing for invariant exploration, and mutation testing where available to assess whether tests detect deliberately introduced semantic errors.

Consider machine learning only for suitable auxiliary tasks, such as:

- Classifying and clustering duplicate or conflicting legacy Membership records for human review.
- Ranking migration anomalies for investigation.
- Identifying recurring patterns in inconsistent historical data.
- Suggesting candidate links between requirements, tests, models, and implementation artefacts.

For any ML proposal, specify input data, labels or absence of labels, evaluation metrics, false-positive/false-negative costs, human review, and reproducibility. Do not introduce an ML system merely because the commission mentions ML.

Never use a model's prediction as the authority for Membership legitimacy, admission/exclusion, committee authority, voting entitlement, or access rights. Those decisions must follow explicit, auditable business rules.

## 9. Trace theory to the real implementation

Build an evidence-based traceability matrix:

`Business statement → adopted rule → invariant → owning context → command/port → persistence fact → test/model → backlog item → release gate`

For each row classify implementation as:

- Implemented and verified.
- Implemented but incompletely verified.
- Specified but not implemented.
- Partially implemented.
- Blocked by a business or architecture decision.
- Suspected defect awaiting confirmation.
- Not investigated.

Inspect real code and tests before assigning a status. Cite file paths and symbols. Do not infer implementation from documentation alone.

Assess the existing Membership kernel, persistence Slice 0, Slice 1a, Slice 1b, command/idempotency persistence, authority port, and correction/annulment coverage. Reconcile any status differences with the prior reports.

Keep persistence remediation separate from business-semantic changes. `PBDIGIT-264` remains unauthorised unless an explicit authorisation is found in the authoritative project record. A historical transcript mentioning an option or recommendation is not, by itself, permission to execute it.

## 10. Security investigation boundary

Review `PBDIGIT-266` only to improve prioritisation and dependency understanding.

Do not send HTTP requests, execute confirming security tests, change route middleware, change controllers, or fix suspected defects under this research-only commission.

Report the four suspected route-guard gaps with their current evidence class. Recommend the smallest separate, explicitly authorised test-first investigation. State clearly that static absence in the files inspected does not prove the absence of a guard elsewhere.

If actual repository evidence establishes that another session changed implementation code, preserve it and report the discrepancy. Do not undo it.

## 11. Required deliverables

Create or update the smallest coherent set of research documents in the existing Membership architecture/research area:

**A. Membership Theory Synthesis**

- Canonical glossary and conceptual model.
- Adopted rules versus hypotheses.
- Context ownership and prohibited cross-context writes.
- Temporal/lifecycle semantics.
- Invariants and evidence requirements.
- Contradictions and unresolved business decisions.

**B. Formal Evidence and Counterexample Register**

- Invariant catalogue.
- Model assumptions and bounded domains.
- Minimal counterexamples.
- Independent-oracle status.
- Kernel-verified versus unchecked histories.
- What each result does and does not establish.

**C. Theory-to-Implementation Traceability Matrix**

- Rule-to-code-to-test-to-backlog mapping.
- Actual implementation status and evidence.
- Missing contracts and tests.
- Dependencies and release gates.

**D. Dependency-Ordered Implementation Readiness Roadmap**

- The smallest safe implementation slices.
- Preconditions and authorisation gates.
- Required tests and migration preflight/rollback evidence.
- Acceptance criteria.
- Explicitly deferred work.
- Business decisions that cannot be replaced by technical inference.

Link the documents to the existing corpus rather than copying all prior content. Update indexes/context records only as required by repository conventions. Do not create duplicate backlog tickets merely to restate known TODOs.

## 12. Acceptance criteria

The commission is complete only when:

1. The actual repository state and the transcript discrepancy are reconciled or explicitly reported unresolved.
2. Every substantive conclusion is traceable to evidence and labelled by epistemic status.
3. Membership, committee authority, election eligibility, finance, and access remain distinct concepts with explicit context ownership.
4. Adopted decisions are not silently rewritten.
5. Every important model result states its domain, limitations, and practical implication.
6. The traceability matrix exposes the difference between theory, executable specification, implementation, and verified production readiness.
7. The roadmap is ordered by dependencies rather than by convenience.
8. `PBDIGIT-264`, `PBDIGIT-265`, `PBDIGIT-266`, Slice 2, and production/cutover gates remain unchanged unless authoritative approval already exists.
9. No unauthorised implementation, migration, test modification, or production change occurs.
10. All unrelated and concurrent work remains untouched.

## 13. Final response format

Report:

1. Executive conclusion: is the current Membership theory coherent, and where is it not?
2. Repository state and discrepancy findings.
3. The strongest confirmed rules and counterexamples.
4. What remains hypothesis, unchecked, or dependent on a business decision.
5. Membership implementation progress, separating conceptual, kernel, persistence, integration, and production readiness.
6. A dependency-ordered TODO list with an owner or decision authority, prerequisite, and completion evidence for each item.
7. The single recommended next action and why it has priority.
8. Files created or updated, commit status, and confirmation that no unauthorised implementation occurred.

Stop after delivering the research and roadmap. Wait for explicit authorisation before implementation.

## 4. How far are we from the Membership implementation goal?

My assessment separates progress in the theory from progress in executable and operational implementation. A mature design does not automatically mean the system is ready for production.

Membership domain theory

Core vocabulary, lifecycle distinctions, and domain boundaries

Advanced

Formal models and falsification

Several bounded models and useful counterexamples exist; some histories remain unchecked

Advanced, incomplete

Membership kernel and early slices

Slice 0 and Slice 1a reported implemented; Slice 1b has conditional acceptance, not final acceptance

Partial

Persistence remediation

PBDIGIT-264 still requires explicit authorisation; migration and verification gates remain

Blocked

Governance authority integration

Committee mandates and Membership decision authority are not fully specified or implemented

Blocked

Full integration and production readiness

Election cutover, correction/annulment coverage, regression, and production evidence remain outstanding

Not established

These are qualitative assessments based on the supplied transcript and previously recorded project state—not a fresh execution of the repository's test suite. The actual repository must be inspected before declaring current implementation status.&#x20;

Overall: We have a substantial foundation for a robust Membership theory, but we have not yet demonstrated end-to-end implementation readiness. The critical remaining work is to connect every approved business rule to its authoritative context, executable enforcement, persistence behaviour, and tests.

## 5. Remaining TODOs — in dependency order

### Priority 1: Establish a trustworthy baseline

- Reconcile repository state. Verify the current branch, `HEAD`, commit `177caf687`, working tree, and the database-change warning. Identify any implementation changes that the transcript did not account for.
- Complete the Membership Theory Synthesis. Consolidate the existing research into one coherent theory without duplicating or silently changing ratified rules.
- Complete the theory-to-implementation traceability matrix. Establish which rules are implemented, tested, incomplete, blocked, or not yet investigated.
- Audit model coverage. Distinguish kernel-verified histories from Python-hypothesis-only histories and explicitly identify the effects of missing Annul/Correct operations.

### Priority 2: Resolve the highest-impact decision gates

- Authorise and investigate `PBDIGIT-266`. Run the separately approved, test-first investigation of the four suspected route-guard gaps. Confirm or refute each suspected defect before proposing a fix.
- Obtain explicit Product Owner authorisation for `PBDIGIT-264`. Then execute the approved persistence remediation, including migration preflight, integrity checks, populated-schema round-trip testing, and rollback verification.
- Resolve `CQ-1`. Ratify the vocabulary distinguishing the operating election leadership from the independent acceptance committee.
- Resolve `CQ-2` and the dependencies `EM-OPEN-049` / `EM-OPEN-066`. Establish who has authority to appoint the relevant election officers and constitute the acceptance committee.
- Resolve `CQ-3` with Legal and Governance. Define who can review, admit, exclude, suspend/restore where authorised, correct, annul, and attest migrated Membership records.
- Resolve Slice 2 decisions D-1 through D-6. In particular, settle D-4 and the authority-evidence requirements before designing the long-term Governance integration.

### Priority 3: Finish the Membership contracts

- Specify `G-AUTH-1`. Define how Membership commands carry the subject, decision reference, and relevant as-of date, and how authority evidence represents the organ, decision, tenure, mandate version, and procedure outcome.
- Resolve `CQ-5` through `CQ-10` with the relevant business owners. This includes Membership requirements for committee seats, retroactive tenure validity, recusal, delegation, appeal powers, and platform-versus-statute rules.
- Complete the Membership correction and annulment implementation plan. Keep ratified algebra separate from operations that the current kernel can actually execute and verify.
- Resolve `PBDIGIT-265`. Align the Full Membership eligibility predicate with the authoritative Membership contract before election cutover.
- Confirm Finance boundaries. Decide the approval mandate and separation-of-duties rules; do not allow Membership operations to write Finance-owned facts.
- Keep Election-Only mode independent. Verify that it does not require an organisation Membership ledger or accidentally acquire Membership authority.

### Priority 4: Implement and verify incrementally

- Complete Slice 1b acceptance. Verify its outstanding conditions before treating it as accepted or production-ready.
- Implement the approved persistence slice only after its authorisation and preconditions are satisfied.
- Implement Governance authority integration only after the necessary mandate, appointment, and Membership-decision contracts are approved.
- Add invariant-driven tests. Cover re-entry, temporal corrections, annulment, delegation after mandate amendment, effective quorum, recusal, and cross-context write prevention.
- Complete the full regression and migration verification. Record baseline versus post-change results and explain any test delta.
- Complete the production/cutover evidence. Verify environment assumptions, database-engine compatibility, security controls, and the unresolved Election eligibility blocker.

### Priority 5: Improve research quality and operational maintainability

- Maintain a counterexample register linked to each invariant, model, test, and decision.
- Use mutation and property-based testing where they provide additional evidence beyond existing tests.
- Evaluate ML only for suitable supporting work, such as migration anomaly triage and duplicate-record classification. Keep all authority and eligibility decisions rule-based and auditable.
- Update the developer guide if the repository investigation confirms that implementation changes occurred and the database guide is missing or outdated.
- Keep the backlog and project context synchronised with verified status, explicit decision owners, and dependencies.

## Final recommendation

The next action should be the Membership Theory Synthesis and Implementation Readiness Audit described in the Claude prompt above. It will establish a reliable baseline and show exactly which decisions, tests, and implementation slices are required to complete the Membership goal.

The security investigation `PBDIGIT-266` should remain a separate, high-priority authorisation decision; it must not be buried inside the research work or treated as a confirmed vulnerability.

The governing principle for the next stage is:

No business assumption becomes an authoritative rule merely because it appears in a model or document; no implementation is considered complete merely because its tests pass; and no production-readiness claim is made without the corresponding evidence.