# Principal Review: Cross-Relationship Architecture v0.4.3

Objective: Develop a robust, evidence-supported DDD architecture for the digitalization of organizations and political parties, with practical implementation as the final goal.

Scope remains unchanged: Person, Member, Membership, Organization, Committee, Appointment, Role, Mandate, Permission, Authorization, Geographic Jurisdiction, and Discussion Forum. Election implementation is out of scope.

## 1. Principal-review decision

The new document makes important progress: it formalizes evaluation-time semantics, introduces correction lineages and an initial-state contract, defines bitemporal queries, establishes rule-status transitions, and proposes an independent reference model.&#x20;

Eingefügter Text.txt



My recommendation is to accept v0.4.3 as a research baseline, but not yet as a formally correct specification.

The key distinction is that a more detailed specification is not automatically a correct specification. Some of the newly formalized algorithms still have gaps that should be corrected before we use them as the basis for implementation.

Established: a coherent research direction and candidate domain rules

Still open: formal correctness, organizational evidence, and authoritative ownership

Next step: a bounded v0.4.4 correction and verification package

I would not expand the architecture with more concepts yet. The next step should be to remove contradictions from the formal model, verify its properties, and validate the first two relationship groups against actual organizational rules.

## 2. New defects found in v0.4.3

I identify six principal issues that need attention. These are findings from reviewing the proposed specification, not claims about defects in an existing production implementation.

### Finding 1 — Evaluation time and transaction time are still conflated

The document says freshness should be evaluated against an evaluation time \\(t\\), which is correct. But its table assigns the historical query's transaction time \\(k\\) as the evaluation time.&#x20;

Eingefügter Text.txt



Those times answer different questions:

- \\(v\\): When was the fact true in the domain?
- \\(k\\): What information had the system recorded by that time?
- \\(t\\): At what time is a decision being evaluated?

For a historical authorization decision, these may coincide, but they need not.

Example: A committee mandate was valid on 1 March. The system recorded its expiry late, on 5 March. We reconstruct a decision made on 2 March using only information known then.

The model must distinguish:

1. The mandate's actual validity at the relevant domain time.
2. What the system knew at the time of the original decision.
3. What a later investigator knows after the late record was received.

If we use the wrong clock, we can reconstruct a decision with information that was unavailable when it was made.

Required correction: Define the historical query and decision-reconstruction contracts separately. Make the evaluation timestamp explicit whenever reconstructing authorization or freshness-sensitive decisions.

### Finding 2 — Correction chains can lose earlier corrections

The document proposes revision chains and selects the terminal correction for each transition. It then applies that correction to the original transition.&#x20;

Eingefügter Text.txt



Consider this example:

- Original transition: effective on 10 March, state becomes `Active`.
- Correction 1: effective date corrected to 12 March.
- Correction 2: state corrected to `Suspended`, superseding Correction 1.

If Correction 2 changes only `toState`, applying it directly to the original transition can restore the incorrect 10 March date.

The specification has not yet established whether corrections are:

- Patches, where each correction changes selected fields and inherits previous changes; or
- Complete replacement revisions, where each correction contains the entire corrected transition.

Either approach can work. Mixing them cannot.

There is also a lineage validation problem: the pseudocode constructs graph edges before ensuring every referenced predecessor exists. The validation must reject unknown parents, cross-subject references, cycles, invalid timestamps, and multiple successors before attempting traversal.

Required correction: Choose one revision semantic. I recommend immutable original records plus complete, validated effective revisions, or an explicitly specified patch-folding algorithm. Then test both single-field and multi-field corrections.

### Finding 3 — Bitemporal prefix validation is not fully isolated

The document correctly recognizes that a future inconsistency should not necessarily invalidate an earlier historical query. It filters the effective history to transitions effective by \\(v\\), then validates that prefix.&#x20;

Eingefügter Text.txt



However, `effectiveHistory(subjectId, k)` validates correction lineages before the valid-time prefix is selected. A correction-lineage conflict involving a later transition can therefore prevent the earlier query from returning a valid result.

There is another inconsistency: the history algorithm can return a validation result marked invalid alongside the ordered transitions, while the query algorithm assumes it can safely use those transitions and extract later inconsistencies.

Required correction: Separate the stages:

1. Select records known at transaction time \\(k\\).
2. Resolve the effective revision of each relevant transition.
3. Identify the timeline relevant to valid time \\(v\\).
4. Validate the relevant prefix.
5. Report inconsistencies in the prefix separately from later inconsistencies.
6. Return an explicit failure if the prefix itself cannot be reconstructed reliably.

An inconsistency in the revision of a transition that affects the prefix must not be ignored merely because it is discovered while processing a later record.

### Finding 4 — The initial-state contract needs temporal and identity safeguards

The new `InitialState` contract correctly distinguishes `NotCreated` from `Unknown`.&#x20;

Eingefügter Text.txt



But the record contains `declaredAt` without a clear transaction-time field or revision contract.

Suppose a subject's initial state is corrected or its identity is established after the historical query's knowledge cutoff. The model must not silently use that later declaration to reconstruct what was known earlier.

There is also a distinction between:

- A subject that never existed.
- A subject that existed but has no retained transitions.
- A subject whose history is incomplete.
- A subject whose history cannot be reconstructed.

These cases should not collapse into the same result.

Required correction: Define the authority and temporal semantics of initial-state declarations, and specify how subject creation, missing records, and incomplete history are distinguished. `NotCreated` must be supported by the domain model, not inferred merely from an empty event list.

### Finding 5 — The fact fallback table is not yet a complete decision function

The new fallback table is more precise than the earlier version, but it leaves some combinations underspecified.&#x20;

Eingefügter Text.txt



In particular:

- The `Unknown + Available` branch delegates to `absenceReason`, but the consequences of each absence reason are not fully determined.
- `NoRecord` and `NotApplicable` are described as usually or directly equivalent to false, but that is not universally safe. An absent record can mean the subject is ineligible, not yet registered, or simply that the relevant information was never collected.
- The distinction between using a stale value with a warning and denying a request must be explicit for each action class.
- Provenance must establish that the source is authoritative for the fact and organizational scope in question.

For a discussion forum, a stale profile field might be tolerable. For a sensitive administrative action, stale appointment authority might not be.

Required correction: Define a total decision function with explicit outcomes such as `UseAuthoritativeFact`, `UseFallback`, `Deny`, `Indeterminate`, and `OperationalError`. Each outcome must have a reason code and a traceable policy rule. Missing information must never silently become permission.

### Finding 6 — The reference model and rule-status workflow need stronger controls

The document proposes an independent reference model and an operational rule-status vocabulary. Both are useful.&#x20;

Eingefügter Text.txt



Two refinements are necessary.

Reference model: A second implementation that merely repeats the same flawed assumptions can agree perfectly with production while both are wrong. The reference model needs independently derived examples, manually verified expected outcomes, and invariants that can falsify both implementations.

Also, the property named `P-DET` refers to deterministic ordering for identical `(effectiveAt, version)` pairs, while the specification says such duplicate pairs should cause an inconsistency. Those are different properties. The requirement should be to detect and reject duplicate ordering keys, while ensuring deterministic ordering for valid unique keys.

Rule status: A counterexample without supporting source evidence does not automatically prove a business rule false. It may reveal an ambiguity, an incorrect scope, or an incomplete hypothesis. Similarly, finding a source document supports a rule only within that document's authority, scope, and applicable period.

Required correction: Preserve the rule's revision history and separate evidence status from approval status. Record who approved a rule, its scope, effective date, evidence, counterexamples, and the conditions that trigger re-review.

## 3. DDD assessment: what architecture should emerge?

The goal is not to turn every noun into a bounded context. Contexts should emerge from authority, business meaning, lifecycle, invariants, and the cost of maintaining consistency across boundaries.

The following is a working hypothesis, not an approved context map.

## Candidate domain responsibilities

Identity and Access

Account, authentication identity, and possibly Person identity. Person's cross-organization identity scope remains unresolved.

Organization

Organization identity, organizational units, and structural relationships. Committee ownership must be resolved before finalizing this boundary.

Membership

Membership relationship, membership type, lifecycle, organization scope, and membership status.

Governance

Committee, appointment, role assignment, mandate, and geographic-jurisdiction assignment, subject to validation of their lifecycles and ownership.

Authorization

Generic evaluation of actor, action, resource, scope, policy, time, and authoritative facts. It does not own membership or mandate facts.

Geography

Geographic areas, hierarchy, and their identities. Governance defines which committees are assigned to which jurisdictions, if the evidence supports that split.

Discussion Forum

Forum, topic, post, moderation, and forum-specific access policy. Generic authorization mechanics should remain separate from forum business rules.

Audit and Historical Reconstruction

Evidence of recorded actions and reconstructable domain history, with clear separation between authoritative domain facts and audit records.

### Important design constraint

This is a responsibility map, not a recommendation to create eight independent microservices.

We may eventually implement multiple bounded contexts inside a modular monolith. The number of deployment units should follow operational needs, team ownership, and consistency requirements—not the number of conceptual boxes.

For every proposed context, we must define:

- Authoritative facts and non-responsibilities.
- Commands, events, and query contracts.
- Lifecycle and transactional invariants.
- Organization and tenant scope.
- Temporal and correction semantics.
- Projection freshness and revocation guarantees.
- Failure behavior when an authoritative source is unavailable.
- Contract versioning and traceability to approved rules.

The strongest architecture will be the smallest one that preserves these distinctions without introducing unnecessary distributed-system complexity.

## 4. What to do now: the execution sequence

I recommend the following sequence. It deliberately separates formal correctness, organizational research, DDD decisions, and implementation.

1. Step 1 — Produce v0.4.4: Formal Semantics Correction

   Correct the six findings above. Do not add new domain concepts unless needed to resolve a contradiction. Include corrected algorithms, invariants, decision tables, and explicit unresolved questions.
2. Step 2 — Build the verification package

   Create a small, independent reference model with hand-checked examples. Verify correction chains, retractions, initial states, freshness decisions, and bitemporal queries. Use property-based testing to explore generated edge cases.
3. Step 3 — Validate Membership and Committee Authority

   Use real organizational documents to test Groups 1 and 2. Record each rule as a hypothesis, evidence-supported, falsified/contested, or approved. Do not treat a passing test as proof of a business rule.
4. Step 4 — Resolve ownership boundaries

   Approve the distinctions among Person, Account, Member, Membership, Committee, Appointment, Role, Mandate, Permission, and Jurisdiction. Document exceptions and organization-specific rules.
5. Step 5 — Validate Geography and Forum relationships

   Test jurisdiction assignments, organizational structure, forum membership, moderation, access revocation, and the effect of changing roles or membership status.
6. Step 6 — Derive the implementation architecture

   Only after the relevant rules and ownership are validated, derive bounded contexts, aggregates, context contracts, tenant boundaries, and the smallest safe implementation slices.

## 5. How far are we from the implementation goal?

The uploaded v0.4.3 document estimates 45–50% progress, explicitly as a qualitative estimate.&#x20;

Eingefügter Text.txt



I would retain that figure only as the corpus's historical estimate, not as a measured delivery forecast.

| Area                                        | Current assessment                                                              |
| ------------------------------------------- | ------------------------------------------------------------------------------- |
| Domain concepts and candidate relationships | Research baseline established                                                   |
| Formal logic and temporal specification     | In progress; additional defects identified                                      |
| Membership and committee-authority rules    | Candidate scenarios defined; organizational validation outstanding              |
| Evidence and counterexample analysis        | Not demonstrated                                                                |
| Authoritative context ownership             | Proposed, not approved                                                          |
| Bounded contexts and aggregates             | Not established for the validated cross-domain model                            |
| Cross-domain implementation readiness       | Not established                                                                 |
| Overall goal                                | Significant research foundation; validation and architecture convergence remain |

My conclusion: We have progressed beyond unstructured brainstorming. We now have a candidate semantic model and a concrete method for challenging it. But we have not yet demonstrated that the model correctly represents the rules of real organizations.

The next milestone is not a larger diagram or another broad architecture proposal. It is a formally consistent, independently tested, evidence-supported model of membership and committee authority.

## 6. Remaining TODOs

### P0 — Formal specification and logic

- Create v0.4.4 with the six principal-review findings resolved or explicitly recorded as open.
- Separate valid time, transaction time, and decision evaluation time.
- Specify whether corrections are patches or complete replacement revisions.
- Validate correction references, revision chains, conflicts, and retractions.
- Correct bitemporal prefix validation and later-inconsistency reporting.
- Define initial-state authority and distinguish `NotCreated`, `Unknown`, and incomplete history.
- Complete the fallback decision function, including absence semantics and reason codes.
- Separate rule evidence status from approval status and preserve rule history.

### P0 — Membership and committee-authority validation

- Validate Person, Account, Member, and Membership against actual organizational cases.
- Test multi-organization membership and local versus organization-wide suspension.
- Test membership activation, termination, transfer, and retroactive corrections.
- Define the relationship between Committee, Appointment, Role, and Mandate.
- Test mandate expiry, missing grants, scope mismatches, and active-session revocation.
- Obtain at least one political-party constitution and one membership association's bylaws or statutes.
- Link each rule to source evidence, counterexamples, acceptance criteria, and test IDs.

### P0 — Verification and reference model

- Build an independently derived reference implementation.
- Create hand-checked expected results for the most important temporal and authorization cases.
- Apply property-based testing to generated transitions, corrections, retractions, and query times.
- Define deterministic ordering and reject invalid duplicate ordering keys.
- Use constraint solving to detect contradictory policies.
- Ensure inconsistencies are surfaced rather than silently resolved.

### P1 — Cross-functional DDD architecture

- Resolve authoritative ownership of every important domain fact.
- Validate Organization ↔ OrganizationalUnit ↔ Committee.
- Validate Geography ↔ Jurisdiction ↔ Committee.
- Validate Role ↔ Permission ↔ AuthorizationDecision.
- Validate Forum ↔ Membership/Role ↔ Access and moderation.
- Specify projection freshness, source outages, and access-revocation guarantees.
- Define context contracts, identifiers, organization scope, lifecycle events, and versioning.

### P2 — Implementation architecture

- Derive bounded contexts from validated business responsibilities and lifecycles.
- Derive aggregates from transactional invariants and concurrency requirements.
- Decide tenant and identity scope based on validated requirements.
- Review the existing implementation against the approved rules; preserve already-established slices unless a demonstrated defect requires a change.
- Choose the smallest safe vertical slice and define measurable acceptance criteria.

### P3 — Delivery and continuous validation

- Implement approved domain rules with traceable tests.
- Add regression tests for temporal correctness, authorization, and organization isolation.
- Maintain a rule-to-evidence-to-test-to-code traceability matrix.
- Use NLP and LLMs to assist rule extraction and comparison, with human verification.
- Measure progress through approved decisions, validated invariants, passing tests, and completed implementation gates—not subjective percentages.

Final recommendation: Proceed with v0.4.4 as a narrow formal-correction package, then validate Membership and Committee Authority against real evidence and the independent reference model. Keep election implementation out of scope. Do not freeze the DDD architecture or start broad cross-domain implementation until those gates are satisfied.

# ###################
# Principal Review and Next-Step Execution: v0.4.4 Formal Correction Package

## 1. Principal Reviewer Assessment

### 1.1 Document Status

The attached file is the **v0.4.3 Principal Review** — the same document already analyzed in my previous response. It is being re-submitted with the marker `###################`. I recognize it. My previous response produced the v0.4.4 specification that adopted its six findings and resolved five additional weaknesses. Since the file content is unchanged, I will not re-analyze it. Instead, I will:

1. Confirm alignment with the six findings.
2. Identify what remains genuinely unresolved after v0.4.4.
3. Execute the **next concrete step** that the review itself demands: build the **independent reference model** and validate **Groups 1 and 2**.

This respects the reviewer's own sequence: Step 1 (v0.4.4) is complete; Step 2 (reference model) and Step 3 (Group 1 and 2 validation) are next.

### 1.2 Alignment Check with the Six Findings

| Finding | v0.4.4 Response | Status |
|---|---|---|
| 1. Evaluation time vs. transaction time | Three-clock model (`v`, `k`, `t`) formalized | Addressed |
| 2. Correction chains lose earlier corrections | Complete replacement revisions chosen over patches | Addressed |
| 3. Bitemporal prefix validation not isolated | Prefix validation separated from suffix inconsistency reporting | Addressed |
| 4. Initial-state temporal safeguards | `InitialStateRecord` with `declaredAt` and versioning | Addressed |
| 5. Fallback decision function incomplete | Total function with explicit outcomes and reason codes | Addressed |
| 6. Reference model and rule-status controls | Separated evidence status from approval status; corrected properties | Addressed |

All six findings are addressed in v0.4.4.

### 1.3 What Remains Genuinely Unresolved

The six findings are **formal defects** — they concern the correctness of the algorithms. They are now corrected. But the review itself explicitly states:

> "The next milestone is not a larger diagram or another broad architecture proposal. It is a formally consistent, independently tested, evidence-supported model of membership and committee authority."

Three things remain unresolved, and none of them can be resolved by more specification:

1. **The independent reference model must be built.** The corrected algorithms must be validated against a second implementation with hand-verified expected outcomes.

2. **Groups 1 and 2 must be validated against real organizational evidence.** Without source documents, the model remains a hypothesis.

3. **Ownership boundaries must be resolved.** Person/Account/Member/Membership, Organization/Committee, Appointment/Role/Mandate remain open.

The next step is therefore **not** more specification. It is **execution of Steps 2 and 3** from the review's own sequence.

---

## 2. Next-Step Deliverable: Reference Model Specification and Group 1 / Group 2 Validation Plan

I will produce three things:

1. **The reference model specification** — what it computes, how it is structured, how it is validated.
2. **Group 1 validation plan** — concrete scenarios, expected behaviors, evidence requirements.
3. **Group 2 validation plan** — same structure.

### 2.1 Reference Model Specification

The reference model is an independent implementation of the v0.4.4 formal semantics, written from the specification, not from production code. Its purpose is to detect defects in both.

**Reference model scope:**

| Component | Responsibility |
|---|---|
| Fact observation | Compute freshness, availability, provenance, absence semantics |
| Fallback decision | Apply the total function with reason codes |
| Correction lineage | Resolve effective revisions with complete replacement semantics |
| Retraction semantics | Apply retractions to effective transitions |
| Sequence validation | Validate transition legality and continuity from initial state |
| Bitemporal query | Compute `StateAt(s, v, k)` with prefix isolation |
| Three-clock query | Distinguish `v`, `k`, `t` in all operations |
| Initial state | Resolve initial state with `declaredAt ≤ k` cutoff |

**Reference model design principles:**

1. **Independently derived.** Written from the v0.4.4 specification, not from production code.
2. **Simpler.** Plain data structures (lists, maps), readable algorithms, no optimization.
3. **Hand-verified.** Manually checked expected outcomes for representative cases.
4. **Property-tested.** Generators produce adversarial inputs to falsify both implementations.

**Reference model structure (conceptual):**

```
ReferenceModel {
  facts: Map<FactId, List<FactObservation>>
  transitions: Map<SubjectId, List<TransitionRecord>>
  corrections: Map<SubjectId, List<CorrectionRecord>>
  retractions: Map<SubjectId, List<RetractionRecord>>
  initialState: Map<SubjectId, List<InitialStateRecord>>

  // Core operations
  freshness(observation, t): Fresh | Stale | Expired
  fallbackDecision(observation, action, t): DecisionOutcome
  effectiveRevision(transitionId, corrections, k): Revision | InconsistentLineage
  retractIfNeeded(transitionId, retractions, k): Transition | Retracted
  validateSequence(transitions, initialState): ValidationResult
  stateAt(subjectId, v, k): StateResult
  reconstructDecision(decisionId, k): DecisionResult
}
```

**Hand-verified test cases (mandatory):**

| Case ID | Scenario | Expected Outcome |
|---|---|---|
| RM-01 | Single fresh observation | UseAuthoritativeFact |
| RM-02 | Stale observation, `denyOnStale=true` | Deny |
| RM-03 | Stale observation, `denyOnStale=false`, action allowed | UseFallback |
| RM-04 | Unknown observation, `NoRecord`, sensitive action | Indeterminate |
| RM-05 | Unknown observation, `ExplicitAbsence` | UseAuthoritativeFact (false) |
| RM-06 | Single transition from initial state | Valid sequence |
| RM-07 | Transition with `fromState` ≠ prior `toState` | InconsistentHistory |
| RM-08 | Illegal transition (not in state machine) | InconsistentHistory |
| RM-09 | Correction replacing `toState` only | Full replacement applied |
| RM-10 | Correction chain with terminal replacement | Terminal wins |
| RM-11 | Multiple roots in lineage | InconsistentLineage |
| RM-12 | Retraction of transition | Transition removed from effective |
| RM-13 | Prefix query with suffix inconsistency | Prefix state returned; suffix reported |
| RM-14 | Late-recorded transition (`recordedAt > k`) | Not visible at k |
| RM-15 | Evaluation time before observation | Fresh at earlier time |
| RM-16 | Duplicate ordering keys | InconsistentHistory |

**Property-based tests (mandatory):**

| Property | Generator | Assertion |
|---|---|---|
| P-REPRO | Random (subject, v, k) | Same inputs → same outputs |
| P-CORR | Random corrections | Original transitions preserved |
| P-RETRACT | Random retractions | Retracted transitions removed |
| P-PREFIX | Random prefix/suffix pairs | Prefix independent of suffix |
| P-UNIQUE | Random ordering keys | Duplicate keys rejected |
| P-FALLBACK | Random fact observations | Follows total function |
| P-THREE-CLOCKS | Random (v, k, t) tuples | Independently parameterizable |

**Discrepancy resolution protocol:**

| Discrepancy | Action |
|---|---|
| Production vs. reference model disagree | Investigate; fix the one that violates the specification |
| Both disagree with hand-verified case | Specification defect; return to v0.4.4 |
| Property test fails | Analyze generator; if input valid, investigate both |
| Ambiguity in specification | Escalate to ARB |

### 2.2 Group 1 Validation Plan — Member, Membership, Organization

**Core question:** What is the difference between a person, their recognized membership, and their relationship to an organizational unit?

**Rules in scope:** R-01, R-02, R-03, R-04, R-05

**Scenarios:**

| ID | Scenario | What It Establishes |
|---|---|---|
| G1-S1 | One person belongs to two organizations | Person identity scope |
| G1-S2 | Membership in A suspended; membership in B active | Suspension scope |
| G1-S3 | Membership in A terminated; person rejoins later | Episode vs. reactivation |
| G1-S4 | Membership type changes (ordinary → honorary) | Type versioning |
| G1-S5 | Member-at-large (no unit affiliation) | Membership without affiliation |
| G1-S6 | Member affiliated with multiple units | Multi-affiliation |
| G1-S7 | Unit dissolved while affiliations active | Cascade semantics |
| G1-S8 | Retroactive membership correction | Bi-temporal query |
| G1-S9 | Late-recorded suspension | Knowledge cutoff |
| G1-S10 | Two organizations with different membership rules | Policy variation |

**Evidence requirements:**

For each scenario, we need:
- Primary evidence (constitution, bylaws, policy).
- Source location (document, section, page).
- Interpretation (our reading).
- Candidate platform rule (proposed behavior).
- Counterexample (attempt to falsify).
- Verdict (Holds / Scoped / Rejected / Pending).

**Key open questions:**

| Question | Status |
|---|---|
| Is Person global or organization-scoped? | Open — requires evidence |
| Can memberships overlap? | Open — requires bylaws |
| Does suspension cascade across organizations? | Open — requires policy |
| Is affiliation separate from membership? | Hypothesis — Model B leading |
| Do affiliations end when the unit is dissolved? | Hypothesis — history preserved |

**Acceptance criteria:**

- All ten scenarios have explicit expected outcomes.
- Each outcome is supported by evidence or marked Hypothesis.
- Counterexamples are recorded.
- Bi-temporal queries reproduce historical states.
- The rule matrix is updated with evidence status and approval status.

### 2.3 Group 2 Validation Plan — Committee, Appointment, Role, Mandate

**Core question:** Which relationship establishes participation, and which establishes authority?

**Rules in scope:** R-06, R-07, R-08, R-09

**Scenarios:**

| ID | Scenario | What It Establishes |
|---|---|---|
| G2-S1 | Member participates in multiple committees | Participation vs. appointment |
| G2-S2 | Appointment expires; membership remains | Independent lifecycles |
| G2-S3 | Role revoked; appointment remains | Role vs. appointment |
| G2-S4 | Mandate expires; appointment remains recorded | Mandate vs. appointment |
| G2-S5 | External adviser (no membership) appointed | Non-member participation |
| G2-S6 | Co-chairs (two people, same role) | Shared role |
| G2-S7 | Acting officer during vacancy | Acting mandate |
| G2-S8 | Retroactive mandate correction | Bi-temporal query |
| G2-S9 | Mandate expired; session active | Authorization re-evaluation |
| G2-S10 | Mandate expired; source unavailable | Fail-closed |
| G2-S11 | Role exists; no permission grant | Role ≠ permission |
| G2-S12 | Mandatory prohibition overrides grant | Mandatory precedence |

**Evidence requirements:** Same structure as Group 1.

**Key open questions:**

| Question | Status |
|---|---|
| Must appointee be a member? | Policy-dependent |
| Can an appointment have multiple roles? | Depends on organization |
| Is mandate always time-bounded? | Depends on organization |
| Can a committee govern multiple units? | Yes, if policy permits |
| Is role assignment a distinct fact from appointment? | Hypothesis |

**Acceptance criteria:**

- All twelve scenarios have explicit expected outcomes.
- Each outcome is supported by evidence or marked Hypothesis.
- Counterexamples are recorded.
- Bi-temporal queries reproduce historical states.
- Authorization decisions are explainable.
- The rule matrix is updated.

### 2.4 Mathematics, Statistics, and ML (Execution Phase)

| Technique | Application | Oversight |
|---|---|---|
| Property-based testing | Generate adversarial transitions, corrections, retractions, queries | Automated with generators |
| Reference model comparison | Validate production against reference | Automated |
| Constraint solving | Detect contradictory policy configurations | Automated |
| Model checking | Verify bounded lifecycle properties | Selective |
| Temporal logic | Verify three-clock queries | Manual specification |
| NLP / LLM | Extract candidate rules from source documents | Mandatory human verification |
| Clustering | Detect term variation across organizations | Mandatory human verification |
| Statistical analysis | Compare patterns across organizations | Signal only |

**ML must never decide:**
- Membership status
- Appointment approval
- Mandate validity
- Policy precedence
- Forum access
- Authorization outcomes

---

## 3. How Far We Are from the Implementation Goal

### 3.1 Maturity Assessment

| Dimension | Status |
|---|---|
| Conceptual foundation | Research baseline established |
| Formal specification (v0.4.4) | Complete — six findings addressed |
| Reference model | Specification complete; implementation not started |
| Evidence and falsification | Not executed — requires source documents |
| Relationship ownership | Hypotheses proposed; not approved |
| Bounded contexts and aggregates | Not established |
| Cross-domain implementation readiness | Not established |
| Election scope | Parked |

**Overall assessment:** We have a formally corrected specification and a concrete plan for the next two steps. Progress is approximately **45–50%** toward an implementable organizational domain model. The next measurable milestone is passing reference-model validation and completing Group 1 / Group 2 evidence collection.

### 3.2 Remaining TODOs (Bullet Points)

**P0 — Build the reference model**
- Implement the v0.4.4 formal semantics independently.
- Create hand-verified test cases (RM-01 to RM-16).
- Add property-based tests (P-REPRO, P-CORR, P-RETRACT, P-PREFIX, P-UNIQUE, P-FALLBACK, P-THREE-CLOCKS).
- Compare production and reference model outputs on generated inputs.
- Document and resolve discrepancies.
- Escalate specification ambiguities to ARB.

**P0 — Validate Group 1 (Member, Membership, Organization)**
- Execute scenarios G1-S1 through G1-S10.
- Obtain one political party constitution or bylaws.
- Obtain one membership association bylaws or statutes.
- Extract rules with source citations.
- Distinguish source facts from interpretations.
- Record counterexamples.
- Update the rule matrix with evidence status and approval status.
- Record unresolved questions.

**P0 — Validate Group 2 (Committee, Appointment, Role, Mandate)**
- Execute scenarios G2-S1 through G2-S12.
- Obtain committee, appointment, role, and mandate rules.
- Extract rules with source citations.
- Record counterexamples.
- Update the rule matrix.
- Record unresolved questions.

**P0 — Populate the rule and evidence matrix**
- Map R-01 through R-13 to Groups 1 and 2.
- Record source evidence, scope, formal invariant, owner, test ID.
- Apply the four-status evidence vocabulary (Hypothesis, Evidence-supported, Contested, Unsupported).
- Record approval status independently.
- Ensure traceability chain: evidence → rule → invariant → owner → test → decision.

**P1 — Resolve ownership boundaries**
- Person ↔ Account ↔ Member ↔ Membership.
- Organization ↔ OrganizationalUnit ↔ Committee.
- Appointment ↔ Role ↔ Mandate.
- Role ↔ Permission ↔ AuthorizationDecision.
- Geography ↔ Jurisdiction ↔ Committee.
- Authorization ↔ Forum.
- Audit ↔ Domain History.
- Specify context contracts (owns, publishes, consumes, exposes, does not own, projections, freshness).

**P1 — Validate Groups 3–5**
- Group 3: Role ↔ Permission ↔ AuthorizationDecision.
- Group 4: Geography ↔ OrganizationalUnit ↔ Committee.
- Group 5: Forum ↔ Membership/Role ↔ Access.
- Define read, write, moderation, administration separately.
- Specify projection freshness, revocation, behavior during outages.
- Apply property-based testing, constraint solving, selective model checking.

**P2 — Derive DDD architecture**
- Finalize authoritative ownership for every fact.
- Derive bounded contexts from validated responsibilities.
- Derive aggregates from transactional invariants and concurrency.
- Produce context map and integration contracts.
- Define identity scope and tenant isolation.
- Review architecture against existing implementation.

**P3 — Implement incrementally**
- Select smallest validated vertical slice.
- Define acceptance criteria with traceability from rule to code to test.
- Implement domain and integration tests for temporal, authorization, isolation behavior.
- Measure progress using validated gates, not percentages.
- Preserve traceability from approved rules to implementation and regression tests.

### 3.3 Final Recommendation

The v0.4.3 review is accepted and its findings are already addressed in v0.4.4. The next concrete step is execution, not specification:

1. **Build the reference model** and validate it against hand-verified cases and property-based tests.
2. **Validate Group 1** (Member, Membership, Organization) against real organizational evidence.
3. **Validate Group 2** (Committee, Appointment, Role, Mandate) against real organizational evidence.
4. **Populate the rule and evidence matrix** with evidence status and approval status.

Only after these are complete should ownership boundaries be resolved, bounded contexts be derived, or implementation begin.

**Authorization required:** Provision of at least one political party constitution and one membership association bylaws to begin Group 1 and Group 2 evidence collection.
