# Strategic-design turn 2 (bounded contexts): review commission (reviewers A and B)

*2026-10-29. Rulebook v1.4 §12b; the strategic-design mandate, revision 1 (§2 element 2, §2a); the Principal's decision on T10-01, promise (b) (decision record addendum 2026-10-29T08:50:11Z, `e73b8e1cb`), and instruction for turn 2. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (theory turn review). W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`.*

## The object
`06-theory/manuscript/chapter_10/10.02-bounded-contexts.md` (draft, before review), read with §10.1 (`10.01-subdomains.md`, settled under (b)).

## Files you may open (only these)
- this brief; `03-methodology/REVIEWER-INSTRUCTIONS.md`; `03-methodology/MANDATE-STRATEGIC-DESIGN-PROPOSED.md`; `03-methodology/IMPLEMENTATION-STRATEGY-PROPOSED.md` §3 (the stability levels); `03-methodology/PRINCIPAL-DECISION-RECORD-RULES-20261018.md`, **only** the addendum of 2026-10-29T08:50:11Z (T10-01);
- the working book: `06-theory/manuscript/chapter_*/` (any section), `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/RESEARCH-PURPOSE.md`, `06-theory/EXTERNAL-SOURCES.md`, `06-theory/manuscript/BOOK-OUTLINE.md`, `06-theory/manuscript/TARGET-TOC.md`;
- W's tools (`tmp/tools/`, read-only; you may run them, including `ce_model.py`) and `02-FILES.jsonl`;
- corpus sources **only from** `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`; check each path there first, and list every one you open.
- **Never:** other files under `03-methodology/` or `00-access/`; any review folder except this one; S0155, S0156, S0157, S0161, S0170, S0174; `docs/knowledge_tranfer/*`; sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `.env*`; any EKS or application code. If a file is refused or not allowed, record it and continue; do not inspect it another way.

## Questions
1. **The checks of REVIEWER-INSTRUCTIONS** for a theory turn: each claim against its cited book and source lines; labels; homes; levels (each ceiling the lowest level it rests on); R1; R2 (`undefined_terms.py --scope 10.02`, judge every in-scope candidate); the quote check; R3 for any correction; a reference to a missing claim checked as possible lost content.
2. **Each context:** is the responsibility statement right; are the concepts and rules the right ones and correctly attributed; are "receives" and "publishes" right in domain terms; is any context drawn from a folder, module or present structure; is any context missing or one too many?
3. **The admission fork:** is the scoring method (both readings, "robust" against "conditional") fair, and does it avoid selecting a reading silently? Is each context's score right? Is the fork marked wherever it matters (§2a's five items)?
4. **The kernel/application line:** is it taken from the boundary test's results (§9.6–§9.17) and not chosen anew? Are "not tested" marks right?
5. **The Principal's instruction 2:** is BC-K2 rightly responsible for the display's content, and BC-K3 for showing it? Does §10.2.3 show that each class (refuted / not attested / supported by recorded evidence) can be read from the record alone, or name the gap? Is G1 a real gap (check §9.13 and the model's kinds)? Are G2–G4 real, and is any gap missing?
6. **Prior work:** are S0149, S0162, S0034, S0033, S0036 and S0196 represented fairly? Is S0162's warning against a context-shaped architecture answered?

## Rules (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.
- Edit nothing in the repository. No corpus or book text to any outside service. Do not reproduce any private person's name.

## Report
- Tables: findings (ID · finding · severity: critical / high / normal · evidence · correction · R3 lines · resolved when); verdicts per context (its statement, its admission score, its ceiling); a verdict on §10.2.3.
- A summary of at most 10 lines, and one line on process independence.
- End with every file you opened and every command and search you ran.
- Deliver it once, beginning "SD2 REVIEW REPORT <A or B>".
