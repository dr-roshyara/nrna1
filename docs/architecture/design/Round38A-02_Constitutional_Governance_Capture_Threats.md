# Round 38A-02 — Constitutional & Governance Capture Threats

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38A — Threat Validation
**Document:** 38A-02 of 6
**Status:** SUBMITTED FOR ARB REVIEW
**Date:** 2026-06-16

**Predecessor:**
- Round 38A-01 — Threat Modeling Baseline — APPROVED WITH STRATEGIC CORRECTIONS

**Governing Discipline (from 38A-01, ARB-approved):**
- Default classification: Assessment Deferred (not Survives)
- Burden of proof: demonstrate failure, not demonstrate survival
- Four architecture gaps: unresolved architecture questions, not automatic FAIL findings
- Pre-constitutional exposures (Source-of-Authority Layer): reported separately in 38A-06, not classified as constitutional FAIL
- Conditional Fail (C-F): bounds failure domain; must name threshold and condition

**Scope:** Constitutional and governance capture threats. Out of scope: APIs, databases, microservices, cryptographic algorithms, technology selection, deployment architecture.

**Deferred to 38A-03:** TM-02 (Certification Capture), TM-04 (Challenge Adjudication Capture) — evaluated in the Authority Capture document where coalition analysis is conducted.

---

## Part A — Pre-Evaluation Architecture State

### A.1 Inherited Assumptions Under Evaluation

The following assumptions from TA-01 (38A-01 Part G) govern this evaluation. Per ARB ruling, they are treated as unresolved architecture questions — not as FAIL conditions before adversarial analysis is applied.

| ID | Assumption | Status | Threat Dependency |
|---|---|---|---|
| AA-01 | MA Legitimacy | UNRESOLVED | TM-07, TM-35 |
| AA-02 | EC Uniqueness | NOT VALIDATED | TM-13, TM-09 |
| AA-03 | EC Amendment Process Independence | ASSUMED, NOT VERIFIED | TM-01, TM-07 |
| AA-06 | AC-31 Reference Standard | UNRESOLVED | AC-31 analysis |
| AA-07 | GovernanceState as Sole Phase Record | IDENTIFIED RISK | TM-03, TM-10 |
| GA-01 | Authority Self-Reporting | OPTIMISTIC | TM-08 |
| GA-04 | Standing Diversity Sufficiency | NOT SPECIFIED | TM-05, TM-12 |
| OA-01 | Challenger Evidence Access | UNSPECIFIED | TM-08, TM-12 |
| OA-02 | Challenge Window Practical Sufficiency | UNDER-SPECIFIED | TM-08, TM-12 |
| OA-03 | ChallengeReceptionFunction Independence | OPERATIONALLY CRITICAL | TM-05 |
| SA-02 | Challenger Willingness Under Pressure | BEHAVIORAL PRECONDITION | TM-12, TM-35 |

### A.2 Active Constitutional Invariants

The following invariants from ADR-1 through ADR-7 are the architecture's primary defenses. Each threat evaluation must test whether these invariants are sufficient or bypassable.

| Invariant | Source | Protection |
|---|---|---|
| ADR3-INV-01 | No stratum satisfies another | Evidence layer independence |
| ADR6-INV-01 | CO-5 void unless CO-2 + CO-3 + CO-4 all independently satisfied | Certification integrity |
| ADR7-INV-01 | Suspension succession pre-designation | Authority continuity |
| ADR7-INV-02 | Anti-capture: no authority may self-grant/expand/restrict standing against itself | Standing protection |

### A.3 The Legitimacy Root

Per Part G.6 of 38A-01 (ARB-approved): the constitutional architecture has a Source-of-Authority Layer predating its constitutional instruments. Threats that attack this layer are **pre-constitutional exposures** — they attack something the architecture was never designed to protect. This document marks such threats explicitly and defers their constitutional FAIL/Survive classification to 38A-06.

```
Source-of-Authority Layer (pre-constitutional)
         ↓
Membership Assembly  ←—— AA-01 unresolved
         ↓
ElectionConstitution  ←—— AA-02 assumed, AA-03 unverified
         ↓
Seven D43 Authority Aggregates
         ↓
CO-5 Election Validity
         ↓
AC-31 Reference Standard  ←—— AA-06 unresolved
```

---

## Part B — Threat Evaluations

### B.1 Evaluation Template

For each threat:

| Field | Content |
|---|---|
| **Attack Path** | How adversary reaches the failure condition, step by step |
| **Weakest Link** | The specific architectural assumption or invariant that fails first |
| **Adversary Capability Threshold** | Minimum adversary capability required to reach the failure condition |
| **Constitutional Defenses** | Which invariants, provisions, or mechanisms resist the attack |
| **Defense Failures** | Where and why constitutional defenses fail under adversarial pressure |
| **Survivability Classification** | Per 38A-01 Part E criteria |
| **Evidence for Classification** | Positive evidence required (per adversarial discipline) |
| **Unresolved Questions** | What this evaluation cannot determine from constitutional architecture alone |

---

### B.2 TM-01 — Constitution Capture

**Class:** 1 (Constitutional) | **Adversary:** D (Colluding Authorities), E (State-Level)

**Attack Path:**

1. Adversary D or E identifies the composition mechanism of the Membership Assembly — who can participate, how, and under what rules.
2. Over time, adversary achieves sufficient MA representation through procedurally valid participation: astroturfing, block voting of affiliated members, displacement of independent members, or financial incentives that do not violate explicit MA participation rules.
3. Adversary uses MA majority to drive ElectionConstitution amendments. Each amendment is individually defensible — it may narrow authority scope, reduce standing diversity thresholds, restrict audit access, or redefine "constitutional compliance" in ways that favor the adversary's preferred outcome.
4. Amendments accumulate. The new EC satisfies CO-4 compliance (because CA evaluates elections against EC, not against an external constitutional standard). The Concentration Chain now references a captured document.
5. Subsequent elections: CA certifies against the captured EC; CO-4 is "satisfied" by the captured standard; ADR6-INV-01 appears fulfilled. The certification is constitutionally valid in form and constitutionally compromised in substance.

**Weakest Link:** AA-03 — *EC Amendment Process Independence* is the structural gap that makes TM-01 reachable. The ADRs treat EC amendment as a constitutional fact but do not specify:
- Who may propose amendments
- What threshold is required for approval
- Whether any amendments are constitutionally prohibited
- How conflicts of interest in the amendment process are identified

A captured EC amendment requires exploiting exactly what AA-03 leaves unspecified.

**Secondary Weakest Link:** AA-04 — *CertificationAuthority Non-Circular CO-4 Evaluation*. CO-4 evaluates whether an election complied with EC. If EC is captured, CO-4 becomes compliance with a captured standard. CA cannot detect this circularity because CA has no access to a standard external to EC.

**Adversary Capability Threshold:**
- Adversary D: sustained organizational capacity to achieve MA representation majority. Requires multi-cycle commitment and organizational patience. Not a low-effort attack.
- Adversary E: same capability with deeper resources and longer time horizon. More accessible to state-level actors whose time horizon spans organizational leadership cycles.
- The threshold is not precisely nameable because MA composition rules and amendment thresholds are not constitutionally specified (AA-03 gap).

**Constitutional Defenses:**
- None specifically. ADR7-INV-02 prevents authority self-extension of standing, but EC amendment is not a standing question — it is a substantive authority question that ADR7-INV-02 does not address.
- The Named Attestation Model (CO-2/CO-3/CO-4 individually challengeable) allows challengers to contest CO-4 compliance. But CO-4 compliance means compliance with EC — challengers cannot challenge CO-4 on the grounds that EC itself is corrupt.

**Defense Failures:**
1. The challenge architecture (ADR-5) governs challenges about *election conduct* relative to EC. It does not govern challenges about EC's constitutional legitimacy.
2. ADR7-INV-02 prevents standing manipulation *within the challenge process*. It does not prevent constitutional amendment of the EC that redefines the challenge process itself.
3. The CertificationAuthority evaluates CO-4 against EC. If EC is amended to weaken CO-4 requirements, CA's CO-4 assessment changes accordingly — without any constitutional violation.

**Survivability Classification:** **C-F (Conditional Fail)**

The architecture conditionally fails when adversary achieves sufficient MA representation to drive EC amendments that erode constitutional protections. The constitutional architecture has no detection mechanism for procedurally valid EC capture — no violation of any stated invariant occurs. Every formal check passes. The election is constitutionally certified against a constitutionally compromised document.

**Evidence for Classification:**
- Positive evidence of failure path: CA evaluates CO-4 against EC (ADR-6). EC defines the CO-4 standard. An amendment changing the CO-4 standard is constitutionally valid under any amendment process (AA-03 gap). Therefore the failure path is reachable.
- No ADR specifies a defense against this path. ADR7-INV-02 is inapplicable to EC amendment. Named Attestation challenges are inapplicable because no formal CO-4 violation occurs.

**Unresolved Questions:**
- Is there a constitutional floor — rights and protections that cannot be removed by EC amendment?
- Can a challenger challenge an EC amendment itself (not an election conducted under EC) through the challenge architecture?
- What constitutes a "sufficient MA majority" for amendment approval?

---

### B.3 TM-03 — GovernanceState Corruption

**Class:** 4 (Evidence Manipulation) | **Adversary:** B (Malicious Election Official), C (Compromised Authority), E (State-Level)

**Attack Path:**

1. Adversary B (election official with GovernanceState write access) or C (authority with administrative control over GovernanceState) corrupts the phase transition records.
2. Corruption target options:
   - Mark *CertificationWindowOpen* before the voting phase has constitutionally closed → CA issues CO-5 prematurely
   - Mark *CertificationComplete* before CO-5 is issued → challenge window bypassed
   - Mark a phase as *Closed* while it remains open → constitutionally protected activity (voting, challenges) is prematurely terminated
   - Delete phase transition records → constitutional phase history becomes irrecoverable
3. CertificationAuthority evaluates CO-3 (procedural compliance) against GovernanceState records. Per AA-07, GovernanceState is the sole authoritative phase record.
4. Corrupted phase records produce corrupted CO-3 compliance assessment. CA issues CO-5 with structurally false CO-3 certification.
5. ADR6-INV-01 appears satisfied — CO-2, CO-3, CO-4 all return "compliant." The election is certified. The constitutional failure is undetectable from within the constitutional architecture.

**Weakest Link:** AA-07 — *GovernanceState as Sole Authoritative Phase Record*. OBS-ADR7-03 explicitly identifies this: GovernanceState corruption creates constitutional evidence ambiguity. The deeper problem is not ambiguity but circularity: **challengers evaluating CO-3 must use GovernanceState as evidence, which is the same record that has been corrupted**. The challenge architecture cannot produce an independent assessment of CO-3 compliance.

**Adversary Capability Threshold:**
- Adversary B: election official with GovernanceState write access. Low-to-moderate capability; insider threat.
- Adversary C: authority aggregate with administrative control of GovernanceState infrastructure. Moderate capability.
- Adversary E: state-level actor with access to infrastructure layer. High capability with broad attack surface.

**Constitutional Defenses:**
- Named Attestation Model: CO-3 is an individually challengeable section. Challengers can file a CO-3 challenge.
- CO-3 challenge path: challenger claims procedural non-compliance → CAB adjudicates → CA must produce evidence of procedural compliance.

**Defense Failures:**
1. **The challenge evidence problem:** To challenge CO-3, challengers need evidence of actual phase timing. GovernanceState is the primary evidence source. If GovernanceState is corrupted, challengers who access GovernanceState for evidence will read the corrupted record. Their challenge will fail because the "evidence" (GovernanceState) confirms the corrupted timeline.
2. **Independent evidence access is unspecified (OA-01):** If challengers cannot access independent timing evidence (e.g., external timestamps, records from independent observers), the CO-3 challenge is self-defeating.
3. **Circular evaluation:** CAB adjudicates CO-3 challenges using GovernanceState as the authoritative phase record. CAB evaluates the challenge against the same corrupted evidence the challenger used.

**Survivability Classification:** **C-F (Conditional Fail)**

The architecture conditionally fails when GovernanceState is corrupted AND no independent phase timing record exists AND challengers have no access to evidence independent of GovernanceState. All three conditions are currently satisfied per AA-07 and OA-01. The C-F threshold — independent timing evidence — is constitutionally unspecified, making this C-F structurally close to an unconditional Fail.

**Evidence for Classification:**
- OBS-ADR7-03: explicitly identified as a constitutional evidence ambiguity risk.
- AA-07: GovernanceState is the sole authoritative record. No independent corroboration.
- OA-01: Challenger evidence access is unspecified.
- The failure path is fully reachable by Adversary B (insider with write access) — a credible, low-capability adversary.

**Unresolved Questions:**
- Can constitutional phase transitions produce externally verifiable artifacts (e.g., timestamps countersigned by independent parties)?
- Is CA required to independently verify phase timing before issuing CO-3 certification, using evidence beyond GovernanceState?

---

### B.4 TM-05 — Standing Manipulation

**Class:** 2 (Governance Capture) | **Adversary:** C (Compromised Authority), D (Colluding Authorities)

**Attack Path:**

1. CertificationAuthority (under Adversary C or D control) becomes subject to legitimate challenges from standing holders.
2. CA modifies standing criteria — applying them retroactively, adding procedural requirements, narrowing the definition of "member in good standing" — specifically targeting the challengers who would file against CA.
3. Challengers are notified their standing has been revoked or was never valid. Their challenge attempts are rejected at the filing stage.
4. If CA controls or influences the ChallengeReceptionFunction (OA-03), filed challenges may never reach CAB for adjudication.
5. CA's constitutional violations proceed without challenge.

**The Bootstrap Problem:** To challenge standing manipulation, a challenger needs standing to file the meta-challenge. If standing has been revoked, the challenger cannot file. ADR7-INV-02 prohibits standing self-restriction by the challenged authority — but enforcing ADR7-INV-02 requires reaching CAB, which requires standing.

**Weakest Link:** OA-03 — *ChallengeReceptionFunction Unforgeable Filing*. If CA operationally controls the challenge filing mechanism, standing manipulation enables filing suppression. The bootstrap problem arises specifically when: (a) CA restricts standing, AND (b) CA controls the filing function, AND (c) challengers cannot bypass CA to reach CAB directly.

**Adversary Capability Threshold:**
- Adversary C: authority aggregate with operational control of standing criteria and filing reception. Moderate capability — requires administrative control, not constitutional amendment.
- Adversary D: coordinated multi-authority covering both the challenged authority and the filing mechanism. Higher capability but higher effectiveness.

**Constitutional Defenses:**
- ADR7-INV-02: explicitly prohibits self-restriction of standing for challenges against the authority itself. This is the primary constitutional defense.
- CAB is established specifically to adjudicate challenges and enforce constitutional constraints on authority behavior.

**Defense Failures:**
1. **ADR7-INV-02 enforcement depends on CAB accessibility:** ADR7-INV-02 is a rule that CAB enforces. If challengers cannot reach CAB (bootstrap problem), the invariant cannot be enforced.
2. **ChallengeReceptionFunction independence is unspecified (OA-03):** If the function that receives challenge filings is operationally controlled by the same authority manipulating standing, the filing path is compromised.
3. **GA-04 (Standing Diversity) gap:** If standing holders are few and can all be individually targeted for standing manipulation, diversity-based resilience fails.

**Survivability Classification:** **S-D → C-F (Conditional)**

- **S-D** when: CAB is operationally accessible independently of CA, and challengers can reach CAB without going through CA-controlled filing. ADR7-INV-02 provides constitutional cover; violation is detectable when challengers report to CAB.
- **C-F** when: CA controls the ChallengeReceptionFunction (OA-03 gap) AND challenges cannot be filed independently of CA. In this condition, the bootstrap problem makes TM-05 a Conditional Fail — standing manipulation succeeds because the enforcement mechanism cannot be reached.

**Evidence for Classification:**
- ADR7-INV-02 is a constitutional defense. The defense is real.
- OA-03 flags the operational gap: ChallengeReceptionFunction independence is not constitutionally specified.
- The C-F condition is reachable because OA-03 is unresolved. Whether OA-03 is resolved in practice depends on implementation — which is out of scope for Round 38A.

**Unresolved Questions:**
- Is ChallengeReceptionFunction constitutionally required to be independent of all seven D43 authority aggregates?
- Can a challenger with revoked standing file a meta-challenge about the standing revocation through an alternative filing path?

---

### B.5 TM-07 — Membership Capture

**Class:** 1 (Constitutional) | **Adversary:** D (Colluding Authorities), E (State-Level)

**Pre-Constitutional Exposure Notice:** TM-07 attacks the Source-of-Authority Layer (G.6). The Membership Assembly's legitimacy (AA-01) is explicitly unresolved. This evaluation proceeds with AA-01 as an unresolved architecture question per ARB ruling (OQ-38A-03 resolution). Classification accounts for AA-01's unresolved status.

**Attack Path:**

1. Adversary identifies the Membership Assembly's participation mechanism — how membership is established, who can vote within MA, how MA decisions are made.
2. Through procedurally valid participation, adversary achieves sufficient MA representation:
   - **Astroturfing:** creating or activating dormant memberships aligned with adversary
   - **Representative capture:** influencing election of MA delegates through targeted campaigns
   - **Incentive-based capture:** offering benefits to MA members for votes on EC amendments
   - **Attrition:** waiting for natural membership turnover and selectively replacing departing members with adversary-aligned members
3. MA majority achieved. Adversary drives EC amendments that embed adversary-preferred terms — restricting challenger standing, weakening audit scope, redefining certification compliance.
4. EC is structurally amended. Subsequent elections are governed by the adversary's EC. The architecture's entire legitimacy chain now rests on a captured foundation.

**The AA-01 Compounding Factor:** AA-01 (MA Legitimacy) is UNRESOLVED. The architecture has not established what makes MA participation "legitimate." If "legitimate participation" is undefined, adversary can claim their participation IS legitimate participation by definition. There is no constitutional standard against which to detect illegitimate MA membership. TM-07 attacks something the architecture has not constitutionally defined.

**Weakest Link:** AA-01 and AA-03 simultaneously:
- AA-01: MA legitimacy is unresolved — what makes participation "legitimate" is undefined
- AA-03: EC amendment process is unspecified — what threshold of MA support is required for a valid amendment is undefined

Without either of these being constitutionally resolved, TM-07 operates in a constitutional vacuum.

**Adversary Capability Threshold:**
- Adversary D: organizational capacity to achieve MA majority through procedurally valid participation. Requires time and organizational reach but not constitutional violation.
- Adversary E: state-level resources applied to membership participation. Very accessible.
- **The threshold is constitutionally unnameable:** because MA composition rules and amendment thresholds are not constitutionally specified (AA-01, AA-03 gaps), the threshold cannot be precisely identified from the constitutional architecture alone.

**Constitutional Defenses:** None. No ADR specifies:
- MA composition requirements
- MA capture detection mechanisms
- Constitutional limits on MA amendment authority
- Legitimate vs. illegitimate MA participation criteria

**Defense Failures:** All potential defenses require specifying MA governance rules that do not exist in the constitutional architecture. The architecture assumes MA legitimacy (AA-01) without establishing it.

**Survivability Classification:** **C-F (Conditional Fail — pre-constitutional exposure)**

The architecture conditionally fails when adversary achieves sufficient MA representation to drive EC amendments. Because AA-01 is unresolved, the failure condition is partially pre-constitutional: TM-07 succeeds not only because the architecture fails to protect MA but because the architecture never established what MA protection would look like.

Per G.6 classification: this is a pre-constitutional exposure. The constitutional architecture was designed to govern from EC downward. TM-07 attacks the layer the architecture was never designed to protect. **38A-06 must report this as a pre-constitutional exposure in addition to C-F classification.**

**Evidence for Classification:**
- Positive evidence of failure path: EC amendment requires MA participation (assumed). No constitutional specification of MA capture detection. No constitutional limit on EC amendment scope. Failure path is fully reachable.
- OBS-ADR7-SS1: MA is "strongest CANDIDATE for source-of-source" — but its own legitimacy is not established. This is direct evidence that the architecture has not closed TM-07.

**Unresolved Questions:**
- Is TM-07 more accurately described as a pre-constitutional exposure or a constitutional architecture gap?
- Can constitutional specification of MA governance rules close TM-07, or does it require institutional (non-constitutional) legitimacy mechanisms?

---

### B.6 TM-08 — Silent Certification Failure

**Class:** 2 (Governance Capture / Structural) | **Adversary:** None required (TM-08-B) / C, D (others)

*Note: This is the only threat in the catalog where the primary failure mode (TM-08-B) requires no adversary. It is a structural integrity test of the constitutional architecture itself.*

#### TM-08-A — CA Issues CO-5 Without Reviewing Evidence

**Attack Path:** CA — through negligence, time pressure, or deliberate misrepresentation — issues CO-5 without reviewing the underlying evidence package. CO-2, CO-3, CO-4 are attested without being evaluated.

**Constitutional Defenses:** Named Attestation Model (OQ-37-06-01 resolved): CO-2/CO-3/CO-4 are individually challengeable attestation sections. A challenger with access to the underlying evidence can demonstrate that CO-2/CO-3/CO-4 was not substantively evaluated.

**Defense Condition:** OA-01 (Challenger Evidence Access) must be resolved. If challengers cannot access the underlying evidence package, they cannot demonstrate that CA's attestation was unsupported. The challenge path is available in principle but depends on OA-01 resolution in practice.

**Survivability Classification:**
- **S-D** if OA-01 is resolved (challengers can access evidence to demonstrate unreviewed attestation)
- **C-F** if OA-01 is unresolved (challengers cannot distinguish reviewed from unreviewed attestation)

**Evidence:** Named Attestation is a constitutional defense. OA-01 is the gap that determines whether the defense is operable.

---

#### TM-08-B — Review Impossible (No Adversary Required)

**Attack Path:** CA attempts to review the evidence package but evidence is inaccessible, in an unusable format, or was never created. CA faces a structural impossibility: it cannot complete the CO-2 review because the evidence required for that review does not exist in accessible form.

Two outcomes:
- **Outcome 1:** CA correctly applies ADR6-INV-01, determines CO-2 cannot be assessed, and refuses to issue CO-5. The election is uncertified. This is constitutionally correct behavior but election failure still occurs.
- **Outcome 2:** CA issues CO-5 despite CO-2 being unassessable. This produces a structurally false CO-5. The election is certified on a false constitutional foundation.

**Constitutional Defenses:**
- ADR6-INV-01: explicitly requires CO-2 to be independently satisfied. CA is constitutionally prohibited from issuing CO-5 without it.
- This invariant is the only defense, and it only applies if CA applies it correctly (Outcome 1).

**Defense Failures:**
1. The constitutional architecture does not specify evidence accessibility requirements for CA review. There is no provision mandating that evidence must be accessible in formats CA can review.
2. Outcome 1 (CA correctly refuses) produces an election failure — not a constitutional architecture success. The architecture has no remediation path for an election that cannot be certified due to evidence inaccessibility.
3. Outcome 2 produces TM-08-A conditions — but with evidence absence rather than negligence as the cause. The outcome is identical: structurally false CO-5.

**Survivability Classification:** **C-F approaching F** *(ARB Correction 2026-06-17 — reclassified from unconditional F)*

**Recovery Path Analysis:**

The ARB identifies that a constitutional architecture may legitimately produce Outcome 1 (CA correctly refuses to certify) without this constituting an unconditional constitutional failure — provided a constitutional recovery procedure exists (election void → rerun required). Under such a procedure: CA correctly applies ADR6-INV-01, refuses CO-5, election is declared void, and a constitutional recovery mechanism triggers a new election with evidence accessibility requirements corrected. That would be a constitutionally valid outcome, not a FAIL.

Under this reading, TM-08-B's failure condition is not "evidence inaccessible" alone but specifically **"evidence inaccessible AND no constitutional recovery procedure exists."**

**Current architecture analysis — both conditions currently satisfied:**
- Condition A: No evidence accessibility mandate — **MET** (no ADR specifies this)
- Condition B: No constitutional recovery procedure (election void → rerun trigger) — **MET** (no ADR specifies what occurs when CA correctly refuses CO-5)

Both conditions are currently satisfied. The C-F is therefore functionally equivalent to F in the current architecture. The distinction is architecturally material: adding a constitutional recovery procedure (election void + rerun trigger with evidence accessibility corrected) would transform TM-08-B from C-F approaching F to C-F (detectable, recoverable). The real architectural problem is the absence of a rerun procedure, not merely the evidence inaccessibility per se.

**Evidence for Classification:**
- No ADR specifies evidence accessibility requirements for CA review.
- No ADR specifies what occurs when CA correctly refuses CO-5 (election void? rerun? permanent cancellation?).
- The architectural fix path: add evidence accessibility mandate (closes Condition A) + constitutional recovery procedure (closes Condition B).

*Note on Zero-FAIL discipline: after this reclassification, 38A-02 produces zero unconditional FAILs. See D.2 for zero-FAIL re-engagement.*

---

#### TM-08-C — Review Suppressed

**Attack Path:** CA's reviewing personnel, under organizational, political, or social pressure (SA-02), elect not to review evidence adverse to the election's preferred outcome. The suppression is informal — not a constitutional violation but a behavioral failure that produces constitutional consequences.

**Constitutional Defenses:** Named Attestation Model: suppression may be observable if there is an audit trail of what CA received vs. what CA attested to having reviewed.

**Defense Condition:** Suppression must produce observable evidence. If CA's reviewing personnel can suppress review without creating any observable record, the Named Attestation challenge path fails.

**Survivability Classification:** **C-F (Conditional Fail)**

Conditionally Fails when suppression leaves no observable evidence trail. The SA-02 dependency (Challenger Willingness Under Pressure) applies in the reverse: if internal CA reviewers are the potential challengers, pressure suppresses not just their review but also their willingness to report the suppression.

---

#### TM-08-D — Review Abandoned (Capacity/Time Failure)

**Attack Path:** CA begins reviewing the evidence package but cannot complete review within the certification window due to evidence volume, insufficient reviewer capacity, or technical failures. CA issues CO-5 with incomplete review rather than refusing to certify.

**Constitutional Defenses:** Named Attestation: an incomplete review should produce a CO-5 that covers only the reviewed evidence. If challengers can identify evidence that was not attested, they can file a challenge.

**Defense Condition:** OA-02 (Challenge Window Practical Sufficiency) — the time available for challengers to identify and challenge incomplete review must be practically sufficient. If CA's incomplete review leaves few hours for challenge, the challenge window may not provide meaningful protection.

**Survivability Classification:** **C-F (Conditional Fail)**

Conditionally Fails when CA's incomplete review is not detectable before challenge window closes.

#### TM-08 Aggregate Assessment

| Mode | Classification | Adversary Required | Primary Condition |
|---|---|---|---|
| TM-08-A | S-D / C-F | None / C | OA-01 resolution |
| **TM-08-B** | **C-F approaching F** | **None** | **No evidence accessibility mandate + no constitutional recovery procedure (both conditions currently met)** |
| TM-08-C | C-F | C/D (pressure source) | Suppression observability |
| TM-08-D | C-F | None | Incomplete review detectability before window closes |

**TM-08-B is a two-condition C-F with both conditions currently satisfied — functionally equivalent to F in the current architecture. The architectural response required: evidence accessibility mandate (Condition A) + constitutional recovery procedure / election rerun trigger (Condition B). (ARB Correction 2026-06-17)**

---

### B.7 TM-09 — Constitutional Drift

**Class:** 1 (Constitutional) | **Adversary:** D (Colluding Authorities), E (State-Level) — long horizon

*Note: TM-09 is unique in the catalog because no single amendment constitutes a FAIL. The failure accumulates across multiple election cycles. This makes it the hardest threat to classify using single-election survivability criteria.*

**Attack Path:**

1. Adversary D or E identifies that individual EC amendments are unlikely to trigger challenge or detection — each amendment appears defensible in isolation.
2. Over multiple election cycles (timeframe: years to decades for Adversary E; months to years for sustained Adversary D), adversary drives a sequence of individually legitimate amendments:
   - Cycle 1: Narrow standing threshold slightly (procedurally defensible — "improve quality of challenges")
   - Cycle 2: Reduce mandatory audit scope (procedurally defensible — "improve efficiency")
   - Cycle 3: Modify certification timeline (procedurally defensible — "align with operational capacity")
   - Cycle 4: Redefine evidence completeness standard (procedurally defensible — "clarify requirements")
3. No single amendment violates the constitutional architecture. Each passes CO-4 compliance against the then-current EC.
4. Cumulative effect: the EC that existed after 10 amendment cycles no longer provides meaningful constitutional guarantees. The architecture still functions formally; the guarantees have been eroded.

**Weakest Link (two simultaneous):**
- No constitutional floor: no provision specifying protections that cannot be removed by amendment. The EC is amendable without limit.
- No drift detection mechanism: no provision requiring constitutional actors to assess cumulative amendment effects. Each amendment is evaluated in isolation.

**Adversary Capability Threshold:**
- Per-amendment threshold: very low. Each individual amendment requires only sufficient MA support.
- Cumulative threshold: sustained organizational commitment across multiple election cycles.
- Adversary D: achievable within 3–5 election cycles with sustained organizational alignment.
- Adversary E: achievable within decades but with near-certainty given state-level resources and generational time horizon.

**Constitutional Defenses:** None. The amendment process (AA-03) is unspecified, which means there is no formal review mechanism that would require cumulative impact assessment. Each amendment is individually valid and individually unchallengeable.

**Defense Failures:**
1. No constitutional floor — rights and protections that are unamendable — exists in the ADR sequence. Any protection can be amended away.
2. No drift monitoring requirement. No body is constitutionally assigned to assess cumulative amendment effects.
3. Named Attestation challenges evaluate elections against the current EC. If EC has drifted, challenges evaluate compliance with a drifted standard.

**Survivability Classification:** **C-F (Conditional Fail)**

The architecture conditionally fails when cumulative amendments reduce constitutional guarantees below a threshold at which meaningful protection disappears. The condition is unspecifiable without a constitutional floor — because no floor exists, the failure threshold cannot be named.

This makes TM-09 a uniquely dangerous C-F: the failure condition is constitutionally unspecifiable. The architecture may be in a drifted failure state without any mechanism to detect or declare it.

**Evidence for Classification:**
- No ADR specifies unamendable constitutional protections.
- No ADR specifies cumulative amendment review obligations.
- The failure path — incremental individually-valid amendments — is reachable by any adversary with sustained MA access.
- Each individual step is constitutionally valid. Failure emerges only from the aggregate.

**Unresolved Questions:**
- Should Round 38A recommend a constitutional floor clause as an architectural requirement to close TM-09?
- Is the failure threshold for TM-09 expressible without an external constitutional standard?

---

### B.8 TM-10 — Phase Lock Attack

**Class:** 2 (Governance Capture) | **Adversary:** B (Malicious Official), C (Compromised Authority), E (State-Level)

**Attack Path:**

1. Adversary identifies that specific phase transitions are required for the election to proceed constitutionally: Voting → CertificationPending → CertificationWindowOpen → CertificationComplete.
2. Adversary targets the GovernanceState write mechanism for a specific transition — preventing that transition from being recorded without corrupting existing records.
3. Attack vectors:
   - Corrupting GovernanceState write access (Adversary B/C: insider)
   - Preventing quorum for any decision required to authorize the phase transition
   - Creating a constitutional dispute about whether conditions for the transition are met, with no interpretation authority to resolve it (connects to Gap 4)
   - Technical interference with GovernanceState infrastructure (Adversary E)
4. Election remains locked in the current phase indefinitely:
   - If locked in Voting phase: CertificationWindow never opens; CA cannot initiate certification; ADR6-INV-01 cannot be triggered; election cannot complete.
   - If locked before ChallengeWindowClose: challenge window remains permanently open; certification cannot finalize.
   - If locked in CertificationPending: CA cannot proceed; election cannot complete.

**Distinction from TM-03:** TM-03 corrupts existing records to manufacture a false phase history. TM-10 prevents new valid records from being written. The attack surface is the GovernanceState write mechanism, not the read mechanism. Detection profile is different: TM-10 is visible (phase doesn't advance) while TM-03 may be invisible (false records look valid).

**Weakest Link:** No constitutional override mechanism for GovernanceState write failure. If the phase machine cannot be written to, no authority has constitutional power to manually advance the phase without using GovernanceState. AA-07 (GovernanceState as sole record) means the override would itself be unconstitutional without GovernanceState confirmation.

**Adversary Capability Threshold:**
- Adversary B: insider with GovernanceState write access. Low capability.
- Adversary C: authority aggregate with administrative control. Moderate capability.
- Adversary E: infrastructure-level access. High capability but high effectiveness.

**Constitutional Defenses:** None specific to phase lock. ADR7-INV-01 (succession) handles authority suspension but not GovernanceState write failure. No constitutional provision specifies what happens when the phase machine fails.

**Defense Failures:**
1. No constitutional override: the architecture has no mechanism for authorities to declare an election's phase advanced independently of GovernanceState.
2. No constitutional failure declaration: the architecture has no mechanism to declare an election officially failed due to phase lock (as opposed to leaving it indefinitely locked).
3. Gap 4 (Constitutional Interpretation Authority) compounds TM-10: if the dispute about whether phase transition conditions are met cannot be authoritatively resolved, phase lock can be achieved through manufactured ambiguity rather than direct technical attack.

**Survivability Classification:** **C-F (Conditional Fail)**

The architecture conditionally fails if phase lock persists beyond the election window. Detection is possible (the phase simply doesn't advance — this is visible). But detection without remedy is not survivability: the architecture detects the attack and has no constitutional response.

**Evidence for Classification:**
- No constitutional provision addresses GovernanceState write failure.
- No constitutional mechanism exists for phase advancement independent of GovernanceState.
- The failure path is reachable by Adversary B (low capability insider).
- AA-07 (GovernanceState as sole record) amplifies this threat: there is no alternative phase authority to appeal to.

**Unresolved Questions:**
- Is there a constitutional mechanism to formally declare an election failed due to phase lock (enabling restart or remediation)?
- Can any authority constitutionally advance a phase independently of GovernanceState in emergency conditions?

---

### B.9 TM-12 — Challenge Window Attrition

**Class:** 2 (Governance Capture) | **Adversary:** B (Malicious Official), C (Compromised Authority), D (Colluding Authorities)

**Attack Path:**

1. A material challenge is filed against CO-2, CO-3, or CO-4. The adversary has interest in preventing this challenge from completing.
2. Adversary deploys procedural attrition mechanisms:
   - Files counter-challenges or jurisdictional objections against the challenger's standing (requires standing itself — Adversary D may have standing through a captured authority)
   - Demands extensive procedural prerequisites before CAB can rule on the substantive challenge
   - Files parallel challenges that consume CAB's adjudicative capacity
   - Demands access to evidence that challengers cannot independently obtain (OA-01 gap) — creating delays while access is negotiated
   - Introduces constitutional interpretation disputes about the challenge procedure itself (connects to Gap 4)
3. The challenge window closes with material challenges still pending as undecided.
4. Election is certified — CO-5 issued — with unresolved material constitutional questions.

**Distinction from TM-05:** TM-05 attacks possession of standing. TM-12 attacks exercise of standing. Challengers in TM-12 have valid standing and successfully filed — they cannot complete their challenge in time.

**Weakest Link:** OA-02 — *Challenge Window Practical Sufficiency*. ADR6-CONSTRAINT-01 specifies a minimum challenge window duration. This minimum is a constitutional guarantee — but constitutional minimum ≠ practical sufficiency under adversarial procedural attack. If adversary's procedural throughput exceeds CAB's adjudication capacity within the window, the challenge fails by attrition.

**Secondary Weakest Link:** GA-04 — *Standing Diversity Sufficiency*. If challengers are few and each must individually combat procedural attrition, their resource capacity is limited. Adversary can focus procedural pressure on individual challengers rather than the challenge system.

**Adversary Capability Threshold:**
- Moderate: requires access to procedural mechanisms (filing counter-challenges, demanding evidence) and coordination with others who have standing.
- Adversary D (colluding authorities): most effective — can deploy multiple procedural attacks simultaneously from multiple authority positions.
- Adversary B: lower effectiveness (individual actor) but can still consume CAB capacity with a single well-timed attrition campaign.

**Constitutional Defenses:**
- CAB independence (established in ADR-5): CAB should be able to manage its own docket.
- ADR7-INV-02: prevents standing manipulation — challengers cannot have standing revoked during the attack.
- Challenge window minimum (ADR6-CONSTRAINT-01): provides baseline time.

**Defense Failures:**
1. No constitutional provision protects the *rate of exercise* of standing — only the *possession* of standing.
2. No constitutional provision specifies minimum CAB processing capacity or maximum procedural complexity.
3. No constitutional provision for docket management under adversarial load — CAB could be constitutionally obligated to adjudicate every procedural motion, even frivolous ones.
4. OA-02 gap: the window minimum is not calibrated to adversarial procedural throughput.

**Survivability Classification:** **C-F (Conditional Fail)**

The architecture conditionally fails when adversary's procedural throughput exceeds CAB's adjudication capacity within the challenge window. The condition is not constitutionally bounded — no provision specifies what CAB capacity must be relative to challenge volume.

**Evidence for Classification:**
- No ADR specifies CAB adjudication capacity requirements.
- No ADR protects the exercise rate of standing.
- ADR6-CONSTRAINT-01 provides a window minimum but not a processing capacity minimum.
- The failure path is reachable by Adversary D at moderate capability.

**Unresolved Questions:**
- Can CAB issue summary rulings on procedural motions to clear adversarial attrition?
- Is there a constitutional mechanism to extend the challenge window when adversarial attrition is detected?

---

### B.10 TM-13 — Constitutional Ambiguity Exploitation

**Class:** 1 (Constitutional) | **Adversary:** B (Malicious Official), C (Compromised Authority), D (Colluding Authorities)

**Attack Path:**

1. Adversary identifies EC provisions that are ambiguous, silent on specific scenarios, or internally inconsistent.
2. Adversary selects an interpretation of the ambiguous provision that expands the adversary's authority, reduces constitutional constraints on the adversary, or restricts challenger rights.
3. Adversary acts on this interpretation. Constitutional actors (other authorities, challengers) dispute the interpretation.
4. No constitutional interpretation authority exists (Gap 4 from 38A-01 G.5) — no body is constitutionally designated to issue binding EC interpretations.
5. Multiple authorities operate under their own preferred interpretations simultaneously:
   - EnrollmentAuthority interprets EC eligibility provision one way
   - CertificationAuthority interprets the same provision differently
   - Challengers interpret it a third way
6. CO-3 procedural compliance becomes incoherent: which interpretation defines "procedural compliance"?
7. The Named Attestation's CO-3 section is contested but unadjudicable — no body can issue an authoritative ruling on what CO-3 required.

**Weakest Link:** Gap 4 — Constitutional Interpretation Authority. This gap exists before any adversary acts. TM-13 requires only that a genuine ambiguity exist in EC (which is not prevented by any constitutional provision) and that the gap (no interpretation authority) prevents resolution.

**Adversary Capability Threshold:**
- Very low: requires identifying an ambiguous EC provision and advancing an interpretation. No technical capability required.
- Adversary B or C with access to EC text and willingness to advance a self-serving interpretation.
- This is one of the lowest-threshold threats in the catalog because it requires only text exploitation, not constitutional violation.

**Constitutional Defenses:**
- ChallengeAdjudicationBody: could assume the interpretation role through precedent — CAB adjudicates challenges, and challenge adjudication necessarily involves interpreting EC provisions.
- Named Attestation: CO-3 challenges surface interpretation disputes.

**Defense Failures:**
1. CAB is not constitutionally designated as an interpretation authority. If CAB issues a ruling that involves EC interpretation, other authorities may dispute CAB's authority to interpret rather than merely adjudicate.
2. The same ambiguity that creates TM-13 affects CAB's ruling: CAB's ruling is itself an interpretation of an ambiguous provision. If that ruling is contested, no higher body can resolve the dispute.
3. AA-02 (EC Uniqueness): if interpretation disputes fracture the effective meaning of EC, AA-02's assumption breaks down functionally even while EC remains formally unique.

**Survivability Classification:** **C-F (Conditional Fail)**

The architecture conditionally fails when: (a) a genuine ambiguity exists in EC, AND (b) adversary advances a self-serving interpretation, AND (c) no body can authoritatively resolve the dispute. Condition (c) — Gap 4 — is already satisfied. The C-F condition reduces to: adversary must identify and exploit a genuine ambiguity. This is a low-threshold C-F.

**Evidence for Classification:**
- Gap 4 (Constitutional Interpretation Authority) is pre-existing — exists before any adversary acts.
- No ADR designates a body with binding EC interpretation authority.
- CAB's interpretation authority is implicit, not constitutionally explicit.
- The failure path requires only text exploitation — a very low adversary capability threshold.

**Unresolved Questions:**
- Is ChallengeAdjudicationBody constitutionally empowered to issue binding EC interpretations, or only to adjudicate compliance disputes?
- If CAB issues an EC interpretation and another authority disputes CAB's interpretation authority, what resolves the meta-dispute?

---

### B.11 TM-15 — Evidence Completeness Attack

**Class:** 4 (Evidence Manipulation) | **Adversary:** B (Malicious Official), C (Compromised Authority)

**Attack Path:**

1. Adversary engineers evidence absence. Required enrollment records for eligible voters are:
   - Never created (Adversary B: election official prevents record creation)
   - Deleted before AuditScopeAuthority activates (Adversary B/C)
   - Created in formats that don't satisfy AC-31 requirements (Adversary B: technical misconfiguration — possibly non-intentional)
   - Systematically incomplete for specific voter populations (selective disenfranchisement at the evidence layer)
2. AuditScopeAuthority activates. CO-2 (Evidence Completeness) requires that enrollment evidence exists for all eligible voters.
3. Evidence does not exist. CO-2 cannot be assessed.
4. CA faces a constitutional fork:

**Fork A — CA correctly applies ADR6-INV-01:**
- CO-2 is unassessable. CA refuses to issue CO-5.
- Election is permanently uncertified.
- This is constitutionally correct behavior — but produces an election failure outcome.
- The constitutional architecture has no remediation path: no mechanism to recreate missing evidence, extend the election, or declare partial certification.

**Fork B — CA incorrectly issues CO-5 despite unassessable CO-2:**
- CO-5 is issued with a false CO-2 attestation.
- Named Attestation Model provides challenge path if challengers can demonstrate CO-2 was unassessable.
- Challenge success depends on OA-01 (challenger evidence access): challengers must be able to access the evidence register to demonstrate absence.

**Weakest Link:** The constitutional architecture has no affirmative requirement to ensure evidence exists before the election begins. The architecture evaluates whether evidence is present when CO-2 is assessed — but it does not mandate evidence creation, maintenance, or format compliance as a pre-election requirement.

**Adversary Capability Threshold:**
- Low to moderate: Adversary B with administrative control over enrollment records can prevent record creation or delete records.
- Non-intentional failure: TM-15-B (records in wrong format) does not require adversary intent — it can be a genuine operational failure.

**Constitutional Defenses:**
- ADR6-INV-01: CA must refuse to certify if CO-2 is not satisfied.
- Named Attestation: CO-2 is challengeable if CA incorrectly attests it was satisfied.

**Defense Failures:**
1. **Fork A defense failure:** ADR6-INV-01 correctly prevents false CO-5, but produces election failure without remediation. This is constitutionally correct but practically disastrous. The defense succeeds at preventing certification failure but produces uncertification failure.
2. **Fork B defense failure:** Named Attestation challenge requires challenger evidence access (OA-01) to demonstrate absence — this is OA-01-dependent.
3. **Pre-election gap:** No constitutional provision requires evidence to exist and be accessible before the election begins. TM-15 exploits this pre-election gap.

**Survivability Classification:** **S-D (Fork A) / C-F (Fork B)**

- Fork A: **S-D** — CA correctly refuses to certify; election is detected as failed. But detection without remediation is a partial failure (election is permanently uncertified with no constitutional path to resolution).
- Fork B: **C-F** — CA issues false CO-5; challenge path available if OA-01 resolved; C-F if OA-01 unresolved.

**The Fork A problem is an architectural design gap:** constitutionally correct behavior (refuse to certify) produces an election that can never be completed. The architecture needs a constitutional procedure for this scenario.

**Evidence for Classification:**
- No ADR requires pre-election evidence creation verification.
- ADR6-INV-01 is a real defense against Fork B.
- The Fork A outcome (permanently uncertified election) is not addressed by any ADR.

**Unresolved Questions:**
- What is the constitutional procedure when an election cannot be certified due to evidence completeness failure? Is the election simply declared failed? Is there a re-run mechanism?
- Can evidence absence be detected before CO-2 assessment (pre-certification audit) rather than during certification?

---

## Part C — Additional Mandatory Analyses

### C.1 Information Environment Capture (TM-34)

**Class:** 7 (Composite) | **Adversary:** D (Colluding Authorities), E (State-Level)

**Context:** TM-34 is the intersection of information warfare and constitutional architecture. The attack does not require compromising any constitutional instrument — it targets the information environment in which constitutional actors operate.

**Attack Taxonomy:**

| Attack Form | Target | Constitutional Connection |
|---|---|---|
| Fake evidence publication | CA reviewers, challengers | OA-01 (evidence access) — cannot distinguish genuine from fake |
| Evidence flooding | CA review, CAB adjudication | TM-08-B interface — review impossibility |
| Disinformation campaigns | MA members, standing holders | SA-02 (willingness under pressure), AA-01 (MA legitimacy) |
| Observer confusion | Constitutional observers | TM-36 interface — observer reports become unreliable |
| Selective publication | Public perception, challengers | Challengers see partial evidence → incorrect CO-2/3/4 assessments |
| Manufactured distrust | MA, observers, challengers | Constitutional architecture produces valid CO-5 that no constituency accepts |

**The TM-08-B Interface:**

The most architecturally severe TM-34 attack form is evidence flooding. If adversary floods evidence channels with plausible-but-false versions of the audit record, CA cannot identify the genuine record for CO-2 assessment. This converts TM-34 → TM-08-B (Review Impossible). Since TM-08-B is already classified as **F (Fails)**, a TM-34 attack that successfully triggers TM-08-B conditions achieves a FAIL outcome.

**Constitutional Architecture Exposure:**
- Named Attestation Model assumes challengers and CA can identify genuine evidence. TM-34 information flooding attacks this assumption directly.
- OA-01 (Challenger Evidence Access) assumes an evidence environment where genuine evidence is distinguishable. TM-34 attacks this assumption.
- AC-31 (reference standard) authenticates genuine evidence — but AC-31 authentication requires that the authentic evidence record can be located. If flooding makes the authentic record indistinguishable, AC-31 cannot function even if the authentic record is technically present.

**Constitutional Defenses:**
- AC-31 reference standard is the primary defense: it should be able to authenticate the genuine evidence record against fakes.
- Named Attestation: CA's attestation is challengeable — if evidence flooding is detected, challengers can challenge CA's ability to have reviewed evidence meaningfully.

**Defense Failures:**
1. AC-31 governance is unspecified (AA-06, Gap analysis below). If AC-31 reference standard is itself compromised (TM-19), AC-31 authentication fails under flooding.
2. OA-01 gap: challenger evidence access is unspecified. Challengers cannot challenge CA's review quality if they cannot independently access and authenticate the evidence.
3. The architecture has no information environment governance. No provision addresses adversarial flooding as a distinct attack class.

**Survivability Classification:** **C-F (with TM-08-B interface → F when flooding triggers review impossibility)**

- **C-F** when flooding degrades CA review quality but does not make review impossible
- **F** (via TM-08-B) when flooding makes it impossible for CA to identify genuine evidence — at this point TM-34 becomes a trigger for an existing FAIL classification

**Evidence:** No ADR specifies an information environment defense. AC-31 is the authentication layer but its governance is unresolved (AA-06). The TM-08-B interface is established above.

**TM-34 Sub-Threats (ARB Expansion — 2026-06-17):**

| Sub-Threat | Form | Constitutional Interface | Severity |
|---|---|---|---|
| **TM-34A** | Evidence Flooding | Overwhelming CA/CAB evidence review channels with plausible-but-false packages; activates TM-08-B (Review Impossible) as a trigger effect | Critical — direct FAIL path via TM-08-B |
| **TM-34B** | Evidence Shadow Copies | Publishing authentic-seeming copies of the evidence record with falsified content; AC-31 cannot authenticate the genuine record when multiple copies claim equal authenticity | High — AC-31 authentication failure |
| **TM-34C** | False Observer Networks | Flooding CAB with adversary-controlled S-2 standing challenges, exhausting adjudication capacity and displacing legitimate challenges (direct TM-12 amplifier) | High — challenge architecture degraded |
| **TM-34D** | Synthetic Evidence Campaigns | Fabricating election participation records, audit trails, or certification correspondence that pass AC-31 format validation but contain false content | Medium-High — CO-2/CO-3 assessment polluted |
| **TM-34E** | AI-generated Evidence Manipulation | Machine-generated content (voter attestations, audit records) at volume that defeats human CA review capacity even when AC-31 format authentication succeeds | Medium — exploits review capacity gap |

**Sub-threat priority:** TM-34A → FAIL path via TM-08-B. TM-34C → C-F path via TM-12. Full sub-threat evaluation deferred to 38A-05/38A-06.

---

### C.2 Legitimacy Narrative Attack (TM-35)

**Class:** 1 (Constitutional / Pre-Constitutional) | **Adversary:** D (Colluding Authorities), E (State-Level)

**Pre-Constitutional Exposure Notice:** TM-35 targets the Source-of-Authority Layer (G.6). AA-01 (MA Legitimacy) is unresolved. This makes TM-35 partially a pre-constitutional attack.

**Attack Taxonomy:**

| Attack Form | Target | Constitutional Anchoring |
|---|---|---|
| MA legitimacy challenge | "MA is not representative of actual diaspora membership" | AA-01 (unresolved) — architecture cannot rebut this |
| EC legitimacy challenge | "EC was written by a captured MA" | TM-07 interface — if MA was captured, EC is tainted |
| CA legitimacy challenge | "CA is politically biased toward one faction" | AA-04 (CO-4 circularity risk) — CA evaluates compliance with EC that CA's faction captured |
| Result rejection | "The CO-5 certification cannot be trusted" | Reputational failure without constitutional mechanism |

**Constitutional Architecture Response to Each Attack:**

1. **MA legitimacy challenge:** The constitutional architecture cannot rebut this. AA-01 is unresolved — there is no constitutional standard for MA legitimacy. The architecture assumes MA's legitimacy; it cannot prove it. TM-35 attacks the architecture's own assumption.

2. **EC legitimacy challenge:** The Named Attestation and challenge architecture can demonstrate that the EC was followed. They cannot demonstrate that the EC itself is legitimate. CO-4 evaluates EC compliance, not EC legitimacy.

3. **CA legitimacy challenge:** CA independence is constitutionally established (ADR-2). But constitutional independence is not the same as perceived independence. TM-35 can successfully attack CA's perceived legitimacy even if CA's constitutional independence is intact.

4. **Result rejection:** CO-5 is constitutionally valid. TM-35 can produce a scenario where CO-5 is constitutionally valid and publicly rejected simultaneously. The constitutional architecture has no mechanism to address this gap.

**Constitutional Defenses:**
- The Named Attestation Model provides a transparency mechanism: all CO-2/CO-3/CO-4 attestations are individually verifiable.
- Challenge architecture allows challengers to test every attestation.

**Defense Failures:**
1. Transparency and technical rebuttability do not address narrative attacks. A successful TM-35 attack is not rebutted by showing the technical attestations are valid — the attack is about whether those attestations represent a legitimate process.
2. AA-01 is the constitutional gap that TM-35 exploits. The architecture cannot close TM-35 without resolving AA-01.
3. SA-02 (Challenger Willingness Under Pressure) is relevant in reverse: if TM-35 successfully manufactures distrust, challengers who would otherwise file challenges may not do so because they believe the process is already captured.

**Survivability Classification:** **A-D (Assessment Deferred — Pre-Constitutional Exposure)**

TM-35 attacks the Source-of-Authority Layer. The constitutional architecture produces a valid CO-5 while TM-35 may succeed. This is not a constitutional FAIL in the technical sense — no constitutional invariant is violated. But it is a pre-constitutional exposure: the architecture's constitutional validity is independent of its perceived legitimacy, and TM-35 targets that perceived legitimacy where AA-01 provides no foundation.

**38A-06 Classification:** Pre-constitutional exposure requiring institutional legitimacy work beyond the constitutional architecture. The architecture cannot close TM-35 through constitutional specification alone.

**Escalation Condition:** TM-35 escalates from A-D to C-F if narrative attack prevents a sufficient fraction of standing holders from exercising their rights (SA-02 dependency). If enough challengers are deterred by manufactured distrust, the challenge architecture's protective function degrades even though challengers technically retain standing.

---

### C.3 AC-31 Reference Standard Governance Analysis

**Context:** Per ARB direction, this is a structural gap analysis, not a threat evaluation. TM-19 (AC-31 Reference Standard Capture) is assigned to 38A-03. This analysis addresses the governance question: who has constitutional responsibility for AC-31?

**Current State of AC-31 Governance:**

| Governance Function | Constitutional Specification | Status |
|---|---|---|
| Who governs the reference standard? | Not specified in any ADR | **UNSPECIFIED** |
| Who certifies it is authoritative? | Not specified; CA governs CO-5, not AC-31 | **UNSPECIFIED** |
| Who can challenge it? | Challenge architecture (ADR-5) covers D43 authorities; AC-31 is not a D43 authority | **NO MECHANISM** |
| Who replaces it if captured or compromised? | No succession mechanism | **NO MECHANISM** |
| Who authenticates it? | The reference standard authenticates evidence; who authenticates the standard? | **CIRCULAR** |

**The Concentration Risk:**

AC-31 is more dangerous than any individual D43 authority aggregate because it affects ALL elections simultaneously. A captured D43 authority threatens one election cycle. A captured AC-31 reference standard threatens every election's evidence authenticity retroactively and prospectively.

The seven D43 authority aggregates each have constitutional governance provisions (ADR-1 through ADR-7), independence requirements (ADR-2), succession provisions (ADR-7), and challenge mechanisms (ADR-5). AC-31 has none of these.

**The Authentication Circularity:**

AC-31 is the reference standard for authenticating evidence. But who authenticates AC-31? If AC-31 requires external authentication, that external authority is a new concentration point. If AC-31 is self-authenticating, it is a single point of failure with no external verification. Either path creates architectural risk that has not been analyzed.

**Gap 5 Formally Elevated (ARB 2026-06-17):** AC-31 Reference Standard Governance is now formally registered as Gap 5 in the structural gaps register (38A-01 G.5 updated). It carries equal weight to Gaps 1–4. TM-19 (Authority Capture — evaluated in 38A-03) attacks a constitutionally ungoverned concentration point; its classification is therefore constrained by Gap 5 resolution status and must explicitly address this dependency.

**Classification:** **A-D (Assessment Deferred — requires constitutional specification before evaluation)**

The AC-31 governance gap cannot be classified as Survives or Fails until the governance structure is specified. Without governance specification, TM-19 (evaluated in 38A-03) is evaluating a capture threat against an unspecified target — making TM-19's assessment itself dependent on this gap's resolution.

---

### C.4 Constitutional Self-Destruction Analysis *(ARB-Required Addition — 2026-06-17)*

**Core Question:** Can the ElectionConstitution legally remove all meaningful constitutional protections through valid amendments?

**Structural Inputs:**
- AW-02-01: No EC floor — any protection can be amended away
- AW-02-04: EC Amendment Process undefined (AA-03)
- TM-01: Single-cycle overt capture of EC via MA amendment threshold
- TM-09: Multi-cycle drift via individually-valid amendment sequence

**Self-Destruction Sequence Analysis:**

| Step | Action | Constitutional Validity | Remaining Protections |
|---|---|---|---|
| 0 | Baseline EC | — | All protections intact |
| 1 | Amendment: narrow standing thresholds | Valid per CO-4 against then-current EC | ADR-5 challenge architecture weakened |
| 2 | Amendment: remove mandatory challenge filing procedures | Valid per CO-4 | ChallengeAdjudicationBody constrained |
| 3 | Amendment: remove CA independence requirements | Valid per CO-4 | ADR-2 independence invariants removed |
| 4 | Amendment: remove evidence completeness requirements | Valid per CO-4 | ADR6-INV-01 removed |
| Final | EC as administrative certificate only | Valid at each step; cumulative = complete dissolution | None |

**Conclusion: YES.** The constitutional architecture can legally self-destruct through a sequence of individually-valid amendments. No invariant across any ADR prevents this. Each step passes CO-4 compliance against the then-current EC.

**Real constitutional systems' solutions:**

| Mechanism | Description | Status in Architecture |
|---|---|---|
| Eternity Clause | Named provisions declared unamendable (e.g., German Basic Law Art. 79(3)) | Not specified |
| Basic Structure Doctrine | Judicial identification of unamendable constitutional core (India: Kesavananda, 1973) | No interpretation authority exists (Gap 4) |
| Constitutional Floor | Minimum protection set below which no amendment may reach | Not specified |
| Supermajority Requirement | Qualified majority + ratification for constitutional changes | Not specified (AA-03 undefined) |

**Impact on TM-01 and TM-09:**

TM-01 (single-cycle overt capture) and TM-09 (multi-cycle drift) are both manifestations of the same underlying gap: the architecture has no unamendable constitutional core. TM-01 is the fast path; TM-09 is the slow path. The Constitutional Self-Destruction analysis reveals that both C-F findings express the same structural vulnerability:

- **TM-01 classification unchanged (C-F):** requires adversary action (MA amendment threshold). But the failure scenario is not "capture of one protection" — it is the first step of potential constitutional self-destruction.
- **TM-09 classification unchanged (C-F):** the failure threshold remains unspecifiable because no floor exists. The self-destruction analysis confirms that the architecture cannot name its minimum viable constitutional state.

Neither TM-01 nor TM-09 is elevated to unconditional F: both require adversary action (achieving MA amendment threshold). However, given sustained adversary pressure, constitutional dissolution is the architecturally guaranteed eventual outcome.

**New Weakness:**

| Weakness ID | Description | Threat(s) | Severity |
|---|---|---|---|
| AW-02-11 | No constitutional self-destruction prevention — no EC floor, no eternity clause, no unamendable core, no basic structure doctrine | TM-01, TM-09 | Critical |

**Recommended architectural response:** Define a minimum constitutional floor (set of protections unamendable by any MA majority) and designate an authority (or supermajority threshold) whose sole function is to verify that proposed amendments do not breach the floor.

---

## Part D — Constitutional Failure Matrix

*Deliverable for Round 38A-02*

| Threat | Class | Classification | Failure Condition | Key Constitutional Gap | Adversary Required |
|---|---|---|---|---|---|
| TM-01 Constitution Capture | 1 | **C-F** | Adversary achieves MA amendment majority | AA-03: amendment process unspecified; no EC floor | D/E |
| TM-03 GovernanceState Corruption | 4 | **C-F** | GovernanceState corrupted; no independent corroboration | AA-07: sole record; OA-01: challenger evidence circular | B/C/E |
| TM-05 Standing Manipulation | 2 | **S-D → C-F** | CAB accessible (S-D) / CA controls filing (C-F) | OA-03: ChallengeReceptionFunction independence unspecified | C/D |
| TM-07 Membership Capture | 1 | **C-F** (pre-constitutional) | Adversary achieves MA threshold; threshold unnameable | AA-01/AA-03 both unresolved; Source-of-Authority Layer | D/E |
| TM-08-A False Attestation | 2 | **S-D / C-F** | OA-01 resolved (S-D) / unresolved (C-F) | OA-01: challenger evidence access unspecified | None / C |
| **TM-08-B Review Impossible** | **2** | **C-F approaching F** | **No evidence accessibility mandate + no recovery procedure (both currently met)** | **AW-02-02 (no accessibility mandate); AW-02-07 (no election void/rerun procedure)** | **None** |
| TM-08-C Review Suppressed | 2 | **C-F** | Suppression observability | SA-02: behavioral precondition | C/D |
| TM-08-D Review Abandoned | 2 | **C-F** | Incomplete review detectable before window closes | OA-02: challenge window practical sufficiency | None / B |
| TM-09 Constitutional Drift | 1 | **C-F** | Cumulative amendments erode guarantees; threshold unspecifiable | No constitutional floor; no drift detection | D/E (long-horizon) |
| TM-10 Phase Lock Attack | 2 | **C-F** | Phase lock persists beyond window; no override | AA-07: sole record; no override mechanism | B/C/E |
| TM-12 Challenge Window Attrition | 2 | **C-F** | Adversary procedural throughput > CAB capacity | OA-02: window sufficiency; no exercise-rate protection | B/C/D |
| TM-13 Constitutional Ambiguity | 1 | **C-F** | Genuine ambiguity + no interpretation authority | Gap 4: no interpretation body designated | B/C/D |
| TM-15 Evidence Completeness | 4 | **S-D (Fork A) / C-F (Fork B)** | CA refuses (S-D, no remediation) / certifies (C-F) | No pre-election evidence mandate; no uncertification procedure | B/C |
| TM-34 Info Environment Capture | 7 | **C-F → F** (if TM-08-B triggered) | Flooding makes genuine evidence unidentifiable | AC-31 governance unresolved; OA-01 gap | D/E |
| TM-35 Legitimacy Narrative Attack | 1 | **A-D** (pre-constitutional) | Attacks Source-of-Authority Layer | AA-01 unresolved; no institutional legitimacy mechanism | D/E |
| AC-31 Governance | — | **A-D** (Gap 5) | Governance structure unspecified | No constitutional AC-31 governance | — |

### D.1 Summary Counts

*(After ARB Correction 2026-06-17 — TM-08-B reclassified)*

| Classification | Count | Threats |
|---|---|---|
| F (Fails) | **0** | *(TM-08-B reclassified; see note below)* |
| C-F approaching F | **1** | TM-08-B (both conditions currently met — functionally F in current architecture) |
| C-F (Conditional Fail) | **10** | TM-01, TM-03, TM-09, TM-10, TM-12, TM-13, TM-08-C/D, TM-34, TM-15(B) |
| S-D (Survives with Detection) | **1** | TM-05 (conditional), TM-15(A) |
| A-D (Assessment Deferred) | **2** | TM-35, AC-31 Gap |
| S-D → C-F (conditional) | **2** | TM-05, TM-08-A (OA-01 dependent) |

### D.2 Zero-FAIL Challenge

Per 38A-01 Part A.4: a finding of zero unconditional FAIL is suspicious and must be challenged.

**ARB Correction (2026-06-17):** TM-08-B reclassified from unconditional F to C-F approaching F. After correction, Round 38A-02 produces zero unconditional FAILs. The zero-FAIL hypothesis is therefore re-engaged.

**Zero-FAIL defense for 38A-02 — three grounds:**

1. **TM-08-B is a C-F with zero conditional slack:** Both conditions (no evidence accessibility mandate; no constitutional recovery procedure) are currently satisfied. The C-F is functionally equivalent to F in the current architecture. The reclassification reflects that adding a constitutional recovery procedure would transform the failure mode — not that the architecture is currently safe.

2. **Eleven C-F conditions across the catalog, two with all conditions currently met:** TM-03 (all three C-F conditions architecturally satisfied) and TM-13 (Gap 4 pre-existing; only adversary-identified ambiguity needed) are both candidates for unconditional FAIL elevation. The absence of a FAIL verdict in 38A-02 reflects the adversarial standard: an unconditional FAIL requires that no adversary action is needed to trigger failure. Both TM-03 and TM-13 still require adversary action.

3. **Constitutional Self-Destruction (C.4) adds AW-02-11:** The architecture is structurally self-annihilating under sustained adversary pressure. This finding is more severe than a single-threat FAIL — it identifies a structural disposition toward complete constitutional dissolution.

The zero-FAIL discipline is satisfied: 38A-02 produced substantial adversarial findings without artificially elevating conditional failures to unconditional FAILs. The question of FAIL elevation for TM-03 and TM-13 remains open for 38A-06 synthesis.

**Candidates for FAIL elevation (38A-06):**
- **TM-03:** All three C-F conditions (GovernanceState corrupted + no independent record + CA evaluates against corrupted record) are currently architecturally satisfied. This C-F may be an unconditional FAIL for the class of adversaries with GovernanceState write access.
- **TM-13:** Gap 4 (no constitutional interpretation authority) is pre-existing. Only adversary-identified ambiguity is needed. In any real EC text, genuine ambiguity is near-certain. This C-F may be equivalent to unconditional.

### D.3 Architectural Weakness Register (38A-02 Findings)

| Weakness ID | Description | Threat(s) | Severity |
|---|---|---|---|
| AW-02-01 | No constitutional EC floor — any protection can be amended away | TM-01, TM-09 | Critical |
| AW-02-02 | No evidence accessibility mandate — CA review can be structurally impossible | TM-08-B | Critical (FAIL) |
| AW-02-03 | GovernanceState is sole phase record with no independent corroboration | TM-03, TM-10 | High |
| AW-02-04 | EC Amendment Process undefined (AA-03) | TM-01, TM-07, TM-09 | Critical |
| AW-02-05 | No constitutional interpretation authority (Gap 4) | TM-13, TM-10 (compound) | High |
| AW-02-06 | ChallengeReceptionFunction independence unspecified (OA-03) | TM-05, TM-12 | High |
| AW-02-07 | No constitutional procedure for permanently uncertified elections | TM-08-B, TM-15 | High |
| AW-02-08 | No exercise-rate protection for challenge standing | TM-12 | Medium |
| AW-02-09 | AC-31 governance completely unspecified (Gap 5) | TM-34, TM-19 | Critical |
| AW-02-10 | MA legitimacy unresolved (AA-01) — Source-of-Authority Layer exposed | TM-07, TM-35 | Critical |
| AW-02-11 | No constitutional self-destruction prevention — no EC floor, no eternity clause, no unamendable core, no basic structure doctrine | TM-01, TM-09 | Critical |

---

## Part E — ARB Decision Block

### Document Status

**[APPROVED WITH TARGETED CORRECTIONS APPLIED — 2026-06-17]**

*Five ARB-directed corrections applied:*
*1. TM-08-B reclassified from F to C-F approaching F (recovery path analysis added)*
*2. Gap 5 formally elevated to structural gaps register (38A-01 G.5 updated)*
*3. TM-34 expanded with sub-threats TM-34A through TM-34E*
*4. Constitutional Self-Destruction Analysis added (C.4 — AW-02-11)*
*5. TM-07 and TM-35 pre-constitutional carry-forward requirement formalized (see below)*

### Findings Summary

1. **Zero unconditional FAILs after ARB correction:** TM-08-B reclassified to C-F approaching F — both conditions currently met; functionally equivalent to F in the current architecture but addressable via targeted architectural additions.

2. **Eleven Conditional Fails identified:** TM-01, TM-03, TM-05(C-F branch), TM-07, TM-08-A/C/D, TM-09, TM-10, TM-12, TM-13, TM-15(B), TM-34 — each with named threshold conditions.

3. **Two Conditional Fails are candidates for FAIL elevation:** TM-03 (three conditions all currently satisfied) and TM-13 (Gap 4 pre-existing; only adversary-identified ambiguity needed). Open for 38A-06 ruling.

4. **Two pre-constitutional exposures — MANDATORY 38A-06 CARRY-FORWARD:** TM-07 (MA Capture) and TM-35 (Legitimacy Narrative Attack) attack the Source-of-Authority Layer that predates the constitutional architecture. These are NOT out-of-scope — they are pre-constitutional exposures that must be carried into 38A-06 Final Verdict with equal visibility to constitutional findings. The classification "pre-constitutional exposure" means the constitutional architecture cannot close them alone; it does not mean they are low-priority or deferred.

5. **Gap 5 formally elevated:** AC-31 Reference Standard Governance — formally added to structural gaps register (38A-01 G.5, 2026-06-17). Carries equal weight to Gaps 1–4.

6. **Constitutional Self-Destruction identified (C.4):** EC can legally remove all constitutional protections through valid amendments. AW-02-11 added. TM-01 and TM-09 are both manifestations of this structural vulnerability. Recommended architectural response: minimum constitutional floor with unamendable core provisions.

7. **Eleven architectural weaknesses documented** in the Architectural Weakness Register (AW-02-01 through AW-02-11).

### Observations

**OBS-38A02-01: TM-08-B Is the Dominant Architectural Finding**
An election architecture that cannot guarantee evidence accessibility for CA review has a structural failure mode that exists before any adversary acts. This is the highest-priority finding from 38A-02. It connects to TM-15 (evidence completeness), TM-34 (information environment flooding), and the broader question of what constitutional guarantees CA review actually provides.

**OBS-38A02-02: TM-03 May Be Unconditional Fail**
The three conditions for TM-03 Conditional Fail (GovernanceState corrupted + no independent record + CA evaluates against corrupted record) are all architecturally satisfied by the current design. This C-F may be equivalent to an unconditional FAIL for the class of adversaries with GovernanceState write access. 38A-06 should evaluate whether TM-03 should be elevated.

**OBS-38A02-03: Pre-Constitutional Exposures Require Institutional Response**
TM-07 and TM-35 cannot be closed by constitutional specification alone. They attack the legitimacy foundation of the architecture's constitutional instruments. The ARB should determine whether institutional legitimacy governance (outside the constitutional architecture) is within 38A scope or is deferred to a future program phase.

**OBS-38A02-04: Gap 5 Elevates TM-19 Severity**
AC-31 governance being entirely unspecified means TM-19 (AC-31 Reference Standard Capture, evaluated in 38A-03) may be evaluable only as A-D until Gap 5 is resolved. 38A-03 should explicitly evaluate whether TM-19 is assessable given Gap 5.

### Open Questions for Round 38A

**OQ-38A02-01:** Should TM-03 be elevated from C-F to F given that all three C-F conditions are currently architecturally satisfied?

**OQ-38A02-02:** Should TM-13 be elevated from C-F to near-certain-FAIL given that Gap 4 is pre-existing and genuine EC ambiguity is near-certain in any real constitutional text?

**OQ-38A02-03:** Do TM-07 and TM-35 (pre-constitutional exposures) require institutional legitimacy governance mechanisms as architectural requirements, or are they accepted as residual risk outside the constitutional architecture's scope?

**OQ-38A02-04:** ~~Should Gap 5 (AC-31 governance) be added to the structural gaps register?~~ **RESOLVED — Gap 5 formally elevated to structural gaps register (38A-01 G.5) per ARB ruling 2026-06-17.**

### Authorization Status

Per ARB ruling 2026-06-17 (APPROVED WITH TARGETED CORRECTIONS):

1. ✅ Constitutional Failure Matrix accepted as 38A-02 deliverable
2. ✅ TM-08-B reclassified to C-F approaching F — accepted
3. **Open (38A-06):** TM-03 and TM-13 FAIL elevation candidates — deferred to 38A-06 synthesis
4. ✅ TM-07 and TM-35: pre-constitutional exposures — MUST be carried into 38A-06 with equal visibility
5. ✅ Gap 5 formally elevated
6. ✅ Round 38A-03 Authorized — Authority Capture & Collusion Threats

---

**Authors:**
- Senior Election Security Architect
- Constitutional Governance Architect
- Threat Modeling Specialist (adversarial)
- DDD Architect

**Round 38A Program Status:**
*38A-01 APPROVED WITH STRATEGIC CORRECTIONS*
*38A-02 APPROVED WITH TARGETED CORRECTIONS APPLIED*
*38A-03 IN PROGRESS — Authority Capture & Collusion Threats*
*38A-04 through 38A-06 — Awaiting Completion*
*Next Phase (after 38A-06): Round 38B — Technical Architecture — AUTHORIZATION NOT YET GRANTED*
