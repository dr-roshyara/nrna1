# Review of a minimal evidence-capture protocol (frozen)

A researcher wrote PROTOCOL-A.md and capture_a.sh (TASK.md; inputs ENVIRONMENT.md, TARGET-FACTS.md, REFERENCE-r2-rank0-script.sh). Use only the files here. You cannot run code: read the script line by line.

Audit **only**:
1. **Read-only safety:** does any command write to the repository, fetch, or change config or refs? List every git subcommand used.
2. **Determinism and reproducibility:** locale and TZ; sort order; config isolation; identical output from the root and from a subdirectory (the self-path is resolved before `cd`).
3. **Exact target matching:**
   - over-matching: do WP-4C-10, WP-4C-1.2 or WP-4C-2 match?
   - under-matching: Unicode hyphens; the `wp4c1-` alias; paths; trailers.
4. **Classification:**
   - is EXECUTION-TRACE vs DOC-OR-GOVERNANCE vs MENTION-ONLY deterministic and operational?
   - are the path patterns sensible for a Laravel / Vue repository (app/, tests/, resources/, routes/, database/ vs docs/, .claude/, engineering/, developer_guide/)?
   - are merges handled without promoting side branches?
5. **Timestamps:** author vs committer recorded separately; labelled RECORDING_ORDER only (M-TIME).
6. **Absence semantics:** NOT-EVIDENCED-IN-CAPTURE, with coverage stated.
7. **Provenance and hashing:** SHA256SUMS of all outputs; the script's own hash recorded correctly.
8. **Neutrality:** no hypothesis, falsifier, conformance judgement or scoring anywhere.
9. **No personal identities** in the output.

**Do NOT score any hypothesis.**

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** (no blocking issue) · **FAIL**, with every blocking issue. **FAIL means the script must not run.**

Write **REVIEW.md** and **REVIEW.json** ({verdict, git_subcommands: [...], blocking: [...], nonblocking: [...], matching_tests: [{string, expected, script_behaviour}], neutrality_ok, identity_ok}).
