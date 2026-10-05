# PBDIGIT-249 — Email verification as election-scoped admission evidence

**Type:** Feature — Admission (new requirement) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Proposal:** `docs/publicdigit/reviews/2026-10-05-identity-verification-lifecycle-and-email-proposal.md` (§E, F)
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Existing-story search (freeze rule):** none owns admission email evidence. `users.email_verified_at` (`MustVerifyEmail`) is account-level and is stamped without any challenge by the voter / organisation imports, social login and a console command, so it is not admission evidence.

| | |
|---|---|
| **Status** | 🟡 `OPEN` — design decided (PO 2026-10-05); **RED contract to be finalised; GREEN not authorised** |
| **Scope** | **Election-scoped:** election + voter + email address. Evidence in one election never admits in another |
| **Storage** | Dedicated email-verification evidence storage. Not `voter_verifications`, not `users.email_verified_at` |
| **Requirement** | New `AdmissionRequirement::EMAIL_VERIFICATION`; optional unless the election's admission policy requires it. **Email verified ≠ identity verified** |
| **Challenge (first implementation)** | Voter-initiated · random token stored **hashed** · bound to election + voter + email address · single-use · expires after **60 minutes** · a newer challenge supersedes the older one · used / expired / superseded → fail closed · raw tokens never logged. No officer-issued challenges and no complex resend policy yet |
| **Admission semantics** | ALL-of is unchanged: `[]` none · `[IDENTITY]` · `[EMAIL]` · `[IDENTITY, EMAIL]` both. Implementing email never makes it required; the Admission Policy is authoritative |
| **Column** | The Voter Management **Email Verification** column, shown only while the policy requires `EMAIL_VERIFICATION` (COL-D1) |
| **RED to finalise** | challenge generated / sent; valid accepted; invalid / expired / replayed / superseded rejected; evidence created; no identity equivalence; admission effect only when required; election / organisation isolation; address change ends the evidence (proposal §E — confirm) |

**Traceability:** proposal §E, F · PBDIGIT-213 admission model (AdmissionRequirement / Evidence / Decision, ALL-of).
