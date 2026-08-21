# Track 2 — Deterministic Assurance · Phase 1 — Author-Side Adoption — Plan

| | |
|---|---|
| **Kind** | **EP-01 engineering plan** — the Planning Stage for the **PHASE 1 ADOPTION** commission of 2026-08-21. Governed home per **ES-004.2**. |
| **Status** | ✅ **EP-01 APPROVED** *(Decision Authority, 2026-08-21 — approval of the Phase-1 adoption plan; R-46: EP-01 plan approval is the DA's act; recorded, not inferred, per CAP-001 precedent `20260802-0015`)*. ⛔ **Approval covers this Phase-1 plan only.** |
| **Predecessor** | `docs/plans/20260821-1138-track2-deterministic-assurance-phase0-plan.md` — **AUTHORIZED · EXECUTED · PHASE 0 COMPLETE — PASS (authorized scope)** |
| **Commissioning review** | `docs/knowledgeos/reviews/2026-08-21-cost-optimization-governance-assurance-review.md` §7 *(Phase 1 — author-side adoption)* |
| **Date** | 2026-08-21 |
| **Authority boundary** | ⛔ **read-only w.r.t. governed artifacts** · warn-only, exit 0 · no new governance gate · no review role · no authority domain · no identifier · no provenance mechanism · no bounded context · no assurance class · no routing · do not modify DV-1…DV-7 or the durability migration · no EKS-07 · no Phase 5 · no S8/OQ-1 |

---

## 1 · Context

TRACK 2 · PHASE 0 (deterministic assurance back-test) is **CLOSED — PASS (authorized scope)**: five realized structural slices S1–S5 (CAP-001/003/004), harness S6, adapters S7; 220 tests / 501 assertions; the §6 back-test matrix is executable and AMD6-quiet; warn-only exit 0; nothing adopted. The DA now commissions **Phase 1 ADOPTION**: turn the proven capability into a reusable **author-side assurance practice** — the author runs the checks before Architecture/Governance handoff and attaches the evidence. It must remain **deterministic · explainable · read-only w.r.t. governed artifacts · warn-only · non-authoritative**.

The earlier readiness-package request is **superseded**; its content is absorbed into the evidence report + business-value summary deliverables.

## 2 · Objective

Implement the smallest practical **author-side handoff-assurance workflow** reusing the existing Phase-0 capabilities, and deliver: the handoff/report definition, author-side usage instructions, automated tests (RED→GREEN→REFACTOR), a real-corpus-validated **adoption evidence report**, and a **business-value summary** (OBSERVED / ESTIMATED / UNKNOWN).

## 3 · Readiness review (EP-03, derived — no human gap)

| Domain | Derived answer |
|---|---|
| Business | Reduce repeated manual mechanical-defect discovery before review. No constitutional invariant touched (knowledge-documentation integrity only). |
| DDD | Existing bounded context (KnowledgeOS engineering tooling, `EngineeringKnowledge\`). The handoff report is a **cross-capability consumer** — no new aggregate, no new capability, no ownership change. |
| Architecture | The commissioning review §7 Phase-1 (author-side adoption, authority = existing pre-delivery obligation) · the accepted 2026-08-04 correction (deterministic work not speculative) · Phase-0 plan D-1…D-7. |
| Process | No approved plan → this plan. |
| TDD | New behavior → RED first: report-content rules (aggregate derivation, recommendation, NOT-CHECKED presence), workflow properties, no-governance-language, no-gate (exit 0). |
| Design | Application service composing existing `handle(string $path): Assessment` services; Domain VO for report invariants; reporter render + CLI adapter. Reuse `Assessment`/`Verdict`/`StructuralCliReporter`. |
| Impact | New files under `scripts/lib/EngineeringKnowledge/Shared/{Domain,Application,Infrastructure}` + `Shared/Tests/…`; one CLI extension in `knowledge-lint.php`; developer guide; evidence report. **No** governed artifact schema change. |
| Verification | `--testsuite=EngineeringKnowledge` green · real-corpus expected→observed · `verify.sh` unchanged ALL GATES PASSED · 12 acceptance criteria checked. |
| Completion | Dev guide per slice · one commit per slice · evidence report · FINAL STATUS discipline. |

## 4 · Scope

**In scope:** handoff-report capability (Domain VO + Application service + reporter render + `knowledge-lint --report=handoff --document=… [--out=…]`), TDD tests, real-corpus validation (defective historical → FAIL · corrected current → quiet on supported classes / INCONCLUSIVE S3 · new authoring fixture → meaningful report), developer guide, evidence report + business-value summary, bookkeeping.

**Not in scope:** new catalogue capability row · new identifier/register · gate/hook/CI wiring · `--strict` wiring · EKS-07 · provenance system · routing/assurance classes · durability migration · DV-1…DV-7 · Phase 5 · S8/OQ-1.

## 5 · Design decisions

| Row | Decision |
|---|---|
| **D-1** | **Report-content rules are business rules** → live in `Shared/Domain` + `Shared/Application`; scripts stay adapters. `Shared/` already hosts cross-capability types (`Assessment`, `Verdict`, `StructuralCliReporter`) — the handoff report is a **consumer**, not a new capability; no new catalogue identifier. |
| **D-2** | The handoff report carries the nine required elements: artifact identity · checker name · checker version · source commit · checks executed (per-slice verdicts) · findings with evidence · NOT-CHECKED areas (D-4 statement verbatim + named rows) · known limitations · generated-at + execution context. |
| **D-3** | **Aggregate verdict is fail-closed** — precedence **FAIL > INCONCLUSIVE > WARN > PASS**: any FAIL → FAIL; else any INCONCLUSIVE → INCONCLUSIVE; else any WARN → WARN; else PASS. *Absence of evidence is not PASS* (CAP-001 / Phase-0 D-2). |
| **D-4** | **Warn-only preserved** — handoff mode **always exit 0**; `--strict` does not apply (note printed if combined). FAIL/INCONCLUSIVE render an explicit **FIX BEFORE HANDOFF** recommendation (author-side practice), never an autonomous reject/approve; findings never auto-converted to PASS; report carries no governance vocabulary (`APPROVED`/`REJECTED`/`ACCEPTED`). |
| **D-5** | **Provenance, existing mechanisms only**: checker version from a library constant `CheckerVersion::CURRENT` · source commit = `git rev-parse --short HEAD` at run time or `UNKNOWN` (shelling git is established: `link-check.php:111`) · generated-at timestamp + command line · process identity only where existing mechanisms support it, **never treated as attestation**. No new provenance mechanism. |
| **D-6** | Output: **stdout by default**; `--out=<path>` is an explicit author opt-in that writes the Markdown report where the author directs. The checker writes nothing it scans (read-only w.r.t. governed artifacts). |
| **D-7** | Entry point: **extend `knowledge-lint.php`** (`--report=handoff --document=<path> [--vocabulary=<path>] [--out=<path>]`) — reuses the existing service wiring. No new script. |

## 6 · Implementation slices (one commit per slice; dev guide per slice)

### P1 — Domain value objects + invariant tests (RED→GREEN)
New under `scripts/lib/EngineeringKnowledge/Shared/Domain/`:
- `HandoffContext` (readonly VO): `target` · `checkerName` · `checkerVersion` · `sourceCommit` · `generatedAt` · `commandLine`; named constructor `of(...)`.
- `AssuranceHandoffReport` (readonly VO): holds `context` + `array<string, Assessment> $perSlice` + `notCheckedStatement` + `notCheckedAreas` + `limitations`. Methods: `aggregateVerdict(): Verdict` (D-3 precedence), `findings(): array<string, Assessment>` (non-PASS slices), `recommendation(): string` (FIX BEFORE HANDOFF iff FAIL; review INCONCLUSIVE; resolve WARN at author judgment; attach-as-evidence iff PASS). Invariant: non-empty `perSlice`; `notCheckedStatement` never empty.
- `CheckerVersion` (constants class): `CURRENT = '1.0.0'`.
New `Shared/Tests/Domain/AssuranceHandoffReportTest.php` (+ `HandoffContextTest.php` if warranted): aggregate precedence, findings filter, recommendation vocabulary, fail-closed (INCONCLUSIVE never yields PASS), D-3 properties. **Reuse** `Assessment::of(Verdict::…, evidence, subject)`.

### P2 — Application service + tests
New `Shared/Application/GenerateHandoffAssuranceReport.php` (`final readonly`): `handle(HandoffContext $context, array $perSlice, ?array $notCheckedAreas = null): AssuranceHandoffReport` — assembles the VO, supplies the structural profile's **named NOT-CHECKED areas** (DI-3/DI-6 → OQ-1/S8 · RC mechanical classes → surface 3 · grant/aggregate identity & amendment lineage → surface 4 · the undeclared-act class — human architecture review only) and known limitations; never manufactures PASS.
New `Shared/Tests/Application/GenerateHandoffAssuranceReportTest.php` — fake assessments (anonymous-class pattern from `ReferenceIntegrity/Tests/Application/ValidateIntraDocumentReferencesTest.php`), asserts report structure + named NOT-CHECKED always present.

### P3 — Reporter render + CLI adapter + integration tests
- Extend `Shared/Infrastructure/StructuralCliReporter.php` with `renderHandoffReport(AssuranceHandoffReport $report): string` — renders all nine elements, reusing `badge()`/`sliceLine()`/`notCheckedStatement()`; header block with context/provenance, per-slice table, findings, D-4 verbatim, named NOT-CHECKED, limitations, recommendation, footer.
- Extend `scripts/knowledge-lint.php`: parse `--report=handoff`, `--document=`, `--out=` (pattern at lines 54–69); a `run_handoff_report()` that instantiates the same five services (S1–S5 map, `run_structural_profile` lines 97–106), runs each over the document, builds `HandoffContext` (version constant · git HEAD via `git -C … rev-parse --short HEAD` else `UNKNOWN` · `date('c')` · `implode(' ', $argv)`), calls `GenerateHandoffAssuranceReport`, renders, writes stdout or `--out`, **exit 0**; missing `--document` → usage exit 3.
- New `Shared/Tests/Adapters/HandoffAdaptersCliTest.php` (or extend `StructuralAdaptersCliTest`, same `invoke()` pattern): defective fixture → report shows FAIL + FIX BEFORE HANDOFF + exit 0 · clean fixture → PASS · no `--vocabulary` → S3 INCONCLUSIVE visible + aggregate INCONCLUSIVE (never PASS) · report contains checker name/version/commit/timestamp + D-4 statement + named NOT-CHECKED + no governance vocabulary · `--out` writes the file and leaves the scanned doc untouched · rerun-after-remediation (fix fixture → PASS).

### P4 — Real-corpus evidence + evidence report + business-value summary
- Scripted runs (read-only, `git show` like `Phase0BackTest::materializeState()`): **AMD4 `0a2fa71d` / AMD5 `7d3abc59` plan state → FAIL** (findings present) · **AMD6 current plan → S1/S2/S4/S5 PASS, S3 INCONCLUSIVE (no vocab config) → aggregate INCONCLUSIVE** · **new authoring fixture → meaningful report** (all slices evaluated or documented INCONCLUSIVE).
- New `docs/knowledgeos/reviews/2026-08-21-track2-phase1-adoption-evidence.md`: real-corpus expected→observed table · the **12 acceptance criteria** from the commission checked · FINAL STATUS: **PHASE 1 ADOPTION IMPLEMENTED / VERIFIED** · STOP.
- **Business-value summary** (in the same evidence report): **OBSERVED** — mechanical share 29%→46%→58% (review §3.1) · 14 defect instances rediscovered deterministically (back-test §3) · 5 amendments / 4 reviews / 12 corrections for one 4,315-line migration (review §3.2) · AMD6's 14-point manual pre-delivery check (review §3.3) · AMD6 quiet (0 false positives on the corrected artifact). **ESTIMATED** (clearly labelled) — per-defect cost ≈ one amendment cycle; the Phase-1 exit metric (review §7: next review raises *fewer* mechanical findings than 7-of-12). **UNKNOWN** — reviewer-hours, financial savings, post-adoption false-positive rate. ⛔ No invented savings.

### P5 — Developer guide
New `developer_guide/knowledgeos/03_phase1_author_side_adoption.md` (+ `00_index.md` row): when to run (before Architecture/Governance handoff) · the command · what the report contains · the FIX BEFORE HANDOFF practice (author-side, warn-only) · the handoff package definition (report + artifact identity + checker version + result + NOT-CHECKED) · what remains human · pitfalls (reading PASS from silence; the `step N` prose trap; trimming D-4; wiring `--strict`).

### P6 — Bookkeeping
Write the canonical plan to `docs/plans/20260821-<HHMM>-track2-phase1-author-side-adoption-plan.md` + approval record commit (mirrors Phase-0 `1fcdb353`) · plan status · session log `.claude/sessions/2026-08-21.md` · `.claude/CONTEXT.md` NEXT → STOP · closing commit chain.

## 7 · Verification

1. `--testsuite=EngineeringKnowledge` green — existing 220 + new P1–P3 tests.
2. Real-corpus runs: expected→observed matches the table in P4.
3. `bash scripts/verify.sh` — unchanged, ALL GATES PASSED (handoff mode is an author-side affordance, wired into nothing).
4. The evidence report checks all 12 acceptance criteria explicitly.

## 8 · Risks / boundaries

| | Risk | Mitigation |
|---|---|---|
| 🔴 | False assurance — report reads as "design is sound" | D-4 verbatim on every report; never trimmed; undeclared-act blindness named |
| 🔴 | Scope creep to a gate | warn-only exit 0 · nothing wired · `--strict` untouched · no OVERALL_STATUS-style state |
| ⚠️ | S3 INCONCLUSIVE misread as a defect | preserved + reason stated ("vocabulary config absent — a Phase-1 adoption act"); aggregate never PASS |
| ⚠️ | Provenance overstated | git HEAD = code provenance, never AI-process attestation; process identity only where mechanisms support it |

## 9 · Open questions / determinations

None blocking — the commission delegates the determinations; they are recorded in D-rows: report target = the author's document · storage = stdout + `--out` opt-in · failure handling = FIX BEFORE HANDOFF (author-side), never autonomous. The plan's approval is the checkpoint; **STOP after delivery — the next decision is the human authority's.**

## 10 · Next actions

1. Write this canonical plan + approval record (this commit).
2. Implement P1 → P2 → P3 → P4 → P5 → P6.
3. **STOP.** Final status discipline: **PHASE 1 ADOPTION IMPLEMENTED / VERIFIED** — organizational adoption is a separate human authorization.

## Traceability

Commission of 2026-08-21 (Phase-1 adoption, second message) · superseded readiness-package request (absorbed) · commissioning review `docs/knowledgeos/reviews/2026-08-21-cost-optimization-governance-assurance-review.md` §7 (Phase 1) · back-test report `docs/knowledgeos/reviews/2026-08-21-track2-phase0-historical-back-test-report.md` · Phase-0 plan `docs/plans/20260821-1138-track2-deterministic-assurance-phase0-plan.md` (D-1…D-7) · catalogue `docs/implementation/PKS_Phase_III_Capability_Catalog.md` (CAP-001/003/004 · H-CAT-1/OQ-4 · OQ-1) · code to reuse: `scripts/knowledge-lint.php` (service map lines 97–106, parsing 54–69), `StructuralCliReporter` (`sliceLine`/`notCheckedStatement`/`badge`), `Assessment`/`Verdict`, the five `…\Application` services + `…\Infrastructure` readers, `YamlVocabularySource` (S3 INCONCLUSIVE path), test patterns `StructuralAdaptersCliTest::invoke()` / `Phase0BackTest::materializeState()`.
