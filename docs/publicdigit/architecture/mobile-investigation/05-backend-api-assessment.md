# 05: Backend and API assessment

> REPORT · generated · 2026-11-09 · snapshot `15217f4a9`. Labels: FACT / INTERPRETATION / ASSUMPTION / RECOMMENDATION / UNKNOWN.

## 1. Existing API coverage (FACT)

| Surface | Routes | Auth | Notes |
|---|---|---|---|
| `routes/api.php` | `GET /api/user`, `POST /api/register`, `POST /api/tokens/create`, `POST /api/login`, locale (2), `detect-location`, `GET /api/openions/search`, `POST /api/organisations/{organisation}/demo-setup`, plus geography | `auth:sanctum` on 3 groups only; `tokens/create` has **none** (`:32-36`) | no versioning; no throttle on login (`:38`); no route-level throttle on login (whether a group-level throttle applies was not verified; ASSUMPTION: the Laravel 11+ default `api` group does not include one) |
| `routes/api_v1.php` | `test-auth`, `test-post-debug`, committee members index/store/destroy | **session guard** (`web` + `auth`), CSRF-exempt, `tenant.api` header | debug routes return `session_id` (`:27`) |
| `routes/governance-api.php`, `routes/api/governance*.php` | committee members (legacy duplicate) plus test routes | web session | duplicates `api_v1` (comment in `api.php:57`) |
| `routes/geography/geographyApiRoutes.php` | geography reads | public, `throttle:100,60` (`:10`) | reusable by mobile as is |

The 2026-11-03 baseline counted about 56 API routes, 6 with `auth:sanctum` (`../reviews/2026-11-03-…-report.md:111`).
- **No API Resources:** `app/Http/Resources` is absent (FACT).
- **About 20 FormRequests** (`app/Http/Requests/**`).
- **No OpenAPI or Scribe** (FACT: nothing in `composer.json` or `config`).
- **No idempotency-key mechanism** (FACT: grep finds the word only in domain/listener comments).
- Rate limiters `api` (60/min) and `governance` are defined (`app/Providers/RouteServiceProvider.php:127-134`). The `governance` limiter keys by **session** tenant, so it is meaningless for token clients (INTERPRETATION).

**Conclusion (INTERPRETATION):** there is **no mobile-usable API today**. Domain capability exists, but HTTP exposure is Inertia-only except for committee members and geography.

## 2. Authentication lifecycle

| Aspect | Today (FACT) | Mobile need (RECOMMENDATION) |
|---|---|---|
| Web login | `LoginController`: validation, `RateLimiter`, verified-email gate, `DashboardResolver` redirect (`LoginController.php:43-94`) | unchanged |
| Token login | `AuthController::login`: no validation, no throttle, no verified check, returns the full `$user` model and a token (`AuthController.php:56-75`) | **do not reuse.** A new endpoint must call the same credential, throttle and verification policy as web (extract it into a shared authenticator service) |
| Token expiry | `config/sanctum.php` `expiration => null` | short-lived access tokens plus a rotating refresh, or Sanctum tokens with expiry and per-device rotation |
| Device credentials | none (`createToken('myApiToken')`) | token name = device id and platform; abilities scoped (`member:read`, `membership:apply`); list and revoke per device in the profile |
| Revocation | no endpoint | `POST /logout` revokes the current token; "log out all devices"; revoke on password change |
| 2FA | `Pages/Auth/TwoFactorChallenge.vue` exists; Fortify features are empty (`config/fortify.php:133-136`) | UNKNOWN whether 2FA is active; must be decided before the mobile login (D-4) |
| Social login | Socialite plus Google controllers | mobile OAuth needs a PKCE/native flow; out of pilot scope |

## 3. Do Inertia controllers call reusable use cases?

| Flow | Evidence | Reusable for API? |
|---|---|---|
| Membership apply (authenticated) | `MembershipApplicationController::store` builds `SubmitMembershipApplicationCommand` → `SubmitMembershipApplication::execute` (`:56-116`) | **Yes**: an API controller can call the same use case |
| Membership approve | Use case `ApproveMembershipApplication` (`:300`) **but** the controller first creates `User`, `OrganisationUser`, `UserOrganisationRole`, sends a password reset and dispatches an event inside its own `DB::transaction` (`:213-305`) | **Partly.** Extract an "approve public application" orchestration use case first. Desktop-only for now anyway |
| Membership public apply | `PublicMembershipApplicationController::store`: rate limit, honeypot, duplicate guard, `MembershipApplication::create`, mails, all in the controller (`:28-87`) | **No.** Extract a use case before any second channel |
| Committee members | `CommitteeMemberController` uses Governance handlers, query services and DTOs (`Api/Governance/CommitteeMemberController.php:8-17`) | **Yes** (already an API, but session-based) |
| Voting | `VoteController` (4,388 lines). Ballot state in session (`:621,870,1655`). `save_vote` reads `session('current_organisation_id')` (`:2919`). Security assessment through `VotingSecurityAssessmentQuery` (`:1489-1497`) | **No.** Structurally web-session-bound (see `06`) |

**INTERPRETATION.** The target `Inertia/API controller → use case → domain` pattern is **already possible for the greenfield contexts** (Membership submit, Governance committee). It is **not** possible for the legacy voting flow or the public application flow without extraction first.

## 4. Required backend changes (RECOMMENDATION, per flow, when that flow goes mobile)

1. **Mobile API surface:** `routes/mobile_api_v1.php` mounted at `/api/mobile/v1` with `auth:sanctum` (token guard **only**, not the `web` group), `throttle`, `json.api`, and a tenant from an explicit header **validated against token-holder membership**, without writing to the session.
   - Naming: the business case names `/mapi/v1` (`BUSINESS_CASE.md:224`). The path is cosmetic; D-2 settles it.
2. **No logic in API controllers:** thin controllers → existing or extracted use cases. Responses through API Resources (new) built from DTOs or read models.
3. **Error standard:** reuse the `{error, code, details}` shape already used for `/api/v1` (`bootstrap/app.php:116-165`), extended to the mobile prefix.
4. **Idempotency:** `Idempotency-Key` header on mobile POSTs (membership application, uploads). Store key → response for 24 h. Mobile retries otherwise create duplicates. Today the public-apply duplicate guard is email-based (`PublicMembershipApplicationController.php:56-64`), which covers that case only.
5. **File uploads:** chunked or presigned multipart with size and type validation, a virus-scan hook (UNKNOWN whether one exists), and an EXIF strip for camera photos.
6. **Notifications:** a new push channel (FCM/APNs) as a Laravel notification channel. A device-token table is per user and **never linked to votes**.
7. **Audit:** API actions emit the same audit events as web. Add a `channel=mobile` attribute, but **never** for voting events (anonymity, `06`).
8. **Documentation and contracts:** OpenAPI generated from routes and FormRequests (for example Scribe; not chosen, D-4/T-decision), plus contract tests (see `07`).
9. **Versioning:** URL major version plus additive-only changes within a version, because store clients lag behind.
10. **CORS:** add the Capacitor origins (`capacitor://localhost`, `https://localhost`) to `config/cors.php` **only for the mobile prefix**, with no credentials for token auth.

## 5. What must not happen
- Do not point the mobile app at `/api/v1` (session, CSRF-exempt) or `/api/login` (unhardened).
- Do not copy validation or business rules from Inertia controllers into API controllers. Extract them into a use case and call it from both.
- Do not let the mobile tenant header write `session(['current_organisation_id'])` (as `IdentifyTenantFromHeader.php` does today). Token requests must stay stateless.
