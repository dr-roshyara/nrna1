# PBDIGIT-265: Full-Membership voter eligibility uses different predicates for the bulk list and the single-user decision

**Type:** Investigation (possible defect, Election context) · **Created:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable). Found during the legacy `members` reader inventory for the Membership cutover gate. **Index row:** added 2026-10-09.

| | |
|---|---|
| **Status** | `OPEN — investigation` · not authorised |
| **May overlap** | `docs/publicdigit/reviews/2026-08-09-iervp-election-runtime-verification.md` (IERVP-2 `grants_voting_rights`; expiry gates assignment, not the ballot) and the entitlement-retention ARB package `2026-08-12-arb-decision-package-election-entitlement-retention.md`. **Check those first;** close this item as a duplicate if they already decide it |

## Evidence (static; runtime impact not verified)

- **Bulk subset** (`EloquentVoterEligibilityQueryService.php:104-121`) requires all of:
  - `status='active'`;
  - `fees_status IN (paid, exempt)`;
  - type NULL or `grants_voting_rights = true`;
  - `membership_expires_at` NULL or in the future;
  - not deleted.
- **Single-user context** (`:174-214`) loads only `status` and `fees_status`. `FullMembershipPolicy::decideForContext` (`FullMembershipPolicy.php:55-61`) decides on those two alone.
- **Consequence if both paths gate the same act:** a member whose type grants no voting rights, or whose `membership_expires_at` has passed while `status` is still `active` (`ExpireMemberships` runs daily), is excluded by the bulk path but accepted by the single-user path.

## Question

Is the difference intended (for example, the entitlement-retention ruling: once assigned, expiry does not remove the entitlement), or a defect? The answer belongs to the Election owner.

**Out of scope for Membership.** Relevant to the cutover gate because the reverse projection must serve whatever predicate Election finally adopts.
