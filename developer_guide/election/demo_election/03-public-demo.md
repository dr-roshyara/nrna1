# Public Demo — Anonymous Voting Without Login

**Implemented:** 2026-04-07  
**Feature branch:** `multitenancy`

---

## What It Is

Any visitor to the public landing page can click **"Try Demo"** and experience the complete 5-step voting workflow **without registering or logging in**.

This eliminates the registration friction that previously blocked potential customers from evaluating the platform.

---

## Why Not the Dummy User Approach

The initial architecture document proposed a shared dummy user (`demo@publicdigit.com`) for all anonymous visitors. This was rejected because:

- `VoterSlugService` deletes all existing slugs for a user when `forceNew = true`
- Two simultaneous anonymous visitors sharing one dummy user would destroy each other's voter slugs
- Analytics would be polluted with fake user records

**The solution:** A dedicated `public_demo_sessions` table keyed by the Laravel session ID. Each anonymous visitor gets their own isolated record with no `user_id` anywhere.

---

## Architecture

```
Anonymous visitor
        │
        ▼
GET /public-demo/start
        │  PublicDemoController::start()
        │  1. Resolves demo election via DemoElectionResolver::getPublicDemoElection()
        │  2. PublicDemoSession::firstOrCreate(['session_token' => session()->getId()])
        │  3. Generates a display_code (e.g. "ABCD-5678")
        │
        ▼
GET /public-demo/{token}/code
        │  PublicDemoController::codeShow()
        │  Inertia renders Code/DemoCode/Create
        │  Props: verification_code = display_code, show_code_fallback = true
        │  The code is shown visually on screen
        │
        ▼ User reads and types the code
POST /public-demo/{token}/code
        │  PublicDemoController::codeVerify()
        │  Compares input vs session.display_code
        │  On match: code_verified = true, current_step = 2
        │
        ▼
GET/POST /public-demo/{token}/agreement
        │  PublicDemoController::agreementShow/Submit()
        │  agreed = true, current_step = 3
        │
        ▼
GET/POST /public-demo/{token}/vote
        │  PublicDemoController::voteShow/Submit()
        │  Loads DemoPost + DemoCandidacy via withoutGlobalScopes()
        │  Saves candidate_selections (JSON) to PublicDemoSession
        │  current_step = 4
        │
        ▼
GET/POST /public-demo/{token}/verify
        │  PublicDemoController::verifyShow/Confirm()
        │  Displays selections for review
        │  On confirm: has_voted = true, voted_at = now(), current_step = 5
        │
        ▼
GET /public-demo/{token}/thank-you
        PublicDemoController::thankYou()
```

---

## PublicDemoSession Model

**File:** `app/Models/PublicDemoSession.php`  
**Table:** `public_demo_sessions`

```php
Schema::create('public_demo_sessions', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('session_token', 255)->unique(); // Laravel session ID
    $table->uuid('election_id');
    $table->string('display_code', 255);            // Code shown on Step 1 screen
    $table->integer('current_step')->default(1);    // 1–5
    $table->boolean('code_verified')->default(false);
    $table->boolean('agreed')->default(false);
    $table->json('candidate_selections')->nullable();
    $table->boolean('has_voted')->default(false);
    $table->timestamp('voted_at')->nullable();
    $table->timestamp('expires_at');                // 60 minutes from creation
    $table->timestamps();
});
```

### Key design decisions

| Decision | Reason |
|----------|--------|
| No `user_id` | Anonymous visitors have no account |
| `session_token` = `session()->getId()` | Each browser is uniquely identified |
| `display_code` stored in DB | Code must survive page refreshes |
| `candidate_selections` JSON | Stores the full selection map |
| `expires_at` = 60 min | Demo sessions are temporary |

---

## Controller

**File:** `app/Http/Controllers/Demo/PublicDemoController.php`

### Entry point: `start()`

```php
public function start(): RedirectResponse
{
    $election = $this->resolver->getPublicDemoElection();

    $demoSession = PublicDemoSession::firstOrCreate(
        ['session_token' => session()->getId()],
        [
            'election_id' => $election->id,
            'display_code' => $this->generateDisplayCode(), // e.g. "ABCD-1234"
            'current_step' => 1,
            'expires_at' => now()->addMinutes(60),
        ]
    );

    // Restart if session was completed or expired
    if ($demoSession->has_voted || $demoSession->isExpired()) {
        $demoSession->delete();
        $demoSession = PublicDemoSession::create([...]);
    }

    return redirect()->route('public-demo.code.show', $demoSession->session_token);
}
```

### Step enforcement: `requireStep()`

Every step method (except `start` and `thankYou`) calls this guard:

```php
private function requireStep(PublicDemoSession $session, int $step): void
{
    if ($session->current_step < $step) {
        abort(403, 'Please complete the previous steps first.');
    }
}
```

This prevents visitors from jumping directly to `/vote` or `/verify` by URL manipulation.

### Code display: `codeShow()`

```php
return Inertia::render('Code/DemoCode/Create', [
    'verification_code' => $publicDemoSession->display_code,
    'show_code_fallback' => true,   // always show code on screen for public demo
    'is_public_demo'    => true,
    // ... other props
]);
```

The existing `Code/DemoCode/Create.vue` already supports displaying the code when `show_code_fallback = true`.

---

## Route Definition

**File:** `routes/election/electionRoutes.php`

```php
Route::prefix('public-demo')->name('public-demo.')->group(function () {
    Route::get('/start',                      [PublicDemoController::class, 'start'])            ->name('start');
    Route::get('/{publicDemoSession}/code',   [PublicDemoController::class, 'codeShow'])         ->name('code.show');
    Route::post('/{publicDemoSession}/code',  [PublicDemoController::class, 'codeVerify'])       ->name('code.verify');
    Route::get('/{publicDemoSession}/agreement',  [PublicDemoController::class, 'agreementShow'])  ->name('agreement.show');
    Route::post('/{publicDemoSession}/agreement', [PublicDemoController::class, 'agreementSubmit'])->name('agreement.submit');
    Route::get('/{publicDemoSession}/vote',   [PublicDemoController::class, 'voteShow'])         ->name('vote.show');
    Route::post('/{publicDemoSession}/vote',  [PublicDemoController::class, 'voteSubmit'])       ->name('vote.submit');
    Route::get('/{publicDemoSession}/verify', [PublicDemoController::class, 'verifyShow'])       ->name('verify.show');
    Route::post('/{publicDemoSession}/verify',[PublicDemoController::class, 'verifyConfirm'])    ->name('verify.confirm');
    Route::get('/{publicDemoSession}/thank-you', [PublicDemoController::class, 'thankYou'])     ->name('thankyou');
});
```

**No auth middleware** — these routes are intentionally public.

The `{publicDemoSession}` route model binding resolves via `getRouteKeyName()` which returns `session_token`.

---

## Election Resolution: `getPublicDemoElection()`

**File:** `app/Services/DemoElectionResolver.php`

```php
public function getPublicDemoElection(): ?Election
{
    // Priority 1: Default platform organisation demo (auto-creates if missing)
    $platformOrg = Organisation::getDefaultPlatform();

    if ($platformOrg) {
        $orgDemo = Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('organisation_id', $platformOrg->id)
            ->first();

        if (!$orgDemo) {
            $orgDemo = app(DemoElectionCreationService::class)
                ->createOrganisationDemoElection($platformOrg->id, $platformOrg);
        }

        if ($orgDemo) return $orgDemo;
    }

    // Priority 2: Platform-wide demo (organisation_id = null)
    return Election::withoutGlobalScopes()
        ->where('type', 'demo')
        ->whereNull('organisation_id')
        ->first();
}
```

The demo election is **auto-created** on first public demo access if it doesn't exist. This means no manual seeding is required.

---

## Frontend: "Try Demo" Button

**File:** `resources/js/Components/Welcome/CTASection.vue`

```vue
<a
  :href="route('public-demo.start')"
  class="... border-green-600 text-green-700 ..."
  aria-label="Try the demo election — no login required"
>
  <span aria-hidden="true">🎪</span>
  {{ $t('pages.welcome.cta_section.btn_try_demo', 'Try Demo') }}
</a>
```

This button is visible to **logged-out visitors only** (it is inside the `v-if="!loggedIn"` block of `Welcome.vue`).

---

## Isolation Guarantee

Two visitors using the demo simultaneously cannot interfere with each other:

```
Visitor A                       Visitor B
─────────────────────────────────────────────
session_token = "abc..."        session_token = "xyz..."
public_demo_sessions row #1     public_demo_sessions row #2
current_step = 3 (voting)       current_step = 1 (code entry)
candidate_selections = [...]    candidate_selections = null

→ Completely independent rows, no shared state
```

---

## Session Lifecycle

| Event | What happens |
|-------|-------------|
| First visit to `/public-demo/start` | `PublicDemoSession` created, `expires_at = now() + 60min` |
| Revisit within 60 minutes | `firstOrCreate` returns existing session — no new row |
| Session expires | `isExpired()` returns true → `start()` deletes and recreates |
| Vote completed | `has_voted = true` → `start()` deletes and recreates on next visit |
| Browser closed | Session row remains until expiry (cleaned up by scheduler if added) |

---

## Adding a Cleanup Command (Recommended)

For production, add a scheduled command to purge expired public demo sessions:

```php
// app/Console/Commands/PurgePublicDemoSessions.php
PublicDemoSession::where('expires_at', '<', now()->subHours(24))->delete();

// app/Console/Kernel.php
$schedule->command('demo:purge-public-sessions')->daily();
```

This is not yet implemented but recommended before going to production.
