# Principal DDD review: Cross-Relationship Architecture v0.4.1

## 1. Executive assessment

I have reviewed the attached Cross-Relationship Architecture Baseline v0.4.1. It is an important step forward: it introduces provisional context contracts, corrects the earlier monotonicity claim, separates organizational facts from authorization decisions, and prioritizes five relationship groups for validation.&#x20;

Eingefügter Text.txt



However, v0.4.1 is not yet ready to be frozen as a formal architecture baseline. Some of its proposed corrections contain further logical inconsistencies. We should resolve those now, before translating the model into database tables, aggregates, APIs or services.

Our goal remains a practical digitalization platform for organizations and political parties. The current scope is exclusively the DDD architecture for:

- Member, Membership and Organization
- Committee, participation, appointment, role and mandate
- Permissions and authorization
- Geographical structures and committee jurisdiction
- Discussion forums and forum access
- Cross-domain ownership, temporal history and integration

Election implementation remains out of scope. We should not introduce voter-roll, election-eligibility or election-workflow concepts into this phase.

My recommendation is to advance to a v0.4.2 semantic-correction and contract-validation step, then execute the validation matrix. This is more useful than expanding the glossary or prematurely creating bounded contexts.

## 2. Principal findings: four corrections still required

### Finding 1 — Fact status mixes two independent dimensions

The v0.4.1 model defines `True`, `False`, `Unknown`, `Unavailable` and `Stale` as values of one `FactStatus` field. It then applies fallback rules to `Unavailable` and `Stale`.&#x20;

Eingefügter Text.txt



This mixes different questions:

- What value is known?
- Could the authoritative source be reached?
- How old is the observation?

These are not mutually exclusive states. A source may be unavailable while a cached value is still fresh, or available while returning a value that is too old for the requested action.

I recommend separating the dimensions.

### Proposed fact-observation contract

1\. Value knowledge

- `KnownTrue`
- `KnownFalse`
- `Unknown`

2\. Source availability

- `Available`
- `Unavailable`

3\. Observation freshness

- `Fresh`
- `Stale`
- Derived from the observation time and the applicable freshness contract

These dimensions describe the knowledge available to the decision process. They should not be mistaken for membership or appointment lifecycle states.

For example, a cached appointment may have a known value, an unavailable source and an expired freshness window. The decision process must consider all three facts rather than treating `Unavailable` as sufficient to determine fallback behavior.

We also need to distinguish an explicit business fact such as “no appointment exists” from an inability to retrieve appointment data. One is a domain result; the other is an operational failure.

Required correction: define a fact-observation contract with source, observation time, valid time where relevant, freshness-policy version, and explicit semantics for absence of a record. Apply the same freshness limit consistently to every fallback path.

### Finding 2 — The correction-aware event algorithm still fails for correction chains

The proposed algorithm builds a set of superseded events and removes them before applying corrections. It subsequently looks for the correction target in that already-filtered set. If the correction identifies the original event as superseded, the target may already have been removed and the algorithm can incorrectly report an unknown target.&#x20;

Eingefügter Text.txt



Retractions have a related problem: the original transition can be removed during initial filtering, leaving nothing for the retraction step to retract.

This is not merely a coding detail. It affects the meaning of historical organizational facts.

Recommended correction: separate immutable transition records from the corrections and retractions that change how those records are interpreted.

A transition record represents what was originally recorded. A correction references that record and contains an explicit amendment. A retraction invalidates a transition without deleting its history.

The algorithm should then:

1. Select the records and corrections known at the requested transaction time.
2. Validate correction targets, authority and revision relationships.
3. Resolve the correction chain deterministically.
4. Derive the effective transitions for the requested historical view.
5. Validate the complete transition sequence.
6. Return the derived state only if the sequence is consistent; otherwise return an explicit inconsistency result.

For correction chains, we should require a single valid revision lineage or explicitly detect conflicting branches. A correction must not silently overwrite another correction.

### Finding 3 — Deterministic ordering does not guarantee a valid state

The document correctly states that ordering simultaneous transitions by version number does not prove business validity. However, its downstream validation pseudocode still needs strengthening. It compares the current state with each transition's target state but does not explicitly require the transition's `fromState` to equal the current state.&#x20;

Eingefügter Text.txt



Consider these transitions:

- `Pending → Active`
- `Suspended → Terminated`

If the first transition produces `Active`, the second cannot simply be accepted because it has a valid target state. Its starting state does not match the current state.

We need two separate validations:

- Transition validity: is this transition permitted by the relevant state machine?
- Sequence validity: is its `fromState` consistent with the state produced by preceding transitions?

If either fails, the system must flag the history as inconsistent rather than silently returning the last `toState`.

The same principle applies to membership, committee appointments, mandates and role assignments.

The desired properties are:

- Historical reproducibility for fixed valid time, transaction time, retained history and rule version.
- Correction awareness: later corrections can change the reconstructed state for an earlier valid time.
- Sequence consistency: valid transitions form a legal lifecycle.
- Explicit handling of ambiguous or invalid histories.

We should not claim that the derived state is always available merely because the event log can be sorted.

### Finding 4 — The proposed ownership contracts contain unresolved overlaps

The ownership table is valuable, but some entries still need semantic clarification before they become contracts.&#x20;

Eingefügter Text.txt



| Unresolved boundary            | Principal-review concern                                                                                                       |
| ------------------------------ | ------------------------------------------------------------------------------------------------------------------------------ |
| Identity & Access ↔ Membership | A login account, a person and an organization's recognized member are different concepts. Their relationship must be explicit. |
| Organization ↔ Governance      | If a committee is also an organizational unit, we must avoid two contexts independently owning the same identity or structure. |
| Governance ↔ Authorization     | A role or mandate may provide facts relevant to authority, but authorization evaluates whether an action is permitted.         |
| Authorization ↔ Forum          | A generic authorization evaluator and a forum-specific access policy are not the same responsibility.                          |
| Audit ↔ Domain contexts        | An audit record is not automatically the authoritative historical state of membership, appointments or jurisdiction.           |
| Geography ↔ Governance         | Jurisdiction assignments may refer to committees, but that does not make Geography the owner of committee composition.         |

These boundaries should be decided from the concepts' meaning, lifecycle and invariants—not merely from a preference for separate services.

In particular, we must not create two authoritative owners for the same fact. One context can publish a fact and another can maintain a derived projection, but the contract must distinguish the source of truth from the projection.

## 3. Proposed v0.4.2: semantic-correction baseline

The next step should produce a small, testable contract for each of the five relationship groups. I recommend the following ownership and responsibility model as a hypothesis to validate—not yet an approved bounded-context map.

| Candidate responsibility | Owns                                                                                       | Must not silently take over                                  |
| ------------------------ | ------------------------------------------------------------------------------------------ | ------------------------------------------------------------ |
| Identity                 | Account and authentication identity; person-identity semantics still require clarification | Membership status or committee authority                     |
| Organization             | Organization identity and organizational structure                                         | Membership lifecycle or appointment decisions                |
| Membership               | Membership relationship, type and lifecycle                                                | Person-wide identity or committee appointment                |
| Governance               | Committee composition, appointments, roles and mandates, subject to validating the model   | Membership lifecycle or generic authorization decisions      |
| Geography                | Geographical entities and jurisdiction relationships                                       | Committee composition or organizational hierarchy            |
| Authorization            | Evaluation of applicable rules against authoritative facts                                 | Ownership of membership, appointment or jurisdiction facts   |
| Forum                    | Discussion content, forum lifecycle and forum-specific access policy                       | Ownership of membership or committee authority               |
| Audit                    | Audit evidence and recorded change history                                                 | Becoming the sole authority for every domain's current state |

Two points deserve special attention.

First, the distinction between `Person`, `Account`, `Member` and `Membership` is still unresolved. We should not assume that every person has an account, that every account represents a single natural person, or that membership itself is a global identity.

Second, a committee may be modeled as a governance body associated with an organizational unit, or as a specialized kind of organizational unit. We should compare real cases before deciding. The model must represent committee-specific composition and authority without duplicating organizational identity.

### 3.1 Proposed contract between domains

Each context contract should include more than `owns`, `publishes` and `consumes`. It should specify:

- The fact or decision the context owns.
- The identifier and organizational scope of that fact.
- The events or queries it exposes.
- The meaning of each published event, including effective time where applicable.
- Schema and contract versioning.
- The freshness and consistency guarantees of any projection.
- The behavior expected when a source is unavailable or a contract is incompatible.
- The facts and decisions that the context explicitly does not own.

For example, Membership might publish `MembershipSuspended` with the membership identifier, organization identifier, effective time and event version. Governance and Forum may consume that event to update projections, but neither becomes the owner of membership status.

For access revocation, event-driven updates alone may not be sufficient if a stale projection could permit an action that should now be denied. We must define an acceptable revocation delay and decide when an authoritative query is required.

This is how we turn DDD boundaries into enforceable contracts rather than boxes in a diagram.

## 4. The first practical validation slice

I recommend starting with Group 1: Member ↔ Membership ↔ Organization, followed by Group 2: Committee ↔ Appointment ↔ Role ↔ Mandate. These provide the authoritative facts on which permissions and forum access depend. This sequencing is also consistent with the attached v0.4.1 recommendation.&#x20;

Eingefügter Text.txt



### Slice 1 — Membership semantics

Use a deliberately small scenario:

An individual belongs to Organization A and Organization B. Their membership in Organization A is suspended, but their membership in Organization B remains active. They also have an appointment in a committee of Organization B.

We should be able to answer:

- Which identity is shared across the two organizations?
- Are the two memberships separate domain relationships?
- Does suspending one membership affect the other?
- Does the suspension affect the committee appointment in Organization A, Organization B, both or neither?
- Which rule determines the answer?
- What should historical queries return before and after the suspension was recorded?
- Which events and tests demonstrate that the behavior is correct?

We should not decide the answer from intuition alone. The intended platform contract and representative organizational rules must establish the expected behavior.

### Slice 2 — Committee authority

Use a second scenario:

A member has an active membership, an active committee appointment, a role with a defined scope, and a mandate that expires at a specified time.

Test the system immediately before and after expiry, including an unavailable authority source and an existing session.

The model must distinguish:

- Membership remains active.
- Committee participation may remain recorded.
- Appointment and mandate validity are evaluated separately.
- A role assignment does not automatically establish permission for every action.
- Authorization evaluates the action, resource, scope, policy and applicable facts at the decision time.

The output should be an explicit domain contract and a set of acceptance tests—not production code before the semantics are validated.

### 4.1 Acceptance criteria for both slices

A slice is ready to be considered validated only when:

1. The concepts and relationships have precise meanings.
2. Each authoritative fact has a defined owner.
3. The business rule is supported by evidence or explicitly remains a hypothesis.
4. Positive, negative, boundary and correction cases have been evaluated.
5. Temporal behavior is deterministic and correction-aware.
6. The expected authorization outcome is explainable.
7. The matrix links the rule to its evidence, owner, test and decision.
8. Unresolved questions are recorded rather than hidden in implementation.

## 5. How formal methods and machine learning should support this work

We should use these techniques selectively, where they reduce a specific architectural risk.

| Technique              | Next practical use                                                    | Expected result                                                                      |
| ---------------------- | --------------------------------------------------------------------- | ------------------------------------------------------------------------------------ |
| Predicate logic        | Formalize membership, appointment, jurisdiction and access conditions | Unambiguous, testable propositions                                                   |
| Temporal logic         | Check expiry, revocation and historical queries                       | Correct behavior across time boundaries                                              |
| Property-based testing | Generate combinations of memberships, appointments, roles and scopes  | Discovery of unexpected relationship combinations                                    |
| Constraint solving     | Find contradictory policy configurations                              | Explicit conflict reports                                                            |
| Model checking         | Explore lifecycle and correction sequences                            | Detection of invalid or unreachable states                                           |
| NLP / LLM              | Extract candidate rules from bylaws and policies                      | Traceable research hypotheses, verified by humans                                    |
| Clustering             | Compare organizational terms and relationship patterns                | Identification of meaningful variations                                              |
| Statistical analysis   | Compare observations across organizations                             | Evidence about recurring patterns, without claiming universality from a small sample |

One particularly valuable test is a generated sequence of lifecycle events: activation, suspension, correction, reactivation and termination. We can compare the implementation's historical query results with a simple, independently defined reference model.

For authorization, we should test a property such as:

\\[ \text{Permit} \Rightarrow \text{ApplicableGrant} \land \text{AllMandatoryConditionsSatisfied} \land \neg\text{ApplicableMandatoryProhibition} \\]

This is a candidate safety invariant; the full predicate must include the approved policy semantics and the fact-freshness contract.

ML must not decide membership status, appointment approval, mandate validity, policy precedence or forum access. Its role is to help discover candidate rules, contradictions and unusual cases for human review.

## 6. How far are we from the implementation goal?

The attached document estimates 45–50% progress toward an implementable organizational domain model, but explicitly treats this as a qualitative estimate.&#x20;

Eingefügter Text.txt



My assessment is:

## Current maturity

Conceptual foundation

Candidate concepts, relationship distinctions and validation scenarios established.

Formal specification

Partially complete. Fact freshness, correction chains, transition consistency and context contracts need further refinement.

Evidence and falsification

Not yet demonstrated by the current corpus. The candidate rules still require validation against organizational evidence and practical scenarios.

Validated DDD architecture and implementation readiness

Not established. Context ownership, boundaries and implementation acceptance criteria remain open.

I would not assign a new percentage at this stage. The next measurable progress should be based on the number of validated relationship rules, resolved formal defects, accepted context contracts and passing acceptance tests.

## 7. Remaining TODOs

### P0 — Correct the formal models

- Produce v0.4.2 with the fact-knowledge, source-availability and freshness dimensions separated.
- Define absence semantics for each fact type.
- Enforce freshness limits consistently across all fallback paths.
- Version freshness contracts and define their effective times.
- Replace the event-correction algorithm with a consistent correction/retraction lineage model.
- Validate correction targets, authority, ordering and conflicting revisions.
- Check both transition legality and sequence continuity.
- Define the result when a historical sequence is inconsistent or ambiguous.
- Preserve historical reproducibility while allowing corrected historical interpretations.
- Separate ordinary denial, missing facts, operational failures and policy configuration errors.

### P0 — Resolve domain ownership and relationship semantics

- Clarify Person, Account, Member and Membership.
- Validate Membership ↔ Organization ↔ OrganizationalUnit.
- Decide how Committee relates to OrganizationalUnit without duplicating ownership.
- Distinguish committee participation, appointment, role and mandate.
- Clarify Role ↔ Permission ↔ AuthorizationDecision.
- Separate geographical jurisdiction from organizational hierarchy.
- Define the ownership of generic authorization evaluation versus forum-specific access policy.
- Clarify audit evidence versus authoritative domain history.
- Specify the authoritative owner for every important fact.

### P0 — Validate Groups 1 and 2

- Run the multi-organization membership scenario.
- Test suspension, termination, transfer and retroactive correction.
- Run the committee appointment and mandate-expiry scenario.
- Test role revocation independently of membership changes.
- Record positive, negative, boundary and counterexample outcomes.
- Link each result to an explicit rule, evidence source and test ID.

### P0 — Operationalize context contracts and the validation matrix

- Define each context's responsibilities, non-responsibilities, events and queries.
- Define event payloads, identifiers, organizational scope and contract versions.
- Specify projection freshness and revocation guarantees.
- Map R-01 through R-13 to the five relationship groups.
- Populate the matrix with evidence, invariants, owners, tests and decisions.
- Keep unsupported rules open or contested.
- Define who can approve structural, temporal, business, legal and operational rules.

### P1 — Validate the remaining relationship groups

- Test Role ↔ Permission ↔ AuthorizationDecision.
- Test Geography ↔ OrganizationalUnit ↔ Committee.
- Test Forum ↔ Membership/Role ↔ Access.
- Test active sessions, stale projections and unavailable authoritative sources.
- Use property-based testing and constraint solving for edge cases.
- Apply model checking selectively to lifecycle and correction sequences.

### P2 — Finalize the DDD architecture

- Derive bounded contexts from validated ownership and lifecycle responsibilities.
- Derive aggregates from transactional invariants and concurrency requirements.
- Produce the context map and integration contracts.
- Define identity scope and tenant-isolation requirements.
- Review the proposed architecture against the existing implementation.
- Identify the smallest safe implementation changes and their dependencies.

### P3 — Implement incrementally

- Define acceptance criteria for each implementation slice.
- Implement validated rules with domain and integration tests.
- Add temporal, authorization and tenant-isolation regression tests.
- Preserve traceability from approved rules to code and tests.
- Measure readiness using verified outcomes rather than subjective percentages.

## Final recommendation

The next step is to correct v0.4.1 into v0.4.2 and validate the membership and committee-authority relationship groups first.

The most urgent technical issue is that the proposed historical-event correction algorithm is not yet internally consistent. The most urgent DDD issue is that the ownership contracts still contain overlapping or insufficiently defined responsibilities.

Once those two problems are addressed, we can validate the model against real organizational scenarios, then derive bounded contexts and aggregates from evidence. That will move us toward an architecture that is both reusable across organizations and practical to implement—without bringing election implementation into the current scope.
#                  #############################
# Principal Review and Next-Step Execution: Cross-Relationship Architecture v0.4.2

## 1. Principal Reviewer Assessment

### 1.1 What This Review Contributes

The v0.4.1 review is the most logically rigorous document in the corpus. It does not restate or merely accept; it identifies four defects in the corrections that were themselves proposed as corrections. This is exactly the discipline the platform requires.

The four findings are genuine logical defects in v0.4.1, not stylistic preferences:

1. **Fact status conflates three independent dimensions.** The `FactStatus` enum (`True`, `False`, `Unknown`, `Unavailable`, `Stale`) mixes value knowledge, source availability, and observation freshness. A source may be unavailable while a cached value is fresh; a source may be available while returning a value too old for the action. These are not mutually exclusive states and must be modeled as independent dimensions.

2. **Correction-aware event algorithm fails on correction chains.** The proposed algorithm removes superseded events before applying corrections, then looks for correction targets in the already-filtered set. If a correction references a superseded event, the target may already be gone. Retractions have the same defect. This is a structural flaw, not a coding detail.

3. **Deterministic ordering does not guarantee a valid state.** The downstream validation checks each transition's target state but does not verify that the transition's `fromState` matches the current state. Two separate validations are required: transition legality (state machine) and sequence continuity (`fromState` consistency).

4. **Ownership contracts contain unresolved overlaps.** Identity ↔ Membership, Organization ↔ Governance, Governance ↔ Authorization, Authorization ↔ Forum, Audit ↔ Domain, Geography ↔ Governance all have boundary questions. No fact may have two authoritative owners.

All four findings are accepted. They become the basis of v0.4.2.

### 1.2 Where I Strengthen the Review

The review is strong but has four weaknesses that must be addressed in v0.4.2.

**Weakness 1 — The three-dimensional fact model is proposed but not fully formalized.**
The review proposes three dimensions (value knowledge, source availability, observation freshness) but does not specify:
- The cross-product semantics (e.g., KnownTrue + Unavailable + Fresh — is that possible?).
- How the three dimensions combine to determine fallback eligibility.
- How absence-of-record is distinguished from unknown-value.

**Weakness 2 — The correction lineage model is described but not specified.**
The review correctly requires "a single valid revision lineage" but does not specify:
- How revision lineage is computed.
- How conflicting branches are detected.
- How the system resolves or rejects ambiguous histories.
- The formal properties that replace the prior algorithm.

**Weakness 3 — The sequence validity check is stated but not formalized.**
The review correctly distinguishes transition legality from sequence continuity, but does not specify:
- The precise validation algorithm.
- The result model when sequence validation fails.
- How the system recovers from inconsistent histories.

**Weakness 4 — The ownership boundaries are identified but not resolved.**
The review lists six unresolved boundaries but does not yet propose resolution criteria. These must be resolved through evidence, not preference.

These weaknesses are addressed in v0.4.2.

---

## 2. Next-Step Deliverable: Cross-Relationship Architecture Baseline v0.4.2

The next artifact is a corrected semantic baseline. It does not add new concepts; it resolves the four defects identified by the review.

### 2.1 Correction 1 — Three-Dimensional Fact Observation Model

**Principle:** Value knowledge, source availability, and observation freshness are independent dimensions. They must not be collapsed into one enum.

**Fact observation model:**

```
FactObservation {
  factId
  subjectId           // the entity the fact is about
  factType            // membership_status, appointment_status, mandate_validity, etc.
  organizationScope

  // Dimension 1: Value knowledge
  valueKnowledge: KnownTrue | KnownFalse | Unknown

  // Dimension 2: Source availability
  sourceAvailability: Available | Unavailable

  // Dimension 3: Observation freshness
  observationTime: timestamp
  validTime: timestamp (optional, for bi-temporal facts)
  freshnessPolicy: FreshnessPolicy
  freshnessStatus: Fresh | Stale  // derived from observationTime and freshnessPolicy

  // Absence semantics
  absenceReason: NoRecord | NotApplicable | ExplicitAbsence | Unknown
}
```

**Dimension semantics:**

| Dimension | Values | Meaning |
|---|---|---|
| Value knowledge | KnownTrue | Authoritative source confirmed true |
| | KnownFalse | Authoritative source confirmed false |
| | Unknown | No record, no evidence, no authoritative answer |
| Source availability | Available | Authoritative source reachable |
| | Unavailable | Source unreachable (timeout, error, connectivity) |
| Observation freshness | Fresh | `now - observationTime ≤ freshnessPolicy.maxAge` |
| | Stale | `now - observationTime > freshnessPolicy.maxAge` |

**Absence semantics (critical distinction):**

| absenceReason | Meaning | Example |
|---|---|---|
| NoRecord | System has no record | No membership ever applied |
| NotApplicable | Fact does not apply to this subject | No committee exists, so no appointment possible |
| ExplicitAbsence | Business fact is explicitly absent | Person was explicitly rejected for membership |
| Unknown | System cannot determine why | Operational failure, data loss |

**Cross-product semantics (which combinations are meaningful):**

| Value Knowledge | Source Availability | Freshness | Meaning | Fallback Eligibility |
|---|---|---|---|---|
| KnownTrue | Available | Fresh | Fact is true and confirmed | Use fact |
| KnownFalse | Available | Fresh | Fact is false and confirmed | Use fact |
| KnownTrue | Available | Stale | True but old | Fallback per policy |
| KnownTrue | Unavailable | Fresh | True, cached recently | Fallback per policy |
| KnownTrue | Unavailable | Stale | True, cached long ago | Deny (unless policy allows) |
| Unknown | Available | Fresh | No record, source reachable | Depends on absenceReason |
| Unknown | Unavailable | — | No record, source unreachable | Deny (fail-closed) |
| — | Unavailable | Stale | Source unreachable, cache stale | Deny |

**Fallback eligibility function:**

```
canUseFallback(observation, action, policy):
  if observation.valueKnowledge == Unknown:
    return false

  if observation.sourceAvailability == Available and observation.freshnessStatus == Fresh:
    return true

  age = now - observation.observationTime

  if age > observation.freshnessPolicy.maxStaleness:
    return false

  if action ∉ observation.freshnessPolicy.allowedFallbackActions:
    return false

  if observation.freshnessStatus == Stale and observation.freshnessPolicy.denyOnStale:
    return false

  if observation.freshnessPolicy.requireFreshAuthority and not isFreshAuthority(observation, action):
    return false

  return true
```

**Required tests:**

| Test | Value | Availability | Freshness | Action | Expected |
|---|---|---|---|---|---|
| F-T1 | KnownTrue | Available | Fresh | Any | Use fact |
| F-T2 | KnownFalse | Available | Fresh | Any | Use fact |
| F-T3 | KnownTrue | Unavailable | Fresh | Read | Use fallback |
| F-T4 | KnownTrue | Unavailable | Stale | Read | Deny if `denyOnStale` |
| F-T5 | KnownTrue | Unavailable | Stale | Authoritative | Deny if `requireFreshAuthority` |
| F-T6 | Unknown | Available | Fresh | Any | Use absenceReason |
| F-T7 | Unknown | Unavailable | — | Any | Deny (fail-closed) |
| F-T8 | KnownTrue | Available | Stale | Read | Fallback per policy |
| F-T9 | KnownTrue | Available | Stale | Authoritative | Deny if `requireFreshAuthority` |

### 2.2 Correction 2 — Correction-Aware Event History (Lineage Model)

**Principle:** Transition records are immutable. Corrections and retractions reference transitions and are resolved through explicit lineage, not by removing events from a filtered set.

**Event model (revised):**

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
  supersedesCorrectionId (nullable)
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

**Key distinction:** Transitions are immutable. Corrections and retractions are separate records that reference transitions.

**Effective history algorithm:**

```
effectiveHistory(subjectId, k):
  # Step 1: Collect all records known at transaction time k
  transitions = { t ∈ TransitionRecords
                  | t.subjectId = subjectId
                  ∧ t.recordedAt ≤ k }
  corrections = { c ∈ CorrectionRecords
                  | c.subjectId = subjectId
                  ∧ c.recordedAt ≤ k }
  retractions = { r ∈ RetractionRecords
                  | r.subjectId = subjectId
                  ∧ r.recordedAt ≤ k }

  # Step 2: Build correction lineage
  # Each transition may have at most one valid correction chain
  lineage = buildLineage(transitions, corrections)

  # Step 3: Detect conflicting corrections
  conflicts = detectConflicts(lineage)
  if conflicts ≠ ∅:
    return InconsistentHistory(reason="conflicting corrections", conflicts)

  # Step 4: Apply corrections in recordedAt order
  effective = {}
  for t in transitions:
    effective[t.id] = t
  for c in sort(corrections, by=recordedAt):
    if c.targetTransitionId ∉ effective:
      return InconsistentHistory(reason="correction targets unknown transition")
    if c.supersedesCorrectionId and c.supersedesCorrectionId not in corrections:
      return InconsistentHistory(reason="correction supersedes unknown correction")
    applyCorrection(effective[c.targetTransitionId], c)

  # Step 5: Apply retractions
  for r in sort(retractions, by=recordedAt):
    if r.targetTransitionId ∉ effective:
      return InconsistentHistory(reason="retraction targets unknown transition")
    remove(effective, r.targetTransitionId)

  # Step 6: Order effective transitions
  ordered = sort(values(effective), by=(effectiveAt, version))

  # Step 7: Validate sequence continuity
  validation = validateSequence(ordered)
  if validation.status == invalid:
    return InconsistentHistory(reason=validation.reason, invalidTransitions=validation.invalid)

  return ordered
```

**Lineage construction:**

```
buildLineage(transitions, corrections):
  lineage = {}
  for t in transitions:
    lineage[t.id] = { transition: t, corrections: [] }
  for c in corrections:
    if c.targetTransitionId ∈ lineage:
      lineage[c.targetTransitionId].corrections.append(c)
  return lineage
```

**Conflict detection:**

```
detectConflicts(lineage):
  conflicts = []
  for t.id, entry in lineage:
    # Sort corrections by recordedAt
    sorted = sort(entry.corrections, by=recordedAt)
    # Check for branching: two corrections with the same supersedesCorrectionId
    branches = groupBy(sorted, c → c.supersedesCorrectionId)
    for branch in branches:
      if len(branch) > 1:
        conflicts.append(branch)
  return conflicts
```

**Sequence validation:**

```
validateSequence(orderedTransitions):
  if orderedTransitions = ∅:
    return { status: valid, state: Unknown }

  # Transition legality: each transition is valid in the state machine
  for t in orderedTransitions:
    if not isValidTransition(t.fromState, t.toState):
      return {
        status: invalid,
        reason: "illegal transition",
        invalid: [t]
      }

  # Sequence continuity: each transition's fromState matches prior toState
  currentState = orderedTransitions[0].fromState
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

  return {
    status: valid,
    finalState: currentState,
    transitions: orderedTransitions
  }
```

**State query:**

```
stateOf(subjectId, v, k):
  history = effectiveHistory(subjectId, k)
  if history is InconsistentHistory:
    return history  // propagate inconsistency
  applicable = { t ∈ history | t.effectiveAt ≤ v }
  if applicable = ∅: return Unknown
  return applicable.last().toState
```

**Formal properties:**

| Property | Statement |
|---|---|
| P1 | Transition records are immutable |
| P2 | Corrections and retractions are additive, never destructive |
| P3 | For fixed (subjectId, v, k), `stateOf` is deterministic |
| P4 | Inconsistent histories are explicitly returned, never silently resolved |
| P5 | Conflicting correction branches are detected and reported |
| P6 | Sequence continuity is validated (`fromState` matches prior `toState`) |
| P7 | Transition legality is validated (state machine) |
| P8 | Historical reproducibility holds for consistent histories |

**Required tests:**

| Test | Scenario | Expected |
|---|---|---|
| EH-T1 | Single transition | stateOf returns correct state |
| EH-T2 | Correction to effectiveAt | Both records preserved; state reflects correction |
| EH-T3 | Correction to toState | State updated; original preserved |
| EH-T4 | Retraction | Transition removed from effective; original preserved |
| EH-T5 | Conflicting correction branches | InconsistentHistory returned |
| EH-T6 | Correction targeting unknown transition | InconsistentHistory returned |
| EH-T7 | Sequence discontinuity | InconsistentHistory returned with details |
| EH-T8 | Illegal transition | InconsistentHistory returned |
| EH-T9 | Bi-temporal query | Correct state for (v, k) |
| EH-T10 | Reproducibility | Same inputs → same output |

### 2.3 Correction 3 — Sequence Validity (Formalized)

**Principle:** Two independent validations are required — transition legality and sequence continuity. Both must pass.

This is now integrated into the `validateSequence` function above. The key distinction:

| Validation | Question | Failure Mode |
|---|---|---|
| Transition legality | Is `(fromState, toState)` a valid state-machine transition? | `illegal transition` |
| Sequence continuity | Does `fromState` match the prior `toState`? | `sequence discontinuity` |

**Result model:**

```
InconsistentHistory {
  subjectId
  reason: string
  invalid: [TransitionRecord]
  expectedFromState (optional)
  actualFromState (optional)
  detectedAt: timestamp
}
```

**Recovery:** Inconsistent histories require human resolution. The system must never silently pick a "best guess."

### 2.4 Correction 4 — Ownership Boundaries (Resolution Criteria and Proposed Resolutions)

The review identifies six unresolved boundaries. Each must be resolved by meaning, lifecycle, and invariants — not by preference for separate services.

**Resolution criteria:**

For each boundary, we ask:
1. Which context creates the fact?
2. Which context modifies it?
3. Which context terminates it?
4. Which context is accountable for its correctness?
5. Which contexts may consume it, and via what contract?

**Proposed resolutions (hypotheses to validate):**

| Boundary | Proposed Resolution | Rationale |
|---|---|---|
| **Identity & Access ↔ Membership** | Identity owns Account and authentication identity; Membership owns the recognized membership relationship. Person identity is a separate question still requiring resolution. | Login is not membership; membership is not identity |
| **Organization ↔ Governance** | Organization owns OrganizationalUnit identity and structure; Governance owns Committee as a distinct concept. A Committee may reference an OrganizationalUnit (e.g., "the committee of Unit X") without being owned by it. | Committee is a governance body, not a structural unit |
| **Governance ↔ Authorization** | Governance owns Appointment, Role, Mandate as facts. Authorization owns the decision function that evaluates facts against policy. Governance does not decide permissions; Authorization does not own facts. | Facts ≠ decisions |
| **Authorization ↔ Forum** | Authorization owns the generic evaluation mechanism (actor, action, resource, facts, policy, time → decision). Forum owns ForumAccessPolicy, which defines *what* to evaluate. Forum-specific policy is owned by Forum; generic evaluation is owned by Authorization. | Policy ≠ evaluation |
| **Audit ↔ Domain contexts** | Audit owns the immutable record of changes. Domain contexts own current authoritative state. Audit is not the authority for current state; it is the authority for what was recorded. | History ≠ current state |
| **Geography ↔ Governance** | Geography owns GeographicJurisdiction and hierarchy. Governance owns Committee. Jurisdiction assignments (Committee ↔ Jurisdiction) are owned by Governance (the assignment) using Geography's jurisdictions. | Territory ≠ committee |

**Contract schema (extended):**

```
ContextContract {
  contextName
  owns: [FactType]
  publishes: [EventType]
  consumes: [EventType]
  exposesQueries: [QueryType]
  doesNotOwn: [FactType]
  projections: [ProjectionType]  // derived, with freshness guarantees
  freshnessGuarantees: [FreshnessContract]
  availabilityBehavior: BehaviorOnSourceUnavailable
  contractVersion
}
```

**Projection model (for derived facts):**

```
Projection {
  projectionId
  ownerContext       // the context maintaining the projection
  sourceContext      // the authoritative source
  sourceFactType
  projectionType: cache | materialized_view | derived_decision
  freshnessGuarantee
  invalidationTriggers: [EventType]
  behaviorOnStale: Deny | UseWithWarning | RequireAuthoritative
}
```

**Key rule:** A projection is never authoritative. If the projection's freshness guarantee is exceeded, the consumer must either query the authoritative source or deny the action (per `behaviorOnStale`).

### 2.5 The Validation Slices (Group 1 and Group 2)

The review proposes two practical validation slices. I formalize them with explicit acceptance criteria.

**Slice 1 — Membership Semantics**

**Scenario:** An individual belongs to Organization A and Organization B. Membership in A is suspended; membership in B remains active. They also have an appointment in a committee of Organization B.

**Required answers:**

| Question | Expected Behavior | Rule |
|---|---|---|
| Which identity is shared across organizations? | Person identity (pending resolution) | R-01 |
| Are the two memberships separate relationships? | Yes | R-02 |
| Does suspending one affect the other? | No | R-02 |
| Does the suspension affect the committee appointment in B? | No | R-07 |
| Does the suspension affect the committee appointment in A (if any)? | Depends on policy — appointment in A requires active membership in A | R-07 |
| What should historical queries return? | The state as known at each transaction time | Bi-temporal query |
| What events and tests demonstrate correctness? | Event records + property-based tests | Validation |

**Acceptance criteria:**

- The model represents both memberships independently.
- Suspension in A does not affect membership in B.
- Appointment in B is unaffected by suspension in A.
- Appointment in A (if exists) is affected per policy.
- Historical queries return correct state for all (v, k) combinations.
- All behaviors are linked to rules, evidence, and tests.

**Slice 2 — Committee Authority**

**Scenario:** A member has an active membership, an active committee appointment, a role with defined scope, and a mandate expiring at a specified time.

**Required distinctions:**

| Concept | Behavior |
|---|---|
| Membership | Remains active after mandate expiry |
| Committee participation | Remains recorded after mandate expiry |
| Appointment | Evaluated separately from mandate |
| Mandate | Determines authority validity |
| Role | Provides facts for authorization; does not establish permission |
| Authorization | Evaluates action, resource, scope, policy, facts, time |

**Tests:**

| Test | Scenario | Expected |
|---|---|---|
| CA-T1 | Before mandate expiry | Action permitted |
| CA-T2 | After mandate expiry | Action denied |
| CA-T3 | After mandate expiry, source unavailable, cache stale | Deny (requireFreshAuthority) |
| CA-T4 | After mandate expiry, existing session | Authorization re-evaluated; deny |
| CA-T5 | Role exists, no permission grant | Deny |
| CA-T6 | Role exists, permission granted for another committee | Deny |
| CA-T7 | Mandatory prohibition applies | Deny |
| CA-T8 | Stale authority fact | Deny |

**Acceptance criteria:**

- Membership and mandate are independently evaluated.
- Authorization decisions are explainable.
- Stale facts are handled per freshness contracts.
- Historical queries reproduce decisions.
- All behaviors are linked to rules, evidence, and tests.

### 2.6 Mathematics, Statistics, and Machine Learning (v0.4.2 Scope)

| Technique | Application | Oversight |
|---|---|---|
| Predicate logic | Membership, appointment, jurisdiction, access conditions | Manual specification |
| Temporal logic | Expiry, revocation, historical queries | Manual specification |
| Property-based testing | Generate lifecycle event sequences (activation, suspension, correction, reactivation, termination) | Automated with generators |
| Reference model comparison | Compare implementation's historical queries against a simple independently-defined reference model | Automated |
| Constraint solving | Detect contradictory policy configurations | Automated |
| Model checking | Explore lifecycle and correction sequences | Selective |
| NLP / LLM | Extract candidate rules from bylaws and policies | Mandatory human verification |
| Clustering | Compare organizational terms and patterns | Mandatory human verification |
| Statistical analysis | Compare observations across organizations | Signal only |

**Safety invariant for authorization:**

```
Permit ⇒ ApplicableGrant
       ∧ AllMandatoryConditionsSatisfied
       ∧ ¬ApplicableMandatoryProhibition
       ∧ FactFreshnessSatisfied
       ∧ SequenceConsistencyValid
```

This is a candidate safety invariant. The full predicate must include approved policy semantics and the fact-freshness contract.

**ML must never decide:**
- Membership status
- Appointment approval
- Mandate validity
- Policy precedence
- Forum access
- Authorization outcomes

ML assists research. Humans and formal logic decide.

---

## 3. How Far We Are from the Implementation Goal

### 3.1 Maturity Assessment

| Dimension | Status |
|---|---|
| Conceptual foundation | Candidate concepts, relationship distinctions, validation scenarios established |
| Formal specification | Partially complete; fact freshness, correction chains, transition consistency, context contracts need refinement |
| Evidence and falsification | Not yet demonstrated; candidate rules require validation against organizational evidence |
| Validated DDD architecture | Not established; context ownership and boundaries remain open |
| Implementation readiness | Not established; acceptance criteria and validated rules remain open |

**Overall assessment:** The corpus has reached a mature formal specification with two remaining corrections being addressed in v0.4.2. We are approximately **45–50%** of the way toward an implementable organizational domain model. This is a qualitative estimate. The next measurable milestone is validating Group 1 and Group 2 with evidence and passing tests.

### 3.2 Remaining TODOs (Bullet Points)

**P0 — Correct the formal models (v0.4.2)**
- Adopt the three-dimensional fact observation model (value knowledge, source availability, observation freshness).
- Define absence semantics (`NoRecord`, `NotApplicable`, `ExplicitAbsence`, `Unknown`).
- Enforce freshness limits consistently across all fallback paths.
- Version freshness contracts and define their effective times.
- Replace the event-correction algorithm with the lineage model (transitions immutable; corrections and retractions are separate records).
- Validate correction targets, authority, ordering, and conflicting revisions.
- Check both transition legality and sequence continuity.
- Define explicit `InconsistentHistory` result with reason and invalid transitions.
- Preserve historical reproducibility while allowing corrected historical interpretations.
- Separate ordinary denial, missing facts, operational failures, and policy configuration errors.

**P0 — Resolve ownership boundaries**
- Clarify Person, Account, Member, Membership relationships.
- Validate Membership ↔ Organization ↔ OrganizationalUnit.
- Decide Committee ↔ OrganizationalUnit relationship without duplicating ownership.
- Distinguish committee participation, appointment, role, mandate.
- Clarify Role ↔ Permission ↔ AuthorizationDecision.
- Separate geographical jurisdiction from organizational hierarchy.
- Define ownership of generic authorization evaluation vs. forum-specific access policy.
- Clarify audit evidence vs. authoritative domain history.
- Specify the authoritative owner for every important fact.
- Define context contract schema with owns, publishes, consumes, exposes, does not own, projections, freshness guarantees.
- Define projection model with invalidation triggers and behavior on stale.

**P0 — Validate Group 1 (Member, Membership, Organization)**
- Run the multi-organization membership scenario.
- Test suspension, termination, transfer, retroactive correction.
- Record positive, negative, boundary, and counterexample outcomes.
- Link each result to a rule, evidence source, and test ID.

**P0 — Validate Group 2 (Committee, Appointment, Role, Mandate)**
- Run the committee authority scenario.
- Test mandate expiry with session persistence and unavailable sources.
- Test role revocation independently of membership.
- Test appointment and mandate separation.
- Record positive, negative, boundary, and counterexample outcomes.

**P0 — Operationalize context contracts and validation matrix**
- Define each context's responsibilities, non-responsibilities, events, queries.
- Define event payloads, identifiers, organizational scope, contract versions.
- Specify projection freshness and revocation guarantees.
- Map R-01 through R-13 to the five relationship groups.
- Populate the matrix with evidence, invariants, owners, tests, decisions.
- Keep unsupported rules open or contested.
- Define decision authority for structural, temporal, business, legal, operational rules.

**P1 — Validate remaining relationship groups**
- Test Group 3: Role ↔ Permission ↔ AuthorizationDecision.
- Test Group 4: Geography ↔ OrganizationalUnit ↔ Committee.
- Test Group 5: Forum ↔ Membership/Role ↔ Access.
- Test active sessions, stale projections, unavailable authoritative sources.
- Use property-based testing, constraint solving, selective model checking.

**P2 — Finalize the DDD architecture**
- Derive bounded contexts from validated ownership and lifecycle responsibilities.
- Derive aggregates from transactional invariants and concurrency requirements.
- Produce the context map and integration contracts.
- Define identity scope and tenant isolation requirements.
- Review the architecture against the existing implementation.
- Identify smallest safe implementation changes.

**P3 — Implement incrementally**
- Define acceptance criteria for each implementation slice.
- Implement validated rules with domain and integration tests.
- Add temporal, authorization, tenant-isolation regression tests.
- Preserve traceability from approved rules to code and tests.
- Measure readiness using verified outcomes, not subjective percentages.

### 3.3 Final Recommendation

The next step is v0.4.2, containing:

1. Three-dimensional fact observation model.
2. Correction-aware event lineage model with sequence validation.
3. Formalized sequence validity (transition legality + continuity).
4. Resolved ownership boundaries with context contract schema and projection model.
5. Validated Slice 1 (membership) and Slice 2 (committee authority) with acceptance criteria.

The most urgent technical issue is the correction-lineage algorithm. The most urgent DDD issue is ownership boundary resolution. Once both are addressed, Group 1 and Group 2 can be validated against real organizational scenarios, and bounded contexts can be derived from evidence.

**Authorization required:** Provision of at least one political party constitution and one membership association bylaws to begin evidence collection and falsification of Groups 1 and 2.