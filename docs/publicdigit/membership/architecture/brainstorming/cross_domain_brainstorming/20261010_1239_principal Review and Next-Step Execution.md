# Principal Review and Next-Step Execution: Cross-Domain Relationship Specification v0.3

## 1. Principal Reviewer Assessment

### 1.1 What the v0.2 Review Contributes

The Principal DDD review of v0.2 is the most technically precise document in the corpus so far. It does not merely restate v0.2; it **challenges** it with four concrete formal defects and one architectural reorientation. I accept all four defects as valid corrections and adopt the reorientation as the correct next direction.

**The four defects are genuine errors in v0.2, not stylistic preferences:**

1. **Bi-temporal query ambiguity.** v0.2 defined `asOf(T)` as if a single time parameter were sufficient. It is not. Valid time and transaction time are independent dimensions. A two-parameter query `F(v, k)` is required.

2. **Abstention mishandling in policy evaluation.** v0.2's algorithm computes max priority over all non-mandatory rules, including abstentions. This can cause a high-priority abstention to suppress a lower-priority permit and fall through to default-deny — which contradicts the intended meaning of abstention as a non-decision.

3. **Unresolved type classifications.** v0.2's type table marks several concepts as "entity or value object" and "global or scoped." These are not stable classifications; they are open architectural decisions dressed as types. The status column must record candidate → evidence → decision → consequences.

4. **Election eligibility cannot be only a live projection.** v0.2 said election eligibility is a projection of organizational facts, not a new fact. This is correct for *runtime* eligibility but incomplete for *auditable* elections. An election may need to persist an eligibility determination or frozen voter-roll version as a historical record, without rewriting the underlying organizational facts.

**The reorientation is correct:** The next milestone is not more glossary. It is demonstrating that the model can explain real cases, survive counterexamples, and preserve historical decisions. Only then should bounded contexts be derived.

### 1.2 Where I Strengthen the v0.2 Review

The v0.2 review is rigorous but has four remaining weaknesses that must be addressed in v0.3.

**Weakness 1 — The mandatory/ordinary distinction is stated but not formalized.**
The review says "a mandatory rule is not just a high-priority rule" and "mandatory constraints should be modeled separately." This is correct but incomplete. v0.3 must specify:
- The formal type distinction between mandatory constraints and ordinary policy rules.
- The conflict resolution when two mandatory rules conflict.
- The precedence between mandatory constraints and ordinary rules.
- The behavior when a mandatory rule's condition cannot be evaluated (e.g., missing facts).

**Weakness 2 — The bi-temporal model is corrected but not extended to state machines.**
The two-parameter query `F(v, k)` is correct. But v0.2 also introduced state machines for lifecycles (membership, appointment, mandate). v0.3 must specify how bi-temporal history interacts with state transitions:
- Is a state change a new bi-temporal fact or an update to an existing one?
- How are retroactive corrections handled when they affect state transitions?
- How does the system answer "what state was this fact in at valid time v, given what we knew at transaction time k"?

**Weakness 3 — The election eligibility correction is stated but not specified.**
The review says an election may need to persist an eligibility determination or frozen voter roll. v0.3 must specify:
- What exactly is frozen: eligibility rules, facts, or both?
- How the frozen record relates to the live organizational facts.
- How corrections to organizational facts after freezing affect the frozen record (answer: they must not).
- How the frozen record is audited and disputed.

**Weakness 4 — The rule and evidence matrix is proposed but not yet structured for execution.**
The matrix in Section 4 of the review is a good start but not yet a working instrument. v0.3 must specify:
- The matrix schema (columns, types, required fields).
- The evidence quality levels and their criteria.
- The falsification protocol.
- The traceability identifiers linking evidence → rule → invariant → test.

---

## 2. Next-Step Deliverable: Cross-Domain Relationship Specification v0.3

The next artifact is **v0.3 — Formal Consistency and Requirements Traceability**. It corrects the four defects, formalizes the mandatory/ordinary distinction, extends bi-temporality to state machines, specifies election eligibility freezing, and structures the evidence matrix for execution.

### 2.1 Correction 1 — Bi-Temporal Query Contract (Formalized)

**Two time dimensions:**

- **Valid time (VT):** when the fact is true in organizational reality.
- **Transaction time (TT):** when the system records its knowledge of the fact.

**Bi-temporal fact:**

```
BiTemporalFact {
  id
  vtStart        // inclusive
  vtEnd          // exclusive, nullable (open-ended)
  ttStart        // inclusive
  ttEnd          // exclusive, nullable (current record)
  factType
  payload
  organizationScope
  versionId      // monotonic per fact identity
}
```

**Query function:**

$$F(v, k) = \{ f \mid f.vtStart \leq v < f.vtEnd \land f.ttStart \leq k < f.ttEnd \}$$

Where:
- $v$ = valid-time instant
- $k$ = transaction-time instant
- Missing upper bounds are treated as $+\infty$

**Canonical queries:**

| Query | Meaning | Formula |
|---|---|---|
| `currentBelief(v)` | What we currently believe was true at valid time v | $F(v, \text{now})$ |
| `knowledgeAt(k)` | What the system knew at transaction time k | $F(\cdot, k)$ |
| `beliefAt(k, v)` | What the system believed at transaction time k about valid time v | $F(v, k)$ |
| `history(id)` | All recorded versions of a fact | $\{ f \mid f.id = id \}$ |
| `corrections(id)` | Versions after the first | $\{ f \mid f.id = id \land f.versionId > 1 \}$ |

**Correction semantics:**

- A correction creates a new version with `ttStart = now`, and closes the previous version with `ttEnd = now`.
- Corrections never delete history.
- Retroactive corrections may change `vtStart`/`vtEnd` but must not alter past `ttStart`/`ttEnd`.
- A correction that contradicts a frozen election record must be rejected or flagged.

**Invariants:**

| ID | Statement |
|---|---|
| BT-1 | $\forall f, f.vtStart < f.vtEnd$ when both exist |
| BT-2 | $\forall f, f.ttStart < f.ttEnd$ when both exist |
| BT-3 | $\forall f, \forall k, \exists$ at most one version of $f$ with $f.ttStart \leq k < f.ttEnd$ |
| BT-4 | $\forall f, \forall v, k$, the answer to $F(v, k)$ is unique per fact identity |
| BT-5 | Corrections are additive; no version is deleted |

### 2.2 Correction 2 — Mandatory Constraints vs. Ordinary Policy Rules

The v0.2 review correctly observes that mandatory rules are not simply high-priority rules. v0.3 formalizes the distinction.

**Two rule types:**

```
MandatoryConstraint {
  id
  scope
  effect: permit | deny
  condition: logical formula over facts
  source: legal | platform | organization
  effectiveFrom
  effectiveUntil
}

OrdinaryPolicyRule {
  id
  scope
  effect: permit | deny | abstain
  priority: integer
  condition: logical formula over facts
  effectiveFrom
  effectiveUntil
}
```

**Key differences:**

| Aspect | Mandatory Constraint | Ordinary Policy Rule |
|---|---|---|
| Overridability | Never overridden by ordinary rules | Can be overridden by higher priority |
| Conflict resolution | Conflicting mandatory constraints = configuration error | Resolved by priority, then deny-wins |
| Source | Legal, platform, or organization constitution | Organization policy |
| Abstention | Not allowed | Allowed |
| Failure mode | Fail-closed if condition unevaluable | Fail-closed if condition unevaluable |

**Corrected evaluation model:**

```
evaluate(actor, action, resource, facts, time):
  applicableMandatory = { c ∈ MandatoryConstraints
                          | c.scope applies
                          ∧ c.condition holds
                          ∧ c.effectiveFrom ≤ time < c.effectiveUntil }
  
  mandatoryDenies = { c ∈ applicableMandatory | c.effect = deny }
  mandatoryPermits = { c ∈ applicableMandatory | c.effect = permit }
  
  if mandatoryDenies ≠ ∅ and mandatoryPermits ≠ ∅:
    return ConfigurationError(reason=conflictingMandatoryConstraints)
  
  if mandatoryDenies ≠ ∅:
    return Deny(reason=mandatoryDeny)
  
  if mandatoryPermits ≠ ∅:
    return Permit(reason=mandatoryPermit)
  
  applicableOrdinary = { r ∈ OrdinaryPolicyRules
                         | r.scope applies
                         ∧ r.condition holds
                         ∧ r.effectiveFrom ≤ time < r.effectiveUntil }
  
  decisiveRules = { r ∈ applicableOrdinary | r.effect ≠ abstain }
  
  if decisiveRules = ∅:
    return Deny(reason=defaultDeny)
  
  maxPriority = max(r.priority for r ∈ decisiveRules)
  highest = { r ∈ decisiveRules | r.priority = maxPriority }
  
  if ∃ r ∈ highest with effect = deny:
    return Deny(reason=highestPriorityDeny)
  if ∃ r ∈ highest with effect = permit:
    return Permit(reason=highestPriorityPermit)
```

**Corrections applied:**

1. **Abstentions removed from decisive set.** The prior algorithm incorrectly allowed abstentions to influence priority.
2. **Mandatory constraints evaluated separately.** They cannot be overridden by ordinary rules, regardless of priority.
3. **Conflicting mandatory constraints are configuration errors.** They must be detected at configuration time and blocked from activation.
4. **Failure mode is fail-closed.** If a condition cannot be evaluated (missing facts), the result is deny.

**Conflict detection:**

- Two mandatory constraints conflict if they apply to the same scope and can simultaneously hold with opposite effects.
- Conflict detection is a constraint satisfaction problem.
- Conflicts are reported to administrators at configuration time.
- Active conflicts block policy activation.
- Runtime conflict detection is a safety net, not the primary mechanism.

### 2.3 Correction 3 — Type System with Decision Status

The v0.2 review correctly observes that the type table contains unresolved alternatives dressed as classifications. v0.3 replaces the single classification column with a decision-status schema.

**Type classification schema:**

| Concept | Candidate Type | Evidence | Decision Status | Consequences |
|---|---|---|---|---|
| Person | Entity (global) or Entity (scoped) | Pending | Open | Privacy, matching, isolation |
| Account | Entity (platform) or reference (external IdP) | Pending | Open | Authentication, federation |
| Actor | Role played by Account | Derived | Provisional | Per-request, no identity |
| Member | Entity (org-scoped Person representation) | Phase 1 | Provisional | Per (Person, Org) |
| Membership | Entity (episode) | Phase 1 | Provisional | Bi-temporal |
| MembershipType | Value object or versioned policy | Pending | Open | Classification vs. policy split |
| OrganizationalUnit | Entity | Phase 1 | Provisional | Hierarchy |
| Committee | Entity | Phase 1 | Provisional | Governance body |
| Participant | Role (not entity) | v0.2 | Provisional | Per activity, no identity |
| CommitteeAppointment | Entity | Phase 1 | Provisional | Bi-temporal |
| Role (organizational) | Value object or entity | Pending | Open | Definition vs. assignment split |
| Mandate | Value object or entity | Pending | Open | Independent lifecycle |
| MemberUnitAffiliation | Entity | v0.2 | Provisional | Bi-temporal |
| Participation/Assignment | Entity | v0.2 | Provisional | Bi-temporal |
| Employment | Entity | v0.2 | Out of scope | Future HR context |
| GeographicJurisdiction | Entity | Phase 1 | Provisional | Bi-temporal |
| Forum | Entity | Phase 1 | Provisional | Lifecycle |
| ForumAccessPolicy | Versioned policy | Phase 1 | Provisional | Precedence model |
| AuthorizationDecision | Function output | v0.2 | Provisional | Auditable, cacheable |

**Decision-status vocabulary:**

- **Provisional:** hypothesis with reasonable support, not yet validated against evidence.
- **Open:** competing alternatives, decision required.
- **Contested:** conflicting evidence or stakeholder views.
- **Approved:** ARB decision recorded.
- **Rejected:** ARB decision recorded.
- **Out of scope:** acknowledged but deferred.

**Rule:** No concept may be implemented until its decision status is Approved.

### 2.4 Correction 4 — Election Eligibility Freezing (Specified)

The v0.2 review correctly observes that election eligibility cannot be only a live projection. v0.3 specifies what is frozen and how it relates to live facts.

**Frozen election record:**

```
FrozenEligibilityRecord {
  id
  electionId
  frozenAt                // transaction time of the freeze
  eligibilityRuleVersion  // version of the rule set used
  factSnapshot            // snapshot of organizational facts at freeze time
  electorate              // set of eligible participants determined at freeze time
  determinationMethod     // how eligibility was computed
  authority               // who authorized the freeze
  auditEvidence           // supporting records
}
```

**Key principles:**

1. **Organizational facts remain owned by their contexts.** The freeze does not copy them into the election context as authoritative; it records a snapshot for reproducibility.
2. **The frozen record is immutable.** Corrections to organizational facts after freezing do not alter the frozen record.
3. **The frozen record is auditable.** Disputes can reconstruct the exact eligibility decision.
4. **The frozen record is versioned.** If the election rules change, a new freeze is created; the old one is preserved.
5. **Election decisions do not rewrite organizational facts.** A voter found ineligible does not become a non-member; the eligibility decision is election-specific.

**Relationship to live facts:**

| Question | Answer |
|---|---|
| Can live facts change after freeze? | Yes — organizational life continues |
| Does the frozen record change? | No |
| Can an election decision be appealed? | Yes — via the election dispute process |
| Does an appeal modify the frozen record? | No — it creates a new determination record |
| Can the frozen record be used for future elections? | Only as historical reference; each election freezes its own |

**This correction preserves the v0.2 principle (election consumes facts) while adding the audit requirement (elections must be reproducible).**

### 2.5 Bi-Temporality and State Machines (Extended)

The v0.2 review corrects the bi-temporal model but does not extend it to state machines. v0.3 specifies the interaction.

**State transitions as bi-temporal facts:**

Each state transition is a bi-temporal fact:

```
StateTransition {
  id
  factId           // the fact whose state changed
  fromState
  toState
  vtStart          // when the transition was valid in reality
  vtEnd
  ttStart          // when the system recorded it
  ttEnd
  authority        // who or what caused the transition
  reason
}
```

**Key rules:**

1. **State is a function of time.** `stateOf(fact, v, k)` returns the state at valid time v, given knowledge at transaction time k.
2. **Retroactive corrections create new transitions.** If a correction changes when a state became valid, a new `StateTransition` is recorded, and the old one is closed in transaction time.
3. **Frozen election records ignore later state changes.** Once frozen, a fact's state is captured in the snapshot.
4. **State machines are bi-temporal.** Both validity and knowledge dimensions apply.

**Formal state query:**

```
stateOf(factId, v, k):
  transitions = { t ∈ StateTransitions
                  | t.factId = factId
                  ∧ t.vtStart ≤ v < t.vtEnd
                  ∧ t.ttStart ≤ k < t.ttEnd }
  if transitions = ∅: return Unknown
  if |transitions| > 1: return Inconsistent (invariant violation)
  return transitions[0].toState
```

**Invariant:** For any (factId, v, k), at most one state transition is active in both dimensions.

### 2.6 Rule and Evidence Matrix (Structured for Execution)

The v0.2 review proposes the matrix but does not structure it for execution. v0.3 specifies the schema.

**Matrix schema:**

| Column | Type | Required | Description |
|---|---|---|---|
| Rule ID | string | Yes | Stable identifier (e.g., R-02-I-1) |
| Rule statement | text | Yes | Plain-language proposition |
| Scope | enum | Yes | Universal / Configurable / Organization-specific / Legal |
| Source evidence | reference | Yes | Document, page, or stakeholder statement |
| Evidence quality | enum | Yes | Observed / Derived / Hypothesis / Contested |
| Counterexample | text | Yes | Scenario that would falsify the rule |
| Counterexample verdict | enum | Yes | Rule holds / Rule scoped / Rule rejected |
| Formal proposition | formula | Yes | Logic representation |
| Owner context | string | Yes | Which context owns the fact |
| Test method | enum | Yes | Unit / Property-based / Model checking / Scenario |
| Test identifier | string | Yes | Link to executable test |
| Decision status | enum | Yes | Hypothesis / Contested / Recommended / Approved / Rejected |
| Decision authority | string | If approved | ARB record reference |

**Falsification protocol:**

For each rule:

1. State the rule in plain language.
2. Identify its scope claim (universal, configurable, etc.).
3. Construct a counterexample scenario.
4. Determine whether the counterexample is legitimate in some organization.
5. If legitimate:
   - If the rule claimed universality, downgrade it to configurable or organization-specific.
   - If the rule claimed configurable, document the configuration variation.
6. If not legitimate, retain the rule at its claimed scope.
7. Record the counterexample and verdict in the matrix.
8. Link the rule to a test that exercises the counterexample.

**Traceability chain:**

```
Evidence → Rule → Formal proposition → Owner → Test → Decision status
```

Every rule must have a complete chain. Incomplete chains block approval.

### 2.7 Scenario Matrix (Extended with Election-Relevant Cases)

The v0.2 review lists seven scenarios. v0.3 extends them with election-relevant cases while keeping the foundational model election-agnostic.

| Scenario | Facts involved | Business question | Election relevance |
|---|---|---|---|
| Member-at-large | Membership, Affiliation | Can membership exist without unit? | Eligibility for at-large voting |
| Volunteer non-member | Person, Participation | What authority does participation grant? | Non-member participation in election administration |
| Mandate expiry | Appointment, Role, Mandate | What powers end, and when? | Committee authority during election period |
| Boundary change | Jurisdiction, Committee | Which responsibilities are affected? | Jurisdiction-based voter rolls |
| Forum access revocation | Appointment, Mandate, Policy | How fast is access revoked? | Election communication forums |
| Retroactive membership correction | Membership, TT | Can past decisions be reconstructed? | Voter roll reproducibility |
| Voter eligibility challenge | Membership, Jurisdiction, Rules | Can a decision be explained and reproduced? | Frozen eligibility records |
| **Multi-organization voter** | Person, Member, Affiliation | Which organization's eligibility applies? | Cross-organization elections |
| **Dual appointment** | Appointment, Role, Mandate | Can one person hold two roles in one election? | Election administration |
| **Historical mandate validity** | Mandate, TT | Was authority valid at the time of the election? | Retroactive authority disputes |
| **Frozen record correction** | Membership, FrozenEligibility | How are corrections handled post-freeze? | Audit and dispute resolution |

### 2.8 Machine Learning and Formal Methods (Refined)

The v0.2 review correctly positions ML as assistive. v0.3 refines the scope.

| Technique | Stage | Output | Human Oversight |
|---|---|---|---|
| NLP / LLM | Evidence extraction | Candidate rules with citations | Mandatory |
| Clustering | Vocabulary analysis | Grouped terms across organizations | Mandatory |
| Contradiction detection | Policy configuration | Flagged conflicts | Mandatory |
| Anomaly detection | Operational data | Flagged unusual patterns | Mandatory |
| Property-based testing | Rule validation | Counterexamples | Automated with generators |
| Constraint solving | Policy validation | Satisfiability results | Automated |
| Model checking | Lifecycle validation | Safety property results | Applied selectively |
| Temporal logic | Temporal rule validation | Temporal property results | Applied to time-bounded facts |
| Proof theory | Authorization audit | Verifiable decision records | Applied to decisions |

**ML must never decide:**
- Membership eligibility
- Affiliation approval
- Appointment approval
- Mandate validity
- Authorization decisions
- Election eligibility

These are decisions made by formal logic over validated facts and policies, with human authority for policy approval and dispute resolution.

### 2.9 Priority Plan (Revised)

**P0 — Correct and freeze formal semantics**
- Confirm bi-temporal query contract.
- Confirm mandatory vs. ordinary policy rule distinction.
- Confirm abstention semantics.
- Confirm type classification schema with decision status.
- Confirm bi-temporal state machine semantics.
- Freeze only definitions with sufficient evidence.

**P0 — Validate the four-way person-to-unit split**
- Test MemberUnitAffiliation, Participation, Employment, and CommitteeAppointment against at least three organization types.
- Document coexistence rules and independent lifecycles.
- Record counterexamples and scope adjustments.

**P0 — Build the rule and evidence matrix**
- Populate for all R-01 to R-13 rules.
- Execute falsification protocol.
- Record decision status for each rule.
- Link each rule to a test.

**P1 — Specify election eligibility freezing**
- Define FrozenEligibilityRecord schema.
- Define freeze semantics.
- Define correction and dispute handling.
- Define audit evidence.

**P1 — Validate election-relevant scenarios**
- Extend the scenario matrix with election-relevant cases.
- Verify the foundational model supports them without election-specific concepts.
- Record findings.

**P1 — Define temporal and authorization semantics**
- Confirm bi-temporal state machines.
- Confirm policy precedence model.
- Define freshness SLA and cache invalidation.
- Define fail-closed behavior.

**P2 — Derive bounded contexts**
- Group relationships by ownership and lifecycle.
- Identify context boundaries from validated clusters.
- Validate each context against scenarios.
- Document responsibilities, aggregates, contracts, events.

**P2 — Validate aggregates**
- Determine aggregate boundaries from transactional invariants.
- Validate with concurrency scenarios.

**P3 — Decide identity and tenancy**
- Compare global vs. scoped identity.
- Compare isolation models.
- Base decisions on validated requirements.
- Obtain ARB approval.

**P3 — Prepare architecture decision package**
- Assemble all artifacts.
- Mark decision statuses.
- Submit for independent review.
- Obtain ARB-2 approval.

**P4 — Implementation planning**
- Produce dependency-ordered plan.
- Obtain ARB-3 authorization.
- Implement incrementally with tests.

---

## 3. How Far We Are and What Remains

### 3.1 Current Status

| Area | Status |
|---|---|
| Phase 1 — Conceptual brainstorming | Foundation established (~25%) |
| Gate 0 — Corpus verification | Completed |
| Gate 1 — Research plan | Completed |
| Cross-domain focus | Completed |
| Cross-Domain Relationship Specification v0.1 | Completed |
| Principal review v0.1 | Completed |
| Cross-Domain Relationship Specification v0.2 | Completed |
| Principal review v0.2 | Completed |
| **Cross-Domain Relationship Specification v0.3** | **Started in this response** |
| — Bi-temporal query contract | Formalized |
| — Mandatory vs. ordinary rules | Formalized |
| — Type system with decision status | Formalized |
| — Election eligibility freezing | Specified |
| — Bi-temporal state machines | Extended |
| — Rule and evidence matrix | Structured |
| — Scenario matrix | Extended |
| Bounded-context derivation | Not started |
| Aggregate validation | Not started |
| Context map | Not started |
| Identity model decision | Not started |
| Tenancy model decision | Not started |
| ML scope approval | Not started |
| ARB-1 approval | Pending |
| ARB-2 architecture approval | Not started |
| ARB-3 implementation authorization | Not started |

**Overall assessment:** We are approximately **40–45%** of the way toward an implementable organizational domain model. The formal semantics are now corrected and largely specified. The remaining work is evidence collection, falsification, election-relevant validation, and bounded-context derivation.

### 3.2 Remaining TODOs (Prioritized)

**P0 — Formal semantics freeze**
- Confirm bi-temporal query contract and correction semantics.
- Confirm mandatory vs. ordinary policy rule distinction.
- Confirm abstention semantics.
- Confirm type classification schema with decision status.
- Confirm bi-temporal state machine semantics.
- Freeze definitions with sufficient evidence.

**P0 — Four-way split validation**
- Validate MemberUnitAffiliation against at least three organization types.
- Validate Participation against at least three organization types.
- Validate Employment as distinct (even if out of scope).
- Validate CommitteeAppointment as distinct.
- Define coexistence rules.
- Document counterexamples and scope adjustments.

**P0 — Rule and evidence matrix**
- Populate for all R-01 to R-13 rules.
- Execute falsification protocol.
- Record decision status for each rule.
- Link each rule to a test.
- Ensure traceability chain is complete.

**P0 — Election eligibility freezing**
- Define FrozenEligibilityRecord schema.
- Define freeze semantics.
- Define correction and dispute handling.
- Define audit evidence requirements.
- Specify relationship to live facts.

**P1 — Election-relevant validation**
- Extend scenario matrix with election cases.
- Verify foundational model supports them.
- Do not add election-specific concepts to the foundational model.
- Record findings and any gaps.

**P1 — Temporal and authorization semantics**
- Confirm bi-temporal state machines.
- Confirm policy precedence model.
- Define freshness SLA and cache invalidation.
- Define fail-closed behavior.
- Define audit evidence for decisions.

**P1 — Evidence collection**
- Obtain constitutions, bylaws, statutes from at least three organization types.
- Conduct stakeholder interviews where possible.
- Mark evidence quality (Observed / Derived / Hypothesis / Contested).
- Record conflicts and their resolution authority.

**P2 — Bounded-context derivation**
- Group relationships by ownership and lifecycle.
- Identify context boundaries from validated clusters.
- Validate each context against scenarios.
- Document responsibilities, aggregates, contracts, events.

**P2 — Aggregate validation**
- Determine aggregate boundaries from transactional invariants.
- Validate with concurrency scenarios.
- Decide Affiliation, CommitteeAppointment, MembershipType, Mandate, Role aggregate status.

**P3 — Identity and tenancy decisions**
- Compare global vs. scoped identity.
- Compare isolation models.
- Base decisions on validated requirements.
- Obtain ARB approval.

**P3 — Architecture decision package**
- Assemble all artifacts.
- Mark decision statuses.
- Submit for independent review.
- Obtain ARB-2 approval.

**P4 — Implementation planning**
- Produce dependency-ordered plan.
- Obtain ARB-3 authorization.
- Implement incrementally with tests and evidence.

### 3.3 Principal Recommendation

The v0.2 review is correct. The next artifact is **v0.3 — Formal Consistency and Requirements Traceability**, which:

1. Corrects the bi-temporal query contract.
2. Corrects the abstention and mandatory-rule semantics.
3. Replaces the type table with a decision-status schema.
4. Specifies election eligibility freezing.
5. Extends bi-temporality to state machines.
6. Structures the rule and evidence matrix for execution.
7. Extends the scenario matrix with election-relevant cases.
8. Refines ML and formal methods scope.

Do not derive bounded contexts yet. The next milestone is demonstrating that the model can explain real cases, survive counterexamples, and preserve historical decisions. Only when those conditions are met should bounded contexts be derived from validated business responsibilities and consistency boundaries.

**Authorization required:** ARB approval to proceed from v0.3 specification to evidence collection and falsification, contingent on the rule and evidence matrix being populated with at least three organization types.
