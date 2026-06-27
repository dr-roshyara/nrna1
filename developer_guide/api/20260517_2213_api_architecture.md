## Public Digit: Architecture Decision Record & Developer Guide

### Multi-Tenant Route Separation & Context Hydration Pipeline

This document outlines the architectural separation between the **Stateful Web UI (Inertia.js)** and the **Stateless REST API (v1)** pipelines. It serves as a permanent reference for engineers working on tenant isolation, authentication sequencing, and asynchronous context resolution.

---

## 1. Architectural Overview

The platform uses an organizational multi-tenancy model where business logic is strictly partitioned across bounded contexts. To maintain a clean separation of concerns and avoid leaky abstractions, the HTTP layout isolates standard multi-page app actions from machine-to-machine or single-page asynchronous operations.

```
Incoming Request
       │
       ▼
 ┌───────────┐       No (Traditional Web / SPA Router)
 │  API URI? ├─────────────────────────────────────────► [ web Middleware Group ]
 └─────┬─────┘                                                   │ 
       │ Yes (/api/v1/*)                                         ▼
       ▼                                                 [ Inertia Lifecycle ]
 [ bootstrap/app.php Route Override Pipeline ]                   │
       │                                                         ▼
       ├─► 1. json.api   (Enforce application/json)       [ Web Webhooks/Views ]
       ├─► 2. tenant.api (Hydrate Global TenantContext)
       ├─► 3. auth       (Evaluate Stateful Session)
       ├─► 4. verified   (Confirm Account Standing)
       │
       ▼
 [ routes/api_v1.php ] ──► [ Controller Adapters ] ──► [ Pure PHP Commands ]

```

---

## 2. Core Separation of Pipelines

### 2.1 The Traditional Web Pipeline

Traditional routing goes through the primary framework container. It executes the standard `web` middleware matrix, checking for CSRF tokens on stateful mutations and feeding response metrics into the Inertia rendering loop. This layer passes complete template footprints directly back to Vue components.

### 2.2 The Isolated API Pipeline (`/api/v1/*`)

To allow the frontend client to interact with data layers asynchronously without full-page reloads, API routes are extracted from the default setup.

* **No Inertia Overhead:** Responses are forced into pure, structured JSON arrays.
* **Bypassed Session Redirection:** Traditional browser `302 Found` commands are blocked and replaced with explicit, machine-readable HTTP status codes (e.g., `401 Unauthorized`, `400 Bad Request`).

---

## 3. The Lifecycle & Middleware Sequence

The ordering of route operations is heavily sensitive to dependencies. The middleware stack for API processing is configured in `bootstrap/app.php` with the following specific order:

```php
\Illuminate\Support\Facades\Route::middleware([
    'web', 
    'json.api', 
    'tenant.api', 
    'auth', 
    'verified'
])

```

### Breakdown of the Sequence

#### 1. `web`

Establishes basic framework session mapping, cookie processing, and encrypted payload parsing.

#### 2. `json.api` (`ForceJsonResponse`)

Intercepts the request early. It dynamically sets the incoming `Accept: application/json` headers on the framework state. This prevents security exceptions from triggering HTML redirections.

#### 3. `tenant.api` (`IdentifyTenantFromHeader`)

Inspects incoming client request parameters to extract organization markers. By looking up headers case-insensitively (`X-Organisation-ID`, `x-organisation-id`, `X-Tenant-Id`), it hydrates the request-scoped, static domain context tracking layer before any database logic occurs:

```php
TenantContext::set($organisationId);

```

#### 4. `auth` & `verified`

Evaluates the user's session standing. Because the tenant context is set *before* this step, user identity queries run safely within their designated multi-tenant database boundaries.

---

## 4. Troubleshooting Ledger & Key Fixes

During implementation, three distinct structural mismatches were identified and corrected.

### Issue 1: CSRF Token Misidentification (`302 Found`)

* **Symptom:** Asynchronous `POST` operations failed immediately with a `302 Found` browser redirect, trying to load a root HTML interface.
* **Root Cause:** Because the custom API routes run alongside a shared cookie environment, they were intercepted by the web layer's automated Cross-Site Request Forgery mitigation checks. Since the JavaScript payload did not supply a valid token, the request was immediately terminated with a web-style redirect before hitting any controller or JSON formatter.
* **Resolution:** Explicitly exempted the entire route space from the stateful CSRF verification engine within `bootstrap/app.php`:
```php
$middleware->validateCsrfTokens(except: [
    'api/v1/*',
]);

```



### Issue 2: Prefix Duplication & Router Fallbacks (`404 Not Found`)

* **Symptom:** The framework routinely returned a `404 Not Found` payload containing error indicators like `MISSING_TENANT_CONTEXT`.
* **Root Cause:** The system's routing group definition inside `bootstrap/app.php` already applied a macro prefix: `->prefix('api/v1')`. Inside `routes/api_v1.php`, routes were nested again inside an explicit prefix group (`Route::prefix('/governance...')`). This generated an unintended internal route structure (`/api/v1/api/v1/governance/...`). Unmatched calls fell back to raw web routing, missing the necessary middleware configuration.
* **Resolution:** Flattened `routes/api_v1.php` to define clean, un-prefixed direct targets:
```php
Route::post('/governance/committees/{committeeId}/members', [CommitteeMemberController::class, 'store']);

```



### Issue 3: Entity Mismatch inside Controller Layout

* **Symptom:** Submitting a member addition request returned a specific `404` error: `{"error":"User not found"}`.
* **Root Cause:** The UI search component deals with tenant-isolated `Member` entities. When sending the selected ID back to the backend, the controller was trying to use that ID directly against the root `User` table (`User::find($id)`).
* **Resolution:** Refactored the controller's lookup sequence to trace the multi-tenant data graph correctly:
```php
$member = \App\Models\Member::withoutGlobalScopes()
    ->where('id', $memberId->value())
    ->where('organisation_id', $tenantIdValue)
    ->first();

$user = $member?->user;

```



---

## 5. JavaScript / Vue Integration Rules

To safely interact with this pipeline from frontend components (e.g., `CommitteeMemberManager.vue`), adhere to these implementation rules:

### 1. Unified Headers Configuration

Every asynchronous HTTP invocation targeting the `/api/v1/*` space must present a fully qualified content handshake. This guarantees uniform treatment across all middleware layers.

```javascript
const fetchOptions = {
    method: 'POST',
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-Organisation-ID': organisationIdFromProps
    },
    body: JSON.stringify(payload)
};

```

### 2. Idempotent Stream Processing

Never read a raw network response stream more than once. Read the payload into a temporary variable to allow multiple processing steps to run without resource locks:

```javascript
const response = await fetch(url, fetchOptions);
const responseText = await response.text(); // Read EXACTLY once into memory

let result;
try {
    result = JSON.parse(responseText);
} catch (e) {
    throw new Error(`Malformed payload response: ${responseText.substring(0, 200)}`);
}

if (!response.ok) {
    throw new Error(result.error || 'Fallback application error state encountered');
}

```