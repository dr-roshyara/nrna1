# Round 38C-P2-02I — F-AUTH Mechanism Dimensions & Interaction Matrix

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Pass 2 — F-AUTH — interaction artifact (between harvest P2-02H and formal P2-02)
**Governed by:** P2-00 (frozen) + P2-17. **No selection, no DDD (gated).**
**Status:** ANALYSIS — reorganizes harvested mechanisms onto orthogonal dimensions; builds the interaction matrix.
**Date:** 2026-06-25

---

## Why this artifact

The harvest (P2-02H) clustered mechanisms into 6 classes — but those classes **mixed dimension-types** (who / when / quality / constraint / protection). This artifact fixes that by re-expressing mechanisms as **coordinates across orthogonal dimensions**, then mapping how mechanisms **interact**. It complements SYN-03's capability dependency graph — *at the mechanism level*.

---

## Part 1 — Orthogonal mechanism dimensions (D1–D5)

| Dim | Question | Values |
|-----|----------|--------|
| **D1 — Authority Source** | *who appoints?* | single · multiple · mixed-composition (lay/peer/ex-officio/citizen) · rotating · sortition |
| **D2 — Temporal** | *when / how long?* | fixed · staggered · long · renewable / **non-renewable** · cooling-off · scheduled-activation |
| **D3 — Qualification** | *which candidates?* | eligibility criteria · independent nomination · conflict screening |
| **D4 — Approval** | *how much agreement?* | simple · supermajority · cross-faction · regional ratification |
| **D5 — Protection** | *security after appointment?* | removal-for-cause · tenure security · succession / continuity |

Every mechanism is a **coordinate vector** in this space (not a point, not a bucket): it may carry weight on several dimensions — e.g. *independent nomination committee* = D3 primary / D1 secondary; *regional* = D1 (seats) and/or D4 (ratification). Treating mechanisms as **vectors** (not points) lets mechanism **similarity** be reasoned about later.

### Orthogonality validation (these are *candidate* dimensions until they pass)

| Test | F-AUTH result |
|------|---------------|
| **Independence** — one dimension varies while another is held constant? | ✓ (term length D2 varies independently of source D1) |
| **Completeness** — every mechanism maps to ≥1 dimension? | ✓ (all 14 mapped) |
| **Non-redundancy** — no two dimensions express the same concern? | ✓ mostly — *watch:* D1 source-plurality and D4 approval both touch *power distribution*; kept distinct (who vs how-much-agreement) |
| **Minimality** — removing a dimension loses expressive power? | ✓ (each needed for some mechanism) |

**Status: validated *for F-AUTH*; candidate-universal.** Other families may need different/additional dimensions (P2-18 prediction **P-D6 Accountability**). Frozen for F-AUTH use; **re-validated per family**.

---

## Part 2 — Harvested mechanisms as coordinates

| Mechanism | D1 | D2 | D3 | D4 | D5 |
|-----------|:--:|:--:|:--:|:--:|:--:|
| Staggered terms | | ✓ | | | |
| Long, non-renewable terms | | ✓ | | | (✓) |
| Multiple appointers | ✓ | | | | |
| Mixed composition (lay/peer/ex-officio/citizen) | ✓ | | | | |
| Sortition | ✓ | | | | |
| Cohort cap | ✓ | | | | |
| Eligibility criteria | | | ✓ | | |
| Independent nomination commission | (✓) | | ✓ | | |
| Conflict screening | | | ✓ | | |
| Supermajority confirmation | | | | ✓ | |
| Cross-faction nomination | | | (✓) | ✓ | |
| Regional distribution / ratification | ✓ | | | (✓) | |
| Removal-for-cause | | | | | ✓ |
| Succession / caretaker | | | | | ✓ |

---

## Part 3 — Mechanism interaction matrix (Rule 15)

| Mechanism | Reinforces | Conflicts with | Requires |
|-----------|------------|----------------|----------|
| Staggered terms | long + non-renewable terms | single-cohort appointment | succession rules (D5) |
| Long non-renewable terms | independence; staggering | experience retention | succession rules |
| Multiple / mixed appointers | cohort cap; supermajority approval | sole appointer | a defined composition rule |
| Sortition | multi-appointer plurality | strict qualification (D3) | minimal eligibility screening |
| Independent nomination commission | qualification; reduces direct appointer control | — *(recursion: who appoints it?)* | its own appointment rules → **F-REV** |
| Supermajority confirmation (D4) | mixed/cross-faction nomination | single nominator | threshold governance → **F-THR** |
| Removal-for-cause (D5) | tenure security | at-will removal | defined grounds + adjudication → **F-REV** |

**Interaction edge types (for the eventual interaction graph).** The columns above are three of a richer typed set — group them:
- **Structural:** requires · depends · enables
- **Behavioral:** reinforces · counterbalances · amplifies
- **Constraint:** conflicts · excludes · dominates

These become **typed edges** when the mechanism interaction graph is formalized (see graph numbering below).

Two interaction findings stand out:
- **Synergy:** staggered + long + non-renewable + multi-appointer = strongly anti-cohort.
- **Tension:** sortition (capture-resistant) ↔ strict qualification (competence) — a genuine interaction, not a simple trade-off.
- **Recursion (cross-family):** the independent nomination commission and removal-for-cause both **require F-REV** (who appoints/judges the appointers) — the GRP resurfacing at the mechanism level.

---

## Part 4 — Composites as coordinate selections (with provenance)

A composite is *one value chosen per dimension* — this is the unit of design (Observation P2-02H-02).

| Composite | D1 | D2 | D3 | D4 | D5 | Provenance |
|-----------|----|----|----|----|----|------------|
| **Capture-resistant appointment** | multi / mixed | staggered + long + non-renewable | independent nomination | supermajority | removal-for-cause | **Constitutional (Germany FCC) + Oversight (central bank) + Governance-eng (JAC) + NRNA-derived combination** |
| **Minimal anti-cohort** | cohort cap | staggered + fixed | eligibility only | simple | fixed terms | **NRNA-derived combination** |

**Composite provenance matters:** even when every *individual* mechanism is well-evidenced (★4–5) and borrowed, **the *composition* is the novel contribution** (NRNA-derived). This separates innovation-in-mechanism from innovation-in-architecture.

---

## Part 5 — The emergent finding (recorded)

> **Capture resistance is an emergent property of the interaction across dimensions D1–D5, not a property of any single mechanism. The unit of design is the composite governance architecture.**

This is the F-AUTH analogue of GRP-01's depth: the program is now discovering **governance architecture** (how mechanisms compose, constrain, and reinforce) rather than isolated devices. If F-THR and F-REV reinforce this, the **composite-architecture principle** becomes a central program contribution. *(Recorded as an emerging observation — not yet elevated; confirmation needs the remaining families.)*

**Measurable definition of (architectural) emergence** (so "emergent" is falsifiable, not rhetorical): a composite has an emergent property **iff** — (1) **no individual** component mechanism possesses it, **AND** (2) **removing any one** component destroys it, **AND** (3) the interaction produces behaviour **impossible from the isolated** mechanisms. F-AUTH's capture resistance meets all three (no single mechanism resists capture; remove staggering or source-plurality and it collapses; the resistance is a joint effect).

**Governance graphs (numbered, for continuity):** **G1** = Capability Dependency Graph (SYN-03); **G2** = Mechanism Interaction Graph (this document); **G3** = Composite Graph (future — only if composites recur across families, P2-18 P-PATTERN). G1→G2→G3 are complementary models; **G3 is not created now.**

---

## Deliverable & next

```
F-AUTH dimensions + interaction matrix
  Dimensions:   D1 Source / D2 Temporal / D3 Qualification / D4 Approval / D5 Protection (orthogonal)
  Mechanisms:   re-expressed as coordinates (no buckets)
  Interactions: reinforces / conflicts / requires (mechanism-level dependency graph)
  Composites:   coordinate selections + provenance (composition = the novel layer)
  Finding:      capture resistance is EMERGENT; unit of design = composite
Cross-family requires surfaced: F-REV (nomination/removal recursion), F-THR (approval thresholds)
```

**Next: formal F-AUTH Pass-2 document (P2-02)** — scores each mechanism on the full template (functional + architectural + **evidence / confidence / transferability** + constraint map), evaluates the two composites, and gives a recommendation (or explicit *no-dominant*) **per dimension and per composite**, retaining alternatives + the P2-02H exclusions. Handles GC-S3-03 / S-3 status explicitly.

---

*Round 38C-P2-02I — F-AUTH Dimensions & Interaction Matrix — ISSUED*
*Orthogonal D1–D5 (mechanisms = coordinates); interaction matrix; composites = coordinate selections + provenance*
*Emergent finding: capture resistance is a composite property, not a single-mechanism property*
*Next: formal F-AUTH P2-02. Strategic DDD GATED.*
