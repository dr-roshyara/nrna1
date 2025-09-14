The voter moves through multiple pages . The pages are given sequencely as follwoiong: 

http://localhost:8000/code/create	
http://localhost:8000/vote/agreement
http://localhost:8000/vote/create
http://localhost:8000/vote/verify
http://localhost:8000/vote/verify_to_show

http://localhost:8000 is doamin . 

Since the voter moves through **multiple pages** that all share the same prefix (`/v/{slug}/...`), you want a 
in above case  http://localhost:8000/v/{slug}/...
**time-boxed (ephemeral) slug** that’s valid for \~30 minutes across *all* those routes. Here’s a clean Laravel-8 approach.

# 1) Create an *ephemeral* slug table

Keep your permanent `voters` table untouched. Issue short-lived slugs in a separate table and map them to a voter.

```php
// database/migrations/2025_09_13_000001_create_voter_slugs.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('voter_slugs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voter_id')->constrained()->cascadeOnDelete();
            $table->string('slug', 64)->unique();
            $table->timestamp('expires_at');       // fixed 30-min window
            $table->boolean('is_active')->default(true); // allow manual revoke
            $table->timestamps();

            $table->index(['voter_id', 'expires_at', 'is_active']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('voter_slugs');
    }
};
```

# 2) Model with relation + scopes

```php
// app/Models/VoterSlug.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoterSlug extends Model
{
    protected $fillable = ['voter_id', 'slug', 'expires_at', 'is_active'];
    protected $casts = ['expires_at' => 'datetime', 'is_active' => 'bool'];

    public function voter(): BelongsTo
    {
        return $this->belongsTo(Voter::class);
    }

    public function scopeValid($q)
    {
        return $q->where('is_active', true)->where('expires_at', '>', now());
    }
}
```

# 3) Route binding + middleware “gate”

Bind `{vslug}` to `VoterSlug` and guard *every* nested page with a middleware that enforces validity and makes the `Voter` available to controllers.

```php
// routes/web.php
use App\Models\VoterSlug;
use App\Domain\Election\Presentation\Http\Controllers\CodeController;
use App\Domain\Election\Presentation\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::bind('vslug', function (string $value) {
    return VoterSlug::with('voter')->where('slug', $value)->first();
});

Route::prefix('v/{vslug}')->middleware('voter.slug.window')->group(function () {
    Route::get('code/create', [CodeController::class, 'create'])->name('voter.code.create');
    Route::post('code',       [CodeController::class, 'store'])->name('voter.code.store');

    Route::get('vote/create', [VoteController::class, 'create'])->name('voter.vote.create');
    Route::post('vote',       [VoteController::class, 'store'])->name('voter.vote.store');
});
```

Middleware:

```php
// app/Http/Middleware/EnsureVoterSlugWindow.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\VoterSlug;

class EnsureVoterSlugWindow
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var VoterSlug|null $vslug */
        $vslug = $request->route('vslug');

        if (!$vslug || !$vslug->is_active || $vslug->expires_at->isPast()) {
            abort(403, 'Link expired or invalid.');
        }

        // OPTIONAL: sliding window—refresh expiry on every hit:
        // $vslug->update(['expires_at' => now()->addMinutes(30)]);

        // Make the voter easily accessible to controllers/views
        $request->attributes->set('voter', $vslug->voter);

        return $next($request);
    }
}
```

Register it:

```php
// app/Http/Kernel.php
protected $routeMiddleware = [
    // ...
    'voter.slug.window' => \App\Http\Middleware\EnsureVoterSlugWindow::class,
];
```

# 4) Controllers: use the attached voter

Keep your folder structure. Example:

```php
// app/Domain/Election/Presentation/Http/Controllers/CodeController.php
namespace App\Domain\Election\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VoterSlug;

class CodeController extends Controller
{
    public function create(Request $request, VoterSlug $vslug)
    {
        $voter = $request->attributes->get('voter'); // from middleware
        return inertia('Domain/Election/code/Create', [
            'voter' => $voter->only('id'),     // expose what you need
            'slug'  => $vslug->slug,
        ]);
    }

    public function store(Request $request, VoterSlug $vslug)
    {
        $voter = $request->attributes->get('voter');
        // ... handle form for this voter ...
        return redirect()->route('voter.vote.create', ['vslug' => $vslug->slug]);
    }
}
```

# 5) Issuing a 30-minute slug

Generate, store, and send the **same slug** prefix used across all routes.

```php
// e.g., in a service class
use App\Models\VoterSlug;

function issueVoterSlug(\App\Models\Voter $voter): VoterSlug
{
    $slug = rtrim(strtr(base64_encode(random_bytes(18)), '+/', '-_'), '='); // short, URL-safe
    return VoterSlug::create([
        'voter_id'   => $voter->id,
        'slug'       => $slug,
        'expires_at' => now()->addMinutes(30),
        'is_active'  => true,
    ]);
}

// Build links:
$vs = issueVoterSlug($voter);
$link = route('voter.code.create', ['vslug' => $vs->slug]);
// Then the flow continues with the SAME /v/{slug}/... prefix.
```

# 6) Revocation & cleanup (nice to have)

* **Manual revoke:** `VoterSlug::whereSlug($slug)->update(['is_active' => false]);`
* **Auto cleanup:** schedule a daily job to delete expired slugs:

  ```php
  // app/Console/Commands/PurgeExpiredVoterSlugs.php
  VoterSlug::where('expires_at', '<', now())->delete();
  ```

# 7) Security notes

* Slug is **opaque** (random), not guessable.
* Use **fixed window** (30 min from issuance). If you prefer **sliding**, uncomment the refresh line in the middleware.
* If you need **per-step** constraints (e.g., can’t skip to `/vote/create` before finishing code), add a lightweight state machine on `voter_slugs` (e.g., `stage: 'code'|'vote'|...`) and enforce it in the middleware or controllers.

---

This gives you a **single slug prefix** that stays valid for 30 minutes across *all* pages in the voter journey, without query tokens and without touching your permanent `voters.slug`.

Great question. The safest, auditable way is to **re-issue the voter’s session** (new ephemeral slug) and **invalidate anything in progress**, without ever deleting history. Think of it as a small state machine + reset button.

Here’s a solid pattern you can drop into your current setup with `voter_slugs`.

# Model the state clearly

* **voters**

  * `id`, `has_cast_ballot` (bool), `ballot_id` (nullable), …
* **voter\_slugs** (your ephemeral link)

  * `id`, `voter_id`, `slug`, `expires_at`, `is_active` (bool), `stage` (enum: `code`, `vote`, `done`), timestamps
* **ballots**

  * `id`, `voter_id`, `status` (enum: `cast`, `voided`), `void_reason` (nullable), payload, timestamps
* **voter\_resets** (audit trail)

  * `id`, `voter_id`, `performed_by` (admin id), `reason`, `previous_slug_id`, `new_slug_id`, timestamps

> Don’t hard-delete anything. Mark old slug inactive and (if needed) mark the old ballot as `voided` with a reason.

# Reset policy (what happens when you “allow to vote from start”)

There are two typical scenarios:

1. **Voter didn’t finish (no ballot cast):**

   * Revoke current active slug(s).
   * Issue a fresh slug (30-min).
   * Reset transient state (e.g., `stage` back to `code`).
   * Audit the reset.

2. **Voter cast a ballot but there was a technical failure or you need to let them re-vote:**

   * Require an **admin action with a reason**.
   * Mark the prior ballot `voided` with that reason.
   * Clear `has_cast_ballot` and `ballot_id` on the voter.
   * Revoke current slug(s) and issue a new slug.
   * Audit the reset.

This guarantees **no double counting** and keeps a clear trail.

# Middleware guard (unchanged idea)

Your existing `EnsureVoterSlugWindow` should also check:

* `is_active == true`
* `expires_at > now()`
* optional: `voter.has_cast_ballot == false` unless you’re on routes that *show results/receipt only*

If someone clicks an **old** or **revoked** link, they get a 403.

# Service method: one click reset

```php
// app/Domain/Election/Application/VoterResetService.php
namespace App\Domain\Election\Application;

use App\Models\{Voter, VoterSlug, Ballot};
use App\Models\VoterReset; // your audit model
use Illuminate\Support\Facades\DB;

class VoterResetService
{
    /**
     * Reset a voter's journey and optionally void prior ballot.
     *
     * @return VoterSlug the newly issued slug
     */
    public function resetToStart(Voter $voter, int $adminUserId, string $reason = null): VoterSlug
    {
        return DB::transaction(function () use ($voter, $adminUserId, $reason) {

            // 1) If they already cast a ballot, void it (admin-initiated reset)
            if ($voter->has_cast_ballot && $voter->ballot_id) {
                /** @var Ballot $ballot */
                $ballot = Ballot::lockForUpdate()->find($voter->ballot_id);
                if ($ballot && $ballot->status === 'cast') {
                    $ballot->update([
                        'status' => 'voided',
                        'void_reason' => $reason ?: 'Reset by admin due to technical issue',
                    ]);
                }
                // clear voter flags
                $voter->update([
                    'has_cast_ballot' => false,
                    'ballot_id'       => null,
                ]);
            } else {
                // lock voter for consistency even if no ballot
                $voter->lockForUpdate();
            }

            // 2) Revoke all active slugs for this voter
            VoterSlug::where('voter_id', $voter->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            // 3) Issue new 30-min slug
            $slug = rtrim(strtr(base64_encode(random_bytes(18)), '+/', '-_'), '=');

            /** @var VoterSlug $newSlug */
            $newSlug = VoterSlug::create([
                'voter_id'   => $voter->id,
                'slug'       => $slug,
                'expires_at' => now()->addMinutes(30),
                'is_active'  => true,
                'stage'      => 'code', // start from the beginning
            ]);

            // 4) Audit
            VoterReset::create([
                'voter_id'        => $voter->id,
                'performed_by'    => $adminUserId,
                'reason'          => $reason,
                'previous_slug_id'=> null, // optionally store last active slug id if you capture it
                'new_slug_id'     => $newSlug->id,
            ]);

            return $newSlug;
        });
    }
}
```

> Wire this to an **admin-only** controller action like `POST /admin/voters/{voter}/reset`, and always require a `reason` if a ballot was already cast.

# Controller snippet (admin action)

```php
public function reset(\App\Models\Voter $voter, \Illuminate\Http\Request $request,
                      \App\Domain\Election\Application\VoterResetService $svc)
{
    $this->authorize('resetVoter', $voter); // policy
    $reason = $request->string('reason')->toString() ?: 'Technical issue';

    $newSlug = $svc->resetToStart($voter, $request->user()->id, $reason);

    return back()->with('status', 'Voter reset. New link: ' .
        route('voter.code.create', ['vslug' => $newSlug->slug]));
}
```

# A few guardrails you’ll appreciate

* **Uniqueness:** Only one active slug per voter. Enforce in code by revoking before issuing.
* **No race conditions:** Use DB transactions and `lockForUpdate()` when touching voter/ballot.
* **Role/Policy checks:** Only privileged roles can void ballots or reset a voter.
* **Clear UX copy:** When a link is expired/revoked, show a friendly page (“Your link expired. Please request a new link.”) instead of a bare 403.
* **Optional:** If you want a “resume window,” store `stage` in `voter_slugs` and block jumping ahead (so “from start” really starts at `/code/create`).

With this, “allow to vote from start” = **void (if needed) → revoke → reissue → audit**. Clean, safe, and production-ready.
Yep—make the journey **stateful** and gate each page by the voter’s current stage. Easiest is a tiny state machine tied to your ephemeral slug.

# Core idea

* Keep one ephemeral slug: `/v/{vslug}/...`
* Store **current\_step** (and optionally a per-step status) server-side.
* A middleware blocks any route whose step is **ahead** of `current_step`.
* Controllers **advance** the step only after the previous step’s *POST* succeeds.

---

## 1) Data model (minimal)

Add step tracking to your ephemeral slug (or use a separate `voter_journeys` table if you prefer).

```php
// database/migrations/xxxx_xx_xx_add_steps_to_voter_slugs.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('voter_slugs', function (Blueprint $table) {
            $table->unsignedTinyInteger('current_step')->default(1)->after('is_active');
            $table->json('step_meta')->nullable()->after('current_step'); // optional
        });
    }
    public function down(): void {
        Schema::table('voter_slugs', function (Blueprint $table) {
            $table->dropColumn(['current_step','step_meta']);
        });
    }
};
```

> If you want a full audit trail, create `voter_journey_events` (`voter_slug_id`, `step`, `event`, `meta`, `created_at`).

---

## 2) Define your canonical step order

Centralize once; reuse everywhere.

```php
// config/election_steps.php
return [
    // step => route name for the GET page
    1 => 'voter.code.create',   // enter code
    2 => 'voter.code.verify',   // (optional) verification screen
    3 => 'voter.vote.create',   // ballot
    4 => 'voter.vote.review',   // review
    5 => 'voter.vote.submit',   // final receipt page (GET)
];
```

> Add any steps you actually use; names must match your route names.

---

## 3) Gatekeeper middleware

Blocks skipping ahead; auto-redirects to the right step.

```php
// app/Http/Middleware/EnsureVoterStepOrder.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\VoterSlug;

class EnsureVoterStepOrder
{
    public function handle(Request $request, Closure $next)
    {
        /** @var VoterSlug $vslug */
        $vslug = $request->route('vslug'); // from your route binding
        if (!$vslug || !$vslug->is_active || $vslug->expires_at->isPast()) {
            abort(403, 'Link expired or invalid.');
        }

        $routeName = optional($request->route())->getName();
        $map = config('election_steps');
        $targetStep = array_search($routeName, $map, true);

        // Non-step routes (e.g., POST actions) pass through
        if ($targetStep === false) {
            return $next($request);
        }

        // If user tries to open FUTURE step, send them back to current
        if ($targetStep > $vslug->current_step) {
            $currentRoute = $map[$vslug->current_step] ?? reset($map);
            return redirect()->route($currentRoute, ['vslug' => $vslug->slug]);
        }

        return $next($request);
    }
}
```

Register it:

```php
// app/Http/Kernel.php
protected $routeMiddleware = [
    // ...
    'voter.flow' => \App\Http\Middleware\EnsureVoterStepOrder::class,
];
```

---

## 4) Routes with names (GET pages + POST actions)

Use the same slug prefix and attach the middleware to the whole group.

```php
// routes/web.php
use App\Domain\Election\Presentation\Http\Controllers\CodeController;
use App\Domain\Election\Presentation\Http\Controllers\VoteController;

Route::prefix('v/{vslug}')->middleware(['voter.slug.window','voter.flow'])->group(function () {
    // STEP 1
    Route::get('code/create',  [CodeController::class, 'create'])->name('voter.code.create');
    Route::post('code',        [CodeController::class, 'store'])->name('voter.code.store');

    // STEP 2 (optional)
    Route::get('code/verify',  [CodeController::class, 'verify'])->name('voter.code.verify');

    // STEP 3
    Route::get('vote/create',  [VoteController::class, 'create'])->name('voter.vote.create');
    Route::post('vote',        [VoteController::class, 'store'])->name('voter.vote.store');

    // STEP 4
    Route::get('vote/review',  [VoteController::class, 'review'])->name('voter.vote.review');

    // STEP 5 (final receipt page)
    Route::get('vote/submit',  [VoteController::class, 'submitted'])->name('voter.vote.submit');
});

// Nice-to-have: resume link
Route::get('v/{vslug}/resume', function (\Illuminate\Http\Request $req) {
    $vslug = $req->route('vslug');
    $map = config('election_steps');
    return redirect()->route($map[$vslug->current_step], ['vslug' => $vslug->slug]);
})->name('voter.resume')->middleware(['voter.slug.window']);
```

---

## 5) Progress service (advance only on successful POST)

Advance exactly one step at a time, transactionally. Prevent double advances on refresh.

```php
// app/Domain/Election/Application/VoterProgressService.php
namespace App\Domain\Election\Application;

use App\Models\VoterSlug;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VoterProgressService
{
    public function advanceFrom(VoterSlug $vslug, string $fromRoute): void
    {
        $map = config('election_steps');
        $fromStep = array_search($fromRoute, $map, true);
        if ($fromStep === false) return;

        DB::transaction(function () use ($vslug, $fromStep, $map) {
            $vslug->refresh(); // latest values

            // Only advance if we're exactly at fromStep
            if ((int)$vslug->current_step !== (int)$fromStep) {
                // Ignore (idempotent), or throw if you want strictness:
                // throw ValidationException::withMessages(['flow' => 'Invalid step transition']);
                return;
            }

            $nextStep = $fromStep + 1;
            if (!isset($map[$nextStep])) {
                // end of flow
                return;
            }

            $vslug->update(['current_step' => $nextStep]);
        });
    }
}
```

---

## 6) Controllers: call `advanceFrom()` only after success

Example: code submission completes Step 1 → advance to Step 2.

```php
// app/Domain/Election/Presentation/Http/Controllers/CodeController.php
namespace App\Domain\Election\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VoterSlug;
use App\Domain\Election\Application\VoterProgressService;

class CodeController extends Controller
{
    public function create(VoterSlug $vslug)
    {
        return inertia('Domain/Election/code/Create', ['slug' => $vslug->slug]);
    }

    public function store(Request $request, VoterSlug $vslug, VoterProgressService $progress)
    {
        // 1) validate + verify code
        $data = $request->validate(['code' => 'required|string']);
        // ... your verification logic; on failure return back with errors ...

        // 2) mark any per-step state if you want
        // $vslug->update(['step_meta' => array_merge($vslug->step_meta ?? [], ['code_verified' => true])]);

        // 3) advance from step 1
        $progress->advanceFrom($vslug, 'voter.code.create');

        // 4) redirect to the *canonical* next GET route based on the current_step
        $map = config('election_steps');
        $nextRoute = $map[$vslug->fresh()->current_step];
        return redirect()->route($nextRoute, ['vslug' => $vslug->slug]);
    }

    public function verify(VoterSlug $vslug)
    {
        return inertia('Domain/Election/code/Verify', ['slug' => $vslug->slug]);
    }
}
```

And later, after ballot POST succeeds, advance from `voter.vote.create` to `voter.vote.review`, etc.

---

## 7) Nice extras you might want

* **Strict back-navigation:** allow visiting **current or previous** steps, but never > current. (Middleware already does this.)
* **No bookmarking issues:** Because the guard uses server-side `current_step`, bookmarks to step 3 will auto-redirect back to step 1/2 if needed.
* **“One-step only” rule:** Only advance in POST handlers that *actually complete* the step.
* **Resume link:** `/v/{slug}/resume` jumps to where they left off.
* **Stage-specific timeouts:** store `step_started_at` in `step_meta`; auto-expire if a single step takes too long.
* **Audit:** append to `voter_journey_events` on every advance (optional but recommended).

---

### TL;DR

* Keep the slug constant across the journey.
* Track `current_step` server-side.
* A middleware maps route → step and blocks future steps.
* POST handlers advance the state after success.

This gives you a clear **trail of links** where the 3rd page can only be visited after the 2nd, etc., with clean redirects and no fragile client logic.
