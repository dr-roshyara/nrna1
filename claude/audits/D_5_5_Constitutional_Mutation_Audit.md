---
name: constitutional-mutation-audit
description: D.5.5 Phase 1B - Verify election constitutional articles cannot mutate after creation
metadata:
  type: audit
  phase: D.5.5
  priority: CRITICAL
  risk_level: EXISTENTIAL
---

# D.5.5 Phase 1B: Constitutional Mutation Audit

## Executive Summary

After election creation, constitutional articles MUST be immutable. If articles can mutate:
- Replay legitimacy is destroyed
- Dispute reconstruction becomes impossible
- Federation trust is violated
- Sovereign determinism is lost

This audit verifies:
1. Articles are frozen at election creation
2. No database mutations after creation
3. No admin UI allows editing constitutional rules
4. No code path can mutate `security_articles_snapshot`
5. Migration/sync operations cannot alter articles

---

## Critical Invariants to Verify

### Invariant 1: Snapshot is Frozen at Creation

```php
// MUST be true:
Election::create([
    'security_articles_snapshot' => json_encode($articles),
    'constitutional_hash' => hash('sha256', json_encode($articles)),
    'security_articles_version' => 'D.2.5',
]);

// MUST NEVER happen:
$election->update(['security_articles_snapshot' => $new_articles]);
```

**Test Required**:
```php
test_security_articles_snapshot_is_immutable_after_creation()
{
    $election = Election::factory()->create();
    $original = $election->security_articles_snapshot;
    
    // Attempt to mutate - should fail or have no effect
    $election->security_articles_snapshot = json_encode(['hacked' => true]);
    $election->save();
    
    $reloaded = Election::find($election->id);
    $this->assertEquals($original, $reloaded->security_articles_snapshot);
}
```

---

### Invariant 2: No Database Mutations Allowed

#### Danger Pattern 1: Direct SQL Update

```sql
-- DANGEROUS - allows mutation
UPDATE elections 
SET security_articles_snapshot = '...'
WHERE id = ?;
```

**Audit Task**: Search database migrations for any UPDATE to `security_articles_snapshot` column after election creation migration.

**Expected Result**: Zero mutations in any migration file.

#### Danger Pattern 2: Eloquent Update in Code

```php
// DANGEROUS
$election->security_articles_snapshot = $new_articles;
$election->save();

// DANGEROUS
Election::where('id', $election->id)
    ->update(['security_articles_snapshot' => $new_articles]);
```

**Audit Task**: Grep for:
```
security_articles_snapshot\s*=
UPDATE.*security_articles_snapshot
```

**Expected Result**: Zero mutations in application code (only initial creation).

---

### Invariant 3: No Admin UI Allows Editing

#### Danger Pattern 3: Settings Page Mutation

```blade
<!-- DANGEROUS - user editable -->
<input v-model="form.max_votes_per_ip" />
<button @click="saveSecurityArticles">Save</button>
```

**Audit Task**: Search admin interfaces for forms editing:
- Network binding strategy
- Device binding strategy
- Verification protocol
- Trust elevation thresholds
- Ballot authorization protocol

**Expected Result**: 
- ✅ READ-ONLY display of current articles OK
- ❌ Edit forms forbidden
- ❌ Save endpoints forbidden

---

### Invariant 4: Constitutional Hash Must Validate

```php
// MUST always be true:
$expectedHash = hash('sha256', $election->security_articles_snapshot);
$this->assertEquals($expectedHash, $election->constitutional_hash);
```

**Test Required**:
```php
test_constitutional_hash_validates_snapshot_integrity()
{
    $elections = Election::all();
    
    foreach ($elections as $election) {
        $expectedHash = hash('sha256', $election->security_articles_snapshot);
        $this->assertEquals(
            $expectedHash, 
            $election->constitutional_hash,
            "Election {$election->id} snapshot was mutated (hash mismatch)"
        );
    }
}
```

---

### Invariant 5: Version Remains Locked

```php
// MUST always equal creation version
$this->assertEquals('D.2.5', $election->security_articles_version);
```

No migrations should update this field after initial creation.

---

## Source Code Audit Checklist

### 1. Election Model Protection

- [ ] `Election` model has NO mutator for `security_articles_snapshot`
- [ ] `Election` model has NO custom `save()` that updates articles
- [ ] `security_articles_snapshot` is NOT in `$fillable` array (or only during creation)
- [ ] `security_articles_version` is NOT in `$fillable`
- [ ] `constitutional_hash` is NOT in `$fillable`

**Files to Check**:
- `app/Models/Election.php`
- `app/Models/Traits/ElectionTrait.php` (if exists)

### 2. Controller Mutations

- [ ] No controller endpoint accepts `security_articles_snapshot` as input
- [ ] No controller calls `$election->update()` with articles data
- [ ] No admin controller has "Edit Constitutional Articles" action
- [ ] No API endpoint allows PATCH/PUT to constitutional fields

**Files to Check**:
- `app/Http/Controllers/Admin/ElectionController.php`
- `app/Http/Controllers/ElectionSettingsController.php`
- Any route matching `/election/{id}/settings`, `/election/{id}/rules`, etc.

### 3. Service Layer Mutations

- [ ] `ElectionSettingsService` does NOT mutate articles
- [ ] `ElectionConfigurationService` does NOT mutate articles
- No service method accepts new constitutional rules as parameter after creation

**Files to Check**:
- `app/Services/Election*.php`
- `app/Application/Election/Services/*.php`

### 4. Migration Safety

All migrations must verify:
- [ ] No ALTER TABLE adding articles mutation columns
- [ ] No UPDATE statement targets articles snapshot
- [ ] No migration runs code that calls `$election->save()` with articles

**Files to Check**:
- `database/migrations/*election*.php`
- `database/migrations/*security*.php`
- `database/migrations/*constitutional*.php`

### 5. Admin UI Validation

- [ ] No election settings form includes articles fields
- [ ] No admin dashboard has "Edit Constitution" section
- [ ] No multi-tenant admin can modify any tenant's articles
- [ ] Articles displayed as READ-ONLY in admin panels

**Files to Check**:
- `resources/views/admin/elections/*`
- `resources/js/Pages/Admin/Elections/*`
- Vue components with election settings UI

### 6. Authorization Gate Protection

If Laravel policies control article editing:

```php
// MUST be forbidden
Gate::define('edit_election_articles', function (User $user, Election $election) {
    return false; // Always forbidden
});

Gate::define('update_security_articles', function (User $user, Election $election) {
    return false; // Always forbidden
});
```

**Files to Check**:
- `app/Policies/ElectionPolicy.php`
- Authorization gate definitions in `AppServiceProvider`

---

## Database-Level Verification

### Query 1: Validate Hash Integrity

```sql
SELECT 
    id,
    security_articles_snapshot,
    constitutional_hash,
    SHA2(security_articles_snapshot, 256) AS calculated_hash,
    CASE 
        WHEN constitutional_hash = SHA2(security_articles_snapshot, 256) 
        THEN 'OK' 
        ELSE 'HASH_MISMATCH' 
    END AS integrity_check
FROM elections
WHERE constitutional_hash != SHA2(security_articles_snapshot, 256);
```

**Expected Result**: Zero rows (no mismatches).

### Query 2: Detect Mutation Patterns

```sql
-- Find elections with unusual snapshot patterns
SELECT id, constitutional_hash, created_at, updated_at
FROM elections
WHERE updated_at > DATE_ADD(created_at, INTERVAL 5 MINUTE)
  AND DATEDIFF(updated_at, created_at) > 0
ORDER BY updated_at DESC
LIMIT 20;
```

**Investigation**: Any election updated significantly after creation might indicate mutation attempt.

---

## Runtime Mutation Protection Tests

### Test 1: Snapshot Remains Read-Only

```php
test_snapshot_cannot_be_modified_via_property_assignment()
{
    $election = Election::factory()->create();
    $original = $election->security_articles_snapshot;
    
    // Attempt direct assignment
    $election->security_articles_snapshot = 'hacked';
    
    // Verify it reverts or fails
    $this->assertNotEquals('hacked', $election->security_articles_snapshot);
    $this->assertEquals($original, $election->security_articles_snapshot);
}
```

### Test 2: Hash Mismatch Detection

```php
test_constitutional_hash_mismatch_is_detected_immediately()
{
    $election = Election::factory()->create();
    
    // Verify hash matches
    $this->assertTrue($election->hasValidConstitutionalHash());
    
    // Simulate mutation (should NOT happen, but test guards)
    // This test verifies the invariant is detectable
}
```

### Test 3: Immutable Snapshot Property

```php
test_security_articles_snapshot_cannot_be_mass_assigned()
{
    $election = Election::factory()->create();
    
    // Attempt mass assignment
    $updated = $election->update([
        'security_articles_snapshot' => json_encode(['evil' => true]),
    ]);
    
    // Verify update failed or was ignored
    $reloaded = Election::find($election->id);
    $this->assertNotContains('evil', $reloaded->security_articles_snapshot);
}
```

---

## Constitutional Mutation Prevention Pattern

### Recommended Implementation

```php
// app/Models/Election.php

class Election extends Model
{
    // Remove from fillable to prevent mass assignment
    protected $fillable = [
        'name',
        'type',
        // ... other fields
        // ❌ NOT: 'security_articles_snapshot'
        // ❌ NOT: 'constitutional_hash'
        // ❌ NOT: 'security_articles_version'
    ];
    
    // Override setter to prevent assignment after creation
    public function setSecurityArticlesSnapshotAttribute($value)
    {
        if ($this->exists) {
            throw new \LogicException(
                'Constitutional articles are immutable after election creation'
            );
        }
        $this->attributes['security_articles_snapshot'] = $value;
    }
    
    // Immutable getter - verify hash
    public function getSecurityArticlesSnapshotAttribute($value)
    {
        $expectedHash = hash('sha256', $value);
        if ($expectedHash !== $this->constitutional_hash) {
            throw new \RuntimeException(
                'Constitutional hash mismatch - snapshot may have been corrupted'
            );
        }
        return $value;
    }
    
    public function hasValidConstitutionalHash(): bool
    {
        return hash('sha256', $this->security_articles_snapshot) 
            === $this->constitutional_hash;
    }
}
```

---

## Audit Results Template

| Check | Status | Finding | Risk |
|-------|--------|---------|------|
| Snapshot frozen at creation | ⬜ | | |
| No database mutations post-creation | ⬜ | | |
| No admin UI allows editing | ⬜ | | |
| Hash validates snapshot integrity | ⬜ | | |
| Version remains locked | ⬜ | | |
| Model has no mutator | ⬜ | | |
| No controller mutations | ⬜ | | |
| No service mutations | ⬜ | | |
| Migrations never update articles | ⬜ | | |
| Authorization forbids edits | ⬜ | | |

---

## Expected Outcome

After this audit completes:

✅ Constitutional articles are provably immutable after creation
✅ Replay determinism is guaranteed (same rules → same outcome)
✅ Federation can trust snapshot lineage
✅ Disputes can be reconstructed with certainty
✅ No hidden mutation paths exist

If ANY mutations are found, they must be eliminated BEFORE D.6 proceeds.
