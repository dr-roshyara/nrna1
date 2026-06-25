# Round 38C-GCD-03 — Amendment Protection Discovery (S-1)

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Governance Capability Discovery (GCD-03)
**Authority:** 38C-15 ARB Ruling, Part F.1 (Governance Capability Discovery authorized — S-1..S-5 realization only)
**Status:** DISCOVERY — **Pass 1 (capabilities only)**
**Date:** 2026-06-24

---

## Discipline boundary (binding)

```
S-1 Constitutional Property
        ↓
Governance Capability        ← THIS DOCUMENT (Pass 1): named, purpose-stated, abstract
        ↓
Candidate Governance Mechanisms   ← Pass 2 (separate, later): tiering rules, ratification methods, …
        ↓
Strategic DDD                 ← gated
```

**Pass 1 = capabilities + purpose only.** No mechanisms, no architecture; Option A/B and the ruling are not reopened.
**PASS 1 RULE:** a capability may reference other capabilities; a capability may not reference a mechanism.

---

## 1. The constitutional property (S-1)

Per 38C-15 C.4 + S-1: *Provisions establishing oversight-body independence, jurisdictional scope, and interpretive finality shall be protected at a constitutional amendment threshold exceeding all ordinary constitutional amendment procedures; they shall not be amendable by ordinary majority or standard supermajority.* (The precise threshold is deferred to Pass 2 / Capability Catalog acceptance.)

S-1 is the **anti-erosion** safeguard: it protects the other safeguards from being lawfully amended away.

---

## 2. Context for the capability set (not mechanisms)

**The threat:** lawful gradual erosion ("the danger is rule-*using*, not rule-*breaking*" — abusive constitutionalism / democratic backsliding). Attack vectors: E-1 direct repeal of a protected provision; E-2 **reclassification** (relabel a protected provision as ordinary, then amend it cheaply); E-3 **salami erosion** (many individually-small amendments); E-4 **meta-amendment** (lower the entrenchment threshold itself, then amend freely); E-5 haste (momentary supermajority).

**The S-1 recursion:** entrenchment rules must themselves be protected — *who entrenches the entrenchment?* In a single-source order this cannot be structurally closed (a sufficient supermajority can always, in principle, reach the amendment rule). This is the S-1 face of Meta-CVI: **managed, not eliminated.**

**Discovery lens — Progressive Entrenchment Erosion (PEE):** entrenchment survives on paper while being hollowed out via E-2/E-3/E-4 (lens, not doctrine). PEE is why *erosion detection* is first-class below.

---

## 3. Pass 1 — S-1 Governance Capability Catalog (purpose only)

### GC-S1-01 — Amendment Classification Capability
**Purpose:** Distinguish protected (entrenched) constitutional provisions from ordinary ones, so the heightened bar attaches to exactly the right provisions.
*Realizes:* the scope of protection. *(Addresses E-2 reclassification at its root.)*

### GC-S1-02 — Tiered Amendment Threshold Capability
**Purpose:** Apply to protected provisions an amendment difficulty that exceeds all ordinary constitutional procedures.
*Realizes:* the core heightened bar of S-1. **Requires GC-S1-01** (you can only raise the bar on provisions that are classified as protected). *(Addresses E-1, E-5.)*

### GC-S1-03 — Constitutional Impact Assessment Capability
**Purpose:** Surface, *before* adoption, whether a proposed amendment touches protected independence, jurisdiction, or finality — so stealth/mislabeled amendments cannot slip the bar.
*Realizes:* pre-adoption transparency. **Requires GC-S1-01.** *(Addresses E-2, E-3.)*

### GC-S1-04 — Deliberation Integrity Capability
**Purpose:** Ensure protected amendments cannot be enacted in haste by a momentary majority (time/process protection).
*Realizes:* protection against transient capture. *(Addresses E-5.)*

### GC-S1-05 — Ratification Breadth Capability
**Purpose:** Require assent to protected amendments from beyond a single central body or cohort.
*Realizes:* distribution of amendment authority (the amendment-side analogue of GC-S2-01). *(Addresses E-1, E-5.)*

### GC-S1-06 — Entrenchment Self-Protection Capability
**Purpose:** Protect the entrenchment rules **themselves** from being lowered by ordinary procedure (close the meta-amendment loophole as far as a single-source order permits).
*Realizes:* the S-1 recursion guard. **Carries the load-bearing residual:** cannot be structurally closed under single source — managed, not eliminated. *(Addresses E-4.)*

### GC-S1-07 — Amendment Erosion Detection Capability
**Purpose:** Detect cumulative/salami erosion and reclassification attempts across time, before protection is hollowed out.
*Realizes:* defense against **PEE** as a first-class capability. **Requires GC-S1-01** (reclassification is the signal) and **GC-S4-01/02** (detection needs a route to act). *(Addresses E-2, E-3.)*

---

## 4. Pass 2 — Mechanism Exploration (DEFERRED — not performed here)

Per capability, Pass 2 will surface and compare candidate mechanisms (e.g. specific thresholds — near-unanimity / multi-cycle / regional ratification; cooling-off durations; classification registries; impact-review procedures). **None proposed here.** Pass 2 remains within GCD (no contexts, aggregates, services).

---

## 5. Realizability assessment (capability level, forward)

**Realizability: CONFIRMED.** The seven capabilities are individually necessary and jointly sufficient to realize S-1 within Option B. Forward design constraints carried downstream: **GC-S1-06** holds the S-1 recursion residual (entrenchment-of-entrenchment, structurally open under single source); **GC-S1-07** holds the PEE residual (made observable, not eliminated). Neither reopens the model.

**Cross-safeguard couplings surfaced:**
- GC-S1-02 → GC-S1-01, GC-S1-03 → GC-S1-01, GC-S1-07 → GC-S1-01 (classification is foundational to S-1).
- GC-S1-07 → GC-S4-01/02 (erosion detection needs challenge standing — same dependency pattern as GC-S2-06).
- GC-S1-05 ↔ GC-S2-01 (both are "distribute the authority" capabilities — appointment vs amendment; candidate shared pattern for the unified catalog).

---

## 6. Open questions (routed forward — NOT answered here)

- **OQ-GCD03-01:** What threshold "exceeds all ordinary procedures" without becoming unamendable in practice (the rigidity/erosion trade-off)? (Pass 2 number; capability-level: is there a defensible ceiling?)
- **OQ-GCD03-02:** How far can GC-S1-06 (entrenchment self-protection) close the meta-amendment loophole before hitting the single-source recursion limit?
- **OQ-GCD03-03 (cross-safeguard):** Is "distribute the authority" (GC-S1-05 + GC-S2-01) one shared capability or two? Resolve in the unified catalog.
- **OQ-GCD03-04 (cross-safeguard):** The recurring "detection → standing-to-act" pattern (GC-S1-07, GC-S2-06 → GC-S4-01/02) suggests a shared observability-plus-standing capability spanning safeguards.

---

## 7. Deliverable

```
S-1 Governance Capability Catalog (Pass 1 — capabilities only)
  GC-S1-01  Amendment Classification
  GC-S1-02  Tiered Amendment Threshold        (requires GC-S1-01)
  GC-S1-03  Constitutional Impact Assessment  (requires GC-S1-01)
  GC-S1-04  Deliberation Integrity
  GC-S1-05  Ratification Breadth              (↔ GC-S2-01 candidate shared pattern)
  GC-S1-06  Entrenchment Self-Protection      [load-bearing; S-1 recursion residual]
  GC-S1-07  Amendment Erosion Detection       [PEE first-class; requires GC-S1-01, GC-S4-01/02]

Status: CANDIDATE CAPABILITIES — named, not yet mechanism-explored, not designed.
Realizability (capability level): CONFIRMED. Mechanisms deferred to Pass 2.
```

---

*Round 38C-GCD-03 — Amendment Protection (S-1) — PASS 1 COMPLETE (capabilities only)*
*Discovery lens: PEE (Progressive Entrenchment Erosion). Couplings surfaced: GC-S1-07→GC-S4-01/02; GC-S1-05↔GC-S2-01.*
*Next candidate phase: GCD-04 (S-3 Jurisdiction Protection), Pass 1*
*Strategic DDD remains GATED until the full S-1..S-5 capability catalog is assembled and accepted*
