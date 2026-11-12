# PBDIGIT-343: Membership ledger clock conformance (D-209) and the C1 / C2 provider

**Type:** Story (release 1, build slice 1) · **Epic:** minimum package (D-146) · **Created:** 2026-11-12

| | |
|---|---|
| **Status** | `ACCEPTED` — by the Principal 2026-11-12, with qualifications (plan task 10). Earlier: plan approved 2026-11-12 (EP-01); strict TDD |
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


## Acceptance (2026-11-12)

**ACCEPTED by the Principal, 2026-11-12** (Principal DDD review "Slice 1 closure and Slice 2 decision": "accept Membership Slice 1"). **Qualifications, recorded with the acceptance:**
- the 135 PostgreSQL tests and the other slice 1 groups (kernel 204, application 92, infrastructure 65, outbox 9, Membership architecture 106) passed;
- **the 4 full architecture-suite failures are pre-existing:** the same names on baseline `a53ba8ea8` and HEAD `58e8bb26d`;
- **the 57 failing legacy Membership unit tests at HEAD also fail on the baseline.** The whole suite is **not** green;
- **no claim that slice 1 is production-ready.** Production routing, the source-of-truth binding, the per-organisation relay and production migrations are slice 2 responsibilities.

Evidence: `docs/publicdigit/membership/evidence/2026-11-12-pbdigit343-slice1-acceptance/` (unchanged; `5596e3eb9`).
