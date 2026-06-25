# Round 39-02 — Methodology Integrity Audit (D5) + Coverage Matrix (D5.1) + Freeze Review

**Program:** NRNA DDD Trustworthiness Research Program
**Workstream:** Round 39 (Methodology Stabilization) — D5
**Status:** AUDIT — a *compiler pass* over the methodology, not a review. Findings are fixed, not just noted.
**Scope:** P2-00, P2-17, P2-18, P2-19, P2-SYN-01, GLOSSARY-01, Round39-01 (Spec v0.9.1), and the family docs P2-01/02/03.
**Date:** 2026-06-25

---

## 1. Semantic integrity (one authoritative definition; no contradiction; no circularity)

| Check | Result |
|-------|--------|
| Every concept one authoritative definition | **PASS** — ontology in Spec §3 + GLOSSARY-01; "family" reserved for capability families; "Class→Group→Mechanism" for mechanisms |
| Contradictory definitions | **none found** |
| Circular definitions | **none found** (composite → dimensions → mechanisms; no loop) |
| **Finding F-1** | **M-05/M-06 vs R-01/R-02 dual naming** — empirical-vs-rule split introduced post-F-PROC. **FIXED:** P2-18 now canonicalizes **R-01(=M-05), R-02(=M-06)** as *method-rules*; M-05/M-06 retained as historical aliases. |

## 2. Lifecycle integrity (one state per observation; one status per prediction; maturity consistent)

| Check | Result |
|-------|--------|
| One lifecycle state per observation | **PASS after fix** |
| **Finding F-2** | P2-18 listed R-01/R-02 lifecycle as "Observed" but P2-SYN-01 matrix recorded them "Replicated" post-F-PROC — **inconsistency. FIXED:** P2-18 now reads **Replicated (post-F-PROC)**, matching the matrix. |
| One status per prediction | **PASS** — register v2: M-01/02/03 Narrowed, M-04/P-CAP/P-D6 Inconclusive, P-PATTERN "no supporting evidence yet", M-07 Observed |
| Maturity consistent | **PASS** — Spec §10 and P2-19 both say **L2+**; no other value appears |

## 3. Traceability integrity (every major claim → observation / evidence / ADR / literature / protocol)

| Claim | Traces to |
|-------|-----------|
| Composite-conditional rule (M-07) | F-AUTH (emergent) + F-PROC (additive) evidence; P2-03 Step 5 |
| Option B + S-1..S-5 | 38C-15 ruling; 14C leadership decision |
| GRP-01 | SYN-03 cycle analysis (graph-derived) |
| Transferability axis | ADR-M-006; F-AUTH state-vs-association |
| MUST NOT / frequency | ADR-M-007; Spec v0.9.1 |
| **Finding F-3** | **ADR-M log not yet a standalone artifact** (seeded in P2-19; ADR-M-007 referenced from Spec). **Open → D6** (create the ADR-M log). Non-blocking for F-THR. |

## 4. Governance integrity (who/conditions/artifact/version per change)

| Frozen artifact | Who may change | Condition | Version effect |
|-----------------|----------------|-----------|----------------|
| Spec (R39-01) | ARB/sponsor | ADR-M + bump | v0.9.x → v1.0 after F-REV |
| EGCP-01 | — | dated erratum only (non-structural) | n/a (frozen pre-registration) |
| GLOSSARY-01 | — | add terms; never silently redefine | n/a |
| P2-00 | — | bug fix / evidence-driven only | noted inline |
| Prediction Register | per Lock Rule | locked during discovery | v1→v2→… per family |

**PASS** — every change path is defined. *(F-1/F-2 fixes were applied as audit corrections, recorded here.)*

## 5. Architectural integrity (no leakage between the three research objects)

| Boundary | Result |
|----------|--------|
| Governance ↛ Software (DDD gate) | **PASS** — no contexts/aggregates/services anywhere; body-risk classification explicitly deferred to Strategic DDD |
| Methodology ↛ Governance | **PASS** — methodology observations (M-*) never alter constitutional properties (INV-5) |
| Software ↛ Methodology | **PASS** — no implementation concern appears in methodology rules |
| **Finding F-4** | **P2-01 (F-OBS) predates the enriched methodology** — light retrofit (evidence/confidence/transferability/origin) pending at consolidation. Known gap; non-blocking. |

---

## D5.1 — Methodology Test Coverage Matrix (what has been *exercised*)

| Rule / construct | Exercised? | By |
|------------------|-----------|----|
| Prediction Lock Rule | **Yes** | F-PROC (locked → unlocked) |
| Hostile testing (§10) | **Yes** | F-PROC (narrowed M-01/03) |
| Three-outcome status (Supported/Narrowed/Refuted/Inconclusive) | **Yes** | F-PROC (all four used) |
| Orthogonal dimensions (R-01) | **Yes** (validated-for-F-AUTH; replicated F-PROC) | F-AUTH, F-PROC |
| Sketch-before-literature (R-02) | **Yes** | F-AUTH, F-PROC |
| Composite evaluation (M-01) | **Yes** | F-AUTH (used), F-PROC (not needed → narrowed) |
| Emergence (measurable def.) | **Partial** | F-AUTH (met all 3); F-PROC (failed → additive) |
| M-07 interaction profiles | **Partial** | 2 families (Emergent, Additive); Mixed untested |
| **P-PROFILE** | **No** | pending **F-THR** |
| **P-CAP** | **No** | pending F-THR |
| **P-D6** (Accountability) | **No** | pending **F-REV** |
| **P-PATTERN** | **No** | pending F-THR/F-REV |
| Saturation criterion | **No** | pending (needs 2 consecutive no-change families) |
| Inter-rater reliability | **No** | not executed (single-classifier) |

**Coverage reading:** the *core loop* (lock → hostile → narrow → update) is exercised and worked. The *generalization* constructs (P-PROFILE, P-CAP, P-D6, P-PATTERN, saturation, inter-rater) are **not yet exercised** — which is exactly why the spec is **v0.9, not v1.0.**

---

## Freeze Review (is the spec ready to govern F-THR?)

| # | Question | Answer |
|---|----------|--------|
| 1 | Every protocol ambiguity resolved? | **Yes** (operational defs in §12; F-1/F-2 fixed) |
| 2 | Every prediction locked? | **Yes** (register re-lockable; v2 recorded) |
| 3 | Every artifact versioned? | **Yes** (Spec v0.9.1; register v2; frozen docs dated) |
| 4 | Every invariant documented? | **Yes** (INV-1..5, §15) |
| 5 | **Can an independent researcher execute F-THR using only the specification?** | **Yes — with two caveats:** (a) the ADR-M log is not yet standalone (F-3 → D6), (b) F-OBS retrofit pending (F-4). Neither blocks an F-THR execution from Spec v0.9.1 + P2-18 + P2-SYN-01. |

**Verdict: the Controlled Working Specification v0.9.1 is READY to govern F-THR.** Recommended (non-blocking) before/with F-THR: close F-3 (D6 ADR-M log).

---

## Deliverable & next

```
D5 Integrity Audit: PASS (5 dimensions); 4 findings — F-1/F-2 FIXED, F-3 (ADR-M log → D6), F-4 (F-OBS retrofit, known)
D5.1 Coverage matrix: core loop EXERCISED; generalization constructs PENDING (= why v0.9)
Freeze Review: Q1-Q5 satisfied → Spec v0.9.1 READY to govern F-THR
```

**Next:** **D6** (ADR-M log, closes F-3) — quick — then **re-lock register → F-THR**. D4 (traceability matrix) and D7/D8 (research-object/literature) and DDD-Readiness can follow F-THR without blocking it.

---

*Round 39-02 — Methodology Integrity Audit (D5) + Coverage (D5.1) + Freeze Review — ISSUED*
*5 integrity dimensions PASS (2 findings fixed inline); core loop exercised; Spec v0.9.1 cleared to govern F-THR. Strategic DDD GATED.*
