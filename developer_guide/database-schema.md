# Database Schema Documentation

## Overview

This document provides complete schema details for both systems:
1. Voter Registration Flag System (users table modifications)
2. Demo/Real Election System (new tables)

---

## Users Table Modifications

### New Columns

```sql
ALTER TABLE users ADD COLUMN wants_to_vote BOOLEAN DEFAULT false AFTER is_voter;
ALTER TABLE users ADD COLUMN voter_registration_at TIMESTAMP NULL AFTER wants_to_vote;
```

### Column Definitions

| Column | Type | Nullable | Default | Index | Purpose |
|--------|------|----------|---------|-------|---------|
| `wants_to_vote` | BOOLEAN | NO | false | YES | User's intent to vote |
| `voter_registration_at` | TIMESTAMP | YES | NULL | NO | When user registered |

### Index Definition

```sql
CREATE INDEX idx_wants_voter ON users(wants_to_vote, is_voter);
```

**Why Composite Index?**
- Covers all voter state queries
- Supports fast filtering: `WHERE wants_to_vote = ? AND is_voter = ?`
- Improves queries finding customers, pending, approved voters

---

## Elections Table

### Schema

```sql
CREATE TABLE elections (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL COMMENT 'Election name',
    slug VARCHAR(255) UNIQUE NOT NULL COMMENT 'URL-friendly slug',
    description LONGTEXT COMMENT 'Election description',
    type ENUM('demo', 'real') DEFAULT 'demo' COMMENT 'Demo or real election',
    start_date DATETIME COMMENT 'When voting begins',
    end_date DATETIME COMMENT 'When voting ends',
    is_active BOOLEAN DEFAULT true COMMENT 'Is election currently active',
    settings JSON COMMENT 'Election configuration as JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_active (is_active),
    INDEX idx_type_active (type, is_active)
);
```

### Column Descriptions

| Column | Type | Size | Nullable | Default | Purpose |
|--------|------|------|----------|---------|---------|
| `id` | BIGINT UNSIGNED | - | NO | AUTO | Primary key |
| `name` | VARCHAR | 255 | NO | - | Election name |
| `slug` | VARCHAR | 255 | NO | - | URL slug (unique) |
| `description` | LONGTEXT | - | YES | NULL | Full description |
| `type` | ENUM | - | NO | 'demo' | Election type |
| `start_date` | DATETIME | - | YES | NULL | Start time |
| `end_date` | DATETIME | - | YES | NULL | End time |
| `is_active` | BOOLEAN | - | NO | true | Activity flag |
| `settings` | JSON | - | YES | NULL | Configuration |
| `created_at` | TIMESTAMP | - | NO | NOW | Created |
| `updated_at` | TIMESTAMP | - | NO | NOW | Updated |

### Sample Data

```json
{
  "id": 1,
  "name": "Demo Election",
  "slug": "demo-election",
  "description": "Test election to familiarize users",
  "type": "demo",
  "start_date": null,
  "end_date": null,
  "is_active": true,
  "settings": {
    "allow_multiple_registrations": false,
    "require_approval": true,
    "show_results": true
  },
  "created_at": "2026-02-03 19:38:00",
  "updated_at": "2026-02-03 19:38:00"
}
```

### Indexes

```sql
-- Find by type
INDEX idx_type (type)
EXPLAIN: WHERE type = 'demo'

-- Find active elections
INDEX idx_active (is_active)
EXPLAIN: WHERE is_active = 1

-- Combined filter
INDEX idx_type_active (type, is_active)
EXPLAIN: WHERE type = 'real' AND is_active = 1
```

---

## VoterRegistrations Table

### Schema

```sql
CREATE TABLE voter_registrations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,

    -- References (NO foreign keys by design)
    user_id BIGINT UNSIGNED NOT NULL COMMENT 'Reference to users.id',
    election_id BIGINT UNSIGNED NOT NULL COMMENT 'Reference to elections.id',

    -- Status
    status ENUM('pending', 'approved', 'rejected', 'voted') DEFAULT 'pending'
        COMMENT 'Registration status',

    -- Election type (cached from elections table)
    election_type ENUM('demo', 'real') DEFAULT 'demo'
        COMMENT 'Cached election type for performance',

    -- Timestamps
    registered_at DATETIME COMMENT 'When user registered',
    approved_at DATETIME COMMENT 'When approved',
    voted_at DATETIME COMMENT 'When vote submitted',

    -- Audit trail
    approved_by VARCHAR(255) COMMENT 'Who approved',
    rejected_by VARCHAR(255) COMMENT 'Who rejected',
    rejection_reason TEXT COMMENT 'Reason for rejection',

    -- Metadata
    metadata JSON COMMENT 'Additional data',

    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Constraints
    UNIQUE KEY unique_user_election (user_id, election_id),

    -- Indexes
    INDEX idx_user_type (user_id, election_type),
    INDEX idx_election_status (election_id, status),
    INDEX idx_type_status (election_type, status),
    INDEX idx_status (status),
    INDEX idx_created (created_at)
);
```

### Column Descriptions

| Column | Type | Size | Nullable | Default | Purpose |
|--------|------|------|----------|---------|---------|
| `id` | BIGINT UNSIGNED | - | NO | AUTO | Primary key |
| `user_id` | BIGINT UNSIGNED | - | NO | - | User reference |
| `election_id` | BIGINT UNSIGNED | - | NO | - | Election reference |
| `status` | ENUM | - | NO | 'pending' | Voter status |
| `election_type` | ENUM | - | NO | 'demo' | Election type (cached) |
| `registered_at` | DATETIME | - | YES | NULL | Registration time |
| `approved_at` | DATETIME | - | YES | NULL | Approval time |
| `voted_at` | DATETIME | - | YES | NULL | Voting time |
| `approved_by` | VARCHAR | 255 | YES | NULL | Approver name |
| `rejected_by` | VARCHAR | 255 | YES | NULL | Rejecter name |
| `rejection_reason` | TEXT | - | YES | NULL | Rejection reason |
| `metadata` | JSON | - | YES | NULL | Extended data |
| `created_at` | TIMESTAMP | - | NO | NOW | Created |
| `updated_at` | TIMESTAMP | - | NO | NOW | Updated |

### Sample Data

```json
{
  "id": 1,
  "user_id": 4,
  "election_id": 1,
  "status": "approved",
  "election_type": "demo",
  "registered_at": "2026-02-03 19:45:12",
  "approved_at": "2026-02-03 19:46:23",
  "voted_at": "2026-02-03 20:10:45",
  "approved_by": "Admin User",
  "rejected_by": null,
  "rejection_reason": null,
  "metadata": {
    "ip_address": "192.168.1.100",
    "browser": "Chrome 91",
    "device": "desktop"
  },
  "created_at": "2026-02-03 19:45:12",
  "updated_at": "2026-02-03 20:10:45"
}
```

### Constraints

#### UNIQUE Constraint

```sql
UNIQUE KEY unique_user_election (user_id, election_id)
```

**Purpose:** Prevents duplicate registrations for same user in same election

**Enforced:** User can only register once per election

### Indexes

```sql
-- Find all registrations for a user in demo elections
INDEX idx_user_type (user_id, election_type)
EXPLAIN: WHERE user_id = ? AND election_type = 'demo'

-- Find all pending voters for an election
INDEX idx_election_status (election_id, status)
EXPLAIN: WHERE election_id = ? AND status = 'pending'

-- Find all voted demo voters across all elections
INDEX idx_type_status (election_type, status)
EXPLAIN: WHERE election_type = 'demo' AND status = 'voted'

-- Find registrations by status (for statistics)
INDEX idx_status (status)
EXPLAIN: WHERE status = 'approved'

-- Find recent registrations
INDEX idx_created (created_at)
EXPLAIN: WHERE created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
```

---

## Relationships Diagram

```
┌─────────────┐
│   USERS     │
│─────────────│
│ id (PK)     │
│ name        │
│ email       │
│ wants_to_vote (NEW)     ←─┐
│ voter_registration_at (NEW) │
│ is_voter    │
│ can_vote    │
│ is_committee_member │
└─────────────┘
       ↓ (hasMany)
       │
       │ voterRegistrations()
       │
       ↓
┌────────────────────────┐
│ VOTER_REGISTRATIONS    │
│────────────────────────│
│ id (PK)                │
│ user_id (FK-like)      │ ←──── (no FK constraint)
│ election_id (FK-like)  │ ←──── (no FK constraint)
│ status                 │
│ election_type          │
│ registered_at          │
│ approved_at            │
│ voted_at               │
│ approved_by            │
│ rejected_by            │
│ rejection_reason       │
│ metadata               │
└────────────────────────┘
       ↑ (belongsTo)
       │
       │ election()
       │
       ↑
┌────────────────────────┐
│   ELECTIONS            │
│────────────────────────│
│ id (PK)                │
│ name                   │
│ slug                   │
│ description            │
│ type (demo/real)       │
│ start_date             │
│ end_date               │
│ is_active              │
│ settings               │
└────────────────────────┘
       ↓ (hasMany)
       │
       │ voterRegistrations()
       │
       ↓
  [VOTER_REGISTRATIONS]
```

---

## Data Types Guide

### BOOLEAN (wants_to_vote, is_active)

```sql
-- Storage: TINYINT(1)
-- Values: 0 = false, 1 = true
-- Query: WHERE wants_to_vote = 1 OR WHERE wants_to_vote = true

-- In PHP models:
protected $casts = ['wants_to_vote' => 'boolean'];
// Automatically converts to bool in PHP
```

### ENUM (type, status, election_type)

```sql
-- type enum('demo', 'real')
-- status enum('pending', 'approved', 'rejected', 'voted')
-- election_type enum('demo', 'real')

-- Advantages:
-- - Enforces valid values at DB level
-- - Memory efficient (stored as integers)
-- - Fast comparisons

-- Adding new enum value (if needed):
ALTER TABLE elections MODIFY COLUMN type ENUM('demo', 'real', 'custom') DEFAULT 'demo';
```

### JSON (settings, metadata)

```sql
-- Stores structured data as JSON
-- Supports partial indexing if needed

-- Usage:
SELECT * FROM elections WHERE JSON_EXTRACT(settings, '$.show_results') = true;

-- In PHP:
$election->settings = ['key' => 'value'];
$election->save();
// Automatically JSON encoded/decoded

// Access:
$showResults = $election->settings['show_results'] ?? false;
```

### DATETIME (timestamps)

```sql
-- registered_at, approved_at, voted_at
-- created_at, updated_at

-- Default: NULL (optional timestamps)
-- Updated automatically: created_at, updated_at

-- Usage:
WHERE registered_at IS NOT NULL  -- Has registered
WHERE voted_at IS NULL           -- Has not voted
WHERE approved_at < NOW()        -- Approved in past
```

---

## Performance Considerations

### Index Strategy

**Primary Use Cases:**

1. **Find all pending voters for approval**
   ```sql
   SELECT * FROM voter_registrations
   WHERE election_id = 1 AND status = 'pending'
   -- Uses: idx_election_status
   ```

2. **Find user's all registrations**
   ```sql
   SELECT * FROM voter_registrations
   WHERE user_id = 123 AND election_type = 'demo'
   -- Uses: idx_user_type
   ```

3. **Get election statistics**
   ```sql
   SELECT status, COUNT(*) FROM voter_registrations
   WHERE election_type = 'demo'
   GROUP BY status
   -- Uses: idx_type_status
   ```

### Query Optimization

**Avoid N+1 Queries:**
```php
// ❌ SLOW: N+1 problem
foreach (VoterRegistration::all() as $reg) {
    echo $reg->user->name;  // N additional queries
}

// ✅ FAST: Eager loading
VoterRegistration::with('user')->get();
```

**Use Scopes:**
```php
// ❌ SLOW: Manual query
VoterRegistration::whereStatus('pending')->get();

// ✅ FAST: Scope
VoterRegistration::pending()->get();
```

### Estimated Sizes

```
Assuming 10,000 users registered for elections:

voters_registrations table:
- 10,000 rows * ~500 bytes = ~5 MB
- With indexes: ~15 MB

elections table:
- 2 rows * ~1 KB = ~2 KB
- Negligible size

Total: ~15 MB for reasonable system size
```

---

## Migration Order

### Correct Order

```
1. Create elections table
   ↓
2. Create voter_registrations table
   ↓
3. Seed default elections
   ↓
4. System ready
```

### Why This Order?

- Elections must exist before voter_registrations references them
- Seeding must happen after table creation
- No circular dependencies

---

## Rollback Procedure

### Remove voter_registrations table

```sql
DROP TABLE voter_registrations;
```

### Remove elections table

```sql
DROP TABLE elections;
```

### Remove voter flags from users

```sql
ALTER TABLE users DROP COLUMN voter_registration_at;
ALTER TABLE users DROP COLUMN wants_to_vote;
DROP INDEX idx_wants_voter ON users;
```

### Verify cleanup

```sql
DESCRIBE users;  -- Should not have wants_to_vote
SHOW TABLES;     -- Should not have voter_registrations or elections
```

---

## Backup & Restore

### Export Elections

```bash
mysqldump -u user -p database elections > elections_backup.sql
```

### Export Voter Registrations

```bash
mysqldump -u user -p database voter_registrations > registrations_backup.sql
```

### Restore Elections

```bash
mysql -u user -p database < elections_backup.sql
```

### Full Database Backup

```bash
mysqldump -u user -p database > full_backup.sql
```

---

## Schema Validation Query

```sql
-- Verify all tables and columns exist
SELECT
    TABLE_NAME,
    COLUMN_NAME,
    COLUMN_TYPE,
    IS_NULLABLE,
    COLUMN_KEY
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME IN ('users', 'elections', 'voter_registrations')
ORDER BY TABLE_NAME, ORDINAL_POSITION;
```

---

## Index Statistics

### View Index Usage

```sql
-- Show index statistics
SELECT * FROM INFORMATION_SCHEMA.STATISTICS
WHERE TABLE_NAME IN ('elections', 'voter_registrations');

-- Show index cardinality (uniqueness)
SHOW INDEX FROM voter_registrations;
```

### Analyze Table Performance

```sql
-- Optimize table
OPTIMIZE TABLE voter_registrations;
OPTIMIZE TABLE elections;

-- Analyze table statistics
ANALYZE TABLE voter_registrations;
ANALYZE TABLE elections;
```

---

## Common Queries with Explain

### Query 1: Find Pending Demo Voters

```sql
EXPLAIN
SELECT vr.*, u.name, u.email
FROM voter_registrations vr
JOIN users u ON vr.user_id = u.id
WHERE vr.election_id = 1 AND vr.status = 'pending'
ORDER BY vr.created_at ASC;

-- Expected:
-- type: ref (uses index)
-- key: idx_election_status
-- rows: ~100 (depending on data)
```

### Query 2: Get User's Registrations

```sql
EXPLAIN
SELECT vr.*, e.name as election_name
FROM voter_registrations vr
JOIN elections e ON vr.election_id = e.id
WHERE vr.user_id = 123 AND vr.election_type = 'demo';

-- Expected:
-- type: ref (uses index)
-- key: idx_user_type
-- rows: ~1 (usually one per user per type)
```

### Query 3: Election Statistics

```sql
EXPLAIN
SELECT
    status,
    COUNT(*) as count,
    100 * COUNT(*) / (SELECT COUNT(*) FROM voter_registrations) as percentage
FROM voter_registrations
WHERE election_type = 'demo'
GROUP BY status;

-- Expected:
-- type: index (full index scan)
-- key: idx_type_status
-- rows: ~all records
```

---

## Size Estimation

### Table Sizes by Data Volume

| Users | Elections | Registrations | Users Table | Elections Table | Registrations Table |
|-------|-----------|---------------|-------------|-----------------|---------------------|
| 1,000 | 2 | 5,000 | 2 MB | 1 KB | 3 MB |
| 10,000 | 2 | 50,000 | 20 MB | 1 KB | 30 MB |
| 100,000 | 10 | 500,000 | 200 MB | 5 KB | 300 MB |
| 1,000,000 | 50 | 5,000,000 | 2 GB | 25 KB | 3 GB |

### With Indexes

```
elections:
- Primary index: ~1 KB
- Other indexes: ~5 KB
Total: ~6 KB (negligible)

voter_registrations:
- Primary index: ~10x table size
- Additional indexes: ~5x table size
Total: ~15x table size
```

---

## References

- MySQL 8.0 ENUM documentation
- MySQL 8.0 JSON documentation
- Index optimization guidelines
- Foreign key constraints (why not used)
