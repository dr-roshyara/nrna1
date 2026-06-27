# Round 40-01 — F-THR Discovery Report (Threat / Capture-Resistance Family)

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Round 40 — F-THR · **Executed under Methodology Baseline MB-39.1**
**Status:** EXECUTION COMPLETE — Execution Report (no methodology change; MB-39.1 unmodified; Register v3 still LOCKED)
**Date:** 2026-06-25

> **Stance.** Hostile replication. The goal below is to *break* M-07, P-PROFILE, P-CAP, and the D1–D5 model — not to confirm them. Findings that survive a genuine refutation attempt are reported as surviving; findings that narrow or fail are reported as such. **Learning, not confirmation.**
> **Reconciliation note (binding):** per the Exit Gate, this report does **not** write to the live Prediction Register (`P2-18`, constituent #2 of MB-39.1). §5 is a *proposed* delta; the unlock+write occurs at the Methodology Governance Review and becomes MB-39.2.

---

## Deliverable 1 — Discovery Report

### 1.1 Family definition (what "capture resistance" is here)

**Capture** = a faction, incumbent, external actor, or coordinated minority acquires disproportionate, illegitimate control over the governance architecture (the election system **and** its oversight/review bodies). **Capture resistance** = the family of properties/mechanisms that prevent, raise the cost of, or expose such acquisition.

**Observation (O-THR-01).** Capture resistance is **only definable relative to an adversary** (faction size, resources, time horizon, insider vs outsider). Unlike F-PROC (process integrity, definable against a fixed procedure) and F-AUTH (appointment quality, definable against a role), capture resistance has **no adversary-independent definition.**
*Evidence:* every candidate mechanism below changes value depending on the assumed attacker (e.g. a supermajority threshold is strong vs a 40 % faction, useless vs a 70 % faction). *Interpretation:* the evaluation framework as inherited (mechanism → coordinate on D1–D5) has **no slot for an attacker model** — see RQ-7 / D6-finding. *Open question:* is "adversary model" a methodology input or a governance property?

### 1.2 Sketch BEFORE literature (R-02 / ADR-M-011)

Independent sketch of capture-resistance mechanisms, produced before any literature harvest:

1. Power dispersion / separation of functions (oversight ≠ adjudication ≠ execution).
2. Elevated approval thresholds (supermajority / entrenchment).
3. Staggered terms (no single moment renews the whole body).
4. Rotation / term limits (no individual entrenchment).
5. Distributed appointment sources (no single appointer).
6. Transparency / public auditability (capture is *detectable*).
7. Standing to challenge + external appeal (capture is *contestable*).
8. Conflict-of-interest / anti-collusion exclusions.
9. Quorum-diversity requirements.
10. Immutable audit trail / verifiability (platform-level; anonymity-preserving).

*Literature pass (informs only):* separation of powers (Montesquieu lineage), entrenchment thresholds, staggered-board anti-takeover analogues, transparency-as-deterrence, contestability literature. **Convergence:** the sketch covered the prevention/threshold/temporal/source clusters; literature **added emphasis** on *detection* and *contestability* as first-class (not merely supporting) — a mild divergence (see R-02 / ADR-M-011 review trigger; not a sharp divergence). *No constitutional reopening.*

---

## Deliverable 2 — Mechanism Catalogue (classified on D1–D5)

D1 Source · D2 Temporal · D3 Qualification · D4 Approval · D5 Protection (MB-39.1 dimension model, ADR-M-002).

| # | Mechanism | D1 | D2 | D3 | D4 | D5 | Clean fit? |
|---|-----------|----|----|----|----|----|-----------|
| M-T1 | Distributed appointment sources | ● | | | | | yes |
| M-T2 | Staggered terms | | ● | | | | yes |
| M-T3 | Rotation / term limits | | ● | | | | yes |
| M-T4 | Conflict-of-interest exclusion | | | ● | | | yes |
| M-T5 | Quorum-diversity requirement | | | ● | | ● | partial |
| M-T6 | Supermajority / entrenchment threshold | | | | ● | ● | partial |
| M-T7 | Insulation from removal | | | | | ● | yes |
| M-T8 | Standing to challenge + external appeal | | | | | ● | partial |
| **M-T9** | **Power dispersion / separation of functions** | ? | | | | | **NO — residue** |
| **M-T10** | **Transparency / public auditability (detection)** | | | | | ? | **NO — residue** |
| M-T11 | Immutable audit trail / verifiability | | | | | ● | partial (platform) |

**Residue (does not fit D1–D5):**
- **M-T9 Power dispersion** is not a coordinate of a *single* mechanism — it is a **topological** property *between bodies* ("oversight and adjudication are structurally independent"). The per-mechanism-vector model cannot express a relation between two bodies. *(Evidence for a structural/topology gap — RQ-5.)*
- **M-T10 Transparency/detection** is not prevention; it is **post-hoc detectability**. None of D1–D5 (all prevention/selection-oriented) captures "makes capture visible after it occurs." *(Evidence for a Detection/Accountability dimension — RQ-5, revives P-D6.)*

**Observation (O-THR-02).** Two of the strongest capture-resistance mechanisms (M-T9, M-T10) **do not fit D1–D5.** *Evidence:* classification attempt above. *Interpretation:* D1–D5 were derived from F-AUTH (an appointment family) and are **selection/prevention-biased**; capture resistance adds **topology** and **detection** axes. *Prediction-impact:* narrows R-01 (dimensions are per-family, not universal — already its scope) and revives **P-D6**. *Open question:* is detection one dimension or two (transparency vs accountability)?

---

## Deliverable 3 — Interaction Graph

Typed per the provisional taxonomy (Structural / Behavioral / Constraint / Temporal / Recursive / Unknown). Unknown is **retained, not forced.**

```
            M-T6 supermajority
                │  (Structural: multiplicative)
                ▼
M-T9 dispersion ───Structural──► capture cost = Π(threshold_i) across independent bodies
   │                                  ▲
   │ Constraint                        │ Structural
   ▼                                   │
M-T7 insulation ◄──Constraint── M-T8 standing-to-challenge
                                        ▲
                                        │ Recursive (feedback loop)
            M-T10 transparency ──Behavioral──► detection ──► M-T8 challenge ──► correction ──┐
                ▲                                                                            │
                └──────────────────────── Recursive ────────────────────────────────────────┘

   M-T2 staggered ──Temporal──► M-T3 rotation        (Temporal cluster; near-additive)
   M-T4 COI ──Constraint──► M-T5 quorum-diversity     (Constraint cluster)

   ?? COMPENSATORY (Unknown): if M-T6 is breached (threshold reached), M-T10+M-T8 still
      expose & contest the capture — partial-failure absorption. Not Structural (not
      multiplicative), not Constraint (not a precondition). RETAINED as Unknown.
```

**Observation (O-THR-03).** Capture resistance contains **at least three distinct interaction regimes simultaneously**: (a) a **Structural** multiplicative core (dispersion × thresholds), (b) a **Recursive** detection→challenge→correction loop (transparency + standing), (c) **near-additive** temporal/constraint cost-raisers (staggering, rotation, COI). *Evidence:* graph above. *Interpretation:* interaction is **not a single family-level property** — different *clusters* exhibit different regimes. *Prediction-impact:* directly stresses **M-07** (which treats the *family* as the unit of an interaction profile). *Open question:* is the cluster, not the family, the correct unit of interaction analysis?

**Observation (O-THR-04, candidate new interaction type).** The partial-failure-absorption relation (breach M-T6 → M-T10/M-T8 still bite) is **Compensatory/Redundancy** — neither multiplicative (Structural) nor a precondition (Constraint). *Evidence:* the loop continues to function when the structural core is defeated. *Interpretation:* defense-in-depth is a genuine interaction type the current taxonomy lacks. *Prediction-impact:* RQ-6 — retain as **Unknown→candidate "Compensatory."** *Competing explanation:* it could be a special case of Recursive (the loop simply runs regardless) — **prefer the simpler explanation** unless a case shows compensation *without* a feedback loop. *Status:* retained as Unknown, not adopted.

---

## Deliverable 4 — Composite Evaluation (A / B / C / D)

**Never assume a composite exists.** Test all four outcomes for capture resistance.

- **A (required)?** No — the temporal/constraint cost-raisers (M-T2/3/4) work **additively**; each independently raises capture cost. A single family-wide composite is not *required*.
- **B (useful)?** **Yes, at the cluster level** — the Structural core (M-T9 × M-T6) and the Recursive loop (M-T10 + M-T8) each produce a property no member has alone (multiplicative cost; self-correction). Composite reasoning is genuinely useful *for those clusters*.
- **C (optional)?** Partly — for the additive cluster, composite framing adds nothing.
- **D (misleading)?** **Yes, at the family level.** Modeling capture resistance as **one** composite is **misleading**: it hides that the family is a *heterogeneous* assembly of a structural sub-composite, a recursive loop, and additive cost-raisers. A single "composite quality" score would average away the structure that actually matters.

**Finding (F-THR-COMPOSITE).** The honest answer is **D-at-family-level / B-at-cluster-level**. *Evidence:* §3 graph + the A/B/C/D test above. *Interpretation:* the inherited "composite = unit of design" framing (M-01, already narrowed by M-07) is **too coarse for heterogeneous families** — the unit should be the **interaction cluster**, not the family. *This is the single most important hostile result of F-THR.*

**Adversarial review of F-THR-COMPOSITE.**
- *Confirmation:* multiple clusters with distinct regimes are visible in the catalogue and graph; forcing one composite demonstrably loses information.
- *Refutation (what would invalidate it):* if every "cluster" actually reduced to one global emergent property under a single attacker model, the family-composite would be correct. Tested: under a *fixed* attacker the clusters still behave differently (prevention vs detection are not fungible) → refutation fails.
- *Alternative explanation:* perhaps the "family" was drawn too broadly (analyst artifact), and capture resistance is really *several* families. **This is the simpler explanation and is preferred** → it becomes a Draft ADR (cluster-as-unit), not an immediate M-07 rewrite.

---

## Deliverable 5 — Prediction Register Update (PROPOSED delta — NOT written to P2-18)

| Prediction | Proposed status | Basis | Confidence |
|------------|-----------------|-------|------------|
| **RQ-1 / P-PROFILE** | **Mixed** → therefore **NARROW to a continuum** | §3: three regimes coexist; not a single discrete profile | Medium (1 strong family) |
| **M-07** | **NARROWED (again)** — interaction profile is a property of the **cluster**, not the family | F-THR-COMPOSITE | Medium |
| **P-CAP** | **SUPPORTED (not refuted)** — no single mechanism delivers capture resistance | §RQ-4 below | Medium-High |
| **P-D6 (Accountability)** | **REVIVED — now has supporting evidence** (M-T10 detection / accountability) | O-THR-02 | Low-Medium |
| **R-01 (orthogonal dims)** | **NARROWED** — D1–D5 insufficient for this family (topology + detection residue) | O-THR-02 | Medium |
| **R-02 (sketch-first)** | **SUPPORTED** with a flag — literature *added* detection/contestability emphasis (mild divergence) | §1.2 | Medium |
| **P-PATTERN** | still **no supporting evidence** — clusters here did not recur from F-AUTH/F-PROC | — | — |

**New observations proposed for the register:** O-THR-01 (adversary-relative definability), O-THR-02 (dimension residue), O-THR-03 (cluster-level interaction), O-THR-04 (candidate Compensatory interaction type).

*All of the above are evaluated, not enacted. The register stays LOCKED until the governance review.*

### RQ answers (explicit)

- **RQ-1:** **Mixed.**
- **RQ-2:** M-07 **survives but is narrowed** — valid that profiles differ, but the **unit is the cluster**, not the family. Not replaced.
- **RQ-3:** **P-PROFILE does not survive intact** — the Mixed result pushes the model toward a **continuum / per-cluster** view, exactly its pre-registered falsifier disposition.
- **RQ-4:** **P-CAP survives (supported).** See adversarial test below.
- **RQ-5:** **D1–D5 are NOT sufficient** for this family — evidence for **topology** and **detection/accountability** axes (residue M-T9, M-T10).
- **RQ-6:** **Yes** — a candidate **Compensatory/Redundancy** interaction type (retained as Unknown).
- **RQ-7:** **Yes** — the framework lacks an **attacker model** and a way to express **inter-body topology**; both are framework weaknesses (as valuable as governance findings).

**Adversarial review of P-CAP (RQ-4).**
- *Confirmation:* every single-mechanism maximum creates a *new* capture vector — unanimity → minority/deadlock capture; total insulation → unaccountable capture; one appointer → source capture. So no lone mechanism is sufficient.
- *Refutation:* a single mechanism delivering full capture resistance would refute P-CAP. The nearest candidate — a structurally **external** guarantor — was already **excluded by transferability** (F-AUTH; voluntary diaspora association has no such body). Within the transferable design space, none dominates → refutation fails.
- *Alternative explanation:* "capture resistance is just raising cost above the attacker's budget," achievable by one strong threshold. **Rejected:** raising one threshold relocates capture to the unguarded vector (deadlock/insider) rather than removing it. Interaction is required to close *multiple* vectors at once → P-CAP supported.

---

## Deliverable 6 — Threats-to-Validity Update

| Threat | Status after F-THR |
|--------|--------------------|
| Single-classifier (no inter-rater) | **Unchanged — still open.** All classifications here are one analyst; the cluster/topology findings especially need a second rater. |
| Confirmation bias | **Mitigated** — hostile stance + per-finding refutation; the headline result (composite-misleading) is *against* the inherited model. |
| Family-boundary artifact | **NEW threat surfaced** — "capture resistance" may be several families (see F-THR-COMPOSITE alternative). Flagged. |
| Adversary-model omission | **NEW threat** — conclusions are implicitly relative to an unstated attacker (O-THR-01). |
| Transferability over-reach | **Controlled** — external-guarantor again excluded; conclusions scoped to a founding-stage voluntary association. |
| Generalization from n | Population of inference = **3 families** (F-AUTH emergent, F-PROC additive, F-THR mixed). Still below saturation. |

---

## Deliverable 7 — Draft ADR Backlog (PROPOSALS ONLY — none enacted)

- **DA-THR-01 (narrows M-07 / ADR-M-009):** the unit of interaction analysis is the **cluster**, not the family. *Class: Methodology Rule. Evidence: F-THR-COMPOSITE. Review: needs ≥1 more heterogeneous family before adoption.*
- **DA-THR-02 (new dimension candidate(s)):** add **D6 Detection/Transparency** and possibly **D7 Distribution/Topology**; reconcile with predicted **P-D6 Accountability**. *Class: Evidence/Methodology. Evidence: O-THR-02 residue.*
- **DA-THR-03 (framework — attacker model):** capture/threat families require an **explicit adversary model** as an evaluation input. *Class: Validation/Methodology. Evidence: O-THR-01.*
- **DA-THR-04 (interaction taxonomy):** admit **Compensatory/Redundancy** as an interaction type *iff* a case shows compensation without a feedback loop (else fold into Recursive). *Class: Methodology. Evidence: O-THR-04.*
- **DA-THR-05 (P-PROFILE → continuum):** replace the discrete {Emergent/Additive/Mixed} profile with a **continuum / per-cluster** model. *Class: Methodology. Evidence: RQ-1/RQ-3.*
- **DA-THR-06 (family-boundary):** add a methodology check for **family over-breadth** (is this one family or several?). *Class: Process. Evidence: F-THR-COMPOSITE alternative.*

*Per the Methodology Constitution (MC-05) and the Exit Gate, these are **proposals**. None is applied; the methodology runs unchanged under MB-39.1.*

---

## Deliverable 8 — Methodology Impact Assessment

**The methodology survived a genuine hostile test and behaved correctly.**
- It **detected its own limits** (dimension residue, missing attacker model, composite-too-coarse) and routed them to Draft ADRs **without changing mid-execution** — exactly the L0/L2/L5 separation the RGA describes.
- No prediction was edited to fit evidence (Register stayed locked; MC-04 held).
- The strongest result is **against** the inherited model (composite-misleading at family level) — evidence the process is not merely confirmatory.
- **M-07 narrowed, not refuted; P-CAP supported; P-PROFILE pushed to a continuum.** This is the "narrowing more often than destroying" pattern the methodology predicts of real science.
- **No Critical methodology failure.** The maturity claim (L2+) is unchanged; F-THR adds the third family without triggering a protocol change *during* execution.

---

## Deliverable 9 — DDD Readiness Delta

Every concept discovered is classified; **none becomes Ubiquitous Language from F-THR alone.**

| Concept | Classification | Reason |
|---------|----------------|--------|
| Capture / capture resistance | **Candidate** | central, but adversary-relative definition unsettled (O-THR-01) |
| Interaction **cluster** (vs family) | **Experimental** | new, n=1; DA-THR-01 pending |
| Detection / Transparency dimension | **Experimental** | residue; DA-THR-02 pending |
| Distribution / Topology dimension | **Experimental** | residue; not yet a clean axis |
| Adversary / attacker model | **Candidate** | clearly needed, but its domain (methodology vs governance) is open |
| Compensatory interaction | **Blocked** | retained as Unknown; may collapse into Recursive |
| Power dispersion, supermajority, staggered terms (mechanisms) | **Candidate** | stable as governance vocabulary, but mechanism set not yet replicated |

**Stable concepts entering DDD Readiness from F-THR: none.** The DDD gate stays genuinely closed.

---

## Deliverable 10 — Executive Scientific Summary

F-THR set out to **falsify** the governance-architecture model and instead **sharpened** it. The Threat/Capture-Resistance family is **Mixed (RQ-1)** — and that Mixed result did real damage to the inherited framing: modeling the family as a **single composite is misleading (Outcome D at family level)**; the correct unit of interaction analysis is the **cluster** (a structural multiplicative core, a recursive detection→challenge→correction loop, and additive cost-raisers, all at once). **M-07 survives but is narrowed again (RQ-2); P-PROFILE is pushed from discrete profiles toward a continuum (RQ-3); P-CAP survives a serious refutation attempt (RQ-4)** — no single transferable mechanism delivers capture resistance because every single-mechanism maximum merely relocates the capture vector. The family also exposed two **framework weaknesses** as valuable as any governance finding: **D1–D5 are insufficient** here (topology and detection residue, reviving P-D6) **(RQ-5)**, and the framework has **no attacker model**, though capture resistance is only definable against one **(RQ-7)**; plus a **candidate new interaction type (Compensatory)** retained rather than forced **(RQ-6)**.

Crucially, **nothing was changed.** Six Draft ADRs were recorded; the Specification, Constitution, Validator, and Register stand exactly as locked in MB-39.1. The methodology demonstrated maturity by **finding its own limits and deferring them to governance** rather than adapting itself mid-experiment.

**Population of inference:** 3 families (emergent / additive / mixed). **Confidence:** medium; **transferability:** scoped to a founding-stage voluntary diaspora association; **replication status:** single-classifier, below saturation. **Competing explanation retained:** capture resistance may be *several* families rather than one heterogeneous family (DA-THR-06).

---

## Exit Gate compliance

✓ MB-39.1 **unmodified** · ✓ Register **still LOCKED** (proposed delta only, §5) · ✓ Constitution/Spec/Validator untouched · ✓ all methodology pressure captured as **Draft ADRs** (DA-THR-01..06) · ✓ no DDD concept promoted to Stable · ✓ Strategic DDD **CLOSED** · ✓ Execution Report only — methodology evolution deferred to the **Methodology Governance Review** (→ possibly MB-39.2).

---

*Round 40-01 — F-THR Discovery Report — EXECUTION COMPLETE under MB-39.1.*
*RQ-1 Mixed · M-07 narrowed (cluster-as-unit) · P-PROFILE → continuum · P-CAP supported · D1–D5 insufficient (topology+detection) · candidate Compensatory interaction · framework needs an attacker model. 6 Draft ADRs, none enacted. Next: Methodology Governance Review.*
