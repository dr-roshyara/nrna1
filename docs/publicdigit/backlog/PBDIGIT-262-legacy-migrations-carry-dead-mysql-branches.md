# PBDIGIT-262: legacy migrations carry MySQL branches that are dead under DB-1

**Type:** Technical debt · **Created:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared), on the PO's instruction. **Index row:** pending.

| | |
|---|---|
| **Status** | `OPEN — technical debt` · not authorised |
| **Evidence** | 5 migrations branch on `getDriverName() === 'mysql'`, e.g. `database/migrations/2026_04_03_155711_add_ended_status_to_members_table.php`, `2026_03_08_080218_add_expired_status_to_voter_slugs.php`, `2026_04_16_063404_add_index_to_organisation_users_table.php`, `2026_04_17_000001_add_organisation_fields_to_incomes_table.php`, `2026_04_26_add_committee_role_to_election_memberships.php` |
| **Risk** | low (never executed on PostgreSQL), but it misleads readers into assuming dual support |
| **Proposed handling** | leave executed migrations unchanged (history); document them as PostgreSQL-only; no new driver branches. Any removal needs care, because migrations are history |
