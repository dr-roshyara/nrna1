# Task (frozen): a minimal repair of Protocol C (r2): human-authorization compliance plus fail-closed guards

Use ONLY the files here:
- `PROTOCOL-C.md`, `scope_c.sh`, `CHANGELOG-C-r1.md`: r1, review verdict PASS_WITH_LIMITATIONS;
- `REVIEW-r1.md` / `.json`;
- `PREREG.md`, `TARGET-FACTS.md`, `ENVIRONMENT.md`, `QUESTIONS.md`, `R-SELF-RULE.md`;
- `HUMAN-SCOPE.md`.

No commands, no web. Change **only** the following.

1. **SCOPE-1 (blocking; found by the main analyst's safety check):** the human **declined signer verification (H2 = NO)** and excluded "signer investigation" from H1 (see HUMAN-SCOPE.md).
   - Remove every read or output of commit signature or verification data: `signature_verified` and `verification_reason` (scope_c.sh l.82–83), plus any other `verification`, `signature` or `gpg` field.
   - State in PROTOCOL-C §1 that signature or verification fields are **out of scope by human decision** and are not read.
2. **NB-1:** the identity guard must fail **closed** on any grep error (exit status ≥ 2) and if `ids.txt` cannot be written or is missing. Replace the `! grep` idiom with explicit exit-status handling.
3. **NB-5:** in `inside()`, any `git rev-parse` failure **other than** "not a git repository" must be treated as inside (refuse), i.e. fail closed.

Leave NB-2, NB-3, NB-4, NB-6 and NB-7 as one-line disclosed limitations in §6. Everything else stays **unchanged**.

Write the repaired **PROTOCOL-C.md** and **scope_c.sh**, plus **CHANGELOG-C-r2.md**.
