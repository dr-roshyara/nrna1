---
name: Voter Import FK Constraint Pattern
description: Multi-table FK chain for election membership - must create UserOrganisationRole before ElectionMembership
type: reference
originSessionId: be14e6db-faf3-4787-84c8-a39f1f385592
---
## FK Constraint Chain Pattern

The election_memberships table has a composite FK that points to user_organisation_roles, NOT organisation_users:

```php
// Migration (election_memberships table)
$table->foreign(['user_id', 'organisation_id'])
      ->references(['user_id', 'organisation_id'])
      ->on('user_organisation_roles')
      ->onDelete('cascade');
```

## Required Record Creation Order

For any voter import/assignment flow:

```php
// 1. Create/update User (must have organisation_id)
$user = User::firstOrCreate(
    ['email' => $email],
    [
        'organisation_id' => $organisation->id,  // REQUIRED
        'password' => bcrypt(...),
    ]
);

// 2. Create UserOrganisationRole (CRITICAL - FK dependency)
UserOrganisationRole::firstOrCreate(
    ['user_id' => $user->id, 'organisation_id' => $organisation->id],
    ['role' => 'member']
);

// 3. Create OrganisationUser (optional but recommended for compatibility)
OrganisationUser::firstOrCreate(
    ['organisation_id' => $organisation->id, 'user_id' => $user->id],
    ['status' => 'active']
);

// 4. NOW safe to create ElectionMembership (FK validation will pass)
ElectionMembership::firstOrCreate(
    ['election_id' => $election->id, 'user_id' => $user->id],
    ['organisation_id' => $organisation->id, 'role' => 'voter']
);
```

## User Creation in Voter Import

When auto-creating users via voter import:
```php
$user = User::firstOrCreate(
    ['email' => $email],
    [
        'organisation_id' => $organisation->id,  // REQUIRED
        'email_verified_at' => now(),             // Set to verified immediately
        'password' => bcrypt(...),
    ]
);
```

**Important:** email_verified_at must be added to User::fillable array to allow mass assignment.

Imported voters are trusted sources (admin upload), so email verification is skipped. They verify by setting password via invitation token.

## SQLite Specific Behavior

- FK constraint checking happens immediately (not deferred)
- Cannot rely on `DB::transaction()` to defer FK validation
- Must execute operations sequentially without transaction wrapper
- Operations should commit immediately to satisfy downstream FK checks

## Election Global Scope Issue

When loading Election via relationship in VoterInvitation:

```php
// ❌ WRONG - election will be filtered by BelongsToTenant scope
$invitation->load('election');

// ✅ RIGHT - bypass global scope filtering
$election = Election::withoutGlobalScopes()->findOrFail($invitation->election_id);
```

This is especially important in public controllers (VoterInvitationController) where tenant context may not be set.
