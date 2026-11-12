# PBDIGIT-343: Membership ledger clock conformance (D-209) and the C1 / C2 provider

**Type:** Story (release 1, build slice 1) · **Epic:** minimum package (D-146) · **Created:** 2026-11-12

| | |
|---|---|
| **Status** | `IN PROGRESS` — plan approved by the Principal 2026-11-12 (EP-01); strict TDD |
| **Plan** | `docs/plans/20261112-1251-release-1-build-slice-1-membership-ledger-clock-and-c1-c2-plan.md` (revision 2) |
| **Authority** | D-162 as amended by D-209; D-215 (K-C1); D-161; D-160; D-210 §4, §11 |

## Goal
- Record time from the organisation database's clock: no adjustment, refusal and bounded retry on regression.
- The `closedThrough` step.
- C1 `MembershipCoverageResolved`, atomic with the fact.
- C2 `CoverageKnownAt`, complete or refused.
- **All proved on the isolated PostgreSQL cluster.**

**Nothing goes live:**
- the source-of-truth port refuses every organisation until slice 2;
- no development or production migration.

## Done when
- Plan §6 tests 1–19 pass on the isolated cluster and the unit suite.
- Architecture tests pass.
- The developer guide is written.
- **The Principal accepts.**
