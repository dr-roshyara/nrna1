# PBDIGIT-341: Several live flows find, reuse or create accounts by matching e-mail (conflicts with D-75)

**Type:** Strategic conformance gap (security and privacy) · **Epic:** none · **Created:** 2026-11-09 · **Raised by:** found by Claude during the Identity participant-access discovery.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · **design first**: the replacement is the claim-code mechanism (Identity tactical design), and K4 is open |
| **Classification** | **observed in code** (static, HEAD `e2051cbbe`) |
| **Analysis** | `docs/publicdigit/architecture/reviews/2026-11-09-identity-participant-access-strategic-discovery.md` §0.3 |

## Gap (FACT)
D-75 (XD-3 act §2.6): "No automatic account creation, takeover or linking based on matching email addresses or names." Live code does exactly that:
- **Voter import:** finds an existing account by e-mail, reuses it, and shows the importing organisation "Existing user" (`app/Services/VoterImportService.php:217-226`; also `:121`, `:172`). One organisation can learn that an address already has a PublicDigit account.
- **Membership approval:** reuses or creates the account by the applicant's e-mail (`MembershipApplicationController.php:221-224`; G-2 of the mobile baseline).
- **Adding a participant:** looks up the account by e-mail (`OrganisationParticipantController.php:73`).
- **`ImportUsers` command:** looks up by e-mail (`app/Console/Commands/ImportUsers.php:565`).

## Business impact
Cross-organisation inference (I-3); a person's account attached to an organisation they never accepted; one human merged into another's account where e-mail addresses are shared within a family.

## Scope when authorised
1. Interim, small: remove the "Existing user" distinction from what an organisation sees.
2. Replace each flow with an organisation-issued claim code (Identity tactical design), after the Principal decides K4.

No code change before the design.

## Related
D-75; `PBDIGIT-340`; M-TDD-01 (J-1); mobile baseline G-2.
