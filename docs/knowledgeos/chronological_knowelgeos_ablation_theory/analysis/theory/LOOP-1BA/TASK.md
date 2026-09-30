# Task (frozen): did the recorded work conform to the norm in force?

Use ONLY `SOURCES.md`: register rulings, one git commit (the execution record), and a recording timeline. The timeline gives the time each ruling was **written into the register**, which is **not necessarily** the time it was decided. No other path, no web, no commands.

## Candidate 1: the WP-4B RED commit
The commit (2026-08-03 16:10) records implementation work on WP-4B ("WP-4B RED").
1. List every ruling that states a norm about **starting implementation on WP-4B**: permitted, prohibited, conditional, or its proviso. Quote each, with its date.
2. Which of these norms was **in force at 16:10 on 2026-08-03**? Establish the order from the **source text** where possible (explicit references between rulings, e.g. "this ruling lifts…", "after X"). Use the recording timeline **only as secondary evidence**, and say when you rely on it.
3. Verdict:
   - **CONFORMS:** a norm permitting the start was in force;
   - **NON-CONFORMS:** the start was prohibited, or its condition unmet, at 16:10;
   - **UNDETERMINED:** say exactly what is missing.

   Quote the decisive text. Distinguish RED, GREEN and other phases if the norms do.

## Candidate 2: R-89 vs R-72's gate
R-72 is reported to gate WP-4C on WP-4B's acceptance.
1. Quote the gate.
2. Was WP-4B accepted (R-87) before R-89? Use the text first, then the timeline.
3. Is R-89 in force at all? Quote its status.
4. Verdict: **CONFORMS · NON-CONFORMS · UNDETERMINED**, with the reason.

## Part 3
For each candidate, does its verdict bear on the claim "work conforms to the norms in force"? Say whether the result would count **against** that claim, **for** it, or **neither**. State the strength (one case, one episode).

Write **ANSWER.json** ({c1: {norms: [...], in_force_at_commit, order_basis: TEXT|TIMELINE|MIXED, verdict, decisive_quotes, missing}, c2: {gate_quote, accepted_before, r89_status, verdict, reason}, bearing: {c1, c2}}) and **ANSWER.md** (labels SOURCE FACT / INFERENCE / UNKNOWN; list the ambiguities).

There is no expected answer.
