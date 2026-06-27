---
knowledge_id: RCP-IMPLEMENT-AGGREGATE
title: Recipe — Implement a Domain Aggregate
knowledge_type: recipe
bounded_context: global
status: approved
authority: authoritative
audience: [developer, architect, ai]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [ddd, aggregate, recipe, how-to]
related_to: [PKG-IMPLEMENT-AGGREGATE, RCP-CREATE-ADR]
requires: [META-NAMING]
---

# Recipe — Implement a Domain Aggregate

> You don't need to remember where everything is. Follow the chain. The reference implementation is the **Determination** aggregate (Adjudication).

## When to use

Adding a new aggregate root to a bounded context (complex business rules, invariants, state transitions). For simple CRUD, use Eloquent + Controller instead (see the decision tree in [`.claude/CLAUDE.md`](../../../../.claude/CLAUDE.md)).

## The chain

1. **Principle** — confirm the layer rules: Domain is pure PHP, no Laravel. [`.claude/CLAUDE.md` → Layer Rules](../../../../.claude/CLAUDE.md).
2. **Discovery** — capture events, invariants, boundary. Write a `ddd-discovery` doc in your `domains/<ctx>/discovery/`.
3. **ADR** — record the boundary decision. [Recipe: Create an ADR](create-adr.md).
4. **Model the aggregate** — private constructor, named constructors (`prepare`/`reconstitute`), guarded transitions that throw on illegal moves, record domain events, `pullEvents()`. Reference: [`Determination.php`](../../../../app/Contexts/Adjudication/Domain/Determination/Determination.php).
5. **State machine** — a backed enum with `isTerminal()`. Reference: [`DeterminationState.php`](../../../../app/Contexts/Adjudication/Domain/Determination/DeterminationState.php).
6. **Repository interface** in Domain; **Eloquent implementation + mapper** in Infrastructure. Reference: [`DeterminationRepository`](../../../../app/Contexts/Adjudication/Domain/Repository/DeterminationRepository.php) + [`EloquentDeterminationRepository`](../../../../app/Contexts/Adjudication/Infrastructure/Repositories/EloquentDeterminationRepository.php).
7. **Application service / command** — orchestrate; wrap in a transactional decorator. Reference: [`AdjudicationServiceProvider`](../../../../app/Contexts/Adjudication/Infrastructure/Providers/AdjudicationServiceProvider.php).
8. **Tests first (TDD)** — aggregate unit tests + integration. Reference: [`tests/Unit/Contexts/Adjudication`](../../../../tests/Unit/Contexts/Adjudication).
9. **Boundary check** — add the context to [`deptrac.yaml`](../../../../deptrac.yaml) and run it.
10. **Document** — fill the `domains/<ctx>/` folder using the [Adjudication pilot](../../domains/adjudication/README.md) as the template.

## AI shortcut

Load the [Implement-Aggregate package](../packages/implement-aggregate.yaml) — it bundles every doc above as a single context for the AI.

## Checklist

- [ ] Domain has zero Laravel imports
- [ ] All transitions guarded; illegal transitions throw with no mutation/event
- [ ] Events recorded and released via the outbox
- [ ] Repository interface in Domain, Eloquent in Infrastructure
- [ ] Tests written first and passing
- [ ] `deptrac` passes; context boundary respected
- [ ] `domains/<ctx>/` documented with knowledge cards
