# Review of SCHEMA-CORE (frozen)

You have SCHEMA-CORE.md, AUTHOR-NOTES.md, CORE-SPEC.md, the TASK, HYPOTHESES.md (for the neutrality check), and RECORDS.json + SOURCES.md (**for the anchoring and codability checks only**).

Audit:
1. **Fidelity:** does SCHEMA-CORE implement CORE-SPEC exactly? Is anything added that decides a theory question? In particular: does it decide whether self-performance "counts" as an act? It must not; it only records it.
2. **Anchoring:** does any example share wording, structure or answer pattern with a specific record in RECORDS.json?
3. **Neutrality:** does any definition or boundary rule make the data favour H-D or H-B? For example, by making NORM_STATED depend on an act, or ACT_REPORTED depend on governance vocabulary.
4. **Codability (dry run):** code R01–R36 yourself with SCHEMA-CORE only. For each field, say how many records are determinate (one clearly correct answer) vs debatable. **Do not output your codes**; output only the counts and the debatable record ids with a one-line reason. Your codes must not become a reference.
5. **Length:** ≤ 1,000 words?

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** · **FAIL** (anchoring, a theory decision or a neutrality leak is blocking).

Write **REVIEW.md** and **REVIEW.json** ({verdict, fidelity_issues: [...], anchoring_issues: [...], neutrality_issues: [...], dry_run: {ACT_REPORTED: {determinate, debatable: [ids]}, ACT_SELF_PERFORMED: {...}, NORM_STATED: {...}}, words, blocking: [...], nonblocking: [...]}).
