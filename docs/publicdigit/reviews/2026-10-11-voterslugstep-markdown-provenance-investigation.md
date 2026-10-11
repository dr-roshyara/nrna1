# Investigation: why `app/Http/Middleware/VoterSlugStep.php` contains Markdown

**Date:** 2026-10-11 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), at the Human PO's request · **Mode:** read-only investigation; this report is the only file written · **Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0), under `reviews/` by convention.
**Baseline:** branch `knowelegeos-modelling`, HEAD `71e5a931e`. The file is tracked and unmodified (`git status` empty), 802 lines, git blob `9a3e9af5c8d10521847b5dc34b7312a01c354af1`.

**Labels:** **[F]** verified fact (command or file:line) · **[I]** inference · **[U]** unknown.

## A. Executive conclusion

**The file is not a PHP class with Markdown appended. All 802 lines are one Markdown document** [F]: an implementation guide titled "Multi-Tenancy Isolation: The `organisation_id` Column", dated March 2, 2026, status "✅ Implemented & Verified" (lines 1–5).

**How it got there.** On 2026-03-04 an object-recovery run rescued the document as a stray git object, one that no commit had ever recorded under a name. The recovery tooling filed it under `code/` (not `documents/`) with the class-like name `VoterSlugStep.php`. Hours later, the batch restore `86468d3d7` copied it into `app/Http/Middleware/` [F, for each step; see B].

**Why it is in this file.** The evidence explains this as a **misclassified recovery artefact** [I, high confidence], not a deliberate placement:
- the original path is unknown and no commit records one [U];
- no document, session log, plan or story records an intention to keep it here for future implementation [F, searched; see G];
- the document describes itself as **already implemented**, not as a specification for future work.

**Your recollection** (that you kept it for future implementation) is **not contradicted by any record, but not supported by one either** [U]. Your own memory is the one source this investigation cannot check. That is why §F asks for a human decision, not an engineering one.

**The parse error at line 279** happens because PHP treats lines 1–209 as inline text. Line 210 contains `<?php`, the first line of an **embedded migration example**. From there PHP parses the example as code, until the Markdown code fence that closes it (```` ``` ````) at line 279 [F].

**Relation to the codebase.** Its subject, `organisation_id` on `voter_slug_steps`, is **implemented today in a different, superseding form** (UUID schema, NOT NULL, composite index, a tenant trait with a creating hook). What the document holds that the code doesn't: a two-organisation isolation-test recipe and some guidance text (§C).

**Two of our backlog records misdescribe the file, and one is a duplicate** [F]:
- `PBDIGIT-70` (2026-08-12) already tracks this file. It calls it a "middleware" with a "stray backtick".
- `PBDIGIT-272`, which I created earlier today, **duplicates `PBDIGIT-70`**, and wrongly says the Markdown "follows the closing `};`" and that the "code before line 279" could be restored.

Both are corrected in G.

## B. Timeline and provenance

| When | Commit | What [F] |
|---|---|---|
| 2026-03-02 (stated in the document) | — | the document's own date. **No commit contains it under any name** (`git log --all --find-object=9a3e9af5…` returns only the recovery commits below). **[I]** it was staged or created but its commit was lost, a local data loss that the "restore deleted …" commits of 2026-03-04 00:38–00:44 also suggest |
| 2026-03-04 01:41 | (folder name) | the recovery run `git_recovery_20260304_014120/`. The blob sits under `code/VoterSlugStep.php`, while recovered documents went to `documents/<hash>.md`. Also present: `recovered-files/laravel_files/app/blob-9a3e9af5….recovered`, `project_phoenix_20260304_030426/reconstruction/assets/VoterSlugStep.php`, and even `…/final/database/migrations/2026_03_04_030526_VoterSlugStep.php` |
| 2026-03-04 02:23 | `86468d3d7` "Phase 4 (Restoration): Batch recover 13 PHP application files from git recovery" | **adds `app/Http/Middleware/VoterSlugStep.php` (802 lines) in one go**. The commit message lists it as "Middleware (2): VerifyVoterSlug, VoterSlugStep". Co-authored by an AI assistant. **The Markdown was never added later: it was the whole file from the start** |
| 2026-03-04 10:00 | `a89207ddf` | commits the recovery folders (including the copies above) |
| 2026-03-04 15:09 | `ec3c756cc` | `vue_recovery_…/inspected/candidates_report.txt:410-416` lists the copy as "Candidate: …/code/VoterSlugStep.php … 24566 bytes … # Multi-Tenancy Isolation" |
| 2026-04-06 | `e93a12714`, `76837b3bb` "cleaned" | all recovery-folder copies deleted. **The copy in `app/` remained, so it is the last surviving copy of this document** |
| 2026-08-12 | `3530a2341`, `1f2abdac1` | `PBDIGIT-70` records the parse failure. Delete or repair is left to the PO; non-goal: "not wiring `VoterSlugStep` up — nothing established that it should exist at all" |
| 2026-10-11 | (uncommitted) | the `PBDIGIT-271` H2 parser scan finds it again. Named exclusion; `PBDIGIT-272` created (a duplicate) |

**Hint at the original location** [I]: its links (`./02-vote-anonymity.md`, `../../developer_guide/03-schema-changes.md`, lines 779–782) imply a numbered document two directories below the repository root, next to a `02-vote-anonymity.md`. `developer_guide/03-schema-changes.md` exists [F]. No `02-vote-anonymity.md` exists anywhere in the tree or in history [F].

## C. What the document specifies, compared with the code today

| Document claim (lines) | Today's code [F] | Verdict |
|---|---|---|
| a migration `2026_03_02_100636_add_organisation_id_…`: `unsignedBigInteger`, **nullable**, FK cascade, index on `organisation_id`, MySQL `UPDATE … JOIN` backfill (94–112, 205–279) | not in the tree and never committed. The table is created by `2026_03_05_000010_create_uuid_voter_slug_steps_table.php`: `uuid('organisation_id')`, **NOT NULL** (line 12), FK cascade (20–23), composite index `(organisation_id, voter_slug_id)` (30) | **superseded.** Do not implement: wrong key type, nullable, and MySQL syntax on a PostgreSQL platform |
| the model uses `BelongsToTenant` (44–51, 548) | `app/Models/VoterSlugStep.php:8,23`: `HasUuids` and `BelongsToTenant`; `organisation_id` fillable; relations to `voterSlug` and `election` | implemented, in a richer form |
| a `TenantScope` class applies `where organisation_id = session(...)` (170–189) | **no `TenantScope` class exists**. `app/Traits/BelongsToTenant.php:42-61` registers a closure scope named `'tenant'`: `TenantContext::get()`, falling back to the session, with a platform-organisation fallback for null / 0, and a table-qualified column | implemented differently. The document's description is outdated |
| creation assigns `organisation_id` automatically (328–355); elsewhere it says "model doesn't automatically set the value" (641–677) | **both are now true in their own way:** the trait's `creating` hook fills it when null (`BelongsToTenant.php:64-86`), and `VoterStepTrackingService.php:73-78` sets it explicitly from the voter slug | implemented, by both mechanisms. The document contradicts itself |
| `IdentifyTenant` middleware; `VoterStepController`; `Admin/ReportController`; tests `tests/Unit/Models/VoterSlugStepTest.php` and `tests/Feature/Voting/MiddlewareChainTest.php` | **none of these exist** | illustrations only; never built |
| security claims: "cross-tenant leakage is impossible", "secure by default" (788–795) | not proven by the document. A global scope can be bypassed (`withoutGlobalScopes`, raw queries, and non-request execution, cf. `PBDIGIT-211` T-1 / T-4) | **overstated; not a verified property** |

**Deferred and still useful** [I]:
1. A **two-organisation isolation test** for `VoterSlugStep` (lines 414–477). I found no test named for cross-tenant isolation of `voter_slug_steps`. Seven tests touch the model with tenant context, but none was verified to assert two-organisation isolation, so this is unconfirmed.
2. The code-review checklist (604–613) and the troubleshooting notes.

Everything else is either implemented in a different form or obsolete.

**Is this the only record of unimplemented requirements?** For the isolation-test recipe, it appears so [I]. For the schema and the model, no: the code is the record, and it supersedes this document.

## D. Current technical state

- **Path versus role:** there is no middleware. Nothing references `App\Http\Middleware\VoterSlugStep` [F]: no middleware registration, route, test or container binding; every use in `app/`, `tests/`, `routes/` and `bootstrap/` refers to `App\Models\VoterSlugStep`. The class content described in your prompt (`App\Models`, `HasUuids`, `BelongsToTenant`, `voter_slug_steps`) is **`app/Models/VoterSlugStep.php`, a different and valid file** [F]. The discrepancy is real, but it is about naming, not substance: the "middleware" file holds only the document.
- **Same-named files:**
  - `app/Models/VoterSlugStep.php` and `DemoVoterSlugStep.php`, both valid;
  - `tests/Feature/Voting/VoterSlugStepTrackingTest.php`;
  - an identical copy of the stray file in the worktree `.claude/worktrees/kos-v11-ddd/` (`cmp` identical).
- **Runtime:** PHP's class loader would only open the file if something referenced `App\Http\Middleware\VoterSlugStep`, and nothing does. Even then no class would be defined: PHP would print lines 1–209 as text, then fail to parse at line 279. **No runtime path, so no user impact** [F/I].
- **Parse check:** `php -l` → `unexpected token "`" … line 279`, exit 255 [F]. It is the only failure among the 1,754 PHP files in `app/` (rerun without aborting on errors, 2026-10-11).
- **Test impact:**
  - **Affected:** `tests/Architecture/MembershipDecisionCreationTest.php`, the R7 / R8 real-tree scan. It parses every file in `app/` and fails on an unparsable one by design.
  - **Exclusion:** the constant `UNPARSABLE_KNOWN = ['app/Http/Middleware/VoterSlugStep.php']` is excluded by name only.
  - **Guard:** `test_the_named_exclusion_still_holds_only_an_unparsable_file` fails once the file parses again, so the exclusion is temporary, narrow, and expires by itself.
  - **Not affected:** R5 (a text scan) and the other architecture tests, which strip comments with the tokenizer and never parse the file.
  - **To remove the exclusion safely:** the file is removed or made parseable on a PO decision; then the guard test fails, the constant is deleted, and the real-tree scan stays green.

## E. Risks of acting too early

| Action | What could be lost or broken |
|---|---|
| **Deleting the file** | the **only surviving copy** of the document, since its recovery copies were deleted on 2026-04-06. Git history would keep blob `9a3e9af5…` (recoverable through `86468d3d7`), but losing it from the tree means losing it in practice. The isolation-test recipe has no other home |
| **Moving it under `docs/` unchanged** | it would preserve a document that claims "✅ Implemented & Verified" and "leakage impossible", and that describes a superseded schema (nullable bigint, MySQL backfill) and a non-existent `TenantScope`. A reader could implement it as written and introduce a defect |
| **Implementing its contents** | a second migration on `voter_slug_steps` would conflict with the UUID / NOT NULL schema, and MySQL syntax would fail on PostgreSQL. It would duplicate behaviour the trait and `VoterStepTrackingService` already provide |
| **"Repairing" it into a PHP class** | `PBDIGIT-70`'s hazard: a class whose name sits next to the live `EnsureVoterStepOrder` step-order guard, mistaken for that guard. There is no intended class to restore |
| **Removing the scanner exclusion** | the real-tree scan errors again, and R7 / R8 enforcement is blocked for every Membership change |

## F. Recommendation

**Option 4: stop for a human decision**, because the deciding fact is your own memory, and there is no record to check it against.

Once you confirm the role, the evidence favours **option 2** [R]:
- move the document into an existing documentation location, after separate authorisation, **with a superseded-content header**. That header would mark the migration, the `TenantScope` text, the nullable column and the "impossible" claims as historical, and point to the current files;
- keep the isolation-test recipe as an explicit open item (a test task in the voting workstream, or an annotation on `PBDIGIT-70`);
- removing the file from `app/` then closes `PBDIGIT-70` and lets the scanner exclusion expire.

**Option 1 (preserve unchanged)** is safe for now and costs only the existing exclusion. That is the state today, and it is acceptable until you decide.

**Option 3 is not recommended:** the substantive implementation already exists in a newer form.

## G. Evidence, corrections and open questions

**Commands (all read-only):**
- `git status --porcelain --ignored`, `git ls-files`;
- `git log --all --follow`, `git log -S "Multi-Tenancy Isolation: The"`, `git log --all --find-object=9a3e9af5…`, `git hash-object`;
- `git show` of `86468d3d7`, `a89207ddf`, `ec3c756cc`, `e93a12714`, `76837b3bb`, `66826183b`;
- `git grep` for `VoterSlugStep`, `TenantScope`, `BelongsToTenant` and the heading;
- `find -iname '*voterslugstep*'`;
- `php -l`;
- `php scripts/doc-placement.php`.

**Files read:**
- `app/Http/Middleware/VoterSlugStep.php` (all of it);
- `app/Models/VoterSlugStep.php`;
- `app/Traits/BelongsToTenant.php`;
- `app/Services/VoterStepTrackingService.php:73-78`;
- `app/Services/TenantContext.php`;
- `database/migrations/2026_03_05_000010_create_uuid_voter_slug_steps_table.php`;
- `docs/publicdigit/backlog/PBDIGIT-70-…`;
- `.claude/sessions/2026-07-10.md`, `2026-08-12.md`, `2026-10-01.md`.

**Corrections to records written earlier today** (proposed only; nothing changed):
1. **`PBDIGIT-272` duplicates `PBDIGIT-70`** (ES-005.4: never create a second). The canonical search missed `PBDIGIT-70`. Proposal: mark `PBDIGIT-272` as a duplicate of `PBDIGIT-70`, and point the scanner constant, guide 08, plan §15, the evidence README, CONTEXT and the session-log references at `PBDIGIT-70`. Or keep 272 only if you prefer; that is your choice.
2. **Wrong description in `PBDIGIT-272`, the plan §15 note, the guide and the evidence README:** they say "Markdown text follows the code / the code before line 279 could be restored". **Correct:** the whole file is Markdown; line 279 is the end of an embedded example.
3. **`PBDIGIT-70`'s wording** ("middleware", "stray backtick") could get an additive note pointing to this report.

**Open questions:**
- [U] the document's original path and filename;
- [U] whether you deliberately kept it in `app/` after 2026-03-04;
- [U] whether a two-organisation `VoterSlugStep` isolation test exists under another name (not exhaustively searched).

**Decisions needed from you:**
1. **The file's role:** was it kept deliberately, and should it become a document (option 2), stay as it is (option 1), or go?
2. **`PBDIGIT-272`:** close it as a duplicate of `PBDIGIT-70` and redirect the references? (Recommended.)
3. **Authorise the record corrections** listed above, in a separate step.

Nothing in this report authorises a change.
