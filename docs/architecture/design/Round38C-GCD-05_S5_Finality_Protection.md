# Round 38C-GCD-05 — Finality Protection Discovery (S-5)

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Governance Capability Discovery (GCD-05) — final safeguard
**Authority:** 38C-15 ARB Ruling, Part F.1
**Status:** DISCOVERY — **Pass 1 (capabilities only)** + **EGCP-01 falsification test (decisive)**
**Date:** 2026-06-25

---

## Discipline boundary (binding)

Pass-1 capabilities only; no mechanisms; Pass-1 Rule; Option A/B and the ruling not reopened. Run as **falsification**, not confirmation. **EGCP-01 is FROZEN** — it will not be edited to fit S-5.

**⚠ PROTECTED-QUESTION CAUTION.** GCD-05 discovers *what capabilities S-5 requires.* It does **NOT** ask *when an election may be invalidated* — that is **OQ-38A05-02 (PROTECTED)**, reserved for the Constitutional Interpretation Chamber. Any S-5 capability touching it may only **route and preserve** the question, never decide it.

---

## 1. The constitutional property (S-5)

Per 38C-15 C.4: *Rulings within jurisdiction shall be constitutionally final; override shall require amendment at the S-1 threshold, not a simple majority. Retroactive invalidation upon discovery of fundamental defects (OQ-38A05-02) remains reserved for the Constitutional Interpretation Chamber.*

**Why S-5 is different.** S-1..S-4 protect *governance processes*. S-5 protects the **stability of constitutional reality** — it answers *"when is governance allowed to stop?"* It is the only **inherently balancing** safeguard: **legal certainty (finality)** vs **substantive correctness (validity)**. Its telos is **equilibrium**, not action.

---

## 2. Pass 1 — S-5 Governance Capability Catalog (purpose only)

### GC-S5-01 — Finality Declaration Capability
**Purpose:** Establish the terminal state in which a governance decision / election result becomes constitutionally final.

### GC-S5-02 — Finality Window Capability
**Purpose:** Define a bounded, published period (challenge window) that must close before finality attaches; finality cannot attach while a window is open. *(cf. ADR6-CONSTRAINT-01.)*

### GC-S5-03 — Override Threshold Capability
**Purpose:** Permit override / reopening of a final decision only at the **S-1 (highest) threshold**, never by simple majority. **↔ couples GC-S1-02.**

### GC-S5-04 — Finality Observability Capability
**Purpose:** Make finality status, window state, and override attempts visible to constitutional actors.

### GC-S5-05 — Protected-Question Routing Capability
**Purpose:** Route a post-finality discovery of a fundamental defect to the Constitutional Interpretation Chamber. **This capability *routes and preserves* the question (OQ-38A05-02); it does NOT decide invalidation.** (The PROTECTED caution, made structural.)

### GC-S5-06 — Resolution Capability
**Purpose:** Bring a finality challenge to a constitutionally recognized resolution, whose outcome is **either Confirmation (finality upheld — the dominant case) or Correction (override granted — rare, high-threshold).** *(See falsifier 2.)*

---

## 3. EGCP-01 Evaluation Matrix (S-5)

| Criterion | Result | Note |
|-----------|--------|------|
| Authority present? | ✔ | GC-S5-01 (who may declare finality) |
| Exercise present? | ✔ | GC-S5-01/02 (decision becoming final) |
| Observation present? | ✔ | GC-S5-04 |
| Challenge present? | ✔ | override attempt / defect claim → S-4 + GC-S5-05 |
| Correction present? | ✔ (reframed) | GC-S5-06 — but as **Resolution**, see below |
| **Resolution outcome** | **BOTH** | **Confirmation (dominant)** *and* Correction (rare) — *new criterion* |
| **Additional stage (Appeal) required?** | **NO** | S-5 actively *resists* a general Appeal stage (finality = termination) |
| GRP present? | ✔ | finality self-reference (when is the finality *declaration itself* final; who validates an override) |
| Equilibrium vs process? | telos = equilibrium | still instantiates the process lifecycle — not a structural break |
| Cross-safeguard reuse? | ✔ | Challenge → S-4; Override ↔ S-1; ↔ S-3 |
| **EGCP status** | **FITS — with a candidate terminal-stage REFINEMENT** | Correction → Resolution{Confirmation\|Correction} |

---

## 4. The three falsifiers (tested)

**Falsifier 1 — Does S-5 require an extra stage (Appeal)?  → NO.**
S-5's entire purpose is to *limit* appeals: finality means the challenge resolution is **terminal**. Override requires the S-1 threshold; the post-finality defect route (GC-S5-05) is a **narrow PROTECTED exception** to CIC, not a general Appeal stage. **S-5 strengthens EGCP on the Appeal axis** — the very safeguard most likely to want Appeal instead argues against it.

**Falsifier 2 — Is "Correction" actually "Resolution" with multiple outcomes?  → YES (candidate refinement).**
S-5 makes vivid what S-1..S-4 left implicit: most finality challenges end in **Confirmation** (the decision stands), not Correction. The terminal stage is better understood as **Resolution → {Confirmation, Correction}**. This is a **refinement** of an existing stage (widening "Correction"), **not** an additional stage. Per the frozen-lens discipline, it is **recorded, not applied** — EGCP-01 is not edited. It is the strongest candidate refinement to carry into synthesis.

**Falsifier 3 — Does S-5 govern equilibrium rather than process?**
S-5's *telos* is equilibrium (a resting state), but it still **instantiates the process lifecycle** (declare → observe → challenge → resolve). Equilibrium is what the lifecycle *protects*, not a replacement for it. **Not a structural break** — though it is the clearest case that the lifecycle can serve a *state* rather than an *action*.

---

## 5. Disposition (per frozen-lens discipline)

- **S-5 FITS the EGCP lifecycle and requires NO additional stage** (Appeal rejected).
- It surfaces **one candidate refinement** that applies retroactively to all five safeguards: **the terminal stage is Resolution{Confirmation|Correction}, not bare Correction.**
- EGCP-01 is **NOT edited** (frozen). The refinement is recorded for the synthesis round.
- **EGCP-01 graduation remains UNDETERMINED** — it is now ready to be decided in the synthesis round, with the full evidence: S-1/S-2/S-4 fit; S-3 PARTIAL/STRESSED (Authority-refinement or meta-level); S-5 fits-with-refinement.

---

## 6. Realizability assessment (capability level, forward)

**Realizability: CONFIRMED.** The six capabilities appear to constitute a **complete candidate capability set** for realizing S-5 within Option B — necessity established; sufficiency not. Forward design constraint: **GC-S5-01/03** hold the S-5 GRP (finality self-reference). **GC-S5-05 must remain a routing/preservation capability only** — it must never be designed into a decision on OQ-38A05-02.

---

## 7. Open questions (routed to the synthesis round — NOT answered here)

- **OQ-GCD05-01 (EGCP-critical):** Should the terminal lifecycle stage be **Resolution{Confirmation|Correction}** rather than **Correction**? (A refinement, not a new stage — synthesis decides, since EGCP is frozen.)
- **OQ-GCD05-02 (PROTECTED-guard):** Confirm GC-S5-05 only *routes/preserves* OQ-38A05-02 and cannot be elaborated into deciding invalidation.
- **OQ-GCD05-03 (cross-safeguard):** GC-S5-03 Override Threshold ↔ GC-S1-02 Tiered Threshold — one shared capability or two?

---

## 8. Deliverable

```
S-5 Governance Capability Catalog (Pass 1 — capabilities only)
  GC-S5-01  Finality Declaration
  GC-S5-02  Finality Window
  GC-S5-03  Override Threshold            (↔ GC-S1-02)
  GC-S5-04  Finality Observability
  GC-S5-05  Protected-Question Routing    [routes OQ-38A05-02 to CIC; does NOT decide it]
  GC-S5-06  Resolution                    [outcome: Confirmation | Correction]

EGCP-01 test result: FITS — no extra stage (Appeal rejected).
                     Candidate refinement: terminal stage = Resolution{Confirmation|Correction}.
                     EGCP-01 NOT edited (frozen). Refinement recorded for synthesis.
Status: CANDIDATE CAPABILITIES — not mechanism-explored, not designed.
```

---

*Round 38C-GCD-05 — Finality Protection (S-5) — PASS 1 COMPLETE; EGCP-01 test → FITS (with terminal-stage refinement)*
*Falsifiers: Appeal stage NOT required; Correction→Resolution candidate refinement; equilibrium is telos, not structural break*
*All five safeguards now discovered. NEXT: dedicated EGCP Synthesis round (NOT Pass 2 yet).*
*Strategic DDD remains GATED*
