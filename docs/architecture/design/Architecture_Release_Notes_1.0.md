# Architecture Release Notes 1.0

**Program:** NRNA DDD Trustworthiness Research Program · **Release:** Architecture Release 1.0 · **Date:** 2026-06-26
**Status:** 📝 RELEASE NOTES — "what changed / what's frozen / known limitations / roadmap." Companion to the `Architecture_Release_1.0` manifest. *(A release document, not methodology.)*

> **One-line:** Release 1.0 freezes the **certified knowledge + the strategic software boundaries** discovered by an evidence-driven methodology (EBSD). It marks the end of Strategic DDD Discovery (Phase II) and the start of the Strategic→Tactical Transition (Phase III).

## What this release contains
| Layer | Artifact | Version |
|-------|----------|---------|
| Knowledge | Certified Domain Knowledge Package | 1.0 |
| Vocabulary | Canonical Vocabulary Dictionary | 1.0 |
| Landscape | Strategic Domain Landscape | 1.0 |
| Boundaries | Boundary Decision Register | **1.1** |
| Methodology (frozen) | Methodology Constitution · Operating Protocol · ADQC v1.1 · EBSD · BC-Evaluation-Framework v1.1 · Dossier · Evaluation | 1.0 |

## What changed (vs "before the program")
- Governance knowledge is **discovered, certified, and frozen** — not improvised during coding.
- Software boundaries are **discovered from evidence** (certified semantics + real code), and **falsified** where the evidence didn't support them.
- **5 Confirmed bounded contexts:** Evidence · Voting · Appointment *(Operational)*; **Adjudication · Contestation** *(Greenfield — the unbuilt correction-loop Core)*.
- **Downgraded from BC** (evidence-driven): Replay → Application Capability · Authorization → Domain Service · Lifecycle → Supporting (derivation over Election) · Audit → Infrastructure.
- **Not contexts:** Results, Legitimacy → Read Models · Anonymity → Architectural Invariant · Trust-Anchor/Consent → External.
- **Key empirical finding:** **administrative finality ≠ constitutional finality.** The code has `Counting → ResultsPublished` (administrative workflow); the certified correction loop (Challenge → Adjudication → Binding Determination → Correction → Finality) is **genuinely greenfield**.

## Candidate research contributions (to be validated, not yet claimed)
- **A — Knowledge Certification Pipeline** (domain discovery → evidence → certification). *Methodological.*
- **B — EBSD** (Evidence-Based Strategic DDD: software boundaries from certified semantics + empirical code).
- **C — Evidence-Governed Boundary Decision Process** (the contribution is the *process* separating evidence/reasoning/decision; the BDR is its artifact).
- **D — Governance→Software Translation Pipeline** (Certified Knowledge → Ownership → Candidate BCs → Evidence → Boundary Decisions → Software — richer than the usual Domain→BC step).
*All `[verify]` via LIT-METHOD (architecture-recovery / empirical-architecture positioning), scheduled after Round 50.*

## Known limitations
- **Behavioral (L3) verification incomplete** — most code evidence is L2 (read), not runtime-tested.
- **Two BDR verdicts carry Evidence-Sufficiency caveats:** BDR-06 Replay (revisit post-implementation); BDR-05 now resolved (greenfield).
- **Scope-bounded** — validated for NRNA's class (voluntary, online, anonymous, no external sovereign); not national/other governance.
- **Multi-home code** (AI-1) — not yet consolidated; migration pending.
- **Single-analyst, pre-implementation** — no empirical/runtime/operational validation yet.
- **Methodology novelty** — candidate contributions A–D are *appears-novel*, pending LIT-METHOD.

## Roadmap (Release 1.0 → next)
```
Release 1.0 (this) → Migration Plan → Round 50 Aggregate Discovery → Aggregate Review
   → Implementation (greenfield Core: Adjudication + Contestation) → Architecture Fitness Tests
   → Empirical Evaluation → LIT-METHOD → Release 1.x / 2.0
```

---
*Architecture Release Notes 1.0 — ISSUED. Bundle: Knowledge/Vocabulary/Landscape 1.0 + BDR 1.1 + frozen methodology. Phase II COMPLETE; Phase III (Strategic→Tactical) starts. Greenfield Core = Adjudication + Contestation. Known limitations + roadmap recorded. Next: Migration Plan → Round 50.*
