# Frontend M1: read-only Reference Geography explorer (PBDIGIT-355)

| | |
|---|---|
| **Status** | **APPROVED by the Principal 2026-11-15** ("Approve as planned (Recommended)"): signed-in only, no header link, strict TDD |
| **Session** | B (frontend). Worktree `../nrna1-m1-geo`, branch `frontend/m1-geography-explorer` from `9211e7fcc`. Session A's working tree is not touched |

## Objective
Signed-in users can browse officially published places, follow parents and children, and see names per language and the "as officially known at" position. **Read-only.**

## Background
- `GeographyReadModel` (G-5a) is the published contract.
- The no-bypass guard, rule 6: delivery code reaches only application contracts.
- `knowledgeAtTime` closes the boundary, which is a short write. `knowledgeAt(position)` only reads.

## Design decisions
1. **Close once, then pin.**
   - `GET /geography` calls `knowledgeAtTime(now)` once and redirects to `/geography/k/{position}`.
   - Every later page reads with `knowledgeAt(position)`, so browsing is consistent and never writes.
   - A busy lock (`KnowledgeNotAvailable` at entry) gives 503. A position beyond the boundary gives 404.
2. **The controller depends only on `GeographyReadModel`.** It holds no domain logic: it maps DTOs to page data.
3. **Locale:** `np` maps to `ne`; `de` and `en` stay as they are. A `PLATFORM_FALLBACK` name is labelled as a fallback.
4. **Only fields the read model provides are shown.**
5. **Top level:** the places with no parent in the chosen scheme, derived from `placesAt` plus `place`. Measure it in RED; if it isn't reasonable, stop and report.

## Task checklist
- [x] 1. Web adapter, RED then GREEN (feature tests on the real publication path), then a commit
- [ ] 2. Pages and component, RED then GREEN (Vitest; design check at or below 91), then a commit (**waits for the Principal's decision on the stop condition**)
- [ ] 3. Guide, full verification, story Done, then a commit (no push)

## Progress
- 2026-11-15: plan approved; worktree created.
- 2026-11-15: **step 1 done.**
  - **RED:** 10 tests failing, because the routes were missing.
  - **GREEN:** 10 passing (147 assertions).
  - **Mutants:** 4 of 4 killed.
  - **Full Geography PostgreSQL suite:** 170 passing.
  - **Guards:** the Geography no-bypass and published-contract guards and the architecture ratchet A-1 to A-5 pass.
  - **Rest of the architecture suite:** its 72 errors are only failed database connections (the worktree has no `.env`); no failures. Guide 34.
- 2026-11-15: **STOP CONDITION REACHED (design decision 5).** 1,250 published places take about 6 s per page, growing linearly, because the contract has no top-level query and names children only per scheme. **Stopped for the Principal; the contract is unchanged.**

**Worktree finding:** a symlinked `vendor/` loads `App\` from the main tree, which is Session A's working copy. It was replaced by a real copy with `composer dump-autoload` before any implementation ran. The first RED run used the symlinked autoloader, but it ran no application code of mine, so its result is still valid.

## Risks
- The top-level derivation may be slow at real data volumes (stop condition).
- The production view stays empty until the G-8 data and D-84's owner are settled. That's intended.

## Open questions
- None for M1. Public access and the header link are deferred by the Principal's choice.

## Next actions
- Step 1, RED.
