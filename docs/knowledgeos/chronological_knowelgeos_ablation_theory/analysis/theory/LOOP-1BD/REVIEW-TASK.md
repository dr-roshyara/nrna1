# Review of a minimal scoping protocol (frozen)

A researcher wrote PROTOCOL-B.md and scope_b.sh (TASK.md; inputs QUESTIONS.md, ENVIRONMENT.md, ROOTS.txt, REFERENCE-r2-scoping-script.sh). Use only the files here; read the script line by line.

Audit **only**:
1. **Read-only safety:** list every command. Is any write outside OUT, any fetch, any config change?
2. **Determinism / reproducibility:** locale, TZ, sort, config isolation; self-path resolved before `cd`; OUT refused inside the work tree.
3. **Boundedness:** existence or scoping only; no broad archaeology; no content beyond what is justified.
4. **Reachability:** can every outcome of every question actually be produced? In particular, can a candidate ever be found INDEPENDENT? Are forced negatives absent? Is NOT_DETERMINABLE used instead of NO where existence does not decide?
5. **Independence rule:** does it treat the channel's own writers as executors by construction (the known defect)? Is a self-attested record rejected as independent merely for having a timestamp?
6. **Self-contamination:** are docs/knowledgeos/ artefacts excluded as candidates?
7. **Identities:** none in the output.
8. **Neutrality:** no hypothesis, conformance judgement or scoring.

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** (no blocking issue) · **FAIL**, with every blocking issue. **FAIL means the script must not run.**

Write **REVIEW.md** and **REVIEW.json** ({verdict, commands: [...], blocking: [...], nonblocking: [...], reachability: {...}, independence_rule_ok, self_contamination_ok, identity_ok, neutrality_ok}).
