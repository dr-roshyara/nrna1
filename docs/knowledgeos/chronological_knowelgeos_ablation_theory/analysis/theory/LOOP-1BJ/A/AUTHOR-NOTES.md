# AUTHOR-NOTES

1. CORE-SPEC.md does not define the `status` values (SOURCE_FACT / INFERENCE / UNKNOWN), so I wrote my own definitions. The line between SOURCE_FACT and INFERENCE, especially for context taken from the section, is my own choice.
2. ACT_SELF_PERFORMED against ACT_REPORTED in minutes: once minutes are approved they may themselves count as an act, but a sentence in them usually *describes* the act. I coded "voted to" as a reported act and "hereby" as a performative. Present-tense performatives without "hereby" will be hard to code.
3. Performative and norm together: an allocation or classification often creates a status. I counted that as NORM_STATED = YES (ex. 3), which may be broader than the spec intends.
4. A refusal *implies* a norm (ex. 6). Keeping NORM_STATED = NO there relies on "do not infer", but that rule only covers act-from-norm, not norm-from-act.
5. Headings: whether a bare label is NO or UNKNOWN is debatable. I chose NO because its words do decide the question: they contain no act and no norm.
6. Plans with a decision inside them ("it was agreed to repaint next year") fall between a plan and a reported act. I settled them with a one-clause exception.
7. "Attempted" in ACT_REPORTED has no clear lower bound, e.g. whether proposing or requesting counts as attempting.
