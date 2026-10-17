# PublicDigit — Principal Architecture Review and next-step plan

## 1. Executive assessment

The attached file describes a read-only repository integrity check and handoff, with the next decision gate centred on tenant isolation in the election challenge-and-adjudication workflow. It does not contain the complete implementation or the underlying test code, so this review distinguishes what the transcript demonstrates from what still requires repository evidence. Source: attached transcript, sections 1–6.

My principal-reviewer conclusion is:

The immediate next step is to resolve the seven Architecture Review Board (ARB) questions, not to start implementation. Claude should prepare an evidence-backed, decision-ready package, identify the consequences of each option, and preserve the current implementation boundary until the ARB decisions are recorded and the Product Owner (PO) authorises the fix.

There are two related but distinct goals:

- Platform goal: build a digitalisation platform for organisations and political parties, including a robust online-voting process.
- Immediate engineering goal: resolve tenant-context and isolation defects in the asynchronous challenge-and-adjudication workflow so that one organisation's processing cannot affect another organisation's data.

Tenant isolation is foundational to the platform, but resolving it alone does not prove that the complete election process is production-ready.

## 2. What the attached evidence tells us

Established

- The transcript reports that the six listed commits contain their intended files.
- The repository review reports no production fix, no test execution and no push during that read-only mission.
- The tenant-context runtime defects have been documented, and the ARB questions Q1–Q7 have been identified.
- PBDIGIT-211 has a committed test/story package according to the transcript, but remains open.

Blocked

- Tenant isolation has runtime-confirmed defects; the transcript reports a cross-tenant write being marked completed.
- Q1–Q7 have no recorded ARB ruling.
- T-3 and T-5 are drafts without backlog IDs.
- The PBDIGIT-211 implementation is awaiting ARB rulings and PO authorisation.
- End-to-end processing, finality, lapse triggers and production readiness have not been demonstrated.

Not established by this file

- The actual source-code cause of each runtime failure.
- Whether the proposed fixes fully preserve isolation across HTTP requests, queued messages, scheduled jobs, retries and nested scopes.
- Whether the full challenge-to-adjudication-to-election-consequence flow satisfies its business invariants.
- Whether the reported historical test results still hold on the current code and an isolated database.

These distinctions matter: a committed test is not a passing test, a documented proposal is not an approved decision, and an approved architecture is not an implemented control.

## 3. DDD and requirements-engineering review

### 3.1 The key architectural issue: tenant context must be an explicit contract

The transcript describes multiple entry points into tenant-specific processing: inbox messages, scheduled jobs and possibly web requests. It also reports a platform fallback for missing tenant context and a mismatch that was marked completed.

From a DDD perspective, the system needs a clearly owned tenant execution contract. The messaging layer transports work; it should not become the owner of tenant identity or isolation policy.

The contract should make the following explicit:

- Tenant identity: which organisation owns the operation, and where that identity originates.
- Execution scope: how the organisation context is established for one operation.
- Isolation invariant: a write may only affect the organisation authorised by the operation's trusted context.
- Failure semantics: what happens on missing context, mismatch, deleted organisation, invalid message or failed processing.
- Scope cleanup: tenant context must be restored or cleared, including on exceptions and nested execution.
- Replay semantics: whether a failed message can be retried or replayed, under which conditions, and with what duplicate-processing protection.
- Evidence: what is recorded to demonstrate that the correct organisation was used and that a rejected operation did not write to another tenant.

The ARB should decide the business and architecture contract. The implementation should then encode it in one well-defined capability, with adapters for the different entry points.

### 3.2 The seven questions are not seven unrelated bugs

They form a connected decision system:

| Questions  | Architectural concern                     | Required outcome                                                                    |
| ---------- | ----------------------------------------- | ----------------------------------------------------------------------------------- |
| Q1, Q2, Q5 | Establishing and resolving tenant context | One explicit, bounded context contract, including allowed platform-level operations |
| Q3         | Preventing cross-tenant effects           | Fail-closed mismatch rules and defined nested-scope behaviour                       |
| Q4         | Organisation lifecycle and delayed work   | Defined handling of soft deletion, parking deadlines, retention and replay          |
| Q6         | Domain and technical ownership            | Named owner for the tenant-context capability                                       |
| Q7         | Consistency across execution paths        | A canonical contract with appropriate adapters, or a justified alternative          |

The choices must be evaluated together. For example, allowing a platform fallback in Q5 can undermine Q3 if a missing tenant is silently replaced by a default organisation. Similarly, replay in Q4 is unsafe until Q1–Q3 establish how tenant identity is bound and checked.

### 3.3 Challenge-and-adjudication needs an explicit domain boundary

The platform must distinguish the process of raising a challenge, the authorised determination, and the election consequence. The authority to adjudicate should be defined by the Governance domain rather than inferred from the name of a committee or implemented as a messaging concern.

A robust process should preserve this chain:

\#chatgpt-mermaid-\_r_92h\_{font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";font-size:16px;fill:rgb(13, 13, 13);}@keyframes edge-animation-frame{from{stroke-dashoffset:0;}}@keyframes dash{to{stroke-dashoffset:0;}}#chatgpt-mermaid-\_r_92h\_ .edge-animation-slow{stroke-dasharray:9,5!important;stroke-dashoffset:900;animation:dash 50s linear infinite;stroke-linecap:round;}#chatgpt-mermaid-\_r_92h\_ .edge-animation-fast{stroke-dasharray:9,5!important;stroke-dashoffset:900;animation:dash 20s linear infinite;stroke-linecap:round;}#chatgpt-mermaid-\_r_92h\_ .error-icon{fill:rgb(243, 243, 243);}#chatgpt-mermaid-\_r_92h\_ .error-text{fill:rgb(13, 13, 13);stroke:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_92h\_ .edge-thickness-normal{stroke-width:1px;}#chatgpt-mermaid-\_r_92h\_ .edge-thickness-thick{stroke-width:3.5px;}#chatgpt-mermaid-\_r_92h\_ .edge-pattern-solid{stroke-dasharray:0;}#chatgpt-mermaid-\_r_92h\_ .edge-thickness-invisible{stroke-width:0;fill:none;}#chatgpt-mermaid-\_r_92h\_ .edge-pattern-dashed{stroke-dasharray:3;}#chatgpt-mermaid-\_r_92h\_ .edge-pattern-dotted{stroke-dasharray:2;}#chatgpt-mermaid-\_r_92h\_ .marker{fill:rgb(143, 143, 143);stroke:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_92h\_ .marker.cross{stroke:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_92h\_ svg{font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";font-size:16px;}#chatgpt-mermaid-\_r_92h\_ p{margin:0;}#chatgpt-mermaid-\_r_92h\_ .label{font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";color:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_92h\_ .cluster-label text{fill:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_92h\_ .cluster-label span{color:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_92h\_ .cluster-label span p{background-color:transparent;}#chatgpt-mermaid-\_r_92h\_ .label text,#chatgpt-mermaid-\_r_92h\_ span{fill:rgb(13, 13, 13);color:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_92h\_ .node rect,#chatgpt-mermaid-\_r_92h\_ .node circle,#chatgpt-mermaid-\_r_92h\_ .node ellipse,#chatgpt-mermaid-\_r_92h\_ .node polygon,#chatgpt-mermaid-\_r_92h\_ .node path{fill:rgb(222, 234, 251);stroke:rgb(83, 154, 248);stroke-width:1px;}#chatgpt-mermaid-\_r_92h\_ .rough-node .label text,#chatgpt-mermaid-\_r_92h\_ .node .label text,#chatgpt-mermaid-\_r_92h\_ .image-shape .label,#chatgpt-mermaid-\_r_92h\_ .icon-shape .label{text-anchor:middle;}#chatgpt-mermaid-\_r_92h\_ .node .katex path{fill:#000;stroke:#000;stroke-width:1px;}#chatgpt-mermaid-\_r_92h\_ .rough-node .label,#chatgpt-mermaid-\_r_92h\_ .node .label,#chatgpt-mermaid-\_r_92h\_ .image-shape .label,#chatgpt-mermaid-\_r_92h\_ .icon-shape .label{text-align:center;}#chatgpt-mermaid-\_r_92h\_ .node.clickable{cursor:pointer;}#chatgpt-mermaid-\_r_92h\_ .root .anchor path{fill:rgb(143, 143, 143)!important;stroke-width:0;stroke:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_92h\_ .arrowheadPath{fill:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_92h\_ .edgePath .path{stroke:rgb(143, 143, 143);stroke-width:1px;}#chatgpt-mermaid-\_r_92h\_ .flowchart-link{stroke:rgb(143, 143, 143);fill:none;}#chatgpt-mermaid-\_r_92h\_ .edgeLabel{background-color:rgb(252, 252, 252);text-align:center;}#chatgpt-mermaid-\_r_92h\_ .edgeLabel p{background-color:rgb(252, 252, 252);}#chatgpt-mermaid-\_r_92h\_ .edgeLabel rect{opacity:0.5;background-color:rgb(252, 252, 252);fill:rgb(252, 252, 252);}#chatgpt-mermaid-\_r_92h\_ .labelBkg{background-color:rgba(252, 252, 252, 0.5);}#chatgpt-mermaid-\_r_92h\_ .cluster rect{fill:rgb(243, 243, 243);stroke:rgba(0, 0, 0, 0.1);stroke-width:1px;}#chatgpt-mermaid-\_r_92h\_ .cluster text{fill:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_92h\_ .cluster span{color:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_92h\_ div.mermaidTooltip{position:absolute;text-align:center;max-width:200px;padding:2px;font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";font-size:12px;background:rgb(243, 243, 243);border:1px solid rgba(0, 0, 0, 0.1);border-radius:2px;pointer-events:none;z-index:100;}#chatgpt-mermaid-\_r_92h\_ .flowchartTitleText{text-anchor:middle;font-size:18px;fill:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_92h\_ rect.text{fill:none;stroke-width:0;}#chatgpt-mermaid-\_r_92h\_ .icon-shape,#chatgpt-mermaid-\_r_92h\_ .image-shape{background-color:rgb(252, 252, 252);text-align:center;}#chatgpt-mermaid-\_r_92h\_ .icon-shape p,#chatgpt-mermaid-\_r_92h\_ .image-shape p{background-color:rgb(252, 252, 252);padding:2px;}#chatgpt-mermaid-\_r_92h\_ .icon-shape .label rect,#chatgpt-mermaid-\_r_92h\_ .image-shape .label rect{opacity:0.5;background-color:rgb(252, 252, 252);fill:rgb(252, 252, 252);}#chatgpt-mermaid-\_r_92h\_ .label-icon{display:inline-block;height:1em;overflow:visible;vertical-align:-0.125em;}#chatgpt-mermaid-\_r_92h\_ .node .label-icon path{fill:currentColor;stroke:revert;stroke-width:revert;}#chatgpt-mermaid-\_r_92h\_ .node .neo-node{stroke:rgb(83, 154, 248);}#chatgpt-mermaid-\_r_92h\_ [data-look="neo"].node rect,#chatgpt-mermaid-\_r_92h\_ [data-look="neo"].cluster rect,#chatgpt-mermaid-\_r_92h\_ [data-look="neo"].node polygon{stroke:url(#chatgpt-mermaid-\_r_92h\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_92h\_ [data-look="neo"].swimlane.cluster rect{filter:none;}#chatgpt-mermaid-\_r_92h\_ [data-look="neo"].node path{stroke:url(#chatgpt-mermaid-\_r_92h\_-gradient);stroke-width:1px;}#chatgpt-mermaid-\_r_92h\_ [data-look="neo"].node .outer-path{filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_92h\_ [data-look="neo"].node .neo-line path{stroke:rgb(83, 154, 248);filter:none;}#chatgpt-mermaid-\_r_92h\_ [data-look="neo"].node circle{stroke:url(#chatgpt-mermaid-\_r_92h\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_92h\_ [data-look="neo"].node circle .state-start{fill:#000000;}#chatgpt-mermaid-\_r_92h\_ [data-look="neo"].icon-shape .icon{fill:url(#chatgpt-mermaid-\_r_92h\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_92h\_ [data-look="neo"].icon-shape .icon-neo path{stroke:url(#chatgpt-mermaid-\_r_92h\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_92h\_ .node text{font-size:14px;font-weight:600;letter-spacing:normal;fill:rgb(0, 79, 153);}#chatgpt-mermaid-\_r_92h\_ .edgeLabels text{font-size:13px;font-weight:600;letter-spacing:-0.08px;fill:rgb(0, 79, 153);}#chatgpt-mermaid-\_r_92h\_ .node tspan[font-weight="normal"],#chatgpt-mermaid-\_r_92h\_ .edgeLabels tspan[font-weight="normal"]{font-weight:600;}#chatgpt-mermaid-\_r_92h\_ .edgeLabel .label rect{opacity:1;rx:13px;ry:13px;fill:rgb(245, 250, 255);stroke:rgb(206, 219, 229);stroke-width:1px;}#chatgpt-mermaid-\_r_92h\_ .node rect,#chatgpt-mermaid-\_r_92h\_ .node circle,#chatgpt-mermaid-\_r_92h\_ .node ellipse,#chatgpt-mermaid-\_r_92h\_ .node polygon,#chatgpt-mermaid-\_r_92h\_ .node path{fill:rgb(229, 243, 255);stroke:rgba(0, 0, 0, 0.1);stroke-width:1px;}#chatgpt-mermaid-\_r_92h\_ .node rect{rx:16px;ry:16px;}#chatgpt-mermaid-\_r_92h\_ .node.mermaid-decision .label-container{fill:rgb(245, 250, 255);stroke:rgb(206, 219, 229);stroke-dasharray:2,2;}#chatgpt-mermaid-\_r_92h\_ .edgePaths .flowchart-link{stroke:rgb(143, 143, 143);stroke-width:1px;stroke-linecap:round;stroke-linejoin:round;}#chatgpt-mermaid-\_r_92h\_ .marker{fill:rgb(143, 143, 143);stroke:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_92h\_ :root{--mermaid-font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";}Challenge raisedEvidence recordedAuthorised adjudicationDeterminationApply permitted electionconsequenceRecord rejection and reasonAudit evidenceVerify authority and scopeAcceptedRejected

This is a target-level process sketch, not evidence that these steps are already implemented. The actual state transitions, authority checks, finality rules and rejection behaviour must be traced to the existing domain model and requirements before implementation.

## 4. What should Claude do next?

I recommend a decision-readiness mission with two stages:

1. Produce the ARB decision package for Q1–Q7, supported by source-code references, runtime-test evidence, options, consequences and explicit unresolved requirements.
2. Stop for the ARB rulings. After the rulings are recorded, prepare a bounded PBDIGIT-211 implementation plan for PO approval.

Do not combine the two stages. The first stage should not modify production code, change schemas, execute database tests or commit files. That prevents the research phase from accidentally making decisions that belong to the ARB or PO.

The following prompt is designed to be pasted into Claude Code.

## 5. Claude Code instructions — next mission

# Mission 5 — ARB Decision Readiness for Tenant Isolation and Election Adjudication

## Role

Act as a principal software architect, senior Domain-Driven Design (DDD) architect, requirements engineer, researcher, formal-methods analyst, mathematician/statistician, and security-minded reviewer.

PublicDigit is a digitalisation platform for organisations and political parties, with online voting as a core domain. The immediate engineering scope is tenant isolation in the asynchronous election challenge-and-adjudication workflow.

Treat the existing repository, requirements, architecture decisions, tests, issue records, and approved governance decisions as the authoritative corpus. Investigate incrementally and preserve existing decisions unless authorised evidence supports a change.

## Mission objective

Prepare an evidence-backed Architecture Review Board (ARB) decision package for Q1–Q7, then identify the exact conditions under which PBDIGIT-211 may be implemented safely.

This is a **read-only decision-readiness mission**. Do not implement the fix during this mission.

## 1. Re-establish the relevant state without repeating completed discovery

1. Read the active project instructions, current context, PBDIGIT-211 story and test artefacts, gate report, relevant tenancy ADRs, and the existing Q1–Q7 handoff.
2. Inspect the actual source and tests relevant to tenant context, inbox processing, scheduled jobs, nested scopes, retries, parked messages, and organisation lifecycle.
3. Verify the current working-tree state before examining changes. Do not overwrite, reset, stage, discard, or reformat shared or other-session work.
4. Reuse existing evidence and prior investigations. Do not repeat the already-reported commit-integrity exercise unless new contradictory evidence appears.
5. Distinguish:
   - observed source-code behaviour;
   - test-confirmed behaviour;
   - documented requirements;
   - accepted architecture decisions;
   - proposals;
   - unknowns.
6. Cite exact file paths, symbols, tests and relevant line ranges wherever possible. If a claim cannot be verified, label it unverified.

Do not claim that tests pass unless you actually ran them safely and have the results. For this mission, do not run tests.

## 2. Trace the real execution paths

Build a source-backed execution map covering:

- HTTP/request entry points;
- inbox/message consumers;
- scheduled or batch jobs;
- tenant-context establishment and restoration;
- organisation lookup and lifecycle checks;
- nested execution scopes;
- transaction boundaries and database selection;
- message acknowledgement, retry, parking and terminal failure;
- audit and operational evidence.

For each path, identify the source of organisation identity, the point where it is trusted, the point where it is enforced, and the outcome if it is absent or inconsistent.

Investigate the reported cross-tenant write marked completed. Establish the strongest conclusion supported by the source and existing test evidence. Do not assume the exact root cause from the summary alone.

## 3. Prepare a decision record for each ARB question

For each question Q1–Q7, provide:

- Question and architectural invariant at stake.
- Current behaviour, supported by code/test evidence.
- Proposed option and at least one credible alternative.
- Consequences for isolation, retries, operability, backwards compatibility and domain ownership.
- Failure scenarios and rejection behaviour.
- Required tests and observable evidence.
- Dependencies on other questions.
- Recommendation with rationale.
- Decision owner: ARB, PO, or another named authority where justified.
- Status: OPEN, PROPOSED, APPROVED, or REJECTED.

Do not present a recommendation as an approved decision. Do not silently close a question.

### Q1 — Tenant identity at the message boundary

Evaluate the proposed explicit “run as organisation” execution scope at the inbox boundary. Determine how message organisation identity is validated against the trusted organisation record and how it is propagated without relying on ambient state.

### Q2 — Process-wide tenant setting

Evaluate whether process-wide tenant state is permissible outside web requests. Consider sequential jobs, concurrent workers, exceptions, nested calls and restoration guarantees. Recommend the narrowest safe contract supported by the runtime model.

### Q3 — Mismatch and nested scopes

Evaluate fail-closed handling of tenant mismatch. Define proposed classifications for permanent rejection, retryable failure and held/manual-review states. Explain whether a nested scope may switch to a different organisation and what happens before any write can occur.

### Q4 — Deleted organisations, deadlines and replay

Trace organisation deletion, parked-message behaviour, the reported 60-minute deadline, retention and replay capability. Identify whether current behaviour can permanently lose work. Do not invent retention or legal requirements; mark them as decisions needed from the appropriate owner.

### Q5 — Missing tenant context

Identify every intended platform-level operation that legitimately requires no organisation context. Evaluate whether missing context should fail loudly globally or only outside request handling. Avoid defaulting silently to an arbitrary organisation.

### Q6 — Ownership of the tenancy capability

Recommend the owning architectural/domain capability and its location. Preserve the distinction between messaging transport and tenant isolation policy. Check the recommendation against ADR-MP-04 and existing bounded-context decisions.

### Q7 — One contract or two models

Compare one canonical tenant execution contract with multiple adapters against separate contracts for scheduled jobs and message consumers. Evaluate semantic drift, testability, ownership and migration cost. Recommend one approach only if the evidence supports it.

## 4. Apply DDD and formal-methods review

Identify the invariants the implementation must preserve. At minimum, evaluate:

- No operation writes to an organisation other than the organisation authorised for that operation.
- Missing or invalid tenant identity cannot silently resolve to a default tenant.
- A failed operation cannot leave tenant context contaminating subsequent work.
- A message cannot be acknowledged as successfully processed when the required operation failed.
- Retries and replay cannot silently produce unauthorised cross-tenant effects.
- Rejected operations leave no forbidden side effects.
- Tenant isolation remains enforced regardless of entry point.

Treat these as candidate invariants until checked against the existing contract and ARB decisions.

Where useful, propose state-transition tables, truth tables, temporal properties, property-based tests or bounded state exploration. Clearly state the model bounds and what they do not prove. Do not confuse a finite-model result with a probability or a production guarantee.

Machine learning is not a substitute for deterministic tenant-isolation controls. Do not introduce ML unless an evidenced, separate use case justifies it.

## 5. Review the election challenge-and-adjudication boundary

Trace the actual flow from challenge submission through evidence capture, authority validation, determination, permitted election consequence, audit evidence, finality and lapse handling.

Keep these concepts separate:

- challenge;
- evidence;
- authorised determination;
- election consequence;
- audit record;
- finality;
- retry or replay;
- tenant execution context.

Establish which context owns each decision. Do not infer authority from a committee name. Do not allow the messaging or tenancy layer to decide whether a challenge is substantively valid or whether an election consequence is legitimate.

Identify any gaps that block the end-to-end election process, but do not broaden this mission into a general membership redesign.

## 6. Test and database safety

This mission is read-only.

- Do not run PHPUnit, migrations, seeders, database scripts or other tests.
- Do not connect to or modify any database.
- Do not modify production code, schema, configuration or test files.
- Do not execute PBDIGIT-211, T-3 or T-5.
- Do not implement a proposed schema change.
- Do not stage, commit or push files.

Separately document the preconditions for the later authorised test mission: a dedicated test database, an explicit database-target assertion, and evidence that the target is not shared with another session or environment. The existing `nrna_test` concern must remain a hard gate until resolved.

## 7. Produce the deliverables

Create a decision-ready report in the established project documentation location, or recommend the location before writing if ownership or naming is unclear. First verify existing naming conventions and avoid duplicate reports.

The report must contain:

1. Executive summary and readiness verdict.
2. Source-backed execution map.
3. Evidence table for the runtime-confirmed failures.
4. Q1–Q7 decision matrix with options, recommendation, owner, dependencies and status.
5. Invariants and failure-mode matrix.
6. Election challenge-to-adjudication flow and identified gaps.
7. Required test scenarios and acceptance criteria, without executing them.
8. PBDIGIT-211 dependency and authorisation gate.
9. T-3 and T-5 status, including the owner who must create and prioritise each backlog item.
10. Ordered TODO list with P0/P1/P2 priorities, dependencies and completion evidence.
11. Explicit limitations, unknowns and decisions that remain open.

Do not edit shared context files or session logs as a side effect. If a state-record update is necessary, propose the exact change separately for approval.

## 8. Final report to the user

Report:

- what was inspected and what was not;
- exact source and test evidence found;
- each ARB question still open and the decision required;
- whether PBDIGIT-211 is ready for PO authorisation;
- blockers to end-to-end and production readiness;
- ordered TODOs and dependencies;
- files changed (expected: documentation only, if writing was authorised);
- tests executed (expected: none);
- confirmation that no database, production code, staging, commit or push was performed.

Stop after the decision package. Await ARB rulings and explicit PO authorisation before implementation.

## 6. How far are we from the implementation goal?

The evidence supports a partial readiness assessment, not a defensible single completion percentage. The transcript reports established discovery and a defined set of blockers, but it does not establish that the complete election challenge-and-adjudication process is operational.

## Readiness by workstream

Discovery and issue documentation

Established

The handoff, runtime findings and outstanding decisions are documented. This does not prove that every architectural question is resolved.

Tenant isolation

Blocked

Runtime-confirmed defects are reported. The ARB decisions and subsequent implementation and verification are still needed.

Challenge and adjudication loop

Incomplete

End-to-end verification, finality and lapse handling have not been demonstrated.

Production readiness

Not demonstrated

The tenant-isolation gate, isolated-database test gate and end-to-end evidence remain outstanding.

My assessment is that the immediate work is in the architecture-decision and implementation-readiness stage, not the production-release stage. We should not convert documentation progress into a percentage of the overall platform implementation.

The larger PublicDigit platform also includes membership, governance, finance, audit, access projections and voting eligibility. This attachment is insufficient to assess the completion of those domains, and the next mission should not expand into them without a concrete dependency.

## 7. Remaining TODOs — ordered by priority

### P0 — Resolve decisions and unblock safe implementation

- ARB: decide Q1–Q7, recording the selected option, boundaries, rationale, decision owner and consequences.
- Architecture: establish one explicit tenant execution contract, including identity provenance, scope lifetime, mismatch handling and cleanup.
- PO: authorise PBDIGIT-211 only after the required ARB rulings are recorded.
- PO: create backlog items and assign owners and priorities for T-3 and T-5. Keep T-5's schema change behind its separate approval.
- Database safety: provision and verify a dedicated test database before running any test that could alter shared data.
- Shared repository state: coordinate changes to shared context/session files; do not overwrite or commit another session's work without authorisation.

### P1 — Implement and verify tenant isolation

- Implement PBDIGIT-211 within the approved scope.
- Add or complete tests for tenant establishment, missing context, mismatched organisations, nested scopes, exceptions, restoration and sequential processing.
- Verify that cross-tenant writes fail closed and cannot be acknowledged as successful processing.
- Implement T-3 after its dependency on PBDIGIT-211 is satisfied.
- Resolve T-5 only under its own schema-change approval.
- Update the relevant database developer guide when database implementation changes require it.
- Run the authorised regression suite against the isolated database and preserve the results as evidence.

### P1 — Complete the challenge-and-adjudication workflow

- Verify authority intake and the relationship between challenge, evidence, determination and election consequence.
- Complete filing-data capture at the required point in the process, including the PBDIGIT-181 dependency.
- Define and implement finality rules and horizon/lapse triggers.
- Verify retry, parking, terminal rejection and replay behaviour.
- Confirm that audit evidence captures the required outcome without transferring domain decision ownership to the messaging layer.
- Test the complete flow from challenge submission to final election consequence, including rejected and failed paths.

### P2 — Broader verification and architectural consistency

- Independently review PBDIGIT-256 and rerun it against the current code on an isolated database.
- Continue the dependency sequence `PBDIGIT-178 → PBDIGIT-177` (with the Governance contract) `→ PBDIGIT-174 → PBDIGIT-182`, respecting the dependencies in the handoff.
- Resolve the bounded-context set identified as SK-215.
- Run the full regression suite after the dependent changes are integrated.
- Update the project context and active plan when the authorised next implementation step changes.
- Record production-readiness evidence for isolation, failure recovery, auditability and end-to-end election processing.

## 8. Principal-reviewer recommendation

Proceed with the ARB decision-readiness mission in the prompt above. The immediate success criterion is not “more code”; it is a decision package in which every Q1–Q7 question is supported by evidence, every recommendation is distinguishable from an approved rule, and the PBDIGIT-211 implementation boundary is explicit.

After the ARB rulings, implementation should follow a controlled loop:

1. Approve the requirements and invariants.
2. Implement the smallest authorised change.
3. Run targeted tests against a verified isolated database.
4. Verify failure and rejection paths, not only the successful path.
5. Run dependent regression tests.
6. Record evidence and update the architecture and implementation status.
7. Reassess production readiness against explicit acceptance criteria.

Formal logic and bounded model checking can help identify state-transition and tenant-isolation violations. Statistical or machine-learning techniques should only be introduced for a justified data-driven problem; they are not substitutes for deterministic access, authority or tenant-isolation controls.

The next gate is therefore Q1–Q7 → approved PBDIGIT-211 scope → safe implementation and verification. That sequence advances the election platform without confusing proposed architecture, authorised work and demonstrated production readiness.