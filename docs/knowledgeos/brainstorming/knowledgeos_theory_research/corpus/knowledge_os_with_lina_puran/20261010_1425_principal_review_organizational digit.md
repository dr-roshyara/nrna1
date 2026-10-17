# Principal review: Organizational digitalization platform — next step

My assessment is that we have moved beyond brainstorming into formal domain-model research and validation. We have candidate concepts, relationship rules, temporal semantics, and a proposed method for testing the model. However, we have not yet demonstrated that the model correctly represents real organizations and political parties.

The next step is therefore not to start implementation and not to derive bounded contexts prematurely. We should validate the cross-domain relationships against real organizational scenarios, correct the remaining formal defects, and establish which rules are universal platform invariants and which depend on an organization's own statutes or policies.

The attached v0.3.1 backlog is a useful research artifact, but I do not recommend accepting it unchanged.&#x20;

Eingefügter Text.txt



## 1. What we are building

Our goal is a reusable digitalization platform for organizations and political parties. The platform must represent organizational structures, memberships, responsibilities, permissions, geographical structures, and communication without confusing concepts that have different meanings or lifecycles.

The current research boundary is:

## Organizational domain model

The shared concepts and relationship rules of the platform

Member & Membership

Identity in the organization, membership lifecycle, membership type and status

Organization & Committee

Organizational units, committee composition, appointments and mandates

Roles & Permissions

Authority, authorization rules, scope and revocation

Geography & Jurisdiction

Geographical hierarchy, jurisdiction and organizational representation

Discussion Forum & Access

Participation, access policy, moderation authority and access revocation

Election implementation is out of scope

We will not develop voter eligibility, voter-roll management or election workflows in this phase. We can validate whether the foundational model is sufficiently expressive for future needs without designing election-specific concepts now.

Our central DDD question is:

Which relationships are genuine domain concepts, who owns their lifecycle, what rules govern them, and how can we prove those rules are correct?

A database diagram alone cannot answer that question.

## 2. What the attached v0.3.1 backlog gets right

The document correctly identifies four important areas for formal correction.&#x20;

Eingefügter Text.txt



- Constraints are not grants. A rule that prohibits or constrains an action must not accidentally authorize that action.
- Events and states are different. A state transition happens at an instant; a state persists over a period.
- Historical decisions need traceability. Correcting a fact must not silently erase what the system previously recorded or decided.
- Evidence must match the claim. A structural invariant can be validated with integrity tests; a business rule may require statutes, policies and stakeholder confirmation.

The document also proposes a useful traceability chain:

`Evidence → Rule → Formal invariant → Owner → Test → Decision`

This should become a core governance mechanism. A rule is not established merely because it appears in a specification, and a test passing does not prove that the underlying business rule is correct.

## 3. Principal-review findings: what must be corrected

### Finding 1 — The authorization algorithm still needs a more precise contract

The corrected algorithm is a good starting point, but its fallback and priority semantics are not yet safe enough to adopt as a platform-wide rule.

For example, suppose a committee appointment expires at 12:00. The authorization service cannot reach the authoritative appointment source at 12:01, but its cache still says the appointment is valid. A fallback to the last known facts could incorrectly preserve access.

We need to distinguish three things:

| Concern              | Required clarification                                                 |
| -------------------- | ---------------------------------------------------------------------- |
| Fact status          | Is a fact true, explicitly false, unknown, unavailable, or stale?      |
| Authorization policy | Which rules grant authority, impose conditions, or prohibit an action? |
| Operational fallback | Which facts may be reused, for how long, and for which decisions?      |

Recommended correction: define freshness requirements per fact and per action. A cached fact may be reused only where its freshness contract permits it. Expiry, revocation and other time-sensitive authority changes must not be overridden by an indiscriminate fallback.

The priority rule also needs explicit approval. The draft says the highest-priority rule wins, with denial winning ties. That is one possible policy model, not a universal DDD invariant. We need to establish which policy types use priority, which prohibitions are absolute, and how conflicting rules are diagnosed.

The next validation should include:

- An appointment expires while a user session is active.
- A role is revoked while the authorization service is unavailable.
- A user has a forum permission but no longer meets the policy's membership condition.
- Two applicable rules conflict at the same priority.
- A required fact is explicitly false versus unavailable.

### Finding 2 — The event-based state model is not yet formally correct

The draft improves the distinction between events and states, but the proposed algorithm has an unresolved correction problem.

It selects all events recorded by transaction time \\(k\\), filters those effective by valid time \\(v\\), sorts them, and takes the last event's `toState`. However, it does not exclude events superseded by corrections. The proposed retraction mechanism also models a retraction as a new transition back to an earlier state, which can misrepresent history.

Consider a membership lifecycle:

1. Membership becomes active on 1 October.
2. The activation is recorded on 2 October.
3. On 10 October, an authorized correction establishes that activation should have taken effect on 5 October.
4. A suspension was recorded as effective on 7 October.

A correct historical query must distinguish what the system believed on 4 October from what it knows after 10 October. It must also detect whether the suspension remains valid after the correction.

The state query therefore needs a correction-aware event interpretation, not just a sort-and-fold operation.

Recommended model:

- Preserve the original transition event.
- Represent correction and retraction explicitly, including authority, reason and recorded time.
- Determine the effective event history as known at transaction time \\(k\\).
- Derive the state at valid time \\(v\\) from that history.
- Validate transition continuity and flag downstream transitions invalidated by a correction.
- Define deterministic handling of simultaneous events without treating arbitrary version ordering as proof of business validity.

We should test this using membership activation, committee appointment, mandate expiry and role revocation. The formal model should be proven on these foundational lifecycles before implementation.

### Finding 3 — Frozen election records must be parked

The document spends substantial space defining policy versions, voter snapshots, frozen eligibility determinations and appeals. These may be useful in a later election workstream, but they are outside our current scope.

We should not treat the election-record design as a current deliverable or as an approved foundational model.

For this phase, the relevant reusable question is narrower:

> Can the platform preserve a historically reproducible view of membership, committee appointments, roles, geographical jurisdiction and authorization decisions at a specified time?

That question can be tested with ordinary organizational scenarios. It does not require implementing an election model.

### Finding 4 — The evidence workflow needs a practical starting point

The matrix schema and decision workflow are useful, but a blank matrix is not validation. Nor does collecting four documents automatically establish that the model is correct.

We need evidence that covers the relationships under study. I recommend starting with:

1. The constitution or bylaws of one political party.
2. The bylaws or statutes of one membership-based association.
3. The committee and geographical-structure rules applicable to those organizations.
4. The relevant membership, role/authority and forum policies, where documented.

Federation agreements or other organizational forms can be added when they test a specific unresolved relationship. A Red Cross / Red Crescent source is not a mandatory prerequisite unless that organization type is part of the intended target domain.

For every source-derived rule, we should record the exact source location and distinguish:

- Source fact: what the document actually states.
- Interpretation: what we believe that statement means.
- Candidate platform rule: the behavior we propose to support.
- Validation evidence: scenarios, counterexamples and tests.
- Decision: approved, rejected or still unresolved.

This prevents us from mistaking our interpretation for an organization's actual rule.

## 4. The most important DDD question: what do the relationships mean?

Before deriving bounded contexts, we need to resolve the following distinctions. These are candidate distinctions to validate, not a declaration that every concept must become its own aggregate or service.

| Relationship                     | Question we must answer                                                                                                                               |
| -------------------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------- |
| Person ↔ Member                  | Is a person represented independently of membership in a particular organization? Can one person be a member of multiple organizations?               |
| Member ↔ Membership              | Does the member represent an organizational relationship, while membership represents its lifecycle, type and status?                                 |
| Membership ↔ Organizational unit | Is affiliation a distinct relationship with its own validity, authority and history?                                                                  |
| Committee ↔ Organizational unit  | Is a committee a type of organizational unit, or does it have a separate purpose, lifecycle and composition rule?                                     |
| Member ↔ Committee               | Is participation distinct from appointment, and can a committee participant lack formal decision-making authority?                                    |
| Committee appointment ↔ Role     | Does an appointment assign one or more roles, or does a role exist independently and receive authority through another relationship?                  |
| Role ↔ Permission                | Does a role contribute permissions, while authorization also depends on scope, conditions, policy and current facts?                                  |
| Organization ↔ Geography         | Is a geographical jurisdiction a different hierarchy from the organization's internal structure? Can one committee cover multiple geographical areas? |
| Committee ↔ Mandate              | Does a mandate define the authority, purpose, scope and duration of a committee or appointment?                                                       |
| Member/Role ↔ Forum              | Is forum access derived from membership or committee authority, explicitly granted, or governed by a combination of these?                            |

The central rule is that similar-looking relationships must not be merged merely because they currently have the same database columns. Their lifecycle, invariants, authority and meaning determine whether they are the same domain concept.

For example, employment, committee appointment and geographical affiliation may all connect a person to an organizational unit. They should not automatically become one generic `Affiliation` entity: their authorization, expiry and business rules may be different.

## 5. The next step: Cross-Relationship Validation Matrix v0.4

My recommendation is to create a scope-corrected validation artifact before writing more architecture specifications. It should turn R-01 through R-13 into a testable research backlog, concentrating only on the organizational relationships.

The first deliverable is not another glossary. It is a matrix that tells us what we know, what remains a hypothesis, how to challenge each hypothesis, and what evidence is needed to make a decision.

### 5.1 Initial validation scenarios

These are proposed research scenarios, not confirmed business rules.

| ID    | Scenario                                                  | What it must establish                                                                                               |
| ----- | --------------------------------------------------------- | -------------------------------------------------------------------------------------------------------------------- |
| CR-01 | One person belongs to two organizations                   | Which facts are person-wide and which are organization-scoped                                                        |
| CR-02 | Membership is suspended or terminated                     | Which relationships and permissions change, and which remain historically valid                                      |
| CR-03 | A committee member's appointment expires                  | Whether committee participation and decision-making authority end together                                           |
| CR-04 | A role is removed but membership remains active           | Whether role-derived permissions are revoked without changing membership                                             |
| CR-05 | A geographical boundary or jurisdiction changes           | How jurisdiction history affects committee responsibility and historical queries                                     |
| CR-06 | A committee serves several geographical areas             | Whether the model supports many-to-many jurisdiction relationships where justified                                   |
| CR-07 | A member loses forum access                               | Whether access depends on membership, committee participation, role, explicit grants or combinations of policy rules |
| CR-08 | A user retains a session after authority is revoked       | Whether authorization is reevaluated and revoked within the applicable freshness requirements                        |
| CR-09 | A relationship is entered late or corrected retroactively | Whether valid-time and transaction-time queries return reproducible results                                          |
| CR-10 | Two organizations have different committee rules          | Whether the model represents genuine policy variation without weakening universal integrity constraints              |

For each scenario, we need a successful case, a negative case, a boundary case and at least one counterexample. Where timing matters, we must test both what was valid at a given time and what the system knew at that time.

### 5.2 The matrix we should populate

Each rule should have a record with these fields:

- Rule ID and version
- Exact rule statement and intended domain
- Rule category: structural, temporal, business, configurable, legal or operational
- Source evidence and exact document location, where applicable
- Evidence quality: observed, derived, hypothesis or contested
- Counterexample and its verdict
- Formal proposition or invariant
- Responsible domain or provisional owner
- Test ID and expected result
- Dependencies and unresolved questions
- Decision status and decision authority

We should not mark a rule as approved merely because it appears reasonable or passes a test. Approval requires the right evidence, a documented falsification attempt, and an explicit decision by the designated authority.

### 5.3 How logic, mathematics and machine learning help

| Technique              | Practical application in this phase                                                                                       |
| ---------------------- | ------------------------------------------------------------------------------------------------------------------------- |
| Formal logic           | Express membership, appointment, jurisdiction and authorization invariants precisely                                      |
| Temporal logic         | Check expiry, revocation, historical validity and correction semantics                                                    |
| Property-based testing | Generate unusual combinations of memberships, appointments, time intervals and permissions                                |
| Constraint solving     | Detect contradictory policy configurations and invalid relationship combinations                                          |
| Model checking         | Explore state transitions and verify lifecycle properties                                                                 |
| NLP / LLM              | Extract candidate rules from statutes and policies, with human verification against the source                            |
| Clustering             | Identify different meanings hidden behind similar organizational terminology                                              |
| Statistical analysis   | Identify recurring patterns in validated organizational cases; avoid treating a small sample as proof of a universal rule |

Machine learning should help us discover patterns and possible contradictions, not decide who is a valid member, who holds a mandate, or who is authorized to act. Those decisions must follow explicit, auditable rules.

## 6. How far are we from the implementation goal?

The attached document estimates 45–50% progress toward an implementable organizational domain model. I would treat that as a qualitative estimate, not a measured completion figure: the matrix is not yet populated with external evidence, and falsification has not been executed.&#x20;

Eingefügter Text.txt



My principal-review assessment is:

## Current maturity assessment

Conceptual foundation

Established as a research baseline

Core concepts and important distinctions have been identified. Their full semantics are not yet validated.

Formal specification

Partially complete

The temporal and authorization models have useful drafts, but correction semantics, failure handling and policy contracts need further validation.

Evidence and falsification

Not executed

The next major milestone. Real organizational rules must be compared with our candidate model.

Architecture and implementation

Not authorized by this research

Bounded contexts, validated aggregate boundaries, context map, and implementation plan remain future deliverables.

I would not claim a reliable overall implementation percentage yet. We have made meaningful progress in the specification, but we have not demonstrated implementation readiness. Those are different milestones.

## 7. Remaining TODOs — ordered by priority

### P0 — Correct and constrain the current specification

- Revise v0.3.1 into a scope-corrected validation baseline for the organizational platform.
- Specify authorization fact availability, freshness, expiry, revocation and safe fallback behavior.
- Decide and document the intended priority and conflict-resolution semantics for each policy category.
- Correct the event-history algorithm to handle superseded events, retractions, late entries and retroactive corrections.
- Define how invalid downstream state transitions are detected and resolved.
- Keep election-specific artifacts and E-01 through E-06 outside the current implementation scope.

### P0 — Validate the domain relationships

- Establish the precise meanings of Person, Member and Membership.
- Determine whether affiliation, participation, employment and committee appointment require separate relationship types.
- Validate Committee versus OrganizationalUnit and the lifecycle of committee membership.
- Clarify the relationship among Role, Mandate, Permission and AuthorizationDecision.
- Separate organizational hierarchy from geographical hierarchy and validate their permitted relationships.
- Define how ForumAccessPolicy relates to membership, committee appointments, roles and explicit access grants.
- Identify which rules are universal platform invariants and which are organization-specific policies.

### P0 — Collect evidence and run falsification

- Obtain representative organizational source documents, starting with one political party and one membership association.
- Gather the relevant committee, jurisdiction, role/authority and forum rules.
- Populate the evidence matrix for R-01 through R-13.
- Record exact source locations and separate source facts from interpretation.
- Construct counterexamples for each candidate rule.
- Record a decision for every counterexample: rule holds, rule needs narrower scope, or model must change.
- Link each validated rule to executable tests and regression scenarios.

### P1 — Validate temporal and authorization semantics

- Test historical queries using both valid time and transaction time.
- Test membership suspension, termination and correction.
- Test appointment expiry, mandate changes and role revocation.
- Test geographical jurisdiction changes and their historical interpretation.
- Test forum access after a role or membership change, including active sessions and stale caches.
- Define audit evidence and required reasons for authorization and relationship corrections.
- Use property-based tests and constraint checks to explore edge cases.

### P1 — Establish governance for domain decisions

- Define the decision authority for approving platform-wide invariants and configurable policies.
- Apply the Open → Contested → Recommended → Approved/Rejected workflow.
- Record unresolved questions explicitly rather than silently resolving them in the data model.
- Define what exploratory prototypes may test before formal approval, without treating them as production authorization.
- Maintain traceability from source evidence to rule, test and decision.

### P2 — Derive the architecture only after validation

- Group validated concepts by lifecycle ownership and business responsibility.
- Derive candidate bounded contexts from those validated groups.
- Identify aggregates from transactional invariants and concurrency requirements.
- Validate context boundaries against at least two real scenarios each.
- Produce the context map, integration contracts and relevant domain events.
- Research identity scope and tenant isolation using explicit requirements and trade-offs.
- Prepare the architecture decision package for review and approval.

### P3 — Prepare implementation

- Produce a dependency-ordered implementation plan.
- Define domain, temporal, authorization, integration and tenant-isolation tests.
- Obtain explicit architecture and implementation authorization.
- Implement incrementally, preserving the validated domain rules and their traceability.

## 8. Final principal recommendation

The immediate next deliverable should be the Cross-Relationship Validation Matrix v0.4, not another broad architecture document.

Its first milestone is to validate the highest-risk relationships:

1. Member ↔ Membership ↔ OrganizationalUnit.
2. Committee ↔ Appointment ↔ Role ↔ Mandate.
3. Role ↔ Permission ↔ AuthorizationDecision.
4. OrganizationalUnit ↔ GeographicalJurisdiction.
5. Membership/Appointment/Role ↔ ForumAccessPolicy.

For each, we must establish the meaning, lifecycle, authority, temporal behavior, counterexamples and tests. Only then should we decide the bounded contexts and aggregates.

This is how we move from a plausible conceptual model to a robust, evidence-backed architecture that can be implemented across different organizations without confusing their distinct governance rules.
# ######################################
# Principal Review and Next-Step Execution: Cross-Relationship Validation Matrix v0.4

## 1. Principal Reviewer Assessment

### 1.1 What the Latest Review Contributes

This principal review is a **scope correction and maturity calibration**, not a new specification. It makes three decisive moves:

1. **It parks the election artifacts.** The v0.3.1 frozen election record model, E-01 through E-06 scenarios, and voter-roll machinery are correctly moved out of the current implementation scope. The reusable question — *can the platform preserve a historically reproducible view of organizational facts?* — is retained; the election-specific machinery is deferred.

2. **It refuses to claim implementation readiness.** The 45–50% figure from the previous document is treated as a qualitative estimate, not a measured completion metric. This is the honest position: the matrix is not populated, falsification has not been executed, and evidence has not been collected.

3. **It identifies two formal defects that remain in v0.3.1.** The authorization algorithm's fallback semantics and the event-history correction algorithm are both still unsafe for adoption. These are the two most important technical findings.

This review is accepted. It becomes the baseline for v0.4.

### 1.2 The Two Remaining Formal Defects (Accepted)

**Defect A — Authorization fallback is unsafe.**

The v0.3.1 algorithm says: "If fallback is not permitted, the request is denied but the denial is logged." This is correct in principle but incomplete in practice. The review correctly identifies the case where:

- An appointment expires at 12:00.
- The authorization service cannot reach the authoritative source at 12:01.
- Its cache still says the appointment is valid.
- The fallback reuses the cache and incorrectly preserves access.

The correction requires distinguishing:

| Concern | Question |
|---|---|
| Fact status | Is the fact true, explicitly false, unknown, unavailable, or stale? |
| Authorization policy | Which rules grant, impose conditions, or prohibit? |
| Operational fallback | Which facts may be reused, for how long, and for which actions? |

The fallback must be **per-fact and per-action**, not global. Time-sensitive facts (mandate expiry, appointment termination, role revocation) must never be reused beyond their freshness window.

**Defect B — Event-history algorithm does not handle superseded events.**

The v0.3.1 algorithm sorts events, filters by transaction time, and takes the last `toState`. It does not exclude events superseded by corrections. The retraction model — treating retraction as a new transition back to an earlier state — misrepresents history.

The review correctly states: a correct historical query must distinguish what the system believed on 4 October from what it knows after 10 October, and must detect whether downstream transitions remain valid after a correction.

This requires a **correction-aware event interpretation**, not a sort-and-fold operation.

### 1.3 Scope Correction (Accepted)

Election is out of scope. The following are parked:

- Frozen eligibility determinations
- Voter roll snapshots
- Election policy versions
- E-01 through E-06 scenarios
- Correction-or-appeal determination artifacts

The retained question is narrower and testable:

> Can the platform preserve a historically reproducible view of membership, committee appointments, roles, geographical jurisdiction, and authorization decisions at a specified time?

This is answerable with ordinary organizational scenarios. No election-specific concepts are needed.

### 1.4 What Remains Open

The review correctly identifies that the following are **not yet resolved**:

- Precise meaning of Person, Member, Membership.
- Whether affiliation, participation, employment, and committee appointment are separate relationship types.
- Committee vs. OrganizationalUnit distinction.
- Role vs. Mandate vs. Permission vs. AuthorizationDecision.
- Organizational vs. geographical hierarchy.
- ForumAccessPolicy derivation.
- Which rules are universal vs. organization-specific.
- Identity scope.
- Tenancy model.

---

## 2. Next-Step Deliverable: Cross-Relationship Validation Matrix v0.4

The next artifact is not another architecture specification. It is a **scope-corrected, evidence-oriented validation matrix** for R-01 through R-13, with the two formal defects corrected and the election artifacts removed.

### 2.1 Correction A — Authorization Fallback (Formalized)

**Principle:** Fact status and authorization policy are independent concerns. Fallback must be per-fact and per-action, bounded by freshness contracts.

**Fact status model:**

```
FactStatus {
  value: True | False | Unknown | Unavailable | Stale
  asOf: timestamp
  source: authoritative context
  freshness: duration
  fallbackPolicy: FallbackPolicy
}

FallbackPolicy {
  maxStaleness: duration
  allowedActions: [Action]
  denyOnStale: boolean
  requireAuthority: boolean
}
```

**Fact status semantics:**

| Status | Meaning | Authorization behavior |
|---|---|---|
| True | Fact confirmed true at `asOf` | Use the fact |
| False | Fact confirmed false at `asOf` | Use the fact |
| Unknown | System has no record | Evaluate per policy; usually deny |
| Unavailable | Source cannot be reached | Use fallback if within `maxStaleness`; otherwise deny |
| Stale | Last known value is older than `freshness` | Deny for time-sensitive actions; use for non-sensitive |

**Corrected evaluation algorithm:**

```
evaluate(actor, action, resource, facts, policy, time):

  requiredFacts = policy.requiredFacts(action, resource)
  
  # Step 1: Fact availability and freshness
  for each fact in requiredFacts:
    status = getFactStatus(fact, time)
    if status == Unavailable:
      if fact.fallbackPolicy.denyOnStale:
        return Deny(reason="Fact unavailable, no safe fallback")
      if time - status.asOf > fact.fallbackPolicy.maxStaleness:
        return Deny(reason="Fact stale beyond fallback window")
      if action ∉ fact.fallbackPolicy.allowedActions:
        return Deny(reason="Action not permitted with stale fact")
    if status == Stale:
      if fact.fallbackPolicy.denyOnStale:
        return Deny(reason="Fact stale")
      if action ∉ fact.fallbackPolicy.allowedActions:
        return Deny(reason="Action not permitted with stale fact")
    if status == Unknown:
      return Deny(reason="Required fact unknown")

  # Step 2: Mandatory conflict detection
  if hasConflictingMandatoryConstraints(policy, time):
    return Deny(reason="Policy configuration invalid")

  # Step 3: Mandatory prohibitions (hard block)
  if anyMandatoryProhibitionApplies(policy, facts, time):
    return Deny(reason="Mandatory prohibition")

  # Step 4: Mandatory conditions (must be satisfied)
  if not allMandatoryConditionsSatisfied(policy, facts, time):
    return Deny(reason="Mandatory condition not satisfied")

  # Step 5: Ordinary rules (decisive only)
  decisiveRules = { r ∈ OrdinaryRules
                    | r.scope applies
                    ∧ r.condition holds
                    ∧ r.effective(time)
                    ∧ r.effect ≠ abstain }

  if decisiveRules = ∅:
    return Deny(reason="No applicable grant")

  # Step 6: Priority resolution (only if policy category permits)
  if policy.category == "priority-based":
    maxPriority = max(r.priority for r ∈ decisiveRules)
    highest = { r ∈ decisiveRules | r.priority = maxPriority }
    if ∃ r ∈ highest with effect = deny:
      return Deny(reason="Highest-priority denial")
    if ∃ r ∈ highest with effect = permit:
      return Permit(reason="Applicable grant")

  if policy.category == "deny-wins":
    if ∃ r ∈ decisiveRules with effect = deny:
      return Deny(reason="Deny wins")
    return Permit(reason="Applicable grant")

  if policy.category == "permit-wins":
    if ∃ r ∈ decisiveRules with effect = permit:
      return Permit(reason="Permit wins")
    return Deny(reason="No applicable grant")

  return Deny(reason="Unresolved policy category")
```

**Key corrections over v0.3.1:**

1. **Fallback is per-fact and per-action**, not global.
2. **Stale facts are distinguished from unavailable facts.**
3. **Time-sensitive actions cannot use stale fallback.**
4. **Policy category is explicit** — priority-based, deny-wins, permit-wins — rather than assuming priority is universal.
5. **Unknown facts produce deny**, not silent assumption.

**Freshness contract examples:**

| Fact Type | Freshness | Fallback Window | Fallback Allowed For |
|---|---|---|---|
| Membership status | 5 min | 30 min | Read operations |
| Appointment status | 1 min | 5 min | Non-authoritative operations |
| Mandate validity | Immediate | 0 (no fallback) | None |
| Role assignment | 5 min | 15 min | Non-authoritative operations |
| Forum policy | 1 hour | 24 hours | Read access |
| Jurisdiction | 1 day | 7 days | Read access |

**Required tests:**

| Test | Scenario | Expected |
|---|---|---|
| AF-T1 | Appointment expires, source available | Deny |
| AF-T2 | Appointment expires, source unavailable, cache within window | Deny (mandate has no fallback) |
| AF-T3 | Membership status stale, read operation | Permit if within fallback window |
| AF-T4 | Membership status stale, write operation | Deny |
| AF-T5 | Conflicting mandatory constraints | Deny |
| AF-T6 | Priority tie between permit and deny | Deny (if deny-wins) or per policy |
| AF-T7 | Unknown fact | Deny |
| AF-T8 | Explicitly false fact | Deny |

### 2.2 Correction B — Event-History Algorithm (Formalized)

**Principle:** Transitions are events. States are intervals derived from events. Corrections and retractions are explicit events, not implicit state reversals.

**Event model:**

```
StateTransitionEvent {
  id
  subjectId
  fromState
  toState
  effectiveAt        // valid-time instant
  recordedAt         // transaction-time instant
  authority
  reason
  eventType          // transition | correction | retraction
  supersedesEventId  // for correction/retraction
  version            // monotonic per subject
}
```

**Event types:**

| Type | Meaning | Effect |
|---|---|---|
| Transition | Normal state change | Adds a new state interval |
| Correction | Retroactive change to a prior event's effective time or target state | Modifies the interpretation of prior events |
| Retraction | Event is declared invalid | Removes the event from effective history |

**Effective event history:**

```
effectiveHistory(subjectId, k) = 
  events = { e ∈ StateTransitionEvents
             | e.subjectId = subjectId
             ∧ e.recordedAt ≤ k }
  
  # Remove superseded events
  superseded = { e.supersedesEventId | e ∈ events ∧ e.supersedesEventId ≠ null }
  effective = { e ∈ events | e.id ∉ superseded }
  
  # Resolve corrections (latest correction wins for the same target)
  corrections = { e ∈ effective | e.eventType = correction }
  for each correction c:
    remove the corrected event from effective
    apply c's modification
  
  # Resolve retractions
  retractions = { e ∈ effective | e.eventType = retraction }
  for each retraction r:
    remove the retracted event from effective
  
  return sort(effective, by=(effectiveAt, version))
```

**State query:**

```
stateOf(subjectId, v, k):
  history = effectiveHistory(subjectId, k)
  applicable = { e ∈ history | e.effectiveAt ≤ v }
  if applicable = ∅: return Unknown
  return applicable.last().toState
```

**Invariants:**

| ID | Statement |
|---|---|
| ST-1 | ∀ event e, e.fromState ≠ e.toState (transitions only) |
| ST-2 | ∀ event e, (e.fromState, e.toState) is valid in the state machine |
| ST-3 | ∀ subject s, ∀ v, k, stateOf(s, v, k) is deterministic |
| ST-4 | Corrections and retractions never delete events |
| ST-5 | A correction that makes a later transition invalid flags the inconsistency |
| ST-6 | Simultaneous events are ordered by version |
| ST-7 | stateOf is monotonic in k for fixed v (knowledge only grows) |

**Edge cases:**

1. **Late entry:** Event effective on 1 Oct, recorded on 15 Oct. Queries with `k < 15 Oct` do not see it.
2. **Retroactive correction:** Correction recorded on 10 Oct changes a transition's effective time from 1 Oct to 5 Oct. Both events preserved.
3. **Retraction:** A transition is retracted. The retraction event references it. State queries after the retraction's `recordedAt` exclude the transition.
4. **Simultaneous transitions:** Ordered by version. The later version wins for state queries.
5. **Invalidating correction:** A correction makes a later transition invalid (e.g., Active → Approved). System flags inconsistency, requires human resolution.

**Required tests:**

| Test | Scenario | Expected |
|---|---|---|
| ST-T1 | Late entry | Query with `k < recordedAt` does not see event |
| ST-T2 | Retroactive correction | Both events preserved; state reflects correction |
| ST-T3 | Retraction | State reverts; original event preserved |
| ST-T4 | Simultaneous events | Deterministic ordering by version |
| ST-T5 | Invalidating correction | System flags inconsistency |
| ST-T6 | Bi-temporal query | Correct state for (v, k) combinations |
| ST-T7 | Monotonicity | More knowledge does not contradict prior state for same v |

### 2.3 Scope Correction — Election Artifacts Parked

The following artifacts are **explicitly deferred** and not part of the current validation:

| Artifact | Disposition |
|---|---|
| FrozenEligibilityRecord | Parked |
| InputSnapshot | Parked |
| EligibilityPolicyVersion | Parked |
| CorrectionOrAppealDetermination | Parked |
| Scenarios E-01 to E-06 | Parked |
| Voter roll machinery | Parked |

**Reusable question retained:** Can the platform preserve a historically reproducible view of organizational facts at a specified time? This is tested with ordinary organizational scenarios (CR-01 to CR-10 below).

### 2.4 Validation Scenarios CR-01 to CR-10

These are the organizational scenarios that validate the foundational model without election-specific concepts.

| ID | Scenario | What It Establishes |
|---|---|---|
| CR-01 | One person belongs to two organizations | Which facts are person-wide vs. organization-scoped |
| CR-02 | Membership is suspended or terminated | Which relationships and permissions change; which remain historically valid |
| CR-03 | Committee member's appointment expires | Whether participation and decision authority end together |
| CR-04 | Role removed, membership remains active | Whether role-derived permissions are revoked without membership change |
| CR-05 | Geographical boundary changes | How jurisdiction history affects committee responsibility and historical queries |
| CR-06 | Committee serves several geographical areas | Whether many-to-many jurisdiction is supported |
| CR-07 | Member loses forum access | Whether access depends on membership, committee participation, role, explicit grants, or combinations |
| CR-08 | User retains session after authority revoked | Whether authorization is reevaluated and revoked within freshness requirements |
| CR-09 | Relationship entered late or corrected retroactively | Whether valid-time and transaction-time queries return reproducible results |
| CR-10 | Two organizations have different committee rules | Whether the model represents genuine policy variation without weakening universal invariants |

**Scenario structure:** For each, we need a positive case, a negative case, a boundary case, and at least one counterexample. Where timing matters, we test both valid time and transaction time.

### 2.5 The v0.4 Validation Matrix

Each rule R-01 through R-13 is recorded with the following fields.

| Field | Description |
|---|---|
| RuleID | Stable traceability identifier (e.g., R-02-I-1) |
| RuleVersion | Current wording version |
| Proposition | Precise, testable business statement |
| Category | Structural / Temporal / Business / Configurable / Legal / Operational |
| Scope | Universal / Configurable / Organization-specific / Legal |
| Domain | The class of organizations or scenarios the rule governs |
| SourceEvidence | Document and section/page, or explicit rationale for technical invariants |
| EvidenceQuality | Observed / Derived / Hypothesis / Contested |
| CounterexampleID | Link to falsification scenario |
| CounterexampleVerdict | Rule holds / Rule scoped / Rule rejected / Pending |
| FormalInvariant | Logical or mathematical representation |
| OwnerContext | Provisional authoritative owner |
| TestID | Executable or planned test |
| Dependencies | Other rules and decisions |
| DecisionStatus | Open / Contested / Recommended / Approved / Rejected |
| DecisionAuthority | Required for approved decisions |

**Decision workflow:**

```
Open → Contested (if counterexample found)
     → Recommended (if evidence supports)
     → Approved (if ARB decides)
     → Rejected (if ARB decides against)
```

**Rule:** A rule cannot move to Approved without complete traceability, a recorded falsification attempt, and an ARB decision.

### 2.6 Evidence Collection Plan (Practical Starting Point)

The review correctly says: a blank matrix is not validation, and four documents do not automatically establish correctness. The starting point should be:

1. **One political party constitution or bylaws.**
2. **One membership-based association bylaws or statutes.**
3. **Committee and geographical-structure rules** applicable to those organizations.
4. **Membership, role/authority, and forum policies** where documented.

Federation agreements can be added when they test a specific unresolved relationship. A Red Cross source is not mandatory unless that organization type is part of the target domain.

**For every source-derived rule, record:**

| Field | Meaning |
|---|---|
| Source fact | What the document states |
| Interpretation | What we believe it means |
| Candidate platform rule | The behavior we propose to support |
| Validation evidence | Scenarios, counterexamples, tests |
| Decision | Approved, rejected, or unresolved |

This prevents mistaking our interpretation for the organization's actual rule.

### 2.7 Machine Learning and Formal Methods (v0.4 Scope)

| Technique | Application | Oversight |
|---|---|---|
| NLP / LLM | Extract candidate rules from statutes and policies | Human verification against source |
| Clustering | Identify different meanings behind similar terms | Human verification |
| Contradiction detection | Flag conflicting rules in policy sets | Human review |
| Anomaly detection | Flag unusual patterns in validated organizational data | Signal for investigation |
| Property-based testing | Generate combinations of memberships, appointments, time intervals, permissions | Automated with generators |
| Constraint solving | Detect contradictory policy configurations | Automated |
| Model checking | Verify lifecycle properties of membership, appointment, mandate | Applied selectively |
| Temporal logic | Check expiry, revocation, historical validity, correction semantics | Applied to time-bounded facts |
| Statistical analysis | Identify recurring patterns in validated cases | Not treated as proof of universal rule |

**ML must never decide:**
- Membership eligibility
- Appointment approval
- Mandate validity
- Authorization decisions
- Policy precedence
- Forum access

ML informs research. Humans and formal logic decide.

---

## 3. How Far We Are and What Remains

### 3.1 Current Maturity Assessment

| Area | Status |
|---|---|
| Conceptual foundation | Established as research baseline |
| Formal specification | Partially complete — temporal and authorization models need correction |
| Evidence and falsification | Not executed |
| Architecture and implementation | Not authorized by this research |

**Overall assessment:** The specification is now scope-corrected and the two remaining formal defects are being addressed. We are approximately **45–50%** of the way toward an implementable organizational domain model. This is a qualitative estimate, not a measured completion metric.

The remaining work is **evidence and falsification**, not further specification. Without real organizational documents, the model remains a hypothesis.

### 3.2 Remaining TODOs (Bullet Points)

**P0 — Correct and constrain the specification**
- Revise v0.3.1 into a scope-corrected validation baseline (v0.4).
- Adopt the corrected authorization algorithm with per-fact and per-action fallback.
- Define freshness contracts per fact type.
- Adopt the corrected event-history algorithm with correction-aware interpretation.
- Define handling of superseded events, retractions, late entries, retroactive corrections.
- Define detection and resolution of invalid downstream transitions.
- Keep election-specific artifacts and E-01 to E-06 outside the current scope.

**P0 — Validate domain relationships**
- Establish precise meanings of Person, Member, Membership.
- Determine whether affiliation, participation, employment, and committee appointment are separate types.
- Validate Committee vs. OrganizationalUnit and committee membership lifecycle.
- Clarify Role, Mandate, Permission, AuthorizationDecision relationships.
- Separate organizational hierarchy from geographical hierarchy.
- Define ForumAccessPolicy derivation from membership, appointments, roles, explicit grants.
- Identify universal platform invariants vs. organization-specific policies.

**P0 — Collect evidence and run falsification**
- Obtain one political party constitution or bylaws.
- Obtain one membership association bylaws or statutes.
- Obtain committee and geographical-structure rules.
- Obtain membership, role/authority, and forum policies.
- Populate the evidence matrix for R-01 through R-13.
- Record exact source locations; separate source fact from interpretation.
- Construct counterexamples for each candidate rule.
- Record verdict: rule holds, rule scoped, rule rejected.
- Link each validated rule to executable tests.

**P1 — Validate temporal and authorization semantics**
- Test historical queries with valid time and transaction time.
- Test membership suspension, termination, correction.
- Test appointment expiry, mandate changes, role revocation.
- Test geographical jurisdiction changes and historical interpretation.
- Test forum access after role or membership change, including active sessions and stale caches.
- Define audit evidence requirements.
- Use property-based tests and constraint checks.

**P1 — Establish governance for domain decisions**
- Define decision authority for platform-wide invariants vs. configurable policies.
- Apply Open → Contested → Recommended → Approved/Rejected workflow.
- Record unresolved questions explicitly.
- Define exploratory prototype boundaries.
- Maintain traceability from evidence to rule, test, decision.

**P2 — Derive architecture after validation**
- Group validated concepts by lifecycle ownership and responsibility.
- Derive candidate bounded contexts from validated groups.
- Identify aggregates from transactional invariants and concurrency.
- Validate context boundaries against at least two real scenarios each.
- Produce context map, integration contracts, domain events.
- Research identity scope and tenant isolation with explicit requirements.
- Prepare architecture decision package.

**P3 — Prepare implementation**
- Produce dependency-ordered implementation plan.
- Define domain, temporal, authorization, integration, tenant-isolation tests.
- Obtain explicit architecture and implementation authorization.
- Implement incrementally with traceability preserved.

### 3.3 Final Principal Recommendation

The next deliverable is the **Cross-Relationship Validation Matrix v0.4**, with:

1. The corrected authorization algorithm (per-fact and per-action fallback).
2. The corrected event-history algorithm (correction-aware interpretation).
3. Election artifacts parked.
4. Scenarios CR-01 through CR-10 as the validation instrument.
5. R-01 through R-13 populated with evidence fields.

The first milestone is to validate the highest-risk relationships:

1. Member ↔ Membership ↔ OrganizationalUnit
2. Committee ↔ Appointment ↔ Role ↔ Mandate
3. Role ↔ Permission ↔ AuthorizationDecision
4. OrganizationalUnit ↔ GeographicalJurisdiction
5. Membership/Appointment/Role ↔ ForumAccessPolicy

Only after these are validated against real organizational evidence should bounded contexts, aggregates, and identity/tenancy decisions be derived.

**Authorization required:** Provision of one political party constitution and one membership association bylaws to begin evidence collection and falsification.
