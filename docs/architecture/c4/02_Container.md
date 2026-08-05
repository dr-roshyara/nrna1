# C2 — Containers

**Diagram:** [`plantuml/Container.puml`](plantuml/Container.puml) · **Derived from:** BDR 1.1 (modular monolith) · ADR-T1/T3 · phpunit.xml/bootstrap (verified) · routes/console.php scheduling

## Explanation
**This is NOT microservices.** Containers are runtime/deployment units: the Vue3+Inertia SPA, ONE Laravel application, queue worker, scheduler, PostgreSQL, Redis. All bounded contexts live INSIDE the Laravel container as modules under `app/Contexts/*`.

## Why bounded contexts are inside Laravel
BDR 1.1 chose a modular monolith: context isolation is enforced by package boundaries (fitness tests + Deptrac), ports & adapters, and the Canonical Event Catalog — not by network. This gives DDD isolation without distributed-system failure modes, at volumes (Blueprint §19: tens of challenges per election) where a network split would be pure cost.

## Assumptions
1. **PostgreSQL** is the database of record (phpunit.xml `DB_CONNECTION=pgsql`, verified). *Note: the project-root CLAUDE.md still says "MySQL/PostgreSQL" and "Laravel 9.x" — flagged as a doc inconsistency in README.*
2. **Redis** in production per Laravel convention (tests use array drivers) — marked ASSUMPTION on the diagram.
3. The **outbox is not a container** — it is `outbox_events` (table) + `OutboxEventProcessor` (relay, cron-invoked). Atomicity with aggregates (ADR-T1) REQUIRES it live in the same database.
