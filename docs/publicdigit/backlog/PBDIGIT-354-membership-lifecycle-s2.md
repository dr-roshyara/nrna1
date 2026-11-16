# PBDIGIT-354: Membership lifecycle (release 1, build slice S2 = blueprint G-4)

| | |
|---|---|
| **Status** | `IN PROGRESS`: plan **approved (D-236)**; S2-1 implemented and proven except D-177 row 20 (rule decided, D-237; implemented in S2-5); **S2-2 DONE 2026-11-15**; **S2-3 DONE 2026-11-15**; **S2-4 DONE 2026-11-16** (D-239, D-240); S2-5, S2-6 open |
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

## Decided

- **Q-1:** Option A (D-236).
- **EP-01:** plan approved in full (D-236).

## Decided: Q-6 → D-241, Q-7 → D-242, Q-8 → D-243 (all Option A, 2026-11-16)

- A Suspend's dates are not correctable in release 1 (Void and record again; Restore ends it earlier); a Correct of a Renew means only its `until` (D-237); a Void is recorded with its evidence and visible through C1; a dedicated notice is PBDIGIT-357.

## Open (S2-5 preflight, plan §7e): as found

- **Q-6:** which date of a Suspend a Correct may change. Recommended A: neither in release 1 (Void and record again; a Restore ends it earlier).
- **Q-7:** may a Correct change a Renew's start? Recommended A: no (Void and record again); a Correct of a Renew means its `until` (D-237).
- **Q-8:** what "a Void is reported to the organisation" requires in S2-5. Recommended A: recorded with its evidence, and visible through C1; a dedicated notice becomes a backlog item.
- **Q-9 (found in S2-5a):** a Correct moving an End onto or after the date of a declaration that End had already superseded. Recommended A: refuse it.

## Decided: Q-5 → D-240 (Option A)

- *(as found)* **Q-5 (S2-4d, plan §7d):** an ending (resign now, death, exclusion) effective on or after a due-but-unexecuted scheduled resignation's date. Recommended: A, refuse it while the declaration is pending. **S2-4 is not Done until decided and implemented.**

## Decided: Q-4 → D-239 (Option A)

- *(as found)* **Q-4 (S2-4 preflight, plan §7c):** which calendar decides that a scheduled resignation's date has arrived. No accepted owner of the organisation time zone exists in the new architecture. Recommended: a consumer-owned `OrganisationCalendar` port, answered by Organisation (S4), fail-closed until then. **No S2-4 code until decided.**

## Decided: Q-3 → D-238 (Option A; S2-3a, then S2-3b after review)

- *(as found)* **Q-3 (S2-3 readiness, plan §7b):** the accepted Governance AuthorityCheck needs the subject and a basis (incl. `ownAct`) that Membership's port does not carry; a pull exclusion is accepted today, though it is push-only. Recommended: S2-3a (extend the port to the accepted shape) then S2-3b. **No S2-3 code until decided.**

## Decided after the plan

- **Q-2: D-237 (Option A).** Its implementation is an S2-5 obligation; S2-1's box stays open until then.

## Progress

- [x] S2-1 Renew and the admission-term rule: **DONE 2026-11-16**: implemented 2026-11-15 (guide 25; evidence `docs/publicdigit/membership/evidence/2026-11-15-pbdigit354-s2-1/`); D-177 row 20 (Correct a Renew's `until`) delivered under D-237 in S2-5b (guide 35)
- [x] S2-2 Suspend / Restore: **DONE 2026-11-15** (guide 26; evidence `docs/publicdigit/membership/evidence/2026-11-15-pbdigit354-s2-2/`); correcting their dates belongs to S2-5
- [x] S2-3 resign now, death: **DONE 2026-11-15:** S2-3a (authority contract, D-238; guide 27) and S2-3b (resign now, record a death; guide 28; evidence `…/2026-11-15-pbdigit354-s2-3b/`)
- [x] S2-4 scheduled resignation (D-239, D-240): **DONE 2026-11-16**. **S2-4a DONE** (domain; guide 29), **S2-4b DONE** (schedule / withdraw / store; guide 30; migrations 360000 / 300700), **S2-4c DONE** (execution in the close; guide 31), **S2-4d DONE** (race, visibility, routing; guide 32) 2026-11-15; **Q-5 decided as D-240 and implemented** (guide 33; evidence `docs/publicdigit/membership/evidence/2026-11-15-pbdigit354-s2-4d-q5/`) 2026-11-16
- [ ] S2-5 Correct / Void, **including D-177 row 20 under D-237** (closes S2-1) and row 14 (**a Restore's date**; a Suspend's: Q-6). **S2-5a DONE** (Correct / Void use cases; D-240 for a corrected End; guide 34), **S2-5b DONE** (D-237, D-241, D-242, row 14; migration 370000 / 300800; guide 35) 2026-11-16. **Implemented in full; not marked Done while Q-9 is open** (plan §7e)
- [ ] S2-6 push shapes (Membership side)
