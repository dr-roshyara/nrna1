# Frontend Architecture Documentation

## Purpose

This directory documents frontend architectural decisions, discoveries, migrations, and governance for the NRNA Public Digit voting platform.

## Guiding Principle

> Architecture documentation explains WHY. Code explains HOW.

## Documentation Framework

```
architecture/frontend/
├── README.md                       ← This file
├── decisions/                      ← ADRs (Architectural Decision Records)
│   └── ADR-XXX-<title>.md
├── discoveries/                    ← Page analysis before refactoring
│   └── YYYYMMDD-<page>-analysis.md
├── migrations/                     ← Migration plans before implementation
│   └── YYYYMMDD-<page>-migration-plan.md
├── governance/                     ← Governance script documentation
│   └── *.md
└── pages/                          ← Per-page documentation
    └── <PageName>.md
```

## Architecture Lifecycle

Every architectural change follows this sequence:

```
Discovery
    ↓
Analysis Document
    ↓
ADR Decision (if new principle)
    ↓
Migration Plan
    ↓
Implementation
    ↓
Governance Validation
    ↓
Documentation Update
```

## Frontend Layers

```
Presentation Layer
├── Vue Pages (Pages/)
├── Components (Components/)
└── Layouts (Layouts/)

Application Layer
├── Composables (Composables/)
├── Action Services (useElectionActions)
└── UseCases (Application/ — future)

Domain Layer
├── ElectionPhaseService
├── Domain Policies
└── Domain Rules

Infrastructure Layer
├── Inertia Router
├── API Calls
└── Browser Storage
```

## Existing Frontend Architecture

Current reusable artifacts discovered in codebase:

| Artifact | Location | Layer | Status |
|----------|----------|-------|--------|
| `ElectionPhaseService` | `Domain/Election/` | Domain | Production |
| `useElectionCapabilities` | `Composables/` | Application | Production |
| `useElectionActions` | `Composables/` | Application | Production (unused) |
| `ElectionActions` | `Constants/` | Domain | Production |
| `ElectionLifecycleStates` | `Constants/` | Domain | Production (generated) |
| `StateMachineContract` | `types/` | Domain | Production |

**Rule:** Reuse before creating new abstractions.

## Documentation Quality Rules

Architectural documents must:
- Explain **WHY**, not just WHAT
- Reference related ADRs
- Include affected files
- Include risks
- Include rollback strategy

Architectural documents must NOT:
- Duplicate code
- Become implementation notes
- Replace ADRs

## Refactoring Principles

1. **No Big Bang Rewrite** — Existing code preserved. Incremental change only.
2. **Boy Scout Rule** — Leave code better than you found it.
3. **Reuse Before Create** — Use existing abstractions before creating new ones.
4. **Incremental DDD Adoption** — DDD applied to new/refactored code only.
5. **Governance Before Merge** — Scripts must pass before increments are complete.
6. **Architecture Documents Are Mandatory** — No refactoring is complete without docs.

## Governance Layers

| Layer | Script | Enforced |
|-------|--------|----------|
| UI Design System | `design-check.sh`, `component-audit.sh` | Blocking |
| Security | `check_roles.php` | Blocking |
| Domain Purity | `check-domain-purity.sh` | Warning |
| Structure | `structure-check.sh` | Warning |

## Phase Status

| Phase | Status | Outcome |
|-------|--------|---------|
| Frontend DDD Discovery Phase 1 | ✅ **Complete** | See [phase1-summary](discoveries/20260613-phase1-summary.md) |
| Backend Domain Discovery | 🔜 **Next** | — |

## Architecture Decisions

- [ADR-001: Reuse Before Create](decisions/ADR-001-Reuse-Before-Create.md) — Before introducing new frontend abstractions, existing domain services, composables, and application services must be evaluated and reused when appropriate.
- [ADR-002: Incremental Frontend DDD Adoption](decisions/ADR-002-Incremental-Frontend-DDD-Adoption.md) — Adopt DDD for future development only; preserve existing investment.

## Milestones

| Date | Milestone | Artifacts |
|------|-----------|-----------|
| 2026-06-13 | **Frontend DDD Increment 1** — First production use of Domain/ layer in frontend. Extracted `ElectionApprovalPolicy` from inline `voterCount <= 40` check in Management.vue. Established ADR framework, discovery process, migration planning workflow, and governance validation. | `ADR-001`, `ADR-002`, `ElectionApprovalPolicy.ts`, `election-management-analysis.md`, `election-actions-assessment.md`, `election-management-increment-1.md`, `pages/ElectionManagement.md` |
| 2026-06-13 | **Management.vue discovery complete** — PhaseService Assessment (no extraction justified) + handleDatesUpdated Assessment (no extraction justified). Most of the page is presentation and orchestration, not domain logic. Moving discovery to next high-business-value page. | `phase-service-assessment.md`, `handleDatesUpdated-assessment.md` |
| 2026-06-13 | **Candidacy/Apply.vue discovery complete** — 1076 lines but only 106 lines script (10%), the rest is CSS. All rules are presentation-layer UX guards. No extraction justified. Moving to Vote/CreateVote.vue. | `candidacy-apply-assessment.md` |
| 2026-06-13 | **Vote/CreateVote.vue discovery complete** — contains 19 hardcoded post IDs with selection limits. However, backend `posts` table already owns this data. ADR-001 prohibits frontend Domain extraction. The correct fix is backend-driven: send post definitions from API. | `create-vote-assessment.md`, `election-post-source-analysis.md` |

## Current Discoveries

- [Election Actions Assessment](discoveries/20260613-election-actions-assessment.md) — Comparison of Management.vue orchestration patterns vs existing `useElectionActions` composable. Gaps, risks, and migration feasibility.
