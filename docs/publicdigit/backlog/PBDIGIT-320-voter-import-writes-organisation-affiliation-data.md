# PBDIGIT-320: Voter import writes organisation-affiliation data (`VoterImportService`)

**Type:** Architecture finding (cross-context write) · **Epic:** none · **Created:** 2026-11-02 · **Raised by:** independent review of PBDIGIT-318 S1, recorded there as inventory item **X-8**; a separate item by PO decision of 2026-11-02

| | |
|---|---|
| **Status** | `OPEN` · **tracked only**. No behaviour change authorised. **Not classified as compliant** |
| **Classification** | **observed in code** (static). Runtime frequency and data affected are **not verified** |
| **Scope** | `app/Services/VoterImportService.php:284-299` |

## Finding (static)

During voter import (an Election-side workflow), `VoterImportService` **writes** the organisation-affiliation stores `user_organisation_roles` and `organisation_users` (`firstOrCreate`).

This is an Election → affiliation **write**. It is a kind of coupling outside the V1–V3′ classification of PBDIGIT-318.

## Why it is open, not decided

Whether this write is a violation depends on decisions that are not taken:
- **OQ-ME-4 (ARB):** who owns `organisation_users` and `user_organisation_roles`: Membership, Organisation (affiliation) or Governance?
- **OQ-ME-3 (ARB):** what "no shared persistence" prohibits.
- Related, proposed: BR-10 (an import creates no User affiliation with PublicDigit) and INV-AFF-1 (organisation-owned affiliation). See the strategic register.

## Not in scope here

- any change to `VoterImportService` or to the import behaviour;
- adding the site to the PBDIGIT-318 baseline;
- classifying it.

All of these need the decisions above and a separately authorised slice.

## Related

- PBDIGIT-318: inventory X-1…X-9 and the S1/S1b guards. X-8 lies outside the scanned paths, so the guard does not see it.
- `docs/publicdigit/reviews/2026-11-02-membership-election-separation-decision-record.md` §4 (OQ-ME-3, OQ-ME-4).
