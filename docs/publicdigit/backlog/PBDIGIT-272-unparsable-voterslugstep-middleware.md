# PBDIGIT-272: `app/Http/Middleware/VoterSlugStep.php` is not valid PHP

**Type:** Defect story (standalone, repository hygiene) · **Epic:** none · **Created:** 2026-10-11 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), on the Human PO / ARB decision of 2026-10-11 during the `PBDIGIT-271` Slice 2a hardening (H2): "named exclusion + backlog". **ID note:** the next free ID (`git grep "PBDIGIT-272"` and the untracked files: no occurrence). **Index row:** added 2026-10-11.

| | |
|---|---|
| **Status** | `CLOSED` · **duplicate of `PBDIGIT-70`** (2026-10-11), which has tracked this file since 2026-08-12. Work continues there. *Correction:* the description below is wrong: the **whole file** is Markdown, and line 279 closes an embedded example; there is no "code before line 279" to restore. See `docs/publicdigit/reviews/2026-10-11-voterslugstep-markdown-provenance-investigation.md`. The test constant `UNPARSABLE_KNOWN` still names 272 and will be re-pointed to 70 in the next authorised code change |
| **Owner** | to be named (voting workstream) |
| **Found by** | the parser-based creation-path scan of `tests/Architecture/MembershipDecisionCreationTest` (`PBDIGIT-271` H2), which fails on any unparsable source in `app/` |
| **Not part of** | `PBDIGIT-271` (the Membership scan only excludes this one path, by name) |

## Finding

- `php -l app/Http/Middleware/VoterSlugStep.php` → `syntax error, unexpected token "`"` on line 279: after the closing `};` of the file, Markdown text follows (a code fence and a "Key Migration Features" section).
- Introduced by `86468d3d7` (2026-03-04, "Phase 4 (Restoration): Batch recover 13 PHP application files from git recovery").
- It is the only file of the 1 754 PHP files in `app/` that fails `php -l` (checked 2026-10-11).
- No reference found in `app/Http/Kernel.php`, `bootstrap/app.php` or `routes/`; PHP cannot load the file, so whatever it was meant to do does not happen today.

## Acceptance

- Decide whether the middleware is needed. Then either restore valid PHP (the code before line 279, with its behaviour verified by tests) or delete the file.
- `php -l` passes for every file in `app/`.
- Remove the named exclusion `UNPARSABLE_KNOWN` from `tests/Architecture/MembershipDecisionCreationTest.php`. Its guard test fails as soon as the file parses, as a reminder.
