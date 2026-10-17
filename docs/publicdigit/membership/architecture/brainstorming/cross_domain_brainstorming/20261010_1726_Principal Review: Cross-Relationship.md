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
#
# Principal Review and Next-Step Execution: Cross-Relationship Architecture v0.4.4

## 1. Principal Reviewer Assessment

### 1.1 What This Review Contributes

The v0.4.3 review is the most disciplined artifact yet. It does not accept the previous document as a specification; it subjects it to independent scrutiny and finds **six new defects**. These are genuine formal defects, not stylistic preferences, and they must be corrected before any further progress.

The six findings:

1. **Evaluation time and transaction time are still conflated.** The v0.4.3 document assigned the transaction time `k` as the evaluation time for historical queries. This is wrong. `v`, `k`, and `t` answer three different questions, and they may coincide but need not.

2. **Correction chains can lose earlier corrections.** The v0.4.3 lineage model selects the terminal correction and applies it to the original transition. If a later correction only changes one field, the earlier correction's changes to other fields are lost. The model must choose between **patch semantics** and **complete replacement semantics**, and must not mix them.

3. **Bitemporal prefix validation is not fully isolated.** `effectiveHistory(subjectId, k)` validates correction lineages before filtering to valid time `v`. A lineage conflict involving a later transition can therefore prevent an earlier, otherwise-consistent query from returning a valid result.

4. **The initial-state contract needs temporal and identity safeguards.** The record contains `declaredAt` without a transaction-time field. A subject's initial state declared after the query's knowledge cutoff must not be used to reconstruct earlier states. Also, `NotCreated`, `Unknown`, and incomplete history must be distinguished.

5. **The fact fallback table is not yet a complete decision function.** `Unknown + Available` delegates to `absenceReason` but does not specify the consequences. `NoRecord` and `NotApplicable` are described as "usually false" but that is not universally safe. Provenance must establish source authority for the fact and organizational scope.

6. **The reference model and rule-status workflow need stronger controls.** A second implementation repeating the same assumptions can agree with production while both are wrong. Rule evidence status must be separated from approval status.

All six findings are **accepted**. They become the basis of v0.4.4.

### 1.2 Where I Strengthen the Review

The review is precise, but I identify five areas where it must be extended before v0.4.4 can be complete.

**Weakness 1 — The three-clock model is stated but not formalized.**
The review correctly distinguishes valid time `v`, transaction time `k`, and evaluation time `t`. It does not specify:
- The formal semantics of each.
- The query contracts that use each.
- How the three interact in decision reconstruction.

**Weakness 2 — The correction semantics choice is required but not decided.**
The review correctly requires choosing patch vs. replacement semantics. It does not specify:
- The consequences of each choice.
- The recommended choice.
- How to validate corrections in either model.

**Weakness 3 — The prefix validation contract is stated but not fully formalized.**
The review correctly requires separating stages, but does not specify:
- The precise stage boundaries.
- The result model when prefix and suffix have different consistency.
- How to surface later inconsistencies without contaminating the prefix result.

**Weakness 4 — The initial-state temporal contract is required but not specified.**
The review correctly requires a transaction-time field and a revision contract. It does not specify:
- The formal model.
- How initial state interacts with corrections.
- How to distinguish `NotCreated`, `Unknown`, and `IncompleteHistory`.

**Weakness 5 — The fallback decision function is required to be total but not specified.**
The review correctly requires a total function with explicit outcomes. It does not specify:
- The full outcome set.
- The reason code taxonomy.
- The interaction with provenance.

These weaknesses are addressed in v0.4.4.

---

## 2. Next-Step Deliverable: Cross-Relationship Architecture v0.4.4

The next artifact is a **formal correction package**. It does not add new domain concepts. It corrects the six defects identified by the review and resolves the five weaknesses I identified.

### 2.1 Correction 1 — The Three-Clock Model (Formalized)

**Principle:** Valid time, transaction time, and evaluation time answer three different questions and must be modeled separately.

**Clock semantics:**

| Clock | Symbol | Question | Scope |
|---|---|---|---|
| Valid time | `v` | When was the fact true in the domain? | Per fact |
| Transaction time | `k` | What had the system recorded by this time? | Per system record |
| Evaluation time | `t` | At what moment is a decision being evaluated? | Per decision |

**Query contracts:**

| Query | Clocks Used | Purpose |
|---|---|---|
| `StateAt(s, v, k)` | `v`, `k` | Reconstruct the state of `s` at valid time `v`, using records known by transaction time `k` |
| `DecisionAt(actor, action, resource, t)` | `t` | Evaluate authorization as of time `t`, using facts known at `t` |
| `ReconstructDecision(decisionId, k)` | `k`, `t` | Reconstruct a past decision using only records known at `k`, evaluated as if at `t` |

**Freshness evaluation:**

Freshness is computed against the **evaluation time** `t`, not wall-clock or transaction time:

```
freshness(observation, t):
  age = t - observation.observationTime
  ...
```

For a real-time authorization decision, `t = now()`.
For a historical reconstruction, `t` is the moment the original decision was made — **not** `k`.
For a hypothetical ("what would have been allowed?"), `t` is explicit.

**Key distinction:** `k` is a knowledge cutoff. `t` is an evaluation moment. They may coincide, but they need not.

**Decision reconstruction algorithm:**

```
ReconstructDecision(decisionId, k):
  # Step 1: Retrieve the decision record
  decision = getDecision(decisionId)

  # Step 2: Determine the evaluation time
  t = decision.evaluatedAt

  # Step 3: Recompute using only records known at k
  facts = getFactsAsOf(decision.subjectId, k)
  policy = getPolicyVersion(decision.policyVersion)

  # Step 4: Evaluate with explicit evaluation time t
  return evaluate(decision.actor, decision.action, decision.resource, facts, policy, t)
```

**Required tests:**

| Test | Scenario | Expected |
|---|---|---|
| TC-T1 | Real-time decision | `t = now()`, uses current facts |
| TC-T2 | Historical reconstruction | `t` = original evaluation time, `k` = knowledge cutoff |
| TC-T3 | Late-recorded fact | `t` before recording; fact not visible |
| TC-T4 | Hypothetical decision | `t` explicit; facts as of `t` |

### 2.2 Correction 2 — Correction Semantics (Chosen)

**Principle:** Corrections must have **one** semantic. The model chooses **complete replacement revisions** over patch semantics, because it is simpler to validate and avoids ordering ambiguity.

**Chosen model: Complete Replacement Revisions**

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
  replacement: {           // complete revised transition content
    fromState
    toState
    effectiveAt
    reason
  }
  recordedAt
  authority
  supersedesCorrectionId (nullable)
}
```

**Key rules:**

| Rule | Statement |
|---|---|
| CS-1 | A correction contains the complete revised content, not a patch |
| CS-2 | The correction replaces the target transition entirely (for interpretation purposes) |
| CS-3 | The original transition record remains immutable |
| CS-4 | A correction's `replacement` must be self-consistent |
| CS-5 | Correction chains form a single lineage: at most one root, at most one successor per node |

**Lineage selection:**

```
effectiveTransition(transitionId, corrections, k):
  chain = { c ∈ corrections
            | c.targetTransitionId = transitionId
            ∧ c.recordedAt ≤ k }
  if chain = ∅: return originalTransition(transitionId)

  graph = buildLineageGraph(chain)

  # Validate lineage
  if hasCycle(graph): return InconsistentLineage(reason="cycle")
  roots = { c | c.supersedesCorrectionId = null }
  if |roots| ≠ 1: return InconsistentLineage(reason="multiple roots")
  terminals = { c | no successor }
  if |terminals| ≠ 1: return InconsistentLineage(reason="multiple terminals")

  terminal = terminals[0]
  return terminal.replacement
```

**Advantages of replacement over patch:**

| Aspect | Replacement | Patch |
|---|---|---|
| Ordering ambiguity | None (terminal wins) | Sensitive to order |
| Field conflicts | Not possible | Possible |
| Validation complexity | Lower | Higher |
| Audit clarity | Higher | Lower |

**Required tests:**

| Test | Scenario | Expected |
|---|---|---|
| CS-T1 | Single correction, single field | Full replacement applied |
| CS-T2 | Correction chain, one field each | Terminal replacement wins |
| CS-T3 | Multiple roots | InconsistentLineage |
| CS-T4 | Cycle in lineage | InconsistentLineage |
| CS-T5 | No corrections | Original transition used |

### 2.3 Correction 3 — Bitemporal Query with Prefix Isolation

**Principle:** Prefix validation must be independent of suffix consistency. A later inconsistency must not prevent an earlier valid query.

**Revised algorithm:**

```
StateAt(subjectId, v, k):
  # Stage 1: Select records known at k
  transitions = { t | t.subjectId = subjectId ∧ t.recordedAt ≤ k }
  corrections = { c | c.subjectId = subjectId ∧ c.recordedAt ≤ k }
  retractions = { r | r.subjectId = subjectId ∧ r.recordedAt ≤ k }

  # Stage 2: Resolve effective revisions for ALL transitions
  resolved = {}
  for t in transitions:
    eff = effectiveTransition(t.id, corrections, k)
    if eff is InconsistentLineage:
      resolved[t.id] = { status: inconsistent, reason: eff.reason }
    else:
      resolved[t.id] = { status: ok, transition: eff }

  # Stage 3: Apply retractions
  for r in retractions:
    if r.targetTransitionId ∈ resolved:
      resolved[r.targetTransitionId] = { status: retracted }

  # Stage 4: Identify the timeline relevant to valid time v
  prefixTransitions = { t ∈ resolved | status = ok ∧ t.transition.effectiveAt ≤ v }
  suffixTransitions = { t ∈ resolved | status = ok ∧ t.transition.effectiveAt > v }

  # Stage 5: Validate the prefix INDEPENDENTLY
  prefixValidation = validateSequence(
    sort(prefixTransitions, by=(effectiveAt, version)),
    initialState(subjectId)
  )

  # Stage 6: Report later inconsistencies separately
  laterInconsistencies = {
    t ∈ resolved | status = inconsistent ∧ (t is in suffix or affects suffix)
  }

  # Stage 7: Return the result
  if prefixValidation.status == invalid:
    return InconsistentHistory(
      reason = prefixValidation.reason,
      invalid = prefixValidation.invalid,
      scope = "prefix"
    )

  return {
    state: prefixValidation.finalState,
    validAt: v,
    knownAt: k,
    laterInconsistencies: laterInconsistencies
  }
```

**Key correction:** Prefix validation uses only `prefixTransitions`. Later inconsistencies are reported separately and do **not** affect the prefix result.

**Result model:**

```
StateResult {
  state: State | NotCreated | Inconsistent
  validAt: timestamp
  knownAt: timestamp
  prefixInconsistencies: [InconsistencyRecord]  // must be empty for a valid result
  laterInconsistencies: [InconsistencyRecord]   // reported but do not affect state
  reason: string  // if state is Inconsistent
}
```

### 2.4 Correction 4 — Initial-State Temporal Contract

**Principle:** Initial state is a declaration with its own transaction time and authority. It must not be silently projected backward from a later declaration.

**Formal model:**

```
InitialStateRecord {
  subjectId
  subjectType
  initialState
  declaredAt              // transaction time: when the system recorded this declaration
  validFrom               // valid time: when the initial state was true
  authority
  reason
  version
}
```

**Query integration:**

```
initialState(subjectId, k):
  records = { r | r.subjectId = subjectId ∧ r.declaredAt ≤ k }
  if records = ∅: return Unknown
  return latest(records, by=(declaredAt, version))
```

**Distinct result cases:**

| Case | Condition | Result |
|---|---|---|
| NotCreated | No transitions, initial state = NotCreated, valid at v | `NotCreated` |
| ExistingButNoTransitions | Initial state known, but no transitions | `initialState` |
| IncompleteHistory | No initial state declared, but transitions exist | `IncompleteHistory` |
| Unknown | Cannot determine | `Unknown` |
| Inconsistent | Transitions violate initial state | `Inconsistent` |

**Correction interaction:**

- If initial state is corrected, a new `InitialStateRecord` is recorded.
- Earlier queries use the earlier record (via `declaredAt ≤ k`).
- Later queries use the corrected record.
- A correction cannot retroactively change what was known earlier.

**Required tests:**

| Test | Scenario | Expected |
|---|---|---|
| IS-T1 | No transitions, no initial state | Unknown |
| IS-T2 | Initial state declared, no transitions | Initial state |
| IS-T3 | Transitions exist, no initial state | IncompleteHistory |
| IS-T4 | Initial state declared after k | Not visible at k |
| IS-T5 | Initial state corrected | Earlier queries use original |

### 2.5 Correction 5 — Fallback Decision Function (Total)

**Principle:** The fallback function must be total. Every combination of inputs produces one of a fixed set of outcomes with a reason code.

**Outcome set:**

```
DecisionOutcome:
  UseAuthoritativeFact    // fact is fresh and available
  UseFallback             // fact is stale or cached; policy permits use
  Deny                    // fact unavailable/stale and policy prohibits use
  Indeterminate           // fact's absence is ambiguous; cannot safely decide
  OperationalError        // source unavailable and no safe fallback
```

**Reason codes:**

| Code | Meaning |
|---|---|
| FRESH_AUTHORITATIVE | Fact is fresh and from an authoritative source |
| FALLBACK_PERMITTED | Stale/unavailable but policy permits fallback |
| FALLBACK_DENIED | Stale/unavailable and policy denies fallback |
| ABSENCE_EXPLICIT_FALSE | Absence is an explicit business fact |
| ABSENCE_NOT_APPLICABLE | Fact type does not apply to subject |
| ABSENCE_NO_RECORD | No record exists; interpretation depends on policy |
| ABSENCE_UNRETRIEVABLE | Source could not be queried |
| PROVENANCE_MISMATCH | Source is not authoritative for this fact/scope |
| ACTION_NOT_PERMITTED | Action not in fallback allow-list |
| AUTHORITY_NOT_FRESH | Authority-sensitive action requires fresh authority |

**Total decision function:**

```
fallbackDecision(observation, action, t):
  # Step 1: Provenance check
  if not isAuthoritative(observation.provenance, observation.factType, observation.organizationScope):
    return { outcome: OperationalError, reason: PROVENANCE_MISMATCH }

  # Step 2: Absence handling
  if observation.observedValue == Unknown:
    if observation.sourceAvailability == Available:
      return absenceDecision(observation.absenceReason, action, t)
    else:
      return { outcome: OperationalError, reason: ABSENCE_UNRETRIEVABLE }

  # Step 3: Freshness evaluation
  f = freshness(observation, t)

  # Step 4: Authoritative available and fresh
  if observation.sourceAvailability == Available and f == Fresh:
    return { outcome: UseAuthoritativeFact, reason: FRESH_AUTHORITATIVE }

  # Step 5: Fallback path
  age = t - observation.observationTime

  if age > observation.freshnessPolicy.maxStaleness:
    return { outcome: Deny, reason: FALLBACK_DENIED }

  if action ∉ observation.freshnessPolicy.allowedFallbackActions:
    return { outcome: Deny, reason: ACTION_NOT_PERMITTED }

  if observation.freshnessPolicy.denyOnStale and f == Stale:
    return { outcome: Deny, reason: FALLBACK_DENIED }

  if observation.freshnessPolicy.requireFreshAuthority and f != Fresh:
    return { outcome: Deny, reason: AUTHORITY_NOT_FRESH }

  return { outcome: UseFallback, reason: FALLBACK_PERMITTED }
```

**Absence decision (fully specified):**

```
absenceDecision(absenceReason, action, t):
  switch absenceReason:
    case ExplicitAbsence:
      return { outcome: UseAuthoritativeFact, reason: ABSENCE_EXPLICIT_FALSE }
    case NotApplicable:
      return { outcome: UseAuthoritativeFact, reason: ABSENCE_NOT_APPLICABLE }
    case NoRecord:
      # Interpretation depends on action sensitivity
      if isSensitiveAction(action):
        return { outcome: Indeterminate, reason: ABSENCE_NO_RECORD }
      return { outcome: UseAuthoritativeFact, reason: ABSENCE_NO_RECORD }
    case Unretrievable:
      return { outcome: OperationalError, reason: ABSENCE_UNRETRIEVABLE }
```

**Key correction:** `NoRecord` does **not** automatically mean false. For sensitive actions, absence of a record produces `Indeterminate`, which requires human review.

### 2.6 Correction 6 — Reference Model and Rule-Status Controls

**Reference model controls:**

The reference model must be:
- **Independently derived** — written from the specification, not from production code.
- **Simpler** — using straightforward data structures and algorithms.
- **Hand-verified** — with manually checked expected outcomes for representative cases.
- **Property-tested** — with generators that produce adversarial inputs.

**Properties to verify (corrected):**

| Property | Statement |
|---|---|
| P-REPRO | Same inputs → same outputs |
| P-CORR | Corrections preserve prior records |
| P-RETRACT | Retractions remove transitions from effective history |
| P-PREFIX | Prefix validation is independent of later inconsistencies |
| P-UNIQUE | Valid ordering keys are unique; duplicate keys are rejected |
| P-FALLBACK | Fallback follows the total decision function |
| P-THREE-CLOCKS | `v`, `k`, and `t` are independently parameterizable |

**Rule-status workflow (separated):**

Two independent status dimensions:

| Dimension | Values | Meaning |
|---|---|---|
| **Evidence status** | Hypothesis | No source; inferred |
| | Evidence-supported | Source found; supports the rule |
| | Contested | Source found; counterexample exists |
| | Unsupported | No source; counterexample exists |
| **Approval status** | Open | No decision |
| | Recommended | ARB review pending |
| | Approved | ARB accepted |
| | Rejected | ARB rejected |

A rule's evidence status is independent of its approval status. A rule may be evidence-supported but not yet approved, or approved but later contested.

**Rule record:**

```
RuleRecord {
  ruleId
  version
  proposition
  scope
  domain
  evidenceStatus
  evidenceRefs: [SourceCitation]
  approvalStatus
  approvalAuthority
  approvalDate
  counterexamples: [CounterexampleRecord]
  effectiveFrom
  reviewTrigger              // conditions that require re-review
  dependencies: [RuleId]
}
```

---

## 3. DDD Assessment — What Architecture Should Emerge

The v0.4.3 review correctly states that bounded contexts must emerge from authority, business meaning, lifecycle, invariants, and consistency cost — not from nouns.

**Working hypothesis (to be validated):**

| Candidate context | Owns | Does not own |
|---|---|---|
| Identity & Access | Account, authentication identity; Person identity pending resolution | Membership, Appointment, Mandate, Policy |
| Organization | Organization, OrganizationalUnit, structural hierarchy | Committee governance, Membership lifecycle |
| Membership | Member, Membership, MembershipType | Person identity, Appointment, Role |
| Governance | Committee, CommitteeAppointment, Role, Mandate | Membership lifecycle, generic authorization |
| Authorization | Decision evaluation, PolicyRule | Any fact it evaluates |
| Geography | GeographicJurisdiction, hierarchy | Committee assignment (owned by Governance) |
| Discussion Forum | Forum, Topic, Post, Moderation, ForumAccessPolicy | Membership, Appointment, Mandate |
| Audit & History | Recorded actions, reconstructable history | Current authoritative state |

**Important architectural constraint:**
- These are responsibilities, not deployment units.
- A modular monolith may implement multiple contexts.
- The number of deployment units should follow operational needs, not concept count.
- No fact may have two authoritative owners.
- Projections are permitted with freshness and revocation contracts.

---

## 4. Execution Sequence (v0.4.4 → Implementation)

| Step | Deliverable | Depends On |
|---|---|---|
| 1 | v0.4.4 Formal Semantics Correction | This review |
| 2 | Independent Reference Model | Step 1 |
| 3 | Group 1 Validation (Membership) | Step 2 + organizational evidence |
| 4 | Group 2 Validation (Committee Authority) | Step 2 + organizational evidence |
| 5 | Ownership Boundary Resolution | Steps 3, 4 |
| 6 | Group 3–5 Validation | Steps 3, 4, 5 |
| 7 | Bounded Context Derivation | Steps 3–6 |
| 8 | Aggregate & Context Map | Step 7 |
| 9 | Tenant & Identity Decisions | Steps 3–8 |
| 10 | Implementation Plan | Steps 7–9 |
| 11 | ARB Architecture Approval | Step 10 |
| 12 | ARB Implementation Authorization | Step 11 |
| 13 | Incremental Implementation | Step 12 |

---

## 5. How Far We Are from the Implementation Goal

### 5.1 Maturity Assessment

| Area | Status |
|---|---|
| Domain concepts and candidate relationships | Research baseline established |
| Formal logic and temporal specification | In progress; v0.4.3 defects identified; v0.4.4 corrections drafted |
| Membership and committee-authority rules | Candidate scenarios defined; organizational validation outstanding |
| Evidence and counterexample analysis | Not demonstrated |
| Authoritative context ownership | Proposed, not approved |
| Bounded contexts and aggregates | Not established |
| Cross-domain implementation readiness | Not established |

**Overall assessment:** We have progressed beyond brainstorming. We have a candidate semantic model and a method for challenging it. But we have not yet demonstrated that the model correctly represents the rules of real organizations.

Progress is approximately **45–50%** toward an implementable organizational domain model. This is a qualitative estimate. The next measurable milestone is a formally consistent, independently tested, evidence-supported model of membership and committee authority.

### 5.2 Remaining TODOs (Bullet Points)

**P0 — Formal specification (v0.4.4)**
- Adopt the three-clock model (valid time, transaction time, evaluation time).
- Adopt complete replacement revision semantics for corrections.
- Adopt bitemporal query with prefix isolation.
- Adopt the initial-state temporal contract with `declaredAt` and versioning.
- Adopt the total fallback decision function with reason codes.
- Adopt the separated evidence-status and approval-status dimensions.
- Correct the reference model controls and property list.
- Reject duplicate ordering keys explicitly.
- Distinguish `NotCreated`, `Unknown`, `IncompleteHistory`, `Inconsistent`.

**P0 — Independent reference model**
- Build a hand-verified reference implementation.
- Create expected outcomes for representative cases.
- Apply property-based testing with adversarial generators.
- Verify three-clock semantics, correction chains, retractions, initial states, freshness, bitemporal queries.
- Use constraint solving to detect contradictory policies.
- Surface inconsistencies explicitly.

**P0 — Validate Group 1 (Member, Membership, Organization)**
- Obtain one political party constitution or bylaws.
- Obtain one membership association bylaws or statutes.
- Define Person, Account, Member, Membership using real cases.
- Test multi-organization membership.
- Test local vs. organization-wide suspension.
- Test activation, termination, transfer, retroactive correction.
- Link each rule to source evidence, counterexamples, test IDs.
- Record evidence status and approval status separately.

**P0 — Validate Group 2 (Committee, Appointment, Role, Mandate)**
- Define appointment, participation, role, mandate, grant, authorization separately.
- Establish whether mandate is required for each protected action.
- Test mandate expiry with session persistence and unavailable sources.
- Test role revocation independently of membership.
- Test wrong-committee scope, missing grants, mandatory prohibitions.
- Link each rule to evidence.

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
- Role ↔ Permission ↔ AuthorizationDecision.
- Geography ↔ OrganizationalUnit ↔ Committee.
- Forum ↔ Membership/Role ↔ Access.
- Define read, write, moderation, administration separately.
- Specify projection freshness, revocation, behavior during outages.

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

### 5.3 Final Recommendation

The next step is v0.4.4: Formal Semantics Correction, containing:

1. The three-clock model.
2. Complete replacement revision semantics for corrections.
3. Bitemporal query with prefix isolation.
4. Initial-state temporal contract.
5. Total fallback decision function.
6. Separated evidence and approval statuses.
7. Corrected reference model controls.

The most urgent technical issues are:
- Correction-lineage semantics (patch vs. replacement).
- Bitemporal prefix isolation.
- The three-clock model.

The most urgent DDD issue is ownership boundary resolution, particularly Person/Account/Member/Membership.

Do not derive bounded contexts or begin broad implementation until:
1. Formal semantics are corrected (v0.4.4).
2. Reference model validates the algorithms.
3. Groups 1 and 2 are validated against real evidence.
4. Ownership boundaries are approved.

**Authorization required:** Provision of one political party constitution and one membership association bylaws to begin evidence collection and falsification of Groups 1 and 2.