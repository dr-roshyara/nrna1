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

*Later slices add their own files: C2 admission policy, C3 identity verification, C4 UI.*

## Related guides

- `developer_guide/election/voter/09-ensure-election-voter-middleware.md`: the middleware now delegates to the port described here.
- `developer_guide/election/voter/06-caching.md`: the voter cache key is now `v3` (see C1).
- `developer_guide/election/voter/11-two-person-suspension.md`: a confirmed suspension sets `status = inactive`, so the voter is denied (`NotActive`).
