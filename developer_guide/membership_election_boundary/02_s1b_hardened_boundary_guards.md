# S1b: hardened Membership–Election boundary guards

**Ticket:** PBDIGIT-318 · **Slice:** S1b, test-only, authorised by the PO on 2026-11-02 after the independent review of S1. A follow-up, also authorised on 2026-11-02, came after the independent review of S1b.
**File:** `tests/Architecture/MembershipElectionBoundaryTest.php`
**Builds on:** guide 01 (S1). Read that first; this guide covers only what S1b changes.
**Status:** **accepted by the PO on 2026-11-02, with the documented residual risks** (see "Known limits and false positives"), after two independent reviews. Acceptance does not make any baselined dependency compliant.

## Purpose

The independent review of S1 showed that it guarded only part of what the plan promised:
- Membership writes to Election tables other than `election_memberships` passed;
- a new violation could hide inside a file that was already baselined;
- an array key equal to a table name failed the test (a false positive).

S1b **narrows** those gaps. The follow-up adds occurrence counts, so an **additional** use of a baselined target can no longer hide. A substitution that keeps the count can (e.g. a baselined read relation replaced by a write through the same model): see the known limits. **S1b does not close every gap.** The remaining detection limits and known false positives are listed below. S1b changes no runtime code, no scan paths and no ownership.

## Where it fits

- `tests/Architecture`, plain PHPUnit, no database, no framework boot.
- It runs in the Architecture suite like S1.

## What changed

| Area | S1 | S1b |
|---|---|---|
| What is tracked | file + pattern label | file + **target** (`model:X`, `table:y`, `namespace:Z`) + **occurrence count**. A new target, or one more occurrence of a baselined target, fails; fewer occurrences require the baseline to be lowered |
| Membership → Election | `ElectionMembership`, `'election_memberships'`, `App\Models\Election`, Election namespaces | every Election-owned **model** (`ELECTION_MODELS`, 28 classes) and **table** (`ELECTION_TABLES` + prefixes), plus the namespaces |
| Model references | plain `use` only | plain, aliased (`as`) and grouped (`{A, B}`) imports; fully qualified names; bare names inside `namespace App\Models` |
| Table references | any quoted string equal to a table name | **anchored**: query-builder calls (`table`, `from`, `join`, `leftJoin`, …, `exists`, `unique`), `exists:`/`unique:` rule strings, `$table` properties, SQL statement strings (with `as` aliases and schema prefixes) |
| Foreign-key replay | `database/migrations` + context folders | + `database/migrations/landlord`; `constrained(table: …)`; `Schema::connection('<literal>')->table(…)`; `foreign(cols, 'name')`; `foreignIdFor(Model::class[, 'col'])->constrained()`; unnamed raw `ADD FOREIGN KEY`; raw `CREATE TABLE … REFERENCES` |
| Self-tests | none | planted forms for detection and non-detection, foreign-key forms, `$table` properties, and the occurrence-count ratchet |

## Key design decisions

- **Three views of a source** (`views()`), built with the PHP tokenizer:
  - `code`: comments removed;
  - `plain`: comments and string contents removed, used for class references;
  - `strings`: the string contents, used for SQL statements and validation rules.
- **How occurrences are counted** (`modelReferences()`, `tableReferences()`):
  - each import of a model counts once;
  - each later use of its local name (or alias), each fully qualified name outside imports and, inside `namespace App\Models`, each bare class name counts once more;
  - each anchored table reference counts once.
  - Counts are deterministic, but they react to harmless refactors. That is intended: any change in how a baselined file touches the other context goes back for a baseline decision.
- **Anchoring, not string equality.** This removes the E6 false positive. It does not remove every false positive (see below).

## Baseline and its governance

```php
'app/Models/Member.php' => ['model:Election' => 2, 'model:ElectionMembership' => 1, 'model:Voter' => 1], // V1b, V1, V1c
'app/Contexts/Membership/Infrastructure/Jobs/CleanupExpiredInvitations.php' => ['model:VoterInvitation' => 2], // V1d
'app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php' => [
    'model:OrganisationUser' => 3, 'table:members' => 2, 'table:membership_types' => 1, 'table:organisation_users' => 2, // V2
],
'voters(member_id) -> members', // V3′
```

The test comments cite the line of each counted occurrence.

**These are known violations awaiting S2, S3 and S4. They are not compliant dependencies.**
- **V1c** (`Member::voters()` → `Voter`) and **V1d** (`CleanupExpiredInvitations` soft-deletes `VoterInvitation`) were found by the S1b RED run.
- Both were added only after the PO's decision of 2026-11-02.

**Governance (PO, 2026-11-02):**
- The **PO** approves routine baseline additions and changes that are consistent with existing architectural decisions.
- The **ARB** decides changes to architectural policy and unresolved cross-context ownership questions (e.g. OQ-ME-3, OQ-ME-4).
- A baseline entry **records a known condition**. It does not establish that the dependency is architecturally compliant.
- **No newly discovered violation is added automatically,** and no baseline change may settle an open architectural question.

## How to extend

- **New Election model or table:** add it to `ELECTION_MODELS` / `ELECTION_TABLES` and add a planted case.
- **New detection form:** add a planted case first and see it fail (RED), then extend the detector.
- **Baseline changes:** only by decision, per the governance rules above. Lower a count in the same change that removes an occurrence.

## Testing

```bash
vendor/bin/phpunit --no-configuration --do-not-cache-result tests/Architecture/MembershipElectionBoundaryTest.php
```

**Result at the time of writing:** OK, 49 tests, 64 assertions. The same result under PHP 8.3, run in a container with the Composer platform check disabled (see pitfalls).

## Known limits and false positives (from the independent review of S1b, 2026-11-02)

**Not detected (blind spots):**
- **Paths:** legacy Election and Membership code outside the scan paths (`app/Models`, `app/Services`, `app/Http/Controllers`, `app/Policies`, `app/Traits`), excluded by the PO. Inventory X-1…X-9 in PBDIGIT-318 records those sites as neither compliant nor baselined.
- **Class names written as strings,** e.g. `hasMany('App\Models\Voter')`, `belongsTo('App\Models\Member')`, or `app('App\Contexts\Membership\…')` on the Election side. Model detection uses the view with string contents removed.
- **Raw SQL not covered:** `TRUNCATE`, `ALTER TABLE` against an Election table in Membership code, and `UPDATE ONLY`.
- **Unusual forms:** a named `table:` argument to `DB::table`, a partial namespace (`use App\Models; Models\Voter::…`), and interpolated table names (undecidable statically).
- **Foreign keys:**
  - `Schema::connection(<expression>)` (only a literal connection name works);
  - schema-qualified or `IF EXISTS` raw `ALTER TABLE`;
  - `CREATE TABLE` inside a heredoc;
  - a table name passed as a constant.
- **Foreign keys in the reverse direction** (a Membership table referencing an Election table) are not guarded. Whether that is required belongs to OQ-ME-3.

**Count-preserving substitution (inherent to a count ratchet):** replacing one baselined occurrence by another of the same target keeps the count, and passes. Example: the V1c `hasMany(Voter::class)` replaced by a `Voter::…->delete()` write (review probe C15). Closing this would need per-site fingerprints, which was judged disproportionate.

**False positives.** They fail loudly, which is the safe direction, but they create pressure to grow the baseline. The governance rules forbid *automatic* growth; any addition needs a PO decision consistent with existing architectural decisions:
- prose that starts like SQL, e.g. "Select a member from elections…" or "Delete from voters list…";
- generic `exists('…')`, `unique('…')`, `from('…')` or `table('…')` calls on any receiver, e.g. `$request->exists('votes')`, `collect()->unique('posts')`;
- a key added and later removed only by `dropColumn` or `Schema::drop`, which the replay does not model;
- inside `namespace App\Models`, any bare word equal to an Election model name (a constant or enum case, e.g. `const Vote = 1`) counts as a model reference (review probe C09);
- `foreignIdFor(X::class)` **without** `constrained()`, followed in the same schema segment by `foreignId('…')->constrained()`, is read as one key (review probe F19).

**`foreignIdFor` targets** are derived with Laravel's naming conventions (snake case plus a simple plural). A model with a custom `$table` or an irregular plural would give a wrong target; pass the table explicitly in `constrained('…')`.

**Approximations:**
- the foreign-key replay is still an approximation (guide 01); only a read-only catalog query proves the deployed schema (S4);
- driver branches are treated as executed.

**PHP 8.3 and `composer.lock`:** the lock pins Symfony 8.1 packages that require PHP ≥ 8.4.1, so a normal PHP 8.3 run stops at Composer's platform check. This is a project-wide issue (PBDIGIT-319), not an S1b defect.

## Traceability

Plan S1b section → independent review of S1 → PO decision (S1 deferred, S1b authorised) → S1b (`3aad77d20`) → independent review of S1b → PO decision (narrow follow-up) → this test and guide → PBDIGIT-318.
