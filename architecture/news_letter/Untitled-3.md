## Process: Switching from Election-Only to Full Membership

### Overview

Switching **from Election-Only to Full Membership** is **safe and reversible**. No data is lost.

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                    ELECTION-ONLY → FULL MEMBERSHIP                                │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                   │
│  BEFORE: Election-Only Mode                                                       │
│  ┌─────────────────────────────────────────────────────────────────────────────┐ │
│  │ • Any OrganisationUser can vote                                              │ │
│  │ • No Member records required                                                 │ │
│  │ • CSV import validates email only                                            │ │
│  └─────────────────────────────────────────────────────────────────────────────┘ │
│                                                                                   │
│                                    ↓                                              │
│                          Admin toggles switch                                      │
│                                    ↓                                              │
│                                                                                   │
│  AFTER: Full Membership Mode                                                      │
│  ┌─────────────────────────────────────────────────────────────────────────────┐ │
│  │ • Only Members with paid/exempt fees can vote                                │ │
│  │ • Member records required for NEW voters                                     │ │
│  │ • CSV import validates membership + fees                                     │ │
│  └─────────────────────────────────────────────────────────────────────────────┘ │
│                                                                                   │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### Step-by-Step Process

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  STEP 1: Admin navigates to Settings                                              │
│  /organisations/{slug}/settings                                                   │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                   │
│  Current Mode: [ 🟢 Election-Only ]                                               │
│                                                                                   │
│  Require Full Membership                                          [ 🔘 OFF ]     │
│                                                                                   │
└─────────────────────────────────────────────────────────────────────────────────┘
                                    ↓
┌─────────────────────────────────────────────────────────────────────────────────┐
│  STEP 2: Admin toggles switch to ON                                               │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                   │
│  Require Full Membership                                          [ 🟢 ON ]      │
│                                                                                   │
│  ⚠️ No warning shown (safe operation)                                             │
│                                                                                   │
│  [Save Changes]                                                                   │
│                                                                                   │
└─────────────────────────────────────────────────────────────────────────────────┘
                                    ↓
┌─────────────────────────────────────────────────────────────────────────────────┐
│  STEP 3: System updates organisation                                              │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                   │
│  UPDATE organisations SET uses_full_membership = true WHERE id = ?                │
│                                                                                   │
│  ✅ Mode changed successfully                                                     │
│                                                                                   │
└─────────────────────────────────────────────────────────────────────────────────┘
                                    ↓
┌─────────────────────────────────────────────────────────────────────────────────┐
│  STEP 4: Immediate Effects                                                        │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                   │
│  What changes immediately:                                                        │
│  ┌─────────────────────────────────────────────────────────────────────────────┐ │
│  │ • Voters dropdown now shows ONLY members (may become empty)                  │ │
│  │ • "ASSIGN USERS" → "ASSIGN MEMBERS"                                          │ │
│  │ • CSV import now validates membership + fees                                 │ │
│  │ • New voter assignments require Member record                                 │ │
│  └─────────────────────────────────────────────────────────────────────────────┘ │
│                                                                                   │
│  What stays the same:                                                             │
│  ┌─────────────────────────────────────────────────────────────────────────────┐ │
│  │ • Existing assigned voters remain voters                                     │ │
│  │ • Existing elections unaffected                                              │ │
│  │ • User accounts unchanged                                                    │ │
│  └─────────────────────────────────────────────────────────────────────────────┘ │
│                                                                                   │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### What Happens to Existing Voters?

| Voter Type | Before Switch | After Switch |
|------------|---------------|--------------|
| Already assigned to election | ✅ Can vote | ✅ Can vote (unchanged) |
| Not yet assigned (no Member) | ✅ Could be added | ❌ Cannot be added until Member created |
| Not yet assigned (has Member) | ✅ Could be added | ✅ Can be added (if fees paid) |

### Admin Next Steps After Switching

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                    POST-SWITCH ADMIN TASKS                                        │
├─────────────────────────────────────────────────────────────────────────────────┤
│                                                                                   │
│  1. Create Member records for existing users                                      │
│     └── Members → Add Member → Select users                                       │
│                                                                                   │
│  2. Set up Membership Types (if not exists)                                       │
│     └── Membership → Types → Create                                               │
│                                                                                   │
│  3. Mark fees as paid/exempt                                                      │
│     └── Members → Find member → Mark Paid                                         │
│                                                                                   │
│  4. Assign members to elections                                                   │
│     └── Elections → Voters → Select from dropdown                                 │
│                                                                                   │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### Code Path: No Warning for This Direction

```php
// OrganisationSettingsController.php
public function updateMembershipMode(Request $request, Organisation $organisation)
{
    // Warning ONLY when switching FROM full TO election-only WITH members
    if ($organisation->uses_full_membership && !$validated['uses_full_membership'] && $memberCount > 0) {
        // Show warning
    }
    
    // Switching FROM election-only TO full: NO WARNING (safe)
    $organisation->update(['uses_full_membership' => $validated['uses_full_membership']]);
}
```

### Visual Comparison: Both Directions

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│  ELECTION-ONLY → FULL MEMBERSHIP                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐ │
│  │  ✅ Safe — No warning                                                       │ │
│  │  ✅ No data loss                                                            │ │
│  │  ✅ Existing voters preserved                                               │ │
│  │  ⚠️ New voters need Member records                                          │ │
│  └─────────────────────────────────────────────────────────────────────────────┘ │
├─────────────────────────────────────────────────────────────────────────────────┤
│  FULL MEMBERSHIP → ELECTION-ONLY                                                  │
│  ┌─────────────────────────────────────────────────────────────────────────────┐ │
│  │  ⚠️ Warning shown if members exist                                          │ │
│  │  ✅ Member data preserved (not deleted)                                     │ │
│  │  ✅ Existing voters preserved                                               │ │
│  │  ℹ️ Any user can now be added as voter                                      │ │
│  └─────────────────────────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────────────────────────┘
```

### Summary

| Aspect | Election-Only → Full Membership |
|--------|--------------------------------|
| Warning shown? | ❌ No |
| Data loss? | ❌ None |
| Existing voters affected? | ❌ No |
| New voter requirements | Stricter (Member + fees) |
| Admin action needed | Create Member records |

**Om Gam Ganapataye Namah** 🪔🐘