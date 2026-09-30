# C03 — Concept, term and definition extraction · v1.2 (delta)

**Base:** `contracts-v1.1/C03-concept-term-definition-extraction.md`, sha256 `e1622dc7b8af0a1cfea396554f1648959a34a1cd4ab7671415fe5e532b71dc19`. Unchanged: the DEFINITION schema and its special treatment, TERM and CONCEPT, and source-vs-interpretation separation.

## Changes
1. The definition cue list of C02 v1.2 §2 decides which units **must** carry a DEFINITION or TERM item, or a `NOT-A-DEFINITION` release. Definitions that the detector misses are still owed under §1, and the independent auditor enumerates definitions itself: `DEFINITION-TERM-MISSING` in the computed comparison.
2. `source_definition` must lie inside the item's covered units, not merely somewhere in the file.
3. A negative definition ("X is not A. It is B") is `definition_form: NEGATIVE` and is recorded with both parts.
