# PBDIGIT-28 — Resolve the remaining missing-module imports

**Type:** Bug (latent — one confirmed runtime failure) · **Epic:** cross-cutting
**Created:** 2026-08-06 · **Status:** `OPEN — BLOCKED ON INTENT DECISIONS`
**Origin:** discovered by the `PBDIGIT-27` sweep; **deliberately excluded from that commission** because every remaining site needs a decision, not a repair.

---

## Why this is a separate story

`PBDIGIT-27` fixed 17 **case-mismatch** imports — mechanical, provably correct, because a correctly-cased target existed in every case. The six sites below are different: **no target exists in any casing.** Fixing them means choosing what the code *should* import, which is a product/intent decision, not a defect repair. Extending PBDIGIT-27 to cover them would have meant inventing intent inside a bug fix.

## Customer impact

| | |
|---|---|
| **Confirmed** | `Pages/Post/IndexPost.vue` **will fail at runtime when that page is opened.** The production build survives it only because Inertia resolves page components lazily — so the failure is invisible to `vite build` and to CI |
| **Latent** | the remaining five are in dead/backup files or unvisited paths; no confirmed customer impact |

## The six findings (evidence from the 429-file / 877-import scan)

| # | Site | Missing import | Nearest existing | Decision needed |
|---|---|---|---|---|
| **1** | `resources/js/Pages/Post/IndexPost.vue:124` | `@/Components/Jetstream/Header.vue` | `Components/Jetstream/NrnaHeader.vue` | point at `NrnaHeader`, or another header, or remove the import? |
| **2** | `resources/js/Pages/Post/IndexPost.vue:125` | `@/Components/Jetstream/Footer.vue` | `Components/Jetstream/NrnaFooter.vue` | same question |
| **3** | `resources/js/Components/Jetstream/Header_backup.vue:20` | `@/Jetstream/NrnaFooter` | `Components/Jetstream/NrnaFooter.vue` (path missing the `Components/` segment) | **delete the `_backup` file**, or repair the path? |
| **4** | `resources/js/Layouts/Applayout_backup.vue:300` | `@/Components/Jetstream/Footer.vue` | `NrnaFooter.vue` | **delete the `_backup` file**, or repair? |
| **5** | `resources/js/Pages/Voter/IndexVoter.vue:246` | `../User.vue` | `Pages/User/Index.vue` (a `Pages/User/` **directory** exists; `Pages/User.vue` does not) | is `../User/Index.vue` intended? |
| **6** | `resources/js/app.js:7` | `../../vendor/tightenco/ziggy` | directory exists | **no defect** — scanner false positive; Vite resolves it via the package manifest. Listed only so the count reconciles |

**So: 5 real findings + 1 false positive.**

## Acceptance criteria

* [ ] For each of findings 1–5, the intended target is decided and recorded in this story.
* [ ] Each site either imports an existing module or the import (or the dead file) is removed.
* [ ] The import scan reports **0 real unresolved imports** (the ziggy false positive may remain, or the scanner is taught about package manifests).
* [ ] `npx vite build` stays green.
* [ ] `Pages/Post/IndexPost.vue` is **opened in a browser** and renders — the only way to confirm finding 1/2, since the build cannot see it.

## Out of scope

* Case-mismatch imports — done in `PBDIGIT-27`.
* Deleting unrelated `_backup` / `copy` files across the repository (there are several: `NrnaHeader copy.vue`, `NrnaFooter.vue_backup`, `ElectionLayout_backup.backup.vue`, `Create_backup.bak`, `Register_backup`, `Applayout_backup`, `Header_backup`). **A dead-file cleanup is its own story** — do not fold it in here.

## Recommended follow-up, not part of this story

The scan used by `PBDIGIT-27`/`PBDIGIT-28` is ~40 lines and runs in seconds. Wiring an equivalent check into CI would make this whole defect class impossible to reintroduce from a case-insensitive dev machine. **That is a new quality gate, which is a governance decision — recorded as a suggestion only.**

## Reproduction (read-only)

Resolve every relative/aliased import in `resources/js` against the filesystem (`@` → `resources/js`, per `vite.config.js:48`), trying each plausible extension and `index.*`. Current result: **429 files · 877 imports · 7 unresolved** (6 listed above + the ziggy false positive).

---

**Traceability:** `PBDIGIT-27` (the sweep that found these; its residual-findings table points here) · `vite.config.js:48` · the six sites listed above · `.claude/CLAUDE.md` §Source Code Editing Policy (why each fix must be a deliberate edit, not a pattern replace)
