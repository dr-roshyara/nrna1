# EPIC-002 ARB Decision Request — Context Mapping Strategy

**Kind:** governance decision request — not an analysis artifact. **Authority:** generated to frame the decision; the decision is the ARB's alone.
**Standing declaration (per ARB, 2026-07-25):** the analysis phase of EPIC-002 Strategic DDD is **complete**. No further preparatory analysis is authorized or needed — the methodology has reached its natural stopping point, and this document deliberately adds no new findings. It applies one decision criterion to findings already on record and puts exactly two choices before the board.
**Inputs:** `EPIC-002_Canonical_Domain_Model_Decision.md`, `EPIC-002_Domain_Collaboration_Discovery.md`, `EPIC-002_Context_Mapping_Readiness_Assessment.md`, and the prior accepted EPIC-002 artifacts. No new sources, no implementation assumptions.
**Explicitly out of scope:** producing any Context Map; relationship patterns; APIs, events, aggregates, repositories, services; Tactical DDD.

---

## 1. Architecture Decision Readiness Assessment

Applying the three-question criterion to the uncertainties still on record — answered strictly from the existing findings, no new analysis:

| # | Question | Coupled custody + merger pair (CB-2/Alt ↔ CB-1/CB-4) | CB-3-Alt (authority-validity) |
|---|---|---|---|
| 1 | Does the remaining uncertainty materially change **boundary ownership**? | **YES** — the custody ruling changes what CB-2 *is* (custodial actor vs. self-verifying mechanism), and via the documented coupling reshapes the CB-1/CB-4 area (Scenario B fuses record-fixing with integrity-onset) | **NO** — presence or absence changes no other position |
| 2 | Does it materially change **business collaboration**? | **YES** — COL-3 and COL-4's content (what actually crosses) differs per scenario | **NO** — only a conditional bifurcation of COL-5, annotation-representable |
| 3 | Does it materially change **strategic relationships**? | **YES** — a fused vs. distinct CB-1/CB-4 topology yields structurally different neighbor sets | **NO** |

**Criterion outcome:** the coupled pair is the *only* uncertainty that answers YES — so if scenario maps are ever produced, they are produced **for that pair alone** (Scenarios A/B/C), never for CB-3-Alt, which is carried as an annotation in every case. No scenario explosion is possible under this criterion.

## 2. The Two Choices

### Choice 1 — Rule now → one canonical Context Map
- **When:** the ARB can confidently choose between custodial (A), self-verifying (B), and hybrid (C) — and, jointly, dispose of the CB-1/CB-4 merger — as a matter of architectural judgment.
- **What follows:** a single authoritative Context Map on the ruled decomposition, CB-3-Alt carried as an annotation.
- **What the board already has for this ruling:** the full consequence analysis of each option is on record — what each choice does to boundaries (readiness assessment §1), to collaborations (collaboration report §4 scenarios), and the fact that hybrid is what real deployments in the evidence base exhibit. Nothing further is coming: the question is architectural, not evidential.

### Choice 2 — Cannot rule yet → comparative scenario maps, then rule immediately
- **When:** only if the board genuinely cannot rule from the material on record.
- **What follows:** three explicitly comparative candidate maps (A, B, C — each one coherent reality; never one map containing competing realities), containing candidate boundaries, business collaborations, information dependencies, and the CB-3-Alt annotation — **no relationship patterns**.
- **Binding sunset clause:** the scenario maps are decision-support artifacts. The ARB rules **immediately after** reviewing them; exactly one canonical Context Map is then produced and the scenario maps are archived as history. They must not persist as parallel semi-authoritative representations.
- **Known risk (on record):** comparative maps invite comparing maps instead of deciding architecture — the immediate-ruling requirement exists precisely to prevent that drift.

## 3. Recommendation

**Attempt Choice 1 first.** The three-question criterion confirms the open uncertainty is real — but it is a single, well-bounded, fully-consequence-analyzed architectural judgment, not an information gap. Every input a scenario map would visualize is already stated in prose in the committed record. Scenario maps add presentation value, not information value; they cost effort and carry the compare-maps-instead-of-deciding risk. **Choice 2 is the legitimate fallback, not the default** — to be invoked only if, upon actually confronting the ruling, the board finds it cannot choose without seeing the alternatives drawn.

## 4. ARB Decision Request

The ARB is asked to answer one question:

> **Can the ARB confidently rule now on the coupled custody + merger pair — choosing among Scenario A (custodial), B (self-verifying), and C (hybrid, per-stream), and jointly disposing of the CB-1/CB-4 merger?**

- **If YES:** record the ruling; authorize **one canonical Context Map** on the ruled decomposition.
- **If NO:** authorize **comparative scenario maps (A/B/C) under the binding sunset clause**, with the ruling required immediately after their review.

Either answer also implicitly disposes of CB-3-Alt's handling: it is carried as an annotation on whichever map(s) are authorized — already established as non-blocking, requiring no separate ruling now.

---

**Stop condition:** this request frames the decision; it does not make it and does not map anything. **STOP.** No Context Map of any kind is authorized until the ARB answers the question above.

---
*Charter: `EPIC-002_Problem_Statement.md` · Inputs: the EPIC-002 artifacts listed in the header · No new sources consulted; no new findings introduced.*
