# State Transition Analysis — The "Draft Reset" Anomaly

**Symptom:** Election state keeps reverting to 'draft' during tests, despite being set to 'counting'.

**Test Evidence:**
```
Test setUp:
  Election::factory()->create()  // state = 'draft'
  
DB::update(['state' => 'counting'])  // Attempt to set state
Election::refresh()  // Reload from DB
  
Result: state = 'draft'  (not 'counting')
```

---

## Hypothesis 1: State Mutator Blocking Writes

**Location:** `app/Models/Election.php::setStateAttribute()` (lines 909-932)

**Code:**
```php
public function setStateAttribute($value): void
{
    if (!\App\Application\Election\Governance\ElectionStateWriteContext::isAuthorized()) {
        // At Level 4: throws UnauthorizedStateMutationException
        if (\App\Application\Election\Deprecation\DeprecationPolicy::isEnforcementActive(4)) {
            throw new \App\Exceptions\UnauthorizedStateMutationException(...);
        }
    }
    // Write allowed if:
    // - Context is authorized, OR
    // - Enforcement level < 4
    $this->attributes['state'] = $value;
}
```

**Problem:**
- This mutator only applies to Eloquent model assignment (e.g., `$election->state = 'counting'`)
- Direct DB updates (e.g., `DB::table('elections')->update(...)`) bypass the mutator entirely
- So direct DB updates should work

**Conclusion:** State mutator NOT the cause of the reset.

---

## Hypothesis 2: Model Boot Methods Resetting State

**Location:** `app/Models/Election.php::booted()` (line 2171)

Need to examine: Does the `booted()` method contain any code that resets state back to 'draft'?

**Test Findings:**
```
grep for booted() in Election.php
Found at line 2171
Must inspect what it does
```

**Status:** NEEDS INVESTIGATION

---

## Hypothesis 3: Factory-Specific Behavior

**Location:** `database/factories/ElectionFactory.php`

**Current Factory Definition:**
```php
public function definition()
{
    return [
        'state' => 'draft',  // Line 31: Default is draft
        // ... other fields
    ];
}
```

**Observation:**
The factory returns `'state' => 'draft'` from `definition()`.

When `create()` is called, it:
1. Calls `definition()` to get default attributes
2. Passes attributes to create
3. The `state` attribute in the create array is `'draft'`

Even though we do `DB::update(['state' => 'counting'])` AFTER create:
- The Eloquent model in memory still has `state = 'draft'` (from the create call)
- We call `refresh()` to reload from DB
- If DB shows `'draft'`, then something updated it back to draft

**Question:** Is there a database trigger? A foreign key cascade? A migration that sets a default?

---

## Hypothesis 4: Refresh() Loading Wrong State from DB

**Scenario:**
```
1. Election created with state='draft'
2. DB raw update sets state='counting'
3. refresh() called
4. But refresh() loads state='draft' from DB
```

**Possible causes:**
- Database trigger that reverts state
- Transaction isolation (test runs in transaction, DB sees draft)
- Multiple elections with same name causing WHERE clause to match wrong row

**Check:**
Need to verify:
1. What row is actually being updated? (use election id)
2. What does the DB actually contain after update?
3. Is refresh() loading a different row?

---

## Hypothesis 5: Test Database vs Factory Behavior

**Observation:** Tests use `RefreshDatabase` trait

**Mechanism:**
```php
use RefreshDatabase;
```

This wraps each test in a database transaction:
```
BEGIN TRANSACTION
  Run test
  DB changes happen
  refresh() loads from within the transaction
ROLLBACK
```

**Possibility:**
The state field might have a **database-level default** that's triggered during the transaction.

Example:
```sql
ALTER TABLE elections ADD CONSTRAINT state_default DEFAULT 'draft'
```

When a row is created, even if we explicitly set state, a constraint or trigger might enforce a minimum state of 'draft'.

**Status:** NEEDS INVESTIGATION - Check migrations for default constraints

---

## Investigation Steps Needed

### Step 1: Check Election Model Boot Method
```bash
grep -A 50 "protected static function booted" app/Models/Election.php
```
Look for:
- Event listeners that modify state
- Observers attached to the model
- Any code that sets state = 'draft'

### Step 2: Check Database Migrations
```bash
grep -r "state.*default.*draft\|state.*trigger" database/migrations/
```
Look for:
- Default constraints on state column
- Database triggers
- Check constraints

### Step 3: Verify DB Update Works in Isolation
```php
// In test
DB::table('elections')->where('id', $id)->update(['state' => 'counting']);
$result = DB::table('elections')->where('id', $id)->first();
dd($result->state);  // What's the actual DB value?
```

### Step 4: Check for Observers
```bash
grep -r "Election.*Observer\|observe.*Election" app/
```
Observer pattern might be modifying state after create.

---

## Current Best Guess

The state is getting reset to 'draft' either by:
1. A database-level constraint/trigger
2. An Eloquent observer on the Election model
3. A boot() method side effect
4. A view/computed attribute that's being persisted back

Most likely: **Database constraint or Eloquent observer**

---

## Evidence Required Before Proceeding

Before we can fix the test, we need to know:
1. **Does `booted()` contain code that affects state?**
2. **Are there database triggers on the elections table?**
3. **Are there Eloquent observers listening to Election?**
4. **Does the `state` column have constraints in the schema?**
5. **Does a direct SELECT immediately after UPDATE return 'draft' or 'counting'?**

Once we answer these questions, we'll understand why the state resets and whether:
- This is a test setup issue (we're not setting up the state correctly), OR
- This is revealing a real architectural issue (the state machine prevents direct state updates)

