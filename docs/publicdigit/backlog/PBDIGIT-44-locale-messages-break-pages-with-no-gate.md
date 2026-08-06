# PBDIGIT-44 — Locale messages can break a page at runtime, and nothing checks them

**Type:** Defect (fixed) + missing gate · **Epic:** cross-cutting · **Created:** 2026-08-06
**Found by:** the production error on `/organisation-create-tutorial`

| | |
|---|---|
| **Status** | **`@` class FIXED · `{{ }}` class OPEN · the gate OPEN** |
| **Customer impact** | 🔴 **A public page threw a `SyntaxError` and its content did not render.** Any locale string can do this, in any language, with no build-time warning |

---

## What happened

`https://publicdigit.com/organisation-create-tutorial` threw:

```
SyntaxError: Invalid linked format
```

**Cause:** `need_help_desc` contained `support@publicdigit.com`. In vue-i18n, **`@` starts a linked message** (`@:key`, `@.mod:key`, `@[key]`), so a literal `@` is a syntax error — the message compiler rejects it and the page fails.

**Fixed** by writing the address as `support(at)publicdigit.com` — which is **already the convention elsewhere in this codebase** (`locales/pages/faq/de.json`, `Pages/Election/ElectionPage.vue`).

## The class, not the instance — 30 messages across 9 namespaces

| Treatment | Where | Why |
|---|---|---|
| **`(at)`** | prose the user reads: `need_help_desc`, `help.contact_support_email`, `trust_center.dpo_email` | reads naturally; matches existing practice |
| **`{'@'}`** (vue-i18n literal interpolation) | form placeholders and CSV/import examples: `*.email_placeholder`, `rep_email_placeholder`, `section_file_format*.example` | ⚠️ **`(at)` would be actively harmful here** — a placeholder showing `mitglied(at)beispiel.de` teaches the wrong format, and an **import example** would produce broken files that users actually upload |

**All 30 are fixed.** The distinction is the point: one directive, two correct implementations, decided by what the string is *for*.

**Left alone deliberately:** `mailto:support@publicdigit.com` in `OrganisationCreateTutorial.vue`. That is an **href, not a message** — it never reaches the compiler, and escaping it would break the link.

## ⛔ Still open — a second class, found only by compiling every message

**15 messages (5 keys × 3 locales) use `{{ x }}` where vue-i18n expects `{x}`:**

| Key | Namespace |
|---|---|
| `position_card.position_label` · `position_card.required_count` · `candidate_selection.max_selection_warning` · `alerts.required_selection_error` | `pages/Vote/DemoVote/Create` |
| `preview.fix_hints.items.3` — contains `{"view":true}`, so the braces read as interpolation | `pages/Organisations/Membership/Participants/Import` |

**Severity is LOW, and the reason matters:** all four vote-page keys are **unreferenced** (0 usages in any `.vue`), and the live vote form uses a different namespace (`pages.voting.candidate_selection.*`). `fix_hints.items` **is** used, but read as raw data (`t.preview.fix_hints.items`), so it never reaches the compiler.

**So nothing user-visible is broken by this class today — it is latent.** If any of those keys is ever wired up, the user sees the literal text: a message `{{ required_number }} required` with a param of `2` renders as **`{{ required_number }} required`**, not `2 required` *(verified through the runtime)*.

## 🔴 The real finding: nothing checks locale messages

**Neither class was caught by anything.** Not a linter, not a test, not the build — `vite build` compiles the *page*, not the *messages*, because vue-i18n compiles them **lazily in the browser, on first render**. So a broken string ships silently and fails only for the users who reach that page in that language.

**This has now cost two incidents' worth of attention** and it is the same shape as `PBDIGIT-27` (case-sensitive imports broke the home page and no gate saw it).

### The gate is cheap and already prototyped

The check that found all 45 problems is ~20 lines: walk every locale JSON, hand each string to the **real** vue-i18n runtime, and fail on a compile error.

⚠️ **One trap, and it is why a naive check would have passed:** `@intlify/message-compiler`'s `baseCompile()` **accepted every one of these strings**. Only `createI18n(...).t(key)` — the actual runtime path — surfaced the errors. **The gate must use the runtime, not the compiler package.**

**Suggested:** `npm run i18n-lint`, wired beside `design-check`/`knowledge-lint`, run in the merge gate. **Adding a gate is a governance decision, so it is recorded here rather than added.**

## Acceptance criteria

* [ ] The `{{ x }}` class fixed, or the unreferenced keys deleted with a note *(they are dead — deleting may be the honest fix).*
* [ ] `npm run i18n-lint` exists, uses the **vue-i18n runtime**, and fails the build on any message that will not compile.
* [ ] It runs in CI.
* [ ] A short note in the developer guide: **`@`, `|`, `{`, `}` are vue-i18n syntax** — use `(at)` in prose, `{'@'}` when the literal character must be shown.

## Deployment note

**The source fix alone changed nothing in production.** `public/build/` is tracked and no CI job builds assets, so the running site served `app-Bu_oS0vX.js` from before the change. The fix required `npm run build` and committing the emitted assets — **worth knowing for every future frontend fix in this repository.**

---

**Traceability:** production console error, 2026-08-06 · `resources/js/locales/pages/OrganisationCreateTutorial/{de,en,np}.json:114` · 9 further namespaces (see the commit) · `resources/js/locales/pages/Vote/DemoVote/Create/*.json` · `resources/js/Pages/Organisations/Membership/Participants/Import.vue:172` · `resources/js/Pages/Tutorials/OrganisationCreateTutorial.vue:334` (the mailto, correctly untouched) · vue-i18n 9.14.5 · `PBDIGIT-27` (same shape: no gate saw it)
