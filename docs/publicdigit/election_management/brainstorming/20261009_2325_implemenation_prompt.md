## 2. One important limitation before choosing the next mission

The session log references this repository file:

`docs/publicdigit/election_management/brainstorming/20261009_2323_implemenation_prompt.md`

However, the attachment contains the log of Claude's session, not the contents of that Markdown prompt. I could not locate a separate copy in the available file library. Therefore, I cannot honestly approve or reject the prompt's detailed instructions yet. The next Claude mission should read the actual file and explicitly report any disagreements before acting.&#x20;

Eingefügter Text.txt



There is also an important scope distinction. The current evidence package is about adjudication and tenant isolation; the referenced new task concerns Membership. We should maintain separate workstreams and avoid reopening frozen adjudication discovery just because Membership work continues.

## 3. My recommended next step

The next mission should be Membership — Prompt Review, Contract Readiness and Small-Slice TDD.

The Membership architecture is already ratified. The available prior context indicates that Slice 0 and Slice 1a have been implemented, Slice 1b has undergone acceptance review, and Slice 2 decisions remain open. Consequently, the right next action is not to invent another membership theory from scratch. It is to evaluate the referenced prompt against the accepted architecture and identify the next authorized, minimal implementation slice.

The research should be practical and falsifiable:

- Formalize the membership rules that actually affect decisions.
- Distinguish accepted rules, derived implications, assumptions, and unresolved policy choices.
- Use mathematical models and independent test oracles to verify temporal membership, identity, provenance, and invariants where relevant.
- Use property-based, exhaustive bounded, and metamorphic tests where they provide meaningful coverage.
- Implement only a small slice whose contract and authorization are already established.
- If Slice 2 has unresolved decisions that affect its semantics, stop before implementation and prepare the precise decision package.

## 4. Prompt instructions for Claude Code

# Mission 5 — Membership Prompt Review, Contract Readiness and Small-Slice TDD

## Role

Act as a principal DDD architect, senior requirements engineer, mathematical and logical reviewer, software assurance researcher, and TDD implementation lead for PublicDigit.

PublicDigit is a digitalization platform for organisations and political parties, with online voting as a core capability. Membership is a foundational domain: its semantics must be correct before other bounded contexts can rely on membership decisions.

## 1. Read and evaluate the actual commission

Open and read the complete file:

`docs/publicdigit/election_management/brainstorming/20261009_2323_implemenation_prompt.md`

Do not infer its contents from its filename or a previous session log.

First, produce a short assessment:

1. What objective does the prompt define?
2. Which existing decisions and contracts govern it?
3. What does it authorize?
4. What are its stop conditions?
5. Do you agree with the proposed sequence?
6. What material disagreements, contradictions, missing prerequisites, or safety risks exist?

If you disagree with a material instruction, explain the disagreement before taking action. Distinguish:

- a genuine contradiction with an accepted decision;
- an engineering risk;
- an optional improvement;
- a matter requiring Product Owner or architecture-board authority.

Do not silently rewrite the commission or treat brainstorming text as an accepted requirement.

## 2. Re-establish Membership's current state

Inspect the actual repository and relevant records, including:

- The ratified Membership architecture and ratification act.
- EP-01 and its accepted revisions.
- The Slice 0 semantic-design contract and implementation review.
- The Slice 1a and Slice 1b contracts, plans, implementation reviews, and acceptance records.
- The current Slice 2 decisions and any existing contract.
- Membership backlog items, developer guides, and project context.
- The actual Git branch, HEAD, working tree, staging area, and recent commits.

Use the repository as the source of truth. Verify reported implementation and test results rather than assuming that a prior session's summary proves them.

Protect concurrent-session changes. Do not reset, stash, overwrite, or discard work. Do not amend or push commits.

## 3. Preserve the accepted architecture

Membership architecture is ratified. Do not reopen ontology or architecture research without evidence of a specific contradiction.

Preserve the established separation between:

- pure Membership domain semantics;
- application decisions and repository contracts;
- persistence, concurrency, and database constraints;
- Election, Finance, and Governance contexts.

Keep the pure domain deterministic and independent of Laravel, persistence, clocks, random-number generation, and infrastructure concerns where required by the accepted contract.

Do not introduce new Membership lifecycle operations, consequences, events, projections, or cross-context responsibilities merely because they appear useful.

Use only the actual ratified contracts and recorded decisions to establish what is permitted.

## 4. Formal and mathematical review

Treat the existing corpus as research evidence, not as permission to invent new policy.

For each rule relevant to the proposed slice, identify:

1. The domain statement in business language.
2. Its formal interpretation.
3. Its preconditions and postconditions.
4. The invariants it must preserve.
5. The accepted source or contract clause.
6. The tests that could falsify an incorrect implementation.
7. Any unresolved ambiguity that changes observable behaviour.

Where temporal membership semantics apply, explicitly distinguish valid time from recording or decision time if the accepted contract requires that distinction.

Where useful, define a reference model independently of the production implementation. Avoid circular verification in which the implementation and the oracle share the same assumptions or algorithm.

Use appropriate methods:

- exhaustive bounded tests for small finite state spaces;
- property-based tests for invariants across generated inputs;
- metamorphic tests for transformations that should preserve outcomes;
- differential testing between the implementation and an independent reference model;
- state-transition and invariant checks for lifecycle behaviour.

Do not introduce machine learning where deterministic domain rules are required. ML may assist research or anomaly discovery, but it must not decide membership eligibility, authority, or election outcomes.

## 5. Decide whether implementation is authorized

Before writing production code, classify the proposed next slice as one of:

**A. Ready for implementation**

The relevant contract is approved, the semantics are unambiguous, the scope is bounded, and implementation authorization is established.

**B. Ready for contract or decision preparation only**

A policy or semantic decision remains open and affects the implementation. Prepare a minimal decision table, including options, consequences, invariants, and example cases. Do not implement behaviour that depends on an unresolved decision.

**C. Blocked**

The prompt conflicts with the ratified architecture, the required authority is missing, or the repository state prevents safe work. Explain the blocker and stop.

Do not infer implementation authorization from the existence of a brainstorming prompt or from another slice having been implemented.

## 6. If implementation is authorized, use one small TDD slice

Choose exactly one minimal behaviour from the approved scope.

Follow this order:

1. State the business rule and its source.
2. Define the observable acceptance criteria.
3. Write a failing test first.
4. Confirm that the test fails for the intended reason.
5. Implement the minimum production change.
6. Make the test pass.
7. Add the appropriate invariant, property, or reference-model test where justified.
8. Run the relevant regression tests using the repository's established safe test configuration.
9. Inspect the diff for accidental scope expansion and violations of domain purity.
10. Update the relevant developer guide, plan, and backlog evidence as required by the Definition of Done.
11. Produce an implementation review with exact commands and observed results.

Do not begin a second slice in the same mission. If the first slice exposes a new policy decision, stop and record it.

### Database and test safety

- Inspect the effective database connection before any test that can mutate data.
- Never run destructive migration commands against a shared database.
- Use an explicitly authorized, isolated test database where required.
- Do not assume that a PHPUnit configuration or environment variable guarantees isolation; verify the actual connection.
- Do not rerun unrelated adjudication tests as part of Membership work.

## 7. Required outputs

Produce:

1. Prompt assessment: agreement, disagreements, and conditions.
2. Repository-state verification.
3. Traceability from accepted rules to the proposed slice.
4. A concise formal rule and invariant table.
5. The implementation-readiness classification A, B, or C.
6. If A and authorized: the single TDD slice and its implementation review.
7. If B: the decision package needed to unblock the slice.
8. A prioritized TODO list.
9. An explicit statement of what was changed, what was tested, and what remains unverified.

## Stop conditions

Do not restart frozen architecture research. Do not invent requirements. Do not cross Membership boundaries into Election, Finance, or Governance. Do not implement unresolved Slice 2 semantics. Do not modify unrelated work, and do not commit or push unless explicitly authorized.

The mission's objective is to advance Membership by one verified, contract-compliant step—not to maximize the number of changed files.

## 5. Current implementation progress and remaining TODOs

Based on the available records, this is the current assessment—not a fresh audit of the repository.

### Membership workstream

- Ratified Membership architecture and established the implementation approach.
- Slice 0 semantic kernel implemented with TDD; its recorded review was GREEN WITH CONDITIONS, including the `DuplicateFactId` deviation requiring reviewer confirmation.
- Slice 1a repository/concurrency work reported implemented and green with conditions.
- Slice 1b acceptance review reported complete in the prior context.
- Review the actual `20261009_2323_implemenation_prompt.md` before approving its exact sequence.
- Reconcile the remaining Slice 0 reviewer condition and administrative backlog/context records if still open.
- Resolve the open Slice 2 decisions and approve its contract.
- Implement the next authorized slice using RED → GREEN → independent verification.
- Complete the necessary persistence, database constraints, concurrency checks, and integration work in their approved slices.
- Validate membership invariants across the full implementation, not only the pure domain kernel.
- Complete cutover and operational verification before relying on the new model in production.

### Adjudication workstream — remains separately blocked

- Obtain the ARB ruling on tenant-context questions Q1–Q7.
- Authorize and implement PBDIGIT-211 tenant isolation remediation.
- Assign and authorize T-3 and T-5 backlog items.
- Resolve T-5's durable failure-recording schema separately.
- Continue the accepted sequence `178 → 177 → 174 → 182`.
- Complete challenge filing persistence, determination finality, horizon/lapse behaviour, and end-to-end correction-loop verification.

### Overall assessment

PublicDigit is beyond the initial domain-discovery stage. The Membership domain has a ratified architecture and multiple implemented slices, while adjudication has useful runtime evidence but unresolved tenant-isolation defects and an incomplete end-to-end correction loop.

The immediate Membership milestone is to verify the actual prompt, close any contract-level blockers, and authorize one minimal TDD slice. That is the most disciplined way to advance the platform without destabilizing the architecture or mixing independent workstreams.&#x20;