---
knowledge_id: ADJ-TESTS
title: Adjudication — Test Map
knowledge_type: test
bounded_context: adjudication
status: approved
authority: authoritative
audience: [developer, ai]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [adjudication, testing, tdd]
related_to: [ADJ-MODEL-DETERMINATION]
verified_by: []
code_refs:
  - tests/Unit/Contexts/Adjudication
  - tests/Feature/Contexts/Adjudication
  - tests/Support/Adjudication
---

# Adjudication — Test Map

> What proves the Adjudication context works, and where it lives.

| Test | Covers |
|---|---|
| [`DeterminationTest.php`](../../../../../tests/Unit/Contexts/Adjudication/DeterminationTest.php) | Aggregate transitions, invariants, illegal-transition discipline |
| [`DeterminationIssuedTest.php`](../../../../../tests/Unit/Contexts/Adjudication/DeterminationIssuedTest.php) | The `DeterminationIssued` event payload |
| [`DeterminationValueObjectsTest.php`](../../../../../tests/Unit/Contexts/Adjudication/DeterminationValueObjectsTest.php) | Value-object validity guarantees |
| [`AdjudicationServiceTest.php`](../../../../../tests/Unit/Contexts/Adjudication/AdjudicationServiceTest.php) | Coordinator behaviour |
| [`AdjudicationServiceIntegrationTest.php`](../../../../../tests/Feature/Contexts/Adjudication/AdjudicationServiceIntegrationTest.php) | Transactional service + outbox end-to-end |

## Test doubles

In-memory adapters keep unit tests fast and pure:
- [`InMemoryDeterminationRepository`](../../../../../tests/Support/Adjudication/InMemoryDeterminationRepository.php)
- [`InMemoryEventOutbox`](../../../../../tests/Support/Adjudication/InMemoryEventOutbox.php)

## How to run

```bash
php artisan test --filter Adjudication   # uses RefreshDatabase (safe; see .claude/CLAUDE.md)
```
