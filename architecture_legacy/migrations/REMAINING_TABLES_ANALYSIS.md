# Remaining Tables Consolidation Analysis

**Date:** 2026-03-02
**Status:** Analysis Complete - Ready for Consolidation
**Tables Analyzed:** 5 main tables + 5 demo tables

---

## 📊 CONSOLIDATION STATUS

### ELECTIONS TABLE ✅ COMPLETE

**Current DB State:** 13 columns
**New Migration:** 13 columns
**Status:** ✅ FULLY SYNCHRONIZED

**Columns:**
1. id ✅
2. name ✅
3. slug (unique) ✅
4. description (text, nullable) ✅
5. type (enum: demo/real) ✅
6. status (enum: planned/active/completed/archived) ✅ **[Added in old migration 2026_02_28]**
7. organisation_id (nullable FK) ✅
8. start_date (dateTime, nullable) ✅
9. end_date (dateTime, nullable) ✅
10. is_active (boolean, default true) ✅
11. settings (json, nullable) ✅
12. created_at ✅
13. updated_at ✅

**Actions Required:** NONE - Already consolidated ✅

---

### VOTER_SLUGS TABLE ✅ MOSTLY COMPLETE

**Current DB State:** 11 columns
**New Migration:** 11 columns
**Status:** ✅ SYNCHRONIZED

**Columns:**
1. id ✅
2. slug (unique) ✅
3. user_id ✅
4. election_id ✅
5. organisation_id (nullable) ✅
6. current_step (unsignedSmallInteger, default 1) ✅
7. step_meta (json, nullable) ✅
8. expires_at (timestamp, nullable) ✅
9. is_active (boolean, default 1) ✅
10. created_at ✅
11. updated_at ✅

**Actions Required:** NONE - Already consolidated ✅

---

### VOTER_SLUG_STEPS TABLE ⚠️ INCOMPLETE

**Current DB State:** 12 columns
**New Migration:** 10 columns
**Status:** ❌ **MISSING 2 COLUMNS**

**Current DB Columns:**
1. id ✅
2. voter_slug_id ✅
3. election_id ✅
4. **organisation_id** ❌ **MISSING FROM MIGRATION!**
5. step ✅
6. ip_address ✅
7. started_at ✅
8. completed_at ✅
9. metadata ✅
10. created_at ✅
11. updated_at ✅
12. **step_data** ❌ **MISSING FROM MIGRATION!**

**Missing in Migration:**
| Column | Type | Purpose | Old Migration |
|--------|------|---------|---|
| organisation_id | unsignedBigInteger | Multi-tenancy scoping | 2026_02_19_192313 |
| step_data | json, nullable | Additional step metadata | Unknown |

**Actions Required:**
1. ✏️ Add `organisation_id` to migration
2. ✏️ Add `step_data` to migration

---

### POSTS TABLE ✅ COMPLETE

**Current DB State:** 14 columns
**New Migration:** 14 columns
**Status:** ✅ FULLY SYNCHRONIZED

**Columns:**
1. id ✅
2. post_id (string, unique) ✅
3. election_id (FK) ✅
4. organisation_id (FK) ✅
5. name ✅
6. nepali_name (nullable) ✅
7. description (text, nullable) ✅ **[Added in old migrations]**
8. is_national_wide (boolean, default true) ✅
9. state_name (string, nullable) ✅
10. required_number (unsignedInteger, default 1) ✅
11. select_all_required (boolean, default true) ✅ **[Added in old migrations]**
12. position_order (unsignedInteger, default 0) ✅ **[Added in old migrations]**
13. created_at ✅
14. updated_at ✅

**Actions Required:** NONE - Already consolidated ✅

---

### CANDIDACIES TABLE 🔴 CRITICAL ISSUES

**Current DB State:** 18 columns
**New Migration:** DOES NOT EXIST ❌
**Status:** ❌ **NEEDS IMMEDIATE CONSOLIDATION**

**Current DB Columns:**
1. id ✅
2. election_id ✅ **[Added later]**
3. post_id ✅ **[Type changed: string → FK]**
4. user_id ✅ **[Type changed: string → FK]**
5. position_order ✅ **[Added later]**
6. bio ✅ **[Added later]**
7. photo_path ✅ **[Added later]**
8. political_party ✅ **[Added later]**
9. metadata ✅ **[Added later]**
10. created_at ✅
11. updated_at ✅
12. candidacy_id ❌ **[DEPRECATED - should be removed]**
13. candidacy_name ❌ **[DUPLICATE/UNUSED - should be removed]**
14. proposer_id ❌ **[DEPRECATED - was string FK]**
15. supporter_id ❌ **[DEPRECATED - was string FK]**
16. image_path_1 ❌ **[DEPRECATED]**
17. image_path_2 ❌ **[DEPRECATED]**
18. image_path_3 ❌ **[DEPRECATED]**

**Critical Issues:**
1. ❌ **NO new consolidated migration exists**
2. ❌ **18 columns with 7 deprecated ones**
3. ❌ **Old migrations used string types for FKs (should be unsignedBigInteger)**
4. ❌ **Duplicate column names (candidacy_id appears twice conceptually)**

**Actions Required:**
1. 🚨 CREATE new consolidated migration: `2026_03_01_000005_create_candidacies_table.php`
2. 🗑️ Remove deprecated columns: candidacy_id, candidacy_name, proposer_id, supporter_id, image_path_1/2/3
3. ✏️ Ensure FK columns have proper types

---

## 📝 DEMO TABLES STATUS

### Current State
```
demo_elections      - ❌ MISSING (needs to be created)
demo_voter_slugs    - ✅ EXISTS (24 columns)
demo_voter_slug_steps - ✅ EXISTS (12 columns)
demo_posts          - ✅ EXISTS (14 columns)
demo_candidacies    - ✅ EXISTS (24 columns)
```

### Demo Tables Issues

**demo_voter_slugs:**
- Has 24 columns (likely has extra legacy columns)
- Needs cleanup of deprecated columns

**demo_candidacies:**
- Has 24 columns (likely has extra legacy columns)
- Needs cleanup of deprecated columns

**demo_elections:**
- MISSING - needs to be created in 2026_03_01_000012_create_demo_tables.php

---

## 🔄 CONSOLIDATION STRATEGY

### Phase 1: Fix voter_slug_steps (Add Missing Columns)

**Actions:**
```php
// Add organisation_id
Schema::table('voter_slug_steps', function (Blueprint $table) {
    $table->unsignedBigInteger('organisation_id')->nullable()->after('election_id');
    $table->foreign('organisation_id')
          ->references('id')
          ->on('organisations')
          ->onDelete('set null');
    $table->index('organisation_id');
});

// Add step_data
Schema::table('voter_slug_steps', function (Blueprint $table) {
    $table->json('step_data')->nullable()->after('metadata');
});
```

### Phase 2: Create Candidacies Migration

**New Migration File:** `database/migrations/2026_03_01_000005_create_candidacies_table.php`

**Columns:**
```php
Schema::create('candidacies', function (Blueprint $table) {
    // Primary and Foreign Keys
    $table->id();
    $table->unsignedBigInteger('election_id');
    $table->unsignedBigInteger('post_id');
    $table->unsignedBigInteger('user_id');

    // Candidate Information
    $table->unsignedInteger('position_order')->default(0);
    $table->text('bio')->nullable();
    $table->string('photo_path')->nullable();
    $table->string('political_party')->nullable();

    // Metadata
    $table->json('metadata')->nullable();

    // Timestamps
    $table->timestamps();

    // Foreign Keys
    $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
    $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
    $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');

    // Indexes
    $table->index(['election_id', 'post_id']);
    $table->unique(['post_id', 'user_id']);
});
```

### Phase 3: Clean Up Deprecated Columns from Candidacies

**Actions:**
```php
// Drop deprecated columns from candidacies table
Schema::table('candidacies', function (Blueprint $table) {
    $table->dropColumn([
        'candidacy_id',
        'candidacy_name',
        'proposer_id',
        'supporter_id',
        'image_path_1',
        'image_path_2',
        'image_path_3'
    ]);
});
```

### Phase 4: Clean Up Demo Tables

**Actions:**
1. Identify and remove deprecated columns from demo_candidacies
2. Identify and remove deprecated columns from demo_voter_slugs
3. Create demo_elections table if missing

---

## 📊 Detailed Column Comparison

### CANDIDACIES Table - Old vs New Design

**OLD DESIGN (2021-2026):**
```
id, candidacy_id, user_id, post_id, proposer_id, supporter_id,
image_path_1, image_path_2, image_path_3,
+ election_id (added 2026-02)
+ position_order (added 2026-02)
+ bio, photo_path, political_party, metadata (added 2026-02)
```

**NEW DESIGN (Consolidated):**
```
id, election_id, post_id, user_id, position_order,
bio, photo_path, political_party, metadata,
created_at, updated_at
```

**Removed Columns (No Longer Used):**
- candidacy_id (redundant - use id instead)
- candidacy_name (never actually used)
- proposer_id (legacy feature, not in use)
- supporter_id (legacy feature, not in use)
- image_path_1, image_path_2, image_path_3 (replaced by single photo_path)

---

## ✅ COMPLETE CONSOLIDATION CHECKLIST

| # | Table | Task | Priority | Status |
|----|-------|------|----------|--------|
| 1 | elections | Verify consolidation | ✅ Done | Complete |
| 2 | voter_slugs | Verify consolidation | ✅ Done | Complete |
| 3 | voter_slug_steps | Add missing columns | 🔴 High | Pending |
| 4 | posts | Verify consolidation | ✅ Done | Complete |
| 5 | candidacies | Create migration | 🔴 Critical | Pending |
| 6 | candidacies | Remove deprecated columns | 🔴 High | Pending |
| 7 | demo_elections | Create table | 🟡 Medium | Pending |
| 8 | demo_candidacies | Clean deprecated columns | 🟡 Medium | Pending |
| 9 | demo_voter_slugs | Clean deprecated columns | 🟡 Medium | Pending |

---

## 🎯 EXECUTION ORDER

1. **Add missing columns to voter_slug_steps**
   - Add organisation_id
   - Add step_data

2. **Create candidacies migration**
   - New consolidated migration file
   - Clean data by removing deprecated columns

3. **Remove deprecated candidacies columns**
   - Use Tinker to drop columns from database

4. **Ensure demo tables match production**
   - Create demo_elections if missing
   - Sync demo_candidacies structure
   - Sync demo_voter_slugs structure

5. **Final verification**
   - All tables have matching columns
   - No deprecated columns remain
   - All foreign keys enforced

---

## 📈 Progress Tracking

**Tables Analyzed:** 5/5 ✅
**Tables Complete:** 3/5 (elections, voter_slugs, posts)
**Tables Needing Work:** 2/5 (voter_slug_steps, candidacies)
**Demo Tables Status:** Needs investigation + cleanup

