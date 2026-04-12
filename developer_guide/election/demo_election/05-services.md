# Demo Election — Services

---

## DemoElectionResolver

**File:** `app/Services/DemoElectionResolver.php`

Resolves the correct demo election for a given context. Used by both the auth-based flow and the public demo flow.

### Methods

#### `getDemoElectionForUser(User $user): ?Election`

Returns the appropriate demo election for a registered user.

**Priority:**
1. Org-specific demo (`organisation_id = $user->organisation_id`) — **auto-creates if missing**
2. Platform-wide demo (`organisation_id = NULL`)

```php
$election = app(DemoElectionResolver::class)->getDemoElectionForUser(auth()->user());
```

#### `getPublicDemoElection(): ?Election`

Returns the demo election for anonymous visitors. Used by `PublicDemoController`.

**Priority:**
1. Default platform organisation's demo — **auto-creates if missing**
2. Platform-wide demo (`organisation_id = NULL`)

```php
$election = app(DemoElectionResolver::class)->getPublicDemoElection();
```

#### `isElectionValidForUser(User $user, Election $election): bool`

Validates that an election is appropriate for a specific user. Returns `false` if:
- Election type is not `demo`
- User has an `organisation_id` but the election belongs to a different organisation

---

## DemoElectionCreationService

**File:** `app/Services/DemoElectionCreationService.php`

Creates a complete demo election with pre-populated posts and candidates. Called automatically by `DemoElectionResolver` when no demo election exists.

### `createOrganisationDemoElection(string $organisationId, Organisation $organisation): Election`

Creates:
- 1 demo election (type = `demo`, status = `active`)
- 2 national posts: **President** (select 1), **Vice President** (select 1)
- 2 regional posts for Europe: **State Representative** (select 2), **District Representative** (select 1)
- Candidates for each post (3 for State Rep, 2 for District Rep)

```
Election: "Demo Election"
├── National Posts
│   ├── President (required_number = 1)
│   │   ├── Alice Johnson
│   │   ├── Bob Smith
│   │   └── Carol Williams
│   └── Vice President (required_number = 1)
│       ├── Daniel Miller
│       ├── Eva Martinez
│       └── Frank Wilson
└── Regional Posts (Europe)
    ├── State Representative (required_number = 2)
    │   ├── Hans Mueller
    │   ├── Anna Schmidt
    │   └── Klaus Weber
    └── District Representative (required_number = 1)
        ├── Maria Fischer
        └── Thomas Wagner
```

**Auto-creation** means you never need to manually seed demo data. The first user to trigger the demo for a given organisation automatically gets a complete demo election.

---

## VoterSlugService

**File:** `app/Services/VoterSlugService.php`

Manages voter slug lifecycle for **registered users** in both real and demo elections.

### Key methods

#### `getOrCreateSlug(User $user, Election $election, bool $forceNew = false)`

Main entry point for slug management.

- `forceNew = false` (default): Returns existing active slug or creates new one
- `forceNew = true`: **Hard-deletes** all existing slugs for this user/election and creates fresh (used for demo re-voting)

```php
// Always fresh for demo
$slug = $this->slugService->getOrCreateSlug($user, $demoElection, true);
```

#### `generateSlugForUser(User $user, ?string $electionId = null): VoterSlug`

Creates a new `VoterSlug` (for real elections). Uses `VoterSlug` table.

#### `createNewSlug(User $user, Election $election, string $model)`

Internal method. Creates a slug in either `voter_slugs` or `demo_voter_slugs` depending on `$model`. Includes:
- Retry logic for read-replica lag (Digital Ocean)
- Verification that the slug was written before returning

#### `validateSlugOwnership($slug, User $user, Election $election): bool`

Security check. Throws `AccessDeniedHttpException` if:
- Slug `user_id` does not match authenticated user
- Slug `election_id` does not match current election

> **Note:** This check is bypassed for `PublicDemoSession` records — the public demo uses its own session-based ownership model.

---

## VoterProgressService

**File:** `app/Services/VoterProgressService.php`

Tracks which step a voter is on and whether they can proceed.

Used inside `DemoVoteController` to determine the voter's current position and prevent skipping.

---

## VotingServiceFactory

**File:** `app/Services/VotingServiceFactory.php`

Factory that returns the correct service implementation based on election type (`demo` vs `real`). Ensures demo controllers use demo models and real controllers use real models.
