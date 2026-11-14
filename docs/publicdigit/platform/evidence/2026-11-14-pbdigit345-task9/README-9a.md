# Evidence: PBDIGIT-345 task 9a, the organisation outbox (2026-11-14)

| File | Shows |
|---|---|
| `t9a-01-red-pg/` | RED: `outbox_events` does not exist in the organisation database |
| `t9a-02-green-pg/` | the Platform PostgreSQL suite: **69 GREEN** (parity of columns, constraints and indexes with the central outbox; the one foreign key targets `organisation_identity`; another organisation's event refused by the database; the central platform database untouched; founding readiness now also requires this migration) |
| `t9a-03-db-free.txt` | Platform unit tests and the migration-set, ledger-isolation, ratchet, default-connection and queue guards: GREEN |
