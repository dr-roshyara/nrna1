# PublicDigit Membership — Review of Claude's Latest Result

My assessment: the evidence document is ready for a focused reviewer decision, and we should now move toward the next engineering increment rather than expand the administrative workflow.

Claude has produced the single review document authorised by ARB-MEM-03. It contains the C-1/G3 evidence matrix, separate disposition sections for Slices 0, 1a and 1b, and a list of outstanding risks. No acceptance has been recorded, and no code or tests were changed.&#x20;

Eingefügter Text.txt



## 1. My Principal Reviewer assessment

C-1 / G3 — Recommend acceptance with explicit residual risks

The targeted tests passed, and the report identifies the bounded differential testing, property tests, mutation-testing evidence and accepted H-2 deviation. The remaining limitations must be recorded rather than hidden.

Conditions and residual risks include the D-COR-2 independent-oracle gap, unenumerated equal `recordedAt` values, developer-reported Infection results, wider-suite errors, and persistence still awaiting C-4.

Slice 0 — Reviewer assessment supports acceptance

The report identifies D-1 as satisfied through the reviewer instruction and ARB-DEC-01, consistent with ARB-MEM-03. This closes the condition, but Human PO/ARB must still record the formal verdict.

Slice 1a — Confirm S1-1 and S1-3

I confirm the stated technical interpretation for review purposes: Slice 1a provides the database-independent contract boundary, and the idempotency rules distinguish a conflicting fingerprint from outcomes that were actually appended. The confirmation should be recorded against the cited contract and review sections. Human PO/ARB still decides acceptance.

Slice 1b — Keep deferred

Do not accept it yet. The isolated PostgreSQL test suite, far-date defect, and applicable persistence conditions require engineering evidence before reconsideration.

These are my reviewer recommendations, not Human PO/ARB decisions. In particular, the C-1 recommendation does not mean that the wider Membership suite is green or that PostgreSQL persistence is proven.&#x20;

Eingefügter Text.txt



## 2. The next step: finish the review, then plan the engineering fix

I recommend this short sequence:

1. Have Claude record my reviewer recommendations and confirmations in the existing document, without changing anything else.
2. Human PO/ARB records the formal G3, Slice 0 and Slice 1a decisions separately. Slice 1b remains deferred.
3. Review the existing PostgreSQL/far-date implementation plan before any code changes.
4. After approval, implement the far-date fix test-first: failing test, minimal fix, passing test, regression evidence.
5. Verify persistence on an isolated PostgreSQL database under its own authorised test plan.

The attached report identifies a far-date defect and references a plan at `docs/plans/20261010-1830-membership-pg-verification-and-far-date-plan.md`. That plan still needs independent review and approval. We should review the actual plan before authorising implementation.&#x20;

Eingefügter Text.txt



## 3. Prompt for Claude — record review decisions and prepare the next engineering gate

# PublicDigit Membership — Record Principal Review and Prepare the Next Engineering Gate

## Roles

- **Claude:** Repository investigator and implementation engineer. Record the authorised reviewer feedback, inspect the existing engineering plan, and prepare implementation only after approval.
- **ChatGPT:** Independent Principal DDD Architect and Reviewer. The following recommendations are supplied for recording in the existing review document.
- **Human PO/ARB:** Owns formal acceptance, residual-risk acceptance, scope authorisation, and implementation approval.

## Task A — Update the existing review document

Work exclusively with:

`docs/publicdigit/reviews/2026-10-10-membership-g3-and-slice-dispositions.md`

Use the existing ARB-MEM-03 authorisation. Do not create another dossier.

Record the following reviewer assessments accurately:

### C-1 / G3

Principal Reviewer recommendation:

**ACCEPT WITH EXPLICIT RESIDUAL RISKS.**

Record these residual risks:

- D-COR-2 is not covered by the independent oracle.
- Equal `recordedAt` values have not been enumerated.
- Infection results are developer-reported, not independently rerun.
- Wider Membership suite errors remain unclassified and have no confirmed owner.
- Persistence remains unverified and is a downstream C-4 dependency.
- The mutation-testing runner weaknesses remain open under PBDIGIT-269.
- Differential/property evidence is bounded and must not be described as a proof of correctness.

Do not claim the wider suite passes. Do not treat out-of-scope persistence as resolved. Leave the Human PO/ARB G3 verdict blank.

### Slice 0

Preserve the finding that D-1 is satisfied under ARB-MEM-03, through the reviewer instruction and ARB-DEC-01.

Keep the proposed disposition as PROPOSED ACCEPT. Leave the formal Human PO/ARB verdict blank.

### Slice 1a

Record the Principal Reviewer's confirmation of:

- S1-1: Slice 1a is the database-independent contract boundary, with persistence supplied by the subsequent adapter layer.
- S1-3: IDEM-2 rejects the same idempotency key with a different fingerprint as `IdempotencyKeyConflict`; IDEM-4 remembers only appended outcomes.

Cite the existing Slice 1a contract and review sections. Do not claim these rules were confirmed by passing tests alone. Leave the Human PO/ARB verdict blank.

### Slice 1b

Keep status DEFERRED. Do not record acceptance.

## Task B — Review the existing PostgreSQL/far-date plan

Inspect the existing file:

`docs/plans/20261010-1830-membership-pg-verification-and-far-date-plan.md`

Do not implement it yet.

Return a concise review covering:

1. exact scope and changed files;
2. the failing regression test that must precede the far-date fix;
3. the minimal implementation change;
4. isolated PostgreSQL setup and evidence of database isolation;
5. exact test commands and expected exit-code capture;
6. regression and acceptance criteria;
7. rollback or containment considerations;
8. unresolved dependencies and risks.

Identify any material defect in the plan. Do not invent missing evidence or claim to have verified a source you could not access.

## Constraints

- Do not change production code or tests.
- Do not run tests or scripts.
- Do not modify contracts, architecture decisions, or unrelated files.
- Do not commit, push, or change branches.
- Do not accept G3 or any slice.
- Do not begin C-2, C-3, C-4, PBDIGIT-264, PBDIGIT-266, PBDIGIT-267, PBDIGIT-269, or Slices 2–15 implementation.
- Preserve other sessions' changes.

After the authorised review-document update, report the exact diff and stop. Return the PostgreSQL/far-date plan review separately for ChatGPT's independent review and Human PO/ARB approval.

The next engineering workflow remains:

**Plan → ChatGPT review → Human PO/ARB approval → test-first implementation → regression verification.**

## 4. How far are we from the Membership goal?

The earlier 20–25% implementation estimate remains a rough, unweighted estimate—not a measured completion percentage.

The current state is best described as follows:

- Domain architecture: Core concepts and key invariants are substantially established.
- C-1: Implementation and targeted tests exist; formal G3 acceptance is pending.
- Slice 0: The outstanding D-1 condition is considered satisfied; formal acceptance is pending.
- Slice 1a: Implementation evidence exists; the reviewer confirmations are now supplied, but Human PO/ARB acceptance remains pending.
- Slice 1b: Deferred until persistence and defect conditions are resolved.
- Remaining delivery: Commands and queries, correction and persistence, cross-context integration, migration, API/UI, full regression and legacy retirement.

### Remaining TODOs

- Record the reviewer recommendation and S1-1/S1-3 confirmations in the existing document.
- Obtain Human PO/ARB's formal G3, Slice 0 and Slice 1a decisions.
- Review and approve the existing PostgreSQL/far-date plan.
- Implement the far-date fix test-first.
- Run the PostgreSQL suite on an isolated database after explicit approval.
- Triage the wider Membership test errors and assign owners.
- Complete the remaining Membership commands, queries, correction semantics and persistence.
- Integrate Governance, Election, Finance and Audit while preserving the separation between Membership and voting entitlement.
- Complete migration, pilot cutover, API/UI, regression, rollback verification and legacy retirement.

My recommendation: complete the focused review now, then return to engineering. Do not create more administrative documents or begin code changes until the existing engineering plan has passed review and Human PO/ARB has approved it.