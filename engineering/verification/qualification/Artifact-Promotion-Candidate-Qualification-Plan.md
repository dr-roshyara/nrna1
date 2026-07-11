# Artifact Promotion Candidate — Qualification Preparation Report

**Commission:** ARB, 2026-07-11 · **Status:** PREPARED — executed during the Project Knowledge pilot; results feed the retrospective.
**Candidate under test:** `DetermineArtifactLifecycle` (candidate Engineering Decision) together with the Artifact Promotion pattern it encodes (`Runtime Artifact → Promotion Event → Governed Engineering Artifact → Qualification → History`). One candidate, two facets: the decision and the pattern.
**Discipline:** evidence only — this document defines measurable promotion criteria; it does not argue for the abstraction. No standards, Decision Model, or runtime configuration are modified by it.

## 1. What evidence is missing (from the validation report)

The validation report holds **3 retrospective confirmations (medium strength) · 0 prospective confirmations (strong) · 0 counterexamples**. Retrospective instances do **not** count toward promotion — they were recognized after the fact, in work not executed as the pattern. The missing evidence is exactly one class:

> **≥ 1 prospective instance in a NON-plan artifact type** — work intentionally executed *as* the pattern, with the promotion event declared **before** the crossing.

## 2. Evidence taxonomy (binding for the pilot)

| Class | Definition | Counts toward promotion? |
|---|---|---|
| Retrospective | pattern recognized afterwards in past events | No (context only) |
| Prospective | promotion event pre-declared; crossing executed through it; recorded at the time (session log / EP-02) | **Yes** |

**What counts as one prospective instance (all four required, checkable from records):** (1) the artifact and its runtime representation named *before* the crossing; (2) the promotion event named *before* the crossing; (3) the governed destination + qualification responsibility named at promotion; (4) the record made at the time, not reconstructed later.

## 3. Qualification criteria

### CONFIRMED when (all three):
1. **≥ 1 prospective non-plan instance** per §2 occurs during the pilot (candidate types the pilot naturally offers: WorkingContext→governed knowledge representation · knowledge claim promotion · pilot report/evidence artifacts).
2. **Zero missed-promotion defects**: no new governance record cites a runtime-named artifact by path without a promotion event (the R-36 defect class; counted by grep over records created during the pilot — count, not score, per ES-003.2).
3. **The decision resolves from existing authorities alone**: every `DetermineArtifactLifecycle` resolution during the pilot needed only ES-005.1 + ES-004.2 + the deletion litmus — no new rule was required (checkable from EP-02 reports).

### FALSIFIED when (any one):
1. **The deletion litmus misclassifies**: an artifact it marks ephemeral turns out governance-load-bearing (its deletion/absence breaks a governed record), or an artifact it marks governed is demonstrably disposable — the discriminator itself fails.
2. **A legitimate crossing the pattern cannot describe**: pilot work requires a runtime→governed transition that has no identifiable explicit promotion event, and inventing one would be ceremony rather than governance.
3. **Systematic bypass**: pilot engineers repeatedly make lifecycle/placement choices without the decision ever being a real decision point (evidence the decision doesn't correspond to actual work).

### INCONCLUSIVE when:
- The pilot produces **no non-plan crossing opportunities at all** (nothing to test). Consequence: the candidate **remains a candidate** — no promotion, no retirement; the horizon extends to the ~20–30-task validation window already on record. Inconclusive is a legal outcome, not a failure.

### EMERGENT OBSERVATION *(fourth outcome — ARB refinement, 2026-07-11)*:
- The pilot reveals a **third explanation** — something neither confirming nor falsifying the candidate (genuine pilots often do). Recorded as **research input only**: it does **not** influence the candidate's verdict or status immediately; it enters the retrospective as a research question (the same channel as the Governance Promotion watch-item). This prevents surprise findings from silently becoming architecture.

## 4. Counting & recording rules
- Counts only, never composite scores (ES-003.2). Sources: session logs, EP-02 reports, the pilot's qualification records — no new instrumentation is built for this (R-26: measurement lives in qualification instruments, periodically).
- The verdict is recorded per ES-003.1 (PASS / FAIL / INCONCLUSIVE with history); the **ARB decides promotion** at the retrospective (ES-006.1) — this plan defines the evidence, never the decision.

## 5. Watch-item (recorded, NOT acted on)
If the pilot's instances suggest the deeper abstraction is a **governance state transition** ("Governance Promotion": Runtime → Candidate → Governed → Qualified → Historical) rather than artifact movement, record it as a research question for the retrospective. **Do not rename anything mid-pilot.**

---
*Traceability: ARB qualification-preparation commission 2026-07-11 · validation report `../reports/2026-07-11-artifact-promotion-pattern-validation.md` (3 retrospective / 0 prospective) · candidate marking in the Engineering Decision Model. STOP — ARB review; execution belongs to the pilot.*
