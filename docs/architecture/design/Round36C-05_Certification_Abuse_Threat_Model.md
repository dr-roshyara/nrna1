# Round 36C-05 — Certification Abuse Threat Model

**Date:** 2026-06-13

**Phase:** Round 36C — Threat Modeling Research

**Sub-document:** 36C-05 (Threat Class 4: Certification Abuse)

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 36B Closure — APPROVED (Certification defined: Evidence + Criteria + Authority)
- Round 36B-CFI-01 (CONFIRMED): Auditability / Verifiability / Certification are separable constitutional capabilities
- Round 36C-01 through 36C-04 — APPROVED
- NCQ-02 (from 36B): Certification Authority requires structural independence — external or internal-but-separated (open)
- TC3-NCQ-04 (from 36C-04): Who governs the governors? Carry into TC-4.
- TF-36C-04-14 (CANDIDATE PROGRAM-LEVEL): governance threats independent of TC-1 and TC-2

**Carry-forward insights (all candidate unless noted):**

```
TC1-DI-01:  Absence of evidence is not evidence of absence.
TC2-DI-01:  Presence of evidence is not evidence of truth.
TC3-DI-01:  Governance-related threats exist independently of evidence completeness and authenticity.
TC3-DI-02:  Undefined ownership is an attack surface.
TC3-DI-03:  Constitutional criteria must be fixed before the decisions they govern.
TC3-NCQ-04: Who governs the governors?
```

**Governing Question:**

```
TC-1 asked: Can evidence disappear?
TC-2 asked: Can false evidence become trusted evidence?
TC-3 asked: Can governance manipulation make a flawed election appear trustworthy?

TC-4 asks: Can certification make an untrustworthy election appear trustworthy?

More precisely:
Does the act of certification itself introduce threat vectors
that are not already captured by TC-1, TC-2, or TC-3?
```

**Preventive Governance Rule (binding):**

```
Do NOT propose mechanisms.
Do NOT introduce: ElectionGuard, Helios, cryptographic attestation,
  digital signatures, threshold schemes, blockchain, Merkle trees,
  ZK proofs, or any implementation technique.
Do NOT create bounded contexts, aggregates, or services.
Threat discovery only.
```

---

## Section 1 — Certification Defined

**From Round 36B (confirmed):**

```
Certification requires three components:

  Evidence:    An auditable record of what occurred.
  Criteria:    Constitutional standards the election must satisfy.
  Authority:   A constitutionally recognized actor who can declare conformance.

All three are necessary.
None is sufficient alone.

  Evidence without Authority: auditable but not certified.
  Criteria without Authority: evaluable but not certified.
  Authority without Evidence: declared but not defensible.
  Authority without Criteria: declared but not meaningful.
```

**What Certification Abuse means in TC-4:**

```
Certification Abuse occurs when the certification act
produces a trustworthiness claim
that is constitutionally unwarranted.

This can arise from:
  A. The evidence being wrong (TC-2 intersection — not pure TC-4)
  B. The criteria being wrong (TC-3 intersection — not pure TC-4)
  C. The Authority being constitutionally invalid (pure TC-4)
  D. The Independence being absent (pure TC-4)
  E. The Certification act being performed out of constitutional sequence (TC-4 timing)

TC-4's distinctive contribution is C, D, and E.
A and B are intersections with prior threat classes.
Both are in scope — intersections reveal how threats compound.
```

---

## Section 2 — TC4-Q1: Certification Against False Evidence (TC-2 × TC-4 Intersection)

**Governing sub-question:** What is the constitutional status of a certification performed in good faith against a fabricated record?

```
Scenario:
  VoteRecorded events are fabricated (TC-2 attack).
  The certifier receives the ElectionAuditLog.
  The certifier has constitutional authority.
  The certifier applies correct criteria.
  The certifier certifies — in good faith.

Constitutional consequence:
  The certification is constitutionally void.
  Not because the certifier abused their authority.
  But because the evidence they certified was false.

This is the TC-2 × TC-4 threat:
  Fabrication enables false certification
  without requiring any certifier misconduct.

TC-4 finding: A constitutionally valid certifier
  applying constitutionally correct criteria
  against a constitutionally false record
  produces a constitutionally void certification.

The certifier is not corrupt. The certification is still void.
```

**The good-faith certifier trap:**

```
TC-3 established (TF-36C-04-02):
  Authentic audit records cannot distinguish
  legitimate-authority action from false-authority action.

TC-4 extends this:
  A certifier with perfect process and constitutional authority
  cannot distinguish:
    Complete, authentic evidence → certifiable election
  from:
    Complete, fabricated evidence → void election

Without an independent evidence reference:
  The certifier's constitutional validity does not protect
  against certifying a fabricated record.

This is constitutionally significant:
  It separates the certifier's constitutional legitimacy
  from the certification's constitutional validity.

  Legitimate certifier + false evidence = constitutionally void certification.
  This is not certifier misconduct. It is a structural gap.
```

---

## Section 3 — TC4-Q2: Certification Against Incomplete Evidence (TC-1 × TC-4 Intersection)

**Governing sub-question:** What is the constitutional status of a certification performed against a suppressed record?

```
Scenario:
  GovernanceTransitionCompleted events are suppressed (TC-1 attack).
  The certifier receives an incomplete ElectionAuditLog.
  The certifier applies criteria that require governance compliance evidence.
  The criteria formally require Concern A evidence.
  The record does not contain it.

Constitutional consequence:
  The certifier cannot satisfy criteria that require absent evidence.

Option A: The certifier notices the absence and declines to certify.
  The TC-1 attack prevented certification. Election is not certified.
  Certification correctly failed.

Option B: The certifier does not notice or accepts implicit assumption
  that absent governance events mean "no governance events occurred" (nothing happened).
  The certifier certifies despite missing evidence.
  The certification is constitutionally void.
  Certifier may be deceived, incompetent, or negligent.

Option C: The expected evidence set is undefined (Gap A-3 / TC3-NCQ-03).
  The certifier does not know that GovernanceTransitionCompleted should exist.
  The certifier has no reference for completeness.
  Certification proceeds against criteria that cannot evaluate governance compliance.
  The certification is constitutionally insufficient even if the certifier acts correctly.
```

**The Gap A-3 certification trap:**

```
Gap A-3 is not only a completeness problem.
It is a certification problem.

If the expected evidence set is undefined:
  No certifier can evaluate completeness.
  Any certification claim about completeness is constitutionally unsupported.

A certifier can state:
  "The record I received satisfies the criteria I was given."

A certifier cannot state (without Gap A-3 resolution):
  "The record is complete."

These are different constitutional claims.
The first is bounded. The second requires an independent reference.
```

---

## Section 4 — TC4-Q3: Certification Criteria Abuse (TC-3 × TC-4 Intersection)

**Governing sub-question:** What happens when a certifier applies constitutionally wrong criteria?

```
From TC3-Q2 (Criteria Manipulation):
  Criteria can be undefined, ambiguous, or changed.

TC-4 investigates: What does this mean for the certification act?

Scenario A — Wrong-epoch criteria:
  Voting occurred under Criteria A.
  Criteria changed to B before certification.
  Certifier applies Criteria B.
  Election satisfies B but not A.

  Constitutional consequence:
    Certification declares conformance to criteria that did not govern the election.
    The election may have failed criteria A under which it was conducted.
    The certification is constitutionally void.

Scenario B — Ambiguous criteria:
  Certifier selects the interpretation that validates the desired outcome.
  The other interpretation would require rejection.
  Both interpretations are linguistically defensible.

  Constitutional consequence:
    The choice of interpretation becomes the effective criteria.
    The certifier has acquired criteria ownership by exercising interpretation authority.
    TC3-NCQ-02 (who has interpretation authority?) is directly implicated.

Scenario C — Absence of criteria:
  No constitutional criteria were established for this election.
  Certifier invents criteria at the time of certification.
  Certifier certifies against self-created criteria.

  Constitutional consequence:
    Criteria ownership and certification authority have collapsed into one actor.
    This is structural self-certification at the criteria layer.
    Even if the certifier acts in good faith, the structure is constitutionally void.
    There is no independence between criteria definition and criteria application.
```

---

## Section 5 — TC4-Q4: Certification Authority Abuse (Pure TC-4)

**Governing sub-question:** What constitutional failures arise from the authority component of certification, independent of the evidence or criteria?

### 5.1 False Certifier

```
Scenario:
  An actor claims certification authority without constitutional basis.
  All evidence is complete and authentic (TC-1 and TC-2 controlled).
  All criteria are correctly defined (TC-3 controlled).
  The certifier applies criteria correctly against true evidence.
  The certifier lacks constitutional authority to certify.

Constitutional consequence:
  The certification is constitutionally void
  regardless of the quality of the evidence or the accuracy of the application.

This is a pure TC-4 threat.
It is not captured by TC-1, TC-2, or TC-3.
  TC-1: Evidence is fine.
  TC-2: Evidence is fine.
  TC-3: Governance manipulation is not the source — the criteria are fine.
  TC-4: The authority itself is constitutionally invalid.

The audit record may show:
  Complete, authentic events.
  Correct criteria applied.
  A certification declaration.

What it cannot show:
  Whether the certifier was constitutionally authorized to declare.
```

### 5.2 Unauthorized Certifier — Not False, Just Not Authorized

```
This is distinct from False Certifier.

False Certifier: the actor does not hold any recognized role.
Unauthorized Certifier: the actor holds a recognized role
  but that role does not include certification authority.

Example:
  The election administrator is a recognized actor.
  The election administrator self-certifies the election.
  The election administrator has legitimate authority over election operations.
  The election administrator does not have constitutional authority to certify.

Constitutional consequence:
  The certification is void.
  The administrator had authority — just not THIS authority.

From NCQ-02 (36B): Certification requires structural independence.
The election administrator is structurally embedded in election operations.
Self-certification violates structural independence.
The certification is constitutionally void.
```

### 5.3 Self-Certification

```
Self-certification is the most dangerous pure TC-4 failure:
  The actor who conducted the election certifies the election.

This is constitutionally void by definition:
  The certifier cannot provide independence
  about the very processes they controlled.

  Auditing your own work is not auditing.
  Certifying your own election is not certification.

It may produce a formally complete document.
It cannot produce a constitutionally valid trustworthiness claim.

Self-certification may arise from:
  Explicit intent (actor consolidates control)
  Structural collapse (no independent actor designated)
  Authority vacuum (nobody else steps forward)
  Software architecture (no separation enforced)

All four produce the same constitutional consequence.
The last two are non-malicious — the constitutional consequence is identical.
```

### 5.4 Authority Substitution

```
Authority Substitution: the designated certifier is replaced
  during the certification process by a non-designated actor.

Scenarios:
  Designated certifier is compromised and replaced with a controlled substitute.
  Designated certifier withdraws and a substitute certifies without re-appointment.
  Designated certifier delegates authority without constitutional basis for delegation.

Constitutional consequence:
  The certification was performed by an actor
  whose authority was not established when the election was configured.
  The authority chain is broken.
  The certification is constitutionally suspect.
```

---

## Section 6 — TC4-Q5: Certification Independence Failure

**Governing sub-question:** What happens when the certifier lacks structural independence from the election it certifies?

### 6.1 Independence as a Constitutional Requirement

```
From 36B-05:
  NCQ-02: Certification Authority requires structural independence
    from operational aggregates.
    External, or internal-but-constitutionally-separated.

  Why: An actor that participated in the election's execution
    cannot provide an independent trustworthiness assessment.
    The assessment is corrupted by participation.

TC-4 investigates: What are the failure modes when this independence is absent?
```

### 6.2 Independence Failure Spectrum

```
Level 0 — Complete independence:
  Certifier has no operational relationship with the election.
  Independence is maximum.
  Certification claim is constitutionally strongest.

Level 1 — Administrative separation:
  Certifier is within the same organization but in a separate unit.
  Independence is partial.
  Conflicts of interest may exist.
  Certification claim requires demonstration that conflicts were managed.

Level 2 — Operational involvement:
  Certifier was involved in some operational aspect.
  Independence is compromised for those aspects.
  Certification may be valid for unrelated aspects only.

Level 3 — Governance authority:
  Certifier holds authority over GovernanceState or election configuration.
  Independence for governance claims is void.
  Certifier certifying their own governance decisions.

Level 4 — Self-certification:
  Certifier conducted the election.
  Independence is zero.
  Certification is constitutionally void for all claims.

This spectrum is a candidate analytical model, not yet a confirmed constitutional hierarchy.

What TC-4 has demonstrated:
  Independence matters constitutionally.
  Level 4 (self-certification) is constitutionally void.

What TC-4 has not yet demonstrated:
  That Levels 0-4 form a strict degradation law applicable across all constitutional models.
  Some constitutional frameworks may recognize Level 1 as sufficient for certain election types.
  Others may require Level 0 only.
  The constitutional ordering of Levels 1-3 requires further determination.

TC-4 observation: most real election systems operate at Level 1 or Level 2.
The gap between constitutional requirement and operational reality
is a structural independence risk — under any constitutional model.
```

### 6.3 The Independence-Evidence Trade-off Observation

```
An independent certifier may have:
  Less operational access → may evaluate against incomplete evidence
  More constitutional legitimacy → certification carries more constitutional weight

A non-independent certifier may have:
  More operational access → may evaluate against complete evidence
  Less constitutional legitimacy → certification carries less constitutional weight

This creates a constitutional tension, not a design decision.

TC-4 names the tension.
36E decides how to address it.
```

---

## Section 7 — TC4-Q6: TC3-NCQ-04 Applied — Who Certifies the Certifier?

**Governing sub-question:** Does certification terminate the authority chain, or does it depend upon a prior authority chain?

```
TC3-NCQ-04 established the recursion:
  Who authorizes the authority?
  Who validates the validator?
  Who certifies the certifier?

Applied specifically to Certification:

  The Certifier declares: "This election meets constitutional criteria."
  Who declares: "This certifier is constitutionally authorized to make that declaration"?

  If the answer is: "The Certifier was appointed by the election organizer" —
    then the organizer controls the certifier.
    Independence may be compromised.
    The authority chain traces back to the election itself.
    Self-certification by proxy.

  If the answer is: "The Certifier was appointed by an independent constitutional authority" —
    then who appointed the constitutional authority?
    The recursion continues.

  The recursion terminates only when:
    Authority is grounded in an external constitutional source
    (organizational charter, law, regulatory body)
    that is not itself part of the election.

This is the constitutional grounding requirement.
```

**TC-4 specific finding:**

```
Certification is the terminal act of the trustworthiness chain.

If certification depends on an authority chain
that is not grounded in a source external to the election,
then certification is not terminal — it is circular.

  Circular certification: a chain of authority that terminates within the election
    rather than in an external constitutional source.

  Terminal certification: authority whose constitutional grounding is
    external to and independent of the election.

The distinction between circular and terminal certification
is constitutionally significant.
Neither TC-1, TC-2, nor TC-3 captures it.
It is a pure TC-4 constitutional concern.

Circular certification is constitutionally suspect.
Whether it is always constitutionally equivalent to self-certification
remains an open constitutional question.

  Self-certification is a special case of circular certification.
  All self-certification is circular.
  Not all circular certification is direct self-certification.

  Example:
    Actor A certifies. Actor B appointed A. Actor C appointed B. Actor A appointed C.
    No actor self-certified directly.
    The chain is circular.
    The constitutional status of this arrangement is not yet determined.

The stronger claim — that circular certification is constitutionally equivalent
to self-certification — is moved into TC4-NCQ-03 as a constitutional question,
not frozen here as a finding.
```

---

## Section 8 — TC4-Q7: Relationship Analysis — Is TC-4 Independent of TC-3?

**Governing sub-question:** Is Certification Abuse a sub-category of Governance Manipulation, or an independent threat class?

### 8.1 Evidence for Sub-category (TC-4 ⊆ TC-3)

```
Both TC-3 and TC-4 concern:
  Authority
  Criteria
  Legitimacy

All certification failures involve some governance failure:
  Wrong certifier = governance failure at authority designation.
  Wrong criteria = governance failure at criteria specification.
  Independence failure = governance failure at structural separation.

Argument: TC-4 is a specialization of TC-3 where the governance failure
  is specifically located at the certification act.
```

### 8.2 Evidence for Independence (TC-4 ≠ TC-3)

```
TC-4 has properties not present in TC-3:

Property 1 — Certification as terminal act:
  TC-3 threats operate throughout the election.
  TC-4 specifically addresses the terminal claim of trustworthiness.
  A governance manipulation (TC-3) may be undetected until certification.
  Certification either reveals or conceals TC-3 failures.
  This terminal role is structurally distinct.

Property 2 — Good-faith certification of false evidence:
  TC-4 introduces scenarios where a legitimate certifier
  correctly applies correct criteria
  against fabricated or incomplete evidence.
  The certifier has committed no TC-3 governance manipulation.
  The certification is still constitutionally void.
  TC-3 cannot capture this: the governance was not manipulated.

Property 3 — The certification terminal question:
  "Who certifies the certifier?" (TC3-NCQ-04 applied to TC-4)
  is specifically about the terminal authority.
  TC-3 asks about governance authorities generally.
  TC-4 asks specifically: does the certifier terminate or continue the chain?
  This is a structurally distinct question.

Property 4 — Independence as constitutional concept:
  TC-3 governance manipulation can occur without any independence failure.
  Independence failure is specifically a certification concept.
  A non-independent certifier may apply criteria correctly
  and still produce a constitutionally void certification.
  The independence failure is the threat — not the criteria application.
```

### 8.3 Relationship Finding

```
TC-4 (Certification Abuse) is PARTIALLY overlapping with TC-3 (Governance Manipulation).

They are not identical:
  TC-4 has independent threat scenarios (Properties 1, 2, 3, 4 above).
  TC-3 has scenarios that do not involve certification.

They are not entirely separate:
  Some TC-4 failures are also TC-3 failures (wrong criteria, wrong authority designation).

The correct relationship:
  TC-3 and TC-4 intersect but neither contains the other.

  TC-4's independent contribution to the threat model:
    - Terminal certification of false/incomplete evidence by legitimate certifier
    - Independence failure as independent constitutional failure
    - Circular vs terminal certification authority grounding
    - Certification concealing or revealing prior threat class failures

The five-threat-class ordering in 36C-01 is constitutionally defensible.
TC-4 is not redundant with TC-3.
```

---

## Section 9 — The Compound Threat Scenarios

**The most dangerous TC-4 scenarios compound prior threat classes:**

| Scenario | TC Classes Involved | Detection difficulty | Constitutional consequence |
|----------|---------------------|---------------------|---------------------------|
| Good-faith certifier certifies fabricated record | TC-2 + TC-4 | VERY HIGH — certifier has no reference | Void certification; certifier cannot detect without independent evidence source |
| Good-faith certifier certifies incomplete record (Gap A-3) | TC-1 + TC-4 | HIGH — certifier may not know what is missing | Certification claims completeness that cannot be verified |
| Certifier applies retroactive criteria to authentic evidence | TC-3 + TC-4 | MEDIUM — criteria timeline is detectable in principle | Void certification; correct process applied to wrong temporal criteria |
| Non-independent certifier applies correct criteria to authentic, complete evidence | Pure TC-4 | MEDIUM — independence failure may be visible | Void certification even with perfect evidence and criteria |
| Self-certification | Pure TC-4 | LOW — structurally apparent | Void; maximum authority concentration; TC-5 precursor |
| Circular certification (chain terminates within election) | TC-4 + TC-5 (preview) | VERY HIGH — requires tracing authority chain | Election certifies itself through authority chain; independence appears but is not present |

---

## Section 10 — TC4 Candidate Domain Insights

**TC4-DI-01 — CANDIDATE DOMAIN INSIGHT: Certification validity is independent of certifier legitimacy**

```
A constitutionally legitimate certifier
correctly applying constitutionally correct criteria
against constitutionally false evidence
produces a constitutionally void certification.

Certifier legitimacy does not guarantee certification validity.
Evidence quality is an independent prerequisite.

Stated symmetrically:
  Evidence quality (TC-1, TC-2) is a prerequisite for certification validity.
  It is not sufficient for certification validity.
  Certifier legitimacy is also required.
  Neither alone is sufficient.

Status: CANDIDATE — requires corroboration from TC-5 and 36D.
```

**TC4-DI-02 — CANDIDATE DOMAIN INSIGHT: Independence is a constitutional property, not a procedural one**

```
An actor who conducts an election and then certifies it
has not performed certification in the constitutional sense.

The act of certification requires structural independence
from the processes being certified.

Independence is not satisfied by:
  A formal declaration of independence.
  A procedural separation of roles within the same authority.
  A time delay between operation and certification.

TC-4 demonstrates that some form of structural independence
appears necessary for constitutionally valid certification.

The minimum constitutional form of that independence remains unresolved.
  Whether external-only is required, or whether internal-but-constitutionally-separated
  suffices, remains an open question (NCQ-02 / TC4-NCQ-02).

What has been demonstrated:
  The complete absence of structural independence
  makes certification constitutionally void regardless of process quality.

What has not been demonstrated:
  The precise threshold where independence becomes constitutionally sufficient.

Status: CANDIDATE — NCQ-02 and TC4-NCQ-02 must be resolved before this can be confirmed.
```

**TC4-DI-03 — CANDIDATE DOMAIN INSIGHT: Certification is the terminal claim — its failure reveals or amplifies all prior threat classes**

```
Certification is the final act that declares constitutional trustworthiness.

If TC-1 was successful (evidence suppressed):
  Certifier either detects and rejects, or certifies void evidence.
  Certification either terminates the suppression's effectiveness
  or amplifies it into the trustworthiness claim.

If TC-2 was successful (evidence fabricated):
  Same: certifier either detects and rejects, or certifies false evidence.

If TC-3 was successful (governance manipulated):
  Certifier either rejects manipulated governance or certifies it.

TC-4 is therefore the system's last potential check on TC-1, TC-2, TC-3.
AND TC-4 failures amplify all prior threat class successes into the public trustworthiness claim.

A successful TC-4 bypass converts a hidden threat into a declared trustworthiness claim.

Status: CANDIDATE — this finding about the terminal role of certification
  is consistent with the program's developing framework.
```

---

## Section 11 — New Constitutional Questions

**TC4-NCQ-01: Does a constitutionally void certification transfer constitutional harm to the declared outcome?**

```
If an election is certified void (false certifier, false evidence, wrong criteria):
  Does the certification's void status transfer to the election outcome?
  Is the declared outcome constitutionally nullified by void certification?
  Or does the outcome stand independently of the certification?

This determines whether certification is constitutive of legitimate outcome
or merely attestative.

TC4-NCQ-01 requires ARB constitutional determination.
```

**TC4-NCQ-02: What is the minimum constitutional independence requirement for a valid certification?**

```
NCQ-02 (from 36B) asked: external or internal-but-constitutionally-separated?

TC4-NCQ-02 narrows this to a threshold question:
  What is the minimum structural independence that produces constitutionally valid certification?

  Is Level 1 (administrative separation) sufficient?
  Is external-only the constitutional floor?
  Can the constitutional floor be organization-defined?

TC4-NCQ-02 requires ARB constitutional determination.
  (Companion to NCQ-02 from 36B — NCQ-02 establishes the requirement;
   TC4-NCQ-02 establishes the threshold.)
```

**TC4-NCQ-03: Does the authority chain for certification require external grounding?**

```
TC3-NCQ-04 asked: who governs the governors?
TC4-Q7 applied this specifically to certification.

The constitutional question:
  Does the certifier's authority trace to a source
  external to and independent of the election?

  If YES (required):
    All certification within the election system is constitutionally void.
    External constitutional grounding is a requirement.

  If NO (not required):
    Internal-but-constitutionally-separated authority can terminate the chain.
    The chain does not need external grounding.

TC4-NCQ-03 requires ARB constitutional determination.
  (This question directly shapes 36E and 37's ADR scope.)
```

---

## Section 12 — Threat Findings Summary

| Finding | Type | Priority |
|---------|------|----------|
| **TF-36C-05-01 (CANDIDATE PROGRAM-LEVEL): A legitimate certifier certifying false evidence in good faith produces a constitutionally void certification — certifier legitimacy ≠ certification validity; separates Authority from Truth** | Constitutional | CRITICAL |
| TF-36C-05-02: Gap A-3 (completeness specification absent) prevents certification from making complete-record claims regardless of certifier quality | Constitutional | CRITICAL |
| TF-36C-05-03: Certification against wrong-epoch criteria is constitutionally void even with complete, authentic evidence | Constitutional | CRITICAL |
| TF-36C-05-04: Self-certification is constitutionally void — independence is zero; special case of circular certification | Constitutional | CRITICAL |
| TF-36C-05-05: Unauthorized certifier (legitimate role, wrong authority scope) produces void certification despite correct process | Constitutional | CRITICAL |
| TF-36C-05-06: Circular certification — authority chain terminates within election — is constitutionally suspect; equivalence to self-certification is open (TC4-NCQ-03) | Constitutional | CRITICAL |
| TF-36C-05-07: Non-independent certifier applying correct criteria to correct evidence produces void certification — independence failure alone is sufficient | Constitutional | CRITICAL |
| TF-36C-05-08: Good-faith certifier cannot detect fabricated record without independent reference (TC-2 × TC-4 structural gap) | Constitutional | CRITICAL |
| TF-36C-05-09: Certifier with criteria interpretation authority has implicitly acquired criteria ownership — TC-4 × TC-3 authority collapse | Constitutional | HIGH |
| TF-36C-05-10: TC-4 appears to contain independent threat scenarios; some may prove to be amplification paths for TC-1/TC-2/TC-3 — requires confirmation in TC-5 and 36D | Constitutional | HIGH |
| TF-36C-05-11: Certification is the terminal act — TC-4 failure amplifies all prior threat class successes into public trustworthiness claims | Constitutional | CRITICAL |
| TF-36C-05-12: TC4-NCQ-01 — void certification's effect on declared outcome is constitutionally undefined | Constitutional | HIGH |
| TF-36C-05-13: TC4-NCQ-02 — minimum independence threshold for valid certification is undefined | Constitutional | CRITICAL |
| TF-36C-05-14: TC4-NCQ-03 — whether circular certification is constitutionally equivalent to self-certification; whether external grounding is required | Constitutional | CRITICAL |
| TF-36C-05-15: Many TC-4 failures become possible only when authority is concentrated — TC-5 must evaluate whether Trust Concentration is a root cause of TC-4 failures or an independent threat class | Constitutional | HIGH |

**Candidate Domain Insights:**

```
TC4-DI-01 (CANDIDATE PROGRAM-LEVEL): Certifier legitimacy does not guarantee certification validity
  — evidence quality is an independent prerequisite; separates Authority from Truth.
  Elevate for validation in 36C-06, 36D, 36E.

TC4-DI-02 (CANDIDATE): Independence is a constitutional property, not a procedural one
  — formal independence ≠ structural independence; threshold form unresolved (NCQ-02, TC4-NCQ-02).

TC4-DI-03 (CANDIDATE): Certification is the terminal claim — TC-4 failure amplifies or terminates
  all prior threat class successes.
```

**Architectural Impact Candidates (for 36E):**

```
AIC-36C-05-01: Certification independence enforcement —
  how is structural independence of the certifier established and verified?
  (TC4-NCQ-02 + NCQ-02 inputs for 36E)

AIC-36C-05-02: Authority chain grounding —
  design implications if external authority grounding is constitutionally required.
  (TC4-NCQ-03 input for 36E and 37)

AIC-36C-05-03: Independent evidence reference for certifier —
  what mechanism (if any) allows a certifier to validate evidence completeness
  and authenticity without becoming operationally involved?
  (TC-2 × TC-4 structural gap / AIC for 36E)

AIC-36C-05-04: Certification scope limitation —
  certification may only be constitutionally valid within the scope
  of the certifier's independence.
  A partial independence model may produce partial certification.
  (Independence spectrum finding / AIC for 36E)
```

---

## ARB Decision

```
Round 36C-05 — Certification Abuse Threat Model

APPROVED

Research Discipline:        VERY HIGH
Threat Modeling Quality:    VERY HIGH
Governance Discipline:      VERY HIGH
DDD Discipline:             HIGH
Architecture Neutrality:    HIGH
Confidence:                 HIGH

Observations applied:

  OBS-36C-05-1: Independence spectrum demoted to candidate analytical model.
    Level 4 (self-certification) confirmed void.
    Levels 0-3 hierarchy not yet a confirmed constitutional law.
    Some constitutional frameworks may treat Level 1 as sufficient.

  OBS-36C-05-2: Circular certification wording softened.
    "Circular = self-certification" is constitutionally suspect but not proven equivalent.
    Self-certification IS a special case of circular. Not vice versa.
    Stronger claim moved into TC4-NCQ-03 as an open constitutional question.

  OBS-36C-05-3: TC4-DI-02 architecture drift corrected.
    "Independence requires constitutional separation" replaced with:
    "Some form of structural independence appears necessary."
    Minimum threshold form remains unresolved (NCQ-02 / TC4-NCQ-02).

  OBS-36C-05-4: TF-36C-05-01 elevated to CANDIDATE PROGRAM-LEVEL INSIGHT.
    "Certifier legitimacy ≠ certification validity" separates Authority from Truth.
    This is among the deepest trustworthiness discoveries in the program.
    Before 36C-05: valid certifier + valid process = valid certification (implicit assumption).
    After 36C-05: valid certifier + valid process + false evidence = void certification.
    Carry for validation in 36C-06, 36D, 36E.

  OBS-36C-05-5: TC-5 bridge added.
    Many TC-4 failures become possible only when authority is concentrated.
    TC-5 must evaluate: is Trust Concentration a root cause of TC-4 failures
    or an independent threat class?

Central findings:
  TF-36C-05-01 (CANDIDATE PROGRAM-LEVEL): Certifier legitimacy ≠ certification validity.
  TF-36C-05-06: Circular certification constitutionally suspect (equivalence to self-cert open).
  TF-36C-05-10: TC-4 appears to have independent threat scenarios; confirmation in TC-5/36D.
  TF-36C-05-11: Certification is the terminal act — TC-4 failure amplifies all prior threat classes.
  TF-36C-05-15: TC-4 failures may require trust concentration — bridge to TC-5.

  TC4-DI-01 (CANDIDATE PROGRAM-LEVEL) / TC4-DI-02 / TC4-DI-03: Three candidate insights.
  TC4-NCQ-01/02/03: Three new constitutional questions.

Governing Rule Compliance:
  No ElectionGuard, Helios, cryptographic mechanisms, digital signatures.
  No bounded contexts, aggregates, or services created.
  No architecture decisions made.
  Threats discovered and classified only.

Round 36C-05: APPROVED

Governing Instructions for Round 36C-06 (ARB binding):

  TC-5 investigates Trust Concentration.

  Governing question:
    Can trustworthiness fail because too much constitutional power
    accumulates in too few actors, even when evidence, governance,
    and certification appear valid?

  Focus on:
    D43 (enrollment authority concentration)
    NCQ-02 (certification authority independence — concentration risk)
    TC3-NCQ-04 (who governs the governors — concentration at grounding point)
    TC4-DI-01 question: is trust concentration a root cause of TC-4 failures?
    The relationship between authority vacuum (TC3-DI-02) and authority concentration.

  Carry:
    All candidate program-level findings (TF-36C-04-14, TF-36C-05-01 candidate).
    TC4-NCQ-03 (circular certification / authority grounding).
    The three-dimension framework (Completeness, Authenticity, Candidate Dimension 3).

  Do NOT propose mechanisms or design.
  Discover threats only.

Round 36C-06 (Trust Concentration): AUTHORIZED
```
