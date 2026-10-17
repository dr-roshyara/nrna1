# PublicDigit — Principal DDD architecture review of the latest session

My assessment is that the project has reached an important transition: the adjudication discovery work is substantially established, the tenant-isolation defects have been characterized at runtime, and the remediation decision package has been prepared. The next bottleneck is now governance authorization, not a lack of architectural ideas.

The latest attached log also records a significant change since our previous review: Claude committed six local changesets after the instruction to commit the completed work. Those commits were not pushed, and some work from the concurrent session remains outside those commits.&#x20;

Eingefügter Text.txt



## 1. What the latest session actually accomplished

Evidence integrity improved

Stale statements saying that the relay tests had not been run were corrected or marked as superseded. The current evidence record distinguishes tests created, tests executed, results observed, and defects fixed.&#x20;

Eingefügter Text.txt



Tenant isolation remains a release blocker

T-1 is runtime-confirmed: the relay can reach a consumer without tenant context. T-4 is also runtime-confirmed: an event for organisation A was processed under organisation B and marked completed when B's tenant context was already active. Neither defect has been fixed.&#x20;

Eingefügter Text.txt



Six local commits were created

The log lists separate commits for PBDIGIT-256, PBDIGIT-211, PBDIGIT-181, adjudication discovery, the multi-database investigation, and a session log. Nothing was pushed. PBDIGIT-256's test results have not been revalidated against the current code, and the PBDIGIT-211 test commit deliberately records failing characterization tests.&#x20;

Eingefügter Text.txt



### Principal review: three conclusions

1. The architecture should not be reopened. The accepted separation between Contestation, Adjudication, and Governance, and the process manager's role in recording rather than inventing authority decisions, remains the governing model.
2. A failing characterization test is valuable evidence, not a completed fix. The current relay test outcome remains 5 tests: 3 failures, 1 pass, 1 incomplete. The incomplete case still depends on Q4.
3. The next mission must not quietly become an implementation mission. ARB decisions, PO approvals, commit boundaries, and the actual Git working-tree state must be clear before the production remediation starts.

## 2. The correct next step

I recommend Mission 4 — Post-Commit Integrity and Decision Handoff.

Its purpose is to establish the exact state after the six commits, ensure that concurrent-session changes have not been accidentally included or disturbed, and prepare the remaining human decisions in a concise form.

It should not repeat Mission 3's document review, rerun the characterization tests, create more architecture documents, or implement the tenant fix. The decision package already exists. The remaining dependency is explicit human authorization.

## 3. Claude Code prompt for the next mission

# Mission 4 — Post-Commit Integrity and Decision Handoff

## Role

Act as principal DDD architect, senior requirements engineer, and software assurance reviewer for PublicDigit, the digitalization platform for organisations and political parties, particularly its online election and adjudication capabilities.

Your mission is to establish the exact post-commit repository state and prepare the outstanding governance and Product Owner decisions. Do not start implementation.

## 1. Verify the six commits

Re-establish the current repository state. Do not assume the HEAD, branch, staging area, or working tree is unchanged since the last session.

Inspect:

- Current branch and HEAD.
- `git status --short`, including staged, unstaged, and untracked files.
- Recent commit history.
- The exact file lists and diffs of the six reported commits:
  - `4b8f54742` — PBDIGIT-256.
  - `44e7c18b5` — PBDIGIT-211 characterization test and evidence package.
  - `1584006eb` — PBDIGIT-181 annotation.
  - `bb9e0ff5a` — adjudication discovery work.
  - `f6fc3c5ab` — multi-database investigation.
  - `af3614547` — session log.

Verify each commit against the actual repository. These IDs are references from the previous session, not assumptions about the current HEAD.

Confirm whether the commits are still local and unpushed, where that can be established without changing remote state.

## 2. Protect concurrent work

The previous session reported staged membership-document renames and uncommitted changes from another session.

- Do not reset, stash, amend, rebase, cherry-pick, force-push, or discard any work.
- Do not alter the staging area to separate another session's changes.
- Do not create additional commits or push anything.
- If unrelated changes are staged or uncommitted, identify them and leave them untouched.
- If commit attribution is uncertain, report the ambiguity instead of modifying history.

Determine whether the six commits contain only their intended work. Report any discrepancy with exact file paths and commit IDs.

## 3. Verify the adjudication decision gate

Inspect the existing decision package:

`docs/publicdigit/reviews/2026-10-09-adjudication-remediation-gate.md`

Cross-check the ARB request, PBDIGIT-211, the PBDIGIT-181 annotation, and `.claude/CONTEXT-publicdigit.md`.

Report only material inconsistencies or changed facts. Do not rewrite records that are already correct.

Preserve these established facts:

- T-1 and T-4 are runtime-confirmed.
- The isolated relay run produced 5 tests: 3 failures, 1 pass, 1 incomplete.
- No production fix has been demonstrated.
- T-5 concerns missing durable relay failure records and requires separate schema authorization.
- T-2's durable filing-data requirement is recorded against PBDIGIT-181.
- T-3 remains a draft backlog item.
- T-5 remains a draft backlog item unless the actual repository proves otherwise.
- The ARB's Q1–Q7 answers remain pending unless an authoritative ruling has been recorded.

Do not rerun the relay tests in this mission.

## 4. Prepare the human decision handoff

Use the existing gate report. Do not create another document unless the existing record cannot hold a necessary decision.

Provide a concise table with four columns:

1. Decision or approval required.
2. Responsible authority: ARB or PO.
3. Current evidence and proposal.
4. Exact action required to unblock the next mission.

Cover:

- ARB Q1–Q7, including tenant mismatch, nested scopes, soft-deleted organisations, parking deadlines, replay, missing tenant context, and ownership of the tenancy capability.
- PO authorization for PBDIGIT-211 remediation.
- Whether to create the proposed T-3 backlog item and assign its identifier.
- Whether to create the proposed T-5 backlog item, its priority and owner, and the separate authorization required for schema work.
- PBDIGIT-256's independent review and validation status.
- Any outstanding commit or documentation approvals not already granted.

Do not ask for approval to commit work that has already been committed under an explicit earlier instruction. Instead, report the actual commit status and any remaining uncommitted work.

## 5. Preserve the architecture and implementation sequence

- Do not restart accepted Adjudication DDD discovery.
- Do not implement the tenant-context fix.
- Do not modify production code, migrations, ADRs, ticket status, or accepted governance rules.
- Do not create story IDs without PO authorization.
- Do not treat a proposed ARB option as an accepted decision.
- Preserve the binding order **PBDIGIT-178 → PBDIGIT-177 → PBDIGIT-174 → PBDIGIT-182**.
- Do not add a production caller to the issuance path before PBDIGIT-178 is complete.

## 6. Required final report

Finish with:

1. Verified repository state and commit integrity.
2. Exact outstanding staged, unstaged, and untracked work.
3. Any material evidence inconsistencies.
4. The ARB/PO decision handoff table.
5. The next action that is actually authorized.
6. A prioritized TODO list and implementation-readiness assessment.

## Stop condition

This is a read-only verification and handoff mission.

If the repository state is consistent and the decision package is ready, stop and report the result. If a discrepancy exists, report it without repairing it unless the repair is purely an authorized, unambiguous factual correction already within this mission.

Do not implement, commit, or push anything.

## 4. How far are we from the implementation goal?

The platform's progress needs to be assessed by architectural capability, not by the number of documents or commits.

| Capability                                   | Current assessment                                                    |
| -------------------------------------------- | --------------------------------------------------------------------- |
| Adjudication domain discovery                | Substantially established and frozen                                  |
| Tenant isolation in the relay                | Blocked — runtime defects confirmed                                   |
| Relay failure observability                  | Incomplete — T-5 needs ownership and an approved persistence contract |
| Challenge filing persistence                 | Requirement documented; implementation not demonstrated               |
| Permanent Election rejection handling        | Unresolved; T-3 remains a draft                                       |
| Authority-decision integration               | Blocked by the existing implementation gate                           |
| Determination finality and horizon/lapse     | Incomplete                                                            |
| End-to-end correction-loop verification      | Not achieved                                                          |
| Production readiness of this correction loop | Not demonstrated                                                      |

The evidence does not support a reliable overall percentage for the entire PublicDigit platform. The narrower adjudication work is beyond initial discovery but is not yet at production-ready integration.

## 5. Remaining TODOs, prioritized

### P0 — Establish trustworthy repository and governance state

- Verify the six local commits and the current Git staging area.
- Preserve any concurrent membership and multi-database work.
- Obtain the ARB ruling on Q1–Q7.
- Decide T-3 and T-5 backlog ownership; assign IDs only with PO authorization.
- Approve the PBDIGIT-211 remediation after the ARB ruling.

### P1 — Fix and verify tenant isolation

- Implement the approved tenant-scoped execution model.
- Guarantee that the message's organisation, not a foreign ambient tenant, governs processing.
- Restore tenant context reliably after every invocation.
- Define and implement soft-deleted-organisation, mismatch, retry, terminal-state, and replay semantics.
- Add durable relay failure recording through a separately authorized schema change.
- Fix permanent Election rejection classification.
- Run isolated regression tests and broader verification after implementation authorization.

### P1 — Complete the adjudication correction loop

- Complete PBDIGIT-178.
- Complete PBDIGIT-177 and the authorized Governance authority contract.
- Complete PBDIGIT-174.
- Complete PBDIGIT-182 with end-to-end verification.
- Implement durable challenge filing facts under PBDIGIT-181.
- Complete determination finality and the horizon/lapse mechanism.
- Resolve admissibility and terminal-outcome rules through the proper governance authority.

### P2 — Complete broader platform work

- Review PBDIGIT-256 independently and revalidate its tests against current code.
- Resolve the remaining bounded-context and authority-contract questions through architecture governance.
- Complete full regression testing and address remaining known failures.
- Keep project-state and developer-guide records aligned with actual implementation changes.

Final recommendation: execute Mission 4 as a read-only integrity check. After that, the next meaningful milestone is an explicit ARB ruling and PO authorization—not another discovery round. The implementation sequence remains frozen until its prerequisite gates are satisfied.&#x20;