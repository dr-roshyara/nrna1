# 06: HTTP entry E-1 (selecting and entering an organisation), PBDIGIT-345 task 6

**Design:** platform routing tactical design §3.1 E-1, §3.2, failure row 2; D-178 ISO-1, ISO-2, ISO-3, ISO-7, ISO-10, §13.6; D-219 (2) shared hostname; D-160.
**Proofs:** T-1, T-15, T-10 part 3. **Evidence:** `docs/publicdigit/platform/evidence/2026-11-14-pbdigit345-task6/`.

## Business purpose

PublicDigit runs on **one shared hostname**, so the person has to say which organisation they want to work in.
- **After sign-in, they select one.** That choice is only a *request*.
- **Every request is checked again in full:**
  - the person is still signed in;
  - they are still bound to the organisation;
  - the organisation is still operational.

  Only then is the request connected to **that organisation's own database**, for this request only.
- **Anything doubtful is refused.** The selection is cleared, and no page is read from any database.

## Components

| Layer | Component | Role |
|---|---|---|
| Application | `EnterOrganisation` | `select(account, requestedId)` and `enter(account, selection, carriedIds)`; refusals as `OrganisationEntryRefused(EntryRefusal)` |
| Application port | `IdentityBindings` | `isBound(accountId, organisation)`: Identity's **current** binding (D-113); `BindingsUnavailable` → fail closed |
| Application port | `SignedInAccount` | the account signed in for this request |
| Infrastructure (delivery) | `Http\OrganisationEntry` (alias `organisation.entry`) | per-request middleware |
| Infrastructure (delivery) | `Http\SelectOrganisationController` | `POST /platform/organisations/select` (`platform.organisations.select`), body `organisation_id` |
| Infrastructure | `IdentitySignedInAccount` | `SignedInAccount` over Identity's `CurrentAccount` (the second, recorded Platform→Identity adapter) |
| Infrastructure | `NoIdentityBindingsYet` | **production** answer to `IdentityBindings` until Identity's binding store exists (PBDIGIT-349): **nobody is bound** |

## Flow (`enter`, on every organisation request)

```
signed in?                     no  → NotSignedIn
selection in the session?      no  → NoSelection        (key platform.organisation_selection; never the legacy key)
selection a UUID?              no  → MalformedId        (a forged or garbage session value)
ids carried by the request     differ → ConflictingId   (route organisation / organisation_id / organisationId,
  (route, body, header)                                   body organisation_id / organisationId, headers
                                                          X-Organisation-Id, X-Tenant-Id)
legacy organisation?           yes → LegacyOrganisation (D-160, L-2: the paths never share one)
currently bound? (Identity)    no  → NotBound           (revoked since selection: refused on the NEXT request)
registry: operational, current schema?  → Unknown / NotOperational / SchemaNotCurrent  (ISO-2: the locator from the registry only)
→ OrganisationConnectionFactory::establish → OrganisationContext bound in the container FOR THIS REQUEST
→ $next → finally: context unbound, connection released
```

**`select` checks the same things,** except the selection and carried-id steps. On success the session id is regenerated (no fixation) and the selection stored.

## Responses

| Case | HTTP | Selection |
|---|---|---|
| any `EntryRefusal` | **403** "Organisation access refused." | **cleared** |
| Identity unavailable, the organisation's database unreachable (`BindingsUnavailable`, `OrganisationRoutingRefused`, `QueryException`) | **503** | **kept**: an outage is not a refusal of the person (D-178 §13.6) |

**Logs** carry the refusal reason or the exception class only: never an id, a locator or a person (ISO-10).

## Using it for an organisation page (future tasks)

```php
Route::middleware(['web', 'auth', 'organisation.entry'])->group(function () {
    Route::get('/org/members', MembersController::class);   // read app(OrganisationContext::class)->connection
});
```

**Rules:**
1. **Never combine `organisation.entry` with the legacy `tenant` or `tenant.api`, or with the `session-api` group.** `OrganisationEntryHttpTest` checks every registered route.
2. **Never name a registry route parameter `{organisation}`.** The legacy app registers a global binder, `Route::model('organisation', Organisation::class)` in `routes/committee/committeeRoutes.php`. It would resolve the parameter in the legacy `organisations` table. The route check refuses that too. Use `{organisation_id}`; the entry compares it with the selection.
3. **Read the organisation's data only through `app(OrganisationContext::class)->connection`.** Never `DB::` (the default connection is central; `OrganisationRoutingDefaultConnectionTest`).
4. **The binding grants routing only (ISO-7).** Membership, roles and authority are separate checks inside the organisation.

## Why production refuses everyone today

**Bindings are Identity's** (I-TDD-02, accepted as D-200): created by claim redemption, which is not built.
- `NoIdentityBindingsYet` answers "not bound" for everyone, so no registry organisation is reachable over HTTP. This is fail closed by construction.
- **PBDIGIT-349** replaces it with Identity's adapter.
- **It must never be replaced by a lookup in the legacy `user_organisation_roles`** (D-160).

**The legacy path is unchanged.** Its `X-Tenant-Id` trust is a known legacy defect (PBDIGIT-266 item 5). The registry entry treats that header only as a conflicting id.

## Testing

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/Platform
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite PlatformPostgres --filter OrganisationEntryHttpTest
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Architecture/OrganisationEntryPathsTest.php
```

- **`EnterOrganisationTest` (unit):** every refusal reason; revoked after selection; another account; Identity unavailable.
- **`OrganisationEntryHttpTest` (isolated cluster, two real organisation databases):**
  - **T-1:** only the selected organisation's database; switching the id in route, body, header or legacy header is refused and clears the selection.
  - **T-15:** a selection not bound; revoked after selection; forged session values (not bound, malformed, not operational, another account, nobody signed in).
  - **T-10 part 3:** a legacy organisation refused; the legacy session key is no selection; the legacy `TenantContext` builds no context and opens no organisation connection; no route runs both entries; no registry route uses `{organisation}`.
  - **503** and the selection kept on an outage.
- **`OrganisationEntryPathsTest` (static):** Platform never uses the legacy resolvers or their keys; the legacy resolvers never use Platform.
- **`EntryAdaptersTest`:** the two production adapters.

**Isolated harness note:** `phpunit.membership-pg.xml` sets `APP_ROUTES_CACHE` to a file that never exists, so routes always come from the route files and never from a developer's local route cache.

**Traceability:** PBDIGIT-345 task 6 · E-1, §3.2 · D-160, D-178, D-219 · T-1, T-15, T-10 part 3 · PBDIGIT-349, PBDIGIT-266.
