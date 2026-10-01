# Task (frozen): the discrimination-readiness assessment

Use ONLY the files here:

| File | Content |
|---|---|
| `HYPOTHESES.md` | H-D vs H-B, frozen |
| `STATE.md` | the reviewed research state |
| `IDENTIFIABILITY.json` | the reviewed identifiability analysis per prediction P1–P8 |
| `PROTOCOL-A-RECONCILIATION.md` | the sealed execution-trace capture |
| `PROTOCOL-B-RECONCILIATION.md`, `PROTOCOL-B-OUTPUT-REVIEW.md` | the sealed norm-status channel scoping |
| `QUESTIONS.md` | the definition of "independent" |
| `PREREG.md` | the outcomes |

No commands, no web.

Answer **one** question: does the acquired evidence contain an **independent norm-state observable**, as defined in PREREG.md / QUESTIONS.md?

1. **Inventory:** list every observable in A and B that bears on norm state or on work timing. For each, give:
   - what it measures (norm state / work / recording time);
   - its author class (norm-maker / executor / self-asserting / independent / unknown);
   - its M-TIME label;
   - whether it is independent.
2. **D1 check.** D1: work evidence cannot separate "norm changed without an act" from "work violated an unchanged norm" without an independent norm-state observable. Does any inventory item break D1? Apply the symmetry rule explicitly: for each candidate, how H-D and how H-B would each explain it.
3. **Verdict:** NO / YES / NOT_DETERMINABLE, with the decisive reason.
   - If NOT_DETERMINABLE: name the missing fact.
   - If YES: name the observable; do **not** design or run a test.
4. **What would change the verdict:** list the candidate evidence channels that are **outside the authorized scope**: host-side channels, signer verification, the R-SELF rule, a non-Claude Gate 1 coding. For each, say:
   - what it could establish;
   - why it needs a human decision;
   - its expected information value (high / medium / low, with the reason).
5. **Strength audit:** quote every sentence in your output that makes an inferential claim, and give the evidence each rests on.

Write **READINESS.md** (at most ~2 pages) and **READINESS.json** ({verdict, decisive_reason, inventory: [...], d1_broken, symmetry: [...], missing_fact, out_of_scope_channels: [{channel, could_establish, needs_human_because, info_value}], strength_audit: [...]}).
