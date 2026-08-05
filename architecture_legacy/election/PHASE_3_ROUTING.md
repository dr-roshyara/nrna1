# Phase 3: Election Routing Strategy

**Status:** Decision Made
**Date:** 2026-02-03
**Decision:** Option B - Simplicity First

---

## Routing Architecture

### **PUBLIC ROUTES (für normale Nutzer)**

```
GET  /vote              → VoteController@create
     Auto-selects: First REAL election (is_active = true)
     Redirect → /vote/create (existing flow)

GET  /demo              → ElectionController@demo
     Auto-selects: First DEMO election (is_active = true)
     Redirect → /vote/create with election context
```

### **ADMIN ROUTES (Hidden, internal use only)**

```
GET  /admin/elections           → ElectionStatsDashboard
GET  /admin/elections/select    → ElectionSelector (for testing)
POST /admin/elections/{id}/reset → Demo reset endpoint
```

---

## Implementation Details

### **VoteController@create (Existing - No Changes)**

```php
public function create(Request $request)
{
    // Phase 2c: Already handles election context via middleware
    // ElectionMiddleware resolves:
    // 1. Session election_id
    // 2. URL route parameter (if tenant context)
    // 3. Default to first REAL election

    return inertia('Vote/Create');
}
```

### **ElectionController@demo (NEW)**

```php
public function demo(Request $request)
{
    $demoElection = Election::where('type', 'demo')
        ->where('is_active', true)
        ->orderBy('created_at', 'desc')
        ->first();

    if (!$demoElection) {
        return redirect('/vote')
            ->with('message', 'No demo elections available');
    }

    // Store in session for voting flow
    session()->put('current_election_id', $demoElection->id);

    // Redirect to voting page
    return redirect()->route('vote.create');
}
```

---

## Component Usage Matrix

| Component | Public? | Admin? | Usage |
|-----------|---------|--------|-------|
| **ElectionTypeBadge** | ✅ YES | ✅ YES | Badge on all voting pages |
| **ElectionCard** | ❌ NO | ✅ YES | Admin election selector only |
| **ElectionSelector** | ❌ NO | ✅ YES | `/admin/elections/select` |
| **SelectElection** | ❌ NO | ✅ YES | Page wrapper for admin |
| **VotingLayout** | ✅ YES | ✅ YES | Voting page layout |
| **ElectionStatsDashboard** | ❌ NO | ✅ YES | `/admin/elections` |

---

## Data Flow

### **User: Click "Vote"**
```
User clicks /vote
    ↓
ElectionMiddleware (Phase 2c)
    ├─ Check session for election_id
    ├─ If not found: First REAL election
    ├─ Store in session
    └─ Continue
    ↓
VoteController@create
    ↓
Vote/Create.vue (with VotingLayout)
    ├─ Show ElectionTypeBadge
    ├─ Show Demo Notice (if demo)
    └─ Start voting process
```

### **User: Click "Demo"**
```
User clicks /demo
    ↓
ElectionController@demo
    ├─ Find first DEMO election
    ├─ Store in session['current_election_id']
    └─ Redirect to /vote/create
    ↓
(Same as above)
```

### **Admin: Access Elections Dashboard**
```
Admin clicks /admin/elections
    ↓
ElectionStatsDashboard.vue
    ├─ Show REAL + DEMO stats
    ├─ Show demo reset button
    ├─ Link to /admin/elections/select
    └─ Admin tools
```

---

## Navigation Links

### **Navbar (for all users)**
```vue
<nav>
  <Link href="/vote">Vote</Link>
  <Link href="/demo">Demo Vote</Link>
  <!-- That's it for users -->

  <!-- Admin only (hidden) -->
  <Link v-if="isAdmin" href="/admin/elections">Admin</Link>
</nav>
```

---

## Benefits of This Approach

✅ **Simplicity** - Users see only 2 options
✅ **Fast** - No selection modal for 95% of users
✅ **Backward Compatible** - Existing `/vote/create` untouched
✅ **Future-Proof** - ElectionSelector still available for admins
✅ **Clear Mental Model** - "Vote" vs "Demo", not "Which election?"

---

## When to Upgrade (Future)

If organisation needs multiple concurrent elections:
1. Expose `/election/select` in navbar
2. Adjust middleware to require explicit selection
3. ElectionSelector.vue is already ready

---

## Implementation Checklist

- [ ] Create ElectionController@demo
- [ ] Build ElectionStatsDashboard.vue
- [ ] Integrate ElectionTypeBadge into 5 voting pages
- [ ] Update VotingLayout in voting pages
- [ ] Update Navbar with /vote and /demo links
- [ ] Test both pathways (/vote and /demo)
- [ ] Hide admin routes from public
- [ ] Document admin access in README
