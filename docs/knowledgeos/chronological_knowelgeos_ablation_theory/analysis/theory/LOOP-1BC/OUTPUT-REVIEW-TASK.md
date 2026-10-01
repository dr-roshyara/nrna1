# Review of a sealed evidence-capture output (frozen)

Files:
- `PROTOCOL-A.md` and `capture_a.sh`: the reviewed protocol;
- `PACKAGE/`: its output, run verbatim. The run from a subdirectory produced byte-identical output;
- `TRACE-COMMITS.txt`: `git show --stat` plus the messages of the 3 EXECUTION-TRACE commits and the 1 UNCLASSIFIABLE commit, with author identities removed.

Use only these files; you cannot run code.

Audit **only**:
1. **Integrity:** do the PACKAGE files agree with each other? Do the counts in 30_summary.tsv match 20_commits.tsv? Is the earliest EXECUTION-TRACE correct, by committer date and by author date?
2. **Classification:** for each of the 4 commits, does the class follow from the protocol's rules and the changed paths? Is each EXECUTION-TRACE genuinely a code / test change referencing WP-4C-1, rather than a sibling item or a mere mention?
3. **Anomalies, to record only:**
   - (a) the earliest trace commit changes a plan file whose **name** implies 19:00 on 2026-08-04, while the commit is recorded at 00:30Z. Describe this. **Do not infer decision or clock facts**: M-TIME says recording ≠ decision.
   - (b) the UNCLASSIFIABLE commit appears to contain **research artefacts of this research programme itself** (analysis or search files dated 2026-09-30). Should such self-generated artefacts be excluded from the historical evidence by an explicit rule? Recommend one, without applying it.
4. **Neutrality:** does anything in the package interpret conformance or any hypothesis? (It must not.)
5. **Coverage and absence semantics:** stated correctly (local refs; completeness vs the host NOT shown)?

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** · **FAIL**.

Write **REVIEW.md** and **REVIEW.json** ({verdict, integrity_ok, classification: [{sha, class, correct, why}], earliest_trace_confirmed, anomalies: [...], self_contamination_rule_recommendation, neutrality_ok, coverage_ok, issues: [...]}).
