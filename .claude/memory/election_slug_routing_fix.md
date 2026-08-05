---
name: election-slug-routing-fix
description: "HTTP controller expects election slug, not ID, as route parameter"
metadata: 
  node_type: memory
  type: feedback
  originSessionId: 0fdfc82e-8e16-4c4d-ac2f-e030787e412d
---

## Election Routing Pattern: Slug, Not ID

**Rule:** All HTTP routes with election parameters must use the election **slug**, not the election **ID**.

**Why:** Controller explicitly queries by slug:
```php
// app/Http/Controllers/Election/ElectionVoterController.php ~line 198
$election = Election::withoutGlobalScopes()->where('slug', $election)->firstOrFail();
```

If tests pass election ID as route parameter, model binding fails with 404.

**How to apply:** When writing tests that call election routes, use:
```php
// CORRECT
route('elections.voters.approve', [
    'election' => $this->election->slug,
    'membership' => $membership->id
])

// WRONG — returns 404
route('elections.voters.approve', [
    'election' => $this->election->id,
    'membership' => $membership->id
])
```

## Discovery Path

Discovered in ElectionVoterManagementTest (2026-05-30):
- 8 tests failing with 404 errors
- Model binding on ElectionMembership appeared to be the culprit (BelongsToTenant scope)
- Root cause: Election lookup failed first (404 before model binding was attempted)
- TenantContext singleton isolation and UserOrganisationRole pattern were red herrings
- Simple: Election controller looks up by slug, tests passed ID

**Impact:** 10 election voter management tests now pass after fixing route parameters.

[[phase1_constitutional_parity_strategy]] [[architecture_pragmatism]]
