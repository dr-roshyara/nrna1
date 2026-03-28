# 12 — ElectionPolicy Authorization

**File:** `app/Policies/ElectionPolicy.php`
**Registered:** `app/Providers/AuthServiceProvider.php`

---

## Policy Methods

| Method | Who can | Used by |
|--------|---------|---------|
| `view` | Any active `ElectionOfficer` | `index`, `export` |
| `manageSettings` | Chief/deputy officer OR org owner/admin | `manageVoters` delegate |
| `manageVoters` | Delegates to `manageSettings` | All write actions |
| `publishResults` | Chief officer only | Result publication |
| `create` | Org owner/admin | Election creation |

---

## `manageSettings` / `manageVoters` — Who Passes

```php
// Passes if ANY of these is true:
ElectionOfficer where user_id = ? AND organisation_id = ? AND role IN ('chief','deputy') AND status = 'active'
OR
UserOrganisationRole where user_id = ? AND organisation_id = ? AND role IN ('owner','admin')
```

This means **org owners and admins can always manage voters** even without an `ElectionOfficer` record — important for bootstrapping a new election before officers are assigned.

---

## Common 403 Causes

| Symptom | Root cause | Fix |
|---------|-----------|-----|
| Owner gets 403 on voter assign | Policy only checked `ElectionOfficer` (old bug, now fixed) | `manageSettings` now also checks `UserOrganisationRole` |
| Controller uses `authorize('manage', $election)` | `manage` method doesn't exist in policy | Use `manageVoters` instead |
| Officer gets 403 | `status` column is not `active` in `election_officers` | Accept the officer invitation first |

---

## Adding a New Protected Action

1. Add a method to `ElectionPolicy` (or reuse `manageVoters`)
2. Call `$this->authorize('methodName', $election)` in the controller
3. Write a feature test covering both authorized and unauthorized cases
