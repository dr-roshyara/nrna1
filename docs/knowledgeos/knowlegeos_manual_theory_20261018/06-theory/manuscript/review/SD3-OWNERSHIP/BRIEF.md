# Strategic-design turn 3 (ownership of invariants): review commission (reviewers A and B)

*2026-10-29. Rulebook v1.4 §12b; the strategic-design mandate, revision 1 (§2 element 5, §2c); the Principal's instruction for turn 3 (G1 does not pause the chain; the admission test justified from the book and corpus, with its falsifier; DDD literature a benchmark only). Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (theory turn review). W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`.*

## The object
`06-theory/manuscript/chapter_10/10.03-ownership-of-invariants.md` (draft, before review), read with §10.1 and §10.2 (settled).

## Files you may open (only these)
- this brief; `03-methodology/REVIEWER-INSTRUCTIONS.md`; `03-methodology/MANDATE-STRATEGIC-DESIGN-PROPOSED.md`; `03-methodology/IMPLEMENTATION-STRATEGY-PROPOSED.md` §3;
- the working book: `06-theory/manuscript/chapter_*/` (any section), `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/RESEARCH-PURPOSE.md`, `06-theory/EXTERNAL-SOURCES.md`, `06-theory/manuscript/BOOK-OUTLINE.md`, `06-theory/manuscript/TARGET-TOC.md`;
- W's tools (`tmp/tools/`, read-only; you may run them, including `ce_model.py`) and `02-FILES.jsonl`;
- corpus sources **only from** `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`; check each path there first, and list every one you open.
- **Never:** any other file under `03-methodology/` (the decision record included) or `00-access/`; any review folder except this one; S0155, S0156, S0157, S0161, S0170, S0174; `docs/knowledge_tranfer/*`; sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `.env*`; any EKS or application code. If a file is refused or not allowed, record it and continue; do not inspect it another way. Read files whole or by line range; do not print more than you need of any file.

## Questions
1. **The checks of REVIEWER-INSTRUCTIONS** for a theory turn: claims against cited book and source lines; labels; homes; levels (each ceiling the lowest level it rests on); R1; R2 (`undefined_terms.py --scope 10.03`, judge every in-scope candidate); the quote check; R3 for any correction; a reference to a missing claim checked as possible lost content.
2. **The inventory (§10.3.0):** is it complete for "every invariant in the book"? Name any invariant the book states that has no row.
3. **Owner and enforcer (§10.3.2):** is the definition of owner sound, operational, and different from "the place that enforces"? Does it handle S0091's two-owner case?
4. **§2c:** are owns / preserves / observes / assumes supported by the book and useful, as the section claims? Is any relation forced? Is any L1–L11 or K1–K7 item described as owned?
5. **The table (§10.3.3):** for each row, is the owner right, the enforcer and operation right, the level ceiling right? Are the gaps (no owner; no record support, G1) marked wherever they hold, and nowhere they do not?
6. **The admission test (§10.3.4):** is the refinement argument a valid falsifier of sufficiency? Is the corpus evidence (S0033) fair? Is the conclusion "not sufficient" established? Is the question for the Principal well posed (three options, one recommended), and is the recommendation (i) sound? Does any line rest on DDD literature as a reason?
7. **Prior work:** S0091, S0198/S0199, S0196, S0036: fair?

## Rules (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.
- Edit nothing in the repository. No corpus or book text to any outside service. Do not reproduce any private person's name.

## Report
- Tables: findings (ID · finding · severity: critical / high / normal · evidence · correction · R3 lines · resolved when); a verdict per table block (A–G); a verdict on §10.3.2 and on §10.3.4.
- A summary of at most 10 lines, and one line on process independence.
- End with every file you opened and every command and search you ran.
- Deliver it once, beginning "SD3 REVIEW REPORT <A or B>".
