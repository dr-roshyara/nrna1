# Round 38C-GCD-P2-00 — Mechanism Discovery Protocol

**Program:** NRNA DDD Trustworthiness Research Program
**Phase:** Governance Capability Discovery — **Pass 2 protocol** (rules of engagement; not itself mechanism discovery)
**Authority:** 38C-15 ARB Ruling, Part F.1
**Status:** 🔒 **PROTOCOL — binding on all Pass 2 work.** No mechanisms discovered here; this defines *how* they will be.
**Date:** 2026-06-25

---

## Purpose

Pass 2 changes the abstraction (from *what capability* to *how realized*) and will produce a large alternative space. This protocol fixes the discipline **before** the explosion, so mechanism work cannot silently mutate the validated capability layer (SYN-03) or the frozen vocabulary (GLOSSARY-01).

---

## The six rules (binding)

**Rule 1 — Mechanisms compete; capabilities do not.** A capability is a fixed requirement (what must be possible). Mechanisms are alternative realizations that compete to satisfy it. Disagreement belongs at the mechanism layer, never the capability layer.

**Rule 2 — Every mechanism must trace upward.** No mechanism is admissible unless it traces:
```
Mechanism → Capability → Family → Constitutional Property → Constitution
```
A mechanism that cannot be traced to a capability is out of scope and rejected.

**Rule 3 — Mechanisms may never redefine capabilities.** If a mechanism appears to require changing a capability's identity or purpose, that is a finding to escalate to a synthesis document (per the Architectural Stability Rule) — never a silent edit. The capability layer is frozen relative to Pass 2.

**Rule 4 — Mechanism comparison is mandatory.** No mechanism is accepted because it was discovered first. Each capability's mechanisms are evaluated comparatively against explicit criteria before any recommendation.

**Rule 5 — The discovery shape is fixed:**
```
one capability → several candidate mechanisms → comparative evaluation → recommended mechanism
```
Never `capability → first idea → accepted`.

**Rule 6 — Every rejected mechanism stays in the archive.** Rejected candidates and the reason for rejection are retained. The rejected set is itself a research asset (it records *why not*, which protects future decisions from re-litigation).

---

## Per-capability output format (Pass 2)

For each capability, Pass 2 produces:

| Field | Content |
|-------|---------|
| Capability | GC-Sx-xx + family |
| Candidate mechanisms | ≥2 genuinely different options |
| Evaluation criteria | constitutional fit, GRP-exposure, cross-safeguard impact, NRNA-feasibility |
| Comparison | each candidate against the criteria |
| Recommended | the mechanism(s) carried forward (with rationale) |
| Rejected (retained) | the rest + reason (Rule 6) |
| Traceability | the upward trace (Rule 2) |

**Still forbidden:** bounded contexts, aggregates, services, APIs, domain events, repositories, implementation. Mechanisms are governance mechanisms, not software. **Strategic DDD remains GATED.**

---

## Family ordering for Pass 2

```
F-OBS  (Observability)            ← first: highest reuse, lowest controversy
F-AUTH (Authority Comp. & Dist.)
F-PROC (Process Integrity)
F-THR  (Threshold & Entrenchment) ← cross-cutting; affects several others
F-REV  (Constitutional Review)    ← last: the hub; most entangled with GRP; needs prior families settled
```

**Hub rigor (from SYN-03 Part 4):** F-REV and the hub capabilities GC-S1-01, GC-S1-02 receive extra comparative rigor — a weak mechanism under a hub weakens many safeguards at once.

---

## Family identity update (explicit, per Architectural Stability Rule)

**F-ADJ is renamed F-REV (Constitutional Review), effective Pass 2.** `F-REV ≡ F-ADJ` (same family; "Challenge & Resolution" was the original label, "Constitutional Review" is canonical — challenge is the *trigger*, review is the *capability*). Prior committed documents (SYN-01/02/03) retain `F-ADJ`; this rename is recorded explicitly (not silent) and used going forward.

---

## Carried open items (do not block Pass 2)

- **S-3 status** (Authority-refinement vs meta/relationship level) — affects *organization* only; GC-S3-03's mechanism space is the one place this may surface (SYN-03 Part 5 watch item).
- **Shared-capability consolidation** — taken up **after** Pass 2, **before** Strategic DDD.
- **RQ-SYN02-01** (are families Option-B artifacts?) — recorded, not investigated in Pass 2.

---

## Deliverable

```
Mechanism Discovery Protocol (binding on Pass 2)
  6 rules: compete-not-capabilities / trace-upward / never-redefine /
           comparison-mandatory / fixed-shape / rejected-retained
  Per-capability output format fixed
  Family order: F-OBS → F-AUTH → F-PROC → F-THR → F-REV
  Hub rigor: F-REV, GC-S1-01, GC-S1-02
  Rename recorded: F-ADJ → F-REV (Constitutional Review)
  DDD remains GATED
```

---

*Round 38C-GCD-P2-00 — Mechanism Discovery Protocol — ISSUED (binding)*
*Pass 2 may begin under this protocol, starting F-OBS. Strategic DDD GATED.*
