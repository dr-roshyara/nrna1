# S1-F001 · Authority provenance is strong; authority *temporal semantics* is absent

**Finding class:** MEASURED CURRENT-STATE EVIDENCE (not hypothesis)
**Status:** OPEN · research evidence only · **not adjudicated, not architecture**
**Lenses:** Temporal · Identity · Evidence · Justification · Agency

---

## Source and provenance

| | |
|---|---|
| **Source document** | `docs/knowledgeos/brainstorming/20260821-2033-what-eks-is-today.md` §1.3 |
| **Provenance** | `P1` ORIGINAL_PROJECT — current-architecture reconstruction of the project's own system |
| **Phase** | 1 |
| **Date** | 2026-08-21 |
| **Evidence class** | ⟦RESEARCH FACT⟧ — **measured over 20 observed grants**, not proposed |

⟦I⟧ Note on weight: by the project's own evidence hierarchy (`20260801-1231` §3) this is
**implementation/persistence-derived** evidence — priority 1–3 — not priority-7 brainstorming. It is
the strongest evidence class encountered in the corpus so far.

---

## The finding

⟦C⟧ *"The current authority record is strong in provenance but weak in temporal semantics."*

Measured against 20 observed grants:

| Property | Evidence |
|---|---|
| Grants with human-act reference | **20/20** |
| `registeredBy = governance` | **20/20** |
| **Grants with validity information** | **0/20** |
| **Grants with delegation information** | **0/20** |
| **Grants with ownership information** | **0/20** |
| Grants with immutable commit addressing | 13/20 |
| Grants using descriptive artifact references | 7/20 |

⟦C⟧ Consequence, in the document's own words: the system *"can establish that authority was registered
against a human act, but cannot reliably answer:"*

> ⟦C⟧ **"Was this authority valid at a particular historical point in time?"**

⟦C⟧ And explicitly: *"That is a current-state limitation, not a future-architecture opinion."*

---

## Why it matters

⟦I⟧ The corpus's temporal question has, until this point, been **philosophical** — knowledge-at-a-time,
projection, evolving Knowledge Space (all Phase 2, all hypothesis). This document supplies a
**measured** temporal gap in the project's own system, three days before the Phase 2 material begins.

⟦I⟧ It reframes the temporal question from *"is knowledge inherently temporal?"* to a narrower,
answerable one: **"can the system reconstruct the validity of an authority at a past instant?"** —
currently, no, at 0/20.

⟦I⟧ It also sharpens KCON-017 (`Record existence ≠ Authority establishment`, `20260821-2032`, the same
day): registration is recorded; **validity over time is not**. The two findings are complementary and
independently arrived at.

---

## Possible Knowledge / KnowledgeOS / Kernel relevance

**⚠ Research relevance only. No Kernel element is proposed, and nothing here is adjudicated.**

- **`W:C-18`** constitutional-version binding — a version binding is precisely a temporal validity
  claim; this is measured evidence that the mechanism currently lacks one.
- **`W:C-3`** who checks authority adequacy — adequacy at *which* time is undefined.
- **`W:C-15`** retraction/withdrawal representation — same family: what does the record say *later*.
- ⟦L⟧ v1.1 comparison only: INV-KOS-AUTHORITY-001 and Article 3 govern authority; ⟨Z-1⟩ makes states
  states *of an identified object*. **Classification: NOT ADDRESSED** — v1.1 does not speak to
  authority *validity intervals*.

---

## Status

**OPEN.** Recorded as measured evidence. Not a defect claim against the formal architecture (which is
a target model, not this baseline). Not a proposal. Requires no action from this session.

**Related findings:** `S1-F002` (vocabulary instability, same document) · ledger KCON-017, KCON-018.
