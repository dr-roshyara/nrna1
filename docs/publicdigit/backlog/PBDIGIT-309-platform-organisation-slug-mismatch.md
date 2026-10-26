# PBDIGIT-309: The platform organisation has two slugs in the code

**Type:** Bug (multi-tenancy) · **Epic:** none · **Created:** 2026-10-26 · **Raised by:** found while analysing PBDIGIT-308.

| | |
|---|---|
| **Status** | `OPEN` · needs a PO decision on the canonical slug |
| **Classification** | **observed in code;** production effect **not verified** |
| **Scope** | platform organisation lookup and creation |

## Finding
| Where | Slug |
|---|---|
| `app/Traits/BelongsToTenant.php:54` (global scope) and `:77` (auto-fill `organisation_id` on create) | `platform` |
| `database/factories/OrganisationFactory.php:53` (platform state) | `platform` |
| `database/seeders/OrganisationSeeder.php:29` | `publicdigit` |
| `database/factories/UserFactory.php:42` | `publicdigit` |
| `app/Console/Commands/SetupPublicDemoElection.php:23` | `publicdigit` |

When a tenant model is created with no tenant context and no `organisation_id`, `BelongsToTenant` looks for slug `platform`. If only `publicdigit` exists, the lookup returns nothing and `organisation_id` stays NULL. Every tenant table has `organisation_id` NOT NULL, so the insert fails.

## Evidence
- `SingleElectionLoginFlowTest`: 8 tests fail with `null value in column "organisation_id" of relation "elections"` (`Election::create()` without an organisation, platform org created by `UserFactory` as `publicdigit`).

## Not verified
- The platform organisation's slug in production. The seeder suggests `publicdigit`, which would mean the fallback never works there.

## Decision needed (PO)
- Which slug is canonical? Then make every lookup and creation site use one constant.
- Should rows ever fall back to the platform organisation at all, or should a missing tenant context fail loudly? The project overview's "demo mode = `organisation_id` NULL" no longer matches the schema.
