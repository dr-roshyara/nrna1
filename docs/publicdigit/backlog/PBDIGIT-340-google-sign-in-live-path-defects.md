# PBDIGIT-340: Social sign-in (Google, Facebook): plain-text constant password, raw errors shown to visitors, takeover code still present

**Type:** Security defect · **Epic:** none · **Created:** 2026-11-09 · **Raised by:** found by Claude during the Identity participant-access discovery.

| | |
|---|---|
| **Status** | `IMPLEMENTED` (working tree, not committed) · **removal** decided by the Principal (P-I5, 2026-11-09: "remove google sign-in"; Facebook: "also remove it. they are out of date") · RED → GREEN verified |
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

## Decision and resolution (2026-11-09)
- **P-I5 decided: remove social sign-in**, Google and Facebook.
- **Additional evidence:** the current `users` table (UUID, `2026_03_05_000002_create_uuid_users_table.php`) has **no `google_id` and no `facebook_id` column**. Both callbacks failed on their first query and printed the SQL error through `dd()`; neither could have signed anyone in since March 2026. Development database: 0 accounts with the plain-text constant password. Facebook (`SocialController`) had the same defects (`:48`, `:60`).
- **Removed:**
  - `GoogleLoginController`, `GoogleController`, `SocialController`;
  - routes `login/google`, `login/google/callback`, `login/facebook`, `login/facebook/callback`, and the commented `auth/google` lines;
  - the Google button in `resources/js/Components/Auth/Login.vue` (an orphan component, no importer);
  - the Alt+G shortcut in `resources/js/Components/General/JoinNetwork.vue`.
- **Kept:** the Google Calendar integration (`google`, `google/oauth`, `google/webhook`; `GoogleAccountController`, `GoogleWebhookController`).
- **Left as is (out of scope):**
  - the `laravel/socialite` package (no remaining user found; removing it changes `composer.lock`, so a separate chore);
  - the generated, unimported `resources/js/ziggy.js`, which still names the old routes.
- **Test:** `tests/Feature/Routes/SocialSignInRemovedTest.php` (database-free). RED on HEAD `7a8f35f17` (2 failures), GREEN with the removal (3 tests, 16 assertions), in a clean worktree.
- **No regression:** `tests/Feature/Routes` has 9 problems on HEAD vs 7 after (the 2 RED tests now pass; the 7 need a database the worktree could not reach); vitest 12 failed / 592 passed (unchanged); design-check 91.
- **Deployment note:** run `composer dump-autoload` (or the normal `composer install`) so the class map drops the deleted controllers.
