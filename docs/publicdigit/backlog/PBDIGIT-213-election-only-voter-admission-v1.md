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

## Progress

| Slice | State | Evidence |
|---|---|---|
| C0 | ✅ committed `112e1a15d` | ADR, story, plan, investigations |
| C1 | ✅ committed `f97ddae59`, **accepted by the PO 2026-10-04** | RED 17 → GREEN 33/33. Regression over 45 suites, pre-C1 worktree vs C1: 166 failures before and after, the same tests and the same causes, **0 delta**. Guide `developer_guide/election/voter_admission/01_c1_voting_entitlement.md` |
| C2 | ✅ committed `110387f63`, **accepted by the PO 2026-10-04** | Policy (default OFF, Election-Only only), two-layer lock, dedicated endpoint, audit, impact, evidence schema. RED 48 → GREEN 49/49 (C1+C2 82/82). Regression over 54 suites, worktree at C1 vs C2: 174 failures before and after, **0 per-test diff, 0 reason diff**. Guide `02_c2_admission_policy.md` |
| C3 | ✅ committed `622ee91e9`, **accepted by the PO 2026-10-04** | Admission enforced by the one entitlement decision; Chief/Deputy authority; window `verify_voter_identity`; suspension ≠ re-verification; cache invalidation both ways; legacy `can_vote` classified (A–G); `Code.can_vote_now` out of scope (0 changes). RED 52 → GREEN 82/82 (C1+C2+C3 167/167). Regression over 77 suites: 1 changed test (`SimpleStoreTest`, D1/D3 fixture conflict → PO-approved fixture-only adaptation); the other 205 failures are unchanged. `AdmissionPolicyC2BoundaryTest` superseded (C3-D3). Findings → `PBDIGIT-214`…`219`. Guide `03_c3_identity_admission.md` |
| C4 | ✅ GREEN, **awaiting PO review before commit** | Settings Voter Admission card; Identity Verification column (shown only with the policy ON); Verify Identity via `IdentityAttestationDialog` with a **server-side `attested`** contract; Verify All (C4-D1…D4: never-verified active voters, exact count, statement, one transaction, per-voter + summary audit with `batch_id`); "Suspend Identity" label; voter notice. Correction 2026-10-04: identity only on the page; the legacy "Verify IP Address" UI entry point removed (IP verification → `PBDIGIT-222`); name badge from the identity state only. Voting-process boundary pinned by `VotingProcessEntitlementBoundaryTest` (41/41, no production change). Regression: 89 suites vs `622ee91e9`, 0 changed results, 0 reason delta. Vitest: same 8 pre-existing failures. Guide `04_c4_ui_and_bulk_verification.md`. Follow-ups: `PBDIGIT-220`, `221`, `222` |

### Overlap found after C0: `PBDIGIT-201`

- **The defect:** `PBDIGIT-201` (backlog README row: *"Starting the vote still hides a valid voter's membership behind the ambient tenant (third site of `PBDIGIT-65`)"*, 🔴 OPEN) is the `start()` defect that C1 removes. C1 does this as part of the unification, so the work was caused by this story and not done for 201.
- **Pin:** `VotingEntitlementUnificationTest::test_start_does_not_depend_on_the_ambient_tenant` (RED before C1: an entitled voter was refused under ambient org B; GREEN after).
- **Status:** engineering does not change 201's status. Accepting it as resolved is a PO act.
- **Search gap:** the C0 existing-story search covered only `PBDIGIT-*.md` files and missed 201, which exists only as a README row. This is recorded here as a C0 search gap.

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
