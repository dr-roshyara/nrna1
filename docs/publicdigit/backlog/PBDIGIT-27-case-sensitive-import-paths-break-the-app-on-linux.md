# PBDIGIT-27 — Case-sensitive import paths break the app on Linux

**Type:** Bug (build/run blocking) · **Epic:** cross-cutting (blocks `PBDIGIT-00` end-to-end verification)
**Severity:** **was HIGH — the home page and the production build were both broken**
**Created / fixed:** 2026-08-06 · **Status:** `FIXED — build green; 6 residual findings recorded below`

---

## Customer impact (why this is a product bug, not tidying)

Visiting `http://127.0.0.1:8000/` returned a Vite overlay instead of the site, and `npx vite build` failed outright. **Nothing could be demonstrated to a customer and no browser verification of any story was possible** — including `PBDIGIT-00`, on which the whole product backlog is gated.

## Root cause

Import specifiers whose **case did not match the filesystem**. macOS and Windows resolve them anyway (case-insensitive filesystems); **Linux does not**, so the defects were invisible until the app ran here.

The repository's actual convention, confirmed from resolving imports: **`Pages/` and `Components/` capitalised · `composables/` lowercase.**

## Evidence — systematic sweep, not spot fixes

A read-only scan resolved **every** relative/aliased import in `resources/js` against the filesystem (`@` → `resources/js` per `vite.config.js:48`), trying each plausible extension and `index.*`:

```
Scanned 429 files · 877 relative/aliased imports
Unresolved: 26   →   after fixes: 7
```

### Fixed (17 sites across 7 files)

| File | Wrong | Correct |
|---|---|---|
| `resources/js/i18n.js:65-67` | `./locales/pages/election/show/{de,en,np}.json` | `…/Election/show/…` |
| `resources/js/Pages/Welcome.vue:78` | `@/pages/Dashboard.vue` | `@/Pages/Dashboard.vue` |
| `resources/js/Pages/Welcome.vue:82-88` | `@/components/Welcome/*.vue` (7×) | `@/Components/Welcome/*.vue` |
| `resources/js/Components/Header/Welcome.vue:41` | `@/pages/Dashboard.vue` | `@/Pages/Dashboard.vue` |
| `resources/js/Components/Header/Welcome.vue:45-51` | `@/components/Welcome/*.vue` (7×) | `@/Components/Welcome/*.vue` |
| `resources/js/Pages/Election/Management.vue:1061` | `@/Composables/useElectionCapabilities` | `@/composables/…` (lowercase — reverse direction) |
| `resources/js/Pages/Election/Partials/StateMachinePanel.vue:224` | `@/Composables/useElectionCapabilities` | `@/composables/…` |
| `resources/js/Pages/Tutorials/GovernanceLevelsTutorial.vue:199` | `@/components/GovernanceLevels/ExampleCard.vue` | `@/Components/…` |
| `resources/js/Pages/User/Index.vue:556` | `import Electionlayout from "@/Layouts/Electionlayout.vue";` | **line deleted** — see note |

**The `User/Index.vue` case was not a case fix.** `:555` already imported `ElectionLayout` from the correctly-cased path and `:676` registers it; the lowercase line was a **dead duplicate import** — the identifier appeared nowhere else in the file. Correcting its case would have left a redundant second import, so the line was removed instead. *(Found by checking identifier usage before editing, not by pattern-matching the path.)*

### Residual findings — NOT fixed, deliberately (6) → **carried to [`PBDIGIT-28`](PBDIGIT-28-resolve-remaining-missing-module-imports.md)**

These are **genuinely missing modules**, not case mismatches. No correctly-cased target exists, so any fix would require **deciding intent** — which would be inventing, not repairing. **They are now a backlog story of their own (`PBDIGIT-28`), not an open loose end of this one.** The table below stays as this story's evidence; `PBDIGIT-28` owns their disposition.

| Site | Missing import | Note |
|---|---|---|
| `Pages/Post/IndexPost.vue:124` | `@/Components/Jetstream/Header.vue` | absent. Nearest existing: `NrnaHeader.vue`. **This page will fail if visited** |
| `Pages/Post/IndexPost.vue:125` | `@/Components/Jetstream/Footer.vue` | absent. Nearest existing: `NrnaFooter.vue` |
| `Components/Jetstream/Header_backup.vue:20` | `@/Jetstream/NrnaFooter` | path is missing the `Components/` segment; file lives at `Components/Jetstream/NrnaFooter.vue`. **In a `_backup` file — likely dead code** |
| `Layouts/Applayout_backup.vue:300` | `@/Components/Jetstream/Footer.vue` | same absent target; **`_backup` file** |
| `Pages/Voter/IndexVoter.vue:246` | `../User.vue` | `Pages/User.vue` does not exist (`Pages/User/` directory does). Probably meant `../User/Index.vue` — **not assumed** |
| `resources/js/app.js:7` | `../../vendor/tightenco/ziggy` | **false positive of the scanner** — the directory exists and Vite resolves it via its package manifest; no defect |

**Why the build is green despite the first two:** Inertia page components are resolved lazily, so `IndexPost.vue`'s missing imports do not break the build graph — they will break **at runtime when that page is opened**. Recorded rather than guessed.

## Verification

| Check | Before | After |
|---|---|---|
| Unresolved imports (429 files, 877 imports) | **26** | **7** (6 genuine + 1 scanner false positive) |
| `npx vite build` | ❌ failed at 644 modules (`useElectionCapabilities`) → then 658 (`Electionlayout.vue`) | ✅ **3490 modules transformed · built in 9.84 s** |
| `npm run design-check` | 92 / 150 | ✅ 92 / 150 — unchanged |
| Home page `/` | ❌ Vite overlay | ⬜ **needs a browser reload to confirm** |

## Working style honoured

Search and verification were automated (the 429-file scan, run before and after). **Every edit was made deliberately in the editor**, one located site at a time — no `sed -i`, no regex sweep. This mattered: adjacent lines contained `voting-election/`, `ElectionNavigation/` and the correctly-cased `@/composables/useMeta`, all of which a broad pattern could have damaged. Policy: `.claude/CLAUDE.md` §Source Code Editing Policy.

## Recommended follow-up (not done — needs decisions)

1. **Decide the intent** for the 6 residual findings (point `IndexPost.vue` at `NrnaHeader`/`NrnaFooter`? delete the `_backup` files? fix `IndexVoter.vue`'s `../User.vue`?) — each is a one-line change once the intent is known.
2. **Prevent recurrence:** the scan used here is ~40 lines and runs in seconds; wiring an equivalent check into CI would make this defect class impossible to reintroduce on a case-insensitive dev machine. *(Recorded as a suggestion, not implemented — it would be a new gate, which is a governance decision.)*

---

**Traceability:** `vite.config.js:48` (`@` alias) · `resources/js/i18n.js:65-67` · `resources/js/Pages/Welcome.vue:78,82-88` · `resources/js/Components/Header/Welcome.vue:41,45-51` · `resources/js/Pages/Election/Management.vue:1061` · `resources/js/Pages/Election/Partials/StateMachinePanel.vue:224` · `resources/js/Pages/Tutorials/GovernanceLevelsTutorial.vue:199` · `resources/js/Pages/User/Index.vue:555,556,676` · `.claude/CLAUDE.md` §Source Code Editing Policy · `docs/pks/2026-08-05-manual-code-editing-observation.md` · blocks `PBDIGIT-00`, enabled the build check in `PBDIGIT-26`
