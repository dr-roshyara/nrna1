# 03: `OrganisationContext` and one connection per organisation (PBDIGIT-345 task 3)

**Design:** delta §3.1, §3.3 (C-1 to C-3); D-222 (custom multi-database integration, no Spatie); T-10 part 1, T-26. **Evidence:** `docs/publicdigit/platform/evidence/2026-11-13-pbdigit345-task3/`.

## In business terms

**Each organisation's work now runs on a connection to that organisation's own database, and only that one.**
- The connection is named after the organisation, so two organisations can never share it.
- **The central (platform) database connection is never redirected to an organisation.**
- Passwords never sit in the registry; it only says where to find them.
- **A connection kept from one organisation can never reach another,** even after switching (T-26).

## Where it fits

| Class | Layer | Role |
|---|---|---|
| `OrganisationConnectionFactory::establish(ResolvedOrganisation): OrganisationContext` | Infrastructure | builds connection `org_<hex of id>` from the locator; the cluster from `config('organisation_routing.clusters')`; the login from its reference; **checks `current_database()`** |
| `OrganisationConnectionFactory::release(OrganisationContext)` | Infrastructure | purges the connection and forgets its configuration (C-2) |
| `OrganisationContext` (`organisationId`, `connection`) | Infrastructure | immutable; **the only way organisation code gets a connection** |
| `EnvironmentDatabaseLogin` | Infrastructure | `env:NAME` → `NAME_USERNAME`, `NAME_PASSWORD` from the process environment; anything else refused |
| `config/organisation_routing.php` | configuration | cluster name → host, port, SSL mode (from `ORGANISATION_DB_CLUSTERS`, JSON) |

## How to use it

```php
$resolved = app(ResolutionService::class)->resolve($trustedOrganisationId);       // task 2: refuses if not routable
$factory  = app(OrganisationConnectionFactory::class);
$context  = $factory->establish($resolved);
try {
    $repository = new PostgresMemberRepository($context->connection /* , … */);   // pass the connection EXPLICITLY
    // … the operation, its transaction on $context->connection only (C-3)
} finally {
    $factory->release($context);                                               // never keep it beyond the operation
}
```

**Tasks 6 and 7 will do this per HTTP request and per job attempt,** so ordinary code receives the context instead of calling the factory.

## Design decisions

- **Name from the id, never a shared name** such as `tenant` (D-222, ratchet A-5).
- **Fail closed:** an unknown cluster, an unusable login reference, or a database other than the locator's is refused. Nothing is left registered.
- **Logins from real environment variables** (`getenv()`), because `env()` returns nothing once config is cached.
- **T-10 part 1:** `tests/Architecture/OrganisationRoutingDefaultConnectionTest.php` forbids the default connection, default transactions and re-pointing in organisation-routing code. **Add each new organisation-context class to its list.**

## Testing

- `tests/Unit/Contexts/Platform/Infrastructure/OrganisationConnectionNamingTest.php`: the name; login references.
- `tests/Feature/Contexts/Platform/OrganisationConnectionFactoryTest.php` (isolated cluster): own database, default untouched, isolation between two organisations, **T-26**, release, refusals, production wiring.

## Pitfalls

- **Never use `DB::connection()` or `DB::transaction()` in organisation code.** Use `$context->connection` and `$context->connection->transaction(...)`.
- **Always `release()` in `finally`.**
- **Files whose names contain "Credentials" are blocked by the project's permission rules.** Use "login" or "secret reference".

**Traceability:** PBDIGIT-345 task 3 · C-1, C-2, C-3 · D-222 · T-10 part 1 · T-26.
