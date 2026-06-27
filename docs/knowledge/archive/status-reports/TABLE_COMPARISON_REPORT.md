# Real Tables vs Demo Tables - Comprehensive Comparison

## 📊 Summary

| Table Pair | Status | Differences Found |
|-----------|--------|-------------------|
| votes vs demo_votes | ⚠️ MINOR | Real has 2 extra indexes |
| results vs demo_results | ✅ MOSTLY IDENTICAL | Real has 1 extra index |
| posts vs demo_posts | ⚠️ MINOR | Real has 1 extra index |
| candidacies vs demo_candidacies | ❌ MAJOR | Demo has 13 extra columns |
| codes vs demo_codes | ❌ MAJOR | Demo has 6 extra columns, Real has missing columns + more indexes |

---

## 1️⃣ votes vs demo_votes

### Columns: ✅ IDENTICAL
Both have: id, election_id, organisation_id, vote_hash, candidate_01-60, no_vote_posts, metadata, cast_at, timestamps

### Indexes: ⚠️ Real has MORE

**votes** (real):
- election_id
- organisation_id
- vote_hash
- cast_at ⭐ EXTRA
- [election_id, organisation_id] ⭐ EXTRA

**demo_votes**:
- election_id
- organisation_id
- vote_hash

**Missing in demo_votes**:
- cast_at index
- [election_id, organisation_id] composite index

---

## 2️⃣ results vs demo_results

### Columns: ✅ IDENTICAL (after recent fix)
Both have: id, vote_id, election_id, post_id, candidate_id, organisation_id, vote_hash, vote_count, timestamps

### Indexes: ⚠️ Real has ONE extra

**results** (real):
- [election_id, post_id]
- [post_id, candidate_id] ⭐ EXTRA
- [election_id, organisation_id]
- vote_hash

**demo_results**:
- [election_id, post_id]
- [election_id, organisation_id]
- vote_hash

**Missing in demo_results**:
- [post_id, candidate_id] index

---

## 3️⃣ posts vs demo_posts

### Columns: ✅ IDENTICAL
Both have: id, election_id, name, description, is_national_wide, state_name, required_number, select_all_required, position_order, timestamps

### Indexes: ⚠️ Real has MORE

**posts** (real):
- [election_id, is_national_wide]
- [election_id, state_name] ⭐ EXTRA

**demo_posts**:
- [election_id, is_national_wide]

**Missing in demo_posts**:
- [election_id, state_name] index

---

## 4️⃣ candidacies vs demo_candidacies

### Columns: ❌ MAJOR DIFFERENCES

**candidacies** (real) has:
- id, election_id, post_id, user_id
- position_order, bio, photo_path, political_party, metadata
- timestamps

**demo_candidacies** has ALL above PLUS 13 extra columns:
1. organisation_id ⭐
2. candidacy_id ⭐
3. user_name ⭐
4. candidacy_name ⭐
5. proposer_name ⭐
6. supporter_name ⭐
7. post_name ⭐
8. post_nepali_name ⭐
9. proposer_id ⭐
10. supporter_id ⭐
11. image_path_1 ⭐
12. image_path_2 ⭐
13. image_path_3 ⭐

### Indexes: ⚠️ Real has MORE

**candidacies** (real):
- [election_id, post_id]
- [post_id, position_order] ⭐ EXTRA
- UNIQUE[post_id, user_id]

**demo_candidacies**:
- [election_id, post_id]
- UNIQUE[post_id, user_id]

**Missing in candidacies (real)**:
- 13 demo-specific columns

**Missing in demo_candidacies**:
- [post_id, position_order] index

---

## 5️⃣ codes vs demo_codes

### Columns: ❌ MAJOR DIFFERENCES

**codes** (real) has:
- id, election_id, user_id, organisation_id
- code1, code2
- is_code1_usable, code1_used_at, is_code2_usable, code2_used_at
- can_vote_now, has_voted, vote_submitted
- voting_started_at ⭐ (demo doesn't have)
- has_code1_sent, code1_sent_at, has_code2_sent, code2_sent_at
- expires_at, voting_time_minutes
- metadata, timestamps

**demo_codes** has:
- id, election_id, user_id, organisation_id
- code1, code2, code3 ⭐, code4 ⭐
- is_code1_usable, code1_used_at, is_code2_usable, code2_used_at
- is_code3_usable ⭐, code3_used_at ⭐, is_code4_usable ⭐, code4_used_at ⭐
- can_vote_now, has_voted, vote_submitted
- has_code1_sent, code1_sent_at, has_code2_sent, code2_sent_at
- expires_at, voting_time_minutes
- vote_show_code ⭐, vote_last_seen ⭐
- metadata, timestamps

**Missing in codes (real)**:
- code3, code4
- is_code3_usable, code3_used_at
- is_code4_usable, code4_used_at
- vote_show_code
- vote_last_seen

**Missing in demo_codes**:
- voting_started_at

### Indexes & Foreign Keys

**codes** (real):
- UNIQUE[election_id, user_id]
- code1, code2 ⭐, [is_code1_usable, can_vote_now] ⭐, expires_at ⭐
- organisation_id has foreign key ⭐

**demo_codes**:
- UNIQUE[election_id, user_id]
- code1 only
- organisation_id has NO foreign key

**Missing in codes (real)**: None (has more)

**Missing in demo_codes**:
- code2 index
- [is_code1_usable, can_vote_now] composite index
- expires_at index
- organisation_id foreign key constraint

---

## 🎯 Action Items to Make Tables Identical

### Quick Fixes (Indexes Only)

#### 1. demo_votes - Add 2 indexes
```sql
CREATE INDEX demo_votes_cast_at_index ON demo_votes(cast_at);
CREATE INDEX demo_votes_election_organisation_index ON demo_votes(election_id, organisation_id);
```

#### 2. demo_posts - Add 1 index
```sql
CREATE INDEX demo_posts_election_state_index ON demo_posts(election_id, state_name);
```

#### 3. demo_results - Add 1 index
```sql
CREATE INDEX demo_results_post_candidate_index ON demo_results(post_id, candidate_id);
```

#### 4. demo_candidacies - Add 1 index
```sql
CREATE INDEX demo_candidacies_post_position_index ON demo_candidacies(post_id, position_order);
```

#### 5. demo_codes - Add indexes + foreign key
```sql
CREATE INDEX demo_codes_code2_index ON demo_codes(code2);
CREATE INDEX demo_codes_usable_vote_index ON demo_codes(is_code1_usable, can_vote_now);
CREATE INDEX demo_codes_expires_index ON demo_codes(expires_at);
ALTER TABLE demo_codes ADD CONSTRAINT demo_codes_organisation_id_foreign
    FOREIGN KEY (organisation_id) REFERENCES organisations(id) ON DELETE SET NULL;
```

### Major Changes (Columns)

#### 6. codes - Add 4-code system + verification columns
```sql
ALTER TABLE codes ADD COLUMN code3 VARCHAR(255) NULL AFTER code2;
ALTER TABLE codes ADD COLUMN code4 VARCHAR(255) NULL AFTER code3;
ALTER TABLE codes ADD COLUMN is_code3_usable BOOLEAN DEFAULT 0 AFTER is_code2_usable;
ALTER TABLE codes ADD COLUMN code3_used_at TIMESTAMP NULL AFTER code2_used_at;
ALTER TABLE codes ADD COLUMN is_code4_usable BOOLEAN DEFAULT 0 AFTER is_code3_usable;
ALTER TABLE codes ADD COLUMN code4_used_at TIMESTAMP NULL AFTER code3_used_at;
ALTER TABLE codes ADD COLUMN vote_show_code VARCHAR(255) NULL;
ALTER TABLE codes ADD COLUMN vote_last_seen TIMESTAMP NULL;
```

#### 7. demo_codes - Add missing column
```sql
ALTER TABLE demo_codes ADD COLUMN voting_started_at TIMESTAMP NULL AFTER vote_submitted;
```

#### 8. candidacies - Add all demo columns (optional)
```sql
ALTER TABLE candidacies ADD COLUMN organisation_id BIGINT UNSIGNED NULL;
ALTER TABLE candidacies ADD COLUMN candidacy_id VARCHAR(255) NULL;
ALTER TABLE candidacies ADD COLUMN user_name VARCHAR(255) NULL;
ALTER TABLE candidacies ADD COLUMN candidacy_name VARCHAR(255) NULL;
ALTER TABLE candidacies ADD COLUMN proposer_name VARCHAR(255) NULL;
ALTER TABLE candidacies ADD COLUMN supporter_name VARCHAR(255) NULL;
ALTER TABLE candidacies ADD COLUMN post_name VARCHAR(255) NULL;
ALTER TABLE candidacies ADD COLUMN post_nepali_name VARCHAR(255) NULL;
ALTER TABLE candidacies ADD COLUMN proposer_id BIGINT UNSIGNED NULL;
ALTER TABLE candidacies ADD COLUMN supporter_id BIGINT UNSIGNED NULL;
ALTER TABLE candidacies ADD COLUMN image_path_1 VARCHAR(255) NULL;
ALTER TABLE candidacies ADD COLUMN image_path_2 VARCHAR(255) NULL;
ALTER TABLE candidacies ADD COLUMN image_path_3 VARCHAR(255) NULL;
ALTER TABLE candidacies ADD CONSTRAINT candidacies_organisation_id_foreign
    FOREIGN KEY (organisation_id) REFERENCES organisations(id) ON DELETE CASCADE;
```

---

## ⚖️ Recommendations

### Immediate Actions (Low Risk, High Value)
✅ **Add all missing indexes to demo tables** - Performance optimization, no breaking changes

### Consider Carefully
⚠️ **Add 4-code system to real codes table** - If you plan to use this feature in production
⚠️ **Add demo candidacy columns to real candidacies** - If you want richer candidate data in production

### Current Best Practice
🎯 **Sync indexes NOW, decide on columns later** - Get performance benefits without structural changes

---

**Status**: Analysis complete. Ready to apply changes based on your decision.
