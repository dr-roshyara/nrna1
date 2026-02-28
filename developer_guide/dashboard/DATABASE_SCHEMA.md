# Database Schema Documentation

## Overview

The three-role dashboard system uses 5 primary tables:
1. **users** - User accounts (extended with legacy columns)
2. **organisations** - organisation records (NEW)
3. **user_organization_roles** - User-to-org role mapping (NEW)
4. **elections** - Election records (extended)
5. **election_commission_members** - Commission member assignments (NEW)

## Detailed Table Schemas

### 1. users Table

**Purpose:** User account storage with legacy column support

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,

    -- LEGACY COLUMNS (for backward compatibility)
    is_voter BOOLEAN DEFAULT FALSE,
    is_committee_member BOOLEAN DEFAULT FALSE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_email (email),
    INDEX idx_created_at (created_at)
);
```

**Columns Explained:**

| Column | Type | Purpose | Example |
|--------|------|---------|---------|
| id | BIGINT | Unique identifier | 1, 2, 3 |
| name | VARCHAR(255) | User's full name | "Anna Schmidt" |
| email | VARCHAR(255) | Email address (unique) | "anna@example.de" |
| email_verified_at | TIMESTAMP | Email verification time | 2026-02-07 14:30:00 |
| password | VARCHAR(255) | Hashed password | $2y$10$... |
| is_voter | BOOLEAN | Legacy: voter flag | false, true |
| is_committee_member | BOOLEAN | Legacy: committee flag | false, true |
| created_at | TIMESTAMP | Account creation | 2026-02-07 10:00:00 |
| updated_at | TIMESTAMP | Last update | 2026-02-07 15:30:00 |

**Legacy Columns Purpose:**
- Used for backward compatibility
- Existing voters continue working
- New system uses `user_organization_roles` instead
- Gradual migration from old to new system

**Example Data:**

```sql
INSERT INTO users VALUES
(1, 'Anna Schmidt', 'anna@diaspora.de', '2026-02-07', '$2y$10...', NULL, FALSE, FALSE, '2026-02-07', '2026-02-07'),
(2, 'Rahul Kumar', 'rahul@diaspora.de', '2026-02-06', '$2y$10...', NULL, FALSE, FALSE, '2026-02-06', '2026-02-06'),
(3, 'Otto Müller', 'otto@gewerkschaft.de', '2025-01-01', '$2y$10...', NULL, TRUE, FALSE, '2025-01-01', '2025-01-01');
```

---

### 2. organisations Table

**Purpose:** Store organisation information

```sql
CREATE TABLE organisations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    description TEXT NULL,
    languages JSON DEFAULT '["de", "en", "np"]',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_slug (slug),
    INDEX idx_created_at (created_at)
);
```

**Columns Explained:**

| Column | Type | Purpose | Example |
|--------|------|---------|---------|
| id | BIGINT | Unique organisation ID | 1, 2, 3 |
| name | VARCHAR(255) | organisation name | "European Nepal Association" |
| slug | VARCHAR(255) | URL-friendly identifier | "european-nepal-assoc" |
| description | TEXT | organisation description | "Cultural organisation for..." |
| languages | JSON | Supported languages | ["de", "en", "np"] |
| created_at | TIMESTAMP | Creation time | 2026-02-07 11:00:00 |
| updated_at | TIMESTAMP | Last update | 2026-02-07 11:30:00 |

**Language Support:**

```json
// Default (all 3 supported)
["de", "en", "np"]

// Custom selection
["en", "de"]

// Single language (rare)
["np"]
```

**Example Data:**

```sql
INSERT INTO organisations VALUES
(1, 'European Nepal Association', 'european-nepal-assoc', 'Cultural organisation for diaspora', '["de","en","np"]', '2026-02-07', '2026-02-07'),
(2, 'German Works Council', 'german-works-council', 'Workers representation', '["de","en"]', '2026-02-01', '2026-02-07'),
(3, 'Tech Workers Collective', 'tech-workers', 'Tech sector organisation', '["en","np"]', '2026-01-15', '2026-02-07');
```

---

### 3. user_organization_roles Table

**Purpose:** Map users to organisations with roles

```sql
CREATE TABLE user_organization_roles (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    organisation_id BIGINT UNSIGNED NOT NULL,
    role VARCHAR(255) NOT NULL,  -- 'admin', 'commission', 'voter'

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (organisation_id) REFERENCES organisations(id) ON DELETE CASCADE,

    UNIQUE KEY unique_user_org_role (user_id, organisation_id, role),
    INDEX idx_user_id (user_id),
    INDEX idx_org_id (organisation_id),
    INDEX idx_role (role)
);
```

**Columns Explained:**

| Column | Type | Purpose | Example |
|--------|------|---------|---------|
| id | BIGINT | Unique record ID | 1, 2, 3 |
| user_id | BIGINT | References users.id | 1, 2, 3 |
| organisation_id | BIGINT | References organisations.id | 1, 2, 3 |
| role | VARCHAR(255) | User's role in organisation | "admin", "commission", "voter" |
| created_at | TIMESTAMP | Assignment time | 2026-02-07 11:15:00 |
| updated_at | TIMESTAMP | Last update | 2026-02-07 11:15:00 |

**Role Values:**

```
'admin'       - organisation administrator (full access)
'commission'  - Election monitor/supervisor
'voter'       - Regular voter/participant
```

**Constraints:**
- Foreign key on user_id (cascade delete)
- Foreign key on organisation_id (cascade delete)
- Unique constraint: same user can't have same role twice in same org
- But: same user CAN have different roles in same org (e.g., admin AND voter)

**Example Data:**

```sql
INSERT INTO user_organization_roles VALUES
-- Anna as admin of European Nepal Association
(1, 1, 1, 'admin', '2026-02-07', '2026-02-07'),
-- Rahul as commission in European Nepal Association
(2, 2, 1, 'commission', '2026-02-07', '2026-02-07'),
-- Priya as voter in European Nepal Association
(3, 3, 1, 'voter', '2026-02-07', '2026-02-07'),
-- Marcus as admin in German Works Council
(4, 4, 2, 'admin', '2026-02-01', '2026-02-01'),
-- Marcus as voter in Tech Workers Collective
(5, 4, 3, 'voter', '2026-02-03', '2026-02-03');
```

**Why Unique Constraint?**

```
✅ ALLOWED (different roles)
user_id=1, org_id=1, role='admin'
user_id=1, org_id=1, role='voter'

❌ NOT ALLOWED (same role twice)
user_id=1, org_id=1, role='admin'
user_id=1, org_id=1, role='admin'  ← Duplicate!

✅ ALLOWED (different orgs)
user_id=1, org_id=1, role='admin'
user_id=1, org_id=2, role='admin'
```

---

### 4. elections Table

**Purpose:** Election records (extended with organisation link)

```sql
CREATE TABLE elections (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    organisation_id BIGINT UNSIGNED,  -- NEW: links to organisation
    starts_at TIMESTAMP NULL,
    ends_at TIMESTAMP NULL,
    status VARCHAR(50) DEFAULT 'draft',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (organisation_id) REFERENCES organisations(id),
    INDEX idx_org_id (organisation_id),
    INDEX idx_status (status)
);
```

**New Column:**

| Column | Type | Purpose | Example |
|--------|------|---------|---------|
| organisation_id | BIGINT | Foreign key to organisation | 1, 2, 3 |

**Example Data:**

```sql
INSERT INTO elections VALUES
(1, 'Board Elections 2026', 'Annual board election', 1, '2026-03-01', '2026-03-05', 'active', '2026-02-07', '2026-02-07'),
(2, 'Works Council 2026', 'Annual council election', 2, '2026-03-10', '2026-03-12', 'draft', '2026-02-01', '2026-02-07'),
(3, 'Tech Team Leads', 'Select team leads', 3, '2026-02-15', '2026-02-20', 'draft', '2026-02-01', '2026-02-01');
```

---

### 5. election_commission_members Table

**Purpose:** Assign users as monitors/supervisors for specific elections

```sql
CREATE TABLE election_commission_members (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    election_id BIGINT UNSIGNED NOT NULL,
    role VARCHAR(255) DEFAULT 'commission_member',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,

    UNIQUE KEY unique_user_election (user_id, election_id),
    INDEX idx_user_id (user_id),
    INDEX idx_election_id (election_id)
);
```

**Columns Explained:**

| Column | Type | Purpose | Example |
|--------|------|---------|---------|
| id | BIGINT | Unique record ID | 1, 2, 3 |
| user_id | BIGINT | References users.id | 1, 2, 3 |
| election_id | BIGINT | References elections.id | 1, 2, 3 |
| role | VARCHAR(255) | Commission role | "commission_member", "supervisor" |
| created_at | TIMESTAMP | Assignment time | 2026-02-07 11:20:00 |
| updated_at | TIMESTAMP | Last update | 2026-02-07 11:20:00 |

**Example Data:**

```sql
INSERT INTO election_commission_members VALUES
(1, 2, 1, 'commission_member', '2026-02-07', '2026-02-07'),
(2, 5, 1, 'supervisor', '2026-02-07', '2026-02-07'),
(3, 3, 2, 'commission_member', '2026-02-01', '2026-02-07');
```

---

## Query Patterns

### Get User's Dashboard Roles

```sql
SELECT DISTINCT role FROM user_organization_roles
WHERE user_id = 1;

-- Result: ['admin', 'commission']
```

### Get All Members of organisation

```sql
SELECT u.id, u.name, u.email, r.role
FROM users u
JOIN user_organization_roles r ON u.id = r.user_id
WHERE r.organisation_id = 1;

-- Result:
-- id | name         | email              | role
-- 1  | Anna Schmidt | anna@diaspora.de   | admin
-- 2  | Rahul Kumar  | rahul@diaspora.de  | commission
-- 3  | Priya Singh  | priya@diaspora.de  | voter
```

### Get Election Commission Members

```sql
SELECT u.id, u.name, u.email, c.role
FROM users u
JOIN election_commission_members c ON u.id = c.user_id
WHERE c.election_id = 1;

-- Result:
-- id | name              | email              | role
-- 2  | Rahul Kumar       | rahul@diaspora.de  | commission_member
-- 5  | Another Monitor   | monitor@example.de | supervisor
```

### Get User's Organizations (Multi-Tenant View)

```sql
SELECT o.id, o.name, o.slug, r.role
FROM organisations o
JOIN user_organization_roles r ON o.id = r.organisation_id
WHERE r.user_id = 4;

-- Result (if Marcus):
-- id | name                    | slug               | role
-- 2  | German Works Council    | german-works-council | admin
-- 3  | Tech Workers Collective | tech-workers       | voter
```

### Check User's Role in organisation

```sql
SELECT COUNT(*) as has_admin
FROM user_organization_roles
WHERE user_id = 1 AND organisation_id = 1 AND role = 'admin';

-- Result: 1 (TRUE) or 0 (FALSE)
```

---

## Indexes & Performance

### Index Strategy

```sql
-- Primary lookups (high frequency)
INDEX idx_user_id ON user_organization_roles(user_id);
INDEX idx_org_id ON user_organization_roles(organisation_id);
INDEX idx_election_id ON election_commission_members(election_id);

-- Unique constraint (prevents duplicates)
UNIQUE KEY unique_user_org_role (user_id, organisation_id, role);

-- Filtering
INDEX idx_role ON user_organization_roles(role);
INDEX idx_status ON elections(status);
```

### Query Performance

```
Without indexes:
- SELECT roles for user → O(n) table scan
- SELECT members of org → O(n) table scan

With indexes:
- SELECT roles for user → O(log n) index lookup
- SELECT members of org → O(log n) index lookup + O(m) result set
```

---

## Migration Strategy

### From Legacy to New System

**Phase 1: Coexistence (Current)**
```
Old system: is_voter, Spatie roles
New system: user_organization_roles
Both work in parallel
```

**Phase 2: Migration Helpers**
```sql
-- Create organisation for legacy users
INSERT INTO organisations (name, slug, languages)
VALUES ('Legacy System', 'legacy-system', '["de","en","np"]');

-- Migrate legacy voters
INSERT INTO user_organization_roles (user_id, organisation_id, role)
SELECT id, 1, 'voter' FROM users WHERE is_voter = TRUE;

-- Clear legacy flags (optional)
UPDATE users SET is_voter = FALSE WHERE ...;
```

**Phase 3: Deprecation**
```
Remove is_voter, is_committee_member columns
Remove legacy role checks
Clean up LoginResponse fallback logic
```

---

## Backup & Recovery

### Critical Tables

1. **users** - Primary data (backup daily)
2. **organisations** - Core structure (backup daily)
3. **user_organization_roles** - Role assignments (backup hourly during active period)
4. **elections** - Election data (backup continuously)
5. **election_commission_members** - Monitor assignments (backup hourly)

### Restore Procedure

```sql
-- Restore from backup
LOAD DATA INFILE '/backups/users.sql' INTO TABLE users;
LOAD DATA INFILE '/backups/organisations.sql' INTO TABLE organisations;
LOAD DATA INFILE '/backups/user_organization_roles.sql' INTO TABLE user_organization_roles;

-- Verify foreign keys
SELECT COUNT(*) FROM user_organization_roles
WHERE user_id NOT IN (SELECT id FROM users);
-- Should return 0
```

---

## Monitoring Queries

### System Health

```sql
-- Active organisations
SELECT COUNT(*) FROM organisations;

-- Users with roles
SELECT COUNT(DISTINCT user_id) FROM user_organization_roles;

-- Active elections
SELECT COUNT(*) FROM elections WHERE status = 'active';

-- Commission assignments
SELECT COUNT(*) FROM election_commission_members;
```

### Role Distribution

```sql
SELECT role, COUNT(*) as count
FROM user_organization_roles
GROUP BY role;

-- Result:
-- role       | count
-- admin      | 15
-- commission | 45
-- voter      | 250
```
