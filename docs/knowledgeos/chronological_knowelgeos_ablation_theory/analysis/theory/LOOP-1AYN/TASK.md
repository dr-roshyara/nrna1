# Normalization task: map free-text values to fixed vocabularies

Use ONLY `VALUES.json` (free-text values coded for source records: each has a record_id, a field and a value) and `RECORDS.json` (the verbatim spans those records came from, for context). No other path, no web, no commands.

Map **every** value to exactly one entry of the vocabulary for its field. **Do not** use any information beyond the value and its record's span.

**Operation fields** (`act.operation`, `norm.about_operation`):
ADOPT · HOLD · WITHDRAW · AUTHORIZE-IMPL · AUTHORIZE-PLAN · PREPARE-ISSUE · REGISTER · CLASSIFY · OPEN-WORK · START-EXECUTION · PLAN · RAISE (promote into a higher-standing home) · ASSIGN-ID · REUSE-ID · SUPERSEDE · ANNOTATE · DECLINE-INTERPRETATION · OTHER:<one word> · UNMAPPABLE

**Actor fields** (`act.actor`, `norm.about_actor`, `norm.stated_by`):
DECISION-AUTHORITY · ARB · ARB-CHIEF · PA · PO · PO/ARB · ENGINEERING · SESSION-AUTHOR · OTHER:<one word> · UNMAPPABLE

**Target fields** (`act.target`, `norm.about_target`): a **canonical identifier** taken literally from the value (e.g. "R-89", "WP-7B-R1", "Slice 7C", "ES-004.3", "AST-013", "matrix item #5"). If the value names several, use the most specific one it is *about*. If none is identifiable, UNMAPPABLE.

**Rules:**
- Map by **meaning**, not by string.
- **Do not merge** distinct meanings; e.g. PLAN ≠ START-EXECUTION, and ARB ≠ ARB-CHIEF unless the value says so.
- Use UNMAPPABLE rather than guess.
- For each mapping, give a one-line justification.

Write **MAPPING.json** ({"items": [{"record_id", "field", "value", "mapped", "why"}]}) and **NOTES.md** (the hard cases).

There are no expected answers.
