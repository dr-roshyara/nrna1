# Slice 2 mechanism evaluation: evidence (2026-11-12)

- **`stale-connection.php` / `.out`:** a read-only probe on two throwaway SQLite files. It boots the app and changes no repository file or database.
  - Run: `php stale-connection.php <repo root> <empty scratch dir>`.
  - It shows that a held `Connection` follows a re-pointed shared name (pattern 1), but stays on its own organisation with per-organisation names (pattern 2).
- **`failer-probe.php`:** the failed-job runtime probe from delta §3.10.1.
- **Spatie facts:** read from the package's own sources (packagist, `composer.json`, `CHANGELOG.md`, `config/multitenancy.php`, `src/Tasks/SwitchTenantDatabaseTask.php`, `src/Actions/MakeQueueTenantAwareAction.php`, `src/Commands/TenantsArtisanCommand.php`) and its v4 docs, on 2026-11-12. The package is **not installed**.
