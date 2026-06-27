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

---

## TODO-BE-001: Review Open Voting Capability Consistency

**Priority:** P1  
**Related ARN:** ARN-001  
**Status:** Open — requires backend investigation

### Problem

The UI shows `open_voting` as available during `setup_nomination` because the capability snapshot reports it as allowed. However, transition validation enforces additional business rules (nomination completed, approved candidates exist, no pending candidacies). This can result in an enabled button that, when clicked, triggers a backend rejection.

### Architectural Concern

The capability system and transition validation may be expressing different business rules. This violates the desired authority chain:

```
ElectionConstitution → Capability Resolver → Frontend
```

because the frontend can display an action as available while the backend later rejects it.

### Investigation Required

Review:
1. `ElectionConstitution::RULES['open_voting']`
2. `LifecycleCapabilityBaselinePolicy::evaluate()`
3. `CapabilityResolver` — how preconditions are evaluated
4. `Election::whyCannotOpenVoting()`
5. `Election::validateOpenVoting()`

Determine:
1. Should `open_voting` be allowed during `setup_nomination`?
2. Should `open_voting` only become available during `ready_for_voting`?
3. Which rule is authoritative — the Constitution's state-gate or the transition's precondition check?
4. Are constitutional preconditions missing from the capability evaluation?

### Expected Outcome

Capability evaluation and transition validation must produce consistent governance decisions. No frontend workaround should be introduced.

### Constraints

- Do not change the Constitution without evidence
- Do not change CapabilityResolver without evidence
- Do not add frontend workarounds
- Only investigate and recommend
