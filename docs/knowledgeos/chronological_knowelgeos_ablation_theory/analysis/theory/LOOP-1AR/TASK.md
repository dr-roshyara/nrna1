# Task (frozen): which legality events are attested acts?

Use ONLY:
- `EVENTS.json`: 36 coded legality events: operation `o`, outcome, cluster, and the coded fields;
- `CODING-LINES.txt`: the coder's source lines, each with its quoted basis;
- `SOURCES.md`: the verbatim register rows and anchors.

No other path, no web, no commands.

For **each** event, decide what the source actually records:
- **ACT-PERFORMED:** the source records that the act *was performed*: a ruling that itself performs the act (a ruling adopting, authorizing, registering, superseding or annotating *is* the act), or a separate record of the act having happened (e.g. a commit).
- **ACT-REFUSED:** the source records an act that was **attempted or proposed and then refused or held** by a decision. Something was actually put forward and turned down.
- **PERMISSION-STATEMENT:** the source only states what is or is not permitted, authorized or allowed (e.g. "X is NOT authorized", "does not begin", "NOT PERMITTED"). No act of that kind is recorded as performed or attempted.
- **GENERIC-PRACTICE:** the source states a general practice or rule, not a particular act (e.g. "numbers are never reused").
- **UNCLEAR:** the sources do not decide. Use this also where no source text is provided.

For each event give the class, the decisive quote with its row or anchor, and one sentence of reasoning. Be careful: a ruling that **refuses a proposal** (e.g. HELD, declined) is ACT-REFUSED when the proposal is identified. A ruling that merely **states a prohibition** is a PERMISSION-STATEMENT.

Then recount. For each variable (a, k, s, t, e, c, h), the "strict witnesses" are the pairs of events of **one operation** that have opposite outcomes, differ only in that variable, and have the other applicable fields equal (see `EVENTS.json`). You do **not** need to recompute every witness. Instead, list the **event pairs named as strict witnesses in `WITNESSES.md`** and mark each pair **SURVIVES** (both events are ACT-PERFORMED or ACT-REFUSED) or **FAILS** (at least one is PERMISSION-STATEMENT, GENERIC-PRACTICE or UNCLEAR).

Write **ANSWER.json** ({events: [{event_id, o, outcome, class, quote, anchor, reason}], counts_by_class, counts_by_operation_and_class, witnesses: [{variable, pair, survives, why}], surviving_support: {var: n_surviving_pairs}}) and **ANSWER.md** (labels SOURCE FACT / INFERENCE / FORMAL CONSEQUENCE / UNKNOWN; list the ambiguities).

There is no expected answer.
