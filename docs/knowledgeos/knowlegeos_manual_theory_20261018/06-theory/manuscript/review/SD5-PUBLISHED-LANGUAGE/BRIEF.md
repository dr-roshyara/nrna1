# Strategic-design turn 5 (published language): review commission (reviewers A and B)

*2026-10-29. Rulebook v1.4 §12b; the strategic-design mandate, revision 1 (§1 and §2 element 4); the Principal's decision on T10-10, (1), and instruction for turn 5. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (theory turn review). W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`.*

## The object
`06-theory/manuscript/chapter_10/10.05-published-language.md` (draft). Read with §10.1–§10.4 (settled; T10-10 (1) applied to §10.2–§10.4) and Ch. 9 (§9.10, §9.13, §9.15, §9.16, §9.17), from which every item is drawn.

## Files you may open (only these)
- this brief; `03-methodology/REVIEWER-INSTRUCTIONS.md`; `03-methodology/MANDATE-STRATEGIC-DESIGN-PROPOSED.md`; `03-methodology/IMPLEMENTATION-STRATEGY-PROPOSED.md` §3;
- the working book: `06-theory/manuscript/chapter_*/` (any section), `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/RESEARCH-PURPOSE.md`, `06-theory/EXTERNAL-SOURCES.md`, `06-theory/manuscript/BOOK-OUTLINE.md`, `06-theory/manuscript/TARGET-TOC.md`;
- W's tools (`tmp/tools/`, read-only; you may run them, including `ce_model.py`, on a scratch copy if you probe it) and `02-FILES.jsonl`;
- corpus sources **only from** `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`; check each path there first, and list every one you open.
- **Never:** any other file under `03-methodology/` (the decision record included) or `00-access/`; any review folder except this one (restrict every search to `chapter_*` directories and the files above; do not search `06-theory/manuscript/` recursively); S0155, S0156, S0157, S0161, S0170, S0174; `docs/knowledge_tranfer/*`; sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `.env*`; any EKS or application code. If a file is refused or not allowed, record it and continue; do not inspect it another way.

## Questions
1. **The checks of REVIEWER-INSTRUCTIONS** for a theory turn: claims against cited book and source lines; labels; homes; levels; R1; R2 (`undefined_terms.py --scope 10.05`, judge every in-scope candidate); the quote check; R3 for any correction; a reference to a missing claim checked as possible lost content.
2. **Completeness (mandate §2 row 4):** are the kernel's published concepts and contracts all listed: the record and its entry kinds, the evaluator's questions and answers T/F/U with refusal reasons, the owner rules, designation? Is anything the book promises missing, or anything listed that the book does not promise?
3. **Fidelity:** does each item's meaning match its Ch. 9 source exactly, with no concept redefined for convenience? Is each item's promise and condition stated as the book states it? Is the naming point (current authority vs standing) handled correctly?
4. **Meaning, not interface (§1):** does any item fix or imply a technical interface, format or mechanism?
5. **Conditions and conditionals:** are the unrecorded inputs (covers, θ, the identity link), the assumptions, the conditional relationships of §10.4 (G1, G11, G10) and the open translation boundaries (T10-14) shown as such? Is anything shown as settled that is conditional?
6. **The other areas (§10.5.3):** right under T10-10 (1) and §10.4?
7. **Traceability and levels (§10.5.4):** each item traced to its claim and register status; every level S0; is "inherits PROVISIONAL" right?
8. **Prior work:** S0028, S0035, S0036, S0039, S0062, S0069, S0196, S1040, S0633: fair? Is S0039's test (a second consumer) applied correctly in §10.5.6?

## Rules (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.
- Edit nothing in the repository. No corpus or book text to any outside service. Do not reproduce any private person's name.

## Report
- Tables: findings (ID · finding · severity: critical / high / normal · evidence · correction · R3 lines · resolved when); a verdict per item (PL-1 to PL-9, §10.5.3, §10.5.6).
- A summary of at most 10 lines, and one line on process independence.
- End with every file you opened and every command and search you ran.
- Deliver it once, beginning "SD5 REVIEW REPORT <A or B>".
