# Architecture Knowledge Portal (AKB Index)

**Status:** 🧭 START HERE. The single entry point to the Architecture Knowledge Base. New architect or AI: read this first, then follow the path.
**AKB Principle 01 — evidence-driven:** certified artifacts beat memory/chat/notes. When memory and evidence disagree, **evidence wins.**
**Date:** 2026-06-27

## What is this project (one line)
A high-assurance, constitutionally-governed, anonymity-preserving online voting platform. Architecture style: **DDD-First Hybrid** (Strategic+Tactical DDD · Hexagonal · Clean layering · CQRS-light · event-driven · modular monolith). **Not** a CRUD app.

## Read first (the path)
1. **This portal.**
2. **Project Constitution & Knowledge Transfer Handbook v1.0** (`docs/architecture/`) — the Parts I–XX onboarding.
3. **Certified Strategic Architecture Landscape v1.0** (`docs/architecture/`) — *what the architecture is* (8 candidates → 5 confirmed BCs).
4. **Implementation Landscape v1.0** (`docs/implementation/`) — *how much is built* (status, roadmap, maturity).
5. **Architecture Baseline 1.1** (`docs/implementation/`) — the validated reference implementation pattern.
6. Then the tactical specs (Round 50-04…50-09) and the governance trio below.

## Authoritative & FROZEN (change only via versioned re-issue + review)
| Artifact | Path | Role |
|----------|------|------|
| Architecture Release 1.0 (+ 10 Principles) | `docs/architecture/design/Architecture_Release_1.0.md` | strategic baseline |
| BDR v1.1 | `docs/architecture/design/Round49-06_Boundary_Decision_Register.md` | 5 Confirmed BCs (immutable) |
| Certified Strategic Architecture Landscape v1.0 | `docs/architecture/Certified_Strategic_Architecture_Landscape_v1.0.md` | strategic map |
| Round 50-04…50-09 | `docs/architecture/design/` | events/policies/state/repo/verification |
| Implementation Architecture Constitution v1.0 | `docs/implementation/` | implementation governance |
| Coding Standard v1.0 · Package Structure v1.0 | `docs/implementation/` | how to write code |
| Canonical Event Catalog v1.0 | `docs/implementation/` | the 11 events |
| Architecture Baseline 1.1 | `docs/implementation/` | validated reference impl |
| ADR-T log | `docs/adr/ADR-T-LOG-Tactical-Implementation.md` | tactical ADRs |
| 38C-15 ARB Ruling · 38C-16 ARB Template | `docs/architecture/design/` | constitutional |

## LIVING (evolve continuously)
Implementation Landscape · Implementation Traceability Matrix · Architecture Debt Backlog · Greenfield Core Playbook · Readiness Audits · this Portal · the AKB index (`docs/architecture/Architecture_Knowledge_Base_v1.0.md`).

## What is implemented / what remains
- **Implemented (reference):** Contestation + Adjudication greenfield Core (Push A done, 50/50 green).
- **Operational (legacy, to migrate):** Evidence, Appointment, Voting.
- **Remaining:** Push B (Election reaction closes the loop) → strangler-migrate the 3 operational BCs → empirical eval → LIT-METHOD.
- Full status + roadmap: **Implementation Landscape v1.0**.

## Where things are
- **Roadmap:** Implementation Landscape v1.0 (`docs/implementation/`).
- **ADRs:** `docs/adr/` (domain ADR-001…008; tactical ADR-T log).
- **Strategic/DDD discovery:** `docs/architecture/design/` (Round 47–50) · `docs/architecture/discovery/` (Round 27–28) · `architecture/strategic/` (Round 6).
- **Code home:** `app/Contexts/<Context>/{Domain,Application,Infrastructure}`.
- **AKB structure (4 levels + lifecycle + maturity):** `docs/architecture/Architecture_Knowledge_Base_v1.0.md`.

## The rule
Every new Level-2+ document **links back into this structure** (parent/related/children) rather than existing in isolation. Frozen decisions change only via ADR + review.

---
*Architecture Knowledge Portal — START HERE. Cornerstone #3 (with Certified Strategic Architecture Landscape = "what" and Implementation Landscape = "how much built"). Reading path + authoritative/frozen vs living index + implemented/remaining + where-things-are. Evidence beats memory.*
