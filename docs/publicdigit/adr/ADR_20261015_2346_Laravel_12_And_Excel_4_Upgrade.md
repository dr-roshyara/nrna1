# ADR: Upgrade the framework to Laravel 12 and the spreadsheet stack to Laravel-Excel 4.x

**Status: ✅ ACCEPTED by the PO on 2026-10-16 (EP-02),** on the evidence in §5.
- **Earlier status:** PROPOSED. Drafted during the approved upgrade plan (EP-01 approval by the PO on 2026-10-15).
- **Accepts the upgrade on the branch only.** Production deployment needs its own authorisation and runbook. Precondition: PHP ≥ 8.3 on every runtime; never deploy during an open election window.

**Created:** 2026-10-15 · **Branch:** `feature/laravel-12-upgrade` · **Ticket:** none; no PBDIGIT story covers this upgrade (maintenance) · **Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0), `adr/`.

> **What this ADR decides, in one line:** *PublicDigit moves to Laravel 12 with Laravel-Excel 4.x (PhpSpreadsheet 5) and PHP ^8.3, through a targeted dependency update, with no Domain-layer change.*

---

## 1 · Context (facts, verified on 2026-10-15)

**Installed before the change** (`composer.lock`):

| Package | Version |
|---|---|
| `laravel/framework` | v11.41.3 |
| `maatwebsite/excel` | 3.1.62 |
| `phpoffice/phpspreadsheet` | 1.29.9 |
| `nesbot/carbon` | 3.8.4 |
| `inertiajs/inertia-laravel` | v2.0.0 (allows only `laravel/framework ^10.0\|^11.0`, so it **blocks** Laravel 12) |
| `spatie/laravel-query-builder` | 6.3.0 (allows only `illuminate/* ^10\|^11`, so it **blocks** Laravel 12) |
| `spatie/laravel-package-tools` | 1.18.3 (allows only `illuminate/contracts` up to `^11`, so it **blocks** Laravel 12; a dependency of query-builder) |
| `@inertiajs/vue3` (npm) | 2.3.17. Frontend; independent of the Laravel version, so it does not change. |
| PHP constraint | `^8.2` (runtime: PHP 8.5.11) |

**Drivers:**
1. Keep the framework on a supported major version.
2. PHP 8.5 runtime compatibility of the spreadsheet stack (PhpSpreadsheet 1.x line).
3. Keep dependency drift small and reviewable rather than accumulating it.

**Spreadsheet usage in the application:**
- Limited to `Excel::toArray`, `Excel::download`, `WithHeadingRow`, `FromArray` and `WithHeadings`.
- Files: `app/Imports/OrganisationUserImport.php`, `app/Services/OrganisationUserImportService.php`, `app/Services/ParticipantImportService.php` and `app/Services/VoterImportService.php`.
- There is no direct PhpSpreadsheet use.
- Every implemented concern method already declares native return types: `headingRow(): int`, `array(): array`, `headings(): array`.

## 2 · Options considered

| Option | Description | Assessment |
|---|---|---|
| **A** | Laravel 12 + Laravel-Excel **3.1.70** (PhpSpreadsheet 1.30.x) | Smallest change; 3.1.70 already allows `illuminate/support ^12`. It keeps PhpSpreadsheet on the 1.x line, which does not address driver 2. |
| **B ✅** | Laravel 12 + Laravel-Excel **4.x** (PhpSpreadsheet ^5) | Addresses all drivers. Larger transitive jump (PhpSpreadsheet 1 → 5); mitigated because the application uses no PhpSpreadsheet API directly. Requires PHP ^8.3. |
| C | Stay on Laravel 11 | Defers the work and accumulates drift. Rejected. |

## 3 · Decision

1. **Adopt option B:**
   - `"php": "^8.3"`
   - `"laravel/framework": "^12.0"`
   - `"maatwebsite/excel": "^4.0"`
2. **Targeted resolution only:**
   - Run `composer update laravel/framework maatwebsite/excel inertiajs/inertia-laravel spatie/laravel-query-builder lcobucci/jwt --with-all-dependencies`, after a dry run.
   - `lcobucci/jwt` was added by a second PO approval on 2026-10-16. Locked 5.5.0 does not allow PHP 8.5, a problem already present before the upgrade. It resolves to 5.6.1, within `^5.0`, and the app has no direct use of it.
   - Revision approved by the PO on 2026-10-15. The first command, without the last two packages, cannot resolve: `--with-all-dependencies` follows the dependencies *of* the named packages, not the packages that depend *on* Laravel.
   - Inertia (→ 2.0.28) and query-builder (→ 6.4.4) move **within** their existing `^2.0` / `^6.0` constraints. No major upgrade and no `composer.json` change for them.
   - Inertia 3.x is out of scope.
   - Do **not** run an unscoped `composer update`, which would move every package to its newest allowed version.
3. **No Domain-layer change is permitted.** The framework is infrastructure. If the upgrade forces a change in `app/Contexts/*/Domain`, treat it as a boundary finding and stop.
4. **Pre-existing failures stay out of scope.** These failures are recorded in the baseline and not modified by the upgrade:
   - `ElectionLifecycleStateConsistencyTest`
   - `ElectionStateMachineConsistencyTest`
   - `ConstitutionalBoundaryTest`
   - `Phase_C25_SovereigntyLeakagePreventionTest`

## 4 · Breaking-change impact (Laravel 12 and Laravel-Excel 4.x against this codebase)

| Change | Impact here | Evidence |
|---|---|---|
| Carbon 3 required | None | Carbon 3.8.4 is already installed |
| `local` disk default root becomes `storage/app/private` | None | `config/filesystems.php:35` sets `root` explicitly |
| `HasUuids` generates UUIDv7 | No behaviour class change. The current trait already uses `Str::orderedUuid()` (time-ordered). See §6, finding F-1 | `HasUuids.php:16–18`; 46 models use the trait |
| Excel 4.x native return types on concern methods | None. Already typed | the 4 files above |
| Excel 4.x `FromQuery` Scout builder removed | None | no Scout usage |
| Excel 4.x config | No key changes (upstream UPGRADE-4.x.md) | — |
| PhpSpreadsheet 1 → 5 | Low. No direct API use | grep of `app/` and `tests/` |
| **`image` rule rejects SVG** (unless `image:allow_svg`) | **Behaviour change, accepted by the PO on 2026-10-16 (option 1)**. Three endpoints listing `svg` stop accepting it: `StoreImage.php:27`, `OrganisationController.php:304` (logo), `ElectionManagementController.php:720` (logo). Intentional security tightening, because SVG can embed script and logos are shown to every voter. No code change | follow-up audit of stored SVGs: **PBDIGIT-299** |
| `Schema::getTables()` / `getViews()` / `getTableListing()` now schema-qualified | None | not used in `app/`, `database/`, `routes/`, `tests/` |
| Container honours constructor default values | Not detectable by search | covered by the full suite and PHPStan before/after |
| Route precedence unified (cached and uncached) | Low | route caching not used locally; covered by the route tests in the suite |

## 5 · Verification protocol (evidence before acceptance)

Each measurement is taken **before** (Laravel 11) and **after** (Laravel 12) the change, and the two are compared. Only differences count as upgrade effects.

| Gate | Command |
|---|---|
| Framework version | `php artisan --version` |
| Manifest | `composer validate` |
| Full test suite | `vendor/bin/phpunit` (JUnit log) |
| Spreadsheet tests | the 9 import tests (`OrganisationUserImport`, `ParticipantImport*`, `*VoterImport*`, `MemberImport*`) |
| Static analysis | `vendor/bin/phpstan analyse -c phpstan-greenfield.neon` |
| Architecture | `deptrac` with `deptrac.yaml` |

**Evidence (2026-10-16):**

| Gate | Laravel 11 (baseline) | Laravel 12 (+ fix) |
|---|---|---|
| Framework | 11.41.3 | **12.70.0** |
| `composer validate` | — | valid |
| `composer audit` | 65 advisories / 17 packages | **12 / 5** (php-jwt, phpseclib, phpunit, psysh, symfony/yaml; outside the update scope, follow-up proposed) |
| Spreadsheet tests (107 cases) | 8 fail · 19 error | 5 fail · 14 error. **0 PASS → FAIL**; 8 improved |
| PHPStan (greenfield) | 22 | 23. **+1 known false positive (PO decision 2026-10-16, option a):** `EloquentAdjudicationProcessStore.php:53`. Laravel 12's query-builder generics make PHPStan (without Larastan) infer `first()` as `stdClass\|null`; runtime returns the model. Not changed |
| Deptrac | — (see note) | **0 violations · 0 errors** (131 uncovered, 970 allowed), both before and after the fix |
| Tenant gate | pass | pass |
| Full suite (8,360 cases, matched 1:1) | 6,373 pass · 392 fail · 1,545 error · 50 skip | 6,373 pass · 387 fail · 1,550 error · 50 skip. 16 improved; 16 regressed (classified below) |
| Frontend build (`vite build` into a scratch directory; tracked `public/build` untouched) | — | ✅ 3,499 modules, built in 21 s |
| `npm run design-check` | frontend unchanged | 91 violations ≤ threshold 150 (POST-MIGRATION) |

**Classification of the 16 PASS → non-PASS transitions:**
- **13 order-dependent** (`organisations_slug_unique` / `users_email_unique` duplicates): every one passes when run alone on Laravel 12. This is shared test data on PostgreSQL (PBDIGIT-295), not the upgrade.
- **2 order-dependent** (`CommitteeSlugAvailabilityServiceTest`, FK `geo_administrative_units_country_code_foreign`): the same error hit 5 of the file's 8 methods on Laravel 11, and the affected methods vary per run (PBDIGIT-295).
- **1 caused by the upgrade, an identifier format change:** `BallotAnonymityCharacterizationTest::test_k7_known_gap_vote_ids_sort_in_cast_order_and_encode_the_creation_time`.
  - The order assertion still passes.
  - The decoder assumes `Str::orderedUuid()` (first 48 bits = seconds × 100 000). Laravel 12's UUIDv7 stores **milliseconds** in those bits; the failing value × 100 equals the cast time.
  - **The known gap A4 persists, at millisecond precision** (see F-1). The test is stale; the property it characterises is not fixed.
  - **Resolved (PO decision 2026-10-16, option a), test only:** the decoder reads the UUID version digit (7 → milliseconds, otherwise `orderedUuid`). The gap assertions and messages are unchanged. All 6 cases of the characterization file pass, matching Laravel 11.

**Deptrac note:**
- No Laravel 11 run was possible because `vendor/` lacked the package.
- Deptrac analyses only `app/` source, so the first Laravel 12 run, made while `app/` was unchanged (PO-approved approach), equals the Laravel 11 result.

**Code change required by the upgrade (PO approval 2026-10-16):**
- Laravel-Excel 4.x types `toArray`/`import` against the marker `Concerns\Import`, and `download`/`store` against `Concerns\Export`.
- `WithHeadingRow` does not extend `Import`, so `OrganisationUserImport` was rejected with a `TypeError` (HTTP 500 in 19 import tests).
- Fix: `OrganisationUserImport implements Import, WithHeadingRow`. Exports needed no change (`FromArray` extends `Export`).
- Upstream UPGRADE-4.x.md does not document this.

## 6 · Findings outside this decision (recorded, not acted on)

- **F-1 (anonymity, ADR-T11): vote and result identifiers are time-ordered.**
  - `BaseVote` and `BaseResult` use `HasUuids`. On Laravel 11 this is `Str::orderedUuid()`; on Laravel 12 it is UUIDv7. Both embed a timestamp.
  - The upgrade does not introduce this, but it confirms it.
  - **Evidence 2026-10-16:** the characterization test K7 (gap A4) shows the creation time is recoverable from Laravel 12 vote ids to the millisecond (UUIDv7), against 10 µs on Laravel 11 (`orderedUuid`).
  - Backlog: **PBDIGIT-300** (priority raised by the PO 2026-10-16); no change under this ADR.
- **F-2 (repository hygiene):** `vendor/` was missing `qossmic/deptrac-shim`, `pdepend/pdepend`, `symfony/config`, `symfony/dependency-injection` and `symfony/var-exporter`, although the lockfile lists them. `composer reinstall` refuses in that state. The targeted update reinstalled them from the lock.
- **F-3 (dependency security):** 12 advisories remain in 5 packages outside the update scope (`firebase/php-jwt`, `phpseclib/phpseclib`, `phpunit/phpunit`, `psy/psysh`, `symfony/yaml`). Backlog: **PBDIGIT-301**.
- **F-4 (mail TLS):** `symfony/mailer` is now 7.4.19. Whether it offers enforced STARTTLS is relevant to the open mail-TLS question from the SES work of 2026-10-15. Backlog: **PBDIGIT-302**; not acted on here.

## 7 · Consequences

- **Positive:**
  - The framework stays on a supported major version.
  - The spreadsheet stack moves to the maintained PhpSpreadsheet 5 line.
  - The dependency diff is limited to the framework and spreadsheet trees.
- **Negative / cost:**
  - The PHP floor rises to 8.3. Every runtime (local, CI and the production server) must run PHP ≥ 8.3 **before** deployment.
  - Production deploys need `config:cache` / `route:cache` and a queue-worker restart.
- **Rollback:** restore the Laravel 11 `composer.json` and `composer.lock` (both kept as evidence), then run `composer install`. No migration is involved.
- **Release constraint:** never deploy during an open election window.

## 8 · Traceability

- **Plan:** approved in session 2026-10-15 (EP-01).
- **Evidence:** the upgrade report of 2026-10-15.
- **Developer guide:** `developer_guide/` (written with the upgrade).
- **Related:**
  - ADR-T11 (anonymity) for F-1.
  - `deptrac.yaml` / `phpstan-greenfield.neon` (ADR-T7) for the gates.
