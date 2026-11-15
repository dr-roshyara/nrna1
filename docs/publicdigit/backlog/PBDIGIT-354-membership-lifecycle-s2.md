# PBDIGIT-354: Membership lifecycle (release 1, build slice S2 = blueprint G-4)

| | |
|---|---|
| **Status** | `PROPOSED`: readiness assessment done; **plan awaiting the Principal's approval (EP-01)**. Nothing implemented |
| **Plan** | `docs/plans/20261115-0510-release-1-build-slice-s2-membership-lifecycle-plan.md` |
| **Context** | Membership (organisation database) |
| **Authorisation** | Principal, 2026-11-15 (development and test only): "proceed with Membership lifecycle", starting with the readiness assessment |
| **Decisions** | D-163, D-177, D-201, D-203 … D-206, D-209, D-210, D-216 (G-3, G-4), D-223, D-233, D-235 |

## Scope

- Renew;
- the admission term rule;
- Suspend / Restore;
- resign now (including `ownAct`);
- record a death;
- scheduled resignation (D-205 exception, D-223);
- Correct / Void with authority;
- the Membership side of the push shapes (exclusion, grouped renewals, Annul).

Plan §4, steps S2-1 … S2-6.

## Acceptance criteria

- **D-177 §12** proved;
- **MI-1, MI-2, MI-3, MI-4, MI-10, MI-15a … f, LC-4** proved on the isolated PostgreSQL cluster;
- legacy suites no worse than the baseline;
- Domain and Application free of Laravel;
- a guide per step;
- **the Principal accepts.**

## Open

- **Q-1:** the meaning of "`MembershipStanding`" (plan §3.5).
- **EP-01:** plan approval.
