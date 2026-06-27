# Round 38B-05 — Constitutional Amendment Governance Specification

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38B-05 — Governance Specification
**Status:** R1–R5 APPLIED (38B-05-ARB-Review 2026-06-18; Senior Architect Review 2026-06-18; R1–R5 applied; 38B-06 authorization PENDING)
**Gap Addressed:** Gap 3 — EC Amendment Process Specification
**Governing Question:** By what process may the ElectionConstitution be amended, and what provisions are protected from amendment?
**Date:** 2026-06-18

**Predecessors:**
- 38B-01 — Constitutional Interpretation Authority (Gap 4) — APPROVED
- 38B-02 — AC-31 Governance Specification (Gap 5) — APPROVED WITH MINOR OBSERVATIONS APPLIED
- 38B-03 — GovernanceState Phase Record Governance (Gap 7) — APPROVED WITH INTEGRATIONS
- 38B-04 — Authority Appointment Process Specification (Gap 6) — APPROVED WITH MINOR REVISIONS APPLIED

**Binding Inputs:**
- OBS-38A06-SD1: Constitutional Self-Destruction — MA can legally remove all constitutional protections via valid EC amendments if no floor exists; Gap 3 enables; Gap 4 amplifies; this is the named 38B deliverable from the 38B Authorization Decision
- OQ-38B01-06 (Primary): CIC constitutional floor — can CIC be abolished via EC amendment? Gap 3 must address
- OQ-38B02-01 (Primary): AC-31 constitutional floor — can AC-31 requirement be deleted via EC amendment? Gap 3 must address
- TC3-NCQ-04 (36C-04): "Who governs the governors?" — Gap 3 is the constitutional answer at the amendment layer
- ADR7-INV-02: No authority may self-grant or restrict standing — amendment process must not enable self-dealing in amendment authority
- OBS-38B04-02: MA concentration — MA now holds 14 constitutional functions; amendment ratification adds MA function #15
- OBS-38B04-04: AA-01 dependency accumulation — every additional MA function increases dependence on pre-constitutional AA-01 resolution; the amendment chain itself is now the vehicle for all future constitutional evolution
- SCF-38B04-01: CAB appointment tension — can MA amend away CAB independence? Gap 3 must address
- SCF-38B04-02: AA-01 dependency scale — 38B synthesis and Gap 3 must characterize what the constitutional governance model achieves and what it assumes

**Binding Carry-Forwards:**
- OQ-38A05-02 (Finality vs. Validity): PROTECTED — amendment retroactive effects on TS-1 route to CIC; not implicitly resolved here
- 38B01-INV-01: CIC interprets; CIC does not operationally govern; CAB adjudicates; neither governs operationally
- OBS-38B02-01: MA dependency tracking — amendment ratification is counted in the MA concentration inventory
- AA-01: MA legitimacy terminal and pre-constitutional; amendment legitimacy ultimately depends on it

**Scope:** Constitutional amendment process and constitutional floor specification only. Not EC content design, not election operational rules, not implementation of any specific amendment. This round specifies how the constitution may be changed and what it may not be changed to remove.

**Named 38B Deliverables this round must produce:**
1. OBS-38A06-SD1 constitutional floor — specified here
2. OQ-38B01-06 addressed — CIC floor
3. OQ-38B02-01 addressed — AC-31 floor

---

## Part A — What Gap 3 Is

### A.1 The Gap

The ElectionConstitution exists as the root of constitutional legitimacy for all authority aggregates (ADR-1, ADR-7). It can be amended — nothing in the current architecture prohibits amendment. But:

**What exists:**
- EC as the shared L-1 constitutional source (ADR-1)
- MA as the ratification body for ADRs (pattern across ADR-1 through ADR-7)
- CIC as the interpretive authority (38B-01)
- EC amendment as an implicit capability (referenced in 36E, ADR rounds, 38B-01)

**What does not exist:**
- Any specification of who may propose an amendment
- Any requirement for constitutional vetting before ratification
- Any deliberation window or publication requirement
- Any ratification threshold other than implicit MA authority
- Any specification of whether any provisions are protected from amendment
- Any transition provisions for the architecture during an amendment

### A.2 Why Gap 3 Is Different from All Other Gaps

Gaps 4, 5, 6, and 7 were governance specification gaps: who does what, through what process, with what verification. Each added a governance mechanism to a previously unspecified constitutional function.

**Gap 3 is a constitutional architecture gap.** It asks not who governs but what cannot be ungoverned. It asks not what process is followed but what process is beyond process — what the architecture assumes can never be legitimately removed.

The constitutional Self-Destruction finding (OBS-38A06-SD1) is unique among all findings in this program. It describes a failure mode where the architecture works exactly as designed — MA ratifies a valid amendment — and the result is the elimination of constitutional trustworthiness. No adversarial capture required; no technical failure required; only constitutional process.

Gap 3 is the final and most fundamental governance specification.

### A.3 Constitutional Self-Destruction Mechanism

```
ElectionConstitution currently has no amendment floor.

Therefore, the following sequence is constitutionally valid:

MA proposes: "Amend EC to remove CIC"
MA ratifies with current simple majority threshold
CIC is constitutionally dissolved
No interpretive authority exists
All subsequent constitutional challenges lack authoritative interpretation
MA proposes: "Amend EC to remove anonymity requirement"
MA ratifies
Voter-vote linkage becomes constitutionally permissible
...
```

Each step is constitutionally valid. No rule is broken. The result is an election system that is constitutionally legitimate and constitutionally untrustworthy simultaneously.

OBS-38A06-SD1 registered this as a mandatory carry-forward into 38B. Gap 3 is the constitutional answer.

---

## Part B — What 38B-05 Must Specify

### B.1 Three Specification Requirements

**Requirement 1 — Amendment Mechanics:** Who may propose, who vets, what deliberation process, who ratifies, what threshold, what challenge pathway, when does an amendment take effect.

**Requirement 2 — Constitutional Floor:** What provisions, if any, are protected from amendment. This directly answers OBS-38A06-SD1. It must address OQ-38B01-06 (CIC floor) and OQ-38B02-01 (AC-31 floor) as named deliverables.

**Requirement 3 — Transition and Stability:** How the constitutional architecture maintains coherence during an amendment period. What happens to pending challenges, active elections, and authority aggregates when an amendment is ratified.

### B.2 The Governing Tension

The governing tension in Gap 3 is between two legitimate constitutional principles:

**Principle of Constitutional Evolution:** A constitution that cannot be amended is a constitution that cannot improve. If constitutional understanding develops, if authority model errors are discovered, if new protections are needed, the EC must be amendable. Freezing the constitution in 2026 serves no one.

**Principle of Constitutional Stability:** A constitution that can be easily amended — especially by the same body that holds appointment authority, ratification authority, and terminal appeal authority — is a constitution that can be systematically dismantled from within. The amendment process cannot become the tool of constitutional Self-Destruction.

Gap 3 must honor both principles. It cannot fully sacrifice either.

---

## Part C — Amendment Mechanics Specification

### C.1 Amendment Proposal

**Who may propose:** Any of the following may submit a proposed EC amendment for constitutional consideration:
- Any MA member, acting individually or collectively
- Any constitutional authority aggregate, acting through its authorized governance process
- Any party with S-1/S-2/S-3 standing (directly affected / constitutional observer / authority peer)
- CIC, acting on its own motion in response to a constitutional interpretation question that reveals an EC gap

**Form requirement:** A proposed amendment must:
1. Identify the specific EC provision being amended (or added or removed)
2. State the proposed amended text
3. State the constitutional justification for the amendment
4. State which amendment tier (Part D) the proposer believes applies and why

**Prohibition (ADR7-INV-02):** No amendment may be proposed, vetted, or ratified through a process in which the amendment's primary beneficiary holds disproportionate procedural authority over its own amendment. An authority aggregate whose constitutional mandate would be expanded by a proposed amendment may not hold disproportionate influence over that amendment's ratification.

### C.2 CIC Constitutional Vetting

**Mandatory before ratification of any amendment:**

CIC must publish a constitutional opinion on every proposed amendment before MA ratification. The CIC opinion must address:

1. **Tier determination:** Which amendment tier (D.2–D.4) correctly governs this amendment, and why
2. **Protected Provision analysis:** Does this amendment implicate any Tier 3 Protected Provision (Part E)? If so, what is CIC's constitutional interpretation of whether the amendment conflicts with or merely modifies that provision?
3. **Self-dealing check (ADR7-INV-02):** Does any constitutional actor hold disproportionate influence over this amendment's ratification by virtue of being the amendment's primary beneficiary?
4. **OQ-38A05-02 intersection:** Does this amendment affect the finality vs. validity question for any issued or pending CO-5 certification? If so, CIC must identify this explicitly — it does not rule on the underlying question (PROTECTED) but must flag the intersection.
5. **Retroactive effect:** Does this amendment purport to have retroactive effect? CIC must opine on whether such retroactive effect is constitutionally permissible (see Part H).

**CIC recusal:** If the proposed amendment directly affects CIC's own existence, mandate, or independence, the full CIC is recused from producing the constitutional opinion. In that case, the constitutional opinion is produced by a panel of prior CIC members (or constitutional scholars designated by MA under a separate EC provision) — see Part F.

**CIC opinion is advisory:** MA is not constitutionally bound to follow CIC's tier determination or Protected Provision analysis. However, if MA ratifies an amendment over a negative CIC opinion on Protected Provision conflict, that ratification is immediately challengeable through CAB by any S-1/S-2/S-3 party.

### C.3 Publication and Deliberation Window

After a proposed amendment receives CIC's constitutional opinion (or after the recusal panel publishes its opinion):

- The proposed amendment, the CIC opinion, and all substantive responses from authority aggregates must be published to all constitutional actors
- A deliberation window of EC-specified minimum duration (distinct per tier — see Part E.3) must pass before MA may vote on ratification
- Any authority aggregate whose constitutional basis would be materially affected by the amendment may submit a formal constitutional response during the deliberation window; CIC must acknowledge (not necessarily follow) each formal response
- The deliberation window may not be shortened except by unanimous consent of all affected authority aggregates plus CIC concurrence

### C.4 Ratification

**Ratifying body:** Membership Assembly.

**Threshold:** Depends on amendment tier (see Part E.3 for tier-specific thresholds).

**Ratification record:** MA ratification of an amendment is a constitutional record governed by 38B-03 GovernanceState temporal governance. The ratification event, vote count, and deliberation record must be preserved as constitutional evidence under the GovernanceState Phase Record governance model.

**Multi-election amendments:** An amendment may not be ratified during an active election cycle. MA must either complete the election cycle and then ratify, or allow a constitutional pause for amendment. This prevents amendment-during-election as a mechanism for altering the constitutional rules mid-cycle.

### C.5 Challenge Window After Ratification

After MA ratification of an amendment, a challenge window of EC-specified duration opens. During this window:

- Any party with S-1/S-2/S-3 standing may challenge the procedural validity of the amendment process (not MA's authority to amend — only whether the procedural requirements of this specification were followed)
- CAB adjudicates; CIC interprets constitutional questions raised in the challenge
- If a challenge succeeds on procedural grounds, the amendment is void and the ratification process must restart
- If no challenge is filed, or all challenges are dismissed, the challenge window closes and the amendment is constitutionally enacted

**Forward reference — OBS-38B05-08 (Amendment Appeal Circularity):** The terminal appeal authority for a challenge to a ratified amendment is MA itself — the same body that ratified the amendment. This structural circularity is inherent to constitutional amendment governance in any sovereign assembly model. CAB adjudicates procedural challenges; CIC interprets constitutional questions; but the ultimate constitutional sovereign reviewing its own ratification decision is MA. See OBS-38B05-08 in the ARB Decision Block for the full characterization.

### C.6 Enactment and Effect

An amendment takes effect on the day the challenge window closes (or on a prospectively specified date if the amendment explicitly designates one later than the challenge window close). The GovernanceState records the amendment enactment as a constitutional-phase event. All authority aggregates whose constitutional basis is affected by the amendment must update their constitutional foundation records within a EC-specified transition period.

---

## Part D — Constitutional Floor — Model Evaluation

### D.1 Option A — No Constitutional Floor (REJECTED)

**Description:** All EC provisions are equally amendable by MA simple majority. The EC is a living document subject to complete revision at any time.

**Assessment:**
- OBS-38A06-SD1: FULLY ENABLED. Constitutional Self-Destruction is legally simple — MA can remove any protection with a single vote. No architectural friction.
- OQ-38B01-06: UNRESOLVED — CIC can be abolished by simple majority
- OQ-38B02-01: UNRESOLVED — AC-31 can be deleted by simple majority
- Violates the named 38B deliverable (OBS-38A06-SD1 floor must be specified)

**Verdict: REJECTED.** Does not fulfill Gap 3's constitutional purpose.

### D.2 Option B — Uniform Supermajority Floor (NOT SELECTED)

**Description:** All amendments require a supermajority (e.g., two-thirds) of MA ratification regardless of what is being amended.

**Assessment:**
- Reduces but does not eliminate Constitutional Self-Destruction risk
- OBS-38B04-02 context: MA holds 14 constitutional functions and controls appointment of all six authority aggregates; a sufficiently coordinated MA can plausibly reach supermajority threshold, especially if it has shaped the membership composition over time through appointment control
- Treats a procedural change to an operational rule the same as a change to the anonymity guarantee — no differentiation between constitutional importance levels
- Does not specifically address OQ-38B01-06 or OQ-38B02-01 — those provisions would still be removable by supermajority

**Verdict: NOT SELECTED.** Better than Option A but does not distinguish constitutional importance levels and does not adequately address the named deliverables.

### D.3 Option C — Unamendable Eternity Clauses (NOT SELECTED)

**Description:** Certain provisions are formally unamendable — no process, no threshold, no MA vote can remove them. Modeled on the German Basic Law Article 79(3).

**Assessment:**
- Strongest protection against Constitutional Self-Destruction
- Preserves protected provisions regardless of MA composition or concentration
- Constitutional Cost 1 (Fossilization): if the protected provisions contain errors or become constitutionally inadequate over time, they cannot be corrected. The architecture is frozen at 2026 constitutional understanding for those provisions.
- Constitutional Cost 2 (Legitimacy paradox): provisions that cannot be changed by any constitutional process are provisions that ultimately depend on extra-constitutional legitimacy — the legitimacy of the original adoption decision. AA-01 dependency is at its maximum under this model.
- Constitutional Cost 3 (Enforcement): an unamendable provision that MA nonetheless removes (by ignoring the constraint) creates a constitutional crisis with no resolution mechanism within the architecture

**Verdict: NOT SELECTED.** Maximum protection at maximum constitutional cost. The legitimacy paradox under AA-01 is particularly acute: unamendable provisions that depend on AA-01 legitimacy have the same ultimate fragility as amendable ones, but without the relief valve of future revision.

### D.4 Option D — Graduated Threshold (Protected Provisions) (SELECTED)

**Description:** Three amendment tiers with different procedural requirements and ratification thresholds:

- **Tier 1 (Operational):** provisions governing operational configuration; lowest threshold
- **Tier 2 (Governance):** provisions governing authority structure and governance processes; higher threshold
- **Tier 3 (Constitutional Core):** provisions protecting the fundamental trustworthiness guarantees of the architecture; maximum procedural burden; near-unanimous threshold; practically very difficult to remove but not formally impossible

**Assessment:**
- Addresses Constitutional Self-Destruction without formal eternity clauses: Tier 3 requirements are sufficiently burdensome that casual or motivated-minority removal is practically blocked
- Preserves MA sovereignty: nothing is technically beyond MA's constitutional power; Tier 3 merely requires extraordinary consensus
- Allows constitutional evolution: even core protections can evolve if extraordinary consensus exists — the architecture can correct errors in its own constitutional floor
- AA-01 dependency: the amendment process legitimacy ultimately depends on MA legitimacy, which depends on AA-01. This is irreducible under any model that preserves MA sovereignty.
- Differentiation by constitutional importance: operational rules change easily; governance structure requires more consensus; core trustworthiness guarantees require extraordinary consensus
- OQ-38B01-06 and OQ-38B02-01 are addressable: CIC existence and AC-31 minimum become Tier 3 provisions

**Verdict: SELECTED.** See Part E.

---

## Part E — Selected Model: Graduated Threshold with Protected Provisions

### E.1 38B05-INV-01: Constitutional Floor Invariant

**38B05-INV-01:** The ElectionConstitution must specify three tiers of provisions with distinct amendment procedures. Every EC provision must be assigned to exactly one tier. No provision may be amended through a procedure less demanding than its assigned tier requires. An amendment that purports to reclassify a Tier 3 provision to a lower tier is itself a Tier 3 amendment subject to Tier 3 requirements.

This invariant prevents the "reclassification" attack: the amendment process cannot be weakened by first reclassifying protected provisions to lower tiers and then amending them through the easier process.

### E.2 Tier Definitions

**Tier 1 — Operational Provisions:** Provisions governing operational configuration of the election system — voting window duration, registration deadlines, ballot format requirements, reporting formats, candidate eligibility operational criteria. These change as organizational needs evolve. Amendment at Tier 1 is expected to occur regularly.

**Tier 2 — Governance Provisions:** Provisions governing the structure and process of constitutional governance — authority aggregate mandates (within existing independence forms), challenge processes, certification processes, term lengths, appointment procedures (within the 38B-04 framework). These change as constitutional understanding matures. Amendment at Tier 2 requires significant consensus.

**Tier 3 — Protected Core Provisions:** Provisions protecting the fundamental constitutional trustworthiness guarantees that define what this architecture IS. These are the provisions whose removal would transform the architecture into something fundamentally different from what was constitutionally adopted. See E.3 for the Protected Core Catalog.

### E.3 Protected Core Catalog (Tier 3)

The following provisions are Tier 3 Protected Core and require the Tier 3 amendment process:

1. **Vote anonymity guarantee (VO-1 / AC-17):** The guarantee that no voter may be linked to their vote. The votes table has no user_id column; no mechanism for voter-vote linkage may be constitutionally introduced.

2. **Challenge rights (S-1/S-2/S-3 standing):** The right of directly affected parties, constitutional observers, and authority peers to challenge constitutional decisions before CAB. The standing classes may not be eliminated; the right of challenge may not be procedurally nullified.

3. **CIC existence and interpretive independence (OQ-38B01-06 addressed):** The Constitutional Interpretation Chamber must exist as the authoritative constitutional interpreter. MA may not amend EC to abolish CIC; MA may not amend the independence requirements of CIC to bring CIC under operational direction of any authority aggregate. See Part F for the full resolution of OQ-38B01-06.

4. **AC-31 minimum requirement (OQ-38B02-01 addressed):** The constitutional requirement that audit-grade evidence authenticity must have an independent reference standard (AC-31). The AC-31 requirement may not be deleted from EC; the independence requirement for AC-31 may not be amended to permit self-authentication. See Part G for the full resolution of OQ-38B02-01.

5. **The amendment process itself:** The three-tier amendment structure, the CIC vetting requirement, and the Tier 3 threshold requirements may not be amended through any process less demanding than Tier 3. The mechanism that protects the protected provisions is itself protected.

6. **MA ratification requirement:** MA must remain the ratifying body for constitutional amendments. The amendment process may not be amended to remove MA from the ratification chain or to transfer constitutional ratification authority to any other body.

7. **CAB independence from direct operational control:** The ChallengeAdjudicationBody must exist as a constitutionally independent body. MA may not amend EC to make CAB operationally directed by any authority aggregate whose decisions CAB adjudicates. (Note: this does not protect the specific appointment model; it protects the independence principle. See OQ-38B05-02.)

### E.3.1 Protected Core Justification Matrix *(R1 Applied)*

*For each Protected Core provision: threats mitigated, architectural dependency, consequence if removed, and why Tier 2 protection is insufficient.*

| Provision | Threats Mitigated | Architectural Dependency | Consequence if Removed | Why Tier 2 Insufficient |
|-----------|------------------|------------------------|----------------------|------------------------|
| **1. Vote anonymity (VO-1)** | TM-20 (voter coercion), voter targeting, post-election persecution | VO-1 is the constitutional basis for the votes table having no user_id; the Vote aggregate's architecture has no constitutional standing without this guarantee | Retroactive voter-vote linkage becomes constitutionally permissible; elections cease to be free in any meaningful sense | Supermajority protection does not exceed political calculation; a motivated majority controlling two-thirds of MA can vote their own voters unmasked |
| **2. Challenge rights (S-1/S-2/S-3)** | TM-04 (CAB capture via standing manipulation), ADR7-INV-02 enforcement, systematic suppression of constitutional challenges | Challenge rights are the enforcement mechanism for every other constitutional guarantee; without them, all Tier 3 protections are unenforceable — they exist but cannot be vindicated | Removing challenge rights first renders all remaining Tier 3 protections unenforceable without touching them; this is the bootstrapping attack on all other protected provisions | Challenge rights protect every other protection; a supermajority could remove challenge rights first, then everything else through the unguarded amendment process |
| **3. CIC existence and interpretive independence** | TM-13 (no interpretation authority), self-serving interpretation by authority aggregates, constitutional ambiguity exploitation | All 38B governance specifications depend on CIC for authoritative interpretation; Gap 4 was the founding recognition that without CIC, constitutional disputes are unresolvable | Constitutional governance collapses into interpretive chaos; each authority aggregate interprets its own mandate; all other Tier 3 protections become subject to self-serving interpretation by the bodies they are meant to constrain | CIC interprets all other protections; removing CIC by supermajority leaves all remaining Tier 3 provisions without authoritative interpretation at the moment they are most needed |
| **4. AC-31 minimum requirement** | TM-19 (AC-31 capture C-F→F), TM-47 (certification ratchet — automatic, no deliberate act required), TM-48 (reference disagreement) | AC-31 is the constitutional grounding for ADR3-INV-01 (evidence strata cannot be collapsed); without AC-31, CO-3 becomes self-certifying and TM-47 becomes unconditional F | Election evidence authenticity becomes self-certifiable; the certification chain becomes circular; CO-5 validity collapses; TM-47 characterizes this as more dangerous than the EC ratchet (AW-05-07) because it operates automatically | An AC-31 capture coalition could plausibly achieve supermajority MA composition through appointment influence (OBS-38B04-02), then amend AC-31 away cleanly |
| **5. Amendment process itself** | Bootstrapping attack (weaken Tier 3 threshold through Tier 1 amendment), reclassification attack (move Tier 3 provisions to lower tier) | All other Protected Core provisions depend on the amendment process as their protection mechanism; if the mechanism is weakened cheaply, all provisions are weakened | Constitutional Self-Destruction becomes trivially easy: destroy the protection mechanism first, then all other protections fall through the weakened process | This is the meta-protection; if it can be weakened by supermajority, a coalition that achieves supermajority once gains permanent leverage over all subsequent protections |
| **6. MA ratification requirement** | Amendment by non-sovereign actors; constitution becoming amendable by authority aggregates without MA; legitimacy collapse | MA is the source-of-source (OBS-ADR7-SS1); removing MA from the amendment chain severs the sovereign legitimacy connection from constitutional evolution; all post-removal amendments would have contested legitimacy | Amendments could be ratified by bodies with less than sovereign legitimacy; all subsequent amendments have contested legitimacy tracing to a non-sovereign ratification; AA-01 dependency becomes unanchored | The sovereign foundation of the entire amendment process; if it can be amended away by supermajority, the mechanism for all future constitutional evolution loses its legitimacy grounding |
| **7. CAB independence principle** | TM-04 (CAB capture via structural subordination), CA+CAB coalition (rated unconditional F in 38A-03), F-1 through F-7 FAIL catalog expansion | CAB is the enforcement body for challenge rights (Tier 3 item 2); without CAB independence, challenge rights are procedurally hollow — they exist but no independent body adjudicates them | Challenge adjudication becomes captured; constitutional challenges are adjudicated by a body under the direction of those being challenged; CA+CAB coalition (F) becomes constitutionally enabled | The CA+CAB coalition failure was rated unconditional F; a Tier 2 supermajority amendment to make CAB operationally subordinate to CA would immediately produce the F-1 failure condition |

### E.3.2 Rejected Candidates for Tier 3 *(R2 Applied)*

*Candidates evaluated for Tier 3 protection that were placed in Tier 2 or lower, with rationale.*

| Candidate | Assigned Tier | Why Not Tier 3 |
|-----------|--------------|----------------|
| **GovernanceState** | Tier 2 | GovernanceState is a record-keeping aggregate — it records constitutional authority rather than constituting it. The constitutional rights underlying GovernanceState (phase authority, temporal governance) are protected through the governance structure rather than GovernanceState's specific technical form. Tier 3 protection would over-constitute what is an operational record; the form should be able to evolve. |
| **CertificationAuthority existence** | Tier 2 | CertificationAuthority is the terminal evaluator, but its existence is a governance mechanism rather than a fundamental constitutional right. Elections can exist with different certification forms (external evaluation, international observation) — the CO-5 framework is a governance construct. The independence principle of CertificationAuthority (Option C External) is protected through Tier 2; its specific form should be able to evolve. |
| **AuditScopeAuthority / AuditExecutionAuthority** | Tier 2 | These aggregates implement the constitutional right to audit (IR-H), but the specific form of audit authority is a governance question. Challenge rights (Tier 3 item 2) protect the right to audit through S-2 standing; the specific aggregates implementing that right are governance mechanisms that should be able to evolve. Over-constituting the operational audit structure would prevent necessary refinement as constitutional understanding matures. |
| **GovernanceAuthority existence** | Tier 2 | GovernanceAuthority is an operational governance aggregate whose specific form should evolve. The constitutional requirement for election phase governance (someone must authorize phases) is protected through challenge rights — arbitrary phase governance is challengeable. Over-constituting the specific form of governance authority would freeze a 2026-era design decision permanently. |
| **Appointment process (38B-04 model)** | Tier 2 | The specific appointment chains (MA → CriteriaAuthority → EnrollmentAuthority, etc.) are governance mechanisms, not constitutional rights. The independence principles of appointed authorities are protected: CIC independence (Tier 3 item 3) and CAB independence (Tier 3 item 7). The specific appointment mechanics should be able to evolve as constitutional understanding matures. Freezing the 38B-04 model at Tier 3 would produce exactly the constitutional fossilization that the graduated threshold model is designed to prevent. |
| **Membership Assembly existence** | Pre-constitutional / not tiered | MA's existence and legitimacy is AA-01 — pre-constitutional. Neither the amendment process nor any tier of protection can constitutionally ground MA's existence within the architecture, because MA is the source-of-source. Protecting MA's existence at Tier 3 would be internally circular: the protection would depend on MA ratification, which would depend on MA existence — a self-referential legitimacy claim. |

**Cross-model tension (INT-38B05-01 — DeepSeek Integration):** The DeepSeek 38B-05 parallel specification classifies two additional provisions as unamendable that Claude's model assigns to Tier 2: (a) CertificationAuthority external independence, and (b) three trust root separation (Legitimacy, Authenticity, and Temporal roots must remain structurally distinct). Claude's model places CertificationAuthority in the Rejected Candidates table on the grounds that the independence principle is protected at Tier 2 and the specific form should be able to evolve. The trust root separation question is new — if the three trust roots can be merged or collapsed through Tier 2 amendment without triggering Tier 3 protection, the structural architecture of the trustworthiness model could be altered without the near-unanimous threshold. This tension is not resolved here; it is a substantive disagreement carried to 38B synthesis as OQ-38B05-05.

### E.4 Tier-Specific Amendment Requirements

| Requirement | Tier 1 (Operational) | Tier 2 (Governance) | Tier 3 (Protected Core) |
|-------------|---------------------|---------------------|-------------------------|
| **CIC opinion** | Required | Required | Required (recusal rules apply) |
| **Deliberation window** | 30 days (EC-specified minimum) | 90 days (EC-specified minimum) | 180 days (EC-specified minimum) |
| **Authority aggregate response** | Optional | Expected | Required for all affected aggregates |
| **MA ratification threshold** | Simple majority | Two-thirds supermajority | Near-unanimity (EC to specify: ≥90% or all-but-one) |
| **Challenge window after ratification** | 30 days | 60 days | 90 days |
| **Minimum between consecutive Tier 3 amendments** | N/A | N/A | EC-specified cooling period (minimum 2 years) |
| **Amendment during active election** | Prohibited | Prohibited | Prohibited |

**Note on Tier 3 threshold:** "Near-unanimity" is not mathematical unanimity. This is a deliberate choice: requiring mathematical unanimity would give a single MA member veto power, which creates a different concentration risk. Near-unanimity (90% or all-but-one) requires extraordinary consensus without creating individual veto power.

---

## Part F — OQ-38B01-06 Resolution: CIC Constitutional Floor

**Question:** Can CIC be abolished via EC amendment?

**Resolution under selected model:**

CIC existence and interpretive independence are Tier 3 Protected Core provisions (E.3, item 3). Abolishing CIC requires:
- Tier 3 amendment process
- 180-day deliberation window
- All authority aggregates must formally respond
- CIC vetting — but CIC cannot vet an amendment that abolishes itself

**CIC recusal for self-abolition amendments:**

If a proposed amendment would abolish or fundamentally alter CIC's constitutional existence or interpretive independence, CIC is recused from producing the required constitutional opinion. In this case, the constitutional opinion is produced by one of the following (in order of preference):
- A panel composed of all prior CIC members who are willing and available
- If insufficient prior members are available: a constitutional review panel designated by MA under a separate EC provision establishing qualifications for constitutional review panel membership

The constitutional review panel holds the same obligation as CIC: produce a constitutional opinion on tier determination, Protected Provision conflict, self-dealing check, and retroactive effects. The panel opinion is advisory; MA may ratify against it subject to immediate CAB challenge.

**OQ-38B01-06 resolution status:** ADDRESSED. CIC constitutional floor is established at Tier 3. The abolition review mechanism is identified and the recusal solution is specified. The EC must include the constitutional review panel designation provisions — this is a required EC design action.

**Residual:** OQ-38B05-01 (below) — the constitutional review panel qualification criteria require EC specification not within the scope of 38B-05 to determine.

---

## Part G — OQ-38B02-01 Resolution: AC-31 Constitutional Floor

**Question:** Can the AC-31 requirement be deleted from EC via amendment?

**Resolution under selected model:**

The AC-31 minimum requirement is a Tier 3 Protected Core provision (E.3, item 4). Deleting AC-31 requires the Tier 3 amendment process: 180-day deliberation, near-unanimous MA ratification, CIC constitutional opinion confirming no Protected Provision conflict (which CIC cannot provide if the amendment deletes a Tier 3 provision — generating an adverse CIC opinion that MA must override to proceed, making the amendment immediately challengeable).

**What "deleting AC-31" means under this model:**

An amendment could attempt to delete AC-31 (the requirement itself), or it could attempt to modify AC-31 to reduce its independence requirements (effectively hollowing out the requirement without formally deleting it). The Tier 3 protection covers both:

- Deletion of AC-31: Tier 3 by definition (item 4 in catalog)
- Modification to remove independence requirement: Tier 3 — CIC must opine on whether the modification conflicts with the AC-31 Protected Provision; modifications that reduce independence requirements below the "independent reference standard" threshold conflict with item 4 and generate a negative CIC opinion

**OQ-38B02-01 resolution status:** ADDRESSED. AC-31 constitutional floor is established at Tier 3. Both the deletion and the hollowing-out attack vectors are addressed.

---

## Part H — Retroactive Effect of Amendments

### H.1 General Rule: Prospective Effect Only

Amendments take effect prospectively from the enactment date (challenge window close or designated future date). An amendment does not retroactively:
- Invalidate elections conducted under prior constitutional rules
- Revoke CO-5 certifications issued under prior constitutional rules
- Alter the constitutional legitimacy of authority aggregate decisions made before the amendment took effect

### H.2 OQ-38A05-02 Intersection: PROTECTED

If an amendment changes AC-31 standards, anonymity requirements, or any other provision that bears on the Finality vs. Validity question, the retroactive effect on issued TS-1 (constitutionally final) certifications is PROTECTED. CIC must flag the intersection in its constitutional opinion (Part C.2), but does not rule on the underlying OQ-38A05-02 question — that is for CIC's constitutional interpretation authority to address when an actual case arises.

**38B-05 does not resolve OQ-38A05-02.** The protection of this question is carried forward intact.

### H.3 Constitutional Defect Corrections

If a constitutional review (not an amendment) reveals that a prior constitutional provision was itself constitutionally defective — not merely imperfect, but constitutionally invalid at the time it was adopted — the correction may operate as follows:
- The corrective amendment addresses the defect prospectively (for future elections)
- The corrective amendment may designate that elections conducted under the defective provision are valid-as-of-their-time (grandfathered) or invalid-as-of-correction (subject to review)
- The retroactive invalidity question for grandfathered vs. reviewed elections requires CIC constitutional interpretation — it is not self-executing from the amendment text

---

## Part I — Amendment Legitimacy Chain

### I.1 Conditions for Constitutional Legitimacy of an Amendment

An amendment is constitutionally legitimate if all of the following are satisfied:

1. Proposed by a party with constitutional standing (Part C.1)
2. CIC constitutional opinion (or recusal panel opinion) completed and published (Part C.2)
3. Protected Provision conflict, if any, either not found by CIC or overridden through Tier 3 process (Part C.2 / Part E.4)
4. Deliberation window respected (Part C.3)
5. Required MA ratification threshold met for the amendment's tier (Part E.4)
6. Amendment not ratified during active election cycle (Part C.4)
7. Challenge window closed or all challenges dismissed (Part C.5)
8. Amendment recorded in GovernanceState as a constitutional-phase event (Part C.6 / 38B-03)

### I.2 Terminal Legitimacy Dependency

The amendment legitimacy chain terminates at MA legitimacy, which terminates at AA-01 (pre-constitutional assumption). An amendment ratified through the above process is constitutionally legitimate to the degree that MA itself is constitutionally legitimate.

This is irreducible under any model that preserves MA sovereignty. The amendment process cannot bootstrap legitimacy that MA does not already possess. OBS-38B04-04 applies to the amendment chain as it does to every other MA function.

### I.3 Self-Amendment Limitation

The amendment process cannot amend its own procedural requirements through a procedure less demanding than Tier 3 (38B05-INV-01). This prevents the "bootstrapping attack" — using a low-threshold amendment to lower the threshold for a high-protection amendment. The EC itself must specify this as an invariant binding on CIC's tier-determination analysis.

---

## Part J — MA Concentration at Close of 38B-05

### J.1 Amendment Ratification as MA Function #15

Amendment ratification — as the constitutional body that ratifies EC amendments — is MA's fifteenth constitutional function, added by the formal specification of the amendment process in 38B-05. (The function existed implicitly before; 38B-05 specifies it explicitly and constrains it through the tier system.)

**MA constitutional functions at close of 38B-05:**

*From ADR baseline:* EC ratification, ADR ratification, terminal appeal (R-6), source-of-source legitimacy (OBS-ADR7-SS1)

*From 38B-01:* CIC establishment ratification, CIC dissolution approval

*From 38B-02:* AC-31 Tier 2 designation

*From 38B-03:* Background terminal phase dispute authority

*From 38B-04:* CriteriaAuthority appointment, AuditAuthority appointment, GovernanceAuthority appointment, CertificationAuthority designation, CAB appointment, CIC appointment

*From 38B-05 (explicitly specified):* EC amendment ratification (governed by tier-specific thresholds)

**Total: 15 explicitly specified MA constitutional functions.**

### J.2 OBS-38B04-04 Forward Dependency

Amendment ratification is now formally specified as a MA function — and the amendment chain is the constitutional vehicle for all future constitutional evolution. OBS-38B04-04 applies: every future amendment will depend on MA legitimacy, which depends on AA-01. The 38B-05 specification makes this dependency explicit and permanent.

This is not a defect; it is the constitutional bedrock condition. No constitutional amendment process for a sovereign assembly can avoid depending on the legitimacy of that assembly. But it must be named clearly: **the entire future constitutional evolution of this architecture is contingent on a pre-constitutional assumption that is unresolved within the architecture.**

### J.3 SCF-38B04-01 Resolution Status (CAB Appointment Tension)

**The question from SCF-38B04-01:** Can MA amend away CAB's independence?

**Resolution under 38B-05:**

CAB independence from direct operational control is Tier 3 Protected Core (E.3, item 7). MA cannot amend EC to place CAB under operational direction of any authority aggregate whose decisions CAB adjudicates.

However: CAB's specific appointment model (MA appoints CAB) is NOT Tier 3 protected. The appointment model is a Tier 2 governance provision — it can be modified through Tier 2 process (two-thirds supermajority + CIC review). This means:

- What is protected (Tier 3): CAB's independence principle — CAB cannot be made operationally dependent on what it adjudicates
- What is not protected (Tier 2): the specific mechanism by which CAB members are selected

This is a constitutional distinction between independence principle (protected) and appointment mechanics (governable). The tension from SCF-38B04-01 is resolved at the principle level. The specific appointment chain (MA → CAB → adjudicates MA decisions) remains as a Tier 2 governance matter subject to evolution.

**SCF-38B04-01 resolution status:** ADDRESSED. The independence principle is protected at Tier 3. The appointment mechanism is governed at Tier 2.

---

## Part K — OBS-38A06-SD1 Assessment: Constitutional Self-Destruction

**Before 38B-05:** OBS-38A06-SD1 — Constitutional Self-Destruction was legally possible via any valid EC amendment process; no floor existed; no procedural friction.

**After 38B-05:** The Graduated Threshold model with Protected Provisions addresses Constitutional Self-Destruction as follows:

**What is now protected:**
- Vote anonymity cannot be removed through Tier 1 or Tier 2 process; requires near-unanimous MA + 180-day deliberation + CIC constitutional opinion
- Challenge rights cannot be eliminated
- CIC cannot be abolished (with Tier 3 procedural burden and CIC recusal mechanism)
- AC-31 minimum cannot be deleted
- The amendment process itself cannot be weakened to enable easier removal of protected provisions
- CAB independence principle cannot be removed

**What remains possible:**
- A sufficiently motivated MA, with near-unanimous composition, following full Tier 3 process, can still remove any Tier 3 provision. This is the constitutional price of preserving MA sovereignty.
- The legitimacy of such a removal would depend on whether MA's own legitimacy is intact (AA-01) and whether the Tier 3 process was followed (challengeable through CAB/CIC).

**OBS-38A06-SD1 residual risk:** The Graduated Threshold model does not eliminate Constitutional Self-Destruction; it makes it constitutionally expensive and constitutionally visible. A Constitutional Self-Destruction attempt via Tier 3 process would: require near-unanimous MA agreement, require a 180-day deliberation window during which all authority aggregates and constitutional observers could respond, require a CIC (or recusal panel) constitutional opinion that would document the destruction, generate an immediate CAB challenge opportunity, and create a 90-day challenge window during which the architecture has maximum transparency into what is happening.

This is not a guaranteed prevention. It is a constitutional early warning and friction system. For a trustworthy election architecture, this is the appropriate level of protection: self-destruction is not impossible, but it cannot be silent or fast.

---

## Part L — Gap 3 Status Assessment

**Gap 3 is SUBSTANTIALLY ADDRESSED.**

**Named deliverables delivered:**
- OBS-38A06-SD1 constitutional floor: ✓ established (Graduated Threshold with Protected Provisions)
- OQ-38B01-06 (CIC constitutional floor): ✓ addressed (Tier 3 protection; recusal mechanism specified)
- OQ-38B02-01 (AC-31 constitutional floor): ✓ addressed (Tier 3 protection; hollowing-out attack addressed)

**What is specified:**
- Amendment mechanics: proposal, CIC vetting, publication, deliberation, ratification, challenge window, enactment
- Constitutional floor: three-tier model; Protected Provisions catalog (7 provisions)
- Tier-specific requirements: thresholds, timelines, deliberation windows, challenge windows
- Retroactive effect: prospective only; OQ-38A05-02 PROTECTED
- Amendment legitimacy chain: 8 conditions for constitutional legitimacy
- MA concentration at 15 functions: formally documented
- OBS-38B04-04 forward dependency: amendment chain as vehicle for future constitutional evolution; all depends on AA-01
- SCF-38B04-01 resolution: CAB independence principle (Tier 3) vs. appointment mechanics (Tier 2) distinguished

**What remains open:**
- OQ-38B05-01: Constitutional review panel qualification criteria for CIC-recusal amendments (EC specification required)
- OQ-38B05-02: Should CAB appointment process be Tier 3 protected, or is Tier 2 governance sufficient? (assessed here as Tier 2; open to ARB re-evaluation)
- OQ-38B05-03: AA-01 forward dependency through the amendment chain — how the architecture characterizes the dependence of all future constitutional evolution on pre-constitutional MA legitimacy
- OQ-38B05-04: OQ-38A05-02 intersection with amendments affecting AC-31 or anonymity provisions — PROTECTED pending CIC ruling
- OQ-38B05-05: **[MAJOR ARCHITECTURAL QUESTION — elevated per Senior Architect Review 2026-06-18]** One-way ratchet — can the Protected Core Catalog be expanded through Tier 2 governance amendment? And should three trust root separation (Legitimacy, Authenticity, Temporal structural distinction) be explicitly protected as a Tier 3 constitutional principle? (38B-06 synthesis — see INT-38B05-01; see ARB-Review OBS-38B05-05)
- OQ-38B05-06: MA self-removal procedural mechanism — when MA ratifies an amendment removing MA from the amendment ratification chain, what procedural mechanism (if any) applies? Analogous to CIC self-abolition (Part F) but no equivalent mechanism specified. (38B-06 synthesis — EC design prerequisite)
- OQ-38B05-07: **[POTENTIALLY PROGRAM-CRITICAL — elevated per Senior Architect Review 2026-06-18]** ADR-EC tier relationship — what is the relationship between ADR architectural decisions (especially ADR-2 independence form decisions) and EC tier provisions? If ADR-2 decisions are not formally EC tier provisions, they may be amendable without tier procedural protection. Until specified, CertificationAuthority independence form and non-CIC/CAB D43 independence requirements have ambiguous constitutional tier assignment. Must be resolved before 38C governance closure.

---

## Part M — Open Questions

**OQ-38B05-01:** Constitutional review panel for CIC-recusal amendments. When an amendment would abolish or fundamentally alter CIC, CIC is recused. Who constitutes the constitutional review panel? What qualifications? How designated? How selected if no prior CIC members are available? This requires EC design not within 38B-05's scope to determine — it is an EC specification question.

**OQ-38B05-02:** Is the CAB appointment process (Tier 2 governance) adequately protected, or should the independence principle protection be extended to include appointment mechanics? The current model distinguishes principle (Tier 3) from mechanics (Tier 2). An argument exists that the appointment mechanics are constitutionally load-bearing — a Tier 2 amendment to the appointment process could gradually erode the independence principle without triggering Tier 3 protection. Carried to 38B synthesis for assessment.

**OQ-38B05-03:** AA-01 forward dependency scale. The entire future constitutional evolution of this architecture is governed by the amendment process, which depends on MA legitimacy, which depends on AA-01. As 38B concludes, the architecture must explicitly characterize this dependency in 38B synthesis. The dependency is not unique to 38B-05 — it runs through the entire 38B series. But the amendment chain is where it becomes most consequential: it is not just that current governance depends on AA-01, but that all future governance evolution depends on it as well.

**OQ-38B05-04 (PROTECTED):** The intersection between the amendment process and OQ-38A05-02 (Finality vs. Validity). If an amendment changes AC-31 standards or anonymity requirements, what is the constitutional effect on previously issued TS-1 certifications? This is not within 38B-05's scope to resolve. Routed to CIC when an actual case arises. PROTECTED.

**OQ-38B05-05 [MAJOR ARCHITECTURAL QUESTION — elevated per Senior Architect Review 2026-06-18]:** One-way ratchet and trust root separation. Two related questions from DeepSeek 38B-05 integration: (a) Can the Protected Core Catalog be expanded — new provisions added at Tier 3 — through a Tier 2 governance amendment? If yes, the constitutional floor is a one-way ratchet: the catalog of protected provisions can grow through governance-level amendments, progressively constraining MA's amendment authority in ways not contemplated at adoption. Over time, the Protected Core may accumulate provisions through Tier 2 amendments, and each added provision would then require near-unanimous Tier 3 threshold to remove. (b) Should the structural separation of the three trust roots (Legitimacy = ElectionConstitution, Authenticity = AC-31, Temporal = GovernanceState) be explicitly protected at Tier 3 as a constitutional principle? The current Protected Core protects CIC (Legitimacy root interpreter), AC-31 minimum (Authenticity root), and GovernanceState at Tier 2 (Temporal root record) — but does not protect the STRUCTURAL SEPARATION of the three roots from each other. A Tier 2 amendment could collapse two trust roots (e.g., authorizing CIC to also serve as AC-31 certifier, merging Legitimacy and Authenticity roots) without clearly violating any of the seven current Tier 3 provisions. This would fundamentally alter the trustworthiness architecture without triggering Tier 3 protection. The Senior Architect Review (2026-06-18) elevated this from a candidate omission to a Major Architectural Question, noting: "Collapsing them can fundamentally alter the architecture. I would not let this disappear into a future backlog." DeepSeek 38B-05 includes trust root separation as formally unamendable (INT-38B05-01). Carried to 38B-06 synthesis as a priority question.

**OQ-38B05-06:** MA self-removal procedural mechanism. When MA ratifies an amendment that removes MA from the amendment ratification chain, what procedural mechanism applies? The CIC self-abolition scenario has a specified recusal mechanism (Part F — constitutional review panel substitutes for CIC). The MA self-removal scenario has no equivalent: MA reviews and ratifies an amendment removing MA from future ratification chains. This is a constitutional self-reference analogous to Part F's CIC recusal scenario but without a specified constitutional resolution. The self-referential character is acknowledged in OBS-38B05-02 (terminal legitimacy dependency) but the procedural mechanism question is not resolved within 38B-05's scope. EC design prerequisite question.

**OQ-38B05-07 [POTENTIALLY PROGRAM-CRITICAL — elevated per Senior Architect Review 2026-06-18]:** ADR-EC tier relationship. What is the relationship between ADR architectural decisions (particularly ADR-2 independence form decisions for all five D43 functions) and EC tier provisions? ADR-2 specified independence forms ratified by MA — Option B (Committee Independence) for Enrollment, Criteria, GovernanceAuth; Option D (Hybrid) for Audit; Option C (External Organization) for Certification. If these ADR-2 decisions are not formally EC tier provisions, they may be amendable through MA ratification without the tier system's procedural protections. If they are EC tier provisions, which tier are they — and can a Tier 2 amendment reduce CertificationAuthority independence from Option C to a weaker form without triggering Tier 3 protection? The Senior Architect Review (2026-06-18) elevated this to potentially program-critical, noting: "Many architectural guarantees currently rely on ADR-2, ADR-4, ADR-6, ADR-7 being treated as stable. If the constitutional amendment model and ADR model are not formally related, you may eventually discover 'Constitution says X, ADR says Y' with no defined precedence. That can become a governance contradiction." Must be resolved before 38C governance closure. Until specified, CA independence form and non-CIC/CAB D43 independence requirements have ambiguous constitutional tier assignment (OBS-38B05-05 gaps 1 and 2).

---

## Part N — 38B Series Completion Assessment

**38B-05 is the final gap specification of the 38B Constitutional Governance Specification series.**

The five confirmed constitutional gaps have now been addressed:

| Gap | Subject | Round | Status |
|-----|---------|-------|--------|
| Gap 4 | Constitutional Interpretation Authority | 38B-01 | APPROVED |
| Gap 5 | AC-31 Governance | 38B-02 | APPROVED |
| Gap 7 | GovernanceState Phase Record Governance | 38B-03 | APPROVED |
| Gap 6 | Authority Appointment Process | 38B-04 | APPROVED |
| Gap 3 | EC Amendment Governance | 38B-05 | APPROVED WITH REVISIONS APPLIED (R1–R5) |
| Gap 8 | Post-Finality Constitutional Review | — | DEFERRED (OQ-38A05-02 pending) |

**38B-06 (Constitutional Governance Synthesis) is the next deliverable — authorization PENDING application of R1–R5 and ARB confirmation.**

The synthesis must address:
- OBS-38B04-02: MA concentration at 15 functions — is this constitutionally acceptable or does it constitute an unacceptable systemic single point of governance failure?
- OBS-38B04-04: AA-01 dependency scale — characterize what the 38B governance model achieves and what it assumes
- SCF-38B04-01: CAB appointment tension — OQ-38B05-02 open question on whether Tier 2 protection is sufficient
- OQ-38B05-03: AA-01 forward dependency through the amendment chain (forward-permanent urgency now formally documented)
- OQ-38B05-05: Trust root structural separation — Major Architectural Question requiring dedicated treatment (not backlog)
- OQ-38B05-07: ADR-EC tier relationship — resolution prerequisite before 38C governance closure
- Whether 38B has adequately protected the constitutional architecture against the five dominant constitutional risks identified in 38A

---

## ARB Decision Block

**[APPROVED WITH MINOR REVISIONS APPLIED]**

### ARB Decision Record

**ARB Review:** 2026-06-18
**Verdict:** APPROVED WITH MINOR REVISIONS

**Revisions required and applied:**
- **R1:** E.3.1 Protected Core Justification Matrix added — for each of the seven Tier 3 provisions: threats mitigated, architectural dependency, consequence if removed, why Tier 2 is insufficient. The catalog is now research-discovered (traced to 38A threat ratings and 38B architectural findings), not architect-selected.
- **R2:** E.3.2 Rejected Candidates for Tier 3 added — six candidates evaluated but placed in Tier 2 or lower (GovernanceState, CertificationAuthority, AuditScopeAuthority/AuditExecutionAuthority, GovernanceAuthority, appointment process, MA existence), each with explicit rationale for why Tier 3 over-constitutes what is properly a governance mechanism.
- **R3:** Gap 3 status confirmed as SUBSTANTIALLY ADDRESSED — not RESOLVED. Four open questions (OQ-38B05-01 through 38B05-04) remain explicitly named and carried forward.

**Verdict rationale:** The three-tier model with Protected Provisions is architecturally stronger than the DeepSeek eternity-clause model because it preserves MA sovereignty while making Constitutional Self-Destruction constitutionally expensive, visible, and challengeable. AA-01 consistency is maintained throughout — nothing is claimed to be beyond the reach of MA legitimacy, which correctly reflects the pre-constitutional status of that legitimacy. The R1 revision eliminates the "architect-selected" criticism by grounding every Tier 3 provision in a specific 38A threat finding or architectural dependency. The R2 revision demonstrates the evaluation discipline: six plausible candidates were evaluated and rejected with explicit rationale.

**38B-05 APPROVED WITH MINOR REVISIONS (R1–R5 now applied).**
**38B-06 authorization: PENDING ARB confirmation of R1–R5 application. Sequence required: Apply R1–R5 → 38B-06 Synthesis → ARB review of synthesis → 38B Closure Decision → then 38C authorization. (Senior Architect Review 2026-06-18)**

---

### Appointment Mechanics Decision

**Amendment mechanics specified for all phases:** proposal, CIC constitutional vetting, publication and deliberation, MA ratification, post-ratification challenge, enactment.

**Key mechanisms:**
- CIC vetting is mandatory before any ratification
- CIC recusal mechanism for self-affecting amendments
- Multi-election amendment prohibition
- GovernanceState records amendment as constitutional-phase event (38B-03 integration)

### Constitutional Floor Decision

**Graduated Threshold with Protected Provisions selected.**

- 38B05-INV-01: Three tiers; no amendment via procedure less demanding than assigned tier; reclassification of Tier 3 provision is itself Tier 3
- Protected Core catalog: seven provisions (anonymity, challenge rights, CIC existence, AC-31 minimum, amendment process itself, MA ratification requirement, CAB independence principle)
- Tier 3 threshold: near-unanimity (≥90%); 180-day deliberation; 90-day challenge window; minimum 2-year cooling period

### Named Deliverables Delivered

- **OBS-38A06-SD1 floor:** Established at Tier 3 — Constitutional Self-Destruction is constitutionally expensive, visible, and challengeable; not impossible, but constitutionally audible
- **OQ-38B01-06 (CIC floor):** Addressed — Tier 3 protection; recusal mechanism specified
- **OQ-38B02-01 (AC-31 floor):** Addressed — Tier 3 protection; hollowing-out attack addressed

### Observations Produced

**OBS-38B05-01 (R5 applied):** The Constitutional Self-Destruction risk (OBS-38A06-SD1) cannot be architecturally eliminated within a sovereign assembly model. The Graduated Threshold model provides **active constitutional friction** — not prevention, not merely documentation, not merely delay. The combination of near-unanimity threshold, 180-day deliberation windows, CIC adverse opinions, challenge windows, and 2-year cooling periods creates structural resistance that changes the political economy and feasibility of Constitutional Self-Destruction. Specifically: Constitutional Self-Destruction under this model requires conditions (near-unanimous sustained commitment across multiple years, with full constitutional visibility at every step) that are structurally incompatible with normal democratic governance. The model is the maximum constitutionally honest protection available — anything stronger either requires claiming constitutional impossibilities (unamendable provisions under AA-01 uncertainty) or would require non-constitutional enforcement mechanisms outside the architecture's scope. (38B-05-ARB-Review OBS-38B05-07)

**OBS-38B05-02 (R4 applied):** The amendment process is now MA function #15. All future constitutional evolution of this architecture depends on MA ratification, which depends on MA legitimacy, which depends on AA-01. This is the most consequential expression of OBS-38B04-04: it is not merely that current constitutional governance depends on AA-01, but that the entire future trajectory of constitutional evolution does as well. **Corollary (forward-permanent urgency):** Before 38B-05, AA-01 was a current-governance dependency — existing governance arrangements depended on it. After 38B-05, AA-01 is a forward-permanent constitutional architecture dependency — the vehicle for all future constitutional change depends on it permanently. The urgency of AA-01 resolution has therefore shifted from a present concern to an architecturally permanent concern. Every future constitutional amendment, including future amendments to the Protected Core Catalog, inherits this dependency. (38B-05-ARB-Review OBS-38B05-06)

**OBS-38B05-03 (SCF-38B04-01 Resolution):** CAB independence principle is Tier 3 protected. CAB appointment mechanics are Tier 2 governed. This distinguishes constitutional independence (what CAB must be) from constitutional process (how CAB members are selected). This is the correct constitutional architecture for a judicial-analogue body in a democratic system.

**OBS-38B05-04 (ARB-Review Output — Protected Core Assessment):** See 38B-05-ARB-Review Part G. All seven Protected Core provisions are individually justified and internally consistent. Challenge rights (item 2) and AC-31 minimum (item 4) have the strongest technical justifications. The catalog has an implicit hierarchical structure: items 2+7 (challenge rights + CAB independence) constitute the enforcement layer for all other provisions; item 5 (amendment process) is the meta-protection; items 1+3+4 (anonymity, CIC, AC-31) are the substantive constitutional rights; item 6 (MA ratification) is the sovereignty anchor.

**OBS-38B05-05 (ARB-Review Output — Candidate Protected Provision Assessment):** See 38B-05-ARB-Review Part H. Five candidates correctly excluded (GovernanceState, GovernanceAuthority, AuditScopeAuthority/AuditExecutionAuthority, appointment governance). Two design gaps identified: (1) CertificationAuthority independence form and (2) non-CIC/CAB D43 independence requirements both lack explicit EC tier assignment — the relationship between ADR-2 architectural decisions and EC tier provisions is unspecified. Three Trust Root structural separation is the most significant candidate omission (HIGH significance — see OQ-38B05-05, elevated per Senior Architect Review 2026-06-18).

**OBS-38B05-06 (ARB-Review Output — AA-01 Consistency Assessment):** See 38B-05-ARB-Review Part I. The Graduated Threshold model is fully consistent with AA-01 throughout. No provision claims to be beyond MA's reach. Forward-permanent AA-01 urgency corollary added to OBS-38B05-02.

**OBS-38B05-07 (ARB-Review Output — Constitutional Self-Destruction Assessment):** See 38B-05-ARB-Review Part J. Mitigation correctly classified as ACTIVE CONSTITUTIONAL FRICTION. The model genuinely mitigates OBS-38A06-SD1 — it changes the political economy and conditions of Constitutional Self-Destruction, not merely its timeline. Conditions for successful Self-Destruction are structurally incompatible with normal democratic governance.

**OBS-38B05-08 (Amendment Appeal Circularity — DeepSeek Integration; renumbered from OBS-38B05-04 per R1):** When an amendment ratified by MA is challenged, the terminal appeal authority within the constitutional architecture is MA itself — the same body that ratified the amendment. MA reviewing its own ratification decision is structurally circular: the sovereign that ratifies amendments is the sovereign that ultimately reviews them. This circularity is inherent to constitutional amendment governance in any sovereign assembly model. It cannot be eliminated without removing MA from the amendment ratification chain (which would violate Tier 3 Protected Core item 6 — MA ratification requirement). Mitigation within the current model: CAB adjudicates procedural challenges independently; CIC provides constitutional compliance assessment that creates an authoritative record MA must address; the challenge window ensures the circularity is exercised under constitutional scrutiny rather than silent discretion. The circularity is not hidden — it is the structural condition of sovereign self-governance. MA cannot dismiss a CIC-documented Protected Provision finding without documented constitutional reasoning, even when reviewing its own ratification. This is the terminal constitutional limit of the challenge mechanism.

### Gap 3 Status

**SUBSTANTIALLY ADDRESSED.** All three named deliverables delivered. Seven open questions now formally named (OQ-38B05-01 through 38B05-07): OQ-38B05-05 elevated to Major Architectural Question; OQ-38B05-07 elevated to Potentially Program-Critical (Senior Architect Review 2026-06-18). Final resolution of all open questions carried to 38B-06 synthesis.

### Open Questions Carried Forward

- OQ-38B05-01: Constitutional review panel qualifications for CIC-recusal amendments (EC design)
- OQ-38B05-02: CAB appointment process — Tier 2 or Tier 3 protection? (38B-06 synthesis)
- OQ-38B05-03: AA-01 forward dependency — forward-permanent urgency now formally documented (38B-06 synthesis)
- OQ-38B05-04: OQ-38A05-02 intersection with amendments affecting AC-31/anonymity — PROTECTED
- OQ-38B05-05: **[MAJOR ARCHITECTURAL QUESTION]** One-way ratchet and three trust root structural separation — not a backlog item; dedicated treatment required in 38B-06
- OQ-38B05-06: MA self-removal procedural mechanism — EC design prerequisite (38B-06 synthesis)
- OQ-38B05-07: **[POTENTIALLY PROGRAM-CRITICAL]** ADR-EC tier relationship — must be resolved before 38C governance closure

### 38B-06 Authorization

**38B-06 (Constitutional Governance Synthesis) — PENDING.** Authorization requires: (1) ARB confirmation that R1–R5 are applied (this document reflects R1–R5 applied); (2) ARB authorization decision. 38B-06 is the final 38B deliverable. It must address: MA concentration assessment (OBS-38B04-02), AA-01 forward-permanent dependency (OBS-38B05-02 corollary), CAB appointment protection level (OQ-38B05-02), trust root structural separation (OQ-38B05-05 — Major Architectural Question), MA self-removal mechanism (OQ-38B05-06), ADR-EC tier relationship (OQ-38B05-07 — Potentially Program-Critical), and the overall constitutional governance achievement of the 38B series against the five dominant constitutional risks from 38A.

---

*Round 38B-05 — Gap 3 Constitutional Amendment Governance Specification — R1–R5 APPLIED*
*Research Program: NRNA DDD Trustworthiness*
*Submission: 2026-06-18 | ARB Review 38B-05-ARB-Review: 2026-06-18 | Senior Architect Review: 2026-06-18*
*ARB Review Revisions Applied: R1 (OBS-38B05-08 renumbering); R2 (OQ-38B05-06 MA self-removal); R3 (OQ-38B05-07 ADR-EC tier relationship, elevated to Potentially Program-Critical); R4 (OBS-38B05-02 forward-permanent AA-01 corollary); R5 (OBS-38B05-01 active constitutional friction characterization)*
*Senior Architect Elevations: OQ-38B05-05 → Major Architectural Question; OQ-38B05-07 → Potentially Program-Critical; 38C authorization not yet granted*
*Prior integrations: OBS-38B05-08 (Amendment Appeal Circularity, renumbered); OQ-38B05-05 (One-way ratchet / trust root separation); INT-38B05-01 (CA independence / trust root cross-model tension noted in E.3.2)*
