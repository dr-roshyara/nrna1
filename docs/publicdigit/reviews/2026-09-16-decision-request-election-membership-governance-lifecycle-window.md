# Decision Request — during which Election Lifecycle phases may Election Membership suspension, removal, and reinstatement be exercised?

**Type:** Governance decision request · **Date:** 2026-09-16 · Raised during implementation planning for `2026-09-16-election-membership-suspension-removal-governance-decision.md`
**⛔ Nothing adopted here. This document raises a question; it resolves nothing.**

---

## Already decided (not reopened by this request)

`2026-09-16-election-membership-suspension-removal-governance-decision.md` (resolving `EM-OPEN-001`, `002`, `004`, and parts of `003`/`005`) stands as recorded:

- State Machine 1 (existing mechanism) and State Machine 2 (Election Membership lifecycle) as defined there.
- `manageVoters` = Chief + Deputy, unchanged.
- One authorized officer is sufficient to suspend.
- Suspension immediately prevents the member from voting.
- Removal is a second step, performed by a different officer than the one who suspended.
- `Code.has_voted` remains untouched.

This request does not touch any of the above.

## Newly discovered fact (repository evidence)

The entire `ElectionVoterController` route group — `index`, `store`, `bulkStore`, `export`, `destroy` (remove), `approve`, `suspend`, `proposeSuspension`, `confirmSuspension`, `cancelProposal` — is wrapped in one middleware group:

```
routes/organisations.php:261-280
Route::middleware(['election.state:import_voters'])->group(function () { ... });
```

`import_voters` resolves to `ElectionLifecycleSnapshot->canManageVoters` (`app/Http/Middleware/OperationCapabilityMapper.php:38`). Its value per election lifecycle state, read directly from `app/Application/Election/Services/ElectionLifecycleEngineImpl.php`:

| State | `canManageVoters` |
|---|---|
| `Draft` | true |
| `SubmittedForApproval` | false |
| `Approved` | true |
| `Rejected` | true |
| `SetupAdministration` | true |
| `SetupNomination` | false *("Voters locked")* |
| all remaining states (voting-active, counting, results, terminal) | false |

**Affected routes:** every named route under `elections.voters.*` in `routes/organisations.php:270-279` (`elections.voters.index/store/bulk/export/destroy/approve/suspend/propose-suspension/confirm-suspension/cancel-proposal`).
**Affected states:** all of `SetupNomination` and every state after it — i.e., the entire nomination phase onward, including the full voting window.

## The contradiction

> **Business intent** (per the adopted decision): suspension can immediately stop an Election Member from voting — implying a dispute can arise, and be acted on, while the member could otherwise be voting.
>
> **Current lifecycle capability**: the routes that would perform suspension, removal, or reinstatement are unavailable for the entire voting window (and everything after `SetupNomination`).

## Why engineering cannot resolve this

Every available fix touches something explicitly protected or explicitly out of scope for this work: widening `canManageVoters`, modifying `OperationCapabilityMapper` or `ElectionLifecycleEngineImpl`, adding a voting-phase carve-out, introducing new middleware, or bypassing the existing gate would each constitute a new lifecycle/capability decision — the same category of decision this project has consistently routed through PO/ARB rather than through implementation inference.

## Questions requiring PO/ARB decision

1. May suspension be exercised while voting is active?
2. May removal be exercised while voting is active?
3. May reinstatement be exercised while voting is active?
4. If suspension is permitted during voting, should only suspension be exposed then, or should removal/reinstatement also be available during voting?
5. Should these actions use the existing `manageVoters` capability, or should they have a separate, dedicated lifecycle capability?
6. Does `manageVoters` semantically mean "roster administration" (a setup-phase activity) rather than "Election Membership governance" (which may need to span the whole election)? The repository may currently have one capability covering both for historical reasons — that is not evidence they are the same business capability.

The answer to (1) should not be assumed to determine (2)–(6); each is asked separately, and (4)/(5)/(6) do not have a default answer implied by the others.

---

**NO GOVERNANCE RULE ADOPTED BY THIS DOCUMENT**
**NO FILES MODIFIED OTHER THAN THIS ONE**
**NO TESTS WRITTEN**
**NO PRODUCTION CODE CHANGED**
