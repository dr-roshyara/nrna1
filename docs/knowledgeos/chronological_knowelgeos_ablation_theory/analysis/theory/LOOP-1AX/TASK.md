# Coding task: ACT and NORM components of source spans

Use ONLY:
- `SCHEMA-r2.md`: the coding schema, §1–4; read it completely;
- `RECORDS.json`: 36 records, each an opaque id, a section heading and a verbatim span;
- `SOURCES.md`: the full sources the spans come from; read the surrounding section when a span alone does not decide.

No other path, no web, no commands.

For **each** record, code both components **independently**, per SCHEMA-r2 §2:
- **ACT:** `state`, `operation`, `actor`, `target`, `quote`;
- **NORM:** `deontic`, `about_operation`, `about_actor`, `about_target`, `stated_by`, `quote`.

Give **every** coded field an epistemic status (§3): SOURCE_FACT / INFERENCE (state the premise) / UNKNOWN / NOT_EVIDENCED.

Apply the critical rules §4 strictly:
- never infer an ACT from a NORM;
- ACT_REFUSED / ACT_WITHDRAWN need an identified proposal or attempt;
- a register row that itself performs a norm-making act is an ACT_OCCURRED of it;
- UNKNOWN when undecided.

Records whose span is "NO SOURCE SPAN AVAILABLE": both components UNKNOWN.

Write **CODING.json** ({"records": [{"record_id", "act": {"state", "operation", "actor", "target", "quote", "status": {field: epistemic}}, "norm": {"deontic", "about_operation", "about_actor", "about_target", "stated_by", "quote", "status": {...}}, "notes"}]}) and **NOTES.md** (one line per hard or ambiguous record).

There are no expected answers.
