# PBDIGIT-26 — Add "Copy Code" action to the Voter Verification page

**Type:** Improvement (UX consistency) · **Epic:** `PBDIGIT-EPIC-05` Voting (public demo, step 4 — verification)
**Created:** 2026-08-05 · **Implemented:** 2026-08-06 · **Status:** `IMPLEMENTED — AWAITING BROWSER VERIFICATION`

## Story

**As a voter** I want to copy my verification code directly from the verification page **so that** I can paste it into the next step without typing it manually.

**Business value:** the journey already allows copying at `/public-demo/{slug}/code`; `/public-demo/{slug}/verify` displayed the same code without the same convenience. The customer should meet one consistent interaction throughout.

---

## 1. Discover — how the existing Copy works

| Element | Evidence |
|---|---|
| Page | `routes/election/electionRoutes.php:617` → `PublicDemoController::codeShow():107` → `Inertia::render('Code/DemoCode/Create')` |
| Component | `resources/js/Pages/Code/DemoCode/Create.vue` (`<script setup>`) |
| Button | `:177` — `type="button"`, `@click="copyCodeToClipboard"`, icon swap, label `Copied!`/`Copy` (`:189`) |
| Clipboard logic | `:338-361` — `navigator.clipboard.writeText(props.verification_code)` with a `<textarea>` + `document.execCommand('copy')` fallback |
| Feedback | `codeCopied = ref(false)` (`:336`), true → reset after **2000 ms** |
| **Reusable composable/component** | **none.** Searched `resources/js/Composables`, `resources/js/composables`, `resources/js/Components` — no clipboard helper exists; the logic is local to `Create.vue` |

## 2. Compare — why the button was missing

| | `/code` | `/verify` |
|---|---|---|
| Component | `Code/DemoCode/Create.vue` | `Vote/DemoVote/Verify.vue` |
| Code prop | `verification_code` | **`debug_code`** (`PublicDemoController::verifyShow():304` ← `$session->display_code`; declared `Verify.vue:524`) |
| Code shown | yes | yes — `Verify.vue:136` |
| Copy button | yes | **no** |
| Script style | `<script setup>` | **Options API** (`export default { props, setup() }`) |

**Root cause:** nothing was broken. The code was already available to the page as `debug_code`; the Copy affordance was simply never added. The two pages were written at different times in different script styles, so the feature never carried over.

## 3. Implement

**Reuse decision:** no reusable clipboard composable exists, and *"changing clipboard implementation"* is out of scope — so the `Create.vue` pattern was mirrored in place. **`Create.vue` was not modified.** Extracting a shared `useClipboard()` composable would touch a working page and is deliberately left as a future improvement.

Three deliberate edits, all in `resources/js/Pages/Vote/DemoVote/Verify.vue`:

| # | Change | Why |
|---|---|---|
| 1 | `import { ref } from "vue";` | Options API component imported no reactivity helpers |
| 2 | `codeCopied` ref + `copyDebugCodeToClipboard()` in `setup()`, both added to `return {…}` | mirrors `Create.vue:336-361` — same clipboard call, same fallback, same 2000 ms reset; reads `props.debug_code` |
| 3 | Copy button beside `{{ debug_code }}`, wrapped in a flex row | same icons, labels and shape as `Create.vue:177-190` |

**⚠️ `type="button"` is mandatory and is commented in the code:** the block sits **inside** `<form @submit.prevent="submit">` (`Verify.vue:127`). Without it, HTML defaults to `type="submit"` — the exact defect fixed in **`PBDIGIT-25`**. This story would otherwise have reintroduced that bug on another page.

**Deliberate styling deviation:** `Create.vue` uses literal `green-*` classes; `Verify.vue`'s panel is `primary-*`. The new button uses **semantic `primary-*` tokens** to match its host page and the design-system rule, rather than copying literal greens. Shape, icons, spacing, labels and behaviour are identical.

## 4. Verify

| Check | Result |
|---|---|
| SFC parses · template compiles | ✅ `@vue/compiler-sfc` — *"parses and its template compiles cleanly"* |
| `npm run design-check` | ✅ 92 violations / threshold 150 — **baseline unchanged** (zero new violations) |
| `npx vite build` | ✅ **3490 modules transformed, built in 9.84 s** (after `PBDIGIT-27` unblocked the build) |
| `Create.vue` untouched | ✅ no diff |
| Button correctly typed inside a form | ✅ `type="button"` (PBDIGIT-25 regression prevented) |
| Copy works · feedback · no reload · scroll preserved · no console errors | ⬜ **requires a browser — not performed** |

## Acceptance criteria

| | Criterion | State |
|---|---|---|
| ☑ | Copy button visible | implemented under the same `v-if="debug_code"` that shows the code |
| ☑ | Clicking copies the verification code | reads the same `debug_code` the page renders — copied value cannot diverge from the shown value |
| ☑ | Success feedback | `Copied!` + check icon, 2 s |
| ☑ | No page reload | `type="button"` |
| ☑ | Consistent with the `/code` page | same shape/icons/labels/timing; colours follow the host page's semantic tokens |
| ☑ | No duplication of a reusable helper | none exists; `Create.vue` untouched |
| ⬜ | Scroll position preserved | needs a browser |
| ⬜ | No regression on the Code page | file untouched (static evidence); needs a click-through |
| ⬜ | No console errors | needs a browser |

**Out of scope, respected:** verification logic · routing · clipboard implementation · unrelated refactoring.

## Verification outstanding (Definition of Done)

1. Reach `/public-demo/{slug}/verify` (step 4 — `requireStep($session, 4)`), click **Copy**: clipboard = displayed code · `Copied!` ~2 s · **no scroll, no reload, no request in the Network tab** · no console errors.
2. Re-check `/public-demo/{slug}/code` still copies as before.

---

**Traceability:** `resources/js/Pages/Vote/DemoVote/Verify.vue:127,136,478,524,554+` · `resources/js/Pages/Code/DemoCode/Create.vue:177-190,336-361` · `app/Http/Controllers/Demo/PublicDemoController.php:107,274,280,304` · `routes/election/electionRoutes.php:617,629` · `PBDIGIT-25` (why `type="button"`) · `PBDIGIT-27` (build blocker cleared to allow the build check) · original commission (Jira story + Claude Code prompt) superseded by this record
