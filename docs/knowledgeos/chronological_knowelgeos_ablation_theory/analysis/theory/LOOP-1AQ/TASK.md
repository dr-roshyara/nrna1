# Task (frozen): is each START's pre-act authorization state independently established?

Use ONLY `SOURCES.md` (register rows plus one git commit) and `START-EVENTS.json` (11 coded START events: id, cluster, coded state `s`, target `t`, outcome). No other path, no web, no commands.

**Background:** START (beginning implementation of a slice or work package) is lawful iff its target is authorized. The coded `s` is the target's authorization state just before the act. That rule is true by definition, so its only empirical content is whether `s` was **established independently**: by a source statement about the authorization that is separate from, and prior to or independent of, the statement of the START act or its outcome.

For **each** of the 11 events:
1. Locate the source text that describes the START act or its outcome (the refusal or the performance). Quote it.
2. Locate the source text that establishes the target's authorization state before the act. Quote it, with its row.
3. Classify:
   - **INDEPENDENT:** a separate statement establishes the state; it would stand even if the START sentence were deleted;
   - **SAME-STATEMENT:** the state and the outcome are asserted in one sentence or clause (e.g. "X is not authorized, so it does not begin");
   - **OUTCOME-DERIVED:** the state can be known only from the outcome;
   - **ACT-NOT-ATTESTED:** no attempted or performed START is actually described; only a permission statement;
   - **UNCLEAR.**
4. Does the coded `s` match what the source states? If it doesn't, give the source's value.

Then report the counts per class, and state whether the frozen claim "START legal ⇔ target authorized" has at least **2 INDEPENDENT events from different clusters with opposite outcomes**. That is the minimum for its premise to be non-circularly exercised.

Write **ANSWER.json** ({events: [{event_id, act_quote, state_quote, state_row, class, coded_s_matches, source_s}], counts, non_circular_contrast: {exists, pairs}}) and **ANSWER.md** (labels SOURCE FACT / INFERENCE / FORMAL CONSEQUENCE / UNKNOWN; list the ambiguities).

There is no expected answer.
