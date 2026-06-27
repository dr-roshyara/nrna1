# State Machine Quick Reference

## State Constants

```php
Election::STATE_ADMINISTRATION = 'administration'
Election::STATE_NOMINATION = 'nomination'
Election::STATE_VOTING = 'voting'
Election::STATE_RESULTS_PENDING = 'results_pending'
Election::STATE_RESULTS = 'results'
```

## Getting State

```php
$election->current_state          // 'administration', 'nomination', etc.
$election->state_info             // Array with name, color, emoji, description
$election->allowsAction('manage_posts')  // true/false
```

## Phase Transitions

| Phase | Method | Auto-Transition |
|-------|--------|---|
| Admin → Nomination | `completeAdministration()` | ✅ Grace period |
| Nomination → Voting | `completeNomination()` | ✅ Grace period |
| Nomination → Voting | `forceCloseNomination()` | - |
| Results Pending → Results | Set `results_published_at` | ❌ Manual only |
| Voting → Results Pending | Automatic at `voting_ends_at` | ✅ Automatic |

## Route Protection

```php
Route::middleware(['election.state:manage_posts'])->post('/posts', ...);
```

## Action Allowed Matrix

| Action | Admin | Nom | Vote | Pend | Res |
|--------|-------|-----|------|------|-----|
| manage_posts | ✅ | ❌ | ❌ | ❌ | ❌ |
| import_voters | ✅ | ❌ | ❌ | ❌ | ❌ |
| apply_candidacy | ❌ | ✅ | ❌ | ❌ | ❌ |
| cast_vote | ❌ | ❌ | ✅ | ❌ | ❌ |
| verify_vote | ❌ | ❌ | ✅ | ✅ | ✅ |
| view_results | ❌ | ❌ | ❌ | ❌ | ✅ |

## Key Files

| File | Purpose |
|------|---------|
| `app/Models/Election.php` | State machine logic |
| `app/Http/Middleware/EnsureElectionState.php` | Route protection |
| `resources/js/Pages/Election/Partials/StateMachinePanel.vue` | Timeline UI |
| `tests/Feature/ElectionStateMachineTest.php` | 25 tests, all passing |

## Common Commands

```bash
# Check current state
php artisan tinker
> $election = Election::find(1); $election->current_state

# Run grace period processing
php artisan elections:process-grace-periods

# Run tests
php artisan test tests/Feature/ElectionStateMachineTest.php

# Check specific method
grep -n "completeAdministration" app/Models/Election.php
```

## Database Columns (New)

**Administration**:
- `administration_suggested_start`, `_end`
- `administration_completed` (boolean)
- `administration_completed_at`

**Nomination**:
- `nomination_suggested_start`, `_end`
- `nomination_completed` (boolean)
- `nomination_completed_at`

**Voting**:
- `voting_starts_at`, `voting_ends_at`

**Config**:
- `allow_auto_transition` (boolean, default true)
- `auto_transition_grace_days` (integer, default 7)

**Audit**:
- `state_audit_log` (JSON array)

## Vue Component Props

```vue
<StateMachinePanel
  :stateMachine="{ currentState, stateInfo, metrics }"
  :election="election"
  :organisation="organisation"
  @phase-completed="handler"
  @dates-updated="handler"
/>
```

## Important Rules

⚠️ **DO NOT**:
- Manually set `current_state` column (doesn't exist - state is derived)
- Override voting dates once voting has started
- Modify state without calling proper methods
- Use legacy `status` field for state machine (backward compatibility only)

✅ **DO**:
- Check `allowsAction()` before allowing operation
- Use `completeAdministration()` for transitions
- Protect routes with `election.state:operation` middleware
- Handle `DomainException` when transitions fail

## Timeline Constraints

```
admin_end ≤ nom_start ≤ nom_end ≤ vote_start ≤ vote_end ≤ results_pub
```

All timestamps must be in chronological order. `validateTimeline()` is called on save.

## Testing

```php
// All 25 tests pass
php artisan test tests/Feature/ElectionStateMachineTest.php
```

Coverage includes:
- State derivation logic
- State transitions
- Authorization checks (`allowsAction()`)
- Timeline validation
- HTTP route protection
- Grace period handling
- Edge cases

---

**Full docs available in:**
- `README.md` - Overview
- `ARCHITECTURE.md` - Design patterns
- `STATES.md` - State definitions
- `DATABASE.md` - Schema
- `MODELS.md` - Method signatures
- `EXAMPLES.md` - Code samples
