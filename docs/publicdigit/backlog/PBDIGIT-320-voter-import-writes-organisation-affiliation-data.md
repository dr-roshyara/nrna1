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
- **BR-9 (open) / OQ-C-5 (ARB):** the storage and ownership of OrganisationAffiliation, and which store represents roles: who owns `organisation_users` and `user_organisation_roles` (business rules `:31`; Decision C `:124`).
- **OQ-ME-3 (ARB):** whether a cross-context foreign key is shared persistence; and **Q-new-1** (ARB, routed 2026-11-02; register O-17): whether a direct cross-context read or write of another context's tables is.
- *(Corrected 2026-11-02 on the PO's instruction, context map C-9. The earlier wording routed the ownership question to OQ-ME-4, whose recorded wording is the contract semantics of V2 (separation decision record `:80`), not store ownership.)*
- Related, proposed: BR-10 (an import creates no User affiliation with PublicDigit) and INV-AFF-1 (organisation-owned affiliation). See the strategic register.

## Not in scope here

- any change to `VoterImportService` or to the import behaviour;
- adding the site to the PBDIGIT-318 baseline;
- classifying it.

All of these need the decisions above and a separately authorised slice.

## Related

- PBDIGIT-318: inventory X-1…X-9 and the S1/S1b guards. X-8 lies outside the scanned paths, so the guard does not see it.
- `docs/publicdigit/reviews/2026-11-02-membership-election-separation-decision-record.md` §4 (OQ-ME-3);
- `docs/publicdigit/business_rules/20261101_1739_person_account_affiliation_business_rules.md` (BR-9);
- `docs/publicdigit/reviews/2026-11-01-organisation-dc-default-organisation-relationship-decision-record.md` (OQ-C-5);
- context map `docs/publicdigit/architecture/context-map.md` §10 (Q-new-1), C-9.
