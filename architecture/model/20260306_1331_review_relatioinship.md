## 📋 **CLAUDE CLI PROMPT: Review Model & Database Implementation**

```bash
## TASK: Comprehensive Review of Model & Database Implementation

### Context
We have completed multiple phases of implementation. Before proceeding to controller/service level, I need you to perform a thorough review of all models and database schema to ensure:

1. All models have correct relationships
2. All database columns exist and are properly defined
3. No inconsistencies between phases
4. All anonymity rules are enforced
5. Device fingerprinting is properly implemented

### Review Scope

#### PHASE A: Core Models (6 models)
- [ ] Organisation
- [ ] User
- [ ] UserOrganisationRole
- [ ] Election
- [ ] Post
- [ ] Candidacy

#### PHASE B: Voting Models (4 models)
- [ ] Code
- [ ] VoterSlug
- [ ] Vote
- [ ] Result

#### PHASE C: Demo Models (4 models)
- [ ] DemoCode
- [ ] DemoVoterSlug
- [ ] DemoVote
- [ ] DemoResult

### Review Checklist

For EACH model, verify:

#### 1. Model File Structure
```php
- [ ] Use HasUuids trait
- [ ] Use SoftDeletes trait (if applicable)
- [ ] protected $keyType = 'string'
- [ ] public $incrementing = false
- [ ] protected $table = 'correct_table_name'
- [ ] protected $fillable array matches database columns
- [ ] protected $casts for JSON/datetime fields
```

#### 2. Database Columns (Compare with migrations)
```sql
- [ ] All columns in $fillable exist in database
- [ ] No extra columns in $fillable not in database
- [ ] Foreign keys properly defined
- [ ] Indexes on frequently queried columns
- [ ] UUID primary keys (not integers)
- [ ] organisation_id foreign key on ALL tenant tables
```

#### 3. Relationships
```php
- [ ] belongsTo relationships defined correctly
- [ ] hasMany relationships defined correctly
- [ ] belongsToMany relationships (with pivot)
- [ ] hasManyThrough relationships (where needed)
- [ ] Proper foreign/local keys specified
- [ ] withoutGlobalScopes() where needed for relationships
```

#### 4. Anonymity Enforcement (CRITICAL)
```php
- [ ] Vote model has NO user() relationship
- [ ] Vote model has NO code() relationship
- [ ] Vote model has NO voterSlug() relationship (one-way only)
- [ ] Result model has NO user() relationship
- [ ] Code model has NO vote() relationship
- [ ] Code model has NO result() relationship
```

#### 5. Device Fingerprinting (NEW)
```php
- [ ] Code model has device_fingerprint_hash column
- [ ] Code model has device_metadata_anonymized column
- [ ] DemoCode model has same columns
- [ ] Index on device_fingerprint_hash
- [ ] NO device data in Vote/Result models
```

#### 6. Verification Columns (Phase C.4)
```php
- [ ] Vote model has receipt_hash column
- [ ] Vote model has participation_proof column
- [ ] Vote model has encrypted_vote column
- [ ] DemoVote has same columns
- [ ] NO vote_hash column (should be removed)
- [ ] NO voting_code column (should be removed)
```

#### 7. Scopes
```php
- [ ] forOrganisation() scope on all tenant models
- [ ] forElection() scope on election-related models
- [ ] active() scope where applicable
- [ ] fromDevice() scope on Code model
```

### Expected Output Format

For EACH model, provide:

```markdown
## Model: [ModelName]

### ✅ Correct
- [List what's implemented correctly]

### ❌ Missing/Issues
- [List what's missing or needs fixing]

### 🔧 Required Actions
- [Step-by-step fixes needed]

### 📊 Database Schema Check
| Column | Exists? | Type | Index? | Notes |
|--------|---------|------|--------|-------|
| id | ✅ | UUID | PK | |
| organisation_id | ✅ | UUID | ✅ | FK |
| ... | | | | |
```

### Summary Table

After reviewing all models, provide:

| Model | Traits | Fillable | Relationships | Anonymity | Device Data | Status |
|-------|--------|----------|---------------|-----------|-------------|--------|
| Organisation | ✅ | ✅ | ✅ | N/A | N/A | ✅ |
| User | ✅ | ✅ | ✅ | ✅ | N/A | ✅ |
| ... | | | | | | |

### Overall Status
- [ ] All 14 models reviewed
- [ ] Total issues found: X
- [ ] Critical anonymity violations: X
- [ ] Missing columns: X
- [ ] Incorrect relationships: X

### Next Steps Recommendation
Based on the review, what should be fixed before proceeding to controller layer?

---

**Execute this review and provide comprehensive findings.** 
```