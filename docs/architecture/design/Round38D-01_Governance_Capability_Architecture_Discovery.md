# Round 38D-01 — Governance Capability Architecture Discovery

**Program:** NRNA DDD Trustworthiness Research Program · **Track:** Constitutional Governance
**Status:** SUBMITTED FOR ARB REVIEW
**Authority:** 38C-15 §7 (Governance Capability Discovery AUTHORIZED) + Senior Architect direction (2026-07-05: phase renamed *Governance Capability Architecture Discovery*; 38D phase plan established)
**Frozen Inputs:** 38C-15 ARB Ruling — Option B Functional Independence + permanent safeguards S-1..S-5 (verbatim, not reinterpreted)
**Date:** 2026-07-05

---

## Part A — Scope (Binding)

**Objective:** Discover the governance capabilities required to realize the constitutionally frozen safeguards S-1 through S-5.

**This discovery MAY:**
- ✔ identify capabilities
- ✔ identify responsibilities
- ✔ identify authority flows
- ✔ identify verification flows
- ✔ identify governance interactions
- ✔ analyze the deferred S-1 threshold candidates (38C-15 §9 explicitly routes this question here)

**This discovery MAY NOT:**
- ✘ revisit Options A/B/C (38C-15 §7)
- ✘ change the constitutional ruling
- ✘ redefine the trust anchor
- ✘ modify constitutional authority ownership
- ✘ produce bounded contexts, aggregates, domain events, services, APIs, or implementation specifications (38C-15 §8)
- ✘ resolve OQ-38A05-02 (PROTECTED — capabilities may route to it; nothing here resolves it)

**Vocabulary discipline:** A *governance capability* is a named organizational ability — "the organization can reliably do X" — with responsibilities, authority flow, and verification flow. It is NOT a bounded context, NOT a service, NOT a software component. Capability → context mapping belongs to Strategic DDD, which remains GATED until this phase's outputs are accepted.

**F-4 discipline (Independence Illusion):** For every capability, the test is not "does a provision say this happens?" but "what organizational ability must exist for this to actually happen?" A safeguard with no realizing capability is nominal — precisely the F-4 pattern this program has spent two rounds guarding against.

---

## Part B — Method

Each safeguard is decomposed by asking four questions:

1. **What must the organization be able to DO** for this safeguard to operate in practice?
2. **Who exercises the authority** in that ability (authority flow)?
3. **Who verifies the ability was exercised correctly** (verification flow — never the same actor, per Anti-Capture CD-05)?
4. **What existing constitutional actor is the candidate owner**, respecting OBS-38B01-AI1 (avoid new authority aggregates unless structurally necessary)?

Existing constitutional actors available as candidate owners (from the frozen 38B/38C record): Membership Assembly (MA), GovernanceAuthority, CriteriaAuthority, EnrollmentAuthority, AuditScopeAuthority, AuditExecutionAuthority, CertificationAuthority, ChallengeAdjudicationBody (CAB), Constitutional Interpretation Chamber (CIC), plus the S-4 distinct review mechanism (constitutionally required by the ruling; its organizational form is discovered here).

---

## Part C — Capability Inventory

### C.1 — Realizing S-1 (Amendment Protection)

**GC-01 — Protected-Provision Registry**
*The ability to maintain an authoritative, current, publicly knowable register of which constitutional provisions are S-1-protected.*
- **Responsibilities:** classify provisions on enactment; record protection tier; publish register; process tier-assignment disputes.
- **Authority flow:** tier assignment originates in the amendment process itself (38B-05 three-tier structure); registry records, does not decide.
- **Verification flow:** any constitutional actor can verify a provision's tier against the register; register integrity itself auditable by AuditExecutionAuthority.
- **Candidate owner:** GovernanceAuthority (record-keeping is its constitutional character) with CIC adjudicating classification disputes.
- **Gap exposed:** 38B-05 defined tiers but no registry duty. Without GC-01, tier-reclassification attacks (blocked in principle by 38B05-INV-01) are undetectable in practice.

**GC-02 — Amendment Tier Enforcement**
*The ability to verify that every proposed amendment is routed through the procedure matching its tier before it can take effect.*
- **Responsibilities:** intercept amendment proposals; determine affected provisions against GC-01's register; block under-tiered routing; record routing decisions.
- **Authority flow:** enforcement is procedural (gate), not interpretive; contested classifications route to CIC.
- **Verification flow:** routing decisions are challengeable by S-2/S-3 standing actors (38C03-INV-02 pattern); routing log auditable.
- **Candidate owner:** GovernanceAuthority (procedural gate) + CIC (contested classification).
- **Threat lineage:** directly counters AW-03-11 (EC ratchet) — the ratchet requires under-tiered amendments to pass unnoticed.

**GC-03 — Extraordinary Ratification**
*The ability to actually conduct an amendment process at the S-1 threshold — the threshold that "exceeds all ordinary amendment procedures."*

38C-15 §9 defers the threshold VALUE here. Three candidate mechanisms, analyzed against the threat record:

| Candidate | Mechanism | Strength | Weakness |
|-----------|-----------|----------|----------|
| **T-A Near-unanimity** | ≥90% of Assembly + extended deliberation (38B-05 pattern) | Simple; single-event; already partially specified | Effective-threshold problem: if MA-aligned cohorts dominate the Assembly, 90% of an aligned body is reachable (38C-13A FI-Failure-3); a snapshot, not a duration test |
| **T-B Multi-cycle confirmation** | Identical text ratified in ≥2 consecutive Assembly terms, separated by a general election | Defeats single-cohort capture — the S-1 change must survive a membership-wide election between votes; converts threshold from a number into TIME | Slow by design (feature for S-1 provisions, but needs an emergency-defect exception routed via CIC); two-term campaigns still possible |
| **T-C Regional ratification** | Supermajority of Assembly + majority ratification in a supermajority of regional/national chapters | Distributes the veto across geography — no central cohort can deliver it alone; engages the diaspora structure directly | Heaviest operational load (volunteer chapters must run ratification votes); uneven chapter capacity; quorum-failure risk (TM-42-class liveness concern) |

**Discovery finding (not a selection):** T-A alone reproduces the weakness S-1 exists to prevent — it is one vote by the body whose capture is the threat model. T-B and T-C both add a dimension orthogonal to Assembly composition (time, geography). The threat record (PAN operates over appointment cycles; AW-03-11 requires sequential quiet steps) suggests the S-1 threshold should include at least one non-snapshot dimension. Composite candidates (T-A+T-B, T-A+T-C) are viable and carry forward to 38D-02 relationship analysis. Selection is an ARB/EC-design decision, not made here.

- **Candidate owner:** MA conducts (ratification is MA function #15); GovernanceAuthority administers calendar/logistics; verification by published, auditable vote records per chapter/cycle.

### C.2 — Realizing S-2 (Appointment Diversity)

**GC-04 — Appointment Term Scheduling**
*The ability to maintain the staggered-term calendar across all oversight bodies such that no single Assembly cohort appoints a majority of any body.*
- **Responsibilities:** compute and publish term calendars; enforce non-renewal; project cohort-appointment shares forward; flag any appointment that would give one cohort a majority BEFORE it is made.
- **Authority flow:** appointments remain with their constitutional appointers (38B-04 unchanged); this capability constrains timing and flags violations — it does not appoint.
- **Verification flow:** calendar and cohort-share computations published; any standing actor may challenge a flagged-but-proceeded appointment.
- **Candidate owner:** GovernanceAuthority (calendar); violations adjudicated by CAB.
- **Threat lineage:** this is the operational anti-PAN mechanism — PAN Stage 1 (appointment drift) becomes visible as a computed cohort share rather than an impression.

**GC-05 — Appointment Compliance Verification**
*The ability to verify, before an appointment takes constitutional effect, that it satisfies S-2 (term length exceeding Assembly term, non-renewal, cohort limits, defined-grounds eligibility).*
- **Responsibilities:** pre-effect compliance check; certification of compliance; refusal record when non-compliant.
- **Authority flow:** verification only — cannot select or veto candidates on merits, only on S-2 compliance.
- **Verification flow:** compliance certificates published; refusals challengeable by the appointing body via CAB.
- **Candidate owner:** AuditExecutionAuthority (it verifies against pre-defined criteria — its constitutional character) reading S-2 rules as Tier-1-style evidence categories.

**GC-06 — Removal Adjudication**
*The ability to process removal of oversight-body members ONLY by supermajority on defined grounds.*
- **Responsibilities:** receive removal petitions; verify grounds against the defined-grounds catalog; conduct supermajority process; record outcome with reasons.
- **Authority flow:** grounds verification precedes any vote — a removal vote without verified grounds is constitutionally void, preventing removal-by-displeasure.
- **Verification flow:** removal proceedings fully recorded; removed members hold S-1-class standing to challenge via the S-4 pathway.
- **Candidate owner:** CAB adjudicates grounds; MA (or designated body) holds the supermajority vote.
- **Gap exposed:** the defined-grounds catalog does not exist. EC-design input required (carried to 38D-04 responsibility matrix).

### C.3 — Realizing S-3 (Jurisdictional Protection)

**GC-07 — Jurisdiction Catalog Maintenance**
*The ability to maintain the explicit, current catalog of every oversight body's mandate — including election oversight and trust-function evaluation as non-narrowable entries.*
- **Responsibilities:** catalog authorship at body creation; amendment tracking (routed through GC-02 at correct tier); publication.
- **Authority flow:** catalog entries change only via the amendment process; the maintainer records, never edits substance.
- **Candidate owner:** GovernanceAuthority (registry character), CIC for interpretation disputes over entry meaning.

**GC-08 — Jurisdiction Narrowing Detection**
*The ability to detect narrowing of an oversight mandate — whether by amendment (blocked at ordinary tiers by S-3) or by interpretation (the PAN Stage 2 route the amendment gate cannot see).*
- **Responsibilities:** review interpretive rulings that touch catalog entries; compare effective jurisdiction (what the body actually reviews) against cataloged jurisdiction; publish divergence findings.
- **Authority flow:** detection and publication only — no power to reverse an interpretation. Reversal routes: interpretation disputes → CIC; CIC's OWN interpretation of its independence mandate → the S-4 distinct review mechanism (this is the one class where CIC cannot be the route — the Meta-CVI closure point identified at 38C-13B, and the reason S-4 exists).
- **Verification flow:** divergence findings are public constitutional records; standing actors may act on them.
- **Candidate owner:** AuditScopeAuthority (defining what evidence of jurisdictional exercise must exist) + AuditExecutionAuthority (comparing actual against cataloged) — the two-tier audit pattern from ADR-4 applied to governance itself.
- **Discovery note:** GC-08 is the single most load-bearing capability in this inventory. It is the operational answer to "how would anyone ever notice" — the detection question that made Meta-CVI dangerous. Without GC-08, S-3 is text; with it, S-3 is a tripwire.

### C.4 — Realizing S-4 (Challenge Pathways + Recusal)

**GC-09 — Independence Challenge Reception**
*The ability to receive challenges to an oversight body's independence from outside constitutional actors, under published standing rules.*
- **Responsibilities:** standing verification (S-1/S-2/S-3 classes per ADR-5); docketing; routing per GC-10; frivolous-challenge limits.
- **Authority flow:** reception is ministerial; it cannot refuse a standing-compliant challenge.
- **Candidate owner:** ChallengeReceptionFunction (already a constitutional capability of CAB per OQ-37-05-01 resolution) — extended, not new.

**GC-10 — Distinct Review Panel Constitution**
*The ability to form, on demand, the S-4 review mechanism: adjudicators who are not participants in the challenged proceeding.*
- **Responsibilities:** maintain a standing roster of qualified reviewers; verify non-participation for each challenge; empanel; dissolve after ruling.
- **Authority flow:** empanelment rules are S-1-protected text (this is where the ruling's "functional separation of role within the single source" becomes real); the panel's rulings on independence challenges are final per S-5.
- **Verification flow:** empanelment records public; non-participation attestations challengeable once per panel.
- **Candidate owner:** roster maintained by GovernanceAuthority; empanelment verified by AuditExecutionAuthority; NO standing body — the panel exists per-challenge (minimizes capture value; a body that exists only when convened cannot be pre-captured by appointment).
- **Discovery note:** the per-challenge (ad hoc) form is a finding, not a decision — a standing panel alternative carries to 38D-02. The trade: standing panel = experience + PAN vulnerability; ad hoc panel = no capture target + variable quality.

**GC-11 — Recusal Enforcement**
*The ability to identify and enforce recusal of any adjudicator who is a participant in the proceeding under challenge.*
- **Responsibilities:** participation mapping per proceeding; recusal determination; substitute seating via GC-10 roster.
- **Candidate owner:** operates inside GC-10's empanelment step; disputes over "participant" status → CIC, EXCEPT where CIC members are the participants — then the GC-10 panel itself determines (the recursion terminates in the panel, mirroring ADR-5's Terminal Authority Principle).

### C.5 — Realizing S-5 (Finality with Defined Exceptions)

**GC-12 — Ruling Finality Registry**
*The ability to record which rulings are constitutionally final, their jurisdiction basis, and their challenge-window status.*
- **Responsibilities:** record rulings with jurisdiction citation; track challenge windows (ADR6-CONSTRAINT-01 pattern); mark finality transitions (TS-1 pattern generalized to governance rulings).
- **Candidate owner:** GovernanceAuthority (registry character); integrity auditable.

**GC-13 — Exception Routing**
*The ability to route fundamental-defect claims against final rulings to the Constitutional Interpretation Chamber — and nowhere else.*
- **Responsibilities:** receive post-finality defect claims; verify claim class (fundamental defect vs. relitigation); route qualifying claims to CIC; refuse and record non-qualifying claims.
- **Authority flow:** routing only. This capability does NOT resolve OQ-38A05-02 — it is the pipe through which a future case reaches CIC, preserving the PROTECTED status exactly as 38C-15 §5 requires.
- **Candidate owner:** ChallengeReceptionFunction (same ministerial character as GC-09).

### C.6 — Cross-Cutting Capabilities

**GC-14 — Safeguard Effectiveness Monitoring**
*The ability to observe, over time, whether S-1..S-5 are operating as designed — the evidence base for the Option A reservation.*
- **Responsibilities:** periodic published assessment of each safeguard's operation (appointments flagged/blocked, amendments re-routed, jurisdiction divergences found, challenges received/adjudicated, finality exceptions routed); trend reporting across Assembly terms.
- **Authority flow:** observation and publication only. GC-14 has NO trigger authority — 38C-15 §7 is explicit that constitutional maturity findings create "no doctrine, trigger, certification, or transition authority." If safeguards prove inadequate, GC-14's record is the evidence; the decision to invoke the Option A reservation belongs to the constitutional amendment process, at the S-1 threshold.
- **Candidate owner:** AuditScopeAuthority defines the evidence categories; AuditExecutionAuthority produces the assessments.

**GC-15 — Constitutional Record Publication**
*The ability to make every record the other 14 capabilities produce (registers, calendars, certificates, findings, dockets, assessments) accessible to the membership.*
- **Rationale:** the diaspora constraint (no press, no courts, dispersed members) means publication IS the external check. Every capability above specifies a verification flow; GC-15 is the shared substrate that makes those flows real rather than nominal — the OA-01 lesson (evidence theoretically available, practically inaccessible) applied to governance.
- **Candidate owner:** GovernanceAuthority as publisher; AuditExecutionAuthority verifies completeness of publication against GC-01/GC-07/GC-12 registers.

---

## Part D — Capability → Safeguard Coverage Matrix

| Capability | S-1 | S-2 | S-3 | S-4 | S-5 | Threat lineage |
|-----------|:---:|:---:|:---:|:---:|:---:|----------------|
| GC-01 Protected-Provision Registry | ● | | ○ | | ○ | 38B05-INV-01 reclassification |
| GC-02 Amendment Tier Enforcement | ● | | ○ | | | AW-03-11 EC ratchet |
| GC-03 Extraordinary Ratification | ● | | | | ○ | FI-Failure-3 effective threshold |
| GC-04 Appointment Term Scheduling | | ● | | | | PAN Stage 1 |
| GC-05 Appointment Compliance Verification | | ● | | | | PAN Stage 1; F-4 |
| GC-06 Removal Adjudication | | ● | | ○ | | removal-by-displeasure |
| GC-07 Jurisdiction Catalog | | | ● | | | FI-Failure-2 constriction |
| GC-08 Jurisdiction Narrowing Detection | | | ● | ○ | | PAN Stage 2; Meta-CVI detection |
| GC-09 Independence Challenge Reception | | | | ● | | 38C03-INV-02 standing |
| GC-10 Distinct Review Panel Constitution | | | | ● | | Meta-CVI closure point |
| GC-11 Recusal Enforcement | | | | ● | | acute self-dealing (38B-04) |
| GC-12 Ruling Finality Registry | | | | | ● | TS-1 generalization |
| GC-13 Exception Routing | | | | ○ | ● | OQ-38A05-02 (routes, PROTECTED) |
| GC-14 Safeguard Effectiveness Monitoring | ○ | ○ | ○ | ○ | ○ | Option A reservation evidence |
| GC-15 Constitutional Record Publication | ○ | ○ | ○ | ○ | ○ | OA-01 accessibility |

● = primary realization · ○ = supporting

**Coverage check:** every safeguard has at least two primary capabilities except S-5 (two: GC-12/GC-13) and S-2 (three). No capability exists without a safeguard anchor — nothing here is speculative architecture.

---

## Part E — Findings

**FD-01:** S-1..S-5 decompose into 15 capabilities, 13 safeguard-specific and 2 cross-cutting. All 15 have candidate owners among EXISTING constitutional actors — OBS-38B01-AI1 is satisfiable; no new authority aggregate is structurally required. The only new organizational form is the GC-10 review panel, which in its ad hoc candidate form is not a standing body at all.

**FD-02:** GC-08 (Jurisdiction Narrowing Detection) is the load-bearing capability. It operationalizes detection of the PAN Stage 2 / Meta-CVI progression that the entire 38C series identified as constitutionally invisible. Its two-tier audit structure reuses ADR-4's pattern rather than inventing one.

**FD-03 (S-1 threshold input, per 38C-15 §9):** A pure snapshot threshold (T-A near-unanimity alone) reproduces the capture weakness S-1 exists to prevent. The threat record favors composites that add a non-snapshot dimension: time (T-B multi-cycle) or geography (T-C regional). Selection deferred to ARB/EC-design with this finding as input.

**FD-04:** The GC-10 panel's organizational form (ad hoc vs. standing) is the highest-consequence open design choice in this inventory — it determines whether the S-4 mechanism itself is PAN-capturable. Both forms carry to 38D-02.

**FD-05:** GC-14 must be authority-free (observation and publication only). Any trigger authority attached to safeguard monitoring would recreate a constitutional actor with self-expanding potential — the Anti-Capture principle applied to the monitoring layer itself.

**FD-06:** Verification flows in this inventory never terminate in the actor being verified. The recursion terminates in three places only: the GC-10 panel (for CIC-participant cases), CIC (for everything else interpretive), and the amendment process at the S-1 threshold (for the safeguards themselves). This tri-terminal structure is the capability-level expression of the ruling's "functional separation of role within the single source."

---

## Part F — Open Questions (carried to 38D-02+)

- **OQ-38D01-01:** GC-10 panel form — ad hoc vs. standing (FD-04). → 38D-02 relationship analysis.
- **OQ-38D01-02:** S-1 threshold composite selection (T-A+T-B vs. T-A+T-C vs. other). → ARB/EC-design with FD-03.
- **OQ-38D01-03:** Defined-grounds catalog for GC-06 removal. → EC-design; 38D-04 records the responsibility.
- **OQ-38D01-04:** Capability load feasibility — can the volunteer organization staff 15 capabilities? Requires the 38D-04 responsibility matrix to expose per-body load before an answer is possible (TM-42 lesson: governance structure without capacity is an availability FAIL).
- **OQ-38D01-05:** GC-15 publication medium and member-accessibility standard (OA-01 class). → 38D-03 interaction model.

---

## Part G — Governance Verification

| Constraint | Status |
|-----------|--------|
| Options A/B/C revisited | NO |
| Ruling reinterpreted | NO — S-1..S-5 taken verbatim |
| Trust anchor redefined | NO |
| Constitutional authority ownership modified | NO — candidate owners only, all existing actors, decision deferred |
| Bounded contexts / aggregates / events / services / APIs produced | NO — capabilities only (38C-15 §8 respected) |
| OQ-38A05-02 | PROTECTED — GC-13 routes, does not resolve |
| New authority aggregates proposed | NO (GC-10 ad hoc panel = per-challenge form, not a standing body; alternative carried as open question) |
| OBS-38B06-05 | PERMANENT — inventory completeness ≠ inventory correctness |

---

## Part H — 38D Phase Plan (per Senior Architect direction)

```
38D-01  Governance Capability Architecture Discovery   ← THIS DOCUMENT
38D-02  Capability Relationships (dependencies, composites, panel-form analysis)
38D-03  Governance Interaction Model (flows between capabilities; GC-15 substrate)
38D-04  Governance Responsibility Matrix (per-body load; OQ-38D01-04 feasibility)
38D-05  Governance Service Discovery (organizational services — still NOT software)
        ↓
Strategic DDD (gate lifts on 38D acceptance): capability → bounded context mapping
        ↓
Context Map → Aggregates
```

*Round 38D-01 — Governance Capability Architecture Discovery — SUBMITTED FOR ARB REVIEW*
*15 capabilities · 6 findings · 5 open questions · 38C-15 ruling treated as frozen input throughout*
*OQ-38B05-05: RESOLVED (38C-15 — not revisited) · OQ-38A05-02: PROTECTED*
*Date: 2026-07-05*
