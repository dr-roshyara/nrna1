# Task (frozen): is each guard analytic or synthetic?

Use ONLY the files in this directory:
- `SCHEMA.md`: the coding schema and variable definitions;
- `EVENTS.json`: coded legality events per operation, and the guard variables to classify;
- `CODING-LINES.txt`: the coding source lines, each with the coder's quoted basis.

No other path, no web, no commands.

A **guard** says that an operation's legal outcome (PERFORMED vs REFUSED) is determined by the listed variable(s). For **each (operation, guard variable)** in `guards_to_classify`, decide which of the following it is:
- **ANALYTIC:** the link "value ⇒ outcome" holds **by the definition of the coded value or of the operation**. The value's meaning already includes the permission or prohibition (e.g. a state named "authorized for this act"), or the operation is defined as available only to that value. Testing it by prediction cannot fail, except through miscoding. Its empirical content is only whether the value was **independently established** before the act.
- **SYNTHETIC:** the value and the outcome are **logically independent**. A contingent rule, threshold or practice links them, and that rule could have been otherwise (e.g. "promotion requires ≥ 2 contexts"). It is testable by prediction.
- **UNKNOWN:** the definitions and coding lines do not settle it.

For each item give:
1. the class;
2. the decisive definitional or coding text, quoted;
3. for ANALYTIC: which **premise** must be independently established, and whether the coding lines show it established independently of the outcome (INDEPENDENT / OUTCOME-DERIVED / UNCLEAR, per event);
4. for SYNTHETIC: which **rule** links value and outcome, and whether the events contain a *contrast* that could have refuted it;
5. the strongest alternative classification, and why it is rejected or not.

Write **ANSWER.json** ({items: [{operation, variable, class, basis_quote, premise_or_rule, premise_status_per_event, refutable_contrast, alternative}], summary: {ANALYTIC: [...], SYNTHETIC: [...], UNKNOWN: [...]}}) and **ANSWER.md** (labels SOURCE FACT / INFERENCE / FORMAL CONSEQUENCE / UNKNOWN; list the ambiguities).

There is no expected answer.
