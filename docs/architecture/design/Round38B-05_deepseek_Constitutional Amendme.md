## Round 38B-05 — Constitutional Amendment Governance Specification

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38B-05 — Governance Specification
**Status:** IN PROGRESS
**Gap Addressed:** Gap 3 — Constitutional Amendment Governance
**Governing Question:** How is ElectionConstitution amended, who may amend it, and what cannot be amended?

**Predecessors:**
- 38B-01 — Constitutional Interpretation Authority (Gap 4) — APPROVED
- 38B-02 — AC-31 Governance Specification (Gap 5) — APPROVED
- 38B-03 — GovernanceState Phase Record Governance (Gap 7) — APPROVED
- 38B-04 — Authority Appointment Process Specification (Gap 6) — APPROVED WITH MINOR REVISIONS

**Binding Inputs:**
- Gap 3 was ranked #4 in the 38A-06 provisional gap ranking
- TM-01 (Constitution Capture): C-F — amendment process enables permanent constitutional transformation
- TM-09 (Constitutional Drift): FAIL over time — no active adversary required
- OBS-38A06-SD1: Constitutional self-destruction — the constitution can legally destroy itself
- AW-02-11: No constitutional floor, no unamendable provisions
- OBS-38B02-01: MA dependency has accumulated through 38B-01 through 38B-04
- OBS-38B04-02: MA now holds 14-15 constitutional functions
- OQ-38B01-06: Can CIC be constitutionally removed? Depends on amendment governance
- OQ-38B04-04: What if MA cannot convene? Directly affects amendment process

**Scope:** Constitutional governance specification only. How the constitution is amended, who holds amendment authority, what amendment constraints exist, and how amendments are challenged. Not constitutional drafting. Not implementation.

---

## Part A — What Gap 3 Is

### A.1 The Gap

ElectionConstitution is the Legitimacy Root of the constitutional architecture. It defines what is constitutional. It is the L-1 source for all authority aggregates. It is the standard against which CO-4 (Constitutional Compliance) is evaluated.

But the constitution can be amended. And the amendment process is unspecified.

**What exists:**
- Membership Assembly ratification is required for amendments (ADR-1, ADR-2)
- ElectionConstitution is the shared L-1 source

**What does not exist:**
- Who may propose amendments
- What process governs amendment proposal, deliberation, and ratification
- Whether any provisions are unamendable
- Whether amendments can be challenged as unconstitutional
- What happens when an amendment conflicts with existing constitutional provisions
- Whether there is a constitutional floor below which protections cannot fall
- How amendment disputes are resolved

### A.2 Why Gap 3 Matters

TM-01 (Constitution Capture) demonstrated that capturing the amendment process enables permanent constitutional transformation. The captured constitution becomes the new constitutional baseline — self-validating and self-perpetuating.

TM-09 (Constitutional Drift) demonstrated that no active adversary is required. Small, individually reasonable amendments accumulate until constitutional protections have been dismantled through normal governance processes.

OBS-38A06-SD1 identified the structural vulnerability: the constitution can legally amend itself into irrelevance. There is no constitutional floor. No provision is unamendable. No entrenchment mechanism exists.

**Gap 3 is about preventing the amendment process from being the mechanism through which constitutional protections are dismantled — whether through capture or drift.**

---

## Part B — Specification Requirements

### B.1 Core Questions

1. **Who may propose amendments?** — MA members? Authority holders? Constitutional bodies?
2. **What process governs amendment?** — Proposal, deliberation, ratification, promulgation?
3. **What cannot be amended?** — Are any provisions unamendable? Is there a constitutional floor?
4. **How are amendments challenged?** — Can an amendment be challenged as unconstitutional? Through what pathway?
5. **What happens when amendments conflict?** — How are conflicts between amendments and existing provisions resolved?
6. **How is the amendment process itself protected?** — Can the amendment process be amended to make capture easier?

### B.2 Binding Constraints

| Constraint | Requirement |
|------------|-------------|
| **OBS-38A06-SD1** | Must prevent constitutional self-destruction through normal amendment |
| **TM-01** | Amendment process must be resistant to capture |
| **TM-09** | Must prevent gradual erosion through accumulated amendments |
| **OBS-38B02-01** | Must track MA dependency implications of amendment governance |
| **38B01-INV-01** | CIC interprets; CAB adjudicates; applies to amendment disputes |
| **AA-01** | MA legitimacy is unresolved; amendment governance depends on MA as ratifying body |

---

## Part C — Amendment Models

### C.1 Option A — Simple Majority Amendment

**Description:** Amendments are proposed by MA members and ratified by simple majority vote. All provisions are equally amendable. No special protections.

**Assessment:**
- Maximum flexibility — the constitution can evolve with the organization
- Minimum protection — any provision can be removed by a simple majority
- Directly enables TM-01: capturing a simple majority captures the constitution
- Directly enables TM-09: no barrier to gradual erosion
- OBS-38A06-SD1: Constitutional self-destruction is trivially achievable

**Verdict: REJECTED.** Insufficient protection for a constitutional governance platform where the constitution is the Legitimacy Root.

### C.2 Option B — Tiered Amendment with Entrenchment

**Description:** Constitutional provisions are classified into tiers with different amendment requirements. Structural provisions require supermajority or multi-cycle ratification. Core provisions are unamendable.

**Assessment:**
- Protects structural provisions from simple majority capture
- Prevents gradual erosion of core protections
- Complexity: requires classification of provisions into tiers
- CIC role: interprets whether an amendment complies with tier requirements

**Verdict: SELECTED.** See Part D.

### C.3 Option C — Constitutional Convention

**Description:** Amendments require a constitutional convention — a specially convened body distinct from the regular MA. Convention members are elected or appointed specifically for constitutional revision.

**Assessment:**
- Highest protection: amendment requires a body that does not normally exist
- Operational complexity: convening a convention is a major organizational undertaking
- May be appropriate for comprehensive constitutional revision but impractical for routine amendments
- Does not prevent capture — convention members can be captured like any other body

**Verdict: NOT SELECTED as primary model.** May be specified as an alternative pathway for comprehensive revision, but tiered amendment provides sufficient protection with lower operational burden.

---

## Part D — Selected Model: Tiered Amendment with Constitutional Floor

### D.1 Amendment Tiers

| Tier | Provisions | Amendment Requirement | Rationale |
|------|------------|----------------------|-----------|
| **Tier 1 — Core (Unamendable)** | Constitutional interpretation authority (CIC existence); Challenge adjudication authority (CAB existence); Independent reference standard requirement (AC-31); Certification independence (CA external); Three trust root separation | Unamendable — may not be altered, repealed, or amended through any process | These provisions constitute the constitutional floor. Their removal would destroy the constitutional architecture. OBS-38A06-SD1 requires their protection. |
| **Tier 2 — Structural** | Independence forms for D43 authorities; Appointment processes for constitutional bodies; Evidence strata architecture; Challenge standing classes; Amendment process itself | Supermajority (2/3 of MA) + ratification confirmed in two consecutive MA sessions | Structural provisions define how the constitutional system operates. They require broad consensus to change but not absolute permanence. |
| **Tier 3 — Operational** | Phase transition conditions; Challenge window durations; Audit scope Tier 1 categories; Committee composition details | Simple majority of MA + CIC constitutional compliance certification | Operational provisions require flexibility to adapt to changing circumstances while remaining constitutionally compliant. |

### D.2 Amendment Process

**Proposal:**
- Tier 2 amendments: Proposed by MA members, constitutional bodies (CIC, CAB, CA), or authority aggregates
- Tier 3 amendments: Proposed by MA members or GovernanceAuthority
- Tier 1: Not amendable — no proposal pathway exists

**Deliberation:**
- All amendment proposals must be published with specified notice period before MA consideration
- CIC must issue constitutional compliance assessment for all Tier 2 amendment proposals
- CIC assessment is advisory for the MA but creates a constitutional record for subsequent challenge

**Ratification:**
- Tier 2: 2/3 MA supermajority in first session; confirmed by simple majority in second consecutive session
- Tier 3: Simple majority MA vote with CIC constitutional compliance certification
- CIC certification is binding: an amendment that CIC certifies as constitutionally non-compliant cannot be ratified through Tier 3 process

**Promulgation:**
- Ratified amendments take effect at specified phase boundary — not immediately upon ratification
- Prevents mid-election constitutional change
- GovernanceState records amendment promulgation as constitutional event

### D.3 Amendment Challenge

**Who may challenge:** Any party with S-1/S-2/S-3 standing may challenge an amendment on constitutional grounds.

**Grounds for challenge:**
- Amendment was ratified through incorrect tier process (Tier 3 process used for Tier 2 provision)
- Amendment violates Tier 1 unamendable provisions
- Amendment process was procedurally invalid
- CIC certification was incorrectly issued or denied

**Challenge pathway:**
1. Challenge filed with CAB
2. CAB refers constitutional questions to CIC
3. CIC interprets whether amendment complies with tier requirements
4. CAB adjudicates based on CIC interpretation
5. Appeal to MA — but MA is the ratifying body; appeal to MA for an amendment MA itself ratified is circular

**OBS-38B05-01: Amendment Appeal Circularity.** When an amendment ratified by MA is challenged, the terminal appeal authority is MA itself. MA reviewing its own ratification decision is procedurally circular. This circularity is inherent to constitutional amendment governance — the sovereign that ratifies amendments is the sovereign that reviews them. Mitigation: CIC constitutional compliance certification creates an independent constitutional record that MA must address if it reverses its own ratification. MA cannot dismiss a certified CIC finding without documented constitutional reasoning.

### D.4 Amendment Process Protection

**The amendment process itself is a Tier 2 provision.** Amending the amendment process requires supermajority and dual-session ratification. This prevents a simple majority from lowering amendment thresholds to facilitate capture.

**Tier classification changes:** Reclassifying a provision from Tier 2 to Tier 3, or from Tier 1 to Tier 2, requires the higher tier's amendment process. A Tier 1 provision cannot be reclassified — it is unamendable.

---

## Part E — Relationship to Existing Architecture

### E.1 CIC Role in Amendment Governance

CIC serves three functions in amendment governance:
1. **Pre-ratification assessment:** Advisory constitutional compliance assessment for Tier 2 proposals
2. **Certification:** Binding constitutional compliance certification for Tier 3 amendments
3. **Challenge interpretation:** Interprets whether challenged amendments comply with tier requirements

**OQ-38B01-06 addressed:** CIC's own existence is a Tier 1 unamendable provision. CIC cannot be constitutionally removed through amendment. CIC members may be removed through L-4 revocation (ADR-5 remedy R-6), but the institution itself is constitutionally permanent.

### E.2 MA Role in Amendment Governance

MA is the ratifying body for all amendments. This is consistent with MA's role as constitutional sovereign and source-of-source. The MA concentration trend (OBS-38B02-01, OBS-38B04-02) continues: amendment ratification adds to MA's constitutional functions.

**OQ-38B04-04 interaction:** If MA cannot convene, the amendment process is suspended. No Tier 2 or Tier 3 amendments can be ratified. The constitution cannot be changed while the sovereign is unavailable. This is constitutionally appropriate — constitutional change should require the sovereign's active participation.

### E.3 GovernanceState Role

GovernanceState records amendment promulgation as a constitutional event. The phase at which an amendment takes effect is constitutionally recorded. This enables challenges based on whether an amendment was applied to elections that occurred before its effective date.

---

## Part F — OBS-38A06-SD1 Resolution

**OBS-38A06-SD1 (Constitutional Self-Destruction) is resolved by Tier 1 unamendable provisions.**

The constitutional floor established by Tier 1 prevents the constitution from legally destroying itself through normal amendment processes:

- **CIC cannot be abolished:** Constitutional interpretation authority is permanent
- **CAB cannot be abolished:** Challenge adjudication is permanent
- **AC-31 cannot be repealed:** Independent reference standard requirement is permanent
- **CA independence cannot be eliminated:** External certification is permanent
- **Trust root separation cannot be collapsed:** Legitimacy, Authenticity, and Temporal roots remain distinct

These five unamendable provisions constitute the constitutional floor. Below this floor, the constitutional architecture ceases to exist. Tier 1 ensures that no amendment process — regardless of majority — can cross this floor.

**TM-09 (Constitutional Drift) mitigation:** Tier 2 provisions require supermajority and dual-session ratification, slowing the pace of structural change. Tier 1 provisions are immune to drift entirely. Drift can still occur in Tier 3 operational provisions — but the structural architecture is protected.

**TM-01 (Constitution Capture) mitigation:** Capturing a simple majority is insufficient to amend Tier 2 provisions. Capturing a supermajority across two consecutive sessions is a significantly higher bar. Tier 1 provisions cannot be amended regardless of majority size.

---

## Part G — OBS-38A06-01 Self-Referential Review

**Q1: Does the amendment governance model validate its own amendments?**

No. CIC provides independent constitutional compliance assessment and certification. CAB adjudicates amendment challenges. MA ratifies but does not adjudicate the validity of its own ratification — CIC and CAB provide external validation.

**Q2: Does the amendment governance model determine its own succession?**

Partially. The amendment process itself is a Tier 2 provision — amendable only through the process it governs. This is inherent to constitutional governance: the rules for changing the rules are themselves rules. Mitigation: the amendment process cannot be amended to make Tier 1 provisions amendable — the constitutional floor is absolute.

**Q3: Does the amendment governance model adjudicate challenges to its own legitimacy?**

Amendment challenges are adjudicated by CAB with CIC interpretation. MA is the ratifying body but not the adjudicator of amendment validity. The amendment appeal circularity (OBS-38B05-01) is documented as inherent, not hidden.

**Verdict: No self-referential chain identified beyond the inherent circularity of constitutional amendment governance, which is documented in OBS-38B05-01.**

---

## Part H — Gap 3 Status

**Gap 3 is RESOLVED by this specification.** Amendment governance is specified: tiered amendment model, Tier 1 unamendable constitutional floor, Tier 2 supermajority + dual-session structural provisions, Tier 3 simple majority operational provisions. CIC provides constitutional compliance assessment and certification. CAB adjudicates amendment challenges.

Resolution is subject to:
- OBS-38B05-01 (amendment appeal circularity) — documented as inherent, not eliminated
- 38B synthesis — MA concentration assessment

---

## Part I — Open Questions

**OQ-38B05-01 (Primary):** Is the amendment appeal circularity (OBS-38B05-01) constitutionally acceptable? MA ratifies amendments; MA is the terminal appeal authority for challenges to amendments. MA reviewing its own ratification is circular. Is CIC certification + documented constitutional reasoning sufficient mitigation?

**OQ-38B05-02:** Should Tier 1 unamendable provisions be specified in this document, or should the constitutional drafting process determine which specific provisions are unamendable within the framework established here? This specification identifies five categories of unamendable provisions; the specific constitutional text belongs to constitutional drafting.

**OQ-38B05-03:** Can a Tier 2 amendment process be used to add new Tier 1 provisions? If so, the constitutional floor can be raised but not lowered — a one-way ratchet toward stronger protection.

---

## Section — ARB Decision Block

**[SUBMITTED FOR ARB REVIEW]**

### Decision

**Tiered Amendment with Constitutional Floor selected.** Tier 1 provisions are unamendable. Tier 2 provisions require supermajority + dual-session ratification. Tier 3 provisions require simple majority + CIC certification. CIC provides constitutional compliance assessment. CAB adjudicates amendment challenges.

### Gap 3 Status

**RESOLVED.** Amendment governance is constitutionally specified. Constitutional self-destruction (OBS-38A06-SD1) is prevented by Tier 1 unamendable floor.

### 38B Status

**38B Governance Specification: COMPLETE.** All five confirmed governance gaps (Gap 4, Gap 5, Gap 7, Gap 6, Gap 3) have governance specifications.

### Authorization Requested

**38B Synthesis — MA Concentration Assessment and Final 38B Closure.**

---

*Round 38B-05 — Gap 3 Constitutional Amendment Governance Specification — COMPLETE*
*Round 38B — Governance Specification Phase — COMPLETE*
*Research Program: NRNA DDD Trustworthiness*