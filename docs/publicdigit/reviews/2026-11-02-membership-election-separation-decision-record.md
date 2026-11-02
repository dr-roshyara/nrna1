# Membership–Election separation: deployment milestone and remediation plan, decision record

**Date:** 2026-11-02 · **Recorded by:** Claude (DDD architect / requirements engineer)
**Status:** **Decided by the PO, 2026-11-02** ("approve both").
- Decision 1: an architectural **direction** for the first milestone.
- Decision 2: acceptance of a **PROPOSED** plan.
- **Neither authorises implementation.** Each slice needs its own approval.

**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/*-decision-record.md` convention.
**Baseline:** branch `election-flow-updated`, HEAD `ce84016a6`.

---

## 0. The rule that already applies (not decided here)

**[Ratified 2026-10-08]** "There is no shared persistence and no Membership → entitlement shortcut" (`docs/publicdigit/reviews/2026-10-08-membership-final-ratification-verdict.md:46`).

This record does not re-ratify that rule. It decides how the rule is enforced in the first milestone.

## 1. Decision 1: option A as the first milestone

> Membership and Election have distinct business responsibilities, explicit interfaces and **separate persistence ownership within one application**. The ratified prohibition on shared persistence and on the Membership → entitlement shortcut applies **now**. Separate databases per context (option B) and independently deployable services (option C) remain possible future decisions and are **not** approved by this decision. **D-1 (organisation tenancy) is unchanged.**

**Clarifications:**
- **Separate persistence ownership is not the same as separate physical databases.** Option A enforces the former only.
- **Membership supplies facts it owns; Election makes election decisions.** Membership may publish that a membership ended; Election decides what that means for a participant.

## 2. Decision 2: remediation plan accepted as PROPOSED

**Plan:** `docs/plans/20261102-0339-membership-election-boundary-remediation-plan.md` (ES-004.2). **Backlog item:** `PBDIGIT-318`.

| Slice | Status after this decision |
|---|---|
| S1: guard rules (architecture and static checks) | **first candidate**; still needs its own approval to start |
| S2: remove V1 | pending approval; gated (§3 of the plan) |
| S3: replace V2 by a contract | pending approval; gated |
| S4: resolve V3 | pending approval; gated |

## 3. Verified findings this rests on (HEAD `041cd5dd8` / `ce84016a6`)

**V1:** `Member::endMembership` writes `election_memberships` (`app/Models/Member.php:184-189`).
- No production caller found.
- It is organisation-filtered only by the request context (`app/Models/ElectionMembership.php:32`).
- **A unit test asserts the write as expected behaviour** (`tests/Unit/Models/MemberTest.php:149-162`).

**V2:** the Election adapter behind the port `VoterEligibilityPolicy` (`app/Contexts/Elections/Domain/Policies/VoterEligibilityPolicy.php:27`) reads Membership and Organisation data directly in five places (`app/Contexts/Elections/Infrastructure/Policies/EloquentVoterEligibilityQueryService.php:91,108,153,179,197`).

**V3:** cross-context foreign key.
- `election_memberships` → `user_organisation_roles` **ON DELETE CASCADE** on PostgreSQL (`database/migrations/2026_03_17_213212_create_election_memberships_table.php:39-42`).
- Re-pointed to `organisation_users` only on MySQL (`2026_04_16_063404_…:21-30`).
- The deployed state is unknown.

### Correction to V3 (2026-11-02, found while preparing S1)

**The V3 finding above was incomplete. In the migration source it is wrong.**
- `database/migrations/2026_05_19_000001_harden_election_memberships_for_election_only_mode.php` **drops** that foreign key in `up()` (`:16-18`, `:40-43`: "The database FK is gone. Integrity is now application responsibility"). It re-adds it only in `down()`. In the migration end state, `election_memberships` has **no** foreign key to `user_organisation_roles` or `organisation_users`. The deployed state remains unknown.
- **The actual integrity coupling found instead (V3′):** `voters.member_id` → **`members`** with **ON DELETE CASCADE** (`database/migrations/2026_03_07_145529_create_voters_table.php:30`). No later migration changes it. Deleting a Membership row deletes Election-side voter rows.

The original V3 text is kept above as history.

## 4. Open items

| # | Question | Owner |
|---|---|---|
| OQ-ME-1 | What happens to an active election participant when their membership ends (Election's rule)? | Election owner + PO |
| OQ-ME-2 | Is the V3 cascade intended? Which facts must remain to explain an election after a role or membership record changes? | PO + Election / Governance owners |
| OQ-ME-3 | Does a cross-context foreign key count as "shared persistence" under the ratified rule? | ARB (interpretation) |
| OQ-ME-4 | Contract semantics for V2: fact vs decision; current vs as-of; unknown vs negative; source unavailable; organisation scope | ARB + Membership owner |

## 5. Traceability

- PO decision "approve both, record them and commit", 2026-11-02.
- Principal Reviewer review of the plan (2026-11-02).
- Governance map §5 (`2026-11-02-governance-domain-discovery-map-roadmap-and-readiness.md`).
