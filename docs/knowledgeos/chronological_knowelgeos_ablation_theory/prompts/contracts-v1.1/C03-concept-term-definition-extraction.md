# C03 — Concept, term and definition extraction · v1.1

**Treatment:** F-SPECIFIC. Inherits v3.5 R5 unchanged (labels are handles; no identity resolution in extraction).
**Code:** `f_checks.check_inventory` (DEFINITION / TERM fields). **Gate:** part of `CONTENT-EXTRACTED`.

## 1. Definitions need special treatment
Every definition in the file — formal, informal, rhetorical, implicit ("X is not A; it is B") — becomes one DEFINITION
item:
```json
{"item_id":"FCI-F####-0007","category":"DEFINITION","covers_units":["U0031"],"page":1,
 "term":"kernel","source_definition":"<the complete source definition, verbatim>",
 "context":"where and how it is introduced (section, what it answers, what it replaces)",
 "related_terms":["invariant","fixed point"],"examples":["<verbatim or item ids>"],
 "qualifications":["<limitations or conditions the source attaches>"],
 "definition_form":"FORMAL|INFORMAL|NEGATIVE|BY-EXAMPLE|BY-ANALOGY|IMPLICIT",
 "verbatim_quote":"…","content":"…"}
```
`source_definition` must be verbatim in the file. If the definition spans units, cover them all. **Two differing
definitions of one term in the same file are two items**, never merged. A later redefinition keeps its own item and
records `redefines_item` when the source says it redefines.

## 2. Terms
Every defined, introduced or specially used term gets a TERM item: `term`, `notation[]` (as written), `first_unit`,
`gloss` (the source's own words or null). A term used but never defined is still a TERM item, with `gloss: null`, so
that its undefinedness is on record.

## 3. Concepts
A CONCEPT item holds an idea the source develops without a definitional form. Its `content` stays in source terms.

## 4. Separation of source and interpretation
The DEFINITION item says what the source says. "The source uses *kernel* in a structural sense" is a Level-2 analysis
record (C04), never part of the item.

## 5. Term keys for cross-file discovery
Normalized `term` values and contribution labels form the file's term keys (`f_checks.term_keys`). They are
mechanical handles for candidate generation (C05), never identity claims.
