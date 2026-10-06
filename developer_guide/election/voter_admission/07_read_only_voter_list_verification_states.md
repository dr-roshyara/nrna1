# 07 — Read-only voter list: verification states (PBDIGIT-250)

**Story:** PBDIGIT-250 · **Commit:** this step's commit · **Builds on:** 05 (identity states), 06 (IP evidence + setting)

## Purpose

The read-only voter list (`/organisations/{org}/elections/{election}/voters`, `OrganisationController::voters`, `Pages/Organisations/Voters.vue`) shows two **read-only** columns:

| Column | Shown when | Values |
|---|---|---|
| Identity Verification | the admission policy requires `OFFICER_IDENTITY_VERIFICATION` | Verified · Verification required · Re-verification required |
| IP Address Verification | "Voter's IP Verification" is enabled (`ip_verification_enabled`) | Verified · Not verified |

**Audience matters.** `ChecksElectionAccess` lets org owners / admins / commission, election officers **and every active voter of the election** open this page. So it carries **states only**: never an IP address, capture notes or who verified (PO 2026-10-06; VIS-D1 stays open). It has no actions.

## Key files

| Layer | File |
|---|---|
| Read-model | `app/ReadModels/VoterVerificationStates.php`: `identity()` and `ip()` |
| Controller | `OrganisationController::voters` (`verificationColumns`, row `identity_state` / `ip_state`) |
| Page | `resources/js/Pages/Organisations/Voters.vue` (`identityBadge`, `ipBadge`) |
| Shared caller | `ElectionVoterController::identityVerificationStates` now delegates to the read-model (behaviour unchanged) |

## How it works

`VoterVerificationStates` is the **one** place that turns evidence into display states. It is election-scoped and returns `user_id → state`:

```php
// identity: voter_verifications.status → state
'active'  => 'verified',
'revoked' => 'reverification_required',
default   => 'verification_required',

// ip: a voter_ip_verifications row for this election → 'verified', else 'not_verified'
```

The controller asks only for the current page's voters, and only for a column that is on:

```php
$verificationColumns = [
    'identity' => $electionModel->admissionPolicy()->requires(AdmissionRequirement::OfficerIdentityVerification),
    'ip'       => $electionModel->isIpVerificationEnabled(),
];
// each row: 'identity_state' => … ?? null, 'ip_state' => … ?? null
```

A column that is off sends `null` states, and the page hides its header and cells.

## How to extend

- A new state? Add it in `VoterVerificationStates`, so both pages stay consistent.
- Never add an IP address, notes or verifier to this page's props until VIS-D1 decides who may see them. Test V5 fails if one appears.
- Not an authorisation source: entitlement stays with `VotingEntitlementQuery`.

## Testing

| Suite | Covers |
|---|---|
| `tests/Feature/Organisation/ElectionVotersVerificationColumnsTest.php` | V1–V7: identity states, column switches, IP states, **no IP / notes / verifier in props**, plain-voter view, election scope |
| `tests/Frontend/OrganisationVotersVerificationColumns.spec.js` | C1–C4: column visibility, labels, read-only (no buttons / links), no IP shown |
| `tests/Feature/Election/VoterManagementIdentityStateTest.php` | the Voter Management identity states, unchanged after the extraction |

## Pitfalls

- **Paginate first, then read states.** States are read for `$page->getCollection()`'s user ids, avoiding an electorate-wide query.
- **Policy ≠ evidence.** The column shows only while the policy requires identity; the state itself is evidence (05).

**Traceability:** PBDIGIT-250 · PBDIGIT-213 C4-B · PBDIGIT-222 · ADR-T11 (no voter ↔ ballot link: these are admission-evidence states, never vote data).
