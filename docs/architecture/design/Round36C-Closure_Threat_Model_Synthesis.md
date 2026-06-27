# Round 36C-Closure — Threat Model Synthesis

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 36C-Closure
**Date:** 2026-06-13
**Status:** SUBMITTED FOR ARB REVIEW

---

## 1. Purpose and Governing Instruction

This document synthesizes what Round 36C discovered about trustworthiness.

It does not discover new threats.
It does not create bounded contexts or aggregates.
It does not make architecture decisions.
It does not promote candidates to confirmed status.

The governing question is:

```
What did we actually learn about trustworthiness?
```

The five threat class documents (36C-01 through 36C-06) were discovery.
This document is synthesis.

---

## 2. What Round 36C Investigated

Round 36C began with one governing question:

```
What must happen for a dishonest election to appear trustworthy?
```

To answer this question, five threat classes were investigated in sequence:

| Class | Name | Governing Question |
|-------|------|--------------------|
| TC-1 | Evidence Suppression | Can a real election event disappear without detection? |
| TC-2 | Evidence Fabrication | Can a non-event be made to appear real? |
| TC-3 | Governance Manipulation | Can trustworthiness fail with complete, authentic evidence? |
| TC-4 | Certification Abuse | Can certification make an untrustworthy election appear trustworthy? |
| TC-5 | Trust Concentration | Can trustworthiness fail due to power concentration alone, even when evidence is complete, authentic, governance is followed, and certification is correct? |

Each class was authorized before the next was opened.
The sequence was binding.

---

## 3. What Round 36C Actually Found

### 3.1 Round 36C began as a threat catalog. It ended as a threat dependency model.

At the start of Round 36C, the expectation was five independent threat classes, each addressing a different attack surface. The five threat classes were expected to be peers.

That expectation was wrong.

The five threat classes are not peers. They form a dependency network, not a simple linear chain. TC-5 is not a fifth peer threat alongside TC-1 through TC-4. TC-5 is a structural amplifier that makes every prior threat class easier to execute, harder to detect, and more constitutionally consequential. TC-1 and TC-2 form an evidence pair — symmetric attacks on what exists and what is asserted. TC-3 governs interpretation of that evidence. TC-4 governs acceptance of the trustworthiness claim. TC-5 amplifies all four.

### 3.2 The Dependency Network

```
TC-5   Trust Concentration — structural amplifier across all others
  ↓    amplifies

TC-4   Certification Abuse         — governs acceptance of the trustworthiness claim
  ↑    validates
TC-3   Governance Manipulation     — governs interpretation of evidence
  ↑    governs interpretation of

┌──────────────────────────────────────────────────────────┐
│  TC-1 (Suppression) ←→ TC-2 (Fabrication)               │
│  Evidence Pair: symmetric attacks on the evidentiary record │
│  TC-1: record smaller than reality                       │
│  TC-2: record larger or wrong relative to reality        │
└──────────────────────────────────────────────────────────┘
```

The network structure, not a strict linear chain:

- TC-1 and TC-2 are evidence-layer peers. Defending against TC-1 does not defend against TC-2, and vice versa.
- TC-3 cannot be defended by TC-1 + TC-2 protections alone — manipulated governance rules make authentic evidence yield fraudulent conclusions.
- TC-4 cannot be defended by TC-1 + TC-2 + TC-3 protections alone — a corrupted certifier validates the entire structure.
- TC-5 is not a layer above TC-4. TC-5 is a structural precondition that makes all four others easier to compromise. It operates across the entire network simultaneously.

The network is not a hierarchy of severity. It is a dependency structure. Defending any node does not protect the nodes above it.

### 3.3 What Each Threat Class Contributed

**TC-1 (Evidence Suppression)** established the foundational problem:
```
Four evidence states exist: Never Existed / Suppressed / Altered / Fabricated.
States 1 and 2 (Never Existed and Suppressed) are constitutionally indistinguishable
to any observer with incomplete knowledge of the expected evidence set.
The expected evidence set is undefined (Gap A-3).
TC1-DI-01 (CANDIDATE): Absence of evidence is not evidence of absence.
```

**TC-2 (Evidence Fabrication)** established the symmetric problem:
```
The mirror of TC-1: evidence can exist that records things that did not happen.
TC2-DI-01 (CANDIDATE): Presence of evidence is not evidence of truth.
TF-36C-COMB-01 (PROGRAM-LEVEL): The record can be simultaneously smaller than
reality (TC-1) and larger than reality (TC-2). Passive observation resolves neither.
```

**TC-3 (Governance Manipulation)** demonstrated that TC-1 + TC-2 defense is insufficient:
```
With complete evidence and authentic evidence, trustworthiness can still fail if:
  - the authority that governs evidence specification is illegitimate,
  - the criteria that determine election validity are manipulated, or
  - the rules governing constitutional change are themselves vulnerable.
TF-36C-04-14 (CANDIDATE PROGRAM-LEVEL): Governance threats exist independently
of TC-1 and TC-2. A new threat dimension was demonstrated, not merely hypothesized.
```

**TC-4 (Certification Abuse)** demonstrated that TC-1 + TC-2 + TC-3 defense is insufficient:
```
Even with complete, authentic evidence under legitimate governance, certification
can fail if the certifier is illegitimate, unauthorized, or structurally captured.
TF-36C-05-01 (CANDIDATE PROGRAM-LEVEL): Certifier legitimacy ≠ certification validity.
The implicit assumption — valid certifier + valid process = valid certification — was refuted.
Certification is the terminal act. TC-4 failure amplifies all prior threat class successes
into a public trustworthiness claim.
```

**TC-5 (Trust Concentration)** identified the structural amplifier:
```
Concentration of authority in a single actor (or software implementation)
makes all four prior threat classes easier to execute.
TC-5 is not merely a fifth peer threat. It is:
  - a risk multiplier across TC-1 through TC-4
  - an independent source of constitutional fragility
  - potentially a structural design requirement (TC5-NCQ-04, unresolved)
TC5-DI-03 (CANDIDATE PROGRAM-LEVEL): Ownership Assignment + Authority Distribution +
Structural Independence are three independent prerequisites for Constitutional Trustworthiness.
```

---

## 4. The Trustworthiness Formula — Program-Level Result

### 4.1 What Round 36A suggested

Round 36A investigated Verifiability. The implicit model at that stage was:

```
Trustworthiness ≈ Verifiability
```

This was not stated explicitly in 36A. It was the background assumption.

### 4.2 What Round 36B refined

Round 36B separated three constitutional capabilities:

```
Auditability (can we inspect?) ≠ Verifiability (can we verify?) ≠ Certification (can we accept?)
```

The model expanded:

```
Trustworthiness ≈ Evidence + Verification + Certification
```

But governance and authority were still implicit.

### 4.3 What Round 36C suggests

Round 36C suggests, through five independent threat investigations, the following candidate constitutional trustworthiness model:

```
CANDIDATE — Constitutional Trustworthiness Model
  (pending Round 36D corroboration; 36D may refine or add dimensions)

  Evidence Completeness          (TC-1 investigation)
  + Evidence Authenticity        (TC-2 investigation)
  + Governance Legitimacy        (TC-3 investigation — CANDIDATE dimension)
  + Certification Validity       (TC-4 investigation)
  + Authority Distribution       (TC-5 investigation)
```

This is not an architecture decision. It is a candidate characterization of what may be required for an election to be constitutionally trustworthy. Its status as complete is not confirmed.

None of these five components implies any of the others:

```
100% complete evidence + 0% authentic = constitutionally suspect
100% authentic evidence + 0% completeness = constitutionally suspect
Complete + authentic evidence + illegitimate governance = constitutionally suspect (TF-36C-04-14)
Complete + authentic + legitimate governance + invalid certification = constitutionally suspect (TF-36C-05-01)
All four above + concentrated authority = constitutionally suspect (TC5-DI-03, pending TC5-NCQ-04)
```

### 4.4 What this formula is not

This formula is not:

- An architecture. It is a set of requirements that architecture must satisfy.
- A confirmation that all five components are equally weighted. Weighting is a 36D question.
- A complete list. Round 36D may add or refine dimensions.
- A confirmation of Governance Legitimacy as a settled third constitutional dimension. It remains a candidate (OBS-36C-04-1).

### 4.5 What this candidate model suggests for Round 36

The original Round 36 motivation was to understand verifiability for an online election system. Round 36C suggests the problem is substantially larger than verifiability:

```
Governance (TC-3) and Authority Distribution (TC-5) may be constitutional prerequisites,
not implementation-layer concerns.
(CANDIDATE — pending Round 36D corroboration; see CF-36C-CLOSURE-03)
```

If confirmed by Round 36D, this would be the most significant architectural shift produced by Round 36.

---

## 5. Candidate Program-Level Insights

Three findings are designated CANDIDATE PROGRAM-LEVEL, plus one dependency insight:

### 5.1 TF-36C-COMB-01 (CANDIDATE PROGRAM-LEVEL)

```
TC-1 and TC-2 form a symmetric threat pair against the Audit context.
The audit record can be simultaneously:
  - smaller than the real election (suppression)
  - larger than the real election (fabrication)
Passive observation resolves neither direction.
```

Implication: A trustworthy audit function requires more than evidence reception. What that "more" is — this is an AIC for 36E, not a conclusion for 36C.

### 5.2 TF-36C-04-14 (CANDIDATE PROGRAM-LEVEL)

```
Governance threats exist independently of TC-1 and TC-2.
A trustworthy election requires legitimate governance of the criteria and authority
by which trustworthiness is evaluated — not only authentic, complete evidence.
```

Implication: The trust model itself is subject to attack. This changes the constitutional scope of the problem. Governance legitimacy is a candidate third constitutional dimension.

### 5.3 TF-36C-05-01 (CANDIDATE PROGRAM-LEVEL)

```
Certifier legitimacy ≠ certification validity.
Prior implicit assumption: valid certifier + valid process = valid certification.
Round 36C-05 refuted this assumption.
A valid certifier can certify a false result.
An invalid certifier cannot produce a constitutionally valid certification even with a correct result.
```

Implication: Certification authority is a constitutional property, not only a procedural one. Where this authority resides and how it is validated are 36D questions.

### 5.4 TC5-DI-03 (CANDIDATE PROGRAM-LEVEL) — Cross-Round Synthesis

```
Ownership Assignment → Authority Distribution → Structural Independence
→ Constitutional Trustworthiness

These three are independent prerequisites. None implies the others:
  - Ownership can be assigned without distributing authority
  - Authority can be distributed by design but concentrated by software
  - Structural independence of design does not guarantee structural independence of implementation
```

This is the deepest synthesis produced by Round 36C. It was not discoverable from any single threat class investigation. It required all five.

---

## 6. Unresolved Constitutional Questions — Priority-Ordered for 36D

The following NCQs were accumulated across Round 36C and require ARB constitutional determination before Round 36E can complete.

### Priority 1 — Most Consequential

**TC5-NCQ-04:**
```
Is structural distribution of authority constitutionally required,
or can behavioral integrity substitute?
```
If structural distribution is constitutionally required → TC-5 findings produce binding architectural constraints.
If behavioral integrity can substitute → TC-5 findings produce governance recommendations.
This question determines the constitutional weight of TC5-DI-03 and the scope of Round 37 ADRs.

**TC3-NCQ-04:**
```
Who governs the governors?
```
Authority over governance criteria is itself governed by someone or something. This recursion has no demonstrated terminal point. If the chain is infinite, constitutional trustworthiness may be unreachable through governance alone. If the chain terminates, what terminates it?

### Priority 2 — Structurally Blocking

**TC1-NCQ-01:**
```
What is the expected evidence set for a constitutionally complete election?
```
Gap A-3 (completeness responsibility undefined) cannot be resolved without an expected evidence set. TC-1 defense requires knowing what should exist. No currently discovered aggregate owns this definition.

**TC2-NCQ-02:**
```
Is passive observation by the Audit context constitutionally sufficient,
or does authentic evidence production require active provenance governance?
```
ARB constitutional determination required. If passive reception is sufficient → current Audit pattern may be adequate. If active provenance governance is required → Audit context has a constitutional obligation beyond observation.

**TC4-NCQ-03:**
```
Is circular certification constitutionally equivalent to self-certification,
or is circular certification a distinct (possibly weaker) failure mode?
```
TF-36C-05-06 established that self-certification is a special case of circular certification, not the reverse. The constitutional question of whether circular (but non-self) certification is constitutionally void or merely suspect remains open.

### Priority 3 — Design-Blocking

**TC2-NCQ-01:**
```
Who is constitutionally responsible for evidence provenance?
```
Provenance responsibility must precede accountability. No aggregate currently owns this. Parallels Gap A-3 and D43.

**TC5-NCQ-01:**
```
What is the minimum constitutional threshold for acceptable authority distribution?
```
TC-5 establishes that concentration is constitutionally suspect. It does not establish what distribution satisfies constitutional requirements.

**TC5-NCQ-02:**
```
Can a software implementation constitutionally verify its own independence?
```
If the independence verification mechanism is itself subject to concentration (AIC-36C-06-05), the constitutional verification is self-referential. What external grounding resolves this?

**TC5-NCQ-03:**
```
Which trustworthiness functions constitutionally require external authority?
Which can be internal-but-constitutionally-separated?
```
Structurally external authority (NCQ-02 from 36B) was established as a principle. Which specific functions require it is undetermined.

### Priority 4 — Research-Supporting

**TC3-NCQ-01/02/03, TC4-NCQ-01/02:**
```
Multiple open questions from TC-3 and TC-4 regarding criteria specification ownership,
authority succession, good-faith certifier traps, and independence minimum thresholds.
```
These are important but depend on TC5-NCQ-04 resolution for their constitutional framing.

---

## 7. D43 — Status at Round 36C-Closure

D43 (Enrollment Authority Ownership Gap) was elevated to explicit trust concentration risk in 36C-01.

Round 36C-Closure assessment of D43:

```
D43 is not merely an ownership gap.
D43 is an instance of TC-5 at a specific domain boundary:
  the Eligibility/Enrollment boundary.

An unresolved ownership gap defaults to the operational actor by institutional gravity.
Whoever controls enrollment controls Eligibility.
Whoever controls Eligibility controls Voting.
The chain is not hypothetical — it is the direct consequence of undefined ownership.

D43 must be a primary 36D investigation target.
```

D43 was not resolved in Round 36C. It is carried to 36D as a priority item.

---

## 8. AICs Consolidated for Round 36E

The following Architectural Impact Candidates were generated across all Round 36C documents. They are carried to 36E for architecture impact assessment. No architecture decisions are made here.

```
From 36C-02 (TC-1):
  AIC-36C-02-01: Expected evidence set definition mechanism (Gap A-3 — HIGH)
  AIC-36C-02-02: Evidence state observability (States 1+2 indistinguishable)
  AIC-36C-02-03: Constitutional criteria for event completeness

From 36C-03 (TC-2):
  AIC-36C-03-01: Event provenance governance — active vs passive
  AIC-36C-03-02: Audit context constitutional scope (observer vs active receiver)
  AIC-36C-03-03: At-most-once delivery as constitutional requirement (idempotency)

From 36C-04 (TC-3):
  AIC-36C-04-01: Evidence specification ownership
  AIC-36C-04-02: Authority legitimacy verification mechanism
  AIC-36C-04-03: Constitutional criteria immutability enforcement
  AIC-36C-04-04: Governance change governance (meta-governance boundary)
  AIC-36C-04-05: D43 authority chain implications for Eligibility
  AIC-36C-04-06: Evidence Governance Failure as distinct concern category

From 36C-05 (TC-4):
  AIC-36C-05-01: Certifier independence minimum threshold
  AIC-36C-05-02: Terminal certification authority boundary
  AIC-36C-05-03: Circular vs self-certification constitutional distinction
  AIC-36C-05-04: Good-faith certifier trap — defense mechanism unknown
  AIC-36C-05-05: TC-4 independence failure spectrum (Levels 0-4, Level 4 = void)

From 36C-06 (TC-5):
  AIC-36C-06-01: Minimum constitutional threshold for authority distribution
  AIC-36C-06-02: External authority grounding (which functions require it)
  AIC-36C-06-03: Internal-but-constitutionally-separated authority — design vs implementation
  AIC-36C-06-04: Constitutional fragility assessment mechanism
  AIC-36C-06-05: Independence verification mechanism [PRIORITY CARRY TO 36E]
    "Designed Distribution ≠ Actual Distribution" — major 36E architectural consideration
```

---

## 9. What Round 36D Must Investigate

Round 36C established the constitutional trustworthiness formula and the dependency structure. Round 36D (Trust Distribution Research) must evaluate how constitutional authority can be distributed to satisfy the requirements that Round 36C identified.

### 9.1 Primary 36D Investigation Targets

```
1. TC5-NCQ-04:
   Is structural distribution constitutionally required,
   or can behavioral integrity substitute?
   (Most consequential open question)

2. TC3-NCQ-04:
   Who governs the governors?
   Does the authority recursion terminate, and if so, how?

3. D43:
   Who owns enrollment authority?
   What are the constitutional implications of that ownership
   for Eligibility, Voting, and Certification?

4. TC5-DI-03 corroboration:
   The three-prerequisite chain (Ownership → Authority → Independence)
   was generated by synthesis of 36B through 36C-06.
   36D must either corroborate or refine this chain.
   Promotion from CANDIDATE to CONFIRMED is gated on 36D.

5. Authority Legitimacy vs Authority Fabrication:
   NOTE-36C-04-A: "Authority Fabrication" may be more precisely "Authority Legitimacy."
   36D must evaluate whether the distinction changes constitutional requirements.
```

### 9.2 What 36D Must Not Do

```
36D must not:
  - Create bounded contexts or aggregates
  - Implement or recommend ElectionGuard, Helios, or threshold cryptography
  - Write ADRs
  - Promote candidates to confirmed without completing 36D research
  - Resolve TC5-NCQ-04 without constitutional justification
```

### 9.3 What 36D Must Produce

```
At minimum:
  - A trust distribution baseline (analog to 36B auditability baseline)
  - A constitutional determination on TC5-NCQ-04 — or evidence that it remains open
  - An authority ownership map (who owns what authority and why)
  - TC5-DI-03 corroboration finding (or refinement)
  - D43 resolution candidate (or escalation to ARB)
  - NCQs carried from 36C, classified by whether 36D resolved, narrowed, or left open
```

---

## 10. What Round 36C-Closure Confirms

### Confirmed Structural Findings

```
CF-36C-CLOSURE-01:
  Round 36C began as a threat catalog and ended as a threat dependency model.
  The five threat classes are not peers. They form a dependency network
  with TC-5 as structural amplifier. TC-1 and TC-2 form an evidence pair;
  TC-3 governs interpretation; TC-4 governs acceptance; TC-5 amplifies all four.

CF-36C-CLOSURE-02:
  The constitutional trustworthiness formula is multi-dimensional.
  Evidence Completeness, Evidence Authenticity, Governance Legitimacy,
  Certification Validity, and Authority Distribution are five independent
  components. Satisfying any four does not satisfy the fifth.

CF-36C-CLOSURE-03 (CANDIDATE PROGRAM-LEVEL — pending Round 36D corroboration):
  Governance (TC-3) and Authority Distribution (TC-5) may be constitutional
  prerequisites for trustworthiness, not implementation-layer concerns.
  Round 36C suggests this. Round 36D must test it.
  If confirmed: this becomes the most significant architectural shift of Round 36.

CF-36C-CLOSURE-04:
  Certification is the terminal act. TC-4 failure amplifies all prior
  threat class successes into a public trustworthiness claim.
  Certification cannot be treated as a post-election administrative step.

CF-36C-CLOSURE-05:
  TC-5 (Trust Concentration) is not a peer to TC-1 through TC-4.
  TC-5 is a structural amplifier. This distinction has constitutional consequences
  that TC5-NCQ-04 must resolve before Round 36E can complete.
```

```

CF-36C-CLOSURE-06 (CANDIDATE PROGRAM-LEVEL):
  Round 36C identified a shift in the primary risk model.

  At the beginning of Round 36:
    Trustworthiness risk ≈ Evidence integrity risk.
    The dominant uncertainty was whether cryptographic or hash mechanisms
    could protect the evidentiary record from tampering.

  At the end of Round 36C:
    Trustworthiness risk ≈ Governance risk
                         + Authority risk
                         + Evidence risk.
    The dominant uncertainty is no longer cryptographic.
    It is constitutional.

  Governance legitimacy, authority distribution, and certification independence
  cannot be provided by cryptographic mechanisms. They require constitutional
  design. Cryptography can protect evidence authenticity (TC-2). It cannot
  assign governance authority (TC-3), validate certifier legitimacy (TC-4),
  or distribute structural power (TC-5).

  This is the most consequential finding produced by Round 36 so far.
  Pending Round 36D corroboration.
```

### What Remains Candidate

```
The following remain CANDIDATE and must not be treated as confirmed:
  - TF-36C-COMB-01 (CANDIDATE PROGRAM-LEVEL) — requires 36D corroboration
  - TF-36C-04-14 (CANDIDATE PROGRAM-LEVEL) — requires 36D corroboration
  - TF-36C-05-01 (CANDIDATE PROGRAM-LEVEL) — requires 36D corroboration
  - TC5-DI-03 (CANDIDATE PROGRAM-LEVEL) — requires 36D corroboration
  - CF-36C-CLOSURE-03 (CANDIDATE PROGRAM-LEVEL) — Governance + Authority = prerequisites
  - CF-36C-CLOSURE-06 (CANDIDATE PROGRAM-LEVEL) — Risk model shift: constitutional, not cryptographic
  - Governance Legitimacy as confirmed third constitutional dimension — CANDIDATE
  - TC5-NCQ-04 resolution — OPEN; resolution determines architectural constraint scope
  - The candidate constitutional trustworthiness model (Section 4.3) as complete —
    36D may add or refine dimensions
```

---

## ARB Decision

```
Round 36C-Closure — Threat Model Synthesis

[ARB REVIEW COMPLETE]

Research Discipline:        HIGH
Synthesis Quality:          HIGH
Governance Discipline:      HIGH
Architecture Neutrality:    HIGH
Confidence:                 HIGH

Central synthesis results:
  CF-36C-CLOSURE-01: Threat catalog → threat dependency network (TC-5 as amplifier)
  CF-36C-CLOSURE-02: Candidate trustworthiness model is five-dimensional
  CF-36C-CLOSURE-03 (CANDIDATE): Governance + Authority Distribution = prerequisites
  CF-36C-CLOSURE-04: Certification is terminal — TC-4 amplifies all prior
  CF-36C-CLOSURE-05: TC-5 is structural amplifier, not peer
  CF-36C-CLOSURE-06 (CANDIDATE): Dominant risk is constitutional, not cryptographic

Governing Rule Compliance:
  No new threat classes introduced.
  No bounded contexts, aggregates, or services created.
  No architecture decisions made.
  No candidates promoted to confirmed.
  Synthesis only — no discovery.

ARB Observations Applied:

  OBS-36C-CLOSURE-1 (APPLIED):
    "Dependency chain" corrected to "dependency network" throughout.
    TC-1 and TC-2 are an evidence pair, not a strict stack.
    TC-3 governs interpretation. TC-4 governs acceptance.
    TC-5 amplifies all others as structural amplifier, not a layer above TC-4.
    Section 3.2 diagram revised to reflect network structure.

  OBS-36C-CLOSURE-2 (APPLIED):
    Section 4.3 weakened from "Constitutional Trustworthiness =" to
    "CANDIDATE — Constitutional Trustworthiness Model."
    Section heading changed from "demonstrated" to "suggests."
    Formula retained; epistemic status now consistent with candidate findings.
    Section 4.5 updated to reflect conditional language.

  OBS-36C-CLOSURE-3 (APPLIED):
    CF-36C-CLOSURE-03 reclassified as CANDIDATE PROGRAM-LEVEL,
    pending Round 36D corroboration. Wording changed from "are constitutional
    prerequisites" to "may be constitutional prerequisites."
    "Most important architectural shift" → conditional on 36D confirmation.
    CF-36C-CLOSURE-03 added to "What Remains Candidate" list.

  OBS-36C-CLOSURE-4 (APPLIED):
    CF-36C-CLOSURE-06 added as CANDIDATE PROGRAM-LEVEL finding.
    Explicit risk-model transition recorded:
      Beginning of Round 36: Trustworthiness risk ≈ Evidence integrity risk
      End of Round 36C:      Trustworthiness risk ≈ Governance + Authority + Evidence risk
    The dominant uncertainty is no longer cryptographic — it is constitutional.
    This is the most consequential finding of Round 36 so far (pending 36D corroboration).

Round 36C-Closure: APPROVED
Round 36C:         CLOSED

Governing Instructions for Round 36D (Trust Distribution Research):

  Round 36D opens with one narrow governing question:

    "When is authority distribution constitutionally required,
     and when is behavioral integrity sufficient?"

  Nearly every unresolved NCQ from Round 36C converges on this question.
  TC5-NCQ-04 is the canonical form. TC3-NCQ-04, D43, and TC5-DI-03
  corroboration all depend on its resolution.

  Round 36D must:
    1. Investigate TC5-NCQ-04 as the primary research question
    2. Investigate TC3-NCQ-04 ("who governs the governors?")
    3. Produce a trust distribution baseline
    4. Evaluate D43 (enrollment authority ownership) as a TC-5 instance
    5. Corroborate or refine TC5-DI-03
    6. Produce an authority ownership map for discovered domain functions
    7. Classify all 36C NCQs: resolved / narrowed / still open

  Round 36D must not:
    - Create bounded contexts or aggregates
    - Implement or recommend ElectionGuard, Helios, or threshold cryptography
    - Write ADRs
    - Promote any candidate to confirmed without 36D research basis

Round 36D: AUTHORIZED
```
