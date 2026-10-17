# Principal Review: Cross-Relationship Architecture v0.4.2

Review perspective: Principal Reviewer, Senior DDD Architect, Requirements Engineer, and specialist in formal logic, temporal models, statistical validation, and machine-learning-assisted research.

Scope: Digitalization platform for organizations and political parties. Election implementation remains explicitly out of scope. We are investigating the cross-functional relationships among Person, Member, Membership, Organization, Committee, Appointment, Role, Mandate, Permission, Authorization, Geography, and Discussion Forum.

## 1. Executive assessment

The attached document, Principal Review and Next-Step Execution: Cross-Relationship Architecture v0.4.2, represents meaningful progress. It moves from identifying architectural problems to proposing more explicit semantic models, validation algorithms, context ownership boundaries, and testable scenarios.&#x20;

Eingefügter Text.txt



However, I would not approve v0.4.2 as a frozen semantic specification or as implementation-ready architecture yet. The document resolves several earlier defects at the conceptual level, but its own algorithms and acceptance criteria still contain ambiguities and contradictions.

My principal-review decision is:

Accept as a research and design baseline

Do not freeze the formal specification yet

Next step: a focused v0.4.3 formal correction and validation package

The important distinction is that we are not trying to create the largest possible DDD model. We are trying to discover the smallest coherent, evidence-supported domain model that correctly represents organizational relationships, authority, access, and historical change.

The document itself acknowledges that organizational evidence has not yet been used to validate or falsify the candidate rules, and that the bounded contexts and implementation readiness remain unestablished.&#x20;

Eingefügter Text.txt



## 2. What v0.4.2 gets right

Five improvements should be preserved.

1. Fact knowledge, source availability, and freshness are separated. These describe different properties and should not be represented by one mutually exclusive status enum.
2. Transitions, corrections, and retractions are modeled separately. This is a sound starting point for retaining what was recorded while supporting later corrections.
3. Transition legality and sequence continuity are distinct checks. A transition can be individually legal but inconsistent with the preceding state.
4. Facts and decisions have different ownership. Membership and mandate facts should not be owned by a generic authorization evaluator; forum-specific access rules should not be confused with generic authorization mechanics.
5. Candidate rules are expected to become testable. The proposed membership and committee-authority slices are useful because they expose interactions that isolated entity diagrams would miss.

These are architectural foundations worth retaining. The remaining work is to make their semantics precise, prove the important properties, and validate the business rules against real organizational evidence.

## 3. Critical findings: defects that remain in v0.4.2

### Finding 1 — The fact-observation model still has semantic gaps

The proposed `FactObservation` separates value knowledge, source availability, and freshness, but the fallback function and its tests do not yet establish a complete, internally consistent contract.&#x20;

Eingefügter Text.txt



Problems to resolve

- Freshness depends on the evaluation time. The specification calculates freshness using `now - observationTime`. That is insufficient for historical decisions. For a decision evaluated as of time \\(t\\), freshness must be calculated relative to \\(t\\), not necessarily the current wall-clock time.
- `maxAge` and `maxStaleness` are not clearly reconciled. The model should define one unambiguous freshness contract, including any distinction between normal freshness and the maximum permitted fallback age.
- Authority and availability are different. An available source is not automatically authoritative for every fact. A cached value can remain available when the authoritative source is unreachable.
- `Unknown` and absence are still too broad. A missing database record is not necessarily proof that a membership does not exist. The source might not have been queried, the data might be incomplete, or the relationship might genuinely be absent.
- The fallback function returns too early. It accepts an available, fresh observation before evaluating `requireFreshAuthority`. The intended meaning of that flag must be defined, and every applicable mandatory condition must be enforced consistently.

I would replace the current interpretation with the following conceptual separation:

| Dimension         | Proposed representation                              | Question answered                                                  |
| ----------------- | ---------------------------------------------------- | ------------------------------------------------------------------ |
| Observed value    | Known true / known false / unknown                   | What value did we establish?                                       |
| Provenance        | Source ID, authority scope, source version           | Who established it, and under what authority?                      |
| Availability      | Available / unavailable                              | Can the source be reached now?                                     |
| Time              | Observed at, valid from/to, recorded at              | When was it observed, when did it apply, and when did we learn it? |
| Freshness         | Derived from policy and evaluation time              | Is this observation sufficiently recent for this use?              |
| Absence semantics | Explicitly defined absence or no reliable conclusion | What does the absence of a record actually mean?                   |

These should be related data, not a single status enumeration.

Principal-review decision: Keep the three-dimensional model, but do not approve the fallback algorithm until its combinations, authority rules, and time semantics are specified and tested.

### Finding 2 — The correction-lineage algorithm is not yet correct

The revised algorithm separates immutable transitions from correction and retraction records. That is a good direction, but the lineage resolution and sequence validation still have defects.&#x20;

Eingefügter Text.txt



The most important issues are:

1. Superseded corrections are still applied. The algorithm applies corrections in recording-time order, but it does not first establish which correction revisions are effective. Whether earlier corrections should be retained as cumulative patches or replaced by a later complete revision is not specified.
2. Branch detection is underspecified. Grouping corrections by `supersedesCorrectionId` is not enough to establish whether two revisions are genuinely conflicting. The model must define valid roots, successor relationships, branch semantics, and what constitutes a conflict.
3. Correction relationships are not fully validated. A correction that supersedes another correction must refer to the correct subject and transition, and the lineage must be checked for cycles, invalid references, and conflicting terminal revisions.
4. Retraction semantics need a complete contract. The model must decide whether a retracted transition can be reinstated, how duplicate retractions behave, and what happens when a correction targets a retracted transition.
5. Ordering can remain ambiguous. Sorting by `effectiveAt` and `version` is deterministic only if ties and version scope are defined. Two transitions with the same effective time cannot simply be ordered arbitrarily if that changes the business meaning.

There is also a subtle defect in the proposed sequence-validation function.

It initializes the current state from the first transition's own `fromState`, then checks whether that transition's `fromState` equals the current state. That first check is true by construction. It therefore does not establish that the initial state is valid.&#x20;

Eingefügter Text.txt



The solution is to define an explicit initial-state rule or an authoritative initial-state record. The first transition must be checked against that rule rather than against itself.

Required correction: Do not merely patch the pseudocode. Define the semantics first, then implement a small reference model independently of the production implementation.

### Finding 3 — Historical queries can be contaminated by future transitions

The proposed `stateOf(subjectId, v, k)` first obtains and validates the entire effective history known at transaction time \\(k\\), and only afterward filters transitions to those effective by valid time \\(v\\).&#x20;

Eingefügter Text.txt



That ordering creates a potential problem: an invalid transition effective after \\(v\\) could cause a historical query for an earlier, otherwise consistent point in time to return `InconsistentHistory`.

The revised contract should distinguish three operations:

1. Resolve the records and corrections known at transaction time \\(k\\).
2. Derive the effective transition timeline from those records.
3. Validate and evaluate the relevant timeline prefix up to valid time \\(v\\).

If the history is inconsistent before \\(v\\), the query must not silently guess a state. If the inconsistency begins after \\(v\\), the specification should explicitly state whether the earlier historical result remains available. My recommendation is to allow the earlier result when its relevant prefix is consistent, while exposing the later inconsistency separately.

The model also needs to define the result before the first transition: is the subject not yet created, in an initial state, or simply unknown? Returning `Unknown` for every empty applicable history is too coarse for all domain concepts.

Formal target:

\\[ \operatorname{StateAt}(s,v,k) \\]

must mean: the state of subject \\(s\\), effective at valid time \\(v\\), reconstructed using only records known by transaction time \\(k\\), under the approved correction and validation rules.

This definition is more important than the choice of event-store technology.

### Finding 4 — Some test expectations assume rules that have not been established

The document correctly labels its proposed ownership resolutions as hypotheses to validate. However, some later scenario acceptance criteria already treat particular business outcomes as established rules.&#x20;

Eingefügter Text.txt



For example, the membership slice assumes that suspending a membership in Organization A cannot affect a membership or appointment in Organization B.

That is a useful candidate isolation invariant, but it should not be universalized without checking the nature and scope of suspension. A local membership suspension is different from a separately defined organization-wide or cross-organization restriction.

Likewise, the committee-authority slice expects an action to be permitted before a mandate expires. But the document also correctly states that having a role does not itself establish permission. The test must therefore specify an applicable permission grant, policy, resource, scope, and all mandatory conditions before expecting a permit decision.&#x20;

Eingefügter Text.txt



I recommend using four explicit rule statuses throughout the validation matrix:

- Hypothesis: a plausible rule inferred from the research corpus.
- Evidence-supported: supported by an identified organizational source.
- Falsified or contested: a counterexample or conflicting source exists.
- Approved: the designated decision authority has accepted the rule and its scope.

A test passing proves that the implementation conforms to the test's assumptions. It does not prove that the assumptions are the correct organizational rules.

### Finding 5 — Ownership is clearer, but not yet validated

The proposed separation of Identity and Access, Membership, Organization, Governance, Geography, Authorization, Forum, and Audit is a useful working hypothesis.&#x20;

Eingefügter Text.txt



Before deriving bounded contexts, I would resolve the following questions:

| Boundary                                      | Decision still required                                                                                                             |
| --------------------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------- |
| Person ↔ Account ↔ Member ↔ Membership        | Is Person a shared identity across organizations, or can identity be scoped to an organization? Which facts establish membership?   |
| Organization ↔ OrganizationalUnit ↔ Committee | Is a Committee always a distinct governance body, or can some organizations model a committee as a specialized organizational unit? |
| Appointment ↔ Role ↔ Mandate                  | Which relationship establishes appointment, which defines the assigned function, and which limits its authority over time?          |
| Role ↔ Permission ↔ Authorization             | Who grants permissions, how are scopes represented, and which prohibitions override grants?                                         |
| Geography ↔ Jurisdiction ↔ Committee          | Who owns geographic boundaries, who assigns jurisdiction, and how are changes to either handled?                                    |
| Authorization ↔ Forum                         | Which access rules belong to the forum, and which general decision mechanics are shared?                                            |
| Audit ↔ Domain history                        | Which record proves a domain transition occurred, and which record proves that a change or decision was audited?                    |

One important refinement: geographic hierarchy and jurisdiction are not the same concept. A geographic district describes a place in a hierarchy. A committee's jurisdiction describes the area over which it has defined authority. The relationship between the two should be modeled explicitly, rather than inferred solely from their names.

Similarly, the proposed ownership rule that Audit owns records of changes must not accidentally make Audit the owner of the current membership or appointment state. We should also avoid assuming that using historical records requires adopting full event sourcing.

The proposed context-contract structure is a sound start, but it should eventually include commands, invariants, transaction boundaries, consistency guarantees, identifiers and scope, contract evolution, and failure behavior—not just ownership and event lists.

## 4. The next step: Cross-Relationship Architecture v0.4.3

I recommend a narrow, controlled next step rather than adding more domain concepts or immediately deriving a large set of bounded contexts.

v0.4.3 should be a formal correction and validation package, not a new architecture redesign.

## Work package A — Correct the formal semantics

Priority P0 · Before architecture freeze

- Finalize fact observation, provenance, absence, and freshness semantics.
- Define fallback behavior as a complete decision table and function.
- Define correction revision chains, conflict detection, retraction semantics, and deterministic ordering.
- Add an explicit initial-state contract and correct sequence validation.
- Define the bitemporal query semantics and the behavior of inconsistent history.
- Separate domain denial, missing facts, operational failure, and policy configuration errors.

## Work package B — Validate Group 1

Member ↔ Membership ↔ Organization

- Define the candidate rules for multiple organizational memberships and their scope.
- Test activation, suspension, termination, transfer, and retroactive correction.
- Test whether a local suspension affects another membership, and record any exception or broader restriction.
- Establish what Person, Account, Member, and Membership mean in actual organizational practice.
- Link every approved outcome to evidence, a rule identifier, and test cases.

## Work package C — Validate Group 2

Committee ↔ Appointment ↔ Role ↔ Mandate

- Define appointment, participation, role, mandate, permission grant, and authorization as distinct concepts.
- Test before and after mandate expiry, including an existing session.
- Test role revocation independently of membership status.
- Test wrong-committee scope, missing permission grants, mandatory prohibitions, and unavailable authoritative sources.
- Establish the required behavior for stale authority facts and failed history reconstruction.

## Work package D — Establish evidence and traceability

Research and governance

- Obtain at least one political-party constitution and one membership association's bylaws or statutes, as proposed in v0.4.2.&#x20;

  Eingefügter Text.txt

- Extract candidate rules while preserving source passages and provenance.

- Distinguish legal or constitutional requirements from organizational choices and technical constraints.

- Search for counterexamples and conflicting rules; do not collect only confirming examples.

- Populate the rule matrix with evidence quality, rule status, owner, invariant, test ID, and decision authority.

### How we should use mathematics, testing, and ML

The v0.4.2 techniques are appropriate, but each should have a defined purpose.&#x20;

Eingefügter Text.txt



| Technique                           | Next practical use                                                                                                           |
| ----------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- |
| Predicate logic                     | Express membership, appointment, jurisdiction, and access conditions unambiguously.                                          |
| Temporal logic                      | Verify expiry, revocation, and historical-state behavior.                                                                    |
| Property-based testing              | Generate many lifecycle sequences, including correction and retraction edge cases.                                           |
| Independent reference model         | Compare the implementation's history queries with a simpler formal model.                                                    |
| Constraint solving                  | Find contradictory rules, invalid scope combinations, and inconsistent policy configurations.                                |
| Model checking                      | Explore small but difficult sequences, such as concurrent corrections or revocation during an active session.                |
| NLP / LLM                           | Extract candidate rules from organizational documents while preserving citations and uncertainty.                            |
| Statistical analysis and clustering | Identify differences in terminology or patterns across organizations; use these as research signals, not as proof of a rule. |

ML must remain an assistance mechanism for discovery and review, not the authority that decides membership, appointment approval, mandate validity, policy precedence, or forum access.

The order matters: first establish the semantics, then build tests that can falsify them. Do not use an LLM's agreement with a rule as validation evidence.

## 5. How far are we from the implementation goal?

The attached document estimates progress at 45–50%, while acknowledging that the estimate is qualitative.&#x20;

Eingefügter Text.txt

&#x20;I would not treat that percentage as a measured completion figure: the corpus does not yet provide a verified denominator or objective completion gates.



My current assessment is:

| Dimension                                 | Assessment                                                                                                                       |
| ----------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------- |
| Conceptual foundation                     | Established as a research baseline. Core concepts, candidate relationships, and important scenarios are identified.              |
| Formal specification                      | In progress. The fact model and historical-correction model still need corrections.                                              |
| Organizational evidence                   | Not demonstrated. The candidate rules have not yet been validated against the required organizational sources.                   |
| Relationship ownership                    | Proposed, not approved. The major boundaries have hypotheses but need evidence and explicit decisions.                           |
| Validated bounded contexts and aggregates | Not established. Their boundaries should follow validated ownership, lifecycle, and transactional invariants.                    |
| Implementation readiness                  | Not established for this cross-domain architecture. Readiness requires accepted rules, acceptance criteria, and traceable tests. |

Overall conclusion: We have a substantial conceptual and research foundation, but we are still in the formalization and domain-validation stage—not yet at a defensible architecture freeze.

The most important milestone is not writing more code. It is demonstrating that the rules governing membership and committee authority are semantically correct, evidence-supported, internally consistent, and testable. Only then can we derive the smallest appropriate DDD architecture with confidence.

## 6. Remaining TODOs

### P0 — Formal correctness

- Correct the freshness and fallback function, including evaluation-time semantics and authority requirements.
- Define the absence and unknown-value semantics.
- Specify correction lineage, valid revision chains, branch conflicts, and retraction behavior.
- Fix sequence validation to use an explicit initial-state contract.
- Specify deterministic ordering and ambiguous-history handling.
- Correct bitemporal query behavior so the relevant historical timeline is validated appropriately.
- Build an independent reference model and tests for the formal semantics.

### P0 — Validate Member, Membership, and Organization

- Define Person, Account, Member, and Membership using real organizational scenarios.
- Establish membership identity and organization-scoping rules.
- Validate multi-organization membership and isolation of local suspension.
- Test activation, suspension, termination, transfer, and retroactive correction.
- Document exceptions, counterexamples, and unresolved questions.

### P0 — Validate Committee, Appointment, Role, and Mandate

- Define committee participation, appointment, role, mandate, permission grant, and authorization separately.
- Establish the rule for whether a mandate is required for each protected action.
- Test expiry, revocation, existing sessions, missing grants, and wrong-committee scope.
- Define the fail-safe behavior when authoritative facts cannot be retrieved or validated.

### P0 — Evidence and governance

- Obtain and review the proposed organizational source documents.
- Create a traceable rule matrix for R-01 through R-13.
- Record source evidence, counterexamples, evidence quality, and rule status.
- Assign the appropriate decision authority to each unresolved business, structural, temporal, and technical rule.
- Keep unsupported or contested rules explicitly open.

### P1 — Validate the remaining relationship groups

- Test Role ↔ Permission ↔ AuthorizationDecision.
- Test Geography ↔ OrganizationalUnit ↔ Committee.
- Test Forum ↔ Membership/Role ↔ Access.
- Define read, write, moderation, and administration access separately where the business requires it.
- Specify projection freshness, revocation guarantees, and behavior during source outages.
- Apply property-based testing, constraint solving, and selective model checking.

### P2 — Derive and review the DDD architecture

- Finalize authoritative ownership for every important fact.
- Derive bounded contexts from validated responsibilities and lifecycles.
- Derive aggregates from transactional invariants and concurrency requirements.
- Produce the context map and integration contracts.
- Define identity scope and tenant-isolation requirements.
- Review the proposed architecture against the existing implementation without assuming that existing code validates the business semantics.

### P3 — Implement incrementally

- Select the smallest validated vertical slice.
- Define acceptance criteria and traceability from each approved rule to code and tests.
- Implement domain and integration tests for temporal behavior, authorization, and organization isolation.
- Measure progress using completed validation gates and test evidence, rather than a subjective percentage.

## Final recommendation

Proceed with v0.4.3: Formal Corrections and Validation of Membership and Committee Authority.

Do not expand the architecture or begin a broad implementation phase yet. Correct the history and freshness semantics, validate Groups 1 and 2 against actual organizational evidence, and record the results in the rule matrix. After those gates pass, we can derive bounded contexts, aggregates, and implementation boundaries from the validated domain rather than from assumptions.

The goal is not to freeze the current corpus. It is to use it as a source of hypotheses, expose its contradictions, and converge on a robust architecture that can survive real organizational rules and edge cases.&#x20;
# ########################################
# Principal Review and Next-Step Execution: Cross-Relationship Architecture v0.4.3

## 1. Principal Reviewer Assessment

### 1.1 What This Review Contributes

The v0.4.2 review is the strongest and most precise artifact in the corpus. It does not merely accept v0.4.2; it subjects it to formal scrutiny and finds five defects, several of which are logically fatal to the proposed algorithms. This is what principal review is supposed to do.

The five findings are **genuine defects**, not stylistic disagreements:

1. **The fact-observation model still has semantic gaps.** Freshness is computed against wall-clock time, not the evaluation time — which breaks historical decisions. `maxAge` and `maxStaleness` are not reconciled. Provenance (who established the fact, under what authority) is missing. `Unknown` conflates multiple very different things. The fallback function returns early, potentially bypassing `requireFreshAuthority`.

2. **The correction-lineage algorithm is not yet correct.** Superseded corrections are still applied. Branch detection is underspecified. Correction relationships are not fully validated. Retraction semantics are incomplete. Ordering can remain ambiguous.

3. **Historical queries can be contaminated by future transitions.** The algorithm validates the entire effective history at transaction time `k`, then filters to valid time `v`. An inconsistency after `v` can incorrectly render an earlier, otherwise-consistent query as `InconsistentHistory`.

4. **Test expectations assume rules that have not been established.** The membership slice assumes suspension in A cannot affect B. The committee slice assumes that having a role is sufficient to permit an action. Both assumptions contradict the document's own principle that rules must be evidence-supported.

5. **Ownership is clearer but not validated.** The proposed ownership boundaries are hypotheses. Person vs. Account vs. Member vs. Membership, Committee vs. OrganizationalUnit, Appointment vs. Role vs. Mandate, Role vs. Permission, Geography vs. Jurisdiction, Authorization vs. Forum, Audit vs. Domain History — all remain open.

This review is accepted. It becomes the basis of v0.4.3.

### 1.2 Where I Strengthen the Review

The review is rigorous, but I identify six areas where it must be extended before v0.4.3 can be complete.

**Weakness 1 — The evaluation-time semantics are stated but not formalized.**
The review correctly states that freshness must be computed relative to evaluation time `t`, not wall-clock time. But it does not specify:
- How the evaluation time is determined for a historical query.
- How the freshness contract differs for real-time decisions vs. historical reconstruction.
- How evaluation time interacts with bi-temporal query parameters.

**Weakness 2 — The correction-lineage model is described but not specified.**
The review requires revision chains, conflict detection, retraction semantics, and deterministic ordering — but does not provide the formal model.

**Weakness 3 — The initial-state contract is required but not defined.**
The review correctly identifies the sequence-validation self-reference defect, but does not specify:
- How initial state is declared per fact type.
- How the system distinguishes "not yet created" from "unknown."
- How initial state interacts with corrections.

**Weakness 4 — The bitemporal query contract is stated but not formalized.**
The review correctly states the `StateAt(s, v, k)` semantic target but does not specify:
- How prefix validation works.
- What "relevant prefix" means operationally.
- How inconsistencies after `v` are surfaced without contaminating the earlier result.

**Weakness 5 — The rule-status vocabulary is proposed but not operationalized.**
The review proposes four statuses (Hypothesis, Evidence-supported, Falsified/Contested, Approved) but does not specify the transition rules between them.

**Weakness 6 — The independent reference model is required but not specified.**
The review correctly requires an independent reference model to validate the production implementation, but does not specify:
- What the reference model computes.
- What level of fidelity it must achieve.
- How discrepancies are resolved.

These weaknesses must be addressed in v0.4.3.

---

## 2. Next-Step Deliverable: Cross-Relationship Architecture v0.4.3

The next artifact is a **formal correction and validation package**, not a new architecture. It corrects the five defects, resolves the six weaknesses, and produces a testable semantic baseline. It does not derive bounded contexts, decide tenancy, or begin implementation.

### 2.1 Correction 1 — Fact Observation Model (Formalized with Evaluation-Time Semantics)

**Principle:** Freshness is a function of the evaluation time, not the wall-clock. Provenance is a first-class dimension. Absence is explicitly typed.

**Fact observation model:**

```
FactObservation {
  factId
  subjectId
  factType                    // membership_status, appointment_status, mandate_validity, ...
  organizationScope

  // Dimension 1: Value knowledge
  observedValue: KnownTrue | KnownFalse | Unknown

  // Dimension 2: Provenance
  provenance: {
    sourceId                  // authoritative source (e.g., "Membership", "Governance")
    authorityScope            // which organization/unit has authority to assert this fact
    sourceVersion             // version of the source record
    assertedAt                // when the source asserted the value
  }

  // Dimension 3: Availability
  sourceAvailability: Available | Unavailable

  // Dimension 4: Time
  validTime: { from, to }     // when the fact is/was true in reality
  observationTime             // when the source last confirmed the value
  recordedTime                // when the system recorded this observation

  // Dimension 5: Freshness policy
  freshnessPolicy: {
    normalFreshness           // age below which the value is considered fresh
    maxStaleness              // age beyond which the value must not be used
    allowedFallbackActions
    denyOnStale
    requireFreshAuthority
    evaluationTimeBasis       // "evaluation" | "wallclock"
  }

  // Dimension 6: Absence semantics
  absenceReason: NoRecord | NotApplicable | ExplicitAbsence | Unretrievable
}
```

**Evaluation-time semantics:**

The freshness of an observation is computed against the **evaluation time** `t`, not wall-clock time:

```
freshness(observation, t):
  age = t - observation.observationTime

  if age ≤ observation.freshnessPolicy.normalFreshness:
    return Fresh
  if age ≤ observation.freshnessPolicy.maxStaleness:
    return Stale
  return Expired
```

**Evaluation time determination:**

| Scenario | Evaluation time |
|---|---|
| Real-time authorization decision | `now()` |
| Historical query | The query's `k` (transaction time) |
| Projected decision (e.g., "what would have been allowed on date D") | Explicit `t` parameter |
| Batch reconciliation | The reconciliation timestamp |

**Absence semantics (extended):**

| `absenceReason` | Meaning | Example | Fallback |
|---|---|---|---|
| NoRecord | No record exists, source was queried | Person never applied for membership | Depends on policy; usually treat as KnownFalse |
| NotApplicable | Fact type does not apply to this subject | No committee exists, so no appointment can exist | Treat as KnownFalse |
| ExplicitAbsence | Business fact explicitly absent (e.g., application rejected) | Membership application explicitly rejected | Treat as KnownFalse |
| Unretrievable | Source could not be queried or returned an error | Network failure, timeout | Treat as Unknown; fallback per policy |

**Fallback decision table:**

| observedValue | sourceAvailability | freshness | `requireFreshAuthority` | action in `allowedFallbackActions` | `denyOnStale` | Result |
|---|---|---|---|---|---|---|
| KnownTrue/False | Available | Fresh | any | any | any | Use fact |
| KnownTrue/False | Available | Stale | false | yes | false | Use fact with warning |
| KnownTrue/False | Available | Stale | false | yes | true | Deny |
| KnownTrue/False | Available | Stale | true | any | any | Deny |
| KnownTrue/False | Unavailable | Fresh | false | yes | any | Use fact |
| KnownTrue/False | Unavailable | Fresh | true | any | any | Deny |
| KnownTrue/False | Unavailable | Stale | false | yes | false | Use fact |
| KnownTrue/False | Unavailable | Stale | any | no | any | Deny |
| KnownTrue/False | Unavailable | Expired | any | any | any | Deny |
| Unknown | Available | — | — | — | — | Use absenceReason |
| Unknown | Unavailable | — | — | — | — | Deny (fail-closed) |

**Required tests:**

| Test | Observed | Availability | Freshness | requireFreshAuthority | allowedAction | denyOnStale | Expected |
|---|---|---|---|---|---|---|---|
| F-T1 | KnownTrue | Available | Fresh | — | — | — | Use fact |
| F-T2 | KnownTrue | Available | Stale | false | yes | false | Use fact with warning |
| F-T3 | KnownTrue | Available | Stale | false | yes | true | Deny |
| F-T4 | KnownTrue | Available | Stale | true | — | — | Deny |
| F-T5 | KnownTrue | Unavailable | Fresh | false | yes | — | Use fact |
| F-T6 | KnownTrue | Unavailable | Fresh | true | — | — | Deny |
| F-T7 | KnownTrue | Unavailable | Stale | false | yes | false | Use fact |
| F-T8 | KnownTrue | Unavailable | Stale | — | no | — | Deny |
| F-T9 | KnownTrue | Unavailable | Expired | — | — | — | Deny |
| F-T10 | Unknown | Available | — | — | — | — | Use absenceReason |
| F-T11 | Unknown | Unavailable | — | — | — | — | Deny |
| F-T12 | KnownTrue | Available | Stale (historical eval time) | — | — | — | Fresh at historical time |

### 2.2 Correction 2 — Correction-Lineage Model (Formalized)

**Principle:** Transitions are immutable records. Corrections and retractions are separate records that form explicit revision lineages. Conflicting lineages are detected, not silently resolved.

**Record types:**

```
TransitionRecord {
  id
  subjectId
  fromState
  toState
  effectiveAt
  recordedAt
  authority
  reason
  version
}

CorrectionRecord {
  id
  subjectId
  targetTransitionId
  correctionType: effectiveAt | toState | fromState | full
  newValues
  recordedAt
  authority
  reason
  supersedesCorrectionId (nullable)  // if this corrects another correction
}

RetractionRecord {
  id
  subjectId
  targetTransitionId
  recordedAt
  authority
  reason
}
```

**Revision lineage:**

```
lineage(corrections):
  graph = {}
  for c in corrections:
    graph[c.id] = { record: c, supersedes: c.supersedesCorrectionId, successors: [] }
  for c in corrections:
    if c.supersedesCorrectionId:
      graph[c.supersedesCorrectionId].successors.append(c.id)
  return graph
```

**Lineage validity rules:**

| Rule | Statement |
|---|---|
| LV-1 | Every `supersedesCorrectionId` references an existing correction |
| LV-2 | Every correction targets an existing transition |
| LV-3 | The lineage graph is acyclic |
| LV-4 | For a given transition, the set of corrections forms a **single revision lineage**: at most one "root" correction (no `supersedesCorrectionId`) and each correction has at most one successor |
| LV-5 | A correction cannot target a retracted transition |
| LV-6 | Retractions cannot target retractions |

**Effective correction selection:**

```
effectiveCorrection(transitionId, corrections, k):
  chain = { c ∈ corrections
            | c.targetTransitionId = transitionId
            ∧ c.recordedAt ≤ k }
  if chain = ∅: return null
  graph = lineage(chain)
  roots = { c ∈ chain | c.supersedesCorrectionId = null }
  if |roots| ≠ 1: return AmbiguousLineage(reason="multiple roots")
  lineageChain = traverse(graph, roots[0])
  terminal = lineageChain.last()
  if |graph[terminal].successors| > 0:
    return AmbiguousLineage(reason="multiple terminal revisions")
  return terminal
```

**Effective history algorithm (revised):**

```
effectiveHistory(subjectId, k):
  # Step 1: Collect records known at transaction time k
  transitions = { t | t.subjectId = subjectId ∧ t.recordedAt ≤ k }
  corrections = { c | c.subjectId = subjectId ∧ c.recordedAt ≤ k }
  retractions = { r | r.subjectId = subjectId ∧ r.recordedAt ≤ k }

  # Step 2: Validate lineage
  lineageCheck = validateLineage(corrections, transitions, retractions)
  if lineageCheck.status == invalid:
    return InconsistentHistory(reason=lineageCheck.reason)

  # Step 3: Determine effective corrections
  effectiveCorrections = {}
  for t in transitions:
    c = effectiveCorrection(t.id, corrections, k)
    if c is AmbiguousLineage:
      return InconsistentHistory(reason="ambiguous correction lineage")
    if c: effectiveCorrections[t.id] = c

  # Step 4: Apply corrections
  effective = {}
  for t in transitions:
    effective[t.id] = applyCorrection(t, effectiveCorrections.get(t.id))

  # Step 5: Apply retractions (retracted transitions removed)
  for r in retractions:
    if r.targetTransitionId ∈ effective:
      remove(effective, r.targetTransitionId)

  # Step 6: Order and validate sequence
  ordered = sort(values(effective), by=(effectiveAt, version))
  validation = validateSequence(ordered)

  return { ordered, validation, inconsistencies: collectInconsistencies(validation) }
```

**Retraction semantics:**

| Question | Decision |
|---|---|
| Can a retracted transition be reinstated? | No — a new transition must be recorded |
| Can a retraction be retracted? | No — a new transition must be recorded |
| What if a correction targets a retracted transition? | Rejected — correction is invalid |
| What if a transition is retracted after a correction applies? | The correction becomes orphaned; flagged as inconsistency |

**Deterministic ordering:**

Sorting by `(effectiveAt, version)` is deterministic only if `version` is scoped to a single `subjectId`. The version is monotonic per subject, so ties are resolved by version. If two records have identical `(effectiveAt, version)` (which should not happen), the system flags an inconsistency.

### 2.3 Correction 3 — Sequence Validation with Initial-State Contract

**Principle:** The initial state of a subject must be declared, not inferred from the first transition. Transition legality and sequence continuity are validated separately.

**Initial-state contract:**

```
InitialState {
  subjectId
  subjectType             // membership, appointment, mandate, ...
  initialState            // the state before any transition
  declaredAt
  authority
}
```

**Initial state rules:**

| Subject Type | Initial State |
|---|---|
| Membership | NotCreated |
| Appointment | NotCreated |
| Mandate | NotCreated |
| Committee | NotCreated |
| OrganizationalUnit | NotCreated |
| Role | NotCreated |

**Note:** `NotCreated` is distinct from `Unknown`. A subject that has never existed is `NotCreated`. A subject whose history cannot be reconstructed is `Unknown`.

**Sequence validation:**

```
validateSequence(orderedTransitions, initialState):
  if orderedTransitions = ∅:
    return { status: valid, state: initialState }

  # Check 1: Transition legality
  for t in orderedTransitions:
    if not isValidTransition(t.fromState, t.toState):
      return { status: invalid, reason: "illegal transition", invalid: [t] }

  # Check 2: Sequence continuity
  currentState = initialState
  for t in orderedTransitions:
    if t.fromState ≠ currentState:
      return {
        status: invalid,
        reason: "sequence discontinuity",
        invalid: [t],
        expectedFromState: currentState,
        actualFromState: t.fromState
      }
    currentState = t.toState

  return { status: valid, finalState: currentState }
```

**Key correction:** The sequence starts from `initialState`, not from the first transition's `fromState`. The first transition's `fromState` must match the declared initial state.

### 2.4 Correction 4 — Bitemporal Query Semantics (Prefix Validation)

**Principle:** Historical queries validate the relevant timeline prefix, not the entire history. Inconsistencies after `v` are surfaced separately.

**Formal semantics:**

$$\operatorname{StateAt}(s, v, k) = \text{the state of } s \text{ at valid time } v \text{, reconstructed from records known by } k \text{, using approved correction and validation rules}$$

**Algorithm:**

```
StateAt(subjectId, v, k):
  # Step 1: Resolve records known at k
  history = effectiveHistory(subjectId, k)

  # Step 2: Filter to valid time ≤ v
  prefix = { t ∈ history.ordered | t.effectiveAt ≤ v }

  # Step 3: Validate prefix
  prefixValidation = validateSequence(prefix, initialState(subjectId))

  # Step 4: Return result
  if prefixValidation.status == invalid:
    return InconsistentHistory(reason=prefixValidation.reason, invalid=prefixValidation.invalid)

  if prefixValidation.finalState == NotCreated:
    return { state: NotCreated, note: "subject did not yet exist at valid time v" }

  return {
    state: prefixValidation.finalState,
    validAt: v,
    knownAt: k,
    laterInconsistencies: history.inconsistencies filtered to effectiveAt > v
  }
```

**Key semantics:**

| Case | Behavior |
|---|---|
| No transitions before `v` | Return `NotCreated` |
| Consistent prefix before `v` | Return prefix state |
| Inconsistent prefix before `v` | Return `InconsistentHistory` |
| Inconsistency after `v` | Return prefix state + surface `laterInconsistencies` |
| No records at all | Return `NotCreated` if initialState is `NotCreated`; otherwise `Unknown` |

**Result model:**

```
StateResult {
  state: State | NotCreated | Unknown
  validAt: timestamp
  knownAt: timestamp
  laterInconsistencies: [InconsistencyRecord]  // after v, if any
  reason: string  // if InconsistentHistory
}
```

### 2.5 Correction 5 — Rule Status Vocabulary (Operationalized)

The review proposes four statuses. I operationalize them with transition rules.

**Rule status vocabulary:**

| Status | Meaning | Evidence Required | Transition |
|---|---|---|---|
| Hypothesis | Plausible rule inferred from corpus | None | → Evidence-supported if source found |
| Evidence-supported | Supported by identified organizational source | Source document with citation | → Approved if ARB decides; → Falsified if counterexample |
| Falsified/Contested | Counterexample or conflicting source exists | Counterexample with evidence | → Hypothesis if counterexample rejected; → Approved if scope narrowed and counterexample no longer applies |
| Approved | Designated decision authority accepted rule and scope | ARB decision record | → Falsified if later counterexample |

**Transition rules:**

```
Hypothesis → Evidence-supported: source document found
Hypothesis → Falsified: counterexample found without source support
Evidence-supported → Approved: ARB decision recorded
Evidence-supported → Falsified: counterexample found
Falsified → Hypothesis: counterexample rejected after review
Falsified → Approved: counterexample scoped out and ARB approves
Approved → Falsified: new counterexample found (requires re-review)
```

**Key principles:**

1. **A passing test does not promote a rule.** Tests verify implementation conformance; they do not establish business correctness.
2. **A rule may be Approved with limited scope.** Approval includes the domain of applicability.
3. **Falsification is reversible.** A falsified rule may return to Hypothesis if the counterexample is rejected.

### 2.6 Correction 6 — Independent Reference Model

**Principle:** The production implementation must be validated against an independently derived reference model. Discrepancies are investigated, not silently reconciled.

**Reference model scope:**

The reference model implements the same formal semantics as the production system, but:
- In a different language or framework.
- With simpler data structures (e.g., lists instead of event stores).
- Without optimization.
- With explicit, readable logic.

**Reference model coverage:**

| Area | Reference Model Behavior |
|---|---|
| Fact observation | Compute freshness, availability, absence semantics |
| Fallback eligibility | Apply decision table |
| Correction lineage | Resolve effective corrections |
| Retraction semantics | Apply retractions |
| Sequence validation | Validate transition legality and continuity |
| Bitemporal query | Compute `StateAt(s, v, k)` |

**Discrepancy resolution:**

| Discrepancy Type | Resolution |
|---|---|
| Production bug | Fix production; add regression test |
| Reference model bug | Fix reference model; document |
| Semantic ambiguity | Escalate to ARB; refine semantics |
| Test case error | Fix test; document |

**Property-based testing against reference model:**

Generate random sequences of transitions, corrections, retractions, and query times. For each sequence:
1. Compute the production result.
2. Compute the reference model result.
3. Assert equality.

**Properties to test:**

| Property | Statement |
|---|---|
| P-REPRO | Same inputs → same outputs (both models) |
| P-CORR | Corrections preserve prior records |
| P-RETRACT | Retractions remove transitions from effective history |
| P-PREFIX | Prefix validation is independent of later inconsistencies |
| P-DET | Deterministic ordering for identical (effectiveAt, version) pairs |
| P-FALLBACK | Fallback follows the decision table |

### 2.7 Validation Slices (Corrected)

The review correctly identifies that the slices assume unestablished rules. I correct them.

**Slice 1 — Membership Semantics (Corrected)**

**Scenario:** An individual belongs to Organization A and Organization B. Membership in A is suspended; membership in B remains active. They have an appointment in a committee of Organization B.

**Corrected expectations:**

| Question | Expected Behavior | Status |
|---|---|---|
| Which identity is shared across organizations? | Person identity (pending resolution) | **Hypothesis** |
| Are the two memberships separate relationships? | Yes (candidate) | **Hypothesis** |
| Does suspending one affect the other? | Depends on suspension scope (local vs. organization-wide) | **Open** |
| Does suspension affect appointment in B? | No, if suspension is local to A | **Hypothesis** |
| Does suspension affect appointment in A (if any)? | Depends on appointment's membership requirement | **Open** |
| What should historical queries return? | State as known at each transaction time | **Testable** |

**Key correction:** Suspension scope is not assumed. The test must distinguish:
- Local suspension (affects only the suspended membership).
- Organization-wide suspension (affects all relationships in that organization).
- Cross-organization suspension (affects multiple organizations — rare, requires explicit policy).

**Slice 2 — Committee Authority (Corrected)**

**Scenario:** A member has an active membership, an active committee appointment, a role with defined scope, and a mandate expiring at a specified time.

**Corrected expectations:**

| Test | Scenario | Expected | Precondition |
|---|---|---|---|
| CA-T1 | Before mandate expiry, applicable permit grant exists | Permit | Grant exists, mandatory conditions satisfied |
| CA-T2 | Before mandate expiry, no applicable grant | Deny | No grant |
| CA-T3 | After mandate expiry | Deny | Mandate expired |
| CA-T4 | After mandate expiry, source unavailable | Deny | `requireFreshAuthority=true` |
| CA-T5 | After mandate expiry, existing session | Deny | Authorization re-evaluated |
| CA-T6 | Role exists, no permission grant | Deny | No grant |
| CA-T7 | Role exists, permission granted for another committee | Deny | Scope mismatch |
| CA-T8 | Mandatory prohibition applies | Deny | Prohibition active |
| CA-T9 | Stale authority fact | Deny | Freshness violated |

**Key correction:** A permit requires an applicable grant, not merely a role. The test must specify the grant.

### 2.8 Mathematics, Statistics, and Machine Learning (v0.4.3 Scope)

| Technique | Application | Purpose | Oversight |
|---|---|---|---|
| Predicate logic | Formalize membership, appointment, jurisdiction, access conditions | Remove ambiguity | Manual specification |
| Temporal logic | Specify expiry, revocation, historical reconstruction | Verify temporal properties | Manual specification |
| Property-based testing | Generate lifecycle sequences with corrections and retractions | Find edge cases | Automated with generators |
| Reference model comparison | Validate production against independently-derived model | Detect implementation errors | Automated |
| Constraint solving | Detect contradictory policies | Prevent configuration errors | Automated |
| Model checking | Verify bounded lifecycle properties | Prove safety | Selective |
| NLP / LLM | Extract candidate rules from organizational documents | Assist research | Human verification required |
| Clustering | Identify different meanings behind similar terms | Assist research | Human verification required |
| Statistical analysis | Compare patterns across organizations | Signal only | Human interpretation |

**ML must never decide:**
- Membership status
- Appointment approval
- Mandate validity
- Policy precedence
- Forum access
- Authorization outcomes

ML generates hypotheses. Humans and formal logic verify.

---

## 3. How Far We Are from the Implementation Goal

### 3.1 Maturity Assessment

| Dimension | Status |
|---|---|
| Conceptual foundation | Established as research baseline |
| Formal specification | In progress — fact model and correction model require further correction (v0.4.3) |
| Organizational evidence | Not demonstrated — requires source documents |
| Relationship ownership | Proposed, not approved |
| Validated bounded contexts and aggregates | Not established |
| Implementation readiness | Not established for cross-domain architecture |

**Overall assessment:** We are approximately **45–50%** of the way toward an implementable organizational domain model. This is a qualitative estimate. The next measurable milestone is validating Groups 1 and 2 with evidence and passing tests against an independent reference model.

The most important milestone is not writing code. It is demonstrating that the rules governing membership and committee authority are:
- Semantically correct.
- Evidence-supported.
- Internally consistent.
- Testable.

### 3.2 Remaining TODOs (Bullet Points)

**P0 — Formal correctness (v0.4.3)**
- Adopt the fact observation model with evaluation-time freshness semantics.
- Reconcile `normalFreshness` and `maxStaleness`.
- Add provenance as a first-class dimension.
- Distinguish `NoRecord`, `NotApplicable`, `ExplicitAbsence`, `Unretrievable`.
- Adopt the fallback decision table; enforce consistently.
- Adopt the correction-lineage model with revision chains and conflict detection.
- Define retraction semantics including reinstatement rules.
- Define deterministic ordering and ambiguity handling.
- Adopt the initial-state contract; correct sequence validation.
- Adopt the bitemporal query with prefix validation.
- Distinguish domain denial, missing facts, operational failure, policy configuration error.
- Build an independent reference model and property-based tests.

**P0 — Validate Group 1 (Member, Membership, Organization)**
- Define Person, Account, Member, Membership using real scenarios.
- Establish membership identity and organization-scoping rules.
- Test multi-organization membership.
- Test suspension scope (local, organization-wide, cross-organization).
- Test activation, termination, transfer, retroactive correction.
- Document counterexamples and unresolved questions.
- Link every outcome to evidence, rule ID, and test.

**P0 — Validate Group 2 (Committee, Appointment, Role, Mandate)**
- Define appointment, participation, role, mandate, permission grant, authorization separately.
- Establish whether a mandate is required for each protected action.
- Test expiry, revocation, existing sessions, missing grants, wrong-committee scope.
- Test mandatory prohibitions and unavailable authoritative sources.
- Define fail-safe behavior for stale authority facts.
- Link every outcome to evidence, rule ID, and test.

**P0 — Evidence and governance**
- Obtain one political party constitution and one membership association bylaws.
- Extract candidate rules with source citations.
- Distinguish legal, constitutional, organizational, and technical rules.
- Search for counterexamples; do not collect only confirming examples.
- Populate the rule matrix with evidence quality, status, owner, invariant, test ID, decision authority.
- Apply the four-status vocabulary (Hypothesis, Evidence-supported, Falsified/Contested, Approved).
- Assign decision authority for structural, temporal, business, legal, operational rules.

**P1 — Validate remaining relationship groups**
- Test Group 3: Role ↔ Permission ↔ AuthorizationDecision.
- Test Group 4: Geography ↔ OrganizationalUnit ↔ Committee.
- Test Group 5: Forum ↔ Membership/Role ↔ Access.
- Define read, write, moderation, administration access separately.
- Specify projection freshness, revocation, behavior during outages.
- Apply property-based testing, constraint solving, selective model checking.

**P2 — Derive and review DDD architecture**
- Finalize authoritative ownership for every fact.
- Derive bounded contexts from validated responsibilities and lifecycles.
- Derive aggregates from transactional invariants and concurrency.
- Produce context map and integration contracts.
- Define identity scope and tenant isolation.
- Review architecture against existing implementation without assuming existing code validates business semantics.

**P3 — Implement incrementally**
- Select smallest validated vertical slice.
- Define acceptance criteria with traceability from rule to code and test.
- Implement domain and integration tests for temporal, authorization, isolation behavior.
- Measure progress using validated gates, not subjective percentages.

### 3.3 Final Recommendation

The next step is v0.4.3: Formal Corrections and Validation of Membership and Committee Authority.

The **most urgent technical issue** is the correction-lineage algorithm and the bitemporal prefix validation. The **most urgent DDD issue** is resolving the Person/Account/Member/Membership boundary and validating the suspension-scope semantics.

Do not derive bounded contexts or begin broad implementation until:
1. The formal semantics are corrected.
2. Groups 1 and 2 are validated against real organizational evidence.
3. The rule matrix is populated with evidence quality and decision status.
4. The independent reference model validates the production algorithms.

The goal is not to freeze the current corpus. It is to use it as a source of hypotheses, expose its contradictions, and converge on a robust architecture that survives real organizational rules and edge cases.

**Authorization required:** Provision of one political party constitution and one membership association bylaws to begin evidence collection and falsification of Groups 1 and 2.