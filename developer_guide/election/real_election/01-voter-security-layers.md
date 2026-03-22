# 01 — Voter Security Layers

**Updated:** 2026-03-19

---

## Overview

Real elections use a **9-layer defence-in-depth** security model. Each layer is independent —
a failure in one does not silently allow a vote through. Layers are numbered 0–5, with Layer 0
having multiple sub-layers for maximum coverage.

---

## The Full Layer Stack

```
HTTP Request (voter hits a v/{vslug} route)
        │
        ▼
┌──────────────────────────────────────────────────────────────────┐
│  ROUTE MIDDLEWARE CHAIN  (electionRoutes.php, line 362)          │
├──────────────────────────────────────────────────────────────────┤
│  SubstituteBindings      — resolve route model bindings          │
│  voter.slug.verify       — slug exists, belongs to this user     │
│  voter.slug.window       — slug has not expired                  │
│  voter.slug.consistency  — election + org validated;             │
│                            sets $request->attributes['election'] │
│  ensure.election.voter   — Layer 0a: membership check ◀ NEW     │
│  voter.step.order        — step sequence enforced (1→2→3→4→5)   │
│  vote.eligibility        — legacy can_vote flag check            │
│  validate.voting.ip      — IP restriction enforcement            │
│  vote.organisation       — organisation context security         │
└──────────────────────────────────────────────────────────────────┘
        │
        ▼
┌──────────────────────────────────────────────────────────────────┐
│  CONTROLLER LAYER  (defense-in-depth)                            │
├──────────────────────────────────────────────────────────────────┤
│  CodeController::create()         — Layer 0b (cached)           │
│  CodeController::store()          — Layer 0c (cached)           │
│  CodeController::showAgreement()  — Layer 0d (cached)           │
│  CodeController::submitAgreement()— Layer 0e (cached)           │
│  VoteController::create()         — Layer 0f (cached)           │
│  VoteController::first_submission()— Layer 0g (cached)          │
│  VoteController::verify()         — Layer 0h (FRESH DB) ◀ CRIT │
│  VoteController::store()          — Layer 0i (FRESH DB + TX) ◀  │
└──────────────────────────────────────────────────────────────────┘
        │
        ▼
┌──────────────────────────────────────────────────────────────────┐
│  EXISTING LAYERS 1–5  (unchanged)                                │
├──────────────────────────────────────────────────────────────────┤
│  Layer 1 — has_voted flag (CodeController::create)              │
│  Layer 2 — is_code1_usable flag                                 │
│  Layer 3 — vote timing window (voting_time_min)                 │
│  Layer 4 — vote session integrity                               │
│  Layer 5 — final hash verification (VoteController::store)      │
└──────────────────────────────────────────────────────────────────┘
```

---

## Security Matrix

| Layer | Location | Check | Cache | Race-safe |
|-------|----------|-------|-------|-----------|
| **0a** | Middleware `ensure.election.voter` | `isVoterInElection()` | ✅ 5 min | — |
| **0b** | `CodeController::create()` | `isVoterInElection()` | ✅ 5 min | — |
| **0c** | `CodeController::store()` | `isVoterInElection()` | ✅ 5 min | — |
| **0d** | `CodeController::showAgreement()` | `isVoterInElection()` | ✅ 5 min | — |
| **0e** | `CodeController::submitAgreement()` | `isVoterInElection()` | ✅ 5 min | — |
| **0f** | `VoteController::create()` | `isVoterInElection()` | ✅ 5 min | — |
| **0g** | `VoteController::first_submission()` | `isVoterInElection()` | ✅ 5 min | — |
| **0h** | `VoteController::verify()` | Fresh `ElectionMembership` query | ❌ No | ✅ Yes |
| **0i** | `VoteController::store()` | Fresh `ElectionMembership` query + rollback | ❌ No | ✅ Yes |
| Removal | `ElectionVoterController::destroy()` | `lockForUpdate()` row lock | — | ✅ Yes |
| 1–5 | Existing controllers | `has_voted` flag + code state | — | ✅ Yes |

---

## Why Two Tiers (Middleware + Controller)?

### Tier 1 — Middleware (first line of defence)

The `ensure.election.voter` middleware blocks unassigned voters at the **route level**,
before any controller code runs. This is the cleanest and most efficient gate.

However, middleware can be misconfigured. A new route added without the middleware group
would bypass Tier 1 entirely.

### Tier 2 — Controller (defense-in-depth)

The `EnsuresVoterMembership` trait adds a secondary check inside each controller method.
Even if a route is added without the middleware, the controller will still block the voter.

This is the **fail-safe** layer — it exists specifically for the scenario where Tier 1 is
bypassed (misconfiguration, future refactoring, route injection).

---

## Why Fresh DB on verify() and store()?

The 5-minute cache on `User::isVoterInElection()` is acceptable for early steps (code
entry, agreement). The voter's membership status is unlikely to change in those milliseconds.

However, between `first_submission` and `verify`, there is a meaningful time gap during
which an administrator could remove the voter. The final two steps must use a fresh database
query to detect this:

```
first_submission → (time passes) → verify → store
                        ↑
                 Admin could remove voter here
```

A cached "eligible" result from `first_submission` would allow the voter to complete
the vote even after removal. The fresh check prevents this.

---

## Demo Elections Are Always Exempt

All Layer 0 checks (both middleware and controller) skip demo elections:

```php
if ($election->type === 'demo') {
    return $next($request); // middleware
    return null;            // trait
}
```

Demo elections use the legacy code-based system. `election_memberships` rows are not
created for demo participants, so checking them would always fail.
