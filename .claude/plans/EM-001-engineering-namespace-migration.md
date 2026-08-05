# Plan — EM-001: Separate Engineering Platform from Product (Phase 1, documentation only)

**Created:** 2026-07-10 · **Status:** ✅ CLOSED — declared finished by ARB verdict 2026-07-10 ("I would not touch the folder structure again until after C3, PB-004, and the retrospective"). EXECUTED 2026-07-10 — authoritative record: `engineering/MIGRATION_REPORT.md` (the matrix below is the original proposal, kept as history; ARB amendments during execution: D1 overturned — ADR-AIP moved; `knowledge/patterns/` (post-review rename from `harvests/`) not `research/`; no empty namespace folders — create a folder only when its first artifact arrives; incremental move→verify→commit loop). Entry point: `engineering/README.md`. Post-review renames (ARB 2026-07-10): `proposals/`→`baseline/`, `harvests/`→`patterns/`.
**ARB amendments:** D1 overturned — **ADR-AIP files MOVE** to `engineering/architecture/adr/` (product ADRs and engineering ADRs are different decision classes; `docs/adr/` keeps product only + pointer) · `.claude/` confirmed untouched in its entirety (runtime mount point, like `.git/` — incl. platform/, plans/, sessions/) · **no plans/sessions/runtime under `engineering/`** (those are runtime artifacts, they live in the mount) · target tree: `engineering/{architecture/{adr,c4,proposals},governance,knowledge/research,verification,developer(reserved)}` · three-concern rule: `docs/` = how PublicDigit works · `engineering/` = how PublicDigit is engineered · `.claude/` = how the current runtime executes that engineering. D2 (developer_guide/ai_platform): folder reserved, move deferred (dev-guide reminder area mapping) unless ARB overrides. D3: proceed — moves touch zero product files and zero files under active PB-006 edit.
**Objective (ARB):** the repository structure reflects the domain model — `engineering/` (how PublicDigit is engineered) separated from product documentation (what PublicDigit is). Classify first, then move; every move justified; product untouched.

## Scope decision (from ERR constraints — narrows the ARB sketch)

**IN (Phase 1, this migration): documentation artifacts only.**
**OUT (deferred to the post-PB-004 retrospective bundle, per prior ARB parking + hook coupling):** everything under `.claude/` (provider-mandated mount point — settings, hooks, CONTEXT, plans, sessions, platform registry: all hook-wired; moving them = modifying adopted runtime assets AST-001..013, its own approved slice) · `developer_guide/` split (active DoD target of the running PB-006 track — moving it mid-ticket breaks the dev-guide reminder's area mapping) · any `docs/` product content (untouched by definition).

## Classification matrix (Phase-1 artifacts)

| Artifact | Owner | Move? | New path |
|---|---|---|---|
| `docs/architecture/proposals/ai-platform/` (6 sealed Phase docs) | Engineering | ✅ | `engineering/architecture/proposals/` *(sealed docs move as-is — a move is not an edit; seal note verified intact)* |
| `docs/architecture/c4/AI_Engineering_Platform_Views.md` | Engineering | ✅ | `engineering/architecture/c4/AI_Engineering_Platform_Views.md` |
| `docs/adr/ADR-AIP-01/-02/-LOG` | Engineering | ⚠ **Recommend NOT moving** | ADR corpus unity: `docs/adr/README.md` is the single classified ADR index (ADR-S/UL/PL/PC/T/AIP); splitting the corpus breaks the one-index rule. Alternative: keep in `docs/adr/`, add `engineering/architecture/adr/README.md` pointer. ARB decides. |
| `architecture/brain_storming/engineering_pattern_cards_*.md` (3), `knowledge_architecture_validation.md` | Engineering | ✅ | `engineering/knowledge/research/` *(rename `brain_storming` semantics per ARB: research/)* |
| `architecture/brain_storming/` product files (ddd_brainstorming, bounding_contexs, page_structure…) + source PDFs | Product/Shared | ❌ | stay (product thinking + harvest provenance PDFs stay beside… **PDFs move WITH the cards** — provenance: ✅ `engineering/knowledge/research/sources/`) |
| `architecture/verification/` (2 reports + related) | Engineering | ✅ | `engineering/architecture/verification/` |
| `developer_guide/ai_platform/` (4 guides) | Engineering | ⚠ **Recommend deferring** | dev-guide reminder + Guide-00 reading order reference these paths; move belongs with the runtime bundle. ARB decides. |
| `architecture/knowledge_transfer/`, `architecture/how_far_we_are/` | Shared/Product | ❌ recommendation only | KT covers the product program; stays |
| Election/product ADRs, docs, c4 puml, business docs, app/, tests/ | Product | ❌ | untouched, by definition |

## Execution steps (after approval)

1. `git mv` the ✅ rows (history-preserving; no content edits — sealed docs stay byte-identical).
2. **Reference sweep:** grep all moved paths across the repo (guides, registry notes, session logs are historical — historical logs are NOT edited; only *living* references updated: Guide 00 reading order, dossier header, c4 cross-refs, CONTEXT track line). Broken-link check re-run.
3. `engineering/README.md` (one page: what lives here vs product docs — the namespace's own Guide-00 pointer).
4. Migration report appended to this plan: moved / unchanged / ambiguous-for-ARB, per the EM-001 objective.
5. Session log entry; commit as one migration commit (`docs(engineering): EM-001 …`).

## Verification

Every ✅ row moved with `git mv` (history intact) · zero broken living references (grep for old paths = only historical logs) · sealed proposals byte-identical (`git diff --stat` shows renames only) · hooks still fire (nothing under `.claude/` touched) · parallel-track files untouched.

## Open ARB decisions (blocking approval)

D1: ADR-AIP files — move or keep corpus unity with a pointer? (Recommend: keep + pointer.)
D2: `developer_guide/ai_platform/` — move now or with the runtime bundle? (Recommend: defer.)
D3: Timing vs the active PB-006 session — execute in a quiet window or immediately? (Recommend: quiet window; the moves touch no product files, but one migration commit mid-track is cleanest coordinated.)
