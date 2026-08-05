# PBDIGIT-28 — Resolve the remaining missing-module imports

**Type:** Bug (dead code) · **Epic:** cross-cutting
**Created:** 2026-08-06 · **Closed:** 2026-08-06 · **Status:** `FIXED — 0 unresolved live imports; build green`
**Origin:** the `PBDIGIT-27` sweep. **Revision 2** — the first issue of this story overstated the defect; see §0.

---

## 0. Correction to Revision 1 (recorded, not hidden)

| Claim in rev 1 | Status | Cause |
|---|---|---|
| *"`Pages/Post/IndexPost.vue` **will fail at runtime** when opened"* | **WITHDRAWN — FALSE.** Both imports were **commented out** (`// import …`), the identifiers appeared nowhere else, and the page renders inside `<nrna-layout>` with `NrnaLayout` correctly registered (`:136`). The page was never broken | my scan matched **commented-out lines**. It reported dead code as live defects |
| *"6 findings: 5 real + 1 false positive"* | **CORRECTED to: 0 live defects in reachable code.** 3 findings were comments · 2 were live imports inside unreferenced `_backup` files · 1 was a scanner false positive | same cause |
| Severity | **downgraded** from "build/run blocking" to **dead-code cleanup** | — |

> **The irony is worth recording:** the scanner made exactly the mistake `PBDIGIT-25` was about — a text search matching a **comment** and reporting it as code. A grep found `DB::table` in a comment there; my scanner found `import …` in a comment here. **Same defect class, committed by the tool built to find defects.** The scanner was fixed (comment stripping + package-manifest resolution) before this story was closed.

## 1. Why this was a separate story from PBDIGIT-27

`PBDIGIT-27` fixed 17 **case-mismatch** imports — mechanical and provably correct, since a correctly-cased target existed. These sites had **no target in any casing**, so closing them required deciding intent (repair? delete? re-point?). That decision belonged in its own story, not inside a bug fix.

## 2. Findings and disposition

| # | Site | Import | Truth | Disposition |
|---|---|---|---|---|
| 1 | `Pages/Post/IndexPost.vue:124` | `@/Components/Jetstream/Header.vue` | **commented out**; page already has chrome via `<nrna-layout>` | **dead line deleted** |
| 2 | `Pages/Post/IndexPost.vue:125` | `@/Components/Jetstream/Footer.vue` | commented out | **dead line deleted** |
| 3 | `Components/Jetstream/Header_backup.vue:20` | `@/Jetstream/NrnaFooter` | **live** broken import, but the file has **0 references** anywhere | **file deleted** |
| 4 | `Layouts/Applayout_backup.vue:300` | `@/Components/Jetstream/Footer.vue` | live broken import, **0 references** | **file deleted** |
| 5 | `Pages/Voter/IndexVoter.vue:246` | `../User.vue` | commented out | **dead line deleted** |
| 6 | `app.js:7` | `../../vendor/tightenco/ziggy` | **no defect** — resolves via the package manifest | **scanner taught** to resolve directories with a `package.json` |

### Decision recorded (the question rev 1 asked)

> *"Point `IndexPost.vue` at `NrnaHeader`/`NrnaFooter`?"* — **No.** The imports were comments, so re-pointing them would be a no-op; and activating them would render a **second** header and footer inside `NrnaLayout`, which already supplies both. **Deleting the dead lines is the correct resolution**, and it changes no rendering.

## 3. Verification

| Check | Before | After |
|---|---|---|
| Unresolved **live** imports (comments excluded) | 2 | ✅ **0** |
| Files scanned · live imports | 429 · 847 | 427 · 839 (2 dead files removed) |
| `npx vite build` | green | ✅ green — **3490 modules · 9.74 s** |
| Rendering behaviour | — | unchanged: only comments and unreferenced files were removed |

## 4. Acceptance criteria

* [x] Intent decided and recorded for every finding (§2).
* [x] Each site either imports an existing module or the dead import/file is removed.
* [x] The scan reports **0** real unresolved imports.
* [x] `npx vite build` stays green.
* [x] ~~`IndexPost.vue` opened in a browser~~ — **no longer required**: the imports were comments, so there is no runtime risk to confirm. *(Criterion retired with its reason, not silently dropped.)*

## 5. Out of scope — remaining dead files (a separate story if wanted)

**15 further backup/copy files carry 0 live references** and were **not** deleted; repo-wide dead-file cleanup was declared out of scope in rev 1 and stays that way:

`Components/Jetstream/{AuthenticationCardLogo_backup.vue, NrnaHeader_backup.vue, NrnaHeader copy.vue, NrnaHeader.vue.backup, NrnaFooter.vue_backup}` · `Components/Profile/MainContent copy.vue` · `Components/Upload/{IconUpload copy.vue, ImageUpload copy.vue}` · `Layouts/{ElectionLayout_backup.backup.vue, LoginLayout.vue_backup, NrnaLayout.vue_backup}` · `Pages/Auth/Register_backup.vue` · `Pages/Election/ElectionPage.vue.bak` · `Pages/Vote/{CreateVotingPage.vue.bak, DemoVote/Create_backup.bak.vue}`

⚠️ **Caveat for whoever takes that story:** a naive `grep <basename>` overstates references — `NrnaLayout.vue_backup` appears to have 34 "references" because its basename matches the **live** `NrnaLayout.vue` imports. Files with odd extensions (`.vue_backup`, `.vue.bak`, `.vue.backup`) cannot be imported as components anyway. **Check by exact filename, not basename.**

## 6. Recommended follow-up (not implemented)

Wiring the (now comment-aware) import scan into CI would make the `PBDIGIT-27` defect class unable to return from a case-insensitive dev machine. **A new quality gate is a governance decision** — recorded as a suggestion only.

---

**Traceability:** `PBDIGIT-27` (the sweep) · `PBDIGIT-25` (the same comment-matching defect class) · `resources/js/Pages/Post/IndexPost.vue:124,125` (deleted) · `resources/js/Pages/Voter/IndexVoter.vue:246` (deleted) · `resources/js/Components/Jetstream/Header_backup.vue` · `resources/js/Layouts/Applayout_backup.vue` (both files deleted, 0 references) · `vite.config.js:48` · `.claude/CLAUDE.md` §Source Code Editing Policy
