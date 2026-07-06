---
name: Phase 7 API Layer Architecture
description: Key architectural decisions for Governance API Layer (Phase 7 Steps 1-4)
type: project
originSessionId: 88722429-c6d0-4dac-af10-fbb07db37e97
---
# Phase 7 — Governance API Layer Architecture

## Architecture Rules (enforced by fitness tests)
- **FE-01**: Response DTOs do not expose Eloquent models
- **FE-02**: Controllers do not import domain policies/interpreters
- **FE-03**: API responses are immutable readonly
- **FE-04**: All governance APIs are versioned under `/api/v1/governance`
- **FE-05**: API controllers must not bypass tenant authorization
- **FE-06**: API hierarchy endpoints must enforce max depth (depth <= 5)
- **FE-07**: Governance APIs must emit correlatable request IDs
- **FE-08**: Query handlers remain interpretation-free

## Key Design Decisions
- **Why:** API-first frontend integration requires stable contracts, not Inertia pages

### Response DTOs (not Laravel API Resources)
Explicit `final readonly` classes implementing `JsonSerializable`. Prevents Eloquent leakage, gives full serialization control.

### No Repository Injection in Controllers
Controllers depend only on Use Cases / Query Services. Repositories stay hidden behind the application boundary.

### Projection Freshness Meta
Response meta must include: `projectionGeneration`, `projectionAgeMs`, `projectionEvaluatedAt`, `stale` flag. Frontend needs to reason about freshness.

### Error Contract Standard
`{ error: { code, message, status, traceId } }` — codes: VALIDATION_ERROR (400), COMMITTEE_NOT_FOUND (404), PROJECTION_STALE (409), RATE_LIMITED (429), INTERNAL_ERROR (500).

### Rate Limiting
100 requests/minute per tenant via Laravel RateLimiter, applied via `throttle:governance` middleware.

### Hierarchy Query Defaults
Default depth=1 (not full tree). Max depth <= 5. Supports `?depth`, `?root`, `?search`, `?state` params. Children endpoint needs cursor pagination.

### Caching
Add `Cache-Control: max-age=60, stale-while-revalidate=300` and `ETag` headers to hierarchy responses.

## What's Deferred from Phase 7
- OpenAPI YAML spec (can write alongside implementation)
- ETag strategy (optimization, not correctness)
- WebSocket/SSE (Phase 8)
- Full admin dashboard (Phase 8)
- Observability/metrics (Phase 7c)
- API deprecation policy (documentation)
