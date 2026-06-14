# Round 36D-04 — Authority Legitimacy Analysis

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 36D-04
**Date:** 2026-06-14
**Status:** SUBMITTED FOR ARB REVIEW

---

## 1. Purpose and Governing Instruction

Round 36D-02 identified seven constitutional functions and their candidate authority patterns.
Round 36D-03 mapped independence relationships for each function.

The remaining question is no longer:

```
Who holds authority?
Independent from whom?
```

The remaining question is:

```
Where does authority obtain legitimacy?
```

A party may hold authority — by institutional gravity, by default, by appointment, by statute — without that authority having constitutional legitimacy. Round 36C established that governance threats are independent of evidence threats (TF-36C-04-14). Round 36D-01 established that authority requires both legitimacy and decision power. This document investigates the legitimacy component for each D43 instance.

The five D43 instances under evaluation:

```
D43-instance-ENROLLMENT
D43-instance-CRITERIA
D43-instance-AUDIT
D43-instance-GOVERNANCE-AUTH
D43-instance-CERTIFICATION
```

For each, the document evaluates five legitimacy dimensions.

This document produces no architecture decisions.
It does not evaluate ElectionGuard, threshold schemes, or any specific mechanism.
It does not create bounded contexts or aggregates.
It does not write ADRs.
OBS-36D-02-1: authority relationships ≠ bounded context partitions — carried throughout.
OBS-36D-03-1: IR-H (independent of audited subject) — carried throughout.

---

## 2. The Five Legitimacy Dimensions

Each D43 instance is evaluated against five dimensions:

```
L-1: Legitimacy Source
     What grounds this authority's constitutional standing?
     Why does this actor have the right to make binding decisions?

L-2: Authority Holder (current state)
     Who currently holds or exercises this authority?
     Is the holder constitutionally grounded, or does authority
     reside by default or institutional gravity?

L-3: Challenge Mechanism
     What is the constitutional process for disputing a decision
     made under this authority?
     Who has standing to challenge?

L-4: Revocation Mechanism
     Under what conditions can this authority be revoked,
     suspended, or transferred?
     Who holds revocation power?
     (TC3-NCQ-04 applies: the revocation authority is itself an
      authority requiring legitimacy)

L-5: Succession Mechanism
     What happens when the authority holder is unavailable,
     incapacitated, constitutionally disqualified, or has ceased to exist?
     Is there a constitutionally grounded successor?
```

**Legitimacy states for each dimension:**

```
DISCOVERED:    Evidence from 36A-36D sub-rounds indicates a specific answer
CANDIDATE:     Plausible answer; insufficient evidence to confirm
UNDEFINED:     No constitutionally grounded answer has been discovered
SELF-REF:      The answer refers back to the authority being evaluated (circular)
OPEN:          Multiple plausible answers; ARB determination required
```

---

## 3. D43-instance-ENROLLMENT

```
What this authority governs:
  Who holds the right to vote, their electoral scope (national vs regional),
  and whether eligibility can be modified, suspended, or revoked.

L-1 (Legitimacy Source):
  State: UNDEFINED
  Finding: No constitutionally grounded legitimacy source has been discovered.
  The enrollment authority is exercised by the operational actor (NRNA administration
  or equivalent) by institutional default — whoever manages the member registry
  manages enrollment.
  A legitimacy source would need to ground: Why does this administrative actor have
  the constitutional right to determine electoral eligibility?
  Constitutional legitimacy for enrollment typically derives from: constitutional
  membership definition, statutory electoral authority, or democratic mandate.
  None of these has been discovered as an explicit, constitutionally grounded source
  in the current model.

L-2 (Authority Holder):
  State: CANDIDATE (operational actor by institutional gravity)
  The operational actor (NRNA administration) currently holds enrollment authority
  by default. This is concentration by institutional gravity, not by constitutional
  design (TC-5 risk, per Round 36C-06 and 36D-02).

L-3 (Challenge Mechanism):
  State: UNDEFINED
  No challenge mechanism for enrollment decisions has been discovered.
  An eligible voter incorrectly excluded has no discovered constitutional process
  to challenge that exclusion within the discovered domain model.
  A challenge mechanism would require: standing (who may challenge), process
  (how challenge is heard), and decision authority (who resolves the challenge).

L-4 (Revocation Mechanism):
  State: UNDEFINED
  The conditions under which enrollment authority itself may be revoked
  (e.g., if the administrative actor loses constitutional standing) are undefined.
  TC3-NCQ-04 applies: who holds the authority to revoke enrollment authority?

L-5 (Succession Mechanism):
  State: UNDEFINED
  What happens to enrollment if the operational actor is unavailable during
  an active election is undefined.

Constitutional Summary for Enrollment:
  All five legitimacy dimensions are UNDEFINED.
  Enrollment authority is held by institutional gravity, not constitutional design.
  This is a complete legitimacy gap at the enrollment boundary.
  D43-instance-ENROLLMENT is not merely an ownership gap — it is a full
  constitutional legitimacy gap.
```

### 3.1 Enrollment Legitimacy Finding

```
36D-CF-04-01 (CANDIDATE):
  Enrollment authority in the discovered model has no identified legitimacy source,
  no challenge mechanism, no revocation mechanism, and no succession mechanism.
  It operates entirely by institutional gravity.

  This is the most complete D43 instance discovered so far.
  All five legitimacy dimensions are undefined simultaneously.

  Constitutional risk: maximum. An actor who holds enrollment authority
  by default, with no challenge mechanism and no revocation mechanism,
  controls the electorate without constitutional constraint.
```

---

## 4. D43-instance-CRITERIA

```
What this authority governs:
  The rules by which election validity is judged — quorum requirements,
  valid vote definition, result criteria, and when results become binding.

L-1 (Legitimacy Source):
  State: OPEN (with TC3-NCQ-04 complication)
  A criteria-setting authority's legitimacy source must itself be grounded
  in something prior to the criteria it sets. Three candidate legitimacy sources:
    (a) Constitutional founding document (TC3-NCQ-04 terminal point — DESIGN POSSIBILITY
        per 36D-CF-03-06, not yet confirmed as discovered)
    (b) Democratic mandate of the NRNA membership (the members themselves ratify the criteria)
    (c) External constitutional authority (statute, court, or international standard)
  Of these three, (b) is the most plausible within a membership organization context.
  If criteria are ratified by the membership they govern, legitimacy derives from
  the governed themselves — a democratic legitimacy source.
  This is a CANDIDATE legitimacy source, not a confirmed discovered fact.

L-2 (Authority Holder):
  State: CANDIDATE (governance body, by presumption)
  GovernanceState is the PRESUMPTIVE OWNER of election criteria configuration
  (OBS-36A-08-2, five-source, two-family evidence from Round 36A).
  However, "owner of criteria configuration" ≠ "constitutional authority to set criteria."
  GovernanceState may be the system that stores and enforces criteria.
  The constitutional body that has the right to set those criteria is a separate question.

L-3 (Challenge Mechanism):
  State: CANDIDATE (membership-level challenge)
  If criteria legitimacy derives from membership ratification (L-1 candidate source b),
  then the challenge mechanism would be: a member, or qualified group of members,
  may challenge criteria that were not properly ratified.
  Whether this challenge mechanism exists in practice is undiscovered.

L-4 (Revocation Mechanism):
  State: SELF-REF (TC3-NCQ-04 applies)
  Who can revoke criteria-setting authority? The same recursion applies.
  The revocation authority must be independent of the criteria-setting authority
  itself — otherwise revoking the criteria setter requires using criteria
  the criteria setter established.
  This is TC3-NCQ-04 applied directly to revocation.

L-5 (Succession Mechanism):
  State: UNDEFINED
  If the criteria-setting body ceases to function during an election,
  what criteria govern the completion of that election?
  No succession mechanism has been discovered.

Constitutional Summary for Criteria:
  L-1: Candidate legitimacy source (membership ratification — not confirmed)
  L-2: Candidate holder (GovernanceState configuration ≠ constitutional authority)
  L-3: Candidate challenge (membership-level — not confirmed)
  L-4: Self-referential (TC3-NCQ-04 recursion — unresolved)
  L-5: Undefined
```

### 4.1 Criteria Legitimacy Finding

```
36D-CF-04-02 (CANDIDATE):
  Criteria authority has a candidate legitimacy source (membership ratification)
  that does not exist for enrollment authority.
  This is the key difference between the two D43 instances.

  However: GovernanceState as configuration holder ≠ constitutional authority holder.
  The distinction must be preserved in 36E.

  The revocation dimension (L-4) is structurally self-referential (TC3-NCQ-04).
  This is the remaining core problem for criteria authority legitimacy.
  If TC3-NCQ-04 cannot be resolved, criteria-setting authority cannot achieve
  complete constitutional legitimacy through any internal mechanism alone.
```

---

## 5. D43-instance-AUDIT

```
What this authority governs:
  The observation, collection, and preservation of election event evidence;
  the determination of what constitutes a complete audit record (Gap A-3).

L-1 (Legitimacy Source):
  State: UNDEFINED (with Gap A-3 complication)
  The audit function's legitimacy depends in part on the audit function's SCOPE.
  Gap A-3: the expected evidence set is constitutionally undefined.
  A legitimacy source for audit authority must ground: why does this actor have
  the right to determine what the expected evidence set is?
  If the audit function's scope is undefined, its legitimacy source is also undefined.
  This is a constitutional circular dependency: scope ↔ legitimacy.

L-2 (Authority Holder):
  State: CANDIDATE (Audit context, presumptive — passive observer)
  The Audit context is a passive observer, not an active authority holder.
  OBS-36A-08-1 established: Audit context is passive observer, NOT publication owner.
  A passive observer does not hold authority over scope.
  If nobody holds authority over audit scope (Gap A-3), then the audit function's
  authority holder is UNDEFINED.

L-3 (Challenge Mechanism):
  State: UNDEFINED
  If the audit record is incomplete or inaccurate, what constitutional process
  exists to challenge it?
  Who has standing to raise an incompleteness challenge?
  No challenge mechanism has been discovered.

L-4 (Revocation Mechanism):
  State: UNDEFINED
  Under what conditions can audit authority be revoked?
  Given that audit authority has no identified holder, revocation is also undefined.

L-5 (Succession Mechanism):
  State: UNDEFINED
  If the audit observation function fails during an election, what ensures
  constitutional continuity of the audit record?

Constitutional Summary for Audit:
  All five dimensions are UNDEFINED or CANDIDATE.
  Gap A-3 is not merely a data definition problem — it is a legitimacy problem.
  Until the authority to define the expected evidence set is constitutionally
  grounded, the audit function lacks a legitimate authority holder.

IR-H (OBS-36D-03-1 applied):
  The audit function must be independent of all actors it audits:
  operator, enrollment authority, criteria setter, certification body,
  and governance transition authorizer.
  If any of these actors controls the audit scope (Gap A-3 is undefined
  and defaults to the operator), IR-H is violated for that actor.
```

### 5.1 Audit Legitimacy Finding

```
36D-CF-04-03 (CANDIDATE):
  Gap A-3 (expected evidence set undefined) is not only a completeness gap.
  It is a legitimacy gap for the audit authority.
  Whoever defines the expected evidence set effectively holds audit scope authority.
  If this is undefined, the audit authority has no grounded legitimacy.
  If it defaults to the operator, the operator holds audit scope authority,
  violating IR-A (operator independence) and IR-H (audited subject independence).

  Gap A-3 resolution is a constitutional prerequisite for audit authority legitimacy,
  not merely a data design problem.
```

---

## 6. D43-instance-GOVERNANCE-AUTH

```
What this authority governs:
  The constitutional authorization that specific governance state transitions
  are permissible (Opening voting, Closing voting, Freezing rules, Declaring results).
  OBS-36D-01-2: authorization ≠ execution. This section covers authorization.

L-1 (Legitimacy Source):
  State: CANDIDATE (constitutional rules + membership mandate)
  Authorization of governance transitions may derive legitimacy from:
    (a) Pre-specified rules in the election constitution (when transitions are permitted)
    (b) The membership mandate (the members have authorized the election to occur
        under specified conditions)
  If (a) and (b) are both satisfied — rules exist, members ratified them —
  the authorization body's legitimacy derives from implementing pre-ratified rules.
  This is a candidate legitimacy source. Its strength depends on criteria legitimacy (L-1 above).

L-2 (Authority Holder):
  State: UNDEFINED
  No constitutionally grounded authorization body has been discovered.
  GovernanceState records transitions; it does not authorize them constitutionally.
  The operational actor authorizes transitions by executing them — but as established
  in OBS-36D-01-2, authorization ≠ execution, and the operator cannot constitutionally
  self-authorize.

L-3 (Challenge Mechanism):
  State: CANDIDATE
  If authorization legitimacy derives from pre-specified rules, a challenge mechanism
  would be: any affected party may challenge that a transition occurred outside the
  constitutionally permitted conditions.
  The challenge must be heard by a body independent of the authorization decision
  (IR-G: self-independence applies).

L-4 (Revocation Mechanism):
  State: CANDIDATE
  An authorization can be challenged; if found to have been unauthorized, the
  transition may be constitutionally contestable. What this means operationally
  (can a voting period be un-opened? can a result declaration be reversed?) is
  an open question that depends on non-recoverability analysis from 36D-02.

L-5 (Succession Mechanism):
  State: UNDEFINED
  If the authorization body is unavailable during an election, no constitutional
  succession mechanism has been discovered.

Constitutional Summary for Governance Authorization:
  L-1: Candidate (rules + mandate — depends on criteria legitimacy)
  L-2: Undefined (operator authorizes by execution, which is constitutionally insufficient)
  L-3: Candidate (challenge via rules violation claim)
  L-4: Candidate (contestability of unauthorized transitions)
  L-5: Undefined
```

---

## 7. D43-instance-CERTIFICATION

```
What this authority governs:
  The terminal constitutional declaration that the election was valid
  and the result is constitutionally binding.
  TF-36C-05-01: Certifier legitimacy ≠ certification validity.
  TF-36C-05-11: Certification amplifies all prior failures into a public claim.

L-1 (Legitimacy Source):
  State: CANDIDATE (external grounding required)
  Certification legitimacy cannot derive from the certified party (IR-A: operator
  independence required) or from criteria the certifier itself established
  (IR-D: criteria independence is a candidate requirement).
  A candidate legitimacy source: constitutional appointment by an independent
  body with democratic standing — a body whose legitimacy derives from the
  membership or from an external authority prior to the election.
  This is the constitutional design principle underlying independent certification
  bodies in governmental election law.
  Whether the NRNA domain model has a constitutionally equivalent mechanism
  is undiscovered.

L-2 (Authority Holder):
  State: UNDEFINED
  No constitutionally grounded certification authority holder has been discovered
  in the NRNA domain model. D43-instance-CERTIFICATION.
  Certification is currently performed by the operator or the election management
  body by default — which is constitutionally self-referential.

L-3 (Challenge Mechanism):
  State: CANDIDATE
  A certified election result should be challengeable if the certification was
  constitutionally invalid (certifier lacked legitimacy, criteria were manipulated,
  evidence was incomplete). The challenge mechanism must be:
    - Available to affected parties (voters, candidates, observers)
    - Heard by a body independent of the certification body (IR-G applies)
    - Timely (challenge windows are constitutional properties, not operational choices)

L-4 (Revocation Mechanism):
  State: OPEN
  Can a certification be revoked? Under what conditions?
  Given the terminal nature of certification (TF-36C-05-11), revocation is
  constitutionally significant and must have a grounded process.
  The revocation authority must be independent of the certification body itself.
  Who holds revocation authority for certification is a constitutional design question
  that carries the TC3-NCQ-04 recursion.

L-5 (Succession Mechanism):
  State: UNDEFINED
  If the certification body is constitutionally disqualified during or after
  an election (e.g., conflict of interest discovered), what constitutional
  succession mechanism exists?

Constitutional Summary for Certification:
  L-1: Candidate (external appointment by independent body — not yet discovered)
  L-2: Undefined (operator certifies by default — constitutionally self-referential)
  L-3: Candidate (challenge via legitimacy or criteria violation)
  L-4: Open (revocation mechanism constitutionally required; TC3-NCQ-04 applies)
  L-5: Undefined
```

### 7.1 Certification Legitimacy Finding

```
36D-CF-04-04 (CANDIDATE):
  Certification is the constitutional function with the highest terminal
  risk (TF-36C-05-11) and simultaneously one of the most complete
  legitimacy gaps in the discovered model.

  The certifier (currently undefined; defaults to operator) is:
    - Not independent of the operator (IR-A violation)
    - Not independently constitutionally grounded (L-1 undefined)
    - Not subject to a discovered challenge mechanism (L-3 candidate only)
    - Not subject to a discovered revocation mechanism (L-4 open)

  Certification is where the constitutional legitimacy gap has the
  highest constitutional consequence.
```

---

## 8. Cross-Cutting Legitimacy Findings

### 8.1 Succession Is Universally Undefined

```
36D-CF-04-05 (CANDIDATE):
  L-5 (Succession Mechanism) is UNDEFINED for all five D43 instances.
  No constitutional succession mechanism has been discovered for any
  of the five constitutional authority types.

  Constitutional significance:
  An election in which any authority holder becomes unavailable mid-election
  has no constitutionally grounded path to completion.
  The election would either stop (non-recoverable if voting has opened) or
  continue under informal succession (constitutional legitimacy compromised).

  Succession mechanisms are not operational edge cases.
  They are constitutional requirements for any authority that governs a
  non-recoverable function.
```

### 8.2 Challenge Mechanisms Appear as Candidates, Not Yet Discovered

```
36D-CF-04-06 (CANDIDATE):
  Challenge mechanisms (L-3) exist as candidates for four of five D43 instances
  (Enrollment being the exception with UNDEFINED).
  None has been discovered as an actually implemented constitutional mechanism.

  Constitutional significance:
  An election system in which authority decisions cannot be challenged is
  constitutionally non-accountable regardless of how well it behaves.
  Accountability requires answerability, which requires a challenge mechanism.
  (Accountability ≠ Responsibility: Round 36D-01, Section 2.4)
```

### 8.3 Membership Ratification as Recurring Candidate Legitimacy Source

```
36D-CF-04-07 (CANDIDATE):
  Membership ratification appears as a candidate legitimacy source for
  Criteria (L-1), Governance Authorization (L-1), and by extension Certification
  (if the certifier is appointed by member mandate).

  This is the constitutional mechanism that would ground multiple authority types
  in democratic legitimacy — the governed ratify the authority that governs them.

  If corroborated: this would represent the constitutional terminal point for
  authority legitimacy in the NRNA context — equivalent to the "founding document"
  possibility raised in 36D-CF-03-06 but derived from organizational democratic
  structure rather than constitutional law.

  This is a candidate hypothesis, not a confirmed finding.
  36E must evaluate whether the NRNA organizational model supports this.
```

### 8.4 TC5-DI-03 Corroboration Status

```
TC5-DI-03 (CANDIDATE PROGRAM-LEVEL) stated:
  Ownership Assignment → Authority Distribution → Structural Independence
  → Constitutional Trustworthiness

Round 36D-04 provides partial corroboration:

  Ownership Assignment (L-2 analysis): five D43 instances have UNDEFINED holders.
  Authority Distribution (L-2 + matrix from 36D-02): concentration by default in operator.
  Structural Independence (L-3/L-4 analysis): challenge and revocation undefined.
  → Constitutional Trustworthiness: not achievable under current discovered state.

  The chain is corroborated in direction by Round 36D-04.
  Whether the three prerequisites are EACH INDEPENDENTLY NECESSARY — or whether some
  combination suffices — remains a 36E question.
  TC5-DI-03 status: CORROBORATION PARTIAL — carry to 36E.
```

---

## 9. Relationship to TC5-NCQ-04

TC5-NCQ-04 (most consequential open question):

```
Is structural distribution constitutionally required,
or can behavioral integrity substitute?
```

Round 36D-04 provides evidence bearing on this question without resolving it:

```
Legitimacy Analysis Evidence:

  The five D43 instances show that legitimacy gaps are STRUCTURAL — they arise from
  the absence of constitutionally grounded authority holders, challenge mechanisms,
  and revocation mechanisms. These are not behavioral failures. They are structural
  absences.

  Implication for TC5-NCQ-04:
    Behavioral integrity (an actor behaving correctly) cannot substitute for
    structural legitimacy (an authority having a constitutional basis for its decisions).

    An actor who holds enrollment authority by institutional gravity but lacks a
    legitimacy source cannot achieve constitutional legitimacy through correct behavior.
    The legitimacy gap is structural, not behavioral.

    This is evidence TOWARD structural distribution being required for legitimacy grounding.
    It is not yet confirmation.

36D-CF-04-08 (CANDIDATE — TC5-NCQ-04 input):
  Structural legitimacy gaps (undefined L-1 through L-5) cannot be resolved
  by behavioral integrity alone.
  Behavioral integrity presupposes a constitutionally legitimate authority holder.
  If the authority holder lacks constitutional legitimacy, their correct behavior
  does not confer legitimacy on their decisions.
  This is a structural argument for why TC5-NCQ-04 may resolve toward structural
  distribution being required — at minimum for authority legitimacy grounding.
```

---

## 10. New NCQs Generated

```
36D-NCQ-12:
  Can membership ratification (CF-04-07) serve as the constitutional terminal point
  for authority legitimacy in the NRNA organizational context?
  If yes: which authorities require membership ratification, and what form
  must that ratification take to be constitutionally sufficient?

36D-NCQ-13:
  Succession is universally undefined (CF-04-05).
  What is the minimum constitutional succession requirement for non-recoverable
  authority functions? Is the absence of a succession mechanism itself a
  constitutional violation, or only a governance risk?

36D-NCQ-14:
  L-4 (Revocation Mechanism) for certification requires a revocation authority
  that is independent of the certification body — which itself requires
  constitutional legitimacy. Does this create a higher-order TC3-NCQ-04 instance?
  If certification revocation authority requires its own legitimacy grounding,
  does the legitimacy chain extend indefinitely?
```

---

## ARB Decision

```
Round 36D-04 — Authority Legitimacy Analysis

[SUBMITTED FOR ARB REVIEW]

Research Discipline:     [PENDING REVIEW]
Analytical Quality:      [PENDING REVIEW]
Governance Discipline:   [PENDING REVIEW]
Architecture Neutrality: [PENDING REVIEW]
Confidence:              [PENDING REVIEW]

Central outputs:
  Five D43 instances evaluated against 5 legitimacy dimensions (L-1 through L-5)
  36D-CF-04-01: Enrollment = complete legitimacy gap; all five dimensions undefined
  36D-CF-04-02: Criteria = membership ratification as candidate L-1 source
  36D-CF-04-03: Gap A-3 is a legitimacy gap for audit authority, not only a data gap
  36D-CF-04-04: Certification = highest terminal risk + most complete legitimacy gap
  36D-CF-04-05: Succession (L-5) universally undefined across all five instances
  36D-CF-04-06: Challenge mechanisms (L-3) candidates only; none discovered as implemented
  36D-CF-04-07: Membership ratification as recurring candidate legitimacy terminal point
  36D-CF-04-08: Structural legitimacy gaps cannot be resolved by behavioral integrity alone
    (TC5-NCQ-04 input — evidence toward structural requirement; not confirmation)
  TC5-DI-03: CORROBORATION PARTIAL — carry to 36E
  Three new NCQs (36D-NCQ-12 through 36D-NCQ-14)

Governing Rule Compliance:
  No architecture decisions made.
  No ElectionGuard, threshold cryptography, blockchain, or cryptographic mechanisms.
  No bounded contexts or aggregates created.
  No ADRs written.
  No candidates promoted to confirmed.
  Constitutional analysis only.
  OBS-36D-02-1 (Authority ≠ Context) carried and verified.
  OBS-36D-03-1 (IR-H) carried and applied.

ARB Observations Applied Before Approval:

  Correction 1 — Constitution as CANDIDATE terminal point, not confirmed:
    Any claim that "the constitution is the terminal point for TC3-NCQ-04"
    must be treated as CANDIDATE, not confirmed finding.
    Round 36D has produced:
      (a) Constitution as CANDIDATE terminal point (36D-CF-03-06 — DESIGN POSSIBILITY)
      (b) Membership ratification as CANDIDATE terminal point (36D-CF-04-07)
    Their relationship is unresolved. Neither is confirmed.
    36D-05 must NOT treat either as settled.

  Correction 2 — Loyalty is behavioral integrity, not structural guarantee:
    "Loyalty to the constitution" is a behavioral integrity claim (36D-HYP-01 applies).
    The constitutional model must remain valid even when actors are NOT loyal.
    The constitutional design must specify: what happens when an actor violates the
    constitution? Who detects? Who adjudicates? What is the remedy? Is it enforceable?
    These are the L-3/L-4/L-5 gaps identified in this document — they are not filled
    by stating that compliance is required.

  Correction 3 — Constitutional interpreter as candidate D43 instance (ARB MODIFIED):
    The question "who interprets the constitution?" reveals a candidate authority gap.
    The constitutional interpreter / adjudicator has no discovered constitutionally
    grounded authority holder in the current domain model.
    This is TC3-NCQ-04 applied at one level higher than criteria-setting.
    The recursion does not terminate by naming the constitution — it must also
    name the interpreter and ground the interpreter's legitimacy.

    36D-HYP-02 (NEW — UNCONFIRMED):
      Constitutional interpretation may represent an additional D43 instance.
      36D-05 must evaluate whether constitutional interpretation constitutes:
        A. A new D43 instance (constitutional interpretation as a distinct function)
        B. A specialization of Governance Authorization
        C. A specialization of Criteria Authority
      The interpreter has not yet been demonstrated to be a constitutional function
      in its own right. D43-instance-INTERPRETER is therefore NOT yet a confirmed
      D43 instance — it is a candidate hypothesis pending 36D-05 evaluation.
    CARRY TO 36D-05 as 36D-HYP-02.

  Correction 4 — 8-question framework belongs in 36E/37, not 36D:
    The 8-question stress test (WHO, appointment mechanism, independence scope,
    challenge mechanism, adjudication mechanism, adjudicator independence,
    remedy, enforceability) is correct. But it is architecture design, not discovery.
    36D identifies what constitutional mechanisms are REQUIRED.
    36E/37 determines how those mechanisms are realized.
    36D-05 must ask: "What is constitutionally necessary?" not "How should it be built?"

  Correction 5 — "If the constitution says so without a mechanism, the gap is deferred":
    This is the core discovery of 36D-04, refined:
    If any legitimacy answer resolves to "because the constitution says so"
    without identifying: legitimacy source, challengeability, revocability, succession —
    the legitimacy question has merely MOVED, not been resolved.
    This applies to every D43 instance and to every proposed constitutional terminal point.

Round 36D-04: APPROVED

Governing Instructions for Round 36D-05 — Constitutional Necessity Analysis:

  The governing question for 36D-05:

    "Which discovered constitutional properties appear necessary
     for constitutional legitimacy, and which currently appear
     beneficial but not yet demonstrated as necessary?"

  This is an evidence classification question, not a design question.
  36D-05 must not evaluate implementation, architecture, or mechanisms.
  36D-05 classifies only what the discovered evidence currently supports.

  Classification scheme:
    REQUIRED:       Evidence from 36C/36D indicates this property is constitutionally
                    necessary — legitimacy is void or fundamentally compromised without it
    SUPPORTED:      Evidence suggests this property strengthens constitutional legitimacy
                    but does not demonstrate that its absence is constitutionally fatal
    OPTIONAL:       Evidence indicates this property is beneficial but not constitutionally
                    indicated by current findings
    UNDETERMINED:   Insufficient evidence to classify; requires further ARB determination

  For each major candidate finding from 36D-01 through 36D-04, apply the
  four-category classification with evidence citation.

  Also carry:
    - 36D-HYP-02 (Correction 3 above) — evaluate A vs B vs C in 36D-05
    - Membership ratification vs constitution as competing candidate terminal points
    - OBS-36D-02-1 (Authority ≠ Context) — carry throughout
    - OBS-36D-03-1 (IR-H: independent of audited subject) — carry throughout
    - No architecture. No mechanisms. No ADRs. Constitutional evidence classification only.

Round 36D-05: AUTHORIZED
```
