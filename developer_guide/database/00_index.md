# Database (PostgreSQL) — developer guide index

**General database behaviour** that more than one context relies on. Context-specific designs stay in their own areas (for example `developer_guide/membership/`).

| # | Guide | Origin | Status |
|---|---|---|---|
| 01 | [PostgreSQL lock waits: `INSERT … ON CONFLICT`, `FOR UPDATE NOWAIT`, timeouts](01_postgresql_lock_waits.md) | `PBDIGIT-343` (Membership ledger clock) | 2026-11-12 |
| 02 | [Counters advanced by the insert, guard triggers, closing a parent row (the Reference Geography migrations)](02_insert_advances_counter_and_guard_triggers.md) | `PBDIGIT-346` (Reference Geography G-4) | 2026-11-13 |
| 03 | [Row-shape CHECKs and reference-kind triggers](03_row_shape_checks_and_reference_kind_triggers.md) | PBDIGIT-346 G-6c-1 | 2026-11-14 |
| 04 | [Central and organisation migrations; the central record of technical operations; `timestamp(0)` rounding](04_central_and_organisation_migrations_and_the_technical_operations_record.md) | `PBDIGIT-345` (tasks 4–11) | 2026-11-15 |
| 05 | [Migrations whose `down()` refuses: conditional and forward-only](05_migrations_whose_down_refuses.md) | `PBDIGIT-349` (Identity I-3, R-3) | 2026-11-16 |
