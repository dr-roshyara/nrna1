# 03: Mobile technology comparison

> REPORT · generated · 2026-11-09. Scores are **judgement grounded in the repository evidence of `01`, `02`, `05` and `06`**, not measurements. Platform-capability statements about browsers and stores are **ASSUMPTIONS** from general platform knowledge (cutoff mid-2026) and must be rechecked when a decision is made.

## 1. Options

| | Option | What it means here |
|---|---|---|
| A | Responsive Inertia | the same app; fix mobile layout and touch interaction (PBDIGIT-311 class of defects) |
| B | PWA | A plus a manifest, installability and a static-asset service worker; web push optional |
| C1 | Capacitor **wrapper** | a native shell loading the hosted Inertia site (`server.url`) |
| C2 | Capacitor **bundled Vue app** | a separate Vite entry, Vue Router, token API, shared components, native plugins |
| D | Flutter or React Native | a new native frontend over a new API |

## 2. Evidence-based option notes

**A: Responsive Inertia.** 146 of 230 pages already use breakpoints (FACT). All server rules (eligibility, voting chain, tenancy) apply unchanged. Limits: no store presence, no native biometrics, no reliable push on iOS unless installed as a PWA (ASSUMPTION).

**B: PWA.** No manifest or service worker exists (FACT, `public/`). The risk is caching: the service worker must not cache HTML of authenticated pages, `/api/*`, or the voting routes `v/{vslug}/*` (`routes/election/electionRoutes.php:514`). Offline support is limited to an "offline" notice page. Web push needs a new backend notification channel (none exists, FACT). Installation on iOS is manual ("Add to Home Screen") (ASSUMPTION).

**C1: Capacitor wrapper.** This reuses 100% of the code but adds no native capability that B lacks, beyond plugins over a remote origin. The session cookie lives inside a WebView. Store review may reject a pure website wrapper (ASSUMPTION, Apple App Store guideline 4.2). The voting IP protections behave exactly as in a mobile browser. **Not recommended.**

**C2: Capacitor bundled Vue app.** This reuses category 1–3 code from `02` (≈17% directly, plus 20–30% after refactoring). It needs:
- a token API (`05`)
- a hardened auth lifecycle (`06` S-2)
- CORS for the Capacitor origins (`config/cors.php:22-26` today allows only localhost)
- secure storage for tokens (Keychain/Keystore plugin), Vue Router, and API adapters

It brings biometrics, camera, deep links and native push. It keeps one language and one component library.

**D: Flutter or React Native.** 0% UI reuse: all 401 Vue files would be re-expressed in another framework, with a second design system to keep in line with `.claude/UI_GUIDELINES.md`. It has the same API prerequisites as C2. It is the best for native feel, the worst for a solo developer. The documented Angular/Ionic plan (`docs/publicdigit/business_case/BUSINESS_CASE.md:221,248`) falls in this category: it is a second framework, and it is not implemented (FACT).

## 3. Weighted comparison matrix (scores 1–5)

| Criterion | Weight | A | B | C1 | C2 | D |
|---|---:|---:|---:|---:|---:|---:|
| Existing code reuse | 20% | 5 | 5 | 5 | 3 | 1 |
| Architectural quality | 20% | 3 | 4 | 2 | 4 | 3 |
| Mobile capabilities | 15% | 1 | 2 | 3 | 4 | 5 |
| Security | 15% | 4 | 3 | 2 | 3 | 3 |
| Maintainability | 10% | 4 | 4 | 2 | 3 | 1 |
| Development effort (5 = least) | 10% | 5 | 4 | 3 | 2 | 1 |
| Long-term flexibility | 10% | 2 | 3 | 1 | 4 | 4 |
| **Weighted total** | 100% | **3.45** | **3.65** | **2.75** | **3.35** | **2.60** |

**Why each score was given**
- *Reuse:* A, B and C1 reuse everything. C2 reuses only the shared layer (`02` §2). D reuses nothing in the UI.
- *Architectural quality:* B adds a clean progressive layer. C2 forces the healthy split of `Controller → use case`, a typed contract and shared UI. C1 hides a web app in a native box (two runtimes, one truth, unclear ownership of auth). A leaves channel concerns implicit.
- *Mobile capabilities:* this follows platform reach (ASSUMPTION): native > Capacitor bundled > wrapper > PWA > browser.
- *Security:* A keeps the hardened web pipeline (CSRF, throttled login, SameSite=lax cookie, `config/session.php:199`). B adds cache-poisoning and stale-auth risk. C1 brings WebView cookie handling plus store-distributed builds. C2 and D need a **new** token surface; today's token endpoints are unhardened (S-1, S-2), so they start at 3, not higher.
- *Maintainability:* one codebase for A and B. C2 has two shells and one shared library. D has two stacks for one developer.
- *Effort:* see the estimates in `08`.
- *Flexibility:* C2 and D open native features and API-first integration. A and B are bounded by the browser.

## 4. Sensitivity analysis

| Change in priorities | Result |
|---|---|
| Base weights | **B 3.65 > A 3.45 > C2 3.35** > C1 2.75 > D 2.60 |
| Capability-first: reuse 10, arch 20, **capabilities 30**, security 10, maint 10, effort 10, flex 10 | **C2 3.50** > B 3.30 > D 3.10 > A 2.90 > C1 2.60: **C2 overtakes** |
| Store presence is a hard product requirement | A and B are excluded; **C2** is the best remaining option; D only if a native UX is worth a rewrite |
| Security-first: reuse 20, arch 20, capabilities 5, **security 30**, maint 10, effort 10, flex 5 | A 3.85 > B 3.75 > C2 3.20 > D 2.35 |
| Solo-effort-first: reuse 20, arch 20, capabilities 5, security 15, maint 10, **effort 25**, flex 5 | A 4.00 > B 3.90 > C2 3.05 > D 2.05 |

**INTERPRETATION.** The ranking turns on **one product question: are store apps (and native capabilities) required?** (D-1). Without them, A/B win under every weighting tried. With them, C2 wins. D never ranks first for this repository under any weighting tried.

## 5. Recommendation
**A → B now. C2 later, only if D-1 says "store apps".** Reject C1 and D (and the Angular/Ionic plan) unless the Principal states a native-UX requirement that outweighs a full UI rewrite.
