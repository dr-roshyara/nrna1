# Round 38C-P2-19 — Research Threats & Methodology Maturity

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Pass 2 — scientific-rigor companion to P2-00 / P2-17 / P2-18 (binding)
**Status:** 🔒 LIVING — updated per family. Makes the methodology's *validity* an explicit object.
**Date:** 2026-06-25

> F-PROC is the **first independent replication study** of the methodology. A replication study must declare its **threats to validity**, its **maturity level**, and its **reliability protocol** up front. This document does that.

---

## 1. Research threats register (threats to validity)

| Class | Threat | Mitigation in force |
|-------|--------|---------------------|
| **Internal** | Confirmation bias; sketch bias; literature-selection bias; mechanism-interpretation bias | sketch-before-literature (P2-17); **prediction lock** (P2-18); **hostile testing** (P2-SYN-01 §10); retained rejected alternatives |
| **Construct** | Are "dimensions" real design axes or just useful categories? Are "families" real? Is "emergence" measurable? | orthogonality tests (validated-per-family, not universal); **measurable emergence** definition (P2-02I); families = *emergent*, candidate |
| **External** | One constitution (NRNA only); one architecture; **no empirical deployment**; single design context | scoped claims ("validated-for-F-AUTH"); GRP scoped to NRNA; lifecycle blocks premature "General Principle"; transferability axis |
| **Reliability** | Would *another* team classify the same mechanisms the same way? | explicit dimension definitions; **independent-reviewer protocol** (§3) — *not yet executed* |

**Honest standing threat:** all architecture-level observations (M-01–M-04) rest on **one** completed family. They are **working hypotheses**, not principles, until replicated.

---

## 2. Methodology maturity model

```
L0 Exploratory → L1 Repeatable → L2 Predictive → L3 Stable → L4 Externally validated
```

| Level | Meaning | Reached? |
|-------|---------|----------|
| L0 Exploratory | ad-hoc discovery | ✓ (early Pass 2) |
| L1 Repeatable | a fixed protocol applied consistently | ✓ (P2-00 frozen) |
| **L2 Predictive** | **generates explicit, pre-registered, falsifiable predictions** | **✓ — current level** |
| L3 Stable | predictions survive multiple families; **methodology saturation** (no protocol change across 2 consecutive families) | not yet |
| L4 Externally validated | an independent team replicates classifications/results | not yet |

**Current placement: L2+ (Predictive, with first successful hostile replication).** F-PROC narrowed M-01/M-03 under the locked protocol without ad-hoc changes — a *successful* hostile replication, which strengthens L2 but is **not yet L3** (L3 needs the pattern across F-THR + F-REV too). Promotion to **L3** requires F-THR + F-REV to update the register without relaxing criteria *and* the yield/saturation metric (P2-SYN-01 §9) to flatten. **L4** requires §3 (independent replication).

---

## 3. Independent-reviewer protocol (inter-rater reliability)

To claim reproducibility, another architect should, **without seeing our classifications**, independently: harvest mechanisms for a family, assign dimensions, and derive composites — then we compute **agreement** (inter-rater reliability).

**Current honest status:** the external reviews received so far (human reviewer + AI reviewers) are **critique**, not blind inter-rater classification — they see our work before commenting. So reliability is **single-classifier** today. This is a recorded external-validity/reliability threat; true inter-rater reliability is a **planned, not-yet-executed** validation step (a prerequisite for L4).

---

## 4. Methodology ADRs (ADR-M convention)

Every major methodology evolution gets a methodology ADR: *decision · reason · evidence · rejected alternatives · impact.* Seed log (full ADRs expandable later):

| ADR-M | Decision | Trigger |
|-------|----------|---------|
| **ADR-M-001** | Composite is the evaluation unit | F-AUTH (P2-02H-02) |
| **ADR-M-002** | Orthogonal dimensions (Class→Group→Mechanism) | F-AUTH category-error in classes |
| **ADR-M-003** | Prediction register (prospective, falsifiable) | post-F-AUTH validation |
| **ADR-M-004** | Prediction Lock Rule (no retroactive prediction) | replication-study rigor |
| **ADR-M-005** | Three spaces (Search→Candidate→Design) | F-AUTH exclusions |
| **ADR-M-006** | Transferability axis (evidence ≠ confidence ≠ transferability) | F-AUTH state-vs-association fit |

---

## Deliverable & next

```
Research rigor companion
  Threats register:   internal / construct / external / reliability (+ standing 1-family threat)
  Maturity model:     L0..L4; CURRENT = L2 Predictive; L3 needs saturation + survival; L4 needs independent replication
  Reliability:        single-classifier today; inter-rater protocol planned (not executed)
  ADR-M log:          methodology decisions recorded (001..006 seeded)
```

**Next:** run **F-PROC** under the **locked** register (P2-18) and the §10 hostile protocol, then update the register + threats + maturity. Strategic DDD GATED.

---

*Round 38C-P2-19 — Research Threats & Methodology Maturity — ISSUED*
*Threats register; maturity = L2 Predictive; reliability single-classifier (inter-rater planned); ADR-M log seeded*
*Next: F-PROC (locked, hostile). Strategic DDD GATED.*
