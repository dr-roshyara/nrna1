# Round 39-MC — Methodology Constitution

**Program:** NRNA DDD Trustworthiness Research Program
**Tier:** **CONSTITUTIONAL** — the most entrenched methodology document; sits **above** the Specification, ADRs, Validator, and Baseline.
**Status:** 🏛️ ADOPTED (2026-06-25) — by sponsor authority (recorded as ADR-M-012)
**Date:** 2026-06-25

> **Why this exists.** The Specification, ADRs, Validator, Prediction Register, Baseline, and Audit are all *operational* artifacts — they may evolve through ADR governance. What was still **implicit** are the principles that **no ADR may override**. This document makes them explicit. It is the methodology's equivalent of a constitution above legislation: ordinary change control (ADR-M) operates *beneath* it; these principles change only by constitutional amendment.

## Hierarchy

```
Methodology Constitution        ← MC-01..MC-08 (this document; entrenched)
        ↓ governs
Methodology Specification        (Round39-01, v0.9.1)
        ↓ governs
ADR-M Decision Records           (Round39-D6)
        ↓ informs
Integrity Validator              (Round39-02)
        ↓ assembled into
Methodology Baseline             (Round39-03, MB-39.1)
        ↓ executed by
Family Execution                 (F-AUTH, F-PROC, F-THR, F-REV)
```

An ADR, Spec clause, validator rule, or baseline that conflicts with any constitutional principle is **void to the extent of the conflict.**

---

## The principles

### MC-01 — Constitutional properties are not modifiable by methodology decisions
No methodology decision (ADR, Spec clause, validator) may create, weaken, or redefine a **governance** constitutional property (e.g. anonymity, Option B + S-1..S-5). The methodology *observes and tests*; it never legislates governance. *(Codifies INV-5.)*

### MC-02 — Methodology cannot create governance requirements
A methodological convenience is never a governance obligation. The flow **Methodology → Governance is "protocol only"** — never a new requirement on the governance architecture. *(Codifies the research-object boundary contract, Round39-02 Validator 4.)*

### MC-03 — Evidence cannot be rewritten
Evidence is **append-only**. Observations, family outcomes, and prediction results are never edited to fit a later conclusion; they are superseded by dated, traceable new evidence. Provenance is preserved. *(Codifies the evidence model, ADR-M-005/006.)*

### MC-04 — Predictions must exist before observation
No prediction may be authored, reworded, or re-scored to match an observation already made. The register is locked before discovery. *(Codifies ADR-M-004 / INV-2; the No-Retroactive-Prediction rule.)*

### MC-05 — Governance discoveries cannot bypass methodology governance
A discovery made during a family execution **proposes** (Draft ADR); it never **enacts**. Methodology changes only through ADR governance, between executions — never during one. *(Codifies the binding change-process, ADR-M / Round39-03 §5.)*

### MC-06 — DDD cannot influence governance discovery
The DDD gate is one-directional. **Software → Governance = Never.** No anticipated context, aggregate, or implementation concern may shape which governance properties or mechanisms are discovered. *(Codifies the DDD gate; Round39-02 Validator 5.)*

### MC-07 — Research objects remain independent
The three domains — **Governance**, **Methodology**, **Software Translation** — are distinct. No artifact may conflate them. Allowed flows are exactly those in the boundary contract; all others are forbidden. *(Codifies D7; Round39-02 Validator 4.)*

### MC-08 — Scientific uncertainty shall never be hidden by a governance decision
An **Accepted** ADR means "this is the procedure we follow," **never** "this hypothesis is established." Confidence, replication count, and transferability are always disclosed alongside any provisional observation. ADR status and methodology maturity are never conflated. *(Codifies the two-vocabularies separation, ADR-M-009 / Round39-D6.)*

---

## Conformance of MB-39.1 (codification, not change)

Each principle is **already** enforced by an existing artifact — this Constitution names them, it does not introduce new constraints. MB-39.1 is therefore **undisturbed** and remains locked.

| Principle | Already enforced by | MB-39.1 conforms? |
|-----------|---------------------|-------------------|
| MC-01 | INV-5 (Spec §15) | ✓ |
| MC-02 | Validator 4 boundary contract (Round39-02) | ✓ |
| MC-03 | Evidence model (ADR-M-005/006); register update log (P2-18) | ✓ |
| MC-04 | ADR-M-004 / INV-2; register v3 LOCKED | ✓ |
| MC-05 | Binding change-process (Round39-D6 §; Round39-03 §5) | ✓ |
| MC-06 | DDD gate; Validator 5 | ✓ |
| MC-07 | D7 boundary contract; Validator 4 | ✓ |
| MC-08 | Two-vocabularies separation (Round39-D6); confidence fields (P2-18) | ✓ |

**Result:** no operational artifact changes; MB-39.1 stays locked and valid; the Constitution becomes its superior governing tier.

---

## Amendment rule (entrenched)

- A constitutional principle **MUST NOT** be changed, weakened, or removed by a routine ADR-M.
- Amendment requires a dedicated **Constitutional Amendment Record**: explicit rationale, sponsor **and** ARB assent, a version bump of this document, and a stated reason why an operational change is insufficient.
- Any ADR-M, Spec clause, or baseline in conflict with a principle is **void to the extent of the conflict** until amended or reconciled.
- This document is **outside** the Methodology Baseline (it governs the baseline). It is therefore not frozen *by* a baseline lock; it is entrenched by its own amendment rule, which is stricter.

---

*Round 39-MC — Methodology Constitution — ADOPTED (2026-06-25, ADR-M-012)*
*8 entrenched principles above the Specification; MB-39.1 verified conformant (codification, not change); amendment only by Constitutional Amendment Record. The methodology is now self-governing with a constitutional ceiling. Strategic DDD GATED.*
