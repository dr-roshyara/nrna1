# EM-001 Migration Report — Engineering bounded-context migration

**Date:** 2026-07-10 · **Status:** EXECUTED (ARB-approved, incremental: move → verify → commit → next; five move commits + one reference-sweep commit)
**Rule:** classify first, then move. Every artifact carries exactly one ownership tag: **Product · Engineering · Runtime · Shared · Historical**. Only Engineering-owned artifacts move. `git mv` where tracked (history preserved); plain move where never committed. **A move is not an edit** — sealed/frozen documents remain byte-identical.

## 1. Moves (Engineering-owned)

| # | Old path | New path | Owner | Why |
|---|---|---|---|---|
| 1 | `docs/adr/ADR-AIP-01-AI-Engineering-Platform-Baseline-v1.0.md` | `engineering/architecture/adr/` | Engineering | Platform baseline decision, not a product decision (ARB ruling on D1: the ADR corpus splits by decision class). Untracked → plain move. |
| 2 | `docs/adr/ADR-AIP-02-Product-Primacy.md` | `engineering/architecture/adr/` | Engineering | Platform governance decision (AIP-14). Untracked → plain move. |
| 3 | `docs/adr/ADR-AIP-LOG-Platform-Rulings.md` | `engineering/architecture/adr/` | Engineering | Platform rulings register (append-only, living). **Tracked → `git mv`.** Stays with its ADR-AIP siblings (cross-referenced corpus). |
| 4–9 | `docs/architecture/proposals/ai-platform/Phase-01-Discovery.md`, `Phase-02-Domain-Model.md`, `Phase-02.5-Certification-Plan.md`, `Phase-02.6-Ubiquitous-Language.md`, `Phase-02.7-Platform-Decisions.md`, `Phase-03A-Reference-Architecture.md` | `engineering/architecture/proposals/` | Engineering (sealed, R-30) | The Baseline v1.0 corpus — the platform's genesis documents. Move as-is, byte-identical; internal cross-references are same-directory relative and survive the move. Empty `docs/architecture/proposals/` removed after. |
| 10 | `docs/architecture/c4/AI_Engineering_Platform_Views.md` | `engineering/architecture/c4/` | Engineering | Platform views. Product C4 views (`01_System_Context.md` … `06_Deployment.md`, plantuml) stay in `docs/architecture/c4/`. |
| 11 | `architecture/verification/2026-07-08-architecture-review.md` | `engineering/verification/reports/` | Engineering (Historical record) | Independent external platform review. Content untouched (it is evidence). |
| 12 | `architecture/verification/2026-07-08-1027-architecture-review.md` | `engineering/verification/reports/` | Engineering (Historical record) | ARB-accepted review + addendum (two-level verdict). Content untouched. |
| 13 | `architecture/verification/2026-07-08-provider-independence-strategies.md` | `engineering/verification/reports/` | Engineering | Provider-independence analysis. |
| 14 | `architecture/verification/Architecture Upgrade Assessment.txt` | `engineering/verification/reports/` | Engineering (Historical record) | Ten-phase upgrade assessment under R-27/R-29 ("NO UPGRADES WARRANTED BEFORE PB-004"). `architecture/verification/` removed after (becomes empty). |
| 15 | `architecture/brain_storming/engineering_pattern_cards_agent_skills.md` | `engineering/knowledge/harvests/` | Engineering | Harvest #1 dossier + the Pattern Evidence Register + Convergence table (research dossier per ARB header ruling). |
| 16 | `architecture/brain_storming/engineering_pattern_cards_claude_runtime_article.md` | `engineering/knowledge/harvests/` | Engineering | Harvest dossier (EPC-011..014). |
| 17 | `architecture/brain_storming/engineering_pattern_cards_claude_best_practices.md` | `engineering/knowledge/harvests/` | Engineering | Harvest dossier (EPC-015..018). |
| 18 | `architecture/brain_storming/knowledge_architecture_validation.md` | `engineering/verification/reports/` | Engineering | Transition audit with findings F1–F4 — evidence, not a harvest. |
| 19 | `architecture/brain_storming/Claude Agent Skills_ A First Principles Deep Dive.pdf` | `engineering/knowledge/harvests/sources/` | Engineering | Harvest #1/#5 source (provenance travels with the harvest). |
| 20 | `architecture/brain_storming/The 15-File Setup … Towards AI.pdf` | `engineering/knowledge/harvests/sources/` | Engineering | Harvest source (provenance). |
| 21 | `architecture/ai_architecture/mermaid_diagram.md` | `engineering/architecture/c4/2026-07-08-arb-diagram-draft-superseded.md` | Engineering (Historical record) | The ARB's platform diagram draft, superseded by the corrected views document (7 corrections applied there). Renamed to state its status. |

## 2. Stays — Runtime (never moves)

| Artifact | Why |
|---|---|
| `.claude/` — everything: `CLAUDE.md`, `CONTEXT.md`, `settings*`, `plans/`, `sessions/`, `scripts/` (hooks), `platform/registry.yaml`, `platform/OPERATING_INSTRUCTIONS.md`, `memory/`, `UI_GUIDELINES.md` | Provider-mandated mount point (like `.git/`). Hook-wired paths; adopted runtime assets AST-001..013. The live registry stays in the mount by design (composition root of the running adapter). |

## 3. Stays — Product (untouched by definition)

`app/`, `tests/`, `config/`, `database/`, `docs/` (product ADRs incl. ADR-T/UL/PL/PC/S/MP logs, knowledge EKP, implementation backlog, business, articles), `docs/architecture/c4/` product views, `architecture/` product workspaces (election, membership, committee, geography_contexts, brain_storming product files `ddd_brainstorming.md`/`bounding_contexs.md`/`ddd_definitions.md`/`page_structure*.md`, strategic, ui_design, …).

## 4. Stays — Shared / deferred (flagged, ARB decides later)

| Artifact | Owner | Recommendation |
|---|---|---|
| `docs/implementation/Implementation_Process_v1.0.md` / `v1.1_Draft.md` (EP-01/02/03, ER rules) | Shared | **Defer.** Engineering-owned content, but it governs every product ticket and is the pointer target of `.claude/CLAUDE.md` and dozens of records. Move (to `engineering/governance/process/`) belongs with the v1.1 ratification at the PB-004 retrospective, as one slice. |
| `developer_guide/ai_platform/` (5 guides) | Engineering | **Defer** (D2). Target `engineering/developer/guides/`; currently coupled to the dev-guide reminder hook's area mapping and mid-track DoD. Later slice together with the hook update. |
| `docs/knowledge/` (EKP, Knowledge Constitution, adjudication pilot) | Shared | **Stay.** Governance is engineering-flavored, but content is product-domain knowledge and it is wired to `npm run knowledge-lint` / `knowledge-graph`. |
| `architecture/knowledge_transfer/`, `architecture/how_far_we_are/` | Shared | Stay — program/product status material. |
| `docs/adr/README.md` | Shared | Stays; **edited** (living index): ADR-AIP class row becomes a pointer to `engineering/architecture/adr/`. |

## 5. Ambiguous — for ARB

| Artifact | Issue |
|---|---|
| `architecture/ai_architecture/CHILI Publisher Interface-2026-07-09-223209.png` | Unrelated screenshot (CHILI Publisher UI) sitting in an AI-architecture folder. Neither Product, Engineering, nor Runtime. Recommend the ARB removes or relocates it manually; EM-001 does not delete. After row 21 moves, `architecture/ai_architecture/` holds only this file. |

## 6. Reference updates (Phase 2 — living documents only; historical records are never edited)

| File | Update |
|---|---|
| `.claude/platform/registry.yaml` | Vocabulary/proposals path pointers → `engineering/architecture/proposals/` |
| `.claude/platform/OPERATING_INSTRUCTIONS.md` | Rulings-log path → `engineering/architecture/adr/ADR-AIP-LOG-…` |
| `.claude/CONTEXT.md` | Platform track line paths |
| `.claude/MEMORY.md` | Platform section paths |
| `.claude/plans/AIP-iteration-1-construction.md` | Paths the C3 cold session must resolve (living plan, not yet executed) |
| `developer_guide/ai_platform/*.md` | Path mentions (reading order, key-files tables) |
| `docs/adr/README.md` | ADR-AIP class row → pointer to `engineering/architecture/adr/` |
| **Never edited:** `.claude/sessions/*` (history), `claude/plans/*` (plan-mode artifacts, historical), sealed proposals, ADR bodies, verification report bodies | Paths inside historical records describe the world as it was — that is their job. The mapping old→new lives here. |

## 7. Verification — EXECUTED results (2026-07-10)

1. ✅ **Move commits:** `9ec8a8e1a` (ADRs) · `7d1a4433b` (sealed proposals) · `1a2fed76d` (C4 views) · `088ceb313` (verification reports) · `95b9a852f` (harvests). Each verified before commit.
2. ✅ **History:** `ADR-AIP-LOG` recorded as rename, 100% similarity. All other artifacts were **never previously committed** (verified per-file with `git ls-files --error-unmatch` after a reviewer challenge) — no history existed to lose; their first git record is at the canonical `engineering/` home.
3. ✅ **Seal integrity (R-30):** the six proposals verified byte-identical by sha1 before/after the move.
4. ✅ **Registry:** `registry.yaml` re-parsed after the vocabulary-path edit (symfony/yaml — VALID); the new vocabulary target confirmed to exist.
5. ✅ **Stale references:** repo-wide grep for all old paths → hits only in historical records (session logs, plan-mode artifacts, the original EM-001 plan matrix) — which are never edited, by rule.
6. ✅ **Hooks/runtime:** nothing under `.claude/` moved; only pointer values inside living runtime documents updated.
7. ✅ **Empty source folders removed** (`docs/architecture/proposals/`, `architecture/verification/`); `architecture/ai_architecture/` retained — holds only the ambiguous PNG (§5, awaiting ARB).
8. **Execution deviation from the original EM-001 plan (recorded, ARB-directed):** D1 overturned (ADR-AIP moved), `knowledge/research/` renamed to `knowledge/harvests/` (domain object, not activity), verification reports nested under `reports/`, no empty namespace folders created (reserved namespaces documented in `README.md` instead), incremental commit-per-move instead of one migration commit.
