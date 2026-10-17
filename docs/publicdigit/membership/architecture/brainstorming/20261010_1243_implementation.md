# PublicDigit Membership — architectural review and next step

The attached report shows that the C-1 acceptance correction has completed independent review, but C-1 has not yet been formally accepted. The reviewer recommends ACCEPT WITH EXPLICIT CONDITIONS, with no blocking findings. That is a positive result, but it is not the same as formal acceptance by the Human PO/ARB.&#x20;

Eingefügter Text.txt



My recommendation as principal DDD architect and requirements reviewer is to close the C-1 decision gate first, without starting another implementation slice prematurely. There are two explicit conditions to resolve, one important condition to preserve for C-4, and a separate decision about the correction kit's design.

## 1. What the independent review actually established

Correction scope and tests

The reviewer reports that the four correction commits are present, no application code changed, the targeted Organisational tests passed (89 domain tests and 34 application tests), and 12 architecture tests passed. All ten sensitivity-kit mutations were manually confirmed to cause genuine assertion failures rather than crashes.&#x20;

Eingefügter Text.txt



The sensitivity runner has reliability weaknesses

It treats any non-zero test exit as a successful mutation kill, does not stop when the canary survives, and uses a working-tree copy rather than the planned `git archive HEAD`. The reviewer says these deviations did not invalidate today's manually checked results, but they were not disclosed in advance.&#x20;

Eingefügter Text.txt



Two architectural obligations remain open

Authority evidence and reason in provenance need to be addressed as a C-3 entry criterion. F-1 and persistence verification must remain explicit C-4 obligations. PostgreSQL persistence was not tested, and the full Membership test directories still contain pre-existing errors outside this correction's scope.&#x20;

Eingefügter Text.txt



One important distinction: the reviewer did not rerun Infection, and the bounded property test is evidence for the tested histories, not a proof of the general theory. These limitations should remain visible in our project status.&#x20;

Eingefügter Text.txt



## 2. Decisions to make now

I recommend the following decision path.

| Decision                                | My recommendation                                                                               | Reason                                                                                                                             |
| --------------------------------------- | ----------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| Condition 1 — sensitivity kit deviation | Acknowledge and record the deviations; create a separate backlog item for hardening the runner  | The current evidence was manually checked. Avoid expanding PBDIGIT-268 unless the runner defects prevent a trustworthy acceptance. |
| Condition 2 — provenance                | Add authority evidence and reason to the C-3 entry criteria                                     | The review identifies a contract-to-test gap; CQ-3 is still open. Do not silently redefine the contract in C-1.                    |
| C-4 obligation                          | Preserve F-1 and isolated-database persistence verification as mandatory entry/acceptance gates | Persistence remains unverified.                                                                                                    |
| C-1 acceptance                          | Proceed to the formal PR and Human PO/ARB decision after the conditions are recorded            | Independent review recommends conditional acceptance, not unilateral acceptance by Claude or ChatGPT.                              |

The sensitivity runner should eventually distinguish assertion failures from errors, use an immutable baseline where required by the approved design, and abort if the canary survives. Those are valuable engineering improvements, but they should be separately scoped and approved if they are not necessary to close the current acceptance gate.

## 3. Next step: Claude prepares a plan, then stops

Do not ask Claude to start C-2, C-3, or C-4 yet. The next task is a small, controlled acceptance-gate closure. Claude should first propose the exact documentation and governance changes needed to address the independent review; ChatGPT reviews that plan; the Human PO/ARB approves it; only then may Claude implement the approved changes.

# PublicDigit Membership — Plan for C-1 Conditional Acceptance Closure

## 1. Objective

Prepare the evidence and governance records required to close the independent review of the C-1 acceptance correction (PBDIGIT-268).

The independent review recommends **ACCEPT WITH EXPLICIT CONDITIONS**. Do not treat this recommendation as formal acceptance.

This is an acceptance-gate task, not a new Membership implementation slice.

## 2. Roles and authority

**Claude — Developer and evidence preparer**

- Inspect the current repository, branch, HEAD, working tree and relevant documents.
- Prepare a change plan before editing any file.
- Identify exact files, proposed wording, traceability changes, risks and verification steps.
- After the plan is reviewed by ChatGPT and explicitly approved by the Human PO/ARB, implement only the approved changes.
- Report evidence honestly and stop at the formal acceptance gate.

**ChatGPT — Principal DDD Architect and Reviewer**

- Independently review the plan against the Membership domain model, contract, invariants, requirements traceability and governance rules.
- Check that the proposed changes close the review conditions without expanding C-1.
- Distinguish facts, architectural recommendations, hypotheses and bounded test evidence.
- Recommend whether the plan is ready for Human PO/ARB approval. Do not grant formal acceptance on the Human's behalf.

**Human PO/ARB**

- Owns the reserved decisions, scope approval and formal C-1 acceptance.
- Explicitly approves or rejects the plan and later makes the formal acceptance decision.

## 3. Phase A — Read-only investigation and plan

Do not modify any files during this phase.

Inspect the current state of:

- `docs/publicdigit/reviews/2026-10-10-membership-c1-correction-independent-review.md`
- `docs/publicdigit/reviews/2026-10-10-membership-c1-acceptance-review.md`, especially §11
- The C-1 plan and story `PBDIGIT-268`
- The relevant Membership contract and backlog entries for C-3, C-4, CQ-3 and F-1
- The project state and active context files
- The existing sensitivity-kit runner and developer guide, solely to determine whether a separate hardening backlog item is appropriate

Verify the actual repository state; do not assume that the report's branch, commits or working-tree status remain unchanged.

### Condition 1 — Disclosed sensitivity-kit deviations

Propose the smallest traceable way to record that:

- The runner uses `cp -r app tests` instead of the planned `git archive HEAD`.
- The runner does not abort when the M0 canary survives.
- The runner counts any non-zero test exit as KILLED, so a crash or test error can be mistaken for a genuine mutation kill unless the result is inspected.

The independent reviewer manually confirmed that all ten current kills were genuine assertion failures. Preserve this evidence without claiming that the runner itself guarantees this distinction.

Recommend whether:

1. The deviations should be acknowledged in acceptance review §11 and runner hardening tracked separately; or
2. A minimal runner correction is necessary before acceptance.

Prefer option 1 unless you identify a specific reason that makes the current evidence unreliable. Do not extend PBDIGIT-268 without explicit approval.

### Condition 2 — Provenance contract and C-3

Propose an exact C-3 entry criterion requiring the authority evidence and reason specified by the relevant provenance contract to be represented and tested appropriately.

- Preserve CQ-3 as open until its actual decision gate is satisfied.
- Do not invent the missing provenance semantics.
- Identify the relevant contract section and code/test locations.
- Distinguish the C-1 correction's acceptance evidence from future C-3 implementation obligations.

### Condition 3 — C-4 and persistence

Ensure the C-4 entry criteria explicitly retain:

- F-1 and its approved deferral.
- Verification of persistence using an isolated PostgreSQL test database.
- Appropriate persistence and round-trip evidence before claiming persistence is verified.

Do not run persistence tests against an unapproved or shared database.

### Acceptance packet

Propose the minimum evidence needed for the subsequent PR and Human PO/ARB decision:

- Independent review and its recommendation.
- Condition-by-condition disposition.
- Commit and working-tree evidence.
- Targeted test results and known limitations.
- A clear statement that formal acceptance is still pending.

Do not rewrite the independent report; it is an append-only record.

## 4. Scope boundaries

During Phase A:

- Make no file changes, commits, pushes or implementation changes.
- Do not mark C-1 accepted.
- Do not start C-2, C-3, C-4, PBDIGIT-264, PBDIGIT-266 or PBDIGIT-267.
- Do not include unrelated strategy, traceability or MEMORY artifacts in the C-1 change set.
- Preserve existing uncommitted work.
- Do not represent pre-existing test errors as failures introduced by C-1.

Explicitly disclose the reported 27 Domain and 29 Application test errors in the wider Membership directories. They were reported as outside C-1, but their cause and remediation remain separate technical debt; do not claim the full Membership suite passes.

## 5. Required Phase A deliverable

Return a plan containing:

1. Current repository and working-tree state.
2. A condition-to-artifact traceability table.
3. Exact proposed document/backlog changes, with file paths and section identifiers.
4. Whether runner hardening is separate work or an acceptance prerequisite, with rationale.
5. Risks, unresolved decisions and verification commands.
6. Explicit out-of-scope items.
7. A proposed sequence for ChatGPT review, Human PO/ARB approval, implementation and final acceptance.

Label the result **PLAN ONLY — AWAITING ARCHITECTURE REVIEW AND HUMAN PO/ARB APPROVAL**.

Stop after presenting the plan. Do not implement it until the Human PO/ARB has explicitly approved the reviewed plan.

## 4. After Claude returns the plan

Use this sequence; each step has a different purpose.

1. Claude submits the plan. No edits yet.
2. ChatGPT reviews the plan. I will check contract fidelity, DDD boundaries, traceability, scope and whether the acceptance conditions are genuinely addressed.
3. Human PO/ARB approves the exact plan. Any change to scope or acceptance criteria requires an explicit decision.
4. Claude implements only the approved documentation/backlog changes. It verifies the diff and reports evidence without modifying the independent report.
5. ChatGPT reviews the resulting diff and evidence. Then the Human PO/ARB makes the formal C-1 acceptance decision through the agreed PR/acceptance process.
6. Only after the gate closes should we ask Claude to prepare a separate plan for the next authorised Membership slice.

## 5. How far are we from the Membership implementation goal?

The most important distinction is between domain theory, a validated correction slice, and a production-ready Membership implementation. They are different milestones.

## Current status

C-1 acceptance correction

Independent review complete; formal acceptance pending.

Conditional

Membership domain model and contracts

Substantial work exists; several correction, provenance and authority questions remain open.

In progress

Persistence and production readiness

PostgreSQL persistence verification, broader regression, and migration/cutover controls remain.

Not verified

I would not assign a new overall percentage from this report alone: it documents one correction and its review, not a fresh audit of every Membership deliverable. The earlier project estimates are useful planning signals, but they are not proof that the end-to-end implementation is complete.

### Remaining TODOs

Immediate — close the C-1 gate

- Claude prepares the plan only.
- ChatGPT independently reviews the proposed plan.
- Human PO/ARB explicitly approves the plan.
- Record the sensitivity-kit deviations accurately and decide whether runner hardening is separate work.
- Add the authority-evidence/reason requirement to C-3 entry criteria, preserving CQ-3 as open until resolved.
- Preserve F-1 and isolated-database persistence verification as C-4 obligations.
- Implement only approved changes; review the resulting diff.
- Complete the PR and obtain formal Human PO/ARB acceptance.
- Keep unrelated uncommitted strategy, traceability and MEMORY work separate.

Next — Membership domain and correction slices

- Prepare and approve C-2, C-3 and C-4 plans separately; do not treat the C-1 approval as authorisation for them.
- Resolve the applicable open correction decisions, including D-COR-1, D-COR-3, D-COR-4, BD-REV-ER and D-COR-5(B), according to the authoritative backlog.
- Resolve CQ-3 / G-AUTH-1 and CQ-9 through their proper decision gates.
- Address F-1 in C-4 and verify persistence in an isolated PostgreSQL environment.
- Track the pre-existing wider Membership test errors separately and establish a trustworthy regression baseline.

Before production or cutover

- Verify persistence round trips, tenant isolation, concurrency and legacy reader/writer behaviour.
- Implement and verify the migration and reverse-projection single-writer strategy.
- Complete required audit trails, projections and remaining application integration.
- Obtain the required ARB-MEM-01 decision before cutover or live writes.
- Run full regression in an approved, safe environment and document any remaining exceptions.
- Ensure the requirements, invariants, tests, implementation and acceptance evidence are traceable.

Research and assurance

- Keep ratified rules separate from hypotheses, derived invariants, bounded property tests and empirical results.
- Extend property/differential testing where it addresses a documented gap or counterexample.
- Treat finite-history testing as bounded evidence, not universal proof.
- Use machine learning only where a justified data-driven problem exists; deterministic Membership rules should remain explicit, testable domain logic.

Bottom line: the immediate next action is Claude's plan-only response for C-1 conditional acceptance closure. We should not begin the next implementation slice until the conditions are resolved and the Human PO/ARB has formally closed the current gate.&#x20;