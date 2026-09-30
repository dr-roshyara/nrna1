# Step 3: Receipt Codes — committee-only, removed from Voter Hub

**Commit:** `57ac18eeb` — `fix(voting): make receipt codes committee-only, move it out of Voter Hub`

## Purpose

The Receipt Codes page (`organisations.election.receipt-codes` → `VotingReceiptController::index()`)
shows the full randomized list of every voter's receipt code for an election, once results are
published. This is a governance/audit view — only the election committee should see it. It had no
authorization check beyond the `results_published` gate, and Voter Hub linked to it directly for
any org member.

## Where it fits

```
app/Http/Controllers/VotingReceiptController.php    ← authorize() added
app/Http/Controllers/OrganisationController.php      ← results_published_at added to voterHub() props
resources/js/Pages/Organisations/VoterHub.vue        ← Receipt Codes tile removed, CTA swapped
```

## Design decision

- **Authorization, not just UI hiding.** `VotingReceiptController::index()` now calls `$this->authorize('viewResults', $election)` — the existing `ElectionPolicy::viewResults()` (any active `ElectionOfficer` for the organisation). The route itself is protected regardless of whether a link to it exists anywhere in the UI.
- **Removed from Voter Hub, not just re-gated there.** The Receipt Codes quick-action tile and both other Voter-Hub CTAs pointing at it are removed entirely (not merely wrapped in an `isOfficer` check) — the page is reached from **Voter Management** (`/elections/{slug}/management`, itself `manageSettings`-authorized), which already had its own Receipt Codes link, unaffected by this change.
- **"Verify Vote" renamed to "See Your Vote"** in Voter Hub, to better describe `/vote/verify_to_show` — a voter's own self-service lookup, distinct in purpose from the committee-only Receipt Codes list.
- **The two CTAs that used to link to Receipt Codes** (in the "published elections" card and the per-election results-published state) now link to `result.index` (View Results) instead — voters still get a clear path to results, just not to the committee-only code list.

## `results_published_at` field

`OrganisationController::voterHub()`'s `activeElections` map already had template code in `VoterHub.vue` gated on `election.results_published_at` (for the results-published CTA state), but the field was never included in the controller's response — a pre-existing, silently-dead gate. Adding it here (as part of this same commit, since it's what the surviving "View Results" CTA now depends on) fixes that gap; it is separately tested.

## Testing

- `tests/Feature/VoteReceiptVerificationTest.php` — new `receipt_codes_page_is_forbidden_for_non_committee_member_even_after_results_published`; the suite's existing committee-member test user is now given an `ElectionOfficer` (`chief`) row in `setUp()` so the pre-existing tests keep passing under the new authorization requirement.
- `tests/Feature/OrganisationVoterHubTest.php` — `test_voter_hub_includes_results_published_at_field`.

## Pitfalls

- The route's own `election.state:view_results` middleware and the controller's `results_published` boolean check are **two separate gates** — `results_published_at` drives lifecycle-state derivation (the middleware), while `results_published` is a manual Hide/Unhide toggle the controller also checks directly. Both must be set for the page to render in a test; this was a pre-existing gap in `VoteReceiptVerificationTest.php`'s fixtures, fixed as part of this commit since it blocked verifying the authorization change.
- Don't re-add a Receipt Codes link to Voter Hub without also deciding whether it should be officer-gated in the UI — the authorization check in the controller is the real boundary either way, but an un-gated link would be misleading UX.

## Traceability

Commit: `57ac18eeb`. Raised directly by the user during Ballot Preview's manual-walkthrough checkpoint ("Receipt Codes should only be seen by the election committee... it should be in Voter Management").
