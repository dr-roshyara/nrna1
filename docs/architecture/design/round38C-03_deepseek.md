## Round 38C-02 — OQ-38B05-07: ADR-EC Relationship Resolution

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38C-02 — Foundational Governance Prerequisite
**Status:** IN PROGRESS
**Purpose:** Resolve the constitutional relationship between ElectionConstitution provisions and ADR architectural decisions

**Predecessors:**
- 38C-01 — Strategic Discovery Charter — APPROVED
- 38B — Governance Specification — CLOSED
- ADR-1 through ADR-7 — APPROVED

**Binding Inputs:**
- OQ-38B05-07 is a mandatory early requirement for 38C
- Must be resolved before any 38C technical architecture ADR is written
- Affects ADR authority, architecture authority, constitution authority, interpretation authority
- 38C01-INV-01 applies: this is governance interpretation, not architectural design

**Scope:** Governance interpretation only. No technical architecture. No bounded contexts. No aggregates. No APIs. No protocols. No cryptography.

---

## Part A — The Question

What is the constitutional relationship between ElectionConstitution provisions and Architectural Decision Records?

When EC says "CertificationAuthority must be constitutionally independent" and ADR-2 says "CertificationAuthority independence is realized through Option C — External Organization," what governs if they conflict? Can an ADR be amended through a process that bypasses EC tier protection? Does CIC interpret ADR provisions with the same authority as EC provisions?

These questions are not academic. They determine whether the architectural guarantees established in ADR-1 through ADR-7 have constitutional force, or whether they are organizational policy amendable through ordinary governance.

---

## Part B — Option A: ADRs Independent of EC

### B.1 Description

ADR decisions are independent architectural governance instruments. They are not EC provisions. They derive authority from MA ratification, not from EC constitutional status. They may be amended through MA-defined ADR amendment process, which may differ from EC tier requirements. EC provisions establish constitutional principles; ADRs are organizational policy implementing those principles.

### B.2 Authority Source

ADRs derive authority from MA ratification as architectural governance instruments. They are not L-1 constitutional provisions. Their authority is organizational, not constitutional. MA may amend them through whatever process MA defines for ADR amendment — which could be simple majority, supermajority, or any other threshold MA chooses.

### B.3 Amendment Requirements

ADRs are amended through MA-defined ADR amendment process. This process is not constitutionally specified. EC tier protections (Tier 2 supermajority, Tier 3 near-unanimity) do not apply to ADR amendments. An ADR could be amended through simple majority if MA so chooses.

### B.4 Challengeability

ADR decisions are challengeable through CAB as authority decisions. A party with S-1/S-2/S-3 standing may challenge an ADR amendment on grounds that it violates EC constitutional principles. CIC interprets whether the amended ADR satisfies EC requirements. If an ADR amendment violates EC independence principles, CAB may rule it constitutionally invalid.

### B.5 CIC Jurisdiction

CIC interprets EC provisions. CIC does not have direct interpretive authority over ADRs as independent instruments. CIC's jurisdiction over ADRs is indirect: when an ADR is challenged as violating EC, CIC interprets the EC provision and CAB determines whether the ADR conflicts.

### B.6 Impact on Existing ADR Invariants

Under Option A, ADR invariants (ADR3-INV-01, ADR6-INV-01, ADR7-INV-01, ADR7-INV-02, 38B01-INV-01, 38B04-INV-01, 38B05-INV-01) are architectural commitments, not constitutional constraints. They bind through MA's organizational authority, not through constitutional entrenchment. An ADR invariant could be amended through the ADR amendment process without triggering EC tier protection.

### B.7 Impact on Trust Roots

Trust root governance models specified in 38B (CIC for Legitimacy, Multi-Party Tiered for Authenticity, Multi-Party Corroboration for Temporal) are specified in governance specifications that have constitutional weight. But the ADR decisions that give them architectural form (ADR-2 independence forms, ADR-4 audit structure, ADR-6 certification architecture) are organizational policy. The constitutional principles are protected; the architectural forms are amendable.

### B.8 Impact on Governance Concentration

MA concentration is partially mitigated: MA controls ADR amendment process, but ADRs are organizational policy, not constitutional provisions. The distinction between constitutional amendment (Tier 2/3) and ADR amendment (MA-defined process) creates two governance tracks — one constitutionally constrained, one organizationally flexible. This could reduce concentration by allowing architectural evolution without constitutional amendment, or increase concentration by allowing MA to bypass constitutional protection through ADR amendment.

### B.9 Assessment

Option A provides maximum architectural flexibility. ADRs can evolve as architectural understanding matures without requiring constitutional amendment. The protection mechanism is challenge-based: ADR amendments that violate EC principles can be challenged through CAB with CIC interpretation.

**Risk:** The challenge mechanism is reactive. An ADR amendment that weakens independence forms takes effect upon MA approval and remains in force until challenged. If no party with standing challenges, the amendment stands. This is the same reactive defense pattern identified in 38A — effective when activated, but dependent on activation.

---

## Part C — Option B: ADRs as EC Tier-2 Provisions

### C.1 Description

ADR decisions are EC Tier 2 provisions. They are constitutional instruments with the same status as other EC governance provisions. They are amended through Tier 2 process: supermajority MA ratification, CIC constitutional vetting, deliberation window, challenge window.

### C.2 Authority Source

ADRs derive authority from EC as Tier 2 provisions. Their constitutional status is equivalent to other EC governance provisions. They are not separate instruments — they are the architectural realization of EC constitutional principles, with the same constitutional force as the principles they implement.

### C.3 Amendment Requirements

ADRs are amended through Tier 2 process: two-thirds supermajority, CIC constitutional opinion, 90-day deliberation window, 60-day challenge window. The amendment process is constitutionally specified and constitutionally constrained.

### C.4 Challengeability

ADR amendments are challengeable through the same pathway as EC amendments: CAB adjudicates procedural challenges, CIC interprets constitutional questions. The Tier 2 process provides structured challenge opportunity.

### C.5 CIC Jurisdiction

CIC has direct interpretive authority over ADRs as EC Tier 2 provisions. CIC's constitutional vetting is mandatory before ADR amendment ratification. CIC interprets whether proposed ADR amendments satisfy EC constitutional principles.

### C.6 Impact on Existing ADR Invariants

ADR invariants are constitutional constraints at Tier 2. They have the same constitutional force as other EC governance provisions. Amending an ADR invariant requires Tier 2 process. The invariants are constitutionally protected, not merely organizationally committed.

### C.7 Impact on Trust Roots

Trust root governance is constitutionally specified at Tier 2. The architectural forms that realize constitutional principles have constitutional status. Changing CA's independence form from Option C to Option B would require Tier 2 amendment — supermajority, CIC vetting, deliberation, challenge.

### C.8 Impact on Governance Concentration

MA concentration increases: MA controls both EC amendment (all three tiers) and ADR amendment (Tier 2). All constitutional and architectural change routes through MA. However, the Tier 2 process provides friction — supermajority requirement, CIC vetting, deliberation and challenge windows — that constrains MA's amendment authority.

### C.9 Assessment

Option B provides maximum constitutional protection. ADR decisions have the same constitutional force as the principles they implement. The amendment process is constitutionally specified and constrained.

**Risk:** Constitutional rigidity. Architectural evolution requires Tier 2 process even for changes that are operationally necessary but constitutionally uncontroversial. If ADR-4's audit architecture needs refinement based on operational experience, the refinement requires supermajority MA ratification — potentially slowing architectural improvement.

---

## Part D — Option C: Principle/Form Distinction (Hybrid)

### D.1 Description

EC provisions establish constitutional principles (what must be achieved). ADR decisions establish architectural forms (how principles are realized). The principle has constitutional status at its designated EC tier. The form has architectural status — binding through MA authority, amendable through ADR process, but constrained by the constitutional principle it serves.

**Example:**
- EC Tier 2 principle: "CertificationAuthority must be constitutionally independent"
- ADR architectural form: "CertificationAuthority independence is realized through Option C — External Organization"
- The principle is constitutionally protected at Tier 2
- The form is architecturally binding through ADR process
- A form amendment that violates the principle is constitutionally invalid
- CIC interprets whether a form satisfies its governing principle

### D.2 Authority Source

Constitutional principles derive authority from EC at their designated tier. Architectural forms derive authority from MA ratification as ADR decisions — organizational authority, not constitutional status. The relationship is hierarchical: principles govern forms; forms serve principles.

### D.3 Amendment Requirements

**Constitutional principles:** Amended through EC tier process (Tier 2 for governance principles, Tier 3 for protected core). Amendment requires tier-appropriate supermajority, CIC vetting, deliberation, challenge.

**Architectural forms:** Amended through ADR amendment process defined by MA. This process must include: CIC constitutional compliance certification (does the amended form satisfy its governing principle?), publication and deliberation period, challenge opportunity through CAB.

The ADR amendment process is lighter than Tier 2 but heavier than simple majority: CIC certification and challenge opportunity are mandatory. An ADR amendment that CIC certifies as violating its governing principle is constitutionally void — even if MA ratifies it.

### D.4 Challengeability

ADR form amendments are challengeable through CAB. Challenge grounds: the amended form violates its governing EC principle. CIC interprets whether the form satisfies the principle. If CIC finds the form violates the principle, the amendment is void.

ADR form decisions can also be challenged directly (not just amendments): a party with standing may challenge an existing ADR form as constitutionally inadequate — arguing that the current form does not satisfy its governing principle. CAB adjudicates; CIC interprets.

### D.5 CIC Jurisdiction

CIC has direct interpretive authority over both principles and forms, but the nature of authority differs:
- **Over principles:** Full constitutional interpretation authority. CIC determines what the principle requires.
- **Over forms:** Constitutional compliance certification. CIC determines whether a given form satisfies its governing principle. CIC does not select forms or dictate architectural choices — CIC certifies compliance.

### D.6 Impact on Existing ADR Invariants

ADR invariants are classified by type:
- **Principle-type invariants:** Those that state constitutional requirements. These are EC principles at their appropriate tier. Example: "CO-5 void unless CO-2+CO-3+CO-4 satisfied" (ADR6-INV-01) — this is a constitutional validity requirement, not an architectural choice.
- **Form-type invariants:** Those that specify architectural realizations. These are ADR architectural forms. Example: "CertificationAuthority independence realized through Option C" (ADR-2) — this is an architectural choice serving the constitutional principle of certification independence.

Principle-type invariants have constitutional protection at their designated tier. Form-type invariants have architectural protection through ADR process with CIC certification requirement.

### D.7 Impact on Trust Roots

Trust root governance has both principle and form dimensions:
- **Principle:** The three trust roots must be governed by independent constitutional mechanisms. This is a constitutional principle.
- **Form:** CIC governs Legitimacy; Multi-Party Tiered governs Authenticity; Multi-Party Corroboration governs Temporal. These are architectural forms serving the governance principle.

The principle has constitutional protection. The forms have architectural protection with CIC certification. Form evolution is possible without constitutional amendment, provided the evolved form satisfies the governance principle.

### D.8 Impact on Governance Concentration

MA concentration is balanced: MA controls both principle amendment (EC tiers) and form amendment (ADR process). But the ADR process is lighter than EC amendment, providing flexibility for architectural evolution without requiring constitutional supermajority. CIC certification provides constitutional safeguard: forms cannot violate principles regardless of MA support.

### D.9 Assessment

Option C provides balanced constitutional protection and architectural flexibility. Principles are constitutionally protected. Forms are architecturally binding but evolutionarily possible. CIC provides constitutional compliance certification. The principle/form distinction creates two governance tracks — one constitutional, one architectural — with clear hierarchy and CIC oversight.

**Risk:** Boundary disputes. Is a given provision a principle or a form? CIC must adjudicate boundary questions. The classification of existing ADR invariants as principle-type or form-type may be contested.

---

## Part E — Comparative Analysis

| Criterion | Option A (Independent) | Option B (EC Tier 2) | Option C (Hybrid) |
|-----------|----------------------|---------------------|-------------------|
| **Constitutional protection** | Weak — reactive challenge only | Strong — Tier 2 amendment barrier | Strong for principles; moderate for forms |
| **Architectural flexibility** | High — simple MA amendment | Low — Tier 2 required for all changes | Moderate — form evolution via ADR process |
| **CIC role** | Indirect — only on challenge | Direct — mandatory vetting | Direct — certification of form compliance |
| **MA concentration** | Moderate — two amendment tracks | High — single track through MA | Moderate — two tracks with hierarchy |
| **Risk of governance drift** | High — ADR weakening without challenge | Low — constitutional barrier | Low — CIC certification prevents violation |
| **ADR invariant protection** | Weak — organizational commitment | Strong — constitutional constraint | Principle invariants strong; form invariants moderate |
| **Trust root protection** | Weak — forms are organizational | Strong — forms are constitutional | Principles strong; forms moderate with CIC safeguard |

---

## Part F — Selection: Option C (Principle/Form Distinction)

### F.1 Rationale

Option C is selected because it provides constitutional protection where needed (principles) and architectural flexibility where appropriate (forms), with CIC constitutional compliance certification as the safeguard against governance drift.

**Why not Option A:** The reactive challenge model is insufficient for constitutional governance. ADR invariants that protect fundamental architectural properties (stratum independence, certification validity requirements, suspension succession) should not depend on someone filing a challenge to remain in force. The program's 38A threat model repeatedly identified reactive defense as a structural vulnerability.

**Why not Option B:** Constitutionalizing all ADR decisions at Tier 2 would freeze architectural forms that should evolve with operational experience. ADR-4's audit architecture is still provisional. Requiring Tier 2 supermajority for every architectural refinement would impede legitimate architectural improvement.

**Why Option C:** The principle/form distinction protects what must be stable (constitutional principles) while allowing what should be flexible (architectural forms) to evolve. CIC certification ensures that form evolution never violates its governing principle. The hierarchy is clear: principles govern forms. CIC adjudicates boundary disputes.

### F.2 Implementation

**Classification of existing ADR invariants:**

**Principle-type (EC constitutional status):**
- ADR3-INV-01: Stratum independence — constitutional requirement
- ADR6-INV-01: CO-5 requires CO-2+CO-3+CO-4 — constitutional validity requirement
- ADR7-INV-01: Suspension succession pre-designation — constitutional governance requirement
- ADR7-INV-02: No self-dealing in standing — constitutional protection
- 38B01-INV-01: CIC interprets; CAB adjudicates — constitutional separation
- 38B04-INV-01: No self-appointment — constitutional constraint
- 38B05-INV-01: Three amendment tiers — constitutional meta-protection

**Form-type (ADR architectural status):**
- ADR-2 independence forms (Option B/C for specific authorities) — architectural choices
- ADR-4 audit authority structure (single aggregate vs. split) — architectural design
- ADR-6 certification architecture specifics (Option C Hybrid evaluation model) — architectural design
- Appointment distribution (which body appoints which authority) — architectural design

**ADR amendment process:**
- MA defines ADR amendment process
- Process must include: CIC constitutional compliance certification, publication and deliberation period, challenge opportunity through CAB
- CIC certifies whether amended form satisfies governing principle
- Amendment with adverse CIC certification is constitutionally void
- Challenge pathway: CAB adjudicates, CIC interprets

---

## Part G — Impact on OQ-38B05-05

The principle/form distinction directly affects trust root separation:

- **Principle:** The three trust roots must be governed by structurally differentiated constitutional mechanisms. This is a constitutional principle — if adopted, it would be Tier 2 or Tier 3 protected.

- **Form:** CIC governs Legitimacy; Multi-Party Tiered governs Authenticity; Multi-Party Corroboration governs Temporal. These are architectural forms.

Under Option C, the principle of trust root separation can be constitutionally protected while the specific governance forms remain architecturally evolvable. A proposal to merge two trust roots would violate the constitutional principle — CIC would certify the merger as constitutionally non-compliant, and the amendment would be void regardless of MA support.

**This provides the protection OQ-38B05-05 identified as missing, without requiring Tier 3 entrenchment of specific governance forms.**

---

## Part H — OQ-38B05-07 Resolution

**OQ-38B05-07 is RESOLVED.**

**Resolution:** Option C — Principle/Form Distinction (Hybrid).

**Constitutional status of ADR decisions:**
- ADRs contain both constitutional principles (EC status at designated tier) and architectural forms (ADR status with CIC certification)
- Principles govern forms; CIC certifies compliance
- Forms are binding through MA authority, amendable through ADR process, constrained by governing principles

**Precedence rule:** EC principles govern ADR forms. Where an ADR form conflicts with its governing EC principle, the principle prevails. CIC interprets whether conflict exists. CAB adjudicates disputes.

**Amendment governance:**
- Principles: Amended through EC tier process
- Forms: Amended through ADR process with mandatory CIC certification
- Form amendments with adverse CIC certification are constitutionally void

---

## Section — ARB Decision Block

**[SUBMITTED FOR ARB REVIEW]**

### Decision

**OQ-38B05-07 RESOLVED.** Option C — Principle/Form Distinction — selected. ADR decisions have constitutional status for principles, architectural status for forms. CIC certifies form compliance with principles.

### Impact

All 38C technical architecture ADRs will be written under Option C governance. Principles will have EC constitutional status. Forms will have ADR architectural status with CIC certification.

### Authorization Requested

**38C-03 — Capability Discovery.** OQ-38B05-07 is resolved. The foundational governance prerequisite is satisfied. Strategic discovery may proceed.

---

*Round 38C-02 — OQ-38B05-07 Resolution — COMPLETE*
*Research Program: NRNA DDD Trustworthiness*
