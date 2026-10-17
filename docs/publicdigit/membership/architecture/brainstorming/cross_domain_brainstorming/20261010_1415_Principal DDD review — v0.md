# Principal DDD review — v0.3 Formal Consistency and Requirements Traceability

I have reviewed the new attachment, including its proposed Rule and Evidence Matrix for R-01 through R-13. The document has progressed from conceptual modeling toward a testable requirements specification, and it correctly identifies evidence collection and falsification as the next major research activities.&#x20;

Eingefügtes Markdown.md



My responsibility as principal reviewer is to challenge the model before it becomes an implementation contract. I found several important issues that should be resolved before we declare the formal specification complete.

My recommendation is to retain v0.3 as a research baseline, create a focused v0.3.1 correction set, and then execute the evidence-and-falsification work. We should not restart the conceptual discussion or jump to bounded contexts yet.

## 1. What is established—and what is not

| Area                                           | Current assessment                                                                              |
| ---------------------------------------------- | ----------------------------------------------------------------------------------------------- |
| Relationship catalogue R-01–R-13               | Useful candidate model; not fully validated                                                     |
| Four person-to-organization relationship types | Clearer semantics; coexistence and ownership still need evidence                                |
| Bi-temporal fact model                         | Two time dimensions defined; state-transition semantics need correction                         |
| Policy evaluation                              | Abstention handling improved; mandatory-permit behavior remains unsafe                          |
| Frozen election eligibility                    | Historical reproducibility is recognized; correction, privacy and dispute rules need refinement |
| Rule and evidence matrix                       | Schema and candidate entries exist; evidence is largely missing                                 |
| Bounded contexts and aggregates                | Correctly deferred                                                                              |
| Identity, tenancy and implementation           | Open; no approval should be inferred                                                            |

The attachment reports that none of the 35 listed rules is approved and that evidence collection and falsification have not yet been executed. This is the important status—not the estimated 40–45% progress figure, which is qualitative.&#x20;

Eingefügtes Markdown.md



## 2. Critical correction A: Mandatory constraints must not grant authorization by themselves

The v0.3 policy algorithm separates mandatory constraints from ordinary rules. That is a useful distinction, but its current semantics contain a security defect.

The document returns `Permit` when an applicable mandatory permit exists.&#x20;

Eingefügtes Markdown.md



Consider this case:

- A mandatory constraint says that an action is permitted for a certain class of actors.
- The actor has no ordinary grant authorizing the requested action.
- The mandatory constraint matches.
- The algorithm returns `Permit` immediately.

A constraint that says an action is not prohibited by that constraint should not necessarily grant the actor the authority to perform it.

### Proposed correction

Separate constraints from grants.

- A mandatory prohibition blocks an action.
- A mandatory requirement can impose conditions that must be satisfied.
- A grant or permit rule supplies the positive authorization basis.
- An ordinary deny can restrict access according to the approved precedence model.
- Missing required facts, unresolved mandatory constraints or evaluation failures must not produce an accidental permit.

A safer conceptual model is:

```
evaluate(actor, action, resource, facts, policy, time):

    if requiredFactsUnavailable:
        return Deny("Required facts unavailable")

    if mandatoryConstraintConflict:
        return Deny("Policy configuration invalid")

    if anyMandatoryProhibitionApplies:
        return Deny("Mandatory constraint")

    if not allMandatoryConditionsSatisfied:
        return Deny("Mandatory condition not satisfied")

    decisiveRules = applicableOrdinaryRules
                    excluding abstain

    if decisiveRules is empty:
        return Deny("No applicable grant")

    highest = rules with maximum priority

    if any rule in highest is deny:
        return Deny("Highest-priority denial")

    if any rule in highest is permit:
        return Permit("Applicable grant")

    return Deny("No applicable grant")
```

This is a candidate algorithm, not a universal legal policy. Some environments may need a different decision model, but the architecture must never confuse a constraint with a grant.

Required tests: mandatory prohibition versus ordinary permit; mandatory condition with missing facts; mandatory permit without a grant; conflicting mandatory constraints; equal-priority permit and deny; high-priority abstention with lower-priority permit.

## 3. Critical correction B: State transitions are events, not state intervals

The attachment models a `StateTransition` with `vtStart`, `vtEnd`, `ttStart` and `ttEnd`, then defines `stateOf(factId, v, k)` by selecting the transition whose valid-time interval contains \\(v\\).&#x20;

Eingefügtes Markdown.md



This representation confuses two things:

- A transition event occurs at a particular effective instant.
- A state holds during a period.

For example, a membership might transition from `Applied` to `Approved` on 1 October and from `Approved` to `Active` on 3 October. The first transition did not remain active until 3 October; it occurred on 1 October.

### Recommended model

Represent state changes as bi-temporal events, then derive state intervals from the ordered transition history.

```
StateTransition
  id
  subjectId
  fromState
  toState
  effectiveAt
  recordedAt
  authority
  reason
  supersedesTransitionId? 
```

The precise storage of transaction-time corrections must be specified. The important semantic distinction is that the transition has an effective instant, while the derived state has a duration.

For an initial implementation, we can model transitions with:

- valid-time event instant;
- transaction-time version interval;
- a unique event identity;
- explicit correction or supersession semantics.

Then define:

\\[ State(s,v,k)= \text{state derived from transitions for }s \text{ valid at }v\text{ as known at }k \\]

This requires a deterministic ordering rule for transitions with the same effective time, as well as validation of legal state-machine transitions.

### Required edge cases

1. A transition is entered late but is effective earlier.
2. A transition is corrected retroactively.
3. Two transitions have the same effective instant.
4. A transition is retracted or invalidated.
5. A correction makes a later transition invalid under the state machine.

We should test these cases before committing to a database representation. A bi-temporal table alone does not guarantee a correct historical state machine.

## 4. Critical correction C: Frozen election records need controlled correction semantics

The attachment correctly identifies that election eligibility may require an immutable historical record.&#x20;

Eingefügtes Markdown.md



I support the principle that later changes to membership or jurisdiction must not silently rewrite a past electorate. However, immutability must not prevent legitimate corrections or appeals.

I recommend distinguishing four artifacts:

1\. Eligibility policy version

The rules approved for the election and the period during which they apply.

2\. Input snapshot or reproducible evidence package

The minimum necessary source facts, source versions, timestamps and references used to compute eligibility.

3\. Frozen eligibility determination

The approved voter-roll version, its result, its computation metadata and the authority that froze it.

4\. Subsequent correction or appeal determination

A new, linked decision that preserves the earlier determination and records the outcome, reason, authority and effective scope of the correction.

The fourth artifact must not silently mutate the third. It should create an auditable successor or amendment with explicit rules about whether it affects the current election.

### Decisions still required

- Does a correction change eligibility only before the election, or can it also affect post-election review?
- Who may authorize a new voter-roll version?
- Is a new version a complete replacement or a narrowly scoped amendment?
- How is a disputed eligibility decision recorded?
- Which data must be retained to reproduce a decision, and which personal data should not be copied into the election record?
- How is the original decision preserved when a correction is accepted?

The exact legal and procedural answers depend on the relevant election rules. They cannot be inferred from the data model alone.

## 5. Critical correction D: Improve the evidence and falsification method

The matrix is the right instrument, but its current rules need a more precise evidence model. The attachment requires source evidence for every rule and proposes that a legitimate counterexample can force a universal rule to be downgraded to configurable.&#x20;

Eingefügtes Markdown.md



Two corrections are needed.

### 5.1 Not every rule requires the same kind of evidence

A mathematical invariant, a business convention, an organizational policy and a legal obligation are different kinds of propositions.

| Rule category           | Example                                          | Appropriate evidence                                      |
| ----------------------- | ------------------------------------------------ | --------------------------------------------------------- |
| Structural invariant    | A relationship must reference an existing entity | Domain specification and integrity tests                  |
| Temporal invariant      | A non-empty interval must have a valid ordering  | Formal definition and boundary tests                      |
| Business rule           | A membership requires approval before activation | Bylaws, policy or authorized stakeholder decision         |
| Configurable rule       | Members may belong to multiple units             | Organizational scenarios and policy evidence              |
| Legal constraint        | A statutory condition applies to a decision      | Applicable legal source and qualified interpretation      |
| Operational requirement | Access must be revoked within a defined time     | Risk analysis, security requirements and acceptance tests |

For example, a database relationship requiring a valid reference does not need a constitution to establish that its implementation must preserve referential integrity. But the decision that an appointment requires membership does need domain evidence.

### 5.2 A counterexample must be evaluated against the intended platform contract

A counterexample from one organization does not automatically invalidate a rule that the platform intentionally enforces for a particular class of organizations.

The correct procedure is:

1. State the rule and the domain over which it claims to apply.
2. Construct a counterexample.
3. Determine whether the counterexample is legitimate within that domain.
4. Identify whether the difference is a configurable policy, a separate relationship type or a genuine model defect.
5. Revise the rule or narrow its scope.
6. Record the decision and add a regression test.

The aim is not to make every organization configurable in every possible way. Excessive configurability creates an untestable product. The aim is to find the smallest stable set of universal invariants while representing genuine organizational differences explicitly.

## 6. Next execution step: Make the matrix operational

The current matrix is populated with candidate statements and counterexamples, but most verdicts are pending evidence. It is therefore a research backlog, not yet a validated requirements baseline.&#x20;

Eingefügtes Markdown.md



I recommend extending every matrix entry with these fields:

| Field               | Purpose                                                                                    |
| ------------------- | ------------------------------------------------------------------------------------------ |
| `RuleID`            | Stable traceability identifier                                                             |
| `RuleVersion`       | Identifies the current wording                                                             |
| `Proposition`       | Precise, testable business statement                                                       |
| `Scope`             | Universal, configurable, organization-specific or legal                                    |
| `EvidenceRef`       | Source document and exact section/page, or an explicit rationale for a technical invariant |
| `EvidenceQuality`   | Observed, derived, hypothesis or contested                                                 |
| `CounterexampleID`  | Link to the falsification scenario                                                         |
| `OwnerContext`      | Provisional authoritative owner                                                            |
| `FormalInvariant`   | Logical or mathematical representation where useful                                        |
| `TestID`            | Executable or planned test                                                                 |
| `DecisionStatus`    | Open, contested, recommended, approved or rejected                                         |
| `DecisionAuthority` | Required for approved decisions                                                            |
| `Dependencies`      | Other rules and decisions on which this rule depends                                       |

The traceability chain becomes:

Source evidence

Business rule and scope

Counterexample and formal invariant

Test and review result

Authorized decision

A rule should not become an approved business requirement merely because a test passes. The test can prove that the implementation follows the rule; the evidence and decision process establish whether the rule is appropriate.

## 7. Election-relevant validation: The next practical scenarios

The platform's foundational relationships must support elections without making the organizational model election-specific. I would validate these scenarios next.

[SPF Seniorerna - Medlemsregistret](https://images.openai.com/static-rsc-4/nH04sFknj04d-U6n7P2E4wAaRsH5DEG2nkHOnn-IXF2LURZ_AjMKPJ6DSjCHh9iGc-yvolJzdsMTmYj04MqhMazd2PPmLNDHITGVu6ZN6xN7UU34MAD2rPyPCu6sSkb4fU6QG1hkJwn8i__RQUAe5GD8jbKnNmv_RJdZAKq0SDQ?purpose=inline)

Scenario E-01 — Voter-roll eligibility

Given membership facts, an eligibility policy version and a defined cutoff time, calculate the eligible electorate and preserve the evidence needed to reproduce the result.

Tests: membership lifecycle, temporal query, policy versioning, frozen determination.

[Comissão organizadora do 6º Congresso Estadual da FETQUIM se reúne para definir formato do evento - FETQUIM - CUT/SP](https://images.openai.com/static-rsc-4/JnJTF4yiTdZYnvKvLsTGt2XuQ2Zh-KI-V5bOVYxxpfWglAUtd1Di_5kwMFPvQBr0nPyF15z2qJ4xIN24KTeVUZ_qLjTluQ05I4ugOhGZiwS0x6LoRp6b0-IkE3RhfMZ2aCHvHrFVuw8VikDhM6ZRk3WhPxcZ9ILX1jsxkcdb3IU?purpose=inline)

Scenario E-02 — Election administration authority

A committee member attempts to approve an election step after their mandate expires. The system must evaluate authority at the relevant decision time, not merely check whether the person once held the role.

Tests: appointment, role assignment, mandate validity, authorization and audit evidence.

[Mapy a kartogramy v publikacích | Ústecký kraj](https://images.openai.com/static-rsc-4/U7dsn1eCY6yrjF1BoseWp48vpaCGDhMPwki1wCwyOt3W6vI_zETN0hvosuTJLmZbJHQZkeIYs0qgiPVIyM_5ZSdbM6m3OEB4TlkggQ5gSvYyy7BkgNo2EN91Fs83Kcrl1XeskH2CNvGtAk4qTvbB2w5AidBKSdAO65-0binS0bQ?purpose=inline)

Scenario E-03 — Jurisdiction change

A geographical boundary changes after the voter roll is frozen. The system must preserve the election's original determination and apply explicit rules to any subsequent correction or appeal.

Tests: jurisdiction history, temporal queries, snapshot immutability, dispute handling.

[Axiell lance sa communauté en ligne dédiée à la gestion des collections pour ses utilisateurs - Axiell France](https://images.openai.com/static-rsc-4/7v42E3dsOkLM9xUA1dN1EKsY4iZ_ymkAf7pkD8zXDP-ziFey5og5GB-SeqzINUL6GyDvk5OlJ0fRNjl1XMWqD5b6KSLf_c-ARr2V_Mo7kxNHhGewB7bkarLvMyZJHUU559B8Kk7CkdAGM-E0cliHiEoHpovutUuVADwGMaeY-qU?purpose=inline)

Scenario E-04 — Forum access revocation

An election administrator's authority ends while the browser session or cached authorization is still active. The system must enforce the approved revocation requirement.

Tests: authorization freshness, cache invalidation, session enforcement and failure behavior.

These are proposed validation cases, not claims that all organizations use identical eligibility rules. The actual voter qualification, cutoff date, appeal process and administrative powers must come from the relevant election rules.

## 8. Updated priorities

1. P0 — Correct the v0.3 formal semantics. Fix mandatory constraint handling, state-transition representation and frozen-election correction semantics.
2. P0 — Complete the evidence matrix. Establish the scope and evidence requirements for each rule, then execute falsification and link tests.
3. P1 — Validate election scenarios. Use E-01 through E-04 and the existing R-01–R-13 relationship catalogue to expose missing facts or responsibilities.
4. P1 — Establish consistency requirements. Define which facts must be current, which decisions may use snapshots, and what happens when facts or policies are unavailable.
5. P2 — Derive bounded contexts and aggregates. Proceed after the material business rules and ownership boundaries are sufficiently validated.
6. P3 — Resolve identity, tenancy and implementation. Prepare architecture decisions and obtain the required approval before implementation authorization.

## Principal architect's conclusion

The next step is not to add more concepts or increase the completion percentage. It is to demonstrate that the model is logically coherent, traceable to evidence, and able to handle difficult historical and authorization cases.

I recommend maintaining v0.3 as the research baseline and preparing a focused v0.3.1 Formal Corrections and Validation Backlog. That correction set should address the four issues above, assign explicit tests, and distinguish verified rules from unresolved hypotheses.

Once those corrections are reviewed and the evidence matrix is populated, we will have a defensible basis for deriving bounded contexts, aggregate boundaries and the implementation architecture for the organizational digitalization and election platform.
# ################################
review 
# Principal Review and Next-Step Execution: v0.3.1 Formal Corrections and Validation Backlog

## 1. Principal Reviewer Assessment

### 1.1 What the v0.3 Review Contributes

The v0.3 review is the most technically sharp document in the corpus. It does not restate; it **attacks** the specification with four concrete formal defects. I accept all four as valid corrections. Two of them are security-critical.

**The four corrections:**

1. **Mandatory constraints must not grant authorization by themselves.** This is a security defect in v0.3. A mandatory permit should not produce an accidental `Permit` when no ordinary grant exists. This is the most important correction in the document.

2. **State transitions are events, not intervals.** v0.3 modeled `StateTransition` with `vtStart`/`vtEnd`, which conflates the event instant with the state duration. Transitions occur at instants; states hold over intervals. The model must separate these.

3. **Frozen election records need controlled correction semantics.** v0.3 correctly identified immutability but did not specify how legitimate corrections, appeals, and amendments work without mutating the frozen record. The four-artifact split (policy version, snapshot, determination, correction) is correct.

4. **The evidence and falsification method needs refinement.** Not every rule requires the same kind of evidence. Structural invariants need integrity tests; business rules need bylaws; legal constraints need legal sources. Counterexamples must be evaluated against the intended platform contract, not treated as automatic scope-downgrades.

**These are corrections, not alternatives.** I adopt them and produce the v0.3.1 correction set.

### 1.2 Where I Strengthen the v0.3 Review

The v0.3 review is rigorous but has four remaining weaknesses.

**Weakness 1 — The corrected evaluation algorithm is stated but not fully specified for failure modes.**
The proposed algorithm returns `Deny` on `requiredFactsUnavailable`, but does not specify:
- What counts as "required"?
- How does the system distinguish missing facts from facts that are absent by design?
- What is the behavior during partial availability (some facts available, some not)?
- How does the system prevent fail-closed from becoming a denial-of-service vector?

**Weakness 2 — The event-based state transition model is introduced but not fully formalized.**
The recommended model uses `effectiveAt` and `recordedAt`, but does not specify:
- How to derive state intervals from events deterministically.
- How to handle simultaneous transitions.
- How corrections and retractions propagate through the event history.
- What invariants the event log must satisfy.

**Weakness 3 — The frozen election record correction semantics are described but not modeled.**
The four-artifact split is correct, but the relationships between artifacts, the rules for creating corrections, and the constraints on amendment scope are not yet specified.

**Weakness 4 — The matrix extension adds fields but does not define the decision workflow.**
The extended matrix fields are useful, but the process by which a rule moves from Open → Contested → Recommended → Approved is not specified. Without this, the matrix remains a research artifact rather than a governance instrument.

---

## 2. Next-Step Deliverable: v0.3.1 Formal Corrections and Validation Backlog

The next artifact corrects the four v0.3 defects, adds the missing formalizations, and produces an executable validation backlog. It does not derive bounded contexts. It does not decide identity or tenancy. It does not begin implementation.

### 2.1 Correction A — Mandatory Constraints vs. Grants (Security-Critical)

**Principle:** A constraint restricts; a grant authorizes. These are different rule types with different effects.

**Rule types:**

| Rule Type | Purpose | Effect | Overridability |
|---|---|---|---|
| Mandatory Prohibition | Blocks an action unconditionally | Deny | Never overridden |
| Mandatory Condition | Imposes a condition that must hold | Gate | Must be satisfied |
| Ordinary Permit | Supplies positive authorization | Permit | Overridable by higher-priority deny |
| Ordinary Deny | Restricts access | Deny | Overridable by higher-priority permit |
| Abstention | Declares no position | None | Excluded from decisive set |

**Corrected evaluation algorithm:**

```
evaluate(actor, action, resource, facts, policy, time):

  # Step 1: Fact availability
  requiredFacts = policy.requiredFacts(action, resource)
  if not allAvailable(requiredFacts, facts):
    return Deny(reason="Required facts unavailable", failClosed=true)

  # Step 2: Mandatory conflict detection
  if hasConflictingMandatoryConstraints(policy, time):
    return Deny(reason="Policy configuration invalid")

  # Step 3: Mandatory prohibitions (hard block)
  applicableProhibitions = { c ∈ MandatoryProhibitions
                             | c.scope applies
                             ∧ c.condition holds
                             ∧ c.effective(time) }
  if applicableProhibitions ≠ ∅:
    return Deny(reason="Mandatory prohibition")

  # Step 4: Mandatory conditions (must be satisfied)
  applicableConditions = { c ∈ MandatoryConditions
                           | c.scope applies
                           ∧ c.effective(time) }
  for each condition c in applicableConditions:
    if not c.satisfied(facts):
      return Deny(reason="Mandatory condition not satisfied")

  # Step 5: Ordinary rules (decisive only)
  decisiveRules = { r ∈ OrdinaryRules
                    | r.scope applies
                    ∧ r.condition holds
                    ∧ r.effective(time)
                    ∧ r.effect ≠ abstain }

  if decisiveRules = ∅:
    return Deny(reason="No applicable grant")

  # Step 6: Priority resolution
  maxPriority = max(r.priority for r ∈ decisiveRules)
  highest = { r ∈ decisiveRules | r.priority = maxPriority }

  if ∃ r ∈ highest with effect = deny:
    return Deny(reason="Highest-priority denial")

  if ∃ r ∈ highest with effect = permit:
    return Permit(reason="Applicable grant")

  return Deny(reason="No applicable grant")
```

**Key security properties:**

| Property | Formal Statement |
|---|---|
| No accidental permit | A `Permit` requires at least one applicable ordinary permit |
| Fail-closed | Missing facts, conflicts, or unevaluable conditions → `Deny` |
| Mandatory precedence | Mandatory prohibitions cannot be overridden |
| Abstention neutrality | Abstentions never affect the decision |
| Default deny | Absence of decisive rules → `Deny` |

**Failure mode specification:**

| Failure | Behavior | Rationale |
|---|---|---|
| Required fact missing | Deny | Fail-closed |
| Policy config invalid | Deny | Cannot evaluate safely |
| Mandatory conflict | Deny | Configuration error |
| Condition unevaluable | Deny | Fail-closed |
| No decisive rules | Deny | Default deny |
| Tie at highest priority | Deny | Deny-wins default |

**Denial-of-service prevention:**
- Facts are cached with defined freshness SLAs.
- Fact unavailability triggers a fallback evaluation using the last known good facts, if within SLA.
- If fallback is not permitted, the request is denied but the denial is logged and alerted.
- Repeated denials from fact unavailability trigger operational escalation.

### 2.2 Correction B — State Transitions as Events (Formalized)

**Principle:** A transition is an event that occurs at an instant. A state is a property that holds over an interval. These must be modeled separately.

**Event model:**

```
StateTransitionEvent {
  id
  subjectId
  fromState
  toState
  effectiveAt        // valid-time instant when the transition takes effect
  recordedAt         // transaction-time instant when the system recorded it
  authority          // who or what caused the transition
  reason
  supersedesEventId  // if this event corrects a prior event
  version            // monotonic per subject
}
```

**Derived state model:**

State intervals are **derived**, not stored:

```
stateIntervals(subjectId, k) = 
  sort transitionEvents(subjectId) by (effectiveAt, version)
  fold events into intervals
  where each interval [state, start, end) is bounded by adjacent transitions
```

**Formal state query:**

```
stateOf(subjectId, v, k):
  events = { e ∈ StateTransitionEvents
             | e.subjectId = subjectId
             ∧ e.recordedAt ≤ k
             ∧ e.effectiveAt ≤ v }
  ordered = sort events by (effectiveAt, version)
  if ordered = ∅: return Unknown
  return ordered.last().toState
```

**Invariants:**

| ID | Statement |
|---|---|
| ST-1 | ∀ event e, e.fromState ≠ e.toState |
| ST-2 | ∀ event e, (e.fromState, e.toState) is a valid transition in the state machine |
| ST-3 | ∀ subject s, ∀ v, k, stateOf(s, v, k) is deterministic |
| ST-4 | Simultaneous events are ordered by version |
| ST-5 | A correction event supersedes the prior event without deleting it |
| ST-6 | If a correction makes a later event invalid under the state machine, the later event must be flagged as inconsistent |

**Edge cases:**

1. **Late entry:** A transition effective on 1 October but recorded on 15 October. Queries with `k < 15 Oct` do not see it. Queries with `k ≥ 15 Oct` and `v ≥ 1 Oct` see it.
2. **Retroactive correction:** A transition is corrected to a different effective time. A new event is recorded with `supersedesEventId` pointing to the original. Both events remain in history.
3. **Simultaneous transitions:** Two transitions with the same `effectiveAt`. Ordered by version. The later version wins for state queries at that instant.
4. **Retraction:** A transition is retracted. A new event records the retraction with `toState` equal to the prior state. The original event remains in history.
5. **Invalidating correction:** A correction makes a later transition invalid (e.g., a membership cannot go from Active to Approved). The system flags the inconsistency and requires human resolution.

**Validation tests:**

| Test | Scenario | Expected |
|---|---|---|
| ST-T1 | Late entry | State query with `k < recordedAt` does not see the event |
| ST-T2 | Retroactive correction | Both events preserved; state query reflects correction |
| ST-T3 | Simultaneous events | Deterministic ordering by version |
| ST-T4 | Retraction | State reverts; original event preserved |
| ST-T5 | Invalidating correction | System flags inconsistency; no silent acceptance |
| ST-T6 | Bi-temporal query | Correct state returned for (v, k) combinations |

### 2.3 Correction C — Frozen Election Record Correction Semantics

**Principle:** Immutability is preserved, but legitimate corrections and appeals are recorded as linked successor artifacts, not by mutating the original.

**Four artifacts:**

```
EligibilityPolicyVersion {
  id
  version
  rules              // the eligibility rules
  effectiveFrom
  effectiveUntil
  authority          // who approved this version
  evidence           // supporting documents
}

InputSnapshot {
  id
  electionId
  policyVersionId
  capturedAt         // transaction time of snapshot
  facts              // minimum necessary facts to reproduce eligibility
  factVersions       // version identifiers for each fact
  references         // pointers to authoritative sources
  privacyScope       // what personal data is included
}

FrozenEligibilityDetermination {
  id
  electionId
  snapshotId
  policyVersionId
  computedAt
  electorate         // the set of eligible participants
  computationMetadata // method, parameters, algorithm version
  authority          // who authorized the freeze
  auditEvidence      // signatures, hashes, logs
}

CorrectionOrAppealDetermination {
  id
  electionId
  supersedesDeterminationId  // links to the frozen determination
  correctionType     // correction | appeal | amendment
  scope              // full replacement | narrow amendment
  affectedSubjects   // specific participants affected
  reason
  authority
  effectiveAt
  affectsElection    // does this change the current election?
  outcome            // the corrected determination
  evidence
}
```

**Relationships:**

| From | To | Cardinality | Meaning |
|---|---|---|---|
| EligibilityPolicyVersion | InputSnapshot | 1:N | A policy version may be used in multiple snapshots |
| InputSnapshot | FrozenEligibilityDetermination | 1:N | A snapshot may produce multiple determinations |
| FrozenEligibilityDetermination | CorrectionOrAppealDetermination | 1:N | A determination may be corrected multiple times |

**Invariants:**

| ID | Statement |
|---|---|
| FE-1 | A FrozenEligibilityDetermination is immutable |
| FE-2 | A CorrectionOrAppealDetermination never mutates its predecessor |
| FE-3 | A CorrectionOrAppealDetermination has a defined scope (full or narrow) |
| FE-4 | A correction that affects the current election must be explicitly authorized |
| FE-5 | A correction that does not affect the current election is still recorded for audit |
| FE-6 | The original determination remains queryable after correction |

**Decisions required (from the v0.3 review):**

1. **Timing of correction:** Does a correction change eligibility only before the election, or can it also affect post-election review?
   - **Proposed default:** Before election freeze → correction allowed. After freeze → correction recorded but does not alter frozen electorate unless explicitly authorized.
2. **Authorization:** Who may authorize a new voter-roll version?
   - **Proposed:** The election authority, defined by the election rules.
3. **Scope:** Is a new version a complete replacement or a narrowly scoped amendment?
   - **Proposed:** Both are supported; the scope is explicit in the correction artifact.
4. **Dispute recording:** How is a disputed eligibility decision recorded?
   - **Proposed:** As a CorrectionOrAppealDetermination with `correctionType = appeal`.
5. **Data retention:** Which data must be retained to reproduce a decision, and which personal data should not be copied into the election record?
   - **Proposed:** Retain fact versions and references, not personal data copies. Access to the referenced facts is governed by the authoritative context.
6. **Preservation:** How is the original decision preserved when a correction is accepted?
   - **Proposed:** The original determination is never mutated; the correction is a linked successor.

**Note:** These decisions are procedural, not data-model. They must be answered by election rules and legal sources, not inferred from the architecture.

### 2.4 Correction D — Evidence and Falsification Method (Refined)

**Principle:** Not every rule requires the same kind of evidence. Evidence must match the rule category.

**Rule categories and evidence requirements:**

| Rule Category | Example | Evidence Type | Falsification Method |
|---|---|---|---|
| Structural invariant | A relationship references an existing entity | Domain specification | Integrity tests |
| Temporal invariant | A non-empty interval has valid ordering | Formal definition | Boundary tests |
| Business rule | Membership requires approval before activation | Bylaws, policy | Scenario + stakeholder |
| Configurable rule | Members may belong to multiple units | Organizational scenarios | Cross-organization evidence |
| Legal constraint | Statutory condition applies | Legal source | Qualified legal interpretation |
| Operational requirement | Access revoked within SLA | Risk analysis | Acceptance tests |

**Corrected falsification protocol:**

1. **State the rule** and its **domain** (the class of organizations or scenarios it claims to govern).
2. **Categorize the rule** (structural, temporal, business, configurable, legal, operational).
3. **Identify the appropriate evidence type** for that category.
4. **Construct a counterexample** within the rule's claimed domain.
5. **Evaluate the counterexample** against the platform contract:
   - If legitimate → the rule is either narrowed or the model is revised.
   - If not legitimate → the rule holds.
   - If the counterexample reveals a different relationship type → introduce a new concept.
6. **Record** the counterexample, verdict, and evidence.
7. **Add a regression test** that exercises the counterexample.

**Key correction:** A counterexample from one organization does not automatically invalidate a rule the platform intentionally enforces for a class of organizations. The platform's contract defines the domain.

**Principle:** Excessive configurability creates an untestable product. The aim is the smallest stable set of universal invariants while representing genuine organizational differences explicitly.

### 2.5 Extended Matrix Schema

The v0.3 review proposes extended fields. I adopt them and add the decision workflow.

| Field | Purpose |
|---|---|
| RuleID | Stable traceability identifier |
| RuleVersion | Identifies current wording |
| Proposition | Precise, testable business statement |
| Category | Structural / Temporal / Business / Configurable / Legal / Operational |
| Scope | Universal / Configurable / Organization-specific / Legal |
| Domain | The class of organizations or scenarios the rule governs |
| EvidenceRef | Source document and exact section/page |
| EvidenceQuality | Observed / Derived / Hypothesis / Contested |
| CounterexampleID | Link to falsification scenario |
| OwnerContext | Provisional authoritative owner |
| FormalInvariant | Logical or mathematical representation |
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

Contested → Open (if counterexample resolved)
          → Recommended (if evidence resolves)
          → Rejected (if evidence refutes)

Recommended → Approved (if ARB approves)
            → Contested (if new counterexample)
```

**Rule:** A rule cannot move to Approved without:
- Complete traceability chain (evidence → rule → invariant → test).
- Falsification attempt recorded.
- ARB decision recorded.

### 2.6 Election-Relevant Validation Scenarios

The v0.3 review proposes E-01 through E-04. I adopt them and add E-05 and E-06.

| Scenario | Tests | Validation Criteria |
|---|---|---|
| E-01 Voter-roll eligibility | Membership, temporal query, policy versioning, frozen determination | Reproducible electorate at cutoff |
| E-02 Election administration authority | Appointment, role, mandate, authorization, audit | Authority evaluated at decision time |
| E-03 Jurisdiction change | Jurisdiction history, temporal queries, snapshot immutability, dispute | Original determination preserved |
| E-04 Forum access revocation | Authorization freshness, cache invalidation, session enforcement | Revocation within SLA |
| E-05 Cross-organization voter | Person, Member, Affiliation, organization scope | Eligibility per organization |
| E-06 Retroactive mandate correction | Mandate, bi-temporal history, authorization | Historical decisions reconstructable |

These validate the foundational model's sufficiency without adding election-specific concepts.

---

## 3. Machine Learning and Formal Methods (v0.3.1 Scope)

| Technique | Application | Oversight |
|---|---|---|
| NLP / LLM | Extract candidate rules from constitutions, bylaws, policies | Mandatory human review |
| Clustering | Cross-organization term analysis | Mandatory human review |
| Contradiction detection | Policy configuration | Mandatory human review |
| Anomaly detection | Operational data | Signal for investigation |
| Property-based testing | Rule validation, edge cases | Automated with generators |
| Constraint solving | Policy conflict detection | Automated |
| Model checking | State machine validation, authorization safety | Applied selectively |
| Temporal logic | Bi-temporal query validation, lifecycle constraints | Applied to time-bounded facts |
| Proof theory | Authorization audit records | Applied to decisions |
| Statistical analysis | Election turnout patterns (if election in scope) | Signal only |

**ML must never decide:**
- Membership eligibility
- Affiliation approval
- Appointment approval
- Mandate validity
- Authorization decisions
- Election eligibility
- Policy precedence

ML informs research. Humans and formal logic decide.

---

## 4. How Far We Are and What Remains

### 4.1 Current Status

| Area | Status |
|---|---|
| Phase 1 — Conceptual brainstorming | Foundation established |
| Gate 0 — Corpus verification | Completed |
| Gate 1 — Research plan | Completed |
| Cross-domain focus | Completed |
| v0.1 — Relationship specification | Completed |
| v0.2 — Formalization | Completed |
| Principal review v0.2 | Completed |
| v0.3 — Formal consistency | Completed |
| Principal review v0.3 | Completed |
| **v0.3.1 — Formal Corrections and Validation Backlog** | **Produced in this response** |
| — Mandatory constraint correction | Specified |
| — State transition event model | Formalized |
| — Frozen election correction semantics | Specified |
| — Evidence and falsification method | Refined |
| — Extended matrix schema | Defined |
| — Decision workflow | Defined |
| — Election-relevant scenarios E-01 to E-06 | Defined |
| Evidence collection | Not started (requires source documents) |
| Falsification execution | Not started |
| Bounded-context derivation | Not started |
| Aggregate validation | Not started |
| Context map | Not started |
| Identity model decision | Not started |
| Tenancy model decision | Not started |
| ML scope approval | Not started |
| ARB-1 approval | Pending |
| ARB-2 architecture approval | Not started |
| ARB-3 implementation authorization | Not started |

**Overall assessment:** The formal specification is now complete and internally consistent. The next bottleneck is **evidence and falsification**, which require real organizational documents. We are approximately **45–50%** of the way toward an implementable organizational domain model.

### 4.2 Remaining TODOs (Bullet Points)

**P0 — Formal corrections (v0.3.1)**
- Adopt the corrected policy evaluation algorithm (mandatory constraints do not grant authorization).
- Adopt the event-based state transition model (transitions are events, states are intervals).
- Adopt the four-artifact frozen election record model (policy version, snapshot, determination, correction).
- Adopt the refined evidence and falsification method (rule categories, domain-scoped counterexamples).
- Adopt the extended matrix schema and decision workflow.
- Adopt the election-relevant scenarios E-01 through E-06.

**P0 — Evidence collection**
- Obtain constitution or bylaws from at least one political party.
- Obtain charter or statutes from at least one Red Cross / Red Crescent national society.
- Obtain bylaws from at least one membership association.
- Obtain federation agreement from at least one federation.
- Extract rules relevant to R-01 through R-13.
- Record EvidenceRef with document section/page for each rule.
- Mark EvidenceQuality for each rule.

**P0 — Falsification execution**
- For each rule, categorize (structural, temporal, business, configurable, legal, operational).
- For each rule, identify the appropriate evidence type.
- For each rule, construct a counterexample within the rule's domain.
- Evaluate counterexamples against the platform contract.
- Narrow scope or revise the model as needed.
- Record counterexample, verdict, and evidence in the matrix.
- Link each rule to an executable regression test.

**P0 — Populate the rule and evidence matrix**
- Complete all columns for R-01 through R-13.
- Ensure complete traceability chain: evidence → rule → invariant → owner → test → decision.
- Move rules through the decision workflow: Open → Contested → Recommended → Approved/Rejected.
- Ensure no rule is Approved without ARB decision.

**P1 — Election-relevant validation**
- Execute scenarios E-01 through E-06.
- Verify the foundational model supports each scenario.
- Record findings and gaps.
- Ensure no election-specific concepts are added to the foundational model.

**P1 — Formal semantics freeze**
- Confirm bi-temporal query contract with operational scenarios.
- Confirm mandatory vs. ordinary rule distinction with tests.
- Confirm abstention semantics with tests.
- Confirm event-based state transition model with edge-case tests.
- Confirm frozen election record correction semantics with dispute scenarios.
- Freeze definitions with sufficient evidence.

**P1 — Identity and tenancy research**
- Gather privacy and isolation requirements.
- Compare global vs. organization-scoped identity.
- Compare isolation models (shared schema, schema-per-tenant, database-per-tenant).
- Prepare decision documents with consequences.

**P2 — Bounded-context derivation**
- Group relationships by ownership and lifecycle.
- Identify context boundaries from validated clusters.
- Validate each context against at least two scenarios.
- Document responsibilities, aggregates, contracts, events.
- Produce the context map with integration patterns.

**P2 — Aggregate validation**
- Determine aggregate boundaries from transactional invariants.
- Validate with concurrency scenarios.
- Decide Affiliation, CommitteeAppointment, MembershipType, Mandate, Role aggregate status.

**P3 — Architecture decision package**
- Assemble all artifacts.
- Mark decision statuses.
- Submit for independent ChatGPT review.
- Obtain ARB-2 architecture approval.

**P4 — Implementation planning**
- Produce dependency-ordered implementation plan.
- Obtain ARB-3 implementation authorization.
- Implement incrementally with domain, integration, authorization, tenant-isolation, and regression tests.

### 4.3 Principal Recommendation

The v0.3 review is correct. The next milestone is not more terminology. It is **evidence and falsification**.

The immediate next action is:

1. **Adopt v0.3.1 corrections** (mandatory constraints, state transitions, frozen election records, evidence method).
2. **Obtain four source documents** (party constitution, Red Cross statutes, association bylaws, federation agreement).
3. **Populate the rule and evidence matrix** for R-01 through R-13.
4. **Execute falsification** for every rule.
5. **Execute election-relevant scenarios** E-01 through E-06.

Only when these are complete should bounded contexts be derived. The model must first demonstrate that it can explain real cases, survive counterexamples, and preserve historical decisions.

**Authorization required:** Provision of at least four organizational source documents to proceed with evidence collection and falsification.
