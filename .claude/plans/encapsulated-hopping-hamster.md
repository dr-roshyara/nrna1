# Election Voter Notices — Reuse the Existing Newsletter System

*(Replaces the previous Voters List plan in this file — that feature is already implemented, tested, and committed across multiple commits this session, most recently `8c4190aa5`. This is a new, unrelated task.)*

## Context

The user asked: *"there is a group email or newsletter system in membership application. can we integrate that system also in Election system. election system needs to send email notice to all voters."*

## Research findings (grounds every decision below)

**The capability already exists — fully — and is already election-aware.** This is a Canonical Discovery result, not a gap:

- `App\Services\NewsletterService` (`app/Services/NewsletterService.php:29-44`) defines `AUDIENCE_TYPES` that already include **7 election-specific audiences**: `election_voters`, `election_not_voted`, `election_voted`, `election_candidates`, `election_observers`, `election_committee`, `election_all` — alongside the Membership audiences (`all_members`, `members_full`, `members_associate`, `members_overdue`) and org-role audiences (`org_participants_staff`, `org_participants_guests`, `org_admins`). Verified directly (`grep` on the constant, lines 29-44 and the `match`/`when` dispatch at 123-129).
- The election-audience queries (`~188-308`) join `ElectionMembership`/`ElectionOfficer` on `election_id` (read from `audience_meta['election_id']`), filter by `role` (`voter`/`candidate`/`observer`) and `has_voted`, and still scope by `users.organisation_id` — i.e. exactly "all voters of election X," "voters who haven't voted," etc., already implemented.
- Delivery is a full queued-campaign pipeline: `createDraft()` → `OrganisationNewsletter` row → `dispatch()` resolves the audience fresh, bulk-inserts `NewsletterRecipient` rows (idempotency key, consent source), dispatches `DispatchNewsletterBatchJob` (queue `emails-normal`), which fans out `SendNewsletterBatchJob` per 50-recipient batch → `App\Mail\OrganisationNewsletterMail` via `Mail::to()->send()`, with per-recipient locking, retry/backoff, a kill-switch, and `NewsletterAuditLog` entries at each transition.
- The existing UI already supports this end-to-end: `OrganisationNewsletterController::create()` (`app/Http/Controllers/Membership/OrganisationNewsletterController.php:46-81`) already passes the org's `Election` list and the full election-audience labels to `Organisations/Membership/Newsletter/Create.vue`; `store()` (`:83-109`) already validates `audience_meta.election_id` as `required_if` for every election audience type.

**The actual gap is authorization/reachability, not capability.** `authorizeAdmin()` (`:339-348`) requires `UserOrganisationRole` = `admin` or `owner` for the organisation — nothing else. This means:
- Today, an org admin/owner *can already* send a notice to "All Election Voters" for any election in their org, via `/organisations/{org}/newsletters/create`.
- An **Election Chief or Deputy who is not also an org admin/owner has no way to trigger this** — election administration everywhere else in this app (`ElectionPolicy::manageVoters`/`manageSettings`, used throughout this session's suspension/removal/reinstatement work) is gated per-election via `ElectionOfficer` role, a completely separate actor set from `UserOrganisationRole`.
- There is no entry point for this feature reachable from within the Election management screens (`ElectionManagementController`/its Vue pages) — an election chief/deputy composing an election notice today would have to know the separate `/newsletters` URL exists and then discover they're blocked from it.

**No competing election-mass-email mechanism exists.** `SendVoterInvitation`/`VoterInvitationMail` sends individual, one-off credential emails per voter (no bulk "notice" concept, no campaign tracking). `Notification::send($activeChiefs, ...)` in `ElectionManagementController.php:192` notifies chiefs, not voters. Nothing else broadcasts to "all voters."

## Design decision (confirmed)

Per this repo's Canonical Discovery rule (*"if [a capability] exists, consume or extend it — never create a second one wearing its name"*), the plan **extends** `NewsletterService`/`OrganisationNewsletter`, it does not build a parallel system. Confirmed with the user:

1. **Entry point**: a "Notify Voters" action added to the existing `/organisations/{org}/elections/{election}/voters/manage` screen (`ElectionVoterController::index()` → `Elections/Voters/Index.vue`) — the same screen officers already use to suspend/remove/reinstate voters — pre-selecting `audience_type` and `audience_meta.election_id` for that election, so an officer never has to know the audience-type/election-id mechanics exist.
2. **Authorization**: extend authorization so an `ElectionOfficer` with `chief`/`deputy` role **for that specific election** can create and dispatch an election-audience newsletter (`election_voters`/`election_not_voted`/`election_voted`/`election_candidates`/`election_observers`/`election_committee`/`election_all`) for it, without needing org-admin/owner rights — matching the same authority already used for suspend/remove/reinstate elsewhere in this app (`ElectionPolicy::manageVoters`). Org admins/owners keep their existing, broader access to every audience type unchanged. Non-election audience types (Membership, org-role) remain org-admin/owner-only — an election officer has no business emailing "All Members."
3. Reuse `NewsletterService`, `OrganisationNewsletter`, `NewsletterRecipient`, the batch jobs, and the audit log exactly as they are — no new mail class, no new queue job, no new campaign table.

## Files to change

**Backend**
- `app/Http/Controllers/Membership/OrganisationNewsletterController.php` — `authorizeAdmin()` split/extended: a new check (e.g. `authorizeElectionNotice()`) that accepts either the existing org-admin/owner role, or an `ElectionOfficer` (`chief`/`deputy`, `active`) scoped to the election in `audience_meta.election_id`/route param — applied on `create()`/`store()` only when the audience type is one of the 7 election types; non-election audience types keep calling the existing org-admin-only `authorizeAdmin()` unchanged.
- A new route + thin controller action under the Election management area, e.g. `organisations/{organisation}/elections/{election}/voters/notify`, reusing the existing `create()`/`store()` logic with the election and a sensible default audience (`election_voters`) pre-filled — not a duplicate controller.

**Frontend**
- A "Notify Voters" button/action added to `resources/js/Pages/Elections/Voters/Index.vue`, next to the existing voter-management actions, linking to the pre-filled newsletter composer for that election.
- Reuse `Organisations/Membership/Newsletter/Create.vue` as the composer — minimal change, one existing UI serving two entry paths (generic org newsletters, and this election-scoped shortcut).

**Tests (TDD, written first)**
- Authorization: an election chief/deputy (not org admin) can create/dispatch an `election_voters`/`election_all`/etc. newsletter for their own election; the same officer is still rejected for non-election audience types; an officer of a *different* election is rejected; existing org-admin/owner access is unchanged (regression).
- Entry point: the new route pre-fills the correct `election_id`/audience for the election it's launched from.

## Verification

1. TDD: write the authorization/entry-point tests first, confirm RED, implement, confirm GREEN.
2. Run `tests/Feature/` files touching `OrganisationNewsletterController`/`NewsletterService` (locate exact existing test files during Phase 1) to confirm no regression to today's org-admin path.
3. `npm run design-check` after any Vue change.
4. Cannot browser-test in this environment — state that explicitly rather than claiming visual verification, consistent with this session's established practice.

## Open item to verify in Phase 1 (before writing tests)

Locate the existing test file(s) covering `OrganisationNewsletterController`/`NewsletterService` (not yet found in this session's research) to establish the current GREEN baseline before adding the new authorization branch — per this session's established regression discipline (`git stash` before/after comparison if any pre-existing failures are found).

## Explicitly out of scope

- Any change to `NewsletterService`'s delivery pipeline, mail class, queue jobs, kill-switch, or audit log — reused unmodified.
- Any change to Membership/org-role audience types or their existing org-admin-only authorization.
- Any change to `SendVoterInvitation`/`VoterInvitationMail` (the existing per-voter credential email).
- Building a second, parallel election-only mailer (explicitly rejected per Canonical Discovery — the capability already exists).
