# Phase 1 — Data Layer: Migrations and Models

## Goal

Establish the full persistence layer before writing any controller logic. This phase produces:
- **4 new migrations** (types, applications, fees, renewals) + **2 alter migrations** (add ended fields, fix ENUM)
- **4 new models** (MembershipType, MembershipApplication, MembershipFee, MembershipRenewal)
- **Extended Member model** with fee/renewal relationships and business methods

---

## TDD Sequence

```
1. Write all Unit model tests first (RED — models don't exist)
2. Run migrations to create tables
3. Create models to make tests GREEN
4. Extend Member model (canSelfRenew, endMembership) → GREEN
```

---

## 1. Migrations

### Migration 1 — `membership_types`

**File:** `database/migrations/2026_04_03_155706_create_membership_types_table.php`

```php
Schema::create('membership_types', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('organisation_id')->constrained()->cascadeOnDelete();
    $table->string('name', 100);
    $table->string('slug', 100);
    $table->text('description')->nullable();
    $table->decimal('fee_amount', 10, 2)->default(0);
    $table->char('fee_currency', 3)->default('EUR');
    $table->smallInteger('duration_months')->nullable(); // null = lifetime
    $table->boolean('requires_approval')->default(true);
    $table->json('form_schema')->nullable();
    $table->boolean('is_active')->default(true);
    $table->smallInteger('sort_order')->default(0);
    $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamps();
    $table->softDeletes();

    $table->unique(['organisation_id', 'slug']);
});
```

**Key design decisions:**
- `duration_months = null` signals a **lifetime membership** — `MembershipType::isLifetime()` checks this
- `form_schema json` holds dynamic application form field definitions (for future custom application forms)
- `UNIQUE(organisation_id, slug)` — slugs unique within an org, not globally
- Soft deletes: archived types preserve historical fee records

---

### Migration 2 — `membership_applications`

**File:** `database/migrations/2026_04_03_155710_create_membership_applications_table.php`

```php
Schema::create('membership_applications', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('organisation_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('membership_type_id')->constrained()->cascadeOnDelete();
    $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected'])
          ->default('draft');
    $table->json('application_data')->nullable();
    $table->timestamp('expires_at')->nullable();       // auto-reject after 30 days
    $table->integer('lock_version')->default(0);       // optimistic locking
    $table->timestamp('submitted_at')->nullable();
    $table->foreignUuid('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
    $table->timestamp('reviewed_at')->nullable();
    $table->string('rejection_reason', 1000)->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

**Critical columns:**

| Column | Purpose |
|--------|---------|
| `expires_at` | Set to `now() + 30 days` on submission. Daily command auto-rejects if past. |
| `lock_version` | Optimistic lock counter. `approve()` uses `WHERE lock_version = ?` to detect concurrent approvals. |
| `reviewed_by` | FK to users — who approved or rejected |

---

### Migration 3 — `membership_fees`

**File:** `database/migrations/2026_04_03_155707_create_membership_fees_table.php`

```php
Schema::create('membership_fees', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('organisation_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('member_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('membership_type_id')->constrained()->cascadeOnDelete();
    $table->decimal('amount', 10, 2);
    $table->char('currency', 3)->default('EUR');
    $table->decimal('fee_amount_at_time', 10, 2);  // snapshot — frozen forever
    $table->char('currency_at_time', 3);           // snapshot — frozen forever
    $table->string('period_label', 50)->nullable();
    $table->date('due_date')->nullable();
    $table->timestamp('paid_at')->nullable();
    $table->enum('status', ['pending', 'paid', 'waived', 'overdue'])->default('pending');
    $table->string('payment_method', 50)->nullable();
    $table->string('payment_reference', 200)->nullable();
    $table->string('idempotency_key', 100)->nullable()->unique(); // prevents duplicate payments
    $table->foreignUuid('recorded_by')->nullable()->constrained('users')->nullOnDelete();
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

**Critical columns:**

| Column | Purpose |
|--------|---------|
| `fee_amount_at_time` | Copied from `membership_type.fee_amount` at creation. Immutable. |
| `currency_at_time` | Copied from `membership_type.fee_currency` at creation. Immutable. |
| `idempotency_key UNIQUE` | Admin supplies a key on payment recording. Duplicate key → 409 error. |
| `due_date` | When set, `scopeOverdue()` returns fees past this date. |

---

### Migration 4 — `membership_renewals`

**File:** `database/migrations/2026_04_03_155708_create_membership_renewals_table.php`

```php
Schema::create('membership_renewals', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('organisation_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('member_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('membership_type_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('renewed_by')->constrained('users')->cascadeOnDelete();
    $table->timestamp('old_expires_at')->nullable();
    $table->timestamp('new_expires_at')->nullable();
    $table->foreignUuid('fee_id')->nullable()->constrained('membership_fees')->nullOnDelete();
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

Every renewal creates a permanent audit record. The linked `fee_id` allows the renewal fee to be paid separately.

---

### Migration 5 — Add ended fields to `members`

**File:** `database/migrations/2026_04_03_155709_add_ended_fields_to_members_table.php`

```php
Schema::table('members', function (Blueprint $table) {
    $table->timestamp('ended_at')->nullable()->after('membership_expires_at');
    $table->text('end_reason')->nullable()->after('ended_at');
});
```

---

### Migration 6 — Add 'ended' to members ENUM (CRITICAL)

**File:** `database/migrations/2026_04_03_155711_add_ended_status_to_members_table.php`

The `members.status` column was created as `ENUM('active', 'expired', 'suspended')`. MySQL ENUMs **cannot be modified using the Schema builder** — you must use a raw statement:

```php
public function up(): void
{
    DB::statement("ALTER TABLE `members` MODIFY COLUMN `status` ENUM('active', 'expired', 'suspended', 'ended') NOT NULL DEFAULT 'active'");
}

public function down(): void
{
    DB::statement("ALTER TABLE `members` MODIFY COLUMN `status` ENUM('active', 'expired', 'suspended') NOT NULL DEFAULT 'active'");
}
```

> **Warning:** If you try `$table->enum('status', [...])` in a Schema `table()` call, Laravel will throw an error or silently not modify the ENUM. Always use `DB::statement("ALTER TABLE ... MODIFY COLUMN")` for ENUM changes on MySQL.

---

## 2. Models

### MembershipType

**File:** `app/Models/MembershipType.php`

```php
class MembershipType extends Model
{
    use HasUuids, SoftDeletes;

    protected $casts = [
        'fee_amount'        => 'decimal:2',
        'requires_approval' => 'boolean',
        'is_active'         => 'boolean',
        'form_schema'       => 'array',
        'duration_months'   => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function isLifetime(): bool
    {
        return $this->duration_months === null;
    }

    public function applications(): HasMany { return $this->hasMany(MembershipApplication::class); }
    public function fees(): HasMany         { return $this->hasMany(MembershipFee::class); }
    public function renewals(): HasMany     { return $this->hasMany(MembershipRenewal::class); }
}
```

**Note:** `MembershipType` does NOT use `BelongsToTenant`. The global scope is not needed here because the type controller always queries with `->where('organisation_id', $organisation->id)` explicitly.

---

### MembershipApplication

**File:** `app/Models/MembershipApplication.php`

The most complex model — implements optimistic locking.

```php
class MembershipApplication extends Model
{
    use HasUuids, SoftDeletes;

    protected $casts = [
        'application_data' => 'array',
        'expires_at'       => 'datetime',
        'submitted_at'     => 'datetime',
        'reviewed_at'      => 'datetime',
        'lock_version'     => 'integer',
    ];

    public function isPending(): bool
    {
        return in_array($this->status, ['submitted', 'under_review']);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /**
     * Approve with optimistic locking.
     * The WHERE clause includes lock_version — if another process already
     * approved this record, $updated will be 0 and the exception fires.
     *
     * @throws ApplicationAlreadyProcessedException
     */
    public function approve(string $reviewedBy): void
    {
        $updated = static::where('id', $this->id)
            ->where('lock_version', $this->lock_version)
            ->where('status', 'submitted')
            ->update([
                'status'       => 'approved',
                'lock_version' => $this->lock_version + 1,
                'reviewed_by'  => $reviewedBy,
                'reviewed_at'  => now(),
            ]);

        if (!$updated) {
            throw new ApplicationAlreadyProcessedException(
                'Application has already been processed or modified concurrently.'
            );
        }

        $this->refresh();
    }

    // reject() follows the same pattern
}
```

#### How Optimistic Locking Works

```
Admin A loads application (lock_version = 0)
Admin B loads application (lock_version = 0)

Admin A clicks Approve → UPDATE WHERE id=X AND lock_version=0
  → 1 row updated ✓ → lock_version becomes 1

Admin B clicks Approve → UPDATE WHERE id=X AND lock_version=0
  → 0 rows updated (lock_version is now 1, not 0)
  → ApplicationAlreadyProcessedException thrown
  → Controller returns 'This application was already processed by another administrator.'
```

No pessimistic row locks. No extra transactions. Zero deadlock risk.

---

### MembershipFee

**File:** `app/Models/MembershipFee.php`

```php
class MembershipFee extends Model
{
    use HasUuids;

    protected $casts = [
        'amount'             => 'decimal:2',
        'fee_amount_at_time' => 'decimal:2',
        'due_date'           => 'date',
        'paid_at'            => 'datetime',
    ];

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', 'paid');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    /**
     * Fees past their due date that have not yet been paid or waived.
     * Used by the daily ProcessMembershipExpiryCommand.
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('status', 'pending')
                     ->whereNotNull('due_date')
                     ->where('due_date', '<', now());
    }
}
```

**Fee snapshot pattern:** When a fee is created (on approval or renewal), the type's current fee is copied:

```php
MembershipFee::create([
    'amount'             => $type->fee_amount,
    'currency'           => $type->fee_currency,
    'fee_amount_at_time' => $type->fee_amount,   // ← snapshot at time of creation
    'currency_at_time'   => $type->fee_currency, // ← snapshot at time of creation
    // ...
]);
```

Even if the type's fee changes from €50 to €75 next month, the historical fee record remains frozen at €50. This is critical for audit and dispute resolution.

---

### MembershipRenewal

**File:** `app/Models/MembershipRenewal.php`

```php
class MembershipRenewal extends Model
{
    use HasUuids;

    protected $casts = [
        'old_expires_at' => 'datetime',
        'new_expires_at' => 'datetime',
    ];

    public function member(): BelongsTo   { return $this->belongsTo(Member::class); }
    public function renewedBy(): BelongsTo { return $this->belongsTo(User::class, 'renewed_by'); }
    public function fee(): BelongsTo      { return $this->belongsTo(MembershipFee::class, 'fee_id'); }
}
```

---

### Member (Extended)

**File:** `app/Models/Member.php` — added methods

```php
/**
 * Can the member self-renew?
 * Returns false for lifetime members (membership_expires_at = null).
 * Returns false if more than 90 days have passed since expiry.
 */
public function canSelfRenew(): bool
{
    if ($this->status !== 'active' || $this->membership_expires_at === null) {
        return false;
    }

    $windowDays = config('membership.self_renewal_window_days', 90);

    return $this->membership_expires_at->isAfter(now()->subDays($windowDays));
}

/**
 * End this membership:
 *   1. Sets status = ended, records ended_at and end_reason
 *   2. Waives all pending fees
 *   3. Removes member from active elections (via user_id — NOT member_id)
 */
public function endMembership(?string $reason = null): void
{
    DB::transaction(function () use ($reason) {
        $this->update([
            'status'     => 'ended',
            'ended_at'   => now(),
            'end_reason' => $reason,
        ]);

        $this->fees()->where('status', 'pending')->update(['status' => 'waived']);

        // IMPORTANT: election_memberships has NO member_id column — uses user_id
        $userId = $this->organisationUser?->user_id;
        if ($userId) {
            ElectionMembership::where('user_id', $userId)
                ->where('status', 'active')
                ->update(['status' => 'removed']);
        }
    });
}
```

> **Critical:** `election_memberships` does NOT have a `member_id` column. It references users via `user_id`. The `endMembership()` method must walk the relationship: `member → organisationUser → user_id`.

---

## 3. BelongsToTenant Pattern

The `BelongsToTenant` trait (in `app/Traits/`) adds a global query scope that appends `WHERE organisation_id = session('current_organisation_id')` to every query on the model.

**Member uses it. MembershipType, MembershipApplication, MembershipFee, MembershipRenewal do NOT.**

Why? The application workflow operates on both public routes (where no session is set) and admin routes (where session is set). Rather than fighting the scope, the controllers always pass `organisation_id` explicitly:

```php
// Controllers use explicit organisation_id — no global scope needed
MembershipApplication::withoutGlobalScopes()
    ->where('organisation_id', $organisation->id)
    ->where('user_id', $user->id)
    ->exists();
```

Member uses BelongsToTenant because it's only ever accessed on protected admin routes where `session('current_organisation_id')` is guaranteed to be set.

---

## 4. Test Coverage — Phase 1

**28 unit tests across 4 model test files:**

| File | Tests |
|------|-------|
| `MembershipTypeTest.php` | `active_scope_filters_inactive`, `is_lifetime_when_null`, `belongs_to_organisation`, `has_fees`, `has_applications` |
| `MembershipApplicationTest.php` | `is_pending_when_submitted_or_under_review`, `is_expired_when_past_expires_at`, `approve_sets_approved_status`, `reject_sets_rejected_with_reason`, `concurrent_approve_throws_exception` |
| `MembershipFeeTest.php` | `paid_scope`, `pending_scope`, `overdue_scope`, `fee_amount_snapshot_preserved`, `belongs_to_member` |
| `MembershipRenewalTest.php` | `belongs_to_member`, `new_expires_at_after_old`, `links_to_fee` |
| `MemberTest.php` | `can_self_renew_before_expiry`, `can_self_renew_within_window`, `cannot_self_renew_after_window`, `lifetime_cannot_self_renew`, `end_membership_sets_status`, `end_membership_waives_pending_fees`, `end_membership_removes_from_elections` |

---

## Common Mistakes

### Mistake 1: Forgetting ENUM cannot be altered with Schema builder

```php
// WRONG — no error but does nothing on MySQL
Schema::table('members', function (Blueprint $table) {
    $table->enum('status', ['active', 'expired', 'suspended', 'ended'])->change();
});

// CORRECT — raw statement required
DB::statement("ALTER TABLE `members` MODIFY COLUMN `status` ENUM('active','expired','suspended','ended') NOT NULL DEFAULT 'active'");
```

### Mistake 2: Using member_id on election_memberships

```php
// WRONG — no such column
ElectionMembership::where('member_id', $member->id)->update(['status' => 'removed']);

// CORRECT — route via organisationUser
$userId = $member->organisationUser?->user_id;
ElectionMembership::where('user_id', $userId)->update(['status' => 'removed']);
```

### Mistake 3: Modifying fee_amount_at_time after creation

The snapshot columns must never be updated after creation. They represent the price at the time the fee was issued, regardless of future type changes. A migration that sets `fee_amount_at_time = NULL` on existing records would break the audit trail permanently.
