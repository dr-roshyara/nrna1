# Principal review of the latest Membership audit

The new attachment reports a significant step forward: Claude has consolidated the Membership theory, executed fresh tests, measured mutation-testing effectiveness, and created a traceability matrix and implementation roadmap. The resulting audit is reported at:

`docs/publicdigit/reviews/2026-10-09-membership-theory-synthesis-and-implementation-readiness-audit.md`

The transcript reports commit `58fdef025`, with research documentation changed but no production code, tests, schema, or configuration changed by this audit. Those statements are evidence from the supplied transcript, not an independently verified current repository state.&#x20;

Eingefügter Text.txt



My recommendation is to preserve the ratified Membership core and move to evidence-driven closure of the implementation gaps. We should not redesign the whole domain or add more abstractions simply because the theory has grown.

## 1. What the new evidence establishes

Core Membership theory: coherent

The adopted core defines one Member per organisation/person pair, Membership periods with re-entry under the same Member, calendar-date valid time, recorded/decision instants, append-only facts, and derived state. Admit and End are reported as implemented and verified.&#x20;

Eingefügter Text.txt



Test evidence: strong for the current scope

The reported test runs passed 93 domain/application unit tests, 12 Membership architecture tests, and 27 PostgreSQL feature tests. Mutation testing reports 349 mutants, 292 killed, 23 surviving, and 34 unreached: an 83% mutation score overall and 92% for covered mutants. These are useful quality signals, not proof of correctness across unimplemented lifecycle operations.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



Persistence: known defects remain gated

The audit identifies a far-date round-trip defect and a command-to-fact integrity defect. It also records unresolved isolation/idempotency work. These should be addressed through the existing authorised persistence plan—not through an improvised schema redesign. `PBDIGIT-264` remains unauthorised in the supplied report.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



Integration: the central gap

Authority, corrections/annulments, Full Membership election eligibility, and migration cutover remain incomplete. The report correctly distinguishes the coherent core from these unfinished edges.&#x20;

Eingefügter Text.txt



## 2. My critical assessment as principal reviewer

The synthesis is a good foundation, but five points need particular discipline.

1\. Do not confuse model verification with business ratification. A counterexample demonstrates the consequences of a rule or implementation choice. It does not determine which legal or organisational policy must be adopted.

2\. Do not treat the mutation score as a completeness score. The 83% overall score and 92% covered-code score describe this mutation run and its configuration. The four identified test gaps should be assessed by semantic risk, not simply by trying to maximise the percentage.

3\. Do not implement Governance before the authority contract is settled. `G-AUTH-1` is a proposed contract, not an approved requirement. Its subject, decision reference, as-of semantics, and evidence structure must be agreed in the Slice 2 design before implementation.

4\. Do not introduce ML into the authority path. The report is correct to defer ML. Record-linkage methods may eventually help flag possible duplicate legacy records, but a human must resolve identity decisions. Membership legitimacy and voting eligibility must remain governed by explicit rules.

5\. Do not let research indefinitely delay concrete engineering. Once the repository state is verified and the necessary authorisations are obtained, each implementation slice should have a narrow scope, measurable acceptance criteria, regression evidence, and a clear stop condition.

The next step should therefore be a decision-and-evidence closure review, not another broad discovery exercise.

## 3. Copy-ready Claude prompt for the next step

This commission uses the completed synthesis as its baseline. It asks Claude to verify the highest-priority next action and prepare a decision-ready test plan, while respecting the existing research-only boundary.

# Claude Code Commission: Membership Audit Challenge and Next-Gate Readiness

## Role

Act as Principal Reviewer, senior DDD architect, requirements engineer, formal-methods researcher, mathematician, statistician, and security-aware implementation architect.

Treat the current PublicDigit Membership corpus as evidence to challenge, not as unquestionable truth. Preserve ratified decisions. Optimise for correctness, minimal change, and a traceable route from business theory to verified implementation.

## Objective

Independently review the newly completed:

`docs/publicdigit/reviews/2026-10-09-membership-theory-synthesis-and-implementation-readiness-audit.md`

Determine whether its conclusions and dependency order are defensible, identify any material omissions, and prepare the smallest next action for explicit Product Owner authorisation.

The immediate candidate is `PBDIGIT-266`, the test-first investigation of suspected route-authorisation gaps. Prepare the investigation so it can be authorised and executed safely. Do not treat this commission as authorisation to execute it.

## 1. Verify current state before relying on the audit

1. Inspect the actual repository, current branch and HEAD, working tree, staged and untracked files, and relevant commits.
2. Verify commit `58fdef025` and whether the audit and its referenced documents remain current.
3. Preserve all unrelated and concurrent changes. Do not reset, stash, clean, revert, or overwrite them.
4. Confirm the actual current status of `PBDIGIT-264`, `PBDIGIT-265`, `PBDIGIT-266`, Slice 1b acceptance, DV-1 countersignature, and Slice 2 decisions D-1 through D-6.
5. Distinguish evidence in repository decision records from statements in transcripts and session notes.
6. If repository state cannot be reconciled safely, report the discrepancy and stop before editing.

Do not infer that a historical recommendation is current authorisation.

## 2. Challenge the synthesis

Review the audit as a principal reviewer. Focus on material errors rather than stylistic changes.

Verify:

- The adopted Membership core and its invariants.
- The distinction between Member identity, Membership periods, temporal facts, authority, committee tenure, election registration, entitlement, and exercisability.
- The separation of Membership, Governance, Election, Finance, Audit, and derived access.
- The claimed implementation and verification status of each critical invariant.
- The distinction between exhaustive finite enumeration, independent-oracle comparison, property-based testing, mutation testing, and proof.
- Whether each conclusion is appropriately labelled ADOPTED, DEMO, MODEL, CX, HYP, BIZ, NV, or BLOCKED.
- Whether any research result has been accidentally presented as an adopted business rule.
- Whether the dependency-ordered roadmap is consistent with the ratified architecture and previous principal-review decisions.

Do not rewrite adopted semantics. If you find a contradiction, identify its source, decision owner, impact, and required resolution.

## 3. Prepare the PBDIGIT-266 investigation plan

Read the authoritative backlog item and inspect the actual routes, controllers, middleware, policies, request authorisation, service-layer checks, and relevant tests.

The existing investigation identifies four suspected areas:

1. Election approval/rejection/pending routes.
2. Committee-membership application review.
3. Governance API operations that add or remove committee members.
4. Legacy finance routes for income/outcome operations.

These are static-inspection findings, not confirmed vulnerabilities.

For each route family, prepare a test matrix that distinguishes:

- Unauthenticated caller.
- Authenticated but unverified caller, where applicable.
- Verified, non-privileged caller in the same organisation.
- Verified, non-privileged caller in a different organisation.
- Authorised role in the correct organisation.
- Authorised role from the wrong organisation.
- Relevant tenant or organisation context missing or inconsistent.

Only include cases appropriate to the actual route contract. Do not invent expected access policy where it is not documented; label the policy question and identify its owner.

For each test, specify:

- Exact route and HTTP method.
- Relevant middleware, controller and policy.
- Required fixtures and organisation boundaries.
- Expected status and state-change assertion.
- Whether the expectation is grounded in an adopted rule or requires a business/security decision.
- The smallest feature-test location and test name.
- The evidence needed to classify the result as confirmed, refuted, or inconclusive.

A missing guard in a controller is not enough to prove a vulnerability: inspect the complete reachable authorisation path. Conversely, a passing status-code assertion is insufficient if the state changed unexpectedly.

Do not send HTTP requests, run the proposed tests, modify tests, change application code, change routes, or fix suspected defects in this commission. Prepare the test-first plan only.

## 4. Keep Membership implementation gates separate

Reconcile the remaining work without implementing it:

- `PBDIGIT-264`: persistence defects and approved F-1 through F-5/N1, F-3, F-4b and F-2 sequence.
- Slice 1b: conditions for formal acceptance and DV-1 countersignature.
- Kernel test gaps TG-1 through TG-4.
- Vocabulary and authority decisions CQ-1 through CQ-10.
- Slice 2/D-4 and proposed G-AUTH-1 contract.
- `PBDIGIT-265`: Full Membership eligibility-predicate mismatch.
- Election-Only ledger-independence criterion T-EO.
- Migration cutover, parity, rollback, and production database-engine verification.
- Correction and annulment lifecycle implementation.
- Governance and Finance contracts.
- Deferred service-access and ML work.

For each, state the authoritative owner, prerequisites, current evidence, required approval, and objective completion evidence. Do not duplicate existing backlog items.

## 5. Formal methods, statistics, and ML

Do not repeat model runs merely to produce more numbers.

Run further analysis only if it can expose a material contradiction or change the next implementation decision. Where useful, specify a minimal counterexample or independent oracle before executing a model.

Do not make statistical claims without an appropriate empirical dataset and sampling frame. Do not open personal-data CSVs merely to explore whether data might be useful.

Do not propose ML for Membership authority, admission/exclusion, identity equivalence, voting eligibility, or access decisions. The deferred duplicate-triage proposal may remain a future, human-reviewed migration aid, subject to privacy and evaluation prerequisites.

## 6. Required deliverables

Produce a concise review document or update the existing audit only where material correction is needed.

It must include:

1. Review verdict: ACCEPT, ACCEPT WITH CONDITIONS, REVISION REQUIRED, or CONFLICT.
2. Repository state and current authoritative status of the gates.
3. Any material audit findings with file/symbol evidence.
4. The route-by-route PBDIGIT-266 test plan.
5. The policy questions that cannot be settled from code alone.
6. The smallest separately authorisable next action.
7. An updated, dependency-ordered TODO list.
8. Exact files changed and confirmation that no tests, runtime code, schema, or configuration were changed.

Avoid creating another long synthesis that merely repeats the existing one.

## 7. Stop conditions

Stop immediately if:

- The working tree or concurrent changes cannot be safely distinguished.
- The next action requires business authority that is not recorded.
- The proposed test requires destructive database setup or risks a shared database.
- A result depends on an unresolved business policy.
- The review would require production changes or access not explicitly authorised.

At the end, present the test plan and the exact authorisation required. Wait for explicit approval before executing tests or implementing fixes.

## 4. How far are we from the Membership implementation goal?

The audit supports the following status assessment. These are qualitative maturity judgements, not percentage estimates.

| Layer                  | Current assessment                           | Main remaining gap                                                 |
| ---------------------- | -------------------------------------------- | ------------------------------------------------------------------ |
| Business theory        | Advanced; core ratified                      | Resolve authority and vocabulary conflicts                         |
| Formal specification   | Strong foundation                            | Verify correction/annulment semantics against the actual kernel    |
| Membership kernel      | Admit/End implemented and verified           | Complete lifecycle operations and close identified test gaps       |
| PostgreSQL persistence | Implemented, conditional acceptance reported | Authorised remediation and migration verification                  |
| Governance integration | Not complete                                 | Approve authority contract and competent decision-makers           |
| Election integration   | Incomplete                                   | Resolve `PBDIGIT-265` and prove Election-Only independence         |
| Finance integration    | Research/design stage                        | Approve ledger and reconciliation contracts                        |
| Migration and cutover  | Not ready                                    | One authoritative truth, parity, rollback, and production evidence |
| Production readiness   | Not established                              | Complete integration, security verification, and release gates     |

The strongest result is that we no longer need to invent Membership from scratch. We have an adopted core, executable Admit/End behaviour, formal invariants, persistence tests, and an increasingly precise map of the missing contracts. The remaining difficulty lies at the boundaries and in safely completing the lifecycle—not in replacing the core theory.&#x20;

Eingefügter Text.txt



## 5. Remaining TODOs

In priority order:

- [P0 — Authorisation] `PBDIGIT-266`: Obtain explicit approval for the test-first investigation of the four suspected route-authorisation gaps. Confirm or refute each through tests before opening any fix work.
- [P0 — Decision evidence] DV-1: Obtain and record the required countersignature for the Slice 1b decision in the authoritative repository record.
- [P1 — Persistence] `PBDIGIT-264`: Obtain explicit implementation authorisation; execute the approved remediation sequence; verify far-date handling, command/fact integrity, transaction isolation, idempotency, and migration round trips.
- [P1 — Test quality] TG-1 through TG-4: Authorise and close the identified kernel test gaps for log validation, evidence-field validation, whitespace-only identifiers, and refused-decision misuse.
- [P1 — Vocabulary] `CQ-1`, `K-2`, `K-3`, `CQ-2`: Resolve the Election Committee naming collision, the undefined Organisation Chief, the legacy committee model's relationship to Membership, and the appointing authority.
- [P1 — Business rules] `CQ-3`, D-1, D-3, and related open decisions: Ratify who can admit, end, correct, annul, and attest Membership records, and define the evidence required for each act.
- [P1 — Architecture] Slice 2 / D-4: Approve the authority contract, including the proposed `G-AUTH-1` changes, before implementing Governance integration.
- [P1 — Election] `PBDIGIT-265`: Resolve the inconsistent Full Membership eligibility predicates and establish the required shared test matrix.
- [P1 — Election-Only] T-EO: Demonstrate that Election-Only mode remains independent of the Membership ledger, not merely the legacy `members` table.
- [P2 — Lifecycle] Corrections and annulments: Implement the ratified lifecycle algebra in separate, approved slices and extend the real-kernel bridge so currently unchecked histories can be reclassified.
- [P2 — Migration] Cutover C-0 through C-10: Resolve competing membership truths, complete reader/writer inventory and column ownership, run parity and dry-run checks, verify rollback, and establish the production database engine.
- [P2 — Governance] Committee participation contract: Resolve the remaining mandate, tenure, delegation, quorum, recusal, and Membership-seat eligibility decisions before implementing the contract.
- [P2 — Finance] Finance ledger contract: Decide approval and separation-of-duties rules, column ownership, reconciliation idempotency, and treatment of credits/refunds.
- [P3 — Integration] HTTP/UI: Implement admission and ending workflows only after the corresponding domain, authority, persistence, and cutover gates are satisfied.
- [P3 — Research quality] Formal verification: Continue counterexample-driven work where it changes decisions; distinguish bounded evidence from proof and keep all unchecked correction histories visible.
- [Deferred] Machine learning: Revisit duplicate-record triage only when the migration dry run, privacy controls, labelled evaluation sample, and human-review process are ready.

The detailed dependencies and completion evidence are already captured in the supplied audit's implementation-readiness roadmap. Avoid creating duplicate tickets for the same work.&#x20;

Eingefügter Text.txt



## Final recommendation

Next step: run the audit challenge and prepare the `PBDIGIT-266` authorisation-ready test plan. Do not execute the tests or implement fixes until explicitly authorised.

That gives us a disciplined sequence: establish the real repository state, investigate the highest-priority live-risk question, close the authorised persistence defects, settle the business authority contract, and then proceed through Membership lifecycle and cutover gates.

Our goal is not merely a working Membership table or a passing test suite. It is a system in which every Membership decision has an explicit business meaning, an authorised source, reconstructable evidence, correct temporal behaviour, and verifiable implementation.