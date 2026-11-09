# public-digit's mobile strategy: what applies to nrna1

| | |
|---|---|
| **Status** | **PROPOSED assessment.** Read-only. Decides nothing. Not independently reviewed (Claude family). |
| **Request** | Principal, 2026-11-09: "read … public-digit and find how the mobile application strategy is used there and if we can apply here". |
| **Source** | `/home/nab-raj.roshyara@dg-nexolution.de/roshyara/personal/public-digit` (HEAD `a766ef5`; `apps/mobile` last commit 2025-12-11). Reference source only (D-1 of the social-contribution plan: nrna1 is the only target). Not governed, not ratified. |
| **Limit** | `packages/laravel-backend` is an **empty sub-repository** in this checkout, so the server side (`/mapi` routes, controllers, tests) **could not be verified**. Server statements come from that project's documents only. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); `architecture/reviews/` |
| **Compared with** | `./2026-11-09-mobile-application-strategic-ddd-baseline.md` (K-1…K-12), the ratified Membership act, D-44…D-47, D-75 |

## 1. The strategy there (FACT unless stated)

| Element | What public-digit does | Evidence |
|---|---|---|
| Split by audience | Vue + Inertia desktop for administration; **Angular Mobile for "member participation & voting"**; "They NEVER share APIs" | `public-digit/CLAUDE.md:566-574` |
| Routing law | six cases; mobile only `/mapi/*` and `/{tenant}/mapi/*`; desktop `/api/*` | `CLAUDE.md:578-600` |
| Auth | Sanctum stateless tokens for mobile; CSRF excluded on `/mapi` | `CLAUDE.md:556`, comparison table |
| Tenancy | landlord DB and one DB per tenant (Spatie multitenancy); tenant slug always in the URL | `CLAUDE.md:578-600`; `mobile_architect/20251201_1844_mobile_business_architecture.md` |
| App packaging | **bundled** Angular app in Capacitor (`webDir: dist/apps/mobile/browser`, prod `androidScheme: https`, no remote server URL); Android project present | `apps/mobile/capacitor.config.prod.ts:3-12`; `apps/mobile/android/` |
| Structure | Nx monorepo; app layers domain / application / infrastructure / presentation; feature modules membership, elections, finance, communication (forums) | `apps/mobile/src/app/` (100 TypeScript files, 17 specs) |
| Token storage | Capacitor `Preferences` (described as "secure token storage"), with a `localStorage` fallback | `apps/mobile/src/app/core/services/auth.service.ts:4,14,155-178` |
| Language | detected from the device's location, first via the third-party service ipapi.co | `infrastructure/adapters/ip-geolocation.adapter.ts:13,77` |
| Voting | the app casts the vote: `POST /elections/{id}/vote` with **`voter_slug` and `candidate_ids` in one request**; the client filters by `has_voted` | `features/elections/services/election.service.ts:87-91,174-181`; `features/elections/models/election.models.ts:105-111,133` |
| Mobile registration | a mobile-specific `MobileMemberRegistrationService`; registration "creates member with **draft** status" | `architecture/backend/membership-context/membership_context_implementation/20260103_0856_mapi_vorbereitung.md:5-8,260-276` (plan, not verified code) |

## 2. What we can apply

| # | Element | Fit with nrna1 | Status here |
|---|---|---|---|
| A-1 | **Administration on the desktop, member participation on the phone** | the same principle the Principal set as DD-13 and PD-E (WD). public-digit is earlier evidence of the same product intent | **already adopted** |
| A-2 | **Bundled app, not a wrapper around the live website** | matches the mobile report's recommendation (option C2 over C1) | evidence for D-1, if a store app is ever chosen (MP-1) |
| A-3 | **A separate, versioned, token-authenticated API for the app, with the organisation in the path** | matches mobile report `05`: the session-based `/api/v1` is not a mobile API | downstream technical decision (D-4, D-6) |
| A-4 | **Thin channel controllers inside the owning context** (`app/Contexts/Membership/Infrastructure/Http/Controllers/Mobile/`) | good, **if** they call the same use case as the desktop (D-7) | a pattern to keep (see N-1) |
| A-5 | **Feature modules by business area** in the app | maps to contexts, not to a Mobile context | consistent with MD-1 |
| A-6 | language support in the app | nrna1 already has de, en, np (`resources/js/i18n.js:3-5`) | reuse the idea, not the detection (N-5) |

## 3. What must not be applied

| # | Element | Conflict | Basis |
|---|---|---|---|
| N-1 | **"They NEVER share APIs"** plus a **mobile-specific registration service** | two transports are fine; **two business operations are not**. A `MobileMemberRegistrationService` beside the desktop path would be a second version of the rules | D-7; K-2 |
| N-2 | **Mobile registration creates a member in "draft" status** | in nrna1 an application is a declaration, not a membership; "member but not yet confirmed" is answered **no** (BD-REC0); admission needs an organisation decision with authority evidence | ratified Membership act (RA:45, RA:72); REG:40; M-TDD-01 |
| N-3 | **Voting from the app, with the voter's identifier and the chosen candidates in one request** | the cast path carries identity, which is exactly what VO-1 forbids; store-app voting is not approved; D-44 separates rights issuance (Election, knows the person) from ballot submission (Voting, never knows the person) | VO-1 (RAT); D-44, D-45 (WD); `PBDIGIT-337` (the same defect class in nrna1) |
| N-4 | **A per-voter "has voted" state** exposed to the app | Election records "right issued", never "has voted"; no "has voted" audiences | D-44; MAP §24; K-7 |
| N-5 | **Language from location via ipapi.co** | sends every member's IP address to a third party (data protection), and **location is not language for a diaspora**: a Nepali member in Germany would get German | K-3 spirit; DD-6 (address ≠ belonging) by analogy. Recommend an explicit choice plus the organisation's default |
| N-6 | **Token in `Preferences` with a `localStorage` fallback** | Preferences is plain app storage, not a secure store; a "secure" comment does not make it secure | security; for D-4 later |
| N-7 | **One database per tenant and the six-case routing law** | nrna1 uses one database with `organisation_id` scoping and `organisations/{slug}` routes. The routing law cannot be transplanted as is | nrna1 CLAUDE.md (multi-tenancy) |
| N-8 | **Angular as a second framework** | the mobile report advised against a second framework. The code cannot be reused in nrna1's Vue frontend, and its domain models predate nrna1's ratified Membership and the D-44 voting design | mobile report `00` §11; D-2 (open) |

## 4. Conclusion

- **Apply the principles, not the stack or the code:**
  - the audience split (A-1, already adopted);
  - bundled rather than wrapped (A-2);
  - a separate token API (A-3);
  - thin controllers calling shared operations (A-4).
- **Do not apply** the voting flow, draft members, has-voted state, location-based language, token storage, routing law or Angular. Each conflicts with a ratified or accepted nrna1 decision.
- **Reusable code: none in practice.** The useful content is the **business intent**, which nrna1 has already absorbed in DD-13/PD-E. The forum ideas were already reconciled (Session B model §36).

## 5. For the Principal

- **D-2** (from the mobile report: retire or keep the Angular/Ionic plan) now has evidence:
  - the Angular app's voting and registration designs conflict with ratified and accepted nrna1 decisions;
  - its backend is not available here;
  - its code cannot be reused in Vue.
  - **Recommendation:** record the Angular plan as **historical**, kept as a reference source, and not continued. The technology for any future app stays open until MP-1 is answered.
- **MP-1** (open) is unchanged: what must a member do in an installed app that the phone browser cannot do?

## Principal decision (2026-11-09)
**D-2 accepted as recommended:** *"I agree with your recommendation."* The Angular/Ionic plan is historical, a reference source only, and not continued. The technology for any future app stays open until MP-1 is answered. Recorded in mobile report `10` (decision note).
