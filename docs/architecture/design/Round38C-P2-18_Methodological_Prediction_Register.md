# Round 38C-P2-18 — Methodological Prediction Register

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Pass 2 — protocol companion to P2-00 / P2-17 (binding)
**Status:** 🔒 LIVING REGISTER — updated after each family. The methodology's honesty instrument.
**Date:** 2026-06-25

> **Purpose:** transform methodological *observations* into *predictions* that the remaining families (F-PROC, F-THR, F-REV) must test. Mirrors Pass 1's discipline — now: *Methodological Observation → Prediction → Falsification by later families → Methodological Synthesis*. A prediction with **F-AUTH only** as evidence is **not yet generalized.**

Every methodological observation carries four fields: **Observation · Prediction · Falsifier · Current evidence.**

## Prediction Lock Rule (BINDING — No Retroactive Prediction)

Before each family's discovery begins, the register is **LOCKED**. **No prediction may be added, removed, reworded, or re-scored during discovery.** After the family's formal document, the register is **UNLOCKED** and each prediction updated to exactly one status: **Supported / Narrowed / Refuted / Inconclusive** (the last when the evidence genuinely does not bear on it). Sequence: `Lock → discover → evaluate → unlock → update`. This removes even the *appearance* of hindsight bias and sits alongside the DDD gate as a standing rule.

> **🔓 LOCK STATUS:** v1 was LOCKED for F-PROC; **UNLOCKED and updated post-F-PROC (v2, 2026-06-25)** — see Register Update Log. **Re-lock before F-THR.**

**Assumption vs Prediction (keep distinct):** an *assumption* (e.g. "composite evaluation is meaningful") underlies a *prediction* (e.g. "future families organize into composites"); the chain is Assumption → Prediction → Evidence → Conclusion. Only predictions are scored here.

---

## Register (v1 — after F-AUTH)

| # | Observation | Prediction (if generally true) | Falsifier | Evidence | Status |
|---|-------------|-------------------------------|-----------|----------|--------|
| **M-01** | Composite is the unit of design | Remaining families require **composite** evaluation, not single-mechanism | A family **adequately explained by one mechanism** | F-AUTH only | provisional |
| **M-02** | Mechanism quality ≠ composite quality | Highest-quality mechanism won't always yield the best composite | A family where the best mechanism **always** yields the best composite | F-AUTH only | provisional |
| **M-03** | Target behaviour is **emergent** | Other resilience properties emerge from **interaction**, not isolated mechanisms | A single mechanism **fully explains** the resilience property | F-AUTH only | provisional |
| **M-04** | Novelty is in **composition** | Future families' novelty = new compositions, not new mechanisms | A family needing an **entirely novel mechanism** | F-AUTH only | provisional |
| **M-05** | **Orthogonal dimensions** model mechanisms | Mechanisms map cleanly as coordinates; D1–D5 (or per-family dims) suffice | A family needing a **new dimension** | F-AUTH (validated-for-F-AUTH) | candidate-universal |
| **M-06** | Sketch-before-literature reduces anchoring | Future harvests show **high sketch↔literature convergence** | **Large divergence** between sketch and literature | F-AUTH only | provisional |

---

## Annotations — strength · level · lifecycle state

Outcomes use **three values, not two**: **Supported · Narrowed · Refuted** (real science narrows more often than it destroys). Lifecycle states are defined in P2-SYN-01 §6.

| # | Strength | Confidence (≠ lifecycle) | Independence | Level | Lifecycle state |
|---|----------|--------------------------|--------------|-------|-----------------|
| M-01 | Weak (1 family) | Medium (1 family, no contradictory evidence) | **Foundational (hub)** | Architecture | Observed |
| M-02 | Weak | Low | Derived (← M-01) | Architecture | Observed |
| M-03 | Weak | Low | Derived (← M-01) | Architecture | Observed |
| M-04 | Weak | Low | Derived (← M-01) | Architecture | Observed |
| M-05 | Moderate | Medium | Foundational | Method | Observed (adopted) |
| M-06 | Moderate | Medium | Independent | Method | Observed (adopted) |

*Levels:* **Method** · **Architecture** · **Family** · **Project** — do not mix when reasoning about a result. *Independence:* Foundational / Derived / Dependent — **a Derived prediction failing matters less than its Foundational parent failing.**

**Prediction impact analysis (if it fails/narrows):**
- **M-01 (hub):** M-02/M-03/M-04 narrow with it. Affected: composite evaluation template, interaction matrix, the "composite = unit of design" framing, the G3 composite-graph prospect, and the **L3 Composition** architecture level.
- **M-05:** scopes the dimension model (per-family dimensions instead of universal D1–D5).
- **M-06:** affects only the harvest *ordering* (sketch-before-literature); no architecture impact.

---

## Specific predictions (testable, recorded — not adopted)

| # | Prediction | Falsifier | Evidence | Test family |
|---|-----------|-----------|----------|-------------|
| **P-CAP** | Capture-resistant / resilience architectures **require multi-dimension interaction** | a single mechanism delivers the property | F-AUTH supports | F-THR, F-REV |
| **P-D6** | A **D6 "Accountability"** dimension may be required (periodic review, transparency obligations, reporting, reappointment review) — distinct from D5 Protection | D1–D5 fully cover F-REV/F-PROC without it | none yet | F-PROC, **F-REV** |
| **P-PATTERN** | A reusable **"Governance Pattern"** layer emerges *iff* the same composite recurs across families | no composite recurs across families | none yet | after F-REV |

*P-D6 and P-PATTERN are **predictions only** — do **not** create the dimension or the layer now; let the families decide (the EGCP discipline).*

---

## Register update log

**v2 — after F-PROC (2026-06-25, hostile replication, `Round38C-P2-03`):**

| Pred | Status | Note |
|------|--------|------|
| M-01 | **NARROWED** | composite is the unit *for interacting/emergent families* only; additive families (F-PROC) → mechanism-by-mechanism |
| M-02 | **NARROWED** | holds only where M-01 holds |
| M-03 | **NARROWED** | emergence is family-specific, not universal |
| M-04 | Inconclusive | F-PROC mechanisms standard; no composition novelty to judge |
| M-05 | **SUPPORTED** → lifecycle *Replicated* | D1–D5 classified F-PROC cleanly; no new dimension |
| M-06 | **SUPPORTED** → lifecycle *Replicated* | blind sketch covered the space |
| P-CAP | Inconclusive | F-PROC property is not resilience/capture |
| P-D6 | Inconclusive | accountability out of F-PROC scope — defer to F-REV |
| P-PATTERN | Weakened | F-PROC additive ≠ F-AUTH interacting; no shared pattern yet |

**New:** **M-07 (candidate, Observed across F-AUTH+F-PROC)** — families divide into **interacting/emergent** (composite = unit) vs **additive/independent** (mechanism = unit); **composite evaluation is conditional on cross-dimensional interaction**, not universal. M-07 is the refined replacement for the over-broad M-01. *Re-locked status pending F-THR.*

---

## Update rule

After each family's formal Pass-2 document, this register is updated: each prediction is marked **reinforced / narrowed / falsified**, and "Evidence" gains the family. M-01–M-04 graduate from *provisional* to *methodology-wide* only when reinforced by F-PROC **and** F-THR **and** F-REV without falsification (or are explicitly narrowed). This is the gate before any "methodology synthesis" (P2-SYN-FINAL).

---

*Round 38C-P2-18 — Methodological Prediction Register — ISSUED (living; v1 after F-AUTH)*
*Observation → Prediction → Falsifier → Evidence. M-01..M-04 provisional (F-AUTH only); M-05/M-06 adopted-as-process; P-CAP/P-D6/P-PATTERN recorded predictions.*
*Updated after F-PROC, F-THR, F-REV. Strategic DDD GATED.*
