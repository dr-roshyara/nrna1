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

## Extension (2026-11-02): INV-ORG-SCOPE-1 Stage 1 static investigation

**Source:** `docs/publicdigit/reviews/2026-11-02-inv-org-scope-1-stage1-static-investigation.md` §2 items 3–4 (static only).

**Answer in principle to the second question above:** INV-ORG-SCOPE-1 (principle approved by the PO, 2026-11-01; ARB ratification pending) requires that a missing or invalid Working Organisation **fails closed and never substitutes the default PublicDigit organisation**.

**What the fallback does today, on the read path too:**

| Deployed data | Effect of a request with no organisation context |
|---|---|
| (a) an organisation with slug `platform` exists | reads and writes are scoped to it |
| (b) it does not exist | the substituted id is `null`, and Laravel turns `where(col, null)` into `IS NULL` (`vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php:933-937`). Reads return **every row with no organisation**; writes fail on NOT NULL columns. |

Which path production takes depends on its data (unknown; OQ-S1-2).

**Existing fail-closed option:** `app/Models/Traits/ScopedByOrganisation.php:17-40` throws when the tenant context is null. **It is used by no model.**

**Still open:** the canonical slug (the first question). The demo organisation is to be separated per Decision C DC-R6.
