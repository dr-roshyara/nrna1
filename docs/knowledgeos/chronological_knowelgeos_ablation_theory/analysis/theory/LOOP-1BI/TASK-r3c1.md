# Task (frozen): repair the coder-facing schema (r3c-1)

You have ONLY:
- `SCHEMA-r3.md`: the authoritative schema;
- `SCHEMA-r3-CODER.md`: a draft coder version;
- `EQUIVALENCE.md`;
- `REVIEW.md` / `.json`: the review of the draft, verdict FAIL.

**You have no source records. Do not try to obtain or imagine any.** No commands, no web.

1. **B1 equivalence:** restore every qualifier that the review lists as dropped:
   - D4 "on the target";
   - the rank-1 cell "for X";
   - R-A2 "a refusal expression that disposes of that proposal";
   - row 46 "with no refusal expression";
   - row 127 "the intended failure is not a non-effect marker; rank 3 if it occurred";
   - the minor compressions ("guard", "no RED begins", the D4 question wording).

   **Where SCHEMA-r3.md and the draft differ, SCHEMA-r3.md wins.**
2. **B2 anchoring:** replace **all** worked examples with examples from **one neutral, fictional domain** that has nothing to do with software governance, for example a village allotment-garden association: its committee minutes, its rule book and its members' plot work.
   - Use no wording such as ruling, register, ADR, RED, WP-, matrix, Chief, ARB or Decision Authority.
   - Each example illustrates exactly one decision path.
   - Remove "(by lineage)". Instead, state the shared-span record-assignment rule exactly as SCHEMA-r3.md states it.
3. **Self-containment:** where the coder needs something SCHEMA-r3.md references (e.g. the operation list), copy it **verbatim** from SCHEMA-r3.md. If SCHEMA-r3.md lacks it, write `NOT IN SCHEMA` there; do not invent it.
4. **Machine-check friendliness:** keep every rule sentence on its own line, with the rule id at the start (e.g. `R-A2:`), so equivalence can be checked line by line.
5. Rebuild **EQUIVALENCE.md** row by row against SCHEMA-r3.md.

Write **SCHEMA-r3-CODER.md** and **EQUIVALENCE.md**, plus **CHANGELOG-r3c1.md**.
