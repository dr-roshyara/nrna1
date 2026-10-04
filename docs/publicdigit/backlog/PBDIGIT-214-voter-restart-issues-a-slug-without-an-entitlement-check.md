# PBDIGIT-214 — `voter.restart` issues a voting slug without consulting voting entitlement

**Type:** Defect (latent, credential issuance) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-04
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)
**Found by:** the PBDIGIT-213 C3 `can_vote` classification and bypass analysis (2026-10-04). See `developer_guide/election/voter_admission/03_c3_identity_admission.md` §Legacy classification.

| | |
|---|---|
| **Status** | 🟡 **OPEN — latent; no voting bypass today.** Not authorised. |
| **Problem** | `POST voter/restart` (`VoterSlugController@restart`, :170–252) mints a VoterSlug with no `VotingEntitlementQuery` (or any) check. |
| **Why no bypass today** | Every real `v/{vslug}` route runs `ensure.election.voter` → `standingOf()`, so an unentitled holder of that slug is refused (pinned by `LegacyVotingFlagsBoundaryTest` / `IdentityVerificationAdmissionTest`). |
| **Business consequence** | Credentials can exist for people who may not vote; the issuance boundary disagrees with the entitlement boundary. |
| **Suggested fix** | Consult `VotingEntitlementQuery::standingOf()` before issuing (no redesign of the slug system). |
