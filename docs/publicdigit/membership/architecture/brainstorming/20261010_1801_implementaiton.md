# PublicDigit Membership — review of the SF-1 plan

## 1. My decision: revise the plan before implementation

Claude has produced a useful, narrowly scoped plan for the SF-1 traceability gap. The plan correctly keeps the historical approval wording, distinguishes implementation clearance from G3 acceptance, preserves the unrelated staged work, and proposes exact additive changes rather than redesigning the Membership architecture.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



My recommendation is: accept the plan's direction, but do not authorize implementation yet.

There are three points to resolve first:

1. Evidence provenance: the plan says the approval and clearance are supported by a review document that is currently untracked. That is useful evidence, but weaker than a durable, versioned record. The plan correctly identifies this limitation.&#x20;

   Eingefügter Text.txt

2. The §16 summary: S3 says that the wording review and implementation were cleared, but does not itself identify the source. Since §16 is a summary of the authoritative record, it should either reference row 6 clearly or include a concise provenance reference.

3. The scope of authorization: the earlier P1–P6 authorization does not automatically authorize SF-1. The new changes need explicit approval. Claude has correctly retained that gate.&#x20;

   Eingefügter Text.txt



The plan file has already been written, despite the earlier planning-only instruction to create no files. The report says it remains uncommitted and that the target documents are unchanged. Treat the plan as a proposed artifact, not as approval to implement.&#x20;

Eingefügter Text.txt



## 2. Prompt for Claude — refine the SF-1 plan, then stop

# PublicDigit Membership — Principal Review of SF-1 Plan Before Approval

## Roles and decision authority

- **Claude:** repository investigator and implementation engineer. Prepare precise evidence and a safe, minimal plan.
- **ChatGPT:** independent Principal DDD Architect, requirements engineer, semantic reviewer, and reviewer of the proposed changes.
- **Human PO/ARB:** owns governance decisions, approval of the final wording, implementation authorization, and formal G3 acceptance.

No role may silently assume another role's authority.

## 1. Objective

Refine the existing proposed plan:

`docs/plans/20261010-1745-membership-c1-sf1-register-traceability-plan.md`

The objective is to resolve the SF-1 traceability gap in the C-1 governance records without changing the approved historical decision or confusing implementation clearance with formal acceptance.

The current audited baseline is reported as:

- Branch: `knowelegeos-modelling`
- HEAD: `76b1192deb03a1ebfb101ac97c75b5f5fdbba21d`
- G3: PENDING
- PBDIGIT-268: not accepted or closed

Verify the baseline before relying on it. If it differs, stop and report the discrepancy.

## 2. Scope: plan review only

Do not implement SF-1.

For this task:

- Do not edit the two target documents.
- Do not edit, stage, or commit any file.
- Do not push.
- Do not rerun tests.
- Do not modify the three unrelated staged renames.
- Do not commit the untracked source document.
- Do not change code, tests, databases, configuration, runner behavior, C-AMEND-1, §14.5, CQ-3, the semantic contract, or the ratified act.

Inspect the current proposed plan and the minimum supporting evidence needed to refine the recommendation. Return the revised proposal in your response; do not write another file unless separately authorized.

## 3. Resolve the provenance question

The current plan identifies this untracked source document:

`docs/publicdigit/membership/architecture/brainstorming/20261010_1728_membershipC1 — revision 4 review.md`

The plan says this document records the Principal Reviewer's approval of revision 4 and reports the Human PO/ARB's implementation authorization.

Distinguish carefully between:

1. the source document's actual text;
2. the Principal Reviewer's statement about the Human PO/ARB authorization;
3. any explicit authorization supplied directly by the Human PO/ARB in the conversation;
4. the authoritative committed decision record.

Do not describe an indirect attestation as a direct quotation or independently authored statement by the Human PO/ARB.

Recommend one of these provenance treatments, explaining the trade-off:

- **Option A:** cite the source document by exact path and explicitly disclose that it is untracked;
- **Option B:** request separate Human PO/ARB authorization to commit the source document, then cite the versioned record;
- **Option C:** use narrower wording that records only what the committed evidence independently supports until the provenance issue is resolved.

Do not commit the source document or invent missing approval evidence.

## 4. Refine the three proposed changes

Evaluate the proposed changes S1, S2 and S3 in the existing plan.

### S1 — Gate record row 6, evidence cell

The current proposal appends wording about the completed wording review and the implementation clearance, including provenance.

Check that every assertion is supported and that the text accurately distinguishes review completion from implementation authorization.

### S2 — Gate record row 6, applied cell

The current proposal records implementation in commit `76b1192de` and explicitly retains G3 as PENDING.

Check that the execution evidence belongs in this cell and that the wording does not suggest C-1 was formally accepted.

### S3 — Semantic contract §16, C-STATUS-1 summary row

The current proposal says that the wording review and implementation were cleared, identifies the implementation commit, and retains G3 PENDING.

Assess whether §16 should:

- provide a concise provenance reference itself;
- refer explicitly to row 6 for provenance; or
- use narrower summary wording that cannot be mistaken for an independently evidenced decision.

Recommend exact wording and explain why it remains faithful to the authoritative register.

Preserve the historical qualifier:

`subject to the Principal Reviewer's wording review`

Do not change the historical decision status or create a new decision identifier without an explicit governance reason.

## 5. Mandatory semantic invariants

Demonstrate that the refined proposal keeps these five states distinct:

1. Plan approval with its historical conditions.
2. Completion of the Principal Reviewer's wording review.
3. Human PO/ARB authorization to implement.
4. Execution of the authorized documentation changes in `76b1192de`.
5. Formal G3 acceptance, which remains PENDING.

Nothing in the proposed wording may imply that state 2, 3, or 4 automatically establishes state 5.

## 6. Preservation and verification

The eventual implementation plan must preserve:

- all unrelated staged renames;
- other sessions' unstaged work;
- the historical wording and all unrelated register rows;
- the existing C-1 scope and approved semantic contract;
- all excluded code, tests, configuration, database and runner paths.

Specify exact post-implementation checks: constrained diffs, preservation of historical wording, table-column consistency, provenance validation, status consistency, and confirmation that excluded paths remain unchanged.

Do not execute these checks during this planning-only task.

## 7. Required response

Return:

1. Baseline and evidence actually inspected.
2. A provenance table distinguishing direct evidence, attestation and committed authoritative records.
3. Your revised recommendation for S1, S2 and S3, with exact wording.
4. The trade-offs and any unresolved Human PO/ARB decisions.
5. A concise implementation and verification plan.
6. A list of explicit exclusions.
7. A final recommendation: approve for Human PO/ARB decision, revise further, or reject.

Do not claim that the plan is approved merely because you have refined it.

## 8. Stop condition

STOP after presenting the refined proposal.

ChatGPT must independently review the proposal. The Human PO/ARB must explicitly approve the final wording and scope before any target-document implementation begins.

G3 remains PENDING, and PBDIGIT-268 remains unaccepted and unclosed until the applicable evidence review and formal decision are completed.

## 3. How far are we from the Membership implementation goal?

The most defensible assessment is that the project has established substantial domain and correction-slice work, but is not yet at complete Membership implementation or production-readiness acceptance.

| Area                                        | Current assessment                                 |
| ------------------------------------------- | -------------------------------------------------- |
| C-1 correction semantics and targeted tests | Substantial work reported; bounded evidence exists |
| P1–P6 documentation reconciliation          | Reported implemented and audited                   |
| SF-1 authoritative-record traceability      | Plan proposed; implementation not yet authorized   |
| G3 / PBDIGIT-268 acceptance                 | Pending                                            |
| PostgreSQL persistence                      | Not demonstrated by the supplied audit             |
| Wider Membership test suite                 | Reported errors remain unresolved                  |
| C-3/C-4 and later correction work           | Separate planning and authorization required       |
| Legacy migration and cutover                | Not ready for approval; governed by ARB-MEM-01     |

Eingefügter Text.txt

### Remaining TODOs

- Review Claude's refined SF-1 wording and evidence.
- Decide how the untracked approval-source document should be handled; do not commit it without separate authorization.
- Obtain explicit Human PO/ARB approval for the final SF-1 wording and scope.
- Implement the approved documentation-only changes and verify the exact diff.
- Prepare the formal G3 acceptance recommendation and obtain the Human PO/ARB's decision.
- Resolve the wider Membership suite errors and capture reliable test evidence.
- Test PostgreSQL persistence and temporal invariants under the appropriate C-4 authorization.
- Address the D-COR-2 oracle gap and other outstanding evidence limitations.
- Resolve CQ-3, BD-REV-ER and D-COR-3 through the established governance process.
- Plan and authorize C-2, C-3 and C-4 separately.
- Complete the single-writer, reverse-projection, migration, rollback and cutover strategy under ARB-MEM-01.
- Validate the integration boundaries among Membership, Governance, Election, Finance, Audit and service access before production rollout.

Immediate next action: send Claude the refinement prompt above. The goal is to make the authoritative record precise and auditable—not to rush the C-1 acceptance gate or expand the implementation scope.