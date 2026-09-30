# C02 — Complete content extraction · v1.1

**Treatment:** F-SPECIFIC (human instruction 2026-09-25, "please strongly follow this suggestion"); it strengthens the
inherited v3.5 R0 ("Phase 1 maximizes information preservation, not reduction"), which it does not change.
**Code:** `scripts/f_units.py`, `f_checks.units_of`, `f_checks.check_inventory`. **Gate:** `CONTENT-EXTRACTED`.

## 1. Principles
- **READ-COMPLETE ≠ CONTENT-COMPLETE. RESEARCH-COMPLETE ≠ CONTENT-COMPLETE.**
- **Perform exhaustive substantive extraction before summarization or interpretation.** "Summarize the file and
  extract the important concepts" is forbidden.
- **Do not decide beforehand which content is "important".** No substantive source content may be discarded merely
  because it is not currently recognized as relevant to an existing KnowledgeOS theory.
- The original file remains the authoritative source. The inventory holds structured records with verbatim quotes,
  spans and provenance — not a copy of the corpus.

## 2. Units (the coverage denominator)
`f_units.py --run RUN F####` (allowed only after READ-COMPLETE for RUN) writes `UNITS.jsonl`. Segmentation is
deterministic: each non-blank line is a unit, except a fenced code block and a `$$`-display math block, which are one
unit each. Kinds: HEADING · LINE · LIST-ITEM · TABLE-ROW · QUOTE · MATH · CODE · MARKUP. MARKUP (`---`, table
separators) needs no coverage. A unit carries `definition_cue` when the lexical detector fires.

## 3. The 26 categories (closed list; the human's list of 2026-09-25)
TERM · DEFINITION · CONCEPT · DISTINCTION · RULE · PRINCIPLE · THEOREM (theorem/proposition/lemma/corollary) · FORMULA ·
NOTATION · ALGORITHM · PROCEDURE · ARCHITECTURE-COMPONENT · RELATIONSHIP · ASSUMPTION · CONSTRAINT · EXAMPLE ·
COUNTEREXAMPLE · HYPOTHESIS · RESEARCH-QUESTION · CONCLUSION · UNRESOLVED-QUESTION · CONTRADICTION-OR-TENSION ·
CLASSIFICATION · MECHANISM · DEPENDENCY · REFERENCE (to another file, document, theory, model, author or work).

## 4. Inventory item — `CONTENT-INVENTORY.jsonl`
```json
{"item_id":"FCI-F####-0001","category":"…","covers_units":["U0012","U0013"],"page":1,
 "verbatim_quote":"exact text from inside the covered units","content":"what the item is, in source terms",
 "section_path":["Part II …","Contribution 1 …"],
 "…category fields (C03)": "…"}
```
Category fields: DEFINITION and TERM per C03. THEOREM: `statement` (verbatim), `proof_status` ∈ STATED-WITH-PROOF |
PROOF-SKETCH | NO-PROOF, `proof_text` (verbatim or null). FORMULA / NOTATION: `latex` (verbatim), `symbols[]`
(each symbol as written, and its source gloss if the file gives one). ALGORITHM / PROCEDURE / ARCHITECTURE-COMPONENT:
`steps[]` or `interface` as written. RELATIONSHIP / DEPENDENCY: `from`, `to`, `relation` (as the source names them).
REFERENCE: `target` (as named) and `ref_kind` ∈ FILE | DOCUMENT | THEORY | MODEL | AUTHOR | WORK | OTHER.
CONTRADICTION-OR-TENSION: only when the **source** frames it; a tension the extractor notices is Level 2 (C07).

## 5. Dispositions — `UNIT-DISPOSITIONS.jsonl`
`{"unit_ids":[…],"disposition":"RESTATES-UNIT"|"NO-SUBSTANTIVE-CONTENT","restates_unit":"U….","reason":"…"}`.
RESTATES-UNIT must point to a unit covered by an item. MATH and CODE units, and definition-cue units, can **never**
be dispositioned: they must be covered by items. Every NO-SUBSTANTIVE-CONTENT is reviewed by the audit.

## 6. Category checklist — `CATEGORY-CHECK.json`
For all 26 categories: `{"count": <items in that category>, "checked": "<how the whole file was checked for it>"}`.
A zero count is valid only with a statement of how the whole file was checked.

## 7. Gate `CONTENT-EXTRACTED`
UNITS equal the deterministic segmentation · every non-markup unit covered or dispositioned · math/code and
definition-cue units covered · every quote verbatim inside its covered units · category fields complete · checklist
counts match. `f_units.py --status` prints the same verdict before the transition is attempted.

## 8. Forbidden
Summarizing first · selecting · paraphrasing a definition instead of quoting it · merging two source definitions ·
correcting the source · importing S-Series labels or interpretations.
