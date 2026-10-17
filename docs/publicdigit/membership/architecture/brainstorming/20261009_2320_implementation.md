# Principal review: Membership architecture and next step

I have reviewed the attached transcript. My recommendation is to continue the Membership research, but first close a repository-integrity issue introduced by the latest documentation commit. We should not let a documentation-maintenance problem silently damage another research session's work, and we should not start implementation while the relevant domain decisions and correction semantics remain open.

The transcript ends while Claude is investigating whether commit `ae3faec5d` caused concurrent entries in `docs/publicdigit/backlog/README.md` to disappear. The transcript does not show the outcome of that investigation. We must therefore treat possible loss of concurrent work as unresolved, not as a confirmed loss or a confirmed safe state.

## 1. Principal reviewer findings

Priority 1 — Protect repository integrity

The latest transcript shows Claude noticed that the backlog index no longer displayed another session's uncommitted rows after committing `ae3faec5d`. This is the immediate next action. Compare the pre-commit working-tree state, the parent commit, the new commit, and any recoverable session state. Preserve concurrent changes and do not use a blanket reset, checkout, or restore.

Priority 2 — Establish one trustworthy project baseline

The transcript reports commits `95da140e5` and `ae3faec5d`, with the latter adding a backlog index row and a context entry. Before doing more work, establish the actual `HEAD`, working-tree status, parent-child commit relationship, and precise diff. The transcript alone does not prove the repository is now consistent.

Priority 3 — Preserve the authority boundaries

The decisions already recorded are important constraints, not permission to implement everything. Correction/annulment is a mandatory prerequisite for Membership live writes and cutover, but its implementation remains unauthorised. PBDIGIT-266 is conditionally authorised for tests only after its database-isolation gate passes. PBDIGIT-267 and PBDIGIT-264 remain separately unauthorised according to the transcript.

Priority 4 — Resume the domain work from evidence

Once the repository baseline is safe, resume the Membership theory-to-implementation workflow: consolidate the corpus, distinguish ratified rules from proposals, close the domain decisions that block the Slice 2 contract, and derive executable invariants and tests before production changes.

## 2. DDD and requirements-engineering assessment

The existing approach is sound in its core direction: it separates domain facts, authority, correction semantics, persistence, migration, and live delivery. The refinement I would insist on is a stronger traceability chain from research evidence to implementation.

For every material Membership rule, the corpus should make it possible to follow:

`Source evidence → Domain statement → Decision status → Invariant → Counterexample → Test → Implementation → Migration/cutover gate`

That chain should distinguish four epistemic statuses:

- Observed: directly evidenced by source code, documents, tests, or database inspection.
- Derived: follows from stated definitions or rules, with its reasoning recorded.
- Proposed: a candidate model or policy awaiting the authorised decision-maker.
- Ratified: explicitly approved by the responsible authority, with a traceable decision record.

This prevents brainstorming notes, finite-model results, and architectural recommendations from accidentally becoming binding business policy.

### Important architectural conclusions to preserve

- Membership is not a proxy for voting entitlement, appointment, authority, or access. These must remain distinct concepts with explicit relationships.
- Correction and annulment are part of the domain's truth-maintenance model. They cannot be replaced by a convenient same-day re-admission workaround if that loses historical meaning.
- Valid time and knowledge time must remain distinguishable. The system may need to answer both “what do we now believe was valid on date X?” and “what did we know when decision Y was made?”
- A passing test suite is not proof of domain completeness. Tests establish only the properties they actually exercise. Mutation testing, counterexamples, temporal cases, and model-based testing help expose gaps.
- Finite-model counts are not probabilities. They identify counterexamples within the enumerated state space; they do not establish real-world prevalence or business significance.
- Machine learning is secondary to authoritative semantics. It may help triage possible duplicate member records if suitable labelled data becomes available, but it must not decide identity equivalence, authority, membership, or voting entitlement.

## 3. Copy-ready Claude instructions for the next step

Use this as the next Claude Code commission. It deliberately starts with repository integrity and then continues the Membership research without crossing the current authorisation boundaries.

# Claude Code Commission — Membership Research Continuation and Repository Integrity

## Role

Act as a principal Domain-Driven Design (DDD) architect, senior requirements engineer, formal-methods researcher, mathematician, statistician, and computer-logic specialist. Use computational modelling, property-based testing, mutation testing, model checking, and machine-learning methods where they provide demonstrable value.

The objective is to derive a robust, internally consistent Membership theory from the existing PublicDigit corpus and turn it into a practical, test-first implementation plan.

The Human ARB remains the final architecture decision authority for decisions assigned to it. Do not invent approval, business policy, or implementation authority.

## Governing constraints

1. Treat existing ratified decisions as binding until explicitly superseded by an authorised decision.
2. Distinguish observed evidence, derived conclusions, hypotheses, recommendations, and ratified decisions.
3. Preserve document-local decision identifiers. Do not rename identifiers or silently resolve conflicting references.
4. Do not implement correction/annulment, perform migrations, alter application configuration, or enable live Membership writes under this commission.
5. Do not execute PBDIGIT-266 tests unless the dedicated database safety gate is demonstrably satisfied and the scope remains within the existing authorisation.
6. Do not execute PBDIGIT-267, PBDIGIT-264, or any other separately unauthorised work item.
7. Do not push commits.
8. The repository may be shared with concurrent Claude sessions. Never overwrite, reset, discard, stage, or commit another session's work without establishing ownership and preserving its changes.
9. If a safety or authority precondition fails, stop that workstream, record the evidence, and report the exact blocker.

# Phase 0 — Resolve the repository-integrity question first

The previous transcript reports commits `95da140e5` and `ae3faec5d`. After the latter commit, Claude noticed that the backlog README no longer displayed rows reportedly added by another concurrent session. The transcript ends before the outcome is known.

Investigate this before making further documentation or domain changes.

### Required investigation

1. Record the actual current `HEAD`, recent commit graph, working-tree status, staged changes, and untracked files.
2. Inspect `95da140e5`, `ae3faec5d`, their parents, and the exact changes to:
   - `docs/publicdigit/backlog/README.md`
   - `.claude/CONTEXT-publicdigit.md`
   - `.claude/sessions/2026-10-09.md`
3. Determine whether the other session's backlog entries were:
   - committed before these commits;
   - present only in the working tree;
   - overwritten or omitted from a HEAD-based copy;
   - recoverable from a commit, reflog, patch, session log, or other concrete evidence.
4. Compare the current files against the best recoverable pre-commit state. Do not infer that data was lost merely because a row is missing from the current view.
5. If concurrent work is missing, prepare a minimal, reviewable recovery that preserves both the committed work and the other session's intended changes.
6. Do not use blanket `git reset`, `git checkout`, `git restore`, destructive clean commands, or broad staging to resolve the discrepancy.
7. Do not amend or rewrite published/shared history. Do not create a repair commit until the exact changes and their ownership are clear.
8. Do not commit or push unless separately authorised. Report any unavoidable uncertainty.

### Phase 0 deliverable

Produce a concise integrity report containing:

- actual repository state;
- whether concurrent work was lost, preserved, or remains unverified;
- evidence and exact affected paths;
- safest minimal recovery plan;
- whether it is safe to resume the Membership research.

If the baseline is unsafe, stop after the report. Do not use research work as a reason to modify the shared tree.

# Phase 1 — Re-establish the Membership research baseline

Once repository integrity is established, inspect the actual repository files. Do not rely solely on this transcript or on earlier summaries.

Read the authoritative Membership contracts, Slice 0/1a/1b/2 material, theory synthesis and implementation-readiness audit, errata, decision records, backlog entries, relevant tests, and active research documents. Search for conflicting or superseded statements.

Create an evidence matrix with these fields:

| Field            | Requirement                                                            |
| ---------------- | ---------------------------------------------------------------------- |
| Evidence ID      | Stable, traceable identifier                                           |
| Source           | File and exact section, line, or test                                  |
| Statement        | What the source actually says                                          |
| Epistemic status | Observed, derived, proposed, or ratified                               |
| Domain concept   | Membership fact, period, authority, correction, etc.                   |
| Invariant        | Formal statement, where applicable                                     |
| Counterexample   | State or history that would violate it                                 |
| Test evidence    | Existing, missing, or not executable                                   |
| Decision owner   | Human ARB, PO, Governance, Finance, Security, or other confirmed owner |
| Dependency       | Decisions and work items required before implementation                |

Do not silently reconcile conflicts. Record them and identify the minimum decision needed to resolve each one.

# Phase 2 — Derive and challenge the Membership theory

Treat the corpus as research material, not as a substitute for a coherent theory.

Explicitly define:

- Person, Organisation, Member, MembershipPeriod, MembershipFact, Decision, AuthorityEvidence, correction, annulment, and the current Membership projection.
- Identity and uniqueness rules.
- Membership admission and ending semantics.
- Re-entry and same-day transitions.
- Valid time, recorded time, decision time, and `knownAt` reconstruction.
- Append-only evidence versus derived current truth.
- Correction, annulment, void, refusal, and the limits of each operation.
- Authorised decision-making and evidence requirements.
- Cross-context ownership and permitted read/write directions.
- Historical reconstruction, idempotency, ordering, concurrency, and migration parity.

Preserve the distinctions between Membership, OrganisationAffiliation, CommitteeAppointment, Tenure, Mandate, Authority, Duty, ElectionRegistration, VotingEntitlement, and Exercisability.

Use formal logic and mathematics where useful. State preconditions, postconditions, invariants, and temporal properties precisely. Give a counterexample for each important rejected alternative.

Do not claim a rule is proved merely because a finite set of examples passes.

# Phase 3 — Computational and statistical research

Use methods only when their assumptions and scope are explicit.

1. **Finite-state/model checking:** enumerate meaningful Membership event sequences and test invariants around admission, ending, correction, annulment, same-day transitions, and historical reconstruction.
2. **Property-based testing:** generate valid and invalid histories and test invariant preservation.
3. **Mutation testing:** determine whether tests detect meaningful semantic faults; report the mutation scope and surviving mutants.
4. **Metamorphic testing:** test properties such as replay determinism and consistency between projections reconstructed at different `knownAt` times.
5. **Temporal testing:** distinguish current interpretation from what was known at a past decision time.
6. **Statistical discipline:** document the sampling frame, data provenance, selection bias, missingness, and uncertainty before making population-level claims. Do not use finite-model counts as probabilities.
7. **Machine learning:** first assess whether any authorised, appropriately labelled dataset exists. Do not inspect or expose personal member data unnecessarily. If no suitable dataset exists, state that empirical ML validation is blocked. ML may be evaluated later for human-reviewed duplicate-record triage; it must not decide membership, legal identity equivalence, authority, or voting eligibility.

For each computation, record the model assumptions, state-space bounds, excluded cases, result, and what the result does and does not establish.

# Phase 4 — Translate the theory into a TDD-first delivery plan

Do not change production code under this commission.

Create or refine a dependency-aware implementation plan that follows:

1. Ratify outstanding authority and correction-policy decisions.
2. Approve the Slice 2 contract, including G-AUTH-1 and T-EO.
3. Implement and verify correction/annulment only under a separate explicit implementation authorisation. This remains a mandatory prerequisite for cutover and live Membership writes.
4. Implement Slice 2 against the approved contract. It may proceed in parallel with the correction slice once their shared prerequisites are satisfied.
5. Complete required PBDIGIT-265 work and obtain the Finance column-ownership map.
6. Verify migration dry runs, historical reconstruction, and parity before cutover.
7. Complete authorised security testing and remediate findings confirmed by evidence before live HTTP/UI writes.

For each implementation story, specify:

- domain behaviour and bounded-context ownership;
- acceptance criteria;
- invariant and negative tests first;
- unit, property-based, integration, and persistence tests as applicable;
- migration and replay tests;
- concurrency and idempotency cases;
- mutation-testing expectations;
- observable evidence needed for acceptance;
- explicit out-of-scope changes;
- dependencies and authorisation gates.

Never treat a proposed test story as authorised simply because it exists in the backlog.

# Phase 5 — Close the current blockers

Maintain the following as explicit status items:

- **PBDIGIT-266:** prior evidence reported `phpunit.xml` targeting shared `nrna_test`, with the database safety gate failing. Re-verify current state read-only. Do not run tests until an isolated database is assigned and a fail-closed pre-test assertion proves the actual target.
- **PBDIGIT-267:** TG-1 through TG-5 remain a separate test-only item requiring explicit authorisation.
- **PBDIGIT-264:** remains separately unauthorised unless a new approval is found in the authoritative record.
- **Slice 1b / PBDIGIT-260:** conditional acceptance remains until required evidence, DV-1 countersignature, and a formal acceptance act are present.
- **Decision identifiers:** preserve Slice 1a `D-1 = INV-FID`. `S2-D1`–`S2-D6` remains a proposal unless an authoritative approval is found.
- **P1–P5:** track each unresolved policy question, recommended default, decision owner, and consequences. Recommendations must not be represented as approved policy.
- **Shared files:** reconcile the backlog index and context file only after Phase 0 confirms the state and concurrent changes are preserved.

# Final deliverables

1. Repository-integrity report.
2. Membership corpus evidence matrix.
3. Consolidated Membership theory, including formal invariants and counterexamples.
4. Conflict and decision register, with owners and dependencies.
5. Model-checking and testing gap analysis, including limitations.
6. Updated dependency graph and TDD-first implementation plan.
7. Prioritised TODO list with blockers and authorisation status.
8. Honest progress assessment split into:
   - domain discovery;
   - theory and invariants;
   - decision closure;
   - executable verification;
   - implementation;
   - migration/cutover readiness;
   - production readiness.

For every progress percentage, state the evidence and denominator. If no defensible quantitative measure exists, report a qualitative status instead.

Finish with:

- what is verified;
- what is still hypothetical;
- what decision is needed next;
- the single highest-value next action.

**Scope for this commission:** inspect, reason, model, and document. Do not implement production changes, run unauthorised tests, alter databases, or push commits.

## 4. How far are we from the Membership implementation goal?

My assessment separates the maturity of the theory and architecture from the amount of verified implementation. They are not the same thing.

## Membership readiness assessment

Qualitative estimates based on the research history and attached transcript—not a fresh repository audit.

Domain discovery and conceptual separation

Advanced

The key distinctions between Membership, authority, appointments, tenure, election entitlement, and access have been established in the research corpus.

Architecture and invariant definition

Advanced, not complete

Core semantics and several invariants are documented, but correction behaviour, authority evidence, and remaining Slice 2 decisions still need closure.

Formal decision closure

Blocked

Slice 1b is not formally accepted. Authority and correction-policy decisions, the Slice 2 contract, and other approvals remain outstanding.

Executable verification

Incomplete

There are existing tests and mutation-analysis results, but test gaps remain. PBDIGIT-266 was blocked by database isolation, and PBDIGIT-267 is not authorised.

Complete implementation and cutover

Not ready

Correction/annulment, Slice 2, migration parity, and security gates remain prerequisites before production Membership writes.

Bottom line: the Membership work is substantially beyond initial discovery, but it is not yet ready for a safe production cutover. I would not give one overall percentage: combining a mature conceptual model with incomplete executable verification would create false precision. The next meaningful milestone is not “more code”; it is a trustworthy repository baseline, a decision-complete contract, and executable evidence for the core invariants.

## 5. Remaining TODOs, in priority order

### P0 — Immediate safety and evidence

- Investigate `ae3faec5d` and establish whether concurrent backlog entries or context changes were omitted or overwritten.
- Verify the actual Git state, commit contents, and recoverability of any missing work; preserve other sessions' changes.
- Re-establish the authoritative Membership research corpus and current implementation baseline.
- Keep PBDIGIT-266 blocked until a dedicated database is assigned and the test process proves its target before executing any destructive setup.

### P1 — Complete the domain and authority decisions

- Resolve the outstanding authority and correction-policy questions: CQ-1/2/3, CQ-9, and BD-REV-ER.
- Resolve Slice 2 admission basis, End kinds, date semantics, G-AUTH-1, and T-EO.
- Approve the Slice 2 contract before implementation begins.
- Complete Slice 1b acceptance evidence and the formal acceptance act, including DV-1 countersignature.
- Decide PBDIGIT-266 policy questions P1–P5, recording the actual decision owner and approved outcome.
- Preserve `D-1 = INV-FID`; approve any Slice 2 identifier renaming formally before using it as authoritative.

### P1 — Make Membership correction safe

- Design correction and annulment semantics against the existing fact and decision model.
- Specify behaviour for correction of an admission, ending, and already-annulled or void decisions.
- Prove historical reconstruction for both current interpretation and `knownAt`.
- Define idempotency, ordering, refusal, and audit-evidence rules.
- Implement and verify the correction slice only after explicit authorisation.
- Keep cutover and live writes blocked until the mandatory correction gate passes.

### P2 — Strengthen verification and implementation

- Authorise and execute PBDIGIT-267 separately, if approved, to address TG-1 through TG-5.
- Authorise PBDIGIT-264 separately, then verify its actual scope and dependencies.
- Complete PBDIGIT-265 and obtain Finance's column-ownership map.
- Build a traceable suite of invariant, property-based, temporal, integration, and migration tests.
- Use mutation testing to assess test sensitivity and report the tested scope, not just a single score.
- Implement Slice 2 against the approved contract; coordinate its dependencies with the correction slice.

### P2 — Migration, security, and cutover

- Reconcile legacy Membership predicates and identify which are authoritative versus historical compatibility behaviour.
- Test migration parity, replay determinism, historical reconstruction, and recovery from interrupted migration.
- Confirm tenant isolation and route authorisation through the separately authorised PBDIGIT-266 investigation.
- Create remediation work items for security findings that are actually confirmed.
- Require correction, Slice 2, PBDIGIT-265, Finance ownership mapping, and migration evidence before cutover.
- Permit live HTTP/UI writes only after the required migration and security gates pass.

### P3 — Research improvements

- Maintain the corpus evidence matrix and resolve conflicting statements explicitly.
- Expand finite-state models around temporal transitions, correction, authority, and concurrent decisions.
- Document model bounds and counterexamples so results are reproducible and not misrepresented as probabilities.
- Assess whether a lawful, appropriately labelled dataset exists before considering ML experiments.
- If suitable data becomes available, evaluate duplicate-record triage with human confirmation, precision-focused metrics, and subgroup/error analysis.
- Keep the implementation roadmap, decision register, backlog index, and context file synchronised without overwriting concurrent work.

Progress figures and repository status should be refreshed by Claude only after it has inspected the current working tree and authoritative files. The attached transcript establishes the reported history, but it does not establish the repository's current state or the outcome of the final integrity check.