# 01 — Voter Management: the identity-evidence read model is independent of the policy

**Story:** `PBDIGIT-213` C4 (PO correction, 2026-10-04) · **File:** `app/Http/Controllers/ElectionVoterController.php` · **Feature guide:** [`election/voter_admission/04_c4_ui_and_bulk_verification.md`](../election/voter_admission/04_c4_ui_and_bulk_verification.md)

## Purpose

The Voter Management page (`GET /organisations/{organisation}/elections/{election}/voters/manage`, `ElectionVoterController@index`) sends two separate display read-models. They answer two different questions and must not be mixed:

| Prop | Question | Source |
|---|---|---|
| `admission.required` | Is identity verification **required** in this election? | the Voter Admission Policy |
| `identityVerification[user_id]` | Has this voter's identity **been verified**? | `voter_verifications.status` — independent of the policy |

## The change

Before the correction, `identityVerificationStates()` returned `not_required` for every voter whenever the policy was OFF, hiding existing evidence. It now always derives the state from the evidence:

```php
private function identityVerificationStates(Election $election, array $userIds): array
{
    $evidenceStatus = VoterVerification::where('election_id', $election->id)
        ->whereIn('user_id', $userIds)
        ->pluck('status', 'user_id');

    $states = [];
    foreach ($userIds as $userId) {
        $states[$userId] = match ($evidenceStatus[$userId] ?? null) {
            'active'  => 'verified',
            'revoked' => 'reverification_required',
            default   => 'verification_required',
        };
    }

    return $states;
}
```

`admissionProps()` is unchanged.

## Why (layer rules)

- Policy OFF means *"not required"*, not *"cannot be recorded"*: Verify Identity and Verify All stay available while OFF, so the page must show the evidence that exists.
- These props are **display only**. Entitlement is decided by `VotingEntitlementQuery` (`app/Contexts/Elections`), never by this controller or the page.

## Pitfalls

- Don't re-introduce a policy short-circuit here; hide the **status column** in the page instead.
- Don't use the older `verifications` prop for identity state (it also contains legacy IP-action rows).

## Testing

`tests/Feature/Election/VoterManagementIdentityStateTest.php::test_with_identity_verification_off_each_voter_still_shows_their_evidence_state` (RED before the change, GREEN after).

**Traceability:** PBDIGIT-213 C4 · ADR_20261004_1549 · `developer_guide/election/voter_admission/04_c4_ui_and_bulk_verification.md`.
