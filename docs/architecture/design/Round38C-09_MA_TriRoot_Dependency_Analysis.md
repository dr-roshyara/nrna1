# Round 38C-09 — MA Tri-Root Dependency Analysis

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38C-09 — MA Tri-Root Dependency Analysis
**Status:** EVIDENCE DOCUMENT — NO RECOMMENDATIONS ISSUED
**Purpose:** Determine whether Trust Root Separation meaningfully reduces concentration risk when all three roots ultimately trace to Membership Assembly (MA). Analyze the source-of-source dependency and its constitutional implications.
**Authorized by:** Senior Architect Review following 38C-06 Classification Ruling
**Date:** 2026-06-19

---

## Part A — Scope and Constraints

### A.1 — What This Document Is

This document analyzes whether the structural differentiation of the three trust roots (achieved through 38B-01/02/03) provides meaningful constitutional protection against concentration risk, given that all three roots ultimately derive their authority from MA. It models the source-of-source dependency, its interactions with F-4/TM-39 and AA-01, and its constitutional implications.

### A.2 — What This Document Is Not

This document does NOT:
- Evaluate OQ-38B05-05
- Recommend any outcome for OQ-38B05-05
- Issue constitutional rulings
- Assign constitutional amendment tiers
- Create new constitutional principles
- Perform EC extension

OQ-38B05-05 remains NOT RESOLVED and MANDATORY PRIMARY REQUIREMENT throughout.
OQ-38A05-02 remains PROTECTED throughout.

### A.3 — Definitional Basis

**MA (Membership Assembly):** The constitutional body representing the membership of the NRNA. MA has accumulated 15 constitutional functions through the 38B specification cycle (OBS-38B04-02). MA is the source-of-source for all three trust roots (OBS-38B02-01, OBS-38B03-SA1). MA's own legitimacy is subject to AA-01 (pre-constitutional legitimacy of MA itself — unresolved).

**Source-of-Source Dependency:** Each trust root has a source — the constitutional body or instrument that governs it (EC for Legitimacy; AC-31 qualified by MA for Authenticity; GovernanceState specified through EC and corroborated by multi-party for Temporal). The source of each of these sources ultimately traces to MA: EC exists because MA ratifies amendments to it; AC-31 exists because MA designates its holders; GovernanceState governance exists because EC (ratified by MA) specifies it.

**Three-Root Separation (as designed in 38B):** The three roots have distinct governance mechanisms — CIC interprets EC (38B-01); multi-party tiered governance governs AC-31 (38B-02); multi-party corroboration with EC-anchored phase specification governs GovernanceState (38B-03). These are constitutionally distinct mechanisms that each trace separately to MA.

---

## Part B — Source-of-Source Dependency Model

### B.1 — The Three-Root Authority Chain

For each root, the authority chain terminates at MA:

**Legitimacy Root (EC):**
```
EC constitutional provisions
    ← EC amendments ratified by MA (38B05-INV-01 / Tier 1/2/3 process)
    ← MA constitutional ratification authority (Function #15 — OBS-38B05-02)
    ← AA-01 (MA's pre-constitutional legitimacy — UNRESOLVED)
```

**Authenticity Root (AC-31):**
```
AC-31 reference standard (specific qualified instance)
    ← MA designation/revocation (Tier 2 governance — 38B-02)
    ← MA constitutional authority to designate AC-31
    ← EC provision granting MA this authority
    ← MA constitutional ratification of EC (circular — but necessarily so in sovereign assembly model)
    ← AA-01 (MA's pre-constitutional legitimacy — UNRESOLVED)
```

**Temporal Root (GovernanceState):**
```
GovernanceState phase records
    ← EC-anchored phase specification (what phases exist — 38B-03)
    ← Multi-party corroboration (operational independence from any single actor)
    ← EC provisions governing phase specification
    ← MA constitutional ratification of EC
    ← AA-01 (MA's pre-constitutional legitimacy — UNRESOLVED)
```

### B.2 — The Separation Layer and the Source Layer

The three trust roots are separated at the **governance layer** — they are governed by distinct mechanisms (CIC, AC-31 multi-party, GovernanceState corroboration). But at the **source layer** — the ultimate constitutional authority that grants legitimacy to each governance mechanism — all three trace to MA.

This creates a two-level structure:

**Level 1 — Governance Layer (differentiated):**
- Legitimacy Root: CIC interpretation; CIC appointed by MA (38B-04); independence from interpretive instruction
- Authenticity Root: Multi-party tiered governance; MA designation; existing aggregates for Tier 3 verification
- Temporal Root: GovernanceState multi-party corroboration; EC-anchored specification; CIC constitutional challenge resolution

**Level 2 — Source Layer (converged):**
- All three roots derive constitutional authority from MA
- MA ratifies the EC that governs CIC and GovernanceState specification
- MA designates AC-31 holders at Tier 2
- MA appoints all authority aggregates that participate in each root's governance (38B-04)

### B.3 — The Analogy: Rivers from a Single Spring

A useful structural analogy (evidence only, not constitutional determination):

**Three separate rivers from three separate springs:** Each river has an independent water source. Contaminating one spring does not contaminate the others. Root separation would be constitutionally independent.

**Three separate rivers from a single spring:** Each river has independent channels, banks, and flow patterns. But they all originate at the same source. If the spring is contaminated, all three rivers are eventually affected, regardless of how independently they flow downstream.

The NRNA trust root architecture, as designed in 38B, resembles the second model: three rivers (roots) with independent channels (governance mechanisms) but a common spring (MA). The independence of the channels is real and meaningful at the governance layer. The common source is also real and creates a different category of concentration risk.

This analogy does not determine whether root separation is meaningful — it characterizes what "meaningful" should mean in the context of a tri-root dependency analysis.

---

## Part C — Sovereign Concentration Analysis

### C.1 — MA Constitutional Function Inventory (Post-38B)

MA holds 15 constitutional functions (OBS-38B04-02):

**Pre-38B functions (8):**
1. EC amendment ratification
2. CIC appointment
3. CAB appointment
4. CertificationAuthority appointment
5. CriteriaAuthority appointment (updated in 38B-04)
6. GovernanceAuthority appointment (updated in 38B-04)
7. AuditScopeAuthority + AuditExecutionAuthority appointment (updated in 38B-04)
8. ChallengeAdjudicationBody appointment (updated in 38B-04)

**38B-added functions (7):**
9. AC-31 Tier 2 designation and revocation (38B-02)
10. CIC constitutional interpretation of AC-31 constitutional qualifications (via CIC appointment — 38B-01 + 38B-02)
11. GovernanceState phase specification ratification (via EC amendment ratification — 38B-03)
12. Successor designation for critical functions (Option B — ADR7-INV-01)
13. EC amendment ratification formally specified and tiered (Function #15 as distinct function — OBS-38B05-02)
14. CriteriaAuthority appointment (explicit governance specification — 38B-04)
15. EnrollmentAuthority appointment via CriteriaAuthority (indirect — 38B-04)

**Summary:** MA holds appointment authority over 6/7 authority aggregates directly; holds AC-31 designation/revocation authority; holds EC amendment ratification authority; holds phase specification ratification authority (via EC); holds successor designation authority.

### C.2 — MA as Constitutional Concentration Point

The 38B-06 Synthesis (OBS-38B06-01) identified that 38B reduced operational concentration while increasing sovereign concentration. MA's functions were 8 pre-38B; they are 15 post-38B.

The OBS-38B04-02 (Primary Finding) characterized this as: "250% increase in MA functions during 38B cycle; TM-07 consequence = comprehensive constitutional governance capture."

At 15 functions, MA is the constitutional concentration point that:
- Appoints all authority aggregates
- Ratifies all EC amendments (including those that would change the roots' governance)
- Designates AC-31 holders (Authenticity Root source)
- Ratifies GovernanceState phase specification (Temporal Root specification source, via EC)

This means: controlling MA constitutionally controls the appointment of all bodies that govern each root's governance mechanism AND the authority to amend the rules governing each root.

### C.3 — What Root Separation Provides Despite MA Convergence

Despite the MA source-of-source convergence, three-root separation provides constitutional value at the governance layer:

1. **Coalition size requirement:** Under root separation, compromising the Authenticity Root (AC-31) requires different constitutional actors than compromising the Legitimacy Root (EC CIC). The actors governing each root are distinct (though all appointed by MA). A coalition that captures CIC (interpretive) does not automatically capture AC-31 governance actors (multi-party threshold) without separately coordinating with those actors.

2. **Detection through cross-root verification:** If the three roots have independent governance mechanisms, an actor that has compromised one root cannot use that root's governance to obscure the compromise from the other roots' governance actors. Cross-root verification requires coordinating three independent compromises.

3. **Temporal disjunction of compromise:** Because each root's governance requires different constitutional actors, compromising all three simultaneously (the three-root simultaneous failure scenario — 38A-05) requires coordinating against three separate governance mechanisms at the same time. Root separation increases the operational difficulty of simultaneous compromise.

4. **Constitutional visibility of compromise:** Under root separation, a compromise of one root's governance is potentially visible to the other roots' governance actors. Under merger, a compromise of the unified governance mechanism affects all roots simultaneously without creating visibility from any independent point.

### C.4 — What Root Separation Does NOT Provide Despite Governance Differentiation

The MA source-of-source convergence limits what root separation can constitutionally guarantee:

1. **MA capture → all roots:** A successful TM-07 attack (Legitimacy Root Compromise via MA capture) simultaneously compromises the source-of-source for all three roots. MA's 15 functions include appointment of all root governance actors. An MA that is constitutionally captured (in the sense of AA-01's pre-constitutional legitimacy being challenged) provides no independent constitutional validation for any of the three roots.

2. **MA appointment authority → all roots:** All actors governing all three roots were appointed by MA. If MA's appointment decisions are constitutionally questioned (e.g., through an AA-01 challenge), then all three roots' governance legitimacy is simultaneously questioned — regardless of how independently the governance mechanisms operate.

3. **AA-01 simultaneity:** AA-01 (MA pre-constitutional legitimacy — unresolved) is the single unresolved pre-constitutional question that simultaneously affects all three roots. If AA-01 is resolved negatively (MA's pre-constitutional legitimacy is found to be insufficient), then all three roots' constitutional authority chains lose their terminal legitimacy simultaneously. Root separation provides no protection against this outcome.

4. **Tier 2 MA coalition path:** A Tier 2 constitutional amendment changes the EC. MA ratifies all EC amendments. A Tier 2 coalition that controls MA ratification can amend the EC in ways that affect all three roots' governance — adjusting CIC authority (Legitimacy Root), changing AC-31 governance provisions (Authenticity Root), adjusting GovernanceState phase specification (Temporal Root) — through a single constitutional act (one EC amendment ratified by MA). Root separation at the governance layer does not prevent simultaneous root governance change through EC amendment.

---

## Part D — Interaction with AA-01

### D.1 — AA-01 as the Pre-Constitutional Terminal

AA-01 (MA Pre-Constitutional Legitimacy): The NRNA constitutional architecture assumes that MA has pre-constitutional legitimacy — the authority to constitute the constitutional order precedes the constitutional order it constitutes. This is a pre-constitutional assumption that the constitutional architecture depends on but does not and cannot resolve from within.

AA-01 has been characterized as having "forward-permanent urgency" (OBS-38B05-02): every additional constitutional function designated to MA increases the depth and scope of AA-01's relevance. At 15 functions, AA-01 is the foundational premise of the entire constitutional governance model.

### D.2 — AA-01 Impact on Root Separation Meaningfulness

**If AA-01 is resolved affirmatively (MA's pre-constitutional legitimacy is established):**

Root separation gains constitutional grounding. The three roots are constitutionally distinct because their governance mechanisms are independently derived from a constitutionally legitimate MA. MA's legitimate appointment of distinct governance bodies for each root creates constitutionally real governance independence at the governance layer. The independence of channels (rivers) is constitutionally grounded.

Root separation under AA-01 affirmative still does not protect against:
- Tier 2 MA coalition (EC amendment path)
- MA capture via TM-07 (if MA's operational integrity is compromised without affecting its pre-constitutional legitimacy)
- Successive appointment replacements over time (if all MA-appointed governance actors are eventually replaced by actors sympathetic to a common interest)

**If AA-01 is resolved negatively (MA's pre-constitutional legitimacy is found insufficient):**

All three roots' authority chains lose their constitutional terminal simultaneously. Root separation, however achieved, cannot provide constitutional protection when the source-of-source is constitutionally ungrounded. The three rivers lose constitutional validity regardless of how independently they flow, because their spring has no constitutional legitimacy.

**If AA-01 remains unresolved (current state):**

The constitutional grounding of all three roots is conditionally dependent on an unresolved pre-constitutional assumption. Root separation provides governance-layer independence but cannot provide constitutional certainty about whether that independence is constitutionally grounded. The independence is real at the operational level; its constitutional grounding is uncertain until AA-01 is resolved.

### D.3 — AA-01 and OQ-38B05-05 Interaction

The MA tri-root dependency creates a specific interaction between AA-01 and OQ-38B05-05:

- If OQ-38B05-05 is resolved affirmatively (trust root separation becomes constitutionally required): the constitutional requirement is grounded in MA's authority (MA ratifies the EC provision mandating separation). MA's constitutional authority traces to AA-01. A constitutional requirement grounded in unresolved AA-01 is itself conditionally grounded.

- If OQ-38B05-05 is resolved negatively (trust root separation is not constitutionally required): the architectural separation of roots is maintained at the governance layer but has no constitutional protection. MA could change all three roots' governance through EC amendment. The governance separation is real but not constitutionally entrenched.

In both resolutions of OQ-38B05-05, AA-01 remains the pre-constitutional terminal that provides the ultimate constitutional grounding. This observation is recorded as evidence — it does not determine which OQ-38B05-05 resolution is correct.

---

## Part E — Interaction with F-4 / TM-39

### E.1 — F-4 and the Source-of-Source Problem

F-4 (TM-39 — Independence Illusion) is a design-level FAIL: the constitutional architecture allows actors to claim D43 independence without structurally guaranteeing it (38B-06, OBS-38B07-03).

The source-of-source dependency interacts with F-4 in the following way:

**Root separation (governance layer):** Provides structural separation between the governance mechanisms of each root. An actor claiming independence from AC-31 governance can point to structural separation between AC-31 governance bodies and EC governance bodies as evidence of genuine independence.

**MA convergence (source layer):** All governance bodies were appointed by MA and operate within a constitutional framework ratified by MA. An actor challenging the independence claim can point to the common source as evidence that independence is a governance-layer formality that does not extend to constitutional source-level independence.

F-4's structural FAIL condition persists in the MA tri-root dependency context: claiming constitutional-level source independence is structurally unavailable when all roots share a source. The Independence Illusion can be maintained at the governance layer (independence of channels) while being constitutionally undermined at the source layer (common spring).

### E.2 — Does Root Separation Mitigate F-4?

The question is whether governance-layer separation (three independent governance mechanisms) provides meaningful F-4 mitigation when source-layer convergence (MA) exists.

**What governance-layer separation provides for F-4:**
- Makes coordinated F-4 operational exploitation more difficult (requires coordination across three governance mechanisms)
- Creates constitutional visibility of F-4 exploitation attempts (different governance bodies can observe each other's behavior)
- Provides constitutional standing for challenge (independent governance bodies have standing to challenge other roots' governance)

**What governance-layer separation does NOT provide for F-4:**
- Cannot constitutionally guarantee that independence claims are source-level independent (all actors trace to MA)
- Cannot prevent F-4 from operating at the MA level (if MA is captured, all independence claims are simultaneously weakened)
- Cannot protect against F-4 exploitation through Tier 2 EC amendment (MA-ratified amendment can simultaneously change all three roots' governance parameters)

**Net assessment (evidence only):** Root separation provides partial F-4 mitigation at the governance layer. It does not provide F-4 mitigation at the source layer. The Independence Illusion can still be constitutionally maintained at the source level even when governance-layer independence is structurally real.

### E.3 — TM-39 and the "Meaningful Reduction" Question

The reviewer framed the central question as: "Does Trust Root Separation meaningfully reduce concentration risk when all three roots ultimately trace to MA?"

TM-39's concentration analysis operates at two levels:

**Operational concentration:** Governance-layer root separation reduces operational concentration. No single actor controls all three roots' governance simultaneously (absent MA-level capture). Governance-layer separation is operationally real.

**Constitutional concentration:** Source-layer convergence maintains constitutional concentration. All governance actors are constitutionally MA-derived. Constitutional-level independence claims are conditionally dependent on MA's constitutional integrity and AA-01's resolution.

The "meaningfulness" question therefore depends on which level of concentration is constitutionally relevant to TM-39's FAIL characterization. This is recorded as an evidence question for OQ-38B05-05 evaluation — it is not answered in this document.

---

## Part F — Constitutional Implications

### F.1 — Two-Level Constitutional Independence

The MA tri-root dependency analysis reveals a structural characteristic of the NRNA constitutional architecture: constitutional independence exists at two levels with different constitutional implications.

**Level 1 — Operational/Governance Independence (separation currently provides this):**
- Distinct governance bodies for each root
- Independent procedural requirements for changing each root
- Cross-root challenge mechanisms
- Different coalition requirements for different attacks

**Level 2 — Constitutional Source Independence (MA convergence prevents this):**
- Constitutionally independent sources of authority for each root
- No single source-of-source for all three roots
- Protection against source-level capture
- AA-01-independent constitutional grounding for each root

The architecture as designed provides Level 1 independence. It does not provide Level 2 independence.

Whether constitutional protection of trust root separation (OQ-38B05-05) would require, provide, or imply Level 2 independence is an open question. Constitutional separation requirements may be fully satisfied by Level 1 independence; or they may require Level 2 independence; or the two levels may serve different constitutional purposes.

This observation is recorded as evidence. It is not a determination of OQ-38B05-05.

### F.2 — Minimum Unit of Constitutional Separation

The comparative constitutional evidence (38C-07) shows that separation of powers doctrine typically requires institutional independence between branches — not source-of-source independence. The legislative and executive branches in a parliamentary system are both democratically elected (sharing a source in the electorate), but their functional separation is constitutionally required despite this common source.

This comparative observation is recorded as evidence: constitutional separation requirements in comparable systems do not necessarily require source-of-source independence. The constitutional requirement may be satisfied by governance-layer independence even when a common source-of-source exists. Whether this comparative evidence applies to NRNA's trust roots requires evaluation in the OQ-38B05-05 process.

### F.3 — The Constitutional "River" Characterization

The "rivers from a single spring" analogy (Part B.3) can be extended:

If a constitutional requirement mandates that the three rivers flow independently (governance-layer separation), contaminating the spring (MA capture) would still affect all three rivers eventually, but:
- The rivers' independent governance would continue to function until MA capture causes governance actor replacement or amendment
- Cross-river constitutional challenge mechanisms could identify and resist MA-driven changes to any individual root
- The constitutional protection does not prevent source-level attack; it provides constitutional resistance and visibility for any source-level attack that propagates to the governance layer

If a constitutional requirement mandated spring-level separation (Level 2 independence), each river would need an independent source — requiring different authority structures for each root's ultimate constitutional grounding. This would require addressing AA-01 separately for each root, creating three distinct pre-constitutional legitimacy questions rather than one.

The constitutional design implications of Level 1 vs. Level 2 independence requirements are recorded here as structural evidence. No preference between the two is expressed.

### F.4 — Interactions Between MA Tri-Root Dependency and 38B Gap Specifications

The three 38B gap specifications that govern the roots were each designed with MA convergence in mind:

**38B-02 (AC-31 — Authenticity Root):** The multi-party tiered governance was designed specifically to reduce MA's operational role in AC-31 while maintaining MA's constitutional designation authority. The design explicitly acknowledges (OBS-38B02-01): "MA dependency increases as AC-31 concentration decreases; tradeoff favorability deferred."

**38B-03 (GovernanceState — Temporal Root):** The multi-party corroboration mechanism is designed to prevent any single actor (including MA-appointed actors) from unilaterally controlling phase records. EC-anchored phase specification is designed to prevent GovernanceState from being self-referential. However, EC anchoring traces to MA ratification.

**38B-04 (Appointment — Cross-Root):** The appointment governance explicitly documents that MA holds appointment authority for all authority aggregates across all three roots. OBS-38B04-02 characterizes this as the concentration consequence of resolving appointment ambiguity.

All three specifications acknowledge MA convergence. None of the three specifications eliminates the source-of-source dependency — they reduce its operational manifestation while maintaining its constitutional reality.

---

## Part G — Open Questions Produced by This Analysis

The following questions are produced by the MA tri-root dependency analysis. They are recorded as open questions for OQ-38B05-05 evaluation — they are not answered in this document.

**OQ-38C09-01 (Governance Layer Sufficiency):** Is governance-layer independence (Level 1) constitutionally sufficient for trust root separation to be meaningful, or does constitutional protection of trust root separation require source-layer independence (Level 2)?

**OQ-38C09-02 (AA-01 Sequencing):** Should OQ-38B05-05 be evaluated before or after AA-01 is resolved? An affirmative OQ-38B05-05 ruling that is conditionally dependent on an unresolved AA-01 may create constitutional uncertainty about the grounding of the very requirement it establishes.

**OQ-38C09-03 (MA Appointment Chain and Separation):** If OQ-38B05-05 is resolved affirmatively, does the constitutional protection of trust root separation also require protecting the appointment chains that govern each root from MA-level coordination? Or is separation of governance mechanisms sufficient even when appointment authority is shared?

**OQ-38C09-04 (Tier 2 EC Amendment Path):** Does constitutionally requiring trust root separation prevent a Tier 2 EC amendment from changing all three roots' governance simultaneously? If MA-ratified EC amendments can simultaneously affect all three roots, what constitutional protection does root separation actually provide against the Tier 2 coalition path?

**OQ-38C09-05 (Comparative Analogy Limits):** The separation-of-powers comparative evidence (38C-07 CE-03) shows that branches sharing a democratic source (the electorate) can still be constitutionally separated. Does this precedent apply to NRNA's trust roots sharing MA as source-of-source? What constitutional analysis determines whether shared-source constitutional separation is sufficient?

---

## Part H — Evidence Summary

| Question | Evidence Produced |
|----------|------------------|
| Does root separation provide meaningful protection? | YES at governance layer (operational independence, detection, coalition separation). CONDITIONAL at source layer (all roots trace to MA; AA-01 unresolved) |
| Does MA convergence eliminate separation benefits? | NO — governance-layer independence remains operationally real. But source-layer concentration limits constitutional independence claims |
| Does root separation mitigate F-4? | PARTIALLY — reduces operational F-4 exploitation difficulty; does not eliminate source-level F-4 constitutional vulnerability |
| What does AA-01 affect in this context? | All three roots' constitutional grounding simultaneously; resolution affects whether governance-layer independence is constitutionally grounded |
| Is Level 2 (source-layer) independence constitutionally required? | OPEN QUESTION — comparative evidence (CE-03) suggests separation of functions may be sufficient even with common democratic source; direct precedent unavailable |
| Can Tier 2 EC amendment override root separation? | YES under current architecture — MA-ratified EC amendment can simultaneously affect all three roots' governance |

---

## Part I — Governance Verification

| Constraint | Status |
|-----------|--------|
| OQ-38B05-05 evaluated | NO |
| OQ-38B05-05 recommendation made | NO |
| OQ-38A05-02 protection maintained | YES — PROTECTED throughout |
| New constitutional principles created | NO |
| New EC provisions created | NO |
| Constitutional amendment tiers assigned | NO |
| EC extension performed | NO |
| Deferred candidates converted | NO |
| AA-01 resolution attempted | NO — AA-01 interaction documented; AA-01 not resolved |

*Round 38C-09 — MA Tri-Root Dependency Analysis — ISSUED*
*OQ-38B05-05: NOT RESOLVED — MANDATORY PRIMARY REQUIREMENT*
*OQ-38A05-02: PROTECTED*
*OBS-38B06-05: APPLICABLE — dependency analysis completeness ≠ dependency analysis correctness*
*Date: 2026-06-19*
