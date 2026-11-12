# 01: The `ServedOrganisation` domain model (PBDIGIT-345, task 1)

**Story:** `PBDIGIT-345` · **Plan:** `docs/plans/20261112-1844-release-1-build-slice-2-platform-routing-and-isolation-plan.md` §6 task 1 · **Design:** D-193 Part 4, §3.2, §3.3, F-02, F-07; D-185; D-178 §13.6; delta revision 3 §3.7, §3.9
**Status:** implemented with TDD (two RED → GREEN cycles, plus a purity guard proved by planted violations). Evidence: `docs/publicdigit/platform/evidence/2026-11-12-pbdigit345-task1/`.

## Purpose

**A registry entry is not permission to serve.** `ServedOrganisation` says, in pure PHP, when PublicDigit may technically serve an organisation. It owns no members, units, authority or content, and none of its steps creates authority (D-193 §3.2).

## Where it fits

`app/Contexts/Platform/Domain`: the Domain layer. No Laravel, persistence, HTTP, queue, filesystem, clock or randomness. **Nothing uses it yet:** persistence and the resolver come in task 2.

| File | Kind | Role |
|---|---|---|
| `ServedOrganisation.php` | aggregate | lifecycle, invariants, `isRoutable()` |
| `PlatformStatus.php` | enum | `pending` · `provisioning` · `operational` (release 1) |
| `SchemaState.php` | enum | `current` · `migration-failed`; null until the first result is recorded |
| `ServiceOperationGrant.php` | value object | §3.3 evidence: terms version, date, accepting person, founding-document fingerprints |
| `CounterpartyVerification.php` | value object | D-175 evidence reference |
| `DatabaseLocator.php` | value object | cluster + database + **credential reference** (never a password) |
| `OperationalReadiness.php` | value object | L-1 facts + F-07 contact count, checked at the `operational` step |
| `LifecycleRefusal.php`, `ServedOrganisationRefused.php` | enum, `DomainException` | why a step was refused |

The organisation's identity is the shared **`TenantId`** (`app/Contexts/Shared/Domain/ValueObjects/TenantId.php`), reused, not duplicated.

## The lifecycle

```
register(TenantId) ──► pending ──startProvisioning()──► provisioning ──markOperational(readiness)──► operational
                        │  needs: recordCounterpartyVerified()       │  needs: assignLocator(), markSchemaCurrent(),
                        │         + recordServiceOperationGrant()     │         readiness complete
                        │  no technical operation                     │
                                                       routable  ⇔  operational  AND  schemaState = current
```

| Rule | Source | Refusal |
|---|---|---|
| provisioning only with a verified counterparty **and** a recorded grant | D-193 F-02, D-185, D-166, D-175 | `CounterpartyNotVerified`, `NoServiceOperationGrant` |
| no technical operation while pending (locator, schema state) | D-185 | `NoTechnicalOperationWhilePending` |
| grant and counterparty recorded once, only while pending | D-193 §3.3 | `GrantAlreadyRecorded`, `CounterpartyAlreadyVerified`, `NotPending` |
| locator assigned once, only during provisioning | ISO-2 | `LocatorAlreadyAssigned`, `NotProvisioning` |
| operational only from provisioning, with a locator, a current schema and complete readiness | F-07; delta §3.7 L-1 | `NotProvisioning`, `NoLocator`, `SchemaNotCurrent`, `NotReady` |
| a failed migration blocks routing and changes no status; a repair restores it | D-178 §13.6 | (state, not refusal) |

```php
$o = ServedOrganisation::register(TenantId::fromString($id));
$o->recordCounterpartyVerified(new CounterpartyVerification('agreement:…'));
$o->recordServiceOperationGrant(new ServiceOperationGrant('terms-2026-11', $acceptedAt, 'account:…', [$sha256]));
$o->startProvisioning();
$o->assignLocator(new DatabaseLocator('cluster-1', 'org_…', 'secret-store:org_…'));
$o->markSchemaCurrent();
$o->markOperational(new OperationalReadiness(ledgerSchemaPresent: true, identityRowMatches: true, ledgerClockRowPresent: true, activeAccountContacts: 1));
$o->isRoutable();   // true
```

## Design decisions and interpretations (reported to the Principal)

- **`TenantId` reused** instead of the plan's `OrganisationId` (canonical discovery; one identity type).
- **No `suspended` or `closed`:** a later release, with undecided triggers (D-193 §3.2).
- **Schema state is null before the first migration result.** The design names only `current` and `migration-failed`. Absence is not a third value, and it is never routable.
- **The `operational` step needs ≥ 1 active-account contact** (D-193 F-07). The slice 2 plan puts contacts out of scope; this is raised with the Principal, because task 5 must then supply at least the initial contact.
- **The domain states the rule; it does not gather facts.** `OperationalReadiness` is filled by the application and infrastructure layers (task 5).
- **Domain events** (`OrganisationRegistered`, `OrganisationOperational`, …; D-193 Part 4) are **not yet recorded.** They come with persistence and the central outbox in task 2.
- **Dates are received as values** (`\DateTimeImmutable` in the grant); the domain never reads a clock.

## Testing

```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Platform tests/Architecture/PlatformDomainPurityTest.php
```

| File | Proves |
|---|---|
| `tests/Unit/Contexts/Platform/Domain/PlatformValueObjectsTest.php` (12) | value-object evidence and validation |
| `tests/Unit/Contexts/Platform/Domain/ServedOrganisationTest.php` (9) | every lifecycle rule and refusal above |
| `tests/Architecture/PlatformDomainPurityTest.php` (5) | imports only itself + `TenantId`; no framework/IO/clock/randomness; all classes final; **the checks catch planted violations** |

## Pitfalls

- **Don't add a status to model health or a failed migration.** Health is an observation; migration failure is schema state.
- **Don't let a request or a caller set the locator.** Only provisioning assigns it, once (ISO-2).
- **Don't make `isRoutable()` depend on anything outside the aggregate.** Bindings (ISO-3) and reachability are checked by the resolver and the entry channels (tasks 2, 6).

**Traceability:** PBDIGIT-345 task 1 · D-166, D-173, D-175, D-178, D-185, D-193 (Part 4, §3.2, §3.3, F-02, F-07), D-219 (1), (4) · delta revision 3 §3.7, §3.9.
