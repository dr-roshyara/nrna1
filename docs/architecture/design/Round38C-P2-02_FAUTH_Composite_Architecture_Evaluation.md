# Round 38C-P2-02 — F-AUTH: Composite Governance Architecture Evaluation

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Pass 2 — F-AUTH (Authority Composition & Distribution) — **formal document**
**Governed by:** P2-00 (frozen) + P2-17. Inputs: P2-02H (harvest), P2-02I (dimensions + interactions).
**Status:** EVALUATION — governance architecture only. **No DDD (gated).**
**Date:** 2026-06-25

> **Objective (redefined after the harvest):** *Evaluate candidate composite governance architectures assembled from mechanisms distributed across orthogonal design dimensions, rather than evaluating mechanisms in isolation.* (Per Observation P2-02H-02: capture resistance is emergent; the **composite** is the evaluation object.)

---

## Part 1 — Capability

F-AUTH realizes: GC-S1-05 Ratification Breadth · GC-S2-01 Appointment Distribution · GC-S2-02 Cohort-Limiting · GC-S2-04 Nomination Integrity · GC-S3-01 Jurisdiction Definition · **GC-S3-03 Competence Determination (open S-3 status)** · GC-S5-01 Finality Declaration. The dominant F-AUTH purpose: realize **authority that cannot be captured** under Option B (single source).

## Part 2 — Design space (from P2-02H)

Search space → design space via the constitutional filters (Option B, anonymity N/A here, GRP, family interactions). **Exclusions retained** (P2-17 §2): external-guarantor appointment (no external sovereign), opaque algorithmic appointment (architectural FAIL), lifetime tenure, incumbent co-optation.

## Part 3 — Mechanism dimensions (orthogonal; from P2-02I)

D1 Authority Source · D2 Temporal · D3 Qualification · D4 Approval · D5 Protection. Mechanisms are **coordinates**, not buckets.

## Part 4 — Mechanism catalogue (catalogued, NOT yet judged)

| Mechanism | Dim | Origin | Ev | Conf | Transfer | Emergence |
|-----------|:--:|--------|:--:|:--:|:--:|:--:|
| Staggered terms | D2 | Constitutional | ★★★★★ | ★★★★ | ★★★★★ | **HIGH** (weak alone) |
| Long non-renewable terms | D2 | Constitutional/Oversight | ★★★★★ | ★★★★ | ★★★★ | MED |
| Multiple / mixed appointers | D1 | Constitutional/Election | ★★★★ | ★★★★ | ★★★★ | **HIGH** |
| Sortition | D1 | Governance-eng / classical | ★★★ | ★★★ | ★★★★★ | **HIGH** |
| Cohort cap | D1 | NRNA-derived | ★★ | ★★★★ | ★★★★★ | **HIGH** |
| Eligibility criteria | D3 | Election/Oversight | ★★★★★ | ★★★★ | ★★★★★ | LOW |
| Independent nomination commission | D3(/D1) | Constitutional (JAC) | ★★★★ | ★★★★ | ★★★ | MED |
| Conflict screening | D3 | Governance-eng | ★★★★ | ★★★★★ | ★★★★★ | LOW |
| Supermajority confirmation | D4 | Constitutional (Germany/Mexico) | ★★★★★ | ★★★★ | ★★★ | **HIGH** |
| Cross-faction / regional ratification | D4(/D1) | Election admin | ★★★★ | ★★★★ | ★★★★★ | HIGH |
| Removal-for-cause | D5 | Oversight (central bank) | ★★★★★ | ★★★★★ | ★★★★ | LOW (standalone value) |
| Succession / caretaker | D5 | Governance-eng | ★★★★ | ★★★★★ | ★★★★★ | LOW |

*Evidence ≠ Confidence ≠ Transferability (P2-17). Note the divergences: sortition/cohort-cap are low-evidence but high-transferability (association-native); supermajority is high-evidence but only ★★★ transferable (assumes a reachable threshold — the MA-reach risk).*

## Part 5 — Candidate composite architectures (coordinate selections)

| | D1 Source | D2 Temporal | D3 Qualification | D4 Approval | D5 Protection |
|--|-----------|-------------|------------------|-------------|---------------|
| **A — Capture-Resistant (maximal)** | multi / mixed | staggered + long + non-renewable | independent nomination | supermajority | removal-for-cause |
| **B — Minimal Anti-Cohort (light)** | cohort cap | staggered + fixed | eligibility only | simple | fixed terms |
| **C — Legitimacy-Weighted (diaspora-fit)** | mixed + regional + sortition element | staggered | eligibility | cross-faction / regional ratification | removal-for-cause |

Provenance: A = Constitutional + Oversight + JAC + **NRNA-derived composition**; B = **NRNA-derived**; C = Election-admin + **NRNA-derived** (regional/sortition tuned to a cross-border federation).

## Part 6 — Composite evaluation (the composite is the object)

| Composite | Functional (capture resistance) | Architectural (GRP/Option-B) | Transferability | Emergence | Complexity / burden | Composite quality |
|-----------|:--:|:--:|:--:|:--:|:--:|:--:|
| **A** | **strongest** | strong; but D4 supermajority depends on MA-reach | ★★★ | **very high** | **high** | high for high-risk bodies |
| **B** | weak (long-horizon PAN survives) | adequate | ★★★★★ | low | low | adequate for low-risk only |
| **C** | strong | strong; broad legitimacy reduces capture surface | ★★★★★ | high | medium | high for representative bodies |

**Mechanism-quality ≠ composite-quality (recorded):** *Independent nomination commission* is a ★★★★★ mechanism, but `independent nomination + single appointing authority` is a **poor composite** (the commission just feeds one capturable approver). Quality is a property of the *selection across dimensions*, not of any cell.

**The MA-reach finding (architectural):** Composite A's D4 supermajority is only as strong as the difficulty for MA-aligned actors to *reach* the threshold — given MA's appointment functions (38C-14), D4 alone is not load-bearing; A's strength comes from the **interaction** D1+D2+D4 together (emergence), not D4 in isolation. This is Observation P2-02H-02 made concrete.

## Part 7 — Recommendation (composite-level; per Rule 9)

**No single composite dominates across all bodies — recommendation is risk-tiered (situational):**

| Body risk tier | Recommended composite | Why |
|----------------|----------------------|-----|
| **High-risk** (oversight / interpretation bodies — where PAN is most dangerous) | **A**, optionally **A+C** hybrid | maximal capture resistance; legitimacy where the body is representative |
| **Representative / broad-mandate** | **C** | legitimacy-weighted; association-native; highest transferability |
| **Low-risk operational** | **B** | proportionate; avoids over-engineering |

**Alternatives retained:** all three composites + the Part-2 exclusions. **No-dominant is the honest result** — the right composite depends on the body's capture risk and representativeness, which is itself a (Strategic-DDD) classification question, not a Pass-2 one.

## Part 8 — Open items (carried, NOT resolved)

- **GC-S3-03 / S-3 status:** Competence Determination's appointment aspect uses these F-AUTH composites **only if** S-3 resolves to Authority-refinement; if S-3 is meta/relationship-level, GC-S3-03 forms its own family (F-BND) and is composed separately. **Carried open.**
- **Cross-family requires:** A and C depend on **F-REV** (who appoints/judges the independent nomination commission and adjudicates removal-for-cause — the GRP at mechanism level) and **F-THR** (the D4 thresholds). Resolve when those families are reached.
- **Body-risk classification** (which body is "high-risk") is a Strategic-DDD question — **not** decided here.

## Methodological observation

**Observation P2-02H-03 (recorded):** *Literature primarily contributes individual mechanisms; architectural novelty emerges primarily through new **compositions** of mechanisms rather than invention of entirely new mechanisms.* This matches historical constitutional innovation (reuse devices, compose differently, obtain different behavior) — and the methodology now detects it explicitly (composite provenance, Part 5).

## Completion check (F-AUTH, per P2-00)

Design space explored + classified ✓ · dimensions (D1–D5) ✓ · catalogue with evidence/confidence/transferability/emergence ✓ · composites defined + evaluated ✓ · recommendation (risk-tiered / no-dominant) ✓ · alternatives + exclusions retained ✓ · open questions recorded ✓. **F-AUTH Pass 2: COMPLETE.**

---

## Deliverable & next

```
F-AUTH composite governance architecture evaluation
  Dimensions:   D1–D5 orthogonal
  Composites:   A Capture-Resistant / B Minimal Anti-Cohort / C Legitimacy-Weighted
  Recommendation: RISK-TIERED, no single dominant (A high-risk / C representative / B low-risk)
  Key:          capture resistance is EMERGENT (D1+D2+D4 interaction), not from any single mechanism
  Mechanism-quality != composite-quality (recorded)
  Carried open: GC-S3-03/S-3 status; F-REV + F-THR cross-family requires; body-risk classification (DDD)
Status: governance architecture only. Strategic DDD GATED.
```

**Next:** Pass 2 — **F-PROC** (Process Integrity), then **F-THR**, then **F-REV** (hub, last — closes the nomination/removal recursion). The composite-architecture principle (P2-02H-02) is now pending reinforcement by F-THR and F-REV before any elevation.

---

*Round 38C-P2-02 — F-AUTH Composite Governance Architecture Evaluation — ISSUED*
*Unit of evaluation = composite, not mechanism; recommendation risk-tiered (no dominant); emergence + transferability scored*
*Obs P2-02H-03: novelty is in composition, not invention. Next: F-PROC. Strategic DDD GATED.*
