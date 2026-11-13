# 01: The architecture ratchet (A-1 to A-5)

**Purpose.** Stop new architectural shortcuts anywhere in `app/` and `routes/`, without rewriting old code. Known violations are listed one by one. **New ones fail the build, even inside a file that already has listed ones.**

**Where it fits.** These are architecture tests (testing level 5 in the baseline, §7). No database is needed. The test runs in the normal `tests/Architecture` suite.

## Key files

| File | Role |
|---|---|
| `tests/Architecture/ArchitectureRatchetTest.php` | the fixture proofs and the real-tree check, one data set per rule |
| `tests/Architecture/Support/ArchitectureRatchet.php` | `scan()` (strips comments, finds violations) and `compare()` (new versus stale) |
| `tests/Architecture/baselines/architecture-ratchet.json` | the known violations: `"rule|file|dependency": {count, class, reason, remediation, decision}` |
| `tests/Fixtures/ArchitectureRatchet/` | deliberate violations in `.php.fixture` files (never loaded) |
| `docs/publicdigit/architecture/publicdigit-architecture-deviation-inventory.md` | generated from the baseline |

## The rules

| Rule | Scope | Forbidden |
|---|---|---|
| A-1 | `app/Contexts/*/Domain` | `Illuminate\…`, `Laravel\…`, `App\Models\…` (imported or inline), facade calls |
| A-2 | `app/Contexts/*/Application` | `Illuminate\Support\Facades\…`, `Illuminate\Database\Eloquent\…`, `App\Models\…`, facade calls (also global aliases such as `Log::`) |
| A-3 | `app/Contexts/X/**` | any `App\Contexts\Y\…` with Y ≠ X, except `Shared` and the types named in `baselines/published-language.json` (today only `Membership\Domain\Member\MemberId`) |
| A-4 | `app/Http`, `app/Console`, `app/Jobs`, `app/Listeners`, `routes`, `Contexts/*/Http`, `Contexts/*/Console` | `…\Infrastructure\…`; write-side ports (`Application\Port(s)\…Repository/Store/Ledger`); `refgeo_*` or `org_membership_*` table names; `DB::insert/update/delete/statement/unprepared/affectingStatement`; `DB::table(...)->insert/update/delete/upsert/truncate/increment/decrement/…` within one statement |
| A-5 | `app/`, `routes/` | `connection('tenant')`, `$connection = 'tenant'` (D-222) |
| A-6 | `app/Contexts/Shared` | any class not in `baselines/shared-contents.json`. Shared is a technical library; a business concept belongs to its owning context |

**Allowed on purpose:** read-only query ports and Eloquent reads in controllers (CLAUDE.md Rule 5).

## When the test fails

```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Architecture/ArchitectureRatchetTest.php
```

- **"new violation(s)":** fix the code. A port, an injected logger, a query contract: the inventory's remediation column says which.
  - **Adding a baseline entry is an architecture change, never a shortcut.** It needs a classification from the closed vocabulary, a reason, a remediation and a decision.
  - It also means raising `BASELINE_CEILING` in the test, with its history comment.
- **"fixed violation(s) still listed":** you removed a violation. Delete its entry, or lower its `count`. Then regenerate the inventory (the test fails while they differ):

```bash
php -r 'require "vendor/autoload.php"; use Tests\Architecture\Support\ArchitectureRatchet as R;
  echo R::inventory(R::loadBaseline("tests/Architecture/baselines/architecture-ratchet.json"));' \
  > docs/publicdigit/architecture/publicdigit-architecture-deviation-inventory.md
```

- **"a new class in Shared":** if it's a business concept, move it to its owning context. If it's genuinely technical, add its path to `shared-contents.json`.
- **A new published-language type** (an identifier or contract DTO other contexts may import) is an architecture ruling. Add it to `published-language.json` only once ruled.

## How it works

```php
$found  = ArchitectureRatchet::scan($root, '.php');          // ["A-2|app/…/WaiveFee.php|DB::" => 1, …]
$result = ArchitectureRatchet::compare($found, $baseline);    // ['new' => [...], 'stale' => [...]]
```

- **A key is new** when it's found more often than the baseline allows. So a second `DB::` call in a listed file fails too.
- **A key is stale** when it's listed more often than found.
- **Comments are removed** with `token_get_all`, so a class named in a docblock isn't a violation.
- **Only `.php` files are scanned,** so backups don't count.

## Extending

- **Add a rule:** a fixture case, a RED run, then the scanner code.
- **CI:** `.github/workflows/architecture-ratchet.yml` runs only this test, without a database, so it isn't masked by the 4 pre-existing failures of the full Architecture suite.

## Pitfalls

- **The scan is static. Two blind spots are asserted by `test_known_blind_spots_stay_documented`:**
  - class names inside strings;
  - Eloquent writes (`$model->save()`).

  Neither is enforced; review is the control.
- **Facade aliases are matched by name** (`DB`, `Log`, `Cache`, …) unless the file imports another class with that name.
- **Moving a file changes its key:** a move shows as one new and one stale entry. Update the baseline in the same commit, with the same reason.

**Traceability:** PBDIGIT-348 · `publicdigit-hexagonal-ddd-baseline.md` §7 · CLAUDE.md Rules 1, 2, 5 · D-222 · ES-005.4.
