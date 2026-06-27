# Round 38C-GCD-SYN-02 — Governance Capability Family Synthesis

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Governance Capability Discovery — **Family Synthesis** (between SYN-01 and Pass 2)
**Authority:** 38C-15 ARB Ruling, Part F.1
**Status:** SYNTHESIS — consolidates cross-cutting capability families; no new discovery
**Date:** 2026-06-25

---

## Discipline boundary (binding)

This document answers **one** question: *which of the 33 Pass-1 capabilities are manifestations of the same governance capability family?* It produces **no mechanisms** (Pass 2), **no DDD** (gated), and resolves **no** open modeling question (S-3 stays open).

**A capability family is an *organizing layer*, NOT a bounded context, NOT a service, NOT an aggregate.** Those are DDD constructs and remain gated. The family layer exists so Pass 2 can explore mechanisms **once per family** and reuse them, instead of reinventing (e.g.) Observability five times.

---

## Why this layer exists (the missing abstraction)

SYN-01's most DDD-consequential finding was not EGCP or GRP — it was that **capabilities cluster into reusable families.** The pipeline therefore has a layer that was implicit:

```
Constitutional Properties (S-1..S-5)        — 38C-15
        ↓
Governance Capability Families              — THIS DOCUMENT (SYN-02)   ← the missing layer
        ↓
Governance Capabilities (GC-Sx-xx, 33)      — Pass 1
        ↓
Governance Mechanisms                       — Pass 2 (organized BY FAMILY)
        ↓
Strategic DDD → Tactical DDD                — gated
```

---

## Part 1 — The candidate capability families (all 33 capabilities mapped)

Five **emergent** candidate families — *discovered from* the capabilities, not imposed on them — account for every Pass-1 capability. They are **candidate** families throughout (never "the five governance families").

### F-OBS — Observability & Detection
*Make governance state visible; detect drift/erosion before harm.*
GC-S1-03 (Impact Assessment), GC-S1-07 (Erosion Detection), GC-S2-05 (Transparency), GC-S2-06 (Drift Detection), GC-S3-04 (Jurisdiction Observability), GC-S4-07 (Challenge Observability), GC-S5-04 (Finality Observability) — **7**

### F-ADJ — Constitutional Review (Adjudication)
*Invoke a challenge, grant standing, isolate conflict, assemble independent review, decide, route, and bring to closure.* **Challenge is only the *trigger*; the family's capability is *review*.** (Renamed from "Challenge & Resolution".)
GC-S3-05 (Jurisdiction Challenge), GC-S3-06 (Restoration), GC-S4-01 (Invocation), GC-S4-02 (Standing), GC-S4-03 (Conflict Isolation), GC-S4-04 (Review Assembly), GC-S4-05 (Review Decision), GC-S4-06 (Corrective Action), GC-S5-05 (Protected-Question Routing), GC-S5-06 (Resolution) — **10** *(the S-4 "infrastructure" cluster — largest family, confirming SYN-01 Part 3)*

### F-THR — Threshold & Entrenchment
*Place heightened, hard-to-cross bars protecting designated provisions/decisions from easy change.*
GC-S1-01 (Amendment Classification), GC-S1-02 (Tiered Threshold), GC-S1-06 (Entrenchment Self-Protection), GC-S3-02 (Scope Entrenchment), GC-S5-03 (Override Threshold) — **5**

### F-AUTH — Authority Composition & Distribution
*Define, distribute, and compose who holds authority (and over what).*
GC-S1-05 (Ratification Breadth), GC-S2-01 (Appointment Distribution), GC-S2-02 (Cohort-Limiting), GC-S2-04 (Nomination Integrity), GC-S3-01 (Jurisdiction Definition), GC-S3-03 (Competence Determination — *provisional; see Part 4*), GC-S5-01 (Finality Declaration) — **7**

### F-PROC — Process Integrity
*Govern how authority is exercised over time — timing, tenure, windows, succession.* *(Naming flag, not yet changed: all members concern **time/continuity**; "Governance Continuity" may fit better than "Process Integrity" — carried to GLOSSARY/Pass 2.)*
GC-S1-04 (Deliberation Integrity), GC-S2-03 (Tenure Protection), GC-S2-07 (Vacancy/Succession), GC-S5-02 (Finality Window) — **4**

**Coverage check:** 7 + 10 + 5 + 7 + 4 = **33** ✓ (every Pass-1 capability placed; no orphans).

---

## Part 2 — Families vs the EGCP lifecycle (observation)

The families are **not** identical to EGCP stages, but four of five align with one:

| Family | EGCP stage alignment |
|--------|----------------------|
| F-OBS | Observation |
| F-ADJ (Constitutional Review) | Challenge + Resolution |
| F-AUTH | Authority |
| F-PROC | Exercise |
| **F-THR** | **cross-cutting governance concern** — wraps Authority/Exercise rather than being a stage |

So **F-THR is a cross-cutting governance concern** (the DDD-recognizable framing): entrenchment is not a lifecycle step, it is a protection *applied to* steps. (Observation only — recorded, not elevated; consistent with EGCP being the working model and GRP the deeper invariant.)

---

## Part 3 — Implication for Pass 2 (why this ordering)

Pass 2 should be organized **by family**, not by safeguard:

```
Per family:  explore candidate mechanisms once  →  note reuse across the safeguards that draw on it
```

Worked illustration (mechanisms NOT explored here — Pass 2's job):
- **F-OBS** mechanisms (monitoring, signals, audit trails, trend detection) discovered **once**, reused by all 7 member capabilities across S-1/S-2/S-3/S-4/S-5 — instead of five near-duplicate inventions.
- **F-ADJ** mechanisms discovered once → this is also where the "Challenge as shared infrastructure" candidate (SYN-01 Part 3) would be examined.

This prevents the duplication SYN-01 already saw forming.

---

## Part 3.5 — Two orthogonal dimensions (recorded, NOT modeled)

Safeguards and emergent families are **not** a single hierarchy — they are **two orthogonal dimensions**, i.e. a matrix:

```
              S-1   S-2   S-3   S-4   S-5
  F-AUTH       ·     ·     ·           ·
  F-OBS        ·     ·     ·     ·     ·
  F-ADJ              (→)   ·     ·     (→)
  F-THR        ·           ·           ·
  F-PROC       ·     ·                 ·
```

Each safeguard draws on several families; each family serves several safeguards. This matrix may become very useful later (it hints at where shared capabilities and, eventually, context boundaries live). **Recorded as an observation only — not modeled, not elevated; Strategic DDD gated.**

---

## Part 4 — Open items carried forward (NOT resolved here)

- **S-3 status (still open).** GC-S3-03 (Competence Determination) is placed in **F-AUTH** *provisionally*, per the leading "Authority-refinement" reading. If the deferred S-3 question later resolves to "S-3 is meta / relationship-oriented," GC-S3-03 (and possibly GC-S3-01) may form a **distinct family** (candidate: *F-BND — Boundary/Competence*). This affects **catalog organization only**, not whether Pass 2 may explore GC-S3-03's mechanisms. Carried, not resolved.
- **Shared-capability consolidation** (which family members collapse into one shared capability in the DDD model) is a **Strategic DDD** question — gated. SYN-02 only *groups*; it does not *merge*.
- **The larger question** (are S-1..S-5 fundamental governance functions?) remains recorded and unanswered (SYN-01 Part 6).
- **RQ-SYN02-01 (recorded, NOT answered):** *Do emergent capability families remain stable when constitutional properties change — or are they artifacts of Option B?* I.e., had **Option A** been selected, would the same families appear? **If yes → families are deeper than the option chosen; if no → they are architecture-dependent.** Worth preserving; do not investigate now.

---

## Part 5 — Discipline guard

- A **family** is a discovery-time grouping for mechanism reuse. It is **not** a bounded context, service, module, or aggregate.
- Family names (F-OBS, F-ADJ, …) must **not** be carried into Strategic DDD as pre-decided contexts. Context boundaries are discovered *in* Strategic DDD, against these families as *input* — not dictated by them (OBS-36D-02-1 spirit: capability map ≠ context map).
- No mechanisms were proposed. DDD remains gated.

---

## Deliverable

```
Emergent Governance Capability Families (candidate; discovered from capabilities; organizing layer only)
  F-OBS   Observability & Detection            (7)   ↔ Observation
  F-ADJ   Constitutional Review (Adjudication) (10)  ↔ Challenge+Resolution  [S-4 infrastructure]
  F-THR   Threshold & Entrenchment             (5)   cross-cutting governance concern
  F-AUTH  Authority Composition & Distribution (7)   ↔ Authority   [holds S-3 open item]
  F-PROC  Process Integrity                    (4)   ↔ Exercise  [naming flag: "Continuity"?]
  Total: 33 capabilities, 0 orphans.

New pipeline layer recorded: Properties → Capability Families → Capabilities → Mechanisms → DDD.
S-3 status carried OPEN (candidate F-BND if it resolves to meta-governance).
Families are NOT bounded contexts/services (DDD gated).
```

---

## Recommended next step

**Authorize Pass 2 — mechanism exploration, organized by capability family** (F-OBS first is natural: highest reuse, lowest controversy). Strategic DDD remains gated until Pass 2 completes and shared-capability consolidation is taken up.

---

*Round 38C-GCD-SYN-02 — Governance Capability Family Synthesis — ISSUED*
*5 candidate families cover all 33 capabilities; F-THR is cross-cutting; 4 align with EGCP stages*
*Pipeline layer added: Properties → Families → Capabilities → Mechanisms → DDD*
*Next: Pass 2 by family (pending authorization). Strategic DDD GATED.*
