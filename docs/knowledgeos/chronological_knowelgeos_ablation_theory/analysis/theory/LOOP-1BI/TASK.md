# Task (frozen): draft SCHEMA-r3, closing six identified gaps in SCHEMA-r2

Use ONLY:
- `SCHEMA-r2.md`: the current schema (§1–4);
- `GAPS.md`: the six gaps found by a reliability study, with the competing readings;
- `RECORDS.json`, `SOURCES.md`: the spans and sources, **for understanding the cases only**;
- `HYPOTHESES.md`: two competing hypotheses. **Your rules must favour neither.**

No commands, no web.

**Requirements:**
1. Write **SCHEMA-r3.md**: SCHEMA-r2 with **only** the changes needed to close the six gaps. Mark every change `[r3-Gn]`, n = 1–6.
2. Each new rule is an **operational decision procedure**: given a span (and its section), a coder reaches one answer, or UNKNOWN if the span does not decide.
   - Define every new term (e.g. ATTEMPTED; REQUIRED vs STATUS_CONSTRAINT).
   - Give a **priority order** where two rules could both apply (e.g. ACT_REFUSED vs ACT_OCCURRED).
   - State a **shared-span rule**.
3. **Neutrality:**
   - choose each rule on principled grounds (clarity, observability, the R-A1 "never infer an act from a norm" spirit);
   - **never** choose it to make the observations fit H-D or H-B;
   - note that the r2 convention R-A3 (a register row performing a norm-making act is an act) was **already flagged as building one hypothesis's view into the observations**. Treat its scope with particular care.
4. **THEORY_CHOICES:** if a gap cannot be closed without a substantive theoretical choice, do **not** choose. List the alternatives, and the consequence of each for what the data can show, in **THEORY-CHOICES.md**.
5. Do **not** consult or try to reproduce any earlier coding. None is provided.

Write **SCHEMA-r3.md**, **THEORY-CHOICES.md** (it may say "none"), and **RATIONALE.md** (one paragraph per gap: the rule, why, and the alternatives rejected).
