# Task (frozen): write SCHEMA-CORE, a short coder-facing coding schema

Use ONLY `CORE-SPEC.md`. You have no data records; do not invent or try to obtain any. No commands, no web.

Write **SCHEMA-CORE.md** (≤ 1,000 words). It must contain:
1. **The unit:** one record = one verbatim span with its section heading. The coder may read the surrounding section in a sources file when the span alone does not decide.
2. **The three fields:** a definition of each, and **a short decision test for each**, phrased as one or two yes/no questions.
3. **The fixed rules** from CORE-SPEC.md.
4. **Boundary guidance.** One line each for: a plan or intention (not an act); a quotation or report of someone else's act (ACT_REPORTED); a heading or label alone; a negated or refused act; a conditional ("if … then may").
5. **Worked examples:** 6–9 examples, all from **one neutral fictional domain**: a village allotment-garden association (committee minutes, rule book, plot work).
   - **Do not** use the words ruling, register, ADR, test, RED, GREEN, WP, matrix, chief, authority, governance, implementation, adopt-as-standard, or any software terms.
   - Each example shows the span and the three answers, with a one-line reason.
6. **The output format:** CODING.json = {"records": [{"record_id", "ACT_REPORTED", "ACT_SELF_PERFORMED", "NORM_STATED", "status": {field: SOURCE_FACT | INFERENCE | UNKNOWN}, "notes"}]}, plus NOTES.md with one line per hard record.

Also write **AUTHOR-NOTES.md**: what you found hard to define, in at most 10 lines.
