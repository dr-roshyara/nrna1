# Voter Admission & Voting Entitlement — Developer Guide

**Story:** `PBDIGIT-213` — Election-Only voter admission v1
**Decision:** `docs/publicdigit/adr/ADR_20261004_1549_Voter_Admission_Policy_And_Voting_Entitlement.md`
**Plan:** `docs/plans/20261004-1549-election-only-voter-admission-v1-plan.md`

---

## The model in one picture

```
Registration (ElectionMembership)  ≠  Voter Admission (policy + evidence)  ≠  Voting Entitlement (may vote NOW)
```

- **Registration** creates the voter's `ElectionMembership` for one election. Import, assign and bulk assign do this, and nothing in this guide changes it.
- **Voter Admission** asks whether the registered voter satisfies the election's admission requirements, for example identity verification. This arrives in C2 and C3.
- **Voting Entitlement** is the one final decision: may this voter vote in this election now?

## Steps

| File | Slice | What it covers |
|---|---|---|
| [01_c1_voting_entitlement.md](./01_c1_voting_entitlement.md) | C1 | One authoritative voting-entitlement decision, scoped by the election |
| [02_c2_admission_policy.md](./02_c2_admission_policy.md) | C2 | The Voter Admission Policy (ON/OFF), its two-layer lock, impact, audit and the evidence schema |
| [03_c3_identity_admission.md](./03_c3_identity_admission.md) | C3 | Identity admission enforced by the one entitlement decision; Chief/Deputy authority; window; suspension ≠ re-verification; cache; legacy `can_vote` classification; `Code.can_vote_now` out of scope |

| [04_c4_ui_and_bulk_verification.md](./04_c4_ui_and_bulk_verification.md) | C4 | Admission card, Identity Verification column, Verify Identity (server-side `attested`), Verify All, Suspend Identity label, voter notice; voting-process boundary; IP verification = untouched legacy (PBDIGIT-222) |
| [05_cancel_verification_and_ip_separation.md](./05_cancel_verification_and_ip_separation.md) | PBDIGIT-248 | Cancel Verification (existing revoke, with confirmation) instead of Re-verify; identity dialog / endpoint carry no IP / device data; "Suspend" label |
| [06_capture_ip_and_ip_verification_setting.md](./06_capture_ip_and_ip_verification_setting.md) | PBDIGIT-222 | Capture IP Address as separate evidence (`voter_ip_verifications`), the "Voter's IP Verification" setting, fail-closed endpoint, legacy `voter_verification_mode` |
| [07_read_only_voter_list_verification_states.md](./07_read_only_voter_list_verification_states.md) | PBDIGIT-250 | Read-only voter list: Identity Verification / IP Address Verification state columns (states only), shared `VoterVerificationStates` read-model |
| [08_ip_binding.md](./08_ip_binding.md) | PBDIGIT-247 | IP Binding: captured AND matching (fail closed) at forEntry / forCast, `IpBindingDenied`, locked setting, `capture_voter_ip` window |
| [09_voter_management_lifecycle_access.md](./09_voter_management_lifecycle_access.md) | PBDIGIT-251 | Voter Management readable in every state; verification, IP capture and two-person suspension until voting closes; `capabilities` prop |

## Related guides

- `developer_guide/election/voter/09-ensure-election-voter-middleware.md`: the middleware now delegates to the port described here.
- `developer_guide/election/voter/06-caching.md`: the voter cache key is now `v3` (see C1).
- `developer_guide/election/voter/11-two-person-suspension.md`: a confirmed suspension sets `status = inactive`, so the voter is denied (`NotActive`).
