# PBDIGIT-301: 12 Composer security advisories remain after the Laravel 12 upgrade

**Type:** Maintenance / dependency security · **Epic:** none · **Created:** 2026-10-16 · **Raised by:** Claude Code session (self-declared, not attestable), from the Laravel 12 upgrade (ADR `ADR_20261015_2346_Laravel_12_And_Excel_4_Upgrade.md`, finding F-3). **Index row:** added 2026-10-16.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · creation approved by the PO 2026-10-16 |
| **Classification** | **observed:** `composer audit` 2026-10-16. The upgrade reduced advisories from 65 (17 packages) to 12 (5 packages) |
| **Scope** | targeted updates of the five packages below; no unscoped `composer update` |

## Finding

| Package | Advisories (CVE) | Runtime or dev |
|---|---|---|
| `firebase/php-jwt` | CVE-2025-45769 (weak encryption, fixed in ≥ 7.0) | runtime; check which package pulls it (`composer why`) |
| `phpseclib/phpseclib` | CVE-2026-32935, -40194, -44167, -55599, -84308 | runtime; check the dependent package |
| `phpunit/phpunit` | CVE-2026-24765 (PHPT coverage deserialization; fixed in 11.5.50) | dev only |
| `psy/psysh` | CVE-2026-25129 (CWD `.psysh.php` auto-load) | dev (via tinker) |
| `symfony/yaml` | CVE-2026-45133, -45304, -45305, -109413 (parser DoS) | check whether runtime |

## Proposed direction (nothing decided)
1. Run `composer why` for each package to find its owner and whether it is runtime or dev.
2. Plan a targeted `composer update <pkgs> --with-all-dependencies` with a dry run first. Same method as `developer_guide/framework_upgrade/`.
3. Compare the full suite against a baseline, as in the upgrade.

## Acceptance criteria
1. `composer audit` reports no advisory for the five packages, or each remaining one is recorded with a reason.
2. No PASS → FAIL against the pre-change baseline.
