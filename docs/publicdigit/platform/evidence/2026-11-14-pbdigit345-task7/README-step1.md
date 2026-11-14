# Evidence: PBDIGIT-345 task 7, step 1: T-19 characterisation on the untouched queue configuration (2026-11-14)

| File | Shows |
|---|---|
| `t7-01-t19-step1-untouched-config/` | T-19 (3 tests) **GREEN on the untouched configuration**; `queue-config.sha256` is `config/queue.php` at that moment (unchanged since HEAD) |
| `t7-02-t19-sensitivity-default-switched/`, `bootstrap-default-database.php` | the same tests with the default connection switched to `database` by a scratchpad bootstrap (no repository change): **all 3 fail**. T-19 detects a changed default |

**Limit:** the harness sets `QUEUE_CONNECTION=sync`, equal to `config/queue.php`'s default. A deployment whose environment sets another default is outside this characterisation; `.env` was not read.
