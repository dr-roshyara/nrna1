## 📚 Public Digit — Architecture Developer Guide

### Routing Architecture: Web vs API Separation

*A comprehensive guide to understanding and maintaining the routing architecture that solved the Inertia + API conflict.*

---

## Table of Contents

1. [The Problem We Solved](#1-the-problem-we-solved)
2. [Core Architecture Principles](#2-core-architecture-principles)
3. [Route Separation Strategy](#3-route-separation-strategy)
4. [Middleware Stack Configuration](#4-middleware-stack-configuration)
5. [Tenant Context Management](#5-tenant-context-management)
6. [Vue API Integration](#6-vue-api-integration)
7. [Common Pitfalls & Solutions](#7-common-pitfalls--solutions)
8. [Debugging Checklist](#8-debugging-checklist)
9. [File Structure Reference](#9-file-structure-reference)

---

## 1. The Problem We Solved

### The Symptom

```
GET /api/governance/committees/{id}/members → 401 Unauthorized
POST → HTML response instead of JSON
```

### The Root Cause

```yaml
INERTIA MIDDLEWARE:
  - Applied globally to ALL web routes
  - Intercepted API requests expecting JSON
  - Returned HTML error pages (login redirects)

API MIDDLEWARE:
  - Default Laravel API routes use Sanctum tokens
  - Session cookies not recognized
  - 401 responses became HTML redirects

RESULT: Vue components received HTML instead of JSON → parsing errors
```

---

## 2. Core Architecture Principles

### The Golden Rule

```yaml
WEB ROUTES (Inertia):
  - Serve HTML pages
  - Use URL slugs for tenant context
  - Session-based authentication
  - Return Inertia responses

API ROUTES (JSON):
  - Serve data only
  - Use headers for tenant context
  - Session-based authentication (same session!)
  - ALWAYS return JSON
  - NEVER trigger Inertia rendering
```

### The Middleware Order Mandate

```yaml
CRITICAL: 'json.api' MUST come BEFORE 'auth'

WHY: 
  - Forces Accept: application/json BEFORE authentication check
  - If auth fails → JSON 401, not HTML redirect to login

CORRECT ORDER:
  1. web (session, cookies)
  2. json.api (FORCE JSON)
  3. auth (authentication)
  4. verified (email verification)
  5. tenant.api (tenant context)
```

---

## 3. Route Separation Strategy

### File Structure

```text
routes/
├── web.php              # Inertia HTML routes (with HandleInertiaRequests)
├── api_v1.php           # Pure JSON API routes (NO Inertia)
└── api.php              # Legacy/External API routes (Sanctum tokens)
```

### bootstrap/app.php Configuration

```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // CRITICAL: API routes registered SEPARATELY from web
            // These routes NEVER see HandleInertiaRequests
            Route::middleware(['web', 'json.api', 'auth', 'verified', 'tenant.api'])
                ->prefix('api/v1')
                ->name('api.v1.')
                ->group(base_path('routes/api_v1.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'json.api' => ForceJsonResponse::class,
            'tenant.api' => IdentifyTenantFromHeader::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // CRITICAL: API routes return JSON errors, never HTML
        $exceptions->shouldRenderJsonWhen(function ($request, $e) {
            return $request->is('api/v1/*') || $request->expectsJson();
        });
    });
```

---

## 4. Middleware Stack Configuration

### ForceJsonResponse Middleware

**Purpose:** Force the request to expect JSON before any other middleware runs.

```php
// app/Http/Middleware/ForceJsonResponse.php
class ForceJsonResponse
{
    public function handle(Request $request, Closure $next)
    {
        // Force JSON expectation BEFORE auth check
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('X-Requested-With', 'XMLHttpRequest');
        
        return $next($request);
    }
}
```

### IdentifyTenantFromHeader Middleware

**Purpose:** Extract tenant context from header for API requests.

```php
// app/Http/Middleware/IdentifyTenantFromHeader.php
class IdentifyTenantFromHeader
{
    public function handle(Request $request, Closure $next)
    {
        $organisationId = $request->header('X-Organisation-ID');
        
        if (!$organisationId) {
            return response()->json([
                'error' => 'X-Organisation-ID header is required for API requests.',
                'code' => 'MISSING_TENANT_CONTEXT'
            ], 400);
        }
        
        $organisation = Organisation::find($organisationId);
        
        if (!$organisation) {
            return response()->json(['error' => 'Organisation not found'], 404);
        }
        
        // Verify user belongs to this organisation
        if (!$request->user()->organisations()->where('organisations.id', $organisation->id)->exists()) {
            return response()->json(['error' => 'Unauthorized tenant context'], 403);
        }
        
        // Bind tenant context for domain layer
        app(TenantContext::class)->setCurrent($organisation);
        
        return $next($request);
    }
}
```

---

## 5. Tenant Context Management

### Web Routes (URL Slug)

```php
// routes/web.php
Route::prefix('organisations/{organisation:slug}')->group(function () {
    Route::get('/committees/{committee}/dashboard', [CommitteeDashboardController::class, 'show'])
        ->name('committee.dashboard');
});
```

### API Routes (Header)

```php
// routes/api_v1.php
Route::prefix('governance')->group(function () {
    Route::get('/committees/{committeeId}/members', [CommitteeMemberController::class, 'index']);
    Route::post('/committees/{committeeId}/members', [CommitteeMemberController::class, 'store']);
    Route::delete('/committees/{committeeId}/members/{memberId}', [CommitteeMemberController::class, 'destroy']);
});
```

### Vue API Client with Header Interceptor

```javascript
// resources/js/services/api.js
import axios from 'axios';

const apiClient = axios.create({
    baseURL: '/api/v1',
    withCredentials: true,  // CRITICAL: Send session cookie
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    }
});

// Interceptor: Add tenant header to EVERY request
apiClient.interceptors.request.use((config) => {
    const organisationId = getCurrentOrganisationId(); // from store/page props
    if (organisationId) {
        config.headers['X-Organisation-ID'] = organisationId;
    }
    return config;
});

export default apiClient;
```

---

## 6. Vue API Integration

### Correct API Call Pattern

```javascript
// CommitteeMemberManager.vue
import apiClient from '@/services/api';

const fetchMembers = async () => {
    try {
        const response = await apiClient.get(`/governance/committees/${committeeId}/members`);
        membersList.value = response.data.members || [];
    } catch (error) {
        console.error('API error:', error.response?.data);
    }
};

const addMember = async () => {
    try {
        const response = await apiClient.post(`/governance/committees/${committeeId}/members`, {
            memberId: selectedMember.value.id,
            role: selectedRole.value,
        });
        
        if (response.status === 201) {
            successMessage.value = 'Member added successfully!';
            await fetchMembers();
        }
    } catch (error) {
        errors.value = error.response?.data?.errors || { searchQuery: 'Failed to add member' };
    }
};
```

### Critical Headers

| Header | Purpose | Required |
|--------|---------|----------|
| `Accept: application/json` | Expect JSON response | ✅ Yes |
| `X-Requested-With: XMLHttpRequest` | Identify AJAX request | ✅ Yes |
| `X-Organisation-ID` | Tenant context for API | ✅ Yes |
| `credentials: 'include'` | Send session cookie | ✅ Yes |

---

## 7. Common Pitfalls & Solutions

### Pitfall 1: Inertia Returns HTML Instead of JSON

```yaml
SYMPTOM: API returns full HTML page
CAUSE: Route is in web.php with Inertia middleware
FIX: Move route to api_v1.php
```

### Pitfall 2: 401 Unauthorized with Valid Session

```yaml
SYMPTOM: 401 even when logged in
CAUSE: API route uses Sanctum guard, not session guard
FIX: Use 'web' middleware group, not 'api'
```

### Pitfall 3: CSRF Token Mismatch

```yaml
SYMPTOM: 419 CSRF token mismatch
CAUSE: Fetch requests without CSRF token
FIX: Use axios (handles automatically) or add X-CSRF-TOKEN header
```

### Pitfall 4: Missing Tenant Header

```yaml
SYMPTOM: "X-Organisation-ID header is required"
CAUSE: Vue component not sending tenant header
FIX: Add axios interceptor to inject header automatically
```

### Pitfall 5: SQL Column Name Mismatch

```yaml
SYMPTOM: column "tenant_id" does not exist
CAUSE: Code uses 'tenant_id' but table has 'organisation_id'
FIX: Use consistent column naming across all queries
```

---

## 8. Debugging Checklist

### When API Returns HTML (not JSON)

```bash
# 1. Check route location
php artisan route:list | grep "your-route"

# 2. Verify route is NOT in web.php with Inertia
# 3. Verify route IS in api_v1.php

# 4. Check middleware order
php artisan route:list --name=your-route
```

### When Getting 401 Unauthorized

```bash
# 1. Check if user is logged in
php artisan tinker --execute="dd(auth()->check())"

# 2. Verify session cookie is being sent
# Browser DevTools → Network → Request Headers → Cookie

# 3. Check API client has withCredentials: true
```

### When Getting Tenant Context Errors

```javascript
// Add debug logging
console.log('Sending X-Organisation-ID:', organisationId);
console.log('API Response:', response.data);
```

---

## 9. File Structure Reference

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── Governance/
│   │           └── CommitteeMemberController.php
│   └── Middleware/
│       ├── ForceJsonResponse.php          # Forces Accept: application/json
│       └── IdentifyTenantFromHeader.php   # Extracts tenant from header
├── Services/
│   └── TenantContext.php                  # Singleton for domain layer
└── Contexts/
    └── Membership/
        └── Infrastructure/
            └── Projection/
                └── CommitteeMemberProjectionListener.php

bootstrap/
└── app.php                                 # Route registration + middleware config

routes/
├── web.php                                 # Inertia HTML routes
├── api_v1.php                              # Pure JSON API routes (NO Inertia)
└── api.php                                 # Legacy Sanctum API routes

resources/
└── js/
    ├── services/
    │   └── api.js                          # Axios client with interceptors
    └── Components/
        └── CommitteeMemberManager.vue
```

---

## Key Takeaways

```yaml
1. SEPARATE ROUTES BY INTENT:
   - Web routes → Inertia + HTML
   - API routes → Pure JSON

2. MIDDLEWARE ORDER MATTERS:
   - 'json.api' BEFORE 'auth'
   - Forces JSON before authentication

3. USE AXIOS FOR API CALLS:
   - Automatic CSRF handling
   - Interceptors for tenant headers
   - withCredentials for session cookies

4. TENANT CONTEXT:
   - Web: URL slug
   - API: X-Organisation-ID header

5. EXCEPTION HANDLING:
   - API routes return JSON errors
   - Never redirect to login page
```

---

*Last Updated: 2026-05-17*  
*Author: Public Digit Architecture Team*  
*Based on: Gemini architecture review + production debugging session*