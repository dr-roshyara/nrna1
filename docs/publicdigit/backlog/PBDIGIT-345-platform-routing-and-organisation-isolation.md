# PBDIGIT-345 — Platform routing and organisation isolation (release 1, slice 2)

| | |
|---|---|
| **Status** | `IN PROGRESS` — tasks 0 (test harness) and 1 (domain model) done 2026-11-12; **tasks 2 (registry, T-2) and 3 (per-organisation connection, T-26, T-10 part 1) done 2026-11-13**; **task 4 (organisation migration set: organisation_identity, F-13, T-8, T-10 part 2) done 2026-11-14**; **task 5 DONE 2026-11-14** (5a steps 1–2 and L-2, 5b step 3 in its own process, 5c step 5 with L-1; T-9, T-13, T-14 part 1); **task 6 DONE 2026-11-14** (HTTP entry E-1; T-1, T-15, T-10 part 3; production fail closed until Identity bindings, PBDIGIT-349); **task 7 DONE 2026-11-14** (queue E-2; T-3, T-4, T-19 twice, T-20 … T-23, T-25; **T-24 carried to task 9, acknowledged by the Principal 2026-11-14, still open**); **task 8 DONE 2026-11-14** (fan-out E-3; T-5); **task 9 DONE 2026-11-14** (organisation outbox and per-organisation relay E-5; T-6, ISO-9, **T-24 passed**); **task 10 DONE 2026-11-14** (D-233; Platform's published routing contract; Membership M-1 … M-3 in production; T-7, T-12, T-14 part 2); next task 11 (operator channel E-4), awaiting the Principal's review; plan approved 2026-11-12 (EP-01); strict TDD |
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
