# Track-2 Phase-0 Historical Back-Test — Report

| | |
|---|---|
| **Kind** | **Assurance evidence report** — the **DELIVERABLE of the TRACK 2 · PHASE 0 — HISTORICAL BACK-TEST** commission. ⛔ **It supplies evidence; it does not manufacture authority. Nothing is adopted by this run.** |
| **Status** | ✅ **FINAL STATUS: PASS — for the authorized Phase-0 scope** *(see §2 for the exact scope of that PASS and §6 for what it does NOT cover)* |
| **Plan** | `docs/plans/20260821-1138-track2-deterministic-assurance-phase0-plan.md` — **EP-01 AUTHORIZED** *(DA approval + commission, recorded `1fcdb353`)* |
| **Commissioning review** | `docs/knowledgeos/reviews/2026-08-21-cost-optimization-governance-assurance-review.md` §7 (the phasing) and §3/§11 (measurement + canonical discovery) |
| **Date** | 2026-08-21 |
| **Authority boundary** | ⛔ **read-only** · no governed artifact modified · no workflow record · no migration state · no gate · no routing · no identifier minted · no assurance class · no methodology change · **warn-only, exit 0 everywhere** |

**Epistemic classes used throughout: Observed · Derived · Hypothesis · Recommendation · Open Question.** *Not mixed.*

---

## 1 · Objective

> **Make the mechanically-detectable defect classes that Track 2 paid for in amendment cycles detectable by a read-only check, and prove it by REDISCOVERING them in the historical record.**

Phase 0 is a **falsification back-test**: the five realized S1–S5 structural checks are run over the migration plan *as it stood at each historical commit* (`0a2fa71d` · `7d3abc59` · `8307beca`) and must reproduce the defects the independent reviews raised — and must be **QUIET on the repaired current artifact** (AMD6). The exit criterion is §6 of the approved plan, reproduced cell-for-cell as an executable harness (`Tests/BackTest/Phase0BackTest.php`).

## 2 · FINAL STATUS

**✅ PASS — for the classes the approved Phase-0 scope authorized the back-test to falsify.**

The five realized slices **rediscovered every historical finding in their classes with deterministic evidence** (§3), the **AMD6 quiet row holds** (§4), every report carries the D-4 NOT-CHECKED statement, the run is **warn-only exit 0** with no gate/hook/CI wiring, **no governed artifact was modified**, and **no migration action was taken**.

⛔ **The PASS is scoped.** It covers what `S1`–`S5` realize and what the §6 matrix requires. The required back-test targets that Phase 0 does **NOT** cover — `DI-3`/`DI-6` *(S8, blocked on `OQ-1` by plan `D-6`)* and the corpus-level classes *(`R-CONFLICT` sequence checks · grant/aggregate identity · amendment lineage)* — are stated as **NOT-CHECKED** in §5. **A PASS here is mechanical, not architectural, assurance** (§7). If those NOT-CHECKED classes were silently read as covered, this report would be the false assurance the whole design exists to prevent.

## 3 · The exit criterion, cell for cell — the back-test matrix (Observed)

⭐ **The matrix is an executable test.** `Tests/BackTest/Phase0BackTest.php` materializes each historical state from git at its actual commit, byte-verifies the plan §6 "Lines" column (706 / 833 / 1206 newlines), and runs the five REALIZED S1–S5 application services over it, requiring every cell:

| state | S1 | S2 | S3 | S4 | S5 |
|---|---|---|---|---|---|
| **amd4** `0a2fa71d` | **FAIL** — `DI-1` `## 4.1` ×2 (349, 436) · `## 4.2` ×2 (404, 442) · order `4.1·4.3·4.4·4.2·4.0·4.1·4.2` | **FAIL** — `§4.1`/`§4.2` ambiguous *(a DI-1 collision consequence — recorded, not hidden)* | **FAIL** — `DI-2` four LIVE `Phase 2b` (469, 627, 631, 673) | PASS | PASS |
| **amd5** `7d3abc59` | PASS | **FAIL** — `DI-5` dangling step reference (criterion 14 / §4.5 cite a step beyond the mandated `1·2·3·4` block) | **FAIL** — `DI-7` two UNDECLARED confusable pairs (`CASE B`/`CASE β`) | PASS | **WARN** — `DI-4` §8:709 vs its own declared §4.4 split (120, 382) |
| **amd6** `8307beca` | PASS | PASS | PASS | PASS | PASS |

⭐ **The regression direction holds.** The checker fires on the defective historical states and is silent on the repaired current one. A deliberately drifted oracle cell turns the harness RED (demonstrated) — the matrix is falsifiable, not decorative.

## 4 · The AMD6 quiet row — current state (Observed)

The exact current plan `KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md`, run through the real adapters over the working tree:

- **S1** `identifier-check --document` → **PASS** — 51 numbered section headings, all unique and monotonic.
- **S2** `link-check --anchors` → **PASS** — every §-reference and step-reference resolves.
- **S3** → **INCONCLUSIVE**, not PASS — the D-5 vocabulary config (`docs/knowledge/schema/vocabulary-integrity.yaml`) does not exist yet; the governed copy is a **Phase-1 adoption act**. Fail-closed: *absence of evidence is not PASS*. The S6 harness (which supplies a fixture vocabulary) asserts the AMD6 S3 cell **PASS** — the plan's five cited retired-term occurrences are all quoted/§-scoped/Traceability (0 live).
- **S4** → **PASS**, **S5** → **PASS**.

## 5 · Required back-test targets → disposition

| Required target | Slice | Disposition |
|---|---|---|
| `DI-1` identifier collision / non-monotonic ordering | **S1** | ✅ **REDISCOVERED** at AMD4 — deterministic evidence, §3 |
| `DI-2` stale vocabulary | **S3** | ✅ **REDISCOVERED** at AMD4 — deterministic evidence, §3 |
| `DI-5` dangling reference | **S2** | ✅ **REDISCOVERED** at AMD5 — deterministic evidence, §3 |
| `DI-7` confusable identifiers | **S3** | ✅ **REDISCOVERED** at AMD5 — deterministic evidence, §3 |
| `DI-4` competing current definitions *(unlabelled superseded)* | **S5** | ✅ **REDISCOVERED** at AMD5 — **WARN** (correct severity), deterministic evidence, §3 |
| `DI-3`/`DI-6` enumeration-vs-content agreement | ⚠️ **S8** | ⛔ **NOT-CHECKED.** No catalogued capability owns the class. Named as `OQ-1` and **returned to Governance/ARB** *(capability existence is not Architecture's to decide)*; **S8 is blocked on `OQ-1`** by plan `D-6` — it was deliberately built last and behind the open question, to prevent capability-by-implementation |
| **RC mechanical classes** *(`R-CONFLICT` sequence / integrity: seq dense + monotonic + superset-after-reconciliation)* | — | ⛔ **NOT-CHECKED.** Integration-analysis surface 3 — a later surface; read-only **detection, never resolution**. Not a Phase-0 slice |
| **Grant/aggregate identity · amendment lineage** *(cited grant/aggregate IDs exist; amendment lineage resolves — `OPEN-M6`)* | — | ⛔ **NOT-CHECKED.** Integration-analysis surface 4 — a corpus-level reader in a later surface. Not a Phase-0 slice |

⛔ **None of the NOT-CHECKED rows is a silent gap — each names its reason.** This is the report's honesty obligation: the PASS in §2 is for the checked rows, and Phase 1 must not assume the NOT-CHECKED rows are covered.

## 6 · Current-state observations recorded — NOT repaired (Phase 0 is read-only)

The structural profile over `docs/knowledgeos/architecture` (13 documents) surfaced **two honest FAILs on other documents** — current tree, **NOT AMD6**, recorded as observations, **not repaired**:

| Observation | Slice | Evidence |
|---|---|---|
| `KOS-AIP-GOV-STATE-DURABILITY-IMPLEMENTATION-DESIGN.md` — **ragged table** | **S4 FAIL** | data row line 131 has **2 columns**; header row line 127 has **3** |
| `KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD5-SUMMARY.md` — **dangling step references** | **S2 FAIL** | no mandated internal-order block exists; the prose cites step numbers the block would have to define (reported at lines 31, 36, 37) |

These are real findings the Phase-0 run surfaced and would remain surfaced by any future run. They are recorded here so the assurance trail is complete; **repairing them is a migration-side act, outside Phase-0's read-only boundary.**

Also observed: three architecture docs (`README.md`, `README (1).md`, `Yes.md`) evaluate as **INCONCLUSIVE** on S1/S2/S4 ("nothing to evaluate") — fail-closed, not PASS.

## 7 · D-4 — what this report does NOT check *(the DA's formulation, verbatim)*

> ⛔ **NOT CHECKED (stated positively):** mechanical assurance proves DECLARED STRUCTURE — identifier uniqueness, intra-document reference resolution, declared vocabulary, table shape, disposition labelling. It does **NOT** check soundness, completeness, authority, or provenance — architecture review discovers **UNDECLARED ARCHITECTURAL CONTENT**. A PASS here is mechanical, not architectural, assurance.

**CRITICAL LIMITATION — the checker cannot discover an undeclared architectural act.** It can verify `ACT → declared normative object → criterion → operator ID`; it **cannot** say *"you forgot to declare an act."* The missing-act class — `RD-1`, `RD-7`, `RD-3·b`, and any act Track 2 paid for that was never declared — is caught **only** by human Architecture review. This report does not and cannot claim to have found every historical defect; it claims to have rediscovered, with deterministic evidence, the declared-structure defects in the five realized classes.

## 8 · Read-only guarantees (Observed)

- ⛔ **No governed artifact modified** — the working tree contains no checker-written change under `docs/`; the four entry points only read.
- ⛔ **No migration action taken** — no state moved, no grant/aggregate touched.
- ⛔ **Warn-only, exit 0** — `knowledge-lint --profile=structural`, `link-check --anchors`, `identifier-check --document` all exit 0 regardless of verdict (`D-3`); `verify.sh` Gate 7 uses severity `warn` and never sets `OVERALL_STATUS`; a `--strict` flag exists but is wired into **no gate, hook, or CI**.
- ⛔ **No identifier minted** — `PMR-10` pre-mint check performed at plan time; the run consumes `CAP-001`/`CAP-003`/`CAP-004`, which the catalogue already carries.
- ⛔ **Nothing adopted** — `OQ-2`'s confirmation (§0.4.4 = `DP-3` applied to a document) is **recorded as evidence, not adopted**; `H-CAT-1`'s confirm/refute is recorded for ARB; the methodology freeze (`2026-08-01`) is untouched.

## 9 · Evidence (Observed)

- `--testsuite=EngineeringKnowledge` — **220 tests / 501 assertions green**, including the S6 back-test harness (19 tests) and the S7 adapter integration tests (5 tests at the real entry points).
- `bash scripts/verify.sh` — **ALL GATES PASSED**, exit 0; Gate 7 "Track-2 Structural Integrity (Phase 0, warn-only)" passes warn-only.
- The four entry points verified end-to-end: `knowledge-lint --profile=structural --root=docs/knowledgeos/architecture` · `link-check --anchors=docs/knowledgeos/architecture` · `identifier-check --document=…/MIGRATION-PLAN.md` · `verify.sh` Gate 7.

## 10 · Stop conditions — honored

| Stop condition | Status |
|---|---|
| no new policy · no new authority · no new gate · no new routing | ✅ honored |
| no new assurance class · no new identifier family | ✅ honored |
| no `B′`/`R-CONFLICT` modification | ✅ honored |
| Phase-0 scope only; S8 not started | ✅ honored |

## 11 · Traceability

`docs/plans/20260821-1138-track2-deterministic-assurance-phase0-plan.md` *(§4 D-1…D-6 · §6 exit criterion · §7 DoD · §9 OQ-1…OQ-4)* · `docs/knowledgeos/reviews/2026-08-21-cost-optimization-governance-assurance-review.md` *(§3 measurement · §5 script discovery · §7 phasing · §11 capability discovery)* · `docs/knowledgeos/brainstorming/20260821_1206_kos-state-durability-assurance-integration.md` *(§2 back-test falsification experiment · §3 surfaces 3–4 for the NOT-CHECKED rows)* · `docs/implementation/PKS_Phase_III_Capability_Catalog.md` *(§4 `CAP-001`…`CAP-006` · §7 `DP-1`…`DP-6` · §8 `H-CAT-1`)* · implementation commits `895d38cb` · `d16a3a78` · `4a923440` · `73dbe091` · `a2529dde` · `b753cac1` · `7f04e220` · **evidence read directly:** `scripts/lib/EngineeringKnowledge/**` · `scripts/{knowledge-lint,link-check,identifier-check}.php` · `scripts/verify.sh` · `Tests/BackTest/Phase0BackTest.php` · `Tests/Adapters/StructuralAdaptersCliTest.php`.

---

**FINAL STATUS: ✅ PASS (authorized Phase-0 scope).** ⛔ **Phase 1 (author-side adoption) is a separate act on §6's evidence — this run adopts nothing.**
