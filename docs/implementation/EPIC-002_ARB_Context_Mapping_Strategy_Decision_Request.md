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

## 3. Recommendation — the Decision Gate (refined per ARB, 2026-07-25)

The process is a **loop, not a single attempt**:

```
Decision Gate

The ARB shall first determine whether the current architectural knowledge is
sufficient to make a governing decision.

If yes:

    Decide.

If no:

    Commission comparative scenario maps as a bounded decision-support
    activity.

Return immediately to the same decision gate.
```

The three-question criterion confirms the open uncertainty is real — but it is a single, well-bounded, fully-consequence-analyzed architectural judgment, not an information gap. Every input a scenario map would visualize is already stated in prose in the committed record. Scenario maps add presentation value, not information value; they cost effort and carry the compare-maps-instead-of-deciding risk. **Choice 2 is the legitimate, bounded support activity inside the loop — never an exit from it**: after the scenario maps are reviewed, the same decision gate is confronted again, immediately.

## 4. ARB Decision Request

The ARB is asked to answer one question:

> **Does the ARB have sufficient architectural evidence to make a responsible decision now on the coupled custody + merger pair — choosing among Scenario A (custodial), B (self-verifying), and C (hybrid, per-stream), and jointly disposing of the CB-1/CB-4 merger?**

*(Wording deliberate, per ARB refinement: "responsible," not "confident" — architecture decisions rarely reach certainty; boards decide under uncertainty. The question is whether the evidence suffices for a responsible judgment, not whether doubt has been eliminated.)*

- **If YES:** record the ruling; authorize **one canonical Context Map** on the ruled decomposition.
- **If NO:** commission **comparative scenario maps (A/B/C) under the binding sunset clause** as a bounded decision-support activity — then return immediately to this same decision gate.

Either answer also implicitly disposes of CB-3-Alt's handling: it is carried as an annotation on whichever map(s) are authorized — already established as non-blocking, requiring no separate ruling now.

---

## 5. RULING RECORDED (ARB, 2026-07-25 — explicit per-item decision, not inferred)

The Decision Gate was answered **YES** — the ARB ruled directly, without commissioning scenario maps:

1. **Custody question: Scenario C — Hybrid.** Both integrity mechanisms coexist, assigned per evidentiary stream: custodial integrity (CB-2) and self-verifying integrity (CB-2-Alt) are BOTH part of the canonical decomposition, each discharging the integrity obligation for the streams assigned to it (including, where warranted, both for one stream — the belt-and-suspenders pattern real deployments exhibit). **Wording deliberate (ARB refinement): the evidence does not prove Scenario C — Scenario C is the best-supported architectural synthesis under the agreed evaluation criteria.** The literature documents patterns and trade-offs; the choice is the board's architectural judgment combining those observations with domain understanding.

**ARB rationale (recorded):** *The Board determines that the current architectural evidence is sufficient to make a responsible decision. Scenario C (Hybrid) is adopted because it provides the best explanation of the evaluated evidence under the agreed evaluation criteria, accommodates the observed diversity of integrity mechanisms (STAR-Vote, Wombat, the ElectionGuard pilot), and avoids introducing unnecessary exceptions. The Board concludes that comparative scenario maps would improve visualization but are unlikely to change the architectural decision; therefore they are not commissioned.*
2. **CB-1/CB-4 merger: KEEP SEPARATE.** Collection and Contemporaneous Record-Fixing remain distinct positions; COL-2 remains a crossing collaboration. **ARB rationale (recorded verbatim in substance):** the primary DDD boundary criterion is *does this responsibility change for different reasons* — not co-occurrence. Collection changes when evidence sources/formats/acquisition policies change; Record-Fixing changes when recording requirements, timestamping rules, or legal/procedural recording requirements change — different reasons to evolve. Their ubiquitous language differs ("what evidence do we have?" vs. "what became the official record at that moment?"). The outputs-travel-together observation is an **integration criterion, not a domain criterion** — Customer/Credit-Assessment and Order/Payment also always travel together yet remain separate contexts. The act-time vs. review-time temporal split prevails. **Recorded reversal condition:** this ruling is reversed only if it is later concluded that Contemporaneous Record-Fixing has **no independent domain policy or decision-making beyond Collection** — i.e., it is merely a mechanical step within Collection. The evidence has not reached that threshold.
3. **CB-3-Alt:** per §4, carried as an annotation on CB-3 — no separate ruling required.

**Consequence, per §2 Choice 1:** one canonical Context Map on the ruled decomposition is now **authorized**. Scenario maps are not produced (the gate was passed without them). Relationship-pattern selection remains unauthorized until the canonical map exists and is ARB-accepted.

**Stop condition (updated):** the gate is answered; the canonical Context Map is the next artifact. Relationship patterns, Tactical DDD, and implementation remain not authorized.

---
*Charter: `EPIC-002_Problem_Statement.md` · Inputs: the EPIC-002 artifacts listed in the header · No new sources consulted; no new findings introduced.*
