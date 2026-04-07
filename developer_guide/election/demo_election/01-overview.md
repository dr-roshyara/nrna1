# Demo Election — Architecture Overview

---

## Purpose

The demo election system lets users and anonymous visitors experience the full voting workflow without affecting real election data. It is used for:

- **Customer onboarding** — potential customers try the product before buying
- **Organisation staff training** — staff test their setup before going live
- **Automated testing** — the test suite runs against demo tables

---

## Two Entry Points

### 1. Auth-based Demo (Registered Users)

```
User logs in
    → Clicks "Demo Versuchen" on /dashboard
    → GET /election/demo/start
    → ElectionManagementController::startDemo()
    → Creates DemoVoterSlug (user_id set)
    → Redirects to /v/{slug}/demo-code/create
    → Full 5-step flow
```

**Controller:** `App\Http\Controllers\Election\ElectionManagementController@startDemo`  
**Route:** `election.demo.start`

### 2. Public Demo (Anonymous Visitors)

```
Visitor on landing page (/)
    → Clicks "Try Demo" in CTA section
    → GET /public-demo/start
    → PublicDemoController::start()
    → Creates PublicDemoSession (session_id only, no user_id)
    → Redirects to /public-demo/{token}/code
    → Full 5-step flow
```

**Controller:** `App\Http\Controllers\Demo\PublicDemoController@start`  
**Route:** `public-demo.start`

---

## Data Isolation

All demo data is stored in separate tables — never mixed with real election data.

```
REAL ELECTIONS          DEMO ELECTIONS
──────────────          ──────────────
posts                   demo_posts
candidacies             demo_candidacies
codes                   demo_codes
voter_slugs             demo_voter_slugs
votes                   demo_votes
voter_slug_steps        demo_voter_slug_steps
                        public_demo_sessions  ← anonymous visitors only
```

---

## Two-Mode Multi-Tenancy

Every demo election record carries an `organisation_id`:

| Mode | organisation_id | Visible to |
|------|----------------|-----------|
| Platform-wide | `NULL` | All users (fallback) |
| Org-specific | `{uuid}` | Only that organisation |

### Priority resolution

`DemoElectionResolver` applies this priority when finding the right demo election for a user:

1. Org-specific demo for the user's `organisation_id` (auto-creates if missing)
2. Platform-wide demo (`organisation_id = NULL`)

For public/anonymous visitors, `getPublicDemoElection()` uses:

1. Default platform organisation's demo election (auto-creates if missing)
2. Any platform-wide demo (`organisation_id = NULL`)

---

## Vote Anonymity

The `demo_votes` table deliberately has **no `user_id` column**:

```sql
CREATE TABLE demo_votes (
    id          UUID PRIMARY KEY,
    organisation_id UUID,
    election_id     UUID,
    voting_code     VARCHAR UNIQUE,   -- hashed, cannot be reversed
    candidate_selections JSON,
    no_vote_option  BOOLEAN,
    voted_at        TIMESTAMP,
    voter_ip        VARCHAR
    -- NO user_id column intentionally
);
```

A `voting_code` bridges the `demo_codes` record to the `demo_votes` record for verification purposes — but it is hashed, so the link cannot be reversed to identify the voter.

---

## Election Type Flag

Elections are distinguished by the `type` column:

```php
$election->type === 'demo'  // demo election
$election->type === 'real'  // real election
```

This determines which set of models (`DemoVoterSlug` vs `VoterSlug`, `DemoCode` vs `Code`, etc.) is used throughout the workflow.
