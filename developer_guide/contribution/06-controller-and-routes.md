# 06 — Controller & Routes

## ContributionController

**File:** `app/Http/Controllers/Contribution/ContributionController.php`

### Constructor Injection

```php
public function __construct(
    private ContributionPointsService $pointsService,
    private LeaderboardService $leaderboardService,
) {}
```

### Actions

#### index(Organisation $organisation): Response

**Route:** `GET /organisations/{organisation}/contributions`
**Name:** `contributions.index`

Lists the authenticated user's contributions, paginated (20 per page). Passes weekly points data for the cap indicator.

**Props sent to Vue:**
```php
[
    'organisation'  => $organisation,          // Organisation model
    'contributions' => $contributions,         // LengthAwarePaginator
    'weeklyPoints'  => $weeklyPoints,          // int
    'weeklyCap'     => 100,                    // int (hardcoded)
]
```

#### create(Organisation $organisation): Response

**Route:** `GET /organisations/{organisation}/contributions/create`
**Name:** `contributions.create`

Renders the contribution form with weekly points data for the live preview.

**Props sent to Vue:**
```php
[
    'organisation' => $organisation,
    'weeklyPoints' => $weeklyPoints,
    'weeklyCap'    => 100,
]
```

#### store(Request $request, Organisation $organisation): RedirectResponse

**Route:** `POST /organisations/{organisation}/contributions`
**Name:** `contributions.store`

Validates and creates a new contribution with status `pending`.

**Validation rules:**

| Field | Rules |
|-------|-------|
| `title` | required, string, max:255 |
| `description` | required, string, max:2000 |
| `track` | required, in:micro,standard,major |
| `effort_units` | required, integer, min:1, max:100 |
| `proof_type` | required, in:self_report,photo,document,third_party,institutional |
| `team_skills` | nullable, array |
| `team_skills.*` | string, max:100 |
| `is_recurring` | boolean |
| `outcome_bonus` | integer, min:0, max:200 |

**On success:** Redirects to the show page with flash message.

**Inertia 2.0 compliance:** Uses `redirect()->route()` with `->with()` flash. The frontend uses `router.post()` — not raw fetch.

#### show(Organisation $organisation, Contribution $contribution): Response

**Route:** `GET /organisations/{organisation}/contributions/{contribution}`
**Name:** `contributions.show`

Shows a single contribution with its ledger entries.

**Security checks:**
1. `$contribution->organisation_id !== $organisation->id` → 404
2. `$contribution->user_id !== auth()->id()` → 403

**Props sent to Vue:**
```php
[
    'organisation' => $organisation,
    'contribution' => $contribution->load('ledgerEntries'),
]
```

#### leaderboard(Organisation $organisation): Response

**Route:** `GET /organisations/{organisation}/leaderboard`
**Name:** `leaderboard`

Renders the leaderboard page.

**Props sent to Vue:**
```php
[
    'organisation' => $organisation,
    'board'        => $this->leaderboardService->get($organisation->id),
]
```

---

## Route Registration

**File:** `routes/organisations.php` (lines 182–189)

```php
// Contributions
Route::prefix('contributions')->name('contributions.')->group(function () {
    Route::get('/',          [ContributionController::class, 'index'])->name('index');
    Route::get('/create',    [ContributionController::class, 'create'])->name('create');
    Route::post('/',         [ContributionController::class, 'store'])->name('store');
    Route::get('/{contribution}', [ContributionController::class, 'show'])->name('show');
});

Route::get('/leaderboard', [ContributionController::class, 'leaderboard'])->name('leaderboard');
```

All routes are nested within the `organisations/{organisation}` group and require authentication middleware.

---

## Route Verification

```bash
php artisan route:list --name=contributions
php artisan route:list --name=leaderboard
```

Expected: 4 contribution routes + 1 leaderboard route = 5 total.
