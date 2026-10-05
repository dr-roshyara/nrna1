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

## Related guides

- `developer_guide/election/voter/09-ensure-election-voter-middleware.md`: the middleware now delegates to the port described here.
- `developer_guide/election/voter/06-caching.md`: the voter cache key is now `v3` (see C1).
- `developer_guide/election/voter/11-two-person-suspension.md`: a confirmed suspension sets `status = inactive`, so the voter is denied (`NotActive`).
