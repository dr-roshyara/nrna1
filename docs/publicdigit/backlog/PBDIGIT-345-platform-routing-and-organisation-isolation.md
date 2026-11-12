# PBDIGIT-345 — Platform routing and organisation isolation (release 1, slice 2)

| | |
|---|---|
| **Status** | `APPROVED` — plan approved by the Principal 2026-11-12 (EP-01); strict TDD; not started |
| **Plan** | `docs/plans/20261112-1844-release-1-build-slice-2-platform-routing-and-isolation-plan.md` |
| **Design** | `docs/publicdigit/architecture/reviews/2026-11-12-platform-organisation-routing-isolation-tactical-design.md` (delta, revision 3, accepted), consuming D-178 and D-193 |
| **Decisions** | D-160, D-164, D-166, D-178, D-193, **D-219**, **D-221**; D-147 unchanged |
| **Depends on** | PBDIGIT-343 (slice 1, accepted with qualifications); PBDIGIT-324 (isolated PostgreSQL cluster) |

## Goal

**Every organisation-side operation runs on exactly one registry organisation's own database, chosen only from the trusted registry, or is refused.** Membership slice 1 becomes reachable for ledger organisations.

## Acceptance

- T-1 … T-25 green on the isolated cluster, with raw output and checksums; T-19 run before and after the queue change;
- D-193's proof obligations listed by number;
- the legacy path unchanged; Domain and Application free of Laravel;
- developer guides per task;
- **acceptance by the Principal.**

## Out of scope

- the emergency route (D-219 (3));
- legacy cutover;
- Organisation structure, Governance, the Membership lifecycle, Forum, Newsletter;
- Reference Geography (D-147; its place in the order is the open Decision G).
