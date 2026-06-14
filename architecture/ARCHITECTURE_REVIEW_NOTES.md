# Architecture Review Notes

**Purpose:** Track findings that require architectural investigation but are not yet resolved.

---

## ARN-001: CapabilityResolver vs validateOpenVoting Inconsistency

**Date:** 2026-06-14  
**Status:** Open  
**Priority:** P1

### Finding

`CapabilityResolver` says `open_voting` is allowed in `setup_nomination` state, but `Election::validateOpenVoting()` transitions can still reject the action because additional checks (nomination_completed, candidates exist, no pending applications) are only verified during transition validation, not during capability evaluation.

### Evidence

- Constitution allows `open_voting` in `['setup_nomination', 'ready_for_voting']` states ✅
- `LifecycleCapabilityBaselinePolicy` checks only state + role + preconditions — not nomination completion
- `Election::whyCannotOpenVoting()` checks `nomination_completed`, `candidates_count`, `pending_candidacies_count`
- `Election::validateOpenVoting()` throws `DomainException` if `whyCannotOpenVoting()` returns a reason

### Risk

Frontend shows the button as enabled (capability says allowed), but the backend rejects the transition. User clicks → error instead of explanation.

### Temporary Workaround (Authorized)

None currently applied. The frontend renders the ability as the capability resolver reports it. A backend fix is required.

### Recommended Resolution

Add the missing preconditions to the Constitution's `open_voting` rule definition or ensure the capability resolver evaluates the same conditions as `validateOpenVoting()`:

```php
'preconditions' => [
    'voting_window_defined',
    'timezone_set',
    'nomination_completed',   // ← add these
    'has_approved_candidates',
    'no_pending_candidacies',
],
```

### References

- `ElectionConstitution::RULES` → `open_voting`
- `LifecycleCapabilityBaselinePolicy::evaluate()` — only checks state/role
- `Election::whyCannotOpenVoting()` — checks nomination completion
- `Election::validateOpenVoting()` — calls whyCannotOpenVoting
