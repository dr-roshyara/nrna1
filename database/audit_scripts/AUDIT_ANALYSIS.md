# 🔍 Database Migration Audit Analysis
**Timestamp:** 2026-03-01 22:44:00
**Status:** ⚠️ CRITICAL ISSUES IDENTIFIED

---

## Executive Summary

The consolidated 2026-03-01 migrations are **INCOMPLETE**. They are missing critical columns from the original schema.

| Metric | Count |
|--------|-------|
| Old migrations total columns | 497 |
| New migrations total columns | 115 |
| **Column loss** | **382 (77%)** |
| Tables with missing columns | 10 |
| Tables completely missing | 31 |

**Verdict:** The new migrations **CANNOT be used as-is**. Many critical voting system columns have been dropped.

---

## 🚨 CRITICAL MISSING COLUMNS (MUST FIX)

### 1. **`votes` table** - MOST CRITICAL
**Missing:** 63 columns
```
- post_id (REQUIRED - links vote to post)
- no_vote_option (REQUIRED - abstention flag)
- voting_code (REQUIRED - vote verification hash)
- candidate_01 through candidate_60 (REQUIRED - voter selections)
```

**Impact:** **THE ENTIRE VOTING SYSTEM CANNOT FUNCTION** without these columns. Votes cannot be stored, results cannot be calculated, verification is impossible.

**Action Required:** These columns MUST be added to migration `2026_03_01_000010_create_votes_table.php`

---

### 2. **`posts` table** - CRITICAL
**Missing:** 2 columns
```
- post_id (VARCHAR) - Primary identifier/unique code for post
- nepali_name (VARCHAR) - Nepali name for bilingual support
```

**Current state:** Only has: election_id, name, description, is_national_wide, state_name, required_number, select_all_required, position_order

**Impact:** Cannot identify posts uniquely, cannot provide Nepali translations.

**Action Required:** Add post_id and nepali_name to migration `2026_03_01_000004_create_posts_table.php`

---

### 3. **`candidacies` table** - CRITICAL
**Missing:** 7 columns
```
- candidacy_id (VARCHAR) - Primary identifier
- candidacy_name (VARCHAR) - Candidate name
- proposer_id (VARCHAR/INT) - Who nominated
- supporter_id (VARCHAR/INT) - Who supported
- image_path_1 (VARCHAR) - Photo 1
- image_path_2 (VARCHAR) - Photo 2
- image_path_3 (VARCHAR) - Photo 3
```

**Current state:** Only has: election_id, post_id, user_id, position_order, bio, photo_path, political_party, metadata

**Impact:** Cannot store or display candidate names, cannot attribute nominations, cannot display candidate photos.

**Action Required:** Add missing columns to migration `2026_03_01_000005_create_candidacies_table.php`

---

### 4. **`codes` table** - MEDIUM-HIGH PRIORITY
**Missing:** 6 columns
```
- code3 (VARCHAR) - Third verification code
- code4 (VARCHAR) - Fourth verification code
- vote_show_code (VARCHAR) - Code to show voter
- is_code3_usable (BOOLEAN) - Third code status
- is_code4_usable (BOOLEAN) - Fourth code status
- vote_last_seen (DATE) - When voter last viewed
```

**Note:** Current implementation appears to be **two-code system** (code1, code2). These may be legacy from **four-code system**. Need to verify if required.

**Decision:** If truly moving to two-code system, these can be skipped. Otherwise, must add.

---

### 5. **`results` table** - ARCHITECTURAL ISSUE
**Missing:** 1 column
```
- candidacy_id (VARCHAR/INT)
```

**Current new column:** `candidate_id`

**Impact:** **SCHEMA MISMATCH** - Old code references `candidacy_id` but new schema uses `candidate_id`. This mismatch breaks backward compatibility.

**Findings from code review:**
- `PhaseRefactor` changes in Phase 3 changed `candidacy_id` → `candidate_id`
- Some code still references old field names
- This is a **BREAKING CHANGE** that needs consistency across all files

**Action Required:** Audit all code references to ensure consistency. Consider adding BOTH columns for compatibility, or update all code to use `candidate_id` exclusively.

---

### 6. **`voter_slugs` table** - CRITICAL
**Missing:** 4+ columns
```
- user_id (BIGINT UNSIGNED) - Which user owns this slug
- expires_at (TIMESTAMP) - When slug expires
- is_active (BOOLEAN) - Whether slug is active
```

**Current state:** Only has: id, slug, timestamps

**Impact:** Cannot link slugs to users, cannot manage slug expiration, cannot deactivate slugs.

**Action Required:** Add missing columns to migration `2026_03_01_000008_create_voter_slugs_table.php`

---

### 7. **`voter_registrations` table** - HIGH PRIORITY
**Missing:** 7 columns
```
- election_type (ENUM) - Type of election
- registered_at (TIMESTAMP) - When registered
- approved_by (VARCHAR) - Who approved
- rejected_by (VARCHAR) - Who rejected
- rejection_reason (TEXT) - Why rejected
- metadata (JSON) - Additional data
- created_at (TIMESTAMP) - Creation timestamp
```

**Impact:** Cannot track voter approval history, cannot store rejection reasons, cannot maintain audit trail.

---

### 8. **`voter_slug_steps` table** - MEDIUM PRIORITY
**Missing:** 1 column
```
- step_data (JSON) - Data collected in step
```

**Impact:** Cannot store step-by-step voting data/responses.

---

### 9. **`demo_posts` table** - HIGH PRIORITY
**Missing:** 3 columns
```
- post_id (VARCHAR) - Post identifier
- nepali_name (VARCHAR) - Nepali post name
- organisation_id (BIGINT UNSIGNED) - Demo org scoping
```

**Impact:** Demo posts cannot be properly identified or scoped to organizations.

---

### 10. **`users` table** - LOW-MEDIUM PRIORITY
**Missing:** 2 columns
```
- current_team_id (BIGINT UNSIGNED) - Team support
- profile_photo_path (VARCHAR) - User photo
```

**Impact:** Lost team functionality and user profile photos. May be acceptable if teams are not used.

---

## 📋 TABLES COMPLETELY MISSING FROM NEW MIGRATIONS

These 31 tables exist in old migrations but are NOT in new consolidated migrations:

**Legacy/Support Tables (likely obsolete):**
- assignments, calendars, committee_types, committees, deligate_candidacies, deligate_votes
- events, google_accounts, images, incomes, jobs, messages, notices, openions
- outcomes, sessions, students, synchronizations, team_invitations, team_user, teams, uploads

**Demo Tables (CRITICAL - likely needed):**
- demo_candidacies, demo_codes, demo_results, demo_voter_slug_steps, demo_voter_slugs, demo_votes

**Standard Laravel (should exist):**
- failed_jobs, password_resets, personal_access_tokens

**Analysis:**
- If demo mode is supported, **demo_* tables MUST be restored**
- Legacy tables can probably be dropped
- Standard Laravel tables should be recreated

---

## ✅ NEW COLUMNS ADDED (INTENTIONAL IMPROVEMENTS)

These columns are NEW in consolidated migrations - likely intentional architecture changes:

### Positive Changes:
| Table | New Column | Purpose |
|-------|-----------|---------|
| candidacies | election_id, bio, photo_path, political_party, metadata | Better candidate data model |
| codes | election_id, organisation_id, timestamps, metadata | Multi-tenant support |
| elections | status, organisation_id | Better election management |
| organisations | email, address, representative, is_platform | Org profile data |
| posts | election_id, description, is_national_wide, select_all_required | Enhanced post model |
| results | election_id, candidate_id, vote_hash, vote_count | Verifiable anonymity support |
| users | two_factor_secret, organisation_id, can_vote, has_voted, voting_ip, region | Enhanced voter data |
| voter_registrations | suspended_at, approved_by_user, ip_address | Better tracking |

---

## 🛠️ RECOMMENDATION: THREE-PHASE FIX STRATEGY

### Phase 1: IMMEDIATE (BEFORE MIGRATION)
Create a new migration file `2026_03_01_015_add_missing_critical_columns.php` that adds:

**CRITICAL (application-breaking without these):**
1. votes: post_id, no_vote_option, voting_code, candidate_01...candidate_60
2. posts: post_id, nepali_name
3. candidacies: candidacy_id, candidacy_name, proposer_id, supporter_id, image_path_1/2/3
4. voter_slugs: user_id, expires_at, is_active
5. results: candidacy_id (for backward compatibility)

**HIGH PRIORITY (feature-breaking without these):**
6. codes: code3, code4, vote_show_code, is_code3_usable, is_code4_usable, vote_last_seen
7. demo_posts: post_id, nepali_name, organisation_id
8. voter_registrations: election_type, registered_at, approved_by, rejected_by, rejection_reason, metadata, created_at
9. voter_slug_steps: step_data

**MEDIUM PRIORITY (nice-to-have):**
10. users: current_team_id, profile_photo_path

---

### Phase 2: RESTORE DEMO TABLES (IF NEEDED)
If demo mode voting is still needed, restore:
- demo_candidacies
- demo_codes
- demo_results
- demo_voter_slug_steps
- demo_voter_slugs
- demo_votes

Create migration: `2026_03_01_016_restore_demo_tables.php`

---

### Phase 3: VERIFY & MIGRATE
1. Run the audit scripts again after adding missing columns
2. Verify no columns are missing
3. Execute `php artisan migrate:fresh` with confidence

---

## 📊 NEXT STEPS

1. **Review** this audit report
2. **Decide** which missing columns are truly required vs. nice-to-have
3. **Create** fix migrations for critical columns
4. **Test** migrations locally
5. **Verify** with audit scripts again
6. **Execute** migrate:fresh

---

## 🔗 Related Files

- Audit extraction: `database/audit_scripts/extract_old_columns.php`
- New columns data: `database/audit_scripts/old_columns.json`
- Comparison data: `database/audit_scripts/new_columns.json`
- Detailed report: `database/audit_scripts/comparison_report.json`

---

**Generated:** 2026-03-01
**Auditor:** Automated Migration Audit System
