# OQ-ENG-003 — Execution Loop Verification (PROTOCOL — not yet executed)

**Status:** COMMISSIONED (ARB, 2026-07-11) — **awaiting execution in a FRESH session.**
**Class:** operational qualification instrument (ES-003) — *verification by execution, not assertion.*
**Purpose:** prove that the execution loop is correctly encoded — that a cold engineer, starting from zero, navigating only by the standards, triggers every rule as designed.
**Deliverable of the run:** a dated record `2026-MM-DD-OQ-ENG-003.md` beside this protocol, per the report table below.

## Execution constraints (binding)

1. **Fresh session only.** The commissioning session authored the standards under test; it is disqualified (execution-integrity, as for C3). The executing session must resolve every decision **from the documents alone** — entry point `engineering/README.md` → STANDARDS_INDEX → Engineering Decision Model.
2. **Sequencing (premise correction on record):** the commission's preamble says "complete, ratified, and declared STABLE" — at commissioning time the platform is **PROPOSED, ratification pending, C3 unrun, STABLE undeclared**. Recommended order: **ratification → fresh session runs C3 + OQ-ENG-003 together → STABLE.** The two instruments are complementary: C3 verifies the *executable* gates (AST-010 run-gates.sh); OQ-ENG-003 verifies the *governance* encoding (the decision loop). The ARB may re-order.
3. **ES-003.1 applies to the run itself:** findings are **reported, never fixed in-run**. Any correction goes through Decision Authority approval, then re-run (verdict history kept — PASS AFTER CORRECTION is a real verdict).
4. **No design:** do not modify architecture · do not create standards · do not write code. Traverse and verify only. Steps 4–5 (Implementation, Verification) are verified as **dry traversal** — does the loop *route* to the right rule? — since no production code is written.

## The loop under test

```text
New work item → DetermineConcern → DetermineApplicableStandards → EP-01 Plan First → ARB Review
→ Implementation (EEP) → Verification (ES-003) → EP-02 Completion Review
→ DetermineReusePotential → (Yes → DetermineArtifactType → DeterminePromotionPath)
→ Recommended next action → ARB decides
```

## Steps and expected triggers

Simulated work item: *"A developer has raised a question about how to structure a new developer guide for the Project Knowledge context."*

| # | Step | Expected trigger(s) |
|---|---|---|
| 1 | New work item | DetermineConcern → ES-005.1 · DetermineApplicableStandards → STANDARDS_INDEX one-line table |
| 2 | Plan | EP-01 Plan First (ES-002 / EEP); EP-01-Light form if below-IDD |
| 3 | Governance gate | ES-001.2 (ARB decides, AI evaluates) · ES-001.1 (no new rule without necessity) |
| 4 | Implementation | EEP lifecycle (plan → independent review → approval → implement → verify → report → decide) · ES-002.1 implementation-first |
| 5 | Verification | ES-003.1 report-never-fix · ES-003.2 no persisted scores · ES-003.3 config-with-result |
| 6 | Completion | EP-02 report (changes · deliberately-not-made · evidence · commits · risks · next action) · ES-004.1 · ES-004.2 |
| 7 | Harvest | DetermineReusePotential → **ES-006.4** ("did this work REVEAL reusable engineering knowledge?"); **"No" is a valid, healthy outcome** |
| 8 | If Yes | DetermineArtifactType → ES-004 · ES-006 (pattern card · guide · qualification improvement · candidate standard · research · nothing) |
| 9 | If candidate standard | DeterminePromotionPath → ES-006.1 ladder; nothing promotes without evidence |
| 10 | Next step | EP-02 ends with "Recommended next action"; **the ARB decides** — continuation never implicit (EEP §9) |

**The one rule that must hold throughout:** *the architecture never executes — the Engineer consults it.* At every step, record evidence that the Engineer consulted the Decision Model/standards, not the reverse.

## Report format (the run's record)

One row per question, verdict **Yes / No / Partial**, each with the evidence (which document was consulted, what it said): DetermineConcern · DetermineApplicableStandards · EP-01 · ARB Review · EEP followed · ES-003 followed · EP-02 report produced · DetermineReusePotential · DetermineArtifactType (if applicable) · DeterminePromotionPath (if applicable) · Ask-for-next-step. Overall verdict per ES-003.1: **PASS · PASS AFTER CORRECTION · WARN · FAIL**. Findings in the F-OQ3-n series. **STOP after the report — ARB review decides the next step.**

---
*Traceability: ARB commission 2026-07-11 (execution-loop verification prompt), issued immediately after R-38 (conceptual freeze) — consistent with it: this exercises the platform, extends nothing.*
