# Round 36C-06 — Trust Concentration Threat Model

**Date:** 2026-06-13

**Phase:** Round 36C — Threat Modeling Research

**Sub-document:** 36C-06 (Threat Class 5: Trust Concentration)

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 36C-01 through 36C-05 — APPROVED
- D43 (Enrollment Authority Ownership Gap) — UNRESOLVED
- TC3-DI-02 (CANDIDATE): Undefined ownership is an attack surface
- TC3-NCQ-04: Who governs the governors?
- TC4-DI-01 (CANDIDATE PROGRAM-LEVEL): Certifier legitimacy ≠ certification validity
- TC4-DI-03 (CANDIDATE): Certification is the terminal trustworthiness claim
- TF-36C-04-14 (CANDIDATE PROGRAM-LEVEL): Governance threats exist independently of evidence threats
- OBS-36C-05-5: Many TC-4 failures become possible only when authority is concentrated

**Unresolved constitutional questions carried forward:**
From 36B: NCQ-01, NCQ-02, NCQ-03, NCQ-04
From 36C-02: TC1-NCQ-01, TC1-NCQ-02
From 36C-03: TC2-NCQ-01, TC2-NCQ-02
From 36C-04: TC3-NCQ-01, TC3-NCQ-02, TC3-NCQ-03, TC3-NCQ-04
From 36C-05: TC4-NCQ-01, TC4-NCQ-02, TC4-NCQ-03

**Governing Question:**

```
TC-1 asked: Can evidence disappear?
TC-2 asked: Can false evidence become trusted evidence?
TC-3 asked: Can governance manipulation make a flawed election appear trustworthy?
TC-4 asked: Can certification make an untrustworthy election appear trustworthy?

TC-5 asks: Can trustworthiness fail because too much constitutional power
           accumulates in too few actors, even when:
             evidence is complete,
             evidence is authentic,
             governance procedures were followed,
             and certification occurred correctly?
```

**Preventive Governance Rule (binding):**

```
Do NOT propose:
  Architectures, bounded contexts, aggregates, services.
  ElectionGuard, Helios, cryptographic mechanisms.
  Trust-distribution mechanisms, threshold cryptography.
  Blockchain, organizational solutions.

Threat discovery only.
```

---

## Section 1 — Trust Concentration Defined

```
Trust Concentration occurs when constitutional authority
over multiple independent trustworthiness functions
converges in a single actor or closely coupled set of actors.

From 36B, trustworthiness requires:

  Evidence:      an auditable record of what occurred.
  Criteria:      constitutional standards the election must satisfy.
  Authority:     a constitutionally recognized actor who can declare conformance.

From 36C-04, governance legitimacy adds:

  Governance:    rules constitutionally established and constitutionally applied.

From 36C-05, certification independence requires:

  Independence:  structural separation between the election and its certifier.

Trust Concentration is when any actor, or closely related set of actors,
holds more than one of:
  Evidence control
  Criteria definition
  Governance authority
  Certification authority
  Enrollment authority (D43)

Maximum concentration: one actor holds all five.
Minimum concentration: each function held by structurally independent actors.
```

**Why TC-5 is the final threat class:**

```
TC-1 through TC-4 describe threats that actors can execute.
TC-5 describes the structural precondition that makes all four easier to execute.

A concentrated system is not automatically fraudulent.
A concentrated system is constitutionally fragile.

The difference between an election that is trustworthy
and one that merely appears trustworthy
may be entirely determined by the actor distribution —
not by the evidence, governance, or certification quality.
```

---

## Section 2 — TC5-Q1: Structural Concentration Without Malice

**Governing sub-question:** Can trust concentration create constitutional failure even when no actor behaves maliciously?

```
Scenario — Benign concentration:

  Single election operator:
    Configures GovernanceState (criteria ownership).
    Holds enrollment authority (D43 — unresolved, defaults to operator).
    Runs the system that produces audit events (evidence proximity).
    Appoints the certifier from within the organization (certification authority).

  Every action taken is authentic.
  Every decision follows the established procedure.
  No actor intends fraud.
  No evidence is suppressed.
  No evidence is fabricated.
  Governance follows the rules.
  Certification occurs.

Constitutional consequence:
  All five trustworthiness functions trace to one actor.
  The election's trustworthiness claim is entirely contingent on:
    One actor's competence.
    One actor's honesty.
    One actor's continuing availability.
    One actor's freedom from external coercion.

  None of these is a guaranteed property.
  A single error, conflict of interest, or external pressure
  collapses all five functions simultaneously.

This is constitutional fragility — not constitutional fraud.
But constitutional fragility is a trustworthiness failure
even in the absence of any malicious act.
```

**Finding TC5-Q1-F1:**

```
Trust concentration is constitutionally dangerous
independently of actor intent.

A completely honest, competent election operator
running all five trustworthiness functions
produces an election whose trustworthiness claim
cannot survive the operator's compromise, error, or coercion.

This is a structural constitutional failure.
No actor needed to behave maliciously.
The structure itself is the threat.
```

**The structural failure analogy:**

```
A bridge designed with a single load-bearing point
is constitutionally unsafe even when:
  The engineer who designed it was honest.
  The materials used were correct.
  The builder followed specification.

The failure is structural, not behavioral.

Trust concentration in an election system
is analogous to a single load-bearing point:
  Structurally fragile by design.
  Independently of the quality of the actors.
```

---

## Section 3 — TC5-Q2: Convergence of All Ownership Functions

**Governing sub-question:** What happens when Evidence ownership, Criteria ownership, Authority ownership, and Certification ownership converge into a single actor?

### 3.1 Convergence Map

```
In the maximum concentration scenario:

  Evidence ownership:
    Actor controls GovernanceState (produces governance events).
    Actor controls Vote processing environment (proximity to VoteRecorded).
    Actor controls Audit context infrastructure (receives and stores events).
    → Actor influences what evidence is produced, transmitted, and stored.

  Criteria ownership:
    Actor configured the election parameters in GovernanceState.
    Actor interprets any ambiguous criteria (TC3-NCQ-02).
    → Actor defines what "correct" means for this election.

  Enrollment authority (D43):
    Actor fills the D43 vacuum — no other owner was designated.
    Actor decides who is eligible to vote.
    → Actor controls who participates.

  Certification authority:
    Actor appointed certifier from within their organizational scope.
    Certifier lacks structural independence (TC4-DI-02 candidate).
    → Actor controls the trustworthiness declaration.

  Governance authority:
    Actor holds GovernanceState transitions.
    Actor can open, close, suspend, resume the election.
    → Actor controls the election timeline and rules.

One actor: Evidence × Criteria × Enrollment × Certification × Governance.
```

### 3.2 The Constitutional Consequence of Full Convergence

```
When all five functions converge:

  Completeness claim:  "The record is complete."
    Made by the actor who controls what evidence is produced.
    Self-referential. Cannot be independently evaluated.

  Authenticity claim:  "The record is authentic."
    Made by the actor who controls the evidence infrastructure.
    Self-referential. Cannot be independently evaluated.

  Governance claim:    "The rules were correctly applied."
    Made by the actor who defined and applied the rules.
    Self-referential. Cannot be independently evaluated.

  Certification claim: "The election meets constitutional criteria."
    Made by an actor appointed by the election operator.
    Circular. Cannot be structurally independent.

  Enrollment claim:    "Only eligible voters participated."
    Made by the actor who defined eligibility.
    Self-referential. Cannot be independently evaluated.

All five trustworthiness claims are self-referential
in a fully converged system.

A self-referential trustworthiness claim is constitutionally suspect
for the same reason self-certification is constitutionally suspect:
  The claim cannot be evaluated by any actor
  who is independent of the claim's author.
```

**Finding TC5-Q2-F1:**

```
Full convergence of Evidence, Criteria, Authority, Certification,
and Enrollment functions into a single actor
renders all trustworthiness claims self-referential.

Self-referential trustworthiness claims are constitutionally suspect
pending resolution of TC5-NCQ-04.

  If TC5-NCQ-04 resolves: structural distribution is constitutionally required →
    self-referential claims are constitutionally void.

  If TC5-NCQ-04 resolves: behavioral integrity can substitute →
    the constitutional status of self-referential claims depends on
    demonstrated behavioral integrity — and the claims are not void by structure alone.

What is confirmed regardless of TC5-NCQ-04:
  Self-referential claims cannot be independently evaluated.
  The evaluator is always entangled with the claim's author.
  This is constitutionally suspect under any model.

The suspicion derives from the structure of authority,
not from the quality of the process.
```

---

## Section 4 — TC5-Q3: D43 and Trust Concentration

**Governing sub-question:** How does D43 (the Enrollment Authority Ownership Gap) interact with trust concentration?

### 4.1 D43 as a Concentration Enabler

```
D43: Enrollment authority ownership UNRESOLVED.

No discovered aggregate holds enrollment authority.
In a real election, enrollment decisions must be made.
Someone must decide who is eligible.

If no actor is constitutionally designated:
  The actor who fills the operational vacuum
  holds enrollment authority by default.

  In most election systems, this is the election operator.
  The same actor who:
    Configures GovernanceState (criteria).
    Runs the audit infrastructure (evidence).
    Appoints the certifier (certification authority).

  D43 resolution defaults to the actor already holding the most authority.
  This is concentration by institutional gravity, not by design.
```

### 4.2 D43 as a Legitimacy Gap Within Concentration

```
D43 is not only a concentration risk.
It is a legitimacy gap within whatever concentration exists.

Even if all other authority is distributed:
  Evidence: independently controlled.
  Criteria: independently established.
  Certification: structurally independent.

  But enrollment authority is unresolved.
  Any actor — including an independently positioned actor —
  who holds enrollment authority without constitutional designation
  holds illegitimate authority by structural default.

D43 means:
  Enrollment decisions cannot be constitutionally evaluated
  regardless of the degree of distribution in other functions.

  A fully distributed election system with an unresolved D43
  has one illegitimate authority by structural default.

  This is a concentration risk even in a distributed system.
```

### 4.3 D43 × TC-5 Constitutional Consequence

```
If enrollment authority is concentrated with operational authority:
  TC5-Q2 (full convergence) applies.

If enrollment authority is concentrated but operationally separate:
  One authority holds more power than any other single authority.
  The election's eligibility foundation is constitutionally unvalidated.

If enrollment authority is distributed but constitutionally undefined:
  D43 is the gap.
  Multiple actors may claim enrollment authority.
  Competing claims cannot be resolved.
  Enrollment decisions become contested.

In all three cases:
  D43 produces a constitutional deficiency regardless of the broader distribution.
  D43 must be resolved before any trustworthiness claim about
  eligible participation can be constitutionally defensible.
```

---

## Section 5 — TC5-Q4: Valid Process, Flawed Distribution

**Governing sub-question:** Can a constitutionally valid process still produce an untrustworthy outcome because power distribution itself is flawed?

```
TC5-Q4 combines TC5-Q1 (benign concentration) with TC5-Q2 (full convergence)
to produce the most important TC-5 scenario:

All steps are constitutionally valid in isolation.
The distribution of authority makes the whole constitutionally suspect.

Step 1: Election operator configures GovernanceState.
  Valid: The operator has operational authority to configure the system.

Step 2: GovernanceState parameters are set correctly.
  Valid: All parameters follow the established requirements.

Step 3: Voting occurs. Votes are correctly recorded.
  Valid: VoteRecorded events are authentic. No suppression. No fabrication.

Step 4: Audit log is complete and authentic.
  Valid: All events were received and recorded correctly.

Step 5: Election operator appoints certifier from their organization.
  Valid: The certifier has the correct role title and mandate letter.

Step 6: Certifier evaluates the audit log against the configured criteria.
  Valid: The certifier applies correct methodology.

Step 7: Certifier declares the election valid.
  Valid: The declaration follows the correct procedure.

Constitutional consequence of the whole:
  Step 1: Operator holds criteria authority.
  Step 5: Operator controls certification appointment.
  Step 6: Certifier evaluates criteria set by the appointer.
  Step 7: Certifier declares validity against criteria controlled by the appointer.

All steps are individually valid.
The authority distribution converts this sequence
into a constitutionally suspect process:
  The operator certified their own criteria application.
  The certifier was structurally controlled by the party being certified.

The valid process with concentrated authority produces
a constitutional structure constitutionally suspect for the same reasons as self-certification.

Whether this produces a constitutionally void outcome
depends on TC5-NCQ-04. What is demonstrated:
  Procedural validity does not resolve the structural suspicion.
```

**The process-validity ≠ distribution-validity insight:**

```
Individual step validity does not imply systemic constitutional validity.

A sequence of individually valid steps
can produce a constitutionally suspect outcome
when the distribution of authority over those steps is flawed.

This is TC-5's most important contribution:
  Procedural correctness does not guarantee constitutional trustworthiness.
  Structural distribution is an independent constitutional requirement.

  Correct procedure + concentrated authority = constitutionally suspect outcome.
  Correct procedure + appropriate distribution = constitutionally defensible outcome.
```

---

## Section 6 — TC5-Q5: Root Cause Analysis

**Governing sub-question:** Is Trust Concentration the root cause behind Evidence Suppression, Governance Manipulation, and Certification Abuse? Or is it an independent threat class?

### 6.1 Evidence That TC-5 Enables TC-1 Through TC-4

```
TC-1 (Evidence Suppression):
  Suppression requires write or delete access to the audit infrastructure.
  A concentrated actor holds this access by definition.
  → Concentration enables TC-1, but does not exclusively cause it.
  (Software defect can also cause TC-1 without concentration.)

TC-2 (Evidence Fabrication):
  Fabrication requires event injection or post-observation modification.
  A concentrated actor controls event infrastructure.
  → Concentration enables TC-2, but does not exclusively cause it.
  (A distributed insider can also cause TC-2.)

TC-3 (Governance Manipulation):
  Criteria manipulation requires GovernanceState authority.
  Rule changes require authority over transitions.
  A concentrated actor holds both.
  → Concentration enables TC-3, but criteria ambiguity can exist structurally
  without concentration (TC3-Q2 demonstrated this independently).

TC-4 (Certification Abuse):
  Self-certification is the purest form of certification abuse.
  Self-certification is the purest form of trust concentration.
  → TC-4 independence failure and TC-5 are deeply coupled.
  OBS-36C-05-5 specifically flagged this relationship.
```

### 6.2 Evidence That TC-5 Is Partially Independent

```
TC-5 has constitutional consequences not derived from TC-1 through TC-4:

Property 1 — Constitutional fragility without exploitation:
  A concentrated system is constitutionally fragile
  even when no TC-1 through TC-4 attack occurs.
  The fragility is the threat — not the exploit.
  TC-1 through TC-4 describe exploits. TC-5 describes structural fragility.

Property 2 — Survivability failure:
  A constitutionally sound trustworthiness model must survive
  any single actor's compromise, error, or coercion.
  A concentrated system fails this test by design.
  No individual TC-1 through TC-4 threat captures this survivability requirement.

Property 3 — Self-referential claim production:
  TC5-Q2 demonstrated that full convergence produces self-referential claims.
  Self-referential claims are constitutionally void independently of any exploit.
  A distributed system produces independently evaluable claims.
  A concentrated system cannot — regardless of whether any attack occurs.
```

### 6.3 Relationship Finding

```
TC-5 is best characterized as a RISK MULTIPLIER and STRUCTURAL ENABLER
across TC-1 through TC-4, PLUS an independent threat in its own right.

  TC-5 as enabler:
    Concentration increases the probability and impact of TC-1 through TC-4.
    A single compromised actor in a concentrated system
    can execute TC-1, TC-2, TC-3, and TC-4 simultaneously.
    In a distributed system, each requires separately compromising a different actor.

  TC-5 as independent threat:
    Constitutional fragility (without any exploit)
    Self-referential claim production (without any attack)
    Single-actor survivability failure (without any malice)
    are TC-5 threats with no TC-1 through TC-4 equivalent.

Conclusion:
  TC-5 is neither purely the root cause of TC-1 through TC-4,
  nor a purely independent threat.

  TC-5 is a multiplier that amplifies all prior threat classes
  AND introduces structural failure modes they do not capture.

  The five-threat-class ordering is constitutionally defensible.
  TC-5 is not redundant with TC-1 through TC-4.
  It provides the structural context within which all prior threats operate.
```

---

## Section 7 — TC5-Q6: Authority, Ownership, Independence, Trustworthiness

**Governing sub-question:** What is the constitutional relationship between Authority, Ownership, Independence, and Trustworthiness?

### 7.1 Four Distinct Constitutional Properties

```
These four concepts have appeared throughout 36C.
TC-5 is the point at which their relationship becomes central.

Authority:
  The constitutional power to make binding decisions.
  Example: GovernanceState holds transition authority.

Ownership:
  The constitutional responsibility to define, maintain, and be accountable for a function.
  Example: Who owns the expected evidence set? (Gap A-3 / TC3-NCQ-03)
  Ownership may be unassigned (D43, Gap A-3) — creating an attack surface (TC3-DI-02).

Independence:
  The structural separation between the actor who performs a function
  and the actor whose performance is being evaluated.
  Example: Certifier must be independent of the election operator (NCQ-02).

Trustworthiness:
  The constitutional property of an election whose claims can be evaluated
  by actors independent of those who produced the claims.
  Trustworthiness requires that every trustworthiness claim
  have an evaluator independent of its author.
```

### 7.2 The Dependency Structure

```
Trustworthiness depends on Independence.
  A trustworthiness claim is constitutionally defensible only if
  it can be evaluated by an independent actor.
  (TC-4 demonstrated this for certification.)
  (TC-5 extends this to all trustworthiness functions.)

Independence depends on Distribution of Authority.
  Independence is only possible when authority over different functions
  is held by structurally separated actors.
  Concentrated authority prevents independence.
  (TC5-Q4 demonstrated this even with correct procedure.)

Distribution of Authority depends on Ownership Assignment.
  Authority cannot be appropriately distributed if ownership is undefined.
  Undefined ownership creates vacuums that collapse to concentration.
  (TC3-DI-02: undefined ownership is an attack surface.)
  (D43: undefined enrollment authority defaults to concentration.)

Therefore:
  Trustworthiness
    ← requires Independence
    ← requires Distribution of Authority
    ← requires Ownership Assignment

  A gap in any prerequisite propagates to a gap in Trustworthiness.
```

### 7.3 The Constitutional Dependency Chain

```
This is the dependency chain discovered across 36B and 36C:

  OWNERSHIP ASSIGNMENT
  (Must be defined. Gap A-3, D43, NCQ-03 are assignment gaps.)
          ↓
  AUTHORITY DISTRIBUTION
  (Once owned, authority must be distributed. TC-5 investigates this.)
          ↓
  STRUCTURAL INDEPENDENCE
  (Distribution must produce independence. TC-4 investigated this.)
          ↓
  CONSTITUTIONAL TRUSTWORTHINESS
  (Independence enables trustworthiness claims to be externally evaluated.)

Each level is necessary.
None is sufficient without the levels below it.

A gap at any level makes trustworthiness claims constitutionally incomplete.

Evidence integrity (TC-1, TC-2) is a parallel prerequisite:
  Trustworthiness also requires complete (TC-1) and authentic (TC-2) evidence.
  These are independent of the authority dependency chain.
  Both chains must hold.
```

---

## Section 8 — TC5 Actor Analysis

| Function | Concentration Failure Mode | Structural consequence |
|----------|--------------------------|----------------------|
| Evidence specification | Single actor defines expected evidence set | Gap A-3 becomes self-referential; completeness claim is void |
| Evidence production | Single actor controls all event-producing aggregates | TC-1 and TC-2 threats amplified to single-actor risk |
| Enrollment (D43) | Defaults to operational actor when undefined | Eligibility decisions made by actor with operational conflict of interest |
| Criteria definition | Single actor defines constitutional criteria | TC3-DI-03 violated; criteria fixed by interested party |
| Criteria interpretation | Single actor resolves ambiguity | TC3-NCQ-02 exploitable by criteria-defining actor |
| Governance transitions | Single actor controls GovernanceState | TC-3 threats amplified to single-actor risk |
| Certification appointment | Election operator appoints certifier | TC-4 independence structurally violated before certification begins |
| Certification | Certifier controlled by operator | TC-4 circular certification; self-certification by proxy |

**Software defect as structural concentration:**

```
Software defects can produce functional concentration
even in constitutionally distributed systems.

Example:
  Five independent actors are designated.
  A software defect routes all event production through one actor's infrastructure.
  The other actors' independence is nominal; the events still flow through one point.

  The constitutional distribution was designed.
  The software implementation concentrated it.

  No actor intended concentration.
  The concentration is real.

This produces the same constitutional fragility as deliberate concentration.
TC-5 applies to implementation concentration equally with deliberate concentration.
A trust model must survive implementation errors, not only intentional design failures.
```

---

## Section 9 — The Constitutional Authority Paradox

```
TC-5 surfaces a constitutional paradox:

  To verify that authority is appropriately distributed,
  an observer needs authority to evaluate the distribution.

  But if that evaluating authority is itself concentrated,
  the evaluation is self-referential.

  Who evaluates the evaluator?
  Who audits the auditor?
  Who distributes authority over distribution?

This is TC3-NCQ-04 applied at the meta-level:
  "Who governs the governors?"
  extended to:
  "Who distributes the distributors?"

The constitutional paradox:
  No purely internal evaluation of authority distribution
  can confirm that the distribution is constitutionally appropriate.
  Internal evaluation is always self-referential to some degree.

This is why TC4-NCQ-03 (external grounding) may be constitutionally required:
  External grounding terminates the recursion.
  Without external grounding, the recursion continues indefinitely.

TC-5 does not resolve this paradox.
TC-5 names it as a fundamental constitutional question.
36D (Trust Distribution) and 36E (Architecture Impact Assessment) must address it.
```

---

## Section 10 — TC5 Threat Interaction Matrix

```
TC-1 × TC-5 (Evidence Suppression × Trust Concentration):
  Concentrated evidence control makes TC-1 single-actor feasible.
  Distributed evidence control requires multi-actor coordination for TC-1.
  Concentration: HIGH amplification of TC-1 risk.

TC-2 × TC-5 (Evidence Fabrication × Trust Concentration):
  Concentrated evidence control makes TC-2 single-actor feasible.
  Distributed control requires multi-actor coordination for TC-2.
  Concentration: HIGH amplification of TC-2 risk.

TC-3 × TC-5 (Governance Manipulation × Trust Concentration):
  Concentrated governance authority makes criteria and rule manipulation feasible.
  D43 concentrated with operational authority: enrollment manipulation feasible.
  Concentration: CRITICAL amplification of TC-3 risk.

TC-4 × TC-5 (Certification Abuse × Trust Concentration):
  Concentrated authority enables self-certification by design.
  TC-4 independence failure IS TC-5 in the certification domain.
  Concentration: TC-4 and TC-5 are structurally coupled for certification.

TF-36C-COMB-01 × TC-5 (Symmetric threat pair × Concentration):
  TC-1 (record smaller than reality) and TC-2 (record contains false events)
  are both more feasible under concentrated evidence control.
  Concentration amplifies the program-level symmetric threat pair.
```

---

## Section 11 — Candidate Domain Insights

**TC5-DI-01 — CANDIDATE DOMAIN INSIGHT: Constitutional fragility from concentration is a threat independent of actor intent**

```
A constitutionally concentrated election is structurally fragile
regardless of the integrity of the actors who hold concentrated authority.

Constitutional trustworthiness requires structural distribution.
It is not satisfied by behavioral integrity alone.

A system that depends entirely on one actor's honesty
is not constitutionally trustworthy —
it is constitutionally vulnerable.

Status: CANDIDATE — requires corroboration from 36D and 36E.
```

**TC5-DI-02 — CANDIDATE DOMAIN INSIGHT: Trust Concentration is a risk multiplier across all five threat classes**

```
TC-5 is not simply a fifth threat to add alongside TC-1 through TC-4.

Trust Concentration structurally amplifies the probability and impact
of every prior threat class:

  Concentrated authority reduces the number of actors
  who must be compromised to execute any of TC-1 through TC-4.

  In the maximum concentration case, one actor's compromise
  enables all four prior threat classes simultaneously.

TC-5 is the structural condition within which all prior threats operate.
It is a multiplier, not merely a peer threat.

Status: CANDIDATE — direction of relationship requires corroboration from 36D.
```

**TC5-DI-03 — CANDIDATE PROGRAM-LEVEL INSIGHT: Ownership Assignment, Authority Distribution, and Structural Independence are three independent constitutional prerequisites for Trustworthiness**

```
TC-5 synthesizes 36B through 36C to reveal three distinct constitutional prerequisites.

This is not a TC-5-specific insight.
It is a program-level synthesis spanning:
  36B (Ownership gaps: Gap A-3, D43, NCQ-03)
  36C-03 (TC2-NCQ-01: Provenance responsibility)
  36C-04 (TC3-DI-02: Undefined ownership as attack surface)
  36C-05 (TC4-DI-02: Independence as constitutional property)
  36C-06 (TC-5: Distribution as independent prerequisite)

Three constitutional prerequisites:

  Ownership Assignment:
    Defined. Unambiguous. Accountable.
    (Gap A-3, D43, TC2-NCQ-01, NCQ-03 are ownership gaps.)

  Authority Distribution:
    Appropriately separated across independent actors.
    (TC-5 investigates this dimension.)

  Structural Independence:
    Each evaluating actor is independent of the actor being evaluated.
    (TC-4 investigated this for certification.)

None implies the others:
  Ownership can be assigned without appropriate distribution.
  Distribution can exist without genuine independence (nominal vs structural).
  Independence can appear structurally without grounding (TC4-NCQ-03).

All three are necessary conditions for constitutional trustworthiness.
None is sufficient alone.

Status: CANDIDATE PROGRAM-LEVEL — synthesizes 36B through 36C-06.
Corroboration required from 36D before promotion to confirmed.
If confirmed in 36D: this becomes a central output of Round 36.
```

**TC5-DI-04 — CANDIDATE DOMAIN INSIGHT: Procedural correctness and structural distribution are independent constitutional requirements**

```
TC5-Q4 demonstrated:
  All individual steps can be constitutionally valid.
  The distribution of authority can still be constitutionally suspect.

These are not the same requirement.
A procedurally correct election with concentrated authority
and a structurally distributed election with a procedural error
are both constitutionally deficient — through different mechanisms.

Both deficiencies must be addressed independently.
Resolving one does not resolve the other.

Status: CANDIDATE — requires corroboration from 36D.
```

---

## Section 12 — New Constitutional Questions

**TC5-NCQ-01: What is the constitutionally permissible minimum distribution of authority?**

```
TC-5 demonstrates that concentration is constitutionally dangerous.
TC-5 does not establish the constitutionally required distribution.

  Must every trustworthiness function be held by a separate actor?
  Can two functions be held by the same actor if they are non-conflicting?
  Is there a constitutional minimum distribution standard?

TC5-NCQ-01 requires ARB constitutional determination.
(This is the foundational question for 36D — Trust Distribution Research.)
```

**TC5-NCQ-02: Can a constitutionally valid election be conducted by a single-organization actor?**

```
Many legitimate elections are conducted entirely within one organization:
  The organization holds all authority.
  The organization appoints the certifier.
  The organization defines the criteria.

Is this constitutionally permissible?
  If YES: What minimum internal independence is required?
  If NO: What external authority must always be present?

This question determines the scope of TC-5's constitutional impact on NRNA.

TC5-NCQ-02 requires ARB constitutional determination.
```

**TC5-NCQ-03: Does the constitutional authority chain always require external termination?**

```
TC3-NCQ-04 asked: who governs the governors?
TC4-NCQ-03 asked: does the authority chain require external grounding?
TC5-NCQ-03 generalizes: at which constitutional functions is external grounding required?

  Is external grounding required for all five functions?
  For certification only (as TC-4 suggests)?
  For enrollment authority only (D43 implication)?
  For criteria definition only?

The answer shapes the entire 36E architectural impact assessment.
And the scope of Round 37 ADR authoring.

TC5-NCQ-03 requires ARB constitutional determination.
```

**TC5-NCQ-04: Is structural distribution constitutionally required, or is behavioral integrity sufficient?**

```
TC5-Q1 demonstrated: concentration is fragile even with honest actors.

The constitutional question:
  Is structural distribution a REQUIREMENT?
  Or is demonstrated behavioral integrity a constitutionally acceptable substitute?

  If structural distribution is required:
    Election systems must demonstrate appropriate distribution by design.
    Behavioral integrity is insufficient.

  If behavioral integrity can substitute:
    An election conducted by one honest, competent actor
    may be constitutionally valid despite concentration.

This question determines whether TC-5 produces binding architectural constraints
or merely governance recommendations.

TC5-NCQ-04 requires ARB constitutional determination.
This may be the most consequential constitutional question in the entire program.
```

---

## Section 13 — Threat Findings Summary

| Finding | Type | Priority |
|---------|------|----------|
| TF-36C-06-01: Trust concentration produces constitutional fragility independently of actor intent or behavior | Constitutional | CRITICAL |
| TF-36C-06-02: Full convergence of Evidence, Criteria, Enrollment, Governance, and Certification in one actor renders all trustworthiness claims self-referential and constitutionally suspect — void pending resolution of TC5-NCQ-04 | Constitutional | CRITICAL |
| TF-36C-06-03: D43 (enrollment authority gap) defaults to the actor with most operational authority — concentration by institutional gravity | Constitutional | CRITICAL |
| TF-36C-06-04: D43 produces a legitimacy gap at enrollment even in otherwise distributed election systems | Constitutional | CRITICAL |
| TF-36C-06-05: Valid procedure with concentrated authority produces a process constitutionally indistinguishable from self-certification | Constitutional | CRITICAL |
| TF-36C-06-06: TC-5 is a risk multiplier across TC-1 through TC-4 — concentration reduces the number of actors required to execute all prior threat classes | Constitutional | CRITICAL |
| TF-36C-06-07: TC-5 has independent constitutional properties not captured by TC-1 through TC-4 — structural fragility, self-referential claims, survivability failure | Constitutional | CRITICAL |
| TF-36C-06-08: Software implementation can concentrate authority even in constitutionally distributed designs | Constitutional | HIGH |
| TF-36C-06-09: Ownership, Authority Distribution, and Independence are three independent constitutional prerequisites; none implies the others | Constitutional | CRITICAL |
| TF-36C-06-10: Procedural correctness does not guarantee structural distribution; both are independent constitutional requirements | Constitutional | CRITICAL |
| TF-36C-06-11: The constitutional authority paradox — internal evaluation of authority distribution is self-referential; may require external grounding to terminate | Constitutional | HIGH |
| TF-36C-06-12: TC5-NCQ-01 — constitutionally permissible minimum distribution of authority is undefined | Constitutional | CRITICAL |
| TF-36C-06-13: TC5-NCQ-02 — whether single-organization elections are constitutionally permissible is undefined | Constitutional | CRITICAL |
| TF-36C-06-14: TC5-NCQ-03 — which functions require external constitutional grounding is undefined | Constitutional | CRITICAL |
| TF-36C-06-15: TC5-NCQ-04 — whether structural distribution is constitutionally required or behavioral integrity can substitute is the most consequential open question in the program | Constitutional | CRITICAL |

**Candidate Domain Insights:**

```
TC5-DI-01 (CANDIDATE): Constitutional fragility from concentration is a threat independent of actor intent
TC5-DI-02 (CANDIDATE): Trust Concentration is a risk multiplier across all five threat classes, not merely a peer threat
TC5-DI-03 (CANDIDATE PROGRAM-LEVEL): Ownership Assignment + Authority Distribution + Structural Independence
  are three independent prerequisites for Trustworthiness — synthesizes 36B through 36C-06
TC5-DI-04 (CANDIDATE): Procedural correctness and structural distribution are independent constitutional requirements
```

**Architectural Impact Candidates (for 36E):**

```
AIC-36C-06-01: Authority distribution model —
  what is the constitutionally required distribution of trustworthiness functions?
  Who may hold which functions concurrently?
  (TC5-NCQ-01 input for 36E)

AIC-36C-06-02: D43 resolution — enrollment authority designation
  must precede any constitutionally defensible eligibility claim.
  (TC5-Q3 / D43 primary finding — CRITICAL for 36E)

AIC-36C-06-03: External grounding identification —
  which trustworthiness functions constitutionally require external authority?
  Which can be internal-but-constitutionally-separated?
  (TC5-NCQ-03 input for 36E and 37)

AIC-36C-06-04: Constitutional fragility vs behavioral integrity —
  is structural distribution a design requirement or a governance recommendation?
  (TC5-NCQ-04 input — shapes ADR scope in Round 37)

AIC-36C-06-05: Independence verification mechanism —
  how does the system confirm that constitutional distribution
  was implemented as designed, not concentrated by software?
  (TC-5 × software defect finding / input for 36E)
  [OBS-36C-06-4: CARRY FORWARD TO 36E — "Designed Distribution ≠ Actual Distribution"
  is a major 36E architectural consideration. Software defect as structural concentration
  is an excellent finding. AIC-36C-06-05 is the canonical carrier for this input.]
```

---

## ARB Decision

```
Round 36C-06 — Trust Concentration Threat Model

[ARB REVIEW COMPLETE]

Research Discipline:        HIGH
Threat Modeling Quality:    HIGH
Governance Discipline:      HIGH
Architecture Neutrality:    HIGH
Confidence:                 HIGH

Central findings:
  TF-36C-06-02: Full convergence produces self-referential trustworthiness claims
    (constitutionally suspect pending TC5-NCQ-04, not void).
  TF-36C-06-06: TC-5 is a risk multiplier across all prior threat classes.
  TF-36C-06-07: TC-5 has independent constitutional properties beyond TC-1/2/3/4.
  TF-36C-06-09: Ownership × Authority × Independence: three independent prerequisites.
  TF-36C-06-15: TC5-NCQ-04 (structural distribution required vs behavioral integrity) —
    most consequential open question in the program.

  TC5-DI-01/02/03/04: Four candidate insights.
  TC5-DI-03: ELEVATED TO CANDIDATE PROGRAM-LEVEL (synthesizes 36B through 36C-06).
  TC5-NCQ-01/02/03/04: Four new constitutional questions.

Governing Rule Compliance:
  No ElectionGuard, Helios, threshold cryptography, or blockchain.
  No bounded contexts, aggregates, or services created.
  No architecture decisions made.
  Threats discovered and classified only.

ARB Observations Applied:

  OBS-36C-06-1 (APPLIED):
    "Constitutionally void" replaced with "constitutionally suspect pending resolution
    of TC5-NCQ-04" throughout Sections 3.2 and TF-36C-06-02. The language correctly
    reflects that the constitutional consequence is unresolved, not settled.

  OBS-36C-06-2 (APPLIED):
    TC5-Q4 sections updated: "constitutionally void" → "constitutionally suspect."
    TC5-DI-04 updated accordingly. Valid procedure under concentrated authority is
    constitutionally suspect, not constitutionally void.

  OBS-36C-06-3 (APPLIED):
    TC5-DI-03 elevated to CANDIDATE PROGRAM-LEVEL INSIGHT.
    "Ownership Assignment → Authority Distribution → Structural Independence →
    Constitutional Trustworthiness" is the strongest synthesis insight produced
    by Round 36C. Corroboration required from 36D before promotion to confirmed.

  OBS-36C-06-4 (APPLIED):
    AIC-36C-06-05 annotated as explicit carry-forward to 36E:
    "Designed Distribution ≠ Actual Distribution" is a major 36E architectural
    consideration. Software defect as structural concentration is a finding of
    high constitutional importance. AIC-36C-06-05 is the canonical carrier.

  OBS-36C-06-5 (APPLIED — see Governing Instructions below):
    36C-Closure must explicitly analyze the dependency structure among TC-1 through TC-5.
    Round 36C has evolved from a threat catalog into a threat dependency model.

Round 36C-06: APPROVED

Governing Instructions for Round 36C-Closure:

  The primary obligation of 36C-Closure is to synthesize the dependency structure
  that has emerged across TC-1 through TC-5. This is not a summary document —
  it is a structural analysis of how the five threat classes relate.

  The dependency structure to analyze:

    TC-5 (Trust Concentration)
      ↓ amplifies
    TC-4 (Certification Abuse — validates)
      ↓ validates
    TC-3 (Governance Manipulation — governs)
      ↓ governs
    TC-2 (Evidence Fabrication — authenticates)
      ↓ authenticates
    TC-1 (Evidence Suppression)

  36C-Closure must address:
    1. The dependency chain and what each threat class does to the chain above it
    2. Why TC-5 is not merely a peer threat but a risk multiplier across all four
    3. The three candidate program-level insights (TF-36C-COMB-01, TF-36C-04-14,
       TF-36C-05-01) and their relationship to TC5-DI-03
    4. All unresolved NCQs and their priority ordering for 36D
    5. The emerging constitutional trustworthiness formula:
       Evidence Completeness + Evidence Authenticity + Governance Legitimacy +
       Certification Validity + Authority Distribution (not cryptography)
    6. What Round 36D (Trust Distribution) must investigate, given 36C's findings

  36C-Closure must NOT:
    - Create bounded contexts, aggregates, or services
    - Make architecture decisions
    - Implement or recommend ElectionGuard, Helios, or any protocol
    - Promote candidates to confirmed without 36D corroboration

Round 36C-Closure: AUTHORIZED
```
