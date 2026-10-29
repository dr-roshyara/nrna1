# Strategic-design turn 4 (context map) and the K2 condition: review commission (reviewers A and B)

*2026-10-29. Rulebook v1.4 §12b; the strategic-design mandate, revision 1 (§2 element 3, §2a, §2b); the Principal's decision on T10-05, (B) with the K2 condition, and instruction for turn 4. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (theory turn review). W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`.*

## The objects
1. `06-theory/manuscript/chapter_10/10.03-ownership-of-invariants.md` **§10.3.6 only** (the decision (B), the K2 condition, its application); the rest of §10.3 is settled.
2. `06-theory/manuscript/chapter_10/10.04-context-map.md` (draft).
Read with §10.1–§10.3 (settled) and the status note added to §10.2.

## Files you may open (only these)
- this brief; `03-methodology/REVIEWER-INSTRUCTIONS.md`; `03-methodology/MANDATE-STRATEGIC-DESIGN-PROPOSED.md`; `03-methodology/IMPLEMENTATION-STRATEGY-PROPOSED.md` §3;
- the working book: `06-theory/manuscript/chapter_*/` (any section), `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/RESEARCH-PURPOSE.md`, `06-theory/EXTERNAL-SOURCES.md`, `06-theory/manuscript/BOOK-OUTLINE.md`, `06-theory/manuscript/TARGET-TOC.md`;
- W's tools (`tmp/tools/`, read-only; you may run them, including `ce_model.py`, on a scratch copy if you probe it) and `02-FILES.jsonl`;
- corpus sources **only from** `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`; check each path there first, and list every one you open.
- **Never:** any other file under `03-methodology/` (the decision record included) or `00-access/`; any review folder except this one (restrict every search to `chapter_*` directories and the files above; do not search `06-theory/manuscript/` recursively); S0155, S0156, S0157, S0161, S0170, S0174; `docs/knowledge_tranfer/*`; sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `.env*`; any EKS or application code. If a file is refused or not allowed, record it and continue; do not inspect it another way.

## Questions
1. **The checks of REVIEWER-INSTRUCTIONS** for a theory turn: claims against cited book and source lines; labels; homes; levels; R1; R2 (`undefined_terms.py --scope 10.03,10.04`, judge every in-scope candidate); the quote check; R3 for any correction; a reference to a missing claim checked as possible lost content.
2. **§10.3.6, the K2 condition:** is the definition (d1)–(d3) exact? Is its compatibility with K2-d, K2-w and K2-r shown, from K2's text (§9.15 L75; §9.17 R2)? Are the premises (p1)–(p3) complete? Is the rule truly of the same form as the existing owner rules (§9.16 R4a; §9.17 (c′))? Does anything in it adjust K2?
3. **§10.3.6, the application:** is each candidate's result right under (d1)–(d3)? Is the discrepancy with the decision's consequence line (norms) stated correctly, and is the question T10-10 well posed, with a sound recommendation?
4. **§10.4, each relationship:** direction, authority, integration in domain terms, shared language, change effect, evidence. Is any relationship missing, or drawn without stated meaning? Is any pattern name used as a reason?
5. **§2a:** is every relationship that depends on G1, T10-10/G11 or the catalogue's status marked, with the five items, and is the count in the header right?
6. **§2b, the direction law:** is its meaning exact? Is the falsifier observable? Is the evidence for, against and undecided complete and fair (look for further kernel decisions that need application information)? Does the result (a narrowed law, law 18 a candidate falsifier) follow?
7. **Prior work:** S0033, S0162, S0149, S0196, §6.6 C8: fair? Is S0162's warning answered?

## Rules (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.
- Edit nothing in the repository. No corpus or book text to any outside service. Do not reproduce any private person's name.

## Report
- Tables: findings (ID · finding · severity: critical / high / normal · evidence · correction · R3 lines · resolved when); a verdict on §10.3.6, per relationship (R1–R13), and on §10.4.3.
- A summary of at most 10 lines, and one line on process independence.
- End with every file you opened and every command and search you ran.
- Deliver it once, beginning "SD4 REVIEW REPORT <A or B>".
