# 05 — Models

## Contribution

**File:** `app/Models/Contribution.php`

### Traits

| Trait | Purpose |
|-------|---------|
| `HasFactory` | Factory support for tests |
| `HasUuids` | Auto-generates UUID primary keys |
| `SoftDeletes` | `deleted_at` column for safe deletion |
| `BelongsToTenant` | Global scope filtering by `organisation_id` |

### Fillable Fields

All form inputs plus workflow fields:

```php
protected $fillable = [
    'organisation_id', 'user_id', 'title', 'description',
    'track', 'status', 'effort_units', 'team_skills',
    'is_recurring', 'outcome_bonus', 'calculated_points',
    'proof_type', 'proof_path', 'verifier_notes',
    'verified_by', 'verified_at', 'approved_by', 'approved_at',
    'created_by',
];
```

### Casts

```php
protected $casts = [
    'team_skills'  => 'array',      // JSON ↔ PHP array
    'is_recurring' => 'boolean',
    'verified_at'  => 'datetime',
    'approved_at'  => 'datetime',
];
```

### Relationships

| Method | Type | Target | FK |
|--------|------|--------|-----|
| `contributor()` | belongsTo | User | `user_id` |
| `organisation()` | belongsTo | Organisation | `organisation_id` |
| `verifier()` | belongsTo | User | `verified_by` |
| `approver()` | belongsTo | User | `approved_by` |
| `ledgerEntries()` | hasMany | PointsLedger | `contribution_id` |

### Usage Examples

```php
// Get all contributions for current user in an org
Contribution::where('organisation_id', $orgId)
    ->where('user_id', auth()->id())
    ->orderByDesc('created_at')
    ->paginate(20);

// Load with ledger entries
$contribution->load('ledgerEntries');

// Check points
$contribution->calculated_points; // int
```

---

## PointsLedger

**File:** `app/Models/PointsLedger.php`

### Design Principle: Immutability

This model represents an **append-only audit log**. Rows should never be updated or deleted in production. To reverse a point award, write a new row with `action: 'adjusted'` and negative points.

### Traits

| Trait | Purpose |
|-------|---------|
| `HasFactory` | Factory support for tests |
| `HasUuids` | Auto-generates UUID primary keys |
| `BelongsToTenant` | Global scope filtering by `organisation_id` |

**Notable absence:** No `SoftDeletes`. Ledger entries are permanent.

### Table Name Override

```php
protected $table = 'points_ledger';
```

Required because Laravel would pluralize to `points_ledgers`.

### Fillable Fields

```php
protected $fillable = [
    'organisation_id', 'user_id', 'contribution_id',
    'points', 'action', 'reason', 'created_by',
];
```

### Casts

```php
protected $casts = [
    'points' => 'integer',
];
```

### Relationships

| Method | Type | Target | FK |
|--------|------|--------|-----|
| `user()` | belongsTo | User | `user_id` |
| `contribution()` | belongsTo | Contribution | `contribution_id` |
| `organisation()` | belongsTo | Organisation | `organisation_id` |

### Action Types

| Action | When Used | Points Sign |
|--------|-----------|-------------|
| `earned` | Contribution approved | Positive |
| `spent` | Points redeemed (future feature) | Negative |
| `adjusted` | Admin correction | Positive or negative |
| `appealed` | Dispute resolution | Positive or negative |
