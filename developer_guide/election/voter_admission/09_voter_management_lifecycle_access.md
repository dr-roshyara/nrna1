# 09 — Voter Management across the lifecycle (PBDIGIT-251)

**Story:** PBDIGIT-251 · **Commit:** this step's commit · **Decisions:** PO 2026-10-06: "accessible all the time … even at the time of voting"; "only suspension" (Remove stays hidden)

## Purpose

Identity verification by video call, IP capture and binding happen just before voting or during it. The Voter Management page (`GET voters/manage`) used to sit in the `import_voters` window, which closes after setup administration, so officers could not reach the verification buttons while voting.

The page is now readable in **every** lifecycle state. Each **write** keeps its own lifecycle window.

## The windows

| Operation (`OperationCapabilityMapper`) | States | Routes |
|---|---|---|
| `view_voter_management` | every state | `GET voters/manage` |
| `import_voters` (unchanged) | while `canManageVoters` (setup administration and earlier) | import, assign, bulk, export, remove, approve, single-officer suspend |
| `suspend_voters` (new) | `canManageVoters` OR SetupAdministration → VotingActive | propose / confirm / cancel suspension (four-eyes) |
| `verify_voter_identity` (unchanged) | SetupAdministration → VotingActive | verify, verify all, cancel verification |
| `capture_voter_ip` (PBDIGIT-247) | SetupAdministration → VotingActive | capture IP |

During voting an officer can therefore verify identity, cancel a verification, capture or re-capture an IP, and propose / confirm a suspension. They **cannot** remove, approve, import or export.

## How the page knows

`ElectionVoterController::lifecycleCapabilities()` asks the **same mapper the route gates use**:

```php
$snapshot = ElectionLifecycle::of($election)->snapshot();

return [
    'manageVoters'   => OperationCapabilityMapper::isOperationAllowed('import_voters', $snapshot),
    'suspendVoters'  => OperationCapabilityMapper::isOperationAllowed('suspend_voters', $snapshot),
    'verifyIdentity' => OperationCapabilityMapper::isOperationAllowed('verify_voter_identity', $snapshot),
    'captureIp'      => OperationCapabilityMapper::isOperationAllowed('capture_voter_ip', $snapshot),
];
```

`Voters/Index.vue` renders each action only when its capability is true:
- Verify Identity / Cancel Verification / Verify All → `verifyIdentity`;
- Verify IP Address → `captureIp`;
- Propose / Confirm / Cancel suspension → `suspendVoters`;
- Approve / Remove / Export → `manageVoters`.

The flags are **display only**. The route gates still refuse anything outside its window.

## Pitfalls

- **Add a new voter action to the right window.** Putting it in `import_voters` closes it after setup administration.
- **Contact data:** the page has no email / telephone editing (test L7). Do not add any.
- **Access:** `authorize('view', $election)` allows any active officer of the organisation, not only this election's officers. The page is now open in every state, so that exposure lasts longer (VIS-D2, not changed here).
- **Remove is single-officer.** The PO noted that removing a voter should follow the four-eyes principle; it stays setup-only and hidden during voting. The single-officer `suspend` route also stays setup-only.

## Testing

| Suite | Covers |
|---|---|
| `tests/Feature/Election/VoterManagementLifecycleAccessTest.php` | L1–L2 page reachable, L3 verification and capture while voting, L4 assign / remove refused, L5 capabilities, L8–L10 suspension windows, L11 approve refused |
| `tests/Frontend/VoterManagementCapabilities.spec.js` | L6a–c actions per capability, L7 no contact-data input |

**Traceability:** PBDIGIT-251 · PBDIGIT-213 D1 · PBDIGIT-222 · PBDIGIT-247 option (a) · two-person suspension (`developer_guide/election/voter/11-two-person-suspension.md`).
