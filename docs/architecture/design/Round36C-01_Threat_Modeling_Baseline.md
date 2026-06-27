# Round 36C-01 — Threat Modeling Baseline

**Date:** 2026-06-13

**Phase:** Round 36C — Threat Modeling Research

**Sub-document:** 36C-01 (Baseline)

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 36A (Verifiability Research) — CLOSED
- Round 36B (Auditability Research) — CLOSED (Closure Summary approved)
- 36B-CFI-01 — CONFIRMED: Auditability, Verifiability, Certification are separable constitutional capabilities
- Ownership matrix (36B-05) — FROZEN
- OBS-36B-CLOSURE-1 — BINDING: governance threats precede cryptographic threats
- Round 36 governance filter (binding from Round 35): "Does this strengthen the discovered model, or does it attempt to replace it?"

**Purpose:**

```
36C does not design defenses.
36C does not select cryptographic mechanisms.
36C does not create bounded contexts.

36C asks one question:

  What must happen
  for a dishonest election
  to appear trustworthy?

This question exposes governance weaknesses much faster than
starting with cryptographic attack scenarios.

The threat model maps against:
  - the discovered domain model (Rounds 17–35)
  - the ownership findings (36B-05)
  - the confirmed gaps (Gap A-3, A-2, A-4, D39, D43)

The threat model does NOT design solutions.
Solutions belong to 36E and 37.
```

**Constraint (ARB binding):**

```
Do NOT introduce:
  ElectionGuard architecture
  Verifier Context
  Guardian Context
  Bulletin Board Context
  Proof Engine Context

Those belong to 36E (Architecture Impact Assessment).

The gate is:
  36C → 36D → 36E → 37

Threat modeling produces threat findings.
Architecture Impact Assessment translates findings into impact candidates.
ADR authoring translates impact candidates into decisions.

Do not shortcut the gate.
```

---

## Section 1 — Threat Modeling in a DDD Context

Traditional threat modeling (STRIDE, PASTA, DREAD) was designed for software systems with clear technical boundaries. NRNA's trustworthiness research requires a domain-first threat model — one that maps threats against domain concepts, ownership boundaries, and constitutional capabilities before mapping them against technical components.

**DDD Threat Model format:**

For each threat, the analysis asks:

| Question | Purpose |
|----------|---------|
| Who performs this threat? | Actor identification — internal, external, or systemic |
| Against which domain concept? | Maps to discovered aggregates and ownership |
| How is the threat executed? | Domain-level mechanism, not technical implementation |
| What is the constitutional consequence? | Auditability / Verifiability / Certification impact |
| Does the current model detect it? | Gap identification against the discovered model |
| What does this expose for 36E? | Architectural impact candidate or constitutional question |

**The governing question throughout 36C:**

```
What must happen
for a dishonest election
to appear trustworthy?
```

This is the red-team lens. The adversary's goal is not to disrupt — disruption is visible. The adversary's goal is to make a fraudulent outcome look legitimate. That is the harder problem and the one that trustworthiness research must model.

**Methodological guardrail (OBS-36C-01-D, binding):**

```
A threat finding does not imply a required architecture change.

The progression is:
  Threat finding (36C)
      ↓
  Architectural impact candidate (36E)
      ↓
  ADR decision (37)

Discovering that "Gap A-3 enables Evidence Suppression" is a threat finding.
It is NOT a decision to create a completeness context.
It is NOT a decision to add an event registry aggregate.
It is an input to 36E's architecture impact assessment.

This guardrail prevents threat findings from bypassing the governance gate.
```

---

## Section 2 — The Five Threat Classes

Threat classes are ordered by the ARB sequence (binding from 36B-04 authorization and 36B Closure):

| Class | Name | Primary Gap Attacked | Domain Owner Threatened |
|-------|------|---------------------|------------------------|
| TC-1 | Evidence Suppression | Gap A-3 (Completeness) | Audit context / undiscovered completeness owner |
| TC-2 | Evidence Fabrication | P-2 (Tamper-evidence) | Audit context / evidence production chain |
| TC-3 | Governance Manipulation | NCQ-03 (Criteria) + NCQ-01 | GovernanceState / criteria owner |
| TC-4 | Certification Abuse | NCQ-01 + NCQ-02 (Authority) | External authority / criteria chain |
| TC-5 | Trust Concentration | D43 + distributed ownership | All aggregates — single-actor control |

**Why this order:**

```
TC-1 precedes TC-2 because:
  Missing evidence is harder to detect than modified evidence.
  Hashes, signatures, and proofs protect evidence that exists.
  They cannot protect evidence that was never emitted.
  The harder attack comes first.

TC-3 precedes TC-4 because:
  Criteria manipulation is a prerequisite for Certification abuse.
  If criteria can be changed, Certification may be achievable against
  a fraudulent election by redefining what "valid" means.

TC-5 is last because:
  It is the root cause beneath all other threat classes.
  If one actor controls everything, all other threat classes
  collapse into one: the actor decides the outcome.
  TC-5 naturally opens 36D (Trust Distribution).
```

---

## Section 3 — Threat Class 1: Evidence Suppression (Gap A-3)

**The fundamental question:**

```
What if the evidence never arrives?
```

**Why this is the highest-priority threat:**

The Audit context is a passive observer. It records what it receives. It cannot assert what it should have received. This is Gap A-3 (Completeness), confirmed as the primary auditability risk in 36B-03 and elevated as the most serious known risk in the program by the ARB at 36B Closure.

Evidence suppression exploits this property: if events are never emitted, the Audit context produces a record that appears coherent but is incomplete. Downstream audit, verification, and certification processes operate against the incomplete record without detecting the gap.

**Threat scenarios:**

```
Scenario S1-A: Vote events never emitted
  VoteRecorded is never fired (or selectively suppressed for specific voters).
  The Audit context receives no evidence of those votes.
  The tally counts votes that were cast but the audit record shows fewer.
  The audit appears clean — not because the election was clean,
  but because the suppressed votes left no evidence.

Scenario S1-B: Governance events never emitted
  GovernanceTransitionCompleted is never emitted for a specific transition.
  GovernanceState changes, but the Audit context has no record.
  The governance replay (if ever available) is missing a transition.
  An auditor inspecting the governance timeline sees a gap
  they cannot distinguish from "nothing happened" vs. "something was hidden."

Scenario S1-C: Selective Certification evidence never emitted
  Evidence that would be submitted to a Certification Authority
  is suppressed before publication.
  The certifier receives an incomplete evidence package.
  Certification proceeds against incomplete evidence.
  The certified election was not certified against all evidence.
```

**What the current model does:**

```
  VoteRecorded is fired by the Vote aggregate.
  GovernanceTransitionCompleted is fired by GovernanceState.
  The Audit context receives these as fire-and-forget observations.

  Detection capability:
    If VoteRecorded is suppressed: UNDETECTABLE by current model.
    If GovernanceTransitionCompleted is suppressed: UNDETECTABLE by current model.
    If evidence package is incomplete: PARTIALLY DETECTABLE if Gap A-3 is resolved.

  Gap A-3 is the enabler of this entire threat class.
  Without a completeness mechanism, evidence suppression is undetectable.
```

**Domain gaps exposed:**

- Gap A-3 (Completeness) — the direct gap
- No expected-event registry in any discovered aggregate
- No completeness invariant that can assert "all events that should have fired, fired"
- No cross-aggregate verification that the audit record matches the operational record

**Impact on 36E:**

```
AIC-36B-01 (Completeness Mechanism) is elevated to CRITICAL priority.

The completeness mechanism appears necessary for strong trustworthiness claims
about NRNA's audit record.

Round 36C will investigate whether any alternative constitutional assurance
model exists — one that provides trustworthiness without a formal
completeness mechanism. That investigation may refine this assessment.

Without a completeness mechanism or an alternative assurance model:
the audit record is a best-effort observation, not a complete record.
```

---

## Section 4 — Threat Class 2: Evidence Fabrication

**The fundamental question:**

```
Can false evidence be injected?
```

Evidence fabrication is the complement of evidence suppression: instead of removing real evidence, the adversary inserts false evidence. A fabricated audit record makes a fraudulent election appear auditable.

**Why this is second:**

Evidence fabrication requires evidence to exist. Suppression removes the presence of evidence entirely. Fabrication attacks the integrity of evidence that is present. Suppression is therefore the prior problem — you cannot fabricate against nothing.

**Threat scenarios:**

```
Scenario S2-A: Fabricated VoteRecorded events
  The audit record contains VoteRecorded events for votes that were never cast.
  This inflates the apparent vote count.
  Without a commitment scheme or receipt mechanism tied to the voter's act of casting,
  the difference between "recorded" and "fabricated" is undetectable.

Scenario S2-B: Fabricated GovernanceTransitionCompleted events
  Governance events are inserted into the audit record that did not correspond
  to real governance transitions.
  Auditability appears complete — the record has events — but the events are false.

Scenario S2-C: Replay forgery
  A governance replay is constructed from fabricated events.
  The replay "succeeds" (produces the expected outcome) but the
  events it replays are not the events that actually occurred.
  GovernanceReplayService (when available) would validate a forged history.
```

**What the current model does:**

```
P-2 (Tamper-evidence) from the F2 infrastructure layer (36B-03) provides
integrity protection for evidence that is recorded. If an event is recorded
and then modified, tamper-evidence detects the modification.

But:
  Tamper-evidence cannot detect insertion of a fabricated event
  that was never recorded in the first place.

  The question is not "was this event modified?"
  The question is "was this event authentic?"

  Authenticity requires commitment at the time of the event —
  a mechanism that ties the domain event to the fact of the act.
  This is where receipt mechanisms and domain event signing become relevant
  (but those are 36E/37 territory — not design decisions in 36C).

  Detection capability:
    If an event is modified after recording: DETECTABLE (P-2).
    If a fabricated event is inserted: CURRENTLY UNDETECTABLE
      without an event authentication mechanism at the source.
```

**Domain gaps exposed:**

- No event authentication mechanism discovered (vote act → VoteRecorded binding)
- The commitment point between "vote was cast" and "VoteRecorded was emitted" is undiscovered
- GovernanceState produces GovernanceTransitionCompleted but no authentication binds the command to the event

**Relationship to VO-1:**

```
VO-1 (voter identity must not appear in vote events or audit records)
is compatible with event authentication.

Authentication can be non-identifying:
  "this event was produced by the Vote aggregate" (aggregate identity)
  not "this event was produced by voter X" (voter identity)

VO-1 constrains what authenticates, not whether authentication exists.
```

**Impact on 36E:**

```
Candidate: Event authentication mechanism at domain event production points.
  Not a design decision — a candidate for AIC in 36E.
  The question for 36E is: which aggregates need commitment schemes
  and what is the appropriate binding mechanism?
```

---

## Section 5 — Threat Class 3: Governance Manipulation

**The fundamental question:**

```
Who can change criteria?
Who can change authority?
Who can redefine what a valid election means?
```

Governance manipulation is the most constitutionally dangerous threat class because it operates above the technical layer. A manipulated election that satisfies all technical requirements is not detectable by technical means if the criteria themselves have been changed.

**Why this is third:**

Governance manipulation requires that evidence exists (TC-1) and is trusted (TC-2). If an adversary has control over criteria, they can design an election to satisfy those criteria while producing a fraudulent outcome. This is meaningless if the evidence record is empty (TC-1 succeeded) or forged (TC-2 succeeded). Governance manipulation is the threat where everything technical works correctly — but the constitutional framework has been corrupted.

**Threat scenarios:**

```
Scenario S3-A: Post-hoc criteria modification
  The governance configuration (held by GovernanceState) is modified
  after the election begins but before the audit concludes.
  Auditors evaluate the election against the modified criteria.
  The election "passes" criteria it would have failed under the original criteria.

  This directly attacks 36A-DI-05 (Governance Configuration Freeze).
  GovernanceState's configuration freeze invariant is the defense.
  The threat is: can the freeze invariant be bypassed?

Scenario S3-B: Criteria redefinition before the election
  Criteria are legitimately set before the election (satisfying P-4 and 36A-DI-05).
  But the criteria are intentionally designed to allow a fraudulent outcome.
  The election satisfies all criteria while producing a controlled result.

  This is not a technical failure — it is a constitutional failure.
  NCQ-03 is directly relevant: who defines what a valid election means?
  If that definition can be captured by an adversary, all technical guarantees
  are void.

Scenario S3-C: Authority substitution
  The Certification Authority is replaced or pressured.
  The new or pressured authority certifies an election that should have been rejected.

  This directly implicates NCQ-02 (who holds Authority?).
  An external authority that is not structurally independent is vulnerable to capture.

Scenario S3-D: Criteria ambiguity
  The criteria are not manipulated. They are simply vague.

  Different parties — auditors, operators, courts, candidates — legitimately
  interpret the criteria differently.

  The resulting dispute produces multiple competing claims of legitimacy:
    Party A: "The election meets criteria under our interpretation."
    Party B: "The election fails criteria under our interpretation."

  No technical mechanism can resolve this dispute.
  It is a constitutional failure — the criteria were not precise enough
  to produce a determinate outcome.

  This is historically one of the most common real-world election failures.
  Not corruption. Ambiguity.

  Connection to NCQ-03: who defines constitutional criteria and with what precision?
  Connection to P-4: pre-audit criterion definition must be sufficiently precise
    to produce unambiguous evaluation.
```

**What the current model does:**

```
GovernanceState owns the configuration freeze invariant (36A-DI-05 confirmed).
The freeze prevents modification during the active voting period.

But:
  The freeze window is time-bounded (before voting opens to after voting closes).
  It does not prevent criteria design manipulation before the window.
  It does not prevent Authority substitution.
  It does not detect criteria capture.

NCQ-01 through NCQ-04 are the constitutional gaps here.
The technical model cannot address governance manipulation
until the constitutional questions are answered.
```

**Domain gaps exposed:**

- NCQ-03 (who defines constitutional criteria?) is the primary gap
- NCQ-02 (who holds Authority?) is the secondary gap
- The current model has no mechanism to detect criteria capture
- The freeze invariant protects against during-election modification but not against pre-election manipulation

**Connection to 36D:**

```
Governance manipulation is enabled by concentration of authority.
If one actor can define criteria AND hold Authority AND operate the election,
all three elements of Certification can be captured simultaneously.

TC-5 (Trust Concentration) explores this directly.
36D (Trust Distribution) is the natural successor.
```

---

## Section 6 — Threat Class 4: Certification Abuse

**The fundamental question:**

```
Can something be certified
without being properly verified?
```

This is one of the most common failure modes in real electoral systems. 36B-CFI-01 confirmed that Certification ≠ Verification. The threat is that this separation is exploited: something is declared certified (Authority declares criteria satisfied) but the evidence was never properly verified.

**Why this is fourth:**

Certification abuse presupposes that the evidence record exists (TC-1 defense failed), the evidence appears trustworthy (TC-2 defense failed or not exploited), and the criteria are accepted (TC-3 defense failed or criteria are compliant). In an environment where TC-1, TC-2, and TC-3 are all controlled, Certification abuse is the final act — the declaration that seals the fraudulent outcome.

**Threat scenarios:**

```
Scenario S4-A: Certification without evidence review
  The Certification Authority declares the election valid
  without actually reviewing the audit evidence.
  The authority had access but did not exercise it.
  The declaration is procedurally valid (authority made it)
  but constitutionally empty (evidence was not evaluated).

  This attacks P-5 (certifier independence) from an operational angle.
  Technical independence is present; operational diligence is absent.

Scenario S4-B: Certification against incomplete evidence (Gap A-3 intersection)
  The evidence record is incomplete (TC-1 partially succeeded).
  The Certification Authority certifies against the available evidence
  without asserting completeness.
  The certification is formally valid but substantively unsound.

  This is the intersection of TC-1 and TC-4.
  It is why Gap A-3 resolution is a prerequisite for defensible certification.

Scenario S4-C: Certification criteria mismatch
  The criteria used for Certification do not match
  the criteria defined before the election.
  The authority applies different criteria than were published.

  This is the intersection of TC-3 (criteria manipulation) and TC-4.

Scenario S4-D: "Certified but not verified" acceptance
  A system that can achieve Certification (Evidence + Criteria + Authority)
  without prior Verifiability (proven correctness of claims).

  36B-CFI-01 showed that a system can be Certifiable without being Verifiable.
  This is the separation — but the separation can be exploited:
    Authority declares: "the evidence is complete and criteria are satisfied."
    The tally was never independently verified.
    The election is certified but unverified.

  This is constitutionally dangerous if NRNA requires both (NCQ-01).
```

**What the current model does:**

```
The current discovered model has no Certification mechanism.
There is no Authority element in any discovered aggregate.
There is no Criteria publication mechanism.
There is no Certification Submission or Certification Record domain concept.

Therefore:
  Certification abuse is currently IMPOSSIBLE in the technical sense —
  there is nothing to abuse.

  But:
  When Certification is eventually designed (after 36E/37),
  these threat scenarios must be addressed in the design.

  The threat class is forward-looking: it identifies what the
  Certification design MUST resist when it is built.
```

**Domain gaps exposed:**

- NCQ-01 (is Certification required?) must be answered to scope Certification design
- NCQ-02 (who holds Authority?) determines Authority independence risk
- No Certification evidence completeness assertion mechanism (Gap A-3 again)
- No published criteria record in the discovered model

---

## Section 7 — Threat Class 5: Trust Concentration

**The fundamental question:**

```
Can one person decide?
Can one aggregate decide?
Can one context decide?
Can one organization decide?
```

Trust concentration is the root threat beneath all other threat classes. If one actor controls the evidence record, the criteria, the authority, and the operational system simultaneously, all other defenses are void. The actor can suppress evidence (TC-1), fabricate evidence (TC-2), manipulate criteria (TC-3), and certify the outcome (TC-4) without any external check.

**Why this is fifth:**

It is the root cause. The previous four threat classes each describe a specific capability that, when concentrated, enables the corresponding fraud. TC-5 describes the structural condition under which all four become simultaneously possible. It is the final and most fundamental threat class.

**Threat scenarios:**

```
Scenario S5-A: Single aggregate controls voting and auditing
  The Vote aggregate and the Audit context are controlled by the same actor.
  The actor can suppress VoteRecorded (TC-1) and prevent detection.
  No independent audit is possible if the auditor is the same actor.

Scenario S5-B: Single actor holds Authority and operates the election
  Certification Authority is held by an actor who also operates GovernanceState.
  The actor can define criteria (TC-3), certify the outcome (TC-4),
  and suppress contradictory evidence (TC-1).
  P-5 (certifier independence) is violated.

Scenario S5-C: Single organization controls all distributed roles
  All ownership roles (evidence production, criteria definition, authority declaration)
  are nominally separate but held by organizations under common control.
  Structural independence is present; operational independence is absent.
  This is the hardest form of trust concentration to detect.

Scenario S5-D: Single context holds all constitutional capabilities
  One bounded context simultaneously provides:
    Auditability (evidence record)
    Verifiability (claim verification)
    Certification (declaration authority)
  The three capabilities that 36B-CFI-01 confirmed as separable
  are collapsed into one structure.
  The separation that 36B established as a constitutional principle
  is violated at the ownership layer.
```

**What the current model does:**

```
The discovered model distributes ownership:
  Vote: individual ballot and receipt
  GovernanceState: governance transitions and freeze
  Audit context: passive observation
  Verification Representation Context: verification surface
  External authority: Certification (undiscovered)

This distribution is not by design — it emerged from discovery.
But it is constitutional evidence: the domain itself distributes ownership.

The threat is: this distribution is not architecturally enforced.
Nothing currently prevents a single context from absorbing multiple roles.

Connection to 36D:
  36D (Trust Distribution) will analyze whether the discovered
  distribution is sufficient, appropriate, and structurally enforced.
  36C (TC-5) identifies the risk. 36D analyzes the response.
```

**Domain gaps exposed:**

- No enforced separation of Auditability, Verifiability, and Certification ownership
- No structural constraint preventing role consolidation
- NCQ-02 (Certification Authority independence) directly relevant

**D43 as an explicit trust concentration risk (OBS-36C-01-C):**

```
D43 is currently the strongest discovered ownership uncertainty in the model.

An unresolved authority owner is inherently a trust concentration risk.

When it is unknown who holds a specific authority,
there is no structural enforcement preventing that authority
from being accumulated alongside other authorities.

Enrollment authority (D43) + Voting authority (GovernanceState/Vote) +
Audit authority (Audit context) held by the same actor
would constitute severe trust concentration across all constitutional capabilities.

D43 must be carried into 36C-06 (Trust Concentration) as a primary subject.
```

---

## Section 8 — Threat Mapping to Ownership Matrix

The 36B-05 ownership matrix, mapped against the five threat classes:

| Domain Element | TC-1 Suppression | TC-2 Fabrication | TC-3 Governance | TC-4 Cert Abuse | TC-5 Concentration |
|---------------|-----------------|-----------------|-----------------|-----------------|-------------------|
| Vote aggregate | HIGH — VoteRecorded suppression | HIGH — fabricated votes | LOW | LOW | MEDIUM |
| GovernanceState | HIGH — transition suppression | HIGH — fabricated transitions | **CRITICAL** — criteria manipulation | HIGH — freeze bypass | HIGH |
| Audit context | **CRITICAL** — Gap A-3 | HIGH — fabricated record | LOW | MEDIUM | HIGH |
| Verification Representation | MEDIUM | MEDIUM | LOW | HIGH | MEDIUM |
| Completeness (undiscovered) | **CRITICAL** — this is the gap | N/A | LOW | HIGH | HIGH |
| Criteria (undiscovered) | LOW | LOW | **CRITICAL** | **CRITICAL** | **CRITICAL** |
| Authority (external) | LOW | LOW | HIGH | **CRITICAL** | **CRITICAL** |

**Reading the matrix:**

```
CRITICAL entries identify where the threat class has no current defense
in the discovered model and the gap has no current owner.

HIGH entries identify where a discovered aggregate is at risk
but has partial mitigation through current invariants.

The most dangerous intersection:
  Completeness (Gap A-3) × TC-1 Suppression = CRITICAL
  Criteria (undiscovered) × TC-3 Governance Manipulation = CRITICAL
  Authority (external) × TC-4 Certification Abuse = CRITICAL

These three CRITICAL × CRITICAL intersections are the primary
subjects for the remaining 36C sub-documents.
```

---

## Section 9 — What 36C Does NOT Do

Explicit exclusions (binding):

```
36C does NOT design defenses.
36C does NOT select cryptographic mechanisms.
36C does NOT create bounded contexts.
36C does NOT decide which threats are "acceptable."
36C does NOT resolve NCQ-01 through NCQ-04 (those require ARB constitutional determination).

36C produces: threat findings.
36E translates: threat findings into architectural impact candidates.
37 decides: which impacts warrant ADRs.
```

---

## Section 10 — 36C Sub-Document Structure

| Sub-document | Threat Class | Primary Domain Target |
|-------------|-------------|----------------------|
| 36C-01 | Baseline (this document) | Framework and mapping |
| 36C-02 | Evidence Suppression (TC-1) | Gap A-3 / Audit context / completeness |
| 36C-03 | Evidence Fabrication (TC-2) | Event authentication / commitment points |
| 36C-04 | Governance Manipulation (TC-3) | GovernanceState / criteria / NCQ-03 |
| 36C-05 | Certification Abuse (TC-4) | External authority / NCQ-01, NCQ-02 |
| 36C-06 | Trust Concentration (TC-5) | Ownership distribution / structural enforcement |

---

## ARB Decision

```
Round 36C-01 — Threat Modeling Baseline

APPROVED WITH OBSERVATIONS

DDD Discipline:             HIGH
Governance Discipline:      HIGH
Threat Modeling Quality:    HIGH
Election-System Relevance:  HIGH
Architecture Neutrality:    HIGH
Premature Solution Bias:    VERY LOW

Observations applied:

  OBS-36C-01-A: Completeness certainty softened. "Must exist before any
    trustworthiness claim" → "appears necessary for strong trustworthiness
    claims." 36C will investigate whether alternative constitutional assurance
    models exist. Finding remains high-priority without being pre-concluded.

  OBS-36C-01-B: Scenario S3-D (Criteria Ambiguity) added to TC-3.
    Ambiguity — not corruption — is historically the most common
    real-world election failure. Constitutional threat, not technical threat.
    Connection to NCQ-03 and P-4 precision requirements established.

  OBS-36C-01-C: D43 elevated in TC-5 (Trust Concentration) as the strongest
    discovered ownership uncertainty. Unknown owner = inherent concentration risk.
    D43 carried explicitly into 36C-06 as a primary subject.

  OBS-36C-01-D: Methodological guardrail added (Section 1):
    "A threat finding does not imply a required architecture change."
    The governance gate (36C → 36E → 37) is now explicit in the threat
    modeling methodology itself.

Governing Question (frozen):
  What must happen for a dishonest election to appear trustworthy?

Five threat classes (ordered, binding):
  TC-1: Evidence Suppression (Gap A-3 — 36C-02)
  TC-2: Evidence Fabrication (commitment points — 36C-03)
  TC-3: Governance Manipulation (criteria/authority/ambiguity — 36C-04)
  TC-4: Certification Abuse (NCQ-01/02 — 36C-05)
  TC-5: Trust Concentration (D43 primary + ownership enforcement — 36C-06)

Constraint compliance:
  No ElectionGuard, Verifier Context, Guardian Context, Bulletin Board
  Context, or Proof Engine Context introduced.
  No design decisions made. No bounded contexts created.
  All findings mapped against discovered domain model only.

Round 36C-01: APPROVED
Round 36C-02 (Evidence Suppression): AUTHORIZED
```
