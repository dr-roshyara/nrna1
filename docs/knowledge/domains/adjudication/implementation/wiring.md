---
knowledge_id: ADJ-IMPL-WIRING
title: Adjudication — Implementation & Wiring
knowledge_type: implementation
bounded_context: adjudication
status: approved
authority: authoritative
audience: [developer, ai]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [adjudication, infrastructure, di, wiring]
related_to: [ADJ-README]
implements: []
adr: []
code_refs:
  - app/Contexts/Adjudication/Infrastructure/Providers/AdjudicationServiceProvider.php
  - app/Contexts/Adjudication/Application/Service/TransactionalAdjudicationService.php
---

# Adjudication — Implementation & Wiring

> How the hexagonal pieces are bound. Source: [`AdjudicationServiceProvider.php`](../../../../../app/Contexts/Adjudication/Infrastructure/Providers/AdjudicationServiceProvider.php).

## Ports → adapters (bound in the service provider)

| Port (Application) | Adapter (Infrastructure) |
|---|---|
| `IdentityGenerator` | `UuidIdentityGenerator` |
| `TransactionManager` | `LaravelTransactionManager` |
| `EventOutbox` | `OutboxEventAdapter` |
| `DeterminationRepository` (Domain) | `EloquentDeterminationRepository` |

## Service composition

`AdjudicationService` is a **transactional decorator** over the frozen `CoordinatesAdjudication` coordinator:

```
TransactionalAdjudicationService( CoordinatesAdjudication(repo, outbox), txManager )
```

The coordinator does the domain work; the decorator wraps it in a transaction so event release + persistence commit atomically.

## Persistence

- Eloquent read model: `DeterminationModel`; domain↔row mapping in `DeterminationMapper` (the aggregate stays persistence-ignorant).
- Migration (tenant-scoped): `Infrastructure/Database/Migrations/Tenant/2026_06_27_000001_create_determinations_table.php` — loaded via `boot()`.

## Layer discipline

Domain has zero Laravel imports; Application depends only on Domain + its own Ports; Infrastructure wires Laravel. Enforced by [`deptrac.yaml`](../../../../../deptrac.yaml).
