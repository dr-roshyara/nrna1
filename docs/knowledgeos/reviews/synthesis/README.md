# KnowledgeOS Synthesis Track — Workplace Index

**This directory is the synthesis workplace** (confirmed HPA, 2026-08-28).
**Source corpus (READ-ONLY, never modified):** `docs/knowledgeos/brainstorming/phase_measure_theory/`
**Standing:** research/synthesis artifacts. Nothing here is adopted architecture; the epistemic grade
of every element is stated inside each artifact.

## Reading order for a reviewer

1. `analysis/governance-notes.md` — **start here**: all rulings GN-01…GN-18, programme status, the
   pending decision.
2. `analysis/phase-1-status.md` → `phase-2a-findings.md` → `phase-2b-findings.md` →
   `phase-2c-coherence-review.md` → `phase-2d-closure.md` — the gate reports, in order.
3. `model/canonical-architecture.md` — the Phase-3A canonical model v0.1.
4. `analysis/phase-3b-falsification-report.md` — the attack on that model; **four repairs await ruling**.

## Directory map

```
analysis/   archaeology + evidence + gates
  corpus-inventory.md            1A  what the corpus is (408 files, cutoffs, duplicates)
  reasoning-timeline.md          1B  five regimes R1–R5, six turning points
  claim-registry.md              1C  26 claims, graded
  decision-registry.md           1D  methodological vs architectural decisions
  contradiction-registry.md      1E  CON-01…07 (lens→algebra shifts, etc.)
  experiment-registry.md         1F  EXP-01…04 (what was actually tested)
  open-questions.md              1G  epistemic gaps EG-01…06, OQ, PQ
  regime-comparison-matrix.md        object/method/evidence per regime
  semantic-lineage-map.md            8 terms: preserved/transformed/displaced (Ω revised in 2B)
  phase-gate-ruling.md               the Phase-1→2A gate
  phase-*-findings / reviews         gate reports 2A…2D, 3B
  governance-notes.md                rulings + status (the control ledger)

model/      reconstruction + canonical architecture
  regime-transition-analysis.md      R1→R5 transitions, 9 questions each
  concern-map / responsibility-map / invariant-map / dependency-graph   (2B)
  lord-omega-analysis.md             Ω decomposition (Ω-a→EC · Ω-b dropped · Ω-c→X_t)
  formalization-fidelity.md          Zero/Lord/Sārathi: preserved vs transformed
  logical-reconstruction.md          the 8-layer candidate model
  canonical-architecture.md          3A canonical model v0.1 (superseded, retained unmodified)
  canonical-architecture-v0.2.md     v0.2 = v0.1 + ruled repairs R-1…R-4 (GN-19)  ← AUTHORIZED head
```

Future (gated, directories created only when their phase produces content):
`architecture/` — 3C conformance results · `book/` — after the book-architecture gate (GN-15/16/18).

## Current state (as of 2026-08-28 — timestamped per GN-03)

Phases 1, 2A–2D, 3A, 3B ✅ · repairs F-1…F-4 **RULED ACCEPT (GN-19, HPA, 2026-08-28)** and
**APPLIED** · **v0.2 = current authorized canonical model** (v0.1 retained as pre-falsification
record) · **3C EXECUTED (GN-22, HPA 2026-08-28)** — plan approved, conformance run complete: findings
`analysis/phase-3c-conformance-findings.md` (CF-001…016) · verdicts
`analysis/phase-3c-verdict-register.md` · report `analysis/phase-3c-report.md` — no repairs, v0.2
unmodified · **GATE A RULED (GN-24):** CF-001 & CF-003 resolve-before-Final-Architecture · CF-006/CF-009
feed-into-Final-Architecture · CF-010 adjudicate · **GATE B RULED (GN-26): Brainstorming Archaeology EXECUTED & COMPLETE** — 9 artifacts
(`analysis/phase-archaeology-*.md`: inventory, findings AF-001…011, concept-evolution,
alternatives ALT-01…08, experiments, decisions HD-1…7, to-v02-map, provenance-index, gate-report) ·
corpus READ-ONLY throughout, v0.2 untouched · **GN-27: CF-001/CF-003 RESOLVED, CF-010 ADJUDICATED** (phase-3c-dispositions-resolution.md) —
all GN-24 pre-Final-Architecture obligations discharged · **FINAL ARCHITECTURE EXECUTED (GN-29)** — `final-architecture/FA-1…FA-8` (baseline = v0.2
unmodified + D-FA-1…7 reconciliation determinations, all needs-ratification; OQ-1…12 open) ·
**FINAL ARCHITECTURE RATIFIED (GN-31, HPA, 2026-08-28)** — D-FA-1…7 ACCEPT · riders OQ-10/OQ-6
HELD · OQ-1…12 open BY RULING (part of the ratified architecture) · **BOOK ARCHITECTURE DESIGNED (GN-33)** — `book-architecture/BA-1…BA-7` (4 parts · 25 chapters ·
terminology/provenance/verification architecture · production spec; DESIGN ONLY, no folders, no
prose) · **BOOK PRODUCED (GN-35)** — `book/`: 25 chapters × 4 artifacts + index + provenance-index; gates
G-B1…G-B4 PASS · **INDEPENDENT REVIEW DONE (GN-38/39): DEFECTS REQUIRING CORRECTION — D-1…D-4, localized,
none structural** (`analysis/book-independent-review.md`; producer packet:
`book-review-acceptance-packet.md`) · GN-40 corrections applied · **BOOK ACCEPTED (GN-41, HPA, 2026-08-28)** — programme gate chain
GN-01…GN-41 complete through acceptance · surviving open items: OQ-1…12 (by ruling) · riders HELD ·
RA v1.1 intake (OQ-9) · BA-3 §1↔§3 note · kernel full-read worklist · then Brainstorming Archaeology → Final Architecture → Book Architecture → Book
(all 🔒, GN-20 sequence).

## Standing rules in force here

GN-01 synthesis-echo = zero evidence · PQ-02 lineage ≠ document count · GN-03 all statistics
timestamped · GN-10 never "validated" · GN-12 show how the architecture became discoverable ·
GN-14 no silent epistemic upgrade in the book · GN-18 book organized by architecture, not chronology.
