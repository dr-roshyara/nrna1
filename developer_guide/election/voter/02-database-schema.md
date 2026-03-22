# 02 — Database Schema

## Prerequisites — What Had to Change First

Before `election_memberships` could be created, two things had to exist:

### Prerequisite 1: Composite unique key on `elections`

Migration: `2026_03_17_213211_add_composite_unique_to_elections_table.php`

```php
Schema::table('elections', function (Blueprint $table) {
    $table->unique(['id', 'organisation_id'], 'unique_org_election');
});
```

**Why?** MySQL requires the *referenced* columns in a composite foreign key to form a key themselves. The `elections` primary key only covers `id`. This migration adds the compound unique needed for:

```sql
FOREIGN KEY (election_id, organisation_id) REFERENCES elections(id, organisation_id)
```

Without this, the migration that creates `election_memberships` fails with:

```
SQLSTATE[HY000]: General error: 1822 Failed to add the foreign key constraint.
Missing index for constraint on table 'elections' required index of columns:
'id', 'organisation_id'
```

### Prerequisite 2: `user_organisation_roles` composite primary key (already exists)

The `user_organisation_roles` table already has a composite primary key on `(user_id, organisation_id)`, which is why the other composite FK works out of the box.

---

## The `election_memberships` Table

Migration: `2026_03_17_213212_create_election_memberships_table.php`

### Full Column Reference

| Column | Type | Nullable | Default | Notes |
|--------|------|----------|---------|-------|
| `id` | `uuid` | No | — | Primary key, auto-generated via `HasUuids` |
| `user_id` | `uuid` | No | — | FK component |
| `organisation_id` | `uuid` | No | — | FK component, denormalised for composite FKs |
| `election_id` | `uuid` | No | — | FK component |
| `role` | `enum` | No | `voter` | `voter`, `candidate`, `observer`, `admin` |
| `status` | `enum` | No | `active` | `invited`, `active`, `inactive`, `removed` |
| `assigned_by` | `uuid` | Yes | `null` | User who created this row |
| `assigned_at` | `timestamp` | Yes | `null` | When the assignment was made |
| `expires_at` | `timestamp` | Yes | `null` | Optional eligibility expiry |
| `last_activity_at` | `timestamp` | Yes | `null` | Updated when voter completes an action |
| `metadata` | `json` | Yes | `null` | Flexible bag: removal reason, import source, etc. |
| `created_at` | `timestamp` | No | — | Laravel standard |
| `updated_at` | `timestamp` | No | — | Laravel standard |

---

### Foreign Keys

```sql
-- FK 1: Ensures user is an actual org member
FOREIGN KEY (user_id, organisation_id)
    REFERENCES user_organisation_roles(user_id, organisation_id)
    ON DELETE CASCADE

-- FK 2: Ensures election belongs to the same org
FOREIGN KEY (election_id, organisation_id)
    REFERENCES elections(id, organisation_id)
    ON DELETE CASCADE

-- FK 3: Assigner must be a real user
FOREIGN KEY (assigned_by)
    REFERENCES users(id)
    ON DELETE SET NULL
```

The `ON DELETE CASCADE` on FK 1 is especially important — it means the database automatically removes all election memberships when a user is detached from an organisation. No PHP cleanup code required.

---

### Business Rule Constraints

```sql
-- One role per user per election
UNIQUE (user_id, election_id)  -- named: unique_user_election
```

Attempting to insert a second row for the same `(user_id, election_id)` pair throws `SQLSTATE[23000] Integrity constraint violation: 1062 Duplicate entry`.

---

### Indexes

| Name | Columns | Purpose |
|------|---------|---------|
| `idx_election_role_status` | `(election_id, role, status)` | Fetch all active voters for an election — the most frequent read |
| `idx_user_status` | `(user_id, status)` | Find all active memberships for a given user |
| `idx_org_role` | `(organisation_id, role)` | Organisation-level reporting |
| `idx_assigned` | `(assigned_by, assigned_at)` | Audit queries — who assigned whom and when |

---

## Running the Migrations

```bash
# Run both (order matters — unique key first, then table that references it)
php artisan migrate

# Confirm structure
php artisan db:show --tables=election_memberships
```

## Rolling Back

```bash
# Rolls back election_memberships table
php artisan migrate:rollback

# Rolls back the unique key on elections
php artisan migrate:rollback
```

The `down()` methods are:

```php
// 2026_03_17_213212 — drops the table
Schema::dropIfExists('election_memberships');

// 2026_03_17_213211 — drops the unique key
Schema::table('elections', function (Blueprint $table) {
    $table->dropUnique('unique_org_election');
});
```

Note: dropping the unique key after the memberships table exists will fail because the FK still references it. Always roll back in reverse migration order.

---

## Entity-Relationship Diagram

```
users
  id (PK)
  │
  ├─ user_organisation_roles
  │    user_id (FK → users.id)
  │    organisation_id (FK → organisations.id)
  │    PRIMARY KEY (user_id, organisation_id)       ← referenced by FK 1
  │
organisations
  id (PK)
  │
elections
  id (PK)
  organisation_id (FK → organisations.id)
  UNIQUE (id, organisation_id)                      ← referenced by FK 2
  │
  └──────────────────────────────────────┐
                                         ▼
                              election_memberships
                                user_id ──────────────── FK 1 (composite)
                                organisation_id ─────────┘
                                election_id ──────────── FK 2 (composite)
                                organisation_id ─────────┘
                                assigned_by ─────────── FK 3 → users.id
                                UNIQUE (user_id, election_id)
```
