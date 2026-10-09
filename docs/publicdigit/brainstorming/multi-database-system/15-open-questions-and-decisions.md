# 15 — Open questions and decisions required

None of these can be derived from the repositories.

| # | Decision | Options | Recommendation | Who |
|---|---|---|---|---|
| D1 | **Cookie scope / SSO** | S1 host-only · S2 shared `.publicdigit.com` · S3 central login + per-host handshake | S1 now, S3 later | PO + Security |
| D2 | **Role of the apex `publicdigit.com`** | Platform hub (login, org directory, platform admin, redirects) vs marketing only | Platform hub + redirect org-less links to the tenant host | PO |
| D3 | **Ownership of the tenancy capability** | Infrastructure platform capability (`app/Infrastructure/Tenancy`) vs a new Platform bounded context | Infrastructure capability; `TenantContext` stays at `App\Services\TenantContext` | ARB |
| D4 | **NULL-organisation rows** (messages, images, uploads, calendars, events, demo_results, dead_letter_queue, geo units) | Platform-owned (→ platform tenant) / reference (central) / orphan (archive) | Classify per table with the data owners | PO |
| D5 | **Tables with no organisation key** (`governance_decisions`, `constitutional_committees`, `role_hierarchy`, `projection_rebuild_runs`) | Central reference / per tenant (add a key) / retire | Governance context owner decides | Governance owner |
| D6 | **Per-organisation DB credentials** | One app role · per-tenant role + secret store | One app role first | PO + Security |
| D7 | **File isolation** | Part of the guarantee (organisation-prefixed paths, migrate files) or not | Yes, for tenant documents | PO + Security |
| D8 | **`organisation_users` placement** | Tenant (recommended) or central | Tenant | Membership owner |
| D9 | **Hostname policy** | Slug-derived proposal + admin confirmation; reserved list; rename = redirect; custom domains later | As stated | PO |
| D10 | **Legacy session-driven voting routes** (`/election`, `/vote/*`, `/voter/start`) | Keep with a guarded fallback, or retire | Retire if unused; else allow-list | PO |
| D11 | **Asset / public file host** | Single asset host vs per-tenant host | Single asset host (files are public) | Ops |
| D12 | **Production data volume** | — | Needed to size the pilot and cutover | PO / Ops |
| D13 | **Shorter tenant URLs** (`nrna.publicdigit.com/elections`) | Keep the full paths / add aliases | Keep; revisit later | PO |
| D14 | **Spatie roles per organisation (teams)** | Global (today) / per org | Keep global | PO |
| D15 | **Election-window freeze policy** for migration | No migration between ReadyForVoting and ResultsPublished | As stated | PO |

## Engineering questions
| # | Question |
|---|---|
| E1 | Actual test isolation semantics on pgsql given `TestCase::beginDatabaseTransaction` returns early |
| E2 | Hidden `Context` round-trip under the real queue driver (validate in the PoC) |
| E3 | The 5 code-referenced tables with no migration: dead code or a missing migration? |
| E4 | Should the two unloaded context migration folders be loaded, merged or deleted? |
| E5 | Where does Google OAuth get its config today (no `services.google`)? Is Google login used in production? |
