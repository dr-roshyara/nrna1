# Demo Election — Models & Database Tables

---

## Table Map

```
demo_posts               ← Election positions (national/regional)
demo_candidacies         ← Candidates per post
demo_codes               ← Voting code state per user per election
demo_voter_slugs         ← Time-limited voting URL per user
demo_voter_slug_steps    ← Step-by-step audit trail
demo_votes               ← Anonymous vote records
public_demo_sessions     ← Anonymous visitor state (public demo only)
```

---

## `demo_posts`

**Model:** `App\Models\DemoPost`  
**Purpose:** Represents a position to vote for (e.g. President, Regional Rep)

| Column | Type | Notes |
|--------|------|-------|
| `id` | UUID | Primary key |
| `organisation_id` | UUID | Tenant scope |
| `election_id` | UUID | FK → elections |
| `name` | string | English name |
| `nepali_name` | string nullable | Nepali translation |
| `is_national_wide` | boolean | `1` = shown to all voters, `0` = regional |
| `state_name` | string nullable | Region filter (e.g. `Bayern`) |
| `required_number` | integer | How many candidates voter must select |
| `position_order` | integer | Display order |

---

## `demo_candidacies`

**Model:** `App\Models\DemoCandidacy`  
**Purpose:** A candidate running for a specific post

| Column | Type | Notes |
|--------|------|-------|
| `id` | UUID | Primary key |
| `organisation_id` | UUID | Tenant scope |
| `post_id` | UUID | FK → demo_posts |
| `user_id` | UUID nullable | FK → users (optional link to real user) |
| `name` | string nullable | Display name |
| `description` | text nullable | Candidate bio |
| `position_order` | integer nullable | Display order |

---

## `demo_codes`

**Model:** `App\Models\DemoCode`  
**Purpose:** Tracks voting code state for a user going through the auth-based demo flow

| Column | Type | Notes |
|--------|------|-------|
| `id` | UUID | Primary key |
| `organisation_id` | UUID | Tenant scope |
| `user_id` | UUID | FK → users |
| `election_id` | UUID | FK → elections |
| `code_to_open_voting_form` | string | Step 1 verification code |
| `code_to_save_vote` | string nullable | Step 4 code (strict mode only) |
| `can_vote_now` | boolean | **Single source of truth** for code verification |
| `has_agreed_to_vote` | boolean | Step 2 complete |
| `has_voted` | boolean | Final vote submitted |
| `vote_submitted` | boolean | Step 4 complete |
| `voting_code` | string nullable | Hashed bridge to demo_votes |
| `code_to_open_voting_form_used_at` | timestamp | When code was entered |
| `session_name` | string nullable | Session tracking |
| `voting_slug` | string nullable | Audit trail |

### Key business rule

`can_vote_now` is the gate that controls access to Steps 2–4. Controllers read this field before rendering any page after Step 1.

---

## `demo_voter_slugs`

**Model:** `App\Models\DemoVoterSlug`  
**Purpose:** Time-limited, URL-embedded session for voting

| Column | Type | Notes |
|--------|------|-------|
| `id` | UUID | Primary key |
| `organisation_id` | UUID | Tenant scope |
| `user_id` | UUID | FK → users |
| `election_id` | UUID | FK → elections |
| `slug` | string unique | URL segment (e.g. `tbj_abc123...`) |
| `current_step` | integer | 1–5 |
| `step_meta` | JSON | Arbitrary step metadata |
| `is_active` | boolean | Active/expired/revoked |
| `can_vote_now` | boolean | Mirrors DemoCode state |
| `has_voted` | boolean | Vote submitted |
| `expires_at` | timestamp | Auto-marks inactive when past (boot hook) |
| `status` | string | `active` / `expired` |
| `step_1_completed_at` … `step_5_completed_at` | timestamp | Step audit |
| `step_1_ip` … `step_5_ip` | string | IP per step |

### Route key

```php
public function getRouteKeyName(): string
{
    return 'slug'; // /v/{vslug} binds on this column
}
```

### Expiry boot hook

When a `DemoVoterSlug` is retrieved from the database, the `booted()` method automatically marks it inactive if `expires_at` has passed:

```php
static::retrieved(function ($slug) {
    if ($slug->expires_at && now()->greaterThan($slug->expires_at) && $slug->is_active) {
        static::query()->where('id', $slug->id)->update(['is_active' => false, ...]);
    }
});
```

---

## `demo_voter_slug_steps`

**Model:** `App\Models\DemoVoterSlugStep`  
**Purpose:** Audit trail — one row per step per slug

| Column | Type | Notes |
|--------|------|-------|
| `id` | UUID | Primary key |
| `organisation_id` | UUID | Tenant scope |
| `voter_slug_id` | UUID | FK → demo_voter_slugs |
| `step` | integer | 1–5 |
| `ip_address` | string nullable | Visitor IP |
| `step_data` | JSON nullable | Arbitrary step data |
| `started_at` | timestamp nullable | |
| `completed_at` | timestamp nullable | |

---

## `demo_votes`

**Model:** `App\Models\DemoVote`  
**Purpose:** Anonymous vote record — **no user_id by design**

| Column | Type | Notes |
|--------|------|-------|
| `id` | UUID | Primary key |
| `organisation_id` | UUID | Tenant scope |
| `election_id` | UUID | FK → elections |
| `voting_code` | string unique | Hashed, links to demo_codes for verification |
| `candidate_selections` | JSON | Full selection map |
| `no_vote_option` | boolean | Whether voter chose "no vote" |
| `voted_at` | timestamp | |
| `voter_ip` | string nullable | IP for fraud detection |

**No `user_id` column.** The `voting_code` is hashed and cannot be reversed to identify the voter.

---

## `public_demo_sessions`

**Model:** `App\Models\PublicDemoSession`  
**Purpose:** Anonymous visitor state for the public demo flow (no login required)

| Column | Type | Notes |
|--------|------|-------|
| `id` | UUID | Primary key |
| `session_token` | string unique | Laravel `session()->getId()` |
| `election_id` | UUID | FK → elections |
| `display_code` | string | Code shown on Step 1 screen (e.g. `ABCD-5678`) |
| `current_step` | integer | 1–5 |
| `code_verified` | boolean | Step 1 complete |
| `agreed` | boolean | Step 2 complete |
| `candidate_selections` | JSON nullable | Voter's selections |
| `has_voted` | boolean | Final vote recorded |
| `voted_at` | timestamp nullable | |
| `expires_at` | timestamp | Session expiry (60 min from creation) |

### Route key

```php
public function getRouteKeyName(): string
{
    return 'session_token'; // /public-demo/{token} binds on this column
}
```

---

## Model Factory Locations

| Model | Factory |
|-------|---------|
| `DemoPost` | `database/factories/DemoPostFactory.php` |
| `DemoCandidacy` | `database/factories/DemoCandidacyFactory.php` |
| `DemoCode` | `database/factories/DemoCodeFactory.php` |
| `DemoVoterSlug` | `database/factories/DemoVoterSlugFactory.php` |
| `DemoVote` | `database/factories/DemoVoteFactory.php` |
| `PublicDemoSession` | `database/factories/PublicDemoSessionFactory.php` |
