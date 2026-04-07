# Demo Election — Route Map

All demo routes are defined in `routes/election/electionRoutes.php`.

---

## Public Demo Routes (No Auth)

These routes are accessible to **anyone** — no login required.

| Method | URL | Route Name | Controller Method |
|--------|-----|-----------|-------------------|
| GET | `/public-demo/start` | `public-demo.start` | `PublicDemoController@start` |
| GET | `/public-demo/{token}/code` | `public-demo.code.show` | `PublicDemoController@codeShow` |
| POST | `/public-demo/{token}/code` | `public-demo.code.verify` | `PublicDemoController@codeVerify` |
| GET | `/public-demo/{token}/agreement` | `public-demo.agreement.show` | `PublicDemoController@agreementShow` |
| POST | `/public-demo/{token}/agreement` | `public-demo.agreement.submit` | `PublicDemoController@agreementSubmit` |
| GET | `/public-demo/{token}/vote` | `public-demo.vote.show` | `PublicDemoController@voteShow` |
| POST | `/public-demo/{token}/vote` | `public-demo.vote.submit` | `PublicDemoController@voteSubmit` |
| GET | `/public-demo/{token}/verify` | `public-demo.verify.show` | `PublicDemoController@verifyShow` |
| POST | `/public-demo/{token}/verify` | `public-demo.verify.confirm` | `PublicDemoController@verifyConfirm` |
| GET | `/public-demo/{token}/thank-you` | `public-demo.thankyou` | `PublicDemoController@thankYou` |

`{token}` is the `session_token` column of `PublicDemoSession` (resolved via route model binding).

---

## Auth-Based Demo Routes (Slug-Based, Recommended)

These require `auth:sanctum` + `verified` and pass through the full voting middleware stack.

| Method | URL | Route Name | Controller Method |
|--------|-----|-----------|-------------------|
| GET | `/v/{vslug}/demo-code/create` | `slug.demo-code.create` | `DemoCodeController@create` |
| POST | `/v/{vslug}/demo-code` | `slug.demo-code.store` | `DemoCodeController@store` |
| GET | `/v/{vslug}/demo-code/agreement` | `slug.demo-code.agreement` | `DemoCodeController@showAgreement` |
| POST | `/v/{vslug}/demo-code/agreement` | `slug.demo-code.agreement.submit` | `DemoCodeController@submitAgreement` |
| GET | `/v/{vslug}/demo-vote/create` | `slug.demo-vote.create` | `DemoVoteController@create` |
| POST | `/v/{vslug}/demo-vote/submit` | `slug.demo-vote.submit` | `DemoVoteController@first_submission` |
| GET | `/v/{vslug}/demo-vote/verify` | `slug.demo-vote.verify` | `DemoVoteController@verify` |
| POST | `/v/{vslug}/demo-vote/final` | `slug.demo-vote.store` | `DemoVoteController@store` |
| GET | `/v/{vslug}/demo-vote/thank-you` | `slug.demo-vote.thank-you` | `DemoVoteController@thankYou` |

Middleware stack for `/v/{vslug}/*`:
```
auth:sanctum, verified, SubstituteBindings,
voter.slug.verify, voter.slug.window, voter.slug.consistency,
voting.code.window, voter.step.order, vote.eligibility,
vote.organisation, election.demo
```

---

## Auth-Based Demo Routes (Non-Slug, Legacy)

These are kept for backward compatibility. Same middleware as slug-based but without the `voter.slug.*` chain.

| Method | URL | Route Name |
|--------|-----|-----------|
| GET | `/demo/code/create` | `demo-code.create` |
| POST | `/demo/codes` | `demo-code.store` |
| GET | `/demo/code/agreement` | `demo-code.agreement` |
| POST | `/demo/code/agreement` | `demo-code.agreement.submit` |
| GET | `/demo/vote/create` | `demo-vote.create` |
| POST | `/demo/vote/submit` | `demo-vote.submit` |
| GET | `/demo/vote/verify` | `demo-vote.verify` |
| POST | `/demo/vote/final` | `demo-vote.store` |
| GET | `/demo/vote/thank-you` | `demo-vote.thank-you` |

---

## Demo Entry Routes

| Method | URL | Route Name | Notes |
|--------|-----|-----------|-------|
| GET | `/election/demo/start` | `election.demo.start` | Auth required — starts demo for logged-in user |
| GET | `/election/demo/list` | `election.demo.list` | Auth required — list available demos |
| POST | `/election/demo/select` | `election.demo.select` | Auth required — start a specific demo |
| GET | `/public-demo/start` | `public-demo.start` | **No auth** — entry for anonymous visitors |

---

## Demo Results Routes

| Method | URL | Route Name | Notes |
|--------|-----|-----------|-------|
| GET | `/demo/global/result` | `demo-result.global` | Auth required — global demo results |
| GET | `/demo/result` | `demo-result.index` | Auth required — org-scoped results |

---

## How to Use Route Names in Vue

```javascript
// In Vue components (Inertia)
import { router } from '@inertiajs/vue3'

// Navigate to public demo start
router.visit(route('public-demo.start'))

// Or as href
:href="route('public-demo.start')"
```

```php
// In PHP (blade or controller)
redirect()->route('public-demo.start')
route('public-demo.code.show', $session->session_token)
```

---

## Route Decision Guide

When adding a new demo feature, ask:

```
Is the user logged in?
├── YES → Use slug-based auth routes (/v/{vslug}/demo-*)
│         Middleware: auth:sanctum + voter.slug.verify + election.demo
└── NO  → Use public-demo routes (/public-demo/{token}/*)
          No auth middleware
          Identity: PublicDemoSession (session_token)
```
