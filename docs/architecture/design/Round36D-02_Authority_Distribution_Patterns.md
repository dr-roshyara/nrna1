# Round 36D-02 — Authority Distribution Patterns

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 36D-02
**Date:** 2026-06-14
**Status:** SUBMITTED FOR ARB REVIEW

---

## 1. Purpose and Governing Instruction

Round 36D-01 defined the constitutional vocabulary.
Round 36D-02 uses that vocabulary to evaluate constitutional functions.

The governing question:

```
Which constitutional functions require:
  - Concentrated authority
  - Distributed authority
  - Independent authority
  - Prohibited authority
```

This document produces no architecture decisions.
It does not evaluate ElectionGuard, threshold schemes, or any specific mechanism.
It does not create bounded contexts or aggregates.
It does not write ADRs.
It does not resolve open NCQs — it produces candidates for ARB evaluation.

---

## 2. The Four Authority Pattern Definitions

Before evaluating functions, the four patterns require operational definitions.

### 2.1 CONCENTRATED

```
Definition:
  A single actor constitutionally holds this authority.
  No structural distribution is constitutionally required.
  Behavioral integrity, with independent verification, is constitutionally sufficient.

Constitutionally safe when:
  (a) The outcome is governed by pre-specified constitutional rules
  (b) Compliance with those rules is verifiable by a structurally independent party
  (c) Failure is detectable and recoverable
  (d) Self-referential risk is absent or bounded

Constitutional risk when:
  The actor controls both the function and the conditions of its verification.
```

### 2.2 DISTRIBUTED

```
Definition:
  Multiple structurally independent actors must each hold a constitutionally
  meaningful share of authority. No single actor's failure or corruption is
  sufficient to undermine the function.

Constitutionally required when:
  (a) The outcome depends on the actor's judgment, AND
  (b) A single actor's unilateral action would produce a non-recoverable outcome, AND
  (c) Behavioral integrity is not independently verifiable without structural separation

Constitutional risk when:
  Distribution is procedural but not structural (actors appear separate but are controlled
  by a single party — "distribution theater").
```

### 2.3 INDEPENDENT

```
Definition:
  The authority holder must be constitutionally independent of the party whose
  function is being evaluated. Independence is relational, not absolute.

Constitutionally required when:
  The function involves evaluation, certification, or oversight of another actor's
  behavior, such that control by that actor makes the function self-referential.

Constitutional risk when:
  Independence is declared but not structural — the authority holder is nominally
  separate but financially, operationally, or politically controlled by the evaluated party.

Note: Independence is a property of a relationship, not an actor.
  An actor may be independent of the election operator
  and dependent on the funding body simultaneously.
```

### 2.4 PROHIBITED

```
Definition:
  The function is constitutionally impermissible regardless of how authority is
  structured. The function itself creates a constitutional contradiction that no
  distribution or independence arrangement can resolve.

Constitutionally indicated when:
  (a) The function creates self-reference that no structural design resolves
  (b) The function requires authority that is constitutionally incompatible
      with democratic legitimacy in any concentration or distribution
  (c) The TC3-NCQ-04 recursion ("who governs the governors?") has no
      constitutionally grounded terminal point for this function

Note: Prohibition is a constitutional finding, not a design preference.
  It means the function as defined cannot be made constitutionally trustworthy.
```

---

## 3. The Evaluation Framework

Each function is evaluated against the Section 6.3 framework from Round 36D-01 (four cases):

```
Case 1: Concentrated authority is constitutionally sufficient
  → Outcome rule-governed + independent verification possible + failure recoverable

Case 2: Structural distribution constitutionally required
  → Judgment-dependent + single-actor failure non-recoverable + self-referential under concentration

Case 3: Independence constitutionally required
  → Evaluation function: authority holder must not be controllable by the evaluated party

Case 4: Constitutional prohibition
  → Function fails Cases 1-3; no structurally sound arrangement exists
```

Each function below produces one candidate pattern, references the applicable NCQs, and notes what remains open for ARB determination.

---

## 4. Function Evaluations

### 4.1 Vote Recording

```
What this function is:
  The act of accepting and recording a ballot into the system
  such that the vote is preserved and attributable to a valid casting event.

Evaluation:

  Is the outcome rule-governed?
    Yes — VO-1 (no voter identity in vote record) and validity rules are
    pre-specified. The vote recording function executes against pre-specified criteria.

  Is the outcome judgment-dependent?
    Partially — the validity of the ballot form may require interpretation.
    But the core recording act (store what was cast) is mechanical.

  Is failure recoverable?
    Conditional — suppression of a vote (TC-1 State 2) may be undetectable
    if the expected evidence set is undefined (Gap A-3). Recovery requires
    knowing what should have been recorded.

  Is independent verification possible?
    Conditional — audit trail exists (Vote → Audit event), but the completeness
    of that trail is governed by Gap A-3. Verification of completeness is
    self-referential if the expected set is undefined.

Candidate Pattern: CONCENTRATED with INDEPENDENT verification condition

  The recording act itself does not constitutionally require distributed authority.
  A single aggregate (Vote) may constitutionally hold write authority over
  the vote record.

  However, concentrated recording authority is constitutionally insufficient
  without a structurally independent mechanism to verify completeness.
  The condition — Gap A-3 resolution — is a prerequisite, not an implementation detail.

  Without Gap A-3 resolution: CONCENTRATED is constitutionally unsafe.
  With Gap A-3 resolution and independent verification: CONCENTRATED may be sufficient.

Open: TC1-NCQ-01 (expected evidence set); TC2-NCQ-02 (verification sufficiency).
```

### 4.2 Enrollment Authority

```
What this function is:
  The determination of who is eligible to participate in an election —
  which individuals hold the right to vote, what their voting scope is
  (national vs regional), and whether their eligibility can be revoked.

Evaluation:

  Is the outcome rule-governed?
    Partially — eligibility criteria may be pre-specified (citizenship, membership).
    But the application of criteria to specific individuals involves judgment.
    Edge cases (disputed status, late enrollment, corrections) require authoritative decision.

  Is the outcome judgment-dependent?
    Yes — enrollment decisions about specific individuals cannot be fully mechanized.
    The criteria cannot anticipate every case.

  Is failure recoverable?
    Partially — improperly excluded voters cannot retroactively vote after the election.
    Improperly included voters represent a constitutional contamination of the result.
    Both forms of failure may be non-recoverable post-election.

  Is independent verification possible?
    Only if the enrollment authority is not the same as the election operator.
    If concentrated in the operational actor (D43 current state): self-referential.

  D43 assessment:
    Enrollment authority is currently held by operational actor by institutional gravity.
    No constitutional grounding exists for this concentration.
    The concentrated actor can: include or exclude voters at will, with no independent check.

Candidate Pattern: INDEPENDENT (minimum); DISTRIBUTED (candidate requirement)

  Concentrated enrollment authority in the operational actor is constitutionally unsafe.
  Independence from the election operator is constitutionally indicated.

  Whether independence alone is sufficient, or whether structural distribution across
  multiple independent bodies is required, is an open question (36D-NCQ-01 applies;
  TC5-NCQ-04 applies to this function specifically).

  D43-instance-ENROLLMENT must be resolved before the constitutional pattern is settled.

Open: D43, TC5-NCQ-04 application to enrollment, minimum distribution threshold.
```

### 4.3 Election Validity Criteria Definition

```
What this function is:
  The specification of the rules by which an election's validity is judged —
  what constitutes a quorum, what constitutes a valid vote, what constitutes
  a constitutional result, and when the election is considered complete and binding.

Evaluation:

  Is the outcome rule-governed?
    This function IS the rules. It cannot be governed by itself.
    Criteria definition is inherently judgment-dependent — someone must decide
    what the criteria are.

  Is the outcome judgment-dependent?
    Yes — criteria definition is the exercise of constitutional authority.
    It cannot be mechanized or pre-specified by itself.

  Is failure recoverable?
    Partially — criteria established before the election cannot retroactively
    be changed without constitutional authorization (TC3-DI-03: criteria must be
    fixed before the decisions they govern). If corrupted criteria are used and
    discovered post-election, the election result is constitutionally contested.

  Is independent verification possible?
    Only if criteria are publicly declared before use AND the declaration mechanism
    is independent of the election operator. If the operator sets criteria at will:
    self-referential (TC3-Q3 from 36C-04).

  TC3-NCQ-04 applies:
    Who governs the governors? If the criteria-setting authority itself can change
    criteria without constitutional constraint, the recursion has no terminal point.

Candidate Pattern: INDEPENDENT (from election operator); DISTRIBUTED (candidate)

  Criteria definition must be constitutionally separated from election operation.
  The actor who operates the election cannot also determine the rules by which
  the election's validity is judged — this is the constitutional core of TC3.

  Whether criteria definition requires full structural distribution across independent
  bodies, or whether independence from the operator is sufficient, is unresolved.

  The criteria must be fixed before the elections they govern.
  Who holds authority to fix them — and who may change them, and when — is D43-instance-CRITERIA.

Open: TC3-NCQ-04 recursion; D43-instance-CRITERIA; criteria immutability enforcement.
```

### 4.4 Audit Oversight

```
What this function is:
  The function of observing, collecting, and preserving evidence of election events
  for the purpose of enabling post-election verification, challenge, and certification.

Evaluation:

  Is the outcome rule-governed?
    Partially — what events should be audited can be pre-specified.
    But which events actually occurred is a fact of the election, not a choice.
    The audit function must observe what happened, not decide what should have happened.

  Is the outcome judgment-dependent?
    The observation itself may not be judgment-dependent.
    But the determination of completeness — whether the audit record captures
    all constitutionally required events — requires knowing the expected evidence set (Gap A-3).
    Gap A-3 means completeness judgment is currently undefined.

  Is failure recoverable?
    No — missing audit evidence for a completed election cannot be recreated.
    TC1-DI-01 applies: absence of evidence ≠ evidence of absence.

  Is independent verification possible?
    Only if the audit function is not controlled by the election operator.
    If the operator can suppress audit events (TC-1 State 2), the audit record
    is not independently trustworthy regardless of its internal consistency.

  TC2-NCQ-02 applies:
    Is passive observation constitutionally sufficient, or does the audit function
    require active provenance governance?

Candidate Pattern: INDEPENDENT (from election operator); Gap A-3 resolution prerequisite

  The audit function cannot be constitutionally trustworthy if controlled by the audited party.
  Independence from the election operator is constitutionally indicated.

  Whether independence requires structural distribution (multiple independent audit
  observers) or whether a single independent observer is constitutionally sufficient
  is unresolved. The answer may depend on TC5-NCQ-01 (minimum distribution threshold).

  Gap A-3 is a constitutional prerequisite for audit trustworthiness.
  Without a defined expected evidence set, completeness cannot be verified,
  and independence of the observer does not resolve the completeness problem.

Open: Gap A-3, TC2-NCQ-02, D43-instance-AUDIT, TC5-NCQ-01 (threshold).
```

### 4.5 Governance State Transitions — Authorization

```
What this function is:
  The constitutional authorization of transitions that change the election's
  operative state — opening voting, closing voting, freezing rules before the election,
  declaring results.

  Per OBS-36D-01-2: authorization of a transition is constitutionally distinct
  from execution of a transition. This evaluation concerns authorization.

Evaluation:

  Is the outcome rule-governed?
    Only if the rules governing WHEN a transition may occur are pre-specified
    and constitutionally fixed before the election begins. If the election operator
    determines transition timing at will, the outcome is judgment-dependent.

  Is the outcome judgment-dependent?
    For the authorization itself: yes, if timing is not rule-governed.
    "Open voting now" is a constitutional act, not a mechanical one.

  Is failure recoverable?
    Generally no — an election opened too early, closed too late, or frozen
    after vote collection has begun cannot be constitutionally "unopened."
    Most governance state transition failures are non-recoverable.

  Is independent verification possible?
    If the same actor who benefits from a particular transition timing also
    authorizes the transition, verification of correct timing is self-referential.

  D35/D37 status:
    GovernanceState transition authority is partially captured in D35 and D37
    (unresolved governance debts). This evaluation does not resolve those debts
    but notes they carry constitutional weight beyond the technical design.

Candidate Pattern: INDEPENDENT (authorization from operator); DISTRIBUTED (candidate)

  The actor who executes a governance state transition cannot be the same as the
  actor who constitutionally authorizes that the transition is permissible.
  Authorization must be constitutionally independent of operational benefit.

  Whether independent authorization (single independent authorizer) is sufficient,
  or whether distributed authorization (multiple independent bodies) is required,
  is unresolved. The non-recoverability of transition failures pushes toward
  distribution; the operational complexity of distributed authorization is a
  countervailing consideration.

  The authorization rules themselves must be constitutionally fixed before the
  election begins — this connects to Criteria Definition (Section 4.3).

Open: D35, D37, TC3-NCQ-04 (criteria recursion applies to authorization criteria),
      OBS-36D-01-2 (authorization vs execution distinction, which this evaluation applies).
```

### 4.6 Certification of Election Validity

```
What this function is:
  The terminal act by which a constitutionally authorized party declares
  that the election process was valid and the result is constitutionally binding.

Evaluation:

  Is the outcome rule-governed?
    Yes and No. The criteria for certification should be pre-specified (TC3-DI-03).
    But the act of certifying — applying the criteria to the specific election — requires
    judgment about whether the criteria are met.

  Is the outcome judgment-dependent?
    Yes — certification is a constitutional determination, not a mechanical output.
    Even with pre-specified criteria, the certifier must judge whether the election
    satisfies them.

  Is failure recoverable?
    No — a false certification is the terminal constitutional act (TF-36C-05-11).
    It amplifies all prior threat class successes into a public trustworthiness claim.
    Once declared, a false certification is constitutionally catastrophic.

  Is independent verification possible?
    Only if the certifier is structurally independent of the election operator
    and of the parties with interest in the result.

  TF-36C-05-01 applies:
    Certifier legitimacy ≠ certification validity.
    A valid certifier can certify a false result.
    An invalid certifier cannot produce a constitutionally valid certification.

  TC4-NCQ-01/02/03 remain open:
    Minimum independence threshold; succession of certifier authority; circular certification.

Candidate Pattern: INDEPENDENT (minimum, constitutionally required); DISTRIBUTED (candidate)

  Independence of the certifier from the certified party is constitutionally non-negotiable.
  The certification function is self-defeating if controlled by the election operator.

  Whether independent certification (single independent certifier) is constitutionally
  sufficient, or whether distributed certification (multiple independent certifying bodies)
  is required, depends on TC5-NCQ-04 and TC4-NCQ-01 (minimum threshold).

  TF-36C-05-15 from Round 36C: many TC-4 failures become possible only when authority
  is concentrated. Certification abuse and trust concentration are structurally linked.

  D43-instance-CERTIFICATION: who holds constitutional certification authority?
  If undefined, it defaults to the operational actor — constitutionally catastrophic
  given the terminal nature of the certification act.

Open: TC4-NCQ-01/02/03, TC5-NCQ-04, D43-instance-CERTIFICATION.
```

### 4.7 Result Declaration

```
What this function is:
  The act of declaring what the election result is — which candidates received
  which vote counts, what the outcome of the election is, and who holds the
  positions contested.

Evaluation:

  Is the outcome rule-governed?
    Yes — if the tally is correctly performed and the result criteria are
    pre-specified, result declaration should follow mechanically.
    The result is a mathematical consequence of valid votes cast under valid rules.

  Is the outcome judgment-dependent?
    Only if the tally is disputed or the result criteria are ambiguous.
    In a well-constituted election, result declaration should not require judgment.
    The function should be: compute and declare, not judge and decide.

  Is failure recoverable?
    No — a false result declaration produces a constitutionally incorrect outcome.
    The closer this declaration is to Certification (Section 4.6), the more
    non-recoverable the failure.

  D39 status:
    Result declaration is partially blocked by D39 (Results/Tallying boundary).
    The precise relationship between tallying and result declaration is unresolved.
    This evaluation is conditional on D39 resolution.

  Important distinction:
    Result Declaration (what the tally produced) ≠ Certification (that the election was valid)
    These are adjacent but constitutionally distinct functions.
    Result Declaration concerns mathematical outcome.
    Certification concerns constitutional validity of the process.

Candidate Pattern: CONCENTRATED (result computation) with INDEPENDENT verification

  The mathematical result of a valid tally is not a judgment — it is a computation.
  If the tally is rule-governed, the result declaration can be by the operational actor.

  However: the VERIFICATION that the tally was correct and complete requires
  an independent function. Result declaration by the operator + independent tally
  verification = candidate constitutionally sufficient arrangement.

  The failure mode: if the operator controls both the tally and the result declaration,
  and no independent verification of the tally exists, the arrangement is
  self-referential (TC-1 + TC-2 both apply to the tally record).

  Conditional on D39: the relationship between tallying and result declaration
  must be resolved before this pattern is confirmed.

Open: D39, tally verification independence (TC2-NCQ-02 analog for tally), Gap A-3 (completeness).
```

---

## 5. Candidate Authority Distribution Matrix

```
Function                        Candidate Pattern         Preconditions / Open Items

Vote Recording                  CONCENTRATED              Gap A-3 resolution required;
                                + INDEPENDENT             independent completeness verification
                                  (verification)          TC1-NCQ-01, TC2-NCQ-02

Enrollment Authority            INDEPENDENT (min)         D43 resolution required;
                                DISTRIBUTED (candidate)   TC5-NCQ-04 application;
                                                          independence minimum threshold

Criteria Definition             INDEPENDENT               D43-instance-CRITERIA;
                                DISTRIBUTED (candidate)   TC3-NCQ-04 recursion;
                                                          immutability enforcement

Audit Oversight                 INDEPENDENT               Gap A-3 prerequisite;
                                                          TC2-NCQ-02; D43-instance-AUDIT;
                                                          TC5-NCQ-01 (threshold)

Governance Transition           INDEPENDENT               Authorization ≠ Execution (OBS-36D-01-2);
  (Authorization)               DISTRIBUTED (candidate)   D35, D37; TC3-NCQ-04 application

Certification                   INDEPENDENT (required)    TC4-NCQ-01/02/03;
                                DISTRIBUTED (candidate)   D43-instance-CERTIFICATION;
                                                          TC5-NCQ-04; TF-36C-05-15

Result Declaration              CONCENTRATED              D39 resolution required;
                                + INDEPENDENT             independent tally verification;
                                  (verification)          Gap A-3 (completeness of tally)
```

---

## 6. Cross-Cutting Findings

### 6.1 Independence Is the Minimum Constitutional Requirement

Across all seven functions evaluated, independence from the operational actor appears as the minimum constitutional requirement. No function in the matrix produces a pattern where the election operator constitutionally holds authority without any independent check.

```
36D-CF-02-01 (CANDIDATE):
  Independence from the election operator is the minimum constitutional
  requirement across all identified constitutional functions.
  Concentrated authority in the operational actor is constitutionally unsafe
  for every function evaluated.

  Whether distribution beyond independence is required depends on:
    (a) whether the function involves non-recoverable failure, and
    (b) whether TC5-NCQ-04 resolves toward structural distribution requirement.
```

### 6.2 The D43 Pattern Appears in Five Functions

D43-instance candidates appear in: Enrollment, Criteria Definition, Audit Oversight, Governance Transition Authorization, and Certification.

```
36D-CF-02-02 (CANDIDATE):
  D43 is not an enrollment-specific debt item.
  D43 represents a class of constitutional authority ownership gaps:
  wherever constitutional authority is undefined, it defaults to the
  operational actor by institutional gravity.

  Five instances of the D43 pattern identified in Round 36D-02.
  No constitutionally grounded authority holder has yet been discovered
  for any of these five instances.
  In all five cases, default concentration produces constitutional risk.

  This elevates D43 from a single debt item to a constitutional vulnerability pattern
  requiring program-level attention.
```

### 6.3 Two Functions Have Precondition Dependencies

Vote Recording and Result Declaration both require preconditions to be met before their authority pattern is constitutionally safe:

```
Vote Recording:  Gap A-3 resolution → defines expected evidence set → enables completeness verification
Result Declaration: D39 resolution → defines tallying boundary → enables result verification

These preconditions are not implementation details.
They are constitutional prerequisites.
The authority pattern for these functions cannot be confirmed until the preconditions are met.
```

### 6.4 Non-Recoverability Drives Distribution Candidates

The functions where DISTRIBUTED appears as a candidate (beyond INDEPENDENT) are the functions where failure is constitutionally non-recoverable:

```
Enrollment: improperly excluded/included voters cannot be remedied post-election
Criteria Definition: retroactive criteria change is constitutionally prohibited
Governance Transition Authorization: transitions cannot be undone
Certification: false certification is the terminal constitutional act

For recoverable functions (Vote Recording with audit trail, Result Declaration
if tally errors are detectable): CONCENTRATED + INDEPENDENT verification may be sufficient.
For non-recoverable functions: DISTRIBUTED becomes a candidate requirement.

This supports 36D-HYP-01: the relevant variable is self-referential risk and
non-recoverability, not the function category itself.
```

---

## 7. The Constitutional Prohibition Test

Per OBS-36D-01-4: for each function, can it be made constitutionally trustworthy through distribution?

```
Vote Recording:                 No Case 4 indication — recordable with proper structure
Enrollment Authority:           No Case 4 indication — distributable with independent oversight
Criteria Definition:            Candidate Case 4 element:
                                  If TC3-NCQ-04 ("who governs the governors?")
                                  has no terminal point, criteria-setting authority
                                  may be constitutionally self-defeating regardless
                                  of how it is distributed. Carry to 36D-03.
Audit Oversight:                No Case 4 indication — observable with proper independence
Governance Transition Auth.:    No Case 4 indication — authorizable with proper independence
Certification:                  No Case 4 indication — certifiable with proper independence
Result Declaration:             No Case 4 indication — declarable with proper structure
```

```
36D-CF-02-03 (CANDIDATE — WEAK):
  Criteria Definition has a candidate Case 4 element.
  TC3-NCQ-04 ("who governs the governors?") identifies a recursion in criteria-setting authority.
  IF that recursion does not terminate constitutionally, THEN no distribution of criteria-setting
  authority resolves the constitutional problem.

  What Round 36D-02 does NOT establish:
    That the recursion never terminates.
    That Criteria Definition is probably prohibited.
    That distribution cannot work.

  What Round 36D-02 DOES establish:
    TC3-NCQ-04 creates a candidate Case 4 condition for this function.
    The Case 4 question must remain open and must be evaluated in 36D-03.

  Candidate status: weak. The recursion may terminate — 36D-03 must investigate.
  Carry to 36D-03 for further evaluation. Not a confirmed finding.
```

---

## 8. What This Matrix Does Not Settle

```
The matrix in Section 5 is a candidate matrix. It does not confirm:
  - The specific threshold for DISTRIBUTED (how many independent actors?)
  - Which form of INDEPENDENCE is constitutionally sufficient
  - Whether TC5-NCQ-04 resolves toward structural distribution for non-recoverable functions
  - Whether D43 instances can be resolved by assignment, or require constitutional design
  - The relationship between Result Declaration and Certification (D39 dependency)

These are inputs for ARB constitutional determination and subsequent 36D sub-rounds.
```

---

## 9. Governing Warning: Authority Distribution ≠ Bounded Context Distribution

```
OBS-36D-02-1 (GOVERNING — carry through all remaining 36D and into 36E):

Round 36D-02 discusses:
  Audit Authority
  Certification Authority
  Criteria Authority
  Enrollment Authority
  Governance Transition Authority

There is a future risk that a reader concludes:

  Authority = Context
  One authority → one bounded context

This conclusion would be WRONG.

The correct relationship is:

  One authority relationship can span multiple bounded contexts.
  One bounded context can participate in multiple authority relationships.
  Authority is a constitutional property.
  Bounded context is a design partition.
  These operate at different levels and must not be conflated.

Examples of the error to avoid:

  WRONG: "Certification Authority → create Certification Context"
  RIGHT: "Certification authority must be constitutionally independent;
         which context(s) are involved is a 36E question"

  WRONG: "Audit Authority → Audit Context owns authority"
         (Audit Context is a passive observer; authority is held by
          whoever defines the expected evidence set — currently undefined)

  WRONG: "D43 gap = missing bounded context"
         (D43 is an authority ownership gap; filling it requires
          constitutional assignment, not necessarily new context creation)

36E must keep authority relationships and bounded context partitions
as separate analytical dimensions. Collapsing them is a category error.
This warning applies to every remaining 36D sub-round.
```

---

## 10. New NCQs Generated

```
36D-NCQ-06:
  Independence is identified as the minimum constitutional requirement.
  But independence is relational — with respect to whom?
  What is the full set of independence relationships constitutionally required
  for each function? (Not just independent of the election operator —
  also: of the funding body, the political appointing authority, etc.)

36D-NCQ-07:
  D43 appears as a pattern across five functions.
  Is there a constitutional rule that explains why authority defaults to the operational
  actor — and if so, does the rule itself have constitutional standing?
  (TC3-NCQ-04 recursion applies here)

36D-NCQ-08:
  Non-recoverability is proposed as the variable that drives distribution candidates.
  Does non-recoverability constitute a constitutionally recognized category?
  Or is non-recoverability a practical consideration that does not carry
  constitutional weight on its own?
```

---

## ARB Decision

```
Round 36D-02 — Authority Distribution Patterns

[ARB REVIEW COMPLETE]

Research Discipline:     HIGH
Analytical Quality:      HIGH
Governance Discipline:   HIGH
Architecture Neutrality: HIGH
Confidence:              HIGH

Central outputs:
  Four authority patterns defined (Concentrated / Distributed / Independent / Prohibited)
  Seven constitutional functions evaluated against Section 6.3 framework
  Candidate Authority Distribution Matrix (Section 5)
  36D-CF-02-01: Independence from operator = minimum constitutional requirement (CANDIDATE)
  36D-CF-02-02: D43 is a pattern across five functions (CANDIDATE — discovery language applied)
  36D-CF-02-03: Criteria Definition has candidate Case 4 element (WEAK CANDIDATE — not confirmed)
  Non-recoverability finding: drives distribution candidates (supports 36D-HYP-01)
  OBS-36D-02-1: Authority Distribution ≠ Bounded Context Distribution (GOVERNING — carry to 36E)
  Three new NCQs (36D-NCQ-06 through 36D-NCQ-08)

ARB Observations Applied:

  Correction 1 (APPLIED):
    36D-CF-02-02 wording softened.
    "None of these five has a constitutionally grounded authority holder"
    → "No constitutionally grounded authority holder has yet been discovered
       for any of these five instances."
    Discovery discipline preserved.

  Correction 2 (APPLIED):
    Criteria Definition Case 4 weakened.
    Status changed to CANDIDATE — WEAK.
    Added explicit statement of what Round 36D-02 does NOT establish:
    that the recursion never terminates, that prohibition is probable,
    or that distribution cannot work.
    TC3-NCQ-04 creates the Case 4 question; it does not answer it.

  OBS-36D-02-1 (APPLIED — GOVERNING):
    Authority Distribution ≠ Bounded Context Distribution.
    Authority is constitutional. Bounded context is a design partition.
    These must not be conflated in any remaining 36D sub-round or in 36E.
    One authority relationship can span multiple contexts.
    One context can participate in multiple authority relationships.
    D43 gaps are authority ownership gaps, not missing contexts.

Governing Rule Compliance:
  No architecture decisions made.
  No ElectionGuard, threshold cryptography, blockchain, or cryptographic mechanisms.
  No bounded contexts or aggregates created.
  No ADRs written.
  No candidates promoted to confirmed.
  Constitutional evaluation only.

Round 36D-02: APPROVED

Governing Instructions for Round 36D-03 — Constitutional Independence Analysis:

  The most important unresolved question from 36D-02 is 36D-NCQ-06:
    "Independent from whom?"

  Independence was identified as the minimum constitutional requirement
  across all seven functions. But independence is relational — with respect
  to which specific parties must each function be independent?

  Round 36D-03 must build an independence matrix:

    For each function below, determine candidate independence relationships:
      Function: Enrollment, Criteria Definition, Audit Oversight,
                Governance Transition Authorization, Certification, Result Verification

    Independence relationships to evaluate for each function:
      - Independent of: Election Operator?
      - Independent of: Funding Body?
      - Independent of: Government / Political Authority?
      - Independent of: Certification Body itself (self-certification)?
      - Independent of: Criteria Setter?
      - Independent of: Auditor?
      - Other relationships emerging from 36D-02 findings?

    For each relationship: Required / Not Required / Open Question

  Also carry forward into 36D-03:
    - Whether TC3-NCQ-04 recursion terminates (Criteria Definition Case 4)
    - OBS-36D-02-1 governing warning (authority ≠ context)
    - D43 as cross-function pattern (candidate)

  Mandatory constraints:
    No ElectionGuard, threshold cryptography, blockchain, or specific mechanisms.
    No bounded contexts or aggregates.
    No ADRs.
    Constitutional evaluation only.

Round 36D-03: AUTHORIZED
```
