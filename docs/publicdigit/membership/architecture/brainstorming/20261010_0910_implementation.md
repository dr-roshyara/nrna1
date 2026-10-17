# Principal Reviewer assessment — PublicDigit Membership

I reviewed the attached Claude transcript as a continuation of our Membership architecture work. My conclusion is that we have reached an important transition: the correction specification and decision package are prepared, but the remaining blocker is now formal architectural governance—not another round of unrestricted discovery.

The next step should obtain the actual Principal Reviewer/ARB decisions, verify the outstanding approval requirements, and preserve a clear path into test-first implementation of C-1. We must continue evolving the existing `app/Contexts/Membership` bounded context, not create a second one.

## 1. My review of the current state

Completed according to the attached report

- Correction contract reconciled through §16.
- PO decisions recorded in the gate register.
- D-COR-5 architecture decision request prepared.
- Membership project context and session log updated.
- Conflicting older wording identified and marked as superseded where appropriate.

Still outstanding

- Principal Reviewer concurrence on D-COR-2, or a formal determination that concurrence is unnecessary.
- Principal Reviewer and ARB decision on D-COR-5 (architecture).
- Formal approval of the correction contract.
- Verification that all C-1 start conditions have been satisfied.

Not yet achieved

- C-1 implementation has not started.
- `Annul` and `Void` have not been implemented as correction operations.
- Production readiness, cutover and live-write authorisation have not been established.

These statuses come from the attached transcript. I have not independently inspected the repository or rerun its checks in this turn.

## 2. Principal Reviewer findings

### Finding A — The architecture decision is correctly isolated

The prepared request asks whether the existing ratified rule remains unchanged: `Annul` and `Void` change fact liveness, but do not create new period-trimming consequences.

The report identifies the conflict between that rule and older wording in BD-REV, BD-TERM4 and final-proof scenario H.

My architectural recommendation is to preserve the explicitly ratified no-consequence rule unless the authorised governance process formally amends it. The older conflicting wording should not silently redefine the kernel.

The critical state transition is:

1. A Member has an earlier period.
2. An `End` closes that period.
3. A later `Admit` creates a re-entry period.
4. Annulment of the earlier `End` would reopen the earlier period and create an overlap.
5. G\* rejects the operation if the resulting state violates INV-9.

The kernel must not invent a trimming consequence merely to make this sequence succeed. What a successful appeal after re-entry should mean remains a separate business/legal question for the PO and Legal.

### Finding B — D-COR-2 is a PO decision, not yet a fully closed gate

You approved refusal of `Annul` or `Void` against a non-live target, using `TargetNotLive`.

That is a sensible rule, but the gate register says the Principal Reviewer’s concurrence is still pending. Claude must not promote the decision to fully closed without the required evidence or a formal governance statement that concurrence is unnecessary.

The important semantic distinction is that a fact can be live while its effect is inactive. The implementation must evaluate the contract's defined fact-liveness predicate, not substitute a period's current activity or the correction's effectiveness.

### Finding C — Do not confuse conditional authorisation with permission to code

Your conditional authorisation is already recorded. The next task is not to ask you to authorise C-1 again.

The start conditions are:

- D-COR-2 concurrence or a formal determination that it is not required.
- D-COR-5 architecture confirmation by the Principal Reviewer and ARB.
- Formal correction-contract approval.

Until all are evidenced, C-1 remains blocked.

### Finding D — The state-synchronisation warning needs a precise response

The attached report says `.claude/CONTEXT-publicdigit.md` was updated, but a project-state checker still reported that `CONTEXT.md` was not updated.

The correct response is not automatically to edit another context file. Claude should verify the repository's actual state-management convention and determine whether the warning is a generic checker false positive or a genuine missing obligation. The next task must not overwrite unrelated session work.

### Finding E — More research is useful only when it closes a specific gap

The bounded oracle probes provide evidence about tested sequences; they do not prove the entire correction algebra correct. Earlier test counts are inherited from the 9 October audit and were not rerun in the latest gate task.

The next step should not repeat broad theory discovery. It should establish a clean, traceable decision gate and then move to test-first implementation once authorised.

## 3. Copy-ready Claude Code prompt for the next step

This prompt focuses on the actual remaining work. It asks Claude to verify the decision package, prepare the formal handoff and determine exactly what is needed to close the governance gate—without implementing anything.

# PublicDigit Membership — Principal Review Handoff and C-1 Start-Gate Verification

## 1. Role and mission

Continue exclusively within the existing PublicDigit Membership bounded context in `app/Contexts/Membership`.

Act as a Principal DDD Architect, requirements engineer, formal-methods researcher and test-first engineering reviewer.

The Membership correction contract and decision package have already been prepared. Do not restart discovery or recreate these documents without a demonstrated defect.

Your immediate mission is to verify the existing decision package, prepare the outstanding human decisions for closure, and establish an evidence-based C-1 start-gate checklist.

This is a documentation and governance task. It is not implementation authorisation.

## 2. Current human decisions — preserve exactly

The PO decisions dated 2026-10-10 are:

- **D-COR-2:** PO APPROVED refusal of Annul/Void against a non-live target using `TargetNotLive`.
- **Correction contract:** CONDITIONALLY APPROVED by the PO, subject to D-COR-5 architectural confirmation.
- **C-1:** CONDITIONALLY AUTHORISED by the PO, subject to the recorded start conditions.

Do not infer any additional decision.

The following remain outstanding:

1. Principal Reviewer concurrence on D-COR-2, or a formal determination that concurrence is not required.
2. Principal Reviewer and ARB confirmation of D-COR-5 (architecture).
3. Formal approval of the correction contract.

C-1 remains BLOCKED until these conditions are evidenced.

## 3. Step A — Verify the current decision artefacts

Inspect the actual repository files and current working tree:

- `docs/publicdigit/reviews/2026-10-09-membership-correction-slice-semantic-contract-draft.md`
- `docs/publicdigit/reviews/2026-10-10-membership-c1-gate-decision-record.md`
- `docs/publicdigit/reviews/2026-10-10-membership-d-cor-5-architecture-decision-request.md`
- `.claude/CONTEXT-publicdigit.md`
- `.claude/sessions/2026-10-10.md`
- the existing Membership human ARB decision record and the repository's applicable state-management instructions.

Verify:

- the PO's decisions appear accurately and exactly once as current decisions;
- historical wording is clearly distinguished from current status;
- the D-COR-5 decision request cites the correct ratified sources;
- the contract remains conditionally approved, not formally approved;
- the gate register and project context agree;
- no document implies C-1 has started;
- the working tree and untracked files are reported accurately.

Do not assume that the previous terminal transcript proves the current repository state.

## 4. Step B — Validate the D-COR-5 decision request

Review the existing request rather than drafting a replacement automatically.

Verify the following architecture question:

Does the ratified rule that Annul and Void create no consequences remain unchanged in the Membership kernel?

The recommended option remains confirmation of the existing rule:

- Annul/Void change the liveness of the target.
- They do not implicitly create period-trimming or other correction consequences.
- G\* rejects a resulting state that violates applicable invariants.
- Existing consequences may revive when their causes become live again, according to the ratified semantics.

Verify the supporting ratified sources and the conflicting historical wording in BD-REV, BD-TERM4 and final-proof scenario H.

Check that the request clearly separates:

A. The architecture decision owned by the Principal Reviewer and ARB.

B. The business/legal question about an appeal after re-entry, owned by the PO and Legal.

Do not let an architecture confirmation silently decide CQ-9, BD-TERM4, the competent appeal body, or ex tunc/ex nunc policy.

If the request is complete, retain it. If it has a defect, propose or make the smallest documentation-only repair and report the exact change.

## 5. Step C — Resolve the D-COR-2 concurrence question

Inspect the actual ratification and governance procedures to determine whether Principal Reviewer concurrence is mandatory for D-COR-2.

Do not infer the answer from general practice.

Return one of these outcomes:

- **Concurrence required:** identify the person or governance body that must provide it and prepare the exact request.
- **Concurrence not required:** identify the authoritative rule or recorded governance determination establishing this.
- **Insufficient evidence:** leave the gate open and state precisely what evidence is missing.

Do not manufacture a concurrence or a governance determination.

## 6. Step D — Prepare the formal decision handoff

Produce a concise handoff that the PO can give to the Principal Reviewer and ARB.

It must contain:

1. The exact D-COR-5 architecture question.
2. The recommended option and its rationale.
3. The ratified source references and relevant counterexample.
4. The consequences of confirmation.
5. The changes and formal re-scoping required if the rule is rejected.
6. The exact approval fields for the Principal Reviewer and ARB.
7. The remaining D-COR-2 concurrence requirement.
8. A statement that the correction contract remains conditionally approved until the formal approval gate closes.
9. A statement that C-1 remains blocked and no implementation is authorised to start yet.

Do not claim to have sent the request to people or received an external response unless that actually occurred.

## 7. Step E — Prepare the C-1 start-gate checklist

Define a checklist with evidence references for:

- D-COR-2 concurrence or formal exemption;
- D-COR-5 architecture confirmation;
- formal contract approval;
- verification that the approved C-1 scope and Definition of Done are unambiguous;
- explicit confirmation that the existing Membership kernel is the implementation target.

The PO has already provided conditional authorisation. Do not ask for duplicate authorisation. Instead, determine when the existing conditional authorisation becomes effective under the recorded conditions.

When all gates are closed, report that C-1 is eligible to start under the existing PO authorisation. Do not start it in this task.

## 8. Project-state synchronisation

Check the state-management warning concerning `CONTEXT.md`.

Verify the actual repository convention and whether `.claude/CONTEXT-publicdigit.md` is the authoritative Membership context file.

Do not update a generic context file merely to silence a checker if that would violate the repository's conventions or overwrite unrelated work.

If the warning is a false positive, report the evidence. If a real update is required, make only the minimal Membership-specific change and preserve unrelated edits.

## 9. Hard restrictions

- Membership only.
- Do not implement Annul, Void or Correct.
- Do not modify production code, tests, migrations, schema, configuration or persistence.
- Do not execute database mutations or enable live writes.
- Do not perform cutover.
- Do not commit, push or release.
- Do not mark the contract formally approved without the required approval.
- Do not claim Principal Reviewer or ARB approval without evidence.
- Do not change ratified architecture or governance decisions without explicit authority.
- Do not reopen completed discovery rounds without concrete contradictory evidence.

## 10. Required final report

Return:

1. Repository evidence verified.
2. Current four-gate decision register.
3. D-COR-5 handoff status and any actual defects.
4. D-COR-2 concurrence requirement and supporting evidence.
5. C-1 start-gate checklist.
6. Any state-synchronisation warning and its disposition.
7. Changed-file list and exact working-tree status.
8. Confirmation that no implementation or database work occurred.
9. One recommended next action.

Stop after the decision handoff and gate verification. Wait for actual human decisions before changing gate statuses.

## 4. How far are we from the Membership implementation goal?

The most important distinction is between having a sound Membership model, having a correction specification, implementing correction, and reaching production readiness.

## Membership implementation progress

Qualitative assessment based on the supplied project record; not a measured completion percentage.

Domain model and architectural boundaries

Established

Ratified Membership ownership, append-only facts and temporal semantics.

Admit / End kernel and persistence foundation

Existing

Reported as implemented; PostgreSQL persistence remains conditionally accepted.

Correction theory and contract

Draft reconciled

Annul / Void / Correct semantics are documented, with outstanding decisions.

C-1 Annul / Void implementation

Not started

Blocked by architectural concurrence and formal approval.

C-2 Correct and consequences

Not started

Blocked by BD-REV-ER and D-COR-3.

Application authority and persistence integration

Not complete

C-3/C-4 and their authority, schema and acceptance gates remain.

Cutover and live writes

Blocked

ARB-MEM-01 requires verified correction before cutover.

We have made meaningful progress on the specification and governance. But the central correction capability has not yet been implemented, so we should not describe the Membership implementation as nearly production-ready.

## 5. Remaining TODOs

### P0 — Close the immediate governance gate

- Obtain the Principal Reviewer's concurrence on D-COR-2, or a formal determination that concurrence is not required.
- Obtain the Principal Reviewer and ARB decision on D-COR-5 (architecture).
- Record the actual decision, names, dates, evidence and conditions.
- Obtain formal approval of the correction contract.
- Verify that all C-1 start conditions are satisfied before implementation begins.

### P1 — Implement C-1, once authorised

- Implement `Annul` and `Void` in the existing Membership kernel, test-first.
- Implement the approved `TargetNotLive` refusal rule.
- Add the PHP oracle port and differential tests specified by the approved contract.
- Test appeal/restoration sequences, liveness rules, invariant refusals and temporal properties.
- Run the authorised regression suite, measure mutation adequacy and document results.
- Complete developer documentation and implementation review.

### P1 — Complete the remaining correction semantics

- Decide BD-REV-ER: whether correction may absorb an entire live Membership period.
- Decide D-COR-1 and D-COR-3, including the permitted correction targets and attributes.
- Resolve CQ-3 with Legal and Governance: competent authority and required evidence.
- Resolve CQ-9 with Legal: appeal process, authority and relevant temporal semantics.
- Keep the business/legal interpretation of appeal after re-entry separate from D-COR-5 architecture.
- Implement C-2 only after its specification and prerequisites are approved.

### P1 — Complete persistence and application integration

- Resolve D-COR-4 and the target-reference naming decision.
- Resolve PBDIGIT-264 and obtain the required Slice 1b acceptance and DV-1 countersignature.
- Implement C-3 command handlers and authority evidence under the approved application contract.
- Implement C-4 additive persistence changes, round-trip tests and concurrency tests.
- Verify tenant scoping and consistency for every new persisted reference.

### P2 — Establish production readiness

- Complete all correction slices and their acceptance gates.
- Assess the existing legacy Membership readers and writers.
- Execute the approved migration, reverse-projection and single-writer strategy.
- Complete regression, data-consistency and cutover checks.
- Satisfy ARB-MEM-01 before enabling live writes or retiring legacy Membership behaviour.

### Research and formal-methods TODOs

- Preserve the distinction between ratified rules, derived invariants, bounded oracle evidence and unresolved policy.
- Re-run the necessary differential and metamorphic tests against the final approved specification.
- Treat model-checking counts as bounded evidence, not probabilities or proof of correctness.
- Introduce machine learning only if a separate, justified Membership data problem and valid dataset emerge; it is not needed to decide the current correction semantics.

My recommendation: focus exclusively on the outstanding Principal Reviewer/ARB decision and D-COR-2 concurrence question. Once the formal contract approval is complete, move directly into authorised C-1 test-first implementation. Avoid further broad discovery unless new evidence reveals a specific contradiction.&#x20;