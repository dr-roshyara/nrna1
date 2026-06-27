# Round 40-06 — Governance Semantic Inventory

**Program:** NRNA DDD Trustworthiness Research Program
**Type:** Semantic **Inventory** (NOT an Ontology) · **Under MB-39.1 (frozen)**
**Status:** 🗂️ LIVING INVENTORY — records *observed* semantic conflicts **with provenance**; assigns **no definitions.** Updated by F-REV.
**Date:** 2026-06-25

> **Inventory vs Ontology (the whole point).** An **inventory** says *"we observed these meanings; status: unresolved."* An **ontology** says *"this is the meaning."* Governance discovery is **incomplete** (F-REV outstanding; taxonomy unsettled), so the latter is premature — a Ubiquitous Language is *discovered*, never fixed in advance (Evans). This document **catalogues with provenance**; it does not define.
> **Provenance rule:** every observed meaning records **where observed · evidence · confidence · alternative interpretations** (the methodology's own evidence model, reused so governance and methodology artifacts do not drift). **No entry contains a chosen definition.** Resolution deferred to post-F-REV (`Governance Ontology v1.0`).

---

## SI-01 — "Independence" 🔴 (Critical — GAR-I1)

```
Term: Independence
Observed meanings (with provenance):
  Meaning A — Institutional / source independence
     Observed in: F-AUTH (appointment design; insulated appointment, S-3)
     Evidence: P2-02H-02 / F-AUTH texts        Confidence: Medium (1 family, no contradiction)
     Alternatives: may be a facet of one concept, not separate
  Meaning B — Operational / functional independence
     Observed in: F-THR (independent vantage to detect/act)         Round40-02/03
     Evidence: F-THR domain pass                Confidence: Medium
     Alternatives: may reduce to "real independence" shared with A
  Meaning C — Decisional independence
     Observed in: F-REV [PREDICTED — not yet discovered]
     Evidence: anticipated only                 Confidence: LOW (predicted)
     Alternatives: may collapse into B once F-REV runs
Conflict: one word, three mechanisms, three failure modes, three constitutional grounds.
Status: ✅ RESOLVED (post-F-REV) → Governance Ontology v1.0 §3: FOUR concepts —
  Decisional ⟹ Operational ⟹ Institutional (dependency chain) + orthogonal Perceived.
Closed by: Round41-01 (F-REV) + Round43-01 (Ontology v1.0).
```

## SI-02 — "Legitimacy" (GAR-I4)

```
Term: Legitimacy
Observed use: emergent apex property in F-THR + cross-family synthesis ("emerges from the closed loop").
   Observed in: Round40-02/03/04         Evidence: synthesis-level     Confidence: Low (undefined)
Conflict: keystone outcome everywhere, never defined — procedural? sociological? constitutional?
Alternative interpretations: procedural (rules followed) / sociological (members accept) /
   constitutional (mandate valid) — undetermined.
Literature candidates (informs only, never dictates — ADR-M-011): Weber/Beetham legitimacy
   families; input/output/throughput legitimacy. NOT adopted.
Status: ✅ RESOLVED (post-F-REV) → Governance Ontology v1.0 §4: EMERGENT composite
  {consent, independence×4, finality, transparency, correctability}; continuously renewed;
  asymmetric; partially recoverable. (Component "consent" decomposition still open — O-REV-Q1.)
Closed by: Round41-01 (F-REV) + Round43-01 (Ontology v1.0).
```

## SI-03 — "Oversight" / "Authority" / "Adjudication" (GAR-I2)

```
Terms: Oversight, Authority, Adjudication
Observed in: F-AUTH (authority to appoint) · F-THR (oversight to detect) · F-REV [PRED] (adjudication to rule)
Evidence: cross-family use        Confidence: Medium that they differ; Low on the boundaries
Conflict: used loosely; boundaries blurred.
Alternative interpretations: 3 distinct roles vs one "Oversight & Adjudication" domain.
Status: UNRESOLVED.   Closes when: F-REV defines adjudication; then separate from oversight/authority.
```

## SI-04 — "Evidence" (GAR-I3)

```
Term: Evidence
Observed meanings:
  F-PROC — audit trail / process record (produced)     Confidence: High (concrete artifact)
  F-THR  — detection evidence (consumed to verify capture)
  F-REV  — adjudication evidence [PREDICTED]
Apparent status: CONSISTENT (all = "reviewable record") but UNCONFIRMED across families.
Alternatives: adjudication evidence may have admissibility rules the others lack.
Status: PROVISIONALLY CONSISTENT — flag, do not freeze.   Closes when: F-REV confirms.
```

## SI-05 — "Independence: property or domain?" (GAR-C3)

```
Question form (not a term conflict): is Independence a candidate governance *domain*
   or a *property of* the Oversight & Adjudication domain?
Linked to: SI-01; RQ-SEM-01.   Status: UNRESOLVED.   Closes when: SI-01 resolves (post-F-REV).
```

---

## Inventory status summary

| Entry | Term | Status | Provenance breadth | Closes with |
|-------|------|--------|--------------------|-------------|
| SI-01 🔴 | Independence | ✅ RESOLVED → Ontology v1.0 §3 (4 concepts) | F-AUTH + F-THR + F-REV | F-REV (done) |
| SI-02 | Legitimacy | ✅ RESOLVED → Ontology v1.0 §4 (emergent composite) | F-REV + synthesis | F-REV (done) |
| SI-03 | Oversight/Authority/Adjudication | UNRESOLVED | 3 families | F-REV |
| SI-04 | Evidence | PROVISIONALLY CONSISTENT | F-PROC/F-THR (+F-REV pred) | F-REV |
| SI-05 | Independence: property vs domain | UNRESOLVED | linked SI-01 | post-F-REV |

**Nothing here is a definition.** Governance Ontology v1.0 is built **after** F-REV.

---

*Round 40-06 — Governance Semantic Inventory — ISSUED (living; conflicts + provenance recorded, no definitions).*
*5 entries; SI-01 "Independence" Critical. Provenance per meaning (where/evidence/confidence/alternatives). Ontology deferred to post-F-REV. MB-39.1 FROZEN · DDD GATED.*
