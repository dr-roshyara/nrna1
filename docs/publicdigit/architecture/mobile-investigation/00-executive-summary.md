# Mobile investigation: executive summary

| | |
|---|---|
| **Status** | **REPORT.** It records evidence and recommends. It decides, ratifies and authorises nothing. |
| **Authority** | generated (Claude, architecture investigation role). Not authoritative without Principal review. |
| **Date** | 2026-11-09 |
| **Snapshot** | branch `election-flow-updated`, HEAD `15217f4a9` (2026-11-08). Read-only: no application code, dependency, config, schema or pipeline was changed. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0). Product architecture lives in `architecture/`. The mandate named `docs/architecture/mobile-investigation/`; that path is the legacy mixed root, so the derived location was used instead. |
| **Commission** | "Desktop and Mobile Multi-Platform Architecture Assessment" (Principal, 2026-11-09). |
| **Builds on** | `docs/publicdigit/architecture/reviews/2026-11-03-publicdigit-as-is-baseline-architecture-report.md` (cited, not repeated). |

## Relationship to the strategic DDD work (added 2026-11-09, after Principal review input)
This report is a **technical (implementation-level) assessment**. It does not establish or change the business or strategic DDD baseline. That work already has canonical records, and they take precedence:
- the PROPOSED roadmap plan `docs/plans/20261108-0303-organisational-baseline-to-tactical-design-roadmap-plan.md` (organisational baseline → business-domain baseline → strategic DDD → tactical design);
- the resumption record `docs/publicdigit/architecture/strategic-design-status.md`;
- the consolidated `docs/publicdigit/architecture/context-map.md` (R-01…R-18).

**Consequences:**
- The mobile target architecture (`04`) is **conditional**. It is not to be frozen before the strategic baseline it depends on is accepted.
- The mobile question enters that roadmap as a **business input**: "which capabilities must members reach on mobile, and what do they require?". It does not start a parallel strategic model.
- The security findings S-1…S-6 do not depend on business boundaries and can be triaged independently (D-0).

**Labels:** **FACT** (verified in the repository, with file and line) · **INTERPRETATION** (reasoned from facts) · **ASSUMPTION** · **RECOMMENDATION** · **UNKNOWN**.

## The answers

**1. Is PublicDigit suitable for multi-platform evolution?** Yes, with conditions. It is one Laravel 12.70 + Inertia 2.0 + Vue 3.5 application (FACT, `composer.lock:1489`, `:1281`; `package-lock.json:6970`). Some flows already call application use cases (Membership submit/approve, FACT `app/Http/Controllers/Membership/MembershipApplicationController.php:116,300`). Other flows keep business logic in controllers or in the web session (Voting, FACT `app/Http/Controllers/VoteController.php`, 4,388 lines). The backend can serve a second channel only for flows whose logic sits behind a use case.

**2. Can the existing Vue 3 application support desktop and mobile?** As a **responsive web application or PWA: yes**, now, because it is the same app in a mobile browser. As a **standalone app bundle** (Capacitor with local assets): only the presentational layer can be reused. Every page gets its data as server-rendered Inertia props, and there is no client router and no token API.

**3. How much frontend code is realistically reusable?** Based on a static scan of all 401 `.vue` files (95,446 lines):
- **About 17% by lines can be reused directly** in a standalone app: 136 of the 171 non-page components (80% of component files, 16,376 lines) do not use Inertia, Ziggy or `usePage`.
- **About 20–30% more can be reused after refactoring**: page presentation, once its data comes through an adapter instead of server props.
- **About 50% or more stays desktop- and Inertia-bound**: page orchestration, `router.*`/`useForm`, and 151 files that use Ziggy `route()`.
- For the **responsive/PWA path, reuse is close to 100%**, because nothing is rebuilt.
- This is a file-level static measure, not a line-by-line review (method in `02`).

**4. What prevents direct reuse?** (FACT, `02`):
- Data arrives as Inertia page props.
- 124 files import `@inertiajs/vue3`; 151 use Ziggy `route()`.
- Authentication is a session cookie.
- The API client finds the tenant from Inertia props (`resources/js/services/api.js:47-80`).
- 56 files hard-code hrefs or `window.location`.
- Only 9 `.vue` files use TypeScript, and there is no shared contract layer.

**5. Is Capacitor appropriate?** **Yes, as a later step, and only as a bundled Vue app on a real token API.** Wrapping the hosted Inertia site in a Capacitor WebView is not recommended. It adds store overhead and gives little native value, and app stores may reject thin wrappers (ASSUMPTION, based on Apple's App Store guideline 4.2; to be checked at submission time). Flutter or React Native would mean rewriting all 401 Vue files, with no domain benefit.

**6. Should we introduce a PWA?** **Yes, as the cheap next step after responsive fixes:** web manifest, installability, and a service worker that caches only static assets. **It must never cache authenticated pages, eligibility, ballots or voting responses.** None of this exists today (FACT: `public/` has no manifest or service worker).

**7. Does Laravel need significant restructuring?** **No.** It needs targeted changes:
- Move business logic out of controllers, flow by flow, as each flow goes mobile (for example `MembershipApplicationController::approve` creates users and roles inside the controller, `:213-300`; `PublicMembershipApplicationController::store` writes with Eloquent directly, `:66-76`).
- Harden the existing API surface (`06`, findings S-1 to S-5).

**8. Do we need a dedicated REST API?** **Only if a store app is chosen.** Responsive and PWA use the existing Inertia controllers. The existing `/api/v1` is session-cookie-based and CSRF-exempt (FACT `bootstrap/app.php:19-22,66-68`), so it is **not** a mobile API. A mobile API would be a versioned, token-authenticated surface whose controllers call the **same use cases** as the Inertia controllers.

**9. What architecture should we implement? (RECOMMENDATION)**
- Stay in **one repository** with **no Nx and no pnpm workspace**.
- Add structured frontend folders: `resources/js/shared/` (UI, tokens, pure composables, typed contracts) and, only when the store app is approved, `resources/js/mobile/` (Capacitor entry, Vue Router, token API client).
- Backend: `Inertia controller → use case ← API controller`, one flow at a time.
- Target diagram in `04`.

**10. What should we do first?**
1. Decide the channel strategy and reconcile the documented Angular/Ionic plan (`10`, D-1 and D-2).
2. Raise the security findings S-1 to S-5 as backlog items.
3. Finish responsive quality on the member-facing flows (PBDIGIT-311 shows mobile defects already exist).
4. Add a PWA shell.
5. Only then, if approved, pilot Membership Registration and Member Profile over a token API.

**11. What should we avoid?**
- A Capacitor wrapper around the remote Inertia site.
- Any offline or cached eligibility, and any offline voting.
- Voting as the first mobile pilot.
- Duplicating business rules in API controllers.
- A second frontend framework (Angular, Flutter or React Native).
- Nx or a monorepo split before a second app exists.
- Reusing `/api/v1` session endpoints for mobile.

**12. What must the Principal decide?** Three decisions block the next step (`10`):
- **D-0** Triage the security findings S-1 to S-6 into backlog items.
- **D-1** Channel strategy: web/PWA only, or also store apps.
- **D-2** Retire or keep the documented Angular/Ionic plan and `/mapi/v1` (`docs/publicdigit/business_case/BUSINESS_CASE.md:221-248`, `FEATURE_MATRIX.md:158-167`). These are documented but not implemented anywhere in the repository (FACT: no `mapi` route, no Angular project).


Needed later:
- **D-3** Is voting ever in mobile-app scope, and how do the IP protections apply to mobile networks?
- **D-4** Mobile authentication model.
- **D-5** Pilot workflow.

## Separately reported existing defects (not modified)
Details in `06` §5.
- **S-1** `POST /api/tokens/create` has no auth middleware.
- **S-2** `POST /api/login` has no validation, no rate limit and no email-verification check, and issues tokens that never expire.
- **S-3** `POST /api/register` leaks exception text.
- **S-4** Debug routes return the session id to JavaScript.
- **S-5** Session-authenticated `/api/v1` is exempt from CSRF protection.
- Already recorded anonymity gaps **PBDIGIT-284** and **G-1** (receipt hash, `VoteController.php:1950`) stay open. Mobile must not ship voting before they are closed.

## Documents
| # | Document |
|---|---|
| 01 | [Existing architecture baseline](01-existing-architecture-baseline.md) |
| 02 | [Frontend reusability assessment and reuse matrix](02-frontend-reusability-assessment.md) |
| 03 | [Mobile technology comparison](03-mobile-technology-comparison.md) |
| 04 | [Target architecture proposal](04-target-architecture-proposal.md) |
| 05 | [Backend and API assessment](05-backend-api-assessment.md) |
| 06 | [Domain and security assessment](06-domain-and-security-assessment.md) |
| 07 | [Testing and quality strategy](07-testing-and-quality-strategy.md) |
| 08 | [Migration and implementation roadmap](08-migration-and-implementation-roadmap.md) |
| 09 | [Risks, assumptions and open questions](09-risks-assumptions-and-open-questions.md) |
| 10 | [Decisions for human review](10-architecture-decisions-for-human-review.md) |

## Investigation status
**INVESTIGATION STATUS: COMPLETE** for a static, read-only assessment. Workstreams A–H are all covered.
- **Limitations:**
  - No application was run.
  - No device or browser tests were done.
  - Reuse figures come from import/usage scans, not from reading every file.
  - Effort estimates are judgement.
  - Store-policy statements are ASSUMPTIONS, to be checked at submission.
