# PBDIGIT-321: Deptrac layers for the Membership and Elections contexts

**Type:** Architecture enforcement (tooling) · **Epic:** none · **Created:** 2026-11-02 · **Raised by:** PO decision on the PBDIGIT-318 deptrac deviation (option (b), 2026-11-02)

| | |
|---|---|
| **Status** | `OPEN` · **not authorised**. A later, separately authorised item |
| **Owner** | the owner of the governed `deptrac.yaml` (PB-007 7A, architecture-derived configuration) |
| **Scope** | add deptrac layers for `app/Contexts/Membership` and `app/Contexts/Elections`, and rules forbidding Membership → Elections dependencies (and the reverse, as decided) |

## Background

- The PBDIGIT-318 S1 plan promised a "deptrac layer rule (`deptrac.yaml`) + static scan for writes to Election tables". S1 and S1b delivered the static PHPUnit test only.
- `deptrac.yaml` has layers for Contestation, Adjudication, `app/Contexts/Election` and Shared only. It does not cover the Membership context, `app/Contexts/Elections` or the legacy `app/Models`.

**PO decision (2026-11-02), option (b):**
- the deviation is **acknowledged**: for this boundary, the static test (`tests/Architecture/MembershipElectionBoundaryTest.php`) is the enforcement, with its documented limits;
- deptrac layers are recorded as this later item.

## What it would add

Class-dependency enforcement over the whole class graph of the covered paths, without a curated model list.

## What it would not add

- table-level coupling, foreign keys and occurrence counts (deptrac sees classes only);
- class names written as strings.

## Questions for the owner (before authorisation)

- Changing the governed PB-007 configuration: what process applies?
- Coverage: should the legacy `app/Models` be in scope? It would probably surface many legacy violations, which would need a baseline policy consistent with PBDIGIT-318's governance.
- Direction: only Membership → Elections, or both? The latter depends on OQ-ME-3 (ARB).

## Not in scope here

Any change to `deptrac.yaml` or CI until this item is authorised.

## Related

- PBDIGIT-318 (S1 / S1b);
- developer guide `developer_guide/membership_election_boundary/01_s1_static_boundary_guards.md` ("Static test, not deptrac").
