# Round 38C-GCD-02 — Challenge Pathways Discovery (S-4)

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Governance Capability Discovery (GCD-02)
**Authority:** 38C-15 ARB Ruling, Part F.1 (Governance Capability Discovery authorized — S-1..S-5 realization only)
**Status:** DISCOVERY — **Pass 1 (capabilities only)**
**Date:** 2026-06-24

---

## Discipline boundary (binding)

The discovery pipeline has a layer that must not be skipped:

```
S-4 Constitutional Property
        ↓
Governance Capability        ← THIS DOCUMENT (Pass 1): named, purpose-stated, abstract
        ↓
Candidate Governance Mechanisms   ← Pass 2 (separate, later): sortition, panels, recusal rules, …
        ↓
Strategic DDD                 ← gated
```

**Pass 1 names capabilities and states their purpose. It contains NO mechanisms, NO solutions, NO architecture, and does not reopen Option A/B or the ruling.** Any sentence here that names *how* (a specific rule, selection method, threshold, or body) would be a layer violation and is deferred to Pass 2.

---

## 1. The constitutional property (S-4)

Per 38C-15 C.4: *When the independence of an oversight body is itself challenged, adjudication shall route through a distinct constitutional review mechanism whose members are not participants in the challenged proceeding — functional role separation, not a separate sovereign.* In short: **a body cannot be the final judge of a challenge to its own independence.**

---

## 2. Context for the capability set (not mechanisms)

- **The S-4 residual:** under Option B there is no *structurally* external vantage — every reviewer ultimately traces to MA. S-4 requires *functional* independence credible despite shared origin. (Recorded in 38C-15 as "managed, not eliminated.")
- **Discovery lens — Progressive Challenge Suppression (PCS):** the pathway can remain *on paper* while becoming *unusable* (standing narrows, admissibility hardens, review is delayed). Lens only, not a doctrine. PCS is why an *observability* capability is first-class below.

These shape *which capabilities are needed*; they do not prescribe mechanisms.

---

## 3. Pass 1 — S-4 Governance Capability Catalog (purpose only)

### GC-S4-01 — Challenge Invocation Capability
**Purpose:** Enable constitutional actors to formally allege independence degradation of an oversight body.
*Realizes:* the existence of the challenge itself. *(Who, and on what trigger — Pass 2.)*

### GC-S4-02 — Standing Protection Capability
**Purpose:** Ensure that legitimate challenges remain admissible over time and cannot be quietly filtered out of existence.
*Realizes:* durability of access. **PCS lives here.** *(Admissibility rules, anti-frivolous bounds — Pass 2.)*

### GC-S4-03 — Conflict Isolation Capability
**Purpose:** Prevent the challenged actors from influencing the adjudication of the challenge against them.
*Realizes:* the "cannot judge itself" core of S-4. *(Recusal scope, partial vs collective — Pass 2.)*

### GC-S4-04 — Independent Review Assembly Capability
**Purpose:** Bring into being a review body sufficiently independent-in-function to evaluate the challenge.
*Realizes:* the "distinct review mechanism" of S-4. **Carries the structural-external-vantage residual** (functional independence only). *(Sortition, panels, elders, delegate pools — Pass 2, where they compete.)*

### GC-S4-05 — Review Decision Capability
**Purpose:** Produce constitutionally recognized findings on the challenge.
*Realizes:* an authoritative outcome. *Intersects OQ-38A05-02 (PROTECTED) at the escalation boundary.* *(Binding vs advisory, escalation path — Pass 2.)*

### GC-S4-06 — Corrective Action Capability
**Purpose:** Transform a finding of degradation into enforceable organizational consequences.
*Realizes:* effect (a finding that changes nothing is no safeguard). *(Remedy tiers, suspension, cooperation-independence — Pass 2.)*

### GC-S4-07 — Challenge Observability Capability
**Purpose:** Detect degradation of the challenge pathway **itself** over time.
*Realizes:* defense against PCS as a first-class capability. PAN taught the lesson: a system may preserve *appointments* while destroying the *ability to complain about appointments*. Candidate governance signals (not implementation): challenge volume, success rate, admissibility-rejection rate, review-delay trend, standing-denial trend. *(How measured / who acts — Pass 2 + couples to GC-S4-01/02.)*

---

## 4. Pass 2 — Mechanism Exploration (DEFERRED — not performed here)

Pass 2 will, for **each** capability above, surface and *compare* candidate mechanisms — e.g. for GC-S4-04: sortition vs ex-officio panel vs regional delegates. **No mechanisms are proposed in this document.** Pass 2 is a separate phase and remains within GCD (still no bounded contexts, aggregates, or services).

---

## 5. Realizability assessment (capability level, forward)

**The seven capabilities are individually necessary and jointly sufficient to realize S-4.** Round 37 ADR-5 (challenge architecture) is prior evidence that this capability set is constructible. Forward reading: S-4 is *realizable* within Option B; mechanism-level realizability is assessed in Pass 2.

**Forward design constraints carried downstream (not model reopenings):**
- GC-S4-04 carries the **structural-external-vantage residual** (functional independence only).
- GC-S4-02 and GC-S4-07 carry **PCS** (the dynamic residual).

The Option A reservation (38C-15 Part E) remains a dormant safety net — not an active GCD concern.

---

## 6. Open questions (routed forward — NOT answered here)

- **OQ-GCD02-01:** Minimum *functional* independence of GC-S4-04 assembly that is credible despite shared MA origin?
- **OQ-GCD02-02:** How does GC-S4-02 stay anti-frivolous **and** anti-suppression at once? (the PCS knife-edge)
- **OQ-GCD02-03:** Where does GC-S4-05 escalation hand off to the Constitutional Interpretation Chamber, given OQ-38A05-02 is PROTECTED?
- **OQ-GCD02-04 (cross-safeguard):** Standing to act on S-2 composition-drift (carried OQ-GCD01-03) appears to live in GC-S4-01/02 — does this couple S-2 and S-4 into shared capabilities?

---

## 7. Deliverable

```
S-4 Governance Capability Catalog (Pass 1 — capabilities only)
  GC-S4-01  Challenge Invocation
  GC-S4-02  Standing Protection            [PCS]
  GC-S4-03  Conflict Isolation             (recusal home)
  GC-S4-04  Independent Review Assembly    [load-bearing; structural-external-vantage residual]
  GC-S4-05  Review Decision                (intersects OQ-38A05-02)
  GC-S4-06  Corrective Action
  GC-S4-07  Challenge Observability        [PCS first-class]

Status: CANDIDATE CAPABILITIES — named, not yet mechanism-explored, not designed.
Realizability (capability level): CONFIRMED. Mechanisms deferred to Pass 2.
```

---

*Round 38C-GCD-02 — Challenge Pathways (S-4) — PASS 1 COMPLETE (capabilities only)*
*Pass 2 (mechanism exploration) and GCD-03 (S-1 Amendment Protection) are the candidate next steps*
*Strategic DDD remains GATED until the full S-1..S-5 capability catalog is assembled and accepted*
