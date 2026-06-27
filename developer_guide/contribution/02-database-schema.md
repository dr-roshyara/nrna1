# 02 — Database Schema

## Tables Created

The migration `2026_04_11_000001_create_contributions_tables.php` creates two tables and adds one column to `users`.

---

## contributions

The main record of a member's work.

| Column | Type | Nullable | Default | Purpose |
|--------|------|----------|---------|---------|
| `id` | UUID (PK) | No | — | Primary key |
| `organisation_id` | UUID (FK → organisations) | No | — | Tenant isolation |
| `user_id` | UUID (FK → users) | No | — | The contributor |
| `title` | VARCHAR(255) | No | — | Human-readable label |
| `description` | TEXT | No | — | Detailed description of the work |
| `track` | ENUM(micro, standard, major) | No | micro | Contribution scale |
| `status` | ENUM(draft, pending, verified, approved, rejected, appealed, completed) | No | draft | Workflow state |
| `effort_units` | INT | No | 0 | Hours of effort (formula input) |
| `team_skills` | JSON | Yes | NULL | Array of skill strings |
| `is_recurring` | BOOLEAN | No | false | Sustainability flag |
| `outcome_bonus` | INT | No | 0 | Extra points for measurable outcomes |
| `calculated_points` | INT | No | 0 | Final point value after formula |
| `proof_type` | ENUM(self_report, photo, document, third_party, institutional) | No | self_report | Verification tier |
| `proof_path` | VARCHAR(255) | Yes | NULL | File path for uploaded proof |
| `verifier_notes` | TEXT | Yes | NULL | Notes from the verifier |
| `verified_by` | UUID (FK → users) | Yes | NULL | Who verified |
| `verified_at` | TIMESTAMP | Yes | NULL | When verified |
| `approved_by` | UUID (FK → users) | Yes | NULL | Who approved |
| `approved_at` | TIMESTAMP | Yes | NULL | When approved |
| `created_by` | UUID (FK → users) | Yes | NULL | Audit: who submitted |
| `created_at` | TIMESTAMP | — | — | Laravel timestamp |
| `updated_at` | TIMESTAMP | — | — | Laravel timestamp |
| `deleted_at` | TIMESTAMP | Yes | NULL | Soft delete |

### Indexes

```sql
INDEX (organisation_id, user_id, status)     -- filter by org + user + workflow state
INDEX (organisation_id, track, created_at)   -- filter by org + track + time
```

### Foreign Keys

```sql
organisation_id → organisations(id)  ON DELETE CASCADE
user_id         → users(id)          ON DELETE CASCADE
verified_by     → users(id)          ON DELETE SET NULL
approved_by     → users(id)          ON DELETE SET NULL
created_by      → users(id)          ON DELETE SET NULL
```

**Design decision:** `CASCADE` on ownership FKs (org/user), `SET NULL` on optional actor FKs (verifier/approver/creator). If a verifier leaves the platform, the contribution record survives — only the verifier reference becomes null.

---

## points_ledger

An **immutable audit trail** for every point transaction. Once written, rows are never updated or deleted.

| Column | Type | Nullable | Default | Purpose |
|--------|------|----------|---------|---------|
| `id` | UUID (PK) | No | — | Primary key |
| `organisation_id` | UUID (FK → organisations) | No | — | Tenant isolation |
| `user_id` | UUID (FK → users) | No | — | Points owner |
| `contribution_id` | UUID (FK → contributions) | No | — | Source contribution |
| `points` | INT | No | — | Point value (can be negative for adjustments) |
| `action` | ENUM(earned, spent, adjusted, appealed) | No | — | Transaction type |
| `reason` | TEXT | Yes | NULL | Human-readable audit note |
| `created_by` | UUID (FK → users) | Yes | NULL | Who triggered this entry |
| `created_at` | TIMESTAMP | — | — | Laravel timestamp |
| `updated_at` | TIMESTAMP | — | — | Laravel timestamp |

### Indexes

```sql
INDEX (organisation_id, user_id, created_at)  -- weekly aggregation queries
```

### Why Immutable?

The ledger is designed like a financial journal. To "undo" points, you write a new row with `action: 'adjusted'` and negative points — you never modify or delete the original entry. This provides:

- Complete audit trail for dispute resolution
- Point-in-time reconstruction of any user's balance
- Tamper-evidence (missing sequence = something was deleted)

---

## users table modification

One column added:

| Column | Type | Default | Purpose |
|--------|------|---------|---------|
| `leaderboard_visibility` | ENUM(public, anonymous, private) | anonymous | Controls how the user appears on the leaderboard |

- **public**: Real name displayed
- **anonymous**: Displayed as "Contributor #N" (rank-based counter)
- **private**: Excluded from leaderboard entirely

---

## ER Diagram

```
users ─────────────┐
  │                 │
  │ user_id         │ user_id
  ▼                 ▼
contributions    points_ledger
  │                 ▲
  │ id              │ contribution_id
  └─────────────────┘
  │
  │ organisation_id
  ▼
organisations
```

---

## Rollback

```bash
php artisan migrate:rollback
```

The `down()` method:
1. Drops `leaderboard_visibility` from `users`
2. Drops `points_ledger`
3. Drops `contributions`
