# PBDIGIT-261: database-engine defaults contradict DB-1 (PostgreSQL only)

**Type:** Technical debt · **Created:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared), on the PO's instruction ("record the remaining MySQL fallback … as separate technical-debt items; do not broaden Slice 1b"). **Index row:** pending.

| | |
|---|---|
| **Status** | `OPEN — technical debt` · not authorised |
| **Decision it contradicts** | DB-1: PostgreSQL is the sole production engine (PO, 2026-10-09; `docs/publicdigit/reviews/2026-10-09-membership-slice1b-contract.md` §1) |
| **Evidence** | `config/database.php:18` `'default' => env('DB_CONNECTION', 'mysql')` · `.env.example:15` `DB_CONNECTION=mysql` · `.ENVIRONMENT_CONFIG_MIGRATION.md:66` lists `mysql` as the default. CI (`.github/workflows/*`: `postgres:15`), `phpunit.xml` and `CLAUDE.md` use PostgreSQL |
| **Risk** | an environment without `DB_CONNECTION` silently falls back to MySQL. The Membership adapter fails fast (Slice 1b); other code would not |
| **Proposed fix** | default `pgsql` in config and `.env.example`; update the environment doc; verify the production `.env` (operator) |
