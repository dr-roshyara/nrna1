# 🏗️ Organisation Controllers Architecture Guide

**Last Updated:** 2026-03-06
**Status:** Documentation Complete

---

## Overview

Public Digit has **TWO separate OrganisationControllers** serving different purposes:

| Aspect | Web Controller | API Controller |
|--------|---|---|
| **Namespace** | `App\Http\Controllers\OrganisationController` | `App\Http\Controllers\Api\OrganisationController` |
| **Location** | `app/Http/Controllers/OrganisationController.php` | `app/Http/Controllers/Api/OrganisationController.php` |
| **Purpose** | Web page rendering | JSON API responses |
| **Response Type** | Inertia views | JSON |
| **Methods** | show(), store() | show(), store() |

---

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                      OrganisationControllers                     │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌──────────────────────────┐   ┌──────────────────────────┐    │
│  │  WEB CONTROLLER          │   │  API CONTROLLER          │    │
│  │  (Browser Requests)      │   │  (API Requests)          │    │
│  ├──────────────────────────┤   ├──────────────────────────┤    │
│  │ Namespace:               │   │ Namespace:               │    │
│  │ App\Http\Controllers\... │   │ App\Http\Controllers\... │    │
│  │                          │   │ \Api\...                 │    │
│  │                          │   │                          │    │
│  │ Methods:                 │   │ Methods:                 │    │
│  │ • show($slug)            │   │ • show($slug)            │    │
│  │ • store(Request)         │   │ • store(Request)         │    │
│  │                          │   │                          │    │
│  │ Returns:                 │   │ Returns:                 │    │
│  │ inertia('Org/Show')      │   │ response()->json()       │    │
│  │                          │   │                          │    │
│  │ Client:                  │   │ Client:                  │    │
│  │ Vue 3 Frontend           │   │ REST API consumers       │    │
│  └──────────────────────────┘   └──────────────────────────┘    │
│           ▲                              ▲                        │
│           │                              │                        │
│    GET /organisations/{slug}    GET /api/organisations/{slug}   │
│    (Web Route)                  (API Route)                      │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

---

## Web Controller: `App\Http\Controllers\OrganisationController`

### Purpose
Serves HTML pages to web browsers. Returns Inertia components that render as Vue 3 pages.

### Methods

#### `show($slug)` - Display Organisation Page
```php
public function show($slug)
{
    // 1. Get organisation by slug
    $organisation = Organisation::where('slug', $slug)
        ->whereNull('deleted_at')
        ->firstOrFail();

    // 2. Verify user is authenticated and a member
    $user = auth()->user();
    $isMember = $user->organisationRoles()
        ->where('organisation_id', $organisation->id)
        ->exists();

    if (!$isMember) {
        return redirect()->route('dashboard')
            ->withErrors(['error' => 'Access denied']);
    }

    // 3. Set current organisation in session
    session(['current_organisation_id' => $organisation->id]);

    // 4. Get user's role and permissions
    $userRole = $user->organisationRoles()
        ->where('organisation_id', $organisation->id)
        ->value('role');
    $canManage = in_array($userRole, ['owner', 'admin']);

    // 5. Gather statistics and return Inertia component
    return inertia('Organisations/Show', [
        'organisation' => $organisation->only(['id', 'name', 'slug']),
        'stats' => [...],
        'demoStatus' => [...],
        'canManage' => $canManage,
    ]);
}
```

**Route:** `GET /organisations/{slug}`

**Response:** Inertia component renders as HTML page in browser

**Used By:**
- Vue 3 frontend when user visits `/organisations/publicdigit`
- Desktop admin dashboard

---

#### `store(Request $request)` - Create Organisation
```php
public function store(Request $request)
{
    // Validate input
    $request->validate(['name' => 'required|string|max:255']);

    // Create new tenant organisation
    $org = DB::transaction(function () use ($request, $user) {
        $org = Organisation::create([
            'name' => $request->name,
            'type' => 'tenant',
            'is_default' => false,
        ]);

        // User becomes OWNER
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $org->id,
            'role' => 'owner',
        ]);

        return $org;
    });

    // Redirect to new org dashboard
    return redirect("/organisations/{$org->id}/dashboard");
}
```

**Route:** `POST /organisations`

**Response:** Redirect to organisation dashboard

---

## API Controller: `App\Http\Controllers\Api\OrganisationController`

### Purpose
Serves JSON responses to API clients. Used for programmatic access to organisation operations.

### Methods

#### `show($slug)` - Get Organisation Data (JSON)
```php
public function show(string $slug)
{
    // 1. Get organisation with admin users
    $organisation = Organisation::where('slug', $slug)
        ->with(['users' => function ($query) {
            $query->wherePivot('role', 'admin');
        }])
        ->firstOrFail();

    // 2. Verify user is a member
    $isMember = $organisation->users()
        ->where('users.id', auth()->id())
        ->exists();

    if (!$isMember) {
        abort(403, 'Access denied');
    }

    // 3. Get demo election statistics
    $demoElection = Election::withoutGlobalScopes()
        ->where('type', 'demo')
        ->where('organisation_id', $organisation->id)
        ->first();

    $demoStats = [
        'exists' => $demoElection !== null,
        'posts' => $demoElection ? DemoPost::count() : 0,
        'candidates' => $demoElection ? DemoCandidacy::count() : 0,
        'codes' => $demoElection ? DemoCode::count() : 0,
        'votes' => $demoElection ? DemoVote::count() : 0,
    ];

    // 4. Return JSON response
    return inertia('Organisations/Show', [
        'organisation' => [...],
        'stats' => ['members_count' => ...],
        'demoStatus' => $demoStats,
        'canManage' => $isMember,
    ]);
}
```

**Route:** `GET /api/organisations/{slug}` (if defined in routes/api.php)

**Response:** JSON object with organisation data

**Features:**
- Extensive logging for debugging
- Detailed demo election statistics
- Full error tracking
- Membership verification with pivot table checks

---

#### `store(StoreOrganisationRequest $request)` - Create Organisation (API)
```php
public function store(StoreOrganisationRequest $request): JsonResponse
{
    try {
        // 1. Create organisation with full details
        $organisation = Organisation::create([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'representative' => $request->representative,
            'slug' => Str::slug($request->name),
        ]);

        // 2. Attach current user as admin
        $organisation->users()->attach($user->id, ['role' => 'admin']);

        // 3. Handle representative (optional)
        if ($request->representative && !$request->representative['is_self']) {
            $representativeUser = User::firstOrCreate([...]);
            $organisation->users()->attach($representativeUser->id, ['role' => 'voter']);
            // Send invitation email
        }

        // 4. Return JSON response with redirect
        return response()->json([
            'success' => true,
            'message' => 'Organisation created successfully!',
            'redirect' => route('organisations.show', $organisation->slug),
            'organisation' => $organisation,
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
}
```

**Route:** `POST /api/organisations` (if defined in routes/api.php)

**Response:** JSON with creation result and redirect URL

**Features:**
- Full organisation creation with email and address
- Representative user handling
- Automatic invitation emails
- Detailed error responses
- JSON-based flow for Inertia 2.0

---

## Why Two Controllers?

### Design Rationale

1. **Separation of Concerns**
   - Web controller handles view rendering
   - API controller handles data operations
   - Each optimized for its use case

2. **Response Format**
   - Web: Returns Inertia components (Vue 3)
   - API: Returns JSON (programmatic access)

3. **Feature Set**
   - Web: Minimal, focused on page display
   - API: Rich, includes all operational details

4. **Error Handling**
   - Web: User-friendly redirects
   - API: JSON error responses with codes

5. **Scalability**
   - Can evolve independently
   - Different versioning strategies
   - Can move to separate services later

---

## Routing

### Web Routes
```php
// routes/web.php
Route::get('/organisations/{slug}', [OrganisationController::class, 'show'])
    ->name('organisations.show');

Route::post('/organisations', [OrganisationController::class, 'store'])
    ->name('organisations.store');
```

**Middleware:** `auth`, `verified`, `ensure.organisation`

---

### API Routes (If Defined)
```php
// routes/api.php
Route::get('/organisations/{slug}', [OrganisationController::class, 'show'])
    ->name('api.organisations.show');

Route::post('/organisations', [OrganisationController::class, 'store'])
    ->name('api.organisations.store');
```

**Middleware:** `api`, `auth:sanctum`

---

## Current Issue (Fixed)

### Problem
The Web Controller had no `show()` method, causing:
```
BadMethodCallException: Method OrganisationController::show does not exist
```

### Solution
Added `show($slug)` method to `App\Http\Controllers\OrganisationController` to:
1. Fetch organisation by slug
2. Verify user membership
3. Set session context
4. Gather statistics
5. Return Inertia component

---

## Best Practices

### When to Add Methods
- **Web Controller:** Add for user-facing pages (show, index, create, edit, etc.)
- **API Controller:** Add for programmatic operations (store, update, delete, etc.)

### Method Naming
- Web: Standard CRUD verbs (show, index, create, store, edit, update, destroy)
- API: Same verbs but return JSON

### Response Handling
- Web: Use `redirect()`, `inertia()`, or `view()`
- API: Use `response()->json()` or `JsonResource`

### Error Handling
- Web: Redirect with error messages
- API: Return HTTP status codes and error JSON

---

## Consolidation Strategy (Future)

If your API becomes more sophisticated, consider:

1. **Single Controller with Output Format Detection**
   ```php
   public function show($slug)
   {
       $data = $this->getOrganisationData($slug);
       return $request->wantsJson()
           ? response()->json($data)
           : inertia('Organisations/Show', $data);
   }
   ```

2. **API Resource Layer**
   ```php
   Route::apiResource('organisations', OrganisationController::class);
   ```

3. **Separate API Versioning**
   ```php
   Route::prefix('api/v2')->group(function () {
       Route::apiResource('organisations', Api\V2\OrganisationController::class);
   });
   ```

---

## Summary

| Aspect | Web | API |
|--------|-----|-----|
| **Location** | `app/Http/Controllers/` | `app/Http/Controllers/Api/` |
| **Purpose** | Page rendering | Data operations |
| **Response** | HTML (Inertia) | JSON |
| **Route Prefix** | `/organisations` | `/api/organisations` |
| **Client** | Vue 3 Browser | REST clients |
| **Error Response** | Redirect | JSON error |

Both controllers serve their specific purpose. The separation allows each to evolve independently while maintaining clean separation of concerns.

---

**Document Version:** 1.0
**Status:** Production Ready ✅
**Last Updated:** 2026-03-06
