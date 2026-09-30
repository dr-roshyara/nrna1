# PBDIGIT-75 — Newsletter authorization never verifies the named election belongs to the organisation

**Type:** Defect (authorization) · **Epic:** `PBDIGIT-EPIC-05` Member Communication · **Created:** 2026-09-30
**Found by:** `PBDIGIT-74` semantic reconstruction (adversarial review pass)
**Raised by:** `claude-code-session:84c0f6f6-795e-4c89-a382-733f2c7b7caf` — self-declared, not attestable
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🔴 **OPEN — investigation complete for the verified part; NOT AUTHORISED FOR IMPLEMENTATION** |
| **Business problem** | An Election Chief/Deputy of **one** organisation's election can pass that `election_id` into **another** organisation's newsletter routes and be authorized, because authorization checks the officer's relationship to the *election* and never checks the election's relationship to the *organisation*. |

## Verified finding

`OrganisationNewsletterController::authorizeNewsletterAccess()` (`:432-452`):

- `admin`/`owner` of `$org` → **returns immediately**, never inspecting `$electionId`;
- otherwise, if an `ElectionOfficer` row exists for `$electionId` with role `chief`/`deputy`, status `active` → **returns**.

**Neither branch verifies `elections.organisation_id === $org->id`.** `elections.organisation_id` exists and is available. Corroborating: `audience_meta.election_id` is validated only as `exists:elections,id` (`:124-129`, `:212-218`, `:260`) — never organisation-scoped; and `NewsletterService` imports `Election` without ever using it.

**Why disclosure is limited today:** the audience queries still filter `users.organisation_id = $org->id`, so recipients remain accounts owned by the sending organisation. ⇒ **Today this is an authorization gap, not a demonstrated cross-tenant recipient leak.** ⚠️ **But it becomes one the moment `PBDIGIT-74` relaxes that predicate** — the two tickets are coupled and must not be fixed independently.

## Reviewer-reported, NOT re-verified

- `previewCount` (`:245-284`) reportedly returns `count` plus a 5-row `name`/`email` sample under `authorizeAdmin` alone — a read path needing no draft, no send and no audit entry. **Verify before acting.**
- `exists:elections,id` reportedly ignores soft deletes.

## Non-actions

⛔ No production code, test, fixture or migration changed · no authorization logic altered · `PBDIGIT-74` not pre-empted · no fix proposed or chosen.

## Required next step

Verify the `previewCount` exposure, then decide **jointly with `PBDIGIT-74`** what establishes that an election belongs to the sending organisation. Fixing either ticket alone risks opening the other.

**Traceability:** `OrganisationNewsletterController.php:124-129, :212-218, :260, :432-452` · `NewsletterService.php:202` and the 7 election audiences · `elections.organisation_id` · `PBDIGIT-74` §10.7
