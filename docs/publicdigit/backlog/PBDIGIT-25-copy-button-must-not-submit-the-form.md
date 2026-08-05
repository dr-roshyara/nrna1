# PBDIGIT-25 — Copy button must not scroll or submit the voting form

**Type:** Bug (UX) · **Epic:** `PBDIGIT-EPIC-05` Voting (public-demo variant of step 1, code entry)
**Priority:** low effort · medium customer impact — it is on the **first screen a prospective customer sees in the public demo**
**Created:** 2026-08-05 · **Fix applied:** 2026-08-05 · **Status:** `FIXED — AWAITING RUNTIME VERIFICATION`

---

## Business value

> *As a voter, I want to copy the demo voting code without the page moving or validating the form, so I can continue the voting process smoothly.*

Because this is the **public demo**, the person hitting this bug is usually evaluating whether to buy the product.

## Where

| | |
|---|---|
| **URL** | `http://127.0.0.1:8000/public-demo/{slug}/code` |
| **Route** | `GET`/`POST /public-demo/{publicDemoSession}/code` — `routes/election/electionRoutes.php:617,618` (group opens `:606`) → `App\Http\Controllers\Demo\PublicDemoController` |
| **Page** | `resources/js/Pages/Code/DemoCode/Create.vue` |
| **Element** | the Copy button beside the displayed demo code, `:177` |

## Current behaviour (as reported, and as confirmed)

* Clicking **Copy** copies the code — *and also:*
* the page scrolls to the voting-code input,
* form validation is triggered,
* the user loses context.

**Confirmed worse than reported:** the click does not merely scroll — it **submits the form to the server**. `submit()` (`:385`) runs `form.post('/public-demo/${slug}/code')` (`:395`) with an empty or partial `voting_code`, so every Copy click issues a failing POST whose validation errors cause the error rendering and the scroll.

## Root cause

`resources/js/Pages/Code/DemoCode/Create.vue:177` — the Copy `<button>` carried `@click="copyCodeToClipboard"` but **no `type` attribute**, and it sits inside `<form @submit.prevent="submit">` (`:163`). Per the HTML specification a `<button>` inside a form defaults to `type="submit"`, so the click ran *both* the copy handler and the form submission.

**Not a Vue problem, not an architecture problem — a missing HTML attribute.**

## Expected behaviour

* Clicking **Copy** only copies the demo code to the clipboard.
* The page does not scroll.
* The form is not submitted (no network request).
* No validation messages appear.
* A short "Copied!" confirmation is shown (already implemented — `:189`).

## Acceptance criteria

| | Criterion | State |
|---|---|---|
| ☑ | Clicking **Copy** copies the code to the clipboard | unchanged by the fix (handler untouched) |
| ☑ | The form is not submitted | **fixed** — `type="button"` prevents the implicit submit |
| ☑ | No validation is triggered | **fixed** — follows from the above (validation only ran via `form.post`) |
| ☑ | Existing voting functionality continues to work | the real submit button (`:272`) keeps `type="submit"`; only two buttons exist in this form |
| ⬜ | The browser position does not change | **needs a browser** — implied by removing the submit, but scroll behaviour cannot be asserted from static evidence |

## Fix applied

```diff
  <button
+     type="button"
      @click="copyCodeToClipboard"
```
`resources/js/Pages/Code/DemoCode/Create.vue:178` (one line added).

**Checks run:** `npm run design-check` → *"All checks passed! (92 violations, threshold: 150)"* — the baseline is unchanged, i.e. the fix introduces no design-system violation.

## Defect-class sweep (deliberate, and deliberately bounded)

The same defect class was checked across the sibling demo pages. **It is not systemic — one occurrence only:**

| Location | Verdict |
|---|---|
| `Code/DemoCode/Create.vue:177` | ❌ **the bug** — no `type`, **inside** the form (`:163`) |
| `Vote/DemoVote/PublicResult.vue:62` | ✅ already `type="button"` |
| `Vote/DemoVote/PublicResult.vue:152` (`copyHash`) | ✅ harmless — outside the form (form spans `:73–117`) |
| `Vote/DemoVote/VerifyVotingCode.vue:59` | ✅ harmless — outside both forms (`:244–332`, `:351–439`) |
| `Vote/DemoVote/ThankYou.vue` | ✅ no form on the page |

**The harmless cases were left untouched** — adding `type="button"` there would be an unrequested change with no defect behind it.

## Verification outstanding

One acceptance criterion needs a human with a browser:

1. Open `/public-demo/{slug}/code`, scroll so the Copy button is visible.
2. Click **Copy** → expect: clipboard contains the code · "Copied!" appears · **no scroll** · no validation message · **no network request in the Network tab**.
3. Then enter the code and submit normally → expect the demo flow to continue as before.

**Status becomes `VERIFIED` when step 2 and 3 are observed.**

---

**Traceability:** `resources/js/Pages/Code/DemoCode/Create.vue:163,177,178,189,272,385,395` · `routes/election/electionRoutes.php:606,617,618` · `App\Http\Controllers\Demo\PublicDemoController` · draft origin: `copy_behavior.md` (superseded by this story; removed to avoid a second home)
