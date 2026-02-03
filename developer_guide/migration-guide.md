# Migration Guide

## Overview

This guide covers the execution, verification, and troubleshooting of the two migration systems implemented.

---

## Phase 1: Voter Registration Flag Migration

### Migration File

**Location:** `database/migrations/2026_02_03_193521_add_wants_to_vote_flag_to_users_table.php`

**What it does:**
1. Adds `wants_to_vote` column (boolean, default false)
2. Adds `voter_registration_at` column (timestamp, nullable)
3. Creates composite index `idx_wants_voter`
4. Automatically populates data based on current state

### Execution

```bash
# Run specific migration
php artisan migrate --step

# Output should show:
# Migrating: 2026_02_03_193521_add_wants_to_vote_flag_to_users_table
# Migrated: 2026_02_03_193521_add_wants_to_vote_flag_to_users_table (XXXms)
```

### Data Migration Logic

When migration runs, it automatically categorizes users:

```php
// Committee members → wants_to_vote = false
// Pending voters (is_voter=0, can_vote=0) → wants_to_vote = true
// Approved voters (is_voter=1, can_vote=1) → wants_to_vote = true
// Suspended voters (is_voter=1, can_vote=0) → wants_to_vote = true
```

### Verification

```bash
# Check columns exist
php artisan tinker
>>> DB::getSchemaBuilder()->getColumnListing('users')
// Should include: 'wants_to_vote', 'voter_registration_at'

# Check index exists
>>> DB::connection()->getDoctrineSchemaManager()->listTableIndexes('users')
// Should include: 'idx_wants_voter'

# Check data populated
>>> User::where('wants_to_vote', 1)->count()
>>> User::where('wants_to_vote', 0)->count()
```

### Rollback

```bash
# Rollback last migration
php artisan migrate:rollback --step=1

# Output:
# Rolling back: 2026_02_03_193521_add_wants_to_vote_flag_to_users_table
# Rolled back: 2026_02_03_193521_add_wants_to_vote_flag_to_users_table
```

**Warning:** Rollback removes columns. Export data first if needed:

```bash
php artisan tinker
>>> $data = User::select('id', 'wants_to_vote', 'voter_registration_at')->get();
>>> Storage::put('voter_backup.json', $data->toJson());
```

---

## Phase 2: Election System Migrations

### Elections Migration

**Location:** `database/migrations/2026_02_03_193800_create_elections_table.php`

**Creates:**
```sql
CREATE TABLE elections (
    id BIGINT UNSIGNED PRIMARY KEY,
    name VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    description LONGTEXT,
    type ENUM('demo', 'real'),
    start_date DATETIME,
    end_date DATETIME,
    is_active BOOLEAN,
    settings JSON,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### VoterRegistrations Migration

**Location:** `database/migrations/2026_02_03_193900_create_voter_registrations_table.php`

**Creates:**
```sql
CREATE TABLE voter_registrations (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id BIGINT UNSIGNED,
    election_id BIGINT UNSIGNED,
    status ENUM('pending', 'approved', 'rejected', 'voted'),
    election_type ENUM('demo', 'real'),
    registered_at DATETIME,
    approved_at DATETIME,
    voted_at DATETIME,
    approved_by VARCHAR(255),
    rejected_by VARCHAR(255),
    rejection_reason TEXT,
    metadata JSON,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    UNIQUE(user_id, election_id),
    INDEX idx_user_type (user_id, election_type),
    INDEX idx_election_status (election_id, status)
);
```

### Execution

```bash
# Run both election migrations
php artisan migrate --step

# Output should show:
# Migrating: 2026_02_03_193800_create_elections_table
# Migrated: 2026_02_03_193800_create_elections_table (XXXms)
# Migrating: 2026_02_03_193900_create_voter_registrations_table
# Migrated: 2026_02_03_193900_create_voter_registrations_table (XXXms)
```

### Verification

```bash
php artisan tinker

# Check tables exist
>>> DB::getSchemaBuilder()->hasTable('elections')           // true
>>> DB::getSchemaBuilder()->hasTable('voter_registrations') // true

# Check columns
>>> DB::getSchemaBuilder()->getColumnListing('elections')
>>> DB::getSchemaBuilder()->getColumnListing('voter_registrations')

# Check indexes
>>> DB::connection()->getDoctrineSchemaManager()
    ->listTableIndexes('voter_registrations')
```

### Rollback

```bash
# Rollback one migration
php artisan migrate:rollback --step=1

# Rollback multiple migrations
php artisan migrate:rollback --steps=2
```

---

## Seeding

### ElectionSeeder

**Location:** `database/seeders/ElectionSeeder.php`

**Creates:**
- Demo Election (type: demo, is_active: true)
- Real Election (type: real, is_active: false)

### Execution

```bash
# Run seeder
php artisan db:seed --class=ElectionSeeder

# Output:
# Elections seeded successfully!
# Database seeding completed successfully.
```

### Verification

```bash
php artisan tinker

# Check elections created
>>> App\Models\Election::count()              // Should be 2
>>> App\Models\Election::where('type', 'demo')->exists()  // true
>>> App\Models\Election::where('type', 'real')->exists()  // true

# Check data
>>> $demo = App\Models\Election::where('type', 'demo')->first()
>>> dd($demo)
```

### Manual Creation (Alternative)

If you prefer to create elections manually:

```bash
php artisan tinker

>>> App\Models\Election::create([
    'name' => 'Demo Election',
    'slug' => 'demo-election',
    'type' => 'demo',
    'is_active' => true,
]);

>>> App\Models\Election::create([
    'name' => 'Real Election',
    'slug' => 'real-election',
    'type' => 'real',
    'is_active' => false,
]);
```

---

## Complete Migration Sequence

### Step-by-Step

```bash
# Step 1: Run Phase 1 migration
php artisan migrate --step
# Adds wants_to_vote to users table

# Step 2: Run Phase 2 migrations
php artisan migrate --step
# Creates elections table

php artisan migrate --step
# Creates voter_registrations table

# Step 3: Seed default elections
php artisan db:seed --class=ElectionSeeder

# Step 4: Verify
php artisan tinker
# Verify all tables and data
```

### All at Once

```bash
# Run all pending migrations
php artisan migrate

# Seed database
php artisan db:seed --class=ElectionSeeder
```

---

## Verification Checklist

After migrations and seeding, verify:

- [ ] `users` table has `wants_to_vote` column
- [ ] `users` table has `voter_registration_at` column
- [ ] `elections` table exists with 2 records
- [ ] `voter_registrations` table exists and is empty
- [ ] All indexes created successfully
- [ ] Demo election is active
- [ ] Real election is inactive
- [ ] Models can be instantiated
- [ ] Relationships work

### Verification Script

```bash
php artisan tinker

# Check tables
echo "=== TABLE CHECKS ===";
echo "users has wants_to_vote: " . (DB::getSchemaBuilder()->hasColumn('users', 'wants_to_vote') ? "YES" : "NO") . "\n";
echo "elections table exists: " . (DB::getSchemaBuilder()->hasTable('elections') ? "YES" : "NO") . "\n";
echo "voter_registrations table exists: " . (DB::getSchemaBuilder()->hasTable('voter_registrations') ? "YES" : "NO") . "\n";

# Check data
echo "\n=== DATA CHECKS ===";
echo "Elections count: " . App\Models\Election::count() . "\n";
echo "Demo election exists: " . (App\Models\Election::where('type', 'demo')->exists() ? "YES" : "NO") . "\n";
echo "Real election exists: " . (App\Models\Election::where('type', 'real')->exists() ? "YES" : "NO") . "\n";
echo "Voter registrations count: " . App\Models\VoterRegistration::count() . " (should be 0)\n";

# Check models
echo "\n=== MODEL CHECKS ===";
echo "Election model exists: " . (class_exists('App\Models\Election') ? "YES" : "NO") . "\n";
echo "VoterRegistration model exists: " . (class_exists('App\Models\VoterRegistration') ? "YES" : "NO") . "\n";

# Check relationships
echo "\n=== RELATIONSHIP CHECKS ===";
$demo = App\Models\Election::where('type', 'demo')->first();
echo "Demo election voters: " . ($demo->voterRegistrations() ? "YES" : "NO") . "\n";

$user = App\Models\User::first();
echo "User voter registrations: " . ($user->voterRegistrations() ? "YES" : "NO") . "\n";

echo "\n✅ All checks completed!\n";
```

---

## Migration Troubleshooting

### Issue: "Table already exists"

```
SQLSTATE[HY000]: General error: 1025
Operation failed: 'elections' already exists
```

**Solution:**
```bash
# Check if table exists
php artisan tinker
>>> DB::getSchemaBuilder()->hasTable('elections')

# If true, either:
# 1. Migration already ran - skip it
# 2. Drop table and rerun migration
>>> DB::statement('DROP TABLE IF EXISTS elections');
>>> php artisan migrate --step
```

### Issue: "Duplicate column"

```
SQLSTATE[HY000]: General error: 1060
Duplicate column name 'wants_to_vote'
```

**Solution:**
```bash
# Column already exists - migration already ran
# Or manually added. Skip this migration.

php artisan tinker
>>> DB::getSchemaBuilder()->hasColumn('users', 'wants_to_vote')
```

### Issue: "Cannot add or update a child row"

```
SQLSTATE[HY000]: General error: 1452
Cannot add or update a child row: a foreign key constraint fails
```

**Solution:**
This shouldn't happen as we don't use foreign keys. If it does:

```bash
# Check for orphaned references
php artisan tinker
>>> DB::table('voter_registrations')
    ->whereNotIn('election_id', DB::table('elections')->pluck('id'))
    ->count()

# If > 0, delete orphans:
>>> DB::table('voter_registrations')
    ->whereNotIn('election_id', DB::table('elections')->pluck('id'))
    ->delete();
```

### Issue: "Index too long"

```
SQLSTATE[HY000]: General error: 1071
Specified key was too long; max key length is 3072 bytes
```

**Solution:**
```bash
# Reduce column sizes (rare with our schema)
# Or use database prefix to shorten index names

# Check index definition
SHOW INDEX FROM voter_registrations;
```

### Issue: Migration doesn't run

```
Nothing to migrate
```

**Solution:**
```bash
# Check migration status
php artisan migrate:status

# If migration shows "Ran", it already executed
# If migration is missing from list, check:
# 1. File exists in database/migrations
# 2. Filename matches Laravel convention: YYYY_MM_DD_HHMMSS_*.php

# Force run all migrations
php artisan migrate:refresh
```

---

## Rollback Procedures

### Rollback Last Migration

```bash
php artisan migrate:rollback --step=1
```

### Rollback Last 2 Migrations

```bash
php artisan migrate:rollback --steps=2
```

### Rollback All Migrations

```bash
php artisan migrate:refresh
```

**WARNING:** `migrate:refresh` runs all migrations again. Better for development only.

### Backup Before Rollback

```bash
# Export user data
php artisan tinker
>>> $users = User::select('id', 'email', 'wants_to_vote', 'voter_registration_at')->get();
>>> Storage::put('users_backup.json', $users->toJson());

# Export elections
>>> $elections = App\Models\Election::all();
>>> Storage::put('elections_backup.json', $elections->toJson());

# Then rollback
php artisan migrate:rollback --step=3
```

---

## Testing After Migration

### Unit Test Example

```php
// tests/Unit/MigrationTest.php
public function test_wants_to_vote_column_exists()
{
    $this->assertTrue(
        Schema::hasColumn('users', 'wants_to_vote')
    );
}

public function test_elections_table_exists()
{
    $this->assertTrue(
        Schema::hasTable('elections')
    );
}

public function test_voter_registrations_table_exists()
{
    $this->assertTrue(
        Schema::hasTable('voter_registrations')
    );
}
```

### Run Tests

```bash
php artisan test tests/Unit/MigrationTest.php
```

---

## Production Deployment

### Pre-Deployment Checklist

- [ ] Backup database
- [ ] Test migrations on staging
- [ ] Verify data migration logic
- [ ] Check for conflicts with existing migrations
- [ ] Prepare rollback plan

### Deployment Steps

```bash
# 1. Backup production database
mysqldump -u user -p database > backup_$(date +%Y%m%d).sql

# 2. Deploy code changes
git pull origin main

# 3. Run migrations
php artisan migrate

# 4. Seed elections
php artisan db:seed --class=ElectionSeeder

# 5. Clear cache
php artisan cache:clear

# 6. Verify
php artisan tinker
# Run verification script (see above)
```

### Rollback Plan

If something goes wrong:

```bash
# 1. Stop application
# 2. Restore from backup
mysql -u user -p database < backup_YYYYMMDD.sql

# 3. Or rollback code and migrations
git revert <commit>
php artisan migrate:rollback --steps=3
```

---

## Performance Impact

### Expected Performance

- **Migration execution time:** 1-2 seconds (total)
- **No downtime required**
- **Data migration:** Automatic, no manual intervention
- **Indexes:** Created atomically

### Monitoring

Monitor these metrics after deployment:

```sql
-- Check slow queries
SHOW PROCESSLIST;

-- Check table statistics
ANALYZE TABLE users;
ANALYZE TABLE elections;
ANALYZE TABLE voter_registrations;

-- Check index usage
SELECT * FROM INFORMATION_SCHEMA.STATISTICS
WHERE TABLE_NAME IN ('users', 'elections', 'voter_registrations');
```

---

## Documentation After Migration

### Update README

Add note about new features:

```markdown
## Voter Registration & Elections

This system now includes:
- Voter registration flag system (Phase 1)
- Multi-election support with demo/real elections (Phase 2)

See `developer_guide/` for complete documentation.
```

### Update Changelog

```markdown
## Version 1.1 - 2026-02-03

### Added
- Voter registration flag system
  - `wants_to_vote` column to separate customers from voters
  - `voter_registration_at` timestamp for tracking
- Demo/Real election system
  - Multi-election support
  - Complete voter registration workflow
  - Audit trails and metadata tracking
```

---

## References

- Laravel Migration Documentation
- Database Schema Documentation (see `database-schema.md`)
- Troubleshooting Guide (see `troubleshooting.md`)
