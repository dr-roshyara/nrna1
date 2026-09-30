# C02 — Complete content extraction · v1.2 (delta)

**Base:** `contracts-v1.1/C02-complete-content-extraction.md`, sha256 `073514ee4b90d0f080160512dbcbfa0b2c06992a72be6fd00e35494752bfca57`. The principles in §1, the 26 categories in §3 and the category-check requirement in §6 are unchanged. **Coverage is not semantic completeness**: the v1.2 rules make empty compliance fail, and they make semantic loss detectable.

## Changes
1. **Units (§2), detection widened (F-04).**
   - Fenced code with ``` or `~~~` is one CODE unit, and so is indented code (≥ 4 spaces or a tab, after a blank line, not a list continuation).
   - These are one MATH unit each: `$$` display blocks, `\[ … \]`, and `\begin{env} … \end{env}` for the closed list of environments in `f_checks.MATH_ENVS`.
   - A line containing a complete `$$…$$` is a single line: it no longer opens a block.
   - A unit is **`math_bearing`** when it is MATH, or when it carries a marker: inline `$…$` (currency excluded), `\(…\)`, `\[`/`\]`, a math environment, a known LaTeX math command (`f_checks.LATEX_CMDS`), or a Unicode mathematical operator / letterlike / alphanumeric character. Arrows alone are not markers.
2. **Definition cues widened (F-05):** `f_checks.DEFINITION_CUES` — `is defined as`, `is called`, `we call/define/say that`, `denotes`, `refers to`, `stands for`, `by … we mean`, `:=`/`≔`/`\coloneqq`/`\triangleq`, `Let/Suppose X be`, Definition/Theorem/… headings and bold labels, `**X** is/are/means/:`, negative definitions (`is not … . It is …`), `Term is **…**`, `X — the …`.
3. **Item rules (§4, F-03):**
   - An item covers ≤ 6 **consecutive** units.
   - `verbatim_quote` is contiguous inside the span of its units, and is ≥ 20 normalized characters or a whole unit.
   - `content` is ≥ 10 characters and ≠ the quote.
   - `page` lies in the units' pages.
   - The identifier guard (C15 §5) applies to `content`, `context`, `gloss` and `qualifications`.
4. **Unit rules (F-03, F-04, F-05):**
   - Every non-markup unit is covered by ≥ 1 item quoting **inside that unit** (or containing the whole unit), or it carries a disposition.
   - A **definition-cue** unit must be covered by a DEFINITION or TERM item quoting inside it, or it carries a `NOT-A-DEFINITION` release.
   - A **math-bearing** unit must be covered by a FORMULA, NOTATION or THEOREM item whose `latex`/`statement` lies in it, or it carries a `NOT-MATHEMATICAL` release.
   - A **CODE** unit must be covered by an item in ALGORITHM, PROCEDURE, ARCHITECTURE-COMPONENT, MECHANISM, NOTATION, FORMULA or EXAMPLE.
5. **Dispositions and releases (§5).**
   - `RESTATES-UNIT` / `NO-SUBSTANTIVE-CONTENT` apply to prose only. They are never allowed for MATH, CODE, math-bearing or definition-cue units.
   - A release (`NOT-A-DEFINITION`, `NOT-MATHEMATICAL`) records a detector false positive with a reason (≥ 10 characters). It releases only the category binding: the unit must still be covered by an item. The independent audit reviews every release and every NO-SUBSTANTIVE disposition.
6. **Category fields.**
   - THEOREM carries `source_status` ∈ ASSERTED | PROOF-SKETCH | PROVED | CITED | UNDEFINED-UNCLEAR (RN-06, the source's status only). Its `statement` is verbatim inside its units.
   - FORMULA/NOTATION `latex` and DEFINITION `source_definition` are verbatim **inside the covered units**.
   - CONTRADICTION-OR-TENSION carries `source_framing_quote` (C07).
7. **CATEGORY-CHECK (§6, F-19).** Each `checked` text is ≥ 20 characters, and the 26 texts are not all identical.
8. **Gate CONTENT-EXTRACTED (§7).** The gate also requires the isolation attestation (C15 §4). It freezes UNITS, CONTENT-INVENTORY, UNIT-DISPOSITIONS, CATEGORY-CHECK and ISOLATION-ATTESTATION (F-01). After the gate, `f_units.py` refuses to rewrite UNITS.

## Tests
`Extraction.*`, `Detectors.*`, `Freeze.*`.
