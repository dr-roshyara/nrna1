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
