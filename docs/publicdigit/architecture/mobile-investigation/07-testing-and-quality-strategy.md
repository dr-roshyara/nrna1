# 07: Testing and quality strategy

> REPORT · generated · 2026-11-09. Labels: FACT / INTERPRETATION / RECOMMENDATION.

## 1. What exists (FACT)

| Layer | Evidence |
|---|---|
| PHPUnit 11: 1,133 `*Test.php` files. Suites Unit, Replay, Feature, Architecture, GreenfieldCore, EngineeringKnowledge (`phpunit.xml:8-44`). Separate PG configs (`phpunit-pgsql.xml`, `phpunit.membership-pg.xml`, `phpunit.pbdigit274.xml`) | `tests/`, config files |
| Architecture fitness: Deptrac plus `tests/Architecture/*` (e.g. `GreenfieldCoreArchitectureTest.php`) | `composer.json` `merge-gate` |
| Static analysis: PHPStan (greenfield), Infection (non-blocking) | `composer.json` scripts |
| Frontend: Vitest 4 with jsdom and `@vue/test-utils`. **4 spec files** (`PhotoEditModal.spec.js`, `Candidates.spec.js`, `Voters.spec.js`, `useElectionActions.test.ts`), plus `tests/js`, `tests/Frontend` folders | `vitest.config.js`; `find` |
| Lint and design: ESLint (`eslint-plugin-vue`), `design-check`, husky and lint-staged | `package.json` |
| CI: **no frontend job**. Merge gate is backend-only | `.github/workflows/*` |
| E2E: no Playwright, Cypress or Dusk found | `package.json`, `composer.json` |

## 2. Gap (INTERPRETATION)
The backend has a disciplined test platform. **The frontend is almost untested and not gated in CI.** Moving components into `shared/` without tests would make regressions in desktop invisible. This gap must close **before** component extraction (roadmap Phase 1).

## 3. Proposed testing pyramid (RECOMMENDATION)

```
              ┌──────────────┐  E2E: Playwright (web + mobile viewports);
              │   few E2E    │  Appium/Maestro or Capacitor device smoke (Phase 8)
            ┌─┴──────────────┴─┐ API contract tests: OpenAPI schema ↔ PHPUnit feature tests;
            │ contract / API   │ client contract tests against the same schema (Vitest)
          ┌─┴──────────────────┴─┐ Component tests: Vitest + Vue Test Utils for shared/ui
          │ component (shared/)  │ and shared/features views (props in / events out)
        ┌─┴──────────────────────┴─┐ Unit: PHPUnit domain/use cases (existing);
        │  unit (PHP + TS domain)  │ Vitest for shared/domain, composables, adapters
        └──────────────────────────┘
```

## 4. Specific tests to add, per phase

| Area | Tests | Tool |
|---|---|---|
| CI | add a `frontend` workflow: `npm ci && npm run lint && npx vitest run && npm run design-check` | GitHub Actions |
| Shared boundary | a lint rule test: `shared/**` does not import `@inertiajs/*`, `ziggy-js` or `@capacitor/*` | ESLint (+ a fitness test like Deptrac) |
| Responsive | Playwright viewport matrix (360×800, 390×844, 768×1024, 1366×768) on the member flows; axe-core accessibility checks | Playwright + `@axe-core/playwright` |
| PWA | the service worker never caches `/api/*`, `v/*`, or authenticated HTML (a Playwright test intercepting the cache) | Playwright |
| Mobile API | per endpoint: auth required; token expiry; revoked token → 401; **two-TenantId isolation** (project Rule 9); throttle; idempotency replay returns the same response; error schema | PHPUnit Feature (RefreshDatabase, `--env=testing` only) |
| Use-case parity | the same use case is invoked by the Inertia and API controllers (a spy or architecture test: API controllers may not use `Eloquent::create`) | PHPUnit Architecture |
| Anonymity | extend the anonymity fitness tests to any new API, push and device tables: no join path from device or push token to `votes`/`results` | PHPUnit Architecture (pattern of `tests/Feature/VoteAnonymityTest.php`) |
| Native | build smoke on Android emulator and iOS simulator; deep link opens the right screen; secure storage round-trip | Capacitor + Maestro/Appium (choice deferred) |
| Security | OWASP MASVS checklist (storage, network, auth, platform) before store submission; ZAP baseline on the mobile API | manual + OWASP ZAP |

## 5. Reuse of existing tests
- **All backend domain and use-case tests stay valid.** The mobile API calls the same use cases.
- Membership use-case tests remain the oracle for the API pilot.
- The existing Vitest specs remain. Desktop behaviour around the extracted components needs **characterization tests written before the move**.
