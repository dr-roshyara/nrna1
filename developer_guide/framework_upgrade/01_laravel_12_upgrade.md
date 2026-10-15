# 01 — Laravel 12 and Laravel-Excel 4 upgrade

**Branch:** `feature/laravel-12-upgrade` · **Date:** 2026-10-15/16 · **Decision:** `docs/publicdigit/adr/ADR_20261015_2346_Laravel_12_And_Excel_4_Upgrade.md` · **Ticket:** none (maintenance)

## Purpose
- Move the framework to Laravel 12.
- Move the spreadsheet stack to Laravel-Excel 4.x (PhpSpreadsheet 5) for the PHP 8.5 runtime.
- Make no Domain-layer change.

## Where it fits
- **Infrastructure only:** `composer.json` / `composer.lock`, plus one Infrastructure class (`app/Imports/OrganisationUserImport.php`).
- No bounded-context Domain or Application code changed.

## Key files

| File | Change |
|---|---|
| `composer.json` | `php` `^8.2` → `^8.3`; `laravel/framework` `^11.0` → `^12.0`; `maatwebsite/excel` `^3.1` → `^4.0` |
| `composer.lock` | targeted resolution (see below) |
| `app/Imports/OrganisationUserImport.php` | now also implements `Maatwebsite\Excel\Concerns\Import` |

## The update command

```bash
composer update laravel/framework maatwebsite/excel inertiajs/inertia-laravel \
  spatie/laravel-query-builder lcobucci/jwt --with-all-dependencies
```

Why the extra packages are named:
- **`--with-all-dependencies`** follows the dependencies *of* the named packages. It never follows the packages that depend *on* Laravel. Packages whose locked version blocked Laravel 12 therefore have to be named explicitly.
- **`inertiajs/inertia-laravel`:** v2.0.0 allows only `laravel/framework ^10|^11`. It resolves to v2.0.28, within `^2.0`.
- **`spatie/laravel-query-builder`:** 6.3.0 allows only `illuminate/* ^10|^11`. It resolves to 6.4.4, within `^6.0`, and pulls `spatie/laravel-package-tools` 1.18.3 → 1.93.3 along.
- **`lcobucci/jwt`:** 5.5.0 does not allow PHP 8.5 (this was already broken before the upgrade). It resolves to 5.6.1, within `^5.0`.

Resulting key versions:

| Package | Version |
|---|---|
| `laravel/framework` | **v12.70.0** |
| `maatwebsite/excel` | **4.0.4** |
| `phpoffice/phpspreadsheet` | **5.10.0** |
| `nesbot/carbon` | 3.14.2 |
| `symfony/mailer` | 7.4.19 |

- **Removed:** `ezyang/htmlpurifier` (only PhpSpreadsheet 1.x needed it; the app does not use it).

## What broke, and the fix

**Symptom:** import preview and processing returned HTTP 500 (19 import tests went PASS → FAIL):

```text
TypeError: Maatwebsite\Excel\Excel::toArray(): Argument #1 ($import) must be of type
Maatwebsite\Excel\Concerns\Import, App\Imports\OrganisationUserImport given
```

**Cause:**
- Laravel-Excel 4.x types its entry points against two new **marker interfaces**:
  - `toArray(Import $import, …)` and `import(Import $import, …)`;
  - `download(Export $export, …)` and `store(Export $export, …)`.
- Data concerns such as `FromArray`, `FromCollection`, `ToArray` and `ToModel` extend these markers. **`WithHeadingRow` does not.**
- A class that implements only `WithHeadingRow` (as ours did, because it is used solely through `Excel::toArray`) is therefore rejected. The upstream UPGRADE-4.x.md does not mention this.

**Fix** (the smallest change; an empty marker adds no behaviour):

```php
use Maatwebsite\Excel\Concerns\Import;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OrganisationUserImport implements Import, WithHeadingRow
```

**Exports needed no change.** The anonymous export classes in `OrganisationUserImportService` and `ParticipantImportService` implement `FromArray`, which already extends `Export`.

## How to add a new import or export (4.x rules)
- **Import used with `Excel::toArray` / `Excel::import`:** implement `Concerns\Import`, or a data concern that extends it (`ToArray`, `ToCollection`, `ToModel`).
- **Export:** implement a `From*` concern (they extend `Export`).
- **Native return types** are required on every concern method, for example `headingRow(): int`, `array(): array`, `headings(): array`. A method without one is a fatal error under 4.x.

## Other Laravel 12 behaviour to know

| Change | Effect here |
|---|---|
| The `image` validation rule rejects SVG | **Accepted** (PO 2026-10-16): SVG uploads to `StoreImage`, the organisation logo and the election logo are now refused. Use `image:allow_svg` only with sanitising. Audit of stored SVGs: **PBDIGIT-299** |
| `HasUuids` → UUIDv7 | Same 36-character format. Vote and result IDs were already time-ordered (`orderedUuid`) on Laravel 11; UUIDv7 stores Unix **milliseconds** in the first 48 bits. The anonymity characterization test K7 (`BallotAnonymityCharacterizationTest`) now decodes both formats via the UUID version digit. The gap is **open**: **PBDIGIT-300** (ADR finding F-1) |
| Query builder generics | PHPStan (without Larastan) now infers `->first()` on `$model->newQuery()` as `stdClass\|null`. **One known false positive:** `EloquentAdjudicationProcessStore.php:53`. Runtime is unchanged; recorded in the ADR and not "fixed" |
| `local` disk default root | Unaffected: `config/filesystems.php` sets `root` explicitly |

## Testing (how this upgrade was verified)
- Per-test-case comparison of the JUnit logs: Laravel 11 baseline against Laravel 12 with the fix.
- PHPStan, deptrac, tenant gate and `composer audit` before and after.
- Results: see the ADR §5 evidence and the upgrade report.

## Pitfalls
- **The upgrade guide is not the source of truth.** The `Import` marker requirement was found only by tests and by reading `vendor/maatwebsite/excel/src/Excel.php`.
- **Don't run `composer reinstall` to repair a missing package.** It refuses when `vendor/composer/installed.json` lists a package whose folder is gone (as happened with `qossmic/deptrac-shim`). An update or install from the lock recreates it.
- **The PHP floor is now 8.3.** Every runtime (CI and the production server) must be ≥ 8.3 **before** deploying.

## Traceability
ADR `ADR_20261015_2346_Laravel_12_And_Excel_4_Upgrade.md` (ACCEPTED 2026-10-16) · backlog PBDIGIT-299 (SVG audit), PBDIGIT-300 (vote-id timestamps), PBDIGIT-301 (remaining advisories), PBDIGIT-302 (mail TLS) · branch `feature/laravel-12-upgrade`.
