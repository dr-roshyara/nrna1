# ES-003 — Qualification

**Status:** PROPOSED · part of the [Standards Index](STANDARDS_INDEX.md)
**Purpose:** how the platform verifies itself and everything it governs — the qualification lifecycle and measurement discipline.
**Scope:** every qualification, measurement, gate run, and recorded verdict.
**Authority:** Decision Authority (ARB); instruments produce, the authority accepts.
**Qualification Method:** re-runs (a qualification is re-validated by re-running it) + audits of verdict-history integrity.
**Supersedes:** the OQ-ENG-001 addendum and MEMORY as rule homes for the lifecycle (resolves finding F-OQ2-1); MEMORY texts of the score-stop and measurement conventions.
**Enforcement:** AI + Machine (instruments) + Human (acceptance) (see the Enforcement Matrix in the Standards Index).
**Related Standards:** ES-001 (authority) · ES-002 (what gets qualified) · ES-004 (how verdicts are recorded).

## Hosted rules (canonical here)

**ES-003.1 — The Qualification Lifecycle** *(ARB 2026-07-10, from OQ-ENG-001 usage evidence)*. A qualification **never silently fixes what it finds**:

```text
Qualification → Findings → Decision Authority approves corrections → Engineer implements → Re-run → Verdict
```

Verdict vocabulary: **PASS · PASS AFTER CORRECTION · WARN · FAIL** — a corrected failure is never re-labeled a clean pass; history is part of the verdict. Distinct ID series: **F-…** findings · **CR-…** corrections · **OQ-…** qualifications — every defect has its own lifecycle. Verification and implementation are different activities under different authorizations.

**ES-003.2 — Score-Persistence Stop** *(ARB 2026-07-10)*. Numeric review scores are conversational, never architectural — they are NOT persisted in repository records. Records persist governance states (**Accepted · Rejected · Deferred · Research question · Evidence required · Stable**) plus rationale. Existing recorded scores stand as history.

**ES-003.3 — Measurement Conventions** *(consolidated from ARB rulings during PB-007 / ENG-004)*:
- The measuring instrument runs → captures → returns PASS/FAIL → stops; **no interpretation inside the instrument** (registered: R-26, rulings register).
- **Measurement configuration is part of measurement semantics** (an explicit thread count, driver, and invocation path belong to the recorded result — F-7D-2 lesson).
- **Baseline, then deliberate ratchet:** thresholds are introduced as observed baselines, raised only deliberately at completion milestones, never retroactively, never lowered.
- **Displayed values are derived, never asserted** (dashboards and reports render what instruments produced; a value with no evidence source is not displayed).

## Registered (pointers)

| Rule | Home |
|---|---|
| R-26 measuring-instrument ruling | rulings register (`../architecture/adr/ADR-AIP-LOG-…`) |
| Qualification records & precedents | `../verification/qualification/` (OQ-ENG-001, OQ-ENG-002) |
| The project's gate binding (`composer merge-gate` / `quality-gate`) | product repository scripts + PB-007 IDD (project property) |
