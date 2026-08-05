# Verifying hydrators and container-bound collaborators

**Step:** coverage closure, 2026-08-04 (alongside WP-4B and WP-4C-1) · **Status:** **implemented** — two production classes that no test had ever executed are now covered.

This guide is about **verification technique**, not new behaviour. Guides 06 and 07 tell you what the seam and `AdjudicationFailureDeclared` *do*; this one tells you **how to prove a class in this context actually runs**, and why the obvious check gives the wrong answer.

---

## The mistake this guide exists to prevent

> **"`grep -rl MyClass tests/` returns nothing, therefore `MyClass` is untested."**
>
> **That inference is wrong in this codebase, and acting on it wastes days.**

Adjudication resolves most collaborators **through the container**. A class named by no test is routinely executed anyway, because a test resolved something *above* it.

Applied to Adjudication's container-bound classes, five were named by no test. **Four were exercised indirectly. One was genuinely never run.**

| Class | Named by a test? | Actually executed? | Why |
|---|---|---|---|
| `TransactionalAdjudicationService` | no | **yes** | `AdjudicationServiceIntegrationTest:53` resolves `AdjudicationService::class` from the container, reaching the decorator |
| `LaravelTransactionManager` | no | **yes** | same resolution — it is the decorator's dependency |
| `UuidIdentityGenerator` | no | **yes** | same resolution |
| `ChallengeRoutedReactionHandler` | no | **yes** | `ChallengeRoutedConsumptionTest` drives it through `InboxHandlerRegistry` |
| **`CoordinatorIssuanceRequest`** | no | ⛔ **NO** | **every seam keystone substitutes a hand-rolled double for it** |

**So the check is not "is the class named?" but "does any test reach it?"** Resolve the entry point from the container and follow the wiring. Four unnecessary test files were avoided by doing that, and one real gap was found.

### Why the one gap sat exactly where it did

`ConcludeToIssuanceSeamTest` supplies its own `RequestsDeterminationIssuance` double **deliberately** — resolving the manager from the container drags in the outbox adapter, which fails on `TenantContext::require()`. The double was the right call for isolating the seam.

**But isolation achieved by doubling is exactly where production-path coverage goes missing.** The double stood in for the shipped adapter in *every* keystone, so the class bound at `AdjudicationServiceProvider` line 49 was never executed. **When you substitute a double for a shipped collaborator, that collaborator now needs its own test — the doubling is the signal.**

---

## Where it fits

```
tests/Unit/Contexts/Adjudication/
    Infrastructure/Issuance/CoordinatorIssuanceRequestTest.php     ← the shipped adapter, real collaborator
    Infrastructure/Outbox/AdjudicationExpiredHydratorTest.php      ← hydrator contract, WP-6's gap
    Process/ConcludeToIssuanceSeamTest.php                         ← the seam (guide 06), doubles the adapter
```

**Key files**

| File | Role |
|---|---|
| `tests/Unit/…/Issuance/CoordinatorIssuanceRequestTest.php` | 3 tests — delegation · **unchanged propagation** · no retry |
| `tests/Unit/…/Outbox/AdjudicationExpiredHydratorTest.php` | 8 tests — the hydrator contract pattern |
| `tests/Support/Adjudication/InMemoryDeterminationRepository.php` | real-collaborator substitute (exposes `count()`, `findByChallengeRef()`) |
| `tests/Support/Adjudication/InMemoryEventOutbox.php` | same, for the outbox port |

---

## Pattern 1 — testing an adapter that is supposed to add nothing

`CoordinatorIssuanceRequest`'s docblock says it *"adds nothing — no retry, no pre-check, no translation."* That reads like a delegation test not worth writing. **It is load-bearing.**

**R-84's §12 reconcile fires only if `DeterminationAlreadyIssued` reaches the requester *unchanged*.** An adapter that caught, wrapped, or retried on that refusal would leave the reconcile permanently unreachable — the seam would fail closed forever, silently, on exactly the crash model R-83 put in scope.

So the assertion is not "it delegates" but **"the refusal survives the trip with its payload intact"**:

```php
public function test_an_inv_b1_refusal_propagates_unchanged(): void
{
    $this->request()->request($this->command());   // first issuance succeeds

    $caught = null;
    try {
        $this->request()->request($this->command());   // INV-B1 must refuse
    } catch (\Throwable $e) {
        $caught = $e;
    }

    $this->assertInstanceOf(DeterminationAlreadyIssued::class, $caught,
        'the adapter must not translate or swallow INV-B1s refusal — R-84s reconcile depends on it');

    $this->assertSame('constitutional-council', $caught->existingIssuedByAuthority()->toString());
    $this->assertNotNull($caught->existingDeterminationId());
}
```

Note it catches `\Throwable`, not `DeterminationAlreadyIssued`. **Catching the expected type would pass vacuously if the adapter threw something else** — the point is to prove the type *and* the payload, so the type must be asserted, not assumed by the catch clause.

### The collaborator is real, not doubled

`CoordinatesAdjudication` is `final`. **Do not remove `final` to make it mockable** — construct it with the in-memory doubles the port tests already use:

```php
$this->coordinator = new CoordinatesAdjudication(
    $this->repo,          // InMemoryDeterminationRepository
    $this->outbox,        // InMemoryEventOutbox
    new class implements IdentityGenerator {
        public function next(): string { return 'corr-minted'; }
    },
);
```

**Double the ports, keep the collaborator.** That exercises the real collaboration rather than a mock's idea of it.

And *"no retry"* is asserted rather than trusted — a refused request must leave **exactly one** determination:

```php
$this->assertSame(1, $this->repo->count(),
    'INV-B1: exactly one determination per challenge, and the adapter adds no retry');
```

---

## Pattern 2 — the hydrator contract

Every outbox hydrator in this context gets the same eight-part treatment. `AdjudicationExpiredHydratorTest` is the reference; guide 07's `AdjudicationFailureDeclaredHydrator` follows it.

| # | Assert | Why |
|---|---|---|
| 1 | `eventType()` is the canonical name | the registry keys on it |
| 2 | a v1 payload reconstructs an **equal** event | the round-trip contract |
| 3 | the result carries **value objects, not strings** | ADR-T16: hydration reconstructs local VOs |
| 4 | **the event carries no verdict** | see below |
| 5 | an **unsupported** version throws | ADR-T5: version, never mutate |
| 6 | an **absent** version defaults to v1 and is accepted | a deliberate tolerance, pinned |
| 7 | each **missing required field** throws (`#[DataProvider]`) | fail closed, per field |
| 8 | unsupported version and missing field are **distinct** failures | one must not mask the other |

### Asserting an absence with reflection

`AdjudicationExpired` must never grow an outcome or legitimacy — **a timer concludes nothing** (Constitutional Policy 4). That is enforced structurally:

```php
public function test_the_event_carries_no_verdict(): void
{
    $reflection = new \ReflectionClass(AdjudicationExpired::class);
    // …
    $this->assertSame(['challengeRef', 'expiredAt'], $properties);
}
```

**This test fails when someone *adds* a field**, which is the point. If you have a legitimate reason to extend the event, the constitutional question comes first — the failing assertion is the gate, not an obstacle.

### Pin deliberate tolerances

The shipped hydrator reads `$payload['schema_version'] ?? 1`. That `?? 1` is a **choice**, so it is asserted:

```php
public function test_an_absent_schema_version_defaults_to_v1_and_is_accepted(): void
{
    $payload = $this->payload();
    unset($payload['schema_version']);
    // … accepted
}
```

Without this test, a future author tightening the hydrator to *require* the field would see green and ship a behaviour change.

---

## Pitfalls

**⚠️ `outbox_events.aggregate_id` is a UUID column.** A readable fixture id (`ch-4c1-rt`) passes every unit test and then fails at the database with `invalid text representation … uuid`. `ChallengeRef::fromString()` does **not** enforce UUID shape, so nothing catches it earlier. Use real UUIDs in any test that touches persistence — **a fixture-only test cannot surface this.**

**⚠️ `#[DataProvider]`, not `@dataProvider`.** The doc-comment form is deprecated in PHPUnit 11 and removed in 12. It emits a deprecation while the gate still reports PASS — **a green gate is not a clean one**; read the deprecation lines.

**⚠️ Don't resolve the process manager from the container in seam tests.** It drags in the outbox adapter and fails on `TenantContext::require()`. Keep the double there and test the adapter separately — that is exactly the split these two files represent.

**⚠️ Zero matches is a result, not an error.** `grep -c` exits non-zero on no matches, which silently kills a `&&` chain and can make an unverified check look like a passed one.

---

## ⛔ An open finding this guide does not fix

`RequestsDeterminationIssuance` is bound to a **bare `CoordinatesAdjudication`**, so the determination write and the outbox enqueue are **two independent writes**. A crash between them leaves a determination **issued but never announced** — a failure mode no adopted crash model covers.

**This is recorded evidence, not repaired code.** It is latent today because no production caller exists — WP-4D is unbuilt. Determinations D-1…D-4 sit with the Decision Authority (`engineering/verification/reports/2026-08-04-decision-authority-adoption-package-seam-transaction-boundary.md`). **Do not wire a production caller before that disposition.**

---

## Testing

```bash
vendor/bin/phpunit tests/Unit/Contexts/Adjudication/Infrastructure/Issuance/CoordinatorIssuanceRequestTest.php
vendor/bin/phpunit tests/Unit/Contexts/Adjudication/Infrastructure/Outbox/
composer merge-gate     # architecture fitness → Deptrac → greenfield PHPStan → widened regression
```

Run the targeted files, not the full suite — the whole suite is slow enough to be its own obstacle.

---

**Traceability:** R-84 (§12 reconcile depends on unchanged propagation) · R-83 (crash models) · R-72 · INV-B1 · ADR-T1 (the adapter exists so the manager does not hold the issuance service) · ADR-T5 (version, never mutate) · ADR-T16 (local VO reconstruction) · Constitutional Policy 4 (a timer concludes nothing) · EPIC-004K §11 · §12 · §197 · WP-6 · `AdjudicationServiceProvider:49` · guides [06](./06_conclude_to_issue_seam.md) and [07](./07_adjudication_failure_declared.md)
