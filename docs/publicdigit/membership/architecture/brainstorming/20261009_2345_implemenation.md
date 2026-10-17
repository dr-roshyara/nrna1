Understood. From now on, I will work exclusively in the PublicDigit Membership domain, even when a message contains material about other platform domains. I will treat unrelated content as out of scope unless it is a necessary Membership integration boundary.

My role is to review the Membership corpus as a principal DDD architect, requirements engineer and formal-methods researcher, and to help turn the ratified Membership model into a correct, testable and safely deployable implementation.

## 1. Membership-only assessment

The supplied status report describes a clear separation between the domain foundation and production readiness:

- Domain theory: ratified, including one Member per organisation/person, append-only history, re-entry as a new Membership period and explicit temporal semantics.
- Kernel: verified for Admit and End, but it implements only 2 of the 9 ratified fact types.
- Application layer: reported as built and verified, including concurrency and retry behaviour.
- Persistence layer: built but conditionally accepted; the far-date defect and additional hardening remain.
- Correction: a model exists, but correction operations are not implemented.
- Application integration: Slice 2 has not started because its decisions remain open.
- Migration and cutover: not ready; the report says zero of eleven cutover gates have been met.

The important distinction is that a workable correction model is evidence for the design, not proof that the production implementation is correct. The correction contract, implementation, persistence behaviour, integration and cutover evidence are still separate obligations.

## 2. The next Membership action

I recommend proceeding with the correction-slice contract, while keeping the unresolved policy questions explicitly open.

The next step is not to reopen ratified domain theory or start a broad redesign. It is to translate the accepted correction rules into an implementation contract that can be reviewed before code is written.

The contract should:

1. Define the semantics of `Annul`, `Correct` and `Void` against the ratified Membership fact model.
2. Preserve append-only history and the distinction between recorded knowledge and effective business time.
3. State which invalid histories are rejected and why.
4. Use the correction oracle as a reference model, while acknowledging its bounded coverage.
5. Keep BD-REV-ER, CQ-3 and CQ-9 open until the appropriate decision authority resolves them.
6. Specify the application, persistence, concurrency and audit obligations without implementing them prematurely.

For BD-REV-ER, the recommendation remains refuse a correction that silently absorbs an entire live Membership period. That is a proposal, not a ratified business rule.

## 3. Claude Code prompt — Membership correction contract

# Membership Mission — Draft the Correction-Slice Contract

## Role

Act as a principal DDD architect, Membership domain researcher, requirements engineer, formal-methods analyst and senior implementation reviewer.

Work exclusively within the PublicDigit Membership domain and its necessary technical integration boundaries. Do not switch to general platform architecture, unrelated Election security, Finance implementation or other domains.

## Objective

Draft a reviewable implementation contract for Membership correction operations based on the existing ratified Membership architecture and the executable correction oracle.

This mission is documentation and analysis only. It does not authorise implementation.

## 1. Establish the authoritative baseline

Inspect the existing Membership architecture, ratification records, fact algebra, temporal model, kernel, application layer, persistence adapter, correction oracle, checks, research report and decision register.

Reuse existing artefacts and conventions. Do not create a competing model or duplicate documentation structure.

Distinguish explicitly between:

- ratified domain rules;
- verified implementation behaviour;
- bounded model results;
- proposals;
- unresolved decisions;
- unverified assumptions.

Cite repository paths and relevant symbols. Do not claim to have run tests unless they were actually executed. Do not run tests in this mission.

## 2. Preserve the ratified Membership model

Preserve these concepts and constraints:

- One Member per organisation/person.
- Re-entry creates a new MembershipPeriod under the same Member.
- Membership facts are append-only.
- Current Membership truth is derived from recorded facts.
- Calendar dates represent Membership valid time.
- Instants represent decision/recording time and support reconstruction of what was known at a given time.
- Membership does not itself grant voting entitlement or application access.
- Membership, MembershipPeriod, CommitteeAppointment, Tenure, Mandate, Authority, ElectionRegistration, VotingEntitlement and Exercisability must not be collapsed into one concept.

Do not revise ratified rules unless contradictory evidence is found. If such evidence exists, report it as a discrepancy requiring review rather than silently changing the model.

## 3. Draft the correction contract

Specify the intended semantics and preconditions for:

- Annul;
- Correct;
- Void;
- rejection of invalid correction attempts;
- reconstruction of current and historical truth;
- repair of erroneous or spurious Admit/End facts;
- preservation of the original evidence and correction lineage.

For each operation, define:

1. Business meaning.
2. Preconditions.
3. Permitted targets.
4. Resulting facts and relationships.
5. Invariants that must remain true.
6. Rejection conditions.
7. Temporal semantics.
8. Concurrency and idempotency expectations.
9. Persistence and audit requirements.
10. Acceptance criteria and corresponding tests.

Do not invent semantics where the ratified corpus is silent. Mark the question OPEN and state who must decide it.

## 4. Keep unresolved policy questions open

### BD-REV-ER — Whole-period absorption

The current recommendation is to refuse a correction that silently absorbs an entire live Membership period. This is a recommendation only.

Document the problem, evidence, alternatives, consequences and recommended option. Keep the decision OPEN until the authorised decision-maker ratifies it.

### CQ-3 — Correction authority

Keep OPEN. Identify the exact authority questions and the evidence needed to decide who may correct, annul or void Membership records.

Do not assume that possession of a technical role proves domain authority.

### CQ-9 — Appeals

Keep OPEN. Identify the unresolved appeal semantics, the boundary between an original decision and an appeal, and any consequences for correction history. Do not invent an appeal process.

These questions must not be marked approved merely because the contract can be drafted around them.

## 5. Validate the oracle's evidential role

Inspect the correction oracle and its checks. Report what they establish and what they do not establish.

In particular:

- distinguish agreement with the production kernel from independent correctness;
- preserve the stated model bounds;
- distinguish finite-model counts from probabilities;
- identify unmodelled operations, authority, concurrency, persistence and other excluded cases;
- define how oracle cases will become executable implementation tests;
- identify any ambiguity between the ratified rule and the oracle implementation.

Do not change production code or alter the ratified specification to make the oracle pass.

## 6. Define the implementation boundary

Describe the minimal future implementation slices and their dependencies.

Keep separate:

- domain decision semantics;
- application command handling;
- repository/persistence behaviour;
- concurrency and retry behaviour;
- audit/correction lineage;
- integration with Membership consumers;
- migration and cutover.

Identify what can proceed in parallel and what is blocked by BD-REV-ER, CQ-3 or CQ-9.

Do not treat Slice 2 as approved merely because it can proceed technically in parallel.

## 7. Explicit exclusions

Do not:

- modify production code, tests, schemas, migrations or configuration;
- run tests or database commands;
- execute PBDIGIT-264, PBDIGIT-266 or PBDIGIT-267;
- implement correction operations;
- run a migration or cutover;
- change the ratified Membership architecture;
- modify unrelated domain artefacts;
- stage, commit or push changes.

## 8. Deliverables

Produce a correction-slice contract containing:

1. Purpose, scope and non-goals.
2. Ratified rules and traceability.
3. Operation semantics and preconditions.
4. Invariants and rejection rules.
5. Temporal and historical reconstruction rules.
6. Correction lineage and audit requirements.
7. Oracle evidence, limitations and test mapping.
8. Acceptance criteria.
9. Open decisions: BD-REV-ER, CQ-3 and CQ-9.
10. Dependency-aware implementation plan.
11. Risks, assumptions and outstanding TODOs.
12. A clear recommendation for the next separately authorised implementation slice.

Finish with a decision register that distinguishes APPROVED, PROPOSED, OPEN and BLOCKED. Do not claim implementation readiness or production readiness based solely on the contract.

Report all files read or changed. The expected result is a contract draft only; no code, tests, database changes, staging, commits or pushes.

## 4. Updated Membership TODOs

### P0 — Contract and decisions

- Draft and review the correction-slice contract.
- Resolve BD-REV-ER through the appropriate decision authority.
- Resolve CQ-3: authority to correct, annul and void.
- Resolve CQ-9: appeals and their relationship to correction history.
- Preserve the distinction between ratified rules and proposed semantics.

### P1 — Correction implementation and persistence

- Implement correction operations in separately authorised slices.
- Convert oracle cases into implementation-level tests.
- Verify correction lineage, temporal reconstruction and rejection behaviour.
- Address the far-date defect and other approved PBDIGIT-264 hardening.
- Obtain the required formal acceptance and countersignature for Slice 1b.

### P1 — Integration and cutover

- Decide the six Slice 2 questions, including admission basis, End kinds, date handling and interim authority.
- Resolve the Membership/Election eligibility discrepancy in PBDIGIT-265 with the Election owner; do not conflate Membership with voting entitlement.
- Complete the approved application wiring and legacy-reader/writer analysis.
- Build and verify the cutover machinery, migration parity and rollback procedure.
- Satisfy the cutover gates with recorded evidence.

### P2 — Additional verification

- Obtain the dedicated test database required for the separately authorised PBDIGIT-266 investigation.
- Run only explicitly authorised tests after database safety is verified.
- Authorise and close the PBDIGIT-267 kernel test gaps separately.
- Demonstrate end-to-end Membership correctness and production readiness.

Overall assessment: the Membership domain foundation is advanced, but the correction operations and product integration remain incomplete. The immediate critical path is correction contract → policy decisions → separately authorised implementation → persistence and integration verification → cutover evidence.