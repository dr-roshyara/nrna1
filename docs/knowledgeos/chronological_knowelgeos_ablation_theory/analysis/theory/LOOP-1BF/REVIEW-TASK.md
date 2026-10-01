# Review of Protocol C (frozen)

A researcher wrote PROTOCOL-C.md and scope_c.sh (TASK.md). Use only the files here; read the script line by line.

Audit:
1. **Read-only on the host:** list every network call with its HTTP method. Is anything other than GET present, or any mutating `gh` subcommand? Is any token printed or written?
2. **Bound:** one commit; associated PRs capped; one base branch; the window; budget ≤ 40; no open-ended search or pagination. Can any path exceed the bound?
3. **Identity:**
   - Can any login, name, e-mail, avatar or URL reach OUT?
   - Do raw responses stay in TMP?
   - Does the guard fail closed?
4. **Determinism / local safety:** locale, TZ, sorting; self path before `cd`; OUT refusal; removal only of the temp directory; explicit exits.
5. **Reachability / no forced negative:** for each channel, can EXISTS, NOT_EVIDENCED_ON_HOST, NOT_ACCESSIBLE and NOT_RETAINED each be produced? Is a 403/404 ever mislabelled as absence? Is the timestamp-generator labelling correct per field? Is norm state ever inferred from a check or a merge?
6. **M-TIME:** host time vs client time vs decision time are kept separate.
7. **Neutrality:** no hypothesis, conformance or scoring.

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** (no blocking issue) · **FAIL**, with every blocking issue. FAIL means the script must not run.

Write **REVIEW.md** and **REVIEW.json** ({verdict, calls: [{line, method, endpoint}], bound_ok, identity_ok, readonly_ok, reachability: {...}, timestamp_labels_ok, blocking: [...], nonblocking: [...]}).
