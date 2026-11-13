# 01: G-0, Identity's minimal Person and the platform-context person reference (PBDIGIT-346)

**Story:** `PBDIGIT-346` slice G-0 · **Design:** `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-standalone-tactical-completion.md` §5, §5a (D-226) · **Contract:** `docs/publicdigit/architecture/reviews/2026-11-13-identity-platform-person-reference-for-reference-geography.md` (D-225, IG-1 … IG-3)
**Status:** implemented with TDD (three RED → GREEN cycles). Evidence: `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g0/`.

## Purpose

Reference Geography's approval rule needs **two different people** for a structural map change (D-88). To check "different" it needs a stable, opaque reference per person, **for Geography only**, with no name, e-mail, organisation or internal id. G-0 builds that, and nothing more of Identity.

## Where it fits

| Layer | File | Role |
|---|---|---|
| Identity Domain | `app/Contexts/Identity/Domain/PersonId.php`, `AccountId.php`, `ContextPersonRef.php`, `PlatformContext.php` | UUID ids; the opaque 32-hex reference; the closed list of contexts (`reference-geography` only) |
| Identity Application | `ActingContextRef.php` (+ `ActingContextRefAnswer`, `ActingContextRefRefusal`, `IdentityUnavailable`) | IG-2: signed-in Account → active? → Person → reference; refused otherwise |
| Identity ports | `Application/Ports/CurrentAccount.php`, `AccountDirectory.php`, `ContextPersonRefs.php` | who is signed in; the Person of an active Account; the reference (no reverse lookup) |
| Identity Infrastructure | `PostgresAccountDirectory.php`, `PostgresContextPersonRefs.php`, `LaravelCurrentAccount.php` | PostgreSQL and the auth guard |
| Migration | `database/migrations/2026_11_13_100000_create_identity_minimal_persons_and_context_refs.php` | `identity_persons`, `identity_account_persons`, `identity_context_person_refs` (+ immutability trigger). **PostgreSQL only; release gate: test databases only until the Principal approves rollout** |
| Geography | `app/Contexts/ReferenceGeography/Domain/PersonReference.php`, `Application/Ports/ActingPerson.php`, `Application/ActingPersonRefused.php`, `Infrastructure/IdentityActingPerson.php` | Geography's own value and port; the single adapter onto Identity |

## Design decisions

- **A legacy `users` row is the Account** (I-TDD-02 §18). Its Person is created **lazily**, on the first `ActingContextRef` call (D-226 Q-G-2).
- **The 1:1 mapping is transitional, not proof of one human per account** (D-226 §5a). Two references mean "not known to be the same person". The only knowledge of sameness is Identity's recorded merges (IG-4/IG-5), whose procedure is deferred.
- **"Active" for a legacy row = not soft-deleted and e-mail verified.** The table has no suspension state.
- **One reference per (Person, context),** a random 128-bit value as 32 hex. Never reused or changed (a trigger refuses UPDATE/DELETE). **No reverse lookup exists** (no method takes a reference and returns anything else; architecture test).
- **First requests serialise** on a transaction-scoped advisory lock per account (`pg_advisory_xact_lock`), so the legacy `users` row is never locked.
- **No foreign key to `users`,** so legacy user deletion is unaffected. A deleted account keeps its Person and mapping, for accountability.
- **A merged Person answers with its survivor** (bounded chain). Nothing in G-0 writes `merged_into`; tests set it as a fixture.
- **Every database failure is `IdentityUnavailable`,** and the caller refuses (fail closed).
- **Geography never imports Identity** in its Domain or Application. Only `IdentityActingPerson` (Infrastructure) imports Identity's published service.

## How it works

```php
// Identity: the acting person's reference for one context
$answer = (new ActingContextRef($currentAccount, $accountDirectory, $contextPersonRefs))
    ->for(PlatformContext::ReferenceGeography);
$answer->isGranted();      // false: NotSignedIn | AccountNotActive | IdentityUnavailable
$answer->ref()->value();   // e.g. 'fedcba98…' (32 hex); throws LogicException if refused

// Geography: its own view
$person = (new IdentityActingPerson($actingContextRef))->current();   // PersonReference, or ActingPersonRefused
```

**Container wiring is not added yet:** the first consumer is G-3 (`StewardRoster`), which binds `ActingPerson` → `IdentityActingPerson` and the Identity ports to their adapters.

## Testing (executed 2026-11-13)

| Test | Kind | Result |
|---|---|---|
| `tests/Unit/Contexts/Identity/ActingContextRefTest.php` (8) | database-free | GREEN |
| `tests/Feature/Contexts/Identity/PostgresContextPersonRefsTest.php` (10) | isolated cluster, suite `IdentityPostgres` | GREEN (a 4-process race included) |
| `tests/Unit/Contexts/ReferenceGeography/IdentityActingPersonTest.php` (6) | database-free | GREEN |
| `tests/Architecture/IdentityG0ArchitectureTest.php` (5) | architecture, with planted violations | GREEN |

```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Identity tests/Unit/Contexts/ReferenceGeography tests/Architecture/IdentityG0ArchitectureTest.php
MEMBERSHIP_PG_IMAGE=docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822 bash scripts/testing/membership-pg.sh --testsuite IdentityPostgres
```

## Pitfalls

- **The cluster suite keeps data between tests** (migrated once per run). Scope every count to the test's own ids. The first GREEN attempt failed on exactly this, a test defect.
- **Never add a method that maps a `ContextPersonRef` back to a Person or Account.** The architecture test will fail, and it would break D-225.
- **Never present two references as "two verified people"** in any record or screen (D-226 §5a).
- **Don't add a context value** without a decision. The table's CHECK and the enum both list `reference-geography` only.

**Traceability:** PBDIGIT-346 G-0 · D-75, D-88, D-200 (I-TDD-02 §18), D-225 (IG-1 … IG-3, IG-P1 … IG-P4, IG-P8), D-226 (Q-G-2, §5a).
