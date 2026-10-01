# Task (frozen): a targeted repair of Protocol C (r1)

Use ONLY the files here:
- `PROTOCOL-C.md`, `scope_c.sh`: the version to repair;
- `REVIEW-r0.md` / `.json`: its review, verdict FAIL;
- `PREREG.md`, `TARGET-FACTS.md`, `ENVIRONMENT.md`, `QUESTIONS.md`, `R-SELF-RULE.md`.

No commands, no web. Repair **only** the following; change nothing else.

1. **B1–B3 (blocking): one status-gating rule for every channel.**
   - A derived field is computed **only from a 200 response**.
   - For any non-200, every derived field of that channel is **NA**, never false or 0. The existence label follows the PREREG mapping:
     - 401/403/404 (and 422 where documented) → NOT_ACCESSIBLE, or NOT_RETAINED where retention applies;
     - transport 000 or another code → NOT_ACCESSIBLE[transport];
     - budget stop → NOT_ACCESSIBLE[not_called_budget].
   - The no-PR branch is taken **only** on a 200 with an empty list. A failed association lookup makes every dependent channel NOT_ACCESSIBLE[dependency_failed], not NOT_EVIDENCED_ON_HOST.
   - A channel **never called** is never NOT_EVIDENCED_ON_HOST.
   - **rulesets is repository-scoped:** always call it, independent of the PR outcome.
   - With no PR, the branch-scoped channels (protection, branch_rules) are reported as NOT_APPLICABLE[no_associated_pr]. Say in §2 that this does **not** show the commit's branches are unprotected.
2. **N1:** record the observation time in summary.json as both the host `Date` response header (HOST_GENERATED) and the client clock (CLIENT_SUPPLIED), labelled.
3. **N3:** the WHY note must reflect the actual cause (budget / transport / HTTP code).
4. **N4:** add `*_truncated` flags for checks, runs, rulesets and branch_rules; pass `per_page` on status. Restrict the checks HOST_GENERATED decision to "the returned page", and say so.
5. **N5:** the identity guard must not be tripped by the script's own fixed note strings. Remove product or proper names from notes. **Keep the guard fail-closed.**
6. **N6:** timeline events without `created_at` are counted separately as NO_TIMESTAMP and never labelled HOST_GENERATED.
7. **N7:** create OUT with `mkdir` (not `-p`) after the emptiness check. Refuse to run if `git` is absent.

Leave N2, N8, N9 and N10 as **one-line disclosed limitations** in PROTOCOL-C §6. Keep the bound, GET-only, budget, identity policy, M-TIME and neutrality **unchanged**.

Write the repaired **PROTOCOL-C.md** and **scope_c.sh** (≤ ~220 lines), plus **CHANGELOG-C-r1.md** listing each change by defect id.
