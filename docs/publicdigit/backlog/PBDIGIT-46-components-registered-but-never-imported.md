# PBDIGIT-46 — Components registered but never imported crash their pages

**Type:** Defect (fixed) + a gap in an earlier story · **Epic:** cross-cutting · **Created:** 2026-08-06
**Found by:** a production-style report — `/vote/verify_to_show` threw `ReferenceError: PublicDigitHeader is not defined`

| | |
|---|---|
| **Status** | ✅ **5 live pages FIXED** · the scanner gap and the missing gate are **OPEN** |
| **Customer impact** | **Each affected page failed to load.** The Vue script threw at module evaluation, so nothing rendered |

---

## The defect

A component listed in `components: { … }` that is **never imported** is an undefined identifier. It throws at module evaluation:

```
Uncaught (in promise) ReferenceError: PublicDigitHeader is not defined
    at VoteShowVerify.vue:482
```

**The page is dead — not degraded.** Nothing after that line runs.

## The five pages

**In every case the identifier was also *unused in the template*, so the registration was dead** — the fix is to remove it, **not** to add an import. Adding one would render chrome the layout already provides.

| Page | Dead registration |
|---|---|
| `Pages/Vote/VoteShowVerify.vue` | `PublicDigitHeader`, `PublicDigitFooter` — **the reported page** |
| `Pages/Result/Index.vue` | `PublicDigitFooter` |
| `Pages/Committee/Calendar.vue` | `Welcome` |
| `Pages/Election/ElectionResult.vue` | `AppLayout` |
| `Pages/Vote/BallotAccessDenied.vue` | `ElectionLayout` |

**`VoteShowVerify.vue` had a second, opposite defect:** `PublicDigitLayout` was **imported and used in the template but never registered**. So the same block registered two components that did not exist and omitted the one that did. **Per the Product Owner's direction ("use PublicDigit Template") the layout is now registered and the header/footer registrations are gone.**

**Remaining, deliberately:** `Components/Jetstream/NrnaHeader_backup.vue` registers an undefined `NrnaFooter`. It is a `_backup` file — **dead code, and part of the 15 dead backup files `PBDIGIT-28` recorded.** Fixing it would imply it should be kept.

**Verified:** `npm run build` green (10.2s); `PublicDigitHeader` no longer appears in the page's chunk.

## ⚠️ This is a gap in `PBDIGIT-28`, and that story's conclusion was too broad

`PBDIGIT-28` closed with *"0 unresolved live imports"* — **true, and insufficient.** It scanned for **imports pointing at missing files**. This class has **no import at all**, so there is nothing "unresolved" to find. **Two different defects, one of which the scan could not see.**

> **A scan that finds nothing proves only that it looked for the wrong thing.**

## ⚠️ And my first scan of *this* class was wrong too — in the same file as last time

The first version flagged **8** files. Three were false positives: `IndexPost.vue`, `Candidacy/Index.vue` and `Voter/IndexVoter.vue` all register `Table: Tailwind2.Table`. **For a `Key: Value` entry, what must be defined is the VALUE's root identifier (`Tailwind2`), not the key (`Table`).** Checking the key reported working code as broken.

**`IndexPost.vue` is the second time a scan of mine produced a false positive about that exact file** — `PBDIGIT-28` rev 1 claimed it would fail at runtime when its imports were merely commented out. **The corrected scanner strips comments *and* resolves value identifiers.**

## 🔴 The real finding: three defect classes, no gate for any of them

| Class | Story | Caught by |
|---|---|---|
| Case-sensitive import paths | `PBDIGIT-27` | nothing — a broken home page |
| Imports pointing at missing files | `PBDIGIT-28` | an ad-hoc scan |
| **Components registered but never imported** | **this story** | **a user hitting the page** |
| Locale strings that will not compile | `PBDIGIT-44` | a user hitting the page |

**Four classes. Four ad-hoc discoveries. No standing check for any of them.** `vite build` passes in every case, because none of these is a *build* error — they are runtime errors in code the bundler happily bundles.

**Suggested, and it is the same shape as `PBDIGIT-44`'s i18n gate:** one `npm run frontend-lint` covering all four classes, wired beside `design-check` in the merge gate. **A new gate is a governance decision, so it is recorded rather than added.**

## Acceptance criteria

* [x] The five live pages load.
* [ ] A standing check for registered-but-undefined components.
* [ ] Merged with `PBDIGIT-44`'s proposed i18n gate rather than added separately — **one frontend gate, four classes.**
* [ ] Decide the dead `_backup` files (`PBDIGIT-28` recorded 15) — fix or delete, not both.

---

**Traceability:** reported error at `resources/js/Pages/Vote/VoteShowVerify.vue:482` · `Pages/Result/Index.vue:154` · `Pages/Committee/Calendar.vue:36` · `Pages/Election/ElectionResult.vue:20` · `Pages/Vote/BallotAccessDenied.vue:243` · `Components/Jetstream/NrnaHeader_backup.vue` (dead) · `PBDIGIT-27` · `PBDIGIT-28` (the gap, and the repeated false positive) · `PBDIGIT-44` (the gate to merge with)
