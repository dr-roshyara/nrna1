# 07 — Authentication and identity

## 1. Current state (FACT)
- `users.id` is a UUID; `users.email` is **globally unique**.
- `users.organisation_id` is **NOT NULL, FK restrict** to `organisations`. It is a "home/current organisation" pointer (`User.php:167-170`).
- Login is hand-written: global email lookup → `Auth::attempt` → `DashboardResolver::resolve` (`LoginController.php:46`). It is **independent of the organisation**.
- Google login uses Socialite (`GoogleLoginController.php:19,31`; routes `routes/user/googleRoutes.php:31-32`). **`config/services.php` has no `google` entry**, so its configuration must come from elsewhere or is missing [I].
- Sanctum is used for `api/v1` (`statefulApi()`, `bootstrap/app.php:65`).
- Spatie permissions run with **teams mode off**: roles are platform-global.
- Access to an organisation is checked via `user_organisation_roles` in `EnsureOrganisationMember`, `OrganisationPolicy`, `ElectionPolicy`, `MembershipPolicy`.
- Registration attaches every new user to the platform organisation `publicdigit` (`RegisterController.php:42-73`).

## 2. Target: one global user, many organisations, one database each (RECOMMENDATION)

```text
CENTRAL: users · user_organisation_roles · roles/permissions · sessions · password_reset_tokens · personal_access_tokens
TENANT:  users (read-only subset projection) · own organisations row · own grants (projection)
```

- **Users stay central (Q6).** `User` is pinned to `central`. Authentication **never needs a tenant**: there is no bootstrap problem. This is the decisive difference from `election`, which duplicates users per tenant.
- **`users.organisation_id` stays** as a central "last used / home organisation" preference. It is **never** used to select a database. (In tenant projections the FK is relaxed by `tenant_adjustments`.)
- **`user_organisation_roles` stays central (Q7)** as the access authority. Each tenant DB holds a read-only projection of *its own* rows, for joins.
- **Spatie roles stay global.** If organisation-specific roles are needed later, that is a separate decision (teams mode).

## 3. Login per hostname (Q8)

```mermaid
sequenceDiagram
  participant U as User
  participant H as nrna.publicdigit.com
  participant R as ResolveOrganisationFromHost
  participant C as Central DB
  participant T as NRNA tenant DB
  U->>H: GET /login
  H->>R: Host = nrna.publicdigit.com
  R->>C: organisation_domains → NRNA (active)
  U->>H: POST /login (email, password)
  H->>C: users by email; password check (central)
  H->>C: user_organisation_roles (user, NRNA)?
  alt member of NRNA
    H->>H: session (host-only cookie for nrna.publicdigit.com)
    H->>T: dashboard data (TenantContext = NRNA)
  else not a member
    H-->>U: 403 "You are not a member of NRNA" (+ link to publicdigit.com/my-organisations)
  end
```

- The **same central user** can log in at `nrna.publicdigit.com` and at `nc.publicdigit.com`. Each host checks its own membership.
- On the **apex** (`publicdigit.com`), login behaves as today (`DashboardResolver`), but it reads cross-organisation facts from central (`election_directory`, grants), not by scanning tenant tables.

## 4. Sessions and cookies (Q9) — DECISION REQUIRED

| Option | Behaviour | Isolation | UX |
|---|---|---|---|
| **S1 — host-only cookies (today's default, `SESSION_DOMAIN=null`)** | Each hostname has its own session cookie; the session rows are in the one central `sessions` table | **Strong:** a session stolen on one tenant is useless on another; CSRF per host | Log in again per organisation |
| **S2 — shared cookie `.publicdigit.com`** | One login works on all subdomains (SSO) | Weaker: every subdomain receives the cookie. Membership checks per request remain mandatory; any XSS on one tenant can act on others. | Single login |
| **S3 — central login + per-host session (SSO handshake)** | Login on the apex; redirect with a one-time token to `<org>.publicdigit.com`, which creates its own host-only session | Strong | Single login, more work |

**RECOMMENDATION:** **S1 now** (zero code; strongest isolation; matches the voting security posture). **S3 later** if SSO is wanted. Avoid S2 for an election platform.

- **Storage:** sessions stay in **one central table** (`SESSION_CONNECTION=central`). No per-tenant session tables. That is `election`'s anti-pattern.
- **Logout:** host-only, so logout ends that host's session. "Log out everywhere" = delete all central session rows for the user.

## 5. Identity-related links

| Flow | Host to use | Reason |
|---|---|---|
| Password reset | **Apex** (`ResetPassword::createUrlUsing`) | Identity is central; one reset page |
| Email verification | **Apex** (signed URL generated and validated on the apex) | Central identity action; a signature is host-bound [I] |
| Google OAuth | **One callback on the apex**; `state` holds the return host; then redirect to the tenant host (S3 handshake or just a re-login) | Avoids registering every subdomain with Google |
| Organisation invitations, officer invitations, voter invitations | **Tenant host** | The tenant context is part of the action |

## 6. Sanctum / API
- Tokens (`personal_access_tokens`) are central.
- The API selects the tenant from the **host** (preferred) or from `X-Organisation-ID` **after** `auth` with a membership check.
- **The `X-Tenant-Id` trust in `TenantContext.php:50-56` must be removed**: it would select a database without a membership check.
- Stateful domains must cover the tenant hosts (`04` §6).

## 7. Identity projection maintenance
| Event (central) | Projection effect (tenant) |
|---|---|
| Grant created (`user_organisation_roles`) | upsert `users` subset + grant row in that tenant |
| Grant removed | delete the grant row; keep the `users` row while referenced by actor FKs |
| Profile change (name, email) | upsert in all tenants where projected |
| User deleted / GDPR erasure | fan-out: anonymise the projected row in every tenant (an FK `set null` cannot cross databases) |

**Mechanism:**
- Use the existing `UserOrganisationRole` observer hook (`AppServiceProvider.php:388`) → central outbox → per-tenant idempotent upsert.
- Plus a nightly `tenants:reconcile-identities`.
- The projection holds **no credentials**.
