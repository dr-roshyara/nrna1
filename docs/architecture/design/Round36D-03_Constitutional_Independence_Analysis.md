# Round 36D-03 — Constitutional Independence Analysis

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 36D-03
**Date:** 2026-06-14
**Status:** SUBMITTED FOR ARB REVIEW

---

## 1. Purpose and Governing Instruction

Round 36D-02 established that independence from the election operator is the minimum constitutional requirement across all seven functions evaluated (36D-CF-02-01, CANDIDATE).

The governing question for Round 36D-03:

```
Independent from whom?
```

Independence is a relational property, not an actor property. An actor is not independent in the abstract — they are independent with respect to a specific party in a specific context. Declaring "independence is required" without specifying the independence relationship is constitutionally incomplete.

This document builds an independence matrix: for each constitutional function, which independence relationships are constitutionally candidate-required?

This document produces no architecture decisions.
It does not evaluate ElectionGuard, threshold schemes, or any specific mechanism.
It does not create bounded contexts or aggregates.
It does not write ADRs.
It carries OBS-36D-02-1 throughout: authority relationships and bounded context partitions are separate analytical dimensions.

---

## 2. Independence Relationships Under Evaluation

The following candidate independence relationships are evaluated for each function. These are not exhaustive — the matrix may generate additional relationships.

```
IR-A:  Independent of the Election Operator
         (the party that administers and executes the election)

IR-B:  Independent of the Funding Body
         (the party that finances the election or the election authority)

IR-C:  Independent of Government / Political Authority
         (the political body whose legitimacy the election determines or affects)

IR-D:  Independent of the Criteria Setter
         (the party that defined the rules for judging election validity)

IR-E:  Independent of the Certifier
         (the party that certifies the election as valid)

IR-F:  Independent of the Audit Function
         (the party responsible for observing and recording election events)

IR-G:  Self-independence (not evaluating one's own actions)
         (the actor holding authority cannot be the same actor whose
          actions are being evaluated, governed, or certified)

IR-H:  Independent of audited subject
         (the audit function must be independent of every actor whose
          actions it observes — not only of the election operator, but of
          enrollment authorities, certification bodies, and governance authorities
          whose conduct may be under audit scrutiny)
         Note: IR-A covers operator independence; IR-H extends this to all
         audited actors, which may extend beyond the operator.
```

**Assessment categories:**

```
REQUIRED:       Evidence from 36C/36D-01/36D-02 indicates constitutional requirement
CANDIDATE:      Plausible constitutional requirement; insufficient evidence to confirm
NOT REQUIRED:   Evidence indicates independence from this party is not constitutionally indicated
OPEN:           Insufficient evidence to assess; ARB determination required
```

---

## 3. Independence Matrix

### 3.1 Enrollment Authority

```
What constitutes this function:
  Determining who holds the right to vote, their voting scope (national vs regional),
  and whether eligibility can be modified or revoked.
  D43-instance-ENROLLMENT — no constitutionally grounded authority holder discovered.

Independence relationships:

  IR-A (Election Operator):    REQUIRED
    Enrollment controlled by the election operator enables inclusion/exclusion at will.
    This is the canonical TC-5 risk at the Eligibility/Enrollment boundary.
    Enrollment authority concentrated in the operator = operator controls the electorate.

  IR-B (Funding Body):         CANDIDATE
    A funding body that can condition continued funding on enrollment decisions
    holds de facto enrollment authority. Constitutional independence of enrollment
    from funding would prevent this. Insufficient evidence to confirm; plausible.

  IR-C (Government/Political): CANDIDATE
    The entity whose legitimacy depends on the election result has a constitutional
    conflict of interest in controlling who may vote. Independence from political
    authority is plausible. Specific form of independence is unresolved.

  IR-D (Criteria Setter):      NOT REQUIRED
    The criteria setter defines who is eligible; enrollment authority applies
    those criteria to individuals. These are constitutionally adjacent, not
    in conflict. Criteria setter and enrollment authority may be the same party
    provided the criteria are pre-specified and fixed.

  IR-E (Certifier):            OPEN
    If the certifier must validate that enrollment was properly conducted,
    the certifier must be independent of the enrollment authority.
    This is an independence requirement on the certifier, not on enrollment.
    No direct enrollment authority → certifier independence requirement identified.

  IR-F (Audit Function):       NOT REQUIRED
    Enrollment authority and audit oversight serve different constitutional functions.
    Audit observes; enrollment decides. No conflict identified that requires
    independence between these two authorities.

  IR-G (Self-independence):    REQUIRED
    Enrollment authority cannot constitutionally determine its own scope.
    An enrollment authority that can expand or restrict the eligible population
    at will, without external check, is constitutionally self-referential.
    Who has standing to challenge an enrollment decision must be independent
    of the enrollment authority itself.
```

### 3.2 Criteria Definition

```
What constitutes this function:
  Specifying the rules by which an election's validity is judged.
  D43-instance-CRITERIA — no constitutionally grounded authority holder discovered.
  TC3-NCQ-04: "who governs the governors?" — recursion; not yet determined to terminate.

Independence relationships:

  IR-A (Election Operator):    REQUIRED
    The operator who runs the election cannot constitutionally set the criteria
    by which their own election is judged valid. This is the TC-3 core finding:
    governance threats are independent of evidence threats. Criteria manipulation
    by the operator is a distinct threat class.

  IR-B (Funding Body):         CANDIDATE
    A funding body with the power to modify election validity criteria holds
    indirect outcome authority. The criteria must be constitutionally stable
    against funding-conditional modification.

  IR-C (Government/Political): CANDIDATE
    The political body whose legitimacy is determined by the election outcome
    has a constitutional conflict of interest in setting the criteria for
    election validity. This is a plausible candidate requirement.
    Specific form of independence is unresolved.

  IR-D (Criteria Setter):      IR-G APPLIES (see below)
    Self-referential: the criteria setter cannot constitutionally set criteria
    that govern the criteria setter's own authority. TC3-NCQ-04 recursion.

  IR-E (Certifier):            CANDIDATE
    If the criteria setter and certifier are the same party, certification
    is constitutionally circular: the criteria setter certifies compliance
    with criteria they themselves set. This is a candidate independence
    requirement. TC4-NCQ-03 applies (circular certification question).

  IR-F (Audit Function):       NOT REQUIRED
    Audit observes against criteria; it does not set criteria. No direct
    constitutional conflict between criteria setting and audit observation
    authority identified.

  IR-G (Self-independence):    REQUIRED — with open question
    Criteria cannot constitutionally govern themselves.
    The criteria-setting authority cannot be subject to criteria it sets
    for itself. Some form of self-independence is constitutionally indicated.
    Whether this is achievable — or whether this is the Case 4 element from
    36D-CF-02-03 — depends on whether TC3-NCQ-04 recursion terminates.

    If TC3-NCQ-04 terminates: IR-G is satisfiable by a terminal authority
    (a constitutional provision, founding document, or equivalent).
    If TC3-NCQ-04 does not terminate: IR-G is constitutionally unsatisfiable
    and Case 4 (prohibition) becomes the candidate outcome.

    Current finding: open. Case 4 is a candidate, not a conclusion.
```

### 3.3 Audit Oversight

```
What constitutes this function:
  Observing, collecting, and preserving evidence of election events
  for post-election verification, challenge, and certification.
  D43-instance-AUDIT — no constitutionally grounded authority holder discovered.
  Gap A-3: expected evidence set undefined.

Independence relationships:

  IR-A (Election Operator):    REQUIRED
    If the operator controls the audit function, the operator can suppress
    evidence (TC-1, State 2) without detection. Audit independence from the
    operator is the canonical independence requirement identified in 36B.

  IR-B (Funding Body):         CANDIDATE
    A funding body that can withdraw funding from the audit function can
    effectively suppress audit activity. Constitutional protection of audit
    funding independence is plausible.

  IR-C (Government/Political): CANDIDATE
    Political authority over the audit function creates a conflict of interest
    where the audit may be constrained in what it observes. Plausible candidate
    independence requirement.

  IR-D (Criteria Setter):      OPEN
    The criteria setter defines what a valid election looks like; the audit
    must observe whether those criteria were followed. If the criteria setter
    can direct the audit to observe only what the criteria setter specifies,
    the audit scope is criteria-setter-controlled. Independence of audit scope
    from criteria-setter direction is a possible constitutional requirement.
    Insufficient evidence to assess.

  IR-E (Certifier):            CANDIDATE
    The certifier uses audit findings to certify. If the certifier also controls
    the audit function, the certifier determines what evidence is available
    for their own certification decision. Independence of audit from certifier
    is a plausible constitutional requirement.
    This is an instance of IR-G (self-independence) at the certification layer.

  IR-F (Audit Function):       NOT APPLICABLE
    The audit function cannot be independent of itself. Internal audit
    independence (sub-functions within the audit) is a design question, not
    a constitutional independence relationship.

  IR-G (Self-independence):    CANDIDATE
    The audit function defines what is auditable. If the audit function can
    decide its own scope, it is self-referential. Gap A-3 (expected evidence
    set undefined) is a constitutional symptom of this: the auditor's scope
    is not externally specified. Whether the scope definition function must
    be constitutionally separate from the observation function is an open question.
```

### 3.4 Governance Transition Authorization

```
What constitutes this function:
  The constitutional authorization that a specific governance state transition
  (Opening voting, Closing voting, Freezing rules, Declaring result) is
  permissible at this time.
  OBS-36D-01-2: Authorization ≠ Execution. This section evaluates authorization only.
  D35, D37 partially applicable.

Independence relationships:

  IR-A (Election Operator):    REQUIRED
    The operator who executes the transition cannot constitutionally also be
    the party that authorizes the transition as permissible. If the operator
    self-authorizes, they control election timing, rule-freezing, and result
    declaration — all constitutional acts, not operational ones.

  IR-B (Funding Body):         CANDIDATE
    A funding body that can condition continued authorization on operational
    choices holds de facto transition authority. Plausible candidate requirement.

  IR-C (Government/Political): CANDIDATE (strong — not yet confirmed)
    Governance transitions determine the constitutional moment of each election
    phase. An entity whose legitimacy depends on the election outcome has a
    structural conflict of interest in authorizing when phases begin and end.
    Example: authorizing early closure of voting to favor a particular outcome.
    However: constitutional systems exist where government appoints an independent
    body that then operates autonomously — political origin does not automatically
    mean political control. The independence requirement is on CONTROL, not on
    appointment. Whether this independence relationship is constitutionally required
    or constitutionally recommended is unresolved.

  IR-D (Criteria Setter):      CANDIDATE
    The criteria setter specifies when transitions are constitutionally permissible.
    If the criteria setter also authorizes specific transitions, they may adjust
    criteria to justify transitions they prefer. Independence of authorization
    from criteria setting is a plausible candidate requirement.

  IR-E (Certifier):            OPEN
    The certifier reviews the overall process. Whether certifier independence
    from the authorization body is constitutionally required is unclear.
    The certifier evaluates whether transitions were properly authorized;
    controlling the authorizer would make this evaluation self-referential.
    Possible candidate independence requirement.

  IR-F (Audit Function):       NOT REQUIRED
    The audit function observes what transitions occurred; it does not
    authorize them. No constitutional conflict between audit observation
    and authorization authority identified.

  IR-G (Self-independence):    REQUIRED
    The authorization body cannot constitute itself as the evaluator of
    whether its own authorizations were constitutionally sound. If challenged,
    the authorizer must not be the adjudicator of the challenge.
```

### 3.5 Certification of Election Validity

```
What constitutes this function:
  The terminal act declaring that the election was constitutionally valid
  and the result is binding.
  TF-36C-05-01: Certifier legitimacy ≠ certification validity.
  D43-instance-CERTIFICATION — no constitutionally grounded authority holder discovered.
  TC4-NCQ-01/02/03: open questions on independence threshold, succession, circular certification.

Independence relationships:

  IR-A (Election Operator):    REQUIRED
    This is the TC-4 core finding. Certification by the operator of their own
    election is constitutionally self-referential. The certifier cannot be
    the same party as the operator.

  IR-B (Funding Body):         CANDIDATE (strong — not yet confirmed)
    A certifier funded by the party whose election they certify has a
    structural conflict of interest. Financial independence of the certifier
    is a plausible candidate constitutional requirement. The strength of this
    concern arises from the terminal nature of certification (TF-36C-05-11):
    a compromised certification amplifies all prior failures into a public claim.
    Whether financial independence is constitutionally required or recommended
    is a 36D-NCQ-09 question.

  IR-C (Government/Political): CANDIDATE (strong — not yet confirmed)
    If the political body whose legitimacy depends on the election result can
    CONTROL the certifier, certification is constitutionally compromised.
    However: constitutional systems exist where government-appointed certification
    bodies retain independence through structural design. Political origin ≠ political
    control. The candidate requirement is on control independence, not on
    appointment origin. Not yet confirmed as required.

  IR-D (Criteria Setter):      CANDIDATE
    If the certifier and criteria setter are the same party, certification
    is circular in a constitutionally suspect way (TC4-NCQ-03). The certifier
    certifies compliance with criteria they themselves established.
    Whether this requires constitutional separation or whether a pre-committed
    criteria set prevents the conflict is unresolved.

  IR-E (Certifier):            IR-G APPLIES (see below)
    The certifier cannot certify their own certification process.

  IR-F (Audit Function):       CANDIDATE
    The certifier relies on audit findings. If the certifier controls the
    audit function, the certifier determines what evidence is available
    for their own certification decision. Independence of certifier from
    audit oversight authority is a plausible candidate requirement.

  IR-G (Self-independence):    REQUIRED
    The certifier cannot certify their own legitimacy as certifier.
    Circular certification — where the certifier's authority derives from
    a certification they themselves issued — is constitutionally suspect
    (TC4-NCQ-03; TF-36C-05-06).
    Who certifies the certifier's constitutional standing is an instance
    of TC3-NCQ-04 ("who governs the governors?") applied to certification.
```

### 3.6 Result Verification

```
What constitutes this function:
  The independent verification that the declared result correctly reflects
  the valid votes cast under valid rules.
  This is distinct from Result Declaration (the operator declares the mathematical
  outcome of the tally) and from Certification (the certifier declares the
  process was constitutionally valid).
  D39 partially applicable (tallying boundary).

Independence relationships:

  IR-A (Election Operator):    REQUIRED
    The party that produced the tally cannot be the sole verifier of the tally.
    Independent result verification requires a party that did not produce the result.

  IR-B (Funding Body):         OPEN
    Whether result verification requires funding independence is unclear.
    A verifier funded by the same body as the operator has an indirect
    conflict of interest. Insufficient evidence to assess.

  IR-C (Government/Political): CANDIDATE
    The political body affected by the result has a conflict of interest
    in controlling result verification. Independence from political authority
    is a plausible candidate requirement.

  IR-D (Criteria Setter):      CANDIDATE
    If the criteria setter also verifies the result, they can adjust criteria
    to make an otherwise non-compliant result appear compliant. Independence
    of result verification from criteria setting is plausible.

  IR-E (Certifier):            CANDIDATE
    If the result verifier and certifier are the same party, the certifier
    verifies their own input. Independence of result verification from
    certification is plausible, though the specific constitutional risk
    is lower than for Certification itself (which is terminal).

  IR-F (Audit Function):       NOT REQUIRED
    Result verification uses audit evidence; it does not produce it.
    No constitutional conflict between result verification and audit
    observation identified.

  IR-G (Self-independence):    REQUIRED
    The party that declares the result cannot constitutionally verify their
    own declaration. Result verification must be by a party other than
    the result declarer.
    This is the most basic constitutional independence requirement for
    this function and is directly supported by TC-1 and TC-2 findings.
```

---

## 4. Consolidated Independence Matrix

```
Function            IR-A    IR-B    IR-C    IR-D    IR-E    IR-F    IR-G    IR-H
                    Oper.   Fund.   Gov't   Crit.   Cert.   Audit   Self    Audited

Enrollment          REQ     CAND    CAND    N/R     OPEN    N/R     REQ     N/A
Criteria Def.       REQ     CAND    CAND    —       CAND    N/R     REQ     N/A
Audit Oversight     REQ     CAND    CAND    OPEN    CAND    N/A     CAND    REQ
Gov. Auth.          REQ     CAND    CAND+   CAND    OPEN    N/R     REQ     OPEN
Certification       REQ     CAND+   CAND+   CAND    —       CAND    REQ     CAND
Result Verif.       REQ     OPEN    CAND    CAND    CAND    N/R     REQ     CAND

Legend:
  REQ   = REQUIRED (candidate — evidence from 36C/36D supports)
  CAND+ = CANDIDATE (strong concern — high evidence weight but not yet confirmed required)
  CAND  = CANDIDATE (plausible; insufficient evidence to confirm)
  OPEN  = Insufficient evidence to assess; ARB determination required
  N/R   = NOT REQUIRED (no constitutional conflict identified)
  N/A   = Not applicable / not relevant for this function
  —     = Self-referential (IR-G applies; see row)

Note on IR-C (Government/Political): Constitutional systems exist where
government appoints an independent body that operates autonomously.
Political appointment ≠ political control. All IR-C findings remain
CANDIDATE — the requirement is on control independence, not appointment origin.

OBS-36D-03-1 (IR-H — carry to all remaining 36D sub-rounds):
  IR-A (operator independence) does not cover all audited actors.
  An audit function may be independent of the election operator
  and simultaneously dependent on another actor whose conduct it audits —
  an enrollment authority, a certification body, a governance authority.
  IR-H must be evaluated for any function that has an observation or
  verification role over other constitutional actors.
  Audit Oversight: IR-H = REQUIRED (the audit must be independent of every audited actor).
  Certification and Result Verification: IR-H = CANDIDATE (the verifier observes
  others' outputs and must be independent of those whose outputs are verified).
```

---

## 5. Cross-Cutting Findings

### 5.1 IR-A (Election Operator) is REQUIRED across all six functions

```
36D-CF-03-01 (CANDIDATE):
  Independence from the election operator is constitutionally required
  for every constitutional function evaluated.
  This corroborates 36D-CF-02-01 (minimum constitutional requirement).

  The operator who administers the election cannot constitutionally hold
  unchecked authority over: who votes, what the rules are, what is audited,
  when phases transition, whether the election is certified, or whether
  the result is verified.

  This is among the strongest candidate findings of Round 36D.
  The election operator is the primary independence reference point.
  (Candidate — confirmed pattern; not yet ARB-confirmed constitutional law)
```

### 5.2 IR-G (Self-independence) is REQUIRED across five of six functions

```
36D-CF-03-02 (CANDIDATE):
  Self-independence is constitutionally required across five of the six
  functions evaluated. The only exception is Audit Oversight, where
  it remains a candidate rather than a requirement.

  The pattern: constitutional functions that evaluate, govern, authorize,
  certify, or verify other functions cannot be self-referential.
  The evaluator cannot evaluate their own evaluation.
  The authorizer cannot authorize their own authority.
  The certifier cannot certify their own certification.
  The verifier cannot verify their own result.

  This is a constitutional property of authority itself, not a feature
  of any particular constitutional design.
```

### 5.3 IR-C (Government/Political) shows a strong pattern

```
36D-CF-03-03 (CANDIDATE — not yet promoted):
  Independence from political authority is a candidate requirement for all six functions.
  The structural conflict of interest — an entity whose legitimacy depends on the
  election outcome controlling the election process — is constitutionally significant.

  Governing constraint (OBS-36D-03 applied):
  Constitutional systems exist where government appoints independent bodies that
  operate autonomously. Political appointment origin does not constitute political control.
  The independence requirement, where it applies, is on CONTROL independence, not
  on appointment independence.

  IR-C remains CANDIDATE across all six functions. The evidence weight is higher for
  governance transition authorization and certification (non-recoverable failure), but
  "strong concern" (CAND+) is not the same as "constitutionally required" (REQ).
  This distinction must be preserved in 36E.
```

### 5.4 IR-B (Funding) is systematically underspecified

```
36D-CF-03-04 (CANDIDATE):
  Funding independence appears as a candidate requirement for most functions
  but lacks sufficient evidence to confirm for most.

  The constitutional basis is clear: financial dependency creates a de facto
  authority relationship. A body that depends on continued funding from a party
  with election interests is structurally not independent of that party.

  However, independence of funding from election interest is a complex
  institutional question that Round 36D cannot resolve from first principles.
  This requires ARB constitutional determination.

  36D-NCQ-09 (new): What is the constitutional threshold for financial independence?
    At what degree of financial dependency does a constitutional independence
    requirement arise? Is any financial relationship disqualifying, or only
    direct funding relationships?
```

### 5.5 The D43 Pattern Has a Structural Signature

```
36D-CF-03-05 (CANDIDATE):
  The five D43 instances (Enrollment, Criteria, Audit, Governance Authorization,
  Certification) share a structural signature in the independence matrix:

  All five show IR-A (operator) = REQUIRED and IR-G (self-independence) = REQUIRED.
  All five show IR-C (government) = CANDIDATE or higher.
  All five lack a constitutionally grounded authority holder (as of 36D-02).

  This structural signature is consistent with the D43 pattern hypothesis:
  wherever constitutional authority is unassigned, concentration by the
  operational actor is the default, producing the same independence failure
  pattern across all five instances.

  Corroborates 36D-CF-02-02.
```

---

## 6. TC3-NCQ-04 and the Self-Independence Recursion

Criteria Definition (Section 3.2) raised the possibility that IR-G (self-independence) for the criteria-setting function may be constitutionally unsatisfiable — leading to the Case 4 candidate from 36D-CF-02-03.

The independence matrix allows a more precise characterization:

```
The self-independence question for Criteria Definition is:

  The criteria-setting authority must be constitutionally independent of itself.
  This means: the criteria-setting authority's standing must derive from something
  other than criteria it has itself set.

  This is TC3-NCQ-04 applied to independence analysis.

  Candidate terminal points for the recursion:
    (a) A founding constitutional document that pre-dates all election criteria
        and is not itself subject to criteria it establishes.
    (b) A sovereign act (constitutional assembly, democratic mandate) that
        grounds the criteria-setting authority at a level prior to election law.
    (c) An external authority (international standards, constitutional court)
        that grounds criteria independently of the domestic election authority.

  None of these candidate terminal points is yet confirmed.
  All three represent constitutional design choices, not constitutional discoveries.
  These are inputs for 36E and Round 37 ADR authoring, not 36D findings.

  36D-CF-03-06 (DESIGN POSSIBILITY — NOT DISCOVERY EVIDENCE):
    TC3-NCQ-04 may terminate at a constitutional founding document or
    sovereign democratic mandate that grounds criteria-setting authority
    prior to election law. If this terminal point exists, Case 4 does not apply.

    THIS IS NOT A DISCOVERED CONSTITUTIONAL FACT.
    This is a candidate design possibility — a way that constitutional systems
    MIGHT resolve the recursion. It has not been observed in the discovered
    domain model, and it has not been confirmed by ARB.

    Do NOT carry this into 36E as discovered truth.
    Carry to 36E as an open question: does the discovered domain model have
    a founding-document analog that grounds criteria-setting authority?
    36E must evaluate this question against the actual domain model, not
    assume the answer is yes.
```

---

## 7. OBS-36D-02-1 Verification

OBS-36D-02-1 (from Round 36D-02) warned: do not confuse authority distribution with bounded context distribution.

Verification against this document:

```
This document discusses:
  Independence relationships between authority holders
  Constitutional requirements for separation of authority
  Structural conflicts of interest

This document does NOT create:
  New bounded contexts
  Audit Context, Certification Context, Criteria Context
  Any aggregate or service

The independence relationships documented here are INPUTS to 36E.
36E will assess what these constitutional relationships imply for
the existing bounded context candidates and aggregate design.
One independence requirement does not equal one bounded context.

OBS-36D-02-1 compliance: VERIFIED for this document.
```

---

## 8. New NCQs Generated

```
36D-NCQ-09:
  What is the constitutional threshold for financial independence?
  At what degree of financial dependency does a constitutional independence
  requirement arise? (IR-B systematically underspecified)

36D-NCQ-10:
  TC3-NCQ-04 may terminate at a founding constitutional document.
  But who is constitutionally authorized to interpret that founding document
  in the context of an election dispute?
  The recursion may simply move one level up rather than terminate.

36D-NCQ-11:
  IR-G (self-independence) is constitutionally required across five functions.
  But who enforces self-independence violations?
  If an actor violates self-independence, the enforcement mechanism must itself
  be independent of the violating actor — which introduces the same recursion.
```

---

## ARB Decision

```
Round 36D-03 — Constitutional Independence Analysis

[ARB REVIEW COMPLETE]

Research Discipline:     HIGH
Analytical Quality:      HIGH
Governance Discipline:   HIGH
Architecture Neutrality: HIGH
Confidence:              HIGH

Central outputs:
  Independence matrix: 6 functions × 8 independence relationships (IR-A through IR-H)
  36D-CF-03-01: IR-A (operator independence) REQUIRED across all six functions (softened)
  36D-CF-03-02: IR-G (self-independence) REQUIRED across five of six functions (CANDIDATE)
  36D-CF-03-03: IR-C (government) = CANDIDATE across all functions (not promoted to REQ)
  36D-CF-03-04: IR-B (funding) systematically underspecified — 36D-NCQ-09
  36D-CF-03-05: D43 pattern has structural signature (IR-A REQ + IR-G REQ + IR-C CAND)
  36D-CF-03-06: Downgraded to DESIGN POSSIBILITY — NOT DISCOVERY EVIDENCE
  OBS-36D-02-1 compliance verified
  OBS-36D-03-1 (IR-H): Independent of audited subject — introduced and applied
  Three new NCQs (36D-NCQ-09 through 36D-NCQ-11)

ARB Observations Applied:

  Observation 1 (APPLIED):
    "Clearest constitutional finding" → "Among the strongest candidate findings
    of Round 36D." Epistemic status preserved — candidate, not confirmed.

  Observation 2 (APPLIED):
    IR-C (Government/Political) kept as CANDIDATE across all functions.
    REQ* labels changed to CAND+ (strong concern, not confirmed required).
    Added governing note: political appointment ≠ political control.
    Constitutional systems exist where government-appointed bodies operate
    independently. The independence requirement is on control, not origin.

  Observation 3 (APPLIED):
    36D-CF-03-06 reclassified from CANDIDATE — WEAK to DESIGN POSSIBILITY.
    Explicit warning added: this is NOT discovery evidence.
    36E must evaluate whether the discovered domain model has a founding-document
    analog — it must not assume the answer is yes.

  Observation 4 (APPLIED — OBS-36D-03-1):
    IR-H (Independent of Audited Subject) introduced as a new independence relationship.
    IR-A covers operator independence; IR-H covers all audited actors.
    An audit function independent of the operator may still be dependent on other actors
    it is auditing (enrollment authorities, certification bodies, governance authorities).
    Matrix extended to 8 columns. Audit Oversight: IR-H = REQUIRED.
    Certification and Result Verification: IR-H = CANDIDATE.
    IR-H must be carried into all remaining 36D sub-rounds.

Governing Rule Compliance:
  No architecture decisions made.
  No ElectionGuard, threshold cryptography, blockchain, or cryptographic mechanisms.
  No bounded contexts or aggregates created.
  No ADRs written.
  No candidates promoted to confirmed.
  Constitutional analysis only.
  OBS-36D-02-1 (Authority ≠ Context) carried and verified.

Round 36D-03: APPROVED

Governing Instructions for Round 36D-04 — Authority Legitimacy Analysis:

  The primary unresolved question is no longer "who holds authority?" or
  "independent from whom?" It is now:

    "Where does authority obtain legitimacy?"

  For every D43 instance — Enrollment, Criteria Definition, Audit Oversight,
  Governance Transition Authorization, Certification — evaluate:

    Legitimacy Source:
      What grounds this authority's constitutional standing?
      (Founding document, democratic mandate, appointment by independent body,
       statutory basis, etc.)

    Authority Holder (candidate or discovered):
      Who currently holds or should hold this authority?

    Challenge Mechanism:
      What is the constitutional process for disputing this authority's decision?
      Who has standing to challenge?

    Revocation Mechanism:
      Under what conditions can this authority be revoked?
      Who holds revocation power? (TC3-NCQ-04 recursion applies here)

    Succession Mechanism:
      What happens when this authority holder is unavailable, incapacitated,
      or constitutionally disqualified?

  Mandatory constraints:
    No ElectionGuard, threshold cryptography, blockchain, or specific mechanisms.
    No bounded contexts or aggregates.
    No ADRs.
    OBS-36D-02-1: Authority ≠ Context — carry throughout.
    OBS-36D-03-1: IR-H (audited subject independence) — carry throughout.
    Constitutional analysis only.

Round 36D-04: AUTHORIZED
```
