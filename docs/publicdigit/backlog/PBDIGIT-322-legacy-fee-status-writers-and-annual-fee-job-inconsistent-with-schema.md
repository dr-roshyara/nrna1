# PBDIGIT-322: Legacy fee-status writers and the annual-fee job disagree with the schema

**Type:** Defect (legacy Membership / fees) · **Epic:** none · **Created:** 2026-11-02 · **Raised by:** E0b-S discovery (`docs/publicdigit/reviews/2026-11-02-membership-e0b-s-types-obligations-dues-standing-discovery.md` §F items 2–3)

| | |
|---|---|
| **Status** | `OPEN` · **tracked only**. No change authorised; no change made |
| **Classification** | item 1: **observed statically** in code and migrations. Item 2: **inferred**, not executed. Production schema, data and logs are **not verified** |
| **Scope** | `app/Listeners/Membership/RecalculateMemberFeeStatus.php`; `app/Http/Controllers/Organisation/OrganisationMemberInvitationController.php:216`; `app/Services/NewsletterService.php:184`; `app/Models/Member.php:104-133`; `app/Contexts/Membership/Infrastructure/Jobs/GenerateAnnualMembershipFees.php`; schedule `routes/console.php:49` |

## Finding 1: `fees_status` values written vs allowed (static)

- **Allowed on PostgreSQL:** `2026_04_19_000001_convert_all_enums_to_varchar_for_postgresql.php:43` replaced the enum `members.fees_status` with a CHECK constraint allowing **`exempt`, `pending`, `paid`, `overdue`**.
- **Written by the code:**
  - `RecalculateMemberFeeStatus` writes `unpaid` (`:30`, `:43`) and `partial` (`:42`);
  - `OrganisationMemberInvitationController.php:216` writes `unpaid`.
- **Read by the code:**
  - `NewsletterService.php:184` selects `fees_status IN ('unpaid', 'partial')`;
  - `Member::getVotingRightsAttribute` matches `partial`.
- **Expected effect [inferred]:** wherever the constraint exists, those writes fail and those reads never match.
- **Not verified:** whether `2026_04_19_000001` ran in each environment (compare roadmap OQ-01).

## Finding 2: `GenerateAnnualMembershipFees` (inferred)

The job is scheduled daily at 01:00 (`routes/console.php:49`) and runs only when `app()->isProduction()`. For each active member expiring within 30 days, it creates a `MembershipFee` with:
- `amount` = `membershipType->annual_fee`. **There is no such attribute** (the column is `fee_amount`), so the amount would be 0.
- `fee_amount_at_time` **not set**. The column is `decimal NOT NULL` without a default (`2026_04_03_155707_create_membership_fees_table.php:24`), so the insert is expected to fail.
- `description`, which is not in `MembershipFee::$fillable` and is silently dropped.

**Expected effect [inferred]:** if any member matches, the job throws daily and creates no fees. If an insert ever succeeds, the fee has amount 0. Not executed; production logs not checked.

## Why it matters

- Dues compliance (future Standing, E0b-S) and the legacy voter queries depend on these values.
- Silent failures here can hide arrears, or make every member look compliant.

## Not in scope here

- Any code, schema or data change.
- The target design: obligation ownership is decision E-1; Standing is E-2.
- The two-column problem (`fees_status` vs `fee_state`) and voting derivation (PBDIGIT-318 V1b/V2). These are recorded in the E0b-S report §F and are handled by the roadmap (W8/W9).

## To resolve (needs authorisation)

1. A read-only check, per environment, of whether `2026_04_19_000001` ran (constraint present) and of the job's production logs. **Needs Ops / PO authorisation.**
2. A decision on whether to repair the legacy writers and the job now, or to retire them under the roadmap (W8/W9).

## Related

- E0b-S discovery report §C, §F;
- PBDIGIT-318 (V1b, V2);
- roadmap `docs/publicdigit/membership/architecture/brainstorming/cross_domain_brainstorming/20261016_2219_membership_architecture_verification_and_completion_plan.md` (OQ-01, W8, W9).
