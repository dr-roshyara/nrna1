# VoteController — Vote Verification & Display Flow

**Branch:** `multitenancy`
**Updated:** 2026-03-27
**Related test:** `tests/Feature/VoteVerifyTest.php` (15 tests, all GREEN)

---

## Overview

After a voter submits their vote in a real election, they receive a **verification code by email**.
This code lets them confirm their vote was recorded correctly — without ever breaking vote anonymity.

The `Vote` model has **no `user_id` column**. Anonymity is absolute.
Verification is done entirely via cryptographic `receipt_hash` — no link to the `Code` model.

---

## Verification Code Format

```
{32-hex-private_key}_{vote-uuid}

Example:
ba2f5445d5de773786f4a56a9f640d1a_a167196f-f656-4191-9286-44189f78eb9a
```

- `private_key` — 32 hex chars (`bin2hex(random_bytes(16))`), generated at vote submission time
- `vote-uuid` — the UUID primary key of the `votes` table row
- Separator — single `_` (underscore)

---

## receipt_hash Formula

Stored on the `Vote` at submission time (`save_vote()`):

```php
$vote->receipt_hash = hash('sha256', $private_key . $vote->id . config('app.key'));
```

At verification time the same formula is recomputed and compared with `hash_equals()` (timing-safe).

---

## Why NOT use the Code Model for verification

| Approach | Problem |
|---|---|
| `Code::where('voting_code', ...)` | `Code` has `user_id` — creates voter ↔ vote linkage = anonymity breach |
| `Vote::all()` + `Hash::check()` loop | O(n) scan over all votes — unusable at scale |
| `receipt_hash` on Vote ✅ | Direct UUID lookup, timing-safe hash comparison, zero user linkage |

---

## Flow Diagram

```
Voter enters emailed code
        │
        ▼
GET  vote.verify_to_show          ← renders VoteShowVerify.vue (input form)
POST vote.submit_code_to_view_vote ← validates + looks up vote
        │
        ├─ extract_vote_data_from_code()   parse & UUID-validate code
        ├─ retrieve_vote_record()          find vote + verify receipt_hash
        ├─ prepare_unified_vote_display()  build display payload
        │       └─ process_vote_selections()
        │               └─ enrich_selection_data()  load Candidacy + User names
        │
        ▼
session("vote_display_data_{vote_id}") ← display payload stored
        │
        ▼
redirect → vote.show/{vote_id}
        │
        ▼
VoteShow.vue                       ← reads session data, renders candidates
```

---

## Public Methods

### `verify_to_show(Request $request)` — GET

Renders the verification code entry form.

```php
return Inertia::render('Vote/VoteShowVerify', [
    'user_name'             => $auth_user->name,
    'has_voted'             => true,
    'is_demo'               => false,
    'verification_code'     => $verification_code,   // null on first load
    'vote_data'             => $vote_data,           // null on first load
    'verification_failed'   => $verification_failed,
    'slug'                  => $voterSlug?->slug,
    'useSlugPath'           => $voterSlug !== null,
    'default_election_type' => 'real',
]);
```

Also handles an optional `?voting_code=` query param for direct verification without a separate POST.
The receipt_hash check runs inline — `Code` model is never consulted.

---

### `submit_code_to_view_vote(Request $request)` — POST

Validates → extracts → retrieves → enriches → stores in session → redirects to `vote.show`.

```php
$request->validate([
    'election_type' => 'nullable|in:demo,real',
    'voting_code'   => 'required|string|min:3|max:500',
]);
```

Passes `$vote_data['private_key']` (not the raw code) to `retrieve_vote_record()`.

---

## Private Methods

### `extract_vote_data_from_code(string $verification_code): array`

Splits on **first** underscore only (`explode('_', $code, 2)`).
Validates the UUID portion with a regex before returning.

```php
// Returns on success:
['success' => true, 'vote_id' => '<uuid>', 'private_key' => '<32hex>']

// Returns on failure:
['success' => false, 'message' => '...']
```

**Critical fix (2026-03-27):** Old code used `(int) end($parts)` which cast any UUID to `0`.
Now uses `explode(..., 2)` + UUID regex `/^[0-9a-f]{8}-...-[0-9a-f]{12}$/i`.

---

### `retrieve_vote_record(string $vote_id, ?string $private_key): array`

1. `Vote::withoutGlobalScopes()->find($vote_id)` — direct UUID lookup, bypasses `BelongsToTenant` scope
2. Recomputes `hash('sha256', $private_key . $vote->id . config('app.key'))`
3. Compares with `hash_equals()` (timing-safe, prevents timing attacks)

```php
// Returns on success:
['success' => true, 'vote' => $vote]

// Returns on failure:
['success' => false, 'message' => '...']
```

**Critical fix (2026-03-27):** Old code did `Vote::all()` + `Hash::check()` loop — replaced with direct lookup + receipt_hash.

---

### `enrich_selection_data(array $selection_data): array`

Loads `Candidacy` + `User` for each selected candidate_id stored in the vote JSON columns.

```php
$candidacy = Candidacy::withoutGlobalScopes()
    ->with('user')
    ->where('id', $candidacy_id)   // primary key = id, NOT candidacy_id
    ->first();
```

Returns per-selection:
```php
[
    'post_id'          => '...',
    'post_name'        => '...',
    'post_nepali_name' => '...',
    'no_vote'          => false,
    'candidates' => [
        [
            'candidacy_id'   => '...',
            'candidacy_name' => 'Full Name from User table',
            'proposer_name'  => '...',
            'supporter_name' => '...',
            'image_path_1'   => '...',
            'user_info'      => ['id', 'name', 'user_id', 'region'],
        ]
    ]
]
```

**Critical fix (2026-03-27):** Old code used `->where('candidacy_id', ...)` — column does not exist. Primary key is `id`.

If candidacy is not found in DB (deleted/archived), falls back to the `name` stored in the vote JSON.

---

### `prepare_unified_vote_display($vote, $auth_user, $verification_code, $election_type): array`

Builds the full display payload stored in session. Works for both `Vote` (real) and `DemoVote`.

```php
[
    'vote_id'                  => '...',
    'verification_code'        => '...',
    'verification_timestamp'   => '...',
    'verification_successful'  => true,
    'is_own_vote'              => true/false,
    'election_type'            => 'real',
    'voter_info'               => ['name', 'user_id', 'region'],
    'vote_info'                => ['voted_at', 'no_vote_option', 'voting_code_used'],
    'vote_selections'          => [...],   // enriched per-post selections
    'summary'                  => ['total_positions', 'positions_voted', 'candidates_selected', 'election_name'],
]
```

Note: Real votes do **not** have `user_id` on the `Vote` row. `voter_info` is populated from the authenticated `$auth_user`, not from the vote record.

---

### `markUserAsVoted($code, string $private_key): void`

Marks the `Code` record after a successful vote submission.

```php
$updateData = [
    'has_voted'                     => true,
    'can_vote_now'                  => false,
    'is_code_to_save_vote_usable'   => false,
    'code_to_save_vote_used_at'     => now(),
    'vote_completed_at'             => now(),
];

// SIMPLE mode (two_codes_system != 1): also lock the entry code
if (config('voting.two_codes_system') != 1) {
    $updateData['is_code_to_open_voting_form_usable'] = 0;
}
```

---

## Session Key Convention

```php
$session_id = "vote_display_data_" . $vote_id;
$request->session()->put($session_id, $display_data);
```

`VoteShow.vue` reads this via the `show()` controller method which passes it as an Inertia prop.

---

## Vote Anonymity — What Is and Is Not Stored

| Column | Table | Value |
|--------|-------|-------|
| `receipt_hash` | `votes` | `sha256(private_key + vote_id + app.key)` — cannot be reversed to identify voter |
| `user_id` | `votes` | **DOES NOT EXIST** |
| `voting_code` | `codes` | Previously erroneously stored here (removed — would create user ↔ vote link via `Code.user_id`) |

---

## Tests

File: `tests/Feature/VoteVerifyTest.php`

| Test | Purpose |
|------|---------|
| `verify_to_show_renders_inertia_page_for_authenticated_user` | GET renders correct component |
| `verify_to_show_redirects_guest_to_login` | Auth guard |
| `submit_code_with_valid_code_shows_vote_data` | Happy path |
| `submit_code_requires_voting_code_field` | Validation |
| `submit_code_rejects_code_without_underscore_separator` | Format validation |
| `submit_code_rejects_code_with_invalid_uuid_portion` | UUID validation |
| `submit_code_rejects_tampered_private_key` | receipt_hash mismatch |
| `submit_code_rejects_nonexistent_vote_id` | Unknown UUID |
| `extract_vote_data_parses_valid_verification_code` | Private method unit test |
| `extract_vote_data_fails_without_underscore` | Private method unit test |
| `extract_vote_data_fails_when_uuid_part_is_not_a_uuid` | Private method unit test |
| `retrieve_vote_record_returns_vote_for_valid_code` | Private method unit test |
| `retrieve_vote_record_fails_for_wrong_private_key` | Private method unit test |
| `retrieve_vote_record_fails_for_nonexistent_vote` | Private method unit test |
| `vote_record_has_no_user_id` | Anonymity guarantee |

Run:
```bash
php artisan test --filter VoteVerifyTest
```

---

## Common Mistakes to Avoid

| Mistake | Why it's wrong |
|---------|----------------|
| `Candidacy::where('candidacy_id', $id)` | Column is `id`, not `candidacy_id` |
| `Vote::all()` + loop to find vote | O(n), broken at scale |
| Storing `voting_code` on `Code` model | `Code` has `user_id` — breaks anonymity |
| `(int) end(explode('_', $code))` | Casts UUID to 0 |
| `Vote::find()` without `withoutGlobalScopes()` | `BelongsToTenant` scope filters by org, fails cross-tenant |
| `Hash::check()` for receipt_hash comparison | Wrong — use `hash_equals()` with raw SHA-256, not bcrypt |
