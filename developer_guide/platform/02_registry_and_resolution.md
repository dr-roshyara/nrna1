# 02: The registry and `ResolutionService` (PBDIGIT-345 task 2)

**Design:** `docs/publicdigit/architecture/reviews/2026-11-12-platform-organisation-routing-isolation-tactical-design.md` §2 row 1, §5 T-2 (ISO-2, D-193 Part 4, D-178 §13.6, D-222). **Evidence:** `docs/publicdigit/platform/evidence/2026-11-13-pbdigit345-task2/`.

## In business terms

**PublicDigit keeps one central list of the organisations it serves,** and for each one, where its own database lives.
- **Before any organisation-side work, the platform asks this list.**
- **The organisation is served only if it is fully set up (`operational`) and its database is up to date.** Otherwise the work is refused.
- **Where its database lives comes only from this list, never from anything a user sends.**
- **No two organisations can ever be pointed at the same database.**

## Where it fits

| Layer | Class | Role |
|---|---|---|
| Domain | `ServedOrganisation::snapshot()` / `fromSnapshot()`, `ServedOrganisationSnapshot` | the only way to and from storage; `fromSnapshot` refuses a state the lifecycle could never produce |
| Application | `Ports\ServedOrganisationRepository` (`find`, `add`, `save`) | the registry port |
| Application | **`ResolutionService::resolve(string $id): ResolvedOrganisation`** | refuses with `OrganisationNotRoutable` (`unknown`, `not-operational`, `schema-not-current`); a non-UUID id is `unknown` without a query |
| Infrastructure | `PostgresServedOrganisationRepository`, `platform_served_organisations` (central migration), `PlatformServiceProvider` | central storage; CHECKs mirror the invariants; **a unique (cluster, database) index** |

## How to use it

```php
try {
    $resolved = app(ResolutionService::class)->resolve($organisationIdFromTheTrustedSelection);
    // $resolved->organisationId, $resolved->locator (cluster, database, credentialRef: a REFERENCE, never a password)
} catch (OrganisationNotRoutable $e) {
    // $e->reason: Unknown | NotOperational | SchemaNotCurrent. Refuse; log content-free (ISO-10)
}
```

**Task 3 will wrap this** in the immutable `OrganisationContext` and its one named connection per organisation (D-222). **Until then, nothing calls it from HTTP or jobs.**

## Design decisions

- **Re-read at every call, never cached:** a failed migration takes effect at the next request (Q-5).
- **Fail closed on corrupt data:** a row the domain's value objects refuse throws `\UnexpectedValueException` on reading. It is never routed.
- **Defence in depth:** the database refuses what the domain refuses (a pending entry with a locator; operational without a locator; a partial grant), and **one database per organisation** is a unique index (D-222).
- **The refusal message names the reason, never a locator** (ISO-10).

## Testing

- `tests/Unit/Contexts/Platform/Domain/ServedOrganisationSnapshotTest.php`: round trips; impossible states refused.
- `tests/Unit/Contexts/Platform/Application/ResolutionServiceTest.php`: T-2 with an in-memory registry.
- `tests/Feature/Contexts/Platform/PostgresServedOrganisationRepositoryTest.php` (isolated cluster, `platform_central` only, `--testsuite PlatformPostgres`): round trips, add/save, T-2, a shared database refused, the database's own refusal, corrupt rows, production wiring.

## Pitfalls

- **Never resolve from a request value** (hostname, URL, header, body). Task 6 resolves only from the trusted selection.
- **The registry is central:** never put it in an organisation database.

**Traceability:** PBDIGIT-345 task 2 · T-2 · ISO-2 · D-193 Part 4 · D-178 §13.6 · D-222 · baseline §5.
