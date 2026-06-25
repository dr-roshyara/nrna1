# Round 38C-P2-17 — Evidence Provenance Protocol

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Pass 2 — protocol companion to P2-00 (binding)
**Status:** 🔒 PROTOCOL — binding on all Pass 2 mechanism work
**Date:** 2026-06-25

---

## Purpose

P2-00 governs *how* mechanisms are discovered and evaluated. P2-17 adds the **provenance, scope, and confidence discipline** that makes the mechanism archive audit-proof years later. Three additions and one ordering rule.

---

## 1. Mechanism provenance (origin taxonomy)

Every mechanism records exactly one **primary origin** (and any secondary):

| Origin | Meaning |
|--------|---------|
| **Constitutional practice** | existing constitutional systems |
| **Election administration** | election-management practice |
| **Oversight institution** | regulators, ombudsmen, audit institutions |
| **Governance engineering** | cross-domain governance frameworks |
| **Safety engineering** | aviation / nuclear / medical assurance |
| **Distributed-systems analogy** | software-derived analogy (use sparingly — governance pattern must be genuinely analogous) |
| **NRNA original** | newly discovered in this research |
| **Derived combination** | a composite of the above (Rule 10) |

*Why:* the question "which mechanisms are genuinely original contributions?" must be answerable instantly. NRNA-original and derived-combination entries are the research's novel output.

---

## 2. Search Space vs Design Space (distinct — record both)

| | Definition |
|--|-----------|
| **Search Space** | everything the literature/imagination contains for a capability |
| **Design Space** | the subset that **survives** the constitutional filters: constitutional properties (S-1..S-5) · Option B · architectural invariants (esp. **anonymity**) · GRP handling · capability-family interactions |

A mechanism may be in the **search space** but **excluded before entering the design space**. **Excluded mechanisms are recorded with their exclusion reason** (not silently dropped). This yields the audit-proof answer to *"why wasn't mechanism X considered?"* → *"X was in the search space but excluded from the design space because it violated [invariant]"* (e.g. vote-level monitoring excluded by anonymity — see P2-01).

---

## 3. Evidence strength vs Confidence (two independent dimensions)

These are **not** the same axis:

- **Evidence strength** (★1–5): how well-supported by real institutions/literature.
- **Confidence**: how well it fits *this* architecture (constitutional invariants, Option B, GRP, anonymity, family interactions).

They can diverge:

| Case | Evidence | Confidence | Example |
|------|----------|-----------|---------|
| Well-used but mis-fit | HIGH | LOW | a mechanism common in mature states that conflicts with anonymity or assumes external courts |
| Novel but well-fitted | LOW | HIGH | an NRNA-original mechanism with little precedent but clean architectural fit |

Both dimensions are recorded per mechanism. A high-evidence / low-confidence mechanism is **not** preferred over a low-evidence / high-confidence one merely because the literature likes it (this is Rule 14 made measurable).

---

## 4. Literature comes AFTER the internal design-space sketch (anti-anchoring)

Ordering rule (mirrors the constitutional-phase discipline that kept literature from driving the architecture):

```
Capability
  → INTERNAL design-space sketch (from first principles + our architecture)   ← FIRST
  → Literature expansion (broaden the search space)
  → Literature comparison (map to the sketch; add/contrast)
  → Design space (filter the search space through the constitutional filters)
  → Evaluation (P2-00)
```

**Sketch before reading.** Reading first anchors the search on whatever the literature happens to emphasize; sketching first lets the architecture pose the questions (Rule 14 direction). This is also why F-AUTH begins with a *harvest* (discover first, document later) rather than a polished document.

---

## Deliverable

```
P2-17 adds to every Pass-2 mechanism record:
  Provenance:        one of 8 origins (incl. NRNA original / derived)
  Search vs Design:  excluded mechanisms retained WITH exclusion reason
  Two dimensions:    evidence strength (★) AND confidence (architectural fit) — recorded separately
  Ordering:          internal sketch BEFORE literature (anti-anchoring)
Binding alongside P2-00. Strategic DDD GATED.
```

---

*Round 38C-P2-17 — Evidence Provenance Protocol — ISSUED (binding, companion to P2-00)*
*Provenance taxonomy; Search≠Design space (exclusions retained); Evidence≠Confidence; sketch-before-literature*
*Next: F-AUTH harvest (internal sketch → literature). Strategic DDD GATED.*
