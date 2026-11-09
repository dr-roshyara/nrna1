# PBDIGIT-340: Google sign-in: plain-text constant password, raw error shown to visitors, takeover code still present

**Type:** Security defect · **Epic:** none · **Created:** 2026-11-09 · **Raised by:** found by Claude during the Identity participant-access discovery.

| | |
|---|---|
| **Status** | `OPEN` · not authorised |
| **Classification** | **observed in code** (static, HEAD `e2051cbbe`); not run |
| **Analysis** | `docs/publicdigit/architecture/reviews/2026-11-09-identity-participant-access-strategic-discovery.md` §0.2, §4 A-8 |

## Defect (FACT)
1. **Live route:** `login/google/callback` → `GoogleLoginController::callback` matches accounts by `google_id` (`:33`), not by e-mail.
2. A first Google sign-in creates an account with the password `'dummypass'` (`:60`). `User` has no `hashed` cast (`app/Models/User.php:141-145`), so the constant is stored **as plain text**. Password sign-in for that e-mail then fails in the hasher, rather than succeeding, but a known constant sits in the database.
3. When the Google e-mail already belongs to an account (for example an imported voter), the unique e-mail constraint fails and `dd($e->getMessage())` (`:70`) shows the database error, including the e-mail, to the visitor.
4. `GoogleController` still contains `User::updateOrCreate(['email' => …])` (`:44`), an e-mail takeover. Its routes are commented out (`routes/user/googleRoutes.php:15-16`, since 2022); re-enabling them would reopen it.

## Business impact
Error disclosure to anyone who tries Google sign-in with a registered address; a constant credential in the database; a latent takeover path. Members who were imported cannot use Google sign-in at all.

## Scope when authorised
Depends on the Principal's choice P-I5 (keep or remove social sign-in). If kept: no stored password for social accounts; no `dd`; on an e-mail collision, ask the person to sign in and link from their account (D-75: never link by e-mail); delete the unrouted takeover code. If removed: delete both controllers and routes.

## Related
D-75 (XD-3 act §2.6, §4: correction proposed); `PBDIGIT-341`.
