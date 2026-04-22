## Grace Period - Explanation

### Simple Definition

A **grace period** is an extra buffer time after a phase's suggested end date before the system automatically moves to the next phase.

---

## Visual Example

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                    GRACE PERIOD EXAMPLE                                              │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                      │
│  Administration Phase                                                               │
│  ┌─────────────────────────────────────────────────────────────────────────────┐    │
│  │  Suggested End Date: April 25, 2026                                          │    │
│  │                                                                               │    │
│  │  GRACE PERIOD: 7 days                                                        │    │
│  │  ┌─────────────────────────────────────────────────────────────────────┐    │    │
│  │  │  April 26 ── April 27 ── April 28 ── April 29 ── April 30 ── May 1  │    │    │
│  │  │                                                                       │    │    │
│  │  │  ←─────────────── GRACE PERIOD (7 days) ───────────────→            │    │    │
│  │  └─────────────────────────────────────────────────────────────────────┘    │    │
│  │                                                                               │    │
│  │  If admin hasn't clicked "Complete" by May 1 → AUTO-TRANSITION to Nomination │    │
│  └─────────────────────────────────────────────────────────────────────────────┘    │
│                                                                                      │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

---

## Why Grace Periods Exist

| Without Grace Period | With Grace Period |
|---------------------|-------------------|
| Phase ends → immediately locks | Phase ends → grace period starts |
| Admin can't finish incomplete work | Admin has buffer time to complete |
| Forced transition may break election | System waits before auto-transition |
| Users get locked out | Flexibility for real-world delays |

---

## Your Implementation

### Database Columns

```php
$table->boolean('allow_auto_transition')->default(true);     // Enable/disable
$table->unsignedInteger('auto_transition_grace_days')->default(7);  // Buffer days
```

### Admin UI Controls

In your timeline settings page:

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│  Auto-Transition                                                                    │
│  Automatic phase transitions after grace period                                     │
│                                                                                      │
│  ┌─────────────────────────────────────┬─────────────────────────────────────────┐   │
│  │  Allow Auto-Transition        [ON]  │  Grace Period (Days)                    │   │
│  │  Automatically transition phases    │  ┌─────────────────────────────────┐   │   │
│  │  after grace period                 │  │              7                   │   │   │
│  │                                     │  └─────────────────────────────────┘   │   │
│  │                                     │  Days after suggested end date          │   │
│  └─────────────────────────────────────┴─────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

---

## When Auto-Transition Happens

### Scenario 1: Administration → Nomination

```php
if (
    $election->allow_auto_transition &&           // Enabled
    $election->administration_completed &&       // Admin phase done
    !$election->nomination_completed &&          // Nomination not started
    $election->administration_completed_at       // Completion date exists
        ->addDays($election->auto_transition_grace_days) < now()  // Grace period passed
) {
    $election->completeNomination('Auto-transition after grace period');
}
```

### Scenario 2: Nomination → Voting

```php
if (
    $election->allow_auto_transition &&           // Enabled
    $election->nomination_completed &&           // Nomination done
    $election->nomination_completed_at           // Completion date exists
        ->addDays($election->auto_transition_grace_days) < now()  // Grace period passed
    && !$election->hasPendingCandidates()        // No pending applications
) {
    $election->lockVoting();                     // Lock and prepare voting
}
```

---

## Example Timeline

| Date | Event |
|------|-------|
| April 25 | Administration suggested end date |
| April 26 - May 2 | **Grace period (7 days)** - Admin can still work |
| May 3 | Auto-transition to Nomination (if not completed manually) |

---

## When to Disable Auto-Transition

| Situation | Why |
|-----------|-----|
| **Complex election** | Need manual control over timing |
| **Legal requirements** | Must follow strict schedule |
| **Pending issues** | Need to resolve before transitioning |
| **Testing** | Prevent unexpected transitions |

---

## Summary

| Term | Meaning |
|------|---------|
| **Grace Period** | Buffer time after suggested end date |
| **Auto-Transition** | Automatic move to next phase after grace period |
| **allow_auto_transition** | Master toggle (ON/OFF) |
| **auto_transition_grace_days** | Number of buffer days (1-30) |

**Grace periods give election officers flexibility while maintaining automated fallback.** 🚀