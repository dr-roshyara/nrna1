# PBDIGIT-276: The exception renderer logs the raw voter slug

**Type:** Security defect (log hygiene) · **Epic:** none (voting) · **Created:** 2026-10-11 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from the `PBDIGIT-273` independent review. **Triaged 2026-10-11** (Principal Reviewer: split the original grouped item). Correctness defects moved to `PBDIGIT-277`, documentation and test hygiene to `PBDIGIT-278`. **Index row:** added 2026-10-11.

| | |
|---|---|
| **Status** | `OPEN` · not authorised |
| **Classification** | security, **verified in code** (not yet reproduced by a test) |

## Finding
- `bootstrap/app.php:213-221`: the `VotingException` renderer logs `'url' => $request->url()` at **error** level. For `v/{vslug}/…` routes the URL contains the slug string, so every rejected voting request writes the raw slug to the logs.
- Since `PBDIGIT-273`, `VerifyVoterSlug`'s own logs and exception context carry only the slug's database id. This renderer is the remaining path.

## Acceptance
- [ ] A test (database-free where possible) shows that no raw slug reaches the log for a rejected `v/{vslug}/…` request.
- [ ] The renderer logs the route name, or a URL with the `vslug` parameter redacted, instead of the raw URL.
- [ ] The user id still appears in the log; whether user id plus slug id together raise an anonymity concern is checked against ADR-T11 before release.

## Coverage by PBDIGIT-274 (recorded 2026-10-13; evidence only, the status is the Human PO's to set)
This finding is site T1 of `PBDIGIT-274` (plan section 12, O-7 = B), implemented in S9c.
- **Acceptance 1 (a test shows no raw slug in the log): covered.**
  - `tests/Isolated/Pbdigit274/Database/RawSlugLoggingTest.php::test_the_voting_exception_renderer_logs_no_raw_link` drives a rejected `v/{vslug}/…` request (JSON and web) and captures every log record. The link was in 2 of 13 records before the change, 0 after.
  - Statically, `tests/Architecture/NoRawVoterSlugInLogsTest.php` (database-free).
  - Evidence: `docs/publicdigit/election/evidence/2026-10-13-pbdigit274-s9c-log-inventory/` (runs 02 and 04).
- **Acceptance 2 (route name instead of the URL): covered.** `bootstrap/app.php` now logs `'route' => $request->route()?->getName()` (commit `712fa9250`).
- **Acceptance 3 (user id plus slug id against ADR-T11): NOT done.** The renderer still logs `user_id`, plus the exception context with `slug_id`. Whether that pair raises an anonymity concern still needs the ADR-T11 check before release.
- **Proposed:** close items 1–2 as covered by 274, and keep item 3 open, or move it to a review. **The decision is the PO's** (274 plan section 2.1a: "276 stays OPEN until the PO records the outcome").
