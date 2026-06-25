# Round 39-03 — Methodology Baseline **MB-39.1**

**Program:** NRNA DDD Trustworthiness Research Program
**Workstream:** Round 39 (Methodology Stabilization) — baseline package + Baseline Lock
**Status:** 🔒🔒 **BASELINE LOCKED** (2026-06-25) — immutable for the duration of F-THR
**Date:** 2026-06-25
**Identifier:** **MB-39.1**

> **Purpose.** F-THR is the **first controlled hostile replication executed under a single, named, immutable methodology**. Before it runs, the entire methodology — not just the prediction register — is bundled under one identifier and frozen. Any future reviewer asking *"which methodology produced the F-THR evidence?"* answers with **one token: MB-39.1.**

> **Superior tier.** MB-39.1 operates **beneath the Methodology Constitution** (`Round39-MC`, MC-01..MC-08, ADR-M-012). The Constitution is *not* a baseline constituent — it governs the baseline and is entrenched by its own (stricter) amendment rule. MB-39.1 has been verified conformant to all eight principles (see Round39-MC conformance table); this reference is non-substantive and changes no constituent.

---

## 1. Baseline constituents (exact artifacts + versions)

MB-39.1 is **exactly** the following set. Nothing outside this set is part of the baseline; nothing inside it may change while the baseline is locked (see §3).

| # | Component | Artifact | Version / state at lock |
|---|-----------|----------|--------------------------|
| 1 | Controlled Working Specification | `Round39-01_Methodology_Specification` | **v0.9.1** |
| 2 | Prediction Register (locked) | `Round38C-P2-18_Methodological_Prediction_Register` | **v3 — LOCKED for F-THR** |
| 3 | Methodology Decision Records | `Round39-D6_Methodology_Decision_Records_ADR-M` | ADR-M-001..011 |
| 4 | Integrity Validator report + Integrity Audit + Coverage | `Round39-02_Methodology_Integrity_Audit` | D5 / D5.1 (NO Critical) |
| 5 | Threats & Maturity | `Round38C-P2-19_Threats_and_Maturity` | maturity **L2+** |
| 6 | Methodology Validation | `Round38C-P2-SYN-01` | lifecycle + convergence + cadence |
| 7 | Protocol & inception | `Round38C-GCD-P2-00` (+ `P2-17` provenance) | frozen |
| 8 | Ontology / Glossary | `GCD-GLOSSARY-01` + Spec §3 | frozen |
| 9 | Maturity model | Spec §10 + P2-19 | L0–L4 (current L2+) |

*If an enumerated artifact's filename differs on disk, the identifier above is canonical; the on-disk file is the binding text.*

**Baseline hash-of-intent.** MB-39.1 = {Spec v0.9.1 · Register v3-locked · ADR-M-001..011 · Validator(no-Critical) · P2-19(L2+) · P2-SYN-01 · P2-00/17 · GLOSSARY-01}. Changing **any** element ⇒ a new baseline identifier (MB-39.2), never an edit to MB-39.1.

---

## 2. Baseline Lock (supersedes "re-lock the prediction register")

What is locked is **the whole baseline**, not only the register:

- specification, ADRs, validator, **prediction register**, ontology/glossary, maturity model, protocol docs.

The Prediction Register is **one component** of the lock, not the lock itself.

**🔒 LOCK STATUS: MB-39.1 LOCKED on 2026-06-25 for the duration of F-THR.**

---

## 3. Freeze discipline (BINDING, stronger than the per-doc freeze)

While MB-39.1 is locked, **nothing** in any constituent may change. Not wording, examples, glossary, validator, ADR text, or the specification.

**Permitted while locked (no version bump):**
- typo fixes, formatting, broken cross-references.

**Forbidden while locked (each becomes a Draft ADR, reviewed *after* F-THR):**
- any change to a rule, definition, prediction, status, threshold, dimension, validator behaviour, or maturity claim;
- any new methodology concept;
- any reinterpretation of an existing one.

**The prediction register MUST NOT be unlocked until the F-THR formal document is complete.** Sequence inherited from ADR-M-004: `Baseline Lock → discover → evaluate → unlock → update`.

---

## 4. F-THR runs under MB-39.1

The F-THR document **SHALL** open with:

> *Executed under Methodology Baseline **MB-39.1**.*

F-THR tests the predictions already recorded in Register v3 — principally **P-PROFILE** (next family's interaction profile: Emergent / Additive / **Mixed**), **P-CAP**, and **M-07** (composite-conditional typology) — plus any others bearing on the F-THR family. No prediction is added or reworded during execution.

---

## 5. Between-executions evolution (the only way the methodology changes)

```
Baseline MB-39.1
       ↓
F-THR  (executed under MB-39.1; methodology frozen throughout)
       ↓
F-THR Execution Report  (+ anomalies recorded as Draft ADR proposals only)
       ↓
Methodology Governance Review  (sponsor + ARB)
       ↓
ADR Decisions  (Accept / Reject; rejected alternatives retained)
       ↓
MB-39.2   (new baseline identifier — only if any ADR is accepted)
       ↓
F-REV  (executed under MB-39.2, or MB-39.1 if no change was warranted)
```

**The methodology never changes during an execution — only between executions.** This mirrors controlled-protocol evolution in engineering/scientific practice and keeps each family's evidence attributable to exactly one baseline.

---

## 6. Readiness checklist

✓ All constituents enumerated with versions · ✓ single identifier assigned (MB-39.1) · ✓ baseline locked · ✓ freeze discipline declared (typo/format/refs only) · ✓ register lock status = v3 LOCKED · ✓ F-THR opening attribution defined · ✓ post-execution review workflow defined · ✓ MB-39.2 reserved for any accepted change.

---

*Round 39-03 — Methodology Baseline MB-39.1 — BASELINE LOCKED (2026-06-25)*
*9 constituents; whole-baseline freeze (not just the register); families execute under one immutable identifier and may only PROPOSE changes between executions. F-THR is now unblocked under MB-39.1. Strategic DDD GATED.*
