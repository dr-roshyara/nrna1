# PBDIGIT-304: PSR-4 autoload cleanup (11 classes skipped by Composer)

**Type:** Code hygiene · **Epic:** none · **Created:** 2026-10-16 · **Raised by:** Claude Code session (self-declared, not attestable), from Composer's `dump-autoload` warnings after the Laravel 12 upgrade. **Index row:** added 2026-10-16.

| | |
|---|---|
| **Status** | `IN PROGRESS` · plan approved by the PO 2026-10-16 (per-file decisions below) |
| **Classification** | **observed:** `composer dump-autoload` skips 11 classes whose namespace does not match their path. On a case-sensitive filesystem (Linux production) none of them can be autoloaded |
| **Scope** | delete stale duplicates and dead code, relocate one model, fix test namespaces; no behaviour change at runtime |

## Finding and decision per file

| File | Finding | Decision (PO 2026-10-16) |
|---|---|---|
| `app/Http/Controllers/RegisterController.php` | stale copy of `Auth\RegisterController` (last changed 2026-03-06; the real one 2026-03-29) | delete |
| `app/Models/BreadcrumbHelper.php` | stale copy of `App\Helpers\BreadcrumbHelper` (lacks the 2026-04-26 type-check fixes) | delete |
| `app/Contexts/Membership/Domain/ValueObjects/TermPeriod.php` | byte-identical copy of `Committee/ValueObjects/TermPeriod.php` | delete |
| `app/Models/Outcome.php` (declares `App\Domain\Finance\Models`) | not autoloadable; referenced in 4 docblocks of `OutcomeController` | move to `app/Domain/Finance/Models/Outcome.php` |
| `app/Services/DiagnoseRedirectLoop.php` | unregistered debug middleware, 0 references | delete |
| `app/Http/Controllers/Api/MemberCommitteesController.php` (declares `API`) | no route, 0 references | delete |
| `tests/Feature/Organizations/VoterController{Integration,Security,}Test.php` | namespace casing (`Organisations`/`organisations`), so the tests never run | fix the namespace to `Tests\Feature\Organizations` |
| `tests/Unit/Domain/Election/Security/OverlaySignalCategoryTest.php` | class named `OverlayInfluenceTest` | rename the class to `OverlaySignalCategoryTest` |
| `tests/Unit/Contracts/Archived_ElectionMembershipContractTest_PhaseC7.php` | archived test, never runs | delete (kept in Git history) |

**Not applied:** the generic advice "move the file to the PSR-4 path" for the three duplicates. The target paths already hold the real classes, so moving would overwrite current code with stale copies.

## Acceptance criteria
1. `composer dump-autoload` reports no "does not comply with psr-4" line.
2. The full suite shows no PASS → FAIL against the pre-change run. Newly running tests (the three `Organizations` tests, the renamed overlay test) are reported separately: they never ran before.
3. One commit per group (duplicates, dead code, model move, tests).
