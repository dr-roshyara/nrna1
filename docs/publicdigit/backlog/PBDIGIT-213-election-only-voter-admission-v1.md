# PBDIGIT-213 — Election-Only voter admission v1: identity verification by the election's Chief or Deputy Chief before a voter may vote

**Type:** Feature + security correction · **Epic:** `PBDIGIT-EPIC-05` Voting (journey segment *Voter Verification → Open Voting → Vote*) · **Created:** 2026-10-04
**Found by:** the `/voters/manage` investigation of 2026-10-04 (`docs/publicdigit/reviews/2026-10-04-election-only-voter-admission-gate-investigation.md`)
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)
**Decision record:** `docs/publicdigit/adr/ADR_20261004_1549_Voter_Admission_Policy_And_Voting_Entitlement.md` · **Plan:** `docs/plans/20261004-1549-election-only-voter-admission-v1-plan.md`

| | |
|---|---|
| **Status** | 🟢 **AUTHORISED (PO, 2026-10-04)**: plan rev 3 approved; implementation in slices C0–C4 |
| **Problem** | Election-Only import creates an `active` voter at once. The **Verify** action is recorded but read by no voting path, so an unverified voter can vote. The entitlement rule exists in 5 diverging copies; the in-transaction check at `vote.store` does not check the election's organisation. |
| **Business outcome** | The Chief or Deputy can switch **"Identity verification required"** ON for an election. Every registered voter must then be verified by the Chief or Deputy Chief of the election before the voter can vote. With the toggle OFF, behaviour is unchanged. A confirmed two-person suspension requires both reinstatement and re-verification. |
| **Acceptance** | Plan §4 tests plus the product-flow test R3-6 (settings ON → impact shown → voters "Identity verification required" → voter denied → chief verifies → voter entitled). Regression suites unchanged. Architecture fitness test proves a single entitlement decision point. |

## Slices (one commit each, `(PBDIGIT-213)`)

| Slice | Content |
|---|---|
| C0 | ADR, this story, plan copy, the two review documents |
| C1 | Unify voting entitlement and enforce the election-derived organisation scope (a security correction) |
| C2 | Admission policy and evidence schema; dedicated **Save Admission Policy** command; policy lock in both the application and the model |
| C3 | Admission enforcement, verification authority, verification window, suspension invalidation, bypass protection (C3.1–C3.5) |
| C4 | UI: settings "Voter Admission" card, Admission column, "Verify Identity", "Suspend Identity" label for the existing Propose action, voter-facing notice, product-flow test |

## Out of scope (raised as follow-ups)

These are gaps G1–G11 in the ADR §7:
- immutable verification history;
- the `ProtocolAppend` adapter;
- one-actor reactivation paths;
- the `destroy` / `bulkAssignVoters` defects;
- legacy fixture repairs;
- IP/device enforcement;
- other mechanisms (email, phone, eID);
- full-membership adoption;
- impact beyond the v1 transition;
- the Election Committee leadership model;
- the "admission" terminology debt.

## Existing-story search

`git grep -il "voter verification|voter_verification|admission|identity verification"` over `docs/publicdigit/backlog/`:
- **`PBDIGIT-68`** owns the *durable entitlement* ruling (registration = "admission" in its wording; see ADR §4 mapping). It does not implement verification gating.
- **`PBDIGIT-EPIC-05`** notes *"voter verification has no constitutional action and no domain event"*. This story adds the domain command and audit, but no constitutional lifecycle action (none is needed: verification is not a lifecycle transition).
- `PBDIGIT-26` (copy-code on the verification page) and `PBDIGIT-48` (election-state representations) are unrelated.

**No existing owner.**
