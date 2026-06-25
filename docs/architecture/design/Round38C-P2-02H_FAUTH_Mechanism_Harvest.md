# Round 38C-P2-02H — F-AUTH Mechanism Harvest (exploratory)

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Pass 2 — F-AUTH (Authority Composition & Distribution) — **HARVEST, not the formal document**
**Governed by:** P2-00 (frozen) + P2-17. **Discover first, document later.**
**Status:** EXPLORATORY HARVEST — rough; clustering precedes the formal Mechanism Design Space Map. **No mechanisms selected. No DDD (gated).**
**Date:** 2026-06-25

---

## Scope

F-AUTH capabilities: GC-S1-05 (Ratification Breadth), GC-S2-01 (Appointment Distribution), GC-S2-02 (Cohort-Limiting), GC-S2-04 (Nomination Integrity), GC-S3-01 (Jurisdiction Definition), GC-S3-03 (Competence Determination — *open S-3 status*), GC-S5-01 (Finality Declaration).

Per P2-17, the harvest runs **internal sketch → literature expansion → cluster** so literature does not anchor the search.

---

## Phase 1 — Internal design-space sketch (BEFORE literature)

From first principles + prior GCD findings (origins to be confirmed in Phase 2):

staggered terms · long terms · non-renewable terms · count-based term limits · multiple distinct appointing actors · supermajority confirmation · cross-faction nomination requirement · regional seat distribution / quotas · eligibility & qualification criteria · independent nomination committee · removal only for defined cause · supermajority removal threshold · vacancy & succession rules · caretaker provisions · cohort cap (no cohort appoints a majority) · cooling-off periods · scheduled/delayed activation · sortition for some seats · ex-officio seats (by independent role) · conflict-of-interest screening at appointment · public appointment provenance (→ F-OBS) · rotation of the appointing authority

(~22 sketched before reading.)

---

## Phase 2 — Literature expansion (broaden the search space; origins tagged)

| Source domain | Mechanisms contributed | Origin |
|---------------|------------------------|--------|
| Germany Federal Constitutional Court | 2/3 split appointment (two chambers), 12-yr **non-renewable**, staggered | Constitutional practice |
| US FEC | no single party may hold a majority (balanced composition) | Election administration |
| Mexico INE | 2/3 legislative threshold + **citizen councilors** | Election administration |
| Central banks | fixed non-renewable terms, removal-for-cause only | Oversight institution |
| Auditors-general / Ombudsman | parliamentary (super)majority appointment + strong tenure | Oversight institution |
| Judicial appointment commissions (UK JAC, ZA JSC) | **mixed-composition independent nomination body** | Constitutional / Oversight |
| Corporate governance | nomination committees, independent directors, **classified/staggered boards** | Governance engineering |
| University governance | senate election, shared governance, tenure | Governance engineering |
| Professional self-regulatory bodies | peer election + **lay members** | Oversight institution |

**Finding:** literature largely **confirms** the internal sketch and adds mainly *mixed/lay/citizen composition* and the *independent nomination commission*. Few genuinely new ideas beyond the sketch — a good sign the sketch was strong (and that few mechanisms here are NRNA-original; most are well-evidenced ★4–5).

---

## Phase 3 — Cluster + dedup → Mechanism Classes → Groups

| Class | Groups | Example mechanisms |
|-------|--------|--------------------|
| **A — Temporal structuring** | staggering · term-length/non-renewal · term-limits · cooling-off · scheduled activation | staggered + long + non-renewable terms |
| **B — Source plurality** | multi-appointer · mixed composition · geographic distribution · random selection · rotation | 2/3 split; mixed lay/peer/ex-officio; regional quotas; sortition |
| **C — Threshold gating** *(↔ F-THR)* | supermajority confirm · cross-faction nomination · supermajority removal | 2/3 confirmation |
| **D — Candidate-pool integrity** | eligibility criteria · independent nomination commission · conflict screening | JAC-style nomination body |
| **E — Tenure protection** | removal-for-cause-only · fixed terms · high removal threshold | central-bank-style security |
| **F — Continuity** *(↔ F-PROC)* | succession rules · caretaker · anti-manufactured-vacancy | scheduled succession |

---

## Phase 4 — Candidate composite architectures (Rule 10 — not yet evaluated)

- **"Capture-resistant appointment"** = A(stagger + long + non-renewable) + B(multi-appointer / mixed composition) + C(supermajority confirm) + E(removal-for-cause). *(The appointment-diversity composite — one architecture, not five competitors.)*
- **"Minimal anti-cohort"** = A(stagger) + B(cohort cap) + E(fixed terms). *(lighter; for lower-risk bodies.)*

---

## Phase 5 — Early architectural flags (for the formal evaluation)

- **GRP / MA-concentration (the hard one):** Source plurality (Class B) is **functional, not structural** under single source — every appointer ultimately traces to MA (the GC-S2-01 residual). **Threshold gating (Class C) effectiveness depends on whether MA-aligned actors can themselves reach the threshold** (38C-14 noted MA's appointment functions). ⇒ Class C *alone* may be defeatable by concentration; it likely needs B + D to be load-bearing.
- **Recursion:** an *independent nomination commission* (D) is itself appointed — *who appoints the nominators?* → another GRP; couples **F-REV**. Resolve when F-REV is reached.
- **Cross-family couplings:** Class C ↔ **F-THR** (thresholds); Class F ↔ **F-PROC** (continuity); public provenance ↔ **F-OBS**.
- **Interaction tension (Rule 15):** sortition (B) ↔ eligibility criteria (D) — capture-resistance vs competence; supermajority confirm (C) + sole nominator → nominator capture (conflict).

---

## Phase 6 — Search-space exclusions (retained with reason — P2-17 §2)

| Excluded mechanism | In search space because | Excluded from design space because |
|--------------------|--------------------------|-------------------------------------|
| Appointment by external state / external guarantor | common in transitional/post-conflict states | **Option B single-source**; NRNA is not a state, no external legitimacy |
| Opaque automatic/algorithmic appointment | reduces human capture | **architectural FAIL** — hidden concentration, non-transparent (functional ✓, architectural ✗) |
| Lifetime tenure | maximal independence-in-office | conflicts with anti-capture turnover + accountability |
| Pure incumbent co-optation (self-selection of successors) | simple, continuity | re-creates appointer = appointee GRP **without** surrounding it |

---

## Open items → formal F-AUTH document

- **GC-S3-03 (Competence Determination)** mechanism space entangles with the **open S-3 status** (Authority-refinement vs meta) — handle explicitly; do not resolve S-3 here.
- The **nomination-commission recursion** (D) → couples F-REV (last family).
- **Class C ↔ F-THR** threshold consolidation (shared mechanism?).
- Evidence strength vs confidence (P2-17 §3) to be scored per mechanism in the formal doc.

---

## Methodological observations (reusable beyond this document)

**Observation P2-02H-01 (scoped — anti-anchoring, one family):** *Within the F-AUTH family*, the independently-derived (sketch-first) search space **substantially overlapped** mechanisms documented in the literature. This is consistent with — but does **not** prove — the claim that capability-driven sketch-before-literature produces a search space aligned with comparative practice. **Whether this generalizes remains to be tested by the remaining families** (tracked as M-06 in P2-18). This is *one family's convergence*, **not** "methodology validated."

**Observation P2-02H-02 (emergent capture resistance — the real finding):** No single mechanism delivers capture resistance. **Capture resistance is an *emergent property* of multiple interacting mechanism *dimensions* (source / temporal / qualification / approval / protection), not a property of any individual mechanism.** Therefore **the unit of design is the *composite governance architecture* (a selection across dimensions), not the mechanism.** This extends the program's arc: capabilities (EGCP) → *mechanisms as architectural compositions*.

**Adopted downstream:** the formal F-AUTH document and the interaction matrix will reorganize the 6 mixed "classes" into **orthogonal dimensions (D1–D5)** so each mechanism becomes *coordinates*, not a bucket (see `P2-02I`).

---

## Status

**This is the harvest, not the formal Mechanism Design Space Map.** Discovery is done; the formal F-AUTH Pass-2 document (P2-02) will: score each mechanism on the full evaluation template (functional + architectural + evidence/confidence + constraint map), build the interaction matrix, evaluate the composites, and produce recommendation(s) or an explicit *no-dominant* per class — retaining all alternatives and the Phase-6 exclusions.

---

*Round 38C-P2-02H — F-AUTH Mechanism Harvest (exploratory) — COMPLETE*
*Internal sketch (22) → literature mostly confirmed it → 6 classes / groups → 2 composites → exclusions retained*
*Hard flag: source plurality is functional-not-structural under single source (GC-S2-01 residual)*
*Next: formal F-AUTH Pass-2 document (P2-02). Strategic DDD GATED.*
