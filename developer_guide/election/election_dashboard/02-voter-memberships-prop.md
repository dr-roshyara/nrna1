# 02 — `voterMemberships` Prop

**File:** `app/Http/Controllers/OrganisationController.php` — `show()` method

---

## Why This Prop Exists

The organisation show page previously passed no voter-specific data. The frontend had no way to know whether the current user was a registered voter in any given election, or whether they had already voted. The `voterMemberships` prop fills that gap.

---

## How It's Built

After the real elections query, `show()` loads the current user's `ElectionMembership` rows for those elections:

```php
$electionIds = $realElections->pluck('id')->toArray();
$voterMemberships = [];

if (!empty($electionIds)) {
    \App\Models\ElectionMembership::where('user_id', $user->id)
        ->whereIn('election_id', $electionIds)
        ->get(['election_id', 'role', 'status', 'has_voted'])
        ->each(function ($m) use (&$voterMemberships) {
            $voterMemberships[$m->election_id] = [
                'role'      => $m->role,
                'status'    => $m->status,
                'has_voted' => (bool) $m->has_voted,
            ];
        });
}
```

Then passed to Inertia:

```php
return inertia('Organisations/Show', [
    // ... other props
    'voterMemberships' => $voterMemberships,
]);
```

---

## Shape

```php
// Key: election UUID (string)
// Value: membership snapshot for the current user
[
    "018e4f2a-xxxx" => [
        "role"      => "voter",     // voter | candidate | observer
        "status"    => "active",    // active | inactive | removed
        "has_voted" => false,       // bool — cast from tinyint
    ],
    "018e4f2b-xxxx" => [
        "role"      => "voter",
        "status"    => "inactive",
        "has_voted" => true,
    ],
]
```

An election absent from the map means the user has **no membership** → treated as ineligible.

---

## Cost

This is a single `SELECT` with a `WHERE user_id = ? AND election_id IN (...)` query. Typically 1–3 elections per org page load. No N+1 risk — it's one query regardless of election count.

---

## Where `$user` Comes From

`$user` is `auth()->user()`, resolved at the top of `show()`. The method already used it for officer role detection before this change.

---

## Frontend Prop Definition

```js
// Show.vue defineProps
voterMemberships: { type: Object, default: () => ({}) },
```

Default is an empty object so the page renders correctly even if the prop is somehow absent.
