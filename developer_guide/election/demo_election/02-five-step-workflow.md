# Demo Election — 5-Step Voting Workflow (Auth-Based)

This document covers the workflow for **registered users**. For the anonymous public demo, see [03-public-demo.md](03-public-demo.md).

---

## Flow Diagram

```
User clicks "Demo Versuchen"
        │
        ▼
ElectionManagementController::startDemo()
  - Resolves demo election via DemoElectionResolver
  - Creates DemoVoterSlug (forceNew = true → hard-deletes old slugs)
  - Stores election_id in session
  - Redirects to /v/{slug}/demo-code/create
        │
        ▼
┌───────────────────────────────────────────────────────────┐
│  STEP 1: Code Entry                                        │
│  GET  /v/{slug}/demo-code/create                          │
│  POST /v/{slug}/demo-code                                 │
│  Controller: DemoCodeController::create() / store()       │
│  Vue: Pages/Code/DemoCode/Create.vue                      │
│                                                           │
│  - Generates code_to_open_voting_form                     │
│  - Sends code by email (or shows on screen if no email)   │
│  - User enters code; can_vote_now flips to true           │
└───────────────────────┬───────────────────────────────────┘
                        │ success
                        ▼
┌───────────────────────────────────────────────────────────┐
│  STEP 2: Agreement                                         │
│  GET  /v/{slug}/demo-code/agreement                       │
│  POST /v/{slug}/demo-code/agreement                       │
│  Controller: DemoCodeController::showAgreement()          │
│             DemoCodeController::submitAgreement()         │
│  Vue: Pages/Code/DemoCode/Agreement.vue                   │
│                                                           │
│  - User reads & accepts voting terms                      │
│  - has_agreed_to_vote flips to true                       │
└───────────────────────┬───────────────────────────────────┘
                        │ success
                        ▼
┌───────────────────────────────────────────────────────────┐
│  STEP 3: Vote Selection                                    │
│  GET  /v/{slug}/demo-vote/create                          │
│  POST /v/{slug}/demo-vote/submit                          │
│  Controller: DemoVoteController::create()                  │
│             DemoVoteController::first_submission()         │
│  Vue: Pages/Vote/DemoVote/Create.vue                      │
│                                                           │
│  - Shows national posts (all voters)                      │
│  - Shows regional posts (filtered by user's region)       │
│  - User selects candidates per post                       │
│  - Vote stored in session (NOT database yet)              │
└───────────────────────┬───────────────────────────────────┘
                        │ success
                        ▼
┌───────────────────────────────────────────────────────────┐
│  STEP 4: Verification                                      │
│  GET  /v/{slug}/demo-vote/verify                          │
│  POST /v/{slug}/demo-vote/final                           │
│  Controller: DemoVoteController::verify()                  │
│             DemoVoteController::store()                    │
│  Vue: Pages/Vote/DemoVote/Verify.vue                      │
│                                                           │
│  - User reviews their selections                          │
│  - On confirm: vote is persisted to demo_votes table      │
│  - voting_code (hashed) links code record to vote record  │
│  - has_voted flips to true on DemoCode and DemoVoterSlug  │
└───────────────────────┬───────────────────────────────────┘
                        │ success
                        ▼
┌───────────────────────────────────────────────────────────┐
│  STEP 5: Thank You                                         │
│  GET  /v/{slug}/demo-vote/thank-you                       │
│  Controller: DemoVoteController::thankYou()                │
│  Vue: Pages/Vote/DemoVote/ThankYou.vue                    │
└───────────────────────────────────────────────────────────┘
```

---

## Middleware Stack

All slug-based demo routes pass through this middleware chain:

```php
['auth:sanctum', 'verified', SubstituteBindings::class,
 'voter.slug.verify',      // Checks slug exists + belongs to user
 'voter.slug.window',      // Checks slug not expired
 'voter.slug.consistency', // Checks slug matches election/org
 'voting.code.window',     // Checks voting time window
 'voter.step.order',       // Enforces step ordering (no skipping)
 'vote.eligibility',       // Checks user can vote
 'vote.organisation',      // Checks org context
 'election.demo'           // Confirms election type = demo
]
```

---

## DemoCode State Machine

`DemoCode` is the single source of truth for a user's progress through Steps 1–2.

```
INITIAL STATE
  can_vote_now = false
  has_agreed_to_vote = false
  has_voted = false
        │
        ▼ User enters correct code
  can_vote_now = true
  code_to_open_voting_form_used_at = now()
        │
        ▼ User accepts agreement
  has_agreed_to_vote = true
        │
        ▼ User confirms vote (Step 4 final submit)
  vote_submitted = true
  has_voted = true
```

**Key rule:** `can_vote_now` is the gating field. Controllers check this before allowing access to Steps 2–4.

---

## DemoVoterSlug State Machine

`DemoVoterSlug` tracks the user's URL-based session.

```
CREATED
  is_active = true
  current_step = 1
  expires_at = now() + 30 minutes
        │
        ▼ Each step completion
  current_step advances (1 → 2 → 3 → 4 → 5)
  step_N_completed_at = now()
        │
        ▼ Vote submitted
  has_voted = true
        │
  expires_at passes → is_active = false (auto via boot hook)
```

### Re-voting

For demo elections, `startDemo()` always passes `forceNew = true` to `VoterSlugService::getOrCreateSlug()`. This **hard-deletes** all existing slugs for the user/election pair before creating a fresh one, allowing unlimited re-voting.

```php
// ElectionManagementController::startDemo()
$slug = $this->slugService->getOrCreateSlug($authUser, $demoElection, true);
//                                                                     ^^^^
//                                                           forces fresh slug
```

---

## Code Display Fallback

When a user has no valid email address, the code is shown directly on the Step 1 page:

```php
// DemoCodeController::create()
'show_code_fallback' => !$hasValidEmail,
'verification_code'  => !$hasValidEmail ? $code->code_to_open_voting_form : null,
```

The Vue component (`Create.vue`) renders a highlighted code box when `show_code_fallback = true`.

---

## Regional Filtering

Step 3 splits posts into national and regional:

```php
// DemoVoteController::create()
$national_posts = DemoPost::withoutGlobalScopes()
    ->where('election_id', $election->id)
    ->where('is_national_wide', 1)
    ->get();

$regional_posts = DemoPost::withoutGlobalScopes()
    ->where('election_id', $election->id)
    ->where('is_national_wide', 0)
    ->where('state_name', $auth_user->region)  // filtered by voter's region
    ->get();
```

A voter with `region = 'Bayern'` only sees regional posts where `state_name = 'Bayern'`. National posts are always shown.
