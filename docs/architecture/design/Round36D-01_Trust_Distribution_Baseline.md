# Round 36D-01 — Trust Distribution Baseline

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 36D-01
**Date:** 2026-06-14
**Status:** SUBMITTED FOR ARB REVIEW

---

## 1. Purpose and Governing Instruction

Round 36D investigates trust distribution.

Before investigating how authority might be distributed, this document establishes what the relevant concepts mean. The constitutional vocabulary must be defined before it can be evaluated.

This document produces no architecture decisions.
It introduces no cryptographic mechanisms.
It does not evaluate ElectionGuard, threshold schemes, or blockchain.
It does not create bounded contexts or aggregates.
It does not write ADRs.

The governing question for Round 36D:

```
When is authority distribution constitutionally required,
and when is behavioral integrity sufficient?
```

Before this question can be answered, seven concepts require definition:

```
Trust
Authority
Responsibility
Accountability
Ownership
Distribution
Independence
```

These are not synonyms. Conflating them produces category errors in constitutional reasoning. This document defines each, then examines the distinctions that matter for Round 36D.

---

## 2. The Seven Constitutional Concepts

### 2.1 Trust

Trust is the relationship between a trustor and a trustee in which the trustor accepts claims or depends on behavior without independent verification of every act.

```
Trust = Accepted Dependence Under Incomplete Information
```

Trust is not certainty. If the trustor could verify every action independently, trust would be unnecessary — direct verification would substitute for it.

In the election context:
- Voters trust that their vote is recorded as cast
- Auditors trust that the audit record reflects real events
- Society trusts that the certified result reflects the actual tally

Each of these trust relationships exists precisely because direct independent verification of every act is not available to the trustor.

**Constitutional implication:** Trust is a structural relationship, not merely a psychological one. It can be constitutionally required, constitutionally forbidden, or constitutionally neutral depending on the specific relationship.

Trust may be grounded in more than one way:

```
OBS-36D-01-1 (APPLIED):

Trust may arise from:

  A) Behavioral integrity — the trustor accepts dependence based on the
     actor's demonstrated or expected correct behavior. The actor has
     behaved correctly in the past, or is expected to do so.

  B) Structural guarantees — the trustor accepts dependence based on
     constitutional design that makes unilateral corruption non-sufficient.
     The structure, not the actor's will, prevents betrayal.

  C) Hybrid arrangements — some combination of behavioral and structural
     grounding, where behavioral integrity is verifiable because structural
     independence of the verifier is ensured.

Round 36D must determine which constitutional functions permit each form.
A trust grounding based solely on behavioral integrity is constitutionally
weaker when the behavioral claim is self-referential — when the same actor
who would act incorrectly also controls the conditions of verification
(TC5-NCQ-04 applies).
```

### 2.2 Authority

Authority is the legitimate power to make decisions that others are constitutionally bound to accept.

```
Authority = Legitimate Decision Power
```

Two properties are contained in this definition and must not be separated:

- **Legitimate** — the power has a recognized constitutional basis
- **Decision power** — it produces outcomes others must accept

Technical capability is not authority. A system may have the technical capability to alter a vote record. That capability does not confer constitutional authority to do so.

Claimed authority is not authority. An actor may claim to hold authority without that claim being constitutionally grounded.

**Constitutional implication:** Authority gaps (where no actor holds legitimate decision power) do not produce neutral outcomes — they produce outcomes by default, determined by whoever has the technical capability or institutional inertia to act.

### 2.3 Responsibility

Responsibility is the obligation to perform a function correctly.

```
Responsibility = Obligation to Perform
```

Responsibility can be assigned without authority. A function can be made someone's responsibility without giving them the authority to determine how it is performed. A system component can be responsible for publishing a result without having authority over what the result is.

Responsibility can be distributed without distributing authority. Multiple actors can each bear responsibility for a function while authority over that function remains centralized.

**Constitutional implication:** Distributed responsibility does not imply distributed authority. Designs that distribute responsibility while concentrating authority produce a constitutional imbalance — actors are answerable for outcomes they cannot constitutionally control.

### 2.4 Accountability

Accountability is the capacity for an actor to be held answerable for their actions.

```
Accountability = Attributable Answerability
```

Accountability requires two preconditions:

1. **Attributability** — the action must be traceable to the actor
2. **Answerability mechanism** — there must be a constitutionally recognized process for holding the actor answerable

Accountability without authority is constitutionally unjust — holding an actor answerable for decisions they lacked the power to control.

Authority without accountability is constitutionally dangerous — the power to make binding decisions without answerability for their correctness or legitimacy.

**Constitutional implication:** Constitutional trustworthiness requires that authority and accountability be matched. Where authority exists, accountability must exist. Where accountability is required, authority must be present to make it meaningful.

### 2.5 Ownership

Ownership is holding exclusive write authority over a defined scope.

```
Ownership = Exclusive Write Authority Within Scope
```

In the DDD sense established in Rounds 33–35: the aggregate that owns an invariant is the sole writer. No other aggregate may alter what the owning aggregate is responsible for.

Ownership differs from constitutional authority in a critical way:

- Ownership is a design assignment within the system
- Authority is a constitutional property that the system must respect, not assign

An aggregate can own a data record while a separate constitutional authority governs the validity of what that record contains.

```
Example:
  Vote aggregate owns the vote record (DDD ownership)
  Election constitution governs whether the election was valid (constitutional authority)
  These are not the same thing
```

**Constitutional implication:** Ownership gaps (D43, Gap A-3) and authority gaps are related but distinct. Resolving an ownership gap in the DDD sense does not automatically confer constitutional authority, and vice versa.

### 2.6 Distribution

Distribution is the state in which authority is held by multiple structurally independent actors such that no single actor's failure or corruption is sufficient to undermine the constitutional function.

```
Distribution = Authority Across Structurally Independent Actors
           Such That Single-Actor Failure Is Non-Sufficient
```

Distribution is a structural property, not a procedural one. An authority can be procedurally distributed — multiple actors are involved in a process — while remaining structurally concentrated — one actor controls the outcome.

```
Example:
  Procedural distribution: multiple administrators approve enrollment
  Structural concentration: one administrator can override all others
  → Structurally concentrated despite procedural appearance of distribution
```

Distribution is not fragmentation. Distribution requires that each actor holds constitutionally meaningful authority. Dividing a function among actors who each hold only a ceremonial role does not constitute constitutional distribution.

**Constitutional implication:** "Designed Distribution ≠ Actual Distribution" (AIC-36C-06-05). A system can be designed for distribution and implemented in a way that concentrates authority. Verifying actual distribution requires a mechanism that is itself constitutionally independent.

### 2.7 Independence

Independence is the property of not being controllable by the party whose actions are being evaluated.

```
Independence = Freedom From Control By The Evaluated Party
```

Independence is a relationship property, not an actor property. An actor is not independent in the abstract — they are independent with respect to a specific party in a specific context.

```
Example:
  A certification authority may be independent of the election operator
  but not independent of the funding body that pays both
  → Independent with respect to operations; not independent with respect to finance
```

Independence is required for trust relationships where direct verification is unavailable. If the evaluator is controlled by the evaluated, the evaluation is self-referential.

**Constitutional implication:** Independence is graduated, not binary. The constitutional question is: independent with respect to what? Which independence relationships are constitutionally required is an open question that this round must investigate.

---

## 3. The Distinction Matrix

The seven concepts are not synonyms. The following distinctions are most constitutionally consequential for Round 36D.

### 3.1 Ownership ≠ Authority

```
Ownership: who holds write rights within the system design
Authority: who holds legitimate decision power over what is correct

A system assigns ownership.
A constitution grounds authority.
These operate at different levels.
```

The enrollment authority gap (D43) is simultaneously an ownership gap and an authority gap. But they are not the same gap. Assigning ownership to an aggregate resolves the DDD design question. It does not resolve the constitutional question of which actor has legitimate authority over enrollment decisions.

### 3.2 Responsibility ≠ Accountability

```
Responsibility: obligation to perform
Accountability: capacity to be held answerable

Responsibility can exist without accountability (no answerability mechanism)
Accountability can exist without responsibility (answerable for others' actions)
Constitutional soundness requires the two to be matched
```

In the election context: an audit function can be given responsibility for recording events without being held constitutionally accountable if its records are incomplete. If no answerability mechanism exists, the responsibility is nominal.

### 3.3 Authority ≠ Trust

```
Authority: legitimate decision power
Trust: accepted dependence without full verification

Authority can be granted to an untrustworthy actor.
Trust can be extended to an actor with no formal authority.
Constitutional trustworthiness requires that trust in institutional relationships
be grounded in something more than assertion.
```

This distinction is at the core of TC5-NCQ-04. Behavioral integrity is a trust claim. Structural distribution is a constitutional claim. They answer different questions:

- Behavioral integrity asks: "Has this actor behaved correctly?"
- Structural distribution asks: "Is this actor constitutionally capable of unilateral harm?"

### 3.4 Distribution ≠ Independence

```
Distribution: authority held by multiple actors
Independence: freedom from control by the evaluated party

Distribution without independence: multiple actors all controlled by one party
Independence without distribution: single independent actor

Both may be constitutionally required in some contexts.
Neither implies the other.
```

An election can have a structurally distributed authority structure — multiple independent bodies — where the bodies are not independent of each other (they report to the same governing body, share funding, share personnel). In that case, structural distribution exists by design but independence does not exist in fact.

---

## 4. Two Concentration-Distribution Paradoxes

### 4.1 Can authority be concentrated while responsibility appears distributed?

Yes. This is the more common failure mode.

```
Pattern:
  Multiple actors are each assigned responsibility for a function
  One actor retains override authority over all others
  → Responsibility is distributed; authority is concentrated

Constitutional consequence:
  Actors bear responsibility for outcomes they cannot control
  The concentrated authority holder bears authority without full visibility
    of the distributed responsibility actors' work
  Accountability becomes ambiguous: who is answerable for a failure?
```

In the election context: multiple administrators may each be responsible for verifying enrollment data. If one actor retains the ability to override all enrollments, the distributed responsibility does not distribute the constitutional risk.

### 4.2 Can responsibility be distributed while authority remains concentrated?

Yes. This is the inverse paradox.

```
Pattern:
  A single actor holds constitutional authority over a function
  That actor delegates execution to multiple parties
  → Authority is concentrated; execution is distributed

Constitutional consequence:
  The concentrated authority holder is constitutionally accountable
    for the actions of all executing parties
  The executing parties bear responsibility without authority
  A failure in any executing party reflects on the authority holder
```

In the election context: a central certification authority may delegate audit observation to regional observers. The central authority retains constitutional certification authority. The observers bear responsibility for accuracy without holding certification authority. This is not the same as distribution of authority — it is delegation of execution under concentrated authority.

**Constitutional significance of both paradoxes:**

Both paradoxes show that the surface appearance of distribution — multiple actors, multiple roles, multiple checkpoints — does not imply constitutional distribution of authority. Trustworthiness analysis must look past the organizational chart to the actual location of decision power.

---

## 5. What Constitutional Properties Require Distribution?

This is the central question for Round 36D. This section establishes candidate positions, not answers.

### 5.1 Candidate Properties That May Require Distribution

```
CANDIDATE-36D-01: Certification of election validity
  If a single actor certifies the election, and that actor is not independent
  of the election's outcome, the certification is self-referential.
  Distribution may be constitutionally required to break the self-reference.
  (TC5-NCQ-04 applies; TC4-NCQ-03 applies)

CANDIDATE-36D-02: Governance criteria specification
  If a single actor specifies the criteria by which elections are evaluated,
  that actor can manipulate trustworthiness assessments by adjusting criteria.
  Distribution of criteria-setting authority may be constitutionally required.
  (TC3-NCQ-04 applies; TF-36C-04-14 applies)

CANDIDATE-36D-03: Audit independence
  If the audit function is controlled by the audited party, the audit
  is constitutionally void.
  Some form of independence from the audited party is constitutionally required.
  Whether this requires structural distribution or actor independence
  is unresolved (TC5-NCQ-03 applies)

CANDIDATE-36D-04: Enrollment authority
  D43 establishes that enrollment authority ownership is undefined.
  If enrollment authority is concentrated by institutional gravity in the
  operational actor, TC-5 risk applies directly.
  Constitutional distribution of enrollment authority is a candidate requirement.
```

### 5.2 Candidate Properties That May Not Require Distribution

```
CANDIDATE-36D-05: Evidence recording at the aggregate level
  Individual vote recording (Vote aggregate) may not constitutionally require
  distributed authority — the invariant is about correctness of the record,
  not about who holds record-keeping authority.
  Behavioral correctness may be constitutionally sufficient here.
  (TC5-NCQ-04 applies but may resolve differently for this function)

CANDIDATE-36D-06: Audit evidence reception
  The Audit context observes events published by other aggregates.
  Reception of events may not require distributed authority —
  the question is whether what is received is accurate, not who receives it.
  Distribution of the audit reception function does not address the underlying
  provenance and completeness questions.
  (TC2-NCQ-02 applies)

CANDIDATE-36D-07: Governance state transitions — WEAKENED (OBS-36D-01-2 applied)
  Governance state transitions (Opening voting, Closing voting, Freezing rules,
  Declaring result) are not ordinary operations. They directly affect legitimacy.
  The prior formulation — "execution may be by a single authority if the rules
  are pre-specified" — understates the risk.

  The correct distinction is:
    Authority to AUTHORIZE a transition (constitutionally governed — who may permit it)
    Execution of a transition (operationally governed — who performs the permitted act)

  These must remain separate questions. If the same actor holds both authorization
  authority and execution capability, and authorizes transitions at will, the
  constitutional rule constraint is nominal, not actual.

  Whether GovernanceState transition AUTHORIZATION requires distribution or independence
  is unresolved — this is a 36D-02 evaluation target. Do not assume execution and
  authorization are constitutionally equivalent.
  (D35/D37 implications; TC3-NCQ-04 applies)
```

### 5.3 The Governing Distinction

The candidate positions above suggest a preliminary distinction:

```
Functions where the outcome depends on the actor's judgment
  → Distribution or independence may be constitutionally required
  → Self-reference risk is present

Functions where the outcome is governed by pre-specified rules
  → Behavioral integrity may be constitutionally sufficient
  → Self-reference risk is absent if the rules are fixed independently
```

This is a candidate distinction, not a confirmed finding. It is the primary hypothesis for Round 36D to evaluate.

---

## 6. Behavioral Integrity vs Structural Distribution

TC5-NCQ-04 is the most consequential open question in the program:

```
Is structural distribution of authority constitutionally required,
or can behavioral integrity substitute?
```

### 6.1 What is Behavioral Integrity?

```
Behavioral integrity is the claim that an actor, despite holding concentrated
authority, will behave correctly because:
  - they are motivated to do so (reputation, incentives, values)
  - they are capable of doing so (competence, resources)
  - they are monitored (audit trail, public observation)
```

The constitutional case for behavioral integrity as sufficient:

```
If an actor consistently behaves correctly, and this can be verified,
structural distribution adds cost and coordination complexity without
adding constitutional protection.

Behavioral integrity is sufficient if:
  the behavior can be independently verified
  the actor cannot unilaterally suppress verification
  the verification mechanism is not controlled by the actor
```

The constitutional case against behavioral integrity as sufficient:

```
Behavioral integrity is a trust claim.
Trust claims require verification.
If the actor holds concentrated authority, they control the conditions
under which their behavior is verified.
The verification mechanism is itself subject to the concentrated authority.
→ Behavioral integrity becomes self-referential under concentration.
```

This is TC5-Q2 from Round 36C-06: full convergence makes all trustworthiness claims self-referential.

### 6.2 What is Structural Distribution?

```
Structural distribution is the property that authority is held by
actors who are constitutionally independent of each other,
such that no single actor's failure or corruption is sufficient
to undermine the constitutional function.
```

The constitutional case for structural distribution as required:

```
Self-referential trustworthiness claims cannot be verified from inside
the concentrated authority. Only structural distribution breaks
the self-reference by providing an external perspective with
constitutional standing.
```

The constitutional case against structural distribution as always required:

```
Distribution introduces new attack surfaces: compromise multiple
structurally separate authorities.
Distribution requires coordination mechanisms that may themselves
introduce concentration or failure points.
Not all functions are equally sensitive to concentration risk.
For low-sensitivity functions, distribution may be constitutionally
disproportionate.
```

### 6.3 The Candidate Resolution Framework

This document does not resolve TC5-NCQ-04. It proposes a candidate framework for how Round 36D might evaluate it:

```
A constitutional function requires structural distribution when:
  (a) the outcome depends on the actor's judgment, AND
  (b) the actor's behavior cannot be independently verified
      without structural independence, AND
  (c) a single actor's failure would be constitutionally non-recoverable

A constitutional function may be satisfied by behavioral integrity when:
  (a) the outcome is governed by pre-specified constitutional rules, AND
  (b) behavioral compliance with those rules can be verified by
      a structurally independent party, AND
  (c) failure is detectable and recoverable
```

This framework is a hypothesis for 36D evaluation, not a confirmed finding.

A fourth case must be added (OBS-36D-01-4 applied):

```
A constitutional function may fail BOTH tests:

  Case 4: Constitutional Prohibition
    A function may be constitutionally impermissible regardless of
    how authority is distributed or how behavioral integrity is assured.
    In such cases the correct constitutional response is prohibition —
    the function itself should not exist.

    This is not a degenerate case. It is a genuine possibility when:
      (a) the function creates self-reference that no structural design resolves, OR
      (b) the function concentrates authority in a form that is constitutionally
          incompatible with democratic legitimacy regardless of distribution, OR
      (c) the TC3-NCQ-04 recursion ("who governs the governors?") has no
          constitutionally grounded terminal point for this function

    36D-NCQ-02 must remain visible in all 36D evaluations.
    For every function evaluated in 36D-02, ask:
      Can this function be made constitutionally trustworthy through distribution?
      Or is prohibition the constitutional answer?
```

```
36D-HYP-01 (CANDIDATE HYPOTHESIS):
  The behavioral integrity vs structural distribution question
  resolves differently for different constitutional functions.
  The relevant variable is not the function itself,
  but the self-referential risk under concentration for that function.
  A fourth possibility — constitutional prohibition — must also be evaluated
  for each function.
```

---

## 7. 36C Hypotheses Carried Into 36D

The following findings from Round 36C become formal hypotheses for 36D evaluation. They are carried as CANDIDATE until 36D either corroborates or refines them.

```
36D-H01 (from TC5-DI-03):
  HYPOTHESIS: Ownership Assignment, Authority Distribution, and Structural Independence
  are three independent constitutional prerequisites for trustworthiness.
  None implies the others.
  36D must: corroborate, refine, or reject this chain.

36D-H02 (from TF-36C-04-14):
  HYPOTHESIS: Governance threats (TC-3) are independent of evidence threats (TC-1, TC-2).
  Legitimate governance is a constitutional requirement separate from evidence integrity.
  36D must: examine what "legitimate governance" requires in terms of authority distribution.

36D-H03 (from TF-36C-05-01):
  HYPOTHESIS: Certifier legitimacy ≠ certification validity.
  Constitutional certification requires that the certifier's authority is grounded
  independently of the certified party.
  36D must: examine what constitutional grounding for certification authority requires.

36D-H04 (from CF-36C-CLOSURE-06):
  HYPOTHESIS: The dominant risk in election trustworthiness is constitutional, not cryptographic.
  Governance legitimacy, authority distribution, and certification independence
  cannot be provided by cryptographic mechanisms.
  36D must: determine which functions require constitutional distribution vs
  which functions cryptographic integrity can address.

36D-H05 (from D43):
  HYPOTHESIS: Enrollment authority concentrated by institutional gravity in the
  operational actor constitutes a TC-5 risk at the Eligibility/Enrollment boundary.
  36D must: evaluate whether enrollment authority is a function that constitutionally
  requires structural distribution.
```

---

## 8. D43 at the Constitutional Level

D43 (Enrollment Authority Ownership Gap) was identified as a TC-5 instance in Round 36C-Closure. At the conceptual level established in this document, D43 looks as follows:

```
DDD ownership gap: which aggregate owns enrollment data?
  → Design question; resolved by aggregate assignment

Authority gap: who holds legitimate constitutional authority over enrollment decisions?
  → Constitutional question; cannot be resolved by aggregate assignment alone

The authority gap is the constitutionally significant form of D43.

Current state:
  Ownership: unassigned (D43)
  Authority: held by operational actor by institutional gravity
  Responsibility: unclear (operational actor de facto)
  Accountability: unclear (no mechanism identified)
  Independence: no independent oversight of enrollment decisions identified

D43 at constitutional level:
  Authority is concentrated at the Eligibility/Enrollment boundary
  by default, not by constitutional design.
  Default concentration is constitutional risk (TC-5).
  Whether constitutional distribution is required at this boundary
  is a 36D investigation target (36D-H05).
```

```
OBS-36D-01-3 (APPLIED): D43 as a Broader Pattern

Enrollment remains the discovered instance of D43 — the specific boundary
where the authority ownership gap was first identified.

However, Round 36B and 36C evidence suggests D43 may represent a broader
constitutional pattern: Authority Ownership Gap, appearing wherever
authority over a constitutional function is undefined and defaults
to the operational actor by institutional gravity.

Candidate additional instances of the D43 pattern:

  D43-instance-ENROLLMENT:     Enrollment authority (confirmed — original D43)
  D43-instance-CERTIFICATION:  Who holds legitimate certification authority?
                                (TC4-NCQ-01 — unresolved)
  D43-instance-CRITERIA:       Who holds authority over election validity criteria?
                                (TC3-NCQ-04 — unresolved)
  D43-instance-AUDIT:          Who holds constitutional authority over audit scope?
                                (Gap A-3 + TC1-NCQ-01 — unresolved)

Round 36D must determine:
  (a) whether D43 is a narrow enrollment-specific gap, or
  (b) whether D43 names a class of authority ownership gaps
      appearing wherever constitutional authority is unassigned

If (b), D43 becomes a constitutional vulnerability category,
not merely a single debt item. Do not assume the pattern is limited to Enrollment.
```

---

## 9. Unresolved Questions for Round 36D-02+

The following questions were generated by this baseline and require investigation in subsequent 36D sub-rounds:

```
36D-NCQ-01:
  Does the candidate resolution framework (Section 6.3) hold?
  Does the self-referential risk criterion correctly identify which functions
  require structural distribution?

36D-NCQ-02:
  Is there a class of constitutional functions where neither behavioral integrity
  nor structural distribution is sufficient — requiring instead constitutional
  prohibition (the function itself is constitutionally impermissible)?
  (TC3-NCQ-04 recursion may apply here)

36D-NCQ-03:
  Can the three-prerequisite chain (TC5-DI-03: Ownership → Authority → Independence)
  be refined to indicate which prerequisite is binding for which function?
  Or is the chain a unit that applies as a whole?

36D-NCQ-04:
  Is there a minimum unit of constitutional distribution —
  a threshold below which "distribution" is merely procedural,
  not constitutional?
  (TC5-NCQ-01 from Round 36C; not resolved here)

36D-NCQ-05:
  What is the constitutional relationship between Trust (Section 2.1) and Authority (Section 2.2)?
  Can trust substitute for authority where authority is absent?
  Or does the absence of authority make trust constitutionally inadequate regardless?
```

---

## ARB Decision

```
Round 36D-01 — Trust Distribution Baseline

[ARB REVIEW COMPLETE]

Research Discipline:     HIGH
Conceptual Clarity:      HIGH
Governance Discipline:   HIGH
Architecture Neutrality: HIGH
Confidence:              HIGH

Central outputs:
  Seven constitutional concepts defined and distinguished
  Ownership ≠ Authority ≠ Responsibility ≠ Accountability
  Distribution ≠ Independence
  Two concentration-distribution paradoxes identified
  Candidate framework for TC5-NCQ-04 resolution (Section 6.3)
    — extended to four cases including Constitutional Prohibition (OBS-36D-01-4)
  Five 36C hypotheses formalized as 36D research targets (36D-H01 through 36D-H05)
  D43 examined at constitutional level; extended to broader pattern (OBS-36D-01-3)
  Five new NCQs for 36D-02+ (36D-NCQ-01 through 36D-NCQ-05)

ARB Observations Applied:

  OBS-36D-01-1 (APPLIED):
    Trust definition expanded to three grounding forms:
    Behavioral Integrity, Structural Guarantees, Hybrid Arrangements.
    Round 36D must determine which constitutional functions permit each.
    Self-referential behavioral claims are constitutionally weaker (TC5-NCQ-04).

  OBS-36D-01-2 (APPLIED):
    CANDIDATE-36D-07 (GovernanceState transitions) revised.
    Authority to AUTHORIZE a transition and EXECUTION of a transition
    must remain separate constitutional questions throughout 36D.
    Governance transitions directly affect legitimacy and must not be
    treated as ordinary operational functions.

  OBS-36D-01-3 (APPLIED):
    D43 scope extended: Enrollment remains the discovered instance.
    Round 36D must evaluate whether D43 is a broader Authority Ownership Gap
    pattern appearing in Certification, Criteria, and Audit Oversight.
    Four candidate D43 instances documented.

  OBS-36D-01-4 (APPLIED):
    Section 6.3 extended to four cases.
    Constitutional Prohibition added as Case 4: a function may fail both
    behavioral integrity and structural distribution tests — the constitutionally
    correct answer may be that the function should not exist.
    36D-NCQ-02 must remain visible throughout all 36D evaluations.

Governing Rule Compliance:
  No architecture decisions made.
  No ElectionGuard, threshold cryptography, blockchain, or cryptographic mechanisms.
  No bounded contexts or aggregates created.
  No ADRs written.
  No candidates promoted to confirmed.
  Conceptual baseline only.

Round 36D-01: APPROVED

Governing Instructions for Round 36D-02 — Authority Distribution Patterns:

  Purpose:
    Determine which constitutional functions require which authority pattern.

  The four authority patterns to evaluate:
    CONCENTRATED:  A single actor may hold this authority constitutionally
    DISTRIBUTED:   Multiple independent actors are constitutionally required
    INDEPENDENT:   Authority must be held independently of the evaluated party
    PROHIBITED:    The function itself may be constitutionally impermissible

  Functions to evaluate (minimum):
    Vote Recording
    Enrollment Authority
    Certification of Election Validity
    Criteria Definition (election validity criteria)
    Audit Oversight
    Governance State Transitions (authorization, not execution)
    Result Declaration

  Method:
    For each function, apply the Section 6.3 framework (four cases).
    Produce a candidate matrix: Function → Authority Pattern.
    Do not resolve — produce candidates for ARB evaluation.

  Mandatory constraints:
    No ElectionGuard, threshold cryptography, blockchain, or specific mechanisms.
    No bounded contexts or aggregates.
    No ADRs.
    No implementation proposals.
    Constitutional evaluation only.

Round 36D-02: AUTHORIZED
```
