# PBDIGIT-355: Frontend M1: read-only Reference Geography explorer

**Status:** DONE (implementation complete 2026-11-15; **the Principal's acceptance (EP-02) pending**; created 2026-11-15; plan approved by the Principal 2026-11-15, "Approve as planned") · **Context:** Reference Geography (delivery only) · **Kind:** frontend vertical slice · **Session:** B (frontend)

## Why

Reference Geography's published read contract (`GeographyReadModel`, G-5a, accepted) is the only new-architecture capability that is built, accepted and safe to show. It has no web entry point. M1 makes it visible to signed-in users, read-only, and sets the frontend pattern later screens follow:
- typed page data;
- an "as officially known at" banner;
- display names per language;
- backend refusals shown clearly.

## Scope (approved)

- Three read-only routes under `auth`:
  - `GET /geography` closes the publication boundary once and redirects to the current position;
  - `GET /geography/k/{position}` lists the top-level places;
  - `GET /geography/k/{position}/places/{unitId}` shows one place and its children.
  - The optional `?date=` is the valid date and defaults to today.
- **The controller depends only on `GeographyReadModel`** (Application\Published).
- **The pinned pages use `knowledgeAt(position)` only**, so browsing never writes.
- The frontend locale `np` is mapped to the language code `ne`. A platform-fallback name is labelled, never shown as a translation.
- Pages `ReferenceGeography/Explorer.vue` and `Place.vue`, the `KnownAtBanner` component, and typed page data, in `PublicDigitLayout`.
- Empty state: "No official geography has been published yet."

## Not in scope

- write routes;
- legacy geography screens;
- a header link;
- clutter cleanup;
- Membership;
- Identity, Organisation, Governance;
- public (signed-out) access;
- any change to the Geography contract.

**Stop condition:** if the top-level places cannot be derived reasonably through the existing contract, stop and report. Do not change the contract.

## Acceptance criteria

- Only published facts are shown; a draft or unpublished revision never is.
- An empty publication shows the empty state.
- Names appear in en/de/ne, with the fallback marked.
- Parents and children are shown and can be navigated.
- The pinned pages leave the publication clock unchanged.
- The existing architecture guards pass.
- Vitest covers the pages.
- The design check stays at or below the baseline of 91 violations.
- A developer guide is written.

## Progress

- **Step 1 (web adapter) DONE 2026-11-15:**
  - 10 feature tests;
  - 4 mutants, all killed;
  - Geography suite 170 passing;
  - guards pass;
  - guide `developer_guide/reference_geography/34_m1_web_explorer_adapter.md`.
- **Stop condition reached:** 1,250 places took about 6 s per page, growing linearly, because the contract had no top-level query.
- **Step 1b DONE 2026-11-15** (the Principal: "Extend the contract"):
  - the published queries `topLevelAt` and `childrenInAnySchemeOf`, plus a batched `slotsOfKind`;
  - place page 0.06 s, explorer 1.79 s;
  - mutants E, F and G killed;
  - guide 35.

  **The story's scope now includes this additive contract change** (it replaces "any change to the Geography contract" under "Not in scope", by the Principal's decision).
- **Step 2 (pages) DONE 2026-11-15:**
  - Vitest 10 passing; mutants H and I killed;
  - design check 91 (the baseline);
  - the production build is fine;
  - guide 36, which includes the administrator notes.
- **Step 3 (closure) DONE 2026-11-15:**
  - the full isolated cluster passes (455 tests, all PostgreSQL suites);
  - Geography unit tests: 315 passing;
  - full unit run: 0 failures involve Reference Geography (the run had no local test database).
  - Evidence: `…/2026-11-15-pbdigit355-step3/`.
- **Limitation:** responsiveness wasn't measured in a browser. The branch isn't pushed or merged.
- **2026-11-15: merged by the Principal** into `election-flow-updated` (`fa63f3ad0`). **Merging isn't acceptance.**
- **Manual acceptance test: PREPARED, NOT RUN.**
  - Guide: `docs/publicdigit/testing/manual/2026-11-15-m1-geography-explorer-manual-test-guide.md`.
  - **The local database has no published geography** (position 0), so T03–T07 are BLOCKED there; the others can be run.
  - **The stale local route cache must be cleared first** (`php artisan route:clear`).
  - **The Principal's acceptance (EP-02) is still open.**

## Plan

`docs/plans/20261115-1200-frontend-m1-reference-geography-explorer-plan.md`
