# Round 39-00 — Methodology Stabilization (Charter)

**Program:** NRNA DDD Trustworthiness Research Program
**Workstream:** Round 39 — **Methodology Stabilization** (NOT Pass 2; NOT DDD)
**Status:** OPEN
**Date:** 2026-06-25

> **Why now.** Through F-PROC the program discovered that *the methodology itself evolves* (M-01 narrowed; M-07 introduced). Before discovering more governance families, the methodology must be **frozen and consolidated** into a single versioned reference, so that **F-THR and F-REV are executed under a stable protocol** rather than a moving one. That makes their evidence substantially stronger.

## Objective

Produce a complete, internally consistent, **versioned** specification of the research methodology incorporating everything learned through F-PROC — then run the remaining hostile replications under it unchanged.

## Deliverables

| # | Deliverable | Status |
|---|-------------|--------|
| **D1** | **Methodology Specification — v0.9 Controlled Working Specification** (consolidates P2-00/17/18/19/SYN-01; +SHALL/SHOULD/MAY, ontology/glossary split, invariants INV-1..5, extension points) | **DELIVERED** (`Round39-01`); → v1.0 only after F-REV |
| D2 | Methodology State Machine (transitions: entry/exit/artifacts/allowed/forbidden) | folded into D1 §5; standalone optional |
| D3 | Methodology Ontology (every concept defined) | folded into D1 §3 |
| D4 | Methodology Traceability Matrix (where defined / who uses / depends-on / validated / falsifiable) | TODO |
| D5 | Methodology Consistency Audit (contradictions / duplicates / drift across all docs) | TODO |
| D6 | Methodology Decision Log (expand ADR-M: why / evidence / alternative / consequences / dependencies) | TODO (seed in P2-19) |
| D7 | Research Object Separation (3 models: A Governance · B Methodology · C Software Translation) | TODO |
| D8 | Literature Integration Matrix (every paper → exactly one research object) | TODO |
| **DDD-R** | **DDD Readiness Assessment** (prepare, do NOT design): which governance concepts are stable enough to become candidate domain concepts; which are provisional (M-07, P-PROFILE) and must NOT shape contexts; which terms are stable ubiquitous-language candidates; what remains blocked pending F-THR/F-REV | TODO |

## Priority sequence

```
P1 Freeze & consolidate methodology  → D1 (done) → D3/D5/D6/D4
P2 Literature integration             → D8 + D7
P3 Re-lock prediction register
P4 Run F-THR     (under Spec v0.9 (Controlled Working Specification), frozen)
P5 Run F-REV
P6 Methodology synthesis (P2-SYN-FINAL)
P7 Handbook Volume 6 (authoritative narrative of the *stable* methodology)
P8 Strategic DDD  (gate opens)
```

## Standing rules for Round 39

- **Spec v0.9 (Controlled Working Specification) is frozen for F-THR and F-REV.** Any change requires an ADR-M and a version bump (v1.1); no silent edits.
- **DDD gate stays closed.** DDD-R *prepares* (identifies stable vs provisional concepts); it does **not** design contexts/aggregates.
- **Handbook Volume 6 waits** until after F-REV — it must narrate the *finalized* methodology, not an evolving one.
- **Three research objects must not be conflated** (D7): Governance / Methodology / Software Translation.

---

*Round 39-00 — Methodology Stabilization Charter — OPEN*
*D1 (Methodology Specification — v0.9 Controlled Working Specification) delivered; D4–D8 + DDD-R pending. F-THR runs only under frozen Spec v0.9; promote to v1.0 only after F-REV.*
