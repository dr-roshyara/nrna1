# 02: Frontend reusability assessment

> REPORT · generated · 2026-11-09 · snapshot `15217f4a9`. Labels: FACT / INTERPRETATION / ASSUMPTION / RECOMMENDATION / UNKNOWN.

## 1. Method and limits
These are static scans over `resources/js` (grep for imports and usage). A file counts as **Inertia-coupled** if it imports `@inertiajs/vue3` or calls Ziggy `route(` or `usePage`.
- **Limits:**
  - No file was executed.
  - Transitive coupling through child components was not traced.
  - Backup files ending in `.vue` (for example `Register_backup.vue`) are counted, which slightly inflates the totals.
- The figures measure **dependency surface**, not effort.

## 2. Coupling measurements (FACT)

| Signal | Files |
|---|---:|
| import from `@inertiajs/vue3` | 124 |
| Ziggy `route(` | 151 |
| `usePage(` | 45 |
| `useForm(` | 36 |
| `router.visit/post/get/put/patch/delete/reload` | 60 |
| `<Link` | 51 |
| `fetch(` | 24 |
| `axios` | 13 |
| `window.location` | 22 |
| `localStorage` | 15 |
| hard-coded `href="/…"` or `window.location.href` | 56 |
| `document.` | 61 |
| `lang="ts"` in `.vue` | 9 |

| Population | Total | Free of Inertia/Ziggy/usePage | Lines |
|---|---:|---:|---:|
| Non-page components and layouts | 171 | **136 (80%)** | 16,376 of 95,446 (**≈17%**) |
| Pages | 230 | 84 do not import Inertia or Ziggy. **All 230 depend on server-rendered props** | — |

**INTERPRETATION.** A page that does not import Inertia is still not portable on its own: its `defineProps` are filled by `Inertia::render(...)` in a controller. To reuse it in a standalone app, something must fetch the same data shape from an API. That is "reusable with architectural refactoring", not "directly reusable".

## 3. Why Inertia dependencies block direct reuse in a standalone Capacitor app (FACT → INTERPRETATION)

| Dependency | What it assumes | Standalone app effect |
|---|---|---|
| `usePage().props` and shared props (`HandleInertiaRequests`) | the server sends auth, organisation, flash and locale on every visit | no server visit means no props; needs an API and a store |
| `router.visit/post` | the server decides the next page (a 302 redirect becomes a new page component) | needs a client router (Vue Router) and API responses with explicit outcomes |
| `useForm` | a 422 response becomes `form.errors`, flash comes from the redirect, and the CSRF cookie is set | needs a form composable mapped to the API error schema (`bootstrap/app.php:157-165` already defines `{error, code, details}` for `/api/v1`) |
| Ziggy `route()` | the route list comes from the server; URLs are relative to the web origin | mobile screens must not build web URLs; use an API client plus router names |
| Partial reloads (`only:`) | server-side evaluation of lazy props | replace with explicit API calls |
| Session cookie and `XSRF-TOKEN` (`app.js:69-80`) | same origin as the page | a Capacitor origin (`capacitor://localhost`, `https://localhost`) is cross-origin; cookie auth is fragile there, and CORS currently allows only localhost dev origins (`config/cors.php:22-26`) |
| `api.js` tenant from Inertia props (`services/api.js:47-80`) | page props exist | needs a tenant source independent of the platform |

**Conclusion (INTERPRETATION):** These dependencies **prevent direct reuse of pages** in a standalone Capacitor app. They do **not** prevent reuse of presentational components, design tokens, pure composables or TS domain helpers.

## 4. Reuse categories

| Category | What falls in it (evidence) |
|---|---|
| **1 Directly reusable** | Canonical UI components: `Components/Button.vue`, `Card.vue`, `Input.vue`, `Modal.vue`, `StatusBadge.vue`, `EmptyState.vue`, `StatCard.vue`, `ToggleSwitch.vue`, `ProgressBar.vue` (verified free of Inertia/Ziggy). `design-tokens/tokens.json`. `Components/Organisation/*` (9, none coupled). `Components/Geography/*` (1, none coupled). `locales/` and the i18n setup. `types/*.ts`. `Domain/Election/*.ts`. `Constants/`. |
| **2 Reusable with minor adaptation** | `composables/useElectionCapabilities.ts` (pure and typed, but fed by Inertia props: give it a source parameter). `services/geographyApi.js` (already an abstraction layer; uses `fetch` with `credentials: 'same-origin'`: make the base URL and auth injectable). `stores/geographyStore*.js` (three versions exist; consolidate first). |
| **3 Reusable with architectural refactoring** | Page presentation for Membership (`Pages/Organisations/Membership/*`, 25 files, 18 coupled, 16 with `router.*`), Profile (`Pages/Profile/Show.vue`), Members (`Pages/Members/Index.vue`), Committee (`Pages/Committee/*` 7, `Components/Committee/*` 3), Elections information (`Pages/Election/*` 22, `Pages/Elections/*` 4). Pattern: split each page into a **container** (data and navigation, platform-specific) and a **view** (props in, events out, shared). |
| **4 Desktop-specific** | Admin and management pages (`Pages/Admin`, election management, voter import, finance administration, `Pages/Organisations/*` admin screens: 54 files, 40 coupled), Jetstream scaffolding (`Components/Jetstream/*` 37), layouts (`Layouts/*`), the TipTap editor and d3/chart dashboards. |
| **5 Mobile-specific (to be built)** | Mobile shell and navigation (tab bar), API auth client with secure token storage, push registration, deep-link handler, camera or file picker adapter, biometric unlock. None exists today. |
| **6 Unknown** | Voting pages (`Pages/Vote/*` 32 files, 13 `useForm`), because they depend on server-session ballot state (see `06`); behaviour under a token API is undefined. Upload components (`Components/Upload/*`, 5 of 6 coupled, 4 `useForm`): the mobile file and camera path is not designed. |

## 5. Reuse matrix by workflow

| Workflow | Existing Vue | Composables | Backend dependency | Inertia-specific | Browser-specific | Mobile incompatibilities | Strategy (RECOMMENDATION) |
|---|---|---|---|---|---|---|---|
| Authentication | `Pages/Auth/*` (10, all coupled, 4 `useForm`) | none dedicated | `LoginController` (session, throttle, verification); `/api/login` (unhardened, S-2) | `useForm`, `route()` | cookie, `window.location` on `auth:expired` (`app.js:63-66`) | session cookie cross-origin; no token lifecycle | Web/PWA: reuse as is. App: new auth screens on shared `Input`/`Button`, backed by a **new hardened token endpoint**, not `/api/login` |
| User profile | `Pages/Profile/Show.vue` (Jetstream style) | — | Jetstream/Fortify profile routes | coupled | — | Fortify features are empty (`config/fortify.php:133`) | Category 3: build a profile view component plus an API read model |
| Membership registration | `Pages/Organisations/Membership/Apply.vue`, `PublicApply.vue` | none | `MembershipApplicationController::store` → `SubmitMembershipApplication` use case (`:116`); `PublicMembershipApplicationController::store` → **direct Eloquent** (`:66-76`) | `router`/`useForm`, `route()` | — | the public path has no use case to share | **Pilot candidate.** Extract the public-apply logic into a use case first, then add an API controller over both use cases |
| Membership management | `Pages/Organisations/Membership/Applications/*`, `Dashboard`, `Types`, `Roles`, `Participants` | — | `approve` builds User, OrganisationUser and UserOrganisationRole **inside the controller transaction** (`:213-300`) | heavy `router.*` | — | admin-heavy | Desktop-specific for now |
| Committee management | `Pages/Committee/*`, `Components/Committee/*`, `CommitteeMemberManager.vue` | — | `/api/v1/governance/committees/{id}/members` (session API) | partial | — | session API, CSRF-exempt (S-5) | Desktop-specific. Later category 3 over a token API |
| Geographic navigation | `Components/Geography/*`, `services/geographyApi.js`, `services/geography.ts`, `stores/geographyStore*.js` | store | `routes/geography/geographyApiRoutes.php` | none | `fetch` same-origin | base URL and credentials hard-wired | **Category 2.** Best shared-library seed |
| Election information | `Pages/Election/*`, `Pages/Elections/*`, `Components/Election/*` (15, 5 coupled) | `useElectionCapabilities.ts`, `useElectionActions.ts` | Inertia controllers pass capability state | `router` in 9 + 3 pages | — | capabilities arrive as props | Category 3. Capabilities **must stay backend-computed**: reuse the ACL composable and supply its source through the API |
| Voting screens | `Pages/Vote/*` (32), `Components/Voting/VotingProgress.vue` | — | `VoteController` (session-held ballot, 11-middleware chain) | `useForm` in 13 | IP-dependent protections | session state; IP binding and limits vs. mobile networks (`06`) | **Not in pilot.** Web/PWA only until D-3 is decided |
| Governance workflows | `Pages/Organisations/*` governance screens, `GovernanceSetupController` | — | mixed | coupled | — | admin-heavy | Desktop-specific |
| Document upload | `Components/Upload/*` (6; `vue-advanced-cropper`, `vue-image-crop-upload`) | — | `UploadController`, `ImageController` | `useForm` multipart | `FileReader`/DOM | camera and file picker differ; large uploads on cellular | Category 6 → design a platform file adapter |
| Notifications | none in frontend | — | Mail only | — | — | no push infrastructure | Category 5 (new) |

## 6. Composables and Inertia: the specific question
`useForm`, `usePage`, `router.visit`, `router.post`, shared props and partial reloads are **Inertia protocol features**. They cannot run without an Inertia server response. A standalone Capacitor bundle could technically point Inertia at the remote server, but then the app *is* the hosted website. This is the wrapper option, rejected in `03`.

**Therefore (RECOMMENDATION):** shared code must not import `@inertiajs/vue3` or call `route()`. That rule is enforceable with an ESLint `no-restricted-imports` rule on `resources/js/shared/**` (proposal only; not implemented).
