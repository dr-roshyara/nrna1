# Round 49-07 — Architecture Migration Plan (Plan stage)

*(Renamed "Migration Plan" → **Architecture** Migration Plan: it migrates **architecture** — ownership, modules, dependencies — not merely files.)*

**Program:** NRNA DDD Trustworthiness Research Program · **Phase:** III (Strategic→Tactical Transition) · **Built against:** Architecture Release 1.0 (BDR v1.1)
**Status:** 📐 MIGRATION PLAN — the **Plan** stage of `Round49-03`'s Migration Decision (Decision → **Plan** → Execution → Verification). **No code moved.** Execution is gated on aggregate design (Round 50) + authorization.
**Date:** 2026-06-26

> **Architecture, not engineering.** This plan sequences *how* the confirmed BCs reach their authoritative modules; it executes nothing. **Migration execution happens AFTER Round 50 aggregate design** (so consistency/transactional boundaries are known) **and after explicit authorization** (live repo).

## Principle (from `Round49-03`)
Each **Confirmed** BC → exactly one authoritative module; current realization `app/Contexts/<BC>/{Domain,Application,Infrastructure}`. **Only Confirmed BCs get modules.** Downgraded candidates do **not**.

## Scope — what migrates vs builds vs stays

| BDR | Candidate | Action | Target module |
|-----|-----------|--------|---------------|
| BDR-01 | Evidence (Operational) | **Migrate** (consolidate from `Domain/Election/Replay` + `Security/Simplified`) | `app/Contexts/Evidence` |
| BDR-02 | Voting (Operational) | **Migrate** (from `Models/Vote`+`Domain/Voting`; Active-Record→aggregate at R50) | `app/Contexts/Voting` |
| BDR-03 | Appointment (Operational) | **Migrate/keep** (from `Contexts/Governance/Domain/Authority`) | `app/Contexts/Appointment` (or retain in Governance) |
| BDR-04 | Contestation (Greenfield) | **Build new** (post-aggregate-design) | `app/Contexts/Contestation` |
| BDR-05 | Adjudication (Greenfield) | **Build new** (election-determination; committee-arbitration stays separate) | `app/Contexts/Adjudication` |
| BDR-06 | Replay | **No module** — Application Capability over Evidence (lives in Evidence's Application layer) | — |
| BDR-07 | Authorization | **No module** — Domain Service (consumed across BCs) | — |
| BDR-08 | Lifecycle | **No module** — Supporting derivation over Election | — |
| BDR-09 | Audit | **No module** — Infrastructure/Platform | — |

## Sequence (strangler — order chosen for risk)
1. **Operational BCs, lowest behavioral risk first:** Evidence → Appointment → Voting. *(Voting last among operational — live election data + anonymity invariant = highest care.)* Each: tests-first → move → verify → legacy shim removed.
2. **Greenfield Core, after aggregate design (Round 50):** Adjudication + Contestation built fresh in `app/Contexts/*` (no migration; new code). Built together (they are the correction-loop pair).
3. **Non-BC clarifications** (no module, but explicit homes): Replay → Evidence/Application; Authorization → a shared Domain-Service location; Lifecycle → Election/Application; Audit → Infrastructure.

## Migration constitution (binding — from `Round49-03`)
(1) no behavior changes · (2) no semantic changes · (3) no public-API changes · (4) **tests before moves** · (5) incremental strangler (never big-bang) · (6) rollback possible · (7) **one BC at a time**.

## Exit criteria
Migration complete **iff** every Confirmed BC (BDR-01..05) has exactly one authoritative module **and** legacy implementations are removed; downgraded candidates have explicit non-BC homes.

## Architecture fitness tests (extend the existing `tests/Architecture/` suite)
- "exactly one module owns Evidence" · "exactly one module owns the Vote SoR" · "no cross-context write to another context's system-of-record" · "**no voter↔vote linkage**" (Anonymity, Q7) · "no `app/Domain/Election2`-style duplicate home". CI-enforced (Deptrac/PHPStan).

## Dependencies & ordering
- **Round 50 (Aggregate Discovery) precedes migration *execution*** — aggregates define the consistency/transactional boundaries each module must honor.
- **BDR-06 (Replay)** placement (Evidence/Application vs own capability) confirmed during Round 50.
- Governance items GI-1/GI-2 (parallel, → KRG) do not block migration of the Confirmed BCs.

## Status
**Plan only — no file moved.** Next stages: **Migration Execution** (per-BC, post-R50, authorized) → **Migration Verification** (fitness tests green; legacy removed).

---
*Round 49-07 — Migration Plan — ISSUED (Plan stage; no code moved).*
*Modules ONLY for 5 Confirmed BCs (Evidence/Voting/Appointment migrate; Adjudication/Contestation build greenfield post-R50). Replay/Authorization/Lifecycle/Audit = no module (capability/service/infra homes). Strangler order: Evidence→Appointment→Voting, then greenfield Core. Migration constitution + exit criteria + CI fitness tests. Execution gated on Round 50 aggregate design + authorization. Next: Round 50 Aggregate Discovery.*
