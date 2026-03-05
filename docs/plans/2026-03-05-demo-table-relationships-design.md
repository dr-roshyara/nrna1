# Demo Table Relationships Redesign

## Overview
Redefine relationships between demo tables (`demo_posts`, `demo_candidacies`, `demo_votes`, `demo_results`) to mirror those of real tables while maintaining demo-specific functionality.

## Date
2026-03-05

## Background
The `2026_03_01_016_restore_demo_tables.php` migration creates demo tables for testing elections. However, foreign key relationships and column definitions don't fully match the real tables (`posts`, `candidacies`, `votes`, `results`). This redesign ensures consistency while preserving demo-mode functionality.

## Design Decisions

### 1. demo_posts
**Changes:**
- Add `description` column (text, nullable) - missing from demo table
- Add `select_all_required` column (boolean, default true) - missing from demo table
- Change `organisation_id` from nullable to NOT NULL (matches real posts)
- Add foreign key to `organisations` table with `onDelete('cascade')`
- Add missing indexes: `['election_id', 'is_national_wide']` and `['election_id', 'state_name']`

### 2. demo_candidacies
**Changes:**
- Keep all existing extra columns (demo-specific functionality)
- Change foreign key `user_id` from `onDelete('cascade')` to `onDelete('restrict')` (matches real candidacies)
- Add unique index on `['post_id', 'user_id']` (matches real candidacies)
- Change `organisation_id` from nullable to NOT NULL (consistency)
- Keep foreign key to `demo_posts` with `onDelete('cascade')` (unchanged)

**Note:** Bio, photo_path, political_party, and metadata columns NOT added (demo-specific columns suffice).

### 3. demo_votes
**Changes:**
- Keep `candidate_01..60` as JSON (demo-specific, differs from real votes)
- Add `vote_session_name` column (string, nullable) - missing from demo table
- Replace `no_vote_option` (boolean) with `no_vote_posts` (json, nullable) - matches real votes
- Change `organisation_id` from nullable to NOT NULL (consistency)
- Change foreign key `organisation_id` from `onDelete('set null')` to `onDelete('cascade')` (matches real votes)
- Add index on `cast_at` column (missing from demo table)

### 4. demo_results
**Changes:**
- Drop `candidacy_id` column (real results has only `candidate_id`)
- Keep `candidate_id` column with foreign key to `demo_candidacies` `onDelete('set null')` (matches real results)
- Change `organisation_id` from nullable to NOT NULL (consistency)
- Keep foreign key `organisation_id` with `onDelete('set null')` (matches real results)
- Ensure foreign keys for `vote_id`, `post_id`, `election_id` match real table actions

### 5. Other Demo Tables
No changes to:
- `demo_codes`
- `demo_voter_slugs`
- `demo_voter_slug_steps`

These tables are demo-specific with no direct real table counterparts.

## Implementation Plan

### File to Modify
`database/migrations/2026_03_01_016_restore_demo_tables.php`

### Steps
1. **demo_posts section** (lines 31-65 in migration)
   - Add missing columns
   - Change organisation_id nullable to NOT NULL
   - Add foreign key to organisations
   - Add missing indexes

2. **demo_candidacies section** (lines 67-138)
   - Change user_id foreign key onDelete to 'restrict'
   - Add unique index on ['post_id', 'user_id']
   - Change organisation_id nullable to NOT NULL

3. **demo_votes section** (lines 250-287)
   - Add vote_session_name column
   - Replace no_vote_option with no_vote_posts
   - Change organisation_id nullable to NOT NULL
   - Change organisation_id foreign key onDelete to 'cascade'
   - Add index on cast_at

4. **demo_results section** (lines 140-171)
   - Drop candidacy_id column
   - Add foreign key for candidate_id to demo_candidacies
   - Change organisation_id nullable to NOT NULL
   - Adjust foreign key onDelete actions to match real table

## Verification
- All foreign key relationships should mirror real tables
- organisation_id is NOT NULL across all demo tables (except where real table has nullable)
- Demo-specific columns and data types preserved where explicitly decided
- Migration should run successfully without errors

## Rollback Considerations
The migration's down() method drops all demo tables. Since we're modifying the up() method before migration runs, rollback remains safe.

## Related Files
- Real table migrations:
  - `2026_03_01_000004_create_posts_table.php`
  - `2026_03_01_000005_create_candidacies_table.php`
  - `2026_03_01_000010_create_votes_table.php`
  - `2026_03_01_000011_create_results_table.php`