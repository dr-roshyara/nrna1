# C4 Architecture Model — NRNA Governance Platform

**Status:** documentation only — these are architecture VIEWS derived from the frozen architecture, never new design. If a diagram and a frozen artifact ever disagree, the artifact wins and the diagram is corrected.
**Generated:** 2026-07-06 · per `architecture/audit_system/C4_Diagram_Generation_Prompt.md`
**Authority order:** Architecture Constitution → Strategic/Implementation Landscape → Push B Blueprint v1.0 → BDR 1.1 → ADR-T log → Event Catalog v1.0 → Round 50 → Package Structure → Coding Standard → Traceability Matrix.

## Documents

| Level | Document | Diagrams |
|-------|----------|----------|
| C1 System Context | `01_System_Context.md` | `SystemContext.puml` |
| C2 Containers | `02_Container.md` | `Container.puml` |
| C3 Components | `03_Component.md` | `Component_Contestation` · `Component_Adjudication` · `Component_Election` · `Component_Voting_Evidence_Appointment` |
| C4 Code | `04_Code.md` | `Code_Challenge` · `Code_Determination` |
| Runtime (project view) | `05_Runtime_Event_Flow.md` | `Runtime_CorrectionLoop` · `EventFlow` · `ContextMap` · `Hexagonal_Adjudication` |
| Deployment | `06_Deployment.md` | `Deployment.puml` |

Render: any PlantUML ≥1.2023 (C4 stdlib includes `<C4/C4_Context>` etc.), e.g. `plantuml docs/architecture/c4/plantuml/*.puml` or the VS Code PlantUML extension.

## Status legend used across diagrams
**IMPLEMENTED** (green) — exists in code, verified 2026-07-06 · **OPERATIONAL** (yellow) — legacy architecture, migration pending · **PLANNED** — approved design (Blueprint/IDD), awaiting its PB ticket · **DESIGNED** — frozen Round 50 model, no code.

## Architecture assumptions (consolidated)
1. PostgreSQL is the database of record; Redis in production by convention (tests: array drivers).
2. No external Identity Provider; no External Trust Anchor — the latter by constitutional ruling 38C-15 (Option B: single sovereign source + safeguards S-1..S-5).
3. Deployment shape (Linux/Nginx/PHP-FPM/Supervisor) is conventional — no frozen deployment doc exists.
4. Context-map pattern labels marked "(interp.)" are readings of Blueprint §10, not frozen decisions.

## Inconsistencies found (reported, NOT resolved here — prompt rule)
1. **Project-root `CLAUDE.md` is stale**: says "Laravel 9.x" and "MySQL/PostgreSQL"; the codebase is Laravel 11 + PostgreSQL (phpunit.xml, `.claude/CLAUDE.md` migration rules). Recommend updating the root CLAUDE.md tech-stack table.
2. **Push B/C label**: Round 50-07 labels `Adjudicated→Resolved` "Push C"; Blueprint v1.0 (later, ARB-frozen) scopes the full loop as Push B. Diagrams follow the Blueprint; recorded in Decision Log D-01.
3. **"Election" naming nuance**: the generation prompt asks for a `Component_Election` diagram; BDR classifies the lifecycle engine as a supporting capability, while the Catalog makes Election/Lifecycle the sole producer of `ElectionCorrectionApplied`. The diagram uses "Election/Lifecycle" and tags the existing core as legacy-operational.
4. **Prompt says MySQL nowhere / CLAUDE.md table says MySQL**: resolved in favor of PostgreSQL (verified config) — see #1.

## Recommendations for missing diagrams (future)
- `Component_Membership.puml` — operational context, worth documenting before its migration epic.
- `Code_*` diagrams for Vote/EvidenceEnvelope/Mandate — when their tickets reach Implemented.
- `Runtime_VotingFlow.puml` — the 5-step voter journey (codes → agreement → ballot → verify → complete).
- Governance & Trust view ("C6" per C4_Model_Recommendation.md) — trust roots, safeguards S-1..S-5, CIC/CAB — best authored after Governance Capability Discovery (38D) completes.
- Update cadence: diagrams touched by a PB ticket are updated in that ticket's documentation step (frozen doc hierarchy rule).
