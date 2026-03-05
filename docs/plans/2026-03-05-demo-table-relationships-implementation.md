# Demo Table Relationships Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Modify `2026_03_01_016_restore_demo_tables.php` migration to redefine relationships between demo tables, matching real table relationships while preserving demo-specific functionality.

**Architecture:** Directly edit the existing migration file before it runs. Apply changes to `demo_posts`, `demo_candidacies`, `demo_votes`, and `demo_results` tables to align foreign keys, column definitions, and constraints with their real table counterparts.

**Tech Stack:** Laravel migrations, MySQL foreign keys, PHP

---

## Task 1: Analyze Current Migration Structure

**Files:**
- Read: `database/migrations/2026_03_01_016_restore_demo_tables.php:1-300`

**Step 1: Identify demo_posts creation block**
Find the `demo_posts` table creation in the migration. Look for `Schema::hasTable('demo_posts')` or similar pattern.

**Step 2: Check if demo_posts table exists in migration**
The current migration doesn't create `demo_posts` (it exists in a separate migration). We need to check if we should modify it there or create new migration. Since user said "modify existing migration", we need to see if `demo_posts` is in this file.

**Step 3: Note foreign key relationships**
Map current foreign keys in demo tables to understand what needs changing.

**Step 4: Save analysis**
Create quick notes on what needs to be changed in each table section.

**Step 5: Commit analysis notes**
```bash
git add docs/analysis/demo-table-analysis.md
git commit -m "docs: Analysis of demo table relationships for redesign"
```

---

## Task 2: Modify demo_posts Table (Add Missing Columns)

**Files:**
- Modify: `database/migrations/old_migrations/2026_02_20_011053_create_demo_posts_table.php:14-31`
OR
- Create new migration if demo_posts not in restore_demo_tables.php

**Step 1: Check where demo_posts is created**
```bash
grep -n "demo_posts" database/migrations/2026_03_01_016_restore_demo_tables.php
```
If not found:
```bash
find database/migrations -name "*demo_posts*" -type f
```

**Step 2: Add missing columns to demo_posts**
Modify the `demo_posts` table creation to add:
- `description` (text, nullable)
- `select_all_required` (boolean, default true)

**Step 3: Make organisation_id NOT NULL**
Change: `$table->unsignedBigInteger('organisation_id')->nullable();`
To: `$table->unsignedBigInteger('organisation_id');`

**Step 4: Add missing foreign key**
Add after line 30 (election_id foreign key):
```php
$table->foreign('organisation_id')
      ->references('id')
      ->on('organisations')
      ->onDelete('cascade');
```

**Step 5: Add missing indexes**
Add after foreign keys:
```php
$table->index(['election_id', 'is_national_wide']);
$table->index(['election_id', 'state_name']);
```

**Step 6: Run migration test**
```bash
php artisan migrate:status | grep demo_posts
```
Check migration hasn't been run yet.

**Step 7: Commit changes**
```bash
git add database/migrations/old_migrations/2026_02_20_011053_create_demo_posts_table.php
git commit -m "fix: Add missing columns and constraints to demo_posts table"
```

---

## Task 3: Modify demo_candidacies Foreign Keys and Indexes

**Files:**
- Modify: `database/migrations/2026_03_01_016_restore_demo_tables.php:32-65`

**Step 1: Change user_id foreign key onDelete action**
Find line 53: `$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');`
Change to: `$table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');`

**Step 2: Add unique index on [post_id, user_id]**
After line 63 (current indexes), add:
```php
$table->unique(['post_id', 'user_id']);
```

**Step 3: Make organisation_id NOT NULL**
Find line 49: `$table->unsignedBigInteger('organisation_id')->nullable();`
Change to: `$table->unsignedBigInteger('organisation_id');`

**Step 4: Add organisation foreign key if missing**
Check if foreign key exists (line 56-58). If not, add:
```php
if (Schema::hasTable('organisations')) {
    $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
}
```

**Step 5: Run migration test**
```bash
php artisan migrate:status | grep 2026_03_01_016
```

**Step 6: Commit changes**
```bash
git add database/migrations/2026_03_01_016_restore_demo_tables.php
git commit -m "fix: Adjust demo_candidacies foreign keys and indexes"
```

---

## Task 4: Modify demo_votes Columns and Constraints

**Files:**
- Modify: `database/migrations/2026_03_01_016_restore_demo_tables.php:253-287`

**Step 1: Add vote_session_name column**
After line 260 (`$table->string('voting_code')->nullable();`), add:
```php
$table->string('vote_session_name')->nullable();
```

**Step 2: Replace no_vote_option with no_vote_posts**
Find line 259: `$table->boolean('no_vote_option')->default(0);`
Replace with: `$table->json('no_vote_posts')->nullable();`

**Step 3: Make organisation_id NOT NULL**
Find line 257: `$table->unsignedBigInteger('organisation_id')->nullable();`
Change to: `$table->unsignedBigInteger('organisation_id');`

**Step 4: Change organisation foreign key onDelete**
Find lines 277-279:
```php
if (Schema::hasTable('organisations')) {
    $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('set null');
}
```
Change to:
```php
if (Schema::hasTable('organisations')) {
    $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
}
```

**Step 5: Add index on cast_at column**
Find lines 282-286 (indexes section), add before closing:
```php
$table->index('cast_at');
```

**Step 6: Verify candidate columns remain JSON**
Check lines 263-266 remain unchanged (candidate_01..60 as JSON).

**Step 7: Run migration test**
```bash
php artisan migrate:status | grep 2026_03_01_016
```

**Step 8: Commit changes**
```bash
git add database/migrations/2026_03_01_016_restore_demo_tables.php
git commit -m "fix: Adjust demo_votes columns and constraints to match real table"
```

---

## Task 5: Modify demo_results Table Structure

**Files:**
- Modify: `database/migrations/2026_03_01_016_restore_demo_tables.php:140-171`

**Step 1: Drop candidacy_id column**
Find line 149: `$table->unsignedBigInteger('candidacy_id')->nullable();`
Remove this line entirely.

**Step 2: Add foreign key for candidate_id**
Find line 161: `$table->foreign('candidacy_id')->references('id')->on('demo_candidacies')->onDelete('set null');`
Replace with:
```php
$table->foreign('candidate_id')->references('id')->on('demo_candidacies')->onDelete('set null');
```

**Step 3: Make organisation_id NOT NULL**
Find line 151: `$table->unsignedBigInteger('organisation_id')->nullable();`
Change to: `$table->unsignedBigInteger('organisation_id');`

**Step 4: Adjust organisation foreign key**
Find lines 162-164:
```php
if (Schema::hasTable('organisations')) {
    $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('set null');
}
```
Keep as is (matches real results).

**Step 5: Verify other foreign keys match real table**
Check:
- Line 158: election_id foreign key (cascade) ✓
- Line 159: post_id foreign key to demo_posts (cascade) ✓
- Line 160: vote_id foreign key to demo_votes (cascade) ✓

**Step 6: Run migration test**
```bash
php artisan migrate:status | grep 2026_03_01_016
```

**Step 7: Commit changes**
```bash
git add database/migrations/2026_03_01_016_restore_demo_tables.php
git commit -m "fix: Adjust demo_results structure to match real table"
```

---

## Task 6: Run Full Migration Test

**Step 1: Run migration dry-run**
```bash
php artisan migrate --pretend
```
Check for any SQL errors.

**Step 2: Check migration status**
```bash
php artisan migrate:status | grep -E "(demo|2026_03_01)"
```

**Step 3: Verify demo_posts migration status**
```bash
php artisan migrate:status | grep -i "demo_posts"
```

**Step 4: If demo_posts migration already ran, create new migration**
If demo_posts migration has been run, create new migration to modify it:
```bash
php artisan make:migration modify_demo_posts_table --table=demo_posts
```

**Step 5: Create demo_posts modification migration if needed**
If Task 2 required new migration, create it with:
```php
Schema::table('demo_posts', function (Blueprint $table) {
    $table->text('description')->nullable();
    $table->boolean('select_all_required')->default(true);
    $table->unsignedBigInteger('organisation_id')->nullable(false)->change();

    if (Schema::hasTable('organisations')) {
        $table->foreign('organisation_id')
              ->references('id')
              ->on('organisations')
              ->onDelete('cascade');
    }

    $table->index(['election_id', 'is_national_wide']);
    $table->index(['election_id', 'state_name']);
});
```

**Step 6: Commit any new migrations**
```bash
git add database/migrations/*_modify_demo_posts_table.php
git commit -m "feat: Add missing columns and constraints to demo_posts"
```

---

## Task 7: Verify All Changes

**Step 1: Create verification checklist**
Create a markdown file with all changes to verify.

**Step 2: Run full test suite for demo tables**
```bash
php artisan test --filter="demo"
```

**Step 3: Check foreign key integrity**
Create a simple test script to verify relationships:
```php
// Check demo_candidacies -> demo_posts
$candidacy = \App\Models\DemoCandidacy::first();
if ($candidacy) {
    $post = $candidacy->post; // Should work
    echo "demo_candidacies -> demo_posts: OK\n";
}

// Check demo_results -> demo_votes
$result = \App\Models\DemoResult::first();
if ($result) {
    $vote = $result->vote; // Should work
    echo "demo_results -> demo_votes: OK\n";
}

// Check demo_results -> demo_candidacies
if ($result && $result->candidate_id) {
    $candidate = $result->candidate; // Should work
    echo "demo_results -> demo_candidacies: OK\n";
}
```

**Step 4: Run verification script**
```bash
php artisan tinker --execute="require 'check_demo_relationships.php'"
```

**Step 5: Commit verification**
```bash
git add tests/verification/demo-relationships-check.md
git commit -m "test: Add verification for demo table relationships"
```

---

## Task 8: Final Documentation

**Step 1: Update CLAUDE.md if needed**
Check if any architecture patterns were established that should be documented.

**Step 2: Update design document with implementation details**
Add "Implementation Notes" section to `docs/plans/2026-03-05-demo-table-relationships-design.md`.

**Step 3: Create rollback verification**
Test that migration down() method still works correctly:
```bash
php artisan migrate:rollback --step=1 --pretend
```

**Step 4: Final commit**
```bash
git add docs/plans/2026-03-05-demo-table-relationships-design.md CLAUDE.md
git commit -m "docs: Update with demo table relationships implementation details"
```

**Step 5: Push to branch**
```bash
git push origin multitenancy
```

---

## Notes

1. **Migration Order:** The `2026_03_01_016_restore_demo_tables.php` migration must run after `demo_posts` migration (if separate).

2. **Foreign Key Consistency:** All `organisation_id` columns are now NOT NULL across demo tables (matches real tables where applicable).

3. **Demo-specific Columns:** Extra columns in `demo_candidacies` (user_name, candidacy_name, etc.) are preserved for demo functionality.

4. **Candidate Columns:** `demo_votes.candidate_01..60` remain JSON type (demo-specific), differing from real `votes` table which uses unsignedBigInteger.

5. **Testing:** Run tests after each major table modification to catch issues early.

6. **Rollback Safety:** Migration down() drops all demo tables, which is safe since we're modifying up() before migration runs.